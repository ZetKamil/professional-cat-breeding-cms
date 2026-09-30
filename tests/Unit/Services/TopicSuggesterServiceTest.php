<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\TopicSuggesterService;
use Tests\TestCase;

/**
 * Unit tests for TopicSuggesterService.
 *
 * All tests are pure (no DB, no HTTP) — the service has no external deps.
 */
class TopicSuggesterServiceTest extends TestCase
{
    private TopicSuggesterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TopicSuggesterService();
    }

    public function test_suggest_returns_array_for_bengalski(): void
    {
        $topics = $this->service->suggest('bengalski');

        $this->assertIsArray($topics);
        $this->assertNotEmpty($topics, 'Should return at least one topic for bengalski');
        $this->assertLessThanOrEqual(6, count($topics), 'Should return at most 6 topics');
    }

    public function test_suggest_returns_array_for_brytyjski(): void
    {
        $topics = $this->service->suggest('brytyjski');

        $this->assertIsArray($topics);
        $this->assertNotEmpty($topics);
    }

    public function test_suggest_returns_array_for_maine_coon(): void
    {
        $topics = $this->service->suggest('maine-coon');

        $this->assertIsArray($topics);
        $this->assertNotEmpty($topics);
    }

    public function test_each_topic_has_required_keys(): void
    {
        $topics = $this->service->suggest('bengalski');

        foreach ($topics as $topic) {
            $this->assertArrayHasKey('title',   $topic, 'Topic must have title');
            $this->assertArrayHasKey('keyword', $topic, 'Topic must have keyword');
            $this->assertArrayHasKey('intent',  $topic, 'Topic must have intent');
        }
    }

    public function test_breeds_returns_all_three_breeds(): void
    {
        $breeds = $this->service->breeds();

        $this->assertCount(3, $breeds);
        $this->assertArrayHasKey('bengalski',  $breeds);
        $this->assertArrayHasKey('brytyjski',  $breeds);
        $this->assertArrayHasKey('maine-coon', $breeds);
    }

    public function test_topics_contain_polish_text(): void
    {
        $topics = $this->service->suggest('bengalski');
        $allTitles = implode(' ', array_column($topics, 'title'));

        // Verify Polish content — at least one topic should mention 'kot' or 'koci'
        $this->assertMatchesRegularExpression('/kot|koci/i', $allTitles);
    }

    public function test_suggest_returns_empty_for_unknown_breed(): void
    {
        // Unknown breed should not crash, returns whatever seasonal topics match
        $topics = $this->service->suggest('unknown-breed');

        $this->assertIsArray($topics);
        // Should still return seasonal topics even for unknown breed
        $this->assertLessThanOrEqual(6, count($topics));
    }
}