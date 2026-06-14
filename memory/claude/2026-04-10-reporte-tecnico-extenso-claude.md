# Reporte técnico extenso — Gano Digital (Pilot) — handoff Claude

**Fecha de elaboración:** 2026-04-10  
**Audiencia:** Claude (u otro agente) con el repo **Gano-digital/Pilot** adjunto o clonado.  
**Propósito:** un solo documento con **problemas identificados**, **soluciones posibles**, **estrategias ya intentadas** y **prioridades**, sin depender del historial de chat Cursor.

**Reglas de lectura**

- **Hecho en `main` en git** ≠ **desplegado en producción** salvo verificación explícita (checksum, SFTP, webhook).
- No asumir credenciales en el repo: `wp-config.php` está en `.gitignore`; secretos reales solo en GitHub Secrets, panel GoDaddy o variables locales.
- Tras **repo público**, cualquier dato operativo sensible debe evitarse en issues públicos (usuario cPanel, rutas `/home/...` reales); en git se usan placeholders en skills (`<IP_SERVIDOR>`, `<USUARIO_CPANEL>`).

---

## Parte A — Resumen ejecutivo maestro

| Ámbito | Situación en una frase | Severidad |
|--------|-------------------------|-----------|
| **Hosting cPanel** | WordPress productivo en addon `gano.digital`; coexistencia con **Drupal/Installatron** en ruta `/123/` **rota** (config ausente, updates 400, sin backups Installatron). | **Alta** (P0 Installatron + P1 SSL) |
| **SSL / HTTPS** | Dominio principal `gano.bio` con **Force HTTPS apagado** y alertas; `gano.digital` requiere revisión unificada con WP. | **Alta** |
| **Recursos** | Tope **512 MB RAM** en plan evidenciado — margen justo para WP + Elementor. | **Media** |
| **GitHub Actions** | Repo **PÚBLICO**; **minutos** de `ubuntu-latest` fueron tensión; **04 Deploy** migró a **self-hosted** en servidor. | **Crítica** si runner sigue en mismo host que producción |
| **Runner self-hosted** | Registrado como `gano-godaddy-server`, etiqueta `gano-production`, **online** — con repo **público** implica riesgo de **ejecución de código desde PRs/forks** en ese host. | **Crítica** hasta aislar o desregistrar |
| **SSH desde GitHub-hosted** | **04** falló con `Permission denied (publickey)` pese a **huella de clave coincidente** entre secret y local — hipótesis: `SERVER_*` o **firewall por IP** frente a runners de GitHub. | **Alta** (mitigado en parte por self-hosted + webhook) |
| **Webhook deploy** | `wp-content/gano-deploy/receive.php` — POST ZIP + HMAC; secreto solo en servidor; comentarios con ruta real fueron **redactados** en repo. | **Media** (endpoint conocido → endurecer en servidor) |
| **Dependabot / Node** | `.gsd/sdk`: **overrides** para `hono`, `@hono/node-server`, `@anthropic-ai/sdk`; `npm audit` 0 en ese árbol. | **Resuelto en HEAD** |
| **Historial Git** | Commit histórico de eliminación de scripts con credenciales — recomienda **TruffleHog/gitleaks** en historial completo tras público. | **Alta** (due diligence) |
| **Producto** | Fases 1–3 en **código** en repo; **Elementor / RCC / GSC** pendientes de panel; PFIDs `PENDING_RCC` en `functions.php`. | **Operativa** |

---

## Parte B — Hosting y panel (evidencias por capturas abril 2026)

### B.1 Arquitectura lógica constatada

| Rol | Dominio / ruta | CMS / herramienta |
|-----|----------------|-------------------|
| Dominio **principal** de la cuenta cPanel | `gano.bio` → `public_html` | Contenido del root (no es el foco del repo Pilot) |
| **Addon** comercial | `gano.digital` → `public_html/gano.digital` | **WordPress** (gano-child, Elementor, Woo, MU-plugins Gano) — **fuente de verdad del negocio** |
| App en Installatron | URL registrada `gano.digital/123/` | **Drupal 11.3.x** bajo gestión Installatron — **separada** del WP del subdirectorio anterior en la lógica de negocio |

**Problema de modelo mental:** el panel “Aplicaciones” muestra Drupal como “Mi sistema de gestión de contenido” mientras el repositorio y `TASKS.md` describen **WordPress**. Eso genera confusión operativa y riesgo de mantener una app **huérfana**.

### B.2 Catálogo de problemas evidenciables (IDs E-xx)

Los detalle completos y el plan por fases están en [`memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md). Aquí la matriz **problema → soluciones posibles**:

| ID | Problema | Soluciones / estrategias |
|----|----------|---------------------------|
| **E-01** | Installatron: `failed_application_config_doesnotexist` / `noconfigfile` | Ticket **GoDaddy** con texto del error; reparación Installatron a nivel servidor (`repair` / `recache` — solo admin); resincronizar instalación; si no aporta valor, **desinstalar** tras backup de carpeta. |
| **E-02** | Logs: “Unable to read source install configuration file” | Misma línea: restaurar metadata, permisos, o eliminar registro corrupto con soporte. |
| **E-03** | Updates Drupal: HTTP **400** hacia `gano.bio/.../123/update.php` vs URL lógica `gano.digital/123` | Corregir **URL base** en Installatron; alinear docroot y DNS; o dejar de usar esa instalación. |
| **E-04** | Sin backups Installatron | Configurar backup en Installatron **si** se mantiene la app; en paralelo backups **cPanel / plugin WP** para el sitio real. |
| **E-05** | `gano.bio`: Force HTTPS **off** | Activar Force HTTPS en cPanel tras validar certificado (AutoSSL). |
| **E-06** | Alerta SSL en dominio principal | **SSL/TLS Status** → cubrir SANs; renovar AutoSSL. |
| **E-07** | `gano.digital`: Force HTTPS ambiguo | Misma herramienta; revisar `.htaccess` del padre que no rompa addon; WP `siteurl`/`home` en https. |
| **E-08** | RAM 512 MB | Caché, menos plugins, PHP 8.x; upgrade de plan si 500 frecuentes. |
| **E-09** | Site Publisher advierte archivos existentes | **No** publicar plantillas que pisen `public_html` o `gano.digital`. |
| **E-10** | Metadata Installatron incoherente (fechas) | Baja prioridad; revisar tras reparar E-01. |
| **E-11** | Doble narrativa Drupal vs WordPress | **Decisión de negocio:** retirar Drupal `/123/` si es basura técnica. |
| **E-12** | Repo público + runner en host | Ver Parte C — **desregistrar runner** o VM aislada. |

### B.3 Estrategias intentadas (hosting)

- **Documentación** en `memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md` + actualización de **`.gano-skills/gano-cpanel-ssh-management/SKILL.md`** (§ híbrido WP + Installatron) y **`gano-wp-security`** (enlace al informe).
- **Redacción** de huella operativa en git (IP/usuario → placeholders en skills y memoria) para reducir filtración en repo público.
- **No** se ha ejecutado desde el agente: ticket GoDaddy, cambios SSL, ni desinstalación Installatron (requieren humano o soporte).

---

## Parte C — GitHub, Actions, visibilidad del repositorio

### C.1 Cronología de estrategias de deploy / CI (abril 2026)

| Estrategia | Qué se buscaba | Resultado / estado |
|------------|----------------|---------------------|
| **04 Deploy con SSH** desde **ubuntu-latest** + secret `SSH` | Rsync directo desde runners de GitHub al servidor | **Fallo persistente** `Permission denied (publickey)` en paso **Probar SSH** aun cuando la **huella SHA256** de la clave en CI coincide con la clave local `id_rsa_deploy` → hipótesis **SERVER_HOST/USER** o **bloqueo por IP** del hosting. |
| **Diagnóstico en workflow** | Pasos *Huella* + *BatchMode* + `RSYNC_RSH` | **PR #159** fusionado; aislar fallo SSH vs rsync. |
| **Documentación troubleshooting** | Runbook para equipos | `memory/ops/github-actions-ssh-secret-troubleshooting.md`, **PR #160** (docs IP/firewall), handoff [`2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](2026-04-08-reporte-handoff-ssh-deploy-tokens.md). |
| **Webhook HTTPS** (`receive.php`) + ZIP firmado | Evitar SSH desde IPs de GitHub bloqueadas por firewall | Implementado en repo (`wp-content/gano-deploy/receive.php`); secreto `GANO_DEPLOY_HOOK_SECRET` solo en `wp-config` del servidor; **PR #164** en historial remoto (según conversación de consolidación). |
| **Runner self-hosted** en GoDaddy | 04 sin minutos GitHub-hosted; sin SSH externo al mismo box | **deploy.yml** usa `runs-on: [self-hosted, gano-production]`; comentarios indican rsync **local** en servidor. |
| **Repo público** | Minutos Actions más favorables para jobs estándar | **Hecho** — `visibility: PUBLIC` verificado con `gh repo view`. |
| **Mitigación documental repo público** | Reducir datos operativos en markdown | Placeholders en skills, `receive.php` sin ejemplo de `/home/...` en comentario, scripts con `--path` genérico. |

### C.2 Problema crítico actual: público + self-hosted en producción

**Situación verificada (2026-04-10):** existía runner **online** `gano-godaddy-server` (id **21**), etiquetas `self-hosted`, `gano-production`, vinculado al repo **Pilot** público.

**Riesgo:** workflows en **pull requests desde forks** pueden, según configuración de Actions, ejecutar jobs en ese runner → **código arbitrario** en la máquina donde corre el runner (si coincide con el servidor de WordPress, es **compromiso de producción**).

**Soluciones posibles (elegir una):**

1. **Desregistrar** el runner en GitHub (`DELETE .../actions/runners/21`) y **parar** el servicio del runner en el host; luego **cambiar 04** a `ubuntu-latest` + **solo** webhook/SFTP o runner en **VM sin datos WP**.
2. **Mantener** runner solo en **máquina aislada** (sin acceso al docroot de `gano.digital`) + en GitHub: **requerir aprobación** para workflows de contribuidores externos / deshabilitar workflows en forks.
3. **Org-level** rules para restringir qué repos usan el runner (si aplica).

**Estrategia intentada en repo:** documentación en `sota-investigacion-2026-04-09-ci-supply-chain-agents.md` §5–6, `activeContext`, `deferredItems` #8, `TASKS.md`. **No** se asume que el runner ya fue eliminado sin nueva verificación `gh api .../runners`.

### C.3 Otros workflows (ubuntu-latest)

Siguen consumiendo minutos cuando corren: `php-lint-gano`, `secret-scan` (TruffleHog), `labeler`, `verify-patches`, `gano-ops-hub`, oleadas Copilot, etc. Repo público alivia política de minutos para estándar; verificar en facturación/org.

---

## Parte D — Seguridad del código y del repositorio

### D.1 Auditoría estática (sin historial)

- **No** se encontraron bloques PEM en barridos dirigidos del árbol.
- **Freemius** en plugin de terceros: cadenas `sk_*` en **mocks** del SDK, no secretos de producción.
- **`ssh_cli.py`:** credenciales solo por variables de entorno.

### D.2 Riesgo historial + repo público

- Commit de **limpieza** de scripts con credenciales → **TruffleHog / gitleaks** sobre **todo el historial** recomendado; **rotar** cualquier secreto que haya existido en commits antiguos.

### D.3 Dependabot (`.gsd/sdk`)

- **Estrategia aplicada:** `overrides` en `package.json` para `hono`, `@hono/node-server`, `@anthropic-ai/sdk`; regeneración de lockfile; **PR #162** en historial.
- **Estado:** `npm audit` 0 en `.gsd/sdk` y `.gsd` en verificación local (abr 2026).

### D.4 Webhook `receive.php`

- **Riesgo:** URL pública conocida; abuso por volumen o fuerza bruta débil sobre HMAC.
- **Mitigaciones:** secreto largo; rate limit / allowlist IP en servidor; monitoreo de logs.

---

## Parte E — Producto WordPress y repositorio Pilot

### E.1 Alineación repo ↔ producción

- **Parches Fases 1–3** (MU-plugins, `gano-child`, rate limit, CSP, SEO, CWV) están en **git**; **TASKS.md** sigue marcando sincronización con servidor como acción manual crítica si no hay deploy automático fiable.
- **`wp-file-manager`:** eliminar en producción (workflow **12** o manual) — riesgo de seguridad.

### E.2 Contenido y comercio

- **Homepage:** copy en `memory/content/homepage-copy-2026-04.md`; checklist Elementor en `memory/ops/homepage-vitrina-launch-plan-2026-04.md` § Fase 1.
- **Fase 4 Reseller:** `GANO_PFID_*` en `functions.php` como `PENDING_RCC` hasta mapeo real; `memory/commerce/rcc-pfid-checklist.md`.

### E.3 PDF / reportes locales

- Scripts `generate_claude_audit_report_pdf.py`, `generate_project_status_pdf.py`; fix `_courier_safe` para logs git con Unicode en Courier.

---

## Parte F — Matriz “problema → estrategia intentada → resultado”

| Problema | Estrategia intentada | Resultado |
|----------|----------------------|-----------|
| SSH desde GitHub al servidor | Secrets + `deploy.yml` + diagnóstico huella | Sigue **publickey** desde runners hosted |
| Minutos Actions agotados | Self-hosted + considerar repo público | Público **sí**; self-hosted **introduce riesgo nuevo** si en prod |
| Firewall bloquea IPs GitHub | Webhook ZIP + HMAC | Camino documentado en código |
| Dependabot en SDK | npm overrides | **Resuelto** en HEAD |
| Huella operativa en git | Redacción markdown + comentarios PHP | **Mitigado** en archivos tocados |
| Installatron roto | Solo documentación + skill | **Pendiente** ticket GoDaddy / acción humana |
| SSL en dominios | — | **Pendiente** cPanel |
| Runner + repo público | Documentación + deferredItems | **Pendiente** desregistrar o aislar |

---

## Parte G — Checklist recomendada para la siguiente sesión Claude

**P0 — Seguridad**

- [ ] Verificar con `gh api` si el runner self-hosted sigue **online** en `Gano-digital/Pilot`; si sí y el repo es público, **proponer PR** que cambie `deploy.yml` a `ubuntu-latest` **o** documentar VM aislada y settings de fork approval (sin ejecutar DELETE sin orden explícita de Diego).
- [ ] Recordar **TruffleHog** en historial si no se ha corrido post-público.

**P1 — Hosting**

- [ ] Resumir para Diego: ticket GoDaddy Installatron (textos E-01–E-03) + SSL Force HTTPS.

**P2 — Producto**

- [ ] No inventar PFIDs; enlazar `rcc-pfid-checklist.md`.

**P3 — Repo**

- [ ] Mantener convención `gano_` en PHP nuevo; no tocar `wp-config` en git.

---

## Parte H — Índice de archivos clave (rutas relativas al repo)

| Ruta | Contenido |
|------|-----------|
| `TASKS.md` | Prioridades operativas |
| `CLAUDE.md` | Contexto proyecto |
| `.github/DEV-COORDINATION.md` | Git ↔ servidor |
| `.github/workflows/deploy.yml` | 04 Deploy (self-hosted en HEAD local clon) |
| `.github/workflows/verify-patches.yml` | 05 comparación MD5 |
| `wp-content/gano-deploy/receive.php` | Webhook deploy |
| `memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md` | Informe hosting detallado |
| `memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md` | SOTA CI + público + runners |
| `memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md` | SSH/huella/CI |
| `memory/ops/github-actions-ssh-secret-troubleshooting.md` | Runbook SSH |
| `memory/ops/homepage-vitrina-launch-plan-2026-04.md` | Plan vitrina |
| `.cursor/memory/activeContext.md` | Foco Cursor actual |
| `.cursor/memory/deferredItems.md` | Cola diferida (#8 runner, #9 cPanel…) |
| `.gano-skills/gano-cpanel-ssh-management/SKILL.md` | Ops cPanel + híbrido Installatron |
| `.gano-skills/gano-github-copilot-orchestration/SKILL.md` | Actions + SOTA |

---

## Parte I — Nota sobre tokens / API Anthropic

El handoff **2026-04-08** menciona pausa de **tokens** para Claude en la nube. Este reporte es **autocontenido en texto**: puede pegarse en Claude Projects o adjuntarse como archivo sin requerir API para entender el estado.

---

_Fin del reporte. Actualizar tras: cierre ticket GoDaddy Installatron, cambio en runners, o deploy verificado en producción._
