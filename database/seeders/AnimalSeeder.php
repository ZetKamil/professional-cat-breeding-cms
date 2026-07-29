<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Seeder;

/**
 * Seeds the animals table with a representative dataset.
 *
 * Creates 8 animals with varied statuses to demonstrate
 * all lifecycle states. Three are marked as featured
 * for the homepage card grid.
 */
class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        // ── Featured Available Kittens (shown on homepage) ────────────
        Animal::factory()
            ->available()
            ->featured()
            ->female()
            ->kitten()
            ->create([
                'name' => 'Luna',
                'slug' => 'luna',
                'color' => 'Niebieski',
                'short_description' => 'Czuła, spokojna koteczka o pięknych oczach. Gotowa do adopcji.',
                'sort_order' => 1,
            ]);

        Animal::factory()
            ->available()
            ->featured()
            ->male()
            ->kitten()
            ->create([
                'name' => 'Simba',
                'slug' => 'simba',
                'color' => 'Liliowy',
                'short_description' => 'Energiczny kocurek z silnym rodowodem. Uwielbia zabawę.',
                'sort_order' => 2,
            ]);

        Animal::factory()
            ->available()
            ->featured()
            ->female()
            ->kitten()
            ->create([
                'name' => 'Mia',
                'slug' => 'mia',
                'color' => 'Kremowy',
                'short_description' => 'Delikatna i towarzyska. Idealna towarzyszka dla rodziny.',
                'sort_order' => 3,
            ]);

        // ── Breeding Adults ──────────────────────────────────────────
        $queen = Animal::factory()
            ->breeding()
            ->female()
            ->create([
                'name' => 'Bella',
                'slug' => 'bella',
                'color' => 'Niebieski',
                'date_of_birth' => now()->subYears(3)->subMonths(2),
                'short_description' => 'Nasza piękna królowa hodowlana. Matka wielu wspaniałych miotów.',
                'sort_order' => 10,
            ]);

        $stud = Animal::factory()
            ->breeding()
            ->male()
            ->create([
                'name' => 'Oliver',
                'slug' => 'oliver',
                'color' => 'Czekoladowy',
                'date_of_birth' => now()->subYears(4),
                'short_description' => 'Champion FIFe. Silny, zdrowy reproduktor z doskonałym rodowodem.',
                'sort_order' => 11,
            ]);

        // ── Reserved ─────────────────────────────────────────────────
        Animal::factory()
            ->reserved()
            ->female()
            ->kitten()
            ->create([
                'name' => 'Cleo',
                'slug' => 'cleo',
                'color' => 'Srebrny tabby',
                'short_description' => 'Zarezerwowana dla nowej rodziny. Przeprowadzka wkrótce.',
                'sort_order' => 4,
            ]);

        // ── Sold (alumni) ────────────────────────────────────────────
        Animal::factory()
            ->sold()
            ->male()
            ->create([
                'name' => 'Felix',
                'slug' => 'felix',
                'color' => 'Czarny',
                'short_description' => 'Szczęśliwie zamieszkał w Warszawie.',
                'sort_order' => 20,
                'mother_id' => $queen->id,
                'father_id' => $stud->id,
            ]);

        // ── Retired ──────────────────────────────────────────────────
        Animal::factory()
            ->retired()
            ->female()
            ->create([
                'name' => 'Zuzia',
                'slug' => 'zuzia',
                'color' => 'Liliowy',
                'date_of_birth' => now()->subYears(7),
                'short_description' => 'Nasza emerytka. Cieszy się zasłużonym odpoczynkiem w domu.',
                'sort_order' => 30,
            ]);
    }
}
