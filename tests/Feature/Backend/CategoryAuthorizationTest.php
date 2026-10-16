<?php

namespace Tests\Feature\Backend;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'role_id' => $userRole->id,
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_categories(): void
    {
        $this->get(route('backend.categories.index'))->assertRedirect('/login');
        $this->post(route('backend.categories.store'), ['name' => 'Test', 'slug' => 'test'])->assertRedirect('/login');
    }

    public function test_regular_user_cannot_create_or_delete_category(): void
    {
        $category = Category::factory()->create();

        // User cannot view create form
        $this->actingAs($this->regularUser)
            ->get(route('backend.categories.create'))
            ->assertForbidden();

        // User cannot store new category
        $this->actingAs($this->regularUser)
            ->post(route('backend.categories.store'), ['name' => 'Nowa Kategoria', 'slug' => 'nowa-kategoria'])
            ->assertForbidden();

        // User cannot edit category
        $this->actingAs($this->regularUser)
            ->get(route('backend.categories.edit', $category))
            ->assertForbidden();

        // User cannot delete category
        $this->actingAs($this->regularUser)
            ->delete(route('backend.categories.destroy', $category))
            ->assertForbidden();
    }

    public function test_admin_can_manage_categories(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('backend.categories.index'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('backend.categories.create'))
            ->assertOk();

        $this->actingAs($this->admin)
            ->get(route('backend.categories.edit', $category))
            ->assertOk();

        $this->actingAs($this->admin)
            ->delete(route('backend.categories.destroy', $category))
            ->assertRedirect(route('backend.categories.index'));

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
