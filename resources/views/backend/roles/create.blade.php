<x-backend.shell title="Dodaj rolę">

    <x-backend.page-header title="Dodaj nową rolę">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-tag me-1"></i>
                Nowa rola
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.roles.store') }}">
                    @csrf

                    @include('backend.roles.partials.form', [
                        'role' => null,
                        'submitLabel' => 'Dodaj rolę',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
