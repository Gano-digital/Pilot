# GoDaddy API Reseller — Patrones Operativos y Límites Legales

**Ámbito:** operación de la API de GoDaddy bajo el modelo **Reseller** de Gano Digital.  
**Fecha de referencia:** 2026-04-16.  
**Fuente oficial:** [GoDaddy Developer Portal — Get Started](https://developer.godaddy.com/getstarted) · [Documentación interactiva](https://developer.godaddy.com/doc) · [Reseller Control Center](https://reseller.godaddy.com/).

> **Complemento de:** [`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md) (investigación inicial, §3) · [`fase4-plataforma.md`](fase4-plataforma.md) (ver [§ Alineación Fase 4](#alineacion-fase4) al final).

---

## 1. Contexto operativo

Gano Digital opera como **GoDaddy Reseller**: los clientes finales compran servicios de hosting/dominio a través del **Reseller Store (carrito marca blanca)** y el **Reseller Control Center (RCC)**. Ese flujo comercial **no depende** de la Developer API REST.

La Developer API (`api.godaddy.com`) es un canal **complementario y opcional** que permite:

- Automatizar tareas de back-office (DNS, disponibilidad de dominios, gestión de subcuentas).
- Integrar flujos externos (scripts, WHMCS, n8n) sin intervención manual en el portal.
- Consultar estado de productos para conciliación o herramientas de soporte.

**Regla de oro:** si el Reseller Store + RCC cubren la operación, no es necesario añadir dependencias REST. Usar la API solo cuando aporte eficiencia real y no duplique fuentes de verdad.

---

## 2. Obligaciones ToU — resumen operativo (parafraseado)

> El texto completo y vinculante está en los [Términos de Uso de GoDaddy](https://www.godaddy.com/legal/agreements/developer-terms-of-service). Lo que sigue es una **paráfrasis operativa** para facilitar la toma de decisiones; no es asesoría legal.

### 2.1 Rate limit

- Máximo **60 solicitudes por minuto por endpoint**.
- Prohibido usar técnicas de evasión de rate limit (múltiples IPs, rotación de claves solo para eludir el límite).
- Estrategia: implementar **backoff exponencial** ante respuestas `429`.

### 2.2 Titularidad de las claves

- Cada clave API corresponde al **cliente GoDaddy** que la generó, únicamente para **su** cuenta o las subcuentas (`X-Shopper-Id`) que administra bajo el modelo Reseller.
- Prohibido: crear claves en nombre de terceros, ofrecer "portales mágicos" que generen claves para otros, ni exponer las credenciales en front-end o repositorios.

### 2.3 Alcance de los datos

- Solo se pueden leer/modificar/transferir dominios y productos **bajo control contractual directo** del titular de la clave.
- Prohibido acceder a cuentas ajenas, incluso si el cliente comparte sus credenciales voluntariamente fuera del flujo `X-Shopper-Id` documentado.

### 2.4 Monetización de la API

- Prohibido cobrar o poner detrás de un paywall servicios que "envuelvan" la API de GoDaddy como producto.
- Permitido: usar la API internamente para automatizar operaciones del propio negocio de reventa.

### 2.5 Marca y afiliación

- No sugerir afiliación, endorsement ni patrocinio de GoDaddy en herramientas de terceros.
- No usar el nombre/logo de GoDaddy sin autorización explícita.

### 2.6 Estabilidad y suspensión

- GoDaddy puede modificar o suspender la API **sin aviso previo**.
- Las claves pueden ser revocadas ante incumplimiento de ToU.
- Diseñar integraciones con **degradación elegante**: si la API no responde, el flujo Reseller Store sigue funcionando.

---

## 3. Flujo de onboarding: OTE → Producción → Good as Gold

```
[1. Generar clave en portal]
        │
        ▼
[2. Ambiente OTE (test)]          endpoint: https://api.ote-godaddy.com
    └─ Probar endpoints sin afectar datos reales
    └─ Validar autenticación, rate limits y estructura de respuestas
        │
        ▼
[3. Generar clave de producción]  endpoint: https://api.godaddy.com
    └─ Nueva clave distinta (no reutilizar la de OTE)
    └─ Almacenar en GitHub Actions secrets (GODADDY_API_KEY / GODADDY_API_SECRET)
        │
        ▼
[4. Verificar elegibilidad de endpoints]
    ├─ Availability API → requiere ≥ 50 dominios en la cuenta
    └─ Management / DNS APIs → requiere ≥ 10 dominios y/o plan Discount Domain Club Pro
        │
        ▼
[5. ¿El flujo debe COMPRAR productos vía API?]
    ├─ NO → continuar sin Good as Gold (consultas, DNS, subcuentas)
    └─ SÍ → activar Good as Gold (saldo prepago GoDaddy que la API puede debitar)
```

### Notas sobre cada paso

| Paso | Detalle |
|------|---------|
| **OTE** | Las llamadas usan `https://api.ote-godaddy.com` con la clave de prueba. No afecta datos reales. Ideal para validar scripts o integraciones antes de producción. |
| **Producción** | Generar una nueva clave en [developer.godaddy.com/keys](https://developer.godaddy.com/keys). El header de autenticación es `Authorization: sso-key [KEY]:[SECRET]`. |
| **Elegibilidad** | Los umbrales de dominios son conocidos al momento de redacción (abr 2026); pueden cambiar. Verificar siempre en [Get Started](https://developer.godaddy.com/getstarted) antes de diseñar un flujo. |
| **Good as Gold** | Solo necesario si la API debe registrar/renovar/transferir dominios u otros productos que debiten fondos. Para Gano Digital, el checkout de vitrina usa Reseller Store; Good as Gold aplica solo a flujos de aprovisionamiento automático vía REST. |

---

## 4. Matriz: acción API / requisito previo / riesgo

| Acción vía API | Ambiente | Requisito previo | Riesgo si se omite el requisito |
|---------------|----------|-----------------|--------------------------------|
| Verificar disponibilidad de dominio | OTE / Producción | ≥ 50 dominios en cuenta (producción) | Error 403 / acceso denegado al endpoint |
| Consultar DNS de un dominio propio | Producción | ≥ 10 dominios y/o Domain Pro | Endpoint no disponible; degradar a consulta manual en RCC |
| Actualizar registros DNS | Producción | Mismo que consulta + dominio bajo control de la cuenta | Modificación accidental de DNS de otro titular |
| Crear subcuenta de cliente (shopper) | Producción | Modelo Reseller activo + `X-Shopper-Id` en header | Llamada tratada como Self-Serve; subcuenta no creada |
| Registrar un dominio vía API | Producción | Good as Gold (saldo suficiente) + elegibilidad | Fallo de pago / dominio no registrado; cliente queda sin servicio |
| Renovar un dominio vía API | Producción | Good as Gold + dominio en la cuenta | Expiración inadvertida si Good as Gold sin fondos |
| Consultar estado de productos del shopper | Producción | Reseller + `X-Shopper-Id` correcto | Datos vacíos o error 401 (clave sin permisos Reseller) |
| Pruebas de integración (scripts, CI) | OTE | Clave OTE separada | Riesgo de afectar datos reales si se usa clave de producción por error |
| Automatizar conciliación de pedidos | Producción | Clave con alcance correcto; logging seguro | Fuga de datos si se loguean respuestas con PII del shopper |

---

## 5. Secretos y gobernanza

- Las claves **NUNCA** van en el repositorio, variables de entorno de front-end, ni en issues/PRs.
- Almacenamiento canónico: **GitHub Actions Secrets** (`GODADDY_API_KEY` / `GODADDY_API_SECRET`) o secret manager externo.
- Rotación: planificar rotación periódica (trimestral recomendado); actualizar todos los workflows antes de desactivar la clave antigua.
- Logging: nunca incluir el par Key/Secret en logs; si hay trazas de error, sanitizar antes de guardar.
- Separación OTE/Producción: usar variables de entorno distintas para cada ambiente y nunca mezclarlas.

---

## 6. Integración con el modelo Reseller de Gano Digital

### Flujo canónico (sin API REST)

```
Cliente final → Reseller Store (carrito marca blanca) → RCC → GoDaddy backend
```

Este flujo **no requiere** claves API, Good as Gold ni configuración adicional de la Developer API.

### Flujo complementario (con API REST)

```
Script/WHMCS/n8n → api.godaddy.com (X-Shopper-Id) → Subcuenta del cliente
```

Casos de uso válidos:

- Verificar disponibilidad de dominios en tiempo real desde una herramienta interna.
- Consultar/actualizar DNS de dominios administrados por Gano.
- Conciliar pedidos del RCC contra el sistema de billing (WHMCS u otro).
- Aprovisionar dominios automáticamente en flujos avanzados (requiere Good as Gold).

### Reglas de coexistencia

1. **No duplicar fuentes de verdad**: RCC es la fuente de verdad de pedidos y clientes; la API solo lee/escribe sobre ella.
2. **Degradación elegante**: si la API falla, el operador debe poder intervenir manualmente en RCC sin interrupción del servicio.
3. **Plugins WordPress**: las credenciales de la API no deben vivir en plugins ni en el tema; usar scripts de back-office o GitHub Actions.

---

## <a name="alineacion-fase4"></a>7. Alineación con Fase 4 — enlaces sin duplicar contenido

La investigación técnica de Fase 4 (billing, DIAN, portal, dominios, VPS, soporte) se documenta en:

- **[`fase4-plataforma.md`](fase4-plataforma.md)** — Referencia principal: WHMCS, ESTUPENDO, FreeScout, Upptime, ResellerClub, Hetzner, WhatsApp API. Costos y plan de implementación por semanas.
- **[`sota-apis-mercadolibre-godaddy-2026-04.md`](sota-apis-mercadolibre-godaddy-2026-04.md)** — Investigación SOTA completa: portales ML y GoDaddy, Self-Serve vs Reseller, Good as Gold, matriz de capacidades (§3 y §4).

### Puntos de conexión con este documento

| Tema en Fase 4 | Conexión con este doc |
|---------------|----------------------|
| WHMCS + módulo Reseller / ResellerClub | Si se integra WHMCS con GoDaddy vía API, aplicar matriz §4 para DNS y dominios |
| Aprovisionamiento VPS (Hetzner/DO) | API GoDaddy no aplica a VPS externos; usar APIs de cada proveedor |
| Portal cliente (`my.gano.digital`) | Datos de subcuentas/shoppers consultables vía API si se usa modelo `X-Shopper-Id` |
| Conciliación de pedidos | API útil para confirmar estado de dominios/productos vendidos en Reseller Store |
| Secrets en CI/CD | Ver §5 de este doc + [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) §1.3 |

---

## 8. Riesgos y limitaciones conocidas

| Riesgo | Impacto | Mitigación |
|--------|---------|------------|
| GoDaddy suspende/modifica la API sin aviso | Integración rota; operación manual | Degradación elegante; no hacer crítica la API para el flujo de ventas |
| Clave de producción usada en tests | Datos reales modificados accidentalmente | Claves OTE y producción en variables de entorno separadas |
| Rate limit excedido (>60 rpm) | Bloqueo temporal de llamadas | Backoff exponencial; cachear respuestas cuando sea posible |
| Shopper sin autorización Reseller | Llamadas fallan con 401/403 | Verificar modelo Reseller activo antes de implementar `X-Shopper-Id` |
| Good as Gold sin fondos suficientes | Registro/renovación de dominio falla | Monitorear saldo; alertas automáticas antes de que llegue a cero |
| Logs con PII del shopper | Exposición de datos personales | Sanitizar respuestas API antes de escribir en logs |

---

## 9. Próximos pasos (follow-up)

Estos ítems quedan fuera del alcance de este documento y se sugieren como tareas futuras:

- [ ] Mapear endpoints específicos de la API interactiva ([`developer.godaddy.com/doc`](https://developer.godaddy.com/doc)) relevantes para Gano (DNS, shoppers, dominios).
- [ ] Evaluar si WHMCS + módulo ResellerClub reemplaza o complementa la API GoDaddy para gestión de dominios.
- [ ] Definir política de rotación de claves API y automatizarla en GitHub Actions.
- [ ] Probar flujo OTE con script de verificación de disponibilidad de dominio.
- [ ] Revisar umbrales de elegibilidad (≥50 / ≥10 dominios) cuando la cuenta Reseller crezca.

---

*Documento operativo interno — no incluye credenciales ni datos sensibles. Paráfrasis de ToU: no sustituye asesoría legal. Última revisión: 2026-04-16.*
