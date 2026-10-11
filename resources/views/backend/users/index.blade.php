<x-backend.shell title="Użytkownicy - Panel Administracyjny">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Użytkownicy">

        {{-- Filterbalk --}}
        <form method="GET" action="{{ route('backend.users.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Szukaj</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Szukaj po nazwisku lub e-mailu..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Rola</label>
                    <select name="role" class="form-select">
                        <option value="">Wszystkie role</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" @selected((string)$filters['role'] === (string)$r->id)>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="active" @selected($filters['status'] === 'active')>Aktywni</option>
                        <option value="inactive" @selected($filters['status'] === 'inactive')>Nieaktywni</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Weryfikacja e-mail</label>
                    <select name="verified" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="yes" @selected($filters['verified'] === 'yes')>Zweryfikowany</option>
                        <option value="no" @selected($filters['verified'] === 'no')>Niezweryfikowany</option>
                    </select>
                </div>

                <div class="col-12 col-md-1">
                    <label class="form-label mb-1">Na stronę</label>
                    <select name="per_page" class="form-select">
                        @foreach($perPageAllowed as $n)
                            <option value="{{ $n }}" @selected((int)$filters['per_page'] === (int)$n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-1">
                    <label class="form-label mb-1">Kosz</label>
                    <select name="trashed" class="form-select">
                        <option value="">Aktywni</option>
                        <option value="with" @selected($filters['trashed'] === 'with')>Wszyscy</option>
                        <option value="only" @selected($filters['trashed'] === 'only')>W koszu</option>
                    </select>
                </div>
                {{-- Sort state --}}
                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter me-1"></i> Filtruj
                    </button>

                    <a class="btn btn-outline-secondary" href="{{ route('backend.users.index') }}">
                        Wyczyść
                    </a>

                    @can('create', \App\Models\User::class)
                        <a href="{{ route('backend.users.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            Dodaj użytkownika
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        {{-- Sort helpers --}}
        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.users.index', [
                    'q' => $filters['q'],
                    'role' => $filters['role'],
                    'status' => $filters['status'],
                    'verified' => $filters['verified'],
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
                Lista użytkowników
                <span class="text-muted ms-2">({{ $users->total() }} łącznie)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Zdjęcie</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('name') }}">Imię i nazwisko{!! $sortIcon('name') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('email') }}">E-mail{!! $sortIcon('email') !!}</a></th>
                        <th>Rola</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('is_active') }}">Status{!! $sortIcon('is_active') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Utworzono{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Akcje</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                @if($user->media)
                                    <img
                                        src="{{ $user->media->url() }}"
                                        alt="{{ $user->name }}"
                                        class="img-thumbnail"
                                        style="width: 60px; height: 60px; object-fit: cover;"
                                    >
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role?->name ?? '-' }}</td>

                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">aktywny</span>
                                @else
                                    <span class="badge bg-secondary">nieaktywny</span>
                                @endif

                                @if($user->deleted_at)
                                    <span class="badge bg-danger ms-1">w koszu</span>
                                @endif
                            </td>

                            <td>{{ optional($user->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $user)
                                        <a href="{{ route('backend.users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="Podgląd">
                                            Podgląd
                                        </a>
                                    @endcan

                                    @if(! $user->deleted_at)
                                        @can('update', $user)
                                            <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Edytuj">
                                                Edytuj
                                            </a>
                                        @endcan

                                        @can('delete', $user)
                                            <form method="POST" action="{{ route('backend.users.destroy', $user) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Czy na pewno chcesz przenieść tego użytkownika do kosza?')">
                                                    Usuń
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $user)
                                            <form method="POST" action="{{ route('backend.users.restore', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Przywrócić tego użytkownika z kosza?')">
                                                    Przywróć
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $user)
                                            <form method="POST" action="{{ route('backend.users.forceDelete', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Czy na pewno chcesz bezpowrotnie usunąć tego użytkownika? Tej operacji nie można cofnąć.')">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                Nie znaleziono użytkowników. Spróbuj wyczyścić filtry.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Wyświetlanie {{ $users->firstItem() ?? 0 }} do {{ $users->lastItem() ?? 0 }} z {{ $users->total() }} użytkowników
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
