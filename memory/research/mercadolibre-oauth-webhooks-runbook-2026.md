# Runbook: MercadoLibre OAuth 2.0 + PKCE + Notificaciones

**Propósito:** guía operativa para implementadores que integran la API de MercadoLibre (autorización, tokens, webhooks).  
**Audiencia:** desarrolladores back-end y DevOps.  
**Fecha de revisión:** 2026-04-16.  
**Fuentes:** únicamente URLs públicas de [developers.mercadolibre.com.co](https://developers.mercadolibre.com.co) — verificadas en la fecha indicada.

---

## Tabla de contenido

1. [Prerrequisitos](#1-prerrequisitos)
2. [Flujo OAuth 2.0 Authorization Code + PKCE — paso a paso](#2-flujo-oauth-20-authorization-code--pkce--paso-a-paso)
3. [Refresh Token — renovación de sesión](#3-refresh-token--renovación-de-sesión)
4. [Tabla de errores comunes](#4-tabla-de-errores-comunes)
5. [Subsección — Notificaciones (Webhooks)](#5-notificaciones-webhooks)
6. [Checklist pre-producción](#6-checklist-pre-producción)
7. [Riesgos y limitaciones conocidas](#7-riesgos-y-limitaciones-conocidas)
8. [Fuentes](#8-fuentes)

---

## 1. Prerrequisitos

| Elemento | Descripción |
|----------|-------------|
| **Cuenta ML** | Cuenta del titular de la solución; idealmente bajo entidad legal para evitar problemas de transferencia |
| **Aplicación en DevCenter CO** | Crear en [developers.mercadolibre.com.co/devcenter](https://developers.mercadolibre.com.co/devcenter) |
| **Client Id + Secret Key** | Se obtienen al crear la app; tratarlos como secretos de producción |
| **PKCE activado** | Marcar la opción en la configuración de la app (altamente recomendado) |
| **Redirect URI** | HTTPS obligatorio; registrada exactamente como se usará en las llamadas; sin parámetros variables |
| **Scopes** | Solicitar solo los necesarios: `read` (GET) o `write` (PUT/POST/DELETE) según el caso de uso |
| **URL de callback para notificaciones** | HTTPS; accesible públicamente; latencia < 500 ms recomendada |

---

## 2. Flujo OAuth 2.0 Authorization Code + PKCE — paso a paso

> **Contexto:** el flujo server-side (Authorization Code) es el grant type estándar para apps que actúan en nombre de un usuario. PKCE (`code_verifier` / `code_challenge`) añade protección contra interceptación del código de autorización y ataques CSRF.

### Paso 1 — Generar el par PKCE

```
# Pseudocódigo — implementar en el lenguaje del servidor
code_verifier  = base64url( random_bytes(32) )        # 43–128 caracteres URL-safe
code_challenge = base64url( sha256( code_verifier ) )  # method = S256 (obligatorio en lugar de 'plain')
```

- Guardar `code_verifier` en la sesión del servidor; **nunca** enviarlo al cliente ni loguearlo.

### Paso 2 — Construir la URL de autorización

```
https://auth.mercadolibre.com.co/authorization
  ?response_type=code
  &client_id=<CLIENT_ID>
  &redirect_uri=<REDIRECT_URI_EXACTA>
  &state=<TOKEN_ALEATORIO_OPACO>
  &code_challenge=<code_challenge>
  &code_challenge_method=S256
```

Notas:
- Sustituir `auth.mercadolibre.com.co` por el dominio del [site_id](https://api.mercadolibre.com/sites) correspondiente si operás fuera de Colombia.
- `state` debe ser un valor aleatorio seguro (mínimo 16 bytes); vincularlo a la sesión del usuario para detectar CSRF.
- `redirect_uri` debe coincidir carácter a carácter con la registrada en DevCenter.

### Paso 3 — Redirigir al usuario y recibir el callback

El usuario aprueba la app en la UI de ML. ML redirige a:

```
<REDIRECT_URI>?code=<AUTHORIZATION_CODE>&state=<EL_STATE_QUE_ENVIASTE>
```

Acciones obligatorias al recibir el callback:
1. **Verificar `state`**: comparar con el guardado en sesión (comparación segura, constante en tiempo). Si no coincide → rechazar y loguear.
2. Extraer `code` para el siguiente paso.
3. Invalidar el `state` en sesión (uso único).

### Paso 4 — Intercambiar el código por tokens

```http
POST https://api.mercadolibre.com/oauth/token
Content-Type: application/x-www-form-urlencoded

grant_type=authorization_code
&client_id=<CLIENT_ID>
&client_secret=<CLIENT_SECRET>
&code=<AUTHORIZATION_CODE>
&redirect_uri=<REDIRECT_URI_EXACTA>
&code_verifier=<code_verifier>
```

> ⚠️ Los parámetros van en el **body** (form urlencoded), **nunca** como query string.

Respuesta exitosa (`200 OK`):

```json
{
  "access_token": "APP_USR-...",
  "token_type": "bearer",
  "expires_in": 10800,
  "scope": "read write",
  "user_id": 123456789,
  "refresh_token": "TG-..."
}
```

- `expires_in` es en segundos (10 800 s = **3 horas** en el ejemplo de la doc oficial; verificar el valor real en cada respuesta).
- Guardar `access_token` y `refresh_token` encriptados en almacenamiento seguro del servidor.

### Paso 5 — Usar el access token en llamadas API

```http
GET https://api.mercadolibre.com/users/me
Authorization: Bearer <access_token>
```

- Incluir el header `Authorization: Bearer <token>` en todas las llamadas que requieren el contexto del usuario.
- **No loguear tokens completos**; si necesitás trazabilidad, loguear solo los primeros 8 caracteres del prefijo.

---

## 3. Refresh Token — renovación de sesión

El `refresh_token` permite obtener un nuevo `access_token` sin requerir interacción del usuario (flujo offline).

```http
POST https://api.mercadolibre.com/oauth/token
Content-Type: application/x-www-form-urlencoded

grant_type=refresh_token
&client_id=<CLIENT_ID>
&client_secret=<CLIENT_SECRET>
&refresh_token=<REFRESH_TOKEN_ACTUAL>
```

**Reglas críticas del refresh token:**

| Regla | Detalle |
|-------|---------|
| **Un solo uso** | Cada `POST /oauth/token` con `refresh_token` emite un **nuevo** refresh token. El anterior queda inválido inmediatamente. |
| **Guardar el nuevo** | Actualizar el almacenamiento antes de confirmar el éxito; si falla el guardado y el token viejo ya fue consumido → el usuario deberá reautorizarse. |
| **Single-flight** | Si múltiples workers/hilos pueden intentar refresh simultáneamente, usar un lock distribuido para evitar condición de carrera (solo uno hace el POST; los demás esperan). |
| **Inactividad** | Un refresh token se invalida si no se hace **ninguna llamada a `api.mercadolibre.com`** en 4 meses consecutivos. |
| **Invalidación forzada** | El usuario cambia contraseña, revoca permisos desde su cuenta ML, o se rota el Client Secret → todos los tokens activos quedan inválidos. |

---

## 4. Tabla de errores comunes

| Código / Error | Cuándo ocurre | Acción recomendada |
|----------------|---------------|-------------------|
| `invalid_grant` | Código de autorización ya usado o expirado; refresh token inválido o ya consumido; redirect_uri no coincide | Verificar que el `code` se usa una sola vez; comprobar `redirect_uri` exacta; verificar que el refresh token es el más reciente emitido; si persiste, forzar re-autorización del usuario |
| `429` / `local_rate_limited` | Exceso de solicitudes por IP o por app en la ventana de tiempo | Implementar **backoff exponencial con jitter** (ej.: esperar `2^intento` segundos + random); no reintentar de forma inmediata en bucle cerrado; revisar si hay workers duplicados haciendo el mismo refresh |
| `invalid_operator_user_id` | El usuario que autoriza la app **no** es administrador principal de la cuenta ML (es un operador/colaborador sin permisos suficientes), o bien el usuario que autorizó perdió el rol de administrador/operador con posterioridad | Requerir que el propietario/administrador de la cuenta realice la autorización inicial; verificar roles en "Administrar permisos" del DevCenter; si el usuario fue desvinculado, solicitar re-autorización con una cuenta con permisos adecuados |
| `invalid_client` | `client_id` o `client_secret` incorrectos | Verificar que se están usando las credenciales correctas para el país/app; confirmar que no se rotan sin actualizar todos los entornos |
| `invalid_scope` | Se solicitan scopes no habilitados en la configuración de la app | Revisar la configuración de scopes en DevCenter; asegurarse de pedir solo los scopes habilitados |
| `invalid_request` | Parámetros faltantes, mal formados o en query string en lugar de body | Revisar que todos los parámetros obligatorios están en el **body** con `Content-Type: application/x-www-form-urlencoded` |
| `forbidden` (403) | El token no tiene permiso para el recurso solicitado o el scope es insuficiente | Verificar el scope del token vs el requerido por el endpoint; si hace falta, re-autorizar con los scopes correctos |
| `unauthorized_client` | La app no está autorizada para el grant type usado | Revisar la configuración del tipo de app en DevCenter (solo lectura vs lectura/escritura vs offline) |
| `unauthorized_application` | La app está bloqueada por ML | Revisar estado de la app en DevCenter; contactar soporte ML si el bloqueo es inesperado |

---

## 5. Notificaciones (Webhooks)

ML envía notificaciones HTTP a la URL de callback registrada en la app cuando ocurren eventos en los topics suscritos (Orders, Messages, Items, Catalog, Shipments, Promotions, etc.).

### 5.1 Registrar la URL de callback

- Configurar en DevCenter → app → sección **Notificaciones**.
- URL debe ser **HTTPS**, accesible públicamente, con certificado válido.
- Elegir los **topics** relevantes para el caso de uso (no suscribirse a todos si no se van a procesar).

Referencia: [ML — Recibe notificaciones](https://developers.mercadolibre.com.co/es_ar/productos-recibe-notificaciones)

### 5.2 Estructura del payload de notificación

```json
{
  "resource": "/orders/123456789",
  "user_id": 123456789,
  "topic": "orders_v2",
  "application_id": 987654321,
  "attempts": 1,
  "sent": "2026-04-16T10:30:00.000Z",
  "received": "2026-04-16T10:30:00.123Z"
}
```

- El campo `resource` contiene la **ruta del recurso** modificado, no el recurso completo.
- Para obtener los datos actualizados: hacer `GET <resource>` con un token válido.

### 5.3 Validar el origen — obligatorio

> ⚠️ **No confiar en el body sin validar que la notificación proviene de MercadoLibre.**

Pasos de validación:

1. **Verificar IP de origen**: ML documenta rangos de IP desde los que envía notificaciones (consultar documentación oficial actualizada). Si el sistema de firewall lo permite, filtrar por origen.
2. **Header `x-signature`**: algunas versiones del webhook incluyen firma. Consultar la documentación del topic específico para ver si está disponible y cómo validarla.
3. **Verificar `application_id`**: debe coincidir con el Client Id de tu app registrada.
4. **Verificar `user_id`**: debe corresponder a un usuario que haya autorizado tu aplicación.
5. **Re-fetch del recurso**: nunca actuar sobre datos del body directamente — siempre recuperar el recurso mediante `GET <resource>` con token propio. Esto también confirma que el recurso efectivamente cambió.

### 5.4 Idempotencia — implementación obligatoria

ML puede enviar la **misma notificación más de una vez** (reintentos ante timeout o error HTTP). El endpoint debe ser idempotente.

**Patrón recomendado:**

```
Al recibir notificación:
1. Extraer identificador único: combinar topic + resource + sent (o un hash de estos)
2. Consultar tabla/cache de notificaciones procesadas
3. Si ya fue procesada → responder 200 OK sin reejecutar lógica de negocio
4. Si es nueva → procesar, marcar como procesada ANTES de responder, responder 200 OK
```

Consideraciones:
- Usar almacenamiento distribuido (Redis, BD) si hay múltiples instancias del servidor.
- El TTL del registro de idempotencia puede ser 24–72 horas (cubrir ventana de reintentos de ML).

### 5.5 Reintentos de ML — comportamiento esperado

| Situación en el endpoint | Comportamiento de ML |
|--------------------------|---------------------|
| Respuesta `200 OK` en tiempo | Notificación marcada como entregada; no reintenta |
| Timeout (sin respuesta en ~500 ms) | ML reintenta según su política de backoff (número de reintentos y ventana exactos pueden variar; consultar la sección de notificaciones en [developers.mercadolibre.com.co](https://developers.mercadolibre.com.co/es_ar/productos-recibe-notificaciones) para valores actualizados) |
| Respuesta `5xx` | ML reintenta |
| Respuesta `4xx` (400, 401, etc.) | ML puede dejar de reintentar (error permanente desde su perspectiva) |

**Implicaciones para el diseño:**

- El endpoint de notificaciones debe responder **`200 OK` lo más rápido posible** (< 500 ms).
- Procesar la lógica de negocio de forma **asíncrona** (encolar en job queue interna) para no bloquear la respuesta.
- Implementar un sistema de **dead letter queue** para notificaciones que fallen el procesamiento interno.

### 5.6 Flujo completo de recepción

```
1. Llega POST al endpoint HTTPS de callback
2. Verificar que el body es JSON válido
3. Extraer application_id → validar que coincide con tu app
4. Extraer user_id → validar que es un usuario autorizado
5. Generar clave de idempotencia (topic + resource + sent)
6. Verificar en cache/BD si ya fue procesada
   - Si sí → responder 200 OK inmediatamente
7. Encolar en job queue interna para procesamiento asíncrono
8. Responder 200 OK (antes de que el worker termine)
9. [Asíncrono] Worker: GET <resource> con token válido → procesar datos reales → marcar notificación como procesada
```

---

## 6. Checklist pre-producción

### 6.1 Configuración de la aplicación

- [ ] App creada en el **DevCenter del país correcto** (CO para Colombia)
- [ ] Cuenta del propietario de la solución (preferiblemente entidad legal)
- [ ] **PKCE habilitado** en la configuración de la app
- [ ] Redirect URI registrada en HTTPS exacta (sin parámetros variables)
- [ ] Scopes configurados con el **mínimo necesario**
- [ ] URL de callback de notificaciones configurada en HTTPS
- [ ] Topics de notificaciones limitados a los que se van a procesar
- [ ] Client Secret almacenado en secret manager (nunca en código ni en repo)

### 6.2 Seguridad del flujo OAuth

- [ ] `code_verifier` generado con al menos 32 bytes de entropía
- [ ] `code_challenge_method` = `S256` (no usar `plain`)
- [ ] `state` aleatorio y vinculado a la sesión del usuario (verificado en callback)
- [ ] Parámetros del POST `/oauth/token` enviados en body, no en query string
- [ ] `access_token` y `refresh_token` almacenados encriptados
- [ ] Tokens **nunca logueados** completos en logs de aplicación
- [ ] Lock distribuido implementado para el flujo de refresh (evitar single-flight race condition)

### 6.3 Manejo de tokens y sesión

- [ ] Lógica de renovación automática antes del vencimiento (`expires_in - margen`)
- [ ] Almacenamiento siempre actualizado con el último refresh token emitido
- [ ] Flujo de re-autorización implementado para cuando el refresh token es inválido
- [ ] Alerta operacional configurada cuando la re-autorización falla repetidamente

### 6.4 Endpoint de notificaciones

- [ ] Endpoint responde `200 OK` en < 500 ms
- [ ] Validación de `application_id` implementada
- [ ] Validación de `user_id` implementada
- [ ] Idempotencia implementada (no procesar la misma notificación dos veces)
- [ ] Procesamiento asíncrono (job queue) separado de la respuesta HTTP
- [ ] Dead letter queue para notificaciones con errores de procesamiento
- [ ] Logs de notificaciones recibidas (sin datos sensibles)

### 6.5 Operaciones y monitoreo

- [ ] Alertas de tasa de errores `429` (backoff exponencial configurado)
- [ ] Alertas de `invalid_grant` repetidos (posible problema de sincronización de tokens)
- [ ] Rotación del Client Secret planificada con **ventana de transición** (ML permite hasta 7 días con dos secretos activos)
- [ ] Procedimiento documentado para invalidación forzada de tokens (cambio de contraseña de usuario)
- [ ] Acceso a DevCenter mapeado al rol correspondiente del equipo

### 6.6 Pruebas antes de ir a producción

- [ ] Flujo completo OAuth + PKCE probado en entorno de staging
- [ ] Refresh token probado en escenarios de expiración y uso único
- [ ] Endpoint de notificaciones probado con payloads simulados (curl/Postman)
- [ ] Prueba de idempotencia: enviar la misma notificación dos veces → verificar que se procesa solo una vez
- [ ] Prueba de reintentos: simular timeout en el endpoint → verificar el comportamiento de ML
- [ ] Prueba de error `429`: verificar que el backoff exponencial funciona correctamente

---

## 7. Riesgos y limitaciones conocidas

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| Race condition en refresh de token | Tokens inválidos para múltiples usuarios simultáneos | Lock distribuido + single-flight |
| Pérdida del refresh token más reciente | Usuarios deben re-autorizar | Transacción atómica: actualizar BD antes de confirmar; backup del token anterior durante la transición |
| Inactividad de 4 meses | Todos los tokens del usuario se invalidan | Monitorear usuarios inactivos; notificar antes de la ventana de invalidación |
| ML reintenta notificaciones → procesamiento duplicado | Acciones de negocio ejecutadas dos veces | Idempotencia obligatoria en el endpoint |
| Client Secret expirado sin ventana de transición | Interrupción del servicio para todos los usuarios | Planificar rotación con antelación usando "Programar renovación" (hasta 7 días) |
| Operador sin permisos de administrador (`invalid_operator_user_id`) | El usuario no puede autorizar la app | Documentar en el onboarding que solo el propietario/admin puede hacer la autorización inicial |
| URL de callback de notificaciones caída | Notificaciones perdidas (ML puede dejar de reintentar) | Monitorear disponibilidad del endpoint; implementar queue con replay desde la API de ML si el topic lo permite |

---

## 8. Fuentes

> Todas las URLs consultadas son de dominio público oficial de MercadoLibre Developers. Fecha de consulta: **2026-04-16**.

- [ML Developers — API Docs (Colombia)](https://developers.mercadolibre.com.co/es_ar/api-docs-es)
- [ML Developers — Crear una aplicación](https://developers.mercadolibre.com.co/es_ar/crea-una-aplicacion-en-mercado-libre-es)
- [ML Developers — Autenticación y autorización (OAuth 2.0 + PKCE)](https://developers.mercadolibre.com.co/es_ar/autenticacion-y-autorizacion)
- [ML Developers — Recomendaciones de autorización y token](https://developers.mercadolibre.com.co/es_ar/recomendaciones-de-autorizacion-y-token)
- [ML Developers — Usuarios y aplicaciones (Recursos Cross)](https://developers.mercadolibre.com.co/es_ar/usuarios-y-aplicaciones)
- [ML Developers — Recibe notificaciones](https://developers.mercadolibre.com.co/es_ar/productos-recibe-notificaciones)
- [ML API Sites — Lista de países y site_id](https://api.mercadolibre.com/sites)

---

## Relacionados en este repo

- [`memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) — investigación SOTA de portales ML y GoDaddy; contexto de uso para Gano Digital.

---

*Documento operativo. Actualizar ante cambios en la documentación oficial de ML Developers o ante hallazgos en implementaciones reales. No incluir credenciales, tokens ni datos de usuarios en este archivo.*
