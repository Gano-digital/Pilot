# Gano Digital — Especificación de activos raster (Oleada 3)

**Versión:** 1.0 — Abril 2026  
**Referencia:** Brief §7 (`memory/research/gano-wave3-brand-ux-master-brief.md`)  
**LCP / preload:** ver [`gano-seo.php` → función `gano_resource_hints()`](../../wp-content/mu-plugins/gano-seo.php#L351)

---

## 1. Formato obligatorio

| Requisito | Valor |
|-----------|-------|
| **Formato principal** | WebP |
| **Fallback** | JPEG (mismo nombre, extensión `.jpg`) — solo si el CMS/cliente no soporta WebP |
| **Paleta de color** | sRGB (no Wide Gamut) para compatibilidad universal |
| **Espacio de color** | sRGB |
| **Compresión WebP** | calidad 80–85 (sin pérdida visible; balance peso/fidelidad) |
| **Metadatos** | Eliminar EXIF/ICC en producción (usar `cwebp -metadata none`) |

---

## 2. Convención de nombres

```
gano-{sección}-{variante}[-{breakpoint}].webp
```

| Parte | Descripción | Ejemplos válidos |
|-------|-------------|-----------------|
| `gano-` | Prefijo obligatorio de marca | — |
| `{sección}` | Área temática del asset | `hero`, `pilar`, `og`, `logo`, `card`, `bg` |
| `{variante}` | Identificador semántico único | `infraestructura`, `seguridad`, `ia`, `soberania`, `nvme` |
| `[-{breakpoint}]` | Opcional: sufijo si hay versión mobile | `-mobile` |

**Ejemplos concretos:**

```
gano-hero-soberania.webp            ← hero principal home
gano-hero-soberania-mobile.webp     ← hero versión móvil (< 768 px)
gano-pilar-infraestructura.webp     ← imagen de pilar "Infraestructura NVMe"
gano-pilar-seguridad.webp           ← imagen de pilar "Zero-Trust"
gano-pilar-ia.webp                  ← imagen de pilar "Gestión IA"
gano-pilar-soberania.webp           ← imagen de pilar "Soberanía LATAM"
gano-og-home.webp                   ← Open Graph imagen homepage
gano-og-ecosistemas.webp            ← Open Graph página ecosistemas/shop
gano-og-default.webp                ← OG fallback para páginas sin imagen propia
```

> **Regla:** usar guiones (`-`) como separadores; **sin espacios, mayúsculas ni caracteres especiales**. El prefijo `gano-` permite identificar rápidamente activos propios en la media library de WordPress.

---

## 3. Dimensiones recomendadas y atributos HTML

### 3.1 Hero (imagen principal / LCP)

| Atributo | Valor |
|----------|-------|
| **Ancho canónico** | 1440 px |
| **Alto canónico** | 720 px |
| **Aspect ratio** | 2:1 |
| **Versión mobile** | 768 × 540 px (4:3, recorte centrado) |
| **Peso objetivo** | ≤ 120 KB (desktop), ≤ 60 KB (mobile) |
| **`width` HTML** | `1440` |
| **`height` HTML** | `720` |
| **`loading`** | *omitir* (o `eager`) — el hero es LCP |
| **`fetchpriority`** | `"high"` |
| **`decoding`** | `"sync"` (evitar retraso de decode en LCP) |

**Markup de referencia (Elementor custom HTML o `functions.php`):**

```html
<img
  src="https://gano.digital/wp-content/uploads/gano-hero-soberania.webp"
  width="1440"
  height="720"
  alt="Hosting WordPress soberano para Colombia — Gano Digital"
  fetchpriority="high"
  decoding="sync"
  loading="eager"
>
```

> **Preload LCP vía MU plugin:** el campo `gano_seo_hero_image` en
> **wp-admin → Ajustes → Gano SEO** genera automáticamente:
>
> ```html
> <link rel="preload" as="image"
>       href="{URL_hero}" fetchpriority="high">
> ```
>
> Código en [`gano-seo.php` líneas 351–355](../../wp-content/mu-plugins/gano-seo.php).  
> Configurar la URL del hero WebP en ese campo para activar el preload.

---

### 3.2 Pilares (cards de ecosistemas / blog)

| Atributo | Valor |
|----------|-------|
| **Ancho canónico** | 800 px |
| **Alto canónico** | 450 px |
| **Aspect ratio** | 16:9 |
| **Peso objetivo** | ≤ 60 KB |
| **`width` HTML** | `800` |
| **`height` HTML** | `450` |
| **`loading`** | `"lazy"` (están below-the-fold) |
| **`fetchpriority`** | *omitir* (browser default) |
| **`decoding`** | `"async"` |

**Markup de referencia:**

```html
<img
  src="https://gano.digital/wp-content/uploads/gano-pilar-infraestructura.webp"
  width="800"
  height="450"
  alt="Infraestructura NVMe de alta velocidad"
  loading="lazy"
  decoding="async"
>
```

> **Nota CLS:** los atributos `width` y `height` deben coincidir con el aspect ratio real del archivo (2:1 o 16:9). Si Elementor recorta la imagen vía CSS, mantener igualmente los atributos en el `<img>` para que el navegador reserve el espacio antes de cargar.

---

### 3.3 Open Graph (OG images)

| Atributo | Valor |
|----------|-------|
| **Ancho** | 1200 px |
| **Alto** | 630 px |
| **Aspect ratio** | ~1.91:1 (estándar Facebook/LinkedIn/WhatsApp) |
| **Peso objetivo** | ≤ 150 KB |
| **Formato alternativo** | JPEG también válido (WhatsApp no soporta WebP OG en todos los clientes) |
| **`og:image:width`** | `1200` |
| **`og:image:height`** | `630` |

**Meta tags de referencia:**

```html
<meta property="og:image"        content="https://gano.digital/wp-content/uploads/gano-og-home.webp">
<meta property="og:image:type"   content="image/webp">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"    content="Gano Digital — Hosting WordPress soberano en Colombia">
```

> **OG en producción:** Rank Math gestiona las meta OG cuando está activo (el plugin MU `gano-seo.php` delega en él para evitar duplicados). Configurar la imagen OG en **Rank Math → General Settings → Social** o en el editor de cada página.  
> El fallback del MU plugin (función `gano_og_fallback()`, líneas 363–390) cubre páginas sin Rank Math.

---

## 4. Resumen de atributos por tipo

| Tipo | `width` | `height` | `loading` | `fetchpriority` | `decoding` |
|------|---------|----------|-----------|-----------------|------------|
| Hero LCP | `1440` | `720` | `eager` | `high` | `sync` |
| Pilar / card | `800` | `450` | `lazy` | — | `async` |
| OG image | — | — | N/A (no en `<img>`) | N/A | N/A |

---

## 5. Checklist de entrega para cada asset

- [ ] Formato WebP exportado desde Figma / Photoshop / `cwebp`
- [ ] Nombre sigue la convención `gano-{sección}-{variante}.webp`
- [ ] Peso dentro del límite de la tabla correspondiente
- [ ] Dimensiones exactas (sin upscaling)
- [ ] Metadatos EXIF eliminados
- [ ] Versión mobile generada para el hero (si aplica)
- [ ] URL del hero configurada en **wp-admin → Ajustes → Gano SEO → URL Hero (LCP)**
- [ ] OG image asignada en Rank Math (o fallback activo en MU plugin)
- [ ] Atributos `width`, `height` añadidos en el markup o en el widget de Elementor

---

## 6. Herramientas recomendadas

| Herramienta | Uso |
|-------------|-----|
| `cwebp` (CLI) | Conversión y compresión WebP batch |
| Squoosh (web) | Ajuste visual de calidad / peso |
| ImageOptim / TinyPNG | Compresión rápida antes de subir |
| Rank Math | Gestión de OG tags por página |
| **wp-admin → Ajustes → Gano SEO** | URL del hero para preload LCP ([gano-seo.php](../../wp-content/mu-plugins/gano-seo.php)) |

---

_Fin de especificación de activos raster — Oleada 3._
