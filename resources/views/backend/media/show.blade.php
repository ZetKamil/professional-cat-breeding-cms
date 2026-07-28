<x-backend.shell title="Media details">

    <x-backend.page-header title="Media details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-image me-1"></i>
                    {{ $media->file_name }}
                </div>

                <div class="d-flex gap-2">
                    @if(! $media->deleted_at)
                        @can('update', $media)
                            <a href="{{ route('backend.media.edit', $media) }}" class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        @endcan
                    @endif

                    <a href="{{ route('backend.media.index') }}" class="btn btn-sm btn-outline-secondary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if($media->isImage())
                    <div class="mb-4">
                        <img src="{{ $media->url() }}" alt="{{ $media->alt_text ?? $media->file_name }}" class="img-fluid rounded border" style="max-height: 420px;">
                    </div>
                @endif

                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        <th style="width: 220px;">ID</th>
                        <td>{{ $media->id }}</td>
                    </tr>
                    <tr>
                        <th>File name</th>
                        <td>{{ $media->file_name }}</td>
                    </tr>
                    <tr>
                        <th>File path</th>
                        <td>{{ $media->file_path }}</td>
                    </tr>
                    <tr>
                        <th>Disk</th>
                        <td>{{ $media->disk }}</td>
                    </tr>
                    <tr>
                        <th>Mime type</th>
                        <td>{{ $media->mime_type ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>File size</th>
                        <td>{{ $media->file_size ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Parent type</th>
                        <td>{{ $media->parentLabel() }}</td>
                    </tr>
                    <tr>
                        <th>Parent record</th>
                        <td>
                            @if($media->mediable)
                                @if($media->mediable_type === \App\Models\Post::class)
                                    <a href="{{ route('backend.posts.show', $media->mediable) }}" class="text-decoration-none">
                                        Post #{{ $media->mediable->id }} - {{ $media->mediable->title }}
                                    </a>
                                @elseif($media->mediable_type === \App\Models\User::class)
                                    <a href="{{ route('backend.users.show', $media->mediable) }}" class="text-decoration-none">
                                        User #{{ $media->mediable->id }} - {{ $media->mediable->name }}
                                    </a>
                                @else
                                    {{ class_basename($media->mediable_type) }} #{{ $media->mediable->id }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Alt text</th>
                        <td>{{ $media->alt_text ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Caption</th>
                        <td>{{ $media->caption ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Sort order</th>
                        <td>{{ $media->sort_order }}</td>
                    </tr>
                    <tr>
                        <th>Featured</th>
                        <td>
                            @if($media->is_featured)
                                <span class="badge bg-success">yes</span>
                            @else
                                <span class="badge bg-secondary">no</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created at</th>
                        <td>{{ optional($media->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated at</th>
                        <td>{{ optional($media->updated_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Deleted at</th>
                        <td>{{ optional($media->deleted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
