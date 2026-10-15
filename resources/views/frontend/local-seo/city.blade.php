<x-frontend.shell
    :title="$city['meta_title']"
    :meta-description="$city['meta_description']"
>
    @push('schema')
    @php
        $localSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Hodowla Kotów z Mazowieckiej Szwajcarii — dla mieszkańców: ' . $city['name'],
            'description' => $city['meta_description'],
            'url' => url()->current(),
            'telephone' => '+48 514 153 204',
            'email' => 'biuro@kotyzmazowieckiejszwajcarii.pl',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Sikórz 56A',
                'addressLocality' => 'Sikórz k. Płocka',
                'postalCode' => '09-413',
                'addressRegion' => 'Mazowieckie',
                'addressCountry' => 'PL',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 52.6074,
                'longitude' => 19.5786,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    'opens' => '09:00',
                    'closes' => '20:00',
                ],
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city['name'],
            ],
            'priceRange' => '$$$',
        ];

        $faqSchema = !empty($city['faq']) ? [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['a'],
                    ],
                ];
            }, $city['faq']),
        ] : null;
    @endphp
    <script type="application/ld+json">
    {!! json_encode($localSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @if($faqSchema)
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
    @endpush

    {{-- ============================================================
    1. LOCAL HERO — DEDICATED CITY HEADER
    ============================================================ --}}
    <section class="hero hero--dark" aria-label="Hodowla kotów dla {{ $city['name_genitive'] }}" data-nav-theme="dark">
        <div class="hero__bg" aria-hidden="true">
            <x-frontend.picture
                src="{{ asset('image/hero/cinematic_bg.jpg') }}"
                alt=""
                width="1600"
                height="900"
                decoding="async"
                loading="eager"
                fetchpriority="high"
                class="hero__image opacity-30"
                webp-widths="400,800,1200"
            />
            <div class="hero__overlay"></div>
        </div>

        <div class="hero__content relative z-10">
            <div class="section-inner">
                <div class="hero-split-grid">
                    <div class="hero-split__left">
                        <span class="hero-split__eyebrow" style="display: inline-block; color: var(--color-gold, #c89d5c); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 12px;">
                            Dla Opiekunów z {{ $city['name_genitive'] }} • {{ $city['distance_time'] }} dojazdu
                        </span>
                        <h1 class="hero-split__title">
                            Hodowla Kotów Rasowych z Rodowodem dla Mieszkańców {{ $city['name_genitive'] }}.
                        </h1>
                        <p class="hero-split__lead">
                            Mieszkasz w {{ $city['name_locative'] }} lub okolicach i marzysz o kocie bengalskim, brytyjskim lub syjamskim? 
                            Nasza butikowa domowa hodowla w Sikorzu k. Płocka (SHiOZ ZOOLANDIA, Certyfikat 58/CW/2025) to bezpieczna przystań pełna troski, badań genetycznych HCM/PKD i domowej miłości — zaledwie {{ $city['distance_time'] }} od Twojego domu.
                        </p>
                        <div class="hero-split__actions" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                            <x-frontend.button href="#kocieta-miasto" icon="arrow-right">
                                Zobacz dostępne kocięta
                            </x-frontend.button>
                            <x-frontend.button 
                                type="button" 
                                variant="secondary"
                                onclick="window.openReservationModal('', '', 'Zapytanie z miasta: {{ $city['name'] }}')">
                                📹 Wideo-wizyta z {{ $city['name_genitive'] }}
                            </x-frontend.button>
                        </div>
                    </div>

                    <div class="hero-split__right">
                        <div class="hero-split__frame">
                            <x-frontend.picture
                                src="{{ asset('image/hero/bars_studio.jpg') }}"
                                alt="Kot rasowy hodowli z Mazowieckiej Szwajcarii dla opiekunów z miasta {{ $city['name'] }}"
                                class="hero-split__image"
                                width="1000"
                                height="750"
                                loading="eager"
                                decoding="async"
                                fetchpriority="high"
                                sizes="(max-width: 768px) 100vw, 50vw"
                                webp-widths="400,800,1200"
                            />
                        </div>
                    </div>
                </div>

                {{-- Trust and credentials strip --}}
                <div class="hero__trust-bar" role="list" aria-label="Standardy hodowli">
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="shield-check" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>SHiOZ ZOOLANDIA (58/CW/2025)</span>
                    </div>
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="dna" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>Badania Kardiologiczne & Genetyczne</span>
                    </div>
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="video" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>Wideo-wizyta bez wychodzenia z domu</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    2. VALUE PROPOSITION — NATURAL COUNTRYSIDE ENVIRONMENT
    ============================================================ --}}
    <x-frontend.section id="dlaczego-my" padding="default" class="reveal-up">
        <x-frontend.section-header 
            eyebrow="Dlaczego Warto Przyjechać do Sikorza" 
            headline="Naturalne Środowisko i Spokój Mazowieckiej Szwajcarii"
            description="Dlaczego przyszli opiekunowie z {{ $city['name_genitive'] }} wybierają naszą domową hodowlę w Sikorzu k. Płocka?"
        />

        {{-- Honest Location Clarification Box --}}
        <div style="background: rgba(200, 157, 92, 0.08); border-left: 4px solid var(--color-gold, #c89d5c); padding: 16px 20px; border-radius: 8px; margin-bottom: 32px; font-size: 0.92rem; line-height: 1.6; color: #333;">
            <strong style="color: #1a1a1a;">📍 Rzetelna Informacja o Lokalizacji:</strong> 
            Nasza hodowla mieści się fizycznie w <strong>Sikorzu 56A k. Płocka (woj. mazowieckie)</strong>. 
            Nie tworzymy fikcyjnych oddziałów ani punktów handlowych w {{ $city['name_locative'] }}. Zapraszamy do poznania naszych kociąt przez wygodną wideo-wizytę na WhatsApp lub Messengerze, a następnie do osobistego odbioru malucha w jego prawdziwym, domowym otoczeniu.
        </div>

        <div class="collection-grid" role="list">
            <div class="collection-card" role="listitem">
                <div>
                    <h3 class="collection-card__title">100% Warunki Domowe</h3>
                    <p class="collection-card__quote">
                        Nasze koty nie żyją w klatkach ani zamkniętych boksach. Wychowują się z nami w przestronnym domu w otoczeniu Brudzeńskiego Parku Krajobrazowego, mając stały kontakt z człowiekiem, dziećmi i domowymi dźwiękami.
                    </p>
                </div>
            </div>

            <div class="collection-card" role="listitem">
                <div>
                    <h3 class="collection-card__title">Pewne Badania Genetyczne</h3>
                    <p class="collection-card__quote">
                        Kupując kota z {{ $city['name_genitive'] }}, oczekujesz pewności. Każdy z rodziców naszych miotów przechodzi regularne badania kardiologiczne echo serca (HCM) oraz testy genetyczne (PKD, PRA-b, PK-Def, FIV/FeLV).
                    </p>
                </div>
            </div>

            <div class="collection-card" role="listitem">
                <div>
                    <h3 class="collection-card__title">Dogodny Dojazd ({{ $city['distance_time'] }})</h3>
                    <p class="collection-card__quote">
                        Sikórz znajduje się {{ $city['distance_time'] }} od {{ $city['name_genitive'] }} ({{ $city['route_desc'] }}). Po wcześniejszym poznaniu kociaka na wideo-wizycie, przyjazd po odbiór staje się przyjemną, bezstresową wycieczką.
                    </p>
                </div>
            </div>
        </div>

        @if(!empty($city['travel_tips']))
            <div style="margin-top: 36px; background: #ffffff; border: 1px solid rgba(0,0,0,0.08); border-radius: 14px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <h3 style="font-size: 1.1rem; color: #1a1a1a; margin-bottom: 8px;">🚗 Wskazówki dojazdu i transportu dla opiekunów z {{ $city['name_genitive'] }}:</h3>
                <p style="font-size: 0.92rem; color: #4a4a4a; line-height: 1.6; margin-bottom: 12px;">{{ $city['travel_tips'] }}</p>
                @if(!empty($city['local_context']))
                    <p style="font-size: 0.92rem; color: #4a4a4a; line-height: 1.6; margin: 0;">{{ $city['local_context'] }}</p>
                @endif
            </div>
        @endif
    </x-frontend.section>

    {{-- ============================================================
    3. VIDEO-VISIT IN 2 STEPS — TIME SAVING
    ============================================================ --}}
    <x-frontend.section tile="parchment" padding="default" class="reveal-up">
        <x-frontend.section-header 
            eyebrow="Wideo-Wizyta dla Mieszkańców {{ $city['name_genitive'] }}" 
            headline="Poznaj Kociaka z Własnej Kanapy w {{ $city['name_locative'] }}"
            description="Nie musisz tracić całego popołudnia na pierwszą podróż w ciemno. Zobacz malucha na żywo przez kamerę w telefonie!"
        />

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 900px; margin: 0 auto;">
            <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(200, 157, 92, 0.35); border-radius: 16px; padding: 28px 24px;">
                <span style="font-size: 2rem; display: block; margin-bottom: 12px;">📱</span>
                <strong style="display: block; font-size: 1.15rem; color: #1a1a1a; margin-bottom: 8px;">Krok 1: 5-minutowa Wideo-wizyta</strong>
                <p style="font-size: 0.9rem; line-height: 1.6; color: #4a4a4a; margin: 0 0 16px;">
                    Łączymy się przez WhatsApp lub Messenger. Pokażemy Ci kociaka w zabawie, zaprezentujemy jego mamę oraz certyfikaty badań zdrowotnych. Rozmawiamy bez pośpiechu i bez zobowiązań.
                </p>
                <button type="button" class="btn btn-primary" style="font-size: 0.85rem;" onclick="window.openReservationModal('', '', 'Wideo-wizyta z: {{ $city['name'] }}')">
                    📹 Umów wideo-rozmowę
                </button>
            </div>

            <div style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(200, 157, 92, 0.35); border-radius: 16px; padding: 28px 24px;">
                <span style="font-size: 2rem; display: block; margin-bottom: 12px;">🚗</span>
                <strong style="display: block; font-size: 1.15rem; color: #1a1a1a; margin-bottom: 8px;">Krok 2: Spokojny odbiór w Sikorzu</strong>
                <p style="font-size: 0.9rem; line-height: 1.6; color: #4a4a4a; margin: 0 0 16px;">
                    Gdy maluch osiągnie gotowość do odbioru (w wieku 12–14 tygodni) i wybierzesz swojego wymarzonego przyjaciela, przyjeżdżasz do naszej hodowli w Sikorzu ({{ $city['distance_time'] }} z {{ $city['name_genitive'] }}). Otrzymujesz rodowód SHiOZ ZOOLANDIA, książeczkę zdrowia (po dwukrotnym zaszczepieniu i dwukrotnym odrobaczeniu), microchip oraz kompletną wyprawkę.
                </p>
                <a href="tel:+48514153204" class="btn btn-secondary" style="font-size: 0.85rem;">
                    📞 Zadzwoń: +48 514 153 204
                </a>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    4. CURRENT KITTENS FOR SATELLITE CITY RESIDENTS
    ============================================================ --}}
    <x-frontend.section id="kocieta-miasto" padding="default" class="reveal-up">
        <x-frontend.section-header 
            eyebrow="Kocięta Gotowe do Rezerwacji" 
            headline="Aktualne Maluchy Czekające na Opiekunów z {{ $city['name_genitive'] }}"
            description="Kliknij 'Wideo-wizyta' przy wybranym kociaku, aby od razu zobaczyć go na żywo z hodowcą."
        />

        <div class="animals-grid">
            @forelse($animals as $animal)
                <x-frontend.animal-card :animal="$animal" />
            @empty
                <div class="editorial-empty-box">
                    <i data-lucide="calendar-heart" aria-hidden="true" class="editorial-empty-box__icon"></i>
                    <h3 class="editorial-empty-box__title">Planujemy nowe mioty na bieżący sezon</h3>
                    <p class="editorial-empty-box__desc">
                        Skontaktuj się z nami, aby zapisać się na listę oczekujących dla opiekunów z {{ $city['name_genitive'] }}.
                    </p>
                </div>
            @endforelse
        </div>
    </x-frontend.section>

    @if(!empty($city['faq']))
        {{-- ============================================================
        4b. LOCAL FAQ FOR CITY RESIDENTS
        ============================================================ --}}
        <x-frontend.section padding="compact" class="reveal-up">
            <x-frontend.section-header 
                eyebrow="Pytania i Odpowiedzi" 
                headline="Częste Pytania Opiekunów z {{ $city['name_genitive'] }}"
                description="Wszystko, co warto wiedzieć przed wyruszeniem w drogę do Sikorza."
            />

            <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px;">
                @foreach($city['faq'] as $faqItem)
                    <div style="background: #ffffff; border: 1px solid rgba(200, 157, 92, 0.25); border-radius: 12px; padding: 20px 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                        <h4 style="font-size: 1.05rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px;">❓ {{ $faqItem['q'] }}</h4>
                        <p style="font-size: 0.92rem; color: #4a4a4a; line-height: 1.6; margin: 0;">{{ $faqItem['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
    5. FINAL LOCAL CTA
    ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.section tile="parchment" padding="default" class="cta-section">
            <div class="cta-block">
                <div class="cta-block__eyebrow">
                    <span>Kontakt z Hodowlą dla {{ $city['name_genitive'] }}</span>
                </div>
                <h2 class="cta-block__headline">
                    Chcesz Porozmawiać o Wybranym Maluszku?
                </h2>
                <p class="cta-block__description">
                    Zadzwoń do nas lub połącz się na WhatsApp. Z przyjemnością odpowiemy na wszystkie pytania dotyczące rasy, pielęgnacji i bezpiecznego transportu kociaka do {{ $city['name_genitive'] }}.
                </p>
                <div class="cta-block__action" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <x-frontend.button
                        type="button"
                        variant="primary"
                        icon="arrow-right"
                        onclick="window.openReservationModal('', '', 'Strona miasta: {{ $city['name'] }}')">
                        📹 Umów wideo-wizytę na żywo
                    </x-frontend.button>
                    <x-frontend.button
                        href="tel:+48514153204"
                        variant="secondary"
                        icon="phone">
                        Zadzwoń: +48 514 153 204
                    </x-frontend.button>
                </div>
                <p class="cta-block__note" role="note" style="margin-top: 16px;">
                    <i data-lucide="shield-check" aria-hidden="true" class="cta-block__note-icon"></i>
                    <span>Sikórz 56A k. Płocka · Certyfikat SHiOZ ZOOLANDIA (58/CW/2025)</span>
                </p>
            </div>
        </x-frontend.section>
    </div>

</x-frontend.shell>
