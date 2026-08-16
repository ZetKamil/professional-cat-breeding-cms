<?php

namespace App\Services;

use App\Actions\Media\DeleteMediaAction;
use App\Actions\Media\ReplaceMediaAction;
use App\Actions\Media\UploadMediaAction;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function __construct(
        protected UploadMediaAction $uploadAction,
        protected ReplaceMediaAction $replaceAction,
        protected DeleteMediaAction $deleteAction,
    ) {}

    /**
     * Upload a new media file and optionally attach to a model.
     */
    public function upload($model, UploadedFile $file, ?string $directory = null): Media
    {
        $data = [
            'mediable_type' => $model ? $model::class : null,
            'mediable_id' => $model ? $model->getKey() : null,
        ];

        $media = $this->uploadAction->handle($data, $file);

        return is_array($media) ? $media[0] : $media;
    }

    /**
     * Replace an existing media file for a model.
     */
    public function replace($model, UploadedFile $file, ?string $directory = null): Media
    {
        if ($model->media) {
            return $this->replaceAction->handle($model->media, $file);
        }

        return $this->upload($model, $file, $directory);
    }

    /**
     * Delete only the physical file from disk.
     */
    public function deleteFile(Media $media): void
    {
        $path = $media->path();

        if (Storage::disk($media->disk)->exists($path)) {
            Storage::disk($media->disk)->delete($path);
        }
    }

    /**
     * Delete physical file and DB record.
     */
    public function delete(Media $media): void
    {
        $this->deleteAction->handle($media);
    }
}

