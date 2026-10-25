@php
    $catTitle = $animal->meta_title ?: ($animal->name . ' — ' . $animal->breed . ' | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii');
    $catDesc = $animal->meta_description ?: ($animal->short_description ?: ('Poznaj kota ' . $animal->name . ' (' . $animal->breed . ', ' . $animal->color . '). Domowa hodowla bez klatek, rodowód SHiOZ ZOOLANDIA, ' . ($animal->status === \App\Enums\AnimalStatus::Breeding ? 'dedykowane badania rasy' : 'po przebadanych rodzicach') . ' i zrównoważony charakter.'));
    $catOgImage = $animal->media ? $animal->media->url() : asset('og-default.jpg');
    $catMimeType = $animal->media && $animal->media->mime_type ? $animal->media->mime_type : 'image/jpeg';
@endphp

<x-frontend.shell
    :title="$catTitle"
    :meta-description="$catDesc"
    :og-title="$catTitle"
    :og-description="$catDesc"
    :og-image="$catOgImage"
    :og-image-type="$catMimeType"
    og-image-width="1200"
    og-image-height="630"
    og-type="profile"
>
    {{-- ============================================================
    1. PROFILE DETAIL SECTION
    ============================================================ --}}
    <section class="animal-profile" aria-label="Profil kota: {{ $animal->name }}"
        data-ga-page="animal_view"
        data-ga-animal-name="{{ $animal->name }}"
        data-ga-animal-breed="{{ $animal->breed }}"
        data-ga-animal-status="{{ $animal->status instanceof \BackedEnum ? $animal->status->value : ($animal->status ?? '') }}"
        data-ga-animal-slug="{{ $animal->slug ?? $animal->id }}"
    >

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
                            <x-frontend.picture
                                :src="$animal->media->url()"
                                :alt="$animal->name . ' — ' . $animal->breed"
                                class="animal-profile__main-image"
                                id="animal-main-photo"
                                width="1000"
                                height="750"
                                decoding="async"
                                loading="eager"
                                fetchpriority="high"
                                sizes="(max-width: 1024px) 100vw, 600px"
                                webp-widths="400,800,1200"
                            />
                        @else
                            <x-frontend.picture
                                src="{{ asset('image/hero/bars_studio.jpg') }}"
                                :alt="$animal->name . ' (Zdjęcie poglądowe)'"
                                class="animal-profile__main-image"
                                id="animal-main-photo"
                                width="1000"
                                height="750"
                                decoding="async"
                                loading="eager"
                                fetchpriority="high"
                                sizes="(max-width: 1024px) 100vw, 600px"
                                webp-widths="400,800,1200"
                            />
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
                                    const picture = mainImg.closest('picture');
                                    if (picture) {
                                        picture.querySelectorAll('source').forEach(s => s.remove());
                                    }
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
                            <x-frontend.badge :variant="$animal->pickupBadgeVariant()">
                                {{ $animal->pickupBadgeLabel() }}
                            </x-frontend.badge>
                            @if($timeline = $animal->pickupTimelineLabel())
                                <span class="animal-profile__badge-timeline">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5" aria-hidden="true"></i>
                                    {{ $animal->isOdchowany() ? 'Termin do ustalenia' : $timeline }}
                                </span>
                            @endif
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

                        @php
                            $isBreeding = $animal->status === \App\Enums\AnimalStatus::Breeding;
                            $breedLower = mb_strtolower($animal->breed ?? '');
                            if (str_contains($breedLower, 'bengal')) {
                                $breedHealthText = 'Badania genetyczne PRA-b, PK-Def n/n • Echo serca (HCM normal) • FIV/FeLV negatywny';
                            } elseif (str_contains($breedLower, 'brytyj') || str_contains($breedLower, 'british')) {
                                $breedHealthText = 'Badania genetyczne PKD n/n • Echo serca (HCM normal) • FIV/FeLV negatywny';
                            } elseif (str_contains($breedLower, 'syjam') || str_contains($breedLower, 'siam')) {
                                $breedHealthText = 'Badania genetyczne PRA n/n, GM1/GM2 • Kontrola kardiologiczna • FIV/FeLV negatywny';
                            } else {
                                $breedHealthText = 'Dedykowane badania genetyczne rasy • Echo serca • FIV/FeLV negatywny';
                            }
                        @endphp

                        <div class="animal-profile__meta-badges" style="display: flex; flex-wrap: wrap; gap: 8px 10px; align-items: center; margin-top: 14px;">
                            <div class="animal-meta-pill" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; background: rgba(200, 157, 92, 0.08); border: 1px solid rgba(200, 157, 92, 0.28); font-size: 0.84rem; color: #3a3a3c; font-weight: 500;">
                                <span aria-hidden="true">🎂</span>
                                <span><strong>Urodzony:</strong> {{ $animal->date_of_birth ? $animal->date_of_birth->format('d.m.Y') : 'Wiek adult' }} ({{ $animal->age() ?? 'Dorosły kot' }})</span>
                            </div>

                            <div class="animal-meta-pill" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; background: rgba(52, 199, 89, 0.08); border: 1px solid rgba(52, 199, 89, 0.28); font-size: 0.84rem; color: #166534; font-weight: 500;">
                                <span aria-hidden="true">🏡</span>
                                <span>100% Wychowanie domowe bez klatek</span>
                            </div>

                            <div class="animal-meta-pill" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; background: rgba(200, 157, 92, 0.08); border: 1px solid rgba(200, 157, 92, 0.28); font-size: 0.84rem; color: #3a3a3c; font-weight: 500;">
                                <span aria-hidden="true">🩺</span>
                                @if($isBreeding)
                                    <span title="Badania dedykowane dla rasy">{{ $breedHealthText }}</span>
                                @elseif($animal->isEarlyReservation() || $animal->isInPickupWindow())
                                    <span>Linia po przebadanych rodzicach • Odbiór w wieku 12–14 tyg. ({{ $animal->pickupWindowFormatted() }})</span>
                                @else
                                    <span>Linia po przebadanych rodzicach • Szczepienia, microchip i rodowód</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Editorial Spec Cards Grid: Compact 3x2 Architecture --}}
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
                                <span class="animal-spec-card__label">Umaszczenie</span>
                                <i data-lucide="palette" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">{{ $animal->color }}</span>
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
                                <span class="animal-spec-card__label">Odbiór</span>
                                <i data-lucide="calendar-check" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">
                                @if($animal->pickupTimelineLabel())
                                    {{ $animal->pickupTimelineLabel() }}
                                @else
                                    {{ $animal->pickupBadgeLabel() }}
                                @endif
                            </span>
                        </div>
                        <div class="animal-spec-card" role="listitem">
                            <div class="animal-spec-card__top">
                                <span class="animal-spec-card__label">Rodowód</span>
                                <i data-lucide="award" class="animal-spec-card__icon" aria-hidden="true"></i>
                            </div>
                            <span class="animal-spec-card__value">SHiOZ ZOOLANDIA</span>
                        </div>
                    </div>

                    {{-- Luxury Price Banner (Only for available/reservable kittens with price) placed right above description --}}
                    <x-frontend.animal-price-box :animal="$animal" />

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

                        @if($animal->isEarlyReservation() || $animal->isInPickupWindow())
                            <div style="margin-top: 14px; padding: 12px 16px; background: rgba(200, 157, 92, 0.08); border-left: 3px solid var(--color-gold, #c89d5c); border-radius: 6px; font-size: 0.88rem; line-height: 1.5; color: var(--color-ink-muted-80, #d1cfc7);">
                                <strong>Zasada odbioru:</strong> Zgodnie ze standardami felinologii kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po pełnej domowej socjalizacji i komplecie szczepień.
                                @if($animal->isEarlyReservation())
                                    Kotek jest obecnie dostępny do rezerwacji — <strong>{{ mb_strtolower($animal->pickupTimelineLabel()) }}</strong>
                                    @if($animal->pickupWindowFormatted())
                                        (przewidywane okno odbioru: <strong>{{ $animal->pickupWindowFormatted() }}</strong>).
                                    @endif
                                @else
                                    Kotek ukończył już 12. tydzień życia i jest <strong>gotowy do odbioru od zaraz</strong>.
                                @endif
                            </div>
                        @elseif($animal->isOdchowany())
                            <div style="margin-top: 14px; padding: 12px 16px; background: rgba(200, 157, 92, 0.08); border-left: 3px solid var(--color-gold, #c89d5c); border-radius: 6px; font-size: 0.88rem; line-height: 1.5; color: var(--color-ink-muted-80, #d1cfc7);">
                                <strong>Zasada odbioru:</strong> Kotek jest w pełni odchowany, po komplecie szczepień i socjalizacji — <strong>gotowy do odbioru</strong>, dokładny termin do bezpośredniego ustalenia z hodowlą.
                            </div>
                        @endif
                    </div>

                    @php
                        $priceText = $animal->formattedPrice() ? ' (' . $animal->formattedPrice() . ')' : '';
                        $inquiryMsg = "Dzień dobry! Piszę w sprawie kociaka: {$animal->name}{$priceText}. Czy jest aktualnie wolny?";
                        $messengerInquiryUrl = 'https://m.me/61580668026948?text=' . urlencode($inquiryMsg);
                        $whatsappInquiryUrl = 'https://wa.me/48514153204?text=' . urlencode($inquiryMsg);
                    @endphp

                    {{-- Actions: Messenger-First, WhatsApp Secondary --}}
                    <div class="animal-profile__actions">
                        <a href="{{ $messengerInquiryUrl }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="animal-profile__btn--messenger"
                           onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: '{{ addslashes($animal->name) }}', method: 'Messenger' }); }">
                            💬 Zapytaj o kociaka lub umów wideo-wizytę (Messenger)
                        </a>

                        <a href="{{ $whatsappInquiryUrl }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="animal-profile__btn--whatsapp"
                           onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: '{{ addslashes($animal->name) }}', method: 'WhatsApp' }); }">
                            WhatsApp (+48 514 153 204)
                        </a>

                        @if(isset($children) && $children->isNotEmpty())
                            <x-frontend.button href="#potomstwo" variant="secondary" icon="users">
                                Zobacz potomstwo ({{ $children->count() }})
                            </x-frontend.button>
                        @endif

                        @if($animal->mother || $animal->father)
                            <x-frontend.button href="#rodowod" variant="secondary" icon="git-branch">
                                Zobacz rodowód
                            </x-frontend.button>
                        @endif
                    </div>

                    {{-- 5-min Live Video-Visit Callout Box --}}
                    <div class="animal-profile__video-callout">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem; line-height: 1;">📹</span>
                            <div>
                                <strong style="display: block; font-size: 0.95rem; color: var(--color-ink, #ffffff);">Zobacz kota na żywo (Wideo-wizyta)</strong>
                                <span style="font-size: 0.82rem; color: var(--color-ink-muted-80, #a1a1a6);">Połącz się bezpośrednio przez Messenger lub WhatsApp i poznaj kociaka w domowym otoczeniu</span>
                            </div>
                        </div>
                        <button type="button" 
                            class="btn btn-secondary" 
                            style="font-size: 0.82rem; padding: 8px 14px; white-space: nowrap;"
                            onclick="window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($animal->pickupBadgeLabel()) }}')">
                            Połącz się teraz →
                        </button>
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
    2. OFFSPRING / KITTENS FROM THIS PARENT (If present)
    ============================================================ --}}
    @if(isset($children) && $children->isNotEmpty())
        <x-frontend.section id="potomstwo" class="reveal-up">
            <x-frontend.section-header eyebrow="Rodzina i Mioty" headline="Potomstwo kota {{ $animal->name }}"
                description="Kocięta urodzone po tym rodzicu w naszej hodowli — aktualnie dostępne do rezerwacji oraz te, które znalazły już swoje kochające domy." />

            <div class="animals-grid">
                @foreach($children as $child)
                    <x-frontend.animal-card :animal="$child" />
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
    3. PEDIGREE & GENEALOGY (If parents present)
    ============================================================ --}}
    <x-frontend.animal-pedigree :mother="$animal->mother" :father="$animal->father" />

    {{-- ============================================================
    4. HEALTH & STANDARD TRUST
    ============================================================ --}}
    <x-frontend.animal-health-panel />

    {{-- ============================================================
    5. RELATED ANIMALS (Same breed / available)
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

    <div class="reveal-up">
        <x-frontend.section tile="parchment" padding="default" class="cta-section">
            <div class="cta-block">
                <div class="cta-block__eyebrow">
                    <span>Wideo-Wizyta na żywo & Rezerwacja</span>
                </div>
                <h2 class="cta-block__headline">
                    Zainteresowany kotem {{ $animal->name }}?
                </h2>
                <p class="cta-block__description">
                    Połącz się z nami na 5-minutową wideo-wizytę na żywo (WhatsApp lub Messenger) albo porozmawiajmy telefonicznie. Chętnie pokażemy malucha w domowym otoczeniu, przedstawimy certyfikaty zdrowia rodziców i odpowiemy na wszystkie Twoje pytania.
                </p>
                <div class="cta-block__action" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <x-frontend.button
                        type="button"
                        variant="primary"
                        icon="arrow-right"
                        onclick="window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($animal->pickupBadgeLabel()) }}')">
                        📹 Umów wideo-wizytę / Zarezerwuj
                    </x-frontend.button>
                    <x-frontend.button
                        href="tel:+48514153204"
                        variant="secondary"
                        icon="phone">
                        Zadzwoń: +48 514 153 204
                    </x-frontend.button>
                </div>
                <p class="cta-block__note" role="note" style="margin-top: 16px;">
                    <i data-lucide="shield-check" aria-hidden="true" class="cta-block__note-icon"></i>
                    <span>Certyfikat SHiOZ ZOOLANDIA (58/CW/2025) · Czysta linia hodowlana z rodowodem</span>
                </p>
            </div>
        </x-frontend.section>
    </div>

    @push('schema')
    @php
        $mainEntity = array_filter([
            '@type' => $animal->shouldShowPrice() ? 'Product' : 'Animal',
            'name' => $animal->name,
            'breed' => $animal->breed ?: null,
            'description' => $animal->short_description ?: ($animal->description ?: null),
            'image' => $animal->media ? $animal->media->url() : null,
            'url' => route('frontend.animals.show', $animal),
        ]);

        if ($animal->shouldShowPrice()) {
            $mainEntity['offers'] = [
                '@type' => 'Offer',
                'price' => number_format((float) $animal->price, 2, '.', ''),
                'priceCurrency' => 'PLN',
                'availability' => $animal->status === \App\Enums\AnimalStatus::Available ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
                'url' => route('frontend.animals.show', $animal),
            ];
        }

        $animalSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemPage',
            'name' => $animal->name . ($animal->breed ? ' — ' . $animal->breed : ''),
            'url' => route('frontend.animals.show', $animal),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name', 'Domowa Hodowla Kotów z Mazowieckiej Szwajcarii'),
                'url' => url('/'),
            ],
            'mainEntity' => $mainEntity,
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($animalSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush

</x-frontend.shell>