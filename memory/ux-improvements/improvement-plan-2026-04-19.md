# Plan de Mejoras UX — Gano Digital · 19 Abril 2026

**Horizonte:** Martes (deadline autorizado por Diego)  
**Referencia análisis:** `memory/ux-improvements/sota-analysis-2026-04-19.md`  
**Score actual estimado:** 58/100 → Objetivo P0+P1: 75/100 → P2: 85/100

---

## SPRINT P0 — Fixes urgentes (hoy, ~2h total)

### [P0-1] Activar CTA "Contratar" en página ecosistemas

**Archivo:** `wp-content/themes/gano-child/templates/page-ecosistemas.php`  
**Tiempo:** ~30 min  
**Impacto:** Desbloquea conversión directa a carrito GoDaddy Reseller

**Qué cambiar:**
- Reemplazar el `<small class="gano-plan-pending-note">Carrito en configuración...` 
  por botones funcionales que apunten a los PFIDs del Reseller Store
- Los PFIDs están mapeados en `wp-content/plugins/gano-reseller-enhancements/includes/class-pfid-admin.php`
- El plugin `gano-reseller-enhancements` ya tiene `class-bundle-handler.php` con lógica de URL de carrito

**Criterio de éxito:** Usuario puede hacer clic en "Contratar" y llega al carrito GoDaddy.

---

### [P0-2] WCAG 2.2 §2.4.11 — scroll-padding-top

**Archivo:** `wp-content/themes/gano-child/css/gano-nav.css`  
**Tiempo:** ~5 min  
**Impacto:** El focus al Tab no queda oculto detrás del header sticky (WCAG AA)

**Cambio:**
```css
/* Agregar al final de gano-nav.css */
html {
  scroll-padding-top: 80px;
}
@media (max-width: 768px) {
  html {
    scroll-padding-top: 70px;
  }
}
```

---

### [P0-3] WCAG 4.1.2 — aria-expanded en hamburger

**Archivo:** `wp-content/themes/gano-child/js/gano-nav.js`  
**Tiempo:** ~20 min  
**Impacto:** Screen readers anuncian estado del menú mobile (WCAG AA)

**Cambios necesarios en gano-nav.js:**
1. Añadir `aria-expanded="false"` y `aria-controls="gano-mobile-menu"` al botón hamburger
2. Al abrir: `hamburger.setAttribute('aria-expanded', 'true')`
3. Al cerrar: `hamburger.setAttribute('aria-expanded', 'false')`
4. Verificar que el contenedor del menú mobile tiene `id="gano-mobile-menu"`

---

## SPRINT P1 — Mejoras de alto impacto (hoy/mañana, ~4h total)

### [P1-1] Breakpoint hamburger a 1024px

**Archivo:** `wp-content/themes/gano-child/css/gano-nav.css`  
**Tiempo:** ~20 min  
**Impacto:** Tablets 768-1024px con menú colapsado correctamente

**Cambio:** Ajustar media query de `768px` a `1024px` en el bloque del hamburger,
manteniendo el layout horizontal solo para ≥1025px.

---

### [P1-2] CTA sticky mobile en página de planes

**Archivo nuevo:** `wp-content/themes/gano-child/css/gano-mobile-cta.css`  
**Archivos a modificar:** `page-ecosistemas.php`, `functions.php` (enqueue)  
**Tiempo:** ~45 min  
**Impacto:** Conversión mobile — CTA siempre visible al hacer scroll

**Implementación:**
```css
/* gano-mobile-cta.css */
.gano-sticky-cta-mobile {
  display: none;
}
@media (max-width: 768px) {
  .gano-sticky-cta-mobile {
    display: flex;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 999;
    padding: 12px 16px;
    background: rgba(15, 25, 35, 0.96);
    backdrop-filter: blur(8px);
    border-top: 1px solid rgba(255, 107, 53, 0.3);
    justify-content: center;
    gap: 12px;
  }
  .gano-sticky-cta-mobile .gano-btn-primary {
    flex: 1;
    max-width: 320px;
    text-align: center;
    padding: 14px 24px;
    font-size: 1rem;
    font-weight: 700;
  }
}
```

---

### [P1-3] Hero image optimization (LCP)

**Archivo:** `wp-content/themes/gano-child/front-page.php`  
**Tiempo:** ~45 min  
**Impacto:** LCP estimado de ~3-4s a ≤2.5s

**Checklist:**
- [ ] Añadir `fetchpriority="high"` al `<img>` del hero
- [ ] Añadir `loading="eager"` (no lazy en hero)
- [ ] Verificar que `width` y `height` están definidos (evita CLS)
- [ ] Si la imagen es de background CSS: usar `<link rel="preload">` en `<head>`
- [ ] Crear versión WebP de `assets/images/hero-datacenter.jpg`
  - Comando: `cwebp -q 85 hero-datacenter.jpg -o hero-datacenter.webp`
  - Usar `<picture>` con `<source type="image/webp">` y fallback `<img>`

---

### [P1-4] Focus trap en panel de chat IA

**Archivo:** `wp-content/themes/gano-child/js/gano-chat.js`  
**Tiempo:** ~1h  
**Impacto:** WCAG 2.1.1 — usuarios de teclado pueden navegar el chat sin salir

**Implementación:**
```javascript
// Agregar en gano-chat.js
function trapFocus(element) {
  const focusableEls = element.querySelectorAll(
    'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
  );
  const firstFocusableEl = focusableEls[0];
  const lastFocusableEl = focusableEls[focusableEls.length - 1];

  element.addEventListener('keydown', function(e) {
    if (e.key !== 'Tab') return;
    if (e.shiftKey) {
      if (document.activeElement === firstFocusableEl) {
        lastFocusableEl.focus();
        e.preventDefault();
      }
    } else {
      if (document.activeElement === lastFocusableEl) {
        firstFocusableEl.focus();
        e.preventDefault();
      }
    }
  });
}

// Llamar cuando el chat se abre:
// trapFocus(chatPanel);
// Al cerrar: restaurar foco al botón que abrió el chat
```

---

## SPRINT P2 — Mejoras SOTA (mañana/martes, ~6h total)

### [P2-1] Trust bar post-hero

**Nuevo componente** en `front-page.php` y `homepage.css`  
**Tiempo:** ~45 min  
**Diseño:**
```
[GoDaddy Reseller] · [99.9% Uptime SLA] · [Soporte en Español] · [NVMe SSD]
```
- Fondo: `rgba(27, 79, 216, 0.08)` (azul tenue)
- Iconos SVG inline pequeños (16px) con `aria-hidden`
- `role="complementary"` para screen readers
- No bloquear LCP — carga después del hero

---

### [P2-2] Pricing visible en dropdown nav

**Archivo:** `wp-content/themes/gano-child/css/gano-nav.css` + HTML del dropdown  
**Tiempo:** ~1.5h  
**Patrón Kinsta:** Sub-ítem del dropdown muestra nombre + precio

```html
<!-- En el dropdown Ecosistemas -->
<li><a href="/ecosistemas/nucleo-prime">
  <span class="nav-plan-name">Núcleo Prime</span>
  <span class="nav-plan-price">$196.000/mes</span>
</a></li>
```

---

### [P2-3] Chat proactivo con delay 20s

**Archivo:** `wp-content/themes/gano-child/js/gano-chat.js`  
**Tiempo:** ~30 min  
**Cambio:** No mostrar el widget de chat inmediatamente. Mostrarlo tras:
- 20 segundos en la página, O
- 60% de scroll en la página
- Con animación `chatFadeUp` ya implementada

```javascript
// Modificar init() en gano-chat.js
const showChatAfterDelay = () => {
  setTimeout(() => { chatBubble.classList.add('visible'); }, 20000);
};
const showChatOnScroll = () => {
  if (window.scrollY > document.body.scrollHeight * 0.6) {
    chatBubble.classList.add('visible');
    window.removeEventListener('scroll', showChatOnScroll);
  }
};
window.addEventListener('scroll', showChatOnScroll);
showChatAfterDelay();
```

---

### [P2-4] `aria-current="page"` en navegación activa

**Archivo:** `wp-content/themes/gano-child/js/gano-nav.js` o `functions.php`  
**Tiempo:** ~20 min  
**WCAG:** 4.1.2 — ayuda a screen readers saber la página actual

```javascript
// En gano-nav.js: detectar página actual y marcar el ítem del menú
const currentPath = window.location.pathname;
document.querySelectorAll('#site-header nav a').forEach(link => {
  if (link.getAttribute('href') === currentPath) {
    link.setAttribute('aria-current', 'page');
  }
});
```

---

## SPRINT P3 — Mejoras estratégicas (post-martes)

### [P3-1] Campo de búsqueda de dominio en hero

**Complejidad:** Alta — requiere integración con GoDaddy Reseller API o redirect a Reseller Store  
**Referencia:** `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`  
**Tiempo:** ~4-6h incluyendo investigación de API  
**Impacto:** Alta — primer touchpoint de usuario en hosting

### [P3-2] Segmentación audiencia en pricing

**Tabs "Freelancer / Agencia / Empresa"** en `/ecosistemas`  
**Tiempo:** ~3h  
**Patrón:** WP Engine — filtra features mostrados según audiencia

### [P3-3] Social proof en header/hero

**Integración reviews** (Google/Trustpilot) o badge estático  
**Tiempo:** ~2h  
**Nota:** Sin reviews reales disponibles, usar métricas propias (uptime, clientes)

---

## RESUMEN TIMELINE

```
HOY (Domingo 19/04)
├── P0-1: CTA activo ecosistemas         [~30 min] 🔴 URGENTE
├── P0-2: scroll-padding-top             [~5 min]  🔴 URGENTE  
├── P0-3: aria-expanded hamburger        [~20 min] 🔴 URGENTE
└── P1-3: Hero image LCP                 [~45 min]

LUNES 20/04
├── P1-1: Breakpoint 1024px nav          [~20 min]
├── P1-2: CTA sticky mobile              [~45 min]
├── P1-4: Focus trap chat IA             [~1h]
├── P2-3: Chat proactivo delay           [~30 min]
└── P2-4: aria-current página activa     [~20 min]

MARTES 21/04 (deadline)
├── P2-1: Trust bar post-hero            [~45 min]
├── P2-2: Pricing en dropdown            [~1.5h]
└── QA + commit + PR                     [~1h]
```

---

## CRITERIOS DE ÉXITO

Al martes:
- [ ] CTA "Contratar" funcional en página de planes → conversión desbloqueada
- [ ] WCAG 2.2 AA: scroll-padding, aria-expanded, aria-current implementados
- [ ] LCP hero estimado ≤ 2.5s (WebP + fetchpriority)
- [ ] Hamburger funciona en 768-1024px (breakpoint 1024px)
- [ ] CTA sticky visible en mobile al hacer scroll en `/ecosistemas`
- [ ] Chat proactivo (no aparece inmediatamente)
- [ ] Trust bar post-hero implementada
- [ ] Score UX estimado: 75/100 (desde 58/100)

---

_Plan generado: 2026-04-19 · Siguiente: implementar P0 inmediatamente_
