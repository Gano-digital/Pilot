/**
 * GANO NAV SCROLL-SPY — IntersectionObserver + Hide-on-Scroll + Glassmorphism
 *
 * Functionality:
 * 1. Scroll-Spy: Highlights current section's nav link based on viewport
 *    - Uses IntersectionObserver with -50% root margin
 *    - Adds .active class to corresponding nav link
 *    - Removes .active from previous link
 *
 * 2. Hide-on-Scroll: Nav hides when scrolling down > 50px; reappears on scroll up
 *    - Tracks scroll direction (deltaY)
 *    - Applies .hidden class (transforms nav out of view)
 *    - Threshold: show nav if scrollTop < lastScrollTop - 50
 *
 * 3. Glassmorphism: Nav background is semi-transparent with backdrop blur
 *    - Background: rgba(5, 7, 10, 0.7)
 *    - Backdrop-filter: blur(10px)
 *    - Smooth transitions on show/hide
 *
 * Dependencies: CSS: gano-nav-enhanced.css
 * WCAG: aria-current="page" on active link, keyboard navigation support
 *
 * @author Gano Digital
 * @version 1.0.0
 */

'use strict';

class ScrollSpy {
  constructor() {
    // Select nav and nav links
    this.nav = document.querySelector('.nav');
    this.links = document.querySelectorAll('.nav-links a[href^="#"]');
    this.sections = [];
    this.lastScrollTop = 0;
    this.isHidden = false;

    // Map links to sections
    this.links.forEach((link) => {
      const href = link.getAttribute('href');
      const section = document.querySelector(href);
      if (section) {
        this.sections.push({ link, section, href });
      }
    });

    // Guard: no sections found
    if (this.sections.length === 0) {
      console.warn('ScrollSpy: No sections found for scroll-spy activation');
      return;
    }

    // Initialize features
    this.initObserver();
    this.attachScrollListener();

    if (window.location.hash) {
      this.handleInitialHash();
    }
  }

  /**
   * Initialize IntersectionObserver for scroll-spy
   * Detects which section is currently in viewport
   */
  initObserver() {
    const observer = new IntersectionObserver(
      (entries) => {
        let visibleSection = null;

        // Find the first visible section
        for (const entry of entries) {
          if (entry.isIntersecting) {
            visibleSection = entry.target;
            break;
          }
        }

        // Update active link if a visible section exists
        if (visibleSection) {
          this.setActiveLink(visibleSection);
        }
      },
      {
        threshold: 0.1,
        rootMargin: '-50% 0px -50% 0px',
      }
    );

    // Observe all sections
    this.sections.forEach(({ section }) => {
      observer.observe(section);
    });
  }

  /**
   * Set active link based on section in viewport
   * @param {Element} section - The currently visible section
   */
  setActiveLink(section) {
    // Remove .active from all links
    this.links.forEach((link) => {
      link.classList.remove('active');
      link.removeAttribute('aria-current');
    });

    // Find and mark the corresponding link as active
    const activeLinkData = this.sections.find((s) => s.section === section);
    if (activeLinkData) {
      const { link } = activeLinkData;
      link.classList.add('active');
      link.setAttribute('aria-current', 'page');
    }
  }

  /**
   * Attach scroll event listener for hide-on-scroll behavior
   * Tracks scroll direction and toggles .hidden class on nav
   */
  attachScrollListener() {
    let scrollTimeout;
    let isScrolling = false;

    window.addEventListener(
      'scroll',
      () => {
        if (!isScrolling) {
          isScrolling = true;
          requestAnimationFrame(() => {
            const scrollTop = window.scrollY;
            const scrollDelta = scrollTop - this.lastScrollTop;

            // Hide nav if scrolling down > 50px and scrollTop > 100px
            if (scrollDelta > 50 && scrollTop > 100) {
              this.toggleNav(true); // Hide
            }
            // Show nav if scrolling up > 50px
            else if (scrollDelta < -50) {
              this.toggleNav(false); // Show
            }

            this.lastScrollTop = scrollTop;
            isScrolling = false;
          });
        }

        // Reset debounce timeout
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
          // Optional: Reset state on scroll end
        }, 150);
      },
      { passive: true }
    );
  }

  /**
   * Toggle nav visibility with .hidden class
   * @param {boolean} hide - True to hide nav, false to show
   */
  toggleNav(hide) {
    // Early exit if state is already set
    if (hide === this.isHidden) {
      return;
    }

    if (hide) {
      this.nav.classList.add('hidden');
      this.isHidden = true;
    } else {
      this.nav.classList.remove('hidden');
      this.isHidden = false;
    }
  }

  /**
   * Handle initial hash navigation
   * Smoothly scrolls to section if hash is in URL
   */
  handleInitialHash() {
    const hash = window.location.hash;
    const target = document.querySelector(hash);
    if (target) {
      setTimeout(() => {
        target.scrollIntoView({ behavior: 'smooth' });
        this.setActiveLink(target);
      }, 100);
    }
  }
}

/**
 * Initialize ScrollSpy on DOM ready
 * Ensures all elements are loaded before attaching observers
 */
document.addEventListener('DOMContentLoaded', () => {
  // Check if nav and nav-links exist before initializing
  if (document.querySelector('.nav') && document.querySelectorAll('.nav-links a[href^="#"]').length > 0) {
    new ScrollSpy();
  }
});
