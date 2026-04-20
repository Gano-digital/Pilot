# Acta de cierre — Rotación de tokens y limpieza de remotes

**Issue:** #267 `[sec] Rotación de tokens y limpieza de remotes`
**Fecha:** 2026-04-19
**Agente:** GitHub Copilot (copilot-swe-agent)
**Estado:** Parcialmente completado — bloqueos manuales documentados

---

## Resumen ejecutivo

Se realizó auditoría completa de credenciales, remotes y workflows de GitHub Actions.
Se encontró y corrigió una exposición de datos sensibles en documentación.
Las acciones de rotación de secretos reales requieren intervención manual de Diego.

---

## 1. Verificación de remotes / URLs con credenciales

**Resultado: ✅ Limpio**

```
origin  https://github.com/Gano-digital/Pilot (fetch)
origin  https://github.com/Gano-digital/Pilot (push)
```

No se encontraron tokens ni contraseñas embebidos en ninguna URL de remote.
Verificado con: `grep -rE "https?://[^@\s]+:[^@\s]+@"` en todo el repositorio.

---

## 2. Verificación de secretos en GitHub Actions

**Resultado: ✅ Correcto**

Todos los workflows usan el patrón `${{ secrets.XXX }}` sin excepción:

| Secret | Workflows que lo usan |
|--------|----------------------|
| `SSH` | `06-db-backup.yml`, `07-sync-content.yml`, `08-health-check-plugins.yml`, `31-plugin-health-check-phase4.yml` |
| `SERVER_HOST` | ídem |
| `SERVER_USER` | ídem |
| `DEPLOY_PATH` | ídem |
| `GANO_DEPLOY_HOOK_URL` | `deploy.yml` |
| `GANO_DEPLOY_HOOK_SECRET` | `deploy.yml` |

Todos los workflows declaran `permissions:` mínimos a nivel top-level o por job.
TruffleHog scan activo via `secret-scan.yml` en cada push/PR a `main`.

---

## 3. Hallazgos y correcciones

### 3.1 ✅ CORREGIDO — Datos sensibles en DEVOPS-GUIDE.md

**Archivo:** `DEVOPS-GUIDE.md` (sección "Secretos Críticos")

**Problema:** La tabla de secretos contenía valores reales en texto plano:
- `SERVER_HOST`: IP real del servidor
- `SERVER_USER`: usuario cPanel real

**Corrección aplicada:** Los valores fueron reemplazados por referencias descriptivas
que apuntan a GoDaddy cPanel / GitHub Settings → Secrets, sin exponer datos reales.

**Commit:** incluido en este PR.

### 3.2 ✅ LIMPIO — ssh_cli.py

El script `ssh_cli.py` usa exclusivamente variables de entorno (`GANO_SSH_HOST`,
`GANO_SSH_USER`, `GANO_SSH_PASS`, `GANO_SSH_KEY_PATH`). No hay credenciales embebidas.

### 3.3 ✅ LIMPIO — Código PHP/JS Gano

Búsqueda en `wp-content/themes/gano-child`, `wp-content/mu-plugins`, `scripts/` y
`.github/`: sin credenciales hardcodeadas en código Gano custom.

---

## 4. Acciones bloqueadas — Requieren intervención manual de Diego

Las siguientes acciones **no pueden ejecutarse por un agente** y deben ser completadas
manualmente antes de considerar el issue completamente cerrado:

### 4.1 🔴 Rotación de clave SSH (`SSH` secret)

**Cuándo rotar:** DEVOPS-GUIDE.md recomienda cada 90 días.
**Pasos:**
1. Generar nuevo par RSA en máquina segura:
   ```bash
   ssh-keygen -t rsa -b 4096 -f ~/.ssh/gano_deploy_new -C "gano-deploy-$(date +%Y%m%d)"
   ```
2. Agregar `gano_deploy_new.pub` a `~/.ssh/authorized_keys` en el servidor (GoDaddy SSH).
3. Actualizar secret `SSH` en GitHub: Settings → Secrets and variables → Actions → `SSH`.
4. Verificar que el workflow `deploy.yml` funciona con la nueva clave.
5. Eliminar la clave pública antigua de `authorized_keys` en el servidor.

### 4.2 🔴 Rotación del webhook secret (`GANO_DEPLOY_HOOK_SECRET`)

**Pasos:**
1. Generar nuevo secret de 64 caracteres:
   ```bash
   openssl rand -hex 32
   ```
2. Actualizar el valor en el servidor (`receive.php` o variable de entorno que valida HMAC).
3. Actualizar secret `GANO_DEPLOY_HOOK_SECRET` en GitHub.
4. Ejecutar `deploy.yml` manualmente para verificar.

### 4.3 🟡 Rotación de GoDaddy API Key/Secret (si está en uso)

**Estado:** No hay workflows activos que usen `GODADDY_API_KEY` / `GODADDY_API_SECRET`.
**Acción:** Si se crea alguna automatización con la API de GoDaddy, rotar en:
https://developer.godaddy.com/keys → crear nuevo par → actualizar GitHub Secrets.

### 4.4 🟡 Verificar expiración de GitHub PAT (si existe)

**Pasos:** Revisar en https://github.com/settings/tokens si hay algún PAT activo
asociado al bot/automatización. Rotar los que estén próximos a expirar o sean
de alcance más amplio del necesario.

---

## 5. Checklist de cierre de oleada

- [x] Remotes verificados — limpios, sin credenciales
- [x] Workflows auditados — todos usan `secrets.*` correctamente
- [x] Permisos de workflows — todos declaran `permissions:` mínimos
- [x] TruffleHog activo — `secret-scan.yml` corre en push/PR a main
- [x] `ssh_cli.py` — usa solo variables de entorno
- [x] Código PHP/JS Gano — sin credenciales hardcodeadas
- [x] `DEVOPS-GUIDE.md` — corregido (IP y usuario redactados)
- [ ] Rotación SSH key — **pendiente Diego**
- [ ] Rotación webhook secret — **pendiente Diego**
- [ ] Verificar expiración PATs — **pendiente Diego**

---

## 6. Referencias

| Recurso | Ruta |
|---------|------|
| Workflows auditados | `.github/workflows/` |
| Guía seguridad workflows | `memory/ops/security-workflows-permissions-note-2026.md` |
| Playbook guardián seguridad | `memory/ops/security-guardian-playbook-2026.md` |
| DEVOPS-GUIDE (corregido) | `DEVOPS-GUIDE.md` |
| SSH troubleshooting | `memory/ops/github-actions-ssh-secret-troubleshooting.md` |
