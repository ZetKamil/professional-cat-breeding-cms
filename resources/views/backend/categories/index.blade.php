<x-backend.shell title="Categories - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Categories">

        <form method="GET" action="{{ route('backend.categories.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Search</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Search name, slug or description..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Per page</label>
                    <select name="per_page" class="form-select">
                        @foreach($perPageAllowed as $n)
                            <option value="{{ $n }}" @selected((int)$filters['per_page'] === (int)$n)>{{ $n }}</option>
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

                    <a class="btn btn-outline-secondary" href="{{ route('backend.categories.index') }}">
                        Clear
                    </a>

                    @can('create', \App\Models\Category::class)
                        <a href="{{ route('backend.categories.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            New category
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.categories.index', [
                    'q' => $filters['q'],
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
                Categories lijst
                <span class="text-muted ms-2">({{ $categories->total() }} totaal)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('name') }}">Name{!! $sortIcon('name') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('slug') }}">Slug{!! $sortIcon('slug') !!}</a></th>
                        <th>Description</th>
                        <th>Posts</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Created{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                {{ $category->name }}
                                @if($category->deleted_at)
                                    <span class="badge bg-danger ms-1">deleted</span>
                                @endif
                            </td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($category->description ?? '-', 80) }}</td>
                            <td>{{ $category->posts_count }}</td>
                            <td>{{ optional($category->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">

                                    <a href="{{ route('backend.categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                        Show
                                    </a>

                                    @if(! $category->deleted_at)
                                        <a href="{{ route('backend.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('backend.categories.destroy', $category) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?')">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('backend.categories.restore', $category->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Restore this category?')">
                                                Restore
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('backend.categories.forceDelete', $category->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Permanently delete this category? This cannot be undone.')">
                                                Force delete
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No categories found. Try clearing filters.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}
                </div>
                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
