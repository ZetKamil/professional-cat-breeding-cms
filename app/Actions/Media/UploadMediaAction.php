<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UploadMediaAction
{
    /**
     * Handle single or multiple file uploads and record creation.
     *
     * @param  array  $data  Metadata dictionary
     * @param  UploadedFile|UploadedFile[]  $files
     * @return Media|Media[]
     */
    public function handle(array $data, UploadedFile|array $files): Media|array
    {
        $fileList = is_array($files) ? $files : [$files];
        $created = [];

        return DB::transaction(function () use ($data, $fileList, &$created) {
            foreach ($fileList as $index => $file) {
                if (! ($file instanceof UploadedFile)) {
                    continue;
                }

                $directory = $this->resolveDirectory($data['mediable_type'] ?? null);
                $disk = 'public';

                $storedPath = $file->store($directory, $disk);

                $filename = basename($storedPath);
                $dirName = dirname($storedPath) === '.' ? null : dirname($storedPath);

                if (($data['is_featured'] ?? false) && ! empty($data['mediable_type']) && ! empty($data['mediable_id'])) {
                    Media::query()
                        ->where('mediable_type', $data['mediable_type'])
                        ->where('mediable_id', $data['mediable_id'])
                        ->update(['is_featured' => false]);
                }

                $media = Media::create([
                    'disk' => $disk,
                    'directory' => $dirName,
                    'filename' => $filename,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'title' => $data['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'alt_text' => $data['alt_text'] ?? null,
                    'caption' => $data['caption'] ?? null,
                    'copyright' => $data['copyright'] ?? null,
                    'sort_order' => (int) ($data['sort_order'] ?? 0) + $index,
                    'is_featured' => (bool) ($data['is_featured'] ?? false),
                    'mediable_type' => $data['mediable_type'] ?? null,
                    'mediable_id' => $data['mediable_id'] ?? null,
                ]);

                $created[] = $media;
            }

            return count($created) === 1 ? $created[0] : $created;
        });
    }

    protected function resolveDirectory(?string $mediableType): string
    {
        if (! $mediableType) {
            return 'media/library';
        }

        $base = class_basename($mediableType);

        return 'media/'.strtolower($base).'s';
    }
}
