# Runbook — Atlas de lectura en home Elementor (página 1745 *Inicio*)

**Contexto:** En producción la home usa **Elementor**, no `front-page.php`. El Atlas ya está en código (`[gano_content_atlas]` + assets); para verlo en la home **sin** cambiar la página estática a plantilla PHP, pegá el shortcode en Elementor.

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

## 2. Si preferís usar solo `front-page.php` del tema

1. **Ajustes → Lectura**: mantener “Una página estática” pero editar la página Inicio.
2. En la página Inicio, panel lateral **Atributos de página → Plantilla**: elegir **Gano Digital — Homepage** (si está registrada) o desactivar Elementor en esa página.
3. Guardar. Entonces WordPress usará `front-page.php` del child (ya incluye el Atlas en el hero).

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
