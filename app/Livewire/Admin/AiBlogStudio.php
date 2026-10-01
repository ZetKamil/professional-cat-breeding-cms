<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\Category;
use App\Services\AiAgent\BlogContentAgent;
use App\Services\AiBlogGeneratorService;
use App\Services\PostService;
use App\Services\TopicSuggesterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * AI Blog Studio — 3-step Livewire component with AI Agent & MCP Tools Integration.
 *
 * Step 1: Choose breed + AI Agent Topic Discovery
 * Step 2: Select cats from database to feature
 * Step 3: Review generated draft → save as DRAFT
 */
class AiBlogStudio extends Component
{
    // ─── Step tracking ───────────────────────────────────────────────
    public int $currentStep = 1;

    // ─── Step 1: Breed & Topic ───────────────────────────────────────
    public string $selectedBreed = 'bengalski';
    public string $selectedTopic = '';
    public string $customTopic   = '';

    // ─── Step 2: Animal selection ────────────────────────────────────
    /** @var array<string> ULIDs of selected animals */
    public array $selectedAnimalIds = [];

    // ─── Step 3: Generated content ───────────────────────────────────
    public ?array $generatedDraft    = null;
    public string $heroImageDataUri  = '';
    public bool   $isGenerating      = false;
    public bool   $isSavingDraft     = false;
    public string $errorMessage      = '';
    public string $successMessage    = '';
    public string $topicError        = '';
    public bool   $topicsLoaded      = false;
    public bool   $isFetchingTopics  = false;

    // ─── AI Agent & MCP State ─────────────────────────────────────────
    public string $agentSessionId    = '';
    public array  $agentLogs         = [];

    // ─── Computed data (loaded on mount) ─────────────────────────────
    public array  $breeds     = [];
    public array  $topics     = [];
    public array  $animals    = [];
    public array  $categories = [];

    public string  $topicSource = 'none';
    public ?string $topicDate   = null;

    public function mount(): void
    {
        $topicService = app(TopicSuggesterService::class);
        $this->breeds = $topicService->breeds();
        $this->loadAnimals();
        $this->categories = Category::orderBy('name')->get(['id', 'name'])->toArray();
    }

    // ─── Step 1 Actions (AI Agent Execution) ─────────────────────────

    public function selectBreed(string $breed): void
    {
        $this->selectedBreed     = $breed;
        $this->selectedTopic     = '';
        $this->customTopic       = '';
        $this->selectedAnimalIds = [];
        $this->topics            = [];
        $this->topicsLoaded      = false;
        $this->topicError        = '';
        $this->topicSource       = 'none';
        $this->agentLogs         = [];
        $this->loadAnimals();
    }

    /**
     * Synchronously execute AI Agent to fetch topics & MCP tools context.
     */
    public function fetchTopics(): void
    {
        $this->executeAgent(forceRefresh: false);
    }

    /**
     * Force-refresh topics via AI Agent (bypassing today's cache).
     */
    public function refreshTopics(): void
    {
        $this->executeAgent(forceRefresh: true);
    }

    private function executeAgent(bool $forceRefresh): void
    {
        $agent                  = app(BlogContentAgent::class);
        $this->isFetchingTopics = true;
        $this->topicError       = '';
        $this->errorMessage     = '';
        $this->topics           = [];
        $this->agentLogs        = [];
        $this->agentSessionId   = 'session_' . uniqid();

        $breedLabel = $this->breeds[$this->selectedBreed] ?? 'Kot Bengalski';

        try {
            // Execute Agent & MCP Tools synchronously
            $topics = $agent->runTopicDiscovery(
                $this->agentSessionId,
                $this->selectedBreed,
                $breedLabel,
                $forceRefresh
            );

            $this->topics       = $topics;
            $this->topicsLoaded = true;

            // Load execution logs from Cache
            $statusKey = "ai_agent_status_{$this->agentSessionId}";
            $cachedStatus = Cache::get($statusKey, []);
            $this->agentLogs   = $cachedStatus['logs'] ?? [];
            $this->topicSource = $cachedStatus['source'] ?? 'live_api';

        } catch (GeminiApiKeyMissingException $e) {
            $this->topicsLoaded = true;
            $this->topicError   = 'Klucz API Gemini nie jest skonfigurowany w pliku .env na serwerze (GEMINI_API_KEY). Wpisz własny temat poniżej.';
        } catch (GeminiServiceUnavailableException $e) {
            $this->topicsLoaded = true;
            $this->topicError   = 'Usługa Gemini AI jest niedostępna (Błąd HTTP 503 / Limit API). Wpisz własny temat poniżej lub spróbuj ponownie za chwilę.';
        } catch (\Throwable $e) {
            Log::warning('AiBlogStudio: Agent execution exception', ['error' => $e->getMessage()]);
            $this->topicsLoaded = true;
            $this->topicError   = 'Błąd pobierania tematów AI: ' . $e->getMessage() . '. Wpisz własny temat poniżej.';

            // Load logs up to error point
            $statusKey = "ai_agent_status_{$this->agentSessionId}";
            $cachedStatus = Cache::get($statusKey, []);
            $this->agentLogs = $cachedStatus['logs'] ?? [];
        } finally {
            $this->isFetchingTopics = false;
        }
    }

    public function selectTopic(int|string $indexOrTopic): void
    {
        if (is_int($indexOrTopic) || ctype_digit((string) $indexOrTopic)) {
            $idx = (int) $indexOrTopic;
            if (isset($this->topics[$idx]['title'])) {
                $title = $this->topics[$idx]['title'];
                $this->selectedTopic = $title;
                $this->customTopic   = $title;
            }
        } else {
            $this->selectedTopic = (string) $indexOrTopic;
            $this->customTopic   = (string) $indexOrTopic;
        }
        $this->errorMessage = '';
    }

    public function updatedCustomTopic(): void
    {
        if ($this->customTopic !== $this->selectedTopic) {
            $this->selectedTopic = '';
        }
        $this->errorMessage = '';
    }

    public function goToStep2(?string $topicOverride = null): void
    {
        $topic = trim($topicOverride ?? '') ?: trim($this->customTopic) ?: trim($this->selectedTopic);

        if (blank($topic)) {
            $this->errorMessage = 'Proszę wpisać własny temat artykułu lub wybrać temat z listy przed przejściem dalej.';
            return;
        }

        $this->customTopic   = $topic;
        $this->selectedTopic = $topic;

        $this->errorMessage  = '';
        $this->currentStep   = 2;
    }

    // ─── Step 2 Actions ─────────────────────────────────────────────

    public function toggleAnimal(string $animalId): void
    {
        if (in_array($animalId, $this->selectedAnimalIds, true)) {
            $this->selectedAnimalIds = array_values(
                array_filter($this->selectedAnimalIds, fn ($id) => $id !== $animalId)
            );
        } else {
            $this->selectedAnimalIds[] = $animalId;
        }
    }

    public function goToStep3(): void
    {
        $blogService           = app(AiBlogGeneratorService::class);
        $this->errorMessage   = '';
        $this->generatedDraft = null;
        $this->heroImageDataUri = '';
        $this->isGenerating   = true;
        $this->currentStep    = 3;

        $topic = trim($this->customTopic) ?: trim($this->selectedTopic);
        $breedLabel = $this->breeds[$this->selectedBreed] ?? $this->selectedBreed;

        try {
            $this->generatedDraft = $blogService->generateDraft(
                $breedLabel,
                $topic,
                $this->selectedAnimalIds
            );
        } catch (GeminiApiKeyMissingException $e) {
            $this->errorMessage = 'Klucz API Gemini nie jest skonfigurowany. Skontaktuj się z administratorem.';
            $this->currentStep  = 2;
        } catch (GeminiServiceUnavailableException $e) {
            $this->errorMessage = 'AI chwilowo niedostępne: ' . $e->getMessage() . ' Spróbuj ponownie za chwilę.';
            $this->currentStep  = 2;
        } catch (\Throwable $e) {
            $this->errorMessage = 'Wystąpił nieoczekiwany błąd podczas generowania: ' . $e->getMessage();
            $this->currentStep  = 2;
        } finally {
            $this->isGenerating = false;
        }
    }

    // ─── Step 3: Save Draft ─────────────────────────────────────────

    public function saveDraft(): void
    {
        if (! $this->generatedDraft) {
            return;
        }

        $postService  = app(PostService::class);
        $blogService  = app(AiBlogGeneratorService::class);

        $this->isSavingDraft  = true;
        $this->errorMessage   = '';
        $this->successMessage = '';

        try {
            $draft = $this->generatedDraft;

            $draft['is_published'] = false;
            $draft['published_at'] = null;
            $draft['user_id']      = Auth::id();

            $draft['body'] = $this->injectAnimalPhotos($draft['body'] ?? '');

            $post = $postService->create([
                'user_id'          => $draft['user_id'],
                'title'            => $draft['title'],
                'slug'             => $draft['slug'],
                'excerpt'          => $draft['excerpt'],
                'body'             => $draft['body'],
                'meta_title'       => $draft['meta_title'] ?? null,
                'meta_description' => $draft['meta_description'] ?? null,
                'is_published'     => false,
                'published_at'     => null,
                'categories'       => [],
            ]);

            if (! empty($draft['hero_image_prompt'])) {
                try {
                    $blogService->generateHeroImage($draft['hero_image_prompt'], $post);
                } catch (\Throwable $e) {
                    Log::warning('AI Blog Studio: failed to generate hero cover image', [
                        'post_id' => $post->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            $this->successMessage = "Szkic \"{$post->title}\" został zapisany! Okładka AI i zdjęcia kotów są już wstawione.";
            $this->generatedDraft  = null;
            $this->currentStep     = 1;
            $this->reset(['selectedTopic', 'customTopic', 'selectedAnimalIds', 'heroImageDataUri']);

            $this->dispatch('draft-saved', postSlug: $post->slug);

        } catch (\Throwable $e) {
            $this->errorMessage = 'Nie udało się zapisać szkicu: ' . $e->getMessage();
        } finally {
            $this->isSavingDraft = false;
        }
    }

    // ─── Navigation ─────────────────────────────────────────────────

    public function backToStep(int $step): void
    {
        if ($step >= 1 && $step < $this->currentStep) {
            $this->currentStep    = $step;
            $this->errorMessage   = '';
            $this->successMessage = '';

            if ($step < 3) {
                $this->generatedDraft   = null;
                $this->heroImageDataUri = '';
            }
        }
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.ai-blog-studio');
    }

    private function loadAnimals(): void
    {
        $blogService = app(AiBlogGeneratorService::class);
        $breedLabel  = $this->breeds[$this->selectedBreed] ?? null;
        $grouped     = $blogService->getAvailableAnimals($breedLabel);

        $this->animals = $grouped->map(
            fn (Collection $group) => $group->map(fn ($animal) => [
                'id'         => $animal->id,
                'name'       => $animal->name,
                'breed'      => $animal->breed,
                'color'      => $animal->color,
                'status'     => $animal->statusLabel(),
                'age'        => $animal->age(),
                'photo_url'  => $animal->media?->url() ?? '',
            ])->values()->toArray()
        )->toArray();
    }

    private function injectAnimalPhotos(string $body): string
    {
        if (empty($this->selectedAnimalIds)) {
            return $body;
        }

        $animals = \App\Models\Animal::published()
            ->with('gallery')
            ->whereIn('id', $this->selectedAnimalIds)
            ->get();

        if ($animals->isEmpty()) {
            return $body;
        }

        $galleryHtml = '<div class="ai-article-gallery row g-3 my-4">';

        foreach ($animals as $animal) {
            $photos = $animal->gallery->take(2);

            foreach ($photos as $photo) {
                $url = $photo->url();
                $alt = e($animal->name . ' — ' . $animal->breed);
                $galleryHtml .= <<<HTML
                    <div class="col-12 col-md-6">
                        <figure class="figure w-100">
                            <img src="{$url}" alt="{$alt}" class="figure-img img-fluid rounded" loading="lazy">
                            <figcaption class="figure-caption text-center">{$animal->name} — {$animal->breed}</figcaption>
                        </figure>
                    </div>
                HTML;
            }
        }

        $galleryHtml .= '</div>';

        return preg_replace('/<\/p>/', '</p>' . $galleryHtml, $body, 1) ?? $body . $galleryHtml;
    }
}