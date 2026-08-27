{{--
    Cookie Consent Banner — Mazowiecka Szwajcaria
    ─────────────────────────────────────────────────────────────────
    Lekki, czytelny i luksusowy baner RODO / GA4 Consent Mode v2.
    • Czysty Vanilla JS (zero zależności od Alpine.js — działa natychmiast)
    • Dedykowany styl o wysokim kontraście (czytelny biały/złoty tekst na ciemnym szkle)
    • Zapamiętywanie w LocalStorage ('katten_cookie_consent': 'accepted' | 'declined')
    • Po akceptacji: gtag('consent', 'update', { 'analytics_storage': 'granted' })
    • Responsywny (mobilny + desktop), nie zasłania krytycznych elementów
--}}

<div
    id="cookie-consent-banner"
    role="dialog"
    aria-label="Zgoda na pliki cookies"
    aria-live="polite"
    style="display: none;"
>
    <div class="cookie-banner-inner">
        {{-- Icon --}}
        <div class="cookie-banner-icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d1ab58" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path>
                <path d="M8.5 8.5v.01"></path>
                <path d="M7.5 15.5v.01"></path>
                <path d="M12 12v.01"></path>
                <path d="M11 17v.01"></path>
                <path d="M16 16v.01"></path>
            </svg>
        </div>

        {{-- Content --}}
        <div class="cookie-banner-content">
            <p class="cookie-banner-title">Szanujemy Twoją prywatność</p>
            <p class="cookie-banner-text">
                Używamy narzędzia <strong>Google Analytics</strong> wyłącznie w celu anonimowej analizy ruchu i ulepszania naszej strony. Nie zbieramy danych osobowych ani nie prowadzimy profilowania marketingowego. Więcej informacji znajdziesz w naszej
                <a href="{{ route('privacy') }}" class="cookie-banner-link" target="_blank" rel="noopener">Polityce Prywatności</a>.
            </p>
        </div>

        {{-- Actions --}}
        <div class="cookie-banner-actions">
            <button
                type="button"
                id="cookie-consent-decline"
                class="cookie-btn cookie-btn-decline"
                onclick="window.declineCookieConsent()"
            >
                Odrzuć
            </button>
            <button
                type="button"
                id="cookie-consent-accept"
                class="cookie-btn cookie-btn-accept"
                onclick="window.acceptCookieConsent()"
            >
                Akceptuj
            </button>
        </div>
    </div>
</div>

<style>
    #cookie-consent-banner {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(24px);
        opacity: 0;
        z-index: 999999;
        width: min(94vw, 760px);
        pointer-events: auto;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
    }

    #cookie-consent-banner.cookie-banner--visible {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .cookie-banner-inner {
        background: rgba(22, 22, 25, 0.96);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(209, 171, 88, 0.38);
        border-radius: 18px;
        box-shadow:
            0 16px 40px rgba(0, 0, 0, 0.65),
            0 2px 8px rgba(209, 171, 88, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        color: #ffffff;
        box-sizing: border-box;
    }

    .cookie-banner-icon {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(209, 171, 88, 0.14);
        border: 1px solid rgba(209, 171, 88, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cookie-banner-content {
        flex: 1;
        min-width: 0;
    }

    .cookie-banner-title {
        margin: 0 0 4px 0;
        font-size: 0.92rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        color: #ffffff;
        font-family: inherit;
    }

    .cookie-banner-text {
        margin: 0;
        font-size: 0.84rem;
        line-height: 1.5;
        color: rgba(240, 240, 245, 0.88);
        font-family: inherit;
    }

    .cookie-banner-text strong {
        color: #ffffff;
        font-weight: 600;
    }

    .cookie-banner-link {
        color: #e2c275;
        text-decoration: underline;
        text-underline-offset: 3px;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .cookie-banner-link:hover {
        color: #ffffff;
    }

    .cookie-banner-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .cookie-btn {
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        text-align: center;
        box-sizing: border-box;
        user-select: none;
    }

    .cookie-btn-decline {
        background: rgba(255, 255, 255, 0.08);
        color: rgba(245, 245, 250, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .cookie-btn-decline:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.4);
    }

    .cookie-btn-decline:active {
        transform: scale(0.97);
    }

    .cookie-btn-accept {
        background: linear-gradient(135deg, #e8cb82 0%, #d1ab58 100%);
        color: #121214;
        border: 1px solid rgba(209, 171, 88, 0.6);
        box-shadow: 0 4px 14px rgba(209, 171, 88, 0.35);
    }

    .cookie-btn-accept:hover {
        background: linear-gradient(135deg, #f0d694 0%, #deb864 100%);
        box-shadow: 0 6px 20px rgba(209, 171, 88, 0.55);
        transform: translateY(-1px);
    }

    .cookie-btn-accept:active {
        transform: translateY(0) scale(0.97);
    }

    @media (max-width: 680px) {
        #cookie-consent-banner {
            bottom: 12px;
            width: calc(100vw - 20px);
        }

        .cookie-banner-inner {
            flex-direction: column;
            align-items: flex-start;
            padding: 16px;
            gap: 14px;
        }

        .cookie-banner-icon {
            display: none;
        }

        .cookie-banner-actions {
            width: 100%;
            display: flex;
            gap: 8px;
        }

        .cookie-btn {
            flex: 1;
            padding: 10px 14px;
            font-size: 0.85rem;
        }
    }
</style>

<script>
    (function () {
        const STORAGE_KEY = 'katten_cookie_consent';

        function initCookieConsent() {
            const banner = document.getElementById('cookie-consent-banner');
            if (!banner) return;

            let consent = null;
            try {
                consent = localStorage.getItem(STORAGE_KEY);
            } catch (e) {}

            // Jeśli użytkownik już zdecydował, nic nie pokazuj
            if (consent === 'accepted' || consent === 'declined') {
                return;
            }

            // Pokaż z delikatnym opóźnieniem dla elegancji
            banner.style.display = 'block';
            requestAnimationFrame(function () {
                setTimeout(function () {
                    banner.classList.add('cookie-banner--visible');
                }, 400);
            });

            // Podpięcie przycisków przez listener (dla maksymalnej pewności)
            const acceptBtn = document.getElementById('cookie-consent-accept');
            const declineBtn = document.getElementById('cookie-consent-decline');

            if (acceptBtn) {
                acceptBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.acceptCookieConsent();
                });
            }

            if (declineBtn) {
                declineBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.declineCookieConsent();
                });
            }
        }

        window.acceptCookieConsent = function () {
            try {
                localStorage.setItem(STORAGE_KEY, 'accepted');
            } catch (e) {}

            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted'
                });
            }

            hideCookieBanner();
        };

        window.declineCookieConsent = function () {
            try {
                localStorage.setItem(STORAGE_KEY, 'declined');
            } catch (e) {}

            hideCookieBanner();
        };

        function hideCookieBanner() {
            const banner = document.getElementById('cookie-consent-banner');
            if (!banner) return;

            banner.classList.remove('cookie-banner--visible');
            setTimeout(function () {
                banner.style.display = 'none';
            }, 360);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCookieConsent);
        } else {
            initCookieConsent();
        }
    })();
</script>
