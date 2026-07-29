<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory for the Animal model.
 *
 * Generates realistic data for testing and seeding.
 * Default state: published, available cat with a birth date.
 *
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    /**
     * Realistic cat names for a Polish breeding cattery.
     */
    private const NAMES = [
        'Luna', 'Simba', 'Mia', 'Leo', 'Bella', 'Oliver',
        'Cleo', 'Max', 'Nala', 'Felix', 'Kicia', 'Mruczek',
        'Puszek', 'Filemon', 'Zuzia', 'Tofik', 'Figa', 'Bursztyn',
    ];

    /**
     * Realistic coat colors for British Shorthair cats.
     */
    private const COLORS = [
        'Niebieski', 'Liliowy', 'Czekoladowy', 'Czarny',
        'Kremowy', 'Biały', 'Cynamonowy', 'Srebrny tabby',
        'Złoty', 'Blue point', 'Lilac point',
    ];

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::NAMES);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'breed' => 'Kot Brytyjski Krótkowłosy',
            'color' => fake()->randomElement(self::COLORS),
            'gender' => fake()->randomElement(AnimalGender::cases()),
            'status' => AnimalStatus::Available,
            'type' => AnimalType::Cat,
            'date_of_birth' => fake()->dateTimeBetween('-3 years', '-3 months'),
            'description' => fake('pl_PL')->paragraphs(2, true),
            'short_description' => fake('pl_PL')->sentence(10),
            'is_featured' => false,
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => 0,
        ];
    }

    // ─── State Methods ──────────────────────────────────────────────

    public function featured(): static
    {
        return $this->state(fn () => [
            'is_featured' => true,
        ]);
    }

    public function breeding(): static
    {
        return $this->state(fn () => [
            'status' => AnimalStatus::Breeding,
        ]);
    }

    public function available(): static
    {
        return $this->state(fn () => [
            'status' => AnimalStatus::Available,
        ]);
    }

    public function reserved(): static
    {
        return $this->state(fn () => [
            'status' => AnimalStatus::Reserved,
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn () => [
            'status' => AnimalStatus::Sold,
        ]);
    }

    public function retired(): static
    {
        return $this->state(fn () => [
            'status' => AnimalStatus::Retired,
        ]);
    }

    public function male(): static
    {
        return $this->state(fn () => [
            'gender' => AnimalGender::Male,
        ]);
    }

    public function female(): static
    {
        return $this->state(fn () => [
            'gender' => AnimalGender::Female,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function kitten(): static
    {
        return $this->state(fn () => [
            'date_of_birth' => fake()->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }
}
