# Agent Orchestrator (AO) — recurso opcional

Este proyecto puede usar **Agent Orchestrator** como herramienta opcional para coordinar **oleadas de agentes** (worktrees + branches + PRs) en paralelo, sin convertirlo en dependencia obligatoria del repo.

Repo upstream: [ComposioHQ/agent-orchestrator](https://github.com/ComposioHQ/agent-orchestrator.git)

## Recomendación para Windows

Usar **WSL2 + Ubuntu + tmux**. En Windows “nativo” se vuelve más frágil por el runtime.

## Instalación (on-demand)

```bash
npm install -g @composio/ao
```

## Arranque en este repo

Desde el root:

```bash
ao start
```

AO genera un `agent-orchestrator.yaml` y abre un dashboard local (por defecto `http://localhost:3000`).

## Guardrails

- No auto-merge a `main`.
- No correr sobre paths sensibles.
- Mantener PRs pequeñas y revisables.

## Skill

Ver `.gano-skills/gano-agent-orchestrator-local/SKILL.md`.

