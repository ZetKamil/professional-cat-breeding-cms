<x-backend.shell title="Pulpit nawigacyjny - Hodowla Kotów z Mazowieckiej Szwajcarii">

    <x-backend.page-header title="Pulpit nawigacyjny">

        {{-- Powitanie i Szybkie Akcje --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-dark text-white border-0 shadow-sm overflow-hidden" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1 fw-bold text-warning">Witaj, {{ auth()->user()->name }}! 👋</h4>
                            <p class="mb-0 text-white-50">Zarządzaj kotami, publikacjami na blogu oraz biblioteką mediów w jednym miejscu.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('backend.animals.create') }}" class="btn btn-warning fw-semibold shadow-sm">
                                <i class="fas fa-plus me-1"></i> Dodaj kota
                            </a>
                            <a href="{{ route('backend.posts.create') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-pen me-1"></i> Napisz artykuł
                            </a>
                            <a href="{{ route('backend.media.index') }}" class="btn btn-outline-light fw-semibold">
                                <i class="fas fa-images me-1"></i> Wgraj media
                            </a>
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-light text-dark fw-semibold">
                                <i class="fas fa-external-link-alt me-1"></i> Podgląd strony
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4 Karty Statystyk (Prawdziwe dane z bazy SQL) --}}
        <div class="row g-3 mb-4">

            {{-- Karta Koty --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold small">Koty i Kocięta</span>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-2 text-warning">
                                <i class="fas fa-paw fa-lg"></i>
                            </div>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">{{ $totalAnimals }}</h2>
                        <p class="small text-muted mb-0">
                            <span class="text-success fw-bold">{{ $availableAnimals }}</span> dostępnych ·
                            <span class="text-warning fw-bold">{{ $reservedAnimals }}</span> zarezerwowanych
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <a href="{{ route('backend.animals.index') }}" class="small text-warning fw-bold text-decoration-none d-flex align-items-center justify-content-between">
                            <span>Zarządzaj kotami</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Karta Artykuły --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold small">Wpisy i Artykuły</span>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                                <i class="fas fa-newspaper fa-lg"></i>
                            </div>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">{{ $totalPosts }}</h2>
                        <p class="small text-muted mb-0">
                            <span class="text-success fw-bold">{{ $publishedPosts }}</span> opublikowanych ·
                            <span class="text-secondary fw-bold">{{ $draftPosts }}</span> szkiców
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <a href="{{ route('backend.posts.index') }}" class="small text-primary fw-bold text-decoration-none d-flex align-items-center justify-content-between">
                            <span>Przeglądaj artykuły</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Karta Media --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold small">Biblioteka Mediów</span>
                            <div class="rounded-circle bg-success bg-opacity-10 p-2 text-success">
                                <i class="fas fa-images fa-lg"></i>
                            </div>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">{{ $totalMedia }}</h2>
                        <p class="small text-muted mb-0">
                            Pliki wgrane do galerii i wpisów
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <a href="{{ route('backend.media.index') }}" class="small text-success fw-bold text-decoration-none d-flex align-items-center justify-content-between">
                            <span>Otwórz menedżer mediów</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Karta Użytkownicy --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-info" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase text-muted fw-bold small">Użytkownicy / Admini</span>
                            <div class="rounded-circle bg-info bg-opacity-10 p-2 text-info">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">{{ $totalUsers }}</h2>
                        <p class="small text-muted mb-0">
                            Konta z dostępem do zaplecza
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0 pb-3">
                        <a href="{{ route('backend.users.index') }}" class="small text-info fw-bold text-decoration-none d-flex align-items-center justify-content-between">
                            <span>Zarządzaj kontami</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabele Rzeczywistych Danych --}}
        <div class="row g-4 mb-4">

            {{-- Tabela 1: Ostatnio Dodane Koty --}}
            <div class="col-xl-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 pt-3 pb-2 d-flex align-items-center justify-content-between">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fas fa-cat text-warning me-2"></i>Ostatnie koty w hodowli
                        </h5>
                        <a href="{{ route('backend.animals.index') }}" class="btn btn-sm btn-outline-warning">Zobacz wszystkie</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Zdjęcie</th>
                                        <th>Imię</th>
                                        <th>Rasa</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Akcje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAnimals as $animal)
                                        @php
                                            $badgeClass = match($animal->status?->value ?? '') {
                                                'available' => 'bg-success',
                                                'reserved' => 'bg-warning text-dark',
                                                'breeding' => 'bg-info text-dark',
                                                'sold' => 'bg-secondary',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                @if($animal->media)
                                                    <img src="{{ $animal->media->url() }}" alt="{{ $animal->name }}" class="rounded shadow-sm" style="width: 42px; height: 42px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                        <i class="fas fa-paw"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="fw-bold">{{ $animal->name }}</td>
                                            <td><span class="text-muted small">{{ $animal->breed }}</span></td>
                                            <td>
                                                <span class="badge {{ $badgeClass }} px-2 py-1 small fw-semibold">
                                                    {{ $animal->status?->label() ?? 'Brak' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('backend.animals.edit', $animal) }}" class="btn btn-sm btn-light border" title="Edytuj">
                                                    <i class="fas fa-edit text-muted"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Brak kotów w bazie danych.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabela 2: Ostatnio Opublikowane Wpisy --}}
            <div class="col-xl-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 pt-3 pb-2 d-flex align-items-center justify-content-between">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fas fa-pen-nib text-primary me-2"></i>Ostatnie artykuły
                        </h5>
                        <a href="{{ route('backend.posts.index') }}" class="btn btn-sm btn-outline-primary">Wszystkie wpisy</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Tytuł</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Edycja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPosts as $post)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-truncate" style="max-width: 220px;" title="{{ $post->title }}">
                                                    {{ $post->title }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $post->categories->first()?->name ?? 'Bez kategorii' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($post->is_published)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">Opublikowany</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 small">Szkic</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('backend.posts.edit', $post) }}" class="btn btn-sm btn-light border" title="Edytuj">
                                                    <i class="fas fa-edit text-muted"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Brak wpisów w bazie danych.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </x-backend.page-header>

</x-backend.shell>
