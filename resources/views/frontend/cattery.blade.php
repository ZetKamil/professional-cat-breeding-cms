<x-frontend.shell title="O Hodowli — Standardy, Filozofia i Kodeks Zaufania | Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Poznaj standardy hodowli kotów z Mazowieckiej Szwajcarii (SHiOZ ZOOLANDIA). Badania kardiologiczne i genetyczne rodziców, etyczna domowa socjalizacja i jasny proces zakupu kocięcia."
    og-image="{{ asset('storage/media/parent_luki_1.jpg') }}">
    {{-- ============================================================
    1. HERO — Editorial, photography-first
    ============================================================ --}}
    <x-frontend.hero eyebrow="Felinologia i Standardy" title="O Hodowli.<br>Bezkompromisowa Etyka."
        lead="Poznaj naszą filozofię opieki, Kodeks Zaufania oraz jasny, przejrzysty proces zakupu i dołączenia kocięcia do Twojego domu."
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
                                są objęte badaniami genetycznymi i kardiologicznymi (HCM, PKD, PRA) oraz posiadają ujemny profil
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
                    <div class="manifesto__stat-card" aria-label="Certyfikacja SHiOZ ZOOLANDIA">
                        <div class="manifesto__stat-number" style="font-size: 1.5rem; letter-spacing: 0.05em;">SHiOZ</div>
                        <div class="manifesto__stat-label">Certyfikat 58/CW/2025 · Rej. 58/P/2025</div>
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
            description="Standardy, które wyznaczają jakość i bezpieczeństwo w naszej hodowli." />

        <div class="kodeks-matrix" role="list" aria-label="Kodeks zaufania">
            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">01 / GENETICS & HEART</span>
                    <h3 class="kodeks-column__title">Genetyka i Kardiologia</h3>
                </div>
                <p class="kodeks-column__desc">
                    Systematyczne badania genetyczne rodziców oraz regularna kontrola kardiologiczna (echo serca) pod kątem HCM i PKD.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">02 / CERTIFICATION & VET</span>
                    <h3 class="kodeks-column__title">SHiOZ ZOOLANDIA</h3>
                </div>
                <p class="kodeks-column__desc">
                    Oficjalne członkostwo 58/CW/2025, rejestracja 58/P/2025. Stały nadzór weterynaryjny, hodowla wolna od FIV oraz FeLV.
                </p>
            </div>

            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">03 / BEHAVIOR</span>
                    <h3 class="kodeks-column__title">Domowa Socjalizacja</h3>
                </div>
                <p class="kodeks-column__desc">
                    Wychowanie w pełnym kontakcie z domownikami, kształtujące pewność siebie, delikatność i otwartość na człowieka.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    4. THE ADOPTION JOURNEY — LUXURY CONCIERGE TIMELINE
    ============================================================ --}}
    <x-frontend.section id="proces-krok-po-kroku" class="reveal-up">
        <x-frontend.section-header eyebrow="Standardy Hodowli" headline="Jak wygląda proces zakupu kocięcia?"
            description="Proces rozpoczyna się od kontaktu — odpowiadamy na pytania, przedstawiamy dostępne kocięta i umawiamy wizytę w hodowli. Po podjęciu decyzji podpisujemy umowę i ustalamy dogodny termin odbioru." />

        <div class="adoption-journey-grid" role="list" aria-label="Proces zakupu kocięcia krok po kroku">
            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 01</span>
                    <i data-lucide="message-circle-heart" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Kontakt i Wybór Kocięcia</h3>
                <p class="adoption-journey__desc">
                    Rozpoczynamy od kontaktu — odpowiadamy na wszystkie pytania, przedstawiamy dostępne kocięta i doradzamy w wyborze malucha.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 02</span>
                    <i data-lucide="home-heart" class="text-ink-muted-48" width="20" height="20" aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Wizyta w Hodowli</h3>
                <p class="adoption-journey__desc">
                    Umawiamy wizytę osobistą w hodowli, aby poznać malucha, jego rodziców oraz domowe warunki, w jakich dorasta.
                </p>
            </div>

            <div class="adoption-journey-pillar" role="listitem">
                <div class="adoption-journey__header">
                    <span class="adoption-journey__number">KROK 03</span>
                    <i data-lucide="file-check-2" class="text-ink-muted-48" width="20" height="20"
                        aria-hidden="true"></i>
                </div>
                <h3 class="adoption-journey__title">Umowa i Odbiór</h3>
                <p class="adoption-journey__desc">
                    Po podjęciu decyzji podpisujemy umowę, ustalamy dogodny termin odbioru oraz przekazujemy kota z kompletem dokumentów i badań.
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