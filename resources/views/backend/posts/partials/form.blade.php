@php
    /**
     * Structured Article Editor
     *
     * Shared between create and edit.
     * - Sections: array of { heading, body, image_url }
     * - Featured animals: checkboxes per post
     * - old() wins after validation errors
     * - $post can be null on create
     */

    // Resolve existing sections from DB or old() after validation error
    $existingSectionsJson = old('sections_json');
    if ($existingSectionsJson === null && isset($post) && $post?->sections) {
        $existingSectionsJson = json_encode($post->sections, JSON_UNESCAPED_UNICODE);
    }
    if (!$existingSectionsJson) {
        // Default skeleton with 3 sections
        $existingSectionsJson = json_encode([
            ['heading' => '', 'body' => '', 'image_url' => ''],
            ['heading' => '', 'body' => '', 'image_url' => ''],
            ['heading' => '', 'body' => '', 'image_url' => ''],
        ], JSON_UNESCAPED_UNICODE);
    }

    // Currently selected animal IDs for this post
    $selectedAnimalIds = old('featured_animal_ids', isset($post) ? $post->animals->pluck('id')->all() : []);
@endphp

<div class="row g-3">

    {{-- ========================= TITLE ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Tytuł (H1) <span class="text-danger">*</span></label>
        <input
            type="text"
            name="title"
            id="post_title_input"
            value="{{ old('title', $post?->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="Np. Pielęgnacja sierści kota bengalskiego jesienią"
        >
        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= SLUG ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Slug</label>
        <input
            type="text"
            name="slug"
            id="post_slug_input"
            value="{{ old('slug', $post?->slug ?? '') }}"
            class="form-control @error('slug') is-invalid @enderror"
            placeholder="pielegnacja-sierci-kota-bengalskiego"
        >
        @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Zostaw puste, aby wygenerować automatycznie z tytułu.</div>
    </div>

    {{-- ========================= AUTHOR ========================= --}}
    <div class="col-12 col-md-6">
        <label class="form-label">Autor</label>
        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
            <option value="">Brak autora</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}"
                    @selected((string) old('user_id', $post?->user_id ?? '') === (string) $author->id)>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= STATUS ========================= --}}
    @php
        $postStatus = old('is_published');
        if ($postStatus === null && isset($post)) {
            $postStatus = $post->is_published ? '1' : '0';
        }
        $postStatus = (string) ($postStatus ?? '0');
    @endphp
    <div class="col-12 col-md-3">
        <label class="form-label">Status</label>
        <select name="is_published" class="form-select @error('is_published') is-invalid @enderror">
            <option value="1" @selected($postStatus === '1')>Opublikowany</option>
            <option value="0" @selected($postStatus === '0')>Szkic (Draft)</option>
        </select>
        @error('is_published')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= PUBLISHED AT ========================= --}}
    <div class="col-12 col-md-3">
        <label class="form-label">Data publikacji</label>
        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', optional($post?->published_at)->format('Y-m-d\TH:i')) }}"
            class="form-control @error('published_at') is-invalid @enderror"
        >
        @error('published_at')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Puste = bieżąca chwila przy publikacji.</div>
    </div>

    {{-- ========================= EXCERPT / LEAD ========================= --}}
    <div class="col-12">
        <label class="form-label">Wstęp / Lead <small class="text-muted">(2–3 zdania widoczne pod tytułem i w listach)</small></label>
        <textarea
            name="excerpt"
            rows="3"
            class="form-control @error('excerpt') is-invalid @enderror"
            placeholder="Krótkie, zachęcające wprowadzenie do artykułu..."
        >{{ old('excerpt', $post?->excerpt ?? '') }}</textarea>
        @error('excerpt')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= CATEGORIES ========================= --}}
    <div class="col-12">
        <label class="form-label d-block">Kategorie</label>
        @php
            $selectedCategories = old(
                'categories',
                isset($post) && $post
                    ? $post->categories->pluck('id')->map(fn ($id) => (string) $id)->all()
                    : []
            );
        @endphp
        <div class="row g-2">
            @foreach($categories as $category)
                <div class="col-12 col-md-4">
                    <div class="form-check">
                        <input
                            class="form-check-input @error('categories') is-invalid @enderror"
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            id="category_{{ $category->id }}"
                            @checked(in_array((string) $category->id, array_map('strval', $selectedCategories), true))
                        >
                        <label class="form-check-label" for="category_{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('categories')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- ========================= FEATURED IMAGE ========================= --}}
    <div class="col-12">
        <label class="form-label">Zdjęcie okładkowe (Featured Image)</label>
        <input
            type="file"
            name="image"
            class="form-control @error('image') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.webp"
        >
        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Dozwolone formaty: jpg, jpeg, png, webp (max 4MB)</div>

        @if($post?->media)
            <div class="mt-3">
                <div class="small text-muted mb-2">Aktualne zdjęcie okładkowe</div>
                <img
                    src="{{ $post->media->url() }}"
                    class="img-thumbnail"
                    style="max-width:200px;"
                    alt="Okładka artykułu"
                >
            </div>
        @endif
    </div>

    {{-- ===============================================================
         STRUCTURED ARTICLE EDITOR (Sekcje artykułu)
         =============================================================== --}}
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <span>
                    <i class="fas fa-newspaper me-2"></i>
                    Treść artykułu — Sekcje (szablon editorialny)
                </span>
                <span class="badge bg-white text-primary ms-2" id="sections_count_badge">0 sekcji</span>
            </div>
            <div class="card-body p-0">

                {{-- Help bar --}}
                <div class="alert alert-info mb-0 rounded-0 border-0 border-bottom py-2 px-3 small">
                    <i class="fas fa-info-circle me-1"></i>
                    Każda sekcja składa się z opcjonalnego <strong>podtytułu H2</strong>, <strong>tekstu akapitu</strong>
                    i opcjonalnego <strong>zdjęcia</strong> (wybieranego z biblioteki mediów).
                    Sekcje możesz przeciągać, dodawać i usuwać. Strona sama złoży je w luksusowy layout.
                </div>

                {{-- Sections list --}}
                <div id="sections_editor" class="p-3">
                    {{-- Sections are rendered by JS from existingSectionsJson --}}
                </div>

                {{-- Add section button --}}
                <div class="p-3 pt-0 border-top">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add_section_btn">
                        <i class="fas fa-plus me-1"></i> Dodaj sekcję
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="preview_sections_btn">
                        <i class="fas fa-eye me-1"></i> Podgląd struktury
                    </button>
                </div>
            </div>
        </div>

        {{-- Hidden input carrying the sections JSON to the server --}}
        <input type="hidden" name="sections_json" id="sections_json_input" value="{{ old('sections_json', $existingSectionsJson) }}">
    </div>

    {{-- ===============================================================
         WYRÓŻNIONE KOTY POD ARTYKUŁEM
         =============================================================== --}}
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark d-flex align-items-center gap-2">
                <i class="fas fa-paw"></i>
                <span>Koty wyróżnione pod artykułem</span>
                <small class="ms-auto text-muted fw-normal">Pojawią się jako karty z linkami przed sekcją „Przeczytaj też"</small>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">
                    Zaznacz koty z hodowli, które chcesz zaprezentować czytelnikowi po przeczytaniu tego artykułu.
                    Klientka klika imię kota — przechodzi prosto do jego profilu.
                </p>

                @if($animals->isEmpty())
                    <div class="alert alert-warning py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Brak opublikowanych kotów w hodowli. Dodaj koty w sekcji <a href="{{ route('backend.animals.index') }}">Animals</a>.
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($animals as $animal)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <label
                                    class="animal-selector-card {{ in_array($animal->id, $selectedAnimalIds) ? 'selected' : '' }}"
                                    for="animal_{{ $animal->id }}"
                                    style="cursor:pointer; display:block; border:2px solid {{ in_array($animal->id, $selectedAnimalIds) ? '#f59e0b' : '#dee2e6' }}; border-radius:10px; overflow:hidden; transition: all 0.15s;"
                                >
                                    <input
                                        type="checkbox"
                                        name="featured_animal_ids[]"
                                        value="{{ $animal->id }}"
                                        id="animal_{{ $animal->id }}"
                                        class="d-none animal-selector-checkbox"
                                        @checked(in_array($animal->id, $selectedAnimalIds))
                                    >
                                    @if($animal->media)
                                        <img
                                            src="{{ $animal->media->url() }}"
                                            alt="{{ $animal->name }}"
                                            class="w-100"
                                            style="height:110px; object-fit:cover;"
                                        >
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:110px;">
                                            <i class="fas fa-cat fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="p-2">
                                        <div class="fw-semibold small">{{ $animal->name }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">{{ $animal->breed }}</div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========================= ACTIONS ========================= --}}
    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit" id="post_save_btn">
            <i class="fas fa-save me-1"></i>
            {{ $submitLabel ?? 'Zapisz' }}
        </button>

        <a class="btn btn-outline-secondary" href="{{ route('backend.posts.index') }}">
            Anuluj
        </a>
    </div>

</div>

{{-- ===============================================================
     MEDIA PICKER MODAL (for sections images)
     =============================================================== --}}
<div class="modal fade" id="sectionMediaPickerModal" tabindex="-1" aria-labelledby="sectionMediaPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="sectionMediaPickerModalLabel">
                    <i class="fas fa-images me-2 text-primary"></i> Wybierz zdjęcie z biblioteki
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-2 mb-3 pb-2 border-bottom">
                    <div class="col-12 col-md-5">
                        <input type="text" class="form-control form-control-sm" id="smpSearch" placeholder="Szukaj po nazwie...">
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm" id="smpType">
                            <option value="">Wszystkie typy</option>
                            <option value="animal">Koty</option>
                            <option value="post">Posty</option>
                            <option value="unattached">Bez przypisania</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-secondary" id="smpRefresh">
                            <i class="fas fa-sync-alt me-1"></i> Odśwież
                        </button>
                        <a href="{{ route('backend.media.create') }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus me-1"></i> Prześlij nowe
                        </a>
                    </div>
                </div>
                <div id="smpGrid" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Ładowanie biblioteki mediów...</div>
                    </div>
                </div>
                <div id="smpPagination" class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top small text-muted"></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

{{-- ===============================================================
     SECTIONS PREVIEW MODAL
     =============================================================== --}}
<div class="modal fade" id="sectionsPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Podgląd struktury artykułu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="sections_preview_body" style="font-family: Georgia, serif; line-height: 1.8;">
            </div>
        </div>
    </div>
</div>

<style>
/* Animal selector card interactive states */
.animal-selector-card:hover {
    border-color: #f59e0b !important;
    box-shadow: 0 2px 8px rgba(245,158,11,0.25);
}
.animal-selector-card.selected {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 3px rgba(245,158,11,0.3);
}

/* Section card */
.section-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    background: #fff;
    margin-bottom: 1rem;
    transition: box-shadow 0.15s;
}
.section-card:hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.section-card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0;
    padding: 0.6rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: grab;
    user-select: none;
}
.section-card-header:active { cursor: grabbing; }
.section-card-body { padding: 1rem; }
.section-card-image-preview {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── State ────────────────────────────────────────────────────────
    let sections = [];
    try {
        const raw = document.getElementById('sections_json_input').value;
        sections = JSON.parse(raw) || [];
    } catch (e) {
        sections = [{ heading: '', body: '', image_url: '' }];
    }

    // ── Render ───────────────────────────────────────────────────────
    const editor = document.getElementById('sections_editor');
    const badge = document.getElementById('sections_count_badge');
    const jsonInput = document.getElementById('sections_json_input');

    function renderSections() {
        editor.innerHTML = '';
        sections.forEach((sec, idx) => {
            editor.appendChild(buildSectionCard(sec, idx));
        });
        badge.textContent = sections.length + ' ' + (sections.length === 1 ? 'sekcja' : sections.length < 5 ? 'sekcje' : 'sekcji');
        saveToInput();
    }

    function buildSectionCard(sec, idx) {
        const card = document.createElement('div');
        card.className = 'section-card';
        card.dataset.idx = idx;

        const isIntro = idx === 0;
        const headingLabel = isIntro
            ? '<i class="fas fa-paragraph text-primary me-1"></i> Sekcja wprowadzająca (intro)'
            : `<i class="fas fa-heading text-secondary me-1"></i> Sekcja ${idx + 1}`;

        card.innerHTML = `
            <div class="section-card-header">
                <span class="drag-handle text-muted me-1" title="Przeciągnij aby zmienić kolejność">
                    <i class="fas fa-grip-vertical"></i>
                </span>
                ${headingLabel}
                <span class="ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger section-remove-btn" data-idx="${idx}" title="Usuń sekcję">
                        <i class="fas fa-trash"></i>
                    </button>
                </span>
            </div>
            <div class="section-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        ${isIntro ? '' : `
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Podtytuł H2 <span class="text-muted fw-normal">(opcjonalny)</span></label>
                            <input
                                type="text"
                                class="form-control section-heading"
                                data-idx="${idx}"
                                value="${escHtml(sec.heading || '')}"
                                placeholder="Np. Jak dbać o sierść kota bengalskiego?"
                            >
                        </div>
                        `}
                        <div>
                            <label class="form-label small fw-semibold">
                                Tekst akapitu <span class="text-danger">*</span>
                            </label>
                            <textarea
                                class="form-control section-body"
                                data-idx="${idx}"
                                rows="5"
                                placeholder="${isIntro ? 'Wprowadź tekst wstępny artykułu...' : 'Treść tej sekcji...'}"
                            >${escHtml(sec.body || '')}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold">Zdjęcie sekcji <span class="text-muted fw-normal">(opcjonalne)</span></label>
                        <div class="section-image-area" data-idx="${idx}">
                            ${sec.image_url
                                ? `<img src="${escHtml(sec.image_url)}" class="section-card-image-preview mb-2" alt="Zdjęcie sekcji">`
                                : `<div class="d-flex align-items-center justify-content-center bg-light border rounded mb-2" style="height:120px;"><i class="fas fa-image fa-2x text-muted"></i></div>`
                            }
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary section-pick-image flex-grow-1" data-idx="${idx}">
                                    <i class="fas fa-folder-open me-1"></i>
                                    ${sec.image_url ? 'Zmień' : 'Wybierz zdjęcie'}
                                </button>
                                ${sec.image_url
                                    ? `<button type="button" class="btn btn-sm btn-outline-danger section-remove-image" data-idx="${idx}" title="Usuń zdjęcie"><i class="fas fa-times"></i></button>`
                                    : ''
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove section
        card.querySelector('.section-remove-btn')?.addEventListener('click', function () {
            if (sections.length > 1 && confirm('Usunąć tę sekcję?')) {
                sections.splice(idx, 1);
                renderSections();
            }
        });

        // Heading input
        card.querySelector('.section-heading')?.addEventListener('input', function () {
            sections[idx].heading = this.value;
            saveToInput();
        });

        // Body textarea
        card.querySelector('.section-body')?.addEventListener('input', function () {
            sections[idx].body = this.value;
            saveToInput();
        });

        // Pick image from library
        card.querySelector('.section-pick-image')?.addEventListener('click', function () {
            openSectionMediaPicker(idx);
        });

        // Remove image
        card.querySelector('.section-remove-image')?.addEventListener('click', function () {
            sections[idx].image_url = '';
            renderSections();
        });

        return card;
    }

    function saveToInput() {
        jsonInput.value = JSON.stringify(sections);
        badge.textContent = sections.length + ' ' + (sections.length === 1 ? 'sekcja' : sections.length < 5 ? 'sekcje' : 'sekcji');
    }

    // ── Add section ──────────────────────────────────────────────────
    document.getElementById('add_section_btn').addEventListener('click', function () {
        sections.push({ heading: '', body: '', image_url: '' });
        renderSections();
        // Scroll to new section
        editor.lastElementChild?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // ── Preview ──────────────────────────────────────────────────────
    document.getElementById('preview_sections_btn').addEventListener('click', function () {
        const titleEl = document.getElementById('post_title_input');
        let html = '<h1 style="font-size:1.8rem;margin-bottom:0.5rem;">' + escHtml(titleEl?.value || '(bez tytułu)') + '</h1>';
        html += '<hr>';
        sections.forEach((s, i) => {
            if (i === 0) {
                if (s.body) html += '<p style="font-size:1.05rem;color:#555;">' + escHtml(s.body).replace(/\n/g, '<br>') + '</p>';
            } else {
                if (s.heading) html += '<h2 style="font-size:1.4rem;margin-top:1.5rem;">' + escHtml(s.heading) + '</h2>';
                if (s.body) html += '<p>' + escHtml(s.body).replace(/\n/g, '<br>') + '</p>';
            }
            if (s.image_url) {
                html += `<figure style="margin:1rem 0;"><img src="${escHtml(s.image_url)}" style="max-width:100%;border-radius:8px;" alt=""><figcaption style="font-size:0.8rem;color:#888;text-align:center;">Zdjęcie sekcji ${i + 1}</figcaption></figure>`;
            }
        });
        document.getElementById('sections_preview_body').innerHTML = html;
        new bootstrap.Modal(document.getElementById('sectionsPreviewModal')).show();
    });

    // ── Animal selectors ─────────────────────────────────────────────
    document.querySelectorAll('.animal-selector-card').forEach(function (card) {
        card.addEventListener('click', function () {
            const cb = card.querySelector('.animal-selector-checkbox');
            if (!cb) return;
            cb.checked = !cb.checked;
            card.classList.toggle('selected', cb.checked);
            card.style.borderColor = cb.checked ? '#f59e0b' : '#dee2e6';
        });
    });

    // ── Section Media Picker ─────────────────────────────────────────
    const smpModal = document.getElementById('sectionMediaPickerModal');
    const smpGrid = document.getElementById('smpGrid');
    const smpSearch = document.getElementById('smpSearch');
    const smpType = document.getElementById('smpType');
    const smpPagination = document.getElementById('smpPagination');
    let smpTargetIdx = null;
    let smpCurrentPage = 1;
    let smpSearchTimeout;

    function openSectionMediaPicker(idx) {
        smpTargetIdx = idx;
        loadSmpMedia(1);
        new bootstrap.Modal(smpModal).show();
    }

    smpModal?.addEventListener('show.bs.modal', function () {
        loadSmpMedia(1);
    });

    document.getElementById('smpRefresh')?.addEventListener('click', () => loadSmpMedia(1));

    smpSearch?.addEventListener('input', function () {
        clearTimeout(smpSearchTimeout);
        smpSearchTimeout = setTimeout(() => loadSmpMedia(1), 300);
    });
    smpType?.addEventListener('change', () => loadSmpMedia(1));

    function loadSmpMedia(page) {
        smpCurrentPage = page;
        const q = smpSearch?.value || '';
        const type = smpType?.value || '';
        const url = `{{ route('backend.media.api') }}?page=${page}&q=${encodeURIComponent(q)}&type=${encodeURIComponent(type)}`;

        smpGrid.innerHTML = '<div class="col-12 text-center py-5 text-muted"><i class="fas fa-spinner fa-spin fa-2x mb-2"></i><div>Ładowanie...</div></div>';

        fetch(url)
            .then(r => r.json())
            .then(data => {
                renderSmpGrid(data.data || []);
                renderSmpPagination(data.meta || {});
            })
            .catch(() => {
                smpGrid.innerHTML = '<div class="col-12 text-center py-4 text-danger"><i class="fas fa-exclamation-triangle"></i> Błąd ładowania mediów.</div>';
            });
    }

    function renderSmpGrid(items) {
        if (!items.length) {
            smpGrid.innerHTML = '<div class="col-12 text-center py-4 text-muted">Brak mediów.</div>';
            return;
        }
        smpGrid.innerHTML = '';
        items.filter(i => i.is_image).forEach(item => {
            const col = document.createElement('div');
            col.className = 'col';
            col.innerHTML = `
                <div class="card h-100 border p-1 text-center shadow-sm" style="cursor:pointer;" data-url="${escHtml(item.url)}" data-id="${item.id}" role="button" tabindex="0">
                    <img src="${escHtml(item.url)}" alt="${escHtml(item.title)}" class="card-img-top rounded" style="height:96px;object-fit:cover;">
                    <div class="card-body p-1">
                        <div class="small fw-semibold text-truncate">${escHtml(item.title)}</div>
                    </div>
                </div>
            `;
            const card = col.querySelector('.card');
            const pick = () => {
                if (smpTargetIdx !== null) {
                    sections[smpTargetIdx].image_url = item.url;
                    renderSections();
                    bootstrap.Modal.getInstance(smpModal)?.hide();
                    smpTargetIdx = null;
                }
            };
            card.addEventListener('click', pick);
            card.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pick(); } });
            smpGrid.appendChild(col);
        });
    }

    function renderSmpPagination(meta) {
        const total = meta.last_page || 1;
        const current = meta.current_page || 1;
        if (total <= 1) { smpPagination.innerHTML = '<span>Wszystkie wyniki</span>'; return; }
        smpPagination.innerHTML = `
            <span>Strona ${current} z ${total}</span>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-secondary ${current === 1 ? 'disabled' : ''}" id="smpPrev">Poprzednia</button>
                <button type="button" class="btn btn-outline-secondary ${current === total ? 'disabled' : ''}" id="smpNext">Następna</button>
            </div>
        `;
        if (current > 1) document.getElementById('smpPrev')?.addEventListener('click', () => loadSmpMedia(current - 1));
        if (current < total) document.getElementById('smpNext')?.addEventListener('click', () => loadSmpMedia(current + 1));
    }

    // ── Helpers ──────────────────────────────────────────────────────
    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Init ─────────────────────────────────────────────────────────
    renderSections();
});
</script>
