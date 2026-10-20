# SESSION_CRO_2026-10-15.md

> Dokument sesji roboczej — Optymalizacja Konwersji Dolnego Odcinka Lejka Sprzedażowego (CRO)
> Data: 2026-10-15
> Status: WSZYSTKIE 6 FAZ ZAIMPLEMENTOWANE I SPRAWDZONE
> Projekt: kotyzmazowieckiejszwajcarii.pl (professional-cat-breeding-cms)

---

## 1. KONTEKST BIZNESOWY I CELE

**Główny problem:** Użytkownicy wchodzący na stronę (90% ruch mobile z Facebook Ads / grup FB) odbijają się na dole lejka. Strona kierowała ich dotąd do formularza `/kontakt` (e-mail), co generowało zero konwersji, ponieważ odbiorcy kupujący koty oczekują natychmiastowego, bezpośredniego kontaktu mobilnego (telefon, WhatsApp z wideo-wizytą na żywo, Messenger) lub szybkiego callbacku.

### Kluczowe dane kontaktowe i wytyczne:
- **Telefon:** `+48 514 153 204`
- **WhatsApp:** `https://wa.me/48514153204`
- **Messenger:** profil FB `https://m.me/61580668026948` (lub https://www.facebook.com/profile.php?id=61580668026948)
- **Zero wzmianek o zadatku / cenie** w UI rezerwacji (kwestie omawiane ustnie przez hodowcę podczas rozmowy/wideo-wizyty).
- **Atrybucja WhatsApp:** Dyskretny kod na końcu wiadomości (np. `[Kod: FB_Ads_bengale]`), aby hodowca na telefonie od razu wiedział, skąd przyszedł klient.
- **Zero-Overwrite Policy:** Bezpieczeństwo bazy produkcyjnej, brak niszczących migracji czy seederów na produkcji.
- **Wczesna rezerwacja:** Kocięta w wieku poniżej 12 tygodni oznaczane automatycznie jako "Wczesna rezerwacja • Odbiór za X tyg.".

---

## 2. DECYZJE WŁAŚCICIELA (USTALONE)

1. **Meta Pixel ID:** Na ten moment placeholder w konfiguracji / `.env` (`VITE_META_PIXEL_ID` / `META_PIXEL_ID`).
2. **Facebook / Messenger:** Użycie linku `https://m.me/61580668026948` oraz profilu `https://www.facebook.com/profile.php?id=61580668026948`.
3. **Kryterium wczesnej rezerwacji:** Automatyczne wyliczanie z daty urodzenia (`date_of_birth` < 12 tygodni od dzisiaj).
4. **Kod źródła (Attribution):** Dyskretny zapis na końcu wiadomości WhatsApp, widoczny dla hodowcy.
5. **Quiz rasowy (Faza 5):** Osobna dedykowana podstrona `/dobierz-rase` (nie zaśmiecać strony głównej).
6. **Local SEO (Faza 6):** Pełny SSR z dynamicznym zaciąganiem dostępnych kotów z bazy.
7. **Kolejność wdrożenia:**
   - **KROK 1: Faza 3 — Modal Rezerwacji i Wideo-Wizyt (Reservation Modal)** — natychmiastowe zatrzymanie ucieczki klientów
   - **KROK 2: Faza 2 — Mobile Sticky Bar** (stały pasek na dole ekranu w strefie kciuka)
   - **KROK 3: Faza 1 — Attribution Engine (UTM capture + GA4/Meta Funnel tracking)**
   - **KROK 4: Faza 4 — Homepage Mobile Refactor & Odznaki Kociąt (Gotowy vs Wczesna rezerwacja)**
   - **KROK 5: Faza 5 — Quiz Doboru Rasy (`/dobierz-rase`)**
   - **KROK 6: Faza 6 — Programmatic Local SEO (Warszawa, Łódź, Toruń, Bydgoszcz)**

---

## 3. ARCHITEKTURA TECHNOLOGICZNA

- **Framework:** Laravel 12 + Livewire 4 + Blade Components + Vite.
- **Styling:** Vanilla CSS z Design Tokens (`resources/css/design-tokens.css`), brak TailwindCSS.
- **Skrypty:** Vanilla JS (moduły w `resources/js/`), bez zewnętrznych ciężkich bibliotek, pełna kompatybilność z iOS Safari i FB In-App Browser.
- **Ikony:** Lucide Icons (tree-shaken w `resources/js/app.js`).

---

## 4. SZCZEGÓŁOWY PLAN REALIZACJI POSZCZEGÓLNYCH FAZ

### KROK 1: FAZA 3 — Reservation & Live Video-Visit Modal (AKTYWNIE WDRAŻANE)
1. Utworzenie komponentu Blade: `resources/views/components/frontend/reservation-modal.blade.php`:
   - Nagłówek dynamiczny (Imię kota + Rasa lub zapytanie ogólne).
   - Treść skupiona na wideo-wizycie przez telefon i bezstresowym poznaniu kociaka w domowych warunkach.
   - 4 ścieżki kontaktu:
     a) WhatsApp (przycisk wyróżniony, bezpośredni link z pre-filled textem i kodem atrybucji).
     b) Messenger (`https://m.me/61580668026948`).
     c) Telefon bezpośredni (`tel:+48514153204`).
     d) 1-polowy formularz szybkiego oddzwonienia (wpisanie numeru telefonu i wysyłka AJAX do dedykowanego endpointu).
2. Obsługa backendu dla szybkiego callbacku:
   - Kontroler `app/Http/Controllers/CallbackController.php` (walidacja numeru, wysyłka powiadomienia e-mail/logowanie zdarzenia, ochrona throttlingiem `throttle:5,1`).
   - Trasa POST `/api/quick-callback` w `routes/web.php`.
3. Skrypt JS: `resources/js/reservation-modal.js`:
   - Otwieranie / zamykanie modala (`window.openReservationModal(catName, catBreed, statusLabel)`).
   - Obsługa ESC, tła, blokada scrolla body (`overflow: hidden`).
   - Generowanie dynamicznego linku WhatsApp z kodem atrybucji z `sessionStorage`.
   - Obsługa wysyłki formularza callback przez fetch AJAX z informacją o sukcesie bez przeładowania strony.
4. Styl CSS: `resources/css/components/reservation-modal.css` spójny z motywem ciemnym / złotym / parchment hodowli.
5. Dołączenie modala do `resources/views/components/frontend/shell.blade.php`.
6. Podpięcie triggerów:
   - Karta kota: `resources/views/components/frontend/animal-card.blade.php` (przycisk wideo-wizyty).
   - Podstrona kota: `resources/views/frontend/animals/show.blade.php` (zastąpienie linku do `/kontakt` otwieraniem modala).

---

### KROK 2: FAZA 2 — Mobile Sticky Bar
1. Komponent `resources/views/components/frontend/mobile-sticky-bar.blade.php`.
2. Przyciski: WhatsApp (Wideo-wizyta) + Zadzwoń (`tel:+48514153204`).
3. Dedykowane style w `resources/css/components/mobile-sticky-bar.css` (ukryty na desktop `md:hidden`, strefa bezpieczna `env(safe-area-inset-bottom)`).
4. Dodanie paddingu dolnego do stopki, aby pasek nie zasłaniał treści.

---

### KROK 3: FAZA 1 — Attribution Engine & Meta Pixel
1. Rozbudowa `resources/js/analytics.js`:
   - Przechwytywanie parametrów URL (`utm_source`, `utm_medium`, `utm_campaign`, `fbclid`) do `sessionStorage` z fallbackiem w `localStorage`.
   - Funkcja `getTrafficSourceLabel()` zwracająca czytelny tag źródła.
   - Dispatcher zdarzeń `trackFunnelStep(step, data)` dla GA4 i Meta Pixel.
2. Dodanie tagu Meta Pixel w `resources/views/components/frontend/shell.blade.php` zintegrowanego z banerem Cookie Consent.

---

### KROK 4: FAZA 4 — Mobile Homepage Refactor & Status Badges
1. Zmiana kolejności sekcji na mobile (kocięta wyżej niż opisy ras).
2. Rozróżnienie etykiet na kartach:
   - Kocięta < 12 tyg: "⏳ Wczesna rezerwacja • Odbiór za X tyg."
   - Kocięta >= 12 tyg: "🟢 Gotowy do odbioru • Odchowany"

---

### KROK 5: FAZA 5 — Quiz Doboru Rasy (`/dobierz-rase`)
1. Kontroler `QuizController`, widok `resources/views/frontend/quiz.blade.php`.
2. Logika pytań i dopasowania (Bengalski, Brytyjski, Syjamski) z natychmiastowym poleceniem kotów z bazy.

---

### KROK 6: FAZA 6 — Local SEO Pages
1. Dedykowany kontroler `LocalSeoController` i szablony dla Warszawy, Łodzi, Torunia, Bydgoszczy.
2. Dane strukturalne Schema.org `LocalBusiness` / `Organization`.
3. Sekcje z wideo-wizytami dla mieszkańców tych miast + dynamiczna lista kociąt.
4. Linki w stopce.

---

## 5. ZGODNOŚĆ KODU I REFACTORING KOMENTARZY (ENGLISH COMMENTS)

Wszystkie komentarze techniczne, nagłówki sekcji CSS/JS, phpdoc oraz opisy testów zostały przetłumaczone na profesjonalny język angielski, zgodnie z wymogiem prezentacji projektu międzynarodowym rekruterom i pracodawcom:
- `resources/js/reservation-modal.js`
- `resources/js/analytics.js`
- `app/Http/Controllers/CallbackController.php`
- `app/Http/Controllers/Frontend/LocalSeoController.php`
- `app/Http/Controllers/Frontend/QuizController.php`
- `resources/views/components/frontend/reservation-modal.blade.php`
- `resources/views/components/frontend/mobile-sticky-bar.blade.php`
- `resources/views/components/frontend/cookie-consent.blade.php`
- `resources/views/frontend/quiz.blade.php`
- `resources/views/frontend/local-seo/city.blade.php`
- `resources/css/components/reservation-modal.css`
- `resources/css/components/mobile-sticky-bar.css`
- `resources/css/pages/home-page.css`
- `resources/css/pages/quiz.css`
- `tests/Feature/Frontend/ReservationAndCroTest.php`

Treści interfejsu (UI copy, etykiety, komunikaty dla odwiedzających) pozostały w 100% po polsku.

---

## 6. STATUS WERYFIKACJI I TESTÓW
- **Vite Build (`npm run build`):** Zbudowane pomyślnie w 14.07s bez błędów.
- **PHPUnit / Pest (`php artisan test`):** 131 testów zakończonych sukcesem (730 asercji, 100% PASS).

