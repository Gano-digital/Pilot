# Sesión 2026-04-02 — Investigación SOTA, skills y continuidad

## Objetivo de la sesión

Investigación breve de estado del arte operativo, actualización de skills `.gano-skills/` alineadas con GitHub Pilot + Copilot + contenido Elementor, y registro para la **siguiente** sesión.

## Entregables hechos

1. **Research:** `memory/research/sota-operativo-2026-04.md` — qué significa SOTA en Gano, tabla de prácticas en repo, límites (BD/Elementor vs código, deploy, GitLab).
2. **Skill nueva:** `.gano-skills/gano-github-copilot-orchestration/SKILL.md` — cola `agent-queue`, `workflow_dispatch`, etiquetas, CI, prompt masivo, enlaces a `DEV-COORDINATION`.
3. **Skills actualizadas:**
   - `gano-multi-agent-local-workflow` — GitHub como cola; GitLab no prioritario; referencia a la skill nueva.
   - `gano-content-audit` — `elementor-home-classes.md`, copy homepage, menú `primary`, git vs servidor.
   - `gano-wp-security` — workspace correcto (sin ruta absoluta ajena); sección CI TruffleHog, SSH por env, `.gitignore`; matiz Reseller/Wompi.
   - `gano-fase4-plataforma` — pivot Reseller vs WHMCS documentado al inicio.
   - `gano-wompi-fixer` — workspace + nota Abril 2026 Reseller.
4. **Índice de skills:** fila añadida en `CLAUDE.md` para la skill nueva.

## Instrucciones para la siguiente sesión

### Prioridad inmediata

1. **Revisar PRs abiertos de Copilot** (p. ej. borradores sobre footer/contacto u otros): ejecutar o esperar **PHP lint** y **TruffleHog** en el PR; fusionar lo que pase y sea coherente con `TASKS.md`.
2. **Deploy rojo:** si `deploy.yml` sigue fallando, diagnosticar (secretos `SSH_*`, host, ruta remota, permisos). No asumir que el fallo invalida el código del tema.
3. **Servidor vs repo:** los cambios recientes en `gano-child` (menú `primary`, clases `gano-el-*`, versión `style.css`) deben **subirse al hosting** o desplegarse según el pipeline activo; Elementor en servidor sigue siendo manual salvo proceso documentado.

### Cola Copilot

- **Seed Copilot task queue** es solo **manual** (Actions → Run workflow). Un push a `tasks.json` **no** crea issues solo.
- Tras seed, asignar **GitHub Copilot** a los issues si aún no están asignados.
- Issues 100 % wp-admin: cerrar con comentario + checklist o enlazar a `DEV-COORDINATION.md`.

### Seguridad / higiene

- Rotar tokens o credenciales si algún workflow o historial expuso secretos (política ya acordada con Diego para cuando cierren el tema deploy).
- No reintroducir scripts con contraseñas en claro; `ssh_cli` / scripts: solo variables de entorno.

### Documentos de lectura rápida

- `memory/research/sota-operativo-2026-04.md`
- `.github/DEV-COORDINATION.md`
- `.github/COPILOT-AGENT-QUEUE.md`
- `memory/content/elementor-home-classes.md`

## Contexto de continuidad (resumen)

- Repo canónico en **GitHub** `Gano-digital/Pilot`, rama `main`.
- 17 issues tipo agente (#17–#33 en verificación previa) y flujo de prompt masivo en `.github/prompts/copilot-bulk-assign.md`.

---

*Fin del reporte de sesión 2026-04-02.*
