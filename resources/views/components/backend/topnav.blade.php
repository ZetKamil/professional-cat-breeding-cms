@php
    $user = auth()->user();
    $avatar = $user?->media()->first();

    $avatarUrl = null;

    if ($avatar && method_exists($avatar, 'url')) {
        $avatarUrl = $avatar->url();
    }

    $brandUrl = $user && $user->can('view-backend-dashboard')
        ? route('backend.dashboard')
        : route('backend.posts.index');
@endphp

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="{{ $brandUrl }}">Start Bootstrap</a>

    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" type="button">
        <i class="fas fa-bars"></i>
    </button>

    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input
                class="form-control"
                type="text"
                placeholder="Search for..."
                aria-label="Search for..."
                aria-describedby="btnNavbarSearch"
            />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a
                class="nav-link dropdown-toggle d-flex align-items-center"
                id="navbarDropdown"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <span class="me-2 text-white">{{ $user->name ?? 'Gebruiker' }}</span>

                @if ($avatarUrl)
                    <img
                        src="{{ $avatarUrl }}"
                        alt="{{ $user->name ?? 'Gebruiker' }}"
                        class="rounded-circle"
                        style="width: 36px; height: 36px; object-fit: cover;"
                    >
                @else
                    <span
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary text-white"
                        style="width: 36px; height: 36px;"
                    >
                        <i class="fas fa-user"></i>
                    </span>
                @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        Settings
                    </a>
                </li>

                <li><hr class="dropdown-divider" /></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
