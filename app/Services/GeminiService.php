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
    private readonly int    $wallTimeout;

    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

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

        $latestAliases = [
            'latest',
            'flash-latest',
            'gemini-latest',
            'gemini-flash-latest',
        ];

        // Route shut down, empty, or shorthand 'latest' models to Google's official rolling alias 'gemini-flash-latest'
        if (empty($normalized) || in_array($normalized, $shutDownModels, true) || in_array($normalized, $latestAliases, true)) {
            $this->textModel = 'gemini-flash-latest';
        } else {
            $this->textModel = $normalized;
        }

        $this->imageModel  = (string) config('services.gemini.image_model', 'imagen-3.0-generate-002');
        $this->timeout     = (int) config('services.gemini.timeout', 20);
        $this->wallTimeout = max(10, (int) config('services.gemini.wall_timeout', 24));
    }

    private function hasValidApiKey(): bool
    {
        return ! blank($this->apiKey)
            && $this->apiKey !== '?'
            && ! str_contains($this->apiKey, 'YOUR_API_KEY');
    }

    /**
     * Assert API key is present before any network call.
     *
     * @throws GeminiApiKeyMissingException
     */
    private function assertKeyConfigured(): void
    {
        if (! $this->hasValidApiKey()) {
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
                $response = Http::connectTimeout(min(5, $effectiveTimeout))
                    ->timeout($effectiveTimeout)
                    ->withHeaders($headers)
                    ->post($url, $payload);

                // Retry on transient server errors (503 high demand, 502, 504)
                if ($attempt < $maxAttempts && in_array($response->status(), [503, 502, 504], true)) {
                    $backoffUs = 400000;
                    Log::warning("GeminiService: HTTP {$response->status()} on attempt {$attempt}/{$maxAttempts}. Backing off briefly before retrying...", [
                        'url' => $url,
                    ]);
                    usleep($backoffUs);
                    continue;
                }

                return $response;
            } catch (ConnectionException $e) {
                if ($attempt < $maxAttempts) {
                    Log::warning("GeminiService: Connection error on attempt {$attempt}/{$maxAttempts}. Retrying...");
                    usleep(300000);
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

        $currentMaxExec = (int) ini_get('max_execution_time');
        $desiredMaxExec = $this->wallTimeout + 30;
        if ($currentMaxExec > 0 && $currentMaxExec < $desiredMaxExec) {
            @set_time_limit($desiredMaxExec);
        }

        // Total wall-clock budget keeps synchronous Livewire requests under OVH/Apache gateway timeouts.
        $textTimeout = min($this->timeout, 18);
        $deadline = microtime(true) + $this->wallTimeout;

        $modelsToTry = array_values(array_unique(array_filter([
            $this->textModel,
            'gemini-flash-latest',
            'gemini-2.5-flash',
            'gemini-2.5-flash-lite',
        ])));

        $basePayload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => [
                ['parts' => [['text' => $userPrompt]]],
            ],
            'generationConfig' => [
                'temperature'      => 0.7,
                'maxOutputTokens'  => 3500,
                'responseMimeType' => 'application/json',
            ],
        ];

        $lastStatus       = 500;
        $lastErrorDetails = '';

        foreach ($modelsToTry as $model) {
            $remaining = (int) floor($deadline - microtime(true));
            if ($remaining < 4) {
                Log::warning('GeminiService: wall-clock budget exhausted before trying fallback model', [
                    'model' => $model,
                    'wall_timeout' => $this->wallTimeout,
                ]);
                break;
            }

            $url = self::BASE_URL . $model . ':generateContent';
            $payload = $basePayload;

            // Disable hidden chain-of-thought latency on Flash 2.5/latest models so drafts finish in seconds.
            $supportsZeroThinking = str_contains($model, 'flash');
            if ($supportsZeroThinking) {
                $payload['generationConfig']['thinkingConfig'] = ['thinkingBudget' => 0];
            }

            $attemptTimeout = min($textTimeout, max(6, $remaining));
            $maxAttempts = $remaining >= 14 ? 2 : 1;

            try {
                $response = $this->sendPostRequest($url, $payload, maxAttempts: $maxAttempts, timeout: $attemptTimeout);

                // Fallback transparently if model rejects thinkingConfig with HTTP 400
                if ($response->status() === 400 && isset($payload['generationConfig']['thinkingConfig'])) {
                    unset($payload['generationConfig']['thinkingConfig']);
                    $retryRemaining = (int) floor($deadline - microtime(true));
                    if ($retryRemaining >= 4) {
                        $response = $this->sendPostRequest($url, $payload, maxAttempts: 1, timeout: min($textTimeout, $retryRemaining));
                    }
                }
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
            $response = $this->sendPostRequest($url, $payload, maxAttempts: 1, timeout: 10);
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
     * Returns false when the API key is missing or contains an unconfigured placeholder.
     * Used by the Livewire component to disable the Generate button early.
     */
    public function isAvailable(): bool
    {
        return $this->hasValidApiKey();
    }

    /**
     * Extract and decode a JSON array returned by an LLM (stripping optional markdown fences).
     */
    public static function decodeJsonArray(string $raw): ?array
    {
        $cleaned = trim($raw);
        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\s*```$/', '', $cleaned) ?? $cleaned;
        $cleaned = trim($cleaned);

        $decoded = json_decode($cleaned, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        $start = strpos($cleaned, '[');
        $end = strrpos($cleaned, ']');
        if ($start !== false && $end !== false && $end > $start) {
            $slice = substr($cleaned, $start, $end - $start + 1);
            $decoded = json_decode($slice, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    /**
     * Decode and validate up to 6 SEO blog topic suggestions returned by Gemini.
     *
     * @return array<int, array{title: string, keyword: string, intent: string}>
     */
    public static function decodeTopicSuggestions(string $raw): array
    {
        $parsed = self::decodeJsonArray($raw);
        if (! is_array($parsed)) {
            return [];
        }

        $clean = [];
        foreach ($parsed as $item) {
            if (! is_array($item)) {
                continue;
            }
            $title = trim((string) ($item['title'] ?? ''));
            $keyword = trim((string) ($item['keyword'] ?? ''));
            if ($title === '' || $keyword === '') {
                continue;
            }
            $clean[] = [
                'title' => $title,
                'keyword' => $keyword,
                'intent' => trim((string) ($item['intent'] ?? 'informational')) ?: 'informational',
            ];
            if (count($clean) >= 6) {
                break;
            }
        }

        return $clean;
    }
}
