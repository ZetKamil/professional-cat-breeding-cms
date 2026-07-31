{{--
    Blog Card Component — Luxury Design System (Apple / Aesop Editorial style)

    Usage:
    <x-frontend.blog-card :post="$post" />
--}}

@props(['post'])

@php
    $wordCount = str_word_count(strip_tags($post->body ?? ''));
    $readTime = max(1, (int) ceil($wordCount / 200));
    $category = $post->categories->first();
@endphp

<x-frontend.card :href="route('frontend.blog.show', $post)" hoverable class="blog-card">
    <x-slot:image>
        <div class="blog-card__media-wrapper">
            @if($post->media)
                <img
                    src="{{ $post->media->url() }}"
                    alt="{{ $post->title }}"
                    width="800"
                    height="500"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <img 
                    src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?q=80&w=800&auto=format&fit=crop" 
                    alt="{{ $post->title }} (Zdjęcie poglądowe)"
                    width="800"
                    height="500"
                    loading="lazy"
                    decoding="async"
                >
            @endif

            {{-- @if($category)
                <div class="blog-card__badge-overlay">
                    <x-frontend.badge variant="muted">
                        {{ $category->name }}
                    </x-frontend.badge>
                </div>
            @endif --}}
        </div>
    </x-slot:image>

    <div class="blog-card__body">
        <div class="blog-card__meta">
            <time datetime="{{ $post->published_at?->toIso8601String() }}">
                {{ $post->published_at?->format('d.m.Y') }}
            </time>
            <span class="blog-card__sep" aria-hidden="true">·</span>
            <span>{{ $readTime }} min czytania</span>
        </div>

        <h3 class="blog-card__title">{{ $post->title }}</h3>

        <p class="blog-card__excerpt">
            {{ Str::limit(strip_tags($post->body ?? ''), 115) }}
        </p>

        <div class="blog-card__read-more">
            <span>Czytaj artykuł</span>
            <i data-lucide="arrow-right" aria-hidden="true"></i>
        </div>
    </div>
</x-frontend.card>
