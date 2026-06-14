# Mapa de Recursos API — Mercado Libre (site MCO — Colombia)

**Ámbito:** mapa navegable desde el hub [API Docs CO](https://developers.mercadolibre.com.co/es_ar/api-docs-es) hacia guías concretas, orientado al **site MCO** (Colombia).  
**Fecha de captura:** 2026-04-16.  
**Documento base:** [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) — leer primero para contexto OAuth, credenciales y alineación con Gano Digital.

> **Uso previsto:** referencia interna para decisiones de integración. No implica compromiso de implementación en el roadmap actual (vitrina Gano + GoDaddy Reseller sigue siendo canónico). Todo dato de autenticación usa **placeholders** — nunca pegar Client Id, Client Secret ni tokens reales en este documento.

---

## 1. Tabla de recursos por área (site MCO)

| Área | URL de documentación oficial | Métodos típicos | Cuándo usarlo (caso de uso) |
|------|-----------------------------|-----------------|-----------------------------|
| **Gestión de aplicaciones** | [Crea una aplicación](https://developers.mercadolibre.com.co/es_ar/crea-una-aplicacion-en-mercado-libre-es) | — (configuración DevCenter, no endpoint REST) | Crear y configurar la app MCO antes de cualquier integración; registrar Redirect URI HTTPS y habilitar scopes. |
| **Autenticación OAuth 2.0** | [Autenticación y autorización](https://developers.mercadolibre.com.co/es_ar/autenticacion-y-autorizacion) | `GET /authorization` (redirect), `POST /oauth/token` | Obtener `access_token` (lectura/escritura) y `refresh_token` (offline). Obligatorio antes de cualquier llamada autenticada. |
| **Usuarios / Perfil** | [Usuarios y aplicaciones (Recursos Cross)](https://developers.mercadolibre.com.co/es_ar/usuarios-y-aplicaciones) | `GET /users/{user_id}`, `GET /users/me`, `PUT /users/{user_id}` | Identificar al vendedor autenticado; leer o actualizar datos de perfil y direcciones. |
| **Ítems (publicaciones)** | [Gestión de ítems](https://developers.mercadolibre.com.co/es_ar/gestiona-tus-publicaciones) | `GET /items/{item_id}`, `POST /items`, `PUT /items/{item_id}`, `DELETE /items/{item_id}` | Crear, leer, actualizar o pausar/cerrar publicaciones en MCO. Requiere scope `write`. |
| **Descripciones de ítems** | [Descripción de publicaciones](https://developers.mercadolibre.com.co/es_ar/gestiona-tus-publicaciones) | `GET /items/{item_id}/description`, `PUT /items/{item_id}/description` | Gestionar el cuerpo de texto HTML de la descripción larga de un ítem. |
| **Catálogo** | [Catálogo (Catalog)](https://developers.mercadolibre.com.co/es_ar/catalogo) | `GET /catalog/products/{catalog_product_id}`, `GET /catalog/search` | Adherir publicaciones al catálogo de ML (productos canónicos); consultar restricciones de atributos por categoría. |
| **Órdenes** | [Gestión de órdenes](https://developers.mercadolibre.com.co/es_ar/gestiona-tus-ordenes) | `GET /orders/{order_id}`, `GET /orders/search`, `PUT /orders/{order_id}/feedback` | Leer órdenes de compra, filtrarlas por fecha/estado, registrar feedback de transacción. |
| **Mensajes** | [Mensajería (Messages)](https://developers.mercadolibre.com.co/es_ar/mensajeria) | `GET /messages/action_guide/{pack_id}`, `POST /messages/action_guide/{pack_id}`, `GET /messages/{message_id}` | Leer y enviar mensajes de postventa entre comprador y vendedor asociados a una orden o pack. |
| **Envíos (Mercado Envíos)** | [Mercado Envíos](https://developers.mercadolibre.com.co/es_ar/mercado-envios) | `GET /shipments/{shipment_id}`, `GET /shipment_labels`, `PUT /shipments/{shipment_id}` | Consultar estado del envío, descargar etiquetas PDF y actualizar datos logísticos. Solo disponible si el vendedor opera con ME. |
| **Promociones** | [Gestión de promociones](https://developers.mercadolibre.com.co/es_ar/gestion-de-promociones) | `GET /seller-promotions/{seller_id}`, `POST /seller-promotions`, `PUT /seller-promotions/{id}`, `DELETE /seller-promotions/{id}` | Crear y administrar descuentos, ofertas del día y campañas de precio para publicaciones propias. |
| **Mercado Pago (checkout externo)** | [Mercado Pago Developers](https://www.mercadopago.com.co/developers/es/docs) | `POST /checkout/preferences`, `GET /payments/{payment_id}` (via MP API) | Procesar cobros a compradores fuera del flujo de ML marketplace (ej. tienda propia). **API separada** con credenciales MP distintas. |
| **Notificaciones (webhooks)** | [Notificaciones](https://developers.mercadolibre.com.co/es_ar/recibe-notificaciones) | Callback HTTP `POST` entrante (no es un endpoint que tú consumes, sino que recibes) | Recibir eventos en tiempo real sobre: `orders`, `items`, `messages`, `shipments`, `catalog_product_ids`, `promotions`. Requiere endpoint HTTPS propio. |
| **Recursos Cross — aplicaciones** | [Usuarios y aplicaciones](https://developers.mercadolibre.com.co/es_ar/usuarios-y-aplicaciones) | `GET /applications/{app_id}` | Consultar metadata de la propia aplicación registrada (scopes activos, callback URL, etc.). |
| **Categorías y atributos** | [Categorías](https://developers.mercadolibre.com.co/es_ar/categorias-y-atributos) | `GET /categories/{category_id}`, `GET /categories/{category_id}/attributes`, `GET /sites/MCO/categories` | Explorar el árbol de categorías de ML Colombia; obtener atributos obligatorios/opcionales por categoría antes de crear un ítem. |
| **Monedas y sitios** | [Sites y monedas](https://api.mercadolibre.com/sites) (API pública sin auth) | `GET /sites`, `GET /sites/MCO`, `GET /currencies/COP` | Verificar `site_id=MCO`, moneda `COP` y dominio de autenticación correcto antes de implementar. |

> **Nota sobre URLs de auth:** la documentación frecuentemente ejemplifica con `auth.mercadolibre.com.ar`. Para Colombia usar `auth.mercadolibre.com.co` (verificar en `GET /sites/MCO` el campo `domain`).

---

## 2. Flujo de integración mínimo (site MCO)

```
1. Crear app en DevCenter CO → obtener CLIENT_ID y CLIENT_SECRET (guardar en secret manager, nunca en repo)
2. Habilitar scopes (lectura / escritura / offline) según caso de uso
3. Registrar Redirect URI HTTPS y URL de notificaciones HTTPS
4. Flujo Authorization Code (+ PKCE si la app lo permite):
   - Redirigir usuario a: https://auth.mercadolibre.com.co/authorization
       ?response_type=code
       &client_id=<CLIENT_ID_PLACEHOLDER>
       &redirect_uri=<REDIRECT_URI_PLACEHOLDER>
       &state=<RANDOM_SECURE_STATE>
       [&code_challenge=<PKCE_CHALLENGE>&code_challenge_method=S256]
   - Intercambiar code por token: POST https://api.mercadolibre.com/oauth/token
5. Almacenar access_token (expira ~6 h) y refresh_token (single-use, offline apps)
6. Llamadas autenticadas: Authorization: Bearer <ACCESS_TOKEN_PLACEHOLDER>
7. Refrescar token antes de expirar; invalidar refresh tras uso
```

---

## 3. Anti-patterns — qué no intentar desde un hosting WP genérico

Estos patrones son problemáticos en el contexto de un **sitio WordPress alojado en GoDaddy Managed WordPress** (como gano.digital), donde el front es WordPress + Elementor sin servidor dedicado de procesos en background.

| Anti-pattern | Por qué es problemático | Alternativa |
|---|---|---|
| **Guardar `access_token` o `refresh_token` en `wp_options`** sin cifrado | WordPress en entorno compartido puede tener acceso a la BD desde plugins con vulnerabilidades; un token filtrado permite operar en nombre del vendedor hasta que expire o se revoque. | Usar variables de entorno del servidor (o equivalente en cPanel), secret manager externo, o capa de backend separada (n8n, serverless). |
| **Poner `CLIENT_ID` y `CLIENT_SECRET` directamente en `functions.php` o en un plugin de WP** | Quedan expuestos si el repo o el servidor se comprometen; TruffleHog y CodeQL los detectarán en CI. | GitHub Actions Secrets + llamada de API desde un workflow o servicio externo; nunca en código PHP del tema/plugin. |
| **Ejecutar polling continuo de órdenes desde `wp-cron`** | `wp-cron` no es un cron real: solo dispara cuando hay tráfico. Órdenes nuevas pueden tardar horas en procesarse; además puede saturar el límite de peticiones PHP en hosting compartido. | Cron real en servidor (cPanel cron jobs) o webhook de notificaciones ML apuntando a un endpoint HTTPS propio. |
| **Procesar webhooks de ML en un endpoint WordPress sin validación de origen** | Cualquier cliente HTTP puede enviar un POST falso; sin verificación firma/IP, la lógica interna puede dispararse con datos falsos. | Validar cabeceras de notificación ML, verificar la orden con un `GET /orders/{id}` antes de actuar, usar nonces o secretos de callback registrados. |
| **Manejar el flujo OAuth completo desde JavaScript en el navegador (front-end WP)** | Expone `CLIENT_SECRET` en el cliente; Authorization Code flow debe ser **server-side**. | Implementar el intercambio de código en PHP server-side (o backend externo); el navegador solo redirige al proveedor de auth. |
| **Sincronizar catálogo completo de ML en una sola petición de página WP** | La carga de cientos de ítems vía API en tiempo de renderizado aumenta el TTFB, puede agotar el límite de memoria PHP (128 MB típico en hosting), y satura el rate limit ML (429). | Paginación + cache (WP transients o Redis si disponible); sincronización diferida en cron/background. |
| **Usar credenciales del dueño de la cuenta para integraciones de clientes de hosting** | Rompe el modelo de permisos: el access token tendrá scope total sobre la cuenta del propietario. | Cada cliente/integrador autoriza su propia app con su propia cuenta ML; PKCE + offline app por perfil. |
| **No planificar la rotación de `CLIENT_SECRET`** | El DevCenter permite renovación con ventana de 7 días; si no se hace proactivamente, hay riesgo de caída de integraciones productivas. | Crear `CALENDAR_EVENT` o issue recurrente para revisar expiración; usar la opción "Programar renovación". |

---

## 4. Sección cruzada — documentos relacionados

| Documento | Relevancia |
|-----------|-----------|
| [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) | **Informe base**: estructura OAuth, credenciales ML, alineación con Gano Digital (GoDaddy Reseller como canónico), riesgos y checklist SOTA. Leer antes de este mapa. |
| [`fase4-plataforma.md`](fase4-plataforma.md) | Investigación Fase 4 de Gano Digital: billing, WHMCS, portal de cliente. ML/MP aparece como alternativa de cobro; verificar alineación con Reseller GoDaddy antes de iterar. |
| [`TASKS.md`](../../TASKS.md) | Estado del proyecto: Fase 4 activa, checkout canónico = GoDaddy Reseller Store + RCC. Cualquier integración ML debe documentarse como "complementaria / follow-up" hasta decisión explícita. |

---

## 5. Alcance de site MCO — notas específicas

- **`site_id = MCO`** → Colombia; confirmar con `GET https://api.mercadolibre.com/sites/MCO`.
- **Moneda:** `COP` (pesos colombianos); usar en atributos de precio de ítems.
- **Dominio de auth:** `auth.mercadolibre.com.co` (no `.com.ar` como muestran muchos ejemplos de docs).
- **Validación de titular:** puede requerirse para crear aplicaciones en Colombia; seguir flujo del DevCenter CO.
- **Categorías MCO:** el árbol difiere del de AR o MX; siempre consultar `GET /sites/MCO/categories` antes de mapear categorías.
- **Envíos:** Mercado Envíos disponible en MCO; confirmar operatividad con el vendedor antes de implementar lógica de etiquetas.
- **Mercado Pago MCO:** las credenciales MP son **independientes** de las de ML marketplace; acceder desde [Mercado Pago Developers CO](https://www.mercadopago.com.co/developers/es/docs).

---

## 6. Riesgos y supuestos

1. Los links de documentación apuntan a URLs públicas del portal ML Colombia (actualizadas a 2026-04-16). ML puede reestructurar su documentación sin aviso.
2. Este mapa **no** implica que Gano Digital implemente estas integraciones: el checkout canónico sigue siendo GoDaddy Reseller Store.
3. Cualquier implementación requiere app registrada en DevCenter MCO con credenciales propias (nunca reutilizar las de otra cuenta o entorno).
4. Los métodos HTTP listados son los más comunes según documentación pública; consultar la referencia interactiva de cada endpoint para parámetros exactos, filtros y versiones.

---

*Documento vivo — ampliar con hallazgos de agentes sin duplicar credenciales ni datos reales.*
