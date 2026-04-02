# Matriz comercial de ecosistemas — Oleada 3

Documento de insumos para copy, ventas y coherencia entre el catálogo visible
en `shop-premium.php` y la narrativa de marca Gano Digital.

**Última actualización:** Abril 2026  
**Relacionado con:** [TASKS.md — Fase 4](../../TASKS.md#-fase-4--integración-godaddy-reseller-agilizada)  
**Fuente de criterio:** [`memory/research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md)

---

## Nota de honestidad Reseller

Los ecosistemas Gano Digital se aprovisionan a través del **programa de reseller de clase mundial GoDaddy**.
El checkout, la facturación y la gestión de renovaciones operan según los términos del proveedor de infraestructura.
Los IDs de producto en `shop-premium.php` (`host-1`, `host-2`, `host-3`) son **marcadores de posición** hasta que
el equipo confirme los IDs reales del Reseller Control Center (RCC) de GoDaddy y los sustituya en el código.
No usar estos IDs de marcador en campañas de rastreo ni en shortcodes de carrito activos sin validar primero.

---

## Tabla principal

| Plan | Promesa central | Audiencia objetivo | Objeción típica | Prueba verificable |
|------|-----------------|--------------------|-----------------|-------------------|
| **Núcleo Prime** | Infraestructura NVMe de entrada con soporte en español, activación rápida y operación sin sobresaltos para sitios en crecimiento. | Emprendedores, agencias boutique, negocios en etapa de lanzamiento o con tráfico moderado en Colombia/LATAM. | "Es caro para lo que necesito ahora." | Almacenamiento NVMe documentado · Stack WordPress + Elementor optimizado · Soporte en español por ticket [definir SLA real antes de publicar]. |
| **Fortaleza Delta** | Entorno WordPress administrado con hardening activo, mayor asignación de recursos y visibilidad operativa para marcas que ya generan ingresos. | PYMES, tiendas WooCommerce medianas, agencias que alojan clientes de alto valor. | "¿Por qué pagar el doble si mi host actual 'funciona'?" | Mayor capacidad de cómputo y almacenamiento que Núcleo Prime (especificar cifras reales del catálogo RCC) · Hardening incluido · Respaldos automatizados [frecuencia real a confirmar]. |
| **Bastión SOTA** | Rendimiento crítico Gen4 con seguridad de nivel empresarial, orientado a operaciones de alta autoridad que no toleran degradación ni incidentes visibles. | Tiendas con picos de tráfico, operaciones SaaS sobre WordPress, medios digitales, proyectos con SLA exigente. | "No veo qué me da que un VPS gestionado no dé." | Recursos dedicados o aislados (confirmar con RCC) · SLA objetivo ≥ 99,9 % de disponibilidad · Monitoreo proactivo documentado · Política de incidentes publicada [pendiente redactar]. |
| **Ultimate WP** _(complementario)_ | Máxima capacidad y blindaje ante picos masivos; para organizaciones o agencias con portafolios de alto tráfico. | Agencias grandes, medios, plataformas con > N usuarios concurrentes [definir umbral real]. | "¿Por qué no un cloud propio?" | Especificaciones de recursos del plan RCC · Soporte prioritario documentado · Caso de uso real o testimonio verificable [pendiente]. |

---

## Tabla de objeciones ampliada — los tres ecosistemas principales

| Objeción | Núcleo Prime | Fortaleza Delta | Bastión SOTA |
|----------|-------------|----------------|--------------|
| **Precio / valor** | El plan base incluye NVMe real (no HDD), soporte en español y activación rápida; más que un hosting compartido genérico por precio similar. | El salto de precio compra hardening activo, más recursos y visibilidad operativa: no es pagar dos veces el mismo servicio. | El costo es proporcional al riesgo que evita: una hora de caída en operaciones de alto tráfico supera la diferencia de precio mensual. |
| **Confianza / quién es Gano** | Infraestructura sobre programa reseller GoDaddy con años de trayectoria; Gano añade capa de servicio y soporte en tu idioma. | Mismo proveedor de infraestructura; Gano gestiona la experiencia y el hardening que el reseller no configura por omisión. | Las políticas de SLA, incidentes y escalamiento son transparentes y publicadas antes de contratar [pendiente publicación]. |
| **Soporte genérico** | Ticket en español con contexto de tu instalación; no un bot genérico. | Canal priorizado para clientes Fortaleza [definir tiempo de respuesta objetivo]. | Canal dedicado / WhatsApp Business [confirmar operación real antes de anunciar]. |
| **Atado al proveedor** | Migración asistida disponible [confirmar si aplica]; WordPress estándar = portabilidad alta. | Backups regulares exportables; no hay lock-in en datos. | Datos siempre tuyos; política de exportación documentada [pendiente]. |

---

## Coherencia con `shop-premium.php`

Los nombres de plan en el PHP coinciden con esta matriz:

| `shop-premium.php` `name` | Matriz | Precio mostrado (COP/mes) | ID marcador |
|--------------------------|--------|--------------------------|-------------|
| `Núcleo Prime` | ✅ | $ 196.000 | `host-1` |
| `Fortaleza Delta` | ✅ | $ 450.000 | `host-2` |
| `Bastión SOTA` | ✅ | $ 890.000 | `host-3` |
| `Ultimate WP` | ✅ (complementario) | $ 1.2M | `host-4` |

> **Acción pendiente (Fase 4):** Reemplazar `host-1` … `host-4` por los IDs reales del Reseller Control Center
> de GoDaddy antes de activar el shortcode `[rstore_cart_button]` en producción.
> Ver [TASKS.md — Fase 4 · Mapeo de UI SOTA → Reseller](../../TASKS.md).

---

## Precios — formulación prudente

- Los precios mostrados son **indicativos mensuales en COP** para el mercado colombiano.
- Pueden variar por tipo de cambio USD/COP aplicado al margen del reseller.
- Publicar siempre con la nota: *"Precios sujetos a variación. Consulta el carrito para el valor final."*
- No publicar precios de terceros (SSL Deluxe, M365) sin verificar el valor vigente en el RCC.

---

## CTAs recomendados por plan

| Plan | CTA primario | CTA secundario |
|------|-------------|----------------|
| Núcleo Prime | Elegir Núcleo Prime | Ver especificaciones |
| Fortaleza Delta | Activar Fortaleza Delta | Comparar planes |
| Bastión SOTA | Solicitar Bastión SOTA | Hablar con ventas |
| Ultimate WP | Cotizar Ultimate WP | Hablar con ventas |

---

## Placeholders pendientes de completar antes de publicar

- `[NIT]` — NIT de Gano Digital para factura / pie de página legal.
- `[teléfono]` / `[WhatsApp]` — canal de ventas real.
- `[SLA Fortaleza Delta]` — tiempo de respuesta de soporte comprometido.
- `[SLA Bastión SOTA]` — tiempo de respuesta y política de incidentes.
- `[IDs RCC]` — `host-1` … `host-4` reemplazados por los IDs reales del catálogo GoDaddy.
- `[testimonio verificable]` — caso de uso real para Ultimate WP.
- `[umbral de tráfico]` — número de usuarios concurrentes para segmentar Ultimate WP.

---

_Fin del documento. Actualizar en paralelo con cambios en `shop-premium.php` y con cada PR de Fase 4._
