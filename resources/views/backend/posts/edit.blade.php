<x-backend.shell title="Edit post">

    <x-backend.page-header title="Edit post">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-file-pen me-1"></i>
                Edit: {{ $post->title }}
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('backend.posts.update',$post) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @include('backend.posts.partials.form', [
                        'post' => $post,
                        'authors' => $authors,
                        'categories' => $categories,
                        'submitLabel' => 'Save changes',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
