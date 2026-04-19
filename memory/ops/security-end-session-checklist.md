# Checklist — cierre de sesión (2 minutos)

Marca mentalmente antes de irte:

- [ ] Commits pusheados o trabajo guardado donde corresponda; **sin** secretos en el diff.
- [ ] `git status` limpio o cambios conscientes (no `.env`, no `id_rsa`).
- [ ] `git remote -v`: sin `https://TOKEN@github.com/...` en la URL.
- [ ] Terminales **sin** SSH interactivo a producción.
- [ ] Navegador: cerrar sesiones sensibles si es PC compartida (Wompi, GoDaddy, GitHub en modo vulnerable).
- [ ] **Win+L** / bloqueo de pantalla si salís del puesto.
- [ ] (Opcional) `gh auth status` — si la máquina no es solo tuya, valorar `gh auth logout`.

**Si algo quedó en el portapapeles:** pegar texto inocuo en un archivo temporal y borrarlo.

---

## Atajos rápidos

### Bash / macOS / WSL

```bash
# Verificar status y remotes de un vistazo
git status && git remote -v

# Verificar si gh tiene sesión activa
gh auth status

# Cerrar sesión gh (solo en PC compartida o ante sospecha)
gh auth logout
```

### Windows — PowerShell

```powershell
# Verificar status y remotes
git status; git remote -v

# Verificar sesión gh
gh auth status

# Cerrar sesión gh
gh auth logout

# Bloquear pantalla inmediatamente
rundll32.exe user32.dll,LockWorkStation
```

> ⚠️ **Advertencia:** `gh auth logout` requiere volver a autenticar con `gh auth login`.
> Usarlo solo en PCs compartidas o si hay sospecha de compromiso de credenciales.

---

_Ver `memory/ops/security-guardian-playbook-2026.md` para contexto completo._

_Script rápido de terminal: `python scripts/security_session_reminder.py`_
