# Gano Digital — Checklist UX Mastery

> **Propósito:** checklist verificable (checkboxes) para auditar la experiencia de usuario del sitio gano.digital.  
> Alineado con las **10 heurísticas de Nielsen**, métricas de **Core Web Vitals percibidos** y consideraciones **mobile-first**.  
> Complementa el brief maestro oleada 3 § 5 (`memory/research/gano-wave3-brand-ux-master-brief.md`).  
> **Última actualización:** Abril 2026.

---

## Cómo usar este checklist

1. Recorre cada sección y marca `[x]` cuando el criterio esté cumplido en producción.
2. Deja `[ ]` y agrega una nota inline cuando el criterio esté pendiente.
3. Vincula el ítem pendiente a un issue de GitHub con `#<número>` si ya existe.
4. Revisa en cada release menor o cambio significativo de theme/Elementor.

---

## Bloque 1 — Heurísticas de Nielsen aplicadas a Gano Digital

### H1 · Visibilidad del estado del sistema

- [ ] El usuario sabe en qué paso del flujo de compra se encuentra (indicador de pasos en checkout WooCommerce/Reseller).
- [ ] Los formularios (contacto, chat IA, quiz) muestran estado de carga (`loading` spinner o mensaje "Enviando…") mientras procesan.
- [ ] Después de enviar un formulario se muestra mensaje de éxito o error claro, no silencio.
- [ ] El botón de CTA principal tiene estado `:focus-visible` con contorno visible (no solo `:focus`).
- [ ] Los links activos del menú reflejan la página actual (`aria-current="page"` o clase CSS equivalente).

### H2 · Concordancia con el mundo real

- [ ] El copy usa terminología comprensible para el público objetivo (empresario colombiano/LATAM, no solo devs): "ecosistema", "plan", "soberanía" explicados en contexto.
- [ ] Los iconos de funcionalidades tienen etiqueta de texto visible o tooltip, no solo glifos.
- [ ] Las fechas y horas usan formato colombiano (dd/mm/aaaa, zona America/Bogota).
- [ ] Los precios muestran **COP** explícito, no solo signo `$` ambiguo.

### H3 · Control y libertad del usuario

- [ ] El usuario puede cerrar modales/popups con tecla `Esc` y con botón visible `×`.
- [ ] El quiz de soberanía digital permite retroceder o cancelar sin perder datos anteriores.
- [ ] Las animaciones GSAP/ScrollTrigger que cubren contenido tienen alternativa de salto (`skip animation`), especialmente en onboarding.
- [ ] El chat IA tiene botón de cierre/colapso accesible vía teclado.

### H4 · Consistencia y estándares

- [ ] Los patrones de tarjeta (pilares SOTA y shop) usan la misma estructura: imagen → título → descripción → CTA.
- [ ] Los colores de CTA son consistentes: naranja `--gano-orange` para CTA principal, azul `--gano-blue` para secundario.
- [ ] Los nombres de planes (Núcleo Prime, Fortaleza Delta, Bastión SOTA) son idénticos en hero, shop, schema JSON-LD y meta tags.
- [ ] El tono de voz es uniforme en toda la página: no mezclar "tú" y "usted" en la misma sección.

### H5 · Prevención de errores

- [ ] Los campos de formulario con formato específico (email, teléfono, NIT) tienen `pattern` HTML5 o validación JS antes del envío.
- [ ] El checkout WooCommerce/Reseller advierte si el plan seleccionado incluye restricciones (p. ej. solo Colombia).
- [ ] Los links externos se abren en nueva pestaña con `rel="noopener noreferrer"` y aviso visual (ícono externo).
- [ ] Las acciones destructivas (cancelar suscripción, borrar cuenta) requieren confirmación secundaria.

### H6 · Reconocimiento antes que recuerdo

- [ ] El menú de navegación es visible sin necesidad de hacer scroll (sticky o siempre visible en desktop).
- [ ] Los CTAs principales son visibles en el primer pantallazo de cada landing, sin necesidad de scroll.
- [ ] Los breadcrumbs están presentes en páginas internas (pilares SOTA, páginas de plan).
- [ ] Los campos de formulario tienen `label` visible (no solo placeholder que desaparece al escribir).

### H7 · Flexibilidad y eficiencia de uso

- [ ] El sitio tiene búsqueda accesible desde la cabecera para usuarios avanzados que saben qué buscan.
- [ ] Los atajos de teclado estándar funcionan (`Tab`, `Shift+Tab`, `Enter`, `Esc`) en toda la interfaz interactiva.
- [ ] El chat IA permite escribir directamente sin pasos previos de registro.
- [ ] Los CTAs de "Hablar con ventas" están disponibles en más de un punto del flujo (hero, pricing, footer).

### H8 · Estética y diseño minimalista

- [ ] Hay un solo CTA de peso primario por viewport en el hero; los secundarios tienen jerarquía visual claramente menor.
- [ ] El fondo glassmorphism no compite visualmente con el texto superpuesto (contraste mínimo WCAG AA: 4.5:1 para texto normal).
- [ ] El color `--gano-gold` sobre fondo `--gano-dark` pasa contraste WCAG AA (verificar con herramienta).
- [ ] Las secciones de la home tienen separación visual clara (no un muro continuo de contenido).
- [ ] Las imágenes decorativas tienen `alt=""` y `aria-hidden="true"`.

### H9 · Ayuda al usuario a reconocer, diagnosticar y recuperarse de errores

- [ ] Los mensajes de error en formularios son específicos: "El correo no tiene un formato válido" en vez de "Error en el campo".
- [ ] Los errores de pago (Wompi) devuelven un mensaje amigable con opción de reintentar o contactar soporte.
- [ ] Las páginas 404 y 500 tienen diseño personalizado con enlace de retorno al home.
- [ ] El chat IA muestra un mensaje de fallback si la API de Gemini falla o supera rate limit.

### H10 · Ayuda y documentación

- [ ] Hay una sección FAQ (con schema `FAQPage` JSON-LD) accesible desde el menú o pie de página.
- [ ] Los términos técnicos usados en los planes tienen tooltip o enlace a glosario/FAQ.
- [ ] El proceso de compra/onboarding tiene una guía de inicio rápido o email de bienvenida documentado.
- [ ] Hay un link visible a la política de privacidad y términos de servicio en el pie de página.

---

## Bloque 2 — Core Web Vitals percibidos (performance UX)

> Los CWV son métricas técnicas, pero su impacto percibido es experiencia de usuario. Este bloque cubre lo verificable sin acceso a servidor.

### LCP — Largest Contentful Paint (objetivo: ≤ 2.5 s)

- [ ] La imagen hero tiene `width` y `height` atributos definidos (evita CLS y habilita optimización del navegador).
- [ ] La imagen hero se sirve en **WebP** con fallback `<picture>` + `<source>` para navegadores sin soporte.
- [ ] La imagen hero tiene `fetchpriority="high"` o `loading="eager"` (no `loading="lazy"`).
- [ ] No hay fuentes de Google Fonts bloqueantes; se usa `display=swap` o `display=optional`.
- [ ] El CSS crítico (above the fold) está inlineado o cargado sin bloqueo de render.
- [ ] Los videos de loop en hero tienen `poster` estático de tamaño correcto para evitar saltos.

### CLS — Cumulative Layout Shift (objetivo: ≤ 0.1)

- [ ] Todas las imágenes tienen `width` y `height` o se reserva espacio con `aspect-ratio` en CSS.
- [ ] Los iframes (mapas, videos) tienen contenedor con dimensiones fijas o `aspect-ratio`.
- [ ] Los skeleton screens o placeholders tienen la misma altura que el contenido final (sin "salto" al cargarse).
- [ ] Los banners de cookies/avisos legales no desplazan el contenido principal al aparecer.
- [ ] Las fuentes web tienen `font-display: swap` o `optional` para evitar layout shift por FOIT/FOUT.

### INP — Interaction to Next Paint (objetivo: ≤ 200 ms)

- [ ] Los event listeners de botones y formularios no ejecutan operaciones síncronas pesadas en el hilo principal.
- [ ] Los scripts de terceros (chat, analytics) se cargan con `defer` o `async`.
- [ ] El chat IA y el quiz de soberanía no bloquean el hilo principal al inicializar (usar `requestIdleCallback` si aplica).

### FID / Responsividad general

- [ ] Los elementos interactivos tienen un área táctil mínima de **44 × 44 px** (CSS o padding explícito).
- [ ] No hay scripts bloqueantes (`<script>` sin `defer`/`async`) en el `<head>` que retrasen la interactividad.

### Performance percibida (psicología de carga)

- [ ] Las secciones con contenido dinámico (pilares, precios) usan **skeleton screens** o animación de placeholder mientras cargan.
- [ ] El scroll reveal de tarjetas usa `IntersectionObserver` (ver `scroll-reveal.js`) y no retrasa más de 100 ms por elemento.
- [ ] La primera acción del usuario (clic en CTA hero) responde en < 100 ms visualmente (aunque el servidor tarde más).

---

## Bloque 3 — Mobile UX (primera pantalla y flujos táctiles)

### Diseño mobile-first

- [ ] El viewport meta tag existe y es correcto: `<meta name="viewport" content="width=device-width, initial-scale=1">`.
- [ ] Ningún elemento horizontal produce scroll lateral en viewport de 360 px de ancho.
- [ ] El texto body es mínimo **16 px** en móvil (evita zoom automático en iOS Safari).
- [ ] Los CTA no quedan ocultos bajo el menú sticky o bajo la barra del navegador en iOS Safari.

### Menú y navegación táctil

- [ ] El menú hamburguesa es accesible con `aria-expanded`, `aria-controls` y es operable con teclado.
- [ ] El menú se cierra al hacer tap fuera de él o al presionar `Esc`.
- [ ] Los ítems del menú tienen separación táctil suficiente (min 44 px de alto).
- [ ] El logo redirige a home desde cualquier página interior (incluyendo en móvil).

### Primer pantallazo (above the fold en móvil)

- [ ] El CTA principal del hero es visible sin hacer scroll en dispositivos de 667 px de alto (iPhone SE landscape como referencia).
- [ ] No hay popups que cubran el CTA principal en el primer pantallazo en móvil.
- [ ] El texto del hero es legible sobre la imagen/video de fondo sin necesidad de acercar zoom.
- [ ] El formulario de contacto rápido (si existe en hero) no desborda el viewport en móvil.

### Formularios y checkout en móvil

- [ ] Los campos de formulario usan el tipo correcto (`type="email"`, `type="tel"`, `type="number"`) para activar el teclado correcto en iOS/Android.
- [ ] El checkout de WooCommerce/Wompi es completamente funcional en móvil sin scroll horizontal.
- [ ] Los botones de pago (Wompi: PSE, Nequi, Daviplata) tienen tamaño táctil adecuado.
- [ ] Los mensajes de error de formulario aparecen cerca del campo con error, no solo al tope de la página.

### Rendimiento en redes lentas (3G / datos móviles Colombia)

- [ ] Las imágenes usan `loading="lazy"` para todo lo que está below the fold.
- [ ] El peso total de la página home es < 2 MB en la primera carga (verificar con WebPageTest o Lighthouse).
- [ ] Los recursos críticos (CSS, fuentes) tienen hints de precarga (`<link rel="preload">`).
- [ ] El sitio muestra contenido útil antes de los 3 segundos en conexión 3G simulada.

---

## Bloque 4 — Accesibilidad y motion

### Contraste y color

- [ ] Texto cuerpo (blanco/gris claro) sobre fondo `--gano-dark` (#0F1923): contraste ≥ 4.5:1.
- [ ] Texto de encabezado en `--gano-gold` (#D4AF37) sobre `--gano-dark`: contraste ≥ 4.5:1 (verificar con [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)).
- [ ] `--gano-orange` (#FF6B35) sobre fondos oscuros en botones CTA: contraste ≥ 3:1 para texto grande o ≥ 4.5:1 para texto normal.
- [ ] Los estados de error en formularios no dependen únicamente del color rojo (agregar ícono o texto).

### Focus y navegación por teclado

- [ ] Todos los elementos interactivos (links, botones, inputs) tienen estilo `:focus-visible` claramente visible.
- [ ] El orden de tabulación es lógico y sigue el flujo visual de izquierda a derecha, arriba a abajo.
- [ ] Los modales y diálogos atrapan el foco (focus trap) mientras están abiertos.
- [ ] Después de cerrar un modal, el foco regresa al elemento que lo abrió.

### `prefers-reduced-motion`

> Las animaciones GSAP y ScrollTrigger deben respetar la preferencia del usuario de reducir movimiento.  
> Ver: `wp-content/themes/gano-child/js/gano-sota-fx.js` y `scroll-reveal.js`.

- [ ] El CSS aplica `@media (prefers-reduced-motion: reduce)` de forma **dirigida** a animaciones decorativas (no al 100% de selectores para no cancelar spinners de carga ni feedback esencial):
  ```css
  /* Reducir solo animaciones decorativas; NO afectar spinners ni indicadores de progreso */
  @media (prefers-reduced-motion: reduce) {
    .gsap-anim,
    .scroll-reveal,
    .hero-parallax,
    .card-hover-fx {
      animation: none !important;
      transition: none !important;
    }
    html {
      scroll-behavior: auto !important;
    }
  }
  ```
- [ ] Los scripts GSAP comprueban `prefers-reduced-motion` y muestran el elemento en su estado final inmediatamente si se prefiere sin movimiento:
  ```js
  const motionOK = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (motionOK) {
    gsap.from('.hero-title', { opacity: 0, y: 40, duration: 0.6 });
  } else {
    // Asegurar estado final visible sin animación
    gsap.set('.hero-title', { opacity: 1, y: 0 });
  }
  ```
- [ ] El ScrollTrigger de `scroll-reveal.js` muestra el contenido inmediatamente si `prefers-reduced-motion: reduce` está activo (no espera la animación para revelar).
- [ ] Los videos de loop en hero tienen `prefers-reduced-motion` como condición para pausarlos:
  ```js
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    heroVideo.pause();
  }
  ```
- [ ] Las micro-animaciones de hover (botones, tarjetas) tienen duración < 300 ms y se desactivan con `prefers-reduced-motion: reduce`.

### Semántica y roles ARIA

- [ ] La estructura de encabezados es jerárquica: un solo `<h1>` por página, seguido de `<h2>`, `<h3>` sin saltos.
- [ ] Las imágenes informativas tienen `alt` descriptivo; las decorativas tienen `alt=""`.
- [ ] Los iconos SVG decorativos tienen `aria-hidden="true"`.
- [ ] El logo del header tiene `alt` con el nombre de la empresa.
- [ ] Los botones de solo icono tienen `aria-label` descriptivo.

---

## Bloque 5 — Confianza y conversión (específico Gano Digital)

- [ ] Los logos de métodos de pago (Wompi: PSE, Nequi, Daviplata, Visa/MC) aparecen solo si están activos en el flujo de compra real.
- [ ] No hay claims no verificables: evitar "el más rápido", "100% uptime garantizado" sin métrica fuente.
- [ ] Las cifras de rendimiento incluyen formulación prudente: "hasta X ms", "objetivo X%".
- [ ] El enlace a política de privacidad y términos está visible en pie de página y en formularios de captación de leads.
- [ ] El NIT de la empresa aparece en el pie de página o en la sección legal (reemplazar `[NIT]` cuando esté disponible).
- [ ] El teléfono de contacto usa `tel:` link en móvil para marcación directa.
- [ ] Los testimonios y casos de éxito (si existen) incluyen nombre y empresa verificables, no genéricos.
- [ ] El modal/banner de cookies cumple con la normativa SIC Colombia: permite aceptar/rechazar sin pre-selección de cookies opcionales.

---

## Referencias

| Recurso | URL / Ruta |
|---------|-----------|
| Nielsen Norman Group — 10 heuristics | https://www.nngroup.com/articles/ten-usability-heuristics/ |
| Web Vitals (Google) | https://web.dev/vitals/ |
| WCAG 2.1 AA | https://www.w3.org/WAI/WCAG21/quickref/ |
| WebAIM Contrast Checker | https://webaim.org/resources/contrastchecker/ |
| MDN prefers-reduced-motion | https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion |
| Brief maestro oleada 3 | `memory/research/gano-wave3-brand-ux-master-brief.md` |
| Clases utilitarias Elementor | `memory/content/elementor-home-classes.md` |
| GSAP animations | `wp-content/themes/gano-child/js/gano-sota-fx.js` |
| Scroll reveal | `wp-content/themes/gano-child/js/scroll-reveal.js` |
| MU plugin seguridad (CSP) | `wp-content/mu-plugins/gano-security.php` |

---

_Checklist generado para el proyecto Gano Digital — Oleada 3 / Abril 2026._
