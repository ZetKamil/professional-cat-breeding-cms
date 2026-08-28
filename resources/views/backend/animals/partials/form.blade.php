<div class="row">
    <div class="col-md-8">
        {{-- Basic Information --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Basic Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $animal->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $animal->slug) }}">
                    <div class="form-text">Leave empty to auto-generate from name.</div>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="breed" class="form-label">Breed <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('breed') is-invalid @enderror" id="breed" name="breed" value="{{ old('breed', $animal->breed ?? 'Maine Coon') }}" required>
                        @error('breed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="color" class="form-label">Color Code</label>
                        <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $animal->color) }}">
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            @foreach($genders as $gender)
                                <option value="{{ $gender->value }}" @selected(old('gender', $animal->gender?->value) === $gender->value)>
                                    {{ $gender->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $animal->status?->value) === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            @foreach($types as $type)
                                <option value="{{ $type->value }}" @selected(old('type', $animal->type?->value) === $type->value)>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($animal->date_of_birth)->format('Y-m-d')) }}">
                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Descriptions --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Content</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description', $animal->short_description) }}</textarea>
                    <div class="form-text">Shown on cards and listings.</div>
                    @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Full Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8">{{ old('description', $animal->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Pedigree --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Pedigree</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mother_id" class="form-label">Mother</label>
                        <select class="form-select @error('mother_id') is-invalid @enderror" id="mother_id" name="mother_id">
                            <option value="">None / External</option>
                            @foreach($potentialParents->where('gender.value', 'female') as $parent)
                                <option value="{{ $parent->id }}" @selected(old('mother_id', $animal->mother_id) === $parent->id)>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mother_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="father_id" class="form-label">Father</label>
                        <select class="form-select @error('father_id') is-invalid @enderror" id="father_id" name="father_id">
                            <option value="">None / External</option>
                            @foreach($potentialParents->where('gender.value', 'male') as $parent)
                                <option value="{{ $parent->id }}" @selected(old('father_id', $animal->father_id) === $parent->id)>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('father_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Publishing & Visibility --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Publishing</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $animal->is_published))>
                    <label class="form-check-label" for="is_published">Published</label>
                </div>
                
                <div class="mb-3">
                    <label for="published_at" class="form-label">Publish Date</label>
                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', optional($animal->published_at)->format('Y-m-d\TH:i')) }}">
                    <div class="form-text">Leave empty to use current time when publishing.</div>
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <hr>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $animal->is_featured))>
                    <label class="form-check-label" for="is_featured">Featured on Homepage</label>
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $animal->sort_order ?? 0) }}">
                    <div class="form-text">Lower numbers appear first.</div>
                    @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Featured Image --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-image me-1 text-warning"></i> Zdjęcie główne</span>
                @if($animal->media)
                    <button type="submit" form="delete-main-photo-form" class="btn btn-outline-danger btn-sm py-0 px-2 small">
                        <i class="fas fa-trash-alt me-1"></i>Usuń główne
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if($animal->media)
                    <div class="mb-3" id="current-main-image-container">
                        <div class="small text-muted mb-1">Obecne zdjęcie główne:</div>
                        <img src="{{ $animal->media->url() }}" class="img-fluid rounded border mb-2" alt="Current image" style="max-height: 180px; object-fit: cover;">
                        <div class="form-text">Wgranie nowego zdjęcia zastąpi obecne zdjęcie główne.</div>
                    </div>
                @endif

                {{-- Live Preview for newly selected main image --}}
                <div class="mb-3 d-none" id="new-main-preview-container">
                    <div class="small text-success fw-bold mb-1"><i class="fas fa-check-circle me-1"></i>Nowe zdjęcie główne (podgląd):</div>
                    <div class="position-relative d-inline-block">
                        <img src="" id="new-main-preview-img" class="img-thumbnail rounded" style="max-height: 180px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1" style="width: 26px; height: 26px; line-height: 1;" id="cancel-new-main" title="Anuluj zmianę zdjęcia">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="small text-muted mt-1" id="new-main-info"></div>
                </div>
                
                <div class="mb-2">
                    <label for="image" class="form-label">Zmień / Wgraj zdjęcie główne</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/jfif,image/*">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Maksymalnie 20 MB (JPG, PNG, WEBP, JFIF). Zdjęcie zostanie automatycznie zoptymalizowane w locie.</div>
                </div>
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-images me-1 text-primary"></i> Galeria zdjęć kota</span>
                @if($animal->gallery && $animal->gallery->isNotEmpty())
                    <span class="badge bg-secondary">{{ $animal->gallery->count() }} w galerii</span>
                @endif
            </div>
            <div class="card-body">
                @if($animal->gallery && $animal->gallery->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Zapisane zdjęcia w galerii (kliknij Usuń aby usunąć z serwera):</label>
                        <div class="row g-2">
                            @foreach($animal->gallery as $img)
                                <div class="col-4 text-center">
                                    <div class="position-relative border rounded p-1 bg-light shadow-sm h-100 d-flex flex-column justify-content-between">
                                        <img src="{{ $img->url() }}" alt="Galeria" class="img-thumbnail w-100 mb-1" style="height: 75px; object-fit: cover;">
                                        <button type="submit" form="delete-media-{{ $img->id }}" class="btn btn-outline-danger btn-sm w-100 py-0" style="font-size: 11px;">
                                            <i class="fas fa-trash me-1"></i>Usuń
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endif

                {{-- Modern Drag & Drop Upload Zone --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Dodaj nowe zdjęcia do galerii</label>

                    {{-- Drop area --}}
                    <div id="gallery-dropzone" class="border-2 rounded p-4 text-center bg-light" style="border: 2px dashed #0d6efd !important; cursor: pointer; transition: all 0.2s ease;">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                        <h6 class="fw-bold mb-1">Przeciągnij i upuść zdjęcia tutaj</h6>
                        <p class="text-muted small mb-2">lub <span class="text-primary text-decoration-underline fw-bold">kliknij, aby wybrać z dysku</span></p>
                        <div class="badge bg-white text-secondary border px-2 py-1 small">
                            Wielokrotny wybór: do 30 zdjęć naraz (max 20 MB / plik, JPG, PNG, WEBP, JFIF)
                        </div>
                    </div>

                    {{-- Hidden native multiple file input --}}
                    <input type="file" id="gallery" name="gallery[]" accept="image/jpeg,image/png,image/webp,image/jfif,image/*" multiple class="d-none">

                    @error('gallery.*')
                        <div class="alert alert-danger mt-2 py-1 px-2 small">{{ $message }}</div>
                    @enderror
                    <div id="gallery-client-error" class="alert alert-danger mt-2 py-1 px-2 small d-none"></div>

                    {{-- Live Queue Container (shown when files are selected) --}}
                    <div id="gallery-queue-wrapper" class="mt-3 d-none">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <div>
                                <span class="badge bg-primary me-2"><i class="fas fa-images me-1"></i><span id="queue-count">0</span> nowych zdjęć</span>
                                <span class="badge bg-light text-dark border"><i class="fas fa-weight-hanging me-1"></i><span id="queue-size">0 MB</span></span>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 12px;" id="queue-clear-all">
                                <i class="fas fa-times me-1"></i>Wyczyść listę
                            </button>
                        </div>

                        {{-- Previews grid --}}
                        <div class="row g-2" id="gallery-preview-grid">
                            {{-- Dynamic thumbnail cards injected by JS --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="card mb-4">
            <div class="card-header bg-light">SEO</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $animal->meta_title) }}">
                    @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $animal->meta_description) }}</textarea>
                    @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Interactive Drag & Drop + Live Preview JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gallery Elements
    const dropzone = document.getElementById('gallery-dropzone');
    const input = document.getElementById('gallery');
    const queueWrapper = document.getElementById('gallery-queue-wrapper');
    const previewGrid = document.getElementById('gallery-preview-grid');
    const countBadge = document.getElementById('queue-count');
    const sizeBadge = document.getElementById('queue-size');
    const clearAllBtn = document.getElementById('queue-clear-all');
    const errorAlert = document.getElementById('gallery-client-error');

    if (dropzone && input) {
        let dt = new DataTransfer();
        const maxFileSize = 20 * 1024 * 1024; // 20 MB

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function renderQueue() {
            previewGrid.innerHTML = '';
            errorAlert.classList.add('d-none');
            errorAlert.textContent = '';

            if (dt.files.length === 0) {
                queueWrapper.classList.add('d-none');
                input.files = dt.files;
                return;
            }

            queueWrapper.classList.remove('d-none');
            let totalBytes = 0;

            Array.from(dt.files).forEach((file, index) => {
                totalBytes += file.size;

                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 text-center';

                const card = document.createElement('div');
                card.className = 'position-relative border rounded p-1 bg-white shadow-sm h-100 d-flex flex-column justify-content-between';

                const objectUrl = URL.createObjectURL(file);

                card.innerHTML = `
                    <div class="position-relative">
                        <img src="${objectUrl}" alt="${file.name}" class="rounded w-100 mb-1" style="height: 80px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                style="width: 22px; height: 22px; font-size: 11px;" title="Usuń to zdjęcie z kolejki">
                            &times;
                        </button>
                    </div>
                    <div class="text-truncate small fw-semibold" title="${file.name}" style="font-size: 11px;">${file.name}</div>
                    <div class="text-muted" style="font-size: 10px;">${formatBytes(file.size)}</div>
                `;

                card.querySelector('button').addEventListener('click', function(e) {
                    e.stopPropagation();
                    removeFile(index);
                });

                col.appendChild(card);
                previewGrid.appendChild(col);
            });

            countBadge.textContent = dt.files.length;
            sizeBadge.textContent = formatBytes(totalBytes);
            input.files = dt.files;
        }

        function removeFile(indexToRemove) {
            const newDt = new DataTransfer();
            Array.from(dt.files).forEach((file, idx) => {
                if (idx !== indexToRemove) {
                    newDt.items.add(file);
                }
            });
            dt = newDt;
            renderQueue();
        }

        function addFiles(newFiles) {
            let errors = [];
            Array.from(newFiles).forEach(file => {
                const isValidImage = file.type.startsWith('image/') || file.name.match(/\\.(jpg|jpeg|png|webp|jfif)$/i);
                if (!isValidImage) {
                    errors.push(`"${file.name}" nie jest prawidłowym plikiem graficznym.`);
                    return;
                }
                if (file.size > maxFileSize) {
                    errors.push(`"${file.name}" przekracza 20 MB (${formatBytes(file.size)}).`);
                    return;
                }
                const duplicate = Array.from(dt.files).some(f => f.name === file.name && f.size === file.size);
                if (!duplicate) {
                    dt.items.add(file);
                }
            });

            if (errors.length > 0) {
                errorAlert.textContent = errors.join(' ');
                errorAlert.classList.remove('d-none');
            }

            renderQueue();
        }

        dropzone.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            if (input.files.length > 0) {
                addFiles(input.files);
            }
        });

        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', () => {
                dt = new DataTransfer();
                renderQueue();
            });
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.backgroundColor = '#e7f1ff';
                dropzone.style.borderColor = '#0a58ca';
                dropzone.style.transform = 'scale(1.01)';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.style.backgroundColor = '#f8f9fa';
                dropzone.style.borderColor = '#0d6efd';
                dropzone.style.transform = 'scale(1)';
            });
        });

        dropzone.addEventListener('drop', (e) => {
            const dtData = e.dataTransfer;
            if (dtData && dtData.files && dtData.files.length > 0) {
                addFiles(dtData.files);
            }
        });
    }

    // Main Image Live Preview
    const mainInput = document.getElementById('image');
    const mainPreviewContainer = document.getElementById('new-main-preview-container');
    const mainPreviewImg = document.getElementById('new-main-preview-img');
    const mainPreviewInfo = document.getElementById('new-main-info');
    const cancelMainBtn = document.getElementById('cancel-new-main');

    if (mainInput && mainPreviewContainer && mainPreviewImg) {
        mainInput.addEventListener('change', function() {
            if (mainInput.files && mainInput.files[0]) {
                const file = mainInput.files[0];
                mainPreviewImg.src = URL.createObjectURL(file);
                const k = 1024;
                const sizeStr = file.size > k * k 
                    ? (file.size / (k * k)).toFixed(1) + ' MB'
                    : (file.size / k).toFixed(0) + ' KB';
                mainPreviewInfo.textContent = `${file.name} (${sizeStr})`;
                mainPreviewContainer.classList.remove('d-none');
            } else {
                mainPreviewContainer.classList.add('d-none');
            }
        });

        if (cancelMainBtn) {
            cancelMainBtn.addEventListener('click', function() {
                mainInput.value = '';
                mainPreviewContainer.classList.add('d-none');
            });
        }
    }
});
</script>
