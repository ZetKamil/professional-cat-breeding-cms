<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Represents the lifecycle status of an animal in the breeding program.
 *
 * Used for filtering, display badges, and business logic decisions.
 * Stored as a string-backed enum for readable database values.
 */
enum AnimalStatus: string
{
    case Breeding = 'breeding';
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';
    case Retired = 'retired';

    /**
     * Polish label for frontend display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Breeding => 'Hodowlany',
            self::Available => 'Dostępny',
            self::Reserved => 'Zarezerwowany',
            self::Sold => 'W nowym domu',
            self::Retired => 'Na emeryturze',
        };
    }

    /**
     * Badge variant for the frontend component.
     * Maps to <x-frontend.badge variant="...">
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::Breeding => 'info',
            self::Available => 'success',
            self::Reserved => 'warning',
            self::Sold => 'muted',
            self::Retired => 'muted',
        };
    }

    /**
     * Whether this animal should be publicly visible on the website.
     */
    public function isPubliclyVisible(): bool
    {
        return match ($this) {
            self::Breeding, self::Available, self::Reserved => true,
            self::Sold, self::Retired => false,
        };
    }
}
