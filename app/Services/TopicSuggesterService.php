<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\TrendingTopic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Provides dynamic breed-specific blog topic suggestions via Gemini API & DB caching.
 *
 * Strictly NO hardcoded fallback topics.
 * Breeds: Kot Bengalski, Kot Brytyjski, Kot Syjamski.
 *
 * Flow:
 * 1. Checks DB `trending_topics` table for today's entry (`fetched_date = today`).
 * 2. If not found in DB today, queries Gemini API for current search trends in Poland.
 * 3. Saves newly fetched AI trends to DB for caching today.
 * 4. If fetching fails (missing key, timeout, API error), throws an exception
 *    so the UI displays a clear error message to the user explaining why topics failed to load.
 */
class TopicSuggesterService
{
    /**
     * The three breeds bred at Katten: Bengalski, Brytyjski, Syjamski.
     */
    public const BREEDS = [
        'bengalski' => 'Kot Bengalski',
        'brytyjski' => 'Kot Brytyjski',
        'syjamski'  => 'Kot Syjamski',
    ];

    public function __construct(
        private readonly GeminiService $gemini
    ) {}

    /**
     * Return topic suggestions for the given breed from DB cache or live Gemini API.
     *
     * @param  string  $breedKey  One of BREEDS keys ('bengalski', 'brytyjski', 'syjamski')
     * @return array<int, array{title: string, keyword: string, intent: string}>
     *
     * @throws GeminiApiKeyMissingException
     * @throws GeminiServiceUnavailableException
     * @throws \RuntimeException
     */
    public function suggest(string $breedKey): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Check DB cache for today
        try {
            $cached = TrendingTopic::where('breed', $breedKey)
                ->where('fetched_date', $today)
                ->first();

            if ($cached && ! empty($cached->topics) && is_array($cached->topics)) {
                Log::info('TopicSuggesterService: returning cached trends from DB', [
                    'breed' => $breedKey,
                    'date'  => $today,
                ]);
                return $cached->topics;
            }
        } catch (\Throwable $e) {
            Log::warning('TopicSuggesterService: DB cache read error', ['error' => $e->getMessage()]);
        }

        // 2. Fetch live trends from Gemini API (will throw GeminiApiKeyMissingException / GeminiServiceUnavailableException on failure)
        $liveTopics = $this->fetchLiveTrendsFromGemini($breedKey);

        if (empty($liveTopics)) {
            throw new \RuntimeException("Nie udało się pobrać aktualnych trendów Google z AI dla rasy '{$breedKey}'. Spróbuj ponownie lub wpisz własny temat.");
        }

        // 3. Cache to DB for today
        try {
            TrendingTopic::updateOrCreate(
                ['breed' => $breedKey, 'fetched_date' => $today],
                ['topics' => $liveTopics]
            );
        } catch (\Throwable $e) {
            Log::warning('TopicSuggesterService: DB cache save error', ['error' => $e->getMessage()]);
        }

        return $liveTopics;
    }

    /**
     * Return available breeds as [key => label] for the UI selector.
     */
    public function breeds(): array
    {
        return self::BREEDS;
    }

    /**
     * Query Gemini API for live search trends.
     *
     * @throws GeminiApiKeyMissingException
     * @throws GeminiServiceUnavailableException
     */
    private function fetchLiveTrendsFromGemini(string $breedKey): array
    {
        $breedName = self::BREEDS[$breedKey] ?? 'Kot Bengalski';
        $currentMonthYear = Carbon::now()->translatedFormat('F Y');

        $systemPrompt = <<<PROMPT
You are a senior SEO keyword analyst and content strategy expert for a high-end Polish cat breeding cattery.
Your goal is to suggest 6 highly attractive, search-trending blog post topics in Polish for cat owners and prospective kitten buyers for breed: {$breedName}.

Rules:
1. All titles MUST be in natural Polish with correct grammar and noun declensions (e.g. "kota bengalskiego", "kota brytyjskiego", "kota syjamskiego").
2. Mix evergreen high-volume search queries (price/cost, feeding/diet, temperament with children, health/genetics) with current seasonal interests for {$currentMonthYear}.
3. Respond ONLY with a valid JSON array of 6 objects. Do not include markdown code blocks or additional text.

JSON Schema:
[
  {
    "title": "Title in Polish",
    "keyword": "main target keyword",
    "intent": "informational" | "commercial"
  }
]
PROMPT;

        $userPrompt = "Suggest 6 search-trending blog post topics for breed '{$breedName}' for {$currentMonthYear} in Poland.";

        $rawResponse = $this->gemini->generateText($systemPrompt, $userPrompt);
        $parsed      = json_decode($rawResponse, true);

        if (is_array($parsed) && count($parsed) >= 1) {
            $clean = [];
            foreach ($parsed as $item) {
                if (isset($item['title'], $item['keyword'])) {
                    $clean[] = [
                        'title'   => (string) $item['title'],
                        'keyword' => (string) $item['keyword'],
                        'intent'  => (string) ($item['intent'] ?? 'informational'),
                    ];
                }
            }

            if (! empty($clean)) {
                Log::info('TopicSuggesterService: fetched fresh trends from Gemini API', [
                    'breed' => $breedKey,
                    'count' => count($clean),
                ]);
                return array_slice($clean, 0, 6);
            }
        }

        return [];
    }
}