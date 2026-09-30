<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when the Gemini API key is missing or not configured.
 *
 * This is a configuration error — set GEMINI_API_KEY in .env
 * before using the AI Blog Generator.
 */
class GeminiApiKeyMissingException extends RuntimeException
{
    public static function notConfigured(): self
    {
        return new self(
            'Gemini API key is not configured. Set GEMINI_API_KEY in .env'
        );
    }
}