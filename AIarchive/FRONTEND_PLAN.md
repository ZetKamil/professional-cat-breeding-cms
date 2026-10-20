# Plan wdrożenia Frontendu – Hodowla Kotów Rasowych (Luxury Cattery)

> **Dokumentacja stanu prac i planu działania**  
> **Data:** 28 lipca 2026  
> **Branch roboczy:** `dev`  
> **Estetyka:** *Apple / Aesop / Tesla* – minimalistyczny, luksusowy design, dużo przestrzeni (whitespace), stonowana kolorystyka, typografia Inter, autorski system CSS (Vanilla CSS + Design Tokens).  
> **Zasada naczelna:** *"Premium brands whisper. Cheap brands shout."*

---

## 1. Jakie były założenia i plan (Vision & Architecture)

1. **Cel główny**:  
   Zastąpienie starego, przestarzałego szablonu blogowego „Gazette” (opartego na Bootstrapie i jQuery) nowoczesnym, eleganckim i niezwykle prestiżowym frontendem dedykowanym luksusowej hodowli kotów rasowych.

2. **Strategia realizacji prac**:  
   Prace zostały podzielone na **samodzielne, w pełni funkcjonalne fazy**, a po każdej fazie kod jest commitowany i wysyłany na GitHub (branch `dev`). Dzięki temu projekt w każdym momencie działa i nie pozostaje w stanie „rozkopanym” w razie przerwania sesji.

3. **Filary projektowe**:  
   - **Design Tokens (CSS Custom Properties)**: Jedno źródło prawdy dla kolorów, typografii, odstępów i cieni w oparciu o `getdesign.md`.
   - **Komponenty Blade (`<x-frontend.*>`)**: Modułowość, rezygnacja z powtarzalnego kodu HTML.
   - **SEO & Dostępność (a11y)**: Dbałość o semantyczny HTML5, meta tagi Open Graph / Twitter Cards, skip-links, obsługę klawiatury i `prefers-reduced-motion`.

---

## 2. Co zostało już zrobione (Ukończone Fazy na GitHubie)

Wszystkie poniższe etapy zostały ukończone, przetestowane i zsynchronizowane na GitHubie na branchu `dev`:

### ✅ Faza 1 – Design System (Commit `8d63062`)
- **[DESIGN_SYSTEM.md](file:///c:/wamp64/www/katten/DESIGN_SYSTEM.md)** – kompletna dokumentacja systemu projektowego.
- **[resources/css/design-tokens.css](file:///c:/wamp64/www/katten/resources/css/design-tokens.css)** – ponad 680 linii autorskiego CSS ze zmiennymi:
  - Paleta kolorów (canvas, parchment, dark tiles, ink, primary, success/warning/error).
  - Skala typograficzna (Display, Tagline, Lead, Body, Utility/Nav) dla czcionki *Inter*.
  - Tokeny dla Spacingu (`--sp-xxs` do `--sp-4xl`), promieni zaokrągleń (`--r-sm` do `--r-full`) i animacji.
- **Vite & app.css** – integracja kompilacji przez `vite build`.

### ✅ Faza 2 – Główny Szkielet i Nawigacja (Commit `5dbb50a`)
- **[shell.blade.php](file:///c:/wamp64/www/katten/resources/views/components/frontend/shell.blade.php)** – główny layout strony z czcionką Inter, ikonami Lucide, skryptami Vite i wsparciem SEO.
- **[header.blade.php](file:///c:/wamp64/www/katten/resources/views/components/frontend/header.blade.php)** – przyklejony (sticky), czarny pasek nawigacyjny w stylu Apple z responsywnym menu (hamburger + pełnoekranowe menu overlay od breakpointu 834px).
- **[footer.blade.php](file:///c:/wamp64/www/katten/resources/views/components/frontend/footer.blade.php)** – stopka na tle *parchment* z 4-kolumnową siatką nawigacyjną, odnośnikami prawnymi i ikonami social media.

### ✅ Faza 3 – Komponenty Bazowe Blade (Commit `07bd36e`)
Stworzono zestaw uniwersalnych komponentów w folderze `resources/views/components/frontend/`:
- **`button.blade.php`** – przyciski wspierające 8 wariantów (primary, secondary, ghost, pearl, itp.), renderowanie jako `<a>` lub `<button>` z ikonami Lucide.
- **`section.blade.php`** – obudowa sekcji z 6 wariantami kolorystycznymi tła (light, parchment, dark, dark-2, dark-3, black) i padingami.
- **`section-header.blade.php`** – nagłówki sekcji (eyebrow, title, description) z wyrównaniem do środka lub lewej.
- **`card.blade.php`** – eleganckie karty ze slotem na zdjęcie, efektem uniesienia w hover i powiększeniem obrazu.
- **`badge.blade.php`** – odznaki (np. *Dostępny*, *Rezerwacja*, *Zdrowy*) w 5 wariantach kolorystycznych.
- **`cta.blade.php`** – sekcja wezwania do działania (Call to Action).

### ✅ Faza 4 – Strona Główna (Commit `483da7f` i `38f47e4`)
- **[resources/views/frontend/home.blade.php](file:///c:/wamp64/www/katten/resources/views/frontend/home.blade.php)** – nowa, prestiżowa strona główna zawierająca sekcje:
  1. **Hero** – pełnoekranowe powitanie z animacjami fade-in i wezwaniem do akcji.
  2. **Nasze Kocięta (Available Animals)** – 3-kolumnowa siatka prezentująca dostępne koty.
  3. **Dlaczego My (Trust Builders)** – 4 filary zaufania na ciemnym tle (Zdrowie, Rodowody FPL/FIFe, Wsparcie, Domowa atmosfera).
  4. **Opinie Klientów** – sekcja społecznego dowodu zaufania na tle parchment.
  5. **Najnowsze Artykuły z Bloga** – zintegrowana z bazą danych (wpisy z modelu `Post`).
  6. **Sekcja CTA** – zachęta do kontaktu.
- **[HomeController.php](file:///c:/wamp64/www/katten/app/Http/Controllers/Frontend/HomeController.php)** – uproszczenie kontrolera (usunięcie zbędnych 5 zapytań po starym blogu Gazette).
- **Poprawka stabilności (`38f47e4`)** – naprawienie wywołania relacji `media()` (`MorphOne`), dzięki czemu strona renderuje się stabilnie bez błędu 500.

### ✅ Faza 5 (Część 1) – Podstrony O Nas i Kontakt (Commit `b840e77`)
- **[about.blade.php](file:///c:/wamp64/www/katten/resources/views/frontend/about.blade.php)** – strona „O Nas”: redakcyjny hero, dwukolumnowa historia, numerowana lista wartości na ciemnym tle oraz siatka standardów (książeczka zdrowia, rodowód, badania DNA, szczepienia).
- **[contact.blade.php](file:///c:/wamp64/www/katten/resources/views/frontend/contact.blade.php)** – strona „Kontakt”: kafelki szybkiego kontaktu (e-mail, telefon, lokalizacja), formularz zapytania o kocięta z walidacją oraz sekcja FAQ (akordeon w czystym HTML `<details>`).
- **[web.php](file:///c:/wamp64/www/katten/routes/web.php) & [ContactController.php](file:///c:/wamp64/www/katten/app/Http/Controllers/ContactController.php)** – podłączenie obsługi wysyłki formularza i powiadomień po polsku.

---

## 3. Dlaczego na razie "nie wygląda super" (Diagnoza wizualna)

Strona jest poprawna technicznie, responsywna i elegancko sformatowana, ale w tym momencie **wygląda jeszcze jak surowy szkic / szkielet**, ponieważ:
1. **Brakuje prawdziwych, zjawiskowych zdjęć (Photography-First)**:  
   Luksusowy design (Apple, Aesop) opiera się w 80% na wybitnej fotografii. Obecnie w sekcji Hero, na kartach kociąt oraz w sekcji O Nas używamy **szarych placeholderów** (`<div class="placeholder-image">`). Dopiero wstawienie prawdziwych, wysokiej jakości zdjęć kotów ożywi tę stronę.
2. **Karty kotów używają statycznych zaślepek**:  
   W `home.blade.php` kocięta są generowane pętlą `@for($i = 1; $i <= 3; $i++)` z przykładowymi danymi ("Luna #1", "Luna #2").
3. **Brak podstrony oferty kotów i kart szczegółowych**:  
   Użytkownik nie może jeszcze wejść w podstronę konkretnego kota ani zobaczyć pełnej listy miotów.

---

## 4. Zaplanowane Następne Kroki (Roadmapa dla dalszej pracy)

Aby dokończyć frontend i zamienić go w w pełni funkcjonalną, zachwycającą aplikację, musimy wykonać następujące kroki:

### 🔹 KROK 1: Dokończenie Podstron Oferty (Faza 5 – c.d.)
- **Strona „Nasze Koty / Oferta” (`/animals` lub `/koty`)**:
  - Wyświetlanie siatki wszystkich kotów z filtrami: *Dostępne kocięta*, *Koty hodowlane*, *Planowane mioty*.
  - Odznaki ze statusem (Dostępny / Rezerwacja / W nowym domu).
- **Strona szczegółowa kota (`/animals/{slug}`)**:
  - Galeria zdjęć (główne zdjęcie + miniaturki).
  - Profil kota: rasa, wiek, rodzice, badania genetyczne, status.
  - Sekcja genealogiczna / rodowód.
  - Przycisk *"Zapytaj o tego kota"* – przenoszący do kontaktu ze wstępnie wypełnionym tematem.
- **Strona bloga (`/blog` i `/blog/{slug}`)**:
  - Widok listy artykułów edukacyjnych i profil wpisu ze zoptymalizowaną czytelnością tekstu (typografia redakcyjna).

### 🔹 KROK 2: Podłączenie Rzeczywistych Danych z Bazy (Faza 6 – CMS & Eloquent)
- Zastąpienie statycznych danych w widokach (`home.blade.php`, `about.blade.php`) danymi z modeli bazy danych:
  - Podłączenie modeli `Animal` / `Litter` (lub odpowiednich tabel ze struktur CMS-a).
  - Automatyczne pobieranie zdjęć z modułu `Media` (lub seedera z przykładowymi pięknymi zdjęciami).

### 🔹 KROK 3: Efekt "WOW" i Polerowanie Wizualne (Faza 7 – UI/UX Polish)
- **Zastąpienie placeholderów pięknymi zdjęciami testowymi (Seedowanie grafiki)**:
  - Wgranie wysokiej jakości zdjęć kotów (lub wygenerowanie ich / pobranie ze sprawdzonych darmowych banków zdjęć premium), aby na środowisku lokalnym (`katten.test`) strona od razu zapierała dech w piersiach.
- **Mikro-interakcje i dopracowanie detali**:
  - Subtelne efekty *glassmorphism* na pasku nawigacji.
  - Dokładne zbalansowanie kontrastów i typografii.
  - Przetestowanie całości na urządzeniach mobilnych pod kątem wygody dotyku (minimum 44px na przyciskach).

---

## 5. Jak weryfikować stronę lokalnie

1. Upewnij się, że w konsoli działa kompilacja skryptów:
   ```bash
   npm run dev
   ```
2. Otwórz w przeglądarce podpiętą domenę (np. w Herd / Valet / WAMP):
   - Strona główna: **http://katten.test/**
   - O Nas: **http://katten.test/about**
   - Kontakt: **http://katten.test/contact**
