<x-backend.shell title="AI Blog Studio">

    <x-backend.page-header title="AI Blog Studio">

        <x-backend.card>
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fas fa-wand-magic-sparkles text-primary"></i>
                <span class="fw-semibold">AI Blog Studio</span>
                <span class="badge bg-secondary ms-1">Beta</span>
                <span class="ms-auto text-muted small">
                    Generuje szkic artykułu na podstawie danych hodowli + Gemini AI
                </span>
            </div>

            <div class="card-body">
                @livewire('admin.ai-blog-studio')
            </div>
        </x-backend.card>

    </x-backend.page-header>
</x-backend.shell>