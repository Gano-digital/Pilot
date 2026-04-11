# Art Direction — Hero gano.digital
**Autor:** ARCANA C.A.D.E. / Antigravity  
**Fecha:** Abril 2026  
**Arquetipo:** El Mago + El Guardián  
**Template objetivo:** Homepage (`/`) — sección Hero  

---

## Concepto creativo

**"La infraestructura que trabaja antes de que algo falle."**

El hero debe transmitir una sola emoción en 0.3 segundos: **control silencioso**. No euforia tecnológica. No alarma. Precisión en reposo — como un centro de datos a las 3am cuando todo está verde.

El visitante debe sentir que está mirando a alguien que ya tiene todo bajo control. No una promesa. Una constatación.

---

## Moodboard estratégico

```
EMOCIÓN PRINCIPAL:    Certeza
EMOCIÓN SECUNDARIA:   Autoridad técnica (sin arrogancia)
```

### 1. Luz y sombra
- **Dirección:** lateral izquierda, fría, ligeramente desde arriba
- **Temperatura:** fría — 5500–6500K (azul-blanco)
- **Contraste:** alto — el fondo colapsa a negro profundo, los elementos activos brillan
- **Razón:** la luz fría activa precisión e inteligencia. El contraste alto = confianza sin ruido

### 2. Composición
- **Regla:** tercios — el copy ocupa el tercio izquierdo, el elemento visual el derecho
- **Espacio negativo:** mucho — el fondo oscuro tiene que respirar
- **Punto focal:** el H1 en el primer tercio superior. El ojo entra por la palabra "infraestructura" o "NVMe"
- **Profundidad:** plano + intermedio. El Monolith en el fondo como sombra, el copy en primer plano

### 3. Color de producción
- **Temperatura dominante:** `--gano-dark-deep` (#05080C) — negro casi absoluto
- **Acento 1:** `--gano-blue` (#1B4FD8) — azul corporativo como luz de sistema activo
- **Acento 2:** `--gano-purple` (#8B5CF6) — violeta tech como gradiente secundario
- **Acento 3:** `--gano-gold` (#D4AF37) — oro solo en el elemento de mayor jerarquía (ej. "SOTA") — máximo 1 uso
- **Saturación:** baja en el fondo, media en acentos
- **Referencia de grading:** Blade Runner 2049 × AWS re:Invent keynote

### 4. Estilo visual
- **Realismo:** estilizado digital — no fotorrealista, no flat
- **Época visual:** contemporáneo 2025-2026 — data center aesthetic, tech dark
- **Influencia:** Swiss International Typographic Style aplicado a dark UI
- **Referencia de sitios:** Vercel.com, Linear.app, Oxide Computer Company

### 5. Elemento hero (el Monolith)
- **Qué es:** una figura geométrica vertical (rectángulo alto, proporción 2:3) semi-transparente con blur
- **Encuadre:** plano general, centrado-derecha, ocupa 40% del viewport en desktop
- **Relación con el espacio:** integrado — no dominante. El copy manda, el Monolith es la "presencia"
- **Tratamiento:** `backdrop-filter: blur(40px)`, borde `--gano-border-sota`, gradiente sutil de `--gano-indigo-soft` a `--gano-purple-soft`
- **Movimiento:** rotación en Y muy lenta (20s loop) — GSAP ya lo tiene implementado en `gano-sota-fx.js`

---

## Brief de imagen (si se usa fotografía o ilustración)

```
OBJETIVO EN 3 SEGUNDOS: "esto es tecnología de nivel, en español, para mí"

FORMATO: 16:9 horizontal para desktop, 9:16 para mobile
RESOLUCIÓN MÍNIMA: 1920×1080px (WebP + fallback JPG)
PESO MÁXIMO: 200KB en WebP

DESCRIPCIÓN DE LA TOMA (keyshot):
  Vista desde un rack de servidores, ángulo levemente picado, luces LED azul-blanco
  sobre fondo oscuro. Sin personas. Sin logos de terceros.
  La imagen actúa como textura de fondo, no como protagonista.
  El copy se superpone en primer plano.

TOMAS REQUERIDAS:
  1. Hero BG — textura de servidores, oscura, desaturada (el copy va encima)
  2. OG Image — versión con logo + H1 + tagline baked-in (1200×630px)
  3. Mobile BG — versión vertical, punto focal en el tercio superior

ELEMENTOS A EVITAR:
  - Personas en traje mirando pantallas (cliché)
  - Colores cálidos o verdes (rompen la paleta)
  - Logos de marcas externas (AWS, Google, etc.)
  - Texto en la imagen (excepto OG Image)
```

---

## Jerarquía tipográfica del hero

| Nivel | Clase CSS | Contenido | Token |
|---|---|---|---|
| Overline | `.gano-overline` | "Ecosistemas WordPress · Colombia" | `--gano-fs-xs` + `--gano-font-mono` |
| H1 Display | `.gano-display` | "Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle." | `--gano-fs-display` |
| Lead | `.gano-lead` | "Hosting WordPress de alto rendimiento con seguridad de nivel empresarial…" | `--gano-fs-lg` |
| CTA primario | `.gano-btn` | "Ver arquitecturas y planes" | `--gano-orange` bg |
| CTA secundario | link texto | "Hablar con el equipo →" | `--gano-text-slate` |
| Microcopy | `.gano-el-hero-microcopy` | "NVMe · Monitoreo proactivo · Facturación en COP" | `--gano-fs-xs` |

**Aplicar en Elementor:**
- Contenedor padre: clase `gano-el-stack`
- Columna de copy: clase `gano-el-layer-top`
- H1: clase `gano-display gano-el-hero-readability`
- Párrafo intro: clase `gano-lead`
- Microcopy: clase `gano-el-hero-microcopy`

---

## Motion — microinteracciones del hero

| Elemento | Trigger | Acción | Duración | Easing |
|---|---|---|---|---|
| Overline | page load | fade in + slide up 20px | 400ms | `--ease-out` |
| H1 | load + 150ms | TextPlugin reveal (GSAP) | 1500ms | `power2.out` |
| Lead paragraph | load + 400ms | fade in + blur(4px→0) | 600ms | `--ease-out` |
| CTAs | load + 700ms | fade in + translateY(20px→0) | 400ms | `--ease-spring` |
| Monolith | continuo | rotateY 360° loop | 20s | `none` |
| Parallax BG | scroll | yPercent: +20 | scrub | `none` |

**Nota:** Todo el motion respeta `prefers-reduced-motion` via `gano-sota-fx.js` línea 11.

---

## Checklist de aprobación (antes de publicar el hero)

- [ ] El ojo sabe adónde ir en 0.3 segundos (H1 → CTA)
- [ ] La luz/color activa certeza técnica — no euforia ni alarma
- [ ] El Monolith está en segundo plano — no compite con el copy
- [ ] El oro (`--gano-gold`) aparece máximo 1 vez en el viewport
- [ ] El CTA primario tiene contraste WCAG AA mínimo
- [ ] La imagen hero tiene `fetchpriority="high"` y `loading="eager"` (hook LCP en `functions.php` lo agrega automáticamente)
- [ ] La versión mobile tiene el copy legible sin hacer zoom
- [ ] `prefers-reduced-motion` desactiva el Monolith rotation y los reveals

---

## Próximos pasos post-hero

1. **prompt-forge** — traducir este brief a prompts para generación de imagen IA (Midjourney / Firefly)
2. **vibra-id** — formalizar el sistema de tokens visuales como guía de marca exportable
3. **gano-el-stack** — auditar en staging que las clases Elementor aplican correctamente
