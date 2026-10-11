<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_the_blog_catalog_page_with_published_posts(): void
    {
        $user = User::factory()->create();
        $publishedPost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Zdrowie Kota Bengalskiego',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        $unpublishedPost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ukryty Szkic',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('frontend.blog.index'));

        $response->assertOk();
        $response->assertViewIs('frontend.blog.index');
        $response->assertSee('Zdrowie Kota Bengalskiego');
        $response->assertDontSee('Ukryty Szkic');
    }

    public function test_filters_blog_posts_by_category_slug(): void
    {
        $user = User::factory()->create();
        $catHealth = Category::factory()->create(['name' => 'Zdrowie', 'slug' => 'zdrowie']);
        $catFood = Category::factory()->create(['name' => 'Żywienie', 'slug' => 'zywienie']);

        $postHealth = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Badania HCM w naszej hodowli',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        $postHealth->categories()->attach($catHealth);

        $postFood = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Dieta BARF poradnik',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        $postFood->categories()->attach($catFood);

        $response = $this->get(route('frontend.blog.index', ['category' => 'zdrowie']));

        $response->assertOk();
        $response->assertSee('Badania HCM w naszej hodowli');
        $response->assertDontSee('Dieta BARF poradnik');
    }

    public function test_searches_blog_posts_by_query(): void
    {
        $user = User::factory()->create();
        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Certyfikat weterynaryjny PKD',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Inny temat zabawy',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.blog.index', ['q' => 'certyfikat']));

        $response->assertOk();
        $response->assertSee('Certyfikat weterynaryjny PKD');
        $response->assertDontSee('Inny temat zabawy');
    }

    public function test_displays_the_blog_show_page_for_published_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Wyprawka dla Kociaka',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();
        $response->assertViewIs('frontend.blog.show');
        $response->assertSee('Wyprawka dla Kociaka');
    }

    public function test_returns_404_for_unpublished_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Szkic nieopublikowany',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertNotFound();
    }
}
