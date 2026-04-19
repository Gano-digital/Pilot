/**
 * GANO NAV — Sticky Navigation + Mega Dropdown Ecosistemas
 */

(function () {
  const nav = document.querySelector('header') || document.querySelector('nav');
  const hero = document.querySelector('.hero-gano');

  // ─── aria-current: marca el ítem del menú que corresponde a la página actual ──
  // WCAG 4.1.2 + accesibilidad de pantalla (screen readers anuncian la página activa)
  (function markActivePage() {
    const currentPath = window.location.pathname.replace(/\/$/, '') || '/';
    document.querySelectorAll('#site-header nav a, header nav a').forEach(function (link) {
      const href = (link.getAttribute('href') || '').replace(/\/$/, '');
      if (href && href === currentPath) {
        link.setAttribute('aria-current', 'page');
        link.closest('li') && link.closest('li').classList.add('current-menu-item');
      }
    });
  })();

  // ─── Pricing hints en sub-ítems del dropdown Ecosistemas ──────────────────────
  // Inyecta el precio como texto secundario visible si el sub-ítem existe en el DOM.
  // No rompe si Elementor no renderiza los sub-ítems exactos.
  (function injectNavPrices() {
    var priceMap = {
      'nucleo-prime':    '$196.000',
      'fortaleza-delta': '$450.000',
      'bastion-sota':    '$890.000',
      'ultimate-wp':     '$1.200.000',
    };
    Object.keys(priceMap).forEach(function (slug) {
      var link = document.querySelector('header a[href*="' + slug + '"], nav a[href*="' + slug + '"]');
      if (!link || link.querySelector('.nav-plan-price')) return;
      var price = document.createElement('span');
      price.className = 'nav-plan-price';
      price.setAttribute('aria-hidden', 'true'); // solo decorativo; el precio real está en la página
      price.textContent = priceMap[slug] + '/mes';
      link.appendChild(price);
    });
  })();

  if (!nav || !hero) return;

  // Cuando el hero sale de la pantalla, la nav se vuelve sólida
  const observer = new IntersectionObserver(([entry]) => {
    if (entry.isIntersecting) {
      nav.classList.remove('nav-solid');
    } else {
      nav.classList.add('nav-solid');
    }
  }, { threshold: 0 });

  observer.observe(hero);

  // Toggle mega-dropdown al click/hover
  const ecoDropdown = document.querySelector('[data-dropdown="ecosistemas"]');
  if (ecoDropdown) {
    ecoDropdown.addEventListener('click', (e) => {
      e.preventDefault();
      ecoDropdown.classList.toggle('active');
    });

    // Cerrar al click afuera
    document.addEventListener('click', (e) => {
      if (!ecoDropdown.contains(e.target)) {
        ecoDropdown.classList.remove('active');
      }
    });
  }

  // Mobile hamburger — WCAG 4.1.2 (aria-expanded/controls) + Esc
  const hamburger = document.querySelector('.gano-hamburger');
  const drawer = document.querySelector('.gano-nav-drawer');
  if (hamburger && drawer) {
    if (!drawer.id) drawer.id = 'gano-mobile-menu';
    hamburger.setAttribute('aria-controls', 'gano-mobile-menu');
    hamburger.setAttribute('aria-expanded', 'false');

    const openMenu = () => {
      drawer.classList.add('open');
      hamburger.setAttribute('aria-expanded', 'true');
    };
    const closeMenu = () => {
      drawer.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    };

    hamburger.addEventListener('click', () => {
      drawer.classList.contains('open') ? closeMenu() : openMenu();
    });
    document.addEventListener('click', (e) => {
      if (!hamburger.contains(e.target) && !drawer.contains(e.target)) {
        closeMenu();
      }
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });
  }
})();
