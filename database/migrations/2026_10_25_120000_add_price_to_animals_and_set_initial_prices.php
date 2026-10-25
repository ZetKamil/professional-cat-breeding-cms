<?php

declare(strict_types=1);

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add price column if not exists
        if (! Schema::hasColumn('animals', 'price')) {
            Schema::table('animals', function (Blueprint $table) {
                $table->unsignedInteger('price')->nullable()->after('color');
            });
        }

        // 2. Ensure Baron exists in local database (from production dump & image files)
        $baron = Animal::where('slug', 'baron')->first();
        if (! $baron) {
            $luna = Animal::where('slug', 'luna')->first();
            $luki = Animal::where('slug', 'luki')->first();

            $baron = Animal::create([
                'id' => '01m14119dq8fj61zfrt2gtt99c',
                'name' => 'Baron',
                'slug' => 'baron',
                'breed' => 'Kot Bengalski',
                'color' => 'Srebrny z czarnymi rozetami',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 5, 5),
                'short_description' => 'Potomek Luny i Lukiego. Dostępny do rezerwacji.',
                'description' => 'Urodzony 5 maja 2026 r., zaszczepiony (2x), odrobaczony (2x), zachipowany, z rodowodem SHiOZ ZOOLANDIA. Gotowy do rezerwacji do nowego domu.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => Carbon::create(2026, 6, 15, 20, 10, 0),
                'sort_order' => 2,
                'price' => 2700,
            ]);

            $photoPath = base_path('image/Bengale/Kociaki/Baron/9e0662c2-3df4-4eb5-8c59-6e88fa7d10b4.jpg');
            if (File::exists($photoPath)) {
                $this->attachPhoto($baron, $photoPath, 'kitten_baron_1.jpg');
            }
        } else {
            $baron->update(['price' => 2700]);
        }

        // 3. Set prices for Bengal kittens
        Animal::where('slug', 'cyprian')->update(['price' => 3000]);
        Animal::where('slug', 'carlos')->update(['price' => 3000]);

        // 4. Set prices for all available British kittens: 2 500 zł each
        Animal::where('breed', 'Kot Brytyjski')
            ->where('status', AnimalStatus::Available)
            ->update(['price' => 2500]);
        // Also ensure specific named British kittens have price set
        Animal::whereIn('slug', ['ada', 'alma', 'atena'])->update(['price' => 2500]);

        // 5. Set prices for available Siamese kittens: 2 100 zł each
        Animal::where('breed', 'Kot Syjamski')
            ->where('status', AnimalStatus::Available)
            ->update(['price' => 2100]);
        Animal::whereIn('slug', ['dafne', 'daisy', 'dakota'])->update(['price' => 2100]);

        // 6. Ensure adult breeding cats have price = null
        Animal::where('status', AnimalStatus::Breeding)->update(['price' => null]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('animals', 'price')) {
            Schema::table('animals', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }

    protected function attachPhoto(Animal $animal, string $sourcePath, string $targetFilename): void
    {
        $storageMediaDir = storage_path('app/public/media');
        if (! File::isDirectory($storageMediaDir)) {
            File::makeDirectory($storageMediaDir, 0755, true, true);
        }

        $publicMediaDir = public_path('storage/media');
        if (! File::isDirectory($publicMediaDir)) {
            File::makeDirectory($publicMediaDir, 0755, true, true);
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
                'title' => "{$animal->name} — {$animal->breed}",
                'alt_text' => "{$animal->name} — {$animal->breed} ({$animal->color})",
                'sort_order' => 0,
                'is_featured' => true,
                'mediable_type' => Animal::class,
                'mediable_id' => $animal->id,
            ]
        );
    }
};
