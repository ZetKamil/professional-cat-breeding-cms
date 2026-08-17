{{--
Animal Health Panel Component — Luxury Design System

Usage:
<x-frontend.animal-health-panel />

Displays the 4-pillar health & trust standards for our breeding cattery.
--}}

<x-frontend.section tile="dark" id="zdrowie" class="reveal-up">
    <x-frontend.section-header eyebrow="Zaufanie i Zdrowie" headline="Standard Zdrowotny Naszej Hodowli"
        description="Wszystkie dorosłe koty hodowlane objęte są badaniami genetycznymi i kardiologicznymi dedykowanymi dla swojej rasy, a kocięta opuszczają nas z pełną profilaktyką weterynaryjną." />

    <div class="trust-grid">
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">01</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="dna" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Badania Genetyczne Rodziców</h3>
            <p class="trust-pillar__desc">
                Wszystkie dorosłe koty hodowlane posiadają certyfikowane badania laboratoryjne dobrane pod kątem danej rasy (m.in. PRA-b i PK-Def dla bengali, PKD dla brytyjczyków, PRA dla syjamów oraz echo serca HCM normal).
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
                Hodowla pozostaje pod stałą opieką weterynaryjną. Nasze stado hodowlane (rodzice) posiada udokumentowany ujemny status w kierunku FIV i FeLV.
            </p>
        </div>

        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">03</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="home" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Profilaktyka Kociąt</h3>
            <p class="trust-pillar__desc">
                Kocięta otrzymują książeczkę zdrowia, komplet szczepień, cykl odrobaczeń, microchip oraz pełną domową socjalizację w stałym kontakcie z ludźmi.
            </p>
        </div>
    </div>

    {{-- Premium Editorial Guarantee Banner --}}
    <div class="trust-guarantee-banner">
        <div class="trust-guarantee-banner__content">
            <div class="trust-guarantee-banner__icon">
                <i data-lucide="award" class="w-5 h-5" aria-hidden="true"></i>
            </div>
            <div>
                <span class="trust-guarantee-banner__title block">Certyfikat Zaufania i Jakości</span>
                <span class="trust-guarantee-banner__desc block">Hodowla zarejestrowana i monitorowana według
                    najwyższych standardów etycznych</span>
            </div>
        </div>
        <a href="{{ route('about') }}#certyfikaty" class="trust-guarantee-banner__link">
            Poznaj naszą dokumentację →
        </a>
    </div>
</x-frontend.section>