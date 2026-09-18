<?php

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_successfully_with_luxury_editorial_sections(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Hodowla Kotów Rasowych z Rodowodem');
        $response->assertSee('SHiOZ ZOOLANDIA');
        $response->assertSee('Badania Genetyczne Rodziców');
        $response->assertSee('Nasze Specjalizacje Rasy');
        $response->assertSee('Koty Bengalskie');
        $response->assertSee('Koty Brytyjskie');
        $response->assertSee('Koty Syjamskie');
        $response->assertSee('Historie Opiekunów Naszych Kotów');
        $response->assertSee('Codzienność w hodowli');
        $response->assertSee('Nasza czytelnia — wkrótce nowe publikacje');
    }

    public function test_cattery_page_renders_successfully_with_philosophy_code_and_process_journey(): void
    {
        $response = $this->get(route('cattery'));

        $response->assertOk();
        $response->assertSee('Filozofia Naszej Hodowli');
        $response->assertSee('Kodeks Zaufania');
        $response->assertSee('Jak wygląda proces zakupu kocięcia?');
        $response->assertSee('Proces rozpoczyna się od kontaktu');
        $response->assertSee('Kontakt i Wybór Kocięcia');
        $response->assertSee('Wizyta w Hodowli');
        $response->assertSee('Umowa i Odbiór');
    }

    public function test_homepage_renders_featured_animals_when_available(): void
    {
        Animal::factory()->create([
            'name' => 'Luna Royal Bengal',
            'is_featured' => true,
            'is_published' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Luna Royal Bengal');
    }

    public function test_homepage_does_not_display_future_scheduled_posts(): void
    {
        $user = \App\Models\User::factory()->create();

        $futurePost = Post::create([
            'user_id' => $user->id,
            'title' => 'Niewidoczny Post z Przyszłości',
            'slug' => 'niewidoczny-post-z-przyszlosci',
            'excerpt' => 'Przyszłość.',
            'body' => 'Treść.',
            'is_published' => true,
            'published_at' => now()->addMonth(),
        ]);

        $pastPost = Post::create([
            'user_id' => $user->id,
            'title' => 'Widoczny Opublikowany Post',
            'slug' => 'widoczny-opublikowany-post',
            'excerpt' => 'Przeszłość.',
            'body' => 'Treść.',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Widoczny Opublikowany Post');
        $response->assertDontSee('Niewidoczny Post z Przyszłości');
    }
}
