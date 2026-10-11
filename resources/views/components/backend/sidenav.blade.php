<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Główne</div>

            @can('view-backend-dashboard')
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Pulpit nawigacyjny
                </a>
            @endcan

            <div class="sb-sidenav-menu-heading">Treści</div>

            @can('viewAny', \App\Models\Animal::class)
                <a class="nav-link {{ request()->routeIs('backend.animals.*') ? 'active' : '' }}"
                   href="{{ route('backend.animals.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-paw"></i>
                    </div>
                    Koty hodowlane
                </a>
            @endcan

            @can('viewAny', \App\Models\Post::class)
                <a class="nav-link {{ request()->routeIs('backend.posts.*') ? 'active' : '' }}"
                   href="{{ route('backend.posts.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-thumbtack"></i>
                    </div>
                    Wpisy na blogu
                </a>
            @endcan

            @can('create', \App\Models\Post::class)
                <a class="nav-link {{ request()->routeIs('backend.posts.ai-studio') ? 'active' : '' }}"
                   href="{{ route('backend.posts.ai-studio') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                    AI Blog Studio
                </a>
            @endcan

            @can('viewAny', \App\Models\Post::class)
                <a class="nav-link {{ request()->routeIs('backend.blog-cta.*') ? 'active' : '' }}"
                   href="{{ route('backend.blog-cta.edit') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-bullhorn text-warning"></i>
                    </div>
                    Oferta pod artykułami
                </a>
            @endcan

            @can('viewAny', \App\Models\Post::class)
                <a class="nav-link {{ request()->routeIs('backend.categories.*') ? 'active' : '' }}"
                   href="{{ route('backend.categories.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    Kategorie bloga
                </a>
            @endcan

            @can('viewAny', \App\Models\Media::class)
                <a class="nav-link {{ request()->routeIs('backend.media.*') ? 'active' : '' }}"
                   href="{{ route('backend.media.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-image"></i>
                    </div>
                    Biblioteka mediów
                </a>
            @endcan

            <div class="sb-sidenav-menu-heading">Administracja</div>

            @can('viewAny', \App\Models\User::class)
                <a class="nav-link {{ request()->routeIs('backend.users.*') ? 'active' : '' }}"
                   href="{{ route('backend.users.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    Użytkownicy
                </a>
            @endcan

            @can('viewAny', \App\Models\Role::class)
                <a class="nav-link {{ request()->routeIs('backend.roles.*') ? 'active' : '' }}"
                   href="{{ route('backend.roles.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    Role i uprawnienia
                </a>
            @endcan

            <div class="sb-sidenav-menu-heading">Strona</div>

            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-globe"></i>
                </div>
                Przejdź do strony
            </a>
        </div>
    </div>

    <div class="sb-sidenav-footer">
        <div class="small">Zalogowano jako:</div>
        {{ auth()->user()->name ?? 'Administrator' }}
    </div>
</nav>
