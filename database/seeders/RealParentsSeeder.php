<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RealParentsSeeder extends Seeder
{
    public function run(): void
    {
        $storageDir = storage_path('app/public/media');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $publicImageDir = public_path('image');
        if (! File::exists($publicImageDir)) {
            File::makeDirectory($publicImageDir, 0755, true);
        }

        $parentsData = [
            // ── BENGALE ──────────────────────────────────────────────
            [
                'name' => 'Bella',
                'slug' => 'bella',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy cętkowany (Brown Spotted Tabby)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Przepiękna kotka hodowlana rasy Kot Bengalski z kontrastowymi cętkami i lśniącą, jedwabistą sierścią.',
                'description' => 'Bella to nasza wybitna kotka hodowlana rasy Kot Bengalski. Posiada doskonały kontrast cętek, łagodne usposobienie oraz najwyższe certyfikaty medyczne. Wolna od HCM, PKD, SMA oraz FIV/FeLV.',
                'source_photos' => [
                    base_path('image/Bengale/Reproduktory/Bella/Bella .jpg'),
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Luki',
                'slug' => 'luki',
                'breed' => 'Kot Bengalski',
                'color' => 'Śnieżny rozetowy (Snow Seal Lynx Point)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Dostojny, silny reproduktor rasy Kot Bengalski o głębokich szafirowych oczach i nieskazitelnych rozetach.',
                'description' => 'Luki to nasz ceniony reproduktor bengalski. Odznacza się wybitną budową anatomiczną, unikalnym ubarwieniem Snow Lynx Point i spokojnym, przyjacielskim charakterem.',
                'source_photos' => [
                    base_path('image/Bengale/Reproduktory/Luki/Luki.jpg'),
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Luna',
                'slug' => 'luna',
                'breed' => 'Kot Bengalski',
                'color' => 'Srebrzysty cętkowany (Silver Spotted)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Elegancka kotka hodowlana rasy Kot Bengalski o ubarwieniu Silver Spotted i wspaniałym domowym usposobieniu.',
                'description' => 'Luna jest kotką o wyrazistym, srebrzystym ubarwieniu z kontrastowym rysunkiem. Wychowana w sercu domu, wspaniale opiekuje się miotami.',
                'source_photos' => [
                    base_path('image/Bengale/Reproduktory/Luna/Luna .jpg'),
                ],
                'sort_order' => 3,
            ],

            // ── BRYTYJCZYKI ──────────────────────────────────────────
            [
                'name' => 'Nel',
                'slug' => 'nel',
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieska (British Blue)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Pluszowa, dostojna kotka hodowlana rasy Kot Brytyjski o klasycznej niebieskiej okrywie i głębokich miedzianych oczach.',
                'description' => 'Nel reprezentuje tradycyjny typ brytyjski o gęstym, pluszowym futrze i wspaniałej, okrągłej głowie. Spokojna, czuła i bezproblemowa w domowym życiu.',
                'source_photos' => [
                    base_path('image/Brytyjczyki/Reproduktory/Nel/Nel.jpg'),
                    base_path('image/Brytyjczyki/Reproduktory/Nel/Nel1.jpg'),
                ],
                'sort_order' => 4,
            ],
            [
                'name' => 'Staś',
                'slug' => 'stas',
                'breed' => 'Kot Brytyjski',
                'color' => 'Kremowy (British Cream)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Potężny, niezwykle łagodny reproduktor brytyjski o unikalnym kremowym ubarwieniu i silnym kośćcu.',
                'description' => 'Staś to nasz flagowy reproduktor rasy Kot Brytyjski. Zachwyca misiowatym wyglądem, zrównoważonym temperamentem oraz doskonałymi wynikami profilaktyki zdrowotnej.',
                'source_photos' => [
                    base_path('image/Brytyjczyki/Reproduktory/Stas/Stas.jpg'),
                    base_path('image/Brytyjczyki/Reproduktory/Stas/Stas1.jpg'),
                ],
                'sort_order' => 5,
            ],

            // ── SYJAMSKIE ────────────────────────────────────────────
            [
                'name' => 'Baltazar',
                'slug' => 'baltazar',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Szlachetny reproduktor syjamski o smukłej sylwetce, ubarwieniu Seal Point i zniewalających błękitnych oczach.',
                'description' => 'Baltazar jest dumnym przedstawicielem linii syjamskiej. Charakteryzuje go ogromna przyjacielskość, wysoki poziom inteligencji oraz piękne kontrasty ubarwienia.',
                'source_photos' => [
                    base_path('image/Syjamy/Reproduktory/Baltazar/Baltazar .jpg'),
                ],
                'sort_order' => 6,
            ],
            [
                'name' => 'Koko',
                'slug' => 'koko',
                'breed' => 'Kot Syjamski',
                'color' => 'Blue Point',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Urocza, niezwykle wokalna i czuła kotka syjamska o delikatnych znaczeniach Blue Point.',
                'description' => 'Koko wprowadza mnóstwo radości do naszej hodowli. Jest kotką nienagannie socjalizowaną, zawsze szukającą kontaktu z człowiekiem i gotową do przytulania.',
                'source_photos' => [
                    base_path('image/Syjamy/Reproduktory/Koko/Koko.jpg'),
                ],
                'sort_order' => 7,
            ],
            [
                'name' => 'Nela',
                'slug' => 'nela-syjamska',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => '2023-04-22',
                'short_description' => 'Przepiękna kotka hodowlana rasy Kot Syjamski o klasycznym ubarwieniu Seal Point i hipnotyzujących szafirowych oczach.',
                'description' => 'Nela to wspaniała kotka hodowlana rasy Kot Syjamski o umaszczeniu Seal Point. Odznacza się harmonijną budową, doskonałym kontrastem znaczeń oraz niezwykle łagodnym, przyjacielskim charakterem. Urodzona 22.04.2023 r.',
                'source_photos' => [
                    base_path('image/Syjamy/Reproduktory/Nela/profilowe.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/700a8851-0a09-427f-9692-b14ffb9f2d39.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/a1a9a5d9-2be4-4ecd-816a-88485b5ff522.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/b774b604-0440-4966-bc3f-582621045f8b.jfif'),
                ],
                'sort_order' => 8,
            ],
        ];

        foreach ($parentsData as $data) {
            $animal = Animal::withTrashed()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'breed' => $data['breed'],
                    'color' => $data['color'],
                    'gender' => $data['gender'],
                    'status' => $data['status'],
                    'type' => $data['type'],
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now(),
                    'deleted_at' => null,
                ]
            );

            // Clear old media for this animal if re-running
            Media::where('mediable_type', Animal::class)->where('mediable_id', $animal->id)->delete();

            $photoIndex = 0;
            foreach ($data['source_photos'] as $sourcePath) {
                if (File::exists($sourcePath)) {
                    $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) ?: 'jpg';
                    $targetFilename = "parent_{$data['slug']}_" . ($photoIndex + 1) . ".{$ext}";
                    $targetPath = storage_path("app/public/media/{$targetFilename}");

                    File::copy($sourcePath, $targetPath);

                    Media::create([
                        'disk' => 'public',
                        'directory' => 'media',
                        'filename' => $targetFilename,
                        'mime_type' => 'image/jpeg',
                        'size' => File::size($targetPath),
                        'title' => "{$data['name']} — {$data['breed']}",
                        'alt_text' => "{$data['name']} — {$data['breed']} ({$data['color']})",
                        'sort_order' => $photoIndex,
                        'is_featured' => ($photoIndex === 0),
                        'mediable_type' => Animal::class,
                        'mediable_id' => $animal->id,
                    ]);

                    $photoIndex++;
                }
            }
        }

        // Link sample kittens to their real parents
        $bella = Animal::where('slug', 'bella')->first();
        $luki = Animal::where('slug', 'luki')->first();
        $nel = Animal::where('slug', 'nel')->first();
        $stas = Animal::where('slug', 'stas')->first();
        $koko = Animal::where('slug', 'koko')->first();
        $baltazar = Animal::where('slug', 'baltazar')->first();

        Animal::where('slug', 'simba')->update(['mother_id' => $nel?->id, 'father_id' => $stas?->id]);
        Animal::where('slug', 'mia')->update(['mother_id' => $koko?->id, 'father_id' => $baltazar?->id]);
        Animal::where('slug', 'cleo')->update(['mother_id' => $bella?->id, 'father_id' => $luki?->id]);
    }
}
