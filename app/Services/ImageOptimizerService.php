<?php

declare(strict_types=1);

namespace App\Services;

use Throwable;

class ImageOptimizerService
{
    /**
     * Maximum dimension (width or height) in pixels.
     */
    protected int $maxDimension = 1920;

    /**
     * JPEG and WebP quality (0-100).
     */
    protected int $quality = 85;

    /**
     * Optimize an image file on disk in-place.
     *
     * - Fixes orientation from EXIF metadata (e.g. from smartphones)
     * - Proportionally downscales images exceeding $maxDimension
     * - Re-compresses the image to significantly reduce file size without visible degradation
     *
     * @param string $filePath Absolute path to the image file
     * @return array{width: int, height: int, size: int}
     */
    public function optimize(string $filePath): array
    {
        if (!file_exists($filePath) || !is_file($filePath)) {
            return ['width' => 0, 'height' => 0, 'size' => 0];
        }

        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return [
                'width' => 0,
                'height' => 0,
                'size' => filesize($filePath) ?: 0,
            ];
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'] ?? '';

        try {
            // 1. Create GD image resource based on mime type
            $image = match ($mime) {
                'image/jpeg', 'image/jpg', 'image/pjpeg' => @imagecreatefromjpeg($filePath),
                'image/png' => @imagecreatefrompng($filePath),
                'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : false,
                default => false,
            };

            if (!$image) {
                return [
                    'width' => $width,
                    'height' => $height,
                    'size' => filesize($filePath) ?: 0,
                ];
            }

            // 2. Fix orientation using EXIF (JPEG only)
            if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg'], true) && function_exists('exif_read_data')) {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    $orientation = (int) $exif['Orientation'];
                    $image = match ($orientation) {
                        3 => imagerotate($image, 180, 0),
                        6 => imagerotate($image, -90, 0),
                        8 => imagerotate($image, 90, 0),
                        default => $image,
                    };
                    $width = imagesx($image);
                    $height = imagesy($image);
                }
            }

            // 3. Calculate new dimensions if image exceeds max dimension
            $targetWidth = $width;
            $targetHeight = $height;

            if ($width > $this->maxDimension || $height > $this->maxDimension) {
                if ($width >= $height) {
                    $targetWidth = $this->maxDimension;
                    $targetHeight = (int) round(($height / $width) * $this->maxDimension);
                } else {
                    $targetHeight = $this->maxDimension;
                    $targetWidth = (int) round(($width / $height) * $this->maxDimension);
                }
            }

            // 4. Create canvas and resample
            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            // Handle transparency for PNG and WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);
            }

            imagecopyresampled(
                $canvas,
                $image,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                $width,
                $height
            );

            // 5. Save back to disk
            match ($mime) {
                'image/jpeg', 'image/jpg', 'image/pjpeg' => imagejpeg($canvas, $filePath, $this->quality),
                'image/png' => imagepng($canvas, $filePath, 8),
                'image/webp' => imagewebp($canvas, $filePath, $this->quality),
                default => null,
            };

            imagedestroy($canvas);
            imagedestroy($image);

            clearstatcache(true, $filePath);

            return [
                'width' => $targetWidth,
                'height' => $targetHeight,
                'size' => filesize($filePath) ?: 0,
            ];
        } catch (Throwable) {
            return [
                'width' => $width,
                'height' => $height,
                'size' => filesize($filePath) ?: 0,
            ];
        }
    }
}
