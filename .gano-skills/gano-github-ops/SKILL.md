---
name: gano-github-ops
description: Configurar y operar GitHub (Copilot, Actions, rulesets, Dependabot) para el repo Gano-digital/Pilot.
---

# Gano — GitHub Ops (Copilot + Actions + reglas)

## Cuándo usar esta skill

- Ajustar **instrucciones de Copilot**, colas de agentes, workflows o **rulesets**.
- Decidir qué automatizar en **GitHub Actions** vs. servidor manual.
- **Dependabot** (actions, npm en `.gsd`) y agrupación de PRs.

## Estado actual del repo (referencia)

- **Ruleset** `Gano.digital` en `Pilot`: enforcement **active** en `~DEFAULT_BRANCH` (protección borrado, no fast-forward, Copilot code review, code quality / CodeQL).
- **CI propio:** PHP lint Gano, TruffleHog, validación de colas JSON.
- **`.github/copilot-instructions.md`:** instrucciones globales; **`.github/instructions/*.instructions.md`:** por path (`php-files`, `css-js`, `mu-plugins`).
- **Colas** Copilot: `.github/agent-queue/tasks*.json` + workflow **08 · Sembrar cola**.

## Comandos útiles

```bash
gh repo view Gano-digital/Pilot --json defaultBranchRef
gh api repos/Gano-digital/Pilot/rulesets
gh run list --repo Gano-digital/Pilot --limit 15
```

## Qué suele hacer el humano (org/plan de pago)

- **Copilot Business / Enterprise:** políticas de org (quién usa Copilot, IP allow, audit logs).
- **GitHub Advanced Security:** CodeQL avanzado, secret scanning a nivel org (el repo usa TruffleHog + CodeQL en checks).
- **Larger runners:** solo si un workflow declara `runs-on: larger` o etiqueta personalizada.
- **Merge queue:** opcional para equipos grandes.

## No hacer

- No pegar **SSH keys** ni tokens en issues.
- No desactivar el ruleset sin consenso.

## Copilot coding agent — modelos (GitHub.com)

- No se configuran en YAML del repo. **Org → Copilot → Models** (owner) + **model picker** al asignar Copilot al issue.
- `memory/ops/github-copilot-agent-models-2026-04.md`

## Referencias en el repo

- `memory/ops/github-github-ops-sota-2026-04.md`
- `.github/workflows/README.md`
- `.github/COPILOT-AGENT-QUEUE.md`
