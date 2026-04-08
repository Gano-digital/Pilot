# Plan — Diseño y fine tuning Battle Map (Galactic Map / Constellation)

**Versión del plan:** 2026-04-07  
**Archivo vivo:** `memory/constellation/CONSTELACION-COSMICA.html`  
**Inventario:** `INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`  
**Investigación UX:** `memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`

---

## 1. Estado actual (baseline)

| Capa | Implementado |
|------|----------------|
| **3D** | Three.js + bloom, órbitas, cuerpos `SYSTEMS`, sol, raycast |
| **HUD** | Leyenda 01–08, chips por facción, tooltip, panel briefing (secciones 01–07) |
| **Audio** | Pools `SFX_NAV`, `SFX_STAR`, `SFX_RACE`, mute + `localStorage` |
| **Datos** | `SYSTEMS`, `BRIEFS`, `TAXONOMY`, `SC_PORTRAITS` en JS inline |
| **Accesibilidad** | `prefers-reduced-motion`, hover SFX limitado en móvil |

**Deuda explícita:** datos y SFX acoplados al HTML (~3k líneas); tuning requiere editar módulo grande.

---

## 2. Objetivos de diseño (fine tuning)

| ID | Objetivo | Criterio de “hecho” |
|----|----------|---------------------|
| D1 | **Legibilidad** | Contraste WCAG razonable en panel + chips; tamaños mínimos en 1280px |
| D2 | **Ritmo** | SFX no saturan: gaps actuales respetados; volumen maestro documentado |
| D3 | **Coherencia facción** | Terran / Protoss / Zerg alineados a retratos + pools (`INVENTARIO`) |
| D4 | **Mantenimiento** | Config externa opcional (`battle-map-config.example.json`) para iterar sin diff gigante |
| D5 | **Performance** | 60fps objetivo en desktop; degradación aceptable en integrada |

---

## 3. Fases de implementación

### Fase 0 — Listo para empezar (esta semana)

- [ ] Abrir `CONSTELACION-COSMICA.html` en navegador local (`file://` o `npx serve memory/constellation`).
- [ ] Verificar rutas relativas: `assets/portraits/`, `sounds/agentcraft/`.
- [ ] Probar mute, click sol/planetas, panel briefing, leyenda.
- [ ] Revisar `battle-map-config.example.json` y decidir si el siguiente paso es **cargar JSON** (Fase 2) o **solo tuning CSS/JS** (Fase 1).

**Agente principal:** Diego + Cursor (ediciones acotadas).

---

### Fase 1 — Fine tuning UX (1–2 iteraciones)

| Tarea | Ámbito | Notas |
|-------|--------|--------|
| 1.1 Paleta / tokens | `:root` CSS | Ajuste fino `--accent`, `--line`, brillo panel |
| 1.2 Tipografía | `Space Grotesk` / `JetBrains Mono` | Escala mínima en `.chip`, `.panel` |
| 1.3 Panel briefing | Secciones reveal | Velocidad typewriter, `prefers-reduced-motion` |
| 1.4 SFX | `sfxMasterGain`, gaps | Constantes al inicio del bloque SFX (`HOVER_SFX_MIN_GAP_MS`, etc.) |
| 1.5 Tooltip | Posición | Evitar salida de viewport en bordes |

**Agentes:** Cursor / Copilot en rama `copilot/cx-*` existente o `fix/constellation-fine-tuning` (feature pequeña).

---

### Fase 2 — Datos y audio data-driven (cuando Fase 1 estabilice)

- [ ] Copiar `battle-map-config.example.json` → `battle-map-config.json` (**gitignore local** si contiene notas internas).
- [ ] Implementar `fetch('battle-map-config.json')` con **fallback** a constantes actuales si falla (offline).
- [ ] Opcional: separar `SYSTEMS` / `BRIEFS` en `.json` bajo `memory/constellation/data/`.

**Agentes:** PR dedicado; validar que no rompe `file://` (CORS: servir vía servidor local).

---

### Fase 3 — Battle Map “agentes ↔ tareas” (evolutivo)

- [ ] Mapeo explícito nodo `SYSTEMS[i]` → issue GitHub / tarea `TASKS.md` (tabla en markdown).
- [ ] Iconografía 3D opcional: billboards adicionales por “tipo de proceso” (sin rips).
- [ ] Export screenshot / estado para notas Obsidian (manual o script).

**Agentes:** Claude dispatch (docs); Copilot (issues oleada Constellation).

---

## 4. Alineación de agentes (quién hace qué)

| Rol | Responsabilidad | Herramienta / artefacto |
|-----|-----------------|-------------------------|
| **Diego** | Prioridad D1–D5, merge, copy en `BRIEFS` | Navegador + Obsidian |
| **Cursor (IDE)** | Edits en HTML/CSS/JS, PR | Rama `feature/constellation-*` |
| **GitHub Copilot** | Oleadas `copilot/cx-*` ya abiertas; no duplicar PRs | `.github/agent-queue/`, `MERGE-PLAYBOOK` |
| **Claude / dispatch** | Documentación, tablas de mapeo tarea↔nodo | `memory/claude/` |
| **Reglas** | Límites anti-rewrite | `.cursor/rules/102-constellation-and-gano-skills.mdc` |

**Regla de oro:** un PR por **una** fase (1.x o 2); evitar mezclar data-driven + redesign total.

---

## 5. Definición de listo (Go / No-Go)

**Go a Fase 2** cuando: Fase 1 checklist cerrada o explícitamente pospuesta; no hay regresiones visibles en panel.

**Go a producción estática** (subir HTML a hosting estático / GitHub Pages de prueba): rutas relativas verificadas; sin secretos en JSON.

---

## 6. Changelog del plan

| Fecha | Cambio |
|-------|--------|
| 2026-04-07 | Plan inicial + ejemplo JSON + marca build en HTML |
