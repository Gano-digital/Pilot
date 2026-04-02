# Elementor — Guía de arquitectura (Oleada 3)

**Tema activo:** `gano-child` (padre: `royal-elementor-kit`; el handle `hello-elementor-parent-style` en `functions.php` es legacy — el URI es correcto)  
**Referencia de clases:** [`elementor-home-classes.md`](elementor-home-classes.md)  
**Brief fuente:** [`../research/gano-wave3-brand-ux-master-brief.md §8`](../research/gano-wave3-brand-ux-master-brief.md)

---

## 1. Regla fundamental — 1 contenedor = 1 intención

Cada contenedor (Flexbox o Grid) de Elementor debe resolver **una sola tarea de comunicación o layout**. Anidar varias secciones de distinta intención dentro de un mismo contenedor genera conflictos de z-index, dificulta el mantenimiento y rompe la coherencia visual en móvil.

| ✅ Correcto | ❌ Evitar |
|------------|----------|
| Contenedor `hero-wrapper` → solo el bloque hero (imagen + copy + CTA) | Un contenedor `page-outer` que engloba hero, pilares y métricas |
| Contenedor `pillars-grid` → solo la fila de tarjetas de pilares | Secciones de conversión anidadas dentro de una sección decorativa |
| Contenedor `social-proof-strip` → solo logotipos o testimonios | CTA principal y CTA secundario compartiendo el mismo contenedor hijo |

### Reglas derivadas

1. **Profundidad máxima:** 3 niveles de anidación (contenedor → columna → widget). Más de 3 niveles es señal de refactorización necesaria.
2. **Nombre de clase obligatorio:** todo contenedor con lógica visual propia recibe una clase `gano-el-*` (ver §4). Contenedores puramente estructurales pueden omitir clase personalizada.
3. **Separación de responsabilidades:**
   - Espaciado entre secciones → `padding-top`/`padding-bottom` del **contenedor padre**, no del widget interior.
   - Color de fondo → contenedor de sección; nunca en columna a menos que sea intencional (ej. tarjeta con fondo propio).

---

## 2. Orden canónico de plantillas en Theme Builder

Elementor Theme Builder gestiona las plantillas globales. El orden recomendado para Gano Digital es:

```
Theme Builder
├── Header          ← Una sola plantilla "Gano Header v1"
│   └── Condición: Entire Site
├── Footer          ← Una sola plantilla "Gano Footer v1"
│   └── Condición: Entire Site
├── 404             ← Plantilla "Gano 404"
│   └── Condición: 404 Page
└── Single Post     ← Plantilla "Gano SOTA Single" (usa sota-single-template.php)
    └── Condición: Post Type → Post
```

### Reglas de precedencia

- **Header y Footer:** `Entire Site` garantiza consistencia. Si en el futuro una landing necesita header distinto, **crear una nueva plantilla** con condición específica en lugar de modificar la global.
- **404:** plantilla dedicada evita que el header/footer de producción quede desactualizado ante cambios de la 404. Incluir CTA de regreso a home y buscador.
- **Conflictos de condición:** cuando dos plantillas del mismo tipo tienen condiciones solapadas, la más específica tiene prioridad. Documentar el solapamiento en este archivo.

---

## 3. Cuándo usar plantilla home dedicada

### Usar plantilla dedicada en Theme Builder cuando…

| Condición | Justificación |
|-----------|---------------|
| La home tiene hero con animaciones GSAP o fondo de video | Evita que esos scripts afecten al resto del sitio |
| Necesitas un header transparente (over-hero) **solo en home** | Una condición `Front Page` en el Header la resuelve sin duplicar lógica |
| El layout de home cambia con frecuencia (A/B, temporadas) | La plantilla se puede duplicar, editar y activar sin tocar la página real |
| La home consume widgets de Royal Elementor Addons no usados en ninguna otra página | Separar reduce carga de scripts en páginas interiores |

### Mantener la home como página de WordPress normal cuando…

| Condición | Justificación |
|-----------|---------------|
| La complejidad es baja y el equipo no tiene Elementor Pro activo | Theme Builder requiere Pro |
| Hay solo 1-2 secciones con lógica especial | Más simple gestionar overrides en CSS con clase body `home` |
| El equipo prefiere ver la home en la lista de páginas de WP Admin | La plantilla Theme Builder no aparece en Páginas → dificulta la colaboración no técnica |

### Configuración recomendada para Gano Digital (actual)

```
Elementor Pro → Theme Builder → Single Page
  Nombre:    "Gano Home v1"
  Condición: Front Page
  CSS Class: body.elementor-page-{ID} .gano-el-home-root
```

Asignar la clase `gano-el-home-root` al contenedor raíz de la plantilla para que los overrides CSS queden aislados a esa vista.

---

## 4. Clases `gano-el-*` — referencia rápida y mapa de intención

> Referencia completa → [`elementor-home-classes.md`](elementor-home-classes.md)

| Clase | Contenedor / elemento | Intención |
|-------|-----------------------|-----------|
| `gano-el-home-root` | Contenedor raíz de la plantilla home | Aísla overrides específicos de home |
| `gano-el-stack` | Contenedor padre del Hero | Gestiona z-index entre capas de imagen y copy |
| `gano-el-layer-top` | Columna de copy (texto + CTA) | Z-index elevado; legibilidad sobre fondo |
| `gano-el-layer-base` | Columna de imagen / shape decorativo | Z-index base; puede tener `overflow: hidden` |
| `gano-el-hero-readability` | Contenedor del título H1 | Semi-glass o sombra de texto para contraste WCAG AA |
| `gano-el-pillar` | Tarjeta de cada pilar (×4) | Glass ligero, hover lift, tipografía alineada a tokens |
| `gano-el-pillar-grid` | Fila de pilares | Grid 2×2 en móvil → 4×1 en desktop |
| `gano-el-prose-narrow` | Contenedor de texto largo | Limita ancho a ~65ch; centra; margen automático |
| `gano-el-metrics` | Fila de métricas / estadísticas | Separador visual + tipografía display |
| `gano-el-metric` | Celda individual de métrica | Número grande + descripción pequeña |
| `gano-el-cta-icons` | Fila de íconos + CTA final | Iconos Elementor/FA; sin ★ literales |
| `gano-el-social-proof` | Franja de logotipos o testimonio | Overflow scroll en móvil si más de 4 logos |
| `gano-el-header` | Contenedor global del Header | Pegajoso; blur de fondo en scroll |
| `gano-el-footer` | Contenedor global del Footer | Grid de columnas 4→2→1 |
| `gano-el-404` | Contenedor de página 404 | Centrado; CTA a home + buscador |

### Cómo agregar una clase nueva

1. Definir en este archivo con nombre, contenedor destino e intención.
2. Agregar la regla CSS en `gano-child/style.css` bajo la sección `/* === Utilidades Elementor gano-el-* === */`.
3. Si la clase tiene motion, verificar que respeta `prefers-reduced-motion: reduce`.
4. Referenciar desde [`elementor-home-classes.md`](elementor-home-classes.md) si la clase aplica a la home.

---

## 5. Kit Global de Elementor — uso correcto

El **Kit Global** (`Elementor → Configuración del sitio`) almacena colores y tipografías que se propagan a todos los widgets. Reglas para Gano Digital:

### Colores globales (kit)

| Token CSS (`gano-child`) | Color Hex | Nombre en kit Elementor |
|--------------------------|-----------|-------------------------|
| `--gano-blue` | `#1B4FD8` | Gano Blue |
| `--gano-green` | `#00C26B` | Gano Green |
| `--gano-orange` | `#FF6B35` | Gano Orange (CTA) |
| `--gano-gold` | `#D4AF37` | Gano Gold |
| `--gano-dark` | `#0F1923` | Gano Dark BG |

> **Regla:** Si un color existe en el kit, **no sobreescribir localmente** en el widget. Si necesitas una variante puntual (ej. versión clara de `--gano-blue`), crearla como nuevo token en `style.css` y agregarla al kit.

### Tipografías globales (kit)

| Rol | Fuente | Peso |
|-----|--------|------|
| Display / H1 | Plus Jakarta Sans | 700 |
| Heading / H2–H3 | Plus Jakarta Sans | 600 |
| Body | Inter | 400 |
| Caption / small | Inter | 400 italic o 500 |

> **Regla:** No usar `Elementor Default` como fuente en ningún widget visible. Asignar siempre un rol tipográfico del kit.

---

## 6. Checklist de revisión antes de publicar una plantilla

- [ ] Cada contenedor tiene una sola intención documentada o su clase `gano-el-*`.
- [ ] Profundidad de anidación ≤ 3 niveles.
- [ ] Sin colores locales que dupliquen los del kit global.
- [ ] Header, Footer y 404 asignados como plantillas de Theme Builder con condición correcta.
- [ ] Home usa `gano-el-home-root` en el contenedor raíz.
- [ ] Clases `gano-el-layer-top` / `gano-el-layer-base` aplicadas en la sección Hero si hay superposición.
- [ ] Tipografía: todos los widgets usan roles del kit global (no "Elementor Default").
- [ ] Motion: animaciones de entrada (Elementor Entrance) o GSAP tienen fallback `prefers-reduced-motion`.
- [ ] Móvil revisado en 375 px y 390 px: sin overflow horizontal, CTA visible en primer pantallazo.
- [ ] Caché vaciada (plugin/hosting) tras deploy de `style.css` o `functions.php`.

---

## 7. Resolución de conflictos frecuentes

| Síntoma | Causa probable | Solución |
|---------|---------------|----------|
| Hero: texto tapado por imagen | Falta `gano-el-layer-top` en columna de copy | Agregar clase; revisar `z-index` en `style.css` |
| Menú no aparece en header Elementor | Location `primary` no asignada en Apariencia → Menús | Asignar menú a "Menú principal (header / Elementor)" |
| Footer duplicado | Plantilla Footer con condición `Entire Site` + footer nativo de WordPress activo | Desactivar footer nativo desde el Kit o con `remove_action( 'wp_footer', ... )` |
| 404 muestra layout de home | Condición de plantilla home demasiado amplia | Cambiar condición de home a `Front Page` (no `All Pages`) |
| Estilos de `gano-el-*` no se aplican | Caché de hosting/CDN sin vaciar | Vaciar caché; forzar version-bust en `wp_enqueue_style` |
| Glassmorphism roto en Safari | `backdrop-filter` requiere `-webkit-backdrop-filter` | Agregar prefijo vendor en `style.css` |

---

_Última actualización: Abril 2026 — Oleada 3._  
_Ver también: [`elementor-home-classes.md`](elementor-home-classes.md) · [`gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md)_
