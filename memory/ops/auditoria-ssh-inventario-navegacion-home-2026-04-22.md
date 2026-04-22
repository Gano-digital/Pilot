# Auditoría SSH — inventario de contenido, navegación y alineación repo/servidor

**Fecha:** 2026-04-22 · **Sitio:** `gano.digital` (docroot WP vía SSH/WP-CLI).  
**Objetivo:** inventario de páginas/entradas visibles, menús, brecha con repo, acciones (borradores publicados), hallazgos críticos y **especificación** del índice animado del hero (implementación tras aprobación + respuestas al cuestionario).

---

## 1. Resumen ejecutivo

| Hallazgo | Severidad |
|----------|-----------|
| La **home** (`page_on_front` **1745** *Inicio*) está construida en **Elementor**, no con `front-page.php` del child theme. | **Alta** para alineación repo ↔ producción: el hero “animado” del repo no se ve en vivo hasta cambiar plantilla o portar diseño a Elementor/bloque. |
| Menú **Menú Principal** (ubicación `primary`): **9 enlaces**; muchas páginas publicadas (pilares SOTA, blog, catálogo, **comenzar-aqui**, **shop-premium**) **no** están en el menú. | **Alta** UX — contenido huérfano. |
| Páginas **Woo legacy** (`/shop/`, `/cart/`, `/checkout/`, `/my-account/`) siguen publicadas. | **Media** — confusión si Woo no está en uso comercial. |
| **Borradores de página** masivos: publicados en bloque (excl. `1698` Coming Soon, `1752`/`1753` Ecosistemas duplicados, `1815` Dashboard demo duplicado). | **Hecho** — revisar SEO canibalización y calidad. |
| **Posts** en inglés legacy (IDs 620–625) coexisten con posts ES nuevos. | **Media** editorial. |

---

## 2. Alineación servidor ↔ repo

| Aspecto | Servidor | Repo (`gano-child`) |
|---------|----------|---------------------|
| Tema activo | `gano-child` **1.0.6** | `style.css` Version 1.0.6 |
| Home | Página **1745** slug `inicio`, **Elementor** (`_elementor_edit_mode` = builder). `_wp_page_template` no forzó `front-page.php` en la consulta previa. | `front-page.php` define hero SOTA PHP + secciones (`#ecosistemas`, dominio, etc.). |
| Templates PHP | Asignados en páginas SOTA (1754–1757, 1656, etc. según despliegues previos) | `templates/*.php` — verificar `_wp_page_template` por página en próxima pasada WP-CLI si hace falta. |

**Conclusión:** el “diseño canónico” del repo para home **no es** el que renderiza WordPress hoy; cualquier **índice animado en hero** debe implementarse **donde vive la home real** (Elementor + CSS global, **o** migrar la home a `front-page.php` con decisión explícita).

---

## 3. Lectura de sitio (WP-CLI)

- `show_on_front`: **page** · `page_on_front`: **1745**
- Permalinks: **`/%postname%/`**
- Menús:
  - **`menu-principal`** (term_id **104**): `primary`, `header`, `main` — **10** ítems en listado meta (9 visibles en export: Inicio, Nosotros, Dominios, Hosting, Servicios, Ecosistemas, SOTA Hub, Diagnóstico, Contacto).
  - **`legal-footer-menu`** (105): privacidad, términos, cookies.
  - **`main-menu`** (4): ítems legacy (`Hosting` → `#`, Servicios → `?page_id=1594`).

---

## 4. Inventario — páginas publicadas (muestra estructural)

Pilares SOTA (ejemplos IDs **1766–1778**): Blog, Diagnóstico, SEO, SOTA Hub, Shop (`shop-premium`), Ecosistemas, pilares técnicos (`seguridad-zero-trust`, `almacenamiento-nvme`, …), `arquitectura-cloud`, `catalogo-sota`, `comenzar-aqui`, `dashboard-demo-2`, etc.

Legales y confianza: contacto, soporte, nosotros, dominios, hosting, servicios, políticas.

**Ausentes del menú principal (ejemplos):** `/shop-premium/`, `/comenzar-aqui/`, `/blog/`, `/catalogo-sota/`, pilares 1767–1774, artículos legales ya en footer.

---

## 5. Inventario — entradas (`post`)

Publicados (muestra): **1779–1781** (ES, temas soberanía/NVMe/zero-trust), **1675–1677** (guías ES), **620–625** (EN, guías genéricas hosting).

---

## 6. Borradores → publicados (acción 2026-04-22)

Publicadas **todas** las páginas en estado `draft` **excepto**:

- **1698** — Coming Soon  
- **1752**, **1753** — *Ecosistemas* duplicados  
- **1815** — *Dashboard SOTA Demo* (borrador; existe **1816** publicado *dashboard-demo-2*)

**Riesgo:** muchas URLs nuevas indexables; posible **solapamiento temático** con pilares ya publicados (NVMe, zero-trust, etc.). Recomendación: auditoría editorial + `noindex` o fusión donde aplique.

---

## 7. Evaluación crítica (contenido + UX)

1. **Dos “voces” y dos idiomas** en blog (EN 620–625 vs ES reciente) — inconsistente para Colombia.  
2. **Categorización ausente** en menú: el visitante no descubre el catálogo de artículos salvo Google o enlaces internos.  
3. **Hero actual (Elementor)** no articula profundidad del archivo publicado.  
4. **main-menu** con `#` y `page_id` antiguo — deuda técnica de menú.  
5. **LCP / animación:** cualquier índice animado en hero debe respetar `prefers-reduced-motion` y no competir con LCP del hero principal (skill UI/UX Pro Max — tokens `--gano-*`, sin wall-of-links).

---

## 8. Paleta y tokens (para el índice animado — especificación previa a código)

Fuente: `wp-content/themes/gano-child/style.css` (`:root`).

- **Acento / foco:** `--gano-gold` `#D4AF37`, `--gano-gold-border`, `--gano-gold-soft`  
- **Primario:** `--gano-blue` / `--gano-color-primary`  
- **CTA energía:** `--gano-orange`  
- **Superficie oscura:** `--gano-bg-dark`, `--gano-dark`, texto `--gano-color-text-on-dark`  
- **Radio / transición:** `var(--gano-radius-*)`, `var(--gano-transition)`

**Anti-patrones:** más de ~8 nodos visibles a la vez en mobile; animación continua sin pausa; enlaces sin `focus-visible`.

---

## 9. Propuesta de diseño — “Atlas de lectura” (hero / primera vista)

**Concepto:** panel compacto bajo el CTA del hero (o botón “Explorar archivo”) que abre una **superficie glass** (`backdrop-filter`, borde `--gano-gold-border`) con:

1. **Chips** de filtro: *Todos · Pilares SOTA · Guías · Legal* (taxonomía o `post_type` acordada).  
2. **Lista animada** (stagger `opacity` + `translateY` 8px, **max 400 ms**, `prefers-reduced-motion: reduce` → sin stagger).  
3. Cada ítem: título, etiqueta de tipo, enlace a URL canónica.

**Variantes de implementación (tras aprobación):**

| Opción | Pros | Contras |
|--------|------|---------|
| **A.** Bloque HTML + CSS en Elementor + pequeño `gano-homepage.js` para filtros | Coherente con home actual | Requiere pegado en Elementor |
| **B.** Cambiar home a `front-page.php` e incrustar componente PHP | Control total en repo | Cambio disruptivo de marketing/SEO |
| **C.** Widget área + shortcode `[gano_content_atlas]` | Reutilizable | Requiere registrar shortcode en `functions.php` |

**Motor de datos:** WP_Query `post_type=page,post`, `post_status=publish`, exclusiones opcionales (Woo, Coming Soon), orden por `modified` o menú manual vía opción/theme mod.

---

## 10. Investigación para enriquecer textos (fuentes internas)

- `memory/content/homepage-copy-2026-04.md`, `homepage-sota-blueprint-2026-04-17.md`  
- `memory/content/trust-pages-bundle-2026.md` (tono y claims verificables)  
- `memory/ops/homepage-vitrina-launch-plan-2026-04.md` (RACI y fases)  
- Skill **UI/UX Pro Max:** motor opcional en `external/ui-ux-pro-max-skill` (no presente en workspace; clonar con `gh repo clone nextlevelbuilder/ui-ux-pro-max-skill external/ui-ux-pro-max-skill` antes de generar design system asistido).

---

## 11. Ejecución post-aprobación (2026-04-22)

1. **Código en repo:** shortcode `[gano_content_atlas]`, `inc/gano-content-atlas.php`, `css/gano-content-atlas.css`, `js/gano-content-atlas.js`, hero en `front-page.php`, enqueue en `functions.php`.  
2. **Runbook Elementor:** [`runbook-elementor-atlas-home-2026-04-22.md`](runbook-elementor-atlas-home-2026-04-22.md) — pegar shortcode en la home 1745.  
3. **Menú `menu-principal` (104):** añadidos **Blog** y **Comenzar aquí**; menú legacy **ID 4** eliminado.  
4. **SEO:** pendiente auditoría editorial (canibalización) según respuesta cuestionario.

---

**Generado por:** auditoría agente Cursor + WP-CLI remoto.
