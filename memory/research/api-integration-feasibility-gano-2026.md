# Matriz de viabilidad: Gano Digital × ML × GoDaddy API (2026)

**Fecha:** 2026-04-16  
**Estado:** documento vivo — revisar ante cambios en TASKS.md o ToU de plataformas  
**Propósito:** decisión legible para roadmap; determinar qué integraciones valen la pena frente a RCC + WordPress + billing futuro (Fase 4).

**Fuentes canónicas:**
- [`TASKS.md`](../../TASKS.md) — estado actual del proyecto y fases
- [`memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) — investigación técnica detallada de portales ML y GoDaddy

---

## Principios de evaluación

1. **Comercio canónico = GoDaddy Reseller (RCC + Reseller Store).** Toda integración se evalúa como *complemento*, no como sustitución.
2. **Control de cuenta cliente:** cualquier operación vía API que afecte cuentas que Gano Digital **no controla directamente** viola los ToU de GoDaddy y es descartada.
3. **Secretos fuera del repo:** credenciales (API Key/Secret, Client Id/Secret) solo en secret manager o GitHub Actions Secrets — nunca en código ni en issues.
4. **Good as Gold (GoDaddy):** necesario solo cuando la API *compra/provisiona* productos con débito en cuenta prepago; no requerido para uso consultivo.

---

## Matriz de iniciativas

| # | Iniciativa | Valor de negocio | Esfuerzo estimado | Dependencias | Riesgo compliance | Prioridad |
|---|-----------|-----------------|-------------------|--------------|-------------------|-----------|
| 1 | **Reseller Store + RCC — catálogo y CTAs (Fase 4 canónica)** | 🔴 Crítico: es el checkout de vitrina; sin esto no hay venta | Bajo–Medio (config RCC + mapeo PFIDs en `shop-premium.php`) | Cuenta Reseller activa en GoDaddy; `gano-reseller-enhancements` en repo | Bajo (dentro del ToU Reseller) | **P0** |
| 2 | **GoDaddy DNS API — automatización de registros DNS (gestión propia)** | Alto: permite automatizar propagación DNS para dominios Gano propios, facilita scripts de onboarding | Medio (script OTE → prod; manejo de auth `sso-key`) | API Key/Secret; ≥ 10 dominios + Discount Domain Club Domain Pro para Management API completa | Bajo si solo se opera sobre dominios **bajo control propio** | **P1** |
| 3 | **GoDaddy Domains Availability API — verificador de disponibilidad** | Medio: feature de vitrina ("¿Está disponible tu dominio?") o herramienta interna de soporte | Bajo (endpoint GET único, sin modificar plugins WP) | API Key; ≥ 50 dominios en cuenta para acceso completo en prod | Bajo; cuidar rate limit ≤ 60 rpm | **P1** |
| 4 | **GoDaddy API Reseller — subaccounts + `X-Shopper-Id`** | Alto: automatizar creación de subcuentas para clientes; integración futura con billing propio (WHMCS o similar) | Alto (requiere flujo de onboarding, validación, manejo de errores, secretos por cliente) | Cuenta Reseller; Good as Gold si provisiona productos vía API; gestión de secretos robusta | Medio (ToU exige control contractual de cada shopper; no operar cuentas sin relación comercial) | **P2** |
| 5 | **Mercado Pago — pasarela de cobro a clientes finales** | Medio–Alto: alternativa local COP a Reseller checkout; relevante si Gano quiere facturación propia en Colombia | Alto (OAuth 2.0 + PKCE; checkout + webhooks; cumplimiento SIC/DIAN) | Client Id/Secret MP; cuenta de negocio verificada; HTTPS obligatorio; DIAN si factura directamente | Medio–Alto (duplica checkout Reseller; implica responsabilidad de facturación directa, validación SIC) | **P2** |
| 6 | **Mercado Libre Marketplace — sincronización de publicaciones** | Bajo–Medio para hosting: ML no es el canal principal de venta de servicios SaaS/hosting en Colombia | Alto (OAuth 2.0 por cuenta cliente; items API; gestión de órdenes; soporte marketplace ML) | DevCenter CO; cuenta ML del vendedor; OAuth consentimiento explícito por usuario | Medio (modelo de datos ML no se mapea naturalmente a servicios de hosting; overhead operativo alto) | **P3** |
| 7 | **GoDaddy Shopper API — operaciones sobre cuentas de terceros sin consentimiento** | ❌ Ninguno | — | — | **MUY ALTO — viola ToU GoDaddy** | **NO HACER** |
| 8 | **GoDaddy API como pasarela de cobro hacia clientes finales (cobrar en nombre de GoDaddy)** | ❌ Ninguno (prohibido por ToU) | — | — | **MUY ALTO — ToU prohíbe monetizar la API de GoDaddy como producto/pasarela propia** | **NO HACER** |
| 9 | **Credenciales GoDaddy / ML en plugins WordPress o en repositorio** | ❌ Riesgo de seguridad crítico | — | — | **CRÍTICO — viola ToU y políticas de seguridad del proyecto** | **NO HACER** |

---

## Detalle por prioridad

### P0 — Hacer ahora (bloquea Fase 4)

**Reseller Store + RCC**
- Depurar catálogo en RCC (Hosting, VPS, SSL con precios en COP).
- Mapear PFIDs reales en CTAs de `shop-premium.php`.
- Probar flujo de checkout marca blanca (vitrina → carrito GoDaddy → confirmación).
- Referencia: [`memory/commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md) · [`TASKS.md` §Fase 4](../../TASKS.md).

### P1 — Hacer en Fase 4 o inicio de Fase 5

**DNS API (automatización interna)**
- Usar OTE primero (`api.ote-godaddy.com`); luego producción con cuenta con ≥ 10 dominios.
- Credenciales solo en GitHub Actions Secrets o variable de entorno; nunca en código.
- Caso de uso inicial: script de verificación/actualización DNS para onboarding de clientes (complementa [`scripts/check_dns_https_gano.py`](../../scripts/check_dns_https_gano.py)).
- Respetar límite de 60 req/min por endpoint; implementar backoff exponencial.

**Domains Availability API**
- Feature opcional de vitrina: input de búsqueda de dominio que llame al endpoint interno (proxy desde servidor, no desde JS del frontend).
- Requiere ≥ 50 dominios para acceso completo en producción; evaluar si la cuenta actual califica.

### P2 — Evaluar en Fase 5 (billing propio / expansión)

**GoDaddy Subaccounts + X-Shopper-Id**
- Solo viable si Gano Digital crece a modelo de billing automatizado con WHMCS u orquestador propio.
- Exige Good as Gold activado si se aprovisionan productos vía API.
- Prerequisito: proceso legal/contractual claro con cada cliente antes de crear su subaccount.

**Mercado Pago como pasarela alternativa**
- Valor real solo si Gano decide facturar directamente (fuera del modelo Reseller).
- Implica integrar cumplimiento SIC y potencialmente DIAN.
- No priorizar mientras el modelo activo sea Reseller GoDaddy (menor complejidad operativa).
- Referencia técnica: [`sota-apis-mercadolibre-godaddy-2026-04.md` §2.3](sota-apis-mercadolibre-godaddy-2026-04.md).

### P3 — Diferir o descartar

**Mercado Libre Marketplace**
- No es el canal natural para hosting WordPress en Colombia.
- Overhead alto (OAuth por usuario, gestión de órdenes ML, soporte marketplace) vs bajo retorno esperado.
- Revisitar solo si hay demanda explícita de clientes o caso de uso de *cliente de Gano que vende en ML y necesita integración*.

### No hacer

| Iniciativa | Razón concreta |
|-----------|---------------|
| Operar cuentas GoDaddy de terceros sin `X-Shopper-Id` legítimo y control contractual | ToU GoDaddy §alcance de datos: "no acceder a cuentas ajenas" |
| Monetizar la API de GoDaddy como producto/pasarela hacia clientes finales | ToU GoDaddy §monetización: prohibido cobrar por servicios basados directamente en la API |
| Almacenar API Key/Secret en `wp-config.php`, en repo o en issues | Viola políticas de seguridad del proyecto y ToU ambas plataformas |
| Crear panel multi-cuenta de DNS sin control contractual de esas cuentas | ToU GoDaddy: sin autorización explícita por cuenta |
| Usar el flujo de ML Device Grant para operar en nombre de usuarios sin consentimiento explícito | ToU ML: requiere consentimiento OAuth por usuario |

---

## Supuestos y limitaciones

- Los umbrales de cuenta GoDaddy (≥ 10 y ≥ 50 dominios) son los documentados al 2026-04-03 en el Developer Portal; verificar actualizaciones antes de implementar.
- La viabilidad de Mercado Pago asume modelo de facturación directa; si Gano permanece como Reseller puro, permanece en P3.
- Good as Gold en GoDaddy: solo aplica cuando la API *compra* productos; hasta entonces es opcional para uso consultivo.
- Este documento no sustituye revisión legal para DIAN o cumplimiento SIC ante cambio de modelo comercial.

---

## Próximos pasos (follow-up)

- [ ] **Humano:** verificar si la cuenta GoDaddy activa cumple umbrales para Management/Availability API (≥ 10 / ≥ 50 dominios).
- [ ] **Copilot (P1):** extender `scripts/check_dns_https_gano.py` o crear script separado de automatización DNS vía API GoDaddy cuando se decida activar P1.
- [ ] **TASKS.md:** marcar cola `tasks-api-integrations-research.json` como completada una vez que este documento esté en `main` y revisado.
- [ ] Revisar este documento al inicio de cada fase para ajustar prioridades según evolución de cuenta y modelo comercial.

---

*Documento vivo. No incluir credenciales, tokens ni datos sensibles en este archivo.*
