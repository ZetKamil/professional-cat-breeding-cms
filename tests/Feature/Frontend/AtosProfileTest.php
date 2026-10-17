<?php

declare(strict_types=1);

namespace Tests\Feature\Frontend;

use App\Models\Animal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtosProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_atos_profile_page_renders_with_featured_and_gallery_photos(): void
    {
        $atos = Animal::where('slug', 'atos')->first();
        expect($atos)->not->toBeNull();

        $response = $this->get('/koty/atos');

        $response->assertOk();
        $response->assertSee('Atos');
        $response->assertSee('Kot Brytyjski');
        $response->assertSee('Colourpoint niebieski');
        $response->assertSee('Reproduktor');
        $response->assertSee('parent_atos_1.jpg');
        $response->assertSee('parent_atos_2.jpg');
        $response->assertSee('Nel');
    }

    public function test_atos_appears_in_catalogue_with_featured_photo(): void
    {
        $response = $this->get('/koty');

        $response->assertOk();
        $response->assertSee('Atos');
        $response->assertSee('parent_atos_1.jpg');
    }

    public function test_authenticated_admin_can_view_atos_in_cms_editor(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $response = $this->actingAs($admin)->get('/backend/animals/atos/edit');

        $response->assertOk();
        $response->assertSee('Atos');
        $response->assertSee('parent_atos_1.jpg');
        $response->assertSee('Dopasuj kadr obecnego zdjęcia');
    }
}
