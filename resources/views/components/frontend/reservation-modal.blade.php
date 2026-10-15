{{--
    Reservation & Live Video-Visit Modal — Mazovian Switzerland
    ─────────────────────────────────────────────────────────────────
    Zero-friction, mobile-first lead capture modal.
    • 4 instant contact channels: WhatsApp Video, Messenger, Phone, 1-field callback.
    • Dynamic injection of cat data (name, breed, status) and traffic attribution code.
    • Strict absence of deposit / price mentions (handled verbally by breeder).
--}}

<div id="reservation-modal-backdrop" class="res-modal-backdrop" aria-hidden="true" style="display: none;">
    <div id="reservation-modal" class="res-modal" role="dialog" aria-modal="true" aria-labelledby="res-modal-title">
        
        {{-- Close button --}}
        <button type="button" class="res-modal__close" onclick="window.closeReservationModal()" aria-label="Zamknij okno rezerwacji">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        {{-- Modal header --}}
        <div class="res-modal__header">
            <div class="res-modal__badge" id="res-modal-badge">
                <span class="res-modal__badge-dot"></span>
                <span id="res-modal-badge-text">Wideo-wizyta na żywo</span>
            </div>
            <h2 id="res-modal-title" class="res-modal__title">Rezerwacja i Wideo-wizyta</h2>
            <p id="res-modal-subtitle" class="res-modal__subtitle">
                Zobacz kociaka na żywo przez kamerę w telefonie (WhatsApp lub Messenger) albo porozmawiajmy głosowo. 
                Pokażemy Ci malucha w domowych warunkach, odpowiemy na pytania i wspólnie ustalimy szczegóły.
            </p>
        </div>

        {{-- Trust & Credentials strip --}}
        <div class="res-modal__trust-strip">
            <div class="res-modal__trust-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold, #c89d5c)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>SHiOZ ZOOLANDIA (58/CW/2025)</span>
            </div>
            <div class="res-modal__trust-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold, #c89d5c)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <span>Badania HCM & PKD</span>
            </div>
            <div class="res-modal__trust-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold, #c89d5c)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <span>100% Domowa Socjalizacja</span>
            </div>
        </div>

        {{-- Contact channels --}}
        <div class="res-modal__actions">
            
            {{-- 1. WhatsApp Video CTA (Primary) --}}
            <a id="res-modal-whatsapp" href="https://wa.me/48514153204" target="_blank" rel="noopener noreferrer" class="res-action-btn res-action-btn--whatsapp" onclick="if(window.trackFunnelStep){ window.trackFunnelStep('click_whatsapp_lead', { cat_name: window.__currentReservationCat?.name || 'Ogólne' }); }">
                <span class="res-action-btn__icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                </span>
                <span class="res-action-btn__text">
                    <strong>Wideo-wizyta przez WhatsApp</strong>
                    <small>Połącz się z hodowlą lub napisz wiadomość</small>
                </span>
                <span class="res-action-btn__arrow">→</span>
            </a>

            {{-- 2. Direct Phone Call CTA --}}
            <a id="res-modal-phone" href="tel:+48514153204" class="res-action-btn res-action-btn--phone" onclick="if(window.trackFunnelStep){ window.trackFunnelStep('click_phone_lead', { cat_name: window.__currentReservationCat?.name || 'Ogólne' }); }">
                <span class="res-action-btn__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </span>
                <span class="res-action-btn__text">
                    <strong>Zadzwoń teraz: +48 514 153 204</strong>
                    <small>Rozmowa bezpośrednio z hodowcą (9:00 – 21:00)</small>
                </span>
                <span class="res-action-btn__arrow">→</span>
            </a>

            {{-- 3. Facebook Messenger CTA --}}
            <a id="res-modal-messenger" href="https://m.me/61580668026948" target="_blank" rel="noopener noreferrer" class="res-action-btn res-action-btn--messenger" onclick="if(window.trackFunnelStep){ window.trackFunnelStep('click_messenger_lead', { cat_name: window.__currentReservationCat?.name || 'Ogólne' }); }">
                <span class="res-action-btn__icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2C6.48 2 2 6.03 2 11c0 2.87 1.5 5.43 3.84 7.07L5 22l4.13-1.81C10.05 20.35 11 20.48 12 20.48c5.52 0 10-4.03 10-9s-4.48-9.48-10-9.48z"></path>
                        <path d="M7.5 13.5l3-3.2 2 2 3-3.2-3 3.2-2-2-3 3.2z" fill="currentColor"></path>
                    </svg>
                </span>
                <span class="res-action-btn__text">
                    <strong>Napisz na Facebook Messenger</strong>
                    <small>Odpowiemy na profilu hodowli</small>
                </span>
                <span class="res-action-btn__arrow">→</span>
            </a>
        </div>

        {{-- 4. 1-Field Callback Form --}}
        <div class="res-modal__callback">
            <div class="res-modal__callback-title">
                <span>Nie możesz teraz rozmawiać?</span>
                <small>Zostaw sam numer telefonu — oddzwonimy lub napiszemy na WhatsApp.</small>
            </div>

            <form id="res-modal-callback-form" onsubmit="window.handleQuickCallbackSubmit(event)">
                @csrf
                {{-- Anti-bot honeypot field --}}
                <div style="display:none;" aria-hidden="true">
                    <input type="text" name="_hp_website" tabindex="-1" autocomplete="off">
                </div>

                <div class="res-modal__form-row">
                    <div class="res-modal__input-wrapper">
                        <span class="res-modal__input-prefix">+48</span>
                        <input 
                            type="tel" 
                            id="res-modal-phone-input" 
                            name="phone" 
                            required 
                            placeholder="np. 514 153 204" 
                            pattern="[0-9\s\-+]{9,20}"
                            autocomplete="tel"
                            aria-label="Twój numer telefonu"
                        >
                    </div>
                    <button type="submit" id="res-modal-submit-btn" class="res-modal__submit-btn">
                        <span>Poproś o kontakt</span>
                    </button>
                </div>

                <div id="res-modal-callback-feedback" class="res-modal__feedback" style="display:none;" role="status"></div>
            </form>
        </div>

        {{-- Footer trust note --}}
        <div class="res-modal__footer-note">
            <span>🔒 Bez spamu. Twój numer służy wyłącznie do jednorazowego kontaktu w sprawie kociaka.</span>
        </div>

    </div>
</div>
