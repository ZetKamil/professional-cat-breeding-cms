# DECISIONS.md

> Architectural Decision Record (ADR)
>
> This document records every major architectural and product decision.
>
> AI assistants MUST read this file before making changes.
>
> Never revert a documented decision unless explicitly instructed by the project owner.

---

# Decision 001

## Project Philosophy

Status:
Accepted

Date:
2026-07-29

---

The project is NOT a Laravel demo.

The project is NOT a CRUD application.

The project is a premium digital product.

Every implementation should increase perceived quality.

Business value is more important than implementation speed.

---

# Decision 002

## Design Direction

Status:
Accepted

---

The UI is inspired by:

• Apple Human Interface Guidelines

• Airbnb

• Aesop

• Stripe

Never imitate generic Laravel templates.

Never imitate Bootstrap.

Never imitate WordPress.

Design should feel calm.

Editorial.

Luxury.

Timeless.

---

# Decision 003

## Frontend Stack

Accepted

Laravel Blade

Livewire

Alpine

Vite

CSS Design Tokens

No Tailwind migration.

No React migration.

No Vue migration.

---

# Decision 004

## Backend Architecture

Accepted

Domain Driven

Actions

Services

Policies

Form Requests

Enums

DTO where appropriate

Never use Fat Controllers.

Never use Fat Models.

---

# Decision 005

## Images First

Accepted

Photography is the most important design element.

Images should dominate.

UI supports photography.

UI never competes with photography.

---

# Decision 006

## Typography

Accepted

Large headings.

Editorial spacing.

Comfortable paragraphs.

Reading rhythm.

Whitespace over decoration.

---

# Decision 007

## Navigation

Accepted

Navigation should remain simple.

Top navigation only.

Sticky.

Glass.

Minimal.

Maximum clarity.

---

# Decision 008

## Components

Accepted

Everything reusable.

Never duplicate UI.

Create reusable Blade Components.

Component first.

---

# Decision 009

## Design Tokens

Accepted

All spacing

colors

radius

typography

animation

must come from Design Tokens.

Never hardcode values.

---

# Decision 010

## CSS Architecture

Accepted

CSS is modular.

Component CSS.

Page CSS.

Utilities.

Tokens.

No inline CSS.

No huge stylesheet.

---

# Decision 011

## Performance

Accepted

Performance is a feature.

CLS near zero.

Images optimized.

Lazy loading.

Minimal DOM.

Minimal CSS.

---

# Decision 012

## Accessibility

Accepted

WCAG AA minimum.

Keyboard navigation.

Reduced Motion.

Visible focus.

ARIA where needed.

---

# Decision 013

## Mobile First

Accepted

Design starts on mobile.

Desktop enhances.

Never desktop-first.

---

# Decision 014

## Homepage Philosophy

Accepted

Homepage tells a story.

Not features.

Not widgets.

Narrative flow:

Who are we

↓

Why trust us

↓

Animals

↓

Testimonials

↓

Articles

↓

Contact

---

# Decision 015

## Cards

Accepted

Cards should resemble Apple product cards.

No heavy shadows.

No loud colors.

No gradients for decoration.

Calm hover.

Premium spacing.

---

# Decision 016

## Animations

Accepted

Animations explain.

Never entertain.

Duration:

150

250

400

Use easing.

No bounce.

No excessive motion.

---

# Decision 017

## Future Architecture

Accepted

Everything should naturally support:

Reservations

CRM

Payments

Newsletter

Events

Customer Accounts

Without rewrites.

---

# Decision 018

## AI Workflow

Accepted

AI never asks

"What should I do next?"

AI follows:

MASTERPLAN

↓

Current Sprint

↓

TASKS

↓

Documentation

↓

Git Commit

↓

Push

↓

Continue

---

# Decision 019

## Git Strategy

Accepted

Small commits.

Atomic commits.

Every feature deployable.

Always push after completing a task.

Never leave broken code.

---

# Decision 020

## Documentation

Accepted

Every sprint updates:

TASKS.md

DEV_JOURNAL.md

DAILY_LOG.md

DECISIONS.md if needed.

Documentation is mandatory.

---

# Decision 021

## Code Quality

Accepted

Readable code.

Predictable code.

Maintainable code.

Production code.

Never tutorial code.

---

# Decision 022

## Product Quality

Accepted

Perceived quality is a feature.

If something feels:

cheap

generic

template-like

busy

then redesign it.

Never settle for "good enough."

Every page should feel handcrafted.

---

# Decision 023

## AI Autonomy

Accepted

AI should continue working until:

Current Sprint is complete

or

Context window is exhausted.

Before stopping AI must:

Update TASKS.md

Update DAILY_LOG.md

Update DEV_JOURNAL.md

Commit

Push

Leave repository working.

Never stop mid-feature.

---

# Decision 024

## Definition of Luxury

Accepted

Luxury means:

Less UI

More whitespace

Better typography

Better photography

Better rhythm

Better interaction

Calmer experience

Never more effects.

Always more refinement.

---

# Decision 025

## Long-Term Goal

Accepted

The project should become one of the best open-source Laravel CMS examples focused on luxury branding, clean architecture and premium frontend experience.

Every implementation should move toward this vision.

---

# Decision 026

## Production Database Integrity & Zero-Overwrite Policy

Status:
Accepted

Date:
2026-10-14

The live production database on the hosting server is the ultimate single source of truth for all animals, blog content, custom descriptions, photos, and sort orders edited by the breeder.

1. **Seeders Are Local Only:**
   Seeders (e.g. `RealKittensAndParentsSeeder.php`, `DatabaseSeeder.php`) must NEVER be executed on the production hosting. They exist solely for fresh local development setups.

2. **Insert-Only Migrations:**
   All migrations introducing new animals or content must be strictly insert-only:
   - Always verify if a record exists (`if (! Animal::where(...)->exists())`).
   - If a record exists, leave it 100% untouched.
   - NEVER execute `->update()` or delete pre-existing records in migrations.

3. **Safe Deployment Procedure:**
   Deployment is executed via:
   - `git push origin main`
   - Accessing `/deploy-sync` in browser.
   - `/deploy-sync` executes only: `git pull`, `artisan migrate --force`, and `artisan optimize:clear`. Blog asset import is disabled by default to protect CMS dashboard edits.

---

# Decision 027

## Animal Sort Order Hierarchy

Status:
Accepted

Date:
2026-10-14

To ensure deterministic, clean sorting and eliminate manual order management, all animals follow a standardized global numbering scheme:

- **1–19:** Kocięta do rezerwacji / dostępne (*Available / Reserved*)
- **20–29:** Koty w nowych domach (*Sold / Alumni*)
- **31–39:** Dorosłe koty hodowlane rasy Kot Brytyjski (*Breeding*) [Staś 31, Nel 32, Atos 33]
- **41–49:** Dorosłe koty hodowlane rasy Kot Syjamski (*Breeding*) [Baltazar 41, Nela 42, Koko 43]
- **51–59:** Dorosłe koty hodowlane rasy Kot Bengalski (*Breeding*) [Bella 51, Luki 52, Luna 53, Nel 54, Bars 55]

Automated assignment is handled natively by `Animal::nextSortOrderFor($status, $breed)`.

---

# Decision 028

## Dual Table Pagination Standard

Status:
Accepted

Date:
2026-10-14

For maximum accessibility and seamless administrative workflow:
- All data tables across the CMS Backend (Animals, Posts, Media, Categories, Users, Roles) and frontend catalogs (Animals, Blog) must render pagination links **both at the top and at the bottom** of the table/grid.
- Users must never be forced to scroll down long lists just to switch pages.

---

# Decision 029

## Complete Polish Localization for CMS & Frontend

Status:
Accepted

Date:
2026-10-14

1. **Frontend:** 100% Polish editorial copy, canonical Polish URLs (`/kontakt`, `/o-nas`, `/blog`, `/koty`).
2. **Backend CMS:** 100% Polish interface (navigation, sidebars, form labels, tooltips, validation messages) tailored for non-technical breeders.
3. **Codebase:** 100% clean English (PHP class names, methods, database columns, architectural patterns).