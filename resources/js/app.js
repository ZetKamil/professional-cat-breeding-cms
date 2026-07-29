// Bootstrap setup (Laravel default)
//import 'bootstrap/dist/js/bootstrap.bundle.min.js'
// resources/js/app.js

// Laadt Bootstrap JS (incl. Alert plugin + data-bs-dismiss support)
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
// // Alpine
// import Alpine from 'alpinejs'
// window.Alpine = Alpine
// Alpine.start()

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

if (revealElements.length > 0) {
    // Use IntersectionObserver for performant scroll reveals
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add class to trigger CSS transition
                entry.target.classList.add('is-revealed');
                // Stop observing once revealed
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -10% 0px', // Trigger slightly before element comes into view
        threshold: 0.1 // 10% of element must be visible
    });

    revealElements.forEach(element => {
        revealObserver.observe(element);
    });
}

