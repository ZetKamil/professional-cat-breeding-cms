<?php

declare(strict_types=1);

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        $atos = Animal::where('slug', 'atos')->orWhere('name', 'Atos')->first();

        if (! $atos) {
            $atos = Animal::create([
                'name' => 'Atos',
                'slug' => 'atos',
                'breed' => 'Kot Brytyjski',
                'color' => 'Colourpoint niebieski (Blue Point / BLH a 33, brytyjski długowłosy)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 9, 9),
                'short_description' => 'Dostojny kocur rasy Kot Brytyjski Długowłosy o szlachetnym umaszczeniu colourpoint niebieski (BLH a 33).',
                'description' => 'Atos to wyjątkowy kocur rasy Kot Brytyjski Długowłosy (British Longhair – BLH a 33) o jasnej szacie ze szlachetnymi niebieskimi znaczeniami i szafirowym spojrzeniu. Charakteryzuje się mocną, masywną budową ciała, gęstym podszerstkiem i oazą spokoju w usposobieniu. Syn naszej kotki Nel. Zarejestrowany w SHiOZ ZOOLANDIA.',
                'sort_order' => 33,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);
        } else {
            if ($atos->sort_order !== 33) {
                $atos->update(['sort_order' => 33]);
            }
        }

        // 1. Główne zdjęcie profilowe — wykadrowane do idealnego kwadratu 1:1 (1536x1536)
        $this->attachCroppedProfilePhoto(
            $atos,
            base_path('image/Brytyjczyki/Kociaki/Atos/profilowe.jfif'),
            'parent_atos_1.jpg',
            true,
            0
        );

        // 2. Zdjęcie z aniołkiem do galerii (1536x2048)
        $this->attachPhoto(
            $atos,
            base_path('image/Brytyjczyki/Kociaki/Atos/9286a117-c510-41a9-aa87-81fc0c49e1aa.jfif'),
            'parent_atos_2.jpg',
            false,
            1
        );
    }

    public function down(): void
    {
        $atos = Animal::where('slug', 'atos')->first();
        if ($atos) {
            Media::where('mediable_type', Animal::class)
                ->where('mediable_id', $atos->id)
                ->whereIn('filename', ['parent_atos_1.jpg', 'parent_atos_2.jpg'])
                ->delete();
        }
    }

    protected function attachCroppedProfilePhoto(Animal $animal, string $sourcePath, string $targetFilename, bool $isFeatured = true, int $sortOrder = 0): void
    {
        if (! File::exists($sourcePath)) {
            return;
        }

        $storageMediaDir = storage_path('app/public/media');
        if (! File::isDirectory($storageMediaDir)) {
            File::makeDirectory($storageMediaDir, 0755, true, true);
            @chmod($storageMediaDir, 0755);
        }

        $publicMediaDir = public_path('storage/media');
        if (! File::isDirectory($publicMediaDir)) {
            File::makeDirectory($publicMediaDir, 0755, true, true);
            @chmod($publicMediaDir, 0755);
        }

        $targetStorage = $storageMediaDir.'/'.$targetFilename;
        $targetPublic = $publicMediaDir.'/'.$targetFilename;

        // Kadrowanie 1:1 od górnej krawędzi (pełne uszy, głowa i pyszczek)
        $cropped = false;
        if (extension_loaded('gd')) {
            $src = @imagecreatefromjpeg($sourcePath);
            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                $size = min($w, $h);
                $dest = imagecreatetruecolor($size, $size);
                imagecopy($dest, $src, 0, 0, 0, 0, $size, $size);
                imagejpeg($dest, $targetStorage, 92);
                imagedestroy($src);
                imagedestroy($dest);
                $cropped = true;
            }
        }

        if (! $cropped) {
            File::copy($sourcePath, $targetStorage);
        }
        @chmod($targetStorage, 0644);

        if (! is_link(public_path('storage'))) {
            File::copy($targetStorage, $targetPublic);
            @chmod($targetPublic, 0644);
        }

        Media::updateOrCreate(
            ['filename' => $targetFilename],
            [
                'disk' => 'public',
                'directory' => 'media',
                'mime_type' => 'image/jpeg',
                'size' => File::size($targetStorage),
                'title' => "{$animal->name} — {$animal->breed}",
                'alt_text' => "{$animal->name} — {$animal->breed} ({$animal->color})",
                'sort_order' => $sortOrder,
                'is_featured' => $isFeatured,
                'mediable_type' => Animal::class,
                'mediable_id' => $animal->id,
            ]
        );
    }

    protected function attachPhoto(Animal $animal, string $sourcePath, string $targetFilename, bool $isFeatured = false, int $sortOrder = 1): void
    {
        if (! File::exists($sourcePath)) {
            return;
        }

        $storageMediaDir = storage_path('app/public/media');
        if (! File::isDirectory($storageMediaDir)) {
            File::makeDirectory($storageMediaDir, 0755, true, true);
            @chmod($storageMediaDir, 0755);
        }

        $publicMediaDir = public_path('storage/media');
        if (! File::isDirectory($publicMediaDir)) {
            File::makeDirectory($publicMediaDir, 0755, true, true);
            @chmod($publicMediaDir, 0755);
        }

        $targetStorage = $storageMediaDir.'/'.$targetFilename;
        $targetPublic = $publicMediaDir.'/'.$targetFilename;

        File::copy($sourcePath, $targetStorage);
        @chmod($targetStorage, 0644);

        if (! is_link(public_path('storage'))) {
            File::copy($sourcePath, $targetPublic);
            @chmod($targetPublic, 0644);
        }

        Media::updateOrCreate(
            ['filename' => $targetFilename],
            [
                'disk' => 'public',
                'directory' => 'media',
                'mime_type' => 'image/jpeg',
                'size' => File::size($targetStorage),
                'title' => "{$animal->name} — Galeria",
                'alt_text' => "{$animal->name} — {$animal->breed} w domowej scenerii",
                'sort_order' => $sortOrder,
                'is_featured' => $isFeatured,
                'mediable_type' => Animal::class,
                'mediable_id' => $animal->id,
            ]
        );
    }
};
