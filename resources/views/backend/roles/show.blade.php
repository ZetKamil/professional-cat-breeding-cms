<x-backend.shell title="Role details">

    <x-backend.page-header title="Role details">

        <x-backend.card>
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-user-tag me-1"></i>
                    {{ $role->name }}
                </div>

                <div class="d-flex gap-2">
                    @if(! $role->deleted_at)
                        @can('update', $role)
                            <a href="{{ route('backend.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                Edit
                            </a>
                        @endcan
                    @endif

                    <a href="{{ route('backend.roles.index') }}" class="btn btn-sm btn-outline-secondary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered mb-4">
                    <tbody>
                    <tr>
                        <th style="width: 220px;">ID</th>
                        <td>{{ $role->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $role->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Users linked</th>
                        <td>{{ $role->users_count ?? $role->users->count() }}</td>
                    </tr>
                    <tr>
                        <th>Created at</th>
                        <td>{{ optional($role->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated at</th>
                        <td>{{ optional($role->updated_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Deleted at</th>
                        <td>{{ optional($role->deleted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                    </tbody>
                </table>

                <h5 class="mb-3">Linked users</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($role->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <a href="{{ route('backend.users.show', $user) }}" class="text-decoration-none">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">active</span>
                                    @else
                                        <span class="badge bg-secondary">inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No users linked to this role.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
