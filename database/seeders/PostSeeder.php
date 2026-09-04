<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Importuje wszystkie 19 pakietów artykułów SEO z katalogu BLOG/
     * z zachowaniem ustalonego harmonogramu niedzielnego (niedziele o 10:00).
     */
    public function run(): void
    {
        Artisan::call('seo:import-articles');
    }
}
