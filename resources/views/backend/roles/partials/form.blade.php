<div class="row g-3">

    <div class="col-12 col-md-6">
        <label class="form-label">Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $role?->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
        >

        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $role?->description ?? '') }}</textarea>

        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">
            {{ $submitLabel ?? 'Save' }}
        </button>

        <a class="btn btn-outline-secondary" href="{{ route('backend.roles.index') }}">
            Cancel
        </a>
    </div>

</div>
