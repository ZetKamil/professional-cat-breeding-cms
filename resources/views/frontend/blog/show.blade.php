<x-frontend.shell
    title="{{ $post->title }} — {{ config('app.name') }}"
    meta-description="{{ Str::limit($post->excerpt, 160) }}"
    ogImage="{{ $post->coverImageUrl() }}"
>
    @php
        $wordCount = str_word_count(strip_tags($post->body));
        $readTime = max(1, (int) ceil($wordCount / 200));
        $category = $post->categories->first();
    @endphp

    {{-- ============================================================
         1. EDITORIAL ARTICLE COVER HERO (Title over Image)
         ============================================================ --}}
    <section class="article-cover-hero" style="background-image: url('{{ $post->coverImageUrl() }}');" aria-label="Nagłówek artykułu">
        <div class="article-cover-hero__overlay"></div>
        <div class="section-inner article-cover-hero__inner">
            <nav class="article-breadcrumb" aria-label="Nawigacja okruszkowa">
                <a href="{{ route('home') }}" class="article-breadcrumb__link">Strona Główna</a>
                <span class="article-breadcrumb__sep" aria-hidden="true">/</span>
                <a href="{{ route('frontend.blog.index') }}" class="article-breadcrumb__link">Baza Wiedzy</a>
            </nav>

            <h1 class="article-cover-hero__title">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="article-cover-hero__lead">
                    {{ $post->excerpt }}
                </p>
            @endif

            <div class="article-author-bar">
                <div class="article-author-bar__info">
                    <span class="article-author-bar__name">{{ config('app.name') }}</span>
                    <span class="article-author-bar__role">Certyfikowana Hodowla Kotów Rasowych</span>
                </div>
                <div class="article-author-bar__meta">
                    <time datetime="{{ $post->published_at?->toIso8601String() }}">
                        {{ $post->published_at?->format('d.m.Y') }}
                    </time>
                    <span aria-hidden="true">·</span>
                    <span>{{ $readTime }} min czytania</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         3. EDITORIAL ARTICLE BODY
         ============================================================ --}}
    <x-frontend.section class="article-body-section">
        <div class="article-layout">
            <article class="article-editorial">
                {!! Str::markdown($post->body) !!}
            </article>
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

            <div class="articles-grid">
                @foreach ($relatedPosts as $related)
                    <x-frontend.blog-card :post="$related" />
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

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "BlogPosting",
      "mainEntityOfPage": {
        "@@type": "WebPage",
        "@@id": "{{ route('frontend.blog.show', $post) }}"
      },
      "headline": "{{ $post->title }}",
      "description": "{{ Str::limit($post->excerpt, 160) }}",
      "image": "{{ $post->coverImageUrl() }}",
      "author": {
        "@@type": "Organization",
        "name": "{{ config('app.name') }}"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset('apple-touch-icon.png') }}"
        }
      },
      "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}"
    }
    </script>
    @endpush
</x-frontend.shell>
