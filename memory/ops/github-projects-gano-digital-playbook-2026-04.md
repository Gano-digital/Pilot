# GitHub Projects — tablero **@Gano.digital** (Pilot) · Playbook 2026-04

Objetivo: **una sola interfaz** donde se vea el hilo de trabajo (issues `[agent]`, PRs, decisiones) con **contexto para el equipo** — sin confundir **proyecto** con **repositorio**.

| Concepto | Qué es |
|----------|--------|
| **@Gano.digital** | **Proyecto** (Project) en GitHub — tableros, vistas, campos, workflows, *status updates*. |
| **Pilot** | **Repositorio** donde vive el código (`Gano-digital/Pilot`). Las tarjetas del proyecto enlazan **issues/PRs de Pilot**. |

---

## 1. Campos personalizados (Fields) — configuración recomendada

En el proyecto: **Settings (del proyecto)** → **Fields** (o desde una vista → **+** en columna de campo).

### Obligatorios (mínimo viable inteligente)

| Campo | Tipo | Opciones / uso |
|-------|------|----------------|
| **Status** | Single select | `Todo`, `In progress`, `Done`, `Blocked` (añade **Blocked** si no existe). |
| **Priority** | Single select | `P0 — Urgente`, `P1 — Esta iteración`, `P2 — Siguiente`, `P3 — Backlog`. |
| **Area** | Single select | `theme`, `mu-plugins`, `plugins-gano`, `docs-memory`, `infra`, `content-seo`, `ci-actions`, `billing-reseller`. |
| **Source** | Single select | `Copilot seed 08`, `Homepage 09`, `Manual`, `Dependabot`, `Security`. |

### Muy recomendados para reporting

| Campo | Tipo | Uso |
|-------|------|-----|
| **Iteration** | **Iteration** (campo nativo) | Sprint quincenal o semanal — alimenta vista *Current iteration* e **Insights**. |
| **Team** | Single select | Sustituir o refinar `No Team` vs `Engineering` por nombres reales (`Core`, `Contenidos`, `Infra`) cuando el equipo crezca. |
| **Parent** | (sub-issues / dependencias) | Issues grandes → sub-issues enlazadas; el proyecto muestra jerarquía. |

### Texto libre (contexto para colaboradores)

| Campo | Tipo | Uso |
|-------|------|-----|
| **Context / notas** | Text | Resumen en 1–3 frases: qué bloquea, qué validó Diego, enlace a `memory/sessions/…` o PR. |
| **Stakeholder** | Single select opcional | `Interno`, `Cliente`, `Legal` — solo si aporta claridad. |

**Regla:** al mover a **Done**, rellenar **Context** o enlazar el **PR** en el issue para que el tablero sea legible sin abrir 10 pestañas.

---

## 2. Vistas (Views) — qué configurar

| Vista | Tipo sugerido | Filtro / agrupación |
|-------|----------------|---------------------|
| **Status board** | Board por **Status** | Ya la tienes — mantener **Todo / In progress / Done**. Opcional: *swimlanes* por **Priority** (si Projects lo permite en tu plan) o segunda dimensión por **Area**. |
| **Prioritized backlog** | Table o Board | Ordenar por **Priority** asc; filtro `Status` ≠ Done. |
| **Current iteration** | Board o Table | `Iteration` = iteración activa; `Status` ≠ Done. |
| **Roadmap** | **Roadmap** (timeline) | Agrupar por **Iteration** o milestone; issues con fechas de inicio/fin si las usáis. |
| **Bugs** | Table | `label:bug` o `Area` = flujo de calidad. |
| **My items** | Table | `Assignee` = @me — para cada colaborador. |
| **Agent queue** | Table | Título contiene `[agent]` o etiqueta `copilot`; útil para ver solo trabajo delegable al agente. |

**Tip:** guardar **URL** de la vista *Agent queue* en el README interno del equipo.

---

## 3. Workflows nativos del proyecto (⚡ automatización sin YAML)

En el proyecto: botón **Workflows** (arriba a la derecha).

Configura al menos:

1. **Al cerrar un issue** → poner **Status** = **Done** (o equivalente).  
   - Así el tablero refleja cierre real sin arrastrar tarjetas a mano.

2. **Al fusionar un PR vinculado** → mover el issue enlazado a **Done** (si GitHub ofrece la plantilla en tu cuenta).  
   - Refuerza el hilo PR → issue → proyecto.

3. **Al añadir un ítem al proyecto** → valores por defecto:  
   - **Priority** = `P3 — Backlog`  
   - **Source** = `Manual` (luego ajustáis si viene del workflow 08).

4. **Auto-close issue** (changelog 2024): si movéis una tarjeta a **Done**, valorar **cerrar el issue** automáticamente **solo** si no usáis Done como “listo en tablero pero aún abierto en GitHub”. Para Gano suele ser mejor: **cerrar issue en GitHub cuando el trabajo está en `main`**, y entonces el workflow 1 lo pasa a Done.

**Orden mental:** GitHub issue es la fuente de verdad del *estado*; el proyecto es la *visualización* para humanos.

---

## 4. Status updates (informe del hilo para el equipo)

Botón **Add status update** en el proyecto.

### Cadencia sugerida

- **Semanal** (viernes o lunes): 5–10 líneas + bullets.
- **Ad-hoc** cuando cierre de oleada, deploy sensible o decisión de producto.

### Plantilla (copiar desde `.github/templates/project-status-update.md`)

Incluir siempre:

- **Iteración:** fechas o nombre del sprint.
- **Hecho:** 3–5 bullets con enlace a PRs o issues (#n).
- **En curso:** 2–4 bullets (quién / qué falta).
- **Riesgos / bloqueados:** 0–3 bullets (SSH, Vercel, DNS, etc.).
- **Contexto enlazado:** `TASKS.md`, `memory/sessions/…`, `DEV-COORDINATION`.

Los colaboradores pueden **suscribirse** al proyecto o mirar la pestaña de updates sin entrar al repo.

---

## 5. Insights (métricas)

**Insights** en el mismo proyecto: gráficos de **ciclo** (tiempo en columnas), **burnup/burndown** por iteración, throughput.

Uso para stakeholders:

- Captura de pantalla quincenal o enlace en el *status update*.
- Filtrar por **Area** para ver si todo el esfuerzo se fue a `theme` vs `infra`.

---

## 6. Automatización en el repo (opcional)

Workflow **13 · Projects · Añadir issues al tablero**: añade al proyecto los issues nuevos que cumplan criterio (`[agent]` en título o etiqueta `copilot`), si configuráis variable y secreto (ver `.github/workflows/project-add-to-project.yml`).

Esto **no sustituye** los Workflows nativos del proyecto; los complementa para que nada se quede fuera del tablero.

---

## 7. Permisos y colaboradores

- **Organización:** miembros con acceso de **lectura** al proyecto pueden ver tablero e insights.
- Para **editar** campos y workflows del proyecto: rol adecuado en la org/repo (maintain/write según política).
- Enlazar en onboarding del equipo: `.github/DEV-COORDINATION.md` + este playbook.

---

## 8. Convención de títulos (ya alineada con la cola)

- Prefijo **`[agent]`** en issues sembrados por **08** — mantenerlo; la vista *Agent queue* lo filtra.
- PRs del Copilot: en el PR, **enlazar issue** (`Closes #n` o `Ref #n`) para que el hilo sea trazable desde el proyecto.

---

## Referencias

- [GitHub Docs — Projects](https://docs.github.com/en/issues/planning-and-tracking-with-projects)
- Changelog *auto-close* al mover a Done: [blog GitHub 2024-04](https://github.blog/changelog/2024-04-25-github-issues-projects-auto-close-issue-project-workflow/)
- Repo: `.github/GITHUB-PROJECT-GANO-DIGITAL.md` (entrada rápida)
- Skill agentes: `.gano-skills/gano-github-projects-board/SKILL.md`
