<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Jobs\RunBlogAgentJob;
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
 * Step 1: Choose breed + AI Agent Topic Discovery (Asynchronous & Resilient)
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
    public bool   $isAgentRunning    = false;
    public array  $agentLogs         = [];
    public string $agentLastMessage  = '';

    // ─── Computed data (loaded on mount) ─────────────────────────────
    public array  $breeds     = [];
    public array  $topics     = [];
    public array  $animals    = [];
    public array  $categories = [];

    public string  $topicSource = 'none';
    public ?string $topicDate   = null;

    public function mount(
        TopicSuggesterService   $topicService,
        AiBlogGeneratorService  $blogService
    ): void {
        $this->breeds     = $topicService->breeds();
        $this->loadAnimals($blogService);
        $this->categories = Category::orderBy('name')->get(['id', 'name'])->toArray();
    }

    // ─── Step 1 Actions (AI Agent Execution) ─────────────────────────

    public function selectBreed(string $breed, AiBlogGeneratorService $blogService): void
    {
        $this->selectedBreed     = $breed;
        $this->selectedTopic     = '';
        $this->customTopic       = '';
        $this->selectedAnimalIds = [];
        $this->topics            = [];
        $this->topicsLoaded      = false;
        $this->topicError        = '';
        $this->topicSource       = 'none';
        $this->isAgentRunning    = false;
        $this->agentLogs         = [];
        $this->loadAnimals($blogService);
    }

    /**
     * Dispatch AI Agent Job in background to execute MCP tools and discover topics.
     */
    public function fetchTopics(BlogContentAgent $agent, TopicSuggesterService $topicService): void
    {
        $this->startAgentExecution(forceRefresh: false, topicService: $topicService, agent: $agent);
    }

    /**
     * Force-refresh topics via AI Agent (bypassing today's cache).
     */
    public function refreshTopics(BlogContentAgent $agent, TopicSuggesterService $topicService): void
    {
        $this->startAgentExecution(forceRefresh: true, topicService: $topicService, agent: $agent);
    }

    private function startAgentExecution(bool $forceRefresh, TopicSuggesterService $topicService, BlogContentAgent $agent): void
    {
        $this->isFetchingTopics = true;
        $this->topicError       = '';
        $this->topics           = [];
        $this->agentLogs        = [];
        $this->agentSessionId   = 'session_' . uniqid();
        $this->isAgentRunning   = true;

        $breedLabel = $this->breeds[$this->selectedBreed] ?? 'Kot Bengalski';

        try {
            // Run Agent directly or via Queue Job depending on environment
            if (config('queue.default') === 'sync') {
                // Synchronous fallback execution for local env without background worker
                $topics = $agent->runTopicDiscovery($this->agentSessionId, $this->selectedBreed, $breedLabel, $forceRefresh);
                $this->topics       = $topics;
                $this->topicSource  = 'live_api';
                $this->topicsLoaded = true;
                $this->isAgentRunning = false;
            } else {
                // Async Queue Execution for Production
                RunBlogAgentJob::dispatch($this->agentSessionId, $this->selectedBreed, $breedLabel, $forceRefresh);
            }
        } catch (\Throwable $e) {
            Log::warning('AiBlogStudio: Agent execution exception', ['error' => $e->getMessage()]);
            $this->topicsLoaded   = true;
            $this->isAgentRunning = false;
            $this->topicError     = 'Nie udało się pobrać tematów przez Agenta: ' . $e->getMessage() . ' Wpisz własny temat poniżej.';
        } finally {
            $this->isFetchingTopics = false;
            $this->checkAgentStatus();
        }
    }

    /**
     * Polled by Livewire wire:poll while agent is running to update thought log & status.
     */
    public function checkAgentStatus(): void
    {
        if (empty($this->agentSessionId)) {
            return;
        }

        $key = "ai_agent_status_{$this->agentSessionId}";
        $data = Cache::get($key);

        if (! is_array($data)) {
            return;
        }

        $this->agentLogs        = $data['logs'] ?? [];
        $this->agentLastMessage  = $data['last_message'] ?? '';
        $state                  = $data['state'] ?? 'idle';

        if ($state === 'completed') {
            $this->topics          = $data['topics'] ?? [];
            $this->topicSource     = $data['source'] ?? 'live_api';
            $this->topicsLoaded    = true;
            $this->isAgentRunning  = false;
        } elseif ($state === 'failed') {
            $this->topicError      = $data['last_message'] ?? 'Błąd Agenta AI.';
            $this->topicsLoaded    = true;
            $this->isAgentRunning  = false;
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
    }

    public function updatedCustomTopic(): void
    {
        if ($this->customTopic !== $this->selectedTopic) {
            $this->selectedTopic = '';
        }
    }

    public function goToStep2(): void
    {
        $topic = trim($this->customTopic) ?: trim($this->selectedTopic);

        if (blank($topic)) {
            $this->errorMessage = 'Wybierz temat lub wpisz własny przed przejściem dalej.';
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

    public function goToStep3(AiBlogGeneratorService $blogService): void
    {
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

    public function saveDraft(PostService $postService, AiBlogGeneratorService $blogService): void
    {
        if (! $this->generatedDraft) {
            return;
        }

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

    private function loadAnimals(AiBlogGeneratorService $blogService): void
    {
        $breedLabel = $this->breeds[$this->selectedBreed] ?? null;
        $grouped    = $blogService->getAvailableAnimals($breedLabel);

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