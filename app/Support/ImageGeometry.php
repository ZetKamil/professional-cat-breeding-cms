<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageGeometry
{
    /**
     * Szybka, bezlatencyjna analiza geometrii zdjęcia (lokalnego na dysku lub ze ścieżki URL).
     *
     * @return array{
     *     orientation: string,
     *     aspect_ratio: float,
     *     width: int,
     *     height: int,
     *     is_portrait: bool,
     *     is_landscape: bool,
     *     is_square: bool
     * }
     */
    public static function analyze(?string $urlOrPath): array
    {
        $default = [
            'orientation'  => 'landscape',
            'aspect_ratio' => 1.778,
            'width'        => 1200,
            'height'       => 675,
            'is_portrait'  => false,
            'is_landscape' => true,
            'is_square'    => false,
        ];

        if (empty($urlOrPath) || ! is_string($urlOrPath)) {
            return $default;
        }

        $localPath = self::resolveLocalPath($urlOrPath);

        if ($localPath && file_exists($localPath)) {
            $info = @getimagesize($localPath);
            if ($info && $info[0] > 0 && $info[1] > 0) {
                $w = (int) $info[0];
                $h = (int) $info[1];
                $ratio = round($w / $h, 3);

                $orientation = match (true) {
                    $ratio < 0.88 => 'portrait',  // Typowy smartfon: 3:4 (0.75), 9:16 (0.56)
                    $ratio > 1.15 => 'landscape', // Standard: 4:3 (1.33), 16:9 (1.78), 21:9
                    default       => 'square',    // ~1:1 (0.88 - 1.15)
                };

                return [
                    'orientation'  => $orientation,
                    'aspect_ratio' => $ratio,
                    'width'        => $w,
                    'height'       => $h,
                    'is_portrait'  => $orientation === 'portrait',
                    'is_landscape' => $orientation === 'landscape',
                    'is_square'    => $orientation === 'square',
                ];
            }
        }

        // Jeśli to zewnętrzny URL lub brak pliku lokalnego, spróbuj odczytać proporcje z parametrów URL (np. pollinations/unsplash)
        if (preg_match('/width=(\d+)&height=(\d+)/', $urlOrPath, $matches)) {
            $w = (int) $matches[1];
            $h = (int) $matches[2];
            if ($w > 0 && $h > 0) {
                $ratio = round($w / $h, 3);
                $orientation = match (true) {
                    $ratio < 0.88 => 'portrait',
                    $ratio > 1.15 => 'landscape',
                    default       => 'square',
                };

                return [
                    'orientation'  => $orientation,
                    'aspect_ratio' => $ratio,
                    'width'        => $w,
                    'height'       => $h,
                    'is_portrait'  => $orientation === 'portrait',
                    'is_landscape' => $orientation === 'landscape',
                    'is_square'    => $orientation === 'square',
                ];
            }
        }

        return $default;
    }

    /**
     * Mapuje podany URL / ścieżkę względną na fizyczną ścieżkę w systemie plików serwera.
     */
    public static function resolveLocalPath(string $urlOrPath): ?string
    {
        $trimmed = trim($urlOrPath);

        // 1. Bezpośrednia istniejąca ścieżka bezwzględna
        if (file_exists($trimmed)) {
            return $trimmed;
        }

        // 2. Obsługa ścieżek zawierających 'storage/'
        if (str_contains($trimmed, 'storage/')) {
            $relative = Str::after($trimmed, 'storage/');
            $relative = ltrim($relative, '/');

            // Najpierw sprawdzamy dysk public (storage/app/public)
            if (Storage::disk('public')->exists($relative)) {
                return Storage::disk('public')->path($relative);
            }

            // Następnie sprawdzamy fizyczny katalog public/storage
            $publicStoragePath = public_path('storage/' . $relative);
            if (file_exists($publicStoragePath)) {
                return $publicStoragePath;
            }
        }

        // 3. Sprawdzenie w public_path
        $publicPath = public_path(ltrim($trimmed, '/'));
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // 4. Sprawdzenie w storage_path('app/public')
        $appPublicPath = storage_path('app/public/' . ltrim($trimmed, '/'));
        if (file_exists($appPublicPath)) {
            return $appPublicPath;
        }

        return null;
    }
}
