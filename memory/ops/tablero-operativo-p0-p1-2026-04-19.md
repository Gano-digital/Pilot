# Tablero Operativo P0-P1 — Gano Digital

**Fecha de apertura:** 2026-04-19  
**Issue maestro:** [#263](https://github.com/Gano-digital/Pilot/issues/263)  
**Issues de ejecución:** #264 (RCC) · #265 (IA vs live) · #266 (SEO) · #267 (seguridad)  
**Autor:** Copilot (oleada operativa abril 2026)

> **Propósito:** tablero maestro de seguimiento P0-P1. Consolida el estado de los cinco frentes
> operativos y sirve como punto de referencia único para coordinación humano-agente.

---

## Resumen ejecutivo

| # | Issue | Frente | Estado | Bloqueador |
|---|-------|--------|--------|-----------|
| 1 | #264 | RCC: PFID/PLID → checkout | 🟡 Pendiente humano | Diego: acceso a GoDaddy RCC |
| 2 | #266 | SEO producción: Gano SEO + Rank Math + GSC | 🟡 Pendiente humano | Diego: wp-admin + GSC |
| 3 | #265 | IA vs live: slugs y estado de páginas | 🟡 Pendiente humano | Diego: wp-admin (activar importer, asignar padres) |
| 4 | #267 | Rotación de tokens y limpieza de remotes | 🟡 Pendiente humano | Diego: GitHub Settings + GoDaddy Portal |
| 5 | #263 | Tablero maestro (este documento) | ✅ Creado | — |

**Resumen de bloqueos:** todos los ítems P0-P1 de esta oleada dependen de acción humana directa
(wp-admin, GoDaddy RCC, GitHub Settings). El código del repositorio está listo. El trabajo de
agentes en esta oleada consiste en dejar los checklists y runbooks precisos para que Diego pueda
ejecutar sin ambigüedad.

---

## Frente 1 — #264: RCC / Checkout Reseller (P0 — bloqueador comercial)

**Objetivo:** que el botón "Adquirir Nodo" en `/ecosistemas` lleve al carrito GoDaddy con PFID y PLID reales.

**Estado del código (repo):**
- ✅ Plugin `gano-reseller-enhancements` activo con panel `wp-admin → Ajustes → Gano Reseller`.
- ✅ `functions.php` lee 8 constantes `GANO_PFID_*` desde `wp_options` con fallback a `/contacto`.
- ✅ `gano_rstore_cart_url()` construye URL correcta `cart.secureserver.net/?plid=…&items=[…]`.

**Documentación actualizada:**
- `memory/commerce/rcc-pfid-checklist.md` — v2 con 8 campos reales (alineado con código).
- `memory/research/reseller-smoke-test.md` — pasos del smoke test checkout.
- `memory/ops/runbook-activacion-wp-admin-2026-04-16.md` §3 — paso a paso PFID en wp-admin.

**Acción humana requerida (Diego):**
```
[ ] 1. Backup en GoDaddy → Managed WordPress → Back Up Now
[ ] 2. Acceder a RCC (https://reseller.godaddy.com/) → Products → Product Catalog
[ ] 3. Copiar los 8 PFIDs (números puros, sin prefijos)
[ ] 4. Copiar el PLID (Account → Private Label ID)
[ ] 5. wp-admin → Ajustes → Reseller Store → ingresar PLID
[ ] 6. wp-admin → Ajustes → Gano Reseller → ingresar los 8 PFIDs → Guardar
[ ] 7. Verificar banner "✓ Checkout listo" (8/8 configurados)
[ ] 8. Smoke test: clic en CTA → URL cart.secureserver.net con PFID y PLID reales
[ ] 9. Verificar precio en COP en el carrito
[ ] 10. Cerrar issue #264 con evidencia (screenshot o nota de resultado)
```

---

## Frente 2 — #266: SEO producción (P0)

**Objetivo:** Gano SEO configurado con datos reales, Rank Math activo, GSC verificada con sitemap.

**Estado del código (repo):**
- ✅ `gano-seo.php` (MU plugin) activo — Schema JSON-LD (Org, LocalBiz, WebSite, Product, FAQ, Breadcrumb), OG, resource hints, LCP preload.
- ✅ Rank Math encolado vía `functions.php` (no hay conflicto conocido con `gano-seo.php`).

**Documentación actualizada:**
- `memory/ops/gano-seo-rankmath-gsc-checklist.md` — pasos operativos (A–F).

**Acción humana requerida (Diego):**
```
[ ] A. wp-admin → Ajustes → Gano SEO → completar datos reales (sin placeholders)
[ ] B. wp-admin → Rank Math → Setup Wizard → tipo negocio + sitemap + módulos
[ ] C. GSC → añadir propiedad https://gano.digital → verificar → enviar sitemap
[ ] D. Validación: schema en https://search.google.com/test/rich-results
[ ] E. Cerrar issue #266 con evidencia
```

---

## Frente 3 — #265: IA vs live / Slugs y páginas (P1)

**Objetivo:** cerrar la brecha entre la IA propuesta y las páginas reales en WordPress; publicar las 20 páginas SOTA con la jerarquía `/pilares/` correcta.

**Estado del código (repo):**
- ✅ `gano-content-importer` listo para activar (crea 20 borradores con slug corto).
- ✅ `gap-ia-vs-live-inventory-2026.md` actualizado con **decisión adoptada** (Opción C).

**Decisión adoptada (2026-04-19):**
- Slugs canónicos: del importer (slug corto).
- Jerarquía: asignar cada página como hija de su categoría dentro del hub `/pilares/`.
- Para los 4 casos ⚠️ (Hosting Compartido, DDoS, Micro-animaciones, Agente IA): mantener slug del importer, no cambiar título.

**Acción humana requerida (Diego):**
```
[ ] 1. wp-admin → Plugins → Activar gano-content-importer
[ ] 2. Verificar 20 borradores en wp-admin → Páginas → Todos
[ ] 3. Para cada página: asignar Página Padre correcta (categoría de /pilares/)
[ ] 4. Verificar slugs de las 4 páginas ⚠️ (mantener slug del importer)
[ ] 5. Publicar páginas (o mantener borrador hasta copy final aprobado)
[ ] 6. Cerrar issue #265 con lista de slugs publicados
```

---

## Frente 4 — #267: Rotación de tokens (P1 — higiene de seguridad)

**Objetivo:** verificar y rotar credenciales expuestas o vencidas; limpiar remotes con tokens en URL.

**Documento creado:**
- `memory/ops/token-rotation-runbook-2026-04.md` — checklist completo de rotación.

**Acción humana requerida (Diego):**
```
[ ] 1. Verificar git remote -v en todos los clones locales (sin tokens en URL)
[ ] 2. Revisar expiración de PATs en https://github.com/settings/tokens
[ ] 3. Rotar ADD_TO_PROJECT_PAT si vence en < 30 días; actualizar secret en GitHub
[ ] 4. Revisar GoDaddy API Key/Secret — rotar si hubo exposición
[ ] 5. Verificar SSH deploy key — rotar si fue copiada a máquina no segura
[ ] 6. Cerrar issue #267 con nota de resultado
```

---

## Checklist de cierre de oleada

```
Agente (completado en repo):
  [x] docs/runbooks generados (este tablero + runbook tokens)

Humano (pendiente Diego — P0 primero):
  [ ] #264: PFID/PLID ingresados → banner "✓ Checkout listo" → smoke test PASS
  [ ] #266: Gano SEO + Rank Math + GSC configurados → schema válido → sitemap enviado
  [ ] #265: 20 páginas SOTA con jerarquía /pilares/ correcta
  [ ] #267: Tokens revisados/rotados → remotes limpios
  [ ] #263: cerrar tablero con link al PR y comentario de estado
```

---

_Generado: 2026-04-19 · Copilot (oleada operativa P0-P1) · Referencia: TASKS.md · DEV-COORDINATION.md_
