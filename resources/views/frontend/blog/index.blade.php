<x-frontend.shell title="Blog i Poradniki o Kotach Rasowych | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Eksperckie poradniki felinologiczne: zdrowie, żywienie BARF, wyprawka dla kociaka, badania genetyczne rodziców i socjalizacja kotów bengalskich, brytyjskich i syjamskich."
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}">
    {{-- ============================================================
    1. EDITORIAL HERO HEADER + CATEGORY FILTER TOOLBAR
    ============================================================ --}}
    <section class="blog-hero" aria-label="Blog">
        <div class="section-inner">
            <span class="text-eyebrow blog-hero__eyebrow">
                Wiedza & Poradniki
            </span>
            <h1 class="text-hero-display blog-hero__title">
                Blog
            </h1>
            <p class="text-lead-airy blog-hero__lead">
                Dzielimy się naszą pasją, doświadczeniem weterynaryjnym oraz wieloletnią praktyką w wychowaniu kotów
                bengalskich, brytyjskich i syjamskich.
            </p>

            {{-- 2. CATEGORY PILLS FILTER & SEARCH TOOLBAR --}}
            <div class="blog-toolbar" style="margin-top: var(--sp-xl);">
                <div class="blog-categories" role="navigation" aria-label="Kategorie artykułów">
                    <a href="{{ route('frontend.blog.index', request()->except('category', 'page')) }}"
                       class="blog-category-pill {{ empty($selectedCategory) ? 'blog-category-pill--active' : '' }}">
                        Wszystkie ({{ $posts->total() }})
                    </a>
                    @foreach($categories as $category)
                        @if($category->posts_count > 0)
                            <a href="{{ route('frontend.blog.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                               class="blog-category-pill {{ $selectedCategory === $category->slug ? 'blog-category-pill--active' : '' }}">
                                {{ $category->name }} ({{ $category->posts_count }})
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Search --}}
                <div class="blog-search">
                    <form action="{{ route('frontend.blog.index') }}" method="GET" class="blog-search__field" role="search">
                        @if($selectedCategory)
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                        @endif
                        <i data-lucide="search" class="blog-search__icon" aria-hidden="true"></i>
                        <input type="search" name="q" value="{{ $searchQuery }}" placeholder="Szukaj artykułu..."
                               class="blog-search__input" aria-label="Szukaj w bazie wiedzy">
                        @if($searchQuery)
                            <a href="{{ route('frontend.blog.index', request()->except('q', 'page')) }}"
                               class="blog-search__clear" aria-label="Wyczyść wyszukiwanie">
                                <i data-lucide="x" style="width: 16px; height: 16px;" aria-hidden="true"></i>
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    3. ARTICLES GRID
    ============================================================ --}}
    <x-frontend.section class="blog-grid-section" padding="none">
        @if ($posts->count() > 0)
            <div class="articles-grid">
                @foreach ($posts as $post)
                    <x-frontend.blog-card :post="$post" />
                @endforeach
            </div>

            {{-- Bottom Pagination --}}
            @if ($posts->hasPages())
                <div class="blog-pagination">
                    {{ $posts->links('components.frontend.pagination') }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="empty-state" style="padding: var(--sp-3xl) var(--sp-md); text-align: center;">
                <i data-lucide="book-open" aria-hidden="true" class="empty-state__icon" style="width: 48px; height: 48px; margin: 0 auto var(--sp-md); color: var(--color-ink-muted-48);"></i>
                <h3 class="text-body-strong" style="font-size: 1.25rem; margin-bottom: 8px;">Nie znaleziono żadnych artykułów</h3>
                <p class="text-body empty-state__desc" style="max-width: 480px; margin: 0 auto var(--sp-lg); color: var(--color-ink-muted-80);">
                    Brak publikacji spełniających Twoje kryteria wyszukiwania. Spróbuj wybrać inną kategorię lub zresetować filtry.
                </p>
                <x-frontend.button variant="secondary" href="{{ route('frontend.blog.index') }}">
                    Zobacz wszystkie artykuły
                </x-frontend.button>
            </div>
        @endif
    </x-frontend.section>
</x-frontend.shell>