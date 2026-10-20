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
- **Test Suite Status**: 183 tests passed (1470 assertions), 100% PASS in ~40s without xdebug, smoke test in ~5s.

---

## Active & Recent Maintenance Tasks (2026-10-20)
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