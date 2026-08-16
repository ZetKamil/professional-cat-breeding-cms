@php
    $currentParentType = old('parent_type', $prefillType ?? '');

    if (! $currentParentType && isset($media) && $media?->mediable_type) {
        $currentParentType = match ($media->mediable_type) {
            \App\Models\Post::class => 'post',
            \App\Models\User::class => 'user',
            \App\Models\Animal::class => 'animal',
            default => '',
        };
    }

    $currentParentId = old('parent_id', $prefillId ?? ($media?->mediable_id ?? ''));
@endphp

<div class="row g-4">

    {{-- DRAG & DROP UPLOAD ZONE (MULTIPLE FOR CREATE / SINGLE FOR REPLACE) --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            @if(isset($media) && $media)
                Replace Image File (optional)
            @else
                Upload Image(s) — Single or Multiple
            @endif
        </label>

        <div
            id="mediaDropzone"
            class="border border-2 border-dashed rounded-3 p-4 text-center bg-light position-relative"
            style="transition: background-color 0.2s ease, border-color 0.2s ease; cursor: pointer;"
            tabindex="0"
            role="button"
            aria-label="Upload media drop zone"
        >
            <input
                type="file"
                name="{{ isset($media) && $media ? 'upload' : 'uploads[]' }}"
                id="mediaFileInput"
                accept="image/*"
                class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                style="cursor: pointer;"
                @if(!isset($media) || !$media) multiple @endif
            >

            <div id="dropzonePlaceholder">
                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                <h6 class="mb-1 fw-bold">Drag & drop image files here, or click to browse</h6>
                <p class="text-muted small mb-0">Supports JPG, PNG, WEBP, GIF up to 10 MB per file</p>
            </div>

            <div id="dropzonePreview" class="row g-2 mt-3 d-none text-start">
                {{-- Dynamic thumbnails will be inserted here by JavaScript --}}
            </div>
        </div>

        @error('upload')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('uploads')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('uploads.*')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        @if(isset($media) && $media && $media->isImage())
            <div class="d-flex align-items-center gap-3 mt-3 p-2 border rounded bg-white">
                <img src="{{ $media->url() }}" alt="{{ $media->alt_text }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                <div>
                    <div class="fw-semibold small">Current File: {{ $media->filename }}</div>
                    <div class="text-muted small">{{ round($media->size / 1024, 1) }} KB | {{ $media->mime_type }}</div>
                </div>
            </div>
        @endif
    </div>

    {{-- METADATA SECTION --}}
    <div class="col-12">
        <hr class="my-1">
        <h6 class="text-muted small text-uppercase fw-bold mb-2">Media Metadata & SEO</h6>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Title / Headline</label>
        <input
            type="text"
            name="title"
            value="{{ old('title', $media?->title ?? '') }}"
            class="form-control @error('title') is-invalid @enderror"
            placeholder="Descriptive title"
        >
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Copyright / Credit</label>
        <input
            type="text"
            name="copyright"
            value="{{ old('copyright', $media?->copyright ?? '') }}"
            class="form-control @error('copyright') is-invalid @enderror"
            placeholder="e.g. © 2026 ZetKamil Cattery"
        >
        @error('copyright')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">
            ALT Text <span class="text-danger">*</span>
            <span class="text-muted fw-normal small">(Crucial for accessibility and SEO)</span>
        </label>
        <input
            type="text"
            name="alt_text"
            value="{{ old('alt_text', $media?->alt_text ?? '') }}"
            class="form-control @error('alt_text') is-invalid @enderror"
            placeholder="Describe what is seen in the image for visually impaired readers"
        >
        @error('alt_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Caption / Description</label>
        <textarea
            name="caption"
            rows="3"
            class="form-control @error('caption') is-invalid @enderror"
            placeholder="Optional extended description displayed below the image"
        >{{ old('caption', $media?->caption ?? '') }}</textarea>
        @error('caption')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- PARENT ATTACHMENT SECTION --}}
    <div class="col-12">
        <hr class="my-1">
        <h6 class="text-muted small text-uppercase fw-bold mb-2">Attachment & Ordering</h6>
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Parent Type (Optional)</label>
        <select name="parent_type" id="parentTypeSelect" class="form-select @error('parent_type') is-invalid @enderror">
            <option value="">Unattached (Media Library)</option>
            <option value="animal" @selected($currentParentType === 'animal')>Animal</option>
            <option value="post" @selected($currentParentType === 'post')>Post (Blog)</option>
            <option value="user" @selected($currentParentType === 'user')>User</option>
        </select>
        @error('parent_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Parent Record</label>
        <select name="parent_id" id="parentIdSelect" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="">No parent selected</option>

            @if($currentParentType === 'animal' && isset($animals))
                @foreach($animals as $animal)
                    <option value="{{ $animal->id }}" @selected((string) $currentParentId === (string) $animal->id)>
                        Animal #{{ $animal->id }} - {{ $animal->name }}
                    </option>
                @endforeach
            @elseif($currentParentType === 'post' && isset($posts))
                @foreach($posts as $post)
                    <option value="{{ $post->id }}" @selected((string) $currentParentId === (string) $post->id)>
                        Post #{{ $post->id }} - {{ $post->title }}
                    </option>
                @endforeach
            @elseif($currentParentType === 'user' && isset($users))
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected((string) $currentParentId === (string) $user->id)>
                        User #{{ $user->id }} - {{ $user->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('parent_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label">Sort Order</label>
        <input
            type="number"
            name="sort_order"
            value="{{ old('sort_order', $media?->sort_order ?? 0) }}"
            class="form-control @error('sort_order') is-invalid @enderror"
            min="0"
        >
        @error('sort_order')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-6 col-md-2 d-flex align-items-end">
        <div class="form-check mb-2">
            <input
                class="form-check-input @error('is_featured') is-invalid @enderror"
                type="checkbox"
                name="is_featured"
                value="1"
                id="isFeaturedCheck"
                @checked(old('is_featured', $media?->is_featured ?? false))
            >
            <label class="form-check-label fw-semibold" for="isFeaturedCheck">
                Featured
            </label>
        </div>
    </div>

    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between">
        <a href="{{ route('backend.media.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Cancel
        </a>

        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-1"></i> {{ $submitLabel ?? 'Save Media' }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('mediaDropzone');
        const fileInput = document.getElementById('mediaFileInput');
        const previewContainer = document.getElementById('dropzonePreview');
        const placeholder = document.getElementById('dropzonePlaceholder');

        if (!dropzone || !fileInput) return;

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('bg-primary-subtle', 'border-primary');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('bg-primary-subtle', 'border-primary');
            });
        });

        fileInput.addEventListener('change', function () {
            displayPreviews(this.files);
        });

        function displayPreviews(files) {
            if (!files || files.length === 0) {
                previewContainer.classList.add('d-none');
                placeholder.classList.remove('d-none');
                return;
            }

            previewContainer.innerHTML = '';
            placeholder.classList.add('d-none');
            previewContainer.classList.remove('d-none');

            Array.from(files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-sm-4 col-md-3';
                    col.innerHTML = `
                        <div class="card p-1 shadow-sm border">
                            <img src="${e.target.result}" class="card-img-top rounded" style="height: 90px; object-fit: cover;" alt="Preview">
                            <div class="card-body p-1 text-center">
                                <div class="small fw-semibold text-truncate" title="${file.name}">${file.name}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">${(file.size / 1024).toFixed(1)} KB</div>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
