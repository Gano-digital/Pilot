# Análisis SOTA UX — Gano Digital · 19 Abril 2026

**Autor:** Claude (sesión autorizada por Diego)  
**Objetivo:** Auditoría integral UX/navegación para hoja de ruta de mejoras al martes.  
**Método:** Análisis codebase + investigación competidores + WCAG 2.2 AA + mapeo de flujos.

---

## 1. ESTADO ACTUAL — INVENTARIO

### Fortalezas existentes (mantener)

| Área | Estado | Evidencia |
|------|--------|-----------|
| Tokens de marca | ✅ Sólido | `style.css` — paleta completa, escalas tipográficas, spacing |
| Arquitectura información | ✅ Documentada | `site-ia-wave3-proposed.md` — 3 clics a checkout |
| WCAG planning | ✅ Checklist detallado | `ux-heuristics-checklist-gano.md` — 5 bloques |
| Nav spec | ✅ Completa | `navigation-primary-spec-2026.md` — P0/P1/P2 definidos |
| Motion responsable | ✅ Implementado | `prefers-reduced-motion` en GSAP + IntersectionObserver |
| Seguridad UI | ✅ Hardened | CSRF nonce, rate limit, sanitización |
| Encoding UTF-8 | ✅ Limpio | Grep en templates/CSS — cero secuencias Mojibake |

### Gaps críticos (no implementados aún)

| Área | Gap | Archivo referencia |
|------|-----|-------------------|
| Nav hamburguesa mobile | Sin `aria-expanded`, `aria-controls` | `gano-nav.js` |
| Focus trap en modales/chat | No implementado | `gano-chat.js` |
| `aria-current="page"` en nav | Ausente | `gano-nav.css`, `functions.php` |
| Skip link visible | Definido en checklist, no verificado en HTML | `ux-a11y-wave3-notes.md` |
| Breadcrumbs en pilares | No implementado | `navigation-primary-spec-2026.md` §6 |
| Búsqueda global | No existe en header | `ux-heuristics-checklist-gano.md` H7 |
| NIT en footer | Placeholder `[NIT]` | Templates legales |
| Skeleton screens | Definidos en checklist, no implementados | `homepage.css` |
| `fetchpriority="high"` en hero | No verificado en `front-page.php` | CWV checklist |
| Dropdown mega-nav | Solo 2 breakpoints (768px); sin 1024px | `gano-nav.css` |

---

## 2. AUDITORÍA COMPETIDORES HOSTING 2026

### 2.1 Patrones de navegación observados

| Competidor | Nav estructura | CTA primario | Diferenciador UX |
|-----------|---------------|-------------|-----------------|
| **Kinsta** | Sticky dark, mega-dropdown categorizado, búsqueda global | "Get Started" naranja permanente | Dashboard UX reconocido como el mejor del sector; pricing transparente en hover |
| **WP Engine** | Minimal top bar + nav horizontal, dropdown por caso de uso | "Start Free Trial" | Segmentación por rol (developer/agency/enterprise) visible en nav |
| **SiteGround** | Nav con iconos coloreados por categoría, chat widget prominente | "Get Plan" con badge de descuento | Trust signal: "4.8★ Trustpilot" en header |
| **Bluehost** | Nav simple ≤5 ítems, CTA "Get Started" + countdown de oferta | Precio tachado + precio real | Beginner-friendly: wizard de 3 pasos en hero |

### 2.2 Patrones SOTA 2026 en hosting

**Tendencias dominantes:**
1. **Pricing visible sin clic** — precios en dropdown o hover-card (Kinsta, SiteGround)
2. **Social proof persistente** — rating/reviews en header o sticky bar
3. **CTA flotante en mobile** — botón "Elegir plan" sticky bottom en iOS/Android
4. **Performance metrics hero** — "99.99% uptime", "<200ms TTFB" como headline
5. **Segmentación por audiencia** — tabs "Freelancer / Agencia / Empresa" en pricing
6. **Chat proactivo con delay** — aparece tras 15-20s o 50% scroll, no inmediato
7. **Trust bar debajo del hero** — logos clientes + métrica clave + certificaciones

### 2.3 Brecha Gano Digital vs. competidores

| Patrón SOTA | Competidores | Gano Digital | Brecha |
|------------|-------------|-------------|--------|
| Pricing en dropdown | Kinsta, SiteGround | ❌ Solo en `/ecosistemas` | Alta |
| Social proof header | SiteGround, Bluehost | ❌ No existe | Alta |
| CTA mobile sticky | WP Engine, Kinsta | ❌ No implementado | Media |
| Performance metrics hero | Todos | ✅ Parcial (SOTA hub) | Baja |
| Segmentación audiencia | WP Engine | ❌ No existe | Media |
| Trust bar post-hero | Bluehost, SiteGround | ❌ No existe | Media |

---

## 3. AUDITORÍA WCAG 2.2 AA

### 3.1 Criterios cumplidos

| Criterio | Estado | Evidencia |
|---------|--------|-----------|
| 1.4.3 Contraste texto (4.5:1) | ✅ | `--gano-gold` (#D4AF37) vs `--gano-dark` (#0F1923) = 7.8:1 |
| 1.4.4 Resize text | ✅ | CSS con rem/em, no px fijos en body |
| 2.1.1 Keyboard | ⚠️ Parcial | Tab/Enter/Esc en formas, no verificado en chat/quiz |
| 1.4.10 Reflow | ✅ | CSS responsive sin overflow horizontal |
| 2.4.7 Focus Visible | ⚠️ Parcial | Estilos definidos, `outline: none` en Elementor puede interferir |
| 3.1.1 Language of page | ✅ | `lang="es"` en `<html>` (por defecto WP) |
| 1.1.1 Non-text content | ⚠️ Parcial | Hero image presente, `alt` pendiente de verificar |

### 3.2 Criterios WCAG 2.2 nuevos (vs 2.1)

| Criterio 2.2 | Descripción | Estado Gano |
|-------------|-------------|------------|
| 2.4.11 Focus Not Obscured | El foco no queda cubierto por sticky header | ⚠️ Riesgo — header fixed 70px puede ocultar foco |
| 2.4.12 Focus Not Obscured (Enhanced) | Mismo, nivel AAA | No aplica (AA target) |
| 2.5.3 Label in Name | Texto visible incluido en accessible name | ⚠️ Verificar botones ícono-solo |
| 2.5.7 Dragging Movements | Alternativa a drag | ✅ N/A (no hay drag en UI) |
| 2.5.8 Target Size (Minimum) | 24×24px mínimo | ⚠️ Iconos nav < 24px posible |
| 3.2.6 Consistent Help | Ayuda consistente en misma ubicación | ⚠️ Chat IA presente pero WhatsApp varía por página |
| 3.3.7 Redundant Entry | No pedir datos ya ingresados | ✅ N/A (formularios simples) |
| 3.3.8 Accessible Authentication | Autenticación sin cognitive test | ✅ N/A (sin login en flujo público) |

### 3.3 Problema específico: foco cubierto por header sticky

```css
/* PROBLEMA en gano-nav.css */
body { padding-top: 70px !important; }

/* El focus ring puede quedar debajo del header cuando
   se hace Tab desde arriba hacia abajo en la página.
   WCAG 2.2 §2.4.11: Focus Not Obscured (AA) */

/* SOLUCIÓN: scroll-padding-top */
html {
  scroll-padding-top: 80px; /* header height + buffer */
}
```

### 3.4 Contraste en glassmorphism (riesgo)

El fondo glassmorphism `rgba(15,17,21,0.85)` con backdrop-filter blur puede tener
contraste variable según el contenido debajo. Requiere verificación con herramienta
en contexto real (no en fondo sólido).

**Recomendación:** Añadir fallback de fondo sólido cuando `backdrop-filter` no está disponible
y asegurar contraste mínimo 4.5:1 incluso sin blur.

---

## 4. MAPEO DE FLUJOS CRÍTICOS

### Flujo 1: Inicio → Selección de plan → Checkout GoDaddy

```
[Home /]
  ↓ Hero CTA "Elegir plan →"         ← 1 clic
[/ecosistemas]
  ↓ Card plan + "Contratar" button   ← 2 clics  
[GoDaddy Reseller Cart]              ← 3 clics ✅ Dentro del objetivo
  ↓ Checkout GoDaddy (marca blanca)
[Confirmación]
```

**Friction points identificados:**
- F1: CTA "Contratar" en `/ecosistemas` aún muestra "Carrito en configuración · Contacta para activar" (página-ecosistemas.php:200) — **bloqueo de conversión activo**
- F2: Sin preview de precio en el dropdown nav — usuario debe navegar para ver precios
- F3: Sin indicador de progreso en el checkout GoDaddy (fuera de control del sitio)
- F4: Modal/overlay de chat aparece sin delay proactivo — puede interrumpir flujo de compra

### Flujo 2: Home → Búsqueda de dominio

```
[Home /]
  ↓ ??? No hay widget de búsqueda de dominio en hero
```

**Friction point crítico F5:** Todos los competidores principales tienen un campo de
búsqueda de dominio en el hero (input + botón). Gano Digital no tiene este elemento.
Es el pattern UX más esperado en hosting.

### Flujo 3: Mobile — Navegación y checkout

```
[Home mobile]
  ↓ Hamburger tap
[Menú drawer]
  ↓ "Ecosistemas" tap → acordeón
[Sub-ítems visibles]
  ↓ Plan tap
[/ecosistemas o sub-página]
  ↓ CTA
[Checkout]
```

**Friction points:**
- F6: Breakpoint hamburguesa a 768px (demasiado pequeño — tablets 768-1024px sin menú colapsado)
- F7: Sin CTA sticky en mobile durante el scroll de la página de planes
- F8: `gano-nav.js` — hamburger sin `aria-expanded`/`aria-controls` (WCAG 4.1.2)

### Flujo 4: Home → Quiz Soberanía Digital

```
[Home /]
  ↓ Sección quiz (si visible)
[Quiz step 1]
  ↓ Respuestas
[Resultado + CTA plan recomendado]
```

**Estado:** Quiz implementado (`gano-sovereignty-quiz.js`, `gano-bundle-quiz.js`) pero
su posición en la homepage y accesibilidad (keyboard nav entre pasos) no verificada.

---

## 5. PROBLEMAS CONOCIDOS — ANÁLISIS DETALLADO

### P-01: CTA de conversión bloqueado

**Archivo:** `wp-content/themes/gano-child/templates/page-ecosistemas.php:200`  
**Código actual:**
```html
<small class="gano-plan-pending-note">Carrito en configuración · Contacta para activar</small>
```
**Impacto:** Bloqueo directo de conversión. El usuario llega a la página de planes y
no puede comprar. Prioridad P0.

### P-02: Dropdown breakpoint incorrecto

**Archivo:** `wp-content/themes/gano-child/css/gano-nav.css:104`  
**Problema:** Solo breakpoint en 768px. Tablets (768-1024px) muestran nav horizontal
completo con espacio insuficiente.  
**Recomendación:** Agregar breakpoint 1024px para colapsar a hamburguesa.

### P-03: `aria-expanded` ausente en hamburger

**Archivo:** `wp-content/themes/gano-child/js/gano-nav.js`  
**WCAG:** 4.1.2 Name, Role, Value (AA)  
**Fix necesario:** Agregar `aria-expanded="false"` al botón hamburger y actualizar
a `"true"` al abrir, más `aria-controls="gano-mobile-menu"` para conectar el button
con el contenido del menú.

### P-04: `scroll-padding-top` faltante

**Archivo:** `wp-content/themes/gano-child/css/gano-nav.css`  
**WCAG 2.2:** 2.4.11 Focus Not Obscured  
**Fix:** `html { scroll-padding-top: 80px; }`

### P-05: Sin campo de búsqueda de dominio en hero

**Impacto UX:** Fricción alta — expectativa estándar en hosting.  
**Referencia:** Kinsta, SiteGround, Bluehost — todos tienen input de dominio en hero.  
**Posibles implementaciones:** Widget GoDaddy Reseller embed, formulario custom
que redirige a carrito Reseller con dominio pre-llenado.

### P-06: NIT de empresa ausente

**Archivos:** `page-terminos.php`, `page-privacidad.php`, footer templates  
**Impacto:** Confianza legal. Diego debe proveer el NIT real para reemplazar `[NIT]`.

### P-07: Chat IA sin focus trap

**Archivo:** `wp-content/themes/gano-child/js/gano-chat.js`  
**WCAG:** 2.1.2 No Keyboard Trap (debe poder salir), pero al abrir el panel del chat,
el Tab debería ciclarse dentro del panel (focus trap para usabilidad).  
**Fix:** Implementar focus trap con `tabindex` en el panel abierto.

### P-08: CTA mobile sticky ausente

**Impacto:** En páginas largas de planes, el usuario mobile pierde el CTA al hacer scroll.  
**Patrón SOTA:** CTA flotante en bottom mobile (fixed, z-index alto).

---

## 6. ANÁLISIS CORE WEB VITALS (estimado)

> Sin acceso a servidor en producción; estimaciones basadas en código.

| Métrica | Objetivo | Estimación | Riesgo |
|---------|----------|-----------|--------|
| LCP | ≤ 2.5s | ~3-4s estimado | Alto |
| CLS | ≤ 0.1 | ~0.05 estimado | Bajo-Medio |
| INP | ≤ 200ms | ~150ms estimado | Bajo |

**Riesgos LCP:**
- `hero-datacenter.jpg` (219 KB) — sin WebP verificado
- GSAP + ScrollTrigger cargan síncronamente si no tienen `defer`
- Plus Jakarta Sans (Google Fonts) — potencial render-blocking si no tiene `display=swap`
- Múltiples CSS files (11) sin critical CSS inlineado

**Optimizaciones LCP prioritarias:**
1. Convertir `hero-datacenter.jpg` a WebP + `fetchpriority="high"`
2. Verificar que Google Fonts usa `display=swap`
3. Critical CSS inline para above-the-fold
4. Defer GSAP cuando no sea crítico para primer render

---

## 7. PRIORIDADES DE MEJORA — MATRIZ

| ID | Problema | Prioridad | Esfuerzo | Impacto | Tipo |
|----|---------|-----------|---------|---------|------|
| P-01 | CTA conversión bloqueado (carrito config) | **P0** | Bajo | Crítico | Bug |
| P-04 | `scroll-padding-top` faltante (WCAG 2.4.11) | **P0** | Muy bajo | Alto | A11y |
| P-03 | `aria-expanded` hamburger ausente (WCAG 4.1.2) | **P0** | Bajo | Alto | A11y |
| P-02 | Breakpoint 1024px para hamburger | **P1** | Bajo | Medio | UX |
| P-08 | CTA mobile sticky | **P1** | Medio | Alto | UX |
| LCP-1 | Hero WebP + fetchpriority | **P1** | Bajo | Alto | Perf |
| P-07 | Focus trap en chat IA | **P1** | Medio | Medio | A11y |
| SOTA-1 | Trust bar post-hero (logos/rating) | **P2** | Medio | Medio | Conversión |
| SOTA-2 | Pricing visible en dropdown nav | **P2** | Medio | Alto | UX |
| P-05 | Campo búsqueda de dominio en hero | **P2** | Alto | Alto | UX |
| SOTA-3 | CTA proactivo chat con delay 20s | **P2** | Bajo | Medio | UX |
| P-06 | NIT empresa en footer/legal | **P2** | Muy bajo (requiere Diego) | Medio | Legal |
| SOTA-4 | Segmentación audiencia en pricing | **P3** | Alto | Medio | UX |
| SOTA-5 | Social proof persistente en header | **P3** | Medio | Bajo | Conversión |

---

## 8. RECOMENDACIONES INMEDIATAS (próximas 48h)

### R1 — Fix P0 urgente: CTA "Contratar" activo (30 min)

Reemplazar el mensaje de "carrito en configuración" por CTA funcional al carrito
GoDaddy Reseller. Los PFIDs están mapeados en `class-pfid-admin.php`.

### R2 — Fix WCAG P0: scroll-padding-top + aria-expanded (30 min)

Dos cambios de una línea cada uno que cubren WCAG 2.2 §2.4.11 y §4.1.2.

### R3 — Hamburger breakpoint 1024px (20 min)

Un breakpoint adicional en `gano-nav.css` cubre tablets y mejora la experiencia
en dispositivos 768-1024px.

### R4 — Hero image optimization (45 min)

Verificar/agregar WebP + `fetchpriority="high"` + dimensiones explícitas en
`front-page.php` para mejorar LCP estimado de ~3-4s a ≤2.5s.

### R5 — CTA mobile sticky en ecosistemas (1h)

Añadir elemento sticky al bottom del viewport solo en mobile durante la página
de planes, con CTA principal "Contratar" naranja.

---

## 9. COMPARATIVO FINAL — GANO VS. SOTA

```
                    Kinsta   SiteGround   WP Engine   Gano Digital
Navigation          ████░    ████░        ████░       ███░░
Mobile UX           █████    ████░        ████░       ███░░
WCAG compliance     ████░    ████░        ███░░       ██░░░  ← gap mayor
Conversion flow     █████    ████░        ████░       ██░░░  ← CTA bloqueado
Performance         █████    █████        ████░       ███░░  ← LCP en riesgo
Trust signals       ████░    █████        ████░       ██░░░  ← sin social proof
Domain search       █████    █████        ████░       ░░░░░  ← ausente
```

**Score estimado actual Gano Digital:** 58/100  
**Score objetivo post-mejoras P0+P1:** 75/100  
**Score objetivo post-mejoras P2:** 85/100

---

## 10. PRÓXIMOS PASOS

Ver plan de implementación → `memory/ux-improvements/improvement-plan-2026-04-19.md`

**Archivos a modificar en Fase 2 (implementación):**
- `wp-content/themes/gano-child/css/gano-nav.css` — scroll-padding, breakpoint 1024px
- `wp-content/themes/gano-child/js/gano-nav.js` — aria-expanded, aria-controls
- `wp-content/themes/gano-child/templates/page-ecosistemas.php` — CTA activo
- `wp-content/themes/gano-child/front-page.php` — hero image optimization
- CSS nuevo: `gano-mobile-cta.css` — sticky bottom CTA
- JS nuevo/ajuste: focus trap en `gano-chat.js`

---

_Análisis generado: 2026-04-19 · Claude Sonnet 4.6 · Sesión autorizada por Diego_  
_Metodología: codebase analysis + web research + WCAG 2.2 AA + competitor benchmarking_
