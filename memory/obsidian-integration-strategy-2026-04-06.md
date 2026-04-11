# Obsidian Integration Strategy

Date: 2026-04-06
Status: IMPLEMENTATION READY

## Resumen Ejecutivo

Obsidian vault es ahora la fuente de verdad centralizada para Gano Digital, accesible a agentes vía MCP (Model Context Protocol).

Acceso vía MCP:
- Claude Code ✅
- Cursor IDE ✅
- VS Code ✅
- Antigravity 🟡 (pending clarification)

## Componentes Implementados

### Estructura del Vault

00-PROJECTS/ - Overview y roadmap
01-ARCHITECTURE/ - Stack y decisiones técnicas
02-SECURITY/ - Hardening y patrones
03-COMMERCE/ - PFID mapping, Reseller RCC
04-TASKS/ - Trabajo activo
05-DAILY-NOTES/ - Daily/weekly/monthly
06-RESOURCES/ - Referencias externas
07-LEARNING/ - SOTA y investigaciones
_TEMPLATES/ - Templater templates
_ATTACHMENTS/ - Imágenes y PDFs

### Plugins (9 Total)

Críticos (must-have):
- Local REST API (expone vault vía HTTPS)
- Dataview (queries dinámicas)
- Templater (automatización)
- Periodic Notes (daily auto-create)
- Tasks (task management)
- Obsidian Git (version control)

Nice-to-have:
- Calendar
- Advanced Tables
- Code Editor Shortcuts

### MCP Configuration

Dos MCP servers:
1. obsidian-mcp-server (primary, CRUD operations)
2. obsidian-semantic-mcp (optional, RAG search)

Local REST API: Puerto 27124 (HTTPS con API key)

### Templates Creadas

✅ daily-template.md - Diarios automáticos
✅ weekly-template.md - Reviews semanales
✅ monthly-template.md - Retrospectivas mensuales
✅ gano-digital-overview.md - Intro vault

## Setup Checklist (Diego)

Fase 1: Instalación plugins (30 min)
- Obsidian 1.6+ instalado
- Vault carpeta creado
- 9 plugins instalados + habilitados
- Local REST API API key anotado

Fase 2: MCP Servers (15 min)
- npm install -g obsidian-mcp-server
- Verificar instalación

Fase 3: Configuración (20 min)
- .env con OBSIDIAN_API_KEY
- .cursor/mcp.json actualizado
- CORS habilitado en Obsidian
- Verificación MCP access

Fase 4: First Use (10 min)
- Primera daily note (auto-created)
- Test Dataview query
- Verificar Obsidian Git auto-commit

Total: ~75 minutos

## Integración con Stack Existente

VS Code: Tasks pueden actualizar vault vía REST API
Cursor: Lee patrones de seguridad y PFID del vault
Claude: Queries SOTA y decisiones via MCP
Git: Obsidian Git auto-commits a repo

## Success Criteria

✅ Vault accesible vía MCP desde Cursor/Claude
✅ Daily notes auto-creándose
✅ Dataview queries funcionando
✅ Obsidian Git auto-commitiendo
✅ Agentes pueden leer arquitectura/seguridad
✅ Zero secrets en vault
✅ Tasks sincronizadas con dispatch queue

## Next Steps (Diego)

Week 1 (Apr 6-13):
- Instalar Obsidian + plugins
- Configurar MCP servers
- Verificar daily notes
- Task 001: Cursor MCP verification

Week 2+:
- Populate vault con notas del repo
- Crear tareas en 04-TASKS
- Daily standups en vault
- Weekly reviews

Est. Setup Time: 75 minutos
Next Review: 2026-04-13
