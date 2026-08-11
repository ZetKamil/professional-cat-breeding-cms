<x-backend.shell title="Edit Animal - SB Admin">

    <x-backend.page-header title="Edit Animal: {{ $animal->name }}">
        
        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Edit animal
            </div>
            
            <div class="card-body">
                <form action="{{ route('backend.animals.update', $animal) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @include('backend.animals.partials.form', ['animal' => $animal])
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('backend.animals.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Animal
                        </button>
                    </div>
                </form>

                {{-- Hidden Delete Form for Main Featured Image --}}
                @if($animal->media)
                    <form id="delete-main-photo-form" action="{{ route('backend.media.destroy', $animal->media) }}" method="POST" class="d-none" onsubmit="return confirm('Czy na pewno chcesz usunąć zdjęcie główne?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif

                {{-- Hidden Delete Forms for Gallery Images --}}
                @if($animal->gallery)
                    @foreach($animal->gallery as $img)
                        <form id="delete-media-{{ $img->id }}" action="{{ route('backend.media.destroy', $img) }}" method="POST" class="d-none" onsubmit="return confirm('Usuń to zdjęcie z galerii?');">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
