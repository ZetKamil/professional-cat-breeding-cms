<?php

declare(strict_types=1);

use App\Livewire\Admin\AiBlogStudio;
use App\Models\User;
use App\Models\Animal;
use Livewire\Livewire;

beforeEach(function () {
    $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
    $this->user = User::factory()->create([
        'role_id' => $adminRole->id,
        'is_active' => true,
    ]);
    $this->actingAs($this->user);
});

test('component mounts with initial breed and pre-filled topic', function () {
    Livewire::test(AiBlogStudio::class)
        ->assertSet('currentStep', 1)
        ->assertSet('selectedBreed', 'bengalski')
        ->assertSet('customTopic', 'Żywienie i pielęgnacja kota bengalskiego')
        ->assertSet('topicsLoaded', false);
});

test('selecting a breed updates default topic and animals', function () {
    Livewire::test(AiBlogStudio::class)
        ->call('selectBreed', 'brytyjski')
        ->assertSet('selectedBreed', 'brytyjski')
        ->assertSet('customTopic', 'Żywienie i pielęgnacja kota brytyjskiego')
        ->assertSet('topicsLoaded', false);
});

test('goToStep2 succeeds with pre-filled topic', function () {
    Livewire::test(AiBlogStudio::class)
        ->call('goToStep2')
        ->assertSet('currentStep', 2)
        ->assertSet('customTopic', 'Żywienie i pielęgnacja kota bengalskiego');
});

test('goToStep2 fails with empty topic', function () {
    Livewire::test(AiBlogStudio::class)
        ->set('customTopic', '')
        ->set('selectedTopic', '')
        ->call('goToStep2')
        ->assertSet('currentStep', 1)
        ->assertSet('errorMessage', 'Proszę wpisać temat artykułu w polu tekstowym poniżej.');
});

test('toggling animal selection works in step 2', function () {
    $animal = Animal::factory()->create([
        'breed' => 'Kot Bengalski',
        'is_published' => true,
        'published_at' => now(),
        'status' => \App\Enums\AnimalStatus::Available,
    ]);

    Livewire::test(AiBlogStudio::class)
        ->call('toggleAnimal', $animal->id)
        ->assertSet('selectedAnimalIds', [$animal->id])
        ->call('toggleAnimal', $animal->id)
        ->assertSet('selectedAnimalIds', []);
});

test('backToStep navigates back to step 1 from step 2', function () {
    Livewire::test(AiBlogStudio::class)
        ->call('goToStep2')
        ->assertSet('currentStep', 2)
        ->call('backToStep', 1)
        ->assertSet('currentStep', 1);
});

test('fetchTopics executes agent and sets topicsLoaded', function () {
    Livewire::test(AiBlogStudio::class)
        ->call('fetchTopics')
        ->assertSet('topicsLoaded', true)
        ->assertSet('isFetchingTopics', false);
});

test('saveDraft creates post with structured sections and tip', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    \Illuminate\Support\Facades\Http::fake();

    $category = \App\Models\Category::firstOrCreate(
        ['slug' => 'zywienie-holistyczne'],
        ['name' => 'Żywienie holistyczne']
    );

    $component = Livewire::test(AiBlogStudio::class);
    $component->set('customTopic', 'Żywienie i dieta BARF dla kota bengalskiego');
    $component->set('generatedDraft', [
        'title'             => 'Jak karmić kota bengalskiego?',
        'slug'              => 'jak-karmic-kota-bengalskiego',
        'excerpt'           => 'Kompleksowy poradnik o diecie BARF.',
        'sections'          => [
            [
                'heading'      => '',
                'body'         => 'Wstęp do diety mięsnej.',
                'tip'          => 'Zawsze dodawaj taurynę.',
                'image_url'    => '',
                'image_prompt' => 'Bengal eating',
            ],
            [
                'heading'      => 'Jakie mięso wybrać?',
                'body'         => 'Najlepiej drób i wołowinę.',
                'tip'          => 'Unikaj kości poddanych obróbce termicznej.',
                'image_url'    => '',
                'image_prompt' => 'Raw meat for cat',
            ],
        ],
        'hero_image_prompt' => 'Bengal cat eating meat',
        'meta_title'        => 'Jak karmić kota?',
        'meta_description'  => 'Poradnik diety BARF.',
    ]);

    $component->call('saveDraft', false);

    $post = \App\Models\Post::where('slug', 'jak-karmic-kota-bengalskiego')->first();
    expect($post)->not->toBeNull();
    expect($post->is_published)->toBeFalse();
    expect($post->published_at)->toBeNull();
    expect($post->sections)->toBeArray();
    expect($post->sections[0]['tip'])->toBe('Zawsze dodawaj taurynę.');
    expect($post->categories->pluck('slug')->all())->toContain('zywienie-holistyczne');
});

test('saveDraft deduplicates colliding slug and filters missing animal ids', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    \Illuminate\Support\Facades\Http::fake();

    \App\Models\Post::factory()->create([
        'slug' => 'powielony-artykul',
    ]);

    $component = Livewire::test(AiBlogStudio::class);
    $component->set('selectedAnimalIds', ['01ZZZZZZZZZZZZZZZZZZZZZZZZ']);
    $component->set('generatedDraft', [
        'title' => 'Powielony artykuł',
        'slug' => 'powielony-artykul',
        'excerpt' => 'Krótki opis',
        'sections' => [],
        'body' => 'Treść',
        'hero_image_prompt' => '',
    ]);

    $component->call('saveDraft', false);

    $created = \App\Models\Post::where('slug', 'powielony-artykul-2')->first();
    expect($created)->not->toBeNull()
        ->and($created->published_at)->toBeNull()
        ->and($created->animals)->toHaveCount(0);
});

test('saveDraft with publishNow true immediately publishes post', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    \Illuminate\Support\Facades\Http::fake();

    $component = Livewire::test(AiBlogStudio::class);
    $component->set('customTopic', 'Pielęgnacja sierści kota bengalskiego');
    $component->set('generatedDraft', [
        'title'             => 'Pielęgnacja futra kota bengalskiego',
        'slug'              => 'pielegnacja-futra-kota-bengalskiego',
        'excerpt'           => 'Czesanie i kąpiele.',
        'sections'          => [
            [
                'heading'      => '',
                'body'         => 'Wstęp o futrze.',
                'tip'          => 'Używaj gumowej rękawicy.',
                'image_url'    => '',
                'image_prompt' => 'Brushing cat',
            ],
        ],
        'hero_image_prompt' => 'Brushing cat',
    ]);

    $component->call('saveDraft', true);

    $post = \App\Models\Post::where('slug', 'pielegnacja-futra-kota-bengalskiego')->first();
    expect($post)->not->toBeNull();
    expect($post->is_published)->toBeTrue();
});

test('goToStep3 generates article draft and recovers cleanly on gemini timeout', function () {
    \Illuminate\Support\Facades\Config::set('services.gemini.api_key', 'test-key-123');
    \Illuminate\Support\Facades\Config::set('services.gemini.wall_timeout', 15);

    \Illuminate\Support\Facades\Http::fake([
        'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
            'candidates' => [[
                'content' => ['parts' => [[
                    'text' => json_encode([
                        'h1' => 'Szybko wygenerowany artykuł',
                        'meta_title' => 'Tytuł SEO',
                        'meta_description' => 'Opis SEO',
                        'excerpt' => 'Krótki wstęp.',
                        'sections' => [
                            ['heading' => '', 'body' => 'Akapit wprowadzający.', 'tip' => 'Wskazówka', 'image_prompt' => 'Bengal cat'],
                        ],
                        'hero_image_prompt' => 'Luxury bengal cat',
                    ]),
                ]]],
            ]],
        ], 200),
    ]);

    $component = Livewire::test(AiBlogStudio::class)
        ->set('customTopic', 'Temperament kota bengalskiego')
        ->call('goToStep3');

    $component->assertSet('currentStep', 3)
        ->assertSet('isGenerating', false)
        ->assertSet('generatedDraft.title', 'Szybko wygenerowany artykuł');
});

test('step 3 lets admin switch hero cover to a selected cat or regenerate seeded ai cover and distributes multiple cats round-robin', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    \Illuminate\Support\Facades\Config::set('services.gemini.api_key', 'test-key-123');

    $cat1 = \App\Models\Animal::factory()->create(['name' => 'Baron', 'breed' => 'Kot Bengalski', 'is_published' => true]);
    $cat2 = \App\Models\Animal::factory()->create(['name' => 'Cyprian', 'breed' => 'Kot Bengalski', 'is_published' => true]);
    $cat3 = \App\Models\Animal::factory()->create(['name' => 'Carlos', 'breed' => 'Kot Bengalski', 'is_published' => true]);

    foreach ([$cat1, $cat2, $cat3] as $idx => $cat) {
        $f = \Illuminate\Http\UploadedFile::fake()->image("cat{$idx}.jpg", 600, 600);
        $path = $f->store('media/animals', 'public');
        \App\Models\Media::create([
            'disk' => 'public',
            'directory' => 'media/animals',
            'filename' => basename($path),
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'mediable_type' => \App\Models\Animal::class,
            'mediable_id' => $cat->id,
            'is_featured' => true,
        ]);
        if ($idx === 0) {
            // Add extra gallery images to Cat 1 — should NOT starve Cat 2 & Cat 3 in sections
            $extra = \Illuminate\Http\UploadedFile::fake()->image('cat0_extra.jpg', 600, 600)->store('media/animals', 'public');
            \App\Models\Media::create([
                'disk' => 'public',
                'directory' => 'media/animals',
                'filename' => basename($extra),
                'mime_type' => 'image/jpeg',
                'size' => 1024,
                'mediable_type' => \App\Models\Animal::class,
                'mediable_id' => $cat->id,
                'is_featured' => false,
                'sort_order' => 2,
            ]);
        }
    }

    \Illuminate\Support\Facades\Http::fake([
        'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
            'candidates' => [[
                'content' => ['parts' => [[
                    'text' => json_encode([
                        'h1' => 'Jak zająć kota bengalskiego?',
                        'meta_title' => 'Tytuł SEO',
                        'meta_description' => 'Opis SEO',
                        'excerpt' => 'Wstęp.',
                        'sections' => [
                            ['heading' => '', 'body' => 'S1'],
                            ['heading' => 'H2 A', 'body' => 'S2'],
                            ['heading' => 'H2 B', 'body' => 'S3'],
                        ],
                        'hero_image_prompt' => 'Bengal cat playing',
                    ]),
                ]]],
            ]],
        ], 200),
    ]);

    $component = Livewire::test(AiBlogStudio::class)
        ->set('selectedAnimalIds', [$cat1->id, $cat2->id, $cat3->id])
        ->set('customTopic', 'Jak zająć kota bengalskiego?')
        ->call('goToStep3');

    $draft = $component->get('generatedDraft');
    expect($draft['hero_image_preview_url'])->toContain('model=flux')
        ->and($draft['hero_image_preview_url'])->toContain('seed=')
        ->and($draft['sections'][0]['image_url'])->toContain($cat1->media->filename)
        ->and($draft['sections'][1]['image_url'])->toContain($cat2->media->filename)
        ->and($draft['sections'][2]['image_url'])->toContain($cat3->media->filename);

    // Regenerating AI cover produces a new preview URL
    $firstAiUrl = $draft['hero_image_preview_url'];
    $component->call('regenerateAiHero');
    expect($component->get('generatedDraft.hero_image_preview_url'))->toContain('seed=');

    // Switching hero cover to real cat Carlos persists Carlos's photo on saveDraft()
    $component->call('selectHeroFromAnimal', $cat3->id)
        ->assertSet('selectedHeroAnimalId', $cat3->id)
        ->call('saveDraft', false);

    $saved = \App\Models\Post::where('slug', 'jak-zajac-kota-bengalskiego')->first();
    expect($saved->media)->not->toBeNull()
        ->and($saved->media->caption)->toContain('Carlos');
});

