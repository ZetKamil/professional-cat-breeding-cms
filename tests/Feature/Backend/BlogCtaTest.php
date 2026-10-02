<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogCtaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_blog_cta_page(): void
    {
        $response = $this->get(route('backend.blog-cta.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_blog_cta_page(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        $response = $this->actingAs($user)->get(route('backend.blog-cta.edit'));
        $response->assertForbidden();
    }

    public function test_admin_and_editor_can_access_blog_cta_edit_page(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->get(route('backend.blog-cta.edit'));
        $response->assertOk();
        $response->assertSee('Oferta hodowli pod artykułami');

        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editor = User::factory()->create(['role_id' => $editorRole->id]);

        $responseEditor = $this->actingAs($editor)->get(route('backend.blog-cta.edit'));
        $responseEditor->assertOk();
    }

    public function test_admin_can_update_blog_cta_settings(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $payload = [
            'is_enabled'    => '1',
            'badge'         => 'Hodowla Kotów Mazowiecka Szwajcaria',
            'heading'       => 'Rezerwacja kociąt z miotu 2026',
            'body'          => 'Zapraszamy do rezerwacji kociąt brytyjskich oraz bengalskich.',
            'image_url'     => 'https://example.com/cat.jpg',
            'button_text'   => 'Zobacz Koty',
            'button_url'    => '/koty',
            'facebook_info' => 'Zapraszamy również na nasz profil na Facebooku!',
            'facebook_text' => 'Zobacz na Facebooku',
            'facebook_url'  => 'https://www.facebook.com/profile.php?id=61580668026948',
        ];

        $response = $this->actingAs($admin)
            ->post(route('backend.blog-cta.update'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('backend.blog-cta.edit'));

        $cta = Setting::getBlogCta();
        $this->assertTrue($cta['is_enabled']);
        $this->assertSame('Hodowla Kotów Mazowiecka Szwajcaria', $cta['badge']);
        $this->assertSame('Rezerwacja kociąt z miotu 2026', $cta['heading']);
        $this->assertSame('https://www.facebook.com/profile.php?id=61580668026948', $cta['facebook_url']);
    }
}
