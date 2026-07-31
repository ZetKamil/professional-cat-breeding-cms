{{--
    CTA Component — Luxury Editorial Invitation (Apple / Aesop style)
    
    Usage:
    <x-frontend.cta
        eyebrow="Adopcja i Kontakt"
        headline="Zainteresowany naszymi kociętami?"
        description="Skontaktuj się z nami — chętnie odpowiemy na Twoje pytania."
        buttonText="Napisz do nas"
        buttonHref="/contact"
    />
--}}

@props([
    'eyebrow' => 'Adopcja i Kontakt',
    'headline',
    'description' => null,
    'buttonText',
    'buttonHref' => '#',
    'buttonVariant' => 'primary',
    'tile' => 'parchment',
    'note' => 'Odpowiadamy zazwyczaj w ciągu 24 godzin · Pełne doradztwo przed i po adopcji',
])

<x-frontend.section :tile="$tile" padding="default" class="cta-section">
    <div class="cta-block">
        @if($eyebrow)
            <div class="cta-block__eyebrow">
                <span>{{ $eyebrow }}</span>
            </div>
        @endif

        <h2 class="cta-block__headline">
            {{ $headline }}
        </h2>

        @if($description)
            <p class="cta-block__description">
                {{ $description }}
            </p>
        @endif

        <div class="cta-block__action">
            <x-frontend.button :variant="$buttonVariant" :href="$buttonHref" icon="arrow-right" class="cta-block__btn">
                {{ $buttonText }}
            </x-frontend.button>
        </div>

        @if($note)
            <p class="cta-block__note" role="note">
                <i data-lucide="shield-check" aria-hidden="true" class="cta-block__note-icon"></i>
                <span>{{ $note }}</span>
            </p>
        @endif
    </div>
</x-frontend.section>
