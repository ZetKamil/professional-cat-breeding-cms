<x-frontend.shell title="{{ config('app.name') }} — Etyczna Hodowla Kotów Rasowych FIFe / FPL"
    meta-description="Hodowla kotów rasowych oparta na czystości genetycznej, spokoju i bezkompromisowych standardach etycznych FIFe / FPL.">
    {{-- ============================================================
    1. CINEMATIC ASYMMETRIC HERO — ROLLS ROYCE / APPLE STYLE
    ============================================================ --}}
    <section class="hero hero--dark" aria-label="Wprowadzenie i filozofia hodowli" data-nav-theme="dark">
        <div class="hero__bg" aria-hidden="true">
            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80"
                alt="Kot bengalski w naturalnym oświetleniu" width="2000" height="1200" decoding="async" loading="eager"
                fetchpriority="high" class="opacity-20">
            <div class="hero__overlay"></div>
        </div>

        <div class="section-inner relative z-10 py-20 lg:py-24">
            {{-- Split-Screen Editorial Opening --}}
            <div class="hero-split-grid">
                <div class="hero-split__left">
                    <h1 class="hero-split__title">
                        Czystość Rasy.<br>
                        Spokój Genetyki.<br>
                        Rodowód FIFe / FPL.
                    </h1>
                    <p class="hero-split__lead">
                        Nie traktujemy hodowli jak komercji. Tworzymy domowe sanktuarium,
                        w którym selekcja genetyczna, spokój socjalizacji i bezkompromisowe
                        standardy medyczne wyznaczają każdy etap życia naszych kotów.
                    </p>
                    <div class="hero-split__actions">
                        <x-frontend.button href="{{ route('contact') }}" icon="arrow-right">
                            Zaplanuj rozmowę adopcyjną
                        </x-frontend.button>
                    </div>

                    {{-- Hero Trust Strip --}}
                    <div class="hero__trust-bar" role="list" aria-label="Nasze certyfikaty i standardy">
                        <div class="hero__trust-item" role="listitem">
                            <i data-lucide="shield-check" class="hero__trust-icon" aria-hidden="true"></i>
                            <span>Certyfikat FIFe / FPL</span>
                        </div>
                        <div class="hero__trust-item" role="listitem">
                            <i data-lucide="dna" class="hero__trust-icon" aria-hidden="true"></i>
                            <span>100% Badania Genetyczne</span>
                        </div>
                        <div class="hero__trust-item" role="listitem">
                            <i data-lucide="heart-handshake" class="hero__trust-icon" aria-hidden="true"></i>
                            <span>Domowa Socjalizacja</span>
                        </div>
                    </div>
                </div>

                <div class="hero-split__right">
                    <div class="hero-split__frame">
                        <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=1200&q=80"
                            alt="Doskonałość rasy - kot bengalski" class="hero-split__image" width="1000" height="750"
                            decoding="async" loading="eager">
                    </div>
                    <div class="hero-split__seal" role="status" aria-label="Gwarancja genetyki">
                        <i data-lucide="award" class="w-5 h-5 text-primary" aria-hidden="true"></i>
                        <div>
                            <span class="block font-semibold">100% HCM / PKD n/n</span>
                            <span class="text-white/70 text-xs">Weryfikowane linie 5 pokoleń</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    2. BREEDING PHILOSOPHY — DOUBLE-SPREAD LUXURY MANIFESTO
    ============================================================ --}}
    <section class="manifesto-section reveal-up" aria-label="Manifest hodowlany" data-nav-theme="light">
        <div class="section-inner">
            <div class="manifesto__quote-box">
                <span class="manifesto__attribution block mb-4">Filozofia Naszej Hodowli</span>
                <blockquote class="manifesto__quote">
                    „Nie hodujemy kotów dla mas. Hodujemy dla tych, którzy poszukują
                    bezkompromisowego zdrowia, harmonii i autentycznego piękna w swoim domu.”
                </blockquote>
            </div>

            <div class="manifesto__grid">
                <div class="manifesto__tenets" role="list" aria-label="Filary etyczne">
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">01</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Standard Bez Kompromisów</h3>
                            <p class="manifesto-tenet__desc">
                                Każda decyzja hodowlana poparta jest wieloletnią analizą ksiąg rodowodowych,
                                co gwarantuje zachowanie wzorca rasy, idealnych proporcji oraz stabilnej psychiki.
                            </p>
                        </div>
                    </div>
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">02</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Czystość Genetyczna i Profilaktyka</h3>
                            <p class="manifesto-tenet__desc">
                                Nie uznajemy półśrodków w medycynie weterynaryjnej. Wszystkie nasze koty hodowlane
                                posiadają kompletne badania genetyczne w kierunku HCM, PKD, SMA oraz ujemny profil
                                FIV/FeLV.
                            </p>
                        </div>
                    </div>
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">03</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Wychowanie w Sercu Domu</h3>
                            <p class="manifesto-tenet__desc">
                                Kocięta od pierwszych minut życia dorastają w domowej przestrzeni, z codziennym odgłosem
                                życia rodzinnego, co przekłada się na ich wyjątkową ufność i otwartość na człowieka.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="manifesto__visual">
                    <div class="manifesto__portrait">
                        <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?auto=format&fit=crop&w=800&q=80"
                            alt="Kot w domowym otoczeniu" width="800" height="1066" decoding="async" loading="lazy">
                    </div>
                    <div class="manifesto__stat-card" aria-label="15 lat doświadczenia">
                        <div class="manifesto__stat-number">15+</div>
                        <div class="manifesto__stat-label">Lat selekcji rodowodowej FPL</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    3. IMMERSIVE BREED SHOWCASE — THE THREE COLLECTIONS
    ============================================================ --}}
    <x-frontend.section id="specjalizacje" class="reveal-up">
        <x-frontend.section-header eyebrow="Kolekcje Hodowlane" headline="Nasze Specjalizacje Rasy"
            description="Trzy wybitne linie felinologiczne, prowadzone według rygorystycznych standardów Międzynarodowej Federacji Felinologicznej (FIFe)." />

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
                        <span class="collection-tag">FIFe Lineage</span>
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
                    <h3 class="collection-card__title">Brytyjczyki</h3>
                    <p class="collection-card__quote">
                        Aksamitne futro, zrównoważony spokój i klasyczna, brytyjska elegancja w każdym ruchu.
                    </p>
                    <div class="collection-card__tags">
                        <span class="collection-tag">Temperament: Spokojny</span>
                        <span class="collection-tag">Pluszowa okrywa</span>
                        <span class="collection-tag">FPL Rodowód</span>
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
    4. CURATED KITTENS & SELECTION ASSURANCE
    ============================================================ --}}
    <x-frontend.section id="nasze-koty" tile="parchment" class="reveal-up">
        <x-frontend.section-header eyebrow="Selekcja 2026" headline="Dostępne Kocięta i Mioty"
            description="Aktualna selekcja kociąt gotowych do rezerwacji. Każde kocię opuszcza nas ze szczepieniami, odrobaczeniem i 5-pokoleniowym rodowodem." />

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
    5. ARCHITECTURAL HEALTH MATRIX (4-PILLAR CODE OF TRUST)
    ============================================================ --}}
    <x-frontend.section tile="dark" id="kodeks" class="reveal-up">
        <x-frontend.section-header eyebrow="Standard Medyczny i Etyka" headline="Czworoczęściowy Kodeks Zaufania"
            description="Standardy, które wyznaczają jakość każdego miotu w naszej hodowli." />

        <div class="kodeks-matrix" role="list" aria-label="Kodeks zaufania">
            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">01 / GENETICS</span>
                    <h3 class="kodeks-column__title">Badania Genetyczne</h3>
                </div>
                <p class="kodeks-column__desc">
                    Weryfikowane certyfikaty HCM, PKD oraz SMA n/n dla każdego rodzica w naszej hodowli.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">02 / VETERINARY</span>
                    <h3 class="kodeks-column__title">Nadzór Kliniczny</h3>
                </div>
                <p class="kodeks-column__desc">
                    Hodowla wolna od wirusów FIV oraz FeLV. Regularne echokardiografie serca i badania krwi.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">03 / FEDERATION</span>
                    <h3 class="kodeks-column__title">Rodowód FIFe / FPL</h3>
                </div>
                <p class="kodeks-column__desc">
                    Oryginalne, pięciopokoleniowe rodowody Międzynarodowej Federacji Felinologicznej dla każdego kota.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">04 / BEHAVIOR</span>
                    <h3 class="kodeks-column__title">Domowa Socjalizacja</h3>
                </div>
                <p class="kodeks-column__desc">
                    Wychowanie w pełnym kontakcie z domownikami, kształtujące pewność siebie, delikatność i otwartość.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    6. THE ADOPTION JOURNEY — LUXURY CONCIERGE TIMELINE
    ============================================================ --}}
    <x-frontend.section id="adopcja-krok-po-kroku" class="reveal-up">
        <x-frontend.section-header eyebrow="Concierge Adopcyjny" headline="Jak wygląda proces adopcji?"
            description="Troszczymy się o przyszłość naszych kotów, dlatego każdy etap adopcji jest jasny, spokojny i przejrzysty." />

        <div class="adoption-journey-grid" role="list" aria-label="Proces adopcji krok po kroku">
            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 01</span>
                    <i data-lucide="message-circle-heart" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Rozmowa i Dobór</h3>
                <p class="adoption-journey__desc">
                    Poznajemy Twoje oczekiwania i styl życia, aby pomóc Ci dobrać kota o charakterze idealnie pasującym
                    do Twojego domu.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 02</span>
                    <i data-lucide="home-heart" class="text-ink-muted-48" width="20" height="20" aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Rezerwacja i Wizyta</h3>
                <p class="adoption-journey__desc">
                    Po podjęciu decyzji podpisujemy umowę przedwstępną. Zapraszamy na wizytę osobistą lub spotkanie
                    wideo z maluchem.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 03</span>
                    <i data-lucide="file-check-2" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Odbiór i Wyprawka</h3>
                <p class="adoption-journey__desc">
                    Kot opuszcza hodowlę w wieku 14-16 tygodni z pięciopokoleniowym rodowodem FPL, badaniami zdrowia
                    oraz pakietem startowym.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    7. VERIFIED PATRON STORIES — EDITORIAL QUOTE MONOLITHS
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="opinie" class="reveal-up">
        <x-frontend.section-header eyebrow="Rekomendacje i Zaufanie" headline="Historie Naszych Wychowanków"
            description="4.9/5 Średnia ocena z 50+ zweryfikowanych adopcji w całej Polsce i Europie." />

        <div class="patrons-grid" role="list" aria-label="Rekomendacje opiekunów">
            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Luna jest cudowna — zdrowa, zadbana i przepiękna. Od pierwszych dni widać, że wyrosła w kochającym
                    domowym otoczeniu. Hodowla godna polecenia z całego serca.”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Anna K.</strong>
                        <span class="text-xs text-ink-muted-48">Właścicielka Luny · Kot Brytyjski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        FIFe Verified
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
                        <span class="text-xs text-ink-muted-48">Właściciel Simby · Kot Bengalski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        FIFe Verified
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
                        <span class="text-xs text-ink-muted-48">Właścicielka Mruczka · Kot Syjamski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        FIFe Verified
                    </span>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    8. READING ROOM — EDITORIAL KNOWLEDGE
    ============================================================ --}}
    <x-frontend.section id="blog" class="reveal-up">
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
    9. FINAL INVITATION — LUXURY CONCIERGE CTA MONOLITH
    ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta tile="parchment" eyebrow="Zaproszenie do Kontaktu" headline="Zaplanuj Rozmowę Adopcyjną"
            description="Napisz do nas — chętnie odpowiemy na wszystkie pytania, doradzimy w wyborze linii genetycznej i umówimy kameralne spotkanie w naszej hodowli."
            buttonText="Skontaktuj się z nami" buttonHref="{{ route('contact') }}" />
    </div>

</x-frontend.shell>