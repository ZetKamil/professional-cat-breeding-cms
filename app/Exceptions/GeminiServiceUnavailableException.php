<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when Gemini API is unavailable (503, timeout, rate-limit).
 *
 * Caught in the Livewire component to show a friendly error message
 * instead of crashing with HTTP 500.
 */
class GeminiServiceUnavailableException extends RuntimeException
{
    public static function timeout(): self
    {
        return new self(
            'Gemini API did not respond within the configured timeout. Please try again.'
        );
    }

    public static function rateLimited(): self
    {
        return new self(
            'Gemini API rate limit reached. Please wait a moment and try again.'
        );
    }

    public static function serverError(int $statusCode, string $details = ''): self
    {
        $message = "Gemini API returned HTTP {$statusCode}";
        if (! empty($details)) {
            $message .= " ({$details})";
        } else {
            $message .= ". Please try again later.";
        }
        return new self($message);
    }
}
