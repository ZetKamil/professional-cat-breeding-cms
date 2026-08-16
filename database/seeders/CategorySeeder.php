<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Katedra kategorii dla hodowli kotów rasowych ZetKamil.
     */
    public function run(): void
    {
        // Czyść tabele przed ponownym seedowaniem (idempotentność)
        DB::table('category_post')->delete();
        DB::table('categories')->delete();

        $categories = [
            [
                'name' => 'Zdrowie i Genetyka',
                'description' => 'Badania genetyczne (HCM, PKD, PRA), certyfikaty weterynaryjne i najwyższe standardy profilaktyki zdrowotnej.',
            ],
            [
                'name' => 'Wyprawka i Pielęgnacja',
                'description' => 'Kompletne poradniki dotyczące przygotowania domu na przyjęcie kocięcia oraz doboru akcesoriów premium.',
            ],
            [
                'name' => 'Odmiany i Rasy',
                'description' => 'Szczegółowe charakterystyki naszych ras: Kot Bengalski, Kot Brytyjski oraz Kot Syjamski.',
            ],
            [
                'name' => 'Socjalizacja i Wychowanie',
                'description' => 'Rozwój emocjonalny kociąt w pierwszych 12 tygodniach życia, adaptacja oraz behawiorystyka.',
            ],
            [
                'name' => 'Żywienie Holistyczne',
                'description' => 'Dieta BARF, najwyższej jakości karmy mokre oraz zasady optymalnego żywienia kotów rasowych.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
