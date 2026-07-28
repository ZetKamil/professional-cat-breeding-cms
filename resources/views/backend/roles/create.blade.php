<x-backend.shell title="Create role">

    <x-backend.page-header title="Create role">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-tag me-1"></i>
                New role
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.roles.store') }}">
                    @csrf

                    @include('backend.roles.partials.form', [
                        'role' => null,
                        'submitLabel' => 'Create role',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
