@props([
    'eyebrow' => 'Zaproszenie do Kontaktu i Rezerwacji',
    'headline' => 'Zaplanuj Rozmowę lub Wideo-Wizytę',
    'description' => 'Chcesz poznać nasze kocięta na żywo? Wideo-wizyty i bezpośrednie zapytania realizujemy wygodnie przez Messenger lub WhatsApp — bez pośpiechu i bez zobowiązań.',
    'tile' => 'parchment',
    'note' => 'Bezpieczna rezerwacja z rodowodem SHiOZ ZOOLANDIA · Pełna dokumentacja medyczna i microchip',
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
                    <a href="https://m.me/61580668026948"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="cta-block__btn--messenger"
                       onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: @js($sourceContext), method: 'Messenger' }); } if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'pre_footer_cta', context: @js($sourceContext) }); }">
                        💬 Napisz lub umów wideo-wizytę (Messenger)
                    </a>
                    <a href="https://wa.me/48514153204?text={{ urlencode('Dzień dobry! Piszę ze strony hodowli Mazowiecka Szwajcaria. Chciałbym zapytać o kocięta i wideo-wizytę na żywo.') }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="cta-block__btn--whatsapp"
                       onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: @js($sourceContext), method: 'WhatsApp' }); } if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'pre_footer_cta', context: @js($sourceContext) }); } var src = (typeof window.getTrafficSourceLabel === 'function') ? ' [Źródło: ' + window.getTrafficSourceLabel() + ']' : ''; this.href = 'https://wa.me/48514153204?text=' + encodeURIComponent('Dzień dobry! Piszę ze strony hodowli Mazowiecka Szwajcaria. Chciałbym zapytać o kocięta i wideo-wizytę na żywo.' + src);">
                        Kontakt przez WhatsApp
                    </a>
                </div>
                <a href="tel:+48514153204" class="cta-block__direct-phone">
                    lub zadzwoń bezpośrednio: <strong>+48 514 153 204</strong>
                </a>
                <a href="https://www.facebook.com/profile.php?id=61580668026948"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="cta-block__fb-link">
                    📸 Obserwuj nas na Facebooku, by oglądać codzienne relacje z hodowli →
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
