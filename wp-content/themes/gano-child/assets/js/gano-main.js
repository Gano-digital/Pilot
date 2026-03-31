/**
 * Gano Digital — Main JavaScript
 * Comportamientos globales del sitio (sin frameworks externos)
 */

(function () {
    'use strict';

    // ─── MENÚ MÓVIL ──────────────────────────────────────────────────────────
    const mobileToggle = document.querySelector('.gano-mobile-toggle');
    const navMenu      = document.querySelector('.gano-nav-menu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('is-open');
            mobileToggle.setAttribute('aria-expanded', isOpen);
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !mobileToggle.contains(e.target)) {
                navMenu.classList.remove('is-open');
                mobileToggle.setAttribute('aria-expanded', false);
            }
        });
    }

    // ─── HEADER STICKY CON CAMBIO DE ESTILO AL SCROLL ─────────────────────
    const header = document.querySelector('.site-header, .header-main');
    if (header) {
        const onScroll = () => {
            if (window.scrollY > 60) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // Ejecutar al cargar
    }

    // ─── ANIMACIÓN DE ENTRADA PARA CARDS (Intersection Observer) ──────────
    const animateCards = () => {
        const cards = document.querySelectorAll(
            '.gano-card, .gano-pricing-card, .elementor-widget-wrap, .woocommerce ul.products li.product'
        );

        if (!cards.length || !('IntersectionObserver' in window)) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('gano-animated');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        cards.forEach((card, i) => {
            card.style.transitionDelay = `${i * 0.05}s`;
            observer.observe(card);
        });
    };

    // ─── SMOOTH SCROLL PARA ANCLAS INTERNAS ───────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', (e) => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const headerHeight = header ? header.offsetHeight : 80;
                const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 16;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    // ─── BÚSQUEDA DE DOMINIO (placeholder — integrar con API real) ──────
    const domainForm = document.querySelector('.gano-domain-search');
    if (domainForm) {
        domainForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const input   = domainForm.querySelector('input[name="domain"]');
            const results = domainForm.querySelector('.gano-domain-results');
            if (!input || !input.value.trim()) return;

            // Mostrar loader mientras se busca
            if (results) {
                results.innerHTML = '<p class="gano-loading">Buscando disponibilidad…</p>';
                results.style.display = 'block';
            }

            // TODO: reemplazar con llamada real a API de dominios (consultar antes de implementar)
            // Por ahora simula una respuesta para desarrollo local
            setTimeout(() => {
                const domain = input.value.trim().toLowerCase().replace(/^https?:\/\//, '');
                if (results) {
                    results.innerHTML = `
                        <div class="gano-domain-result">
                            <strong>${domain}</strong>
                            <span class="gano-badge gano-badge--green">✓ Disponible</span>
                            <button class="gano-btn gano-btn-cta">Registrar ahora</button>
                        </div>
                        <p class="gano-text-muted" style="font-size:0.8rem;margin-top:0.5rem;">
                            Resultado simulado — requiere integración con API de dominios.
                        </p>
                    `;
                }
            }, 900);
        });
    }

    // ─── ACORDEÓN FAQ ────────────────────────────────────────────────────
    document.querySelectorAll('.gano-faq-item').forEach((item) => {
        const question = item.querySelector('.gano-faq-question');
        const answer   = item.querySelector('.gano-faq-answer');
        if (!question || !answer) return;

        question.addEventListener('click', () => {
            const isOpen = item.classList.toggle('is-open');
            answer.style.maxHeight = isOpen ? answer.scrollHeight + 'px' : '0';
            question.setAttribute('aria-expanded', isOpen);
        });
    });

    // ─── NOTIFICACIÓN FLOTANTE (toast) ────────────────────────────────────
    window.ganoToast = function (message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `gano-toast gano-toast--${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position:fixed;bottom:24px;right:24px;z-index:9999;
            background:${type === 'success' ? 'var(--gano-green)' : 'var(--gano-orange)'};
            color:#fff;padding:.875rem 1.5rem;border-radius:8px;
            font-family:var(--gano-font-heading);font-weight:600;
            box-shadow:0 8px 24px rgba(0,0,0,.18);
            animation:ganoSlideIn .3s ease;
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3500);
    };

    // ─── INIT ─────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        animateCards();
    });

    // CSS para animación del toast (inyectar una sola vez)
    if (!document.getElementById('gano-toast-styles')) {
        const s = document.createElement('style');
        s.id = 'gano-toast-styles';
        s.textContent = `
            @keyframes ganoSlideIn {
                from { transform: translateY(12px); opacity: 0; }
                to   { transform: translateY(0);    opacity: 1; }
            }
            .gano-card, .gano-pricing-card, .woocommerce ul.products li.product {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity .4s ease, transform .4s ease;
            }
            .gano-animated {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        `;
        document.head.appendChild(s);
    }

})();
