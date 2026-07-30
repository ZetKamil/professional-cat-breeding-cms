<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteMediaAction
{
    public function handle(Media $media): bool
    {
        return DB::transaction(function () use ($media) {
            $path = $media->path();

            if (Storage::disk($media->disk)->exists($path)) {
                Storage::disk($media->disk)->delete($path);
            }

            return (bool) $media->delete();
        });
    }
}
