<x-backend.shell title="Edit role">

    <x-backend.page-header title="Edit role">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-pen me-1"></i>
                Edit: {{ $role->name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.roles.update', $role) }}">
                    @csrf
                    @method('PATCH')

                    @include('backend.roles.partials.form', [
                        'role' => $role,
                        'submitLabel' => 'Save changes',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
