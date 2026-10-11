<x-backend.shell title="Wpisy na blogu - Panel Administracyjny">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Wpisy na blogu">

        <form method="GET" action="{{ route('backend.posts.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Szukaj</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Szukaj po tytule, skrócie lub treści..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Autor</label>
                    <select name="author" class="form-select">
                        <option value="">Wszyscy autorzy</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected((string) $filters['author'] === (string) $author->id)>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Kategoria</label>
                    <select name="category" class="form-select">
                        <option value="">Wszystkie kategorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category'] === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Wszystkie</option>
                        <option value="published" @selected($filters['status'] === 'published')>Opublikowane</option>
                        <option value="draft" @selected($filters['status'] === 'draft')>Szkice</option>
                    </select>
                </div>

                <div class="col-12 col-md-1">
                    <label class="form-label mb-1">Na stronę</label>
                    <select name="per_page" class="form-select">
                        @foreach($perPageAllowed as $n)
                            <option value="{{ $n }}" @selected((int) $filters['per_page'] === (int) $n)>{{ $n }}</option>
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

                {{-- Huidige sort state bewaren --}}
                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter me-1"></i> Filtruj
                    </button>

                    <a class="btn btn-outline-secondary" href="{{ route('backend.posts.index') }}">
                        Wyczyść
                    </a>

                    @can('create', \App\Models\Post::class)
                        <form method="POST" action="{{ route('backend.posts.importSeo') }}" class="d-inline ms-auto" onsubmit="return confirm('Czy na pewno chcesz zaimportować/zaktualizować 19 artykułów SEO z katalogu BLOG/ z niedzielnym harmonogramem?');">
                            @csrf
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="fas fa-file-import me-1"></i>
                                🚀 Importuj Artykuły SEO (19)
                            </button>
                        </form>
                        <a href="{{ route('backend.blog-cta.edit') }}" class="btn btn-outline-warning text-dark fw-bold ms-2">
                            <i class="fas fa-bullhorn me-1"></i>
                            Oferta pod artykułami
                        </a>
                        <a href="{{ route('backend.posts.create') }}" class="btn btn-success ms-2">
                            <i class="fas fa-plus me-1"></i>
                            Nowy wpis
                        </a>
                    @endcan
                </div>
            </div>
        </form>

        @php
            $sortUrl = function (string $col) use ($filters) {
                $newDir = ($filters['sort'] === $col && $filters['dir'] === 'asc') ? 'desc' : 'asc';

                return route('backend.posts.index', [
                    'q' => $filters['q'],
                    'author' => $filters['author'],
                    'category' => $filters['category'],
                    'status' => $filters['status'],
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
                Lista wpisów
                <span class="text-muted ms-2">({{ $posts->total() }} łącznie)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Zdjęcie</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('title') }}">Tytuł{!! $sortIcon('title') !!}</a></th>
                        <th>Autor</th>
                        <th>Kategorie</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('is_published') }}">Status{!! $sortIcon('is_published') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('published_at') }}">Data publikacji{!! $sortIcon('published_at') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Utworzono{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Akcje</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>

                                @if($post->media)

                                    <img
                                        src="{{ $post->media->url() }}"
                                        style="width:80px"
                                        class="img-thumbnail">

                                @endif

                            </td>

                            <td>{{ $post->id }}</td>

                            <td>
                                {{ $post->title }}

                                @if($post->deleted_at)
                                    <span class="badge bg-danger ms-1">w koszu</span>
                                @endif
                            </td>

                            <td>{{ $post->user?->name ?? '-' }}</td>

                            <td>
                                @forelse($post->categories as $category)
                                    <span class="badge bg-info text-dark me-1">{{ $category->name }}</span>
                                @empty
                                    -
                                @endforelse
                            </td>

                            <td>
                                @if($post->is_published)
                                    <span class="badge bg-success">opublikowany</span>
                                @else
                                    <span class="badge bg-secondary">szkic</span>
                                @endif
                            </td>

                            <td>{{ optional($post->published_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>{{ optional($post->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $post)
                                        <a href="{{ route('backend.posts.show', $post) }}" class="btn btn-sm btn-outline-primary" title="Podgląd">
                                            Podgląd
                                        </a>
                                    @endcan

                                    @if(! $post->deleted_at)
                                        @can('update', $post)
                                            <a href="{{ route('backend.posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary" title="Edytuj">
                                                Edytuj
                                            </a>
                                        @endcan

                                        @can('delete', $post)
                                            <form method="POST" action="{{ route('backend.posts.destroy', $post) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Czy na pewno chcesz przenieść ten wpis do kosza?')">
                                                    Usuń
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $post)
                                            <form method="POST" action="{{ route('backend.posts.restore', $post->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Przywrócić ten wpis z kosza?')">
                                                    Przywróć
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $post)
                                            <form method="POST" action="{{ route('backend.posts.forceDelete', $post->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Czy na pewno chcesz bezpowrotnie usunąć ten wpis? Tej operacji nie można cofnąć.')">
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
                            <td colspan="8" class="text-center text-muted py-4">
                                Nie znaleziono wpisów. Spróbuj wyczyścić filtry.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Wyświetlanie {{ $posts->firstItem() ?? 0 }} do {{ $posts->lastItem() ?? 0 }} z {{ $posts->total() }} wpisów
                </div>
                <div>
                    {{ $posts->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
