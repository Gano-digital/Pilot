# Trazabilidad — ola ops producción + convergencia repo (2026-04-19)

Documento **índice** para humanos y agentes: qué quedó hecho en servidor y repo, qué sigue pendiente, y cómo están alineadas las herramientas de trabajo.

## Fuente de verdad (orden de lectura)

| Prioridad | Artefacto | Rol |
|-----------|-----------|-----|
| 1 | [`TASKS.md`](../../TASKS.md) | Lista maestra hecho/pendiente operativo |
| 2 | [`.cursor/memory/activeContext.md`](../../.cursor/memory/activeContext.md) | Foco actual y bloqueadores |
| 3 | [`.cursor/memory/progress.md`](../../.cursor/memory/progress.md) | Línea temporal por fecha |
| 4 | [`.cursor/memory/techContext.md`](../../.cursor/memory/techContext.md) | Decisiones de stack y políticas |
| 5 | Este archivo + [`2026-04-19-auditoria-ssh-inventario-sota.md`](2026-04-19-auditoria-ssh-inventario-sota.md) | Detalle de auditoría SSH, backups y MD5 |
| 6 | GitHub **Issue [#263](https://github.com/Gano-digital/Pilot/issues/263)** | Tablero operativo P0–P1 (comentarios con estado de olas) |
| 7 | **`memory/claude/README.md`** + **`dispatch-status-2026-04-19.md`** | Handoff Claude: orden de lectura y prioridades P0–P2 post-ola |

Issues de apoyo (oleada documentada en conversación): [#264](https://github.com/Gano-digital/Pilot/issues/264) RCC/PFID, [#265](https://github.com/Gano-digital/Pilot/issues/265) IA/slugs, [#266](https://github.com/Gano-digital/Pilot/issues/266) SEO/bots, [#267](https://github.com/Gano-digital/Pilot/issues/267) rotación tokens.

## Hecho (producción `gano.digital`)

- **Auditoría SSH:** inventario WP, plugins, menús, opciones Reseller, drift inicial documentado en `2026-04-19-auditoria-ssh-inventario-sota.md`.
- **Backups en servidor:** DB y `.htaccess` bajo `~/backups/sota-audit-2026-04-19/`; copias pre-convergencia de archivos bajo `~/backups/repo-converge-20260419-125954/`.
- **Home canónica:** menú principal con ítem custom **Inicio → `https://gano.digital/`**; página legacy `/home/` en borrador; duplicado `dominios-2` en papelera.
- **Opciones `gano_pfid_*`:** pobladas en `wp_options` desde slugs del catálogo Reseller (`rstore_id`); `gano_pfid_online_storage` permanece `PENDING_RCC` hasta mapeo claro.
- **Política bots (`.htaccess`):** bloqueo solo de escáneres abusivos (`sqlmap|nikto|acunetix|masscan|dirbuster|wpscan|zgrab|nmap|httrack`); crawlers de descubrimiento / UAs tipo `GPTBot` y `curl` default verificados en `200` para `/`.
- **Archivos raíz para crawlers:** `https://gano.digital/llms.txt`, `https://gano.digital/bot-seo-context.md` (contenido con CTA comercial).
- **Limpieza plugins:** 13 plugins inactivos eliminados del disco (Elementor stack, WooCommerce, fases legacy, wompi inactivo, etc.). Quedan activos solo el stack Reseller + fases 6/7; `seo-by-rank-math` y `wordfence` **instalados, inactivos**.
- **Convergencia código repo → servidor:** despliegue por **SCP** de 8 archivos críticos; **MD5 idéntico** repo vs producción; `wp cache flush` + `wp rewrite flush`.
- **Repo:** commit `780cedff` (sesión auditoría) y estado posterior en `main` según `git log`.

## Pendiente (priorizado)

| Área | Qué falta | Notas |
|------|-----------|--------|
| RCC / catálogo | Validar en RCC que los IDs usados en `gano_pfid_*` coinciden con **PFID numérico** esperado; completar `gano_pfid_online_storage` | Hoy varios valores son **slugs** (`wordpress-basic`, etc.) coherentes con `rstore_id`; smoke visual en `/ecosistemas/` OK |
| Contenido | Lorem / placeholders en Elementor; copy final homepage según `homepage-copy-2026-04.md` | Sigue siendo trabajo **wp-admin** |
| SEO formal | Rank Math setup + GSC + panel Gano SEO (datos empresa) | Acordado dejar Rank Math hasta cerrar copy |
| Seguridad | Activar Wordfence + 2FA cuando se defina ventana | Plugin presente, inactivo |
| Tokens | Rotación PAT/remotes — Issue [#267](https://github.com/Gano-digital/Pilot/issues/267) | Tabla en `deferredItems.md` |
| CSP / terceros | Violaciones reportadas hacia `gui.secureserver.net` | Ajuste fino en `gano-security.php` o allowlist cuando se priorice |
| Archivos raíz en git | Versionar `llms.txt` y `bot-seo-context.md` en repo | El árbol `Pilot` no incluye docroot WP completo; decidir carpeta (p. ej. `memory/ops/public-root/`) |
| CI vs SSH manual | Preferir **webhook 04** + **05 verify** cuando el drift sea rutinario; usar SCP solo para parches urgentes | Documentado en `techContext` |

## Alineación de herramientas (agentes + Diego)

- **Shell local:** Windows **PowerShell** — evitar `$(date ...)` sin escapar (PowerShell lo interpreta); para comandos SSH complejos usar **`ssh --% host comando`** (stop-parsing) o comillas simples en el argumento remoto.
- **GitHub CLI:** `gh issue comment` puede **no persistir** si el comando corre en entorno sandbox; usar ejecución **sin sandbox** (`all`) cuando haga falta confirmar el comentario en GitHub.
- **Deploy:** canonical **código** = `main` en `Gano-digital/Pilot`; producción = `DEPLOY_PATH` en [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md). Parche puntual: **SCP** con backup previo en servidor.
- **Memoria Cursor:** tras bloques grandes, actualizar `activeContext.md`, `progress.md`, `techContext.md` (protocolo [`.cursor/rules/006-memory-protocol.mdc`](../../.cursor/rules/006-memory-protocol.mdc)).

## Comandos útiles (no pegar secretos)

```bash
# Comparar MD5 local vs remoto (ejemplo un archivo)
md5sum wp-content/themes/gano-child/functions.php
ssh gano-godaddy md5sum /home/USER/public_html/gano.digital/wp-content/themes/gano-child/functions.php
```

Sustituir `USER` y ruta por los valores reales del hosting (no publicar en issues).
