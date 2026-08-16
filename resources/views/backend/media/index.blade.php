<x-backend.shell title="Media Library - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .media-grid-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .media-grid-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
            }
            .media-thumb-wrapper {
                height: 180px;
                background-color: #f8f9fa;
                overflow: hidden;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .media-thumb-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            .media-grid-card:hover .media-thumb-img {
                transform: scale(1.05);
            }
            .badge-overlay {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 2;
            }
            .actions-overlay {
                position: absolute;
                bottom: 10px;
                right: 10px;
                z-index: 2;
                background: rgba(255, 255, 255, 0.9);
                border-radius: 4px;
                padding: 2px 6px;
            }
        </style>
    </x-slot:head>

    <x-backend.page-header title="Media Library">
        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
            <div>
                <span class="text-muted">Manage all production media, photography, and attachments</span>
            </div>
            <div class="d-flex gap-2">
                @can('create', \App\Models\Media::class)
                    <a href="{{ route('backend.media.create') }}" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt me-1"></i>
                        Upload Media
                    </a>
                @endcan
            </div>
        </div>
    </x-backend.page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header bg-white py-3">
            <form method="GET" action="{{ route('backend.media.index') }}" class="mb-0">
                <div class="row g-2 align-items-end">

                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1 small text-muted">Search keyword</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ $filters['q'] }}"
                            class="form-control form-control-sm"
                            placeholder="File name, ALT, title, caption..."
                        >
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1 small text-muted">Filter by type</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="animal" @selected($filters['type'] === 'animal')>Animal</option>
                            <option value="post" @selected($filters['type'] === 'post')>Post</option>
                            <option value="user" @selected($filters['type'] === 'user')>User</option>
                            <option value="unattached" @selected($filters['type'] === 'unattached')>Unattached</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1 small text-muted">Featured</label>
                        <select name="featured" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="yes" @selected($filters['featured'] === 'yes')>Featured Yes</option>
                            <option value="no" @selected($filters['featured'] === 'no')>Featured No</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1 small text-muted">Per page</label>
                        <select name="per_page" class="form-select form-select-sm">
                            @foreach($perPageAllowed as $n)
                                <option value="{{ $n }}" @selected((int) $filters['per_page'] === (int) $n)>{{ $n }} items</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3 d-flex gap-2 justify-content-end">
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                        <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                        <div class="btn-group btn-group-sm" role="group" aria-label="View switch">
                            <button
                                type="submit"
                                name="view"
                                value="grid"
                                class="btn {{ $filters['view'] === 'grid' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                                title="Grid View"
                            >
                                <i class="fas fa-th"></i>
                            </button>
                            <button
                                type="submit"
                                name="view"
                                value="list"
                                class="btn {{ $filters['view'] === 'list' ? 'btn-secondary' : 'btn-outline-secondary' }}"
                                title="List View"
                            >
                                <i class="fas fa-list"></i>
                            </button>
                        </div>

                        <button class="btn btn-sm btn-primary" type="submit">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>

                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('backend.media.index') }}">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @php
                $sortUrl = function (string $col) use ($filters) {
                    $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                    return route('backend.media.index', [
                        'q' => $filters['q'],
                        'type' => $filters['type'],
                        'featured' => $filters['featured'],
                        'trashed' => $filters['trashed'],
                        'per_page' => $filters['per_page'],
                        'view' => $filters['view'],
                        'sort' => $col,
                        'dir' => $newDir,
                    ]);
                };

                $sortIcon = function (string $col) use ($filters) {
                    if ($filters['sort'] !== $col) return '';
                    return $filters['dir'] === 'asc' ? ' ▲' : ' ▼';
                };
            @endphp

            @if ($media->isEmpty())
                <div class="text-center py-5">
                    <div class="text-muted mb-3">
                        <i class="fas fa-images fa-3x"></i>
                    </div>
                    <h5>No media files found</h5>
                    <p class="text-muted small">Try adjusting your filters or upload a new media file.</p>
                    @can('create', \App\Models\Media::class)
                        <a href="{{ route('backend.media.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Upload Media
                        </a>
                    @endcan
                </div>
            @else
                @if ($filters['view'] === 'list')
                    {{-- LIST VIEW --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">Preview</th>
                                    <th>
                                        <a href="{{ $sortUrl('filename') }}" class="text-decoration-none text-dark">
                                            File Name {{ $sortIcon('filename') }}
                                        </a>
                                    </th>
                                    <th>Type & Attached To</th>
                                    <th>
                                        <a href="{{ $sortUrl('size') }}" class="text-decoration-none text-dark">
                                            Size {{ $sortIcon('size') }}
                                        </a>
                                    </th>
                                    <th>ALT / Caption</th>
                                    <th>
                                        <a href="{{ $sortUrl('sort_order') }}" class="text-decoration-none text-dark">
                                            Sort {{ $sortIcon('sort_order') }}
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ $sortUrl('created_at') }}" class="text-decoration-none text-dark">
                                            Uploaded {{ $sortIcon('created_at') }}
                                        </a>
                                    </th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($media as $item)
                                    <tr>
                                        <td>
                                            @if($item->isImage())
                                                <img
                                                    src="{{ $item->url() }}"
                                                    alt="{{ $item->alt_text ?: $item->title }}"
                                                    class="rounded border"
                                                    style="width: 54px; height: 54px; object-fit: cover;"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                                    <i class="fas fa-file text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-break">{{ $item->title ?: $item->filename }}</div>
                                            <div class="small text-muted">{{ $item->filename }}</div>
                                            @if($item->is_featured)
                                                <span class="badge bg-warning text-dark mt-1">Featured</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->mediable)
                                                <span class="badge bg-info text-dark">
                                                    {{ class_basename($item->mediable_type) }} #{{ $item->mediable_id }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Unattached</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap small text-muted">
                                            {{ round($item->size / 1024, 1) }} KB
                                        </td>
                                        <td>
                                            @if($item->alt_text)
                                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">ALT: OK</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">No ALT</span>
                                            @endif
                                            @if($item->caption)
                                                <div class="small text-muted text-truncate mt-1" style="max-width: 180px;">{{ $item->caption }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td class="text-nowrap small text-muted">
                                            {{ $item->created_at?->format('Y-m-d') }}
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#mediaPreviewModal"
                                                    data-media-url="{{ $item->url() }}"
                                                    data-media-title="{{ $item->title ?: $item->filename }}"
                                                    data-media-alt="{{ $item->alt_text ?: 'None' }}"
                                                    data-media-caption="{{ $item->caption ?: 'None' }}"
                                                    data-media-copyright="{{ $item->copyright ?: 'None' }}"
                                                    data-media-size="{{ round($item->size / 1024, 1) }} KB"
                                                    title="Preview"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('backend.media.show', $item) }}" class="btn btn-outline-secondary" title="Details">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                                @can('update', $item)
                                                    <a href="{{ route('backend.media.edit', $item) }}" class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $item)
                                                    <form method="POST" action="{{ route('backend.media.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this media file?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- GRID VIEW --}}
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                        @foreach($media as $item)
                            <div class="col">
                                <div class="card h-100 border media-grid-card">
                                    <div class="media-thumb-wrapper">
                                        <div class="badge-overlay d-flex flex-column gap-1">
                                            @if($item->is_featured)
                                                <span class="badge bg-warning text-dark">Featured</span>
                                            @endif
                                            @if($item->mediable)
                                                <span class="badge bg-info text-dark">{{ class_basename($item->mediable_type) }}</span>
                                            @else
                                                <span class="badge bg-secondary">Library</span>
                                            @endif
                                        </div>
                                        @if($item->isImage())
                                            <img
                                                src="{{ $item->url() }}"
                                                alt="{{ $item->alt_text ?: $item->title }}"
                                                class="media-thumb-img"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="text-center p-3">
                                                <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
                                                <div class="small text-muted">{{ $item->mime_type }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="card-title text-truncate mb-1 small fw-bold" title="{{ $item->title ?: $item->filename }}">
                                                {{ $item->title ?: $item->filename }}
                                            </h6>
                                            <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.75rem;">
                                                <span>{{ round($item->size / 1024, 1) }} KB</span>
                                                <span>{{ $item->created_at?->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                            <div>
                                                @if($item->alt_text)
                                                    <span class="badge bg-success-subtle text-success-emphasis" style="font-size: 0.65rem;">ALT</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size: 0.65rem;">NO ALT</span>
                                                @endif
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#mediaPreviewModal"
                                                    data-media-url="{{ $item->url() }}"
                                                    data-media-title="{{ $item->title ?: $item->filename }}"
                                                    data-media-alt="{{ $item->alt_text ?: 'None' }}"
                                                    data-media-caption="{{ $item->caption ?: 'None' }}"
                                                    data-media-copyright="{{ $item->copyright ?: 'None' }}"
                                                    data-media-size="{{ round($item->size / 1024, 1) }} KB"
                                                    title="Preview"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @can('update', $item)
                                                    <a href="{{ route('backend.media.edit', $item) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $item)
                                                    <form method="POST" action="{{ route('backend.media.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this media file?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4">
                    {{ $media->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- IMAGE PREVIEW MODAL --}}
    <div class="modal fade" id="mediaPreviewModal" tabindex="-1" aria-labelledby="mediaPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPreviewModalLabel">Media Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3 bg-light p-2 rounded d-flex align-items-center justify-content-center" style="min-height: 240px; max-height: 480px; overflow: hidden;">
                        <img id="previewModalImage" src="" alt="Preview" class="img-fluid" style="max-height: 450px; object-fit: contain;">
                    </div>
                    <div class="text-start border-top pt-3 row g-2 small">
                        <div class="col-sm-6">
                            <strong>Title / File:</strong> <span id="previewModalTitle" class="text-muted"></span>
                        </div>
                        <div class="col-sm-6">
                            <strong>File Size:</strong> <span id="previewModalSize" class="text-muted"></span>
                        </div>
                        <div class="col-sm-6">
                            <strong>ALT Text:</strong> <span id="previewModalAlt" class="text-muted"></span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Copyright:</strong> <span id="previewModalCopyright" class="text-muted"></span>
                        </div>
                        <div class="col-12">
                            <strong>Caption:</strong> <span id="previewModalCaption" class="text-muted"></span>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">URL</span>
                                <input type="text" id="previewModalUrl" class="form-control" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="copyUrlBtn">
                                    <i class="fas fa-copy me-1"></i> Copy URL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const previewModal = document.getElementById('mediaPreviewModal');
            if (previewModal) {
                previewModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const url = button.getAttribute('data-media-url');
                    const title = button.getAttribute('data-media-title');
                    const alt = button.getAttribute('data-media-alt');
                    const caption = button.getAttribute('data-media-caption');
                    const copyright = button.getAttribute('data-media-copyright');
                    const size = button.getAttribute('data-media-size');

                    document.getElementById('previewModalImage').src = url;
                    document.getElementById('previewModalTitle').textContent = title;
                    document.getElementById('previewModalAlt').textContent = alt;
                    document.getElementById('previewModalCaption').textContent = caption;
                    document.getElementById('previewModalCopyright').textContent = copyright;
                    document.getElementById('previewModalSize').textContent = size;
                    document.getElementById('previewModalUrl').value = url;
                });
            }

            const copyUrlBtn = document.getElementById('copyUrlBtn');
            if (copyUrlBtn) {
                copyUrlBtn.addEventListener('click', function () {
                    const urlInput = document.getElementById('previewModalUrl');
                    urlInput.select();
                    navigator.clipboard.writeText(urlInput.value);
                    copyUrlBtn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                    setTimeout(() => {
                        copyUrlBtn.innerHTML = '<i class="fas fa-copy me-1"></i> Copy URL';
                    }, 2000);
                });
            }
        });
    </script>

</x-backend.shell>
