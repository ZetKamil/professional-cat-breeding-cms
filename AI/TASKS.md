# Sprint Animals Pickup Scheduling — Earliest Pickup Date Override (COMPLETED)

## Status
- **Sprint Pickup Scheduling**: COMPLETED (2026-10-17) — Optional Earliest Pickup Date Override with Automatic 12–14 Weeks Fallback
- **Sprint Animals Media**: COMPLETED (2026-10-17) — Atos Profile & 1:1 Cropped Media Attachment, Responsive WebP Pipeline
- **Sprint Cropper**: COMPLETED (2026-10-16) — Reusable Image Cropper, Live Card Preview, Base64 & File Bag Pipeline
- **Sprint CRO**: COMPLETED (2026-10-15) — Reservation Modal, Mobile Sticky Bar, Attribution & Local SEO
- **Sprint 7**: COMPLETED — Security, SEO, Forms & Final Production QA (2026-08-11)

## Sprint Animals Pickup Scheduling Tasks (2026-10-17)
- [x] `feat(animals): database migration` — Created `2026_10_17_120000_add_earliest_pickup_date_to_animals_table.php` and populated 2026-11-14 (4 weeks) for British kittens
- [x] `feat(animals): pickup calculation engine` — Added `earliestPickupDate()` with fallback in `Animal.php`, updated timeline, badge, and pickup window calculations
- [x] `feat(cms): earliest pickup date in form` — Added date picker input and live calculation info alert in `backend/animals/partials/form.blade.php`
- [x] `feat(backend): requests & actions` — Added validation in `StoreAnimalRequest` and `UpdateAnimalRequest`, persisted in `CreateAnimalAction` and `UpdateAnimalAction`
- [x] `feat(frontend): show page pickup label` — Displayed custom pickup date/window in `frontend/animals/show.blade.php`
- [x] `test(frontend): earliest pickup date test suite` — Verified via `EarliestPickupDateTest.php` (5 tests, 41 assertions, 100% PASS)

# Sprint Animals Media Expansion — Atos Profile & Media Integration (COMPLETED)

# Sprint Media Cropping — Reusable Profile Framing & Cropper Module (COMPLETED)

## Sprint Media Cropping Tasks (2026-10-16)
- [x] `feat(cms): blade image cropper` — Created `<x-backend.image-cropper />` with Cropper.js, 1:1/4:3/16:9/free ratios, live dual card/avatar preview and global JS API
- [x] `feat(cms): animal form integration` — Added `[ ✂ Dopasuj kadr obecnego zdjęcia ]` and automatic file selection interception with live card preview
- [x] `feat(backend): cropped image pipeline` — Added base64 decoding in `StoreAnimalRequest` and `UpdateAnimalRequest` with automatic `UploadedFile` file bag binding
- [x] `feat(media): featured image preservation` — Updated `MediaService::replace()` to ensure `is_featured = true` for primary animal photo
- [x] `fix(ui): natural 1:1 card framing` — Updated `animals.css` to `object-position: var(--animal-card-img-position, center center)`
- [x] `fix(data): daisy portrait crop` — Re-cropped vertical 1536x2048 photo to exact 1:1 square (1536x1536) with full ears and face preservation
- [x] `test(backend): image crop test suite` — Verified with `AnimalImageCropTest.php` (2 tests, 10 assertions, 100% PASS)

# Sprint CRO — Sales Funnel Bottom & Live Video-Visits (COMPLETED)
- **Sprint 6.8**: COMPLETED
- **Sprint 6.5**: COMPLETED
- **Sprint 6**: IN PROGRESS
- **Sprint 5**: COMPLETED
- **Sprint 4**: COMPLETED

## Sprint CRO Tasks (2026-10-15)
- [x] `feat(cro): reservation modal` — Added `<x-frontend.reservation-modal />` with WhatsApp Video, Phone, Messenger & 1-field Quick Callback
- [x] `feat(cro): quick callback api` — Created `CallbackController.php` with POST `/api/quick-callback`, throttling and bot honeypot
- [x] `feat(cro): mobile sticky bar` — Added `<x-frontend.mobile-sticky-bar />` for thumb-zone WhatsApp & call access on mobile
- [x] `feat(analytics): utm attribution` — Added UTM & fbclid capture into sessionStorage/localStorage with source code in WhatsApp
- [x] `feat(analytics): meta pixel & consent` — Added Meta Pixel integration with Consent Mode v2 support in `shell.blade.php` and cookie consent
- [x] `feat(cro): mobile homepage refactor` — Reordered kittens section above breed showcase on mobile devices via CSS flexbox
- [x] `feat(cro): early reservation badges` — Implemented automatic `< 12 weeks` calculation for "Wczesna rezerwacja • za X tyg."
- [x] `feat(cro): breed matcher quiz` — Created interactive 3-step quiz at `/dobierz-rase` with live matching and video-visit triggers
- [x] `feat(seo): programmatic local seo` — Created SSR landing pages for Warszawa, Łódź, Toruń, Bydgoszcz with Schema.org LocalBusiness
- [x] `test(cro): automated test suite` — Verified all routes, validation and security via `ReservationAndCroTest` (6 passed, 25 assertions)


## Sprint 7 Tasks
- [x] `fix(mobile): safe-area support` — Added `env(safe-area-inset-top)` to `.site-header`, `env(safe-area-inset-bottom)` to mobile menu footer and `.footer-bottom`
- [x] `fix(mobile): hero padding-top` — Reduced padding so headline is in first fold on 375px
- [x] `fix(mobile): hero cta buttons` — Full-width stretch on mobile
- [x] `fix(mobile): overflow-x global` — `overflow-x: hidden` on frontend-shell + `overscroll-behavior: none` on menu-open
- [x] `fix(mobile): typography tokens` — `--text-lead-airy-size` and `--text-tagline-size` scaled down on mobile
- [x] `fix(mobile): footer social touch target` — Increased to 44×44px (Apple HIG)
- [x] `fix(mobile): newsletter form` — Vertical stack on tiny screens (<480px)
- [x] `fix(mobile): hamburger lines` — Thicker lines (2px), better visual weight
- [x] `fix(mobile): homepage manifesto, collections, kodeks, empty box` — Compact layout on mobile
- [x] `fix(mobile): about gallery grid` — Fixed row heights to auto on mobile
- [x] `fix(mobile): animals profile padding, thumbs, spec cards` — Compact on mobile
- [x] `fix(mobile): blog article cover hero` — Height 380px on mobile, title clamp, author bar vertical
- [x] `fix(mobile): contact methods mid-breakpoint` — 2-col at 640-834px, full-width submit, smaller map
- [x] `build: npm run build` — ✓ Clean build
- [x] `test: php artisan test` — ✓ 48 passed, 153 assertions

## Sprint 7 — Production Release & Audit
- [x] `audit: production` — Completed a full 15-stage production audit covering security, SEO, and performance without redesigns. Found 4 critical, 6 important, and 3 low-priority issues.
- [x] `fix(sec): APP_DEBUG and env setup` — Secured `.env.example` by setting `APP_ENV=production` and `APP_DEBUG=false` for production templates.
- [x] `fix(seo): robots.txt and sitemap` — Updated `robots.txt` with correct production URL and created a dynamic XML sitemap covering all pages, posts, and animals.
- [x] `fix(sec): diagnostic routes` — Locked down `/check-media` and `/fix-storage` routes with `['auth', 'verified', 'active']` middleware to prevent unauthorized server access.
- [x] `test: php artisan test` — ✓ 49 passed, 157 assertions after critical production fixes.

---

# Sprint 5 — Luxury Brand Digital Polish (Remaining Tasks)

## 1. Final Call to Action & Footer Experience
- [x] `feat(ui): luxury cta experience` — Refined global CTA component (`cta.blade.php`, `cta.css`): luxury typography, stronger visual hierarchy, calm parchment background, premium spacing, beautiful button interaction, editorial composition, elegant responsiveness, accessibility.
- [x] `feat(ui): footer experience` — Improved footer (`footer.blade.php`, `footer.css`): premium typography, better spacing, newsletter presentation, trust indicators, contact hierarchy, social links polish, Apple-level visual rhythm.

## 2. Universal Quality, Accessibility & Performance Polish
- [x] `refactor(ui): universal microinteractions` — Audited interactive elements: Buttons, Links, Cards, Navigation, Forms, Inputs, Badges, Pagination, Tables, Dropdowns; refined Hover, Focus, Active, Disabled, Loading states so nothing feels like Bootstrap.
- [x] `test(ui): accessibility audit` — Verified Keyboard navigation, Focus rings, ARIA labels, Reduced Motion, Contrast, Screen reader compatibility, Semantic HTML.
- [x] `perf(ui): performance polish` — Reduced CSS duplication, reduced DOM complexity, prevented CLS, improved image loading, optimized transitions, ensured Lighthouse-ready frontend.

---

## Current Status
- **Sprint 4**: COMPLETED
- **Sprint 5**: COMPLETED
- **Sprint 6**: IN PROGRESS — Premium CMS Experience (Media Library COMPLETED).
- **Sprint 6.5**: COMPLETED — Creative Director Homepage Storytelling Redesign.
- **Sprint 6.8**: COMPLETED — Luxury UI/UX Final Polish & Privacy Compliance.

---

# Sprint 6.8 — Luxury UI/UX Final Polish & Privacy Compliance (COMPLETED)
- [x] `style(ui): apple/aesop pixel-perfect pass` — Softened card borders to `rgba(0,0,0,0.04)`, equalized micro-labels typography to `12px` globally, refined glassmorphism blur on badges. 
- [x] `fix(ui): editorial layout balancing` — Fixed the "Nasza filozofia" layout issue by implementing a robust `1fr 1fr` grid with a strict `height: 550px` image wrapper, perfectly balancing text and imagery without whitespace gaps or text offsets.
- [x] `feat(legal): gdpr privacy policy` — Created dedicated Privacy Policy (`privacy.blade.php`) and Terms placeholder pages with a clean UI. Updated footer links and added GDPR-compliant double-opt-in disclaimer to the newsletter form. Removed redundant trust badges from footer for extreme minimalism.

---
- **Sprint 4**: COMPLETED
- **Sprint 5**: COMPLETED (100% finished — Cinematic Hero, Animal Cards, Trust Section, Testimonials, About & Blog Previews, Luxury CTA, Footer Experience, Microinteractions, Accessibility & CLS/Performance Polish).
- **Sprint 6**: IN PROGRESS — Premium CMS Experience (Media Library COMPLETED).
- **Sprint 6.5**: COMPLETED — Creative Director Homepage Storytelling Redesign (€20,000 Apple/Aesop/Polène Standard).

---

# Sprint 6.5 — Creative Director Homepage Storytelling Redesign (COMPLETED)
- [x] `feat(ui): redesign homepage storytelling` — Completely redesigned homepage storytelling (`resources/views/frontend/home.blade.php`, `resources/css/pages/home-page.css`) to meet €20,000 luxury digital brand standard (Apple / Aesop / Polène / Porsche / Rolls Royce / Bang & Olufsen):
  - **Cinematic Asymmetric Hero**: Added monospace masthead bar (`[ 01 — ETHICAL BREEDING ]`), asymmetric typography (`Czystość Rasy. Spokój Genetyki. Rodowód FIFe / FPL.`), and overlapping glass photography frame with floating verified lineage seal (`100% HCM / PKD n/n`).
  - **Breeding Philosophy Double-Spread Manifesto**: Added editorial magazine double-spread manifesto statement (`"Nie hodujemy kotów dla mas..."`), 3 numbered ethical tenet rows, and editorial portrait photo with 15+ years statistics card.
  - **Immersive Breed Showcase**: Transformed breed showcase strip into an immersive 3-collection gallery (`Collection 01 / Bengal`, `Collection 02 / British Shorthair`, `Collection 03 / Siamese`) with characteristics tags and quote descriptions.
  - **Curated Kittens & Selection Assurance**: Styled available kittens as `Selekcja 2026` with breeding standard guarantee strip and editorial empty state.
  - **Architectural Health Matrix (`#kodeks`)**: Redesigned trust builders into a 4-pillar architectural matrix (`01 / GENETICS`, `02 / VETERINARY`, `03 / FEDERATION`, `04 / BEHAVIOR`).
  - **Concierge Adoption Timeline (`#adopcja-krok-po-kroku`)**: Elevated the adoption journey into a 3-step luxury concierge timeline.
  - **Verified Patron Stories (`#opinie`)**: Replaced standard star cards with editorial quote monoliths and `FIFe Verified` badges.
  - **Reading Room (`#blog`) & Concierge CTA**: Styled blog preview as a reading room and concluded with an editorial parchment CTA monolith.
  - Verified 100% test compatibility (`48 passed`, 153 assertions) and clean production bundle (`npm run build`).
- [x] `style(home): editorial finish pass to apple/aesop/polène standards` — Performed a pixel-perfect visual finish pass on the homepage (`resources/views/frontend/home.blade.php`, `resources/css/pages/home-page.css`):
  - **8-Pixel Rhythm Normalization**: Normalized vertical section padding (`py-20 lg:py-24`, `--sp-4xl` desktop / `--sp-2xl` mobile), section header bottom spacing (`--sp-2xl`), and grid gaps (`--sp-xl` desktop / `--sp-lg` mobile).
  - **Card Proportions & Equal Heights**: Updated all cards (`.collection-card`, `.kodeks-column`, `.patron-monolith`, `.adoption-journey-pillar`) to use flex column equal heights with standardized internal padding (`--sp-2xl` / `32px` desktop, `--sp-xl` / `24px` mobile).
  - **Noise Reduction & Apple Hover Elevation**: Removed unnecessary borders, eliminated redundant contact CTA button in adoption timeline, and replaced heavy shadows with subtle Apple/Aesop hover elevation (`0 16px 32px -12px rgba(0, 0, 0, 0.06)`).
  - **Typography & Baseline Alignment**: Enforced `text-wrap: balance;` on all section headlines, standardized paragraph line-heights (`1.65`), and unified all uppercase monospace eyebrows (`11px`, `600`, `#d1ab58`, `0.12em`).

---

# Sprint 6 — Premium Frontend Experience (~90% Production Quality)

## 1. Homepage Polish (COMPLETED)
- [x] `feat(ui): homepage apple/aesop luxury polish` — Refined Hero section with editorial eyebrow (`Etyczna Hodowla Kotów Rasowych · FIFe / FPL`) and trust badges bar; added minimalist Breed Showcase Strip (`.breed-showcase-strip`); added Aesop-inspired 3-Step Adoption Journey Preview (`#adopcja-krok-po-kroku`); polished About Preview, Testimonials, Available Kittens, and Why Choose Us; added editorial empty state for Blog preview; prevented CLS on all images; created `home-page.css` and comprehensive Feature tests (`HomepageTest.php`).

## 2. Animal Detail Polish (COMPLETED)
- [x] `feat(ui): premium animal detail experience` — Brought Animal Profile page (`resources/views/frontend/animals/show.blade.php`, `animals-page.css`) to ~95% Apple/Aesop luxury quality: added editorial breadcrumbs, pedigree & gender badges, birth date formatting, interactive thumbnail gallery with keyboard access & CLS prevention (`1000x750`), airy 3-column editorial spec cards (`.animal-spec-card`), enhanced health panel & pedigree lineage cards, 65ch reading measure, related animals polish, and final parchment CTA block. Verified 48/48 tests passing.

## 3. Animal Catalog Polish (COMPLETED)
- [x] `feat(ui): premium animal catalog experience` — Brought Animal Catalog page (`resources/views/frontend/animals/index.blade.php`, `animals-page.css`) to ~90% production quality: stripped all inline `<style>` attributes, replaced with clean BEM/design system CSS classes (`.animals-catalog-hero__eyebrow`, `.animals-catalog-hero__title`, `.animals-catalog-hero__lead`, `.animals-filter-clear`, `.animals-grid__empty`, `.empty-state__action`, `.animals-catalog__pagination`), added `.section--no-pt` and `.section--no-pb` modifier classes to `design-tokens.css`.

---

# Sprint 6.7 — Production Visual QA & Frontend Audit (COMPLETED)
- [x] `fix(ui): about page cls prevention` — Added explicit image dimensions (`width="1000" height="1250"`, gallery explicit widths/heights, `decoding="async"`, `loading="lazy"`) to all imagery in `about.blade.php`.
- [x] `fix(ui): blog & skeleton spacing tokens` — Fixed critical missing `--space-*` variables across `blog-page.css` and `skeleton.css` by mapping `--space-1` to `--space-24` in `design-tokens.css` (`:root`), restoring proper padding, margin, and grid gap across all blog pages and loading skeletons.
- [x] `fix(ui): error pages contrast watermark` — Fixed invisible error code text (`404` / `500`) in `errors.css` where `--color-canvas-parchment` was unreadable on light backgrounds; replaced with an editorial ink watermark (`color: var(--color-ink); opacity: 0.12;`).
- [x] `test(ui): zero inline style verification` — Verified that ZERO inline `style=""` attributes remain in any `resources/views/frontend/` Blade template.

## 1. Visual Rhythm
- [x] Audit every page for spacing consistency
- [x] Normalize vertical rhythm
- [x] Remove visual noise
- [x] Improve section transitions
- [x] Improve content width hierarchy

---

## 2. Typography
- [x] Refine heading scale
- [x] Improve paragraph measure
- [x] Improve editorial spacing
- [x] Improve lists
- [x] Improve quote styling

---

## 8. Final Audit
- [x] Lighthouse >95
- [x] Accessibility
- [x] CLS audit
- [x] LCP audit
- [x] CSS cleanup