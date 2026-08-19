<x-frontend.shell title="Kontakt i Rezerwacja Kociąt | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Skontaktuj się z naszą hodowlą w Sikorzu (woj. mazowieckie). Zadzwoń: +48 514 153 204 lub napisz przez formularz kontaktowy. Odpowiadamy w ciągu 24h."
    og-image="{{ asset('storage/media/parent_bella_1.jpg') }}">
    {{-- ============================================================
    1. TOP HERO + CONTACT FORM (Primary Action Front & Center)
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
                    <p class="text-lead-airy contact-hero-lead" style="margin-top: 16px; margin-bottom: 32px; color: var(--color-ink-muted-80);">
                        Masz pytania o dostępne kocięta, planowane mioty lub chcesz umówić się na odwiedziny w naszej domowej hodowli? Wypełnij formularz obok lub skontaktuj się z nami bezpośrednio — z radością odpowiemy.
                    </p>

                    {{-- Direct Contact Fast Access Cards --}}
                    <div class="contact-fast-links" role="list" aria-label="Szybki kontakt">
                        <a href="tel:+48514153204" class="contact-fast-link" role="listitem">
                            <div class="contact-fast-link__icon">
                                <i data-lucide="phone-call" aria-hidden="true"></i>
                            </div>
                            <div class="contact-fast-link__body">
                                <span class="contact-fast-link__label">Zadzwoń do nas</span>
                                <span class="contact-fast-link__val">+48 514 153 204</span>
                                <span class="contact-fast-link__hint">Dostępni codziennie • Szybka rozmowa</span>
                            </div>
                        </a>

                        <a href="mailto:biuro@kotyzmazowieckiejszwajcarii.pl" class="contact-fast-link" role="listitem">
                            <div class="contact-fast-link__icon">
                                <i data-lucide="mail" aria-hidden="true"></i>
                            </div>
                            <div class="contact-fast-link__body">
                                <span class="contact-fast-link__label">Napisz e-mail</span>
                                <span class="contact-fast-link__val">biuro@kotyzmazowieckiejszwajcarii.pl</span>
                                <span class="contact-fast-link__hint">Odpowiedź zwykle w ciągu 24h</span>
                            </div>
                        </a>

                        <div class="contact-fast-link" role="listitem">
                            <div class="contact-fast-link__icon">
                                <i data-lucide="map-pin" aria-hidden="true"></i>
                            </div>
                            <div class="contact-fast-link__body">
                                <span class="contact-fast-link__label">Lokalizacja hodowli</span>
                                <span class="contact-fast-link__val">Sikórz k. Płocka (woj. mazowieckie)</span>
                                <span class="contact-fast-link__hint">Wizyty po wcześniejszym umówieniu terminu</span>
                            </div>
                        </div>
                    </div>

                    {{-- Trust / Association Badge --}}
                    <div class="contact-trust-pill" style="margin-top: 28px; display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; background: rgba(209, 171, 88, 0.12); border: 1px solid rgba(209, 171, 88, 0.35); border-radius: 9999px; font-size: 0.85rem; color: #78350f;">
                        <i data-lucide="award" style="width: 18px; height: 18px; color: var(--color-primary);" aria-hidden="true"></i>
                        <span>Członek <strong>SHiOZ ZOOLANDIA</strong> (Certyfikat 58/CW/2025 · Rej. 58/P/2025)</span>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Interactive Form Card --}}
                <div class="contact-form-card" id="formularz">
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
                        <p style="font-size: 0.9rem; color: var(--color-ink-muted-80);">Wypełnij poniższe pola, a odezwiemy się tak szybko, jak to możliwe.</p>
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
                            <textarea name="message" id="message" rows="5"
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
    </section>

    {{-- ============================================================
    2. INTERACTIVE LOCATION & DIRECTIONS (Google Maps)
    ============================================================ --}}
    <x-frontend.section>
        <x-frontend.section-header eyebrow="Dojazd" headline="Gdzie nas znaleźć?"
            description="Nasza hodowla mieści się w cichej, zielonej miejscowości Sikórz k. Płocka (woj. mazowieckie). Dogodny dojazd z Warszawy, Torunia, Włocławka i Łodzi." />

        <div class="contact-map-embed reveal-up" style="margin-top: 32px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19561.42857416954!2d19.571424!3d52.618641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471c4c1a5d625cdb%3A0x8e82110c73291fb2!2sSik%C3%B3rz%2C%2009-413!5e0!3m2!1spl!2spl!4v1710000000000!5m2!1spl!2spl"
                width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy"
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