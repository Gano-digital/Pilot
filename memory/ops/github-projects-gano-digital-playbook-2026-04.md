# GitHub Projects — tablero **@Gano.digital** ↔ repo **Pilot** · Playbook operativo

**Versión:** 2026-04 · **Ámbito:** organización **Gano-digital**, repositorio **`Gano-digital/Pilot`** (`main`).

**Objetivo:** una interfaz de planificación y **comunicación de progreso** alineada a prácticas habituales en ingeniería de producto (trazabilidad issue ↔ PR ↔ código, priorización, iteraciones, reporting), sin duplicar la fuente de verdad del código.

---

## 0. Mapa conceptual (evitar ambigüedad)

| Término | Definición en Gano Digital |
|---------|----------------------------|
| **Proyecto @Gano.digital** | **GitHub Project** (v2): vistas, campos personalizados, *workflows* del tablero, *status updates*, Insights. |
| **Repositorio Pilot** | **`Gano-digital/Pilot`**: código WordPress Gano, `.github/`, `memory/`, `TASKS.md`. Los ítems del tablero son **issues y PRs de este repo** (salvo que enlacéis otro explícitamente). |
| **Fuente de verdad del estado “cerrado”** | **Issue/PR en GitHub** + merge a **`main`** + `TASKS.md` / `memory/sessions/` cuando aplique. El tablero **refleja** y **comunica**; no sustituye git. |

---

## 0.1 Rutas canónicas en el repo (verificadas desde la raíz)

Usar siempre rutas relativas a la **raíz del clon** (`Pilot`).

| Recurso | Ruta |
|---------|------|
| Este playbook | `memory/ops/github-projects-gano-digital-playbook-2026-04.md` |
| Entrada corta tablero ↔ repo | `.github/GITHUB-PROJECT-GANO-DIGITAL.md` |
| Plantilla *status update* | `.github/templates/project-status-update.md` |
| Coordinación dev / servidor | `.github/DEV-COORDINATION.md` |
| Instrucciones Copilot (repo) | `.github/copilot-instructions.md` |
| Cola masiva agente | `.github/COPILOT-AGENT-QUEUE.md` |
| Colas JSON (7 archivos) | `.github/agent-queue/` (`tasks.json`, `tasks-wave2.json`, … `tasks-security-guardian.json`) |
| Workflow añadir al Project (13) | `.github/workflows/project-add-to-project.yml` |
| Índice workflows 01–13 | `.github/workflows/README.md` |
| Tareas y fases del producto | `TASKS.md` |
| Playbook humanos + agentes | `memory/ops/agent-playbook-asistentes-2026-04.md` |
| Ops GitHub (rulesets, Dependabot) | `memory/ops/github-github-ops-sota-2026-04.md` |
| Modelos Copilot (org) | `memory/ops/github-copilot-agent-models-2026-04.md` |
| Skill tablero (IA local) | `.gano-skills/gano-github-projects-board/SKILL.md` |

---

## 1. Matriz de campos del proyecto (definición estándar)

Configuración recomendada en **Project settings → Fields**. Los valores deben **coincidir literalmente** entre personas para que filtros e Insights sean comparables.

### 1.1 Campos obligatorios (mínimo viable “enterprise light”)

| Field (nombre en UI) | Tipo GitHub | Valores permitidos | Mapeo a repo real |
|------------------------|-------------|--------------------|-------------------|
| **Status** | Single select | `Todo`, `In progress`, `Done`, `Blocked` | Ciclo de vida en tablero. **Blocked** = dependencia externa (SSH, DNS, aprobación). |
| **Priority** | Single select | `P0`, `P1`, `P2`, `P3` | **P0** incidente/seguridad; **P1** iteración actual (`TASKS.md` 🔴); **P2** siguiente; **P3** backlog. |
| **Area** | Single select | Ver tabla 1.2 | Alineado a carpetas y a etiquetas `area:*` del labeler. |
| **Source** | Single select | Ver tabla 1.3 | Origen del trabajo (trazabilidad a Actions / humano). |

### 1.2 Valores recomendados — **Area** (alineados al código y a `.github/labeler.yml`)

| Valor en Project | Correspondencia en repo |
|------------------|-------------------------|
| `theme` | `wp-content/themes/gano-child/**` · etiqueta GitHub `area:theme` |
| `mu-plugins` | `wp-content/mu-plugins/**` (p. ej. `gano-security.php`, `gano-seo.php`) · `area:mu-plugins` |
| `plugins-gano` | `wp-content/plugins/gano-*/**` · `area:plugins-gano` |
| `ci-actions` | `.github/**` · `area:ci` |
| `docs-memory` | `memory/**`, `TASKS.md`, `CLAUDE.md`, reportes en `reports/` |
| `content-seo` | Copy, landings, Elementor (mucho trabajo en servidor; documentar en issue) |
| `infra` | DNS, SSL, hosting, runbooks en `memory/ops/` · etiqueta `infra` |
| `commerce-reseller` | GoDaddy Reseller, Woo vitrina, `gano-reseller-*` — según `TASKS.md` Fase 4 |

*Nota:* el labeler solo aplica **PRs**; en issues del tablero **rellenar Area a mano** o por convención de título/cola.

### 1.3 Valores recomendados — **Source** (trazabilidad a automatización)

| Valor en Project | Origen típico |
|------------------|---------------|
| `workflow-08-copilot-queue` | Issues creados por **08 · Sembrar cola Copilot** (`seed-copilot-queue.yml`) |
| `workflow-09-homepage` | **09 · Sembrar issues homepage** (`seed-homepage-issues.yml`) |
| `workflow-10-orchestrate` | **10 · Orquestar oleadas** (issues oleada 2 / consolidación) |
| `dependabot` | PRs/issues con etiqueta `dependencies` |
| `manual` | Creado a mano o fuera de plantillas |
| `security-guardian` | Cola `tasks-security-guardian.json` + skill sesión / higiene |
| `coordination` | Etiqueta `coordination` / plantilla sync servidor ↔ git |

---

## 2. Campos adicionales (recomendados para reporting)

| Field | Tipo | Uso |
|-------|------|-----|
| **Iteration** | Iteration (nativo) | Sprint 1–2 semanas; alimenta vista *Current iteration* y **Insights** (throughput). |
| **Start / Target date** | Date (si disponible) | Roadmap; opcional en fase inicial. |
| **Parent issue** | Sub-issue / vínculo | Epics (pocas tarjetas padre; hijos `[agent]`). |
| **Team** | Single select | P. ej. `Core`, `Contenidos`, `Infra` — evitar indefinido `No Team` a medio plazo. |
| **Context link** | Text | Una línea: `PR #nn`, `memory/sessions/…`, o bloqueo (Vercel, SSH). |

---

## 3. Matriz RACI (roles sobre el tablero)

Estilo estándar de industria: quién hace qué a nivel **proceso**, no por tarea.

| Actividad | **R**esponsable | **A**probador (Accountable) | **C**onsultado | **I**nformado |
|-----------|-----------------|----------------------------|----------------|---------------|
| Mantener campos y vistas del Project | Líder técnico / Diego | Diego | Colaboradores | Equipo |
| *Status update* semanal | Diego (o rotación) | Diego | — | Todo el equipo |
| Mover ítems a **Done** | Quien cierra el issue / fusiona PR | Diego (merge final sensible) | — | Equipo vía tablero |
| Configurar *workflows* nativos del Project | Diego | Diego | — | Documentado en este playbook |
| Variable `GANO_PROJECT_URL` + PAT workflow 13 | Diego (org admin) | Diego | — | — |

---

## 4. Definición de lista (DoR) y hecho (DoD) en el tablero

| | Criterio |
|---|----------|
| **Definition of Ready (DoR)** | Issue con **título claro**, criterio de aceptación en cuerpo o enlace a `memory/`; **Area** y **Priority** rellenados; si es `[agent]`, incluye `<!-- agent-task-id:… -->` cuando viene de cola JSON. |
| **Definition of Done (DoD)** | Código en `main` (o explícitamente “solo servidor/wp-admin” con checklist en issue); **PR** enlazado si hubo cambio en repo; **Status** = Done; **Context link** con `#PR` o sesión; CI relevante verde cuando aplique. |

Alineado a la realidad Gano: muchas tareas son **Elementor/servidor** — el DoD honesto es “issue cerrado con **pasos ejecutados** y evidencia (captura o nota en `TASKS.md`)”, no solo tarjeta en Done.

---

## 5. Vistas del proyecto (plantilla estándar)

| Vista | Tipo | Configuración sugerida |
|-------|------|-------------------------|
| **Status board** | Board | Columnas = **Status**; filtro opcional por **Iteration** actual. |
| **Prioritized backlog** | Table | `Status` ≠ Done; ordenar por **Priority**; columnas: Title, Assignee, Area, Source. |
| **Current iteration** | Board o Table | **Iteration** = activa; `Status` ∈ {Todo, In progress, Blocked}. |
| **Roadmap** | Roadmap | Agrupar por **Iteration** o fechas; issues con *start/target* si se usan. |
| **Agent queue** | Table | Título contiene `[agent]` **o** etiqueta `copilot` — cola Copilot + revisión humana. |
| **Infra & coordination** | Table | Etiqueta `infra` **o** `coordination` **o** `Area` = infra. |
| **Dependabot / deps** | Table | Etiqueta `dependencies` o `Source` = dependabot. |
| **My items** | Table | `Assignee` = @me |

---

## 6. Workflows nativos del proyecto (GitHub UI)

**Ubicación:** proyecto → **Workflows** (esquina superior derecha). Configuración típica alineada a DoD:

1. **Issue cerrado** → **Status** = `Done` (sincronía tablero ↔ GitHub).
2. **PR fusionado** (si la plantilla existe en vuestra cuenta) → mover issue vinculado a `Done`.
3. **Ítem añadido al proyecto** → valores por defecto: `Priority` = `P3`, `Source` = `manual` (ajustar al crear desde 08/09).
4. **Auto-close al mover a Done:** usar solo si **no** separáis “done en tablero” de “cerrado en GitHub”; en Gano suele preferirse **cerrar issue tras merge o checklist servidor**, y que el workflow 1 refleje Done.

**Referencia:** [GitHub Docs — Projects](https://docs.github.com/en/issues/planning-and-tracking-with-projects) · [Changelog auto-close workflow](https://github.blog/changelog/2024-04-25-github-issues-projects-auto-close-issue-project-workflow/).

---

## 7. Status updates (comunicación al equipo)

- **Cadencia:** semanal (p. ej. viernes) + ad-hoc en deploy o cierre de oleada.
- **Plantilla:** copiar desde **`.github/templates/project-status-update.md`** (rutas internas ya en formato raíz repo).
- **Contenido mínimo:** iteración; completado (con `#issue` / `#PR`); en curso; bloqueos (`TASKS.md` 🔴: SSH, GSC, wp-file-manager, etc.); puntero a **`TASKS.md`** y **`memory/sessions/`**.

---

## 8. Insights

- Revisar **cycle time** por **Area** (¿todo en `theme` y nada en `infra`?).
- **Burnup** por **Iteration** para expectativas con stakeholders.
- Export o captura para informes externos (mensual).

---

## 9. Automatización en el repositorio (workflow 13)

| Elemento | Valor |
|----------|--------|
| Archivo | `.github/workflows/project-add-to-project.yml` |
| Nombre en UI | **13 · Projects · Añadir issues al tablero Gano.digital** |
| Variable | `GANO_PROJECT_URL` — URL del proyecto (copiar del navegador, p. ej. `https://github.com/orgs/Gano-digital/projects/N`) |
| Secret | `ADD_TO_PROJECT_PAT` — PAT con permiso de **escritura en Projects** (org) + lectura de issues en `Pilot` |
| Criterio de inclusión | Título contiene **`[agent]`** o etiqueta **`copilot`** (eventos `opened` / `labeled`) |

Sin variable y secret el job **no corre** (diseño intencional).

---

## 10. Convenciones de nomenclatura (ya en uso en el repo)

| Patrón | Uso |
|--------|-----|
| `[agent]` en título | Issues sembrados por workflow **08**; filtros del tablero. |
| `<!-- agent-task-id:… -->` | Deduplicación en **08**; no borrar al editar cuerpo. |
| Etiqueta `copilot` | Creada por **06**; usada por workflow **13** y vistas. |
| PRs del agente | `Closes #n` / `Ref #n` + plantilla **`.github/pull_request_template.md`**. |

---

## 11. Estado actual del producto (contexto abril 2026)

Para que el tablero refleje **necesidades reales** del proyecto (no solo UI):

| Prioridad en `TASKS.md` | Temas que deben verse en Project / Status updates |
|-------------------------|-----------------------------------------------------|
| 🔴 Crítico | Deploy Fases 1–3 a servidor (`04`/`05`), secrets SSH; eliminar **wp-file-manager** (`12`); parches alineados a `gano-security` / child / MU-plugins. |
| 🟡 Alto | GSC, Rank Math, Gano SEO en wp-admin; sustituir Lorem/testimonios; páginas confianza. |
| Roadmap | Reseller / Fase 4 (`memory/research/fase4-plataforma.md`); sin mezclar con tareas `[agent]` cerradas solo en tablero. |

---

## 12. Referencias cruzadas (sin rutas rotas)

| Documento | Propósito |
|-----------|-----------|
| `.github/GITHUB-PROJECT-GANO-DIGITAL.md` | Puerta de entrada desde el repo a este playbook. |
| `.github/DEV-COORDINATION.md` | Servidor ↔ git ↔ Actions; incluye fila del workflow **13**. |
| `.github/workflows/README.md` | Lista **01–13** con archivos `.yml`. |
| `.gano-skills/gano-github-copilot-orchestration/SKILL.md` | Colas **08–10** y JSON en `agent-queue/`. |
| `.gano-skills/gano-github-projects-board/SKILL.md` | Resumen para asistentes IA en Cursor/Claude. |

---

*Última revisión de rutas y etiquetas: alineado a `.github/labeler.yml`, `setup-repo-labels.yml` (06), `seed-copilot-queue.yml` (08) y `TASKS.md` (abril 2026).*
