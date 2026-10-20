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
    expect($post->sections)->toBeArray();
    expect($post->sections[0]['tip'])->toBe('Zawsze dodawaj taurynę.');
    expect($post->categories->pluck('slug')->all())->toContain('zywienie-holistyczne');
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

