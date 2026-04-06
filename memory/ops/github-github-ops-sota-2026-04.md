# GitHub Ops — SOTA y checklist (Gano Digital · Pilot) · 2026-04

Documento de referencia para **Copilot**, **Actions**, **rulesets**, **Dependabot** y planes de pago de GitHub. Lo aplicable al repo ya está versionado en `.github/`; lo org-wide solo lo puede configurar un **owner** o **admin**.

## Qué ya está hecho en el repo (2026-04)

| Elemento | Estado |
|----------|--------|
| **Ruleset `Gano.digital`** | Activado (`enforcement: active`) en `~DEFAULT_BRANCH`: anti-borrado, no fast-forward, **Copilot code review** (push + drafts), **code quality** (CodeQL). |
| **copilot-instructions.md** | Instrucciones globales + sección SOTA para coding agent. |
| **Path instructions** | `php-files`, `css-js`, `mu-plugins` (`.github/instructions/*.instructions.md`). |
| **Dependabot** | `github-actions` agrupado; **npm** en `.gsd` y `.gsd/sdk` semanal. |
| **Skill local** | `.gano-skills/gano-github-ops/SKILL.md` para Cursor/Claude. |

## Investigación SOTA (resumen)

1. **Instrucciones de repositorio** — GitHub recomienda `copilot-instructions.md` + instrucciones por ruta; se combinan (la específica de path complementa la global). Fuente: documentación oficial Copilot.
2. **Coding agent** — Mejor rendimiento con issues con **criterios de aceptación** y alcance claro; evitar tareas críticas de seguridad sin revisión humana. Fuente: GitHub “Best practices for Copilot coding agent”.
3. **Rulesets** — Sustituyen o complementan branch protection clásica; permiten **Copilot code review** y políticas de calidad unificadas.
4. **Dependabot grouping** — Reduce PRs fragmentados (actions + npm tooling).

## Qué debes hacer tú (org / plan de pago — UI GitHub)

Marca lo que vayas aplicando.

### Organización `Gano-digital`

- [ ] **Settings → Copilot** (o **Policies**): confirmar que los miembros que necesitan agente tienen licencia **Copilot**; revisar si aplica **Copilot for Business** (políticas, exclusiones de repos si los hubiera).
- [ ] **Audit log** (si Enterprise/team con audit): revisar accesos a repos sensibles periódicamente.
- [ ] **GitHub Advanced Security** (si está contratado): activar **secret scanning** y **push protection** a nivel org para complementar TruffleHog en CI — no sustituye el escaneo en PR, refuerza commits directos.
- [ ] **IP allow list** (Enterprise): solo si el equipo exige acceso restringido por IP.

### Repositorio `Pilot`

- [ ] **Settings → Secrets and variables → Actions:** `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` coherentes con la clave **OpenSSH/Ed25519** sin passphrase para CI (ver `memory/ops/github-actions-ssh-secret-troubleshooting.md`).
- [ ] **Settings → Branches:** si además del ruleset quieres **Require pull request reviews** (p. ej. 1 aprobación humana), configúralo aquí o amplía el ruleset — evita duplicar reglas contradictorias.
- [ ] **Projects** (beta): opcional — tablero Kanban enlazado a issues `copilot` / `coordination` para oleadas de agentes.
- [ ] **Webhooks** (opcional): solo si integras Slack/Discord para fallos de Actions.

### Flujo de datos agentes ↔ repo

- **Entrada:** issues con `<!-- agent-task-id:... -->` (workflow **08**), plantillas en `ISSUE_TEMPLATE/`.
- **Salida:** PRs de Copilot; **CODEOWNERS** apunta a `@Ouroboros1984` — los PRs pueden requerir tu revisión según plan y reglas.
- **No automatizable desde el repo:** aprobación de facturación GitHub, límites de minutos Actions, compra de Advanced Security.

## Mantenimiento

- Cada **trimestre:** revisar reglas del ruleset (¿sigue siendo deseable Copilot review en cada push?).
- Tras **grandes cambios** en `main`: ejecutar **07 · Validar cola JSON** si se editan `agent-queue/*.json`.

## Cursor (IDE local) — modelos y plan

No se gestiona desde GitHub; ver **`memory/ops/cursor-models-y-plan-2026-04.md`** y la skill **`.gano-skills/gano-cursor-models/`**.

## Referencias externas

- [Repository custom instructions](https://docs.github.com/copilot/customizing-copilot/adding-repository-custom-instructions-for-github-copilot)
- [Copilot coding agent best practices](https://docs.github.com/copilot/tutorials/coding-agent/best-practices)
- [Rulesets](https://docs.github.com/repositories/configuring-branches-and-merges-in-your-repository/managing-rulesets)
