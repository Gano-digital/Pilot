# Runbook — Atlas de lectura en home Elementor (página 1745 *Inicio*)

**Contexto:** La página estática de inicio (p. ej. ID **1745** *Inicio*) puede seguir marcada como **Elementor** en metadatos (`_elementor_edit_mode = builder`). Si el child theme define **`front-page.php` en la raíz del tema**, WordPress **prioriza ese archivo** para renderizar `/` y el lienzo de Elementor puede quedar **sin datos** (`_elementor_data` vacío) o **no mostrarse** en el cuerpo principal salvo que el tema lo inserte (p. ej. `the_content()`). El Atlas está en código como **`[gano_content_atlas]`** + assets PHP/CSS/JS; podés pegarlo en Elementor **solo si** el contenido de Elementor se está mostrando en la home.

---

## 1. Pasos en wp-admin (Elementor)

1. **Páginas → Inicio → Editar con Elementor** (ID típico 1745).
2. Localizá la sección que reemplaza el antiguo bloque “Marco operativo / SLA” (o el bloque donde quieras el copy SOTA).
3. Añadí widget **Código corto** (Shortcode).
4. Pegá exactamente:
   ```
   [gano_content_atlas]
   ```
5. **Actualizar** la página.
6. Visitá la home en incógnito: deberías ver el bloque **SOTA — State of the Art**, el botón **Mapa de lectura** y el enlace al catálogo SOTA.

**Nota:** Si dejás la sección antigua duplicada, borrá el widget viejo para no repetir contenido.

---

## 2. Modo recomendado hoy: `front-page.php` del child (Atlas + narrativa UX)

Con **Gano Digital — Homepage** implementado en `wp-content/themes/gano-child/front-page.php` (incluye Atlas bajo el hero, proof bar, orden de secciones, HUD encolado vía `functions.php`), **no hace falta** duplicar todo en Elementor.

1. **Ajustes → Lectura**: “Una página estática” → **Inicio** = página 1745 (o la vigente).
2. Dejá el child **activo** (`gano-child`). Con `front-page.php` presente, la portada `/` sale del PHP del tema.
3. **Opcional:** en *Inicio*, **Elementor → Settings → Disable Elementor for this page** (o equivalente) para evitar confusión en el equipo y ediciones “fantasma” que no impactan el front.

---

## 3. Menú principal (ya aplicable vía WP-CLI en servidor)

Ítems añadidos según plan: **Blog** → `/blog/`, **Comenzar aquí** → `/comenzar-aqui/` en el menú `menu-principal` (ubicación primary).

Menú legacy **main-menu** (ID 4): eliminado si ya no se usaba.

---

## 4. Curación del Atlas

- Lista por defecto: slugs en `gano_atlas_default_curated_slugs()` en `inc/gano-content-atlas.php`.
- **Extra / override:** opción `gano_atlas_curated_slugs` (texto): slugs separados por comas.
  ```bash
  wp option update gano_atlas_curated_slugs "slug-extra-1,slug-extra-2"
  ```

---

## 5. Posts EN legacy (traducción)

Plan editorial: traducir o reemplazar entradas 620–625; hasta entonces pueden quedar publicadas pero **fuera** del Atlas (no están en la lista curada).

---

## 6. QA rápido UX

- [ ] Botón “Mapa de lectura” abre hoja inferior en móvil.
- [ ] Chips filtran (Todos / Pilares / Guías / Legal).
- [ ] Escape cierra; foco vuelve al botón.
- [ ] `prefers-reduced-motion`: lista sin animación escalonada.
- [ ] Sin errores de consola.

---

## 7. Verificación remota (WP-CLI + SSH) — 2026-04-23

**Docroot de ejemplo:** `/home/…/public_html/gano.digital` (ajustar usuario/ruta).

```bash
cd /ruta/al/wordpress

# Página de inicio configurada
wp option get show_on_front          # esperado: page
wp option get page_on_front          # esperado: ID numérico (p. ej. 1745)

# Título y modo Elementor
wp post get 1745 --field=post_title
wp post meta get 1745 _elementor_edit_mode   # p. ej. builder

# ¿Hay lienzo Elementor serializado? (si devuelve vacío, el cuerpo lo lleva el tema)
wp post meta get 1745 _elementor_data | wc -c

# Caches tras deploy
wp cache flush
wp rewrite flush
```

**Hecho en producción (2026-04-23):** `page_on_front = 1745`, título *Inicio*, `_elementor_edit_mode = builder`, meta `_elementor_data` **sin contenido útil** (0 bytes vía `wp post meta get` + `wc`), coherente con **HTML servido desde `front-page.php`** (hero, proof bar, Atlas, HUD). Se ejecutaron **`wp cache flush`** y **`wp rewrite flush`**.

**Nota:** el subcomando `wp elementor flush-css` no estaba registrado en ese entorno; si hace falta regenerar CSS de Elementor, usar **Elementor → Herramientas → Regenerar CSS** en wp-admin o instalar el paquete WP-CLI de Elementor si el hosting lo permite.
