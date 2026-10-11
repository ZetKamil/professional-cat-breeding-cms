<x-backend.shell title="Role i uprawnienia - Panel Administracyjny">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Role i uprawnienia">

        <form method="GET" action="{{ route('backend.roles.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-6">
                    <label class="form-label mb-1">Szukaj</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Szukaj po nazwie roli lub opisie..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Na stronę</label>
                    <select name="per_page" class="form-select">
                        @foreach($perPageAllowed as $n)
                            <option value="{{ $n }}" @selected((int)$filters['per_page'] === (int)$n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Kosz / Usunięte</label>
                    <select name="trashed" class="form-select">
                        <option value="">Tylko aktywne</option>
                        <option value="with" @selected($filters['trashed'] === 'with')>Aktywne i w koszu</option>
                        <option value="only" @selected($filters['trashed'] === 'only')>Tylko w koszu</option>
                    </select>
                </div>

                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter me-1"></i> Filtruj
                    </button>

                    <a class="btn btn-outline-secondary" href="{{ route('backend.roles.index') }}">
                        Wyczyść
                    </a>

                    @can('create', \App\Models\Role::class)
                        <a href="{{ route('backend.roles.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            Nowa rola
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.roles.index', [
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
                Lista ról
                <span class="text-muted ms-2">({{ $roles->total() }} łącznie)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('name') }}">Nazwa roli{!! $sortIcon('name') !!}</a></th>
                        <th>Opis</th>
                        <th>Liczba użytkowników</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Utworzono{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Akcje</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                {{ $role->name }}

                                @if($role->deleted_at)
                                    <span class="badge bg-danger ms-1">w koszu</span>
                                @endif
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($role->description ?? '-', 80) }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td>{{ optional($role->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $role)
                                        <a href="{{ route('backend.roles.show', $role) }}" class="btn btn-sm btn-outline-primary" title="Podgląd">
                                            Podgląd
                                        </a>
                                    @endcan

                                    @if(! $role->deleted_at)
                                        @can('update', $role)
                                            <a href="{{ route('backend.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary" title="Edytuj">
                                                Edytuj
                                            </a>
                                        @endcan

                                        @can('delete', $role)
                                            <form method="POST" action="{{ route('backend.roles.destroy', $role) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Czy na pewno chcesz przenieść tę rolę do kosza?')">
                                                    Usuń
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $role)
                                            <form method="POST" action="{{ route('backend.roles.restore', $role->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Przywrócić tę rolę z kosza?')">
                                                    Przywróć
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $role)
                                            <form method="POST" action="{{ route('backend.roles.forceDelete', $role->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Czy na pewno chcesz bezpowrotnie usunąć tę rolę? Tej operacji nie można cofnąć.')">
                                                    Usuń trwale
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nie znaleziono ról. Spróbuj wyczyścić filtry.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Wyświetlanie {{ $roles->firstItem() ?? 0 }} do {{ $roles->lastItem() ?? 0 }} z {{ $roles->total() }} ról
                </div>
                <div>
                    {{ $roles->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
