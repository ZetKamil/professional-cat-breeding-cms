<x-backend.shell title="Dodaj użytkownika">

    <x-backend.page-header title="Dodaj użytkownika">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-plus me-1"></i>
                Nowy użytkownik
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('backend.users.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    @include('backend.users.partials.form', [
                        'user' => null,
                        'roles' => $roles,
                        'submitLabel' => 'Dodaj użytkownika',
                        'isEdit' => false,
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
