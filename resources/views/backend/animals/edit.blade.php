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
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
