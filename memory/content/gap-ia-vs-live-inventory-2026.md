# Brecha IA propuesta vs inventario real — Gano Digital
_Generado: Abril 2026 · Wave 4 · Actualizado: Abril 2026 (Wave 4 SOTA)_  
_Fuente IA: `memory/content/site-ia-wave3-proposed.md`_

---

## Cómo usar este documento

1. **Columna Estado**: refleja lo que se puede inferir **desde git**. Para completar con el estado real de WordPress, sigue las instrucciones en la sección **"Cómo completar desde wp-admin"** al final.
2. **Estados posibles**: `desconocido` · `existe` · `borrador` · `falta`
3. **Fuente de verdad**: archivo o documento del repo que avala el estado registrado.
4. **Acción sugerida**: próximo paso para cerrar la brecha.

---

## Tabla principal — Rutas de primer nivel

| Ruta IA propuesta | Estado (git) | Fuente de verdad | Acción sugerida |
|---|---|---|---|
| `/` (Home) | `desconocido` | `site-ia-wave3-proposed.md` §7 indica "publicada (Elementor, pendiente copy final)" — no verificable desde git sin acceso a BD | Verificar en wp-admin → Páginas; aplicar copy de `homepage-copy-2026-04.md` |
| `/ecosistemas` | `existe` (2 plantillas PHP en repo) | `wp-content/themes/gano-child/templates/shop-premium.php` (catálogo animado) + `wp-content/themes/gano-child/templates/page-ecosistemas.php` (versión simplificada) | Asignar la plantilla correcta en wp-admin; `page-ecosistemas.php` es la recomendada para la vitrina principal; verificar que esté publicada |
| `/ecosistemas/nucleo-prime` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `nucleo-prime`; padre: página `/ecosistemas` |
| `/ecosistemas/fortaleza-delta` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `fortaleza-delta`; padre: `/ecosistemas` |
| `/ecosistemas/bastion-sota` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `bastion-sota`; padre: `/ecosistemas` |
| `/pilares` (índice) | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-sota-hub.php` — "Hub de Innovación SOTA" | Asignar `page-sota-hub.php` a la página `/pilares` en wp-admin; verificar que esté publicada con slug correcto |
| `/hosting-wordpress-colombia` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-seo-landing.php` + `seo-pages/hosting-wordpress-colombia.php` | Asignar la plantilla `page-seo-landing.php` a la página; verificar que esté publicada con el slug correcto |
| `/nosotros` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-nosotros.php` — "Nosotros — Quiénes somos" | Asignar `page-nosotros.php` a la página en wp-admin; revisar y reemplazar placeholders `[nombre]`, `[foto]` antes de publicar |
| `/contacto` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-contacto.php` — "Contacto" | Asignar `page-contacto.php` a la página en wp-admin; rellenar `[teléfono]` y `[email]` reales antes de publicar |
| `/legal` (hub) | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear 3 sub-páginas bajo `/legal`" | Crear página padre `/legal` en wp-admin como contenedor; sus 3 hijos ya tienen plantillas (ver filas siguientes) |
| `/legal/terminos-y-condiciones` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-terminos.php` — "Términos y Condiciones" | Crear sub-página bajo `/legal` en wp-admin, asignar `page-terminos.php`; completar NIT real (placeholder `[NIT]`) |
| `/legal/politica-de-privacidad` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-privacidad.php` — "Política de Privacidad" | Crear sub-página bajo `/legal` en wp-admin, asignar `page-privacidad.php`; fecha actualización `2026-04-06` ya incluida |
| `/legal/acuerdo-de-nivel-de-servicio` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-sla.php` — "Acuerdo de Nivel de Servicio (SLA)" | Crear sub-página bajo `/legal` en wp-admin, asignar `page-sla.php`; fecha actualización `2026-04-06` ya incluida |

---

## Tabla secundaria — 20 páginas SOTA (`/pilares/**`)

> **⚠️ Drift detectado:** los títulos en `gano-content-importer` difieren de los slugs propuestos en la IA. La columna "Slug generado (importer)" muestra el slug que WordPress crearía con `sanitize_title()` a partir del título real del plugin. Si el importer fue activado, estas páginas existen como borradores con esos slugs, **no** con los slugs de la IA propuesta.

| Ruta IA propuesta | Slug generado por importer | Estado (git) | Fuente de verdad | Acción sugerida |
|---|---|---|---|---|
| `/pilares/infraestructura/arquitectura-nvme-la-muerte-del-ssd-tradicional` | `arquitectura-nvme-el-manifiesto-de-la-velocidad-critica` | `borrador` (si importer activado) | `gano-content-importer.php` l.113; `sota-single-template.php` | Verificar slug en wp-admin → Páginas → Borradores; actualizar slug a ruta IA o actualizar la IA doc |
| `/pilares/infraestructura/la-muerte-del-hosting-compartido-el-riesgo-invisible` | `contenedores-aislados-tu-isla-de-rendimiento` | `borrador` (si importer activado) | `gano-content-importer.php` l.293 | ⚠️ Título/concepto diverge: "Hosting Compartido" (IA) vs "Contenedores Aislados" (importer). Decidir cuál usar y unificar |
| `/pilares/infraestructura/edge-computing-contenido-a-cero-distancia-de-tu-cliente` | `edge-computing-colapsando-la-latencia-geografica` | `borrador` (si importer activado) | `gano-content-importer.php` l.319 | Actualizar slug o IA doc; contenido conceptual coherente |
| `/pilares/infraestructura/backups-continuos-en-tiempo-real-tu-maquina-del-tiempo` | `resiliencia-de-datos-maquina-del-tiempo-digital` | `borrador` (si importer activado) | `gano-content-importer.php` l.423 | Actualizar slug o IA doc |
| `/pilares/infraestructura/escalamiento-elastico-sobrevive-a-tu-propio-exito-viral` | `escalamiento-elastico-el-ecosistema-infinito` | `borrador` (si importer activado) | `gano-content-importer.php` l.475 | Slug próximo; ajustar sufijo si se quiere coherencia con IA doc |
| `/pilares/infraestructura/alta-disponibilidad-ha-la-infraestructura-indestructible` | `arquitectura-indestructible-alta-disponibilidad-enterprise` | `borrador` (si importer activado) | `gano-content-importer.php` l.579 | Actualizar slug o IA doc |
| `/pilares/seguridad/zero-trust-security-el-fin-de-las-contrasenas` | `zero-trust-el-fin-de-la-confianza-implicita` | `borrador` (si importer activado) | `gano-content-importer.php` l.148 | Actualizar slug o IA doc |
| `/pilares/seguridad/mitigacion-ddos-inteligente-firewall-de-nueva-generacion` | `inmunidad-ddos-blindaje-ia-en-el-perimetro` | `borrador` (si importer activado) | `gano-content-importer.php` l.267 | ⚠️ Título diverge; evaluar si el ángulo "Inmunidad DDoS / Blindaje IA" reemplaza al propuesto |
| `/pilares/seguridad/cifrado-post-cuantico-la-boveda-del-futuro` | `blindaje-post-cuantico-cifrado-para-la-proxima-decada` | `borrador` (si importer activado) | `gano-content-importer.php` l.371 | Actualizar slug o IA doc |
| `/pilares/rendimiento/headless-wordpress-la-velocidad-absoluta` | `arquitectura-headless-desacoplando-el-futuro` | `borrador` (si importer activado) | `gano-content-importer.php` l.241 | Actualizar slug o IA doc |
| `/pilares/rendimiento/green-hosting-infraestructura-sostenible-para-tu-negocio` | `ingenieria-sostenible-rendimiento-con-conciencia` | `borrador` (si importer activado) | `gano-content-importer.php` l.345 | Actualizar slug o IA doc |
| `/pilares/rendimiento/cicd-automatizado-nunca-mas-rompas-tu-tienda-en-vivo` | `orquestacion-cicd-evolucion-sin-interrupcion` | `borrador` (si importer activado) | `gano-content-importer.php` l.397 | Actualizar slug o IA doc |
| `/pilares/rendimiento/skeleton-screens-la-psicologia-de-la-velocidad-percibida` | `skeleton-screens-psicologia-del-rendimiento` | `borrador` (si importer activado) | `gano-content-importer.php` l.449 | Slug muy cercano; revisar en wp-admin |
| `/pilares/rendimiento/micro-animaciones-e-interacciones-hapticas-diseno-que-se-siente` | `experiencias-cineticas-diseno-que-se-siente-premium` | `borrador` (si importer activado) | `gano-content-importer.php` l.527 | ⚠️ Título diverge: "Micro-animaciones" (IA) vs "Experiencias Cinéticas" (importer). Unificar |
| `/pilares/rendimiento/http3-y-quic-el-protocolo-que-rompe-la-congestion` | `protocolos-de-vanguardia-http3-quic-transmision` | `borrador` (si importer activado) | `gano-content-importer.php` l.553 | Actualizar slug o IA doc |
| `/pilares/inteligencia-artificial/gestion-predictiva-con-ai-cero-caidas-cero-sorpresas` | `ia-predictiva-la-mente-que-anticipa-el-fallo` | `borrador` (si importer activado) | `gano-content-importer.php` l.183 | Actualizar slug o IA doc |
| `/pilares/inteligencia-artificial/self-healing-el-ecosistema-que-se-cura-solo` | `self-healing-resiliencia-autonoma` | `borrador` (si importer activado) | `gano-content-importer.php` l.501 | Slug más corto; revisar en wp-admin; actualizar si se quiere coherencia |
| `/pilares/inteligencia-artificial/el-agente-ia-de-administracion-tu-infraestructura-habla-espanol` | `co-piloto-de-ia-soberana-administracion-por-conversacion` | `borrador` (si importer activado) | `gano-content-importer.php` l.631 | ⚠️ Título diverge; unificar concepto |
| `/pilares/estrategia/soberania-digital-en-latam-tus-datos-tu-control` | `soberania-digital-jurisdiccion-y-control-total` | `borrador` (si importer activado) | `gano-content-importer.php` l.215 | Actualizar slug o IA doc |
| `/pilares/estrategia/analytics-server-side-privacidad-velocidad-y-datos-reales` | `soberania-de-datos-analytics-privado-server-side` | `borrador` (si importer activado) | `gano-content-importer.php` l.605 | Actualizar slug o IA doc |

---

## Página en importer sin equivalente en la IA propuesta

| Título importer | Slug generado | Estado (git) | Nota |
|---|---|---|---|
| `Contenedores Aislados: Tu Isla de Rendimiento` | `contenedores-aislados-tu-isla-de-rendimiento` | `borrador` (si importer activado) | No tiene ruta equivalente en `site-ia-wave3-proposed.md`; puede sustituir a "La muerte del hosting compartido…" o necesita una ruta nueva bajo `/pilares/infraestructura/` |

---

## Páginas con plantilla PHP en repo sin ruta en IA propuesta

> Estas páginas tienen template PHP listo pero **no aparecen** en el árbol de rutas de `site-ia-wave3-proposed.md`. Diego debe decidir si se integran en la IA oficial o si son páginas complementarias.

| Slug sugerido | Plantilla PHP | Estado (git) | Nota / Acción |
|---|---|---|---|
| `/dominios` | `page-dominios.php` — "Dominios — Registra tu Presencia" | `existe` (plantilla en repo) | Mencionada en `products-services-pages-matrix-2026.md` como destino para CTAs de búsqueda de dominio (shortcode `[rstore-domain-search]`). Añadir al árbol IA bajo el nivel 1 o como sección de `/ecosistemas`. |
| `/diagnostico-digital` | `page-diagnostico-digital.php` — "Diagnostico Digital SOTA" | `existe` (plantilla en repo) | Quiz de diagnóstico SOTA; pendiente de publicación según `sota-rollout-qa-wave-2026-04.md`. Considerar añadir al árbol IA como flujo de conversión (`/diagnostico-digital` o CTA en Home). |
| `/contacto/gracias` (o slug a definir) | `page-contacto-gracias.php` — "Contacto — Gracias" | `existe` (plantilla en repo) | Página de confirmación de formulario (redirect post-envío). No necesita ruta pública en el menú; definir slug en wp-admin para el redirect. |

---

## Resumen de brechas críticas

| Tipo de brecha | Cantidad | Acción prioritaria |
|---|---|---|
| Páginas Elementor pendientes de crear (nivel 1, sin plantilla PHP) | 4 (`/ecosistemas/*` ×3, `/legal` hub padre) | Crear en wp-admin una a una |
| Plantillas PHP en repo sin confirmar publicación en WP | 9 (`/ecosistemas`, `/pilares`, `/hosting-wordpress-colombia`, `/nosotros`, `/contacto`, `/legal/terminos`, `/legal/privacidad`, `/legal/sla`, `/dominios`) | Asignar plantilla + publicar en wp-admin; verificar slug |
| Páginas SOTA con slug drift (importer ≠ IA doc) | 20 | Decidir slug canónico (IA doc **o** importer) y unificar |
| Títulos conceptualmente divergentes | 4 (Hosting Compartido/Contenedores, DDoS, Micro-animaciones/Cinéticas, Agente IA) | Diego decide cuál título es definitivo; actualizar el documento que no sea fuente de verdad |
| Páginas en repo sin ruta equivalente en IA propuesta | 3 (`/dominios`, `/diagnostico-digital`, `/contacto/gracias`) | Decidir si se añaden al árbol IA o si son complementarias; actualizar `site-ia-wave3-proposed.md` si aplica |

---

## Cómo completar esta tabla desde wp-admin (sin pegar credenciales en GitHub)

**Objetivo:** verificar el estado real de cada página y anotar en la columna **Estado** el valor correcto (`existe` / `borrador` / `falta`).

### Paso 1 — Listar todas las páginas (incluyendo borradores)

1. Acceder a **wp-admin → Páginas → Todas las páginas**.
2. Cambiar el filtro de estado a **Todos** para ver también borradores y páginas en revisión.
3. Activar la columna **Slug** en las opciones de pantalla (esquina superior derecha → "Opciones de pantalla").

### Paso 2 — Exportar la lista para comparar

Opción A (rápida):
- **wp-admin → Herramientas → Exportar → Páginas → Descargar archivo de exportación**.
- Abrir el XML en un editor de texto y buscar `<wp:post_name>` para ver los slugs.

Opción B (con WP-CLI si tienes acceso SSH):
```bash
# Lista slug, título y estado de todas las páginas
wp post list --post_type=page --fields=post_name,post_title,post_status --format=csv
```
> ⚠️ No pegar la salida completa en un issue público si contiene información sensible (IDs internos están bien; no incluir URLs de administración con tokens).

### Paso 3 — Cruzar con la tabla de arriba

Para cada ruta de la IA propuesta:
- Si la página **existe y está publicada** → marcar `existe`.
- Si la página **existe como borrador** → marcar `borrador`.
- Si la página **no aparece en ningún estado** → marcar `falta`.
- Si el **slug en WordPress difiere** del slug propuesto en la IA → anotar el slug real en la columna "Fuente de verdad" y añadir nota en "Acción sugerida".

### Paso 4 — Actualizar este archivo

Hacer un commit directo en la rama `main` (o abrir un PR rápido) con los estados reales completados. No incluir contraseñas, tokens ni URLs de wp-admin con nonces en el diff.

---

## Decisión adoptada — Slugs canónicos para las 20 páginas SOTA

_Actualizado 2026-04-19 (issue #265 — cierre de brecha IA vs live)_

> **Decisión: Opción C — Combinación.**  
> Mantener el slug corto del importer, asignar cada página como hija de su categoría padre
> dentro del hub `/pilares/`. Para los 4 casos con divergencia conceptual grave (marcados ⚠️),
> usar el slug del importer pero actualizar el título para alinearlo con el concepto del IA doc.

### Tabla de resolución de casos ⚠️

| Ruta IA propuesta | Slug importer | Decisión |
|---|---|---|
| `/pilares/infraestructura/la-muerte-del-hosting-compartido-el-riesgo-invisible` | `contenedores-aislados-tu-isla-de-rendimiento` | **Mantener slug importer.** El concepto "contenedores aislados" es más preciso técnicamente. Actualizar IA doc para reflejar este slug. |
| `/pilares/seguridad/mitigacion-ddos-inteligente-firewall-de-nueva-generacion` | `inmunidad-ddos-blindaje-ia-en-el-perimetro` | **Mantener slug importer.** "Blindaje IA en el perímetro" refuerza la narrativa SOTA. |
| `/pilares/rendimiento/micro-animaciones-e-interacciones-hapticas-diseno-que-se-siente` | `experiencias-cineticas-diseno-que-se-siente-premium` | **Mantener slug importer.** "Experiencias Cinéticas" es coherente con la marca premium. |
| `/pilares/inteligencia-artificial/el-agente-ia-de-administracion-tu-infraestructura-habla-espanol` | `co-piloto-de-ia-soberana-administracion-por-conversacion` | **Mantener slug importer.** "Co-piloto de IA Soberana" alinea con narrativa de soberanía digital. |

### Pasos para cerrar la brecha (BLOQUEADO por acción humana — Diego)

1. Activar `gano-content-importer` en wp-admin (ver runbook `memory/ops/runbook-activacion-wp-admin-2026-04-16.md` §2).
2. Verificar que las 20 páginas aparecen como **Borradores** en `wp-admin → Páginas → Todos`.
3. Para cada página:
   - Abrir la página → editar **Atributos de página → Padre**: seleccionar la categoría correcta dentro de `/pilares/`.
   - Ejemplo: "Arquitectura NVMe…" → Padre: `Pilares > Infraestructura`.
4. Para los 4 casos ⚠️: no cambiar el slug del importer, pero verificar que el **título** sea el del importer (ya están en ese texto).
5. Publicar (o dejar como Borrador hasta que el copy final sea aprobado por Diego).
6. Actualizar `site-ia-wave3-proposed.md` §7 reemplazando las 4 rutas ⚠️ con los slugs del importer.

---

_Mantener este documento sincronizado con `site-ia-wave3-proposed.md` y `TASKS.md` al tomar decisiones de slugs o publicación. Última revisión: 2026-04-19 — issue #265 (decisión canónica adoptada)._
