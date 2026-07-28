<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{


    /**
     * Upload een nieuwe afbeelding en koppel deze aan een model
     */
    public function upload($model, UploadedFile $file, string $directory = null): Media
    {
        $disk = 'public';

        $path = $file->store($directory, $disk);

        $filename = basename($path);

        $directory = dirname($path) === '.' ? null : dirname($path);

        $media = new Media([
            'disk' => $disk,
            'directory' => $directory,
            'filename' => $filename,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        $model->media()->save($media);

        return $media;
    }

    /**
     * Vervang een bestaande afbeelding
     */
    public function replace($model, UploadedFile $file, string $directory = null): Media
    {
        if ($model->media) {
            $this->deleteFile($model->media);
            $model->media->delete();
        }

        return $this->upload($model, $file, $directory);
    }

    /**
     * Verwijder enkel de fysieke file
     */
    public function deleteFile(Media $media): void
    {
        $path = $media->path();

        if (Storage::disk($media->disk)->exists($path)) {
            Storage::disk($media->disk)->delete($path);
        }
    }

    /**
     * Verwijder file en media record
     */
    public function delete(Media $media): void
    {
        $this->deleteFile($media);

        $media->delete();
    }
}
