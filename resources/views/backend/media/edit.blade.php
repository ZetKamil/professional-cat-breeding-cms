<x-backend.shell title="Edit media">

    <x-backend.page-header title="Edit media">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-pen me-1"></i>
                Edit: {{ $media->file_name }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.media.update', $media) }}">
                    @csrf
                    @method('PATCH')

                    @include('backend.media.partials.form', [
                        'media' => $media,
                        'posts' => $posts,
                        'users' => $users,
                        'submitLabel' => 'Save changes',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
