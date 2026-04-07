# Progress Tracker

## 2026-04-07 — Auditoría desarrolladores + Constellation (abierto / entregable)

### Completado

- [x] Regla Cursor `102-constellation-and-gano-skills.mdc` (Constellation, skills, TASKS/CLAUDE)
- [x] `memory/audits/`: PDF `gano-digital-auditoria-desarrolladores-*.pdf` + HTML con retratos y estadísticas
- [x] Script `scripts/generate_dev_audit_pdf.py` (fpdf2 + Arial Windows); `.gitignore` excepción `memory/audits/*.pdf`, ignorados `vendor/` y `.obsidian/`
- [x] Skill `gano-starcraft1-assets-constellation` + research `starcraft1-assets-sota.md` enlazados en CLAUDE y QUICK_START

### Pendiente (equipo)

- [ ] Revisar PDF/HTML con desarrolladores; regenerar PDF tras hitos mayores

---

## 2026-04-02 — Setup entorno y gobernanza (cerrado)

### Completado

- [x] VS Code instalado y configurado (extensiones según sesión)
- [x] Cursor: **9** rules `.mdc` (contexto, boundaries, security, error handling, copilot oversight, memory protocol, PHP/WP, CSS/JS, git workflow)
- [x] Cursor hooks configurados
- [x] Cursor memory bank inicializado (`projectBrief`, `techContext`, `activeContext`, `progress`, `deferredItems`)
- [x] `AGENTS.md` (estándar cross-tool)
- [x] Workflows / repo: `copilot-setup-steps.yml`, `CODEOWNERS` (según sesión de setup)
- [x] Documentación Copilot repo: `copilot-instructions.md`, cola `.github/agent-queue/`, playbooks merge/coordinación

### Cierre explícito

- [x] **“Setup Cursor rules + memory protocol”** — considerado **completado**; el foco del equipo pasa a **entrega en producción + triage GitHub**.

---

## 2026-04-02 — Post-actualización (memory bank alineado)

### Estado operativo (referencia `TASKS.md`)

- Código **Fases 1–3** sólido en repo; pendiente **deploy** y tareas de **panel/servidor**.
- GitHub: cola de agentes **~32 issues** (#17–33, #54–68); **~35 PRs abiertos**, mayoría **draft** — siguiente esfuerzo: **revisión humana + CI + merge**.

### En progreso

- [ ] SSH/SFTP GoDaddy (según lo que permita el plan)
- [ ] Revisión y merge de PRs Copilot
- [ ] Deploy F1–3 + eliminación `wp-file-manager` + SEO/GSC/Rank Math
- [ ] Homepage Elementor + Fase 4 Reseller

### Backlog

- [ ] Migrar / alinear contenido staging si aplica
- [ ] MCPs adicionales (Figma, Dataplex, etc.) si se priorizan
- [ ] Rotación de tokens y limpieza de remotes con credenciales (cuando toque despliegue masivo)

---

## 2026-04-03 — Dispatch Claude + validación entorno (cerrado)

### Completado

- [x] Cola `memory/claude/dispatch-queue.json` completada (`cd-repo-001` a `cd-repo-012`).
- [x] Checklists operativos creados y/o actualizados para:
    - RCC -> PFID
    - Gano SEO + GSC + Rank Math
    - Remoción de `wp-file-manager`
    - Wordfence + 2FA
- [x] PHP 8.3 CLI habilitado en Windows local.
- [x] `php -l wp-content/themes/gano-child/functions.php` validado sin errores.
- [x] Acceso al repo confirmado por HTTPS (`origin`).

### Hallazgos

- [ ] SSH a GitHub aún no autentica con la clave local actual.
- [ ] SSH al servidor `72.167.102.145` aún no autentica con las claves locales actuales.

### Estado operativo

- Repo: operativo por HTTPS.
- Cursor memory bank: requiere usar `activeContext.md` actualizado 2026-04-03 como punto de arranque.
- Próximo cuello real: deploy/servidor + panel WordPress, no cola Claude.

---

## Cómo usar este archivo

Tras hitos importantes, añadir una sección con fecha o actualizar la sección **Post-actualización** para que nuevas sesiones de Cursor lean el estado en `.cursor/memory/activeContext.md` y aquí.
