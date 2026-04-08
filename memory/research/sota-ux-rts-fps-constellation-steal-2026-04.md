# Investigación SOTA — UX Metro / StarCraft 1 / Warcraft 2–3 / BioShock → Constellation & Battle Map

**Fecha:** 2026-04-07  
**Marco:** **S.T.E.A.L.** = *Strategically Take Elements And Learn* (adaptado): **extraer patrones de diseño, jerarquía y feedback**, **reimplementar en código y arte original o licencias seguras**, y **trasladarlos** al proyecto Gano (Constellation, Battle Map, HUD).  
**No** constituye asesoría legal. Los universos citados son propiedad de sus titulares (Deep Silver / 4A, Blizzard, 2K, etc.).

---

## 0. Guardarraíles y estrategia (actualizado 2026-04-07)

**Contexto:** la herramienta Constellation / Battle Map es de **uso personal (Diego)**, sin ánimo de lucro sobre el diseño UX de esa herramienta; el **lucro** corresponde al **sitio y negocio Gano**. Eso **no elimina** el copyright sobre materiales de terceros, pero orienta las decisiones: priorizar **mesa de trabajo interna**, expansiones desde **AgentCraft curated** y **Kenney**, y evitar empaquetar dumps masivos en un **repo público** si Pilot sigue siendo visible en GitHub.

**Inventario ejecutable:** tabla de WAV, PNG y rutas en [`memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`](../constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md).

| Riesgo | Postura del proyecto |
|--------|----------------------|
| Sprites, modelos 3D, audio y voces **extraídos** de juegos comerciales | **No redistribuir** en repo público sin criterio claro; uso local / herramienta privada según tu EULA y tolerancia al riesgo. Ver `memory/research/starcraft1-assets-sota.md` y skill `.gano-skills/gano-starcraft1-assets-constellation/`. |
| **Inspiración** (layout HUD, ritmo de feedback, codificación por color, affordances) | **Sí**, totalmente deseable: es diseño de producto, no copia de assets. |
| **Audio en repo** | Subconjunto versionado en `memory/constellation/sounds/agentcraft/`; ampliación desde `vendor/agentcraft/curated-sounds/`. |
| **3D en web** | Modelos **originales** (Blender → glTF/GLB) o **CC0**; no mallas rippeadas de MPQ/PK en releases públicos. |
| **Prensa / screenshots** | Uso acotado según términos del titular; ver `memory/constellation/blizzard-press-reference.txt`. |

**S.T.E.A.L. operativo:** llevar **ideas y sistemas** a otro lugar (vuestro HTML/CSS/JS y arte propio o curado), no asumir redistribución libre de binarios ajenos.

---

## 1. Patrones UX por título (qué robar conceptualmente)

### 1.1 Metro (2033 / Last Light / Exodus)

| Patrón | Qué aporta | Traducción a código / UI propia |
|--------|------------|----------------------------------|
| **Diegetic UI mínimo** (cronómetro, mascarilla, filtros en pantalla) | Menos chrome, más inmersión | Overlays con `backdrop-filter`, bordes finos, tipografía condensada; **no** copiar texturas de máscara. |
| **Inventario “realista”** | Peso limitado, sensación táctil | Límite de “slots” de agentes/tareas en panel lateral. |
| **Carga emocional por recursos** (munición, filtros) | Ansiedad controlada | Metáfora: “oxígeno” = tiempo hasta deadline; **variables CSS** por umbral (verde → ámbar → rojo). |
| **Menús lentos / notebook** | Ritmo pausado | Transiciones `cubic-bezier` largas en panel de briefing (ya alineable a “briefing SC”). |

**Assets visuales recreables:** ruido film grain (CSS `noise` SVG o canvas ligero), viñetas, scanlines sutiles (sin arte de Metro).

---

### 1.2 StarCraft 1 (+ Brood War)

| Patrón | Qué aporta | Traducción |
|--------|------------|------------|
| **Briefing bar + retrato + texto** | Contexto narrativo antes de acción | Panel inferior ya presente en Constellation; retratos con marco por facción. |
| **Codificación Terran / Protoss / Zerg** | Reconocimiento instantáneo | Chips por “raza” = **equipo o tipo de agente** (Terran=ops, Protoss=arquitectura, Zerg=exploración, etc.). |
| **SFX cortos en cada acción** | Confirmación auditiva | Pools WAV por hover/select/error (implementado con AgentCraft). |
| **Minimap mental** | Orientación | Battle Map como grafo + minimapa espejo (2D canvas). |
| **Portraits** | Identidad de unidad | Secuencia de frames o loop sutil (CSS / spritesheet); ver §4. |

**Formatos técnicos:** GRP + paleta; pipeline IronGRP → PNG → web (documentado en research SC1).

---

### 1.3 Warcraft II

| Patrón | Qué aporta | Traducción |
|--------|------------|------------|
| **UI medieval / piedra y oro** (marcos ornamentados) | Legibilidad en baja res | Bordes **inspirados** con CSS `border-image` o SVG propio (no tileset Blizzard). |
| **“Work complete” / pings** | Cierre de micro-tarea | Sonido único de “done” por tipo de issue (un WAV distinto por severidad). |
| **Mapa de campaña** | Nodos desbloqueables | Battle Map: nodos = épicas/temas; aristas = dependencias. |

---

### 1.4 Warcraft III

| Patrón | Qué aporta | Traducción |
|--------|------------|------------|
| **Hero + inventario + habilidades** | Foco en un actor potente | Un “héroe” = proyecto o iniciativa; slots = sub-tareas. |
| **Quest log** | Lista de objetivos rastreables | Panel derecho tipo quest log con estados (activo / completado). |
| **Team color** | Coherencia en multijugador | Color por squad en chips del mapa. |
| **Cinemáticas breves** | Hitos | Transiciones full-screen ligeras al cerrar épica (CSS, no vídeo con IP). |

**3D:** WC3 usó modelos en motor propio; en web, **no** importar `.mdx` rippeados; usar **glTF** propios (§5).

---

### 1.5 BioShock (1 / Infinite)

| Patrón | Qué aporta | Traducción |
|--------|------------|------------|
| **Arte déco / steampunk UI** | Estética única | Paleta y tipografía **inspiradas** (Art Deco geométrico, sin logos ni íconos del juego). |
| **Audio logs** | Narrativa desacoplada | “Logs” en panel: markdown + player de **audio libre** o TTS. |
| **Plasmids / elección** | Ramificación | Nodos del mapa con bifurcación visual (A/B). |
| **Farolas / señalética** | Guía espacial | En 3D: meshes **originales** tipo “faro” o “boya” para marcar hitos (no Big Daddy model). |

---

## 2. Matriz S.T.E.A.L. (elemento → destino Gano)

| Origen (idea) | Elemento | Destino en proyecto | Implementación segura |
|---------------|----------|---------------------|------------------------|
| Metro | Diegetic HUD | Constellation overlays | CSS variables, capas, sin assets IP |
| SC1 | Briefing + portrait | Panel + chips | HTML existente + ampliar frames/GIF propios |
| SC1 | Beeps UI | Hover/select | `sounds/agentcraft/*.wav` + tablas §3 |
| WC2 | Marco ornamentado | Contenedores de nodos | SVG/CSS original |
| WC3 | Quest log | Lista de tareas épica | Componente panel |
| BioShock | Guía lumínica | Marcadores 3D | glTF propio + luces Three.js |

---

## 3. StarCraft — audio existente en repo + semántica (base para ampliar)

**Fuente en código:** `memory/constellation/CONSTELACION-COSMICA.html` (`SFX_BASE`, pools) + carpeta `memory/constellation/sounds/agentcraft/`.

### 3.1 Convención de nombres (BW / AgentCraft)

Los nombres de archivo suelen codificar: **unidad abreviada** + **tipo de línea** (Rdy, Yes, Err, Upd, etc.). No son “transcripciones” de voz en texto; son **samples** para feedback inmediato.

| Archivo (ejemplo) | Lectura útil para UX | Uso sugerido en Battle Map / UI |
|-------------------|----------------------|----------------------------------|
| `TSCRdy00.wav`, `TSCYes00/01/03.wav` | Terran UI / comandante “sí / listo” | Hover suave, confirmación de click, menú |
| `TSCUpd00.wav` | Actualización de estado | Abrir panel, refresh de datos |
| `marine-TMaRdy00.wav`, `ghost-tghrdy00.wav` | Unidad lista | Pool Terran alternativo |
| `probe-pprYes00.wav`, `pprRdy00.wav` | Protoss worker | “Asignación” de tarea / agente Protoss |
| `PAdUpd02.wav`, `PAdUpd06.wav` | Advisor Protoss | Panel lateral, navegación |
| `PAdErr00.wav`, `PAdErr06.wav` | Error suave / fuerte | Validación fallida, bloqueo |
| `ZAdErr00.wav`, `ZAdErr06.wav` | Alerta Zerg | Riesgo, conflicto |
| `zergling-ZZeRdy00.wav`, `zergling-ZZeYes00.wav` | Zerg ligero | Exploración rápida |
| `zealot-PZeYes00.wav`, `dragoon-PDrYes00.wav` | Combate Protoss | Hitos críticos |
| `hydra-zhyRdy00.wav`, `tank-ttayes00.wav`, `vulture-tvuyes00.wav`, `shuttle-PShYes00.wav` | Unidades variadas | Diversidad por **tipo de proceso** (ver §6) |
| `tadErr01.wav` | Advisor Terran error | Advertencia media |

**Ampliación congruente:** añadir más WAV **solo** si la licencia del paquete lo permite y el archivo **no** es rip directo de Blizzard sin permiso; preferir **curación AgentCraft** o **sfx propios** grabados / sintetizados.

### 3.2 Dónde encajar más variedad (sin romper coherencia)

| Contexto UI | Familia de sonido | Notas |
|-------------|-------------------|--------|
| Menú principal / apertura | `TSCUpd`, `TSCRdy` | Ya parcialmente mapeado |
| Botón primario | `TSCYes*` | Confirmación corta |
| Botón secundario / cancelar | `PAdErr` suave o sample neutro propio | Evitar error agudo para “cancel” |
| Hover lista | Pool existente `hoverPool` | Throttle en JS (ya hay `lastHoverAudioKey`) |
| Éxito de tarea | `*Yes00` Terran o Protoss | Un solo “ding” por cierre |
| Fallo / bloqueo | `*Err*` coherente con facción del nodo | |
| Agente asignado | Sample de unidad **ligera** (zergling/probe) | Refuerzo metafórico |

---

## 4. Retratos animados: “hall”, GIF y pipeline GRP

| Enfoque | Pros | Contras |
|---------|------|---------|
| **CSS** (scale, brightness, scanline) | Ya en uso; barato | No es “boca” real |
| **Spritesheet + `steps()`** | Apariencia SC1 | Necesita export de frames (ver pipeline IronGRP) |
| **APNG / WebP animado** | Un archivo | Peso; editar menos flexible |
| **GIF** | Universal | Paleta pobre, peso |
| **Canvas + frame array** | Control total | Más código |

**“Hall”:** si se refiere a **salón de briefing** o **efecto holográfico**, implementar con **capas CSS** (brillo, scanlines, vignette) sobre PNG estático; si es typo de **HUD**, el patrón es el mismo.

**Recomendación:** mantener **PNG + CRT overlay** en repo; animación **breve** (idle) con 4–8 frames **originales** o export local no commiteado.

---

## 5. Elementos 3D “reales” para navegación y señalización

| Opción | Descripción | Licencia |
|--------|-------------|----------|
| **Three.js + glTF propio** | Naves “tipo ciencia ficción” **modeladas en Blender** (siluetas simples) | 100 % vuestro |
| **Instancing** | Muchas copias de “marcador” (cono, prisma) con color por equipo | Ligero en GPU |
| **Billboards** | Sprites que miran a cámara | Buen sustituto de unidades SC sin mallas pesadas |
| **Rip de modelos Blizzard** | No recomendado para producción pública | EULA |

**Señalización:** luces puntuales, “beacons” en coordenadas del grafo; **líneas** entre nodos como hyperlanes (Line2D o tubo delgado).

---

## 6. Metáfora Battle Map: unidades ↔ procesos / agentes

Propuesta creativa (ajustable): asignar **tipos de nodo** a **roles**, no a franquicias Blizzard.

| Metáfora | Rol en Gano | Feedback sugerido |
|----------|-------------|-------------------|
| **SCV / Probe / Drone** | Tarea operativa, setup | Sample `ppr` / `ZZeRdy` |
| **Observer / Science Vessel** | Auditoría, visibilidad | UI silenciosa + `TSCUpd` al revelar |
| **Shuttle / Dropship** | Handoff entre equipos | Transición + `PShYes` |
| **Carrier / Battlecruiser** | Iniciativa grande, varios sub-items | Sonido más largo, panel expandido |
| **Zergling** | Tarea rápida, exploración | `ZZe*` |
| **Hydra / Tank** | Bloqueo, fuego defensivo | `zhy` / `tta` en estados “críticos” |

Esto da **variedad congruente** sin necesidad de voces largas transcritas: el **nombre del archivo** ya es el vocabulario técnico.

---

## 7. Recursos del repo a actualizar cuando implementéis

- `CONSTELACION-COSMICA.html` — bloque `SFX_*` y pools por facción.
- `memory/research/starcraft1-assets-sota.md` — pipeline GRP.
- `.gano-skills/gano-starcraft1-assets-constellation/SKILL.md` — guardarraíles.
- Opcional: tabla **CSV/JSON** de mapeo `archivo → evento UI` para editores sin tocar JS.

---

## 8. Referencias externas (contexto, no redistribución)

- Documentación formatos y herramientas: PyMS, IronGRP (enlaces en `starcraft1-assets-sota.md`).
- Wikis de comunidad (quotes / líneas de unidad): útiles para **tono narrativo** en copy, no para empaquetar audio oficial.

---

## 9. Changelog

| Fecha | Nota |
|-------|------|
| 2026-04-07 | Versión inicial: SOTA multi-título, S.T.E.A.L., guardarraíles IP, mapeo WAV existentes, 3D, metáforas Battle Map. |
