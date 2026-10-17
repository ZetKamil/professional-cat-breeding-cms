# DAILY_LOG.md

## 2026-10-17

### Completed

✓ Uproszczenie i Pełna Indywidualizacja Dat Odbioru Kociąt (`refactor(animals)`, `refactor(cms)`, `fix(db)`)
  - **Usunięcie Wymuszonej Daty Zbiorczej:**
    - Utworzono migrację `2026_10_17_130000_reset_british_kittens_pickup_dates.php` czyszczącą masowo wpisane daty dla brytyjczyków do wartości `NULL`.
    - Kociaki wracają do 100% czystego stanu – każde ma własne, niezależne pole i liczy się ze standardowej daty urodzenia (12 tyg.), dopóki hodowca sam nie ustali mu indywidualnej daty.
  - **Uproszczenie Formularza w CMS:**
    - Usunięto skomplikowany, techniczny boks alertu z informacjami o 2-tygodniowych oknach.
    - Zostawiono 2 proste, intuicyjne pola: `Data urodzenia` (z podglądem: Wiek i kiedy mija 12 tyg.) oraz `Indywidualna data odbioru (opcjonalnie)`.
  - **Przejrzysta Prezentacja na Profilu Kota:**
    - Zastąpiono sztuczny zakres „14.11.2026 – 28.11.2026” czytelną dla człowieka informacją: `Od 14.11.2026 (za ok. 4 tyg.)`.

✓ Wdrożenie Opcjonalnej Najwcześniejszej Daty Odbioru z Automatycznym Fallbackiem (`feat(animals)`, `feat(cms)`, `test(frontend)`)
  - **Mechanizm `earliest_pickup_date` w Modelu `Animal`:**
    - Dodano kolumnę `earliest_pickup_date` (nullable date) w tabeli `animals` za pomocą bezpiecznej migracji `2026_10_17_120000_add_earliest_pickup_date_to_animals_table.php`.
    - Skonfigurowano termin odbioru kociąt brytyjskich (`ada`, `alma`, `atena`) na **2026-11-14** (za 4 tygodnie od bieżącej daty `2026-10-17`).
    - Zaimplementowano metodę `earliestPickupDate()` z transparentnym fallbackiem: jeśli hodowca nie poda daty, system automatycznie wylicza termin ze standardowej matematyki felinologicznej (`date_of_birth + 12 tyg.`).
    - Zsynchronizowano metody `weeksUntilPickupWindow()`, `pickupWindow()`, `isEarlyReservation()`, `isReadyNow()`, `isInPickupWindow()`, `pickupBadgeLabel()` oraz `pickupTimelineLabel()`.
  - **Panel CMS Hodowcy (`backend/animals/partials/form.blade.php`):**
    - Dodano pole edycyjne `Najwcześniejsza data odbioru (opcjonalnie)` obok `Daty urodzenia`.
    - Dodano pomocniczy boks informacyjny na żywo prezentujący aktualnie obliczone okno, status oraz czy data pochodzi z własnego wpisu czy standardowej matematyki.
    - Zaktualizowano `StoreAnimalRequest`, `UpdateAnimalRequest`, `CreateAnimalAction` oraz `UpdateAnimalAction`.
  - **Prezentacja na Wizytówkach i Profilach Kociąt:**
    - Wizytówki i profile poprawnie prezentują status „Dostępny do rezerwacji” oraz „Odbiór: za ok. 4 tyg.” wraz z oknem odbioru 14.11.2026 – 28.11.2026.
  - **Testy Automatyczne:**
    - Utworzono testy funkcjonalne `tests/Feature/Frontend/EarliestPickupDateTest.php` (5 testów, 41 asercji, 100% PASS).
    - Zweryfikowano brak regresji w całym projekcie: 180 testów, 1464 asercje PASS.

✓ Wdrożenie Pełnego Profilu i Mediów Kocura Atosa (`feat(animals)`, `feat(media)`, `test(frontend)`)
  - **Zdjęcia i Media Atosa:**
    - Wykadrowano zdjęcie profilowe `image/Brytyjczyki/Kociaki/Atos/profilowe.jfif` do idealnego kwadratu 1:1 (1536x1536, `parent_atos_1.jpg`) z pełnym wyeksponowaniem uszu, pyszczka i szafirowych oczu bez ucinania w kartach i profilu.
    - Przypisano zdjęcie galeryjne `parent_atos_2.jpg` (z aniołkiem na czerwonym aksamicie z `9286a117-c510-41a9-aa87-81fc0c49e1aa.jfif`).
    - Wygenerowano zoptymalizowane warianty WebP (400px, 800px, 1200px) za pomocą `images:optimize`.
  - **Bezpieczna Migracja Idempotentna (`2026_10_17_110000_attach_atos_photos.php`):**
    - Przypisano media do profilu Atosa (`atos`, Kot Brytyjski Długowłosy BLH a 33, reproduktor, sort_order: 33, matka Nel).
    - Zsynchronizowano pliki w `storage/app/public/media/` oraz `public/storage/media/`.
  - **Weryfikacja i Testy:**
    - Utworzono testy automatyczne w `tests/Feature/Frontend/AtosProfileTest.php` (100% PASS, 16 asercji).
    - Zweryfikowano działanie stron `/koty/atos`, `/koty` oraz panelu edycji `/backend/animals/atos/edit`.

## 2026-10-16

### Completed

✓ Wdrożenie Reużywalnego Modułu Kadrowania Zdjęć (Image Cropper Module) w CMS (`feat(cms)`, `feat(ui)`, `test(backend)`)
  - **Reużywalny Komponent Blade `<x-backend.image-cropper>`:**
    - Utworzono uniwersalny komponent modalu kadrowania bazujący na bibliotece Cropper.js z globalnym interfejsem JavaScript `window.openImageCropper(...)`.
    - Obsługa predefiniowanych proporcji: **1:1 Kwadrat (Wizytówka / Profilowe)**, **4:3 Portret (Galeria)**, **16:9 (Baner / Blog)** oraz **Swobodny**.
    - Interaktywne narzędzia manipulacji: płynny zoom (+/-), obrót o 90° w lewo/prawo, odbicie lustrzane w poziomie (Flip H) oraz reset do oryginału.
    - **Podgląd na żywo (Live Preview):** Równoległy podgląd w kształcie karty kota (zaokrąglenie rogów 14px, cień) oraz okrągłego awatara z wyświetlaniem wymiarów kadru w czasie rzeczywistym.
  - **Integracja z Formularzem Kota (`form.blade.php`):**
    - Dodano przycisk `[ ✂ Dopasuj kadr obecnego zdjęcia ]` przy głównym zdjęciu kota – hodowca może w dowolnej chwili skorygować kadr bez konieczności ponownego wyszukiwania i wgrywania pliku z telefonu.
    - Automatyczne wywoływanie kadrownika 1:1 natychmiast po wybraniu nowego pliku z dysku z możliwością późniejszej korekty przyciskiem `[ ✂ Popraw / Zmień kadr ]`.
    - Płynny eksport do formatu JPEG/WebP o wysokiej rozdzielczości (Blob + File API DataTransfer z bezpiecznym fallbackiem base64 `cropped_image`).
  - **Backend & Walidacja (`StoreAnimalRequest`, `UpdateAnimalRequest`, `MediaService`):**
    - Wzbogacono FormRequesty o dekodowanie payloadu `cropped_image` w `prepareForValidation` i automatyczne wstrzykiwanie bezpiecznego obiektu `UploadedFile` do kolekcji plików walidacji.
    - `MediaService->replace()` zapewnia flagę `is_featured = true` dla głównego zdjęcia oraz automatyczne przejście przez `ImageOptimizerService`.
  - **Aktualizacja Stylów Wizytówek (`animals.css`):**
    - Zastąpiono sztywne `object-position: center 18%` elastyczną zmienną `var(--animal-card-img-position, center center)`, co w połączeniu z wykadrowanym plikiem 1:1 gwarantuje 100% widoczności sylwetki, uszu i pyszczka bez ucinania.
  - **Rozwiązanie problemu kotki Daisy:**
    - Wykadrowano pionowe zdjęcie Daisy (1536x2048) do idealnego kwadratu 1536x1536 z pełnym wyeksponowaniem uszu i pyszczka.
  - **Testy automatyczne:**
    - Utworzono testy funkcjonalne w `tests/Feature/Backend/AnimalImageCropTest.php` (100% PASS, 10 asercji).

## 2026-10-15

### Completed

✓ Kompleksowa Naprawa Kadrowania i Rozmiarów Zdjęć Kotów (`fix(ui)`, `style(images)`, `perf(rwd)`)
  - **Eliminacja ucinania głów i uszu w Kartach Kotów (`animal-card`):**
    - **Diagnoza problemu:** Zdjęcia kociąt wgrywane z telefonów są w formacie pionowym (3:4, np. 1536x2048). Sztywny poziomy kontener `aspect-ratio: 4 / 3` oraz wyśrodkowanie `object-position: center (50%)` obcinało aż 30% góry i dołu zdjęcia, ścinając kotom głowy na wysokości czoła.
    - **Wdrożone rozwiązanie:**
      1. Zmieniono proporcje `.animal-card__media-wrapper` ze spłaszczonego 4:3 na **idealny kwadrat `1 / 1`** (standard Instagrama / Apple), co daje o 33% więcej przestrzeni pionowej na sylwetkę kota.
      2. Wprowadzono inteligentne pozycjonowanie **`object-position: center 18%`**, które traktuje górną część zdjęcia jako punkt skupienia, zabezpieczając 100% uszu, oczu i pyszczka kociaka.
      3. Zsynchronizowano szkielet ładowania `.skeleton-card__image` do `aspect-ratio: 1 / 1` oraz wymiary obrazów na `800x800` (eliminacja CLS).
  - **Naprawa ucinania kota w sekcjach Hero (np. „O Hodowli”, „O Nas”):**
    - **Diagnoza problemu:** W `hero.css` reguła `.hero__image` miała sztywne `object-position: center 30%`, przez co przeglądarka kadrowała na sufit i wazony, a kot siedzący na stole w dolnej części kadru był odcięty w pasie przy krawędzi ekranu.
    - **Wdrożone rozwiązanie:**
      1. Wprowadzono zmienną CSS `var(--hero-image-position, center 65%)` w `hero.css`.
      2. Dodano prop `imagePosition` do komponentu `<x-frontend.hero />`.
      3. Ustawiono `image-position="center 68%"` w `cattery.blade.php` oraz `center 60%` w `about.blade.php`, co przeniosło środek kadru idealnie na kota, eksponując go w całości.
      4. Zabezpieczono kadr reproduktora Barsa w Hero strony głównej (`.hero-split__image` z `object-position: center 25%`).
      5. Zabezpieczono okrągłe awatary rodziców w rodowodzie (`animal-pedigree.blade.php` z `object-position: center 20%`).
  - **Kompilacja i testy:**
    - Zrekompilowano pakiety Vite (`npm run build`).
    - Wszystkie testy automatyczne przeszły pomyślnie (`HomepageTest`, `AnimalTest`).

✓ Zwiększenie liczby prezentowanych kociąt na Stronie Głównej do 6 (`feat(home)`, `feat(cro)`, `test(home)`)
  - **Optymalizacja siatki Dostępnych Kociąt:**
    - W `HomeController.php` zwiększono limit `take(3)` do `take(6)`.
    - Po odchudzeniu strony głównej z długich sekcji tekstowych zwolniła się przestrzeń na wyeksponowanie pełnej oferty kociąt.
    - Na desktopie siatka `.animals-grid` układa się teraz w 2 równe rzędy po 3 karty (obecnie wyświetlają się zbalansowane mioty: Ada i Alma - brytyjskie, Dafne i Daisy - syjamskie, Candy i Carlos - bengalskie).
    - Wszystkie 6 kociąt posiada bezpośrednie przyciski wideo-rozmowy na żywo i rezerwacji.
  - **Pokrycie testami:**
    - Rozszerzono `test_homepage_renders_featured_animals_when_available` w `tests/Feature/Frontend/HomepageTest.php`, aby weryfikować renderowanie 6 kociąt w rzędzie (4/4 testy pass, 27 asercji).

✓ Ujednolicenie Dolnego CTA w całym serwisie & Przeniesienie Opinii do „O Hodowli” (`feat(cta)`, `feat(cro)`, `refactor(views)`)
  - **Przeniesienie sekcji „Historie Opiekunów Naszych Kotów” do „O Hodowli” (`cattery.blade.php`):**
    - Zdjęto sekcję `#opinie` z `home.blade.php`, maksymalnie odchudzając stronę główną i skupiając ją na bezpośrednim procesie: *Hero → Dostępne Kocięta → Domowa Socjalizacja → Wideo-rozmowa / WhatsApp*.
    - Umieszczono sekcję `#opinie` na podstronie `cattery.blade.php` tuż pod 3-krokowym procesem zakupu kocięcia (*„Krok 03: Umowa i Odbiór”*), co stanowi naturalny dowód społeczny dla klientów czytających o standardach hodowli.
  - **Utworzenie w 100% reużywalnego komponentu `<x-frontend.conversion-cta />`:**
    - Utworzono komponent `resources/views/components/frontend/conversion-cta.blade.php`, który ujednolica końcowe wezwanie do działania na wszystkich podstronach.
    - Zastępuje on dawne, niespójne teksty i odsyłacze do formularza `/kontakt` kompletnym pakietem konwersyjnym:
      1. Złoty przycisk: `📹 Umów wideo-rozmowę na żywo` (otwierający modal z obsługą kierunkowych międzynarodowych i oddzwonienia).
      2. Grafitowy przycisk: `💬 Porozmawiaj na WhatsApp` (z gotowym szablonem i analityką `contact_cta_click`).
      3. Bezpośredni telefon: `lub zadzwoń bezpośrednio: +48 514 153 204`.
      4. Znak zaufania: `Bezpieczna rezerwacja z rodowodem SHiOZ ZOOLANDIA · Pełna dokumentacja medyczna i wyprawka`.
    - Komponent wdrożono na:
      - Stronie głównej (`home.blade.php`),
      - Stronie „O Hodowli” (`cattery.blade.php`),
      - Katalogu kotów (`animals/index.blade.php`),
      - Stronie „O Nas” (`about.blade.php`),
      - Widoku każdego artykułu w Bazie Wiedzy (`blog/show.blade.php`).
    - Każda przyszła zmiana treści, numeru telefonu czy przycisków odbywa się w jednym pliku `conversion-cta.blade.php` dla całego portalu.
  - **Weryfikacja testami:**
    - Zaktualizowano asercje w `tests/Feature/Frontend/HomepageTest.php` (sprawdzenie obecności opinii na podstronie O Hodowli oraz ich braku na Home).
    - Wszystkie testy przeszły pomyślnie (`HomepageTest`, `ReservationAndCroTest`, `AnimalTest`, `BlogTest`).

✓ Optymalizacja Ścieżki Zakupowej na Stronie Głównej & Domowa Socjalizacja (`feat(home)`, `feat(cro)`, `test(home)`)
  - **Usunięcie sekcji „Nasze Specjalizacje Rasy”:**
    - Zlikwidowano długi blok 3 kart ras z `home.blade.php`, dzięki czemu zarówno na desktopie, jak i mobile, użytkownik po sekcji Hero od razu widzi „Dostępne Kocięta i Mioty”.
    - Usunięto niepotrzebny wrapper `.home-sections-flow`, maksymalizując szybkość i eliminując niepotrzebne scrollowanie.
  - **Przebudowa sekcji „Codzienność w hodowli” na Opcję A (Domowa Socjalizacja):**
    - Zastąpiono powierzchowną sekcję odsyłającą do Facebooka merytorycznym blokiem budującym zaufanie: *„Czułość i życie bez klatek od pierwszego dnia”*.
    - Wdrożono 3 filary domowej socjalizacji: *Zero klatek, 100% życia z nami*, *Czuły kontakt z człowiekiem*, *Prywatne relacje wideo po rezerwacji*.
    - Dodano główny przycisk wywołujący modal wideo-wizyty na żywo (`window.openReservationModal`) oraz dyskretny odnośnik do relacji na Facebooku.
    - Zaktualizowano galerię zdjęć na autentyczne ujęcia domowej opieki i reproduktorów (`story_care.jpg`, `stas_studio.jpg`, `bars_studio.jpg`).
  - **Wzmocnienie Dolnego CTA dla Użytkowników Desktopowych:**
    - Zaktualizowano komponent `<x-frontend.cta />` o obsługę slotu dla podwójnych przycisków akcji.
    - Zastąpiono przekierowanie na statyczną podstronę `/kontakt` dwoma bezpośrednimi akcjami: złotym przyciskiem `📹 Umów wideo-rozmowę na żywo` (otwierającym modal rezerwacji/oddzwonienia) oraz `💬 Porozmawiaj na WhatsApp` (z gotowym szablonem i trackingiem zdarzenia `contact_cta_click`), wraz z bezpośrednim numerem telefonu `+48 514 153 204`.
  - **Weryfikacja testami i kompilacja:**
    - Zaktualizowano asercje w `tests/Feature/Frontend/HomepageTest.php` (4/4 testy pass).
    - `ReservationAndCroTest.php` przeszedł pomyślnie (9/9 pass).
    - Zbudowano produkcyjne assety Vite (`npm run build`).

✓ Międzynarodowe Kierunkowe i Walidacja Telefonów w Formularzu Oddzwonienia (`feat(callback)`, `feat(mobile)`, `test(cro)`)
  - **Wybór kierunkowego kraju dla klientów zagranicznych:**
    - Zastąpiono sztywny prefiks `+48` interaktywnym, mobilnym selektorem kierunkowych w `<x-frontend.reservation-modal />`.
    - Dodano listę najczęstszych kierunków zagranicznych (🇵🇱 Polska +48 jako domyślny, 🇩🇪 Niemcy +49, 🇬🇧 UK +44, 🇳🇴 Norwegia +47, 🇳🇱 Holandia +31, 🇸🇪 Szwecja +46, 🇦🇹 Austria +43, 🇨🇭 Szwajcaria +41, 🇧🇪 Belgia +32, 🇮🇪 Irlandia +353, 🇫🇷 Francja +33, 🇩🇰 Dania +45, 🇺🇸 USA/Kanada +1, 🇨🇿 Czechy +420, 🇸🇰 Słowacja +421, 🇮🇹 Włochy +39, 🇪🇸 Hiszpania +34, 🇺🇦 Ukraina +380, 🇱🇹 Litwa +370, 🇫🇮 Finlandia +358 oraz opcja 🌐 Inny).
    - Zachowano luksusowy design (złota strzałka, ciemne tło pergaminowe, separator, wsparcie systemowego pickera iOS/Android bez uciążliwych popupów).
  - **Inteligentne łączenie numerów w JavaScript (`reservation-modal.js`):**
    - Jeśli użytkownik wklei numer z prefiksem `+` lub `00` (np. `+49 170...`), system automatycznie zachowuje wpisany kierunek bez dublowania prefiksu.
    - Jeśli użytkownik wpisze numer lokalny, pobierany jest prefiks z selektora.
  - **Uelastycznienie walidacji backendowej (`QuickCallbackRequest.php`):**
    - Zmiana reguły długości z `min:9, max:25` na `min:7, max:30` z dopuszczeniem międzynarodowych formatów ze spacjami i myślnikami (`/^[0-9\s\-+()]{7,30}$/`).
  - **Testy jednostkowe i integracyjne (`ReservationAndCroTest.php`):**
    - Dodano testy `test_quick_callback_supports_international_phone_numbers` (weryfikacja formatów DE, UK, NO, NL, US) oraz `test_reservation_modal_renders_international_country_selector`.
    - Wszystkie testy przeszły pomyślnie (9/9 pass, 72 asercje).

✓ Zaawansowane dopracowanie Lokalnego SEO, Behawioralnego Quizu oraz Zasad Bezpiecznego Wdrożenia (`feat(seo)`, `feat(quiz)`, `docs(governance)`)
  - **Lokalne SEO dla Miast Satelickich (Punkt 6):**
    - Dodano dedykowaną podstronę `/hodowla-kotow-wloclawek` (najbliższe duże miasto, ~35-40 min trasą DK62 wzdłuż Wisły) obok Warszawy (~80-90 min), Łodzi (~80-90 min przez A1/DK60), Torunia (~70-75 min przez DK91/DK62) i Bydgoszczy.
    - Całkowicie wyeliminowano sformułowania o „ciasnych hodowlach w miejskich blokach” i porównania z konkurencją – zastąpiono je rzetelnymi atutami własnej domowej hodowli w otoczeniu Brudzeńskiego Parku Krajobrazowego.
    - Dodano jasny komunikat o rzeczywistej lokalizacji: Sikórz 56A k. Płocka (woj. mazowieckie), bez tworzenia fikcyjnych placówek czy wizytówek.
    - Każde miasto otrzymało unikalne opisy dojazdu, wskazówki dotyczące bezpiecznego transportu kociaka w transporterze oraz unikalną sekcję FAQ.
    - Zaktualizowano linkowanie wewnętrzne w stopce serwisu.
  - **Behawioralny Quiz Doboru Rasy (Punkt 7):**
    - Wdrożono 4-etapowy model wielokryterialnego dopasowania:
      1. Poziom energii i styl spędzania czasu (relaks na kanapie / eksploracja i zabawa lamparta / głęboki dialog i więź).
      2. Czas poza domem i struktura domowników (praca poza domem 7-9h – kot brytyjski znosi to doskonale, syjam cierpi na lęk separacyjny; praca zdalna; rodzina z dziećmi; inne zwierzęta).
      3. Preferencje dotyczące pielęgnacji i szaty (krótka gładka sierść bez czesania / gęsty plusz / smukły kot orientalny).
      4. Horyzont czasowy (gotowy od zaraz / wczesna rezerwacja z nowego miotu za 3-6 tyg. / elastycznie).
    - Algorytm wylicza ważoną punktację dla ras Bengal, British i Siamese, generując spersonalizowane uzasadnienie behawioralne.
    - Weryfikacja rzeczywistej dostępności w CMS: jeśli w bazie nie ma kociąt spełniających kryteria, wyświetla się rzetelny komunikat o braku maluchów w danym terminie z zaproszeniem do zapisu na listę oczekujących na WhatsApp.
    - Przekazywanie wyniku do WhatsApp (pre-filled tekst z rasą i kodem źródła) oraz do modalu szybkiego oddzwonienia.
    - Quiz działa w 100% bez logowania i bez konieczności podawania danych osobowych.
  - **Pasek Sticky Bar & Ergonomia Mobilna:**
    - Dodano klasę `is-modal-open` wyłączającą dolny pasek sticky bar podczas otwartego modalu rezerwacji, zapobiegając zasłanianiu pól i klawiatury mobilnej.
  - **Filary Zarządzania, Wdrożenia i Raportowania Biznesowego:**
    - Zapisano w `AI/Plannadzis.md` procedury bezpiecznego wdrożenia produkcyjnego (osobna gałąź Git, weryfikacja `git status`, backup bazy i zdjęć, zakaz modyfikacji `.env`, brak nieautoryzowanych migracji).
    - Zdefiniowano kryteria odbioru (RWD 320/375/390 px, brak overflow-x, poprawne formularze, linki, brak błędów w konsoli, zero regresji).
    - Ustalono ramy raportowania ROI (wydatki reklamowe, wartościowe zapytania `callback_submitted`, wideo-wizyty, rezerwacje i sprzedaż).
  - **Testy i Kompilacja:**
    - Pełny zestaw testów (132 testy, 758 asercji) zakończony z kodem 0.
    - Zbudowano bundle Vite produkcyjny (`npm run build`).
    - Zmiany zacommitowane i wypchnięte do gałęzi `main`.

✓ Pełne wdrożenie Optymalizacji Konwersji Dolnego Odcinka Lejka Sprzedażowego (CRO) & Live Video-Visits (`feat(cro)`, `feat(analytics)`, `feat(seo)`)
  - **Faza 3: Modal Rezerwacji i Wideo-Wizyt na żywo (Reservation Modal):**
    - Utworzono komponent `<x-frontend.reservation-modal />` z 4 ścieżkami kontaktu: WhatsApp Video (z pre-filled textem i kodem atrybucji), bezpośredni telefon, Facebook Messenger (`m.me/61580668026948`) oraz 1-polowy formularz szybkiego oddzwonienia.
    - Zero wzmianek o zadatku / cenie w UI modala (zgodnie z polityką hodowli).
    - Utworzono kontroler `app/Http/Controllers/CallbackController.php` z endpointem POST `/api/quick-callback` (walidacja numeru, throttling `5,1`, honeypot przeciw botom).
    - Dedykowane style w `resources/css/components/reservation-modal.css` i logika JS w `resources/js/reservation-modal.js`.
    - Podpięto modal pod profil kota (`animals/show.blade.php`), karty kotów (`animal-card.blade.php`) i stronę główną.
  - **Faza 2: Mobile Sticky Action Bar w strefie kciuka:**
    - Utworzono komponent `<x-frontend.mobile-sticky-bar />` z przyciskami "📹 Wideo-wizyta" i "📞 Zadzwoń", widoczny wyłącznie na urządzeniach mobilnych (`@media (max-width: 767px)`).
    - Dedykowany arkusz `resources/css/components/mobile-sticky-bar.css` ze wsparciem dla safe-area na iOS i marginesem dla stopki.
  - **Faza 1: Attribution Engine & Funnel Analytics (GA4 + Meta Pixel):**
    - Rozbudowano `resources/js/analytics.js` o automatyczne przechwytywanie parametrów URL (`utm_source`, `utm_medium`, `utm_campaign`, `fbclid`) do `sessionStorage` z fallbackiem w `localStorage`.
    - Wdrożono formatowanie etykiety źródła `getTrafficSourceLabel()` dopisywanej dyskretnie do wiadomości WhatsApp (`[Kod: ...]`).
    - Zaimplementowano uniwersalny dispatcher zdarzeń `trackFunnelStep()` obsługujący GA4 i Meta Pixel (`fbq('track', 'Lead')`).
    - Zintegrowano Meta Pixel z Consent Mode v2 w `shell.blade.php` i `cookie-consent.blade.php`.
  - **Faza 4: Homepage Mobile Refactor & Status Badges:**
    - Zmiana kolejności na mobile za pomocą CSS flexbox (`.home-sections-flow`): sekcja kociąt wyświetla się przed opisami ras.
    - Odświeżono nagłówek Hero i pasek zaufania o bezpośrednie wideo-wizyty.
    - Wdrożono dynamiczne rozróżnienie kociąt w wieku < 12 tyg jako `⏳ Wczesna rezerwacja • za X tyg.` oraz >= 12 tyg jako `🟢 Gotowy do odbioru`.
    - Ujednolicono nawigację na "Baza Wiedzy" w nagłówku i stopce.
  - **Faza 5: Interaktywny Quiz Doboru Rasy (`/dobierz-rase`):**
    - Utworzono dedykowaną stronę `/dobierz-rase`, kontroler `QuizController` oraz widok `quiz.blade.php`.
    - 3-krokowy quiz dopasowujący styl życia i oczekiwania do rasy (Bengalski, Brytyjski, Syjamski) z natychmiastowym filtrowaniem dostępnych kociąt i triggerem wideo-wizyty.
  - **Faza 6: Programmatic Local SEO dla Miast Satelickich:**
    - Utworzono kontroler `LocalSeoController` i dedykowane strony lądowania SSR dla 4 kluczowych miast:
      - `/hodowla-kotow-warszawa`
      - `/hodowla-kotow-lodz`
      - `/hodowla-kotow-torun`
      - `/hodowla-kotow-bydgoszcz`
    - Każda strona zawiera Schema.org `LocalBusiness` z polem `areaServed`, dynamiczną listę kociąt z bazy i opis dojazdu do Sikorza.
    - Dodano regionalny pasek linków wewnętrznych w stopce serwisu.
  - **Weryfikacja jakościowa:**
    - `npm run build`: kompilacja Vite zakończona z kodem 0.
    - `php artisan test`: wszystkie testy jednostkowe i integracyjne przeszły pomyślnie (PASS), w tym dedykowany zestaw `ReservationAndCroTest`.

## 2026-10-14

### Completed

✓ Pełna synchronizacja dokumentacji AI i zasad bezpiecznej pracy z produkcją (`docs(ai)`, `feat(db)`)
  - **Zero Overwrite Policy & Bezpieczeństwo Danych Produkcyjnych:**
    - Dodano oficjalne zasady w `DECISIONS.md` (Decision 026) oraz `RULES.md` (Sekcja 15): baza produkcyjna na hostingu jest jedynym źródłem prawdy dla kotów, treści i kolejności.
    - Zakaz uruchamiania seederów (`RealKittensAndParentsSeeder.php`, `DatabaseSeeder.php`) na serwerze produkcyjnym (wyłącznie lokalny dev).
    - Wszystkie migracje muszą być ściśle typu *insert-only* z warunkiem `if (! Animal::where(...)->exists())` i zerem `update()`.
    - Bezpieczna procedura wdrożeń: `git push origin main` -> wywołanie `/deploy-sync` (automatyczny git pull, bezpieczne migracje i optimize:clear).
  - **Standaryzacja hierarchii `sort_order` w dokumentacji:**
    - 1–19: Kocięta do rezerwacji / dostępne (Available / Reserved)
    - 20–29: Koty w nowych domach (Sold / Alumni)
    - 31–39: Koty hodowlane rasy Kot Brytyjski (Breeding) [Staś 31, Nel 32, Atos 33]
    - 41–49: Koty hodowlane rasy Kot Syjamski (Breeding) [Baltazar 41, Nela 42, Koko 43]
    - 51–59: Koty hodowlane rasy Kot Bengalski (Breeding) [Bella 51, Luki 52, Luna 53, Nel 54, Bars 55]
    - Automatyczne wyliczanie za pomocą `Animal::nextSortOrderFor($status, $breed)`.
  - **Podwójna paginacja (góra i dół) w standardach UI:**
    - Wdrożono przyciski stronicowania na górze i na dole każdej tabeli w panelu CMS (Animals, Posts, Media, Categories, Users, Roles) oraz na publicznych katalogach (Koty, Blog).
    - Zapisano jako obligatoryjny standard w `DECISIONS.md` (Decision 028) oraz `RULES.md` (Sekcja 15.4).
  - **Synchronizacja statusu w `SPRINTS.md` i `CLAUDE.md`:**
    - Zaktualizowano `SPRINTS.md`: Sprinty 0 do 7 oznaczone jako w 100% zrealizowane (Wydanie produkcyjne, SEO, polskie URL, bezpieczeństwo).
    - Zaktualizowano `CLAUDE.md`: priorytet czytania plików sesji oraz bezpośrednia widoczność polityki Zero Overwrite.
    - Zaktualizowano `DOMAINS.md`: szczegółowe wytyczne dla 3 głównych ras hodowli (Bengale, Brytyjczyki, Syjamy).

✓ Utworzenie profili i rejestracja mediów dla nowych kotów: Bengal Bars, 4 małe syjamy, 3 maluchy brytyjskie i reproduktor Atos (`feat(animals)`, `feat(media)`)
  - **Bengal Bars (Reproduktor):**
    - Dodano profil reproduktora rasy Kot Bengalski (`bars`, `AnimalGender::Male`, `AnimalStatus::Breeding`, sort_order: 12).
    - Zarejestrowano dane metrykalne z karty kota SHiOZ ZOOLANDIA: kod EMS BEN ns 24 (srebrzysty cętkowany z rozetami), rodowód (PL) FPL LO 354902, mikrochip 900263006037836, data ur. 09.05.2024 r.
    - Przypisano zdjęcie profilowe jako główne (`parent_bars_1.jpg`) oraz 2 zdjęcia do galerii (`parent_bars_2.jpg`, `parent_bars_3.jpg`).
    - Karta kota została wykluczona z mediów publicznych.
  - **Miot D — 4 kocięta syjamskie (Dafne, Daisy, Dakota, Diana):**
    - Utworzono profile kociąt rasy Kot Syjamski o umaszczeniu Seal Point, data ur. 13.09.2026 r., status `available`.
    - Przypisano relacje rodzicielskie do reproduktorów: matka Koko (`koko`) i ojciec Baltazar (`baltazar`).
    - Przypisano dedykowane zdjęcia koszykowe jako zdjęcia główne. Karta miotu została wykluczona z mediów.
  - **Brytyjczyki — 3 kocięta (Ada, Alma, Atena) oraz kocur Atos:**
    - Kocięta miotu A (ur. 09.09.2026 r., status `available`, matka Nel):
      - Ada: kotka brytyjska krótkowłosa (BSH a, niebieska), zdjęcie profilowe główne + 1 zdjęcie z galerii.
      - Alma: kotka brytyjska długowłosa (BLH a 33, colourpoint niebieski), zdjęcie profilowe główne + 1 zdjęcie z galerii.
      - Atena: kotka brytyjska krótkowłosa (BSH a, niebieska), zdjęcie profilowe główne + 3 zdjęcia z galerii.
    - Atos: reproduktor rasy Kot Brytyjski Długowłosy (`atos`, `AnimalGender::Male`, `AnimalStatus::Breeding`, sort_order: 14, matka Nel). Profil utworzony bez mediów (karta miotu wykluczona; profil oczekuje na nadesłanie dedykowanych fotografii).
  - **Bezpieczeństwo bazy danych i wdrożenie na hosting:**
    - Utworzono nieinwazyjną, idempotentną migrację `2026_10_14_140000_add_bars_british_and_siamese_cats.php`.
    - Pomyślnie wykonano migrację lokalną, synchronizując pliki ze `storage/app/public/media` oraz `public/storage/media`.
    - Zweryfikowano działanie wszystkich nowych podstron (`/koty`, `/koty/bars`, `/koty/dafne`, `/koty/daisy`, `/koty/dakota`, `/koty/diana`, `/koty/ada`, `/koty/alma`, `/koty/atena`, `/koty/atos`) – wszystkie zwracają HTTP 200.
    - Wszystkie 125 testów automatycznych (705 asercji) zakończonych wynikiem PASS.


### Completed

✓ Pełne przejście na język polski w dashboardzie i na stronie głównej oraz kanoniczne polskie URL (`feat(i18n)`, `feat(seo)`, `refactor(routing)`)
  - **Kanoniczne adresy URL & SEO:**
    - Zmiana adresu formularza kontaktowego na `/kontakt` (GET i POST).
    - Zmiana adresu strony "O nas" na `/o-nas`.
    - Wdrożenie trwałych przekierowań 301 z dawnych adresów angielskich (`/contact` -> `/kontakt`, `/about` -> `/o-nas`).
    - Zaktualizowanie dynamicznej mapy witryny `/sitemap.xml` oraz inwentarza SEO.
  - **Zmiana nazwy sekcji na Blog:**
    - Zmiana nazewnictwa z „Baza Wiedzy” na „Blog” w nagłówku strony (desktop i mobile), w stopce, w okruszkach chleba, w nagłówkach `<h1>`, tagach `<title>` oraz opisach powiązanych artykułów.
  - **Polonizacja panelu CMS (Dashboard / Backend):**
    - Przetłumaczenie całego paska bocznego (Sidenav) oraz górnego (Topnav), usunięcie pozostałości szablonowych i języka niderlandzkiego (*Naar website*, *Start Bootstrap*, *Gebruiker*).
    - Pełne przetłumaczenie widoków modułów: Koty hodowlane, Wpisy na blogu, Kategorie bloga, Biblioteka mediów, Użytkownicy, Role i uprawnienia, Oferta pod artykułami (Global CTA).
    - Utworzenie słownika `lang/pl.json` dla wszystkich komponentów autoryzacji i ustawień konta (Livewire, 2FA, hasło, wygląd, powiadomienia).
    - Przetłumaczenie szablonu wiadomości e-mail (`emails/contact.blade.php`) i klasy Mailable na język polski.
  - **Testy automatyczne:**
    - Zaktualizowano wszystkie testy funkcjonalne i dymne (`ContactTest`, `SeoTest`, `ProductionSmokeTest`, `SecurityHardeningTest`).
    - Wszystkie 125 testów (705 asercji) zakończone wynikiem 100% PASS.

## 2026-10-04

### Completed

✓ Artykuł Bazy Wiedzy: Szczepienia kociąt — wdrożenie autentycznych fotografii kotki brytyjskiej i najmłodszych kociąt (`feat(blog)`, `style(ui)`)
  - Wybór i zoptymalizowanie 2 najlepszych fotografii kotki brytyjskiej z miotem z folderu `image/Brytyjczyki/` (`7d9781e7...` oraz `706e576b...`).
  - Wykadrowanie w proporcji 4:3 (1200x900) z zachowaniem pełnej sylwetki matki oraz wszystkich nowo narodzonych maluchów (brak uciętych głów/uszu).
  - Wdrożenie zdjęć do artykułu `Szczepienia kociąt — harmonogram, koszt i poradnik`:
    - Sekcja 2 (Harmonogram szczepień i przeciwciała matczyne): wplecenie zdjęcia troskliwie karmiącej kotki brytyjskiej (`08_kotka_brytyjska_karmiaca.jpg`) jako merytorycznej ilustracji odporności z siary w pierwszych tygodniach życia.
    - Sekcja 3 (Standard profilaktyki w hodowli w Sikorzu): wplecenie zdjęcia czuwającej kotki brytyjskiej z maluchami (`09_kotka_brytyjska_z_kocietami.jpg`) jako wizytówki domowych warunków i bezpieczeństwa hodowli.
    - Sekcja 1 (Przed czym chronią szczepienia): przypisanie zdjęcia młodego kociaka (`06_nasz_kociak_1.jpg`).
    - Sekcja 4 (Ile kosztuje szczepienie): zachowanie zdjęcia odchowanego kociaka (`07_nasz_kociak_2.jpg`).
  - Zaktualizowanie bazy danych (`Post` id: 12) – pola `sections` oraz `body`.
  - Zaktualizowanie pliku `BLOG/012-szczepienia-kociat-harmonogram/01_article.md` oraz synchronizacja plików w `public/storage/` i `storage/app/public/`.
  - Optymalizacja CSS w `blog-page.css`: uelastycznienie `.article-section-img` (`max-height: 560px`, `height: auto`), dzięki czemu zdjęcia 4:3 oraz pionowe nie są obcinane przez sztywne 16:9.
  - Pomyślna kompilacja assetów produkcyjnych (`npm run build`).

✓ Naprawa błędu CMS: Znikające zdjęcia przy zapisie / usuwaniu pojedynczego zdjęcia (`fix(blog)`)
  - **Diagnoza problemu:**
    1. W `UpdatePostRequest` oraz `StorePostRequest` pole `sections` nie było zadeklarowane w tablicy `rules()`. W efekcie Laravel przy wywołaniu `$request->validated()` bezwzględnie odrzucał pole `sections`.
    2. Metoda `PostService::update()` otrzymywała pustą wartość `sections` i nadpisywała kolumnę w bazie jako `null`.
    3. Metoda `sectionsToBody()` w FormRequestach i serwisach odrzucała tagi zdjęć markdown (`![...]`), regenerując `body` wyłącznie z tekstu akapitów.
    4. Każde kliknięcie "Zapisz" (lub usunięcie pojedynczego zdjęcia z dowolnej sekcji) powodowało całkowite wyczyszczenie zdjęć ze wszystkich sekcji artykułu.
  - **Wdrożone rozwiązanie:**
    1. Dodano pełną walidację struktury `sections` (`sections.*.heading`, `sections.*.body`, `sections.*.image_url`) w `UpdatePostRequest` i `StorePostRequest`.
    2. Zabezpieczono `sectionsToBody()`, aby zachowywał linki do zdjęć w Markdown (`![alt](url)`).
    3. Dodano zabezpieczenie w `PostService::update()`: `array_key_exists('sections', $data) ? $data['sections'] : $post->sections`.
    4. Dodano nasłuchiwacz zdarzenia `submit` w `form.blade.php`, aby przed wysłaniem formularza zawsze upewnić się o serializacji `sections_json`.
    5. Wszystkie 124 testy Laravel Pest przeszły pomyślnie.

✓ Naprawa Bazy Wiedzy: Wszystkie zaplanowane artykuły widoczne z dzisiejszą datą i zaburzoną kolejnością (`fix(blog)`, `fix(deploy)`)
  - **Diagnoza problemu:**
    1. W `routes/web.php` w trasie `/deploy-sync` wywoływano `Artisan::call('seo:import-articles', ['--force-now' => true]);`.
    2. Przekazanie flagi `--force-now` sprawiało, że dla każdego z 19 pakietów artykułów z folderu `BLOG/` kolumna `published_at` była bezwzględnie nadpisywana stemplem czasu `now()`.
    3. W efekcie wszystkie 7 artykułów zaplanowanych na przyszłość (od 11 października do 22 listopada) otrzymywało dzisiejszą datę publikacji, stając się natychmiast widocznymi publicznie (`published_at <= now()`).
    4. Ponieważ wszystkie 19 artykułów otrzymało ten sam timestamp co do sekundy, klauzula `latest('published_at')` sortowała je losowo, zaburzając porządek chronologiczny.
  - **Wdrożone rozwiązanie:**
    1. W `routes/web.php` usunięto flagę `['--force-now' => true]` z wywołania w trasie `/deploy-sync`.
    2. W `ImportSeoArticles.php` dodano zabezpieczenie ignorujące `--force-now`, jeśli nie wskazano konkretnego folderu (`--folder=...`), uniemożliwiając globalne nadpisanie harmonogramu.
    3. W `BlogController.php` wprowadzono deterministyczne sortowanie `orderByDesc('published_at')->orderByDesc('id')`.
    4. Utworzono migrację `2026_10_04_133000_restore_exact_weekly_publishing_schedule.php`, która przywraca 100% precyzyjny niedzielny harmonogram 10:00 (12 opublikowanych, 7 przyszłych).
    5. Dodano testy w `BlogTest.php` weryfikujące ukrywanie zaplanowanych artykułów oraz ścisłą kolejność chronologiczną.

✓ Ochrona edycji CMS: Zakaz nadpisywania istniejących artykułów przez pliki z dysku (`fix(cms)`, `fix(deploy)`)
  - **Diagnoza problemu:** Gdy użytkownik usuwał zdjęcia lub poprawiał sekcje artykułu w panelu CMS (`/backend/posts`), każde wywołanie trasy `/deploy-sync` lub komendy `seo:import-articles` pobierało plik markdown z dysku (`BLOG/{folder}/01_article.md`) i przez `Post::updateOrCreate()` bezwzględnie nadpisywało bazę danych, przywracając usunięte zdjęcia.
  - **Wdrożone rozwiązanie:**
    1. W `ImportSeoArticles.php` dodano weryfikację: jeśli dany artykuł istnieje już w bazie danych (`slug`), import go bezwzględnie pomija (`Skipped Post ID ... [Już istnieje w CMS — zachowano edycje]`).
    2. Import tworzy wyłącznie *nowe* pakiety artykułów, które nie mają jeszcze odpowiednika w CMS.
    3. Dodano flagę `--force-sync`, która jest jedynym sposobem na wymuszenie nadpisania bazy z dysku, jeśli kiedykolwiek byłoby to potrzebne.
    4. Zmiany wypchnięto na produkcję i uruchomiono `/deploy-sync` — serwer pomyślnie pominął wszystkie 19 istniejących postów, zachowując 100% edycji dokonanych przez hodowcę w panelu.


## 2026-08-11

### Completed

✓ Sprint 7 — Security, SEO, Forms & Final Production QA (`fix(sec)`, `fix(seo)`, `feat(legal)`, `test(qa)`)
  - Przeprowadzenie pełnego 7-etapowego audytu produkcyjnego (Security, SEO Head, Robots/Sitemap, Form Validation, Custom Error Pages, Config Polish, Pre-Launch Audit).
  - Wdrożenie zabezpieczeń walidacji w `ContactFormRequest`: `email:rfc`, `max:254`, `subject min:2`, `message min:15`.
  - Wdrożenie 15 testów bezpieczeństwa formularza kontaktowego w `ContactTest.php` (throttle, payload limit, CSRF token).
  - Zaktualizowanie `robots.txt`: dodanie `Disallow: /storage/` oraz aktualizacja adresu sitemap na `https://kotyzmazowieckiejszwajcarii.pl/sitemap.xml`.
  - Utworzenie autorskich, spójnych stylistycznie stron błędów: `403.blade.php`, `419.blade.php`, `429.blade.php`.
  - Stworzenie bezpiecznego szablonu środowiska produkcyjnego `.env.production` z docelową domeną `kotyzmazowieckiejszwajcarii.pl`.
  - Usunięcie dublowanego prefiksu `/backend` dla głównego pulpitu — kanoniczną trasą pozostał `/dashboard` (name: `dashboard`).
  - Pełna aktualizacja treści Polityki Prywatności w `privacy.blade.php` z 100% zachowaniem tekstu prawnego (REGON, adres, RODO, skargi UODO, cookies).
  - Przeprowadzenie pełnego audytu QA responsive i regresji wizualnej (320px - 1920px). Zero regresji na desktopie.
  - Testy końcowe: Wszystkie **64 na 64 testy PASS (100% sukcesu)**.

## 2026-08-09

### Completed

✓ Sprint 7 — Production Release & Audit (`audit: production`, `fix(sec)`, `fix(seo)`)
  - Przeprowadzenie pełnego 15-stopniowego audytu produkcyjnego (bezpieczeństwo, SEO, stabilność) zgodnie z wytycznymi, bez dokonywania zmian w designie.
  - Wykrycie 4 problemów krytycznych, 6 istotnych i 3 o niskim priorytecie.
  - Zabezpieczenie pliku `.env.example` przygotowanego pod wdrożenie: `APP_ENV=production`, `APP_DEBUG=false`, poprawiono `LOG_LEVEL` i email wyjściowy.
  - Naprawienie pliku `robots.txt` - zmiana deweloperskiego adresu na docelowy i wskazanie pliku sitemap.
  - Utworzenie dynamicznej mapy strony (`sitemap.xml`) przy pomocy trasy w `web.php` oraz luksusowego widoku `sitemap.blade.php`, obejmującej wszystkie strony, opublikowane koty i artykuły na blogu.
  - Dodanie zabezpieczeń Middleware `['auth', 'verified', 'active']` do diagnostycznych tras (`/check-media` i `/fix-storage`), zamykając lukę bezpieczeństwa, w której każdy miał dostęp do uruchamiania seedera bazy i skryptów uprawnień.
  - Przeprowadzenie testów - wszystkie 49 testów przechodzi pomyślnie.
  - Zmiany dokumentacyjne zaktualizowane w dokumentacji AI (zgodnie z `MASTER_PLAN.md`).



## 2026-08-06

### Completed

✓ Sprint 7 — Mobile Experience Audit & Refinement (`fix(mobile)`)
  - Przeprowadzenie kompletnego audytu mobilnego wszystkich stron i komponentów (Home, About, O Hodowli, Koty, Profil Kota, Baza Wiedzy, Kontakt, Stopka, Nawigacja).
  - Identyfikacja 30+ problemów mobilnych w 11 kategoriach (Hero, Navbar, Homepage, Footer, Blog, Animals, Contact, About, Typography, Cards, Global).
  - Dodanie obsługi `env(safe-area-inset-top)` do `.site-header` — pełne wsparcie iPhone notch/Dynamic Island. Analogicznie `env(safe-area-inset-bottom)` do stopki i mobile menu.
  - Zmniejszenie `padding-top` sekcji hero na mobile (`clamp(140px,16vh,180px)` → `clamp(90px,14vh,120px)`) — nagłówek widoczny w pierwszym foldsie na 375px.
  - Pełnowymiarowe przyciski CTA w hero na mobile (`align-items: stretch; width: 100%`).
  - Dodanie `overflow-x: hidden` na `body.frontend-shell` (mobile ≤1023px) oraz `overscroll-behavior: none` na `body.menu-open` (iOS scroll-chain fix).
  - Skalowanie tokenów typograficznych na mobile: `--text-lead-airy-size` (24px → 20px), `--text-tagline-size` (21px → 18px).
  - Zwiększenie touch target social links w stopce do 44×44px (Apple HIG minimum). Pionowy layout newsletter form na ekranach <480px.
  - Wzmocnienie hamburger lines (1.5px → 2px height, 18px → 20px width). Kompaktowy mobile menu na bardzo małych ekranach (<400px).
  - Kompleksowe poprawki homepage: manifesto quote clamp (1.75rem → 1.375rem min), kompaktowy gap tenets, collection card min-height (380px → 280px mobile), kodeks padding, editorial empty box padding.
  - Naprawa gallery grid na stronie About: `grid-template-rows: auto` zamiast fixed 240px, `aspect-ratio: 16/9` na itemach.
  - Kompaktowy animal profile: mniejszy padding-top, thumbnails 68px (było 80px), spec cards padding, filter pills na tiny screens.
  - Blog/artikel: article cover hero min-height 560px → 380px na mobile, clamp title (2.25rem → 1.625rem min), pionowy author bar.
  - Contact: 2-kolumnowy mid-breakpoint dla contact methods (640-834px), pełnowymiarowy submit, mapa 420px → 260px na mobile.
  - Build: ✓ czysty (`npm run build` — 205KB CSS frontend bundle).
  - Testy: ✓ 48 passed, 153 assertions — zero regresji.
  - Desktop: ✓ NIEZMIENIONY — każda zmiana za `max-width` media query lub `clamp()`/`env()`.

## 2026-07-31


### Completed

✓ Sprint 6.8 — Luxury UI/UX Final Polish & Privacy Compliance (`style(ui)`, `feat(legal)`)
  - Wyrównanie typografii mikroetykiet (`12px`) w kartach zwierząt i artykułów (`animals.css`, `articles.css`).
  - Optymalizacja efektu szkła na odznakach (zmiana przezroczystości na `0.65` dla lepszego rozmycia).
  - Zmiękczenie obramowań kart z twardych linii na bardzo subtelne `rgba(0, 0, 0, 0.04)`.
  - Usunięcie animowanego wskaźnika scrollowania (myszki) z sekcji Hero w oknie `about.blade.php`.
  - Perfekcyjne wyrównanie układu sekcji "Nasza filozofia" (`about.css`): zmiana siatki na `1fr 1fr`, usunięcie pustych przestrzeni przez nałożenie stałej wysokości zdjęcia `550px` i wyosiowanie obu kolumn do góry (`flex-start`).
  - Naprawa krzywych marginesów w stopce (usunięcie domyślnych stylów akapitów w `footer.css`).
  - Wdrożenie kompleksowej Polityki Prywatności zgodnie z RODO (utworzenie widoku `privacy.blade.php` oraz zaślepki `terms.blade.php`). Zmodyfikowanie tras w `web.php`.
  - Dodanie jasnej klauzuli o akceptacji polityki prywatności do sekcji newslettera, spełniającej standardy RODO z zachowaniem estetyki premium.
  - Usunięcie zbędnych odznak zaufania ze stopki, celem maksymalnego minimalizmu.
  - Przeprowadzenie rygorystycznego audytu prawnego i technicznego (QA RODO): usunięcie nieprawdziwych oświadczeń (MailerLite, zespół prawny), uzupełnienie Polityki Prywatności o brak profilowania, decyzji zautomatyzowanych i transferu poza EOG oraz konkretne kategorie przetwarzanych danych.
  - Usunięcie wszystkich atrybutów `style=""` ze stron prawnych i przeniesienie stylów do BEM (`legal-page.css`, `footer.css`).
  - Urealnienie powiadomień mailowych z formularza kontaktowego: zmiana odbiorcy w `SendContactMessageMail.php` na `hodowla.z.mazowieckiej.szwajcarii@gmail.com` i przetłumaczenie `ContactMessageNotification.php` na język polski.
  - Zmniejszenie dolnych marginesów i paddingów pod stopką (sekcja copyright i linki prawne): zmiana padding-bottom `.site-footer .section` z `48px` na `24px` oraz usunięcie podwójnych odstępów nad i pod `.footer-bottom` (`footer.css`).
  - Zmniejszenie obfitego pionowego paddingu i marginesów w sekcji CTA („Szukasz wymarzonego kociaka?”) w `cta.css`: zmiana domyślnego paddingu sekcji 80px na `24px` (desktop) i `17px` (mobile) oraz zmniejszenie paddingu bloku `.cta-block`.
  - Zmniejszenie nadmiarowego dolnego marginesu w sekcji katalogu kotów („Hodowla & Oferta – Nasze Koty”) pod ostatnim zdjęciem: dodanie klasy `.animals-catalog-section` z `padding-bottom: 24px` (`animals-page.css` oraz `animals/index.blade.php`).
  - Wyeliminowanie błędu z czarnymi aktywnymi przyciskami filtrów („cały czarny do najechania” – `background-color: var(--color-ink)`) na stronie Bazy Wiedzy (`blog-page.css`) oraz Katalogu Kotów (`animals-page.css`): zastąpienie ciemnych pigułek jasnym, luksusowym stylem w stylu Apple z obramowaniem `var(--color-primary)` i stałym stylem przy hoverze (`.blog-category-pill--active`, `.breed-pill--active`).
  - Całkowite usunięcie paska narzędziowego z filtrami kategorii (pigułki) oraz wyszukiwarki ze strony Bazy Wiedzy (`blog/index.blade.php`), aby nie zaśmiecać widoku przy niedużej liczbie artykułów i zachować minimalistyczny, redakcyjny wygląd.
  - Opracowanie luksusowego, redakcyjnego stylu dla sekcji tytułowej Bazy Wiedzy (`.blog-hero` w `blog/index.blade.php` i `blog-page.css`): wprowadzenie nadtytułu (`.text-eyebrow`), wyrazistego tytułu z ciasnym trackingiem Apple (`.text-hero-display`) i eleganckiego leadu ze swobodną interlinią (`.text-lead-airy`), spójnego z katalogiem „Nasze Koty”.
  - Zmniejszenie nadmiarowego dolnego paddingu pod siatką artykułów na liście Bazy Wiedzy (`.blog-grid-section` w `blog-page.css` z `var(--space-20)` na `var(--sp-lg)` / 24px) – identycznie jak wcześniej w sekcji katalogu kotów.
  - Wyeliminowanie niewidocznego obramowania przed najechaniem myszką (`border: 1px solid rgba(0, 0, 0, 0.04)`) we wszystkich kartach w serwisie (`.animal-card` w `animals.css`, `.blog-card` w `articles.css` oraz `.collection-card`, `.patron-monolith`, `.adoption-journey-pillar` w `home-page.css`): zastąpienie obramowania wyrazistym luksusowym kolorem `var(--color-hairline)` (`#e0e0e0`), dodanie delikatnego cienia spoczynkowego (`box-shadow: 0 4px 16px rgba(0,0,0,0.05)`) oraz podświetlenie ramki kolorem akcentu marki (`var(--color-primary)`) przy hoverze.
  - Wyważenie pionowych odstępów wewnętrznych w kartach (poprawa ergonomii dla użytkownika końcowego):
    - W kartach kotów (`animals.css`) wyeliminowałem błąd podwójnego paddingu powodowanego przez nakładanie się `.card-body` z `.animal-card__body` (dawniej 40px nad danymi kota). Zresetowałem `.card-body` do 0 i ustawiłem zwarty, ergonomiczny padding `.animal-card__body` na `12px 20px 20px`, dzięki czemu dane kota są idealnie blisko zdjęcia.
    - W kartach bloga (`.blog-card__body` i `.blog-card__read-more` w `articles.css`) zmniejszyłem nadmiarowy dolny padding pod linkiem „Czytaj artykuł” z `var(--sp-xl)` (32px) do `var(--sp-md)` (16px) oraz zoptymalizowałem odstęp nad linią oddzielającą na 12px.
  - Kompleksowa modernizacja widoku pojedynczego artykułu (`show.blade.php` i `blog-page.css`):
    - Zastąpienie surowego startu z oddzielnym tytułem i luźnym zdjęciem pod nim na rzecz luksusowej, pełnoekranowej sekcji `.article-cover-hero`: zdjęcie artykułu wyświetla się w tle nagłówka z ciepłą nakładką gradientową, a biały tytuł (`.article-cover-hero__title`), lead i pill z autorstwem/czasem czytania wyśrodkowane są na jego tle.
    - Wystylizowanie nawigacji okruszkowej (`.article-breadcrumb`) jako pigułki glassmorphism o wysokim kontraście i całkowite usunięcie z niej oraz ze stopki artykułu pigułek kategorii (kategorie nie są wyświetlane na frontendzie, ale pozostają zachowane w bazie danych i relacjach Eloquent).
    - Całkowite usunięcie zbędnej sekcji „Udostępnij:” oraz ikon przycisków społecznościowych/maila ze stopki artykułu w celu zachowania maksymalnego minimalizmu.
    - Naprawa rozjechanych i nieostylowanych kart w sekcji „Przeczytaj także”: zastąpienie ręcznie wpisanego, uszkodzonego kodu na rzecz dedykowanego układu siatki `.articles-grid` i oficjalnego komponentu `<x-frontend.blog-card :post="$related" />`.
  - Wyeliminowanie problemu czarnego i niebieskiego tła na ikonie Facebooka w stopce portalu (`footer.css`): przycisk `.footer-social__link` otrzymał **przezroczyste tło** (nie zlewa się z pergaminem ani nie tworzy czarnej plamy), **szlachetną złotą obwódkę (`#c8a04a`)**, wyraźny ciemnografitowy znak (`#1d1d1f`) oraz regułę `fill: none !important` zapobiegającą wypełnianiu wektora. Na hoverze przycisk podświetla się na ciepły złoty odcień (`#a47e2a`) bez użycia koloru niebieskiego.

## 2026-07-30

### Completed

✓ Sprint 6.5 — Editorial Finish Pass & Apple/Aesop Craftsmanship (`style(home)`, `test(ui)`)
  - Wykonanie rygorystycznego szlifu wizualnego (Editorial Finish Pass) na stronie głównej (`home.blade.php`, `home-page.css`) w standardzie rzemiosła wizualnego Apple, Aesop, Polène i Linear (8-pixel rhythm)
  - Znormalizowanie pionowych odstępów sekcji (`--sp-4xl` na desktopie, `--sp-2xl` na mobile), marginesów nagłówków sekcji (`--sp-2xl`) oraz siatek (`--sp-xl` desktop / `--sp-lg` mobile) w skali kaskady 8px
  - Ujednolicenie kart w rzędach flex kolumnowych o równej wysokości z identycznym paddingiem wewnętrznym (`--sp-2xl` / `32px` desktop, `--sp-xl` / `24px` mobile) dla kolekcji ras, kodeksu zaufania, opinii patronów i filarów adopcji
  - Usunięcie szumu wizualnego: redukcja zbędnych obramowań i ciężkich cieni (zastąpionych subtelnym Apple hover elevation), usunięcie nadmiarowego przycisku CTA kontaktowego w sekcji adopcji (aby nie konkurował z finalnym CTA Monolith)
  - Wdrożenie `text-wrap: balance;` dla nagłówków sekcji, ujednolicenie etykiet Monospace Masthead (`11px`, `600`, `#d1ab58`, `0.12em`) oraz optymalizacja miary i interlinii akapitów (`1.65`)
  - Potwierdzenie pełnej stabilności testów (`php artisan test` — 48 passed, 153 assertions) oraz czysty build produkcyjny (`npm run build`)
✓ Sprint 6.7 — Production Visual QA & Frontend Audit (`fix(ui)`, `test(ui)`)
  - Przeprowadzenie rygorystycznej kontroli jakości wizualnej (Production Visual QA) z perspektywy Senior Frontend Engineera we wszystkich widokach publicznych (Home, About, Katalog Kotów, Profil Kota, Baza Wiedzy, Kontakt, 404/500) i komponentach Blade
  - Wyeliminowanie błędu przesunięć układu (CLS prevention) w widoku `about.blade.php` poprzez uzupełnienie brakujących jawnych wymiarów obrazów (`width="1000" height="1250"`, w tym dla elementów galerii)
  - Wykrycie i naprawienie krytycznego błędu braku definicji zmiennych `--space-1` – `--space-24` w `design-tokens.css` (`:root`), który powodował zerowanie odstępów, marginesów i siatek w całym module blogowym (`blog-page.css`) oraz skrótach ładowania (`skeleton.css`)
  - Usunięcie wszystkich stylów liniowych (`style=""`) w katalogu kotów (`animals/index.blade.php`) na rzecz dedykowanych klas BEM i nowych modyfikatorów odstępów (`.section--no-pt`, `.section--no-pb`) w tokenach projektowych
  - Naprawienie niewidocznego tekstu kodów błędów (`404` i `500`) na stronach błędów (`errors.css`), zastępując jasne tło pergaminowe redakcyjnym znakiem wodnym atramentu (`color: var(--color-ink); opacity: 0.12;`)
  - Potwierdzenie stabilności całego systemu testami automatycznymi (`php artisan test` — 48 passed, 153 assertions) oraz czystą kompilacją pakietów produkcyjnych (`npm run build`)
✓ Sprint 6.5 — Homepage Storytelling Redesign (`feat(ui)`, `test(ui)`)
  - Przeprojektowanie narracji strony głównej (`home.blade.php`, `home-page.css`) z poziomu deweloperskiego/szablonowego do standardu marki luksusowej wycenianej na €20,000 (Apple, Aesop, Polène, Porsche, Rolls Royce, Bang & Olufsen) bez modyfikacji logiki backendowej
  - Wdrożenie filmowego, asymetrycznego Hero z redakcyjną metryką Monospace Masthead (`[ 01 — ETHICAL BREEDING ]`) oraz nakładaną na szklaną ramkę pieczęcią gwarancji linii genetycznych (`100% HCM / PKD n/n`)
  - Wprowadzenie dwustronicowego manifestu filozofii hodowli (`"Nie hodujemy kotów dla mas..."`) z trójdzielną linią czasu i wyeksponowaną kartą statystyk 15+ lat
  - Przeformułowanie prezentacji ras w immersyjne kolekcje hodowlane (`Collection 01 / Bengal`, `Collection 02 / British Shorthair`, `Collection 03 / Siamese`) z parametrami ras i cytatami
  - Zaprojektowanie czterokolumnowej matrycy kodeksu zaufania medycznego (`#kodeks`: Genetyka, Nadzór Kliniczny, Federacja, Behawiorystyka), osi czasu adopcji Concierge (`#adopcja-krok-po-kroku`), monolitycznych cytatów patronów z weryfikacją FIFe (`#opinie`) oraz Czytelni Hodowlanej (`#blog`)
  - Bezbłędna kompilacja aktywów produkcyjnych (`npm run build`) oraz stabilność testów (`php artisan test` — 48 passed, 153 assertions)
✓ Sprint 6 — Premium Frontend Experience: Animal Detail Apple/Aesop Luxury Polish (`feat(ui)`, `test(ui)`)
  - Wdrożenie redakcyjnej nawigacji okruszkowej (`.animal-profile__breadcrumbs`) oraz pigułkowych odznak statusu, rodowodu (`Rodowód FIFe / FPL`) i płci na stronie profilowej kota (`show.blade.php`)
  - Utworzenie interaktywnej galerii zdjęć z obsługą klawiatury (`role="button"`, `tabindex="0"`, `Enter`/`Space`), stanem aktywnym miniaturki (`.animal-profile__thumb--active`) oraz ochroną przed CLS (`1000x750`, `decoding="async"`, `fetchpriority="high"`)
  - Zastąpienie gęstej tabeli specyfikacji przestronną 3-kolumnową siatką redakcyjnych kart (`.animal-spec-card`) z ikonami Lucide, czytelną typografią i światłem
  - Przeprojektowanie komponentów panelu zdrowia (`animal-health-panel.blade.php`) oraz genealogii rodowodu (`animal-pedigree.blade.php`) w stylu magazynu Aesop bez użycia tabel
  - Optymalizacja czytelności opisu (szerokość wiersza `max-width: 65ch`, interlinia `1.8`), sekcji powiązanych kotów (`#inne-koty`) oraz dodanie bloku CTA na dole strony
  - Weryfikacja testów w `tests/Feature/Frontend/AnimalTest.php` (100% testów przechodzi) i bezbłędny build aktywów (`npm run build`)
✓ Sprint 6 — Premium Frontend Experience: Homepage Apple/Aesop Luxury Polish (`feat(ui)`, `test(ui)`)
  - Wdrożenie redakcyjnego paska zaufania w sekcji Hero (`.hero__trust-bar`) ze złoconymi ikonami certyfikacji (`FIFe / FPL`, `Badania Genetyczne`, `Domowa Socjalizacja`)
  - Utworzenie minimalistycznego horyzontalnego paska wyboru specjalizacji rasy (`.breed-showcase-strip`) z interaktywnymi kartami ras (Koty Bengalskie, Brytyjczyki, Syjamskie) oraz linkowaniem do katalogu
  - Zaprojektowanie 3-etapowej sekcji procesu adopcji na stronie głównej (`#adopcja-krok-po-kroku`) w stylu redakcyjnym Aesop (`01. Rozmowa i Dobór`, `02. Rezerwacja i Wizyta`, `03. Odbiór i Wyprawka`)
  - Wdrożenie redakcyjnych stanów empty state dla sekcji dostępnych kociąt (`#nasze-koty`) oraz podglądu bloga (`#blog`), eliminując nagłe znikanie całych sekcji przy braku treści
  - Optymalizacja mobilna, wyeliminowanie CLS dla wszystkich obrazów oraz dodanie testu `tests/Feature/Frontend/HomepageTest.php` (100% testów przechodzi)
✓ Sprint 6 — Premium CMS Experience: Media Library (`feat(cms)`, `refactor(cms)`, `test(cms)`)
  - Utworzenie migracji `2026_07_30_200000_add_cms_fields_to_media_table.php` dodającej pola metadanych i SEO (`caption`, `copyright`, `sort_order`, `is_featured`) oraz uelastyczniającej relacje polimorficzne `mediable` w celu obsługi mediów nieprzypisanych w Bibliotece Mediów
  - Opracowanie warstwy Domain-Driven Actions (`UploadMediaAction`, `ReplaceMediaAction`, `DeleteMediaAction`) i polityki autoryzacji `MediaPolicy` dla użytkowników z rolami `admin`, `editor`, `user`
  - Refaktoryzacja `MediaController`, `StoreMediaRequest` (wsparcie dla wgrywania wielu plików jednocześnie up to 20 images), `UpdateMediaRequest` oraz `MediaIndexRequest` (wyszukiwanie `q`, filtrowanie, sortowanie i tryb widoku `grid`/`list`)
  - Przeprojektowanie widoków Blade biblioteki mediów (`index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`) z responsywnym widokiem siatki i listy, dostępnym modalami podglądu, strefą Drag & Drop oraz stworzenie uniwersalnego komponentu `<x-backend.media-picker>` kompatybilnego z modułami Animals, Blog i Homepage Builder
  - Pokrycie testami integracyjnymi w `tests/Feature/Backend/MediaLibraryTest.php` (46/46 testów przechodzi, assets `npm run build` bez zduplikowanych stylów i skryptów)
✓ Sprint 5 — Luxury Brand Digital Polish: Universal Quality, Accessibility & Performance Polish (`refactor(ui)`, `test(ui)`, `perf(ui)`)
  - Wdrożenie globalnych mikrointerakcji w `resources/css/components/ui-core.css`: płynne uniesienie przycisków na hover (`translateY(-2px)`), przesuwanie ikon w prawo (`translateX(3px)`), stany active/disabled bez efektu Bootstrapa, editorial table hover oraz pigułkowa nawigacja paginacji w stylu Apple
  - Przeprowadzenie audytu dostępności (Accessibility): uniwersalny, czytelny pierścień focus (`outline: 2px solid var(--color-primary); outline-offset: 3px;`), obsługa preferencji `prefers-reduced-motion` wyłączających animacje, semantyczne oznaczenia ARIA w nagłówku i stopce oraz łącze „Przejdź do treści” (`.skip-to-content`)
  - Eliminacja przesunięć układu (CLS prevention) w widokach `animals/show.blade.php`, `blog/show.blade.php`, `home.blade.php` i `hero.blade.php` poprzez dodanie jawnych atrybutów `width`, `height` oraz `decoding="async"` / `fetchpriority="high"`
✓ Sprint 5 — Luxury Brand Digital Polish: `feat(ui): luxury cta experience` & `feat(ui): footer experience`
  - Utworzenie dedykowanego modułu `resources/css/components/cta.css` z luksusową kompozycją editorial invitation na spokojnym tle `parchment`, napisem wspierającym (eyebrow) w kroju monospace oraz sekcją budującą zaufanie (`.cta-block__note`)
  - Przeprojektowanie stopki (`footer.blade.php`, `footer.css`) z rygorem rytmu wizualnego w stylu Apple / Aesop: dodanie wiersza odznak zaufania (`.footer-trust-badges`), hierarchii kontaktowej z czytelnymi ikonami (`.footer-contact-item`) oraz wykończonymi łączami ze swobodnym animowanym przesunięciem na hover
  - Opracowanie luksusowego formularza newslettera (`.newsletter-form`) z subtelną ramką, ringiem focus z design tokenów oraz gwarancją prywatności (`.newsletter-privacy`)
✓ Sprint 5 — Luxury Brand Digital Polish: `feat(ui): magazine about & blog previews`
  - Wdrożenie redakcyjnej estetyki magazynowej w sekcji About Preview (`about.css`, `home.blade.php`): asymetryczna siatka, trzykolumnowy wiersz statystyk (`.about-preview__stats`) oraz nakładana odznaka ze szkła mrożonego (`.about-preview__badge-overlap`) na dole zdjęcia
  - Odświeżenie kart blogowych w sekcji Blog Preview (`articles.css`, `blog-card.blade.php`): precyzyjne proporcje 16:10, odznaka kategorii w lewym dolnym rogu na zdjęciu (`bottom-left overlay`), hierarchia metadanych w kroju monospace oraz interaktywne łącze „Czytaj artykuł →”
  - Optymalizacja wykończenia obrazów i zapobieganie CLS poprzez jawne atrybuty `width="800" height="500"` i `decoding="async"`
✓ Sprint 5 — Luxury Brand Digital Polish: `feat(ui): luxury testimonial cards`
  - Przeprojektowanie sekcji opinii (`testimonials.css`, `home.blade.php`) na luksusowe karty ze spokojną, redakcyjną typografią i powiększonym światłem (`padding: var(--sp-xl)`)
  - Dodanie wiersza nagłówkowego z 5-gwiazdkową oceną w stonowanym odcieniu złota (`#b58c28`) oraz pigułkowej odznaki zweryfikowanej adopcji (`.testimonial__verified`)