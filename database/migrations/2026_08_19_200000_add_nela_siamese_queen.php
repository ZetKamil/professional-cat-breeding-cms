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
        // Clean up any temporary 'nela' record to ensure 'nela-syjamska' is the single unique slug
        $oldNela = Animal::where('slug', 'nela')->first();
        if ($oldNela) {
            $oldNela->update(['slug' => 'nela-syjamska']);
            $nela = $oldNela;
        } else {
            $nela = Animal::where('slug', 'nela-syjamska')->first();
        }

        if (! $nela) {
            $nela = Animal::create([
                'name' => 'Nela',
                'slug' => 'nela-syjamska',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2023, 4, 22),
                'short_description' => 'Przepiękna kotka hodowlana rasy Kot Syjamski o klasycznym ubarwieniu Seal Point i hipnotyzujących szafirowych oczach.',
                'description' => 'Nela to wspaniała kotka hodowlana rasy Kot Syjamski o umaszczeniu Seal Point. Odznacza się harmonijną budową, doskonałym kontrastem znaczeń oraz niezwykle łagodnym, przyjacielskim charakterem. Urodzona 22.04.2023 r.',
                'sort_order' => 17,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        $photos = [
            base_path('image/Syjamy/Reproduktory/Nela/profilowe.jfif'),
            base_path('image/Syjamy/Reproduktory/Nela/700a8851-0a09-427f-9692-b14ffb9f2d39.jfif'),
            base_path('image/Syjamy/Reproduktory/Nela/a1a9a5d9-2be4-4ecd-816a-88485b5ff522.jfif'),
            base_path('image/Syjamy/Reproduktory/Nela/b774b604-0440-4966-bc3f-582621045f8b.jfif'),
        ];

        $photoIdx = 1;
        foreach ($photos as $photoPath) {
            if (File::exists($photoPath)) {
                $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION)) ?: 'jfif';
                $targetFilename = "parent_nela_syjamska_{$photoIdx}.{$ext}";
                $this->attachPhoto($nela, $photoPath, $targetFilename, $photoIdx === 1, $photoIdx - 1);
                $photoIdx++;
            }
        }
    }

    public function down(): void
    {
        Animal::where('slug', 'nela-syjamska')->orWhere('slug', 'nela')->delete();
    }

    protected function attachPhoto(Animal $animal, string $sourcePath, string $targetFilename, bool $isFeatured = true, int $sortOrder = 0): void
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
};
