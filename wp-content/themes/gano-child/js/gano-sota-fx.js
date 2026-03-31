/**
 * Gano Digital — SOTA FX Handler
 * Custom GSAP animations for an organic, vivid, and technical experience.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Register GSAP Plugins
    gsap.registerPlugin(ScrollTrigger, TextPlugin);

    // 2. Performance Check: Reduce animations if user prefers reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    // 3. Entrance Animations: Refined for SOTA Content Hub
    const initEntranceAnimations = () => {
        // Main pages and sections
        const sotaPages = document.querySelectorAll('.gano-sota-page');
        const sections = document.querySelectorAll('.gano-sota-page section');
        const hookBoxes = document.querySelectorAll('.gano-hook-box');
        const quoteBoxes = document.querySelectorAll('.gano-quote-box');
        const ctaBoxes = document.querySelectorAll('.gano-cta-box');

        // Page title reveal with TextPlugin
        const titles = document.querySelectorAll('.gano-sota-page h1');
        titles.forEach(title => {
            const originalText = title.innerText;
            title.innerText = '';
            gsap.to(title, {
                scrollTrigger: {
                    trigger: title,
                    start: 'top 90%',
                },
                text: originalText,
                duration: 1.5,
                ease: 'power2.out'
            });
        });

        // Staggered sections
        sections.forEach((section, i) => {
            gsap.from(section, {
                scrollTrigger: {
                    trigger: section,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                },
                opacity: 0,
                y: 50,
                filter: 'blur(10px)',
                duration: 1,
                delay: i * 0.1,
                ease: 'power3.out',
            });
        });

        // Hook & Quote boxes with subtle scale and shine
        [...hookBoxes, ...quoteBoxes, ...ctaBoxes].forEach(box => {
            gsap.from(box, {
                scrollTrigger: {
                    trigger: box,
                    start: 'top 80%',
                },
                opacity: 0,
                scale: 0.95,
                duration: 1.2,
                ease: 'expo.out'
            });
        });

        // Staggered List Items
        const listItems = document.querySelectorAll('.gano-sota-page li');
        listItems.forEach((li, i) => {
            gsap.from(li, {
                scrollTrigger: {
                    trigger: li,
                    start: 'top 95%',
                },
                opacity: 0,
                x: -20,
                duration: 0.8,
                delay: (i % 5) * 0.1, // Stagger groups of 5
                ease: 'power2.out'
            });
        });
    };

    // 4. Parallax Backgrounds: Smooth translation on scroll
    const initParallax = () => {
        const backgrounds = document.querySelectorAll('.sota-hero-bg img, .gano-hero::before');
        
        backgrounds.forEach((bg) => {
            gsap.to(bg, {
                scrollTrigger: {
                    trigger: bg.parentElement,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: true,
                },
                yPercent: 20,
                ease: 'none',
            });
        });
    };

    // 5. Magnetic Buttons: Organic micro-interaction
    const initMagneticButtons = () => {
        const buttons = document.querySelectorAll('.gano-btn-primary, .gano-btn-secondary, .gano-buy-button');
        
        buttons.forEach((btn) => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                gsap.to(btn, {
                    x: x * 0.35,
                    y: y * 0.35,
                    duration: 0.3,
                    ease: 'power2.out',
                });
            });
            
            btn.addEventListener('mouseleave', () => {
                gsap.to(btn, {
                    x: 0,
                    y: 0,
                    duration: 0.6,
                    ease: 'elastic.out(1, 0.3)',
                });
            });
        });
    };

    // 6. Vivid Progress Bar: Animate the Gano Gold bar
    const initProgressBar = () => {
        const progressBar = document.getElementById('gano-read-progress');
        if (!progressBar) return;
        
        gsap.to(progressBar, {
            scrollTrigger: {
                trigger: document.body,
                start: 'top top',
                end: 'bottom bottom',
                scrub: 0.3,
            },
            width: '100%',
            backgroundColor: '#FF6B35', 
            ease: 'none',
        });
    };

    // Initialize all
    initEntranceAnimations();
    initParallax();
    initMagneticButtons();
    initProgressBar();
    
    console.log('✨ Gano SOTA FX v2.0 Initialized');
});
