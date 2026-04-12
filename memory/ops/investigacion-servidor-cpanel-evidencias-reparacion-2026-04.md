# Investigación servidor cPanel — evidencias (capturas) + lógica de contenidos + plan de reparación

**Fecha:** 2026-04-10  
**Fuente:** capturas de pantalla del panel (cPanel Jupiter, Dominios, Estadísticas, Installatron, Site Publisher) + conocimiento del repo **Pilot** (WordPress / Elementor / WooCommerce en `gano.digital`).  
**Alcance:** diagnóstico evidenciable; acciones en servidor las ejecuta el humano o soporte GoDaddy con privilegios adecuados.

---

## 1. Mapa lógico del hosting (según capturas)

| Activo | Valor evidenciado | Nota |
|--------|-------------------|------|
| **Dominio principal (cuenta)** | `gano.bio` | Document root: `/public_html` |
| **Dominio adicional (addon)** | `gano.digital` | Document root: `/public_html/gano.digital` |
| **IP compartida** | Visible en “Información general” | Coherente con hosting compartido GoDaddy |
| **Usuario cPanel** | Visible en panel y logs Installatron | No repetir en documentación pública del repo |
| **Tema cPanel** | Jupiter | — |
| **RAM límite** | 512 MB (uso ~40 MB en captura) | Techo bajo si conviven muchos procesos PHP + cron + runner |

**Lógica de contenidos en dos capas (crítico):**

1. **Sitio de negocio Gano Digital (repo + TASKS):** WordPress en **`gano.digital`** → ruta de disco **`…/public_html/gano.digital`**. Ahí viven tema `gano-child`, Elementor, WooCommerce, MU-plugins Gano, etc. **Esta es la fuente de verdad del producto.**

2. **Aplicación Installatron etiquetada “Mi sistema de gestión de contenido”:** en el panel aparece como **Drupal 11.3.x**, URL indicada **`https://gano.digital/123/`**, gestión vía **Installatron** (pestañas Estado / Backups / Registros / Configuración).

**Conclusión de arquitectura:** En la misma cuenta coexisten (a) **WordPress productivo** en el subdirectorio del addon domain y (b) **una instalación Drupal** bajo ruta tipo **`/123/`** (segmento de URL no estándar para un sitio corporativo). Eso explica confusión en “qué CMS manda” y riesgo de que herramientas automáticas (actualizaciones, backups Installatron) apunten a rutas o dominios incorrectos.

---

## 2. Problemas evidenciables (lista cerrada)

### P0 — Integridad y gobernanza del CMS “gestionado” por Installatron

| ID | Evidencia | Interpretación |
|----|-----------|----------------|
| **E-01** | Mensaje PHP en Installatron: `failed_application_config_doesnotexist` / `_errors_noconfigfile` | Installatron **no encuentra o no puede leer** el archivo de configuración de la instalación registrada. Estado interno **roto** para esa app aunque la UI muestre checks verdes en otros campos. |
| **E-02** | Registros: desde ~24-03-2026 errores repetidos **“Unable to read source install configuration file”** (eventos Installs / Change) | Coherente con E-01: la metadata o el archivo fuente de la instalación está **ausente, movido o con permisos incorrectos**. |
| **E-03** | Actualizaciones automáticas fallidas: `curl` a `https://gano.bio/123/update.php` → **HTTP 400** | El **endpoint de actualización de Drupal** parece resolverse contra **`gano.bio`** con path `/123/`, mientras la URL “lógica” del sitio en Installatron es **`gano.digital/123`**. **Incoherencia dominio/path** → las automatizaciones fallan. |
| **E-04** | “Última copia de seguridad”: **ninguna**; “Siguiente automática”: **no configurada** | **Riesgo de pérdida de datos** para lo que Installatron considera su instalación (Drupal en esa ruta). No sustituye backups del WordPress en `gano.digital`. |

### P1 — SSL / HTTPS y percepción de seguridad

| ID | Evidencia | Interpretación |
|----|-----------|----------------|
| **E-05** | Dominio **principal** `gano.bio`: **Force HTTPS Redirect = Apagado** + icono de advertencia | Tráfico puede entrar en **HTTP** en el dominio principal; malo para SEO y para usuarios que enlazan sin esquema. |
| **E-06** | Icono de alerta junto a “Dominio principal” en Información general + botón **SSL/TLS Status** | Posible **certificado pendiente, parcial o nombre alternativo** no cubierto; requiere revisión en SSL/TLS Status y AutoSSL. |
| **E-07** | `gano.digital`: **Force HTTPS** con estado **ambiguo** (icono “?” en captura Dominios) | Puede ser **certificado**, **herencia desde document root**, o conflicto con `.htaccess` del padre; hay que unificar criterio (cPanel + WP `siteurl`/`home`). |

### P2 — Recursos y operación

| ID | Evidencia | Interpretación |
|----|-----------|----------------|
| **E-08** | Límite **512 MB** RAM | Para WordPress + Elementor + plugins + tráfico, es **apretado**; picos pueden generar errores 500 o throttling si el plan no escala. |
| **E-09** | Site Publisher advierte: directorios **ya contienen otros archivos** | Correcto: no usar Site Publisher para “pisar” `public_html` ni `gano.digital` sin backup. |
| **E-10** | Fechas/metadata Installatron (“Managed Since” futuro / “Última actualización: Nunca” en captura) | Sugiere **reloj**, **zona horaria** o **metadata corrupta**; secundario frente a E-01–E-04 pero indica desorden en la herramienta. |

### P3 — Coherencia con el repositorio Pilot (lógica “verdad” del negocio)

| ID | Evidencia lógica | Interpretación |
|----|------------------|----------------|
| **E-11** | Repo y `TASKS.md` describen **WordPress**; cPanel “Aplicaciones” destaca **Drupal** como “Mi sistema de gestión de contenido” | **Dos narrativas:** el negocio es WP; Installatron gestiona un **Drupal en `/123/`** que puede ser residual, de prueba o mal renombrado. Si **no aporta al negocio**, es **deuda técnica y vector de confusión** (y posible superficie de ataque si queda abandonado). |
| **E-12** | GitHub **Pilot** ya es **público** + runner self-hosted (documentado en `activeContext` / `deferredItems`) | Riesgo **CI** aparte del hosting; no sustituye reparar cPanel pero debe cerrarse en paralelo. |

---

## 3. Investigación SOTA (primera pasada — literatura y práctica)

| Tema | Recomendación SOTA | Referencia |
|------|-------------------|------------|
| **Installatron corrupto** | Reparación a nivel **administrador del servidor** (`installatron --repair`, recache); en hosting compartido: **ticket a GoDaddy** con capturas y errores E-01/E-02. | [Installatron troubleshooting](https://installatron.com/docs/troubleshooting) |
| **HTTPS en cPanel** | Preferir **Force HTTPS** nativo por dominio; revisar **AutoSSL**; evitar reglas `.htaccess` en raíz que rompan addon en subcarpeta. | [cPanel Force HTTPS](https://www.cpanel.net/blog/products/force-https-redirection/), [cPanel redirects + subdirs](https://support.cpanel.net/hc/en-us/articles/13302137917719-Prevent-redirects-from-affecting-subdirectories) |
| **OWASP / cadena de suministro** | Inventario de **todas** las apps instaladas (WP, Drupal, Installatron); eliminar o aislar lo no usado. | [OWASP A03:2025](https://owasp.org/Top10/2025/A03_2025-Software_Supply_Chain_Failures/) |
| **Backups** | **3-2-1**; al menos backups **GoDaddy** + copia **descargable** de BD + `wp-content` para WP; no depender solo de Installatron si está roto. | Buenas prácticas generales |

---

## 4. Segunda pasada SOTA (con datos ya consolidados del propio informe)

Incorporando las evidencias E-01 a E-12:

1. **Prioridad absoluta:** separar mentalmente **WordPress (`gano.digital`)** vs **Drupal/Installatron (`/123/`)**. Las reparaciones de Installatron **no** arreglan Elementor ni la tienda WooCommerce salvo que compartan archivos (poco probable si WP está en `…/gano.digital`).

2. **El fallo 400 en `gano.bio/123/update.php`** indica que las **actualizaciones automáticas de Drupal** están mal **apuntadas** (dominio principal vs addon, o path inexistente en el vhost de `gano.bio`). SOTA: **corregir URL base en Installatron** o **re-sincronizar** la instalación tras reparación; si Drupal no se usa, **desinstalar** desde Installatron (con backup previo) reduce superficie.

3. **SSL en `gano.bio`:** aunque el negocio sea `gano.digital`, un principal en HTTP con advertencia **afecta reputación y herramientas** que escanean el hostname de la cuenta.

4. **512 MB RAM:** SOTA para WP medianos en hosting compartido incluye **caché objeto**, **limitar plugins**, **PHP 8.x** y valorar **plan superior** si hay 500 intermitentes.

5. **Repo público:** mantener alineado con [`sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) (runner, secretos).

---

## 5. Plan de reparación (fases)

### Fase 0 — Inventario sin tocar producción (1–2 h)

- [ ] En **Administrador de archivos** o SSH: confirmar si existe directorio físico correspondiente a **`gano.digital/123`** (o `123` bajo `public_html/gano.digital`) y si contiene un `settings.php` de Drupal.
- [ ] Anotar **qué URL pública** responve hoy el sitio WordPress real (`https://gano.digital` sin `/123`).
- [ ] Decisión de negocio: **¿Drupal en `/123/` sigue siendo necesario?** Si no → candidato a eliminación tras backup.

### Fase 1 — SSL y dominios (P1, mismo día si es posible)

- [ ] **SSL/TLS Status** / AutoSSL: validar cobertura para `gano.bio`, `www`, `gano.digital`, `www`.
- [ ] Activar **Force HTTPS** para **gano.digital** primero (sitio de ingresos); luego evaluar `gano.bio` según uso real del dominio principal.
- [ ] En WordPress (`gano.digital`): `Ajustes → Generales` → URLs en **https**; buscar contenido mixto si hace falta.

### Fase 2 — Installatron / Drupal (P0)

- [ ] Abrir ticket **GoDaddy** con: texto exacto del error `failed_application_config_doesnotexist`, logs “Unable to read source install configuration file”, y el fallo **400** en `update.php`. Pedir **reparación Installatron** o **resincronización** de la instalación.
- [ ] Si el proveedor no resuelve: valorar **clonar** instalación a ruta limpia o **eliminar** registro Installatron tras backup de la carpeta `123` (si existe).
- [ ] Configurar **al menos un backup** (Installatron si queda sano, o backup de cuenta cPanel / plugin WP para el sitio real).

### Fase 3 — WordPress productivo (paralelo, no bloqueado por Drupal)

- [ ] Backups regulares de **solo** `…/gano.digital` + BD asociada.
- [ ] Lista de plugins; quitar riesgo (`wp-file-manager` si aún existe en prod).
- [ ] Monitorizar uso de memoria; si hay 500, correlacionar con pico de RAM.

### Fase 4 — GitHub / runner (ya documentado fuera de este archivo)

- [ ] Cerrar riesgo **repo público + self-hosted** según `deferredItems` y `activeContext`.

---

## 6. Qué **no** se puede afirmar sin más evidencia

- Si el **Drupal** sirve tráfico público real o es solo un registro huérfano.
- Contenido exacto del disco sin `ls` o File Manager en rutas `123`.
- Si **gano.bio** debe redirigir a **gano.digital** (decisión de marca/DNS).

---

## 7. Referencias cruzadas en el repo

| Recurso | Uso |
|---------|-----|
| `.gano-skills/gano-cpanel-ssh-management/SKILL.md` | Actualizado § híbrido WP + Installatron |
| `TASKS.md` | Active (SSL, wp-file-manager, deploy) |
| `memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md` | Repo público + runners |
| `.cursor/memory/activeContext.md` | Foco y bloqueadores |

---

_Actualizar este documento cuando GoDaddy confirme reparación Installatron o cuando se elimine la instalación `/123/`._
