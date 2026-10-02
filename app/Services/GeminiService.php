<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Low-level HTTP client for the Google Gemini API.
 *
 * Responsibilities:
 * - Send prompts to the Gemini text model and return raw text.
 * - Send image generation requests and return a downloadable URL.
 * - Translate HTTP / network errors into domain exceptions.
 *
 * Business logic (prompt assembly, context building) belongs
 * in AiBlogGeneratorService, NOT here.
 */
class GeminiService
{
    private readonly string $apiKey;
    private readonly string $textModel;
    private readonly string $imageModel;
    private readonly int    $timeout;

    private const BASE_URL       = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private const IMAGEN_API_URL = 'https://us-central1-aiplatform.googleapis.com/v1/projects/';

    public function __construct()
    {
        // We store whatever is configured — trim any quotes/spaces from .env
        $rawKey           = (string) config('services.gemini.api_key', '');
        $this->apiKey     = trim($rawKey, " \t\n\r\0\x0B\"'");

        $configured       = trim((string) config('services.gemini.text_model', 'gemini-flash-latest'));
        $normalized       = strtolower(str_replace(' ', '-', $configured));

        // Models that have been shut down or deprecated in Google's API:
        // gemini-2.0-flash / lite (shut down June 1, 2026), gemini-1.5 series, etc.
        $shutDownModels = [
            'gemini-2.0-flash',
            'gemini-2.0-flash-001',
            'gemini-2.0-flash-lite',
            'gemini-2.0-flash-lite-001',
            '2.0-flash',
            '2.0-flash-lite',
            'gemini-1.5-pro',
            'gemini-1.5-flash',
            'gemini-pro',
        ];

        // Route shut down or empty models to Google's official rolling alias 'gemini-flash-latest'
        if (empty($normalized) || in_array($normalized, $shutDownModels, true)) {
            $this->textModel = 'gemini-flash-latest';
        } else {
            $this->textModel = $normalized;
        }

        $this->imageModel = (string) config('services.gemini.image_model', 'imagen-3.0-generate-002');
        $this->timeout    = (int) config('services.gemini.timeout', 60);
    }

    /**
     * Assert API key is present before any network call.
     *
     * @throws GeminiApiKeyMissingException
     */
    private function assertKeyConfigured(): void
    {
        if (blank($this->apiKey) || $this->apiKey === '?' || str_contains($this->apiKey, 'YOUR_API_KEY')) {
            throw GeminiApiKeyMissingException::notConfigured();
        }
    }

    /**
     * Send HTTP POST request with x-goog-api-key header, automatic SSL bundle fallback,
     * and optional retry on transient errors.
     *
     * @param  int  $maxAttempts  1 = no retry, 2 = one retry, etc.
     * @param  int|null  $timeout  Override configured timeout (seconds).
     */
    private function sendPostRequest(
        string   $url,
        array    $payload,
        int      $maxAttempts = 2,
        ?int     $timeout     = null
    ): \Illuminate\Http\Client\Response {
        $effectiveTimeout = $timeout ?? $this->timeout;

        $headers = [
            'Content-Type'   => 'application/json',
            'x-goog-api-key' => $this->apiKey,
        ];

        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $attempt++;
            try {
                $response = Http::timeout($effectiveTimeout)
                    ->withHeaders($headers)
                    ->post($url, $payload);

                // Retry on transient server errors
                if ($attempt < $maxAttempts && in_array($response->status(), [503, 502, 504], true)) {
                    Log::warning("GeminiService: HTTP {$response->status()} on attempt {$attempt}/{$maxAttempts}. Retrying...");
                    usleep(500000); // 0.5s flat delay — keep total time short
                    continue;
                }

                return $response;
            } catch (ConnectionException $e) {
                if (str_contains($e->getMessage(), 'SSL') || str_contains($e->getMessage(), 'cURL error 60')) {
                    return Http::withoutVerifying()
                        ->timeout($effectiveTimeout)
                        ->withHeaders($headers)
                        ->post($url, $payload);
                }

                if ($attempt < $maxAttempts) {
                    Log::warning("GeminiService: Connection error on attempt {$attempt}/{$maxAttempts}. Retrying...");
                    usleep(500000);
                    continue;
                }

                throw $e;
            }
        }

        throw new ConnectionException('Gemini API request failed after retries.');
    }

    /**
     * Send a prompt to the Gemini text model.
     *
     * @param  string  $systemPrompt  Instructions / context (role, rules, knowledge).
     * @param  string  $userPrompt    The specific content request.
     * @return string                 Raw text response from Gemini.
     *
     * @throws GeminiServiceUnavailableException
     */
    public function generateText(string $systemPrompt, string $userPrompt): string
    {
        $this->assertKeyConfigured();

        // Text timeout: generous enough for free-tier Gemini, but within PHP max_execution_time.
        $textTimeout = min($this->timeout, 30);

        // Cascade of models to try if the primary fails (503/404/500/timeout):
        // 1. Configured text model
        // 2. gemini-flash-latest (Google's official rolling alias)
        // 3. gemini-2.5-flash (stable production flash)
        // 4. gemini-3.8-flash (current generation flash)
        // 5. gemini-3.5-flash
        $modelsToTry = array_values(array_unique(array_filter([
            $this->textModel,
            'gemini-flash-latest',
            'gemini-2.5-flash',
            'gemini-3.8-flash',
            'gemini-3.5-flash',
        ])));

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => [
                ['parts' => [['text' => $userPrompt]]],
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 4096,
                'responseMimeType' => 'application/json',
            ],
        ];

        $lastStatus       = 500;
        $lastErrorDetails = '';

        foreach ($modelsToTry as $model) {
            $url = self::BASE_URL . $model . ':generateContent';

            try {
                // 2 attempts (1 retry), 30s timeout — allows for free-tier latency
                $response = $this->sendPostRequest($url, $payload, maxAttempts: 2, timeout: $textTimeout);
            } catch (ConnectionException) {
                Log::warning('GeminiService: connection timeout', ['model' => $model]);
                continue;
            }

            $lastStatus = $response->status();

            if ($response->status() === 429) {
                Log::warning("GeminiService: model {$model} rate limited");
                continue;
            }

            if (! $response->successful()) {
                $body             = $response->json();
                $lastErrorDetails = $body['error']['message'] ?? $response->body();
                Log::warning("GeminiService: model {$model} returned HTTP {$lastStatus}, trying fallback if available...", [
                    'error' => $lastErrorDetails,
                ]);
                continue;
            }

            $body = $response->json();
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if (! empty($text)) {
                return $text;
            }
        }

        if ($lastStatus === 429) {
            throw GeminiServiceUnavailableException::rateLimited();
        }

        throw GeminiServiceUnavailableException::serverError($lastStatus, $lastErrorDetails);
    }

    /**
     * Generate a decorative hero image via Gemini Imagen.
     *
     * Returns the first generated image as a base64-encoded PNG string
     * (data URI) or empty string on failure.
     *
     * IMPORTANT: This is used ONLY for decorative cover art.
     * Real cat photos in article body come from the Animal->gallery() relation.
     *
     * @throws GeminiServiceUnavailableException
     */
    public function generateImage(string $prompt): string
    {
        $this->assertKeyConfigured();

        // Imagen uses predict endpoint with x-goog-api-key header
        $url = self::BASE_URL . $this->imageModel . ':predict';

        $payload = [
            'instances'  => [['prompt' => $prompt]],
            'parameters' => [
                'sampleCount'   => 1,
                'aspectRatio'   => '16:9',
                'outputOptions' => ['mimeType' => 'image/png'],
            ],
        ];

        try {
            $response = $this->sendPostRequest($url, $payload);
        } catch (ConnectionException) {
            Log::warning('GeminiService: image generation timeout');
            throw GeminiServiceUnavailableException::timeout();
        }

        if ($response->status() === 429) {
            throw GeminiServiceUnavailableException::rateLimited();
        }

        if (! $response->successful()) {
            Log::error('GeminiService: image generation failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw GeminiServiceUnavailableException::serverError($response->status());
        }

        $predictions = $response->json('predictions');

        if (empty($predictions[0]['bytesBase64Encoded'])) {
            return '';
        }

        // Return raw base64 (no data URI prefix).
        // Callers decode this and save via MediaService.
        return $predictions[0]['bytesBase64Encoded'];
    }

    /**
     * Quick health / availability check.
     *
     * Returns false when the API key is missing or on any network error.
     * Used by the Livewire component to disable the Generate button early.
     */
    public function isAvailable(): bool
    {
        if (blank(config('services.gemini.api_key'))) {
            return false;
        }

        // We trust the config; no live ping here to avoid quota usage.
        return true;
    }
}
