# Guardián de seguridad — playbook (Gano Digital)

**Propósito:** reducir superficie de exposición cuando **no** estás trabajando y al **cierre de cada sesión**. Complementa CI (TruffleHog, PHP lint), no los sustituye.

**Roles:** partes son **solo humano** (bloqueo de equipo, credenciales del SO); partes pueden **automatizarse en repo** vía issues `sec-*` y Copilot.

---

## 1. Qué se considera «sensible» aquí

| Tipo | Ejemplos | Acción típica |
|------|-----------|-----------------|
| **Secretos** | PAT `ghp_`, tokens Wompi/API, `client_secret`, claves SSH privadas, contraseñas wp-admin | No en git; rotar si filtrados; usar GitHub Secrets / gestor de secretos |
| **Identificadores operativos** | IP + usuario cPanel, rutas internas de servidor | Evitar en issues/PRs públicos; placeholders en docs |
| **Datos personales** | NIT, teléfonos directos de terceros | Solo donde el negocio lo exige; anonimizar en issues de prueba |
| **No esencial en repo** | Logs pegados, dumps SQL, `.env` real, capturas con URLs con token | No commitear; purgar del historial solo con `git filter-repo` y soporte si hace falta |

---

## 2. Recordatorio en terminal (opcional)

```bash
python scripts/security_session_reminder.py
```

---

## 3. Cierre de sesión — humano (obligatorio)

1. **Cerrar** editores y terminales con sesiones activas en servidores (`ssh`, `wp`, rsync).
2. **Bloquear** el equipo (Win+L) si abandonás el puesto; apagar o hibernar si es fin del día.
3. **No dejar** archivos `id_rsa`, `.pem`, `.env` en Escritorio o carpetas sincronizadas sin cifrar si no son necesarios.
4. **Portapapeles:** si copiaste un token, sobrescribí con otro texto antes de compartir pantalla.
5. **`gh` / Git:** si usaste `gh auth login` en una máquina compartida, valorar `gh auth logout` (inconveniente: tendrás que volver a autenticar).

---

## 4. Cierre de sesión — repo / Git (recomendado)

1. `git status` — no commitear accidentalmente `reports/*.pdf` con datos si los generaste con info interna (los `.pdf` suelen estar ignorados).
2. Revisar que **no** hay remotes con `https://TOKEN@github.com/...` (`git remote -v`).
3. No subir **adjuntos** a issues con capturas que muestren tokens de DevTools o cookies.

---

## 5. Qué puede hacer el «agente guardián» (Copilot en GitHub)

Issues desde `tasks-security-guardian.json`:

- Auditar **rastros** en markdown (instrucciones, plantillas) con ejemplos que parezcan secretos reales.
- Proponer **mejoras** a `.gitignore` y exclusiones.
- Mantener **documentación** alineada a `gano-security.php` y políticas del repo.

### Qué NO hace el agente

- **No borra secretos de GitHub ni revoca tokens:** eso requiere acceso a GitHub Settings o CLI del administrador del repositorio.
- **No vacía historial git remoto:** purgar commits con datos sensibles exige `git filter-repo` y coordinación con el equipo; hacerlo mal puede corromper el historial.
- **No accede ni desactiva cuentas** (cPanel, GoDaddy, Wordfence, wp-admin): esas acciones son estrictamente responsabilidad humana o del soporte de hosting.

---

## 6. Relación con CI

- **02 · TruffleHog** y **01 · PHP lint** siguen siendo la red de seguridad en cada push/PR.
- Este playbook es **comportamiento** + **higiene documental** entre sesiones.

---

## 7. Referencias

- [`.gano-skills/gano-session-security-guardian/SKILL.md`](../../.gano-skills/gano-session-security-guardian/SKILL.md) — uso en Cursor al terminar sesión.
- [`memory/ops/security-end-session-checklist.md`](security-end-session-checklist.md) — lista corta imprimible (incluye atajos Bash/PowerShell).
- [`scripts/security_session_reminder.py`](../../scripts/security_session_reminder.py) — script de terminal para mostrar el recordatorio (sin red ni borrado).
- [`TASKS.md`](../../TASKS.md) — ítem de cola guardián si está sembrado.

_Última revisión: 2026-04-16._
