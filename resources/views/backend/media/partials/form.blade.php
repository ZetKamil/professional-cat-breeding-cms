@php
    $currentParentType = old('parent_type', $prefillType ?? '');

    if (! $currentParentType && isset($media) && $media?->mediable_type) {
        $currentParentType = match ($media->mediable_type) {
            \App\Models\Post::class => 'post',
            \App\Models\User::class => 'user',
            default => '',
        };
    }

    $currentParentId = old('parent_id', $prefillId ?? ($media?->mediable_id ?? ''));
@endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Upload image</label>
        <input
            type="file"
            name="upload"
            accept="image/*"
            class="form-control @error('upload') is-invalid @enderror"
        >

        @error('upload')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(!empty($media?->file_path))
            <div class="form-text">Huidig bestand: {{ $media->file_name }}</div>
        @endif
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Parent type</label>
        <select name="parent_type" class="form-select @error('parent_type') is-invalid @enderror">
            <option value="">No parent</option>
            <option value="post" @selected($currentParentType === 'post')>Post</option>
            <option value="user" @selected($currentParentType === 'user')>User</option>
        </select>

        @error('parent_type')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-8">
        <label class="form-label">Parent record</label>
        <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
            <option value="">No parent selected</option>

            @if($currentParentType === 'post')
                @foreach($posts as $post)
                    <option value="{{ $post->id }}" @selected((string) $currentParentId === (string) $post->id)>
                        Post #{{ $post->id }} - {{ $post->title }}
                    </option>
                @endforeach
            @elseif($currentParentType === 'user')
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

    <div class="col-12">
        <label class="form-label">Alt text</label>
        <input
            type="text"
            name="alt_text"
            value="{{ old('alt_text', $media?->alt_text ?? '') }}"
            class="form-control @error('alt_text') is-invalid @enderror"
        >

        @error('alt_text')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Caption</label>
        <textarea
            name="caption"
            rows="4"
            class="form-control @error('caption') is-invalid @enderror"
        >{{ old('caption', $media?->caption ?? '') }}</textarea>

        @error('caption')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Sort order</label>
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

    <div class="col-12 col-md-8 d-flex align-items-end">
        <div class="form-check mb-2">
            <input
                class="form-check-input @error('is_featured') is-invalid @enderror"
                type="checkbox"
                name="is_featured"
                value="1"
                id="is_featured"
                @checked(old('is_featured', $media?->is_featured ?? false))
            >
            <label class="form-check-label" for="is_featured">
                Mark as featured image
            </label>
        </div>

        @error('is_featured')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @if(!empty($media) && $media->isImage())
        <div class="col-12">
            <label class="form-label d-block">Current image</label>
            <img src="{{ $media->url() }}" alt="{{ $media->alt_text ?? $media->file_name }}" class="img-fluid rounded border" style="max-height: 320px;">
        </div>
    @endif

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary" type="submit">
            {{ $submitLabel ?? 'Save' }}
        </button>

        <a class="btn btn-outline-secondary" href="{{ route('backend.media.index') }}">
            Cancel
        </a>
    </div>
</div>
