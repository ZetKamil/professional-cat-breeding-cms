<x-frontend.shell
    title="O Nas — Pasja, Certyfikaty i Domowa Socjalizacja | Domowa Hodowla Kotów z Mazowieckiej Szwajcarii"
    meta-description="Poznaj naszą domową hodowlę kotów rasowych w Sikorzu k. Płocka. Członek SHiOZ ZOOLANDIA (Certyfikat 58/CW/2025, Rej. 58/P/2025). Opieka weterynaryjna, badania rodziców i domowa socjalizacja."
    og-image="{{ asset('image/about/hero_bg.jpg') }}"
>
    {{-- ============================================================
         1. HERO — Editorial, photography-first
         ============================================================ --}}
    <x-frontend.hero
        eyebrow="Nasza Historia"
        title="Domowa hodowla z pasją<br>i pełną troską."
        lead="Każdy kot, który opuszcza naszą domową hodowlę, niesie ze sobą doświadczenie, miłość i bezkompromisową troskę o zdrowie i rozwój."
        image-url="{{ asset('image/about/hero_bg.jpg') }}"
        image-position="center 60%"
        image-alt="Ciepła i jasna przestrzeń domowa hodowli kotów rasowych z kotem bengalskim"
        scroll-target="#nasza-filozofia"
        size="large"
        data-nav-theme="dark"
    />

    {{-- ============================================================
         2. STORY — Two-column editorial
         ============================================================ --}}
    <x-frontend.section id="nasza-filozofia" class="reveal-up">
        <div class="about-story">
            <div class="about-story__text">
                <x-frontend.section-header
                    align="left"
                    eyebrow="Nasza filozofia"
                    headline="Zdrowie i piękno idą w parze"
                />
                <div class="about-story__body">
                    <p class="text-body text-ink-muted-80">
                        Nasza przygoda z hodowlą zaczęła się od fascynacji wyjątkowym charakterem
                        i pięknem kotów rasowych. Z biegiem lat ta pasja przerodziła się w profesjonalną
                        hodowlę, w której dobrostan zwierząt stoi na bezwzględnym pierwszym miejscu.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Wierzymy, że odpowiedzialna hodowla to nie tylko piękne koty — to przede
                        wszystkim gigantyczna odpowiedzialność. Każdy dorosły kot hodowlany jest objęty pakietem badań genetycznych dedykowanych dla danej rasy
                        oraz regularną kontrolą kardiologiczną (echo serca HCM), a kocięta rozwijają się pod stałym nadzorem lekarza weterynarii.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Prowadzimy kameralną hodowlę domową, co pozwala nam
                        poświęcić każdemu kociakowi maksimum uwagi, czułości i bezpiecznej stymulacji.
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
         4. VALUES — Dark tile
         ============================================================ --}}
    <x-frontend.section tile="dark" id="wartosci" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Nasze wartości"
            headline="Na czym nam zależy"
            description="Każda decyzja, którą podejmujemy, jest podyktowana tymi zasadami."
        />

        <div class="values-grid">
            <div class="value-item">
                <div class="value-item__number">01</div>
                <h3 class="value-item__title text-tagline">Transparentność</h3>
                <p class="value-item__desc text-body">
                    Pełna dokumentacja zdrowotna, rodowody, wyniki badań genetycznych —
                    wszystko jest jawne i dostępne dla przyszłych opiekunów.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">02</div>
                <h3 class="value-item__title text-tagline">Zdrowie</h3>
                <p class="value-item__desc text-body">
                    Badania genetyczne i kardiologiczne rodziców, pełna profilaktyka kociąt, stały nadzór weterynaryjny — zdrowie jest priorytetem numer jeden.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">03</div>
                <h3 class="value-item__title text-tagline">Socjalizacja</h3>
                <p class="value-item__desc text-body">
                    Koty rosną w domowym środowisku, w ciągłym kontakcie z ludźmi. Są doskonale przyzwyczajone do codziennego życia rodzinnego.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">04</div>
                <h3 class="value-item__title text-tagline">Wsparcie po zakupie</h3>
                <p class="value-item__desc text-body">
                    Nie kończymy relacji w momencie przekazania kota. Pomagamy w adaptacji,
                    doradzamy w kwestiach żywienia, zdrowia i wychowania przez całe życie kota.
                </p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         5. AWARDS & CERTIFICATES
         ============================================================ --}}
    <x-frontend.section id="nagrody" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Certyfikacja i Bezpieczeństwo"
            headline="Oficjalne Certyfikaty"
        />

        <div class="awards-grid">
            <div class="award-card">
                <i data-lucide="award" class="award-card__icon"></i>
                <h3 class="text-body-strong">SHiOZ ZOOLANDIA</h3>
                <p class="text-nav text-ink-muted-48">Certyfikat Członkowski: 58/CW/2025 · Rejestracja Hodowli: 58/P/2025</p>
            </div>
            <div class="award-card">
                <i data-lucide="shield-check" class="award-card__icon"></i>
                <h3 class="text-body-strong">Wolni od FIV/FeLV</h3>
                <p class="text-nav text-ink-muted-48">Badania ujemne dla wszystkich kotów hodowlanych</p>
            </div>
            <div class="award-card">
                <i data-lucide="dna" class="award-card__icon"></i>
                <h3 class="text-body-strong">Badania Genetyczne Rodziców</h3>
                <p class="text-nav text-ink-muted-48">Badania właściwe dla rasy (PRA-b, PK-Def, PKD) oraz echo serca</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         6. GALLERY — Luxury Masonry
         ============================================================ --}}
    <x-frontend.section tile="dark" id="galeria" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Galeria"
            headline="Życie w hodowli"
            description="Momenty zatrzymane w kadrze. Poznaj nasze codzienne życie z kotami."
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

        <div class="gallery-cta">
            <x-frontend.button variant="secondary" href="https://www.facebook.com/profile.php?id=61580668026948"
                target="_blank" rel="noopener">
                Obserwuj nas na Facebooku →
            </x-frontend.button>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         7. WHAT YOU GET (Standard)
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="co-otrzymujesz" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Standard"
            headline="Dokumentacja i standard"
        />

        <div class="benefits-grid">
            <div class="benefit-item">
                <i data-lucide="file-check" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Książeczka zdrowia</h3>
                <p class="benefit-item__desc">Kompletna historia szczepień i zabiegów</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="scroll-text" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Rodowód SHiOZ ZOOLANDIA</h3>
                <p class="benefit-item__desc">Wielopokoleniowy certyfikat rodowodowy wydany przez SHiOZ ZOOLANDIA</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="dna" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Badania rodziców</h3>
                <p class="benefit-item__desc">Wyniki testów DNA i badań kardiologicznych rodziców dedykowanych dla rasy</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="syringe" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">2x Szczepienie i Chip</h3>
                <p class="benefit-item__desc">2 obowiązkowe szczepienia oraz wszczepiony, zarejestrowany mikrochip</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="bug-off" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">2x Odrobaczanie</h3>
                <p class="benefit-item__desc">2 cykle odrobaczania potwierdzone wpisami w książeczce zdrowia</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="phone" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Wsparcie po zakupie</h3>
                <p class="benefit-item__desc">Bezpłatna pomoc, stały kontakt i doradztwo przez całe życie kota</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         8. CONTACT CTA
         ============================================================ --}}
    <x-frontend.conversion-cta source-context="O Nas" />

</x-frontend.shell>
