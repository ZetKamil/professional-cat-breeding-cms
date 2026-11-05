<div class="row">
    <div class="col-md-8">
        {{-- Basic Information --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Podstawowe informacje</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Imię <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $animal->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $animal->slug) }}">
                    <div class="form-text">Pozostaw puste, aby wygenerować automatycznie z imienia.</div>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="breed" class="form-label">Rasa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('breed') is-invalid @enderror" id="breed" name="breed" value="{{ old('breed', $animal->breed ?? 'Maine Coon') }}" required>
                        @error('breed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="color" class="form-label">Kod koloru (EMS)</label>
                        <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $animal->color) }}">
                        @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Płeć <span class="text-danger">*</span></label>
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
                        <label for="type" class="form-label">Typ <span class="text-danger">*</span></label>
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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Data urodzenia</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($animal->date_of_birth)->format('Y-m-d')) }}">
                        <div class="form-text">
                            @if($animal->date_of_birth)
                                Wiek: {{ $animal->ageInWeeks() }} tyg. &bull; Standardowy odbiór (12 tyg.): <strong>{{ $animal->date_of_birth->copy()->addWeeks(12)->format('d.m.Y') }}</strong>
                            @else
                                Data urodzenia kociaka.
                            @endif
                        </div>
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="earliest_pickup_date" class="form-label">
                            Indywidualna data odbioru <small class="text-muted">(opcjonalnie)</small>
                        </label>
                        <input type="date" class="form-control @error('earliest_pickup_date') is-invalid @enderror" id="earliest_pickup_date" name="earliest_pickup_date" value="{{ old('earliest_pickup_date', optional($animal->earliest_pickup_date)->format('Y-m-d')) }}">
                        <div class="form-text">
                            Wypełnij tylko wtedy, gdy chcesz ręcznie wskazać termin odbioru <strong>dla tego konkretnego kota</strong>. Puste = automatycznie 12 tyg. od urodzenia.
                        </div>
                        @error('earliest_pickup_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">
                            Kwota w opcji „na kolanka” (zł) <small class="text-muted">(pupil domowy)</small>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $animal->price) }}" 
                                   placeholder="np. 2700" 
                                   min="0" 
                                   step="50">
                            <span class="input-group-text">zł</span>
                        </div>
                        <div class="form-text">
                            Wyświetla się elegancko na karcie kota i profilu. Widoczna tylko dla dostępnych kociąt (ukryta dla kotów hodowlanych).
                        </div>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Descriptions --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Treść i opisy</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="short_description" class="form-label">Krótki opis</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description', $animal->short_description) }}</textarea>
                    <div class="form-text">Wyświetlany na wizytówkach i listach kotów.</div>
                    @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Pełny opis</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8">{{ old('description', $animal->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Pedigree --}}
        <div class="card mb-4">
            <div class="card-header bg-light">Rodowód (Rodzice)</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mother_id" class="form-label">Matka</label>
                        <select class="form-select @error('mother_id') is-invalid @enderror" id="mother_id" name="mother_id">
                            <option value="">Brak / Zewnętrzna</option>
                            @foreach($potentialParents->where('gender.value', 'female') as $parent)
                                <option value="{{ $parent->id }}" @selected(old('mother_id', $animal->mother_id) === $parent->id)>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mother_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="father_id" class="form-label">Ojciec</label>
                        <select class="form-select @error('father_id') is-invalid @enderror" id="father_id" name="father_id">
                            <option value="">Brak / Zewnętrzny</option>
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
            <div class="card-header bg-light">Publikacja i widoczność</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $animal->is_published))>
                    <label class="form-check-label" for="is_published">Opublikowany na stronie</label>
                </div>
                
                <div class="mb-3">
                    <label for="published_at" class="form-label">Data publikacji</label>
                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', optional($animal->published_at)->format('Y-m-d\TH:i')) }}">
                    <div class="form-text">Pozostaw puste, aby użyć aktualnego czasu.</div>
                    @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <hr>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $animal->is_featured))>
                    <label class="form-check-label" for="is_featured">Wyróżniony na stronie głównej</label>
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label">Kolejność wyświetlania (sort_order)</label>
                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', $animal->sort_order ?: '') }}" placeholder="np. 1, 20, 30...">
                    <div class="form-text">
                        <div class="d-flex flex-wrap gap-1 my-1">
                            <span class="badge bg-light text-dark border">1–19 Kocięta</span>
                            <span class="badge bg-light text-dark border">20+ W nowym domu</span>
                            <span class="badge bg-light text-dark border">31+ Brytyjczyki</span>
                            <span class="badge bg-light text-dark border">41+ Syjamy</span>
                            <span class="badge bg-light text-dark border">51+ Bengale</span>
                        </div>
                        <div class="small text-muted">Zostaw puste lub wpisz 0, aby system nadał kolejny numer automatycznie.</div>
                    </div>
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
                {{-- Hidden input for cropped base64 image data --}}
                <input type="hidden" name="cropped_image" id="cropped_image" value="">

                @if($animal->media)
                    <div class="mb-3" id="current-main-image-container">
                        <div class="small text-muted mb-1">Obecne zdjęcie główne:</div>
                        <div class="d-flex align-items-start gap-3 flex-wrap">
                            <img src="{{ $animal->media->url() }}" id="current-main-img" class="img-fluid rounded border mb-2 shadow-sm" alt="Obecne zdjęcie" style="max-height: 160px; max-width: 160px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <button type="button" class="btn btn-sm btn-outline-primary mb-2 shadow-sm" id="btn-crop-existing-main">
                                    <i class="fas fa-crop-alt me-1"></i> Dopasuj kadr obecnego zdjęcia
                                </button>
                                <div class="form-text small text-muted" style="line-height: 1.4;">
                                    Możesz w każdej chwili poprawić kadr, przybliżyć lub przesunąć obecne zdjęcie kota bez ponownego wgrywania pliku.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Live Preview for newly selected/cropped main image --}}
                <div class="mb-3 d-none" id="new-main-preview-container">
                    <div class="small text-success fw-bold mb-1 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-check-circle me-1"></i>Nowe zdjęcie główne (podgląd kadru):</span>
                        <span class="badge bg-success" id="cropped-status-badge"><i class="fas fa-crop-alt me-1"></i>Wykadrowane (1:1)</span>
                    </div>
                    <div class="position-relative d-inline-block">
                        <img src="" id="new-main-preview-img" class="img-thumbnail rounded shadow-sm" style="width: 160px; height: 160px; object-fit: cover;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1" style="width: 26px; height: 26px; line-height: 1;" id="cancel-new-main" title="Anuluj zmianę zdjęcia">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-recrop-main">
                            <i class="fas fa-crop-alt me-1"></i> Popraw / Zmień kadr
                        </button>
                    </div>
                    <div class="small text-muted mt-1" id="new-main-info"></div>
                </div>
                
                <div class="mb-2">
                    <label for="image" class="form-label">Wgraj / Zmień zdjęcie główne</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/jfif,image/*">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text text-wrap" style="white-space: normal !important; line-height: 1.4;">
                        Maksymalnie 20 MB (JPG, PNG, WEBP, JFIF).<br>
                        <span class="text-primary fw-semibold"><i class="fas fa-crop-alt me-1"></i>Po wybraniu pliku otworzy się interaktywny kadrownik 1:1</span> &bull; 
                        <span class="text-success fw-semibold"><i class="fas fa-magic me-1"></i>Automatyczna optymalizacja w locie</span>
                    </div>
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label small text-muted fw-bold mb-0">
                                <i class="fas fa-arrows-alt me-1 text-primary"></i>
                                Zapisane zdjęcia w galerii (chwyć i przeciągnij myszką, aby zmienić kolejność):
                            </label>
                            <span id="gallery-reorder-status" class="badge bg-success-subtle text-success border border-success-subtle d-none">
                                <i class="fas fa-check me-1"></i> Zapisano nową kolejność!
                            </span>
                        </div>
                        <div id="animal-gallery-sortable" class="row g-2" data-reorder-url="{{ route('backend.animals.media.reorder', $animal) }}">
                            @foreach($animal->gallery as $img)
                                <div class="col-4 text-center gallery-sortable-item" data-media-id="{{ $img->id }}" draggable="true" style="cursor: grab; user-select: none;">
                                    <div class="position-relative border rounded p-1 bg-light shadow-sm h-100 d-flex flex-column justify-content-between gallery-card-inner">
                                        <div class="d-flex justify-content-between align-items-center px-1 mb-1" style="font-size: 11px;">
                                            <span class="badge bg-dark-subtle text-dark border order-badge" style="font-size: 10px;">#{{ $loop->iteration }}</span>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button" class="btn btn-link p-0 text-muted btn-move-left" title="Przesuń w lewo" style="font-size: 11px; text-decoration: none;">◀</button>
                                                <button type="button" class="btn btn-link p-0 text-muted btn-move-right" title="Przesuń w prawo" style="font-size: 11px; text-decoration: none;">▶</button>
                                                <span class="drag-handle text-muted ms-1" title="Chwyć i przeciągnij" style="cursor: grab;">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <img src="{{ $img->url() }}" alt="Galeria" class="img-thumbnail w-100 mb-1" style="height: 75px; object-fit: cover; pointer-events: none;">
                                        <div class="d-flex flex-column gap-1">
                                            @if($img->is_featured)
                                                <span class="badge bg-warning text-dark py-1" style="font-size: 11px;" title="To zdjęcie jest wizytówką profilową kota">
                                                    <i class="fas fa-star me-1"></i>Główne
                                                </span>
                                            @else
                                                <button type="submit" form="set-featured-media-{{ $img->id }}" class="btn btn-outline-warning text-dark btn-sm w-100 py-0" style="font-size: 11px;" title="Ustaw to zdjęcie jako zdjęcie główne (profilowe)">
                                                    <i class="fas fa-star text-warning me-1"></i>Główne
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 py-0 btn-crop-gallery-item" data-image-url="{{ $img->url() }}" data-filename="{{ basename($img->filename) }}" style="font-size: 11px;" title="Wykadruj to zdjęcie 1:1 i ustaw jako nowe zdjęcie główne">
                                                <i class="fas fa-crop-alt me-1"></i>Kadruj (1:1)
                                            </button>
                                            <button type="submit" form="delete-media-{{ $img->id }}" class="btn btn-outline-danger btn-sm w-100 py-0" style="font-size: 11px;">
                                                <i class="fas fa-trash me-1"></i>Usuń
                                            </button>
                                        </div>
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
                    <div id="gallery-dropzone" class="border-2 rounded p-3 p-md-4 text-center bg-light" style="border: 2px dashed #0d6efd !important; cursor: pointer; transition: all 0.2s ease; word-break: break-word;">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                        <h6 class="fw-bold mb-1">Przeciągnij i upuść zdjęcia tutaj</h6>
                        <p class="text-muted small mb-2">lub <span class="text-primary text-decoration-underline fw-bold">kliknij, aby wybrać z dysku</span></p>
                        <div class="d-inline-block bg-white text-secondary border rounded-3 px-3 py-2 small text-wrap text-center shadow-sm" style="max-width: 100%; white-space: normal !important; line-height: 1.45;">
                            <div>Wielokrotny wybór: do 30 zdjęć naraz (max 20 MB / plik)</div>
                            <div class="text-success fw-semibold mt-1">
                                <i class="fas fa-magic me-1"></i>Automatyczna optymalizacja / kompresja w locie
                            </div>
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
            <div class="card-header bg-light">Optymalizacja SEO</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Tytuł SEO (Meta Title)</label>
                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $animal->meta_title) }}">
                    @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="meta_description" class="form-label">Opis SEO (Meta Description)</label>
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

    // ─── Main Image Interactive Cropping & Live Preview ───────────────
    const mainInput = document.getElementById('image');
    const croppedInput = document.getElementById('cropped_image');
    const mainPreviewContainer = document.getElementById('new-main-preview-container');
    const mainPreviewImg = document.getElementById('new-main-preview-img');
    const mainPreviewInfo = document.getElementById('new-main-info');
    const cancelMainBtn = document.getElementById('cancel-new-main');
    const btnCropExisting = document.getElementById('btn-crop-existing-main');
    const btnRecrop = document.getElementById('btn-recrop-main');
    const currentContainer = document.getElementById('current-main-image-container');

    // Keep memory of the raw uncropped file to allow re-cropping without re-selecting
    let currentRawImageSrc = null;
    let currentRawFileName = '{{ $animal->slug ?: "cat" }}_photo.jpg';

    function applyCroppedResult(blob, dataUrl) {
        if (!mainPreviewImg || !mainPreviewContainer) return;

        mainPreviewImg.src = dataUrl;
        mainPreviewContainer.classList.remove('d-none');

        if (croppedInput) {
            croppedInput.value = dataUrl;
        }

        if (currentContainer) {
            currentContainer.classList.add('opacity-50');
        }

        // Try to update the native file input with a real File object for maximum compatibility
        try {
            const dt = new DataTransfer();
            const croppedFile = new File([blob], 'cropped_' + currentRawFileName, { type: 'image/jpeg' });
            dt.items.add(croppedFile);
            mainInput.files = dt.files;
        } catch (err) {
            console.warn('DataTransfer not supported, falling back to base64 payload:', err);
        }

        const k = 1024;
        const sizeStr = blob.size > k * k 
            ? (blob.size / (k * k)).toFixed(1) + ' MB'
            : (blob.size / k).toFixed(0) + ' KB';

        if (mainPreviewInfo) {
            mainPreviewInfo.innerHTML = `
                <div class="text-success fw-bold mt-1">
                    <i class="fas fa-check-circle me-1"></i> Kadr 1:1 zatwierdzony (${sizeStr})
                </div>
                <div class="text-muted" style="font-size: 11px;">
                    Zapisz formularz na dole strony („Zaktualizuj dane kota”), aby zachować zmiany.
                </div>
            `;
        }
    }

    // Safe helper to trigger the cropping modal even if script definitions are finishing DOM attachment
    function triggerCropper(options) {
        if (typeof window.openImageCropper === 'function') {
            window.openImageCropper(options);
            return;
        }

        setTimeout(function() {
            if (typeof window.openImageCropper === 'function') {
                window.openImageCropper(options);
            } else {
                alert('Narzędzie do kadrowania wciąż się ładuje. Spróbuj ponownie za moment.');
            }
        }, 150);
    }

    // 1. When user clicks "Dopasuj kadr obecnego zdjęcia"
    if (btnCropExisting) {
        btnCropExisting.addEventListener('click', function() {
            @if($animal->media)
                const existingUrl = '{{ $animal->media->url() }}';
                currentRawImageSrc = existingUrl;
                currentRawFileName = '{{ basename($animal->media->filename) }}';

                triggerCropper({
                    imageSrc: existingUrl,
                    title: 'Kadrowanie obecnego zdjęcia: ' + @js($animal->name),
                    aspectRatio: 1,
                    lockRatio: true,
                    onCrop: applyCroppedResult
                });
            @endif
        });
    }

    // 1b. When user clicks "Kadruj (1:1)" on any photo in the gallery
    document.querySelectorAll('.btn-crop-gallery-item').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const imgUrl = this.dataset.imageUrl;
            const fileName = this.dataset.filename || 'gallery_photo.jpg';

            if (!imgUrl) return;

            currentRawImageSrc = imgUrl;
            currentRawFileName = fileName;

            triggerCropper({
                imageSrc: imgUrl,
                title: 'Wykadruj do formatu wizytówki 1:1 (z galerii): ' + fileName,
                aspectRatio: 1,
                lockRatio: true,
                onCrop: function(blob, dataUrl) {
                    applyCroppedResult(blob, dataUrl);
                    if (mainPreviewContainer) {
                        mainPreviewContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    });

    // 2. When user selects a new file from disk
    if (mainInput) {
        mainInput.addEventListener('change', function() {
            if (mainInput.files && mainInput.files[0]) {
                const file = mainInput.files[0];
                currentRawFileName = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    currentRawImageSrc = e.target.result;

                    // Automatically open cropper on file select
                    triggerCropper({
                        imageSrc: currentRawImageSrc,
                        title: 'Dopasuj kadr nowego zdjęcia: ' + file.name,
                        aspectRatio: 1,
                        lockRatio: true,
                        onCrop: applyCroppedResult
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. When user clicks "Popraw / Zmień kadr"
    if (btnRecrop) {
        btnRecrop.addEventListener('click', function() {
            const srcToCrop = currentRawImageSrc || (mainPreviewImg ? mainPreviewImg.src : null);
            if (srcToCrop) {
                triggerCropper({
                    imageSrc: srcToCrop,
                    title: 'Dostosuj kadr zdjęcia (Wizytówka 1:1)',
                    aspectRatio: 1,
                    lockRatio: true,
                    onCrop: applyCroppedResult
                });
            }
        });
    }

    // 4. Cancel / reset new crop
    if (cancelMainBtn) {
        cancelMainBtn.addEventListener('click', function() {
            if (mainInput) mainInput.value = '';
            if (croppedInput) croppedInput.value = '';
            currentRawImageSrc = null;
            if (mainPreviewContainer) mainPreviewContainer.classList.add('d-none');
            if (currentContainer) currentContainer.classList.remove('opacity-50');
        });
    }

    // ─── Existing Gallery Images Drag & Drop Reordering ────────────────
    const sortableContainer = document.getElementById('animal-gallery-sortable');
    const reorderStatus = document.getElementById('gallery-reorder-status');

    if (sortableContainer) {
        let draggedItem = null;

        function updateOrderBadges() {
            const items = sortableContainer.querySelectorAll('.gallery-sortable-item');
            items.forEach((item, idx) => {
                const badge = item.querySelector('.order-badge');
                if (badge) badge.textContent = '#' + (idx + 1);
            });
        }

        function saveNewGalleryOrder() {
            const reorderUrl = sortableContainer.dataset.reorderUrl;
            if (!reorderUrl) return;

            const items = sortableContainer.querySelectorAll('.gallery-sortable-item');
            const mediaIds = Array.from(items).map(item => item.dataset.mediaId);

            if (reorderStatus) {
                reorderStatus.className = 'badge bg-primary-subtle text-primary border border-primary-subtle d-inline-block';
                reorderStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Zapisywanie...';
            }

            fetch(reorderUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ media_ids: mediaIds })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (reorderStatus) {
                        reorderStatus.className = 'badge bg-success-subtle text-success border border-success-subtle d-inline-block';
                        reorderStatus.innerHTML = '<i class="fas fa-check me-1"></i> Zapisano nową kolejność!';
                        setTimeout(() => {
                            if (reorderStatus) reorderStatus.classList.add('d-none');
                        }, 3000);
                    }
                } else {
                    throw new Error(data.message || 'Błąd zapisu kolejności');
                }
            })
            .catch(err => {
                console.error('Gallery reorder error:', err);
                if (reorderStatus) {
                    reorderStatus.className = 'badge bg-danger-subtle text-danger border border-danger-subtle d-inline-block';
                    reorderStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Błąd zapisu kolejności';
                }
            });
        }

        function initGalleryItemEvents(item) {
            item.addEventListener('dragstart', (e) => {
                if (e.target.closest('button') || e.target.closest('form')) {
                    e.preventDefault();
                    return;
                }
                draggedItem = item;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', item.dataset.mediaId);
                setTimeout(() => item.classList.add('opacity-50'), 0);
            });

            item.addEventListener('dragend', () => {
                if (draggedItem) {
                    draggedItem.classList.remove('opacity-50');
                    draggedItem = null;
                }
                sortableContainer.querySelectorAll('.gallery-sortable-item').forEach(el => {
                    el.classList.remove('border-primary');
                });
            });

            item.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (!draggedItem || draggedItem === item) return;

                const rect = item.getBoundingClientRect();
                const next = (e.clientX - rect.left) / (rect.right - rect.left) > 0.5;
                sortableContainer.insertBefore(draggedItem, next && item.nextSibling ? item.nextSibling : item);
                updateOrderBadges();
            });

            item.addEventListener('drop', (e) => {
                e.preventDefault();
                updateOrderBadges();
                saveNewGalleryOrder();
            });

            const btnLeft = item.querySelector('.btn-move-left');
            const btnRight = item.querySelector('.btn-move-right');

            if (btnLeft) {
                btnLeft.addEventListener('click', (e) => {
                    e.preventDefault();
                    const prev = item.previousElementSibling;
                    if (prev) {
                        sortableContainer.insertBefore(item, prev);
                        updateOrderBadges();
                        saveNewGalleryOrder();
                    }
                });
            }

            if (btnRight) {
                btnRight.addEventListener('click', (e) => {
                    e.preventDefault();
                    const next = item.nextElementSibling;
                    if (next) {
                        sortableContainer.insertBefore(next, item);
                        updateOrderBadges();
                        saveNewGalleryOrder();
                    }
                });
            }
        }

        sortableContainer.querySelectorAll('.gallery-sortable-item').forEach(initGalleryItemEvents);
    }
});
</script>

{{-- Reusable Cropper Modal Component --}}
<x-backend.image-cropper id="animalImageCropper" />

