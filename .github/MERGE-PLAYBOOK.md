# Playbook — fusionar PRs de Copilot y cerrar issues de la cola

Guía operativa para la gestión de PRs generados por agentes (Copilot) e issues abiertos.
Cubre el ciclo completo: **CI → Ready → squash/merge → cerrar issue → deploy**.

En **Actions**, los workflows usan prefijos `01`…`18`, `30`–`31` (CI → PR → Deploy → Agentes). Ver [`.github/workflows/README.md`](workflows/README.md).

---

## 1. Flujo completo de fusión

```
Issue abierto
    │
    ▼
Copilot (o autor) crea rama + PR (borrador)
    │
    ▼
CI en verde
  01 · PHP lint (Gano)  → rutas: mu-plugins/, gano-child/, gano-* plugins
  02 · TruffleHog       → detecta secretos en cualquier ruta
  03 · Labeler          → etiquetas automáticas por path
    │
    ▼ (CI verde)
Marcar PR como "Ready for review"
    │
    ▼
Revisar diff con AGENT-REVIEW-CHECKLIST.md
  ✓ Alcance = issue (sin scope creep)
  ✓ Sin conflictos con main (mergeable)
  ✓ Sin credenciales / SQL crudo
  ✓ Prefijo gano_ en PHP nuevo
    │
    ▼ (aprobado)
Squash & Merge
  Mensaje: "tipo(alcance): descripción corta"
  Cuerpo:  "Closes #NN"  — si el issue queda resuelto en repo
           "[sync] #NN"  — si requiere acción adicional en wp-admin/Elementor
    │
    ▼
Cerrar issue
  • Automático cuando el commit tiene "Closes #NN" y llega a main
  • Manual con comentario técnico si era solo wp-admin / servidor
    │
    ▼
Deploy (si aplica)
  04 · Deploy · Producción (rsync)  — si cambió gano-child/ o mu-plugins/
  05 · Ops · Verificar parches      — comparar MD5 repo vs servidor
    │
    ▼
Actualizar TASKS.md si hubo drift
```

---

## 2. Antes de fusionar (checklist rápida)

| # | Criterio | Herramienta |
|---|----------|-------------|
| 1 | CI en verde: **PHP lint** y **TruffleHog** | Pestaña **Checks** del PR |
| 2 | Rama fusionable con `main` (sin conflictos) | `mergeable_state` en UI o botón **Update branch** |
| 3 | Diff corresponde al issue (sin scope creep) | Revisión manual |
| 4 | Sin secretos / credenciales en texto plano | TruffleHog + ojo humano |
| 5 | PHP nuevo usa prefijo `gano_` y `$wpdb->prepare()` | PHP lint + revisión |
| 6 | No elimina `gano-phase*` sin confirmación | Revisión manual |
| 7 | CTAs / checkout coherentes con Reseller | Leer diff + `TASKS.md` |

---

## 3. Orden de fusión (reduce conflictos)

### Regla general
Fusionar primero lo que otros PRs toman como **base visual o funcional**.

1. **Tema `gano-child`** — CSS, handles, tokens, LCP, menú: base para todo lo visual.
2. **MU-plugins / SEO** (`mu-plugins/gano-seo.php`, `gano-security.php`) — si no pisan el tema.
3. **Plugins Gano custom** (`gano-reseller-enhancements`, etc.) — dependen del tema estable.
4. **Docs / `memory/**`** — al final o en paralelo si no hay conflicto con otros paths.
5. **Commerce / `shop-premium.php`** — solo cuando el tema esté en `main`.

---

## 4. Cierre de issues: `Closes #` vs `[sync]`

| Situación | Qué escribir en el merge |
|-----------|--------------------------|
| El PR resuelve el issue completamente en repo | `Closes #NN` en el cuerpo del commit de squash |
| El issue requiere acción adicional en wp-admin / Elementor | Comentar en el issue: `[sync] Código en repo (PR #NN). Pendiente: <pasos manuales>` y cerrar con etiqueta `needs-manual-deploy` (crearla si no existe con **06 · Repo · Crear etiquetas** o manualmente en Issues → Labels) |
| El issue era 100% wp-admin (no hay cambio en repo) | Cerrar con comentario: `[sync] Sin cambio en repo. Acción requerida en servidor: <pasos>` |
| El PR cubre parcialmente el issue | Dejar el issue abierto; añadir comentario con lo que queda pendiente |

> **Tip:** GitHub cierra el issue automáticamente cuando el commit de squash llega a `main`
> si el cuerpo del PR o el mensaje contiene `Closes #NN`, `Fixes #NN` o `Resolves #NN`.

---

## 5. Después del merge

1. **Deploy automático** (si `paths` en `deploy.yml` coinciden con archivos cambiados):
   - `gano-child/**` o `mu-plugins/**` → `04 · Deploy · Producción (rsync)` se activa.
2. **Deploy manual**: si el push no disparó el workflow, ir a Actions → **04 · Deploy · Producción** → **Run workflow** → rama `main`.
3. **Verificar parches**: `05 · Ops · Verificar parches` para comparar MD5 entre repo y servidor.
4. **Actualizar `TASKS.md`**: marcar subtarea como `[x]` si el issue cerrado era un ítem del plan.
5. **Actualizar ramas restantes**: tras cada merge a `main`, pulsar **Update branch** en los PRs abiertos para evitar estado `dirty`.

---

## 6. PRs activos — Oleada 2 (estado 2026-04-19)

Todos en **borrador** al momento de este documento. Actualizar tras cada fusión.

### Lote A — Homepage fixplan (base visual primero)

| PR | Issue | Título corto | Dependencia previa | Archivos clave | Riesgo |
|----|-------|--------------|-------------------|----------------|--------|
| [#236](https://github.com/Gano-digital/Pilot/pull/236) | #221 | Menú → ubicación `primary` | ninguna | `functions.php` | Bajo |
| [#237](https://github.com/Gano-digital/Pilot/pull/237) | #222 | Hero: imagen + CTA Elementor | #236 (header base) | `functions.php`, datos Elementor | Medio — verificar attachment_id en wp-admin → Medios antes de fusionar; si no existe, usar fallback o dejar checklist `[sync]` |
| [#238](https://github.com/Gano-digital/Pilot/pull/238) | #224 | Copy "Un Socio Tecnológico" | ninguna | `functions.php` o template | Bajo |
| [#240](https://github.com/Gano-digital/Pilot/pull/240) | #226 | Iconos en CTA final (quitar ★) | #238 (copy base) | CSS / shortcode | Bajo |
| [#241](https://github.com/Gano-digital/Pilot/pull/241) | #227 | Coming soon → borrador | ninguna | `functions.php` o WP-CLI | Bajo |

### Lote B — Técnico / agentes (puede ir en paralelo al Lote A)

| PR | Issue | Título corto | Dependencia previa | Archivos clave | Riesgo |
|----|-------|--------------|-------------------|----------------|--------|
| [#243](https://github.com/Gano-digital/Pilot/pull/243) | #230 | preconnect/preload fuentes | ninguna | `functions.php`, `gano-seo.php` | Bajo |
| [#247](https://github.com/Gano-digital/Pilot/pull/247) | #234 | aria + foco en CTAs | #236 (HTML header) | `functions.php`, shortcodes | Bajo |
| [#246](https://github.com/Gano-digital/Pilot/pull/246) | #233 | Ampliar rutas php-lint | ninguna | `php-lint-gano.yml` | Bajo |
| [#245](https://github.com/Gano-digital/Pilot/pull/245) | #232 | .htaccess vs CSP | ninguna | `.htaccess`, `gano-security.php` | Medio — probar en staging |
| [#242](https://github.com/Gano-digital/Pilot/pull/242) | #229 | Canonical Rank Math vs gano-seo.php | ninguna | `gano-seo.php` | Medio — no duplicar OG |
| [#244](https://github.com/Gano-digital/Pilot/pull/244) | #231 | Smoke carrito Reseller (doc) | ninguna | `memory/research/reseller-smoke-test.md` | Ninguno (solo docs) |
| [#239](https://github.com/Gano-digital/Pilot/pull/239) | #228 | MERGE-PLAYBOOK (este PR) | ninguna | `.github/MERGE-PLAYBOOK.md` | Ninguno (solo docs) |
| [#248](https://github.com/Gano-digital/Pilot/pull/248) | #235 | Post-Dependabot: workflows @v4→@v6 | ninguna | `.github/workflows/*.yml` | Bajo |

### Orden sugerido de fusión (oleada 2)

```
1.  #236  menú primary        — base del header
2.  #238  copy socio          — independiente, bajo riesgo
3.  #239  MERGE-PLAYBOOK docs — independiente
4.  #244  smoke carrito docs  — independiente
5.  #243  preconnect fuentes  — functions.php
6.  #247  a11y CTAs           — depende de header (#236)
7.  #237  hero Elementor      — necesita #236 (header estable)
8.  #240  iconos CTA          — necesita #238 (copy)
9.  #241  coming soon borrador
10. #246  php-lint rutas      — solo CI yaml
11. #242  canonical SEO       — gano-seo.php, probar output
12. #245  .htaccess vs CSP    — probar en staging primero
13. #248  Dependabot workflows — al final, tras CI verde
```

> **PRs con bloqueo externo (#237, #241):** si el agente solo dejó checklist sin cambio
> en repo, fusionar el PR de documentación y cerrar el issue con comentario `[sync]`
> indicando los pasos manuales exactos en wp-admin / WP-CLI.

---

## 7. Oleadas de issues (referencia rápida)

| Oleada | Archivo cola | IDs | Cómo sembrar |
|--------|-------------|-----|--------------|
| 1 | `tasks.json` | `hp-*`, `theme-*` | **08 · Sembrar cola Copilot** |
| 2 | `tasks-wave2.json` | `w2-*` | **08** → `queue_file: tasks-wave2.json` |
| 3 | `tasks-wave3.json` | `w3-*` | **08** → `queue_file: tasks-wave3.json` |
| 4 | `tasks-wave4-ia-content.json` | `w4-*` | **08** → `queue_file: tasks-wave4-ia-content.json` |
| Infra | `tasks-infra-dns-ssl.json` | `dns-*` | **08** → scope `infra` |

Los IDs de cada oleada son únicos; el workflow deduplica por `agent-task-id` en el cuerpo del issue.

Para orquestar fusión + siembra de la siguiente oleada en un solo paso:
**Actions → 10 · Orquestar oleadas** → `dry_run_merge: true` primero para ver el plan sin fusionar.

**Oleada 3 (marca/UX/comercial):** `tasks-wave3.json` (`w3-*`). Brief: `memory/research/gano-wave3-brand-ux-master-brief.md`.

**Oleada 4 (narrativa / páginas / matriz comercial):** `tasks-wave4-ia-content.json` (`w4-*`). Prompt en `copilot-bulk-assign.md`.

**Infra DNS/HTTPS:** `tasks-infra-dns-ssl.json` (`dns-*`). Scope `infra`. El agente solo documenta; Diego/soporte aplican DNS/SSL en GoDaddy.

---

## 8. Seguridad (recordatorio)

- **Nunca** incluir en mensajes de merge, comentarios de issue ni en el código:
  credenciales, tokens, rutas absolutas con usuario de hosting, API keys.
- Los secrets del deploy viven **solo** en GitHub → Settings → Secrets.
  Ver [AGENT-REVIEW-CHECKLIST.md](AGENT-REVIEW-CHECKLIST.md) para la lista de secrets requeridos.
- Si TruffleHog falla con falso positivo, documentar en el PR con evidencia — no silenciar sin justificación.

---

_Ver también [COPILOT-AGENT-QUEUE.md](COPILOT-AGENT-QUEUE.md) · [AGENT-REVIEW-CHECKLIST.md](AGENT-REVIEW-CHECKLIST.md) · [DEV-COORDINATION.md](DEV-COORDINATION.md)._
