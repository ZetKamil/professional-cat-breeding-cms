/**
 * analytics.js — GA4 & Meta Pixel Funnel Conversion Tracking & Attribution
 * Cattery of Mazovian Switzerland (Hodowla Kotów z Mazowieckiej Szwajcarii)
 *
 * Implemented events:
 *   view_cat_profile       — viewing a specific cat profile (with attribution)
 *   open_reservation_modal — opening the reservation / video-visit modal
 *   click_whatsapp_lead    — clicking WhatsApp lead contact
 *   click_messenger_lead   — clicking Messenger lead contact
 *   click_phone_lead       — clicking direct phone call lead
 *   submit_callback_number — submitting phone number in quick callback form (lead)
 *   contact_start          — first interaction with traditional contact form
 *   contact_submit         — successful traditional form submission
 *   animal_list_view       — viewing the cat listing catalog
 *
 * Privacy & Security guarantees:
 *   • NO PII (name, email, phone number, message text) in GA4 / Meta Pixel payloads
 *   • Idempotent events prevented from duplicate triggers (sessionStorage guard)
 *   • Full compatibility with Consent Mode v2 and GDPR
 */

/* ─────────────────────────────────────────────────────────────────
   1. UTM & Traffic Attribution Engine
───────────────────────────────────────────────────────────────── */

/**
 * Captures UTM parameters and fbclid from current URL and persists in session.
 */
export function captureTrafficSource() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'fbclid'];
        let hasNewParams = false;

        keys.forEach(key => {
            if (urlParams.has(key)) {
                const val = urlParams.get(key);
                if (val) {
                    sessionStorage.setItem('katten_' + key, val);
                    try {
                        localStorage.setItem('katten_' + key, val);
                        localStorage.setItem('katten_' + key + '_ts', Date.now().toString());
                    } catch (e) {}
                    hasNewParams = true;
                }
            }
        });

        // Fallback from localStorage (up to 7 days old) if current session is empty
        if (!hasNewParams) {
            keys.forEach(key => {
                if (!sessionStorage.getItem('katten_' + key)) {
                    try {
                        const lsVal = localStorage.getItem('katten_' + key);
                        const ts = localStorage.getItem('katten_' + key + '_ts');
                        if (lsVal && ts && (Date.now() - parseInt(ts, 10)) < 7 * 24 * 60 * 60 * 1000) {
                            sessionStorage.setItem('katten_' + key, lsVal);
                        }
                    } catch (e) {}
                }
            });
        }
    } catch (e) {
        console.warn('UTM capture error:', e);
    }
}

/**
 * Returns a human-readable traffic source label for WhatsApp attribution and analytics.
 *
 * @returns {string}
 */
export function getTrafficSourceLabel() {
    try {
        const fbclid = sessionStorage.getItem('katten_fbclid');
        const source = sessionStorage.getItem('katten_utm_source') || '';
        const medium = sessionStorage.getItem('katten_utm_medium') || '';
        const campaign = sessionStorage.getItem('katten_utm_campaign') || '';

        if (fbclid || medium === 'cpc' || medium === 'paid' || source.toLowerCase().includes('fb_ad') || source.toLowerCase().includes('meta_ad')) {
            return 'FB_Ads' + (campaign ? '_' + campaign : '');
        }

        if (source.toLowerCase() === 'facebook' || source.toLowerCase() === 'fb') {
            return 'FB_Post' + (campaign ? '_' + campaign : '');
        }

        if (source.toLowerCase() === 'google') {
            return medium ? 'Google_' + medium : 'Google';
        }

        if (source) {
            return source + (campaign ? '_' + campaign : '');
        }

        // Check document.referrer
        if (document.referrer) {
            if (document.referrer.includes('facebook.com') || document.referrer.includes('instagram.com')) {
                return 'FB_Social';
            }
            if (document.referrer.includes('google.')) {
                return 'Google_Organiczny';
            }
        }

        return 'Organiczny';
    } catch (e) {
        return 'Bezposredni';
    }
}

window.getTrafficSourceLabel = getTrafficSourceLabel;

/* ─────────────────────────────────────────────────────────────────
   2. Unified Funnel Dispatcher (GA4 + Meta Pixel)
───────────────────────────────────────────────────────────────── */

/**
 * Dispatches conversion funnel events to both GA4 and Meta Pixel.
 *
 * @param {string} eventName
 * @param {Object} [params]
 */
export function trackFunnelStep(eventName, params) {
    params = params || {};
    params.traffic_source = getTrafficSourceLabel();

    // 1. Google Analytics 4
    if (typeof window.gtag === 'function') {
        window.gtag('event', eventName, params);
    }

    // 2. Meta Pixel (fbq)
    if (typeof window.fbq === 'function') {
        const leadEvents = [
            'click_whatsapp_lead',
            'click_messenger_lead',
            'click_phone_lead',
            'submit_callback_number'
        ];

        if (leadEvents.includes(eventName)) {
            window.fbq('track', 'Lead', {
                content_name: eventName,
                status: params.cat_name || 'Ogólne',
            });
        }

        window.fbq('trackCustom', eventName, params);
    }
}

window.trackFunnelStep = trackFunnelStep;

/* ─────────────────────────────────────────────────────────────────
   3. Session Helpers
───────────────────────────────────────────────────────────────── */

function sendGaEvent(eventName, params) {
    if (typeof window.gtag !== 'function') return;
    window.gtag('event', eventName, params || {});
}

function isEventSentThisSession(key) {
    try {
        return !!sessionStorage.getItem('ga_sent_' + key);
    } catch (e) {
        return false;
    }
}

function markEventSent(key) {
    try {
        sessionStorage.setItem('ga_sent_' + key, '1');
    } catch (e) {}
}

/* ─────────────────────────────────────────────────────────────────
   4. Traditional Contact Form Tracking
───────────────────────────────────────────────────────────────── */

function initContactStart() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    const SESSION_KEY = 'contact_start';

    function onContactInteraction() {
        if (isEventSentThisSession(SESSION_KEY)) return;
        markEventSent(SESSION_KEY);

        sendGaEvent('contact_start', {
            form_location: 'contact_page',
        });

        form.removeEventListener('focusin', onContactInteraction);
        form.removeEventListener('input', onContactInteraction);
    }

    form.addEventListener('focusin', onContactInteraction, { passive: true });
    form.addEventListener('input', onContactInteraction, { passive: true });
}

function initContactSubmit() {
    const successAlert = document.querySelector('.form-alert--success');
    if (!successAlert) return;

    const isContactPage = !!document.getElementById('contactForm') ||
                          window.location.pathname.includes('contact') ||
                          window.location.pathname.includes('kontakt');
    if (!isContactPage) return;

    const SESSION_KEY = 'contact_submit';
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    trackFunnelStep('contact_submit', {
        method: 'traditional_form',
    });
}

function initPhoneClick() {
    document.querySelectorAll('a[href^="tel:"]').forEach(function (link) {
        link.addEventListener('click', function () {
            trackFunnelStep('click_phone_lead', {
                link_type: 'phone_tag',
            });
        });
    });
}

function initEmailClick() {
    document.querySelectorAll('a[href^="mailto:"]').forEach(function (link) {
        link.addEventListener('click', function () {
            sendGaEvent('email_click', {
                link_type: 'email',
            });
        });
    });
}

/* ─────────────────────────────────────────────────────────────────
   5. Animal View Events
───────────────────────────────────────────────────────────────── */

function initAnimalView() {
    const profileSection = document.querySelector('[data-ga-page="animal_view"]');
    if (!profileSection) return;

    const slug = profileSection.dataset.gaAnimalSlug || window.location.pathname;
    const SESSION_KEY = 'animal_view_' + slug;
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    const breed = profileSection.dataset.gaAnimalBreed;
    const status = profileSection.dataset.gaAnimalStatus;
    const name = profileSection.dataset.gaAnimalName;

    trackFunnelStep('view_cat_profile', {
        cat_name: name || 'Nieznany',
        cat_breed: breed || 'Nieznana',
        availability_status: status || '',
    });
}

function initAnimalListView() {
    const listSection = document.querySelector('[data-ga-page="animal_list"]');
    if (!listSection) return;

    const SESSION_KEY = 'animal_list_view';
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    const breedFilter = listSection.dataset.gaBreedFilter || 'all';

    sendGaEvent('animal_list_view', {
        breed_filter: breedFilter,
    });
}

/* ─────────────────────────────────────────────────────────────────
   6. Boot Analytics
───────────────────────────────────────────────────────────────── */

export function bootAnalytics() {
    captureTrafficSource();
    initContactStart();
    initContactSubmit();
    initPhoneClick();
    initEmailClick();
    initAnimalView();
    initAnimalListView();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootAnalytics);
} else {
    bootAnalytics();
}
