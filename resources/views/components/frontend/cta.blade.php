{{--
    CTA Component — Call to Action block
    
    Usage:
    <x-frontend.cta
        headline="Zainteresowany naszymi kociętami?"
        description="Skontaktuj się z nami — chętnie odpowiemy na Twoje pytania."
        buttonText="Napisz do nas"
        buttonHref="/contact"
    />
    
    <x-frontend.cta tile="dark" ...>
    
    Props:
    - headline: main CTA headline
    - description: supporting text
    - buttonText: CTA button label
    - buttonHref: CTA button link
    - buttonVariant: button variant (default: primary)
    - tile: section tile color (default: parchment)
--}}

@props([
    'headline',
    'description' => null,
    'buttonText',
    'buttonHref' => '#',
    'buttonVariant' => 'primary',
    'tile' => 'parchment',
])

<x-frontend.section :tile="$tile" padding="lg">
    <div class="cta-block">
        <h2 class="cta-block__headline text-display-lg">
            {{ $headline }}
        </h2>

        @if($description)
            <p class="cta-block__description text-lead-airy">
                {{ $description }}
            </p>
        @endif

        <div class="cta-block__action">
            <x-frontend.button :variant="$buttonVariant" :href="$buttonHref" icon="arrow-right">
                {{ $buttonText }}
            </x-frontend.button>
        </div>
    </div>
</x-frontend.section>
