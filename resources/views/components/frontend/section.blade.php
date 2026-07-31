{{--
    Section Component — Design System
    
    Usage:
    <x-frontend.section>Content here</x-frontend.section>
    <x-frontend.section tile="dark" id="about">Dark section</x-frontend.section>
    <x-frontend.section tile="parchment" padding="lg">...</x-frontend.section>
    
    Props:
    - tile: light|parchment|dark|dark-2|dark-3|black (surface color)
    - id: section anchor ID
    - padding: default|sm|lg|none (vertical padding)
    - class: additional classes
--}}

@props([
    'tile' => 'light',
    'id' => null,
    'padding' => 'default',
])

@php
    $tileClass = match($tile) {
        'light'     => 'tile-light',
        'parchment' => 'tile-parchment',
        'dark'      => 'tile-dark',
        'dark-2'    => 'tile-dark-2',
        'dark-3'    => 'tile-dark-3',
        'black'     => 'tile-black',
        default     => 'tile-light',
    };
    
    $paddingClass = match($padding) {
        'sm'      => 'section--sm',
        'lg'      => 'section--lg',
        'none'    => 'section--none',
        default   => '',
    };

    $navTheme = match($tile) {
        'dark', 'dark-2', 'dark-3', 'black' => 'dark',
        'parchment'                         => 'cream',
        default                             => 'light',
    };
@endphp

<section
    {{ $attributes->merge(['class' => "section {$tileClass} {$paddingClass}"]) }}
    @if($id) id="{{ $id }}" @endif
    data-nav-theme="{{ $navTheme }}"
>
    <div class="section-inner">
        {{ $slot }}
    </div>
</section>
