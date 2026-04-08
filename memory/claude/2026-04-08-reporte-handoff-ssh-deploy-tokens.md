# Reporte de handoff para Claude — SSH, Deploy CI, tokens y estado repo

**Fecha:** 2026-04-08  
**Audiencia:** Claude (Cursor, Claude Code, o sesión con repo adjunto)  
**Contexto:** Diego indicará que **reparará cuando vuelva a tener tokens** (Anthropic / APIs / u otros). Este documento concentra **estado verificado**, **evidencia** y **siguientes pasos** sin depender del historial del chat.

---

## 1. Resumen ejecutivo

| Tema | Estado |
|------|--------|
| **Secret `SSH` en GitHub** | Es la **misma clave** que `~/.ssh/id_rsa_deploy` local: huella **SHA256 `dyd1XfzLC/BelNXpEVp+DP1RAgp8csstQkBJVWkz6RU`** (RSA 4096) coincide en CI y en `ssh-keygen -lf` local sobre la privada. |
| **Workflow 04 · Deploy** | Sigue fallando con **`Permission denied (publickey)`** en el paso **Probar SSH (BatchMode)** (post-#159), **antes** de rsync. **No** es fallo de formato PEM/libcrypto. |
| **Hipótesis principal** | **`SERVER_USER` / `SERVER_HOST`** en secretos distintos del par real **o** **restricción por IP** en GoDaddy/cPanel (SSH permitido desde IP de casa; **runners GitHub** tienen IPs dinámicas). |
| **Workflow 14 · Ops Hub** | **Verde** tras fix en `scripts/generate_gano_ops_progress.py` (#157). |
| **PR #160** | Abierto: docs troubleshooting “huella coincide pero publickey”. **Merge bloqueado** por política de rama en GitHub (ruleset); fusionar desde UI cuando haya permiso o revisión. |

---

## 2. Evidencia técnica (no repetir secretos)

### 2.1 Logs de Actions relevantes

- Deploy fallido tras merge #159: run **24148915581** — falla en **Probar SSH (BatchMode)**.
- Ops Hub OK mismo push: run **24148915574** (workflow 14).

### 2.2 Pasos añadidos al `deploy.yml` (PR #159 en `main`)

1. **Huella de clave en ssh-agent** — `ssh-add -l -E sha256`
2. **Probar SSH (BatchMode)** — `ssh user@host "echo SSH_OK && whoami && pwd"`
3. **RSYNC_RSH** — `ssh -o BatchMode=yes -o ConnectTimeout=30`

Documentación: [`memory/ops/github-actions-ssh-secret-troubleshooting.md`](../ops/github-actions-ssh-secret-troubleshooting.md) (incluye sección *Huella local = huella en CI, pero sigue publickey*).

### 2.3 Comparación de huella (local vs CI)

En Windows, en el equipo de Diego:

```powershell
ssh-keygen -lf "$env:USERPROFILE\.ssh\id_rsa_deploy"
```

Resultado esperado (clave privada, mismo formato que `ssh-add -l` sobre la clave cargada): **4096 SHA256:dyd1XfzLC/BelNXpEVp+DP1RAgp8csstQkBJVWkz6RU** … (comentario tipo cPanel).

**Nota:** `id_rsa_deploy.pub` puede no existir; la huella se obtiene igual desde el archivo **privado** con `ssh-keygen -lf`.

---

## 3. Qué revisar cuando haya tokens / acceso

1. **GitHub → Settings → Secrets → Actions:** verificar que `SERVER_USER` sea exactamente el usuario SSH (p. ej. `f1rml03th382`) y `SERVER_HOST` el mismo host/IP que en pruebas exitosas (`ssh` local).
2. **GoDaddy / cPanel:** SSH Access, firewall, o “solo IPs permitidas” — si existe, documentar si se puede ampliar a rangos de GitHub ([API meta `actions`](https://api.github.com/meta)) o si hace falta **self-hosted runner**.
3. **Fusionar PR #160** si la política de rama lo permite tras revisión.
4. **Anthropic / APIs:** si “tokens” se refiere a cuota Claude: retomar colas en [`dispatch-queue.json`](dispatch-queue.json) y prompts en [`dispatch-prompt.md`](dispatch-prompt.md).

---

## 4. Estado del repositorio (referencias)

| Recurso | Contenido |
|---------|-----------|
| [`TASKS.md`](../../TASKS.md) | Active, Fase 4, homepage |
| [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](../ops/homepage-vitrina-launch-plan-2026-04.md) | Plan vitrina + RACI |
| [`AGENTS.md`](../../AGENTS.md) | Fuentes de verdad |
| [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) | Git ↔ servidor |
| PR **#159** (merged) | Plan vitrina + prechequeo SSH en deploy |
| PR **#160** (open) | Doc IP / huella |

---

## 5. Pendientes producto (sin bloquear Claude)

- **Homepage:** aplicar [`memory/content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md) en Elementor (humano, wp-admin).
- **Fase 4 Reseller:** RCC, PFIDs, [`memory/commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md).
- **wp-file-manager:** eliminar en servidor (workflow 12 o manual) — [`TASKS.md`](../../TASKS.md).

---

## 6. Cómo usar este reporte en la siguiente sesión

1. Leer este archivo primero.  
2. Luego [`02-pendientes-detallados.md`](02-pendientes-detallados.md) y [`TASKS.md`](../../TASKS.md).  
3. No asumir que deploy funciona hasta que **04** esté verde en Actions tras un push a rutas cubiertas.

---

_Documento generado para continuidad entre sesiones; actualizar si cambia la hipótesis de IP o los secretos._
