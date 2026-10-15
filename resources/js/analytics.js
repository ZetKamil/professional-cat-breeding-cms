/**
 * analytics.js — GA4 & Meta Pixel Funnel Conversion Tracking & Attribution
 * Cattery of Mazovian Switzerland (Hodowla Kotów z Mazowieckiej Szwajcarii)
 *
 * Core Principles:
 *   1. Strict Separation of Intent from Real Outcomes:
 *      • view_cat_profile    — genuine cat profile view
 *      • quiz_started        — visitor began the quiz
 *      • quiz_completed      — visitor received a valid recommendation
 *      • contact_cta_click   — user clicked a contact CTA (WhatsApp, Phone, Messenger, Modal)
 *                              -> Mapped in Meta Pixel to standard 'Contact' (NEVER 'Lead')
 *      • callback_submitted  — server accepted a valid callback / form submission (HTTP 200)
 *                              -> Mapped in Meta Pixel to standard 'Lead'
 *      • qualified_lead      — verified inquiry qualified by breeder offline/CRM
 *      • sale_completed      — confirmed kitten sale/reservation recorded via breeder process
 *                              -> Mapped in Meta Pixel to standard 'Purchase'
 *
 *   2. Zero PII Guarantee:
 *      • Phone numbers, emails, customer names, and message bodies are scrubbed
 *        before any event payload is sent to GA4 or Meta Pixel.
 *
 *   3. Truthful Attribution & Safe Fallback:
 *      • Malicious characters in UTM parameters are stripped.
 *      • Unidentified visits fallback to 'Bezposredni' (Direct), NEVER falsely labelled as 'Organiczny'.
 *      • 'Organiczny' is reserved strictly for verifiable search engine referrers.
 *
 *   4. Duplicate & Re-render Protection:
 *      • Global event delegation on document prevents duplicate listeners from Livewire updates.
 *      • In-memory debouncer prevents duplicate rapid dispatches (< 600ms).
 */

/* ─────────────────────────────────────────────────────────────────
   1. UTM & Traffic Attribution Engine
───────────────────────────────────────────────────────────────── */

/**
 * Sanitizes untrusted query string values to prevent XSS or broken payloads.
 * Restricts to alphanumeric, underscore, hyphen, and dot; max 80 chars.
 *
 * @param {string|null} val
 * @returns {string}
 */
export function sanitizeUtmValue(val) {
    if (!val || typeof val !== 'string') return '';
    // Strip HTML/script tags and unsafe characters
    let clean = val.replace(/[<>"'`;\\{}()=]/g, '').trim();
    // Allow standard alphanumeric, polish characters, hyphens, underscores and dots
    clean = clean.replace(/[^\w\s\-.\u0100-\u017F]/g, '');
    return clean.slice(0, 80);
}

window.sanitizeUtmValue = sanitizeUtmValue;

/**
 * Captures UTM parameters and fbclid from current URL and persists in session/localStorage.
 */
export function captureTrafficSource() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'fbclid'];
        let hasNewParams = false;

        keys.forEach(key => {
            if (urlParams.has(key)) {
                const rawVal = urlParams.get(key);
                const safeVal = sanitizeUtmValue(rawVal);
                if (safeVal) {
                    sessionStorage.setItem('katten_' + key, safeVal);
                    try {
                        localStorage.setItem('katten_' + key, safeVal);
                        localStorage.setItem('katten_' + key + '_ts', Date.now().toString());
                    } catch (e) {}
                    hasNewParams = true;
                }
            }
        });

        // Fallback from localStorage (up to 7 days old) if current session has no UTMs
        if (!hasNewParams) {
            keys.forEach(key => {
                if (!sessionStorage.getItem('katten_' + key)) {
                    try {
                        const lsVal = localStorage.getItem('katten_' + key);
                        const ts = localStorage.getItem('katten_' + key + '_ts');
                        if (lsVal && ts && (Date.now() - parseInt(ts, 10)) < 7 * 24 * 60 * 60 * 1000) {
                            sessionStorage.setItem('katten_' + key, sanitizeUtmValue(lsVal));
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
 * Returns a human-readable, safe traffic source label for WhatsApp attribution and analytics.
 * Fallback policy:
 *   - Verifiable Meta ads/FB click -> 'FB_Ads[_Campaign]'
 *   - Verifiable Facebook post/organic -> 'FB_Post[_Campaign]'
 *   - Verifiable Google Paid -> 'Google_Ads[_Campaign]'
 *   - Verifiable Custom UTM -> '[Source]_[Campaign]'
 *   - Verifiable Social referrer -> 'Social_[Network]'
 *   - Verifiable Search referrer -> 'Google_Organiczny' or 'Search_Organiczny'
 *   - Verifiable External referrer -> 'Odsylacz_[Domain]'
 *   - Unidentified visit (no referrer, no UTM) -> 'Bezposredni' (NEVER assumed as organic)
 *
 * @returns {string}
 */
export function getTrafficSourceLabel() {
    try {
        const fbclid = sessionStorage.getItem('katten_fbclid');
        const source = (sessionStorage.getItem('katten_utm_source') || '').toLowerCase().trim();
        const medium = (sessionStorage.getItem('katten_utm_medium') || '').toLowerCase().trim();
        const campaign = sessionStorage.getItem('katten_utm_campaign') || '';
        const safeCampaign = sanitizeUtmValue(campaign);

        // 1. Meta / Facebook Paid Ads
        if (fbclid || medium === 'cpc' || medium === 'paid' || source.includes('fb_ad') || source.includes('meta_ad')) {
            return 'FB_Ads' + (safeCampaign ? '_' + safeCampaign : '');
        }

        // 2. Facebook Organic Posts
        if (source === 'facebook' || source === 'fb') {
            return 'FB_Post' + (safeCampaign ? '_' + safeCampaign : '');
        }

        // 3. Google Ads
        if (source === 'google' && (medium === 'cpc' || medium === 'paid')) {
            return 'Google_Ads' + (safeCampaign ? '_' + safeCampaign : '');
        }

        // 4. Custom specified UTM Source
        if (source) {
            const safeSource = sanitizeUtmValue(source);
            return safeSource + (safeCampaign ? '_' + safeCampaign : '');
        }

        // 5. Referrer Inspection
        if (document.referrer) {
            try {
                const refUrl = new URL(document.referrer);
                const host = refUrl.hostname.toLowerCase();

                // If same origin (internal navigation), check if we already have an attribution
                if (host === window.location.hostname) {
                    return 'Bezposredni';
                }

                // Social Networks
                if (host.includes('facebook.com') || host.includes('instagram.com') || host.includes('m.me')) {
                    return 'FB_Social';
                }
                if (host.includes('t.co') || host.includes('twitter.com') || host.includes('x.com')) {
                    return 'Twitter_Social';
                }
                if (host.includes('tiktok.com')) {
                    return 'TikTok_Social';
                }
                if (host.includes('youtube.com')) {
                    return 'YouTube_Social';
                }

                // Search Engines (Genuine Organic Search)
                if (host.includes('google.')) {
                    return 'Google_Organiczny';
                }
                if (host.includes('bing.') || host.includes('duckduckgo.') || host.includes('yahoo.')) {
                    return 'Search_Organiczny';
                }

                // Generic External Referral
                return 'Odsylacz';
            } catch (e) {
                // If URL parsing fails, continue to direct
            }
        }

        // 6. Safe Truthful Fallback for missing UTMs and empty referrer
        return 'Bezposredni';
    } catch (e) {
        return 'Bezposredni';
    }
}

window.getTrafficSourceLabel = getTrafficSourceLabel;

/* ─────────────────────────────────────────────────────────────────
   2. Zero-PII Data Sanitization
───────────────────────────────────────────────────────────────── */

/**
 * Strips all Personally Identifiable Information (PII) before transmission to analytics.
 * Removes phone, email, name, message fields and any string containing emails or phone numbers.
 *
 * @param {Object} raw
 * @returns {Object}
 */
export function sanitizeAnalyticsParams(raw) {
    if (!raw || typeof raw !== 'object') return {};

    const clean = {};
    const piiKeyPattern = /^(phone|tel|email|mail|imie|nazwisk|address|adres|wiadomosc|message|fullphone|rawphone|_token|_hp)/i;
    const emailPattern = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
    const phonePattern = /(?:\+?48)?(?:\s|-)?(?:[0-9]{3})(?:\s|-)?(?:[0-9]{3})(?:\s|-)?(?:[0-9]{3})/;

    Object.keys(raw).forEach(key => {
        // Drop any PII key
        if (piiKeyPattern.test(key)) {
            return;
        }

        const val = raw[key];

        // Scrub string values that might accidentally contain email or phone
        if (typeof val === 'string') {
            if (emailPattern.test(val) || phonePattern.test(val)) {
                return; // Drop values with embedded PII
            }
            // Strip potential control characters
            clean[key] = val.replace(/[<>"'`]/g, '').trim().slice(0, 120);
        } else if (typeof val === 'number' || typeof val === 'boolean') {
            clean[key] = val;
        }
    });

    return clean;
}

window.sanitizeAnalyticsParams = sanitizeAnalyticsParams;

/* ─────────────────────────────────────────────────────────────────
   3. Unified Funnel Dispatcher (GA4 + Meta Pixel)
───────────────────────────────────────────────────────────────── */

const recentDispatches = new Map();

/**
 * Dispatches conversion funnel events to both GA4 and Meta Pixel with deduplication.
 *
 * Distinct Event Meanings:
 *   • view_cat_profile   — genuine cat profile view
 *   • quiz_started       — visitor started the quiz
 *   • quiz_completed     — visitor received result
 *   • contact_cta_click  — user clicked contact CTA (mapped to Meta 'Contact', NOT 'Lead')
 *   • callback_submitted — server confirmed acceptance of callback request (mapped to Meta 'Lead')
 *   • qualified_lead     — breeder verified qualified inquiry
 *   • sale_completed     — breeder confirmed kitten sale / reservation
 *
 * @param {string} eventName
 * @param {Object} [params]
 */
export function trackFunnelStep(eventName, params) {
    try {
        if (!eventName || typeof eventName !== 'string') return;

        // In-memory debouncer: prevent duplicate rapid dispatches (< 600ms)
        const channelKey = params?.channel || params?.location || '';
        const catKey = params?.cat_name || '';
        const dedupeKey = `${eventName}_${channelKey}_${catKey}`;
        const now = Date.now();

        if (recentDispatches.has(dedupeKey) && (now - recentDispatches.get(dedupeKey) < 600)) {
            return;
        }
        recentDispatches.set(dedupeKey, now);

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

        // 2. Meta Pixel (Safe Execution with strict standard event mapping)
        if (typeof window.fbq === 'function') {
            try {
                // Intent Level: Contact CTA click (Meta Standard 'Contact', NEVER 'Lead')
                if (eventName === 'contact_cta_click') {
                    window.fbq('track', 'Contact', {
                        content_name: 'contact_cta_click',
                        channel: cleanParams.channel || 'general',
                        cat_name: cleanParams.cat_name || 'General',
                    });
                } 
                // Conversion Level: Server-accepted callback or form submission (Meta Standard 'Lead')
                else if (eventName === 'callback_submitted') {
                    window.fbq('track', 'Lead', {
                        content_name: 'callback_submitted',
                        channel: cleanParams.channel || 'quick_callback',
                        cat_name: cleanParams.cat_name || 'General',
                    });
                }
                // Qualification Level: Breeder-verified qualified inquiry
                else if (eventName === 'qualified_lead') {
                    window.fbq('trackCustom', 'qualified_lead', cleanParams);
                }
                // Outcome Level: Confirmed kitten sale / reservation
                else if (eventName === 'sale_completed') {
                    window.fbq('track', 'Purchase', {
                        content_name: cleanParams.cat_name || 'Kitten Reservation',
                        content_type: 'product',
                        currency: 'PLN',
                    });
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
   4. Verified Outcome Tracking (Breeder Confirmation)
───────────────────────────────────────────────────────────────── */

/**
 * Records a genuinely verified qualified lead.
 * To be invoked ONLY when an inquiry is verified by breeder process.
 *
 * @param {Object} [details]
 */
export function trackQualifiedLead(details) {
    trackFunnelStep('qualified_lead', details || {});
}

window.trackQualifiedLead = trackQualifiedLead;

/**
 * Records a genuinely confirmed kitten sale or formal reservation.
 * To be invoked ONLY when confirmed through breeder / admin procedure.
 *
 * @param {Object} [details]
 */
export function trackSaleCompleted(details) {
    trackFunnelStep('sale_completed', details || {});
}

window.trackSaleCompleted = trackSaleCompleted;

/* ─────────────────────────────────────────────────────────────────
   5. Session Helpers
───────────────────────────────────────────────────────────────── */

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
   6. Automated Event Initializers (Using Safe Global Delegation)
───────────────────────────────────────────────────────────────── */

let isDelegationBound = false;

function bindGlobalClickDelegation() {
    if (isDelegationBound) return;
    isDelegationBound = true;

    document.addEventListener('click', function (e) {
        // Direct Phone Link Click
        const phoneLink = e.target.closest('a[href^="tel:"]');
        if (phoneLink) {
            trackFunnelStep('contact_cta_click', {
                channel: 'phone',
                link_type: 'tel_tag',
            });
            return;
        }

        // Direct Email Link Click
        const emailLink = e.target.closest('a[href^="mailto:"]');
        if (emailLink) {
            if (typeof window.gtag === 'function') {
                try {
                    window.gtag('event', 'email_click', { link_type: 'mailto_tag' });
                } catch (err) {}
            }
            return;
        }

        // Direct WhatsApp Link Click (outside modal)
        const waLink = e.target.closest('a[href*="wa.me"]');
        if (waLink && !waLink.id.includes('res-modal')) {
            trackFunnelStep('contact_cta_click', {
                channel: 'whatsapp',
                link_type: 'direct_link',
            });
            return;
        }

        // Direct Messenger Link Click (outside modal)
        const fbLink = e.target.closest('a[href*="m.me"]');
        if (fbLink && !fbLink.id.includes('res-modal')) {
            trackFunnelStep('contact_cta_click', {
                channel: 'messenger',
                link_type: 'direct_link',
            });
        }
    }, { passive: true });
}

function initContactFormTracking() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    const SESSION_KEY = 'contact_start';

    function onContactInteraction() {
        if (isEventSentThisSession(SESSION_KEY)) return;
        markEventSent(SESSION_KEY);

        trackFunnelStep('contact_cta_click', {
            channel: 'traditional_form',
            location: 'contact_page',
        });

        form.removeEventListener('focusin', onContactInteraction);
        form.removeEventListener('input', onContactInteraction);
    }

    form.addEventListener('focusin', onContactInteraction, { passive: true });
    form.addEventListener('input', onContactInteraction, { passive: true });
}

function initContactSuccessCheck() {
    const successAlert = document.querySelector('.form-alert--success');
    if (!successAlert) return;

    const isContactPage = !!document.getElementById('contactForm') ||
                          window.location.pathname.includes('contact') ||
                          window.location.pathname.includes('kontakt');
    if (!isContactPage) return;

    const SESSION_KEY = 'contact_submit_done';
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    trackFunnelStep('callback_submitted', {
        channel: 'traditional_form',
    });
}

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

    if (typeof window.gtag === 'function') {
        try {
            window.gtag('event', 'animal_list_view', { breed_filter: breedFilter });
        } catch (err) {}
    }
}

/* ─────────────────────────────────────────────────────────────────
   7. Boot Analytics
───────────────────────────────────────────────────────────────── */

export function bootAnalytics() {
    captureTrafficSource();
    bindGlobalClickDelegation();
    initContactFormTracking();
    initContactSuccessCheck();
    initAnimalView();
    initAnimalListView();
}

// Initial Boot
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootAnalytics);
} else {
    bootAnalytics();
}

// Livewire Navigation Support
if (typeof document !== 'undefined') {
    document.addEventListener('livewire:navigated', () => {
        captureTrafficSource();
        initContactFormTracking();
        initContactSuccessCheck();
        initAnimalView();
        initAnimalListView();
    });
}
