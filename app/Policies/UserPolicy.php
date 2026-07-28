<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->name === 'admin';
    }

    public function view(User $user, User $model): bool
    {
        return $user->role?->name === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role?->name === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->role?->name === 'admin';
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role?->name === 'admin';
    }

    public function restore(User $user, User $model): bool
    {
        return $user->role?->name === 'admin';
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->role?->name === 'admin';
    }
}
