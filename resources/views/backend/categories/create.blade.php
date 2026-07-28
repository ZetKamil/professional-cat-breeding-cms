<x-backend.shell title="Create category">

    <x-backend.page-header title="Create category">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-folder-plus me-1"></i>
                New category
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.categories.store') }}">
                    @csrf

                    @include('backend.categories.partials.form', [
                        'category' => null,
                        'submitLabel' => 'Create category',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
