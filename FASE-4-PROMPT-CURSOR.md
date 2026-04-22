# Fase 4 — Integración GoDaddy Reseller | Prompt Maestro para Cursor

**Fecha:** 2026-04-21
**Status:** 🟡 Brainstorming completado, listo para implementación
**Owner:** Diego (Gano Digital)
**Context:** IDE Sync activado — Cursor auto-captura cambios a `wiki/dev-sessions/`

---

## Resumen Ejecutivo

Fase 4 es **integración del checkout GoDaddy Reseller Store** con iframe embebido en gano.digital. Los PFIDs (product family IDs) se sincronizan **dinámicamente** desde el plugin base GoDaddy Reseller — no requieren hardcoding manual.

**Decisiones tomadas (Brainstorming 2026-04-21):**
- ✅ **PFIDs:** Verificar dinámicamente vía plugin base (no hardcodear)
- ✅ **Checkout:** Iframe embebido en page-ecosistemas.php (white-label)
- ✅ **Testing:** Manual smoke test primero (documentar en wiki)
- ✅ **Plugin:** Revisar gano-reseller-enhancements antes de proceder

---

## Estado Actual (Verificado 2026-04-21)

### Infraestructura ✅
- **Servidor:** 72.167.102.145 (Managed WordPress Deluxe)
- **Plugin Reseller base:** Activo en producción
- **gano-reseller-enhancements:** Presente, con:
  - ✅ PFID Admin panel (Settings → Gano Reseller)
  - ✅ Bundle Handler (3-year SKU mapping)
  - ✅ Filtros sync (precios override, market es-CO/COP)
  - ✅ Smoke test page (admin only)

### Base de Datos ❌ Pendiente
- **PFIDs en wp_options:** VACÍO (no configurado aún)
- **Acción requerida:** Admin debe entrar a Ajustes → Gano Reseller y llenar los 8 campos con valores del RCC

### Theme & Pages ✅
- **gano-child/functions.php:** Listo, tiene filtros para API args (es-CO, COP)
- **page-ecosistemas.php:** Tiene 4 CTAs "Elegir Plan" — estos van a convertirse en iframe embebidos
- **page-ecosistemas.php:** Ya contiene shortcodes [gano_cta_registro] (de Auditoría UX)

### Plugins Activos (No tocar sin decisión explícita)
```
✅ gano-phase6-catalog
✅ gano-phase7-activator
✅ gano-reseller-enhancements
✅ reseller-store (base GoDaddy)
```

---

## Arch Structure — Fase 4

```
┌─────────────────────────────────────────┐
│  Visitor: page-ecosistemas.php          │
│  Shows: 4 ecosistema cards + CTA        │
└──────────────┬──────────────────────────┘
               │
               ▼
        Click "Elegir Plan"
               │
               ▼
┌─────────────────────────────────────────┐
│  Shortcode: [gano_reseller_iframe]      │
│  ├─ Reads: gano_pfid_* from wp_options  │
│  │  (or PENDING_RCC if empty)           │
│  └─ Renders: <iframe> to Reseller       │
│     Store with PFID param               │
└──────────────┬──────────────────────────┘
               │
               ▼
        GoDaddy Reseller Store
        (iframe, white-label)
               │
               ▼
        Customer checkout flow
        (secure, GoDaddy handles payment)
```

---

## Tareas Principales (en orden)

### 1️⃣ Verificar Sincronización Dinámica de PFIDs
**Archivo:** `wp-content/plugins/gano-reseller-enhancements/`
**Acción:** Revisar que el plugin base GoDaddy Reseller populate automáticamente wp_options con `rstore_*` opciones. Los nuestros PFIDs leen desde ahí vía get_option().

**Validación:**
```bash
wp option list --search=rstore_  # En producción, debe listar opciones sincronizadas
```

---

### 2️⃣ Crear Shortcode [gano_reseller_iframe]
**Archivo:** `wp-content/themes/gano-child/functions.php`
**Qué hace:**
- Lee PFID desde wp_options (fallback: PENDING_RCC)
- Si PFID válido: renderiza `<iframe src="reseller-store/?pfid=XXX">`
- Si PENDING_RCC: muestra CTA "Configurar en Ajustes → Gano Reseller"
- Incluye iframe-resizer script (responsive height)

**Shortcode Usage:**
```php
[gano_reseller_iframe ecosistema="hosting_economia" class="gano-iframe--premium"]
```

---

### 3️⃣ Mapear Ecosistemas a Shortcodes
**Archivo:** `wp-content/themes/gano-child/templates/page-ecosistemas.php`
**Acción:**
- Líneas ~60-180: Donde están los 4 botones "Elegir Plan"
- Reemplazar: `<a href="/shop">Elegir</a>` → `echo do_shortcode('[gano_reseller_iframe ecosistema="hosting_economia"]');`
- Mantener: CTA Registro (de Auditoría UX) ya integrada

---

### 4️⃣ CSP Headers & Iframe Resizer
**Archivo:** `wp-content/mu-plugins/gano-security.php`
**Acción:**
- Permitir iframe: `frame-src https://reseller-store.godaddy.com`
- Enqueue: `js/gano-iframe-resizer.min.js` en footer (antes de closing `</body>`)
- Script: `iFrameResize({ log: false }, '.gano-iframe--reseller');`

---

### 5️⃣ Smoke Test Checklist
**Manual Testing** (en staging o local):

**Setup:**
```
1. Activar gano-reseller-enhancements
2. Ir a Ajustes → Gano Reseller
3. Llenar 4 PFIDs hosting con valores de demo (ej: 123456, 123457, etc.)
4. Guardar
```

**Test Cases:**
- [ ] page-ecosistemas.php carga sin JS errors
- [ ] Cada CTA "Elegir" abre iframe embebido
- [ ] Iframe es responsive (redimensiona con viewport)
- [ ] Reseller Store logo/branding visible dentro iframe
- [ ] Carrito funciona (add to cart, proceed to checkout)
- [ ] PFID correcto pasado en URL de iframe (inspect Network tab)
- [ ] CSP headers no bloquean iframe (no errors en console)
- [ ] Fallback funciona si PFID es PENDING_RCC

---

### 6️⃣ Documentar en Wiki
**Archivo:** `wiki/dev-sessions/fase4-reseller-integration-2026-04-21.md`
**Contenido:**
- Decisiones de brainstorming (PFIDs dinámicos, iframe, manual testing)
- Arquitectura (diagrama)
- Checklist de smoke test (con resultados ✅/❌)
- Blockers encontrados (si aplica)
- Próximos pasos

---

## Bloqueadores Conocidos

| Bloqueador | Status | Mitigación |
|-----------|--------|-----------|
| PR #279 merge a main | 🔴 BLOCKING | Esperando admin review (GitHub branch protection) |
| PFIDs en RCC | 🟡 PENDING | Diego debe obtener valores del RCC y configurar en admin panel |
| Staging site | 🟢 AVAILABLE | Usar para testing antes de producción (ver CLAUDE.md) |

---

## Recursos de Referencia

| Archivo | Propósito |
|---------|-----------|
| `CLAUDE.md` | Reglas proyecto: español, no WooCommerce/Elementor sin decisión, plugins de fase no borrar |
| `memory/project_gano_digital.md` | Estado completo del proyecto (fases, stack, timeline) |
| `memory/ide_sync_setup_2026_04_21.md` | Setup IDE Sync (Cursor + Antigravity hooks auto-captura) |
| `wp-content/plugins/gano-reseller-enhancements/` | Plugin Reseller (PFID admin, bundle handler, filtros) |
| `wp-content/themes/gano-child/functions.php` | Theme functions (agregar shortcode aquí) |
| `wp-content/themes/gano-child/templates/page-ecosistemas.php` | Página ecosistemas (mapear shortcodes aquí) |
| `wp-content/mu-plugins/gano-security.php` | Seguridad (actualizar CSP headers aquí) |
| `wiki/dev-sessions/` | Auto-populated by IDE hooks (sesión captures, commits, changes) |

---

## Instrucciones para Cursor

### Flujo de Trabajo Recomendado

1. **Leer contexto:**
   ```
   @CLAUDE.md @memory/project_gano_digital.md @memory/ide_sync_setup_2026_04_21.md
   ```

2. **Revisar plugin existente:**
   ```
   @wp-content/plugins/gano-reseller-enhancements/
   ```

3. **Crear shortcode:**
   - Edit: `wp-content/themes/gano-child/functions.php`
   - Add: `gano_reseller_iframe()` function (40 líneas)
   - Register: `add_shortcode('gano_reseller_iframe', 'gano_reseller_iframe');`

4. **Mapear ecosistemas:**
   - Edit: `wp-content/themes/gano-child/templates/page-ecosistemas.php`
   - Replace: 4 botones con shortcodes
   - Validate: No borrar CTA Registro existente

5. **Actualizar CSP:**
   - Edit: `wp-content/mu-plugins/gano-security.php`
   - Add: `frame-src https://reseller-store.godaddy.com`
   - Enqueue: iframe-resizer script

6. **Smoke test manual:**
   - Documentar en: `wiki/dev-sessions/fase4-reseller-integration-2026-04-21.md`
   - Checklist: 8 test cases (ver sección 5️⃣ arriba)

7. **Commit & PR:**
   ```bash
   git checkout -b fase-4/reseller-integration
   git add wp-content/themes/gano-child/functions.php
   git add wp-content/themes/gano-child/templates/page-ecosistemas.php
   git add wp-content/mu-plugins/gano-security.php
   git add wiki/dev-sessions/fase4-reseller-integration-2026-04-21.md
   git commit -m "Fase 4: Reseller iframe integration + CSP headers"
   git push -u origin fase-4/reseller-integration
   ```

---

## Quick Commands

```bash
# SSH to check PFIDs
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145
cd /home/f1rml03th382/public_html/gano.digital
wp option get gano_pfid_hosting_economia

# Local dev: activate plugin
wp plugin activate gano-reseller-enhancements

# Test shortcode
wp eval 'echo do_shortcode("[gano_reseller_iframe ecosistema=\"hosting_economia\"]");'
```

---

## Success Criteria (Fase 4 Completa)

- ✅ Shortcode `[gano_reseller_iframe]` funcional
- ✅ 4 ecosistemas mapeados a shortcodes en page-ecosistemas.php
- ✅ CSP headers permiten iframe de Reseller
- ✅ Smoke test 8/8 casos pasando
- ✅ Documentación en wiki (decisiones + resultados)
- ✅ PR creado a rama `fase-4/reseller-integration`
- ✅ Esperando admin merge de PR #279 + GitHub Actions workflow #04 deploy

---

## Notas para Próxima Sesión

- Si PR #279 se mergea antes de terminar Fase 4, workflow #04 ejecutará RSYNC automático
- Los archivos de Fase 4 (shortcode, CSP, docs) se deployarán automáticamente al servidor
- Validación post-deployment: revisar error_log del servidor, probar CTAs en vivo
- Si hay issues con iframe (CSP block, tamaño), ver `memory/ide_sync_setup_2026_04_21.md` troubleshooting

---

**Preparado por:** Claude Code | **Date:** 2026-04-21 | **Next:** Cursor implementation
