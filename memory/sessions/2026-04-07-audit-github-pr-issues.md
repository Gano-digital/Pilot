# Auditoría GitHub — PRs, issues, estado local (2026-04-07)

Generado tras verificación del remoto `origin` (`Gano-digital/Pilot`) y estado del repo local.

## Estado local (workspace)

| Dato | Valor |
|------|--------|
| Rama actual | `docs/memoria-fase4-clarificacion-reseller-2026-04-06` (sincronizada con `origin`) |
| Último commit (HEAD) | `45b314a0` — [Phase 4] Feat: Add Antigravity tasks to dispatch queue (ag-phase4-001 to 004) |
| Sin cambios staged | Working tree limpio salvo **untracked** `memory/constellation/` (`00-PROYECTO-CONSTELACION-INDICE.md`, `01-MAPA-ESTRATEGICO.md`) — decidir si se versionan (Obsidian) o se ignoran en `.gitignore` |

## Pull requests abiertos

| # | Título | Rama → | Estado |
|---|--------|--------|--------|
| **136** | docs(memoria): clarificación Fase 4 — Reseller Store canónico vs Developer API opcional | `docs/memoria-fase4-clarificacion-reseller-2026-04-06` → `main` | **Abierto**; `mergeable_state: blocked` al momento de la auditoría |

### PR #136 — bloqueos detectados (auditoría 2026-04-07)

1. **Reglas del repositorio:** merge rechazado (`405`) con mensaje *“Code quality results are pending for one analyzed language”* — esperar a que **CodeQL** (jobs *Analyze python* / *Analyze javascript-typescript*) y el workflow **Agent** terminen en verde.
2. **Revisor solicitado:** `Copilot` — puede exigirse aprobación antes del merge.
3. **Checks de terceros (histórico):** en esa fecha podía aparecer un contexto de despliegue externo ya no usado como gate; **política actual:** ignorar para merge si la app está desvinculada; revisar **Settings → Rules → main** solo para checks que el equipo quiera obligatorios.

**Acción recomendada:** Cuando CodeQL y Agent estén en verde, reintentar **Squash merge** en [PR #136](https://github.com/Gano-digital/Pilot/pull/136). Si sigue bloqueado, revisar **Settings → Rules → main** (checks requeridos).

## Issues abiertos

| # | Título | Asignados | Notas |
|---|--------|-----------|--------|
| **29** | [agent] Checklist: subir parches Fase 1–3 si faltan en servidor | Ouroboros1984, Copilot | **Trabajo humano/servidor:** comparar `main` con archivos en producción (wp-config, mu-plugins, gano-child). No cerrable por merge en GitHub solo. |

No hay otros issues abiertos en el listado consultado.

## Qué falta avanzar (priorizado)

1. **Merge PR #136** cuando CI/reglas lo permitan (documentación Fase 4 / Reseller vs API).
2. **Issue #29:** sesión SSH/Panel: diff o despliegue de parches Fase 1–3 según `TASKS.md`.
3. **Opcional:** `git add` / commit de `memory/constellation/` si el índice Obsidian debe vivir en `main`, o añadir patrón a `.gitignore` si es solo local.

## Checks que sí pasaron (última muestra)

- `trufflehog` — success  
- `labeler` — success  
- `Analyze (python)` — success  

---

*Revisar de nuevo en GitHub Actions si los jobs *Agent* o *Analyze (javascript-typescript)* siguen en curso.*
