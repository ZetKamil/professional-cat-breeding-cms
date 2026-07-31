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

        {{-- ============================================================
        2. CATEGORY PILLS & SEARCH BAR
        ============================================================ --}}
        <div class="blog-toolbar">
            <nav class="blog-categories" aria-label="Kategorie artykułów">
                <a href="{{ route('frontend.blog.index') }}"
                    class="blog-category-pill {{ !$selectedCategory ? 'blog-category-pill--active' : '' }}">
                    Wszystkie ({{ $categories->sum('posts_count') }})
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('frontend.blog.index', ['category' => $category->slug]) }}"
                        class="blog-category-pill {{ $selectedCategory === $category->slug ? 'blog-category-pill--active' : '' }}">
                        {{ $category->name }} ({{ $category->posts_count }})
                    </a>
                @endforeach
            </nav>

            <form action="{{ route('frontend.blog.index') }}" method="GET" class="blog-search" role="search">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <div class="blog-search__field">
                    <i data-lucide="search" aria-hidden="true" class="blog-search__icon"></i>
                    <input type="search" name="q" value="{{ $searchQuery ?? '' }}"
                        placeholder="Szukaj w bazie wiedzy..." class="blog-search__input"
                        aria-label="Szukaj w bazie wiedzy">
                    @if ($searchQuery)
                        <a href="{{ route('frontend.blog.index', ['category' => $selectedCategory]) }}"
                            class="blog-search__clear" aria-label="Wyczyść wyszukiwanie">
                            <i data-lucide="x" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </form>
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