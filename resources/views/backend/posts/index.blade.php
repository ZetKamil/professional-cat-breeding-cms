<x-backend.shell title="Posts - SB Admin">

    <x-slot:head>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </x-slot:head>

    <x-backend.page-header title="Posts">

        <form method="GET" action="{{ route('backend.posts.index') }}" class="mb-4">
            <div class="row g-2 align-items-end">

                <div class="col-12 col-md-3">
                    <label class="form-label mb-1">Search</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        class="form-control"
                        placeholder="Search title, slug, excerpt or body..."
                    >
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Author</label>
                    <select name="author" class="form-select">
                        <option value="">All authors</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected((string) $filters['author'] === (string) $author->id)>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label mb-1">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All categories</option>
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
                        <option value="">All</option>
                        <option value="published" @selected($filters['status'] === 'published')>Published</option>
                        <option value="draft" @selected($filters['status'] === 'draft')>Draft</option>
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

                {{-- Huidige sort state bewaren --}}
                <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                <input type="hidden" name="dir" value="{{ $filters['dir'] }}">

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary" type="submit">Apply</button>

                    <a class="btn btn-outline-secondary" href="{{ route('backend.posts.index') }}">
                        Clear
                    </a>

                    @can('create', \App\Models\Post::class)
                        <a href="{{ route('backend.posts.create') }}" class="btn btn-success ms-auto">
                            <i class="fas fa-plus me-1"></i>
                            New post
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
                Posts lijst
                <span class="text-muted ms-2">({{ $posts->total() }} totaal)</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('id') }}">ID{!! $sortIcon('id') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('title') }}">Title{!! $sortIcon('title') !!}</a></th>
                        <th>Author</th>
                        <th>Categories</th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('is_published') }}">Status{!! $sortIcon('is_published') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('published_at') }}">Published{!! $sortIcon('published_at') !!}</a></th>
                        <th><a class="text-decoration-none" href="{{ $sortUrl('created_at') }}">Created{!! $sortIcon('created_at') !!}</a></th>
                        <th class="text-end">Actions</th>
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
                                    <span class="badge bg-danger ms-1">deleted</span>
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
                                    <span class="badge bg-success">published</span>
                                @else
                                    <span class="badge bg-secondary">draft</span>
                                @endif
                            </td>

                            <td>{{ optional($post->published_at)->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>{{ optional($post->created_at)->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @can('view', $post)
                                        <a href="{{ route('backend.posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                            Show
                                        </a>
                                    @endcan

                                    @if(! $post->deleted_at)
                                        @can('update', $post)
                                            <a href="{{ route('backend.posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $post)
                                            <form method="POST" action="{{ route('backend.posts.destroy', $post) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('restore', $post)
                                            <form method="POST" action="{{ route('backend.posts.restore', $post->id) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Restore this post?')">
                                                    Restore
                                                </button>
                                            </form>
                                        @endcan

                                        @can('forceDelete', $post)
                                            <form method="POST" action="{{ route('backend.posts.forceDelete', $post->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Permanently delete this post? This cannot be undone.')">
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
                                No posts found. Try clearing filters.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }}
                </div>
                <div>
                    {{ $posts->links() }}
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
