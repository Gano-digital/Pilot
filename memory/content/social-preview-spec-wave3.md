# Guía de Previews Sociales — Gano Digital Wave 3
> Especificación de OG images y Twitter Cards para gano.digital  
> Última actualización: 2026-04  
> Contexto técnico: Rank Math (gestor principal) + `gano-seo.php` (fallback MU plugin)

---

## 1. Stack de etiquetas OG/Twitter en el sitio

| Capa | Responsable | Activa si… |
|------|-------------|------------|
| **Rank Math** (prioridad) | Plugin SEO activo | Rank Math está instalado y activo |
| **`gano-seo.php` fallback** | `gano_og_fallback()` hook `wp_head` prio 3 | Rank Math no genera la etiqueta o está inactivo |

El fallback emite `og:type`, `og:title`, `og:description`, `og:url`, `og:image`, `og:site_name`, `og:locale` (→ `es_CO`) y Twitter card tipo `summary_large_image`.

---

## 2. Dimensiones estándar de imágenes OG

### 2.1 Open Graph (Facebook, LinkedIn, WhatsApp, Telegram…)

| Parámetro | Valor recomendado | Mínimo aceptable |
|-----------|-------------------|-----------------|
| **Ancho** | **1 200 px** | 600 px |
| **Alto** | **630 px** | 315 px |
| **Relación de aspecto** | **1.91 : 1** | — |
| **Formato** | WebP + fallback JPG | PNG aceptado |
| **Peso máximo** | ≤ 1 MB (ideal ≤ 400 KB) | 8 MB límite Facebook |
| **Color de fondo** | sólido (no transparencia) | — |

> **Por qué 1 200 × 630:** es el tamaño que evita recorte en Facebook/LinkedIn y activa la vista expandida en lugar del thumbnail pequeño.

### 2.2 Twitter / X Card (`summary_large_image`)

| Parámetro | Valor recomendado | Mínimo aceptable |
|-----------|-------------------|-----------------|
| **Ancho** | **1 200 px** | 300 px |
| **Alto** | **628 px** (≈ 1 200 × 628) | 157 px |
| **Relación de aspecto** | **1.91 : 1** | ≥ 1 : 1 |
| **Formato** | WebP + fallback JPG | PNG/GIF aceptado |
| **Peso máximo** | ≤ 5 MB | — |

> Una sola imagen de **1 200 × 630 px** sirve simultáneamente para OG y Twitter. Diseñar sobre ese canvas unifica el proceso.

### 2.3 WhatsApp (previsualización de links)

WhatsApp usa las etiquetas OG. Lee `og:image`. Con 1 200 × 630 px la imagen se muestra completa; con dimensiones menores solo aparece el título. Sin ajuste extra requerido.

---

## 3. Zona de texto seguro en imagen (Safe Zone)

```
┌──────────────────────────────────────────────────────────────┐
│  1 200 px                                                     │
│  ┌────────────────────────────────────────────────────────┐  │
│  │                                                        │  │
│  │   ← 100 px →                              ← 100 px → │  │
│  │   ┌──────────────────────────────────────────────┐    │  │
│  │   │                                              │  ↑ │  │
│  │   │          ZONA SEGURA DE TEXTO               │ 100 │  │
│  │   │          (1 000 × 430 px)                   │  px │  │
│  │   │                                              │  ↓ │  │
│  │   └──────────────────────────────────────────────┘    │  │
│  │                                                        │  │
│  └────────────────────────────────────────────────────────┘  │
│  630 px                                                       │
└──────────────────────────────────────────────────────────────┘
```

| Dimensión | Canvas total | Zona segura | Margen |
|-----------|-------------|-------------|--------|
| Ancho | 1 200 px | 1 000 px | 100 px c/lado |
| Alto | 630 px | 430 px | 100 px c/lado |

**Reglas para texto dentro de la zona segura:**

- Título (`H1` visual): tipografía `Plus Jakarta Sans Bold`, tamaño ≥ 48 px equivalente a 300 dpi.
- Bajada/subtítulo: tipografía `Inter Regular`, tamaño ≥ 28 px.
- Contraste mínimo: ratio **4.5:1** sobre el fondo (WCAG AA). Usar overlay semitransparente si la imagen de fondo es compleja.
- No ubicar elementos críticos (logo, CTA, título) a menos de **80 px** de cualquier borde; algunos reproductores recortan el 5–8 % de los bordes.
- Logo Gano Digital: posición recomendada esquina inferior-izquierda de la zona segura, alto fijo 48 px.

---

## 4. Sistema de diseño para imágenes OG

### 4.1 Paleta de color autorizada (variables CSS del sitio)

| Token | Hex | Uso en OG image |
|-------|-----|-----------------|
| `--gano-dark` | `#0F1923` | Fondo base (fondos nocturnos) |
| `--gano-blue` | `#1B4FD8` | Fondo alternativo / acento corporativo |
| `--gano-green` | `#00C26B` | Overlay / acento SOTA |
| `--gano-orange` | `#FF6B35` | CTA / badge de urgencia |
| `--gano-gold` | `#D4AF37` | Acento premium (planes SOTA / Kinetic Monolith) |
| Blanco puro | `#FFFFFF` | Texto principal sobre fondos oscuros |

### 4.2 Convención de nomenclatura de archivos

Formato: `gano-{sección}-{variante}-og.webp`

Ejemplos:
- `gano-home-hero-og.webp` — imagen principal de la homepage
- `gano-ecosistemas-nucleo-prime-og.webp` — plan Núcleo Prime
- `gano-ecosistemas-fortaleza-delta-og.webp` — plan Fortaleza Delta
- `gano-ecosistemas-bastion-sota-og.webp` — plan Bastión SOTA
- `gano-sota-nvme-og.webp` — página SOTA "Arquitectura NVMe"
- `gano-blog-default-og.webp` — fallback para posts sin imagen destacada
- `gano-legal-og.webp` — términos, privacidad (imagen genérica)

### 4.3 Ruta de subida en WordPress

```
wp-content/uploads/og-images/
├── gano-home-hero-og.webp
├── gano-blog-default-og.webp
├── gano-legal-og.webp
├── ecosistemas/
│   ├── gano-ecosistemas-nucleo-prime-og.webp
│   ├── gano-ecosistemas-fortaleza-delta-og.webp
│   └── gano-ecosistemas-bastion-sota-og.webp
└── sota/
    ├── gano-sota-nvme-og.webp
    ├── gano-sota-zerotrust-og.webp
    └── … (una por cada una de las 20 páginas SOTA)
```

### 4.4 Capas de plantilla recomendadas (Canva / Figma / Photopea)

1. **Capa base:** fondo `#0F1923` o imagen de infraestructura (sutil, oscurecida con overlay al 60 %).
2. **Capa overlay:** gradiente lineal `rgba(27,79,216,0.55)` → `rgba(15,25,35,0.90)`, dirección 135°.
3. **Capa logo:** logotipo Gano Digital, blanco, alineado a esquina inferior-izquierda (zona segura).
4. **Capa título:** texto principal (máx 2 líneas, ~55 caracteres) centrado o alineado izquierda.
5. **Capa badge** (opcional): pastilla con plan o categoría (`NUCLEO PRIME`, `SOTA`, `SEO`, etc.) usando `--gano-orange` o `--gano-gold`.
6. **Capa glassmorphism** (opcional para planes premium): panel `backdrop-filter: blur(24px)` como visual; renderizarlo como PNG estático para la OG image.

---

## 5. Páginas prioritarias — Plan de producción

### Prioridad 1 — Inmediata (alto impacto comercial)

| Página | Slug/URL | Imagen OG requerida | Descripción |
|--------|----------|---------------------|-------------|
| Homepage | `/` | `gano-home-hero-og.webp` | Hero con propuesta de valor central |
| Ecosistemas / Shop | `/ecosistemas` o `/shop-premium` | `gano-ecosistemas-og.webp` | Catálogo de planes |
| Plan Núcleo Prime | `/ecosistemas/nucleo-prime` | `gano-ecosistemas-nucleo-prime-og.webp` | Detalle plan entrada |
| Plan Fortaleza Delta | `/ecosistemas/fortaleza-delta` | `gano-ecosistemas-fortaleza-delta-og.webp` | Detalle plan avanzado |
| Plan Bastión SOTA | `/ecosistemas/bastion-sota` | `gano-ecosistemas-bastion-sota-og.webp` | Detalle plan premium |

### Prioridad 2 — Corto plazo (SEO y confianza)

| Página | Slug | Imagen OG requerida | Nota |
|--------|------|---------------------|------|
| Blog / Artículos | `/blog` | `gano-blog-default-og.webp` | Fallback genérico + thumbnail por post |
| Hosting WordPress Colombia | `/hosting-wordpress-colombia` | `gano-landing-seo-og.webp` | Landing SEO Fase 3 |
| Sobre nosotros / Manifiesto | `/nosotros` | `gano-nosotros-og.webp` | Narrativa de marca |
| Contacto | `/contacto` | `gano-contacto-og.webp` | Puede reutilizar imagen genérica |

### Prioridad 3 — Wave 3 / SOTA (posicionamiento técnico)

Cada una de las 20 páginas SOTA del plugin `gano-content-importer`. Imagen temática por categoría:

| Categoría | Páginas SOTA incluidas | Imagen base sugerida |
|-----------|----------------------|----------------------|
| **Infraestructura** | NVMe, Edge Computing, HTTP/3, Alta Disponibilidad, Escalamiento Elástico | Fondo azul oscuro + patrón de circuitos |
| **Seguridad** | Zero-Trust, DDoS, Cifrado Post-Cuántico, Hosting Compartido (riesgo), Self-Healing | Fondo verde oscuro + iconografía de escudo |
| **Inteligencia Artificial** | Gestión Predictiva IA, Agente IA, Analytics Server-Side | Fondo degradado azul-morado |
| **Estrategia** | Soberanía Digital LATAM, Muerte del Hosting Compartido, CI/CD | Fondo dorado/naranja oscuro |
| **Rendimiento** | Headless WordPress, Core Web Vitals, Skeleton Screens, Backups Continuos, Micro-Animaciones | Fondo verde + métricas visuales |

> Las 20 imágenes SOTA pueden generarse con una plantilla base + título variable. Peso objetivo: ≤ 150 KB c/u en WebP.

### Páginas que NO requieren imagen OG personalizada (usar fallback genérico)

- Términos y condiciones
- Política de privacidad
- Aviso legal / GDPR
- Página 404
- Resultados de búsqueda

---

## 6. Configuración en Rank Math

### Por página individual (Rank Math → Social)

1. En el editor de la página, abrir el panel **Rank Math → Social**.
2. Pestaña **Facebook**: subir imagen 1 200 × 630 px; rellenar título y descripción personalizados.
3. Pestaña **Twitter**: activar "Usar imagen de Facebook" si la imagen es válida para ambas.
4. Guardar y validar con [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/) y [Twitter Card Validator](https://cards-dev.twitter.com/validator).

### Imagen global de fallback en Rank Math

`Rank Math → General Settings → Open Graph → Default Thumbnail` → apuntar a `gano-home-hero-og.webp`.

Esta imagen se usa cuando una página no tiene imagen OG definida. El MU plugin `gano-seo.php` aplica un segundo fallback programático si Rank Math no emite la etiqueta.

---

## 7. Configuración del fallback en `gano-seo.php`

El fallback actual (función `gano_og_fallback()`) usa:

```php
$image = is_singular()
    ? ( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: $cfg['logo_url'] )
    : $cfg['logo_url'];
```

Para que el fallback use la imagen OG correcta, registrar en `wp-admin → Ajustes → Gano SEO`:

- `gano_seo_logo` → URL del logo en alta resolución (mínimo 512 × 512 px cuadrado).
- `gano_seo_hero_image` → URL de `gano-home-hero-og.webp` (se usa como `og:image` global cuando no hay thumbnail de post).

> **Mejora pendiente (Fase 4):** extender `gano_og_fallback()` para leer una opción `gano_seo_og_default_image` independiente del logo, y usar `og:image:width` / `og:image:height` explícitos.

---

## 8. Validación y control de calidad

### Herramientas de validación

| Herramienta | URL | Valida |
|-------------|-----|--------|
| Facebook Sharing Debugger | https://developers.facebook.com/tools/debug/ | OG tags, imagen, título |
| LinkedIn Post Inspector | https://www.linkedin.com/post-inspector/ | LinkedIn preview |
| Twitter Card Validator | https://cards-dev.twitter.com/validator | Twitter card |
| OpenGraph.xyz | https://www.opengraph.xyz | Vista rápida multi-red |
| Rank Math Schema Validator | Panel de Rank Math | Schema + OG integrado |

### Checklist de QA por página

- [ ] `og:image` responde 200 (no redirección 301 ni 404).
- [ ] Dimensiones reales de la imagen: ≥ 1 200 × 630 px.
- [ ] Peso de la imagen: ≤ 1 MB.
- [ ] `og:title` ≤ 60 caracteres (para que no se trunque).
- [ ] `og:description` ≤ 155 caracteres.
- [ ] Twitter card type = `summary_large_image`.
- [ ] `og:locale` = `es_CO`.
- [ ] Imagen no tiene texto fuera de la zona segura (100 px desde cada borde).
- [ ] Logo visible y reconocible en thumbnail pequeño (200 × 105 px aprox.).
- [ ] No hay binarios de imagen en el repositorio Git (solo esta especificación).

---

## 9. Recordatorio de convenciones del repositorio

- **Sin binarios en Git.** Las imágenes OG se suben directamente a la Biblioteca de Medios de WordPress o a `wp-content/uploads/og-images/` vía FTP/SSH. Este archivo es la especificación; los assets viven en el servidor.
- Nomenclatura consistente: `gano-{sección}-{variante}-og.webp` (minúsculas, guiones, sin espacios).
- Documentar en este archivo cualquier cambio de dimensión o convención de diseño para mantener coherencia entre Wave 3 y versiones posteriores.
