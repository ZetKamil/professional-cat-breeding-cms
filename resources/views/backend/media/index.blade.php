<x-backend.shell title="Media - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Media">

        <form method="GET" action="{{ route('backend.media.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Search</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Search file name, path, alt text..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="post" @selected($filters['type'] === 'post')>Post</option>
                        <option value="user" @selected($filters['type'] === 'user')>User</option>
                        <option value="unattached" @selected($filters['type'] === 'unattached')>Unattached</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Featured</label>
                    <select name="featured" class="form-select">
                        <option value="">All</option>
                        <option value="yes" @selected($filters['featured'] === 'yes')>Yes</option>
                        <option value="no" @selected($filters['featured'] === 'no')>No</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Per page</label>
                    <select name="per_page" class="form-select">
                        @foreach($perPageAllowed as $n)
                            <option value="{{ $n }}" @selected((int) $filters['per_page'] === (int) $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Deleted</label>
                    <select name="trashed" class="form-select">
                        <option value="">Active only</option>
                        <option value="with" @selected($filters['trashed'] === 'with')>Active + deleted</option>
                        <option value="only" @selected($filters['trashed'] === 'only')>Deleted only</option>
                    </select>
                </div>

                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">Apply</button>

                    <a class="btn btn-outline-secondary" href="{{ route('backend.media.index') }}">
                        Clear
                    </a>

                    @can('create', \App\Models\Media::class)
                        <a href="{{ route('backend.media.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            New media
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.media.index', [
                    'q' => $filters['q'],
                    'type' => $filters['type'],
                    'featured' => $filters['featured'],
                    'trashed' => $filters['trashed'],
                    'per_page' => $filters['per_page'],
                    'sort' => $col,
                    'dir' => $newDir,
                ]);
            };

            $sortIcon = function (string $col) use ($filters) {
                if ($filters['sort'] !== $col) return '';
                return $filters['dir'] === 'asc' ? ' ▲' : ' ▼';
            };
        @endphp

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Media lijst
                <span class="text-muted ms-2">({{ $media->total() }} totaal)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('file_name') }}">File name{!! $sortIcon('file_name') !!}</a></th>
                        <th>Parent</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('is_featured') }}">Featured{!! $sortIcon('is_featured') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('sort_order') }}">Sort{!! $sortIcon('sort_order') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Created{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($media as $item)
                        <tr>
                            <td>{{ $item->id }}</td>

                            <td style="width: 90px;">
                                @if($item->isImage())
                                    <img src="{{ $item->url() }}" alt="{{ $item->alt_text ?? $item->file_name }}" class="img-thumbnail" style="max-height: 60px;">
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $item->file_name }}

                                @if($item->deleted_at)
                                    <span class="badge bg-danger ms-1">deleted</span>
                                @endif
                            </td>

                            <td>
                                @if($item->mediable)
                                    @if($item->mediable_type === \App\Models\Post::class)
                                        <span class="badge bg-info text-dark">Post</span>
                                        {{ $item->mediable->title }}
                                    @elseif($item->mediable_type === \App\Models\User::class)
                                        <span class="badge bg-warning text-dark">User</span>
                                        {{ $item->mediable->name }}
                                    @else
                                        {{ $item->parentLabel() }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                @if($item->is_featured)
                                    <span class="badge bg-success">yes</span>
                                @else
                                    <span class="badge bg-secondary">no</span>
                                @endif
                            </td>

                            <td>{{ $item->sort_order }}</td>
                            <td>{{ optional($item->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $item)
                                        <a href="{{ route('backend.media.show', $item) }}" class="btn btn-sm btn-outline-primary">
                                            Show
                                        </a>
                                    @endcan

                                    @if(! $item->deleted_at)
                                        @can('update', $item)
                                            <a href="{{ route('backend.media.edit', $item) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $item)
                                            <form method="POST" action="{{ route('backend.media.destroy', $item) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this media item?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $item)
                                            <form method="POST" action="{{ route('backend.media.restore', $item->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Restore this media item?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $item)
                                            <form method="POST" action="{{ route('backend.media.forceDelete', $item->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Permanently delete this media item? This cannot be undone.')">
                                                    Force delete
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No media found. Try clearing filters.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Showing {{ $media->firstItem() ?? 0 }} to {{ $media->lastItem() ?? 0 }} of {{ $media->total() }}
                </div>
                <div>
                    {{ $media->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
