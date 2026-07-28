<x-backend.shell title="Create user">

    {{-- Page header: consistent met index --}}
    <x-backend.page-header title="Create user">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-plus me-1"></i>
                New user
            </div>

            <div class="card-body">
                {{--
                    POST naar backend.users.store (resource route).
                    @csrf is verplicht in Laravel voor beveiliging.
                --}}
                <form method="POST"
                      action="{{ route('backend.users.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    {{--
                        Form partial in partials/:
                        - schaalbaar bij grotere feature (meerdere partials)
                        - create en edit kunnen dit delen
                    --}}
                    @include('backend.users.partials.form', [
                        'user' => null,                 // create: geen bestaand model
                        'roles' => $roles,               // dropdown data
                        'submitLabel' => 'Create user',  // knoptekst
                        'isEdit' => false,               // later nuttig bij edit (password optional)
                    ])
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
