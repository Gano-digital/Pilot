# Plan consolidado aprobado — inicio de ejecucion

Fecha: 2026-04-11  
Estado: Alineado con repo local y listo para operacion por fases

## Verificacion de consistencia (Claude vs repo)

1. Workflow de deploy por webhook HTTPS
   - Verificado: `.github/workflows/deploy.yml`
   - Usa secrets:
     - `GANO_DEPLOY_HOOK_URL`
     - `GANO_DEPLOY_HOOK_SECRET`
   - Endpoint esperado:
     - `https://gano.digital/wp-content/gano-deploy/receive.php`

2. Webhook receiver
   - Verificado en repo: `wp-content/gano-deploy/receive.php`
   - Implementa:
     - firma HMAC (`X-Gano-Signature`)
     - allowlist de rutas Gano
     - respuesta JSON con `deployed/skipped/errors`

3. Estado de issues
   - Issues Constellation previos cerrados.
   - Oleada activa actual: issues `#183` a `#197` abiertos.

4. Documentos mencionados por Claude
   - Existia el plan en `C:\Users\diego\.claude\plans\binary-brewing-treasure.md`
   - Se detecto estado "Plan Mode — Requiere Aprobacion" en ese archivo.
   - Se crean estos documentos de ejecucion en el repo para continuidad trazable.

## Decisiones operativas alineadas

- Deploy a produccion: webhook HTTPS (no SSH runner externo).
- Integracion visual: capa convergente `gano-km-*` ya implementada en tema/templates.
- Chat: mantener placeholder operativo; evolucion a Fase 5.
- Comercio: foco Reseller GoDaddy (sin pasarelas fuera del ecosistema actual).

## Orden de ejecucion recomendado

1. Fase 0: verificar secrets webhook.
2. Fase 1: ejecutar workflow 04 + validar sitio.
3. Fase 2: aplicar ajustes de Elementor (home) con clases convergentes.
4. Fase 3: depurar RCC y smoke test checkout.
5. Fase 4: cierre de issues/oleadas y QA final.
