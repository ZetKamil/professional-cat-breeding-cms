{{-- 
    Premium Adaptive Glass Navigation — Apple / Aesop inspired
    3-zone layout: Brand | Links | Utilities (Panel + Hamburger)
    Adaptive scroll themes (light, cream, dark) & accessible mobile overlay
--}}

<header class="site-header" role="banner">
    {{-- Global Nav Bar --}}
    <nav class="global-nav global-nav--top" aria-label="Główna nawigacja" id="globalNav">
        <div class="global-nav__inner">
            {{-- Zone 1: Logo / Brand --}}
            <a href="{{ route('home') }}" class="global-nav__logo" aria-label="{{ config('app.name', 'Cattery') }} — strona główna">
                <span class="global-nav__logo-text">{{ config('app.name', 'Cattery') }}</span>
            </a>

            {{-- Zone 2: Desktop Primary Links --}}
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
                    <a href="{{ route('cattery') }}"
                       class="global-nav__link {{ request()->routeIs('cattery') ? 'is-active' : '' }}"
                       {{ request()->routeIs('cattery') ? 'aria-current=page' : '' }}>
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

            {{-- Zone 3: Right side utilities --}}
            <div class="global-nav__utils">
                @auth
                    @if(Route::has('dashboard'))
                        <a href="{{ route('dashboard') }}" class="global-nav__link">
                            Panel
                        </a>
                    @endif
                @endauth

                <a href="{{ route('contact') }}" class="global-nav__cta" aria-label="Zapytaj o kocięta">
                    Zapytaj o kocięta
                </a>

                {{-- Mobile hamburger --}}
                <button
                    class="global-nav__hamburger"
                    type="button"
                    aria-label="Otwórz menu mobilne"
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    id="mobileMenuBtn"
                >
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Apple-Style Fullscreen Mobile Menu Overlay --}}
    <div class="mobile-menu" id="mobile-menu" role="dialog" aria-modal="true" aria-label="Menu mobilne" aria-hidden="true">
        <div class="mobile-menu__header">
            <a href="{{ route('home') }}" class="mobile-menu__brand">
                {{ config('app.name', 'Cattery') }}
            </a>
            <button type="button" class="mobile-menu__close" aria-label="Zamknij menu" id="mobileMenuCloseBtn">
                <i data-lucide="x" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="mobile-menu__nav" aria-label="Nawigacja mobilna">
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
                    <a href="{{ route('cattery') }}" class="mobile-menu__link {{ request()->routeIs('cattery') ? 'is-active' : '' }}">
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
                    @if(Route::has('dashboard'))
                        <li>
                            <a href="{{ route('dashboard') }}" class="mobile-menu__link">
                                Panel Administracyjny
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </nav>
        <div class="mobile-menu__footer">
            <a href="{{ route('contact') }}" class="mobile-menu__cta">
                Zapytaj o kocięta
            </a>
        </div>
    </div>
</header>

<script>
    // Premium Adaptive Glass Navbar Controller — Vanilla JS (O(1) rAF scroll detection)
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.getElementById('globalNav');
        const openBtn = document.getElementById('mobileMenuBtn');
        const closeBtn = document.getElementById('mobileMenuCloseBtn');
        const overlay = document.getElementById('mobile-menu');
        if (!nav) return;

        let lastTheme = '';
        let isScrolled = false;
        let isTop = true;
        let ticking = false;

        // 1. Scan page for sections and classify theme (dark / cream / light)
        const getSectionTheme = (el) => {
            if (!el) return 'light';
            const explicit = el.getAttribute('data-nav-theme');
            if (explicit === 'dark' || explicit === 'cream' || explicit === 'light') return explicit;

            const cls = (el.className || '').toString();
            const tileAttr = el.getAttribute('tile') || '';
            if (
                cls.includes('tile-dark') ||
                cls.includes('tile-black') ||
                tileAttr.includes('dark') ||
                tileAttr === 'black' ||
                cls.includes('hero--dark') ||
                cls.includes('article-cover-hero')
            ) {
                return 'dark';
            }
            if (cls.includes('tile-parchment') || tileAttr === 'parchment') {
                return 'cream';
            }
            return 'light';
        };

        const detectCurrentTheme = () => {
            const probeY = 32; // center of 64px navbar
            const sections = document.querySelectorAll('main section, main > div, main [data-nav-theme], main [class*="tile-"], footer');
            let matchedTheme = 'light';

            for (let i = 0; i < sections.length; i++) {
                const rect = sections[i].getBoundingClientRect();
                if (rect.top <= probeY && rect.bottom >= probeY) {
                    matchedTheme = getSectionTheme(sections[i]);
                    break;
                }
            }
            return matchedTheme;
        };

        // 2. Performant Scroll Listener with RequestAnimationFrame
        const updateNavbarState = () => {
            const scrollY = window.scrollY || window.pageYOffset;
            const newIsTop = scrollY <= 10;
            const newIsScrolled = scrollY > 10;
            const newTheme = detectCurrentTheme();

            if (newIsTop !== isTop) {
                isTop = newIsTop;
                nav.classList.toggle('global-nav--top', isTop);
            }

            if (newIsScrolled !== isScrolled) {
                isScrolled = newIsScrolled;
                nav.classList.toggle('is-scrolled', isScrolled);
            }

            if (newTheme !== lastTheme) {
                nav.classList.remove('global-nav--light', 'global-nav--cream', 'global-nav--dark');
                nav.classList.add(`global-nav--${newTheme}`);
                lastTheme = newTheme;
            }

            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                window.requestAnimationFrame(updateNavbarState);
                ticking = true;
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        updateNavbarState(); // Initial evaluation on page load

        // 3. Apple-style Mobile Menu Overlay with Accessible Focus Management & ESC
        const openMobileMenu = () => {
            if (!overlay || !openBtn) return;
            overlay.classList.add('is-open');
            overlay.setAttribute('aria-hidden', 'false');
            openBtn.setAttribute('aria-expanded', 'true');
            document.body.classList.add('menu-open');
            if (closeBtn) closeBtn.focus();
        };

        const closeMobileMenu = () => {
            if (!overlay || !openBtn) return;
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
            openBtn.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
            openBtn.focus();
        };

        if (openBtn) openBtn.addEventListener('click', openMobileMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMobileMenu);

        // Close on link click
        if (overlay) {
            overlay.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    closeMobileMenu();
                });
            });
        }

        // ESC key listener
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay && overlay.classList.contains('is-open')) {
                closeMobileMenu();
            }
        });
    });
</script>
