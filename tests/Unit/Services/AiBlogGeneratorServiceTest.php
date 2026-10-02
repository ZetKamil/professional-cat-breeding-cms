<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Services\AiBlogGeneratorService;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Unit tests for AiBlogGeneratorService.
 *
 * Key assertions:
 * - Generated draft is ALWAYS a draft (is_published = false)
 * - Missing API key throws GeminiApiKeyMissingException early
 * - API errors surface as GeminiServiceUnavailableException (not HTTP 500)
 */
class AiBlogGeneratorServiceTest extends TestCase
{
    public function test_generated_draft_is_always_unpublished(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andReturn(json_encode([
                    'h1'                => 'Test artykul',
                    'meta_title'        => 'Meta title',
                    'meta_description'  => 'Meta desc',
                    'excerpt'           => 'Short excerpt.',
                    'content_html'      => '<p>Body text</p>',
                    'hero_image_prompt' => 'A beautiful cat',
                ]));
        });

        Config::set('services.gemini.api_key', 'test-key-123');

        $service = app(AiBlogGeneratorService::class);
        $draft   = $service->generateDraft('Kot Bengalski', 'Test topic', []);

        $this->assertFalse(
            $draft['is_published'],
            'Draft MUST always be unpublished -- human must publish manually.'
        );
    }

    public function test_throws_when_api_key_is_missing(): void
    {
        Config::set('services.gemini.api_key', null);

        $this->expectException(GeminiApiKeyMissingException::class);

        // Key is validated at call-time, not construction time
        // (page loads fine; error appears only when user clicks Generate)
        $service = new GeminiService();
        $service->generateText('system', 'user');
    }

    public function test_throws_when_api_key_is_empty_string(): void
    {
        Config::set('services.gemini.api_key', '');

        $this->expectException(GeminiApiKeyMissingException::class);

        $service = new GeminiService();
        $service->generateText('system', 'user');
    }

    public function test_service_unavailable_propagates_to_caller(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andThrow(GeminiServiceUnavailableException::timeout());
        });

        Config::set('services.gemini.api_key', 'test-key-123');

        $this->expectException(GeminiServiceUnavailableException::class);
        $this->expectExceptionMessageMatches('/timeout/i');

        $service = app(AiBlogGeneratorService::class);
        $service->generateDraft('Kot Bengalski', 'Test topic', []);
    }

    public function test_draft_contains_required_keys(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andReturn(json_encode([
                    'h1'                => 'Ile kosztuje kot bengalski?',
                    'meta_title'        => 'Meta title',
                    'meta_description'  => 'Meta desc',
                    'excerpt'           => 'Excerpt.',
                    'content_html'      => '<p>Body</p>',
                    'hero_image_prompt' => 'Elegant cat',
                ]));
        });

        Config::set('services.gemini.api_key', 'test-key-123');

        $service = app(AiBlogGeneratorService::class);
        $draft   = $service->generateDraft('Kot Bengalski', 'Cena kota bengalskiego', []);

        foreach (['title', 'slug', 'excerpt', 'body', 'is_published', 'published_at',
                  'meta_title', 'meta_description', 'hero_image_prompt', 'featured_animal_ids'] as $key) {
            $this->assertArrayHasKey($key, $draft, "Draft missing required key: {$key}");
        }
    }

    public function test_draft_slug_is_url_safe(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andReturn(json_encode([
                    'h1'                => 'Ile kosztuje Kot Bengalski?',
                    'meta_title'        => 'Meta',
                    'meta_description'  => 'Meta desc',
                    'excerpt'           => 'Excerpt.',
                    'content_html'      => '<p>Body</p>',
                    'hero_image_prompt' => 'Cat',
                ]));
        });

        Config::set('services.gemini.api_key', 'test-key-123');

        $service = app(AiBlogGeneratorService::class);
        $draft   = $service->generateDraft('Kot Bengalski', 'Test', []);

        $this->assertMatchesRegularExpression(
            '/^[a-z0-9\-]+$/',
            $draft['slug'],
            'Slug must be URL-safe lowercase'
        );
    }

    public function test_generate_hero_image_returns_null_when_base64_empty(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateImage')
                ->once()
                ->andReturn('');
        });

        Config::set('services.gemini.api_key', 'test-key-123');

        $service = app(AiBlogGeneratorService::class);
        $post    = new \App\Models\Post();
        $media   = $service->generateHeroImage('cat prompt', $post);

        $this->assertNull($media);
    }

    public function test_normalize_sections_cleans_and_structures_data(): void
    {
        $service = app(AiBlogGeneratorService::class);

        $parsedWithSynonyms = [
            'excerpt' => 'Lead summary.',
            'sections' => [
                [
                    'title'   => 'Ignored intro heading',
                    'content' => 'First intro paragraph.',
                ],
                [
                    'title'     => 'Sekcja druga H2',
                    'paragraph' => '<p>Treść z tagami html.</p>',
                ],
                [
                    'heading' => 'Sekcja trzecia H2',
                    'body'    => 'Normalny tekst sekcji.',
                ],
                [
                    'heading' => '🐾 Dostępne kocięta w naszej hodowli',
                    'body'    => 'Zapraszamy do rezerwacji kociąt!',
                ],
            ],
        ];

        $normalized = $service->normalizeSections($parsedWithSynonyms, 'Kot Bengalski');

        // 1. Must filter out the trailing 🐾 CTA section (handled dynamically by Global CTA)
        $this->assertCount(3, $normalized, 'Should filter out the duplicate closing CTA section');

        // 2. Section 0 must have empty heading for the intro lead
        $this->assertSame('', $normalized[0]['heading']);
        $this->assertSame('First intro paragraph.', $normalized[0]['body']);

        // 3. Section 1 mapped 'title' to 'heading' and stripped HTML from body
        $this->assertSame('Sekcja druga H2', $normalized[1]['heading']);
        $this->assertSame('Treść z tagami html.', $normalized[1]['body']);

        // 4. Section 2 kept regular fields
        $this->assertSame('Sekcja trzecia H2', $normalized[2]['heading']);
        $this->assertSame('Normalny tekst sekcji.', $normalized[2]['body']);
    }
}