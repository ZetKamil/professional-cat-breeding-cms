<x-frontend.shell
    title="Nasze Koty — {{ config('app.name') }}"
    meta-description="Poznaj nasze wybitne koty rasowe — Bengalskie, Brytyjskie Krótkowłosy i Syjamskie. Zdrowie, doskonały rodowód i cudowny charakter."
>
    {{-- ============================================================
         1. CATALOG HERO
         ============================================================ --}}
    <section class="animals-catalog-hero" aria-label="Nasze koty">
        <div class="section-inner">
            <span class="text-eyebrow" style="color: var(--color-primary); display: block; margin-bottom: var(--sp-xs);">
                Hodowla & Oferta
            </span>
            <h1 class="text-hero-display" style="margin-bottom: var(--sp-sm);">
                Nasze Koty
            </h1>
            <p class="text-lead-airy" style="max-width: 640px; margin: 0 auto; color: var(--color-ink-muted-80);">
                Poznaj nasze koty hodowlane i dostępne kocięta. Hodujemy trzy wyjątkowe rasy:
                Bengalskie, Brytyjskie oraz Syjamskie — z dbałością o najwyższe standardy zdrowotne.
            </p>
        </div>
    </section>

    {{-- ============================================================
         2. FILTER BAR (Breeds + Statuses)
         ============================================================ --}}
    <section class="section" aria-label="Filtrowanie kotów" style="padding-top: 0;">
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

                {{-- Status Filter --}}
                <div class="animals-filter-secondary">
                    <div class="animals-filter-group" role="navigation" aria-label="Filtruj po statusie">
                        <a
                            href="{{ route('frontend.animals.index', request()->except('status', 'page')) }}"
                            class="filter-chip {{ empty($currentStatus) ? 'filter-chip--active' : '' }}"
                        >
                            Wszystkie statusy
                        </a>
                        @foreach($statuses as $statusEnum)
                            <a
                                href="{{ route('frontend.animals.index', array_merge(request()->except('page'), ['status' => $statusEnum->value])) }}"
                                class="filter-chip {{ $currentStatus === $statusEnum->value ? 'filter-chip--active' : '' }}"
                            >
                                {{ $statusEnum->label() }}
                            </a>
                        @endforeach
                    </div>

                    @if(request()->hasAny(['breed', 'status', 'gender', 'q']))
                        <a
                            href="{{ route('frontend.animals.index') }}"
                            class="text-nav"
                            style="color: var(--color-primary); display: inline-flex; align-items: center; gap: 4px;"
                        >
                            <i data-lucide="x-circle" style="width: 16px; height: 16px;"></i>
                            Wyczyść filtry
                        </a>
                    @endif
                </div>
            </div>

            {{-- ============================================================
                 3. ANIMALS GRID
                 ============================================================ --}}
            <div class="animals-grid">
                @forelse($animals as $animal)
                    <x-frontend.animal-card :animal="$animal" />
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i data-lucide="cat" aria-hidden="true" class="empty-state__icon"></i>
                        <h3 class="empty-state__title text-tagline">
                            Brak kotów spełniających kryteria
                        </h3>
                        <p class="empty-state__desc text-body">
                            Obecnie nie mamy kotów o wybranej rasie lub statusie.
                            Spróbuj zmienić filtry lub skontaktuj się z nami, aby zapytać o planowane mioty.
                        </p>
                        <div style="margin-top: var(--sp-lg);">
                            <x-frontend.button href="{{ route('frontend.animals.index') }}" variant="secondary" icon="refresh-cw">
                                Pokaż wszystkie koty
                            </x-frontend.button>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($animals->hasPages())
                <div style="margin-top: var(--sp-2xl);">
                    {{ $animals->links() }}
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
            eyebrow="Adopcja i Doradztwo"
            headline="Szukasz wymarzonego kociaka?"
            description="Napisz do nas — chętnie opowiemy o naszych rasach i doradzimy, który kot najlepiej pasuje do Twojego domu."
            buttonText="Zapytaj o kocięta"
            buttonHref="{{ route('contact') }}"
        />
    </div>
</x-frontend.shell>
