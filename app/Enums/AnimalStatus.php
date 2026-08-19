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
    case Available = 'available';
    case Reserved = 'reserved';
    case Breeding = 'breeding';
    case Sold = 'sold';
    case Retired = 'retired';

    /**
     * Polish label for frontend display.
     */
    public function label(): string
    {
        return match ($this) {
            self::Available => 'Dostępny',
            self::Reserved => 'Zarezerwowany',
            self::Breeding => 'Kot Hodowlany',
            self::Sold => 'W nowym domu',
            self::Retired => 'Emeryt',
        };
    }

    /**
     * Polish label for filter navigation.
     */
    public function filterLabel(): string
    {
        return match ($this) {
            self::Available => 'Dostępne',
            self::Reserved => 'Zarezerwowane',
            self::Breeding => 'Koty Hodowlane (Rodzice)',
            self::Sold => 'W nowym domu',
            self::Retired => 'Emeryci',
        };
    }

    /**
     * Badge variant for the frontend component.
     * Maps to <x-frontend.badge variant="...">
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::Available => 'gold',
            self::Reserved => 'warning',
            self::Breeding => 'info',
            self::Sold, self::Retired => 'muted',
        };
    }

    /**
     * Whether this animal should be publicly visible on the website.
     */
    public function isPubliclyVisible(): bool
    {
        return match ($this) {
            self::Available, self::Reserved, self::Breeding, self::Sold => true,
            self::Retired => false,
        };
    }
}
