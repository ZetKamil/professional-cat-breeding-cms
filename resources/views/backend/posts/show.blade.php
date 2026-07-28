<x-backend.shell title="Post details">

    <x-backend.page-header title="Post details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                @if($post->media)
                    <div class="mb-3">
                        <img
                            src="{{ $post->media->url() }}"
                            class="img-fluid rounded">
                    </div>
                @endif

                <div>
                    <i class="fas fa-file-lines me-1"></i>
                    {{ $post->title }}
                </div>

                <div class="d-flex gap-2">
                    @if(! $post->deleted_at)
                        @can('update', $post)
                            <a href="{{ route('backend.posts.edit', $post) }}" class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        @endcan
                    @endif

                    <a href="{{ route('backend.posts.index') }}" class="btn btn-sm btn-outline-secondary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered mb-4">
                    <tbody>
                    <tr>
                        <th style="width: 220px;">ID</th>
                        <td>{{ $post->id }}</td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $post->title }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $post->slug }}</td>
                    </tr>
                    <tr>
                        <th>Author</th>
                        <td>{{ $post->user?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Created by</th>
                        <td>{{ $post->creator?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Last updated by</th>
                        <td>{{ $post->editor?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($post->is_published)
                                <span class="badge bg-success">published</span>
                            @else
                                <span class="badge bg-secondary">draft</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Published at</th>
                        <td>{{ optional($post->published_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Excerpt</th>
                        <td>{{ $post->excerpt ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Body</th>
                        <td style="white-space: pre-wrap;">{{ $post->body }}</td>
                    </tr>
                    <tr>
                        <th>Categories</th>
                        <td>
                            @forelse($post->categories as $category)
                                <span class="badge bg-info text-dark me-1">{{ $category->name }}</span>
                            @empty
                                -
                            @endforelse
                        </td>
                    </tr>
                    <tr>
                        <th>Created at</th>
                        <td>{{ optional($post->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated at</th>
                        <td>{{ optional($post->updated_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Deleted at</th>
                        <td>{{ optional($post->deleted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
