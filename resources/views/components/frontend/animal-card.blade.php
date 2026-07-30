@props([
    'animal',
    'showAge' => true,
])

<x-frontend.card :href="route('frontend.animals.show', $animal)" hoverable>
    <x-slot:image>
        @if($animal->media)
            <img
                src="{{ $animal->media->url() }}"
                alt="{{ $animal->name }}"
                loading="lazy"
            >
        @else
            <img 
                src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?auto=format&fit=crop&w=800&q=80" 
                alt="Kociak (Zdjęcie poglądowe)" 
                loading="lazy"
            >
        @endif
    </x-slot:image>
    <div class="animal-card__body">
        <div class="animal-card__meta">
            <x-frontend.badge :variant="$animal->status->badgeVariant()">
                {{ $animal->status->label() }}
            </x-frontend.badge>
            <span class="animal-card__gender">{{ $animal->gender->symbol() }} {{ $animal->gender->label() }}</span>
        </div>
        <h3 class="animal-card__name text-tagline">{{ $animal->name }}</h3>
        <p class="animal-card__breed text-body">{{ $animal->breed }}</p>
        @if($showAge && $animal->date_of_birth)
            <p class="animal-card__age">Ur. {{ $animal->date_of_birth->format('d.m.Y') }}</p>
        @endif
    </div>
</x-frontend.card>
