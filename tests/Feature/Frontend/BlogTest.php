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

    public function test_future_scheduled_post_is_hidden_from_catalog_and_returns_404_for_guest(): void
    {
        $user = User::factory()->create();
        $futurePost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Artykuł Zaplanowany Na Za Tydzień',
            'is_published' => true,
            'published_at' => now()->addDays(7),
        ]);

        // Catalog test
        $catalogResponse = $this->get(route('frontend.blog.index'));
        $catalogResponse->assertOk();
        $catalogResponse->assertDontSee('Artykuł Zaplanowany Na Za Tydzień');

        // Show test for guest
        $showResponse = $this->get(route('frontend.blog.show', $futurePost));
        $showResponse->assertNotFound();
    }

    public function test_blog_posts_are_sorted_strictly_reverse_chronological_by_published_at(): void
    {
        $user = User::factory()->create();

        $olderPost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Starszy Artykuł Lipiec',
            'is_published' => true,
            'published_at' => now()->subWeeks(4),
        ]);

        $newerPost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Nowszy Artykuł Wrzesień',
            'is_published' => true,
            'published_at' => now()->subWeeks(1),
        ]);

        $response = $this->get(route('frontend.blog.index'));
        $response->assertOk();

        // Newer post should appear before older post in the HTML content
        $content = $response->getContent();
        $posNewer = strpos($content, 'Nowszy Artykuł Wrzesień');
        $posOlder = strpos($content, 'Starszy Artykuł Lipiec');

        $this->assertNotFalse($posNewer);
        $this->assertNotFalse($posOlder);
        $this->assertTrue($posNewer < $posOlder, 'Newer post must be rendered before older post.');
    }
}

