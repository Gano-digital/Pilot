# StarCraft 1 / Brood War — inventario de assets y brecha con Gano Constellation (SOTA práctico)

**Fecha:** 2026-04-02  
**Alcance:** investigación técnica para localizar **qué recursos existen**, en **qué formato**, y **qué nos falta** para el mapa `memory/constellation/CONSTELACION-COSMICA.html` y experiencias HUD similares.  
**No** constituye asesoría legal; el juego y sus archivos son propiedad de Blizzard Entertainment.

---

## 1. Taxonomía de assets en StarCraft (1998) / Brood War

| Categoría | Uso en el juego | Formatos típicos | Notas |
|-----------|-----------------|-------------------|--------|
| **Sprites unidad/edificio** | Mapa isométrico | `.grp` (índices de paleta, RLE o sin comprimir) | No guardan RGB; requieren `.pal` / PCX de paleta |
| **Retratos (briefing + in-game)** | Diálogos, HUD | Mismos `grp` + scripts de animación / `iscript` | Varios frames por unidad; “boca” clásico = secuencia de frames |
| **Interfaz** | Menús 640×480, botones | `grp`, tilesets, a veces recursos en MPQ | Paletas dedicadas (p. ej. escenas distintas) |
| **Audio** | Voces, UI, ambiente | `.wav` empaquetados (calidad 22 kHz mono típico BW) | Curación vía herramientas de modding / extracción con licencia de uso personal |
| **Datos** | Stats, armas, unidades | `.dat` binarios, tablas `.tbl` | PyMS / editores de mapa |
| **Contenedor** | Distribución en disco | MPQ (archivos Storm) | Lectura con StormLib / editores MPQ (respetar EULA) |

**Conclusión:** el “retrato animado” clásico **no es un vídeo**: es una **secuencia de frames GRP** + paleta compartida + timing en motor.

---

## 2. Herramientas de referencia (comunidad, activas)

| Herramienta | Rol | Enlace |
|-------------|-----|--------|
| **IronGRP** | CLI Rust: GRP ↔ PNG con paleta `.pal`; análisis de GRP | [github.com/sjoblomj/irongrp](https://github.com/sjoblomj/irongrp) |
| **PyMS** | Suite Python: PyGRP, PyPAL, PyPCX, PyMPQ, etc. | [github.com/poiuyqwert/PyMS](https://github.com/poiuyqwert/PyMS) |
| **PalEdit** | Edición de paletas `.pal`, `.pcx`, export a varios formatos | [github.com/benbaker76/PalEdit](https://github.com/benbaker76/PalEdit) |
| **StarCraft: Remastered** | Assets en pipeline distinto (HD); herramientas tipo **animosity** (mencionadas en irongrp para SCR) | Evaluar solo si el objetivo es SCR, no BW clásico |

Para **web** (Three.js / HTML): el flujo realista es **GRP → PNG por frame** (IronGRP + paleta correcta) → **spritesheet** o **secuencia numerada** → CSS/`requestAnimationFrame` o textura atlas.

---

## 3. Qué usa hoy el proyecto Gano (consolidado)

| Necesidad | Implementación actual | Origen |
|-----------|------------------------|--------|
| Retratos HUD | PNG estáticos por facción | `memory/constellation/assets/portraits/` + `vendor/agentcraft/.../starcraft-local-from-clone` |
| “Animación” retrato | CSS (`portraitLive`, `portraitIdle` en chips) | Original; **no** hay frames GRP en repo |
| Sonidos HUD | WAV curados por raza/categoría | `memory/constellation/sounds/agentcraft/` (subset de AgentCraft + pools) |
| Patrones UI menú RTS | Variables CSS, bisel, briefing bar | Inspiración conceptual; sin sprites de menú Blizzard en repo |
| Prensa / moodboard | Solo referencia | [Blizzard Games Press – StarCraft](https://blizzard.gamespress.com/StarCraft#?tab=screenshots-1&scrollto=) — ver `memory/constellation/blizzard-press-reference.txt` |

---

## 4. Brecha: recursos que “faltan” para fidelidad alta

| Recurso | Estado | Cómo obtenerlo sin ensuciar el repo |
|---------|--------|-------------------------------------|
| Frames GRP de un worker/unidad concreta | No versionados | Instalación legal del juego + extracción local + IronGRP → PNG; **no** commitear binarios Blizzard |
| Paleta `units.pal` (o equivalente) | Necesaria para export fiel | Incluida en datos del juego; usar solo en máquina de desarrollo |
| Spritesheet web optimizado | Por generar | Pipeline: PNG frames → `texturepacker` / script → WebP/PNG atlas |
| Audio adicional por unidad | Parcialmente cubierto | Más muestras desde **AgentCraft curated-sounds** en `vendor/agentcraft/` (licencia del proyecto AgentCraft, no Blizzard) |
| UI menú BW completa | No replicada pixel-perfect | Opciones: (a) diseño original inspirado (actual), (b) capturas de referencia locales sin redistribución |

---

## 5. Riesgos legales y de producto

- Redistribuir archivos extraídos del juego en un repo o sitio público **puede violar** EULA y copyright.
- **Prensa Blizzard** tiene términos propios; no asumir uso comercial en `gano.digital` sin revisión.
- **AgentCraft / starcraft-local** en vendor: seguir la licencia del proyecto upstream; no confundir con “dominio público Blizzard”.

---

## 6. Recomendaciones operativas (orden)

1. Mantener **arte original + AgentCraft** en git; cualquier GRP→PNG **fuera del repo** o en almacenamiento privado del equipo.  
2. Si se necesitan frames: **IronGRP** + paleta correcta → revisar calidad por frame antes de atlas.  
3. Documentar en el PR/commit solo **scripts y documentación**, no `.grp` ni MPQ.  
4. Para marketing: priorizar **Games Press** o material propio / Remastered según términos aplicables.

---

## 7. Referencias cruzadas en el workspace

- Skill operativa: `.gano-skills/gano-starcraft1-assets-constellation/SKILL.md`  
- Mapa interactivo: `memory/constellation/CONSTELACION-COSMICA.html`  
- Nota prensa Blizzard: `memory/constellation/blizzard-press-reference.txt`  
- UX multi-título + S.T.E.A.L. + mapeo SFX AgentCraft: `memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`  
- Inventario WAV/PNG/unidades y estrategia herramienta privada: `memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`
