# Tarea cd-content-001 — Wave contenidos: homepage 2026

**ID:** `cd-content-001`  
**Prioridad:** P1  
**Requiere humano:** NO (documentación + preparación)  
**Generado:** 2026-04-19  
**Estado:** Listo para implementación por Diego en wp-admin

---

## Objetivo

Alinear la homepage en Elementor con:
1. Orden canónico de 6 bloques (spec 2026)
2. Clases CSS y tokens visuales completos
3. Copy finales en español (es-CO)
4. Accesibilidad WCAG AA + Core Web Vitals

**Sin tocar wp-admin:** Cambios son documentables, CSS yá existe, copy listo. Diego implementa en Elementor en ~90 min.

---

## 📋 Checklist de implementación — 6 bloques

### Bloque 1 — HERO

**Estado actual:** ¿Existe en Elementor?  
**Necesario:**
- [ ] Contenedor principal con clase `gano-el-stack`
- [ ] Columna izquierda (copy) con clase `gano-el-layer-top`
  - [ ] H1: "Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle."
  - [ ] Subheadline: "Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad."
  - [ ] CTA primario (botón naranja): "Ver arquitecturas y planes" → `/ecosistemas`
  - [ ] CTA secundario (enlace): "Hablar con el equipo" → formulario contacto
  - [ ] Microcopy: "NVMe · Monitoreo proactivo · Facturación en COP" (clase `gano-el-hero-microcopy`)
- [ ] Columna derecha (imagen/shape) con clase `gano-el-layer-base`
  - [ ] Imagen o shape decorativa del hero
  - [ ] `fetchpriority="high"` + `loading="eager"` (inyectado por MutationObserver en JS)
- [ ] Widget H1 con clase `gano-el-hero-readability`
- [ ] Tipografía: H1 token `--gano-fs-5xl` (48 px clamp)

**Validación CWV:**
- [ ] Imagen hero optimizada (< 150 KB)
- [ ] LCP testeable en <2.5s
- [ ] Sin layout shift (CLS < 0.1)

---

### Bloque 2 — CUATRO PILARES

**Estado actual:** ¿Existe?  
**Necesario:**
- [ ] Sección con espaciado `padding: var(--gc-section-y) 0` (clamp 56–96 px)
- [ ] H2 de sección: "Nuestras ventajas" (centrada)
- [ ] Grid 2×2 (escritorio) → stack (móvil)
- [ ] Cada tarjeta con clase `gano-el-pillar`
  - [ ] **Pilar 1:** Título: "NVMe que se nota en Core Web Vitals, no solo en el folleto." + Texto de descripción
  - [ ] **Pilar 2:** Título: "WordPress endurecida para el tráfico real de un negocio." + Texto
  - [ ] **Pilar 3:** Título: "Confianza cero por defecto: identidad, sesiones y permisos bajo control." + Texto
  - [ ] **Pilar 4:** Título: "Contenido más cerca del usuario, sin magia barata." + Texto
- [ ] Cada pilar tiene H3 + párrafo `<p>`
- [ ] Sin CTA en tarjetas (solo información)
- [ ] Íconos SVG con `aria-hidden="true"` si son decorativos

**Tipografía:**
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] H3 (títulos pilar): `--gano-fs-3xl` (30 px)
- [ ] Body texto: `--gano-fs-base` (16 px)

---

### Bloque 3 — UN SOCIO TECNOLÓGICO

**Estado actual:** ¿Existe?  
**Necesario:**
- [ ] Sección con fondo `--gano-home-section--surface` (gradiente oscuro)
- [ ] H2 centrada: "Un socio tecnológico que trabaja en silencio."
- [ ] Párrafo principal con clase `gano-el-prose-narrow` (max-width ~65 caracteres, centrado)
  - Texto: "Gano Digital no compite en "hosting barato". Compite en **continuidad**: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico para que no te enteres de los incidentes por redes sociales."
- [ ] Lista (`<ul>`) de 3 bullets:
  - "Infraestructura alineada a cargas reales de WordPress y comercio."
  - "Monitoreo y respuesta con foco en negocio, no en excusas."
  - "Roadmap claro hacia soberanía digital y cumplimiento en Colombia."
- [ ] CTA opcional (enlace de texto): "Conocer nuestra historia" → `/nosotros`

**Tipografía:**
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] Párrafo: `--gano-fs-base` (16 px), line-height `--gano-lh-relaxed` (1.65)

---

### Bloque 4 — MÉTRICAS / FRANJA DE CONFIANZA

**Estado actual:** ¿Existe?  
**Necesario:**
- [ ] Sección franja con clase `gano-el-metrics`
- [ ] Grid de celdas (3+ columnas en escritorio) con clase `gano-el-metric` cada una
- [ ] **IMPORTANTE:** Usar solo cifras verificables (SLA o mediciones reales)

**Ejemplos de métricas honestas:**
- [ ] "99.9% disponibilidad" (según plan, respaldar con SLA)
- [ ] "NVMe en todos los planes" (verificable)
- [ ] "Soporte 24/7 en español" (definir canal operativo)

**¿NO incluir?**
- ❌ "Millones de clientes" (sin contrato)
- ❌ Logos de clientes sin contrato
- ❌ Cifras infladas

**Tipografía:**
- [ ] Sin H2 en este bloque (franja visual)
- [ ] Cifra grande: `--gano-fs-3xl` (30 px, weight 700)
- [ ] Etiqueta bajo cifra: `--gano-fs-sm` (14 px)

---

### Bloque 5 — ECOSISTEMAS / PLANES

**Estado actual:** ¿Existe?  
**Necesario:**
- [ ] Sección con H2: "Elige tu arquitectura"
- [ ] Grid de 3 tarjetas (cada plan: Núcleo Prime, Fortaleza Delta, Bastión SOTA)
- [ ] **Cada tarjeta:**
  - [ ] H3 con nombre del plan
  - [ ] Precio en COP (ej: "desde $X.XXX/mes")
  - [ ] 3-4 features o características
  - [ ] Botón CTA con clase `gano-btn`: "Elegir este plan" → URL producto (Reseller Store o `/ecosistemas`)
- [ ] Precios conectados a WooCommerce o SKU Reseller

**Tipografía:**
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] H3 (plan): `--gano-fs-3xl` (30 px)
- [ ] Precio: `--gano-fs-2xl` (24 px, color dorado `--gano-gold`)
- [ ] Features: `--gano-fs-base` (16 px)

---

### Bloque 6 — CTA FINAL

**Estado actual:** ¿Existe?  
**Necesario:**
- [ ] Sección oscura con fondo `--gano-color-surface-dark`
- [ ] H2 retórica (centrada): "¿Listo para una infraestructura que no te pida disculpas?"
- [ ] Botón primario con clase `gano-btn`: "Elegir mi arquitectura" → `/ecosistemas`
- [ ] (Opcional) Shortcode `[gano_cta_icons]`: 5 íconos de beneficios con etiquetas
  - Bolt (⚡) + "Velocidad real"
  - Shield halved (🛡️) + "Seguridad"
  - Circle check (✓) + "Confiabilidad"
  - Headset (🎧) + "Soporte"
  - Coins (💰) + "Precio justo"

**Tipografía:**
- [ ] H2: `--gano-fs-4xl` (36 px), color `#FFFFFF`
- [ ] Botón: `--gano-btn` con background `--gano-orange`
- [ ] Etiquetas de icono: `--gano-fs-sm` (14 px)

**Accesibilidad:**
- [ ] Botón con `:focus-visible` naranja
- [ ] Anillo de foco de 3 px offset 3 px
- [ ] Contraste WCAG AA mínimo (4.5:1)

---

## 🎨 Tokens CSS — Verificación

Todos los tokens deben estar en `wp-content/themes/gano-child/style.css`:

| Token | Valor | Usado en |
|-------|-------|----------|
| `--gano-fs-display` | 72 px | Bloque 1 (hero headline opcional) |
| `--gano-fs-5xl` | 48 px | Bloque 1 (H1) |
| `--gano-fs-4xl` | 36 px | H2 en bloques 2, 3, 5, 6 |
| `--gano-fs-3xl` | 30 px | H3 (tarjetas, precios) |
| `--gano-fs-xl` | 20 px | H4 (etiquetas) |
| `--gano-fs-base` | 16 px | Body, párrafos |
| `--gano-fs-sm` | 14 px | Microcopy, etiquetas |
| `--gano-fs-xs` | 12 px | Meta, badges |
| `--gano-lh-tight` | 1.1 | Display, H1 |
| `--gano-lh-snug` | 1.25 | H2, H3 |
| `--gano-lh-base` | 1.5 | Etiquetas, microcopy |
| `--gano-lh-relaxed` | 1.65 | Body, párrafos |
| `--gano-gold` | #D4AF37 | Énfasis, focus, premiums |
| `--gano-orange` | (definido) | Botones CTA |
| `--gano-color-surface-dark` | #05080B | Fondos oscuros |
| `--gano-color-text-on-dark` | #E2E8F0 | Texto sobre oscuro |

**Verificación:**
- [ ] Abrir `wp-content/themes/gano-child/style.css`
- [ ] Confirmar que `:root` contiene todos los tokens listados arriba
- [ ] Si falta alguno: crear entrada en `:root`

---

## 🎯 Clases CSS — Asignación en Elementor

En Elementor, para cada elemento:
1. Ir a **Avanzado** → **CSS Classes**
2. Agregar la clase correspondiente (separadas por espacio si múltiples)

**Ejemplo bloque 1:**
- Contenedor: `gano-el-stack`
- Columna copy: `gano-el-layer-top`
- Widget H1: `gano-el-hero-readability`

---

## 📝 Accesibilidad — WCAG AA Checklist

- [ ] Cada página tiene **un único H1** (en Bloque 1)
- [ ] Jerarquía clara: H1 → H2 (secciones) → H3 (subsecciones)
- [ ] Botones tienen `:focus-visible` con anillo dorado (3 px)
- [ ] Contraste mínimo 4.5:1 (texto normal), 7:1 recomendado para dorado
- [ ] Íconos decorativos tienen `aria-hidden="true"`
- [ ] Imágenes tienen `alt` descriptivo en español
- [ ] Enlaces de texto tienen `:hover` y `:active` visibles
- [ ] Sin movimiento/parpadeo > 3 Hz
- [ ] Formularios tienen labels asociados

**Validación:**
- [ ] Pasar por https://wave.webaim.org/ (contra home en vivo)
- [ ] Revisión Lighthouse en Chrome DevTools (pestaña Accessibility)

---

## 📊 Core Web Vitals — Objetivos por bloque

| Métrica | Bloque afectado | Objetivo | Acción |
|---------|-----------------|----------|--------|
| **LCP** | Hero imagen | < 2.5s | `fetchpriority="high"` + optimización imagen |
| **INP** | Botones (todos) | < 200ms | Eventos optimizados, sin lag en hover |
| **CLS** | Todos | < 0.1 | Dimensiones fijas, sin layout shift |
| **VSI** (nuevo 2026) | Todos | < 0.15 | Estabilidad visual en sesión completa |

**Post-implementación:**
- [ ] Testear con Google PageSpeed Insights (mobile + desktop)
- [ ] Revisar Rank Math en wp-admin (schema JSON-LD debe estar presente)
- [ ] Validar con DebugBear si acceso disponible

---

## 🔄 Flujo de implementación (para Diego)

**Tiempo estimado:** 90 minutos

1. **Preparación (10 min)**
   - Abrir WordPress en navegador privado para verificar estado actual
   - Abrir Elementor editor en homepage (draft o en vivo)
   - Tener abierto `memory/content/homepage-section-order-spec-2026.md` como referencia

2. **Auditoría (15 min)**
   - ¿Existen los 6 bloques ya?
   - ¿Qué copy es placeholder?
   - ¿Qué clases faltan?
   - Documentar en tabla de estado

3. **Implementación por bloques (60 min)**
   - Bloque 1 — Hero (15 min)
   - Bloque 2 — Pilares (12 min)
   - Bloque 3 — Socio (10 min)
   - Bloque 4 — Métricas (8 min)
   - Bloque 5 — Ecosistemas (10 min)
   - Bloque 6 — CTA Final (5 min)

4. **Validación (15 min)**
   - Publicar borrador a staging site
   - Testing en móvil + escritorio
   - Lighthouse check
   - Accesibilidad Wave

5. **Post-implementación (opcional, no bloqueante)**
   - Conectar precios a Reseller Store (Fase 4 comercio)
   - Configurar Rank Math schema (Fase 2 SEO)

---

## 📂 Archivos clave

- `memory/content/homepage-section-order-spec-2026.md` — Spec canónica
- `memory/content/homepage-copy-2026-04.md` — Copy lista (este documento)
- `memory/content/visual-tokens-wave3.md` — Escala tipográfica + tokens
- `memory/content/elementor-home-classes.md` — Mapeo clases → elementos
- `wp-content/themes/gano-child/css/homepage.css` — Estilos implementados
- `wp-content/themes/gano-child/style.css` — Tokens :root

---

## ✅ Definition of Done

La tarea se cierra cuando:
- [ ] Homepage sigue orden canónico de 6 bloques
- [ ] Todas las clases CSS aplicadas en Elementor
- [ ] Copy 100% reemplazado (sin placeholders)
- [ ] Tokens visuales validados `:root`
- [ ] WCAG AA checklist completada
- [ ] Lighthouse score ≥ 85 (performance) en móvil
- [ ] LCP < 2.5s, INP < 200ms, CLS < 0.1
- [ ] Accesibilidad Wave: 0 errores

---

## 📞 Contacto / Escalaciones

Si Diego encuentra:
- ❓ Elemento Elementor no existe → Crear en Elementor, reporte de creación
- ❓ Token CSS falta → Agregar a `:root` en `style.css`, PR
- ❓ Copy necesita revisión → Diego realiza ajustes, documenta en commit
- ❓ Precio no disponible en Reseller → Escalar a Fase 4 (tarea separada ag-phase4-002)

---

**Generado por:** Claude Code dispatch  
**Última actualización:** 2026-04-19 20:45 UTC  
**Próxima revisión:** Post-implementación
