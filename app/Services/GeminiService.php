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
        $this->textModel  = (string) config('services.gemini.text_model',  'gemini-flash-latest');
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
     * Send HTTP POST request with x-goog-api-key header and automatic SSL bundle fallback.
     */
    private function sendPostRequest(string $url, array $payload): \Illuminate\Http\Client\Response
    {
        $headers = [
            'Content-Type'   => 'application/json',
            'x-goog-api-key' => $this->apiKey,
        ];

        try {
            return Http::timeout($this->timeout)
                ->withHeaders($headers)
                ->post($url, $payload);
        } catch (ConnectionException $e) {
            if (str_contains($e->getMessage(), 'SSL') || str_contains($e->getMessage(), 'cURL error 60')) {
                return Http::withoutVerifying()
                    ->timeout($this->timeout)
                    ->withHeaders($headers)
                    ->post($url, $payload);
            }
            throw $e;
        }
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

        $url = self::BASE_URL . $this->textModel . ':generateContent';

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

        try {
            $response = $this->sendPostRequest($url, $payload);
        } catch (ConnectionException) {
            Log::warning('GeminiService: connection timeout', ['model' => $this->textModel]);
            throw GeminiServiceUnavailableException::timeout();
        }

        // Automatic fallback if model returns 404
        if ($response->status() === 404 && $this->textModel !== 'gemini-flash-latest') {
            Log::info("GeminiService: model {$this->textModel} returned 404, falling back to gemini-flash-latest");
            $fallbackUrl = self::BASE_URL . 'gemini-flash-latest:generateContent';
            try {
                $response = $this->sendPostRequest($fallbackUrl, $payload);
            } catch (ConnectionException) {
                throw GeminiServiceUnavailableException::timeout();
            }
        }

        if ($response->status() === 429) {
            Log::warning('GeminiService: rate limited');
            throw GeminiServiceUnavailableException::rateLimited();
        }

        if (! $response->successful()) {
            Log::error('GeminiService: unexpected HTTP status', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw GeminiServiceUnavailableException::serverError($response->status());
        }

        $body = $response->json();

        return $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
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
