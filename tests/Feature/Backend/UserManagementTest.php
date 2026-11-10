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
}
