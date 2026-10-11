<x-backend.shell title="Dodaj kota - Panel hodowli">

    <x-backend.page-header title="Dodaj nowego kota">
        
        <x-backend.card>
            <div class="card-header">
                <i class="fas fa-plus me-1"></i>
                Nowy kot w hodowli
            </div>
            
            <div class="card-body">
                <form action="{{ route('backend.animals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @include('backend.animals.partials.form', ['animal' => new \App\Models\Animal()])
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('backend.animals.index') }}" class="btn btn-outline-secondary">
                            Anuluj
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>
                            Zapisz kota
                        </button>
                    </div>
                </form>
            </div>
        </x-backend.card>

    </x-backend.page-header>

</x-backend.shell>
