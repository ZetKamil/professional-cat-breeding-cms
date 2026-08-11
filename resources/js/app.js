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

