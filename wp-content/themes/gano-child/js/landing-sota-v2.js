/**
 * Gano Digital — Landing SOTA V2
 * Scoped to .gano-landing-sota. Encapsulated to avoid global namespace pollution.
 */
(function () {
  'use strict';

  const landing = document.querySelector('.gano-landing-sota');
  if (!landing) return;

  /* ---- Navbar scroll effect ---- */
  const navbar = landing.querySelector('#navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  /* ---- Generate particles ---- */
  const particlesContainer = landing.querySelector('#particles');
  if (particlesContainer) {
    for (let i = 0; i < 30; i++) {
      const p = document.createElement('div');
      p.className = 'particle';
      p.style.left = Math.random() * 100 + '%';
      p.style.top = Math.random() * 100 + '%';
      p.style.animationDelay = Math.random() * 6 + 's';
      p.style.animationDuration = (4 + Math.random() * 4) + 's';
      particlesContainer.appendChild(p);
    }
  }

  /* ---- Scroll reveal ---- */
  const revealElements = landing.querySelectorAll('.reveal');
  if (revealElements.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    revealElements.forEach(function (el) { observer.observe(el); });
  } else {
    revealElements.forEach(function (el) { el.classList.add('visible'); });
  }

  /* ---- Pricing tabs (delegation) ---- */
  landing.addEventListener('click', function (e) {
    const tab = e.target.closest('.pricing-tab');
    if (!tab) return;
    e.preventDefault();
    landing.querySelectorAll('.pricing-tab').forEach(function (t) {
      t.classList.remove('active');
    });
    tab.classList.add('active');
  });

  /* ---- Mobile menu toggle ---- */
  landing.addEventListener('click', function (e) {
    const toggle = e.target.closest('.mobile-toggle');
    if (!toggle) return;
    e.preventDefault();
    const navLinks = landing.querySelector('.nav-links');
    const navCta = landing.querySelector('.nav-cta');
    if (navLinks && navCta) {
      const isHidden = navLinks.style.display === 'none' || getComputedStyle(navLinks).display === 'none';
      if (isHidden) {
        navLinks.style.display = 'flex';
        navCta.style.display = 'flex';
        navLinks.style.flexDirection = 'column';
        navLinks.style.position = 'absolute';
        navLinks.style.top = '100%';
        navLinks.style.left = '0';
        navLinks.style.right = '0';
        navLinks.style.background = 'rgba(5,5,8,0.95)';
        navLinks.style.padding = '24px';
        navLinks.style.backdropFilter = 'blur(20px)';
        navLinks.style.borderBottom = '1px solid var(--border-subtle)';
      } else {
        navLinks.style.display = '';
        navCta.style.display = '';
        navLinks.style.flexDirection = '';
        navLinks.style.position = '';
        navLinks.style.top = '';
        navLinks.style.left = '';
        navLinks.style.right = '';
        navLinks.style.background = '';
        navLinks.style.padding = '';
        navLinks.style.backdropFilter = '';
        navLinks.style.borderBottom = '';
      }
    }
  });

  /* ---- Smooth scroll for anchor links ---- */
  landing.addEventListener('click', function (e) {
    const anchor = e.target.closest('a[href^="#"]');
    if (!anchor) return;
    const targetId = anchor.getAttribute('href');
    if (!targetId || targetId === '#') return;
    const target = document.querySelector(targetId);
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });

})();
