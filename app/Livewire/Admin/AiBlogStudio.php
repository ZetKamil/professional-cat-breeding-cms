<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\Animal;
use App\Models\Category;
use App\Models\Post;
use App\Services\AiAgent\BlogContentAgent;
use App\Services\AiBlogGeneratorService;
use App\Services\PostService;
use App\Services\TopicSuggesterService;
use App\Support\ImageGeometry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
    public ?array $generatedDraft       = null;
    public string $heroImageDataUri     = '';
    public string $selectedHeroAnimalId = '';
    public bool   $isGenerating         = false;
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

    public function boot(): void
    {
        abort_unless(Auth::user()?->can('create', \App\Models\Post::class), 403);
    }

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
        $this->selectedBreed        = $breed;
        $this->selectedTopic        = '';
        $this->selectedAnimalIds    = [];
        $this->selectedHeroAnimalId = '';
        $this->topics               = [];
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
        $this->errorMessage         = '';
        $this->generatedDraft       = null;
        $this->heroImageDataUri     = '';
        $this->selectedHeroAnimalId = '';
        $this->isGenerating         = true;
        $this->currentStep          = 3;

        $topic = trim($this->customTopic) ?: trim($this->selectedTopic);
        $breedLabel = $this->breeds[$this->selectedBreed] ?? $this->selectedBreed;

        try {
            $draft = $blogService->generateDraft(
                $breedLabel,
                $topic,
                $this->selectedAnimalIds
            );

            // 1. Distribute photos from selected cattery animals to the article sections
            if (!empty($draft['sections'])) {
                $draft['sections'] = $this->distributeAnimalPhotosToSections(
                    $draft['sections'],
                    $this->selectedAnimalIds
                );

                // 2. Auto-fill any remaining sections without images with thematic AI images
                $draft['sections'] = $this->assignAiPhotosToEmptySections(
                    $draft['sections'],
                    $breedLabel,
                    $topic
                );

                $draft['body'] = $blogService->sectionsToBody($draft['sections']);
            }

            // Set wide-framed hero image preview URL
            if (!empty($draft['hero_image_prompt'])) {
                $draft['hero_image_preview_url'] = AiBlogGeneratorService::buildPollinationsUrl(
                    (string) $draft['hero_image_prompt'],
                    breed: $this->selectedBreed
                );
            }

            // Auto-detect initial hero layout: Split Hero for portrait/square photos, Magazine Frame for landscape
            $heroUrl = (string) ($draft['hero_image_preview_url'] ?? '');
            $geo = ImageGeometry::analyze($heroUrl);
            $draft['hero_layout'] = $geo['is_portrait'] ? 'split' : 'magazine';

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

    public function regenerateAiHero(): void
    {
        if (! $this->generatedDraft) {
            return;
        }

        $this->selectedHeroAnimalId = '';
        $prompt = (string) ($this->generatedDraft['hero_image_prompt'] ?? ($this->generatedDraft['title'] ?? ''));
        $this->generatedDraft['hero_image_preview_url'] = AiBlogGeneratorService::buildPollinationsUrl(
            $prompt,
            seed: random_int(1, 999999),
            breed: $this->selectedBreed
        );
        $this->generatedDraft['hero_layout'] = 'magazine';
    }

    public function selectHeroFromAnimal(string $animalId): void
    {
        if (! $this->generatedDraft) {
            return;
        }

        $animal = Animal::whereKey($animalId)->with(['media', 'gallery'])->first();
        if (! $animal) {
            return;
        }

        $photoUrl = $animal->media?->url() ?? $animal->gallery->first()?->url() ?? '';
        if ($photoUrl === '') {
            return;
        }

        $this->selectedHeroAnimalId = (string) $animal->id;
        $this->generatedDraft['hero_image_preview_url'] = $photoUrl;

        // Auto-assign Split Hero if animal photo is vertical / square to prevent awkward stretching
        $geo = ImageGeometry::analyze($photoUrl);
        $this->generatedDraft['hero_layout'] = $geo['is_portrait'] ? 'split' : 'magazine';
    }

    public function setHeroLayout(string $layout): void
    {
        if ($this->generatedDraft) {
            $this->generatedDraft['hero_layout'] = in_array($layout, ['split', 'magazine', 'auto'], true) ? $layout : 'split';
        }
    }

    public function setSectionLayout(int $index, string $layout): void
    {
        if (isset($this->generatedDraft['sections'][$index])) {
            $this->generatedDraft['sections'][$index]['layout'] = in_array($layout, ['duo', 'portrait', 'landscape', 'auto'], true) ? $layout : 'auto';
        }
    }

    // ─── Step 3: Save Draft / Publish ────────────────────────────────

    public function saveDraft(bool $publishNow = false): void
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

            $draft['is_published'] = $publishNow;
            $draft['published_at'] = $publishNow ? now() : null;
            $draft['user_id']      = Auth::id();

            $baseSlug = Str::slug((string) ($draft['slug'] ?: ($draft['title'] ?? 'artykul'))) ?: 'artykul-' . Str::lower(Str::random(6));
            $uniqueSlug = $baseSlug;
            $suffix = 2;
            while (Post::withTrashed()->where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $baseSlug . '-' . $suffix++;
            }

            $validAnimalIds = Animal::query()
                ->whereIn('id', array_filter((array) ($this->selectedAnimalIds ?? [])))
                ->pluck('id')
                ->all();

            $sections = $draft['sections'] ?? [];
            $topicForCategory = trim($this->customTopic) ?: trim($this->selectedTopic);
            $assignedCategories = $this->detectCategoryForTopic($topicForCategory);

            $post = $postService->create([
                'user_id'             => $draft['user_id'],
                'title'               => Str::limit((string) ($draft['title'] ?? ''), 255, ''),
                'slug'                => $uniqueSlug,
                'excerpt'             => $draft['excerpt'] ?? null,
                'sections'            => $sections,
                'hero_layout'         => $draft['hero_layout'] ?? 'split',
                'body'                => !empty($sections) ? $blogService->sectionsToBody($sections) : ($draft['body'] ?? ''),
                'featured_animal_ids' => $validAnimalIds,
                'meta_title'          => isset($draft['meta_title']) ? Str::limit((string) $draft['meta_title'], 255, '') : null,
                'meta_description'    => isset($draft['meta_description']) ? Str::limit((string) $draft['meta_description'], 500, '') : null,
                'is_published'        => $publishNow,
                'published_at'        => $draft['published_at'],
                'categories'          => $assignedCategories,
            ]);

            // Persist remote section images locally so they are permanently stored in /public/storage/posts/
            if (!empty($sections)) {
                $persistedSections = $this->persistRemoteSectionImages($sections, $post->id);
                if ($persistedSections !== $sections) {
                    $post->update([
                        'sections' => $persistedSections,
                        'body'     => $blogService->sectionsToBody($persistedSections),
                    ]);
                }
            }

            // Cover Image Generation / Attachment (WYSIWYG: honor explicit animal choice or exact previewed cover)
            $heroCreated = false;
            if ($this->selectedHeroAnimalId !== '') {
                $heroCreated = $this->attachFeaturedImageFromAnimal($post, [$this->selectedHeroAnimalId]);
            } elseif (! empty($draft['hero_image_preview_url'])) {
                try {
                    $media = $blogService->generateHeroImageFromPreview(
                        (string) $draft['hero_image_preview_url'],
                        (string) ($draft['hero_image_prompt'] ?? ''),
                        $post
                    );
                    if ($media) {
                        $heroCreated = true;
                    }
                } catch (\Throwable $e) {
                    Log::warning('AI Blog Studio: failed to persist preview hero cover image', [
                        'post_id' => $post->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            } elseif (! empty($draft['hero_image_prompt'])) {
                try {
                    $media = $blogService->generateHeroImage($draft['hero_image_prompt'], $post);
                    if ($media) {
                        $heroCreated = true;
                    }
                } catch (\Throwable $e) {
                    Log::warning('AI Blog Studio: failed to generate hero cover image', [
                        'post_id' => $post->id,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            // If AI generation failed, automatically attach the selected cat's photo as the Post's Featured Image
            if (! $heroCreated) {
                $this->attachFeaturedImageFromAnimal($post, $this->selectedAnimalIds);
            }

            $this->savedPostSlug   = $post->slug;
            $this->savedPostTitle  = $post->title;
            $actionVerb            = $publishNow ? 'opublikowany na blogu' : 'zapisany jako szkic';
            $this->successMessage  = "Artykuł \"{$post->title}\" został {$actionVerb}!";
            $this->generatedDraft  = null;
            $this->currentStep     = 1;
            $this->reset(['selectedTopic', 'customTopic', 'selectedAnimalIds', 'heroImageDataUri', 'selectedHeroAnimalId']);

            $this->dispatch('draft-saved', postSlug: $post->slug);

        } catch (\Throwable $e) {
            $this->errorMessage = 'Nie udało się zapisać wpisu: ' . $e->getMessage();
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
                $this->generatedDraft       = null;
                $this->heroImageDataUri     = '';
                $this->selectedHeroAnimalId = '';
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

    /**
     * Attach a high-resolution photo from the selected cattery animal as the Post's Featured Image.
     * Ensures the post ALWAYS has a featured cover image, even when AI image generation (Imagen) is unavailable.
     */
    private function attachFeaturedImageFromAnimal(\App\Models\Post $post, array $animalIds): bool
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
            return false;
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
                    'alt_text'      => ($sourceAnimal?->name ?? 'Kot') . ' — ' . $post->title,
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

                return true;
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AI Blog Studio: failed to attach animal photo as post featured image', [
                'post_id' => $post->id,
                'error'   => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Przypisuje zdjęcia wybranych kotów z hodowli bezpośrednio do sekcji artykułu (round-robin między wybranymi kotami).
     * Bada geometrię (ImageGeometry) i jeśli w puli pozostają dodatkowe zdjęcia pionowe, łączy je w Smartfonowy Duet (duo).
     */
    private function distributeAnimalPhotosToSections(array $sections, array $animalIds): array
    {
        $perAnimalPhotos = [];

        if (!empty($animalIds)) {
            $animals = Animal::whereIn('id', $animalIds)
                ->with(['media', 'gallery'])
                ->get();

            foreach ($animals as $animal) {
                $catPhotos = [];
                if ($animal->media) {
                    $catPhotos[] = [
                        'url'     => $animal->media->url(),
                        'caption' => "{$animal->name} ({$animal->breed})",
                    ];
                }
                if ($animal->gallery->isNotEmpty()) {
                    foreach ($animal->gallery as $photo) {
                        $catPhotos[] = [
                            'url'     => $photo->url(),
                            'caption' => "{$animal->name} ({$animal->breed})",
                        ];
                    }
                }

                // Unikalne zdjęcia dla danego kota
                $unique = [];
                foreach ($catPhotos as $cp) {
                    $unique[$cp['url']] = $cp;
                }
                if (!empty($unique)) {
                    $perAnimalPhotos[] = array_values($unique);
                }
            }
        }

        // Round-robin: najpierw po 1 zdjęciu z każdego kota, potem drugie, etc.
        $orderedPhotos = [];
        $maxDepth = !empty($perAnimalPhotos) ? max(array_map('count', $perAnimalPhotos)) : 0;
        for ($round = 0; $round < $maxDepth; $round++) {
            foreach ($perAnimalPhotos as $catPhotos) {
                if (isset($catPhotos[$round])) {
                    $orderedPhotos[] = $catPhotos[$round];
                }
            }
        }

        // Jeśli brakuje zdjęć, dopełnij zdjęciami innych kotów danej rasy
        if (count($orderedPhotos) < count($sections)) {
            $breedLabel = $this->breeds[$this->selectedBreed] ?? null;
            if ($breedLabel) {
                $otherAnimals = Animal::published()
                    ->where('breed', 'like', "%{$breedLabel}%")
                    ->whereNotIn('id', $animalIds)
                    ->with(['media', 'gallery'])
                    ->take(3)
                    ->get();

                foreach ($otherAnimals as $other) {
                    if ($other->media) {
                        $orderedPhotos[] = [
                            'url'     => $other->media->url(),
                            'caption' => "{$other->name} ({$other->breed})",
                        ];
                    }
                    if ($other->gallery->isNotEmpty()) {
                        foreach ($other->gallery as $photo) {
                            $orderedPhotos[] = [
                                'url'     => $photo->url(),
                                'caption' => "{$other->name} ({$other->breed})",
                            ];
                        }
                    }
                }
            }
        }

        // Unikalne zdjęcia w kolejce
        $uniqueOrdered = [];
        foreach ($orderedPhotos as $op) {
            $uniqueOrdered[$op['url']] = $op;
        }
        $orderedPhotos = array_values($uniqueOrdered);

        if (empty($orderedPhotos)) {
            return $sections;
        }

        // 1. Przypisanie głównego zdjęcia do każdej sekcji (gwarantuje round-robin między kotami)
        $photoIndex = 0;
        foreach ($sections as $sIdx => &$sec) {
            if (empty($sec['image_url']) && isset($orderedPhotos[$photoIndex])) {
                $p = $orderedPhotos[$photoIndex++];
                $sec['image_url']     = $p['url'];
                $sec['image_caption'] = $p['caption'];

                $geo = ImageGeometry::analyze($p['url']);
                $sec['orientation']   = $geo['orientation'];
                $sec['layout']        = $geo['is_portrait'] ? 'portrait' : 'landscape';
            }
        }

        // 2. Jeśli w puli pozostały dodatkowe zdjęcia pionowe ze smartfona, sparuj je w sekcje DUO
        while ($photoIndex < count($orderedPhotos)) {
            $leftover = $orderedPhotos[$photoIndex++];
            $leftGeo = ImageGeometry::analyze($leftover['url']);

            $paired = false;
            foreach ($sections as $sIdx => &$sec) {
                // Nie paruj sekcji 0 (intro), tylko kolejne sekcje
                if ($sIdx > 0 && !empty($sec['image_url']) && empty($sec['image_url_2'])) {
                    $secGeo = ImageGeometry::analyze($sec['image_url']);
                    if ($secGeo['is_portrait'] && $leftGeo['is_portrait']) {
                        $sec['layout']          = 'duo';
                        $sec['image_url_2']     = $leftover['url'];
                        $sec['image_caption_2'] = $leftover['caption'];
                        $paired = true;
                        break;
                    }
                }
            }

            if (!$paired) {
                break;
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

    /**
     * Auto-assign thematic AI photo URLs to any sections that still lack images.
     * Uses English semantic topic translation and strict breed phenotypes.
     */
    private function assignAiPhotosToEmptySections(array $sections, string $breedLabel, string $topic): array
    {
        foreach ($sections as $idx => &$sec) {
            if (empty($sec['image_url'])) {
                $rawPrompt = !empty($sec['image_prompt']) ? (string) $sec['image_prompt'] : '';
                if (blank($rawPrompt) || preg_match('/[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]/u', $rawPrompt)) {
                    $rawPrompt = AiBlogGeneratorService::getThematicEnglishScene($topic, (string) ($sec['heading'] ?? ''));
                }

                $sec['image_url']   = AiBlogGeneratorService::buildPollinationsUrl(
                    $rawPrompt,
                    width: 1200,
                    height: 675,
                    breed: $breedLabel
                );
                $sec['layout']      = $sec['layout'] ?? 'landscape';
                $sec['orientation'] = 'landscape';
            }
        }

        return $sections;
    }

    /**
     * Download remote section images in parallel and persist them to local storage to guarantee permanent availability.
     * Persists both image_url and image_url_2 (for Duo layouts).
     */
    private function persistRemoteSectionImages(array $sections, int $postId): array
    {
        $remoteUrls = [];
        foreach ($sections as $idx => $sec) {
            $url1 = $sec['image_url'] ?? '';
            if (str_starts_with($url1, 'https://image.pollinations.ai/') || str_starts_with($url1, 'https://images.unsplash.com/')) {
                $remoteUrls["{$idx}_1"] = $url1;
            }
            $url2 = $sec['image_url_2'] ?? '';
            if (str_starts_with($url2, 'https://image.pollinations.ai/') || str_starts_with($url2, 'https://images.unsplash.com/')) {
                $remoteUrls["{$idx}_2"] = $url2;
            }
        }

        if (empty($remoteUrls)) {
            return $sections;
        }

        try {
            $responses = \Illuminate\Support\Facades\Http::pool(function (\Illuminate\Http\Client\Pool $pool) use ($remoteUrls) {
                $reqs = [];
                foreach ($remoteUrls as $key => $url) {
                    $reqs[$key] = $pool->as((string) $key)->connectTimeout(4)->timeout(8)->get($url);
                }

                return $reqs;
            });

            foreach ($remoteUrls as $key => $url) {
                $response = $responses[(string) $key] ?? null;
                if ($response instanceof \Illuminate\Http\Client\Response && $response->successful() && strlen($response->body()) > 4000) {
                    [$idx, $slot] = explode('_', (string) $key);
                    $idx = (int) $idx;
                    $dir = 'posts';
                    $filename = "sec_{$postId}_{$idx}_{$slot}_" . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put("{$dir}/{$filename}", $response->body());

                    // Copy to public/storage if not symlinked
                    $targetPath = public_path("storage/{$dir}/{$filename}");
                    if (!\Illuminate\Support\Facades\File::isDirectory(dirname($targetPath))) {
                        \Illuminate\Support\Facades\File::makeDirectory(dirname($targetPath), 0755, true, true);
                    }
                    \Illuminate\Support\Facades\File::put($targetPath, $response->body());

                    if ($slot === '1') {
                        $sections[$idx]['image_url'] = asset("storage/{$dir}/{$filename}");
                    } else {
                        $sections[$idx]['image_url_2'] = asset("storage/{$dir}/{$filename}");
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AI Blog Studio: could not persist remote section image locally', [
                'error' => $e->getMessage(),
            ]);
        }

        return $sections;
    }
}