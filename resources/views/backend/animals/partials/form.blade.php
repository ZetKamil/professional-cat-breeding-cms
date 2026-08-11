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
            <div class="card-header bg-light fw-bold"><i class="fas fa-image me-1 text-warning"></i> Featured Image (Zdjęcie główne)</div>
            <div class="card-body">
                @if($animal->media)
                    <div class="mb-3">
                        <img src="{{ $animal->media->url() }}" class="img-fluid rounded mb-2" alt="Current image" style="max-height: 180px; object-fit: cover;">
                        <div class="form-text">Wgranie nowego zdjęcia zastąpi obecne zdjęcie główne.</div>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label for="image" class="form-label">Zmień / Wgraj zdjęcie główne</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Gallery Images --}}
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold"><i class="fas fa-images me-1 text-primary"></i> Galeria zdjęć kota</div>
            <div class="card-body">
                @if($animal->gallery && $animal->gallery->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label small text-muted">Obecne zdjęcia w galerii:</label>
                        <div class="row g-2">
                            @foreach($animal->gallery as $img)
                                <div class="col-4">
                                    <img src="{{ $img->url() }}" alt="Galeria" class="img-thumbnail w-100" style="height: 70px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="gallery" class="form-label">Dodaj zdjęcia do galerii (wielokrotny wybór)</label>
                    <input class="form-control @error('gallery.*') is-invalid @enderror" type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                    <div class="form-text">Możesz zaznaczyć kilka zdjęć naraz.</div>
                    @error('gallery.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
