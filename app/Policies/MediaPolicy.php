<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Media;
use App\Models\User;

/**
 * Authorization policy for the Media domain.
 */
class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor', 'user'], true);
    }

    public function view(User $user, Media $media): bool
    {
        return in_array($user->role?->name, ['admin', 'editor', 'user'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function update(User $user, Media $media): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function delete(User $user, Media $media): bool
    {
        return in_array($user->role?->name, ['admin', 'editor'], true);
    }

    public function restore(User $user, Media $media): bool
    {
        return $user->role?->name === 'admin';
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return $user->role?->name === 'admin';
    }
}
