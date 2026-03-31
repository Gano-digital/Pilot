# Investigación Fase 4 — Plataforma Real de Hosting
_Actualizado: Marzo 18, 2026 — Fuentes verificadas Marzo 2026_

**Objetivo Fase 4**: Transformar gano.digital de un sitio de marketing en una plataforma real de hosting con provisión automática, portal de cliente, facturación DIAN y dominio integrado.

---

## 1. BILLING PANEL — Decisión final: WHMCS

### Resumen ejecutivo
**Recomendación: WHMCS** (por ahora) — No por costo sino por el módulo Wompi Colombia oficial y activo.

| Criterio | WHMCS | Blesta |
|----------|-------|--------|
| Módulo Wompi Colombia | ✅ Oficial, actualizado Oct 2025 | ❌ No existe (custom dev) |
| Precio 2026 (250 clientes) | $34.95/mes | $17.95/mes |
| Licencia one-time | ❌ No | ✅ $350 (branded) |
| Estado de desarrollo | Estable (v8.13.1) | Activo (v5.13.3, v6 en desarrollo) |
| Seguridad | Historial más vulnerable | Registro más limpio |
| Wompi QR módulo | ✅ También disponible | ❌ No |

### WHMCS Pricing 2026 (vigente desde Enero 2026)
- Plus (250 clientes): **$34.95/mes** (subió de $29.95 en 2025)
- Professional (500): $54.95/mes
- Business (1,000): $79.95/mes

### Blesta Pricing 2026 (vigente desde Enero 2026)
- Branded Monthly: **$17.95/mes**
- Owned Branded (one-time): **$350** ← ÓPTIMO si se migra a Blesta después
- Owned Flex (one-time): $500
- Opcional: soporte/actualizaciones anuales $62/año

### Nota de Blesta para futuro
Blesta v6.0 (UI completamente rediseñada) está en desarrollo para 2026. Si se lanza en 2026, considerar migrar de WHMCS → Blesta cuando el módulo Wompi esté disponible o desarrollado por nosotros.

### Módulo Wompi para WHMCS
- **Wompi Widget & Web Checkout**: https://marketplace.whmcs.com/product/8212-wompi-widget-web-checkout
  - Última actualización: 13 octubre 2025 (compatible con WHMCS 8.13)
  - Métodos: PSE, Nequi, Daviplata, tarjetas
  - Firma SHA256 de integridad incluida
  - Soporte técnico colombiano local
- **Wompi QR**: https://marketplace.whmcs.com/product/7399-wompi-qr (alternativa QR)

---

## 2. FACTURACIÓN DIAN — ESTUPENDO (confirmado activo)

### Estado actual (2026)
- **ESTUPENDO** sigue siendo el módulo recomendado para WHMCS en Colombia
- DIAN-autorizado, mantenido activamente
- Marketplace: https://marketplace.whmcs.com/product/5744-estupendo-facturacion-electronica-colombia

### Contacto ESTUPENDO
- Email: ismary.lara@estupendo.com.co
- Teléfono: +57 3132323588

### Funcionalidades ESTUPENDO
- Emisión manual y automática de facturas (notas débito, crédito)
- Auto-emisión vía WHMCS CRON
- Compatible con sistema de impuestos de WHMCS
- Templates de visualización en área de cliente
- Compatible con PayU Latam

### Requisitos técnicos DIAN 2026
- Formato: **UBL 2.1** (XML con firma digital)
- Código único: **CUFE** (asignado por DIAN en validación)
- **Modelo clearance**: las facturas deben enviarse a DIAN para aprobación ANTES de entregarse al cliente
- Código QR: obligatorio en cada factura
- Retención: mínimo 5 años desde el 1 de enero siguiente a la emisión

### Proceso de activación DIAN
1. Registro como emisor electrónico ante DIAN
2. Obtener certificado digital para firma XML
3. Configurar cuenta de pruebas en ESTUPENDO
4. Configurar cuenta de producción + resolución DIAN
5. Integrar WHMCS CRON para emisión automática

### Alternativa open-source (para integración custom en Blesta)
- PHP package: `henry-ht/ubl21dian` (Packagist)
- GitHub: `helbertDev3c/tc-ubl21dian` (implementación DIAN UBL 2.1)

---

## 3. PORTAL DE CLIENTE (my.gano.digital)

### Solución: WHMCS nativo
WHMCS incluye portal de cliente completo. Subdominio: my.gano.digital → apuntar a IP del servidor WHMCS.
- SSL automático (Let's Encrypt o Cloudflare)
- Gestión de servicios, facturas, tickets desde el portal
- Personalizable con templates WHMCS

---

## 4. SISTEMA DE TICKETS (support.gano.digital)

### Recomendación: FreeScout
- **Versión actual**: v1.17.145 (activamente mantenido, 2025)
- **Costo**: GRATIS (open source)
- **GitHub**: https://github.com/freescout-help-desk/freescout
- **Módulo WHMCS**: https://github.com/LJPc-solutions/freescout-whmcs-module (gratuito)
- **Instalación**: ~2 horas en servidor propio

### Alternativas evaluadas
| Solución | Costo | WHMCS Integration | Veredicto |
|----------|-------|-------------------|-----------|
| **FreeScout** | Gratis | ✅ Módulo GitHub | ✅ Recomendado |
| WHMCS Nativo | Incluido | ✅ Nativo | ⚠️ Funcional pero básico |
| Hesk | Gratis (lite) | Manual | Aceptable |
| osTicket | Gratis | Manual | Complejo para empezar |
| Freshdesk | $15/agente/mes | ✅ API | Innecesario al inicio |

### Setup sugerido
- Instalar FreeScout en support.gano.digital
- Conectar via módulo WHMCS para sincronizar tickets con clientes
- Usar WHMCS nativo como respaldo para ticketing interno

---

## 5. STATUS PAGE (status.gano.digital)

### Recomendación: Upptime (primera opción, coste $0)
- **GitHub-based**: genera status page desde GitHub Actions + GitHub Pages
- **Costo**: GRATIS (usando GitHub Free)
- **Setup**: ~30 minutos
- **Repo**: https://github.com/upptime/upptime
- **Cómo funciona**: monitora URLs cada 5 min, genera historial de uptime, publica en GitHub Pages
- **Dominio custom**: status.gano.digital → GitHub Pages (DNS CNAME)

### Alternativa: Cachet (más control, más complejo)
- **GitHub**: https://github.com/cachethq/cachet
- **Costo**: Gratis (self-hosted) — requiere servidor propio + PHP + BD
- **Estado**: Requiere verificar estado de mantenimiento en 2026

### Alternativa rápida: UptimeRobot Status Pages
- Plan gratuito: 50 monitores, status page pública
- URL: https://uptimerobot.com
- Limitación: branding de UptimeRobot en plan gratuito

### Recomendación para gano.digital
**Corto plazo**: Upptime (GitHub, gratis, fácil)
**Largo plazo**: Cachet en servidor propio para control total + integración WHMCS

---

## 6. DOMINIOS (ResellerClub)

### Recomendación: ResellerClub + módulo WHMCS nativo
- **WHMCS integración**: nativa (incluida en WHMCS)
- **TLDs disponibles**: 750+ incluyendo .co, .com.co, .org.co, .edu.co
- **Modelo**: pay-as-you-go (sin tarifas mensuales)
- **Precio estimado**: $8-15/dominio según TLD
- **Setup**: 2-3 horas en WHMCS

### Para dominios .co colombianos
- ResellerClub soporta .co directamente
- Alternativamente: NeuStar registrar (operador oficial .co)
- Considerar también dominio .com.co para mercado colombiano

### Pasos de activación
1. Crear cuenta en ResellerClub: https://www.resellerclub.com
2. Depositar fondos (mínimo ~$50 USD)
3. En WHMCS: Configuración → Registradores de dominios → ResellerClub
4. Poner API Key + API Secret de ResellerClub
5. Importar precios de TLDs

---

## 7. PROVISIÓN VPS — DigitalOcean o Hetzner

### Módulos disponibles (WHMCS)
- **DigitalOcean**: ModulesGarden module (actualizado Abril 2025)
- **Hetzner**: Caasify free module (soporta DO+Hetzner+Vultr+Linode)
- **Alternativa**: Proxmox con módulo WHMCS para VPS on-premise

### Recomendación para gano.digital
**Corto plazo**: Hetzner (precio/performance superior para LATAM, datacenter en Brasil disponible)
**Módulo**: Caasify (gratis, soporta múltiples providers)
- Hetzner CX22: 2 vCPU, 4GB RAM, 40GB NVMe — **€3.79/mes** (precio Hetzner)
- Margen sugerido: vender a COP 80.000-100.000/mes = ~$20 USD

---

## 8. WHATSAPP BUSINESS API — Notificaciones hosting

### Recomendación: CloudLinkd + Meta Cloud API (costo casi $0)

**CloudLinkd**
- GitHub: https://github.com/cloudlinkd-networks/WHMCS-WhatsApp-Notification
- **Estado**: Activamente mantenido en 2025 (último update: Mayo 2025)
- **Costo**: Gratis (open source)
- **Setup**: Crear cuenta en https://wa.cloudlinkd.com + API key + módulo WHMCS

**Meta WhatsApp Cloud API — Pricing Colombia (desde Julio 2025)**
| Tipo de mensaje | Costo por mensaje |
|-----------------|-------------------|
| Service (usuario inicia, ventana 24h) | **GRATIS** |
| Utility (recordatorios, facturas) | **$0.0008 USD** |
| Authentication (OTP/2FA) | $0.0008 USD |
| Marketing (promo) | $0.02 USD |

**Estimación costo mensual para gano.digital (100 clientes)**
- 100 mensajes de servicio (respuestas, soporte): $0.00
- 50 notificaciones de renovación/factura: $0.04 USD
- 20 OTPs de 2FA: $0.016 USD
- **Total: ~$0.06/mes** → prácticamente GRATIS

**Alternativas de terceros**
- 360Dialog: €200/mes (para volumenes altos, zero-markup Meta)
- Twilio: markup alto ($0.005-0.010/msg + tarifa Meta)
- Vonage: variable

### Flujo de implementación WhatsApp
1. Crear Meta Business Account en https://business.facebook.com
2. Solicitar WhatsApp Business Account (WABA)
3. Verificar número de teléfono colombiano
4. Configurar CloudLinkd con API key de Meta Cloud
5. Instalar módulo CloudLinkd en WHMCS
6. Configurar templates de mensajes para: nueva factura, renovación próxima, ticket abierto, pago recibido

---

## 9. STACK COMPLETO FASE 4 — Resumen de costos estimados

| Componente | Solución | Costo mensual |
|-----------|----------|---------------|
| Billing Panel | WHMCS Plus | $34.95 USD |
| Módulo Wompi | Marketplace WHMCS | Incluido |
| DIAN Facturación | ESTUPENDO (contactar) | Por confirmar |
| Portal cliente | WHMCS nativo | Incluido |
| Tickets | FreeScout | $0 |
| Status page | Upptime (GitHub) | $0 |
| Dominios | ResellerClub | Pay-as-you-go |
| Provisión VPS | Hetzner + Caasify | Costo por cliente |
| WhatsApp notif. | CloudLinkd + Meta | ~$0.06/100 clientes |
| **TOTAL base** | | **~$35 USD/mes** |

---

## 10. PLAN DE IMPLEMENTACIÓN FASE 4

### Semana 1
- [ ] Comprar licencia WHMCS Plus
- [ ] Instalar WHMCS en billing.gano.digital o my.gano.digital
- [ ] Instalar módulo Wompi Widget & Web Checkout
- [ ] Contactar ESTUPENDO (ismary.lara@estupendo.com.co) para proceso DIAN

### Semana 2
- [ ] Configurar ResellerClub en WHMCS (dominios)
- [ ] Instalar FreeScout en support.gano.digital
- [ ] Configurar módulo WHMCS ↔ FreeScout
- [ ] Desplegar Upptime en status.gano.digital (GitHub Pages)

### Semana 3
- [ ] Registrar Meta Business + WABA para WhatsApp
- [ ] Instalar CloudLinkd WHMCS WhatsApp module
- [ ] Configurar Hetzner + módulo Caasify para VPS
- [ ] Pruebas end-to-end: nueva contratación → provisión → factura → WhatsApp

### Semana 4
- [ ] Proceso DIAN con ESTUPENDO (puede tomar 2-4 semanas)
- [ ] Publicar página de precios WHMCS
- [ ] Testing completo antes de lanzamiento

---

## Fuentes y referencias
- Blesta pricing 2026: https://www.blesta.com/pricing/ (Jan 2026)
- Blesta vs WHMCS: https://blog.hosteons.com/2025/05/14/whmcs-vs-blesta-which-is-right-for-your-hosting-business/
- WHMCS Wompi module: https://marketplace.whmcs.com/product/8212-wompi-widget-web-checkout (Oct 2025)
- ESTUPENDO DIAN: https://marketplace.whmcs.com/product/5744-estupendo-facturacion-electronica-colombia
- CloudLinkd WhatsApp: https://github.com/cloudlinkd-networks/WHMCS-WhatsApp-Notification
- Meta WhatsApp pricing: https://developers.facebook.com/documentation/business-messaging/whatsapp/pricing
- FreeScout: https://github.com/freescout-help-desk/freescout
- FreeScout WHMCS module: https://github.com/LJPc-solutions/freescout-whmcs-module
- Upptime: https://upptime.js.org/
- DIAN invoicing Colombia: https://edicomgroup.com/blog/colombia-electronic-invoicing
