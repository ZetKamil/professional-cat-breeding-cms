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
        $response->assertSee('Hodowla Kotów Rasowych z Rodowodem');
        $response->assertSee('SHiOZ ZOOLANDIA');
        $response->assertSee('Badania Genetyczne Rodziców');
        $response->assertSee('Dostępne Kocięta i Mioty');
        $response->assertSee('Domowa Socjalizacja');
        $response->assertSee('Zero klatek, 100% życia z nami');
        $response->assertSee('Umów wideo-rozmowę na żywo');
    }

    public function test_cattery_page_renders_successfully_with_philosophy_code_and_process_journey(): void
    {
        $response = $this->get(route('cattery'));

        $response->assertOk();
        $response->assertSee('Filozofia Naszej Hodowli');
        $response->assertSee('Kodeks Zaufania');
        $response->assertSee('Jak wygląda proces zakupu kocięcia?');
        $response->assertSee('Proces rozpoczyna się od kontaktu');
        $response->assertSee('Kontakt i Wybór Kocięcia');
        $response->assertSee('Wizyta w Hodowli');
        $response->assertSee('Umowa i Odbiór');
        $response->assertSee('Historie Opiekunów Naszych Kotów');
        $response->assertSee('Umów wideo-rozmowę na żywo');
    }

    public function test_homepage_renders_featured_animals_when_available(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            Animal::factory()->create([
                'name' => "Kociak Numer {$i}",
                'is_featured' => true,
                'is_published' => true,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertOk();
        for ($i = 1; $i <= 6; $i++) {
            $response->assertSee("Kociak Numer {$i}");
        }
    }

    public function test_homepage_does_not_render_blog_section(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('home-section--blog');
    }

    public function test_homepage_renders_mobile_hero_optimizations(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('SHiOZ ZOOLANDIA • MAZOWIECKA SZWAJCARIA');
        $response->assertSee('Koty Bengalskie, Brytyjskie');
        $response->assertSee('i Syjamskie z Rodowodem.');
        $response->assertSee('Domowa socjalizacja, badania genetyczne rodziców (HCM/PKD) i pełne wsparcie.');
        $response->assertSee('🐾 Zobacz dostępne kocięta');
        $response->assertSee('✨ Quiz: Dobierz rasę (45s)');
        $response->assertSee(route('quiz'));
        $response->assertSee('bars_studio.jpg');
    }
}
