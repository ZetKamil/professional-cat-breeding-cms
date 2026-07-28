<x-backend.shell title="Edit category">

    <x-backend.page-header title="Edit category">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-folder-open me-1"></i>
                Edit: {{ $category->name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.categories.update', $category) }}">
                    @csrf
                    @method('PATCH')

                    @include('backend.categories.partials.form', [
                        'category' => $category,
                        'submitLabel' => 'Save changes',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
