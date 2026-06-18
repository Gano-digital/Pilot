# Server Audit — Three Waves Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Reparar gano.digital en tres waves paralelas: comercial (catálogo canónico + checkout), técnico (plugins + DB + templates), contenido (11 páginas SOTA vacías).

**Architecture:** Tres ramas Git independientes sin conflictos entre sí. Wave A toca `functions.php` y metadata de páginas. Wave C mueve archivos de templates y ejecuta WP-CLI. Wave B actualiza post_content via WP-CLI. Cada wave tiene su propio PR con validación automática por GitHub Actions.

**Tech Stack:** WordPress 6.x, WP-CLI, PHP 8.3, SSH (`f1rml03th382@72.167.102.145`), GitHub Actions, `gano-child` theme, shortcode `[gano_reseller_iframe]`

> **Nota WP-CLI:** Si algún comando `wp post update --post_content='...'` falla por caracteres especiales, usar el patrón alternativo: escribir el contenido en `/tmp/content.txt` en el servidor y luego `wp post update ID --post_content="$(cat /tmp/content.txt)"`. Todos los comandos SSH de este plan son idempotentes — se pueden re-ejecutar sin riesgo.

---

## Prerequisito — Merge PR #305

- [ ] **Verificar que PR #305 está abierto**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
gh pr view 305
```

Esperado: PR `feat/catalog-acceso-dev-categories` en estado OPEN.

- [ ] **Merge PR #305**

```bash
gh pr merge 305 --merge --delete-branch
```

- [ ] **Actualizar main local**

```bash
git checkout main
git pull origin main
```

Esperado: commit de merge visible en `git log --oneline -3`.

---

## Wave A — `fix/commercial-catalog`

**Archivos modificados:**
- Modify: `wp-content/themes/gano-child/functions.php` (añadir redirect 301)
- Server-side WP-CLI: metadata página ID 1776, post_content IDs 1997–2000, trash ID 1996

---

### Task A-1: Crear rama

- [ ] **Crear y cambiar a la rama**

```bash
git checkout main
git checkout -b fix/commercial-catalog
```

---

### Task A-2: Redirect 301 ecosistemas → catalogo

- [ ] **Abrir `wp-content/themes/gano-child/functions.php`**

Buscar la línea que contiene `// RESELLER TABS (page-ecosistemas.php)` (alrededor de línea 171). Insertar el siguiente bloque **antes** de esa línea:

```php
// REDIRECT: /ecosistemas/ → /catalogo/ (canónico desde 2026-06-17)
add_action( 'template_redirect', function() {
    if ( is_page( 'ecosistemas' ) ) {
        wp_redirect( home_url( '/catalogo/' ), 301 );
        exit;
    }
}, 1 );
```

- [ ] **Validar sintaxis PHP localmente**

```bash
php -l wp-content/themes/gano-child/functions.php
```

Esperado: `No syntax errors detected`

- [ ] **Commit parcial**

```bash
git add wp-content/themes/gano-child/functions.php
git commit -m "fix(commercial): redirect 301 /ecosistemas/ → /catalogo/"
```

---

### Task A-3: Asignar template y activar catálogo en /catalogo/

Estos comandos se ejecutan vía SSH en el servidor de producción.

- [ ] **Asignar template page-ecosistemas.php a la página /catalogo/ (ID 1776)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post meta update 1776 _wp_page_template 'templates/page-ecosistemas.php'"
```

Esperado: `Success: Updated custom field '_wp_page_template'.`

- [ ] **Actualizar post_content de /catalogo/ para activar shortcodes del catálogo**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 1776 --post_content='[gano_reseller_iframe ecosistema=\"hosting_economia\" heading=\"Núcleo Prime — Elige tu plan\"][gano_reseller_iframe ecosistema=\"hosting_deluxe\" heading=\"Fortaleza Delta — Elige tu plan\"][gano_reseller_iframe ecosistema=\"hosting_premium\" heading=\"Bastión SOTA — Elige tu plan\"][gano_reseller_iframe ecosistema=\"hosting_ultimate\" heading=\"Ultimate WP — Elige tu plan\"]'"
```

Esperado: `Success: Updated post 1776.`

- [ ] **Verificar que la página /catalogo/ usa el template correcto**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post meta get 1776 _wp_page_template"
```

Esperado: `templates/page-ecosistemas.php`

---

### Task A-4: Fix encoding en páginas de planes

Los precios tienen encoding roto (`.200.000/mes` en lugar de `$200.000/mes`). Corregir vía WP-CLI.

- [ ] **Fix ID 1997 — WordPress Básico**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 1997 \
  --post_title='WordPress Básico' \
  --post_content='<h1>WordPress Básico</h1><p>Hosting WordPress económico para proyectos iniciales.</p><p><strong>Precio:</strong> \$96.000 COP/mes</p><p><a href=\"/catalogo/\">Ver todos los planes</a></p>'"
```

- [ ] **Fix ID 1998 — WordPress Deluxe**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 1998 \
  --post_title='WordPress Deluxe' \
  --post_content='<h1>WordPress Deluxe</h1><p>Hosting WordPress optimizado para negocios en crecimiento.</p><p><strong>Precio:</strong> \$50.000 COP/mes</p><p><a href=\"/catalogo/\">Ver todos los planes</a></p>'"
```

- [ ] **Fix ID 1999 — WordPress Ultimate**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 1999 \
  --post_title='WordPress Ultimate' \
  --post_content='<h1>WordPress Ultimate</h1><p>Hosting WordPress de alto rendimiento para comercio electrónico.</p><p><strong>Precio:</strong> \$90.000 COP/mes</p><p><a href=\"/catalogo/\">Ver todos los planes</a></p>'"
```

- [ ] **Fix ID 2000 — cPanel Ultimate**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2000 \
  --post_title='cPanel Ultimate' \
  --post_content='<h1>cPanel Ultimate</h1><p>cPanel ilimitado para agencias y alto tráfico.</p><p><strong>Precio:</strong> \$200.000 COP/mes</p><p><a href=\"/catalogo/\">Ver todos los planes</a></p>'"
```

- [ ] **Verificar que los 4 posts tienen precios correctos**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && for id in 1997 1998 1999 2000; do echo \"=== \$id ===\"; wp post get \$id --field=post_content | grep -o 'Precio.*'; done"
```

Esperado: cada uno muestra `Precio:</strong> $XX.000 COP/mes` sin encoding roto.

---

### Task A-5: Trash página "Producto" (ID 1996)

- [ ] **Mover a trash (reversible)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post delete 1996 --force=false"
```

Esperado: `Success: Trashed post 1996.`

---

### Task A-6: Forzar PFID sync

- [ ] **Verificar estado actual de PFIDs**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp option list --search='gano_pfid*' --format=csv"
```

- [ ] **Si el resultado está vacío, disparar el sync workflow**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
gh workflow run 30-reseller-catalog-sync.yml --ref main
```

- [ ] **Verificar PFIDs después del sync (esperar 60s)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp option list --search='gano_pfid*' --format=csv"
```

Esperado: al menos 4 entradas con PFIDs numéricos.

---

### Task A-7: Commit, push y PR

- [ ] **Verificar que el único cambio en el repo es functions.php**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git status
```

Esperado: solo `wp-content/themes/gano-child/functions.php` modificado (los cambios WP-CLI son en el servidor, no en el repo).

- [ ] **Push y crear PR**

```bash
git push -u origin fix/commercial-catalog
gh pr create \
  --title "fix(commercial): catálogo canónico /catalogo/ + redirect ecosistemas + fix encoding precios" \
  --body "$(cat <<'EOF'
## Cambios

- Redirect 301 `/ecosistemas/` → `/catalogo/` en `functions.php`
- Template `page-ecosistemas.php` asignado a ID 1776 (`/catalogo/`)
- Shortcodes `[gano_reseller_iframe]` activados en `/catalogo/`
- Encoding fix en páginas de planes (IDs 1997–2000): precios en COP correcto
- Trash página "Producto" (ID 1996) — sin propósito definido

## Verificación

- [ ] `curl -I https://gano.digital/ecosistemas/` → HTTP 301 Location: /catalogo/
- [ ] `curl -s https://gano.digital/catalogo/` → contiene `gano_reseller_iframe` output
- [ ] `php -l functions.php` → sin errores de sintaxis

## Actions automáticos post-merge

- `30-reseller-catalog-sync.yml` se dispara para verificar PFIDs
EOF
)"
```

---

## Wave C — `fix/technical-cleanup`

**Archivos modificados:**
- Create dir: `wp-content/themes/gano-child/templates/deprecated/`
- Move (9 archivos): `templates/*.php|*.html` → `templates/deprecated/`
- Create: `wp-content/themes/gano-child/templates/README.md`
- Server-side WP-CLI: plugin deactivations, Rank Math DB fix

---

### Task C-1: Crear rama

- [ ] **Crear rama desde main actualizado**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git checkout main
git pull origin main
git checkout -b fix/technical-cleanup
```

---

### Task C-2: Mover templates huérfanos a deprecated/

- [ ] **Crear directorio deprecated/**

```bash
mkdir -p "C:\Users\diego\Downloads\Gano.digital-copia\wp-content\themes\gano-child\templates\deprecated"
```

- [ ] **Mover los 9 templates huérfanos**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia\wp-content\themes\gano-child\templates"

git mv page-ecosistemas-v2.php deprecated/page-ecosistemas-v2.php
git mv page-ecosistemas-v3.php deprecated/page-ecosistemas-v3.php
git mv page-showcase.php deprecated/page-showcase.php
git mv page-sota-hub.php deprecated/page-sota-hub.php
git mv shop-premium.php deprecated/shop-premium.php
git mv sota-single-template.php deprecated/sota-single-template.php
git mv page-dashboard-demo.php deprecated/page-dashboard-demo.php
git mv page-diagnostico-digital.php deprecated/page-diagnostico-digital.php
git mv homepage-2026-preview.html deprecated/homepage-2026-preview.html
```

- [ ] **Verificar que los moves están staged**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git status
```

Esperado: 9 entradas `renamed: templates/X → templates/deprecated/X`

---

### Task C-3: Crear templates/README.md

- [ ] **Crear el archivo**

Crear `wp-content/themes/gano-child/templates/README.md` con este contenido:

```markdown
# Templates activos — gano-child

Mapa de templates PHP a páginas WordPress en producción.

| Template | ID | Título | URL |
|----------|----|--------|-----|
| `page-ecosistemas.php` | 1776 | Catálogo de Productos | `/catalogo/` |
| `page-nosotros.php` | 1658 | Nuestra Filosofía | `/nosotros/` |
| `page-contacto.php` | 1662 | Contacto | `/contacto/` |
| `page-hosting.php` | 1660 | Ecosistemas de Infraestructura | `/hosting/` |
| `page-dominios.php` | 1659 | Registro de Dominios | `/dominios/` |
| `page-servicios.php` | 1661 | Blindaje y Optimización | `/servicios/` |
| `page-sla.php` | 1940 | Acuerdo de Nivel de Servicio | `/acuerdo-de-nivel-de-servicio/` |
| `page-privacidad.php` | 1939 | Política de Privacidad | `/politica-de-privacidad/` |
| `page-terminos.php` | 1672 | Términos y Condiciones | `/terminos-y-condiciones/` |
| `page-seo-landing.php` | — | SEO Landing | `/hosting-wordpress-colombia/` |
| `page-comenzar-aqui.php` | 1813 | Cómo Comprar | `/comenzar-aqui/` |
| `page-contacto-gracias.php` | — | Gracias por contactarnos | `/contacto-gracias/` |

## deprecated/

Templates retirados de uso activo (2026-06-17). No borrar — pueden ser referencia
para nuevos desarrollos o necesitarse en nuevos entornos.
```

- [ ] **Commit de templates**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git add wp-content/themes/gano-child/templates/
git commit -m "refactor(templates): mover 9 templates huérfanos a deprecated/, agregar README"
```

---

### Task C-4: Desactivar plugins phase + content-importer en servidor

- [ ] **Verificar plugins activos antes del cambio**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp plugin list --status=active --fields=name --format=csv"
```

- [ ] **Desactivar los 4 plugins**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp plugin deactivate gano-phase1-installer gano-phase2-business gano-phase3-content gano-content-importer"
```

Esperado: 4 líneas `Plugin 'X' deactivated.`

- [ ] **Verificar que el sitio sigue funcionando después de desactivar**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post list --post_status=publish --format=count"
```

Esperado: número > 0 (confirma que WP responde sin errores fatales).

- [ ] **Verificar plugins activos después del cambio**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp plugin list --status=active --fields=name --format=csv"
```

Esperado: lista que NO incluye `gano-phase1-installer`, `gano-phase2-business`, `gano-phase3-content`, `gano-content-importer`.

---

### Task C-5: Fix Rank Math DB (tablas faltantes)

- [ ] **Verificar tablas Rank Math actuales**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp db query \"SHOW TABLES LIKE '%rank_math%'\" 2>/dev/null"
```

- [ ] **Ciclo deactivate/activate para forzar recreación de tablas**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp plugin deactivate rank-math && wp plugin activate rank-math"
```

- [ ] **Si las tablas siguen faltando, forzar re-install de DB**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp option delete rank_math_install_version && wp plugin deactivate rank-math && wp plugin activate rank-math"
```

- [ ] **Verificar que las tablas existen**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp db query \"SHOW TABLES LIKE '%rank_math%'\" 2>/dev/null"
```

Esperado: al menos `wp_6ce773b45f_rank_math_meta` y `wp_6ce773b45f_rank_math_redirections` en la lista.

- [ ] **Verificar que el error_log ya no tiene errores de Rank Math**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "tail -20 /home/f1rml03th382/error_log | grep rank_math || echo 'Sin errores rank_math'"
```

Esperado: `Sin errores rank_math`

---

### Task C-6: Push y PR

- [ ] **Push y crear PR**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git push -u origin fix/technical-cleanup
gh pr create \
  --title "fix(technical): desactivar phase plugins, fix Rank Math DB, templates deprecated/" \
  --body "$(cat <<'EOF'
## Cambios en repo

- 9 templates huérfanos movidos a `templates/deprecated/`
- Nuevo `templates/README.md` con mapa completo de templates → páginas

## Cambios en servidor (WP-CLI, no en repo)

- Plugins desactivados: `gano-phase1-installer`, `gano-phase2-business`, `gano-phase3-content`, `gano-content-importer`
- Rank Math DB: tablas recreadas via deactivate/activate cycle

## Verificación

- [ ] `wp plugin list --status=active` no incluye phase1/2/3 ni content-importer
- [ ] `SHOW TABLES LIKE '%rank_math%'` retorna tablas
- [ ] `error_log` sin errores `rank_math_meta doesn't exist`

## Actions automáticos post-merge

- `08-health-check-plugins.yml`
- `31-plugin-health-check-phase4.yml`
EOF
)"
```

---

## Wave B — `feat/content-sota`

**Archivos modificados:**
- Server-side WP-CLI: post_content de IDs 2028–2039 (11 páginas), ID 1766 (Blog), trash ID 1745

---

### Task B-1: Crear rama

- [ ] **Crear rama**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
git checkout main
git pull origin main
git checkout -b feat/content-sota
```

---

### Task B-2: Contenido para las 9 páginas SOTA Architecture

Cada página sigue la estructura: H1 → párrafo intro → H2 características (4 bullets técnicos) → CTA a `/catalogo/`. Tono: "Soberanía Digital", técnico pero comercial.

- [ ] **ID 2028 — Fortaleza Zero-Trust (`/seguridad-zero-trust/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2028 --post_content='<h1>Fortaleza Zero-Trust: Seguridad que No Asume Nada</h1>
<p>En Gano Digital operamos bajo el principio fundamental de que ningún acceso — interno o externo — es confiable por defecto. Cada paquete, cada solicitud, cada conexión se verifica criptográficamente antes de recibir acceso a tu infraestructura.</p>
<h2>Arquitectura de Desconfianza Total</h2>
<ul>
<li><strong>Verificación Continua de Identidad</strong> — Autenticación multifactor en cada capa del stack, sin sesiones persistentes no verificadas.</li>
<li><strong>Microsegmentación de Red</strong> — Tu infraestructura dividida en zonas de confianza cero con políticas de acceso mínimo privilegiado.</li>
<li><strong>Cifrado AES-256 en Reposo y Tránsito</strong> — Ningún dato viaja o descansa sin cifrado de grado militar.</li>
<li><strong>Auditoría Forense en Tiempo Real</strong> — Cada acceso registrado con timestamp, IP y hash de integridad para trazabilidad total.</li>
</ul>
<p><a href=\"/catalogo/\">Activar protección Zero-Trust en tu ecosistema</a></p>'"
```

- [ ] **ID 2029 — Núcleo NVMe Gen4 (`/almacenamiento-nvme/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2029 --post_content='<h1>Núcleo NVMe Gen4: La Muerte de la Latencia</h1>
<p>El almacenamiento tradicional es el cuello de botella que frena tu competitividad digital. Nuestra infraestructura NVMe Gen4 opera a velocidades que hacen obsoleto cualquier SSD convencional — porque en la economía digital, la velocidad es la única ventaja sostenible.</p>
<h2>Especificaciones de Élite</h2>
<ul>
<li><strong>Velocidad de Lectura hasta 7.000 MB/s</strong> — 14x más rápido que un SSD SATA estándar. Tu aplicación responde antes de que el usuario note la solicitud.</li>
<li><strong>IOPS de Misión Crítica</strong> — Millones de operaciones por segundo sin degradación bajo carga de producción real.</li>
<li><strong>Latencia Sub-Microsegundo</strong> — Eliminamos el tiempo de búsqueda rotacional. Tu base de datos vive en velocidad de RAM.</li>
<li><strong>Redundancia RAID-NVMe Nativa</strong> — Tolerancia a fallos sin sacrificar rendimiento. Disponibilidad 99.9% garantizada por SLA.</li>
</ul>
<p><a href=\"/catalogo/\">Migrar tu infraestructura a NVMe Gen4</a></p>'"
```

- [ ] **ID 2030 — Soberanía Digital Absoluta (`/soberania-digital/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2030 --post_content='<h1>Soberanía Digital Absoluta: Tus Datos, Tu Jurisdicción</h1>
<p>La dependencia de infraestructura extranjera es el riesgo invisible que amenaza la continuidad de tu operación. Gano Digital opera bajo jurisdicción colombiana, con datos que permanecen bajo el marco legal de la Ley 1581 de 2012 — sin nubes opacas, sin servidores offshore, sin letra pequeña.</p>
<h2>Independencia Tecnológica Real</h2>
<ul>
<li><strong>Infraestructura en Jurisdicción Local</strong> — Tus datos sujetos a la legislación colombiana. Sin transferencias internacionales sin consentimiento explícito.</li>
<li><strong>Sin Lock-in de Proveedor</strong> — Arquitectura abierta que te permite migrar sin penalización. Tu infraestructura te pertenece.</li>
<li><strong>Auditoría de Cumplimiento DIAN-Ready</strong> — Logs y registros estructurados para satisfacer requerimientos de auditoría fiscal colombiana.</li>
<li><strong>Control Total de Backups</strong> — Tus respaldos bajo tu llave. Cifrados, versionados y accesibles únicamente por ti.</li>
</ul>
<p><a href=\"/catalogo/\">Recuperar la soberanía de tu infraestructura digital</a></p>'"
```

- [ ] **ID 2031 — Cerebro IA Predictivo (`/inteligencia-sintetica/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2031 --post_content='<h1>Cerebro IA Predictivo: Tu Infraestructura que Aprende</h1>
<p>La gestión reactiva de infraestructura es costosa e ineficiente. Nuestro sistema de inteligencia predictiva analiza patrones de tráfico, consumo de recursos y señales de anomalía para anticipar y resolver problemas antes de que impacten tu operación.</p>
<h2>Inteligencia Aplicada a tu Ecosistema</h2>
<ul>
<li><strong>Predicción de Picos de Tráfico</strong> — Modelos de series temporales que anticipan la demanda y escalan recursos automáticamente.</li>
<li><strong>Detección Temprana de Anomalías</strong> — Alertas ante patrones inusuales de acceso, consumo de CPU o latencia anormal antes de que escalen.</li>
<li><strong>Autocuración Autónoma</strong> — Procesos que detectan y remedian degradaciones de servicio sin intervención humana.</li>
<li><strong>Optimización Continua de Recursos</strong> — IA que reduce el desperdicio computacional y maximiza la eficiencia de tu inversión mensual.</li>
</ul>
<p><a href=\"/catalogo/\">Activar inteligencia predictiva en tu ecosistema</a></p>'"
```

- [ ] **ID 2032 — Malla de Borde Anycast (`/red-global-anycast/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2032 --post_content='<h1>Malla de Borde Anycast: Tu Contenido sin Distancia</h1>
<p>La latencia geográfica es el enemigo silencioso de tu conversión. Con enrutamiento Anycast BGP, cada solicitud de tu usuario colombiano se sirve desde el nodo más cercano de la red, eliminando el salto transatlántico que ralentiza tu competencia.</p>
<h2>Red de Distribución de Élite</h2>
<ul>
<li><strong>Enrutamiento BGP Anycast</strong> — La misma IP sirve desde múltiples puntos de presencia. El tráfico toma automáticamente el camino más corto.</li>
<li><strong>CDN con Lógica de Negocio</strong> — No solo archivos estáticos: lógica de aplicación ejecutada en el borde para respuestas sub-50ms.</li>
<li><strong>Failover Automático de Red</strong> — Si un nodo falla, el tráfico se redirige en milisegundos sin intervención manual.</li>
<li><strong>Compresión Brotli + HTTP/3</strong> — Protocolos modernos que reducen el payload hasta un 40% respecto a HTTP/1.1 con gzip.</li>
</ul>
<p><a href=\"/catalogo/\">Desplegar tu contenido en la malla Anycast</a></p>'"
```

- [ ] **ID 2033 — Elasticidad Serverless (`/computacion-serverless/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2033 --post_content='<h1>Elasticidad Serverless: Paga Solo lo que Consumes</h1>
<p>La infraestructura fija es un modelo económico del pasado. Con arquitectura serverless, tus recursos se escalan en tiempo real según la demanda real — sin servidores sobredimensionados en horas valle, sin cuellos de botella en picos de tráfico viral.</p>
<h2>Eficiencia Computacional Máxima</h2>
<ul>
<li><strong>Autoescalado de 0 a Infinito</strong> — Tu aplicación maneja un usuario o un millón con la misma arquitectura. El escalado es automático y transparente.</li>
<li><strong>Modelo de Costo por Uso Real</strong> — Facturas proporcionales al consumo efectivo. Sin costos fijos de servidores inactivos.</li>
<li><strong>Despliegue sin Gestión de Servidores</strong> — Enfoca tu equipo en el producto, no en la administración de infraestructura.</li>
<li><strong>Alta Disponibilidad por Diseño</strong> — Arquitectura sin punto único de fallo. La redundancia es inherente al modelo serverless.</li>
</ul>
<p><a href=\"/catalogo/\">Migrar a arquitectura serverless</a></p>'"
```

- [ ] **ID 2034 — Ecosistemas Híbridos SOTA (`/ecosistemas-hibridos/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2034 --post_content='<h1>Ecosistemas Híbridos SOTA: Lo Mejor de Dos Mundos</h1>
<p>La elección entre nube pública y privada es un falso dilema. Los ecosistemas híbridos de Gano Digital combinan la soberanía de infraestructura dedicada con la elasticidad de la nube — adaptándose a los requerimientos de tu negocio, no al revés.</p>
<h2>Arquitectura Híbrida de Precisión</h2>
<ul>
<li><strong>Carga de Trabajo Inteligente</strong> — Datos sensibles en infraestructura privada soberana; cargas dinámicas en nube elástica. Separación por política, no por limitación.</li>
<li><strong>Conectividad Privada Dedicada</strong> — Túneles cifrados entre entornos con latencia predecible. Sin tráfico sensible por internet público.</li>
<li><strong>Panel Unificado de Gestión</strong> — Visibilidad total sobre recursos híbridos desde una sola consola. Sin silos operacionales.</li>
<li><strong>Cumplimiento Regulatorio Garantizado</strong> — Arquitectura diseñada para satisfacer Ley 1581, ISO 27001 y requerimientos sectoriales colombianos.</li>
</ul>
<p><a href=\"/catalogo/\">Diseñar tu ecosistema híbrido</a></p>'"
```

- [ ] **ID 2035 — Edge Computing de Autoridad (`/edge-computing-pro/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2035 --post_content='<h1>Edge Computing de Autoridad: Procesamiento donde Vive tu Usuario</h1>
<p>Cada milisegundo de latencia es conversión perdida. El Edge Computing acerca el procesamiento al usuario final — eliminando el viaje de datos al datacenter central y ejecutando lógica crítica a metros de quien la solicita.</p>
<h2>Inteligencia en el Borde de la Red</h2>
<ul>
<li><strong>Ejecución de Lógica en el Borde</strong> — Autenticación, personalización y filtrado ejecutados antes de llegar al origen. Tiempo de respuesta sub-20ms.</li>
<li><strong>Caché Inteligente con TTL Dinámico</strong> — Contenido cacheado con reglas que entienden la naturaleza de tu aplicación, no solo extensiones de archivo.</li>
<li><strong>Protección DDoS Distribuida</strong> — El ataque se absorbe en el borde antes de tocar tu infraestructura principal.</li>
<li><strong>Streaming de Alto Volumen sin Saturación</strong> — Distribución de assets pesados (video, imágenes 4K) sin impactar el rendimiento de tu aplicación core.</li>
</ul>
<p><a href=\"/catalogo/\">Activar Edge Computing en tu infraestructura</a></p>'"
```

- [ ] **ID 2036 — Ciber-Resiliencia Fractal (`/ciber-resiliencia-fractal/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2036 --post_content='<h1>Ciber-Resiliencia Fractal: Tu Negocio Indestructible</h1>
<p>La resiliencia real no es sobrevivir un ataque — es no notarlo. Nuestra arquitectura fractal distribuye la operación en capas redundantes que se autocuran y se reorganizan ante cualquier perturbación, manteniendo la continuidad de tu negocio sin intervención humana.</p>
<h2>Arquitectura de Supervivencia</h2>
<ul>
<li><strong>Redundancia N+2 en Cada Capa</strong> — Almacenamiento, red y cómputo con capacidad excedente que absorbe fallos sin degradación perceptible.</li>
<li><strong>Backups Continuos con RPO Cero</strong> — Replicación en tiempo real. En caso de desastre, el punto de recuperación es el instante anterior al incidente.</li>
<li><strong>Respuesta Automática a Incidentes</strong> — Playbooks predefinidos que se ejecutan en segundos ante patrones de ataque conocidos. Sin esperar al equipo humano.</li>
<li><strong>Pruebas de Resiliencia Periódicas</strong> — Simulaciones de fallo programadas para validar que los mecanismos de autocuración funcionan antes de que los necesites.</li>
</ul>
<p><a href=\"/catalogo/\">Blindar tu infraestructura con resiliencia fractal</a></p>'"
```

---

### Task B-3: Páginas SOTA especiales

- [ ] **ID 2039 — Arquitectura Cloud SOTA (`/arquitectura-cloud/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2039 --post_content='<h1>Arquitectura Cloud SOTA: El Estado del Arte en Infraestructura</h1>
<p>SOTA — State of the Art — no es un slogan. Es el estándar de ingeniería que aplicamos a cada componente de tu infraestructura. Desde el almacenamiento NVMe hasta el enrutamiento Anycast, cada capa está diseñada con las mejores prácticas disponibles en 2026.</p>
<h2>Los Pilares de la Arquitectura SOTA</h2>
<ul>
<li><strong>NVMe Gen4 + Zero-Trust</strong> — Velocidad máxima con seguridad por diseño. Rendimiento sin compromisos de seguridad.</li>
<li><strong>Anycast BGP + Edge Computing</strong> — Distribución global con procesamiento local. El usuario siempre conecta al nodo óptimo.</li>
<li><strong>IA Predictiva + Autocuración</strong> — Infraestructura que aprende y se repara. Operaciones sin fricción humana.</li>
<li><strong>Soberanía Digital + Cumplimiento</strong> — Control total bajo jurisdicción colombiana. Arquitectura que respeta la ley y tu negocio.</li>
</ul>
<p><a href=\"/catalogo/\">Explorar todos los ecosistemas SOTA</a></p>'"
```

- [ ] **ID 2037 — Gano SOTA Index (`/catalogo-sota/`)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 2037 --post_content='<h1>Tecnologías SOTA — Estado del Arte en Gano Digital</h1>
<p>Cada componente de nuestra infraestructura está seleccionado por ser la mejor solución disponible en su categoría. Este índice documenta las tecnologías que conforman nuestro ecosistema de hosting de élite.</p>
<h2>Índice de Tecnologías</h2>
<ul>
<li><a href=\"/almacenamiento-nvme/\">Núcleo NVMe Gen4</a> — Almacenamiento de velocidad extrema</li>
<li><a href=\"/seguridad-zero-trust/\">Fortaleza Zero-Trust</a> — Seguridad sin asunciones</li>
<li><a href=\"/soberania-digital/\">Soberanía Digital Absoluta</a> — Control y jurisdicción local</li>
<li><a href=\"/inteligencia-sintetica/\">Cerebro IA Predictivo</a> — Infraestructura que aprende</li>
<li><a href=\"/red-global-anycast/\">Malla de Borde Anycast</a> — Contenido sin distancia geográfica</li>
<li><a href=\"/computacion-serverless/\">Elasticidad Serverless</a> — Escala automática por demanda</li>
<li><a href=\"/ecosistemas-hibridos/\">Ecosistemas Híbridos</a> — Soberanía + elasticidad combinadas</li>
<li><a href=\"/edge-computing-pro/\">Edge Computing de Autoridad</a> — Procesamiento en el borde</li>
<li><a href=\"/ciber-resiliencia-fractal/\">Ciber-Resiliencia Fractal</a> — Infraestructura indestructible</li>
<li><a href=\"/arquitectura-cloud/\">Arquitectura Cloud SOTA</a> — El estado del arte completo</li>
</ul>
<p><a href=\"/catalogo/\">Ver planes y precios</a></p>'"
```

---

### Task B-4: Fix Blog y trash Inicio

- [ ] **ID 1766 — Blog: activar listado real**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post update 1766 --post_content='<!-- El listado de artículos se genera dinámicamente por WordPress. Esta página muestra los últimos artículos del blog de Gano Digital sobre hosting, seguridad y soberanía digital. -->'"
```

Nota: El content del Blog es generado por WordPress automáticamente cuando la página está configurada como "Posts page" en Ajustes → Lectura. Verificar que sea así:

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp option get page_for_posts"
```

Esperado: `1766`. Si retorna `0`, ejecutar:

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp option update page_for_posts 1766"
```

- [ ] **ID 1745 — Trash página "Inicio" (Elementor placeholder)**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && wp post delete 1745 --force=false"
```

Esperado: `Success: Trashed post 1745.`

---

### Task B-5: Verificar todas las páginas

- [ ] **Contar páginas SOTA que aún tienen contenido placeholder**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && for id in 2028 2029 2030 2031 2032 2033 2034 2035 2036 2037 2039; do content=\$(wp post get \$id --field=post_content 2>/dev/null); if echo \"\$content\" | grep -q 'SOTA Architecture Content\|SOTA Catalog\|SOTA Cloud Architecture'; then echo \"PENDIENTE: ID \$id\"; else echo \"OK: ID \$id\"; fi; done"
```

Esperado: todas las líneas dicen `OK: ID XXXX`.

- [ ] **Verificar que todas las páginas SOTA tienen H1**

```bash
ssh -i ~/.ssh/id_rsa_deploy -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && for id in 2028 2029 2030 2031 2032 2033 2034 2035 2036 2037 2039; do has_h1=\$(wp post get \$id --field=post_content | grep -c '<h1>'); echo \"ID \$id: H1=\$has_h1\"; done"
```

Esperado: `H1=1` para todas.

---

### Task B-6: Commit y PR

La Wave B no tiene cambios en el repo (todo es WP-CLI en servidor). Para mantener trazabilidad, se crea un archivo de registro.

- [ ] **Crear archivo de trazabilidad**

Crear `docs/content-updates/2026-06-17-sota-pages-content.md`:

```markdown
# Actualización de contenido — Páginas SOTA

**Fecha:** 2026-06-17
**Método:** WP-CLI directo en servidor producción
**Branch:** feat/content-sota

## Páginas actualizadas

| ID | Slug | Estado |
|----|------|--------|
| 2028 | /seguridad-zero-trust/ | Contenido real agregado |
| 2029 | /almacenamiento-nvme/ | Contenido real agregado |
| 2030 | /soberania-digital/ | Contenido real agregado |
| 2031 | /inteligencia-sintetica/ | Contenido real agregado |
| 2032 | /red-global-anycast/ | Contenido real agregado |
| 2033 | /computacion-serverless/ | Contenido real agregado |
| 2034 | /ecosistemas-hibridos/ | Contenido real agregado |
| 2035 | /edge-computing-pro/ | Contenido real agregado |
| 2036 | /ciber-resiliencia-fractal/ | Contenido real agregado |
| 2037 | /catalogo-sota/ | Índice SOTA agregado |
| 2039 | /arquitectura-cloud/ | Contenido real agregado |
| 1766 | /blog/ | Configurado como Posts page |
| 1745 | /inicio/ | Movida a trash |
```

- [ ] **Commit y PR**

```bash
cd "C:\Users\diego\Downloads\Gano.digital-copia"
mkdir -p docs/content-updates
git add docs/content-updates/2026-06-17-sota-pages-content.md
git commit -m "feat(content): 11 páginas SOTA con copy real + fix blog + trash Inicio"
git push -u origin feat/content-sota
gh pr create \
  --title "feat(content): 11 páginas SOTA vacías con copy real, fix blog, trash Inicio" \
  --body "$(cat <<'EOF'
## Cambios

- 9 páginas SOTA Architecture: contenido real con H1, características técnicas, CTA a /catalogo/
- Gano SOTA Index: índice con links a todas las páginas SOTA
- Arquitectura Cloud SOTA: descripción del stack completo
- Blog: configurado como Posts page (page_for_posts = 1766)
- Inicio (ID 1745): movida a trash — el home real es ID 1657

## Verificación

- [ ] Ninguna página publicada tiene solo comentarios HTML
- [ ] Todas las páginas SOTA tienen H1 + 4 bullets + CTA
- [ ] /blog/ lista los 6 posts existentes
- [ ] /inicio/ retorna 404 o redirect

## Actions automáticos post-merge

- `07-sync-content.yml`
EOF
)"
```

---

## Criterios de éxito finales

### Wave A ✓ cuando:
- `curl -I https://gano.digital/ecosistemas/` → `HTTP/1.1 301` con `Location: https://gano.digital/catalogo/`
- `curl -s https://gano.digital/catalogo/` → contiene texto `Núcleo Prime` o `Fortaleza Delta`
- `wp option list --search="gano_pfid*"` → ≥4 entradas

### Wave C ✓ cuando:
- `wp plugin list --status=active --fields=name` → no incluye `gano-phase1-installer`, `gano-phase2-business`, `gano-phase3-content`, `gano-content-importer`
- `wp db query "SHOW TABLES LIKE '%rank_math%'"` → ≥2 tablas
- `ls templates/deprecated/` → 9 archivos

### Wave B ✓ cuando:
- `wp post list --post_type=page --post_status=publish | grep "SOTA Architecture Content"` → 0 resultados
- `wp option get page_for_posts` → `1766`
- `wp post get 1745 --field=post_status` → `trash`
