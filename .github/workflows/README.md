# Guía visual — workflows (GitHub Actions)

Los **nombres mostrados en la barra lateral** siguen el patrón:

`NN · <fase> · <descripción>`

| Prefijo | Fase | Significado |
|--------:|------|-------------|
| **01–02** | CI | Calidad en cada PR/push (PHP, secretos) |
| **03** | PR | Automatización sobre pull requests |
| **04–05, 12** | Deploy / Ops | Producción, verificación parches, eliminación remota wp-file-manager |
| **06** | Repo | Configuración puntual del repositorio |
| **07–11** | Agentes | Cola Copilot, semillas y orquestación |
| **13** | Projects | Añadir issues `[agent]` / `copilot` al tablero @Gano.digital (opcional) |

**Orden sugerido al operar (manual):** `06` una vez si faltan etiquetas → `07` al editar colas → `08`/`09` para crear issues → `10` solo si aplica oleada histórica → `04`/`05` cuando toque servidor. **Guía ampliada:** [`memory/ops/agent-playbook-asistentes-2026-04.md`](../../memory/ops/agent-playbook-asistentes-2026-04.md).

**Workflows que no están en esta carpeta** (los gestiona GitHub): *Copilot coding agent*, *Dependabot Updates* — no usan este esquema de nombres.

**Auditoría y troubleshooting:** [`memory/ops/github-actions-audit-2026-04.md`](../../memory/ops/github-actions-audit-2026-04.md) (fallos conocidos, riesgos, parches).

**GitHub Ops (Copilot, rulesets, Dependabot, plan de pago):** [`memory/ops/github-github-ops-sota-2026-04.md`](../../memory/ops/github-github-ops-sota-2026-04.md).

Archivo | Nombre en UI
--------|----------------
`php-lint-gano.yml` | 01 · CI · Sintaxis PHP (Gano)
`secret-scan.yml` | 02 · CI · Escaneo secretos (TruffleHog)
`labeler.yml` | 03 · PR · Etiquetas automáticas
`deploy.yml` | 04 · Deploy · Producción (rsync)
`verify-patches.yml` | 05 · Ops · Verificar parches en servidor
`setup-repo-labels.yml` | 06 · Repo · Crear etiquetas GitHub
`validate-agent-queue.yml` | 07 · Agentes · Validar cola JSON
`seed-copilot-queue.yml` | 08 · Agentes · Sembrar cola Copilot
`seed-homepage-issues.yml` | 09 · Agentes · Sembrar issues homepage
`orchestrate-copilot-waves.yml` | 10 · Agentes · Orquestar oleadas
`copilot-setup-steps.yml` | 11 · Agentes · Setup pasos Copilot
`verify-remove-wp-file-manager.yml` | 12 · Ops · Eliminar wp-file-manager (SSH)
`project-add-to-project.yml` | 13 · Projects · Añadir issues al tablero Gano.digital

**Tablero GitHub Projects (@Gano.digital):** [`.github/GITHUB-PROJECT-GANO-DIGITAL.md`](../GITHUB-PROJECT-GANO-DIGITAL.md) · playbook [`memory/ops/github-projects-gano-digital-playbook-2026-04.md`](../../memory/ops/github-projects-gano-digital-playbook-2026-04.md).

Cola **tasks-security-guardian.json** (guardián de seguridad): sembrar con **08**; scope `security` o `all`.
