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

<style>
    .section-header {
        margin-bottom: var(--sp-2xl);
        max-width: 720px;
    }

    .section-header--center {
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }

    .section-header--left {
        text-align: left;
    }

    .section-header__eyebrow {
        display: inline-block;
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--color-primary);
        margin-bottom: var(--sp-sm);
    }

    .section-header__headline {
        text-wrap: balance;
    }

    .section-header__description {
        margin-top: var(--sp-md);
        text-wrap: balance;
    }

    /* Dark tile overrides */
    .tile-dark .section-header__eyebrow,
    .tile-black .section-header__eyebrow {
        color: var(--color-primary-on-dark);
    }

    .tile-dark .section-header__headline,
    .tile-black .section-header__headline {
        color: var(--color-canvas);
    }

    .tile-dark .section-header__description,
    .tile-black .section-header__description {
        color: var(--color-body-muted);
    }
</style>
