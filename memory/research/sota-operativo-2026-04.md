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
| **Orquestación de trabajo** | Seis colas JSON en `.github/agent-queue/` + workflow **08 · Sembrar cola Copilot**; deduplicación por `agent-task-id` en el cuerpo del issue. |
| **Coordinación humano–agente** | `.github/DEV-COORDINATION.md`, prompt `.github/prompts/copilot-bulk-assign.md`, guía `.github/COPILOT-AGENT-QUEUE.md`. |
| **Contenido / UI** | Documentación en `memory/content/` (clases Elementor, copy homepage); código de estilos en `gano-child`. |

## Límites conocidos (honestidad SOTA)

- Los agentes de código **no aplican** cambios en la base de datos de WordPress ni en el editor visual de Elementor en el servidor; ahí hacen falta **wp-admin** o flujos de import/export acordados.
- El **deploy** por Actions puede fallar por credenciales o entorno; es independiente de la calidad del diff en un PR del agente.
- **GitLab** como remoto adicional no sustituye el flujo descrito arriba si la prioridad es Copilot + Actions en GitHub.

## Investigación complementaria — Copilot coding agent (documentación oficial GitHub)

Síntesis alineada a la documentación oficial: [Best practices (coding agent)](https://docs.github.com/en/copilot/tutorials/coding-agent/best-practices) y [Reviewing a pull request created by Copilot](https://docs.github.com/en/copilot/how-tos/agents/copilot-coding-agent/reviewing-a-pull-request-created-by-copilot) (2026):

| Principio | Implicación en Gano |
|-----------|---------------------|
| **Issues bien acotados** | La cola versionada (`tasks*.json`) fuerza título, cuerpo, rutas y `agent-task-id`; evita tareas vagas o mezcladas. |
| **Criterios de aceptación** | Los issues oleada 3/4/infra/API describen entregables en `memory/` u operación; el revisor valida contra eso, no solo el diff. |
| **Iteración antes de merge** | Draft PR → marcar **Ready** → squash merge tras CI; mencionar `@copilot` en comentarios del PR si hace falta corrección en rama. |
| **Workflows y Actions** | Revisar con lupa los cambios en `.github/workflows/` que proponga un agente; los workflows no siempre se ejecutan igual en ramas de agente. |
| **Aprobaciones** | Si hay branch protection, la aprobación del autor no cuenta; hace falta otro revisor o bypass según política org. |

Esto refuerza el playbook local: `.github/prompts/copilot-bulk-assign.md`, `MERGE-PLAYBOOK.md`, `AGENT-REVIEW-CHECKLIST.md`.

## Integraciones externas (referencia de investigación)

- Resumen de portales **Mercado Libre Developers** y **GoDaddy Developer API** (sin credenciales): [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md).
- Cola delegable para profundizar: `.github/agent-queue/tasks-api-integrations-research.json`.

---

## Referencias internas

- Skills: `.gano-skills/gano-github-copilot-orchestration/SKILL.md`, `gano-multi-agent-local-workflow`, `gano-content-audit`, `gano-wp-security`.
- Proyecto y decisión comercial: `memory/projects/gano-digital.md`.
- Fase 4 / billing propio (cuando aplique): `memory/research/fase4-plataforma.md`.
- Progreso ejecutivo en una página: [`memory/sessions/2026-04-02-progreso-consolidado.md`](../sessions/2026-04-02-progreso-consolidado.md).

_Última ampliación SOTA: 2026-04-03._
