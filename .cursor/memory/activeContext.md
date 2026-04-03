# Active Context — Estado Actual

*Última actualización: 2026-04-02 (post-actualización rules + memory bank alineado)*

## Foco actual (producto y repo)

- **Servidor / producción:** desplegar parches Fases 1–3 al hosting real, eliminar `wp-file-manager`, configurar Gano SEO / GSC / Rank Math (`TASKS.md` sección Active).
- **GitHub `Gano-digital/Pilot`:** triage de **PRs en borrador** (~35 a última revisión), merge ordenado (`MERGE-PLAYBOOK`), cierre de issues oleada 1 (#17–33) y oleada 3 (#54–68) cuando el trabajo esté cubierto.
- **Homepage / Elementor:** copy desde `memory/content/`, menú primary, sustituir Lorem (issues `homepage-fixplan`).
- **Fase 4:** catálogo Reseller, mapeo CTAs en `shop-premium.php`, smoke test checkout (sin asumir estado de servidor no documentado).

## Completado recientemente (entorno + gobernanza agentes)

- [x] **Setup Cursor:** rules **9** `.mdc` (001–006, 100–101, 200), memory protocol, PHP/WP, CSS/JS, git workflow, copilot oversight.
- [x] Memory bank (`.cursor/memory/`) operativo; `AGENTS.md` y flujo multi-agente documentados.
- [x] Skills proyecto en `.gano-skills/` (incl. orquestación Copilot + multi-agente local) alineadas con cola GitHub y prompts por oleada.

## En progreso

- [ ] SSH key / acceso: generar o completar según política GoDaddy Managed WordPress (a menudo **solo SFTP**, no SSH shell).
- [ ] SFTP / sync VS Code si aplica al flujo de deploy de Diego.
- [ ] Revisión y merge de PRs Copilot (cuello actual: muchos **draft**).
- [ ] Deploy de archivos críticos al servidor y tareas solo-wp-admin listadas en `TASKS.md`.

## Bloqueadores

- **SSH vs SFTP:** en muchos planes Managed WordPress no hay shell; validar con el panel GoDaddy antes de asumir `ssh`.
- **Brecha Git ↔ producción:** código en `main` no reflejado en gano.digital hasta deploy + acciones en panel.

## Próximos pasos (orden sugerido)

1. Triage PRs (priorizar `memory/**/*.md` wave3 de bajo riesgo, luego tema/MU).
2. Deploy checklist F1–3 + retirada `wp-file-manager`.
3. Panel: SEO, menús, copy real en Elementor.
4. Fase 4 Reseller: RCC + prueba de flujo de compra.
5. Opcional: Actions **Orchestrate Copilot waves** con `dry_run_merge: true` antes de merges masivos.

## Decisiones / referencias

- Fuentes de verdad: `TASKS.md`, `CLAUDE.md`, `.github/DEV-COORDINATION.md`, `.github/copilot-instructions.md`.
- Jerarquía si hay conflicto: `CLAUDE.md` > `copilot-instructions.md` > `AGENTS.md`. **Comercio y checkout:** solo **GoDaddy Reseller / lo que ya está en el hosting GoDaddy** — no priorizar Wompi ni pasarelas ajenas a ese ecosistema.
- Resumen ejecutivo para humanos: `memory/sessions/2026-04-02-progreso-consolidado.md`.
