# Skill — Assets StarCraft 1 / Constellation HUD (Gano Digital)

**Última actualización:** 2026-04-02  
**Estado:** operativo (referencia técnica + guardrails)  
**Especialización:** localizar formatos y pipelines para retratos/sprites/audio inspirados en SC1 sin violar licencias; alinear con `memory/constellation/`.

---

## Cuándo usar esta skill

- Trabajas en **`CONSTELACION-COSMICA.html`**, leyenda, SFX AgentCraft, o futuras animaciones de retrato.
- Preguntan por **GRP, paletas, MPQ** o “portraits animados del juego”.
- Hay que decidir **qué va al repo** vs **solo máquina local**.

---

## Qué sabemos (resumen ejecutivo)

1. **StarCraft 1** guarda gráficos sobre todo en **GRP + paleta externa** (`.pal` / PCX); los retratos animados son **secuencias de frames**, no vídeo.
2. El proyecto **no** incluye frames GRP de Blizzard; usa **PNG estáticos** (AgentCraft / starcraft-local) y **CSS** para movimiento.
3. **Audio** en el mapa: WAV bajo `memory/constellation/sounds/agentcraft/` con pools por facción (ver código del HTML).
4. **Herramientas útiles** (solo en entorno de desarrollo, con copia legal del juego si aplica extracción):
   - [IronGRP](https://github.com/sjoblomj/irongrp) — GRP ↔ PNG + `.pal`
   - [PyMS](https://github.com/poiuyqwert/PyMS) — PyGRP, PyPAL, PyMPQ, etc.
   - [PalEdit](https://github.com/benbaker76/PalEdit) — paletas

Investigación extendida: **`memory/research/starcraft1-assets-sota.md`**.

---

## Checklist antes de añadir “assets del juego”

- [ ] ¿El archivo es **redistribuible** según licencia? Si es de `stardat.mpq` / CD / instalación Battle.net → **no** commitear en git público sin permiso explícito.
- [ ] ¿Ya existe equivalente en **AgentCraft** `vendor/agentcraft/` o PNG propio?
- [ ] Si exportas GRP→PNG: ¿documentaste **solo** el pipeline (comandos) y no los binarios?

---

## Pipeline recomendado (web)

1. **GRP → PNG** (IronGRP) con la **paleta correcta** del contexto (unidades vs UI).
2. Seleccionar N frames (p. ej. ciclo de “idle”).
3. Combinar en **spritesheet** o cargar secuencia en `<canvas>` / CSS `steps()` si el peso es aceptable.
4. Integrar en el mismo patrón que `PORTRAIT_BASE` en el HTML (sin romper CSP si se sirve desde el mismo origen).

---

## Integración con otras skills

| Skill | Relación |
|-------|----------|
| `gano-multi-agent-local-workflow` | Git sin secretos; no subir MPQ/GRP |
| `gano-blender-3d-assets-generator` | Alternativa **no-SC**: retratos 3D render a PNG |
| Contenido constellation | `memory/constellation/blizzard-press-reference.txt` para prensa Blizzard |

---

## Errores comunes a evitar

- Asumir que **Games Press** autoriza empaquetar sprites en el tema WordPress.
- Mezclar assets **StarCraft II** con expectativa de estética **SC1 640×480**.
- Commitear **vendor completo** de herramientas de extracción con binarios del juego dentro.

---

## Actualización

Si cambia el manifiesto de sonidos o retratos, actualizar:

- `memory/research/starcraft1-assets-sota.md` (brecha técnica)
- Comentarios en `CONSTELACION-COSMICA.html` bloque SFX / `SC_PORTRAITS`
