<x-frontend.shell
    title="{{ $post->meta_title ?: ($post->title . ' | Blog — Hodowla Kotów z Mazowieckiej Szwajcarii') }}"
    meta-description="{{ $post->meta_description ?: Str::limit($post->excerpt ?: strip_tags($post->body ?? ''), 160) }}"
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
                <a href="{{ route('frontend.blog.index') }}" class="article-breadcrumb__link">Blog</a>
            </nav>

            <h1 class="article-cover-hero__title">
                {{ $post->title }}
            </h1>

            <div class="article-author-bar">
                <div class="article-author-bar__info">
                    <span class="article-author-bar__name">{{ config('app.name') }}</span>
                    <span class="article-author-bar__role">Certyfikowana Domowa Hodowla Kotów Rasowych</span>
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

                @php
                    $markdownOptions = [
                        'html_input' => 'strip',
                        'allow_unsafe_links' => false,
                    ];

                    $renderArticleMarkdown = function ($markdownText) use ($markdownOptions) {
                        $html = Str::markdown($markdownText ?? '', $markdownOptions);
                        // Transform "Odpowiedź w pigułce:" paragraph into Quiet Luxury editorial callout box
                        $pattern = '/<p>(?:\s*<strong>)?\s*Odpowiedź w pigułce:\s*(.*?)(?:<\/strong>)?\s*<\/p>/siu';
                        return preg_replace_callback($pattern, function ($matches) {
                            $content = trim($matches[1]);
                            if (str_starts_with($content, '<strong>') && str_ends_with($content, '</strong>')) {
                                $content = substr($content, 8, -9);
                            }
                            return '<aside class="article-takeaway-box" aria-label="Odpowiedź w pigułce">'
                                . '<div class="article-takeaway-box__header">'
                                . '<span class="article-takeaway-box__icon">💡</span>'
                                . '<span class="article-takeaway-box__label">Odpowiedź w pigułce</span>'
                                . '</div>'
                                . '<div class="article-takeaway-box__content">' . $content . '</div>'
                                . '</aside>';
                        }, $html);
                    };
                @endphp

                @if($hasSections)
                    {{-- ─── Structured sections rendering ─────────── --}}
                    @php
                        $sectionsCount = count($post->sections);
                        // Only omit legacy AI boilerplate sections starting with 🐾
                        $lastSection = $sectionsCount > 1 ? $post->sections[$sectionsCount - 1] : null;
                        $lastIsLegacyBoilerplate = $lastSection && str_starts_with(trim($lastSection['heading'] ?? ''), '🐾');
                        $renderCount = ($lastIsLegacyBoilerplate && ($globalCta['is_enabled'] ?? true)) ? $sectionsCount - 1 : $sectionsCount;
                    @endphp

                    @for($i = 0; $i < $renderCount; $i++)
                        @php
                            $section = $post->sections[$i];
                            $sectionBody = trim($section['body'] ?? '');
                            $sectionHeading = trim($section['heading'] ?? '');
                            $sectionTip = trim($section['tip'] ?? '');
                            $sectionImage = trim($section['image_url'] ?? '');
                            $isIntro = $i === 0;
                        @endphp

                        @if($isIntro)
                            {{-- Intro section: no H2, just lead paragraph --}}
                            @if($sectionBody)
                                <div class="article-intro-content">
                                    {!! $renderArticleMarkdown($sectionBody) !!}
                                </div>
                            @endif
                            @if($sectionTip)
                                <aside class="article-tip-box" aria-label="Wskazówka hodowcy">
                                    <div class="article-tip-box__header">
                                        <span class="article-tip-box__icon">💡</span>
                                        <span class="article-tip-box__label">Wskazówka Hodowcy</span>
                                    </div>
                                    <p class="article-tip-box__text">{{ $sectionTip }}</p>
                                </aside>
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
                            {{-- Regular section: optional H2 + body + tip + image --}}
                            <div class="article-section-block">
                                @if($sectionHeading)
                                    <h2>{{ $sectionHeading }}</h2>
                                @endif
                                @if($sectionBody)
                                    <div class="article-section-content">
                                        {!! $renderArticleMarkdown($sectionBody) !!}
                                    </div>
                                @endif
                                @if($sectionTip)
                                    <aside class="article-tip-box" aria-label="Wskazówka hodowcy">
                                        <div class="article-tip-box__header">
                                            <span class="article-tip-box__icon">💡</span>
                                            <span class="article-tip-box__label">Wskazówka Hodowcy</span>
                                        </div>
                                        <p class="article-tip-box__text">{{ $sectionTip }}</p>
                                    </aside>
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
                    @php
                        $bodyToRender = $post->body ?? '';
                        if ($globalCta['is_enabled'] ?? true) {
                            $bodyToRender = preg_replace('/(?:\r?\n)*---\s*(?:\r?\n)*##\s*(?:🐾|Dostępne kocięta|Aktualnie dostępne).*$/su', '', $bodyToRender);
                        }
                    @endphp
                    {!! $renderArticleMarkdown($bodyToRender) !!}
                @endif

                {{-- ============================================================
                     UNIFIED QUIET LUXURY CTA CARD AT THE BOTTOM OF THE ARTICLE
                     Strictly Black (#141414) / Champagne Gold (#C5A059) Quiet Luxury
                     ============================================================ --}}
                <aside class="article-luxury-cta-card" aria-label="Kontakt z hodowlą">
                    <div class="article-luxury-cta-card__badge">
                        <span>🐾 DOMOWA HODOWLA MAZOWIECKA SZWAJCARIA</span>
                    </div>

                    @if($post->slug === 'kot-bengalski-w-domu-mity-zalety-odchowanych-kociat' || str_contains($post->slug, 'odchowanych-kociat') || str_contains($post->slug, 'bengal'))
                        <h3 class="article-luxury-cta-card__heading">
                            Chcesz zobaczyć naszą trójkę odchowanych bengali na żywo lub zapytać o szczegóły?
                        </h3>
                        <p class="article-luxury-cta-card__subtext">
                            Napisz do nas na Messengerze – chętnie prześlemy dzisiejsze filmiki z Baronem, Cyprianem i Carlosem lub połączymy się na krótką wideo-wizytę.
                        </p>

                        <div class="article-luxury-cta-card__buttons">
                            <a href="https://m.me/61580668026948?text={{ urlencode('Dzień dobry! Chciał(a)bym zapytać o kociaki bengalskie i filmiki.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="article-luxury-cta-btn article-luxury-cta-btn--primary"
                               onclick="try{if(typeof fbq==='function'){fbq('track','Contact',{method:'Messenger',content_name:'Odchowane kocięta bengalskie'});}}catch(e){} if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'blog_post_bengal', post_title: @js($post->title) }); }">
                                💬 Napisz na Messengerze (Zapytaj o kociaki i filmik)
                            </a>

                            <a href="https://wa.me/48514153204?text={{ urlencode('Dzień dobry! Chciał(a)bym zapytać o kociaki bengalskie i filmiki.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="article-luxury-cta-btn article-luxury-cta-btn--secondary"
                               onclick="try{if(typeof fbq==='function'){fbq('track','Contact',{method:'WhatsApp',content_name:'Odchowane kocięta bengalskie'});}}catch(e){} if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'blog_post_bengal', post_title: @js($post->title) }); }">
                                WhatsApp (+48 514 153 204)
                            </a>
                        </div>

                        <div class="article-luxury-cta-card__quiz">
                            <a href="{{ route('quiz') }}" class="article-luxury-cta-card__quiz-link">
                                ✨ Nie wiesz, czy Bengal pasuje do Twojego domu? Rozwiąż 45-sekundowy Quiz →
                            </a>
                        </div>
                    @else
                        {{-- Generic / Breed-Adaptive Luxury Card for all other articles --}}
                        <h3 class="article-luxury-cta-card__heading">
                            {{ $globalCta['heading'] ?? 'Szukasz wymarzonego, rodowodowego kociaka?' }}
                        </h3>
                        <p class="article-luxury-cta-card__subtext">
                            Napisz do nas na Messengerze – z przyjemnością opowiemy o naszych miotach, warunkach domowego wychowania i prześlemy aktualne materiały wideo.
                        </p>

                        <div class="article-luxury-cta-card__buttons">
                            <a href="https://m.me/61580668026948?text={{ urlencode('Dzień dobry! Chciał(a)bym zapytać o dostępne kocięta.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="article-luxury-cta-btn article-luxury-cta-btn--primary"
                               onclick="try{if(typeof fbq==='function'){fbq('track','Contact',{method:'Messenger',content_name: @js($post->title) });}}catch(e){} if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'blog_post', post_title: @js($post->title) }); }">
                                💬 Napisz na Messengerze (Zapytaj o kocięta)
                            </a>

                            <a href="https://wa.me/48514153204?text={{ urlencode('Dzień dobry! Chciał(a)bym zapytać o dostępne kocięta.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="article-luxury-cta-btn article-luxury-cta-btn--secondary"
                               onclick="try{if(typeof fbq==='function'){fbq('track','Contact',{method:'WhatsApp',content_name: @js($post->title) });}}catch(e){} if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'blog_post', post_title: @js($post->title) }); }">
                                WhatsApp (+48 514 153 204)
                            </a>
                        </div>

                        <div class="article-luxury-cta-card__quiz">
                            <a href="{{ route('quiz') }}" class="article-luxury-cta-card__quiz-link">
                                ✨ Nie wiesz, która rasa najlepiej pasuje do Ciebie? Rozwiąż 45-sekundowy Quiz →
                            </a>
                        </div>
                    @endif
                </aside>

            </article>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         4. FEATURED CATS STRIP (before related articles)
         Shows animals pinned to this post in a spacious 3-column grid
         ============================================================ --}}
    @php
        $catsToDisplay = $articleAnimals ?? $post->animals;
    @endphp

    @if($catsToDisplay->isNotEmpty())
        <x-frontend.section class="article-featured-cats-section">
            <x-frontend.section-header
                eyebrow="Poznaj nasze koty"
                headline="Koty z tego artykułu"
                description="Chcesz dowiedzieć się więcej? Zajrzyj bezpośrednio do profili kotów wspomnianych w tym artykule."
            />

            <div class="animals-grid">
                @foreach($catsToDisplay as $animal)
                    <x-frontend.animal-card :animal="$animal" :show-age="true" />
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
                description="Poznaj inne wpisy z naszego bloga, które pomogą Ci w codziennej opiece nad kotem rasowym."
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
    <x-frontend.conversion-cta source-context="Artykuł bloga: {{ $post->title }}" />

    @push('schema')
    @php
        $blogPostingSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('frontend.blog.show', $post),
            ],
            'headline' => $post->title,
            'description' => \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->body ?? ''), 160),
            'image' => $post->coverImageUrl(),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Hodowla Kotów z Mazowieckiej Szwajcarii',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Hodowla Kotów z Mazowieckiej Szwajcarii',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                ],
            ],
            'datePublished' => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($blogPostingSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
</x-frontend.shell>
