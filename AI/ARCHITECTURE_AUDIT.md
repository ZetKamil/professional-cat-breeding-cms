# ARCHITECTURE_AUDIT.md

Version: 1.0
Status: ACTIVE
Owner: AI Architecture

---

# Purpose

This document defines architectural invariants of the project.

Every AI agent MUST read this document before implementing new features.

Its purpose is to prevent:

- architecture drift
- duplicated implementations
- inconsistent UI
- random Laravel patterns
- spaghetti controllers
- CSS chaos
- business logic leaks

Whenever architecture changes,
this document MUST be updated.

---

# Current Architecture

Project follows:

Laravel 12

Blade Components

Domain Oriented Architecture

Action Pattern

Repository-less architecture

Service classes only when needed

Thin Controllers

Reusable Blade Components

Design Token Driven UI

Apple/Aesop inspired Design System

---

# Layers

Presentation

↓

Controller

↓

Request Validation

↓

Action

↓

Model

↓

Database

No controller may skip Action layer.

---

# Controllers

Controllers ONLY:

authorize()

validate()

call Action

return response

Maximum complexity:

VERY LOW

Controllers must never contain:

business logic

database queries

transactions

file upload logic

email logic

complex loops

large if/else trees

---

# Actions

Business logic belongs here.

Example:

CreateAnimalAction

UpdateAnimalAction

CreatePostAction

DeleteMediaAction

etc.

Actions may:

use transactions

dispatch events

store files

call services

call models

Actions must remain focused.

One action = one responsibility.

---

# Models

Models contain:

relationships

casts

scopes

accessors

mutators

helper methods

No large business logic.

---

# Requests

Every POST/PATCH request uses FormRequest.

Validation NEVER inside Controller.

prepareForValidation()

authorize()

rules()

messages()

are preferred.

---

# Routes

Use resource routes whenever possible.

Backend:

/admin

Frontend:

/

Avoid custom endpoints unless necessary.

---

# Blade

Everything reusable becomes a component.

Never duplicate markup.

Preferred:

<x-frontend.card>

instead of

copy/paste html

---

# Component Hierarchy

Page

↓

Section

↓

Card

↓

Primitive

Example:

Home

↓

Featured Animals

↓

Animal Card

↓

Badge

↓

Button

---

# CSS

Architecture:

design-tokens.css

↓

ui-core.css

↓

components/

↓

pages/

No inline CSS.

No <style> blocks.

No duplicated values.

---

# Tokens

Spacing

Typography

Radius

Shadow

Transitions

Colors

must always come from tokens.

Never hardcode.

Bad:

padding:48px

Good:

padding:var(--sp-xl)

---

# Images

Always define:

width

height

loading

decoding

Example

loading="lazy"

decoding="async"

Prevent CLS.

---

# Typography

Headlines

Editorial

Body

Monospace metadata

No random font sizes.

---

# Animations

Allowed:

opacity

transform

filter

Forbidden:

layout animations

large transitions

heavy box-shadow animations

Animation duration:

150ms

250ms

400ms

Reduced Motion must always work.

---

# Accessibility

Every page must support:

keyboard

screen readers

ARIA labels

focus-visible

contrast

Reduced Motion

Semantic HTML

---

# Performance

Goals

LCP under 2.5s

CLS under 0.05

INP under 200ms

No unnecessary JS.

Minimal CSS.

---

# Design Rules

Premium

Calm

Minimal

Editorial

Luxury

Breathing room

Large whitespace

No Bootstrap feeling.

---

# Business Domains

Animal

Blog

Media

Users

Authentication

Settings

Each domain isolated.

---

# Media

Polymorphic

Single featured image

Gallery

Future video support

---

# Database

ULID

SoftDeletes

Enums

Indexes

Slug routing

No integer IDs.

---

# Public Pages

Home

About

Animals

Animal Detail

Blog

Article

Contact

404

Privacy

Cookies

Terms

Every page must use shared layout.

---

# Admin

Dashboard

Animals

Blog

Media

Users

Roles

Settings

Same component library.

---

# SEO

Every public page supports:

Meta Title

Meta Description

OpenGraph

Twitter Card

Canonical

Structured Data

Sitemap

Robots

---

# Testing

Every feature:

Feature Test

Policy Test

Request Test

Action Test (if complex)

Bug fixes require regression tests.

---

# Before Creating New Component

AI MUST ask:

Can existing component be reused?

If yes:

reuse

If no:

create reusable component.

Never duplicate UI.

---

# Before Creating CSS

AI MUST ask:

Can existing utility solve it?

Can token solve it?

Can component solve it?

Only then create CSS.

---

# Before Adding Dependency

Ask:

Can Laravel already do it?

Can Alpine do it?

Can Blade do it?

Can CSS do it?

Prefer native solution.

---

# Definition of Done

Feature is complete only if:

✔ Architecture respected

✔ Tests pass

✔ Responsive

✔ Accessible

✔ Performance maintained

✔ No duplicated CSS

✔ Components reusable

✔ Documentation updated

✔ TASKS updated

✔ DAILY_LOG updated

✔ DEV_JOURNAL updated

✔ Commit created

✔ Push to dev

---

# Current Technical Debt

Minor

Remaining work:

Premium CTA

Premium Footer

Accessibility audit

Performance audit

SEO audit

Admin UI polish

Settings module

Media Manager improvements

---

# Future Architecture

Future modules:

Reservations

Waiting List

Payments

Newsletter

CRM

Invoices

Breeding Planning

Medical Records

Analytics

Customer Portal

Each module follows identical architecture.

No exceptions.

---

# Golden Rule

Never implement the fastest solution.

Always implement the most maintainable solution.

Architecture is more important than speed.