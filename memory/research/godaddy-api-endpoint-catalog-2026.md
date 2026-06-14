# Catálogo de familias de endpoints — GoDaddy Developer API

**Ámbito:** inventario estructurado de las familias de endpoints disponibles en [`developer.godaddy.com/doc`](https://developer.godaddy.com/doc).  
**Fecha de captura:** 2026-04-16.  
**Uso previsto:** referencia interna para automatización, soporte y herramientas complementarias. **No** sustituye el flujo canónico **GoDaddy Reseller Store + RCC** para catálogo y checkout de vitrina gano.digital.  
**Documento base:** [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) (§3 — GoDaddy).

---

## 1. Límites globales y restricciones de uso (Get Started)

> Fuente: [`developer.godaddy.com/getstarted`](https://developer.godaddy.com/getstarted)

| Parámetro | Valor / Regla |
|-----------|---------------|
| **Rate limit** | **60 solicitudes por minuto por endpoint** (no rodear ni generar carga desproporcionada) |
| **Ambientes** | OTE (test): `https://api.ote-godaddy.com` · Producción: `https://api.godaddy.com` |
| **Autenticación** | Header `Authorization: sso-key [API_KEY]:[API_SECRET]` en todas las llamadas |
| **Primera clave** | Siempre se crea como clave de **test** (OTE); para producción se crea una clave separada |
| **Titularidad** | Solo el **propietario** de la cuenta GoDaddy puede crear claves API para esa cuenta; prohibido generar claves en nombre de terceros |
| **Datos accesibles** | Únicamente productos y cuentas bajo **control directo** del integrador autenticado |
| **Monetización** | Prohibido cobrar o poner detrás de paywall servicios basados en esta API sin acuerdo explícito con GoDaddy |
| **Estabilidad** | GoDaddy puede modificar o suspender endpoints **sin aviso previo**; no depender de rutas no documentadas |

### 1.1 Restricciones de elegibilidad — Domains API en producción

| Endpoint / grupo | Umbral mínimo requerido |
|------------------|------------------------|
| **Availability API** (`/v1/domains/available`) | ≥ **50 dominios** en la cuenta |
| **Management y DNS APIs** | ≥ **10 dominios** en la cuenta **y/o** plan activo **Discount Domain Club – Domain Pro** |

> Cuentas que no cumplan estos umbrales recibirán error de elegibilidad al intentar usar esos endpoints en producción.

### 1.2 Good as Gold (compras vía API)

- **Solo se necesita** cuando la API debe **completar una compra** que debita productos (p. ej. registrar un dominio).
- **No es requisito** para consultas, gestión DNS, o flujos que no debiten productos.
- El carrito de vitrina gano.digital opera vía **Reseller Store + RCC**: ese flujo **no depende** de Good as Gold.

---

## 2. Self-Serve vs Reseller

| Dimensión | Self-Serve | Reseller |
|-----------|-----------|---------|
| **Actúa en nombre de** | Su propia cuenta GoDaddy | Subcuentas de clientes |
| **Header `X-Shopper-Id`** | No se usa | **Obligatorio** en llamadas sobre subaccounts |
| **Crear subaccounts** | N/A | `POST /v1/shoppers/subaccount` |
| **Onboarding** | API Keys propias en developer.godaddy.com/keys | Puede estar integrado vía [Reseller Control Center](https://reseller.godaddy.com/) |
| **Caso de uso Gano Digital** | Gestión interna (DNS propio, dominios propios) | Soporte/automatización sobre clientes del Reseller |

> **Nota para Gano:** el `customerId` (UUIDv4) que usan algunos endpoints (p. ej. Certificates v2) se obtiene de `GET /v1/shoppers/{shopperId}?includes=customerId`.

---

## 3. Inventario de familias de endpoints

> Fuente de referencia: [`developer.godaddy.com/doc`](https://developer.godaddy.com/doc) — captura 2026-04.

### 3.1 Domains

**Propósito:** registrar, transferir, renovar, actualizar y cancelar dominios; gestionar contactos y privacidad.

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/domains` | Listar dominios en la cuenta |
| `POST` | `/v1/domains/purchase` | Registrar un dominio |
| `GET` | `/v1/domains/available` | Verificar disponibilidad de un dominio _(requiere ≥ 50 dominios en prod)_ |
| `POST` | `/v1/domains/available` | Verificar disponibilidad en lote |
| `GET` | `/v1/domains/{domain}` | Obtener detalles de un dominio |
| `PATCH` | `/v1/domains/{domain}` | Actualizar configuración (nameservers, auto-renew, etc.) |
| `DELETE` | `/v1/domains/{domain}` | Cancelar/eliminar dominio |
| `POST` | `/v1/domains/{domain}/renew` | Renovar dominio |
| `POST` | `/v1/domains/{domain}/transfer` | Iniciar transferencia entrante |
| `GET` | `/v1/domains/{domain}/privacy` | Estado de privacidad WHOIS |
| `POST` | `/v1/domains/{domain}/privacy/purchase` | Activar privacidad WHOIS |
| `DELETE` | `/v1/domains/{domain}/privacy` | Desactivar privacidad WHOIS |
| `GET` | `/v1/domains/{domain}/contacts` | Obtener contactos (registrant, admin, tech, billing) |
| `PATCH` | `/v1/domains/{domain}/contacts` | Actualizar contactos |
| `GET` | `/v1/domains/tlds` | Listar TLDs disponibles |
| `GET` | `/v1/domains/{domain}/agreements` | Obtener acuerdos legales para acciones sobre el dominio |
| `GET` | `/v1/domains/suggest` | Sugerencias de nombres de dominio |

**Self-Serve vs Reseller:** en contexto Reseller, incluir `X-Shopper-Id: {shopperId}` para operar sobre dominios de subaccounts.

---

### 3.2 DNS (Records — incluido en Domains)

**Propósito:** gestión granular de registros DNS para dominios bajo la cuenta. _(Requiere ≥ 10 dominios o plan Domain Pro en producción)._

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/domains/{domain}/records` | Listar todos los registros DNS |
| `PUT` | `/v1/domains/{domain}/records` | Reemplazar **todos** los registros DNS |
| `PATCH` | `/v1/domains/{domain}/records` | Añadir/modificar registros (merge) |
| `GET` | `/v1/domains/{domain}/records/{type}` | Listar registros por tipo (A, AAAA, CNAME, MX, TXT, etc.) |
| `PUT` | `/v1/domains/{domain}/records/{type}` | Reemplazar todos los registros de un tipo |
| `GET` | `/v1/domains/{domain}/records/{type}/{name}` | Registros por tipo y nombre |
| `PUT` | `/v1/domains/{domain}/records/{type}/{name}` | Reemplazar registros por tipo y nombre |
| `DELETE` | `/v1/domains/{domain}/records/{type}/{name}` | Eliminar registros por tipo y nombre |

**Tipos de registro soportados:** `A`, `AAAA`, `CNAME`, `MX`, `NS`, `SOA`, `SRV`, `TXT`, `CAA`, `DS`, `SSHFP`.

**Self-Serve vs Reseller:** igual que Domains; `X-Shopper-Id` para subaccounts.

---

### 3.3 Shoppers

**Propósito:** gestión de perfiles de clientes/subaccounts (Reseller) y recuperación del `customerId`.

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/shoppers/{shopperId}` | Obtener perfil del shopper |
| `GET` | `/v1/shoppers/{shopperId}?includes=customerId` | Obtener perfil **+ customerId** (UUID requerido para Certificates v2) |
| `POST` | `/v1/shoppers/subaccount` | Crear subaccount de cliente _(Reseller)_ |
| `PATCH` | `/v1/shoppers/{shopperId}` | Actualizar datos del shopper |
| `DELETE` | `/v1/shoppers/{shopperId}` | Eliminar cuenta del shopper |
| `POST` | `/v1/shoppers/{shopperId}/factors/pin` | Gestión de PIN / factor de autenticación |

**Self-Serve vs Reseller:**
- Self-Serve: `GET /v1/shoppers/{myShopperId}` para datos propios.
- Reseller: `POST /v1/shoppers/subaccount` para crear clientes; `X-Shopper-Id` en todas las llamadas subsiguientes.

---

### 3.4 Orders

**Propósito:** realizar y consultar pedidos/transacciones comerciales (requiere Good as Gold para compras que debitan cuenta).

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `POST` | `/v1/orders` | Crear un nuevo pedido |
| `GET` | `/v1/orders` | Listar pedidos |
| `GET` | `/v1/orders/{orderId}` | Obtener detalles de un pedido |

**Self-Serve vs Reseller:** con `X-Shopper-Id` se realizan pedidos en nombre del subaccount indicado.

> ⚠️ **Advertencia:** este endpoint debita productos de la cuenta prepago (Good as Gold). No usar en producción sin pruebas previas en OTE.

---

### 3.5 Certificates

**Propósito:** emitir, renovar, revocar y descargar certificados SSL/TLS.

| Versión | Método | Ruta | Descripción breve |
|---------|--------|------|-------------------|
| v1 | `GET` | `/v1/certificates` | Listar certificados _(deprecado para flujos nuevos)_ |
| **v2** | `POST` | `/v2/customers/{customerId}/certificates` | Crear/emitir certificado _(requiere `customerId` UUID de Shoppers API)_ |
| **v2** | `GET` | `/v2/customers/{customerId}/certificates` | Listar certificados del cliente |
| **v2** | `GET` | `/v2/customers/{customerId}/certificates/{certificateId}` | Detalle de un certificado |
| **v2** | `POST` | `/v2/customers/{customerId}/certificates/{certificateId}/renew` | Renovar certificado |
| **v2** | `POST` | `/v2/customers/{customerId}/certificates/{certificateId}/reissue` | Reemitir certificado |
| **v2** | `POST` | `/v2/customers/{customerId}/certificates/{certificateId}/revoke` | Revocar certificado |
| **v2** | `GET` | `/v2/customers/{customerId}/certificates/{certificateId}/download` | Descargar certificado |
| **v2** | `GET` | `/v2/customers/{customerId}/certificates/{certificateId}/actions` | Ver acciones disponibles |

**Prerequisito:** obtener el `customerId` (UUIDv4) con `GET /v1/shoppers/{shopperId}?includes=customerId` antes de usar rutas v2.

**Self-Serve vs Reseller:** `customerId` del shopper propio en Self-Serve; `customerId` del subaccount + `X-Shopper-Id` en Reseller.

---

### 3.6 Subscriptions

**Propósito:** consultar y gestionar suscripciones activas (planes, renovaciones).

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/subscriptions` | Listar suscripciones activas |
| `GET` | `/v1/subscriptions/{subscriptionId}` | Detalle de una suscripción |
| `PATCH` | `/v1/subscriptions/{subscriptionId}` | Actualizar configuración de suscripción |
| `DELETE` | `/v1/subscriptions/{subscriptionId}` | Cancelar suscripción |
| `GET` | `/v1/subscriptions/productGroups` | Listar grupos de productos disponibles |

**Self-Serve vs Reseller:** con `X-Shopper-Id` para consultar/gestionar suscripciones de subaccounts.

---

### 3.7 Agreements

**Propósito:** recuperar textos legales y acuerdos de términos de servicio requeridos antes de ciertas compras o cambios.

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/agreements` | Obtener textos de acuerdos para las claves (`keys`) proporcionadas |

**Nota:** este endpoint se llama típicamente como paso previo a `POST /v1/domains/purchase` para presentar los ToS al usuario final.

---

### 3.8 Abuse

**Propósito:** reporte y gestión de tickets de abuso asociados a dominios o productos.

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/abuse/tickets` | Listar tickets de abuso |
| `POST` | `/v1/abuse/tickets` | Crear ticket de abuso |
| `GET` | `/v1/abuse/tickets/{ticketId}` | Detalle de un ticket |

---

### 3.9 Aftermarket

**Propósito:** gestión de dominios en el mercado secundario (compraventa de dominios ya registrados).

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `DELETE` | `/v1/aftermarket/listings/{domain}` | Retirar un dominio de la venta en el aftermarket |
| `POST` | `/v1/aftermarket/listings/auctions` | Crear subasta de un dominio |
| `POST` | `/v1/aftermarket/listings/buyNow` | Crear listado de venta directa (Buy Now) |

---

### 3.10 Auctions

**Propósito:** participar o consultar subastas activas de dominios en el aftermarket GoDaddy.

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/auctions` | Listar subastas disponibles |
| `GET` | `/v1/auctions/{auctionId}` | Detalle de una subasta |
| `GET` | `/v1/auctions/{auctionId}/bids` | Ver pujas de una subasta |
| `POST` | `/v1/auctions/{auctionId}/bids` | Realizar una puja |

---

### 3.11 Countries

**Propósito:** obtener datos de referencia de países, estados y provincias (útil para formularios de contacto de dominios).

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/countries` | Listar países disponibles |
| `GET` | `/v1/countries/{countryKey}` | Detalle de un país (estados, provincias) |

---

### 3.12 Parking

**Propósito:** métricas de dominios en parking (monetización de tráfico mientras el dominio está sin uso activo).

| Método | Ruta | Descripción breve |
|--------|------|-------------------|
| `GET` | `/v1/customers/{customerId}/parking/metrics` | Métricas agregadas de dominios en parking |
| `GET` | `/v1/customers/{customerId}/parking/metricsByDomain` | Métricas desglosadas por dominio |

---

## 4. Resumen de familias por caso de uso (Gano Digital)

| Familia | Relevancia para Gano Digital | Self-Serve | Reseller (`X-Shopper-Id`) |
|---------|------------------------------|:----------:|:-------------------------:|
| **Domains** | Alta — automatización de registros DNS y gestión de dominios de clientes | ✅ | ✅ |
| **DNS** | Alta — actualizar TXT/CNAME sin panel (scripts, CI/CD) | ✅ | ✅ |
| **Shoppers** | Media — obtener `customerId`; crear subaccounts si se amplía el modelo Reseller | ✅ | ✅ (subaccounts) |
| **Orders** | Baja-media — solo si se automatiza compra de dominios vía API + Good as Gold | ✅ | ✅ |
| **Certificates** | Media — gestión SSL programática para clientes (v2 recomendado) | ✅ | ✅ |
| **Subscriptions** | Baja — consultar estado de planes de hosting activos | ✅ | ✅ |
| **Agreements** | Media — paso previo obligatorio a purchases vía API | ✅ | — |
| **Abuse** | Baja — solo si se procesan reportes de abuso de dominio | ✅ | — |
| **Aftermarket / Auctions** | Muy baja — mercado secundario, fuera del scope actual | ✅ | — |
| **Countries** | Baja — dato de referencia para formularios | ✅ | — |
| **Parking** | Muy baja — métricas de parking, no es línea de negocio actual | — | ✅ (customerId) |

---

## 5. Checklist de integración segura (recordatorio SOTA)

- [ ] **Secretos fuera del repo:** `API_KEY` y `API_SECRET` solo en GitHub Actions secrets, variables de entorno del servidor, o secret manager. Nunca en código, commits, ni issues.
- [ ] **Ambiente OTE primero:** probar todos los flujos nuevos en `api.ote-godaddy.com` antes de cambiar a producción.
- [ ] **Rate limit:** implementar backoff exponencial al recibir `HTTP 429`; no superar 60 req/min por endpoint.
- [ ] **`X-Shopper-Id`:** incluir en todas las llamadas sobre subaccounts si se opera en modo Reseller.
- [ ] **`customerId` para Certificates v2:** obtener con `GET /v1/shoppers/{shopperId}?includes=customerId` antes de llamar a rutas v2.
- [ ] **Acuerdos legales:** llamar `GET /v1/agreements` y mostrar los ToS al usuario antes de ejecutar `POST /v1/domains/purchase`.
- [ ] **Elegibilidad en producción:** verificar que la cuenta supera los umbrales (50/10 dominios) antes de depender de Availability/Management/DNS APIs en producción.
- [ ] **Good as Gold:** confirmar saldo suficiente antes de llamadas a `POST /v1/orders` o `POST /v1/domains/purchase` en producción.
- [ ] **Plugins WordPress:** las credenciales y llamadas deben vivir fuera del front-end (servidor propio, scripts, n8n, WHMCS) — no embeberlas en código PHP del tema ni plugins públicos.

---

## 6. Relación con el modelo comercial canónico

```
gano.digital (vitrina)
    └── GoDaddy Reseller Store + RCC        ← CANÓNICO para catálogo, CTAs y checkout
          │
          └── Developer API (REST)          ← COMPLEMENTARIO / OPCIONAL
                ├── Domains/DNS             → automatización interna, soporte técnico
                ├── Shoppers                → gestión subaccounts Reseller
                ├── Certificates            → gestión SSL programática
                └── Subscriptions           → conciliación de planes activos
```

La **API Developer** no sustituye el carrito ni modifica el flujo de compra del cliente final. Los cambios a `shop-premium.php`, CTAs y Reseller Store siguen siendo independientes de este catálogo.

---

## 7. Fuentes y referencias

- [GoDaddy — Developer Portal](https://developer.godaddy.com/) — portal principal
- [GoDaddy — Get Started / Terms & setup](https://developer.godaddy.com/getstarted) — rate limits, elegibilidad, Good as Gold
- [GoDaddy — API Reference (interactive)](https://developer.godaddy.com/doc) — referencia interactiva por endpoint
- [GoDaddy — API Keys](https://developer.godaddy.com/keys) — generación y gestión de claves
- [GoDaddy — Reseller Control Center](https://reseller.godaddy.com/) — panel Reseller (onboarding puede estar integrado aquí)
- Documento base: [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) — §3 GoDaddy (contexto ToU, OTE/prod, Self-Serve vs Reseller)

---

*Documento de referencia interna. Actualizar en próxima revisión si GoDaddy modifica familias de endpoints o umbrales de elegibilidad. No incluir API keys ni secretos reales en este archivo.*
