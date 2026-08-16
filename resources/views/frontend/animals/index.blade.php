<x-frontend.shell
    title="Nasze Koty — Koty Bengalskie, Brytyjskie i Syjamskie | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Przeglądaj koty hodowlane i dostępne kocięta w naszej hodowli. Zdrowe linie genetyczne, komplet badań, rodowód stowarzyszenia i pełna socjalizacja."
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}"
>
    {{-- ============================================================
         1. CATALOG HERO
         ============================================================ --}}
    <section class="animals-catalog-hero" aria-label="Nasze koty">
        <div class="section-inner">
            <span class="text-eyebrow animals-catalog-hero__eyebrow">
                Hodowla & Oferta
            </span>
            <h1 class="text-hero-display animals-catalog-hero__title">
                Nasze Koty
            </h1>
            <p class="text-lead-airy animals-catalog-hero__lead">
                Poznaj nasze koty hodowlane i dostępne kocięta. Hodujemy trzy wyjątkowe rasy:
                Bengalskie, Brytyjskie oraz Syjamskie — z dbałością o najwyższe standardy zdrowotne.
            </p>
        </div>
    </section>

    {{-- ============================================================
         2. FILTER BAR (Breeds + Statuses)
         ============================================================ --}}
    <section class="section section--no-pt animals-catalog-section" aria-label="Filtrowanie kotów">
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
    <div class="reveal-up">
        <x-frontend.cta
            tile="parchment"
            eyebrow="Kontakt i Doradztwo"
            headline="Szukasz wymarzonego kociaka?"
            description="Napisz do nas — chętnie opowiemy o naszych rasach i doradzimy, który kot najlepiej pasuje do Twojego domu."
            buttonText="Zapytaj o kocięta"
            buttonHref="{{ route('contact') }}"
        />
    </div>
</x-frontend.shell>
