<?php

declare(strict_types=1);

namespace Tests\Feature\Backend;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_update_unverified_user_when_verified_checkbox_is_unchecked(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // Unchecked checkbox omits 'verified' from POST payload
        $createResponse = $this->actingAs($admin)
            ->post(route('backend.users.store'), [
                'name' => 'Niezweryfikowany Redaktor',
                'email' => 'unverified-editor@example.com',
                'role_id' => $editorRole->id,
                'is_active' => 1,
                'password' => 'StrongPass123!',
                'password_confirmation' => 'StrongPass123!',
            ]);

        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect(route('backend.users.index'));

        $createdUser = User::where('email', 'unverified-editor@example.com')->first();
        expect($createdUser)->not->toBeNull()
            ->and($createdUser->email_verified_at)->toBeNull();

        // Update user without 'verified' and verify route-bound ignore does not collide on spoofed 'user' input
        $updateResponse = $this->actingAs($admin)
            ->patch(route('backend.users.update', $createdUser), [
                'name' => 'Zaktualizowany Redaktor',
                'email' => 'unverified-editor@example.com',
                'role_id' => $editorRole->id,
                'is_active' => 1,
                'user' => 'spoofed-string-value',
            ]);

        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('backend.users.index'));

        $createdUser->refresh();
        expect($createdUser->name)->toBe('Zaktualizowany Redaktor')
            ->and($createdUser->email_verified_at)->toBeNull();
    }

    public function test_admin_cannot_delete_or_deactivate_own_account(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id, 'is_active' => true]);

        $this->actingAs($admin)
            ->from(route('backend.users.index'))
            ->delete(route('backend.users.destroy', $admin))
            ->assertRedirect(route('backend.users.index'))
            ->assertSessionHas('error');

        expect($admin->fresh()->trashed())->toBeFalse();

        $this->actingAs($admin)
            ->from(route('backend.users.edit', $admin))
            ->patch(route('backend.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role_id' => $adminRole->id,
                'is_active' => 0,
            ])
            ->assertRedirect(route('backend.users.edit', $admin))
            ->assertSessionHas('error');

        expect($admin->fresh()->is_active)->toBeTrue();
    }
}
