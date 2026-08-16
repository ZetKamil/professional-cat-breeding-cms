@props([
    'name' => 'media_id',
    'value' => null,
    'label' => 'Select Media',
    'hint' => '',
    'previewUrl' => null,
])

@php
    $inputId = 'media_picker_input_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $modalId = 'mediaPickerModal_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $gridId = 'mediaPickerGrid_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $previewImgId = 'media_picker_preview_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $emptyStateId = 'media_picker_empty_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    $selectedStateId = 'media_picker_selected_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
@endphp

<div class="media-picker-component mb-3">
    @if($label)
        <label class="form-label fw-semibold" for="{{ $inputId }}">{{ $label }}</label>
    @endif

    <input type="hidden" name="{{ $name }}" id="{{ $inputId }}" value="{{ $value }}" class="media-picker-hidden-input">

    {{-- PREVIEW CONTAINER --}}
    <div class="card p-2 border bg-light">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

            {{-- Selected State --}}
            <div id="{{ $selectedStateId }}" class="d-flex align-items-center gap-3 {{ ($value || $previewUrl) ? '' : 'd-none' }}">
                <img
                    id="{{ $previewImgId }}"
                    src="{{ $previewUrl ?: '' }}"
                    alt="Selected media preview"
                    class="rounded border bg-white shadow-sm"
                    style="width: 72px; height: 72px; object-fit: cover;"
                >
                <div>
                    <div class="fw-semibold small text-success">
                        <i class="fas fa-check-circle me-1"></i> Media Attached
                    </div>
                    <div class="text-muted small">Click 'Change' to pick a different file, or 'Remove' to detach.</div>
                </div>
            </div>

            {{-- Empty State --}}
            <div id="{{ $emptyStateId }}" class="d-flex align-items-center gap-2 {{ ($value || $previewUrl) ? 'd-none' : '' }}">
                <div class="rounded border bg-white d-flex align-items-center justify-content-center text-muted" style="width: 64px; height: 64px;">
                    <i class="fas fa-image fa-2x"></i>
                </div>
                <div>
                    <div class="fw-semibold small">No Media Selected</div>
                    <div class="text-muted small">Browse the media library to select an image.</div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-2">
                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary media-picker-open-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $modalId }}"
                >
                    <i class="fas fa-folder-open me-1"></i> Browse Library
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger media-picker-remove-btn {{ ($value || $previewUrl) ? '' : 'd-none' }}"
                    id="remove_btn_{{ $inputId }}"
                >
                    <i class="fas fa-times me-1"></i> Remove
                </button>
            </div>
        </div>
    </div>

    @if($hint)
        <div class="form-text small">{{ $hint }}</div>
    @endif
</div>

{{-- MEDIA LIBRARY MODAL --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}_label" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="{{ $modalId }}_label">
                    <i class="fas fa-images me-2 text-primary"></i> Select Media from Library
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3">
                <div class="row g-2 mb-3 pb-2 border-bottom">
                    <div class="col-12 col-md-5">
                        <input
                            type="text"
                            class="form-control form-control-sm media-picker-search"
                            placeholder="Search file name, title, or ALT text..."
                            aria-label="Search media in picker"
                        >
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm media-picker-type-filter" aria-label="Filter by type">
                            <option value="">All Types</option>
                            <option value="animal">Animal</option>
                            <option value="post">Post</option>
                            <option value="user">User</option>
                            <option value="unattached">Unattached</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 text-end">
                        <button type="button" class="btn btn-sm btn-secondary media-picker-refresh-btn">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                        <a href="{{ route('backend.media.create') }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-plus me-1"></i> Upload New
                        </a>
                    </div>
                </div>

                {{-- MEDIA ITEMS GRID --}}
                <div id="{{ $gridId }}" class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 media-picker-grid">
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                        <div>Loading media library...</div>
                    </div>
                </div>

                {{-- PAGINATION --}}
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top small text-muted media-picker-pagination">
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputId = @json($inputId);
        const modalId = @json($modalId);
        const gridId = @json($gridId);
        const previewImgId = @json($previewImgId);
        const emptyStateId = @json($emptyStateId);
        const selectedStateId = @json($selectedStateId);

        const modalEl = document.getElementById(modalId);
        const gridEl = document.getElementById(gridId);
        const inputEl = document.getElementById(inputId);
        const previewImgEl = document.getElementById(previewImgId);
        const emptyStateEl = document.getElementById(emptyStateId);
        const selectedStateEl = document.getElementById(selectedStateId);
        const removeBtnEl = document.getElementById('remove_btn_' + inputId);

        if (!modalEl || !gridEl) return;

        let currentPage = 1;

        const searchInput = modalEl.querySelector('.media-picker-search');
        const typeSelect = modalEl.querySelector('.media-picker-type-filter');
        const refreshBtn = modalEl.querySelector('.media-picker-refresh-btn');
        const paginationContainer = modalEl.querySelector('.media-picker-pagination');

        modalEl.addEventListener('show.bs.modal', function () {
            loadMedia(1);
        });

        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => loadMedia(1));
        }

        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadMedia(1), 300);
            });
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', () => loadMedia(1));
        }

        if (removeBtnEl) {
            removeBtnEl.addEventListener('click', function () {
                inputEl.value = '';
                previewImgEl.src = '';
                emptyStateEl.classList.remove('d-none');
                selectedStateEl.classList.add('d-none');
                removeBtnEl.classList.add('d-none');

                inputEl.dispatchEvent(new CustomEvent('media-selected', { detail: { id: null, url: null } }));
            });
        }

        function loadMedia(page = 1) {
            currentPage = page;
            const q = searchInput ? searchInput.value : '';
            const type = typeSelect ? typeSelect.value : '';

            const url = `{{ route('backend.media.api') }}?page=${page}&q=${encodeURIComponent(q)}&type=${encodeURIComponent(type)}`;

            gridEl.innerHTML = `
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                    <div>Loading media library...</div>
                </div>
            `;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    renderGrid(data.data || []);
                    renderPagination(data.meta || {});
                })
                .catch(err => {
                    gridEl.innerHTML = `
                        <div class="col-12 text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle mb-2"></i>
                            <div>Failed to load media items.</div>
                        </div>
                    `;
                });
        }

        function renderGrid(items) {
            if (!items.length) {
                gridEl.innerHTML = `
                    <div class="col-12 text-center py-4 text-muted">
                        <div>No media items found.</div>
                    </div>
                `;
                return;
            }

            gridEl.innerHTML = '';
            items.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col';
                col.innerHTML = `
                    <div
                        class="card h-100 border p-1 text-center shadow-sm media-picker-item"
                        style="cursor: pointer; transition: transform 0.15s ease;"
                        tabindex="0"
                        role="button"
                        aria-label="Select media ${item.title}"
                        data-media-id="${item.id}"
                        data-media-url="${item.url}"
                    >
                        <img src="${item.url}" alt="${item.alt_text || item.title}" class="card-img-top rounded" style="height: 96px; object-fit: cover;">
                        <div class="card-body p-1 d-flex flex-column justify-content-between">
                            <div class="small fw-semibold text-truncate" title="${item.title}">${item.title}</div>
                            <div class="text-muted" style="font-size: 0.65rem;">${item.size_kb} KB</div>
                        </div>
                    </div>
                `;

                const card = col.querySelector('.media-picker-item');
                card.addEventListener('click', () => selectMedia(item));
                card.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectMedia(item);
                    }
                });

                gridEl.appendChild(col);
            });
        }

        function selectMedia(item) {
            inputEl.value = item.id;
            previewImgEl.src = item.url;

            emptyStateEl.classList.add('d-none');
            selectedStateEl.classList.remove('d-none');
            if (removeBtnEl) removeBtnEl.classList.remove('d-none');

            inputEl.dispatchEvent(new CustomEvent('media-selected', { detail: { id: item.id, url: item.url, item: item } }));

            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        }

        function renderPagination(meta) {
            if (!paginationContainer) return;
            const totalPages = meta.last_page || 1;
            const current = meta.current_page || 1;

            if (totalPages <= 1) {
                paginationContainer.innerHTML = '<span>Showing all items</span>';
                return;
            }

            paginationContainer.innerHTML = `
                <span>Page ${current} of ${totalPages}</span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary ${current === 1 ? 'disabled' : ''}" id="mediaPrevBtn">Previous</button>
                    <button type="button" class="btn btn-outline-secondary ${current === totalPages ? 'disabled' : ''}" id="mediaNextBtn">Next</button>
                </div>
            `;

            const prevBtn = paginationContainer.querySelector('#mediaPrevBtn');
            const nextBtn = paginationContainer.querySelector('#mediaNextBtn');

            if (prevBtn && current > 1) {
                prevBtn.addEventListener('click', () => loadMedia(current - 1));
            }
            if (nextBtn && current < totalPages) {
                nextBtn.addEventListener('click', () => loadMedia(current + 1));
            }
        }
    });
</script>
