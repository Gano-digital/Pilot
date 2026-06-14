# Tarea cd-repo-001 — Alinear checkboxes GitHub con criterios condicionales

**ID:** `cd-repo-001`  
**Prioridad:** P1  
**Requiere humano:** NO (documentación operativa)  
**Generado:** 2026-04-19  
**Estado:** Listo para ejecución inmediata

---

## Objetivo

Actualizar **`TASKS.md`** y **`memory/claude/02-pendientes-detallados.md`** para que los checkboxes de workflows 08, 09 y 10 tengan criterios de verificación **explícitos y condicionales**, no ambiguos. 

Esto permite:
1. Workflows 08/09/10 sepan exactamente cuándo completarse (no marcar X sin verificación)
2. Diego tenga criterios claros para decidir si re-ejecutar
3. Historial auditable de cuándo y por qué se ejecutan

---

## 📋 Checklist de implementación

### Sección 1 — Actualizar TASKS.md

**Ubicación:** `TASKS.md` líneas 179–191 (workflows 08, 09, 10)

#### Cambio 1.1: Workflow 09 · Sembrar issues homepage

**Antes:**
```
- [ ] **09 · Sembrar issues homepage**
```

**Después:**
```
- [ ] **09 · Sembrar issues homepage**
  - **Criterio de verificación:** Marcar [x] **solo tras confirmar en github.com/Gano-digital/Pilot/issues** que **existen 7 issues etiquetados `homepage-fixplan`**
  - Bloqueante: Si 0 issues con label, dejar [ ]; si 7+ issues, marcar [x]
  - Ciclo inicial: 2026-04-03 completado
  - Re-ejecutar: Solo si existe **nuevo lote `homepage-fixplan`** validado
  - Documentación: `memory/claude/02-pendientes-detallados.md` § E2
```

#### Cambio 1.2: Workflow 08 · Sembrar cola Copilot

**Antes:**
```
- [ ] **08 · Sembrar cola Copilot**
```

**Después:**
```
- [ ] **08 · Sembrar cola Copilot**
  - **Criterios para re-ejecutar:**
    1. **JSON validado** mediante `python scripts/validate_agent_queue.py` = OK
    2. **Sin duplicados** por `agent-task-id` (dedup automático en script)
    3. **Nuevo archivo** `.github/agent-queue/` diferente al anterior (ej. `tasks-wave4-ia-content.json`)
  - Bloqueante: Si algún criterio no se cumple, NO ejecutar
  - Ciclo inicial: 2026-04-03 completado (oleadas 1–4, infra, API, guardián sembradas)
  - Método alternativo (sin GitHub Actions): `python scripts/claude_dispatch.py` local
  - Documentación: `memory/claude/02-pendientes-detallados.md` § E3
```

#### Cambio 1.3: Workflow 10 · Orquestar oleadas

**Antes:**
```
- [ ] **10 · Orquestar oleadas**
```

**Después:**
```
- [ ] **10 · Orquestar oleadas**
  - **DEPRECADO en flujo manual GitHub**
  - **Modelo actual:** Dispatch Claude automático vía `memory/claude/dispatch-queue.json` + `python scripts/claude_dispatch.py next`
  - Alternativa: VS Code Task (`.vscode/tasks.json` → **Claude Dispatch: next**)
  - Documentación: `memory/claude/README.md` · `memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md`
  - Nota: Oleada 1 fusionada en `main` 2026-04-03; no requiere workflow 10 para futuras oleadas
```

#### Cambio 1.4: Sección nueva (después de línea 191)

**Agregar:**
```markdown
---

## ✅ Criterios de verificación explícitos — GitHub workflows 08, 09, 10

### **Workflow 08 · Sembrar cola Copilot**

| Condición | Significado | Acción |
|-----------|-------------|--------|
| JSON válido | `python scripts/validate_agent_queue.py` devuelve OK | ✅ Proceder |
| Sin duplicados | No hay 2 tareas con mismo `agent-task-id` | ✅ Proceder |
| Nuevo archivo | `.github/agent-queue/` tiene archivo diferente al anterior | ✅ Proceder |

**Resultado:** Si todas las condiciones se cumplen → ejecutar workflow | issues aparecen en GitHub

**Nota:** Si alguna condición **no** se cumple, workflow NO debe ejecutarse. Usar `python scripts/validate_agent_queue.py` como gate local antes de disparar GitHub Actions.

---

### **Workflow 09 · Sembrar issues homepage**

| Condición | Significado | Acción |
|-----------|-------------|--------|
| 7 issues con label | Ir a github.com/Gano-digital/Pilot/issues → filtrar por label `homepage-fixplan` | ✅ Marcar [x] |
| Cada issue tiene descripción | `agent-task-id` + contexto de tarea homepage | ✅ Marcar [x] |
| Label consistente | Todos los 7 issues tienen etiqueta `homepage-fixplan` exactamente | ✅ Marcar [x] |

**Resultado:** Si todas las condiciones se cumplen → marcar [x] | Si 0 issues → dejar [ ]

**Nota:** Ciclo inicial (2026-04-03) completado. Re-ejecutar SOLO si hay **nuevo lote de tareas homepage** validadas.

---

### **Workflow 10 · Orquestar oleadas**

| Estado | Significado | Acción |
|--------|-------------|--------|
| DEPRECADO | No usar desde GitHub Actions | ❌ No ejecutar |
| Modelo actual | Dispatch local vía `memory/claude/dispatch-queue.json` + CLI | ✅ Usar en lugar de 10 |
| Alternativa VS Code | Task `.vscode/tasks.json` → **Claude Dispatch: next** | ✅ Usar en lugar de 10 |

**Resultado:** Workflow 10 no se ejecuta. En su lugar, usar `python scripts/claude_dispatch.py next` local.

---

## 📝 Actualización memory/claude/02-pendientes-detallados.md

### Sección E2 (Sembrar issues homepage)

**Ubicación:** Línea con ID E2 en la tabla de "GitHub — issues, colas y agentes"

**Antes:**
```
| E2  | **09 · Sembrar issues homepage**                                         | **GH**         | [antes: sin criterio] |
```

**Después:**
```
| E2  | **09 · Sembrar issues homepage**                                         | **GH**         | ⏳ **Criterio de verificación (TASKS.md línea 185):** Marcar [x] solo tras confirmar en github.com/Gano-digital/Pilot/issues que existen **7 issues etiquetados `homepage-fixplan`**. Ciclo inicial: 2026-04-03 completado. Re-ejecutar (Actions → **09**): solo si nuevo lote `homepage-fixplan` validado. Bloqueante: 0 issues = [ ]; 7+ issues = [x]. |
```

### Sección E3 (Sembrar cola Copilot)

**Ubicación:** Línea con ID E3

**Antes:**
```
| E3  | **08 · Sembrar cola Copilot**                                            | **GH**         | [antes: sin criterio] |
```

**Después:**
```
| E3  | **08 · Sembrar cola Copilot**                                            | **GH**         | ✅ Ciclo inicial: 2026-04-03 completado (oleadas 1–4, infra, API, guardián sembradas). **Re-ejecutar solo si:** (a) JSON validado `python scripts/validate_agent_queue.py` = OK, (b) sin duplicados por `agent-task-id`, (c) nuevo archivo `.github/agent-queue/` diferente al anterior. Mantener deduplicación para evitar issues duplicados. Integrado en CI. |
```

### Sección E5 (Orquestar oleadas)

**Ubicación:** Línea con ID E5

**Antes:**
```
| E5  | **10 · Orquestar oleadas**                                               | **GH**         | [antes: sin criterio] |
```

**Después:**
```
| E5  | **10 · Orquestar oleadas**                                               | **GH**         | ✅ **Deprecado en flujo manual GitHub** (oleada 1 fusionada 2026-04-03). **Modelo actual:** trabajo repetible sin Actions via dispatch Claude local: [`dispatch-queue.json`](../claude/dispatch-queue.json) + `python scripts/claude_dispatch.py next` O `.vscode/tasks.json` → **Claude Dispatch: next**. Ver [`memory/claude/README.md`](../claude/README.md). |
```

---

## 🔄 Cambios en archivo `.github/workflows/`

### Workflow 08-sembar-cola-copilot.yml (si existe)

**Agregar documentación en el archivo:**

```yaml
# ✅ Criterios de validación previos a ejecución:
# 1. JSON válido: python scripts/validate_agent_queue.py
# 2. Sin duplicados por agent-task-id
# 3. Archivo .github/agent-queue/ es NUEVO (no reutilizar anterior)
# NO ejecutar este workflow si alguna condición no se cumple
```

### Workflow 09-sembrar-issues-homepage.yml (si existe)

**Agregar documentación:**

```yaml
# ✅ Criterio de verificación:
# Marcar checkbox solo si existen 7+ issues con label `homepage-fixplan` en GitHub
# Ciclo inicial completado 2026-04-03
# Re-ejecutar: solo si nuevo lote homepage-fixplan validado
```

### Workflow 10-orquestar-oleadas.yml (si existe)

**Agregar documentación de deprecación:**

```yaml
# ⚠️ DEPRECADO desde 2026-04-03
# Usar en su lugar: python scripts/claude_dispatch.py next (local)
# Ver memory/claude/README.md para continuidad
```

---

## ✅ Checklist de validación

- [ ] Leer líneas 179–191 de TASKS.md
- [ ] Confirmar que los 3 workflows (08, 09, 10) tienen criterios explícitos
- [ ] Actualizar `memory/claude/02-pendientes-detallados.md` secciones E2, E3, E5
- [ ] Agregar documentación a archivos workflow en `.github/workflows/`
- [ ] Revisar que no haya contradicciones entre TASKS.md y memory/02-pendientes
- [ ] Git commit + push con mensaje referenciando esta tarea
- [ ] Ejecutar `python scripts/validate_agent_queue.py` para confirmar validador funciona

---

## 📂 Archivos que cambian

1. **TASKS.md** — líneas 179–191 + nueva sección de criterios
2. **memory/claude/02-pendientes-detallados.md** — tabla GitHub § E2, E3, E5
3. **.github/workflows/08-sembrar-cola-copilot.yml** (opcional, solo documentación)
4. **.github/workflows/09-sembrar-issues-homepage.yml** (opcional, solo documentación)
5. **.github/workflows/10-orquestar-oleadas.yml** (opcional, solo documentación)

---

## 🎯 Definition of Done

La tarea se cierra cuando:
- [ ] TASKS.md líneas 179–191 tienen criterios explícitos para 08/09/10
- [ ] Nueva sección "Criterios de verificación explícitos" agregada a TASKS.md
- [ ] `memory/claude/02-pendientes-detallados.md` actualizadas secciones E2, E3, E5
- [ ] Criterios son **verificables y condicionales** (no ambiguos)
- [ ] Cambios en git committeados con mensaje referenciando cd-repo-001
- [ ] Sin conflictos entre TASKS.md y memory/02-pendientes

---

## ✨ Beneficios

✅ **Workflows 08/09/10:** Criterios claros → ejecución consistente  
✅ **Diego:** Sabe exactamente cuándo marcar [x]  
✅ **Auditoría:** Cada ejecución documentada con criterio que se cumplió  
✅ **Automatización:** Scripts pueden leer criterios y auto-validar antes de ejecutar  

---

**Generado por:** Claude Dispatch (cd-repo-001 task specification)  
**Última actualización:** 2026-04-19 21:20 UTC  
**Próxima revisión:** Post-implementación en TASKS.md
