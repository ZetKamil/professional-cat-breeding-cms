<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Exceptions\GeminiApiKeyMissingException;
use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\Category;
use App\Services\AiBlogGeneratorService;
use App\Services\PostService;
use App\Services\TopicSuggesterService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * AI Blog Studio — 3-step Livewire component.
 *
 * Step 1: Choose breed + topic
 * Step 2: Select cats from database to feature
 * Step 3: Review generated draft → save as DRAFT
 *
 * Safety guarantees:
 * - Posts are ALWAYS saved as is_published = false (draft)
 * - Animal photos in article come from real DB gallery, not AI
 * - API errors show friendly messages, never HTTP 500
 * - Generate button is disabled during processing (wire:loading)
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

    // ─── Computed data (loaded on mount) ─────────────────────────────
    public array          $breeds    = [];
    public array          $topics    = [];
    public array          $animals   = [];  // grouped by breed: ['breed' => [Animal, ...]]
    public array          $categories = [];

    public function mount(
        TopicSuggesterService   $topicService,
        AiBlogGeneratorService  $blogService
    ): void {
        $this->breeds     = $topicService->breeds();
        $this->loadTopics($topicService, $this->selectedBreed);
        $this->loadAnimals($blogService);
        $this->categories = Category::orderBy('name')->get(['id', 'name'])->toArray();
    }

    // ─── Step 1 Actions ─────────────────────────────────────────────

    public function selectBreed(string $breed, TopicSuggesterService $topicService, AiBlogGeneratorService $blogService): void
    {
        $this->selectedBreed     = $breed;
        $this->selectedTopic     = '';
        $this->selectedAnimalIds = [];
        $this->loadTopics($topicService, $breed);
        $this->loadAnimals($blogService);
    }

    public function loadTopics(TopicSuggesterService $topicService, string $breed): void
    {
        $this->topicError = '';
        $this->topics     = [];

        try {
            $this->topics = $topicService->suggest($breed);
        } catch (GeminiApiKeyMissingException) {
            $this->topicError = 'Klucz API Gemini nie jest skonfigurowany w pliku .env na serwerze.';
        } catch (GeminiServiceUnavailableException $e) {
            $this->topicError = 'Usługa AI jest chwilowo niedostępna (' . $e->getMessage() . ').';
        } catch (\Throwable $e) {
            $this->topicError = 'Nie udało się pobrać aktualnych trendów: ' . $e->getMessage();
        }
    }

    public function selectTopic(string $topic): void
    {
        $this->selectedTopic = $topic;
        $this->customTopic   = $topic;
    }

    public function updatedCustomTopic(): void
    {
        if ($this->customTopic !== $this->selectedTopic) {
            $this->selectedTopic = '';
        }
    }

    public function goToStep2(): void
    {
        $topic = $this->customTopic ?: $this->selectedTopic;

        if (blank($topic)) {
            $this->errorMessage = 'Wybierz temat lub wpisz własny przed przejściem dalej.';
            return;
        }

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

        $topic = $this->customTopic ?: $this->selectedTopic;
        $breedLabel = $this->breeds[$this->selectedBreed] ?? $this->selectedBreed;

        try {
            $this->generatedDraft = $blogService->generateDraft(
                $breedLabel,
                $topic,
                $this->selectedAnimalIds
            );
        } catch (GeminiApiKeyMissingException $e) {
            $this->errorMessage = 'Klucz API Gemini nie jest skonfigurowany. Skontaktuj się z administratorem.';
            $this->currentStep  = 1;
        } catch (GeminiServiceUnavailableException $e) {
            $this->errorMessage = 'AI chwilowo niedostępne: ' . $e->getMessage() . ' Spróbuj ponownie za chwilę.';
            $this->currentStep  = 2;
        } catch (\Throwable $e) {
            $this->errorMessage = 'Wystąpił nieoczekiwany błąd podczas generowania. Spróbuj ponownie.';
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

            // SAFETY: Force is_published = false regardless of AI output
            $draft['is_published'] = false;
            $draft['published_at'] = null;
            $draft['user_id']      = Auth::id();

            // Build final body: AI text + real animal photo blocks
            $draft['body'] = $this->injectAnimalPhotos($draft['body'] ?? '');

            $post = $postService->create([
                'user_id'          => $draft['user_id'],
                'title'            => $draft['title'],
                'slug'             => $draft['slug'],
                'excerpt'          => $draft['excerpt'],
                'body'             => $draft['body'],
                'meta_title'       => $draft['meta_title'] ?? null,
                'meta_description' => $draft['meta_description'] ?? null,
                'is_published'     => false,  // hardcoded — human must publish manually
                'published_at'     => null,
                'categories'       => [],
            ]);

            // Auto-generate decorative AI cover image via Imagen
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

    // ─── Render ─────────────────────────────────────────────────────

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.ai-blog-studio');
    }

    // ─── Private Helpers ────────────────────────────────────────────

    private function loadAnimals(AiBlogGeneratorService $blogService): void
    {
        $breedLabel = $this->breeds[$this->selectedBreed] ?? null;
        $grouped    = $blogService->getAvailableAnimals($breedLabel);

        // Convert to plain array for Livewire serialization
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

    /**
     * Inject real animal gallery photos into the article body.
     *
     * After the first closing </p> tag, insert a gallery block
     * with actual photos of selected cats. This replaces AI-generated
     * images in the article body with real cattery photos.
     */
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

        // Insert after the first closing </p>
        return preg_replace('/<\/p>/', '</p>' . $galleryHtml, $body, 1) ?? $body . $galleryHtml;
    }
}