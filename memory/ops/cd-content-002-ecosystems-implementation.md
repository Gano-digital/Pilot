# Tarea cd-content-002 — Wave contenidos: ecosistemas (4 planes) con CTAs Reseller

**ID:** `cd-content-002`  
**Prioridad:** P1  
**Requiere humano después:** SÍ (Diego debe confirmar PFIDs en RCC)  
**Generado:** 2026-04-20  
**Estado:** Listo para implementación por Diego en wp-admin

---

## Objetivo

Crear 4 páginas en WordPress (templates PHP o Elementor) que presenten los planes de hosting Gano Digital con:
1. Copy cohesivo de la matriz de ecosistemas (wave 3)
2. CTAs que redirijan al checkout Reseller de GoDaddy
3. Placeholders {{PFID_PLACEHOLDER}} para PFIDs confirmados por Diego en RCC
4. Estructura responsive + accesibilidad WCAG AA

**Scope:** Páginas `/ecosistemas` (listado), `/ecosistemas/nucleo-prime`, `/ecosistemas/fortaleza-delta`, `/ecosistemas/bastion-sota`  
**Fuentes:** `memory/content/ecosystems-copy-matrix-wave3.md` + `memory/commerce/rcc-pfid-checklist.md`

---

## 📋 Las 4 Páginas

### Página 1 — ECOSISTEMAS (Listado / Catálogo)

**URL:** `/ecosistemas`  
**Template:** `shop-premium.php` (actual) O nueva plantilla `page-ecosistemas.php`

**Estructura:**
- [ ] H1: "Elige tu arquitectura de hosting WordPress"
- [ ] Párrafo introductorio (200 palabras máx): promesa central de Gano Digital sobre infraestructura seria
- [ ] Grid de 3 tarjetas (escritorio) → stack (móvil)
  - [ ] **Tarjeta 1: Núcleo Prime**
    - Título: "Núcleo Prime"
    - Subtítulo: "Infraestructura NVMe de entrada"
    - Precio: "desde $ 196.000/mes"
    - Descripción breve: (50 palabras del copy matriz)
    - Botón primario: "Elegir Núcleo Prime" → `/ecosistemas/nucleo-prime`
    - Enlace secundario: "Ver especificaciones" → anchor `#specs`
  - [ ] **Tarjeta 2: Fortaleza Delta**
    - Título: "Fortaleza Delta"
    - Subtítulo: "Hardening activo para negocios en crecimiento"
    - Precio: "desde $ 450.000/mes"
    - Descripción breve: (50 palabras)
    - Botón: "Activar Fortaleza Delta" → `/ecosistemas/fortaleza-delta`
  - [ ] **Tarjeta 3: Bastión SOTA**
    - Título: "Bastión SOTA"
    - Subtítulo: "Rendimiento crítico y SLA empresarial"
    - Precio: "desde $ 890.000/mes"
    - Descripción breve: (50 palabras)
    - Botón: "Solicitar Bastión SOTA" → `/ecosistemas/bastion-sota`

**Comparativa de planes (tabla o acordeón):**
- [ ] Tabla con filas: Almacenamiento NVMe, Dominios, Soporte, SLA, Hardening, CI/CD, IA
- [ ] Checkmarks (✓) indicando qué planes incluyen cada feature
- [ ] Clase CSS: `gano-comparison-table`

**CSS Classes:**
- [ ] Contenedor principal: `gano-el-ecosystems-hub`
- [ ] Grid tarjetas: `gano-ecosystems-grid`
- [ ] Tarjeta individual: `gano-ecosystem-card` + `gano-card--{plan-name}` (prime, delta, sota)
- [ ] Botones: `gano-btn gano-btn--primary` + `gano-btn--{color}` (dorado para SOTA)
- [ ] Tabla comparativa: `gano-comparison-table`

**Accesibilidad:**
- [ ] Botones con `:focus-visible` anillo dorado
- [ ] Headings correctos (H1 → H2 tarjetas)
- [ ] Tabla: `<caption>`, `scope="col"` en headers
- [ ] Texto alternativo en iconos (si aplica)

**SEO:**
- [ ] Meta title: "Hosting WordPress en Colombia | Planes Gano Digital"
- [ ] Meta description: "Elige entre Núcleo Prime, Fortaleza Delta o Bastión SOTA. Infraestructura NVMe, soporte en español."
- [ ] Schema JSON-LD: Product x3 (uno por plan) con precio, descripción, agregateRating (si disponible)

---

### Página 2 — NÚCLEO PRIME (Detalle)

**URL:** `/ecosistemas/nucleo-prime`  
**Slug:** `nucleo-prime`  
**Padre:** `/ecosistemas`

**Estructura (Hero + detalle + objeciones + CTA):**

#### Bloque 1 — Hero
- [ ] H1: "Núcleo Prime — Infraestructura NVMe de entrada"
- [ ] Subheadline: "Activación rápida. Soporte en español. Sin sorpresas."
- [ ] Párrafo 100-150 palabras (de matriz: "Infraestructura NVMe de entrada con soporte en español, activación rápida...")
- [ ] Imagen o shape (si existe)
- [ ] CTA primario: "Contratar Núcleo Prime" → `/reseller-store?pid={{PFID_NUCLEO_PRIME}}` (placeholder)

#### Bloque 2 — Qué incluye
- [ ] H2: "¿Qué incluye?"
- [ ] Lista (ul) con 6-8 features:
  - "Almacenamiento SSD NVMe hasta 50 GB"
  - "1 sitio WordPress/dominio"
  - "Soporte en español por ticket"
  - "CDN integrado"
  - "Backups diarios" (si aplica)
  - "Instalador WordPress en 1 clic"
  - "Certificado SSL incluido"
  - "100+ guías en español"
- [ ] Clase: `gano-el-feature-list`

#### Bloque 3 — Cuándo es para ti (audiencia)
- [ ] H2: "Cuándo es para ti"
- [ ] Párrafo (50-100 palabras): "Emprendedores que lanzan su primer sitio web..."
- [ ] 3 bullets de casos de uso:
  - "Agencia boutique con 5-10 clientes web"
  - "Tienda WooCommerce inicial (< 100 productos)"
  - "Blog profesional o portafolio en crecimiento"
- [ ] Clase: `gano-el-use-cases`

#### Bloque 4 — Objeciones comunes (FAQ)
- [ ] H2: "¿Preguntas?"
- [ ] Acordeón con 4-5 preguntas (extraídas de matriz):
  - "¿Por qué pagarle a Gano si puedo usar GoDaddy directo?" → Respuesta: soporte en español, panel simplificado, etc.
  - "¿Puedo migrar mis datos después?" → Sí, WordPress estándar, exportable
  - "¿Cuál es el SLA?" → [PENDIENTE SLA DIEGO]
  - "¿Puedo upgraarme después?" → Sí, a Fortaleza Delta sin downtime
  - "¿Incluye email?" → [CONFIRMAR CON RCC]
- [ ] Clase: `gano-el-faq-accordion`

#### Bloque 5 — Comparativa con otros planes
- [ ] H2: "¿Cómo se compara?"
- [ ] Tabla mini (3 columnas: Núcleo Prime / Fortaleza Delta / Bastión SOTA)
- [ ] Filas: Almacenamiento, Soporte, SLA, Hardening
- [ ] Marca Núcleo Prime diferenciado (highlight row)
- [ ] Clase: `gano-comparison-mini`

#### Bloque 6 — CTA Final
- [ ] H2: "Listo para empezar?"
- [ ] Botón grande: "Contratar Núcleo Prime — $196.000/mes" → `/reseller-store?pid={{PFID_NUCLEO_PRIME}}`
- [ ] Texto pequeño: "Activación en 5 minutos. Cancela en cualquier momento."
- [ ] Enlace alternativo: "Hablar con el equipo" → formulario contacto

**CSS Classes:**
- [ ] Página contenedor: `gano-ecosystem-detail gano-ecosystem-detail--prime`
- [ ] Botones CTA: `gano-btn gano-btn--primary gano-btn--lg`
- [ ] Precio destacado: `gano-price-highlight` (token dorado)

**Accesibilidad:**
- [ ] Jerarquía H1 → H2 (secciones) clara
- [ ] Acordeón con ARIA expanded/hidden
- [ ] Botones con `:focus-visible`
- [ ] Contraste ≥4.5:1 para body, ≥7:1 recomendado para énfasis

---

### Página 3 — FORTALEZA DELTA (Detalle)

**URL:** `/ecosistemas/fortaleza-delta`  
**Slug:** `fortaleza-delta`  
**Padre:** `/ecosistemas`

**Estructura:** (Análogo a Núcleo Prime, con copy de matriz para Delta)

#### Bloque 1 — Hero
- [ ] H1: "Fortaleza Delta — Hardening activo para negocios en crecimiento"
- [ ] Subheadline: "Más recursos. Mayor seguridad. Visibilidad total."
- [ ] Párrafo 100-150 palabras (de matriz: "Entorno WordPress administrado con hardening activo, mayor asignación de recursos...")
- [ ] CTA: "Activar Fortaleza Delta" → `/reseller-store?pid={{PFID_FORTALEZA_DELTA}}`

#### Bloque 2 — Qué incluye
- [ ] H2: "¿Qué incluye?"
- [ ] Lista (8-10 features):
  - "Almacenamiento NVMe hasta 150 GB"
  - "Hasta 3 sitios/dominios"
  - "Hardening de seguridad base" (WAF, rate limiting, etc.)
  - "Soporte prioritario — [SLA DELTA A CONFIRMAR]"
  - "Respaldos diarios + exportables"
  - "Staging site incluido"
  - "CDN global"
  - "HTTP/3 + QUIC"
  - "Monitoreo proactivo"
  - "Dashboard operativo en español"

#### Bloque 3 — Cuándo es para ti
- [ ] H2: "Cuándo es para ti"
- [ ] Párrafo: "PYMES y tiendas WooCommerce medianas..."
- [ ] 3 bullets:
  - "Tienda WooCommerce de 100-1000 productos"
  - "Agencia con clientes de alto valor"
  - "Sitio que requiere uptime confiable"

#### Bloque 4 — Objeciones comunes
- [ ] H2: "¿Preguntas?"
- [ ] Acordeón (4-5 preguntas de matriz):
  - "¿Por qué el doble del precio de Núcleo?" → Mayor capacidad, hardening, monitoreo
  - "¿Qué tipo de hardening?" → WAF, rate limiting, CSP headers, etc. [DETALLE RCC]
  - "¿Cuál es el tiempo de respuesta del soporte?" → [SLA DELTA DIEGO]
  - "¿Puedo bajar a Núcleo después?" → Sí, sin penalización
  - "¿Incluye SSL wildcard?" → Sí, incluido

#### Bloque 5 — Comparativa con otros planes
- [ ] Tabla mini (Núcleo Prime / Fortaleza Delta / Bastión SOTA)
- [ ] Marca Fortaleza Delta diferenciado

#### Bloque 6 — CTA Final
- [ ] "Activar Fortaleza Delta — $450.000/mes"
- [ ] Enlace: "Hablar con ventas" (si quieren descuento por volumen)

**CSS Classes:**
- [ ] Página: `gano-ecosystem-detail gano-ecosystem-detail--delta`
- [ ] Botones: `gano-btn gano-btn--secondary` (color Delta TBD)

---

### Página 4 — BASTIÓN SOTA (Detalle)

**URL:** `/ecosistemas/bastion-sota`  
**Slug:** `bastion-sota`  
**Padre:** `/ecosistemas`

**Estructura:** (Análogo, con énfasis en SLA y crítico)

#### Bloque 1 — Hero
- [ ] H1: "Bastión SOTA — Rendimiento crítico y SLA empresarial"
- [ ] Subheadline: "99.9% disponibilidad. Incidentes visibles. Infraestructura blindada."
- [ ] Párrafo (de matriz): "Rendimiento crítico Gen4 con seguridad de nivel empresarial..."
- [ ] CTA: "Solicitar Bastión SOTA" → `/reseller-store?pid={{PFID_BASTION_SOTA}}`  
  _(O "Cotizar" si no hay PFID directo para bundles)_

#### Bloque 2 — Qué incluye
- [ ] H2: "¿Qué incluye?"
- [ ] Lista (10+ features, énfasis en confiabilidad):
  - "Almacenamiento NVMe hasta 300 GB (o más, confirmar RCC)"
  - "Dominios ilimitados"
  - "Hardening + WAF + DDoS mitigation avanzada"
  - "SLA 99.9% con crédito" (si cliente falla, descuento automático)
  - "Soporte dedicado 24/7 — [SLA SOTA DIEGO]"
  - "Respaldos continuos + snapshot en tiempo real"
  - "Staging + desarrollo aislados"
  - "Monitoreo y alertas proactivas"
  - "Escalamiento automático ante picos"
  - "Policy de incidentes pública"
  - "Cifrado Post-Cuántico" (aspiracional, si aplica)
  - "Recursos dedicados o garantizados"

#### Bloque 3 — Cuándo es para ti
- [ ] Párrafo: "Operaciones de alta autoridad, SaaS, medios digitales..."
- [ ] 3 bullets:
  - "Tienda con > 10K visitas/día"
  - "Proyecto SaaS crítico sobre WordPress"
  - "Medio digital con SLA exigente"

#### Bloque 4 — Objeciones comunes + Garantías
- [ ] H2: "Confianza y transparencia"
- [ ] Acordeón:
  - "¿Cuál es exactamente el SLA?" → "99.9% disponibilidad, con crédito: [POLÍTICA DIEGO]"
  - "¿Qué pasa si hay un incidente?" → "Notificación en 15 min, reporte post-mortem en 24h [CONFIRMAR]"
  - "¿Cuáles son los recursos garantizados?" → [SPEC RCC: CPU, RAM, conexión]
  - "¿Por qué no un VPS gestionado?" → Especialización WordPress, team en español, soporte reactivo
  - "¿Qué pasa con mis datos si Gano cierra?" → "Exportables en 48h, WordPress estándar, zero lock-in"

#### Bloque 5 — Comparativa
- [ ] Tabla (Núcleo Prime / Fortaleza Delta / Bastión SOTA)
- [ ] Bastión SOTA diferenciado (checkmarks en todas las filas)
- [ ] Fila adicional: "Precio mensual" con $ 196K / $ 450K / $ 890K

#### Bloque 6 — CTA Final + Caso de uso
- [ ] H2: "Listo para blindaje total?"
- [ ] Botón: "Contratar Bastión SOTA — $890.000/mes" (o "Solicitar demo" si sin PFID)
- [ ] Texto: "La infraestructura que tus usuarios merece."
- [ ] Testimonio o case study (si disponible, de lo contrario {{CASE_STUDY_PLACEHOLDER}})

**CSS Classes:**
- [ ] Página: `gano-ecosystem-detail gano-ecosystem-detail--sota`
- [ ] Botones: `gano-btn gano-btn--primary gano-btn--sota` (dorado/premium styling)
- [ ] Badge: `gano-badge gano-badge--sla-certified` (si aplica)

---

## 🔗 Mapping de CTAs a Reseller Store

**Estructura de URLs de checkout:**

```
Base Reseller: /reseller-store/ (redirige a GoDaddy Reseller Store)

Plan URLs:
- Núcleo Prime:      /reseller-store?pid={{PFID_NUCLEO_PRIME}}
- Fortaleza Delta:   /reseller-store?pid={{PFID_FORTALEZA_DELTA}}
- Bastión SOTA:      /reseller-store?pid={{PFID_BASTION_SOTA}}
- Ultimate WP:       /reseller-store?pid={{PFID_ULTIMATE_WP}}

Actual si hay WordPress plugin:
- [rstore_cart_button product_id="{{PFID_PLACEHOLDER}}" label="Contratar"]
```

**Placeholders:**
- `{{PFID_NUCLEO_PRIME}}`
- `{{PFID_FORTALEZA_DELTA}}`
- `{{PFID_BASTION_SOTA}}`
- `{{PFID_ULTIMATE_WP}}` (opcional)
- `{{SLA_RESPONSE_DELTA}}`
- `{{SLA_RESPONSE_SOTA}}`
- `{{SPEC_CPU_SOTA}}`, `{{SPEC_RAM_SOTA}}`, etc.
- `{{CASE_STUDY_SOTA}}`

---

## 🎨 Tokens CSS — Verificación

Usar los mismos tokens de cd-content-001 más opcionales:

| Token | Valor | Usado en |
|-------|-------|----------|
| `--gano-fs-5xl` | 48 px | H1 en hero planes |
| `--gano-fs-4xl` | 36 px | H2 (secciones: Qué incluye, etc.) |
| `--gano-fs-3xl` | 30 px | H3 (bullets, acordeón headers) |
| `--gano-fs-base` | 16 px | Body, listas |
| `--gano-gold` | #D4AF37 | Énfasis, prices, premium buttons |
| `--gano-orange` | (definido) | Botones CTA primarios |
| `--gano-color-surface-dark` | #05080B | Fondos oscuros (si aplica) |
| `--gano-color-text-on-dark` | #E2E8F0 | Texto sobre oscuro |

---

## 📝 Accesibilidad — WCAG AA Checklist

- [ ] Cada página tiene **un único H1**
- [ ] Jerarquía clara: H1 → H2 (secciones) → H3 (subsecciones)
- [ ] Botones con `:focus-visible` anillo dorado (3 px offset 3 px)
- [ ] Contraste mínimo 4.5:1 (texto normal), 7:1 para dorado
- [ ] Acordeones con `aria-expanded`, `aria-hidden` correctos
- [ ] Tablas con `<caption>`, `scope="col"` en headers
- [ ] Iconos decorativos con `aria-hidden="true"` si aplica
- [ ] Imágenes con `alt` descriptivo en español
- [ ] Enlaces en lista (no solo color) diferenciados
- [ ] Sin movimiento/parpadeo > 3 Hz
- [ ] Formularios de contacto con labels asociados

**Validación:**
- [ ] Pasar por https://wave.webaim.org/ (contra sitio en vivo)
- [ ] Revisión Lighthouse accesibilidad (DevTools Chrome)

---

## 📊 Core Web Vitals — Objetivos

| Métrica | Bloque | Objetivo | Acción |
|---------|--------|----------|--------|
| **LCP** | Hero imagen (si existe) | < 2.5s | Optimizar imagen, `fetchpriority="high"` |
| **INP** | Botones, acordeones | < 200ms | Eventos optimizados |
| **CLS** | Todos | < 0.1 | Dimensiones fijas, sin shift |

**Post-implementación:**
- [ ] PageSpeed Insights (mobile + desktop)
- [ ] Rank Math schema JSON-LD presente
- [ ] DebugBear si acceso disponible

---

## 🔄 Flujo de implementación (para Diego)

**Tiempo estimado:** 4-6 horas (4 páginas × 60-90 min cada una)

### Opción A: Elementor (recomendado para rapidez visual)
1. Crear 4 páginas en Elementor (draft)
2. Asignar template `elementor_canvas` o `elementor_full_width`
3. Construir con módulos de Hero, Texto, Acordeón (Elementor Accordion)
4. Asignar clases CSS en cada elemento (Avanzado → CSS Classes)
5. Validar responsive en móvil/tablet
6. Publicar como draft (Diego revisa)

### Opción B: PHP Templates (si no usar Elementor)
1. Crear templates en `wp-content/themes/gano-child/templates/`
   - `page-ecosystem-detail-base.php` (heredada por nucleo-prime, fortaleza-delta, bastion-sota)
   - O 4 templates separados
2. Usar template tags `get_the_title()`, `post_content`, etc.
3. Aplicar en cada página (Ajustes página → Template)

### Bloque de validación (antes de publicar):
1. [ ] Todos los placeholders presentes: `{{PFID_*}}`, `{{SLA_*}}`, etc.
2. [ ] CTAs funcionan (no 404)
3. [ ] Responsive OK (Chrome DevTools)
4. [ ] Lighthouse ≥85 (performance)
5. [ ] WAVE 0 errores
6. [ ] Spell check español

---

## ✅ Definition of Done

La tarea se cierra cuando:
- [ ] 4 páginas creadas (draft o published, decidido por Diego)
- [ ] Copy de matriz integrado 100% (sin Lorem Ipsum)
- [ ] Placeholders {{PFID_*}} presentes donde corresponda
- [ ] CTAs apuntan a `/reseller-store` con estructura correcta
- [ ] Clases CSS aplicadas (gano-ecosystem-detail, gano-btn, etc.)
- [ ] Tablas/acordeones accesibles (ARIA, scope, etc.)
- [ ] Responsive OK (mobile, tablet, desktop)
- [ ] Lighthouse ≥85 (performance)
- [ ] WCAG AA checklist completada
- [ ] WAVE 0 errores

**Bloqueos:** 
- Diego debe confirmar en RCC los 4 PFIDs actuales
- Diego debe proporcionar SLAs reales (Fortaleza Delta, Bastión SOTA)
- Diego debe confirmar specs de cada plan (storage exacto, dominios, etc.)

---

## 📂 Archivos clave

- `memory/content/ecosystems-copy-matrix-wave3.md` — Fuente de copy
- `memory/commerce/rcc-pfid-checklist.md` — Referencia PFID
- `memory/content/content-master-plan-2026.md` — Spec general contenido
- `wp-content/themes/gano-child/style.css` — Tokens CSS
- `wp-content/themes/gano-child/templates/` — Ubicación templates PHP (si aplica)

---

## 📞 Contacto / Escalaciones

Si Diego encuentra:
- ❓ PFID faltante → Escalar a RCC Reseller (Diego acceso)
- ❓ SLA indefinido → Documentar en TASKS.md, postergar publicación
- ❓ Elementor accordion no funcionan → Usar plugin ACF Accordion u HTML puro
- ❓ CTAs no redirigen a Reseller → Revisar shortcode `[rstore_cart_button]` + logging

---

**Generado por:** Claude Dispatch  
**Última actualización:** 2026-04-20 UTC  
**Próxima revisión:** Post-implementación Diego
