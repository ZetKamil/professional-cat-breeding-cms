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
        // ─────────────────────────────────────────────────────────────
        // 1. REPRODUKTORY SYJAMSKIE (Rodzice miotu syjamskiego)
        // ─────────────────────────────────────────────────────────────
        $baltazar = Animal::where('breed', 'Kot Syjamski')
            ->where(fn ($q) => $q->where('slug', 'baltazar')->orWhere('name', 'Baltazar'))
            ->first();
        if (! $baltazar) {
            $baltazar = Animal::create([
                'name' => 'Baltazar',
                'slug' => 'baltazar',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2020, 11, 16),
                'short_description' => 'Szlachetny reproduktor syjamski o smukłej sylwetce, ubarwieniu Seal Point i zniewalających błękitnych oczach.',
                'description' => 'Baltazar to nasz flagowy reproduktor rasy Kot Syjamski o klasycznym ubarwieniu Seal Point. Charakteryzuje go ogromna przyjacielskość, wysoki poziom inteligencji oraz głębokie, szafirowe spojrzenie. Rodowód SHiOZ 12371R/2021, mikrochip 616049000054127, urodzony 16.11.2020 r.',
                'sort_order' => 41,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $baltazar,
                base_path('image/Syjamy/Reproduktory/Baltazar/Baltazar .jpg'),
                'parent_baltazar_1.jpg',
                true,
                0
            );
        }

        $koko = Animal::where('breed', 'Kot Syjamski')
            ->where(fn ($q) => $q->where('slug', 'koko')->orWhere('name', 'Koko'))
            ->first();
        if (! $koko) {
            $koko = Animal::create([
                'name' => 'Koko',
                'slug' => 'koko',
                'breed' => 'Kot Syjamski',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2020, 10, 5),
                'short_description' => 'Urocza, niezwykle opiekuńcza i czuła kotka hodowlana rasy Kot Syjamski o szlachetnym umaszczeniu Seal Point.',
                'description' => 'Koko to wspaniała kotka hodowlana rasy Kot Syjamski. Wzorcowa matka miotów syjamskich, bezgranicznie oddana człowiekowi, socjalizowana w sercu domu. Rodowód SHiOZ 3311RW/2021, mikrochip 61604901758702, urodzona 05.10.2020 r.',
                'sort_order' => 43,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $koko,
                base_path('image/Syjamy/Reproduktory/Koko/Koko.jpg'),
                'parent_koko_1.jpg',
                true,
                0
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 2. REPRODUKTOR BENGALSKI: BARS (Nel jest nr 54, więc Bars otrzymuje nr 55)
        // ─────────────────────────────────────────────────────────────
        $bars = Animal::where('slug', 'bars')->orWhere('name', 'Bars')->first();
        if (! $bars) {
            $bars = Animal::create([
                'name' => 'Bars',
                'slug' => 'bars',
                'breed' => 'Kot Bengalski',
                'color' => 'Srebrzysty cętkowany z rozetami (BEN ns 24)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2024, 5, 9),
                'short_description' => 'Dostojny, muskularny reproduktor rasy Kot Bengalski o zjawiskowym srebrzystym umaszczeniu (BEN ns 24) i wyrazistych rozetach.',
                'description' => 'Bars (A-Bars) to nasz wybitny reproduktor rasy Kot Bengalski o rzadkim i pożądanym ubarwieniu srebrzystym cętkowanym z kontrastowymi rozetami (kod EMS: BEN ns 24 – Black Silver Spotted Tabby). Charakteryzuje się harmonijną budową anatomiczną, silnym kośćcem oraz jedwabiście gładką sierścią o naturalnym blasku.

Posiada zrównoważony, łagodny i przyjacielski temperament. Jest zarejestrowanym reproduktorem w SHiOZ ZOOLANDIA (rodowód (PL) FPL LO 354902, mikrochip nr 900263006037836). Urodzony 9 maja 2024 r. Stanowi dumę i filar naszego programu hodowlanego kotów bengalskich.',
                'sort_order' => 55,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Profilowe jako główne zdjęcie
            $this->attachPhoto(
                $bars,
                base_path('image/Bengale/Reproduktory/Bars/profilowe.jpg'),
                'parent_bars_1.jpg',
                true,
                0
            );
            // Zdjęcia do galerii
            $this->attachPhoto(
                $bars,
                base_path('image/Bengale/Reproduktory/Bars/cd19243a-7a06-486c-a03d-1241346798dd.jpg'),
                'parent_bars_2.jpg',
                false,
                1
            );
            $this->attachPhoto(
                $bars,
                base_path('image/Bengale/Reproduktory/Bars/ce8bc221-1f71-41d8-a3b2-b5c17bb0b2a2.jpg'),
                'parent_bars_3.jpg',
                false,
                2
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 3. 4 MAŁE SYJAMY (Miot D, data ur. 13.09.2026 r. - sort_order 7–10)
        // ─────────────────────────────────────────────────────────────
        $siameseKittens = [
            [
                'name' => 'Dafne',
                'slug' => 'dafne',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'sort_order' => 7,
                'short_description' => 'Urocza koteczka syjamska z miotu D o klasycznym ubarwieniu Seal Point i głębokich, błękitnych oczach.',
                'description' => 'Dafne to przepiękna kotka syjamska z miotu D po utytułowanych rodzicach Koko i Baltazarze. Cechuje ją łagodne usposobienie, wesoła natura i ogromna chęć do pieszczot. Wychowywana w domowym cieple, w pełni nauczona czystości. Urodzona 13 września 2026 r. (SHiOZ ZOOLANDIA). Gotowa do rezerwacji.',
                'photo' => base_path('image/Syjamy/Kociaki/Dafne/7b7099e2-d8f8-4e8b-8285-539525e1104a.jpg'),
                'target' => 'kitten_dafne_1.jpg',
            ],
            [
                'name' => 'Daisy',
                'slug' => 'daisy',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'sort_order' => 8,
                'short_description' => 'Słodka, pełna wdzięku kotka syjamska z miotu D o umaszczeniu Seal Point i wielkim serduszku.',
                'description' => 'Daisy to pełna uroku koteczka syjamska o klasycznym wybarwieniu Seal Point. Bardzo kontaktowa, ciekawska i uwielbiająca spanie na kolanach. Córka Koko i Baltazara, urodzona 13 września 2026 r. Zarejestrowana w SHiOZ ZOOLANDIA. Gotowa do rezerwacji.',
                'photo' => base_path('image/Syjamy/Kociaki/Daisy/7b7099e2-d8f8-4e8b-8285-539525e1104a.jpg'),
                'target' => 'kitten_daisy_1.jpg',
            ],
            [
                'name' => 'Dakota',
                'slug' => 'dakota',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'sort_order' => 9,
                'short_description' => 'Bystra i energiczna kotka syjamska o pięknej sylwetce i szafirowym spojrzeniu.',
                'description' => 'Dakota wyróżnia się bystrym spojrzeniem, harmonijną budową i wspaniałym temperamentem. Od pierwszych tygodni socjalizowana z ludźmi i przyzwyczajana do codziennych odgłosów domu. Córka Koko i Baltazara, urodzona 13 września 2026 r. Gotowa do rezerwacji.',
                'photo' => base_path('image/Syjamy/Kociaki/Dakota/fc2b3579-56d0-484e-9de2-1c77736527fe.jpg'),
                'target' => 'kitten_dakota_1.jpg',
            ],
            [
                'name' => 'Diana',
                'slug' => 'diana',
                'color' => 'Seal Point',
                'gender' => AnimalGender::Female,
                'sort_order' => 10,
                'short_description' => 'Delikatna i czuła kotka syjamska o arystokratycznym spojrzeniu i wspaniałym kontraście znaczeń.',
                'description' => 'Diana to mała arystokratka o łagodnym, przyjaznym charakterze. Szuka bliskiego kontaktu z opiekunem, głośno mruczy i zachwyca błękitnymi oczami. Córka Koko i Baltazara, urodzona 13 września 2026 r. Gotowa do rezerwacji.',
                'photo' => base_path('image/Syjamy/Kociaki/Diana/fc2b3579-56d0-484e-9de2-1c77736527fe.jpg'),
                'target' => 'kitten_diana_1.jpg',
            ],
        ];

        foreach ($siameseKittens as $kData) {
            $kitten = Animal::where('slug', $kData['slug'])->first();
            if (! $kitten) {
                $kitten = Animal::create([
                    'name' => $kData['name'],
                    'slug' => $kData['slug'],
                    'breed' => 'Kot Syjamski',
                    'color' => $kData['color'],
                    'gender' => $kData['gender'],
                    'status' => AnimalStatus::Available,
                    'type' => AnimalType::Cat,
                    'date_of_birth' => Carbon::create(2026, 9, 13),
                    'short_description' => $kData['short_description'],
                    'description' => $kData['description'],
                    'mother_id' => $koko?->id,
                    'father_id' => $baltazar?->id,
                    'sort_order' => $kData['sort_order'],
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now(),
                ]);

                $this->attachPhoto(
                    $kitten,
                    $kData['photo'],
                    $kData['target'],
                    true,
                    0
                );
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 4. KOTKA BRYTYJSKA NEL (Matka miotu brytyjskiego - na serwerze ma nr 32)
        // ─────────────────────────────────────────────────────────────
        $nel = Animal::where('breed', 'Kot Brytyjski')
            ->where(fn ($q) => $q->where('slug', 'nel')->orWhere('name', 'Nel'))
            ->first();
        if (! $nel) {
            $nel = Animal::create([
                'name' => 'Nel',
                'slug' => 'nel',
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieska (British Blue / BSH a)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2025, 10, 31),
                'short_description' => 'Dostojna kotka hodowlana rasy Kot Brytyjski o klasycznej niebieskiej okrywie i pięknej okrągłej głowie.',
                'description' => 'Nel to nasza kluczowa kotka hodowlana rasy Kot Brytyjski Krótkowłosy (BSH a). Wzorcowa budowa, pluszowe futro i zrównoważony domowy charakter. Posiada certyfikaty SHiOZ 3331R/2026, mikrochip 900263004298012. Troskliwa matka miotu A.',
                'sort_order' => 32,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $nel,
                base_path('image/Brytyjczyki/Reproduktory/Nel/Nel.jpg'),
                'parent_nel_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $nel,
                base_path('image/Brytyjczyki/Reproduktory/Nel/Nel1.jpg'),
                'parent_nel_2.jpg',
                false,
                1
            );
        }

        // ─────────────────────────────────────────────────────────────
        // 5. ATOS (Brytyjczyk, duży - profil bez zdjęć wg polecenia; Staś 31, Nel 32, więc Atos ma 33)
        // ─────────────────────────────────────────────────────────────
        $atos = Animal::where('slug', 'atos')->orWhere('name', 'Atos')->first();
        if (! $atos) {
            Animal::create([
                'name' => 'Atos',
                'slug' => 'atos',
                'breed' => 'Kot Brytyjski',
                'color' => 'Colourpoint niebieski (Blue Point / BLH a 33, brytyjski długowłosy)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Breeding,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 9, 9),
                'mother_id' => $nel?->id,
                'short_description' => 'Dostojny kocur rasy Kot Brytyjski Długowłosy o szlachetnym umaszczeniu colourpoint niebieski (BLH a 33).',
                'description' => 'Atos to wyjątkowy kocur rasy Kot Brytyjski Długowłosy (British Longhair – BLH a 33) o jasnej szacie ze szlachetnymi niebieskimi znaczeniami i szafirowym spojrzeniu. Charakteryzuje się mocną, masywną budową ciała, gęstym podszerstkiem i oazą spokoju w usposobieniu. Syn naszej kotki Nel. Zarejestrowany w SHiOZ ZOOLANDIA.',
                'sort_order' => 33,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);
            // Zdjęcia Atosa nie są dodawane (zgodnie z wytyczną: karta miotu wykluczona, a właściwe zdjęcia zostaną dodane w przyszłości)
        }

        // ─────────────────────────────────────────────────────────────
        // 6. 3 KOCIAKI BRYTYJSKIE (Ada, Alma, Atena - Miot A, ur. 09.09.2026 r. - sort_order 12–14)
        // ─────────────────────────────────────────────────────────────
        // Ada
        $ada = Animal::where('slug', 'ada')->orWhere('name', 'Ada')->first();
        if (! $ada) {
            $ada = Animal::create([
                'name' => 'Ada',
                'slug' => 'ada',
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieski (Blue / BSH a, brytyjski krótkowłosy)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 9, 9),
                'mother_id' => $nel?->id,
                'short_description' => 'Pluszowa koteczka brytyjska o klasycznym niebieskim umaszczeniu (BSH a) i czułym serduszku.',
                'description' => 'Ada to śliczna koteczka rasy Kot Brytyjski Krótkowłosy o tradycyjnym niebieskim ubarwieniu (BSH a). Odznacza się gęstym, aksamitnym futrem i kochanym, spokojnym charakterem. Córka Nel, urodzona 9 września 2026 r. (SHiOZ ZOOLANDIA). Dostępna do rezerwacji.',
                'sort_order' => 12,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $ada,
                base_path('image/Brytyjczyki/Kociaki/Ada/profilowe.jpg'),
                'kitten_ada_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $ada,
                base_path('image/Brytyjczyki/Kociaki/Ada/834465222_122149501701022267_8004309469989776849_n.jpg'),
                'kitten_ada_2.jpg',
                false,
                1
            );
        }

        // Alma
        $alma = Animal::where('slug', 'alma')->orWhere('name', 'Alma')->first();
        if (! $alma) {
            $alma = Animal::create([
                'name' => 'Alma',
                'slug' => 'alma',
                'breed' => 'Kot Brytyjski',
                'color' => 'Colourpoint niebieski (Blue Point / BLH a 33, brytyjski długowłosy)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 9, 9),
                'mother_id' => $nel?->id,
                'short_description' => 'Puchata koteczka brytyjska długowłosa o unikalnym umaszczeniu colourpoint niebieski (BLH a 33).',
                'description' => 'Alma to prawdziwa perełka w naszej hodowli – kotka rasy Kot Brytyjski Długowłosy o zjawiskowym umaszczeniu colourpoint niebieski (BLH a 33) i błękitnych oczach. Jej puchata, miękka szata oraz łagodne, urocze spojrzenie natychmiast skradają serce. Córka Nel, urodzona 9 września 2026 r. Dostępna do rezerwacji.',
                'sort_order' => 13,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $alma,
                base_path('image/Brytyjczyki/Kociaki/Alma/profilowe.jpg'),
                'kitten_alma_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $alma,
                base_path('image/Brytyjczyki/Kociaki/Alma/833977717_122149501923022267_3301714185762999095_n.jpg'),
                'kitten_alma_2.jpg',
                false,
                1
            );
        }

        // Atena
        $atena = Animal::where('slug', 'atena')->orWhere('name', 'Atena')->first();
        if (! $atena) {
            $atena = Animal::create([
                'name' => 'Atena',
                'slug' => 'atena',
                'breed' => 'Kot Brytyjski',
                'color' => 'Niebieski (Blue / BSH a, brytyjski krótkowłosy)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 9, 9),
                'mother_id' => $nel?->id,
                'short_description' => 'Ciekawska i wesoła kotka brytyjska krótkowłosa o pluszowej niebieskiej okrywie (BSH a).',
                'description' => 'Atena to energiczna, ciekawa świata koteczka brytyjska krótkowłosa (BSH a) o aksamitnym, sprężystym futerku. Uwielbia zabawy, towarzystwo człowieka i innych zwierząt. Córka Nel, urodzona 9 września 2026 r. Posiada dokumentację SHiOZ ZOOLANDIA. Dostępna do rezerwacji.',
                'sort_order' => 14,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $atena,
                base_path('image/Brytyjczyki/Kociaki/Atena/profilowe.jpg'),
                'kitten_atena_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $atena,
                base_path('image/Brytyjczyki/Kociaki/Atena/833149368_122149501659022267_8498765482473004629_n.jpg'),
                'kitten_atena_2.jpg',
                false,
                1
            );
            $this->attachPhoto(
                $atena,
                base_path('image/Brytyjczyki/Kociaki/Atena/833863551_122149501617022267_3070672115881667229_n.jpg'),
                'kitten_atena_3.jpg',
                false,
                2
            );
            $this->attachPhoto(
                $atena,
                base_path('image/Brytyjczyki/Kociaki/Atena/sadaadsa.jpg'),
                'kitten_atena_4.jpg',
                false,
                3
            );
        }
    }

    public function down(): void
    {
        $slugs = ['bars', 'dafne', 'daisy', 'dakota', 'diana', 'ada', 'alma', 'atena', 'atos'];
        Animal::whereIn('slug', $slugs)->delete();
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
                'sort_order' => $sortOrder,
                'is_featured' => $isFeatured,
                'mediable_type' => Animal::class,
                'mediable_id' => $animal->id,
            ]
        );
    }
};
