<x-frontend.shell
    title="Baza Wiedzy & Poradniki — ZetKamil Hodowla Kotów"
    meta-description="Eksperckie artykuły dotyczące zdrowia, genetyki, wyprawki, diety BARF i socjalizacji kotów bengalskich, brytyjskich i syjamskich."
>
    {{-- ============================================================
         1. EDITORIAL HERO HEADER
         ============================================================ --}}
    <x-frontend.section class="blog-hero reveal-up">
        <div class="blog-hero__content">
            <span class="text-eyebrow">Wiedza & Pielęgnacja</span>
            <h1 class="text-display">Baza Wiedzy ZetKamil</h1>
            <p class="text-intro blog-hero__intro">
                Dzielimy się naszą pasją, doświadczeniem weterynaryjnym oraz wieloletnią praktyką w wychowaniu kotów bengalskich, brytyjskich i syjamskich.
            </p>
        </div>

        {{-- ============================================================
             2. CATEGORY PILLS & SEARCH BAR
             ============================================================ --}}
        <div class="blog-toolbar">
            <nav class="blog-categories" aria-label="Kategorie artykułów">
                <a 
                    href="{{ route('frontend.blog.index') }}" 
                    class="blog-category-pill {{ ! $selectedCategory ? 'blog-category-pill--active' : '' }}"
                >
                    Wszystkie ({{ $categories->sum('posts_count') }})
                </a>
                @foreach ($categories as $category)
                    <a 
                        href="{{ route('frontend.blog.index', ['category' => $category->slug]) }}" 
                        class="blog-category-pill {{ $selectedCategory === $category->slug ? 'blog-category-pill--active' : '' }}"
                    >
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
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ $searchQuery ?? '' }}" 
                        placeholder="Szukaj w bazie wiedzy..." 
                        class="blog-search__input"
                        aria-label="Szukaj w bazie wiedzy"
                    >
                    @if ($searchQuery)
                        <a href="{{ route('frontend.blog.index', ['category' => $selectedCategory]) }}" class="blog-search__clear" aria-label="Wyczyść wyszukiwanie">
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
            <div class="blog-grid">
                @foreach ($posts as $post)
                    @php
                        $wordCount = str_word_count(strip_tags($post->body));
                        $readTime = max(1, (int) ceil($wordCount / 200));
                        $category = $post->categories->first();
                    @endphp
                    <article class="blog-card reveal-up" style="--delay: {{ $loop->index * 0.1 }}s;">
                        <a href="{{ route('frontend.blog.show', $post) }}" class="blog-card__media">
                            <div class="blog-card__img-wrapper">
                                <img 
                                    src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?q=80&w=800&auto=format&fit=crop" 
                                    alt="{{ $post->title }}" 
                                    class="blog-card__img"
                                    loading="lazy"
                                >
                            </div>
                            @if ($category)
                                <span class="blog-card__badge">{{ $category->name }}</span>
                            @endif
                        </a>

                        <div class="blog-card__content">
                            <div class="blog-card__meta">
                                <time datetime="{{ $post->published_at?->toIso8601String() }}">
                                    {{ $post->published_at?->format('d.m.Y') }}
                                </time>
                                <span class="blog-card__dot" aria-hidden="true">•</span>
                                <span>{{ $readTime }} min czytania</span>
                            </div>

                            <h2 class="blog-card__title">
                                <a href="{{ route('frontend.blog.show', $post) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <p class="blog-card__excerpt">
                                {{ Str::limit($post->excerpt, 120) }}
                            </p>

                            <div class="blog-card__footer">
                                <a href="{{ route('frontend.blog.show', $post) }}" class="blog-card__link">
                                    <span>Czytaj artykuł</span>
                                    <i data-lucide="arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
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
                    Brak publikacji spełniających Twoje kryteria wyszukiwania. Spróbuj wybrać inną kategorię lub zresetować filtry.
                </p>
                <x-frontend.button variant="secondary" href="{{ route('frontend.blog.index') }}">
                    Zobacz wszystkie artykuły
                </x-frontend.button>
            </div>
        @endif
    </x-frontend.section>
</x-frontend.shell>
