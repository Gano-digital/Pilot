# FAQ Schema — Candidatos oleada 3 (Wave 3)

Documento de insumos para ampliar el bloque `FAQPage` de `gano-seo.php`.  
Cada pregunta es un **borrador revisable** por Diego antes de activar.

**Criterios de selección:**
- Complementan las 5 preguntas ya publicadas (sin repetir SOTA, soberanía, ecosistemas, seguridad ni migraciones).
- Responden objeciones reales de compra en Colombia/LATAM.
- Honestidad Reseller: no inventan datacenter propio; reconocen programa de reseller donde aplica.
- Cifras con formulación prudente ("hasta", "objetivo"); placeholders `[X]` donde falte dato real.
- Español Colombia, tono profesional cálido (tuteo en marketing, "usted" en contexto legal/checkout).

---

## Candidatos

### C01 — Métodos de pago disponibles
**Pregunta:**  
¿Qué métodos de pago aceptan para contratar un ecosistema?

**Respuesta (borrador):**  
Procesamos pagos en pesos colombianos (COP) a través de Wompi: PSE, tarjeta de crédito, tarjeta débito, Nequi y Daviplata. Tu transacción y facturación quedan en Colombia, sin conversiones de moneda.

**Notas de revisión:** Confirmar que todos los métodos Wompi estén activos en producción antes de publicar.

---

### C02 — Backups y recuperación
**Pregunta:**  
¿Cada cuánto hacen copias de seguridad y cómo recupero mi sitio si algo falla?

**Respuesta (borrador):**  
Nuestros planes incluyen respaldos automáticos con frecuencia mínima diaria. Si necesitas restaurar, el proceso parte de los snapshots de tu ecosistema; el tiempo objetivo de recuperación es inferior a [X] horas según el plan contratado. Bastión SOTA incluye los intervalos de respaldo más cortos de la familia.

**Notas de revisión:** Reemplazar `[X]` con el SLA real del proveedor de infraestructura.

---

### C03 — Soporte técnico
**Pregunta:**  
¿Qué soporte técnico incluye cada plan y en qué horarios opera?

**Respuesta (borrador):**  
Todos los ecosistemas incluyen soporte por ticket en español. Núcleo Prime cubre incidencias en horario hábil Colombia; Fortaleza Delta amplía la cobertura a través de nuestro portal de cliente; Bastión SOTA tiene prioridad alta con tiempos de respuesta objetivo menores a [X] horas. Brindamos acompañamiento en español, con foco WordPress.

**Notas de revisión:** Ajustar SLAs reales; mencionar canal (email, portal, WhatsApp) según lo que esté operativo.

---

### C04 — ¿Necesito conocimientos técnicos?
**Pregunta:**  
¿Necesito saber programación o administración de servidores para gestionar mi plan?

**Respuesta (borrador):**  
No. Los ecosistemas de Gano Digital están diseñados para que puedas operar tu WordPress sin tocar el servidor. El panel de control es visual, la configuración inicial la hacemos contigo y el monitoreo proactivo actúa antes de que notes un problema.

**Notas de revisión:** Verificar que el panel de control prometido esté disponible en el plan más básico (Núcleo Prime).

---

### C05 — Facturación y quién la emite
**Pregunta:**  
¿Quién factura el servicio y cómo funciona la renovación?

**Respuesta (borrador):**  
La facturación se emite a través de Gano Digital, operando como proveedor de servicios de infraestructura WordPress respaldado en un programa de reseller de clase mundial. Recibirás tu factura en COP al correo registrado. Las renovaciones son automáticas; puedes cancelar con [X] días de anticipación según los términos del plan.

**Notas de revisión:** Agregar NIT real `[NIT]` y días de cancelación `[X]` cuando estén definidos.

---

### C06 — ¿Servidores en Colombia?
**Pregunta:**  
¿Sus servidores están ubicados físicamente en Colombia?

**Respuesta (borrador):**  
Operamos sobre infraestructura de clase empresarial con presencia en centros de datos que cubren LATAM. La facturación, el soporte y la relación contractual son 100 % colombianos. Si la ubicación física del servidor es un requisito regulatorio para tu organización, escríbenos y analizamos la arquitectura más adecuada.

**Notas de revisión:** Ajustar la ubicación exacta del datacenter del proveedor reseller si se puede revelar sin NDA.

---

### C07 — Escalabilidad
**Pregunta:**  
¿Puedo cambiar de ecosistema si mi negocio crece?

**Respuesta (borrador):**  
Sí. El camino natural es Núcleo Prime → Fortaleza Delta → Bastión SOTA. El cambio de ecosistema se coordina con el equipo de Gano Digital; migramos los recursos sin afectar la disponibilidad de tu sitio. No hay penalización por escalar.

**Notas de revisión:** Confirmar política real de upgrade sin penalización con el proveedor de infraestructura.

---

### C08 — SLA de disponibilidad
**Pregunta:**  
¿Qué nivel de disponibilidad (uptime) garantizan?

**Respuesta (borrador):**  
Nuestro objetivo de disponibilidad es del 99,9 % mensual sobre la plataforma base. Bastión SOTA incorpora redundancia adicional para acercarse al 99,99 %. Los SLA formales se detallan en el contrato de servicio de cada ecosistema y están respaldados por los acuerdos del proveedor de infraestructura.

**Notas de revisión:** Citar solo porcentajes que estén en el SLA escrito del reseller; evitar prometer más de lo que cubre el contrato aguas arriba.

---

### C09 — Diferencia entre Núcleo Prime y Bastión SOTA
**Pregunta:**  
¿Cuál es la diferencia principal entre el ecosistema Núcleo Prime y Bastión SOTA?

**Respuesta (borrador):**  
Núcleo Prime es la base de alto rendimiento: almacenamiento NVMe, WordPress optimizado y seguridad esencial, ideal para negocios en crecimiento. Bastión SOTA es la capa de resiliencia total: redundancia activa, respaldos con mayor frecuencia, soporte prioritario y monitoreo avanzado, pensado para operaciones críticas que no pueden tolerar interrupciones.

**Notas de revisión:** Añadir diferencias de precio en COP cuando estén publicadas en la tienda.

---

### C10 — WordPress como plataforma
**Pregunta:**  
¿Por qué construir sobre WordPress en lugar de otra plataforma?

**Respuesta (borrador):**  
WordPress impulsa más del 43 % de la web mundial, cuenta con el ecosistema de plugins y temas más grande del mercado, y ofrece control total sobre tu contenido y datos. Gano Digital especializa su infraestructura exclusivamente en WordPress para llevar esa plataforma a niveles enterprise: optimizaciones a nivel de servidor, caching inteligente y hardening específico que una solución genérica no puede ofrecer.

**Notas de revisión:** Actualizar porcentaje de mercado WordPress con cifra verificada del año en curso si cambia significativamente.

---

## Estado de candidatos

| ID  | Estado      | Integrado en schema | Observaciones |
|-----|-------------|---------------------|---------------|
| C01 | Borrador ✏️  | No                  | Verificar métodos Wompi activos |
| C02 | Borrador ✏️  | No                  | Completar SLA backups |
| C03 | Borrador ✏️  | No                  | Definir SLAs de soporte |
| C04 | Borrador ✏️  | No                  | Verificar panel de control |
| C05 | Borrador ✏️  | No                  | Agregar NIT y días cancelación |
| C06 | Borrador ✏️  | No                  | Verificar NDA datacenter |
| C07 | Borrador ✏️  | No                  | Confirmar política upgrade |
| C08 | Borrador ✏️  | No                  | Verificar SLA reseller |
| C09 | Borrador ✏️  | No                  | Completar con precios COP |
| C10 | Borrador ✏️  | No                  | Actualizar % mercado WP |

---

## Cómo activar una pregunta en el schema

Una vez que Diego revise y apruebe un candidato, puede agregarlo al schema de dos formas:

### Opción A — Filtro PHP (sin editar el MU plugin)
Agrega en `wp-content/themes/gano-child/functions.php` o en un plugin pequeño:

```php
add_filter( 'gano_faq_questions', function( array $questions ): array {
    $questions[] = array(
        'question' => '¿Qué métodos de pago aceptan para contratar un ecosistema?',
        'answer'   => 'Procesamos pagos en pesos colombianos (COP) a través de Wompi: PSE, tarjeta de crédito, tarjeta débito, Nequi y Daviplata. Tu transacción y facturación quedan en Colombia, sin conversiones de moneda.',
    );
    return $questions;
} );
```

### Opción B — Edición directa del MU plugin
Editar la sección `FAQ en homepage` de `wp-content/mu-plugins/gano-seo.php` y agregar
un elemento al array `mainEntity` con la misma estructura `@type: Question / acceptedAnswer`.

---

_Generado por Copilot Agent — Oleada 3, Abril 2026._  
_Para uso humano exclusivo. Revisar antes de publicar en schema de producción._
