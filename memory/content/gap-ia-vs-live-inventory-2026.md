# Brecha IA propuesta vs inventario real — Gano Digital
_Generado: Abril 2026 · Wave 4_  
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
| `/ecosistemas` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/shop-premium.php` | Asignar la plantilla `shop-premium.php` a la página en wp-admin; verificar que esté publicada |
| `/ecosistemas/nucleo-prime` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `nucleo-prime`; padre: página `/ecosistemas` |
| `/ecosistemas/fortaleza-delta` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `fortaleza-delta`; padre: `/ecosistemas` |
| `/ecosistemas/bastion-sota` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor" | Crear página en wp-admin → Elementor; slug sugerido: `bastion-sota`; padre: `/ecosistemas` |
| `/pilares` (índice) | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página índice Elementor" | Crear página índice en wp-admin → Elementor con grid de 5 categorías |
| `/hosting-wordpress-colombia` | `existe` (plantilla PHP en repo) | `wp-content/themes/gano-child/templates/page-seo-landing.php` + `seo-pages/hosting-wordpress-colombia.php` | Asignar la plantilla `page-seo-landing.php` a la página; verificar que esté publicada con el slug correcto |
| `/nosotros` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente contenido real"; TASKS.md "Crear página Nosotros real" | Crear página Elementor; mantener en borrador hasta tener copy real (sin placeholders visibles) |
| `/contacto` | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear página Elementor con formulario" | Crear página Elementor; añadir CF7/formulario nativo; rellenar `[teléfono]` y `[email]` reales |
| `/legal` (hub) | `desconocido` | `site-ia-wave3-proposed.md` §7 "Pendiente — Crear 3 sub-páginas bajo `/legal`" | Crear página padre `/legal` y sus 3 hijos (ver sección siguiente) |
| `/legal/terminos-y-condiciones` | `desconocido` | `site-ia-wave3-proposed.md` §4 Hub Legal | Crear sub-página bajo `/legal`; incluir NIT real (placeholder `[NIT]`) y legislación SIC Colombia |
| `/legal/politica-de-privacidad` | `desconocido` | `site-ia-wave3-proposed.md` §4 Hub Legal | Crear sub-página bajo `/legal`; cumplimiento RGPD + normativa SIC Colombia |
| `/legal/acuerdo-de-nivel-de-servicio` | `desconocido` | `site-ia-wave3-proposed.md` §4 Hub Legal | Crear sub-página bajo `/legal`; redactar con formulaciones prudentes ("objetivo de disponibilidad del 99,9%") |

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

## Resumen de brechas críticas

| Tipo de brecha | Cantidad | Acción prioritaria |
|---|---|---|
| Páginas Elementor pendientes de crear (nivel 1) | 7 (`/ecosistemas/*` ×3, `/pilares` índice, `/nosotros`, `/contacto`, `/legal` hub) | Crear en wp-admin una vez por una |
| Páginas legales pendientes | 3 | Crear sub-páginas bajo `/legal` con copy legal real |
| Plantillas PHP en repo sin confirmar publicación | 2 (`/ecosistemas`, `/hosting-wordpress-colombia`) | Asignar plantilla + publicar en wp-admin |
| Páginas SOTA con slug drift (importer ≠ IA doc) | 20 | Decidir slug canónico (IA doc **o** importer) y unificar |
| Títulos conceptualmente divergentes | 4 (Hosting Compartido/Contenedores, DDoS, Micro-animaciones/Cinéticas, Agente IA) | Diego decide cuál título es definitivo; actualizar el documento que no sea fuente de verdad |

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

## Decisión pendiente de Diego — Slugs canónicos para las 20 páginas SOTA

Antes de publicar las páginas SOTA, tomar la siguiente decisión:

> **¿Se adoptan los slugs de `site-ia-wave3-proposed.md` o los que genera el importer?**

| Opción | Ventaja | Desventaja |
|---|---|---|
| **A — Slugs del IA doc** | Coherentes con la jerarquía `/pilares/categoria/slug` propuesta; mejores para SEO con categoría en URL | Requiere editar los slugs en wp-admin o ejecutar script WP-CLI tras importar |
| **B — Slugs del importer** | Se generan automáticamente; sin trabajo extra post-importación | No respetan la jerarquía `/pilares/categoria/`; menos descriptivos en algunos casos |
| **C — Combinación** | Usar slugs del importer pero asignarlos como hijos de las páginas de categoría dentro de `/pilares/` | Requiere editar "Página padre" en wp-admin para cada una de las 20 páginas |

**Recomendación:** Opción C. Mantener el contenido del importer, editar el slug corto si hay divergencia conceptual grave (ver filas marcadas con ⚠️ en la tabla de arriba), y asignar cada página como hija de su categoría dentro del hub `/pilares/`.

---

_Mantener este documento sincronizado con `site-ia-wave3-proposed.md` y `TASKS.md` al tomar decisiones de slugs o publicación._
