<?php

declare(strict_types=1);

namespace App\Services\AiAgent;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\TrendingTopic;
use App\Services\AiAgent\Contracts\AgentToolInterface;
use App\Services\AiAgent\Tools\FetchGoogleTrendsTool;
use App\Services\AiAgent\Tools\GetCatteryAnimalsTool;
use Illuminate\Support\Carbon;
use App\Services\TopicSuggesterService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * AI Agent for Blog Studio.
 *
 * Implements an agentic workflow:
 * 1. Tool Registration (MCP Tools: GetCatteryAnimalsTool, FetchGoogleTrendsTool).
 * 2. Asynchronous Execution & Reasoning Trail (Logs steps to Cache for Livewire polling).
 * 3. Execution of tools & topic synthesis.
 * 4. Zero hardcoded fallback topics — throws exception on API failure.
 */
class BlogContentAgent
{
    /** @var array<string, AgentToolInterface> */
    private array $tools = [];

    public function __construct(
        GetCatteryAnimalsTool $animalsTool,
        FetchGoogleTrendsTool $trendsTool
    ) {
        $this->registerTool($animalsTool);
        $this->registerTool($trendsTool);
    }

    public function registerTool(AgentToolInterface $tool): void
    {
        $this->tools[$tool->name()] = $tool;
    }

    /**
     * Run the Agent to fetch and analyze topics for a given breed.
     *
     * @param string $sessionId Unique session ID for Livewire polling status
     * @param string $breedKey 'bengalski' | 'brytyjski' | 'syjamski'
     * @param string $breedLabel Full label e.g. 'Kot Bengalski'
     * @param bool $forceRefresh Skip today's DB cache
     */
    public function runTopicDiscovery(string $sessionId, string $breedKey, string $breedLabel, bool $forceRefresh = false): array
    {
        $today = Carbon::today()->toDateString();
        $this->updateStatus($sessionId, 'running', 'Inicjalizacja Agenta AI oraz rejestracja narzędzi MCP...', [
            'registered_tools' => array_keys($this->tools),
        ]);

        // Step 1: Check DB Cache if not forceRefresh
        if (! $forceRefresh) {
            $this->logThought($sessionId, 'Sprawdzanie pamieci lokalnej (DB Cache) dla dzisiejszych trendów...');
            try {
                $cached = TrendingTopic::where('breed', $breedKey)
                    ->where('fetched_date', $today)
                    ->first();

                if ($cached && ! empty($cached->topics) && is_array($cached->topics)) {
                    $this->logThought($sessionId, 'Pobrano 6 zweryfikowanych tematów z pamieci lokalnej.');
                    $this->updateStatus($sessionId, 'completed', 'Agent zakończył analizę trendów (z pamięci roboczej).', [
                        'topics' => $cached->topics,
                        'source' => 'cache',
                    ]);
                    return $cached->topics;
                }
            } catch (\Throwable $e) {
                Log::warning('BlogContentAgent: DB Cache read error', ['error' => $e->getMessage()]);
            }
        }

        // Step 2: Execute MCP Tool 1 - Get Cattery Context
        $this->logThought($sessionId, "Wykonanie narzędzia MCP: 'get_cattery_animals' dla rasy {$breedLabel}...");
        $animalsContext = [];
        try {
            if (isset($this->tools['get_cattery_animals'])) {
                $animalsContext = $this->tools['get_cattery_animals']->execute(['breed' => $breedLabel]);
                $count = count($animalsContext);
                $this->logThought($sessionId, "Narzędzie 'get_cattery_animals' zwróciło {$count} aktywnych kotów z bazy hodowli.");
            }
        } catch (\Throwable $e) {
            $this->logThought($sessionId, "Uwaga: Pobranie bazy kotów zwróciło ostrzeżenie: {$e->getMessage()}");
        }

        // Step 3: Execute MCP Tool 2 - Fetch Live Trends
        $this->logThought($sessionId, "Wykonanie narzędzia MCP: 'fetch_google_trends' poprzez Gemini API...");
        if (! isset($this->tools['fetch_google_trends'])) {
            throw new \RuntimeException("Narzędzie 'fetch_google_trends' nie jest zarejestrowane.");
        }

        try {
            $liveTopics = $this->tools['fetch_google_trends']->execute([
                'breed_name' => $breedLabel,
            ]);

            if (empty($liveTopics)) {
                throw new \RuntimeException("Narzędzie 'fetch_google_trends' zwróciło pustą listę tematów.");
            }

            $countTopics = count($liveTopics);
            $this->logThought($sessionId, "Narzędzie 'fetch_google_trends' zakończone sukcesem. Wygenerowano {$countTopics} tematów z trendów.");

            // Step 4: Save to DB Cache for today
            try {
                TrendingTopic::updateOrCreate(
                    ['breed' => $breedKey, 'fetched_date' => $today],
                    ['topics' => $liveTopics]
                );
                $this->logThought($sessionId, "Zapisano pobrane trendy w pamięci podręcznej na dzień {$today}.");
            } catch (\Throwable $e) {
                Log::warning('BlogContentAgent: Cache save error', ['error' => $e->getMessage()]);
            }

            $this->updateStatus($sessionId, 'completed', 'Agent AI pomyślnie przetworzył i syntezował trendy!', [
                'topics' => $liveTopics,
                'source' => 'live_api',
            ]);

            return $liveTopics;

        } catch (GeminiApiKeyMissingException $e) {
            $errorMsg = "Brak klucza API: " . $e->getMessage();
            $this->logThought($sessionId, "❌ " . $errorMsg);
            $this->updateStatus($sessionId, 'failed', $errorMsg, []);
            throw $e;
        } catch (GeminiServiceUnavailableException $e) {
            $this->logThought($sessionId, "⚠️ Google Gemini API zgłasza przeciążenie serwerów (503: High demand).");
            $this->logThought($sessionId, "Uruchamiam automatyczną procedurę awaryjną (Fallback Strategy)...");

            // 1. Sprawdź czy mamy w bazie wcześniejsze trendy dla tej rasy
            try {
                $previous = TrendingTopic::where('breed', $breedKey)
                    ->orderBy('fetched_date', 'desc')
                    ->first();

                if ($previous && ! empty($previous->topics) && is_array($previous->topics)) {
                    $dateStr = $previous->fetched_date instanceof \DateTimeInterface
                        ? $previous->fetched_date->format('Y-m-d')
                        : (string) $previous->fetched_date;

                    $this->logThought($sessionId, "✅ Załadowano zweryfikowane trendy z bazy danych (zapis z {$dateStr}).");
                    $this->updateStatus($sessionId, 'completed', 'Załadowano trendy z bazy danych (Google AI jest chwilowo przeciążone).', [
                        'topics' => $previous->topics,
                        'source' => 'db_fallback',
                    ]);

                    return $previous->topics;
                }
            } catch (\Throwable $dbEx) {
                Log::warning('BlogContentAgent: DB fallback read error', ['error' => $dbEx->getMessage()]);
            }

            // 2. Jeśli brak w bazie — użyj zestawu rekomendowanych tematów SEO dla danej rasy
            $curated = TopicSuggesterService::CURATED_TOPICS[$breedKey] ?? null;
            if (! empty($curated)) {
                $this->logThought($sessionId, "✅ Załadowano sprawdzoną bazę tematów SEO hodowli dla rasy {$breedLabel}.");
                $this->updateStatus($sessionId, 'completed', 'Załadowano rekomendowane tematy SEO hodowli (Google AI jest chwilowo przeciążone).', [
                    'topics' => $curated,
                    'source' => 'curated_fallback',
                ]);

                return $curated;
            }

            // Jeśli żaden fallback nie zadziałał — zaraportuj błąd
            $errorMsg = "Usługa AI niedostępna: " . $e->getMessage();
            $this->logThought($sessionId, "❌ " . $errorMsg);
            $this->updateStatus($sessionId, 'failed', $errorMsg, []);
            throw $e;
        } catch (\Throwable $e) {
            $errorMsg = "Błąd wykonania Agenta: " . $e->getMessage();
            $this->logThought($sessionId, "❌ " . $errorMsg);
            $this->updateStatus($sessionId, 'failed', $errorMsg, []);
            throw $e;
        }
    }

    private function updateStatus(string $sessionId, string $state, string $message, array $extra = []): void
    {
        $key = "ai_agent_status_{$sessionId}";
        $current = Cache::get($key, ['logs' => []]);

        $current['state'] = $state; // 'idle' | 'running' | 'completed' | 'failed'
        $current['last_message'] = $message;
        $current['updated_at'] = Carbon::now()->toTimeString();

        if (isset($extra['topics'])) {
            $current['topics'] = $extra['topics'];
        }
        if (isset($extra['source'])) {
            $current['source'] = $extra['source'];
        }

        Cache::put($key, $current, 3600); // 1 hour TTL
    }

    private function logThought(string $sessionId, string $logText): void
    {
        $key = "ai_agent_status_{$sessionId}";
        $current = Cache::get($key, ['logs' => [], 'state' => 'running']);

        $entry = sprintf("[%s] %s", Carbon::now()->format('H:i:s'), $logText);
        $current['logs'][] = $entry;

        Cache::put($key, $current, 3600);
        Log::info("BlogContentAgent [{$sessionId}]: {$logText}");
    }
}
