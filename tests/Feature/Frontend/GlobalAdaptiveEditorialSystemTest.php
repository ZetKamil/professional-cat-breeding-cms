<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Support\ImageGeometry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalAdaptiveEditorialSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Http::preventStrayRequests();
    }

    /**
     * Test 1: Global Hero on "O Hodowli" (cattery) renders smooth bottom-fade transition
     * and non-colliding right-aligned image position.
     */
    public function test_cattery_page_renders_hero_with_bottom_fade_and_safe_position(): void
    {
        $response = $this->get(route('cattery'));

        $response->assertOk();
        $response->assertSee('hero__bottom-fade', false);
        $response->assertSee('right 75%', false);
    }

    /**
     * Test 2: Post heroLayout() automatically detects portrait vs landscape cover photos
     * for any existing or new post in the database.
     */
    public function test_post_hero_layout_automatically_adapts_to_cover_geometry(): void
    {
        $user = User::factory()->create();

        // 1. Post with portrait cover (e.g. 905x1207 like in post 18)
        $portraitPost = Post::create([
            'user_id' => $user->id,
            'title' => 'Kot Bengalski w Mieszkaniu Test',
            'slug' => 'kot-bengalski-w-mieszkaniu-test',
            'body' => 'Wprowadzenie...',
            'is_published' => true,
            'published_at' => now(),
            'sections' => [
                ['heading' => '', 'body' => 'Wprowadzenie...', 'image_url' => ''],
            ],
        ]);

        // Mock portrait media
        \App\Models\Media::create([
            'disk' => 'public',
            'directory' => 'blog/018-kot-bengalski-a-mieszkanie',
            'filename' => '02_hero.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 12345,
            'title' => 'Kot Bengalski Hero',
            'is_featured' => true,
            'mediable_type' => Post::class,
            'mediable_id' => $portraitPost->id,
        ]);

        $this->assertEquals('split', $portraitPost->heroLayout());
        $this->assertTrue($portraitPost->coverIsPortrait());

        // 2. Explicit hero_layout overrides auto
        $portraitPost->hero_layout = 'magazine';
        $this->assertEquals('magazine', $portraitPost->heroLayout());
    }

    /**
     * Test 3: Blog show renders Editorial Split Hero with untouched cat photo in gold frame,
     * no duplicate in section 0, and support for Duo and Portrait section blocks.
     */
    public function test_blog_show_renders_editorial_split_hero_and_adaptive_sections(): void
    {
        $user = User::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'title' => 'Kot bengalski a mieszkanie — czy bengal może żyć bez ogrodu?',
            'slug' => 'kot-bengalski-a-mieszkanie-editorial-test',
            'excerpt' => 'Dzielimy się 10-letnim doświadczeniem w hodowli kotów bengalskich...',
            'body' => 'Wprowadzenie do artykułu o mieszkaniu z bengalem.',
            'is_published' => true,
            'published_at' => now(),
            'hero_layout' => 'split',
            'sections' => [
                // Section 0 (Intro): has image_url identical to coverImageUrl() -> MUST NOT be duplicated!
                [
                    'heading' => '',
                    'body' => 'Wprowadzenie do artykułu o mieszkaniu z bengalem.',
                    'tip' => 'Koty bengalskie kochają wysokość.',
                    'image_url' => 'http://localhost/katten/storage/blog/018-kot-bengalski-a-mieszkanie/02_hero.jpg',
                ],
                // Section 1: Smartphone Duo (two vertical photos side-by-side)
                [
                    'heading' => 'Aktywność i zabawa w domu',
                    'body' => 'Dwa młode kociaki podczas codziennej zabawy.',
                    'tip' => 'Zapewnij solidny drapak sufitowy.',
                    'layout' => 'duo',
                    'image_url' => '/storage/test/kitten1.jpg',
                    'image_url_2' => '/storage/test/kitten2.jpg',
                ],
                // Section 2: Portrait Spotlight (single vertical photo)
                [
                    'heading' => 'Spokój i relaks po zabawie',
                    'body' => 'Gdy emocje opadną, bengal szuka bliskości człowieka.',
                    'tip' => 'Czyste legowisko w cichym miejscu.',
                    'layout' => 'portrait',
                    'image_url' => '/storage/test/portrait_cat.jpg',
                ],
            ],
        ]);

        // Attach cover media
        \App\Models\Media::create([
            'disk' => 'public',
            'directory' => 'blog/018-kot-bengalski-a-mieszkanie',
            'filename' => '02_hero.jpg',
            'mime_type' => 'image/jpeg',
            'size' => 12345,
            'title' => 'Hero Cover',
            'is_featured' => true,
            'mediable_type' => Post::class,
            'mediable_id' => $post->id,
        ]);

        $response = $this->get(route('frontend.blog.show', $post));

        $response->assertOk();

        // 1. Editorial Hero is rendered with split layout frame
        $response->assertSee('article-editorial-hero', false);
        $response->assertSee('article-hero-frame', false);
        $response->assertSee('article-editorial-hero__media', false);

        // 2. Section 0 does NOT duplicate the hero cover image
        $response->assertDontSee('<img src="http://localhost/katten/storage/blog/018-kot-bengalski-a-mieszkanie/02_hero.jpg" alt="Zdjęcie do artykułu"', false);

        // 3. Smartphone Duo block is rendered
        $response->assertSee('article-media-duo', false);
        $response->assertSee('/storage/test/kitten1.jpg', false);
        $response->assertSee('/storage/test/kitten2.jpg', false);

        // 4. Portrait Spotlight block is rendered
        $response->assertSee('article-media-portrait', false);
        $response->assertSee('/storage/test/portrait_cat.jpg', false);
    }

    /**
     * Test 4: Post request validation decodes Base64 cropped_image for cover
     * and Base64 images in sections (image_url, image_url_2).
     */
    public function test_post_requests_decode_base64_images_in_hero_and_sections(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // 1x1 transparent png in base64
        $base64Png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $payload = [
            'title' => 'Nowy artykuł z Base64 i Duetem',
            'slug' => 'nowy-artykul-z-base64',
            'is_published' => true,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'cropped_image' => $base64Png,
            'hero_layout' => 'split',
            'sections' => [
                [
                    'heading' => 'Sekcja z Duetem Base64',
                    'body' => 'Treść sekcji z dwoma zdjęciami z telefonu.',
                    'layout' => 'duo',
                    'image_url' => $base64Png,
                    'image_url_2' => $base64Png,
                ],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('backend.posts.store'), $payload);

        $response->assertRedirect(route('backend.posts.index'));

        $post = Post::where('slug', 'nowy-artykul-z-base64')->firstOrFail();

        // Cover media was created from Base64
        $this->assertNotNull($post->media);
        $this->assertTrue($post->media->is_featured);

        // Sections were decoded and replaced with public storage URLs
        $sections = $post->sections;
        $this->assertNotEmpty($sections);
        $this->assertStringNotContainsString('data:image', $sections[0]['image_url']);
        $this->assertStringNotContainsString('data:image', $sections[0]['image_url_2']);
        $this->assertStringContainsString('/storage/', $sections[0]['image_url']);
        $this->assertStringContainsString('/storage/', $sections[0]['image_url_2']);
    }
}
