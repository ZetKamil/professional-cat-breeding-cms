<?php

declare(strict_types=1);

use App\Livewire\Admin\AiBlogStudio;
use App\Models\User;
use App\Models\Animal;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
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

