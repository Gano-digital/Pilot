class MobileMenu {
  constructor() {
    this.toggle = document.querySelector('.mobile-toggle');
    this.menu = document.querySelector('.mobile-menu');
    this.backdrop = document.querySelector('.mobile-menu-backdrop');
    this.closeButton = document.querySelector('.mobile-menu-close');
    this.menuLinks = document.querySelectorAll('.mobile-menu-items a');

    if (!this.toggle || !this.menu) {
      console.warn('MobileMenu: Required elements not found');
      return;
    }

    this.isOpen = false;
    this.attachEventListeners();
  }

  attachEventListeners() {
    this.toggle.addEventListener('click', () => this.toggleMenu());

    if (this.closeButton) {
      this.closeButton.addEventListener('click', () => this.closeMenu());
    }

    if (this.backdrop) {
      this.backdrop.addEventListener('click', () => this.closeMenu());
    }

    this.menuLinks.forEach(link => {
      link.addEventListener('click', () => this.closeMenu());
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.isOpen) {
        this.closeMenu();
      }
    });

    this.menu.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        const focusableElements = this.menu.querySelectorAll(
          'button, a, [tabindex]:not([tabindex="-1"])'
        );
        if (focusableElements.length === 0) return;

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    });
  }

  toggleMenu() {
    if (this.isOpen) {
      this.closeMenu();
    } else {
      this.openMenu();
    }
  }

  openMenu() {
    this.menu.classList.add('open');
    if (this.backdrop) {
      this.backdrop.classList.add('visible');
    }
    this.toggle.classList.add('open');
    document.body.style.overflow = 'hidden';

    this.isOpen = true;

    if (this.closeButton) {
      this.closeButton.focus();
    }

    this.menu.setAttribute('aria-hidden', 'false');
  }

  closeMenu() {
    this.menu.classList.remove('open');
    if (this.backdrop) {
      this.backdrop.classList.remove('visible');
    }
    this.toggle.classList.remove('open');
    document.body.style.overflow = '';

    this.isOpen = false;

    this.toggle.focus();

    this.menu.setAttribute('aria-hidden', 'true');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new MobileMenu();
});
