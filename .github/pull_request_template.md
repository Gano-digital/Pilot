## Resumen

Describe el cambio en una o dos frases.

## Área

- [ ] Tema `gano-child`
- [ ] MU-plugins / seguridad
- [ ] Plugins `gano-*`
- [ ] CI / GitHub
- [ ] Contenido / Elementor (nota: mucho vive en BD; documenta IDs o export)

## Checklist

- [ ] No se añaden credenciales, tokens ni rutas con usuario de hosting en claro.
- [ ] Si toca checkout/Reseller o plugins de pago: revisado según `TASKS.md` y sin secretos en claro.
- [ ] `php -l` local en archivos tocados (o confío en CI).
- [ ] Si el cambio ya está en servidor o diverge de prod: actualicé [`DEV-COORDINATION.md`](.github/DEV-COORDINATION.md) / `TASKS.md` o abrí issue **[sync]**.

## Notas para Copilot / revisores

Contexto extra, URLs de preview o capturas.

Si el PR lo abrió **Copilot coding agent**, enlaza el **issue** origen y confirma que pasan **PHP lint**, **TruffleHog** y **CodeQL** (ruleset / code quality).
