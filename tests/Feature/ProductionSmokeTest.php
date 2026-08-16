<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\Category;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionSmokeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create and authenticate an Admin user.
     */
    protected function createAdminUser(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        return $user;
    }

    // ─── 1. PUBLIC ROUTES SMOKE TEST ──────────────────────────────────────────

    public function test_public_pages_return_http_200(): void
    {
        $category = Category::create([
            'name' => 'Zdrowie',
            'slug' => 'zdrowie',
        ]);

        $admin = $this->createAdminUser();

        $post = Post::create([
            'title' => 'Prawidłowe Żywienie Kociąt Rasowych',
            'slug' => 'prawidlowe-zywienie-kociat-rasowych',
            'body' => 'Kompletny poradnik dotyczący żywienia kotów.',
            'user_id' => $admin->id,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        $post->categories()->attach($category);

        $animal = Animal::create([
            'name' => 'Luna',
            'slug' => 'luna-kot-bengalski',
            'breed' => 'Kot Bengalski',
            'color' => 'Brown Tabby Spotted',
            'gender' => 'female',
            'status' => 'available',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $publicRoutes = [
            '/',
            '/about',
            '/o-hodowli',
            '/contact',
            '/login',
            '/register',
            '/forgot-password',
            '/polityka-prywatnosci',
            '/regulamin',
            '/koty',
            '/koty/' . $animal->slug,
            '/blog',
            '/blog/' . $post->slug,
            '/sitemap.xml',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertOk();
        }
    }

    // ─── 2. AUTHENTICATED BACKEND ROUTES SMOKE TEST ───────────────────────────

    public function test_authenticated_admin_can_access_all_backend_modules(): void
    {
        $admin = $this->createAdminUser();

        $backendRoutes = [
            '/dashboard',
            '/backend/categories',
            '/backend/posts',
            '/backend/media',
            '/backend/users',
            '/backend/roles',
            '/settings/profile',
            '/settings/password',
            '/settings/appearance',
        ];

        foreach ($backendRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertOk();
        }

        // Two-factor settings route (requires confirmed password session)
        $response2Fa = $this->actingAs($admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/settings/two-factor');
        $response2Fa->assertOk();
    }

    // ─── 3. FORM SUBMISSION AND CONTACT FLOW ──────────────────────────────────

    public function test_contact_form_processes_and_redirects(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Jan Kowalski',
            'email'   => 'jan.kowalski@example.com',
            'phone'   => '+48 514 153 204',
            'subject' => 'Pytanie o miot bengalski',
            'message' => 'Dzień dobry, chciałbym dowiedzieć się więcej o planowanych miotach.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('status');
    }

    // ─── 4. SECURITY & CONFIG INTEGRITY ───────────────────────────────────────

    public function test_htaccess_and_security_files_are_present(): void
    {
        $this->assertFileExists(public_path('.htaccess'));
        $this->assertFileExists(base_path('.htaccess'));
        $this->assertFileExists(public_path('robots.txt'));
        $this->assertFileExists(base_path('storage/.htaccess'));
    }
}
