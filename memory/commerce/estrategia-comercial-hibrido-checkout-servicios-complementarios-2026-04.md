# Estrategia comercial híbrida — checkout convencional + crypto en servicios complementarios

**Audiencia objetivo inicial:** perfiles **crypto-native / tech investors** (founders, fondos pequeños, family offices digitales) que ya operan on-chain y buscan **infraestructura web soberana** sin friccionar el núcleo regulado del Reseller.  
**Estado:** documentación de producto y go-to-market; **sin implementación técnica de pagos crypto** en esta fase.  
**Fecha:** 2026-04-22.  
**Relacionado:** [`crypto-manual-fulfillment-godaddy-policy-2026-04.md`](crypto-manual-fulfillment-godaddy-policy-2026-04.md) (referencia legal/operativa; modelo full-crypto **diferido**).

---

## 1. Principio rector (híbrido)

| Capa | Cobro | Quién factura / contrata | Notas |
|------|--------|---------------------------|--------|
| **A. Infraestructura núcleo** (hosting, SSL, M365 vía catálogo Reseller, etc.) | **Tarjeta/ métodos del checkout GoDaddy** (flujo actual) | Cliente con **GoDaddy** según modelo *introduction* o *selling agent* del [Reseller Agreement](https://www.godaddy.com/legal/agreements/reseller-agreement) | Cero integración crypto en el carrito; máxima claridad regulatoria y conciliación con RCC. |
| **B. Servicios complementarios Gano** (consultoría, implementación, migración, retainers, capacitación, “concierge” técnico, paquetes hora) | **Pago acordado a Gano Digital**; canal puede incluir **crypto** (off-chain acordado: red, activo, confirmación) | Contrato / orden de servicio **entre cliente y Gano** | No sustituye el checkout del Reseller; es **ingreso separado** y trazable. |

**Mensaje de valor para inversionistas:** “Tu stack crítico queda en un proveedor institucional con checkout estándar; tu relación con Gano para **velocidad y acompañamiento** puede alinearse a cómo ya movés treasury (incl. activos digitales) en servicios **no-hosting**.”

---

## 2. Por qué encaja con el perfil “inversionista”

1. **Riesgo percibido:** muchos inversores crypto evitan pasarelas opacas; el núcleo en **checkout conocido** reduce fricción de compliance interno.  
2. **Opcionalidad:** quien quiera pagar retainer o sprint en **stablecoin / BTC** puede hacerlo **solo** en la capa B, con contrato y memo comercial claros.  
3. **Soberanía narrativa:** Gano posiciona **marca + soporte + implementación**; la infra sigue bajo marco Reseller (transparencia en trust pages).  
4. **No confundir con “producto de inversión”:** copy y T&C deben hablar de **servicios profesionales B2B**, no de rendimiento financiero.

---

## 3. Empaque comercial sugerido (ofertas)

- **“Núcleo Reseller”** — lo que ya vendés vía CTAs / catálogo; pago 100 % checkout.  
- **“Gano Sprint”** — paquete fijo de horas (migración, DNS, performance, seguridad base). Pago: factura Gano; **opción crypto** explícita en el anexo comercial.  
- **“Gano Retainer mensual”** — soporte y evolución; mismo esquema de pago dual.  
- **“Concierge onboarding”** — línea de tiempo + entregables; ideal para primer contacto desde contenido `.md` / `.txt` orientado a bots (SEO) que lleve a **formulario B2B**, no a carrito crypto.

**Precios:** anclar complementarios en **COP/USD** con equivalencia crypto **solo como referencia** al momento del pago (ventana de tipo de cambio acordada).

---

## 4. Embudo (alto nivel)

1. **Atracción:** contenido técnico + narrativa B2B/crypto-friendly (sin promesas de retorno).  
2. **Calificación:** formulario “tipo de proyecto / stack / urgencia / presupuesto estimado”.  
3. **Núcleo:** enlace a **checkout Reseller** para contratar hosting/productos de catálogo.  
4. **Upsell:** propuesta firmada o LOI para **capa B** (Sprint / Retainer).  
5. **Entrega:** activación RCC + entregables Gano; soporte según plan.

---

## 5. Riesgos y guardarraíles

- **Separación contable:** ingresos A (comisiones / flujo Reseller) vs ingresos B (servicios Gano) — libro distinto o partidas claras.  
- **Compliance:** capa B con KYC ligero B2B según evolución del negocio; revisar políticas de exchange si usás cartera centralizada.  
- **Expectativas:** el cliente debe entender que **el hosting recurrente** sigue el ciclo de facturación del proveedor subyacente, no un “pago único crypto” eterno.  
- **Legal:** mantener alineación con Reseller Agreement (clientes **business**; límites de agente); asesoría local cuando el volumen de capa B lo justifique.

---

## 6. Estado de implementación

| Ítem | Estado |
|------|--------|
| Documentación estrategia híbrida | ✅ Este archivo |
| Copy público (web / trust) | Pendiente humano |
| T&C “Servicios complementarios + opción crypto” | Pendiente asesoría / redacción |
| Integración técnica crypto | **Fuera de alcance** (solo documentado) |

---

## 7. Referencias internas

- `memory/commerce/rcc-pfid-checklist.md`  
- `memory/content/trust-pages-bundle-2026.md`  
- `TASKS.md` § Fase 4  
- `.cursor/memory/activeContext.md`
