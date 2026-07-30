<?php

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_successfully_with_luxury_editorial_sections(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Etyczna Hodowla Kotów Rasowych');
        $response->assertSee('Certyfikat FIFe / FPL');
        $response->assertSee('100% Badania Genetyczne');
        $response->assertSee('Nasze Specjalizacje Rasy');
        $response->assertSee('Koty Bengalskie');
        $response->assertSee('Brytyjczyki');
        $response->assertSee('Koty Syjamskie');
        $response->assertSee('Jak wygląda proces adopcji?');
        $response->assertSee('Rozmowa i Dobór');
        $response->assertSee('Rezerwacja i Wizyta');
        $response->assertSee('Odbiór i Wyprawka');
        $response->assertSee('Nasza czytelnia — wkrótce nowe publikacje');
    }

    public function test_homepage_renders_featured_animals_when_available(): void
    {
        Animal::factory()->create([
            'name' => 'Luna Royal Bengal',
            'is_featured' => true,
            'is_published' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Luna Royal Bengal');
    }
}
