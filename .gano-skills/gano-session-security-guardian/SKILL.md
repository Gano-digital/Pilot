---
name: gano-session-security-guardian
description: >
  Guardián de seguridad al cerrar sesión o dejar el repo: higiene de datos sensibles,
  checklist humano, qué puede automatizar Copilot vs qué no. Usar al terminar el día,
  antes de compartir pantalla, o al mencionar purga, exposición, tokens, cierre de sesión,
  end-session, lockdown, o cola tasks-security-guardian.
---

# Gano — Guardián de seguridad (cierre de sesión)

## Cuándo usar esta skill

- Fin de jornada o antes de alejarte del equipo.
- Después de trabajar con **SSH**, **panel hosting**, **Wompi**, **RCC**, **gh auth**, o **tokens**.
- Cuando vayas a **compartir pantalla** o enviar un **zip** del proyecto.

## Qué hace el «guardián» (concepto)

| Capa | Acción |
|------|--------|
| **Humano** | Bloqueo de pantalla, cerrar sesiones web sensibles, no dejar PEM/env en carpetas públicas. |
| **Git local** | `git status` limpio; `git remote -v` sin token en URL; no `git add` de `.env`. |
| **Repo (agente)** | Issues `sec-*` desde [`.github/agent-queue/tasks-security-guardian.json`](../../.github/agent-queue/tasks-security-guardian.json): auditar docs, `.gitignore`, plantillas. |
| **CI** | TruffleHog + PHP lint en GitHub (automático en push/PR). |

El agente **no** borra historial de Git remoto ni revoca secretos en GitHub.org: eso es **humano** o **administrador**.

## Checklist rápido (copiar mentalmente)

1. ¿Cerré SSH / wp-cli / rsync a producción?
2. ¿Hay tokens en el portapapeles? Sobrescribir.
3. ¿`git status` sin archivos sensibles?
4. ¿Bloqueo de pantalla (Win+L) si me voy?

Lista imprimible: [`memory/ops/security-end-session-checklist.md`](../../memory/ops/security-end-session-checklist.md).

## Playbook completo

[`memory/ops/security-guardian-playbook-2026.md`](../../memory/ops/security-guardian-playbook-2026.md)

## Sembrar issues en GitHub (Copilot)

**Actions → 08 · Sembrar cola Copilot** → `queue_file: tasks-security-guardian.json` → `scope: all` o `security`.

Prompt: bloque **«Guardián seguridad»** en [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md).

## Skills relacionadas

- `gano-wp-security` — MU-plugins, CSP, hardening WordPress.
- `gano-github-copilot-orchestration` — colas y workflows.
- `gano-multi-agent-local-workflow` — PAT en remotes, rutas locales.
