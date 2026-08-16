# UI_PATTERNS.md

> Version: 1.0
> Project: Professional Cat Breeding CMS
> Purpose: Reusable UI patterns and interaction standards.
> Related:
> - RULES.md
> - DESIGN_SYSTEM.md
> - COMPONENT_LIBRARY.md

---

# Philosophy

The UI is built from reusable patterns.

Never create custom layouts for individual pages unless absolutely necessary.

Visitors should subconsciously recognize every section.

Consistency creates trust.

Every page should feel like it belongs to the same premium product.

---

# Layout System

Every public page follows:

Navbar

↓

Hero

↓

Main Content

↓

CTA

↓

Footer

Spacing is always consistent.

No page invents its own spacing.

---

# Section Pattern

Every content section uses:

Section

↓

Eyebrow

↓

Heading

↓

Description

↓

Content

↓

CTA (optional)

Example:

---------------------------------

ABOUT

Luxury British Shorthair Cattery

Small introduction...

[content]

[button]

---------------------------------

Never skip hierarchy.

---

# Hero Pattern

Every Hero contains:

Small Eyebrow

↓

Large Headline

↓

Supporting paragraph

↓

Primary CTA

↓

Secondary CTA

↓

Large photograph

Never place multiple competing messages.

---

# Card Pattern

Cards always contain:

Image

↓

Badge

↓

Title

↓

Description

↓

Meta information

↓

Action

Cards never exceed 3 visual levels.

---

# Animal Card Pattern

Image

↓

Status Badge

↓

Animal Name

↓

Breed

↓

Short Description

↓

Age

↓

View Details Button

Hover:

• subtle lift

• subtle shadow

• image zoom

Nothing flashy.

---

# Blog Card Pattern

Featured Image

↓

Category

↓

Title

↓

Excerpt

↓

Reading Time

↓

Read Article

---

# CTA Pattern

Every CTA section contains:

Headline

↓

One supporting sentence

↓

Single primary button

Optional:

secondary button

Never more than two buttons.

---

# Testimonial Pattern

Large quotation

↓

Customer image

↓

Customer name

↓

Location

↓

Optional rating

Maximum three testimonials per row.

---

# Gallery Pattern

Desktop

2–4 columns

Mobile

1 column

Lightbox opens on click.

Keyboard navigation required.

Swipe support required.

---

# Feature Grid

Maximum:

3 columns desktop

2 tablet

1 mobile

Every feature contains:

Icon

↓

Title

↓

Description

Never mix icon sizes.

---

# Forms

Forms follow:

Label

↓

Input

↓

Helper text (optional)

↓

Validation

Never:

Placeholder instead of label.

---

# Buttons

Hierarchy:

Primary

Secondary

Ghost

Text Link

Danger

Never invent button styles.

---

# Tables

Admin only.

Alternating row hover.

Sortable columns.

Sticky header.

Bulk actions.

Search above table.

Pagination below.

---

# Empty States

Every empty state contains:

Illustration

↓

Headline

↓

Explanation

↓

Primary action

Never show blank pages.

---

# Loading States

Prefer:

Skeletons

instead of

Spinners.

Skeleton duration:

200-400ms

---

# Error States

Always explain:

What happened

↓

Why

↓

How to recover

Never display stack traces.

---

# Success States

Short.

Positive.

No unnecessary dialogs.

Prefer toast notifications.

---

# Search Pattern

Desktop:

Search bar

+

Filters

+

Sorting

Mobile:

Search

↓

Filters Drawer

↓

Results

---

# Filter Pattern

Desktop

Sidebar

Mobile

Bottom Sheet

Never use huge dropdowns.

---

# Navigation Pattern

Desktop

Logo

Primary Nav

CTA

Mobile

Logo

Hamburger

Drawer

No more than 6 top-level links.

---

# Breadcrumb Pattern

Home

/

Animals

/

Animal Name

Always visible on detail pages.

---

# Detail Page Pattern

Hero

↓

Quick Facts

↓

Gallery

↓

Story

↓

Pedigree

↓

Achievements

↓

Related Animals

↓

Inquiry CTA

---

# Blog Detail Pattern

Hero

↓

Metadata

↓

Content

↓

Quote Blocks

↓

Related Articles

↓

CTA

---

# CMS Pattern

Sidebar

↓

List

↓

Filters

↓

Create

↓

Edit

↓

Delete

Always predictable.

---

# Modal Pattern

Maximum width:

640px

Used only for:

Confirmations

Quick actions

Image previews

Never build large forms inside modals.

---

# Animation Pattern

Allowed:

Fade

Slide

Scale

Image Zoom

Forbidden:

Bounce

Spin

Flash

Elastic

Animations explain movement.

Never decorate.

---

# Scroll Behaviour

Smooth scrolling.

Reveal on scroll.

Parallax only for hero imagery.

Never animate every element.

---

# Accessibility Pattern

Every interaction supports:

Keyboard

Screen Reader

Focus states

Reduced motion

ARIA

Always.

---

# Responsive Pattern

Mobile

↓

Tablet

↓

Desktop

↓

Ultra Wide

No breakpoint should require redesign.

---

# Reusable Sections

Approved reusable homepage blocks:

Hero

About Preview

Featured Animals

Breeding Philosophy

Achievements

Testimonials

FAQ

Latest Articles

Instagram

Newsletter

CTA

Footer

Do not invent new sections unless necessary.

---

# Visual Rhythm

Pages alternate between:

White

↓

Warm Gray

↓

White

↓

Dark CTA

This creates rhythm.

Never use the same background color for every section.

---

# Premium Rules

Whitespace is content.

Typography is branding.

Photography is the hero.

Animations are invisible.

Components are reusable.

Consistency beats creativity.

The interface should disappear.

The animals should remain.