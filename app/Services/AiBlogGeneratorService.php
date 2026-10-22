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
            ->orderBy('status')
            ->orderBy('name');

        if ($breedFilter) {
            $query->where('breed', 'like', "%{$breedFilter}%");
        }

        return $query->get()->groupBy('breed');
    }

    /**
     * Generate a decorative hero image for a post cover and attach it via MediaService.
     * Tries Gemini Imagen first, then falls back to Pollinations AI photo generator.
     *
     * @param  string            $prompt Image prompt for Imagen / AI generator
     * @param  \App\Models\Post  $post   Created Post model
     * @return \App\Models\Media|null
     */
    public function generateHeroImage(string $prompt, \App\Models\Post $post): ?\App\Models\Media
    {
        $imageData = null;
        $mime = 'image/png';
        $ext = 'png';

        // 1. Try Gemini Imagen
        try {
            $base64 = $this->gemini->generateImage($prompt);
            if (filled($base64)) {
                $imageData = base64_decode($base64);
            }
        } catch (\Throwable $e) {
            Log::info('AiBlogGeneratorService: Gemini Imagen unavailable, using AI photo engine fallback', [
                'error' => $e->getMessage(),
            ]);
        }

        // 2. Reliable AI fallback engine (Pollinations AI)
        if (empty($imageData)) {
            $imageData = $this->fetchAiGeneratedImage($prompt);
            $mime = 'image/jpeg';
            $ext = 'jpg';
        }

        if (empty($imageData)) {
            return null;
        }

        $tmpPath = sys_get_temp_dir() . '/ai_hero_' . uniqid() . '.' . $ext;
        file_put_contents($tmpPath, $imageData);

        try {
            $uploadedFile = new \Illuminate\Http\UploadedFile($tmpPath, 'hero.' . $ext, $mime, null, true);
            $media = app(MediaService::class)->upload($post, $uploadedFile, 'posts', isFeatured: true);

            // Update title and alt attributes
            $media->update([
                'title'       => $post->title,
                'alt_text'    => $post->title . ' — Hodowla Kotów z Mazowieckiej Szwajcarii',
                'is_featured' => true,
            ]);

            return $media;
        } finally {
            if (file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        }
    }

    /**
     * Download an AI-generated image via public AI image model.
     */
    public function fetchAiGeneratedImage(string $prompt, int $width = 1200, int $height = 675): ?string
    {
        $cleanPrompt = trim($prompt);
        if (blank($cleanPrompt)) {
            $cleanPrompt = 'Magnificent pedigreed cat in a warm luxury interior, natural sunlight, cinematic 8k';
        }

        $encoded = urlencode($cleanPrompt);
        $url = "https://image.pollinations.ai/prompt/{$encoded}?width={$width}&height={$height}&nologo=true&enhance=true";

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(25)
                ->withoutVerifying()
                ->get($url);

            if ($response->successful() && strlen($response->body()) > 5000) {
                return $response->body();
            }
        } catch (\Throwable $e) {
            Log::warning('AiBlogGeneratorService: failed to fetch AI image from Pollinations', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
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

        $sections = $this->normalizeSections($parsed, $topic);

        // Safety net: generate hero image prompt even if Gemini did not return one
        $heroImagePrompt = $parsed['hero_image_prompt']
            ?? $this->fallbackHeroPrompt($breed);

        return [
            // Post fields — passed directly to PostService::create()
            'title'       => $parsed['h1'] ?? $topic,
            'slug'        => Str::slug($parsed['h1'] ?? $topic),
            'excerpt'     => $parsed['excerpt'] ?? ($sections[0]['body'] ?? ''),
            'sections'    => $sections,
            // body is auto-generated from sections in PostRequest, but set here for fallback
            'body'        => $this->sectionsToBody($sections),
            'is_published' => false,   // ALWAYS false — human must publish manually
            'published_at' => null,

            // SEO metadata (stored separately from post body)
            'meta_title'       => $parsed['meta_title'] ?? Str::limit($parsed['h1'] ?? $topic, 60),
            'meta_description' => $parsed['meta_description'] ?? Str::limit($parsed['excerpt'] ?? '', 155),

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

        ## CATTERY CONTACT & LOCATION (MANDATORY)
        - Cattery name: Hodowla Kotów z Mazowieckiej Szwajcarii
        - Location: Sikórz k. Płocka (woj. mazowieckie)
        - Official Phone / WhatsApp: +48 514 153 204 (If you mention contact, phone or WhatsApp, ONLY use this number: +48 514 153 204)
        - Contact page: /kontakt

        ## BREED CONTEXT
        You are writing about: {$breed}

        ## OUTPUT FORMAT
        You MUST respond with a single valid JSON object. No markdown, no explanation, ONLY JSON.
        Required fields:
        {
          "h1": "Main article title (H1)",
          "meta_title": "SEO meta title (max 60 chars)",
          "meta_description": "SEO meta description (max 155 chars)",
          "excerpt": "Short teaser paragraph (2-3 sentences, shown under the title and in article listings)",
          "sections": [
            {
              "heading": "",
              "body": "Introductory lead paragraph text (no heading for the first section)",
              "tip": "Breeder practical pro-tip (1-2 sentences) or empty string",
              "image_prompt": "English prompt for photo of cat or theme illustrating this section"
            },
            {
              "heading": "First H2 subtitle",
              "body": "Body paragraphs for this section. Use \\n between paragraphs.",
              "tip": "Actionable breeder tip from Hodowla z Mazowieckiej Szwajcarii (1-2 sentences)",
              "image_prompt": "English prompt for photo illustrating this H2 topic"
            }
          ],
          "hero_image_prompt": "English prompt for generating a decorative high-resolution cover photo"
        }
        RULES FOR sections:
        - Generate 4-6 sections total.
        - The FIRST section MUST have an empty heading ("") — it is the intro paragraph.
        - All other sections MUST have a non-empty heading (H2 level).
        - tip: A concise, highly professional practical advice from the breeder (1-2 sentences) highlighting responsible care, health, or socialization.
        - image_prompt: A vivid English prompt describing a photo that fits this specific section (e.g. "British Shorthair kitten playing on a carpet, warm sunlight, photorealistic 8k").
        - body is plain text only. No HTML tags.
        - DO NOT generate a contact/closing cattery offer section (e.g. "Dostępne kocięta", "Kontakt z hodowlą"). The website dynamically appends the live cattery offer and contact card at the bottom of every article. Focus 100% on high-quality educational knowledge and advice for the reader.
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

    /**
     * Convert sections array to a plain-text body string for backward compatibility
     * (full-text search index, RSS feeds, legacy posts without sections).
     *
     * @param  array<int, array{heading: string, body: string, image_url: string}>  $sections
     */
    public function sectionsToBody(array $sections): string
    {
        return collect($sections)->map(function (array $s) {
            $parts = [];
            if (!empty($s['heading'])) {
                $parts[] = '## ' . $s['heading'];
            }
            if (!empty($s['body'])) {
                $parts[] = $s['body'];
            }
            if (!empty($s['tip'])) {
                $parts[] = '> 💡 **Wskazówka hodowcy:** ' . $s['tip'];
            }
            if (!empty($s['image_url'])) {
                $alt = !empty($s['heading']) ? $s['heading'] : 'Zdjęcie sekcji';
                $parts[] = '![' . $alt . '](' . $s['image_url'] . ')';
            }
            return implode("\n\n", $parts);
        })->implode("\n\n");
    }

    /**
     * Normalize the parsed Gemini response into a standard sections array:
     * [
     *   ['heading' => '', 'body' => '...', 'tip' => '...', 'image_url' => '', 'image_prompt' => '...'],
     *   ['heading' => 'H2...', 'body' => '...', 'tip' => '...', 'image_url' => '', 'image_prompt' => '...'],
     * ]
     *
     * Handles:
     * - Key variations ('content', 'text', 'paragraph' -> 'body')
     * - Key variations ('title', 'subtitle' -> 'heading')
     * - Ensures Section 0 is always the intro (heading = '')
     * - Strips redundant trailing CTA / contact sections (since Global CTA handles this dynamically)
     * - Fallback: parses raw markdown or HTML body if 'sections' was missing
     */
    public function normalizeSections(array $parsed, string $fallbackTopic = ''): array
    {
        $rawSections = $parsed['sections'] ?? null;

        // Fallback: If sections is missing, parse raw body / content_html / excerpt
        if (!is_array($rawSections) || empty($rawSections)) {
            $rawContent = $parsed['body'] ?? $parsed['content_html'] ?? $parsed['content'] ?? '';
            $rawSections = $this->parseContentIntoSections($rawContent, $parsed['excerpt'] ?? '');
        }

        $normalized = [];

        foreach ($rawSections as $item) {
            if (!is_array($item)) {
                continue;
            }

            // Extract heading with fallbacks
            $heading = trim((string) (
                $item['heading'] ?? $item['title'] ?? $item['subtitle'] ?? ''
            ));

            // Extract body with fallbacks
            $body = trim((string) (
                $item['body'] ?? $item['content'] ?? $item['text'] ?? $item['paragraph'] ?? ''
            ));

            // Strip HTML tags if Gemini returned raw tags
            $body = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], ["\n", "\n", "\n", "\n\n"], $body));
            $body = trim(preg_replace("/\n{3,}/", "\n\n", $body));

            $tip = trim((string) (
                $item['tip'] ?? $item['breeder_tip'] ?? $item['advice'] ?? ''
            ));

            $imagePrompt = trim((string) (
                $item['image_prompt'] ?? $item['prompt'] ?? ''
            ));

            $imageUrl = trim((string) ($item['image_url'] ?? $item['image_path'] ?? ''));

            if ($body === '' && $heading === '') {
                continue;
            }

            // Omit legacy trailing CTA / contact section if Gemini generated one
            // because our CMS dynamically displays the live Global CTA at the bottom
            $isClosingCta = str_starts_with($heading, '🐾')
                || str_contains(mb_strtolower($heading), 'dostępne kocięta')
                || str_contains(mb_strtolower($heading), 'rezerwacja')
                || str_contains(mb_strtolower($heading), 'kontakt z hodowlą')
                || str_contains(mb_strtolower($heading), 'zapraszamy do kontaktu');

            if ($isClosingCta && count($normalized) >= 3) {
                continue;
            }

            $normalized[] = [
                'heading'      => $heading,
                'body'         => $body,
                'tip'          => $tip,
                'image_url'    => $imageUrl,
                'image_prompt' => $imagePrompt,
            ];
        }

        // Guarantee at least 1 section exists
        if (empty($normalized)) {
            $introText = !empty($parsed['excerpt']) ? $parsed['excerpt'] : "Wprowadzenie do artykułu na temat: {$fallbackTopic}.";
            $normalized[] = [
                'heading'      => '',
                'body'         => $introText,
                'tip'          => '',
                'image_url'    => '',
                'image_prompt' => '',
            ];
        }

        // Section 0 MUST have empty heading (it's the intro lead section in our editorial template)
        $normalized[0]['heading'] = '';

        return $normalized;
    }

    /**
     * Fallback parser to break markdown or plain text into sections by H2 (##) or <h2> tags.
     */
    private function parseContentIntoSections(string $content, string $excerpt = ''): array
    {
        $content = trim($content);
        if ($content === '') {
            return !empty($excerpt)
                ? [['heading' => '', 'body' => $excerpt, 'image_url' => '']]
                : [];
        }

        // Convert HTML <h2> to markdown ##
        $content = preg_replace('/<h2[^>]*>(.*?)<\/h2>/i', "\n## $1\n", $content);

        // Split by markdown ##
        $parts = preg_split('/(?=^##\s+)/m', $content);

        $sections = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') continue;

            if (preg_match('/^##\s+(.+)$/m', $part, $matches)) {
                $heading = trim($matches[1]);
                $body = trim(preg_replace('/^##\s+.+$/m', '', $part));
                $sections[] = [
                    'heading'   => $heading,
                    'body'      => $body,
                    'image_url' => '',
                ];
            } else {
                // Intro text before any H2
                $sections[] = [
                    'heading'   => '',
                    'body'      => $part,
                    'image_url' => '',
                ];
            }
        }

        return $sections;
    }
}
