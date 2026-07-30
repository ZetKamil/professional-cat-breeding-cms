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
                            <span class="text-nav text-ink-muted-48 animal-profile__badge-gender">
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
            />

            <div class="animals-grid">
                @foreach($relatedAnimals as $related)
                    <x-frontend.animal-card :animal="$related" />
                @endforeach
            </div>
        </x-frontend.section>
    @endif

</x-frontend.shell>
