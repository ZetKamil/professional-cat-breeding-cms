<x-frontend.shell
    title="{{ $animal->meta_title ?: ($animal->name . ' — ' . $animal->breed . ' | Hodowla Kotów z Mazowieckiej Szwajcarii') }}"
    meta-description="{{ $animal->meta_description ?: ($animal->short_description ?: ('Poznaj kota ' . $animal->name . ' (' . $animal->breed . ', ' . $animal->color . '). Rodowód stowarzyszenia, badania genetyczne i zrównoważony charakter.')) }}"
    og-image="{{ $animal->media ? $animal->media->url() : asset('storage/media/parent_bella_1.jpg') }}"
    og-type="profile"
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
                <a href="{{ route('frontend.animals.index', ['breed' => $animal->breed]) }}"
                    class="animal-profile__breadcrumb-link">{{ $animal->breed }}</a>
                <span class="animal-profile__breadcrumb-sep" aria-hidden="true">/</span>
                <span class="text-ink">{{ $animal->name }}</span>
            </nav>

            {{-- Back Navigation --}}
            <div class="animal-profile__back">
                <a href="{{ route('frontend.animals.index') }}" class="text-nav animal-profile__back-link">
                    <i data-lucide="arrow-left" aria-hidden="true"></i>
                    Wróć do listy kotów
                </a>
            </div>

            <div class="animal-profile__grid">
                {{-- LEFT COLUMN: Photo & Gallery --}}
                <div class="animal-profile__gallery">
                    <div class="animal-profile__main-image-wrap" id="animal-gallery-main" role="button" tabindex="0"
                        aria-label="Powiększ zdjęcie kota {{ $animal->name }}"
                        onclick="openAnimalLightbox()"
                        onkeydown="if(event.key === 'Enter' || event.key === ' '){ openAnimalLightbox(); event.preventDefault(); }"
                        title="Kliknij lub naciśnij Enter, aby powiększyć zdjęcie na pełnym ekranie">
                        @if($animal->media)
                            <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }} — {{ $animal->breed }}"
                                class="animal-profile__main-image" id="animal-main-photo" width="1000" height="750"
                                decoding="async" loading="eager" fetchpriority="high">
                        @else
                            <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=1200&q=80"
                                alt="{{ $animal->name }} (Zdjęcie poglądowe)" class="animal-profile__main-image"
                                id="animal-main-photo" width="1000" height="750" decoding="async" loading="eager"
                                fetchpriority="high">
                        @endif
                        <div class="animal-profile__zoom-hint" aria-hidden="true">
                            <i data-lucide="maximize-2" class="w-3.5 h-3.5" aria-hidden="true"></i>
                            <span>Powiększ</span>
                        </div>
                    </div>

                    @if($animal->gallery && $animal->gallery->isNotEmpty())
                        <div class="animal-profile__thumbs" role="list" aria-label="Galeria zdjęć kota {{ $animal->name }}">
                            @if($animal->media)
                                <div class="animal-profile__thumb animal-profile__thumb--active" role="button" tabindex="0"
                                    aria-label="Wyświetl zdjęcie główne" data-image-src="{{ $animal->media->url() }}"
                                    data-image-alt="{{ $animal->name }} — {{ $animal->breed }}"
                                    onclick="switchMainAnimalPhoto(this)"
                                    onkeydown="if(event.key === 'Enter' || event.key === ' '){ switchMainAnimalPhoto(this); event.preventDefault(); }">
                                    <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" width="100" height="100"
                                        decoding="async" loading="lazy">
                                </div>
                            @endif

                            @foreach($animal->gallery as $index => $img)
                                @if($animal->media && $img->id === $animal->media->id)
                                    @continue
                                @endif
                                <div class="animal-profile__thumb" role="button" tabindex="0"
                                    aria-label="Wyświetl zdjęcie {{ $index + 1 }} z galerii" data-image-src="{{ $img->url() }}"
                                    data-image-alt="{{ $animal->name }} — {{ $animal->breed }} (zdjęcie {{ $index + 1 }})"
                                    onclick="switchMainAnimalPhoto(this)"
                                    onkeydown="if(event.key === 'Enter' || event.key === ' '){ switchMainAnimalPhoto(this); event.preventDefault(); }">
                                    <img src="{{ $img->url() }}" alt="{{ $animal->name }}" width="100" height="100"
                                        decoding="async" loading="lazy">
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

                            function openAnimalLightbox() {
                                const mainImg = document.getElementById('animal-main-photo');
                                if (!mainImg || !mainImg.src) return;
                                
                                let modal = document.getElementById('animal-lightbox-modal');
                                if (!modal) {
                                    modal = document.createElement('div');
                                    modal.id = 'animal-lightbox-modal';
                                    modal.setAttribute('role', 'dialog');
                                    modal.setAttribute('aria-modal', 'true');
                                    modal.setAttribute('aria-label', 'Powiększone zdjęcie kota');
                                    modal.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.92);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;';
                                    modal.innerHTML = `
                                        <div style="position:relative;max-width:95vw;max-height:95vh;">
                                            <img id="animal-lightbox-img" src="" alt="Powiększone zdjęcie" style="max-width:95vw;max-height:92vh;object-fit:contain;border-radius:8px;box-shadow:0 20px 40px rgba(0,0,0,0.8);">
                                            <button id="animal-lightbox-close" type="button" aria-label="Zamknij powiększenie zdjęcia" style="position:absolute;top:-15px;right:-15px;background:#fff;border:none;border-radius:50%;width:40px;height:40px;font-size:22px;font-weight:bold;cursor:pointer;box-shadow:0 4px 10px rgba(0,0,0,0.3);">&times;</button>
                                        </div>
                                    `;
                                    modal.onclick = function(e) {
                                        if (e.target === modal || e.target.id === 'animal-lightbox-close') {
                                            closeAnimalLightbox();
                                        }
                                    };
                                    document.body.appendChild(modal);

                                    document.addEventListener('keydown', function(e) {
                                        if (e.key === 'Escape' && modal.style.display === 'flex') {
                                            closeAnimalLightbox();
                                        }
                                    });
                                }
                                document.getElementById('animal-lightbox-img').src = mainImg.src;
                                modal.style.display = 'flex';
                                const closeBtn = document.getElementById('animal-lightbox-close');
                                if (closeBtn) closeBtn.focus();
                            }

                            function closeAnimalLightbox() {
                                const modal = document.getElementById('animal-lightbox-modal');
                                if (modal) {
                                    modal.style.display = 'none';
                                    const trigger = document.getElementById('animal-gallery-main');
                                    if (trigger) trigger.focus();
                                }
                            }
                        </script>
                    @endif
                </div>

                {{-- RIGHT COLUMN: Details & Specs --}}
                <div class="animal-profile__info">
                    <div class="animal-profile__header">
                        <div class="animal-profile__badges">
                            <x-frontend.badge :variant="$animal->status->badgeVariant()">
                                {{ $animal->statusLabel() }}
                            </x-frontend.badge>
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
                            <span>Data urodzenia:
                                {{ $animal->date_of_birth ? $animal->date_of_birth->format('d.m.Y') : 'Wiek adult' }}
                                ({{ $animal->age() ?? 'Dorosły kot' }})</span>
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
                        <div class="animal-spec-card animal-spec-card--wide" role="listitem">
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
                            <span class="animal-spec-card__value">{{ $animal->statusLabel() }}</span>
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
                            icon="mail">
                            Zapytaj o tego kota
                        </x-frontend.button>

                        @if($animal->mother || $animal->father)
                            <x-frontend.button href="#rodowod" variant="secondary" icon="git-branch">
                                Zobacz rodowód
                            </x-frontend.button>
                        @endif
                    </div>

                    {{-- Contextual Facebook Daily Life Discovery --}}
                    <div class="animal-profile__social-note">
                        <span class="animal-profile__social-text">Chcesz zobaczyć więcej codziennych zdjęć i
                            filmów?</span>
                        <a href="https://www.facebook.com/profile.php?id=61580668026948" target="_blank" rel="noopener"
                            class="animal-profile__social-link">
                            Zobacz nas na Facebooku →
                        </a>
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
            <x-frontend.section-header eyebrow="Oferta" headline="Poznaj też inne nasze koty"
                description="Koty z naszej hodowli o podobnym charakterze lub z pokrewnych linii genetycznych." />

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
        <x-frontend.cta tile="parchment" eyebrow="Kontakt i Rezerwacja"
            headline="Zainteresowany kotem {{ $animal->name }}?"
            description="Napisz do nas — z przyjemnością odpowiemy na wszystkie pytania na temat wybranego kota, przedstawimy certyfikaty zdrowia rodziców i zaprosimy Cię na spotkanie."
            buttonText="Zapytaj o {{ $animal->name }}"
            buttonHref="{{ route('contact', ['subject' => 'Zapytanie o kota: ' . $animal->name]) }}" />
    </div>

    @push('schema')
    @php
        $animalSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemPage',
            'name' => $animal->name . ($animal->breed ? ' — ' . $animal->breed : ''),
            'url' => route('frontend.animals.show', $animal),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name', 'Hodowla Kotów z Mazowieckiej Szwajcarii'),
                'url' => url('/'),
            ],
            'mainEntity' => array_filter([
                '@type' => 'Animal',
                'name' => $animal->name,
                'breed' => $animal->breed ?: null,
                'description' => $animal->short_description ?: ($animal->description ?: null),
                'image' => $animal->media ? $animal->media->url() : null,
                'url' => route('frontend.animals.show', $animal),
            ]),
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($animalSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush

</x-frontend.shell>