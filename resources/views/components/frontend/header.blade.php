{{-- 
    Premium Navigation — Apple-inspired
    Two-row nav: black global bar + transparent sub-nav
    Collapses to hamburger at 834px
--}}

<header class="site-header" role="banner">
    {{-- Global Nav Bar — Glassmorphism Apple-inspired --}}
    <nav class="global-nav" aria-label="Główna nawigacja" id="globalNav">
        <div class="global-nav__inner section-inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="global-nav__logo" aria-label="{{ config('app.name') }} — strona główna">
                <span class="global-nav__logo-text">{{ config('app.name', 'Cattery') }}</span>
            </a>

            {{-- Desktop Links --}}
            <ul class="global-nav__links" role="list">
                <li>
                    <a href="{{ route('home') }}"
                       class="global-nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                       {{ request()->routeIs('home') ? 'aria-current=page' : '' }}>
                        Strona Główna
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.animals.index') }}"
                       class="global-nav__link {{ request()->routeIs('frontend.animals.*') ? 'is-active' : '' }}"
                       {{ request()->routeIs('frontend.animals.*') ? 'aria-current=page' : '' }}>
                        Nasze Koty
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}#o-nas"
                       class="global-nav__link">
                        O Hodowli
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}"
                       class="global-nav__link {{ request()->routeIs('about') ? 'is-active' : '' }}"
                       {{ request()->routeIs('about') ? 'aria-current=page' : '' }}>
                        O Nas
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.blog.index') }}"
                       class="global-nav__link {{ request()->routeIs('frontend.blog.*') ? 'is-active' : '' }}"
                       {{ request()->routeIs('frontend.blog.*') ? 'aria-current=page' : '' }}>
                        Baza Wiedzy
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}"
                       class="global-nav__link {{ request()->routeIs('contact') ? 'is-active' : '' }}"
                       {{ request()->routeIs('contact') ? 'aria-current=page' : '' }}>
                        Kontakt
                    </a>
                </li>
            </ul>

            {{-- Right side utilities --}}
            <div class="global-nav__utils">
                @auth
                    @if(Route::has('backend.dashboard'))
                        <a href="{{ route('backend.dashboard') }}" class="global-nav__link">
                            Panel
                        </a>
                    @endif
                @endauth

                {{-- Mobile hamburger --}}
                <button
                    class="global-nav__hamburger"
                    type="button"
                    aria-label="Otwórz menu"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    onclick="this.setAttribute('aria-expanded', this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'); document.getElementById('mobile-menu').classList.toggle('is-open'); document.body.classList.toggle('menu-open');"
                >
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile Menu Overlay --}}
    <div class="mobile-menu" id="mobile-menu" role="dialog" aria-label="Menu mobilne">
        <nav class="mobile-menu__nav" aria-label="Menu mobilne">
            <ul class="mobile-menu__links" role="list">
                <li>
                    <a href="{{ route('home') }}" class="mobile-menu__link {{ request()->routeIs('home') ? 'is-active' : '' }}">
                        Strona Główna
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.animals.index') }}" class="mobile-menu__link {{ request()->routeIs('frontend.animals.*') ? 'is-active' : '' }}">
                        Nasze Koty
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}#o-nas" class="mobile-menu__link" onclick="document.querySelector('.global-nav__hamburger').click()">
                        O Hodowli
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}" class="mobile-menu__link {{ request()->routeIs('about') ? 'is-active' : '' }}">
                        O Nas
                    </a>
                </li>
                <li>
                    <a href="{{ route('frontend.blog.index') }}" class="mobile-menu__link {{ request()->routeIs('frontend.blog.*') ? 'is-active' : '' }}">
                        Baza Wiedzy
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="mobile-menu__link {{ request()->routeIs('contact') ? 'is-active' : '' }}">
                        Kontakt
                    </a>
                </li>
                @auth
                    <li>
                        <a href="{{ route('backend.dashboard') }}" class="mobile-menu__link">
                            Panel Administracyjny
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<script>
    // Premium scroll effect for navigation
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.getElementById('globalNav');
        if (!nav) return;

        const handleScroll = () => {
            if (window.scrollY > 10) {
                nav.classList.add('is-scrolled');
            } else {
                nav.classList.remove('is-scrolled');
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        handleScroll(); // Init
    });
</script>
