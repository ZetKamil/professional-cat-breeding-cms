<x-backend.shell title="Oferta pod artykułami (Global CTA) - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .cta-preview-card {
                background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(255, 255, 255, 0.96) 45%, rgba(245, 238, 220, 0.4) 100%);
                border: 2px solid rgba(212, 175, 55, 0.35);
                border-left: 6px solid #c59b27;
                border-radius: 16px;
                padding: 1.75rem;
                box-shadow: 0 10px 25px rgba(197, 155, 39, 0.08);
            }
            .cta-preview-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 12px;
                background: #fff;
                border: 1px solid rgba(212, 175, 55, 0.4);
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                color: #946c00;
                margin-bottom: 1rem;
            }
            .cta-preview-title {
                font-size: 1.35rem;
                font-weight: 700;
                color: #1d1d1f;
                margin-bottom: 1rem;
            }
            .cta-preview-body {
                font-size: 0.95rem;
                line-height: 1.7;
                color: #333336;
            }
            .cta-preview-body ul {
                margin: 0.75rem 0;
                padding-left: 1.5rem;
            }
            .cta-preview-body p {
                margin-bottom: 0.75rem;
            }
            .cta-preview-img {
                width: 100%;
                max-height: 240px;
                object-fit: cover;
                border-radius: 10px;
                margin-top: 1rem;
                border: 1px solid rgba(0,0,0,0.1);
            }
        </style>
    </x-slot:head>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="fas fa-bullhorn text-warning me-2"></i>Oferta hodowli pod artykułami (Global CTA)
            </h1>
            <p class="text-muted mb-0 small">
                Zarządzaj wspólnym boksem z ofertą kociąt, który automatycznie wyświetla się na dole każdego artykułu na blogu.
            </p>
        </div>
        <div>
            <a href="{{ route('backend.posts.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Wróć do artykułów
            </a>
            <a href="{{ route('frontend.blog.index') }}" target="_blank" class="btn btn-outline-primary btn-sm ms-2">
                <i class="fas fa-external-link-alt me-1"></i> Zobacz blog
            </a>
        </div>
    </div>

    {{-- Info banner --}}
    <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 d-flex align-items-center gap-3 mb-4">
        <i class="fas fa-lightbulb fa-2x text-warning flex-shrink-0"></i>
        <div>
            <strong>Jak to działa?</strong>
            Gdy urodzą się nowe kociaki (brytyjskie lub bengalskie), zmieni się ich dostępność lub status rezerwacji,
            <strong>wystarczy zaktualizować ten jeden formularz</strong>.
            Nowa treść natychmiast pojawi się pod wszystkimi wpisami na blogu — nawet tymi napisanymi rok czy dwa lata temu!
        </div>
    </div>

    <form method="POST" action="{{ route('backend.blog-cta.update') }}">
        @csrf

        <div class="row g-4">
            {{-- Left column: Form inputs --}}
            <div class="col-12 col-xl-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <span class="fw-bold"><i class="fas fa-edit me-1 text-primary"></i> Parametry oferty</span>
                        <div class="form-check form-switch mb-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_enabled"
                                name="is_enabled"
                                value="1"
                                @checked(old('is_enabled', $cta['is_enabled'] ?? true))
                            >
                            <label class="form-check-label small fw-semibold" for="is_enabled">
                                Wyświetlaj boks pod artykułami
                            </label>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- Badge text --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold" for="badge_input">
                                Etykieta / Odznaka nad nagłówkiem
                            </label>
                            <input
                                type="text"
                                name="badge"
                                id="badge_input"
                                class="form-control @error('badge') is-invalid @enderror"
                                value="{{ old('badge', $cta['badge'] ?? 'Hodowla Kotów z Mazowieckiej Szwajcarii') }}"
                                placeholder="Np. Hodowla Kotów z Mazowieckiej Szwajcarii"
                            >
                            @error('badge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Heading --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold" for="heading_input">
                                Nagłówek sekcji (H2) <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="heading"
                                id="heading_input"
                                class="form-control fw-semibold @error('heading') is-invalid @enderror"
                                value="{{ old('heading', $cta['heading'] ?? '') }}"
                                placeholder="Np. 🐾 Dostępne kocięta brytyjskie i bengalskie w naszej hodowli"
                                required
                            >
                            @error('heading')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Body --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold" for="body_input">
                                Treść oferty i kontakt <span class="text-danger">*</span>
                            </label>
                            <textarea
                                name="body"
                                id="body_input"
                                class="form-control font-monospace @error('body') is-invalid @enderror"
                                rows="10"
                                placeholder="Wpisz treść aktualnej oferty, dostępne maluchy (brytyjczyki, bengale), telefon, zaproszenie do hodowli..."
                                required
                            >{{ old('body', $cta['body'] ?? '') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">
                                Obsługuje formatowanie Markdown: <code>**pogrubienie**</code>, wypunktowania (<code>- punkt</code>), linki (<code>[tekst](/koty)</code>).
                            </div>
                        </div>

                        {{-- Image URL & Media Picker --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Zdjęcie oferty <span class="text-muted fw-normal">(opcjonalne)</span></label>
                            <input type="hidden" name="image_url" id="image_url_input" value="{{ old('image_url', $cta['image_url'] ?? '') }}">

                            <div class="d-flex align-items-center gap-3">
                                <div id="image_preview_box" class="flex-shrink-0" style="width:140px;height:90px;">
                                    @if(!empty($cta['image_url']))
                                        <img src="{{ $cta['image_url'] }}" id="preview_thumb" class="rounded border w-100 h-100" style="object-fit:cover;" alt="Podgląd">
                                    @else
                                        <div id="preview_placeholder" class="rounded border bg-light d-flex align-items-center justify-content-center w-100 h-100 text-muted">
                                            <i class="fas fa-image fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <button type="button" class="btn btn-outline-primary btn-sm mb-1" id="open_media_btn">
                                        <i class="fas fa-folder-open me-1"></i> Wybierz z biblioteki mediów
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm mb-1 ms-1 {{ empty($cta['image_url']) ? 'd-none' : '' }}" id="remove_image_btn">
                                        <i class="fas fa-trash me-1"></i> Usuń
                                    </button>
                                    <div class="small text-muted">Zalecany format: poziomy (16:9), zdjęcie aktualnych kociąt lub hodowli.</div>
                                </div>
                            </div>
                        </div>

                        {{-- Action button (Optional) --}}
                        <div class="row g-2 pt-2 border-top">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Tekst przycisku (opcjonalny)</label>
                                <input
                                    type="text"
                                    name="button_text"
                                    id="button_text_input"
                                    class="form-control form-control-sm"
                                    value="{{ old('button_text', $cta['button_text'] ?? 'Zobacz dostępne koty') }}"
                                    placeholder="Np. Zobacz dostępne koty"
                                >
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">Link przycisku</label>
                                <input
                                    type="text"
                                    name="button_url"
                                    id="button_url_input"
                                    class="form-control form-control-sm"
                                    value="{{ old('button_url', $cta['button_url'] ?? '/koty') }}"
                                    placeholder="Np. /koty lub /contact"
                                >
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Wszystkie zmiany zapisywane są natychmiastowo w bazie danych.</span>
                        <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                            <i class="fas fa-save me-1"></i> Zapisz i zaktualizuj pod wszystkimi artykułami
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right column: Live Preview --}}
            <div class="col-12 col-xl-5">
                <div class="sticky-top" style="top: 80px; z-index: 10;">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark"><i class="fas fa-eye me-1 text-warning"></i> Podgląd na żywo (jak widzi czytelnik)</span>
                            <span class="badge bg-success" id="preview_status_badge">Aktywny</span>
                        </div>
                        <div class="card-body bg-light p-3">
                            <div class="cta-preview-card" id="live_preview_card">
                                <div class="cta-preview-badge" id="preview_badge">
                                    <span>🐾</span>
                                    <span id="preview_badge_text">{{ $cta['badge'] ?? 'Hodowla Kotów z Mazowieckiej Szwajcarii' }}</span>
                                </div>
                                <h2 class="cta-preview-title" id="preview_title">
                                    {{ $cta['heading'] ?? '🐾 Dostępne kocięta w naszej hodowli' }}
                                </h2>
                                <div class="cta-preview-body" id="preview_body">
                                </div>
                                <div id="preview_img_wrapper" class="{{ empty($cta['image_url']) ? 'd-none' : '' }}">
                                    <img src="{{ $cta['image_url'] ?? '' }}" id="preview_card_img" class="cta-preview-img" alt="Zdjęcie oferty">
                                </div>
                                <div class="mt-3 text-start" id="preview_button_wrapper">
                                    <a href="#" class="btn btn-warning text-dark btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm" id="preview_button">
                                        {{ $cta['button_text'] ?? 'Zobacz dostępne koty' }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- ===============================================================
         MEDIA PICKER MODAL
         =============================================================== --}}
    <div class="modal fade" id="mediaPickerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-images me-2"></i>Wybierz zdjęcie z biblioteki</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <input type="text" id="mpSearch" class="form-control form-control-sm" placeholder="Szukaj po nazwie...">
                        </div>
                        <div class="col-auto ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="mpRefresh">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                    </div>
                    <div id="mpGrid" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-2"></div>
                    <div id="mpPagination" class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top small text-muted"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const badgeInput = document.getElementById('badge_input');
        const headingInput = document.getElementById('heading_input');
        const bodyInput = document.getElementById('body_input');
        const imageUrlInput = document.getElementById('image_url_input');
        const buttonTextInput = document.getElementById('button_text_input');
        const buttonUrlInput = document.getElementById('button_url_input');
        const isEnabledInput = document.getElementById('is_enabled');

        const previewBadgeText = document.getElementById('preview_badge_text');
        const previewTitle = document.getElementById('preview_title');
        const previewBody = document.getElementById('preview_body');
        const previewImgWrapper = document.getElementById('preview_img_wrapper');
        const previewCardImg = document.getElementById('preview_card_img');
        const previewButton = document.getElementById('preview_button');
        const previewButtonWrapper = document.getElementById('preview_button_wrapper');
        const previewStatusBadge = document.getElementById('preview_status_badge');
        const livePreviewCard = document.getElementById('live_preview_card');

        // Simple Markdown parser for live preview
        function parseMd(md) {
            if (!md) return '';
            let html = md
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" style="color:#b38600;font-weight:600;">$1</a>');

            const lines = html.split('\n');
            let res = '';
            let inList = false;

            lines.forEach(line => {
                const trimmed = line.trim();
                if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
                    if (!inList) { res += '<ul>'; inList = true; }
                    res += '<li>' + trimmed.substring(2) + '</li>';
                } else {
                    if (inList) { res += '</ul>'; inList = false; }
                    if (trimmed === '') {
                        // Empty line
                    } else {
                        res += '<p>' + trimmed + '</p>';
                    }
                }
            });
            if (inList) res += '</ul>';
            return res;
        }

        function updatePreview() {
            previewBadgeText.textContent = badgeInput.value || 'Hodowla Kotów z Mazowieckiej Szwajcarii';
            previewTitle.textContent = headingInput.value || '(Brak nagłówka)';
            previewBody.innerHTML = parseMd(bodyInput.value);

            if (imageUrlInput.value) {
                previewCardImg.src = imageUrlInput.value;
                previewImgWrapper.classList.remove('d-none');
            } else {
                previewImgWrapper.classList.add('d-none');
            }

            if (buttonTextInput.value) {
                previewButton.textContent = buttonTextInput.value + ' →';
                previewButtonWrapper.classList.remove('d-none');
            } else {
                previewButtonWrapper.classList.add('d-none');
            }

            if (isEnabledInput.checked) {
                previewStatusBadge.textContent = 'Aktywny';
                previewStatusBadge.className = 'badge bg-success';
                livePreviewCard.style.opacity = '1';
            } else {
                previewStatusBadge.textContent = 'Wyłączony';
                previewStatusBadge.className = 'badge bg-secondary';
                livePreviewCard.style.opacity = '0.4';
            }
        }

        badgeInput.addEventListener('input', updatePreview);
        headingInput.addEventListener('input', updatePreview);
        bodyInput.addEventListener('input', updatePreview);
        buttonTextInput.addEventListener('input', updatePreview);
        buttonUrlInput.addEventListener('input', updatePreview);
        isEnabledInput.addEventListener('change', updatePreview);

        updatePreview();

        // ── Media Picker Modal ───────────────────────────────────────────
        const mpModal = document.getElementById('mediaPickerModal');
        const mpGrid = document.getElementById('mpGrid');
        const mpSearch = document.getElementById('mpSearch');
        const mpPagination = document.getElementById('mpPagination');
        const imagePreviewBox = document.getElementById('image_preview_box');
        const removeImageBtn = document.getElementById('remove_image_btn');

        document.getElementById('open_media_btn').addEventListener('click', function () {
            loadMedia(1);
            new bootstrap.Modal(mpModal).show();
        });

        removeImageBtn.addEventListener('click', function () {
            imageUrlInput.value = '';
            imagePreviewBox.innerHTML = '<div class="rounded border bg-light d-flex align-items-center justify-content-center w-100 h-100 text-muted"><i class="fas fa-image fa-2x"></i></div>';
            removeImageBtn.classList.add('d-none');
            updatePreview();
        });

        let searchTimeout;
        mpSearch.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadMedia(1), 300);
        });

        document.getElementById('mpRefresh').addEventListener('click', () => loadMedia(1));

        function loadMedia(page) {
            const q = encodeURIComponent(mpSearch.value || '');
            mpGrid.innerHTML = '<div class="col-12 text-center py-4 text-muted"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
            fetch(`{{ route('backend.media.api') }}?page=${page}&q=${q}&type=image`)
                .then(r => r.json())
                .then(data => {
                    renderMediaGrid(data.data || []);
                    renderMediaPagination(data.meta || {});
                })
                .catch(() => {
                    mpGrid.innerHTML = '<div class="col-12 text-center py-3 text-danger">Błąd ładowania mediów.</div>';
                });
        }

        function renderMediaGrid(items) {
            if (!items.length) {
                mpGrid.innerHTML = '<div class="col-12 text-center py-4 text-muted">Brak zdjęć w bibliotece.</div>';
                return;
            }
            mpGrid.innerHTML = '';
            items.filter(i => i.is_image).forEach(item => {
                const col = document.createElement('div');
                col.className = 'col';
                col.innerHTML = `
                    <div class="card h-100 border p-1 text-center shadow-sm" style="cursor:pointer;">
                        <img src="${item.url}" alt="${item.title || ''}" class="card-img-top rounded" style="height:90px;object-fit:cover;">
                        <div class="card-body p-1">
                            <div class="small fw-semibold text-truncate">${item.title || ''}</div>
                        </div>
                    </div>
                `;
                col.querySelector('.card').addEventListener('click', function () {
                    imageUrlInput.value = item.url;
                    imagePreviewBox.innerHTML = `<img src="${item.url}" class="rounded border w-100 h-100" style="object-fit:cover;" alt="Wybrane zdjęcie">`;
                    removeImageBtn.classList.remove('d-none');
                    updatePreview();
                    bootstrap.Modal.getInstance(mpModal)?.hide();
                });
                mpGrid.appendChild(col);
            });
        }

        function renderMediaPagination(meta) {
            const total = meta.last_page || 1;
            const current = meta.current_page || 1;
            if (total <= 1) { mpPagination.innerHTML = ''; return; }
            mpPagination.innerHTML = `
                <span>Strona ${current} z ${total}</span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary ${current === 1 ? 'disabled' : ''}" id="mpPrev">Poprzednia</button>
                    <button type="button" class="btn btn-outline-secondary ${current === total ? 'disabled' : ''}" id="mpNext">Następna</button>
                </div>
            `;
            if (current > 1) document.getElementById('mpPrev')?.addEventListener('click', () => loadMedia(current - 1));
            if (current < total) document.getElementById('mpNext')?.addEventListener('click', () => loadMedia(current + 1));
        }
    });
    </script>
</x-backend.shell>
