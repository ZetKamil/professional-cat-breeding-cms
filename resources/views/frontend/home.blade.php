<x-frontend.shell title="Hodowla Kotów Rasowych z Rodowodem – Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria"
    meta-description="Domowa hodowla kotów rasowych z rodowodem SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Koty bengalskie, brytyjskie i syjamskie w Sikorzu k. Płocka (Mazowsze). Sprawdź dostępne kocięta!"
    og-image="{{ asset('image/hero/bars_studio.jpg') }}">
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
                        <span class="hero-split__eyebrow" style="display: inline-block; color: var(--color-gold, #c89d5c); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 12px;">
                            Hodowla Kotów Rasowych • SHiOZ ZOOLANDIA • Sikórz k. Płocka
                        </span>
                        <h1 class="hero-split__title">
                            Hodowla Kotów Rasowych z Rodowodem.<br>
                            Bengalskie, Brytyjskie i Syjamskie.
                        </h1>
                        <p class="hero-split__lead">
                            Domowa hodowla kotów z Mazowieckiej Szwajcarii, oficjalny członek <strong>SHiOZ ZOOLANDIA</strong> (Certyfikat: 58/CW/2025, Rejestracja: 58/P/2025). 
                            Nasze koty rozwijają się w bezpiecznych, pełnych miłości warunkach domowych, z regularną kontrolą kardiologiczną (echo serca) i badaniami genetycznymi rodziców dobranymi do danej rasy.
                        </p>
                        <div class="hero-split__actions" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                            <x-frontend.button href="#nasze-koty" icon="arrow-right">
                                Zobacz dostępne kocięta
                            </x-frontend.button>
                            <x-frontend.button 
                                type="button" 
                                variant="secondary"
                                onclick="window.openReservationModal('', '', '')">
                                📹 Wideo-wizyta na żywo
                            </x-frontend.button>
                        </div>
                        <div style="margin-top: 14px;">
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
            description="Lista kociąt gotowych do rezerwacji. Wszystkie nasze kocięta opuszczają hodowlę z aktualną książeczką zdrowia z dwoma szczepieniami, odrobaczeniem, microchipem oraz rodowodem SHiOZ ZOOLANDIA." />

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
    4. VERIFIED PATRON STORIES — EDITORIAL QUOTE MONOLITHS
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="opinie" class="reveal-up home-section--opinie">
        <x-frontend.section-header eyebrow="Rekomendacje i Zaufanie" headline="Historie Opiekunów Naszych Kotów"
            description="Opinie i wrażenia nowych opiekunów kotów pochodzących z naszej hodowli." />

        <div class="patrons-grid" role="list" aria-label="Rekomendacje opiekunów">
            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Luna jest cudowna — zdrowa, zadbana i przepiękna. Od pierwszych dni widać, że wyrosła w kochającym
                    domowym otoczeniu. Hodowla godna polecenia z całego serca.”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Anna K.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekunka Luny · Kot Brytyjski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>

            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Profesjonalne podejście na każdym etapie, pełna dokumentacja medyczna oraz cierpliwe odpowiadanie
                    na wszystkie nasze pytania. Polecam bez wahania!”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Marek W.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekun Simby · Kot Bengalski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>

            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Widać, że koty w tej hodowli są prawdziwie kochane. Nasz Mruczek był natychmiast oswojony, ufny i
                    szczęśliwy w nowym domu od pierwszego dnia.”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Katarzyna M.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekunka Mruczka · Kot Syjamski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>
        </div>
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
                <h2 class="daily-life__headline">Czułość i życie bez klatek od pierwszego dnia.</h2>
                <p class="daily-life__desc">
                    Wierzymy, że stabilny i ufny charakter kota kształtuje się w pierwszych 12 tygodniach życia. Nasze koty nie mieszkają w odizolowanych boksach ani klatkach — dorastają w samym sercu naszego domu, otoczone miłością, dźwiękami codzienności i stałą opieką weterynaryjną.
                </p>

                <div class="daily-life__pillars" role="list" aria-label="Filary domowej socjalizacji">
                    <div class="daily-life__pillar" role="listitem">
                        <div class="daily-life__pillar-icon" aria-hidden="true">
                            <i data-lucide="home"></i>
                        </div>
                        <div>
                            <h3 class="daily-life__pillar-title">Zero klatek, 100% życia z nami</h3>
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
                        📹 Zobacz maluchy na żywo
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
                            alt="Staś – kocur brytyjski, domowy spokój i ufność"
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
    6. FINAL INVITATION — DIRECT VIDEO CONCIERGE & WHATSAPP ACTIONS
    ============================================================ --}}
    <div class="reveal-up home-section--cta">
        <x-frontend.cta tile="parchment" eyebrow="Zaproszenie do Kontaktu i Rezerwacji" headline="Zaplanuj Rozmowę lub Wideo-Wizytę"
            description="Chcesz poznać nasze kocięta na żywo? Włączamy kamerę w naszym salonie i odpowiadamy na wszystkie pytania — bez pośpiechu i bez zobowiązań."
            note="Bezpieczna rezerwacja z rodowodem SHiOZ ZOOLANDIA · Pełna dokumentacja medyczna i wyprawka">
            <div class="cta-block__actions-row">
                <x-frontend.button
                    type="button"
                    variant="primary"
                    onclick="window.openReservationModal('', '', '')"
                    icon="video"
                    class="cta-block__btn">
                    📹 Umów wideo-rozmowę na żywo
                </x-frontend.button>
                <x-frontend.button
                    type="button"
                    variant="secondary"
                    onclick="if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', cat_name: 'Ogólne zapytanie' }); } window.open('https://wa.me/48514153204?text=' + encodeURIComponent('Dzień dobry! Piszę ze strony głównej hodowli Mazowiecka Szwajcaria. Chciałbym zapytać o rezerwację kociąt i wideo-wizytę na żywo.'), '_blank');"
                    icon="message-circle"
                    class="cta-block__btn">
                    💬 Porozmawiaj na WhatsApp
                </x-frontend.button>
            </div>
            <a href="tel:+48514153204" class="cta-block__direct-phone">
                lub zadzwoń bezpośrednio: <strong>+48 514 153 204</strong>
            </a>
        </x-frontend.cta>
    </div>

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "LocalBusiness",
      "name": "Hodowla Kotów z Mazowieckiej Szwajcarii",
      "description": "Domowa hodowla kotów rasowych z Mazowieckiej Szwajcarii (Sikórz k. Płocka). Członek SHiOZ ZOOLANDIA (Certyfikat: 58/CW/2025, Rejestracja: 58/P/2025). Koty bengalskie, brytyjskie i syjamskie z rodowodem, po przebadanych rodzicach.",
      "image": "{{ asset('image/hero/bars_studio.jpg') }}",
      "logo": "{{ asset('logo.png') }}",
      "url": "https://kotyzmazowieckiejszwajcarii.pl/",
      "telephone": "+48514153204",
      "email": "hodowla.z.mazowieckiej.szwajcarii@gmail.com",
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