{{--
    Cookie Consent Banner
    ─────────────────────────────────────────────────────────────────
    Lekki baner RODO / ePrivacy zgodny z GA4 Consent Mode v2.
    • Alpine.js do zarządzania widocznością
    • LocalStorage do zapamiętywania decyzji ('katten_cookie_consent': 'accepted'|'declined')
    • Po kliknięciu "Akceptuj" — gtag consent update → analytics_storage: granted
    • Po kliknięciu "Odrzuć" — consent pozostaje denied, baner chowany
    • NIE zbiera żadnych danych osobowych
--}}
<div
    x-data="cookieConsent()"
    x-show="visible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    x-cloak
    id="cookie-consent-banner"
    role="dialog"
    aria-label="Baner zgody na cookies"
    aria-live="polite"
    style="
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        width: min(96vw, 660px);
        background: var(--color-surface, #1a1a1a);
        border: 1px solid rgba(209,171,88,0.25);
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.35), 0 2px 8px rgba(0,0,0,0.2);
        padding: 20px 24px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 16px;
    "
>
    {{-- Icon --}}
    <div style="flex-shrink: 0; width: 40px; height: 40px; border-radius: 50%; background: rgba(209,171,88,0.12); display: flex; align-items: center; justify-content: center;">
        <i data-lucide="cookie" style="width: 20px; height: 20px; color: var(--color-primary, #D1AB58);" aria-hidden="true"></i>
    </div>

    {{-- Text --}}
    <div style="flex: 1; min-width: 200px;">
        <p style="margin: 0; font-size: 0.875rem; line-height: 1.5; color: var(--color-ink-muted-80, #ccc);">
            Używamy <strong style="color: var(--color-ink, #f0f0f0);">Google Analytics</strong>, aby poprawiać działanie strony.
            Nie zbieramy danych osobowych. Szczegóły w
            <a href="{{ route('privacy') }}" style="color: var(--color-primary, #D1AB58); text-decoration: underline;" target="_blank" rel="noopener">Polityce Prywatności</a>.
        </p>
    </div>

    {{-- Buttons --}}
    <div style="display: flex; gap: 10px; flex-shrink: 0; flex-wrap: wrap;">
        <button
            type="button"
            @click="decline()"
            id="cookie-consent-decline"
            style="
                padding: 8px 18px;
                border-radius: 8px;
                border: 1px solid rgba(255,255,255,0.15);
                background: transparent;
                color: var(--color-ink-muted-80, #aaa);
                font-size: 0.85rem;
                font-weight: 500;
                cursor: pointer;
                transition: border-color 0.2s, color 0.2s;
                white-space: nowrap;
            "
            onmouseover="this.style.borderColor='rgba(255,255,255,0.35)'; this.style.color='var(--color-ink, #f0f0f0)';"
            onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='var(--color-ink-muted-80, #aaa)';"
        >
            Odrzuć
        </button>
        <button
            type="button"
            @click="accept()"
            id="cookie-consent-accept"
            style="
                padding: 8px 20px;
                border-radius: 8px;
                border: none;
                background: var(--color-primary, #D1AB58);
                color: #0d0d0d;
                font-size: 0.85rem;
                font-weight: 600;
                cursor: pointer;
                transition: opacity 0.2s, box-shadow 0.2s;
                white-space: nowrap;
                box-shadow: 0 2px 8px rgba(209,171,88,0.3);
            "
            onmouseover="this.style.opacity='0.9'; this.style.boxShadow='0 4px 16px rgba(209,171,88,0.45)';"
            onmouseout="this.style.opacity='1'; this.style.boxShadow='0 2px 8px rgba(209,171,88,0.3)';"
        >
            Akceptuj
        </button>
    </div>
</div>

<script>
    function cookieConsent() {
        return {
            visible: false,

            init() {
                try {
                    var saved = localStorage.getItem('katten_cookie_consent');
                    if (!saved) {
                        // Krótkie opóźnienie, żeby baner nie migał przy pierwszym renderze
                        setTimeout(() => { this.visible = true; }, 800);
                    }
                } catch(e) {
                    // localStorage niedostępne — nie pokazuj banera
                }
            },

            accept() {
                try {
                    localStorage.setItem('katten_cookie_consent', 'accepted');
                } catch(e) {}

                // GA4 Consent Mode v2 — udziel zgody
                if (typeof gtag === 'function') {
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted'
                    });
                }

                this.visible = false;
            },

            decline() {
                try {
                    localStorage.setItem('katten_cookie_consent', 'declined');
                } catch(e) {}

                // Consent pozostaje 'denied' — nic nie robimy z gtag
                this.visible = false;
            }
        };
    }
</script>
