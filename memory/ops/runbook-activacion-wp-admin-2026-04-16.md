# Runbook: Activación de plugins Gano en wp-admin (sin SSH)

**Fecha**: 2026-04-17
**Audiencia**: Diego (operación manual desde navegador)
**Objetivo**: Activar los plugins de fase en orden correcto, configurar PFIDs y dejar `gano.digital` lista para comercializar.
**Duración estimada**: 30–45 min.
**Pre-requisitos**: Acceso `https://gano.digital/wp-admin/` + cPanel File Manager (solo paso 4).

---

## 0. Pre-check · Backup (obligatorio)

1. GoDaddy → **My Products** → Managed WordPress (gano.digital).
2. Clic en **Settings** → **Back Up Now**.
3. Espera "Backup completed". Anota fecha y hora.

> ⚠️ No continúes sin backup: las fases instalan contenido y modifican WooCommerce.

---

## 1. Eliminar `wp-file-manager` (CVE crítico)

1. `wp-admin → Plugins → Instalados`.
2. Busca `WP File Manager`.
3. **Desactivar** → **Eliminar** → confirmar.
4. Verifica: la alerta en MU plugin `gano-security.php` (si aparece en el dashboard) debe desaparecer en el siguiente refresh.

Si no está instalado, salta al paso 2.

---

## 2. Activar plugins de fase **en orden estricto**

Activa uno a la vez, **espera a que la página recargue**, verifica el criterio de éxito, luego activa el siguiente. Si falla cualquiera, para y revisa §Troubleshooting.

| # | Plugin a activar | Criterio de éxito tras activar |
|---|---|---|
| 1 | `gano-phase1-installer` | `wp-content/mu-plugins/gano-security.php` presente · `.htaccess` reforzado |
| 2 | `gano-phase2-business` | WooCommerce → Ajustes → General: moneda **COP**, zona **Bogotá** |
| 3 | `gano-phase3-content` | Páginas: Home, Contacto, Términos, etc. creadas |
| 4 | `gano-content-importer` | Páginas: 20 páginas SOTA en estado **Borrador** |
| 5 | `gano-phase6-catalog` | Productos → **4 ecosistemas** visibles (Núcleo Prime, Fortaleza Delta, Bastión SOTA, Ultimate WP) |
| 6 | `gano-phase7-activator` | Menú principal publicado · templates asignados a páginas clave |
| 7 | `gano-reseller-enhancements` | **Ya activado** (hoy 2026-04-17). Solo confirmar que sigue activo |

> ℹ️ **Los plugins 1–4 son one-shot installers**: su hook de activación corre una sola vez. Tras ejecutarse puedes **desactivarlos** (paso 5). **No los elimines del repositorio** hasta confirmar que el contenido quedó en el sitio.

---

## 3. Configurar los 8 PFID (checkout Reseller)

1. Abre `wp-admin → Ajustes → Gano Reseller`.
2. Para cada campo, pega el PFID numérico que obtuviste del RCC (guía en `memory/commerce/rcc-pfid-checklist.md`):

   **Hosting WordPress**
   - [ ] WordPress Hosting Economy (Núcleo Prime)
   - [ ] WordPress Hosting Deluxe (Fortaleza Delta)
   - [ ] WordPress Hosting Premium (Bastión SOTA)
   - [ ] WordPress Hosting Ultimate (Ultimate WP)

   **Seguridad y SSL**
   - [ ] SSL DV Deluxe
   - [ ] Website Security Premium

   **Email y Almacenamiento**
   - [ ] Microsoft 365 Business Premium
   - [ ] Online Storage 1 TB

3. Clic en **Guardar PFIDs**. Si algún valor es inválido verás error rojo arriba.
4. Cuando los 8 estén configurados aparece banner verde **"✓ Checkout listo"**.

> ℹ️ Sin PFIDs los CTAs de `/ecosistemas` redirigen a `/contacto` (comportamiento seguro, no crashean).

---

## 4. Constantes en `wp-config.php` (opcional · cPanel File Manager)

Sólo si quieres activar el chat IA real o el endpoint de auditoría Phase 6.

1. cPanel → **File Manager** → `/home/<user>/public_html/gano.digital/wp-config.php`.
2. Abre con **Edit** (crea copia de seguridad automática).
3. Justo antes de la línea `/* That's all, stop editing! */` añade:

   ```php
   // Gano Digital — secretos
   define( 'GANO_API_TOKEN',      '' ); // OpenAI/LLM key. Vacío = fallback estático.
   define( 'GANO_AGENT_API_KEY',  '' ); // Auditoría Phase 6. Vacío = endpoint 503.
   ```

4. Pega los valores reales entre las comillas. Guarda.
5. Verifica: no hay errores al recargar cualquier página del sitio.

> ⚠️ `wp-config.php` **no está en el repo git**. Estos valores viven solo en el servidor.

---

## 5. Desactivar one-shots (solo tras confirmar instalación)

Una vez **verificado** que el contenido y configuración de cada fase están en el sitio:

1. `wp-admin → Plugins → Instalados`.
2. **Desactivar** (no eliminar) los 4 one-shots:
   - [ ] `gano-phase1-installer`
   - [ ] `gano-phase2-business`
   - [ ] `gano-phase3-content`
   - [ ] `gano-content-importer`
3. Mantén **activos** de forma permanente:
   - `gano-phase6-catalog` (sincroniza catálogo cuando sea necesario)
   - `gano-phase7-activator` (mantiene templates/menú)
   - `gano-reseller-enhancements` (filtros carrito + panel PFID)

> ⚠️ **No elimines aún los plugins desactivados del repo**. Diego + Claude los marcarán como "archivable" en una tarea posterior cuando `memory/notes/plugins-de-fase.md` así lo indique.

---

## 6. Validación final

1. Navega a `https://gano.digital/` — home carga sin errores.
2. `https://gano.digital/ecosistemas/` — los 4 planes visibles con SLAs (no debe aparecer "por confirmar", "a definir", "según política").
3. Clic en **"Elegir Núcleo Prime"** (o cualquier CTA):
   - Si los PFIDs están configurados → abre `cart.secureserver.net/?plid=...&items=[...]` en nueva pestaña.
   - Si no → redirige a `/contacto` (comportamiento esperado).
4. Dashboard wp-admin → ningún warning rojo (el único aceptable: `GANO_AGENT_API_KEY no definida` si decidiste no configurarla).
5. Ajustes → Gano Reseller — revisa contador de PFIDs configurados.

---

## Troubleshooting

| Síntoma | Causa probable | Acción |
|---|---|---|
| **Activar phase-N falla con "Plugin could not be activated"** | Dependencia previa no corrió | Desactiva todos los phases y re-actívalos en orden desde el 1 |
| **Pantalla blanca tras activar phase6** | `ArgumentCountError` ya resuelto en commit `0c19cdd6` | Actualizar vía deploy (Workflow 04) o SFTP antes de reintentar |
| **"Missing table" al ver productos WooCommerce** | Phase 2 no corrió completo | Desactiva phase6 → re-activa phase2 → verifica WooCommerce → re-activa phase6 |
| **CTA abre `#` en lugar del carrito** | PFID sigue en `PENDING_RCC` | Ajustes → Gano Reseller → introduce el valor |
| **Panel "Gano Reseller" no aparece en Ajustes** | `gano-reseller-enhancements` desactivado | Plugins → activar `gano-reseller-enhancements` |
| **PFID rechazado al guardar** | Formato inválido (debe ser 3–10 dígitos o `PENDING_RCC`) | Revisa el valor en RCC: número puro sin prefijos ni guiones |
| **Dashboard muestra "auditoría degradada"** | `GANO_AGENT_API_KEY` no definida | Opcional: paso 4 de este runbook |

---

## Orden de reversión (si algo sale mal)

1. Desactivar en orden inverso: phase7 → phase6 → content-importer → phase3 → phase2 → phase1.
2. Restaurar backup (paso 0) desde **GoDaddy → Managed WordPress → Backups**.
3. Crear issue en el repo con logs del error y estado al momento del fallo.

---

**Autor**: Claude (Opus 4.7) · **Última actualización**: 2026-04-17
**Referencias**: `memory/notes/plugins-de-fase.md` · `memory/commerce/rcc-pfid-checklist.md` · `.github/DEV-COORDINATION.md`
