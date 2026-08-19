<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AnimalGender;
use App\Enums\AnimalStatus;
use App\Enums\AnimalType;
use App\Models\Animal;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RealKittensAndParentsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing animals and media to avoid duplicate placeholders
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('media')->where('mediable_type', Animal::class)->delete();
        DB::table('animals')->delete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $storageDir = storage_path('app/public/media');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // ── 2. PARENTS DATA ──────────────────────────────────────────
        $parentsData = [
            // Bengale Parents
            'bella' => [
                'name' => 'Bella',
                'slug' => 'bella',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy cętkowany (Brown Spotted Tabby)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Przepiękna kotka hodowlana rasy Kot Bengalski z kontrastowymi cętkami i lśniącą sierścią.',
                'description' => 'Bella to nasza wybitna kotka hodowlana rasy Kot Bengalski. Posiada doskonały kontrast cętek, łagodne usposobienie oraz najwyższe certyfikaty medyczne (HCM, PKD, SMA n/n, FIV/FeLV negatywny).',
                'photos' => [
                    base_path('image/Bengale/Reproduktory/Bella/Bella .jpg'),
                ],
                'sort_order' => 10,
            ],
            'luki' => [
                'name' => 'Luki',
                'slug' => 'luki',
                'breed' => 'Kot Bengalski',
                'color' => 'Śnieżny rozetowy (Snow Seal Lynx Point)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Dostojny, silny reproduktor rasy Kot Bengalski o głębokich szafirowych oczach i nieskazitelnych rozetach.',
                'description' => 'Luki to nasz ceniony reproduktor bengalski. Odznacza się wybitną budową anatomiczną, unikalnym ubarwieniem Snow Lynx Point i przyjacielskim charakterem.',
                'photos' => [
                    base_path('image/Bengale/Reproduktory/Luki/Luki.jpg'),
                ],
                'sort_order' => 11,
            ],
            'luna' => [
                'name' => 'Luna',
                'slug' => 'luna',
                'breed' => 'Kot Bengalski',
                'color' => 'Srebrzysty cętkowany (Silver Spotted)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Elegancka kotka hodowlana rasy Kot Bengalski o ubarwieniu Silver Spotted i domowym usposobieniu.',
                'description' => 'Luna jest kotką o wyrazistym, srebrzystym ubarwieniu z kontrastowym rysunkiem. Wychowana w sercu domu, wspaniale opiekuje się miotami.',
                'photos' => [
                    base_path('image/Bengale/Reproduktory/Luna/Luna .jpg'),
                ],
                'sort_order' => 12,
            ],
            'nel_bengal' => [
                'name' => 'Nel',
                'slug' => 'nel-bengalska',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy rozetowy (Brown Rosetted)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Trzecia wybitna kotka hodowlana rasy Kot Bengalski o pięknych konturowych rozetach.',
                'description' => 'Nel to nasza trzecia kotka hodowlana rasy Kot Bengalski. Zachwyca wyrazistym rysunkiem futra, nienaganną budową i wspaniałym instynktem macierzyńskim.',
                'photos' => [
                    base_path('image/Bengale/Reproduktory/Nel/763944468_1407023464607549_4687163816505510510_n.jpg'),
                ],
                'sort_order' => 13,
            ],

            // Brytyjczyki Parents
            'nel' => [
                'name' => 'Nel',
                'slug' => 'nel',
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieska (British Blue)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Pluszowa, dostojna kotka hodowlana rasy Kot Brytyjski o klasycznej niebieskiej okrywie i miedzianych oczach.',
                'description' => 'Nel reprezentuje tradycyjny typ brytyjski o gęstym, pluszowym futrze i okrągłej głowie. Spokojna, czuła i bezproblemowa w domowym życiu.',
                'photos' => [
                    base_path('image/Brytyjczyki/Reproduktory/Nel/Nel.jpg'),
                    base_path('image/Brytyjczyki/Reproduktory/Nel/Nel1.jpg'),
                ],
                'sort_order' => 13,
            ],
            'stas' => [
                'name' => 'Staś',
                'slug' => 'stas',
                'breed' => 'Kot Brytyjski',
                'color' => 'Kremowy (British Cream)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Potężny, niezwykle łagodny reproduktor brytyjski o unikalnym kremowym ubarwieniu.',
                'description' => 'Staś to nasz flagowy reproduktor rasy Kot Brytyjski. Zachwyca misiowatym wyglądem, zrównoważonym temperamentem oraz doskonałymi wynikami profilaktyki zdrowotnej.',
                'photos' => [
                    base_path('image/Brytyjczyki/Reproduktory/Stas/Stas.jpg'),
                    base_path('image/Brytyjczyki/Reproduktory/Stas/Stas1.jpg'),
                ],
                'sort_order' => 14,
            ],

            // Syjamskie Parents
            'baltazar' => [
                'name' => 'Baltazar',
                'slug' => 'baltazar',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Szlachetny reproduktor syjamski o smukłej sylwetce, ubarwieniu Seal Point i zniewalających błękitnych oczach.',
                'description' => 'Baltazar jest dumnym przedstawicielem linii syjamskiej. Charakteryzuje go ogromna przyjacielskość, inteligencja oraz błękitne spojrzenie.',
                'photos' => [
                    base_path('image/Syjamy/Reproduktory/Baltazar/Baltazar .jpg'),
                ],
                'sort_order' => 15,
            ],
            'koko' => [
                'name' => 'Koko',
                'slug' => 'koko',
                'breed' => 'Kot Syjamski',
                'color' => 'Blue Point',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'short_description' => 'Urocza, niezwykle wokalna i czuła kotka syjamska o delikatnych znaczeniach Blue Point.',
                'description' => 'Koko wprowadza mnóstwo radości do naszej hodowli. Jest kotką nienagannie socjalizowaną, zawsze szukającą kontaktu z człowiekiem.',
                'photos' => [
                    base_path('image/Syjamy/Reproduktory/Koko/Koko.jpg'),
                ],
                'sort_order' => 16,
            ],
            'nela' => [
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
                'photos' => [
                    base_path('image/Syjamy/Reproduktory/Nela/profilowe.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/700a8851-0a09-427f-9692-b14ffb9f2d39.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/a1a9a5d9-2be4-4ecd-816a-88485b5ff522.jfif'),
                    base_path('image/Syjamy/Reproduktory/Nela/b774b604-0440-4966-bc3f-582621045f8b.jfif'),
                ],
                'sort_order' => 17,
            ],
        ];

        // Create Parent Records & Attach Photos
        $parentModels = [];
        foreach ($parentsData as $key => $data) {
            $animal = Animal::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
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
            ]);

            $parentModels[$key] = $animal;

            $photoIndex = 0;
            foreach ($data['photos'] as $sourcePath) {
                $resolvedPath = $this->resolveSourceFile($sourcePath);
                if ($resolvedPath) {
                    $ext = strtolower(pathinfo($resolvedPath, PATHINFO_EXTENSION)) ?: 'jpg';
                    $targetFilename = "parent_{$data['slug']}_" . ($photoIndex + 1) . ".{$ext}";
                    $targetPath = storage_path("app/public/media/{$targetFilename}");

                    File::copy($resolvedPath, $targetPath);
                    @chmod($targetPath, 0644);

                    // Duplicate copy directly to public/storage/media for hosting environments without symlink support
                    $publicMediaDir = public_path('storage/media');
                    if (! File::isDirectory($publicMediaDir)) {
                        File::makeDirectory($publicMediaDir, 0755, true, true);
                        @chmod($publicMediaDir, 0755);
                    }
                    $pubTarget = $publicMediaDir . '/' . $targetFilename;
                    File::copy($resolvedPath, $pubTarget);
                    @chmod($pubTarget, 0644);

                    Media::create([
                        'disk' => 'public',
                        'directory' => 'media',
                        'filename' => $targetFilename,
                        'mime_type' => 'image/jpeg',
                        'size' => File::size($targetPath),
                        'title' => "{$data['name']} — {$data['breed']}",
                        'alt_text' => "{$data['name']} — {$data['breed']}",
                        'sort_order' => $photoIndex,
                        'is_featured' => ($photoIndex === 0),
                        'mediable_type' => Animal::class,
                        'mediable_id' => $animal->id,
                    ]);

                    $photoIndex++;
                }
            }
        }

        // ── 3. REAL KITTENS DATA ─────────────────────────────────────
        $kittensData = [
            // Bengale Kittens
            [
                'name' => 'Adam',
                'slug' => 'adam',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy cętkowany (Brown Spotted)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => now()->subMonths(3),
                'short_description' => 'Cudowny, ciekawy świata kocurek bengalski o kontraście ciemnych cętek. Gotowy do rezerwacji i odbioru.',
                'description' => 'Adam to mały wulkan energii o wyjątkowej urodzie. Od pierwszych dni socjalizowany z ludźmi, nauczony korzystania z kuwety i drapaka. Syn Belli i Lukiego.',
                'mother_id' => $parentModels['bella']->id,
                'father_id' => $parentModels['luki']->id,
                'photos' => [
                    base_path('image/Bengale/Kociaki/Adam/758883597_122141726607022267_5246948439232082347_n.jpg'),
                    base_path('image/Bengale/Kociaki/Adam/759011215_122141726655022267_4982179204181042357_n.jpg'),
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Bernard',
                'slug' => 'bernard',
                'breed' => 'Kot Bengalski',
                'color' => 'Złocisty rozetowy (Marbled / Spotted)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => now()->subMonths(3),
                'short_description' => 'Puszysty, mądry kocurek bengalski o pięknych oczach. Uwielbia zabawy i przebywanie na kolanach.',
                'description' => 'Bernard jest niezwykle czułym i mądrym kocurkiem z miotu po Lunie i Lukim. Posiada gęstą, aksamitną sierść i przyjacielski charakter.',
                'mother_id' => $parentModels['luna']->id,
                'father_id' => $parentModels['luki']->id,
                'photos' => [
                    base_path('image/Bengale/Kociaki/Bernard/762432910_2195423374652848_1816139222087952746_n.jpg'),
                    base_path('image/Bengale/Kociaki/Bernard/763944498_1706031534029289_3747133177292206269_n.jpg'),
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Beauty',
                'slug' => 'beauty',
                'breed' => 'Kot Bengalski',
                'color' => 'Brązowy cętkowany z połyskiem (Glitter Tabby)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => now()->subMonths(3),
                'short_description' => 'Śliczna, filigranowa koteczka bengalska o przepięknym błysku futerka i wesołym usposobieniu.',
                'description' => 'Beauty to prawdziwa perełka w naszej hodowli. Wyjątkowo fotogeniczna, otwarta na ludzi i inne zwierzęta. Córka Belli i Lukiego.',
                'mother_id' => $parentModels['bella']->id,
                'father_id' => $parentModels['luki']->id,
                'photos' => [
                    base_path('image/Bengale/Kociaki/Beuty/758543391_122141726337022267_8364555572634280011_n.jpg'),
                    base_path('image/Bengale/Kociaki/Beuty/758793588_122141726289022267_4719430306451005898_n.jpg'),
                    base_path('image/Bengale/Kociaki/Beuty/759002912_122141726421022267_9156566110733471392_n.jpg'),
                    base_path('image/Bengale/Kociaki/Beuty/759688512_122141726247022267_3570466272649213988_n.jpg'),
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Niko',
                'slug' => 'niko',
                'breed' => 'Kot Bengalski',
                'color' => 'Srebrzysty rozetowy (Silver Charcoal)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Reserved,
                'type' => AnimalType::Cat,
                'date_of_birth' => now()->subMonths(3),
                'short_description' => 'Zarezerwowany kocurek bengalski o zjawiskowych rysach i usposobieniu prawdziwego pieszczocha.',
                'description' => 'Niko znalazł już swoją wymarzoną rodzinę. Wyrośnie na pięknego, silnego kocurka po rodowodowych rodzicach Lunie i Lukim.',
                'mother_id' => $parentModels['luna']->id,
                'father_id' => $parentModels['luki']->id,
                'photos' => [
                    base_path('image/Bengale/Kociaki/Niko/760964589_122141989437022267_6532068221918144746_n.jpg'),
                    base_path('image/Bengale/Kociaki/Niko/761273312_122141989641022267_7367735594160025777_n.jpg'),
                    base_path('image/Bengale/Kociaki/Niko/761615335_122141989695022267_7082346391114312173_n.jpg'),
                ],
                'sort_order' => 4,
            ],

            // Syjamskie Kitten
            [
                'name' => 'Syjamek',
                'slug' => 'syjamek',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => now()->subMonths(2),
                'short_description' => 'Słodki, niebieskooki kocurek syjamski o przepięknym umaszczeniu Seal Point. Bardzo kontaktowy.',
                'description' => 'Syjamek to uroczy maluch o głębokich szafirowych oczach i wielkim sercu. Wychowywany w domowych warunkach, syn Koko i Baltazara.',
                'mother_id' => $parentModels['koko']->id,
                'father_id' => $parentModels['baltazar']->id,
                'photos' => [
                    base_path('image/Syjamy/Kociaki/Syjamek/762590707_905482929293825_7809244338165134928_n.jpg'),
                    base_path('image/Syjamy/Kociaki/Syjamek/763701068_2125062465032935_1950926511584394964_n.jpg'),
                ],
                'sort_order' => 5,
            ],
        ];

        // Create Kitten Records & Attach Photos
        foreach ($kittensData as $kData) {
            $kitten = Animal::create([
                'name' => $kData['name'],
                'slug' => $kData['slug'],
                'breed' => $kData['breed'],
                'color' => $kData['color'],
                'gender' => $kData['gender'],
                'status' => $kData['status'],
                'type' => $kData['type'],
                'date_of_birth' => $kData['date_of_birth'],
                'short_description' => $kData['short_description'],
                'description' => $kData['description'],
                'mother_id' => $kData['mother_id'],
                'father_id' => $kData['father_id'],
                'sort_order' => $kData['sort_order'],
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $photoIndex = 0;
            foreach ($kData['photos'] as $sourcePath) {
                $resolvedPath = $this->resolveSourceFile($sourcePath);
                if ($resolvedPath) {
                    $ext = strtolower(pathinfo($resolvedPath, PATHINFO_EXTENSION)) ?: 'jpg';
                    $targetFilename = "kitten_{$kData['slug']}_" . ($photoIndex + 1) . ".{$ext}";
                    $targetPath = storage_path("app/public/media/{$targetFilename}");

                    File::copy($resolvedPath, $targetPath);
                    @chmod($targetPath, 0644);

                    // Duplicate copy directly to public/storage/media for hosting environments without symlink support
                    $publicMediaDir = public_path('storage/media');
                    if (! File::isDirectory($publicMediaDir)) {
                        File::makeDirectory($publicMediaDir, 0755, true, true);
                        @chmod($publicMediaDir, 0755);
                    }
                    $pubTarget = $publicMediaDir . '/' . $targetFilename;
                    File::copy($resolvedPath, $pubTarget);
                    @chmod($pubTarget, 0644);

                    Media::create([
                        'disk' => 'public',
                        'directory' => 'media',
                        'filename' => $targetFilename,
                        'mime_type' => 'image/jpeg',
                        'size' => File::size($targetPath),
                        'title' => "{$kData['name']} — {$kData['breed']}",
                        'alt_text' => "{$kData['name']} — {$kData['breed']} ({$kData['color']})",
                        'sort_order' => $photoIndex,
                        'is_featured' => ($photoIndex === 0),
                        'mediable_type' => Animal::class,
                        'mediable_id' => $kitten->id,
                    ]);

                    $photoIndex++;
                }
            }
        }
    }

    /**
     * Resolves source file case-insensitively, handles trailing spaces, and folder typos on Linux filesystems.
     */
    protected function resolveSourceFile(string $sourcePath): ?string
    {
        if (File::exists($sourcePath)) {
            return $sourcePath;
        }

        // Try removing space before extension (e.g. 'Bella .jpg' -> 'Bella.jpg')
        $trimmed = preg_replace('/\s+\./', '.', $sourcePath);
        if ($trimmed && File::exists($trimmed)) {
            return $trimmed;
        }

        $targetFilename = strtolower(basename($sourcePath));
        $targetFilenameTrimmed = strtolower(basename($trimmed ?? $sourcePath));

        // Global recursive scan in base_path('image') for matching filename
        $baseImageDir = base_path('image');
        if (File::isDirectory($baseImageDir)) {
            $allFiles = File::allFiles($baseImageDir);
            foreach ($allFiles as $f) {
                $fName = strtolower($f->getFilename());
                if ($fName === $targetFilename || $fName === $targetFilenameTrimmed) {
                    return $f->getPathname();
                }
            }
        }

        return null;
    }
}
