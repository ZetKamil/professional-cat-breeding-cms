<?php

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
        $luna = Animal::where('name', 'like', '%Luna%')->orWhere('slug', 'luna')->first();
        $luki = Animal::where('name', 'like', '%Luki%')->orWhere('slug', 'luki')->first();

        // 1. Add Barbie (Sold)
        if (! Animal::where('slug', 'barbie')->exists()) {
            $barbie = Animal::create([
                'name' => 'Barbie',
                'slug' => 'barbie',
                'breed' => 'Kot Bengalski',
                'color' => 'Rozeta na Złocie (Brown Spotted Tabby)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Sold,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Przepiękna kotka bengalska o wyrazistych rozetach i jedwabistym futrze. Córka Luny i Lukiego. Znalazła już swój wymarzony dom.',
                'description' => 'Barbie to urocza kotka bengalska o wyjątkowo ciepłym, złocistym odcieniu futra i doskonałym kontraście rozet. Odznacza się niezwykle łagodnym, przyjacielskim charakterem i zamiłowaniem do wspólnych zabaw. Urodzona 21 czerwca 2026 r. z doskonałego skojarzenia Luny i Lukiego. Znalazła już swój nowy, kochający dom.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 10,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $barbie,
                base_path('image/Bengale/Kociaki/Barbie/1319f9c7-e385-4c24-a5c3-c7943e632b30.jfif'),
                'kitten_barbie_1.jpg'
            );
        }

        // 2. Add Bella (Available Kitten)
        if (! Animal::where('slug', 'bella-kociak')->exists()) {
            $bellaKitten = Animal::create([
                'name' => 'Bella (Kocię)',
                'slug' => 'bella-kociak',
                'breed' => 'Kot Bengalski',
                'color' => 'Ciepłe Złoto z Rozetami (Brown Spotted Tabby)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Cudowna, radosna kotka bengalska o wspaniałym kontraście i ufnej naturze. Córka Luny i Lukiego. Dostępna do rezerwacji.',
                'description' => 'Bella to wyjątkowa mała koteczka bengalska o głębokim, złotym umaszczeniu z pięknie zarysowanymi rozetami. Wychowywana w sercu naszego domu, od pierwszych chwil otoczona troską i miłością. Córka naszej utytułowanej Luny oraz potężnego kocura Lukiego. Urodzona 21 czerwca 2026 r., zaszczepiona (2x), odrobaczona (2x), zachipowana, z rodowodem SHiOZ ZOOLANDIA. Gotowa do rezerwacji do nowego, odpowiedzialnego domu.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 1,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $bellaKitten,
                base_path('image/Bengale/Kociaki/Bella/8997de83-1d4f-425f-9114-933f4da1c6ff.jfif'),
                'kitten_bella_kociak_1.jpg'
            );
        }

        // 3. Attach additional gallery photos for Stas if present
        $stas = Animal::where('name', 'like', '%Staś%')->orWhere('name', 'like', '%Stas%')->orWhere('slug', 'stas')->first();
        if ($stas) {
            $stasPhotos = [
                base_path('image/Brytyjczyki/Reproduktory/Stas/stas2 (1).jpg'),
                base_path('image/Brytyjczyki/Reproduktory/Stas/stas2 (2).jpg'),
            ];

            foreach ($stasPhotos as $idx => $sPhoto) {
                if (File::exists($sPhoto)) {
                    $targetFilename = 'parent_stas_gallery_' . ($idx + 1) . '.jpg';
                    if (! Media::where('filename', $targetFilename)->exists()) {
                        $this->attachPhoto($stas, $sPhoto, $targetFilename, false);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Animal::whereIn('slug', ['barbie', 'bella-kociak'])->delete();
    }

    protected function attachPhoto(Animal $animal, string $sourcePath, string $targetFilename, bool $isFeatured = true): void
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

        $targetStorage = $storageMediaDir . '/' . $targetFilename;
        $targetPublic = $publicMediaDir . '/' . $targetFilename;

        File::copy($sourcePath, $targetStorage);
        @chmod($targetStorage, 0644);

        if (! is_link(public_path('storage'))) {
            File::copy($sourcePath, $targetPublic);
            @chmod($targetPublic, 0644);
        }

        Media::create([
            'disk' => 'public',
            'directory' => 'media',
            'filename' => $targetFilename,
            'mime_type' => 'image/jpeg',
            'size' => File::size($targetStorage),
            'title' => "{$animal->name} — {$animal->breed}",
            'alt_text' => "{$animal->name} — {$animal->breed} ({$animal->color})",
            'sort_order' => $isFeatured ? 0 : 1,
            'is_featured' => $isFeatured,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);
    }
};
