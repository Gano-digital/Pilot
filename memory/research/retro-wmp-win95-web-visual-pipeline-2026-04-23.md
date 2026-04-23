# Pipeline visual “WMP legacy + salvapantallas 3D Win95” para web (SOTA)

**Objetivo:** replicar la *lógica* estética (geométrica, low-poly, procedural, “demostración de GPU”) sin copiar assets propietarios de Microsoft.  
**Fecha:** 2026-04-23

---

## 1. Pendiente operativo (repo / PR)

- Rama `feat/gano-content-atlas-home-2026-04` empujada; PR **#285** en `Gano-digital/Pilot`.
- `git status`: sin cambios pendientes en tema; carpeta `wiki/` sigue **untracked** (no commiteada).
- Checks GitHub API pueden aparecer como `pending` hasta que corran workflows; revisar pestaña **Checks** del PR antes de merge.

---

## 2. Windows Media Player — visualizaciones (no solo “vectores”)

### 2.1 Arquitectura oficial (legacy Win32)

- Las visualizaciones clásicas eran **COM DLL** que implementaban el modelo de **efectos** del reproductor: interfaz **`IWMPEffects`** con **`Render`**, datos de **forma de onda** y **espectro** (FFT), y **presets** (variantes del mismo efecto: barras vs “scope/wave”, colores, etc.).
- Documentación Microsoft (archivada, pero útil para entender el *pipeline*):
  - [About Custom Visualizations](https://learn.microsoft.com/en-us/windows/win32/wmp/about-custom-visualizations)
  - [Windows Media Player Custom Visualizations](https://learn.microsoft.com/en-us/previous-versions/windows/desktop/wmp/windows-media-player-custom-visualizations)
  - [Building a Visualization](https://learn.microsoft.com/en-us/previous-versions/windows/desktop/wmp/building-a-visualization)
  - [Presets](https://learn.microsoft.com/en-us/previous-versions/windows/desktop/wmp/presets) — explica enumeraciones tipo **Bars / Wave (scope)** y el switch en `Render`.

**Traducción a web:** mismo pipeline conceptual:

1. `AnalyserNode` (FFT) + `getByteFrequencyData` / `getByteTimeDomainData`.
2. Shader o canvas que **mapea buffers** a geometría (barras, polilínea, partículas, mesh deformado).
3. “Presets” = **parámetros** (paleta, smoothing, número de bandas, GLSL uniforms).

### 2.2 “Vectores y figuras geométricas” en WMP

En la práctica eran:

- **Barras / spikes** (rectángulos o instanced quads).
- **Osciloscopio** (polyline 2D/3D).
- **Dot planes** (rejilla de puntos con desplazamiento Z o heightmap del espectro).

En web: **Canvas 2D**, **SVG** (limitado a muchas partículas), **Three.js** (`BufferGeometry`, `InstancedMesh`), **regl** o **WebGPU** (futuro) si el presupuesto de GPU lo vale.

### 2.3 Skins WMP (cromo UI “player”)

- Paquete **`.wmz`**: ZIP con PNG/GIF/JPG, **`.wms`** (definición tipo XML de controles), opcional **JScript** (eventos).
- Referencias:
  - [Skin Files (Microsoft Learn)](https://learn.microsoft.com/en-us/previous-versions/windows/desktop/wmp/skin-files)
  - [Skin Definition File](https://learn.microsoft.com/en-us/previous-versions/windows/desktop/wmp/skin-definition-file)
  - [Windows Media Player Skin Package (Archiveteam wiki)](http://fileformats.archiveteam.org/wiki/Windows_Media_Player_Skin_Package)

**Uso para Gano:** inspiración de **layout** (transport, EQ, visor), no parsear `.wmz` en producción. Mejor **recrear** en HTML/CSS (grid + bordes biselados + tipografía bitmap-like) o SVG con filtros.

---

## 3. Salvapantallas 3D Windows 95 / NT (OpenGL)

### 3.1 Familia icónica (nombres de producto)

Típicamente citados junto a OpenGL temprano:

1. **3D Flying Objects** — sólidos platónicos / Utah teapot, rotaciones, niebla.
2. **3D Maze** — laberinto procedural con texturas “psicodélicas” / ladrillo.
3. **3D Pipes** — crecimiento de tubos en grilla cúbica, ramificaciones, colores.
4. **3D Flower Box** — variantes florales / orgánicas (menos citada en blogs pero en el ecosistema NT samples).
5. **3D Text** — extrusión de texto (outline + depth).

### 3.2 Implementación histórica

- Contexto común: **OpenGL 1.x**, geometría **axis-aligned**, mucha **proceduralidad** (semillas, PRNG), colisiones simples con grilla.
- Código de referencia (no redistribuir sin licencia): aparecía en **Windows SDK / MSVC** bajo rutas tipo `MSTOOLS\SAMPLES\OPENGL\SCRSAVE` (NT 4.0); discusiones en foros ([Ars thread](https://arstechnica.com/civis/threads/opengl-3d-pipes-code.385970/)), [BetaArchive](https://www.betaarchive.com/forum/viewtopic.php?t=20417).

### 3.3 Réplicas web abiertas (auditar licencia de cada repo)

- [1j01/pipes](https://github.com/1j01/pipes) — **Three.js**, remake educativo de **3D Pipes**; README apunta al sample NT.
- [Alex313031/webgl-pipes](https://github.com/Alex313031/webgl-pipes) — fork WebGL / Three.js.
- Artículo contextual: [Tech Nostalgia — Pipes & Maze](https://techsnostalgia.com/windows-3d-pipes-maze-screensaver/) (algoritmos, marketing, OpenGL).

**Para Gano:** tratar estos repos como **referencia de algoritmo**; reescribir con vuestros colores Gano, densidad de tubos, y módulos propios.

---

## 4. “Misma lógica” pero más rica: Milkdrop / Butterchurn (viz audio WebGL2)

WMP ≠ Milkdrop, pero **culturalmente** es el bucket “visualización de música” que hoy es viable en web con stack abierto:

- [Butterchurn](https://github.com/jberg/butterchurn/) — WebGL2, **MIT**, implementación de **Milkdrop**; presets `.milk` / paquetes npm `butterchurn-presets`.
- [butterchurnviz.com](https://butterchurnviz.com/)

**Cuándo usarlo:** hero reactivo a audio, páginas evento, “modo DJ”; **cuándo no:** landing legal/compliance (CPU/GPU, consentimiento de micrófono, `prefers-reduced-motion`).

---

## 5. Low poly + 3D assets (ampliación coherente)

| Recurso | Uso web | Notas |
|--------|---------|--------|
| **Blender** (shade flat, vertex color, triangulate) | export **glTF** | Look PS1/N64/Win95 promo |
| **Three.js + drei** | `<Canvas>`, helpers, `MeshTransmissionMaterial` | Integración React |
| **R3F + postprocessing** | bloom barato, CRT, scanlines | Con moderación |
| **Spline / Vectary** | prototipo rápido | export y optimizar |
| **Kenney.nl / Poly Pizza / Quaternius** | props CC0 | revisar licencia por paquete |
| **Shadertoy** | estudio de shaders | portar a Three con atribución si aplica |

**Estética “screensaver” en CSS puro:** degradados cónigos, `conic-gradient`, `blur()` + `mix-blend-mode`, `clip-path` poligonal — para fondos si no hace falta WebGL.

---

## 6. Legal / marca (resumen práctico)

- **No** redistribuir binarios `.scr`, texturas originales, ni logotipos Windows/Microsoft.
- **Sí** reimplementar **algoritmos** (laberinto en grilla, crecimiento de tubos, extrusión de texto) y paletas **originales** inspiradas en la época.
- Texto comercial: “inspirado en la era de los salvapantallas OpenGL” en vez de “Windows 95®” como claim principal.

---

## 7. Catálogo de posibilidades web (realistas)

| # | Patrón UI | Técnica principal | Esfuerzo |
|---|-----------|-------------------|---------|
| 1 | Fondo hero “tubos” | Three.js instancing + grid graph | Alto |
| 2 | Fondo hero “laberinto” tórico | Raymarching o mesh + fog | Alto |
| 3 | Fondo “objetos voladores” | Rigid bodies ligeros o keyframes | Medio |
| 4 | Separador de sección tipo “pipe segment” | SVG + CSS anim | Bajo |
| 5 | Loader “crecimiento de tubo” | Canvas 2D | Medio |
| 6 | Loader “maze corner” | SVG stroke-dashoffset | Bajo |
| 7 | Iconografía 3D isométrica | Blender → glTF estático | Medio |
| 8 | Cards con bisel Win95-ish | CSS `box-shadow` inset + 1px borders | Bajo |
| 9 | Player chrome ficticio | HTML/CSS (sin WMZ) | Medio |
| 10 | Ecualizador falso (no audio) | CSS keyframes | Bajo |
| 11 | Ecualizador real | Web Audio + Canvas bars | Medio |
| 12 | Visual “Milkdrop lite” | Butterchurn + audio consent | Alto |
| 13 | Partículas low poly | Points + custom depth | Medio |
| 14 | “Wireframe city” | Instanced cubes | Medio |
| 15 | CRT / scanlines overlay | CSS + `backdrop-filter` | Bajo |
| 16 | Glitch data-mosh textual | shaders / canvas | Medio |
| 17 | Cursor trail geométrico | Canvas pointer | Bajo |
| 18 | Scroll-linked camera | Three + ScrollTrigger | Alto |
| 19 | Transición página “pipe wipe” | WebGL mask | Alto |
| 20 | Modo “screensaver” opcional | página `/ambient` + timeout | Medio |
| 21 | Mapa estático “maze thumb” | SVG generado server-side | Medio |
| 22 | OG image 3D | headless Three render | Alto |
| 23 | Lottie *inspirado* (flat) | After Effects → lottie | Medio |
| 24 | ASCII / half-block | canvas text grid | Bajo |
| 25 | WebGPU path (2026+) | compute particles | Muy alto |

---

## 8. Flujo de trabajo completo (visual + textual)

### Fase A — Descubrimiento (1–3 días)

1. **Moodboard** con referencias legales (capturas propias, sketches, reimplementaciones MIT).
2. **Mapa de superficies** del sitio: qué páginas toleran WebGL (home, /sota-hub) vs cuáles deben ser ultra ligeras (legal, checkout copy).
3. **Audio policy:** ¿solo `<audio>` interno?, ¿micrófono?, ¿sin audio (viz “falso”)?

### Fase B — Concepto técnico

1. Elegir **1 técnica hero** (p. ej. pipes *o* maze, no ambos a la vez en mobile).
2. Definir **budget**: triángulos máx, draw calls, `dpr` cap, pausa en pestaña oculta.
3. **A11y:** `prefers-reduced-motion` → sustituir por gradiente estático o canvas 2D minimal.

### Fase C — Prototipo (spike)

1. Repo ejemplo Three (o fork educativo) aisladamente.
2. Medir **LCP/INP** con hero desactivado vs activado.
3. Gate: si INP > umbral en mobile median, degradar.

### Fase D — Producción integrada

1. Web component o bloque React embebido solo donde Elementor permita script.
2. **Fallback** SSR: imagen estática generada (OG / poster).
3. Feature flag (`gano_ambient_webgl` option) para apagar en Black Friday / incidentes.

### Fase E — Texto / storytelling

1. **Microcopy honesto:** “visualización procedural inspirada en demos OpenGL de los 90” (no “es el salvapantallas de Windows”).
2. **Glosario** (como ya hacéis en catálogo SOTA): términos FFT, preset, procedural.
3. **SEO:** no keyword-stuff marcas; sí “low poly”, “procedural”, “WebGL hero”.

### Fase F — Pipeline de assets propios

1. Blender kitbash → glTF → `gltf-pipeline` / `meshopt`.
2. Versionado en repo `assets/3d/` + CDN cache bust.
3. **Atribución** de terceros en `humans.txt` o pie de página si aplica.

---

## 9. Prompts sugeridos (para otras IAs / scraping futuro)

- “List open source WebGL screensaver clones similar to Windows NT OpenGL samples, with license and last commit year.”
- “Map Web Audio API analyser features to Milkdrop variables for Butterchurn preset authoring.”
- “Collect academic papers on maze generation algorithms used in 1990s demos.”

---

## 10. Nota sobre “agentes”

En esta sesión el subagente de exploración no pudo ejecutarse por límite de API; la síntesis anterior combina **búsqueda web** + conocimiento de ingeniería. Reintentar `Task` explore en ventana nueva si necesitáis más fuentes académicas o scraping dirigido a documentación offline (MSDN DVD).
