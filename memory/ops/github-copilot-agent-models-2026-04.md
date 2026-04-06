# GitHub Copilot coding agent — modelos y políticas (org Gano-digital)

Esta guía es solo para **Copilot en GitHub.com** (coding agent / cloud agent), no para Cursor en tu PC.

## Dónde se elige el modelo (no está en el repo)

El **model picker** aparece al:

- Asignar un issue a **Copilot** en **github.com**, o  
- Mencionar `@copilot` en un comentario de PR, o  
- Iniciar tarea desde **Agents** / panel de agentes / GitHub Mobile / Raycast (según [documentación oficial](https://docs.github.com/en/copilot/how-tos/use-copilot-agents/coding-agent/changing-the-ai-model)).

Si **no** hay selector, se usa **Auto** (selección automática por disponibilidad y límites).

**No existe** (a fecha de documentación GitHub) un archivo en el repositorio que fije el modelo por defecto para todos los agentes: es **cuenta + organización + políticas**.

## Modelos soportados (referencia docs GitHub)

Lista típica (puede ampliarse; ver siempre la UI actual):

- **Auto**
- **Claude Sonnet 4.5**
- **Claude Opus 4.5 / 4.6**
- **GPT-5.x-Codex** (variantes según plan)

> Los nombres exactos salen en el **model picker** al asignar el agente.

## Planes y quién puede elegir

- **Copilot Business / Enterprise:** model picker para coding agent según políticas de la org ([changelog](https://github.blog/changelog/)).
- **Copilot Pro / Pro+:** según evolución de producto; si no ves picker, contacta soporte o revisa plan.

## Qué debe hacer el **owner** de la organización (imprescindible para “máxima potencia”)

Las políticas de **modelos** están en la org o enterprise:

1. GitHub → **Organización `Gano-digital`** → **Settings** → **Copilot** → **Policies** (características) y página **Models** (modelos).
2. En **Models policy**: habilitar los modelos **premium / adicionales** que el plan permita. Si un modelo está **deshabilitado** a nivel org, **no aparecerá** en el picker (los usuarios pueden quedarse en Auto o en un modelo base).
3. Los modelos extra pueden tener **coste adicional** según contrato GitHub — revisar facturación / “Copilot premium models” en la documentación de tu tier.

Referencias:

- [Políticas de Copilot](https://docs.github.com/en/copilot/concepts/policies)
- [Gestionar políticas en la organización](https://docs.github.com/en/copilot/how-tos/administer/organizations/managing-policies-for-copilot-in-your-organization)

## Qué pueden hacer los **miembros** del repo (cada tarea)

1. Abrir el issue → **Assign** → **Copilot** (o flujo de Agents).
2. En el modal, abrir el **model picker** y elegir:
   - **Claude Opus 4.6** (o el Opus más alto listado) para tareas **difíciles** (seguridad, refactors grandes, arquitectura).
   - **Claude Sonnet 4.5/4.6** para la mayoría de PRs de código y docs en repo.
   - **Auto** si priorizas **menos rate limit** y velocidad sobre “siempre el más caro”.
3. Pegar el prompt adicional desde [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) como ya hacéis.

## Automatización

- **Issues generados por workflow 08** no incluyen modelo en el YAML: el modelo se elige **en el momento de asignar** a Copilot.
- **No hay API pública** documentada para “default model = Opus para todo el org” en todos los runs del agente: hay que **política org (Models)** + **elección humana** al asignar (o confiar en **Auto**).

## Coherencia con el repo

- `.github/copilot-instructions.md` sigue siendo el **contexto del proyecto** (stack Gano, reglas PHP, Reseller).
- El **modelo** solo cambia **comportamiento/capacidad** del LLM, no sustituye las instrucciones del repo.
