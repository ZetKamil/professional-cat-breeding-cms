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
            'route_desc' => 'szybki dojazd trasą S7 / DK62 prosto do zielonego Sikorza k. Płocka',
            'meta_title' => 'Hodowla Kotów Rasowych Warszawa • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Szukasz rasowego kota w Warszawie? Poznaj domową hodowlę z Mazowieckiej Szwajcarii (Sikórz k. Płocka). Sprawdź kocięta na żywo przez wideo-wizytę na WhatsApp!',
        ],
        'lodz' => [
            'slug' => 'hodowla-kotow-lodz',
            'name' => 'Łódź',
            'name_locative' => 'Łodzi',
            'name_genitive' => 'Łodzi',
            'region' => 'województwo łódzkie',
            'distance_time' => '~1h 30 min',
            'route_desc' => 'dogodny dojazd autostradą A1 i trasą na Płock prosto do Sikorza',
            'meta_title' => 'Hodowla Kotów Rasowych Łódź • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Hodowla kotów rasowych dla mieszkańców Łodzi i regionu łódzkiego. Koty bengalskie, brytyjskie i syjamskie z rodowodem SHiOZ ZOOLANDIA. Umów wideo-wizytę na żywo!',
        ],
        'torun' => [
            'slug' => 'hodowla-kotow-torun',
            'name' => 'Toruń',
            'name_locative' => 'Toruniu',
            'name_genitive' => 'Torunia',
            'region' => 'województwo kujawsko-pomorskie',
            'distance_time' => '~1h 15 min',
            'route_desc' => 'komfortowy i szybki dojazd wzdłuż Wisły przez Włocławek do Sikorza',
            'meta_title' => 'Hodowla Kotów Rasowych Toruń • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Domowa hodowla kotów rasowych z rodowodem dla opiekunów z Torunia. Zobacz maluchy bez wychodzenia z domu na wideo-wizycie WhatsApp/Messenger!',
        ],
        'bydgoszcz' => [
            'slug' => 'hodowla-kotow-bydgoszcz',
            'name' => 'Bydgoszcz',
            'name_locative' => 'Bydgoszczy',
            'name_genitive' => 'Bydgoszczy',
            'region' => 'województwo kujawsko-pomorskie',
            'distance_time' => '~1h 40 min',
            'route_desc' => 'spokojna trasa przez Toruń i Włocławek prosto do zielonej doliny Sikorza',
            'meta_title' => 'Hodowla Kotów Rasowych Bydgoszcz • Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria',
            'meta_description' => 'Kot rasowy z rodowodem dla mieszkańców Bydgoszczy i Włocławka. Certyfikat SHiOZ ZOOLANDIA, badania genetyczne HCM/PKD i 5-minutowe wideo-wizyty na żywo.',
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
