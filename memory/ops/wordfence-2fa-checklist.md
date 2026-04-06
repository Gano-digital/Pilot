# Checklist Wordfence + 2FA (wp-admin)

Objetivo: habilitar 2FA operativo para cuentas administrativas y reducir riesgo de acceso no autorizado.

Referencia:

- `TASKS.md` (item MEDIA: habilitar 2FA en wp-admin)

## 1) Precondiciones

- [ ] Confirmar plugin Wordfence activo y actualizado.
- [ ] Identificar cuentas admin que deben usar 2FA (al menos owner + backup admin).
- [ ] Definir canal de backup de codigos (recovery codes en lugar seguro).

## 2) Configuracion en Wordfence

- [ ] Ir a Wordfence -> Login Security.
- [ ] Habilitar 2FA para rol Administrator (y editores criticos si aplica).
- [ ] Revisar politicas de fuerza de password y lockout.

## 3) Enrolamiento de usuarios

- [ ] Cada admin escanea QR con app TOTP (ej: Authenticator).
- [ ] Guardar recovery codes de cada cuenta en almacenamiento seguro.
- [ ] Probar cierre y nuevo login con OTP valido.

## 4) Validacion

- [ ] Confirmar que login sin OTP ya no permite acceso para admins.
- [ ] Confirmar que recovery code funciona en prueba controlada.
- [ ] Verificar que no se bloqueo ninguna cuenta critica por error.

## 5) Evidencia

- [ ] Captura de pantalla de Wordfence Login Security con 2FA activo.
- [ ] Registro de cuentas enroladas (sin exponer secretos ni codigos).

## 6) DoD (Definition of Done)

- [ ] 2FA activo para cuentas administrativas clave.
- [ ] Recovery codes almacenados de forma segura.
- [ ] Login de prueba exitoso con OTP y fallback probado.

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
