# Tarea cd-content-004 — Wave contenidos: Trust pages bundle + Legal

**ID:** `cd-content-004`  
**Prioridad:** P2  
**Requiere humano:** SÍ (datos legales + contacto)  
**Generado:** 2026-04-19  
**Estado:** Listo para implementación por Diego en wp-admin + revisión legal

---

## Objetivo

Crear un paquete coherente de 5 páginas de confianza y transparencia:
1. `/nosotros` — Identidad, transparencia Reseller, equipo
2. `/contacto` — Formulario + datos de contacto verificables
3. `/legal/terminos-y-condiciones` — Contrato cliente ↔ Gano Digital
4. `/legal/politica-de-privacidad` — Cumplimiento Ley 1581/2012 + GDPR donde aplique
5. `/legal/acuerdo-de-nivel-de-servicio` (SLA) — Compromisos de disponibilidad + incidentes

**Objetivos secundarios:**
- Todos los placeholders reemplazados con datos reales
- Enlaces cruzados coherentes (footer ↔ legal ↔ contacto)
- Disclaimer Reseller GoDaddy presente y consistente
- Cumplimiento normativo SIC Colombia + Ley 1581
- Accesibilidad WCAG AA

**Duración estimada:** 4–6 horas (Diego) + 2–4 semanas revisión legal

---

## 📋 Estructura por página — Checklist de implementación

### Página 1: `/nosotros`

**URL:** gano.digital/nosotros  
**Template:** `wp-content/themes/gano-child/templates/page-nosotros.php` (auto-asignado si existe)  
**Método recomendado:** PHP template OR Elementor (ver sección "Notas de implementación")

**Necesario:**

#### Bloque 1 — HERO
- [ ] H1: "Hosting en serio. Sin rodeos."
- [ ] Subheading: "El mercado colombiano merece opciones de hosting que no requieran leer la letra pequeña tres veces."
- [ ] Párrafo introductorio (180–220 palabras) resumiendo propuesta: infraestructura + soporte experto + transparencia
- [ ] CTA primario: "Ver nuestros ecosistemas" → `/ecosistemas`
- [ ] Clase CSS: `gano-el-hero-trust`

#### Bloque 2 — IDENTIDAD (Quiénes somos)
- [ ] H2: "Quiénes somos"
- [ ] Párrafo: "Operador de ecosistemas WordPress de alto rendimiento para negocios en Colombia y LATAM."
- [ ] Párrafo: "No somos un datacenter; somos el equipo que configura, protege y sostiene tu infraestructura."
- [ ] Enlace cruzado: "Conoce nuestro modelo de infraestructura" → [Bloque 4]
- [ ] Clase CSS: `gano-el-section-identity`

#### Bloque 3 — DIFERENCIADORES (bullets de confianza)
- [ ] H2: "Por qué Gano"
- [ ] Grid 2×3 de tarjetas con diferenciadores (usar Opción B de trust-and-reseller-copy-wave3.md):
  - Español y COP
  - WordPress con foco
  - Transparencia reseller
  - Hardening incluido
  - Soberanía del sitio
  - Facturación Colombia
- [ ] Cada tarjeta: título + descripción corta (40–60 palabras)
- [ ] Clase CSS: `gano-el-trust-card` (cada tarjeta)
- [ ] Clase contenedor: `gano-el-trust-grid`

#### Bloque 4 — TRANSPARENCIA RESELLER (sección larga)
- [ ] H2: "Sobre nuestra infraestructura"
- [ ] Párrafo introductorio explicando modelo Reseller (ver trust-pages-bundle-2026.md §1.1)
- [ ] Lista viñetada: "Lo que sí hacemos directamente" (4 items)
- [ ] Párrafo de cierre: "La transparencia es parte del servicio..."
- [ ] Enlace cruzado: "Ver nuestros Términos" → `/legal/terminos-y-condiciones`
- [ ] Enlace cruzado: "Conocer nuestro SLA" → `/legal/acuerdo-de-nivel-de-servicio`
- [ ] Clase CSS: `gano-el-transparency-block`

#### Bloque 5 — EQUIPO (OPCIONAL, solo si datos verificables)
- [ ] H2: "El equipo"
- [ ] `[PENDIENTE DIEGO]`: nombre + rol + bio real del fundador/equipo
- [ ] **REGLA:** Si no hay bios verificables, omitir sección. No usar fotos de stock.
- [ ] Si se incluye: foto (square, 300×300px) + nombre + rol + 2–3 líneas de experiencia
- [ ] Clase CSS: `gano-el-team-card`

#### Bloque 6 — CTA FINAL
- [ ] H2: "¿Listo para empezar?"
- [ ] Botón primario: "Ver ecosistemas" → `/ecosistemas`
- [ ] Botón secundario: "Habla con el equipo" → `/contacto`
- [ ] Clase CSS: `gano-el-final-cta-trust`

**Tipografía:**
- [ ] H1: `--gano-fs-5xl` (48 px)
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] Body: `--gano-fs-base` (16 px)
- [ ] Card title: `--gano-fs-3xl` (30 px)

---

### Página 2: `/contacto`

**URL:** gano.digital/contacto  
**Template:** `wp-content/themes/gano-child/templates/page-contacto.php`  
**Método recomendado:** PHP template + Elementor para formulario

**Necesario:**

#### Bloque 1 — HERO
- [ ] H1: "Antes de comprar, habla con nosotros"
- [ ] Subheading: "Resolvemos dudas técnicas y comerciales sin formularios de ventas agresivos."
- [ ] Clase CSS: `gano-el-hero-contact`

#### Bloque 2 — FORMULARIO DE CONTACTO
- [ ] Formulario HTML5 con campos:
  - [ ] Nombre (requerido, min 3 caracteres)
  - [ ] Empresa (opcional)
  - [ ] Correo (requerido, validación email)
  - [ ] Asunto (requerido, desplegable con opciones: Comercial, Técnico, Otra)
  - [ ] Mensaje (requerido, textarea min 20 caracteres)
  - [ ] Checkbox: "He leído la Política de Privacidad" (requerido, enlace → `/legal/politica-de-privacidad`)
- [ ] Botón submit: "Enviar" (color naranja, clase `gano-btn`)
- [ ] Confirmación post-envío: "Gracias. Responderemos en `[PENDIENTE DIEGO: X horas hábiles]`."
- [ ] Clase CSS contenedor: `gano-el-form-contact`
- [ ] Validación cliente + servidor (sin guardar datos sensibles en logs sin cifrado)

#### Bloque 3 — DATOS DE CONTACTO VERIFICABLES
- [ ] H2: "O contacta directamente"
- [ ] Grid 3 columnas:
  - [ ] **Comercial:** hola@gano.digital · Horario: `[PENDIENTE DIEGO: confirmar]`
  - [ ] **Soporte técnico:** soporte@gano.digital · Horario: `[PENDIENTE DIEGO: 24/7 o acotado]`
  - [ ] **Teléfono/WhatsApp:** `[PENDIENTE DIEGO: +57 XXX XXX XXXX]` · Horario: igual comercial
- [ ] Cada card tiene ícono (correo/teléfono/chat) + texto
- [ ] Clase CSS: `gano-el-contact-method`

#### Bloque 4 — MICROCOPY DE CONFIANZA
- [ ] Párrafo bajo formulario: "No compartimos tu información con terceros. Respondemos dentro de `[PENDIENTE DIEGO: X horas hábiles]`."
- [ ] Enlace cruzado: "Política de Privacidad" → `/legal/politica-de-privacidad`
- [ ] Clase CSS: `gano-el-trust-microcopy`

#### Bloque 5 — CTA SECUNDARIO
- [ ] Botón: "¿Ya sabes qué necesitas? Ver planes y precios" → `/ecosistemas`
- [ ] Clase CSS: `gano-el-secondary-cta-contact`

**Tipografía:**
- [ ] H1: `--gano-fs-5xl` (48 px)
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] Body: `--gano-fs-base` (16 px)
- [ ] Label formulario: `--gano-fs-sm` (14 px)

---

### Página 3: `/legal/terminos-y-condiciones`

**URL:** gano.digital/legal/terminos-y-condiciones  
**Template:** `wp-content/themes/gano-child/templates/page-terminos.php`  
**Método recomendado:** PHP template (contenido legal requiere versioning git)

**CRÍTICO:** Antes de publicar, someter a revisión de abogado con conocimiento de Ley 1581/2012 y normativa SIC Colombia.

**Necesario:**

#### Estructura general
- [ ] Versión de documento + fecha (ej. "Términos y Condiciones — Vigentes desde 2026-04-XX")
- [ ] Tabla de contenidos (enlaces internos a cada sección)
- [ ] Numeración clara (1. Identificación, 2. Objeto del servicio, etc.)

#### Secciones obligatorias

1. **Identificación del prestador**
   - [ ] Razón social: `[PENDIENTE DIEGO: Gano Digital SAS o persona natural]`
   - [ ] NIT: `[PENDIENTE DIEGO: NIT]-[dígito verificación DIAN]`
   - [ ] Domicilio: `[PENDIENTE DIEGO: dirección fiscal Colombia]`
   - [ ] Correo: legal@gano.digital

2. **Objeto del servicio**
   - [ ] Descripción: "Provisión de ecosistemas de hosting WordPress administrado sobre infraestructura aprovisionada a través del programa de reseller de GoDaddy, Inc."

3. **Transparencia de infraestructura (CLÁUSULA OBLIGATORIA)**
   - [ ] Texto completo (ver trust-pages-bundle-2026.md §1.3): "Los servicios de Gano Digital son provistos sobre infraestructura operada por GoDaddy..."
   - [ ] Enlace: godaddy.com/es/legal

4. **Precios y facturación**
   - [ ] "Los precios están expresados en pesos colombianos (COP)."
   - [ ] `[PENDIENTE DIEGO]`: ¿Se emite factura electrónica DIAN? ¿Cuál es el ciclo de facturación?
   - [ ] Indicar si hay cargos por setup, instalación o migración

5. **Disponibilidad (SLA)**
   - [ ] Objetivo de disponibilidad: `[PENDIENTE DIEGO: % confirmado con GoDaddy]`
   - [ ] **NO PUBLICAR sin tener SLA escrito del plan GoDaddy**
   - [ ] Ventana de mantenimiento: `[PENDIENTE DIEGO: ej. domingos 02:00–04:00 hora Colombia, aviso 48h]`

6. **Soporte**
   - [ ] Nivel de servicio (comercial, técnico, urgencia)
   - [ ] Canales: email (soporte@gano.digital) + `[PENDIENTE DIEGO: WhatsApp/ticket URL]`
   - [ ] Tiempos de respuesta por nivel: `[PENDIENTE DIEGO: definir]`

7. **Cancelación y portabilidad**
   - [ ] Proceso de cancelación: `[PENDIENTE DIEGO: con preaviso de X días]`
   - [ ] Política de reembolsos: `[PENDIENTE DIEGO: parcial/total, condiciones]`
   - [ ] Exportación de datos: "El cliente tiene derecho a solicitar la exportación completa de su sitio WordPress."

8. **Limitación de responsabilidad**
   - [ ] "Gano Digital no responde por caídas causadas por la infraestructura de GoDaddy más allá de lo que GoDaddy cubre en su SLA propio."

9. **Ley aplicable**
   - [ ] "Ley aplicable: República de Colombia"
   - [ ] "Jurisdicción: Bogotá D.C."

10. **Enlaces de pie de página**
    - [ ] "Política de Privacidad" → `/legal/politica-de-privacidad`
    - [ ] "SLA" → `/legal/acuerdo-de-nivel-de-servicio`
    - [ ] "Contáctanos" → `/contacto`

**Tipografía:**
- [ ] H1: `--gano-fs-5xl` (48 px)
- [ ] H2 (sección): `--gano-fs-4xl` (36 px)
- [ ] Body: `--gano-fs-base` (16 px)
- [ ] Destacado legal (cláusulas clave): **negrita** con `--gano-gold` color

**Clase CSS:**
- [ ] Contenedor: `gano-el-legal-page`
- [ ] Tabla de contenidos: `gano-el-toc`
- [ ] Sección: `gano-el-legal-section`

---

### Página 4: `/legal/politica-de-privacidad`

**URL:** gano.digital/legal/politica-de-privacidad  
**Template:** `wp-content/themes/gano-child/templates/page-privacidad.php`  
**Método recomendado:** PHP template

**CRÍTICO:** Someter a revisión de abogado especializado en Ley 1581/2012 (habeas data Colombia) y GDPR antes de publicar.

**Necesario:**

#### Estructura general
- [ ] Versión + fecha de vigencia: "Política de Privacidad — Vigente desde 2026-04-XX"
- [ ] Tabla de contenidos

#### Secciones obligatorias

1. **Responsable del tratamiento**
   - [ ] `[PENDIENTE DIEGO]`: Gano Digital SAS / nombre completo
   - [ ] NIT: `[PENDIENTE DIEGO]`
   - [ ] Dirección: `[PENDIENTE DIEGO]`
   - [ ] Email: legal@gano.digital

2. **Datos que se recopilan**
   - [ ] Nombre, correo, empresa
   - [ ] Datos de navegación (logs, cookies técnicas)
   - [ ] Datos de pago procesados por GoDaddy (Gano Digital **NO** almacena datos de tarjeta)

3. **Finalidad del tratamiento**
   - [ ] Prestación del servicio
   - [ ] Soporte técnico
   - [ ] Facturación
   - [ ] Mejora del sitio

4. **Base legal (Art. 6 GDPR + Ley 1581)**
   - [ ] Ejecución del contrato
   - [ ] Interés legítimo para análisis de uso
   - [ ] Consentimiento (si aplica banner de cookies)

5. **Transferencias internacionales**
   - [ ] "Los datos de alojamiento se procesan en infraestructura de GoDaddy (EE.UU. / UE). Gano Digital informa al cliente al momento de contratar."

6. **Derechos del titular**
   - [ ] Acceso a datos
   - [ ] Rectificación
   - [ ] Supresión (derecho al olvido)
   - [ ] Portabilidad
   - [ ] Oposición
   - [ ] Ejercicio: "legal@gano.digital"

7. **Cookies**
   - [ ] Técnicas (sesión, seguridad)
   - [ ] Analíticas (solo si se usan): `[PENDIENTE DIEGO: herramienta y tracking details]`
   - [ ] **Nota:** Si se usa Google Analytics/Hotjar, incluir enlace a política de cookies y conforme con ePrivacy

8. **Retención de datos**
   - [ ] Durante vigencia del contrato
   - [ ] Post-contrato: `[PENDIENTE DIEGO: X años según normativa DIAN/SIC]`

9. **Enlaces de pie**
   - [ ] "Términos y Condiciones" → `/legal/terminos-y-condiciones`
   - [ ] "Contacto / Ejercer derechos" → `/contacto`

**Tipografía:** igual a Términos y Condiciones

**Clase CSS:** `gano-el-privacy-page`

---

### Página 5: `/legal/acuerdo-de-nivel-de-servicio` (SLA)

**URL:** gano.digital/legal/acuerdo-de-nivel-de-servicio  
**Template:** `wp-content/themes/gano-child/templates/page-sla.php`  
**Método recomendado:** PHP template

**BLOQUEANTE:** NO PUBLICAR con porcentajes de disponibilidad hasta confirmar el SLA real del plan GoDaddy contratado.

**Necesario:**

#### Bloque 1 — DISCLAIMER DE APERTURA
- [ ] H2: "Compromisos de disponibilidad"
- [ ] Párrafo: "Los compromisos de disponibilidad de Gano Digital están condicionados a los niveles de servicio de GoDaddy como proveedor de infraestructura. Gano Digital actúa como operador autorizado y no puede garantizar disponibilidad superior a la que GoDaddy garantiza en su propio SLA."
- [ ] Enlace: godaddy.com/es/legal
- [ ] Clase CSS: `gano-el-sla-disclaimer`

#### Bloque 2 — OBJETIVO DE DISPONIBILIDAD
- [ ] H2: "Disponibilidad objetivo"
- [ ] Texto: `[PENDIENTE DIEGO: definir % real]` (ejemplo: "Nos comprometemos a una disponibilidad de 99,5% medida en uptime del servicio.")
- [ ] **NOTA:** No usar "99,9%" sin respaldo escrito de GoDaddy

#### Bloque 3 — VENTANA DE MANTENIMIENTO
- [ ] H2: "Ventana de mantenimiento"
- [ ] Texto: `[PENDIENTE DIEGO: definir]` (ejemplo: "Domingos 02:00–04:00 hora Colombia, con aviso previo de 48 horas.")

#### Bloque 4 — TIEMPOS DE RESPUESTA A INCIDENTES
- [ ] H2: "Tiempos de respuesta"
- [ ] Tabla con niveles de severidad:
  - [ ] Crítico (sitio caído): `[PENDIENTE DIEGO: X minutos/horas]`
  - [ ] Alto (degradación severa): `[PENDIENTE DIEGO]`
  - [ ] Medio (funcionalidad parcial): `[PENDIENTE DIEGO]`
  - [ ] Bajo (consultas, mejoras): `[PENDIENTE DIEGO]`
- [ ] Clase CSS: `gano-el-sla-table`

#### Bloque 5 — CANAL DE REPORTE
- [ ] H2: "Reportar un incidente"
- [ ] Texto: "Correo: soporte@gano.digital"
- [ ] `[PENDIENTE DIEGO]`: ¿Incluir WhatsApp/ticket URL?

#### Bloque 6 — EXCLUSIONES
- [ ] H2: "Exclusiones del SLA"
- [ ] Lista de casos no cubiertos:
  - Ataques DDoS que superen capacidad de mitigación
  - Fallos de infraestructura GoDaddy fuera del control de Gano
  - Modificaciones del cliente que generan incidencia
  - Mal uso del servicio

#### Bloque 7 — COMPENSACIÓN POR INCUMPLIMIENTO
- [ ] H2: "Compensación"
- [ ] `[PENDIENTE DIEGO: definir]` (ejemplo: "Crédito de servicio equivalente a X días de facturación si se incumple SLA.")

#### Bloque 8 — ENLACES DE PIE
- [ ] "Reportar incidente" → `/contacto`
- [ ] "Términos y Condiciones" → `/legal/terminos-y-condiciones`

**Tipografía:**
- [ ] H1: `--gano-fs-5xl` (48 px)
- [ ] H2: `--gano-fs-4xl` (36 px)
- [ ] Table: `--gano-fs-sm` (14 px)

**Clase CSS:** `gano-el-sla-page`

---

## 🎨 Clases CSS requeridas — Verificación

Todas presentes en `wp-content/themes/gano-child/style.css`:

| Clase | Uso | Estado |
|-------|-----|--------|
| `gano-el-hero-trust` | Hero `/nosotros` | ✅ Existente o crear |
| `gano-el-trust-card` | Tarjetas diferenciadores | ✅ Existente o crear |
| `gano-el-trust-grid` | Grid de tarjetas | ✅ Existente o crear |
| `gano-el-form-contact` | Contenedor formulario | ✅ Existente o crear |
| `gano-el-contact-method` | Cards contacto directo | ✅ Existente o crear |
| `gano-el-legal-page` | Contenedor página legal | ✅ Existente o crear |
| `gano-el-legal-section` | Sección dentro legal | ✅ Existente o crear |
| `gano-el-toc` | Tabla de contenidos | ✅ Existente o crear |
| `gano-el-sla-table` | Tabla SLA tiempos | ✅ Existente o crear |
| `gano-el-privacy-page` | Contenedor política | ✅ Existente o crear |
| `gano-el-sla-page` | Contenedor SLA | ✅ Existente o crear |
| `gano-btn` | Botones CTA | ✅ Ya existe (cd-content-001) |

**Verificación:**
- [ ] Abrir `wp-content/themes/gano-child/style.css`
- [ ] Confirmar que todas las clases están presentes
- [ ] Si falta alguna: crear entrada con estilos base

---

## 📝 Placeholders pendientes — Resumen

> Ninguno de estos valores puede ser inventado. Completar ANTES de publicar cualquier página.

```yaml
# Identificación legal (CRÍTICO)
razon_social:            "[PENDIENTE DIEGO]"   # Gano Digital SAS u otra
nit:                     "[PENDIENTE DIEGO]"   # NIT + dígito verificación
direccion_fiscal:        "[PENDIENTE DIEGO]"   # Dirección registrada Colombia
codigo_postal:           "[PENDIENTE DIEGO]"

# Contacto (CRÍTICO)
telefono:                "[PENDIENTE DIEGO]"   # +57 XXX XXX XXXX
whatsapp:                "[PENDIENTE DIEGO]"   # Solo dígitos
email_legal:             "legal@gano.digital"  # Confirmar que está activo
email_abuso:             "[PENDIENTE DIEGO]"   # abuse@gano.digital

# Horarios (CRÍTICO para /contacto)
horario_comercial:       "[PENDIENTE DIEGO]"   # Ej. Lun–Sáb 8:00–20:00
horario_soporte:         "[PENDIENTE DIEGO]"   # ¿24/7 por ticket? ¿Acotado?

# SLA (CRÍTICO — NO PUBLICAR SIN ESTO)
disponibilidad_objetivo:     "[PENDIENTE DIEGO]"  # % real respaldado GoDaddy
ventana_mantenimiento:       "[PENDIENTE DIEGO]"  # Ej. Dom 02:00–04:00 + aviso 48h
tiempo_respuesta_critico:    "[PENDIENTE DIEGO]"  # Minutos u horas
tiempo_respuesta_alto:       "[PENDIENTE DIEGO]"
tiempo_respuesta_medio:      "[PENDIENTE DIEGO]"
tiempo_respuesta_bajo:       "[PENDIENTE DIEGO]"
compensacion_incumplimiento: "[PENDIENTE DIEGO]"  # Crédito de servicio ¿cuántos días?

# Privacidad + legal
retencion_datos:         "[PENDIENTE DIEGO]"  # Años post-contrato
banner_cookies:          "[PENDIENTE DIEGO]"  # ¿Se usa analítica tracking?
factura_electronica:     "[PENDIENTE DIEGO]"  # ¿DIAN e-factura obligatorio?
horas_respuesta_contacto: "[PENDIENTE DIEGO]" # Ej. "dentro de 24 horas hábiles"

# Equipo (OPCIONAL — solo si datos verificables)
fundador_nombre:         "[PENDIENTE DIEGO]"
fundador_rol:            "[PENDIENTE DIEGO]"
fundador_bio:            "[PENDIENTE DIEGO]"   # 2–3 líneas experiencia
fundador_foto:           "[PENDIENTE DIEGO]"   # 300×300 px, square
```

---

## 📊 Enlace cruzado — Mapa completo

Todos los enlaces ya están documentados en `trust-pages-bundle-2026.md` §2. **Verificación:**

| Origen | Destino | Texto ancla | Verificado |
|--------|---------|-------------|-----------|
| Footer | `/legal/terminos-y-condiciones` | "Términos y Condiciones" | ☐ |
| Footer | `/legal/politica-de-privacidad` | "Política de Privacidad" | ☐ |
| Footer | `/legal/acuerdo-de-nivel-de-servicio` | "SLA" | ☐ |
| Footer | `/contacto` | "Escríbenos" | ☐ |
| `/nosotros` Bloque 2 | `/legal/terminos-y-condiciones` | "Términos y Condiciones" | ☐ |
| `/nosotros` Bloque 4 | `/legal/acuerdo-de-nivel-de-servicio` | "Ver nuestro SLA" | ☐ |
| `/nosotros` Bloque 6 | `/contacto` | "Habla con el equipo" | ☐ |
| `/contacto` Bloque 2 | `/legal/politica-de-privacidad` | "Política de Privacidad" | ☐ |
| `/contacto` Bloque 5 | `/ecosistemas` | "Ver planes y precios" | ☐ |
| `/legal/terminos-y-condiciones` pie | `/legal/politica-de-privacidad` | "Política de Privacidad" | ☐ |
| `/legal/terminos-y-condiciones` pie | `/legal/acuerdo-de-nivel-de-servicio` | "SLA" | ☐ |
| `/legal/terminos-y-condiciones` pie | `/contacto` | "Contáctanos" | ☐ |
| `/legal/politica-de-privacidad` pie | `/legal/terminos-y-condiciones` | "Términos y Condiciones" | ☐ |
| `/legal/politica-de-privacidad` pie | `/contacto` | "legal@gano.digital" | ☐ |
| `/legal/acuerdo-de-nivel-de-servicio` pie | `/contacto` | "Reportar incidente" | ☐ |
| `/legal/acuerdo-de-nivel-de-servicio` pie | `/legal/terminos-y-condiciones` | "Términos y Condiciones" | ☐ |

---

## 📝 Disclaimer Reseller — Versiones para pegar

Todos los disclaimers ya están en `trust-and-reseller-copy-wave3.md`. **Dónde ponerlas:**

- [ ] Bloque 4 `/nosotros` (versión larga)
- [ ] Footer (versión ultra-corta)
- [ ] Pie de cada página `/legal/*` (versión legal)
- [ ] `/legal/terminos-y-condiciones` cláusula obligatoria (versión larga legal)

---

## 🎯 Accesibilidad — WCAG AA Checklist

- [ ] Cada página legal tiene H1 único (título de página)
- [ ] Jerarquía clara: H1 → H2 (secciones) → H3 (subsecciones si aplica)
- [ ] Formulario `/contacto`:
  - [ ] Labels asociados a inputs (`<label for="campo">`)
  - [ ] Campos requeridos marcados con `required` + visual ✓ o *
  - [ ] Mensaje de error claro si validación falla
  - [ ] Foco visible en inputs (anillo dorado 3px)
  - [ ] Orden tab lógico (izquierda→derecha, arriba→abajo)
- [ ] Botones:
  - [ ] `:focus-visible` con anillo dorado (3 px offset 3 px)
  - [ ] Contraste mínimo 4.5:1 (texto ↔ fondo)
  - [ ] Texto descriptivo: "Enviar formulario" en lugar de "Submit"
- [ ] Enlaces:
  - [ ] Color distintivo + subrayado (no solo color)
  - [ ] `:hover` y `:active` claramente visibles
  - [ ] Enlace a políticas tiene `title="Abre en nueva pestaña"` si aplica
- [ ] Tablas (SLA):
  - [ ] Atributo `scope` en headers (`<th scope="col">` o `scope="row"`)
  - [ ] Contraste mínimo 4.5:1 entre celdas
- [ ] Sin movimiento/parpadeo > 3 Hz
- [ ] Imágenes (si se incluyen):
  - [ ] Atributo `alt` descriptivo en español
  - [ ] Imágenes decorativas con `alt=""` + `aria-hidden="true"`

**Validación:**
- [ ] Pasar cada página por https://wave.webaim.org/
- [ ] Chrome DevTools → Lighthouse pestaña Accessibility
- [ ] Expected result: 0 errores WAVE, Lighthouse accessibility ≥90

---

## 📊 Core Web Vitals — Objetivos por página

| Métrica | Objetivo | Acción | Verificado |
|---------|----------|--------|-----------|
| **LCP** (Largest Contentful Paint) | <2.5s | Imágenes hero optimizadas, lazy loading para imágenes abajo | ☐ |
| **INP** (Interaction to Next Paint) | <200ms | Formulario sin lag, sin scripts pesados en eventos | ☐ |
| **CLS** (Cumulative Layout Shift) | <0.1 | Dimensiones fijas en elementos dinámicos (formulario, modales) | ☐ |

**Post-implementación:**
- [ ] Testear cada página con Google PageSpeed Insights (mobile + desktop)
- [ ] Lighthouse ≥85 (performance)
- [ ] Validar con DebugBear si acceso disponible

---

## 🔄 Flujo de implementación (para Diego)

**Tiempo estimado:** 4–6 horas (Diego en wp-admin) + 2–4 semanas (abogado)

### Fase 1 — Preparación (30 min)
1. [ ] Leer `trust-pages-bundle-2026.md` completo
2. [ ] Recolectar datos pendientes (NIT, teléfono, dirección fiscal, horarios SLA)
3. [ ] Crear draft vacío de las 5 páginas en WordPress (status: draft)
4. [ ] Asignar templates PHP correctos en cada página (Atributos de página → Template)

### Fase 2 — Contenido `/nosotros` + `/contacto` (2 horas)
1. [ ] `/nosotros` — Bloques 1–6 (hero, identidad, diferenciadores, transparencia, equipo, CTA)
2. [ ] `/contacto` — Bloques 1–5 (hero, formulario, datos contacto, microcopy, CTA)
3. [ ] Añadir todas las clases CSS
4. [ ] Verificar enlaces cruzados internos

### Fase 3 — Páginas legales (2 horas)
1. [ ] `/legal/terminos-y-condiciones` — todas las secciones
2. [ ] `/legal/politica-de-privacidad` — todas las secciones
3. [ ] `/legal/acuerdo-de-nivel-de-servicio` — bloques 1–8
4. [ ] Reemplazar TODOS los placeholders reales
5. [ ] Crear tabla de contenidos (enlaces internos a cada sección)

### Fase 4 — Revisión legal (2–4 semanas, paralelo)
- [ ] Enviar `/legal/terminos-y-condiciones` + `/legal/politica-de-privacidad` a abogado especialista Ley 1581
- [ ] SLA: esperar confirmación escrita de GoDaddy sobre disponibilidad antes de publicar %
- [ ] Incorporar cambios del abogado sin delay

### Fase 5 — Validación (1.5 horas)
- [ ] Lighthouse check todas las páginas (mobile + desktop, ≥85)
- [ ] WAVE: 0 errores accesibilidad
- [ ] Formulario `/contacto`: prueba envío + confirmación
- [ ] Todos los enlaces cruzados funcionales (no errores 404)
- [ ] Responsiveness: escritorio + tablet + móvil

### Fase 6 — Publicación (30 min)
- [ ] Cambiar status draft → published en orden:
  1. `/nosotros`
  2. `/contacto`
  3. `/legal/terminos-y-condiciones` (tras OK abogado)
  4. `/legal/politica-de-privacidad` (tras OK abogado)
  5. `/legal/acuerdo-de-nivel-de-servicio` (tras confirmación SLA GoDaddy)
- [ ] Verificar URLs canónicas sin espacios
- [ ] Actualizar Footer links (sitio completo)

---

## 📂 Archivos clave

- `memory/content/trust-pages-bundle-2026.md` — Estructura + placeholders + enlaces cruzados
- `memory/content/trust-and-reseller-copy-wave3.md` — Copy lista (footer, disclaimer, diferenciadores)
- `wp-content/themes/gano-child/templates/page-*.php` — Templates PHP listos (5 archivos)
- `wp-content/themes/gano-child/style.css` — Tokens + clases CSS
- `wp-content/themes/gano-child/css/forms.css` — Estilos formulario (crear si no existe)

---

## ✅ Definition of Done

La tarea se cierra cuando:
- [ ] 5 páginas creadas (`/nosotros`, `/contacto`, 3 legales)
- [ ] TODOS los placeholders reemplazados (NIT, teléfono, SLA, etc.)
- [ ] Disclaimer Reseller presente en todas partes requeridas
- [ ] Enlaces cruzados: todas las URLs funcionales (0 errores 404)
- [ ] Formulario `/contacto`: envío funcional + confirmación
- [ ] WCAG AA: 0 errores Wave en cada página
- [ ] Lighthouse ≥85 (performance) en mobile + desktop
- [ ] LCP <2.5s, INP <200ms, CLS <0.1
- [ ] Revisión legal completada para Términos + Privacidad
- [ ] SLA confirmado en escrito con GoDaddy antes de publicación
- [ ] Footer actualizado con enlaces a legal
- [ ] Status publicado (no draft)

---

## 🚫 Bloqueantes — No publicar sin:

1. **NIT + razón social verificada** en Cámara de Comercio
2. **Teléfono + email legal activos** y probados
3. **SLA % confirmado escrito** con GoDaddy (no inventar "99,9%")
4. **Revisión legal completa** de Términos + Privacidad por abogado Ley 1581
5. **Horarios de soporte reales** definidos (no vacíos)
6. **Formulario funcional** con confirmación post-envío
7. **Accesibilidad WCAG AA** (0 errores Wave, Lighthouse ≥90)

---

## 📞 Contacto / Escalaciones

Si Diego encuentra:
- ❓ Template PHP no existe → Crear en `templates/page-*.php`
- ❓ Clase CSS no existe → Agregar a `style.css` con estilos base
- ❓ SLA no confirmado GoDaddy → Escalar a fase 4 (tarea separada ag-phase4-002)
- ❓ Abogado requiere cambios legales → Iterar documento + reenviar
- ❓ Formulario no envía → Verificar configuración WP mail + logs

---

**Generado por:** Claude Dispatch (cd-content-004 task specification)  
**Última actualización:** 2026-04-19 21:15 UTC  
**Próxima revisión:** Post-implementación + abogado review  
**Bloqueantes:** PFIDs (cd-content-002 pendiente), SLA GoDaddy confirmado
