{{--
    Button Component — Design System
    
    Usage:
    <x-frontend.button>Label</x-frontend.button>
    <x-frontend.button variant="secondary" href="/contact">Contact</x-frontend.button>
    <x-frontend.button variant="ghost" icon="arrow-right">Next</x-frontend.button>
    
    Props:
    - variant: primary|secondary|dark-utility|pearl|store-hero|icon|ghost|destructive
    - href: if set, renders as <a>, otherwise <button>
    - icon: Lucide icon name (appended after text)
    - type: button|submit|reset (for <button> only)
    - disabled: boolean
    - class: additional classes
--}}

@props([
    'variant' => 'primary',
    'href' => null,
    'icon' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
    $variantClass = match($variant) {
        'primary'      => 'btn-primary',
        'secondary'    => 'btn-secondary',
        'dark-utility' => 'btn-dark-utility',
        'pearl'        => 'btn-pearl',
        'store-hero'   => 'btn-store-hero',
        'icon'         => 'btn-icon',
        'ghost'        => 'btn-ghost',
        'destructive'  => 'btn-destructive',
        default        => 'btn-primary',
    };
    
    $classes = "btn {$variantClass}";
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) aria-disabled="true" tabindex="-1" @endif
    >
        {{ $slot }}
        @if($icon)
            <i data-lucide="{{ $icon }}" aria-hidden="true"></i>
        @endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) disabled aria-disabled="true" @endif
    >
        {{ $slot }}
        @if($icon)
            <i data-lucide="{{ $icon }}" aria-hidden="true"></i>
        @endif
    </button>
@endif
