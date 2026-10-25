# AI/CHANGELOG.md

## [2026-10-25] Quiet Luxury Unification & Senior UI/UX Design System Overhaul

### Added & Refined
- **Eliminacja Kolorów Neonowych i Ujednolicenie Quizu (`/dobierz-rase`)**:
  - Usunięcie wszelkich jaskrawych odcieni zieleni (`#25D366`), błękitu Messenger (`#0084FF`), brązów i granatu na rzecz palety Quiet Luxury: `#111111` Charcoal, `#FAF8F5` Warm Cream, `#C5A059` Champagne Gold.
  - Podstawowy przycisk Messenger w solidnym Champagne Gold (`#C5A059` z hover `#B38E46`), wtórny WhatsApp z subtelną ramką i tłem transparentnym.
  - Karta obserwacji profilu na Facebooku na dole quizu w estetyce ciemno-złotej z obramowaniem i konturowym przyciskiem.
  - Boxy powiadomień o wczesnej rezerwacji i propozycjach alternatywnych ras zyskały aksamitne, złote wykończenie.
- **Redesign Kart Dopasowanych Kociąt w Wynikach Quizu**:
  - Nowoczesna siatka mini-kart (`grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))`).
  - Powiększone zdjęcia 80×80px z subtelnym obramowaniem w złocie.
  - Szeryfowa typografia dla imion kociąt, wskaźnik statusu odbioru, etykieta ceny w Champagne Gold.
  - Pojedynczy, czysty przycisk `Zobacz profil →` prowadzący do profilu kota.
- **Redesign Kart Kotów i Proporcje Zdjęć 4:3 (`aspect-ratio: 4 / 3`)**:
  - Przekształcenie kontenerów zdjęć kotów z nadmiernie wysokich (pionowe smartfonowe 9:16/3:4) na prestiżowy format `aspect-ratio: 4 / 3` z `object-fit: cover; object-position: center 25%`.
  - Nowa estetyczna stopka karty: subtelna linia graniczna, cena kociaka w pogrubionym szeryfie/złocie po lewej stronie oraz pigułkowy przycisk `Zobacz profil →` po prawej.
- **Podział Katalogu `/koty` na Dwie Wyraźne Sekcje Wizualne**:
  - **Sekcja 1 (Góra):** „Dostępne Kocięta i Mioty” – wyłącznie kocięta do rezerwacji i odbioru z widocznymi cenami.
  - **Luksusowy Separator:** Dyskretna linia z diamentowym motywem `✦` w odcieniu Champagne Gold.
  - **Sekcja 2 (Dół):** „Nasi Rodzice i Koty Hodowlane” – dorośli rodzice miotów i koty hodowlane (Atos, Luki, Koko) prezentowani bez cen, z akcentem na linie championów i badania genetyczne.
- **Profil Kota (`/koty/[slug]`) Layout & Balance Fix**:
  - **Kompaktowa siatka 3×2 dla atrybutów:** Rasa, Płeć, Umaszczenie, Wiek, Odbiór, Rodowód (wysokość 72px z wyważonymi paddingami).
  - **Luksusowy Baner Cenowy:** Wyniesiony bezpośrednio nad opis kota i przyciski akcji (`2 700 zł — opcja „na kolanka” z rodowodem i wyprawką`).
  - **Sticky Gallery na Desktopie:** Lewa kolumna galerii ze zdjęciem głównym i miniaturami zakotwiczona na `position: sticky; top: 100px;` (brak pustych przestrzeni podczas czytania prawej kolumny).
  - **Jednorzędowy pasek miniatur:** Siatka 6 kolumn (`grid-template-columns: repeat(6, 1fr)`) idealnie dopasowana pod zdjęciem głównym.
  - **Portrety Rodziców 88×88px w Rodowodzie:** Powiększenie awatarów rodziców (Ojciec / Matka) z małych 48px kółek do formatu 88×88px rounded-xl z obramowaniem w złocie (`.pedigree-card__portrait`).

## [2026-10-25] Master UI/UX Unification, Admin Price Dashboard & Premium Funnel Refactor

### Added
- **Zarządzanie Cenami w Panelu Admina (`/backend/animals`, `/backend/dashboard`)**:
  - Kolumna szybkiej edycji `Cena (zł)` z inline formularzem i natychmiastowym zapisem (`PATCH /backend/animals/{animal}/quick-price`).
  - Pole `price` (unsigned integer PLN) w schemacie `animals`, mutator i akcesor `formattedPrice()` (`2 700 zł`) w modelu `Animal`.
  - Migracja `2026_10_25_120000_add_price_to_animals_and_set_initial_prices.php` z bezpiecznym seedowaniem cen początkowych: Baron `2 700 zł`, Cyprian `3 000 zł`, Carlos `3 000 zł`, Brytyjskie kocięta `2 500 zł`, Syjamskie kocięta `2 100 zł`.
- **Prezentacja Cen na Kartach i Profilu Kota (`/koty`, `/koty/[slug]`)**:
  - Pigułkowy badge cenowy w złotym odcieniu `.animal-card__price-badge` na kartach kotów.
  - Luksusowy Price Box `.animal-profile__price-box` na profilu kota z etykietą `CENA KOCIAKA (OPCJA „NA KOLANKA”)`, szeryfową typografią i opisem wyprawki z rodowodem.
  - Złoty przycisk Primary Messenger `#C5A059` z predefiniowaną wiadomością zapytań o kota i cenę (`https://m.me/61580668026948?text=...`).
  - Wtórny przycisk konturowy WhatsApp (+48 514 153 204).
  - Wzbogacenie boxu wideo-wizyty o wzmiankę o Messengerze.
- **Refaktoryzacja Ekranu Wyników Quizu (`/dobierz-rase`)**:
  - Usunięcie powtórzonego górnego bloku CTA (eliminacja visual clutter).
  - Zastąpienie neonowych przycisków w wierszach kociąt jednym spójnym przyciskiem: `🎬 Zobacz profil, zdjęcia i filmiki →` oraz odznaką ceny.
  - Pojedynczy dolny blok kontaktu w stylu Quiet Luxury: złoty Messenger, konturowy WhatsApp, mikro-kopia gwarancyjna.
  - Karta zaproszenia na Facebooka zyskała ciemno-złote obramowanie i elegancki przycisk konturowy.
- **Ujednolicenie Globalnego CTA Przedstopkowego (`conversion-cta.blade.php`)**:
  - Przycisk 1 (Primary Gold): `💬 Napisz lub umów wideo-wizytę (Messenger)`.
  - Przycisk 2 (Secondary Outline): `Kontakt przez WhatsApp`.
  - Subtelny odnośnik do codziennych relacji na Facebooku hodowli.
- **SEO & Analityka**:
  - Schema.org `Product` / `Offer` (`price`, `PLN`, `InStock`) na podstronach kociąt z ustaloną ceną.
  - Zachowanie zdarzenia Meta Pixel `fbq('track', 'Contact')` na wszystkich przyciskach Messenger i WhatsApp.
- **Harden Edge Cases & Concurrency / Race Conditions**:
  - Mutator `Animal::setPriceAttribute` odporny na: ujemne wartości, zera, dziwne znaki, znaczniki XSS oraz overflow (limit max 1 000 000 zł).
  - Akcesory `formattedPrice()` i `shouldShowPrice()` wykluczają zerowe i ujemne ceny (kocięta rodowodowe nigdy nie mają etykiety 0 zł).
  - `AnimalController::updateQuickPrice` zabezpieczony transakcją bazodanową z blokadą pesymistyczną (`DB::transaction` + `lockForUpdate()`), co całkowicie zapobiega race conditions przy jednoczesnych edycjach lub podwójnych kliknięciach.
  - Walidacja wejścia cenowego (`max:50` / `max:1000000`) w kontrolerze i FormRequestach (`StoreAnimalRequest`, `UpdateAnimalRequest`).
  - Metoda `Animal::age()` zabezpieczona przed datami w przyszłości (`Oczekiwany miot`), zerem dni (`Dziś`) oraz uzupełniona o poprawną polską odmianę liczebników (1 miesiąc, 2-4 miesiące, 5 miesięcy, 1 rok, 2-4 lata, 5 lat).
- **Testy Automatyczne**:
  - Nowy zestaw testów `tests/Feature/Frontend/PriceAndFunnelUnificationTest.php` rozszerzony o testy warunków brzegowych i współbieżności (12 testów, 90 asercji).
  - Całkowity wynik testów aplikacji: 197 testów przeszło pomyślnie (1581 asercji), 100% PASS.

## [2026-10-17] Sprint Animals Media — Atos Profile & Media Integration

### Added
- **Profil i Media Kocura Atosa (`/koty/atos`)**:
  - Główne zdjęcie profilowe 1:1 (`parent_atos_1.jpg`) ze zoptymalizowanymi wariantami WebP (400px, 800px, 1200px).
  - Zdjęcie galeryjne z aniołkiem (`parent_atos_2.jpg`) w formacie 1536×2048.
  - Bezpieczna migracja `2026_10_17_110000_attach_atos_photos.php` przypisująca media i porządkująca hierarchię sortowania (`sort_order = 33`).
  - Pokrycie testami funkcjonalnymi w `AtosProfileTest.php`.

## [2026-08-11] Sprint 7 — Security, SEO, Forms & Final Production QA

### Added
- **Strony błędów w marce (`errors/`)**: Dodano dedykowane widoki błędów `403.blade.php`, `419.blade.php` i `429.blade.php` spójne stylistycznie ze stroną główną.
- **Testy bezpieczeństwa kontaktowego (`ContactTest.php`)**: Utworzono 15 testów dla formularza kontaktowego (`throttle:5,1`, CSRF, limity znakowe, payload).
- **Konfiguracja produkcyjna (`.env.production`)**: Utworzono dedykowany szablon `.env.production` pod domenę `kotyzmazowieckiejszwajcarii.pl`.

### Changed
- **Walidacja formularza kontaktowego (`ContactFormRequest.php`)**: Wzmocniono reguły walidacji (`email:rfc`, `max:254`, `subject min:2`, `message min:15`).
- **Robots & Sitemap (`robots.txt`)**: Zablokowano `/storage/` przed indeksowaniem i ustawiono adres produkcyjny sitemap.
- **Polityka Prywatności (`privacy.blade.php`)**: Zaktualizowano pełny, 11-punktowy tekst prawny RODO, dane rejestrowe REGON, kontaktowe oraz zasady cookies z datą 11.08.2026.
- **Routing panelu (`web.php`)**: Ujednolicono trasy nawigacyjne — jedynym oficjalnym adresem pulpitu pozostał `/dashboard`.

## [2026-08-06] Sprint 7 — Mobile Experience Audit & Refinement

### Fixed (Mobile Only — Desktop Unchanged)
- **Safe Area Support (`header.css`, `footer.css`)**: Added `env(safe-area-inset-top)` to `.site-header` (iPhone notch/Dynamic Island), `env(safe-area-inset-bottom)` to `.mobile-menu__footer` and `.footer-bottom` (gesture bar iOS/Android).
- **Hero Mobile (`hero.css`)**: Reduced `padding-top` from `clamp(140px,16vh,180px)` to `clamp(90px,14vh,120px)` on mobile so headline lands in first fold. Made CTA buttons full-width (`align-items: stretch; width: 100%`). Reduced lead font-size and line-height.
- **Global Overflow (`design-tokens.css`)**: Added `overflow-x: hidden` on `body.frontend-shell` under `max-width: 1023px`. Added `overscroll-behavior: none` on `body.menu-open` to prevent iOS scroll-chain.
- **Typography Tokens (`design-tokens.css`)**: Added mobile overrides for `--text-lead-airy-size` (24px → 20px) and `--text-tagline-size` (21px → 18px) under `max-width: 640px`.
- **Footer Touch Target (`footer.css`)**: Increased social link from 40×40px to 44×44px (Apple HIG minimum). Added vertical newsletter form layout on tiny screens (<480px). Added safe-area padding to `.footer-bottom`.
- **Hamburger (`header.css`)**: Increased hamburger lines from 1.5px to 2px height, 18px to 20px width. Added compact mobile menu layout for very small viewports (<400px).
- **Homepage (`home-page.css`)**: Reduced hero-split frame max-height on mobile. Lowered manifesto quote clamp minimum (1.75rem → 1.375rem). Reduced manifesto tenet gap on mobile. Reduced collection card min-height (380px → 280px on mobile). Compact kodeks column padding on mobile. Reduced editorial empty box padding on mobile.
- **About Page (`about.css`)**: Fixed gallery grid `grid-template-rows: auto` on mobile (was fixed 240px). Added `aspect-ratio: 16/9` to gallery items. Reduced `.about-preview__badge-overlap` padding on small screens.
- **Animals Page (`animals-page.css`)**: Reduced animal profile `padding-top` on mobile. Reduced thumbnail size (80px → 68px) and gap on mobile. Compact spec card padding. Smaller breed pill padding on tiny screens.
- **Blog Page (`blog-page.css`)**: Reduced article cover hero `min-height` (560px → 380px) on mobile. Lowered article title clamp minimum (2.25rem → 1.625rem). Added vertical author bar stacking on mobile. Reduced blog hero padding-top.
- **Contact Page (`contact.css`)**: Added 2-column mid-breakpoint (640–834px) for contact methods. Full-width submit button on mobile. Reduced map embed height (420px → 260px) on mobile.

## [2026-07-31] Sprint 6.8 — Luxury UI/UX Final Polish & Privacy Compliance

### Added
- **Publiczny Katalog Kotów (`/koty`, `/koty/{slug}`)**:
  - Kontroler `App\Http\Controllers\Frontend\AnimalController`.
  - Filtrowanie według 3 oficjalnych ras: **Kot Bengalski**, **Kot Brytyjski Krótkowłosy**, **Kot Syjamski**.
  - Zjawiskowy widok siatki z kartami oraz profil kota z danymi rodowodowymi, statystykami i galerią.
  - Modułowy arkusz stylów `resources/css/pages/animals-page.css`.
- **Baza Wiedzy & Poradniki (`/blog`, `/blog/{slug}`)**:
  - Kontroler `App\Http\Controllers\Frontend\BlogController`.
  - Widok katalogu z filtrowaniem po kategoriach (`Zdrowie i Genetyka`, `Wyprawka i Pielęgnacja`, `Odmiany i Rasy`, etc.) i wyszukiwarką.
  - Widok artykułu (`frontend.blog.show`) z redakcyjną typografią (drop-caps, licznik czasu czytania, bio hodowli, sekcja podobnych wpisów).
  - Modułowy arkusz stylów `resources/css/pages/blog-page.css`.
- **Komponent Shimmer Skeleton (`<x-frontend.skeleton>`)**:
  - Reużywalny komponent dla stanów ładowania z animacją shimmer gradient (`resources/css/components/skeleton.css`).
- **Testy Automatyczne (Feature Tests)**:
  - `tests/Feature/Frontend/AnimalTest.php` (katalog, filtrowanie ras, widok profilu, 404 dla nieopublikowanych).
  - `tests/Feature/Frontend/BlogTest.php` (artykuły, wyszukiwarka, filtrowanie po slug, widok redakcyjny).

### Fixed / Changed
- **CSS Architecture & Cleanup**:
  - Sprawdzono plik `style.css` (w liczbie pojedynczej) — brak użycia w projekcie (był pustym plikiem).
  - Plik `resources/css/styles.css` (~250KB) — potwierdzono, że jest to natywny arkusz starego panelu administracyjnego SB Admin / Gazette (ładowany w `backend/shell.blade.php`). Dodano go do `vite.config.js` i zachowano wyłącznie dla backendu.
  - Usunięto style inline z widoków `home.blade.php`, `about.blade.php`, `contact.blade.php` na rzecz dedykowanych modułów CSS.
- **Idempotencyjne Seedery**:
  - Zaktualizowano `RoleSeeder`, `UserSeeder`, `CategorySeeder`, `PostSeeder` przy użyciu `updateOrInsert` – `php artisan db:seed` działa wielokrotnie bez błędów naruszenia unikalności.
- **Pełna Zgodność Testów (41/41 PASS)**:
  - Naprawiono `ProfileUpdateTest` dla modelu `User` z `SoftDeletes` (`$user->fresh()->trashed()`).
  - Naprawiono `DashboardTest` (dodano obsługę roli `user` w Gate `'view-backend-dashboard'` i zunifikowano middleware na tracie `/dashboard`).
