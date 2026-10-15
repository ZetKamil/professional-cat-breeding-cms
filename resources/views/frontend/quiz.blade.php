<x-frontend.shell
    title="Dobierz Idealną Rasę Kota w 45 Sekund — Bengalski, Brytyjski czy Syjamski? | Mazowiecka Szwajcaria"
    meta-description="Nie wiesz, jaki kot pasuje do Twojego stylu życia? Wypełnij nasz 45-sekundowy quiz i poznaj rasę idealnie dopasowaną do Ciebie oraz zobacz dostępne maluchy na żywo."
    nav-theme="dark"
>
    <section class="quiz-section hero--dark" data-nav-theme="dark" aria-label="Interaktywny quiz doboru rasy kota">
        <div class="section-inner">
            
            {{-- Quiz Hero Header --}}
            <div class="quiz-hero">
                <span class="quiz-hero__eyebrow">Dopasowanie Behawioralne & Styl Życia</span>
                <h1 class="quiz-hero__title">Dobierz Idealną Rasę w 45 Sekund</h1>
                <p class="quiz-hero__lead">
                    Odpowiedz na 3 szybkie pytania o Twoje oczekiwania, dom i codzienny rytm dnia. 
                    Pokażemy Ci rasę dopasowaną do Twojego serca oraz dostępne kocięta gotowe do wideo-wizyty na żywo.
                </p>
            </div>

            {{-- Main Quiz Container --}}
            <div class="quiz-card" id="quizContainer">
                
                {{-- Progress Bar --}}
                <div class="quiz-progress-wrap" id="quizProgressWrap">
                    <div class="quiz-progress-info">
                        <span id="quizStepLabel">Krok 1 z 3</span>
                        <span id="quizStepPercent">33%</span>
                    </div>
                    <div class="quiz-progress-bar">
                        <div class="quiz-progress-fill" id="quizProgressFill" style="width: 33%;"></div>
                    </div>
                </div>

                {{-- STEP 1: Lifestyle and Energy Level --}}
                <div class="quiz-step is-active" id="quizStep1" data-step="1">
                    <h2 class="quiz-step__question">Jak wygląda Twój wymarzony dzień z kotem?</h2>
                    <p class="quiz-step__hint">Wybierz atmosferę, która najlepiej pasuje do Ciebie:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(1, 'british')">
                            <span class="quiz-option__icon">🛋️</span>
                            <div class="quiz-option__content">
                                <strong>Spokojny relaks, cisza i miękki plusz</strong>
                                <small>Kot, który ceni zrównoważony rytm, odpoczywa obok na kanapie i nie domaga się nieustannej uwagi.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(1, 'bengal')">
                            <span class="quiz-option__icon">🐆</span>
                            <div class="quiz-option__content">
                                <strong>Dużo zabawy, inteligencja i energia lamparta</strong>
                                <small>Niezwykle bystry towarzysz, który aportuje zabawki, uczy się sztuczek i zachwyca dzikim wyglądem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(1, 'siamese')">
                            <span class="quiz-option__icon">💎</span>
                            <div class="quiz-option__content">
                                <strong>Niezwykle głęboka więź i kocia rozmowa</strong>
                                <small>Kot, który chodzi za Tobą krok w krok, uwielbia spać pod kołdrą i odpowiada swoim melodyjnym głosem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Household members --}}
                <div class="quiz-step" id="quizStep2" data-step="2" style="display:none;">
                    <h2 class="quiz-step__question">Kto tworzy Twoje domowe ognisko?</h2>
                    <p class="quiz-step__hint">Wszystkie nasze koty są socjalizowane w domu rodzinnym:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(2, 'single_couple')">
                            <span class="quiz-option__icon">☕</span>
                            <div class="quiz-option__content">
                                <strong>Singiel lub para</strong>
                                <small>Szukam wiernego przyjaciela do wspólnego, spokojnego mieszkania i wieczorów po pracy.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(2, 'family_kids')">
                            <span class="quiz-option__icon">🏡</span>
                            <div class="quiz-option__content">
                                <strong>Rodzina z dziećmi</strong>
                                <small>Ważny jest stabilny, zrównoważony charakter, cierpliwość i przyjazne nastawienie do maluchów.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(2, 'with_pets')">
                            <span class="quiz-option__icon">🐾</span>
                            <div class="quiz-option__content">
                                <strong>Dom z innymi zwierzętami (pies/kot)</strong>
                                <small>Zależy mi na otwartym kocie, który łatwo odnajdzie się w stadzie po prawidłowej socjalizacji z zapachem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: Timeline & Arrival --}}
                <div class="quiz-step" id="quizStep3" data-step="3" style="display:none;">
                    <h2 class="quiz-step__question">Kiedy chciałbyś powitać kociaka w swoim domu?</h2>
                    <p class="quiz-step__hint">Dopasujemy malucha do Twojego terminu gotowości:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(3, 'now')">
                            <span class="quiz-option__icon">🟢</span>
                            <div class="quiz-option__content">
                                <strong>Jak najszybciej (maluch gotowy do odbioru)</strong>
                                <small>Szukam w pełni odchowanego kociaka (szczepienia, odrobaczenia, chip, 100% kuweta).</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" onclick="window.selectQuizOption(3, 'future')">
                            <span class="quiz-option__icon">⏳</span>
                            <div class="quiz-option__content">
                                <strong>Za 3–6 tygodni (wczesna rezerwacja z nowego miotu)</strong>
                                <small>Chcę spokojnie przygotować wyprawkę i z radością obserwować jak rośnie mój maluszek.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- RESULT SCREEN --}}
                <div class="quiz-result" id="quizResult" style="display:none;">
                    <div class="quiz-result__badge">
                        <span>✨ Twoje Idealne Dopasowanie:</span>
                    </div>

                    <h2 class="quiz-result__title" id="quizResultBreedTitle">Kot Bengalski</h2>
                    <p class="quiz-result__desc" id="quizResultBreedDesc"></p>

                    {{-- Matched kittens from database --}}
                    <div class="quiz-result__cats-box">
                        <h3 class="quiz-result__cats-heading">Dostępne maluchy tej rasy w naszej hodowli:</h3>
                        
                        <div class="quiz-cats-grid" id="quizMatchingCatsGrid">
                            @foreach($animals as $animal)
                                @php
                                    $bLower = mb_strtolower($animal->breed ?? '');
                                    $breedCategory = 'other';
                                    if (str_contains($bLower, 'bengal')) $breedCategory = 'bengal';
                                    elseif (str_contains($bLower, 'brytyj') || str_contains($bLower, 'british')) $breedCategory = 'british';
                                    elseif (str_contains($bLower, 'syjam') || str_contains($bLower, 'siam')) $breedCategory = 'siamese';

                                    $weeksOld = $animal->date_of_birth ? (int)$animal->date_of_birth->diffInWeeks(now()) : 99;
                                    $isReadyNow = $weeksOld >= 12;
                                @endphp
                                <div class="quiz-cat-item" 
                                     data-breed="{{ $breedCategory }}" 
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
                                            {{ $isReadyNow ? '🟢 Gotowy do odbioru' : '⏳ Wczesna rezerwacja' }}
                                        </span>
                                    </div>
                                    <button type="button" 
                                            class="quiz-cat-item__btn" 
                                            onclick="window.openReservationModal('{{ addslashes($animal->name) }}', '{{ addslashes($animal->breed) }}', '{{ addslashes($animal->statusLabel()) }}')">
                                        📹 Zobacz na żywo
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Primary actions after quiz completion --}}
                    <div class="quiz-result__actions">
                        <button type="button" id="quizPrimaryCta" class="quiz-cta-btn quiz-cta-btn--primary" onclick="window.openReservationModal(window.__quizMatchedBreedName, '', '')">
                            📹 Umów wideo-wizytę na żywo (WhatsApp)
                        </button>
                        <a href="tel:+48514153204" class="quiz-cta-btn quiz-cta-btn--secondary">
                            📞 Zadzwoń do hodowcy: +48 514 153 204
                        </a>
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

    {{-- Lightweight Vanilla JS Quiz State Machine --}}
    @push('scripts')
    <script>
        (function() {
            const quizState = {
                step1: null,
                step2: null,
                step3: null,
            };

            const breedInfo = {
                bengal: {
                    title: 'Kot Bengalski (Przygoda, Elegancja i Dzikie Piękno)',
                    desc: 'Twoje odpowiedzi wskazują, że pokochasz kota bengalskiego! To pełen pasji, inteligentny i aktywny przyjaciel, który wniesie do Twojego domu urok dzikiego kota w połączeniu z przyjaznym, pieszczotliwym charakterem domowego pupila. Nasze bengale pochodzą ze sprawdzonych linii, z ujemnymi badaniami genetycznymi PRA-b i PK-Def.',
                    name: 'Kot Bengalski'
                },
                british: {
                    title: 'Kot Brytyjski Krótkowłosy (Arystokratyczny Spokój i Plusz)',
                    desc: 'Dla Ciebie stworzony jest kot brytyjski! To uosobienie domowego ciepła, łagodności i pluszowej elegancji. Doskonale radzi sobie zarówno podczas chwil samotności, jak i leniwych popołudni na kanapie. Nasze maluchy są po przebadanych kardiologicznie i genetycznie (PKD n/n) rodzicach.',
                    name: 'Kot Brytyjski'
                },
                siamese: {
                    title: 'Kot Syjamski (Niezrównana Więź i Głębokie Porozumienie)',
                    desc: 'Twoim wymarzonym kompanem jest kot syjamski! Jeśli pragniesz kota, który naprawdę uczestniczy w Twoim życiu, chodzi krok w krok, rozmawia z Tobą i obdarza Cię bezgraniczną miłością — ta rasa skradnie Twoje serce bez reszty.',
                    name: 'Kot Syjamski'
                }
            };

            window.selectQuizOption = function(step, value) {
                quizState['step' + step] = value;

                if (step === 1) {
                    goToStep(2, '66%');
                } else if (step === 2) {
                    goToStep(3, '100%');
                } else if (step === 3) {
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
                if (label) label.textContent = 'Krok ' + nextStepNumber + ' z 3';
                if (percentEl) percentEl.textContent = percent;
            }

            function showQuizResult() {
                const wrap = document.getElementById('quizProgressWrap');
                if (wrap) wrap.style.display = 'none';

                document.querySelectorAll('.quiz-step').forEach(el => el.style.display = 'none');
                const resultBox = document.getElementById('quizResult');
                if (resultBox) resultBox.style.display = 'block';

                const matchedBreed = quizState.step1 || 'bengal';
                const info = breedInfo[matchedBreed] || breedInfo.bengal;
                window.__quizMatchedBreedName = info.name;

                document.getElementById('quizResultBreedTitle').textContent = info.title;
                document.getElementById('quizResultBreedDesc').textContent = info.desc;

                // Filter matching kittens in DOM
                const items = document.querySelectorAll('.quiz-cat-item');
                let matchCount = 0;
                items.forEach(item => {
                    const breed = item.dataset.breed;
                    if (breed === matchedBreed) {
                        item.style.display = 'flex';
                        matchCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Fallback: if no cats of this breed currently available, show all
                if (matchCount === 0) {
                    items.forEach(item => item.style.display = 'flex');
                }

                // Funnel analytics event
                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('quiz_completed', {
                        matched_breed: info.name,
                        timeline: quizState.step3 || '',
                        household: quizState.step2 || '',
                    });
                }
            }

            window.restartQuiz = function() {
                quizState.step1 = null;
                quizState.step2 = null;
                quizState.step3 = null;

                const wrap = document.getElementById('quizProgressWrap');
                if (wrap) wrap.style.display = 'block';

                const resultBox = document.getElementById('quizResult');
                if (resultBox) resultBox.style.display = 'none';

                goToStep(1, '33%');
            };
        })();
    </script>
    @endpush
</x-frontend.shell>
