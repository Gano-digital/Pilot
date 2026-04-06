# Tech Context — Decisiones y Stack

## Stack Confirmado

| Componente               | Tecnologia                         | Version                                                              |
| ------------------------ | ---------------------------------- | -------------------------------------------------------------------- |
| CMS                      | WordPress                          | 6.x                                                                  |
| Builder                  | Elementor Pro                      | Latest                                                               |
| Addons                   | Royal Elementor Addons             | Latest                                                               |
| Commerce                 | WooCommerce                        | Latest                                                               |
| Checkout / pagos cliente | GoDaddy Reseller Store             | Marca blanca (RCC + Store); pasarelas locales solo legacy si existen |
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
