---
name: gano-cursor-models
description: Elegir y optimizar modelos de Cursor (Chat, Agent, Composer) según plan y tarea; no prometer cambios desde el repo.
---

# Gano — Modelos en Cursor (plan y tareas)

## Cuándo usar

- El usuario pregunta por **modelos más potentes**, **créditos**, **Auto vs fijo**, o **automatizar** la elección del modelo.

## Reglas para el agente

1. **No afirmar** que se puede “subir a Git” un archivo que fuerce el modelo en todas las máquinas sin comprobar la versión de Cursor y las claves `cursor.*` vigentes.
2. Remitir a **`memory/ops/cursor-models-y-plan-2026-04.md`** para la guía completa.
3. Recomendar siempre: **Cursor Settings → Models** + **Billing / usage-based** + opcional **API keys**.
4. Separar **Cursor (Agent nativo)** de **GitHub Copilot** (extensión o coding agent en la nube).

## Resumen de 30 segundos para el usuario

- Máxima potencia: **activar todos los modelos** del plan → **desactivar Auto** si quiere fijar el mejor → **usage-based** si no quiere quedarse sin cupo a mitad de mes.
- Automático desde el repo: **no**; como mucho script local o política IT.
