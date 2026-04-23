# Dispatch Claude — cómo ejecutar la cola local

Este repo **no puede** conectar con la nube de Anthropic ni programar tareas en tu cuenta de Claude por ti. Lo que sí queda **programado aquí** es:

1. **`memory/claude/dispatch-queue.json`** — definición de tareas que **Claude puede hacer** dentro del repo (documentación, auditorías, alineación de markdown, validaciones).
2. **`scripts/claude_dispatch.py`** — elegir la siguiente tarea y llevar registro local de completadas.
3. **`scripts/validate_claude_dispatch.py`** — validar el JSON de la cola.

Todo lo que requiere **wp-admin, RCC de GoDaddy, secrets de GitHub o Elementor en vivo** sigue siendo **humano**; esas tareas en la cola generan **checklists** para Diego, no sustituyen el panel.

---

## VS Code / Cursor (tareas del workspace)

Con la carpeta del repo abierta como **workspace root**:

1. **Terminal → Run Task…** (`Ctrl+Shift+B` no aplica por defecto; usa la paleta) o **Ctrl+Shift+P** → `Tasks: Run Task`.
2. Elige por ejemplo:
   - **Gano: Claude dispatch — siguiente tarea (next)**
   - **Gano: Claude dispatch — listar**
   - **Gano: validar cola Claude dispatch**
   - **Gano: Claude dispatch — mostrar tarea** / **marcar completa** (pide el id `cd-repo-XXX`).
3. Atajos en **Cursor** (configurados en tu perfil local): **Ctrl+Shift+Alt+N** = next, **Ctrl+Shift+Alt+L** = listar, **Ctrl+Shift+Alt+V** = validar cola dispatch. Si chocan con otra extensión, cambiálos en *Keyboard Shortcuts* buscando `Run Task`.

Los archivos viven en **`.vscode/tasks.json`** (versionados en git). Si usás **VS Code** en lugar de Cursor, copiá la misma carpeta `.vscode` o sincronizá settings; los atajos van en `%APPDATA%\Code\User\keybindings.json` con las mismas entradas.

---

## Flujo rápido (Cursor o Claude Code)

1. Desde la raíz del repo:

   ```bash
   python scripts/validate_claude_dispatch.py
   python scripts/claude_dispatch.py next
   ```

2. Copia el bloque que imprime `next` (instrucciones + definition of done) y pégalo en Claude como **primer mensaje**, o di:

   > Ejecuta la tarea `cd-repo-XXX` definida en `memory/claude/dispatch-queue.json` en este workspace. Al terminar, resume archivos tocados y criterios de done.

3. Cuando la tarea esté cumplida en disco:

   ```bash
   python scripts/claude_dispatch.py complete cd-repo-XXX
   ```

4. Repite con `next`.

**Listado:** `python scripts/claude_dispatch.py list`  
**Una tarea concreta:** `python scripts/claude_dispatch.py show cd-repo-002`

---

## Estado local (`dispatch-state.json`)

- Archivo: `memory/claude/dispatch-state.json` (**en .gitignore**).
- Si no existe, se crea al primer `complete`.
- Para **reiniciar** la cola: borra ese archivo.

---

## Prompt maestro (copiar a Claude Projects / instrucciones del proyecto)

Usa este bloque como **instrucciones personalizadas** del proyecto o primer mensaje fijo:

```text
Trabajas en el repositorio Gano Digital (WordPress child theme gano-child, MU-plugins gano-*, GitHub Pilot).
Fuente de contexto: memory/claude/README.md y CLAUDE.md.

Si Diego pide efectos visuales o “showcases” (Canvas/SVG/generativo: fractales/metabolas/caleidoscopios/simetría):
- Lee y aplica la skill: .cursor/skills/gano-web-visual-systems/SKILL.md
- Usa el second brain: memory/research/visual-systems-canvas-svg-second-brain-2026-04.md
- Respeta prefers-reduced-motion y la política LCP: memory/research/motion-and-3d-policy-gano.md

Cuando Diego pida "siguiente tarea dispatch":
1. Sugiere ejecutar en terminal: python scripts/claude_dispatch.py next
2. Aplica en el repo lo indicado en instructions y paths de esa tarea.
3. No pidas secrets ni entres a wp-admin; si la tarea lleva requires_human_after=true, termina con checklist claro para humano.
4. Al cerrar: python scripts/claude_dispatch.py complete <id>

Reglas: no eliminar plugins gano-phase* sin confirmación explícita; no commitear credenciales.
```

---

## Si tu plan de Claude tiene "tareas programadas" o subagentes

Crea **una tarea programada por cada** `id` de `dispatch-queue.json` (o una por día con `next`). Cuerpo de cada tarea: salida de:

```bash
python scripts/claude_dispatch.py show cd-repo-00N
```

Así el contenido sigue siendo la **fuente de verdad** en git y no se desincroniza con copias manuales en la web.

---

## Relación con Copilot / GitHub

- **`.github/agent-queue/*.json`** → issues para **Copilot coding agent** en GitHub.
- **`memory/claude/dispatch-queue.json`** → trabajo para **Claude en el workspace** sin abrir issue.

Son colas distintas; pueden convivir.
