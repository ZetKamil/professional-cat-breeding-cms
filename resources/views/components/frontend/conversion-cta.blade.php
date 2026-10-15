@props([
    'eyebrow' => 'Zaproszenie do Kontaktu i Rezerwacji',
    'headline' => 'Zaplanuj Rozmowę lub Wideo-Wizytę',
    'description' => 'Chcesz poznać nasze kocięta na żywo? Włączamy kamerę w naszym salonie i odpowiadamy na wszystkie pytania — bez pośpiechu i bez zobowiązań.',
    'tile' => 'parchment',
    'note' => 'Bezpieczna rezerwacja z rodowodem SHiOZ ZOOLANDIA · Pełna dokumentacja medyczna i wyprawka',
    'sourceContext' => 'Strona hodowli',
])

{{-- Reusable High-Converting CTA Component across all frontend pages --}}
<div class="reveal-up conversion-cta-wrap">
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
                <div class="cta-block__actions-row">
                    <x-frontend.button
                        type="button"
                        variant="primary"
                        onclick="window.openReservationModal('', '', '{{ addslashes($sourceContext) }}')"
                        icon="video"
                        class="cta-block__btn">
                        📹 Umów wideo-rozmowę na żywo
                    </x-frontend.button>
                    <x-frontend.button
                        type="button"
                        variant="secondary"
                        onclick="if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', cat_name: '{{ addslashes($sourceContext) }}' }); } window.open('https://wa.me/48514153204?text=' + encodeURIComponent('Dzień dobry! Piszę ze strony hodowli Mazowiecka Szwajcaria. Chciałbym zapytać o rezerwację kociąt i wideo-wizytę na żywo.'), '_blank');"
                        icon="message-circle"
                        class="cta-block__btn">
                        💬 Porozmawiaj na WhatsApp
                    </x-frontend.button>
                </div>
                <a href="tel:+48514153204" class="cta-block__direct-phone">
                    lub zadzwoń bezpośrednio: <strong>+48 514 153 204</strong>
                </a>
            </div>

            @if($note)
                <p class="cta-block__note" role="note">
                    <i data-lucide="shield-check" aria-hidden="true" class="cta-block__note-icon"></i>
                    <span>{{ $note }}</span>
                </p>
            @endif
        </div>
    </x-frontend.section>
</div>
