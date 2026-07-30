<x-frontend.shell
    title="{{ $animal->name }} — {{ $animal->breed }} | {{ config('app.name') }}"
    meta-description="{{ $animal->short_description ?: 'Poznaj kota ' . $animal->name . ' (' . $animal->breed . ', ' . $animal->color . '). Doskonały rodowód i zdrowie.' }}"
>
    {{-- ============================================================
         1. PROFILE DETAIL SECTION
         ============================================================ --}}
    <section class="animal-profile" aria-label="Profil kota: {{ $animal->name }}">
        <div class="section-inner">
            {{-- Editorial Breadcrumbs --}}
            <nav class="animal-profile__breadcrumbs" aria-label="Nawigacja okruszkowa">
                <a href="{{ route('home') }}" class="animal-profile__breadcrumb-link">Strona Główna</a>
                <span class="animal-profile__breadcrumb-sep" aria-hidden="true">/</span>
                <a href="{{ route('frontend.animals.index') }}" class="animal-profile__breadcrumb-link">Nasze Koty</a>
                <span class="animal-profile__breadcrumb-sep" aria-hidden="true">/</span>
                <a href="{{ route('frontend.animals.index', ['breed' => $animal->breed]) }}" class="animal-profile__breadcrumb-link">{{ $animal->breed }}</a>
                <span class="animal-profile__breadcrumb-sep" aria-hidden="true">/</span>
                <span class="text-ink">{{ $animal->name }}</span>
            </nav>

            {{-- Back Navigation --}}
            <div class="animal-profile__back">
                <a
                    href="{{ route('frontend.animals.index') }}"
                    class="text-nav animal-profile__back-link"
                >
                    <i data-lucide="arrow-left" aria-hidden="true"></i>
                    Wróć do listy kotów
                </a>
            </div>

            <div class="animal-profile__grid">
                {{-- LEFT COLUMN: Photo & Gallery --}}
                <div class="animal-profile__gallery">
                    <div class="animal-profile__main-image-wrap" id="animal-gallery-main">
                        @if($animal->media)
                            <img
                                src="{{ $animal->media->url() }}"
                                alt="{{ $animal->name }} — {{ $animal->breed }}"
                                class="animal-profile__main-image"
                                id="animal-main-photo"
                                width="1000"
                                height="750"
                                decoding="async"
                                loading="eager"
                                fetchpriority="high"
                            >
                        @else
                            <img 
                                src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=1200&q=80" 
                                alt="{{ $animal->name }} (Zdjęcie poglądowe)"
                                class="animal-profile__main-image"
                                id="animal-main-photo"
                                width="1000"
                                height="750"
                                decoding="async"
                                loading="eager"
                                fetchpriority="high"
                            >
                        @endif
                    </div>

                    @if($animal->gallery && $animal->gallery->isNotEmpty())
                        <div class="animal-profile__thumbs" role="list" aria-label="Galeria zdjęć kota {{ $animal->name }}">
                            @if($animal->media)
                                <div
                                    class="animal-profile__thumb animal-profile__thumb--active"
                                    role="button"
                                    tabindex="0"
                                    aria-label="Wyświetl zdjęcie główne"
                                    data-image-src="{{ $animal->media->url() }}"
                                    data-image-alt="{{ $animal->name }} — {{ $animal->breed }}"
                                    onclick="switchMainAnimalPhoto(this)"
                                    onkeydown="if(event.key === 'Enter' || event.key === ' '){ switchMainAnimalPhoto(this); event.preventDefault(); }"
                                >
                                    <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" width="100" height="100" decoding="async" loading="lazy">
                                </div>
                            @endif

                            @foreach($animal->gallery as $index => $img)
                                <div
                                    class="animal-profile__thumb"
                                    role="button"
                                    tabindex="0"
                                    aria-label="Wyświetl zdjęcie {{ $index + 1 }} z galerii"
                                    data-image-src="{{ $img->url() }}"
                                    data-image-alt="{{ $animal->name }} — {{ $animal->breed }} (zdjęcie {{ $index + 1 }})"
                                    onclick="switchMainAnimalPhoto(this)"
                                    onkeydown="if(event.key === 'Enter' || event.key === ' '){ switchMainAnimalPhoto(this); event.preventDefault(); }"
                                >
                                    <img src="{{ $img->url() }}" alt="{{ $animal->name }}" width="100" height="100" decoding="async" loading="lazy">
                                </div>
                            @endforeach
                        </div>

                        <script>
                            function switchMainAnimalPhoto(el) {
                                const mainImg = document.getElementById('animal-main-photo');
                                if (!mainImg) return;
                                const src = el.getAttribute('data-image-src');
                                const alt = el.getAttribute('data-image-alt');
                                if (src) {
                                    mainImg.src = src;
                                    if (alt) mainImg.alt = alt;
                                }
                                document.querySelectorAll('.animal-profile__thumb').forEach(t => t.classList.remove('animal-profile__thumb--active'));
                                el.classList.add('animal-profile__thumb--active');
                            }
                        </script>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Details & Specs --}}
                <div class="animal-profile__info">
                    <div class="animal-profile__header">
                        <div class="animal-profile__badges">
                            <x-frontend.badge :variant="$animal->status->badgeVariant()">
                                {{ $animal->status->label() }}
                            </x-frontend.badge>
                            <span class="animal-profile__badge-pedigree">
                                <i data-lucide="award" aria-hidden="true" width="14" height="14"></i>
                                Rodowód FIFe / FPL
                            </span>
                            <span class="animal-profile__badge-gender text-ink-muted-80">
                                {{ $animal->gender->symbol() }} {{ $animal->gender->label() }}
                            </span>
                        </div>

                        <h1 class="text-hero-display animal-profile__name">
                            {{ $animal->name }}
                        </h1>
                        <p class="animal-profile__breed">
                            {{ $animal->breed }} • {{ $animal->color }}
                        </p>

                        <div class="animal-profile__meta-line">
                            <span>Data urodzenia: {{ $animal->date_of_birth ? $animal->date_of_birth->format('d.m.Y') : 'Wiek adult' }} ({{ $animal->age() ?? 'Dorosły kot' }})</span>
                            <span aria-hidden="true">·</span>
                            <span>Badania genetyczne 100% HCM / PKD n/n</span>
                        </div>
                    </div>

                    {{-- Editorial Spec Cards Grid --}}
                    <div class="animal-profile__specs-grid" role="list" aria-label="Specyfikacja kota">
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Rasa</span>
                                <i data-lucide="cat" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->breed }}</span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Płeć</span>
                                <i data-lucide="user-check" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->gender->label() }}</span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Wiek</span>
                                <i data-lucide="calendar" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->age() ?? 'Dorosły kot' }}</span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Umaszczenie</span>
                                <i data-lucide="palette" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->color }}</span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Status</span>
                                <i data-lucide="shield-check" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->status->label() }}</span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Rodowód</span>
                                <i data-lucide="award" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">FPL / FIFe</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="animal-profile__description">
                        @if($animal->description)
                            <div class="text-body text-ink-muted-80">
                                {!! nl2br(e($animal->description)) !!}
                            </div>
                        @elseif($animal->short_description)
                            <p class="text-body text-ink-muted-80">
                                {{ $animal->short_description }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="animal-profile__actions">
                        <x-frontend.button
                            href="{{ route('contact', ['subject' => 'Zapytanie o kota: ' . $animal->name]) }}"
                            icon="mail"
                        >
                            Zapytaj o tego kota
                        </x-frontend.button>

                        @if($animal->mother || $animal->father)
                            <x-frontend.button
                                href="#rodowod"
                                variant="secondary"
                                icon="git-branch"
                            >
                                Zobacz rodowód
                            </x-frontend.button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. PEDIGREE & GENEALOGY (If parents present)
         ============================================================ --}}
    <x-frontend.animal-pedigree :mother="$animal->mother" :father="$animal->father" />

    {{-- ============================================================
         3. HEALTH & STANDARD TRUST
         ============================================================ --}}
    <x-frontend.animal-health-panel />

    {{-- ============================================================
         4. RELATED ANIMALS (Same breed / available)
         ============================================================ --}}
    @if($relatedAnimals->isNotEmpty())
        <x-frontend.section id="inne-koty" class="reveal-up">
            <x-frontend.section-header
                eyebrow="Oferta"
                headline="Poznaj też inne nasze koty"
                description="Koty z naszej hodowli o podobnym charakterze lub z pokrewnych linii genetycznych."
            />

            <div class="animals-grid">
                @foreach($relatedAnimals as $related)
                    <x-frontend.animal-card :animal="$related" />
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
         5. FINAL CTA
         ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta
            tile="parchment"
            eyebrow="Adopcja i Kontakt"
            headline="Zainteresowany adopcją {{ $animal->name }}?"
            description="Napisz do nas — z przyjemnością odpowiemy na wszystkie pytania na temat wybranego kota, przedstawimy certyfikaty zdrowia rodziców i zaprosimy Cię na spotkanie."
            buttonText="Zapytaj o {{ $animal->name }}"
            buttonHref="{{ route('contact', ['subject' => 'Zapytanie o kota: ' . $animal->name]) }}"
        />
    </div>

</x-frontend.shell>
