<x-backend.shell title="Create post">

    <x-backend.page-header title="Create post">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-file-circle-plus me-1"></i>
                New post
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('backend.posts.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    @include('backend.posts.partials.form', [
                        'post' => null,
                        'authors' => $authors,
                        'categories' => $categories,
                        'submitLabel' => 'Create post',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
