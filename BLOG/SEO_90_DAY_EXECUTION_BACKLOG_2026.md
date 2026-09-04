# SEO 90-DAY EXECUTION BACKLOG 2026
## Hodowla Kotów z Mazowieckiej Szwajcarii

**Okres:** 04.09.2026 → 02.12.2026  
**Domena:** https://kotyzmazowieckiejszwajcarii.pl/  
**Główny cel:** wzrost organicznego ruchu o wysokiej intencji oraz liczby realnych zapytań o kocięta  
**Priorytet:** Bengal → Mazowieckie → Płock → zakup / trust → konwersja  
**Wykonawca:** Antigravity  
**Owner biznesowy:** właściciel hodowli  
**Source of truth:** `AI/SEO_MASTER_PLAN_2026.md` + `AI/SEO_KEYWORD_MAP_2026.md`

---

# 0. JAK CZYTAĆ TEN BACKLOG

To nie jest lista pomysłów. To jest **kolejka wykonawcza**.

Antigravity ma realizować zadania w kolejności:
- P0 = blokujące / najważniejsze,
- P1 = ważne,
- P2 = dopiero po wykonaniu P0/P1.

Każde zadanie ma:
- cel,
- zakres,
- zależności,
- Definition of Done,
- dowód wykonania.

Nie oznaczamy zadania jako DONE na podstawie samego faktu, że kod został zmieniony. DONE wymaga testu lub konkretnego dowodu.

---

# 1. CELE 90 DNI

## Business

1. Zwiększyć liczbę kwalifikowanych wejść organicznych.
2. Zwiększyć wejścia na profile dostępnych kotów bengalskich.
3. Zwiększyć `contact_submit`.
4. Zwiększyć `phone_click`.
5. Zwiększyć `email_click`.
6. Wzmocnić lokalne zapytania związane z Płockiem i Mazowieckiem.
7. Zbudować topical authority wokół kota bengalskiego.

## SEO

1. Uporządkować architekturę Bengal.
2. Wzmocnić pillar page Bengal.
3. Opublikować 16–20 wysokiej jakości artykułów.
4. Zbudować logiczne internal linking.
5. Poprawić CTR stron z impressions.
6. Zdobyć pierwsze stabilne TOP10 dla long-tail/local.
7. Rozpocząć wzrost fraz non-brand.
8. Zbudować zaufanie i sygnały entity/local.

---

# 2. KPI

## Primary

- `contact_submit`
- `phone_click`
- `email_click`

## Secondary

- organic clicks
- organic impressions
- organic CTR
- non-brand clicks
- liczba zapytań lokalnych
- wejścia na `/koty`
- wejścia na profile kotów
- przejścia artykuł → `/koty-bengalskie`
- przejścia `/koty-bengalskie` → profil kota
- przejścia profil → kontakt

## Monitorowane, ale nie jako główny KPI

- liczba opublikowanych artykułów,
- liczba indeksowanych URL,
- liczba fraz w TOP10,
- średnia pozycja.

---

# 3. REGUŁA PRIORYTETU

Jeżeli pojawia się konflikt między:
- publikacją nowego artykułu,
- poprawą istniejącej strony,
- poprawą śledzenia konwersji,
- naprawą błędu indeksacji,

priorytet:

**techniczną możliwość indeksowania → konwersję → pillar / landing → supporting content → eksperymenty.**

---

# 4. GLOBALNE ZASADY DLA ANTIGRAVITY

## ZAKAZ

Nie wolno:
- tworzyć doorway pages dla miast,
- tworzyć stron tylko po to, by umieścić keyword,
- wymyślać danych o zdrowiu,
- wymyślać opinii,
- wymyślać cen,
- tworzyć fałszywych FAQ,
- publikować niezweryfikowanych informacji,
- używać Product/Offer dla profilu kota bez rzeczywistego modelu oferty,
- deklarować „100% zdrowy” bez dokumentacji,
- automatycznie publikować bez kontroli faktów.

## WYMÓG

Każdy artykuł musi mieć:
- primary keyword,
- intent,
- cluster,
- target URL,
- CTA,
- content gap,
- internal links,
- fact check,
- metadata,
- zoptymalizowane obrazy,
- poprawny canonical,
- właściwy schema.

---

# 5. ARCHITEKTURA DOCELOWA

```text
/
├── /koty
│
├── /koty-bengalskie
│   ├── dostępne koty
│   └── profile
│
├── /koty-brytyjskie        [P1]
├── /koty-syjamskie         [P1]
│
├── /o-hodowli
├── /o-nas
├── /baza-wiedzy
└── /kontakt
```

Artykuły:

```text
/baza-wiedzy/*
```

Pillar Bengal jest centrum pierwszego klastra.

---

# 6. 90-DAY ROADMAP — OVERVIEW

| Faza | Dni | Cel |
|---|---:|---|
| Phase 0 | 1–3 | Baseline / audit |
| Phase 1 | 4–10 | Technical + tracking |
| Phase 2 | 11–21 | Bengal pillar + commercial foundation |
| Phase 3 | 22–35 | First content cluster |
| Phase 4 | 36–49 | Trust / health / purchase |
| Phase 5 | 50–63 | Local SEO + commercial pages |
| Phase 6 | 64–77 | Content expansion + internal linking |
| Phase 7 | 78–84 | CRO + SEO refinement |
| Phase 8 | 85–90 | Measurement + next quarter plan |

---

# PHASE 0 — BASELINE
## Dzień 1–3

---

## SEO-001 — Snapshot produkcji

**Priority:** P0

### Zadanie

Przed jakąkolwiek większą zmianą zapisane mają zostać:

- wszystkie indeksowalne URL,
- sitemap URL,
- robots.txt,
- title,
- meta description,
- canonical,
- schema,
- status HTTP,
- H1,
- liczba słów,
- image count,
- alt coverage,
- internal links,
- breadcrumbs,
- Core Web Vitals / PageSpeed baseline.

### DoD

Powstaje:

`AI/SEO_BASELINE_2026-09-04.md`

z tabelą URL → stan przed zmianami.

---

## SEO-002 — Baseline Google Search Console

**Priority:** P0

### Zakres

Pobrać z ostatnich 28 dni:
- clicks,
- impressions,
- CTR,
- average position,
- queries,
- pages,
- device,
- country,
- branded vs non-branded możliwie najbliżej dostępnych danych.

### DoD

Raport baseline + eksport / screenshoty.

---

## SEO-003 — Baseline GA4

**Priority:** P0

Zweryfikować:
- `contact_submit`
- `phone_click`
- `email_click`
- `cat_profile_view`

Jeżeli któregoś eventu brakuje → wpisujemy do Phase 1 jako blocker.

---

# PHASE 1 — TECHNICAL + TRACKING
## Dzień 4–10

---

## SEO-004 — Crawl/indexability audit

**Priority:** P0

Sprawdzić:
- robots,
- sitemap,
- canonical,
- noindex,
- redirecty,
- 404,
- 5xx,
- duplikaty,
- query parameters,
- soft 404,
- indeksowanie profili kotów,
- indeksowanie bloga.

### DoD

Raport:
`AI/SEO_TECHNICAL_AUDIT_2026.md`

Każdy znaleziony problem ma severity:
- Critical
- High
- Medium
- Low

---

## SEO-005 — Canonical + title + description validation

**Priority:** P0

Automatyczny test wszystkich publicznych stron:

- 1 canonical,
- canonical self-referencing, jeśli właściwe,
- unique title,
- unique description,
- no accidental placeholders,
- no duplicated metadata.

### DoD

Automatyczny test + zero Critical.

---

## SEO-006 — Schema validation

**Priority:** P0

Sprawdzić:
- homepage,
- organization/local business,
- contact,
- article,
- animal profile.

### DoD

Brak oczywistych sprzeczności z treścią strony.

Nie dodawać ecommerce schema do profili kotów bez rzeczywistej oferty online.

---

## SEO-007 — GA4 conversion tracking

**Priority:** P0

Wdrożyć / zweryfikować:

```text
contact_submit
phone_click
email_click
cat_profile_view
availability_click
```

### Primary conversion

`contact_submit`

### DoD

Każdy event można ręcznie wywołać w production i potwierdzić w GA4 DebugView / real-time.

---

## SEO-008 — Search Console verification

**Priority:** P0

Potwierdzić:
- property,
- sitemap,
- canonical,
- indexing,
- manual actions,
- page indexing,
- enhancement/status.

### DoD

Brak nierozpoznanego Critical issue.

---

## SEO-009 — SEO dashboard

**Priority:** P1

Utworzyć prostą tabelę:

```text
Date
Organic clicks
Organic impressions
CTR
Avg position
Non-brand clicks
Contact submits
Phone clicks
Email clicks
Cat profile views
```

---

# PHASE 2 — BENGAL FOUNDATION
## Dzień 11–21

---

## SEO-010 — Build Bengal Pillar

**Priority:** P0

Docelowy URL:

`/koty-bengalskie`

### Strona musi odpowiadać na:

- czym jest kot bengalski,
- charakter,
- potrzeby,
- dla kogo,
- zdrowie / odpowiedzialna hodowla,
- dostępne kocięta,
- proces kontaktu,
- lokalizacja hodowli,
- prawdziwe profile kotów,
- linki do wiedzy.

### Primary keywords

- hodowla kota bengalskiego Mazowieckie
- hodowla kota bengalskiego Płock
- kot bengalski hodowla Mazowieckie
- koty bengalskie Mazowieckie
- kocięta bengalskie Mazowieckie
- kot bengalski Płock

### DoD

- content ≥ jednego mocnego pillar page,
- realne dane,
- realne koty,
- CTA,
- internal links,
- schema,
- metadata,
- mobile check,
- linki do 3–6 przyszłych artykułów lub istniejących materiałów.

---

## SEO-011 — Bengal internal linking hub

**Priority:** P0

W `Nasze Koty` dodać czytelne przejście do Bengal pillar.

W Bengal pillar:
- linki do dostępnych kotów,
- link do kontaktu,
- linki do najważniejszych poradników.

---

## SEO-012 — Cat profile SEO

**Priority:** P0

Dla aktualnie dostępnych bengali zweryfikować:
- H1,
- title,
- description,
- canonical,
- OG,
- alt,
- status dostępności,
- data urodzenia,
- rasa,
- płeć,
- fakty zdrowotne,
- rodzice, jeśli publikowane.

### DoD

Zero niespójnych danych.

---

## SEO-013 — Availability freshness

**Priority:** P0

Sprawdzić, czy użytkownik i wyszukiwarka nie widzą:
- sprzedanego kota jako dostępnego,
- starego CTA,
- wygasłej oferty.

---

# PHASE 3 — CONTENT CLUSTER #1
## Dzień 22–35

Publikujemy 4–5 artykułów.

---

## ARTICLE-001

### „Ile kosztuje kot bengalski?”

**Primary keyword:** `ile kosztuje kot bengalski`

**Intent:** C/T

**Target:** `/baza-wiedzy/ile-kosztuje-kot-bengalski`

### Musi odpowiedzieć:

- od czego zależy cena,
- co wpływa na wartość kociaka,
- rodowód,
- opieka,
- badania,
- dokumenty,
- co warto sprawdzić przed zakupem.

### CTA

**Zobacz dostępne kocięta bengalskie**

### Links out

- `/koty-bengalskie`
- 1–2 profile kotów
- `/kontakt`

---

## ARTICLE-002

### „Kot bengalski — charakter i usposobienie”

**Keyword:** `kot bengalski charakter`

**Intent:** I

### CTA

**Poznaj nasze koty bengalskie**

---

## ARTICLE-003

### „Czy kot bengalski nadaje się do mieszkania?”

**Keyword:** `kot bengalski w mieszkaniu`

**Intent:** I/C

### CTA

**Poznaj potrzeby rasy / nasze koty**

---

## ARTICLE-004

### „Jak wybrać hodowlę kota bengalskiego?”

**Keyword:** `jak wybrać hodowlę kota bengalskiego`

**Intent:** C

### CTA

**Poznaj naszą hodowlę**

---

## ARTICLE-005

### „Jak przygotować dom na kota bengalskiego?”

**Keyword:** `jak przygotować dom dla kota bengalskiego`

**Intent:** C/I

### CTA

**Zobacz, kiedy warto skontaktować się z hodowlą**

---

## SEO-014 — Content inventory update

Po każdej publikacji aktualizować:

`AI/SEO_CONTENT_INVENTORY_2026.md`

Kolumny:

```text
ID
Title
Primary keyword
Intent
Cluster
Target URL
CTA
Date published
Indexed
Clicks
Impressions
CTR
Position
Update date
```

---

# PHASE 4 — TRUST / HEALTH / PURCHASE
## Dzień 36–49

Publikujemy 4–5 artykułów.

---

## ARTICLE-006

### „Jakie badania powinien mieć kot bengalski?”

**Keyword:** `badania kota bengalskiego`

**Intent:** I/C

**Wymóg:** fakty zgodne z rzeczywistą dokumentacją.

---

## ARTICLE-007

### „HCM u kota bengalskiego — co warto wiedzieć?”

**Keyword:** `HCM kot bengalski`

**Intent:** I

**Wymóg:** brak porad diagnostycznych udających weterynarza; źródła i ostrożny język.

---

## ARTICLE-008

### „PKD u kota bengalskiego — co sprawdza odpowiedzialna hodowla?”

**Keyword:** `PKD kot bengalski`

**Intent:** I/C

---

## ARTICLE-009

### „Kiedy kocię może opuścić hodowlę?”

**Keyword:** `kiedy kocię może opuścić hodowlę`

**Intent:** C

### CTA

**Zapytaj o dostępne kocięta**

---

## ARTICLE-010

### „Co otrzymuje kocię przy zakupie?”

**Keyword:** `co otrzymuje kocię z hodowli`

**Intent:** C/T

### CTA

**Zapytaj o konkretnego kota**

---

## SEO-015 — Trust block

Na Bengal pillar i /o-hodowli dodać sekcję:

- organizacja / członkostwo — tylko aktualne,
- dokumenty,
- badania,
- sposób opieki,
- lokalizacja,
- kontakt.

Nie używać ogólników bez potwierdzenia.

---

# PHASE 5 — LOCAL SEO + COMMERCIAL
## Dzień 50–63

---

## SEO-016 — Local landing assessment

**Priority:** P0

Przeanalizować, czy potrzebne są osobne:

`/hodowla-kotow-mazowieckie`

`/hodowla-kotow-plock`

### Warunek

Tworzymy stronę tylko, gdy:
- ma unikalną treść,
- nie kopiuje homepage,
- ma własny lokalny intent,
- użytkownik naprawdę z niej skorzysta.

Jeżeli nie → nie tworzyć.

---

## SEO-017 — Local entity consistency

Zweryfikować identyczność danych:
- nazwa,
- telefon,
- miejscowość,
- adres / obszar,
- strona,
- profile zewnętrzne.

---

## SEO-018 — Google Business Profile

Przygotować plan:
- prawdziwe zdjęcia,
- kategoria,
- opis,
- lokalizacja,
- godziny,
- posty / aktualizacje,
- prawdziwe opinie.

Zakaz:
- kupowania opinii,
- generowania opinii,
- podszywania się pod klientów.

---

## ARTICLE-011

### „Hodowla kota bengalskiego Mazowieckie — jak wybrać?”

**Keyword:** `hodowla kota bengalskiego Mazowieckie`

**Intent:** L/C

### CTA

**Poznaj naszą hodowlę**

---

## ARTICLE-012

### „Hodowla kota bengalskiego Płock — na co zwrócić uwagę?”

**Keyword:** `hodowla kota bengalskiego Płock`

**Intent:** L/C/T

### CTA

**Skontaktuj się z hodowlą w okolicach Płocka**

---

## ARTICLE-013

### „Hodowla kotów Płock — jak szukać odpowiedzialnej hodowli?”

**Keyword:** `hodowla kotów Płock`

**Intent:** L/C

### CTA

**Poznaj nasze koty**

Nie tworzyć, jeżeli tekst ma być tylko powtórką artykułu o Bengal.

Musi mieć szerszą, lokalną wartość.

---

# PHASE 6 — PURCHASE + LIFESTYLE
## Dzień 64–77

Publikujemy 4–5 artykułów.

---

## ARTICLE-014

### „Zakup kota bengalskiego krok po kroku”

**Keyword:** `zakup kota bengalskiego krok po kroku`

**Intent:** C/T

---

## ARTICLE-015

### „Kot bengalski a dzieci — co warto wiedzieć?”

**Keyword:** `kot bengalski dla dzieci`

**Intent:** I/C

---

## ARTICLE-016

### „Pierwsze dni kota bengalskiego w domu”

**Keyword:** `pierwsze dni kota bengalskiego w domu`

**Intent:** I/C

---

## ARTICLE-017

### „Co kupić dla kota bengalskiego? Wyprawka”

**Keyword:** `wyprawka dla kota bengalskiego`

**Intent:** I/C

---

## ARTICLE-018

### „Jak sprawdzić rodziców kociaka przed zakupem?”

**Keyword:** `jak sprawdzić rodziców kociaka`

**Intent:** C

---

## SEO-019 — Internal linking pass #1

Przejść przez:
- wszystkie 18 nowych artykułów,
- Bengal pillar,
- `/koty`,
- profile dostępnych kotów,
- `/o-hodowli`,
- `/kontakt`.

### Dla każdego artykułu

Minimum:
- 1 link pillar,
- 1–3 linki contextually related,
- 1 link do następnego kroku,
- 1 CTA.

---

# PHASE 7 — CRO + REFINEMENT
## Dzień 78–84

---

## SEO-020 — Landing page CRO audit

Przetestować:
- widoczność CTA above the fold,
- mobile,
- telefon click,
- mail click,
- formularz,
- dostępność informacji,
- status kotów,
- zaufanie,
- next step.

---

## SEO-021 — Article → conversion path

Dla top 5 artykułów według GSC sprawdzić:

```text
organic landing
→ pillar
→ cat
→ contact
```

Jeśli ścieżka nie jest oczywista:
- poprawić CTA,
- poprawić link,
- skrócić drogę.

---

## SEO-022 — CTR optimization

Po wystarczającej liczbie impressions wytypować:
- top 10 pages z wysokimi impressions i niskim CTR,
- poprawić title,
- poprawić description.

Nie zmieniać URL bez potrzeby.

---

## SEO-023 — Cannibalization check

Sprawdzić, czy dwie strony nie konkurują na tę samą intencję.

Przykład:

```text
/koty-bengalskie
vs
/baza-wiedzy/hodowla-kota-bengalskiego-mazowieckie
```

Jeżeli intent jest taki sam:
- łączyć,
- zmienić zakres,
- przekierować,
- albo zmienić target keyword.

Nie utrzymywać dwóch prawie identycznych stron.

---

# PHASE 8 — MEASUREMENT + NEXT QUARTER
## Dzień 85–90

---

## SEO-024 — 90-day report

Raport musi zawierać:

### Traffic
- organic clicks before / after,
- impressions before / after,
- non-brand growth,
- top landing pages.

### Keywords
- new TOP10,
- new TOP20,
- local keywords,
- Bengal keywords.

### Conversion
- contact_submit,
- phone_click,
- email_click,
- profile views.

### Content
- best 10 articles,
- worst 5,
- pages with impressions but low CTR,
- pages with clicks but poor conversion.

---

## SEO-025 — Update candidates

Wytypować:
- 5 artykułów do aktualizacji,
- 5 stron do CRO,
- 5 keyword opportunities,
- 5 content gaps.

---

## SEO-026 — Q1/2027 backlog

Na podstawie danych przygotować:

```text
TOP 10 NEXT ARTICLES
TOP 5 LANDING IMPROVEMENTS
TOP 5 LOCAL OPPORTUNITIES
TOP 5 TECHNICAL TASKS
TOP 5 CRO EXPERIMENTS
```

---

# 7. WEEKLY EXECUTION RHYTHM

Antigravity ma działać według stałego rytmu.

## Poniedziałek

- GSC check,
- Analytics check,
- content task,
- technical issues.

## Wtorek

- research,
- article brief.

## Środa

- writing / implementation.

## Czwartek

- fact check,
- SEO check,
- linking,
- schema.

## Piątek

- publish,
- indexing,
- analytics verification.

---

# 8. ARTICLE PRODUCTION PROTOCOL

Każdy artykuł tworzymy przez:

```text
1. keyword
2. intent
3. SERP review
4. content gap
5. outline
6. factual source check
7. first-hand input
8. draft
9. SEO review
10. internal linking
11. CTA
12. images
13. schema
14. publish
15. request indexing
16. monitor
```

---

# 9. SERP RESEARCH BRIEF

Przed napisaniem:

```yaml
keyword:
intent:
current_top_results:
dominant_content_type:
common_subtopics:
questions:
competitor_gaps:
local_results:
unique_angle:
internal_links:
cta:
```

Nie kopiować konkurencji.

---

# 10. CONTENT SCORE

Każdy temat przed rozpoczęciem dostaje:

```text
Business value       0–5
Intent               0–5
Ranking opportunity  0–5
Local value          0–5
Content gap          0–5
First-hand value     0–5
Conversion value     0–5
```

Publikujemy najpierw najwyżej ocenione.

---

# 11. DEFINITIONS OF DONE

## Technical

```text
[ ] HTTP 200
[ ] indexable
[ ] canonical correct
[ ] title unique
[ ] description unique
[ ] H1 correct
[ ] schema valid
[ ] mobile checked
[ ] internal links
[ ] no broken links
```

## Content

```text
[ ] intent matched
[ ] answer at top
[ ] useful depth
[ ] first-hand content where possible
[ ] no fluff
[ ] facts checked
[ ] no invented claims
[ ] CTA
```

## Conversion

```text
[ ] contact path exists
[ ] phone path works
[ ] email path works
[ ] cat path exists
[ ] events fire
```

---

# 12. INTERNAL LINKING MAP

## Bengal pillar receives links from:

- cena Bengal,
- charakter Bengal,
- mieszkanie,
- wybór hodowli,
- badania,
- HCM,
- PKD,
- odbiór,
- zakup,
- dzieci,
- wyprawka,
- pierwsze dni,
- local articles.

## Bengal pillar links to:

- available kittens,
- individual cats,
- contact,
- selected knowledge articles.

## Articles link horizontally only when relevant.

---

# 13. CONTENT CLUSTER STATUS BOARD

## Bengal

```text
[P0] Pillar                    TODO
[P0] Cena                      TODO
[P0] Charakter                 TODO
[P0] Mieszkanie                TODO
[P0] Wybór hodowli              TODO
[P1] Przygotowanie domu         TODO
[P1] Badania                    TODO
[P1] HCM                       TODO
[P1] PKD                       TODO
[P1] Odbiór                     TODO
[P1] Co otrzymuje kociak        TODO
[P1] Zakup krok po kroku        TODO
[P1] Dzieci                     TODO
[P1] Pierwsze dni               TODO
[P1] Wyprawka                   TODO
[P1] Rodzice kociaka            TODO
[P0] Local Mazowieckie          TODO
[P0] Local Płock                TODO
```

---

# 14. WHAT NOT TO DO IN 90 DAYS

Nie robić:

### ❌ 20 city landing pages

### ❌ 50 AI articles in one week

### ❌ 10 articles about the same keyword

### ❌ keyword stuffing

### ❌ fake local claims

### ❌ fake reviews

### ❌ fake health claims

### ❌ schema spam

### ❌ mass-produced low-value content

### ❌ redesign całego frontendu „przy okazji SEO”

SEO ma być **kontrolowanym systemem zmian**, nie pretekstem do przebudowy całej aplikacji.

---

# 15. ANTIGRAVITY MASTER EXECUTION PROMPT

Poniższy prompt można przekazać Antigravity jako nadrzędną instrukcję do realizacji backlogu.

---

## PROMPT

Jesteś lead SEO engineerem i technical content strategist dla projektu:

**Hodowla Kotów z Mazowieckiej Szwajcarii**

Domena:
`https://kotyzmazowieckiejszwajcarii.pl/`

Pracujesz na istniejącym projekcie Laravel.

### MUSISZ NAJPIERW PRZECZYTAĆ

```text
AI/SEO_MASTER_PLAN_2026.md
AI/SEO_KEYWORD_MAP_2026.md
AI/AI_CONTEXT.md
AI/ARCHITECTURE_AUDIT.md
AI/COMPONENTS.md
AI/COMPONENT_LIBRARY.md
AI/UI_PATTERNS.md
AI/UX_CHECKLIST.md
```

Jeżeli którykolwiek plik nie istnieje, nie wymyślaj jego zawartości. Zgłoś brak.

---

## CEL

Realizuj `AI/SEO_90_DAY_EXECUTION_BACKLOG_2026.md` etapami.

Nie implementuj całych 90 dni za jednym razem.

Zaczynaj od pierwszego niewykonanego P0.

Po każdym etapie:

1. zmień kod,
2. uruchom testy,
3. wykonaj SEO validation,
4. sprawdź regresję,
5. pokaż dokładnie co zmieniłeś,
6. pokaż pliki,
7. pokaż testy,
8. pokaż czego NIE robiłeś i dlaczego.

---

## STRICT RULES

### 1. Nie zmieniaj architektury aplikacji bez potrzeby.

### 2. Nie twórz stron lokalnych tylko dla keywordów.

### 3. Nie wymyślaj faktów o hodowli ani kotach.

### 4. Nie wymyślaj informacji zdrowotnych.

### 5. Nie dodawaj sztucznego ecommerce schema dla zwierząt.

### 6. Każda nowa strona musi mieć określony intent.

### 7. Każdy artykuł musi wspierać konkretny cluster.

### 8. Każdy artykuł musi mieć jeden primary CTA.

### 9. Każdy artykuł musi linkować do właściwego pillar page.

### 10. Nie publikuj treści bez fact-checkingu.

### 11. Nie usuwaj istniejącego funkcjonalnego SEO/analytics bez uzasadnienia.

### 12. Wszystkie zmiany muszą być kompatybilne z istniejącym Laravel stackiem.

---

## EXECUTION ORDER

Realizuj kolejno:

```text
SEO-001
SEO-002
SEO-003
SEO-004
SEO-005
SEO-006
SEO-007
SEO-008
SEO-009
SEO-010
SEO-011
SEO-012
SEO-013
ARTICLE-001
ARTICLE-002
ARTICLE-003
ARTICLE-004
ARTICLE-005
SEO-014
ARTICLE-006
ARTICLE-007
ARTICLE-008
ARTICLE-009
ARTICLE-010
SEO-015
SEO-016
SEO-017
SEO-018
ARTICLE-011
ARTICLE-012
ARTICLE-013
ARTICLE-014
ARTICLE-015
ARTICLE-016
ARTICLE-017
ARTICLE-018
SEO-019
SEO-020
SEO-021
SEO-022
SEO-023
SEO-024
SEO-025
SEO-026
```

---

# 16. REPORT FORMAT ANTIGRAVITY

Po każdym większym tasku odpowiedź ma być:

```text
TASK:
STATUS:

WHAT CHANGED:
- ...

FILES CHANGED:
- ...

SEO IMPACT:
- ...

CONVERSION IMPACT:
- ...

TESTS:
- ...

VALIDATION:
- ...

RISKS:
- ...

NOT CHANGED:
- ...

NEXT TASK:
- ...
```

---

# 17. 90-DAY SUCCESS CRITERIA

Po 90 dniach chcemy mieć:

```text
[ ] Bengal pillar live
[ ] 18+ strong articles
[ ] internal linking complete
[ ] local SEO improved
[ ] GA4 conversions verified
[ ] Search Console baseline + 90-day comparison
[ ] no critical indexing issues
[ ] no major metadata duplication
[ ] real cat profiles optimized
[ ] health claims verified
[ ] SEO content inventory maintained
[ ] CTR experiments completed
[ ] next-quarter backlog created
```

---

# 18. FINAL STRATEGIC RULE

W pierwszych 90 dniach nie próbujemy „wygrać całego Google”.

Budujemy jedną silną specjalizację:

**KOT BENGALSKI + MAZOWIECKIE + PŁOCK + ODPOWIEDZIALNA HODOWLA + REALNE KOCIĘTA**

Dopiero po zbudowaniu tego klastra rozszerzamy topical authority na British i Siamese.

---

# 19. PRIORITY CHEAT SHEET

```text
P0
├── Technical SEO
├── GA4 conversions
├── Bengal pillar
├── Bengal profiles
├── Price
├── Character
├── Living in apartment
├── Choosing breeder
├── Local Mazowieckie
└── Local Płock

P1
├── Health
├── Purchase process
├── Trust
├── Children
├── First days
└── Kitten preparation

P2
├── British
├── Siamese
└── Broad generic keywords
```

---

# 20. ONE-LINE RULE

**Każda zmiana SEO musi odpowiadać na jedno pytanie: czy zwiększa prawdopodobieństwo, że właściwa osoba znajdzie hodowlę, zaufa jej i skontaktuje się w sprawie konkretnego kota?**

Jeżeli nie — zmiana nie jest priorytetem.
