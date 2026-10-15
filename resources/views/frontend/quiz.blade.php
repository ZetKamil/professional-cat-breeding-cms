<x-frontend.shell
    title="Dobierz Idealną Rasę Kota w 45 Sekund — Bengalski, Brytyjski czy Syjamski? | Mazowiecka Szwajcaria"
    meta-description="Nie wiesz, jaki kot pasuje do Twojego stylu życia? Wypełnij nasz 4-etapowy quiz behawioralny i poznaj rasę dopasowaną do Ciebie oraz zobacz dostępne maluchy na żywo."
    nav-theme="dark"
>
    <section class="quiz-section hero--dark" data-nav-theme="dark" aria-label="Interaktywny quiz doboru rasy kota">
        <div class="section-inner">
            
            {{-- Quiz Hero Header --}}
            <div class="quiz-hero">
                <span class="quiz-hero__eyebrow">Dopasowanie Behawioralne & Styl Życia</span>
                <h1 class="quiz-hero__title">Dobierz Idealną Rasę w 45 Sekund</h1>
                <p class="quiz-hero__lead">
                    Odpowiedz na 4 pytania o Twój rytm dnia, domowników i oczekiwania. 
                    Nasz algorytm behawioralny wskaże rasę najlepiej dopasowaną do Twojego życia oraz sprawdzi aktualną dostępność kociąt w hodowli.
                </p>
            </div>

            {{-- Main Quiz Container --}}
            <div class="quiz-card" id="quizContainer">
                
                {{-- Progress Bar --}}
                <div class="quiz-progress-wrap" id="quizProgressWrap">
                    <div class="quiz-progress-info">
                        <span id="quizStepLabel">Krok 1 z 4</span>
                        <span id="quizStepPercent">25%</span>
                    </div>
                    <div class="quiz-progress-bar">
                        <div class="quiz-progress-fill" id="quizProgressFill" style="width: 25%;"></div>
                    </div>
                </div>

                {{-- STEP 1: Lifestyle and Energy Level --}}
                <div class="quiz-step is-active" id="quizStep1" data-step="1">
                    <h2 class="quiz-step__question">1. Jak wygląda Twój wymarzony dzień z kotem?</h2>
                    <p class="quiz-step__hint">Wybierz atmosferę, która najlepiej oddaje Twoje oczekiwania:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="1" data-value="calm_couch" onclick="window.selectQuizOption(1, 'calm_couch')">
                            <span class="quiz-option__icon">🛋️</span>
                            <div class="quiz-option__content">
                                <strong>Spokojny relaks, cisza i miękki plusz</strong>
                                <small>Kot, który ceni zrównoważony rytm, odpoczywa obok na kanapie i nie wymaga nieustannej uwagi.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="1" data-value="active_curious" onclick="window.selectQuizOption(1, 'active_curious')">
                            <span class="quiz-option__icon">🐆</span>
                            <div class="quiz-option__content">
                                <strong>Dużo zabawy, inteligencja i energia lamparta</strong>
                                <small>Niezwykle bystry towarzysz, który aportuje zabawki, uczy się sztuczek i zachwyca dzikim wyglądem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="1" data-value="devoted_vocal" onclick="window.selectQuizOption(1, 'devoted_vocal')">
                            <span class="quiz-option__icon">💎</span>
                            <div class="quiz-option__content">
                                <strong>Niezwykle głęboka więź i kocia rozmowa</strong>
                                <small>Kot, który chodzi za Tobą krok w krok, uwielbia spać pod kołdrą i odpowiada melodyjnym głosem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Time away & Household Dynamics --}}
                <div class="quiz-step" id="quizStep2" data-step="2" style="display:none;">
                    <h2 class="quiz-step__question">2. Twój dom i czas spędzany poza nim</h2>
                    <p class="quiz-step__hint">Wskaż sytuację, która najlepiej opisuje Twój dom:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="2" data-value="away_work" onclick="window.selectQuizOption(2, 'away_work')">
                            <span class="quiz-option__icon">🏢</span>
                            <div class="quiz-option__content">
                                <strong>Często pracuję poza domem (7–9 godz. dziennie)</strong>
                                <small>Kot musi ze spokojem i bez lęku separacyjnego znosić chwile samotności.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="home_work" onclick="window.selectQuizOption(2, 'home_work')">
                            <span class="quiz-option__icon">💻</span>
                            <div class="quiz-option__content">
                                <strong>Praca zdalna / stale ktoś jest w domu</strong>
                                <small>Szukam kota, który będzie moim kompanem podczas dnia i lubi bliski kontakt z człowiekiem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="family_children" onclick="window.selectQuizOption(2, 'family_children')">
                            <span class="quiz-option__icon">🏡</span>
                            <div class="quiz-option__content">
                                <strong>Rodzina z dziećmi</strong>
                                <small>Kluczowy jest stabilny układ nerwowy, cierpliwość oraz przyjazne nastawienie do maluchów.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="other_pets" onclick="window.selectQuizOption(2, 'other_pets')">
                            <span class="quiz-option__icon">🐾</span>
                            <div class="quiz-option__content">
                                <strong>Dom z innymi zwierzętami (pies / inny kot)</strong>
                                <small>Zależy mi na otwartym kocie, który łatwo odnajdzie się w stadzie po prawidłowej socjalizacji.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: Coat, Grooming & Interaction Preferences --}}
                <div class="quiz-step" id="quizStep3" data-step="3" style="display:none;">
                    <h2 class="quiz-step__question">3. Jakie cechy wyglądu i pielęgnacji preferujesz?</h2>
                    <p class="quiz-step__hint">Wybierz to, co sprawi Ci najwięcej radości w codziennej opiece:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="3" data-value="short_sleek" onclick="window.selectQuizOption(3, 'short_sleek')">
                            <span class="quiz-option__icon">✨</span>
                            <div class="quiz-option__content">
                                <strong>Krótka, gładka sierść bez podszerstka (zero czesania)</strong>
                                <small>Jedwabista w dotyku, lśniąca szata z dzikim rysunkiem rozet, minimalna pielęgnacja.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="3" data-value="dense_plush" onclick="window.selectQuizOption(3, 'dense_plush')">
                            <span class="quiz-option__icon">🧸</span>
                            <div class="quiz-option__content">
                                <strong>Gęsty, miękki plusz jak u pluszowego niedźwiadka</strong>
                                <small>Okrągłe policzki, aksamitna podwójna okrywa, uwielbiam relaksujące czesanie szczotką.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="3" data-value="interactive_shadow" onclick="window.selectQuizOption(3, 'interactive_shadow')">
                            <span class="quiz-option__icon">💙</span>
                            <div class="quiz-option__content">
                                <strong>Smukła, orientalna sylwetka i żywy dialog</strong>
                                <small>Błękitne migdałowe oczy, kot reagujący na każde Twoje słowo i obecny w każdej czynności.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 4: Timeline & Readiness --}}
                <div class="quiz-step" id="quizStep4" data-step="4" style="display:none;">
                    <h2 class="quiz-step__question">4. Kiedy chciałbyś powitać kociaka w swoim domu?</h2>
                    <p class="quiz-step__hint">Dopasujemy kocięta do Twojego planu i gotowości:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="4" data-value="ready_now" onclick="window.selectQuizOption(4, 'ready_now')">
                            <span class="quiz-option__icon">🟢</span>
                            <div class="quiz-option__content">
                                <strong>Jak najszybciej (maluch gotowy do odbioru)</strong>
                                <small>Szukam w pełni odchowanego kociaka (szczepienia, odrobaczenia, chip, 100% kuweta).</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="4" data-value="ready_future" onclick="window.selectQuizOption(4, 'ready_future')">
                            <span class="quiz-option__icon">⏳</span>
                            <div class="quiz-option__content">
                                <strong>Za 3–6 tygodni (wczesna rezerwacja z nowego miotu)</strong>
                                <small>Chcę spokojnie skompletować wyprawkę i obserwować rozwój maluszka z hodowcą.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="4" data-value="ready_flexible" onclick="window.selectQuizOption(4, 'ready_flexible')">
                            <span class="quiz-option__icon">🌿</span>
                            <div class="quiz-option__content">
                                <strong>Elastycznie – liczy się idealne dopasowanie charakteru</strong>
                                <small>Mogę poczekać na malucha, który skradnie moje serce.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- RESULT SCREEN --}}
                <div class="quiz-result" id="quizResult" style="display:none;">
                    <div class="quiz-result__badge">
                        <span>✨ Twoje Behawioralne Dopasowanie:</span>
                    </div>

                    <h2 class="quiz-result__title" id="quizResultBreedTitle">Kot Bengalski</h2>
                    <p class="quiz-result__desc" id="quizResultBreedDesc"></p>

                    {{-- Personalized Behavioral Note --}}
                    <div id="quizBehavioralNoteBox" style="background: rgba(200, 157, 92, 0.14); border: 1px solid rgba(200, 157, 92, 0.35); border-left: 4px solid var(--color-gold, #c89d5c); padding: 16px 20px; border-radius: 10px; margin: 18px 0 24px; text-align: left; font-size: 0.95rem; line-height: 1.6;">
                        <strong id="quizBehavioralNoteTitle" style="color: var(--color-gold, #c89d5c); display: block; font-size: 1rem; margin-bottom: 6px;">💡 Dlaczego ten wybór pasuje do Twojego rytmu dnia:</strong>
                        <p id="quizBehavioralNoteText" style="margin: 0; color: #f5f0e8; font-weight: 400;"></p>
                    </div>

                    {{-- Matched kittens from database --}}
                    <div class="quiz-result__cats-box">
                        <h3 class="quiz-result__cats-heading" id="quizMatchedCatsHeading">Aktualnie dostępne maluchy tej rasy w hodowli:</h3>
                        
                        {{-- Notice if user chose 'ready_now' but matched breed is in early reservation (like British kittens) --}}
                        <div id="quizFutureNoticeForNow" style="display:none; background: rgba(200, 157, 92, 0.15); border: 1px solid rgba(200, 157, 92, 0.4); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; text-align: left; font-size: 0.92rem; line-height: 1.55; color: #f5f0e8;">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <span style="font-size: 1.6rem; line-height: 1;">⏳</span>
                                <div>
                                    <strong style="color: var(--color-gold, #c89d5c); display: block; font-size: 0.98rem; margin-bottom: 4px;">Szukasz kociaka od zaraz? Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka.</strong>
                                    Maluszki z tego miotu są obecnie pod troskliwą opieką mamy i przechodzą program domowej socjalizacji. Już teraz możesz połączyć się na żywo na WhatsApp, zobaczyć je podczas zabawy i dokonać wczesnej rezerwacji swojego ulubieńca.
                                </div>
                            </div>
                        </div>

                        <div class="quiz-cats-grid" id="quizMatchingCatsGrid">
                            @foreach($animals as $animal)
                                @if($animal->status === \App\Enums\AnimalStatus::Available)
                                    @php
                                        $bLower = mb_strtolower($animal->breed ?? '');
                                        $breedCategory = 'other';
                                        if (str_contains($bLower, 'bengal')) $breedCategory = 'bengal';
                                        elseif (str_contains($bLower, 'brytyj') || str_contains($bLower, 'british')) $breedCategory = 'british';
                                        elseif (str_contains($bLower, 'syjam') || str_contains($bLower, 'siam')) $breedCategory = 'siamese';

                                        $isReadyNow = $animal->isReadyNow();
                                        $badgeLabel = $animal->pickupBadgeLabel();
                                    @endphp
                                    <div class="quiz-cat-item" 
                                         data-breed="{{ $breedCategory }}" 
                                         data-status="available"
                                         data-ready="{{ $isReadyNow ? 'now' : 'future' }}">
                                         <div class="quiz-cat-item__img">
                                            @if($animal->media)
                                                <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('image/hero/bars_studio.jpg') }}" alt="{{ $animal->name }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="quiz-cat-item__info">
                                            <h4>{{ $animal->name }}</h4>
                                            <p>{{ $animal->breed }} • {{ $animal->gender->label() }}</p>
                                            <span class="quiz-cat-item__status">
                                                {{ $isReadyNow ? '🟢 ' . $badgeLabel : '⏳ ' . $badgeLabel }}
                                            </span>
                                        </div>
                                        <button type="button" 
                                                class="quiz-cat-item__btn" 
                                                onclick="window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($badgeLabel) }}')">
                                            📹 Zobacz na żywo
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Alert when 0 kittens of this breed exist in database or all are booked --}}
                        <div id="quizNoMatchingCatsNotice" style="display:none; background: rgba(200, 157, 92, 0.12); border: 1px solid rgba(200, 157, 92, 0.35); border-radius: 12px; padding: 22px; text-align: center; margin-top: 16px; color: #f5f0e8;">
                            <span style="font-size: 2rem; display: block; margin-bottom: 8px;">🗓️</span>
                            <h4 id="quizNoCatsNoticeTitle" style="font-size: 1.05rem; font-weight: 700; color: var(--color-gold, #c89d5c); margin-bottom: 6px;">Planujemy kolejny miot tej rasy</h4>
                            <p id="quizNoCatsNoticeText" style="font-size: 0.92rem; color: #e8e3d8; line-height: 1.6; margin-bottom: 16px;">
                                Aktualnie wszystkie maluchy tej rasy z obecnych miotów znalazły już swoje domy lub są w trakcie rezerwacji. Skontaktuj się z nami, aby zapisać się na Listę Pierwszeństwa na WhatsApp na najbliższe narodziny!
                            </p>
                            <button type="button" class="btn btn-primary" onclick="window.contactBreederFromQuiz()">
                                📹 Zapisz się na Listę Pierwszeństwa na WhatsApp
                            </button>
                        </div>

                        {{-- Alternative recommendations if user wanted ready_now and matched breed isn't ready today, OR if 0 kittens matched --}}
                        <div id="quizAlternativesBox" style="display:none; margin-top: 28px; padding-top: 22px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
                            <div style="background: rgba(37, 211, 102, 0.1); border: 1px solid rgba(37, 211, 102, 0.3); border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; text-align: left;">
                                <strong id="quizAltBoxTitle" style="color: #25d366; font-size: 0.96rem; display: flex; align-items: center; gap: 8px;">
                                    <span>⚡</span> Zależy Ci na odbiorze kociaka już w ten weekend?
                                </strong>
                                <p id="quizAltBoxDesc" style="margin: 4px 0 0; font-size: 0.88rem; color: #e6f7ec; line-height: 1.5;">
                                    Poznaj nasze w pełni odchowane maluchy innych ras, które są po komplecie szczepień, zaczipowane i nauczone czystości, gotowe do odbioru od ręki:
                                </p>
                            </div>
                            <div class="quiz-cats-grid" id="quizAlternativeCatsGrid"></div>
                        </div>
                    </div>

                    {{-- Primary actions after quiz completion --}}
                    <div class="quiz-result__actions" style="margin-top: 28px;">
                        <button type="button" id="quizPrimaryCta" class="quiz-cta-btn quiz-cta-btn--primary" onclick="window.contactBreederFromQuiz()">
                            📹 Porozmawiaj z hodowcą o dopasowaniu (WhatsApp)
                        </button>
                        <button type="button" class="quiz-cta-btn quiz-cta-btn--secondary" onclick="window.openCallbackFromQuiz()">
                            📞 Poproś o szybkie oddzwonienie
                        </button>
                    </div>

                    <div style="text-align: center; margin-top: 24px;">
                        <button type="button" class="quiz-restart-btn" onclick="window.restartQuiz()">
                            🔄 Wypełnij quiz od nowa
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- Multi-Criteria Vanilla JS Quiz State Machine --}}
    <x-slot:scripts>
    <script>
        (function() {
            const quizState = {
                step1: null, // energy & lifestyle
                step2: null, // household & time away
                step3: null, // coat & interaction
                step4: null, // timeline
            };

            const breedInfo = {
                bengal: {
                    title: 'Kot Bengalski (Przygoda, Inteligencja i Dzikie Piękno)',
                    desc: 'Twoje odpowiedzi wskazują, że pokochasz kota bengalskiego! To pełen pasji, bystry i towarzyski przyjaciel, który łączy urok miniaturowego lamparta z ogromną miłością do człowieka. Wychowując się w naszym domu, bengale doskonale reagują na zabawy z wędką, aportowanie i wspólne odkrywanie zakamarków.',
                    name: 'Kot Bengalski'
                },
                british: {
                    title: 'Kot Brytyjski Krótkowłosy (Arystokratyczny Spokój i Plusz)',
                    desc: 'Dla Twojego stylu życia stworzony jest kot brytyjski! To uosobienie domowego spokoju, łagodności i pluszowego ciepła. Doskonale radzi sobie zarówno podczas chwil samotności, jak i leniwych wieczorów. Nie narzuca się, lecz z uwielbieniem czuwa w pobliżu swojego człowieka.',
                    name: 'Kot Brytyjski'
                },
                siamese: {
                    title: 'Kot Syjamski (Niezrównana Więź, Cień Człowieka i Dialog)',
                    desc: 'Twoim idealnym kompanem jest kot syjamski! Jeśli pragniesz przyjaciela, który naprawdę uczestniczy w każdej chwili Twojego dnia, chodzi za Tobą krok w krok, rozmawia melodyjnym głosem i śpi przytulony do ramienia — ta rasa skradnie Twoje serce bez reszty.',
                    name: 'Kot Syjamski'
                }
            };

            window.selectQuizOption = function(step, value) {
                quizState['step' + step] = value;

                if (step === 1) {
                    goToStep(2, '50%');
                } else if (step === 2) {
                    goToStep(3, '75%');
                } else if (step === 3) {
                    goToStep(4, '100%');
                } else if (step === 4) {
                    showQuizResult();
                }
            };

            function goToStep(nextStepNumber, percent) {
                document.querySelectorAll('.quiz-step').forEach(el => el.style.display = 'none');
                const target = document.getElementById('quizStep' + nextStepNumber);
                if (target) {
                    target.style.display = 'block';
                }
                const fill = document.getElementById('quizProgressFill');
                const label = document.getElementById('quizStepLabel');
                const percentEl = document.getElementById('quizStepPercent');

                if (fill) fill.style.width = percent;
                if (label) label.textContent = 'Krok ' + nextStepNumber + ' z 4';
                if (percentEl) percentEl.textContent = percent;

                // Smooth scroll to container for comfortable viewing
                const container = document.getElementById('quizContainer');
                if (container && window.scrollY > 150) {
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            function calculateWinningBreed() {
                const scores = {
                    bengal: 0,
                    british: 0,
                    siamese: 0
                };

                // STEP 1: Energy & Lifestyle
                if (quizState.step1 === 'calm_couch') {
                    scores.british += 4;
                    scores.siamese += 1;
                } else if (quizState.step1 === 'active_curious') {
                    scores.bengal += 4;
                    scores.siamese += 2;
                } else if (quizState.step1 === 'devoted_vocal') {
                    scores.siamese += 4;
                    scores.bengal += 1;
                    scores.british += 1;
                }

                // STEP 2: Time away & Household (Realistic Behavioral Adaptation)
                if (quizState.step2 === 'away_work') {
                    scores.british += 4; // British handles alone time well
                    scores.bengal += 1;
                    scores.siamese -= 3; // Siamese suffer from separation anxiety when left alone for long periods
                } else if (quizState.step2 === 'home_work') {
                    scores.siamese += 3;
                    scores.bengal += 2;
                    scores.british += 1;
                } else if (quizState.step2 === 'family_children') {
                    scores.british += 3; // Stoic, calm
                    scores.bengal += 3;  // Playful with children
                    scores.siamese += 1;
                } else if (quizState.step2 === 'other_pets') {
                    scores.bengal += 2;
                    scores.siamese += 2;
                    scores.british += 2;
                }

                // STEP 3: Coat & Interaction
                if (quizState.step3 === 'short_sleek') {
                    scores.bengal += 4;
                    scores.siamese += 2;
                } else if (quizState.step3 === 'dense_plush') {
                    scores.british += 4;
                } else if (quizState.step3 === 'interactive_shadow') {
                    scores.siamese += 4;
                    scores.bengal += 1;
                }

                // Determine winner
                let bestBreed = 'bengal';
                let highestScore = -999;
                for (const [breed, score] of Object.entries(scores)) {
                    if (score > highestScore) {
                        highestScore = score;
                        bestBreed = breed;
                    }
                }

                return bestBreed;
            }

            function getBehavioralNote(breed) {
                if (breed === 'british') {
                    if (quizState.step2 === 'away_work') {
                        return 'Z uwagi na to, że pracujesz poza domem, kot brytyjski to wybór doskonały. W przeciwieństwie do ras orientalnych znakomicie radzi sobie ze spokojnym oczekiwaniem na Twój powrót i nie cierpi na lęk separacyjny.';
                    }
                    if (quizState.step2 === 'family_children') {
                        return 'W domu z dziećmi kot brytyjski wyróżnia się niezwykłą cierpliwością, stabilnym układem nerwowym i zrównoważonym temperamentem.';
                    }
                    return 'Kot brytyjski idealnie komponuje się ze zrównoważonym rytmem domowym, oferując aksamitną miękkość i arystokratyczną dyskrecję.';
                } else if (breed === 'bengal') {
                    if (quizState.step1 === 'active_curious') {
                        return 'Twoja chęć aktywnej zabawy i nauki sztuczek idealnie odpowiada inteligencji kota bengalskiego. Bengale to żywiołowe, pełne energii koty, które uwielbiają interakcję z opiekunem.';
                    }
                    return 'Bengale łączą zjawiskowy rysunek rozet z niezwykłą domową czułością. Odpowiednia dawka zabawy zapewni mu szczęśliwe, spełnione życie w Twoim domu.';
                } else if (breed === 'siamese') {
                    if (quizState.step2 === 'home_work') {
                        return 'Ponieważ pracujesz zdalnie lub często przebywasz w domu, kot syjamski będzie zachwycony. Syjamy potrzebują bliskiej obecności człowieka, odwdzięczając się bezgranicznym przywiązaniem.';
                    }
                    return 'Syjam to kot o unikalnej osobowości – uwielbia wokalny dialog i tworzy z wybranym opiekunem więź głębszą niż większość domowych pupili.';
                }
                return '';
            }

            function showQuizResult() {
                const wrap = document.getElementById('quizProgressWrap');
                if (wrap) wrap.style.display = 'none';

                document.querySelectorAll('.quiz-step').forEach(el => el.style.display = 'none');
                const resultBox = document.getElementById('quizResult');
                if (resultBox) {
                    resultBox.style.display = 'block';
                }

                const matchedBreed = calculateWinningBreed();
                const info = breedInfo[matchedBreed] || breedInfo.bengal;
                window.__quizMatchedBreedName = info.name;
                window.__quizMatchedBreedKey = matchedBreed;

                const titleEl = document.getElementById('quizResultBreedTitle');
                const descEl = document.getElementById('quizResultBreedDesc');
                if (titleEl) titleEl.textContent = info.title;
                if (descEl) descEl.textContent = info.desc;

                // Personalized behavioral explanation
                const noteBox = document.getElementById('quizBehavioralNoteBox');
                const noteText = document.getElementById('quizBehavioralNoteText');
                const note = getBehavioralNote(matchedBreed);
                if (note && noteBox && noteText) {
                    noteText.textContent = note;
                    noteBox.style.display = 'block';
                } else if (noteBox) {
                    noteBox.style.display = 'none';
                }

                // 1. Show all kittens of the matched breed in the primary grid
                const items = document.querySelectorAll('#quizMatchingCatsGrid .quiz-cat-item');
                const timeline = quizState.step4; // 'ready_now', 'ready_future', 'ready_flexible'
                let matchedCount = 0;
                let hasReadyNowInMatched = false;

                items.forEach(item => {
                    const breed = item.dataset.breed;
                    const ready = item.dataset.ready; // 'now' or 'future'
                    const status = item.dataset.status || 'available';

                    // Strict protection: never show anything not available
                    if (status !== 'available') {
                        item.style.display = 'none';
                        return;
                    }

                    if (breed === matchedBreed) {
                        item.style.display = 'flex';
                        matchedCount++;
                        if (ready === 'now') {
                            hasReadyNowInMatched = true;
                        }
                    } else {
                        item.style.display = 'none';
                    }
                });

                window.__quizMatchedCount = matchedCount;

                // Update heading with matched breed name - hide completely if 0 cats
                const headingEl = document.getElementById('quizMatchedCatsHeading');
                if (headingEl) {
                    if (matchedCount > 0) {
                        headingEl.style.display = 'block';
                        headingEl.textContent = `Dostępne maluchy rasy ${info.name} w naszej hodowli:`;
                    } else {
                        headingEl.style.display = 'none';
                    }
                }

                // 2. If user chose 'ready_now', but matched breed kittens are in early reservation (e.g. British Ada, Alma, Atena)
                const futureNotice = document.getElementById('quizFutureNoticeForNow');
                if (futureNotice) {
                    if (timeline === 'ready_now' && matchedCount > 0 && !hasReadyNowInMatched) {
                        futureNotice.style.display = 'block';
                    } else {
                        futureNotice.style.display = 'none';
                    }
                }

                // 3. Alert when zero kittens of this breed exist in database or all booked
                const noCatsNotice = document.getElementById('quizNoMatchingCatsNotice');
                if (noCatsNotice) {
                    if (matchedCount === 0) {
                        noCatsNotice.style.display = 'block';
                        const noCatsTitle = document.getElementById('quizNoCatsNoticeTitle');
                        const noCatsDesc = document.getElementById('quizNoCatsNoticeText');
                        if (noCatsTitle) {
                            noCatsTitle.textContent = `Planujemy kolejny miot rasy ${info.name}`;
                        }
                        if (noCatsDesc) {
                            noCatsDesc.innerHTML = `Aktualnie wszystkie maluchy rasy <strong>${info.name}</strong> z obecnego miotu znalazły już swoje kochające domy lub są w trakcie procedury rezerwacji.<br>Dołącz do Listy Pierwszeństwa na WhatsApp – jako pierwszy dowiesz się o nowym miocie i potwierdzeniu ciąży!`;
                        }
                    } else {
                        noCatsNotice.style.display = 'none';
                    }
                }

                // 4. Show alternatives if user wanted ready_now and matched breed isn't ready today, OR if matchedCount === 0
                const altBox = document.getElementById('quizAlternativesBox');
                const altGrid = document.getElementById('quizAlternativeCatsGrid');
                const altTitle = document.getElementById('quizAltBoxTitle');
                const altDesc = document.getElementById('quizAltBoxDesc');

                if (altBox && altGrid) {
                    altGrid.innerHTML = '';
                    let altCount = 0;

                    items.forEach(item => {
                        const isAvailable = (item.dataset.status || 'available') === 'available';
                        if (!isAvailable) return;

                        if (item.dataset.breed !== matchedBreed) {
                            const isReady = item.dataset.ready === 'now';

                            // Scenario A: User wants ready_now, but matched breed is future -> show ONLY ready_now alternatives
                            // Scenario B: 0 kittens of matched breed -> show ready_now if user wanted ready_now, or any available kitten if flexible
                            const shouldInclude = (timeline === 'ready_now')
                                ? isReady
                                : (matchedCount === 0);

                            if (shouldInclude) {
                                const clone = item.cloneNode(true);
                                clone.style.display = 'flex';
                                const btn = clone.querySelector('.quiz-cat-item__btn');
                                const origBtn = item.querySelector('.quiz-cat-item__btn');
                                if (btn && origBtn && origBtn.getAttribute('onclick')) {
                                    btn.setAttribute('onclick', origBtn.getAttribute('onclick'));
                                }
                                altGrid.appendChild(clone);
                                altCount++;
                            }
                        }
                    });

                    if (altCount > 0 && ((timeline === 'ready_now' && !hasReadyNowInMatched) || matchedCount === 0)) {
                        if (altTitle && altDesc) {
                            if (timeline === 'ready_now') {
                                altTitle.innerHTML = '<span>⚡</span> Zależy Ci na odbiorze kociaka już w ten weekend?';
                                altDesc.textContent = matchedCount > 0
                                    ? `Maluchy rasy ${info.name} potrzebują jeszcze paru tygodni socjalizacji. Poznaj nasze w pełni odchowane maluchy innych ras, gotowe do odbioru od ręki:`
                                    : 'Poznaj nasze w pełni odchowane maluchy innych ras, które są po komplecie szczepień, zaczipowane i gotowe do natychmiastowego odbioru:';
                            } else {
                                altTitle.innerHTML = '<span>🐾</span> Poznaj dostępne maluchy innych naszych ras:';
                                altDesc.textContent = 'W naszej hodowli prowadzimy również starannie socjalizowane mioty innych ras. Być może któryś z poniższych maluchów skradnie Twoje serce:';
                            }
                        }
                        altBox.style.display = 'block';
                    } else {
                        altBox.style.display = 'none';
                    }
                }

                // Smooth scroll to result
                const container = document.getElementById('quizContainer');
                if (container) {
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                // Funnel analytics event
                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('quiz_completed', {
                        matched_breed: info.name,
                        lifestyle: quizState.step1 || '',
                        household: quizState.step2 || '',
                        coat: quizState.step3 || '',
                        timeline: quizState.step4 || '',
                        matched_count: matchedCount,
                    });
                }
            }

            window.contactBreederFromQuiz = function() {
                const breed = window.__quizMatchedBreedName || 'Kot Rasowy';
                const hasAvailable = (window.__quizMatchedCount || 0) > 0;
                let attributionCode = 'Quiz_Dopasowania';
                try {
                    if (typeof window.getTrafficSourceLabel === 'function') {
                        attributionCode = window.getTrafficSourceLabel();
                    }
                } catch(e) {}

                let msg = '';
                if (hasAvailable) {
                    msg = `Dzień dobry! Rozwiązałem/am quiz doboru rasy na kotyzmazowieckiejszwajcarii.pl. Mój wynik dopasowania to: ${breed}. Chciał(a)bym porozmawiać z hodowcą o dostępności i wideo-wizycie. [Kod: ${attributionCode}]`;
                } else {
                    msg = `Dzień dobry! Rozwiązałem/am quiz doboru rasy na kotyzmazowieckiejszwajcarii.pl. Mój wynik to: ${breed}. Wiem, że maluchy z obecnego miotu są już zarezerwowane – chciał(a)bym zapisać się na Listę Pierwszeństwa na kolejny planowany miot. [Kod: ${attributionCode}]`;
                }
                
                // Track contact click
                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('contact_cta_click', {
                        channel: 'whatsapp',
                        origin: 'quiz_result',
                        matched_breed: breed,
                        has_available: hasAvailable
                    });
                }

                window.open(`https://wa.me/48514153204?text=${encodeURIComponent(msg)}`, '_blank');
            };

            window.openCallbackFromQuiz = function() {
                const breed = window.__quizMatchedBreedName || 'Kot Rasowy';
                if (typeof window.openReservationModal === 'function') {
                    window.openReservationModal('Wynik Quizu: ' + breed, breed, 'Zgłoszenie z Quizu');
                }
            };

            window.restartQuiz = function() {
                quizState.step1 = null;
                quizState.step2 = null;
                quizState.step3 = null;
                quizState.step4 = null;

                const wrap = document.getElementById('quizProgressWrap');
                if (wrap) wrap.style.display = 'block';

                const resultBox = document.getElementById('quizResult');
                if (resultBox) resultBox.style.display = 'none';

                const futureNotice = document.getElementById('quizFutureNoticeForNow');
                if (futureNotice) futureNotice.style.display = 'none';

                const noCatsNotice = document.getElementById('quizNoMatchingCatsNotice');
                if (noCatsNotice) noCatsNotice.style.display = 'none';

                const altBox = document.getElementById('quizAlternativesBox');
                if (altBox) altBox.style.display = 'none';

                goToStep(1, '25%');
            };

            // Event delegation fallback
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('quizContainer');
                if (container) {
                    container.addEventListener('click', function(e) {
                        const opt = e.target.closest('.quiz-option');
                        if (opt && opt.dataset.step && opt.dataset.value) {
                            window.selectQuizOption(parseInt(opt.dataset.step, 10), opt.dataset.value);
                        }
                    });
                }
            });
        })();
    </script>
    </x-slot:scripts>
</x-frontend.shell>
