<div class="row g-3">

    <div class="col-12 col-md-6">
        <label class="form-label">Nazwa kategorii</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $category?->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
        >

        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Slug (URL)</label>
        <input
            type="text"
            name="slug"
            value="{{ old('slug', $category?->slug ?? '') }}"
            class="form-control @error('slug') is-invalid @enderror"
        >

        @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div class="form-text">Pozostaw puste, aby wygenerować automatycznie z nazwy.</div>
    </div>

    <div class="col-12">
        <label class="form-label">Opis</label>
        <textarea
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $category?->description ?? '') }}</textarea>

        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">
            {{ $submitLabel ?? 'Zapisz' }}
        </button>

        <a class="btn btn-outline-secondary" href="{{ route('backend.categories.index') }}">
            Anuluj
        </a>
    </div>

</div>
