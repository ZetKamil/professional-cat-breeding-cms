{{--
    Cookie Consent Banner — Mazowiecka Szwajcaria
    ─────────────────────────────────────────────────────────────────
    High-converting, legally compliant GDPR / GA4 Consent Mode v2 banner.
    • Pure Vanilla JS (executes immediately, zero external dependencies)
    • Friendly, user-centric copy focused on cat video/photo loading speed
    • Primary CTA: "Akceptuj wszystkie" (prominent brand accent color, grants analytics & ads)
    • Secondary CTA: "Tylko niezbędne" (subtle outline, denies analytics & ads)
    • LocalStorage persistence ('katten_cookie_consent': 'accepted' | 'declined')
    • Responsive layout (mobile thumb-zone & desktop floating capsule)
--}}

<div
    id="cookie-consent-banner"
    role="dialog"
    aria-label="Zgoda na pliki cookies"
    aria-live="polite"
    style="display: none;"
>
    <div class="cookie-banner-inner">
        {{-- Paw / Cookie Icon --}}
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
            <p class="cookie-banner-title">Dbamy o Twój komfort! 🐾</p>
            <p class="cookie-banner-text">
                Używamy technologii takich jak pliki cookie, aby strona działała płynnie, a treści i wideo z naszymi kotami ładowały się szybciej. Klikając „Akceptuj wszystkie”, wspierasz rozwój naszej domowej hodowli.
                <a href="{{ route('privacy') }}" class="cookie-banner-link" target="_blank" rel="noopener">Polityka Prywatności</a>.
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
                Tylko niezbędne
            </button>
            <button
                type="button"
                id="cookie-consent-accept"
                class="cookie-btn cookie-btn-accept"
                onclick="window.acceptCookieConsent()"
            >
                Akceptuj wszystkie
            </button>
        </div>
    </div>
</div>

<style>
    #cookie-consent-banner {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(28px);
        opacity: 0;
        z-index: 999999;
        width: min(94vw, 820px);
        pointer-events: auto;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
    }

    #cookie-consent-banner.cookie-banner--visible {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .cookie-banner-inner {
        background: rgba(18, 18, 21, 0.96);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 20px;
        box-shadow:
            0 20px 48px rgba(0, 0, 0, 0.7),
            0 2px 10px rgba(0, 102, 204, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.12);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        color: #ffffff;
        box-sizing: border-box;
    }

    .cookie-banner-icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
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
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: -0.01em;
        color: #ffffff;
        font-family: inherit;
    }

    .cookie-banner-text {
        margin: 0;
        font-size: 0.85rem;
        line-height: 1.52;
        color: rgba(240, 240, 245, 0.85);
        font-family: inherit;
    }

    .cookie-banner-link {
        color: #d1ab58;
        text-decoration: underline;
        text-underline-offset: 3px;
        font-weight: 500;
        transition: color 0.2s ease;
        white-space: nowrap;
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
        padding: 10px 20px;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        white-space: nowrap;
        text-align: center;
        box-sizing: border-box;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    /* Secondary CTA: subtle outlined button */
    .cookie-btn-decline {
        background: transparent;
        color: rgba(240, 240, 245, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.22);
        font-weight: 500;
    }

    .cookie-btn-decline:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.42);
    }

    .cookie-btn-decline:active {
        transform: scale(0.97);
    }

    /* Primary CTA: highly prominent brand accent color */
    .cookie-btn-accept {
        background: linear-gradient(135deg, #0077ed 0%, #005bb5 100%);
        color: #ffffff;
        font-weight: 600;
        border: 1px solid rgba(0, 119, 237, 0.8);
        box-shadow: 0 4px 16px rgba(0, 102, 204, 0.45);
    }

    .cookie-btn-accept:hover {
        background: linear-gradient(135deg, #1685f7 0%, #0066cc 100%);
        box-shadow: 0 6px 22px rgba(0, 102, 204, 0.65);
        transform: translateY(-1px);
    }

    .cookie-btn-accept:active {
        transform: translateY(0) scale(0.97);
    }

    /* Hide sticky bar when cookie banner is visible on mobile to avoid overlap */
    body.cookie-banner-active .mobile-sticky-bar {
        display: none !important;
    }

    @media (max-width: 680px) {
        #cookie-consent-banner {
            bottom: 12px;
            width: calc(100vw - 20px);
        }

        .cookie-banner-inner {
            flex-direction: column;
            align-items: stretch;
            padding: 16px 16px 14px;
            gap: 12px;
            border-radius: 16px;
        }

        .cookie-banner-icon {
            display: none;
        }

        .cookie-banner-title {
            font-size: 0.92rem;
            margin-bottom: 4px;
        }

        .cookie-banner-text {
            font-size: 0.82rem;
            line-height: 1.48;
        }

        /* On mobile: Primary CTA on top, Secondary subtle button below */
        .cookie-banner-actions {
            width: 100%;
            display: flex;
            flex-direction: column-reverse;
            gap: 8px;
            margin-top: 4px;
        }

        .cookie-btn {
            width: 100%;
            padding: 11px 16px;
            font-size: 0.88rem;
        }

        .cookie-btn-decline {
            border: 1px solid rgba(255, 255, 255, 0.14);
            font-size: 0.82rem;
            padding: 9px 16px;
            color: rgba(240, 240, 245, 0.65);
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

            // If user has already made a choice, do not show banner
            if (consent === 'accepted' || consent === 'declined') {
                return;
            }

            // Display banner with smooth micro-delay for polished entrance
            banner.style.display = 'block';
            document.body.classList.add('cookie-banner-active');

            requestAnimationFrame(function () {
                setTimeout(function () {
                    banner.classList.add('cookie-banner--visible');
                }, 350);
            });

            // Bind click handlers explicitly to ensure reliable event handling
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

            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'cookie_consent_accepted',
                'consent_status': 'granted'
            });

            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'analytics_storage': 'granted',
                    'ad_storage': 'granted',
                    'ad_user_data': 'granted',
                    'ad_personalization': 'granted'
                });
            }

            if (typeof fbq === 'function') {
                fbq('consent', 'grant');
            }

            hideCookieBanner();
        };

        window.declineCookieConsent = function () {
            try {
                localStorage.setItem(STORAGE_KEY, 'declined');
            } catch (e) {}

            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'cookie_consent_declined',
                'consent_status': 'denied'
            });

            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    'analytics_storage': 'denied',
                    'ad_storage': 'denied',
                    'ad_user_data': 'denied',
                    'ad_personalization': 'denied'
                });
            }

            if (typeof fbq === 'function') {
                fbq('consent', 'revoke');
            }

            hideCookieBanner();
        };

        function hideCookieBanner() {
            const banner = document.getElementById('cookie-consent-banner');
            if (!banner) return;

            document.body.classList.remove('cookie-banner-active');
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
