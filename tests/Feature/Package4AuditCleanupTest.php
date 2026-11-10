<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\MediaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Package4AuditCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_frontend_blog_post_renders_custom_global_cta_and_respects_is_enabled_toggle(): void
    {
        $post = Post::factory()->create([
            'slug' => 'ogolny-poradnik-pielegnacji',
            'title' => 'Ogólny poradnik pielęgnacji kota',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        Setting::set('blog_closing_cta', [
            'is_enabled' => true,
            'badge' => 'Jesienne Mioty 2026',
            'heading' => 'Sprawdź aktualne kocięta w naszej hodowli',
            'body' => "Zapraszamy na **wideo-rozmowę**!\n\n<script>alert('xss')</script>",
            'image_url' => '/images/cta-banner.jpg',
            'button_text' => 'Zobacz Katalog',
            'button_url' => '/koty?breed=brytyjskie',
            'facebook_info' => 'Bieżące relacje na Facebooku',
            'facebook_text' => 'Odwiedź Profil FB',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61580668026948',
        ]);

        $response = $this->get(route('frontend.blog.show', $post));
        $response->assertOk()
            ->assertSee('Jesienne Mioty 2026')
            ->assertSee('Sprawdź aktualne kocięta w naszej hodowli')
            ->assertSee('<strong>wideo-rozmowę</strong>', false)
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('/images/cta-banner.jpg')
            ->assertSee('Zobacz Katalog')
            ->assertSee('Odwiedź Profil FB');

        Setting::set('blog_closing_cta', [
            'is_enabled' => false,
            'heading' => 'Ukryty nagłówek CTA',
        ]);

        $disabledResponse = $this->get(route('frontend.blog.show', $post));
        $disabledResponse->assertOk()
            ->assertDontSee('article-luxury-cta-card')
            ->assertDontSee('Ukryty nagłówek CTA');
    }

    public function test_cms_restore_and_force_delete_propagate_403_and_404_instead_of_redirecting(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $admin = User::factory()->create(['role_id' => $adminRole->id, 'is_active' => true]);
        $regularUser = User::factory()->create(['role_id' => $userRole->id, 'is_active' => true]);

        $deletedPost = Post::factory()->create(['user_id' => $admin->id]);
        $deletedPost->delete();

        // Missing record returns 404 (not swallowed into 302 redirect)
        $this->actingAs($admin)->patch(route('backend.posts.restore', 999999))->assertNotFound();
        $this->actingAs($admin)->delete(route('backend.posts.forceDelete', 999999))->assertNotFound();
        $this->actingAs($admin)->patch(route('backend.users.restore', 999999))->assertNotFound();

        // Unauthorized user receives 403 Forbidden (not swallowed into 302 redirect)
        $this->actingAs($regularUser)->patch(route('backend.posts.restore', $deletedPost->id))->assertForbidden();
        $this->actingAs($regularUser)->delete(route('backend.posts.forceDelete', $deletedPost->id))->assertForbidden();
    }

    public function test_admin_maintenance_get_routes_render_csrf_confirmation_form_without_executing(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'is_active' => true]);

        foreach (['/deploy-now', '/deploy-sync', '/fix-storage', '/sync-blog-articles'] as $route) {
            $resp = $this->actingAs($admin)->get($route);
            $resp->assertOk();
            $resp->assertSee("method='POST'", false);
            $resp->assertSee("name='_token'", false);
        }
    }

    public function test_media_service_upload_respects_custom_directory(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('custom.jpg', 300, 300);

        $media = app(MediaService::class)->upload(null, $file, 'custom/gallery');

        $this->assertSame('media/custom/gallery', $media->directory);
        Storage::disk('public')->assertExists($media->path());
    }
}
