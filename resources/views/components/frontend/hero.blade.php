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
    'imageUrl' => null,
    'imageAlt' => 'Piękny kot rasowy w hodowli',
    'scrollTarget' => '#o-nas',
    'align' => 'left',
    'size' => 'full',
])

@php
    $alignClass = $align === 'center' ? 'hero--center' : 'hero--left';
    $sizeClass = $size === 'large' ? 'hero--large' : 'hero--full';
    $resolvedImageUrl = $imageUrl ?: asset('image/hero/cinematic_bg.jpg');
@endphp

<section {{ $attributes->merge(['class' => "hero {$alignClass} {$sizeClass}", 'data-nav-theme' => 'dark']) }} aria-label="Powitanie">
    {{-- Background Photography --}}
    <div class="hero__bg">
        <x-frontend.picture 
            :src="$resolvedImageUrl" 
            :alt="$imageAlt"
            class="hero__image"
            width="2000"
            height="1200"
            decoding="async"
            loading="eager"
            fetchpriority="high"
            sizes="100vw"
        />
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


</section>
