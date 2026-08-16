<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
            '/contact',
            '/o-hodowli',
            '/about',
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
    }

    public function test_guest_is_blocked_from_all_backend_routes(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/check-media',
            '/fix-storage',
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

    public function test_non_admin_user_cannot_execute_fix_storage(): void
    {
        $user = User::factory()->create(); // standard user without admin role

        $response = $this->actingAs($user)->get('/fix-storage');
        $response->assertForbidden();
    }

    public function test_media_streaming_rejects_directory_traversal_and_disallowed_extensions(): void
    {
        // Disallowed extensions must return 404
        $responseEnv = $this->get('/storage/media/production.env');
        $responseEnv->assertNotFound();

        $responsePhp = $this->get('/storage/media/shell.php');
        $responsePhp->assertNotFound();

        $responseSh = $this->get('/storage/media/script.sh');
        $responseSh->assertNotFound();

        $responseSql = $this->get('/storage/media/dump.sql');
        $responseSql->assertNotFound();

        $responseJson = $this->get('/storage/media/composer.json');
        $responseJson->assertNotFound();
    }

    public function test_contact_form_rate_limiting(): void
    {
        // 5 requests allowed per minute
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/contact', [
                'name' => 'Jan Kowalski',
                'email' => 'jan@example.com',
                'phone' => '+48500100200',
                'subject' => 'Pytanie o kocięta',
                'message' => 'Dzień dobry, chciałbym zapytać o dostępność kociąt w Państwa hodowli.',
            ]);
            $this->assertNotEquals(429, $response->status());
        }

        // 6th request must be throttled with HTTP 429 Too Many Requests
        $responseBlocked = $this->post('/contact', [
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
}
