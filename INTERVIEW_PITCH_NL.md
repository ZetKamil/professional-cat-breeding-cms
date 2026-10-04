# 🎤 Sollicitatiegesprek Cheat Sheet (Simpel & Professioneel Nederlands)

Ten plik możesz mieć otwarty na boku ekranu podczas rozmowy.
Zdania są krótkie, proste do wymówienia i brzmią w 100% naturalnie dla każdego programisty w Belgii/Flandrii.

---

## 1. De Opening / Elevator Pitch (30 seconden)
*Kiedy padnie pytanie: "Vertel eens iets over dit project?" (Opowiedz o tym projekcie)*

> **"Ik heb een AI-module gebouwd voor de website van een professionele kattenkwekerij.**  
>  
> **Het doel was simpel: de klant wil goede artikels op Google, maar heeft geen tijd om lange teksten te schrijven.**  
>  
> **Mijn AI-agent maakt automatisch volledige artikels met Google Gemini.**  
> **Het speciale is: de AI gebruikt echte data uit onze database, zoals de namen van de katten en medische testen.**  
>  
> **De klant kan de tekst daarna heel makkelijk aanpassen in het admin panel, zonder HTML te kennen."**  
---

## 2. De Tech Stack (Krótko i konkretnie)
*Kiedy padnie pytanie: "Welke technologieën heb je gebruikt?" (Jakich technologii użyłeś?)*

* **"Backend:** Laravel 11 met PHP 8."
* **"Frontend / UI:** Livewire 3 met Alpine.js. Dat geeft een snelle Single Page Application ervaring, zonder dat we React of Vue nodig hebben."
* **"AI Engine:** Google Gemini API met een strikt JSON-schema."
* **"Database & Data:** MySQL, we halen echte katten en stambomen op als context voor de prompt."
* **"Testing & Quality:** Meer dan 120 geautomatiseerde tests in PHPUnit en Pest voor security en permissies."

---

## 3. Hoe werkt de AI-agent? (In 3 simpele stappen)
*Kiedy padnie pytanie: "Hoe werkt die agent precies onder de motorkap?" (Jak to działa pod maską?)*

> 1. **"Eerst kijken we naar zoekintenties en Google Analytics data om te zien wat mensen zoeken."**  

>  
> 2. **"Daarna sturen we die vraag naar Gemini, samen met echte info over onze katten uit de database."**  
> 3. **"Gemini geeft geen platte tekst terug, maar een nette JSON structuur: een introductie, tips, een FAQ en SEO-tags."** 
>  
> **"Het artikel wordt opgeslagen als 'draft', zodat de klant het eerst zelf kan nakijken."** 
---

## 4. Twee echte problemen die ik heb opgelost (Senior Value!)
*Kiedy padnie pytanie: "Wat was een moeilijke technische uitdaging?" (Co było trudnym wyzwaniem technicznym?)*

### Uitdaging 1: Geen kapotte HTML meer (Modulaire blokken)
> **"Vroeger gebruikten we een gewone WYSIWYG-editor.**  
> **Maar als de klant iets aanpaste, ging de HTML-layout soms kapot.**  
> **Daarom heb ik het veranderd naar modulaire blokken.**  
> **Elke sectie is nu een apart veld: een titel, een alinea of een tip.**  
> **Het is heel veilig voor de klant en het design blijft altijd mooi."**  
> *(Wcześniej używaliśmy edytora WYSIWYG, ale klient psuł HTML. Zamieniłem to na modułowe bloki: każda sekcja to osobne pole. Jest bezpiecznie, a design zawsze wygląda ładnie.)*

### Uitdaging 2: De Globale Aanbieding (Global Offer / CTA)
> **"We hebben ook een 'Global Offer' module gebouwd.**  
> **Kittens worden om de paar maanden geboren en gereserveerd.**  
> **Als de status verandert, past de klant dat op één centrale plek aan.**  
> **En die nieuwe info verschijnt meteen onder álle artikels op de blog!"**  
> *(Zrobiliśmy moduł Globalnej Oferty. Kocięta rodzą się co kilka miesięcy. Kiedy zmienia się ich status, klient zmienia to w 1 centralnym miejscu i od razu pojawia się to pod wszystkimi artykułami!)*

---

## 5. Antwoorden op typische interviewvragen (Q&A)

### Vraag 1: "Waarom Livewire en niet React of Vue?"
> **"Omdat Livewire veel sneller te ontwikkelen is voor dit project.**  
> **We blijven volledig in PHP en Laravel.**  
> **We hoeven geen aparte REST API te bouwen en te onderhouden, maar de gebruiker krijgt wel een snelle, reactieve interface."**

### Vraag 2: "Wat als de AI foute dingen verzint (hallucinaties)?"
> **"We hebben twee beveiligingen:**  
> **Ten eerste geven we de AI echte feiten uit onze database mee.**  
> **Ten tweede wordt het artikel nooit direct live gezet. Het is altijd eerst een 'draft' dat door een mens moet worden goedgekeurd."**

### Vraag 3: "Hoe zit het met security en testen?"
> **"We gebruiken Laravel Policies voor autorisatie (alleen admins en editors mogen content beheren).**  
> **En we hebben meer dan 120 tests die security headers, rate limiting en controller flows controleren."**

---

## 💡 Handige Vlaamse zinnen voor tijdens het gesprek:
- **"Precies."** *(Dokładnie / Właśnie tak)*
- **"Dat klopt."** *(Zgadza się)*
- **"Mag ik even mijn scherm delen om het te tonen?"** *(Czy mogę udostępnić ekran, żeby to pokazać?)*
- **"Hier zie je het dashboard..."** *(Tutaj widzicie dashboard...)*
- **"Het idee hierachter was..."** *(Idea stojąca za tym była taka, że...)*
- **"Hebben jullie hier nog vragen over?"** *(Czy macie do tego jeszcze jakieś pytania?)*
