<x-backend.shell title="Animal Details: {{ $animal->name }}">

    <x-backend.page-header title="Animal Details: {{ $animal->name }}">
        
        <div class="mb-4">
            <a href="{{ route('backend.animals.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
            <a href="{{ route('backend.animals.edit', $animal) }}" class="btn btn-primary ms-2">
                <i class="fas fa-edit me-1"></i> Edit Animal
            </a>
        </div>

        <div class="row">
            <div class="col-md-4">
                {{-- Primary Image --}}
                <x-backend.card class="mb-4">
                    <div class="card-header">
                        <i class="fas fa-image me-1"></i> Featured Image
                    </div>
                    <div class="card-body text-center">
                        @if($animal->media)
                            <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" class="img-fluid rounded shadow-sm">
                        @else
                            <div class="text-muted p-5 bg-light rounded border">
                                <i class="fas fa-camera fa-3x mb-2 text-secondary"></i>
                                <p class="mb-0">No image uploaded</p>
                            </div>
                        @endif
                    </div>
                </x-backend.card>

                {{-- Status & Visibility --}}
                <x-backend.card class="mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-1"></i> Status & Visibility
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Status
                            <span class="badge bg-{{ $animal->status->badgeVariant() }} text-dark border">
                                {{ $animal->status->label() }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Published
                            @if($animal->is_published)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Featured
                            @if($animal->is_featured)
                                <span class="badge bg-warning text-dark">Yes</span>
                            @else
                                <span class="text-muted">No</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sort Order
                            <span>{{ $animal->sort_order }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Created
                            <span>{{ $animal->created_at->format('Y-m-d H:i') }}</span>
                        </li>
                    </ul>
                </x-backend.card>
            </div>

            <div class="col-md-8">
                {{-- Data --}}
                <x-backend.card class="mb-4">
                    <div class="card-header">
                        <i class="fas fa-paw me-1"></i> Basic Details
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">Name</th>
                                    <td><strong>{{ $animal->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td><code>{{ $animal->slug }}</code></td>
                                </tr>
                                <tr>
                                    <th>Breed</th>
                                    <td>{{ $animal->breed }}</td>
                                </tr>
                                <tr>
                                    <th>Color</th>
                                    <td>{{ $animal->color ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $animal->gender->label() }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ $animal->type->label() }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>
                                        @if($animal->date_of_birth)
                                            {{ $animal->date_of_birth->format('Y-m-d') }}
                                            <span class="text-muted ms-1">({{ $animal->age() }})</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-backend.card>

                {{-- Pedigree --}}
                <x-backend.card class="mb-4">
                    <div class="card-header">
                        <i class="fas fa-sitemap me-1"></i> Pedigree
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <h6 class="text-muted text-uppercase small fw-bold">Parents</h6>
                                <p class="mb-1">
                                    <strong>Mother:</strong> 
                                    @if($animal->mother)
                                        <a href="{{ route('backend.animals.show', $animal->mother) }}">{{ $animal->mother->name }}</a>
                                    @else
                                        <span class="text-muted">None / External</span>
                                    @endif
                                </p>
                                <p class="mb-0">
                                    <strong>Father:</strong> 
                                    @if($animal->father)
                                        <a href="{{ route('backend.animals.show', $animal->father) }}">{{ $animal->father->name }}</a>
                                    @else
                                        <span class="text-muted">None / External</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted text-uppercase small fw-bold">Offspring</h6>
                                @php
                                    $children = $animal->gender->value === 'female' 
                                        ? $animal->childrenAsMother 
                                        : $animal->childrenAsFather;
                                @endphp
                                
                                @if($children->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($children as $child)
                                            <li><i class="fas fa-caret-right text-muted me-1"></i> <a href="{{ route('backend.animals.show', $child) }}">{{ $child->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mb-0">No registered offspring in system.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-backend.card>

                {{-- Text Content --}}
                <x-backend.card class="mb-4">
                    <div class="card-header">
                        <i class="fas fa-file-alt me-1"></i> Content & SEO
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold">Short Description</h6>
                        <div class="p-3 bg-light rounded border mb-3 text-break">
                            {{ $animal->short_description ?: 'No short description provided.' }}
                        </div>
                        
                        <h6 class="fw-bold">Description</h6>
                        <div class="p-3 bg-light rounded border mb-4 text-break" style="white-space: pre-wrap;">{{ $animal->description ?: 'No description provided.' }}</div>
                        
                        <div class="border-top pt-3 mt-2">
                            <h6 class="text-muted text-uppercase small fw-bold">SEO Preview</h6>
                            @if($animal->meta_title || $animal->meta_description)
                                <div class="p-3 border rounded shadow-sm" style="max-width: 600px; background-color: #fff;">
                                    <div style="color: #1a0dab; font-size: 20px; font-family: arial, sans-serif; cursor: pointer;">
                                        {{ $animal->meta_title ?: $animal->name . ' - ' . config('app.name') }}
                                    </div>
                                    <div style="color: #006621; font-size: 14px; font-family: arial, sans-serif;">
                                        {{ url('/koty/' . $animal->slug) }}
                                    </div>
                                    <div style="color: #545454; font-size: 14px; font-family: arial, sans-serif; line-height: 1.4;">
                                        {{ $animal->meta_description ?: Str::limit($animal->short_description ?: $animal->description, 150) }}
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No custom SEO data provided. Default fallbacks will be used.</p>
                            @endif
                        </div>
                    </div>
                </x-backend.card>
                
            </div>
        </div>

    </x-backend.page-header>

</x-backend.shell>
