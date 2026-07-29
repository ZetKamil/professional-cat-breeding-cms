<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the biological gender of an animal.
 *
 * Used for filtering, display, and breeding logic.
 */
enum AnimalGender: string
{
    case Male = 'male';
    case Female = 'female';

    /**
     * Polish label for frontend display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Male => 'Kocur',
            self::Female => 'Kotka',
        };
    }

    /**
     * Symbol for compact display (e.g., animal cards).
     */
    public function symbol(): string
    {
        return match ($this) {
            self::Male => '♂',
            self::Female => '♀',
        };
    }
}
