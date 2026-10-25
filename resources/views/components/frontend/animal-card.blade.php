@props([
    'animal',
    'showAge' => true,
])

@php
    $cardStatusLabel = $animal->pickupBadgeLabel();
    $badgeVariant = $animal->pickupBadgeVariant();
    $timelineLabel = $animal->pickupCardTimelineLabel();
@endphp

<x-frontend.card :href="route('frontend.animals.show', $animal)" hoverable class="animal-card" aria-label="Profil kota: {{ $animal->name }} ({{ $animal->breed }})">
    <x-slot:image>
        <div class="animal-card__media-wrapper">
            @if($animal->media)
                <x-frontend.picture
                    :src="$animal->media->url()"
                    :alt="$animal->name . ' — ' . $animal->breed"
                    width="800"
                    height="800"
                    loading="lazy"
                    decoding="async"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px"
                    webp-widths="400,800"
                />
            @else
                <x-frontend.picture
                    src="{{ asset('image/hero/bars_studio.jpg') }}"
                    :alt="$animal->name . ' — ' . $animal->breed . ' (zdjęcie poglądowe)'"
                    width="800"
                    height="800"
                    loading="lazy"
                    decoding="async"
                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 400px"
                    webp-widths="400,800"
                />
            @endif

            <div class="animal-card__badge-overlay">
                <x-frontend.badge :variant="$badgeVariant">
                    {{ $cardStatusLabel }}
                </x-frontend.badge>
            </div>
        </div>
    </x-slot:image>

    <div class="animal-card__body">
        <div class="animal-card__meta">
            <span class="animal-card__breed">{{ $animal->breed }}</span>
            <span class="animal-card__sep" aria-hidden="true">·</span>
            <span class="animal-card__gender">{{ $animal->gender->symbol() }} {{ $animal->gender->label() }}</span>
            @if($showAge && $animal->age())
                <span class="animal-card__sep" aria-hidden="true">·</span>
                <span>{{ $animal->age() }}</span>
            @endif
        </div>

        <h3 class="animal-card__name">{{ $animal->name }}</h3>

        @if($timelineLabel)
            <div class="animal-card__timeline">
                <i data-lucide="calendar" class="animal-card__timeline-icon" aria-hidden="true"></i>
                <span class="animal-card__timeline-text">{{ $timelineLabel }}</span>
            </div>
        @endif

        <div class="animal-card__footer">
            @if($animal->shouldShowPrice())
                <span class="animal-card__price-badge" title="Cena kociaka (opcja „na kolanka”)">
                    {{ $animal->formattedPrice() }}
                </span>
            @else
                <span class="animal-card__quick-reserve"
                      role="button"
                      tabindex="0"
                      title="Umów wideo-wizytę na żywo"
                      onclick="event.preventDefault(); event.stopPropagation(); window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($cardStatusLabel) }}');"
                      onkeydown="if(event.key === 'Enter' || event.key === ' '){ event.preventDefault(); event.stopPropagation(); window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($cardStatusLabel) }}'); }">
                    <span class="animal-card__quick-reserve-dot"></span>
                    <span>Wideo-wizyta</span>
                </span>
            @endif

            <span class="animal-card__cta">
                Profil
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-primary" aria-hidden="true"></i>
            </span>
        </div>
    </div>
</x-frontend.card>
