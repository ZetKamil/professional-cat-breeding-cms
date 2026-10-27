# TASKS.md

> Project: Luxury Cattery Website & CMS
> Status: Active Maintenance & Expansion
> Single Source of Truth for Active Work

---

## Current Status Overview
- **Completed Sprints Archive**: Preserved in `AIarchive/TASKS_COMPLETED_SPRINTS.md` (Sprints 0–7, CRO, Cropper, Atos Profile, Pickup Scheduling).
- **Recent Releases**:
  - `Sprint Animals Pickup Scheduling` (2026-10-17) — Earliest pickup date override with 12–14 weeks fallback.
  - `Sprint Animals Media` (2026-10-17) — Atos profile, 1:1 cropped media attachment.
  - `Sprint Cropper` (2026-10-16) — Reusable Cropper.js with 1:1 safe-zone preview.
  - `Sprint CRO` (2026-10-15) — Reservation modal, mobile sticky bar, WhatsApp UTM attribution, local SEO.
- **Test Suite Status**: 193 tests passed (1555 assertions), 100% PASS in ~45s, smoke test in ~5s.

---

## Active & Recent Maintenance Tasks (2026-10-27)
- [x] `feat(pricing): quiet luxury 'kociak na kolanka' presentation, mobile quiz clamp removal, cage-free phrasing cleanup & blog lead fix` — Four-part UX and content refinement:
  1. **Ceny kotów („Kociak w opcji na kolanka”):** Zastąpiono etykiety „Cena kociaka” profesjonalną terminologią felinologiczną: `KOCIAK W OPCJI „NA KOLANKA”` + `2 700 zł — pupil domowy z rodowodem` w boksie profilowym, `Na kolanka: {kwota}` na kartach kotów i w wynikach quizu, oraz `Kwota w opcji „na kolanka” (zł)` w formularzu CMS.
  2. **Quiz Mobile Unclamping:** Usunięto regułę `-webkit-line-clamp: 3` z `.quiz-result__desc` w `quiz.css`, umożliwiając pełne, nieobcięte wyświetlanie opisu dopasowania behawioralnego rasy.
  3. **Usunięcie słów o klatkach („bez klatek” / „klatek”):** Oczyszczono 7 widoków Blade i test z zachowaniem naturalnego, ciepłego sensu zdań (*„Chów w sercu rodziny, 100% życia z nami”*, *„Czułość i domowe ciepło od pierwszego dnia”*).
  4. **Naprawa widoku artykułu i zajawek:** Usunięto powielony lead `$post->excerpt` z nagłówka hero w `blog/show.blade.php`. Wdrożono migrację `2026_10_27_163000_clean_post_excerpts.php` oczyszczającą nagłówki Markdown `# ` w bazie oraz zoptymalizowano `Post::cardExcerpt()`.
  5. **Testy i Kompilacja:** Wszystkie 12 testów `PriceAndFunnelUnificationTest`, 5 testów `HomepageTest` oraz pełny `ProductionSmokeTest` przechodzą w 100% PASS; assety produkcyjne Vite przebudowane pomyślnie.
- [x] `feat(pricing): master ui/ux unification, admin price dashboard & premium funnel refactor` (2026-10-25) — Comprehensive 5-part architecture and UX/UI unification across the entire site:
  1. **Admin Price Management & Kitten Seeding:** Added `price` (integer PLN) to `animals` schema via safe migration. Added quick-edit price column in Cats Management table (`/backend/animals`) and Dashboard (`/backend/dashboard`) via `PATCH /backend/animals/{animal}/quick-price`, plus full price input in edit form. Seeded initial prices: Baron (`2 700 zł`), Cyprian (`3 000 zł`), Carlos (`3 000 zł`), 3 British kittens (`2 500 zł` each), available Siamese kitten (`2 100 zł` each); adult breeding cats / reproduktors hidden from pricing.
  2. **Elegance Price Display on Cards & Profile:** Cards on Homepage and `/koty` display a high-contrast quiet luxury gold pill price tag (`2 700 zł`). Cat profile page (`/koty/[slug]`) features luxury Price Box (`CENA KOCIAKA (OPCJA „NA KOLANKA”)`, serif/gold value, comprehensive starter kit guarantee subtext). Messenger-first primary CTA (`#C5A059`, prefilled kitten inquiry message, direct `m.me` link) + WhatsApp secondary outline CTA (`+48 514 153 204`). Video callout box updated to highlight Messenger & WhatsApp.
  3. **Quiz Result Screen Refactor (`/dobierz-rase`):** Eliminated visual clutter and neon "Christmas tree" styling. Removed duplicate top CTA block. Replaced clashing dual blue/green buttons in kitten rows with a single quiet luxury profile button (`🎬 Zobacz profil, zdjęcia i filmiki →`) and price badge. Single luxury contact block: primary solid gold Messenger button (`#C5A059`), secondary subtle outline WhatsApp button, reassurance micro-copy. Refined Facebook follower card with dark gold border.
  4. **Global Pre-Footer CTA Unification (`conversion-cta.blade.php`):** Replaced legacy generic buttons across all pages with primary gold Messenger button (`💬 Napisz lub umów wideo-wizytę (Messenger)`), secondary outline WhatsApp button, direct phone line, and subtle link to Facebook cattery stories.
  5. **SEO Product/Offer Schema & Pixel Tracking:** Automatically outputs Schema.org `Product` with `offers` (`price`, `PLN`, `InStock`) on priced kittens; retains pure `Animal` entity without commerce tags on breeding adults. Preserved `fbq('track', 'Contact')` on all Messenger & WhatsApp conversion buttons site-wide.
  6. **Automated Feature Tests:** 8 comprehensive tests in `PriceAndFunnelUnificationTest.php`. Total test suite: 193 tests, 1555 assertions, 100% PASS.
- [x] `fix(hero): mobile hero section hotfix & above-the-fold optimization` — Elevated featured cat photo (Bars) to the top on mobile (`~230px` height) with anchored glass/gold SHiOZ ZOOLANDIA seal. Shortened mobile eyebrow to `SHiOZ ZOOLANDIA • MAZOWIECKA SZWAJCARIA`, condensed H1 headline to `Koty Bengalskie, Brytyjskie i Syjamskie z Rodowodem.` (26px, leading-tight, max 3 lines), trimmed subtitle to 2 lines (`Domowa socjalizacja, badania genetyczne rodziców (HCM/PKD) i pełne wsparcie.`), and deployed two compact action buttons (`🐾 Zobacz dostępne kocięta` and `✨ Quiz: Dobierz rasę (45s)`) comfortably above the sticky bottom bar with peek of `Dostępne Kocięta i Mioty`. Added automated feature test. 184 tests passing 100% (1479 assertions).
- [x] `feat(ai-studio): image generator & structured breeder pro-tips` — Multi-tier AI image engine (Imagen + Pollinations fallback + cattery library), automatic local image persistence into `storage/app/public/posts/`, section `tip` (breeder callout box in blog template & CMS editor), Step 3 Hero Cover preview, and One-Click publish option. Result: 183 tests passing 100% (1470 assertions).
- [x] `test(audit): test suite hardening & de-duplication` — Audited test suite; removed dummy boilerplate tests (ExampleTest), added missing core admin modules (/backend/animals, /backend/posts/ai-studio, /backend/blog-cta) to smoke test, removed redundant local SEO duplicate from ReservationAndCroTest, tied quiz scoring test to live DOM options, fixed hardcoded role ID in MediaLibraryTest, and resolved stud vs kitten semantics in BritishCatsFatherAssignmentTest. Result: 180 clean, genuine tests passing 100% (1457 assertions).
- [x] `feat(legal): cookie consent banner modernization` — Redesigned banner UI/UX to legally maximize opt-in rates (prominent "Akceptuj wszystkie" primary CTA, subtle "Tylko niezbędne" secondary CTA, friendly cat-centric copy, mobile sticky bar collision avoidance, verified on desktop & mobile).
- [x] `perf(tests): fast test execution engine` — Added `composer test:smoke` (5s), optimized `composer test` with `-d xdebug.mode=off` (~40s vs 150s).
- [x] `feat(git): pre-commit hook enforcement` — Configured `.git/hooks/pre-commit` to guarantee 100% full test suite execution before every commit.
- [x] `refactor(ai): archive historical context` — Created adjacent `AIarchive/` folder, moved 13 completed/obsolete historical files, condensed active `AI/` knowledge base.

---

## Upcoming Milestones & Backlog

### Milestone 1: Litters & Planned Matings Module (Mioty i Zapowiedzi)
- [ ] `feat(litters): database model & migration` — Table `litters` (mother_id, father_id, expected_date, birth_date, status, description).
- [ ] `feat(cms): litter management dashboard` — CRUD in admin with kitten assignments.
- [ ] `feat(frontend): planned litters showcase` — Public display of upcoming litters with early reservation CTAs.

### Milestone 2: Automated Pedigree Generator (Generator Rodowodów)
- [ ] `feat(pedigree): 4-generation visual pedigree tree` — Automated generation from parent associations.
- [ ] `feat(pedigree): pdf export` — Printable / downloadable certificate for kitten buyers.

### Milestone 3: Lightweight Breeder CRM & Reservation Ledger
- [ ] `feat(crm): inquiry & reservation ledger` — Central tracking of phone / WhatsApp leads, kitten reservations and status transitions.
- [ ] `feat(crm): client communication notes` — Internal notes for the breeder on agreed terms and handover dates.