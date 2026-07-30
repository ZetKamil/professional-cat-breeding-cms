{{--
    Animal Health Panel Component — Luxury Design System
    
    Usage:
    <x-frontend.animal-health-panel />
    
    Displays the 4-pillar health & trust standards for our breeding cattery.
--}}

<x-frontend.section tile="dark" id="zdrowie" class="reveal-up">
    <x-frontend.section-header
        eyebrow="Zaufanie i Zdrowie"
        headline="Standard Zdrowotny Naszej Hodowli"
        description="Każdy kot w naszej hodowli objęty jest kompleksową profilaktyką medyczną i badaniami genetycznymi."
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
                Rodzice są w pełni wolni od chorób genetycznych właściwych dla rasy (HCM, PKD, SMA n/n) z weryfikowanymi certyfikatami laboratoryjnymi.
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
                Hodowla pozostaje pod stałą, dedykowaną opieką weterynaryjną. Wszystkie nasze koty są wolne od wirusa niedoboru odporności i białaczki.
            </p>
        </div>
        <div class="trust-pillar">
            <div class="trust-pillar__header">
                <span class="trust-pillar__index">03</span>
                <div class="trust-pillar__icon">
                    <i data-lucide="scroll-text" aria-hidden="true"></i>
                </div>
            </div>
            <h3 class="trust-pillar__title">Rodowód FIFe / FPL</h3>
            <p class="trust-pillar__desc">
                Pełna dokumentacja medyczna, książeczka zdrowia, mikroczip ISO oraz oryginalny, pięciopokoleniowy rodowód Międzynarodowej Federacji Felinologicznej.
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
                Koty wychowują się z nami w domowym zaciszu, w stałym kontakcie z człowiekiem, co gwarantuje im poczucie bezpieczeństwa i otwartość.
            </p>
        </div>
    </div>

    {{-- Premium Editorial Separator & Guarantee Banner --}}
    <div class="mt-12 pt-8 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <i data-lucide="award" class="text-primary w-6 h-6" aria-hidden="true"></i>
            <div>
                <span class="text-sm font-semibold text-white block">Certyfikat Zaufania FIFe / FPL</span>
                <span class="text-xs text-white/60">Hodowla zarejestrowana i monitorowana według najwyższych standardów etycznych</span>
            </div>
        </div>
        <a href="{{ route('about') }}#certyfikaty" class="text-xs uppercase font-mono tracking-wider text-primary hover:underline">
            Poznaj naszą dokumentację →
        </a>
    </div>
</x-frontend.section>
