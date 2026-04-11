---
name: gano-agent-orchestrator-local
description: Usar Agent Orchestrator (AO) como recurso opcional para coordinar oleadas de agentes en paralelo (worktrees + PRs), sin volverlo dependencia obligatoria del repo.
---

# Skill — Agent Orchestrator (AO) opcional

Referencia: [ComposioHQ/agent-orchestrator](https://github.com/ComposioHQ/agent-orchestrator.git) (MIT).

**Objetivo:** tener un mecanismo “bajo demanda” para lanzar oleadas de agentes en paralelo (cada uno aislado en worktree/branch/PR), especialmente útil para:
- UX/bugfixes del tool `memory/constellation/`
- higiene de docs/skills/scripts en `tools/` y `.gano-skills/`
- babysitting de CI/reviews en PRs

---

## Guardrails (obligatorio)

- **AO es opcional**: no es parte del runtime del sitio WordPress.
- **No** clonar `agent-orchestrator` dentro de este repo: usar instalación global o un checkout separado.
- **No** auto-merge en `main`: mantener revisión humana (reglas del repo, seguridad, Code Quality).
- **No** correr agentes sobre secretos o paths sensibles (`.env`, `wp-config.php`, llaves, etc.).
- **Siempre** preferir tareas atómicas y PRs pequeñas (< 400 líneas) según `200-git-workflow`.

---

## Requisitos

- Node.js 20+
- Git 2.25+
- `gh` autenticado
- Runtime recomendado:
  - **Windows**: WSL2 (Ubuntu) para `tmux`
  - Linux/macOS: `tmux`

---

## Instalación (local, una vez)

### Opción A (recomendada): instalar AO por npm

```bash
npm install -g @composio/ao
```

### Opción B: desde fuente (solo si vas a contribuir al orchestrator)

```bash
git clone https://github.com/ComposioHQ/agent-orchestrator.git
cd agent-orchestrator
bash scripts/setup.sh
```

---

## Uso en este repo (patrón recomendado)

### 1) Arrancar AO apuntando al repo

Desde el root del repo:

```bash
ao start
```

Esto genera/usa `agent-orchestrator.yaml` y abre dashboard local (por defecto `http://localhost:3000`).

### 2) Alimentar trabajo “tipo oleada”

Usar AO cuando haya un lote claro de issues/tareas (por ejemplo, tu cola `.github/agent-queue/` o una lista de bugs de Constellation).

**Regla práctica:** AO coordina y monitorea; las tareas “source of truth” siguen siendo:
- `TASKS.md`
- `CLAUDE.md`
- `.github/agent-queue/*` (si aplica)

### 3) Cierre

- Mantener worktrees/artefactos fuera del repo o ignorados.
- Subir solo cambios de código/docs/skills que pasen guardrails.

---

## Checklist rápido (antes de usarlo)

- ¿Hay una lista finita de tareas atómicas? (sí/no)
- ¿Se puede trabajar sin tocar `wp-config.php`/`.env`? (sí/no)
- ¿Las tareas no requieren decisiones humanas de producto/legal? (sí/no)
- ¿Tenemos CI/restricciones que obligan a PRs pequeños? (sí/no)

Si cualquier respuesta es “no”, usar flujo manual o Copilot queue existente.

