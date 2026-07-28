@props([
    'header' => null,
])

<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    @if($header)
        <div class="card-header">
            {{ $header }}
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
