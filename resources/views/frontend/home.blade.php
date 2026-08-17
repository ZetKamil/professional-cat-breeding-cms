<x-frontend.shell title="Hodowla Kotów Rasowych z Rodowodem – Bengalskie, Brytyjskie, Syjamskie | Mazowiecka Szwajcaria"
    meta-description="Domowa hodowla kotów rasowych z rodowodem SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Koty bengalskie, brytyjskie i syjamskie w Sikorzu k. Płocka (Mazowsze). Sprawdź dostępne kocięta!"
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}">
    {{-- ============================================================
    1. CINEMATIC ASYMMETRIC HERO — ROLLS ROYCE / APPLE STYLE
    ============================================================ --}}
    <section class="hero hero--dark" aria-label="Wprowadzenie i filozofia hodowli" data-nav-theme="dark">
        <div class="hero__bg" aria-hidden="true">
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80"
                alt="Kot bengalski w naturalnym oświetleniu" width="2000" height="1200" decoding="async" loading="eager"
                fetchpriority="high" class="hero__image opacity-25">
            <div class="hero__overlay"></div>
        </div>

        <div class="section-inner relative z-10 py-20 lg:py-24">
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
                        <x-frontend.button href="{{ route('frontend.animals.index') }}" icon="arrow-right">
                            Zobacz dostępne kocięta
                        </x-frontend.button>
                        <x-frontend.button href="{{ route('contact') }}" variant="secondary" icon="mail">
                            Skontaktuj się z nami
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
                        <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=1200&q=80"
                            alt="Doskonałość rasy - kot bengalski" class="hero-split__image" width="1000" height="750"
                            decoding="async" loading="eager">
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
                    <span>Badania Genetyczne Rodziców (Dla Rasy)</span>
                </div>
                <div class="hero__trust-item" role="listitem">
                    <i data-lucide="heart-handshake" class="hero__trust-icon" aria-hidden="true"></i>
                    <span>Domowa Socjalizacja</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    2. IMMERSIVE BREED SHOWCASE — THE THREE COLLECTIONS
    ============================================================ --}}
    <x-frontend.section id="specjalizacje" class="reveal-up home-section--specjalizacje">
        <x-frontend.section-header eyebrow="Kolekcje Hodowlane" headline="Nasze Specjalizacje Rasy"
            description="Wyselekcjonowane linie felinologiczne prowadzone zgodnie ze standardami stowarzyszenia SHiOZ ZOOLANDIA." />

        <div class="collection-grid" role="list" aria-label="Specjalizacje rasy">
            <a href="{{ route('frontend.animals.index', ['breed' => 'bengal']) }}" class="collection-card"
                role="listitem">
                <div>
                    <span class="collection-card__index">Collection 01 / Bengal</span>
                    <h3 class="collection-card__title">Koty Bengalskie</h3>
                    <p class="collection-card__quote">
                        Dzikie spojrzenie, wyraźna atłasowa rozeta i niezwykle inteligentny, aktywny temperament.
                    </p>
                    <div class="collection-card__tags">
                        <span class="collection-tag">Aktywność: Wysoka</span>
                        <span class="collection-tag">Rozeta na złocie</span>
                        <span class="collection-tag">Rodowód SHiOZ ZOOLANDIA</span>
                    </div>
                </div>
                <span class="collection-card__cta">
                    Poznaj kolekcję bengalską
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-primary" aria-hidden="true"></i>
                </span>
            </a>

            <a href="{{ route('frontend.animals.index', ['breed' => 'british']) }}" class="collection-card"
                role="listitem">
                <div>
                    <span class="collection-card__index">Collection 02 / British Shorthair</span>
                    <h3 class="collection-card__title">Koty Brytyjskie</h3>
                    <p class="collection-card__quote">
                        Aksamitne futro, zrównoważony spokój i klasyczna, brytyjska elegancja w każdym ruchu.
                    </p>
                    <div class="collection-card__tags">
                        <span class="collection-tag">Temperament: Spokojny</span>
                        <span class="collection-tag">Pluszowa okrywa</span>
                        <span class="collection-tag">Rodowód SHiOZ ZOOLANDIA</span>
                    </div>
                </div>
                <span class="collection-card__cta">
                    Poznaj kolekcję brytyjską
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-primary" aria-hidden="true"></i>
                </span>
            </a>

            <a href="{{ route('frontend.animals.index', ['breed' => 'siamese']) }}" class="collection-card"
                role="listitem">
                <div>
                    <span class="collection-card__index">Collection 03 / Siamese</span>
                    <h3 class="collection-card__title">Koty Syjamskie</h3>
                    <p class="collection-card__quote">
                        Szafirowe spojrzenie, smukła sylwetka oraz wyjątkowe przywiązanie i komunikatywność z
                        człowiekiem.
                    </p>
                    <div class="collection-card__tags">
                        <span class="collection-tag">Wysoka inteligencja</span>
                        <span class="collection-tag">Oczy szafirowe</span>
                        <span class="collection-tag">Linie europejskie</span>
                    </div>
                </div>
                <span class="collection-card__cta">
                    Poznaj kolekcję syjamską
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-primary" aria-hidden="true"></i>
                </span>
            </a>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    3. CURATED KITTENS & SELECTION ASSURANCE
    ============================================================ --}}
    <x-frontend.section id="nasze-koty" tile="parchment" class="reveal-up home-section--nasze-koty">
        <x-frontend.section-header eyebrow="Selekcja 2026" headline="Dostępne Kocięta i Mioty"
            description="Lista kociąt gotowych do rezerwacji. Każde kocię opuszcza nas z 2 szczepieniami, 2 odrobaczeniami, zarejestrowanym microchipem i rodowodem SHiOZ ZOOLANDIA." />

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
    5. DAILY LIFE IN THE CATTERY — EDITORIAL FACEBOOK DISCOVERY
    ============================================================ --}}
    <x-frontend.section id="codziennosc" class="reveal-up home-section--codziennosc">
        <div class="daily-life-grid">
            <div class="daily-life__left">
                <div class="daily-life__meta">
                    <span class="daily-life__eyebrow">01 — DAILY LIFE & TRANSPARENCY</span>
                </div>
                <h2 class="daily-life__headline">Codzienność w hodowli</h2>
                <p class="daily-life__desc">
                    Nie pokazujemy tylko efektu końcowego.
                    Dzielimy się również codziennym życiem naszych kotów — zdjęciami, filmami, rozwojem kociąt i
                    chwilami zza kulis.
                </p>
                <div class="daily-life__frequency">
                    <span class="daily-life__badge">Nowe relacje praktycznie codziennie</span>
                    <span class="daily-life__subtext">Zdjęcia • Filmy • Kulisy hodowli</span>
                </div>
                <div class="daily-life__cta">
                    <x-frontend.button variant="secondary" href="https://www.facebook.com/profile.php?id=61580668026948"
                        target="_blank" rel="noopener">
                        Obserwuj nas na Facebooku →
                    </x-frontend.button>
                </div>
            </div>

            <div class="daily-life__right">
                <div class="daily-life__gallery" role="group" aria-label="Codzienne życie hodowli">
                    <div class="daily-life__image-wrap daily-life__image-wrap--main">
                        <img src="https://images.unsplash.com/photo-1548247416-ec66f4900b2e?auto=format&fit=crop&w=800&q=80"
                            alt="Codzienna socjalizacja w hodowli" width="800" height="800" decoding="async"
                            loading="lazy">
                    </div>
                    <div class="daily-life__image-wrap daily-life__image-wrap--sub1">
                        <img src="https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?auto=format&fit=crop&w=600&q=80"
                            alt="Rozwój kociąt w domowym otoczeniu" width="600" height="600" decoding="async"
                            loading="lazy">
                    </div>
                    <div class="daily-life__image-wrap daily-life__image-wrap--sub2">
                        <img src="https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?auto=format&fit=crop&w=600&q=80"
                            alt="Zabawa i relacje z kotami" width="600" height="600" decoding="async" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    6. READING ROOM — EDITORIAL KNOWLEDGE
    ============================================================ --}}
    <x-frontend.section id="blog" class="reveal-up home-section--blog">
        <x-frontend.section-header eyebrow="Czytelnia Hodowlana" headline="Wiedza i Felinologia"
            description="Edukujemy, dzielimy się wiedzą i pomagamy zrozumieć świat kotów rasowych." />

        @if(isset($latestPosts) && $latestPosts->count() > 0)
            <div class="articles-grid">
                @foreach($latestPosts->take(3) as $post)
                    <x-frontend.blog-card :post="$post" />
                @endforeach
            </div>

            <div class="section-action">
                <x-frontend.button variant="secondary" href="{{ route('frontend.blog.index') }}" icon="arrow-right">
                    Zobacz wszystkie artykuły w czytelni
                </x-frontend.button>
            </div>
        @else
            <div class="editorial-empty-box">
                <i data-lucide="book-open" aria-hidden="true" class="editorial-empty-box__icon"></i>
                <h3 class="editorial-empty-box__title">Nasza czytelnia — wkrótce nowe publikacje</h3>
                <p class="editorial-empty-box__desc">
                    Przygotowujemy dla Państwa rzetelne poradniki na temat pielęgnacji, żywienia oraz psychologii kotów
                    rasowych.
                </p>
            </div>
        @endif
    </x-frontend.section>

    {{-- ============================================================
    7. FINAL INVITATION — LUXURY CONCIERGE CTA MONOLITH
    ============================================================ --}}
    <div class="reveal-up home-section--cta">
        <x-frontend.cta tile="parchment" eyebrow="Zaproszenie do Kontaktu" headline="Zaplanuj Rozmowę"
            description="Napisz do nas — chętnie odpowiemy na wszystkie pytania, doradzimy w wyborze linii genetycznej i umówimy kameralne spotkanie w naszej hodowli."
            buttonText="Skontaktuj się z nami" buttonHref="{{ route('contact') }}" />
    </div>

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "LocalBusiness",
      "name": "Hodowla Kotów z Mazowieckiej Szwajcarii",
      "description": "Domowa hodowla kotów rasowych z Mazowieckiej Szwajcarii (Sikórz k. Płocka). Członek SHiOZ ZOOLANDIA (Certyfikat: 58/CW/2025, Rejestracja: 58/P/2025). Koty bengalskie, brytyjskie i syjamskie z rodowodem, po przebadanych rodzicach.",
      "image": "{{ asset('storage/media/parent_bella_1.jpg') }}",
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