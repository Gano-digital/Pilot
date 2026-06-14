# Completar gano.digital P0-P3: Especificación Técnica

**Fecha:** 2026-04-24
**Proyecto:** gano.digital — hosting WordPress Colombia
**Objetivo:** Completar construcción visual + contenido con uniformidad y copy de conversión (P0-P3)
**Entrega:** Todo pusheado a main, merged, deployed a servidor

---

## Resumen Ejecutivo

gano.digital tiene 4 fases completadas (parches, hardening, SEO, Reseller) pero requiere:
1. Rellenar placeholders rotos (P0: 6 items)
2. Fijar credibilidad + CSS (P1-P2: 11 items de CSS + contenido)
3. Enriquecer con copy desde second brain (P3: 6 items)
4. Uniforme visual (paleta, botones, secciones)
5. Index/navegación mejorada (hero + menú principal)
6. Integrar assets visuales
7. Deploy final a producción

**Scope:** Una sesión monolítica con subagents paralelos donde sea posible. Tono copywriting: balanceado técnico + emocional.

---

## 1. ARQUITECTURA Y DEPENDENCIAS

### Bloques de ejecución (orden secuencial)

```
┌─────────────────────────────┐
│ 1. CSS Foundation (P2)      │  Paleta uniforme, tokens, conflictos resueltos
│    ↓                        │
├─────────────────────────────┤
│ 2. P0 Content (Placeholders)│  Rellenar NIT, SLA, Wompi→Reseller, beneficios, horarios, equipo
│    ↓                        │
├─────────────────────────────┤
│ 3. P1 Credibility (CSS+UI)  │  Botones uniformes, dark sections, SOTA CSS extraction
│    ↓                        │
├─────────────────────────────┤
│ 4. P3 Enrichment (Copy)     │  Hero, ecosistemas, trust signals, FAQ schema, footer, nav
│    ↓                        │
├─────────────────────────────┤
│ 5. Navigation (Hero + Menu) │  Homepage index + menú primario mejorado
│    ↓                        │
├─────────────────────────────┤
│ 6. Assets Visual Integration│  Imágenes, iconos, decorativos (generar + internet)
│    ↓                        │
├─────────────────────────────┤
│ 7. Final Audit + Deploy     │  No placeholders, paleta uniforme, lighthouse, deploy SSH
└─────────────────────────────┘
```

### Dependencias

- **CSS Foundation** → bloqueante para todos (paleta base)
- **P0** → bloqueante para P1 (P0 desbloquea credibilidad)
- **P1 + P3** → paralelos después de P0
- **Navigation** → después de P1 + P3 (tenemos contenido)
- **Assets** → después de Navigation (sabemos dónde van)
- **Audit + Deploy** → final, después de todo

**Paralelización posible:**
- P0 + CSS Foundation: 80% paralelos (CSS no bloquea mucho de P0)
- P1 + P3: completamente paralelos
- Assets: paralelo con Navigation

---

## 2. DETALLE: BLOQUE CSS FOUNDATION (P2)

**Objetivo:** Paleta uniforme, 0 conflictos CSS, todos los tokens declarados.

### Problemas a resolver

| P2-ID | Problema | Solución |
|-------|----------|----------|
| P2-1 | Tres negros distintos (#05070a, #0f1115, #0F1923) | Usar ÚNICO: `--gano-bg-dark: #0F1923` en `:root` |
| P2-2 | `:root` duplicado (líneas 861-867) | Fusionar en bloque único, eliminar `--gano-dark-bg` |
| P2-3 | `--gano-fw-*`, `--gano-lh-*` no declarados | Añadir a `:root`: fw-bold, fw-semibold, fw-extrabold, lh-tight, lh-relaxed, lh-base, lh-snug |
| P2-4 | `--gano-blue` hardcodeado #2952CC en fallbacks | Reemplazar todo → `#1B4FD8` (valor canónico) |
| P2-5 | Estilos inline en templates (page-*.php) | Extraer bloques `<style>` → `style.css` |
| P2-6 | `--gano-radius` no declarado | Añadir: `--gano-radius: var(--gano-radius-md)` |

### Cambios CSS específicos

**File: `wp-content/themes/gano-child/style.css`**

1. **Fusionar `:root` bloques** (líneas ~1-100, 861-867):
   - Traer todo a ÚNICO bloque `:root`
   - Eliminar duplicados
   - Añadir tokens faltantes:
     ```css
     :root {
       /* Existentes */
       --gano-blue: #1B4FD8;
       --gano-green: #00C26B;
       --gano-orange: #FF6B35;
       --gano-bg-dark: #0F1923;  /* ÚNICO */

       /* Nuevos */
       --gano-fw-bold: 700;
       --gano-fw-semibold: 600;
       --gano-fw-extrabold: 800;
       --gano-lh-tight: 1.1;
       --gano-lh-relaxed: 1.6;
       --gano-lh-base: 1.5;
       --gano-lh-snug: 1.375;
       --gano-radius: var(--gano-radius-md, 8px);
     }
     ```

2. **Definir `.gano-dark-section`**:
   ```css
   .gano-dark-section {
     background-color: var(--gano-bg-dark);
     color: white;
     padding: 48px 32px;
     border-radius: var(--gano-radius-lg);
   }
   ```

3. **Fijar `.gano-btn-primary` (ÚNICO estilo)**:
   ```css
   .gano-btn-primary {
     background-color: var(--gano-orange); /* #FF6B35 */
     color: white;
     border: none;
     border-radius: 8px;
     padding: 12px 32px;
     font-weight: var(--gano-fw-semibold);
     cursor: pointer;
     transition: all 220ms var(--motion-ease-out);
   }
   .gano-btn-primary:hover {
     background-color: darken(var(--gano-orange), 10%);
     transform: translateY(-2px);
     box-shadow: 0 8px 16px rgba(255, 107, 53, 0.3);
   }
   ```

4. **Reemplazar hardcodes `#2952CC` → `#1B4FD8`** en:
   - `page-nosotros.php`
   - `page-sla.php`
   - `page-contacto.php`

5. **Extraer estilos inline** de:
   - `page-nosotros.php`: `<style>` → nueva sección en `style.css` con prefix `.gano-page-nosotros`
   - `page-sla.php`: `<style>` → nueva sección con prefix `.gano-page-sla`
   - `page-sota-hub.php`: 100% inline → nuevo archivo `css/sota-hub.css`

---

## 3. DETALLE: BLOQUE P0 CONTENT (Placeholders rotos)

**Objetivo:** 0 placeholders visibles, contenido definitivo.

| P0-ID | Archivo | Placeholder Actual | Reemplazo Definitivo |
|-------|---------|-------------------|----------------------|
| P0-1 | page-privacidad.php, page-terminos.php | `[NIT_PENDIENTE]` | "Gano Digital SAS (en proceso de constitución), NIT: En trámite" |
| P0-2 | page-sla.php | "Por confirmar" (4 rows) | Crítico 4h / Alto 8h / Medio 24h / Bajo 72h (texto: "Objetivo de tiempo de respuesta") |
| P0-3 | page-seo-landing.php | "Startup Blueprint" + precios viejos | Sincronizar con catálogo: Núcleo Prime $196K / Fortaleza Delta $450K / Bastión SOTA $890K / Ultimate WP $1.2M |
| P0-4 | page-seo-landing.php | "Wompi Colombia" en trust signals | Reemplazar → "GoDaddy Reseller" (white-label, sin mencionar GoDaddy en copy, solo en logos) |
| P0-5 | page-ecosistemas.php | Beneficios con `[confirmar con RCC]` | Descripción real de cada ecosistema (copy desde second brain) |
| P0-6 | page-contacto.php | "Por confirmar" (horario) | "Soporte 24/7 vía ticket, primera respuesta hasta 8 horas hábiles" |
| P0-7 | page-nosotros.php | "Sección en preparación. Próximamente" (equipo) | Diego Sandoval — Fundador. Bio: "Psicólogo organizacional y consultor técnico con experiencia en hosting, seguridad web y soluciones digitales para empresas colombianas." (sin foto por ahora, avatar placeholder) |

---

## 4. DETALLE: BLOQUE P1 CREDIBILITY (CSS + UI + Content)

**Objetivo:** Credibilidad visual + contenido confiable.

### P1-1: Equipo real en `/nosotros/`
- Cambiar "Sección en preparación" → bio real de Diego
- Estructura: nombre + rol + bio breve + avatar placeholder
- Copy fuente: CV ejecutivo de Diego

### P1-2: Horario de atención en `/contacto/`
- Cambiar "Por confirmar" → "Soporte 24/7 vía ticket. Primera respuesta: hasta 8 horas hábiles (Lunes-Viernes)"
- Añadir tooltip: "Urgencias críticas: contacta a support@gano.digital"

### P1-3: Botones uniformes (`.gano-btn-primary`)
- **Conflicto actual:** 3 estilos distintos (gradiente azul/verde, gradiente SOTA, naranja sólido)
- **Solución:** Fijar como naranja sólido #FF6B35 + border-radius 8px en style.css (ver P2 CSS)
- **Aplicar a:** Todos los botones primarios en el sitio (buscar `.gano-btn-primary` en templates)

### P1-4: Estilos inline extraction
- `page-sota-hub.php`: 100% estilos inline → crear `css/sota-hub.css` con todas las clases
- `page-nosotros.php`: `<style>` embebido → extraer a `style.css`
- `page-sla.php`: `<style>` embebido → extraer a `style.css`

### P1-5: Clase `.gano-dark-section` sin definición
- Definir en `style.css` (ver P2 CSS Foundation)
- Aplicar a todas las secciones que la usan (búsqueda: `.gano-dark-section`)

---

## 5. DETALLE: BLOQUE P3 ENRICHMENT (Copy desde Second Brain)

**Objetivo:** Enriquecer contenido con copy profesional de conversión.

### P3-1: Homepage copy
**Fuente:** `wiki/content-library/copy/homepage-copy-2026-04.md`
**Qué:** Hero H1 + pilares + métricas + CTA
**Dónde:** `front-page.php` o elemento Elementor si es página visual
**Copy a inyectar:**
- H1 hero (reemplazar placeholder)
- Subtítulo 2-3 líneas
- 3 pilares con descripción breve
- Métricas/social proof (uptime %, clientes, etc.)

### P3-2: Ecosistemas tabla comparativa
**Fuente:** `wiki/content-library/copy/ecosystems-copy-matrix-wave3.md`
**Qué:** Tabla 6 filas (Specs, Features, Support, Price, Uptime, etc.) × 4 ecosistemas
**Dónde:** `page-ecosistemas.php`
**Copy a inyectar:** Descripción detallada de cada ecosistema (Núcleo Prime, Fortaleza Delta, Bastión SOTA, Ultimate WP)

### P3-3: Menú primario mejorado
**Fuente:** `wiki/content-library/copy/navigation-primary-spec-2026.md`
**Qué:** Dropdowns para Ecosistemas, Pilares, CTA naranja
**Dónde:** `header.php` o elemento nav de Elementor
**Copy a inyectar:**
```
Gano Digital | Hosting | Ecosistemas ▼ | Pilares ▼ | Nosotros | Contacto | [ CTA ]

Dropdowns:
  Ecosistemas: Núcleo Prime, Fortaleza Delta, Bastión SOTA, Ultimate WP
  Pilares: Soporte 24/7, Seguridad, Performance, Migración Gratis
```

### P3-4: Trust signals GoDaddy Reseller
**Fuente:** `wiki/content-library/copy/trust-and-reseller-copy-wave3.md`
**Qué:** "Powered by GoDaddy Reseller" + facturación en COP nativa + soporte en español
**Dónde:** Footer + sección trust signals en ecosistemas
**Copy a inyectar:** "Reseller certificado", "Facturación en pesos colombianos (COP)", "Soporte en español 24/7"

### P3-5: Footer enriquecido
**Fuente:** `wiki/content-library/copy/footer-contact-audit-wave3.md`
**Qué:** Emails, WhatsApp, links legales, social links
**Dónde:** `footer.php` o elemento footer Elementor
**Copy a inyectar:** Links correctos, emails, teléfono WhatsApp, políticas de privacidad/términos

### P3-6: FAQ Schema (JSON-LD)
**Fuente:** `wiki/content-library/copy/faq-schema-candidates-wave3.md`
**Qué:** FAQ en `/ecosistemas/` y `/nosotros/` con structured data JSON-LD
**Dónde:** Dentro de cada página template + enqueue en functions.php
**Copy a inyectar:** Q&A pairs (ej: "¿Qué incluye Núcleo Prime?" → respuesta clara + SEO boost)

---

## 6. DETALLE: BLOQUE NAVIGATION (Hero + Menú)

**Objetivo:** Navegación intuitiva hacia todo el contenido enriquecido.

### Homepage Hero Section
**Ubicación:** `front-page.php` o sección hero Elementor
**Estructura:**
```html
┌─────────────────────────────────────────┐
│  Imagen hero branded (generar/stock)    │
│                                         │
│  "¿Qué es Gano?"                       │
│  Subtítulo: "Hosting WordPress en      │
│  Colombia, con soporte real y precios  │
│  que funcionan"                        │
│                                         │
│  [ Comenzar Ahora ] [ Ver Documentos ] │
│                                         │
│  ───────────────────────────────────  │
│  3 puntos clave:                       │
│  • Soporte 24/7 en español            │
│  • Seguridad Wordfence incluida       │
│  • Uptime 99.9% garantizado           │
│                                         │
└─────────────────────────────────────────┘
```

**Copy:** Usar de homepage-copy-2026-04.md
**CTA:** "Comenzar Ahora" → naranja #FF6B35, links a `/register/` (GoDaddy Reseller)

### Menú Primario Mejorado
**Ubicación:** `header.php` o nav Elementor
**Estructura:**
```
Gano Digital Logo | Inicio | Hosting | Ecosistemas ▼ | Pilares ▼ | Nosotros | Contacto | [ Registrarse ]

Dropdown Ecosistemas:
  ├─ Núcleo Prime ($196K/mes)
  ├─ Fortaleza Delta ($450K/mes)
  ├─ Bastión SOTA ($890K/mes)
  └─ Ultimate WP ($1.2M/mes)

Dropdown Pilares:
  ├─ Soporte 24/7
  ├─ Seguridad Wordfence
  ├─ Performance (Core Web Vitals)
  └─ Migración Gratis
```

**Copy:** Usar de navigation-primary-spec-2026.md
**Estilo:** Menú primario actual + añadir dropdowns con CSS hover/focus
**CTA principal:** Botón "Registrarse" naranja, links a `/register/`

---

## 7. DETALLE: BLOQUE ASSETS VISUAL

**Objetivo:** Integrar imágenes, iconos, elementos decorativos alineados a brand.

### Estrategia de Assets

| Tipo | Fuente | Acción |
|------|--------|--------|
| Hero Image | Generar (DALL-E/Stable) + Stock (Unsplash) | Generar 1-2 opciones brand-aligned (azul/verde/naranja), seleccionar mejor |
| Iconos Features | Heroicons / Feather Icons | Traer libres (SVG), integrar en secciones |
| Fondos decorativos | Existentes SOTA design | Reutilizar gradientes, patterns del design system |
| Logo | Existente en repo | Reutilizar gano-logo.svg |
| Colores | Paleta `:root` CSS | Usar tokens CSS (--gano-blue, --gano-orange, etc.) |

### Generación de Assets

**Hero Image:**
```
Prompt: "Modern WordPress hosting dashboard, Colombia theme, blue and orange accents,
clean minimalist design, professional, tech company aesthetic"
Tool: DALL-E 3 (si tienes API) o Stable Diffusion
Alternativa: Stock de Unsplash (buscar "wordpress hosting", "tech dashboard")
```

**Iconos:**
- Soporte 24/7: `headphones` (Heroicons)
- Seguridad: `shield-check` (Heroicons)
- Performance: `lightning-bolt` (Heroicons)
- Uptime: `check-circle` (Heroicons)

### Integración en Sitio

- Hero image: URL en `front-page.php` background-image o `<img>`
- Iconos: SVG inline o via icon font (recomendado: SVG inline para control de color)
- Fondos: CSS gradients en `.gano-dark-section` y secciones SOTA

---

## 8. FLUJO DE VALIDACIÓN FINAL

**Antes de deploy:**

1. **Auditoría de placeholders:**
   - Grep toda la carpeta `wp-content/themes/gano-child/` por:
     - `[` y `]` (marcadores)
     - "Por confirmar"
     - "Lorem ipsum"
     - "TBD", "TODO"
   - Resultado esperado: 0 matches

2. **Auditoría CSS:**
   - Verificar `:root` es único (no duplicados)
   - Verificar `.gano-btn-primary` tiene un SOLO estilo
   - Verificar `--gano-bg-dark: #0F1923` es el único negro usado
   - Buscar estilos inline restantes (debería haber 0 bloques `<style>` en templates)

3. **Auditoría de copy:**
   - Verificar copy es balanceado (técnico + emocional)
   - Verificar CTAs son consistentes (color, mensaje)
   - Verificar no hay referencias a "Wompi", "legacy", etc.

4. **Validación visual:**
   - Lighthouse score ≥90 en Performance, Accessibility, SEO
   - Verificar paleta uniforme: no colores contrastantes rotos
   - Verificar assets cargados (sin 404s)
   - Verificar responsividad (desktop + mobile)

5. **Validación de servidor:**
   - SSH a servidor, verificar no hay errores PHP
   - Website 200 OK
   - Homepage carga en <3s
   - Todos los assets accesibles

---

## 9. DEPLOYMENT FINAL

**Pipeline:**
```
Feature branch: feature/gano-completion

Commits (en orden):
1. CSS Foundation (P2 fixes, tokens, uniformidad)
2. P0 Content (placeholders → definitivo)
3. P1 Credibility (botones, dark sections, inline CSS extraction)
4. P3 Enrichment (copy from second brain, FAQ schema)
5. Navigation (hero section + menu mejorado)
6. Assets Visual (imágenes, iconos integrados)
7. Final audit (validaciones + cleanup)

Push → GitHub Actions detects

PR #279 merge: Fusionar el feature/gano-completion → main

Post-merge:
- GitHub Actions deploy.yml dispara automáticamente
- rsync SSH sincroniza cambios al servidor
- Verificación post-deploy: website 200 OK, assets loaded
```

**Rollback:** Si hay problema post-deploy, git revert último commit y re-deploy.

---

## 10. ÉXITO: CRITERIA Y DEFINITION OF DONE

**Sitio está COMPLETO cuando:**

✅ **P0:** 0 placeholders visibles, contenido definitivo (NIT, SLA, Wompi→Reseller, beneficios, horarios, equipo)
✅ **P1:** Credibilidad visual (botones uniformes naranja, dark sections, CSS extraction completo)
✅ **P2:** CSS paleta uniforme (`:root` único, 0 conflictos, tokens completos, 0 estilos inline)
✅ **P3:** Copy enriquecido desde second brain (hero, ecosistemas, trust signals, FAQ, footer, nav)
✅ **Navigation:** Hero section en homepage + menú primario mejorado con dropdowns
✅ **Assets:** Imágenes, iconos, decorativos integrados y cargando sin errores
✅ **Validación:** Lighthouse ≥90, 0 placeholders, paleta uniforme, responsividad OK
✅ **Deployment:** Todo pushed, merged, deployed a servidor, verificado 200 OK

**Estimado de tiempo:** 4-6 horas para una sesión intensiva con subagents paralelos.

---

## Resumen de Archivos a Modificar

| Archivo | Tipo | Cambios |
|---------|------|---------|
| `style.css` | Modify | Fusionar `:root`, añadir tokens, fijar `.gano-btn-primary`, extraer inline styles |
| `page-privacidad.php` | Modify | Reemplazar `[NIT_PENDIENTE]` |
| `page-terminos.php` | Modify | Reemplazar `[NIT_PENDIENTE]` |
| `page-sla.php` | Modify | Reemplazar "Por confirmar" → tiempos SLA, extraer `<style>` |
| `page-seo-landing.php` | Modify | Sincronizar planes/precios, eliminar "Wompi" |
| `page-ecosistemas.php` | Modify | Reemplazar beneficios `[confirmar RCC]` con copy real |
| `page-contacto.php` | Modify | Reemplazar horario "Por confirmar" |
| `page-nosotros.php` | Modify | Añadir equipo real (Diego), extraer `<style>` |
| `page-sota-hub.php` | Modify | Extraer 100% estilos inline → nuevo archivo |
| `front-page.php` | Modify | Enriquecer hero section + navigation |
| `header.php` | Modify | Mejorar menú primario con dropdowns |
| `footer.php` | Modify | Enriquecer con copy auditado |
| `css/sota-hub.css` | Create | Nuevas clases de page-sota-hub extraídas |
| `functions.php` | Modify | Enqueue novo sota-hub.css, JSON-LD FAQ schema |

---

**Plan redactado:** Listo para writing-plans skill.
**Spec status:** FINAL, listo para uso.

