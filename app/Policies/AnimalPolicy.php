<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;

/**
 * Authorization policy for the Animal domain.
 *
 * Follows the same pattern as PostPolicy:
 * - Admin and Editor roles have full management access
 * - Regular users can view published animals only
 * - Only admins can restore or permanently delete
 */
class AnimalPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor', 'user'], true);
    }

    public function view(User $user, Animal $animal): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function update(User $user, Animal $animal): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function delete(User $user, Animal $animal): bool
    {
        return $user->role?->name === 'admin';
    }

    public function restore(User $user, Animal $animal): bool
    {
        return $user->role?->name === 'admin';
    }

    public function forceDelete(User $user, Animal $animal): bool
    {
        return $user->role?->name === 'admin';
    }
}
