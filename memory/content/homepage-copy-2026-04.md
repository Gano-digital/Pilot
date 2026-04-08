# Copy listo para homepage (Elementor) — Abril 2026

Texto en español (Colombia), tono **manifiesto técnico** alineado a `.gano-skills/gano-content-audit` y al Plan Maestro. Pégalo en los widgets correspondientes del editor de Elementor (home).

---

## Hero

**Headline (H1)**  
Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.

**Subheadline**  
Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.

**CTA primario**  
Ver arquitecturas y planes

**CTA secundario**  
Hablar con el equipo

**Microcopy bajo CTAs (opcional)**  
NVMe · Monitoreo proactivo · Facturación en COP

---

## Cuatro pilares (reemplaza Lorem en cada card)

### 1 — Velocidad real (NVMe)

**Título**  
NVMe que se nota en Core Web Vitals, no solo en el folleto.

**Texto**  
Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.

### 2 — WordPress blindada

**Título**  
WordPress endurecida para el tráfico real de un negocio.

**Texto**  
Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.

### 3 — Zero-Trust operativo

**Título**  
Confianza cero por defecto: identidad, sesiones y permisos bajo control.

**Texto**  
La seguridad no es un cartel: es política aplicada en capas. Menos suposiciones, más trazabilidad cuando importa.

### 4 — Edge y latencia

**Título**  
Contenido más cerca del usuario, sin magia barata.

**Texto**  
Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia. Menos saltos innecesarios, más respuesta perceptible.

---

## Bloque “Un socio tecnológico”

**Título**  
Un socio tecnológico que trabaja en silencio.

**Párrafo**  
Gano Digital no compite en “hosting barato”. Compite en **continuidad**: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico para que no te enteres de los incidentes por redes sociales.

**Bullets sugeridos**

- Infraestructura alineada a cargas reales de WordPress y comercio.
- Monitoreo y respuesta con foco en negocio, no en excusas.
- Roadmap claro hacia soberanía digital y cumplimiento en Colombia.

---

## Métricas / franjas (sin inflar cifras)

Usa solo lo que puedas respaldar con SLA o mediciones reales. Ejemplos de formulación prudente:

- **99,9% de disponibilidad** como objetivo de servicio (según plan).
- **Almacenamiento NVMe** por slot según arquitectura elegida.
- **Soporte 24/7 en español** (definir canal: ticket / chat / WhatsApp según operación real).

Evita “millones de clientes” o logos sin contrato. Preferible brevedad honesta.

---

## CTA final

**Título**  
¿Listo para una infraestructura que no te pida disculpas?

**Botón**  
Elegir mi arquitectura

---

## Notas de implementación

1. **Checklist por bloques (orden, clases CSS, a11y):** [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](../ops/homepage-vitrina-launch-plan-2026-04.md) § Fase 1 — checklist de aplicación.
2. **Menú**: el child theme **Gano** registra la ubicación WordPress **`primary`** (el padre solo tenía `main`). En Apariencia → Menús asigna el menú a **“Menú principal (header / Elementor)”** y alinea con lo que pida tu plantilla de cabecera en Elementor. Ver [`elementor-home-classes.md`](elementor-home-classes.md).
2. **Imágenes**: cada bloque con icono o ilustración debe tener `alt` descriptivo en español.
3. **Coming soon / borradores**: cualquier página no lista debe quedar en borrador para no competir SEO con la home.
