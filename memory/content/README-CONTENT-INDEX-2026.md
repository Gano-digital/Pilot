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
| [`sota-audit-mockups-2026-04.md`](sota-audit-mockups-2026-04.md) | Auditoría de mockups SOTA: qué se adopta, qué se descarta y por qué (HTML estático → stack WordPress). | Iniciar cualquier implementación SOTA en Elementor o `shop-premium.php`. |

---

## 2 · Homepage (copy y clases)

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md) | Bloques de texto definitivos (Hero, Pilares, CTAs) listos para pegar en Elementor. | Sustituir Lorem o placeholders en la homepage en wp-admin. |
| [`art-direction-hero-2026.md`](art-direction-hero-2026.md) | Dirección artística del Hero: concepto creativo, moodboard estratégico y especificación visual (arquetipo Mago + Guardián). | Diseñar o ajustar la sección Hero en Elementor o en assets visuales. |
| [`homepage-2026-elementor-import-guide.md`](homepage-2026-elementor-import-guide.md) | Guía paso a paso para construir la homepage en Elementor: procedimiento, copy, clases y tokens ya listos en el tema. | Implementar o reconstruir la homepage en Elementor (staging primero). |
| [`elementor-home-classes.md`](elementor-home-classes.md) | Tabla de clases CSS por sección (`gano-el-stack`, `gano-el-layer-*`) con auditoría de uso. | Aplicar o auditar clases CSS del child theme en la homepage. |
| [`sota-visual-guide-v1.md`](sota-visual-guide-v1.md) | Guía visual SOTA v1: tokens base, componentes canónicos (`gano-sota-*`) y capas para mantener coherencia Theme + Elementor. | Extender el sistema visual SOTA o aplicar componentes en nuevas páginas. |
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
| [`seo-canonical-og-analysis-2026.md`](seo-canonical-og-analysis-2026.md) | Análisis de solapamientos canonical/OG entre Rank Math y `gano-seo.php`: diagnóstico y correcciones aplicadas. | Tocar `gano-seo.php`, configurar Rank Math, o publicar páginas SEO. |
| [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | *(ver §1)* Incluye las 20 páginas de pilares SEO (`/pilares/…`) con sus slugs canónicos. | Crear o renombrar páginas de pilares en wp-admin. |

---

## 6 · Operación y referencia cruzada

| Archivo | Propósito en una línea | Léelo antes de… |
|---------|------------------------|-----------------|
| [`digital-files-and-content-setup.md`](digital-files-and-content-setup.md) | Mapa de carpetas `memory/`, principios repo vs producción, roles de agentes y estado acordado abril 2026. | Comenzar cualquier tarea nueva o incorporar un agente al proyecto. |
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
| [`content-master-plan-2026.md`](content-master-plan-2026.md) | Plan maestro de narrativa, capas y prioridad de páginas (P0–P3). | Reordenar el sitio o abrir nuevas landings. |
| [`products-services-pages-matrix-2026.md`](products-services-pages-matrix-2026.md) | Matriz vitrina ↔ slugs ↔ `GANO_PFID_*` ↔ notas RCC. | Tocar `shop-premium.php` o CTAs de producto. |
| [`gap-ia-vs-live-inventory-2026.md`](gap-ia-vs-live-inventory-2026.md) | Brecha entre IA propuesta e inventario real (marcar *desconocido* donde aplique). | Auditar páginas en wp-admin y alinear slugs. |
| [`homepage-section-order-spec-2026.md`](homepage-section-order-spec-2026.md) | Orden ejecutable de bloques homepage (AIDA + CTA a ecosistemas). | Reordenar secciones en Elementor. |
| [`homepage-sota-blueprint-2026-04-17.md`](homepage-sota-blueprint-2026-04-17.md) | Blueprint UX de la homepage SOTA: bloques aprobados, rutas de conversión directa y consultiva. | Implementar o validar la arquitectura final de bloques en Elementor. |
| [`pilares-seo-narrative-clusters-2026.md`](pilares-seo-narrative-clusters-2026.md) | Clusters de pilares SEO y orden de publicación sugerido. | Publicar o priorizar artículos `/pilares/…`. |
| [`navigation-primary-spec-2026.md`](navigation-primary-spec-2026.md) | Especificación del menú `primary` (orden, etiquetas ES-CO, destinos). | Editar menú en Theme Builder / wp-admin. |
| [`trust-pages-bundle-2026.md`](trust-pages-bundle-2026.md) | Coherencia Nosotros + Contacto + Legal (enlaces cruzados, disclaimer Reseller). | Actualizar páginas de confianza o legales. |
| [`sota-hub-activation-guide.md`](sota-hub-activation-guide.md) | Guía para activar `gano-content-importer` en staging y verificar borradores de las 20 páginas SOTA. | Activar el plugin de importación de contenido o verificar páginas SOTA en wp-admin. |
| [`sota-convergence-code-pack-2026-04.md`](sota-convergence-code-pack-2026-04.md) | Pack de clases CSS nuevas (`gano-km-*`) que adapta prototipos obsidian-prism al stack WordPress + Elementor. | Aplicar el sistema visual SOTA convergente en nuevas secciones o páginas. |
| [`sota-multimedia-production-kit-2026-04.md`](sota-multimedia-production-kit-2026-04.md) | Inventario de prototipos multimedia (obsidian-prism, cybernetic-hero, etc.) y pipeline de referencia visual. | Seleccionar o adaptar prototipos visuales para assets de producción. |

Ver seguimiento operativo en [`TASKS.md`](../../TASKS.md) (homepage, despliegue).

---

*Última actualización: Abril 2026 (oleadas 3 + 4 indexadas) · Gano Digital*
