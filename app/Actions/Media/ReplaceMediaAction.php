<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReplaceMediaAction
{
    public function handle(Media $media, UploadedFile $file, array $data = []): Media
    {
        return DB::transaction(function () use ($media, $file, $data) {
            $oldPath = $media->path();
            if (Storage::disk($media->disk)->exists($oldPath)) {
                Storage::disk($media->disk)->delete($oldPath);
            }

            $directory = $media->directory ?: 'media/library';
            $storedPath = $file->store($directory, $media->disk);

            $filename = basename($storedPath);
            $dirName = dirname($storedPath) === '.' ? null : dirname($storedPath);

            if (($data['is_featured'] ?? false) && $media->mediable_type && $media->mediable_id) {
                Media::query()
                    ->where('mediable_type', $media->mediable_type)
                    ->where('mediable_id', $media->mediable_id)
                    ->where('id', '!=', $media->id)
                    ->update(['is_featured' => false]);
            }

            $media->update([
                'directory' => $dirName,
                'filename' => $filename,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'title' => $data['title'] ?? $media->title,
                'alt_text' => $data['alt_text'] ?? $media->alt_text,
                'caption' => $data['caption'] ?? $media->caption,
                'copyright' => $data['copyright'] ?? $media->copyright,
                'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : $media->sort_order,
                'is_featured' => isset($data['is_featured']) ? (bool) $data['is_featured'] : $media->is_featured,
            ]);

            return $media;
        });
    }
}
