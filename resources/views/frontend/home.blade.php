<x-frontend.shell
    title="{{ config('app.name') }} — Profesjonalna Hodowla Kotów Rasowych"
    meta-description="Profesjonalna hodowla kotów rasowych — zdrowie, piękno, transparentność. Poznaj nasze kocięta i dorosłe koty."
>
    {{-- ============================================================
         1. WHO ARE YOU?
         HERO SECTION — Full viewport, photography-first
         ============================================================ --}}
    <x-frontend.hero
        eyebrow="Profesjonalna Hodowla"
        title="Piękno. Zdrowie.<br>Zaufanie."
        lead="Hodujemy z pasją, troską o zdrowie i pełną transparentnością. Każdy kot jest wyjątkowy."
        image-url="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80"
        image-alt="Piękny kot rasowy"
        scroll-target="#o-nas"
        size="full"
    >
        <x-frontend.button href="#o-nas" icon="arrow-down">
            Poznaj nas
        </x-frontend.button>
        <x-frontend.button variant="secondary" href="#nasze-koty">
            Nasze koty
        </x-frontend.button>
    </x-frontend.hero>

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
                    <div class="about-preview__stats">
                        <div class="about-preview__stat">
                            <div class="about-preview__stat-number">15+</div>
                            <div class="about-preview__stat-label">Lat pasji i doświadczenia</div>
                        </div>
                        <div class="about-preview__stat">
                            <div class="about-preview__stat-number">100%</div>
                            <div class="about-preview__stat-label">Zdrowia i badań genetycznych</div>
                        </div>
                        <div class="about-preview__stat">
                            <div class="about-preview__stat-number">FIFe</div>
                            <div class="about-preview__stat-label">Międzynarodowy certyfikat</div>
                        </div>
                    </div>
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
                    width="1000"
                    height="750"
                    decoding="async"
                    loading="lazy"
                >
                <div class="about-preview__badge-overlap">
                    <div class="about-preview__badge-content">
                        <div class="about-preview__badge-title">Certyfikowana Hodowla FIFe / FPL</div>
                        <div class="about-preview__badge-desc">Etyczne standardy miłości i dbałości</div>
                    </div>
                    <div class="about-preview__badge-icon" aria-hidden="true">
                        <i data-lucide="award" aria-hidden="true"></i>
                    </div>
                </div>
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
                <div class="testimonial__header">
                    <div class="testimonial__rating" aria-label="Ocena: 5 na 5 gwiazdek">
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                    </div>
                    <span class="testimonial__verified">
                        <i data-lucide="check-circle" aria-hidden="true"></i>
                        Zweryfikowana adopcja
                    </span>
                </div>
                <p class="testimonial__quote">
                    „Luna jest cudowna — zdrowa, zadbana i przepiękna. Od pierwszych dni widać, że wyrosła w kochającym domowym otoczeniu. Hodowla godna polecenia z całego serca."
                </p>
                <footer class="testimonial__author">
                    <div class="testimonial__avatar" aria-hidden="true">AK</div>
                    <div class="testimonial__author-meta">
                        <span class="testimonial__name">Anna K.</span>
                        <span class="testimonial__detail">Właścicielka Luny · Brytyjczyk</span>
                    </div>
                </footer>
            </blockquote>

            <blockquote class="testimonial">
                <div class="testimonial__header">
                    <div class="testimonial__rating" aria-label="Ocena: 5 na 5 gwiazdek">
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                    </div>
                    <span class="testimonial__verified">
                        <i data-lucide="check-circle" aria-hidden="true"></i>
                        Zweryfikowana adopcja
                    </span>
                </div>
                <p class="testimonial__quote">
                    „Profesjonalne podejście na każdym etapie, pełna dokumentacja medyczna oraz cierpliwe odpowiadanie na wszystkie nasze pytania. Polecam bez wahania!"
                </p>
                <footer class="testimonial__author">
                    <div class="testimonial__avatar" aria-hidden="true">MW</div>
                    <div class="testimonial__author-meta">
                        <span class="testimonial__name">Marek W.</span>
                        <span class="testimonial__detail">Właściciel Simby · Kot Bengalski</span>
                    </div>
                </footer>
            </blockquote>

            <blockquote class="testimonial">
                <div class="testimonial__header">
                    <div class="testimonial__rating" aria-label="Ocena: 5 na 5 gwiazdek">
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                        <i data-lucide="star" class="testimonial__star" aria-hidden="true"></i>
                    </div>
                    <span class="testimonial__verified">
                        <i data-lucide="check-circle" aria-hidden="true"></i>
                        Zweryfikowana adopcja
                    </span>
                </div>
                <p class="testimonial__quote">
                    „Widać, że koty w tej hodowli są prawdziwie kochane. Nasz Mruczek był natychmiast oswojony, ufny i szczęśliwy w nowym domu od pierwszego dnia."
                </p>
                <footer class="testimonial__author">
                    <div class="testimonial__avatar" aria-hidden="true">KM</div>
                    <div class="testimonial__author-meta">
                        <span class="testimonial__name">Katarzyna M.</span>
                        <span class="testimonial__detail">Właścicielka Mruczka · Kot Syjamski</span>
                    </div>
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
                <div class="trust-item__header">
                    <span class="trust-item__index">01</span>
                    <div class="trust-item__icon">
                        <i data-lucide="shield-check" aria-hidden="true"></i>
                    </div>
                </div>
                <h3 class="trust-item__title">Zdrowie Gwarantowane</h3>
                <p class="trust-item__desc">
                    Pełne badania genetyczne, regularne kontrole weterynaryjne, szczepienia i odrobaczanie z dbałością o każdy detal.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__header">
                    <span class="trust-item__index">02</span>
                    <div class="trust-item__icon">
                        <i data-lucide="award" aria-hidden="true"></i>
                    </div>
                </div>
                <h3 class="trust-item__title">Rodowody FPL / FIFe</h3>
                <p class="trust-item__desc">
                    Każdy kot posiada pełny, pięciopokoleniowy rodowód zarejestrowany w międzynarodowej organizacji felinologicznej.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__header">
                    <span class="trust-item__index">03</span>
                    <div class="trust-item__icon">
                        <i data-lucide="heart-handshake" aria-hidden="true"></i>
                    </div>
                </div>
                <h3 class="trust-item__title">Wsparcie Po Adopcji</h3>
                <p class="trust-item__desc">
                    Nie zostawiamy Cię samego — pomagamy w adaptacji, doradzamy żywieniowo i odpowiadamy na pytania przez cały okres.
                </p>
            </div>

            <div class="trust-item">
                <div class="trust-item__header">
                    <span class="trust-item__index">04</span>
                    <div class="trust-item__icon">
                        <i data-lucide="home" aria-hidden="true"></i>
                    </div>
                </div>
                <h3 class="trust-item__title">Domowa Atmosfera</h3>
                <p class="trust-item__desc">
                    Koty rosną z nami w domowym środowisku, są doskonale socjalizowane i naturalnie przyzwyczajone do obecności ludzi.
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
                    <x-frontend.blog-card :post="$post" />
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
            tile="parchment"
            eyebrow="Adopcja i Kontakt"
            headline="Zainteresowany naszymi kociętami?"
            description="Napisz do nas — chętnie odpowiemy na wszystkie pytania, doradzimy w wyborze rasy i umówimy kameralne spotkanie w naszej hodowli."
            buttonText="Skontaktuj się z nami"
            buttonHref="{{ route('contact') }}"
        />
    </div>

</x-frontend.shell>


