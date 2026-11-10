<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_post_and_replace_image(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Original Post Title',
            'slug' => 'original-post-title',
            'body' => 'This is the original body content of the blog post.',
            'is_published' => true,
        ]);

        $firstImage = UploadedFile::fake()->image('first_image.jpg', 600, 400);

        // Update post with the first image
        $response = $this->actingAs($admin)
            ->patch(route('backend.posts.update', $post), [
                'title' => 'Updated Post Title',
                'slug' => 'updated-post-title',
                'body' => 'This is the updated body content with sufficient characters.',
                'is_published' => 1,
                'image' => $firstImage,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.posts.edit', 'updated-post-title'));

        $post->refresh();
        $post->load('media');

        expect($post->title)->toBe('Updated Post Title')
            ->and($post->media)->not->toBeNull();

        $originalMediaFilename = $post->media->filename;

        // Now replace with a second image
        $secondImage = UploadedFile::fake()->image('second_image.png', 800, 600);

        $response2 = $this->actingAs($admin)
            ->patch(route('backend.posts.update', $post), [
                'title' => 'Updated Post Title Again',
                'slug' => 'updated-post-title-again',
                'body' => 'This is the updated body content with sufficient characters.',
                'is_published' => 1,
                'image' => $secondImage,
            ]);

        $response2->assertSessionHasNoErrors();
        $post->refresh();
        $post->load('media');

        expect($post->media)->not->toBeNull()
            ->and($post->media->filename)->not->toBe($originalMediaFilename);
    }

    public function test_post_edit_preserves_section_tips_and_allows_detaching_cover_and_un_nested_import_form(): void
    {
        Storage::fake('public');

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $cover = UploadedFile::fake()->image('cover.jpg', 600, 400);
        $storedPath = $cover->store('media/posts', 'public');

        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Artykuł ze wskazówką',
            'slug' => 'artykul-ze-wskazowka',
            'is_published' => true,
        ]);

        $post->media()->create([
            'disk' => 'public',
            'directory' => 'media/posts',
            'filename' => basename($storedPath),
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'is_featured' => true,
        ]);

        $sections = [
            [
                'heading' => 'Sekcja z poradą',
                'body' => 'Treść akapitu.',
                'tip' => 'Pamiętaj o regularnych szczepieniach!',
                'image_url' => '',
                'image_prompt' => 'Warm luxury studio photo',
            ],
        ];

        $response = $this->actingAs($admin)
            ->patch(route('backend.posts.update', $post), [
                'title' => 'Artykuł ze wskazówką',
                'slug' => 'artykul-ze-wskazowka',
                'sections_json' => json_encode($sections),
                'is_published' => 1,
                'remove_featured_media' => 1,
            ]);

        $response->assertSessionHasNoErrors();

        $post->refresh();
        $post->load('media');

        expect($post->sections[0]['tip'] ?? null)->toBe('Pamiętaj o regularnych szczepieniach!')
            ->and($post->sections[0]['image_prompt'] ?? null)->toBe('Warm luxury studio photo')
            ->and($post->body)->toContain('Pamiętaj o regularnych szczepieniach!')
            ->and($post->media)->toBeNull();

        // Confirm importSeo form is outside the GET filter form on posts index
        $indexHtml = $this->actingAs($admin)->get(route('backend.posts.index'))->getContent();
        expect(preg_match('/<form[^>]+method="GET"[^>]*>(.*?)<\/form>/is', $indexHtml, $filterFormMatch))->toBe(1);
        expect($filterFormMatch[1])->not->toContain('import-seo')
            ->and($indexHtml)->toContain('id="importSeoPostsForm"')
            ->and($indexHtml)->toContain('form="importSeoPostsForm"');
    }
}
