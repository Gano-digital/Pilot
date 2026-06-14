# Checklist: Wordfence + 2FA en wp-admin

Objetivo: habilitar 2FA para cuentas administrativas y reducir riesgo de acceso no autorizado.

Referencia:

- `TASKS.md` (item 2FA / Wordfence)

## 1) Precondiciones

- Confirmar Wordfence activo y actualizado.
- Identificar cuentas admin criticas (owner + backup admin).
- Definir almacenamiento seguro para recovery codes.

## 2) Configuracion base

1. Ir a `wp-admin -> Wordfence -> Login Security` (o menu equivalente).
2. Activar 2FA para rol `Administrator`.
3. Revisar politicas de password y lockout.

## 3) Enrolamiento

1. Escanear QR con app TOTP por cada admin.
2. Guardar recovery codes fuera de WordPress.
3. Probar login completo con OTP.

## 4) Validacion

- Verificar que un admin ya no entra sin OTP.
- Probar un recovery code en prueba controlada.
- Confirmar que no se bloquea ninguna cuenta critica.

## 5) Buenas practicas

- Mantener un usuario de emergencia con proceso documentado.
- No publicar secretos o codigos en GitHub.
- Registrar fecha/responsable en bitacora interna.
