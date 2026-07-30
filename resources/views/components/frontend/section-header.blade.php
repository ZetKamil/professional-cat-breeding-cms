{{--
    Section Header Component
    
    Usage:
    <x-frontend.section-header
        eyebrow="Nasze koty"
        headline="Poznaj naszą rodzinę"
        description="Każdy kot jest wyjątkowy..."
    />
    
    Props:
    - eyebrow: small label above headline (optional)
    - headline: main headline (required)
    - description: supporting paragraph (optional)
    - align: left|center (default: center)
--}}

@props([
    'eyebrow' => null,
    'headline',
    'description' => null,
    'align' => 'center',
])

<div class="section-header section-header--{{ $align }}">
    @if($eyebrow)
        <span class="section-header__eyebrow">
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="section-header__headline text-display-lg">
        {{ $headline }}
    </h2>

    @if($description)
        <p class="section-header__description text-lead-airy">
            {{ $description }}
        </p>
    @endif
</div>
