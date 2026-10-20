# RULES.md — The Project Constitution

> **Version:** 2.0 (Streamlined Edition)  
> **Project:** Luxury Cattery Website & CMS (`kotyzmazowieckiejszwajcarii.pl`)  
> **Architecture:** Domain-Oriented Architecture + Action Pattern  
> **Framework:** Laravel 12 + Livewire 4 + Flux UI  
> **Full Unabridged Backup:** [`AIarchive/RULES_BACKUP.md`](file:///c:/wamp64/www/katten/AIarchive/RULES_BACKUP.md)

---

## 1. Project Vision & Identity

1. **Premium Digital Brand, Not a CRUD/Blog:**
   - This project represents a luxury cattery comparable to Apple, Aesop, and Polène.
   - Core values: professionalism, ethics, health transparency, craftsmanship, and trust.
   - Trust always precedes sales. Every UI element and piece of content must reinforce credibility.
2. **Photography-First Experience:**
   - The cats are always the heroes. Layouts remain visually calm.
   - Neutral surface palette: Canvas White (`#ffffff`), Parchment (`#f5f5f7`), Dark Tiles (`#272729`), Void Black (`#000000`).
   - Single interactive brand accent: Action Blue (`#0066cc`). No rainbow colors or heavy gradients.
3. **Frictionless Lead Capture:**
   - Primary conversions: **Phone Call (`+48 514 153 204`)**, **WhatsApp Live Video-Visits**, **Messenger Chat**, and **1-Field Quick Phone Callback**.
   - Zero mention of deposits (*zadatek*) or kitten prices inside the reservation modal. Verbal agreement only.

---

## 2. Architecture & Backend Standards

1. **Domain-Driven Action Pattern (Unbreakable Flow):**
   ```
   HTTP Route / Request
         ↓
   Controller (authorize + validate only — max 10-15 lines)
         ↓
   Domain Action (app/Actions/{Domain}/... — single responsibility)
         ↓
   Eloquent Model & Database Layer
   ```
   * Controllers **must never** contain business logic, multi-table queries, or raw DB transactions.
   * Actions live in `app/Actions/{Domain}/` (e.g. `Animals`, `Media`, `Blog`, `SEO`).
2. **Form Requests & Validation:**
   * Every mutation requires a dedicated `FormRequest` class in `app/Http/Requests/`.
   * Public endpoints must be guarded with rate limiting (`middleware('throttle:5,1')`) and anti-bot honeypots.
3. **Repository-less Architecture:**
   * Rely on rich Eloquent models, local query scopes (`scopePublished`, `scopeAvailable`), and domain actions instead of artificial repository abstractions.

---

## 3. Production Safety & Zero-Overwrite Policy (STRICT)

1. **Live Production Database is Ground Truth:**
   - The production database contains manually curated descriptions, verified birth dates, parent links, and sort orders.
   - **KATEGORYCZNY ZAKAZ**: Never run `db:seed` or `migrate:fresh` on production hosting.
2. **Strictly Insert-Only Migrations:**
   - Any migration adding new cats or default content must be strictly insert-only:
     ```php
     if (! Animal::where('slug', $slug)->exists()) {
         Animal::create([...]);
     }
     ```
   - Migrations must **NEVER** call `Animal::update(...)` or unconditional `updateOrCreate()` on existing records.
3. **Safe Deployment Flow:**
   - Push to remote: `git push origin main`.
   - Trigger browser/webhook sync on hosting: `/deploy-sync` (executes `git pull`, `migrate --force`, `optimize:clear`).

---

## 4. Design System & UI/UX Standards

1. **Typography Scale (Inter):**
   * Default body text runs at **17px** (not 16px) with line-height `1.47` for an editorial reading pace.
   * Display headlines use negative letter-spacing (`-0.28px` to `-0.374px`). Font weight `500` is deliberately absent.
2. **8-Pixel Spacing Rhythm:**
   * Standard section vertical padding: `--sp-4xl` (96px desktop) / `--sp-2xl` (48px mobile).
   * Standard grid gaps: `--sp-xl` (24px desktop) / `--sp-lg` (16px mobile).
   * Internal card padding: standardized to `--sp-2xl` (32px desktop) / `--sp-xl` (24px mobile).
3. **Zero Inline Styles (`style=""`):**
   * Absolute ban on `style=""` attributes in public frontend Blade templates.
   * All styling must use Design Tokens (`resources/css/design-tokens.css`) and BEM CSS classes.
4. **Mobile Ergonomics & Apple HIG:**
   * Minimum touch target: 44×44px.
   * Respect iOS safe areas: `env(safe-area-inset-top)` on headers, `env(safe-area-inset-bottom)` on sticky bars and footers.
   * `MobileStickyBar` must auto-hide when modals or menus are opened (`is-modal-open`).
5. **Microinteractions & Accessibility:**
   * Animations: subtle (150ms–400ms), never flashy. Always respect `prefers-reduced-motion`.
   * High-contrast focus rings: `outline: 2px solid var(--color-primary); outline-offset: 3px;`.

---

## 5. Animal Domain & Felinology Standards

1. **1:1 Square Card Framing:**
   * Kitten and cat cards use `aspect-ratio: 1 / 1`.
   * Photos must be framed using the CMS Cropper.js tool with safe-zone guides to guarantee 100% visibility of ears, face, and paws without clipping.
2. **Standardized Hierarchy (`sort_order`):**
   Automated via `Animal::nextSortOrderFor($status, $breed)`:
   * **1–19:** Kocięta do rezerwacji / dostępne (*Available / Reserved*)
   * **20–29:** Koty w nowych domach (*Sold / Alumni*)
   * **31–39:** Dorosłe koty hodowlane rasy Kot Brytyjski (*Breeding*) [Staś 31, Nel 32, Atos 33]
   * **41–49:** Dorosłe koty hodowlane rasy Kot Syjamski (*Breeding*) [Baltazar 41, Nela 42, Koko 43]
   * **51–59:** Dorosłe koty hodowlane rasy Kot Bengalski (*Breeding*) [Bella 51, Luki 52, Luna 53, Nel 54, Bars 55]
3. **Pickup Window & Health Verification:**
   * Standard felinological pickup: between 12 and 14 weeks from `date_of_birth`.
   * `earliest_pickup_date` (nullable) allows the breeder to set an explicit custom handover date with transparent fallback.
   * Strict truth in copywriting: always specify **2 odrobaczenia** (never 3).

---

## 6. Table UI & Localization

1. **Dual Pagination (Top & Bottom):**
   * Every list and table view (backend CMS and public frontend catalogs) must render pagination controls **both above and below** the table for zero-scroll navigation.
2. **Full Polish Localization:**
   * All user-facing and admin interfaces (navigation, buttons, tooltips, validation messages) must be localized in Polish (`lang/pl.json`).

---

## 7. SEO & Attribution Standards

1. **Honest Programmatic Local SEO:**
   * Satellite landing pages (Warszawa, Łódź, Toruń, Bydgoszcz, Włocławek) must clearly state that the cattery is physically located in Sikórz k. Płocka (woj. mazowieckie).
   * Valid Schema.org `LocalBusiness` JSON-LD with real coordinates, truthful address, and specific driving directions (S7, A1, DK62, DK60).
   * Zero negative attacks on competitor breeders.
2. **UTM Attribution Engine:**
   * Capture `utm_source`, `utm_medium`, `utm_campaign`, `fbclid` on app load into `sessionStorage`.
   * Dynamically append source code to WhatsApp links (e.g. `[Kod: FB_Ads_bengale]`) so the breeder immediately sees the origin of leads on her phone.

---

## 8. Testing & Git Protocol

1. **Session Start Verification:**
   * Run `composer test:smoke` (~5 seconds). Verifies public HTTP 200, dashboard access, contact flow, and security headers.
2. **Pre-Commit Enforcement (MANDATORY):**
   * Enforced automatically via `.git/hooks/pre-commit`:
     `php -d xdebug.mode=off artisan test`
   * Every commit must pass **100% of the 183 automated tests**. Any failure aborts the commit immediately.
3. **Conventional Commits:**
   * Format: `feat(domain): description`, `fix(domain): description`, `perf(domain): description`.
