# Drift: cola Copilot vs código actual (auditoría local)

**Regla:** no borrar entradas de `.github/agent-queue/*.json` sin OK de Diego; esto solo documenta **probable obsolescencia** para cerrar issues en GitHub.

| agent-task-id        | Archivo cola | Estado en `main` (evidencia — 2026-04-19)                                                                                                                                                      | Recomendación                                                                                                     |
| -------------------- | ------------ | ----------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `hp-primary-nav` | `tasks.json` | ✅ **OBSOLETA** — TASKS.md línea 162: "Menú **Inicio → /** (WP-CLI)" aplicado 2026-04-19. Verificado en vivo.| **Cerrar issue** con comentario: "Resuelto 2026-04-19: menú primary asignado vía WP-CLI; verificado en sitio en vivo." |
| `hp-coming-soon` | `tasks.json` | ✅ **OBSOLETA** — TASKS.md línea 196: "dashboard-infraestructura marcado como borrador (_gano_coming_soon); phase7 respeta la bandera." | **Cerrar issue** con comentario: "Resuelto 2026-04-19: coming soon flagged and managed per strategy." |
| `theme-audit-handle` | `tasks.json` | ✅ **OBSOLETA** — `functions.php` línea 106: handle ya es `royal-elementor-kit-parent` (no `hello-elementor-parent-style`). Dependencia correcta en línea 107. | **Cerrar issue** con comentario: "Resuelto: handle parent ya es `royal-elementor-kit-parent` (verified 2026-04-19, functions.php:106)." |
| `theme-css-root`     | `tasks.json` | ⚠️ **REVISAR VISUALMENTE** — `style.css`: --gano-gold y variantes (#D4AF37, soft, bg, border) presentes (4 tokens consolidados). Sin duplicados detectados. | Marcar issue "Ready for Review"; revisor debe confirmar en navegador que no hay regresión visual en shop/SOTA. Si OK → cerrar; si regresión → asignar. |
| `theme-lcp`          | `tasks.json` | ✅ **ACTIVO, NO OBSOLETO** — `functions.php` contiene 5 referencias a MutationObserver/fetchpriority. Script inyectado y funcional en front page. | No cerrar. Mantener en cola para validación visual de selectores vs estructura Elementor actual (nota: Diego ya revisó). |
| `seo-rankmath` | `tasks.json` | ⏳ **BLOQUEADO POR HUMANO** — TASKS.md línea 101-106: requiere wp-admin (Re-run Setup Wizard). No código en este lado. | Marcar como "waiting-for-human"; Diego ejecuta en wp-admin según TASKS.md § Active. |
| `seo-gsc` | `tasks.json` | ⏳ **BLOQUEADO POR HUMANO** — TASKS.md línea 94-99: requiere wp-admin + GSC. No código en este lado. | Marcar como "waiting-for-human"; Diego ejecuta en wp-admin según TASKS.md § Active. |

## Cómo usar esta tabla

1. **Para tareas ✅ OBSOLETAS:** ir a GitHub (issues con label `homepage-fixplan` o `area:theme`), buscar issue con `agent-task-id:hp-primary-nav` (etc.) en cuerpo, y cerrar con comentario de evidencia.
2. **Para tareas ⚠️ REVISAR:** asignar a revisión visual en navegador; documentar resultado.
3. **Para tareas ⏳ BLOQUEADO:** marcar con label "waiting-for-human" o "coordination"; Diego ejecuta en wp-admin según TASKS.md § Active.
4. **Para tareas ✅ ACTIVAS:** mantener en cola; no cerrar sin supervisión.

**Plantilla de cierre:** Ver [`gh-issue-close-guide.md`](gh-issue-close-guide.md) (aún por crear en cd-repo-003).

---

**Última auditoría:** 2026-04-19 (cd-repo-006)
