# 🐾 Professional Cattery CMS & Editorial Platform

> **Live Production Architecture & Custom CMS built for *Hodowla Kotów z Mazowieckiej Szwajcarii***  
> A high-performance, security-hardened, luxury editorial platform and full-featured backend CMS built with **Laravel 12**, **Livewire 4**, **PHP 8.2+**, and **Vite 7**.

---

## 🌟 Executive Summary

This repository contains a full-stack, enterprise-grade content management system and luxury frontend application designed for a premium cat breeding business (*Hodowla Kotów z Mazowieckiej Szwajcarii*). 

Built to demonstrate modern Laravel software engineering best practices, this application features a **domain-driven architecture**, **strict security hardening**, **event-driven notifications**, **polymorphic media handling**, **automated GDPR/RODO compliance**, and an extensive suite of **64 automated feature tests**.

---

## 🚀 Key Architectural & Engineering Highlights

### 🎨 1. Luxury Frontend & Modern Design System
- **Tailor-made BEM CSS Engine**: Zero generic utility frameworks; built with raw CSS, CSS Custom Properties (HSL color tokens), and modular BEM methodology.
- **Editorial Aesthetics**: Tailored typography (Google Fonts *Inter* & *Outfit*), dynamic glassmorphism, fluid micro-interactions, and responsive layout boundaries (supporting 320px mobile up to 4K displays).
- **Interactive Catalog**: Filterable cat & kitten showcase supporting real-time filtering by breed, lifecycle status, and gender.

### ⚙️ 2. Domain-Driven Backend & Custom CMS
- **Real-Time Analytics Dashboard**: Custom `DashboardController` tracking active breeding program metrics (available kittens, reserved, breeding parents, retired, published blog posts, and media library counts).
- **Domain Enums & Type Safety**: Native PHP 8.2 backed Enums (`AnimalStatus`, `AnimalGender`, `AnimalType`) powering state transitions, UI badge variants, and public visibility logic.
- **Polymorphic Media Library**: Custom `MediaService` supporting single/multiple file uploads, metadata management (`alt` tags), hot-swapping images, and physical disk cleanup on deletion.
- **Event-Driven Architecture**: Decoupled mail notification pipeline (`ContactMessageSent` event &rarr; `SendContactMessageMail` listener &rarr; `ContactMessageNotification` mailer).

### 🛡️ 3. Security & Compliance Hardening
- **Strict Validation & Rate Limiting**: Hardened `ContactFormRequest` (`email:rfc`, max length checks, min body bounds) backed by `throttle:5,1` middleware protection against Spam/Brute-Force.
- **GDPR / RODO Compliance**: Technical privacy audit verified; automated cookie table (`session` & `XSRF-TOKEN`), zero unconsented third-party trackers, and automated email retention policy (30-day retention).
- **Auth & Session Security**: 2FA (Two-Factor Authentication via Fortify), Bcrypt password hashing, `SESSION_SECURE_COOKIE` + `SESSION_HTTP_ONLY`, CSRF middleware protection.
- **Custom Exception UI**: Branded error pages for `403 Forbidden`, `419 Session Expired`, and `429 Too Many Requests`.
- **Shared Hosting Symlink Bypass**: Built-in HTTP media streaming pipeline (`/storage/media/{filename}`) and automated link repair (`/fix-storage`) to ensure zero broken images even on restrictive Apache shared hosts without SSH access.

---

## 🧪 Automated Testing & Quality Assurance

The codebase includes an extensive suite of **64 automated feature tests** (`194 assertions`) covering:

- **Authentication & Security**: Login, logout, password resets, email verification, 2FA confirmation, CSRF protection.
- **Contact & Rate Limiting**: Form submission, RFC email validation, min/max character boundaries, throttle limit triggers.
- **Media Library**: Single & bulk upload, metadata update, image hot-swapping, disk removal.
- **Frontend & Catalog**: Catalog rendering, breed filtering, blog search, published/unpublished scoping, 404 handling.

Run the test suite locally:
```bash
php artisan test
```

---

## 🛠️ Tech Stack & Ecosystem

| Layer | Technology |
|---|---|
| **Framework** | Laravel 12.x |
| **Language** | PHP 8.2+ |
| **Frontend Dynamic** | Livewire 4.x / Blade |
| **Asset Pipeline** | Vite 7.x |
| **Authentication** | Laravel Fortify (with 2FA) |
| **Styles & CSS** | Custom BEM CSS / HSL Tokens |
| **Database** | SQLite (Dev/Test) / MySQL (Production) |
| **Icons** | Lucide Icons & FontAwesome 6 |

---

## 📦 Local Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/ZetKamil/professional-cat-breeding-cms.git
   cd professional-cat-breeding-cms
   ```

2. **Install PHP & Node dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Link Public Storage**:
   ```bash
   php artisan storage:link
   ```

6. **Build Frontend Assets & Run Local Server**:
   ```bash
   npm run build
   php artisan serve
   ```

7. **Run Automated Test Suite**:
   ```bash
   php artisan test
   ```

---



## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).
