<x-backend.shell title="Edycja użytkownika: {{ $user->name }}">

    <x-backend.page-header title="Edycja użytkownika: {{ $user->name }}">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-pen me-1"></i>
                Edycja: {{ $user->name }}
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('backend.users.update', $user) }}"
                      enctype="multipart/form-data">
                @csrf
                    @method('PATCH')

                    @include('backend.users.partials.form', [
                        'user' => $user,
                        'roles' => $roles,
                        'submitLabel' => 'Zapisz zmiany',
                        'isEdit' => true,
                    ])
                </form>

            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
