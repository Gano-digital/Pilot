# Crypto → compra manual en RCC — política GoDaddy y second brain consolidado

> **Estado (2026-04-22):** modelo **full crypto → compra manual en RCC** queda **diferido**; sin implementación activa. La línea comercial prioritaria documentada es el enfoque **híbrido** (checkout convencional para núcleo Reseller + crypto opcional solo en **servicios complementarios a Gano**) — ver [`estrategia-comercial-hibrido-checkout-servicios-complementarios-2026-04.md`](estrategia-comercial-hibrido-checkout-servicios-complementarios-2026-04.md).

**Ámbito:** modelo comercial de **referencia futura**: cliente deja datos → registro → pago en cripto a cartera operada por Gano → tras confirmación, el operador compra/provisiona servicios vía programa **Reseller** (RCC / checkout).  
**Fecha:** 2026-04-22.  
**No es asesoría legal ni fiscal.** Texto vinculante: enlaces oficiales al final.

---

## 1. Qué quedó consolidado del trabajo previo (técnico + operativo)

| Tema | Estado / decisión |
|------|-------------------|
| Canal principal de cobro cliente | **Reseller Store + RCC**; API Developer es complementaria (`memory/research/godaddy-api-reseller-operations-2026.md`). |
| PFIDs / `pl_id` | Fuente de verdad RCC; validar numéricos vs slugs; `online_storage` suele ser el último hueco (`memory/commerce/rcc-pfid-checklist.md`). |
| Precios en WordPress | Sync vía plugin `reseller-store` (`listPrice` / `salePrice`); override ACF solo si **ACF está activo** y `override_price` en CPT — sin ACF, vitrina sigue catálogo API del store. |
| Bundles (`class-bundle-handler.php`) | Los IDs deben ser **PFIDs reales del RCC**, no placeholders; probar total carrito = expectativa RCC. |
| Sandbox | Smoke Test → modo sandbox antes de cargos reales. |
| White-label | Marca Gano primero + disclosure coherente con trust pages (`memory/content/trust-pages-bundle-2026.md`). |
| Bundles y descuentos “reales” | Preferir **RCC** (reglas, markup mínimo) frente a precios inventados en WP. |

---

## 2. Nombres sugeridos para el servicio (posicionamiento B2B)

Evitar “inversión” o lenguaje financiero regulado. Opciones claras para **empresas / emprendedores con necesidad de hosting**:

1. **Aprovisionamiento asistido Gano** — “Pagás en cripto; nosotros activamos tu plan en la infraestructura contratada.”
2. **Concierge Reseller** — énfasis en soporte humano + una factura/pedido trazable.
3. **Activación crypto (B2B)** — explícito que el contrato de servicio sigue siendo el flujo Reseller / términos del proveedor.

**Mensaje clave:** no es “comprar cripto como producto”; es **pagar por servicios digitales** con un medio acordado, cumpliendo identificación y registro previos.

---

## 3. Marco GoDaddy Reseller (contrato público) — lectura operativa

Fuente: [GoDaddy Reseller Agreement](https://www.godaddy.com/legal/agreements/reseller-agreement) (Last Revised: **2/2/2026** en la versión consultada). Complemento: [Universal Terms of Service](https://www.godaddy.com/legal/agreements) y políticas enlazadas desde la página Legal del programa.

### 3.1 Solo clientes “business”

El acuerdo indica que el programa es para vender a **“business customer”** según definición del contrato, y que debés **mercadea, ofrecer, vender y revender solo a clientes empresariales**, sin permitir conscientemente uso **personal, familiar o doméstico** (cláusula de representaciones en la sección YOUR OBLIGATIONS).

**Implicación para crypto:** diseñá el funnel (copy, formulario, T&C) para **B2B** (razón social o actividad empresarial, NIT cuando aplique, uso profesional del servicio).

### 3.2 Cómo se paga el servicio (tu rol como reseller)

En **Payment for Services** el acuerdo prevé que las compras de *Available Services* por ciertos tipos de cliente ocurran en el ecommerce de GoDaddy, y enumera tres modos de pago:

1. **Vos**, en nombre del cliente, **con tu propio método de pago**.  
2. **Vos**, en nombre del cliente, **con los datos de pago del cliente**.  
3. **El cliente**, con sus propios datos.

**Lectura práctica:** el flujo “cliente te envía cripto → vos usás tarjeta/saldo/método tuyo en RCC o checkout para contratar en su nombre” encaja en el **modelo (1)** siempre que el resto del acuerdo (precios, contrato con GoDaddy o con el cliente según el tipo de agencia) se cumpla. **Confirmá con soporte Reseller o revisión legal** si tu variante exacta (crypto off-ramp personal) requiere documentación adicional.

### 3.3 Introduction agent vs Selling agent (límites de autoridad)

- **Introduction agent:** el cliente contrata **directo con GoDaddy**; vos no negociás ni variás términos en nombre de GoDaddy; debés **divulgar** que sos agente de introducción y los límites de tu autoridad.  
- **Selling agent:** el cliente contrata **con vos** para los servicios; precios en rango retail fijado por GoDaddy; términos estipulados por GoDaddy; entrás en contrato con el cliente **en representación** según esa sección.

**Implicación:** el soporte “humano” que das (onboarding, DNS, explicación) debe **no parecer** que modificás el contrato estándar de GoDaddy si sos introduction agent. Para promesas comerciales propias (SLA Gano, tiempos de respuesta), usá **T&C propios** que no choquen con los de GoDaddy/ICANN cuando aplique dominios.

### 3.4 Precio mayorista / retail

Debes configurar markup y reglas en RCC de forma que se **mantenga el precio mayorista** para GoDaddy (obligación explícita en el acuerdo). Los descuentos agresivos o “regalar comisión” tienen un marco (p. ej. *Discount Shopper* mencionado en comisiones); no uses precios en vitrina que **rompan** el mínimo o las reglas del catálogo.

### 3.5 Confidencialidad RCC

La información de clientes, pedidos y datos del **Reseller Control Center** se trata como **confidencial**; ejercé cuidado razonable para no divulgarla. Alineado con no publicar PFIDs/tokens en repos públicos.

### 3.6 API Reseller (si algún día comprás vía API)

Sección aparte: **Prepaid Account**, requisitos legales en sitio, ICANN, etc. Tu camino actual (“sin compras API”) reduce exposición Good as Gold; el fulfillment manual sigue siendo principalmente **RCC / storefront**, no REST.

### 3.7 Developer API (uso paralelo)

Seguir [`memory/research/godaddy-api-reseller-operations-2026.md`](../../memory/research/godaddy-api-reseller-operations-2026.md) y [Developer Terms of Service](https://www.godaddy.com/legal/agreements/developer-terms-of-service). No usar la API como pasarela de cobro a terceros.

---

## 4. Riesgos y buenas prácticas del flujo crypto → compra manual

| Riesgo | Mitigación sugerida |
|--------|---------------------|
| **Mezcla contable** cartera personal (p. ej. exchange) vs flujo de negocio | Preferir cartera/ cuenta **dedicada al negocio**, libro de pedidos (quién pagó, monto, hash, plan contratado). |
| **AML / travel rule** (jurisdicciones y VASPs) | Investigá obligaciones en **Colombia** y políticas del exchange; no asumir que “solo P2P” exime registros. |
| **Volatilidad** | Cotización en COP/USD **con ventana de tiempo** o stablecoin acordada; confirmación on-chain explícita. |
| **Chargeback / irreversibilidad** | Política clara: crypto confirmada = pedido procesado; reembolsos según T&C propios y límites del proveedor. |
| **Titular del servicio** | Definir si el producto queda a nombre del **cliente** (datos RCC correctos) o política de subcuentas; conservá prueba de consentimiento. |
| **Impuestos** | Sin empresa registrada, el alcance fiscal es limitado y delicado; antes de escalar volumen, asesoría local. |

---

## 5. Alcance razonable para “primeros clientes crypto”

Dentro del marco anterior, un alcance **defendible** al inicio:

1. **Registro B2B** mínimo (contacto, uso previsto, aceptación de T&C Gano + referencia a términos del proveedor).  
2. **Presupuesto cerrado** en fiat equivalente + instrucción de pago crypto (red, activo, ventana).  
3. **Confirmación on-chain** por operador; registro en hoja/ticket interno.  
4. **Compra en RCC** con método (1) o flujo estándar que corresponda a tu tipo de agencia; entrega de accesos según producto.  
5. **Soporte** en línea con números y canales publicados en el sitio (sin depender solo de DMs informales).

**No prometer:** “anonimato total”, “sin KYC siempre”, o integración on-chain nativa con el carrito GoDaddy.

---

## 6. Enlaces oficiales (verificación periódica)

- [Reseller Agreement](https://www.godaddy.com/legal/agreements/reseller-agreement)  
- [Legal Agreements index](https://www.godaddy.com/legal/agreements)  
- [Developer Terms of Service](https://www.godaddy.com/legal/agreements/developer-terms-of-service)  
- Documentación interna relacionada: `godaddy-api-reseller-operations-2026.md`, `api-integration-feasibility-gano-2026.md`, `rcc-pfid-checklist.md`, `rcc-checkout-issue-264-status.md`

---

## 7. Próximos pasos recomendados (checklist)

- [ ] Confirmar con **cuenta Reseller / soporte GoDaddy** si tu flujo crypto off-ramp + pago con método propio (1) requiere documentación o disclosure extra.  
- [ ] Redactar **T&C “Crypto B2B”** cortos (sin choque con introduction/selling agent).  
- [ ] Definir **libro de pedidos** y política de tipo de cambio / confirmaciones.  
- [ ] Revisar **Binance / exchange** términos de uso para cuentas personales recibiendo pagos de terceros por servicios.  
- [ ] Cuando exista volumen: entidad legal + facturación según asesoría CO.

---

**Responsable contenido:** Diego (decisión comercial) · **Mantenimiento:** actualizar tras cambios en Reseller Agreement o regulación local.
