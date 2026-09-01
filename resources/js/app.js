// SB Admin core script
import './sb-admin/scripts'

// SB Admin plugins (conditionally load to prevent Chart.js ReferenceError on frontend)
if (document.querySelector('.sb-nav-fixed')) {
    import('./sb-admin/datatables-simple-demo').catch(() => {});
    import('./sb-admin/chart-area-demo').catch(() => {});
    import('./sb-admin/chart-bar-demo').catch(() => {});
    import('./sb-admin/chart-pie-demo').catch(() => {});
}

// ==========================================================================
// LUCIDE ICONS — Self-hosted, tree-shaken (replaces unpkg CDN)
// Only icons actually used in templates are imported.
// ==========================================================================

import { createIcons } from 'lucide';
import {
    AlertCircle,
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    Award,
    BookOpen,
    BugOff,
    Calendar,
    CalendarHeart,
    Cat,
    CheckCircle,
    CheckCircle2,
    ChevronLeft,
    ChevronRight,
    Clock,
    Dna,
    FileCheck,
    FileCheck2,
    GitBranch,
    GitCommitVertical,
    Heart,
    HeartHandshake,
    Home,
    HouseHeart,
    Mail,
    MapPin,
    Maximize2,
    MessageCircleHeart,
    Palette,
    Phone,
    PhoneCall,
    RefreshCw,
    ScrollText,
    Send,
    Shield,
    ShieldCheck,
    Syringe,
    UserCheck,
    Users,
    X,
} from 'lucide';

const lucideIconSet = {
    AlertCircle,
    ArrowLeft,
    ArrowRight,
    ArrowUpRight,
    Award,
    BookOpen,
    BugOff,
    Calendar,
    CalendarHeart,
    Cat,
    CheckCircle,
    CheckCircle2,
    ChevronLeft,
    ChevronRight,
    Clock,
    Dna,
    FileCheck,
    FileCheck2,
    GitBranch,
    GitCommitVertical,
    Heart,
    HeartHandshake,
    Home,
    HouseHeart,
    HomeHeart: HouseHeart,
    'home-heart': HouseHeart,
    'house-heart': HouseHeart,
    Mail,
    MapPin,
    Maximize2,
    MessageCircleHeart,
    Palette,
    Phone,
    PhoneCall,
    RefreshCw,
    ScrollText,
    Send,
    Shield,
    ShieldCheck,
    Syringe,
    UserCheck,
    Users,
    X,
};

/**
 * Initialize (or re-initialize) Lucide icons within an optional root element.
 * Passing a specific root avoids full-DOM re-scans on Livewire updates.
 *
 * @param {Element|Document} [root=document]
 */
function initLucideIcons(root) {
    createIcons({
        icons: lucideIconSet,
        ...(root && root !== document ? { nameAttr: 'data-lucide', attrs: {}, nodes: Array.from((root || document).querySelectorAll('[data-lucide]')) } : {}),
    });
}

// Initial page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initLucideIcons());
} else {
    initLucideIcons();
}

// Livewire 3 — re-initialize icons after any component update
// Using targeted re-scan on the updated component to avoid expensive full-DOM scan
if (typeof window !== 'undefined') {
    document.addEventListener('livewire:navigated', () => initLucideIcons());
    document.addEventListener('livewire:update', (event) => {
        // event.target is the Livewire component element that was updated
        const target = event?.target ?? document;
        // Small delay to allow DOM to settle
        requestAnimationFrame(() => initLucideIcons(target));
    });
}

// ==========================================================================
// SCROLL REVEAL ANIMATIONS
// ==========================================================================

const revealElements = document.querySelectorAll('.reveal-up');

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            e.preventDefault();
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

function initReveal() {
    const revealElements = document.querySelectorAll('.reveal-up');
    if (revealElements.length === 0) return;

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px 100px 0px', // Trigger before element enters viewport
        threshold: 0.01 // Trigger as soon as 1% is visible
    });

    revealElements.forEach(element => {
        const rect = element.getBoundingClientRect();
        // Immediately reveal if element is already within or close to viewport
        if (rect.top < window.innerHeight + 100) {
            element.classList.add('is-revealed');
        } else {
            revealObserver.observe(element);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initReveal);
} else {
    initReveal();
}

// ==========================================================================
// GA4 ANALYTICS EVENTS
// ==========================================================================
import './analytics';
