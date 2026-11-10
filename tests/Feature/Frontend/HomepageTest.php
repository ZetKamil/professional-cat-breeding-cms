<?php

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_successfully_with_luxury_editorial_sections(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Hodowla Kotów z Rodowodem Płock – Bengalskie, Brytyjskie, Syjamskie');
        $response->assertSee('SHiOZ ZOOLANDIA');
        $response->assertSee('Badania Genetyczne Rodziców');
        $response->assertSee('Dostępne Kocięta z Rodowodem');
        $response->assertSee('Domowa Socjalizacja');
        $response->assertSee('Chów w sercu rodziny, 100% życia z nami');
        $response->assertSee('Napisz lub umów wideo-wizytę');
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
        $response->assertSee('Napisz lub umów wideo-wizytę');
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
        $response->assertSee('Hodowla kotów z rodowodem (Płock):');
        $response->assertSee('Bengalskie, Brytyjskie i Syjamskie.');
        $response->assertSee('Badania genetyczne rodziców (HCM/PKD), pełne wsparcie i chów w sercu rodziny.');
        $response->assertSee('🐾 Zobacz dostępne kocięta');
        $response->assertSee('✨ Quiz: Dobierz rasę (45s)');
        $response->assertSee(route('quiz'));
        $response->assertSee('bars_studio.jpg');
    }

    public function test_homepage_and_cattery_include_local_plock_seo_and_ai_overview_entity_box(): void
    {
        $expectedTitle = 'Hodowla Kotów z Rodowodem Płock – Bengalskie, Brytyjskie, Syjamskie';
        $expectedDescription = 'Hodowla kotów rasowych z rodowodem – Płock / Sikorz (SHiOZ ZOOLANDIA). Kocięta bengalskie, brytyjskie i syjamskie z rodowodem, mikroczipem i kompletem szczepień.';

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee("<title>{$expectedTitle}</title>", false);
        $homeResponse->assertSee('content="'.$expectedDescription.'"', false);

        $entityItems = [
            'Hodowla:',
            'Mazowiecka Szwajcaria (Hodowla Kotów Rasowych z Rodowodem)',
            'Rasy:',
            'Kot Bengalski, Kot Brytyjski Krótkowłosy, Kot Syjamski (Tajski)',
            'Zrzeszenie:',
            'SHiOZ ZOOLANDIA (Polska Federacja Felinologiczna)',
            'Lokalizacja:',
            'Sikorz / Płock (09-413, zaledwie 10 minut od centrum Płocka, woj. mazowieckie)',
            'Szczegóły:',
            'Kocięta opuszczają hodowlę w pełni zsocjalizowane w warunkach domowych, z wielopokoleniowym rodowodem, mikroczipem, książeczką zdrowia (komplet szczepień i odrobaczeń) oraz badaniami genetycznymi rodziców (HCM/PKD).',
        ];

        $catteryResponse = $this->get(route('cattery'));
        $catteryResponse->assertOk();

        foreach ([$homeResponse, $catteryResponse] as $response) {
            foreach ($entityItems as $text) {
                $response->assertSee($text);
            }
            $response->assertSee('📍 Lokalizacja: Sikorz k. Płocka (10 km od centrum Płocka, dogodny dojazd z Warszawy, Łodzi i Torunia)');
            $response->assertSee('Hodowla kotów Płock / Sikórz');
            $response->assertSee('Hodowla kotów Warszawa');
        }
    }

    public function test_homepage_outputs_upgraded_plock_local_business_organization_json_ld(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();

        $content = $response->getContent();
        preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches[1]);

        $schema = json_decode(trim($matches[1][0]), true);
        $this->assertIsArray($schema);
        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame(['LocalBusiness', 'Organization'], $schema['@type']);
        $this->assertSame('Hodowla Kotów z Mazowieckiej Szwajcarii – Płock', $schema['name']);
        $this->assertSame('Płock / Sikorz', $schema['address']['addressLocality']);
        $this->assertSame('09-413', $schema['address']['postalCode']);
        $this->assertSame('Mazowieckie', $schema['address']['addressRegion']);

        $servedCities = array_column($schema['areaServed'], 'name');
        $this->assertSame(['Płock', 'Warszawa', 'Łódź', 'Toruń', 'Bydgoszcz', 'Poznań'], $servedCities);
        $this->assertContains('Koty rasowe z rodowodem', $schema['knowsAbout']);
    }
}
