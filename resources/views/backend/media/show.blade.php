<x-backend.shell title="Media details - SB Admin">

    <x-backend.page-header title="Media details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-image me-1"></i>
                    {{ $media->title ?: $media->filename }}
                </div>

                <div class="d-flex gap-2">
                    @can('update', $media)
                        <a href="{{ route('backend.media.edit', $media) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    @endcan

                    <a href="{{ route('backend.media.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Library
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-12 col-md-5 text-center">
                        <div class="bg-light border rounded p-3 mb-3 d-flex align-items-center justify-content-center" style="min-height: 280px;">
                            @if($media->isImage())
                                <img src="{{ $media->url() }}" alt="{{ $media->alt_text ?: $media->filename }}" class="img-fluid rounded shadow-sm" style="max-height: 360px;">
                            @else
                                <div>
                                    <i class="fas fa-file fa-4x text-muted mb-2"></i>
                                    <div class="small text-muted">{{ $media->filename }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="input-group input-group-sm">
                            <span class="input-group-text">URL</span>
                            <input type="text" id="mediaUrlInput" class="form-control" value="{{ $media->url() }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="copyUrlBtn">
                                <i class="fas fa-copy me-1"></i> Copy URL
                            </button>
                        </div>
                    </div>

                    <div class="col-12 col-md-7">
                        <table class="table table-bordered mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;" class="table-light">ID</th>
                                    <td>{{ $media->id }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">File Name</th>
                                    <td class="fw-semibold">{{ $media->filename }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Title / Headline</th>
                                    <td>{{ $media->title ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">ALT Text (SEO)</th>
                                    <td>
                                        @if($media->alt_text)
                                            <span class="badge bg-success-subtle text-success-emphasis">{{ $media->alt_text }}</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="table-light">Caption</th>
                                    <td>{{ $media->caption ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Copyright</th>
                                    <td>{{ $media->copyright ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Attached To</th>
                                    <td>
                                        @if($media->mediable)
                                            <span class="badge bg-info text-dark">
                                                {{ class_basename($media->mediable_type) }} #{{ $media->mediable_id }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Unattached (Library)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="table-light">Sort Order / Featured</th>
                                    <td>
                                        Sort: <strong>{{ $media->sort_order }}</strong>
                                        @if($media->is_featured)
                                            <span class="badge bg-warning text-dark ms-2">Featured</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="table-light">MIME Type & Size</th>
                                    <td>
                                        {{ $media->mime_type ?: 'unknown' }} | {{ round($media->size / 1024, 1) }} KB
                                    </td>
                                </tr>
                                <tr>
                                    <th class="table-light">Uploaded At</th>
                                    <td>{{ $media->created_at?->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const copyBtn = document.getElementById('copyUrlBtn');
            if (copyBtn) {
                copyBtn.addEventListener('click', function () {
                    const input = document.getElementById('mediaUrlInput');
                    input.select();
                    navigator.clipboard.writeText(input.value);
                    copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="fas fa-copy me-1"></i> Copy URL';
                    }, 2000);
                });
            }
        });
    </script>
</x-backend.shell>
