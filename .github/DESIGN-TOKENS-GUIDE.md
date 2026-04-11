# 🎨 Design Tokens Guide — Gano Digital

> **Para:** Desarrolladores frontend, diseñadores
> **Paleta:** Kinetic Monolith + Wave 3 UX (Fase 3.5 Arcana)
> **Archivo fuente:** `wp-content/themes/gano-child/style.css` (`:root`)

---

## Por Qué Tokens

**Sin tokens:** Cada componente tiene sus propios valores
```css
/* ❌ PROBLEMA: mantenimiento pesado + inconsistencia visual */
.hero-title { color: #0F172A; }
.card-title { color: #0F172A; }  /* Mismo color, diferente valor */
.button { color: #0F1729; }      /* Casi igual — pero diferente */
```

**Con tokens:** Un único "fuente de verdad"
```css
/* ✅ SOLUCIÓN: cambiar un valor = cambiar todo */
:root { --gano-dark: #0F172A; }
.hero-title { color: var(--gano-dark); }
.card-title { color: var(--gano-dark); }
.button { color: var(--gano-dark); }

/* Más tarde: --gano-dark: #1A2842 → cambia en TODO */
```

---

## Estructura de Tokens

### 1️⃣ Colores (Color Tokens)

**Paleta Base:**
```css
:root {
  /* Brand Dark — Hero, text principal */
  --gano-dark:              #0F172A;
  --gano-dark-deep:         #05080C;      /* Nuevo Kinetic */

  /* Whites & Grays */
  --gano-white:             #FFFFFF;
  --gano-gray-900:          #111827;
  --gano-gray-700:          #374151;
  --gano-gray-500:          #6B7280;
  --gano-gray-400:          #9CA3AF;
  --gano-gray-200:          #E5E7EB;
  --gano-gray-100:          #F3F4F6;
  --gano-text-slate:        #94A3B8;      /* Nuevo */

  /* Brand Accent — Purple + Indigo (Kinetic) */
  --gano-purple:            #8B5CF6;
  --gano-purple-soft:       rgba(139, 92, 246, 0.10);
  --gano-purple-glow:       rgba(139, 92, 246, 0.50);
  --gano-indigo:            #6366F1;
  --gano-indigo-soft:       rgba(99, 102, 241, 0.10);
  --gano-border-sota:       rgba(99, 102, 241, 0.20);   /* SOTA borders */

  /* Cards & BG (SOTA dark) */
  --gano-card-sota:         #0B1118;      /* Nuevo */

  /* Alerts & Semantic */
  --gano-success:           #10B981;
  --gano-warning:           #F59E0B;
  --gano-error:             #EF4444;
  --gano-info:              #3B82F6;
}
```

**Usando colores:**
```css
.hero { background: var(--gano-dark); }
.alert-success { color: var(--gano-success); }
.card { background: var(--gano-card-sota); border: 1px solid var(--gano-border-sota); }
```

### 2️⃣ Espaciado (Spacing Tokens)

**Escala 8px base (mobile-first):**
```css
:root {
  --gano-space-0:           0;
  --gano-space-xs:          0.5rem;   /* 8px */
  --gano-space-sm:          1rem;     /* 16px */
  --gano-space-md:          1.5rem;   /* 24px */
  --gano-space-lg:          2rem;     /* 32px */
  --gano-space-xl:          3rem;     /* 48px */
  --gano-space-2xl:         4rem;     /* 64px */
  --gano-space-3xl:         6rem;     /* 96px */
}
```

**Contexto: cuándo usar cada uno**
```
xs (8px)   → gap entre elementos tiny, icon spacing
sm (16px)  → padding standard, button internal
md (24px)  → section padding, component gaps
lg (32px)  → hero padding, large sections
xl (48px)  → hero vertical, hero padding
2xl/3xl    → page sections
```

**Usando espacios:**
```css
.button { padding: var(--gano-space-sm) var(--gano-space-md); }
.section { padding: var(--gano-space-lg) var(--gano-space-md); }
.grid { gap: var(--gano-space-md); }

/* Responsive — override en breakpoints */
@media (min-width: 768px) {
  .section { padding: var(--gano-space-2xl) var(--gano-space-xl); }
}
```

### 3️⃣ Tipografía (Font Tokens)

**Familias:**
```css
:root {
  --gano-font-heading:      'Plus Jakarta Sans', system-ui, sans-serif;
  --gano-font-body:         'Inter', system-ui, sans-serif;
  --gano-font-mono:         'Fira Code', monospace;
}
```

**Tamaños (type scale):**
```css
:root {
  --gano-fs-xs:             0.75rem;    /* 12px */
  --gano-fs-sm:             0.875rem;   /* 14px */
  --gano-fs-base:           1rem;       /* 16px */
  --gano-fs-lg:             1.125rem;   /* 18px */
  --gano-fs-xl:             1.25rem;    /* 20px */
  --gano-fs-2xl:            1.5rem;     /* 24px */
  --gano-fs-3xl:            1.875rem;   /* 30px */
  --gano-fs-4xl:            2.25rem;    /* 36px */
  --gano-fs-display:        3rem;       /* 48px — títulos grandes */
}
```

**Line heights (readability):**
```css
:root {
  --gano-lh-tight:          1.25;       /* Headings */
  --gano-lh-normal:         1.5;        /* Body */
  --gano-lh-relaxed:        1.75;       /* Large text */
}
```

**Font weights:**
```css
:root {
  --gano-fw-regular:        400;
  --gano-fw-medium:         500;
  --gano-fw-semibold:       600;
  --gano-fw-bold:           700;
}
```

**Usando tipografía:**
```css
h1 {
  font-family: var(--gano-font-heading);
  font-size: var(--gano-fs-4xl);
  font-weight: var(--gano-fw-bold);
  line-height: var(--gano-lh-tight);
}

p {
  font-family: var(--gano-font-body);
  font-size: var(--gano-fs-base);
  line-height: var(--gano-lh-normal);
  color: var(--gano-gray-700);
}
```

### 4️⃣ Border Radius (Shape Tokens)

**Escala:**
```css
:root {
  --gano-radius-none:       0;
  --gano-radius-sm:         0.25rem;   /* 4px */
  --gano-radius-md:         0.5rem;    /* 8px */
  --gano-radius-lg:         1rem;      /* 16px */
  --gano-radius-xl:         1.5rem;    /* 24px */
  --gano-radius-pill:       9999px;    /* fully rounded buttons */
}
```

**Contexto:**
```
none       → inputs strict (no rounded)
sm (4px)   → subtle cards, inputs borders
md (8px)   → buttons, badges
lg (16px)  → large cards, containers
xl (24px)  → feature sections
pill       → buttons, avatars, badges
```

**Usando:**
```css
.button { border-radius: var(--gano-radius-md); }
.card { border-radius: var(--gano-radius-lg); }
.avatar { border-radius: var(--gano-radius-pill); }
```

### 5️⃣ Shadows (Elevation Tokens)

**Escala de profundidad:**
```css
:root {
  --gano-shadow-sm:         0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --gano-shadow-md:         0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --gano-shadow-lg:         0 10px 15px -3px rgba(0, 0, 0, 0.1);
  --gano-shadow-xl:         0 20px 25px -5px rgba(0, 0, 0, 0.1);

  /* Glow (Kinetic brand) */
  --gano-glow-purple:       0 0 20px rgba(139, 92, 246, 0.3);
  --gano-glow-indigo:       0 0 15px rgba(99, 102, 241, 0.25);
}
```

**Usando:**
```css
.card { box-shadow: var(--gano-shadow-md); }
.card:hover { box-shadow: var(--gano-shadow-lg); }
.accent-button {
  background: var(--gano-purple);
  box-shadow: var(--gano-glow-purple);
}
```

### 6️⃣ Transitions & Animations

**Duraciones:**
```css
:root {
  --gano-duration-fast:     150ms;
  --gano-duration-normal:   300ms;
  --gano-duration-slow:     500ms;
}

--gano-easing-ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
--gano-easing-ease-out:    cubic-bezier(0, 0, 0.2, 1);
```

**Usando:**
```css
button {
  transition: background-color var(--gano-duration-normal) var(--gano-easing-ease-in-out);
}
```

---

## Flujo: Nuevo Componente

### ✅ Proceso Correcto

**1. Diseña en Figma/AdobeXD**
- Define colores, espacios, tipografía
- Anota los valores exactos

**2. Busca en `:root`**
```bash
# En editor, busca en style.css
Ctrl+F "--gano-" → ¿existe el color que necesitas?
```

**3. Si existe, úsalo:**
```css
.new-component {
  background: var(--gano-card-sota);
  padding: var(--gano-space-md);
  border-radius: var(--gano-radius-lg);
  color: var(--gano-dark);
}
```

**4. Si NO existe:**
- ❌ **NO hardcodees** el valor
- ✅ **Abre un issue** o **PR** con:
  - El valor que necesitas
  - Por qué no encaja en la paleta actual
  - Diego o el equipo decidirá si añadir token nuevo

---

## Casos Reales — Antes/Después

### Caso 1: Producto Badge

**ANTES (hardcoded):**
```css
.gano-product-badge {
  background: white;
  color: #000;
  padding: 4px 8px;
  border-radius: 50px;
  font-size: 11px;
  border: 1px solid #e5e7eb;
}
```

**DESPUÉS (tokens):**
```css
.gano-product-badge {
  background: var(--gano-white);
  color: var(--gano-dark);
  padding: var(--gano-space-xs) var(--gano-space-sm);
  border-radius: var(--gano-radius-pill);
  font-size: var(--gano-fs-xs);
  border: 1px solid var(--gano-gray-200);
}
```

**Ventaja:** Si luego la paleta cambia a tema oscuro, todo se actualiza.

### Caso 2: Form Error

**ANTES:**
```css
.gano-form-notice--error {
  background: #FEE;
  color: #C00;
  border: 1px solid #F00;
  padding: 12px 16px;
  border-radius: 8px;
}
```

**DESPUÉS:**
```css
.gano-form-notice--error {
  background: rgba(239, 68, 68, 0.1);  /* error color softened */
  color: var(--gano-error);
  border: 1px solid var(--gano-error);
  padding: var(--gano-space-sm) var(--gano-space-md);
  border-radius: var(--gano-radius-md);
}
```

---

## DevTools — Debug Tokens

### Chrome / Firefox DevTools

**Opción 1: Inspeccionar elemento**
```
1. Abre DevTools (F12)
2. Selecciona elemento
3. Panel "Styles" → busca "var(--gano-*)"
4. Hover sobre variable → ve valor computado
```

**Opción 2: Ver todos los tokens**
```javascript
// En Console
getComputedStyle(document.documentElement)
// O filtrar:
Array.from(getComputedStyle(document.documentElement).getPropertyNames())
  .filter(p => p.includes('gano'))
  .forEach(p => console.log(p, '=', getComputedStyle(document.documentElement).getPropertyValue(p)))
```

### Verificar cambios locales
```bash
# En Laragon
1. Edita wp-content/themes/gano-child/style.css
2. Recarga navegador (sin cache: Ctrl+Shift+R)
3. DevTools → busca tu token
```

---

## Wave 3 — Nuevos Tokens (Esqueletos + Success)

**Skeleton loaders (animaciones de carga):**
```css
.gano-skeleton {
  background: linear-gradient(
    90deg,
    var(--gano-gray-200),
    var(--gano-gray-100),
    var(--gano-gray-200)
  );
  background-size: 200% 100%;
  animation: gano-skeleton-shine var(--gano-duration-slow) infinite;
}

@keyframes gano-skeleton-shine {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* Tamaños estándar */
.gano-skeleton--text { height: var(--gano-space-sm); }
.gano-skeleton--card { height: 200px; }
.gano-skeleton--avatar { width: 48px; height: 48px; border-radius: var(--gano-radius-pill); }
```

**Form success (complementa form error):**
```css
.gano-form-notice--success {
  background: rgba(16, 185, 129, 0.1);  /* success softened */
  color: var(--gano-success);
  border: 1px solid var(--gano-success);
  padding: var(--gano-space-sm) var(--gano-space-md);
  border-radius: var(--gano-radius-md);
}
```

---

## Checklist para PR

**Antes de hacer commit:**

- [ ] Busqué el token necesario en `:root`
- [ ] NO hardcodeé colores, espacios, fuentes
- [ ] Usé `var(--gano-*)` consistentemente
- [ ] Probé en local (Laragon) y se ve igual
- [ ] DevTools muestra valores correctos
- [ ] Responsive breakpoints usan tokens
- [ ] Si añadí token nuevo, expliqué por qué en PR

**En el descripción de PR:**
```markdown
## Cambios CSS

- Movidas 15 propiedades a tokens
- Nuevos componentes:
  - `.gano-form-notice--success`
  - `.gano-skeleton--avatar`
- Tokens consultados:
  - --gano-space-md (padding estándar)
  - --gano-radius-lg (large cards)

## Tokens creados (si aplica)
- Ninguno — solo usé existentes
```

---

## FAQs

**P: ¿Puedo cambiar un token?**
R: No — son "fuentes de verdad". Si necesitas otro valor, abre issue con Diego.

**P: ¿Qué hago si el token no existe?**
R: Abre PR con descripción clara + justificación. Diego revisará.

**P: ¿Los tokens aplican a WooCommerce?**
R: Parcialmente — WooCommerce tiene su propio CSS. Usa tokens en `gano-child/` siempre que puedas.

**P: ¿Cómo afecta esto a Elementor?**
R: Elementor **no respeta** tokens CSS (usa valores duros). Hablamos por PR si Elementor necesita un componente.

**P: Necesito un gradiente/shadow personalizado.**
R: Abre issue — podría convertirse en token si es reutilizable.

---

## Referencias

- **Archivo fuente:** `wp-content/themes/gano-child/style.css` (líneas 1–300)
- **Guía de onboarding:** `SERGIO-ONBOARDING.md`
- **Paleta Figma:** [Enlace a Figma compartido — pedir a Diego]
- **Atomic Design:** Usa los tokens en orden: colores → tipografía → espacios → radios

---

**Última actualización:** 2026-04-09 (Sergio Arias integration — Wave 3 UX)
**Mantenedor:** Diego + Claude Code

