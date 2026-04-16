# Fase 1 — instrucciones operativas (deploy y validacion)

Fecha: 2026-04-11  
Objetivo: llevar cambios de `main` a produccion con validacion post-deploy

## Tareas secuenciales (A1–A4)

### A1. Verificar prerequisitos

- [ ] Secrets `GANO_DEPLOY_HOOK_URL` y `GANO_DEPLOY_HOOK_SECRET` configurados.
- [ ] Receiver accesible en servidor (`/wp-content/gano-deploy/receive.php`).
- [ ] `wp-config.php` contiene `GANO_DEPLOY_HOOK_SECRET` correcto.

### A2. Ejecutar deploy

- [ ] Lanzar workflow:
  - `04 · Deploy · Produccion (webhook HTTPS)`
- [ ] Confirmar estado verde del run.

### A3. Validacion funcional minima

- [ ] Home `https://gano.digital` responde HTTP 200/3xx.
- [ ] `wp-admin` abre correctamente.
- [ ] Templates clave cargan sin error fatal:
  - `shop-premium`
  - `ecosistemas`
  - `sota-hub`
  - `diagnostico-digital`

### A4. Validacion de seguridad operativa

- [ ] Workflow `05 · Ops · Verificar parches en servidor` ejecutado.
- [ ] Workflow `12 · Ops · Eliminar wp-file-manager (SSH)` ejecutado o verificado manualmente.
- [ ] `gano-security.php` y `gano-seo.php` presentes en MU plugins.

## Troubleshooting rapido

- Si falla webhook con 403:
  - revisar firma HMAC/secret desalineado.
- Si falla con 503:
  - `GANO_DEPLOY_HOOK_SECRET` no configurado en `wp-config.php`.
- Si run queda verde pero no hay cambios:
  - revisar `paths` trigger o lanzar `workflow_dispatch` manual.

## Criterios de cierre Fase 1

- Deploy completado y validado.
- Sitio operativo sin regresiones severas.
- Checklist de seguridad basica completado.
