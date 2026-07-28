<x-backend.shell title="User details">

    <x-backend.page-header title="User details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-id-card me-1"></i>
                    {{ $user->name }}
                </div>

                <div class="d-flex gap-2">
                    @can('update', $user)
                        <a href="{{ route('backend.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                            Edit
                        </a>
                    @endcan

                    <a href="{{ route('backend.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{-- Read-only overzicht, Bootstrap table --}}
                <table class="table table-bordered mb-0">
                    <tbody>
                    <tr>
                        @if($user->media)
                            <img
                                src="{{ $user->media->url() }}"
                                alt="{{ $user->name }}"
                                class="img-thumbnail mb-1"
                                style="width: 60px; height: 60px; object-fit: cover;"
                            >
                        @else
                            -
                        @endif
                    </tr>
                    <tr>
                        <th style="width: 220px;">ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>

                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th>Role</th>
                        <td>{{ $user->role?->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">active</span>
                            @else
                                <span class="badge bg-secondary">inactive</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Verified</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-info">yes</span>
                                <span class="text-muted ms-2">{{ $user->email_verified_at->format('Y-m-d H:i') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">no</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Created at</th>
                        <td>{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Updated at</th>
                        <td>{{ optional($user->updated_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Deleted at</th>
                        <td>{{ optional($user->deleted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
