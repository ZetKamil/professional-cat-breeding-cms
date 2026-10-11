<x-backend.shell title="Dodaj media">

    <x-backend.page-header title="Dodaj media">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-photo-film me-1"></i>
                Nowe pliki multimedialne
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.media.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('backend.media.partials.form', [
                        'media' => null,
                        'posts' => $posts,
                        'users' => $users,
                        'animals' => $animals,
                        'prefillType' => $prefillType,
                        'prefillId' => $prefillId,
                        'submitLabel' => 'Wgraj pliki mediów',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
