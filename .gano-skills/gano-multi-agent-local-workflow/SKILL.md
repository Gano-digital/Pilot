---
name: gano-multi-agent-local-workflow
description: >
  Orquesta trabajo entre Cursor, Claude Code, Antigravity (GSD/Gemini) y backups locales sin
  pisar cambios. Usar cuando haya varios agentes en el mismo repo, al mencionar "Antigravity",
  "Claude", "GSD", "ruta 1", "ruta 2", "Downloads/Claude", conflictos de git, scripts de DB/deploy,
  o riesgo de romper staging/producción.
---

# Gano — Flujo multi‑agente y entorno local

## Roles (qué es cada cosa)

| Herramienta | Rol típico | Dónde vive la config en este proyecto |
|-------------|------------|----------------------------------------|
| **Cursor** | Edición, terminal, git, revisión de diff | Workspace del repo |
| **Claude Code / carpeta local Claude** | Documentos, prompts, auditorías, HTML de arquitectura | Referencia en `C:\Users\diego\Downloads\Claude` (no es el código fuente del sitio) |
| **Antigravity** | Runtime GSD orientado a skills (Gemini); equivalente a "skills-first" | Global: `~/.gemini/antigravity/` · Proyecto: `./.agent/` (según instalador GSD en `.gsd/`) |
| **GSD (get-shit-done)** | Workflows y agentes compartidos; el instalador puede **convertir** comandos Claude → skills Antigravity | Submódulo `.gsd/` |

## Regla de oro: una sola fuente de verdad del código

- **Código y WordPress activos:** `C:\Users\diego\Downloads\Gano.digital-copia` (**ruta 1**). Aquí vive el repo git, `wp-content/`, `TASKS.md`, `memory/`, deploy.
- **Backup / mockups / snapshots:** `C:\Users\diego\Downloads\Gano.digital v2` (**ruta 2**). Tratar como **solo lectura** para comparar o rescatar archivos puntuales; **no** copiar carpetas enteras encima de ruta 1 sin revisión (mezcla WP, backups con plugins de riesgo, y restos no WP).

## Cómo no interferir entre agentes

1. **Antes de cambiar archivos:** `git status` — si otro agente dejó cambios, coordinar o trabajar en otra rama.
2. **No lanzar en paralelo:** segundo servidor PHP/MySQL, segundo `import_staging_db`, segundo deploy rsync, ni scripts que borren/importen BD sin confirmación explícita.
3. **Watchers Node:** si ya hay procesos `node` (p. ej. GSD), no iniciar otro dev server/build del mismo proyecto sin revisar puertos y `package.json` relevante.
4. **Plugins de fase y MU plugins:** no eliminar `gano-phase*` sin el flujo acordado; ver `memory/notes/plugins-de-fase.md`.

## Git y secretos

- **Nunca** dejar PAT de GitHub en la URL de `origin` (`https://TOKEN@github.com/...`). Usar `https://github.com/org/repo.git` y credencial vía **Git Credential Manager**, PAT al prompt, o **SSH**.
- Los tokens de GitHub (`ghp_`) no son GitLab; rotar en GitHub si hubo exposición.
- **GitLab** como remoto adicional: opcional/exploratorio; la integración CI y la cola Copilot viven en **GitHub** (`Gano-digital/Pilot`). No mezclar credenciales de GitLab en URLs trackeadas.

## GitHub como cola de trabajo (Abril 2026)

- **Issues `[agent]`** generados por `Seed Copilot task queue` + asignación a **Copilot coding agent** para PRs.
- **Backlog típico:** muchos PRs en **draft** — el agente entrega código/docs pero **no** fusiona; hace falta revisión humana y marcar listos para revisión. No confundir “mucho draft” con fallo del bot: suele ser **falta de triage**.
- **Documentación:** `.github/DEV-COORDINATION.md`, `.github/COPILOT-AGENT-QUEUE.md`, prompt masivo en `.github/prompts/copilot-bulk-assign.md` (dos bloques: oleada 1 vs oleada 3).
- Skill dedicada: **`gano-github-copilot-orchestration`** (incluye tabla de estado de outputs y prompts).

## Aprovechar lo que ya produjo Claude (workflow local)

- **Prompts y marketing:** `Downloads/Claude\Offloading_Tasks_LLMs_GanoDigital.md` — tareas para otros LLMs; no modifica el repo hasta que alguien copie el resultado a WP/Elementor.
- **Arquitectura de sitio / copy:** `Downloads/Claude\Arquitectura_Sitio_Web_2026.html` — referencia UX/copy; implementación en ruta 1 vía tema hijo y plantillas.
- **No leer** `settings.local.json` de Claude salvo necesidad explícita (suele contener datos sensibles).

## Antigravity y GSD (aprendizaje del repo)

- El submódulo **`.gsd/`** documenta que Antigravity usa **Skills** y rutas bajo `~/.gemini/antigravity/` (o `./.agent/` en local). Los instaladores pueden transformar rutas/comandos pensados para Claude hacia Antigravity.
- Para alinear Cursor con el mismo playbook: si GSD instaló comandos como skills en Antigravity, el contenido útil es el **mismo flujo** (plan → ejecutar → verificar); en Cursor se siguen `TASKS.md` y las skills `.gano-skills/*`.

## Checklist rápido antes de operaciones destructivas

- [ ] ¿Es imprescindible tocar BD o `wp-content/uploads`?
- [ ] ¿Hay backup o commit previo?
- [ ] ¿El cambio es solo en ruta 1 y revisado en diff?
- [ ] ¿Se evitó copiar desde ruta 2 sin diff archivo a archivo?

## Skills relacionadas

- `gano-github-copilot-orchestration` — Actions, cola JSON, Copilot agent.
- `gano-wp-security`, `gano-content-audit`, `gano-fase4-plataforma` — alcance técnico; `gano-wompi-fixer` solo **legacy**; esta skill es **proceso y convivencia** entre herramientas.
