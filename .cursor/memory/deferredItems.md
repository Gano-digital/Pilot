# Deferred Items — Bugs y Issues Fuera de Scope

*Items descubiertos durante trabajo que no son del scope actual.*
*Cada item debe convertirse en issue de GitHub o resolverse en sesion futura.*

## Pendientes

| # | Fecha | Encontrado en | Descripcion | Severidad |
|---|-------|--------------|-------------|-----------|
| 1 | 2026-04-02 | Auditoria seguridad | GitHub PAT expuesto — rotar urgente | CRITICA |
| 2 | 2026-04-02 | Auditoria seguridad | GitLab token expuesto — rotar | ALTA |
| 3 | 2026-04-02 | Auditoria seguridad | GoDaddy API keys expuestas — rotar | ALTA |
| 4 | 2026-04-02 | Staging setup | SSH key no generada para GoDaddy | MEDIA |
| 5 | 2026-04-02 | Header fix | fix_header.py en C:/tmp pendiente | BAJA |
| 6 | 2026-04-08 | Deploy CI 04 | Huella SSH coincide con local; sigue publickey — revisar SERVER_* y firewall/IP hosting vs runners GitHub; PR #160 docs; handoff [`memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](../../memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md) | ALTA |
| 7 | 2026-04-09 | SOTA / supply chain | Opcional: análisis estático de workflows (`zizmor` u homólogo) antes de merge masivo YAML; inventario plugins WP en prod como tabla en `memory/ops/` | BAJA |
| 8 | 2026-04-10 | Repo público + Actions | ~~Runner self-hosted en `Pilot`~~ — **verificado 2026-04-10:** API `repos/.../actions/runners` → **0 runners** en el repo; `deploy.yml` usa **ubuntu-latest** + webhook HTTPS. **Pendiente solo con permisos org:** confirmar que no queden runners a nivel **organización** (`admin:org`); `test-runner.yml` corre en **`ubuntu-latest`** (smoke manual). | MITIGADO (repo) |
| 9 | 2026-04-10 | cPanel / Installatron | Errores `noconfigfile` + logs “Unable to read source install configuration file” + updates 400 — ticket GoDaddy o reparación Installatron; decidir si Drupal `/123/` se elimina; SSL Force HTTPS en dominios activos — ver [`investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../../memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md) | ALTA |
| 10 | 2026-04-19 | CSP / Reseller UI | Reportes `CSP_VIOLATION` hacia recursos `gui.secureserver.net` (flujos GoDaddy) — revisar directivas en `gano-security.php` o documentar como ruido aceptable si no rompe UX | MEDIA |

## Resueltos
*Mover items aqui cuando se completen.*

| # | Fecha | Resolución |
|---|-------|-----------|
| 8 | 2026-04-10 | **Verificado 2026-04-11:** `gh api repos/Gano-digital/Pilot/actions/runners` → total_count: 0. **Runner ya eliminado.** `deploy.yml` usa `ubuntu-latest` + webhook HTTPS. **Cerrado.** |
