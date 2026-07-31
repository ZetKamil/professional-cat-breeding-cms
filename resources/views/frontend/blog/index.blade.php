<x-frontend.shell title="Baza Wiedzy & Poradniki — ZetKamil Hodowla Kotów"
    meta-description="Eksperckie artykuły dotyczące zdrowia, genetyki, wyprawki, diety BARF i socjalizacji kotów bengalskich, brytyjskich i syjamskich.">
    {{-- ============================================================
    1. EDITORIAL HERO HEADER
    ============================================================ --}}
    <x-frontend.section class="blog-hero reveal-up">
        <div class="blog-hero__content">
            <h1 class="text-display">Baza Wiedzy</h1>
            <p class="text-intro blog-hero__intro">
                Dzielimy się naszą pasją, doświadczeniem weterynaryjnym oraz wieloletnią praktyką w wychowaniu kotów
                bengalskich, brytyjskich i syjamskich.
            </p>
        </div>

    </x-frontend.section>

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
                    {{ $posts->links() }}
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