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

<style>
    /* ==========================================================================
       CONTACT HERO
       ========================================================================== */

    .contact-hero {
        padding-top: 44px;
    }

    .contact-hero .section {
        padding-top: var(--sp-4xl);
        padding-bottom: var(--sp-4xl);
    }

    .contact-hero__content {
        max-width: 640px;
    }

    .contact-hero__eyebrow {
        display: inline-block;
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--color-primary-on-dark);
        margin-bottom: var(--sp-lg);
    }

    .contact-hero__headline {
        color: var(--color-canvas);
        margin-bottom: var(--sp-lg);
    }

    .contact-hero__lead {
        color: var(--color-body-muted);
    }

    /* ==========================================================================
       CONTACT METHODS
       ========================================================================== */

    .contact-methods {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-lg);
    }

    .contact-method {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: var(--sp-xl);
        border-radius: var(--r-lg);
        border: 1px solid var(--color-hairline);
        text-decoration: none;
        transition: border-color var(--duration-fast) var(--ease-out),
                    transform var(--duration-base) var(--ease-out);
    }

    a.contact-method:hover {
        border-color: var(--color-primary);
        transform: translateY(-2px);
    }

    .contact-method__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: var(--r-full);
        background-color: var(--color-canvas-parchment);
        color: var(--color-primary);
        margin-bottom: var(--sp-md);
    }

    .contact-method__icon svg {
        width: 20px;
        height: 20px;
    }

    .contact-method__value {
        font-size: var(--text-btn-util-size);
        color: var(--color-ink-muted-48);
        margin-top: var(--sp-xxs);
    }

    /* ==========================================================================
       CONTACT FORM LAYOUT
       ========================================================================== */

    .contact-form-layout {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: var(--sp-3xl);
        align-items: start;
    }

    .contact-form-info__details {
        margin-top: var(--sp-lg);
    }

    .contact-form-info__details .text-body {
        color: var(--color-ink-muted-80);
    }

    .contact-form-card {
        background-color: var(--color-canvas);
        border-radius: var(--r-lg);
        padding: var(--sp-xl);
        border: 1px solid var(--color-hairline);
    }

    /* ==========================================================================
       FORM ELEMENTS
       ========================================================================== */

    .contact-form {
        display: flex;
        flex-direction: column;
        gap: var(--sp-lg);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: var(--sp-xs);
    }

    .form-label {
        font-size: var(--text-btn-util-size);
        font-weight: 600;
        color: var(--color-ink);
    }

    .form-label__optional {
        font-weight: 400;
        color: var(--color-ink-muted-48);
    }

    .form-input {
        font-family: var(--font-sans);
        font-size: var(--text-body-size);
        color: var(--color-ink);
        background-color: var(--color-canvas);
        border: 1px solid var(--color-hairline);
        border-radius: var(--r-sm);
        padding: var(--sp-sm) var(--sp-md);
        min-height: var(--touch-target-min);
        outline: none;
        transition: border-color var(--duration-fast) var(--ease-out),
                    box-shadow var(--duration-fast) var(--ease-out);
        width: 100%;
    }

    .form-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.12);
    }

    .form-input--error {
        border-color: var(--color-error);
    }

    .form-input--error:focus {
        box-shadow: 0 0 0 3px rgba(255, 59, 48, 0.12);
    }

    .form-textarea {
        resize: vertical;
        min-height: 140px;
    }

    .form-error {
        font-size: var(--text-nav-size);
        color: var(--color-error);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: var(--sp-xs);
    }

    /* Success alert */
    .form-alert {
        display: flex;
        align-items: flex-start;
        gap: var(--sp-sm);
        padding: var(--sp-md);
        border-radius: var(--r-sm);
        margin-bottom: var(--sp-lg);
    }

    .form-alert--success {
        background-color: rgba(52, 199, 89, 0.08);
        border: 1px solid rgba(52, 199, 89, 0.2);
        color: var(--color-success);
    }

    .form-alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .form-alert p {
        font-size: var(--text-btn-util-size);
    }

    /* ==========================================================================
       FAQ
       ========================================================================== */

    .faq-list {
        max-width: 720px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
    }

    .faq-item {
        border-bottom: 1px solid var(--color-hairline);
    }

    .faq-item__question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--sp-lg) 0;
        cursor: pointer;
        list-style: none;
        user-select: none;
        transition: color var(--duration-fast) var(--ease-out);
    }

    .faq-item__question::-webkit-details-marker {
        display: none;
    }

    .faq-item__question::after {
        content: '+';
        font-size: var(--text-lead-size);
        font-weight: 300;
        color: var(--color-ink-muted-48);
        transition: transform var(--duration-base) var(--ease-out);
        flex-shrink: 0;
        margin-left: var(--sp-lg);
    }

    .faq-item[open] .faq-item__question::after {
        transform: rotate(45deg);
    }

    .faq-item__question:hover {
        color: var(--color-primary);
    }

    .faq-item__answer {
        padding-bottom: var(--sp-lg);
        color: var(--color-ink-muted-80);
        max-width: 640px;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */

    @media (max-width: 834px) {
        .contact-methods {
            grid-template-columns: 1fr;
        }

        .contact-form-layout {
            grid-template-columns: 1fr;
            gap: var(--sp-xl);
        }
    }

    @media (max-width: 640px) {
        .contact-hero .section {
            padding-top: var(--sp-2xl);
            padding-bottom: var(--sp-2xl);
        }
    }
</style>
