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

