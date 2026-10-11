<x-backend.shell title="Edycja kategorii: {{ $category->name }}">

    <x-backend.page-header title="Edycja kategorii: {{ $category->name }}">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-folder-open me-1"></i>
                Edycja: {{ $category->name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.categories.update', $category) }}">
                    @csrf
                    @method('PATCH')

                    @include('backend.categories.partials.form', [
                        'category' => $category,
                        'submitLabel' => 'Zapisz zmiany',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
