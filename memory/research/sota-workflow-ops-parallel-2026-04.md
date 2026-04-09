# Investigación SOTA — Flujo de trabajo, operación y paralelismo (abril 2026)

**Propósito:** registrar prácticas actuales en equipos pequeños con WordPress + GitHub + hosting gestionado, **colisionar** con el estado real de Gano Digital y proponer **cambios esenciales** al flujo. Este documento es un **carriles paralelos**: no sustituye `TASKS.md` ni bloquea Fase 4, deploy ni contenido.

**Audiencia:** Diego, agentes (Cursor/Copilot/Claude), colaboradores puntuales.

**Cómo usarlo:** revisar una sección por sprint; marcar ítems en la checklist final al completarlos; abrir issues en GitHub solo cuando un ítem deba ser **propiedad explícita** de código o CI.

---

## 1. Referencias SOTA (síntesis)

No es un listado exhaustivo; son líneas que la industria y buenas prácticas convergen en 2025–2026 para stacks parecidos al vuestro:

| Tema SOTA | Qué implica | Relevancia Gano |
|-----------|-------------|-----------------|
| **Git como fuente de verdad + deploy auditable** | Cada cambio en prod trazable a commit/PR; rollbacks posibles. | Alta: ya tenéis `deploy.yml`, `verify-patches.yml`; falta **cerrar el hábito** servidor ↔ `main`. |
| **Staging antes de producción** | Cambios de tema/plugin probados en copia antes del dominio público. | Alta: Managed WP ofrece staging en plan; alinear con “no romper vitrina”. |
| **Gates de merge más allá del lint** | Code scanning / políticas que bloquean merge si hay hallazgos sin triage. | **Colisión activa:** PR #136 y ruleset «Code Quality» — hay que **ritualizar** dismiss/fix sin paralizar el equipo. |
| **Inventario de superficie (plugins + tema)** | Saber qué terceros actualizan, cadencia, riesgo EOL. | Media–alta: no está todo en el repo (Elementor, Wordfence, etc.); el riesgo es **operativo**, no solo código propio. |
| **Runbooks cortos + ensayo de restauración** | Menos tiempo medio de recuperación (MTTR) ante incidentes. | Alta para marca hosting: credibilidad = disponibilidad percibida. |
| **Automatización con agentes + revisión humana** | Oleadas de PRs con squash y checklist; evitar “fatiga de merge”. | Ya lo practicáis; el SOTA pide **límite de WIP** y poda de workflows muertos. |
| **Tests después de estabilizar producto** | PHPUnit/E2E cuando el núcleo comercial y deploy están bajo control. | Alineado con `.gano-skills/gano-wp-engineering-quality/` y `TASKS.md` etapa posterior. |
| **Secretos fuera del repo + rotación** | TruffleHog + cultura; rotación tras incidentes. | Entrada explícita en `deferredItems` y notas de sesión. |

---

## 2. Colisión: SOTA vs progreso actual Gano

### Fortalezas (no romper)

- Fases 1–3 en código con **hardening real** (CSP, rate limit, SEO MU-plugin, CWV).
- **CI** (PHP lint paths propios, TruffleHog, validación de colas, CodeQL).
- **Documentación** inusualmente rica: `TASKS.md`, `CLAUDE.md`, memory bank, skills, playbooks Copilot.
- **Modelo comercial claro:** Reseller GoDaddy como eje; investigación WHMCS/DIAN despriorizada a propósito.

### Tensiones (donde el feedback “choca” con necesidades)

| Tensión | SOTA diría | Necesidad Gano ahora |
|---------|------------|----------------------|
| **Más tests vs entregar** | Subir cobertura pronto. | Prioridad: **deploy F1–3**, RCC, checkout, contenido. Tests “puros” **después** (ya registrado). |
| **Más automatización vs claridad** | Más workflows. | Riesgo de **fatiga**: ya hay muchos jobs; conviene **poda** y documentar cuáles son P0. |
| **Merge rápido vs ruleset** | Calidad en puerta. | **Bloqueo real** en #136: exige **proceso** (otro revisor, dismiss CodeQL) sin mezclar con trabajo creativo. |
| **SSH perfecto vs HTTPS Git** | SSH everywhere. | `origin` HTTPS funciona; SSH al servidor es **mejora**, no prerequisito para merge de docs. |
| **Constellation + Pilot** | Un solo backlog. | Dos frentes legítimos; el riesgo es **mezclar prioridades** en la misma semana sin carriles. |

### Veredicto

El proyecto **no está “atrasado”** frente a SOTA general de WP SMB; está **adelantado en gobernanza** y **rezagado en operación de borde** (deploy continuo, staging, incidentes documentados). Esa brecha es **normal** y se cierra con hábitos, no con reescritura.

---

## 3. Cambios esenciales al flujo (priorizados)

### P0 — No interrumpen contenido/Fase 4; son “cinturón”

1. **Carril explícito en el tablero mental (o en GitHub Projects):**
   - **Carril A — Producto:** deploy, wp-file-manager, SEO/GSC, Elementor, Fase 4 Reseller.
   - **Carril B — Plataforma repo:** PR #136 desbloqueo, cierre issues cubiertos por `main`, poda de workflows opcionales.
   - **Carril C — Madurez ops (paralelo, baja cadencia):** runbook 1 página, drill restauración **cuando** haya backup verificado, inventario plugins terceros.

2. **Ritual de merge bloqueado por CodeQL/ruleset** (15 min/semana): persona con permisos revisa **un** PR bloqueado; dismiss con justificación o issue vinculado. Evita que #136 se convierta en zombi.

3. **Definición de “done” para deploy:** un ítem en `TASKS.md` marcado solo cuando `verify-patches` o checklist manual confirme **checksum/path** acordado (ya descrito; falta **fecha objetivo** suave, no bloqueante).

### P1 — Tras primer deploy exitoso F1–3

4. **Staging:** toda regla de tema/MU-plugin que toque front: probar en staging antes de prod (regla de equipo, aunque sea checklist en Notion/memory).

5. **SSH:** resolver autorización en **ventana dedicada**; no mezclar con sesiones de copy Elementor.

### P2 — Cuando Fase 4 smoke pase en staging

6. **Inventario plugins** (tabla: nombre, rol, quién actualiza, última revisión).

7. **Activar skill** `gano-wp-engineering-quality` con alcance mínimo (ver `TASKS.md` etapa posterior).

---

## 4. Modelo de trabajo en paralelo (sin interrumpir tareas)

```
┌─────────────────────────────────────────────────────────────────┐
│  HILO PRINCIPAL (no pausar): TASKS Active + Fase 4 + homepage    │
└─────────────────────────────────────────────────────────────────┘
          │
          ├──► Paralelo “5–10% tiempo”: Carril B (GitHub/PRs)
          │
          └──► Paralelo “2–5% tiempo”: Carril C (ops/runbook) — mensual o bimensual
```

**Reglas:**

- **No** abrir nuevas oleadas Copilot hasta que el backlog de merge/revisión baje o quede asignado.
- Los ítems de este documento se **tildan** aquí o en `progress.md`; solo se crean **issues nuevos** si hay dueño o bloqueo cross-team.
- Si un ítem P0 de este doc **choca** con un P0 de `TASKS.md` (deploy crítico), gana **TASKS.md**.

---

## 5. Checklist operativa (ir tachando)

Copiar progreso a `.cursor/memory/progress.md` cuando se complete un bloque.

### Plataforma GitHub / CI

- [ ] Desbloqueo documentado de PR #136 (aprobación + dismiss/fix CodeQL según política).
- [ ] Lista corta de workflows “siempre vivos” vs “bajo demanda” (referencia: `.github/workflows/README.md` actualizada si hace falta).
- [ ] Cerrar o actualizar issues obsoletos respecto a `main` (sesión dedicada 30 min).

### Producto / servidor

- [ ] Deploy F1–3 verificado en prod (o staging si es el primer paso).
- [ ] wp-file-manager eliminado en servidor.
- [ ] Secrets GitHub para deploy probados **sin** pegar valores en issues.

### Ops (paralelo, no bloqueante)

- [ ] Runbook incidente 1 página (`memory/ops/` o wiki interno).
- [ ] Drill restauración desde backup (staging).
- [ ] Tabla inventario plugins terceros (v1).

### Calidad código (etapa posterior explícita)

- [ ] Activar checklist en `TASKS.md` → sección *Etapa posterior — Tests y calidad de ingeniería*.

---

## 6. Referencias cruzadas en el repo

| Recurso | Uso |
|---------|-----|
| `TASKS.md` | Fuente de verdad de prioridades; sección nueva enlazando este doc. |
| `.cursor/memory/activeContext.md` | Foco actual; menciona carriles si aplica. |
| `.cursor/memory/deferredItems.md` | Secretos/rotación; no duplicar hallazgos aquí. |
| `.gano-skills/gano-wp-engineering-quality/SKILL.md` | Cuando toque tests automatizados. |
| `memory/ops/agent-playbook-asistentes-2026-04.md` | Coordinación agentes humanos. |
| [`sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](sota-investigacion-2026-04-09-ci-supply-chain-agents.md) | CI/CD, OWASP A03:2025, runners self-hosted, minutos Actions. |

---

## 7. Changelog del documento

| Fecha | Nota |
|-------|------|
| 2026-04-07 | Versión inicial: SOTA, colisión, P0–P2, paralelismo, checklist. |
| 2026-04-09 | Enlace a investigación CI/supply chain y runners (`sota-investigacion-2026-04-09-ci-supply-chain-agents.md`). |
