<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TrendingTopic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Provides breed-specific, seasonally-aware blog topic suggestions.
 *
 * Architecture:
 * 1. Checks DB table `trending_topics` for today's AI-generated trends per breed.
 * 2. If not found in DB today, queries Gemini API for current search trends in Poland.
 * 3. Saves newly fetched AI trends to DB (`trending_topics` table) for caching today.
 * 4. Falls back to curated static topics if API is unavailable or unconfigured.
 */
class TopicSuggesterService
{
    /**
     * The three breeds we breed at Katten.
     * Keys are used as identifiers in the Livewire component.
     */
    public const BREEDS = [
        'bengalski'  => 'Kot Bengalski',
        'brytyjski'  => 'Kot Brytyjski',
        'maine-coon' => 'Maine Coon',
    ];

    public function __construct(
        private readonly GeminiService $gemini
    ) {}

    /**
     * Return 5–6 topic suggestions for the given breed and current month.
     *
     * @param  string  $breedKey  One of the BREEDS keys (e.g. 'bengalski')
     * @return array<int, array{title: string, keyword: string, intent: string}>
     */
    public function suggest(string $breedKey): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Check DB cache first
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
            Log::warning('TopicSuggesterService: DB cache check failed', ['error' => $e->getMessage()]);
        }

        // 2. Fetch live trends from Gemini API
        $liveTopics = $this->fetchLiveTrendsFromGemini($breedKey);

        if (! empty($liveTopics)) {
            // Store in DB for today
            try {
                TrendingTopic::updateOrCreate(
                    ['breed' => $breedKey, 'fetched_date' => $today],
                    ['topics' => $liveTopics]
                );
            } catch (\Throwable $e) {
                Log::warning('TopicSuggesterService: failed to cache trends to DB', ['error' => $e->getMessage()]);
            }

            return $liveTopics;
        }

        // 3. Fallback to curated static topics if API fails/unavailable
        return $this->fallbackTopics($breedKey);
    }

    /**
     * Return all available breeds as [key => label] for the UI select.
     */
    public function breeds(): array
    {
        return self::BREEDS;
    }

    // ─── AI Live Trend Fetcher ───────────────────────────────────────

    /**
     * Query Gemini API for live, search-trending article topics in Poland.
     */
    private function fetchLiveTrendsFromGemini(string $breedKey): array
    {
        $breedName = self::BREEDS[$breedKey] ?? 'Kot Bengalski';
        $currentMonthYear = Carbon::now()->translatedFormat('F Y');

        $systemPrompt = <<<PROMPT
You are a senior SEO keyword analyst and content strategy expert for a high-end Polish cat breeding cattery.
Your goal is to suggest 6 highly engaging, search-trending blog post topics in Polish for cat owners and prospective kitten buyers.

Rules:
1. All titles MUST be in natural Polish with correct grammar and noun declensions (e.g. "kota bengalskiego", "kota brytyjskiego", "Maine Coona").
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

        $userPrompt = "Suggest 6 trending blog post topics for breed '{$breedName}' for {$currentMonthYear} in Poland.";

        try {
            $rawResponse = $this->gemini->generateText($systemPrompt, $userPrompt);
            $parsed      = json_decode($rawResponse, true);

            if (is_array($parsed) && count($parsed) >= 3) {
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

                if (count($clean) >= 3) {
                    Log::info('TopicSuggesterService: fetched fresh trends from Gemini API', [
                        'breed' => $breedKey,
                        'count' => count($clean),
                    ]);
                    return array_slice($clean, 0, 6);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('TopicSuggesterService: Gemini API live trends fetch failed', [
                'breed' => $breedKey,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    // ─── Fallback Static Topics ──────────────────────────────────────

    private function fallbackTopics(string $breedKey): array
    {
        $month   = (int) Carbon::now()->format('n');
        $season  = $this->currentSeason($month);
        $topics  = $this->breedTopics($breedKey);
        $seasonal = $this->seasonalTopics($breedKey, $season);

        $merged = array_values(array_unique(
            array_merge($seasonal, $topics),
            SORT_REGULAR
        ));

        return array_slice($merged, 0, 6);
    }

    private function breedTopics(string $breedKey): array
    {
        return match ($breedKey) {
            'bengalski' => [
                ['title' => 'Ile kosztuje kot bengalski? Cena i koszty utrzymania',
                 'keyword' => 'ile kosztuje kot bengalski', 'intent' => 'commercial'],
                ['title' => 'Kot bengalski a dzieci – czy to dobra kombinacja?',
                 'keyword' => 'kot bengalski a dzieci', 'intent' => 'informational'],
                ['title' => 'Czym karmić kota bengalskiego? Dieta i żywienie',
                 'keyword' => 'czym karmić kota bengalskiego', 'intent' => 'informational'],
                ['title' => 'Badania genetyczne HCM u kotów bengalskich – co musisz wiedzieć',
                 'keyword' => 'badania hcm kot bengalski', 'intent' => 'informational'],
                ['title' => 'Kot bengalski w mieszkaniu – czy się sprawdzi?',
                 'keyword' => 'kot bengalski w mieszkaniu', 'intent' => 'informational'],
                ['title' => 'Socjalizacja kociąt bengalskich w hodowli',
                 'keyword' => 'socjalizacja kociąt bengalskich', 'intent' => 'informational'],
            ],
            'brytyjski' => [
                ['title' => 'Kot brytyjski krótkowłosy – charakter i pielęgnacja',
                 'keyword' => 'kot brytyjski charakter', 'intent' => 'informational'],
                ['title' => 'Ile kosztuje kot brytyjski z rodowodem?',
                 'keyword' => 'kot brytyjski cena', 'intent' => 'commercial'],
                ['title' => 'Kot brytyjski czy bengalski – które zwierzę wybrać?',
                 'keyword' => 'kot brytyjski czy bengalski', 'intent' => 'commercial'],
                ['title' => 'Dieta kota brytyjskiego – czym karmić, by uniknąć otyłości',
                 'keyword' => 'dieta kot brytyjski', 'intent' => 'informational'],
                ['title' => 'Pielęgnacja sierści kota brytyjskiego – kompletny poradnik',
                 'keyword' => 'pielęgnacja kot brytyjski', 'intent' => 'informational'],
                ['title' => 'Kastracja kota brytyjskiego – kiedy i dlaczego?',
                 'keyword' => 'kastracja kot brytyjski', 'intent' => 'informational'],
            ],
            'maine-coon' => [
                ['title' => 'Maine Coon – największy kot domowy. Wszystko co musisz wiedzieć',
                 'keyword' => 'maine coon', 'intent' => 'informational'],
                ['title' => 'Ile kosztuje Maine Coon z hodowli?',
                 'keyword' => 'maine coon cena', 'intent' => 'commercial'],
                ['title' => 'Maine Coon w mieszkaniu – czy potrzebuje ogrodu?',
                 'keyword' => 'maine coon w mieszkaniu', 'intent' => 'informational'],
                ['title' => 'Czym karmić Maine Coona? Dieta dla dużego kota',
                 'keyword' => 'maine coon dieta', 'intent' => 'informational'],
                ['title' => 'Maine Coon a pies – czy mogą żyć razem?',
                 'keyword' => 'maine coon a pies', 'intent' => 'informational'],
                ['title' => 'Pielęgnacja Maine Coona – szczotkowanie i kąpiel',
                 'keyword' => 'pielęgnacja maine coon', 'intent' => 'informational'],
            ],
            default => [],
        };
    }

    private function seasonalTopics(string $breedKey, string $season): array
    {
        $genitiveMap = [
            'bengalski'  => 'kota bengalskiego',
            'brytyjski'  => 'kota brytyjskiego',
            'maine-coon' => 'Maine Coona',
        ];
        $breedGenitive = $genitiveMap[$breedKey] ?? 'kota';

        return match ($season) {
            'wiosna' => [
                ['title' => "Wiosenne linienie u {$breedGenitive} – jak dbać o sierść?",
                 'keyword' => "linienie {$breedGenitive}", 'intent' => 'informational'],
                ['title' => "Bezpieczny balkon dla {$breedGenitive} – siatki i zabezpieczenia",
                 'keyword' => "balkon dla kota", 'intent' => 'informational'],
            ],
            'lato' => [
                ['title' => "Jak uchronić {$breedGenitive} przed upałami?",
                 'keyword' => "{$breedGenitive} upał", 'intent' => 'informational'],
                ['title' => "Wakacyjny wyjazd a {$breedGenitive} – hotel czy opieka w domu?",
                 'keyword' => "{$breedGenitive} wakacje", 'intent' => 'informational'],
            ],
            'jesień' => [
                ['title' => "Jesienne wzmocnienie odporności u {$breedGenitive}",
                 'keyword' => "odporność {$breedGenitive}", 'intent' => 'informational'],
                ['title' => "Jesienne wieczory z {$breedGenitive} – najlepsze zabawki i aktywności",
                 'keyword' => "zabawki dla {$breedGenitive}", 'intent' => 'informational'],
            ],
            'zima' => [
                ['title' => "Bezpieczne święta z {$breedGenitive} – choinka i trujące rośliny",
                 'keyword' => "{$breedGenitive} święta", 'intent' => 'informational'],
                ['title' => "Nowy rok z {$breedGenitive} – jak pomóc przetrwać sylwestrowe hałasy?",
                 'keyword' => "{$breedGenitive} sylwester", 'intent' => 'informational'],
            ],
            default => [],
        };
    }

    private function currentSeason(int $month): string
    {
        return match (true) {
            in_array($month, [3, 4, 5], true)  => 'wiosna',
            in_array($month, [6, 7, 8], true)  => 'lato',
            in_array($month, [9, 10, 11], true) => 'jesień',
            default                             => 'zima',
        };
    }
}