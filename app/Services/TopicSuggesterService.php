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

    /**
     * High-quality curated fallback topics per breed used when Google AI API is unavailable.
     */
    public const CURATED_TOPICS = [
        'bengalski' => [
            ['title' => 'Cena kota bengalskiego — ile kosztuje kociak z rodowodem FIFE/TICA?', 'keyword' => 'kot bengalski cena', 'intent' => 'commercial'],
            ['title' => 'Pielęgnacja i żywienie kota bengalskiego — co powinien jeść mały leopard?', 'keyword' => 'dieta kot bengalski', 'intent' => 'informational'],
            ['title' => 'Czy kot bengalski nadaje się dla dzieci i do mieszkania?', 'keyword' => 'kot bengalski dla dzieci', 'intent' => 'informational'],
            ['title' => 'Charakter i zachowanie kota bengalskiego — jak radzić sobie z energią?', 'keyword' => 'kot bengalski charakter', 'intent' => 'informational'],
            ['title' => 'Kot bengalski a inne zwierzęta — czy dogada się z psem lub kotem?', 'keyword' => 'kot bengalski z psem', 'intent' => 'informational'],
            ['title' => 'Wyprawka dla kota bengalskiego — niezbędne akcesoria i drapaki', 'keyword' => 'wyprawka dla kota bengalskiego', 'intent' => 'commercial'],
        ],
        'brytyjski' => [
            ['title' => 'Kot brytyjski cena i koszty utrzymania — na co zwrócić uwagę w hodowli?', 'keyword' => 'kot brytyjski cena', 'intent' => 'commercial'],
            ['title' => 'Żywienie i waga kota brytyjskiego — jak dbać o zdrową sylwetkę?', 'keyword' => 'dieta kot brytyjski', 'intent' => 'informational'],
            ['title' => 'Temperament kota brytyjskiego — cichy pieszczoch czy niezależny domownik?', 'keyword' => 'kot brytyjski charakter', 'intent' => 'informational'],
            ['title' => 'Pielęgnacja gęstej sierści kota brytyjskiego — czesanie i higiena', 'keyword' => 'sierść kota brytyjskiego', 'intent' => 'informational'],
            ['title' => 'Kot brytyjski w domu z dziećmi — dlaczego to idealna rasa rodzinna?', 'keyword' => 'kot brytyjski dzieci', 'intent' => 'informational'],
            ['title' => 'Kastracja i sterylizacja kota brytyjskiego — kiedy wykonać zabieg?', 'keyword' => 'sterylizacja kota brytyjskiego', 'intent' => 'informational'],
        ],
        'syjamski' => [
            ['title' => 'Kot syjamski cena i rodowód — ile kosztuje prawdziwy Syjam z hodowli?', 'keyword' => 'kot syjamski cena', 'intent' => 'commercial'],
            ['title' => 'Mowa i charakter kota syjamskiego — dlaczego te koty tak dużo mówią?', 'keyword' => 'kot syjamski charakter', 'intent' => 'informational'],
            ['title' => 'Pielęgnacja i zdrowie kota syjamskiego — genetyka i długość życia', 'keyword' => 'zdrowie kota syjamskiego', 'intent' => 'informational'],
            ['title' => 'Czy kot syjamski źle znosi samotność? Porady dla właścicieli', 'keyword' => 'kot syjamski samotność', 'intent' => 'informational'],
            ['title' => 'Jak żywić kota syjamskiego — dieta dla aktywnego i smukłego kota', 'keyword' => 'dieta kot syjamski', 'intent' => 'informational'],
            ['title' => 'Kot syjamski a alergia — czy ta rasa uczula mniej?', 'keyword' => 'kot syjamski alergia', 'intent' => 'informational'],
        ],
    ];

    public function __construct(
        private readonly GeminiService $gemini
    ) {}

    /**
     * Return topic suggestions for the given breed.
     *
     * @param  string  $breedKey
     * @param  bool    $forceRefresh
     * @return array<int, array{title: string, keyword: string, intent: string}>
     */
    public function suggest(string $breedKey, bool $forceRefresh = false): array
    {
        $status = $this->suggestWithStatus($breedKey, $forceRefresh);
        return $status['topics'];
    }

    /**
     * Return topic suggestions along with source information (live_api, today_cache, db_fallback, curated_fallback).
     *
     * @return array{topics: array, source: string, date: ?string}
     */
    public function suggestWithStatus(string $breedKey, bool $forceRefresh = false): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Check DB cache for today unless force refresh is requested
        if (! $forceRefresh) {
            try {
                $cached = TrendingTopic::where('breed', $breedKey)
                    ->where('fetched_date', $today)
                    ->first();

                if ($cached && ! empty($cached->topics) && is_array($cached->topics)) {
                    Log::info('TopicSuggesterService: returning cached trends from DB', [
                        'breed' => $breedKey,
                        'date'  => $today,
                    ]);
                    return [
                        'topics' => $cached->topics,
                        'source' => 'today_cache',
                        'date'   => $today,
                    ];
                }
            } catch (\Throwable $e) {
                Log::warning('TopicSuggesterService: DB cache read error', ['error' => $e->getMessage()]);
            }
        }

        // 2. Fetch live trends from Gemini API
        try {
            $liveTopics = $this->fetchLiveTrendsFromGemini($breedKey);
            if (! empty($liveTopics)) {
                try {
                    TrendingTopic::updateOrCreate(
                        ['breed' => $breedKey, 'fetched_date' => $today],
                        ['topics' => $liveTopics]
                    );
                } catch (\Throwable $e) {
                    Log::warning('TopicSuggesterService: DB cache save error', ['error' => $e->getMessage()]);
                }

                return [
                    'topics' => $liveTopics,
                    'source' => 'live_api',
                    'date'   => $today,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('TopicSuggesterService: Gemini live fetch failed, resorting to fallbacks', [
                'breed' => $breedKey,
                'error' => $e->getMessage(),
            ]);
        }

        // 3. Fallback: Check for previous cached entries in DB
        try {
            $previous = TrendingTopic::where('breed', $breedKey)
                ->orderBy('fetched_date', 'desc')
                ->first();

            if ($previous && ! empty($previous->topics) && is_array($previous->topics)) {
                $dateStr = $previous->fetched_date instanceof \DateTimeInterface
                    ? $previous->fetched_date->format('Y-m-d')
                    : (string) $previous->fetched_date;

                return [
                    'topics' => $previous->topics,
                    'source' => 'db_fallback',
                    'date'   => $dateStr,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('TopicSuggesterService: DB fallback read error', ['error' => $e->getMessage()]);
        }

        // 4. Fallback: Curated breed topics
        $curated = self::CURATED_TOPICS[$breedKey] ?? self::CURATED_TOPICS['bengalski'];
        return [
            'topics' => $curated,
            'source' => 'curated_fallback',
            'date'   => null,
        ];
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