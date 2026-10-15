<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Enums\AnimalStatus;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocalSeoController extends Controller
{
    /**
     * City configurations for Programmatic Local SEO landing pages.
     */
    protected array $cities = [
        'warszawa' => [
            'slug' => 'hodowla-kotow-warszawa',
            'name' => 'Warszawa',
            'name_locative' => 'Warszawie',
            'name_genitive' => 'Warszawy',
            'region' => 'województwo mazowieckie',
            'distance_time' => '~80–90 min',
            'route_desc' => 'trasa drogą ekspresową S7 / DK62 przez Nowy Dwór Mazowiecki i Wyszogród lub autostradą A2 do DK50/DK60',
            'travel_tips' => 'Dojazd z Warszawy (Bielany, Bemowo, Ochota) zajmuje średnio 80–90 minut. Na podróż powrotną przygotowujemy dla maluszka kocyk z zapachem mamy i rodzeństwa, dzięki czemu kociak zazwyczaj spokojnie przesypia drogę do stolicy.',
            'local_context' => 'Dla wielu przyszłych opiekunów z Warszawy wizyta w Sikorzu to okazja do zobaczenia prawdziwych warunków domowych, w jakich maluch dorastał w otoczeniu Brudzeńskiego Parku Krajobrazowego, z dala od wielkomiejskiego hałasu.',
            'meta_title' => 'Hodowla Kotów Rasowych Warszawa • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Szukasz rasowego kota w Warszawie? Poznaj domową hodowlę z Mazowieckiej Szwajcarii (Sikórz k. Płocka). Sprawdź kocięta na żywo przez wideo-wizytę na WhatsApp!',
            'faq' => [
                [
                    'q' => 'Czy muszę przyjeżdżać z Warszawy osobiście przed rezerwacją?',
                    'a' => 'Nie ma takiej konieczności. Zanim podejmiesz decyzję, łączymy się na niezobowiązującą wideo-wizytę przez WhatsApp lub Messenger. Możesz zobaczyć kociaka, jego mamę i warunki domowe ze swojej kanapy w Warszawie.',
                ],
                [
                    'q' => 'Jak wygląda odbiór i przygotowanie kociaka do podróży do Warszawy?',
                    'a' => 'Po wcześniejszym umówieniu terminu przyjeżdżasz do Sikorza. Przekazujemy rodowód SHiOZ ZOOLANDIA, książeczkę zdrowia, chip oraz kompletną wyprawkę z instrukcją żywieniową i adaptacyjną.',
                ],
            ],
        ],
        'lodz' => [
            'slug' => 'hodowla-kotow-lodz',
            'name' => 'Łódź',
            'name_locative' => 'Łodzi',
            'name_genitive' => 'Łodzi',
            'region' => 'województwo łódzkie',
            'distance_time' => '~1h 25 min',
            'route_desc' => 'wygodny dojazd autostradą A1 w kierunku północnym do węzła Kutno Północ, a następnie DK60 przez Gostynin do Sikorza k. Płocka',
            'travel_tips' => 'Trasa z Łodzi i aglomeracji łódzkiej jest prosta i bezpieczna. Po zjeździe z A1 w Kutnie do Sikorza pozostaje około 50 km płynnej trasy DK60. Przed wyjazdem podajemy dokładne wskazówki dojazdu.',
            'local_context' => 'Mieszkańcy Łodzi doceniają pełną transparentność zdrowotną – każde z naszych kociąt pochodzi od rodziców przebadanych kardiologicznie (echo serca HCM) oraz genetycznie (PKD, PRA-b, PK-Def).',
            'meta_title' => 'Hodowla Kotów Rasowych Łódź • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Hodowla kotów rasowych dla mieszkańców Łodzi i regionu łódzkiego. Koty bengalskie, brytyjskie i syjamskie z rodowodem SHiOZ ZOOLANDIA. Umów wideo-wizytę na żywo!',
            'faq' => [
                [
                    'q' => 'Jak zorganizować transport kociaka do Łodzi?',
                    'a' => 'Najbardziej rekomendujemy odbiór osobisty w naszej hodowli w Sikorzu (~85 minut drogi z Łodzi). Umożliwia to osobiste poznanie hodowcy, odbiór wyprawki i bezpośredni instruktaż pielęgnacyjny.',
                ],
                [
                    'q' => 'Czy przed przyjazdem z Łodzi mogę zobaczyć malucha na żywo?',
                    'a' => 'Tak, to nasz standardowy krok. Organizujemy 5-10 minutowe połączenie wideo przez WhatsApp, aby zaprezentować zachowanie kociaka, jego socjalizację i odpowiedzieć na wszelkie pytania.',
                ],
            ],
        ],
        'torun' => [
            'slug' => 'hodowla-kotow-torun',
            'name' => 'Toruń',
            'name_locative' => 'Toruniu',
            'name_genitive' => 'Torunia',
            'region' => 'województwo kujawsko-pomorskie',
            'distance_time' => '~1h 15 min',
            'route_desc' => 'malownicza i płynna trasa DK91 do Włocławka, a stamtąd DK62 wzdłuż doliny Wisły prosto do Sikorza',
            'travel_tips' => 'Z Torunia do Sikorza dojedziesz w około godzinę i kwadrans. Droga wzdłuż Wisły przez Włocławek jest spokojna i pozwala na komfortowy przejazd bez stania w korkach.',
            'local_context' => 'Dla opiekunów z Torunia i Kujaw nasza hodowla to gwarancja domowego wychowania. Maluchy od małego przyzwyczajane są do obecności domowników, dźwięków odkurzacza i codziennego życia rodzinnego.',
            'meta_title' => 'Hodowla Kotów Rasowych Toruń • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Domowa hodowla kotów rasowych z rodowodem dla opiekunów z Torunia. Zobacz maluchy bez wychodzenia z domu na wideo-wizycie WhatsApp/Messenger!',
            'faq' => [
                [
                    'q' => 'W jakim wieku kociak może wyruszyć do domu w Toruniu?',
                    'a' => 'Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka, po dwukrotnym zaszczepieniu, dwukrotnym odrobaczeniu, zaczipowaniu i pełnej socjalizacji kuwetowej.',
                ],
                [
                    'q' => 'Jakie dokumenty otrzymuję przy odbiorze?',
                    'a' => 'Każdy kotek otrzymuje wielopokoleniowy rodowód SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025), książeczkę zdrowia z wpisami weterynaryjnymi, umowę kupna-sprzedaży oraz certyfikaty badań rodziców.',
                ],
            ],
        ],
        'wloclawek' => [
            'slug' => 'hodowla-kotow-wloclawek',
            'name' => 'Włocławek',
            'name_locative' => 'Włocławku',
            'name_genitive' => 'Włocławka',
            'region' => 'województwo kujawsko-pomorskie',
            'distance_time' => '~35–40 min',
            'route_desc' => 'bezpośrednia i szybka trasa drogą krajową DK62 przez Nowy Duninów wprost do Sikorza k. Płocka',
            'travel_tips' => 'Z Włocławka do naszej domowej hodowli dzieli Cię zaledwie około 38 km. Przejazd trasą DK62 wzdłuż Jeziora Włocławskiego zajmuje niewiele ponad pół godziny, co sprawia, że jesteśmy Twoją najbliższą profesjonalną domową hodowlą kotów rasowych.',
            'local_context' => 'Bliskość geograficzna pozwala przyszłym opiekunom z Włocławka na wyjątkowo komfortowy kontakt – po wstępnej wideo-wizycie WhatsApp możesz bez problemu przyjechać na spotkanie adaptacyjne i osobiście poznać wybranego malucha.',
            'meta_title' => 'Hodowla Kotów Rasowych Włocławek • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Szukasz kota rasowego we Włocławku i okolicach? Odwiedź naszą domową hodowlę w Sikorzu (~35 min drogą DK62). Kocięta z rodowodem, badaniami HCM/PKD i wideo-wizytami na żywo.',
            'faq' => [
                [
                    'q' => 'Czy z Włocławka mogę przyjechać na niezobowiązujące spotkanie?',
                    'a' => 'Jak najbardziej. Ponieważ dzieli nas tylko 35 minut drogi, po wcześniejszym ustaleniu terminu zapraszamy serdecznie do naszego domu w Sikorzu, aby poznać koty w ich naturalnym otoczeniu.',
                ],
                [
                    'q' => 'Jak przygotować auto na krótki powrót do Włocławka?',
                    'a' => 'Trasa 35 minut jest dla kociaka bardzo łagodna. Wystarczy stabilny transporter z miękkim podkładem. Otrzymasz od nas kocyk z zapachem gniazda, który zapewni maluchowi pełne poczucie bezpieczeństwa.',
                ],
            ],
        ],
        'bydgoszcz' => [
            'slug' => 'hodowla-kotow-bydgoszcz',
            'name' => 'Bydgoszcz',
            'name_locative' => 'Bydgoszczy',
            'name_genitive' => 'Bydgoszczy',
            'region' => 'województwo kujawsko-pomorskie',
            'distance_time' => '~1h 40 min',
            'route_desc' => 'trasa drogą krajową DK10 lub autostradą A1 przez Toruń i Włocławek prosto do Sikorza k. Płocka',
            'travel_tips' => 'Dojazd z Bydgoszczy to około 110–120 km. Trasa przez Toruń i Włocławek jest płynna, a na miejscu czeka spokojna wiejska przestrzeń Sikorza, gdzie w ciszy i bez pośpiechu odbierzesz nowego członka rodziny.',
            'local_context' => 'Dla opiekunów z Bydgoszczy kluczowa jest pewność pochodzenia – hodowla zarejestrowana w SHiOZ ZOOLANDIA, regularny nadzór lekarza weterynarii oraz rzetelne badania kardiologiczne HCM i genetyczne.',
            'meta_title' => 'Hodowla Kotów Rasowych Bydgoszcz • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Kot rasowy z rodowodem dla mieszkańców Bydgoszczy i regionu. Certyfikat SHiOZ ZOOLANDIA, badania genetyczne HCM/PKD i 5-minutowe wideo-wizyty na żywo.',
            'faq' => [
                [
                    'q' => 'Czy przed podróżą z Bydgoszczy mogę sprawdzić wyniki badań rodziców?',
                    'a' => 'Oczywiście. Wszystkie certyfikaty badań kardiologicznych (echo serca HCM) oraz testy genetyczne (PKD, PRA-b, PK-Def) okazujemy podczas wideo-wizyty i dołączamy ich kopie do dokumentacji kociaka.',
                ],
                [
                    'q' => 'W co zaopatrzyć się przed przyjazdem po odbiór malucha?',
                    'a' => 'Wystarczy transporter z atestem. Od nas otrzymujesz zapas karmy na pierwsze dni, ulubione zabawki kociaka, kocyk z zapachem mamy oraz szczegółowe zalecenia pielęgnacyjne.',
                ],
            ],
        ],
    ];

    /**
     * Render the dedicated city landing page with real-time SSR animal data.
     */
    public function show(string $cityKey): View
    {
        if (! isset($this->cities[$cityKey])) {
            abort(404);
        }

        $city = $this->cities[$cityKey];

        $animals = Animal::query()
            ->published()
            ->whereIn('status', [AnimalStatus::Available, AnimalStatus::Reserved])
            ->with(['media'])
            ->orderByRaw("CASE WHEN status = 'available' THEN 1 ELSE 2 END ASC")
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.local-seo.city', [
            'city'    => $city,
            'animals' => $animals,
        ]);
    }
}
