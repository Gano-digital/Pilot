# Playbook — fusionar PRs de Copilot y cerrar issues de la cola

Guía breve para cuando la **oleada 1** dejó muchos PRs en borrador (#34–#50 aprox.) e **issues** #17–#33 abiertos.

En **Actions**, los workflows del repo usan prefijos `01`…`11` (CI → PR → Deploy → Agentes). Ver [`.github/workflows/README.md`](workflows/README.md).

## 1. Antes de fusionar

- Revisar **Checks** del PR: **PHP lint (Gano)** y **TruffleHog** en verde.
- Leer el diff; los issues marcados `[sync]` o “solo wp-admin” pueden no tener PR completo — fusionar solo lo que sea código/repo.

## 2. Orden sugerido (reduce conflictos)

1. **Tema / `gano-child`** (handles, CSS, LCP) — base visual.
2. **MU-plugins / SEO** si el PR no pisa el tema.
3. **Docs / README** — al final o en paralelo si no hay conflicto.
4. **Commerce / shop-premium** — cuando el tema estable esté en `main`.

## 3. Al fusionar

- Preferir **squash** si el historial del agente es ruidoso.
- En el cuerpo del merge o comentario, enlazar el **issue** `Closes #NN` si el trabajo del issue quedó cubierto por el PR; si el issue era solo servidor (Elementor), **cerrar** con comentario explicando que queda acción manual.

## 4. Después del merge

- **Deploy**: si cambió `gano-child/`, `mu-plugins/` o `gano-*` plugins cubiertos por `deploy.yml`, disparar **Deploy** manual en Actions si el push no lo lanzó por `paths`.
- Actualizar **`TASKS.md`** o un issue `coordination` si hubo drift.

## 5. Oleada 2 de issues

Cuando quieras **nuevas** tareas sin duplicar la oleada 1, usa una de estas opciones:

- **Todo en uno (recomendado tras revisar PRs):** Actions → **10 · Orquestar oleadas** → activa `merge_wave1` y `seed_wave2`; prueba primero con **`dry_run_merge: true`** para ver el plan sin fusionar.
- **Solo issues:** Actions → **08 · Sembrar cola Copilot** → `queue_file`: **`tasks-wave2.json`** → ámbito `all` o filtrado.

Los IDs `w2-*` no chocan con `hp-*` / `theme-*` de `tasks.json`; la deduplicación es por `agent-task-id` en el cuerpo del issue.

**Oleada 3 (marca/UX/comercial):** archivo `tasks-wave3.json` (`w3-*`). Seed con **08 · Sembrar cola Copilot** → `queue_file: tasks-wave3.json`. Brief: `memory/research/gano-wave3-brand-ux-master-brief.md`.

---

_Ver también [COPILOT-AGENT-QUEUE.md](COPILOT-AGENT-QUEUE.md) y [DEV-COORDINATION.md](DEV-COORDINATION.md)._
