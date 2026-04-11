# Reporte de sesión — Handoff para Claude (GoDaddy API, Reseller, WHMCS)

**Fecha:** 2026-04-02  
**Contexto:** Continuidad comercial/técnica — Diego es **revendedor API** GoDaddy (claves creadas); vitrina **gano.digital** sigue el modelo **Reseller Store + RCC**; documentación del repo **realineada** hoy.

---

## 1. Resumen ejecutivo

| Tema | Conclusión |
|------|------------|
| **Checkout y negocio principal** | Sigue siendo **GoDaddy Reseller Store + Reseller Control Center (RCC)** + plugin **`gano-reseller-enhancements`**. No depende de la API REST del Developer Portal. |
| **Developer API (REST)** | **Herramienta opcional**: automatización, consultas, integraciones **fuera** del núcleo WordPress (scripts, back-office, futuro billing). **No** sustituye el carrito ni obliga a tocar plugins. |
| **Good as Gold** | Según [Get Started](https://developer.godaddy.com/getstarted), hace falta cuando la API **compra** productos que debitan la cuenta prepago. **No** es requisito solo por tener claves o por uso consultivo — **puede posponerse** hasta que exista ese flujo. |
| **Llave en mano vs API (tabla oficial)** | [Diferencias de planes](https://www.godaddy.com/es/help/en-que-se-diferencian-los-planes-de-revendedor-api-y-de-revendedor-llave-en-mano-7940): en llave en mano **no** está el paquete de API de revendedor para comprar/administrar dominios/cuentas como en plan API; el **programa reseller** público ([programa-reseller](https://www.godaddy.com/es/programa-reseller)) describe tienda, márgenes y soporte. Diego está en **API reseller** — encaje con `X-Shopper-Id`, subcuentas, y eventual Good as Gold para compras API. |
| **WHMCS** | [whmcs.com](https://www.whmcs.com/): billing/automatización **paralela** al storefront Reseller; **WHMCS Cloud** reduce operación de servidor; **no** sustituye la vitrina actual. Ayuda GoDaddy: WHMCS **no** compatible con “sitio de servicio completo” del reseller básico/pro ([planes básico/profesional](https://www.godaddy.com/es/help/que-son-los-planes-de-revendedor-basico-y-profesional-5798)). |

---

## 2. Aprendizajes técnicos (APIs GoDaddy)

### 2.1 Familias de endpoints revisados en conversación

Documentación base: [developer.godaddy.com/doc](https://developer.godaddy.com/doc) — cada producto enlaza su Swagger; bases **OTE** `https://api.ote-godaddy.com` y **prod** `https://api.godaddy.com`.

| API | Utilidad práctica (resumida) |
|-----|------------------------------|
| **Shoppers** | Identidad de cliente en GoDaddy; subcuentas; soporte/CRM. |
| **Orders** | Pedidos, conciliación, herramientas internas. |
| **Subscriptions** | Renovaciones, estado recurrente. |
| **Agreements** | Términos/registrador en flujos por API. |
| **Certificates** | Ciclo de vida SSL del catálogo. |
| **Aftermarket** | Mercado secundario / premium. |
| **ANS** | Identidad de agentes (IA); **no** core hosting — baja prioridad salvo producto explícito. |

### 2.2 Get Started — puntos obligatorios

Fuente: [developer.godaddy.com/getstarted](https://developer.godaddy.com/getstarted)

- Primera clave → **OTE**; producción → **nueva** clave + `api.godaddy.com`.
- Auth: `Authorization: sso-key API_KEY:API_SECRET`.
- **Reseller:** header **`X-Shopper-Id`** del cliente (subcuenta).
- **Rate limit:** 60 solicitudes/minuto por endpoint.
- **Domains API (prod):** posibles restricciones por volumen de dominios / DDC (ver doc).
- **ToU:** no operar fuera de control directo; cláusula estricta sobre monetización de la API — alinear con **términos del programa API Reseller** si el modelo lo requiere.

### 2.3 Error 404 en el navegador

Abrir solo `https://api.ote-godaddy.com` (sin path) devuelve **`NOT_FOUND`**: es normal; hace falta **path completo del Swagger** + herramienta que envíe **`Authorization`** (curl, Postman, no el navegador solo).

---

## 3. Aprendizajes de negocio (“oro” nativo)

- **Llave en mano:** tienda lista, pagos, informes RCC, soporte white-label al cliente — **escala sin API**.
- **API reseller:** automatización y **tu** facturación al cliente (pasarela propia); **certificación** previa a prod ([qué es revendedor API](https://www.godaddy.com/es/help/que-es-un-plan-de-revendedor-api-5939)).
- **50 ideas** de implementación (sesión anterior) fueron **regeneradas** con matriz **LM vs API**; quedó obsoleto asumir API para quien solo tiene llave en mano.

---

## 4. Qué se hizo hoy en el repo (documentación)

| Archivo | Cambio |
|---------|--------|
| `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md` | §3.6 Good as Gold acotado; **§3.7** alineación proyecto (Store/RCC vs API vs plugins). |
| `memory/projects/gano-digital.md` | Bloque Developer API opcional + Good as Gold. |
| `CLAUDE.md` | Término **Developer API (GoDaddy)** en tabla. |
| `TASKS.md` | Fase 4: checkout = carrito Reseller, API REST opcional, Good as Gold condicional. |
| `.cursor/memory/techContext.md` | Fila APIs REST opcionales. |
| `.github/copilot-instructions.md` | Misma línea maestra para agentes. |
| `memory/research/sota-operativo-2026-04.md` | Puntero a §3.7. |
| `memory/commerce/rcc-pfid-checklist.md` | Nota: PFIDs = RCC; API no requerida para mapeo. |

**No** se modificaron plugins de WordPress ni lógica de checkout.

---

## 5. Solucionado / cerrado (hoy)

- Aclaración **Good as Gold** vs “solo herramientas”.
- Aclaración **404** en raíz del host API.
- **Realineación** documental: Reseller Store canónico vs Developer API complementario.
- Marco **WHMCS** como expansión futura, no conflicto directo con vitrina actual.

---

## 6. Pendiente de implementar (priorizado para Claude)

### 6.1 Operativo / humano (no código en repo)

- [ ] Validar en **OTE** un endpoint concreto (path del Swagger) con `curl` o Postman antes de usar prod en automatización.
- [ ] **Good as Gold:** solo cuando se definan **compras** vía API que debiten cuenta; financiar según volumen.
- [ ] **Fase 4 Reseller** según `TASKS.md`: depurar catálogo RCC, mapear PFIDs en CTAs (`shop-premium.php` / checklist `memory/commerce/rcc-pfid-checklist.md`), smoke test checkout.
- [ ] DNS/SSL pendientes humanos: `memory/ops/`, script `scripts/check_dns_https_gano.py`.

### 6.2 Técnico opcional (solo si hay decisión explícita)

- [ ] Servicio **server-side** (Node/PHP) que llame a GoDaddy API con secretos en env — **sin** meter Key/Secret en WordPress público.
- [ ] Integración **WHMCS** o **WHMCS Cloud** en staging cuando Fase 4 “plataforma real” avance; ver `memory/research/fase4-plataforma.md`.
- [ ] Módulos registrador: [WHMCS GoDaddy](https://docs.whmcs.com/domains/domain-registrar-modules/godaddy), [ResellerClub](https://docs.whmcs.com/domains/domain-registrar-modules/resellerclub) — referencia, no tarea inmediata si el modelo sigue siendo solo Reseller Store.

### 6.3 Investigación / agentes (cola existente)

- [ ] Cola `.github/agent-queue/tasks-api-integrations-research.json` — profundizar informe SOTA sin credenciales en repo.
- [ ] Prompts en `.github/prompts/copilot-bulk-assign.md` (bloque API).

---

## 7. Recursos que Claude debe leer primero

| Prioridad | Ruta o URL |
|-----------|------------|
| Alta | `CLAUDE.md` |
| Alta | `TASKS.md` (Fase 4) |
| Alta | `memory/projects/gano-digital.md` |
| Alta | `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md` (§3.7) |
| Media | `memory/commerce/rcc-pfid-checklist.md` |
| Media | `memory/research/fase4-plataforma.md` |
| Media | `.github/copilot-instructions.md` |
| Referencia externa | [Get Started GoDaddy API](https://developer.godaddy.com/getstarted) |
| Referencia externa | [Tabla planes reseller](https://www.godaddy.com/es/help/en-que-se-diferencian-los-planes-de-revendedor-api-y-de-revendedor-llave-en-mano-7940) |

---

## 8. Guardrails (no regresiones)

1. **No** commitear API Key/Secret ni Good as Gold en claro.
2. **No** asumir que la API REST reemplaza **Reseller Store + RCC** sin decisión explícita de Diego.
3. **No** modificar plugins (`gano-reseller-enhancements`, Reseller Store) salvo issue o pedido explícito.
4. Desarrollo API: **OTE** primero; prod solo con flujo probado e idempotencia donde aplique.

---

## 9. Una frase para Claude

**El producto comercial de gano.digital sigue siendo la vitrina WordPress + carrito Reseller; las APIs GoDaddy son palancas opcionales en servidor o futuro billing; la documentación del 2026-04-02 ya refleja esa jerarquía.**

---

*Fin del reporte. Actualizar este archivo si cambia el plan contractual (solo Reseller vs API) o el alcance de Fase 4.*
