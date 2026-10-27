<x-frontend.shell title="O Domowej Hodowli — Standardy, Filozofia i Kodeks Zaufania | Mazowiecka Szwajcaria"
    meta-description="Poznaj standardy domowej hodowli kotów z Mazowieckiej Szwajcarii (SHiOZ ZOOLANDIA). Badania kardiologiczne i genetyczne rodziców, etyczna domowa socjalizacja i jasny proces zakupu kocięcia."
    og-image="{{ asset('og-cattery.jpg') }}">
    {{-- ============================================================
    1. HERO — Editorial, photography-first
    ============================================================ --}}
    <x-frontend.hero eyebrow="Felinologia i Standardy Domowe" title="O Domowej Hodowli.<br>Bezkompromisowa Etyka i Ciepło."
        lead="Poznaj naszą filozofię opieki, Kodeks Zaufania oraz jasny, przejrzysty proces zakupu i dołączenia kocięcia do Twojego domu."
        image-url="{{ asset('image/cattery/hero_bg.jpg') }}"
        image-position="center 90%"
        image-alt="Arystokratyczny kot brytyjski długowłosy w eleganckim wnętrzu domowej hodowli" scroll-target="#filozofia" size="large"
        class="hero--cattery"
        data-nav-theme="dark" />

    {{-- ============================================================
    2. PHILOSOPHY — EDITORIAL 3-COLUMN ROLLS ROYCE PILLARS
    ============================================================ --}}
    <x-frontend.section id="filozofia" class="reveal-up">
        <div class="manifesto__grid">
            <div class="manifesto__heading">
                <x-frontend.section-header align="left" eyebrow="Filozofia Naszej Hodowli"
                    headline="Nie budujemy po prostu hodowli. Tworzymy nowe standardy w domowej felinologii."
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
                                żadnej izolacji – każdy kot ma pełen dostęp do przestrzeni domowej.
                            </p>
                        </div>
                    </div>
                    <div class="manifesto-tenet" role="listitem">
                        <span class="manifesto-tenet__num">02</span>
                        <div>
                            <h3 class="manifesto-tenet__title">Czystość Genetyczna i Badania Rodziców</h3>
                            <p class="manifesto-tenet__desc">
                                Nie uznajemy półśrodków w medycynie weterynaryjnej. Wszystkie nasze dorosłe koty hodowlane
                                są objęte pakietami badań genetycznych i kardiologicznych dobranymi dedykowanie do danej rasy (PRA-b, PK-Def, PKD, echo serca HCM) oraz posiadają ujemny profil FIV/FeLV.
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
                        <x-frontend.picture 
                            src="{{ asset('image/cattery/manifesto_portrait.jpg') }}"
                            alt="Kot bengalski w luksusowym, domowym otoczeniu hodowli" 
                            width="800" 
                            height="1066" 
                            decoding="async" 
                            loading="lazy"
                            sizes="(max-width: 768px) 100vw, 400px"
                        />
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
    3. OUR STORY & BREED FOUNDATION (STAŚ BLH & NEL BSH)
    ============================================================ --}}
    <x-frontend.section id="nasza-historia" class="reveal-up">
        <div class="about-story">
            <div class="about-story__text">
                <x-frontend.section-header
                    align="left"
                    eyebrow="Nasza Historia i Rasy"
                    headline="Koty Brytyjskie (krótkowłose i długowłose), Bengalskie i Syjamskie"
                />
                <div class="about-story__body">
                    <p class="text-body text-ink-muted-80">
                        Nasza przygoda z hodowlą zrodziła się z autentycznego zachwytu nad naturą, inteligencją
                        i szlachetnym pięknem kotów rasowych. Prowadzimy kameralną hodowlę domową, w której
                        każde kocię traktowane jest indywidualnie – z maksymalną uwagą, czułością i bezpieczną socjalizacją.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Szczególną dumą naszej hodowli są <strong>Koty Brytyjskie hodowane w obu odmianach szaty</strong>:
                        majestatyczne <strong>koty brytyjskie długowłose (BLH)</strong>, których uosobieniem jest nasz arystokratyczny
                        reproduktor <strong>Staś</strong> (colourpoint o magnetycznych błękitnych oczach), oraz klasyczne, aksamitne
                        <strong>koty brytyjskie krótkowłose (BSH)</strong>, reprezentowane przez opiekuńczą kotkę <strong>Nel</strong>.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Obok kotów brytyjskich rozwijamy również linie zwinnych, cętkowanych <strong>kotów bengalskich</strong>
                        oraz niezwykle kontaktowych i lojalnych <strong>kotów syjamskich / tajskich</strong>.
                        Wszystkie nasze koty dorastają ramię w ramię z nami, w atmosferze miłości, bezpieczeństwa i spokoju.
                    </p>
                </div>
            </div>
            <div class="about-story__image-wrapper">
                <x-frontend.picture 
                    src="{{ asset('image/about/story_care.jpg') }}" 
                    alt="Opieka i czułość w hodowli — hodowca z kociakiem brytyjskim colourpoint"
                    class="about-story__image"
                    width="1000"
                    height="1333"
                    decoding="async"
                    loading="lazy"
                    sizes="(max-width: 768px) 100vw, 50vw"
                />
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    4. ARCHITECTURAL HEALTH MATRIX (4-PILLAR CODE OF TRUST)
    ============================================================ --}}
    <x-frontend.section tile="dark" id="kodeks" class="reveal-up" data-nav-theme="dark">
        <x-frontend.section-header eyebrow="Standard Medyczny i Etyka" headline="Kodeks Zaufania"
            description="Standardy, które wyznaczają jakość i bezpieczeństwo w naszej hodowli." />

        <div class="kodeks-matrix" role="list" aria-label="Kodeks zaufania">
            <div class="kodeks-column" role="listitem">
                <div>
                    <span class="kodeks-column__num">01 / GENETICS & HEART</span>
                    <h3 class="kodeks-column__title">Genetyka i Kardiologia Rodziców</h3>
                </div>
                <p class="kodeks-column__desc">
                    Systematyczne badania genetyczne rodziców (PRA-b, PK-Def, PKD) oraz regularna kontrola kardiologiczna (echo serca HCM) dedykowane dla poszczególnych ras.
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
    5. WHAT YOU GET — DOCUMENTATION & STANDARD
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="co-otrzymujesz" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Standard"
            headline="Dokumentacja i standard"
            description="Przekazujemy kocię wyłącznie z pełną, przejrzystą dokumentacją i certyfikatami potwierdzającymi pochodzenie oraz profilaktykę."
        />

        <div class="benefits-grid" role="list" aria-label="Standard i dokumentacja dołączana do kocięcia">
            <div class="benefit-item" role="listitem">
                <i data-lucide="file-check" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Książeczka zdrowia</h3>
                <p class="benefit-item__desc">Kompletna historia szczepień i zabiegów profilaktycznych</p>
            </div>
            <div class="benefit-item" role="listitem">
                <i data-lucide="scroll-text" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Rodowód SHiOZ ZOOLANDIA</h3>
                <p class="benefit-item__desc">Wielopokoleniowy certyfikat rodowodowy wydany przez SHiOZ ZOOLANDIA (58/CW/2025)</p>
            </div>
            <div class="benefit-item" role="listitem">
                <i data-lucide="dna" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Badania rodziców</h3>
                <p class="benefit-item__desc">Wyniki testów DNA i badań kardiologicznych rodziców dedykowanych dla danej rasy</p>
            </div>
            <div class="benefit-item" role="listitem">
                <i data-lucide="syringe" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">2x Szczepienie i Chip</h3>
                <p class="benefit-item__desc">2 obowiązkowe szczepienia oraz wszczepiony, zarejestrowany mikrochip</p>
            </div>
            <div class="benefit-item" role="listitem">
                <i data-lucide="bug-off" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">2x Odrobaczanie</h3>
                <p class="benefit-item__desc">2 cykle odrobaczania potwierdzone wpisami w książeczce zdrowia</p>
            </div>
            <div class="benefit-item" role="listitem">
                <i data-lucide="phone" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Wsparcie po zakupie</h3>
                <p class="benefit-item__desc">Bezpłatna pomoc, stały kontakt i doradztwo przez całe życie kota</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    6. THE ADOPTION JOURNEY — LUXURY CONCIERGE TIMELINE
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
                    <i data-lucide="house-heart" class="text-ink-muted-48" width="20" height="20" aria-hidden="true"></i>
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
                    Po podjęciu decyzji podpisujemy umowę, ustalamy dogodny termin odbioru oraz przekazujemy kocię z książeczką zdrowia (2 szczepienia, 2 odrobaczenia), zarejestrowanym microchipem, rodowodem SHiOZ ZOOLANDIA oraz kopiami badań genetycznych rodziców.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    7. GALLERY — LIFE IN THE CATTERY
    ============================================================ --}}
    <x-frontend.section tile="dark" id="galeria" class="reveal-up" data-nav-theme="dark">
        <x-frontend.section-header
            eyebrow="Galeria i Reproduktory"
            headline="Życie w Naszej Hodowli"
            description="Poznaj nasze koty hodowlane i zobacz ich codzienność w domowym środowisku."
        />

        <div class="gallery-grid">
            <div class="gallery-item gallery-item--large">
                <x-frontend.picture 
                    src="{{ asset('image/hero/stas_studio.jpg') }}" 
                    alt="Nasz arystokratyczny reproduktor Staś — kot brytyjski długowłosy colourpoint" 
                    width="1200" 
                    height="896" 
                    decoding="async" 
                    loading="lazy"
                    sizes="(max-width: 768px) 100vw, 66vw"
                />
            </div>
            <div class="gallery-item">
                <x-frontend.picture 
                    src="{{ asset('image/hero/bars_studio.jpg') }}" 
                    alt="Nasz reproduktor Bars — kot bengalski o umaszczeniu silver rosetted" 
                    width="1200" 
                    height="896" 
                    decoding="async" 
                    loading="lazy"
                    sizes="(max-width: 768px) 100vw, 33vw"
                />
            </div>
            <div class="gallery-item">
                <x-frontend.picture 
                    src="{{ asset('image/hero/baltazar_studio.jpg') }}" 
                    alt="Nasz reproduktor Baltazar — dumny kot syjamski / tajski" 
                    width="1200" 
                    height="896" 
                    decoding="async" 
                    loading="lazy"
                    sizes="(max-width: 768px) 100vw, 33vw"
                />
            </div>
        </div>

        <div class="gallery-cta" style="margin-top: var(--sp-2xl); text-align: center;">
            <x-frontend.button variant="secondary" href="https://www.facebook.com/profile.php?id=61580668026948"
                target="_blank" rel="noopener">
                Obserwuj nas na Facebooku →
            </x-frontend.button>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    8. VERIFIED PATRON STORIES — EDITORIAL QUOTE MONOLITHS
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="opinie" class="reveal-up">
        <x-frontend.section-header eyebrow="Rekomendacje i Zaufanie" headline="Historie Opiekunów Naszych Kotów"
            description="Opinie i wrażenia nowych opiekunów kotów pochodzących z naszej hodowli po przejściu procesu adopcji." />

        <div class="patrons-grid" role="list" aria-label="Rekomendacje opiekunów">
            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Luna jest cudowna — zdrowa, zadbana i przepiękna. Od pierwszych dni widać, że wyrosła w kochającym
                    domowym otoczeniu. Hodowla godna polecenia z całego serca.”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Anna K.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekunka Luny · Kot Brytyjski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>

            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Profesjonalne podejście na każdym etapie, pełna dokumentacja medyczna oraz cierpliwe odpowiadanie
                    na wszystkie nasze pytania. Polecam bez wahania!”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Marek W.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekun Simby · Kot Bengalski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>

            <div class="patron-monolith" role="listitem">
                <p class="patron-monolith__quote">
                    „Widać, że koty w tej hodowli są prawdziwie kochane. Nasz Mruczek był natychmiast oswojony, ufny i
                    szczęśliwy w nowym domu od pierwszego dnia.”
                </p>
                <div class="patron-monolith__author">
                    <div>
                        <strong class="block text-ink">Katarzyna M.</strong>
                        <span class="text-xs text-ink-muted-48">Opiekunka Mruczka · Kot Syjamski</span>
                    </div>
                    <span class="patron-monolith__badge">
                        <i data-lucide="check-circle-2" class="w-4 h-4" aria-hidden="true"></i>
                        Rodowód SHiOZ ZOOLANDIA
                    </span>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
    9. FINAL INVITATION — REUSABLE CONVERSION CTA
    ============================================================ --}}
    <x-frontend.conversion-cta source-context="O Domowej Hodowli" />
</x-frontend.shell>