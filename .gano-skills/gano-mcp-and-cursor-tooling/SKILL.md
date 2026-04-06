---
name: gano-mcp-and-cursor-tooling
description: >
  MCP en Cursor, GitHub (Pilot), Vitest/GSD SDK y extensiones VS Code para Gano Digital.
  Usar cuando el usuario pida MCP, servidores MCP, .cursor/mcp.json, integración GitHub desde el IDE,
  o alinear tooling con Copilot/Actions.
---

# Gano — MCP, Cursor y extensiones (2026-04)

## Fuente de verdad del código

- **GitHub:** `Gano-digital/Pilot` (`origin` único). GitLab archivado: `memory/ops/archived-gitlab-remote-2026-04.md`.

## MCP en este workspace (Cursor)

1. **Descriptores en el proyecto Cursor** (herramientas que el agente puede invocar):
   - **`user-github`** — issues, PRs, `assign_copilot_to_issue`, `create_pull_request_with_copilot`, `get_copilot_job_status`, `request_copilot_review`, etc. Requiere sesión/credencial GitHub válida en Cursor.
   - **`cursor-ide-browser`** — navegación y pruebas UI (lock → snapshot → interacción → unlock).

2. **`.cursor/mcp.json` en el repo** puede quedar vacío (`mcpServers: {}`) si los servidores están definidos en **Cursor Settings → MCP** (usuario o workspace). No commitear secretos; usar variables de entorno referenciadas solo en máquina local.

3. **Antes de llamar una herramienta MCP:** leer el descriptor JSON en la carpeta `mcps/<servidor>/tools/` del proyecto Cursor (o la documentación del servidor).

## Servidores MCP útiles para tareas Gano (elegir según necesidad)

| Servidor / tema | Para qué | Notas |
|-----------------|----------|--------|
| **GitHub** (integración Cursor o `@modelcontextprotocol` según doc actual) | PRs, issues, Copilot agent API vía tools | Ya cubierto por `user-github` en muchos setups |
| **Filesystem** (`@modelcontextprotocol/server-filesystem`) | Acceso a rutas acotadas del monorepo | Acotar `args` a la carpeta del proyecto |
| **Fetch** | Obtener docs/API públicas | Útil para investigar APIs GoDaddy, Wompi docs, etc. |
| **Git** (servidor MCP git oficial) | Historial, diffs, estado sin shell | Alternativa a `gh` cuando el MCP esté configurado |
| **PostgreSQL / SQLite** | Si en el futuro hay BD local de tooling | No sustituye MySQL de WordPress en hosting |
| **Browser (Cursor IDE)** | Smoke tests de gano.digital | Ya integrado como `cursor-ide-browser` |

Listas amplias: [modelcontextprotocol/servers](https://github.com/modelcontextprotocol/servers), [awesome-mcp-servers](https://github.com/wong2/awesome-mcp-servers), registro [registry.modelcontextprotocol.io](https://registry.modelcontextprotocol.io).

**Riesgo:** cada MCP amplía superficie de acceso; revisar permisos (filesystem paths, scopes de token GitHub).

## Extensiones VS Code / Cursor (recomendaciones en repo)

Ver **`.vscode/extensions.json`**: PHP (Intelephense), YAML (workflows), GitHub Actions, ESLint para `.gsd`, Markdown, PowerShell en Windows. Instalar con “Install Recommended Extensions”.

## Comandos locales frecuentes (sin MCP)

- **Cola agentes / CI:** `gh workflow run`, `gh pr list`, `gh pr checks` — ver `gano-github-copilot-orchestration` y `.cursor/rules/005-copilot-oversight.mdc`.
- **SDK GSD:** `cd .gsd/sdk && npm run test:unit` (Vitest 4).
- **Validar cola JSON:** `python scripts/validate_agent_queue.py`.

## Referencia Claude Code (plugins)

Integración MCP en plugins de Claude Code: copia de apoyo en **`.gano-skills/_reference-claude-official/mcp-integration/`** (skill **mcp-integration** del marketplace oficial).

## Skills relacionadas

- `gano-github-ops`, `gano-github-copilot-orchestration`, `gano-multi-agent-local-workflow`
