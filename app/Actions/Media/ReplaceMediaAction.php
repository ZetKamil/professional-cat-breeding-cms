<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ReplaceMediaAction
{
    public function __construct(
        protected ?\App\Services\ImageOptimizerService $imageOptimizerService = null
    ) {
        $this->imageOptimizerService = $this->imageOptimizerService ?? new \App\Services\ImageOptimizerService;
    }

    public function handle(Media $media, UploadedFile $file, array $data = []): Media
    {
        return DB::transaction(function () use ($media, $file, $data) {
            $oldPath = $media->path();
            $diskName = $media->disk ?: 'public';
            $disk = Storage::disk($diskName);
            $directory = $media->directory ?: 'media/library';
            $storedPath = $file->store($directory, $diskName);
            $publicTarget = public_path('storage/'.$storedPath);
            $copiedToPublic = false;

            try {
                $storageFullPath = $disk->path($storedPath);
                $fileSize = $file->getSize();

                if (File::exists($storageFullPath)) {
                    // Optimize image in-place (downscale, EXIF orientation fix, compression)
                    $optimizedInfo = $this->imageOptimizerService->optimize($storageFullPath);
                    if ($optimizedInfo['size'] > 0) {
                        $fileSize = $optimizedInfo['size'];
                    }

                    @chmod($storageFullPath, 0644);

                    if ($diskName === 'public' && ! is_link(public_path('storage'))) {
                        $publicTargetDir = dirname($publicTarget);
                        if (! File::isDirectory($publicTargetDir)) {
                            File::makeDirectory($publicTargetDir, 0755, true, true);
                            @chmod($publicTargetDir, 0755);
                        }
                        $copiedToPublic = @File::copy($storageFullPath, $publicTarget);
                        if ($copiedToPublic) {
                            @chmod($publicTarget, 0644);
                        }
                    }
                }

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
                    'size' => $fileSize,
                    'title' => $data['title'] ?? $media->title,
                    'alt_text' => $data['alt_text'] ?? $media->alt_text,
                    'caption' => $data['caption'] ?? $media->caption,
                    'copyright' => $data['copyright'] ?? $media->copyright,
                    'sort_order' => isset($data['sort_order']) ? (int) $data['sort_order'] : $media->sort_order,
                    'is_featured' => isset($data['is_featured']) ? (bool) $data['is_featured'] : $media->is_featured,
                ]);
            } catch (\Throwable $e) {
                if ($disk->exists($storedPath)) {
                    $disk->delete($storedPath);
                }
                if ($copiedToPublic && File::exists($publicTarget)) {
                    @File::delete($publicTarget);
                }
                throw $e;
            }

            if ($oldPath !== '' && $oldPath !== $storedPath) {
                if ($disk->exists($oldPath)) {
                    $disk->delete($oldPath);
                }
                $oldPublicPath = public_path('storage/'.$oldPath);
                if (! is_link(public_path('storage')) && File::exists($oldPublicPath)) {
                    @File::delete($oldPublicPath);
                }
            }

            return $media;
        });
    }
}
