<x-backend.shell title="Edycja mediów: {{ $media->file_name }}">

    <x-backend.page-header title="Edycja mediów: {{ $media->file_name }}">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-pen me-1"></i>
                Edycja: {{ $media->file_name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.media.update', $media) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @include('backend.media.partials.form', [
                        'media' => $media,
                        'posts' => $posts,
                        'users' => $users,
                        'animals' => $animals,
                        'submitLabel' => 'Zapisz zmiany',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
