{{--
    Badge Component — Design System
    
    Usage:
    <x-frontend.badge>Dostępny</x-frontend.badge>
    <x-frontend.badge variant="muted">Zarezerwowany</x-frontend.badge>
    <x-frontend.badge variant="success">Zdrowy</x-frontend.badge>
    
    Props:
    - variant: default|muted|success|warning|error
--}}

@props([
    'variant' => 'default',
])

@php
    $variantClass = match($variant) {
        'muted'   => 'badge--muted',
        'success' => 'badge--success',
        'warning' => 'badge--warning',
        'error'   => 'badge--error',
        default   => 'badge--default',
    };
@endphp

<span {{ $attributes->merge(['class' => "badge {$variantClass}"]) }}>
    {{ $slot }}
</span>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        padding: var(--sp-xxs) var(--sp-sm);
        font-size: var(--text-nav-size);
        font-weight: 600;
        letter-spacing: var(--text-nav-ls);
        line-height: var(--text-nav-lh);
        border-radius: var(--r-pill);
        white-space: nowrap;
    }

    .badge--default {
        background-color: var(--color-primary);
        color: var(--color-canvas);
    }

    .badge--muted {
        background-color: var(--color-canvas-parchment);
        color: var(--color-ink-muted-48);
    }

    .badge--success {
        background-color: rgba(52, 199, 89, 0.12);
        color: var(--color-success);
    }

    .badge--warning {
        background-color: rgba(255, 159, 10, 0.12);
        color: var(--color-warning);
    }

    .badge--error {
        background-color: rgba(255, 59, 48, 0.12);
        color: var(--color-error);
    }
</style>
