# Plan maestro SOTA de convergencia visual y narrativa (abril 2026)

## 1) Alcance y objetivo

Unificar lo ya construido en `gano.digital` (theme + Elementor + templates comerciales + Constellation) en un sistema visual, narrativo y operativo coherente, pulcro y elegante, tomando como referencia:

- `C:\Users\diego\Downloads\_ARCHIVO_WEB\Gano_Digital_Showcase.html`
- `C:\Users\diego\Downloads\stitch\stitch\prototypes\obsidian-prism\code.html`
- `C:\Users\diego\Downloads\stitch\stitch\prototypes\obsidian-prism\DESIGN.md`
- estado real del repo (`wp-content/themes/gano-child`, `memory/content`, `memory/ops`)

## 2) Investigacion SOTA (hallazgos consolidados)

### 2.1 Lo mejor de cada referencia

1. `Gano_Digital_Showcase.html`
   - Aporta una narrativa comercial clara por bloques (Hero -> Planes -> Quiz -> Innovacion).
   - Tiene un sistema de clases simple, facil de portar a Elementor.
   - Debilidad: mezcla estilos inline, claims duros no verificables y errores de redaccion.

2. `obsidian-prism/code.html`
   - Aporta una direccion visual premium de alto nivel (superficies nocturnas, glow sutil, HUD tecnico).
   - Excelente estructura para "hero insignia" y tarjetas de valor con profundidad.
   - Debilidad: usa Tailwind CDN (no permitido en productivo del proyecto).

3. `obsidian-prism/DESIGN.md`
   - Aporta reglas de sistema transferibles: no-line rule, glass + gradient rule, capas tonales, grid asimetrico.
   - Es util como marco de diseno para estandarizar criterio entre agentes.

### 2.2 Matriz de adopcion (adopt / adapt / discard)

| Patron | Fuente | Decision | Motivo |
|---|---|---|---|
| Superficies oscuras por capas tonales | Obsidian Prism | **ADOPT** | Ya es compatible con SOTA v1 y fortalece jerarquia visual |
| CTA gradiente primario + glow suave | Obsidian Prism + Showcase | **ADAPT** | Mantener identidad Gano y contraste WCAG |
| Pulse indicator / live badge | Obsidian Prism | **ADOPT** | Encaja con narrativa de infraestructura viva |
| Hero con figura tecnica central (prism/constellation) | Obsidian Prism | **ADAPT** | Reusar lenguaje Constellation sin saturar performance |
| Tailwind CDN en runtime | Obsidian Prism | **DISCARD** | Regla del proyecto: no Tailwind para codigo nuevo |
| Inline styles por componente | Showcase | **DISCARD** | Aumenta drift y deuda visual |
| Claims absolutos no auditados | Showcase | **DISCARD** | Riesgo de confianza/SEO/legal |

## 3) Problemas detectados y solucion propuesta

| Problema | Impacto | Solucion | Artefacto |
|---|---|---|---|
| Paleta dual sin puente formal (brand tokens vs kinetic palette) | Inconsistencia entre paginas | Crear capa de convergencia tokenizada para secciones premium | `css/gano-sota-convergence.css` |
| Deriva de componentes entre Elementor y templates PHP | Experiencia desigual | Definir set canonico de clases interoperables (hero, strip, cards, prism) | `elementor-home-classes.md` + CSS nuevo |
| Mensajeria con tonos mixtos (marketing vs tecnico) | Marca menos creible | Establecer guion de voz por seccion y tipo de CTA | este plan + code pack |
| Riesgo de sobre-animacion | Impacto en CWV y accesibilidad | Motion con guardrails + `prefers-reduced-motion` obligatorio | CSS nuevo |
| Sin kit multimedia ejecutable para produccion | Bloqueos en implementacion de contenido | Crear kit de insumos multimedia (inventario + shotlist + specs) | `sota-multimedia-production-kit-2026-04.md` |

## 4) Arquitectura conceptual de convergencia

### Capa A — Fundacion visual

- Tokens base existentes (`--gano-*`) siguen siendo la fuente de verdad.
- Se agrega una subcapa "Kinetic Convergence" para secciones premium:
  - superficies nocturnas,
  - ghost borders,
  - gradientes primarios,
  - brillo ambiental controlado.

### Capa B — Componentes canonicos (portables)

1. `gano-km-shell` (envoltorio de seccion premium)
2. `gano-km-hero` (hero articulador)
3. `gano-km-metrics` (franja de metricas)
4. `gano-km-value-grid` + `gano-km-card` (propuesta de valor)
5. `gano-km-prism` (contenedor visual para pieza insignia)
6. `gano-km-live-badge` (micro-senal operativa)

### Capa C — Narrativa y mensajes

- Eje de mensaje:
  1) soberania digital verificable,
  2) rendimiento medible,
  3) seguridad por defecto,
  4) soporte local experto.
- Regla editorial:
  - evitar hiperboles tecnicas sin evidencia,
  - CTA primario orientado a conversion comercial real (Reseller),
  - CTA secundario orientado a contacto consultivo.

### Capa D — Operacion y despliegue

- Elementor: aplicacion de clases canonicas en home y bloques comerciales.
- Templates PHP: adopcion gradual en `shop-premium`, `page-ecosistemas`, `page-sota-hub`.
- QA: visual (desktop/mobile), a11y de foco, smoke comercial RCC.

## 5) Plan de ejecucion por fases

### Fase 0 — Baseline y freeze visual corto

- Congelar nuevas variaciones de estilo fuera de clases canonicas.
- Revisar paginas con mas drift y mapear deuda visual.

### Fase 1 — Convergencia de fundacion (este sprint)

- Integrar stylesheet de convergencia.
- Publicar code pack reusable para Elementor y templates.
- Publicar kit multimedia para produccion de assets.

### Fase 2 — Home + Ecosistemas + Shop (frente comercial)

- Hero convergente + franja de metricas + valor diferencial.
- Normalizacion de CTA y mensajes por estado comercial (`active|pending|coming-soon`).

### Fase 3 — Contenido SOTA + confianza

- Unificar narrativa de pilares, hub y bloques de innovacion.
- Aplicar reglas de "claim verificable" y "prueba operativa".

### Fase 4 — QA integral y hardening UX

- Validacion WCAG (foco, contraste, teclado),
- validacion performance (LCP/CLS y animacion),
- validacion comercial (click-path a carrito/checkout).

## 6) Orquestacion de agentes (oleadas sugeridas)

1. **Oleada V1 (theme/css):** aplicar clases de convergencia en templates clave.
2. **Oleada V2 (content):** microcopy y claims auditables por bloque.
3. **Oleada V3 (qa):** checklist visual/a11y/performance y correcciones.
4. **Oleada V4 (comercial):** verificacion de CTA Reseller + estados.

Regla: cada oleada cierra con evidencia en `memory/` y sin introducir Tailwind en runtime.

## 7) KPIs de terminado coherente

- Coherencia visual entre Home, Ecosistemas, Shop y SOTA Hub >= 90% (checklist interno).
- 0 estilos inline nuevos en templates del rollout.
- 100% de CTAs comerciales en rutas validas (RCC/Reseller/contacto segun estado).
- 100% de componentes premium respetan `prefers-reduced-motion`.

## 8) Entregables vinculados de este trabajo

- Plan maestro: este archivo.
- Codigo articulador: `wp-content/themes/gano-child/css/gano-sota-convergence.css`.
- Guion de implementacion: `memory/content/sota-convergence-code-pack-2026-04.md`.
- Insumos multimedia: `memory/content/sota-multimedia-production-kit-2026-04.md`.
