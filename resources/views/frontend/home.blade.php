<x-frontend.shell title="Domowa Hodowla Kotów Rasowych z Rodowodem – Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria"
    meta-description="Domowa hodowla kotów rasowych z rodowodem SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Koty bengalskie, brytyjskie i syjamskie w Sikorzu k. Płocka (Mazowsze). Sprawdź dostępne kocięta!"
    og-image="{{ asset('og-default.jpg') }}">
    {{-- ============================================================
    1. CINEMATIC ASYMMETRIC HERO — ROLLS ROYCE / APPLE STYLE
    ============================================================ --}}
    <section class="hero hero--dark" aria-label="Wprowadzenie i filozofia hodowli" data-nav-theme="dark">
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
                {{-- Split-Screen Editorial Opening --}}
                <div class="hero-split-grid">
                    <div class="hero-split__left">
                        <span class="hero-split__eyebrow">
                            <span class="hero-split__eyebrow--mobile">SHiOZ ZOOLANDIA • MAZOWIECKA SZWAJCARIA</span>
                            <span class="hero-split__eyebrow--desktop">SHiOZ ZOOLANDIA • MAZOWIECKA SZWAJCARIA • SIKÓRZ K. PŁOCKA</span>
                        </span>
                        <h1 class="hero-split__title">
                            <span class="hero-split__title--mobile">Domowa hodowla kotów:<br>Bengalskie, Brytyjskie i Syjamskie.</span>
                            <span class="hero-split__title--desktop">Domowa Hodowla Kotów<br>Rasowych z Rodowodem.<br>Bengalskie, Brytyjskie i Syjamskie.</span>
                        </h1>
                        <p class="hero-split__lead">
                            <span class="hero-split__lead--mobile">Badania genetyczne rodziców (HCM/PKD), pełne wsparcie i chów w sercu rodziny.</span>
                            <span class="hero-split__lead--desktop">Hodowla kotów z Mazowieckiej Szwajcarii, oficjalny członek <strong>SHiOZ ZOOLANDIA</strong> (Certyfikat: 58/CW/2025, Rejestracja: 58/P/2025). Nasze koty rozwijają się w bezpiecznych, pełnych miłości warunkach, z regularną kontrolą kardiologiczną (echo serca) i badaniami genetycznymi rodziców dobranymi do danej rasy.</span>
                        </p>
                        <div class="hero-split__actions">
                            {{-- Mobile Compact Actions --}}
                            <div class="hero-split__actions--mobile">
                                <a href="#nasze-koty" class="hero-btn hero-btn--primary">
                                    <span>🐾 Zobacz dostępne kocięta</span>
                                </a>
                                <a href="{{ route('quiz') }}" class="hero-btn hero-btn--secondary">
                                    <span>✨ Quiz: Dobierz rasę (45s)</span>
                                </a>
                            </div>

                            {{-- Desktop Full Actions --}}
                            <div class="hero-split__actions--desktop">
                                <x-frontend.button href="#nasze-koty" icon="arrow-right">
                                    Zobacz dostępne kocięta
                                </x-frontend.button>
                                <x-frontend.button href="{{ route('quiz') }}" variant="secondary">
                                    ✨ Quiz: Dobierz rasę (45s)
                                </x-frontend.button>
                            </div>
                        </div>
                        <div class="hero-split__facebook">
                            <a href="https://www.facebook.com/profile.php?id=61580668026948" target="_blank" rel="noopener"
                                class="hero-split__secondary-link">
                                Codzienne życie hodowli na Facebooku →
                            </a>
                        </div>
                    </div>

                    <div class="hero-split__right">
                        <div class="hero-split__frame">
                            <x-frontend.picture
                                src="{{ asset('image/hero/bars_studio.jpg') }}"
                                alt="Bars – kot bengalski z rodowodem, reproduktor hodowli Mazowiecka Szwajcaria"
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

                        <div class="hero-split__seal" role="status" aria-label="Gwarancja zdrowia i genetyki">
                            <i data-lucide="award" class="w-5 h-5 text-primary" aria-hidden="true"></i>
                            <div>
                                <span class="block font-semibold">SHiOZ ZOOLANDIA</span>
                                <span class="text-white/70 text-xs">Certyfikat: 58/CW/2025 · Rej. 58/P/2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hero Trust Strip --}}
                <div class="hero__trust-bar" role="list" aria-label="Nasze certyfikaty i standardy">
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="shield-check" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>Certyfikat SHiOZ ZOOLANDIA (58/CW/2025)</span>
                    </div>
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="dna" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>Badania Genetyczne Rodziców (HCM/PKD)</span>
                    </div>
                    <div class="hero__trust-item" role="listitem">
                        <i data-lucide="heart-handshake" class="hero__trust-icon" aria-hidden="true"></i>
                        <span>Wideo-wizyty na żywo</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    2. CURATED KITTENS & SELECTION ASSURANCE (PRIORITIZED FOR CRO)
    ============================================================ --}}
    <x-frontend.section id="nasze-koty" tile="parchment" class="reveal-up home-section--nasze-koty">
        <x-frontend.section-header eyebrow="Selekcja 2026" headline="Dostępne Kocięta i Mioty"
            description="Lista kociąt gotowych do rezerwacji. Wszystkie nasze kocięta opuszczają hodowlę z aktualną książeczką zdrowia z dwoma szczepieniami, dwukrotnym odrobaczeniem, microchipem oraz rodowodem SHiOZ ZOOLANDIA." />

        <div class="animals-grid">
            @forelse($featuredAnimals as $animal)
                <x-frontend.animal-card :animal="$animal" />
            @empty
                <div class="editorial-empty-box">
                    <i data-lucide="calendar-heart" aria-hidden="true" class="editorial-empty-box__icon"></i>
                    <h3 class="editorial-empty-box__title">Aktualnie wszystkie kocięta znalazły nowe domy</h3>
                    <p class="editorial-empty-box__desc">
                        Planujemy nowe mioty w nadchodzącym sezonie hodowlanym. Zachęcamy do niezobowiązującego kontaktu w
                        celu wpisu na naszą listę oczekujących.
                    </p>
                    <div class="mt-6">
                        <x-frontend.button variant="secondary" href="{{ route('contact') }}">
                            Zapytaj o plany na sezon 2026/2027
                        </x-frontend.button>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($featuredAnimals) > 0)
            <div class="section-action">
                <x-frontend.button variant="secondary" href="{{ route('frontend.animals.index') }}" icon="arrow-right">
                    Zobacz wszystkie koty w katalogu
                </x-frontend.button>
            </div>
        @endif
    </x-frontend.section>


    {{-- ============================================================
    5. DOMESTIC SOCIALIZATION & DAILY LIFE (OPTION A)
    ============================================================ --}}
    <x-frontend.section id="codziennosc" class="reveal-up home-section--codziennosc">
        <div class="daily-life-grid">
            <div class="daily-life__left">
                <span class="daily-life__eyebrow" style="display: inline-block; margin-bottom: 12px;">
                    Domowa Socjalizacja • Sikórz k. Płocka
                </span>
                <h2 class="daily-life__headline">Czułość i domowe ciepło od pierwszego dnia.</h2>
                <p class="daily-life__desc">
                    Wierzymy, że stabilny i ufny charakter kota kształtuje się w pierwszych 12 tygodniach życia. Nasze koty nie są izolowane — dorastają w samym sercu naszego domu, otoczone miłością, dźwiękami codzienności i stałą opieką weterynaryjną.
                </p>

                <div class="daily-life__pillars" role="list" aria-label="Filary domowej socjalizacji">
                    <div class="daily-life__pillar" role="listitem">
                        <div class="daily-life__pillar-icon" aria-hidden="true">
                            <i data-lucide="home"></i>
                        </div>
                        <div>
                            <h3 class="daily-life__pillar-title">Chów w sercu rodziny, 100% życia z nami</h3>
                            <p class="daily-life__pillar-text">Salon i sypialnia to ich naturalna przestrzeń. Naturalnie oswajają się z odkurzaczem, telewizorem i gośćmi.</p>
                        </div>
                    </div>
                    <div class="daily-life__pillar" role="listitem">
                        <div class="daily-life__pillar-icon" aria-hidden="true">
                            <i data-lucide="heart"></i>
                        </div>
                        <div>
                            <h3 class="daily-life__pillar-title">Czuły kontakt z człowiekiem</h3>
                            <p class="daily-life__pillar-text">Codzienne branie na ręce, głaskanie i zabawa sprawiają, że koty same szukają kolan i głośno mruczą.</p>
                        </div>
                    </div>
                    <div class="daily-life__pillar" role="listitem">
                        <div class="daily-life__pillar-icon" aria-hidden="true">
                            <i data-lucide="video"></i>
                        </div>
                        <div>
                            <h3 class="daily-life__pillar-title">Prywatne relacje wideo po rezerwacji</h3>
                            <p class="daily-life__pillar-text">Po rezerwacji malucha regularnie otrzymujesz prywatne filmiki i zdjęcia, pokazujące każdy etap jego rozwoju.</p>
                        </div>
                    </div>
                </div>

                <div class="daily-life__actions">
                    <x-frontend.button
                        type="button"
                        variant="primary"
                        onclick="window.openReservationModal('', '', '')"
                        icon="video">
                        Zobacz maluchy na żywo
                    </x-frontend.button>
                    <a href="https://www.facebook.com/profile.php?id=61580668026948"
                        target="_blank" rel="noopener"
                        class="daily-life__fb-link">
                        Codzienne kulisy na Facebooku →
                    </a>
                </div>
            </div>

            <div class="daily-life__right">
                <div class="daily-life__gallery" role="group" aria-label="Domowe otoczenie i relacje z kotami">
                    <div class="daily-life__image-wrap daily-life__image-wrap--main">
                        <x-frontend.picture
                            src="{{ asset('image/about/story_care.jpg') }}"
                            alt="Czuła opieka i socjalizacja kotów w domowej hodowli Mazowiecka Szwajcaria"
                            width="800"
                            height="800"
                            decoding="async"
                            loading="lazy"
                            sizes="(max-width: 768px) 100vw, 350px"
                            webp-widths="400,800"
                        />
                    </div>
                    <div class="daily-life__image-wrap daily-life__image-wrap--sub1">
                        <x-frontend.picture
                            src="{{ asset('image/hero/stas_studio.jpg') }}"
                            alt="Staś – kocur brytyjski długowłosy (BLH), domowy spokój i ufność"
                            width="600"
                            height="600"
                            decoding="async"
                            loading="lazy"
                            sizes="(max-width: 768px) 50vw, 200px"
                            webp-widths="400"
                        />
                    </div>
                    <div class="daily-life__image-wrap daily-life__image-wrap--sub2">
                        <x-frontend.picture
                            src="{{ asset('image/hero/bars_studio.jpg') }}"
                            alt="Bars – kot bengalski, zabawa i energia w domowym otoczeniu"
                            width="600"
                            height="600"
                            decoding="async"
                            loading="lazy"
                            sizes="(max-width: 768px) 50vw, 200px"
                            webp-widths="400"
                        />
                    </div>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    4. FINAL INVITATION — REUSABLE CONVERSION CTA
    ============================================================ --}}
    <x-frontend.conversion-cta source-context="Strona główna" />

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "LocalBusiness",
      "name": "Domowa Hodowla Kotów z Mazowieckiej Szwajcarii",
      "description": "Domowa hodowla kotów rasowych z Mazowieckiej Szwajcarii (Sikórz k. Płocka). Członek SHiOZ ZOOLANDIA (Certyfikat: 58/CW/2025, Rejestracja: 58/P/2025). Koty bengalskie, brytyjskie i syjamskie z rodowodem, po przebadanych rodzicach.",
      "image": "{{ asset('image/hero/bars_studio.jpg') }}",
      "logo": "{{ asset('logo.png') }}",
      "url": "https://kotyzmazowieckiejszwajcarii.pl/",
      "telephone": "+48514153204",
      "email": "biuro@kotyzmazowieckiejszwajcarii.pl",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Sikórz 56A",
        "addressLocality": "Sikórz",
        "postalCode": "09-413",
        "addressRegion": "mazowieckie",
        "addressCountry": "PL"
      },
      "sameAs": [
        "https://www.facebook.com/profile.php?id=61580668026948"
      ]
    }
    </script>
    @endpush
</x-frontend.shell>