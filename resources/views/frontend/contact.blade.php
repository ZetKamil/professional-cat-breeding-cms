<x-frontend.shell
    title="Kontakt — {{ config('app.name') }}"
    meta-description="Skontaktuj się z naszą hodowlą — formularz kontaktowy, telefon, e-mail. Chętnie odpowiemy na Twoje pytania."
>
    {{-- ============================================================
         HERO — Calm, inviting
         „Jak mogę się skontaktować?"
         ============================================================ --}}
    <section class="contact-hero tile-dark" aria-label="Kontakt">
        <div class="section">
            <div class="section-inner">
                <div class="contact-hero__content">
                    <span class="contact-hero__eyebrow">Kontakt</span>
                    <h1 class="text-hero-display contact-hero__headline">
                        Porozmawiajmy.
                    </h1>
                    <p class="text-lead-airy contact-hero__lead">
                        Masz pytania o nasze koty? Chcesz umówić wizytę?
                        Napisz do nas — odpowiadamy w ciągu 24 godzin.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CONTACT METHODS — Quick access
         ============================================================ --}}
    <x-frontend.section>
        <div class="contact-methods">
            <a href="mailto:kontakt@example.com" class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="mail" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">E-mail</h3>
                <p class="contact-method__value">kontakt@example.com</p>
            </a>

            <a href="tel:+48000000000" class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="phone" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">Telefon</h3>
                <p class="contact-method__value">+48 000 000 000</p>
            </a>

            <div class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="map-pin" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">Lokalizacja</h3>
                <p class="contact-method__value">Polska</p>
            </div>
        </div>

        {{-- Google Maps Placeholder --}}
        <div class="contact-map-placeholder reveal-up">
            <i data-lucide="map" class="contact-map__icon" aria-hidden="true"></i>
            <span class="contact-map__text text-tagline">Mapa dojazdu (Integracja Google Maps)</span>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         INQUIRY FORM — Reduce friction, friendly labels
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="formularz">
        <div class="contact-form-layout">
            <div class="contact-form-info">
                <x-frontend.section-header
                    align="left"
                    eyebrow="Formularz"
                    headline="Wyślij zapytanie"
                    description="Wypełnij formularz, a skontaktujemy się z Tobą tak szybko, jak to możliwe."
                />

                <div class="contact-form-info__details">
                    <p class="text-body">
                        Odpowiadamy na wiadomości zwykle w ciągu 24 godzin.
                        Jeśli sprawa jest pilna, zadzwoń.
                    </p>
                </div>
            </div>

            <div class="contact-form-card">
                @if(session('status'))
                    <div class="form-alert form-alert--success" role="alert">
                        <i data-lucide="check-circle" aria-hidden="true"></i>
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                <form
                    action="{{ route('frontend.contact.store') }}"
                    method="POST"
                    class="contact-form"
                    novalidate
                >
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Imię i nazwisko</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="form-input @error('name') form-input--error @enderror"
                            autocomplete="name"
                            required
                        >
                        @error('name')
                            <p class="form-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Adres e-mail</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="form-input @error('email') form-input--error @enderror"
                            autocomplete="email"
                            required
                        >
                        @error('email')
                            <p class="form-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            Telefon
                            <span class="form-label__optional">(opcjonalnie)</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            value="{{ old('phone') }}"
                            class="form-input"
                            autocomplete="tel"
                        >
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Wiadomość</label>
                        <textarea
                            name="message"
                            id="message"
                            rows="6"
                            class="form-input form-textarea @error('message') form-input--error @enderror"
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="form-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <x-frontend.button type="submit" icon="send">
                            Wyślij wiadomość
                        </x-frontend.button>
                    </div>
                </form>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         FAQ — Common questions
         ============================================================ --}}
    <x-frontend.section id="faq">
        <x-frontend.section-header
            eyebrow="FAQ"
            headline="Najczęstsze pytania"
        />

        <div class="faq-list">
            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Jak wygląda proces adopcji kota?
                </summary>
                <p class="faq-item__answer text-body">
                    Proces rozpoczyna się od kontaktu — odpowiadamy na pytania, przedstawiamy
                    dostępne kocięta i umawiamy wizytę w hodowli. Po podjęciu decyzji
                    podpisujemy umowę adopcyjną i ustalamy termin odbioru.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    W jakim wieku kocięta opuszczają hodowlę?
                </summary>
                <p class="faq-item__answer text-body">
                    Kocięta opuszczają hodowlę nie wcześniej niż w 14. tygodniu życia,
                    po ukończeniu pełnego cyklu szczepień i socjalizacji.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Jakie dokumenty otrzymuję?
                </summary>
                <p class="faq-item__answer text-body">
                    Każdy kot otrzymuje: rodowód FPL/FIFe, książeczkę zdrowia z historią
                    szczepień, wyniki badań genetycznych rodziców, umowę adopcyjną
                    oraz starter pack z karmą i zabawkami.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Czy mogę odwiedzić hodowlę przed podjęciem decyzji?
                </summary>
                <p class="faq-item__answer text-body">
                    Tak, zachęcamy do odwiedzin! Umawiamy wizyty po wcześniejszym kontakcie
                    telefonicznym lub mailowym. Chcemy, abyś zobaczył warunki, w jakich
                    rosną nasze koty.
                </p>
            </details>

            <details class="faq-item">
                <summary class="faq-item__question text-body-strong">
                    Czy oferujecie wsparcie po adopcji?
                </summary>
                <p class="faq-item__answer text-body">
                    Absolutnie tak. Jesteśmy dostępni przez telefon i e-mail przez
                    cały okres życia kota. Pomagamy w kwestiach adaptacji, żywienia,
                    zdrowia i wychowania.
                </p>
            </details>
        </div>
    </x-frontend.section>

</x-frontend.shell>
