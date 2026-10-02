<x-frontend.shell
    title="{{ $post->title }} | Baza Wiedzy — Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="{{ Str::limit($post->excerpt ?: strip_tags($post->body ?? ''), 160) }}"
    og-image="{{ $post->coverImageUrl() }}"
    og-type="article"
>
    @php
        $wordCount = str_word_count(strip_tags($post->body ?? ''));
        $readTime = max(1, (int) ceil($wordCount / 200));
        $category = $post->categories->first();
        $hasSections = !empty($post->sections) && is_array($post->sections);
        $globalCta = \App\Models\Setting::getBlogCta();
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
         Renders either structured sections (new) or legacy HTML body
         ============================================================ --}}
    <x-frontend.section class="article-body-section">
        <div class="article-layout">
            <article class="article-editorial">

                @if($hasSections)
                    {{-- ─── Structured sections rendering ─────────── --}}
                    @php
                        $sectionsCount = count($post->sections);
                        // If the last section is a legacy static CTA (starts with 🐾 or mentions kittens), omit it from the body loop
                        $lastSection = $sectionsCount > 1 ? $post->sections[$sectionsCount - 1] : null;
                        $lastIsCta = $lastSection && (
                            str_starts_with(trim($lastSection['heading'] ?? ''), '🐾') ||
                            str_contains(mb_strtolower($lastSection['heading'] ?? ''), 'dostępne') ||
                            str_contains(mb_strtolower($lastSection['heading'] ?? ''), 'kocięta')
                        );
                        $renderCount = ($lastIsCta && ($globalCta['is_enabled'] ?? true)) ? $sectionsCount - 1 : $sectionsCount;
                    @endphp

                    @for($i = 0; $i < $renderCount; $i++)
                        @php
                            $section = $post->sections[$i];
                            $sectionBody = trim($section['body'] ?? '');
                            $sectionHeading = trim($section['heading'] ?? '');
                            $sectionImage = trim($section['image_url'] ?? '');
                            $isIntro = $i === 0;
                        @endphp

                        @if($isIntro)
                            {{-- Intro section: no H2, just lead paragraph --}}
                            @if($sectionBody)
                                <div class="article-intro-content">
                                    {!! Str::markdown($sectionBody) !!}
                                </div>
                            @endif
                            @if($sectionImage)
                                <figure class="article-section-figure article-section-figure--full">
                                    <img
                                        src="{{ $sectionImage }}"
                                        alt="Zdjęcie do artykułu"
                                        class="article-section-img"
                                        loading="lazy"
                                    >
                                </figure>
                            @endif
                        @else
                            {{-- Regular section: optional H2 + body + image --}}
                            <div class="article-section-block">
                                @if($sectionHeading)
                                    <h2>{{ $sectionHeading }}</h2>
                                @endif
                                @if($sectionBody)
                                    <div class="article-section-content">
                                        {!! Str::markdown($sectionBody) !!}
                                    </div>
                                @endif
                                @if($sectionImage)
                                    <figure class="article-section-figure">
                                        <img
                                            src="{{ $sectionImage }}"
                                            alt="{{ $sectionHeading ?: 'Zdjęcie sekcji' }}"
                                            class="article-section-img"
                                            loading="lazy"
                                        >
                                    </figure>
                                @endif
                            </div>
                        @endif
                    @endfor
                @else
                    {{-- ─── Legacy body (markdown HTML) ─────────────── --}}
                    {!! Str::markdown($post->body) !!}
                @endif

                {{-- ============================================================
                     DYNAMIC GLOBAL CATTERY OFFER & KITTENS CTA CARD
                     Always displays the latest kittens availability across ALL articles!
                     ============================================================ --}}
                @if(!empty($globalCta) && ($globalCta['is_enabled'] ?? true) && !empty($globalCta['body']))
                    <aside class="article-closing-cta-card" aria-label="Aktualna oferta hodowli i kontakt">
                        <div class="article-closing-cta-card__badge">
                            <span class="article-closing-cta-card__icon">🐾</span>
                            <span class="article-closing-cta-card__label">{{ $globalCta['badge'] ?? 'Hodowla Kotów z Mazowieckiej Szwajcarii' }}</span>
                        </div>

                        @if(!empty($globalCta['heading']))
                            <h2 class="article-closing-cta-card__title">{{ $globalCta['heading'] }}</h2>
                        @endif

                        <div class="article-closing-cta-card__body">
                            {!! Str::markdown($globalCta['body']) !!}
                        </div>

                        @if(!empty($globalCta['image_url']))
                            <figure class="article-closing-cta-card__figure">
                                <img
                                    src="{{ $globalCta['image_url'] }}"
                                    alt="{{ $globalCta['heading'] ?? 'Kocięta w hodowli' }}"
                                    class="article-closing-cta-card__img"
                                    loading="lazy"
                                >
                            </figure>
                        @endif

                        @if(!empty($globalCta['button_text']) && !empty($globalCta['button_url']))
                            <div class="article-closing-cta-card__actions" style="margin-top: 1.5rem;">
                                <a href="{{ $globalCta['button_url'] }}" class="btn btn-warning text-dark fw-bold" style="background:#c59b27; border-color:#c59b27; color:#181816; font-weight:700; padding:10px 24px; border-radius:9999px; text-decoration:none; display:inline-block; box-shadow: 0 4px 14px rgba(197, 155, 39, 0.25);">
                                    {{ $globalCta['button_text'] }} →
                                </a>
                            </div>
                        @endif
                    </aside>
                @endif

            </article>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         4. FEATURED CATS STRIP (before related articles)
         Shows animals pinned to this post by the editor
         ============================================================ --}}
    @if($post->animals->isNotEmpty())
        <x-frontend.section class="article-featured-cats-section">
            <x-frontend.section-header
                eyebrow="Poznaj nasze koty"
                headline="Koty z tego artykułu"
                description="Chcesz dowiedzieć się więcej? Zajrzyj bezpośrednio do profili kotów wspomnianych w tym artykule."
            />

            <div class="animals-grid animals-grid--compact">
                @foreach($post->animals as $animal)
                    <x-frontend.animal-card :animal="$animal" :show-age="false" />
                @endforeach
            </div>
        </x-frontend.section>
    @endif

    {{-- ============================================================
         5. RELATED ARTICLES GRID
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
         6. CTA SECTION
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
      "description": "{{ Str::limit($post->excerpt ?: strip_tags($post->body ?? ''), 160) }}",
      "image": "{{ $post->coverImageUrl() }}",
      "author": {
        "@@type": "Organization",
        "name": "Hodowla Kotów z Mazowieckiej Szwajcarii"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "Hodowla Kotów z Mazowieckiej Szwajcarii",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ asset('logo.png') }}"
        }
      },
      "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}"
    }
    </script>
    @endpush
</x-frontend.shell>
