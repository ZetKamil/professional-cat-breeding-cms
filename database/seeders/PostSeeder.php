<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Artykuły redakcyjne dla hodowli kotów rasowych ZetKamil.
     */
    public function run(): void
    {
        $users = User::query()->pluck('id')->all();
        $categories = Category::query()->pluck('id')->all();

        if (empty($users) || empty($categories)) {
            return;
        }

        // Czyszczenie starej bazy postów (idempotentność)
        DB::table('category_post')->delete();
        DB::table('posts')->delete();

        $posts = [
            [
                'title' => 'Dlaczego badania genetyczne HCM i PKD są kluczowe przy wyborze kota rasowego',
                'excerpt' => 'Zdrowie jest absolutnym fundamentem naszej filozofii hodowlanej. Dowiedz się, dlaczego regularne badania echokardiograficzne i genetyczne rodziców gwarantują długie lata życia kocięcia.',
                'body' => "W hodowli ZetKamil nie uznajemy kompromisów w kwestii zdrowia. Każda kotka i każdy kocur hodowlany przechodzą szczegółowe badania genetyczne w kierunku kardiomiopatii przerostowej (HCM) oraz wielotorbielowatości nerek (PKD).\n\nBadania te wykonujemy w certyfikowanych laboratoriach weterynaryjnych w Europie. Dzięki temu nasi przyszli opiekunowie otrzymują pełną gwarancję, że ich nowe kocię jest wolne od najgroźniejszych chorób genetycznych spotykanych u kotów rasowych.\n\nPrzed opuszczeniem hodowli każdy maluch przechodzi również kompletny przegląd weterynaryjny, odrobaczenia oraz szczepienia najnowszej generacji szczepionkami.",
                'is_published' => true,
                'category_names' => ['Zdrowie i Genetyka'],
            ],
            [
                'title' => 'Wyprawka dla Kociaka – Kompletny przewodnik po akcesoriach premium',
                'excerpt' => 'Przygotowujesz dom na przybycie nowego domownika? Sprawdź naszą autorską listę polecanych drapaków, żwirków, kuwet oraz zabawek interaktywnych.',
                'body' => "Pierwsze dni kocięcia w nowym domu są niezwykle ważne dla jego poczucia bezpieczeństwa. Dlatego stworzyliśmy szczegółowy przewodnik, który pomoże Ci skompletować wyprawkę najwyższej jakości.\n\nRekomendujemy solidne drapaki z naturalnego syzalu, stabilne kuwety o odpowiednich wymiarach oraz sprawdzone podłoża bentonitowe i drewniane, z których kocięta korzystały w naszej hodowli.\n\nWażnym elementem są również miski ceramiczne lub ze stali nierdzewnej – zapobiegają one alergii kontaktowej i są łatwe w utrzymaniu higieny.",
                'is_published' => true,
                'category_names' => ['Wyprawka i Pielęgnacja', 'Socjalizacja i Wychowanie'],
            ],
            [
                'title' => 'Kot Bengalski w domowym zaciszu – temperament, energia i wyjątkowo piękna rozeta',
                'excerpt' => 'Dziki wygląd lamparda połączony z łagodnym i wysoce inteligentnym charakterem domowego towarzysza. Poznaj bliżej naturę kotów bengalskich.',
                'body' => "Koty bengalskie to niezwykłe połączenie hipnotyzującego, dzikiego futra z rozetami i niesamowitego przywiązania do człowieka. W naszej hodowli selekcjonujemy linie o wybitnym kontraście barw oraz jedwabistej strukturze sierści.\n\nBengale są kotami bardzo aktywnymi, inteligentnymi i ciekawskimi. Uwielbiają zabawy interaktywne, wspinaczkę, a nierzadko również... zabawę w wodzie! Doskonale dogadują się z dziećmi oraz innymi zwierzętami domowymi.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy'],
            ],
            [
                'title' => 'Arystokrata wśród kotów – dlaczego Brytyjczyk to idealne towarzystwo dla rodziny',
                'excerpt' => 'Pluszowe futro, bursztynowe spojrzenie i stoicki spokój. Koty brytyjskie krótkowłose to kwintesencja domowej elegancji i harmonii.',
                'body' => "Kot Brytyjski Krótkowłosy (British Shorthair) to jedna z najbardziej cenionych ras na świecie. Ich zrównoważony, spokojny temperament sprawia, że są idealnymi przyjaciółmi zarówno dla rodzin z dziećmi, jak i osób ceniących ciszę i spokój.\n\nW ZetKamil kładziemy szczególny nacisk na prawidłową budowę anatomiczną, gęsty podszerstek oraz głębokie, miedziane spojrzenie naszych brytyjczyków.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy', 'Socjalizacja i Wychowanie'],
            ],
            [
                'title' => 'Tajemnicze błękitne spojrzenie – niezwykły charakter Kotów Syjamskich',
                'excerpt' => 'Ekspresyjne, niezwykle oddane opiekunowi i komunikatywne. Koty syjamskie wnoszą do domu wyjątkową energię i miłość.',
                'body' => "Koty syjamskie zachwycają szafirowym kolorem oczu oraz eleganckimi znaczeniami typu point. To rasa dla osób, które szukają kota prawdziwie obecnego w życiu domowym – syjamy towarzyszą opiekunowi w każdej czynności i chętnie 'rozmawiają' swoim melodyjnym głosem.\n\nNasze syjamy są socjalizowane od pierwszych dni życia w salonie, dzięki czemu cechuje je wyjątkowa otwartość i ufność.",
                'is_published' => true,
                'category_names' => ['Odmiany i Rasy'],
            ],
            [
                'title' => 'Dieta BARF i żywienie holistyczne w naszej hodowli – fundament zdrowia na lata',
                'excerpt' => 'Prawidłowe żywienie to inwestycja w długowieczność kota. Dowiedz się, dlaczego karmy wysokomięsne i dieta surowa są optymalnym wyborem.',
                'body' => "Koty są bezwzględnymi mięsożercami. W hodowli ZetKamil opieramy żywienie naszych kotów na biologicznie odpowiedniej diecie surowej (BARF) oraz wyselekcjonowanych karmach mokrych klasy human-grade, wolnych od zbóż i sztucznych dodatków.\n\nPrawidłowy bilans tauryny, kwasów omega-3 oraz aminokwasów wpływa bezpośrednio na blask futra, witalność oraz odporność naszych kociąt.",
                'is_published' => true,
                'category_names' => ['Żywienie Holistyczne', 'Zdrowie i Genetyka'],
            ],
        ];

        foreach ($posts as $item) {
            $userId = $users[0]; // główny administrator / hodowca

            $post = Post::create([
                'user_id' => $userId,
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'excerpt' => $item['excerpt'],
                'body' => $item['body'],
                'is_published' => $item['is_published'],
                'published_at' => $item['is_published'] ? now()->subDays(rand(1, 14)) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoryIds = Category::query()
                ->whereIn('name', $item['category_names'])
                ->pluck('id')
                ->all();

            $post->categories()->sync($categoryIds);
        }
    }
}
