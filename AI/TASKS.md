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

## Active & Recent Maintenance Tasks (2026-10-28)
- [x] `revert(brand): rollback logo assets to original state from cb5a4f63` (2026-10-28) — Reverted all generated logo assets back to original `public/logo.png`, `public/logo-72.*`, `public/logo-36.*`, and original favicons. Preserved quiz carousel slides (`1.png`, `2.png`, `3.png`, `4.png`).
- [x] `feat(quiz): luxury 1:1 square Open Graph cover & crawler image unification` (2026-10-28) — Resolved Facebook link preview distortion where pinned ads showed a cropped pink circle logo instead of the quiz cover. Integrated `image/quiz/1.png` into `public/images/quiz/`, generated High-DPI 1200×1200px square asset (`og-quiz-1200.jpg`), updated all fallback aliases, enforced `og-image-width="1200"` / `og-image-height="1200"` in `quiz.blade.php`, fixed query string preservation for cache-busting and added `<link rel="image_src">` in `shell.blade.php`, and added automated feature test `QuizOpenGraphTest.php` (9 assertions, 100% PASS).
- [x] `test(audit): adversarial edge cases and race conditions test suite & price mutator overflow hardening` (2026-10-27) — Comprehensive adversarial testing suite (12 dedicated tests in `AdversarialEdgeCasesAndRaceConditionsTest.php`) forcing boundary conditions, race conditions, and hostile inputs:
  1. **Edge Case 1 — Ułamkowe grosze i separatory (comma/dot):** Naprawiono `Animal::setPriceAttribute`, usuwając część dziesiętną przed sanitizacją cyfr (`2 700,50 zł` -> `2700 zł`, eliminując błąd sklejenia do `270 050 zł`).
  2. **Edge Case 2 — Przepełnienie typu całkowitego (PHP float/int 64-bit overflow):** Przetestowano skrajny ciąg 50 cyfr (`999...999`). Odkryto błąd, w którym `(int) (1e51)` w PHP dawał `0` zamiast `1 000 000`. Wdrożono ograniczenie `$num >= 1000000` przed rzutowaniem do int.
  3. **Edge Case 3 — Wyciek ceny kota hodowlanego:** Przypadkowa cena wpisana kotu dorosłemu w bazie danych nigdy nie jest renderowana publicznie w katalogu ani na profilu (`shouldShowPrice()`).
  4. **Edge Case 4 — Natychmiastowa reakcja na zmianę statusu:** Zmiana ze statusu `Available` na `Sold` natychmiast ukrywa badge cenowy, boks ceny na profilu i oferty Schema.org.
  5. **Edge Case 5 — Odporność parsera zajawek artykułów:** Treść zawierająca wyłącznie nagłówki Markdown (`# `), bloki kodu (` ``` `) lub tabele Markdown (`|`) jest bezpiecznie czyszczona bez wycieku składni i bez łamania układu.
  6. **Edge Case 6 — Odporność stref czasowych:** Artykuły z datą w przyszłości w UTC są ukryte do dokładnej sekundy publikacji.
  7. **Race Condition 1 — Jednoczesne zgłoszenia szybkiego kontaktu (Quick Callback):** Sprawdzono blokadę atomową (`Cache::lock('quick_callback_lock_...')`), zapobiegającą wysłaniu zdublowanych powiadomień SMS/email przy równoczesnym podwójnym kliknięciu w tej samej milisekundzie.
  8. **Race Condition 2 — Równoczesna aktualizacja ceny (Concurrent Price Updates):** Zweryfikowano integralność transakcyjną bazy danych i poprawność odświeżenia danych.
  9. **Pusty katalog i brakujące dane w Quizie:** Przetestowano zachowanie algorytmu rekomendacji w sytuacji, gdy wszystkie koty w bazie mają status `Sold` lub `Breeding` — quiz renderuje elegancki fallback z zaproszeniem do kontaktu i wideo-rozmowy.
  10. **Stan testów:** 233 testy zdane (1797 asercji), 100% PASS w ~53s.
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