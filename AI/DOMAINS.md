# DOMAINS.md

> Domain Architecture
> Project: Luxury Cattery Website & CMS
> Architecture: Domain-Oriented + Action Pattern
> Language: English (Codebase)

---

# Philosophy

The application is divided into independent business domains.

A domain owns its own:

- Models
- Actions
- Policies
- Services
- Livewire Components
- DTOs
- Enums
- Notifications
- Events
- Tests

Business logic must never leak between domains.

Dependencies should always point toward the owning domain.

---

# Current Domain Map

Core

Authentication

Administration

Animals

Media

Blog

SEO

Inquiry

Future

Litters

Products

Orders

Reservations

Customers

Payments

Newsletter

Analytics

API

---

# Domain Relationships

Authentication
    ↓

Administration
    ↓

Animals ←→ Media
    ↓
Inquiry

Blog ←→ Media
    ↓
SEO

Products ←→ Media
    ↓
Orders

Customers
    ↓
Reservations

Payments
    ↓
CRM

---

# Authentication Domain

Purpose

Authentication controls access to the application.

Responsibilities

Login

Logout

Password Reset

Email Verification

Remember Me

Sessions

Roles

Permissions

Owns

User

Role

Permission

Policies

UserPolicy

RolePolicy

Future

Two Factor Authentication

Social Login

Audit Logs

Never owns

Animals

Products

Blog

---

# Administration Domain

Purpose

Administration manages the CMS.

Responsibilities

Dashboard

Navigation

User Management

Role Management

Settings

Media Access

Owns

Admin Dashboard

Widgets

Settings

Logs

Future

Activity Timeline

System Health

Statistics

---

# Animals Domain

Purpose

Animals represent the breeder's cats.

This is the most important domain.

---

Responsibilities

Animal lifecycle

Availability

Pedigree

Gallery

Health

Achievements

Breeding Information

Public Presentation

Inquiry Entry Point

---

Owns

Animal

AnimalGallery

AnimalAchievement

AnimalHealth

AnimalStatus

AnimalGender

AnimalType

---

Actions

CreateAnimalAction

UpdateAnimalAction

DeleteAnimalAction

PublishAnimalAction

ArchiveAnimalAction

ReserveAnimalAction

SellAnimalAction

RetireAnimalAction

---

Services

AnimalImageService

PedigreeService

AnimalSeoService

---

Policies

AnimalPolicy

---

Livewire

AnimalIndex

AnimalShow

AnimalGallery

AnimalFilters

AnimalSearch

---

Future

Medical History

Vaccinations

DNA Tests

Certificates

Family Tree

Breeding Calendar

Litter Assignment

AI Recommendations

---

Relationships

Animal

↓

Gallery

↓

Parents

↓

Litters

↓

Inquiry

↓

Media

---

# Inquiry Domain

Purpose

Convert visitors into customers.

Responsibilities

Inquiry Form

Validation

Notifications

Status

Assignment

History

Owns

Inquiry

InquiryStatus

InquiryMessage

Actions

CreateInquiryAction

AssignInquiryAction

ReplyInquiryAction

CloseInquiryAction

Services

MailService

NotificationService

Future

CRM Integration

Lead Scoring

Automation

---

# Blog Domain

Purpose

Generate SEO traffic.

Educate visitors.

Build trust.

Responsibilities

Posts

Categories

Tags

Authors

Reading Time

SEO

Related Posts

Search

Owns

Post

Category

Tag

PostStatus

Actions

PublishPostAction

ArchivePostAction

GenerateSlugAction

Services

SeoService

ReadingTimeService

Future

Series

Authors

Comments

Video Articles

Downloads

---

# Media Domain

Purpose

Centralized media management.

Responsibilities

Images

Videos

Documents

Responsive Images

Optimization

Metadata

Cropping

Gallery Support

Owns

Media

MediaFolder

MediaVariant

Actions

UploadMediaAction

OptimizeMediaAction

GenerateVariantsAction

DeleteMediaAction

Services

ImageService

StorageService

OptimizationService

Future

AVIF

CDN

Watermarks

AI Cropping

---

# SEO Domain

Purpose

Provide search engine optimization.

Responsibilities

Meta Tags

OpenGraph

Canonical URLs

JSON-LD

Schema.org

Breadcrumbs

Sitemap

Robots

Redirects

Actions

GenerateMetaAction

GenerateSchemaAction

GenerateSitemapAction

Future

Automatic Internal Linking

SEO Audit

AI Suggestions

---

# Products Domain

Status

Future

Purpose

Premium breeder shop.

Responsibilities

Products

Inventory

Categories

Pricing

SEO

Gallery

Orders

Owns

Product

ProductCategory

ProductVariant

Inventory

Future

Subscriptions

Gift Cards

Bundles

---

# Litters Domain

Status

Future

Purpose

Manage breeding litters.

Responsibilities

Mother

Father

Birth Date

Kittens

Availability

Reservations

Gallery

Timeline

Relationships

Litter

↓

Animals

↓

Parents

↓

Media

Future

Pedigree Generation

---

# Reservations Domain

Status

Future

Purpose

Reserve animals.

Responsibilities

Reservation

Deposit

Status

Customer

Timeline

Future

Digital Contracts

Payments

---

# Customers Domain

Status

Future

Purpose

Manage breeder relationships.

Responsibilities

Profiles

Purchase History

Reservations

Documents

Notes

Future

CRM

Communication History

Automation

---

# Payments Domain

Status

Future

Purpose

Financial transactions.

Responsibilities

Stripe

PayPal

Invoices

Refunds

Receipts

Never store card data.

---

# Analytics Domain

Status

Future

Responsibilities

Traffic

Popular Animals

Popular Articles

Inquiry Funnel

Conversion Rate

SEO Performance

Future

AI Reports

Forecasts

Heatmaps

---

# API Domain

Status

Future

Purpose

Expose application services.

Responsibilities

REST API

Authentication

Tokens

Rate Limiting

Documentation

Webhooks

Future

GraphQL

SDK

---

# Cross-Domain Rules

Domains communicate through:

Actions

Services

Events

Notifications

Never through Blade.

Never through duplicated logic.

Never through Helpers.

---

# Shared Components

Reusable resources may be shared.

Examples

Media

Notifications

Mail

Storage

Localization

Enums

DTOs

These components must remain generic.

---

# Domain Independence

Each domain should:

Own its models.

Own its Actions.

Own its validation.

Own its tests.

Own its policies.

Avoid depending on unrelated domains.

Dependencies should always be intentional.

---

# Future Growth

Adding a new domain should never require changing existing domains.

The architecture should support continuous expansion while preserving:

- Maintainability
- Readability
- Scalability
- Testability
- Developer Experience

A new feature should feel like plugging a new module into an existing platform, not rewriting the platform itself.