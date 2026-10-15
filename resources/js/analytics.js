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

/**
 * Strips any potential Personally Identifiable Information (PII) before transmission.
 *
 * @param {Object} raw
 * @returns {Object}
 */
export function sanitizeAnalyticsParams(raw) {
    const clean = Object.assign({}, raw || {});
    delete clean.phone;
    delete clean.email;
    delete clean.name;
    delete clean.message;
    delete clean.telephone;
    delete clean._hp_website;
    delete clean._token;
    return clean;
}

window.sanitizeAnalyticsParams = sanitizeAnalyticsParams;

/* ─────────────────────────────────────────────────────────────────
   2. Unified Funnel Dispatcher (GA4 + Meta Pixel)
───────────────────────────────────────────────────────────────── */

/**
 * Dispatches conversion funnel events to both GA4 and Meta Pixel.
 *
 * 3-Level Conversion Architecture:
 *   Level 1: contact_cta_click  — User clicked a contact CTA (WhatsApp, Messenger, Phone, Video-Visit).
 *                                  Tracked in Meta as Contact (NOT Lead) to avoid false positive leads.
 *   Level 2: callback_submitted — Valid callback request or form submission accepted by server.
 *                                  Tracked in Meta as Lead (actual submission).
 *   Level 3: qualified_lead     — Real prospect interaction confirmed offline/by breeder.
 *
 * @param {string} eventName
 * @param {Object} [params]
 */
export function trackFunnelStep(eventName, params) {
    try {
        const cleanParams = sanitizeAnalyticsParams(params);
        cleanParams.traffic_source = getTrafficSourceLabel();

        // 1. Google Analytics 4 (Safe Execution)
        if (typeof window.gtag === 'function') {
            try {
                window.gtag('event', eventName, cleanParams);
            } catch (err) {
                // Silently swallow third-party tracking errors
            }
        }

        // 2. Meta Pixel (Safe Execution)
        if (typeof window.fbq === 'function') {
            try {
                // Level 1: Contact CTA Click (Meta Standard 'Contact', NOT 'Lead')
                if (
                    eventName === 'contact_cta_click' ||
                    eventName === 'click_whatsapp_lead' ||
                    eventName === 'click_messenger_lead' ||
                    eventName === 'click_phone_lead'
                ) {
                    window.fbq('track', 'Contact', {
                        content_name: 'contact_cta_click',
                        channel: cleanParams.channel || eventName,
                        status: cleanParams.cat_name || 'General',
                    });
                } 
                // Level 2: Server-Accepted Callback / Form Submission (True Meta 'Lead')
                else if (
                    eventName === 'callback_submitted' ||
                    eventName === 'submit_callback_number' ||
                    eventName === 'contact_submit'
                ) {
                    window.fbq('track', 'Lead', {
                        content_name: 'callback_submitted',
                        status: cleanParams.cat_name || 'General',
                    });
                } 
                // Level 3: Qualified prospect inquiry confirmed in practice
                else if (eventName === 'qualified_lead') {
                    window.fbq('trackCustom', 'qualified_lead', cleanParams);
                }

                // Custom event for DebugView & Meta Events Manager granular visibility
                window.fbq('trackCustom', eventName, cleanParams);
            } catch (err) {
                // Silently swallow pixel errors
            }
        }
    } catch (e) {
        console.warn('Analytics dispatch error:', e);
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

    trackFunnelStep('callback_submitted', {
        channel: 'traditional_form',
    });
}

function initPhoneClick() {
    document.querySelectorAll('a[href^="tel:"]').forEach(function (link) {
        link.addEventListener('click', function () {
            trackFunnelStep('contact_cta_click', {
                channel: 'phone',
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
