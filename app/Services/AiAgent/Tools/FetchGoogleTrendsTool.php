<?php

declare(strict_types=1);

namespace App\Services\AiAgent\Tools;

use App\Services\AiAgent\Contracts\AgentToolInterface;
use App\Services\GeminiService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * MCP Tool: Fetches live trending search queries in Poland for cat breeds via Gemini API.
 */
class FetchGoogleTrendsTool implements AgentToolInterface
{
    public function __construct(
        private readonly GeminiService $gemini
    ) {}

    public function name(): string
    {
        return 'fetch_google_trends';
    }

    public function description(): string
    {
        return 'Fetches search-trending blog topics and high-volume keywords in Poland for a specific cat breed.';
    }

    public function execute(array $args = []): array
    {
        $breedName = $args['breed_name'] ?? 'Kot Bengalski';
        $monthYear = Carbon::now()->translatedFormat('F Y');

        Log::info('MCP Tool Execution: fetch_google_trends', ['breed_name' => $breedName]);

        $systemPrompt = <<<PROMPT
You are an expert SEO trend analyst for cat breeding catteries in Poland.
Suggest 6 search-trending blog post topics in natural Polish for breed: {$breedName}.

Respond ONLY with a valid JSON array of 6 objects:
[
  {
    "title": "Tytuł po polsku",
    "keyword": "słowo kluczowe",
    "intent": "informational"
  }
]
PROMPT;

        $userPrompt = "Fetch 6 Google search trends for {$breedName} for {$monthYear} in Poland.";
        $animalsContext = $args['animals_context'] ?? [];
        if (is_array($animalsContext) && ! empty($animalsContext)) {
            $animalNames = collect($animalsContext)
                ->map(fn ($animal) => trim((string) ($animal['name'] ?? '') . ' (' . (string) ($animal['status'] ?? '') . ')'))
                ->filter()
                ->take(8)
                ->implode(', ');
            if ($animalNames !== '') {
                $userPrompt .= " Cattery context animals currently in cattery: {$animalNames}.";
            }
        }

        $raw = $this->gemini->generateText($systemPrompt, $userPrompt);
        $topics = GeminiService::decodeTopicSuggestions($raw);

        if (! empty($topics)) {
            return $topics;
        }

        throw new \RuntimeException("Nie udało się sparsować wyników trendów z API Gemini.");
    }
}
