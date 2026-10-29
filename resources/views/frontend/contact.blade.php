<x-frontend.shell title="Kontakt i Rezerwacja Kociąt | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Skontaktuj się z naszą domową hodowlą w Sikorzu (woj. mazowieckie). Zadzwoń: +48 514 153 204 lub napisz przez formularz kontaktowy. Odpowiadamy w ciągu 24h."
    og-image="{{ asset('og-default.jpg') }}">
    {{-- ============================================================
    1. TOP HERO + CONTACT CONCIERGE (Fast Phone / WhatsApp / Form)
    ============================================================ --}}
    <section class="contact-hero-section tile-parchment" aria-label="Kontakt i formularz zapytania">
        <div class="section-inner contact-hero-section__inner">
            <div class="contact-form-layout">
                {{-- LEFT COLUMN: Editorial Welcome & Direct Contact Info --}}
                <div class="contact-form-info">
                    <span class="text-eyebrow text-primary" style="display: inline-block; margin-bottom: 12px;">Kontakt & Rezerwacja</span>
                    <h1 class="text-hero-display contact-hero-title">
                        Porozmawiajmy o Twoim wymarzonym kocie.
                    </h1>
                    <p class="text-lead-airy contact-hero-lead" style="margin-top: 16px; margin-bottom: 28px; color: var(--color-ink-muted-80);">
                        Masz pytania o dostępne kocięta, planowane mioty lub chcesz umówić się na odwiedziny w naszej domowej hodowli? Wybierz najwygodniejszy dla siebie sposób kontaktu — z radością odpowiemy.
                    </p>

                    {{-- Direct Contact Fast Access Cards --}}
                    <ul class="contact-fast-links" aria-label="Szybki kontakt" style="list-style:none;margin:0;padding:0;">
                        <li>
                            <a href="tel:+48514153204" class="contact-fast-link">
                                <div class="contact-fast-link__icon">
                                    <i data-lucide="phone-call" aria-hidden="true"></i>
                                </div>
                                <div class="contact-fast-link__body">
                                    <span class="contact-fast-link__label">Zadzwoń bezpośrednio</span>
                                    <span class="contact-fast-link__val">+48 514 153 204</span>
                                    <span class="contact-fast-link__hint">Dostępni codziennie • Bezpośrednia rozmowa z hodowcą</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="https://wa.me/48514153204?text={{ rawurlencode('Dzień dobry! Piszę ze strony kotyzmazowieckiejszwajcarii.pl. Chciał(a)bym porozmawiać o kociętach i umówić wideo-wizytę na żywo.') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="contact-fast-link"
                               onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: 'Strona kontaktu', method: 'WhatsApp' }); } if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'whatsapp', origin: 'contact_page' }); } var src = (typeof window.getTrafficSourceLabel === 'function') ? ' [Źródło: ' + window.getTrafficSourceLabel() + ']' : ''; this.href = 'https://wa.me/48514153204?text=' + encodeURIComponent('Dzień dobry! Piszę ze strony kotyzmazowieckiejszwajcarii.pl. Chciał(a)bym porozmawiać o kociętach i umówić wideo-wizytę na żywo.' + src);">
                                <div class="contact-fast-link__icon" style="background: rgba(37, 211, 102, 0.14); color: #25d366;">
                                    <i data-lucide="message-circle" aria-hidden="true"></i>
                                </div>
                                <div class="contact-fast-link__body">
                                    <span class="contact-fast-link__label">Napisz na WhatsApp</span>
                                    <span class="contact-fast-link__val">+48 514 153 204</span>
                                    <span class="contact-fast-link__hint">Wideo-wizyta na żywo • Czat i zdjęcia maluchów</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="https://m.me/61580668026948"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="contact-fast-link"
                               onclick="if(typeof fbq === 'function'){ fbq('track', 'Contact', { content_name: 'Strona kontaktu', method: 'Messenger' }); } if(window.trackFunnelStep){ window.trackFunnelStep('contact_cta_click', { channel: 'messenger', origin: 'contact_page' }); }">
                                <div class="contact-fast-link__icon" style="background: rgba(0, 132, 255, 0.12); color: #0084ff;">
                                    <i data-lucide="messages-square" aria-hidden="true"></i>
                                </div>
                                <div class="contact-fast-link__body">
                                    <span class="contact-fast-link__label">Facebook Messenger</span>
                                    <span class="contact-fast-link__val">Koty z Mazowieckiej Szwajcarii</span>
                                    <span class="contact-fast-link__hint">Wiadomość prywatna przez profil hodowli</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="mailto:biuro@kotyzmazowieckiejszwajcarii.pl" class="contact-fast-link">
                                <div class="contact-fast-link__icon">
                                    <i data-lucide="mail" aria-hidden="true"></i>
                                </div>
                                <div class="contact-fast-link__body">
                                    <span class="contact-fast-link__label">Napisz e-mail</span>
                                    <span class="contact-fast-link__val">biuro@kotyzmazowieckiejszwajcarii.pl</span>
                                    <span class="contact-fast-link__hint">Odpowiedź zwykle w ciągu 24h</span>
                                </div>
                            </a>
                        </li>

                        <li>
                            <div class="contact-fast-link">
                                <div class="contact-fast-link__icon">
                                    <i data-lucide="map-pin" aria-hidden="true"></i>
                                </div>
                                <div class="contact-fast-link__body">
                                    <span class="contact-fast-link__label">Lokalizacja domowej hodowli</span>
                                    <span class="contact-fast-link__val">Sikórz k. Płocka (woj. mazowieckie)</span>
                                    <span class="contact-fast-link__hint">Odwiedziny w domowej hodowli po wcześniejszym umówieniu terminu</span>
                                </div>
                            </div>
                        </li>
                    </ul>

                    {{-- Trust / Association Badge --}}
                    <div class="contact-trust-pill" style="margin-top: 28px; display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; background: rgba(209, 171, 88, 0.12); border: 1px solid rgba(209, 171, 88, 0.35); border-radius: 9999px; font-size: 0.85rem; color: #78350f;">
                        <i data-lucide="award" style="width: 18px; height: 18px; color: var(--color-primary);" aria-hidden="true"></i>
                        <span>Członek <strong>SHiOZ ZOOLANDIA</strong> (Certyfikat 58/CW/2025 · Rej. 58/P/2025)</span>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Modern Dual Tabbed Contact Card --}}
                <div class="contact-form-card" id="formularz">
                    {{-- Tab Switcher --}}
                    <div class="contact-tabs" role="tablist" aria-label="Wybór metody kontaktu">
                        <button type="button" class="contact-tab is-active" id="tabQuickBtn" role="tab" aria-selected="true" onclick="switchContactTab('quick')">
                            ⚡ Szybki telefon (15 min)
                        </button>
                        <button type="button" class="contact-tab" id="tabEmailBtn" role="tab" aria-selected="false" onclick="switchContactTab('email')">
                            ✉️ Formularz wiadomości
                        </button>
                    </div>

                    {{-- TAB 1: QUICK CALLBACK (Instant Phone Number Lead) --}}
                    <div id="contactQuickPanel" class="contact-quick-panel">
                        <div style="margin-bottom: 16px;">
                            <h2 style="font-size: 1.35rem; font-weight: 600; color: var(--color-ink); margin-bottom: 6px;">Zostaw numer — oddzwonimy</h2>
                            <p style="font-size: 0.9rem; color: var(--color-ink-muted-80); margin: 0; line-height: 1.5;">
                                Wpisz numer telefonu, a hodowca skontaktuje się z Tobą (zwykle w 15–30 minut), aby odpowiedzieć na pytania i umówić wideo-wizytę na żywo.
                            </p>
                        </div>

                        {{-- Quick Callback Success Box --}}
                        <div id="contactQuickSuccess" style="display:none; padding: 16px; border-radius: 12px; background: rgba(52, 199, 89, 0.12); border: 1px solid rgba(52, 199, 89, 0.35); color: #15803d; margin-bottom: 16px;">
                            <div style="display: flex; align-items: flex-start; gap: 10px;">
                                <i data-lucide="check-circle" style="width: 22px; height: 22px; color: #16a34a; flex-shrink: 0;" aria-hidden="true"></i>
                                <div>
                                    <strong style="display: block; font-size: 0.98rem; margin-bottom: 4px;">Dziękujemy! Otrzymaliśmy Twoje zgłoszenie.</strong>
                                    <p style="margin: 0; font-size: 0.9rem; line-height: 1.45;">Hodowca skontaktuje się z Tobą telefonicznie najszybciej jak to możliwe.</p>
                                </div>
                            </div>
                        </div>

                        <form id="contactQuickForm" onsubmit="submitContactQuickCallback(event)" style="display: flex; flex-direction: column; gap: 16px;">
                            {{-- Honeypot --}}
                            <input type="text" id="contactQuickHp" style="display:none !important;" tabindex="-1" autocomplete="off" aria-hidden="true">

                            <div class="form-group">
                                <label for="contactQuickPhone" class="form-label">Twój numer telefonu</label>
                                <input type="tel" id="contactQuickPhone" required placeholder="+48 500 123 456" class="form-input" autocomplete="tel" style="font-size: 1.05rem; font-weight: 500;">
                                <span id="contactQuickPhoneError" style="display:none; color: var(--color-error); font-size: 0.85rem; margin-top: 4px;">Wprowadź poprawny numer telefonu.</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Którą rasą się interesujesz?</label>
                                <div class="contact-quick-pill-selector" id="contactQuickBreedPills">
                                    <button type="button" class="quick-breed-pill is-selected" data-breed="Wszystkie rasy" onclick="selectQuickBreed(this)">Wszystkie rasy</button>
                                    <button type="button" class="quick-breed-pill" data-breed="Kot Brytyjski (BSH / BLH)" onclick="selectQuickBreed(this)">Kot Brytyjski (BSH / BLH)</button>
                                    <button type="button" class="quick-breed-pill" data-breed="Kot Bengalski" onclick="selectQuickBreed(this)">Kot Bengalski</button>
                                    <button type="button" class="quick-breed-pill" data-breed="Kot Syjamski" onclick="selectQuickBreed(this)">Kot Syjamski</button>
                                </div>
                                <input type="hidden" id="contactQuickSelectedBreed" value="Wszystkie rasy">
                            </div>

                            <button type="submit" id="contactQuickSubmitBtn" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 14px 20px; font-size: 1rem; font-weight: 600; margin-top: 8px;">
                                📞 Poproś o szybki kontakt (oddzwonimy)
                            </button>

                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; color: var(--color-ink-muted-80); margin-top: 4px; flex-wrap: wrap; gap: 8px;">
                                <span>🔒 Bez spamu • Bezpieczny kontakt</span>
                                <a href="https://wa.me/48514153204?text={{ rawurlencode('Dzień dobry! Piszę ze strony kotyzmazowieckiejszwajcarii.pl. Chciał(a)bym porozmawiać o kociętach.') }}" target="_blank" rel="noopener noreferrer" style="color: #25d366; font-weight: 600; text-decoration: underline;">
                                    Wolisz czat? Napisz na WhatsApp →
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- TAB 2: DETAILED EMAIL MESSAGE FORM --}}
                    <div id="contactEmailPanel" style="display:none;">
                        @if(session('status'))
                            <div class="form-alert form-alert--success" role="alert" style="margin-bottom: 24px; padding: 14px 18px; border-radius: 12px; background: rgba(52, 199, 89, 0.12); border: 1px solid rgba(52, 199, 89, 0.35); color: #15803d; display: flex; align-items: flex-start; gap: 12px; box-shadow: 0 4px 16px rgba(52, 199, 89, 0.12);">
                                <i data-lucide="check-circle" style="width: 22px; height: 22px; color: #16a34a; flex-shrink: 0; margin-top: 1px;" aria-hidden="true"></i>
                                <p style="margin: 0; font-size: 0.95rem; font-weight: 500; line-height: 1.45;">{{ session('status') }}</p>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="form-alert form-alert--error" role="alert" style="margin-bottom: 24px; padding: 14px 18px; border-radius: 12px; background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626; color: #991b1b; display: flex; align-items: flex-start; gap: 12px;">
                                <i data-lucide="alert-circle" style="width: 22px; height: 22px; color: #dc2626; flex-shrink: 0; margin-top: 1px;" aria-hidden="true"></i>
                                <p style="margin: 0; font-size: 0.95rem; font-weight: 500; line-height: 1.45;">{{ session('error') }}</p>
                            </div>
                        @endif

                        <div class="contact-form-card__header" style="margin-bottom: 20px;">
                            <h2 style="font-size: 1.35rem; font-weight: 600; color: var(--color-ink); margin-bottom: 6px;">Formularz kontaktowy</h2>
                            <p style="font-size: 0.9rem; color: var(--color-ink-muted-80);">Wypełnij poniższe pola, a odezwiemy się drogą e-mailową lub telefoniczną.</p>
                        </div>

                        <form action="{{ route('frontend.contact.store') }}" method="POST" class="contact-form" id="contactForm" novalidate onsubmit="const btn = this.querySelector('button[type=submit]'); if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; btn.style.pointerEvents = 'none'; btn.innerText = 'Wysyłanie wiadomości...'; }">
                            @csrf

                            {{-- Invisible anti-spam honeypot --}}
                            <input type="text" name="_hp_website" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off" aria-hidden="true">

                            @if(request('subject') || old('subject'))
                                <input type="hidden" name="subject" value="{{ old('subject', request('subject')) }}">
                                <div class="form-subject-badge" style="margin-bottom: 18px; padding: 10px 14px; background: rgba(184, 134, 11, 0.08); border-left: 3px solid var(--color-primary); border-radius: 6px; font-size: 0.9rem; color: #78350f;">
                                    <strong style="color: #92400e;">Dotyczy:</strong> {{ old('subject', request('subject')) }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="name" class="form-label">Imię i nazwisko</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    placeholder="np. Anna Kowalska"
                                    class="form-input @error('name') form-input--error @enderror" autocomplete="name" required>
                                @error('name')
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Adres e-mail</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="np. anna.kowalska@example.com"
                                    class="form-input @error('email') form-input--error @enderror" autocomplete="email"
                                    required>
                                @error('email')
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    Numer telefonu
                                    <span class="form-label__optional">(opcjonalnie — ułatwia szybki kontakt)</span>
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-input"
                                    placeholder="np. +48 500 123 456"
                                    autocomplete="tel">
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Wiadomość</label>
                                <textarea name="message" id="message" rows="4"
                                    placeholder="Napisz, jaka rasa lub kot Cię interesuje, czy masz pytania o warunki rezerwacji i kiedy planujesz powiększenie rodziny..."
                                    class="form-input form-textarea @error('message') form-input--error @enderror"
                                    required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="form-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-actions">
                                <x-frontend.button type="submit" icon="send">
                                    Wyślij wiadomość
                                </x-frontend.button>
                            </div>

                            <p class="form-rodo-info text-caption text-ink-muted-60" style="margin-top: 14px; font-size: 0.82rem; line-height: 1.45;">
                                Wysyłając formularz, przekazujesz nam swoje dane osobowe, które przetwarzamy w celu obsługi Twojego zapytania i udzielenia odpowiedzi. Podanie danych jest dobrowolne, przy czym dane oznaczone jako wymagane są niezbędne do obsługi zapytania. Szczegółowe informacje znajdziesz w <a href="{{ route('privacy') }}" class="text-primary" style="text-decoration: underline;">Polityce Prywatności</a>.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
    2. INTERACTIVE LOCATION & DIRECTIONS (Google Maps & Travel Times)
    ============================================================ --}}
    <x-frontend.section>
        <x-frontend.section-header eyebrow="Dojazd i Wizyty" headline="Gdzie nas znaleźć?"
            description="Nasza hodowla mieści się w cichej, zielonej miejscowości Sikórz k. Płocka (woj. mazowieckie). Dogodny i szybki dojazd z Warszawy, Torunia, Włocławka i Łodzi." />

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 24px; margin-bottom: 24px;">
            <div style="background: var(--color-canvas-parchment); border-radius: 12px; padding: 14px 18px; border: 1px solid var(--color-hairline);">
                <strong style="display: block; font-size: 0.92rem; color: var(--color-ink);">Z Warszawy</strong>
                <span style="font-size: 0.85rem; color: var(--color-ink-muted-80);">ok. 1h 20 min (trasa S7 / DK62)</span>
            </div>
            <div style="background: var(--color-canvas-parchment); border-radius: 12px; padding: 14px 18px; border: 1px solid var(--color-hairline);">
                <strong style="display: block; font-size: 0.92rem; color: var(--color-ink);">Z Płocka</strong>
                <span style="font-size: 0.85rem; color: var(--color-ink-muted-80);">ok. 15 minut (dogodny dojazd lokalny)</span>
            </div>
            <div style="background: var(--color-canvas-parchment); border-radius: 12px; padding: 14px 18px; border: 1px solid var(--color-hairline);">
                <strong style="display: block; font-size: 0.92rem; color: var(--color-ink);">Z Torunia i Włocławka</strong>
                <span style="font-size: 0.85rem; color: var(--color-ink-muted-80);">ok. 45–60 min (DK62 / DK10)</span>
            </div>
            <div style="background: var(--color-canvas-parchment); border-radius: 12px; padding: 14px 18px; border: 1px solid var(--color-hairline);">
                <strong style="display: block; font-size: 0.92rem; color: var(--color-ink);">Z Łodzi</strong>
                <span style="font-size: 0.85rem; color: var(--color-ink-muted-80);">ok. 1h 30 min (autostrada A1 / DK60)</span>
            </div>
        </div>

        <div class="contact-map-embed reveal-up">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19561.42857416954!2d19.571424!3d52.618641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471c4c1a5d625cdb%3A0x8e82110c73291fb2!2sSik%C3%B3rz%2C%2009-413!5e0!3m2!1spl!2spl!4v1710000000000!5m2!1spl!2spl"
                width="100%" height="420" style="border:0; border-radius: 16px;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokalizacja Hodowla Kotów z Mazowieckiej Szwajcarii — Sikórz"></iframe>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    3. FAQ — Common questions
    ============================================================ --}}
    <x-frontend.section id="faq" tile="parchment">
        <x-frontend.section-header eyebrow="FAQ" headline="Najczęstsze pytania"
            description="Wszystko, co warto wiedzieć przed kontaktem i rezerwacją kocięcia w naszej hodowli." />

        <div class="faq-list" style="margin-top: 32px;">
            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Jak wygląda proces zakupu kocięcia?
                </summary>
                <p class="faq-item__answer text-body">
                    Proces rozpoczyna się od kontaktu — odpowiadamy na pytania, przedstawiamy
                    dostępne kocięta i umawiamy wizytę w hodowli. Po podjęciu decyzji
                    podpisujemy umowę i ustalamy dogodny termin odbioru.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    W jakim wieku kocięta opuszczają hodowlę?
                </summary>
                <p class="faq-item__answer text-body">
                    Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka,
                    po przejściu 2 szczepień, 2 odrobaczeń, zachipowaniu i pełnej domowej socjalizacji.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Jakie dokumenty otrzymuję?
                </summary>
                <p class="faq-item__answer text-body">
                    Każde kocię otrzymuje komplet dokumentów: książeczkę zdrowia z udokumentowanymi 2 szczepieniami i 2 odrobaczeniami,
                    wszczepiony i zarejestrowany microchip, certyfikat rodowodowy wydany przez SHiOZ ZOOLANDIA,
                    kopie wyników badań genetycznych i kardiologicznych obojga rodziców (dedykowanych dla danej rasy) oraz formalną umowę.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Czy mogę odwiedzić hodowlę przed podjęciem decyzji?
                </summary>
                <p class="faq-item__answer text-body">
                    Tak, gorąco zachęcamy do odwiedzin! Umawiamy wizyty po wcześniejszym kontakcie
                    telefonicznym lub mailowym. Chcemy, abyś zobaczył domowe warunki, w jakich
                    rosną nasze koty.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Czy oferujecie wsparcie po zakupie kocięcia?
                </summary>
                <p class="faq-item__answer text-body">
                    Absolutnie tak. Jesteśmy dostępni przez telefon i e-mail przez
                    cały okres życia kota. Pomagamy w kwestiach adaptacji, żywienia,
                    zdrowia i wychowania.
                </p>
            </details>
        </div>
    </x-frontend.section>

    <x-slot:scripts>
    <script>
        function switchContactTab(tab) {
            const quickPanel = document.getElementById('contactQuickPanel');
            const emailPanel = document.getElementById('contactEmailPanel');
            const quickBtn = document.getElementById('tabQuickBtn');
            const emailBtn = document.getElementById('tabEmailBtn');

            if (tab === 'email') {
                if (quickPanel) quickPanel.style.display = 'none';
                if (emailPanel) emailPanel.style.display = 'block';
                if (quickBtn) {
                    quickBtn.classList.remove('is-active');
                    quickBtn.setAttribute('aria-selected', 'false');
                }
                if (emailBtn) {
                    emailBtn.classList.add('is-active');
                    emailBtn.setAttribute('aria-selected', 'true');
                }
            } else {
                if (quickPanel) quickPanel.style.display = 'flex';
                if (emailPanel) emailPanel.style.display = 'none';
                if (quickBtn) {
                    quickBtn.classList.add('is-active');
                    quickBtn.setAttribute('aria-selected', 'true');
                }
                if (emailBtn) {
                    emailBtn.classList.remove('is-active');
                    emailBtn.setAttribute('aria-selected', 'false');
                }
            }
        }

        function selectQuickBreed(btn) {
            const container = document.getElementById('contactQuickBreedPills');
            if (container) {
                container.querySelectorAll('.quick-breed-pill').forEach(b => b.classList.remove('is-selected'));
            }
            btn.classList.add('is-selected');
            const input = document.getElementById('contactQuickSelectedBreed');
            if (input) {
                input.value = btn.dataset.breed || 'Wszystkie rasy';
            }
        }

        function submitContactQuickCallback(e) {
            e.preventDefault();
            const phoneInput = document.getElementById('contactQuickPhone');
            const errSpan = document.getElementById('contactQuickPhoneError');
            const hp = document.getElementById('contactQuickHp');
            const breedInput = document.getElementById('contactQuickSelectedBreed');
            const submitBtn = document.getElementById('contactQuickSubmitBtn');
            const successBox = document.getElementById('contactQuickSuccess');

            if (hp && hp.value.trim() !== '') {
                return false;
            }

            const phoneVal = phoneInput ? phoneInput.value.trim() : '';
            if (!phoneVal || phoneVal.replace(/\D/g, '').length < 6) {
                if (errSpan) errSpan.style.display = 'block';
                if (phoneInput) phoneInput.focus();
                return false;
            }
            if (errSpan) errSpan.style.display = 'none';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Przesyłanie zgłoszenia...';
            }

            const payload = {
                phone: phoneVal,
                cat_breed: breedInput ? breedInput.value : 'Wszystkie rasy',
                traffic_source: 'kontakt_page_lead'
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ url('/api/quick-callback') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (successBox) successBox.style.display = 'block';
                    document.getElementById('contactQuickForm').style.display = 'none';
                    if (typeof window.trackFunnelStep === 'function') {
                        window.trackFunnelStep('contact_quick_callback_submitted', { phone: phoneVal });
                    }
                } else {
                    alert(data.message || 'Wystąpił błąd podczas wysyłania zgłoszenia. Spróbuj zadzwonić bezpośrednio: +48 514 153 204');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '📞 Poproś o szybki kontakt (oddzwonimy)';
                    }
                }
            })
            .catch(() => {
                alert('Wystąpił problem z połączeniem. Zadzwoń do nas bezpośrednio: +48 514 153 204');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '📞 Poproś o szybki kontakt (oddzwonimy)';
                }
            });
        }

        // Auto-switch to email tab if validation errors or session status exist
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any() || session('status') || session('error') || request('tab') === 'email' || request('subject'))
                switchContactTab('email');
            @endif
        });
    </script>
    </x-slot:scripts>

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ContactPage",
      "name": "Kontakt — Domowa Hodowla Kotów z Mazowieckiej Szwajcarii",
      "url": "{{ route('contact') }}",
      "mainEntity": {
        "@@type": "LocalBusiness",
        "name": "Domowa Hodowla Kotów z Mazowieckiej Szwajcarii",
        "image": "{{ asset('og-default.jpg') }}",
        "logo": "{{ asset('logo.png') }}",
        "telephone": "+48514153204",
        "email": "biuro@kotyzmazowieckiejszwajcarii.pl",
        "address": {
          "@@type": "PostalAddress",
          "streetAddress": "Sikórz 56A",
          "addressLocality": "Sikórz",
          "postalCode": "09-413",
          "addressRegion": "mazowieckie",
          "addressCountry": "PL"
        },
        "geo": {
          "@@type": "GeoCoordinates",
          "latitude": "52.618641",
          "longitude": "19.571424"
        }
      }
    }
    </script>
    @endpush

</x-frontend.shell>