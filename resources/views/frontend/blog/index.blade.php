<x-frontend.shell title="Baza Wiedzy i Poradniki o Kotach Rasowych | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Eksperckie poradniki felinologiczne: zdrowie, żywienie BARF, wyprawka dla kociaka, badania genetyczne i socjalizacja kotów bengalskich, brytyjskich i syjamskich."
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}">
    {{-- ============================================================
    1. EDITORIAL HERO HEADER
    ============================================================ --}}
    <section class="blog-hero" aria-label="Baza Wiedzy">
        <div class="section-inner">
            <span class="text-eyebrow blog-hero__eyebrow">
                Wiedza & Poradniki
            </span>
            <h1 class="text-hero-display blog-hero__title">
                Baza Wiedzy
            </h1>
            <p class="text-lead-airy blog-hero__lead">
                Dzielimy się naszą pasją, doświadczeniem weterynaryjnym oraz wieloletnią praktyką w wychowaniu kotów
                bengalskich, brytyjskich i syjamskich.
            </p>
        </div>
    </section>

    {{-- ============================================================
    3. ARTICLES GRID
    ============================================================ --}}
    <x-frontend.section class="blog-grid-section">
        @if ($posts->count() > 0)
            <div class="articles-grid">
                @foreach ($posts as $post)
                    <x-frontend.blog-card :post="$post" />
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <div class="blog-pagination">
                    {{ $posts->links('components.frontend.pagination') }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="empty-state">
                <i data-lucide="book-open" aria-hidden="true" class="empty-state__icon"></i>
                <h3 class="text-body-strong">Nie znaleziono żadnych artykułów</h3>
                <p class="text-body empty-state__desc">
                    Brak publikacji spełniających Twoje kryteria wyszukiwania. Spróbuj wybrać inną kategorię lub zresetować
                    filtry.
                </p>
                <x-frontend.button variant="secondary" href="{{ route('frontend.blog.index') }}">
                    Zobacz wszystkie artykuły
                </x-frontend.button>
            </div>
        @endif
    </x-frontend.section>
</x-frontend.shell>