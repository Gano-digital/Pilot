# Tech Context — Decisiones y Stack

## Stack Confirmado

| Componente               | Tecnologia                         | Version                                                              |
| ------------------------ | ---------------------------------- | -------------------------------------------------------------------- |
| CMS                      | WordPress                          | 6.x                                                                  |
| Builder                  | Elementor (opcional / legado)      | **No instalado en prod (abr 2026)** — vitrina SOTA vive en `gano-child` (PHP/CSS/JS) |
| Addons                   | Royal Elementor Addons             | **Removidos en prod (abr 2026)**                                     |
| Commerce                 | WooCommerce                        | **Removido en prod (abr 2026)** — modelo comercial = Reseller Store |
| Checkout / pagos cliente | GoDaddy Reseller Store             | Marca blanca (RCC + Store); pasarelas locales solo legacy si existen |
| APIs REST GoDaddy (Developer) | Opcional / fuera de WP        | Herramientas o integraciones server-side; no sustituyen Store; Good as Gold solo si la API compra productos ([getstarted](https://developer.godaddy.com/getstarted)) |
| Seguridad                | Wordfence + gano-security.php      | Wordfence **instalado, inactivo** en prod (abr 2026); MU `gano-security` activo |
| SEO                      | Rank Math + gano-seo.php           | Rank Math **instalado, inactivo** en prod; MU `gano-seo` activo |
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
9. **SOTA CI / supply chain (abr 2026)** — OWASP A03:2025 + uso seguro de Actions y runners self-hosted; checklist en `memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md`; skills `gano-github-copilot-orchestration` y `gano-github-ops` enlazan la síntesis.
10. **Homepage SOTA runtime budget (abr 2026)** — baseline SSH validado en hosting y objetivo `LCP <= 3.0s`; en front-page se prioriza motion CSS sutil y se evita carga innecesaria de GSAP/Constellation cuando no aporta conversión directa.
11. **Política bots en Apache (abr 2026)** — en producción se bloquean solo user-agents de **escaneo abusivo** (`sqlmap`, `nikto`, `acunetix`, `masscan`, `dirbuster`, `wpscan`, `zgrab`, `nmap`, `httrack`). Crawlers de descubrimiento y herramientas tipo `curl` / `GPTBot` no se deniegan por esa regla. Contenido orientado a bots: `llms.txt` y `bot-seo-context.md` en la raíz del sitio.
12. **Convergencia repo ↔ producción (abr 2026)** — la fuente canónica de **código versionado** es `main` en `Gano-digital/Pilot`. Despliegue rutinario: workflow **04** + verificación **05** ([`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md)). Despliegue de **parche urgente**: SCP/rsync con backup previo en servidor y comprobación de checksums.
13. **Sistemas visuales Canvas/SVG (abr 2026)** — skill `.cursor/skills/gano-web-visual-systems/`; memoria extendida y bibliografía en `memory/research/visual-systems-canvas-svg-second-brain-2026-04.md`. Prototipos locales sin CDN antes de subir a `gano-child`; alinear con `memory/research/motion-and-3d-policy-gano.md` (LCP, reduced-motion).

## Tooling opcional (repo)

- **`.gsd/sdk` (Node, no runtime WP):** Dependabot en transitive deps (`hono`, `@hono/node-server`, `@anthropic-ai/sdk`) se cierra con **`overrides`** en `.gsd/sdk/package.json` + `npm install`; `npm audit` debe quedar en 0.
- **Graphify (local, seguro):** skill `.gano-skills/gano-graphify-local/` (sin hooks) para generar `graphify-out/` bajo demanda.
- **Agent Orchestrator (AO):** skill `.gano-skills/gano-agent-orchestrator-local/` para coordinar oleadas paralelas (worktrees/PRs). Recomendado WSL2+tmux en Windows.
- **ML‑SSD (Apple):** submodule `vendor/ml-ssd` + skill `.gano-skills/gano-ml-ssd/` como base reproducible para experimentos/evaluación de codegen.

## Servidor

- Host / usuario SSH o cPanel: **solo en panel GoDaddy o variables locales** (`GANO_SSH_*` / secrets de CI); no pegar IP ni usuario real en commits públicos.
- PHP: 8.3
- WP-CLI: disponible
- SSH: alias local `gano-godaddy` en uso para operación; no documentar usuario/IP en commits
- Storage: 20GB NVMe
- CDN: incluido

## Acceso y remotos

- **Único remoto activo:** `origin` → `https://github.com/Gano-digital/Pilot.git` (**repositorio público** desde 2026-04-10; no asumir privacidad del código).
- GitLab no está en uso; referencia archivada en `memory/ops/archived-gitlab-remote-2026-04.md`
- Acceso Git verificado por HTTPS; no asumir SSH operativo hasta validar clave en GitHub y servidor.

## Herramientas de Dev

- Cursor IDE (agentes, rules)
- Claude Code (CLI, skills)
- VS Code (extensiones PHP/WP)
- GitHub Actions (CI/CD)
- GitHub Copilot Agent (PRs automatizados)
