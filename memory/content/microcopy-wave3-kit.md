# Gano Digital — Kit de Microcopy (Oleada 3, ES-CO)

Insumo de texto de interfaz para el equipo de diseño, desarrollo y contenido.
Alineado con el **Brief maestro oleada 3** (`memory/research/gano-wave3-brand-ux-master-brief.md`, §2 Voz y tono).

**Última actualización:** Abril 2026
**Idioma:** Español Colombia (es-CO)

---

## Regla de tuteo / ustedeo por contexto

| Contexto | Pronombre | Justificación |
|----------|-----------|---------------|
| **Marketing / Hero / Pilares** | **tú** | Tono cercano y de confianza; el prospecto explora, no ha comprometido nada todavía. |
| **Checkout / resumen de pedido** | **usted** | Contexto contractual y de pago; genera seguridad jurídica percibida. |
| **Formularios legales / consentimiento / políticas** | **usted** | Lenguaje formal alineado a normativa SIC Colombia. |
| **Mensajes de error / feedback de UI** | **tú** | Reduce fricción; el usuario está resolviendo un problema, no leyendo un contrato. |
| **Correos transaccionales (confirmación de compra)** | **usted** | Representan un documento de la transacción. |
| **Soporte / chat** | **tú** (por defecto) | Ajustable si el cliente escala el tono; no mezclar en la misma conversación. |

> ⚠️ **Regla de oro:** no mezclar tú/usted dentro de la misma sección o componente. Elige uno y mantenlo consistente hasta que cambie el contexto (ej. de la página de planes → checkout).

---

## 1. CTAs primarios

> Verbos de valor directo. Un CTA primario por vista/sección. Acción inmediata clara.

| ID | Texto del botón | Contexto de uso | Ecosistema / página |
|----|----------------|-----------------|---------------------|
| `cta-p-01` | Ver arquitecturas y planes | Hero principal | Global |
| `cta-p-02` | Elegir mi arquitectura | Sección de cierre / bottom CTA | Global |
| `cta-p-03` | Activar Núcleo Prime | Card del plan Núcleo Prime | Shop / Ecosistemas |
| `cta-p-04` | Activar Fortaleza Delta | Card del plan Fortaleza Delta | Shop / Ecosistemas |
| `cta-p-05` | Activar Bastión SOTA | Card del plan Bastión SOTA | Shop / Ecosistemas |
| `cta-p-06` | Agregar al carrito | Botón de compra en producto WooCommerce | Shop premium |
| `cta-p-07` | Completar mi pedido | Botón de confirmación en checkout | Checkout |
| `cta-p-08` | Confirmar y pagar | Botón de pago final (Wompi) | Checkout — paso de pago |
| `cta-p-09` | Solicitar diagnóstico gratis | Lead magnet / sección de valor | Landing / Contacto |
| `cta-p-10` | Empezar la migración | Para clientes con sitio existente | Landing migración |

---

## 2. CTAs secundarios

> Acción complementaria o de menor compromiso. Verbo informativo o de comparación.

| ID | Texto del enlace / botón | Contexto de uso |
|----|--------------------------|-----------------|
| `cta-s-01` | Hablar con el equipo | Hero / sección de duda comercial |
| `cta-s-02` | Comparar ecosistemas | Sección de planes / tabla |
| `cta-s-03` | Ver especificaciones técnicas | Dentro de card de plan |
| `cta-s-04` | Leer más | Pilar SEO / artículo de blog — solo si no hay texto completo visible |
| `cta-s-05` | Ver política de privacidad | Pie de formulario / footer |
| `cta-s-06` | Ver términos del servicio | Pie de formulario / footer |
| `cta-s-07` | Cancelar | Modales de confirmación — acción destructiva secundaria |
| `cta-s-08` | Volver a los planes | Breadcrumb / botón de retroceso en checkout |
| `cta-s-09` | Guardar para después | Carrito / lista de deseos (si aplica) |
| `cta-s-10` | Abrir chat de soporte | Flotante o inline cuando hay duda técnica |

---

## 3. Mensajes de error genéricos

> Tono: directo, sin alarma. Siempre ofrecer siguiente acción. Pronombre: **tú**.

| ID | Tipo | Mensaje de error | Acción sugerida en UI |
|----|------|-----------------|----------------------|
| `err-01` | Error de red / timeout | Algo salió mal con la conexión. Revisa tu red e intenta de nuevo. | Botón: **Reintentar** |
| `err-02` | Error 500 (servidor) | Tuvimos un problema interno. Ya estamos al tanto. Si el problema persiste, contáctanos. | Enlace: **Contactar soporte** |
| `err-03` | Error 404 (página no encontrada) | Esta página no existe o fue movida. Pero tu destino sí. | Botones: **Ir al inicio** / **Ver planes** |
| `err-04` | Campo requerido vacío | Este campo es obligatorio. | Inline bajo el campo |
| `err-05` | Formato de correo inválido | Revisa que el correo esté bien escrito (ej: tu@empresa.com). | Inline bajo el campo |
| `err-06` | Contraseña demasiado corta | La contraseña debe tener al menos 8 caracteres. | Inline bajo el campo |
| `err-07` | Error de pago genérico (Wompi) | No pudimos procesar el pago. Verifica los datos o intenta con otro método. | Botones: **Cambiar método** / **Reintentar** |
| `err-08` | Pago rechazado por banco | Tu banco rechazó la transacción. Comunícate con ellos o elige otro método de pago. | Botón: **Elegir otro método** |
| `err-09` | Sesión expirada | Tu sesión venció por inactividad. Ingresa de nuevo para continuar. | Botón: **Volver a ingresar** |
| `err-10` | Formulario con errores múltiples | Hay algunos campos que necesitan corrección. Revísalos antes de continuar. | Scroll automático al primer error |
| `err-11` | Archivo demasiado pesado | El archivo supera el límite permitido ([X] MB). Reduce el tamaño e inténtalo de nuevo. | Inline bajo el campo de archivo |
| `err-12` | Límite de intentos alcanzado | Demasiados intentos fallidos. Espera unos minutos antes de volver a intentarlo. | Contador regresivo visible |

---

## 4. Estados vacíos

> Mostrar propósito claro + acción recuperadora. Nunca mostrar pantalla en blanco.

### 4a. Carrito vacío (WooCommerce)

| Elemento | Texto |
|----------|-------|
| **Título** | Tu carrito está vacío |
| **Subtítulo** | Aún no has agregado ningún ecosistema a tu pedido. |
| **CTA primario** | Ver arquitecturas y planes |
| **CTA secundario** | Hablar con el equipo |
| **Microcopy de apoyo** | ¿No sabes por dónde empezar? Compara los ecosistemas o cuéntanos sobre tu sitio y te orientamos. |

### 4b. Sin resultados de búsqueda

| Elemento | Texto |
|----------|-------|
| **Título** | Sin resultados para "[término]" |
| **Subtítulo** | No encontramos nada con esa búsqueda. Prueba con otras palabras o explora nuestros ecosistemas. |
| **CTA primario** | Ver todos los planes |
| **CTA secundario** | Contactar soporte |
| **Microcopy de apoyo** | También puedes revisar nuestra sección de preguntas frecuentes. |

### 4c. Sin pedidos en el historial (WooCommerce / portal cliente)

| Elemento | Texto |
|----------|-------|
| **Título** | Todavía no tienes pedidos |
| **Subtítulo** | Cuando actives un ecosistema, lo verás aquí con toda la información de tu servicio. |
| **CTA primario** | Explorar ecosistemas |

### 4d. Sin entradas en blog / recursos

| Elemento | Texto |
|----------|-------|
| **Título** | Aún no hay contenido en esta sección |
| **Subtítulo** | Estamos preparando recursos de valor. Vuelve pronto. |
| **CTA secundario** | Volver al inicio |

### 4e. Error 404 personalizado

| Elemento | Texto |
|----------|-------|
| **Título (H1)** | Esta página no existe (todavía) |
| **Párrafo** | El enlace puede estar desactualizado o la página fue movida. No se ha perdido nada importante: desde aquí puedes ir a donde necesitas. |
| **CTA primario** | Ir al inicio |
| **CTA secundario** | Ver planes |
| **Microcopy de apoyo** | Si crees que esto es un error, escríbenos. |

---

## 5. Pie de formulario y consentimiento

> Pronombre: **usted** (contexto de datos personales y legal). Placeholders señalados con `[ ]`.

### 5a. Checkbox de consentimiento (formulario de contacto / lead magnet)

```
Al enviar este formulario, usted acepta que Gano Digital [NIT: PENDIENTE — completar con
abogado/Diego] trate sus datos de contacto con el fin de responder a su solicitud y, si lo
autoriza, enviarle información sobre nuestros servicios. Puede ejercer sus derechos de
acceso, rectificación, supresión y portabilidad de conformidad con la Ley 1581 de 2012
(Colombia) y el Decreto 1074 de 2015. Para más información, consulte nuestra
[Política de tratamiento de datos personales — ENLACE PENDIENTE].
```

### 5b. Checkbox de suscripción a comunicaciones (opcional, independiente del anterior)

```
Deseo recibir novedades, guías técnicas y ofertas de Gano Digital por correo electrónico.
Puede cancelar su suscripción en cualquier momento desde el enlace en cada mensaje.
```

### 5c. Pie de formulario de checkout (bajo botón de pago)

```
Al confirmar su pedido, usted acepta los [Términos del Servicio — ENLACE PENDIENTE] y la
[Política de Privacidad — ENLACE PENDIENTE] de Gano Digital. Los pagos son procesados por
Wompi ([ENLACE a política de Wompi — PENDIENTE]) y están sujetos a los términos del
proveedor de infraestructura. Facturación en pesos colombianos (COP).
```

> ⚠️ **Placeholders legales — requieren revisión:**
> - `[NIT: PENDIENTE]` — completar con el NIT real de la razón social de Gano Digital.
> - `[Política de tratamiento de datos personales — ENLACE PENDIENTE]` — URL de la página legal una vez redactada por abogado o asumida por Diego.
> - `[Términos del Servicio — ENLACE PENDIENTE]` — ídem.
> - `[Política de Privacidad — ENLACE PENDIENTE]` — ídem.
> - `[ENLACE a política de Wompi — PENDIENTE]` — enlace oficial a T&C de Wompi Colombia.
> - Todos los textos legales **deben ser revisados por un abogado** antes de publicar en producción.

### 5d. Mensaje de confirmación post-envío (formulario de contacto)

| Elemento | Texto |
|----------|-------|
| **Título** | ¡Mensaje recibido! |
| **Párrafo** | Gracias por escribirnos. Revisaremos su solicitud y le responderemos en un plazo de 24–48 horas hábiles [PENDIENTE — confirmar SLA real de soporte con Diego]. |
| **Microcopy** | Mientras tanto, puede explorar nuestros ecosistemas o revisar las preguntas frecuentes. |
| **CTA opcional** | Ver planes |

### 5e. Mensaje de confirmación post-pago (checkout Wompi)

| Elemento | Texto |
|----------|-------|
| **Título** | ¡Pedido confirmado! |
| **Párrafo** | Su ecosistema está siendo aprovisionado. Recibirá un correo de confirmación en [dirección de email del cliente] con los detalles de acceso. |
| **Microcopy** | Si no recibe el correo en los próximos 10 minutos [PENDIENTE — ajustar según proveedor de correo transaccional], revise la carpeta de spam o contáctenos. |
| **CTA secundario** | Ver mi pedido |

---

## 6. Notas de implementación

1. **Sustitución de placeholders:** todos los textos con `[MAYÚSCULAS — PENDIENTE]` deben ser completados por Diego o el asesor legal antes de publicar. Usar `<!-- TODO: -->` en el código para rastrearlos.
2. **Consistencia tú/usted:** revisar con el checklist de la sección 0 antes de cada despliegue.
3. **WooCommerce:** los textos de carrito vacío y checkout se pueden sobrescribir vía `woocommerce/cart/cart-empty.php` en el child theme o con el filtro `woocommerce_empty_cart_message`.
4. **Elementor:** los textos de estados vacíos y CTAs deben registrarse también en los widgets de texto del editor para mantener consistencia con este documento.
5. **Accesibilidad:** los mensajes de error deben estar enlazados al campo mediante `aria-describedby`; los estados vacíos deben tener `role="status"` o equivalente para lectores de pantalla.
6. **Actualización de este archivo:** al cambiar cualquier CTA o mensaje en producción, actualizar también este kit para que sirva como fuente de verdad.
