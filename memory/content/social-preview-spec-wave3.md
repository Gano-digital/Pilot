# Gano Digital — Especificación de previews sociales (OG / Twitter Card) — Oleada 3

**Última actualización:** Abril 2026  
**Contexto técnico:** Rank Math Pro (prioridad) + `gano-seo.php` como fallback MU plugin.  
**Sin binarios en este repositorio** — este documento es la especificación; los activos se suben vía SFTP/Media Library de WordPress.

---

## 1. Dimensiones recomendadas

### 1.1 Open Graph (Facebook, LinkedIn, WhatsApp, iMessage)

| Formato | Dimensiones | Relación de aspecto | Peso máximo | Notas |
|---------|-------------|---------------------|-------------|-------|
| **OG Image estándar** | **1200 × 630 px** | 1.91:1 | 8 MB (meta; objetivo < 300 KB) | Resolución mínima aceptada por Facebook: 200 × 200 px |
| OG Image cuadrada (fallback) | 1200 × 1200 px | 1:1 | < 300 KB | Para plataformas que recortan a cuadrado |

> **Recomendado para Gano Digital:** producir en **1200 × 630 px** como imagen primaria. La cuadrada puede ser un recorte de la primaria con zona de texto recentrada.

### 1.2 Twitter Card / X

| Tipo de tarjeta | Dimensiones mínimas | Dimensiones óptimas | Relación de aspecto | Peso máximo |
|-----------------|--------------------|--------------------|---------------------|-------------|
| `summary_large_image` (**activo en gano-seo.php**) | 300 × 157 px | **1200 × 630 px** | 2:1 | 5 MB (objetivo < 300 KB) |
| `summary` (solo logo/icono) | 144 × 144 px | 400 × 400 px | 1:1 | 5 MB |

> `gano-seo.php` line 386 usa `summary_large_image` — mantener este tipo en Rank Math y en el fallback; produce la mayor visibilidad en feed.

### 1.3 Especificaciones de archivo

| Parámetro | Valor recomendado |
|-----------|------------------|
| **Formato** | WebP (principal) + JPG fallback para plataformas que no soporten WebP |
| **Modo de color** | sRGB |
| **Calidad WebP** | 82–88 (balance nitidez/peso) |
| **Calidad JPG fallback** | 85 |
| **Naming convention** | `gano-og-{página}-{variante}.webp` (ej: `gano-og-home-v1.webp`) |
| **Hosting** | WordPress Media Library → URL pública (`/wp-content/uploads/og/`) |

---

## 2. Zona de texto seguro en imagen (safe zone)

Todas las redes sociales recortan o superponen elementos UI sobre la imagen. Para garantizar legibilidad sin importar el contexto de visualización:

```
┌──────────────────────────────────────────────────┐  1200 px
│  ░░░░░░░░  MARGEN EXTERNO (60 px todos los lados) ░░░░░  │
│  ░                                                 ░  │
│  ░   ┌────────────────────────────────────────┐   ░  │
│  ░   │                                        │   ░  │
│  ░   │   ZONA SEGURA DE TEXTO                 │   ░  │
│  ░   │   1080 × 510 px                        │   ░  │
│  ░   │   (contenido vital debe vivir aquí)    │   ░  │
│  ░   │                                        │   ░  │
│  ░   │   Logo arriba-izq ≥ 48 × 48 px         │   ░  │
│  ░   │   Headline central (ver sección 3)     │   ░  │
│  ░   │   Tagline / URL abajo                  │   ░  │
│  ░   └────────────────────────────────────────┘   ░  │
│  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │
└──────────────────────────────────────────────────┘  630 px
```

**Regla práctica:** ningún texto ni logo debe cruzar los primeros/últimos 60 px de borde. En móvil (vista comprimida en Twitter), el recorte puede llegar a los 80 px en los lados cortos.

### Esquinas críticas a respetar

- **Arriba izquierda:** avatar/favicon de la cuenta en Twitter superpone ~48 × 48 px.
- **Abajo izquierda/derecha:** botones de acción social pueden cubrir hasta 40 px verticales en algunas vistas móvil.
- **Centro:** zona de mayor atención — colocar headline y claim principal.

---

## 3. Tipografía en imagen OG

Usar los mismos tokens de marca del child theme, renderizados como texto **rasterizado** (no `<text>` SVG si el renderizador de red social no lo soporta):

| Rol | Fuente | Tamaño sugerido (en canvas 1200 × 630) | Color sobre fondo oscuro |
|-----|--------|-----------------------------------------|--------------------------|
| **Headline** | Plus Jakarta Sans Bold | 52–64 px | `#FFFFFF` o `--gano-gold: #D4AF37` |
| **Subheadline / claim** | Plus Jakarta Sans Regular | 28–36 px | `#E5E7EB` |
| **URL / marca** | Inter Regular | 22–26 px | `--gano-blue: #1B4FD8` sobre fondo claro; `#93C5FD` sobre fondo oscuro |
| **Etiqueta de plan** (opcional) | Inter SemiBold | 18–20 px | color de acento del ecosistema (ver sección 4) |

**Contraste mínimo:** cualquier texto sobre fondo debe superar WCAG AA (4.5:1 para texto normal, 3:1 para texto grande). Verificar con [contrast-ratio.com](https://contrast-ratio.com) antes de producción.

---

## 4. Sistema de color por página / ecosistema

Mantener coherencia con los tokens `--gano-*` del child theme:

| Contexto | Fondo sugerido | Acento / Headline | Justificación |
|----------|---------------|-------------------|---------------|
| **Home / general** | `#0F1923` (`--gano-dark`) | `#D4AF37` (`--gano-gold`) + blanco | Máximo impacto, alineado al hero oscuro |
| **Núcleo Prime** | `#0F1923` | `#1B4FD8` (`--gano-blue`) | Plan de entrada; azul = confianza/solidez |
| **Fortaleza Delta** | `#0F1923` | `#00C26B` (`--gano-green`) | Crecimiento, actividad operativa |
| **Bastión SOTA** | `#0F1923` | `#D4AF37` (`--gano-gold`) | Premium, máxima soberanía |
| **Blog / Pilares SEO** | `#0F1923` o `#111827` | `#1B4FD8` con tagline blanco | Contenido técnico, autoridad |
| **Legal / Contacto** | `#F9FAFB` (claro) | `#0F1923` + `--gano-blue` | Neutralidad, confianza institucional |

---

## 5. Páginas que deben tener imagen OG propia (prioridad decreciente)

### Prioridad 1 — Conversión directa

| Página | Slug / URL | Imagen OG | Descripción meta sugerida (≤ 155 caracteres) |
|--------|-----------|-----------|----------------------------------------------|
| **Home** | `/` | `gano-og-home-v1.webp` | "Hosting WordPress de alto rendimiento para Colombia. NVMe, seguridad Zero-Trust y soporte en español. Ecosistemas desde COP." |
| **Shop / Ecosistemas** | `/ecosistemas` o `/tienda` | `gano-og-shop-v1.webp` | "Núcleo Prime, Fortaleza Delta y Bastión SOTA. Elige el ecosistema WordPress que escala con tu negocio." |
| **Bastión SOTA** (producto top) | `/producto/bastion-sota` | `gano-og-bastion-sota-v1.webp` | "Bastión SOTA: máxima disponibilidad, IA operativa y soberanía digital. El ecosistema WordPress sin compromisos." |
| **Núcleo Prime** | `/producto/nucleo-prime` | `gano-og-nucleo-prime-v1.webp` | "Núcleo Prime: el punto de entrada al hosting WordPress de alta ingeniería. NVMe y seguridad desde el inicio." |
| **Fortaleza Delta** | `/producto/fortaleza-delta` | `gano-og-fortaleza-delta-v1.webp` | "Fortaleza Delta: ecosistema WordPress con mayor resiliencia, backups continuos y monitoreo proactivo." |

### Prioridad 2 — Autoridad SEO (pilares de contenido)

Estas 20 páginas comparten una plantilla OG; producir **3 variantes de imagen** que se roten por categoría temática (infra, seguridad, IA/operación).

| Categoría | Variante OG | Páginas que la usan |
|-----------|-------------|---------------------|
| **Infraestructura / velocidad** | `gano-og-pilar-infra-v1.webp` | NVMe, Edge Computing, HTTP/3, Escalamiento Elástico, Alta Disponibilidad, Headless WP |
| **Seguridad / soberanía** | `gano-og-pilar-security-v1.webp` | Zero-Trust, DDoS, Muerte del hosting compartido, Cifrado post-cuántico, Soberanía digital LATAM |
| **IA / operación / experiencia** | `gano-og-pilar-ia-v1.webp` | Gestión predictiva IA, Self-Healing, CI/CD, Backups tiempo real, Skeleton Screens, Micro-animaciones, Analytics server-side, Agente IA |

### Prioridad 3 — Confianza institucional

| Página | Slug | Imagen OG | Notas |
|--------|------|-----------|-------|
| **Nosotros / Manifiesto** | `/nosotros` | `gano-og-brand-v1.webp` | Solo publicar si la página tiene contenido real |
| **Contacto** | `/contacto` | Reutilizar `gano-og-home-v1.webp` | Imagen genérica es suficiente |
| **Política de privacidad** | `/privacidad` | Logo sobre fondo claro | No crítico para conversión |

---

## 6. Cómo se inyectan las imágenes OG hoy

### Flujo activo

```
Rank Math Pro (activo)
  └─ Gestiona og:image vía campo "Featured Image" o imagen personalizada por página
       └─ Si no hay imagen en RM → gano-seo.php: gano_og_fallback()
            └─ Busca post thumbnail → logo_url (fallback último recurso)
```

### Configuración en Rank Math Pro

1. **Por página individual:** Rank Math → Edit Post → Social → Open Graph Image.
2. **Por tipo de post (global):** Rank Math → Títulos y Metas → cada CPT → pestaña Social.
3. **Dimensión declarada:** agregar en Rank Math las meta tags de dimensión para evitar warnings de Facebook Debugger:

```php
// Rank Math filtra rank_math_og_image antes de salida.
// Si no se configura en RM, gano-seo.php puede extenderse con:
add_action( 'wp_head', 'gano_og_image_dimensions', 4 );
function gano_og_image_dimensions(): void {
    if ( class_exists( 'RankMath' ) ) {
        return; // RM lo maneja
    }
    echo '<meta property="og:image:width"  content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">'  . "\n";
    echo '<meta property="og:image:type"   content="image/webp">' . "\n";
}
```

> **Nota:** este snippet es opcional; añadirlo a `gano-seo.php` si se detectan warnings en Facebook Sharing Debugger o Twitter Card Validator.

---

## 7. Proceso de creación del activo (sin Photoshop obligatorio)

### Herramientas recomendadas (sin costo para MVP)

| Herramienta | Uso | Enlace |
|-------------|-----|--------|
| **Figma** (gratuito) | Diseño vectorial, exportación WebP/PNG | figma.com |
| **Canva Pro** | Plantillas OG 1200 × 630 integradas | canva.com |
| **Squoosh** | Compresión WebP sin pérdida visible | squoosh.app |
| **OG Image Preview** (Chrome extension) | Ver cómo se verá antes de publicar | — |

### Pasos mínimos por imagen

1. Canvas en **1200 × 630 px**, fondo `#0F1923`.
2. Logo Gano Digital en esquina superior izquierda, dentro de la zona segura (≥ 60 px del borde).
3. Headline del ecosistema/página en **Plus Jakarta Sans Bold 56 px**, color `#FFFFFF` o `#D4AF37`.
4. Tagline corto (≤ 8 palabras) en **Plus Jakarta Sans Regular 30 px**, color `#E5E7EB`.
5. URL `gano.digital` en esquina inferior derecha en **Inter 22 px**, color `#93C5FD`.
6. Exportar como **WebP calidad 85** + JPG fallback 85 con el mismo nombre base.
7. Subir a WordPress → Media Library → carpeta `og/` (crear con WP File Manager o SFTP).
8. Pegar URL en Rank Math → campo OG Image de la página correspondiente.

---

## 8. Checklist de validación antes de publicar

- [ ] Imagen en **1200 × 630 px** confirmada (inspeccionar con herramienta de imagen).
- [ ] Peso del archivo **< 300 KB** (WebP preferido).
- [ ] Texto principal visible y legible dentro de la zona segura (60 px de margen).
- [ ] Contraste del texto sobre fondo verificado (WCAG AA ≥ 4.5:1).
- [ ] URL cargada en Rank Math Pro campo OG Image (o en `gano_seo_config()` como fallback).
- [ ] Tags `og:image:width` y `og:image:height` presentes (RM los agrega automáticamente o con el snippet de sección 6).
- [ ] Validar con [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/) — "Obtener nueva información de scrape".
- [ ] Validar con [Twitter Card Validator](https://cards-dev.twitter.com/validator).
- [ ] Validar con [LinkedIn Post Inspector](https://www.linkedin.com/post-inspector/).
- [ ] Sin texto superpuesto al avatar/botones de red social en vista móvil (verificar screenshots).
- [ ] `og:locale` = `es_CO` confirmado (ya configurado en `gano-seo.php` line 385).

---

## 9. Naming convention — resumen

```
wp-content/uploads/og/
├── gano-og-home-v1.webp                  ← Home (prioridad 1)
├── gano-og-home-v1.jpg                   ← Fallback JPG
├── gano-og-shop-v1.webp                  ← Shop / Ecosistemas
├── gano-og-bastion-sota-v1.webp          ← Producto Bastión SOTA
├── gano-og-nucleo-prime-v1.webp          ← Producto Núcleo Prime
├── gano-og-fortaleza-delta-v1.webp       ← Producto Fortaleza Delta
├── gano-og-pilar-infra-v1.webp           ← Pilares: infraestructura / velocidad
├── gano-og-pilar-security-v1.webp        ← Pilares: seguridad / soberanía
├── gano-og-pilar-ia-v1.webp              ← Pilares: IA / operación
└── gano-og-brand-v1.webp                 ← Nosotros / Manifiesto
```

Versionar con `-v2`, `-v3` al actualizar diseño; mantener el anterior mientras se propaga la caché de redes sociales (48–72 h).

---

_Fin de la especificación — Oleada 3 — Gano Digital._
