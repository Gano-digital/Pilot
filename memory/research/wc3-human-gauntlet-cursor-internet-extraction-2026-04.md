# WC3 — Cursor humano / guantelete: plan de extracción web y ejecución (2026-04)

Documento único: **plan**, **fuentes**, **descargas ejecutadas**, **inventario local**, **mapeo a web/gano.digital** y **riesgos legales**.

---

## 1. Objetivo

Recopilar desde internet la **información técnica** y **referencias visuales** necesarias para replicar o adaptar el cursor de **Humano (guantelete)** de *Warcraft III* e integrarlo después en **gano.digital**, preferentemente vía el patrón existente (`gano-cursor.js` + cursor DOM), no vía `cursor: url(*.ani)` (soporte web pobre).

---

## 2. Plan completo (fases)

| Fase | Qué cubre | Criterio de hecho |
|------|-------------|-------------------|
| **A** | Documentación motor (MDX + BLP, secuencias, atlas) | Lista de animaciones y enlace al tutorial verificado |
| **B** | Referencia de textura in-game (dimensiones atlas) | Medida confirmada (p. ej. 256×128) + URL de descarga PNG |
| **C** | Cursores Windows (.cur / .ani) para prototipo / hotspots | ZIP comunitario con licencia declarada + extracción local |
| **D** | Set alternativo “gauntlet” | Segundo ZIP para comparar variantes |
| **E** | Manifest de integridad | `sources-manifest.json` con SHA-256 por archivo |
| **F** | (Siguiente, no ejecutado aquí) | Convertir `.cur` → PNG/WebP, medir hotspot, sprite sheet + JSON para tema hijo |

---

## 3. Ejecución realizada (2026-04-19)

### 3.1 Descargas binarias (completadas)

| Origen | URL directa | Destino en repo |
|--------|-------------|------------------|
| RealWorld Cursor — *WARCRAFT 3 HUMAN* | https://www.rw-designer.com/cursor-downloadset/warcraft-3-human.zip | `memory/research/wc3-human-gauntlet-cursor-downloads/warcraft-3-human.zip` |
| RealWorld Cursor — *GAUNTLET WARCRAFT 3* | https://www.rw-designer.com/cursor-downloadset/gauntlet-warcraft-3.zip | `memory/research/wc3-human-gauntlet-cursor-downloads/gauntlet-warcraft-3.zip` |
| The Spriters Resource — atlas PNG | https://www.spriters-resource.com/media/assets/193/196317.png | `memory/research/wc3-human-gauntlet-cursor-downloads/spriters-human-cursor-atlas-196317.png` |

Páginas de contexto (licencia “Release to Public Domain” según RW Designer en cada set):

- https://www.rw-designer.com/cursor-set/warcraft-3-human  
- https://www.rw-designer.com/cursor-set/gauntlet-warcraft-3  
- https://www.rw-designer.com/licenses  

### 3.2 Extracción ZIP

- `warcraft-3-human.zip` → `memory/research/wc3-human-gauntlet-cursor-downloads/extracted-warcraft-3-human/`
- `gauntlet-warcraft-3.zip` → `memory/research/wc3-human-gauntlet-cursor-downloads/extracted-gauntlet-warcraft-3/`

### 3.3 Integridad

- `memory/research/wc3-human-gauntlet-cursor-downloads/sources-manifest.json` — 29 entradas (archivos extraídos + ZIP + PNG + el propio manifest).  
- **Nota:** si se regenera el manifest, conviene excluir `sources-manifest.json` del listado para evitar hash circular.

---

## 4. Información técnica clave (internet, verificada)

### 4.1 Motor original del juego (MDX + BLP)

- Modelo UI **`HumanCursor.mdx`**, textura **`HumanCursor.blp`**, **BlendTime** 150 (comunidad).  
- **14 secuencias** documentadas en tutorial Hive: `Target`, `Normal`, `Select`, `TargetSelect`, `InvalidTarget`, `HoldItem`, ocho `Scroll *`.  
- Fuente: [Creating a custom cursor | Hive Workshop](https://www.hiveworkshop.com/threads/creating-a-custom-cursor.134122/)

### 4.2 Atlas raster (referencia de layout)

- The Spriters Resource — asset **Cursor (Human)**, **256×128** px, PNG.  
- Página: https://www.spriters-resource.com/pc_computer/warcraft3reignofchaos/sheet/196317/

### 4.3 Cursores en navegador (contexto histórico vs SOTA)

- RW Designer aún documenta `cursor: url(...)`; para producción moderna, preferir **cursor sintético** (como Gano ya hace) por limitaciones de `.ani` y GIF en CSS.  
- https://www.rw-designer.com/cursors-online  

---

## 5. Inventario de archivos extraídos (nombres exactos)

### 5.1 `extracted-warcraft-3-human/`

| Archivo | Rol aproximado para web |
|---------|-------------------------|
| `Human.cur` | Puntero principal humano |
| `move1.cur`, `attack1.cur` | Movimiento / acción |
| `cursor_spell_default.ani`, `HU WIB.ani`, `HUMAN LINK.ani` | Hechizo, “what is buff”, enlace |
| `UNAVABIBLE.ani` | No disponible (typo en nombre original) |
| `War-*.cur` | Resize / beam / pen / move / no / cross / up — equivalentes a `cursor: *` del SO |
| `readme.txt` | Autor **THTH**, licencia PD según archivo |
| `warcraft-3-human.crs` | Metadatos RealWorld Cursor |

### 5.2 `extracted-gauntlet-warcraft-3/`

| Archivo | Rol aproximado |
|---------|----------------|
| `normal cursor.cur`, `link select.cur`, `wib.cur` | Variantes de puntero |
| `busy.ani`, `text.ani` | Espera / texto I‑beam animado |
| `readme.txt` | Autor **THTH**, licencia PD según archivo |

---

## 6. Mapeo sugerido → integración `gano-child`

Código actual del cursor premium:

- `wp-content/themes/gano-child/js/gano-cursor.js`
- `wp-content/themes/gano-child/css/gano-cursor.css`

Sugerencia de correspondencia (iteración futura):

| Estado web (clase o flag) | Archivo de referencia RW | Analogía MDX (Hive) |
|---------------------------|--------------------------|---------------------|
| Idle | `Human.cur` / `normal cursor.cur` | `Normal` |
| Hover CTA / enlace | `HUMAN LINK.ani` o anillo actual | `Target` / `TargetSelect` |
| `mousedown` | frame de “cierre” o `attack1.cur` | `Select` |
| `disabled` / zona no clicable | `UNAVABIBLE.ani` | `InvalidTarget` |
| `wait` / loading | `cursor_spell_default.ani` o `busy.ani` | (no 1:1; UI propia) |
| Resize ventana (si aplica) | `War-Size *.cur` | `Scroll *` (solo si implementás bordes) |

Implementación recomendada: **sustituir dot/ring** por un `<div>` con `background-image` (sprite) o `<img>`, manteniendo **lerp** y **eventos** ya existentes.

---

## 7. Herramientas siguientes (fase F)

1. **Extraer PNG desde `.cur` / frames desde `.ani`**: ImageMagick, Greenfish Icon Editor, o librerías Node (`to-ico` inverso limitado; a menudo mejor herramienta gráfica en Windows).  
2. **Hotspot**: leer cabecera CUR (coordenadas del “click point”) y aplicar como `transform-origin` en CSS.  
3. **Empaquetado web**: sprite sheet + `manifest.json` por estado (`fps`, `loop`, `offset`).  
4. **Accesibilidad**: respetar `prefers-reduced-motion`; no forzar cursor custom en touch (ya alineado con `@media (pointer: fine)` en `gano-cursor.css`).

---

## 8. Aviso legal (obligatorio para gano.digital)

- Los ZIP de RW Designer declaran **“Released to Public Domain”** en la página del set y en `readme.txt` (**autor THTH**). Eso cubre la **contribución del autor al sitio**, no sustituye automáticamente un **clearance comercial** frente a posibles reclamos de **derechos de autor / marca** sobre el diseño icónico del juego.  
- El PNG de Spriters Resource es **rip de juego** con términos del sitio; úsalo como **referencia de proporción**, no como base final de marca comercial sin revisión legal.  
- Para producción en **gano.digital**, lo más seguro sigue siendo **arte original inspirado** o **licencia explícita**.

---

## 9. Checklist rápido “¿está listo para integrar?”

- [x] Atlas 256×128 descargado y referenciado  
- [x] Sets `.cur`/`.ani` locales + hashes  
- [ ] Conversión a assets web (PNG/WebP) + medición de hotspot  
- [ ] Branch en tema hijo + feature flag (opcional)  
- [ ] Revisión legal si el visual es demasiado cercano al oficial  

---

## 10. SHA-256 de los ZIP y del PNG (copia rápida)

| Archivo | SHA-256 |
|---------|---------|
| `warcraft-3-human.zip` | `E856F404C53786B60C4623BAC799D4D61EFBD088C952B2EA4DF880E5C4D4A25B` |
| `gauntlet-warcraft-3.zip` | `F16342D98C91FAB55B0308DEBAFB4CAD9F34C7C838070505CA817789207AA1A8` |
| `spriters-human-cursor-atlas-196317.png` | `2DDE3B99B3BA0AC489ABB890461EEB892598A191EAD50ACD07CCAAE533868B8F` |

*(Detalle completo: `sources-manifest.json` en la misma carpeta.)*

---

## 11. Mantenimiento en producción (post-implementación)

Ver **`memory/ops/gano-wc3-cursor-maintenance.md`**: checklist de pruebas, filtro `gano_enable_wc3_cursor`, rollback y verificación SSH.
