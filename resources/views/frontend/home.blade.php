<x-frontend.shell
    title="{{ config('app.name') }} — Profesjonalna Hodowla Kotów Rasowych"
    meta-description="Profesjonalna hodowla kotów rasowych — zdrowie, piękno, transparentność. Poznaj nasze kocięta i dorosłe koty."
>
    {{-- ============================================================
         HERO SECTION — Full viewport, photography-first
         One message. One CTA. Photography dominates.
         ============================================================ --}}
    <section class="hero" aria-label="Powitanie">
        <div class="hero__bg">
            {{-- Placeholder gradient — replace with real photography --}}
            <div class="hero__bg-placeholder"></div>
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
                    <x-frontend.button href="#nasze-koty" icon="arrow-down">
                        Poznaj nasze koty
                    </x-frontend.button>
                    <x-frontend.button variant="secondary" href="{{ route('contact') }}">
                        Kontakt
                    </x-frontend.button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         AVAILABLE ANIMALS — Card grid
         „Które kocięta są dostępne?"
         ============================================================ --}}
    <x-frontend.section id="nasze-koty">
        <x-frontend.section-header
            eyebrow="Dostępne"
            headline="Nasze Kocięta"
            description="Każde kocię opuszcza hodowlę zaszczepione, odrobaczone, z książeczką zdrowia i rodowodem."
        />

        <div class="animals-grid">
            {{-- Placeholder cards — will be replaced with real data --}}
            @for($i = 1; $i <= 3; $i++)
                <x-frontend.card hoverable>
                    <x-slot:image>
                        <div class="placeholder-image placeholder-image--cat">
                            <i data-lucide="cat" aria-hidden="true"></i>
                            <span>Zdjęcie kota</span>
                        </div>
                    </x-slot:image>
                    <div class="animal-card__body">
                        <div class="animal-card__meta">
                            <x-frontend.badge variant="success">Dostępny</x-frontend.badge>
                            <span class="animal-card__gender">♀ Kotka</span>
                        </div>
                        <h3 class="animal-card__name text-tagline">Luna #{{ $i }}</h3>
                        <p class="animal-card__breed text-body">Kot Brytyjski Krótkowłosy</p>
                        <p class="animal-card__age">Ur. {{ now()->subMonths(3)->format('d.m.Y') }}</p>
                    </div>
                </x-frontend.card>
            @endfor
        </div>

        <div class="section-action">
            <x-frontend.button variant="secondary" href="#wszystkie-koty" icon="arrow-right">
                Zobacz wszystkie koty
            </x-frontend.button>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         WHY CHOOSE US — Trust builders (dark tile)
         „Dlaczego akurat ta hodowla?"
         ============================================================ --}}
    <x-frontend.section tile="dark" id="dlaczego-my">
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
         TESTIMONIALS — Social proof (parchment tile)
         „Czy mogę zaufać tej hodowli?"
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="opinie">
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
         LATEST ARTICLES — Blog preview (light tile)
         ============================================================ --}}
    @if(isset($latestPosts) && $latestPosts->count() > 0)
        <x-frontend.section id="blog">
            <x-frontend.section-header
                eyebrow="Blog"
                headline="Najnowsze Artykuły"
                description="Edukujemy, dzielimy się wiedzą i pomagamy zrozumieć świat kotów rasowych."
            />

            <div class="articles-grid">
                @foreach($latestPosts->take(3) as $post)
                    <x-frontend.card hoverable href="#">
                        <x-slot:image>
                            @if($post->media->first())
                                <img
                                    src="{{ $post->media->first()->getUrl() }}"
                                    alt="{{ $post->title }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="placeholder-image">
                                    <i data-lucide="image" aria-hidden="true"></i>
                                </div>
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
         CONTACT CTA — Final conversion point
         „Jak mogę się skontaktować?"
         ============================================================ --}}
    <x-frontend.cta
        tile="dark"
        headline="Zainteresowany naszymi kociętami?"
        description="Napisz do nas — chętnie odpowiemy na wszystkie pytania i umówimy wizytę."
        buttonText="Skontaktuj się"
        buttonHref="{{ route('contact') }}"
    />

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
    }

    .hero__bg-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(
            135deg,
            var(--color-surface-tile-1) 0%,
            var(--color-surface-tile-3) 50%,
            var(--color-surface-black) 100%
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
       ANIMALS GRID
       ========================================================================== */

    .animals-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
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
       PLACEHOLDER IMAGES
       ========================================================================== */

    .placeholder-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        background-color: var(--color-canvas-parchment);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: var(--sp-xs);
        color: var(--color-ink-muted-48);
    }

    .placeholder-image svg {
        width: 32px;
        height: 32px;
        opacity: 0.4;
    }

    .placeholder-image span {
        font-size: var(--text-nav-size);
        opacity: 0.5;
    }

    .placeholder-image--cat {
        background: linear-gradient(
            135deg,
            var(--color-canvas-parchment) 0%,
            var(--color-divider-soft) 100%
        );
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
    }
</style>
