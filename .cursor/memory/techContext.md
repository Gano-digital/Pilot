# Tech Context — Decisiones y Stack

## Stack Confirmado

| Componente               | Tecnologia                         | Version                                                              |
| ------------------------ | ---------------------------------- | -------------------------------------------------------------------- |
| CMS                      | WordPress                          | 6.x                                                                  |
| Builder                  | Elementor Pro                      | Latest                                                               |
| Addons                   | Royal Elementor Addons             | Latest                                                               |
| Commerce                 | WooCommerce                        | Latest                                                               |
| Checkout / pagos cliente | GoDaddy Reseller Store             | Marca blanca (RCC + Store); pasarelas locales solo legacy si existen |
| APIs REST GoDaddy (Developer) | Opcional / fuera de WP        | Herramientas o integraciones server-side; no sustituyen Store; Good as Gold solo si la API compra productos ([getstarted](https://developer.godaddy.com/getstarted)) |
| Seguridad                | Wordfence + gano-security.php      | Custom                                                               |
| SEO                      | Rank Math + gano-seo.php           | Custom                                                               |
| Tema                     | gano-child (Hello Elementor child) | Custom                                                               |
| Animaciones              | GSAP 3 + ScrollTrigger             | 3.x                                                                  |
| PHP                      | PHP                                | 8.3                                                                  |
| Server                   | GoDaddy Managed WordPress Deluxe   | -                                                                    |

## Decisiones Arquitecturales

1. **Vanilla CSS** sobre Tailwind — integracion con Elementor
2. **GSAP** sobre CSS animations — control granular de animaciones
3. **MU-Plugins** para seguridad/SEO — carga antes que todo, no desactivable
4. **Prefijo gano\_** en funciones — namespace propio
5. **GSD workflow** para agentes — coordinacion multi-agente via .github/agents/
6. **Copilot queue** via JSON — oleadas de tareas versionadas
7. **Tooling opcional (no runtime)** — graphify (mapa conocimiento), AO (orquestación), ML‑SSD (I+D evaluación)
8. **Plan vitrina gano.digital** — documento canónico de fases, RACI y procesos: `memory/ops/homepage-vitrina-launch-plan-2026-04.md` (complementa `TASKS.md`; copy fuente en `memory/content/`).

## Tooling opcional (repo)

- **`.gsd/sdk` (Node, no runtime WP):** Dependabot en transitive deps (`hono`, `@hono/node-server`, `@anthropic-ai/sdk`) se cierra con **`overrides`** en `.gsd/sdk/package.json` + `npm install`; `npm audit` debe quedar en 0.
- **Graphify (local, seguro):** skill `.gano-skills/gano-graphify-local/` (sin hooks) para generar `graphify-out/` bajo demanda.
- **Agent Orchestrator (AO):** skill `.gano-skills/gano-agent-orchestrator-local/` para coordinar oleadas paralelas (worktrees/PRs). Recomendado WSL2+tmux en Windows.
- **ML‑SSD (Apple):** submodule `vendor/ml-ssd` + skill `.gano-skills/gano-ml-ssd/` como base reproducible para experimentos/evaluación de codegen.

## Servidor

- Host: 72.167.102.145 (GoDaddy)
- Usuario: f1rml03th382
- PHP: 8.3
- WP-CLI: disponible
- SSH: transporte responde, pero autenticación por clave aún no validada
- Storage: 20GB NVMe
- CDN: incluido

## Acceso y remotos

- **Único remoto activo:** `origin` → `https://github.com/Gano-digital/Pilot.git`
- GitLab no está en uso; referencia archivada en `memory/ops/archived-gitlab-remote-2026-04.md`
- Acceso Git verificado por HTTPS; no asumir SSH operativo hasta validar clave en GitHub y servidor.

## Herramientas de Dev

- Cursor IDE (agentes, rules)
- Claude Code (CLI, skills)
- VS Code (extensiones PHP/WP)
- GitHub Actions (CI/CD)
- GitHub Copilot Agent (PRs automatizados)
