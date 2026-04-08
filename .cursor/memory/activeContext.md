# Active Context — Estado Actual

_Última actualización: 2026-04-07 (investigación SOTA workflow paralelo + registro en `memory/research/`)_

## Foco actual (producto y repo)

- **Servidor / producción:** desplegar parches Fases 1–3 al hosting real, eliminar `wp-file-manager`, configurar Gano SEO / GSC / Rank Math (`TASKS.md` sección Active).
- **GitHub `Gano-digital/Pilot`:** repositorio en `main` ya consolidado; foco actual = cierre manual de issues cubiertos por `main`, uso selectivo de colas opcionales (API / security guardian) y verificación de accesos.
- **Homepage / Elementor:** copy desde `memory/content/`, menú primary, sustituir Lorem (issues `homepage-fixplan`).
- **Fase 4:** catálogo Reseller, mapeo CTAs en `shop-premium.php`, smoke test checkout (sin asumir estado de servidor no documentado).
- **Constellation / Battle Map:** plan de **diseño + fine tuning + fases + alineación agentes** — [`memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md`](../../memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md); config ejemplo JSON — [`battle-map-config.example.json`](../../memory/constellation/battle-map-config.example.json); `CONSTELACION-COSMICA.html` expone `window.__GANO_BATTLE_MAP__` (build/plan). Oleada GitHub `copilot/cx-*` sigue playbook; no duplicar PRs masivos.
- **Investigación SOTA UX:** [`memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`](../../memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md). **Inventario recursos:** [`memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`](../../memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md).

## Completado recientemente (entorno + gobernanza agentes)

- [x] **Setup Cursor:** rules **9** `.mdc` (001–006, 100–101, 200), memory protocol, PHP/WP, CSS/JS, git workflow, copilot oversight.
- [x] Memory bank (`.cursor/memory/`) operativo; `AGENTS.md` y flujo multi-agente documentados.
- [x] Skills proyecto en `.gano-skills/` (incl. orquestación Copilot + multi-agente local) alineadas con cola GitHub y prompts por oleada.
- [x] **Cola Claude dispatch:** tareas `cd-repo-001` a `cd-repo-012` completadas; checklists operativos creados en `memory/commerce/` y `memory/ops/`.
- [x] **PHP CLI local:** PHP 8.3 habilitado en Windows y `php -l wp-content/themes/gano-child/functions.php` pasa sin errores.
- [x] **Tooling opcional incorporado (sin romper runtime):**
  - Graphify seguro (skill `gano-graphify-local`) para mapas de arquitectura.
  - Agent Orchestrator (AO) opcional (skill `gano-agent-orchestrator-local`) para oleadas paralelas (recomendado WSL2).
  - ML‑SSD (Apple) como submodule `vendor/ml-ssd` + skill `gano-ml-ssd` (I+D / evaluación codegen).
- [x] **PR #136** (docs/memoria Fase 4) dejado **merge-ready** (conflictos resueltos, CI verde). Nota: el merge puede quedar bloqueado por ruleset de “Code Quality” en GitHub.

## En progreso

- [ ] SSH key / acceso: validar autorización real de la clave actual contra GitHub y servidor; el transporte responde, pero la autenticación por clave sigue fallando.
- [ ] SFTP / sync VS Code si aplica al flujo de deploy de Diego.
- [ ] Deploy de archivos críticos al servidor y tareas solo-wp-admin listadas en `TASKS.md`.

## GitHub — PRs abiertos (auditoría 2026-04-07)

Repo `Gano-digital/Pilot`: **9 PRs abiertos** (#136 + #145–#152). **Ninguno fusionable vía `gh merge` desde esta sesión:** ruleset org **«Gano.digital»** bloquea merge con **«Code quality findings»** (incluso `--admin` en #136). Además **#136** no puede autoaprobarse (autor = mismo usuario).

| PR | Rama | Estado | mergeable | Notas |
|----|------|--------|-----------|--------|
| 136 | docs/memoria-fase4… | OPEN | MERGEABLE pero **merge blocked** por política | CI: validate, TruffleHog, CodeQL jobs OK |
| 145–152 | copilot/cx-* | DRAFT | 147 **CONFLICTING**; resto MERGEABLE | Constellation oleada; squash sugerido |

**Desbloqueo manual en GitHub:** Security / Code scanning o reglas del PR → revisar o descartar hallazgos que exija el ruleset; u otra cuenta con permiso **aprueba** #136. Luego `gh pr merge 136 --squash` o botón Merge.

**#147:** antes de merge, actualizar rama con `main` (resolver conflictos) vía UI «Update branch» o merge local.

## Bloqueadores

- **SSH autorización:** `origin` responde por HTTPS, pero `ssh` a `git@github.com` y al host `72.167.102.145` devuelve rechazo de clave; no asumir shell operativo hasta validar `authorized_keys` / deploy key.
- **Brecha Git ↔ producción:** código en `main` no reflejado en gano.digital hasta deploy + acciones en panel.
- **Ruleset GitHub (Code Quality):** algunos PRs pueden quedar “BLOCKED” aunque CI esté verde; requiere triage del hallazgo exacto en la UI de GitHub.

## Skills / prioridades diferidas

- **Tests y calidad de ingeniería (WP):** skill `.gano-skills/gano-wp-engineering-quality/SKILL.md`; prioridad añadida en `TASKS.md` (*Etapa posterior — Tests y calidad de ingeniería*). Activar cuando deploy F1–3 y Fase 4 estén en orden.

## Trabajo en paralelo (SOTA workflow)

- **Registro maestro:** [`memory/research/sota-workflow-ops-parallel-2026-04.md`](../../memory/research/sota-workflow-ops-parallel-2026-04.md) — carriles A (producto), B (repo/GitHub), C (ops madurez); P0–P2; checklist; regla: no interrumpir hilo principal (`TASKS.md` Active / Fase 4). Enlace también en `TASKS.md` § *Trabajo en paralelo*.

## Próximos pasos (orden sugerido)

1. Validar manualmente clave SSH en GitHub y en el servidor; si funciona, migrar `origin` a SSH solo si aporta valor operativo.
2. Deploy checklist F1–3 + retirada `wp-file-manager`.
3. Panel: SEO, menús, copy real en Elementor.
4. Fase 4 Reseller: RCC + PFIDs reales + prueba de flujo de compra.
5. GitHub: cerrar issues cubiertos por `main`; sembrar colas opcionales solo si hacen falta.

## Decisiones / referencias

- Fuentes de verdad: `TASKS.md`, `CLAUDE.md`, `.github/DEV-COORDINATION.md`, `.github/copilot-instructions.md`.
- Jerarquía si hay conflicto: `CLAUDE.md` > `copilot-instructions.md` > `AGENTS.md`. **Comercio y checkout:** solo **GoDaddy Reseller / lo que ya está en el hosting GoDaddy** — no priorizar Wompi ni pasarelas ajenas a ese ecosistema.
- Resumen ejecutivo para humanos: `memory/sessions/2026-04-02-progreso-consolidado.md`.
- Estado dispatch y documentación operativa reciente: `memory/sessions/2026-04-03-claude-dispatch.md`.
