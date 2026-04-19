# Plan Maestro de Contenidos — Gano Digital 2026

**Versión:** 1.1 · Abril 2026  
**Autor:** Agente Copilot (revisión pendiente Diego)  
**Fuentes:** `site-ia-wave3-proposed.md` · `homepage-story-arc-wave3.md` · `TASKS.md` · `microcopy-wave3-kit.md` · `ecosystems-copy-matrix-wave3.md` · `trust-and-reseller-copy-wave3.md` · `gap-ia-vs-live-inventory-2026.md` · `products-services-pages-matrix-2026.md`

> **Changelog v1.1:** Añadido §0 Orden de lectura; ampliada tabla maestra con páginas de /dominios, /seguridad y /colaboracion; incorporado bloqueo PLID/PENDING_RCC al camino crítico; añadida nota sobre slug drift de las 20 páginas SOTA; actualizadas referencias cruzadas.

---

## 0. Orden de lectura recomendado

> Para agentes y colaboradores que se incorporan al proyecto. Leer en este orden antes de tocar copy, slugs o comercio.

| Paso | Documento | Por qué leerlo primero |
|------|-----------|------------------------|
| 1 | [`memory/research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md) | Brief maestro: voz, sistema visual, IA base, criterios de aceptación — fuente de verdad raíz |
| 2 | **Este documento** (`content-master-plan-2026.md`) | Resumen ejecutivo + capas + estado + tabla maestra |
| 3 | [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | Árbol de rutas completo, mapa de clics y tecnología por página |
| 4 | [`homepage-story-arc-wave3.md`](homepage-story-arc-wave3.md) | Orden canónico de secciones de la home; checklist de publicación |
| 5 | [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md) | Copy final listo para pegar en Elementor |
| 6 | [`products-services-pages-matrix-2026.md`](products-services-pages-matrix-2026.md) | Vitrina ↔ slugs ↔ `GANO_PFID_*` ↔ notas RCC |
| 7 | [`gap-ia-vs-live-inventory-2026.md`](gap-ia-vs-live-inventory-2026.md) | Brecha entre IA propuesta e inventario real; decisiones de slug pendientes |
| 8 | [`TASKS.md`](../../TASKS.md) | Estado del proyecto por fases; Fase 4 Reseller con PFIDs |

**Principio de no duplicación:** si ya existe un documento detallado para un tema, este plan apunta a él con un enlace — no lo reitera.

---

## Resumen ejecutivo

Gano Digital es un operador de ecosistemas WordPress para Colombia y LATAM, aprovisionado sobre el programa de reseller de GoDaddy. Su propuesta de valor se articula en torno a soberanía digital, rendimiento NVMe, seguridad hardened y soporte en español con facturación en COP.

El sitio web debe recorrer un arco narrativo en **seis pasos** (AIDA + prueba social) que lleva al visitante desde la atención inicial hasta la decisión de compra en **máximo 3 clics** desde la homepage. La estructura de contenido se organiza en cuatro capas:

1. **Home** — presentación del ecosistema, captura de atención, primeros CTAs.
2. **Ecosistemas** — catálogo de conversión con tres planes y enlace directo al carrito GoDaddy Reseller.
3. **Pilares SEO** — 20 artículos técnicos de posicionamiento orgánico, organizados en cinco categorías.
4. **Confianza / Legal / Contacto** — páginas que cierran la duda de compra y cumplen con la normativa SIC Colombia.

El trabajo pendiente más crítico es la **publicación en wp-admin** de las páginas de ecosistemas individuales, el formulario de contacto y las páginas legales. El código PHP (plantillas, plugins de fase, MU-plugins) ya está en el repositorio y listo para activar.

---

## 1. Capas narrativas del sitio

### Capa 1 — Home `/`

**Rol:** Primer contacto. Captura atención, comunica quiénes somos y lleva al catálogo.  
**Arco AIDA:**

| Sección | Etapa | Objetivo |
|---------|-------|----------|
| Hero | Atención | Resolver "¿esto es para mí?" en < 3 s |
| Cuatro pilares | Interés | Traducir promesa técnica a beneficios tangibles |
| Socio tecnológico | Interés → Confianza | Credibilidad sin hipérboles; honestidad Reseller |
| Métricas / franja | Prueba social | Anclar promesas con cifras verificables |
| Ecosistemas / Planes | Deseo | Presentar los 3 planes con precio COP; up-sell natural |
| CTA final | Acción | Cierre con un solo botón dominante |

**Salidas directas (1 clic):** `/ecosistemas`, `/contacto`, plan individual.

> Ver orden canónico completo → [`homepage-story-arc-wave3.md`](homepage-story-arc-wave3.md)

---

### Capa 2 — Ecosistemas `/ecosistemas`

**Rol:** Catálogo de conversión. El visitante compara planes y activa la compra.  
**Estructura:**

- Tabla comparativa de los tres planes (Núcleo Prime · Fortaleza Delta · Bastión SOTA)
- Cada plan: nombre, precio en COP, specs clave, CTA primario
- Enlace a `/contacto` para duda comercial
- Pilares SEO relacionados por plan (ver §5 de `site-ia-wave3-proposed.md`)

**Honestidad Reseller:** no simular datacenter propio; mencionar programa de reseller cuando aplique.

> Ver matriz comercial completa → [`ecosystems-copy-matrix-wave3.md`](ecosystems-copy-matrix-wave3.md)

**Precios de referencia (COP/mes):**

| Plan | Precio | ID marcador (pendiente RCC) |
|------|--------|----------------------------|
| Núcleo Prime | $ 196.000 | `host-1` |
| Fortaleza Delta | $ 450.000 | `host-2` |
| Bastión SOTA | $ 890.000 | `host-3` |
| Ultimate WP *(complementario)* | $ 1.200.000 | `host-4` |

> ⚠️ Reemplazar `host-1`…`host-4` por IDs reales del RCC antes de activar en producción (TASKS.md Fase 4).

---

### Capa 3 — Pilares SEO `/pilares`

**Rol:** Posicionamiento orgánico + educación técnica + enlace interno a planes.  
**Estructura:** 5 categorías × artículos SOTA individuales.

| Categoría | Pilares | Plan recomendado |
|-----------|---------|-----------------|
| Infraestructura | NVMe, Hosting Compartido, Edge, Backups, Escalamiento, Alta Disponibilidad | Bastión SOTA (todos) · Fortaleza Delta (HA, backups) |
| Seguridad | Zero-Trust, DDoS, Cifrado Post-Cuántico | Fortaleza Delta + Bastión SOTA |
| Rendimiento | Headless, Green Hosting, CI/CD, Skeleton Screens, Micro-animaciones, HTTP/3 | Bastión SOTA (full) · Fortaleza Delta (CI/CD, HTTP/3) |
| Inteligencia Artificial | Gestión Predictiva, Self-Healing, Agente IA | Bastión SOTA |
| Estrategia | Soberanía Digital, Analytics Server-Side | Todos los planes |

Cada pilar termina con un bloque CTA que enlaza al plan recomendado (slugs: `/ecosistemas/bastion-sota`, `/ecosistemas/fortaleza-delta`, `/ecosistemas/nucleo-prime`, `/ecosistemas`).

---

### Capa 4 — Confianza, Legal y Contacto

**Rol:** Cerrar la duda de compra y cumplir normativa SIC Colombia + GDPR.

| Página | Rol | Estado |
|--------|-----|--------|
| `/nosotros` | Manifiesto de marca, transparencia Reseller, equipo | Pendiente copy real |
| `/contacto` | Formulario + WhatsApp + chat IA | Pendiente crear en Elementor |
| `/legal/terminos-y-condiciones` | Contrato de servicio | Pendiente redacción legal |
| `/legal/politica-de-privacidad` | Ley 1581/2012 + GDPR | Pendiente redacción legal |
| `/legal/acuerdo-de-nivel-de-servicio` | SLA objetivo 99,9 % | Pendiente datos reales |

> Placeholders en uso: `[NIT]`, `[teléfono]`, `[dirección]`, `[SLA real]` — completar con Diego o asesor legal antes de publicar.

---

## 2. Reglas de voz y tono

| Contexto | Pronombre | Tono |
|----------|-----------|------|
| Marketing / Hero / Pilares | **tú** | Cercano, técnico, visionario |
| Checkout / resumen de pedido | **usted** | Formal, seguridad jurídica |
| Formularios legales / consentimiento | **usted** | Formal, normativa SIC |
| Mensajes de error / feedback UI | **tú** | Directo, sin alarma |
| Correos transaccionales | **usted** | Documento de transacción |
| Soporte / chat | **tú** (por defecto) | Conversacional, ajustable |

**Regla de oro:** no mezclar tú/usted dentro de la misma sección.

**Tono prohibido:** lenguaje de "barato", "hosting compartido" con connotación negativa genérica, comparativas de bajo costo, promesas de datacenter propio.

**Tono requerido:** Manifiesto Técnico — autoritario, visionario, sofisticado, soberano.

> Ver kit completo de microcopy y CTAs → [`microcopy-wave3-kit.md`](microcopy-wave3-kit.md)

---

## 3. Estado del repositorio: hecho vs. pendiente

### ✅ Hecho en repo (código listo para activar)

| Artefacto | Ruta | Notas |
|-----------|------|-------|
| Plantilla shop / ecosistemas | `wp-content/themes/gano-child/templates/shop-premium.php` | Tabla de planes con precios COP; IDs marcadores pendientes RCC |
| Landing SEO Colombia | `wp-content/themes/gano-child/templates/page-seo-landing.php` + `seo-pages/hosting-wordpress-colombia.php` | Lista para asignar en wp-admin |
| Plantilla SOTA individual | `wp-content/themes/gano-child/templates/sota-single-template.php` | Renderiza las 20 páginas SOTA con CTA a planes |
| Plugin importador de páginas SOTA | `wp-content/plugins/gano-content-importer/` | Crea 20 páginas como draft al activarse |
| Schema JSON-LD (Org + LocalBiz + Product + FAQ) | `wp-content/mu-plugins/gano-seo.php` | Activo en producción |
| CSP + headers de seguridad | `wp-content/mu-plugins/gano-security.php` | Activo en producción |
| Chat IA | `wp-content/themes/gano-child/js/gano-chat.js` | Integrado |
| Animaciones GSAP / scroll reveals | `wp-content/themes/gano-child/js/gano-sota-fx.js` + `scroll-reveal.js` | Integrados |
| Quiz Soberanía Digital | `wp-content/themes/gano-child/functions.php` | Encolado |
| Copy de homepage | `memory/content/homepage-copy-2026-04.md` | Listo para pegar en Elementor |
| Arquitectura de información Wave 3 | `memory/content/site-ia-wave3-proposed.md` | Árbol de rutas + mapa de clics |
| Kit de microcopy | `memory/content/microcopy-wave3-kit.md` | CTAs, errores, vacíos, consentimientos |
| Matriz comercial de ecosistemas | `memory/content/ecosystems-copy-matrix-wave3.md` | Objeciones + coherencia shop |
| Copy de confianza/reseller | `memory/content/trust-and-reseller-copy-wave3.md` | Footer, nosotros, disclaimer |

### ⏳ Pendiente en Elementor / wp-admin (no modificable desde GitHub)

| Página / elemento | Acción requerida | Prioridad |
|-------------------|-----------------|-----------|
| Home — copy final | Aplicar `homepage-copy-2026-04.md` en el editor Elementor; reemplazar Lorem | **P0** |
| Menú principal | Asignar a ubicación `primary` en Apariencia → Menús | **P0** |
| `/ecosistemas` | Asignar plantilla `shop-premium.php` a la página en wp-admin | **P0** |
| `/hosting-wordpress-colombia` | Asignar plantilla `page-seo-landing.php` | **P1** |
| 20 páginas SOTA | Activar plugin `gano-content-importer` → revisar → publicar individualmente | **P1** |
| `/ecosistemas/nucleo-prime` | Crear página Elementor con copy del plan | **P1** |
| `/ecosistemas/fortaleza-delta` | Crear página Elementor con copy del plan | **P1** |
| `/ecosistemas/bastion-sota` | Crear página Elementor con copy del plan | **P1** |
| `/pilares` (índice) | Crear página Elementor con grid de 5 categorías | **P2** |
| `/contacto` | Crear página Elementor con CF7 + WhatsApp + datos `[pendientes]` | **P1** |
| `/nosotros` | Crear página Elementor — solo cuando haya copy real (sin placeholders visibles) | **P2** |
| `/legal/terminos-y-condiciones` | Redactar (abogado/Diego) + publicar | **P2** |
| `/legal/politica-de-privacidad` | Redactar (abogado/Diego) + publicar | **P2** |
| `/legal/acuerdo-de-nivel-de-servicio` | Redactar con SLA real + publicar | **P2** |
| IDs de producto RCC | Reemplazar `host-1`…`host-4` en `shop-premium.php` con IDs reales del Reseller Control Center | **P0** (bloquea checkout) |
| Datos de contacto legales | Completar `[NIT]`, `[teléfono]`, `[dirección]` en footer, formularios y legales | **P1** |

---

## 4. Tabla maestra de páginas

> Prioridades: **P0** = bloquea negocio / lanzamiento · **P1** = crítico · **P2** = importante · **P3** = deseable

| Página | Capa | Rol narrativo | Prioridad | Depende de |
|--------|------|---------------|-----------|------------|
| `/` (Home) | 1 — Home | Primer contacto; AIDA completo; hub de entrada a todo el sitio | **P0** | Copy `homepage-copy-2026-04.md` aplicado en Elementor; menú asignado |
| `/ecosistemas` | 2 — Ecosistemas | Catálogo de conversión; tabla comparativa de planes; CTA a carrito Reseller | **P0** | Plantilla `shop-premium.php` asignada; IDs RCC confirmados; PLID válido |
| `/ecosistemas/nucleo-prime` | 2 — Ecosistemas | Detalle del plan de entrada; argumento "primera infraestructura seria" | **P1** | Página padre `/ecosistemas`; copy de plan confirmado |
| `/ecosistemas/fortaleza-delta` | 2 — Ecosistemas | Detalle del plan medio; argumento "seguridad para negocios en crecimiento" | **P1** | Página padre `/ecosistemas`; copy de plan confirmado |
| `/ecosistemas/bastion-sota` | 2 — Ecosistemas | Detalle del plan premium; argumento "ecosistema completo sin compromisos" | **P1** | Página padre `/ecosistemas`; copy de plan confirmado |
| `/ecosistemas/ultimate-wp` | 2 — Ecosistemas | Cuarto plan (nombre "Ultimate WP" pendiente renombrar); up-sell máximo | **P2** | Decisión de Diego sobre nombre de ecosistema; IDs RCC |
| `/dominios/` | 2 — Ecosistemas | Búsqueda y registro de dominios; onboarding de nuevos clientes | **P2** | Shortcode `[rstore-domain-search]` configurado en wp-admin |
| `/seguridad/ssl-deluxe` | 2 — Ecosistemas | Upsell SSL; complementa cualquier plan de hosting | **P3** | `GANO_PFID_SSL_DELUXE` confirmado en RCC; página creada |
| `/seguridad/security-ultimate` | 2 — Ecosistemas | Upsell seguridad avanzada; diferenciador vs. competencia | **P3** | `GANO_PFID_SECURITY_ULTIMATE` confirmado en RCC |
| `/colaboracion/m365-premium` | 2 — Ecosistemas | Cross-sell Microsoft 365; valor añadido para empresas | **P3** | `GANO_PFID_M365_PREMIUM` confirmado en RCC |
| `/colaboracion/online-storage-1tb` | 2 — Ecosistemas | Cross-sell almacenamiento; complemento a planes | **P3** | `GANO_PFID_ONLINE_STORAGE` confirmado en RCC |
| `/hosting-wordpress-colombia` | 3 — Pilares SEO | Landing SEO primaria; keyword principal del negocio | **P1** | Plantilla `page-seo-landing.php` asignada en wp-admin |
| `/pilares` (índice) | 3 — Pilares SEO | Hub SEO de las 5 categorías; breadcrumb de entrada a los artículos | **P2** | Las 20 páginas SOTA publicadas |
| 20 páginas SOTA (infraestructura) | 3 — Pilares SEO | Posicionamiento orgánico por keyword técnica; enlace interno a planes | **P1** | Plugin `gano-content-importer` activado; plantilla `sota-single-template.php`; slugs canónicos decididos (ver `gap-ia-vs-live-inventory-2026.md`) |
| `/contacto` | 4 — Confianza | Reducir fricción pre-venta; canal directo con el equipo | **P1** | Datos de contacto reales `[teléfono]`, `[email]`; formulario CF7 configurado |
| `/nosotros` | 4 — Confianza | Credibilidad; transparencia del modelo Reseller; manifiesto de marca | **P2** | Copy real disponible (sin placeholders visibles en producción) |
| `/legal/terminos-y-condiciones` | 4 — Legal | Cumplimiento contractual; requerido antes del checkout | **P2** | Redacción por abogado/Diego; `[NIT]` confirmado |
| `/legal/politica-de-privacidad` | 4 — Legal | Ley 1581/2012 + GDPR; consentimiento en formularios | **P2** | Redacción por abogado/Diego; `[NIT]` confirmado |
| `/legal/acuerdo-de-nivel-de-servicio` | 4 — Legal | Transparencia de SLA; confianza pre-compra en planes medios/altos | **P3** | SLA real acordado con GoDaddy Reseller |

---

## 5. Árbol de dependencias críticas (camino crítico P0)

```
[Datos reales de Diego]
  └── NIT confirmado → textos legales del footer y formularios
  └── IDs RCC (8 PFIDs) → functions.php / wp-admin → Ajustes → Gano Reseller
  └── PLID (Private Label ID) → wp-admin → Ajustes → Reseller Store → Private Label ID
       (sin PLID válido, gano_rstore_cart_url() retorna '#' aunque los PFIDs sean correctos)

[wp-admin — tareas obligatorias antes de lanzar]
  ├── Asignar menú a ubicación "primary"
  ├── Aplicar copy homepage en Elementor (homepage-copy-2026-04.md)
  ├── Asignar plantilla shop-premium.php a /ecosistemas
  └── Asignar plantilla page-seo-landing.php a /hosting-wordpress-colombia

[GitHub — ya hecho]
  ├── Plantillas PHP en gano-child/templates/
  ├── MU-plugins (seguridad + SEO)
  ├── Plugin gano-content-importer (20 páginas SOTA como draft)
  ├── Plugin gano-reseller-enhancements (panel PFID en wp-admin)
  └── Chat IA, animaciones GSAP, Quiz
```

> ⚠️ **Slug drift en 20 páginas SOTA:** los slugs generados por `gano-content-importer` difieren de los propuestos en `site-ia-wave3-proposed.md`. Antes de publicar, Diego debe decidir cuál slug es canónico. Ver análisis completo y opciones A/B/C → [`gap-ia-vs-live-inventory-2026.md`](gap-ia-vs-live-inventory-2026.md).

> ⚠️ **Nombre "Ultimate WP":** el cuarto plan de hosting no sigue la nomenclatura de ecosistema (Núcleo/Fortaleza/Bastión). Decidir si adoptar un nombre propio (ej. `Comando Sigma`) o mantener "Ultimate WP". Ver §4.3 → [`products-services-pages-matrix-2026.md`](products-services-pages-matrix-2026.md).

---

## 6. Referencias cruzadas

| Documento | Contenido |
|-----------|-----------|
| [`site-ia-wave3-proposed.md`](site-ia-wave3-proposed.md) | Árbol de rutas completo, mapa de clics, tecnología Elementor vs PHP |
| [`homepage-story-arc-wave3.md`](homepage-story-arc-wave3.md) | Orden canónico de secciones de la home; marco AIDA; checklist de publicación |
| [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md) | Copy final de cada sección de la home |
| [`microcopy-wave3-kit.md`](microcopy-wave3-kit.md) | CTAs primarios/secundarios, errores, estados vacíos, consentimientos |
| [`ecosystems-copy-matrix-wave3.md`](ecosystems-copy-matrix-wave3.md) | Promesas, objeciones, coherencia con `shop-premium.php` |
| [`trust-and-reseller-copy-wave3.md`](trust-and-reseller-copy-wave3.md) | Footer, nosotros, disclaimer reseller |
| [`products-services-pages-matrix-2026.md`](products-services-pages-matrix-2026.md) | Matriz vitrina ↔ slugs ↔ `GANO_PFID_*` ↔ notas RCC; incluye dominios, seguridad, colaboración |
| [`gap-ia-vs-live-inventory-2026.md`](gap-ia-vs-live-inventory-2026.md) | Brecha entre IA propuesta e inventario real; slug drift de las 20 páginas SOTA; decisión pendiente Diego |
| [`../../TASKS.md`](../../TASKS.md) | Estado del proyecto por fases; Fase 4 Reseller — PFIDs, PLID, gano-reseller-enhancements |

---

_Fin del documento · Actualizar cuando cambie el estado de páginas en wp-admin o se confirmen datos legales (NIT, teléfono, IDs RCC, PLID)._
