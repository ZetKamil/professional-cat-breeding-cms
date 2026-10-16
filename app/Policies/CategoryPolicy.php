<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

/**
 * Authorization policy for the Category domain.
 *
 * Rules:
 * - Admin, Editor and User can view categories in backend
 * - Only Admin and Editor can create or update categories
 * - Only Admin can delete, restore or permanently delete categories
 */
class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor', 'user'], true);
    }

    public function view(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor', 'user'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function update(User $user, Category $category): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->role?->name === 'admin';
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->role?->name === 'admin';
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->role?->name === 'admin';
    }
}
