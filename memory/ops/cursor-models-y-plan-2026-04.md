# Cursor + modelos — plan de pago, “máxima potencia” y límites de automatización

## Verdad incómoda (importante)

| Qué quieres | Qué se puede automatizar desde el repo Gano |
|-------------|---------------------------------------------|
| Elegir el modelo **más capaz** en Chat / Agent / Composer | **No** desde Git. Es **cuenta Cursor + app** (y a veces `settings.json` local). |
| Que **todos** los devs usen el mismo modelo por defecto | Solo si compartís **`.vscode/settings.json`** en el workspace **y** Cursor respeta esas claves en vuestra versión (cambia entre versiones). |
| **Consumir** el máximo de créditos del plan sin desperdicio | Se configura con **modelo por tarea**, **Auto** vs **fijo**, y **usage-based** (facturación por uso). |
| GitHub **Copilot** (otro producto) | Políticas en **github.com** / org; no es el mismo motor que el chat nativo de Cursor. |

No existe un archivo en `Pilot` que “suban a GitHub” y cambie sola la potencia del modelo en tu máquina: es **intencional** (evitar que un repo te cambie facturación).

---

## Dónde se configura en Cursor (orden práctico)

### 1. Modelos habilitados y por defecto (lo más importante)

1. Abre **Cursor** → **Settings** (Ctrl+,) → busca **“Cursor Settings”** o el engranaje **Cursor** (no solo VS Code).
2. Entra a **Models** / **Features** (nombres varían según versión).
3. Activa **todos los modelos** que tu plan permita (Sonnet, Opus, GPT-4.x, etc.). Si un modelo aparece bloqueado, es **plan o región**, no el repo.
4. Para **máxima calidad** en tareas difíciles (refactors, seguridad, arquitectura):
   - Elige el modelo **frontier** más reciente que Cursor liste para **Agent** (suele ser familia **Claude Sonnet / Opus** o el **GPT** más alto según la tabla de esa pantalla).
5. **Auto model**: si lo activas, Cursor reparte entre modelos según tarea (ahorra pero no siempre usa el más caro). Si quieres **exprimir el plan**, suele ser mejor **desactivar Auto** y **fijar** el mejor modelo para Agent/Composer.

### 2. Créditos y “seguir usando cuando se acaban”

- **Cursor Settings** → **Subscription / Billing** (o en [cursor.com](https://cursor.com) cuenta).
- Activa **usage-based pricing** (nombre aproximado) si tu plan lo ofrece: así **no se corta** el trabajo cuando se agotan los créditos incluidos; pagas extra según uso.
- Revisa el **dashboard de uso** para ver qué feature (Chat vs Agent vs Composer) más consume.

### 3. API propia (BYOK) — opcional

- En **Cursor Settings** → sección de **API Keys** / **OpenAI** / **Anthropic** (según versión).
- Si añades **clave propia** de Anthropic u OpenAI, puedes desbloquear **más capacidad** o modelos según contrato con ese proveedor; el **coste** va por ese proveedor, no solo por Cursor.

### 4. GitHub Copilot (extension / integración)

- En **GitHub** → **Settings** → **Copilot** (usuario u org): políticas de features.
- En Cursor, la extensión **GitHub Copilot** tiene **sus propios** ajustes de modelo (p. ej. `gitlens.ai` o Copilot chat); es **independiente** del modelo del Agent de Cursor.
- Para **repos** en GitHub: **Copilot coding agent** en la nube usa la política de **GitHub**, no el `settings.json` local.

---

## Qué poner en `settings.json` (opcional, verificar en tu versión)

Ruta Windows: `%APPDATA%\Cursor\User\settings.json`

Algunas versiones de Cursor exponen claves del tipo:

- `cursor.general.model` / `cursor.chat.model` / `cursor.agent.model` — **los nombres cambian**.

**Método seguro:** en Cursor, **Command Palette** → **“Preferences: Open User Settings (JSON)”** y busca en el autocompletado las claves que empiecen por `cursor.` relacionadas con **model** o **agent**.

No copies ciegamente ejemplos de internet: un id de modelo obsoleto produce el error tipo *“model does not work with your plan”*.

---

## Recomendación de uso para tareas Gano (sin automatizar por archivo)

| Tarea | Modelo sugerido (lógica) |
|-------|---------------------------|
| PHP/WordPress, MU-plugins, seguridad | El **más reciente** “Sonnet / Opus” o equivalente que Cursor marque como **mejor para código** |
| Edits masivos Composer | Mismo o **Composer default** si la UI lo separa |
| Docs, TASKS, memoria | Puede ser un modelo más barato o Auto si priorizas créditos |

---

## Qué dejamos en el repo (solo documentación)

- Esta guía: `memory/ops/cursor-models-y-plan-2026-04.md`
- Skill: `.gano-skills/gano-cursor-models/SKILL.md` (para que los agentes en Cursor no prometan “cambiar el modelo desde git”).

---

## Si quieres “un solo clic” en el futuro

Opciones reales:

1. **Script local** (PowerShell) que copie un `settings.json` plantilla a `%APPDATA%\Cursor\User\` — solo tú lo ejecutas; no es automático al clonar.
2. **Política de equipo** (IT): desplegar Cursor con MSI + `settings.json` corporativo (Enterprise).
3. **Cursor Teams / Enterprise** (si contratáis): políticas centralizadas desde el proveedor.

Para un fundador solo, lo sostenible es **ajustar una vez** en la UI y revisar **billing** mensual.
