<x-frontend.shell
    title="Dobierz Idealną Rasę Kota w 45 Sekund — Bengalski, Brytyjski czy Syjamski? | Mazowiecka Szwajcaria"
    meta-description="Nie wiesz, jaki kot pasuje do Twojego stylu życia? Wypełnij nasz 4-etapowy quiz behawioralny i poznaj rasę dopasowaną do Ciebie oraz zobacz dostępne maluchy na żywo."
    og-title="🐾 Test: Kot Bengalski, Brytyjski czy Syjamski? Sprawdź w 45 sekund!"
    og-description="Rozwiąż krótki test i sprawdź, która rasa z naszej domowej hodowli SHiOZ ZOOLANDIA pasuje do Twojego domu."
    og-image="{{ asset('og-quiz.jpg') }}"
    og-image-width="1200"
    og-image-height="630"
    og-image-type="image/jpeg"
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
                    Nasz model dopasowania wskaże rasę odpowiadającą Twojemu stylowi życia oraz sprawdzi aktualną dostępność kociąt i planowane mioty w hodowli.
                </p>
                <div class="quiz-hero__badges">
                    <span class="quiz-hero__badge">⚡ 4 szybkie pytania (45 sekund)</span>
                    <span class="quiz-hero__badge">🔒 Wynik od razu — bez wymogu podawania e-maila</span>
                    <span class="quiz-hero__badge">📹 Dostępność kociąt i miotów na żywo</span>
                </div>
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
                    <p class="quiz-step__hint">Wybierz atmosferę, która najbardziej odpowiada Twojemu stylowi życia:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="1" data-value="calm_couch" onclick="window.selectQuizOption(1, 'calm_couch')">
                            <span class="quiz-option__icon">🛋️</span>
                            <div class="quiz-option__content">
                                <strong>Spokojny relaks, cisza i miękki plusz</strong>
                                <small>Kot, który z reguły ceni zrównoważony rytm, odpoczywa obok na kanapie i nie wymaga nieustannej intensywnej uwagi.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="1" data-value="active_curious" onclick="window.selectQuizOption(1, 'active_curious')">
                            <span class="quiz-option__icon">🐆</span>
                            <div class="quiz-option__content">
                                <strong>Dużo zabawy, inteligencja i żywiołowa energia</strong>
                                <small>Bystry, ciekawski towarzysz, który chętnie uczy się sztuczek, aportuje zabawki i zachwyca dzikim rysunkiem rozet.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="1" data-value="devoted_vocal" onclick="window.selectQuizOption(1, 'devoted_vocal')">
                            <span class="quiz-option__icon">💎</span>
                            <div class="quiz-option__content">
                                <strong>Głęboka więź, bliskość i kocia rozmowa</strong>
                                <small>Towarzysz, który uwielbia uczestniczyć w życiu domu, reaguje na Twój głos i tworzy niezwykle bliską relację z człowiekiem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 2: Time away & Household Dynamics --}}
                <div class="quiz-step" id="quizStep2" data-step="2" style="display:none;">
                    <div class="quiz-step__nav">
                        <button type="button" class="quiz-nav-back" onclick="window.prevQuizStep(2)" aria-label="Wróć do pytania 1">
                            ← Wróć do pytania 1
                        </button>
                    </div>

                    <h2 class="quiz-step__question">2. Twój dom i czas spędzany poza nim</h2>
                    <p class="quiz-step__hint">Wskaż sytuację, która najlepiej opisuje codzienny rytm Twojego domu:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="2" data-value="away_work" onclick="window.selectQuizOption(2, 'away_work')">
                            <span class="quiz-option__icon">🏢</span>
                            <div class="quiz-option__content">
                                <strong>Praca poza domem (kilka godzin dziennie)</strong>
                                <small>Zależy mi na kocie o zrównoważonym temperamencie, który ze spokojem odpoczywa pod nieobecność domowników.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="home_work" onclick="window.selectQuizOption(2, 'home_work')">
                            <span class="quiz-option__icon">💻</span>
                            <div class="quiz-option__content">
                                <strong>Praca zdalna / często ktoś jest w domu</strong>
                                <small>Szukam kota, który będzie moim kompanem w ciągu dnia i ceni regularny kontakt z człowiekiem.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="family_children" onclick="window.selectQuizOption(2, 'family_children')">
                            <span class="quiz-option__icon">🏡</span>
                            <div class="quiz-option__content">
                                <strong>Dom tętniący życiem / rodzina z dziećmi</strong>
                                <small>Kluczowy jest zrównoważony układ nerwowy, cierpliwość oraz dobra domowa socjalizacja malucha.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="2" data-value="other_pets" onclick="window.selectQuizOption(2, 'other_pets')">
                            <span class="quiz-option__icon">🐾</span>
                            <div class="quiz-option__content">
                                <strong>Dom z innymi pupilami (pies lub inny kot)</strong>
                                <small>Zależy mi na otwartym kocie, który po prawidłowej adaptacji odnajdzie się w towarzystwie innych zwierząt.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: Coat, Grooming & Interaction Preferences --}}
                <div class="quiz-step" id="quizStep3" data-step="3" style="display:none;">
                    <div class="quiz-step__nav">
                        <button type="button" class="quiz-nav-back" onclick="window.prevQuizStep(3)" aria-label="Wróć do pytania 2">
                            ← Wróć do pytania 2
                        </button>
                    </div>

                    <h2 class="quiz-step__question">3. Jakie cechy wyglądu i pielęgnacji preferujesz?</h2>
                    <p class="quiz-step__hint">Wybierz to, co sprawi Ci najwięcej satysfakcji w codziennej opiece:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="3" data-value="short_sleek" onclick="window.selectQuizOption(3, 'short_sleek')">
                            <span class="quiz-option__icon">✨</span>
                            <div class="quiz-option__content">
                                <strong>Krótka, gładka sierść (minimalna pielęgnacja)</strong>
                                <small>Jedwabista w dotyku, lśniąca okrywa z kontrastowym rysunkiem rozet, łatwa w codziennej pielęgnacji.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="3" data-value="dense_plush" onclick="window.selectQuizOption(3, 'dense_plush')">
                            <span class="quiz-option__icon">🧸</span>
                            <div class="quiz-option__content">
                                <strong>Gęsty, aksamitny plusz jak u misia</strong>
                                <small>Okrągłe policzki, miękka podwójna okrywa włosowa; chętnie poświęcę czas na regularne szczotkowanie.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="3" data-value="interactive_shadow" onclick="window.selectQuizOption(3, 'interactive_shadow')">
                            <span class="quiz-option__icon">💙</span>
                            <div class="quiz-option__content">
                                <strong>Smukła, orientalna sylwetka i wyraziste spojrzenie</strong>
                                <small>Błękitne migdałowe oczy, elegancka linia ciała i kot aktywnie włączający się w domowe sprawy.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>
                    </div>
                </div>

                {{-- STEP 4: Timeline & Readiness --}}
                <div class="quiz-step" id="quizStep4" data-step="4" style="display:none;">
                    <div class="quiz-step__nav">
                        <button type="button" class="quiz-nav-back" onclick="window.prevQuizStep(4)" aria-label="Wróć do pytania 3">
                            ← Wróć do pytania 3
                        </button>
                    </div>

                    <h2 class="quiz-step__question">4. Kiedy chciałbyś powitać kociaka w swoim domu?</h2>
                    <p class="quiz-step__hint">Dopasujemy dostępne maluchy i planowane mioty do Twojego harmonogramu:</p>

                    <div class="quiz-options">
                        <button type="button" class="quiz-option" data-step="4" data-value="ready_now" onclick="window.selectQuizOption(4, 'ready_now')">
                            <span class="quiz-option__icon">🟢</span>
                            <div class="quiz-option__content">
                                <strong>Jak najszybciej (kociak w wieku min. 12–14 tygodni)</strong>
                                <small>Szukam w pełni odchowanego malucha (komplet szczepień, mikrochip, 100% kuweta), gotowego do bezpiecznego odbioru.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="4" data-value="ready_future" onclick="window.selectQuizOption(4, 'ready_future')">
                            <span class="quiz-option__icon">⏳</span>
                            <div class="quiz-option__content">
                                <strong>Za 3–6 tygodni (wczesna rezerwacja z nowego miotu)</strong>
                                <small>Chcę spokojnie przygotować dom na powitanie kociaka i obserwować dorastanie maluszka w kontakcie z hodowcą.</small>
                            </div>
                            <span class="quiz-option__arrow">→</span>
                        </button>

                        <button type="button" class="quiz-option" data-step="4" data-value="ready_flexible" onclick="window.selectQuizOption(4, 'ready_flexible')">
                            <span class="quiz-option__icon">🌿</span>
                            <div class="quiz-option__content">
                                <strong>Elastycznie – liczy się dopasowanie charakteru</strong>
                                <small>Mogę poczekać na malucha lub kolejny miot, który idealnie zgra się z naszą rodziną.</small>
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

                    {{-- Short punchy 2-3 sentence description --}}
                    <p class="quiz-result__desc" id="quizResultBreedDesc"></p>

                    {{-- Matched kittens from database --}}
                    <div class="quiz-result__cats-box">
                        <h3 class="quiz-result__cats-heading" id="quizMatchedCatsHeading">Dostępne kocięta tej rasy w naszej hodowli:</h3>

                        {{-- Notice if user chose 'ready_now' but matched breed is in early reservation (e.g. British kittens < 12 weeks) --}}
                        <div id="quizFutureNoticeForNow" style="display:none; background: rgba(197, 160, 89, 0.08); border: 1px solid rgba(197, 160, 89, 0.28); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; text-align: left; font-size: 0.92rem; line-height: 1.55; color: #FAF8F5;">
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <span style="font-size: 1.6rem; line-height: 1;">⏳</span>
                                <div>
                                    <strong style="color: var(--color-gold, #c89d5c); display: block; font-size: 0.98rem; margin-bottom: 4px;">Szukasz kociaka od zaraz? Kocięta mogą wyjeżdżać do nowych domów w wieku 12–14 tygodni, po potwierdzeniu gotowości konkretnego kociaka.</strong>
                                    Maluszki z tego miotu są teraz pod opieką mamy i przechodzą program domowej socjalizacji. Już teraz możesz napisać na Messengerze, zobaczyć je podczas zabawy i dokonać wczesnej rezerwacji swojego ulubieńca.
                                </div>
                            </div>
                        </div>

                        {{-- Matched Kittens: Clean Luxury Mini-Cards --}}
                        <div class="quiz-cats-grid" id="quizMatchingCatsGrid">
                            @foreach($animals as $animal)
                                @if($animal->status === \App\Enums\AnimalStatus::Available)
                                    @php
                                        $bLower = mb_strtolower($animal->breed ?? '');
                                        $breedCategory = 'other';
                                        if (str_contains($bLower, 'bengal')) $breedCategory = 'bengal';
                                        elseif (str_contains($bLower, 'brytyj') || str_contains($bLower, 'british')) $breedCategory = 'british';
                                        elseif (str_contains($bLower, 'syjam') || str_contains($bLower, 'siam')) $breedCategory = 'siamese';

                                        $hasDob = $animal->date_of_birth !== null;
                                        $ageInWeeks = $animal->ageInWeeks();
                                        $isStrictlyReadyToLeave = $hasDob && $ageInWeeks >= 12;
                                        $badgeLabel = $animal->pickupBadgeLabel();
                                        $timelineLabel = $animal->pickupCardTimelineLabel();
                                    @endphp
                                    <div class="quiz-cat-item"
                                         data-breed="{{ $breedCategory }}"
                                         data-status="available"
                                         data-ready="{{ $isStrictlyReadyToLeave ? 'now' : 'future' }}"
                                         data-cat-name="{{ addslashes($animal->name) }}"
                                         data-cat-breed="{{ addslashes($animal->breed) }}">
                                        <div class="quiz-cat-item__img">
                                            @if($animal->media)
                                                <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" loading="lazy">
                                            @else
                                                <img src="{{ asset('image/hero/bars_studio.jpg') }}" alt="{{ $animal->name }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="quiz-cat-item__info">
                                            <div class="quiz-cat-item__name-row">
                                                <h4>{{ $animal->name }}</h4>
                                                @if($animal->shouldShowPrice())
                                                    <span class="quiz-cat-item__price">{{ $animal->formattedPrice() }}</span>
                                                @endif
                                            </div>
                                            <p>{{ $animal->breed }} • {{ $animal->color }}</p>
                                            <span class="quiz-cat-item__status">
                                                <span class="quiz-cat-item__status-dot {{ $isStrictlyReadyToLeave ? '' : 'quiz-cat-item__status-dot--future' }}"></span>
                                                <span class="quiz-cat-item__status-text">{{ $isStrictlyReadyToLeave ? 'Gotowy do odbioru' : $badgeLabel }}</span>
                                                @if($timelineLabel)
                                                    <span class="quiz-cat-item__timeline-sep">•</span>
                                                    <span class="quiz-cat-item__timeline">{{ $timelineLabel }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="quiz-cat-item__actions">
                                            <a href="{{ route('frontend.animals.show', $animal) }}"
                                               class="quiz-cat-item__btn-profile">
                                                Zobacz profil →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Alert when 0 kittens of this breed exist in database or all are booked --}}
                        <div id="quizNoMatchingCatsNotice" style="display:none; background: rgba(197, 160, 89, 0.08); border: 1px solid rgba(197, 160, 89, 0.28); border-radius: 12px; padding: 22px; text-align: center; margin-top: 16px; color: #FAF8F5;">
                            <span style="font-size: 2rem; display: block; margin-bottom: 8px;">🗓️</span>
                            <h4 id="quizNoCatsNoticeTitle" style="font-size: 1.05rem; font-weight: 700; color: var(--color-gold, #c89d5c); margin-bottom: 6px;">Planujemy kolejny miot tej rasy</h4>
                            <p id="quizNoCatsNoticeText" style="font-size: 0.92rem; color: #e8e3d8; line-height: 1.6; margin-bottom: 16px;">
                                Aktualnie wszystkie maluchy tej rasy z obecnych miotów znalazły już swoje domy lub są w trakcie rezerwacji. Skontaktuj się z nami, aby porozmawiać o planowanym miocie i zapisać się na Listę Pierwszeństwa!
                            </p>
                            <a id="quizNoCatsCtaMessenger"
                               href="https://m.me/61580668026948"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="quiz-cta-btn--gold"
                               style="display:inline-flex; max-width: 360px;"
                               onclick="window.fireQuizContactEvent('messenger', 'no_cats_notice')">
                                💬 Zapytaj na Messengerze o planowany miot
                            </a>
                        </div>

                        {{-- Alternative recommendations if user wanted ready_now and matched breed not ready, or 0 matched --}}
                        <div id="quizAlternativesBox" style="display:none; margin-top: 28px; padding-top: 22px; border-top: 1px solid rgba(255, 255, 255, 0.12);">
                            <div style="background: rgba(197, 160, 89, 0.08); border: 1px solid rgba(197, 160, 89, 0.28); border-radius: 12px; padding: 14px 18px; margin-bottom: 16px; text-align: left;">
                                <strong id="quizAltBoxTitle" style="color: #C5A059; font-size: 0.96rem; display: flex; align-items: center; gap: 8px;">
                                    <span>⚡</span> Zależy Ci na odbiorze kociaka już w ten weekend?
                                </strong>
                                <p id="quizAltBoxDesc" style="margin: 4px 0 0; font-size: 0.88rem; color: #FAF8F5; line-height: 1.5;">
                                    Poznaj nasze w pełni odchowane maluchy innych ras, które są po komplecie szczepień, zaczipowane i gotowe do odbioru od ręki:
                                </p>
                            </div>
                            <div class="quiz-cats-grid" id="quizAlternativeCatsGrid"></div>
                        </div>

                        {{-- Fallback when catalogue has 0 available cats at all --}}
                        <div id="quizEmptyCatalogueNotice" style="display:none; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px; padding: 20px; text-align: center; margin-top: 16px; color: #f5f0e8;">
                            <p style="margin: 0 0 14px; font-size: 0.93rem; color: #ded9d0; line-height: 1.6;">
                                Aktualnie przygotowujemy nowe mioty do publikacji w katalogu online.<br>
                                Skontaktuj się bezpośrednio z hodowlą – z przyjemnością poinformujemy Cię o aktualnym stanie miotów i planowanych narodzinach.
                            </p>
                            <a href="https://m.me/61580668026948"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="quiz-cta-btn--gold"
                               style="display:inline-flex; max-width: 320px;"
                               onclick="window.fireQuizContactEvent('messenger', 'empty_catalogue')">
                                💬 Napisz do hodowcy (Messenger)
                            </a>
                        </div>
                    </div>

                    {{-- Compact Golden Behavioral Note --}}
                    <div id="quizBehavioralNoteBox" style="background: rgba(200, 157, 92, 0.14); border: 1px solid rgba(200, 157, 92, 0.35); border-left: 4px solid var(--color-gold, #c89d5c); padding: 16px 20px; border-radius: 10px; margin: 0 0 22px; text-align: left; font-size: 0.92rem; line-height: 1.6;">
                        <strong id="quizBehavioralNoteTitle" style="color: var(--color-gold, #c89d5c); display: block; font-size: 0.95rem; margin-bottom: 5px;">💡 Dlaczego ten wybór pasuje do Twojego rytmu dnia:</strong>
                        <p id="quizBehavioralNoteText" style="margin: 0; color: #f5f0e8; font-weight: 400;"></p>
                    </div>

                    {{-- Single Primary Contact Block (Messenger First, WhatsApp Secondary, Quiet Luxury) --}}
                    <div class="quiz-result__actions">
                        <div class="quiz-cta-group">
                            {{-- Primary Button: Solid Luxury Gold #C5A059 --}}
                            <a id="quizCtaMessengerBottom"
                               href="https://m.me/61580668026948"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="quiz-cta-btn--gold"
                               onclick="window.fireQuizContactEvent('messenger', 'bottom_cta')">
                                <svg class="quiz-cta-btn__icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2C6.48 2 2 6.03 2 11c0 2.87 1.5 5.43 3.84 7.07L5 22l4.13-1.81C10.05 20.35 11 20.48 12 20.48c5.52 0 10-4.03 10-9s-4.48-9.48-10-9.48z"/>
                                    <path d="M7.5 13.5l3-3.2 2 2 3-3.2-3 3.2-2-2-3 3.2z" fill="rgba(18,18,20,0.4)"/>
                                </svg>
                                💬 Napisz na Messengerze (Zapytaj o kociaki lub wideo-wizytę)
                            </a>

                            {{-- Secondary Button: Dark Subtle Outline with WhatsApp icon --}}
                            <a id="quizCtaWhatsappBottom"
                               href="https://wa.me/48514153204"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="quiz-cta-btn--outline"
                               onclick="window.fireQuizContactEvent('whatsapp', 'bottom_cta')">
                                <svg class="quiz-cta-btn__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                </svg>
                                Napisz na WhatsApp (+48 514 153 204)
                            </a>

                            {{-- Tertiary Callback Button --}}
                            <button type="button"
                                    class="quiz-cta-btn--callback"
                                    onclick="window.openCallbackFromQuiz()">
                                📞 Poproś o szybkie oddzwonienie
                            </button>
                        </div>

                        {{-- Reassurance Micro-copy --}}
                        <p class="quiz-cta-reassurance">⚡ Odpowiadamy w kilka minut • Wideo-wizytę z kociakami możemy połączyć również bezpośrednio na Messengerze.</p>

                        {{-- Refined Facebook Follow Box (Luxury Dark Gold Border) --}}
                        <div class="quiz-follower-card">
                            <span class="quiz-follower-card__icon">📸</span>
                            <h3 class="quiz-follower-card__heading">Nie planujesz zakupu od razu? Zobacz codzienne filmiki z kociakami!</h3>
                            <p class="quiz-follower-card__sub">Wrzucamy codzienne relacje, wideo z maluchami i zapowiedzi nowych miotów. Zaobserwuj naszą hodowlę na Facebooku:</p>
                            <a href="https://www.facebook.com/profile.php?id=61580668026948"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="quiz-follower-card__btn"
                               onclick="window.fireQuizFollowEvent()">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                👍 Zaobserwuj naszą Hodowlę na Facebooku
                            </a>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 20px; display: flex; justify-content: center; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <button type="button" class="quiz-modify-btn" onclick="window.prevQuizStep(4)">
                            ✏️ Zmień odpowiedzi (krok 4)
                        </button>
                        <button type="button" class="quiz-restart-btn" onclick="window.restartQuiz()">
                            🔄 Wypełnij quiz od nowa
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ============================================================
    2. EDUCATIONAL SEO GUIDE — BREED COMPARISON & FAQ
    ============================================================ --}}
    <x-frontend.section tile="parchment" id="przewodnik-po-rasach" class="reveal-up">
        <x-frontend.section-header
            eyebrow="Eksperckie Porównanie Ras"
            headline="Kot Bengalski, Brytyjski czy Syjamski? Jak wybrać idealnego towarzysza?"
            description="Poznaj kluczowe różnice w temperamencie, potrzebach ruchowych i pielęgnacji kotów z naszej domowej hodowli."
        />

        <div class="benefits-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--sp-xl); margin-bottom: var(--sp-3xl);" role="list" aria-label="Porównanie ras kotów">
            {{-- Bengalski --}}
            <div class="benefit-item" style="background: var(--color-canvas, #fff); border-radius: var(--r-xl); padding: var(--sp-xl); border: 1px solid var(--color-hairline); box-shadow: 0 4px 20px rgba(0,0,0,0.03);" role="listitem">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 2rem;">🐆</span>
                    <div>
                        <h3 class="text-body-strong" style="font-size: 1.15rem; margin: 0; color: var(--color-ink);">Kot Bengalski</h3>
                        <span style="font-size: 0.8rem; color: var(--color-gold, #c89d5c); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Dzikie Piękno & Inteligencja</span>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 16px; font-size: 0.92rem; line-height: 1.6; color: var(--color-ink-muted-80);">
                    <li style="margin-bottom: 8px;"><strong>Temperament:</strong> Niezwykle bystry, żywiołowy, uwielbia aportować i uczyć się sztuczek.</li>
                    <li style="margin-bottom: 8px;"><strong>Szata:</strong> Krótka, jedwabista z brokatowym połyskiem (glitter), niemal nie gubi sierści.</li>
                    <li style="margin-bottom: 8px;"><strong>Potrzeby:</strong> Wymaga przestrzeni do wspinaczki (wysoki drapak) i codziennej stymulacji umysłowej.</li>
                    <li><strong>Dla kogo:</strong> Dla osób aktywnych, rodzin ze starszymi dziećmi lub osób pracujących z domu.</li>
                </ul>
            </div>

            {{-- Brytyjski --}}
            <div class="benefit-item" style="background: var(--color-canvas, #fff); border-radius: var(--r-xl); padding: var(--sp-xl); border: 1px solid var(--color-hairline); box-shadow: 0 4px 20px rgba(0,0,0,0.03);" role="listitem">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 2rem;">🛋️</span>
                    <div>
                        <h3 class="text-body-strong" style="font-size: 1.15rem; margin: 0; color: var(--color-ink);">Kot Brytyjski (BSH / BLH)</h3>
                        <span style="font-size: 0.8rem; color: var(--color-gold, #c89d5c); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Arystokratyczny Spokój & Plusz</span>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 16px; font-size: 0.92rem; line-height: 1.6; color: var(--color-ink-muted-80);">
                    <li style="margin-bottom: 8px;"><strong>Temperament:</strong> Spokojny, zrównoważony, łagodny, nienarzucający się kompan kanapowy.</li>
                    <li style="margin-bottom: 8px;"><strong>Szata:</strong> Krótkowłosa pluszowa (BSH, jak kotka Nel) lub okazała półdługa (BLH, jak reproduktor Staś colourpoint).</li>
                    <li style="margin-bottom: 8px;"><strong>Potrzeby:</strong> Rutyna, ciche otoczenie, bez problemu znosi chwile samotności podczas pracy opiekuna.</li>
                    <li><strong>Dla kogo:</strong> Dla osób ceniących ciszę, rodzin z dziećmi, osób pracujących poza domem.</li>
                </ul>
            </div>

            {{-- Syjamski --}}
            <div class="benefit-item" style="background: var(--color-canvas, #fff); border-radius: var(--r-xl); padding: var(--sp-xl); border: 1px solid var(--color-hairline); box-shadow: 0 4px 20px rgba(0,0,0,0.03);" role="listitem">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <span style="font-size: 2rem;">💎</span>
                    <div>
                        <h3 class="text-body-strong" style="font-size: 1.15rem; margin: 0; color: var(--color-ink);">Kot Syjamski / Tajski</h3>
                        <span style="font-size: 0.8rem; color: var(--color-gold, #c89d5c); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Głęboka Więź & Szafirowe Oczy</span>
                    </div>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0 0 16px; font-size: 0.92rem; line-height: 1.6; color: var(--color-ink-muted-80);">
                    <li style="margin-bottom: 8px;"><strong>Temperament:</strong> Niezwykle kontaktowy, lojalny, "kocie dziecko" podążające krok w krok za opiekunem.</li>
                    <li style="margin-bottom: 8px;"><strong>Szata:</strong> Delikatna, przylegająca do ciała, z eleganckim umaszczeniem point i błękitnymi oczami.</li>
                    <li style="margin-bottom: 8px;"><strong>Potrzeby:</strong> Dużo uwagi i czułości — źle znosi długotrwałą samotność i ignorowanie.</li>
                    <li><strong>Dla kogo:</strong> Dla osób szukających oddanego przyjaciela, który uwielbia brać udział we wszystkim, co robisz.</li>
                </ul>
            </div>
        </div>

        {{-- FAQ Section --}}
        <div style="max-width: 800px; margin: 0 auto; background: var(--color-canvas, #fff); border-radius: var(--r-xl); padding: var(--sp-2xl); border: 1px solid var(--color-hairline);">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-ink); margin-bottom: 20px; text-align: center;">Najczęstsze pytania o dobór rasy</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-ink); margin-bottom: 4px;">Czy kot bengalski nadaje się do mieszkania w bloku?</h4>
                    <p style="font-size: 0.92rem; color: var(--color-ink-muted-80); margin: 0; line-height: 1.5;">Tak! Kot bengalski świetnie czuje się w mieszkaniu pod warunkiem zagospodarowania przestrzeni wertykalnej — wysokich drapaków, półek ściennych oraz codziennej zabawy w polowanie wędką.</p>
                </div>
                <div style="border-top: 1px solid var(--color-hairline); padding-top: 16px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-ink); margin-bottom: 4px;">Czym różni się kot brytyjski krótkowłosy (BSH) od długowłosego (BLH)?</h4>
                    <p style="font-size: 0.92rem; color: var(--color-ink-muted-80); margin: 0; line-height: 1.5;">Obie rasy dzielą ten sam cudowny, opanowany i przyjacielski charakter. Różnią się długością okrywy: brytyjczyk krótkowłosy ma gęsty, sprężysty plusz, natomiast długowłosy (jak nasz reproduktor Staś) zachwyca jedwabistym półdługim włosem z puszystą kryzą, który wymaga czesania 1–2 razy w tygodniu.</p>
                </div>
                <div style="border-top: 1px solid var(--color-hairline); padding-top: 16px;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--color-ink); margin-bottom: 4px;">Jak socjalizacja w Waszej hodowli ułatwia adaptację kociaka?</h4>
                    <p style="font-size: 0.92rem; color: var(--color-ink-muted-80); margin: 0; line-height: 1.5;">Nasze kocięta nie znają klatek ani boksów. Od urodzenia mieszkają z nami w pokojach, słyszą odkurzacz, telewizor, rozmawiają z dziećmi i są codziennie brane na ręce. Dzięki temu w nowym domu adaptują się natychmiast i bez stresu.</p>
                </div>
            </div>
        </div>
    </x-frontend.section>

    {{-- Conversion CTA --}}
    <x-frontend.conversion-cta source-context="Quiz Dobierz Rasę" />

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
                    title: 'Kot Bengalski (Aktywność, Inteligencja i Bliska Relacja)',
                    desc: 'Twoje odpowiedzi sugerują, że w Twoim domu świetnie może odnaleźć się kot bengalski. Bengale to z reguły niezwykle bystre, ciekawskie i energiczne koty, które wnoszą do domu mnóstwo radości i uwielbiają interaktywne zabawy (np. aportowanie czy wędki). Każdy kociak ma jednak swój indywidualny charakter – w naszej domowej hodowli dbamy o staranną wczesną socjalizację, a podczas wideo-wizyty na żywo pomożemy Ci ocenić temperament konkretnego malucha.',
                    name: 'Kot Bengalski'
                },
                british: {
                    title: 'Kot Brytyjski Krótkowłosy (Zrównoważony Spokój i Pluszowa Natura)',
                    desc: 'Do wskazanego przez Ciebie stylu życia rekomendujemy kota brytyjskiego. Brytyjczyki są powszechnie cenione za zrównoważony temperament, łagodność i nienarzucające się towarzystwo. Choć z reguły dobrze znoszą chwile samodzielności w ciągu dnia, każdy kot potrzebuje codziennej dawki czułości i bezpiecznej rutyny. Chętnie przedstawimy Ci charaktery poszczególnych kociąt podczas rozmowy wideo.',
                    name: 'Kot Brytyjski'
                },
                siamese: {
                    title: 'Kot Syjamski (Głęboka Więź, Komunikatywność i Bliskość)',
                    desc: 'Twoje oczekiwania wskazują na rasę syjamską. Koty te słyną z niezwykłego przywiązania do opiekuna, inteligencji oraz bogatej wokalizacji – często stają się prawdziwym „cieniem” człowieka. Najlepiej czują się w domach, gdzie mogą liczyć na regularną obecność i bliski kontakt. Podczas bezpośredniej rozmowy pomożemy upewnić się, czy rytm Twojego domu odpowiada potrzebom konkretnego kociaka.',
                    name: 'Kot Syjamski'
                }
            };

            window.selectQuizOption = function(step, value) {
                quizState['step' + step] = value;

                // Track quiz start once when first option is clicked
                if (step === 1 && !quizState._startedTracked) {
                    quizState._startedTracked = true;
                    if (typeof window.trackFunnelStep === 'function') {
                        window.trackFunnelStep('quiz_started', {
                            first_choice: value
                        });
                    }
                }

                // Visual feedback on selected option
                const stepEl = document.getElementById('quizStep' + step);
                if (stepEl) {
                    stepEl.querySelectorAll('.quiz-option').forEach(opt => {
                        opt.classList.toggle('is-selected', opt.dataset.value === value);
                    });
                }

                if (step === 1) {
                    setTimeout(() => goToStep(2), 100);
                } else if (step === 2) {
                    setTimeout(() => goToStep(3), 100);
                } else if (step === 3) {
                    setTimeout(() => goToStep(4), 100);
                } else if (step === 4) {
                    setTimeout(() => showQuizResult(), 100);
                }
            };

            window.prevQuizStep = function(currentStep) {
                if (currentStep <= 1) return;
                goToStep(currentStep - 1);
            };

            function goToStep(nextStepNumber) {
                // Prevent invalid transitions: cannot proceed without prior steps
                if (nextStepNumber === 2 && !quizState.step1) { nextStepNumber = 1; }
                if (nextStepNumber === 3 && (!quizState.step1 || !quizState.step2)) { nextStepNumber = 1; }
                if (nextStepNumber === 4 && (!quizState.step1 || !quizState.step2 || !quizState.step3)) { nextStepNumber = 1; }

                const percentages = { 1: '25%', 2: '50%', 3: '75%', 4: '100%' };
                const percent = percentages[nextStepNumber] || '25%';

                const wrap = document.getElementById('quizProgressWrap');
                if (wrap) wrap.style.display = 'block';

                const resultBox = document.getElementById('quizResult');
                if (resultBox) resultBox.style.display = 'none';

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

                // Highlight previously selected option if any
                const savedVal = quizState['step' + nextStepNumber];
                if (target) {
                    target.querySelectorAll('.quiz-option').forEach(opt => {
                        opt.classList.toggle('is-selected', opt.dataset.value === savedVal);
                    });
                }

                // Smooth scroll to container for comfortable mobile viewing
                const container = document.getElementById('quizContainer');
                if (container && window.scrollY > 150) {
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            function calculateWinningBreed() {
                const scores = {
                    bengal: 0.0,
                    british: 0.0,
                    siamese: 0.0
                };

                // STEP 1: Energy & Lifestyle (Core temperament - weight 5.2)
                if (quizState.step1 === 'calm_couch') {
                    scores.british += 5.2;
                    scores.siamese += 1.0;
                } else if (quizState.step1 === 'active_curious') {
                    scores.bengal += 5.2;
                    scores.siamese += 2.0;
                } else if (quizState.step1 === 'devoted_vocal') {
                    scores.siamese += 5.2;
                    scores.bengal += 1.0;
                    scores.british += 1.0;
                }

                // STEP 2: Time away & Household Dynamics (weight 3.5 / 3.0 / 2.5)
                if (quizState.step2 === 'away_work') {
                    scores.british += 3.5;
                    scores.bengal += 1.0;
                    scores.siamese -= 3.0;
                } else if (quizState.step2 === 'home_work') {
                    scores.siamese += 3.0;
                    scores.bengal += 2.0;
                    scores.british += 1.0;
                } else if (quizState.step2 === 'family_children') {
                    scores.british += 2.5;
                    scores.bengal += 2.5;
                    scores.siamese += 1.5;
                } else if (quizState.step2 === 'other_pets') {
                    scores.bengal += 2.0;
                    scores.siamese += 2.0;
                    scores.british += 2.0;
                }

                // STEP 3: Coat & Interaction Preferences (weight 3.0)
                if (quizState.step3 === 'short_sleek') {
                    scores.bengal += 3.0;
                    scores.siamese += 1.5;
                } else if (quizState.step3 === 'dense_plush') {
                    scores.british += 3.0;
                } else if (quizState.step3 === 'interactive_shadow') {
                    scores.siamese += 3.0;
                    scores.bengal += 1.0;
                }

                // Determine winner with deterministic resolution
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
                        return 'Ponieważ spędzasz część dnia poza domem, zrównoważony charakter kota brytyjskiego może być optymalny. W przeciwieństwie do ras bardzo wymagających stałej uwagi, brytyjczyki zazwyczaj spokojnie odpoczywają, choć po Twoim powrocie docenią wspólną chwilę relaksu.';
                    }
                    if (quizState.step2 === 'family_children') {
                        return 'W domu z dziećmi kot brytyjski wyróżnia się zazwyczaj cierpliwością, stabilnym układem nerwowym i opanowaniem. W naszej hodowli maluchy od pierwszych tygodni oswajają się z domowym ruchem.';
                    }
                    return 'Kot brytyjski idealnie komponuje się ze zrównoważonym rytmem domowym, oferując aksamitną miękkość i arystokratyczną dyskrecję.';
                } else if (breed === 'bengal') {
                    if (quizState.step1 === 'active_curious') {
                        return 'Twoja chęć aktywnej zabawy i nauki sztuczek idealnie odpowiada naturalnej ciekawości kota bengalskiego. Bengale to żywiołowe, pełne pasji koty, które uwielbiają interakcję z opiekunem i stymulację umysłową.';
                    }
                    return 'Bengale łączą zjawiskowy rysunek rozet z niezwykłą domową czułością. Odpowiednia dawka wspólnej zabawy zapewni mu harmonijne, szczęśliwe życie w Twoim domu.';
                } else if (breed === 'siamese') {
                    if (quizState.step2 === 'home_work') {
                        return 'Praca w domu lub częsta obecność domowników to dla syjama wymarzone środowisko. Koty te bardzo cenią ciągły kontakt i możliwość aktywnego uczestniczenia w codziennych czynnościach.';
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
                let totalAvailableInGrid = 0;
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

                    totalAvailableInGrid++;

                    if (breed === matchedBreed) {
                        item.style.display = '';
                        matchedCount++;
                        if (ready === 'now') {
                            hasReadyNowInMatched = true;
                        }
                    } else {
                        item.style.display = 'none';
                    }
                });

                window.__quizMatchedCount = matchedCount;
                window.__quizTotalAvailableCount = totalAvailableInGrid;

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

                // 2. If user chose 'ready_now', but matched breed kittens are in early reservation (e.g. British Ada, Alma, Atena < 12 weeks)
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
                    if (matchedCount === 0 && totalAvailableInGrid > 0) {
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

                // Fallback when catalogue has 0 available cats at all
                const emptyCatalogueNotice = document.getElementById('quizEmptyCatalogueNotice');
                if (emptyCatalogueNotice) {
                    if (totalAvailableInGrid === 0) {
                        emptyCatalogueNotice.style.display = 'block';
                    } else {
                        emptyCatalogueNotice.style.display = 'none';
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
                                clone.style.display = '';
                                // Preserve href attributes on anchor elements
                                const origBtns = item.querySelectorAll('[href]');
                                const cloneBtns = clone.querySelectorAll('[href]');
                                origBtns.forEach((origA, i) => {
                                    if (cloneBtns[i]) {
                                        cloneBtns[i].href = origA.href;
                                        if (origA.getAttribute('onclick')) {
                                            cloneBtns[i].setAttribute('onclick', origA.getAttribute('onclick'));
                                        }
                                    }
                                });
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
                                    ? `Maluchy rasy ${info.name} potrzebują jeszcze paru tygodni socjalizacji (12–14 tyg.). Poznaj nasze w pełni odchowane maluchy innych ras, gotowe do odbioru od ręki:`
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

                // Funnel analytics event (no sensitive household PII passed)
                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('quiz_completed', {
                        matched_breed: info.name,
                        timeline: quizState.step4 || 'flexible',
                        matched_count: matchedCount,
                    });
                }
            }

            /**
             * PART 3 + 6: Fire contact event for Messenger or WhatsApp CTA buttons.
             * Builds dynamic URL with breed context and fires Meta Pixel + GA4 events.
             * @param {string} channel  'messenger' | 'whatsapp'
             * @param {string} location  CTA location label for analytics
             */
            window.fireQuizContactEvent = function(channel, location) {
                const breed = window.__quizMatchedBreedName || 'Kot Rasowy';
                const hasAvailable = (window.__quizMatchedCount || 0) > 0;
                const timeframeLabels = {
                    ready_now: 'jak najszybciej (kociak min. 12–14 tyg.)',
                    ready_future: 'za 3–6 tygodni / wczesna rezerwacja',
                    ready_flexible: 'elastycznie (liczy się dopasowanie charakteru)'
                };
                const timeframe = timeframeLabels[quizState.step4] || 'termin elastyczny';

                let attributionCode = 'Quiz_Dopasowania';
                try {
                    if (typeof window.getTrafficSourceLabel === 'function') {
                        attributionCode = window.getTrafficSourceLabel();
                    }
                } catch(e) {}

                // PART 6: Meta Pixel Contact event
                try {
                    if (typeof fbq === 'function') {
                        fbq('track', 'Contact', { content_name: breed, method: channel === 'messenger' ? 'Messenger' : 'WhatsApp' });
                    }
                } catch(e) {}

                // PART 6: GA4 generate_lead push
                try {
                    if (typeof window.dataLayer !== 'undefined') {
                        window.dataLayer.push({ event: 'generate_lead', contact_method: channel, breed: breed, quiz_location: location });
                    }
                } catch(e) {}

                // Legacy funnel tracker (GA4 + fbq wrapper)
                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('contact_cta_click', {
                        channel: channel,
                        origin: 'quiz_result_' + location,
                        matched_breed: breed,
                        has_available: hasAvailable,
                        timeframe: quizState.step4 || 'flexible'
                    });
                }

                // Backward-compatibility alias for legacy scripts and test suites
                window.contactBreederFromQuiz = function() {
                    return window.fireQuizContactEvent('whatsapp', 'legacy');
                };

                // Update href dynamically with breed context before navigation
                if (channel === 'messenger') {
                    const msgText = hasAvailable
                        ? `Dzień dobry! W teście wyszedł mi ${breed}. W jakiej cenie są aktualnie kocięta i które maluchy są wolne? [Źródło: ${attributionCode}]`
                        : `Dzień dobry! W teście wyszedł mi ${breed}. W jakiej cenie są kocięta i kiedy planujecie nowy miot? [Źródło: ${attributionCode}]`;
                    const url = `https://m.me/61580668026948?text=${encodeURIComponent(msgText)}`;
                    window.open(url, '_blank');
                } else {
                    // WhatsApp
                    const waMsg = hasAvailable
                        ? `Dzień dobry! Rozwiązałem(am) test na stronie – mój wynik to ${breed}. W jakiej cenie są aktualnie kocięta i czy mogę prosić o krótki filmik z wolnymi maluchami? [Źródło: ${attributionCode}]`
                        : `Dzień dobry! Rozwiązałem(am) test na stronie – mój wynik to ${breed}. W jakiej cenie są kocięta i kiedy planujecie nowy miot? [Źródło: ${attributionCode}]`;
                    const url = `https://wa.me/48514153204?text=${encodeURIComponent(waMsg)}`;
                    window.open(url, '_blank');
                }
            };

            /**
             * PART 4 + 6: Fire contact event from an individual kitten card.
             * @param {string} channel  'messenger' | 'whatsapp'
             * @param {string} catName  Kitten's name
             * @param {string} catBreed Kitten's breed
             */
            window.fireQuizCatEvent = function(channel, catName, catBreed) {
                // PART 6: Meta Pixel
                try {
                    if (typeof fbq === 'function') {
                        fbq('track', 'Contact', { content_name: catName + ' (' + catBreed + ')', method: channel === 'messenger' ? 'Messenger' : 'WhatsApp' });
                    }
                } catch(e) {}

                // PART 6: GA4
                try {
                    if (typeof window.dataLayer !== 'undefined') {
                        window.dataLayer.push({ event: 'generate_lead', contact_method: channel, breed: catBreed, cat_name: catName });
                    }
                } catch(e) {}

                if (typeof window.trackFunnelStep === 'function') {
                    window.trackFunnelStep('contact_cta_click', { channel: channel, origin: 'quiz_cat_card', cat_name: catName });
                }
                // href is pre-rendered server-side; navigation is handled by the <a> tag itself
            };

            /**
             * PART 5 + 6: Fire tracking event when user clicks Facebook follow button.
             */
            window.fireQuizFollowEvent = function() {
                // PART 6: Meta Pixel custom event
                try {
                    if (typeof fbq === 'function') {
                        fbq('trackCustom', 'FacebookFollowClick', { location: 'quiz_result' });
                    }
                } catch(e) {}

                try {
                    if (typeof window.dataLayer !== 'undefined') {
                        window.dataLayer.push({ event: 'facebook_follow_click', location: 'quiz_result' });
                    }
                } catch(e) {}
            };

            window.openCallbackFromQuiz = function() {
                const breed = window.__quizMatchedBreedName || 'Kot Rasowy';
                const timeframeLabels = {
                    ready_now: 'odbiór: jak najszybciej',
                    ready_future: 'odbiór: za 3–6 tyg.',
                    ready_flexible: 'odbiór: elastyczny'
                };
                const timeframe = timeframeLabels[quizState.step4] || 'termin elastyczny';

                if (typeof window.openReservationModal === 'function') {
                    window.openReservationModal(
                        `Wynik Quizu: ${breed} (${timeframe})`,
                        breed,
                        'Zgłoszenie z Quizu'
                    );
                }
            };

            window.restartQuiz = function() {
                quizState.step1 = null;
                quizState.step2 = null;
                quizState.step3 = null;
                quizState.step4 = null;

                document.querySelectorAll('.quiz-option').forEach(opt => opt.classList.remove('is-selected'));

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

                const emptyCatalogueNotice = document.getElementById('quizEmptyCatalogueNotice');
                if (emptyCatalogueNotice) emptyCatalogueNotice.style.display = 'none';

                goToStep(1);
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
