<x-backend.shell title="Kategorie bloga - Panel Administracyjny">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Kategorie bloga">

        <form method="GET" action="{{ route('backend.categories.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-4">
                    <label class="form-label mb-1">Szukaj</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Szukaj po nazwie, slugu lub opisie..."
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

                    <a class="btn btn-outline-secondary" href="{{ route('backend.categories.index') }}">
                        Wyczyść
                    </a>

                    @can('create', \App\Models\Category::class)
                        <a href="{{ route('backend.categories.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            Nowa kategoria
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
                Lista kategorii
                <span class="text-muted ms-2">({{ $categories->total() }} łącznie)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('name') }}">Nazwa{!! $sortIcon('name') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('slug') }}">Slug{!! $sortIcon('slug') !!}</a></th>
                        <th>Opis</th>
                        <th>Liczba wpisów</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Utworzono{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Akcje</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                {{ $category->name }}
                                @if($category->deleted_at)
                                    <span class="badge bg-danger ms-1">w koszu</span>
                                @endif
                            </td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($category->description ?? '-', 80) }}</td>
                            <td>{{ $category->posts_count }}</td>
                            <td>{{ optional($category->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">

                                    <a href="{{ route('backend.categories.show', $category) }}" class="btn btn-sm btn-outline-primary" title="Podgląd">
                                        Podgląd
                                    </a>

                                    @if(! $category->deleted_at)
                                        <a href="{{ route('backend.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary" title="Edytuj">
                                            Edytuj
                                        </a>

                                        <form method="POST" action="{{ route('backend.categories.destroy', $category) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Czy na pewno chcesz przenieść tę kategorię do kosza?')">
                                                Usuń
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('backend.categories.restore', $category->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-success"
                                                    onclick="return confirm('Przywrócić tę kategorię z kosza?')">
                                                Przywróć
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('backend.categories.forceDelete', $category->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Czy na pewno chcesz bezpowrotnie usunąć tę kategorię? Tej operacji nie można cofnąć.')">
                                                Usuń trwale
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Nie znaleziono kategorii. Spróbuj wyczyścić filtry.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Wyświetlanie {{ $categories->firstItem() ?? 0 }} do {{ $categories->lastItem() ?? 0 }} z {{ $categories->total() }} kategorii
                </div>
                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
