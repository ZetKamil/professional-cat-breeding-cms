@php
    $breedTitles = [
        'Kot Bengalski' => 'Koty Bengalskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'bengal' => 'Koty Bengalskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'bengalskie' => 'Koty Bengalskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'Kot Brytyjski' => 'Koty Brytyjskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'british' => 'Koty Brytyjskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'brytyjskie' => 'Koty Brytyjskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'brytyjczyki' => 'Koty Brytyjskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'Kot Syjamski' => 'Koty Syjamskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'siamese' => 'Koty Syjamskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
        'syjamskie' => 'Koty Syjamskie — Kocięta z Rodowodem | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii',
    ];
    $breedOgImages = [
        'Kot Bengalski' => asset('og-bengal.jpg'),
        'bengal' => asset('og-bengal.jpg'),
        'bengalskie' => asset('og-bengal.jpg'),
        'Kot Brytyjski' => asset('og-british.jpg'),
        'british' => asset('og-british.jpg'),
        'brytyjskie' => asset('og-british.jpg'),
        'brytyjczyki' => asset('og-british.jpg'),
        'Kot Syjamski' => asset('og-siamese.jpg'),
        'siamese' => asset('og-siamese.jpg'),
        'syjamskie' => asset('og-siamese.jpg'),
    ];
    $pageTitle = ($currentBreed && isset($breedTitles[$currentBreed]))
        ? $breedTitles[$currentBreed]
        : 'Nasze Koty — Kocięta z Domowej Hodowli | Koty Bengalskie, Brytyjskie i Syjamskie';
    $pageOgImage = ($currentBreed && isset($breedOgImages[$currentBreed]))
        ? $breedOgImages[$currentBreed]
        : asset('og-default.jpg');
@endphp

<x-frontend.shell
    :title="$pageTitle"
    meta-description="Przeglądaj koty dorosłe i dostępne kocięta w naszej domowej hodowli. Zdrowe linie genetyczne, komplet badań rodziców dedykowanych rasie, rodowód SHiOZ ZOOLANDIA i pełna domowa socjalizacja."
    :og-title="$pageTitle"
    og-description="Przeglądaj koty dorosłe i dostępne kocięta w naszej domowej hodowli. Zdrowe linie genetyczne, komplet badań rodziców dedykowanych rasie, rodowód SHiOZ ZOOLANDIA i pełna domowa socjalizacja."
    :og-image="$pageOgImage"
    og-image-width="1200"
    og-image-height="630"
    og-image-type="image/jpeg"
>
    {{-- ============================================================
         1. CATALOG HERO
         ============================================================ --}}
    <section class="animals-catalog-hero" aria-label="Nasze koty">
        <div class="section-inner">
            <span class="text-eyebrow animals-catalog-hero__eyebrow">
                Domowa Hodowla & Dostępne Kocięta
            </span>
            <h1 class="text-hero-display animals-catalog-hero__title">
                Nasze Koty
            </h1>
            <p class="text-lead-airy animals-catalog-hero__lead">
                Poznaj nasze koty hodowlane i dostępne kocięta wychowywane w sercu naszego domu. Hodujemy trzy wyjątkowe rasy:
                Bengalskie, Brytyjskie (krótkowłose BSH i długowłose BLH) oraz Syjamskie — z dbałością o najwyższe standardy zdrowotne i czułą socjalizację bez klatek.
            </p>
        </div>
    </section>

    {{-- ============================================================
         2. FILTER BAR (Breeds + Statuses)
         ============================================================ --}}
    <section class="section section--no-pt animals-catalog-section" aria-label="Filtrowanie kotów"
        data-ga-page="animal_list"
        data-ga-breed-filter="{{ $currentBreed && strlen((string)$currentBreed) ? (string)$currentBreed : 'all' }}"
    >


        <div class="section-inner">
            <div class="animals-filter-bar">
                {{-- Breed Filter --}}
                <div class="animals-filter-breeds" role="navigation" aria-label="Wybierz rasę">
                    <a
                        href="{{ route('frontend.animals.index', request()->except('breed', 'page')) }}"
                        class="breed-pill {{ empty($currentBreed) ? 'breed-pill--active' : '' }}"
                    >
                        Wszystkie rasy
                    </a>
                    @foreach($breeds as $breed)
                        <a
                            href="{{ route('frontend.animals.index', array_merge(request()->except('page'), ['breed' => $breed])) }}"
                            class="breed-pill {{ $currentBreed === $breed ? 'breed-pill--active' : '' }}"
                        >
                            {{ $breed }}
                        </a>
                    @endforeach
                </div>


            </div>

            @if($animals->hasPages())
                <div class="animals-catalog__pagination mb-4">
                    {{ $animals->links('components.frontend.pagination') }}
                </div>
            @endif

            {{-- ============================================================
                 3. ANIMALS GRID
                 ============================================================ --}}
            <div class="animals-grid">
                @forelse($animals as $animal)
                    <x-frontend.animal-card :animal="$animal" />
                @empty
                    <div class="empty-state animals-grid__empty">
                        <i data-lucide="cat" aria-hidden="true" class="empty-state__icon"></i>
                        <h3 class="empty-state__title text-tagline">
                            Brak kotów spełniających kryteria
                        </h3>
                        <p class="empty-state__desc text-body">
                            Obecnie nie mamy kotów o wybranej rasie lub statusie.
                            Spróbuj zmienić filtry lub skontaktuj się z nami, aby zapytać o planowane mioty.
                        </p>
                        <div class="empty-state__action">
                            <x-frontend.button href="{{ route('frontend.animals.index') }}" variant="secondary" icon="refresh-cw">
                                Pokaż wszystkie koty
                            </x-frontend.button>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($animals->hasPages())
                <div class="animals-catalog__pagination">
                    {{ $animals->links('components.frontend.pagination') }}
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         4. CONTACT CTA
         ============================================================ --}}
    <x-frontend.conversion-cta source-context="Katalog kotów" />
</x-frontend.shell>
