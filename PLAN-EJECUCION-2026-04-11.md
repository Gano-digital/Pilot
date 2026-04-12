# ✅ PLAN CONSOLIDADO APROBADO — LISTO PARA EJECUCIÓN

**Fecha:** 2026-04-11 (13:30 UTC)  
**Estado:** ✅ PLAN APROBADO + Instrucciones operativas creadas  
**Decisiones tomadas:** Hero 3D Magnetic Cards + Webhook HTTPS Deploy + Chat placeholder Fase 5

---

## 📋 RESUMEN EJECUCIÓN

He completado una auditoría exhaustiva (6 agentes Explore):
- ✅ Estado issues/PRs/servidor
- ✅ Validación plugins + contenido WordPress
- ✅ SOTA research + skills actuales
- ✅ Validación backup v2-marzo vs código actual (95% implementado)
- ✅ Memorias Cursor + dispatch queue
- ✅ Webhook deploy descubierto (bloqueador SSH resuelto)

**Plan escrito:** `C:\Users\diego\.claude\plans\binary-brewing-treasure.md`

---

## 🚀 PRÓXIMOS PASOS (TÚ, DIEGO)

### **FASE 0 — Setup secrets (15 min)**

**1. Generar secret (en tu PC, PowerShell/Git Bash):**
```bash
openssl rand -hex 32
# Copia el output de 64 caracteres hexadecimales
```

**2. Agregar a wp-config.php en gano.digital:**
```php
define('GANO_DEPLOY_HOOK_SECRET', 'tu-secret-de-64-chars-aqui');
```

**3. Agregar a GitHub Secrets** (`Gano-digital/Pilot` → Settings → Secrets and variables → Actions):
```
GANO_DEPLOY_HOOK_URL    = https://gano.digital/wp-content/gano-deploy/receive.php
GANO_DEPLOY_HOOK_SECRET = <mismo-secret-que-wp-config>
```

### **FASE 1 — Deploy (30 min)**

**Ejecutar workflow 04 manualmente:**
- GitHub → Actions → "04 · Deploy · Producción" → Run workflow
- O hacer un push a `main` (se dispara automáticamente)
- Monitorea logs (debería mostrar ✅ verde)

**Validar post-deploy:**
- [ ] gano.digital carga (HTTP 200)
- [ ] wp-admin funciona
- [ ] wp-file-manager no aparece en plugin list
- [ ] gano-security.php + gano-seo.php activos en MU-plugins

### **FASE 2–4 — En paralelo (8–10 horas)**

**Parallelizable:**
- **FASE 2 (Diego en Elementor):** Reemplazar Lorem → copy real, implementar hero 3D cards
- **FASE 3 (Diego en GoDaddy RCC):** Depurar catálogo Reseller, smoke test checkout
- **FASE 4 (Claude):** Cerrar issues GitHub, sembrar colas agentes

---

## 📁 DOCUMENTACIÓN GENERADA

He creado **4 documentos nuevos** en `memory/ops/` y `memory/claude/`:

1. **`memory/claude/2026-04-11-plan-aprobado-inicio-ejecucion.md`**
   - Decisiones finales (hero, SSH→webhook, chat)
   - Timeline estimada (12–18 horas)
   - Orden ejecución

2. **`memory/ops/2026-04-11-fase0-webhook-validado.md`**
   - Endpoint webhook funcional (receive.php)
   - Setup secrets paso a paso
   - Verificación post-setup

3. **`memory/ops/2026-04-11-FASE1-instrucciones-operativas.md`**
   - Tareas A1–A4 detalladas (secuencial)
   - Checklist validación
   - Si algo falla (troubleshooting)
   - Timeline: 30 min

4. **`C:\Users\diego\.claude\plans\binary-brewing-treasure.md`**
   - Plan completo (4 fases parallelizables)
   - Criterios GO-LIVE
   - Libertad creativa confirmada

---

## 🎯 CRITERIOS GO-LIVE

| Criterio | Estado | Verificación |
|----------|--------|---|
| **Seguridad deploy** | ✅ | Webhook HTTPS funcional |
| **Código en servidor** | ⏳ | gano.digital HTTP 200 |
| **Parches aplicados** | ⏳ | WP_DEBUG=false, CSP headers |
| **CVE crítica fuera** | ⏳ | wp-file-manager eliminado |
| **Copy real visible** | ⏳ | Hero 3D cards + SOTA secciones |
| **SEO configurado** | ⏳ | GSC + Rank Math schema |
| **Checkout funciona** | ⏳ | Bundle → GoDaddy cart COP |
| **GitHub sync** | ⏳ | Issues cerrados, colas limpias |

---

## 💾 PENDIENTE COMMIT

Hay 11 archivos staged (desde sesiones previas) + 3 nuevos documentos. Haremos commit cuando GitHub lock se libere. **Contenido seguro en memory/ — no hay datos sensibles.**

---

## 🔗 REFERENCIAS RÁPIDAS

- **Plan maestro:** `C:\Users\diego\.claude\plans\binary-brewing-treasure.md`
- **FASE 0 setup:** `memory/ops/2026-04-11-fase0-webhook-validado.md`
- **FASE 1 instrucciones:** `memory/ops/2026-04-11-FASE1-instrucciones-operativas.md`
- **Webhook endpoint:** `wp-content/gano-deploy/receive.php` ✅ (funcional)
- **Workflow 04 deploy:** `.github/workflows/deploy.yml` ✅ (HTTPS, sin SSH)

---

## ⏱️ NEXT ACTIONS

1. **Inmediato:** Generar secret, agregar a GitHub + wp-config (15 min)
2. **Hoy:** Ejecutar workflow 04 (30 min deploy + validación)
3. **Paralelo:** Elementor (FASE 2), RCC (FASE 3), GitHub cleanup (FASE 4)
4. **Target:** GO-LIVE gano.digital 2026-04-12/13

---

**Documento:** 2026-04-11 | Preparado por: Claude | Ejecución: Diego
