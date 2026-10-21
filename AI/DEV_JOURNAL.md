# DEV_JOURNAL.md

> Project: Luxury Cattery Website & CMS
> Engineering Journal & Architectural Record

## 2026-10-21 — Mobile Hero Section Hotfix (Above the Fold Optimization) (`fix(hero)`, `feat(ui)`, `test(frontend)`)

### Context
On mobile viewports (`< 768px`), the Homepage Hero section was rendering as a massive black wall of text where the `<h1>` spanned 7 lines, repeated the phrase ("Domowa hodowla kotów...") three times across eyebrow, title, and lead, obscured the cat photo below the fold, and pushed all action buttons and available kittens below the viewport.

### Technical & Architectural Resolutions
1. **Elevating Cat Photography (Bars) to the First Fold**:
   - In `resources/css/pages/home-page.css`, applied `.hero-split__right { order: -1; width: 100%; margin: 0; }` under `@media (max-width: 767px)`.
   - Set `.hero-split__frame` to a clean height of 230px with `object-fit: cover; object-position: center 25%;`.
   - Repositioned `.hero-split__seal` (SHiOZ ZOOLANDIA) as a compact, floating glass badge (`bottom: 10px; left: 10px;`) directly anchored inside the cat photo corner, taking zero additional vertical space.
2. **Eliminating Redundant Copy & Shrinking Typography on Mobile (`max-md`)**:
   - Shortened mobile eyebrow to `SHiOZ ZOOLANDIA • MAZOWIECKA SZWAJCARIA` (11px, letter-spacing 0.08em).
   - Reduced mobile H1 headline to 26px (`leading-tight`, max 3 lines) and condensed copy to: `Koty Bengalskie, Brytyjskie i Syjamskie z Rodowodem.` to eliminate redundancy with the eyebrow.
   - Constrained mobile subtitle lead to max 2 lines with `-webkit-line-clamp: 2` (`Domowa socjalizacja, badania genetyczne rodziców (HCM/PKD) i pełne wsparcie.`, 14px, `#d6d3d1`).
   - Desktop markup and styles remain 100% intact via dedicated desktop/mobile conditional spans and BEM classes (`.hero-split__eyebrow--mobile`, `.hero-split__title--mobile`, etc.), completely removing obsolete inline `style=""` attributes.
3. **Above-the-Fold Compact CTAs & Available Kittens Peek**:
   - Deployed two compact touch-friendly pill buttons on mobile:
     - Primary: `🐾 Zobacz dostępne kocięta` (`href="#nasze-koty"`)
     - Secondary: `✨ Quiz: Dobierz rasę (45s)` (`href="{{ route('quiz') }}"`)
   - Reduced mobile hero vertical padding (`padding-top: calc(var(--nav-height, 64px) + 8px); padding-bottom: 16px;`) and `.hero-split__left` gap from 24px to 6px.
   - Reduced `.home-section--nasze-koty` mobile padding-top to 24px, ensuring that both action buttons fit comfortably above the mobile sticky bar and the top edge of `Dostępne Kocięta i Mioty` peeks prominently into the viewport to encourage scrolling.
4. **Verification**:
   - Automated testing: added `test_homepage_renders_mobile_hero_optimizations` in `tests/Feature/Frontend/HomepageTest.php`. Full test suite passes 100% (184 tests, 1479 assertions).
   - Production Vite build: compiled assets (`public/build/assets/app-C43eghny.css`).
   - Visual QA: verified via `browser_subagent` at 375x667 mobile resolution.

---

## 2026-10-20 — Comprehensive Test Suite Audit, De-duplication & Quality Hardening (`test(audit)`, `refactor(tests)`)

### Context
User requested a rigorous audit of all 183 tests after noticing that several test files contained flaws, stale logic, or inaccurate assumptions. A comprehensive audit across all 35 test files revealed boilerplate tests testing nothing, missing critical backend routes in smoke tests, hardcoded database IDs, duplicate assertion suites, and genealogical/role conflation between kittens and adult breeding studs.

### Technical & Architectural Resolutions
1. **Elimination of Fake/Boilerplate Tests**:
   - Deleted `tests/Unit/ExampleTest.php` (`expect(true)->toBeTrue()`) and `tests/Feature/ExampleTest.php` (`$response->assertOk()`).
2. **Production Smoke Test Hardening (`ProductionSmokeTest.php`)**:
   - Expanded backend route coverage to include `/backend/animals` (the central cattery management module), `/backend/posts/ai-studio` (AI content generation wizard), and `/backend/blog-cta` (global article offer).
3. **Genealogy & Adult Stud Disambiguation (`BritishCatsFatherAssignmentTest.php`)**:
   - Differentiated British litter kittens (`ada`, `alma`, `atena`) from adult breeding stud Atos (`reproduktor`, BLH a 33), aligning the test semantics with real cattery biology.
4. **Hardcoded Role ID Remediation (`MediaLibraryTest.php`)**:
   - Replaced brittle `'role_id' => 1` across all 4 test methods with dynamic role resolution `Role::firstOrCreate(['name' => 'admin'])` in `beforeEach()`.
5. **DOM-Coupled Quiz Validation & De-duplication (`ReservationAndCroTest.php`)**:
   - Removed stale redundant copy of local SEO city page tests (covered fully by `LocalSeoTest.php`).
   - Enhanced `test_quiz_scoring_algorithm_determines_unique_winner_across_all_thirty_six_paths` to actively fetch the quiz page and assert that all 10 option keys exist in the rendered DOM.
6. **Integrity & Velocity**:
   - 180 clean, genuine tests passing 100% (1457 assertions).
   - Fast smoke suite executes in 5.4s.

---

## 2026-10-20 — Cookie Consent Banner UI/UX Overhaul & Consent Mode v2 Alignment (`feat(legal)`, `feat(ui)`, `test(frontend)`)

### Context
To legally maximize cookie and analytics tracking opt-in rates, the cookie banner was redesigned. The direct "Odrzuć" button was removed from the first layer, replacing it with a prominent brand Primary CTA ("Akceptuj wszystkie") and a subtle Secondary CTA ("Tylko niezbędne"). The banner copy was rewritten to be friendly and user-centric, highlighting video performance and domestic cattery support.

### Technical & Architectural Resolutions
1. **Consent Mode v2 & Pixel Contract**:
   - `window.acceptCookieConsent()` updates Google Consent Mode v2 (`analytics_storage: 'granted'`, `ad_storage: 'granted'`, `ad_user_data: 'granted'`, `ad_personalization: 'granted'`) and executes `fbq('consent', 'grant')`.
   - `window.declineCookieConsent()` updates Consent Mode v2 to `denied` for storage and revokes Pixel consent via `fbq('consent', 'revoke')`.
2. **Mobile Sticky Bar Conflict Elimination**:
   - In mobile viewports (`max-width: 680px`), the bottom `mobile-sticky-bar` previously occupied the same thumb zone.
   - Introduced `body.cookie-banner-active .mobile-sticky-bar { display: none !important; }`. The sticky bar cleanly yields while the banner is displayed, and is restored when dismissed.
   - Actions order reversed on mobile (`flex-direction: column-reverse`) to position the primary "Akceptuj wszystkie" button at the top thumb position.
3. **Automated Testing & Browser Verification**:
   - Updated assertions in `AnalyticsAndAttributionTest.php` to match new copy. All 183 automated tests (1493 assertions) pass 100%.
   - Validated in browser subagent on desktop (1280x800) and mobile (375x667).

---

## 2026-10-20 — Test Suite Acceleration Engine & AI Knowledge Base Restructuring (`perf(tests)`, `refactor(ai)`, `feat(git)`)

### Context
Initial analysis and test runs were taking ~150 seconds per session due to active Xdebug v3.4.0beta1 in WAMP PHP CLI, and the AI context folder contained 29 files (over 300 KB) with historical logs and completed sprint plans. User requested:
1. Shortening start verification while guaranteeing 100% full test suite execution before commits.
2. Archiving completed, obsolete, or duplicate documentation into an adjacent `AIarchive/` folder.

### Technical & Architectural Resolutions
1. **Xdebug CLI Bypass**:
   - Running test suites with `php -d xdebug.mode=off artisan test` reduced execution time from 150.39s to 43.42s (~3.5x faster).
2. **Dual Testing Strategy (`composer.json`)**:
   - `composer test:smoke`: Executes `ProductionSmokeTest` verifying HTTP 200 on public pages, admin dashboard access, contact redirects, security headers, and canonical redirects in **5.28s**.
   - `composer test`: Enforces Pint linting + full test suite (183 tests, 1493 assertions) with xdebug disabled in **~43s**.
3. **Pre-commit Hook Enforcement (`.git/hooks/pre-commit`)**:
   - Configured `.git/hooks/pre-commit` to strictly run `php -d xdebug.mode=off artisan test`. Any failing test aborts the commit automatically, guaranteeing zero broken commits in git history.
4. **AI Knowledge Base Streamlining**:
   - Created `AIarchive/` adjacent to `AI/`.
   - Moved 13 obsolete / completed historical files (`COMPONENTS.md`, `getdesign.md`, `MVP.md`, `FRONTEND_PLAN.md`, `MASTER_PLAN.md`, `SPRINTS.md`, `ROADMAP.md`, `SESSION_CRO_2026-10-15.md`, `Plannadzis_CRO_COMPLETED.md`, and full historical sprint logs).
   - Preserved active, relevant files in `AI/` for fast orientation and zero context bloat.

---

## 2026-10-17 — Pickup Date Simplification & Full Cat Independence (`refactor(animals)`, `refactor(cms)`, `fix(db)`)

### Context
Breeder reported confusion after observing that all British kittens had received the same pickup date simultaneously, thinking they were linked by breed in the application logic. The breeder asked why this happened and requested a much simpler, intuitive system where each cat is 100% independent without unnecessary complexity.

### Root Cause & Resolution
1. **Root Cause**: In migration `2026_10_17_120000`, a bulk SQL update was executed for `ada`, `alma`, and `atena` to prefill `2026-11-14`. This made all three cats share the same initial date, creating the false impression of an implicit coupling.
2. **Database Reset**: Created `2026_10_17_130000_reset_british_kittens_pickup_dates.php` clearing the bulk dates back to `NULL`. Each cat now starts in a pure state governed solely by its own `date_of_birth + 12 weeks`, unless the breeder enters an explicit date for that individual cat in CMS.
3. **Form Simplification (`form.blade.php`)**: Removed the verbose alert box and mathematical window labels. Created two clean, side-by-side inputs:
   - `Data urodzenia` (shows age and exact standard 12-week date: e.g. 02.12.2026).
   - `Indywidualna data odbioru (opcjonalnie)` (allows setting a custom date for that cat only).
4. **Clean Presentation**: Simplified profile page spec cards from technical ranges like `14.11.2026 – 28.11.2026` to human-friendly `Od 14.11.2026 (za ok. 4 tyg.)`.

---

## 2026-10-17 — Earliest Pickup Date Override with Automatic Fallback (`feat(animals)`, `feat(cms)`, `test(frontend)`)

### Context
Breeder requested the ability to specify an explicit earliest pickup date (`earliest_pickup_date`) for cats/kittens in the CMS (e.g. British kittens Ada, Alma, and Atena are scheduled to be ready in exactly 4 weeks from today, 2026-10-17 -> 2026-11-14). If this explicit date is not provided, the system must transparently fall back to the standard felinology mathematics based on kitten age and vaccination schedule (12–14 weeks from `date_of_birth`).

### Technical & Architectural Decisions
1. **Database Schema (`2026_10_17_120000_add_earliest_pickup_date_to_animals_table.php`)**:
   - Added nullable `earliest_pickup_date` column right after `date_of_birth` on `animals`.
   - Populated `2026-11-14` for British kittens (`ada`, `alma`, `atena`) to reflect the breeder's 4-week timeline.
2. **Model Architecture & Fallback Engine (`Animal.php`)**:
   - Introduced `earliestPickupDate(): ?Carbon` helper using `Carbon::parse()` for seamless `Carbon` / `CarbonImmutable` / `DateTimeInterface` interoperability.
   - Refactored `pickupWindow()`, `weeksUntilPickupWindow()`, `isEarlyReservation()`, `isReadyNow()`, `isInPickupWindow()`, `pickupBadgeLabel()`, and `pickupTimelineLabel()` to respect `earliestPickupDate()` with zero regression on automatic `date_of_birth + 12 weeks` fallback.
3. **Admin CMS Integration**:
   - Added `earliest_pickup_date` date picker input alongside `date_of_birth` in `backend/animals/partials/form.blade.php`.
   - Added a live informative preview alert displaying current pickup window, active status badge, countdown timeline, and whether calculation is manual ("Własna data") or automated ("Automatycznie 12–14 tyg.").
   - Updated `StoreAnimalRequest`, `UpdateAnimalRequest`, `CreateAnimalAction`, and `UpdateAnimalAction` to ensure seamless validation and persistence.
4. **Automated Testing & QA**:
   - Created `tests/Feature/Frontend/EarliestPickupDateTest.php` testing override, boundary conditions, ready-now transition, null fallback, public show page rendering, and CMS updating (5 passed, 41 assertions).
   - Executed full project test suite: 180 passed, 1464 assertions (100% PASS).
   - Formatted using Laravel Pint.

---

## 2026-10-17 — Atos Profile Media Integration & 1:1 Framing (`feat(animals)`, `feat(media)`, `test(frontend)`)

### Context
User provided photos for British Longhair stud cat Atos (`image/Brytyjczyki/Kociaki/Atos/`) and requested adding / completing his profile. Atos was previously registered in the database as a future breeding stud without media (pending standalone photography).

### Technical & Creative Implementation
1. **Photo Curation & Aspect Ratio Optimization**:
   - `profilowe.jfif` (1536x2048, vertical portrait): Using GD image processing, cropped from `(0,0)` to a crisp 1:1 square (`1536x1536`, 92% JPEG quality, saved as `parent_atos_1.jpg`), ensuring 100% preservation of ears, head, paws, and gaze in cards and profile avatars without clipping.
   - `9286a117-c510-41a9-aa87-81fc0c49e1aa.jfif`: Registered as `parent_atos_2.jpg` in the gallery (`is_featured = false`, `sort_order = 1`).
2. **Responsive WebP Generation**:
   - Generated WebP variants (400px, 800px, 1200px) via `images:optimize`.
3. **Safe Idempotent Migration (`2026_10_17_110000_attach_atos_photos.php`)**:
   - Strictly respects Zero-Overwrite Policy: attaches media to `atos` (`mediable_type = Animal::class`), populates descriptive metadata, links to mother Nel, and aligns `sort_order = 33`.
4. **Automated Testing & QA**:
   - Created `tests/Feature/Frontend/AtosProfileTest.php` (100% PASS, 16 assertions).

---

## 2026-10-16 — Reusable Image Cropper Module & Profile Photo Framing (`feat(cms)`, `feat(ui)`, `test(backend)`)

### Context
User reported that portrait photos (3:4, e.g. 1536x2048) on kitten cards (such as Daisy) had their ears and heads clipped due to fixed CSS `aspect-ratio: 1/1` and lack of a cropping tool in the CMS.

### Technical & Creative Implementation
1. **Reusable Blade Cropper Component (`resources/views/components/backend/image-cropper.blade.php`)**:
   - Modal component powered by Cropper.js with `window.openImageCropper(...)`.
   - Predefined aspect ratios: 1:1 Square (Card/Profile), 4:3 Portrait, 16:9 Landscape, Freeform.
   - Live dual preview: Card preview + Avatar preview with real-time dimensions.
2. **Form Integration**:
   - Added `[ ✂ Dopasuj kadr obecnego zdjęcia ]` in animal form with live card preview and automatic file interception.
3. **Backend & Validation**:
   - Base64 payload decoding into `UploadedFile` file bag binding in `StoreAnimalRequest` and `UpdateAnimalRequest`.
4. **Daisy Portrait Crop**:
   - Cropped 1536x2048 portrait into exact 1:1 square (1536x1536) preserving full face and ears.
5. **Automated Testing**:
   - `tests/Feature/Backend/AnimalImageCropTest.php` (100% PASS, 10 assertions).

---

## 2026-10-15 — Sprint CRO: Mobile-First Funnel, Reservation Modal & Local SEO (`feat(cro)`, `feat(analytics)`, `test(cro)`)

### Context
90% of user traffic arrives from mobile devices (Facebook In-App Browser). The old site directed traffic to `/kontakt` (email), resulting in zero leads. Rebuilt the bottom of the funnel around phone, WhatsApp live video visits, and frictionless callback.

### Technical & CRO Implementation
1. **Attribution Engine & Funnel Analytics (`resources/js/analytics.js`)**:
   - Captures `utm_source`, `utm_medium`, `utm_campaign`, `fbclid` into `sessionStorage` with 7-day `localStorage` fallback.
   - Dynamically appends attribution code (e.g. `[Kod: FB_Ads_bengale]`) to WhatsApp pre-filled messages.
   - Dual event tracking for GA4 (`window.gtag`) and Meta Pixel (`window.fbq`).
2. **Mobile Sticky Bar (`<x-frontend.mobile-sticky-bar />`)**:
   - Fixed bottom action bar in mobile thumb zone with direct WhatsApp Video-Visit and Call CTA.
3. **Reservation & Video-Visit Modal (`<x-frontend.reservation-modal />`)**:
   - High-converting modal with WhatsApp, Messenger, direct phone, and 1-field quick callback (`/api/quick-callback`). Strictly zero mention of deposit (zadatek) or price in UI.
4. **Interactive Breed Matcher Quiz (`/dobierz-rase`)**:
   - 3-step questionnaire calculating behavioral fit across Bengal, British, and Siamese cats with real-time database availability check.
5. **Programmatic Local SEO**:
   - SSR landing pages for Warszawa, Łódź, Toruń, Bydgoszcz, Włocławek with honest location disclosures and Schema.org `LocalBusiness`.
6. **Automated Testing**:
   - `tests/Feature/Frontend/ReservationAndCroTest.php` (6 passed, 25 assertions).

---

## 2026-10-14 — Production Database Alignment & Dual Table Pagination

### Context
Synchronized codebase with live production database state (21 animals across 3 pages) without touching, updating, or overwriting manual edits made directly on the production server.

### Technical Implementation
1. **Zero-Overwrite Insert Migration (`2026_10_14_140000_add_bars_british_and_siamese_cats.php`)**:
   - Fully insert-only migration: existing production records matched safely by slug and left 100% untouched.
   - Bengal breeding sequence: Bella (51), Luki (52), Luna (53), Bengal Nel (54) -> new stud Bars assigned #55.
   - British breeding sequence: Staś (31), British Nel (32) -> new stud Atos assigned #33.
   - Siamese kittens (Dafne 7, Daisy 8, Dakota 9, Diana 10) and British kittens (Ada 12, Alma 13, Atena 14) assigned into open slots (1-19).
2. **Dual Pagination (Top & Bottom)**:
   - Added pagination controls both above and below tables across all backend views and frontend catalogs.
3. **Automated `sort_order` Assignment**:
   - Refined `Animal::nextSortOrderFor()` to auto-increment within standard breeding pools (British 31+, Siamese 41+, Bengals 51+), sold pool (20-29), and kittens (1-19).

---

## Historical Archives
Detailed logs for Sprints 0 through 7 (July–August 2026) are preserved in:
- [`AIarchive/DEV_JOURNAL_SPRINTS_0_TO_7.md`](file:///c:/wamp64/www/katten/AIarchive/DEV_JOURNAL_SPRINTS_0_TO_7.md)
