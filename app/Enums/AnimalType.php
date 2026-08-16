<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the type of animal in the system.
 *
 * Currently limited to cats, but the architecture uses "Animal"
 * generically to support potential future expansion.
 */
enum AnimalType: string
{
    case Cat = 'cat';

    /**
     * Polish label for frontend display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Cat => 'Kot',
        };
    }
}
