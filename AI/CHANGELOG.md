# AI/CHANGELOG.md

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
