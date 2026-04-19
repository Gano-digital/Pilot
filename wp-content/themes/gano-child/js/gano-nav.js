/**
 * GANO NAV — Sticky Navigation + Mega Dropdown Ecosistemas
 * <50 líneas: IntersectionObserver + classList toggle
 */

(function () {
  const nav = document.querySelector('header') || document.querySelector('nav');
  const hero = document.querySelector('.hero-gano');

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

  // Mobile hamburger
  const hamburger = document.querySelector('.gano-hamburger');
  const drawer = document.querySelector('.gano-nav-drawer');
  if (hamburger && drawer) {
    hamburger.addEventListener('click', () => {
      drawer.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
      if (!hamburger.contains(e.target) && !drawer.contains(e.target)) {
        drawer.classList.remove('open');
      }
    });
  }
})();
