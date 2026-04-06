# Prompt Maestro — Wave de Contenidos + Fixes Producción
# Gano Digital · 2026-04-06
# Para: agentes Cursor / GitHub Copilot / Claude en Cursor

---

Eres un agente de desarrollo asignado al proyecto **Gano Digital** (`Gano-digital/Pilot` en GitHub).
Tu misión es ejecutar la **wave de contenidos y fixes de producción del 6 de abril de 2026**,
trabajando sobre el repositorio local y el servidor de producción vía SSH/wp-cli.

Lee este prompt completo antes de actuar. Ejecuta las tareas en el orden indicado.
No inventes datos: si falta información marcada como `[PENDIENTE]`, deja el placeholder y continúa.

---

## 1. CONTEXTO DEL PROYECTO

**Sitio:** gano.digital — hosting WordPress para Colombia, modelo Reseller GoDaddy.
**Stack:** WordPress + Elementor + WooCommerce + Hello Elementor → gano-child.
**Servidor producción:** 72.167.102.145 · cPanel: f1rml03th382
**Ruta WP:** `/home/f1rml03th382/public_html/gano.digital`
**DB:** `gano_staging` · prefijo: `wp_6ce773b45f_`
**Repo:** rama activa `claude/contenidos-web-2026-04-06` → PR #114.
**Branch de trabajo:** crea `fix/prod-wave-YYYY-MM-DD` desde `claude/contenidos-web-2026-04-06` para tus cambios.

**Reglas duras:**
- Trabaja siempre en **staging** antes de producción.
- No eliminar plugins de fase (`gano-phase1..7`) sin confirmación explícita de Diego.
- No commitear secretos, credenciales ni wp-config.php con datos reales.
- No tocar RCC en vivo; PFIDs se resuelven solos al activar el Reseller Store plugin.
- El `.htaccess` tiene anti-scraper (bloquea curl con 403) — es correcto, no tocar.

---

## 2. TAREAS DE PRODUCCIÓN (servidor) — issues #115–#119

Ejecutar **en este orden** vía wp-cli sobre staging:

### TAREA A — Issue #116: Unpublish Coming Soon (BLOQUEA todo lo demás)
```bash
wp --path=/home/f1rml03th382/public_html/gano.digital \
   post update 1698 --post_status=draft
# Verificar:
wp --path=... post get 1698 --field=post_status
```
**Criterio:** post 1698 en `draft`. Nadie ve /coming-soon.

---

### TAREA B — Issue #115: Asignar menú de navegación
```bash
# 1. Ver menús disponibles
wp --path=... menu list --format=table

# 2. Ver locations registradas
wp --path=... menu location list --format=table

# 3. Asignar (reemplaza <menu-slug> con el resultado del paso 1)
wp --path=... menu location assign <menu-slug> primary
# Si existe location "header", también:
wp --path=... menu location assign <menu-slug> header
```
**Criterio:** header visible en gano.digital con navegación funcional.

---

### TAREA C — Issue #117: Assets faltantes
```bash
# Verificar si existen físicamente
ls /home/f1rml03th382/public_html/gano.digital/wp-content/uploads/ | grep -E "hero_digital_garden|decorative_abstract_seed"

# Si existen → importar como attachment
wp --path=... media import /home/.../uploads/hero_digital_garden.png --title="Hero Digital Garden"
wp --path=... media import /home/.../uploads/decorative_abstract_seed.png --title="Decorative Abstract Seed"

# Si NO existen → nota en comentario del issue y continúa sin bloquear.
```
**Criterio:** ambas imágenes tienen attachment ID en `wp_6ce773b45f_posts`.

---

### TAREA D — Issue #118: Reemplazar Lorem ipsum en Elementor JSON (post 1657)
El JSON tiene 108,642 chars. Parsea con Python:

```python
import json, pymysql  # o usa wp-cli

# Conectar a gano_staging (credenciales en wp-config.php del servidor)
# Obtener meta_value de post_id=1657, meta_key='_elementor_data'
# Decodificar JSON
# Buscar widgets tipo 'text-editor' o 'heading' con texto Lorem ipsum
# Reemplazar con copy de memory/content/homepage-copy-2026-04.md:
#
# PILARES (4 tarjetas — clase gano-el-pillar):
#   1. Título: "NVMe que se nota en Core Web Vitals, no solo en el folleto."
#      Texto: "Almacenamiento de nueva generación y stack optimizado para WordPress..."
#   2. Título: "WordPress endurecida para el tráfico real de un negocio."
#      Texto: "Hardening continuo, controles de acceso y visibilidad..."
#   3. Título: "Confianza cero por defecto: identidad, sesiones y permisos bajo control."
#      Texto: "La seguridad no es un cartel: es política aplicada en capas..."
#   4. Título: "Contenido más cerca del usuario, sin magia barata."
#      Texto: "Arquitectura pensada para entregar experiencias rápidas..."
#
# SOCIO TECNOLÓGICO (clase gano-el-prose-narrow):
#   "Gano Digital no compite en 'hosting barato'. Compite en continuidad:
#    infraestructura seria, soporte en tu idioma y visibilidad sobre lo que
#    pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico..."
#
# Re-serializar y hacer UPDATE en DB.
# IMPORTANTE: limpiar caché Elementor tras el update:
```

```bash
wp --path=... elementor flush-css
wp --path=... cache flush
```
**Criterio:** ningún Lorem ipsum visible en gano.digital.

---

### TAREA E — Issue #119: Activar gano-content-importer + revisar drafts SOTA
```bash
# Solo en staging
wp --path=... plugin activate gano-content-importer

# Verificar 20 páginas creadas como draft
wp --path=... post list \
   --post_type=page --post_status=draft \
   --meta_key=_gano_sota_category \
   --fields=ID,post_title,post_status \
   --format=table

# Crear página hub (asignar template manualmente en wp-admin o):
wp --path=... post create \
   --post_title="Hub de Innovación SOTA" \
   --post_status=draft \
   --post_type=page \
   --meta_input='{"_wp_page_template":"templates/page-sota-hub.php"}'
```
**Criterio:** 20 páginas en draft + página hub creada. Sin Lorem ipsum en drafts.

---

## 3. TAREAS DE REPO — wave de contenidos

### TAREA F — Trust pages (cd-content-004)
Crear `wp-content/themes/gano-child/templates/page-trust-bundle.php` con:

**Copy base (no inventar datos legales sin confirmar):**
- **Garantía:** 30 días de satisfacción o crédito equivalente (si se puede confirmar con el proveedor).
- **SLA:** disponibilidad objetivo ≥ 99,9% para Bastión SOTA; estándar para otros planes.
- **Contacto y soporte:** WhatsApp +573135646123 · soporte por ticket en español.
- **NIT:** `[NIT_PENDIENTE]` — Diego lo confirma. No inventar.
- **Política de privacidad:** datos tratados según Ley 1581 de 2012 (Colombia).
- **Política de reembolsos:** crédito en cuenta; no aplica a dominios ya registrados.

Estructura de páginas a crear como drafts (con `wp_insert_post` o guía de wp-admin):
1. `/garantia` — Garantía de servicio y SLA
2. `/privacidad` — Política de privacidad (Ley 1581)
3. `/terminos` — Términos y condiciones
4. `/soporte` — Centro de soporte y canales de contacto
5. `/contacto` — Formulario + WhatsApp +573135646123

---

### TAREA G — Navegación primaria y footer (cd-content-005)
Aplicar `memory/content/navigation-primary-spec-2026.md`.

En el child theme (`inc/homepage-blocks.php` o `functions.php`), registrar/verificar los menús:
- **Header (primary):** Inicio · Ecosistemas · Hub SOTA · Contacto
- **Footer:** Privacidad · Términos · Garantía · Soporte

En producción usar wp-cli:
```bash
wp --path=... menu create "Menú Principal"
wp --path=... menu item add-custom <menu-id> "Inicio" "/"
wp --path=... menu item add-custom <menu-id> "Ecosistemas" "/ecosistemas"
wp --path=... menu item add-custom <menu-id> "Innovación SOTA" "/hub-sota"
wp --path=... menu item add-custom <menu-id> "Contacto" "/contacto"
wp --path=... menu location assign "menu-principal" primary
```

---

### TAREA H — FAQ JSON-LD (cd-content-005 continúa)
Añadir a `wp-content/mu-plugins/gano-seo.php` los FAQ candidatos de
`memory/content/faq-schema-candidates-wave3.md`. Ejemplos mínimos:

```json
{
  "@type": "Question",
  "name": "¿Qué diferencia a Gano Digital de un hosting compartido genérico?",
  "acceptedAnswer": { "@type": "Answer",
    "text": "Gano Digital usa almacenamiento NVMe, hardening WordPress activo y soporte en español con facturación en COP. El hosting compartido genérico no incluye ninguna de estas capas." }
},
{
  "@type": "Question",
  "name": "¿Puedo pagar en pesos colombianos?",
  "acceptedAnswer": { "@type": "Answer",
    "text": "Sí. Todos nuestros planes se facturan en COP a través del programa Reseller de GoDaddy." }
}
```
No duplicar el schema Organization ya existente. Añadir solo en homepage y /ecosistemas.

---

## 4. VALIDACIÓN FINAL

Antes de hacer PR:
```bash
# PHP lint
find wp-content/themes/gano-child/ -name "*.php" | xargs php -l

# Dispatch validate
python scripts/validate_claude_dispatch.py

# Lista de tareas pendientes en dispatch
python scripts/claude_dispatch.py list
```

Verificar visualmente en staging:
- [ ] Header con menú de navegación visible.
- [ ] /coming-soon no accesible.
- [ ] Homepage sin Lorem ipsum.
- [ ] /ecosistemas carga con 4 planes y precios en COP.
- [ ] /hub-sota lista páginas SOTA (en draft = "en preparación"; en publish = cards).
- [ ] FAQ JSON-LD presente en source de homepage.
- [ ] Tab navigation: skip link y focus-visible funcionan.

---

## 5. COMMIT Y PR

```bash
git add -A
git commit -m "feat(wave-prod): trust pages, nav, FAQ schema, prod fixes #115-#119"
git push origin fix/prod-wave-2026-04-06

# PR apuntando a claude/contenidos-web-2026-04-06 (no a main directamente)
gh pr create \
  --base claude/contenidos-web-2026-04-06 \
  --title "fix(prod): nav menus + coming soon + assets + lorem ipsum + trust pages" \
  --body "Cierra #115 #116 #117 #118 #119. Wave contenidos completa."
```

---

## REFERENCIAS CLAVE DEL REPO

| Archivo | Para qué |
|---------|----------|
| `memory/content/homepage-copy-2026-04.md` | Copy definitivo de cada bloque de la home |
| `memory/content/homepage-section-order-spec-2026.md` | Orden y clases CSS de los 6 bloques |
| `memory/content/navigation-primary-spec-2026.md` | Spec del menú principal y footer |
| `memory/content/faq-schema-candidates-wave3.md` | Preguntas FAQ para JSON-LD |
| `memory/content/trust-pages-bundle-2026.md` | Copy para páginas legales y de confianza |
| `memory/content/trust-and-reseller-copy-wave3.md` | Honestidad Reseller + copy de garantía |
| `memory/content/footer-contact-audit-wave3.md` | Auditoría del footer y contacto |
| `memory/content/ecosystems-copy-matrix-wave3.md` | Matriz comercial de los 4 planes |
| `memory/content/microcopy-wave3-kit.md` | Microcopy: botones, labels, tooltips |
| `memory/content/ux-a11y-wave3-notes.md` | Skip link, focus-visible, aria-* |
| `memory/claude/dispatch-queue.json` | Cola de tareas ejecutables |
| `memory/content/sota-hub-activation-guide.md` | Guía wp-cli para las 20 SOTA |
| `GANO_DIGITAL_FIXPLAN.md` (en Downloads local) | Plan 7 pasos para fixes de producción |
