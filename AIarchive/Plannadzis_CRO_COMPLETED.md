# ROLE & OBJECTIVE
Act as a Principal Full-Stack Developer & Conversion Rate Optimization (CRO) Architect. 
Your task is to refactor and expand the existing codebase (`professional-cat-breeding-cms` powering `kotyzmazowieckiejszwajcarii.pl`) to implement a high-converting, Mobile-First Sales Funnel, Live Video-Visit Reservation System, Attribution/Funnel Analytics (GA4 + Meta Pixel), and Programmatic Local SEO Landing Pages.

### ARCHITECTURE COMPATIBILITY — MANDATORY

The existing project uses Laravel 12, Livewire 4, Blade templates, Vite, PHP, and custom BEM/CSS styling.

- Do not introduce React, Next.js, TypeScript, Tailwind CSS, Alpine.js, or another framework unless the existing repository already uses it and the change is justified.
- Do not copy example file paths or environment-variable names blindly. Adapt all implementations to the actual repository.
- Reuse existing models, enums, routes, Livewire components, Blade layouts, validation, mail notifications, analytics, cookie-consent logic, and admin functionality wherever possible.
- Inspect the existing implementation before creating new files, database fields, migrations, components, or event systems.
- Preserve existing security controls, throttling, accessibility, and the current visual design system.
- If the requested functionality conflicts with the existing architecture, report the conflict and propose the smallest compatible solution before implementing it.

### LIVE AVAILABILITY AND BREEDING DATA

Treat all kitten names, counts, birth dates, availability statuses, expected pickup dates, vaccination claims, health-test claims, and descriptions as business data that must be verified against the current CMS/database and confirmed by the breeder.

- Never overwrite existing records or invent missing kitten details.
- Prefer existing admin-managed data over hardcoded names, counts, and dates.
- If the current CMS cannot represent upcoming litters or expected availability, propose the smallest backward-compatible extension.
- Display future availability separately from kittens ready for pickup.
- Calculate relative dates from verified data, not from hardcoded phrases such as "in 3 weeks".
- Never claim a vaccination, health test, pedigree, socialization milestone, or other benefit unless it is confirmed.
- If the required data is unavailable or contradictory, report the discrepancy instead of guessing.

## BUSINESS CONTEXT & RULES (STRICT COMPLIANCE REQUIRED)
1. **90% Mobile Traffic (In-App Browser):** Most users arrive on smartphones via the Facebook In-App Browser. All UI/UX changes must prioritize mobile thumb-zones, fast visual scanning, and deep-linking (`https://wa.me/...` and `tel:...`) that reliably breaks out of the FB In-App Browser.
2. **Zero Email Friction:** Users do not want to send emails to buy a kitten. The primary conversion actions are: **Phone Call (`+48 514 153 204`)**, **WhatsApp Live Video-Visit / Chat (`48514153204`)**, **Messenger Chat**, and a **1-Field Quick Phone Callback**.
3. **No Upfront Deposit/Price Mention in Reservation UI:** Do NOT mention "zadatek" (deposit) or prices inside the reservation modal. The breeder handles deposit details verbally during the phone/video call. Frame the reservation action around: *"Wideo-wizyta na żywo (WhatsApp / Messenger) i niezobowiązująca rozmowa o rezerwacji"*.
4. **Source Attribution in WhatsApp:** Every WhatsApp message generated from the site must dynamically append a tracking code at the end (e.g., `[Kod: fb_ads_bengale]` or `[Kod: Organiczny]`) based on captured URL UTM parameters, so the breeder immediately knows which ad/post generated the lead when reading her phone.
5. **No Google Maps Embeds with Reviews (Anti-Sabotage):** Do NOT integrate external Google Maps review widgets due to local competitor sabotage risks in Płock. Instead, build standalone Local SEO Landing Pages targeting satellite cities (Warszawa, Łódź, Toruń, Bydgoszcz/Włocławek).

---

## STEP-BY-STEP IMPLEMENTATION PLAN

Before writing any code, inspect the project structure (framework, routing, styling system, data store/CMS structure, and existing Google Analytics/Cookie consent setup). Execute the implementation in the following 6 sequential phases:

### PHASE 1: Attribution Engine & Funnel Analytics (`analytics` utility)
**Goal:** Track exactly where users come from (Facebook Ads, Sunday FB group posts, Google) and where they drop off in the funnel.

1. Create a centralized analytics & attribution module (e.g., `src/utils/analytics.ts` or `.js`):
   - **UTM Capture (`captureTrafficSource`):** On initial app load, parse URL parameters (`utm_source`, `utm_medium`, `utm_campaign`, `fbclid`). Store them in `sessionStorage` (and `localStorage` as fallback with a 7-day timestamp) so attribution persists as the user navigates across pages.
   - **Source Label Formatter (`getTrafficSourceLabel`):** Return a clean string (e.g., `fb_ads (miot_jesien)` or `FB_Post` or `Google/Bezpośredni`) to be appended to WhatsApp pre-filled messages and callback forms.
   - **Funnel Event Dispatcher (`trackFunnelStep`):** Create a unified function that fires events to both **Google Analytics 4 (`window.gtag`)** and **Meta Pixel (`window.fbq`)**:
     - `view_cat_profile` – fired when a user views a specific cat's page (payload: `cat_name`, `cat_breed`, `availability_status`, `traffic_source`).
     - `open_reservation_modal` – fired when a user clicks any "Zapytaj / Zarezerwuj / Wideo-wizyta" CTA button (tracks intent before contact).
     - `click_whatsapp_lead` – fired when user clicks the WhatsApp CTA (also fires `fbq('track', 'Lead')`).
     - `click_messenger_lead` – fired when user clicks the Messenger CTA (also fires `fbq('track', 'Lead')`).
     - `click_phone_lead` – fired when user clicks the `tel:+48514153204` CTA (also fires `fbq('track', 'Lead')`).
     - `submit_callback_number` – fired when user submits their phone number in the quick 1-field callback form (also fires `fbq('track', 'Lead')`).
2. **Meta Pixel Integration:**
   - Add support for Meta Pixel in the main document head / layout (respecting existing cookie banner / consent logic or configuring it alongside GA4 via environment variable `NEXT_PUBLIC_META_PIXEL_ID` / `VITE_META_PIXEL_ID`).
3. Hook `captureTrafficSource()` into the root layout/app component so it runs on every landing page entry.

---

### PHASE 2: Mobile-First Navigation & Sticky Bottom Action Bar
**Goal:** Ensure 90% of mobile visitors have instant, zero-scroll access to Phone and WhatsApp Video-Visits in their thumb zone.

1. Create a `MobileStickyBar` component visible only on mobile/tablet viewports (`md:hidden`):
   - Fixed at the bottom of the viewport (`fixed bottom-0 left-0 right-0 z-40`), styled to match the site's premium aesthetic (subtle backdrop blur, clean top border, safe-area padding).
   - **Context-Aware Props:** If rendered on a specific cat's profile page (`/koty/[slug]`), pass `catName` and `catBreed` so the WhatsApp message automatically references that specific cat.
   - **Button 1 (Primary Green/Brand):** `📹 Wideo-wizyta / WhatsApp` -> Deep-links to `https://wa.me/48514153204?text=...` with pre-filled text including `catName` (if present) and `[Kod: ${getTrafficSourceLabel()}]`. Fires `trackFunnelStep('click_whatsapp_lead', ...)`.
   - **Button 2 (Dark/Gold):** `📞 Zadzwoń` -> Links to `tel:+48514153204`. Fires `trackFunnelStep('click_phone_lead', ...)`.
2. Add bottom padding (`pb-20 md:pb-0`) to the main layout/footer so the sticky bar never obscures footer content.
3. **Navigation Consistency:** Unify navigation links across all headers/footers so "Blog" / "Baza Wiedzy" is consistently named **"Baza Wiedzy"**.

---

### PHASE 3: The Reservation & Live Video-Visit Modal (100% Lead Capture)
**Goal:** Replace passive routing to `/kontakt` (which yielded 0 email leads) with an interactive, high-converting Reservation & Video-Visit Modal.

1. Create a `ReservationModal` component:
   - Accepts props: `isOpen`, `onClose`, `catName`, `catBreed`, `statusLabel` (e.g., *"Gotowy do odbioru"* or *"Wczesna rezerwacja – odbiór za 3 tyg."*).
   - **Copywriting & Positioning:**
     - Header: `Rezerwacja i Wideo-wizyta: {catName}` (or general breeding inquiry if opened from a generic CTA).
     - Subtext: Emphasize live connection — *"Zobacz kociaka na żywo przez kamerę w telefonie (WhatsApp lub Messenger) albo porozmawiajmy głosowo. Pokażemy Ci malucha w domowych warunkach, odpowiemy na pytania i wspólnie ustalimy szczegóły rezerwacji."* (Strictly NO mention of deposits/zadatek).
   - **4 Instant Capture Paths inside the Modal:**
     1. **WhatsApp Video/Chat CTA:** Pre-filled `https://wa.me/48514153204?text=...` containing cat name, status, and `[Kod: ${source}]`.
     2. **Messenger Video/Chat CTA:** Link to Facebook Messenger (`https://m.me/...` - make the FB page slug configurable in constants) with tracking.
     3. **Direct Phone Call CTA:** `tel:+48514153204` (`Zadzwoń teraz: +48 514 153 204`).
     4. **1-Field Quick Callback Form ("Nie możesz teraz rozmawiać? Zostaw sam numer telefonu – zadzwonimy lub napiszemy na WhatsApp"):**
        - Single input field: Phone number (validation: minimum 9 digits) + Submit button (`Proszę o kontakt`).
        - Connects to the existing contact/form submission handler (or API route), sending: `phone`, `catName`, `trafficSource`, and a default message `"Szybka prośba o kontakt telefoniczny / WhatsApp ze strony"`.
2. **Wire up CTAs across the site:**
   - On individual cat profile pages (`/koty/[slug]`), replace the primary *"Zapytaj o tego kota"* / *"Zapytaj o [Imię]"* link with a button that triggers `open_reservation_modal` and opens `ReservationModal` pre-populated with that cat's data.
   - Add a prominent **"📹 Umów 5-minutową Wideo-wizytę na żywo"** callout box on every cat profile page and on `/kontakt`.

---

### PHASE 4: Mobile Homepage Refactor & Multi-Stage Litter Catalog (Ready Now vs. Early Reservation)
**Goal:** Stop mobile users from scrolling 5 times before seeing cats, and support two distinct sales priorities:
- **Priority A (Immediate Sale):** 3 older Bengal kittens (1 from May, 2 from June – *Baron, Cyprian, Carlos*) that are fully socialized, vaccinated, and ready for immediate pickup.
- **Priority B (Early Reservation Pipeline):** Newly born litters – 3 British Shorthair kittens (ready in 3–4 weeks) and 4 Siamese kittens (ready in 6 weeks).

1. **Mobile Homepage Layout Optimization:**
   - Make the mobile Hero section compact (concise headline, trust badges: *SHiOZ ZOOLANDIA • Badania HCM/PKD • Wideo-wizyty na żywo*, and immediate CTA).
   - Move the **"Dostępne Kocięta i Nowe Mioty"** section directly below the Hero section on mobile viewports (before long breed descriptions) so users coming from Facebook ads see kittens within 1 scroll.
2. **Enhanced Cat Status Badges & Copywriting Support in CMS/Data Model:**
   - Support extended availability statuses and badges on cat cards and profiles:
     - `available_now`: Badge **"🟢 Gotowy do odbioru • Odchowany"** (Highlight benefits for 4-5 month old Bengals: *pełna odporność poszczepienna, 100% nauczony czystości, ukształtowany i przewidywalny charakter*).
     - `early_reservation`: Badge **"⏳ Wczesna rezerwacja • Odbiór za X tyg."** (For the 3 British kittens – 3-4 weeks, and 4 Siamese kittens – 6 weeks).
     - `reserved`: Badge **"🔒 Zarezerwowany"**.
   - Ensure the catalog/CMS data structure includes entries or easy configuration for:
     - The 3 ready Bengal males (*Baron, Cyprian, Carlos*) with updated benefit-driven copy.
     - The upcoming British Shorthair litter (3 kittens – available in 3–4 weeks, open for early reservation & live video viewing).
     - The upcoming Siamese litter (4 kittens – available in 6 weeks, open for early reservation & live video viewing).

---

### PHASE 5: Interactive Mobile Quiz ("Dobierz Rasę w 45 Sekund")
**Goal:** Engage cold/warm weekend traffic from Facebook groups and convert undecided mobile visitors into qualified WhatsApp/Phone leads.

1. **Multi-Criteria Behavioral Scoring Algorithm:**
   - 4-step progressive evaluation:
     - *Krok 1 (Styl życia & Poziom Energii):* Spokojny relaks na kanapie vs. Aktywność i inteligencja lamparta vs. Niezwykle głęboka więź i dialog.
     - *Krok 2 (Czas poza domem & Domownicy):* Praca poza domem (7-9h - kot brytyjski znosi to doskonale, syjam cierpi na lęk separacyjny) vs. Praca zdalna / stała obecność vs. Rodzina z dziećmi (cierpliwość i stabilność) vs. Dom z innymi zwierzętami.
     - *Krok 3 (Pielęgnacja & Cechy Szaty):* Krótka gładka sierść bez podszerstka (zero czesania) vs. Gęsty plusz vs. Smukła orientalna elegancja.
     - *Krok 4 (Horyzont czasowy gotowości):* Gotowy do odbioru od zaraz vs. Wczesna rezerwacja z nowego miotu (3-6 tyg.) vs. Elastycznie.
   - Calculates weighted scores across Bengal, British Shorthair, and Siamese, outputting the winning breed alongside a personalized behavioral explanation.
2. **Real-time CMS Availability & Honesty Check:**
   - Automatically cross-references the winning breed and chosen timeline with published animals in the CMS database.
   - If matching kittens exist: displays matching cards with immediate "📹 Zobacz na żywo" button.
   - If NO kittens match that breed/timeline: displays a warm, honest notice (*"Aktualnie maluchy tej rasy z tego terminu znalazły już domy lub oczekujemy na najbliższe narodziny nowego miotu"*) and direct WhatsApp CTA to join the waiting list for upcoming litters.
3. **Frictionless Contact Handoff:**
   - Pre-fills WhatsApp message: `Dzień dobry! Rozwiązałem/am quiz doboru rasy na kotyzmazowieckiejszwajcarii.pl. Mój wynik to: [Rasa]. Chciał(a)bym porozmawiać o dostępności i wideo-wizycie. [Kod: ...]`.
   - Allows requesting a quick callback with the pre-populated breed name.
   - Zero login, zero registration, and zero upfront personal data required to take the quiz and view results.

---

### PHASE 6: Programmatic Local SEO – Satellite Cities ("Miasta Stołeczne i Satelickie")
**Goal:** Capture high-budget organic search traffic from major surrounding cities without relying on a local Płock Google Maps listing.

1. **Curated Target Cities:**
   - `/hodowla-kotow-warszawa` (Warszawa & aglomeracja warszawska – ~80–90 min)
   - `/hodowla-kotow-lodz` (Łódź & aglomeracja łódzka – ~80–90 min przez A1/DK60)
   - `/hodowla-kotow-torun` (Toruń & Kujawy – ~70–75 min przez DK91/DK62)
   - `/hodowla-kotow-wloclawek` (Włocławek – ~35–40 min przez DK62)
   - `/hodowla-kotow-bydgoszcz` (Bydgoszcz – ~1h 35–40 min)
2. **Strict Quality, Honesty & Anti-Spam SEO Rules:**
   - **Jasna lokalizacja fizyczna:** Wyraźne informowanie użytkowników i wyszukiwarek, że hodowla fizycznie mieści się w Sikorzu 56A k. Płocka (woj. mazowieckie). Brak fikcyjnych placówek, wirtualnych biur czy nieprawdziwych wizytówek.
   - **Brak atakowania konkurencji:** Całkowite usunięcie negatywnych sformułowań o "ciasnych hodowlach w miejskich blokach". Skupienie na pozytywnych przewagach własnych: przestronny dom w otoczeniu Brudzeńskiego Parku Krajobrazowego, domowa socjalizacja, badania kardiologiczne HCM i genetyczne.
   - **Unikalne treści i praktyczne wskazówki:** Każde miasto posiada specyficzne wskazówki dojazdu (trasy S7, A1, DK62, DK60), czas podróży, porady dotyczące bezpiecznego przewozu malucha do danego miasta oraz dedykowaną sekcję FAQ.
   - **Weryfikacja JSON-LD Schema:** Obiekt `LocalBusiness` z rzeczywistym adresem w Sikorzu, zakresem obsługi (`areaServed`) wskazującym na miasto docelowe, bez nieprawdziwych ocen ani fikcyjnych recenzji.
   - **Rzetelne linkowanie wewnętrzne:** Subtelna sekcja w stopce globalnej łącząca kluczowe miasta satelickie.

---

## PILLARS ADDED FOR ENTERPRISE-GRADE GOVERNANCE

### 1. Bezpieczne Wdrożenie Produkcyjne (Production Deployment Protocol)
- **Osobna gałąź Git:** Prace realizowane w izolowanych gałęziach funkcyjnych przed merge'em do gałęzi głównej.
- **Weryfikacja `git status`:** Sprawdzenie czystości drzewa roboczego przed każdym commitem; żadne pliki pomocnicze ani tymczasowe nie mogą trafić do repozytorium.
- **Ochrona konfiguracji i danych użytkowników:** Kategoryczny zakaz nadpisywania plików `.env`, `storage/app/public` oraz przesłanych zdjęć/dokumentów.
- **Zakaz nieautoryzowanych migracji produkcyjnych:** Agent nigdy nie uruchamia niszczących migracji bazy danych ani poleceń `migrate:fresh` na środowisku docelowym bez pisemnej autoryzacji.
- **Kompletna weryfikacja testów:** Zawsze uruchamiany pełny zestaw testów automatycznych (`php artisan test`) przed commitem i deploymentem.

### 2. Kryteria Odbioru dla Każdej Fazy (Acceptance Criteria)
- **Testy RWD (320px, 375px, 390px):** Bezwzględny brak poziomego przewijania (`overflow-x: hidden`), czytelna typografia i łatwe klikanie przycisków w strefie kciuka.
- **Sprawne formularze i walidacja:** Każdy formularz posiada walidację serwerową (Laravel FormRequest), zabezpieczenie CSRF, honeypot przeciw botom oraz limitowanie żądań (`throttle`).
- **Działające linki i deep-linki:** Poprawność linków `tel:`, WhatsApp `https://wa.me/...` oraz wewnętrznych URL-i (brak błędów 404 lub pętli przekierowań).
- **Czysta konsola przeglądarki:** Zero błędów JavaScript w konsoli (`window`, SSR-safe, obsługa braku Pixela/adblockerów).
- **Brak regresji:** Wszystkie istniejące funkcjonalności CMS, autoryzacji i logiki zwierząt muszą zachować 100% poprawności.

### 3. Wydajność Mobilna (Mobile Performance & Ergonomics)
- **Optymalizacja zasobów:** Grafiki serwowane w nowoczesnych formatach (WebP, responsywne srcset), lazy-loading obrazów poza pierwszym ekranem (LCP optimization).
- **Ergonomia Sticky Bara:** Przyklejony pasek dolny (`mobile-sticky-bar`) nie może zasłaniać treści footerów ani przeszkadzać w obsłudze formularzy/modali (automatyczne ukrywanie przy otwarciu modalu przez klasę `is-modal-open`).

### 4. Raportowanie Wyników Biznesowych (Business ROI & Funnel Tracking)
- **Prosta ewidencja konwersji:**
  - Wydatki reklamowe (Meta Ads / kampanie lokalne).
  - Wartościowe zapytania (`callback_submitted`, kliknięcia WhatsApp/telefon z kodem UTM).
  - Umówione wideo-wizyty na żywo.
  - Potwierdzone rezerwacje miotów.
  - Finalna sprzedaż kociąt.
- Zapewnienie, że analityka mierzy rzeczywiste zaangażowanie i konwersje serwerowe, a nie puste odsłony czy przypadkowe kliknięcia.