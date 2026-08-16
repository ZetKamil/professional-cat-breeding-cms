{{--
    Card Component — Design System
    
    Usage:
    <x-frontend.card>
        <x-slot:image>
            <img src="/photo.webp" alt="Description" />
        </x-slot:image>
        <h3>Title</h3>
        <p>Description</p>
    </x-frontend.card>
    
    <x-frontend.card href="/animals/luna" hoverable>
        Content...
    </x-frontend.card>
    
    Props:
    - href: if set, entire card becomes a link
    - hoverable: adds hover lift effect
    - class: additional classes
--}}

@props([
    'href' => null,
    'hoverable' => false,
])

@php
    $classes = 'card';
    if ($hoverable) $classes .= ' card--hoverable';
    if ($href) $classes .= ' card--linked';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if(isset($image))
            <div class="card-media">
                {{ $image }}
            </div>
        @endif
        <div class="card-body">
            {{ $slot }}
        </div>
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        @if(isset($image))
            <div class="card-media">
                {{ $image }}
            </div>
        @endif
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>
@endif

