# Fase 0 — webhook deploy validado (checklist operativo)

Fecha: 2026-04-11

## Estado

- Workflow validado: `.github/workflows/deploy.yml`
- Metodo: webhook HTTPS firmado por HMAC-SHA256
- Receiver en repo: `wp-content/gano-deploy/receive.php`

## Secrets requeridos (GitHub Actions)

- `GANO_DEPLOY_HOOK_URL`
  - Valor esperado: `https://gano.digital/wp-content/gano-deploy/receive.php`
- `GANO_DEPLOY_HOOK_SECRET`
  - Mismo valor que `GANO_DEPLOY_HOOK_SECRET` en `wp-config.php` del servidor

## Setup rapido

1. Generar secret local (64 hex chars):
   - `openssl rand -hex 32`
2. Agregar en `wp-config.php` (servidor):
   - `define('GANO_DEPLOY_HOOK_SECRET', '...');`
3. Configurar los 2 secrets en GitHub repo (`Settings -> Secrets and variables -> Actions`).

## Prueba recomendada

1. Ejecutar workflow manual:
   - `04 · Deploy · Produccion (webhook HTTPS)`
2. Confirmar logs de pasos:
   - `Security Scan` OK
   - `Sign and send webhook` HTTP 2xx
   - `Verify Deployment` HTTP 2xx/3xx
3. Confirmar respuesta del webhook con JSON valido.

## Criterios de salida de Fase 0

- Secrets configurados y sin placeholders.
- Workflow 04 termina en verde.
- `gano.digital` responde HTTP correcto despues de deploy.
