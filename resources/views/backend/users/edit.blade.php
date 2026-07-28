<x-backend.shell title="Edit user">

    <x-backend.page-header title="Edit user">

        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-user-pen me-1"></i>
                Edit: {{ $user->name }}
            </div>

            <div class="card-body">

                {{--
                    Update route verwacht PUT/PATCH.
                    HTML kan geen PUT/PATCH, dus:
                    - method="POST"
                    - @method('PATCH') als method spoofing
                --}}
                <form method="POST"
                      action="{{ route('backend.users.update',$user) }}"
                      enctype="multipart/form-data">

                @csrf
                    @method('PATCH')

                    {{-- Hergebruik dezelfde feature partial --}}
                    @include('backend.users.partials.form', [
                        'user' => $user,
                        'roles' => $roles,
                        'submitLabel' => 'Save changes',
                        'isEdit' => true,
                    ])
                </form>

            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>
