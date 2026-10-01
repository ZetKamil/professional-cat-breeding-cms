{{-- AI Blog Studio — 3-step wizard with AI Agent & MCP Tools Integration --}}
<div @if($isAgentRunning) wire:poll.1s="checkAgentStatus" @endif>

    {{-- ─── Progress Bar ────────────────────────────────────────────────── --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold text-muted small">
                Krok {{ $currentStep }} z 3 —
                @if($currentStep === 1) Wybierz rasę i uruchom Agenta AI
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
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i>
            {{ $errorMessage }}
        </div>
    @endif

    @if($successMessage)
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {!! $successMessage !!}
            &nbsp;<a href="{{ route('backend.posts.index') }}" class="alert-link ms-1">Przejdź do listy postów →</a>
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
                                    class="btn w-100 {{ $selectedBreed === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ $label }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ─── AI Agent & MCP Log Console ──────────────────────────── --}}
            @if(!empty($agentLogs) || $isAgentRunning)
                <div class="card bg-dark text-light border-secondary mb-4 shadow-sm" style="border-radius: 8px;">
                    <div class="card-header bg-black text-info d-flex align-items-center justify-content-between py-2 border-bottom border-secondary">
                        <span class="fw-bold font-monospace small">
                            <i class="fas fa-microchip me-2 text-warning"></i> AI Agent Console (MCP Tools Runtime)
                        </span>
                        @if($isAgentRunning)
                            <span class="badge bg-warning text-dark spinner-border spinner-border-sm" style="width:0.8rem; height:0.8rem;"></span>
                        @else
                            <span class="badge bg-success" style="font-size: 0.65rem;">Agent Ready</span>
                        @endif
                    </div>
                    <div class="card-body p-3 font-monospace small" style="max-height: 180px; overflow-y: auto; background-color: #121212;">
                        @foreach($agentLogs as $log)
                            <div class="text-light opacity-90 py-1 border-bottom border-secondary border-opacity-25" style="font-size: 0.82rem;">
                                <span class="text-success fw-bold">❯</span> {{ $log }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ─── Topic suggestions ──────────────────────────────────── --}}
            <div class="mb-4">

                @if(! $topicsLoaded && ! $isAgentRunning)
                    {{-- === STAN POCZĄTKOWY: Uruchomienie Agenta === --}}
                    <div class="text-center py-4 border rounded bg-light">
                        <i class="fas fa-robot fa-2x text-primary mb-3 d-block"></i>
                        <p class="text-muted mb-3">
                            Uruchom Agenta AI, aby wykorzystał narzędzia MCP (Google Trends + Baza Hodowli)<br>
                            i wygenerował sugerowane tematy dla rasy <strong>{{ $breeds[$selectedBreed] ?? $selectedBreed }}</strong>.
                        </p>
                        <button type="button"
                                wire:click="fetchTopics"
                                wire:loading.attr="disabled"
                                id="fetch-topics-btn"
                                class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="fas fa-play me-2"></i> Uruchom Agenta AI (MCP Tools)
                        </button>
                    </div>

                @elseif($isAgentRunning)
                    {{-- === AGENT W TRAKCIE PRACY === --}}
                    <div class="text-center py-4 border rounded bg-light">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
                        <h5 class="fw-semibold text-primary mb-1">Agent AI przetwarza dane w tle...</h5>
                        <p class="text-muted small mb-0">Wykonywanie narzędzi MCP i analiza trendów dla {{ $breeds[$selectedBreed] ?? $selectedBreed }}</p>
                    </div>

                @else
                    {{-- === TEMATY ZAŁADOWANE (lub błąd AI) === --}}

                    @if($topicError)
                        <div class="alert alert-warning d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-triangle-exclamation fa-lg mt-1 text-warning flex-shrink-0"></i>
                            <div>
                                <strong>Status Agenta AI: Odmowa / Błąd niedostępności</strong><br>
                                <span class="text-muted small">{{ $topicError }}</span>
                            </div>
                            <button type="button"
                                    wire:click="fetchTopics"
                                    class="btn btn-sm btn-outline-warning ms-auto flex-shrink-0">
                                <i class="fas fa-rotate me-1"></i>Ponów próbę Agenta
                            </button>
                        </div>

                    @else
                        {{-- Tematy załadowane poprawnie --}}
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label fw-semibold mb-0">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                Sugerowane tematy od Agenta AI
                                @if($topicSource === 'cache')
                                    <span class="badge bg-secondary text-white ms-1 fw-normal">
                                        <i class="fas fa-database me-1"></i>MCP Cache
                                    </span>
                                @else
                                    <span class="badge bg-success text-white ms-1 fw-normal">
                                        <i class="fas fa-brain me-1"></i>Na żywo z MCP Tools & Gemini
                                    </span>
                                @endif
                            </label>

                            <button type="button"
                                    wire:click="refreshTopics"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Wymuś powtórzenie analizy przez Agenta">
                                <i class="fas fa-rotate me-1"></i>Ponów analizę Agenta
                            </button>
                        </div>

                        <div class="row g-2">
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
                @endif
            </div>

            {{-- Selected / Custom topic field --}}
            <div class="mb-4">
                <label for="customTopic" class="form-label fw-semibold">
                    <i class="fas fa-pencil me-1"></i> Temat artykułu <span class="text-muted fw-normal">(wybierz powyżej lub wpisz własny)</span>
                </label>
                <input type="text"
                       id="customTopic"
                       wire:model.live.debounce.150ms="customTopic"
                       wire:keydown.enter.prevent="goToStep2"
                       class="form-control form-control-lg"
                       placeholder="Wybierz temat z listy powyżej lub wpisz własny...">
            </div>

            {{-- CTA --}}
            <div class="d-grid">
                <button type="button"
                        wire:click="goToStep2"
                        wire:loading.attr="disabled"
                        id="step1-next-btn"
                        class="btn btn-primary btn-lg">
                    Dalej: Wybierz koty →
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
                $currentAnimals = $animals[$breedLabel] ?? [];
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
                <button type="button" wire:click="goToStep3" class="btn btn-primary btn-lg px-5">
                    Generuj artykuł z AI →
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
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">{{ $generatedDraft['title'] ?? 'Szkic wpisu' }}</h5>
                        <span class="badge bg-warning text-dark">SZKIC (Niepublikowany)</span>
                    </div>
                    <div class="card-body">
                        <p class="lead text-muted">{{ $generatedDraft['excerpt'] ?? '' }}</p>
                        <hr>
                        <div class="article-body">
                            {!! $generatedDraft['body'] ?? '' !!}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" wire:click="backToStep(2)" class="btn btn-outline-secondary btn-lg">
                        ← Wstecz
                    </button>
                    <button type="button" wire:click="saveDraft" class="btn btn-success btn-lg px-5" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveDraft">
                            <i class="fas fa-save me-2"></i> Zapisz jako Szkic w CMS
                        </span>
                        <span wire:loading wire:target="saveDraft">
                            <span class="spinner-border spinner-border-sm me-2"></span> Zapisuję...
                        </span>
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>