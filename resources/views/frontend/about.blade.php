<x-frontend.shell
    title="O Nas — {{ config('app.name') }}"
    meta-description="Poznaj naszą hodowlę kotów rasowych — naszą historię, filozofię i standardy, które wyróżniają nas na tle innych."
>
    {{-- ============================================================
         HERO — Editorial, calm, personal
         „Kim jesteście?"
         ============================================================ --}}
    <section class="about-hero tile-dark" aria-label="O nas">
        <div class="section">
            <div class="section-inner">
                <div class="about-hero__content">
                    <span class="about-hero__eyebrow">Nasza Historia</span>
                    <h1 class="text-hero-display about-hero__headline">
                        Hodowla z pasją<br>od pokoleń.
                    </h1>
                    <p class="text-lead-airy about-hero__lead">
                        Każdy kot, który opuszcza naszą hodowlę, niesie ze sobą lata doświadczenia,
                        miłości i troski o zdrowie oraz piękno rasy.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         OUR STORY — Editorial text, photography-first
         „Dlaczego to robicie?"
         ============================================================ --}}
    <x-frontend.section>
        <div class="about-story">
            <div class="about-story__text">
                <x-frontend.section-header
                    align="left"
                    eyebrow="Nasza filozofia"
                    headline="Zdrowie i piękno idą w parze"
                />
                <div class="about-story__body">
                    <p class="text-body">
                        Nasza przygoda z hodowlą zaczęła się od fascynacji wyjątkowym charakterem
                        i pięknem kotów rasowych. Z biegiem lat ta pasja przerodziła się w profesjonalną
                        hodowlę, w której zdrowie genetyczne, socjalizacja i dobrostan zwierząt
                        stoją na pierwszym miejscu.
                    </p>
                    <p class="text-body">
                        Wierzymy, że profesjonalna hodowla to nie tylko piękne koty — to przede
                        wszystkim odpowiedzialność. Każdy kot jest badany genetycznie, regularnie
                        kontrolowany przez weterynarza i wychowywany w domowej atmosferze pełnej miłości.
                    </p>
                    <p class="text-body">
                        Naszym celem jest, aby każdy nowy właściciel otrzymał zdrowego, dobrze
                        zsocjalizowanego kota i pełne wsparcie przez cały okres wspólnego życia.
                    </p>
                </div>
            </div>
            <div class="about-story__image">
                <div class="placeholder-image placeholder-image--about">
                    <i data-lucide="heart" aria-hidden="true"></i>
                    <span>Zdjęcie hodowli</span>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         VALUES — What we stand for (dark tile alternation)
         „Jakie wartości wyznajecie?"
         ============================================================ --}}
    <x-frontend.section tile="dark" id="wartosci">
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
                    zwierzętami. Są przyzwyczajone do codziennego życia.
                </p>
            </div>

            <div class="value-item">
                <div class="value-item__number">04</div>
                <h3 class="value-item__title text-tagline">Wsparcie</h3>
                <p class="value-item__desc text-body">
                    Nie kończymy relacji w momencie adopcji. Pomagamy w adaptacji,
                    doradzamy w kwestiach żywienia, zdrowia i wychowania.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         WHAT YOU GET — Concrete benefits (parchment tile)
         „Co dokładnie dostaję?"
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="co-otrzymujesz">
        <x-frontend.section-header
            eyebrow="Standard"
            headline="Co otrzymujesz z naszej hodowli"
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
                <p class="benefit-item__desc">Wyniki testów DNA rodziców</p>
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
                <p class="benefit-item__desc">Pomoc i doradztwo przez cały okres życia kota</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         CONTACT CTA
         ============================================================ --}}
    <x-frontend.cta
        tile="dark"
        headline="Chcesz poznać nas bliżej?"
        description="Zapraszamy do kontaktu — chętnie opowiemy więcej o naszej hodowli i odpowiemy na pytania."
        buttonText="Skontaktuj się"
        buttonHref="{{ route('contact') }}"
    />

</x-frontend.shell>

<style>
    /* ==========================================================================
       ABOUT HERO
       ========================================================================== */

    .about-hero {
        padding-top: 44px; /* nav height offset */
    }

    .about-hero .section {
        padding-top: var(--sp-4xl);
        padding-bottom: var(--sp-4xl);
    }

    .about-hero__content {
        max-width: 720px;
    }

    .about-hero__eyebrow {
        display: inline-block;
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--color-primary-on-dark);
        margin-bottom: var(--sp-lg);
    }

    .about-hero__headline {
        color: var(--color-canvas);
        margin-bottom: var(--sp-lg);
    }

    .about-hero__lead {
        color: var(--color-body-muted);
    }

    /* ==========================================================================
       STORY — Two-column editorial
       ========================================================================== */

    .about-story {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--sp-3xl);
        align-items: center;
    }

    .about-story__body {
        display: flex;
        flex-direction: column;
        gap: var(--sp-lg);
    }

    .about-story__body .text-body {
        color: var(--color-ink-muted-80);
    }

    .about-story__image {
        border-radius: var(--r-lg);
        overflow: hidden;
    }

    .placeholder-image--about {
        aspect-ratio: 3 / 4;
        background: linear-gradient(
            160deg,
            var(--color-canvas-parchment) 0%,
            var(--color-divider-soft) 100%
        );
    }

    /* ==========================================================================
       VALUES GRID — Dark tile
       ========================================================================== */

    .values-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--sp-xl);
    }

    .value-item {
        padding: var(--sp-lg);
    }

    .value-item__number {
        font-size: var(--text-hero-display-size);
        font-weight: 600;
        color: rgba(255, 255, 255, 0.08);
        line-height: 1;
        margin-bottom: var(--sp-md);
    }

    .value-item__title {
        color: var(--color-canvas);
        margin-bottom: var(--sp-sm);
    }

    .value-item__desc {
        color: var(--color-body-muted);
        font-size: var(--text-btn-util-size);
        line-height: 1.5;
    }

    /* ==========================================================================
       BENEFITS GRID — Parchment tile
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
        font-size: var(--text-btn-util-size);
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
    }

    @media (max-width: 834px) {
        .about-story {
            grid-template-columns: 1fr;
            gap: var(--sp-xl);
        }

        .about-story__image {
            order: -1;
        }

        .benefits-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .values-grid,
        .benefits-grid {
            grid-template-columns: 1fr;
        }

        .about-hero .section {
            padding-top: var(--sp-2xl);
            padding-bottom: var(--sp-2xl);
        }
    }
</style>
