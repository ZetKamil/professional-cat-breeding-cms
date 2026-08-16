<x-backend.shell title="Animals - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Animals">

        <form method="GET" action="{{ route('backend.animals.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Search</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Search name, breed, color..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">All genders</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->value }}" @selected($filters['gender'] === $gender->value)>
                                {{ $gender->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-1">
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

                    <a class="btn btn-outline-secondary" href="{{ route('backend.animals.index') }}">
                        Clear
                    </a>

                    @can('create', \App\Models\Animal::class)
                        <a href="{{ route('backend.animals.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            New animal
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.animals.index', [
                    'q' => $filters['q'],
                    'status' => $filters['status'],
                    'gender' => $filters['gender'],
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
                Animals list
                <span class="text-muted ms-2">({{ $animals->total() }} total)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('name') }}">Name{!! $sortIcon('name') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('breed') }}">Breed{!! $sortIcon('breed') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('gender') }}">Gender{!! $sortIcon('gender') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('status') }}">Status{!! $sortIcon('status') !!}</a></th>
                        <th>Featured / Published</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('sort_order') }}">Sort Order{!! $sortIcon('sort_order') !!}</a></th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($animals as $animal)
                        <tr>
                            <td>
                                @if($animal->media)
                                    <img
                                        src="{{ $animal->media->url() }}"
                                        style="width:80px; height:80px; object-fit: cover;"
                                        class="img-thumbnail"
                                        alt="{{ $animal->name }}">
                                @endif
                            </td>

                            <td>
                                {{ $animal->name }}
                                @if($animal->deleted_at)
                                    <span class="badge bg-danger ms-1">deleted</span>
                                @endif
                            </td>

                            <td>{{ $animal->breed }}<br><small class="text-muted">{{ $animal->color }}</small></td>
                            <td>{{ $animal->gender->label() }}</td>
                            
                            <td>
                                <span class="badge bg-{{ $animal->status->badgeVariant() }} text-dark border">
                                    {{ $animal->status->label() }}
                                </span>
                            </td>

                            <td>
                                @if($animal->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> Featured</span>
                                @endif
                                
                                @if($animal->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>

                            <td>{{ $animal->sort_order }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $animal)
                                        <a href="{{ route('backend.animals.show', $animal) }}" class="btn btn-sm btn-outline-primary">
                                            Show
                                        </a>
                                    @endcan

                                    @if(! $animal->deleted_at)
                                        @can('update', $animal)
                                            <a href="{{ route('backend.animals.edit', $animal) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $animal)
                                            <form method="POST" action="{{ route('backend.animals.destroy', $animal) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this animal?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $animal)
                                            <form method="POST" action="{{ route('backend.animals.restore', $animal->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Restore this animal?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $animal)
                                            <form method="POST" action="{{ route('backend.animals.forceDelete', $animal->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Permanently delete this animal? This cannot be undone.')">
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
                                No animals found. Try clearing filters.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Showing {{ $animals->firstItem() ?? 0 }} to {{ $animals->lastItem() ?? 0 }} of {{ $animals->total() }}
                </div>
                <div>
                    {{ $animals->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
