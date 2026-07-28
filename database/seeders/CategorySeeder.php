<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * We maken hier een vaste basisset categories aan
     * zodat:
     * - de category module meteen testdata heeft
     * - posts later aan categories gekoppeld kunnen worden
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laravel',
                'description' => 'Artikels over het Laravel framework, routing, controllers, Blade, Eloquent en meer.',
            ],
            [
                'name' => 'PHP',
                'description' => 'Artikels over PHP, objectgeoriënteerd programmeren, syntax en best practices.',
            ],
            [
                'name' => 'JavaScript',
                'description' => 'Artikels over JavaScript, frontend interactie en moderne webontwikkeling.',
            ],
            [
                'name' => 'Vue.js',
                'description' => 'Artikels over Vue.js componenten, state en frontend architectuur.',
            ],
            [
                'name' => 'Livewire',
                'description' => 'Artikels over Laravel Livewire, interactieve componenten en server-driven UI.',
            ],
            [
                'name' => 'MySQL',
                'description' => 'Artikels over relationele databases, query optimalisatie en datamodellering.',
            ],
            [
                'name' => 'AI',
                'description' => 'Artikels over artificiële intelligentie, automatisatie en slimme workflows.',
            ],
            [
                'name' => 'SEO',
                'description' => 'Artikels over zoekmachineoptimalisatie, contentstructuur en online zichtbaarheid.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
