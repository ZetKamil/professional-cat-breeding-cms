<x-frontend.shell
    title="{{ $animal->name }} — {{ $animal->breed }} | {{ config('app.name') }}"
    meta-description="{{ $animal->short_description ?: 'Poznaj kota ' . $animal->name . ' (' . $animal->breed . ', ' . $animal->color . '). Doskonały rodowód i zdrowie.' }}"
>
    {{-- ============================================================
         1. PROFILE DETAIL SECTION
         ============================================================ --}}
    <section class="animal-profile" aria-label="Profil kota: {{ $animal->name }}">
        <div class="section-inner">
            {{-- Back Navigation --}}
            <div style="margin-bottom: var(--sp-xl);">
                <a
                    href="{{ route('frontend.animals.index') }}"
                    class="text-nav"
                    style="display: inline-flex; align-items: center; gap: 6px; color: var(--color-ink-muted-80);"
                >
                    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
                    Wróć do listy kotów
                </a>
            </div>

            <div class="animal-profile__grid">
                {{-- LEFT COLUMN: Photo & Gallery --}}
                <div class="animal-profile__gallery">
                    <div class="animal-profile__main-image-wrap">
                        @if($animal->media)
                            <img
                                src="{{ $animal->media->url() }}"
                                alt="{{ $animal->name }} - {{ $animal->breed }}"
                                class="animal-profile__main-image"
                                loading="eager"
                                fetchpriority="high"
                            >
                        @else
                            <img 
                                src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=1200&q=80" 
                                alt="{{ $animal->name }} (Zdjęcie poglądowe)"
                                class="animal-profile__main-image"
                                loading="eager"
                                fetchpriority="high"
                            >
                        @endif
                    </div>

                    @if($animal->gallery && $animal->gallery->isNotEmpty())
                        <div class="animal-profile__thumbs">
                            @foreach($animal->gallery as $img)
                                <div class="animal-profile__thumb">
                                    <img src="{{ $img->url() }}" alt="{{ $animal->name }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Details & Specs --}}
                <div class="animal-profile__info">
                    <div class="animal-profile__header">
                        <div class="animal-profile__badges">
                            <x-frontend.badge :variant="$animal->status->badgeVariant()">
                                {{ $animal->status->label() }}
                            </x-frontend.badge>
                            <span class="text-nav text-ink-muted-48" style="display: inline-flex; align-items: center; gap: 4px;">
                                {{ $animal->gender->symbol() }} {{ $animal->gender->label() }}
                            </span>
                        </div>

                        <h1 class="text-hero-display animal-profile__name">
                            {{ $animal->name }}
                        </h1>
                        <p class="animal-profile__breed">
                            {{ $animal->breed }} • {{ $animal->color }}
                        </p>
                    </div>

                    {{-- Spec Grid --}}
                    <div class="animal-profile__specs">
                        <div class="animal-spec">
                            <span class="animal-spec__label">Rasa</span>
                            <span class="animal-spec__value">{{ $animal->breed }}</span>
                        </div>
                        <div class="animal-spec">
                            <span class="animal-spec__label">Płeć</span>
                            <span class="animal-spec__value">{{ $animal->gender->label() }}</span>
                        </div>
                        <div class="animal-spec">
                            <span class="animal-spec__label">Wiek</span>
                            <span class="animal-spec__value">{{ $animal->age() ?? 'Dorosły kot' }}</span>
                        </div>
                        <div class="animal-spec">
                            <span class="animal-spec__label">Umaszczenie</span>
                            <span class="animal-spec__value">{{ $animal->color }}</span>
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
    @if($animal->mother || $animal->father)
        <x-frontend.section tile="parchment" id="rodowod" class="reveal-up">
            <x-frontend.section-header
                eyebrow="Genealogia"
                headline="Rodzice i Rodowód"
                description="Nasza linia hodowlana opiera się na wyselekcjonowanych, wielopokoleniowych rodowodach FIFe/FPL."
            />

            <div class="pedigree-grid">
                @if($animal->mother)
                    <a
                        href="{{ route('frontend.animals.show', $animal->mother) }}"
                        class="pedigree-card"
                    >
                        <div class="pedigree-card__icon">
                            <i data-lucide="heart" aria-hidden="true"></i>
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Matka (Queen)</span>
                            <span class="pedigree-card__name">{{ $animal->mother->name }}</span>
                            <span class="pedigree-card__breed">{{ $animal->mother->breed }} • {{ $animal->mother->color }}</span>
                        </div>
                    </a>
                @endif

                @if($animal->father)
                    <a
                        href="{{ route('frontend.animals.show', $animal->father) }}"
                        class="pedigree-card"
                    >
                        <div class="pedigree-card__icon">
                            <i data-lucide="shield" aria-hidden="true"></i>
                        </div>
                        <div class="pedigree-card__info">
                            <span class="pedigree-card__role">Ojciec (Stud)</span>
                            <span class="pedigree-card__name">{{ $animal->father->name }}</span>
                            <span class="pedigree-card__breed">{{ $animal->father->breed }} • {{ $animal->father->color }}</span>
                        </div>
                    </a>
                @endif
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
         3. HEALTH & STANDARD TRUST
         ============================================================ --}}
    <x-frontend.section tile="dark" id="zdrowie" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Zaufanie"
            headline="Standard Zdrowotny"
            description="Każdy kot w naszej hodowli posiada kompletne badania i certyfikaty."
        />

        <div class="trust-grid">
            <div class="trust-pillar">
                <div class="trust-pillar__icon">
                    <i data-lucide="dna" aria-hidden="true"></i>
                </div>
                <h3 class="trust-pillar__title text-body-strong">Badania Genetyczne</h3>
                <p class="trust-pillar__desc text-body">
                    Rodzice są wolni od chorób genetycznych właściwych dla rasy (HCM, PKD, SMA n/n).
                </p>
            </div>
            <div class="trust-pillar">
                <div class="trust-pillar__icon">
                    <i data-lucide="shield-check" aria-hidden="true"></i>
                </div>
                <h3 class="trust-pillar__title text-body-strong">FIV / FeLV Negatywny</h3>
                <p class="trust-pillar__desc text-body">
                    Hodowla jest pod stałą opieką weterynaryjną. Wolni od wirusa niedoboru odporności i białaczki.
                </p>
            </div>
            <div class="trust-pillar">
                <div class="trust-pillar__icon">
                    <i data-lucide="scroll-text" aria-hidden="true"></i>
                </div>
                <h3 class="trust-pillar__title text-body-strong">Rodowód FIFe</h3>
                <p class="trust-pillar__desc text-body">
                    Pełna dokumentacja, książeczka zdrowia, mikroczip oraz pięciopokoleniowy rodowód FPL/FIFe.
                </p>
            </div>
            <div class="trust-pillar">
                <div class="trust-pillar__icon">
                    <i data-lucide="home" aria-hidden="true"></i>
                </div>
                <h3 class="trust-pillar__title text-body-strong">Domowa Socjalizacja</h3>
                <p class="trust-pillar__desc text-body">
                    Koty wychowują się w domowym zaciszu, przyzwyczajone do codziennych odgłosów i miłości człowieka.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         4. RELATED ANIMALS (Same breed / available)
         ============================================================ --}}
    @if($relatedAnimals->isNotEmpty())
        <x-frontend.section id="inne-koty" class="reveal-up">
            <x-frontend.section-header
                eyebrow="Oferta"
                headline="Poznaj też inne nasze koty"
            />

            <div class="animals-grid">
                @foreach($relatedAnimals as $related)
                    <x-frontend.animal-card :animal="$related" />
                @endforeach
            </div>
        </x-frontend.section>
    @endif

</x-frontend.shell>
