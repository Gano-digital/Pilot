---
name: gano-elementor-flexbox-migration
description: >
  Skill de migración de arquitectura Elementor: reemplazo de legacy Section/Column
  nesting por Flexbox Container. SOTA 2026 identifica 30-40% reducción de DOM nodes,
  impactando positivamente LCP, INP y CLS. Usa cuando necesites optimizar performance
  de hero section, secciones principales o layouts complejos, o cuando el usuario
  mencione "Elementor performance", "Flexbox", "DOM reduction", "hero optimization".
---

# Gano Elementor Flexbox Migration — Arquitectura de Alto Rendimiento

Skill para implementar la arquitectura Flexbox Container de Elementor 2026, reduciendo
DOM bloat y mejorando Core Web Vitals.

---

## Contexto SOTA 2026

### El Problema: Legacy Section Architecture
```
Old (Lento):
Page
└── Container (legacy)
    ├── Section
    │   └── Column
    │       ├── Column
    │       │   └── Widget
    │       └── Column
    │           └── Widget
    └── Section ...más nesting...

DOM nodes: 50+ (parsing lento)
CSS payload: 25-40 KB (inline + computed styles)
LCP impact: 2.8-3.5s
```

### La Solución: Flexbox Container
```
New (Rápido):
Page
└── Container (Flexbox)
    ├── Div (flex item)
    │   └── Widget
    ├── Div (flex item)
    │   └── Widget
    └── Div (flex item)
        └── Widget

DOM nodes: 15-20 (parsing rápido)
CSS payload: 5-8 KB (native flexbox = sin cruft)
LCP impact: 1.6-2.0s
Reducción: 30-40% menos DOM
```

### Impacto en Core Web Vitals
| Métrica | Legacy | Flexbox | Beneficio |
|---------|--------|---------|-----------|
| **LCP** | 2.8-3.5s | 1.6-2.0s | ✅ -40% |
| **INP** | 180-220ms | 80-120ms | ✅ -45% |
| **CLS** | 0.12-0.18 | 0.04-0.08 | ✅ -65% |
| **VSI** (nuevo 2026) | 0.15-0.22 | 0.03-0.07 | ✅ -75% |

---

## Paso 1: Auditoría Estructura Actual (Hero Section)

**Ubicación:** Gano Digital homepage → Hero section

**Inspeccionar en DevTools:**
```bash
# Abrir gano.digital en navegador
# F12 → Elements → Inspector
# Buscar: <section class="elementor-section">

# Contar elementos anidados:
# - ¿Cuántas <div class="elementor-column"?
# - ¿Cuántas <div class="elementor-container"?
# - DOM node count total?

# Herramienta: Lighthouse
# PageSpeed Insights → gano.digital
# Anotar LCP, INP, CLS, VSI (si aplica)
```

---

## Paso 2: Planificación de Migración

### Identificar Widgets Críticos (Hero)
```
Hero section estructura actual:
┌─ Section (legacy)
│  ├─ Column (width: 50%)
│  │  └─ Heading
│  ├─ Column (width: 50%)
│  │  └─ Image
│  └─ Column (full)
│     └─ CTA Buttons
```

### Objetivo: Estructura Flexbox
```
Hero section nueva:
┌─ Container (Flexbox, flex-direction: row)
│  ├─ Div (flex: 1)
│  │  └─ Heading
│  ├─ Div (flex: 1)
│  │  └─ Image
│  └─ Div (flex: 1)
│     └─ CTA Buttons
```

### Checklist Pre-Migración
- [ ] Backup actual hero en Elementor (guardar JSON export)
- [ ] Screenshot screenshot página actual
- [ ] Anotar valores CWV baseline
- [ ] Preparar clases CSS nuevas (si aplica custom styling)

---

## Paso 3: Implementación en Elementor (Diego en wp-admin)

**Acceso:** WordPress admin → Elementor → Edit Homepage

### Crear Container Flexbox
1. **Borrar la Section legacy** (o crear nueva sección limpia)
2. **Agregar Container** (Elementor 3.5+)
3. **Configurar Flexbox:**
   - Layout: Flexbox
   - Direction: Row (para hero lado-a-lado)
   - Align items: Center (vertical centering)
   - Justify content: Space-between

### Migrar Widgets al Container
1. **Heading** → Crear Div → Agregar Heading widget
2. **Image** → Crear Div → Agregar Image widget
3. **Buttons** → Crear Div → Agregar Button widgets

### CSS Personalizado (si aplica)
```css
/* En gano-child/style.css */

/* Container Flexbox Hero */
.elementor-container.hero-flexbox {
  display: flex;
  flex-direction: row;
  gap: 2rem;
  align-items: center;
  justify-content: space-between;
  padding: 4rem 2rem;
}

/* Div items (flexible widths) */
.elementor-container.hero-flexbox > div {
  flex: 1;
  min-width: 250px; /* Prevenir shrink excesivo */
}

/* Responsivo: Stack en mobile */
@media (max-width: 768px) {
  .elementor-container.hero-flexbox {
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .elementor-container.hero-flexbox > div {
    flex: 1 100%;
  }
}
```

---

## Paso 4: Validación Post-Migración

### Core Web Vitals Retest
```bash
# PageSpeed Insights → gano.digital
# Anotar nuevos valores:
✓ LCP: [anotar]
✓ INP: [anotar]
✓ CLS: [anotar]
✓ VSI: [anotar si aplica]

# Meta: todas VERDE (90+) en Lighthouse
```

### DOM Node Count Validation
```bash
# DevTools → Elements
# Verificar reducción:
- Before: X nodes
- After: Y nodes
- Reducción: (X-Y)/X * 100 %
# Target: >25% reducción
```

### Pruebas Funcionales
- [ ] Hero visible y centrado en desktop
- [ ] Imagen responsive (escala bien en tablets/mobile)
- [ ] Buttons clickeables y funcionales
- [ ] Animaciones SOTA aún funcionan (si existen)
- [ ] No quebrantado ningún layout en otras páginas

---

## Paso 5: Propagación a Otras Secciones (Opcional)

Una vez validada hero, propagar Flexbox a:
- **Secciones de características** (3-column grid)
- **FAQ accordion**
- **Pricing cards**
- **Footer widgets**

### Template Reutilizable
```
Container (Flexbox, justify-content: center)
├── Div (flex basis: 30%, min-width: 250px)
│   └── Card widget
├── Div (flex basis: 30%, min-width: 250px)
│   └── Card widget
└── Div (flex basis: 30%, min-width: 250px)
    └── Card widget
```

---

## Herramientas de Soporte

### Lighthouse (Auditoría Local)
```
Chrome DevTools → Lighthouse tab
→ Analyze page load
→ Revisar CWV section
```

### PageSpeed Insights (Auditoría SOTA)
```
URL: https://pagespeed.web.dev/
Ingresa: https://gano.digital
Revisa: "Lab Data" → VSI (nuevo 2026)
```

### DebugBear (Monitoreo Continuo — Opcional)
```
https://www.debugbear.com
Setup: Agregar gano.digital
Beneficio: Alertas automáticas si CWV se degrada post-deploy
```

---

## Checklist Completitud

- [ ] Auditoría estructura hero completada
- [ ] CWV baseline anotada
- [ ] Container Flexbox creado en Elementor
- [ ] Widgets migrados al nuevo container
- [ ] CSS personalizado agregado (si aplica)
- [ ] DOM node count reducido >25%
- [ ] CWV retest completado (todas VERDE)
- [ ] Pruebas funcionales pasadas
- [ ] Deploy a producción validado

---

## Referencias SOTA 2026

- [Elementor Flexbox Container Documentation](https://elementor.com/help/flexbox-container/)
- [Core Web Vitals 2026 Guide](https://developers.google.com/search/docs/appearance/core-web-vitals)
- [Elementor Speed Performance 2026](https://elementor.com/speed-performance/)
- [SOTA Investigation 2026-04](../../memory/research/sota-investigation-2026-04.md) — § 1 Elementor + WordPress Performance
