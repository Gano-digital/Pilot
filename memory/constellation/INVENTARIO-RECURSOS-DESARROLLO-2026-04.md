# Inventario de recursos — Constellation / Battle Map (desarrollo)

**Fecha:** 2026-04-07  
**Propósito:** catálogo único para **usar y modificar** assets ya presentes o enlazados; guía de ampliación sin perder el hilo.

---

## 1. Estrategia actualizada (contexto Diego)

| Concepto | Decisión |
|----------|----------|
| **Herramienta** (Constellation, mapa, HUD interno) | Uso **personal / equipo**, sin modelo de negocio sobre el diseño UX de esta herramienta. |
| **Lucro** | Proviene del **sitio y producto Gano** (hosting, etc.), **no** de vender ni licenciar el pack visual de Constellation. |
| **Repositorio Pilot** | Si el remoto es **público**, conviene seguir evitando subir binarios dudosos o carpetas masivas de extracción; lo **sí versionado** son scripts, HTML, PNG ya acordados y sonidos bajo `memory/constellation/sounds/agentcraft/`. |
| **Copyright** | Sigue existiendo sobre materiales de juegos; el hecho de ser “no comercial” o “solo para mí” **no borra** derechos de autor, pero **cambia el perfil de riesgo** frente a un producto público masivo. Extracciones directas del juego: **solo entorno local**, según tu interpretación de EULA personal; **no** asumir redistribución. |
| **AgentCraft + Kenney** | Priorizar expansiones desde **`vendor/agentcraft/curated-sounds`** y **`assets/kenney-rts-pack`** / **`open-rts-pack`** cuando necesites piezas **claras para remix** frente a dumps grandes bajo `Zerg/` en vendor. |

**En una frase:** la herramienta es tu **mesa de trabajo**; el sitio es la **vitrina**. El inventario prioriza lo que ya está integrado o curado para desarrollo.

---

## 2. Mapa rápido de carpetas

| Área | Ruta | Rol |
|------|------|-----|
| **Mapa principal** | `memory/constellation/CONSTELACION-COSMICA.html` | HUD, SFX, chips, panel |
| **Visualizador** | `memory/constellation/VISUALIZADOR-INTERACTIVO.html` | Grafo / vista alternativa |
| **Documentación narrativa** | `memory/constellation/0x-*.md`, `MAPA-VISUAL-INTERACTIVO.md` | Roadmap, agentes, riesgos |
| **Audio en uso en git (mapa)** | `memory/constellation/sounds/agentcraft/` | **29** WAV; pools en JS del HTML |
| **Retratos estáticos (mapa)** | `memory/constellation/assets/portraits/` | 3 PNG worker por facción |
| **Investigación UX** | `memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md` | Patrones Metro / RTS / BioShock |
| **Pipeline SC1** | `memory/research/starcraft1-assets-sota.md` | GRP, IronGRP, legal |
| **AgentCraft (vendor)** | `vendor/agentcraft/` | Sonidos extra, UI PNG, Kenney, clones locales |

---

## 3. Audio — inventario en `memory/constellation/sounds/agentcraft/` (29 archivos)

Copiar o referenciar desde el HTML con `SFX_BASE = 'sounds/agentcraft/'`.

**Terran / UI genérica**

| Archivo | Notas |
|---------|--------|
| `TSCRdy00.wav` | Listo / UI |
| `TSCYes00.wav`, `TSCYes01.wav`, `TSCYes03.wav` | Confirmaciones |
| `TSCUpd00.wav` | Actualización / abrir |
| `marine-TMaRdy00.wav` | Marine |
| `ghost-tghrdy00.wav`, `ghost-tghyes00.wav` | Ghost |
| `tank-ttayes00.wav` | Tanque |
| `vulture-tvuyes00.wav` | Vulture |
| `tadErr01.wav`, `tadUpd02.wav` | Advisor Terran |

**Protoss**

| Archivo | Notas |
|---------|--------|
| `probe-pprYes00.wav`, `pprRdy00.wav` | Probe |
| `pzeRdy00.wav` | Zealot ready |
| `zealot-PZeYes00.wav` | Zealot |
| `dragoon-PDrYes00.wav` | Dragoon |
| `shuttle-PShYes00.wav` | Shuttle |
| `PAdUpd02.wav`, `PAdUpd06.wav` | Panel / advisor |
| `PAdErr00.wav`, `PAdErr06.wav` | Errores |

**Zerg**

| Archivo | Notas |
|---------|--------|
| `zergling-ZZeRdy00.wav`, `zergling-ZZeYes00.wav` | Zergling |
| `hydra-zhyRdy00.wav` | Hidra |
| `zdrRdy00.wav`, `zdrYes01.wav` | Drone |
| `ZAdErr00.wav`, `ZAdErr06.wav` | Errores Zerg |

*Ampliación:* hay **~75 WAV** adicionales en `vendor/agentcraft/curated-sounds/` (incl. `battle-tbardy`, `goliath`, `carrier`, `scout`, `mutalid`, `overlord`, etc.) — copiar al subir nuevos pools al HTML.

---

## 4. Imágenes — retratos y UI

### En mapa (`memory/constellation/assets/portraits/`)

| Archivo | Uso |
|---------|-----|
| `portrait-worker-terran.png` | Chip Terran |
| `portrait-worker-zerg.png` | Chip Zerg |
| `portrait-worker-protoss.png` | Chip Protoss |

### Origen espejo (AgentCraft)

`vendor/agentcraft/assets/starcraft-local-from-clone/` — **23 PNG** (mismos retratos + piezas UI: `ui-bottom-*`, `ui-top-*`, `minimap-frame`, `command-*`, `unit-light`, `unit-heavy`, `terrain-*`, `icon-minerals`, etc.). Útiles para **iterar en local** o exportar variantes; si Pilot es público, decidir qué subconjunto merece estar en `memory/constellation/assets/`.

### Kenney / Open RTS (vendor)

- `vendor/agentcraft/assets/kenney-rts-pack/` — tiles, comandos (licencia Kenney según paquete).
- `vendor/agentcraft/assets/open-rts-pack/` — SVG/PNG genéricos.

**Modificación:** edición en GIMP/Figma; retratos animados: pipeline en `starcraft1-assets-sota.md` (GRP → PNG) solo en máquina local si aplica.

---

## 5. Unidades — ¿podemos usarlas?

| Tipo de “unidad” | En el repo | Uso recomendado |
|-------------------|------------|-----------------|
| **Metáfora** (SCV = tarea operativa, Observer = auditoría) | Siempre | Libre; es diseño de producto. |
| **Sonido asociado a unidad** (nombre en archivo) | WAV en `sounds/agentcraft/` + curated | Sí para **feedback** en tu herramienta; ampliar desde `curated-sounds` copiando a `memory/.../agentcraft/` cuando quieras versionarlo en el mapa. |
| **Sprites 2D de unidades del juego** | No en `memory/` como spritesheet GRP | Generar localmente o usar PNG de `starcraft-local-from-clone` / remix Kenney según tu criterio de riesgo. |
| **Modelos 3D del juego** | No recomendado en repo | Blender **original** para iconos 3D en Battle Map. |

---

## 6. Próximos pasos de desarrollo (paralelos)

1. **JSON de mapeo** `ui-event → archivo.wav` (opcional) junto al HTML para editar sin tocar JS.
2. **Promoción selectiva:** de `vendor/agentcraft/curated-sounds/` → `memory/constellation/sounds/agentcraft/` solo los que entren en pools nuevos.
3. **Inventario vivo:** al añadir un WAV, una línea en la tabla §3 de este archivo.

---

## 7. Referencias cruzadas

- `memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`
- `.gano-skills/gano-starcraft1-assets-constellation/SKILL.md`
- `.cursor/memory/activeContext.md`
- **Plan diseño + fine tuning + fases agentes:** `BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md`
- **Config ejemplo (data-driven):** `battle-map-config.example.json`
