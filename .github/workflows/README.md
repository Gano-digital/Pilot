# Guía visual — workflows (GitHub Actions)

Los **nombres mostrados en la barra lateral** siguen el patrón:

`NN · <fase> · <descripción>`

| Prefijo | Fase | Significado |
|--------:|------|-------------|
| **01–02** | CI | Calidad en cada PR/push (PHP, secretos) |
| **03** | PR | Automatización sobre pull requests |
| **04–05, 12, 14** | Deploy / Ops | Producción, verificación parches, wp-file-manager, **Ops Hub (Pages)** |
| **06** | Repo | Configuración puntual del repositorio |
| **07–11** | Agentes | Cola Copilot, semillas y orquestación |
| **13** | Projects | Añadir issues `[agent]` / `copilot` al tablero @Gano.digital (opcional) |
| **16–18** | Ops SSH | Backups/sync/health con SSH (saltan si faltan secrets) |
| **30–32** | Comercio F4 | Validación catálogo Reseller, salud plugins y secuencia de release |
| **99** | Debug | Workflow manual de prueba rápida |

**Orden sugerido al operar (manual):** `06` una vez si faltan etiquetas → `07` al editar colas → `08`/`09` para crear issues → `10` solo si aplica oleada histórica → `04`/`05` cuando toque servidor. **Guía ampliada:** [`memory/ops/agent-playbook-asistentes-2026-04.md`](../../memory/ops/agent-playbook-asistentes-2026-04.md).

## Estado despliegue SOTA (2026-04-11)

- Tras integrar el catálogo canónico y templates SOTA en repo, ejecutar esta secuencia:
  1. **04 · Deploy · Producción (rsync)** para subir `gano-child`.
  2. **05 · Ops · Verificar parches en servidor** para validar paridad repo/servidor.
  3. **12 · Ops · Eliminar wp-file-manager (SSH)** si aún aparece en servidor.
- No cerrar tareas SOTA sin verificar publicación real de `/shop-premium/` y `/diagnostico-digital/`.

**Workflows que no están en esta carpeta** (los gestiona GitHub): *Copilot coding agent*, *Dependabot Updates* — no usan este esquema de nombres.

## Auditoría de versiones de actions (post-Dependabot)

Última verificación: **2026-04-19** · Issue #235 · PRs Dependabot #14–#16 fusionados.

| Action | Versión actual | Workflows que la usan |
|--------|---------------|----------------------|
| `actions/checkout` | `@v6` | Todos los workflows que requieren código del repo |
| `actions/github-script` | `@v9` | `orchestrate-copilot-waves.yml`, `seed-copilot-queue.yml`, `seed-homepage-issues.yml`, `setup-repo-labels.yml` |
| `actions/setup-node` | `@v6` | `copilot-setup-steps.yml` |
| `actions/setup-python` | `@v6` | `gano-ops-hub.yml` |
| `actions/upload-artifact` | `@v7` | `30-reseller-catalog-sync.yml`, `31-plugin-health-check-phase4.yml` |
| `actions/upload-pages-artifact` | `@v5` | `gano-ops-hub.yml` |
| `actions/deploy-pages` | `@v5` | `gano-ops-hub.yml` |
| `actions/labeler` | `@v6` | `labeler.yml` |
| `actions/add-to-project` | `@v1.0.2` | `project-add-to-project.yml` |
| `shivammathur/setup-php` | `@v2` | `copilot-setup-steps.yml`, `php-lint-gano.yml` |
| `webfactory/ssh-agent` | `@v0.10.0` | `verify-patches.yml`, `verify-remove-wp-file-manager.yml` |

**Resultado:** ✅ No se encontraron referencias `@v4` obsoletas para `actions/checkout` ni `actions/github-script`. Todos los workflows son consistentes con las actualizaciones de Dependabot.

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
`gano-ops-hub.yml` | 14 · Ops · Gano Ops Hub (TASKS + dispatch + Project API + GitHub Pages)
`06-db-backup.yml` | 16 · Ops · Backup · BD Automático
`07-sync-content.yml` | 17 · Ops · Content Sync · Staging ↔ Producción
`08-health-check-plugins.yml` | 18 · Ops · Health Check · Validar plugins gano-*
`30-reseller-catalog-sync.yml` | 30 · Reseller Catalog Sync
`31-plugin-health-check-phase4.yml` | 31 · Plugin Health Phase 4
`32-commerce-release-sequence.yml` | 32 · Release · Commerce Sequence
`test-runner.yml` | 99 · Debug · Test Runner

**Tablero GitHub Projects (@Gano.digital):** [`.github/GITHUB-PROJECT-GANO-DIGITAL.md`](../GITHUB-PROJECT-GANO-DIGITAL.md) · playbook [`memory/ops/github-projects-gano-digital-playbook-2026-04.md`](../../memory/ops/github-projects-gano-digital-playbook-2026-04.md).

Cola **tasks-security-guardian.json** (guardián de seguridad): sembrar con **08**; scope `security` o `all`.
