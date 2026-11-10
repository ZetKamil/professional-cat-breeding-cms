<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_responses_contain_owasp_security_headers(): void
    {
        $endpoints = [
            '/',
            '/koty',
            '/blog',
            '/kontakt',
            '/o-hodowli',
            '/o-nas',
            '/polityka-prywatnosci',
            '/regulamin',
        ];

        foreach ($endpoints as $url) {
            $response = $this->get($url);
            $response->assertOk();

            $response->assertHeader('X-Content-Type-Options', 'nosniff');
            $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
            $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
            $response->assertHeader('X-XSS-Protection', '0');
            $response->assertHeader('Content-Security-Policy');
        }
    }

    public function test_backend_and_auth_routes_contain_security_headers(): void
    {
        $response = $this->get('/login');
        $response->assertOk();

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader('Content-Security-Policy');
    }

    public function test_guest_is_blocked_from_all_backend_routes(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/check-media',
            '/fix-storage',
            '/deploy-now',
            '/deploy-sync',
            '/sync-blog-articles',
            '/backend/users',
            '/backend/roles',
            '/backend/categories',
            '/backend/posts',
            '/backend/animals',
            '/backend/media',
            '/backend/media-api',
            '/settings/profile',
            '/settings/password',
            '/settings/appearance',
            '/settings/two-factor',
        ];

        foreach ($protectedRoutes as $url) {
            $response = $this->get($url);
            // Must redirect to login or deny access
            $response->assertRedirect('/login');
        }
    }

    public function test_non_admin_user_cannot_execute_admin_maintenance_routes(): void
    {
        $user = User::factory()->create(); // standard user without admin role

        $this->actingAs($user)->get('/fix-storage')->assertForbidden();
        $this->actingAs($user)->get('/check-media')->assertForbidden();
        $this->actingAs($user)->get('/check-mail')->assertForbidden();
        $this->actingAs($user)->get('/deploy-now')->assertForbidden();
        $this->actingAs($user)->get('/deploy-sync')->assertForbidden();
        $this->actingAs($user)->get('/sync-blog-articles')->assertForbidden();
    }

    public function test_media_streaming_rejects_directory_traversal_and_disallowed_extensions(): void
    {
        // Disallowed extensions must return 404 or 403 (blocked)
        $responseEnv = $this->get('/storage/media/production.env');
        $this->assertTrue(in_array($responseEnv->status(), [403, 404], true));

        $responsePhp = $this->get('/storage/media/shell.php');
        $this->assertTrue(in_array($responsePhp->status(), [403, 404], true));

        $responseSh = $this->get('/storage/media/script.sh');
        $this->assertTrue(in_array($responseSh->status(), [403, 404], true));

        $responseSql = $this->get('/storage/media/dump.sql');
        $this->assertTrue(in_array($responseSql->status(), [403, 404], true));

        $responseJson = $this->get('/storage/media/composer.json');
        $this->assertTrue(in_array($responseJson->status(), [403, 404], true));

        // Directory traversal attempts with .. or %2e%2e must be rejected (403 or 404)
        $this->assertTrue(in_array($this->get('/storage/../../../../something.jpg')->status(), [403, 404], true));
        $this->assertTrue(in_array($this->get('/storage/%2e%2e/%2e%2e/something.png')->status(), [403, 404], true));
    }

    public function test_contact_form_rate_limiting(): void
    {
        // 5 requests allowed per minute
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/kontakt', [
                'name' => 'Jan Kowalski',
                'email' => 'jan@example.com',
                'phone' => '+48500100200',
                'subject' => 'Pytanie o kocięta',
                'message' => 'Dzień dobry, chciałbym zapytać o dostępność kociąt w Państwa hodowli.',
            ]);
            $this->assertNotEquals(429, $response->status());
        }

        // 6th request must be throttled with HTTP 429 Too Many Requests
        $responseBlocked = $this->post('/kontakt', [
            'name' => 'Jan Kowalski',
            'email' => 'jan@example.com',
            'phone' => '+48500100200',
            'subject' => 'Pytanie o kocięta',
            'message' => 'Dzień dobry, chciałbym zapytać o dostępność kociąt w Państwa hodowli.',
        ]);
        $responseBlocked->assertStatus(429);
    }

    public function test_htaccess_and_security_configuration_files_exist_with_hardening(): void
    {
        // Root .htaccess
        $rootHtaccess = base_path('.htaccess');
        $this->assertFileExists($rootHtaccess);
        $rootContent = file_get_contents($rootHtaccess);
        $this->assertStringContainsString('Options -Indexes', $rootContent);
        $this->assertStringContainsString('RewriteRule ^\\. - [F,L]', $rootContent);
        $this->assertStringContainsString('composer\\.(json|lock)', $rootContent);
        $this->assertStringContainsString('X-Content-Type-Options', $rootContent);

        // Public .htaccess
        $publicHtaccess = public_path('.htaccess');
        $this->assertFileExists($publicHtaccess);
        $publicContent = file_get_contents($publicHtaccess);
        $this->assertStringContainsString('Options -Indexes', $publicContent);
        $this->assertStringContainsString('X-Content-Type-Options', $publicContent);
        $this->assertStringContainsString('Require all denied', $publicContent);

        // Storage .htaccess
        $storageHtaccess = storage_path('.htaccess');
        $this->assertFileExists($storageHtaccess);
        $storageContent = file_get_contents($storageHtaccess);
        $this->assertStringContainsString('Require all denied', $storageContent);
    }

    public function test_storage_fallback_route_never_exposes_private_directory_files(): void
    {
        $privateDir = storage_path('app/private/livewire-tmp');
        File::ensureDirectoryExists($privateDir);
        $secretFile = $privateDir.'/secret-audit-probe.jpg';
        File::put($secretFile, 'private-content');

        try {
            $this->get('/storage/secret-audit-probe.jpg')->assertNotFound();
            $this->get('/storage/livewire-tmp/secret-audit-probe.jpg')->assertNotFound();
        } finally {
            @File::delete($secretFile);
        }
    }

    public function test_inactive_user_cannot_access_settings_routes_and_non_admin_settings_redirect_works(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $inactive = User::factory()->create([
            'role_id' => $userRole->id,
            'is_active' => false,
        ]);

        $this->actingAs($inactive)->get('/settings')->assertForbidden();
        $this->actingAs($inactive)->get('/settings/profile')->assertForbidden();
        $this->actingAs($inactive)->get('/settings/password')->assertForbidden();

        $activeUser = User::factory()->create([
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);

        $this->actingAs($activeUser)->get('/settings')->assertRedirect(route('profile.edit'));
        $this->actingAs($activeUser)->get('/settings/profile')->assertOk();
    }

    public function test_svg_upload_is_rejected_and_editor_cannot_delete_media(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $editor = User::factory()->create(['role_id' => $editorRole->id]);

        $svgFile = UploadedFile::fake()->create('xss.svg', 10, 'image/svg+xml');
        $this->actingAs($admin)
            ->post(route('backend.media.store'), ['upload' => $svgFile])
            ->assertSessionHasErrors('upload');

        $media = Media::create([
            'disk' => 'public',
            'directory' => 'media/library',
            'filename' => 'protected-avatar.jpg',
        ]);

        $this->actingAs($editor)
            ->delete(route('backend.media.destroy', $media))
            ->assertForbidden();

        // Confirm outbound AI calls keep TLS verification intact
        $this->assertStringNotContainsString('withoutVerifying', file_get_contents(app_path('Services/GeminiService.php')));
        $this->assertStringNotContainsString('withoutVerifying', file_get_contents(app_path('Services/AiBlogGeneratorService.php')));
        $this->assertStringNotContainsString('withoutVerifying', file_get_contents(app_path('Livewire/Admin/AiBlogStudio.php')));
    }
}
