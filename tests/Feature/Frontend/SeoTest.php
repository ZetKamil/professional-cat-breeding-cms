<?php

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_returns_valid_xml_with_all_public_pages(): void
    {
        // Create sample published animal and post
        $animal = Animal::factory()->create([
            'name' => 'Simba Test',
            'slug' => 'simba-test',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $author = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $author->id,
            'title' => 'Pielęgnacja Kotów',
            'slug' => 'pielegnacja-kotow',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        // Check XML tags and schema
        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);

        // Check public static routes
        $this->assertStringContainsString('/koty</loc>', $content);
        $this->assertStringContainsString('/o-hodowli</loc>', $content);
        $this->assertStringContainsString('/about</loc>', $content);
        $this->assertStringContainsString('/blog</loc>', $content);
        $this->assertStringContainsString('/contact</loc>', $content);
        $this->assertStringContainsString('/polityka-prywatnosci</loc>', $content);
        $this->assertStringContainsString('/regulamin</loc>', $content);

        // Check dynamic records
        $this->assertStringContainsString('/koty/simba-test</loc>', $content);
        $this->assertStringContainsString('/blog/pielegnacja-kotow</loc>', $content);

        // Disallowed administrative routes must not appear in sitemap
        $this->assertStringNotContainsString('/backend', $content);
        $this->assertStringNotContainsString('/dashboard', $content);
        $this->assertStringNotContainsString('/login', $content);
        $this->assertStringNotContainsString('/register', $content);
        $this->assertStringNotContainsString('/settings', $content);
        $this->assertStringNotContainsString('/check-media', $content);
        $this->assertStringNotContainsString('/fix-storage', $content);
    }

    public function test_robots_txt_file_is_present_and_correctly_configured(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Allow: /', $content);
        $this->assertStringContainsString('Disallow: /backend', $content);
        $this->assertStringContainsString('Disallow: /dashboard', $content);
        $this->assertStringContainsString('Disallow: /login', $content);
        $this->assertStringContainsString('Disallow: /register', $content);
        $this->assertStringContainsString('Disallow: /settings', $content);
        $this->assertStringContainsString('Sitemap: https://kotyzmazowieckiejszwajcarii.pl/sitemap.xml', $content);
    }

    public function test_public_pages_contain_required_seo_and_open_graph_tags(): void
    {
        $routes = [
            '/',
            '/o-hodowli',
            '/about',
            '/koty',
            '/blog',
            '/contact',
            '/polityka-prywatnosci',
            '/regulamin',
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertOk();

            // Technical SEO tags
            $response->assertSee('<meta charset="UTF-8">', false);
            $response->assertSee('name="viewport"', false);
            $response->assertSee('name="description"', false);
            $response->assertSee('rel="canonical"', false);
            $response->assertSee('lang="pl"', false);

            // Open Graph tags
            $response->assertSee('property="og:title"', false);
            $response->assertSee('property="og:description"', false);
            $response->assertSee('property="og:url"', false);
            $response->assertSee('property="og:site_name"', false);
            $response->assertSee('property="og:type"', false);

            // Twitter Cards
            $response->assertSee('name="twitter:card"', false);
            $response->assertSee('name="twitter:title"', false);
            $response->assertSee('name="twitter:description"', false);
        }
    }

    public function test_dynamic_animal_and_blog_pages_have_unique_seo_tags(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Aura Royal',
            'slug' => 'aura-royal',
            'breed' => 'Kot Bengalski',
            'color' => 'Rozeta na złocie',
            'short_description' => 'Unikalny profil kotki Aura Royal.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $responseAnimal = $this->get('/koty/' . $animal->slug);
        $responseAnimal->assertOk();
        $responseAnimal->assertSee('Aura Royal');
        $responseAnimal->assertSee('Unikalny profil kotki Aura Royal');
        $responseAnimal->assertSee('property="og:type" content="profile"', false);

        $author = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $author->id,
            'title' => 'Wszystko o Żywieniu Kotów',
            'slug' => 'wszystko-o-zywieniu-kotow',
            'excerpt' => 'Krótkie streszczenie poradnika żywieniowego.',
            'body' => 'Treść poradnika...',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $responsePost = $this->get('/blog/' . $post->slug);
        $responsePost->assertOk();
        $responsePost->assertSee('Wszystko o Żywieniu Kotów');
        $responsePost->assertSee('Krótkie streszczenie poradnika żywieniowego');
        $responsePost->assertSee('property="og:type" content="article"', false);
    }

    public function test_backend_and_auth_pages_have_noindex_nofollow(): void
    {
        $responseLogin = $this->get('/login');
        $responseLogin->assertOk();
        $responseLogin->assertSee('<meta name="robots" content="noindex, nofollow" />', false);
    }

    public function test_animal_page_structured_data_is_valid_item_page_with_animal_entity_and_no_ecommerce_semantics(): void
    {
        $animal = Animal::factory()->create([
            'name' => 'Leo Magnifico',
            'slug' => 'leo-magnifico',
            'breed' => 'Kot Brytyjski',
            'short_description' => 'Aksamitny brytyjczyk z doskonałej linii hodowlanej.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/koty/' . $animal->slug);
        $response->assertOk();

        // Extract JSON-LD script content
        $content = $response->getContent();
        $this->assertStringContainsString('<script type="application/ld+json">', $content);

        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
        $this->assertNotEmpty($matches, 'JSON-LD script tag not found');

        $json = trim($matches[1]);
        $data = json_decode($json, true);

        // Verify valid JSON
        $this->assertIsArray($data, 'JSON-LD could not be parsed as valid JSON: ' . json_last_error_msg());

        // Verify schema structure
        $this->assertEquals('https://schema.org', $data['@context']);
        $this->assertEquals('ItemPage', $data['@type']);
        $this->assertStringContainsString('Leo Magnifico', $data['name']);
        $this->assertArrayHasKey('mainEntity', $data);

        // Verify mainEntity is Animal
        $this->assertEquals('Animal', $data['mainEntity']['@type']);
        $this->assertEquals('Leo Magnifico', $data['mainEntity']['name']);
        $this->assertEquals('Kot Brytyjski', $data['mainEntity']['breed']);
        $this->assertStringContainsString('Aksamitny brytyjczyk', $data['mainEntity']['description']);

        // Verify NO E-commerce / Merchant semantics exist
        $this->assertArrayNotHasKey('offers', $data);
        $this->assertArrayNotHasKey('offers', $data['mainEntity']);
        $this->assertArrayNotHasKey('price', $data);
        $this->assertArrayNotHasKey('priceCurrency', $data);
        $this->assertArrayNotHasKey('provider', $data);
        $this->assertArrayNotHasKey('sku', $data);
        $this->assertArrayNotHasKey('gtin', $data);

        $this->assertStringNotContainsString('"Product"', $json);
        $this->assertStringNotContainsString('"Offer"', $json);
        $this->assertStringNotContainsString('InStock', $json);
        $this->assertStringNotContainsString('OutOfStock', $json);
        $this->assertStringNotContainsString('priceCurrency', $json);
    }
}
