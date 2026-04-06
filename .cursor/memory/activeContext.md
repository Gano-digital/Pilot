# Active Context — Estado Actual

_Última actualización: 2026-04-03 (dispatch Claude cerrado + PHP CLI habilitado + diagnóstico SSH)_

## Foco actual (producto y repo)

- **Servidor / producción:** desplegar parches Fases 1–3 al hosting real, eliminar `wp-file-manager`, configurar Gano SEO / GSC / Rank Math (`TASKS.md` sección Active).
- **GitHub `Gano-digital/Pilot`:** repositorio en `main` ya consolidado; foco actual = cierre manual de issues cubiertos por `main`, uso selectivo de colas opcionales (API / security guardian) y verificación de accesos.
- **Homepage / Elementor:** copy desde `memory/content/`, menú primary, sustituir Lorem (issues `homepage-fixplan`).
- **Fase 4:** catálogo Reseller, mapeo CTAs en `shop-premium.php`, smoke test checkout (sin asumir estado de servidor no documentado).

## Completado recientemente (entorno + gobernanza agentes)

- [x] **Setup Cursor:** rules **9** `.mdc` (001–006, 100–101, 200), memory protocol, PHP/WP, CSS/JS, git workflow, copilot oversight.
- [x] Memory bank (`.cursor/memory/`) operativo; `AGENTS.md` y flujo multi-agente documentados.
- [x] Skills proyecto en `.gano-skills/` (incl. orquestación Copilot + multi-agente local) alineadas con cola GitHub y prompts por oleada.
- [x] **Cola Claude dispatch:** tareas `cd-repo-001` a `cd-repo-012` completadas; checklists operativos creados en `memory/commerce/` y `memory/ops/`.
- [x] **PHP CLI local:** PHP 8.3 habilitado en Windows y `php -l wp-content/themes/gano-child/functions.php` pasa sin errores.

## En progreso

- [ ] SSH key / acceso: validar autorización real de la clave actual contra GitHub y servidor; el transporte responde, pero la autenticación por clave sigue fallando.
- [ ] SFTP / sync VS Code si aplica al flujo de deploy de Diego.
- [ ] Deploy de archivos críticos al servidor y tareas solo-wp-admin listadas en `TASKS.md`.

## Bloqueadores

- **SSH autorización:** `origin` responde por HTTPS, pero `ssh` a `git@github.com` y al host `72.167.102.145` devuelve rechazo de clave; no asumir shell operativo hasta validar `authorized_keys` / deploy key.
- **Brecha Git ↔ producción:** código en `main` no reflejado en gano.digital hasta deploy + acciones en panel.

## Próximos pasos (orden sugerido)

1. Validar manualmente clave SSH en GitHub y en el servidor; si funciona, migrar `origin` a SSH solo si aporta valor operativo.
2. Deploy checklist F1–3 + retirada `wp-file-manager`.
3. Panel: SEO, menús, copy real en Elementor.
4. Fase 4 Reseller: RCC + PFIDs reales + prueba de flujo de compra.
5. GitHub: cerrar issues cubiertos por `main`; sembrar colas opcionales solo si hacen falta.

## Decisiones / referencias

- Fuentes de verdad: `TASKS.md`, `CLAUDE.md`, `.github/DEV-COORDINATION.md`, `.github/copilot-instructions.md`.
- Jerarquía si hay conflicto: `CLAUDE.md` > `copilot-instructions.md` > `AGENTS.md`. **Comercio y checkout:** solo **GoDaddy Reseller / lo que ya está en el hosting GoDaddy** — no priorizar Wompi ni pasarelas ajenas a ese ecosistema.
- Resumen ejecutivo para humanos: `memory/sessions/2026-04-02-progreso-consolidado.md`.
- Estado dispatch y documentación operativa reciente: `memory/sessions/2026-04-03-claude-dispatch.md`.
