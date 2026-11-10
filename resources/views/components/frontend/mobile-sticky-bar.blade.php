{{--
    Luxury Sticky Mobile Bottom Bar — Mazovian Switzerland
    ─────────────────────────────────────────────────────────────────
    Sleek, non-intrusive Messenger conversion bar for mobile viewports (< 768px).
    Appears after 250px scroll on /koty, /koty/[slug], /blog/[slug],
    or immediately upon viewing Quiz results (/dobierz-rase).
--}}

@php
    $path = trim(request()->path(), '/');

    // Route / path matching
    $isAnimalsCatalog = request()->routeIs('frontend.animals.index') || $path === 'koty';
    $isAnimalShow = request()->routeIs('frontend.animals.show') || (str_starts_with($path, 'koty/') && $path !== 'koty');
    $isBlogShow = request()->routeIs('frontend.blog.show') || (str_starts_with($path, 'blog/') && $path !== 'blog');
    $isQuiz = request()->routeIs('quiz') || $path === 'dobierz-rase';

    $scrollEligible = $isAnimalsCatalog || $isAnimalShow || $isBlogShow;
    $shouldRender = $scrollEligible || $isQuiz;

    $routeAnimal = request()->route('animal');
    $stickyKittenName = $routeAnimal instanceof \App\Models\Animal ? $routeAnimal->name : null;

    $stickyWhatsappMessage = $stickyKittenName
        ? "Dzień dobry! Piszę w sprawie kociaka {$stickyKittenName} z hodowli Mazowiecka Szwajcaria. Czy mogę prosić o więcej informacji i filmik?"
        : 'Dzień dobry! Piszę w sprawie kociąt z hodowli Mazowiecka Szwajcaria. Czy mogę prosić o więcej informacji i filmik?';
    $stickyWhatsappUrl = 'https://wa.me/48514153204?text=' . rawurlencode($stickyWhatsappMessage);

    $stickyMessengerMessage = $stickyKittenName
        ? "Dzień dobry! Piszę w sprawie kociaka {$stickyKittenName} z hodowli Mazowiecka Szwajcaria. Czy mogę prosić o więcej informacji i filmik?"
        : 'Dzień dobry! Chciał(a)bym zapytać o kociaki lub aktualne filmiki.';
    $stickyMessengerUrl = 'https://m.me/61580668026948?text=' . rawurlencode($stickyMessengerMessage);
@endphp

@if($shouldRender)
<aside 
    id="mobileStickyMessengerBar" 
    class="mobile-sticky-bar" 
    role="toolbar" 
    aria-label="Szybki kontakt Messenger i WhatsApp"
    data-scroll-eligible="{{ $scrollEligible ? '1' : '0' }}"
    data-is-quiz="{{ $isQuiz ? '1' : '0' }}"
>
    <div class="mobile-sticky-bar__inner">
        {{-- Top/left text (Cream / White) --}}
        <div class="mobile-sticky-bar__text">
            Pytanie o kociaka lub filmik?
        </div>

        {{-- Dual Quiet Luxury Action Buttons: Messenger (#C5A059) & WhatsApp (#111111 + #FAF8F5) --}}
        <div class="mobile-sticky-bar__actions">
            <a 
                href="{{ $stickyMessengerUrl }}" 
                class="mobile-sticky-bar__messenger-btn"
                id="mobileStickyMessengerBtn"
                data-messenger-cta="1"
                onclick="window.handleStickyMessengerClick(event)"
            >
                💬 Messenger →
            </a>
            <a 
                href="{{ $stickyWhatsappUrl }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="mobile-sticky-bar__whatsapp-btn"
                id="mobileStickyWhatsappBtn"
                data-whatsapp-cta="1"
                onclick="window.handleStickyWhatsappClick(event)"
            >
                📱 WhatsApp
            </a>
        </div>
    </div>
</aside>

<script>
(function() {
    window.handleStickyMessengerClick = function(e) {
        // 1. Funnel analytics tracking: contact_cta_click (GA4 + Meta Pixel)
        if (typeof window.trackFunnelStep === 'function') {
            window.trackFunnelStep('contact_cta_click', {
                channel: 'messenger',
                method: 'messenger',
                location: 'sticky_mobile_bar',
                cat_name: @js($stickyKittenName ?: 'General'),
            });
        }

        // 2. Meta Pixel Contact event
        if (typeof window.fbq === 'function') {
            try {
                window.fbq('track', 'Contact', {
                    content_name: @js($stickyKittenName ? "sticky_mobile_messenger_{$stickyKittenName}" : 'sticky_mobile_messenger'),
                    channel: 'Messenger',
                    method: 'Messenger'
                });
            } catch(err) {}
        }

        const btn = document.getElementById('mobileStickyMessengerBtn');
        if (btn && !btn.href.includes('?text=')) {
            btn.href = @js($stickyMessengerUrl);
        }
    };

    window.handleStickyWhatsappClick = function(e) {
        // 1. Funnel analytics tracking: contact_cta_click (GA4 + Meta Pixel)
        if (typeof window.trackFunnelStep === 'function') {
            window.trackFunnelStep('contact_cta_click', {
                channel: 'whatsapp',
                method: 'whatsapp',
                location: 'sticky_mobile_bar',
                cat_name: @js($stickyKittenName ?: 'General'),
            });
        }

        // 2. Meta Pixel Contact event
        if (typeof window.fbq === 'function') {
            try {
                window.fbq('track', 'Contact', {
                    content_name: @js($stickyKittenName ? "sticky_mobile_whatsapp_{$stickyKittenName}" : 'sticky_mobile_whatsapp'),
                    channel: 'WhatsApp',
                    method: 'WhatsApp'
                });
            } catch(err) {}
        }
    };

    function initMobileStickyMessengerBar() {
        const bar = document.getElementById('mobileStickyMessengerBar');
        if (!bar) return;

        const isScrollEligible = bar.dataset.scrollEligible === '1';
        const isQuiz = bar.dataset.isQuiz === '1';
        let quizResultShown = false;

        function checkQuizResultVisible() {
            const res = document.getElementById('quizResult');
            return !!(res && res.style.display !== 'none' && res.offsetHeight > 0);
        }

        function updateVisibility() {
            // Guard: only active on mobile viewports (< 768px)
            if (window.innerWidth >= 768) {
                bar.classList.remove('mobile-sticky-bar--visible');
                return;
            }

            // Guard: hide when reservation modal or other full dialogs are active
            if (document.body.classList.contains('is-modal-open') || document.body.classList.contains('modal-open')) {
                bar.classList.remove('mobile-sticky-bar--visible');
                return;
            }

            // Route 1: Quiz page — display when results are shown
            if (isQuiz) {
                if (quizResultShown || checkQuizResultVisible()) {
                    bar.classList.add('mobile-sticky-bar--visible');
                } else {
                    bar.classList.remove('mobile-sticky-bar--visible');
                }
                return;
            }

            // Route 2: /koty, /koty/[slug], /blog/[slug] — display after 250px scroll
            if (isScrollEligible) {
                if (window.scrollY >= 250) {
                    bar.classList.add('mobile-sticky-bar--visible');
                } else {
                    bar.classList.remove('mobile-sticky-bar--visible');
                }
            }
        }

        if (isQuiz) {
            window.addEventListener('quiz:results-shown', function() {
                quizResultShown = true;
                updateVisibility();
            });

            // MutationObserver fallback for dynamic step transitions
            const quizContainer = document.getElementById('quizContainer');
            if (quizContainer && window.MutationObserver) {
                const observer = new MutationObserver(function() {
                    if (checkQuizResultVisible()) {
                        quizResultShown = true;
                        updateVisibility();
                    }
                });
                observer.observe(quizContainer, { attributes: true, subtree: true, attributeFilter: ['style', 'class'] });
            }
        }

        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateVisibility();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });

        window.addEventListener('resize', updateVisibility, { passive: true });

        updateVisibility();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileStickyMessengerBar);
    } else {
        initMobileStickyMessengerBar();
    }

    document.addEventListener('livewire:navigated', initMobileStickyMessengerBar);
})();
</script>
@endif
