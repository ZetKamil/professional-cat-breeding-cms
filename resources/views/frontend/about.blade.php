<x-frontend.shell
    title="O Nas — {{ config('app.name') }}"
    meta-description="Poznaj naszą hodowlę kotów rasowych — naszą historię, filozofię i standardy, które wyróżniają nas na tle innych."
>
    {{-- ============================================================
         1. HERO — Editorial, photography-first
         ============================================================ --}}
    <x-frontend.hero
        eyebrow="Nasza Historia"
        title="Hodowla z pasją<br>od pokoleń."
        lead="Każdy kot, który opuszcza naszą hodowlę, niesie ze sobą lata doświadczenia, miłości i bezkompromisowej troski o zdrowie."
        image-url="https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=2000&q=80"
        image-alt="Spokojny kot w promieniach słońca"
        scroll-target="#nasza-filozofia"
        size="large"
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
                        Wierzymy, że profesjonalna hodowla to nie tylko piękne koty — to przede
                        wszystkim gigantyczna odpowiedzialność. Każdy kot jest regularnie badany genetycznie
                        i kardiologicznie, kontrolowany przez zaufanego weterynarza i wychowywany w naszej sypialni.
                    </p>
                    <p class="text-body text-ink-muted-80">
                        Nie jesteśmy wielką fermą. Mamy zaledwie kilka miotów rocznie, co pozwala nam
                        poświęcić każdemu kociakowi maksimum uwagi.
                    </p>
                </div>
            </div>
            <div class="about-story__image-wrapper">
                <img 
                    src="https://images.unsplash.com/photo-1533743983669-94fa5c4338ec?auto=format&fit=crop&w=1000&q=80" 
                    alt="Kot i właściciel"
                    class="about-story__image"
                    loading="lazy"
                >
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         3. TIMELINE — Milestones of the cattery
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="historia" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Kamienie milowe"
            headline="Nasza Droga"
            description="Historia budowania zaufania krok po kroku."
        />

        <div class="timeline">
            <div class="timeline__item">
                <div class="timeline__year text-display-md">2015</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Początki naszej pasji</h3>
                    <p class="text-body text-ink-muted-80">Pierwszy kot rasowy zamieszkał w naszym domu, zmieniając nasze życie na zawsze. Rozpoczęliśmy intensywną naukę o genetyce i behawiorystyce.</p>
                </div>
            </div>
            
            <div class="timeline__item">
                <div class="timeline__year text-display-md">2017</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Rejestracja hodowli (FIFe)</h3>
                    <p class="text-body text-ink-muted-80">Oficjalnie dołączyliśmy do Międzynarodowej Federacji Felinologicznej, przyjmując najwyższe światowe standardy etyczne i hodowlane.</p>
                </div>
            </div>

            <div class="timeline__item">
                <div class="timeline__year text-display-md">2019</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Pierwszy prestiżowy miot</h3>
                    <p class="text-body text-ink-muted-80">Na świat przyszedł miot "A". Pięć zdrowych, silnych i wspaniale zsocjalizowanych kociąt trafiło do kochających domów w całej Europie.</p>
                </div>
            </div>

            <div class="timeline__item">
                <div class="timeline__year text-display-md">2024</div>
                <div class="timeline__content">
                    <h3 class="text-tagline">Grand International Champion</h3>
                    <p class="text-body text-ink-muted-80">Nasz wspaniały kocur zdobył zaszczytny tytuł na międzynarodowej wystawie, potwierdzając wybitną zgodność naszej linii ze wzorcem rasy.</p>
                </div>
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
                    wszystko jest dostępne dla przyszłych właścicieli.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">02</div>
                <h3 class="value-item__title text-tagline">Zdrowie</h3>
                <p class="value-item__desc text-body">
                    Badania genetyczne, regularne kontrole weterynaryjne, odpowiedzialny
                    dobór par hodowlanych — zdrowie jest priorytetem numer jeden.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">03</div>
                <h3 class="value-item__title text-tagline">Socjalizacja</h3>
                <p class="value-item__desc text-body">
                    Koty rosną w domowym środowisku, w kontakcie z ludźmi i innymi
                    zwierzętami. Są doskonale przyzwyczajone do codziennego życia.
                </p>
            </div>
            <div class="value-item">
                <div class="value-item__number">04</div>
                <h3 class="value-item__title text-tagline">Wsparcie</h3>
                <p class="value-item__desc text-body">
                    Nie kończymy relacji w momencie adopcji. Pomagamy w adaptacji,
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
            eyebrow="Prestiż"
            headline="Nasze Certyfikaty"
        />

        <div class="awards-grid">
            <div class="award-card">
                <i data-lucide="award" class="award-card__icon"></i>
                <h3 class="text-body-strong">Certyfikowana Hodowla FIFe</h3>
                <p class="text-nav text-ink-muted-48">Federation Internationale Feline</p>
            </div>
            <div class="award-card">
                <i data-lucide="shield-check" class="award-card__icon"></i>
                <h3 class="text-body-strong">Wolni od FIV/FeLV</h3>
                <p class="text-nav text-ink-muted-48">Badania ujemne dla wszystkich kotów hodowlanych</p>
            </div>
            <div class="award-card">
                <i data-lucide="dna" class="award-card__icon"></i>
                <h3 class="text-body-strong">Certyfikat Badań Genetycznych</h3>
                <p class="text-nav text-ink-muted-48">HCM, PKD, SMA n/n</p>
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
                <img src="https://images.unsplash.com/photo-1548247416-ec66f4900b2e?auto=format&fit=crop&w=800&q=80" alt="Kot" loading="lazy">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?auto=format&fit=crop&w=600&q=80" alt="Kociak" loading="lazy">
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1526336024174-e58f5cdd8e13?auto=format&fit=crop&w=600&q=80" alt="Bawiący się kot" loading="lazy">
            </div>
            <div class="gallery-item gallery-item--wide">
                <img src="https://images.unsplash.com/photo-1533738363-b7f9aef128ce?auto=format&fit=crop&w=800&q=80" alt="Odpoczywający kot" loading="lazy">
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         7. WHAT YOU GET (Standard)
         ============================================================ --}}
    <x-frontend.section tile="parchment" id="co-otrzymujesz" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Standard"
            headline="Wyprawka i dokumentacja"
        />

        <div class="benefits-grid">
            <div class="benefit-item">
                <i data-lucide="file-check" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Książeczka zdrowia</h3>
                <p class="benefit-item__desc">Kompletna historia szczepień i zabiegów</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="scroll-text" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Rodowód FPL/FIFe</h3>
                <p class="benefit-item__desc">Zarejestrowany rodowód z pełną genealogią</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="dna" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Badania genetyczne</h3>
                <p class="benefit-item__desc">Wyniki testów DNA obojga rodziców</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="syringe" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Szczepienia</h3>
                <p class="benefit-item__desc">Kompletny cykl szczepień adekwatny do wieku</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="bug-off" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Odrobaczanie</h3>
                <p class="benefit-item__desc">Regularne odrobaczanie od 2. tygodnia życia</p>
            </div>
            <div class="benefit-item">
                <i data-lucide="phone" aria-hidden="true" class="benefit-item__icon"></i>
                <h3 class="benefit-item__title text-body-strong">Wsparcie dożywotnie</h3>
                <p class="benefit-item__desc">Bezpłatna pomoc i doradztwo przez całe życie</p>
            </div>
        </div>
    </x-frontend.section>

    {{-- ============================================================
         8. CONTACT CTA
         ============================================================ --}}
    <div class="reveal-up">
        <x-frontend.cta
            tile="parchment"
            eyebrow="Rozmowa i Adopcja"
            headline="Chcesz poznać nas bliżej?"
            description="Zapraszamy do kontaktu — chętnie opowiemy więcej o naszej hodowli, doradzimy i odpowiemy na wszystkie pytania."
            buttonText="Skontaktuj się z nami"
            buttonHref="{{ route('contact') }}"
        />
    </div>

</x-frontend.shell>
