<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnimalTest extends TestCase
{
    use RefreshDatabase;

    public function test_animal_catalog_page_can_be_rendered(): void
    {
        Animal::factory()->create([
            'name' => 'Luna',
            'breed' => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty');

        $response->assertOk();
        $response->assertSee('Nasze Koty');
        $response->assertSee('Kot Bengalski');
        $response->assertSee('Kot Brytyjski');
        $response->assertSee('Kot Syjamski');
        $response->assertSee('Luna');
    }

    public function test_animal_catalog_filters_by_breed(): void
    {
        Animal::factory()->create([
            'name' => 'BengalCat',
            'breed' => 'Kot Bengalski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Animal::factory()->create([
            'name' => 'BritishCat',
            'breed' => 'Kot Brytyjski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty?breed=Kot+Bengalski');

        $response->assertOk();
        $response->assertSee('BengalCat');
        $response->assertDontSee('BritishCat');
    }

    public function test_animal_profile_page_can_be_rendered(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Simba',
            'slug' => 'simba',
            'breed' => 'Kot Brytyjski',
            'color' => 'Niebieski',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/koty/simba');

        $response->assertOk();
        $response->assertSee('Simba');
        $response->assertSee('Kot Brytyjski');
        $response->assertSee('Niebieski');
        $response->assertSee('Badania Genetyczne');
        $response->assertSee('Strona Główna');
        $response->assertSee('Nasze Koty');
    }

    public function test_unpublished_animal_returns_404(): void
    {
        Animal::factory()->create([
            'name' => 'Secret',
            'slug' => 'secret',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/koty/secret');

        $response->assertNotFound();
    }
}
