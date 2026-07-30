{{--
    Animal Health Panel Component — Luxury Design System
    
    Usage:
    <x-frontend.animal-health-panel />
    
    Displays the 4-pillar health & trust standards for our breeding cattery.
--}}

<x-frontend.section tile="dark" id="zdrowie" class="reveal-up">
    <x-frontend.section-header
        eyebrow="Zaufanie"
        headline="Standard Zdrowotny"
        description="Każdy kot w naszej hodowli posiada kompletne badania i certyfikaty."
    />

    <div class="trust-grid">
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">01</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="dna" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Badania Genetyczne</h3>
            <p class="trust-pillar__desc">
                Rodzice są w pełni wolni od chorób genetycznych właściwych dla rasy (HCM, PKD, SMA n/n) z weryfikowanymi certyfikatami.
            </p>
        </div>
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">02</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="shield-check" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">FIV / FeLV Negatywny</h3>
            <p class="trust-pillar__desc">
                Hodowla jest pod stałą, dedykowaną opieką weterynaryjną. Koty są wolne od wirusa niedoboru odporności i białaczki.
            </p>
        </div>
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">03</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="scroll-text" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Rodowód FIFe</h3>
            <p class="trust-pillar__desc">
                Pełna dokumentacja medyczna, książeczka zdrowia, mikroczip oraz pięciopokoleniowy rodowód FPL / FIFe.
            </p>
        </div>
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">04</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="home" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Domowa Socjalizacja</h3>
            <p class="trust-pillar__desc">
                Koty wychowują się z nami w domowym zaciszu, naturalnie przyzwyczajone do codziennych odgłosów i kontaktu z człowiekiem.
            </p>
        </div>
    </div>
</x-frontend.section>
