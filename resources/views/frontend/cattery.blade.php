<x-frontend.shell title="O Hodowli — {{ config('app.name') }}"
    meta-description="Poznaj nasze standardy felinologiczne, Trzyfilarowy Kodeks Zaufania oraz jasny, spokojny proces adopcji kota rasowego.">
    {{-- ============================================================
    1. HERO — Editorial, photography-first
    ============================================================ --}}
    <x-frontend.hero eyebrow="Felinologia i Standardy" title="O Hodowli.<br>Bezkompromisowa Etyka."
        lead="Poznaj naszą filozofię genetyki, Trzyfilarowy Kodeks Zaufania oraz jasny, spokojny proces adopcji każdego kota."
        image-url="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=2000&q=80"
        image-alt="Kot bengalski w naturalnym oświetleniu" scroll-target="#filozofia" size="large"
        data-nav-theme="dark" />

    {{-- ============================================================
    2. PHILOSOPHY — EDITORIAL 3-COLUMN ROLLS ROYCE PILLARS
    ============================================================ --}}
    <x-frontend.section id="filozofia" class="reveal-up">
        <div class="manifesto__grid">
            <div class="manifesto__heading">
                <x-frontend.section-header align="left" eyebrow="Filozofia Naszej Hodowli"
                    headline="Nie budujemy po prostu hodowli. Tworzymy nowe standardy w felinologii."
                    description="Każda decyzja w naszej hodowli jest podyktowana rygorystycznym szacunkiem do genetyki, spokoju socjalizacji oraz bezkompromisowej etyki felinologicznej." />
            </div>

            <div class="manifesto__content">
                <div class="manifesto__tenets" role="list" aria-label="Filary naszej hodowli">
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">01</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Dobrostan Ponad Wszystko</h3>
                            <p class="manifesto-tenet__desc">
                                Nasze koty żyją razem z nami, jako pełnoprawni członkowie rodziny. Nie uznajemy
                                klatkowania ani izolacji – każdy kot ma pełen dostęp do przestrzeni domowej.
                            </p>
                        </div>
                    </div>
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">02</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Czystość Genetyczna i Profilaktyka</h3>
                            <p class="manifesto-tenet__desc">
                                Nie uznajemy półśrodków w medycynie weterynaryjnej. Wszystkie nasze koty hodowlane
                                posiadają kompletne badania genetyczne w kierunku HCM, PKD, SMA oraz ujemny profil
                                FIV/FeLV.
                            </p>
                        </div>
                    </div>
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">03</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Wychowanie w Sercu Domu</h3>
                            <p class="manifesto-tenet__desc">
                                Kocięta od pierwszych minut życia dorastają w domowej przestrzeni, z codziennym odgłosem
                                życia rodzinnego, co przekłada się na ich wyjątkową ufność i otwartość na człowieka.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="manifesto__visual">
                    <div class="manifesto__portrait">
                        <img src="https://images.unsplash.com/photo-1573865526739-10659fec78a5?auto=format&fit=crop&w=800&q=80"
                            alt="Kot w domowym otoczeniu" width="800" height="1066" decoding="async" loading="lazy">
                    </div>
                    <div class="manifesto__stat-card" aria-label="15 lat doświadczenia">
                        <div class="manifesto__stat-number">15+</div>
                        <div class="manifesto__stat-label">Lat selekcji rodowodowej</div>
                    </div>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    3. ARCHITECTURAL HEALTH MATRIX (4-PILLAR CODE OF TRUST)
    ============================================================ --}}
    <x-frontend.section tile="dark" id="kodeks" class="reveal-up" data-nav-theme="dark">
        <x-frontend.section-header eyebrow="Standard Medyczny i Etyka" headline="Kodeks Zaufania"
            description="Standardy, które wyznaczają jakość każdego miotu w naszej hodowli." />

        <div class="kodeks-matrix" role="list" aria-label="Kodeks zaufania">
            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">01 / GENETICS</span>
                    <h3 class="kodeks-column__title">Badania Genetyczne</h3>
                </div>
                <p class="kodeks-column__desc">
                    Weryfikowane certyfikaty HCM, PKD oraz SMA n/n dla każdego rodzica w naszej hodowli.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">02 / VETERINARY</span>
                    <h3 class="kodeks-column__title">Nadzór Kliniczny</h3>
                </div>
                <p class="kodeks-column__desc">
                    Hodowla wolna od wirusów FIV oraz FeLV. Regularne echokardiografie serca i badania krwi.
                </p>
            </div>


            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">03 / BEHAVIOR</span>
                    <h3 class="kodeks-column__title">Domowa Socjalizacja</h3>
                </div>
                <p class="kodeks-column__desc">
                    Wychowanie w pełnym kontakcie z domownikami, kształtujące pewność siebie, delikatność i otwartość.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    4. THE ADOPTION JOURNEY — LUXURY CONCIERGE TIMELINE
    ============================================================ --}}
    <x-frontend.section id="adopcja-krok-po-kroku" class="reveal-up">
        <x-frontend.section-header eyebrow="Concierge Adopcyjny" headline="Jak wygląda proces adopcji?"
            description="Troszczymy się o przyszłość naszych kotów, dlatego każdy etap adopcji jest jasny, spokojny i przejrzysty." />

        <div class="adoption-journey-grid" role="list" aria-label="Proces adopcji krok po kroku">
            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 01</span>
                    <i data-lucide="message-circle-heart" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Rozmowa i Dobór</h3>
                <p class="adoption-journey__desc">
                    Poznajemy Twoje oczekiwania i styl życia, aby pomóc Ci dobrać kota o charakterze idealnie pasującym
                    do Twojego domu.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 02</span>
                    <i data-lucide="home-heart" class="text-ink-muted-48" width="20" height="20" aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Rezerwacja i Wizyta</h3>
                <p class="adoption-journey__desc">
                    Po podjęciu decyzji podpisujemy umowę przedwstępną. Zapraszamy na wizytę osobistą lub spotkanie
                    wideo z maluchem.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 03</span>
                    <i data-lucide="file-check-2" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Odbiór i Dokumentacja</h3>
                <p class="adoption-journey__desc">
                    Kot opuszcza hodowlę w wieku 14-16 tygodni z kompletem dokumentów: certyfikatem rodowodowym stowarzyszenia,
                    książeczką zdrowia oraz badaniami genetycznymi rodziców.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    5. FINAL INVITATION — LUXURY CONCIERGE CTA MONOLITH
    ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta tile="parchment" eyebrow="Zaproszenie do Kontaktu" headline="Zaplanuj Rozmowę"
            description="Napisz do nas — chętnie odpowiemy na wszystkie pytania, doradzimy w wyborze linii genetycznej i umówimy kameralne spotkanie w naszej hodowli."
            buttonText="Skontaktuj się z nami" buttonHref="{{ route('contact') }}" />
    </div>
</x-frontend.shell>