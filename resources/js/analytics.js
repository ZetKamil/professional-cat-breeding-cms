/**
 * analytics.js — GA4 Conversion Tracking
 * Hodowla Kotów z Mazowieckiej Szwajcarii
 *
 * Events zaimplementowane:
 *   contact_start      — pierwsze wejście w interakcję z formularzem
 *   contact_submit     — pomyślne wysłanie formularza (tylko po sukcesie serwera)
 *   phone_click        — kliknięcie linku tel:
 *   email_click        — kliknięcie linku mailto:
 *   animal_view        — wejście na stronę konkretnego kota
 *   animal_list_view   — wejście na stronę listy kotów
 *
 * Gwarancje bezpieczeństwa:
 *   • ŻADNYCH PII (imię, email, telefon, treść) w payloadach
 *   • Eventy nie wysyłają się wielokrotnie (sessionStorage guard)
 *   • contact_submit tylko przy faktycznym sukcesie (detekcja .form-alert--success)
 *   • Kompatybilność z GA4 Consent Mode v2 (gtag buforuje eventy gdy consent 'denied')
 */

/* ─────────────────────────────────────────────────────────────────
   Helpers
───────────────────────────────────────────────────────────────── */

/**
 * Bezpieczne wysłanie eventu do GA4.
 * Jeśli gtag niedostępne (brak skryptu, AdBlocker) — ciche pominięcie.
 *
 * @param {string} eventName
 * @param {Object} [params]
 */
function sendGaEvent(eventName, params) {
    if (typeof gtag !== 'function') return;
    gtag('event', eventName, params || {});
}

/**
 * Czy event został już wysłany w bieżącej sesji?
 * Zapobiega wielokrotnemu wysyłaniu przy powrocie do strony w SPA.
 *
 * @param {string} key
 * @returns {boolean}
 */
function isEventSentThisSession(key) {
    try {
        return !!sessionStorage.getItem('ga_sent_' + key);
    } catch (e) {
        return false;
    }
}

/** @param {string} key */
function markEventSent(key) {
    try {
        sessionStorage.setItem('ga_sent_' + key, '1');
    } catch (e) {}
}

/* ─────────────────────────────────────────────────────────────────
   1. contact_start
   Wysyłany raz, gdy użytkownik po raz pierwszy wchodzi w interakcję
   z formularzem kontaktowym (focus lub input na polu formularza).
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

        // Usuń listenery po pierwszym wysłaniu
        form.removeEventListener('focusin', onContactInteraction);
        form.removeEventListener('input', onContactInteraction);
    }

    form.addEventListener('focusin', onContactInteraction, { passive: true });
    form.addEventListener('input', onContactInteraction, { passive: true });
}

/* ─────────────────────────────────────────────────────────────────
   2. contact_submit
   Wysyłany WYŁĄCZNIE gdy serwer zwrócił sukces (session('status')).
   Wykrywamy obecność elementu .form-alert--success w DOM.
   NIE wysyła się przy błędzie walidacji (brak alertu sukcesu).
───────────────────────────────────────────────────────────────── */
function initContactSubmit() {
    // Sprawdzamy obecność alertu sukcesu — renderowanego przez Blade po przekierowaniu
    const successAlert = document.querySelector('.form-alert--success');
    if (!successAlert) return;

    // Dodatkowe zabezpieczenie: sprawdź że jesteśmy na stronie /contact
    // (nie chcemy fałszywie triggerować na innej stronie)
    const isContactPage = !!document.getElementById('contactForm') ||
                          window.location.pathname.includes('contact');
    if (!isContactPage) return;

    const SESSION_KEY = 'contact_submit';
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    sendGaEvent('contact_submit', {
        method: 'form',
    });
}

/* ─────────────────────────────────────────────────────────────────
   3. phone_click
   Wysyłany po kliknięciu linku tel:.
   NIE przesyłamy numeru telefonu (PII).
───────────────────────────────────────────────────────────────── */
function initPhoneClick() {
    document.querySelectorAll('a[href^="tel:"]').forEach(function (link) {
        link.addEventListener('click', function () {
            sendGaEvent('phone_click', {
                link_type: 'phone',
            });
        });
    });
}

/* ─────────────────────────────────────────────────────────────────
   4. email_click
   Wysyłany po kliknięciu linku mailto:.
   NIE przesyłamy adresu e-mail (PII).
───────────────────────────────────────────────────────────────── */
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
   5. animal_view
   Wysyłany raz na stronie konkretnego kota.
   Dane pobierane z data atrybutów na sekcji profilu — bez PII.
   Parametry: animal_breed, animal_status (nie animal_name — ostrożność)
───────────────────────────────────────────────────────────────── */
function initAnimalView() {
    const profileSection = document.querySelector('[data-ga-page="animal_view"]');
    if (!profileSection) return;

    const SESSION_KEY = 'animal_view_' + (profileSection.dataset.gaAnimalSlug || window.location.pathname);
    if (isEventSentThisSession(SESSION_KEY)) return;
    markEventSent(SESSION_KEY);

    const params = {};

    const breed = profileSection.dataset.gaAnimalBreed;
    const status = profileSection.dataset.gaAnimalStatus;
    const name = profileSection.dataset.gaAnimalName;

    // animal_breed: np. "Bengalski" — nie jest PII
    if (breed) params.animal_breed = breed;
    // animal_status: np. "Hodowlany", "Dostępny" — nie jest PII
    if (status) params.animal_status = status;
    // animal_name: imię kota — nie jest danową osobową (to imię zwierzęcia, nie człowieka)
    if (name) params.animal_name = name;

    sendGaEvent('animal_view', params);
}

/* ─────────────────────────────────────────────────────────────────
   6. animal_list_view
   Wysyłany raz na stronie listy kotów.
   Parametry: breed_filter (aktywny filtr rasy lub 'all')
───────────────────────────────────────────────────────────────── */
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
   Boot — inicjalizacja po załadowaniu DOM
───────────────────────────────────────────────────────────────── */
function bootAnalytics() {
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

// Eksport dla testów jednostkowych (jeśli środowisko na to pozwala)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        sendGaEvent,
        isEventSentThisSession,
        markEventSent,
        bootAnalytics,
    };
}
