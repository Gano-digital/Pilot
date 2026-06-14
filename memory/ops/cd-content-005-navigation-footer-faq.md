# Tarea cd-content-005 — Wave contenidos: Navegación + Footer + FAQ Schema

**ID:** `cd-content-005`  
**Prioridad:** P2  
**Requiere humano:** NO (documentación + preparación)  
**Generado:** 2026-04-19  
**Estado:** Listo para implementación progresiva  

---

## Objetivo

Alinear con especificaciones finales:
1. **Navegación primaria:** estructura canónica, dropdowns, botón CTA
2. **Footer:** auditoría de placeholders falsos y datos pendientes
3. **FAQ Schema JSON-LD:** inyectar 10 candidatos de preguntas frecuentes en gano-seo.php

**Entregables:**
- ✅ `memory/content/navigation-primary-spec-2026.md` — Especificación completa (checklist + estructura visual)
- ✅ `memory/content/footer-contact-audit-wave3.md` — Auditoría de placeholders y datos faltantes
- ✅ `memory/content/faq-schema-candidates-wave3.md` — 10 preguntas borrador para schema FAQPage
- 📋 **NUEVO:** `memory/ops/cd-content-005-navigation-footer-faq.md` — Este documento (guía implementación)
- ✅ `wp-content/mu-plugins/gano-seo.php` — Ya soporta FAQ schema vía filtro `gano_faq_questions`

---

## 1. VALIDACIÓN — NAVEGACIÓN PRIMARIA

### Estado actual

**Especificación:** `memory/content/navigation-primary-spec-2026.md` (v1.1 · Abril 2026)

✅ **Documentación COMPLETA:**
- Tabla de ítems P0/P1: 5 ítems principales + sub-ítems dropdowns
- Estructura visual confirmada: header con logo + 4 ítems + botón CTA
- CSS ya implementado: `.gano-menu-cta` y `.menu-separator` en `gano-child/style.css` (líneas 1706–1737)
- Registro de ubicación: `primary` ya registrada en `functions.php` (líneas 321–330)

### Checklist de validación

- [ ] **Menú creado en wp-admin:** Nombre `Menú Principal 2026`, asignado a ubicación `primary`
- [ ] **Ítems P0 presentes y en orden:**
  - [ ] Ecosistemas (con dropdown: Núcleo Prime, Fortaleza Delta, Bastión SOTA, Comparar todos)
  - [ ] Pilares (con dropdown: 5 categorías de SOTA)
  - [ ] Nosotros (solo si página publicada con copy real)
  - [ ] Contacto
  - [ ] Elegir plan → (botón CTA con clase `gano-menu-cta`)
- [ ] **CSS clases aplicadas:**
  - [ ] Botón CTA tiene clase `gano-menu-cta`
  - [ ] Separador del dropdown Ecosistemas tiene clase `menu-separator`
- [ ] **Responsive verificado:**
  - [ ] Escritorio (>1024px): 5 ítems + dropdown visibles
  - [ ] Móvil (<768px): menú hamburguesa funcional, acordeones desplegables

### Especificación conocida incompleta

**Nota:** No hay referencias a sub-ítems específicos (`/ecosistemas/nucleo-prime`, `/pilares#infraestructura`) que requieran páginas o anclas especiales:
- Eco-sistema subpáginas: parte de Fase 4 (GANO_PFID_* y Reseller)
- Anclas en `/pilares`: requieren verificación post-implementación SOTA pages

**Recomendación:** Marcar estas como verificables en navegador (no bloqueantes para schema de navegación).

---

## 2. AUDITORÍA — FOOTER Y CONTACTO

### Hallazgos críticos (de `memory/content/footer-contact-audit-wave3.md`)

| Ubicación | Campo | Estado actual | Requerido |
|-----------|-------|---------------|----------|
| **Footer Elementor** | Dirección | 🔴 USA falsa (Grand Rapids, MI) | [PENDIENTE DIEGO] Colombia |
| **Footer Elementor** | Teléfono | 🔴 USA falso (+1-314-892-2600) | [PENDIENTE DIEGO] +57 XXX |
| **Footer Elementor** | Email | ✅ hola@gano.digital | Sin cambio |
| **wp-admin → Gano SEO** | `gano_seo_phone` | 🔴 Vacío | [PENDIENTE DIEGO] |
| **wp-admin → Gano SEO** | `gano_seo_whatsapp` | 🔴 Vacío | [PENDIENTE DIEGO] |
| **wp-admin → Gano SEO** | `gano_seo_nit` | 🔴 Vacío | [PENDIENTE DIEGO] |
| **gano-phase3-content installer** | Página /contacto dirección | 🟡 "Calle 184 #18-22" | Confirmar o ajustar antes de activar |
| **gano-phase3-content installer** | Horarios atención | 🟡 Lun-Vie 8-18, Sáb 9-13 | Verificar contra operación real |

### Acción requerida (BLOQUEADOR — Requiere Diego)

**TAREA HUMANA — No es automatizable:**
Diego debe proveer en wp-admin antes de publicar contacto:

1. **Dirección colombiana real** (mínimo: ciudad + código postal)
2. **Teléfono local** (formato: +57 XXX XXX XXXX)
3. **NIT de Gano Digital SAS** (formato: 9XX.XXX.XXX-X)
4. **Número WhatsApp** (para schema contacto)
5. **Horarios verificados** de atención (para página contacto)

**Dónde se configuran:**
```bash
# Opción A — WP-CLI (si Diego acceso SSH):
wp option update gano_seo_phone     '+57 300 123 4567'
wp option update gano_seo_whatsapp  '573001234567'
wp option update gano_seo_nit        '900.123.456-7'
wp option update gano_seo_street     'Calle 184 #18-22, Bogotá, Colombia'
wp option update gano_seo_postal     '110111'

# Opción B — wp-admin → Ajustes → Gano SEO (UI)
```

**Impacto si no se completa:**
- Footer muestra datos falsos → desconfianza usuario
- Schema Organization vacío en SEO → afecta SERP
- Página contacto con placeholders → no es creíble
- Formulario contacto no puede validar teléfono real

### Verificación post-datos

Una vez Diego complete los datos:

- [ ] Footer en vivo refleja dirección real (sin Grand Rapids)
- [ ] Schema Organization en HTML incluye `telephone`, `taxID` (NIT)
- [ ] Página `/contacto` muestra horarios y dirección correcta
- [ ] Alerta en wp-admin `gano-security.php` desaparece (placeholders removidos)

---

## 3. FAQ SCHEMA — INYECCIÓN EN GANO-SEO.PHP

### Estado actual

**Archivo:** `wp-content/mu-plugins/gano-seo.php`

✅ **Ya implementado:**
- Función `gano_build_faq_schema()` (línea 199) — construye schema FAQPage
- Array `$faq_base` (línea 204) — **5 preguntas base** actuales
- Filtro `gano_faq_questions` (línea 234) — permite agregar preguntas vía `add_filter()`
- Output a `wp_head` (línea 262) — JSON-LD inyectado correctamente

**Estructura actual de pregunta:**
```php
array(
    'question' => '¿Pregunta en español?',
    'answer'   => 'Respuesta completa en español.',
)
```

### 10 Candidatos para agregar

Fuente: `memory/content/faq-schema-candidates-wave3.md`

| ID | Pregunta | Estado | Requiere |
|-----|----------|--------|----------|
| C01 | ¿Qué métodos de pago aceptan? | ✏️ Borrador | Verificar Wompi activos |
| C02 | ¿Cada cuánto backups? | ✏️ Borrador | SLA del proveedor |
| C03 | ¿Qué soporte incluye? | ✏️ Borrador | Horarios + canales operativos |
| C04 | ¿Necesito conocimientos técnicos? | ✏️ Borrador | Confirmar panel de control |
| C05 | ¿Quién factura y cómo renovación? | ✏️ Borrador | NIT + días cancelación |
| C06 | ¿Servidores en Colombia? | ✏️ Borrador | Ubicación datacenter (NDA) |
| C07 | ¿Puedo escalar si crece? | ✏️ Borrador | Política upgrade sin penalización |
| C08 | ¿Qué SLA de disponibilidad? | ✏️ Borrador | Verificar SLA reseller |
| C09 | ¿Diferencia Prime vs SOTA? | ✏️ Borrador | Completar con precios COP |
| C10 | ¿Por qué WordPress? | ✏️ Borrador | % mercado WP actual |

### Opción A — Inyección vía filtro (recomendado, sin editar MU plugin)

**Ubicación:** `wp-content/themes/gano-child/functions.php`

Agregar al final del archivo:

```php
/**
 * Extiende FAQ schema con 10 candidatos de Wave 3.
 * Mantiene las 5 preguntas base + añade 10 nuevas.
 * Notas: placeholders [X] se reemplazan con datos reales post-Diego.
 * Ver: memory/content/faq-schema-candidates-wave3.md
 */
add_filter( 'gano_faq_questions', function( array $questions ): array {
    $candidates = array(
        // C01 — Métodos de pago
        array(
            'question' => '¿Qué métodos de pago aceptan para contratar un ecosistema?',
            'answer'   => 'Procesamos pagos en pesos colombianos (COP) a través de Wompi: PSE, tarjeta de crédito, tarjeta débito, Nequi y Daviplata. Tu transacción y facturación quedan en Colombia, sin conversiones de moneda.',
        ),
        // C02 — Backups
        array(
            'question' => '¿Cada cuánto hacen copias de seguridad y cómo recupero mi sitio si algo falla?',
            'answer'   => 'Nuestros planes incluyen respaldos automáticos con frecuencia mínima diaria. Si necesitas restaurar, el proceso parte de los snapshots de tu ecosistema; el tiempo objetivo de recuperación es inferior a [X] horas según el plan contratado. Bastión SOTA incluye los intervalos de respaldo más cortos de la familia.',
        ),
        // C03 — Soporte técnico
        array(
            'question' => '¿Qué soporte técnico incluye cada plan y en qué horarios opera?',
            'answer'   => 'Todos los ecosistemas incluyen soporte por ticket en español. Núcleo Prime cubre incidencias en horario hábil Colombia; Fortaleza Delta amplía la cobertura a través de nuestro portal de cliente; Bastión SOTA tiene prioridad alta con tiempos de respuesta objetivo menores a [X] horas. Brindamos acompañamiento en español, con foco WordPress.',
        ),
        // C04 — Conocimientos técnicos
        array(
            'question' => '¿Necesito saber programación o administración de servidores para gestionar mi plan?',
            'answer'   => 'No. Los ecosistemas de Gano Digital están diseñados para que puedas operar tu WordPress sin tocar el servidor. El panel de control es visual, la configuración inicial la hacemos contigo y el monitoreo proactivo actúa antes de que notes un problema.',
        ),
        // C05 — Facturación
        array(
            'question' => '¿Quién factura el servicio y cómo funciona la renovación?',
            'answer'   => 'La facturación se emite a través de Gano Digital (NIT [NIT]), operando como proveedor de servicios de infraestructura WordPress respaldado en un programa de reseller de clase mundial. Recibirás tu factura en COP al correo registrado. Las renovaciones son automáticas; puedes cancelar con [X] días de anticipación según los términos del plan.',
        ),
        // C06 — Servidores en Colombia
        array(
            'question' => '¿Sus servidores están ubicados físicamente en Colombia?',
            'answer'   => 'Operamos sobre infraestructura de clase empresarial con presencia en centros de datos que cubren LATAM. La facturación, el soporte y la relación contractual son 100 % colombianos. Si la ubicación física del servidor es un requisito regulatorio para tu organización, escríbenos y analizamos la arquitectura más adecuada.',
        ),
        // C07 — Escalabilidad
        array(
            'question' => '¿Puedo cambiar de ecosistema si mi negocio crece?',
            'answer'   => 'Sí. El camino natural es Núcleo Prime → Fortaleza Delta → Bastión SOTA. El cambio de ecosistema se coordina con el equipo de Gano Digital; migramos los recursos sin afectar la disponibilidad de tu sitio. No hay penalización por escalar.',
        ),
        // C08 — SLA disponibilidad
        array(
            'question' => '¿Qué nivel de disponibilidad (uptime) garantizan?',
            'answer'   => 'Nuestro objetivo de disponibilidad es del 99,9 % mensual sobre la plataforma base. Bastión SOTA incorpora redundancia adicional para acercarse al 99,99 %. Los SLA formales se detallan en el contrato de servicio de cada ecosistema y están respaldados por los acuerdos del proveedor de infraestructura.',
        ),
        // C09 — Diferencia Prime vs SOTA
        array(
            'question' => '¿Cuál es la diferencia principal entre el ecosistema Núcleo Prime y Bastión SOTA?',
            'answer'   => 'Núcleo Prime es la base de alto rendimiento: almacenamiento NVMe, WordPress optimizado y seguridad esencial, ideal para negocios en crecimiento. Bastión SOTA es la capa de resiliencia total: redundancia activa, respaldos con mayor frecuencia, soporte prioritario y monitoreo avanzado, pensado para operaciones críticas que no pueden tolerar interrupciones.',
        ),
        // C10 — Por qué WordPress
        array(
            'question' => '¿Por qué construir sobre WordPress en lugar de otra plataforma?',
            'answer'   => 'WordPress impulsa más del 43 % de la web mundial, cuenta con el ecosistema de plugins y temas más grande del mercado, y ofrece control total sobre tu contenido y datos. Gano Digital especializa su infraestructura exclusivamente en WordPress para llevar esa plataforma a niveles enterprise: optimizaciones a nivel de servidor, caching inteligente y hardening específico que una solución genérica no puede ofrecer.',
        ),
    );

    // Fusionar con las 5 base + los 10 candidatos
    return array_merge( $questions, $candidates );
}, 10, 1 );
```

**Ventajas:**
- ✅ No modifica MU plugin (evita conflictos en updates)
- ✅ Fácil de revisar y auditar
- ✅ Placeholders `[X]`, `[NIT]` visibles → recordatorio de campos requeridos
- ✅ Todas las preguntas visible en vivo al momento

**Placeholders a reemplazar (post-aprobación Diego):**
- `[X]` → SLA real de recuperación/respuesta (ej: "4", "8", "24")
- `[NIT]` → NIT de Gano Digital (ej: "900.123.456-7")
- "Wompi" → Confirmar si es el método actual (si es Reseller/GoDaddy, ajustar a "GoDaddy")

### Opción B — Edición directa del MU plugin (alternativa)

Si Diego prefiere editar `gano-seo.php` directamente:
- Línea 204: Array `$faq_base` — agregar 10 items aquí
- Validar JSON LD con https://validator.schema.org

**No recomendado** (MU plugins actualizan con menos frecuencia, pero menos estándar que theme functions.php).

### Validación post-inyección

```bash
# 1. Verificar que el schema se emite en homepage
curl -s https://gano.digital | grep -A 50 '"@type":"FAQPage"'

# 2. Con Google Schema Validator (manual)
# → Ir a https://validator.schema.org/
# → Pegar HTML de homepage
# → Verificar 15 preguntas (5 base + 10 nuevas) en schema
```

---

## 4. DEFINICIÓN DE HECHO — Checklist de validación

### Navegación

- [ ] 5 ítems P0 visibles en header (Ecosistemas, Pilares, Nosotros, Contacto, Elegir plan →)
- [ ] Dropdown Ecosistemas muestra: Núcleo Prime, Fortaleza Delta, Bastión SOTA, separador, Comparar todos
- [ ] Dropdown Pilares muestra: Infraestructura, Seguridad, Rendimiento, Inteligencia Artificial, Estrategia
- [ ] Botón CTA naranja con `:focus-visible` dorado
- [ ] Menú hamburguesa funcional en móvil (<768px)
- [ ] Acordeones de dropdowns en móvil
- [ ] Menú footer incluye: Términos, Privacidad, SLA, Hosting WordPress Colombia

### Footer

- [ ] Dirección colombiana real (sin Grand Rapids)
- [ ] Teléfono local +57 (sin USA)
- [ ] Email: hola@gano.digital ✅
- [ ] Alerta `gano-security.php` desaparece (placeholders removidos)
- [ ] Copyright dinámica: "© 2026 Gano Digital SAS. Todos los derechos reservados."
- [ ] Links legales funcionales

### FAQ Schema

- [ ] 15 preguntas en FAQPage (5 base + 10 candidatos)
- [ ] JSON-LD válido en https://validator.schema.org
- [ ] Preguntas y respuestas en español
- [ ] Placeholders `[X]`, `[NIT]` reemplazados con valores reales
- [ ] Método de pago correcto (Wompi vs GoDaddy vs Reseller)
- [ ] Schema visible en view-source homepage

---

## 5. CRONOGRAMA — Orden sugerido de implementación

### Fase 1: Navegación (30 min — sin bloqueos)
1. Crear menú en wp-admin con 5 ítems P0
2. Asignar a ubicación `primary`
3. Configurar dropdowns + botón CTA
4. Probar responsive

### Fase 2: Footer (bloqueador — requiere Diego)
1. Diego: Completar datos en wp-admin → Gano SEO
2. Editar footer Elementor (reemplazar placeholders)
3. Verificar alerta de seguridad desaparece
4. Probar página contacto post-activación gano-phase3

### Fase 3: FAQ Schema (30 min — no bloqueante)
1. Copiar bloque `add_filter( 'gano_faq_questions'...` a `functions.php`
2. Revisar placeholders `[X]`, `[NIT]`, método pago
3. Validar en https://validator.schema.org
4. Publicar en producción

### Total: 1–2 horas (con datos de Diego)

---

## 6. ARCHIVOS CLAVE

| Archivo | Propósito |
|---------|-----------|
| `memory/content/navigation-primary-spec-2026.md` | Especificación menú + checklist |
| `memory/content/footer-contact-audit-wave3.md` | Auditoría placeholders + datos faltantes |
| `memory/content/faq-schema-candidates-wave3.md` | 10 candidatos FAQ |
| `wp-content/mu-plugins/gano-seo.php` | FAQ schema builder (ya soporta filtro) |
| `wp-content/themes/gano-child/functions.php` | Donde inyectar 10 FAQ via add_filter |
| `wp-content/themes/gano-child/style.css` | CSS para `.gano-menu-cta`, `.menu-separator` (líneas 1706–1737) |
| `wp-content/plugins/gano-phase3-content/` | Página /contacto installer (datos provisionales) |

---

## 7. NOTAS DE ENTREGA

**Para Diego (humano):**
1. Navegación: creación en wp-admin es straightforward (30 min)
2. Footer: requiere datos reales (dirección, teléfono, NIT) — es bloqueador
3. FAQ: pre-redactadas, solo requiere reemplazar placeholders `[X]` → SLAs reales

**Para Claude (continuidad):**
- Si Diego completa datos Fase 2 antes de próxima sesión, ejecutar Fase 3 FAQ
- Validar JSON-LD con schema.org validator antes de publicar
- Confirmar que todos los 15 FAQ son visibles en homepage post-publicación

---

## ✅ Definition of Done

La tarea se cierra cuando:
- [ ] Navegación primaria estructura canónica en wp-admin (5 P0 + sub-ítems)
- [ ] CSS clases aplicadas (.gano-menu-cta, .menu-separator)
- [ ] Footer sin placeholders falsos (dirección Colombia, teléfono local)
- [ ] FAQ Schema con 15 preguntas (5 base + 10 candidatos)
- [ ] Schema FAQPage válido (https://validator.schema.org)
- [ ] Todas las preguntas visibles en homepage en vivo
- [ ] Alerta `gano-security.php` desaparece (sin placeholders)
- [ ] Página /contacto lista para activación (post-gano-phase3)

---

**Generado por:** Claude Dispatch (cd-content-005 preparation)  
**Última actualización:** 2026-04-19 21:10 UTC  
**Próxima revisión:** Post-implementación Fase 1-3
