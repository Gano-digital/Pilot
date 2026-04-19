# Pendientes detallados — inventario para Claude

Este documento **complementa** [`TASKS.md`](../../TASKS.md) y [`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md) con una vista **por área** y notas de **coherencia** (qué está hecho solo en git, qué requiere servidor o panel).

**Índice rápido ola 2026-04-19:** [`../sessions/2026-04-19-trazabilidad-ops-wave-handoff.md`](../sessions/2026-04-19-trazabilidad-ops-wave-handoff.md) · Tablero [#263](https://github.com/Gano-digital/Pilot/issues/263)

**Leyenda de estado**

| Etiqueta | Significado                                                                     |
| -------- | ------------------------------------------------------------------------------- |
| **Repo** | Cambio presente en `main` del repositorio Pilot (puede no estar en producción). |
| **Prod** | Requiere acción en hosting / wp-admin / RCC.                                    |
| **GH**   | Requiere acción en GitHub (Actions, issues, settings).                          |

---

## A. Crítico — seguridad y paridad código ↔ servidor

| ID  | Tarea                           | Dónde             | Notas                                                                                                                                                                                                      |
| --- | ------------------------------- | ----------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| A1  | Configurar secrets de deploy    | **GH**            | `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. La huella de `SSH` ya coincide con `id_rsa_deploy` local; si sigue `publickey` en CI, revisar **usuario/host** en secretos y **restricción por IP** en hosting — ver [`2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](2026-04-08-reporte-handoff-ssh-deploy-tokens.md) y [`../ops/github-actions-ssh-secret-troubleshooting.md`](../ops/github-actions-ssh-secret-troubleshooting.md). |
| A2  | Desplegar código crítico al servidor | **Prod** + **GH** | **2026-04-19:** convergencia manual **8 archivos** (tema + MU `gano-seo`/`gano-security` + `class-pfid-admin.php`) con MD5 repo=prod; rutina futura: push → **04** + **05**. `wp-config.php` **no** va en git. |
| A3  | Verificar parches (checksums)   | **GH**            | Workflow **05 · Ops · Verificar parches en servidor** — manual; opción subir faltantes.                                                                                                                    |
| A4  | Eliminar **wp-file-manager**    | **Prod**          | **12 · Ops · Eliminar wp-file-manager (SSH)** con `force_remove` **o** wp-admin + SFTP. Riesgo histórico documentado en auditorías; la alerta en `gano-security.php` depende de que el plugin desaparezca. |

---

## B. Alta — SEO y visibilidad (wp-admin)

| ID  | Tarea                                         | Dónde    | Notas                                                                    |
| --- | --------------------------------------------- | -------- | ------------------------------------------------------------------------ |
| B1  | Pantalla **Gano SEO** — datos empresa digital | **Prod** | Cobertura Colombia, sin forzar dirección física si el modelo es digital. |
| B2  | **Google Search Console**                     | **Prod** | Propiedad `https://gano.digital`.                                        |
| B3  | **Rank Math** — asistente inicial             | **Prod** | Ajustar a servicios digitales / sin tienda física.                       |

---

## C. Alta — contenido y Elementor

| ID  | Tarea                                             | Dónde    | Notas                                                                                                                                                                         |
| --- | ------------------------------------------------- | -------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| C1  | Menú **primary** + home canónica                    | **Prod** | **2026-04-19:** ítem **Inicio → /** aplicado vía WP-CLI; revisar si **header Elementor** aún enlaza a `/home/` u otras rutas legacy.                                                                          |
| C2  | Sustituir Lorem y placeholders                    | **Prod** | Fuente: [`../content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md); clases: [`../content/elementor-home-classes.md`](../content/elementor-home-classes.md). |
| C3  | Hero: imagen y attachment coherentes              | **Prod** |                                                                                                                                                                               |
| C4  | Layout: clases `gano-el-*`, CTA con iconos reales | **Prod** |                                                                                                                                                                               |
| C5  | Eliminar testimonios falsos / métricas infladas   | **Prod** | Listado en `TASKS.md` — integridad de marca.                                                                                                                                  |
| C6  | Página **Nosotros** real (manifiesto SOTA)        | **Prod** |                                                                                                                                                                               |
| C7  | Skip link `#gano-main-content`                    | **Prod** | Revisar en canvas Elementor si el marcaje del main sigue el id esperado (entregable a11y en repo).                                                                            |

---

## D. Alta — comercio Reseller (Fase 4 agilizada)

| ID  | Tarea                                                       | Dónde               | Notas                                                                                                                            |
| --- | ----------------------------------------------------------- | ------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| D1  | Depurar catálogo y precios en **RCC**                       | **Prod** (GoDaddy)  | Hosting, VPS, SSL alineados a lo que vende la vitrina.                                                                           |
| D2  | **PFIDs** (`gano_pfid_*` / carrito)                         | **Prod** + **RCC**  | **2026-04-19:** `wp_options` pobladas vía WP-CLI con IDs del catálogo Reseller (`rstore_id` / slugs, p. ej. `wordpress-basic`). **Pendiente:** validar contra RCC que el carrito acepta esos identificadores o sustituir por **PFID numérico**; `gano_pfid_online_storage` sigue `PENDING_RCC`. |
| D3  | Prueba de flujo **Comprar → carrito marca blanca → cierre** | **Prod**            | Smoke manual; referencia [`../smoke-tests/checkout-reseller-2026-04.md`](../smoke-tests/checkout-reseller-2026-04.md) si aplica. |
| D4  | Canal de soporte (FreeScout u otro)                         | **Decisión**        | TASKS menciona FreeScout; no bloquea merge en git.                                                                               |

---

## E. GitHub — issues, colas y agentes

| ID  | Tarea                                                                    | Dónde          | Notas                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| --- | ------------------------------------------------------------------------ | -------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| E1  | **Cerrar issues** ya cubiertos por `main`                                | **GH**         | Tras consolidación 2026-04-03, muchos issues pueden cerrarse con comentario “resuelto en main”. Revisión **manual** lista por lista.                                                                                                                                                                                                                                                                                                                                                  |
| E2  | **09 · Sembrar issues homepage**                                         | **GH**         | Ciclo inicial ya ejecutado (reflejado en `TASKS.md` como hecho). Repetir **solo** si se abre un nuevo lote `homepage-fixplan` pendiente de creación en GitHub.                                                                                                                                                                                                                                                                                    |
| E3  | **08 · Sembrar cola Copilot**                                            | **GH**         | Ciclo inicial completado; ejecutar de nuevo únicamente con backlog nuevo validado. Mantener deduplicación por `agent-task-id` para evitar issues duplicados.                                                                                                                                                                                                                                                                                      |
| E4  | Prompt Copilot por lote                                                  | **GH**         | Un solo archivo: [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) — tablas y bloques para oleadas **1, 2, 3, 4**, **infra** DNS/HTTPS, **API** (ML + GoDaddy research) y **guardián seguridad**. Índice de colas: [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md).                                                                                                                                                   |
| E5  | **10 · Orquestar oleadas**                                               | **GH**         | **No** requerido para la oleada ya fusionada. Solo si reaparece un lote “oleada 1” y se desea automatizar. Para trabajo repetible sin tocar GitHub: `memory/claude/dispatch-queue.json` + `python scripts/claude_dispatch.py next`.                                                                                                                                                                                                                                                   |
| E6  | Rotación de tokens / remotes sin credenciales en URL                     | **GH** + local | Buena práctica post-deploy.                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| E7  | Sembrar colas **oleada 4**, **infra**, **API** o **guardián** (opcional) | **GH**         | Oleadas 1–3 y muchas tareas 4/infra ya pueden estar **sembradas** o resueltas en `main`. Ejecutar **08** solo si necesitas **nuevos** issues desde los JSON (sin duplicar `agent-task-id`). Archivos válidos: `tasks.json`, `tasks-wave2.json`, `tasks-wave3.json`, `tasks-wave4-ia-content.json`, `tasks-infra-dns-ssl.json`, `tasks-api-integrations-research.json`, `tasks-security-guardian.json` — ver [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md). |
| E8  | **CI** estable (labeler **03**, setup **11**)                            | **GH**         | Si un PR falla en etiquetas o PHP lint, revisar `memory/ops/github-actions-audit-2026-04.md` antes de “arreglar” a ciegas.                                                                                                                                                                                                                                                                                                                                                            |

---

## F. Media — plugins, acceso, someday

| ID  | Tarea                                                           | Dónde    | Notas                                                                                                                                         |
| --- | --------------------------------------------------------------- | -------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| F1  | **2FA** en wp-admin                                             | **Prod** | Wordfence **instalado, inactivo** (abr 2026); activar en ventana acordada.                                                                       |
| F2  | **Plugins de fase / basura**                                    | **Prod** | **2026-04-19:** en producción se **eliminaron del disco** plugins inactivos (installers fase 1–3, Elementor stack, WooCommerce, wompi inactivo, etc.). Siguen activos `gano-phase6-catalog`, `gano-phase7-activator`, `gano-reseller-enhancements`, `reseller-store`. **No reinstalar** Woo/Elementor sin decisión explícita. Repo puede aún contener carpetas de plugins no desplegados. |
| F3  | Fase 5 (status page, chat LLM real, afiliados)                  | Backlog  | `TASKS.md` — someday.                                                                                                                         |

---

## G. Investigación / referencia (no es “pendiente” inmediato)

| Recurso                                                                                                          | Uso                                                                                                         |
| ---------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------- |
| [`../research/fase4-plataforma.md`](../research/fase4-plataforma.md)                                             | WHMCS, Blesta, DIAN, portal — **referencia** mientras el modelo activo sea Reseller GoDaddy.                |
| [`../research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md)             | Brief oleada 3 / marca y UX.                                                                                |
| [`../research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md) | Mapa APIs ML + GoDaddy Reseller; ampliar vía cola `tasks-api-integrations-research.json`.                   |
| [`../ops/security-guardian-playbook-2026.md`](../ops/security-guardian-playbook-2026.md)                         | Sesiones sin filtrar secretos; alineado con cola `tasks-security-guardian.json`.                            |
| [`2026-04-02-progreso-consolidado.md`](../sessions/2026-04-02-progreso-consolidado.md)                           | Buen resumen de fases 1–3; **ignorar** conteos obsoletos de PRs abiertos (sustituir por sesión 2026-04-03). |

---

## H. Orden sugerido de ejecución (Diego / operador)

1. **D2** validación RCC + PFID numérico (y `online_storage`) — bloquea confianza total en checkout.
2. **C1–C7** copy y Elementor sin placeholders.
3. **B1–B3** SEO panel cuando el contenido esté estable (acuerdo abril 2026: Rank Math después del copy).
4. **F1** Wordfence + 2FA en ventana.
5. **E6** rotación tokens / remotes.
6. **A3** periódico tras cambios (checksums **05**).
7. **E1** / **E7** solo si hace falta nueva semilla de issues; tablero operativo **#263**.

---

## I. Validación local rápida (desarrolladores)

```bash
python scripts/validate_agent_queue.py
```

Debe imprimir `OK` si las colas en `.github/agent-queue/` son JSON válido según el validador del repo.

---

_Ultima revisión: **2026-04-19** — alineado con `TASKS.md`, ola ops producción (bots, plugins, convergencia repo↔servidor) y [`2026-04-19-trazabilidad-ops-wave-handoff.md`](../sessions/2026-04-19-trazabilidad-ops-wave-handoff.md)._
