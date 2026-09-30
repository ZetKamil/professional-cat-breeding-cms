{{-- AI Blog Studio — 3-step wizard for generating blog posts with Gemini AI --}}
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
    {{-- STEP 1: Breed + Topic --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @if($currentStep === 1)
        <div wire:key="step-1">
            <form wire:submit.prevent="goToStep2">

                {{-- Breed selector --}}
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

                {{-- Topic suggestions --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-lightbulb me-1 text-warning"></i>
                        Sugerowane tematy na dziś <span class="text-muted fw-normal">(na żywo z Google Trends / AI)</span>
                    </label>

                    @if($topicError)
                        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3">
                            <i class="fas fa-exclamation-triangle mt-1"></i>
                            <div>
                                <strong>Problem z pobraniem trendów:</strong> {{ $topicError }}<br>
                                <small class="text-muted">Możesz wpisać własny temat w polu poniżej.</small>
                            </div>
                        </div>
                    @elseif(empty($topics))
                        <div class="alert alert-info py-2 mb-3">
                            <i class="fas fa-spinner fa-spin me-1"></i> Pobieram najnowsze trendy…
                        </div>
                    @else
                        <div class="row g-2">
                            @foreach($topics as $i => $topic)
                                <div class="col-12 col-md-6" wire:key="topic-card-{{ $i }}-{{ $selectedBreed }}">
                                    <button type="button"
                                            wire:click="selectTopic('{{ addslashes($topic['title']) }}')"
                                            id="topic-btn-{{ $i }}"
                                            class="btn w-100 text-start {{ $selectedTopic === $topic['title'] ? 'btn-primary' : 'btn-outline-secondary' }}"
                                            style="white-space: normal; line-height: 1.4; user-select: text;">
                                        <span class="d-block">{{ $topic['title'] }}</span>
                                        <span class="badge {{ $topic['intent'] === 'commercial' ? 'bg-warning text-dark' : 'bg-info' }} mt-1" style="font-size:.65rem;">
                                            {{ $topic['intent'] === 'commercial' ? 'Zakupowy' : 'Edukacyjny' }}
                                        </span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Selected / Custom topic field --}}
                <div class="mb-4">
                    <label for="customTopic" class="form-label fw-semibold">
                        <i class="fas fa-pencil me-1"></i> Temat artykułu <span class="text-muted fw-normal">(wybierz powyżej lub wpisz/edytuj własny)</span>
                    </label>
                    <input type="text"
                           id="customTopic"
                           wire:model="customTopic"
                           class="form-control form-control-lg"
                           placeholder="Wybierz temat z listy powyżej lub wpisz własny...">
                </div>

                {{-- CTA --}}
                <div class="d-grid">
                    <button type="submit"
                            id="step1-next-btn"
                            class="btn btn-primary btn-lg">
                        Dalej: Wybierz koty →
                    </button>
                </div>

            </form>
        </div>

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- STEP 2: Animal Selection --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @elseif($currentStep === 2)
        <div wire:key="step-2">

            <p class="text-muted mb-3">
                Wybierz kocięta lub koty, o których będzie artykuł.
                Ich <strong>prawdziwe zdjęcia z galerii</strong> zostaną automatycznie wstawione do treści.
                Możesz pominąć ten krok i generować artykuł ogólny.
            </p>

            @if(!empty($animals))
                @foreach($animals as $breedName => $group)
                    <h6 class="text-muted text-uppercase small fw-bold mb-2 mt-3">{{ $breedName }}</h6>
                    <div class="row g-2 mb-3">
                        @foreach($group as $animal)
                            @php $isSelected = in_array($animal['id'], $selectedAnimalIds, true); @endphp
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div wire:click="toggleAnimal('{{ $animal['id'] }}')"
                                     id="animal-card-{{ Str::limit($animal['id'], 8, '') }}"
                                     class="card h-100 cursor-pointer border-2 {{ $isSelected ? 'border-primary bg-primary bg-opacity-10' : 'border-light' }}"
                                     style="cursor:pointer; transition: border-color .2s, background .2s;">
                                    @if($animal['photo_url'])
                                        <img src="{{ $animal['photo_url'] }}"
                                             alt="{{ $animal['name'] }}"
                                             class="card-img-top object-fit-cover"
                                             style="height:140px;">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                             style="height:140px;">
                                            <i class="fas fa-paw fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong class="d-block">{{ $animal['name'] }}</strong>
                                                <small class="text-muted">{{ $animal['color'] }} · {{ $animal['age'] ?? '—' }}</small>
                                            </div>
                                            @if($isSelected)
                                                <i class="fas fa-check-circle text-primary fs-5"></i>
                                            @endif
                                        </div>
                                        <span class="badge bg-secondary mt-1" style="font-size:.65rem;">
                                            {{ $animal['status'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Brak opublikowanych kotów w bazie. Artykuł zostanie wygenerowany ogólnie dla rasy
                    <strong>{{ $breeds[$selectedBreed] ?? $selectedBreed }}</strong>.
                </div>
            @endif

            @if(!empty($selectedAnimalIds))
                <div class="alert alert-primary py-2 mt-2">
                    <i class="fas fa-paw me-1"></i>
                    Wybrano {{ count($selectedAnimalIds) }} {{ count($selectedAnimalIds) === 1 ? 'kota/kocię' : 'koty/kocięta' }}.
                    Ich zdjęcia zostaną wstawione do artykułu.
                </div>
            @endif

            <div class="d-grid mt-3">
                <button type="button"
                        wire:click="goToStep3"
                        id="step2-generate-btn"
                        class="btn btn-primary btn-lg"
                        wire:loading.attr="disabled"
                        wire:target="goToStep3">
                    <span wire:loading.remove wire:target="goToStep3">
                        <i class="fas fa-wand-magic-sparkles me-2"></i>
                        Generuj artykuł z AI →
                    </span>
                    <span wire:loading wire:target="goToStep3">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Generuję treść… (15-30 sek.)
                    </span>
                </button>
            </div>
        </div>

    {{-- ════════════════════════════════════════════════════════════════════ --}}
    {{-- STEP 3: Preview & Save Draft --}}
    {{-- ════════════════════════════════════════════════════════════════════ --}}
    @elseif($currentStep === 3)
        <div wire:key="step-3">

            @if($isGenerating)
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status" style="width:3rem;height:3rem;"></div>
                    <p class="text-muted">AI generuje artykuł zgodnie z zasadami copywritingu Katten…</p>
                </div>

            @elseif($generatedDraft)

                {{-- ─── SEO Summary ──────────────────────────────────────── --}}
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase small fw-bold mb-3">
                            <i class="fas fa-chart-bar me-1"></i> Metadane SEO
                        </h6>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">H1 / Tytuł artykułu</label>
                                <div class="fw-semibold fs-5">{{ $generatedDraft['title'] ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Meta Title ({{ strlen($generatedDraft['meta_title'] ?? '') }}/60)</label>
                                <div class="border rounded p-2 bg-white small">{{ $generatedDraft['meta_title'] ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small text-muted mb-1">Meta Description ({{ strlen($generatedDraft['meta_description'] ?? '') }}/155)</label>
                                <div class="border rounded p-2 bg-white small">{{ $generatedDraft['meta_description'] ?? '—' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Excerpt (zajawka)</label>
                                <div class="border rounded p-2 bg-white small fst-italic">{{ $generatedDraft['excerpt'] ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─── Hero Image Info ────────────────────────────────────── --}}
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase small fw-bold mb-2">
                            <i class="fas fa-image me-1"></i> Grafika okładkowa (dekoracyjna AI)
                        </h6>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-magic me-1 text-primary"></i>
                            Dekoracyjna okładka z AI (Gemini Imagen) zostanie wygenerowana automatycznie podczas zapisywania szkicu i dołączona do artykułu.
                            Prawdziwe zdjęcia wybranych kotów z hodowli zostały już wstawione bezpośrednio w treść poniżej.
                        </p>
                    </div>
                </div>

                {{-- ─── Article Body Preview ─────────────────────────────── --}}
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase small fw-bold mb-3">
                            <i class="fas fa-file-alt me-1"></i> Treść artykułu (podgląd)
                        </h6>
                        <div class="border rounded p-3 bg-white"
                             style="max-height:450px; overflow-y:auto; line-height:1.7;">
                            {!! $generatedDraft['body'] ?? '' !!}
                        </div>
                    </div>
                </div>

                {{-- ─── Safety Warning ──────────────────────────────────── --}}
                <div class="alert alert-warning d-flex align-items-start">
                    <i class="fas fa-eye me-2 mt-1"></i>
                    <div>
                        <strong>Przed zapisem przejrzyj treść.</strong><br>
                        Artykuł zostanie zapisany jako <span class="badge bg-secondary">Szkic</span> — nie zostanie opublikowany automatycznie.
                        Możesz go edytować w panelu Posts przed kliknięciem "Opublikuj".
                    </div>
                </div>

                {{-- ─── Actions ─────────────────────────────────────────── --}}
                <div class="d-flex gap-2">
                    <button type="button"
                            wire:click="saveDraft"
                            id="save-draft-btn"
                            class="btn btn-success btn-lg flex-grow-1"
                            wire:loading.attr="disabled"
                            wire:target="saveDraft">
                        <span wire:loading.remove wire:target="saveDraft">
                            <i class="fas fa-save me-2"></i> Zapisz jako szkic
                        </span>
                        <span wire:loading wire:target="saveDraft">
                            <span class="spinner-border spinner-border-sm"></span> Zapisuję…
                        </span>
                    </button>

                    <button type="button"
                            wire:click="backToStep(1)"
                            class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-rotate-left me-1"></i> Zacznij od nowa
                    </button>
                </div>

            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                    <p>Brak wygenerowanej treści. Wróć i spróbuj ponownie.</p>
                    <button type="button" wire:click="backToStep(1)" class="btn btn-outline-primary">
                        ← Wróć do kroku 1
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>