---
title: "SOTA — Tooling agentic opcional (Graphify, AO, ML‑SSD)"
date: 2026-04-07
scope: repo-process
---

# SOTA — Tooling agentic opcional (Graphify, AO, ML‑SSD)

## Resumen ejecutivo

En abril 2026 se incorporaron tres recursos **opcionales** al repo `Gano-digital/Pilot` para acelerar investigación, coordinación de oleadas y evaluación de calidad de codegen, sin afectar el runtime de WordPress:

- **Graphify (vendor, local)**: genera un knowledge-graph persistente para navegar arquitectura (sin hooks).
- **Agent Orchestrator (AO)**: orquesta oleadas paralelas (worktrees/PRs) bajo supervisión.
- **ML‑SSD (Apple)**: base reproducible de I+D para evaluar/mejorar codegen (submodule).

El principio es el mismo para los tres: **opt‑in**, bajo demanda, con guardrails de seguridad y sin introducir dependencias de producción.

---

## 1) Graphify (local, seguro)

**Qué aporta**
- Mapa persistente (`graphify-out/`) para preguntas de arquitectura, dependencias, “god nodes” y clustering.
- Reduce el tiempo de “onboarding” de agentes cuando el repo crece.

**Riesgos y mitigación**
- Hooks / automatizaciones no deseadas → **prohibido** instalar hooks.
- Alcance excesivo (paths sensibles) → ejecutar por allowlist.

**Integración en Gano**
- Skill: `.gano-skills/gano-graphify-local/`
- Docs: `tools/graphify/README.md`

---

## 2) Agent Orchestrator (AO)

Repo upstream: `https://github.com/ComposioHQ/agent-orchestrator.git`

**Qué aporta**
- Coordinación de múltiples agentes con aislamiento por worktree/branch/PR.
- Útil cuando hay oleadas grandes (ej. Constellation UX/perf/a11y).

**Riesgos y mitigación**
- Automatización de merges → **no** auto-merge en `main`.
- Infra adicional (tmux) → en Windows, **WSL2 recomendado**.
- Reescrituras masivas → enforce “parches mínimos” y PRs pequeñas.

**Integración en Gano**
- Skill: `.gano-skills/gano-agent-orchestrator-local/`
- Docs: `tools/agent-orchestrator/README.md`

---

## 3) ML‑SSD (Apple) para I+D

Repo upstream: `https://github.com/apple/ml-ssd.git`

**Qué aporta**
- Base reproducible para experimentos de *evaluation* en codegen (pipeline simple: sample → finetune → decode).
- Útil como marco de evaluación interna cuando se quiera comparar prompts/parámetros y consistencia de patches.

**Riesgos y mitigación**
- Costos compute → no correr training pesado por defecto.
- Datasets privados → no commitear corpora/outputs sensibles.
- Licencia Apple (custom) → mantenerlo como tooling interno; respetar `LICENSE`.

**Integración en Gano**
- Submodule: `vendor/ml-ssd`
- Skill: `.gano-skills/gano-ml-ssd/`
- Docs: `tools/ml-ssd/README.md`

---

## 4) Recomendación operativa

1. **Por defecto**: usar Grep/Glob + lectura dirigida (mínimo cambio).
2. **Cuando el repo se vuelve “denso”**: correr Graphify sobre un scope acotado.
3. **Cuando hay oleadas grandes**: usar AO como coordinador (no como “autopilot”).
4. **Cuando se quiera medir mejoras**: usar ML‑SSD como marco de evaluación, registrando resultados en `memory/research/` con fecha.

