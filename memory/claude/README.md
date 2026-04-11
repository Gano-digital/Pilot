# Contexto para Claude — Gano Digital (Pilot)

Esta carpeta concentra **registros estructurados** para que cualquier instancia de Claude (Cursor, Claude Code, web con repo adjunto) recupere **qué se hizo en abril 2026**, **en qué estado quedó el repositorio** y **qué sigue pendiente**, sin depender solo del historial de un chat.

## Orden de lectura recomendado

| Orden | Archivo | Para qué sirve |
|------:|---------|----------------|
| 0 | [2026-04-08-reporte-handoff-ssh-deploy-tokens.md](2026-04-08-reporte-handoff-ssh-deploy-tokens.md) | **Handoff SSH/Deploy/CI + tokens** — huella verificada, hipótesis IP, PR #160, enlaces a runs. Leer primero si retomas tras pausa de API/tokens. |
| 1 | [README.md](README.md) (este) | Navegación y convenciones. |
| 2 | [01-historial-y-contexto-cursor-2026-04.md](01-historial-y-contexto-cursor-2026-04.md) | Línea de tiempo, decisiones, consolidación de PRs, paso de documentación. |
| 3 | [02-pendientes-detallados.md](02-pendientes-detallados.md) | Inventario exhaustivo de pendientes por área y prioridad. |
| 4 | [03-referencia-tecnica-y-faq.md](03-referencia-tecnica-y-faq.md) | Workflows, rutas canónicas, respuestas cortas a dudas recurrentes. |
| 5 | [dispatch-prompt.md](dispatch-prompt.md) + [dispatch-queue.json](dispatch-queue.json) | **Cola programable para Claude** en el repo: qué puede hacer el agente sin wp-admin ni secrets; scripts en `scripts/claude_dispatch.py`. |

## Cola dispatch (Claude en el workspace)

- **Definición de tareas:** [`dispatch-queue.json`](dispatch-queue.json) — 12 ítems (`cd-repo-001` … `cd-repo-012`) con instrucciones, paths y *definition of done*.
- **Cómo usarlo:** [`dispatch-prompt.md`](dispatch-prompt.md) — flujo `next` / `complete`, prompt maestro para Claude Projects, y límites (no se puede “programar” la nube Anthropic desde git).
- **Scripts:** `python scripts/claude_dispatch.py list|next|show <id>|complete <id>` · `python scripts/validate_claude_dispatch.py`
- **Cursor/VS Code:** tareas en [`.vscode/tasks.json`](../../.vscode/tasks.json) (`Tasks: Run Task`); atajos globales en perfil Cursor (ver [`dispatch-prompt.md`](dispatch-prompt.md)).
- **Progreso local:** `memory/claude/dispatch-state.json` (gitignored; se crea al primer `complete`).

Esto es **independiente** de la cola Copilot (`.github/agent-queue/*.json` — **siete** archivos validados — → issues en GitHub vía workflow **08**).

## Documentos “oficiales” fuera de esta carpeta

- **Playbook agentes + asistentes (arranque, troubleshooting, cerrar issues, offloading):** [`../ops/agent-playbook-asistentes-2026-04.md`](../ops/agent-playbook-asistentes-2026-04.md)
- **Plantillas para cerrar issues en GitHub:** [`gh-issue-close-guide.md`](gh-issue-close-guide.md)
- **Memoria del proyecto y stack:** [`CLAUDE.md`](../../CLAUDE.md) en la raíz del workspace.
- **Lista maestra de tareas:** [`TASKS.md`](../../TASKS.md).
- **Setup digital — archivos y contenido (mapa `memory/`, handoff, constelación):** [`../content/digital-files-and-content-setup.md`](../content/digital-files-and-content-setup.md).
- **Plan vitrina gano.digital (fases, RACI, agentes):** [`../ops/homepage-vitrina-launch-plan-2026-04.md`](../ops/homepage-vitrina-launch-plan-2026-04.md).
- **Gano Ops Hub (stats TASKS + dispatch, botones Actions):** [`../../tools/gano-ops-hub/README.md`](../../tools/gano-ops-hub/README.md).
- **Recordatorio operativo de Diego (prioridades + cuándo correr Actions):** [`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md).
- **Progreso consolidado (equipo):** [`../sessions/2026-04-02-progreso-consolidado.md`](../sessions/2026-04-02-progreso-consolidado.md).
- **Consolidación PRs Copilot (2026-04-03):** [`../sessions/2026-04-03-consolidacion-prs-copilot.md`](../sessions/2026-04-03-consolidacion-prs-copilot.md).
- **Auditoría GitHub Actions:** [`../ops/github-actions-audit-2026-04.md`](../ops/github-actions-audit-2026-04.md) (labeler v6, workflow 11, etc.).
- **Guardián seguridad / cierre de sesión:** [`../ops/security-guardian-playbook-2026.md`](../ops/security-guardian-playbook-2026.md), skill [`.gano-skills/gano-session-security-guardian/SKILL.md`](../../.gano-skills/gano-session-security-guardian/SKILL.md), checklist [`memory/ops/security-end-session-checklist.md`](../ops/security-end-session-checklist.md), script `scripts/security_session_reminder.py`, cola `.github/agent-queue/tasks-security-guardian.json`.
- **Investigación APIs (ML + GoDaddy):** [`../research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md).
- **Coordinación git ↔ servidor:** [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md).
- **Skill orquestación GitHub/Copilot:** [`.gano-skills/gano-github-copilot-orchestration/SKILL.md`](../../.gano-skills/gano-github-copilot-orchestration/SKILL.md) — **7** archivos JSON en `.github/agent-queue/` (incl. API research + guardián seguridad).

## Convenciones

- Las fechas del proyecto asumen **año 2026** (autoridad: metadatos de sesión del workspace).
- **“Hecho en repo”** no implica **desplegado en producción** salvo que se diga explícitamente.
- Los números de **issue/PR** de GitHub pueden cambiar en forks; la fuente de verdad es el remoto **Gano-digital/Pilot** en GitHub.

## Continuidad día a día

- **Salida / regreso (cPanel, DNS, reanudar agentes 08):** [`../notes/nota-salida-cpanel-dns-y-agentes-2026-04.md`](../notes/nota-salida-cpanel-dns-y-agentes-2026-04.md)
- [`../sessions/2026-04-03-noche-continuidad-manana.md`](../sessions/2026-04-03-noche-continuidad-manana.md) — ejemplo: qué se hizo en repo vs qué queda en GitHub (Copilot/Actions).

## Informes PDF para handoff (Claude / junta)

- **Auditoría / dispatch:** `python scripts/generate_claude_audit_report_pdf.py` → `reports/Gano-Digital-Auditoria-Desarrollo-YYYY-MM-DD.pdf` (cola dispatch, consolidación PRs; ver script para alcance).
- **Reporte de estado equipo (junta):** `python scripts/generate_board_report_pdf.py` → `reports/Gano-Digital-Reporte-Estado-YYYY-MM.pdf` (contenido embebido en el script; regenerar tras cambios de negocio).
- **Handoff extendido (comercial/UX + skills):** `python scripts/generate_handoff_claude_commercial_extended_pdf.py` → `reports/Gano-Digital-Handoff-Claude-Comercial-YYYY-MM-DD.pdf`.

Los `.pdf` suelen estar en `.gitignore`; no esperes el binario en el remoto.

## Mantenimiento

Cuando se cierre un bloque grande (deploy, RCC, eliminación de wp-file-manager, cierre masivo de issues), actualizar:

1. [`02-pendientes-detallados.md`](02-pendientes-detallados.md) y [`TASKS.md`](../../TASKS.md).
2. Opcionalmente añadir una entrada datada al final de [`01-historial-y-contexto-cursor-2026-04.md`](01-historial-y-contexto-cursor-2026-04.md) o crear `memory/sessions/YYYY-MM-DD-*.md` y enlazarlo aquí.

## Tooling opcional incorporado (abril 2026)

- **Graphify (local, seguro):** `.gano-skills/gano-graphify-local/` — genera `graphify-out/` para mapa de arquitectura bajo demanda (sin hooks).
- **Agent Orchestrator (AO):** `.gano-skills/gano-agent-orchestrator-local/` — coordina oleadas paralelas (worktrees/PRs), recomendado en WSL2+tmux.
- **ML‑SSD (Apple):** `.gano-skills/gano-ml-ssd/` + submodule `vendor/ml-ssd` — base reproducible de I+D para evaluación de codegen (no runtime).
