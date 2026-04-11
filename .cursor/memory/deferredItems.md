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
| 8 | 2026-04-10 | Repo público + Actions | **CRÍTICO:** Runner self-hosted `gano-godaddy-server` (id 21) online en `Pilot` público — desregistrar en GitHub + parar servicio en host, o runner aislado; revisar Settings → Actions → fork workflows | CRITICA |
| 9 | 2026-04-11 | `.gsd/sdk` build local | `npm run build` falla en `src/event-stream.ts:313` por TS2352 (cast `BetaContentBlock[]`), ajeno a la skill cloud starter; si se toca `.gsd/sdk`, resolver antes de volver a exigir build verde en ese paquete | MEDIA |
| 10 | 2026-04-11 | `.gsd` test local | `npm test` falla con 21 tests en `security-scan` (`prompt-injection-scan.sh`, `base64-scan.sh`, `secret-scan.sh`), ajeno a la skill cloud starter; si se toca `.gsd`, revisar esos scripts/tests antes de exigir suite verde | MEDIA |

## Resueltos
*Mover items aqui cuando se completen.*
