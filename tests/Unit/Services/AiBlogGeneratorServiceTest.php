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
}