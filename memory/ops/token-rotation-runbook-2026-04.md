# Runbook: Rotación de Tokens y Limpieza de Remotes

**Fecha:** 2026-04-19 · **Issue:** [#267](https://github.com/Gano-digital/Pilot/issues/267)  
**Audiencia:** Diego (operación humana). Los agentes de código no pueden ejecutar estas acciones.

> **Estado:** ⚠️ BLOQUEADO por acción humana. Este documento detalla exactamente qué revisar y qué rotar.

---

## 1. Estado actual del repo (verificado 2026-04-19)

| Ítem | Estado |
|------|--------|
| Remote `origin` | `https://github.com/Gano-digital/Pilot` — ✅ limpio (sin token en URL) |
| Remote `gitlab` | No presente en el clon actual — ✅ OK (ver `memory/ops/archived-gitlab-remote-2026-04.md`) |
| TruffleHog CI | Activo en `secret-scan.yml` — escanea cada push a `main` |
| Archivos `.env` en repo | No existen (verificado) — ✅ OK |
| `wp-config.php` en repo | No existe (correcto — nunca debe estar en git) — ✅ OK |

---

## 2. Checklist de limpieza de remotes (local y clones)

Para cada máquina donde tengas un clon local del repo:

```bash
# 1. Verificar remotes
git remote -v

# Resultado esperado (solo origin sin token):
# origin  https://github.com/Gano-digital/Pilot (fetch)
# origin  https://github.com/Gano-digital/Pilot (push)
```

- [ ] Confirmar que no hay `https://TOKEN@github.com/…` en ningún remote.
- [ ] Si existe un remote `gitlab`: `git remote remove gitlab`.
- [ ] Si existe cualquier remote con token en la URL: `git remote set-url origin https://github.com/Gano-digital/Pilot`.

---

## 3. Tokens y credenciales a rotar o revisar

### 3.1 GitHub Personal Access Token (PAT)

| Token | Uso | Dónde está | Acción |
|-------|-----|-----------|--------|
| `ADD_TO_PROJECT_PAT` | Workflow 13 — Add to Project | GitHub Secrets del repo | Revisar expiración; rotar si expiró o si hubo exposición |
| Token de `gh auth login` local | CLI `gh` en máquinas locales | Máquina local (keychain o token file) | Si la máquina no es solo tuya: `gh auth logout` + `gh auth login` para regenerar |

**Cómo rotar un PAT de GitHub:**
1. Ir a <https://github.com/settings/tokens>.
2. Seleccionar el token correspondiente.
3. Clic en **Regenerate** (o **Delete** y crear nuevo).
4. Actualizar el secret en: **GitHub repo → Settings → Secrets and variables → Actions → `ADD_TO_PROJECT_PAT`**.
5. Verificar que los workflows que lo usan siguen funcionando.

### 3.2 Secretos de GitHub Actions (repo)

Revisar que todos los secretos configurados sean actuales y no hayan sido expuestos:

| Secret | Cuándo rotar |
|--------|-------------|
| `GANO_DEPLOY_HOOK_URL` | Si el endpoint webhook cambió o hubo exposición |
| `GANO_DEPLOY_HOOK_SECRET` | Rotar si hubo acceso no autorizado al servidor |
| `SSH` (clave privada) | Rotar si la clave fue copiada a una máquina no segura |
| `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` | Actualizar si el servidor cambia; no son secretos críticos per se |
| `GODADDY_API_KEY` / `GODADDY_API_SECRET` | Rotar en GoDaddy Reseller Portal → Developer Tools → API Keys |
| `ADD_TO_PROJECT_PAT` | Rotar en GitHub Settings → Tokens |

**Cómo actualizar un secret en GitHub:**
1. <https://github.com/Gano-digital/Pilot/settings/secrets/actions>
2. Clic en el secret → **Update**.
3. Pegar el nuevo valor → **Save**.

### 3.3 Clave SSH de deploy

Si la clave SSH (`id_rsa` o equivalente) fue generada para CI/deploy:

- [ ] Verificar que la clave pública correspondiente está en `~/.ssh/authorized_keys` del servidor.
- [ ] Si la clave privada estuvo en texto plano en algún lugar no seguro: generar par nuevo.

```bash
# Generar nuevo par (solo si es necesario rotar)
ssh-keygen -t ed25519 -C "gano-deploy-2026" -f ~/.ssh/gano_deploy_ed25519

# Subir clave pública al servidor
ssh-copy-id -i ~/.ssh/gano_deploy_ed25519.pub usuario@servidor

# Actualizar secret SSH en GitHub (pegar contenido de la clave privada)
cat ~/.ssh/gano_deploy_ed25519
```

> ⚠️ Nunca commitear la clave privada. Solo la clave pública puede ir en el servidor.

### 3.4 GoDaddy API Key / Secret (Reseller)

- [ ] Si se sospecha exposición: GoDaddy Reseller Portal → Developer Tools → API Keys → **Regenerate**.
- [ ] Actualizar secret `GODADDY_API_KEY` y `GODADDY_API_SECRET` en GitHub.

### 3.5 Wordfence (wp-admin)

- [ ] Verificar que la cuenta de wp-admin de Wordfence no tiene alertas de actividad sospechosa.
- [ ] Si hubo cambio de contraseña de wp-admin: actualizar también en Wordfence si usa autenticación propia.

---

## 4. Verificación post-rotación

```bash
# 1. TruffleHog local (si está instalado) — escanear historial reciente
trufflehog git file://. --since-commit HEAD~10 --only-verified

# 2. Verificar que CI/CD sigue funcionando tras rotar secrets
# → GitHub Actions → último workflow run → confirmar verde

# 3. Verificar remote limpio
git remote -v
git config --list | grep url
```

- [ ] CI (secret-scan.yml + php-lint-gano.yml) pasa en verde tras rotar.
- [ ] Workflows de deploy (04, 05) funcionan con los secretos actualizados.

---

## 5. Calendario de rotación recomendado

| Token / Secreto | Frecuencia | Próxima fecha |
|-----------------|-----------|---------------|
| GitHub PAT (`ADD_TO_PROJECT_PAT`) | Cada 90 días o al expirar | Revisar en <https://github.com/settings/tokens> |
| SSH deploy key | Anual o ante incidente | — |
| GoDaddy API Key/Secret | Anual o ante incidente | — |
| `GANO_DEPLOY_HOOK_SECRET` | Anual o ante incidente | — |

---

## 6. Qué NO puede hacer un agente de código

- No puede revocar tokens en GitHub ni GoDaddy (requiere acceso a Settings con autenticación del propietario).
- No puede vaciar historial git remoto (purgar requiere `git filter-repo` + coordinación del equipo).
- No puede acceder ni desactivar cuentas de hosting (cPanel, GoDaddy, Wordfence, wp-admin).

---

## 7. Checklist de cierre (issue #267)

```
BLOQUEADO por acción humana (Diego):
  [ ] Remote git verificado: sin tokens en URLs (git remote -v)
  [ ] Remote gitlab removido si existe en clones locales
  [ ] PATs de GitHub revisados: no expirados, no expuestos
  [ ] ADD_TO_PROJECT_PAT rotado si es necesario y secret actualizado
  [ ] SSH deploy key revisada; rotada si fue expuesta
  [ ] GoDaddy API Key/Secret revisados; rotados si necesario
  [ ] Secretos de GitHub Actions actualizados tras rotación
  [ ] CI/CD verificado en verde tras cambios
  [ ] Fecha de rotación anotada en este documento o en issue #267
```

---

**Creado:** 2026-04-19 · **Issue:** #267  
**Referencias:** `memory/ops/archived-gitlab-remote-2026-04.md` · `memory/ops/security-end-session-checklist.md` · `memory/ops/security-guardian-playbook-2026.md` · `.github/DEV-COORDINATION.md`
