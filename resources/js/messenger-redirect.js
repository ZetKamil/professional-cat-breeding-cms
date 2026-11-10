/**
 * Mobile & iOS Messenger Redirect Handler
 * Hodowla Kotow z Mazowieckiej Szwajcarii
 *
 * - Keeps Android Messenger links opening natively without disruption.
 * - Removes `target="_blank"` on mobile devices (prevents Meta In-App Browser
 *   WebView from forcing a Facebook web login wall in a blank tab).
 * - On iOS (iPhone / iPad / iPod), attempts native scheme
 *   `fb-messenger://user-thread/61580668026948` with a visibility-aware
 *   same-window fallback to `https://m.me/61580668026948`.
 */

const MESSENGER_WEB_URL = 'https://m.me/61580668026948';
const MESSENGER_APP_SCHEME = 'fb-messenger://user-thread/61580668026948';
const IOS_FALLBACK_DELAY_MS = 1200;

export function isIosDevice() {
    if (typeof navigator === 'undefined') return false;
    const ua = navigator.userAgent || '';
    if (/iPhone|iPad|iPod/i.test(ua)) return true;

    // iPadOS 13+ may report Macintosh desktop UA while supporting multi-touch
    return navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
}

export function isAndroidDevice() {
    if (typeof navigator === 'undefined') return false;
    return /Android/i.test(navigator.userAgent || '');
}

export function isMobileDevice() {
    if (typeof navigator === 'undefined') return false;
    const ua = navigator.userAgent || '';
    if (isIosDevice() || isAndroidDevice()) return true;
    if (/Mobile|Tablet|webOS|BlackBerry|IEMobile|Opera Mini/i.test(ua)) return true;

    return typeof window !== 'undefined'
        && typeof window.matchMedia === 'function'
        && window.matchMedia('(max-width: 767.98px)').matches;
}

export function sanitizeMobileMessengerLinks(root = document) {
    if (!root || typeof root.querySelectorAll !== 'function') return;
    if (!isMobileDevice()) return;

    const links = root.querySelectorAll('a[href*="m.me/"]');
    links.forEach((link) => {
        if (link.getAttribute('target') === '_blank') {
            link.removeAttribute('target');
        }
    });
}

let activeFallbackTimer = null;

export function openMessengerUrl(fallbackUrl) {
    const webUrl = (typeof fallbackUrl === 'string' && fallbackUrl.includes('m.me/'))
        ? fallbackUrl
        : MESSENGER_WEB_URL;

    if (isIosDevice()) {
        if (activeFallbackTimer) {
            window.clearTimeout(activeFallbackTimer);
            activeFallbackTimer = null;
        }

        const cleanup = () => {
            if (activeFallbackTimer) {
                window.clearTimeout(activeFallbackTimer);
                activeFallbackTimer = null;
            }
            document.removeEventListener('visibilitychange', onVisibilityChange);
            window.removeEventListener('pagehide', cleanup);
            window.removeEventListener('blur', cleanup);
        };

        const onVisibilityChange = () => {
            if (document.hidden) {
                cleanup();
            }
        };

        document.addEventListener('visibilitychange', onVisibilityChange);
        window.addEventListener('pagehide', cleanup, { once: true });
        window.addEventListener('blur', cleanup, { once: true });

        activeFallbackTimer = window.setTimeout(() => {
            cleanup();
            window.location.assign(webUrl);
        }, IOS_FALLBACK_DELAY_MS);

        // Trigger native iOS Messenger app directly within user gesture
        window.location.href = MESSENGER_APP_SCHEME;
        return;
    }

    if (isMobileDevice()) {
        // Android / Mobile web: navigate in the same window so OS intent opens cleanly
        window.location.assign(webUrl);
        return;
    }

    window.open(webUrl, '_blank', 'noopener,noreferrer');
}

function handleDocumentMessengerClick(event) {
    if (event.defaultPrevented) return;
    if (!event.target || typeof event.target.closest !== 'function') return;

    const link = event.target.closest('a[href*="m.me/"]');
    if (!link) return;

    if (isMobileDevice()) {
        link.removeAttribute('target');
    }

    if (isIosDevice()) {
        event.preventDefault();
        openMessengerUrl(link.getAttribute('href') || link.href || MESSENGER_WEB_URL);
    }
}

export function initMessengerRedirect() {
    if (typeof document === 'undefined') return;

    sanitizeMobileMessengerLinks(document);

    if (!window.__kattenMessengerRedirectBound) {
        window.__kattenMessengerRedirectBound = true;
        document.addEventListener('click', handleDocumentMessengerClick);
        window.addEventListener('resize', () => sanitizeMobileMessengerLinks(document), { passive: true });
        document.addEventListener('livewire:navigated', () => sanitizeMobileMessengerLinks(document));
    }

    window.openMessengerLink = openMessengerUrl;
    window.sanitizeMobileMessengerLinks = sanitizeMobileMessengerLinks;
    window.isIosDevice = isIosDevice;
}

if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMessengerRedirect);
    } else {
        initMessengerRedirect();
    }
}
