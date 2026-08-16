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
        // Clean existing records (including soft-deleted) before seeding to prevent unique slug collisions
        \Illuminate\Support\Facades\DB::table('animals')->delete();

        // ── Featured Available Kittens (shown on homepage) ────────────
        Animal::factory()
            ->available()
            ->featured()
            ->female()
            ->kitten()
            ->create([
                'name' => 'Luna',
                'slug' => 'luna',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy cętkowany (Brown Spotted)',
                'short_description' => 'Czuła, energiczna koteczka bengalska o przepięknych cętkach. Gotowa do adopcji.',
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
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieski',
                'short_description' => 'Spokojny, pluszowy kocurek brytyjski z silnym rodowodem. Uwielbia zabawę i przytulanie.',
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
                'breed' => 'Kot Syjamski',
                'color' => 'Seal point',
                'short_description' => 'Elegancka, towarzyska koteczka syjamska o szafirowych oczach. Idealna dla rodziny.',
                'sort_order' => 3,
            ]);

        // ── Breeding Adults ──────────────────────────────────────────
        $queen = Animal::factory()
            ->breeding()
            ->female()
            ->create([
                'name' => 'Bella',
                'slug' => 'bella',
                'breed' => 'Kot Brytyjski',
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
                'breed' => 'Kot Brytyjski',
                'color' => 'Czekoladowy',
                'date_of_birth' => now()->subYears(4),
                'short_description' => 'Utytułowany reproduktor z doskonałym rodowodem stowarzyszenia.',
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
                'breed' => 'Kot Bengalski',
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
                'breed' => 'Kot Brytyjski',
                'color' => 'Czarny',
                'short_description' => 'Szczęśliwie zamieszkał w Warszawie.',
                'sort_order' => 20,
                'mother_id' => $queen->id,
                'father_id' => $stud->id,
            ]);

    }
}
