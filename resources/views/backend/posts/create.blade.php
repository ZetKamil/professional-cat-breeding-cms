<x-backend.shell title="Nowy wpis na blogu">

    <x-backend.page-header title="Nowy wpis na blogu">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-file-circle-plus me-1"></i>
                Napisz nowy artykuł
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('backend.posts.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    @include('backend.posts.partials.form', [
                        'post'        => null,
                        'authors'     => $authors,
                        'categories'  => $categories,
                        'animals'     => $animals,
                        'submitLabel' => 'Utwórz post',
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
