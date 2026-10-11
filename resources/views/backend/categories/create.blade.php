<x-backend.shell title="Dodaj kategorię">

    <x-backend.page-header title="Dodaj nową kategorię">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-folder-plus me-1"></i>
                Nowa kategoria
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.categories.store') }}">
                    @csrf

                    @include('backend.categories.partials.form', [
                        'category' => null,
                        'submitLabel' => 'Dodaj kategorię',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
