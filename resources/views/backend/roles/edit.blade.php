<x-backend.shell title="Edycja roli: {{ $role->name }}">

    <x-backend.page-header title="Edycja roli: {{ $role->name }}">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-pen me-1"></i>
                Edycja: {{ $role->name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.roles.update', $role) }}">
                    @csrf
                    @method('PATCH')

                    @include('backend.roles.partials.form', [
                        'role' => $role,
                        'submitLabel' => 'Zapisz zmiany',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
