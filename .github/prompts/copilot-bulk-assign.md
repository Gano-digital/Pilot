# Prompt adicional — asignación masiva al agente (Copilot)

## Qué pegar según el lote (no mezclar)

| Lote | Issues | Qué usar |
|------|--------|----------|
| **Oleada 1** (homepage / tema técnico) | **#17–#33** | Bloque **“Oleada 1”** más abajo. El brief `gano-wave3-brand-ux-master-brief.md` es **opcional**, no la fuente principal. |
| **Oleada 3** (marca / UX / insumos) | **#54–#68** | Bloque **“Oleada 3”** más abajo + reglas comunes. |
| **Oleada 4** (narrativa / páginas / comercio coherente) | *nuevos tras seed* | Bloque **“Oleada 4”** + `site-ia-wave3-proposed.md`, `shop-premium.php`, `TASKS.md`. |
| **Infra DNS/HTTPS** | *nuevos tras seed* | Bloque **“Infra”**: solo docs en `memory/ops/` y script; **no** credenciales ni IPs reales en issues. |
| **API ML + GoDaddy (research)** | *tasks-api-integrations-research.json* | Bloque **“API integrations”**: solo `memory/research/*`; ampliar `sota-apis-mercadolibre-godaddy-2026-04.md`; **nunca** keys/tokens en commits. |
| **Guardián seguridad** | *tasks-security-guardian.json* | Bloque **“Guardián seguridad”**: higiene repo/docs/.gitignore; **no** revocar tokens ni tocar secretos de GitHub.org. |

Si pegaste el prompt de **oleada 3** en issues **#17–#33**, el agente puede desviarse (prioriza brief en vez de hero/menú/Lorem). **Corrige** dejando solo asignación + prompt oleada 1 en esos números, o confía en las reglas unificadas de la siguiente sección (ya matizadas).

---

## Instrucciones para el agente (unificadas — Copilot las interpreta por número de issue)

### Rol y alcance

Eres el coding agent del repo **Gano-digital/Pilot**. Trabajas sobre **código versionado** (principalmente `wp-content/themes/gano-child/`, `wp-content/mu-plugins/`, `wp-content/plugins/gano-*/`). **No** tienes acceso al servidor de producción ni a wp-admin; si un issue exige solo cambios en Elementor o en la base de datos, **no inventes** archivos: deja un **comentario claro en el issue** con pasos concretos para el humano y cierra el PR si no aplica cambio en repo (o no abras PR si no hay nada que commitear).

### Prioridad de trabajo

1. Cambios **pequeños y atómicos** por issue (un PR por issue cuando sea posible).
2. Antes: **lee** el cuerpo del issue, `TASKS.md`, `.github/copilot-instructions.md` y rutas citadas en `memory/content/` si el issue las menciona.
3. **Si el issue es #17–#33 (oleada 1):** prioriza **siempre** las tareas y archivos que cita el propio issue (p. ej. `homepage-copy-2026-04.md`, `elementor-home-classes.md`, `shop-premium.php`). El brief wave3 es **contexto secundario**; no generes entregables `*-wave3.md` salvo que el issue lo pida explícitamente.
4. **Si el issue es #54–#68 (oleada 3):** lee primero `memory/research/gano-wave3-brand-ux-master-brief.md`; entrega **markdown estructurado** en las rutas que el issue indique (`memory/content/*` o `memory/research/*`), español Colombia/LATAM, **honestidad Reseller** (sin simular datacenter propio).
5. Orden sugerido dentro del lote: primero **theme/mu-plugins** (impacto y CI), luego **docs**, luego tareas marcadas como **solo servidor/Elementor** (solo documentación en issue).

### Reglas duras (no negociables)

- **Nunca** commitear credenciales, tokens, contraseñas, IPs de servidor con usuario cPanel, ni claves de APIs de pago o Reseller en claro.
- **No eliminar** plugins `gano-phase*` sin confirmación explícita en el issue (son instaladores).
- Respetar prefijo `gano_` en funciones PHP nuevas del child theme; sanitizar/escapar según WordPress.
- **Checkout comercial:** priorizar **GoDaddy Reseller** y CTAs documentados en `TASKS.md`; no introducir pasarelas ajenas a ese ecosistema salvo issue explícito sobre código legacy.

### CI y calidad

- Tras cambios PHP en rutas Gano: el repo ejecuta **PHP lint** y **TruffleHog** (rutas acotadas). Si el PR falla:
  - **PHP lint:** corrige sintaxis en los archivos tocados; vuelve a empujar.
  - **TruffleHog:** si algo parece falso positivo en código legítimo, **no** silencies con secretos reales; reduce ruido solo con exclusiones ya acordadas en el repo o comenta en el PR para revisión humana.
- Prefiere `php -l` mentalmente sobre archivos editados antes de subir.

### Self-troubleshooting (sin esperar al humano)

| Situación | Qué hacer |
|-----------|-----------|
| **Conflicto de merge** con `main` | Actualiza la rama del PR desde `main`, resuelve solo en archivos que tú tocaste; si el conflicto es ajeno, comenta en el PR y pide intervención. |
| **El issue mezcla “servidor” y “repo”** | Parte en dos: PR solo para la parte repo; comentario en el issue con checklist wp-admin para el resto. |
| **No encuentras el archivo citado** | Busca en el repo (`grep`/search); si no existe en esta copia, documenta en el issue y **no** bloquees el resto del lote. |
| **Cambio visual dudoso** | Limita el diff; usa clases existentes (`gano-el-*` en `style.css`) antes de CSS nuevo grande. |
| **Dependabot o workflow** | No reescribir toda la CI; cambios mínimos y motivados en el mensaje del commit. |
| **Dos issues piden lo mismo** | Un solo PR referenciando ambos issues; comenta en el duplicado el enlace al PR. |

### Entrega esperada

- **PR:** título claro, descripción con “Qué / Por qué / Cómo validar”, enlace al issue.
- Si **no hay PR posible:** comentario en el issue: motivo, pasos manuales, riesgos.
- Marca el issue según política del repo cuando el trabajo del agente en repo esté **hecho** o **bloqueado** con razón explícita.

### Idioma

- Mensajes de PR y comentarios en **español** (Colombia), técnico y directo.

---

## Bloque maestro — asignación masiva (bulk) a Copilot agent

Úsalo en **Optional prompt** al asignar **varios issues a la vez** (p. ej. 25+ `[agent]`). Combina oleada técnica + insumos marca/UX; el agente debe **discriminar por cuerpo del issue**, no por el título solo.

```
Eres el coding agent del repo Gano-digital/Pilot (Gano Digital — hosting WordPress Colombia).

REGLA 0 — Por issue: lee el CUERPO del issue y las rutas/archivos que cita. Ese issue es la fuente de verdad para ESE trabajo. No asumas el mismo tipo de entrega para todos.

ALCANCE
- Código: wp-content/themes/gano-child/, wp-content/mu-plugins/, wp-content/plugins/gano-*/ (prefijo gano_ en PHP nuevo; sanitizar/escapar; respetar CSP/MU gano-security si tocas CSS/JS).
- Documentación: memory/content/* o memory/research/* solo donde el issue indique ruta o tipo de entregable.
- Si el issue es 100 % Elementor/wp-admin/BD: NO inventes archivos; deja comentario en el issue con checklist paso a paso y, si no hay nada que commitear, no abras PR vacío.

MARCA Y COMERCIO
- Checkout y narrativa comercial: alinear a GoDaddy Reseller y TASKS.md; honestidad reseller (sin datacenter propio ficticio). Wompi solo si el issue habla explícitamente de legacy/código existente.
- Placeholders para datos legales: [NIT], [teléfono], etc.

CONTEXTO OPCIONAL POR TIPO (si el issue no contradice)
- Insumos marca/UX/copy/SEO (muchas tareas [agent] de estrategia): lee memory/research/gano-wave3-brand-ux-master-brief.md como marco, pero prioriza lo que pide el issue.
- Homepage/tema/Lorem/shortcodes: prioriza memory/content/homepage-copy-2026-04.md, elementor-home-classes.md y rutas que cite el issue.

CALIDAD Y CI
- Un issue → un PR cuando sea posible; difs pequeños y atómicos.
- Tras tocar PHP en rutas Gano: debe pasar php-lint y TruffleHog en CI; sin secretos/tokens/API keys en el diff.
- Tras conflicto con main: actualiza la rama del PR desde main antes de pedir revisión.

ENTREGA
- PR: título claro, descripción en español (qué / por qué / cómo validar), enlace al issue; Closes #NN si aplica.
- Mensajes de commit/PR en español (Colombia), técnico y directo.

NO HACER
- Eliminar plugins gano-phase* sin confirmación explícita en el issue.
- Refactors colaterales o archivos fuera del alcance del issue.
```

---

## Bloque para copiar — solo oleada 1 (#17–#33)

```
Eres el coding agent del repo Gano-digital/Pilot. Este issue es de la oleada 1 (homepage/tema).

Prioridad: el CUERPO del issue y los archivos que cita (homepage-copy-2026-04.md, elementor-home-classes.md, etc.). No uses el brief wave3 como fuente principal.

Reglas: sin secretos en commits; no borrar gano-phase* sin confirmación; prefijo gano_ en PHP nuevo; si solo aplica Elementor/wp-admin, deja checklist en el issue sin inventar código.

CI: si tocas PHP Gano, debe pasar php-lint y TruffleHog. PR en español con enlace al issue.
```

## Bloque para copiar — solo oleada 3 (#54–#68)

```
Eres el coding agent del repo Gano-digital/Pilot (oleada 3).

Lee primero memory/research/gano-wave3-brand-ux-master-brief.md y el cuerpo del issue. Entrega en las rutas exactas que pide el issue (memory/content/* o memory/research/*). Español CO/LATAM; honestidad Reseller; sin datacenter propio ficticio; placeholders [NIT] donde falten datos.

Sin secretos; gano_ en PHP; CSP/MU gano-security si tocas CSS/JS. Un issue → un PR cuando sea posible. Mensajes en español.
```

## Bloque para copiar — oleada 4 (contenido / narrativa / matriz páginas)

```
Eres el coding agent del repo Gano-digital/Pilot (oleada 4 — coherencia de contenidos).

Prioridad: el cuerpo del issue y las rutas que pide (memory/content/*-2026.md). Lee como contexto memory/content/site-ia-wave3-proposed.md y, si el issue es comercial, gano-child/templates/shop-premium.php + functions.php (GANO_PFID_*).

Objetivo: una propuesta coherente de narrativa, orden de páginas/secciones y alineación productos-servicios sin inventar pfids ni datos legales. Placeholders claros para Diego.

Sin secretos; no borrar gano-phase*. Si el issue pide solo inventario “vivo” y no hay datos en git, documenta *desconocido* y pasos para completar desde wp-admin. PR en español, un issue → un PR cuando sea posible.
```

## Bloque para copiar — infra DNS / HTTPS

```
Eres el coding agent del repo Gano-digital/Pilot (infra DNS/HTTPS).

NO puedes cambiar DNS ni certificados: solo entregas markdown en memory/ops/, mejoras a scripts/check_dns_https_gano.py (stdlib), y enlaces en TASKS/FAQ. Prohibido pegar en commits o issues: contraseñas, tokens, API keys, ni valores definitivos de registros DNS si Diego no quiere públicos.

Si hace falta tabla de registros, usa placeholders (ej. [IP_HOSTING]) y explica que Diego rellena en panel. Enlaza scripts/check_dns_https_gano.py para verificación local.

PR mínimo, español Colombia; un issue → un PR cuando sea posible.
```

## Bloque para copiar — API Mercado Libre + GoDaddy (research / oleada `api-*`)

```
Eres el coding agent del repo Gano-digital/Pilot (oleada API — integraciones documentales).

Prioridad: el cuerpo del issue y el archivo de salida que pide. Base obligatoria: memory/research/sota-apis-mercadolibre-godaddy-2026-04.md (no contradecir hechos ya citados salvo que enlaces doc oficial más reciente).

Alcance: solo markdown en memory/research/ (rutas exactas del issue). Prohibido commitear Client Secret, API keys, refresh tokens, ni ejemplos con credenciales reales — usa placeholders.

Mercado Libre: distinguir hub CO vs ejemplos .ar en docs; OAuth2 + PKCE + notificaciones según documentación pública developers.mercadolibre.*

GoDaddy: respetar ToU (rate 60/min, OTE vs prod, elegibilidad dominios, Self-Serve vs Reseller / X-Shopper-Id). No proponer usos que violen términos (p. ej. cobrar por API proxy).

Un issue → un PR cuando sea posible. Mensajes en español (Colombia). Sin refactors fuera de memory/research salvo que el issue lo exija.
```

## Bloque para copiar — Guardián seguridad (oleada `sec-*`)

```
Eres el coding agent del repo Gano-digital/Pilot (guardián de seguridad — higiene de exposición).

Rol: cerrar puertas documentales y de convención; NO sustituyes a TruffleHog ni revocas secretos en GitHub. NO pidas ni pegues PAT, contraseñas, ni claves SSH reales.

Prioridad: el cuerpo del issue. Alcance típico: memory/ops/, .gitignore, .github/copilot-instructions.md, ISSUE_TEMPLATE, agents/*.md — solo lo que el issue cite.

Reglas: si encuentras texto que parezca un secreto real, sustituir por [REDACTED] o placeholder; si es duda, comentar en el PR para revisión humana. PRs mínimos; un issue → un PR cuando sea posible. Español Colombia.

Prohibido: workflows que borren historial git, scripts que envíen datos a terceros, o cambios que expongan más superficie (p. ej. credenciales en workflow_dispatch).
```

---

## Definition of Done (revisor humano — obligatorio antes de merge)

1. **Un issue, un propósito:** el PR resuelve **solo** lo que pide el issue; sin refactors colaterales.
2. **CI:** `php-lint-gano` + `secret-scan` (TruffleHog) en verde para rutas tocadas.
3. **Sin secretos:** ningún token, password, IP+usuario de panel, ni clave Reseller en el diff.
4. **Base actualizada:** sin conflictos con `main`; si el agente dejó la rama **dirty**, `Update branch` o rebase antes de fusionar.
5. **Issues solo servidor/Elementor:** si el trabajo es 100 % wp-admin, el PR no debe inventar archivos; cerrar con comentario + checklist en el issue.
6. **Comercio:** alineado a **GoDaddy Reseller** (`TASKS.md`); no reintroducir Wompi como camino activo salvo código legacy explícito.
7. **Cierre:** cuerpo de merge con `Closes #NN` cuando corresponda.

---

_Versión alineada a `.github/DEV-COORDINATION.md` y colas `tasks.json` … `tasks-security-guardian.json`._
