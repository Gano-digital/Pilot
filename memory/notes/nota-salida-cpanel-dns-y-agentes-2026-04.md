# Nota de salida — cPanel / dominio + agentes GitHub (abril 2026)

**Para:** Diego — al volver.  
**Contexto:** pediste avanzar con el problema de **dominio en cPanel**, reanudar **agentes** y dejar claro qué es **humano** vs **automatizable**.

---

## 1. Lo que solo tú puedes hacer (panel / servidor)

No está en el repo ni lo puede cerrar GitHub Actions:

| Acción | Por qué |
|--------|---------|
| **Diagnosticar en cPanel** | Zone Editor, dominio añadido / document root, SSL/TLS o AutoSSL, errores concretos del navegador. |
| **Anotar datos del incidente** | Dominio exacto, síntoma (SSL, DNS, 500, otro sitio), si falla solo dentro del panel o también fuera. |
| **Ajustar DNS en registrador** | Si el DNS no está en el mismo cPanel, hay que alinear A/CNAME con la IP correcta. |
| **Secrets en GitHub** (`SSH`, etc.) | Para **04 · Deploy**, **05**, **12** — ver `.github/DEV-COORDINATION.md`. |

**Referencias en repo:** [`memory/ops/dns-https-godaddy-runbook-2026.md`](../ops/dns-https-godaddy-runbook-2026.md) (síntomas y orden de comprobación; el panel puede ser GoDaddy o cPanel, la lógica DNS/HTTPS es la misma), [`memory/ops/url-canonical-gano-digital-2026.md`](../ops/url-canonical-gano-digital-2026.md), [`scripts/check_dns_https_gano.py`](../../scripts/check_dns_https_gano.py) solo para **`gano.digital`** / **`www`**.

---

## 2. Lo que ya quedó hecho en el repo (última sesión)

- Playbook agentes: [`memory/ops/agent-playbook-asistentes-2026-04.md`](../ops/agent-playbook-asistentes-2026-04.md).
- Workflows **08** y **10** con deduplicación **sin** API Search (menos errores al sembrar).
- `TASKS.md` § Infra DNS: documentos marcados como presentes; pendiente **ejecutar** script y **cambios en panel**.
- Guía cierre issues: [`memory/claude/gh-issue-close-guide.md`](../claude/gh-issue-close-guide.md).

**Desde aquí no tengo acceso a tu cPanel ni a GitHub en vivo** — no puedo “tomar control” del servidor; solo mantener el mapa en git.

---

## 3. Agentes en GitHub — qué pasó y cómo reanudar

### Qué pasó (estado conocido desde documentación, no desde API)

- **PRs Copilot** de la oleada grande se **fusionaron en `main`** (~abril 2026). Muchos issues antiguos pueden estar **obsoletos** aunque sigan abiertos.
- **Workflow 10 · Orquestar oleadas** ya **no** es el paso obligatorio para esa oleada.
- Los agentes **no** arreglan DNS/cPanel: **crean issues** y (si asignás Copilot) **PRs en el repo**.

### Qué revisar al volver (5 min)

1. **github.com** → repo **Gano-digital/Pilot** → **Issues** → filtrar por `is:open` y etiquetas `copilot`, `homepage-fixplan`, `infra`, `coordination` según corresponda.
2. Por cada issue: ¿el trabajo ya está en `main`? → cerrar con plantilla en [`gh-issue-close-guide.md`](../claude/gh-issue-close-guide.md).
3. **Pull requests** abiertos: revisar CI (01, 02) y fusionar o cerrar.

### Desplegar trabajo nuevo vía workflow (sembrar issues)

**Actions** → **08 · Agentes · Sembrar cola Copilot** → **Run workflow**:

| Si necesitás… | `queue_file` | `scope` típico |
|---------------|--------------|----------------|
| Documentación / runbooks DNS (no cambia tu panel) | `tasks-infra-dns-ssl.json` | `infra` o `all` |
| Research APIs ML + GoDaddy | `tasks-api-integrations-research.json` | `docs` o `all` |
| Higiene seguridad / .gitignore / docs sin secretos | `tasks-security-guardian.json` | `security` o `all` |
| Narrativa / contenido oleada 4 | `tasks-wave4-ia-content.json` | `all` o `content_seo` / `docs` |

Después: en cada issue nuevo → **asignar GitHub Copilot** y pegar el bloque correcto de [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md).

**Validación local** antes de editar JSON: `python scripts/validate_agent_queue.py` → debe decir `OK`.

**No** re-ejecutar **08** en bucle: si el issue **abierto** ya tiene el mismo `<!-- agent-task-id:... -->`, el workflow **omite** crear duplicados.

---

## 4. Datos adicionales que me (o a un agente) conviene que traigas

Para bajar el problema de **cPanel** a causa concreta:

1. Dominio exacto (y si es principal, addon o subdominio).
2. Mensaje de error del navegador o código (p. ej. `ERR_SSL`, `DNS_PROBE`).
3. Si `gano.digital` / `www` responden desde otra red (datos móvil).
4. Sin pegar en issues públicos: IPs completas, usuario cPanel, contraseñas (ver [`memory/ops/security-guardian-playbook-2026.md`](../ops/security-guardian-playbook-2026.md)).

---

## 5. Orden sugerido el día que vuelvas

1. CPanel / dominio: síntoma + zona DNS + SSL (tú).  
2. `python scripts/check_dns_https_gano.py` si el caso es **gano.digital**.  
3. GitHub: limpiar issues obsoletos; abrir **08** solo si necesitás **nuevos** issues de cola.  
4. Deploy / parches: **04** / **05** cuando secrets y prioridad lo permitan.

---

*Generado para continuidad mientras estás fuera; actualizar este archivo o `TASKS.md` cuando cierres el incidente.*
