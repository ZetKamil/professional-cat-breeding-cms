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
 * Step 1: Choose breed + AI Agent Topic Discovery (or custom topic)
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
    public string $savedPostSlug     = '';
    public string $savedPostTitle    = '';
    public string $topicError        = '';
    public bool   $topicsLoaded      = false;
    public bool   $isFetchingTopics  = false;

    // ─── AI Agent & MCP State ─────────────────────────────────────────
    public string $agentSessionId    = '';
    public array  $agentLogs         = [];

    // ─── Computed data (loaded on mount) ─────────────────────────────
    public array  $breeds     = [];
    public array  $topics     = [];
    public array  $animals    = []; // Flat 0-indexed array for the selected breed
    public array  $categories = [];

    public string  $topicSource = 'none';
    public ?string $topicDate   = null;

    public function mount(): void
    {
        $topicService       = app(TopicSuggesterService::class);
        $this->breeds       = $topicService->breeds();
        $this->loadAnimals();
        $this->categories   = Category::orderBy('name')->get(['id', 'name'])->toArray();
        $this->setInitialTopic();
    }

    // ─── Step 1 Actions (AI Agent Execution) ─────────────────────────

    public function selectBreed(string $breed): void
    {
        $this->selectedBreed     = $breed;
        $this->selectedTopic     = '';
        $this->selectedAnimalIds = [];
        $this->topics            = [];
        $this->topicsLoaded      = false;
        $this->topicError        = '';
        $this->topicSource       = 'none';
        $this->agentLogs         = [];
        $this->loadAnimals();
        $this->setInitialTopic();
    }

    private function setInitialTopic(): void
    {
        $label = $this->breeds[$this->selectedBreed] ?? 'Kot Bengalski';
        $genitive = match ($this->selectedBreed) {
            'bengalski' => 'kota bengalskiego',
            'brytyjski' => 'kota brytyjskiego',
            'syjamski'  => 'kota syjamskiego',
            default     => mb_strtolower($label),
        };
        $this->customTopic   = "Żywienie i pielęgnacja {$genitive}";
        $this->selectedTopic = $this->customTopic;
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
            $this->topicError   = 'Klucz API Gemini nie jest skonfigurowany w pliku .env na serwerze (GEMINI_API_KEY). Możesz użyć domyślnego tematu lub wpisać własny poniżej.';
        } catch (GeminiServiceUnavailableException $e) {
            $this->topicsLoaded = true;
            $this->topicError   = 'Usługa Gemini AI jest niedostępna (' . $e->getMessage() . '). Sprawdź czy GEMINI_TEXT_MODEL w .env to aktualny model (rekomendowany: gemini-flash-latest). Możesz wpisać własny temat poniżej.';
        } catch (\Throwable $e) {
            Log::warning('AiBlogStudio: Agent execution exception', ['error' => $e->getMessage()]);
            $this->topicsLoaded = true;
            $this->topicError   = 'Błąd pobierania tematów AI: ' . $e->getMessage() . '. Możesz użyć domyślnego tematu lub wpisać własny poniżej.';

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

    public function goToStep2(): void
    {
        $topic = trim($this->customTopic) ?: trim($this->selectedTopic);

        if (blank($topic)) {
            $this->errorMessage = 'Proszę wpisać temat artykułu w polu tekstowym poniżej.';
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
            $draft = $blogService->generateDraft(
                $breedLabel,
                $topic,
                $this->selectedAnimalIds
            );

            // Distribute photos from selected cattery animals to the article sections
            if (!empty($draft['sections'])) {
                $draft['sections'] = $this->distributeAnimalPhotosToSections(
                    $draft['sections'],
                    $this->selectedAnimalIds
                );
                $draft['body'] = $blogService->sectionsToBody($draft['sections']);
            }

            $this->generatedDraft = $draft;
        } catch (GeminiApiKeyMissingException $e) {
            $this->errorMessage = 'Klucz API Gemini nie jest skonfigurowany w .env (GEMINI_API_KEY). Skontaktuj się z administratorem.';
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
            $draft['published_at'] = now();
            $draft['user_id']      = Auth::id();

            $sections = $draft['sections'] ?? [];
            $body = !empty($sections)
                ? $blogService->sectionsToBody($sections)
                : ($draft['body'] ?? '');

            $topicForCategory = trim($this->customTopic) ?: trim($this->selectedTopic);
            $assignedCategories = $this->detectCategoryForTopic($topicForCategory);

            $post = $postService->create([
                'user_id'             => $draft['user_id'],
                'title'               => $draft['title'],
                'slug'                => $draft['slug'],
                'excerpt'             => $draft['excerpt'],
                'sections'            => $sections,
                'body'                => $body,
                'featured_animal_ids' => $this->selectedAnimalIds ?? [],
                'meta_title'          => $draft['meta_title'] ?? null,
                'meta_description'    => $draft['meta_description'] ?? null,
                'is_published'        => false,
                'published_at'        => $draft['published_at'],
                'categories'          => $assignedCategories,
            ]);

            $heroCreated = false;
            if (! empty($draft['hero_image_prompt'])) {
                try {
                    $media = $blogService->generateHeroImage($draft['hero_image_prompt'], $post);
                    if ($media) {
                        $heroCreated = true;
                    }
                } catch (\Throwable $e) {
                    Log::warning('AI Blog Studio: failed to generate hero cover image via Imagen', [
                        'post_id' => $post->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            // If Imagen is unavailable or failed (e.g. Free Tier Google API key),
            // automatically attach the selected cat's photo as the Post's Featured Image!
            if (! $heroCreated) {
                $this->attachFeaturedImageFromAnimal($post, $this->selectedAnimalIds);
            }

            $this->savedPostSlug   = $post->slug;
            $this->savedPostTitle  = $post->title;
            $this->successMessage  = "Szkic \"{$post->title}\" został zapisany w nowym szablonie!";
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

        // Extract animals for selected breed into a simple 0-indexed flat array (prevents space keys in snapshot)
        $breedGroup  = $grouped->get($breedLabel) ?? collect();

        $this->animals = $breedGroup->map(fn ($animal) => [
            'id'         => $animal->id,
            'name'       => $animal->name,
            'breed'      => $animal->breed,
            'color'      => $animal->color,
            'status'     => $animal->statusLabel(),
            'age'        => $animal->age(),
            'photo_url'  => $animal->media?->url() ?? '',
        ])->values()->toArray();
    }

    private function injectAnimalPhotos(string $body): string
    {
        if (empty($this->selectedAnimalIds)) {
            return $body;
        }

        $animals = \App\Models\Animal::published()
            ->with(['media', 'gallery'])
            ->whereIn('id', $this->selectedAnimalIds)
            ->get();

        if ($animals->isEmpty()) {
            return $body;
        }

        $galleryHtml = '<div class="ai-article-gallery row g-3 my-4">';

        foreach ($animals as $animal) {
            // Get gallery photos or fall back to primary featured media
            $photos = $animal->gallery->isNotEmpty()
                ? $animal->gallery->take(2)
                : collect([$animal->media])->filter();

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

    /**
     * Attach a high-resolution photo from the selected cattery animal as the Post's Featured Image.
     * Ensures the post ALWAYS has a featured cover image, even when AI image generation (Imagen) is unavailable.
     */
    private function attachFeaturedImageFromAnimal(\App\Models\Post $post, array $animalIds): void
    {
        $animals = \App\Models\Animal::whereIn('id', $animalIds)
            ->with(['media', 'gallery'])
            ->get();

        if ($animals->isEmpty()) {
            $breedLabel = $this->breeds[$this->selectedBreed] ?? null;
            $animals = \App\Models\Animal::published()
                ->where('breed', 'like', "%{$breedLabel}%")
                ->with(['media', 'gallery'])
                ->get();
        }

        $sourceMedia = null;
        $sourceAnimal = null;

        foreach ($animals as $animal) {
            if ($animal->media) {
                $sourceMedia  = $animal->media;
                $sourceAnimal = $animal;
                break;
            }
            if ($animal->gallery->isNotEmpty()) {
                $sourceMedia  = $animal->gallery->first();
                $sourceAnimal = $animal;
                break;
            }
        }

        if (! $sourceMedia) {
            return;
        }

        try {
            $sourceDisk = $sourceMedia->disk ?? 'public';
            $sourcePath = $sourceMedia->path();

            if (\Illuminate\Support\Facades\Storage::disk($sourceDisk)->exists($sourcePath)) {
                $ext          = pathinfo($sourceMedia->filename, PATHINFO_EXTENSION) ?: 'jpg';
                $newFilename  = 'post_' . $post->id . '_' . uniqid() . '.' . $ext;
                $newDirectory = 'posts';
                $newPath      = $newDirectory . '/' . $newFilename;

                \Illuminate\Support\Facades\Storage::disk($sourceDisk)->copy($sourcePath, $newPath);

                \App\Models\Media::create([
                    'disk'          => $sourceDisk,
                    'directory'     => $newDirectory,
                    'filename'      => $newFilename,
                    'mime_type'     => $sourceMedia->mime_type ?: 'image/jpeg',
                    'size'          => $sourceMedia->size ?: 0,
                    'title'         => $post->title,
                    'alt_text'      => e(($sourceAnimal?->name ?? 'Kot') . ' — ' . $post->title),
                    'caption'       => $sourceAnimal ? "{$sourceAnimal->name} ({$sourceAnimal->breed})" : null,
                    'is_featured'   => true,
                    'mediable_type' => \App\Models\Post::class,
                    'mediable_id'   => $post->id,
                ]);

                \Illuminate\Support\Facades\Log::info('AI Blog Studio: attached animal photo as post featured image', [
                    'post_id'   => $post->id,
                    'animal_id' => $sourceAnimal?->id,
                    'filename'  => $newFilename,
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AI Blog Studio: failed to attach animal photo as post featured image', [
                'post_id' => $post->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * Przypisuje zdjęcia wybranych kotów z hodowli bezpośrednio do sekcji artykułu.
     */
    private function distributeAnimalPhotosToSections(array $sections, array $animalIds): array
    {
        $urls = [];

        if (!empty($animalIds)) {
            $animals = \App\Models\Animal::whereIn('id', $animalIds)
                ->with(['media', 'gallery'])
                ->get();

            foreach ($animals as $animal) {
                if ($animal->media) {
                    $urls[] = $animal->media->url();
                }
                if ($animal->gallery->isNotEmpty()) {
                    foreach ($animal->gallery as $photo) {
                        $urls[] = $photo->url();
                    }
                }
            }
        }

        // Jeśli brakuje zdjęć, pobierz zdjęcia innych kotów danej rasy z hodowli
        if (count($urls) < count($sections)) {
            $breedLabel = $this->breeds[$this->selectedBreed] ?? null;
            if ($breedLabel) {
                $otherAnimals = \App\Models\Animal::published()
                    ->where('breed', 'like', "%{$breedLabel}%")
                    ->whereNotIn('id', $animalIds)
                    ->with(['media', 'gallery'])
                    ->take(3)
                    ->get();

                foreach ($otherAnimals as $animal) {
                    if ($animal->media) {
                        $urls[] = $animal->media->url();
                    }
                    if ($animal->gallery->isNotEmpty()) {
                        foreach ($animal->gallery as $photo) {
                            $urls[] = $photo->url();
                        }
                    }
                }
            }
        }

        $urls = array_values(array_unique(array_filter($urls)));

        if (empty($urls)) {
            return $sections;
        }

        $photoIndex = 0;
        foreach ($sections as &$sec) {
            if (empty($sec['image_url']) && isset($urls[$photoIndex])) {
                $sec['image_url'] = $urls[$photoIndex];
                $photoIndex++;
            }
        }

        return $sections;
    }

    /**
     * Automatically suggest/assign a category matching the topic for the blog post.
     *
     * @return array<int> Category IDs
     */
    private function detectCategoryForTopic(string $topic): array
    {
        $lower = mb_strtolower($topic);

        $matchedSlug = match (true) {
            str_contains($lower, 'karm') || str_contains($lower, 'żywien') || str_contains($lower, 'diet') || str_contains($lower, 'barf') || str_contains($lower, 'mięs')
                => 'zywienie-holistyczne',

            str_contains($lower, 'wyprawk') || str_contains($lower, 'szczotk') || str_contains($lower, 'pielęgnac') || str_contains($lower, 'kąpiel') || str_contains($lower, 'kuwet') || str_contains($lower, 'drapak') || str_contains($lower, 'linieni')
                => 'wyprawka-i-pielegnacja',

            str_contains($lower, 'zdrow') || str_contains($lower, 'badani') || str_contains($lower, 'chorob') || str_contains($lower, 'genetyk') || str_contains($lower, 'hcm') || str_contains($lower, 'pkd') || str_contains($lower, 'szczepien') || str_contains($lower, 'kastrac')
                => 'zdrowie-i-genetyka',

            str_contains($lower, 'wychowan') || str_contains($lower, 'socjalizac') || str_contains($lower, 'zachowan') || str_contains($lower, 'dzieć') || str_contains($lower, 'dzieck') || str_contains($lower, 'pies') || str_contains($lower, 'miaucz')
                => 'socjalizacja-i-wychowanie',

            default
                => 'odmiany-i-rasy',
        };

        $category = \App\Models\Category::where('slug', $matchedSlug)->first();

        return $category ? [$category->id] : [];
    }
}