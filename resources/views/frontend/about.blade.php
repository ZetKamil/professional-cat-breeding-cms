<x-frontend.shell
    title="O Nas — {{ config('app.name') }}"
    meta-description="Poznaj naszą hodowlę kotów rasowych — naszą historię, filozofię i standardy, które wyróżniają nas na tle innych."
>
    {{-- ============================================================
         1. HERO — Editorial, photography-first
         ============================================================ --}}
    <section class="hero hero--about" aria-label="O nas">
        <div class="hero__bg">
            <img 
                src="https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=2000&q=80" 
                alt="Spokojny kot w promieniach słońca"
                class="hero__image"
                loading="eager"
                fetchpriority="high"
            >
            <div class="hero__overlay"></div>
        </div>
        <div class="hero__content">
            <div class="hero__inner section-inner">
                <span class="hero__eyebrow">Nasza Historia</span>
                <h1 class="text-hero-display hero__headline">
                    Hodowla z pasją<br>od pokoleń.
                </h1>
                <p class="text-lead-airy hero__lead">
                    Każdy kot, który opuszcza naszą hodowlę, niesie ze sobą lata doświadczenia,
                    miłości i bezkompromisowej troski o zdrowie.
                </p>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. STORY — Two-column editorial
         ============================================================ --}}
    <x-frontend.section id="nasza-filozofia" class="reveal-up">
        <div class="about-story">
            <div class="about-story__text">
                <x-frontend.section-header
                    align="left"
                    eyebrow="Nasza filozofia"
                    headline="Zdrowie i piękno idą w parze"
                />
                <div class="about-story__body">
                    <p class="text-body text-ink-muted-80">
                        Nasza przygoda z hodowlą zaczęła się od fascynacji wyjątkowym charakterem
                        i pięknem kotów rasowych. Z biegiem lat ta pasja przerodziła się w profesjonalną
                        hodowlę, w której dobrostan zwierząt stoi na bezwzględnym pierwszym miejscu.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Wierzymy, że profesjonalna hodowla to nie tylko piękne koty — to przede
                        wszystkim gigantyczna odpowiedzialność. Każdy kot jest regularnie badany genetycznie
                        i kardiologicznie, kontrolowany przez zaufanego weterynarza i wychowywany w naszej sypialni.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Nie jesteśmy wielką fermą. Mamy zaledwie kilka miotów rocznie, co pozwala nam
                        poświęcić każdemu kociakowi maksimum uwagi.
                    </p>
                </div>
            </div>
            <div class="about-story__image-wrapper">
                <img 
                    src="https://images.unsplash.com/photo-1533743983669-94fa5c4338ec?auto=format&fit=crop&w=1000&q=80" 
                    alt="Kot i właściciel"
                    class="about-story__image"
                    loading="lazy"
                >
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         3. TIMELINE — Milestones of the cattery
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="historia" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Kamienie milowe"
            headline="Nasza Droga"
            description="Historia budowania zaufania krok po kroku."
        />

        <div class="timeline">
            <div class="timeline__item">
                <div class="timeline__year text-display-md">2015</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Początki naszej pasji</h3>
                    <p class="text-body text-ink-muted-80">Pierwszy kot rasowy zamieszkał w naszym domu, zmieniając nasze życie na zawsze. Rozpoczęliśmy intensywną naukę o genetyce i behawiorystyce.</p>
                </div>
            </div>
            
            <div class="timeline__item">
                <div class="timeline__year text-display-md">2017</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Rejestracja hodowli (FIFe)</h3>
                    <p class="text-body text-ink-muted-80">Oficjalnie dołączyliśmy do Międzynarodowej Federacji Felinologicznej, przyjmując najwyższe światowe standardy etyczne i hodowlane.</p>
                </div>
            </div>

            <div class="timeline__item">
                <div class="timeline__year text-display-md">2019</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Pierwszy prestiżowy miot</h3>
                    <p class="text-body text-ink-muted-80">Na świat przyszedł miot "A". Pięć zdrowych, silnych i wspaniale zsocjalizowanych kociąt trafiło do kochających domów w całej Europie.</p>
                </div>
            </div>

            <div class="timeline__item">
                <div class="timeline__year text-display-md">2024</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Grand International Champion</h3>
                    <p class="text-body text-ink-muted-80">Nasz wspaniały kocur zdobył zaszczytny tytuł na międzynarodowej wystawie, potwierdzając wybitną zgodność naszej linii ze wzorcem rasy.</p>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         4. VALUES — Dark tile
         ============================================================ --}}
    <x-frontend.section tile="dark" id="wartosci" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Nasze wartości"
            headline="Na czym nam zależy"
            description="Każda decyzja, którą podejmujemy, jest podyktowana tymi zasadami."
        />

        <div class="values-grid">
            <div class="value-item">
                <div class="value-item__number">01</div>
                <h3 class="value-item__title text-tagline">Transparentność</h3>
                <p class="value-item__desc text-body">
                    Pełna dokumentacja zdrowotna, rodowody, wyniki badań genetycznych —
                    wszystko jest dostępne dla przyszłych właścicieli.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">02</div>
                <h3 class="value-item__title text-tagline">Zdrowie</h3>
                <p class="value-item__desc text-body">
                    Badania genetyczne, regularne kontrole weterynaryjne, odpowiedzialny
                    dobór par hodowlanych — zdrowie jest priorytetem numer jeden.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">03</div>
                <h3 class="value-item__title text-tagline">Socjalizacja</h3>
                <p class="value-item__desc text-body">
                    Koty rosną w domowym środowisku, w kontakcie z ludźmi i innymi
                    zwierzętami. Są doskonale przyzwyczajone do codziennego życia.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">04</div>
                <h3 class="value-item__title text-tagline">Wsparcie</h3>
                <p class="value-item__desc text-body">
                    Nie kończymy relacji w momencie adopcji. Pomagamy w adaptacji,
                    doradzamy w kwestiach żywienia, zdrowia i wychowania przez całe życie kota.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         5. AWARDS & CERTIFICATES
         ============================================================ --}}
    <x-frontend.section id="nagrody" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Prestiż"
            headline="Nasze Certyfikaty"
        />

        <div class="awards-grid">
            <div class="award-card">
                <i data-lucide="award" class="award-card__icon"></i>
                <h3 class="text-body-strong">Certyfikowana Hodowla FIFe</h3>
                <p class="text-nav text-ink-muted-48">Federation Internationale Feline</p>
            </div>
            <div class="award-card">
                <i data-lucide="shield-check" class="award-card__icon"></i>
                <h3 class="text-body-strong">Wolni od FIV/FeLV</h3>
                <p class="text-nav text-ink-muted-48">Badania ujemne dla wszystkich kotów hodowlanych</p>
            </div>
            <div class="award-card">
                <i data-lucide="dna" class="award-card__icon"></i>
                <h3 class="text-body-strong">Certyfikat Badań Genetycznych</h3>
                <p class="text-nav text-ink-muted-48">HCM, PKD, SMA n/n</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         6. GALLERY — Luxury Masonry
         ============================================================ --}}
    <x-frontend.section tile="dark" id="galeria" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Galeria"
            headline="Życie w hodowli"
            description="Momenty zatrzymane w kadrze. Poznaj nasze codzienne życie z kotami."
        />

        <div class="gallery-grid">
            <div class="gallery-item gallery-item--large">
                <img src="https://images.unsplash.com/photo-1548247416-ec66f4900b2e?auto=format&fit=crop&w=800&q=80" alt="Kot" loading="lazy">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?auto=format&fit=crop&w=600&q=80" alt="Kociak" loading="lazy">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?auto=format&fit=crop&w=600&q=80" alt="Bawiący się kot" loading="lazy">
            </div>
            <div class="gallery-item gallery-item--wide">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?auto=format&fit=crop&w=800&q=80" alt="Odpoczywający kot" loading="lazy">
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         7. WHAT YOU GET (Standard)
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="co-otrzymujesz" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Standard"
            headline="Wyprawka i dokumentacja"
        />

        <div class="benefits-grid">
            <div class="benefit-item">
                <i data-lucide="file-check" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Książeczka zdrowia</h3>
                <p class="benefit-item__desc">Kompletna historia szczepień i zabiegów</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="scroll-text" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Rodowód FPL/FIFe</h3>
                <p class="benefit-item__desc">Zarejestrowany rodowód z pełną genealogią</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="dna" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Badania genetyczne</h3>
                <p class="benefit-item__desc">Wyniki testów DNA obojga rodziców</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="syringe" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Szczepienia</h3>
                <p class="benefit-item__desc">Kompletny cykl szczepień adekwatny do wieku</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="bug-off" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Odrobaczanie</h3>
                <p class="benefit-item__desc">Regularne odrobaczanie od 2. tygodnia życia</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="phone" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Wsparcie dożywotnie</h3>
                <p class="benefit-item__desc">Bezpłatna pomoc i doradztwo przez całe życie</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         8. CONTACT CTA
         ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta
            tile="dark"
            headline="Chcesz poznać nas bliżej?"
            description="Zapraszamy do kontaktu — chętnie opowiemy więcej o naszej hodowli i odpowiemy na pytania."
            buttonText="Skontaktuj się"
            buttonHref="{{ route('contact') }}"
        />
    </div>

</x-frontend.shell>

<style>
    /* ==========================================================================
       HERO (Shared heavily with home, but tailored)
       ========================================================================== */
    .hero {
        position: relative;
        min-height: 80vh;
        min-height: 80dvh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero__bg {
        position: absolute;
        inset: 0;
        z-index: 0;
        background-color: var(--color-surface-black);
    }

    .hero__image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        transform: scale(1.05);
        animation: heroZoom var(--duration-slow) cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }

    .hero__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.3) 0%,
            rgba(0, 0, 0, 0.7) 100%
        );
    }

    .hero__content {
        position: relative;
        z-index: 1;
        width: 100%;
        padding: var(--sp-section) var(--content-padding-x);
    }

    .hero__inner {
        max-width: 720px;
    }

    .hero__eyebrow {
        display: inline-block;
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--color-primary-on-dark);
        margin-bottom: var(--sp-lg);
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) forwards;
    }

    .hero__headline {
        color: var(--color-canvas);
        margin-bottom: var(--sp-lg);
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) forwards;
    }

    .hero__lead {
        color: var(--color-body-muted);
        max-width: 520px;
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) forwards;
    }

    /* ==========================================================================
       STORY — Two-column editorial
       ========================================================================== */
    .about-story {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--sp-4xl);
        align-items: center;
    }

    .about-story__body {
        display: flex;
        flex-direction: column;
        gap: var(--sp-lg);
        margin-top: var(--sp-xl);
    }

    .text-ink-muted-80 {
        color: var(--color-ink-muted-80);
    }

    .about-story__image-wrapper {
        border-radius: var(--r-lg);
        overflow: hidden;
        aspect-ratio: 4 / 5;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    .about-story__image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 10s ease-out;
    }

    .about-story:hover .about-story__image {
        transform: scale(1.05);
    }

    /* ==========================================================================
       TIMELINE
       ========================================================================== */
    .timeline {
        display: flex;
        flex-direction: column;
        gap: var(--sp-3xl);
        margin-top: var(--sp-2xl);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 1px;
        background-color: var(--color-hairline);
    }

    .timeline__item {
        position: relative;
        padding-left: var(--sp-2xl);
    }

    .timeline__item::before {
        content: '';
        position: absolute;
        top: 8px;
        left: -4px;
        width: 9px;
        height: 9px;
        border-radius: var(--r-full);
        background-color: var(--color-primary);
        box-shadow: 0 0 0 4px var(--color-canvas-parchment);
    }

    .timeline__year {
        color: var(--color-ink);
        margin-bottom: var(--sp-xs);
        letter-spacing: -0.02em;
    }

    /* ==========================================================================
       VALUES GRID
       ========================================================================== */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--sp-xl);
    }

    .value-item {
        padding: var(--sp-lg) 0;
    }

    .value-item__number {
        font-size: var(--text-display-md-size);
        font-weight: 600;
        color: rgba(255, 255, 255, 0.08);
        line-height: 1;
        margin-bottom: var(--sp-md);
        letter-spacing: -0.04em;
    }

    .value-item__title {
        color: var(--color-canvas);
        margin-bottom: var(--sp-sm);
    }

    .value-item__desc {
        color: var(--color-body-muted);
        font-size: var(--text-body-size);
        line-height: 1.5;
    }

    /* ==========================================================================
       AWARDS
       ========================================================================== */
    .awards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
        margin-top: var(--sp-xl);
    }

    .award-card {
        background-color: var(--color-canvas-parchment);
        border: 1px solid var(--color-hairline);
        border-radius: var(--r-lg);
        padding: var(--sp-xl);
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--sp-sm);
        transition: transform var(--duration-fast) var(--ease-out),
                    box-shadow var(--duration-fast) var(--ease-out);
    }

    .award-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.04);
    }

    .award-card__icon {
        width: 32px;
        height: 32px;
        color: var(--color-primary);
        margin-bottom: var(--sp-xs);
    }

    /* ==========================================================================
       GALLERY
       ========================================================================== */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: 240px 240px;
        gap: var(--sp-sm);
        margin-top: var(--sp-xl);
    }

    .gallery-item {
        border-radius: var(--r-sm);
        overflow: hidden;
        position: relative;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 5s ease-out;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .gallery-item--large {
        grid-row: span 2;
        grid-column: span 2;
    }

    .gallery-item--wide {
        grid-column: span 2;
    }

    /* ==========================================================================
       BENEFITS
       ========================================================================== */
    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
    }

    .benefit-item {
        background-color: var(--color-canvas);
        border-radius: var(--r-lg);
        padding: var(--sp-xl);
        border: 1px solid var(--color-hairline);
        display: flex;
        flex-direction: column;
        gap: var(--sp-sm);
    }

    .benefit-item__icon {
        width: 24px;
        height: 24px;
        color: var(--color-primary);
        margin-bottom: var(--sp-xs);
    }

    .benefit-item__desc {
        font-size: var(--text-body-size);
        color: var(--color-ink-muted-48);
        line-height: 1.5;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 1068px) {
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .awards-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 834px) {
        .about-story {
            grid-template-columns: 1fr;
            gap: var(--sp-xl);
        }

        .about-story__image-wrapper {
            order: -1;
            aspect-ratio: 16/9;
        }

        .benefits-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto;
            grid-auto-rows: 240px;
        }

        .gallery-item--large, .gallery-item--wide {
            grid-column: span 1;
            grid-row: span 1;
        }
    }

    @media (max-width: 640px) {
        .values-grid,
        .benefits-grid,
        .awards-grid,
        .gallery-grid {
            grid-template-columns: 1fr;
        }

        .timeline::before {
            left: 20px;
        }
        .timeline__item {
            padding-left: 48px;
        }
        .timeline__item::before {
            left: 16px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hero__eyebrow,
        .hero__headline,
        .hero__lead,
        .hero__image {
            animation: none;
            opacity: 1;
            transform: none;
        }
        .about-story:hover .about-story__image,
        .gallery-item:hover img {
            transform: none;
        }
        .award-card:hover {
            transform: none;
        }
    }
</style>
