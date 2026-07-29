<x-backend.shell title="New Animal - SB Admin">

    <x-backend.page-header title="New Animal">
        
        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-plus me-1"></i>
                Add new animal
            </div>
            
            <div class="card-body">
                <form action="{{ route('backend.animals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @include('backend.animals.partials.form', ['animal' => new \App\Models\Animal()])
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('backend.animals.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>
                            Save Animal
                        </button>
                    </div>
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
