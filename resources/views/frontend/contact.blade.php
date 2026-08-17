<x-frontend.shell title="Kontakt i Rezerwacja Kociąt | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Skontaktuj się z naszą hodowlą w Sikorzu (woj. mazowieckie). Zadzwoń: +48 514 153 204 lub napisz przez formularz kontaktowy. Odpowiadamy w ciągu 24h."
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}">
    {{-- ============================================================
    HERO — Calm, inviting
    „Jak mogę się skontaktować?"
    ============================================================ --}}
    <section class="contact-hero tile-parchment" aria-label="Kontakt">
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
            <a href="mailto:hodowla.z.mazowieckiej.szwajcarii@gmail.com" class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="mail" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">E-mail</h3>
                <p class="contact-method__value">hodowla.z.mazowieckiej.szwajcarii@gmail.com</p>
            </a>

            <a href="tel:+48514153204" class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="phone" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">Telefon</h3>
                <p class="contact-method__value">+48 514 153 204</p>
            </a>

            <div class="contact-method">
                <div class="contact-method__icon">
                    <i data-lucide="map-pin" aria-hidden="true"></i>
                </div>
                <h3 class="contact-method__title text-body-strong">Lokalizacja</h3>
                <p class="contact-method__value">Sikórz, woj. mazowieckie, Polska</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    INQUIRY FORM — Reduce friction, friendly labels
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="formularz">
        <div class="contact-form-layout">
            <div class="contact-form-info">
                <x-frontend.section-header align="left" eyebrow="Formularz" headline="Wyślij zapytanie"
                    description="Wypełnij formularz, a skontaktujemy się z Tobą tak szybko, jak to możliwe." />

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

                @if(session('error'))
                    <div class="form-alert form-alert--error" role="alert" style="background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; color: #991b1b; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="alert-circle" aria-hidden="true"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('frontend.contact.store') }}" method="POST" class="contact-form" id="contactForm" novalidate onsubmit="const btn = this.querySelector('button[type=submit]'); if (btn) { btn.disabled = true; btn.style.opacity = '0.7'; btn.style.pointerEvents = 'none'; btn.innerText = 'Wysyłanie wiadomości...'; }">
                    @csrf

                    {{-- Invisible anti-spam honeypot --}}
                    <input type="text" name="_hp_website" style="display:none !important; position:absolute; left:-9999px;" tabindex="-1" autocomplete="off" aria-hidden="true">

                    @if(request('subject') || old('subject'))
                        <input type="hidden" name="subject" value="{{ old('subject', request('subject')) }}">
                        <div class="form-subject-badge" style="margin-bottom: 18px; padding: 10px 14px; background: rgba(184, 134, 11, 0.08); border-left: 3px solid var(--color-gold, #b8860b); border-radius: 6px; font-size: 0.9rem; color: #78350f;">
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
                        <textarea name="message" id="message" rows="6"
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

                    <p class="form-rodo-info text-caption text-ink-muted-60" style="margin-top: 16px; font-size: 0.85rem; line-height: 1.5;">
                        Wysyłając formularz, przekazujesz nam swoje dane osobowe, które przetwarzamy w celu obsługi Twojego zapytania i udzielenia odpowiedzi. Podanie danych jest dobrowolne, przy czym dane oznaczone jako wymagane są niezbędne do obsługi zapytania. Podanie numeru telefonu jest dobrowolne. Szczegółowe informacje dotyczące przetwarzania danych osobowych znajdziesz w <a href="{{ route('privacy') }}" class="text-primary" style="text-decoration: underline;">Polityce Prywatności</a>.
                    </p>
                </form>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    GOOGLE MAPS EMBED
    ============================================================ --}}
    <x-frontend.section>
        <div class="contact-map-embed reveal-up">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19561.42857416954!2d19.571424!3d52.618641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471c4c1a5d625cdb%3A0x8e82110c73291fb2!2sSik%C3%B3rz%2C%2009-413!5e0!3m2!1spl!2spl!4v1710000000000!5m2!1spl!2spl"
                width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokalizacja Hodowla Kotów z Mazowieckiej Szwajcarii — Sikórz"></iframe>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    FAQ — Common questions
    ============================================================ --}}
    <x-frontend.section id="faq">
        <x-frontend.section-header eyebrow="FAQ" headline="Najczęstsze pytania" />

        <div class="faq-list">
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
                    Kocięta opuszczają hodowlę po ukończeniu 14. tygodnia życia,
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

    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "ContactPage",
      "name": "Kontakt — Hodowla Kotów z Mazowieckiej Szwajcarii",
      "url": "{{ route('contact') }}",
      "mainEntity": {
        "@@type": "LocalBusiness",
        "name": "Hodowla Kotów z Mazowieckiej Szwajcarii",
        "image": "{{ asset('storage/media/parent_bella_1.jpg') }}",
        "telephone": "+48514153204",
        "email": "hodowla.z.mazowieckiej.szwajcarii@gmail.com",
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