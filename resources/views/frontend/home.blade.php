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
                <x-frontend.animal-card :animal="$animal" />
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
                <x-frontend.button variant="secondary" href="{{ route('frontend.animals.index') }}" icon="arrow-right">
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
                    <x-frontend.card hoverable :href="route('frontend.blog.show', $post)">
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

            <div class="section-action">
                <x-frontend.button variant="secondary" href="{{ route('frontend.blog.index') }}" icon="arrow-right">
                    Zobacz wszystkie artykuły
                </x-frontend.button>
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


