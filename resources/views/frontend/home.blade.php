<x-frontend.shell
    title="{{ config('app.name') }} — Profesjonalna Hodowla Kotów Rasowych"
    meta-description="Profesjonalna hodowla kotów rasowych — zdrowie, piękno, transparentność. Poznaj nasze kocięta i dorosłe koty."
>
    {{-- ============================================================
         1. WHO ARE YOU?
         HERO SECTION — Full viewport, photography-first
         ============================================================ --}}
    <section class="hero" aria-label="Powitanie">
        <div class="hero__bg">
            <img 
                src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80" 
                alt="Piękny kot rasowy"
                class="hero__image"
                loading="eager"
                fetchpriority="high"
            >
            <div class="hero__overlay"></div>
        </div>
        <div class="hero__content">
            <div class="hero__inner section-inner">
                <span class="hero__eyebrow">Profesjonalna Hodowla</span>
                <h1 class="hero__headline text-hero-display">
                    Piękno. Zdrowie.<br>Zaufanie.
                </h1>
                <p class="hero__lead text-lead-airy">
                    Hodujemy z pasją, troską o zdrowie i pełną transparentnością.
                    Każdy kot jest wyjątkowy.
                </p>
                <div class="hero__actions">
                    <x-frontend.button href="#o-nas" icon="arrow-down">
                        Poznaj nas
                    </x-frontend.button>
                    <x-frontend.button variant="secondary" href="#nasze-koty">
                        Nasze koty
                    </x-frontend.button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. CAN I TRUST YOU?
         ABOUT PREVIEW — Storytelling, clean layout
         ============================================================ --}}
    <x-frontend.section id="o-nas" class="reveal-up">
        <div class="about-preview">
            <div class="about-preview__text">
                <x-frontend.section-header
                    align="left"
                    eyebrow="O Hodowli"
                    headline="Z miłości do doskonałości"
                />
                <div class="about-preview__body">
                    <p class="text-body text-ink-muted-80">
                        Nie jesteśmy fabryką. Jesteśmy kameralną, domową hodowlą, w której
                        każde narodziny to święto. Nasze koty żyją z nami na co dzień, śpią 
                        w naszych łóżkach i uczestniczą w życiu rodzinnym.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Dzięki temu są perfekcyjnie zsocjalizowane, ufne i otwarte na 
                        człowieka. Kładziemy ogromny nacisk na zdrowie genetyczne i
                        zgodność ze standardem rasy.
                    </p>
                    <div class="about-preview__action">
                        <x-frontend.button variant="secondary" href="{{ route('about') }}" icon="arrow-right">
                            Poznaj naszą historię
                        </x-frontend.button>
                    </div>
                </div>
            </div>
            <div class="about-preview__image-wrapper">
                <img 
                    src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?auto=format&fit=crop&w=1000&q=80" 
                    alt="Kot relaksujący się w domowym zaciszu"
                    class="about-preview__image"
                    loading="lazy"
                >
            </div>
        </div>
    </x-frontend.section>

    {{-- TESTIMONIALS (Part of "Can I trust you?") --}}
    <x-frontend.section tile="parchment" id="opinie" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Opinie"
            headline="Co mówią nasi klienci"
        />

        <div class="testimonials-grid">
            <blockquote class="testimonial">
                <p class="testimonial__quote text-lead-airy">
                    „Luna jest cudowna — zdrowa, zadbana, piękna. Hodowla godna polecenia z całego serca."
                </p>
                <footer class="testimonial__author">
                    <span class="testimonial__name text-body-strong">Anna K.</span>
                    <span class="testimonial__detail">Właścicielka Luny</span>
                </footer>
            </blockquote>

            <blockquote class="testimonial">
                <p class="testimonial__quote text-lead-airy">
                    „Profesjonalne podejście, pełna dokumentacja, cierpliwe odpowiadanie na pytania. Polecam!"
                </p>
                <footer class="testimonial__author">
                    <span class="testimonial__name text-body-strong">Marek W.</span>
                    <span class="testimonial__detail">Właściciel Simby</span>
                </footer>
            </blockquote>

            <blockquote class="testimonial">
                <p class="testimonial__quote text-lead-airy">
                    „Widać, że koty są kochane. Nasz Mruczek był od razu oswojony i szczęśliwy."
                </p>
                <footer class="testimonial__author">
                    <span class="testimonial__name text-body-strong">Katarzyna M.</span>
                    <span class="testimonial__detail">Właścicielka Mruczka</span>
                </footer>
            </blockquote>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         3. WHICH ANIMALS ARE AVAILABLE?
         AVAILABLE ANIMALS — Card grid
         ============================================================ --}}
    <x-frontend.section id="nasze-koty" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Dostępne"
            headline="Nasze Kocięta"
            description="Poznaj nasze aktualnie dostępne mioty. Każde kocię opuszcza hodowlę z pełną dokumentacją medyczną."
        />

        <div class="animals-grid">
            @forelse($featuredAnimals as $animal)
                <x-frontend.card hoverable>
                    <x-slot:image>
                        @if($animal->media)
                            <img
                                src="{{ $animal->media->url() }}"
                                alt="{{ $animal->name }}"
                                loading="lazy"
                            >
                        @else
                            <img 
                                src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=800&q=80" 
                                alt="Kociak (Zdjęcie poglądowe)" 
                                loading="lazy"
                            >
                        @endif
                    </x-slot:image>
                    <div class="animal-card__body">
                        <div class="animal-card__meta">
                            <x-frontend.badge :variant="$animal->status->badgeVariant()">
                                {{ $animal->status->label() }}
                            </x-frontend.badge>
                            <span class="animal-card__gender">{{ $animal->gender->symbol() }} {{ $animal->gender->label() }}</span>
                        </div>
                        <h3 class="animal-card__name text-tagline">{{ $animal->name }}</h3>
                        <p class="animal-card__breed text-body">{{ $animal->breed }}</p>
                        @if($animal->date_of_birth)
                            <p class="animal-card__age">Ur. {{ $animal->date_of_birth->format('d.m.Y') }}</p>
                        @endif
                    </div>
                </x-frontend.card>
            @empty
                <div class="empty-state">
                    <i data-lucide="cat" aria-hidden="true" class="empty-state__icon"></i>
                    <h3 class="text-body-strong">Aktualnie brak dostępnych kociąt</h3>
                    <p class="text-body empty-state__desc">
                        Planujemy nowe mioty w nadchodzącym sezonie. Zachęcamy do kontaktu w celu rezerwacji.
                    </p>
                    <x-frontend.button variant="secondary" href="{{ route('contact') }}">
                        Zapytaj o plany hodowlane
                    </x-frontend.button>
                </div>
            @endforelse
        </div>

        @if(count($featuredAnimals) > 0)
            <div class="section-action">
                <x-frontend.button variant="secondary" href="#wszystkie-koty" icon="arrow-right">
                    Zobacz wszystkie koty
                </x-frontend.button>
            </div>
        @endif
    </x-frontend.section>

    {{-- ============================================================
         4. WHY CHOOSE THIS BREEDING?
         TRUST BUILDERS — Dark tile
         ============================================================ --}}
    <x-frontend.section tile="dark" id="dlaczego-my" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Dlaczego my"
            headline="Hodowla z certyfikatem"
            description="Stawiamy na zdrowie, genetykę i profesjonalizm. Każdy kot ma pełną dokumentację."
        />

        <div class="trust-grid">
            <div class="trust-item">
                <div class="trust-item__icon">
                    <i data-lucide="shield-check" aria-hidden="true"></i>
                </div>
                <h3 class="trust-item__title text-tagline">Zdrowie Gwarantowane</h3>
                <p class="trust-item__desc text-body">
                    Pełne badania genetyczne, regularne kontrole weterynaryjne, szczepienia i odrobaczanie.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__icon">
                    <i data-lucide="award" aria-hidden="true"></i>
                </div>
                <h3 class="trust-item__title text-tagline">Rodowody FPL/FIFe</h3>
                <p class="trust-item__desc text-body">
                    Każdy kot posiada pełny rodowód zarejestrowany w międzynarodowej organizacji felinologicznej.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__icon">
                    <i data-lucide="heart-handshake" aria-hidden="true"></i>
                </div>
                <h3 class="trust-item__title text-tagline">Wsparcie Po Adopcji</h3>
                <p class="trust-item__desc text-body">
                    Nie zostawiamy Cię samego — pomagamy w adaptacji i odpowiadamy na pytania przez cały okres.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__icon">
                    <i data-lucide="home" aria-hidden="true"></i>
                </div>
                <h3 class="trust-item__title text-tagline">Domowa Atmosfera</h3>
                <p class="trust-item__desc text-body">
                    Koty rosną w domowym środowisku, są socjalizowane i przyzwyczajone do kontaktu z ludźmi.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         LATEST ARTICLES — Blog preview
         ============================================================ --}}
    @if(isset($latestPosts) && $latestPosts->count() > 0)
        <x-frontend.section id="blog" class="reveal-up">
            <x-frontend.section-header
                eyebrow="Blog"
                headline="Najnowsze Artykuły"
                description="Edukujemy, dzielimy się wiedzą i pomagamy zrozumieć świat kotów rasowych."
            />

            <div class="articles-grid">
                @foreach($latestPosts->take(3) as $post)
                    <x-frontend.card hoverable href="#">
                        <x-slot:image>
                            @if($post->media)
                                <img
                                    src="{{ $post->media->url() }}"
                                    alt="{{ $post->title }}"
                                    loading="lazy"
                                >
                            @else
                                <img 
                                    src="https://images.unsplash.com/photo-1495360010541-f48722b34f7d?auto=format&fit=crop&w=800&q=80" 
                                    alt="Artykuł" 
                                    loading="lazy"
                                >
                            @endif
                        </x-slot:image>
                        <div class="article-card__body">
                            @if($post->categories->first())
                                <x-frontend.badge variant="muted">
                                    {{ $post->categories->first()->name }}
                                </x-frontend.badge>
                            @endif
                            <h3 class="text-tagline">{{ $post->title }}</h3>
                            <p class="article-card__excerpt text-body">
                                {{ Str::limit(strip_tags($post->body), 120) }}
                            </p>
                            <time class="article-card__date" datetime="{{ $post->published_at?->toIso8601String() }}">
                                {{ $post->published_at?->format('d.m.Y') }}
                            </time>
                        </div>
                    </x-frontend.card>
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
         5. CONTACT US
         CONTACT CTA — Final conversion point
         ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta
            tile="dark"
            headline="Zainteresowany naszymi kociętami?"
            description="Napisz do nas — chętnie odpowiemy na wszystkie pytania i umówimy wizytę."
            buttonText="Skontaktuj się"
            buttonHref="{{ route('contact') }}"
        />
    </div>

</x-frontend.shell>

<style>
    /* ==========================================================================
       HERO — Full viewport, photography-first
       ========================================================================== */

    .hero {
        position: relative;
        min-height: 100vh;
        min-height: 100dvh;
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
            rgba(0, 0, 0, 0.2) 0%,
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
        animation: heroFadeUp var(--duration-slow) var(--ease-out) 0.2s forwards;
    }

    .hero__headline {
        color: var(--color-canvas);
        margin-bottom: var(--sp-lg);
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) 0.4s forwards;
    }

    .hero__lead {
        color: var(--color-body-muted);
        max-width: 520px;
        margin-bottom: var(--sp-xl);
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) 0.6s forwards;
    }

    .hero__actions {
        display: flex;
        gap: var(--sp-md);
        flex-wrap: wrap;
        opacity: 0;
        animation: heroFadeUp var(--duration-slow) var(--ease-out) 0.8s forwards;
    }

    @keyframes heroFadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes heroZoom {
        to {
            transform: scale(1);
        }
    }

    /* Hero secondary button on dark */
    .hero .btn-secondary {
        border-color: rgba(255, 255, 255, 0.3);
        color: var(--color-canvas);
    }

    .hero .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: var(--color-canvas);
    }

    /* ==========================================================================
       ABOUT PREVIEW — Split Layout
       ========================================================================== */
       
    .about-preview {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--sp-3xl);
        align-items: center;
    }

    .about-preview__body {
        display: flex;
        flex-direction: column;
        gap: var(--sp-md);
        margin-top: var(--sp-xl);
    }

    .text-ink-muted-80 {
        color: var(--color-ink-muted-80);
    }

    .about-preview__action {
        margin-top: var(--sp-md);
    }

    .about-preview__image-wrapper {
        border-radius: var(--r-lg);
        overflow: hidden;
        aspect-ratio: 4 / 5;
        box-shadow: var(--shadow-product);
    }

    .about-preview__image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 10s ease-out;
    }

    .about-preview:hover .about-preview__image {
        transform: scale(1.05);
    }

    /* ==========================================================================
       ANIMALS GRID & EMPTY STATE
       ========================================================================== */

    .animals-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
    }

    .empty-state {
        grid-column: 1 / -1;
        background-color: var(--color-canvas-parchment);
        border-radius: var(--r-lg);
        padding: var(--sp-3xl) var(--sp-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: var(--sp-sm);
        border: 1px dashed var(--color-hairline);
    }

    .empty-state__icon {
        width: 48px;
        height: 48px;
        color: var(--color-ink-muted-48);
        margin-bottom: var(--sp-sm);
        opacity: 0.5;
    }

    .empty-state__desc {
        color: var(--color-ink-muted-80);
        max-width: 480px;
        margin-bottom: var(--sp-md);
    }

    .animal-card__body {
        display: flex;
        flex-direction: column;
        gap: var(--sp-xs);
    }

    .animal-card__meta {
        display: flex;
        align-items: center;
        gap: var(--sp-sm);
    }

    .animal-card__gender {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
    }

    .animal-card__name {
        margin-top: var(--sp-xxs);
    }

    .animal-card__breed {
        color: var(--color-ink-muted-80);
    }

    .animal-card__age {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
    }

    .section-action {
        display: flex;
        justify-content: center;
        margin-top: var(--sp-xl);
    }

    /* ==========================================================================
       TRUST GRID — Dark tile
       ========================================================================== */

    .trust-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--sp-xl);
    }

    .trust-item {
        text-align: center;
    }

    .trust-item__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        margin: 0 auto var(--sp-lg);
        border-radius: var(--r-lg);
        background-color: rgba(255, 255, 255, 0.06);
        color: var(--color-primary-on-dark);
        transition: transform var(--duration-fast) var(--ease-out);
    }
    
    .trust-item:hover .trust-item__icon {
        transform: translateY(-4px);
    }

    .trust-item__icon svg {
        width: 24px;
        height: 24px;
    }

    .trust-item__title {
        color: var(--color-canvas);
        margin-bottom: var(--sp-sm);
    }

    .trust-item__desc {
        color: var(--color-body-muted);
        font-size: var(--text-btn-util-size);
        line-height: 1.5;
    }

    /* ==========================================================================
       TESTIMONIALS GRID
       ========================================================================== */

    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
    }

    .testimonial {
        background-color: var(--color-canvas);
        border-radius: var(--r-lg);
        padding: var(--sp-xl);
        border: 1px solid var(--color-hairline);
        display: flex;
        flex-direction: column;
        gap: var(--sp-lg);
        transition: transform var(--duration-fast) var(--ease-out), box-shadow var(--duration-fast) var(--ease-out);
    }

    .testimonial:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
    }

    .testimonial__quote {
        color: var(--color-ink-muted-80);
        font-style: italic;
        flex: 1;
    }

    .testimonial__author {
        display: flex;
        flex-direction: column;
        gap: var(--sp-xxs);
    }

    .testimonial__name {
        color: var(--color-ink);
    }

    .testimonial__detail {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
    }

    /* ==========================================================================
       ARTICLES GRID
       ========================================================================== */

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
    }

    .article-card__body {
        display: flex;
        flex-direction: column;
        gap: var(--sp-xs);
    }

    .article-card__excerpt {
        color: var(--color-ink-muted-80);
        font-size: var(--text-btn-util-size);
        line-height: 1.5;
    }

    .article-card__date {
        font-size: var(--text-nav-size);
        color: var(--color-ink-muted-48);
        margin-top: var(--sp-xs);
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */

    @media (max-width: 1068px) {
        .trust-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 834px) {
        .about-preview {
            grid-template-columns: 1fr;
        }

        .about-preview__image-wrapper {
            order: -1;
            aspect-ratio: 16 / 9;
        }

        .animals-grid,
        .testimonials-grid,
        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .hero__inner {
            max-width: 100%;
        }
    }

    @media (max-width: 640px) {
        .hero {
            min-height: 85vh;
            min-height: 85dvh;
        }

        .animals-grid,
        .trust-grid,
        .testimonials-grid,
        .articles-grid {
            grid-template-columns: 1fr;
        }

        .hero__actions {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .hero__eyebrow,
        .hero__headline,
        .hero__lead,
        .hero__actions {
            opacity: 1;
            animation: none;
        }
        .hero__image, .about-preview__image {
            animation: none;
            transition: none;
        }
        .trust-item:hover .trust-item__icon, .testimonial:hover {
            transform: none;
        }
    }
</style>
