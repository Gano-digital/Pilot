# Auditoria SOTA de Mockups — Abril 2026

Fuente de entrada:

- `mockup_sota_ignorar.html`
- `shop-premium-mockup.html`
- `hero-prototypes.html`
- `mockup_completo_ignorar.html`
- `diagnostico_digital_gano.html`

## Resultado ejecutivo

Los conceptos visuales son valiosos como direccion de arte, pero no estaban listos para produccion porque mezclaban:

- HTML estatico con Tailwind CDN y estilos inline.
- contenido demo (ingles, metrics placeholders, CTAs sin flujo comercial real).
- dependencias no controladas (assets externos y efectos pesados).
- estructura no integrada al stack WordPress + Elementor + Reseller RCC.

## Matriz de adopcion

| Patron visual/contenido | Estado | Motivo |
|---|---|---|
| Hero cinematico (gradientes + glass + CTA dual) | Adoptar | Encaja con narrativa SOTA y conversion. |
| Hero con tarjetas 3D parallax | Adaptar | Alto impacto visual; requiere version ligera para mobile/performance. |
| Scrollytelling con video sticky | Adaptar | Util solo en secciones premium; limitar peso y fallback. |
| Barra de estado tecnico (uptime/latencia) | Adaptar | Debe usar datos verificables o neutral wording. |
| Grid de pilares/beneficios con iconos | Adoptar | Excelente para home, nosotros y landings. |
| Catalogo tipo glass cards por categoria | Adoptar | Encaja directo con `shop-premium.php` y flujo RCC/PFID. |
| Diagnostico digital tipo quiz | Adoptar | Alto potencial de lead-gen; migrar a template WP mantenible. |
| Texto glitch agresivo y efectos extremos | Descartar | Riesgo de legibilidad/marca y costo de render. |
| Particles/WebGL full-screen continuo | Descartar | Penaliza mobile y Core Web Vitals sin valor proporcional. |
| Metricas absolutas no auditables | Descartar | Riesgo de confianza/legal; usar claims verificables. |

## Mapeo a paginas reales

| Seccion mockup | Destino real |
|---|---|
| Hero principal + CTA | Home (Elementor + clases `gano-el-*`) |
| Catalogo por categorias | `templates/shop-premium.php` y `templates/page-ecosistemas.php` |
| Storytelling tecnico | `templates/page-sota-hub.php` y `templates/sota-single-template.php` |
| Seccion “por que elegirnos” | `templates/page-nosotros.php` + home |
| Quiz/diagnostico | nuevo template `templates/page-diagnostico-digital.php` |
| CTA comercial final | Home, Ecosistemas, Nosotros, SEO landing |

## Criterios de integracion

1. Sin Tailwind CDN en runtime productivo del tema.
2. Sin texto placeholder ni datos no verificables.
3. Compatibilidad con GoDaddy Reseller (PFID/PLID) en CTAs.
4. Efectos con `prefers-reduced-motion` y degradacion elegante.
5. Reutilizacion de tokens y componentes en `style.css`.

## Recomendacion de rollout

- Onda 1: Design system + Home + Ecosistemas/Shop + Contacto.
- Onda 2: Nosotros + SOTA Hub + SEO landing.
- Onda 3: Diagnostico digital + ajustes de conversion + QA final.
