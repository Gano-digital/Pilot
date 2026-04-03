# Checklist: 2FA en wp-admin (Wordfence)

**Plugins:** Wordfence (u otro 2FA compatible). **Antes:** asegurar acceso a email y guardar **códigos de recuperación** en lugar seguro.

## Pasos (genéricos — nombres de menú pueden variar según versión)

1. **wp-admin → Wordfence → Login Security** (o ruta equivalente “2FA”).
2. Activar **2FA** para al menos un rol **Administrator**.
3. Escanear QR con app autenticadora (Google Authenticator, Authy, etc.) o usar métodos alternativos que ofrezca Wordfence.
4. Probar **cerrar sesión** y volver a entrar con código TOTP.
5. Generar y archivar **códigos de respaldo**; sin ellos, un fallo del teléfono bloquea el acceso.
6. (Opcional) Usuario de emergencia o segundo admin con 2FA distinto.

## Advertencias

- No desactivar 2FA en producción “temporalmente” sin plan.
- Documentar en bitácora interna **quién** tiene acceso 2FA (sin pegar secretos en GitHub).

## Referencia

- `TASKS.md` — ítem 2FA / Wordfence.
