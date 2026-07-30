<x-frontend.shell
    title="{{ $post->title }} — ZetKamil Hodowla Kotów"
    meta-description="{{ Str::limit($post->excerpt, 160) }}"
>
    @php
        $wordCount = str_word_count(strip_tags($post->body));
        $readTime = max(1, (int) ceil($wordCount / 200));
        $category = $post->categories->first();
    @endphp

    {{-- ============================================================
         1. EDITORIAL ARTICLE HEADER
         ============================================================ --}}
    <x-frontend.section class="article-header-section reveal-up">
        <nav class="breadcrumb" aria-label="Nawigacja okruszkowa">
            <a href="{{ route('home') }}" class="breadcrumb__link">Strona Główna</a>
            <span class="breadcrumb__sep" aria-hidden="true">/</span>
            <a href="{{ route('frontend.blog.index') }}" class="breadcrumb__link">Baza Wiedzy</a>
            @if ($category)
                <span class="breadcrumb__sep" aria-hidden="true">/</span>
                <a href="{{ route('frontend.blog.index', ['category' => $category->slug]) }}" class="breadcrumb__link">{{ $category->name }}</a>
            @endif
        </nav>

        <div class="article-header">
            @if ($category)
                <span class="text-eyebrow">{{ $category->name }}</span>
            @endif
            <h1 class="text-display article-title">{{ $post->title }}</h1>
            
            <p class="text-intro article-lead">
                {{ $post->excerpt }}
            </p>

            <div class="article-author-bar">
                <div class="article-author-bar__info">
                    <span class="article-author-bar__name">ZetKamil Cattery</span>
                    <span class="article-author-bar__role">Certyfikowana Hodowla Kotów Rasowych</span>
                </div>
                <div class="article-author-bar__meta">
                    <time datetime="{{ $post->published_at?->toIso8601String() }}">
                        {{ $post->published_at?->format('d.m.Y') }}
                    </time>
                    <span aria-hidden="true">•</span>
                    <span>{{ $readTime }} min czytania</span>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         2. HERO COVER IMAGE
         ============================================================ --}}
    <div class="article-cover reveal-up">
        <div class="article-cover__wrapper">
            <img 
                src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?q=80&w=1600&auto=format&fit=crop" 
                alt="{{ $post->title }}" 
                class="article-cover__img"
            >
        </div>
    </div>

    {{-- ============================================================
         3. EDITORIAL ARTICLE BODY
         ============================================================ --}}
    <x-frontend.section class="article-body-section">
        <div class="article-layout">
            <article class="article-editorial">
                {!! nl2br(e($post->body)) !!}
            </article>

            {{-- Tags & Categories Footer --}}
            <div class="article-footer">
                <div class="article-footer__categories">
                    <span class="article-footer__label">Kategorie:</span>
                    <div class="article-footer__pills">
                        @foreach ($post->categories as $cat)
                            <a href="{{ route('frontend.blog.index', ['category' => $cat->slug]) }}" class="blog-category-pill">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="article-footer__share">
                    <span class="article-footer__label">Udostępnij:</span>
                    <div class="article-share-links">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" class="article-share-btn" aria-label="Udostępnij na Facebooku">
                            <i data-lucide="facebook" aria-hidden="true"></i>
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(request()->fullUrl()) }}" class="article-share-btn" aria-label="Wyślij e-mailem">
                            <i data-lucide="mail" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         4. RELATED ARTICLES GRID
         ============================================================ --}}
    @if ($relatedPosts->count() > 0)
        <x-frontend.section tile="light" class="related-articles-section">
            <x-frontend.section-header
                eyebrow="Podobne Artykuły"
                headline="Przeczytaj także"
                description="Poznaj inne wpisy z naszej bazy wiedzy, które pomogą Ci w codziennej opiece nad kotem rasowym."
            />

            <div class="blog-grid">
                @foreach ($relatedPosts as $related)
                    @php
                        $relWordCount = str_word_count(strip_tags($related->body));
                        $relReadTime = max(1, (int) ceil($relWordCount / 200));
                        $relCat = $related->categories->first();
                    @endphp
                    <article class="blog-card">
                        <a href="{{ route('frontend.blog.show', $related) }}" class="blog-card__media">
                            <div class="blog-card__img-wrapper">
                                <img 
                                    src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?q=80&w=800&auto=format&fit=crop" 
                                    alt="{{ $related->title }}" 
                                    class="blog-card__img"
                                    loading="lazy"
                                >
                            </div>
                            @if ($relCat)
                                <span class="blog-card__badge">{{ $relCat->name }}</span>
                            @endif
                        </a>

                        <div class="blog-card__content">
                            <div class="blog-card__meta">
                                <time datetime="{{ $related->published_at?->toIso8601String() }}">
                                    {{ $related->published_at?->format('d.m.Y') }}
                                </time>
                                <span class="blog-card__dot" aria-hidden="true">•</span>
                                <span>{{ $relReadTime }} min czytania</span>
                            </div>

                            <h3 class="blog-card__title">
                                <a href="{{ route('frontend.blog.show', $related) }}">
                                    {{ $related->title }}
                                </a>
                            </h3>

                            <p class="blog-card__excerpt">
                                {{ Str::limit($related->excerpt, 100) }}
                            </p>

                            <div class="blog-card__footer">
                                <a href="{{ route('frontend.blog.show', $related) }}" class="blog-card__link">
                                    <span>Czytaj artykuł</span>
                                    <i data-lucide="arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
         5. CTA SECTION
         ============================================================ --}}
    <x-frontend.section class="article-cta-section">
        <div class="article-cta-box">
            <div class="article-cta-box__content">
                <h2 class="text-display">Masz pytania dotyczące naszych kociąt?</h2>
                <p class="text-intro">
                    Chętnie doradzimy w kwestii rezerwacji miotu, wyprawki lub wyboru odpowiedniej rasy do Twojego stylu życia.
                </p>
                <div class="article-cta-box__actions">
                    <x-frontend.button variant="primary" href="{{ route('contact') }}">
                        Skontaktuj się z nami
                    </x-frontend.button>
                    <x-frontend.button variant="secondary" href="{{ route('frontend.animals.index') }}">
                        Zobacz dostępne koty
                    </x-frontend.button>
                </div>
            </div>
        </div>
    </x-frontend.section>
</x-frontend.shell>
