---
name: project-server-state
description: "Estado del servidor SSH — runner GitHub, bugs CSP activos, rutas clave"
metadata: 
  node_type: memory
  type: project
  originSessionId: c58974dc-9c79-4530-bd2d-a948dce24523
---

Estado auditado el 2026-06-18.

## Rutas clave
- Sitio principal: `/home/f1rml03th382/public_html/gano.digital/`
- WordPress raíz (legacy): `/home/f1rml03th382/public_html/` (WP secundario/staging)
- GitHub Runner: `/home/f1rml03th382/github-runner/`
- Repo clonado por runner: `/home/f1rml03th382/github-runner/_work/Pilot/Pilot/`

## GitHub Runner — OFFLINE
- Último job procesado: 2026-04-09 01:40:41Z (Deploy Succeeded)
- Error final: "Runner not found / Runner listener exit with terminated error"
- El runner fue desregistrado de GitHub (token expirado o eliminado desde la UI)
- Para reactivar: Diego debe ir a github.com/Gano-digital/Pilot/settings/actions/runners, eliminar runner offline, crear nuevo token, y ejecutar `./config.sh` con el nuevo token.

**Why:** Sin runner activo, ningún push a `main` dispara `deploy.yml`. El pipeline está muerto.
**How to apply:** Recordar siempre que los deploys son manuales mientras el runner esté offline.

## CSP Bugs activos (gano-security.php línea 350-362)
### Bug 1 — CRÍTICO: gui.secureserver.net no está en script-src
- Dominio `gui.secureserver.net` está en `connect-src` pero NO en `script-src`
- GoDaddy Reseller Store carga JavaScript desde `gui.secureserver.net` (header/footer assets)
- Resultado: scripts bloqueados en /catalogo/, /ecosistemas/, /hosting/, /dominios/, homepage
- Fix propuesto: agregar `https://gui.secureserver.net` a `script-src` en gano-security.php

### Bug 2 — MEDIO: style-src-elem y script-src-elem no declarados explícitamente
- El CSP tiene `style-src` y `script-src` con `cdnjs.cloudflare.com` correctamente
- Algunos browsers (Chromium) reportan violaciones bajo `style-src-elem`/`script-src-elem` aunque los recursos estén cubiertos por el fallback de `style-src`/`script-src`
- Fix: agregar directivas explícitas `script-src-elem` y `style-src-elem` con los mismos dominios
- NO tocar sin confirmación de Diego

## CSP Header real (verificado con curl 2026-06-18)
Solo hay UN header CSP (no doble). La política viene exclusivamente de gano-security.php. Sin conflicto a nivel servidor.
