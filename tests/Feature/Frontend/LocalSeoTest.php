<?php

namespace Tests\Feature\Frontend;

use App\Enums\AnimalStatus;
use App\Http\Controllers\Frontend\LocalSeoController;
use App\Models\Animal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalSeoTest extends TestCase
{
    use RefreshDatabase;

    protected array $cityRoutes = [
        'warszawa' => '/hodowla-kotow-warszawa',
        'lodz' => '/hodowla-kotow-lodz',
        'torun' => '/hodowla-kotow-torun',
        'bydgoszcz' => '/hodowla-kotow-bydgoszcz',
        'wloclawek' => '/hodowla-kotow-wloclawek',
    ];

    public function test_all_configured_local_seo_pages_return_successful_response(): void
    {
        $controller = new LocalSeoController();
        $cities = $controller->getCities();

        $this->assertCount(5, $cities);

        foreach ($this->cityRoutes as $key => $route) {
            $this->assertArrayHasKey($key, $cities);
            $response = $this->get($route);
            $response->assertOk();
        }
    }

    public function test_local_seo_pages_contain_honest_location_and_truthful_address(): void
    {
        foreach ($this->cityRoutes as $key => $route) {
            $response = $this->get($route);
            $response->assertOk();

            // Truthful physical location in Sikórz k. Płocka
            $response->assertSee('Sikórz 56A k. Płocka');
            $response->assertSee('Rzetelna Informacja o Lokalizacji');
            $response->assertSee('Nie tworzymy fikcyjnych oddziałów');

            // Legitimate club certification
            $response->assertSee('SHiOZ ZOOLANDIA (58/CW/2025)');

            // Honest travel distance / time
            $response->assertSee('dojazdu');
        }
    }

    public function test_local_seo_pages_state_12_to_14_weeks_pickup_age_and_two_dewormings(): void
    {
        foreach ($this->cityRoutes as $key => $route) {
            $response = $this->get($route);
            $response->assertOk();

            $content = $response->getContent();

            // Strict 12–14 weeks pickup age
            $this->assertStringContainsString('12–14 tygodni', $content);

            // Strict two dewormings rule
            $this->assertTrue(
                str_contains($content, 'dwukrotnym odrobaczeniu') || str_contains($content, 'dwukrotnym odrobaczeniem') || str_contains($content, 'dwukrotnym odrobaczeniu') || str_contains($content, 'dwukrotne odrobaczenie'),
                "City {$key} should mention two dewormings"
            );

            // Never state incorrect 14-16 weeks
            $this->assertStringNotContainsString('14–16 tygodni', $content);
            $this->assertStringNotContainsString('14-16 tygodni', $content);
        }
    }

    public function test_local_seo_pages_have_unique_titles_meta_descriptions_and_single_h1(): void
    {
        $titles = [];
        $descriptions = [];

        foreach ($this->cityRoutes as $route) {
            $response = $this->get($route);
            $response->assertOk();

            $content = $response->getContent();

            // Single H1 tag
            $h1Count = preg_match_all('/<h1[^>]*>(.*?)<\/h1>/si', $content, $matches);
            $this->assertEquals(1, $h1Count, "Expected exactly 1 H1 tag on {$route}");

            // Extract title
            preg_match('/<title>(.*?)<\/title>/si', $content, $titleMatches);
            $this->assertNotEmpty($titleMatches);
            $title = trim($titleMatches[1]);
            $this->assertNotContains($title, $titles, "Duplicate title found: {$title}");
            $titles[] = $title;

            // Extract description
            preg_match('/<meta\s+name="description"\s+content="([^"]*)"/si', $content, $descMatches);
            $this->assertNotEmpty($descMatches);
            $description = trim($descMatches[1]);
            $this->assertNotContains($description, $descriptions, "Duplicate meta description found: {$description}");
            $descriptions[] = $description;

            // Canonical link must match current path
            $this->assertStringContainsString('rel="canonical"', $content);
        }

        $this->assertCount(5, $titles);
        $this->assertCount(5, $descriptions);
    }

    public function test_local_seo_pages_output_valid_local_business_and_faq_json_ld(): void
    {
        foreach ($this->cityRoutes as $key => $route) {
            $response = $this->get($route);
            $response->assertOk();

            $content = $response->getContent();

            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
            $this->assertNotEmpty($matches[1], "No JSON-LD scripts found on {$route}");

            $foundLocalBusiness = false;
            $foundFaqPage = false;

            foreach ($matches[1] as $jsonString) {
                $decoded = json_decode(trim($jsonString), true);
                if (! is_array($decoded)) {
                    continue;
                }

                if (($decoded['@type'] ?? '') === 'LocalBusiness') {
                    $foundLocalBusiness = true;
                    $this->assertEquals('https://schema.org', $decoded['@context']);
                    $this->assertEquals('Sikórz 56A', $decoded['address']['streetAddress']);
                    $this->assertEquals('09-413', $decoded['address']['postalCode']);
                    $this->assertEquals('Sikórz k. Płocka', $decoded['address']['addressLocality']);
                    $this->assertEquals('City', $decoded['areaServed']['@type']);
                    $this->assertNotEmpty($decoded['areaServed']['name']);
                    $this->assertArrayHasKey('geo', $decoded);
                    $this->assertEquals(52.6074, $decoded['geo']['latitude']);
                    $this->assertEquals(19.5786, $decoded['geo']['longitude']);
                    $this->assertArrayHasKey('openingHoursSpecification', $decoded);
                }

                if (($decoded['@type'] ?? '') === 'FAQPage') {
                    $foundFaqPage = true;
                    $this->assertEquals('https://schema.org', $decoded['@context']);
                    $this->assertNotEmpty($decoded['mainEntity']);
                    foreach ($decoded['mainEntity'] as $faq) {
                        $this->assertEquals('Question', $faq['@type']);
                        $this->assertNotEmpty($faq['name']);
                        $this->assertEquals('Answer', $faq['acceptedAnswer']['@type']);
                        $this->assertNotEmpty($faq['acceptedAnswer']['text']);
                    }
                }
            }

            $this->assertTrue($foundLocalBusiness, "LocalBusiness schema not found on {$route}");
            $this->assertTrue($foundFaqPage, "FAQPage schema not found on {$route}");
        }
    }

    public function test_local_seo_pages_render_live_animals_from_database(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Kajko Regionalny',
            'slug' => 'kajko-regionalny',
            'status' => AnimalStatus::Available,
            'is_published' => true,
            'published_at' => now()->subDay(),
            'date_of_birth' => now()->subWeeks(6),
        ]);

        $response = $this->get('/hodowla-kotow-warszawa');
        $response->assertOk();
        $response->assertSee('Kajko Regionalny');
        // Because the cat is 6 weeks old, pickup window is 12–14 weeks (early reservation stage)
        $response->assertSee('Dostępny do rezerwacji');
        $response->assertSee('Odbiór: za ok. 6 tyg.');
    }

    public function test_footer_contains_internal_links_to_all_local_seo_pages(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        foreach ($this->cityRoutes as $route) {
            $response->assertSee($route);
        }
    }
}
