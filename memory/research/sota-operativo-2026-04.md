# Estado del arte operativo — Gano Digital (Abril 2026)

Nota breve para agentes y humanos: qué es “SOTA” en este proyecto y qué prácticas están adoptadas en el repo.

## Significado de SOTA en Gano

- **Producto / marca:** “State of the Art” — posicionamiento de hosting y ecosistemas de alto rendimiento (Núcleo Prime, Fortaleza Delta, Bastión SOTA), narrativa técnica y mockup cinético.
- **Ingeniería:** no es un estándar único; es **stack WordPress + Elementor + hardening documentado** + **CI en GitHub** + **delegación controlada a agentes** (Copilot coding agent) con verificación humana.

## Prácticas adoptadas en el repositorio (canon GitHub)

| Área | Práctica |
|------|-----------|
| **Integración** | Rama `main` en `Gano-digital/Pilot`; PRs revisables con plantilla y etiquetas. |
| **Seguridad en CI** | TruffleHog acotado a código Gano; PHP lint en MU-plugins, tema hijo y plugins `gano-*`. |
| **Orquestación de trabajo** | `tasks.json` + workflow manual **Seed Copilot task queue**; deduplicación por `agent-task-id` en el cuerpo del issue. |
| **Coordinación humano–agente** | `.github/DEV-COORDINATION.md`, prompt `.github/prompts/copilot-bulk-assign.md`, guía `.github/COPILOT-AGENT-QUEUE.md`. |
| **Contenido / UI** | Documentación en `memory/content/` (clases Elementor, copy homepage); código de estilos en `gano-child`. |

## Límites conocidos (honestidad SOTA)

- Los agentes de código **no aplican** cambios en la base de datos de WordPress ni en el editor visual de Elementor en el servidor; ahí hacen falta **wp-admin** o flujos de import/export acordados.
- El **deploy** por Actions puede fallar por credenciales o entorno; es independiente de la calidad del diff en un PR del agente.
- **GitLab** como remoto adicional no sustituye el flujo descrito arriba si la prioridad es Copilot + Actions en GitHub.

## Referencias internas

- Skills: `.gano-skills/gano-github-copilot-orchestration/SKILL.md`, `gano-multi-agent-local-workflow`, `gano-content-audit`, `gano-wp-security`.
- Proyecto y decisión comercial: `memory/projects/gano-digital.md`.
- Fase 4 / billing propio (cuando aplique): `memory/research/fase4-plataforma.md`.
