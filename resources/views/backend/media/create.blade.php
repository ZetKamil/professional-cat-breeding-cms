<x-backend.shell title="Create media">

    <x-backend.page-header title="Create media">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-photo-film me-1"></i>
                New media
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('backend.media.store') }}" enctype="multipart/form-data">
                    @csrf

                    @include('backend.media.partials.form', [
                        'media' => null,
                        'posts' => $posts,
                        'users' => $users,
                        'prefillType' => $prefillType,
                        'prefillId' => $prefillId,
                        'submitLabel' => 'Create media',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
