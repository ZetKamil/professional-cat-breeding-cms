<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\GeminiServiceUnavailableException;
use App\Models\Animal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Orchestrates AI-generated blog post drafts for the Katten CMS.
 *
 * Responsibilities:
 * - Aggregate animal context from the database
 * - Load Katten copywriting rules from COPYWRITING.md
 * - Build a structured system prompt (rules + context)
 * - Call GeminiService for text and image generation
 * - Return a PostService-compatible array (always as DRAFT)
 *
 * This class does NOT persist data. Persistence is handled
 * by PostService::create() after human review in the CMS.
 */
class AiBlogGeneratorService
{
    public function __construct(
        private readonly GeminiService $gemini
    ) {}

    /**
     * Return published animals grouped by breed for the Livewire selector UI.
     *
     * Each group is a Collection of animals with id, name, breed, status,
     * and primary media URL — everything the UI card needs.
     *
     * @return Collection<string, Collection<int, Animal>>  Keyed by breed string.
     */
    public function getAvailableAnimals(?string $breedFilter = null): Collection
    {
        $query = Animal::published()
            ->with(['media', 'gallery'])
            ->orderByStatus();

        if ($breedFilter) {
            $query->where('breed', 'like', "%{$breedFilter}%");
        }

        return $query->get()->groupBy('breed');
    }

    /**
     * Generate a decorative hero image for a post cover.
     * Delegates to GeminiService — exposed here so the Livewire component
     * only needs to depend on this one service.
     *
     * Returns base64 data URI or empty string on failure.
     *
     * @throws GeminiServiceUnavailableException
     */
    public function generateHeroImage(string $prompt): string
    {
        return $this->gemini->generateImage($prompt);
    }


    /**
     * Generate a blog post draft based on selected parameters.
     *
     * @param  string    $breed       E.g. "Kot Bengalski", "Kot Brytyjski"
     * @param  string    $topic       E.g. "Zywienie kociat bengalskich"
     * @param  array     $animalIds   ULIDs of animals to feature in the article
     * @return array     Draft data ready for PostService::create() — always is_published=false
     *
     * @throws GeminiServiceUnavailableException
     */
    public function generateDraft(string $breed, string $topic, array $animalIds = []): array
    {
        $animals = $this->loadAnimals($animalIds);

        $systemPrompt = $this->buildSystemPrompt($breed, $animals);
        $userPrompt   = $this->buildUserPrompt($breed, $topic, $animals);

        Log::info('AiBlogGeneratorService: generating draft', [
            'breed'      => $breed,
            'topic'      => $topic,
            'animalIds'  => $animalIds,
        ]);

        $rawResponse = $this->gemini->generateText($systemPrompt, $userPrompt);
        $parsed      = $this->parseGeminiResponse($rawResponse);

        // Safety net: generate hero image prompt even if Gemini did not return one
        $heroImagePrompt = $parsed['hero_image_prompt']
            ?? $this->fallbackHeroPrompt($breed);

        return [
            // Post fields — passed directly to PostService::create()
            'title'       => $parsed['h1'] ?? $topic,
            'slug'        => Str::slug($parsed['h1'] ?? $topic),
            'excerpt'     => $parsed['excerpt'] ?? '',
            'body'        => $parsed['content_html'] ?? '',
            'is_published' => false,   // ALWAYS false — human must publish manually
            'published_at' => null,

            // SEO metadata (stored separately from post body)
            'meta_title'       => $parsed['meta_title'] ?? null,
            'meta_description' => $parsed['meta_description'] ?? null,

            // Passed back to Livewire to offer image generation
            'hero_image_prompt' => $heroImagePrompt,

            // Animal ULIDs so the Livewire component can inject real gallery images
            'featured_animal_ids' => $animalIds,
        ];
    }

    // ─── Context Loaders ────────────────────────────────────────────

    /**
     * Load Animal models with gallery for context injection.
     */
    private function loadAnimals(array $animalIds): Collection
    {
        if (empty($animalIds)) {
            return collect();
        }

        return Animal::published()
            ->with(['gallery', 'mother', 'father'])
            ->whereIn('id', $animalIds)
            ->get();
    }

    /**
     * Build the system prompt — rules + cattery knowledge.
     *
     * The system prompt is the permanent context that shapes every
     * response. It loads COPYWRITING.md so Gemini always writes
     * in the Katten tone of voice without us repeating rules each time.
     */
    private function buildSystemPrompt(string $breed, Collection $animals): string
    {
        $copywritingRules = $this->loadCopywritingRules();
        $animalContext    = $this->buildAnimalContext($animals);

        return <<<PROMPT
        You are a senior copywriter working exclusively for "Hodowla Kotów z Mazowieckiej Szwajcarii" — 
        a premium, responsible cat breeding cattery in Poland.
        
        Your ONLY goal is to write blog content in Polish that educates potential cat owners and 
        builds trust in the cattery. You NEVER write generic content.
        
        ## MANDATORY TONE OF VOICE AND WRITING RULES
        {$copywritingRules}
        
        ## CATTERY KNOWLEDGE — THE ANIMALS WE ARE WRITING ABOUT
        {$animalContext}
        
        ## BREED CONTEXT
        You are writing about: {$breed}
        
        ## OUTPUT FORMAT
        You MUST respond with a single valid JSON object. No markdown, no explanation, ONLY JSON.
        Required fields:
        {
          "h1": "Main article title (H1)",
          "meta_title": "SEO meta title (max 60 chars)",
          "meta_description": "SEO meta description (max 155 chars)",
          "excerpt": "Short teaser paragraph (2-3 sentences)",
          "content_html": "Full article body as valid HTML with H2, H3, P, UL tags",
          "hero_image_prompt": "English prompt for generating a decorative cover image (NOT a photo of a real cat)"
        }
        PROMPT;
    }

    /**
     * Build the specific content request from the user.
     */
    private function buildUserPrompt(string $breed, string $topic, Collection $animals): string
    {
        $animalNames = $animals->pluck('name')->implode(', ');

        $prompt = "Napisz artykuł blogowy na temat: \"{$topic}\" dotyczący rasy: {$breed}.";

        if ($animalNames) {
            $prompt .= " W artykule nawiąż do konkretnych kociąt z naszej hodowli: {$animalNames}.";
            $prompt .= " Opisz je ciepło i autentycznie na podstawie dostarczonych danych.";
        }

        $prompt .= " Artykuł ma być edukacyjny, budować zaufanie, pomagać w decyzji zakupu.";
        $prompt .= " Długość: 800-1200 słów. Styl: luksusowy, spokojny, ciepły.";

        return $prompt;
    }

    /**
     * Format animal data from DB into readable context for the AI.
     * This prevents hallucination — the AI writes about REAL cats.
     */
    private function buildAnimalContext(Collection $animals): string
    {
        if ($animals->isEmpty()) {
            return "No specific animals selected for this article.";
        }

        return $animals->map(function (Animal $animal) {
            $age    = $animal->age() ?? 'nieznany wiek';
            $status = $animal->statusLabel();
            $mother = $animal->mother?->name ?? 'nieznana';
            $father = $animal->father?->name ?? 'nieznany';
            $photos = $animal->gallery->count();

            return "- Imię: {$animal->name} | Rasa: {$animal->breed} | Kolor: {$animal->color} "
                . "| Wiek: {$age} | Status: {$status} | Matka: {$mother} | Ojciec: {$father} "
                . "| Dostępnych zdjęć w galerii: {$photos}";
        })->implode(PHP_EOL);
    }

    // ─── Response Parsing ───────────────────────────────────────────

    /**
     * Parse and validate the JSON response from Gemini.
     *
     * If Gemini wraps JSON in a markdown code block (```json ... ```),
     * we strip that first. Returns empty array on parse failure.
     */
    private function parseGeminiResponse(string $raw): array
    {
        // Strip markdown code fences if Gemini ignores the JSON-only instruction
        $cleaned = preg_replace('/```(?:json)?\s*/i', '', $raw);
        $cleaned = str_replace('```', '', (string) $cleaned);
        $cleaned = trim($cleaned);

        $decoded = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('AiBlogGeneratorService: failed to parse Gemini JSON response', [
                'raw'   => substr($raw, 0, 500),
                'error' => json_last_error_msg(),
            ]);

            return [];
        }

        return $decoded;
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    /**
     * Load Katten copywriting rules from the project COPYWRITING.md file.
     *
     * Falls back to a hardcoded summary so the service never crashes
     * if the file is missing during deployment.
     */
    private function loadCopywritingRules(): string
    {
        $path = base_path('AI/COPYWRITING.md');

        if (file_exists($path)) {
            return file_get_contents($path) ?: $this->hardcodedRules();
        }

        Log::warning('AiBlogGeneratorService: COPYWRITING.md not found, using fallback rules');

        return $this->hardcodedRules();
    }

    private function hardcodedRules(): string
    {
        return <<<RULES
        Tone: Luxury, Calm, Warm, Professional, Honest, Educational.
        NEVER: Salesy, clickbait, fake urgency, excessive exclamation marks.
        Style: Short sentences. Simple language. Confident. Transparent.
        Approach: Educate first. Sell second.
        CTA examples: "Zapytaj o dostępność" (NOT "Buy now"), "Poznaj nasze kocięta" (NOT "Best kittens").
        RULES;
    }

    private function fallbackHeroPrompt(string $breed): string
    {
        return "Elegant minimalist illustration of a {$breed} cat, warm natural lighting, "
            . "luxury interior background, soft bokeh, professional photography style, "
            . "warm amber tones, premium lifestyle aesthetic";
    }
}
