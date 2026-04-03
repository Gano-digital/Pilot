# Pendientes detallados — inventario para Claude

Este documento **complementa** [`TASKS.md`](../../TASKS.md) y [`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md) con una vista **por área** y notas de **coherencia** (qué está hecho solo en git, qué requiere servidor o panel).

**Leyenda de estado**

| Etiqueta | Significado |
|----------|-------------|
| **Repo** | Cambio presente en `main` del repositorio Pilot (puede no estar en producción). |
| **Prod** | Requiere acción en hosting / wp-admin / RCC. |
| **GH** | Requiere acción en GitHub (Actions, issues, settings). |

---

## A. Crítico — seguridad y paridad código ↔ servidor

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| A1 | Configurar secrets de deploy | **GH** | `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. Sin ellos, **04 · Deploy** falla. Ver [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md). |
| A2 | Desplegar Fases 1–3 al servidor | **Prod** + **GH** | Push que toque `gano-child` / `gano-*` / `mu-plugins` dispara deploy si secrets OK; `wp-config.php` **no** va en git — subir por canal seguro aparte. |
| A3 | Verificar parches (checksums) | **GH** | Workflow **05 · Ops · Verificar parches en servidor** — manual; opción subir faltantes. |
| A4 | Eliminar **wp-file-manager** | **Prod** | **12 · Ops · Eliminar wp-file-manager (SSH)** con `force_remove` **o** wp-admin + SFTP. Riesgo histórico documentado en auditorías; la alerta en `gano-security.php` depende de que el plugin desaparezca. |

---

## B. Alta — SEO y visibilidad (wp-admin)

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| B1 | Pantalla **Gano SEO** — datos empresa digital | **Prod** | Cobertura Colombia, sin forzar dirección física si el modelo es digital. |
| B2 | **Google Search Console** | **Prod** | Propiedad `https://gano.digital`. |
| B3 | **Rank Math** — asistente inicial | **Prod** | Ajustar a servicios digitales / sin tienda física. |

---

## C. Alta — contenido y Elementor

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| C1 | Asignar menú **primary** en header | **Prod** | El tema registra la ubicación en **Repo**; falta asignación en panel / canvas Elementor. |
| C2 | Sustituir Lorem y placeholders | **Prod** | Fuente: [`../content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md); clases: [`../content/elementor-home-classes.md`](../content/elementor-home-classes.md). |
| C3 | Hero: imagen y attachment coherentes | **Prod** | |
| C4 | Layout: clases `gano-el-*`, CTA con iconos reales | **Prod** | |
| C5 | Eliminar testimonios falsos / métricas infladas | **Prod** | Listado en `TASKS.md` — integridad de marca. |
| C6 | Página **Nosotros** real (manifiesto SOTA) | **Prod** | |
| C7 | Skip link `#gano-main-content` | **Prod** | Revisar en canvas Elementor si el marcaje del main sigue el id esperado (entregable a11y en repo). |

---

## D. Alta — comercio Reseller (Fase 4 agilizada)

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| D1 | Depurar catálogo y precios en **RCC** | **Prod** (GoDaddy) | Hosting, VPS, SSL alineados a lo que vende la vitrina. |
| D2 | Sustituir **PENDING_RCC** / pfids en código | **Repo** + **Prod** | Tras merge #52, el modelo es **GANO_PFID_***; valores reales vienen del RCC / Private Label. |
| D3 | Prueba de flujo **Comprar → carrito marca blanca → cierre** | **Prod** | Smoke manual; referencia [`../smoke-tests/checkout-reseller-2026-04.md`](../smoke-tests/checkout-reseller-2026-04.md) si aplica. |
| D4 | Canal de soporte (FreeScout u otro) | **Decisión** | TASKS menciona FreeScout; no bloquea merge en git. |

---

## E. GitHub — issues, colas y agentes

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| E1 | **Cerrar issues** ya cubiertos por `main` | **GH** | Tras consolidación 2026-04-03, muchos issues pueden cerrarse con comentario “resuelto en main”. Revisión **manual** lista por lista. |
| E2 | **09 · Sembrar issues homepage** | **GH** | En `TASKS.md` sigue como checkbox: ejecutar **solo si** los 7 issues `homepage-fixplan` **no** existen. Si ya se crearon, marcar hecho en TASKS al confirmar. |
| E3 | **08 · Sembrar cola Copilot** | **GH** | Solo para **nuevos** issues desde JSON; deduplicación por `agent-task-id`. Oleadas 2/3 ya sembradas históricamente — no re-sembrar sin revisar duplicados. |
| E4 | Prompt Copilot por lote | **GH** | Un solo archivo: [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) — tablas y bloques para oleadas **1, 2, 3, 4**, **infra** DNS/HTTPS, **API** (ML + GoDaddy research) y **guardián seguridad**. Índice de colas: [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md). |
| E5 | **10 · Orquestar oleadas** | **GH** | **No** requerido para la oleada ya fusionada. Solo si reaparece un lote “oleada 1” y se desea automatizar. |
| E6 | Rotación de tokens / remotes sin credenciales en URL | **GH** + local | Buena práctica post-deploy. |
| E7 | Sembrar colas **oleada 4**, **infra**, **API** o **guardián** (opcional) | **GH** | Oleadas 1–3 y muchas tareas 4/infra ya pueden estar **sembradas** o resueltas en `main`. Ejecutar **08** solo si necesitas **nuevos** issues desde los JSON (sin duplicar `agent-task-id`). Archivos válidos: `tasks.json`, `tasks-wave2.json`, `tasks-wave3.json`, `tasks-wave4-ia-content.json`, `tasks-infra-dns-ssl.json`, `tasks-api-integrations-research.json`, `tasks-security-guardian.json` — ver [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md). |
| E8 | **CI** estable (labeler **03**, setup **11**) | **GH** | Si un PR falla en etiquetas o PHP lint, revisar `memory/ops/github-actions-audit-2026-04.md` antes de “arreglar” a ciegas. |

---

## F. Media — plugins, acceso, someday

| ID | Tarea | Dónde | Notas |
|----|-------|-------|--------|
| F1 | **2FA** en wp-admin | **Prod** | Wordfence presente; activación recomendada. |
| F2 | **Plugins de fase** — ejecutar y luego limpiar con confirmación | **Prod** | **No borrar** `gano-phase*` hasta confirmar que su trabajo quedó aplicado — ver [`../notes/plugins-de-fase.md`](../notes/plugins-de-fase.md). |
| F3 | Fase 5 (status page, chat LLM real, afiliados) | Backlog | `TASKS.md` — someday. |

---

## G. Investigación / referencia (no es “pendiente” inmediato)

| Recurso | Uso |
|---------|-----|
| [`../research/fase4-plataforma.md`](../research/fase4-plataforma.md) | WHMCS, Blesta, DIAN, portal — **referencia** mientras el modelo activo sea Reseller GoDaddy. |
| [`../research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md) | Brief oleada 3 / marca y UX. |
| [`../research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md) | Mapa APIs ML + GoDaddy Reseller; ampliar vía cola `tasks-api-integrations-research.json`. |
| [`../ops/security-guardian-playbook-2026.md`](../ops/security-guardian-playbook-2026.md) | Sesiones sin filtrar secretos; alineado con cola `tasks-security-guardian.json`. |
| [`2026-04-02-progreso-consolidado.md`](../sessions/2026-04-02-progreso-consolidado.md) | Buen resumen de fases 1–3; **ignorar** conteos obsoletos de PRs abiertos (sustituir por sesión 2026-04-03). |

---

## H. Orden sugerido de ejecución (Diego / operador)

1. Secrets **A1** → **A2** o deploy manual equivalente.  
2. **A3** para validar.  
3. **A4** wp-file-manager.  
4. **B1–B3** SEO en panel.  
5. **D1–D2** RCC + pfids.  
6. **C1–C7** Elementor y copy.  
7. **E1** limpieza de issues en GitHub en paralelo cuando haya tiempo.  
8. **E7** solo si hace falta **nueva** semilla de issues (oleada 4, infra, API o guardián); si no, priorizar **Fase 4** según `TASKS.md` y research de plataforma.

---

## I. Validación local rápida (desarrolladores)

```bash
python scripts/validate_agent_queue.py
```

Debe imprimir `OK` si las colas en `.github/agent-queue/` son JSON válido según el validador del repo.

---

_Ultima revisión: **2026-04-04** — 7 colas `agent-queue`, prompts API/guardián en `copilot-bulk-assign.md`, auditoría Actions y playbook guardián._
