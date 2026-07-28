<x-backend.shell title="Category details">

    <x-backend.page-header title="Category details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-folder me-1"></i>
                    {{ $category->name }}
                </div>

                <div class="d-flex gap-2">
                    @if(! $category->deleted_at)
                        @can('update', $category)
                            <a href="{{ route('backend.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        @endcan
                    @endif

                    <a href="{{ route('backend.categories.index') }}" class="btn btn-sm btn-outline-secondary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th style="width: 220px;">ID</th>
                        <td>{{ $category->id }}</td>
                    </tr>

                    <tr>
                        <th>Name</th>
                        <td>{{ $category->name }}</td>
                    </tr>

                    <tr>
                        <th>Slug</th>
                        <td>{{ $category->slug }}</td>
                    </tr>

                    <tr>
                        <th>Description</th>
                        <td>{{ $category->description ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Linked posts</th>
                        <td>{{ $category->posts_count ?? 0 }}</td>
                    </tr>

                    <tr>
                        <th>Created at</th>
                        <td>{{ optional($category->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Updated at</th>
                        <td>{{ optional($category->updated_at)->format('Y-m-d H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Deleted at</th>
                        <td>{{ optional($category->deleted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
