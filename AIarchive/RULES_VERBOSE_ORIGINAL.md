01. Vision & AI Roles
02. Architecture Standards
03. UI/UX & Apple Design Rules
04. Laravel & Livewire Standards
05. Frontend Rules
06. Backend Rules
07. SEO & Performance
08. Security
09. Development Workflow
10. Documentation & DEV_JOURNAL
11. Definition of Done
12. Forbidden Patterns
13. AI Decision Framework
14. Future-proof Architecture

# RULES.md

> **Version:** 1.0
> **Project:** Luxury Cattery Website & CMS
> **Framework:** Laravel 12/13 + Livewire 4 + Flux UI
> **Architecture:** Domain Driven + Action Pattern + Service Layer
> **Status:** Production Grade
> **Language:** Polish (Frontend & Backend CMS) / English (Codebase)

---

# 1. Project Vision

## Mission

This project is **not** a simple website.

It is a premium digital brand designed to build trust, showcase exceptional breeding, generate customer inquiries, educate visitors through SEO, and evolve into a complete platform for breeders.

The application should feel comparable to premium brands such as:

- Apple
- Tesla
- Aesop
- Airbnb
- Patagonia

The website must communicate:

- professionalism
- trust
- elegance
- simplicity
- transparency
- premium quality

Every design and technical decision should reinforce these values.

---

## Primary Goals

The project must accomplish the following business goals.

### 1.

Present the breeder as a trusted expert.

### 2.

Generate inquiries about available kittens.

### 3.

Provide an easy-to-manage CMS for non-technical users.

### 4.

Become the primary SEO source for information about the breed.

### 5.

Support future expansion into:

- ecommerce
- reservations
- customer accounts
- online payments
- newsletters
- events
- pedigree management

---

## Long-Term Vision

The project should be designed as a scalable digital ecosystem rather than a static website.

Future modules should integrate naturally without requiring architectural rewrites.

Everything should be modular.

Everything should be reusable.

Everything should be production-ready.

---

# 2. AI Responsibilities

Claude is not acting as a code generator.

Claude is acting as a complete software team.

During every task Claude simultaneously acts as:

- Lead Software Architect
- Senior Laravel Developer
- Senior Livewire Developer
- UI Designer
- UX Director
- Product Designer
- SEO Specialist
- Performance Engineer
- Accessibility Expert
- Security Reviewer
- Technical Mentor

Never optimize only for speed.

Always optimize for long-term maintainability.

---

## Decision Making

Whenever multiple implementations exist:

Always choose the solution that is:

- more scalable
- easier to maintain
- easier to understand
- easier to test
- more secure
- more accessible
- more performant
- more elegant

Never choose shortcuts.

Never choose "tutorial solutions".

Never generate temporary code.

Everything should be production-ready.

---

## Confidence

Claude should make senior-level decisions.

Do not ask unnecessary questions.

If multiple valid solutions exist, choose the one with the lowest technical debt.

Only ask the user when a business requirement cannot be inferred.

---

# 3. Product Philosophy

The project is NOT a CRUD application.

The project is a premium customer experience.

Every screen should answer exactly one user question.

Examples:

Who are you?

↓

Can I trust you?

↓

Which kittens are available?

↓

Why are your cats special?

↓

How can I contact you?

Every page should guide the visitor naturally toward the next step.

---

## Emotional Design

Visitors should feel:

safe

calm

interested

curious

confident

never overwhelmed.

The interface should reduce cognitive load.

The interface should never compete with the photography.

---

## Luxury Brand Principles

Apple is an inspiration.

Never imitate Apple layouts.

Extract principles only.

Never think like a generic WordPress theme.

The website should feel handcrafted.

Premium brands remove elements.

Cheap brands add elements.

Whenever unsure:

remove instead of adding.

---

# 4. Design Philosophy

Design should follow:

Minimalism

Whitespace

Large typography

Beautiful photography

Simple hierarchy

Elegant spacing

Soft animations

Animation must never delay interaction.

Interaction always has priority.

Timeless visual language

Consistency over creativity.

---

## Visual Hierarchy

The user's eyes should naturally follow:

Hero

↓

Headline

↓

Primary CTA

↓

Supporting content

↓

Trust indicators

↓

Next section

No visual element should compete with the primary message.

---

## Typography

Typography is one of the strongest branding tools.

Rules:

Large headings.

Comfortable line height.

Short paragraphs.

Readable spacing.

Never use decorative fonts for body text.

Headings should create rhythm.

Reading should feel effortless.

---

## Photography

Photography is more important than illustrations.

Large photographs should dominate the layout.

Images should communicate:

quality

personality

care

luxury

authenticity

Avoid stock photography whenever possible.

---

# 5. Apple Inspired Principles

Apple Human Interface Guidelines should inspire the project.

Do NOT copy Apple.

Understand why Apple works.

---

## Simplicity

Every component should have one responsibility.

Every section should have one purpose.

Every button should have one action.

If something can be removed,

remove it.

---

## Focus

One primary CTA per section.

One visual focus per screen.

Never present multiple competing actions.

---

## Motion

Animations should explain.

Animations should never entertain.

Animation should improve orientation.

Respect prefers-reduced-motion.

Duration:

150ms

250ms

400ms

No longer.

---

## Spacing

Whitespace is not empty.

Whitespace improves readability.

Whitespace increases perceived quality.

Never compress layouts.

---

## Colors

Prefer calm neutral colors.

Avoid saturated colors.

Accent colors should be used sparingly.

The cats should always be the visual focus.

---

# 6. User Experience Principles

The interface should never require explanation.

If a user must think,

the interface has failed.

---

## Three Click Rule

Important information should never require more than three interactions.

---

## Recognition Over Recall

Users should recognize actions immediately.

Do not hide important actions.

Avoid clever navigation.

Choose obvious navigation.

---

## Mobile First

Every feature must be designed for mobile first.

Desktop enhances the experience.

Mobile defines the experience.

---

## Accessibility

Target WCAG AA.

Keyboard navigation required.

Visible focus states required.

ARIA labels where necessary.

Semantic HTML first.

Accessibility is not optional.

---

# 7. Conversion Principles

The purpose of the website is not page views.

The purpose is inquiries.

Everything should increase trust.

Everything should reduce friction.

Everything should encourage contact.

---

## Trust Builders

Use:

health certificates

show awards

pedigrees

customer testimonials

real photographs

transparent breeding philosophy

Never exaggerate.

Authenticity converts better than marketing.


# 8. Software Architecture

## Core Philosophy

This project follows a Domain-Oriented Architecture built on Laravel best practices.

The architecture must optimize for:

- maintainability
- scalability
- readability
- testability
- low coupling
- high cohesion

Every new feature should naturally fit into the existing architecture.

Never create code that only solves today's problem.

Always consider future expansion.

---

## Separation of Concerns

Every layer has exactly one responsibility.

### Routes

Routes only define:

- URI
- Middleware
- Route Model Binding
- Controller or Livewire endpoint

Never place business logic inside routes.

---

### Controllers

Controllers coordinate requests.

Responsibilities:

- authorize
- validate (or delegate)
- call Action
- return response

Controllers should remain extremely small.

Target:

5–30 lines.

---

### Livewire Components

Livewire Components represent the presentation layer.

Responsibilities:

- UI state
- user interaction
- validation
- calling Actions

Never place business logic inside components.

Never query large datasets directly inside render().

---

### Models

Models represent data.

Models may contain:

Relationships

Scopes

Attribute Casts

Accessors

Mutators

Small helper methods

Never place workflows inside Models.

Avoid "fat models."

---

### Actions

Business logic belongs inside Actions.

Each Action should solve one business problem.

Examples:

CreateAnimalAction

UpdateAnimalAction

ReserveAnimalAction

PublishPostAction

CreateInquiryAction

Each Action contains exactly one public method:

handle()

Everything else should be private.

---

### Services

Services communicate with external systems.

Examples:

MailService

ImageService

SeoService

PedigreeImportService

StripeService

NewsletterService

Services should never know about HTTP requests.

---

### Policies

Every business entity must have a Policy.

Examples:

AnimalPolicy

PostPolicy

MediaPolicy

ProductPolicy

Never authorize inside Blade.

Never authorize inside Actions.

---

# 9. Domain Design

The application is divided into domains.

Each domain owns its logic.

Current Domains:

Authentication

Animals

Litters

Blog

Media

Products

SEO

Inquiry

Administration

Future Domains:

Reservations

Payments

Customers

Newsletter

Events

Gallery

Analytics

Every domain should remain independent.

---

## Animals Domain

Do not think in database tables.

Think in business concepts.

Animal

↓

Gallery

↓

Pedigree

↓

Health

↓

Achievements

↓

Inquiry

Each concept has its own responsibility.

---

## Naming

Always use business language.

Bad

CatController2

Good

AnimalController

Bad

saveData()

Good

ReserveAnimalAction

Bad

status2

Good

AnimalStatus

Names should explain intent.

---

# 10. Database Standards

The database should be self-documenting.

Schema design is part of software architecture.

---

## Naming

Tables:

plural

snake_case

Columns:

snake_case

Foreign keys:

animal_id

mother_id

father_id

Never use abbreviations.

---

## IDs

Default:

Laravel ULID

Avoid auto increment IDs unless justified.

---

## Money

Never use float.

Always use:

decimal(10,2)

---

## Dates

Use timestamps.

Use Carbon.

Store everything in UTC.

Convert for presentation only.

---

## Soft Deletes

Business data should never disappear.

Use SoftDeletes whenever recovery may be useful.

Examples:

Animals

Products

Posts

Inquiries

Media

---

## Foreign Keys

Always define foreign keys.

Always define cascade behavior explicitly.

Never rely on Laravel defaults.

---

## Indexes

Index every frequently queried column.

Examples:

slug

status

published_at

breed

gender

created_at

Never optimize later.

Design indexes early.

---

# 11. Enums

Every state should become an Enum.

Never use magic strings.

Examples:

AnimalStatus

AnimalGender

AnimalType

InquiryStatus

PostStatus

ProductStatus

UserRole

Advantages:

Type safety

Autocomplete

Cleaner code

Fewer bugs

---

# 12. Validation

Validation belongs close to user input.

Preferred order:

Form Objects

↓

Validate Attribute

↓

Form Request

Never validate manually inside business logic.

Validation messages should be friendly.

The client is not a developer.

---

# 13. File Uploads

Every uploaded image should be processed.

Requirements:

Unique filename

Responsive versions

WebP

Original preserved

Metadata stored

Lazy loading

Alt text support

Future support:

AVIF

Automatic watermark

Automatic compression

---

# 14. SEO Architecture

SEO is a core feature.

Not an addon.

Never sacrifice readability for SEO.

Humans first.

Search engines second.

Every page supports:



SEO Title

Meta Description

Canonical URL

OpenGraph

Twitter Cards

Schema.org

Breadcrumbs

XML Sitemap

Robots

JSON-LD

Every blog article should automatically generate structured data.

Animal pages should expose structured information whenever appropriate.

---

# 15. Performance

Performance is part of the product.

Target:

Lighthouse Performance >95

Accessibility >95

SEO >100

Best Practices >100

---

## Images

Never serve original photos.

Always generate optimized versions.

Use lazy loading.

Use responsive sizes.

Prefer WebP.

Support AVIF in the future.

---

## Queries

Prevent N+1.

Always eager load.

Cache expensive queries.

Avoid duplicate queries.

Use Computed Properties in Livewire.

---

## Rendering

Never execute database queries inside Blade.

Never execute expensive queries inside render().

Move heavy work into:

Actions

Computed Properties

Services

---

# 16. Livewire 4 Standards

Always prefer:

Single File Components

Computed Properties

Form Objects

Volt where appropriate

Reactive UI

Keep Components small.

One component.

One responsibility.

---

## State

UI state belongs inside Livewire.

Business state belongs inside Actions.

Persistence belongs inside Models.

---

## Communication

Prefer:

Events

Computed

Properties

Dependency Injection

Avoid tightly coupled components.

---

# 17. Folder Structure

app/

    Actions/

    Enums/

    Services/

    Policies/

    Models/

    Livewire/

    DTO/

    Support/

    ViewModels/

    Notifications/

    Mail/

resources/

    views/

    components/

    layouts/

database/

    factories/

    seeders/

tests/

    Feature/

    Unit/

Each folder should represent intent.

Never create "Helpers" full of unrelated code.

---

# 18. Coding Standards

Follow PSR-12.

Follow Laravel Pint.

Prefer constructor property promotion.

Prefer readonly properties where applicable.

Prefer dependency injection.

Avoid static helpers.

Avoid facades inside business logic unless justified.

Write expressive code.

Readable code is faster than clever code.

---

# 19. Documentation

Every complex class should contain DocBlocks explaining:

Purpose

Responsibilities

Dependencies

Examples where useful

Code should teach future developers.

The project is also a learning resource.

# 20. Development Workflow

Development should always follow the same predictable process.

Never jump directly into writing code.

Always think first.

---

## Mandatory Workflow

Every task must follow this lifecycle.

### Step 1 — Understand

Understand the business requirement.

Do not assume.

Do not invent features.

Clarify only if the requirement cannot be inferred.

---

### Step 2 — Analyze

Before writing code:

• analyze existing architecture

• search for reusable code

• identify affected modules

• detect possible side effects

• detect future scalability issues

---

### Step 3 — Plan

Before changing code Claude must explain:

Affected files

New classes

Database impact

Potential risks

Architecture decision

---

### Step 4 — Implement

Implement the smallest complete solution.

Never leave partially implemented features.

Everything should compile.

Everything should work after migrate:fresh --seed.

---

### Step 5 — Review

Review your own code.

Ask yourself:

Can this be simplified?

Can this be reused?

Can this scale?

Can another developer understand this?

---

### Step 6 — Optimize

Optimize only after correctness.

Never optimize prematurely.

---

### Step 7 — Document

Update

DEV_JOURNAL.md

Explain architectural decisions.

Explain why.

Not only what.

---

# 21. AI Decision Framework


When unsure

search the existing codebase first.

Reuse existing architecture.

Create new code only if necessary.

Claude should think like a Lead Engineer.

Before writing any code ask internally:

Is this maintainable?

Is this scalable?

Is this testable?

Is this reusable?

Is this secure?

Is this accessible?

Is this production-ready?

If the answer is "No"

rewrite the solution.

---

## Preferred Order

Always optimize for:

Correctness first.

Maintainability second.

Performance third.

Premature optimization is forbidden.

Production bottlenecks must be optimized.

Never sacrifice architecture for speed.

---

# 22. DRY Principles

Never duplicate logic.

Never duplicate validation.

Never duplicate UI.

Never duplicate queries.

Never duplicate configuration.

If code appears three times

extract it.

---

# 23. KISS Principles

Keep everything simple.

Simple code is easier to maintain.

Simple code contains fewer bugs.

Simple code is easier to review.

Avoid unnecessary abstraction.

Avoid overengineering.

---

# 24. SOLID

Every feature should naturally follow SOLID principles.

Single Responsibility

Open Closed

Liskov

Interface Segregation

Dependency Inversion

When unsure

prefer composition over inheritance.

---

# 25. YAGNI

Do not build features that are not needed today.

Design for extension.

Do not implement imaginary requirements.

Architecture should support future growth.

Implementation should solve today's problem.

---

# 26. Dependency Injection

Dependency Injection is the default.

Avoid static dependencies.

Avoid service locators.

Dependencies should be explicit.

Every class should clearly communicate what it needs.

---

# 27. Error Handling

Applications fail.

Good software fails gracefully.

Never expose exceptions to users.

Always log unexpected errors.

Always provide friendly messages.

Technical details belong in logs.

Not in the UI.

---

## Logging

Log only meaningful events.

Examples:

Animal Reserved

Inquiry Created

Payment Confirmed

Blog Published

Avoid noisy logs.

---

# 28. Security

Security is never optional.

Every feature should assume malicious input.

---

## Validation

Validate

everything.

Never trust user input.

---

## Authorization

Every business entity requires authorization.

Never rely on hidden buttons.

Backend must enforce permissions.

---

## CSRF

Always enabled.

Never disable security for convenience.

---

## XSS

Escape output by default.

Render HTML only after sanitization.

---

## SQL Injection

Always use Eloquent.

Use Query Builder when necessary.

Never concatenate SQL strings.

---

## Secrets

Never hardcode

API Keys

Passwords

Tokens

Secrets

Everything belongs in .env

---

# 29. Accessibility

Accessibility is part of quality.

Not an optional improvement.

---

## Requirements

Keyboard navigation

Visible focus

Semantic HTML

Alt text

ARIA when necessary

Screen reader friendly

Contrast AA

Reduced motion support

---

# 30. Frontend Philosophy

Frontend should feel calm.

Not busy.

Not flashy.

The animals are the heroes.

Not the interface.

---

## Components

Prefer reusable components.

Buttons

Cards

Badges

Modals

Inputs

Forms

Navigation

Hero Sections

Gallery

Testimonials

Everything reusable.

---

## Responsive

Design mobile first.

Tablet second.

Desktop third.

Large monitors last.

Never break layouts.

---

# 31. Forms

Forms should reduce user effort.

Only ask for required information.

Use inline validation.

Explain errors clearly.

Support autofill.

Support keyboard navigation.

Large touch targets.

Friendly labels.

Never use placeholder as label.

---

# 32. Media

Media is the heart of this application.

Photography sells trust.

---

## Images

Always optimize.

Always lazy load.

Always support responsive sizes.

Always preserve originals.

Generate thumbnails automatically.

Generate WebP automatically.

Prepare architecture for AVIF.

---

## Galleries

Gallery should feel premium.

Large imagery.

Smooth transitions.

Keyboard support.

Swipe on mobile.

Fullscreen support.

---

# 33. SEO Content

Content should educate.

Not advertise.

Blog articles should answer real user questions.

Every article should:

teach

build trust

improve SEO

generate inquiries

---

# 34. Internationalization

Architecture should support multiple languages.

Current language:

Polish.

Future:

English

German

Dutch

Never hardcode translatable content.

Always use localization files.

---

# 35. Future Modules

Architecture should naturally support:

Shop

Reservations

Wishlist

Customer Accounts

Newsletter

Events

Pedigree Generator

Medical History

Vaccinations

Certificates

Breeding Calendar

Invoices

CRM

Payments

Analytics

API

Everything should integrate naturally.

Never require rewriting the architecture.

---

# 36. Technical Debt

Technical debt is expensive.

If something feels wrong

improve it now.

Never postpone bad architecture.

---

# 37. Code Reviews

Claude should review every implementation.

Review as if another Senior Developer submitted the Pull Request.

Look for:

Duplication

Naming

Architecture

Performance

Security

Accessibility

Maintainability

Only finish when satisfied.

---

# 38. Definition of Done

A feature is only complete if:

✓ Works correctly

✓ Tested

✓ Responsive

✓ Accessible

✓ Secure

✓ SEO compliant

✓ Performance optimized

✓ Production ready

✓ Documented

✓ DEV_JOURNAL updated

If any item is missing

the feature is NOT finished.


# 39. Design System

The application must follow one consistent design language.

Never design each page independently.

Every page belongs to one system.

---

## Design Inspiration

Primary Inspiration

Apple Human Interface Guidelines

Secondary Inspirations

Aesop

Tesla

Linear

Notion

Airbnb

Patagonia

Do NOT imitate.

Understand the design principles.

---

## Visual Keywords

Luxury

Editorial

Elegant

Minimal

Timeless

Breathable

Premium

Organic

Confident

Calm

Natural

---

## Grid

Desktop

12 Columns

Tablet

8 Columns

Mobile

4 Columns

Always align to grid.

---

## Spacing Scale

Use 8pt Grid.

4

8

16

24

32

48

64

96

128

Never invent random spacing.

---

## Border Radius

xs

4

sm

8

md

12

lg

16

xl

24

full

9999

Consistency is mandatory.

---

## Shadows

Very subtle.

Prefer elevation using whitespace.

Avoid heavy shadows.

---

## Glass Effects

Allowed only when they improve usability.

Never use glassmorphism for decoration.

---

## Icons

Use Lucide Icons only.

Never mix icon libraries.

---

## Buttons

Primary

Secondary

Ghost

Link

Destructive

No additional button styles.

---

## Inputs

Consistent height.

Consistent radius.

Large click targets.

Accessible labels.

Never rely on placeholders.

# 40. Component Library

Every reusable UI belongs inside the design system.

Preferred components:

Hero

Section

Card

Animal Card

Blog Card

Gallery

Carousel

Timeline

Stats

Badge

Button

Input

Textarea

Select

Tabs

Accordion

Breadcrumb

Pagination

Toast

Modal

Drawer

Alert

Empty State

Loading State

Skeleton

Newsletter

Testimonial

FAQ

CTA

Footer

Header

Everything reusable.

Never duplicate components.

If similar components exist

merge them.

# 41. Frontend Architecture

Frontend is a product.

Not a collection of pages.

Every page should reuse:

Layouts

Components

Spacing

Typography

Colors

Animations

Cards

Forms

Sections

Never create one-off layouts.

---

Every page should have:

Hero

Content

CTA

Footer

unless justified otherwise.

---

Prefer composition.

Avoid page-specific CSS.

Everything reusable.

# 42. Motion Design

Motion should improve understanding.

Never distract.

Use animations for:

Page transitions

Image loading

Hover

Cards

Accordions

Navigation

Gallery

Dialogs

Dropdowns

Lazy loading

Preferred duration

150

250

400

Preferred easing

ease-out

Avoid bouncing animations.

Avoid spinning animations.

Avoid unnecessary motion.

Motion should feel expensive.

Not playful.

# 43. Content Strategy

Content should educate.

Not sell aggressively.

Every page should answer questions.

Every paragraph should have purpose.

Avoid filler text.

Avoid marketing clichés.

Use authentic language.

The breeder should sound experienced.

Not corporate.

Not salesy.

Trust first.

Sales second.

# 44. CMS Philosophy

The client is not a developer.

The CMS should require almost zero training.

Every form should explain itself.

Every destructive action requires confirmation.

Empty states should guide users.

Validation should explain how to fix errors.

Admin panel should feel calm.

Never overwhelm the client.

Prefer fewer options.

Prefer defaults.

Use helper text.

Use previews whenever possible.

# 45. AI Self Review

Before finishing every task Claude must review the result.

Checklist

Architecture

Code Style

Naming

Performance

Accessibility

SEO

Responsiveness

Security

Maintainability

Developer Experience

Luxury Design

Apple Inspired UI

If any category scores below 9/10

improve the solution.

Never stop after the first working implementation.

Always iterate.

Think like a Lead Engineer reviewing a Pull Request.

Avoid generic Helper classes.

Prefer:

Action

Service

Value Object

DTO

Enum

Trait (only when justified)

Never create:

Utils

Helper

Manager

Common

BaseService

Misc

General

unless explicitly requested.

Avoid inheritance.

Prefer composition.

Base classes require strong justification.

Do not introduce Repository Pattern unless multiple data sources exist.

Events should model business events.

Do not replace direct method calls with Events without clear benefit.


Before generating any UI

Claude must inspect

existing components

existing layouts

existing spacing

existing typography

existing colors

existing cards

existing buttons

Never redesign an existing system.

Extend it.

Context Window Protection:

Never rewrite large files unless requested.

Modify only the necessary parts.

Preserve surrounding code.

Avoid unrelated refactoring.

One task.

One responsibility.

Project Memory:
Before implementing a feature:

1. Inspect the existing architecture.
2. Reuse existing components.
3. Reuse existing Actions.
4. Reuse existing Services.
5. Reuse existing Enums.
6. Reuse existing Policies.
7. Reuse existing UI components.

Only create new files when no suitable implementation exists.


# 45. AI Design Direction


The website should not resemble a traditional breeder website.

It should resemble a premium lifestyle brand.

Users should immediately feel:

Luxury

Trust

Calm

Craftsmanship

Transparency

Every page should communicate quality before information.

People buy emotionally before they buy rationally.

The interface should support this behaviour.

---

## Inspiration Priority

1. Apple

2. Tesla



Claude should understand WHY these products feel premium.

Never copy.

Extract principles.

---

## Apple Principles

Large typography

Minimal interface

Editorial layouts

Large photography

Clear hierarchy

Perfect spacing

One CTA

No visual noise

Premium motion

---

## Aesop Principles

Warm colors

Natural feeling

Editorial layouts

Luxury typography

Calm interface

Organic photography

---

## Tesla Principles

One screen.

One message.

One CTA.

Large imagery.

No distractions.

---

## Airbnb Principles

Card layouts

Filters

Gallery

Easy exploration

Search UX

---

## Patagonia Principles

Storytelling

Authenticity

Mission first

Community

Trust before selling

---

## Final Experience

The user should never think

"I am browsing a breeder website."

The user should think

"I discovered a premium luxury brand."

Luxury products remove.

Cheap products add.

Whenever unsure

remove instead of adding.

---

Whitespace is a feature.

---

Typography is more important than decorations.

---

Photography is more important than illustrations.

---

Animation is more important than gradients.

---

Content is more important than animation.

---

Trust is more important than conversion.

---

One excellent section is better than three average ones.

---

Every section should answer one question.

Never answer three questions on one screen.

---

Every interaction should feel intentional.

Nothing should feel accidental.

---

The user should never feel lost.

The interface should always guide them.

---

Premium brands whisper.

Cheap brands shout.

The interface should whisper.

# 45. # Page Architecture

Home
Hero

↓

Available Animals

↓

Why Choose Us

↓

Stud Males

↓

Queens

↓

Health & Certificates

↓

Testimonials

↓

Instagram

↓

Latest Articles

↓

Contact

↓

Footer
Animal Page
Hero

↓

Gallery

↓

About

↓

Health

↓

Pedigree

↓

Parents

↓

Achievements

↓

FAQ

↓

Inquiry Form

↓

Related Animals
Blog
Hero

↓

Categories

↓

Featured

↓

Articles

↓

Newsletter

↓

Footer
Contact
Hero

↓

Contact Methods

↓

Inquiry Form

↓

Google Maps

↓

FAQ

↓

Footer
Shop (Future)
Hero

↓

Categories

↓

Filters

↓

Products

↓

Newsletter

↓

Footer

---

# 15. Production Deployment & Data Safety Rules

## 15.1 Zero-Overwrite Policy (Live DB is Ground Truth)
- The production database on the hosting server contains live, manually curated records (descriptions, photos, birth dates, sort orders).
- NEVER overwrite or update existing database records via seeders or migrations unless explicitly requested by the project owner.
- `DatabaseSeeder.php` and `RealKittensAndParentsSeeder.php` are for fresh local development ONLY. They must NEVER be executed on production!
- All migrations introducing content must be strictly INSERT-ONLY:
  ```php
  if (! Animal::where('slug', $slug)->exists()) {
      Animal::create([...]);
  }
  ```
- Migrations must NEVER contain `Animal::update(...)` or unconditional `updateOrCreate()` on records that the user may have edited in the CMS dashboard.

## 15.2 Safe Deployment Flow
1. Developer pushes to remote: `git push origin main`.
2. Access the webhook/browser sync URL on hosting: `/deploy-sync`.
3. `/deploy-sync` executes:
   - `git pull origin main`
   - `php artisan migrate --force`
   - `php artisan optimize:clear`
   - Note: Blog file import is skipped by default to protect edits made in CMS dashboard.

## 15.3 Animal Sorting Standard (sort_order)
All animals must follow the automated hierarchy managed by `Animal::nextSortOrderFor($status, $breed)`:
- **1–19:** Kocięta do rezerwacji / dostępne (*Available / Reserved*)
- **20–29:** Koty w nowych domach (*Sold / Alumni*)
- **31–39:** Dorosłe koty hodowlane rasy Kot Brytyjski (*Breeding*) [Staś 31, Nel 32, Atos 33]
- **41–49:** Dorosłe koty hodowlane rasy Kot Syjamski (*Breeding*) [Baltazar 41, Nela 42, Koko 43]
- **51–59:** Dorosłe koty hodowlane rasy Kot Bengalski (*Breeding*) [Bella 51, Luki 52, Luna 53, Nel 54, Bars 55]

## 15.4 UI Standards: Dual Pagination & Polish CMS
- Every table and list view (backend CMS and public frontend) must render pagination controls BOTH above and below the table.
- Both Frontend and Backend CMS interfaces (navigation, sidebars, buttons, tooltips, validation messages) are fully localized in Polish (`lang/pl.json`).

