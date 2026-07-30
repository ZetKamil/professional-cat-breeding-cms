@props([
    'animal',
    'showAge' => true,
])

<x-frontend.card :href="route('frontend.animals.show', $animal)" hoverable class="animal-card">
    <x-slot:image>
        <div class="animal-card__media-wrapper">
            @if($animal->media)
                <img
                    src="{{ $animal->media->url() }}"
                    alt="{{ $animal->name }}"
                    loading="lazy"
                    decoding="async"
                    width="800"
                    height="600"
                >
            @else
                <img 
                    src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=800&q=80" 
                    alt="Kociak (Zdjęcie poglądowe)" 
                    loading="lazy"
                    decoding="async"
                    width="800"
                    height="600"
                >
            @endif

            <div class="animal-card__badge-overlay">
                <x-frontend.badge :variant="$animal->status->badgeVariant()">
                    {{ $animal->status->label() }}
                </x-frontend.badge>
            </div>
        </div>
    </x-slot:image>

    <div class="animal-card__body">
        <div class="animal-card__meta">
            <span class="animal-card__breed">{{ $animal->breed }}</span>
            <span class="animal-card__sep" aria-hidden="true">·</span>
            <span class="animal-card__gender">{{ $animal->gender->symbol() }} {{ $animal->gender->label() }}</span>
        </div>

        <h3 class="animal-card__name">{{ $animal->name }}</h3>

        @if($showAge && $animal->date_of_birth)
            <p class="animal-card__age">Ur. {{ $animal->date_of_birth->format('d.m.Y') }}</p>
        @endif
    </div>
</x-frontend.card>
