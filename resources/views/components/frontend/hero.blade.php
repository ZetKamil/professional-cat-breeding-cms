{{--
    Cinematic Hero Component — Apple / Aesop Luxury Product Experience
    
    Props:
    - title: Headline text
    - eyebrow: Optional small uppercase tagline
    - lead: Optional description text
    - imageUrl: Background image URL
    - imageAlt: Alt text for accessibility
    - scrollTarget: ID of the section to scroll to (e.g., '#o-nas')
    - align: 'left' | 'center' (default: 'left')
    - size: 'full' | 'large' (default: 'full' -> 100vh)
--}}
@props([
    'title',
    'eyebrow' => null,
    'lead' => null,
    'imageUrl' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80',
    'imageAlt' => 'Piękny kot rasowy w hodowli',
    'scrollTarget' => '#o-nas',
    'align' => 'left',
    'size' => 'full',
])

@php
    $alignClass = $align === 'center' ? 'hero--center' : 'hero--left';
    $sizeClass = $size === 'large' ? 'hero--large' : 'hero--full';
@endphp

<section {{ $attributes->merge(['class' => "hero {$alignClass} {$sizeClass}"]) }} aria-label="Powitanie">
    {{-- Background Photography --}}
    <div class="hero__bg">
        <img 
            src="{{ $imageUrl }}" 
            alt="{{ $imageAlt }}"
            class="hero__image"
            loading="eager"
            fetchpriority="high"
        >
        <div class="hero__overlay" aria-hidden="true"></div>
        <div class="hero__vignette" aria-hidden="true"></div>
    </div>

    {{-- Editorial Content Block --}}
    <div class="hero__content">
        <div class="hero__inner section-inner">
            @if($eyebrow)
                <span class="hero__eyebrow">
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 class="hero__headline text-hero-display">
                {!! $title !!}
            </h1>

            @if($lead)
                <p class="hero__lead text-lead-airy">
                    {{ $lead }}
                </p>
            @endif

            @if($slot->isNotEmpty())
                <div class="hero__actions">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>

    {{-- Cinematic Scroll Indicator --}}
    @if($scrollTarget)
        <a href="{{ $scrollTarget }}" class="hero__scroll-indicator" aria-label="Przewiń w dół, aby dowiedzieć się więcej">
            <span class="hero__scroll-mouse">
                <span class="hero__scroll-wheel"></span>
            </span>
            <span class="hero__scroll-label">Odkryj więcej</span>
        </a>
    @endif
</section>
