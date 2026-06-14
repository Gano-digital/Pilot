# Cursor WC3 (guantelete) — mantenimiento, pruebas y mitigación

**Contexto:** Cursor personalizado del tema hijo (`gano-child`) inspirado en *Warcraft III*, usando atlas raster `assets/cursor/wc3-human-atlas.png` (256×128) y lógica en `js/gano-cursor.js` + `css/gano-cursor.css`.

**PRs de referencia:** [#274](https://github.com/Gano-digital/Pilot/pull/274) (feature), [#275](https://github.com/Gano-digital/Pilot/pull/275) (hover en iconos). Mejoras posteriores (animación, accesibilidad, filtro de apagado) van en commits posteriores a esta guía.

---

## 1. Archivos involucrados

| Ruta | Rol |
|------|-----|
| `wp-content/themes/gano-child/assets/cursor/wc3-human-atlas.png` | Sprite atlas (idle fila superior, miras fila central, estados inferiores). |
| `wp-content/themes/gano-child/css/gano-cursor.css` | `cursor: none` condicionado, posiciones de fondo por estado, breakpoints. |
| `wp-content/themes/gano-child/js/gano-cursor.js` | Inserción del nodo, clases en `body`, `mousemove`, `mouseleave` en `documentElement`, click. |
| `wp-content/themes/gano-child/functions.php` | `wp_enqueue_*` con `filemtime` y filtro `gano_enable_wc3_cursor`. |

---

## 2. Comportamiento esperado (checklist manual)

En **Chrome/Edge** escritorio, `pointer: fine`:

1. **Idle:** guantelete animado (8 fotogramas, `steps(7)` en CSS) siguiendo el puntero con ligero **lerp** (suavizado).
2. **Sobre enlace/botón/CTA:** se detiene la animación idle y se muestra la **mira** (fila central del atlas, `background-position: 0 -32px`).
3. **Sobre elemento deshabilitado** (`disabled`, `aria-disabled`, `.disabled`): mira **inválida** (`-64px -96px`).
4. **Click:** pose alternativa (`-96px -96px`) + escala 0.92 hasta `mouseup`.
5. **Campos de texto** (`input` texto, `textarea`, `contenteditable`): cursor del sistema tipo texto, overlay oculto.
6. **`prefers-reduced-motion: reduce`:** el script **no** inicializa el cursor; el CSS fuerza `cursor: auto` y oculta el wrapper si existiera.
7. **`pointer: coarse` o ancho ≤1024px:** sin cursor custom (CSS + early return en JS).

**Consola:** no debe haber errores en cada paso.

---

## 3. Mitigación rápida (sin deploy de código)

### Opción A — Filtro WordPress (recomendado)

En un **MU-plugin**, `functions.php` del child (temporal), o plugin tipo “Code snippets”:

```php
add_filter( 'gano_enable_wc3_cursor', '__return_false' );
```

Esto omite el `wp_enqueue` del CSS/JS del cursor. Los visitantes recuperan el cursor nativo al refrescar (caché de página puede demorar 1 ciclo CDN).

### Opción B — Quitar atlas

Si el problema es solo el PNG (404, peso, licencia): dejar el filtro en `false` o borrar `wc3-human-atlas.png` y **comentar** los `enqueue` en `functions.php` hasta sustituir el asset.

---

## 4. Rollback completo (volver al estado “sin WC3”)

1. Aplicar **Opción A** o revertir en Git el commit del cursor (tema hijo).
2. Opcional: eliminar `assets/cursor/wc3-human-atlas.png` y los archivos `gano-cursor.*` si se retira la feature por completo.
3. Desplegar con el flujo habitual (`main` → workflow **04 · Deploy** si aplica).

---

## 5. Conflictos típicos con otro código

| Síntoma | Causa probable | Acción |
|---------|----------------|--------|
| Cursor invisible pero clics OK | Otro `cursor: none` global o z-index encima del `.gano-cursor-gauntlet` | Inspeccionar capas; subir `z-index` del gauntlet o bajar overlay conflictivo. |
| Doble cursor / parpadeo | Tema/plugin también inyecta cursor custom | Desactivar el otro componente o usar filtro `gano_enable_wc3_cursor` false. |
| Hover “incorrecto” en iconos | Selector `hover` no coincide con el DOM (Elementor, `role` raro) | Ampliar `hoverSelector` en `gano-cursor.js` o marcar nodos con `data-gano-cursor-hover`. |
| Animación entrecortada | CPU throttling o muchos `filter` en la página | Probar sin `drop-shadow` en `.gano-cursor-gauntlet` o reducir frecuencia del keyframe. |
| Texto sin I-beam | `gano-cursor--text` no se aplica | Añadir el `type` de input faltante en `textCursorSelector`. |

---

## 6. Verificación en servidor (SSH)

**No** fijar host/usuario en el repo: usar el alias o variables documentadas en sesiones internas (`GANO_SSH_HOST`, etc.).

Ejemplos de comprobación **solo lectura** (ruta típica GoDaddy cPanel; ajustar si tu hosting usa otro layout):

```bash
BASE=public_html/gano.digital/wp-content/themes/gano-child
ls -la "$BASE/js/gano-cursor.js" "$BASE/assets/cursor/wc3-human-atlas.png"
wc -c "$BASE/js/gano-cursor.js"
# Coherencia con el artefacto local tras git pull (opcional)
md5sum "$BASE/js/gano-cursor.js"
```

Tras un deploy por webhook, el ZIP actualiza el tema; si los `md5` locales (tras `git pull`) y remotos coinciden, el sync es correcto.

---

## 7. Issues GitHub relacionados

Los issues abiertos **#263–#265** son operativos (RCC, slugs, tablero); **no** bloquean el cursor. Si el cursor genera deuda (p. ej. A11y formal), abrir un issue con etiqueta `ux` enlazando a esta guía.

---

## 8. Legal / marca (recordatorio)

El atlas reproduce iconografía de *Warcraft III*. Para uso comercial prolongado valorar **arte propio** o clearance; ver `memory/research/wc3-human-gauntlet-cursor-internet-extraction-2026-04.md`.
