# Investigación SOTA: portales de desarrolladores Mercado Libre y GoDaddy

**Ámbito:** documentación pública accesible desde los hubs oficiales ([Mercado Libre API Docs — Colombia](https://developers.mercadolibre.com.co/es_ar/api-docs-es), [GoDaddy Developer Portal](https://developer.godaddy.com/)).  
**Fecha de captura:** 2026-04-03.  
**Uso previsto:** referencia interna para **herramientas opcionales** (automatización, consultas, dominios) sin sustituir el roadmap actual de Gano Digital (**WordPress + GoDaddy Reseller Store + RCC**). Las APIs REST del [Developer Portal](https://developer.godaddy.com/) **no** sustituyen el carrito ni exigen cambios en plugins: solo complementan (scripts, back-office, futuros sistemas como WHMCS) cuando haga falta.

---

## 1. Resumen ejecutivo

| Plataforma | Qué ofrece el portal | Implicación para un proveedor de hosting WP en Colombia |
|------------|----------------------|---------------------------------------------------------|
| **Mercado Libre** | Ecosistema de APIs por unidad de negocio (marketplace, envíos, **Mercado Pago**, etc.), OAuth 2.0, aplicaciones en DevCenter por país, notificaciones push a URL propia | Relevante si Gano u un cliente vende en MELI/MCO y quiere sincronizar publicaciones, órdenes o mensajes; **Mercado Pago** es un eje distinto al “core hosting”. |
| **GoDaddy** | APIs para operar productos GoDaddy (dominios, DNS, shoppers, etc.), claves API, entorno **OTE** vs **producción**, modelo **Reseller** con `X-Shopper-Id` | Alineado con la realidad **Reseller**: automatización de DNS/dominios **solo** sobre cuentas bajo control directo; límites estrictos de uso comercial y rate limit globales. |

Ninguno de los dos portales sustituye un panel de billing tipo WHMCS/Blesta; ambos exigen **gobernanza de secretos** (Client Secret, API Key/Secret) y cumplimiento de términos.

---

## 2. Mercado Libre Developers (`developers.mercadolibre.com.co`)

### 2.1 Estructura del hub [API Docs](https://developers.mercadolibre.com.co/es_ar/api-docs-es)

La página índice organiza la documentación en:

1. **Documentación por unidad de negocio** — accesos temáticos (logos/animaciones en UI) hacia:
   - Mercado Libre (marketplace core)
   - Global selling
   - Mercado Envíos
   - Mercado Pago
2. **Recursos generales** — travesía transversal:
   - **Gestión de aplicaciones** (crear app, credenciales, notificaciones)
   - **Recursos Cross** (usuarios, aplicaciones, patrones comunes)
   - **Seguridad** (autorización, tokens, buenas prácticas)

Esta arquitectura implica que **no hay un único manual lineal**: el integrador debe elegir unidad de negocio + leer cross-cutting security.

### 2.2 Gestión de aplicaciones — [Crea una aplicación](https://developers.mercadolibre.com.co/es_ar/crea-una-aplicacion-en-mercado-libre-es)

Puntos operativos documentados (actualización citada en página: 16/01/2026):

- **DevCenter por país:** Colombia → [developers.mercadolibre.com.co/devcenter](https://developers.mercadolibre.com.co/devcenter/). Otros sitios (AR, BR, CL, MX, UY, PE, EC, VE) tienen URL propia; el `site_id` y dominios de auth deben coincidir con el país de operación.
- **Credenciales:** tras crear la app se obtienen **Client Id** y **Secret Key** para autenticación API.
- **Cuenta:** se recomienda cuenta del **propietario** de la solución y, idealmente, bajo **entidad legal** (evitar problemas de transferencia).
- **Validación de titular:** en AR, MX, BR y CL puede ser obligatoria la validación de datos del titular antes de crear aplicaciones.
- **Redirect URI:** **obligatorio HTTPS** en la URI de redirección.
- **PKCE:** opcional en configuración pero **recomendado** (mitiga inyección de código de autorización y CSRF).
- **Device Grant:** flujo para token con **solo credenciales de app** para recursos propios (no en nombre de usuario), con polling hasta autorización o timeout.
- **Scopes funcionales:**
  - **Lectura:** métodos **GET** HTTPS.
  - **Escritura:** **PUT, POST, DELETE** HTTPS.
  - Enlace a permisos funcionales detallados (documentación .ar en la cadena de enlaces).
- **Notificaciones (callbacks):** topics principales listados — *Orders, Messages, Items, Catalog, Shipments, Promotions*; URL de callback obligatoria y alineada a [productos / notificaciones](https://developers.mercadolibre.com.ar/es_ar/productos-recibe-notificaciones).
- **Renovación de Client Secret:** flujos **Renovar ahora** vs **Programar renovación** (hasta 7 días, granularidad 30 min); durante la ventana pueden coexistir dos secretos; impacto en usuarios que autorizan durante la rotación.
- **Tipos de app por scope:** solo lectura; online lectura/escritura; offline lectura/escritura (**refresh token** + actuar sin usuario en línea).
- **Administrar permisos:** listado de usuarios que otorgaron permiso; estados (nuevo, inactivo por uso, activo).
- **Eliminación:** borrar aplicación es **irreversible**.

### 2.3 Autenticación y autorización — [flujo OAuth 2.0](https://developers.mercadolibre.com.co/es_ar/autenticacion-y-autorizacion)

Síntesis técnica (actualización citada: 29/12/2025):

- **Bearer token** en header `Authorization` en **todas** las llamadas que correspondan.
- **Grant type:** Authorization Code **server-side**.
- **URL de autorización** usa host por país; ejemplo documentado `auth.mercadolibre.com.ar` — debe sustituirse TLD/país según [sites](https://api.mercadolibre.com/sites).
- **redirect_uri:** debe ser **idéntica** a la registrada; **sin información variable** en la URL; datos opacos van en **`state`** (recomendado: valor aleatorio seguro).
- **PKCE:** si la app lo tiene habilitado, `code_challenge` y `code_challenge_method` (**S256** preferido vs `plain`) son obligatorios en authorize; `code_verifier` en el POST de token.
- **POST `/oauth/token`:** parámetros en **body** (form urlencoded), no como query string.
- **Respuesta token típica:** `access_token`, `token_type: bearer`, `expires_in` (ej. **10800 s ≈ 6 h** en ejemplo), `scope`, `user_id`, `refresh_token` (si aplica offline).
- **Refresh:** `grant_type=refresh_token`; el **refresh token es de un solo uso** — cada refresh devuelve un **nuevo** refresh; solo válido el **último** emitido.
- **Invalidación anticipada del access token:** cambio de contraseña, rotación de Client Secret, revocación por usuario, **inactividad 4 meses** sin llamadas a `api.mercadolibre.com`.
- **Operadores/colaboradores:** si el usuario no es administrador principal, el grant puede fallar con `invalid_operator_user_id`.
- **Errores documentados:** `invalid_client`, `invalid_grant`, `invalid_scope`, `invalid_request`, `forbidden` (403), `local_rate_limited` (**429** — reintentar), `unauthorized_client`, `unauthorized_application` (app bloqueada).

### 2.4 Seguridad de tokens — [Recomendaciones](https://developers.mercadolibre.com.co/es_ar/recomendaciones-de-autorizacion-y-token)

- POST token con datos en **body** (ejemplo `curl` oficial).
- Uso de **`state`** para correlacionar la respuesta con la sesión iniciada.
- **Validar origen** de notificaciones antes de confiar en payloads.
- Incluir **access token** en todas las peticiones que correspondan al usuario.

### 2.5 Recursos Cross — [Usuarios y aplicaciones](https://developers.mercadolibre.com.co/es_ar/usuarios-y-aplicaciones)

La guía consolida ejemplos `curl` contra `api.mercadolibre.com` para:

- `GET /users/{user_id}` y `GET /users/me` (perfil).
- `PUT /users/{id}` (actualización de datos — requiere scope adecuado).
- Direcciones, métodos de pago aceptados, y otros recursos enlazados a guías por producto (“Leer más” hacia *producto-consulta-usuarios*, *direcciones-del-usuario*, etc.).

**Nota SOTA:** los ejemplos usan `Bearer $ACCESS_TOKEN`; la forma del token en documentación de auth (`APP_USR-...`) confirma prefijos distintivos para trazabilidad en logs (nunca loguear tokens completos en producción).

### 2.6 Implicancias para Colombia (MCO)

- Usar **hub .com.co** y **DevCenter CO** para coherencia legal/operativa; los ejemplos de `auth.*` en docs a veces usan **.com.ar** como ilustración — sustituir por el dominio de auth del **site** objetivo.
- Integraciones de **Mercado Pago** suelen vivir bajo la unidad de negocio homónima en el mismo portal agregador; revisar scopes y URLs de API específicas de MP (no duplicadas en profundidad en este informe base).

---

## 3. GoDaddy Developer Portal (`developer.godaddy.com`)

### 3.1 Superficie pública

Desde [developer.godaddy.com](https://developer.godaddy.com/):

- **Get Started** — términos, límites, setup ([getstarted](https://developer.godaddy.com/getstarted))
- **API Keys** — generación y gestión ([keys](https://developer.godaddy.com/keys))
- **Documentación interactiva** — [doc](https://developer.godaddy.com/doc) (referencia por endpoint; la UI dinámica no se reproduce íntegramente en este markdown estático)

### 3.2 Términos de uso — extracto operativo ([Get Started](https://developer.godaddy.com/getstarted))

Elementos críticos para un integrador “SOTA”:

- **Rate limit:** hasta **60 solicitudes por minuto por endpoint** (no rodear ni generar carga desproporcionada).
- **Claves API:** solo el **cliente GoDaddy** para **su** cuenta; prohibido crear claves en nombre de terceros o exponer portales de terceros que generen claves.
- **Alcance de datos:** no registrar/transferir/renovar/modificar dominios o productos **fuera del control directo** del usuario; no acceder a cuentas ajenas.
- **Monetización:** no cobrar ni poner detrás de paywall servicios basados en la API de GoDaddy (cláusula restrictiva para marketplaces de “API como producto”).
- **Propiedad intelectual:** no implicar afiliación/endorsement con marca GoDaddy en conectores de terceros.
- **Cambios y suspensión:** derecho a modificar/suspender API **sin aviso previo**; revocación de claves ante incumplimiento.
- **Disclaimer:** uso bajo propio riesgo; limitación amplia de responsabilidad.
- **Resellers:** si ya eres API Reseller, el onboarding puede estar resuelto vía [Reseller Control Center](https://reseller.godaddy.com/).

### 3.3 Autenticación y ambientes

- Primera clave creada = **test** → usar **`https://api.ote-godaddy.com`** (OTE).
- Producción: nueva clave + **`https://api.godaddy.com`**.
- Header documentado: `Authorization: sso-key [API_KEY]:[API_SECRET]`
- Ejemplo público: `GET /v1/domains/available?domain=example.guru`

### 3.4 Self-Serve vs Reseller

- **Self-Serve:** operás tu cuenta; ignorar subcuentas y header `X-Shopper-Id`.
- **Reseller:** operás en nombre de clientes; crear **Subaccount** y enviar **`X-Shopper-Id`** en las llamadas.
- **customerId / shopper:** endpoints bajo documentación `shoppers` (v1 GET documentado en portal).

### 3.5 Dominios — requisitos de elegibilidad (producción)

Según Get Started, parte de la **Domains API** en producción puede exigir:

- **Availability API:** cuentas con **≥ 50 dominios**.
- **Management y DNS APIs:** **≥ 10 dominios** y/o plan activo **Discount Domain Club – Domain Pro**.

Implica que cuentas “pequeñas” pueden **no** tener acceso completo a ciertas rutas hasta cumplir umbrales comerciales.

### 3.6 Pagos y facturación vía API

- **Good as Gold:** según [Get Started](https://developer.godaddy.com/getstarted), hace falta **cuando la API debe completar compras** (p. ej. registrar un dominio): la API descuenta tarifas fijas de esa cuenta prepago. **No** es requisito para “tener claves” ni para todo uso de herramientas: si solo exploras, consultas o integras flujos que **no** debitan productos vía API, puede **omitirse** hasta que ese flujo exista.
- **Sin pasarela:** la API **no** provee procesador para cobrar a *tus* clientes finales — debes integrar cobro propio (coherente con modelo **API Reseller**; el **checkout de vitrina** sigue siendo Reseller Store + RCC cuando no automatizas la compra vía REST).

### 3.7 Alineación con el proyecto Gano Digital (2026-04)

| Camino | Rol |
|--------|-----|
| **Reseller Store + RCC + `gano-reseller-enhancements`** | **Canónico** para catálogo, CTAs y checkout marca blanca. Sin dependencia de Good as Gold en ese flujo. |
| **Developer API (Key/Secret, OTE / prod)** | **Opcional**: soporte interno, conciliación, automatización futura, integración con billing aparte. **Redundante** si RCC + informes cubren la operación; **evitar** duplicar fuentes de verdad sin proceso. |
| **Plugins WordPress** | **No** es necesario modificarlos para “usar” las APIs: credenciales y llamadas deben vivir **fuera del front** (servidor propio, scripts, n8n, WHMCS, etc.). |

---

## 4. Matriz rápida: capacidad vs plataforma

| Necesidad | Mercado Libre / MP | GoDaddy API |
|-----------|-------------------|-------------|
| Publicar/vender en marketplace | Sí (items, órdenes, mensajes, promos — según unidad) | No |
| Cobro online (checkout propio) | MP como pasarela (unidad Mercado Pago) | No (Good as Gold es saldo GoDaddy) |
| Verificar disponibilidad dominio | No | Sí (con límites de cuenta) |
| Gestionar DNS de dominios en GoDaddy | No | Sí (sujeto a elegibilidad) |
| Operar como Reseller con subclientes | N/A (modelo distinto) | Sí (`X-Shopper-Id`, subaccounts) |
| Webhooks / eventos | Sí (topics ML + callback URL HTTPS) | Depende del producto/endpoint (ver doc interactiva) |

---

## 5. Riesgos y prácticas SOTA (checklist interno)

1. **Secretos:** Client Secret ML y Key/Secret GoDaddy solo en **secret manager** / GitHub Actions secrets — nunca en repo ni en issues.
2. **Rotación:** planificar rotación de Client Secret ML con ventana programada; actualizar todos los entornos antes de expirar el viejo.
3. **OAuth ML:** validar **`state`**; usar PKCE en apps nuevas; **refresh token** single-flight para evitar condiciones de carrera.
4. **429 / rate limits:** backoff exponencial en ML (`local_rate_limited`) y **≤60 rpm** por endpoint en GoDaddy.
5. **Términos GoDaddy:** no ofrecer “panel mágico” de DNS multi-cuenta sin control contractual de esas cuentas.
6. **Tráfico de notificaciones ML:** verificar origen antes de actuar; no confiar en body sin validación.

---

## 6. Continuación (agentes / siguiente sesión)

Se creó cola versionada **`.github/agent-queue/tasks-api-integrations-research.json`** para profundizar: inventario de endpoints GoDaddy desde `developer.godaddy.com/doc`, mapa de recursos ML por caso de uso, matriz de oportunidades Gano, y anexo de higiene de secretos. Sembrar con workflow **08** y `queue_file: tasks-api-integrations-research.json`.

**Mapa navegable ML (site MCO):** disponible en [`mercadolibre-api-resource-map-2026.md`](mercadolibre-api-resource-map-2026.md) — tabla por área/URL/métodos/caso de uso, anti-patterns para WP hosting, y referencias cruzadas. Generado 2026-04-16.

---

## 7. Fuentes (URLs consultadas)

- [Mercado Libre — API Docs (CO, es_ar)](https://developers.mercadolibre.com.co/es_ar/api-docs-es)
- [ML — Crea una aplicación](https://developers.mercadolibre.com.co/es_ar/crea-una-aplicacion-en-mercado-libre-es)
- [ML — Autenticación y autorización](https://developers.mercadolibre.com.co/es_ar/autenticacion-y-autorizacion)
- [ML — Recomendaciones de autorización y token](https://developers.mercadolibre.com.co/es_ar/recomendaciones-de-autorizacion-y-token)
- [ML — Usuarios y aplicaciones (Recursos Cross)](https://developers.mercadolibre.com.co/es_ar/usuarios-y-aplicaciones)
- [GoDaddy — Developer Portal](https://developer.godaddy.com/)
- [GoDaddy — Get Started / Terms & setup](https://developer.godaddy.com/getstarted)
- [GoDaddy — Documentation (referencia interactiva)](https://developer.godaddy.com/doc)

---

*Documento vivo: ampliar con hallazgos de los agentes sin duplicar credenciales ni datos personales reales.*
