# Índice maestro de contenidos — `memory/content/` · 2026

**Audiencia:** humanos y agentes de IA (Copilot, Claude, Cursor).  
**Propósito:** mapa de lectura con dependencias para no reescribir lo que ya existe.  
**Mantenimiento:** actualizar cuando se añada o archive un documento en esta carpeta.

> **Regla:** antes de tocar copy, diseño o comercio, consulta primero el **brief maestro**
> [`memory/research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md)
> y el estado de tareas en [`TASKS.md`](../../TASKS.md).

---

## 1 · Estrategia y arquitectura

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | Árbol de rutas completo del sitio (Home → Pilares → Legal), regla de 3 clics y mapa de conversión. | Cualquier cambio de menú, slugs o estructura de páginas. |
| [`homepage-story-arc-wave3.md`](homepage-story-arc-wave3.md) | Orden canónico de secciones en Home con justificación AIDA y mapeo a bloques Elementor existentes. | Reordenar secciones de la homepage o añadir nuevos bloques. |
| [`elementor-architecture-wave3.md`](elementor-architecture-wave3.md) | Guía de arquitectura Elementor: tema activo, jerarquía de templates, notas sobre handle legacy. | Tocar `functions.php`, crear templates Elementor o migrar el tema padre. |

---

## 2 · Homepage (copy y clases)

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md) | Bloques de texto definitivos (Hero, Pilares, CTAs) listos para pegar en Elementor. | Sustituir Lorem o placeholders en la homepage en wp-admin. |
| [`elementor-home-classes.md`](elementor-home-classes.md) | Tabla de clases CSS por sección (`gano-el-stack`, `gano-el-layer-*`) con auditoría de uso. | Aplicar o auditar clases CSS del child theme en la homepage. |
| [`microcopy-wave3-kit.md`](microcopy-wave3-kit.md) | Kit completo de microcopy (botones, tooltips, validaciones, mensajes de error) en es-CO. | Escribir texto de interfaz en cualquier página del sitio. |
| [`raster-asset-spec-wave3.md`](raster-asset-spec-wave3.md) | Especificación de imágenes raster (tamaños, formatos WebP/AVIF, convenciones de nombre, preload LCP). | Subir imágenes al Media Library o ajustar hints de preload en `gano-seo.php`. |
| [`social-preview-spec-wave3.md`](social-preview-spec-wave3.md) | Especificación de Open Graph y Twitter Card (dimensiones, textos, proceso de subida). | Configurar previsualizaciones OG en Rank Math o en el MU plugin `gano-seo.php`. |

---

## 3 · IA / Rutas y accesibilidad

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | *(ver §1)* Árbol de rutas y navegación. | *(ver §1)* |
| [`ux-a11y-wave3-notes.md`](ux-a11y-wave3-notes.md) | Notas de accesibilidad WCAG 2.1 AA: `focus-visible`, skip-link, cambios en `style.css`. | Tocar estilos de interacción o componentes de navegación por teclado. |
| [`visual-tokens-wave3.md`](visual-tokens-wave3.md) | Escala tipográfica, contraste, tokens de `display`, `line-height`, `letter-spacing` en `:root`. | Extender variables CSS del child theme o verificar ratios de contraste. |

---

## 4 · Comercio / Reseller

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`ecosystems-copy-matrix-wave3.md`](ecosystems-copy-matrix-wave3.md) | Matriz comercial de los 4 ecosistemas (Núcleo Prime, Fortaleza Delta, Bastión SOTA) con features, precio y diferenciadores. | Tocar `shop-premium.php`, `GANO_PFID_*` o cualquier CTA de compra. |
| [`trust-and-reseller-copy-wave3.md`](trust-and-reseller-copy-wave3.md) | Bloques de copy de confianza y transparencia honesta sobre el modelo Reseller (footer, legal, nosotros). | Escribir secciones de confianza, `/nosotros` o el pie de página. |
| [`footer-contact-audit-wave3.md`](footer-contact-audit-wave3.md) | Auditoría detallada del footer y página de contacto: datos faltantes, placeholders `[NIT]`, `[teléfono]`. | Completar los datos reales de contacto o actualizar el footer en Elementor. |
| [`content-audit-report.md`](content-audit-report.md) | Informe de auditoría de contenidos del footer y contacto generado por el agente. | Revisar qué copy ya fue aprobado y qué queda pendiente de aprobación de Diego. |

---

## 5 · SEO / Pilares

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`faq-schema-candidates-wave3.md`](faq-schema-candidates-wave3.md) | Lista de preguntas y respuestas candidatas para el bloque `FAQPage` de `gano-seo.php`. | Ampliar el schema JSON-LD de FAQ en el MU plugin o en Rank Math. |
| [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | *(ver §1)* Incluye las 20 páginas de pilares SEO (`/pilares/…`) con sus slugs canónicos. | Crear o renombrar páginas de pilares en wp-admin. |
| [`pilares-seo-narrative-clusters-2026.md`](pilares-seo-narrative-clusters-2026.md) | 5 clusters narrativos (Infra, Seguridad, Rendimiento, IA, Estrategia), orden de publicación P0→P4 y mapa de enlaces editoriales entre pilares. | Priorizar qué pilares publicar primero o definir enlaces internos entre artículos. |

---

## 6 · Operación y referencia cruzada

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`../research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md) | **Brief maestro oleada 3:** voz de marca, sistema visual, arquitectura y criterios de aceptación. | *Cualquier entrega de oleada 3 o 4.* |
| [`../research/competitive-framework-colombia-hosting.md`](../research/competitive-framework-colombia-hosting.md) | Marco competitivo del mercado de hosting en Colombia. | Ajustar precios, diferenciadores o copy de conversión. |
| [`../research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md) | Investigación sobre portales de desarrolladores Mercado Libre y GoDaddy (OAuth, límites, Reseller). | Evaluar integraciones API o delegar la cola `tasks-api-integrations-research.json`. |
| [`../commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md) | Checklist para mapear Product Feature IDs (PFID) en GoDaddy Reseller Control Center. | Conectar CTAs del sitio al carrito de GoDaddy Reseller. |
| [`../ops/gano-seo-rankmath-gsc-checklist.md`](../ops/gano-seo-rankmath-gsc-checklist.md) | Pasos para configurar Rank Math y Google Search Console. | Publicar páginas SEO o validar cobertura en GSC. |

---

## Oleada 4 — Entregas en repo (2026-04)

Generados vía PRs Copilot desde [`.github/agent-queue/tasks-wave4-ia-content.json`](../../.github/agent-queue/tasks-wave4-ia-content.json). Integrar en wp-admin / Elementor según cada documento.

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`content-master-plan-2026.md`](content-master-plan-2026.md) | **Plan maestro v1.1:** narrativa, capas, orden de lectura, prioridad P0–P3 y tabla maestra de páginas (incluye /dominios, /seguridad, /colaboracion). | Reordenar el sitio o abrir nuevas landings. |
| [`products-services-pages-matrix-2026.md`](products-services-pages-matrix-2026.md) | Matriz vitrina ↔ slugs ↔ `GANO_PFID_*` ↔ notas RCC. | Tocar `shop-premium.php` o CTAs de producto. |
| [`gap-ia-vs-live-inventory-2026.md`](gap-ia-vs-live-inventory-2026.md) | Brecha entre IA propuesta e inventario real (marcar *desconocido* donde aplique). | Auditar páginas en wp-admin y alinear slugs. |
| [`homepage-section-order-spec-2026.md`](homepage-section-order-spec-2026.md) | Orden ejecutable de bloques homepage (AIDA + CTA a ecosistemas). | Reordenar secciones en Elementor. |
| [`pilares-seo-narrative-clusters-2026.md`](pilares-seo-narrative-clusters-2026.md) | Clusters de pilares SEO y orden de publicación sugerido. | Publicar o priorizar artículos `/pilares/…`. |
| [`navigation-primary-spec-2026.md`](navigation-primary-spec-2026.md) | Especificación del menú `primary` (orden, etiquetas ES-CO, destinos). | Editar menú en Theme Builder / wp-admin. |
| [`trust-pages-bundle-2026.md`](trust-pages-bundle-2026.md) | Coherencia Nosotros + Contacto + Legal (enlaces cruzados, disclaimer Reseller). | Actualizar páginas de confianza o legales. |

Ver seguimiento operativo en [`TASKS.md`](../../TASKS.md) (homepage, despliegue).

---

*Última actualización: Abril 2026 · Gano Digital*
