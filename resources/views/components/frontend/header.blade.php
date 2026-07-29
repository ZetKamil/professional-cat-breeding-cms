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
                    <a href="{{ route('home') }}#nasze-koty"
                       class="global-nav__link">
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
                    <a href="{{ route('home') }}#nasze-koty" class="mobile-menu__link" onclick="document.querySelector('.global-nav__hamburger').click()">
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

<style>
    /* ==========================================================================
       GLOBAL NAV — Glassmorphism Apple-inspired (Light Mode)
       ========================================================================== */

    .global-nav {
        position: sticky;
        top: 0;
        z-index: var(--z-sticky);
        padding: 0 var(--content-padding-x);
        background-color: rgba(255, 255, 255, 0.72);
        backdrop-filter: saturate(180%) blur(20px);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        border-bottom: 1px solid transparent;
        transition: background-color var(--duration-base) var(--ease-out),
                    border-bottom-color var(--duration-base) var(--ease-out);
    }
    
    .global-nav.is-scrolled {
        background-color: rgba(255, 255, 255, 0.85);
        border-bottom-color: rgba(0, 0, 0, 0.08);
    }

    .global-nav__inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 48px; /* Slightly taller for breathing room */
        transition: height var(--duration-base) var(--ease-out);
    }

    /* Logo */
    .global-nav__logo {
        display: flex;
        align-items: center;
        gap: var(--sp-xs);
        text-decoration: none;
        flex-shrink: 0;
    }

    .global-nav__logo-text {
        font-size: var(--text-tagline-size);
        font-weight: 600;
        letter-spacing: var(--text-tagline-ls);
        color: var(--color-ink);
        white-space: nowrap;
    }

    /* Desktop Links */
    .global-nav__links {
        display: flex;
        align-items: center;
        gap: var(--sp-lg);
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .global-nav__link {
        font-size: var(--text-nav-size);
        font-weight: var(--text-nav-weight);
        letter-spacing: var(--text-nav-ls);
        line-height: var(--text-nav-lh);
        color: var(--color-ink-muted-80);
        text-decoration: none;
        padding: var(--sp-xs) 0;
        transition: color var(--duration-fast) var(--ease-out);
        white-space: nowrap;
    }

    .global-nav__link:hover,
    .global-nav__link.is-active {
        color: var(--color-ink);
    }

    /* Utils */
    .global-nav__utils {
        display: flex;
        align-items: center;
        gap: var(--sp-md);
    }

    /* Hamburger — hidden on desktop */
    .global-nav__hamburger {
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
        width: var(--touch-target-min);
        height: var(--touch-target-min);
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .hamburger-line {
        display: block;
        width: 18px;
        height: 1.5px;
        background-color: var(--color-ink);
        border-radius: 1px;
        transition: transform var(--duration-base) var(--ease-out),
                    opacity var(--duration-fast) var(--ease-out);
    }

    /* Hamburger open state */
    .global-nav__hamburger[aria-expanded="true"] .hamburger-line:first-child {
        transform: translateY(3.25px) rotate(45deg);
    }

    .global-nav__hamburger[aria-expanded="true"] .hamburger-line:last-child {
        transform: translateY(-3.25px) rotate(-45deg);
    }

    /* ==========================================================================
       MOBILE MENU — Full-screen overlay
       ========================================================================== */

    .mobile-menu {
        position: fixed;
        inset: 44px 0 0 0;
        z-index: var(--z-overlay);
        background-color: var(--color-surface-black);
        opacity: 0;
        visibility: hidden;
        transition: opacity var(--duration-base) var(--ease-out),
                    visibility var(--duration-base) var(--ease-out);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .mobile-menu.is-open {
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu__nav {
        padding: var(--sp-2xl) var(--content-padding-x);
    }

    .mobile-menu__links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .mobile-menu__link {
        display: block;
        padding: var(--sp-md) 0;
        font-size: var(--text-lead-size);
        font-weight: 600;
        color: var(--color-canvas);
        text-decoration: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        transition: color var(--duration-fast) var(--ease-out);
    }

    .mobile-menu__link:hover,
    .mobile-menu__link.is-active {
        color: var(--color-primary-on-dark);
    }

    /* Body scroll lock */
    body.menu-open {
        overflow: hidden;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */

    @media (max-width: 834px) {
        .global-nav__links {
            display: none;
        }

        .global-nav__hamburger {
            display: flex;
        }

        .global-nav__utils .global-nav__link {
            display: none;
        }
    }
</style>
