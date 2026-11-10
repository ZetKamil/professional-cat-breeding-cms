<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AiAgent\Tools\FetchGoogleTrendsTool;
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

    /**
     * NO hardcoded fallback topics — if Gemini API fails, suggest() must throw an exception.
     * The caller (AiBlogStudio) is responsible for showing an error and letting user type their own topic.
     */
    public function test_suggest_throws_exception_on_api_failure_no_hardcoded_fallbacks(): void
    {
        $this->mock(GeminiService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateText')
                ->once()
                ->andThrow(new \RuntimeException('API error 503'));
        });

        $service = app(TopicSuggesterService::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/503|nie udało|trendów/i');

        $service->suggest('bengalski');
    }

    public function test_parses_fenced_json_in_trend_tool_and_topic_suggester_and_checks_placeholder_keys(): void
    {
        $fenced = "Oto propozycje:\n```json\n" . json_encode([
            ['title' => 'Kot bengalski w bloku', 'keyword' => 'kot bengalski mieszkanie', 'intent' => 'informational'],
            ['invalid' => 'brak-pol'],
        ]) . "\n```";

        $this->mock(GeminiService::class, function (MockInterface $mock) use ($fenced) {
            $mock->shouldReceive('generateText')->twice()->andReturn($fenced);
        });

        $topics = app(TopicSuggesterService::class)->suggest('bengalski');
        $this->assertCount(1, $topics);
        $this->assertSame('Kot bengalski w bloku', $topics[0]['title']);

        $toolTopics = app(FetchGoogleTrendsTool::class)->execute([
            'breed_name' => 'Kot Bengalski',
            'animals_context' => [['name' => 'Simba', 'status' => 'available']],
        ]);
        $this->assertCount(1, $toolTopics);
        $this->assertSame('kot bengalski mieszkanie', $toolTopics[0]['keyword']);

        config(['services.gemini.api_key' => 'YOUR_API_KEY']);
        $this->assertFalse((new GeminiService())->isAvailable());

        config(['services.gemini.api_key' => '?']);
        $this->assertFalse((new GeminiService())->isAvailable());
    }
}