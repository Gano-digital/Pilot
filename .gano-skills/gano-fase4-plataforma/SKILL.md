---
name: gano-fase4-plataforma
description: >
  Investigación y opciones avanzadas de plataforma (WHMCS, DIAN, soporte, etc.). La línea
  operativa actual del proyecto es GoDaddy Reseller + Managed WordPress (ver TASKS.md).
  Usa esta skill para exploración o staging de billing propio solo si Diego lo pide; no
  asumir Wompi ni WHMCS como camino por defecto.
---

# Gano Fase 4 — Plataforma Real de Hosting

Skill para implementar la plataforma completa de hosting de gano.digital:
billing panel, facturación DIAN, portal de cliente, soporte, dominios y VPS.

**Investigación completa**: `memory/research/fase4-plataforma.md`

---

## Pivot comercial (Abril 2026)

La estrategia vigente en `memory/projects/gano-digital.md` y **`TASKS.md`** prioriza **marca blanca GoDaddy Reseller**
(checkout y facturación delegados dentro del ecosistema GoDaddy). El cuerpo de esta skill sigue siendo **material de referencia**
(WHMCS, DIAN, etc.) por si en el futuro se evalúa plataforma propia; **no** es la prioridad mientras Reseller cubra el negocio.
**No** priorizar Wompi ni pasarelas locales salvo decisión explícita del fundador.

---

## Decisiones tomadas (Marzo 2026, datos frescos)

### Billing Panel: WHMCS (no Blesta)
**Por qué WHMCS primero**: El módulo Wompi Colombia oficial solo existe para WHMCS.
Blesta no tiene módulo Wompi (requeriría desarrollo custom).

- WHMCS Plus: $34.95/mes (250 clientes)
- Módulo Wompi: https://marketplace.whmcs.com/product/8212-wompi-widget-web-checkout
  - Actualizado octubre 2025, compatible v8.13, soporte colombiano local
- WHMCS v8.13.1 es la versión estable actual

**Migración futura a Blesta**: Cuando Blesta v6.0 esté disponible (2026) y si se desarrolla módulo Wompi, migrar. Licencia one-time Blesta $350 vs $419.40/año de WHMCS.

### DIAN: ESTUPENDO
- Módulo: https://marketplace.whmcs.com/product/5744-estupendo-facturacion-electronica-colombia
- Contacto: ismary.lara@estupendo.com.co | +57 3132323588
- Proceso DIAN: 2-4 semanas → iniciar PRIMERO
- Formato técnico DIAN 2026: UBL 2.1 XML + firma digital + CUFE
- ESTUPENDO maneja todo el proceso (XML, CUFE, QR, envío a DIAN)

### Tickets: FreeScout
- Gratis, open source, activamente mantenido (v1.17.145)
- Módulo WHMCS: https://github.com/LJPc-solutions/freescout-whmcs-module
- Instalar en support.gano.digital

### Status Page: Upptime
- GitHub-based, totalmente gratis, 30 minutos de setup
- Configura CNAME en DNS: status.gano.digital → GitHub Pages
- Repo base: https://github.com/upptime/upptime

### Dominios: ResellerClub
- Integración nativa WHMCS, 750+ TLDs, .co/.com.co soportados
- Pay-as-you-go: $8-15/dominio (sin tarifa mensual)
- Depósito mínimo ~$50 USD para empezar

### VPS: Hetzner + Caasify
- Hetzner CX22: €3.79/mes → revender a $80.000-100.000 COP
- Módulo Caasify: gratis, soporta Hetzner + DigitalOcean + Vultr + Linode

### WhatsApp: CloudLinkd + Meta Cloud API
- CloudLinkd: https://github.com/cloudlinkd-networks/WHMCS-WhatsApp-Notification
  - Gratis, mantenido activamente (update Mayo 2025)
- Meta Cloud API Colombia pricing (desde Julio 2025):
  - Service messages (respuestas 24h): **GRATIS**
  - Utility (renovaciones, facturas): **$0.0008 USD/msg**
  - Costo estimado 100 clientes: ~$0.06/mes

---

## Stack completo Fase 4

| Componente | Solución | URL / Fuente | Costo/mes |
|-----------|----------|--------------|-----------|
| Billing | WHMCS Plus | whmcs.com | $34.95 |
| Pagos | Módulo Wompi WHMCS | marketplace.whmcs.com | Incluido |
| DIAN | ESTUPENDO | ismary.lara@estupendo.com.co | Confirmar |
| Tickets | FreeScout | github.com/freescout-help-desk | $0 |
| Status | Upptime | upptime.js.org | $0 |
| Dominios | ResellerClub | resellerclub.com | Pay/use |
| VPS | Hetzner+Caasify | hetzner.com | Por cliente |
| WhatsApp | CloudLinkd+Meta | github.com/cloudlinkd-networks | ~$0.06 |
| **TOTAL base** | | | **~$35 USD/mes** |

---

## Checklist de instalación

### 1. WHMCS
```bash
# Instalar en servidor independiente (no el mismo que WordPress)
# PHP 8.1+, MySQL 5.7+, cURL, ionCube Loader
# Descargar: https://download.whmcs.com/
```
- Configuración básica: nombre empresa, URL, moneda COP, zona Bogotá
- Instalar plantilla WHMCS compatible (Six o personalizada)
- Conectar dominio: my.gano.digital + SSL

### 2. Módulo Wompi en WHMCS
```
WHMCS Admin → Configuración → Módulos de pago → Activar Wompi Widget
→ Public Key: pub_prod_...
→ Private Key: prv_prod_...
→ Integrity Secret: prod_integrity_...
→ Modo: production
```

### 3. ResellerClub
```
WHMCS Admin → Configuración → Registradores de dominios → ResellerClub
→ ResellerID: [de tu cuenta ResellerClub]
→ API Key: [de tu cuenta ResellerClub]
→ Sync Domain Pricing
```

### 4. FreeScout
```bash
composer create-project freescout/freescout support.gano.digital
php artisan freescout:install
# Configurar mail: SMTP de gano.digital
# Instalar módulo WHMCS desde GitHub
```

### 5. Upptime (status.gano.digital)
```yaml
# .github/workflows/uptime.yml — automático con template
# Configurar en .upptimerc.yml:
sites:
  - name: gano.digital
    url: https://gano.digital
  - name: WooCommerce
    url: https://gano.digital/shop
  - name: WHMCS Portal
    url: https://my.gano.digital
```
- DNS: `status.gano.digital CNAME [tu-usuario].github.io`

### 6. CloudLinkd WhatsApp
```
1. Crear cuenta: https://wa.cloudlinkd.com
2. Conectar número WhatsApp Business colombiano (+57...)
3. Descargar módulo WHMCS de GitHub
4. WHMCS Admin → Módulos de notificación → CloudLinkd
5. Configurar plantillas: nueva factura, renovación, ticket abierto, pago recibido
```

---

## Errores comunes y soluciones

| Problema | Causa | Solución |
|---------|-------|---------|
| Módulo Wompi no procesa PSE | Ambiente incorrecto | Verificar que sea production no sandbox |
| FreeScout no sincroniza WHMCS | Token API expirado | Regenerar API token en WHMCS admin |
| Upptime no actualiza status | GitHub Actions deshabilitado | Habilitar Actions en el repo |
| WhatsApp envío falla | Template no aprobado | Esperar aprobación Meta (24-48h) |
| DIAN rechaza factura | CUFE inválido | ESTUPENDO maneja esto automáticamente |

---

## Contactos clave Fase 4

| Contacto | Para qué | Datos |
|---------|---------|-------|
| ESTUPENDO (Ismary Lara) | DIAN facturación | ismary.lara@estupendo.com.co / +57 3132323588 |
| WHMCS Support | Licencia y soporte | https://whmcs.com/support |
| Wompi Soporte | Módulo WHMCS | Panel Wompi → Soporte |
| Hetzner | VPS provider | https://www.hetzner.com/support |
