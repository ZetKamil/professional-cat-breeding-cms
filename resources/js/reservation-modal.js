/**
 * reservation-modal.js
 * Cattery of Mazovian Switzerland (Hodowla Kotów z Mazowieckiej Szwajcarii)
 * Interactive logic for the Reservation & Live Video-Visit Modal.
 */

window.__currentReservationCat = null;

/**
 * Retrieves the formatted traffic attribution code (tag for the breeder).
 * Appended to WhatsApp messages so the breeder immediately sees the lead source.
 *
 * @returns {string}
 */
function getTrafficAttributionCode() {
    try {
        if (typeof window.getTrafficSourceLabel === 'function') {
            return window.getTrafficSourceLabel();
        }
        const fbclid = sessionStorage.getItem('katten_fbclid') || (new URLSearchParams(window.location.search)).get('fbclid');
        const source = sessionStorage.getItem('katten_utm_source') || (new URLSearchParams(window.location.search)).get('utm_source');
        const campaign = sessionStorage.getItem('katten_utm_campaign') || (new URLSearchParams(window.location.search)).get('utm_campaign');

        if (fbclid) return 'FB_Ads' + (campaign ? '_' + campaign : '');
        if (source === 'facebook') return 'FB_Post';
        if (source === 'google') return 'Google';
        if (source) return source;
        return 'Bezposredni';
    } catch (e) {
        return 'Bezposredni';
    }
}

/**
 * Opens the reservation modal and populates it with selected cat details.
 *
 * @param {string|null} catName
 * @param {string|null} catBreed
 * @param {string|null} statusLabel
 */
window.openReservationModal = function (catName, catBreed, statusLabel) {
    const backdrop = document.getElementById('reservation-modal-backdrop');
    if (!backdrop) return;

    window.__currentReservationCat = {
        name: catName || null,
        breed: catBreed || null,
        status: statusLabel || null,
    };

    // Text & DOM elements
    const titleEl = document.getElementById('res-modal-title');
    const badgeTextEl = document.getElementById('res-modal-badge-text');
    const whatsappBtn = document.getElementById('res-modal-whatsapp');
    const messengerBtn = document.getElementById('res-modal-messenger');
    const phoneInput = document.getElementById('res-modal-phone-input');
    const feedback = document.getElementById('res-modal-callback-feedback');

    if (feedback) {
        feedback.style.display = 'none';
        feedback.className = 'res-modal__feedback';
        feedback.textContent = '';
    }

    if (catName) {
        titleEl.textContent = `Rozmowa i Wideo-wizyta: ${catName}`;
        if (badgeTextEl) {
            badgeTextEl.textContent = statusLabel ? `${statusLabel} • Kontakt z hodowcą` : `Kociak: ${catName}`;
        }
    } else {
        titleEl.textContent = 'Rozmowa i Wideo-wizyta na żywo';
        if (badgeTextEl) {
            badgeTextEl.textContent = 'Kontakt z hodowcą';
        }
    }

    // Dynamic WhatsApp deep link
    if (whatsappBtn) {
        const attributionCode = getTrafficAttributionCode();
        let messageText = '';

        if (catName) {
            messageText = `Dzień dobry! Piszę ze strony kotyzmazowieckiejszwajcarii.pl. Interesuje mnie kotek ${catName}` +
                (catBreed ? ` (${catBreed})` : '') +
                `. Chciał(a)bym porozmawiać z hodowcą i zobaczyć malucha na żywo przez kamerę w telefonie. [Kod: ${attributionCode}]`;
        } else {
            messageText = `Dzień dobry! Piszę ze strony kotyzmazowieckiejszwajcarii.pl. Chciał(a)bym zapytać o dostępne kocięta i umówić wideo-wizytę na żywo. [Kod: ${attributionCode}]`;
        }

        whatsappBtn.href = `https://wa.me/48514153204?text=${encodeURIComponent(messageText)}`;
    }

    // Dynamic Facebook Messenger link
    if (messengerBtn) {
        messengerBtn.href = 'https://m.me/61580668026948';
    }

    // Analytics event - modal opened
    if (typeof window.trackFunnelStep === 'function') {
        window.trackFunnelStep('open_reservation_modal', {
            cat_name: catName || 'Ogólne',
            cat_breed: catBreed || 'Nieokreślona',
            status_label: statusLabel || '',
        });
    }

    // Smooth reveal animation
    backdrop.style.display = 'flex';
    requestAnimationFrame(() => {
        backdrop.classList.add('is-open');
        document.body.classList.add('is-modal-open');
        document.body.style.overflow = 'hidden';
        if (phoneInput && window.innerWidth > 768) {
            phoneInput.focus();
        }
    });
};

/**
 * Closes the reservation modal.
 */
window.closeReservationModal = function () {
    const backdrop = document.getElementById('reservation-modal-backdrop');
    if (!backdrop) return;

    backdrop.classList.remove('is-open');
    document.body.classList.remove('is-modal-open');
    setTimeout(() => {
        backdrop.style.display = 'none';
        document.body.style.overflow = '';
    }, 250);
};

/**
 * Handles the 1-field quick callback AJAX submission.
 *
 * @param {Event} event
 */
window.handleQuickCallbackSubmit = function (event) {
    event.preventDefault();

    const form = event.target;
    const phoneInput = form.querySelector('input[name="phone"]');
    const countryCodeSelect = form.querySelector('select[name="country_code"]');
    const submitBtn = form.querySelector('button[type="submit"]');
    const feedback = document.getElementById('res-modal-callback-feedback');
    const hpInput = form.querySelector('input[name="_hp_website"]');

    if (!phoneInput || !phoneInput.value.trim()) return;

    let rawPhone = phoneInput.value.trim();
    const countryCode = countryCodeSelect ? countryCodeSelect.value.trim() : '+48';
    let fullPhone = rawPhone;

    // Smart formatting: if user already typed leading + or 00, respect it
    if (rawPhone.startsWith('+')) {
        fullPhone = rawPhone;
    } else if (rawPhone.startsWith('00')) {
        fullPhone = '+' + rawPhone.slice(2).trim();
    } else if (countryCode) {
        fullPhone = countryCode + ' ' + rawPhone;
    }

    const cat = window.__currentReservationCat || {};
    const attribution = getTrafficAttributionCode();
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || form.querySelector('input[name="_token"]')?.value;

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Wysyłanie...</span>';
    }

    fetch('/api/quick-callback', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token || '',
        },
        body: JSON.stringify({
            phone: fullPhone,
            cat_name: cat.name || null,
            cat_breed: cat.breed || null,
            page_url: window.location.href,
            traffic_source: attribution,
            _hp_website: hpInput ? hpInput.value : '',
        }),
    })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                let errMsg = data.message || 'Wystąpił problem podczas wysyłania zgłoszenia.';
                if (data.errors && data.errors.phone) {
                    errMsg = Array.isArray(data.errors.phone) ? data.errors.phone[0] : data.errors.phone;
                }
                throw new Error(errMsg);
            }
            return data;
        })
        .then(data => {
            if (feedback) {
                feedback.style.display = 'block';
                feedback.className = 'res-modal__feedback res-modal__feedback--success';
                feedback.textContent = data.message || 'Dziękujemy! Skontaktujemy się telefonicznie tak szybko, jak to możliwe.';
            }
            phoneInput.value = '';

            if (typeof window.trackFunnelStep === 'function') {
                window.trackFunnelStep('callback_submitted', {
                    channel: 'quick_callback',
                    cat_name: cat.name || 'Ogólne',
                    cat_breed: cat.breed || 'Nieokreślona',
                });
            }

            setTimeout(() => {
                window.closeReservationModal();
            }, 3500);
        })
        .catch(error => {
            if (feedback) {
                feedback.style.display = 'block';
                feedback.className = 'res-modal__feedback res-modal__feedback--error';
                feedback.textContent = error.message || 'Wystąpił błąd. Zadzwoń pod numer +48 514 153 204.';
            }
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Poproś o kontakt</span>';
            }
        });
};

// Global event listeners (backdrop click, Escape key)
document.addEventListener('DOMContentLoaded', () => {
    const backdrop = document.getElementById('reservation-modal-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                window.closeReservationModal();
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const openBackdrop = document.getElementById('reservation-modal-backdrop');
            if (openBackdrop && openBackdrop.style.display !== 'none') {
                window.closeReservationModal();
            }
        }
    });
});
