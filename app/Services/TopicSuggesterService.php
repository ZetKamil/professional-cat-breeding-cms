<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Carbon;

/**
 * Provides breed-specific, seasonally-aware blog topic suggestions.
 *
 * Topics are curated to match real buyer search intent and seasonal
 * peaks in Poland. They respects the Katten tone of voice:
 * educational first, never salesy.
 *
 * This service is pure — no DB queries, no external API calls.
 * All topics are statically defined and can be extended.
 */
class TopicSuggesterService
{
    /**
     * The three breeds we breed at Katten.
     * Keys are used as identifiers in the Livewire component.
     */
    public const BREEDS = [
        'bengalski' => 'Kot Bengalski',
        'brytyjski' => 'Kot Brytyjski',
        'maine-coon' => 'Maine Coon',
    ];

    /**
     * Return 5–6 topic suggestions for the given breed and current month.
     *
     * @param  string  $breedKey  One of the BREEDS keys (e.g. 'bengalski')
     * @return array<int, array{title: string, keyword: string, intent: string}>
     */
    public function suggest(string $breedKey): array
    {
        $month   = (int) Carbon::now()->format('n');
        $season  = $this->currentSeason($month);
        $breed   = self::BREEDS[$breedKey] ?? 'Kot Bengalski';
        $topics  = $this->breedTopics($breedKey);
        $seasonal = $this->seasonalTopics($breedKey, $season);

        // Merge seasonal (first) + evergreen, deduplicate, return top 6
        $merged = array_values(array_unique(
            array_merge($seasonal, $topics),
            SORT_REGULAR
        ));

        return array_slice($merged, 0, 6);
    }

    /**
     * Return all available breeds as [key => label] for the UI select.
     */
    public function breeds(): array
    {
        return self::BREEDS;
    }

    // ─── Private Topic Banks ─────────────────────────────────────────

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
        $breedLabel = self::BREEDS[$breedKey] ?? 'kota';

        return match ($season) {
            'wiosna' => [
                ['title' => "Wiosenne odrobaczanie {$breedLabel} – kiedy i jak?",
                 'keyword' => "odrobaczanie {$breedLabel}", 'intent' => 'informational'],
                ['title' => "Alergie wiosenne u {$breedLabel} – objawy i leczenie",
                 'keyword' => "alergie {$breedLabel}", 'intent' => 'informational'],
            ],
            'lato' => [
                ['title' => "Jak chronić {$breedLabel} przed upałem latem?",
                 'keyword' => "{$breedLabel} upał lato", 'intent' => 'informational'],
                ['title' => "Wakacje z {$breedLabel} – podróż i opieka",
                 'keyword' => "{$breedLabel} wakacje", 'intent' => 'informational'],
            ],
            'jesień' => [
                ['title' => "Jesienne szczepienia {$breedLabel} – harmonogram",
                 'keyword' => "szczepienia {$breedLabel}", 'intent' => 'informational'],
                ['title' => "Zima zbliża się – jak przygotować {$breedLabel} na chłody?",
                 'keyword' => "{$breedLabel} zima przygotowanie", 'intent' => 'informational'],
            ],
            'zima' => [
                ['title' => "Grudniowe kocięta – jak zadbać o kota na święta?",
                 'keyword' => "{$breedLabel} święta", 'intent' => 'informational'],
                ['title' => "Nowy rok, nowy kot – poradnik dla przyszłego właściciela {$breedLabel}",
                 'keyword' => "zakup {$breedLabel}", 'intent' => 'commercial'],
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