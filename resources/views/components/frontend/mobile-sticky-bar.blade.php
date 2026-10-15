{{--
    Mobile Sticky Action Bar — Mazovian Switzerland
    ─────────────────────────────────────────────────────────────────
    Fast-access action bar pinned to the bottom of the viewport
    on mobile viewports (within primary thumb zone).
--}}

@php
    $currentAnimal = isset($animal) && $animal instanceof \App\Models\Animal ? $animal : null;
@endphp

<aside class="mobile-sticky-bar" role="toolbar" aria-label="Szybki kontakt z hodowlą">
    <div class="mobile-sticky-bar__inner">
        
        {{-- Button 1: Live Video-Visit via WhatsApp --}}
        <button 
            type="button" 
            class="mobile-sticky-bar__btn mobile-sticky-bar__btn--video"
            onclick="window.openReservationModal('{{ $currentAnimal ? addslashes($currentAnimal->name) : '' }}', '{{ $currentAnimal ? addslashes($currentAnimal->breed) : '' }}', '{{ $currentAnimal ? addslashes($currentAnimal->statusLabel()) : '' }}')"
        >
            <span class="mobile-sticky-bar__icon" aria-hidden="true">📹</span>
            <span class="mobile-sticky-bar__label">
                <strong>Wideo-wizyta</strong>
                <small>WhatsApp na żywo</small>
            </span>
        </button>

        {{-- Button 2: Direct Phone Call --}}
        <a 
            href="tel:+48514153204" 
            class="mobile-sticky-bar__btn mobile-sticky-bar__btn--phone"
            onclick="if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'phone', location: 'sticky_bar' }); }"
        >
            <span class="mobile-sticky-bar__icon" aria-hidden="true">📞</span>
            <span class="mobile-sticky-bar__label">
                <strong>Zadzwoń</strong>
                <small>+48 514 153 204</small>
            </span>
        </a>

    </div>
</aside>
