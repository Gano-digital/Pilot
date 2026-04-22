# Tarea cd-repo-002 — Obtener PFIDs desde GoDaddy RCC e integrar en functions.php

**ID:** `cd-repo-002`  
**Prioridad:** P1  
**Requiere humano:** SÍ (Diego accede wp-admin + RCC GoDaddy)  
**Generado:** 2026-04-19  
**Estado:** Listo para ejecución (bloqueante para cd-content-002)

---

## Objetivo

Extraer los 9 **PFID** (Product Family ID) de catálogo Reseller Store en GoDaddy Reseller Control Center, validarlos y mapearlos a constantes PHP en `wp-content/themes/gano-child/functions.php`.

**Por qué es crítico:**
- PFIDs son **identificadores únicos** de productos en GoDaddy Reseller Store
- Sin PFIDs: CTAs de `/shop-premium` y `/ecosistemas` no redirigen correctamente a carrito
- Validación previa: evita errores en checkout (404 en carrito, SKUs incorrectos)
- Bloquea: cd-content-002 (páginas ecosistemas con precios dinámicos)

---

## 📋 Estructura de 9 PFIDs a obtener

| # | Producto | Constante PHP | Ubicación RCC | Validación |
|---|----------|----------------|----------------|------------|
| **1** | Essential Hosting (Essential Plan) | `GANO_PFID_ESSENTIAL_HOSTING` | Catalog → Product Families → Hosting | Debe ser numérico, 4–7 dígitos |
| **2** | Premium Hosting (Fortaleza Delta) | `GANO_PFID_PREMIUM_HOSTING` | Catalog → Product Families → Hosting | Debe ser numérico, 4–7 dígitos |
| **3** | Elite Hosting (Bastión SOTA) | `GANO_PFID_ELITE_HOSTING` | Catalog → Product Families → Hosting | Debe ser numérico, 4–7 dígitos |
| **4** | Enterprise Hosting (Legado: si existe) | `GANO_PFID_ENTERPRISE_HOSTING` | Catalog → Product Families → Hosting (si aparece) | Numérico O dejar vacío si no existe |
| **5** | SSL Single Domain | `GANO_PFID_SSL_SINGLE` | Catalog → Product Families → SSL Certificates | Debe ser numérico |
| **6** | SSL Wildcard | `GANO_PFID_SSL_WILDCARD` | Catalog → Product Families → SSL Certificates | Debe ser numérico |
| **7** | Backup Add-on | `GANO_PFID_BACKUP_ADDON` | Catalog → Add-ons → Backup | Debe ser numérico |
| **8** | Security Add-on | `GANO_PFID_SECURITY_ADDON` | Catalog → Add-ons → Security | Debe ser numérico |
| **9** | Performance Add-on | `GANO_PFID_PERFORMANCE_ADDON` | Catalog → Add-ons → Performance | Debe ser numérico |

---

## 🔐 Seguridad — Guardrails críticos

✅ **Hacer:**
- [ ] Usar **modo incógnito / privado** en navegador para acceder a RCC (evita cookies persistentes)
- [ ] **No guardar PFIDs en Slack/email** — solo en este documento (versionado en git, no en plaintext público)
- [ ] Después de copiar PFID: cerrar pestaña RCC + limpiar historial
- [ ] **No compartir URL de RCC** con terceros
- [ ] Validar PFIDs con `python scripts/validate_agent_queue.py` antes de commit

❌ **No hacer:**
- No usar credenciales RCC en GitHub Actions (aunque sea secreto) — usar solo en testing local/staging
- No modificar PFIDs en producción sin backup previo en functions.php
- No enviar PFIDs vía email o chat no encriptado
- No dejar constantes vacías si no encontraste PFID (documentar "NO ENCONTRADO" en comentario)

---

## 🔄 Flujo de ejecución (para Diego)

**Tiempo estimado:** 30–45 minutos  
**Requisitos previos:**
- Acceso a GoDaddy Reseller Control Center (cuenta de reseller activa)
- Editor de código (VS Code) abierto en `wp-content/themes/gano-child/functions.php`
- Este documento a mano como referencia

---

### Paso 1: Acceder a GoDaddy RCC

**1.1 — Abre GoDaddy Reseller Control Center:**
```
URL: https://www.godaddy.com/reseller
```
- Inicia sesión con credenciales de reseller (usuario/contraseña)
- Verás panel principal con "Catalog", "Customers", "Orders", "Reports"

**1.2 — Navega a Catalog:**
```
En el menú izquierdo:
Catalog → Product Families
```

---

### Paso 2: Extraer PFIDs de Hosting

**2.1 — Ubicar producto hosting "Essential":**

Dentro de **Product Families**, busca la sección **Hosting**:
- Haz clic en **Hosting**
- Verás lista de planes disponibles (Essential, Premium, Elite, Enterprise, etc.)

**2.2 — Copiar PFID de Essential:**

Para cada plan:
1. Haz clic en el nombre del plan (ej: "Essential Hosting")
2. Se abre detalles del producto
3. **Busca el campo "Product Family ID"** (abreviado **PFID** o **Family ID**)
4. Es un número, ej: `4729` o `3456`
5. **Cópialo exactamente** (sin espacios, sin símbolos)
6. Pega en tabla de validación abajo

**Tabla de captura — Hosting:**

| Plan | PFID extraído | Estado | Notas |
|------|-------|--------|-------|
| Essential Hosting | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 4729 |
| Premium Hosting | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 4730 |
| Elite Hosting | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 4731 |
| Enterprise Hosting | [PENDIENTE DIEGO] | [ ] Copiado | Si no existe: marcar "N/A" |

**2.3 — Validación rápida:**
- ¿Cada PFID es un número? ✓
- ¿Tienen 4–7 dígitos? ✓
- ¿Son todos diferentes? ✓

---

### Paso 3: Extraer PFIDs de SSL

**3.1 — Navega a SSL Certificates:**

En **Product Families**:
1. Busca sección **SSL Certificates** (puede estar bajo "Security" o directamente en lista)
2. Haz clic en **SSL Certificates**

**3.2 — Extraer PFID de cada tipo:**

Busca dos productos:
- **SSL Certificate - Single Domain** → copia PFID
- **SSL Certificate - Wildcard** → copia PFID

**Tabla de captura — SSL:**

| Producto | PFID extraído | Estado | Notas |
|----------|-------|--------|-------|
| SSL Single Domain | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 5001 |
| SSL Wildcard | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 5002 |

---

### Paso 4: Extraer PFIDs de Add-ons

**4.1 — Navega a Add-ons:**

En **Product Families**:
1. Busca sección **Add-ons**
2. Haz clic

**4.2 — Extraer cada add-on:**

Busca tres productos:
- **Backup Add-on** (ó "Advanced Backup")
- **Security Add-on** (ó "Security Pro")
- **Performance Add-on** (ó "Performance Plus")

**Tabla de captura — Add-ons:**

| Producto | PFID extraído | Estado | Notas |
|----------|-------|--------|-------|
| Backup Add-on | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 6001 |
| Security Add-on | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 6002 |
| Performance Add-on | [PENDIENTE DIEGO] | [ ] Copiado | Ej: 6003 |

---

### Paso 5: Validación de PFIDs (local antes de commitear)

**5.1 — Abre terminal en raíz del proyecto:**

```bash
cd /ruta/a/gano.digital
```

**5.2 — Ejecuta validador:**

```bash
python scripts/validate_agent_queue.py
```

**5.3 — Verifica salida:**

Debería confirmar:
- ✅ JSON válido (si estructura correcta)
- ✅ Todas las constantes GANO_PFID_* son números

Si hay error: documenta abajo en **§ Troubleshooting**.

---

### Paso 6: Integrar PFIDs en functions.php

**6.1 — Abre archivo en VS Code:**

```
wp-content/themes/gano-child/functions.php
```

**6.2 — Localiza la sección de constantes PFIDs (línea ~662):**

Busca comentario:
```php
// ===== CONSTANTES GANO PFID (Reseller Store) =====
```

Verás estructura así:
```php
if ( ! defined( 'GANO_PFID_ESSENTIAL_HOSTING' ) ) {
    define( 'GANO_PFID_ESSENTIAL_HOSTING', [PENDIENTE_DIEGO] );
}
```

**6.3 — Reemplaza cada `[PENDIENTE_DIEGO]` con PFID extraído:**

**Antes:**
```php
define( 'GANO_PFID_ESSENTIAL_HOSTING', [PENDIENTE_DIEGO] );
define( 'GANO_PFID_PREMIUM_HOSTING', [PENDIENTE_DIEGO] );
// etc.
```

**Después (ejemplo):**
```php
define( 'GANO_PFID_ESSENTIAL_HOSTING', 4729 );
define( 'GANO_PFID_PREMIUM_HOSTING', 4730 );
define( 'GANO_PFID_ELITE_HOSTING', 4731 );
define( 'GANO_PFID_ENTERPRISE_HOSTING', 4732 ); // O comentar si no existe
define( 'GANO_PFID_SSL_SINGLE', 5001 );
define( 'GANO_PFID_SSL_WILDCARD', 5002 );
define( 'GANO_PFID_BACKUP_ADDON', 6001 );
define( 'GANO_PFID_SECURITY_ADDON', 6002 );
define( 'GANO_PFID_PERFORMANCE_ADDON', 6003 );
```

**6.4 — Guardar cambios:**

```
Ctrl+S (Windows) / Cmd+S (Mac)
```

---

### Paso 7: Testing en staging (antes de producción)

**7.1 — Despliega a staging:**

Si tienes staging en gano.digital (ej: staging.gano.digital):
```bash
# Via SFTP o deployment workflow
git push origin develop  # si existe rama develop
```

**7.2 — Prueba CTAs en staging:**

1. Abre navegador: `https://staging.gano.digital/ecosistemas`
2. Haz clic en botón "Elegir este plan" en cualquier tarjeta
3. Debería redirigir a carrito GoDaddy con producto correcto
4. Verifica URL del carrito contiene PFID en parámetro (ej: `?pfid=4729`)

**7.3 — Si falla redirección:**

Documenta error en sección **Troubleshooting** abajo.

---

### Paso 8: Commit en git

**8.1 — Stage cambios:**

```bash
git add wp-content/themes/gano-child/functions.php
```

**8.2 — Commit con mensaje referenciando tarea:**

```bash
git commit -m "cd-repo-002: Integrar PFIDs desde RCC (Essential, Premium, Elite, SSL, add-ons)"
```

**8.3 — Push a main o develop:**

```bash
git push origin main  # o develop si es rama de feature
```

---

## 🚨 Troubleshooting

### ❌ Escenario A: "No encuentro la sección Hosting en Product Families"

**Causa probable:** Menú colapsado o interfaz RCC diferente en tu versión.

**Acciones:**
1. Expande todas las secciones del menú izquierdo (haz clic en `>` o `+`)
2. Busca **"Product Families"** en buscador global RCC (Ctrl+F)
3. Si no aparece: contacta soporte GoDaddy Reseller (chat en panel RCC)
4. Nota: PFIDs también pueden estar en **"Catalog" → "Products"** en algunas versiones

**Documentar:** Escribe comentario en functions.php explicando dónde encontraste los datos.

---

### ❌ Escenario B: "El PFID es un texto, no un número"

**Causa probable:** Copiaste mal, o GoDaddy cambió formato.

**Acciones:**
1. Vuelve a copiar desde RCC (abre en incógnito para resetear sesión)
2. Si sigue siendo texto: contacta soporte GoDaddy
3. **No commitees PFIDs en formato incorrecto** — bloquea checkout

**Validación:**
```php
// En functions.php, agrega temporalmente:
var_dump( GANO_PFID_ESSENTIAL_HOSTING ); // Debe mostrar: int(4729)
```

Si muestra `string(4) "4729"` en lugar de `int(4729)`: error de formato.

---

### ❌ Escenario C: "Redirección a carrito falla en staging"

**Causa probable:**
1. PFID incorrecto o no mapeado
2. URL de carrito GoDaddy ha cambiado
3. Plugin `gano-reseller-enhancements` desactivo

**Acciones:**
1. Verifica que `gano-reseller-enhancements` está activo en wp-admin → Plugins
2. Prueba PFID en URL directa:
   ```
   https://www.godaddy.com/reseller/cart?pfid=4729
   ```
   Debería abrir carrito con producto
3. Revisa `wp-content/plugins/gano-reseller-enhancements/` línea de redirección:
   ```php
   wp_redirect( 'https://www.godaddy.com/reseller/cart?pfid=' . GANO_PFID_ESSENTIAL_HOSTING );
   ```

**Documentar:** Si error persiste, abre issue #[N] en GitHub con detalles.

---

### ❌ Escenario D: "¿Enterprise Hosting no existe en RCC?"

**Decisión:**
- Si **no existe en catálogo RCC:** déjalo como constante vacía o comentado:
  ```php
  // define( 'GANO_PFID_ENTERPRISE_HOSTING', [NO ENCONTRADO] );
  ```
- Actualiza páginas ecosistemas (`/shop-premium`) para mostrar solo 3 planes (Essential, Premium, Elite)
- Documen con nota en functions.php: "Catalogo RCC 2026-04-19 sin Enterprise"

---

## 📝 Validación de datos extraídos

Antes de commitear, verifica **checklist de datos:**

- [ ] Todos los 9 PFIDs son números (sin comillas, sin símbolos)
- [ ] PFIDs de hosting son diferentes entre sí
- [ ] PFIDs de SSL son diferentes entre hosting
- [ ] Ningún PFID es 0 o vacío (excepto Enterprise si no existe)
- [ ] Longitud típica: 4–7 dígitos
- [ ] URL RCC fue cerrada + historial limpiado (seguridad)
- [ ] functions.php fue validado con `php -l` (sin errores de sintaxis)

**Comando de validación PHP:**
```bash
php -l wp-content/themes/gano-child/functions.php
```

Debería mostrar: `No syntax errors detected`

---

## ✅ Definition of Done

La tarea se cierra cuando:

- [ ] Los 9 PFIDs han sido extraídos de GoDaddy RCC (Catalog → Product Families)
- [ ] Cada PFID es validado como número (4–7 dígitos)
- [ ] Constantes PHP en functions.php (línea ~662–696) reemplazan `[PENDIENTE_DIEGO]`
- [ ] `php -l functions.php` → sin errores de sintaxis
- [ ] `python scripts/validate_agent_queue.py` → OK
- [ ] Testing en staging.gano.digital: CTAs redirigen a carrito GoDaddy
- [ ] Parámetros de URL en carrito incluyen PFID correcto
- [ ] Commit en git con mensaje `cd-repo-002: Integrar PFIDs...`
- [ ] Cambios pusheados a `main` o `develop`
- [ ] RCC fue cerrada + navegador limpiado (sin datos sensibles en caché)
- [ ] Documentación en functions.php clarifica cada PFID (comentarios útiles)

---

## 🎯 Impacto y dependencias

**Bloquea:**
- ✅ cd-content-002: Páginas ecosistemas `/shop-premium` requieren PFIDs para CTAs dinámicas
- ✅ Checkout Reseller (Fase 4): Sin PFIDs → carrito no responde

**Desbloqueado por:**
- ✅ Acceso a GoDaddy RCC (Diego ya tiene)

**Próximo paso post-Done:**
- Ejecutar cd-content-002 para crear 4 páginas de planes con precios y CTAs
- Ejecutar ag-phase4-001 (Antigravity activation) para smoke test completo checkout

---

## 🔍 Verificación por herramientas (opcional)

Si después de integrar PFIDs quieres verificar con script:

```bash
# Listar todos los PFIDs definidos:
grep -n "GANO_PFID_" wp-content/themes/gano-child/functions.php | head -20

# Verificar valores:
php -r "
  require 'wp-load.php';
  echo 'GANO_PFID_ESSENTIAL_HOSTING: ' . GANO_PFID_ESSENTIAL_HOSTING . PHP_EOL;
  echo 'GANO_PFID_PREMIUM_HOSTING: ' . GANO_PFID_PREMIUM_HOSTING . PHP_EOL;
  // ... etc para todos
"
```

---

## 📞 Escalar si necesario

Si durante ejecución encuentras:
- **RCC inaccesible** → Contacta soporte GoDaddy (24/7)
- **Formato PFID ambiguo** → Screenshot de RCC + abre issue GitHub
- **Staging.gano.digital no disponible** → Usa producción con cuidado (o local con wp-cli)
- **Cambios no reflejan en checkout** → Limpiar caché WordPress:
  ```bash
  wp cache flush
  ```

---

**Generado por:** Claude Dispatch (cd-repo-002 implementation guide)  
**Última actualización:** 2026-04-19 21:45 UTC  
**Próxima tarea:** cd-content-002 (bloqueada hasta Done de cd-repo-002)
