/**
 * Gano Digital — Scroll Reveal Animations
 * Triggers animations when elements come into viewport
 * v2.0.0
 */

(function() {
    'use strict';

    // Check browser support for IntersectionObserver
    if (!('IntersectionObserver' in window)) {
        console.warn('ScrollReveal: IntersectionObserver not supported. Animations disabled.');
        return;
    }

    /**
     * Configuration for scroll reveal animations
     */
    const revealOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
    };

    /**
     * Observer callback — triggers animation when element enters viewport
     */
    const revealOnScroll = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add animation class
                entry.target.classList.add('gano-revealed');

                // Unobserve after animation triggers
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    /**
     * Apply scroll reveal to all elements with .gano-sota-page
     */
    function initScrollReveal() {
        // Main article container
        const articles = document.querySelectorAll('.gano-sota-page');
        articles.forEach(article => {
            article.classList.add('gano-reveal-item');
            revealOnScroll.observe(article);
        });

        // Individual sections for staggered reveal
        const sections = document.querySelectorAll('.gano-sota-page section');
        sections.forEach((section, index) => {
            section.classList.add('gano-reveal-item');
            section.style.setProperty('--reveal-delay', `${index * 0.1}s`);
            revealOnScroll.observe(section);
        });

        // Bullet points for individual stagger
        const listItems = document.querySelectorAll('.gano-sota-page li');
        listItems.forEach((item, index) => {
            item.classList.add('gano-reveal-item');
            item.style.setProperty('--reveal-delay', `${index * 0.08}s`);
            revealOnScroll.observe(item);
        });

        // CTA boxes
        const ctaBoxes = document.querySelectorAll('.gano-cta-box');
        ctaBoxes.forEach(box => {
            box.classList.add('gano-reveal-item');
            revealOnScroll.observe(box);
        });

        // Quote boxes
        const quotes = document.querySelectorAll('.gano-quote-box');
        quotes.forEach(quote => {
            quote.classList.add('gano-reveal-item');
            revealOnScroll.observe(quote);
        });
    }

    /**
     * Add smooth scroll behavior for CTA links
     */
    function initSmoothScroll() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (!link) return;

            e.preventDefault();
            const targetId = link.getAttribute('href').slice(1);
            const target = document.getElementById(targetId);

            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    /**
     * Respeta preferencias de reducción de movimiento
     */
    function respectMotionPreferences() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReducedMotion) {
            // Desactiva animaciones si usuario lo prefiere
            document.documentElement.style.setProperty('--motion-duration', '0s');
            document.documentElement.style.setProperty('--motion-enabled', '0');
        }
    }

    /**
     * Initialize on DOM ready
     */
    document.addEventListener('DOMContentLoaded', () => {
        respectMotionPreferences();
        initScrollReveal();
        initSmoothScroll();

        console.log('✅ Gano Digital Scroll Reveal initialized.');
    });

    /**
     * Fallback if DOMContentLoaded already fired
     */
    if (document.readyState === 'loading') {
        // DOM still loading
    } else {
        respectMotionPreferences();
        initScrollReveal();
        initSmoothScroll();
    }
})();
