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

        // 1. Candy (Kotka, BEN n 24/32)
        if (! Animal::where('slug', 'candy')->exists()) {
            $candy = Animal::create([
                'name' => 'Candy',
                'slug' => 'candy',
                'breed' => 'Kot Bengalski',
                'color' => 'BEN n 24/32 (Rozety na Złocie)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Cudowna, pełna wdzięku kotka bengalska o ciepłym złocistym odcieniu, wyraźnych rozetach i słodkim usposobieniu. Córka Luny i Lukiego. Dostępna do rezerwacji.',
                'description' => 'Candy to urocza, pełna wdzięku koteczka bengalska o niezwykłym kontraście umaszczenia BEN n 24/32 i jedwabiście gładkim futerku o złotym blasku. Od pierwszych tygodni wyróżnia się łagodnym, przytulaśnym charakterem i ogromną ciekawością świata. Wychowywana w domowym cieple, pod stałą opieką weterynaryjną. Córka naszej utytułowanej Luny i dostojnego kocura Lukiego. Urodzona 21 czerwca 2026 r., posiada rodowód SHiOZ ZOOLANDIA, komplet szczepień i chip. Gotowa do rezerwacji do nowego, kochającego domu.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 1,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $candy,
                base_path('image/Bengale/Kociaki/Candy/profilowe.jfif'),
                'kitten_candy_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $candy,
                base_path('image/Bengale/Kociaki/Candy/520ab1ac-369f-436c-9d65-9651f48a536a.jfif'),
                'kitten_candy_2.jpg',
                false,
                1
            );
            $this->attachPhoto(
                $candy,
                base_path('image/Bengale/Kociaki/Candy/b2230f55-8741-4444-a955-d3febef39e38.jfif'),
                'kitten_candy_3.jpg',
                false,
                2
            );
        }

        // 2. Carlos (Kocur, BEN n 24/32)
        if (! Animal::where('slug', 'carlos')->exists()) {
            $carlos = Animal::create([
                'name' => 'Carlos',
                'slug' => 'carlos',
                'breed' => 'Kot Bengalski',
                'color' => 'BEN n 24/32 (Rozety na Złocie)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Mocny, odważny kocur bengalski o spektakularnym rysunku rozet i bystrym spojrzeniu. Syn Luny i Lukiego. Dostępny do rezerwacji.',
                'description' => 'Carlos to silny, doskonale zbudowany kocurek bengalski o wyrazistym umaszczeniu BEN n 24/32 z pięknie ukształtowanymi, ciemnymi rozetami na ciepłym tle. Posiada wspaniały, zrównoważony temperament — jest niezwykle kontaktowy, odważny i chętny do wspólnych zabaw. Urodzony 21 czerwca 2026 r. ze skojarzenia Luny i Lukiego. Przy odbiorze otrzyma wielopokoleniowy rodowód SHiOZ ZOOLANDIA, książeczkę zdrowia (szczepienia, odrobaczenia) oraz mikrochip.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 2,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $carlos,
                base_path('image/Bengale/Kociaki/Carlos/f9fbbdea-82c4-46d2-90e8-786ec3c6d5cb.jfif'),
                'kitten_carlos_1.jpg',
                true,
                0
            );
            $this->attachPhoto(
                $carlos,
                base_path('image/Bengale/Kociaki/Carlos/b19f5b88-aefa-4ca4-b71a-b1e3903a3c4f.jfif'),
                'kitten_carlos_2.jpg',
                false,
                1
            );
        }

        // 3. Carmen (Kotka, BEN n 24/32)
        if (! Animal::where('slug', 'carmen')->exists()) {
            $carmen = Animal::create([
                'name' => 'Carmen',
                'slug' => 'carmen',
                'breed' => 'Kot Bengalski',
                'color' => 'BEN n 24/32 (Rozety na Złocie)',
                'gender' => AnimalGender::Female,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Zjawiskowa kotka bengalska o szlachetnych liniach, głębokim kontraście i figlarnym spojrzeniu. Córka Luny i Lukiego. Dostępna do rezerwacji.',
                'description' => 'Carmen to pełna gracji kotka bengalska o intensywnym, kontrastowym rysunku rozet (BEN n 24/32) i wspaniałej ekspresji. Odznacza się bystrym umysłem, wesołym usposobieniem i zamiłowaniem do pieszczot. Doskonale zsocjalizowana z ludźmi w warunkach domowych. Córka Luny i Lukiego, urodzona 21 czerwca 2026 r. Wraz z wyprawką otrzyma rodowód SHiOZ ZOOLANDIA, mikrochip oraz udokumentowane zabiegi profilaktyczne. Dostępna do rezerwacji.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 3,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $carmen,
                base_path('image/Bengale/Kociaki/Carmen/80e507c5-ff42-4951-9c7d-c405f9ba348f.jfif'),
                'kitten_carmen_1.jpg',
                true,
                0
            );
        }

        // 4. Cyprian (Kocur, BEN n 24/32)
        if (! Animal::where('slug', 'cyprian')->exists()) {
            $cyprian = Animal::create([
                'name' => 'Cyprian',
                'slug' => 'cyprian',
                'breed' => 'Kot Bengalski',
                'color' => 'BEN n 24/32 (Rozety na Złocie)',
                'gender' => AnimalGender::Male,
                'status' => AnimalStatus::Available,
                'type' => AnimalType::Cat,
                'date_of_birth' => Carbon::create(2026, 6, 21),
                'short_description' => 'Dostojny, przyjacielski kocurek bengalski o harmonijnej budowie i wyrazistych rozetach. Syn Luny i Lukiego. Dostępny do rezerwacji.',
                'description' => 'Cyprian to wspaniały, ciekawski kocur bengalski o doskonałej anatomii, gęstym, lśniącym futrze i malowniczych rozetach w kodzie BEN n 24/32. Jest ufny, bardzo przywiązany do człowieka i uwielbia towarzyszyć w codziennych domowych czynnościach. Urodzony 21 czerwca 2026 r. z doskonałego połączenia genetycznego Luny i Lukiego. Posiada certyfikowany rodowód SHiOZ ZOOLANDIA, chip oraz aktualne szczepienia i odrobaczenia.',
                'mother_id' => $luna?->id,
                'father_id' => $luki?->id,
                'sort_order' => 4,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ]);

            $this->attachPhoto(
                $cyprian,
                base_path('image/Bengale/Kociaki/Cyprian/6ec20d8c-15ea-4b98-93fe-a8591d9e69d7.jfif'),
                'kitten_cyprian_1.jpg',
                true,
                0
            );
        }
    }

    public function down(): void
    {
        $kittens = Animal::whereIn('slug', ['candy', 'carlos', 'carmen', 'cyprian'])->get();
        foreach ($kittens as $kitten) {
            foreach ($kitten->gallery as $media) {
                @unlink(storage_path('app/public/media/' . $media->filename));
                @unlink(public_path('storage/media/' . $media->filename));
                $media->delete();
            }
            $kitten->delete();
        }
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

        Media::create([
            'disk' => 'public',
            'directory' => 'media',
            'filename' => $targetFilename,
            'mime_type' => 'image/jpeg',
            'size' => File::size($targetStorage),
            'title' => "{$animal->name} — {$animal->breed}",
            'alt_text' => "{$animal->name} — {$animal->breed} ({$animal->color})",
            'sort_order' => $sortOrder,
            'is_featured' => $isFeatured,
            'mediable_type' => Animal::class,
            'mediable_id' => $animal->id,
        ]);
    }
};
