/**
 * ScrollReveal — gano-scroll-reveal.js
 * IntersectionObserver-based scroll animation trigger with stagger support
 * Task F3.2 — Sprint 1 Visual SOTA
 */

class ScrollReveal {
  constructor() {
    this.elements = document.querySelectorAll('[data-animation]');
    this.observer = null;

    // Check for reduced motion preference upfront
    this.prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (this.prefersReducedMotion) {
      this.respectReducedMotion();
    } else {
      this.createObserver();
      this.observeElements();
    }
  }

  /**
   * Create IntersectionObserver with specified configuration
   */
  createObserver() {
    const options = {
      threshold: 0.1,
      rootMargin: '-50px 0px -50px 0px',
    };

    this.observer = new IntersectionObserver((entries) => {
      this.handleIntersection(entries);
    }, options);
  }

  /**
   * Observe all elements with [data-animation] attribute
   */
  observeElements() {
    this.elements.forEach((element) => {
      this.observer.observe(element);
    });
  }

  /**
   * Handle intersection events — trigger animation when element enters viewport
   */
  handleIntersection(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const element = entry.target;
        const duration = element.getAttribute('data-duration') || '600';
        const delay = element.getAttribute('data-delay') || '0';
        const stagger = element.getAttribute('data-stagger');

        // Set CSS custom properties for duration and delay
        element.style.setProperty('--animation-duration', `${duration}ms`);
        element.style.setProperty('--animation-delay', `${delay}ms`);

        // Apply stagger delays to child elements if data-stagger is set
        if (stagger) {
          this.applyStagger(element, parseInt(delay), parseInt(stagger));
        }

        // Trigger animation by adding reveal-active class
        element.classList.add('reveal-active');

        // Unobserve the element after animation is triggered (no need to re-trigger)
        this.observer.unobserve(element);
      }
    });
  }

  /**
   * Apply staggered animation delays to child elements
   * @param {Element} element - Parent element
   * @param {number} baseDelay - Base delay in ms from data-delay
   * @param {number} staggerDelay - Stagger increment in ms from data-stagger
   */
  applyStagger(element, baseDelay, staggerDelay) {
    const children = element.children;

    Array.from(children).forEach((child, index) => {
      const childDelay = baseDelay + index * staggerDelay;
      child.style.setProperty('--animation-delay', `${childDelay}ms`);

      // Ensure child inherits animation if it has data-animation
      if (!child.hasAttribute('data-animation')) {
        // If child doesn't have its own animation, we still want to apply the stagger delay
        // but the animation is defined on the parent
      }
    });
  }

  /**
   * Respect prefers-reduced-motion by immediately showing all content
   */
  respectReducedMotion() {
    this.elements.forEach((element) => {
      // Remove animation and make content immediately visible
      element.style.animation = 'none';
      element.style.opacity = '1';
      element.style.transform = 'none';
      element.classList.add('reveal-active');
    });
  }
}

/**
 * Initialize ScrollReveal on DOM content loaded
 */
document.addEventListener('DOMContentLoaded', () => {
  new ScrollReveal();
});
