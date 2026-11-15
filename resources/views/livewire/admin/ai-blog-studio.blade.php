{{-- AI Blog Studio — 3-step wizard with AI Agent & MCP Tools Integration --}}
<div>

    {{-- ─── Progress Bar ────────────────────────────────────────────────── --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold text-muted small">
                Krok {{ $currentStep }} z 3 —
                @if($currentStep === 1) Wybierz rasę i temat
                @elseif($currentStep === 2) Wybierz koty do artykułu
                @else Podgląd i zapis szkicu
                @endif
            </span>
            @if($currentStep > 1)
                <button type="button" wire:click="backToStep({{ $currentStep - 1 }})"
                        class="btn btn-sm btn-outline-secondary">
                    ← Wstecz
                </button>
            @endif
        </div>
        <div class="progress" style="height: 6px;">
            <div class="progress-bar bg-primary"
                 style="width: {{ $currentStep * 33 }}%; transition: width .4s ease;">
            </div>
        </div>
    </div>

    {{-- ─── Error / Success Messages ───────────────────────────────────── --}}
    @if($errorMessage)
        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm" role="alert">
            <i class="fas fa-triangle-exclamation me-2 fa-lg"></i>
            <div>
                <strong>Uwaga:</strong> {{ $errorMessage }}
            </div>
        </div>
    @endif

    @if($successMessage)
        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2 fa-lg"></i>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span>{{ $successMessage }}</span>
                @if($savedPostSlug)
                    <a href="{{ route('backend.posts.edit', $savedPostSlug) }}" class="btn btn-sm btn-success fw-bold">
                        <i class="fas fa-edit me-1"></i> Otwórz edycję w nowym szablonie →
                    </a>
                @endif
                <a href="{{ route('backend.posts.index') }}" class="alert-link ms-1">Lista postów</a>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- STEP 1: Breed + Topic Discovery via AI Agent & MCP --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @if($currentStep === 1)
        <div wire:key="step-1">

            {{-- ─── Breed selector ─────────────────────────────────────── --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="fas fa-paw me-1 text-primary"></i> Rasa kota
                </label>
                <div class="row g-2">
                    @foreach($breeds as $key => $label)
                        <div class="col-12 col-md-4" wire:key="breed-card-{{ $key }}">
                            <button type="button"
                                    wire:click="selectBreed('{{ $key }}')"
                                    id="breed-btn-{{ $key }}"
                                    class="btn w-100 {{ $selectedBreed === $key ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                {{ $label }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ─── Topic Error Alert (ALWAYS visible if set) ──────────── --}}
            @if($topicError)
                <div class="alert alert-warning d-flex align-items-start gap-3 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-triangle-exclamation fa-lg mt-1 text-warning flex-shrink-0"></i>
                    <div class="flex-grow-1">
                        <strong class="d-block text-dark">Informacja o stanie usługi AI:</strong>
                        <span class="text-secondary small d-block mb-2">{{ $topicError }}</span>
                        <span class="badge bg-dark text-warning font-monospace">Wpisz własny temat poniżej lub kliknij ponów próbę</span>
                    </div>
                    <button type="button"
                            wire:click="fetchTopics"
                            wire:loading.attr="disabled"
                            class="btn btn-sm btn-outline-dark ms-auto flex-shrink-0">
                        <span wire:loading.remove wire:target="fetchTopics">
                            <i class="fas fa-rotate me-1"></i>Ponów próbę
                        </span>
                        <span wire:loading wire:target="fetchTopics" style="display: none;">
                            <span class="spinner-border spinner-border-sm me-1"></span>Łączenie z AI…
                        </span>
                    </button>
                </div>
            @endif

            {{-- ─── Topic suggestions ──────────────────────────────────── --}}
            <div class="mb-4">

                @if(! $topicsLoaded)
                    {{-- === STAN POCZĄTKOWY: Brak akcji aż do kliknięcia przycisku === --}}
                    <div class="text-center py-4 border rounded bg-light">
                        <p class="text-muted mb-3">
                            Tematy z AI nie są ładowane automatycznie.<br>
                            Kliknij przycisk poniżej, aby Agent AI pobrał najnowsze trendy dla rasy <strong>{{ $breeds[$selectedBreed] ?? $selectedBreed }}</strong>.
                        </p>
                        <button type="button"
                                wire:click="fetchTopics"
                                wire:loading.attr="disabled"
                                id="fetch-topics-btn"
                                class="btn btn-primary btn-lg px-5 shadow-sm">
                            <span wire:loading.remove wire:target="fetchTopics">
                                <i class="fas fa-robot me-2"></i> Uruchom Agenta AI (Pobierz tematy)
                            </span>
                            <span wire:loading wire:target="fetchTopics" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Agent AI analizuje dane… (proszę czekać)
                            </span>
                        </button>
                    </div>

                @else
                    {{-- === TEMATY ZAŁADOWANE (lub błąd AI) === --}}

                    @if(!empty($topics))
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label fw-semibold mb-0">
                                Sugerowane tematy (Agent AI)
                                @if($topicSource === 'cache')
                                    <span class="badge bg-secondary text-white ms-1 fw-normal">
                                        <i class="fas fa-database me-1"></i>Pamięć podręczna
                                    </span>
                                @else
                                    <span class="badge bg-success text-white ms-1 fw-normal">
                                        <i class="fas fa-bolt me-1"></i>Na żywo z Google Trends & AI
                                    </span>
                                @endif
                            </label>

                            <button type="button"
                                    wire:click="refreshTopics"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Wymuś powtórzenie analizy przez Agenta">
                                <span wire:loading.remove wire:target="refreshTopics">
                                    <i class="fas fa-rotate me-1"></i>Odśwież tematy z AI
                                </span>
                                <span wire:loading wire:target="refreshTopics" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-1"></span>Pobieram…
                                </span>
                            </button>
                        </div>

                        <div class="row g-2 mb-3">
                            @foreach($topics as $i => $topic)
                                <div class="col-12 col-md-6" wire:key="topic-card-{{ $i }}-{{ $selectedBreed }}">
                                    <button type="button"
                                            wire:click="selectTopic({{ $i }})"
                                            id="topic-btn-{{ $i }}"
                                            class="btn w-100 text-start {{ ($selectedTopic === $topic['title'] || $customTopic === $topic['title']) ? 'btn-primary' : 'btn-outline-secondary' }}"
                                            style="white-space: normal; line-height: 1.4; user-select: text;">
                                        <span class="d-block fw-semibold">{{ $topic['title'] }}</span>
                                        <span class="badge {{ ($topic['intent'] ?? '') === 'commercial' ? 'bg-warning text-dark' : 'bg-info' }} mt-1" style="font-size:.65rem;">
                                            {{ ($topic['intent'] ?? '') === 'commercial' ? 'Zakupowy' : 'Edukacyjny' }}
                                        </span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Logi wykonania Agenta AI --}}
                    @if(!empty($agentLogs))
                        <div class="card bg-dark text-light border-secondary mb-4 shadow-sm" style="border-radius: 8px;">
                            <div class="card-header bg-black text-info d-flex align-items-center justify-content-between py-2 border-bottom border-secondary">
                                <span class="fw-bold font-monospace small">
                                    <i class="fas fa-microchip me-2 text-warning"></i> AI Agent Console (MCP Execution Log)
                                </span>
                                <span class="badge bg-success" style="font-size: 0.65rem;">Execution Log</span>
                            </div>
                            <div class="card-body p-3 font-monospace small" style="max-height: 160px; overflow-y: auto; background-color: #121212;">
                                @foreach($agentLogs as $log)
                                    <div class="text-light opacity-90 py-1 border-bottom border-secondary border-opacity-25" style="font-size: 0.82rem;">
                                        <span class="text-success fw-bold">❯</span> {{ $log }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Selected / Custom topic field --}}
            <div class="mb-4">
                <label for="customTopic" class="form-label fw-semibold">
                    <i class="fas fa-pencil me-1"></i> Temat artykułu <span class="text-muted fw-normal">(wybierz powyżej lub wpisz własny)</span>
                </label>
                <input type="text"
                       id="customTopic"
                       wire:model="customTopic"
                       wire:keydown.enter.prevent="goToStep2"
                       class="form-control form-control-lg"
                       placeholder="Wybierz temat z listy powyżej lub wpisz własny temat tutaj...">
            </div>

            {{-- CTA --}}
            <div class="d-grid">
                <button type="button"
                        wire:click="goToStep2"
                        wire:loading.attr="disabled"
                        id="step1-next-btn"
                        class="btn btn-primary btn-lg">
                    <span wire:loading.remove wire:target="goToStep2">Dalej: Wybierz koty →</span>
                    <span wire:loading wire:target="goToStep2" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-2"></span>Przechodzę…
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- STEP 2: Select Animals from DB --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @if($currentStep === 2)
        <div wire:key="step-2">
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="fas fa-info-circle fa-lg me-2"></i>
                <div>
                    Wybrany temat: <strong>"{{ $customTopic }}"</strong><br>
                    <span class="small text-muted">Wybierz koty z Twojej hodowli, których zdjęcia zostaną automatycznie dołączone do tekstu artykułu.</span>
                </div>
            </div>

            @php
                $breedLabel = $breeds[$selectedBreed] ?? $selectedBreed;
                $currentAnimals = $animals;
            @endphp

            @if(empty($currentAnimals))
                <div class="text-center py-4 text-muted border rounded bg-light mb-4">
                    <i class="fas fa-cat fa-2x mb-2 d-block"></i>
                    Brak kotów w bazie dla rasy {{ $breedLabel }}.
                </div>
            @else
                <div class="row g-3 mb-4">
                    @foreach($currentAnimals as $animal)
                        @php $isSelected = in_array($animal['id'], $selectedAnimalIds, true); @endphp
                        <div class="col-6 col-md-3" wire:key="animal-card-{{ $animal['id'] }}">
                            <div class="card h-100 {{ $isSelected ? 'border-primary shadow-sm bg-primary bg-opacity-10' : '' }}"
                                 wire:click="toggleAnimal('{{ $animal['id'] }}')"
                                 style="cursor: pointer;">
                                @if($animal['photo_url'])
                                    <img src="{{ $animal['photo_url'] }}" class="card-img-top" alt="{{ $animal['name'] }}" style="height: 140px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white text-center py-4" style="height: 140px;">
                                        <i class="fas fa-cat fa-2x mt-3"></i>
                                    </div>
                                @endif
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title mb-1 fw-bold">{{ $animal['name'] }}</h6>
                                    <span class="badge bg-secondary mb-1" style="font-size: 0.65rem;">{{ $animal['color'] }}</span>
                                    @if(!empty($animal['photo_url']))
                                        @php
                                            $aGeo = \App\Support\ImageGeometry::analyze($animal['photo_url']);
                                        @endphp
                                        <span class="badge {{ $aGeo['is_portrait'] ? 'bg-info bg-opacity-25 text-dark' : 'bg-light text-secondary border' }} d-block mx-auto mb-1" style="font-size: 0.62rem; width: fit-content;">
                                            <i class="fas {{ $aGeo['is_portrait'] ? 'fa-mobile-screen' : 'fa-image' }} me-1"></i>
                                            {{ $aGeo['is_portrait'] ? '3:4 Portret' : ($aGeo['is_square'] ? '1:1 Awatar' : '16:9 Poziome') }}
                                        </span>
                                    @endif
                                    <div class="form-check d-flex justify-content-center mt-1">
                                        <input class="form-check-input" type="checkbox"
                                               value="{{ $animal['id'] }}"
                                               @if($isSelected) checked @endif
                                               onclick="return false;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <button type="button" wire:click="backToStep(1)" class="btn btn-outline-secondary btn-lg">
                    ← Wstecz
                </button>
                <button type="button" wire:click="goToStep3" class="btn btn-primary btn-lg px-5" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="goToStep3">Generuj artykuł z AI →</span>
                    <span wire:loading wire:target="goToStep3" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-2"></span>Generuję artykuł z AI…
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- STEP 3: Preview & Save Draft --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @if($currentStep === 3)
        <div wire:key="step-3">
            @if($isGenerating)
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h4>AI pisze wpis na bloga...</h4>
                    <p class="text-muted">Generuję nagłówki, treść SEO oraz prompty graficzne.</p>
                </div>
            @elseif($generatedDraft)
                {{-- Editorial Cover & Header Preview (Magazine / Split Hero) --}}
                @if(!empty($generatedDraft['hero_image_preview_url']))
                    @php
                        $heroLayout = $generatedDraft['hero_layout'] ?? 'split';
                        $heroAnimals = collect($animals)
                            ->filter(fn ($a) => !empty($a['photo_url']))
                            ->sortByDesc(fn ($a) => in_array($a['id'], $selectedAnimalIds, true))
                            ->take(5);
                    @endphp

                    <div class="card mb-4 border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: #faf8f5; border: 1px solid rgba(197, 160, 89, 0.3) !important;">
                        @if($heroLayout === 'split')
                            {{-- Editorial Split Hero (Kinfolk / Apple Newsroom Magazine style) --}}
                            <div class="row g-0 align-items-center">
                                <div class="col-12 col-lg-7 p-4 p-md-5">
                                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                        <span class="badge bg-warning text-dark fw-bold">
                                            <i class="fas {{ $selectedHeroAnimalId !== '' ? 'fa-paw' : 'fa-magic' }} me-1"></i>
                                            {{ $selectedHeroAnimalId !== '' ? 'Wybrany kot z hodowli' : 'AI Editorial Hero' }}
                                        </span>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                            <i class="fas fa-columns me-1"></i>Układ: Editorial Split (3:4 Portret — 0% uciętych pikseli)
                                        </span>
                                    </div>
                                    <h2 class="fw-bold mb-3" style="color: #1e293b; font-size: clamp(1.4rem, 2.5vw, 2.1rem); line-height: 1.25;">
                                        {{ $generatedDraft['title'] ?? 'Tytuł wpisu' }}
                                    </h2>
                                    <p class="text-secondary mb-3" style="font-size: 1.05rem; line-height: 1.6;">
                                        {{ $generatedDraft['excerpt'] ?? '' }}
                                    </p>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <span><i class="fas fa-feather me-1 text-warning"></i>Hodowla z Mazowieckiej Szwajcarii</span>
                                        <span>•</span>
                                        <span><i class="fas fa-clock me-1 text-primary"></i>~4 min czytania</span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5 p-3 p-md-4 text-center">
                                    <div class="position-relative d-inline-block shadow-sm" style="border-radius: 14px; overflow: hidden; border: 2px solid rgba(197, 160, 89, 0.4); max-height: 380px; aspect-ratio: 3 / 4; background: #ffffff;">
                                        <img src="{{ $generatedDraft['hero_image_preview_url'] }}"
                                             wire:key="hero-preview-{{ md5($generatedDraft['hero_image_preview_url']) }}"
                                             alt="Okładka artykułu"
                                             style="height: 100%; width: 100%; object-fit: cover; object-position: center 20%;">
                                        <span class="position-absolute bottom-0 start-0 w-100 py-1 px-2 text-white small text-center"
                                              style="background: rgba(0,0,0,0.65); font-size: 0.72rem;">
                                            <i class="fas fa-check-circle text-success me-1"></i>Pełna sylwetka kota bez przycinania (3:4)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Grand Magazine Frame (Panoramic 16:9) --}}
                            <div class="p-4 p-md-5">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-warning text-dark fw-bold">
                                        <i class="fas {{ $selectedHeroAnimalId !== '' ? 'fa-paw' : 'fa-magic' }} me-1"></i>
                                        {{ $selectedHeroAnimalId !== '' ? 'Wybrany kot z hodowli' : 'AI Hero Cover' }}
                                    </span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        <i class="fas fa-image me-1"></i>Układ: Grand Magazine (16:9 Panorama)
                                    </span>
                                </div>
                                <h2 class="fw-bold mb-3" style="color: #1e293b; font-size: clamp(1.4rem, 2.5vw, 2.1rem); line-height: 1.25;">
                                    {{ $generatedDraft['title'] ?? 'Tytuł wpisu' }}
                                </h2>
                                <p class="text-secondary mb-4" style="font-size: 1.05rem; line-height: 1.6; max-width: 900px;">
                                    {{ $generatedDraft['excerpt'] ?? '' }}
                                </p>
                                <div class="position-relative shadow-sm" style="border-radius: 14px; overflow: hidden; max-height: 420px; aspect-ratio: 16 / 9; border: 2px solid rgba(197, 160, 89, 0.4); background: #141414;">
                                    <img src="{{ $generatedDraft['hero_image_preview_url'] }}"
                                         wire:key="hero-preview-{{ md5($generatedDraft['hero_image_preview_url']) }}"
                                         alt="Okładka artykułu"
                                         style="height: 100%; width: 100%; object-fit: cover; object-position: center 25%;">
                                </div>
                            </div>
                        @endif

                        <div class="card-footer bg-white border-top py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-semibold text-muted">Układ okładki:</span>
                                <button type="button" wire:click="setHeroLayout('split')"
                                        class="btn btn-sm {{ $heroLayout === 'split' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fas fa-columns me-1"></i>Split Editorial (3:4 Portret)
                                </button>
                                <button type="button" wire:click="setHeroLayout('magazine')"
                                        class="btn btn-sm {{ $heroLayout === 'magazine' ? 'btn-primary fw-bold' : 'btn-outline-secondary' }}">
                                    <i class="fas fa-image me-1"></i>Grand Magazine (16:9 Panorama)
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="small fw-semibold text-muted">Zmień zdjęcie:</span>
                                <button type="button" wire:click="regenerateAiHero" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-sync-alt me-1"></i>Nowe tło AI
                                </button>
                                @foreach($heroAnimals as $hAnimal)
                                    <button type="button"
                                            wire:key="hero-animal-btn-{{ $hAnimal['id'] }}"
                                            wire:click="selectHeroFromAnimal('{{ $hAnimal['id'] }}')"
                                            class="btn btn-sm {{ $selectedHeroAnimalId === $hAnimal['id'] ? 'btn-warning text-dark fw-semibold' : 'btn-outline-dark' }}">
                                        🐾 {{ $hAnimal['name'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card mb-4 border-primary shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-primary text-white me-2">Nowy Szablon Editorialny</span>
                            <span class="badge bg-warning text-dark">SZKIC (Draft)</span>
                        </div>
                        <span class="text-muted small fw-semibold">
                            <i class="fas fa-layer-group me-1 text-primary"></i>
                            {{ !empty($generatedDraft['sections']) ? count($generatedDraft['sections']) . ' sekcji z mediami' : '' }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        {{-- H1 --}}
                        <h2 class="fw-bold mb-3" style="color: #1e293b;">
                            {{ $generatedDraft['title'] ?? 'Szkic wpisu' }}
                        </h2>

                        {{-- Excerpt / Lead --}}
                        @if(!empty($generatedDraft['excerpt']))
                            <div class="p-3 bg-light rounded border-start border-4 border-primary mb-4">
                                <span class="fw-bold text-uppercase small text-muted d-block mb-1">
                                    <i class="fas fa-feather me-1 text-primary"></i> Wstęp / Lead artykułu:
                                </span>
                                <p class="lead mb-0 text-secondary" style="font-size: 1.05rem;">
                                    {{ $generatedDraft['excerpt'] }}
                                </p>
                            </div>
                        @endif

                        {{-- Structured Sections --}}
                        @if(!empty($generatedDraft['sections']))
                            <div class="structured-sections-preview">
                                @foreach($generatedDraft['sections'] as $sIdx => $sec)
                                    @php
                                        $secLayout = $sec['layout'] ?? (($sec['orientation'] ?? '') === 'portrait' ? 'portrait' : 'landscape');
                                        $hasImage1 = !empty($sec['image_url']);
                                        $hasImage2 = !empty($sec['image_url_2']);
                                        $isDuo = $secLayout === 'duo' && $hasImage1 && $hasImage2;
                                    @endphp
                                    <div class="p-3 mb-4 rounded border bg-white shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-secondary text-white" style="font-size: 0.72rem;">
                                                    {{ $sIdx === 0 ? 'Sekcja 1 (Wprowadzenie)' : 'Sekcja ' . ($sIdx + 1) }}
                                                </span>
                                                @if($isDuo)
                                                    <span class="badge bg-primary text-white" style="font-size: 0.7rem;">
                                                        <i class="fas fa-clone me-1"></i>Smartfonowy Duet (Side-by-Side 3:4)
                                                    </span>
                                                @elseif($secLayout === 'portrait' || ($sec['orientation'] ?? '') === 'portrait')
                                                    <span class="badge bg-info text-dark" style="font-size: 0.7rem;">
                                                        <i class="fas fa-portrait me-1"></i>Kadr Portretowy (3:4)
                                                    </span>
                                                @elseif($hasImage1)
                                                    <span class="badge bg-secondary text-white" style="font-size: 0.7rem;">
                                                        <i class="fas fa-image me-1"></i>Panorama (16:9)
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Layout Switcher for section --}}
                                            @if($hasImage1)
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if($hasImage2)
                                                        <button type="button" wire:click="setSectionLayout({{ $sIdx }}, 'duo')"
                                                                class="btn btn-xs py-0 px-2 {{ $secLayout === 'duo' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                                title="Duet dwóch pionowych zdjęć 3:4">
                                                            Duet 3:4
                                                        </button>
                                                    @endif
                                                    <button type="button" wire:click="setSectionLayout({{ $sIdx }}, 'portrait')"
                                                            class="btn btn-xs py-0 px-2 {{ $secLayout === 'portrait' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                            title="Portret 3:4 obok tekstu">
                                                        Portret 3:4
                                                    </button>
                                                    <button type="button" wire:click="setSectionLayout({{ $sIdx }}, 'landscape')"
                                                            class="btn btn-xs py-0 px-2 {{ $secLayout === 'landscape' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                            title="Szeroka panorama 16:9">
                                                        Panorama 16:9
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        @if(!empty($sec['heading']))
                                            <h4 class="fw-bold text-dark mt-2 mb-2" style="font-size: 1.25rem;">
                                                {{ $sec['heading'] }}
                                            </h4>
                                        @endif

                                        {{-- Content and Images display according to layout --}}
                                        @if($isDuo)
                                            {{-- Duo Layout: Full text + Pro-tip, then 2 vertical photos side-by-side --}}
                                            <div class="mb-3">
                                                @foreach(explode("\n", $sec['body'] ?? '') as $p)
                                                    @if(trim($p) !== '')
                                                        <p class="text-muted mb-2" style="line-height: 1.6;">{{ trim($p) }}</p>
                                                    @endif
                                                @endforeach

                                                @if(!empty($sec['tip']))
                                                    <div class="p-2 px-3 mt-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-50 small">
                                                        <strong class="text-warning-emphasis d-block mb-1">
                                                            <i class="fas fa-lightbulb text-warning me-1"></i> Wskazówka hodowcy:
                                                        </strong>
                                                        <span class="fst-italic text-dark">{{ $sec['tip'] }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row g-2 mt-2 pt-2 border-top">
                                                <div class="col-6 text-center">
                                                    <div class="position-relative shadow-sm rounded overflow-hidden" style="border: 2px solid rgba(197, 160, 89, 0.35); aspect-ratio: 3 / 4; max-height: 240px; background: #faf8f5;">
                                                        <img src="{{ $sec['image_url'] }}"
                                                             class="w-100 h-100"
                                                             style="object-fit: cover; object-position: center 20%;"
                                                             alt="{{ $sec['image_caption'] ?? 'Kadr 1' }}">
                                                    </div>
                                                    <small class="text-muted d-block mt-1 font-monospace" style="font-size: 0.72rem;">
                                                        🐾 {{ $sec['image_caption'] ?? 'Kadr 1 (3:4)' }}
                                                    </small>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <div class="position-relative shadow-sm rounded overflow-hidden" style="border: 2px solid rgba(197, 160, 89, 0.35); aspect-ratio: 3 / 4; max-height: 240px; background: #faf8f5;">
                                                        <img src="{{ $sec['image_url_2'] }}"
                                                             class="w-100 h-100"
                                                             style="object-fit: cover; object-position: center 20%;"
                                                             alt="{{ $sec['image_caption_2'] ?? 'Kadr 2' }}">
                                                    </div>
                                                    <small class="text-muted d-block mt-1 font-monospace" style="font-size: 0.72rem;">
                                                        🐾 {{ $sec['image_caption_2'] ?? 'Kadr 2 (3:4)' }}
                                                    </small>
                                                </div>
                                            </div>

                                        @elseif($hasImage1)
                                            {{-- Single Image Layout (Portrait or Landscape) --}}
                                            <div class="row g-3">
                                                <div class="col-12 col-md-8">
                                                    @foreach(explode("\n", $sec['body'] ?? '') as $p)
                                                        @if(trim($p) !== '')
                                                            <p class="text-muted mb-2" style="line-height: 1.6;">{{ trim($p) }}</p>
                                                        @endif
                                                    @endforeach

                                                    @if(!empty($sec['tip']))
                                                        <div class="p-2 px-3 mt-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-50 small">
                                                            <strong class="text-warning-emphasis d-block mb-1">
                                                                <i class="fas fa-lightbulb text-warning me-1"></i> Wskazówka hodowcy:
                                                            </strong>
                                                            <span class="fst-italic text-dark">{{ $sec['tip'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12 col-md-4 text-center">
                                                    <div class="position-relative shadow-sm rounded overflow-hidden mx-auto"
                                                         style="{{ $secLayout === 'portrait' ? 'aspect-ratio: 3 / 4; max-height: 230px;' : 'aspect-ratio: 16 / 9; max-height: 180px;' }} border: 1px solid rgba(197, 160, 89, 0.3); background: #faf8f5;">
                                                        <img src="{{ $sec['image_url'] }}"
                                                             class="w-100 h-100"
                                                             style="object-fit: cover; object-position: center 20%;"
                                                             alt="Zdjęcie sekcji">
                                                    </div>
                                                    <small class="text-muted d-block mt-1 font-monospace" style="font-size: 0.72rem;">
                                                        <i class="fas fa-camera text-secondary me-1"></i>{{ $sec['image_caption'] ?? ($secLayout === 'portrait' ? 'Kadr portretowy 3:4' : 'Panorama 16:9') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            {{-- No Image Layout --}}
                                            <div class="col-12">
                                                @foreach(explode("\n", $sec['body'] ?? '') as $p)
                                                    @if(trim($p) !== '')
                                                        <p class="text-muted mb-2" style="line-height: 1.6;">{{ trim($p) }}</p>
                                                    @endif
                                                @endforeach

                                                @if(!empty($sec['tip']))
                                                    <div class="p-2 px-3 mt-3 rounded bg-warning bg-opacity-10 border border-warning border-opacity-50 small">
                                                        <strong class="text-warning-emphasis d-block mb-1">
                                                            <i class="fas fa-lightbulb text-warning me-1"></i> Wskazówka hodowcy:
                                                        </strong>
                                                        <span class="fst-italic text-dark">{{ $sec['tip'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="article-body">
                                {!! \Illuminate\Support\Str::markdown($generatedDraft['body'] ?? '', ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                            </div>
                        @endif

                        {{-- Featured Cats preview --}}
                        @if(!empty($selectedAnimalIds))
                            @php
                                $previewAnimals = \App\Models\Animal::whereIn('id', $selectedAnimalIds)->with('media')->get();
                            @endphp
                            @if($previewAnimals->isNotEmpty())
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="fw-bold text-dark mb-2">
                                        <i class="fas fa-paw text-warning me-1"></i> Wyróżnione koty pod artykułem ({{ $previewAnimals->count() }}):
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($previewAnimals as $pAnimal)
                                            <span class="badge bg-warning text-dark py-2 px-3 fs-6">
                                                🐾 {{ $pAnimal->name }} ({{ $pAnimal->breed }})
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <button type="button" wire:click="backToStep(2)" class="btn btn-outline-secondary btn-lg">
                        ← Wstecz
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" wire:click="saveDraft(false)" class="btn btn-primary btn-lg px-4" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveDraft(false)">
                                <i class="fas fa-save me-2"></i> Zapisz jako Szkic
                            </span>
                            <span wire:loading wire:target="saveDraft(false)" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span> Zapisuję...
                            </span>
                        </button>
                        <button type="button" wire:click="saveDraft(true)" class="btn btn-success btn-lg px-4" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveDraft(true)">
                                <i class="fas fa-globe me-2"></i> Zapisz i Opublikuj
                            </span>
                            <span wire:loading wire:target="saveDraft(true)" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2"></span> Publikuję...
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>