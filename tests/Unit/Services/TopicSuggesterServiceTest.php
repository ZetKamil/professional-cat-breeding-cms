<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\GeminiService;
use App\Services\TopicSuggesterService;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Unit tests for TopicSuggesterService.
 */
class TopicSuggesterServiceTest extends TestCase
{
    public function test_breeds_returns_bengalski_brytyjski_and_syjamski(): void
    {
        $service = app(TopicSuggesterService::class);
        $breeds  = $service->breeds();

        $this->assertCount(3, $breeds);
        $this->assertArrayHasKey('bengalski', $breeds);
        $this->assertArrayHasKey('brytyjski', $breeds);
        $this->assertArrayHasKey('syjamski',  $breeds);
        $this->assertEquals('Kot Syjamski', $breeds['syjamski']);
    }

    public function test_suggest_returns_topics_from_gemini(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andReturn(json_encode([
                    ['title' => 'Cena kota syjamskiego', 'keyword' => 'kot syjamski cena', 'intent' => 'commercial'],
                    ['title' => 'Żywienie kota syjamskiego', 'keyword' => 'dieta kot syjamski', 'intent' => 'informational'],
                ]));
        });

        $service = app(TopicSuggesterService::class);
        $topics  = $service->suggest('syjamski');

        $this->assertCount(2, $topics);
        $this->assertEquals('Cena kota syjamskiego', $topics[0]['title']);
    }

    public function test_suggest_throws_exception_on_api_failure_no_hardcoded_fallbacks(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andThrow(new \RuntimeException('API error'));
        });

        $this->expectException(\RuntimeException::class);

        $service = app(TopicSuggesterService::class);
        $service->suggest('bengalski');
    }
}