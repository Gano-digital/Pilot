# RCC → PFID Checklist (Mapping GoDaddy Reseller to Gano Digital)

**Documento técnico para mapear catálogo GoDaddy Reseller (RCC) a constantes PFID**

_Última actualización: 2026-04-16_  
_Responsable: Diego (Gano Digital) + Antigravity tests_

---

## 1. ¿Qué es PFID?

**PFID** = **Private Family ID** (GoDaddy Reseller Store)

- Identificador único del producto en tu catálogo Reseller
- Se obtiene en GoDaddy Reseller Control Center (RCC)
- Se mapea a constantes PHP (`GANO_PFID_*`) en el código
- Se usa en CTAs (Calls-to-Action) de la tienda para redirigir a checkout

---

## 2. ¿Dónde están los PFIDs en el código?

**Archivo**: `wp-content/themes/gano-child/functions.php`

**Líneas aproximadas**: 662–696

**Estructura**:
```php
// Hosting Plans (Gano Ecosystems)
define('GANO_PFID_ESSENTIAL_HOSTING', 'PENDING_RCC');    // Essential: 1 dominio, 50 GB NVMe, CDN
define('GANO_PFID_PROFESSIONAL_HOSTING', 'PENDING_RCC'); // Professional: 3 dominios, 150 GB NVMe, CDN
define('GANO_PFID_PREMIUM_HOSTING', 'PENDING_RCC');      // Premium: 5 dominios, 300 GB NVMe, CDN, staging
define('GANO_PFID_ENTERPRISE_HOSTING', 'PENDING_RCC');   // Enterprise: Dominios ilimitados, 600 GB NVMe, etc.

// SSL Certificates
define('GANO_PFID_SSL_SINGLE', 'PENDING_RCC');           // Single domain SSL
define('GANO_PFID_SSL_WILDCARD', 'PENDING_RCC');         // Wildcard SSL

// Add-ons
define('GANO_PFID_BACKUP_ADDON', 'PENDING_RCC');         // Backup & Disaster Recovery
define('GANO_PFID_SECURITY_ADDON', 'PENDING_RCC');       // Advanced Security
define('GANO_PFID_PERFORMANCE_ADDON', 'PENDING_RCC');    // Performance Optimization
```

---

## 3. Cómo Obtener PFIDs de RCC

### Paso 1: Acceder a GoDaddy Reseller Control Center

1. Ve a: https://reseller.godaddy.com/
2. Inicia sesión con tu cuenta Reseller
3. Navega a: **Products → Product Catalog**

### Paso 2: Localizar el Producto

1. Busca (ej.) "Managed WordPress" o "Essential Hosting"
2. Haz clic en el producto para abrirlo
3. En la página de detalles, copia el **PFID** (visible en la URL o en los metadatos)

### Paso 3: Validación de PFID

- **Formato**: Número único, generalmente 4-6 dígitos (ej. `123456`)
- **No incluyas**: prefijos, sufijos o formatos especiales
- **Ejemplo válido**: `123456`
- **Ejemplo inválido**: `PFID_123456`, `123456_ESSENTIAL`

---

## 4. Tabla de Mapeo: Catálogo → PFID → PHP Constante

| # | Producto (RCC) | Descripción | PFID (del RCC) | PHP Constante | Estado |
|---|---|---|---|---|---|
| 1 | Managed WordPress Essential | 1 dominio, 50 GB NVMe, CDN | `ENTER_RCC_PFID` | `GANO_PFID_ESSENTIAL_HOSTING` | ⏳ Pendiente |
| 2 | Managed WordPress Professional | 3 dominios, 150 GB NVMe, CDN | `ENTER_RCC_PFID` | `GANO_PFID_PROFESSIONAL_HOSTING` | ⏳ Pendiente |
| 3 | Managed WordPress Premium | 5 dominios, 300 GB NVMe, CDN, staging | `ENTER_RCC_PFID` | `GANO_PFID_PREMIUM_HOSTING` | ⏳ Pendiente |
| 4 | Managed WordPress Enterprise | Dominios ilimitados, 600 GB NVMe, etc. | `ENTER_RCC_PFID` | `GANO_PFID_ENTERPRISE_HOSTING` | ⏳ Pendiente |
| 5 | SSL Certificate (Single) | Dominio único | `ENTER_RCC_PFID` | `GANO_PFID_SSL_SINGLE` | ⏳ Pendiente |
| 6 | SSL Certificate (Wildcard) | Wildcard domain coverage | `ENTER_RCC_PFID` | `GANO_PFID_SSL_WILDCARD` | ⏳ Pendiente |
| 7 | Backup & Disaster Recovery | Add-on de backup continuo | `ENTER_RCC_PFID` | `GANO_PFID_BACKUP_ADDON` | ⏳ Pendiente |
| 8 | Advanced Security | Add-on de seguridad avanzada | `ENTER_RCC_PFID` | `GANO_PFID_SECURITY_ADDON` | ⏳ Pendiente |
| 9 | Performance Optimization | Add-on de optimización | `ENTER_RCC_PFID` | `GANO_PFID_PERFORMANCE_ADDON` | ⏳ Pendiente |

---

## 5. Cómo Validar PFIDs (Post-Mapping)

### Validación en Staging

1. **Abre staging site**: https://gano.digital/staging/ (o URL staging en servidor GoDaddy)
2. **Navega a un CTA**: (ej.) "Comprar Essential" en landing page
3. **Verifica**:
   - ¿El carrito se abre?
   - ¿Aparece el producto correcto?
   - ¿El precio es correcto?
4. **Captura screenshot** si es exitoso

### Validación en Producción

**Posteror a staging exitoso**:

1. **Repite en producción**: https://gano.digital/
2. **Verifica con Antigravity test**: `/reseller-cart-test production`
3. **Monitorea**:
   - Órdenes en RCC dashboard dentro de 5 min
   - Emails de confirmación al cliente
   - Zero CORS/500 errors en logs

---

## 6. Seguridad: Mantener PFIDs Privados

⚠️ **NO hacer**:
- Pastear PFIDs en chat público, GitHub issues, o PR comments sin protección
- Commitear `GANO_PFID_*` valores reales en el repositorio (usar servidor/mu-plugin)
- Compartir credenciales RCC en registros de compilación

✅ **Hacer**:
- Guardar PFIDs en `wp-config.php` o archivo de configuración privado del servidor
- Usar `wp-content/mu-plugins/` para valores sensibles
- Validar PFIDs localmente en staging antes de producción
- Documentar cambios en TASKS.md sin valores explícitos

---

## 7. Workflow Recomendado (Antigravity + Diego)

### Fase 1: Discovery (Diego humano)
1. Diego accede a RCC
2. Copia los 9 PFIDs en orden
3. Comparte lista en privado (chat directo, no público)

### Fase 2: Mapeo (Claude)
1. Claude actualiza `wp-content/themes/gano-child/functions.php`
2. Reemplaza `PENDING_RCC` con valores reales
3. Commit y push a rama `feature/phase4-pfid-mapping`

### Fase 3: Testing (Antigravity + Diego)
1. Diego en Agent Manager: `/reseller-cart-test staging`
2. Antigravity prueba cada CTA
3. Test report: ¿PASS 9/9 steps?
4. Si PASS: merge a main, luego production test

---

## 8. Troubleshooting

| Problema | Síntoma | Solución |
|----------|---------|----------|
| PFID incorrecto | Carrito muestra producto diferente | Verifica PFID en RCC; copiar exactamente sin caracteres extra |
| PFID expirado | Carrito retorna 404 | Producto descontinuado en RCC; reemplazar o deprecar en código |
| Precio mismatch | Carrito muestra precio diferente al sitio | Sincronizar precios en RCC con código (o usar override en ACF) |
| Redirect timeout | Carrito no se abre | Verifica red/CORS; revisa logs servidor `wp-content/gano-deploy/` |

---

## 9. Referencias

| Documento | Propósito |
|-----------|-----------|
| `TASKS.md` § Fase 4 | Estado operativo Reseller, CTA mapping |
| `wp-content/themes/gano-child/functions.php` (L. 662–696) | Ubicación exacta constantes PFID |
| `memory/integration/antigravity-integration-2026-04-06.md` | Setup Antigravity, test syntax |
| `.github/DEV-COORDINATION.md` | Secretos GoDaddy API (si se necesita) |
| `memory/commerce/crypto-manual-fulfillment-godaddy-policy-2026-04.md` | Pago crypto → compra manual RCC (referencia; modelo full-crypto diferido) |
| `memory/commerce/estrategia-comercial-hibrido-checkout-servicios-complementarios-2026-04.md` | Estrategia híbrida: checkout Reseller + crypto opcional en servicios complementarios Gano |

---

## 10. Checklist Final (Post-Mapping)

```
Infraestructura de código (ya completo — no repetir):
  [x] Panel wp-admin → Ajustes → Gano Reseller implementado (class-pfid-admin.php)
  [x] Smoke test wp-admin → Herramientas → Smoke Test Reseller (class-smoke-test.php)
  [x] Constantes GANO_PFID_* en functions.php leen wp_options con fallback PENDING_RCC
  [x] Locale API corregido a es-CO / COP
  [x] CTAs con fallback seguro a /contacto cuando PFID no configurado
  [x] Check de PFIDs añadido al smoke test (verifica 8/8 en una tabla)

PFID Discovery (acción humana — Diego):
  [ ] Verificar pl_id en RCC → Account → Private Label ID (confirmar que 599667 es correcto)
  [ ] Configurar pl_id en wp-admin → Ajustes → Reseller Store
  [ ] 8 PFIDs extraídos de RCC → Products → Product Catalog
  [ ] Formato correcto (3-10 dígitos sin prefijos)
  [ ] Ingresados en wp-admin → Ajustes → Gano Reseller → Guardar PFIDs
  [ ] Banner verde "✓ Checkout listo" visible (8/8 configurados)

Smoke Test Sandbox (acción humana — Diego):
  [ ] wp-admin → Herramientas → Smoke Test Reseller → checks 1-5 en verde
  [ ] Modo sandbox activado
  [ ] URL de carrito abre cart.test-godaddy.com con producto y precio COP
  [ ] Carrito abandonado antes de pagar (sin cargo real)

Production Testing (tras sandbox OK):
  [ ] Modo sandbox desactivado
  [ ] CTA en /ecosistemas → carrito cart.secureserver.net con pl_id real
  [ ] Precio en COP correcto
  [ ] Carrito abandonado antes de pagar
  [ ] Issue #264 cerrado con evidencia en memory/ops/rcc-checkout-issue-264-status.md
```

---

**Documento creado**: 2026-04-16  
**Propósito**: Guía técnica para mapeo RCC → PFID → Gano Digital  
**Responsable**: Diego (datos RCC) + Claude (integración) + Antigravity (testing)  
