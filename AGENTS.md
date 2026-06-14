# AGENTS.md — Cross-Tool Agent Instructions

> Este archivo es leido por Cursor, Claude Code, GitHub Copilot, Codex, y cualquier herramienta AI que siga el estandar agents.md.

## Proyecto
**Gano Digital** — Plataforma de hosting WordPress para Colombia.
URL: https://gano.digital | Repo: Gano-digital/Pilot

## Skills disponibles en este repo
- `phase4-commerce` (`.agents/skills/phase4-commerce/SKILL.md`)
- `cloud-runbook` (`.agents/skills/cloud-runbook/SKILL.md`)
- `ui-ux-pro-max` (`.cursor/skills/ui-ux-pro-max/SKILL.md`) - puente local al motor clonado en `external/ui-ux-pro-max-skill`

## Stack
- WordPress 6.x + **gano-child** (vitrina SOTA: PHP/CSS/JS; homepage sin depender de Elementor en prod)
- **Producción (2026-04-19):** Elementor y WooCommerce **no instalados** (retirados en ola hardening); no reinstalar sin decisión explícita
- Pagos / checkout cliente: **GoDaddy Reseller Store** (RCC + PLID/PFID); sin priorizar pasarelas locales fuera de ese ecosistema
- Seguridad: **gano-security.php** (MU, activo) + Wordfence (**instalado, inactivo** en prod hasta ventana acordada)
- SEO: **gano-seo.php** (MU, activo) + Rank Math (**instalado, inactivo** hasta cerrar copy)
- Tema: gano-child (Hello Elementor child)
- Animaciones: GSAP 3 + ScrollTrigger
- **Cursor WC3 (gano-child):** puntero personalizado; apagar con filtro `gano_enable_wc3_cursor` — ver [`memory/ops/gano-wc3-cursor-maintenance.md`](memory/ops/gano-wc3-cursor-maintenance.md) y `.github/copilot-instructions.md` (sección Cursor WC3).
- PHP 8.3 en GoDaddy Managed WordPress

## Reglas Universales (TODOS los agentes)

### HACER
- Leer `CLAUDE.md` y `.github/copilot-instructions.md` antes de cambios grandes
- Usar prefijo `gano_` en funciones PHP
- Sanitizar inputs y escapar outputs SIEMPRE
- Usar `$wpdb->prepare()` para SQL
- Seguir conventional commits
- Documentar decisiones en memory/sessions/ o .cursor/memory/
- Verificar CI (php-lint + TruffleHog) antes de merge

### NO HACER
- NO commitear credenciales, tokens, keys, passwords
- NO modificar wp-config.php
- NO eliminar plugins **activos** del runtime comercial (`gano-phase6-catalog`, `gano-phase7-activator`, `reseller-store`, `gano-reseller-enhancements`) sin decisión explícita; installers fase 1–3 ya fueron retirados del **servidor** en 2026-04-19 (ver `memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md`)
- NO usar Tailwind CSS (solo Vanilla CSS + variables)
- NO usar jQuery para codigo nuevo (Vanilla JS)
- NO ejecutar comandos destructivos sin permiso explicito
- NO pushear a main directamente — siempre via PR

## Coordinacion Multi-Agente

### Fuentes de verdad
1. `TASKS.md` — Estado del proyecto
2. `CLAUDE.md` — Contexto completo
3. `.github/DEV-COORDINATION.md` — Brujula operativa
4. `.github/copilot-instructions.md` — Instrucciones Copilot
5. `.cursor/memory/activeContext.md` — Contexto actual Cursor
6. `memory/ops/homepage-vitrina-launch-plan-2026-04.md` — Plan vitrina gano.digital: fases, RACI (Cursor / Copilot / Claude / humano), enlaces a copy y Fase 4 Reseller

### Biblioteca local Gano-Wiki (fuera de git)

- **Ruta:** `C:\Users\diego\OneDrive\Documentos\Gano-Wiki\` (OneDrive; **no** es el repo `Pilot`).
- **Para qué sirve:** conocimiento curado (playbooks, troubleshooting, ángulos de contenido, design system exportado, snapshots), consumo por IAs y producción de contenido.
- **No sustituye** `TASKS.md` ni `.cursor/memory/` para desarrollo activo. Jerarquía ante conflicto: `CLAUDE.md` > `copilot-instructions.md` > este `AGENTS.md` > `Gano-Wiki/AGENTS.md`.
- **Entrada rápida:** `Gano-Wiki/STATUS-AND-ROADMAP.md` (hecho/pendiente), `Gano-Wiki/skills/super-skill/gano-digital-master.md` (contexto operativo narrativo).
- **Regla:** no commitear el wiki al repo sin aprobación explícita (puede contener copias de material sensible indexado aparte).

### Flujo de trabajo
```
Issue en GitHub → Copilot crea PR (draft) → CI verifica → Cursor/Diego revisa → Merge
```

### Estado operativo SOTA (abril 2026)

- **Repo:** integración SOTA activa en `gano-child` (tokens, templates y catálogo canónico).
- **Producción (2026-04-19):** **8 archivos críticos** alineados con `main` (MD5 verificado tras SCP); home canónica `/`; menú Inicio corregido; política bots balanceada; `gano_pfid_*` en `wp_options` (validar RCC); plugins inactivos de riesgo/eliminables retirados.
- **Catálogo canónico actual:** mantener como referencia implementada en `functions.php` + `templates/shop-premium.php` (no reintroducir catálogos alternos sin decisión explícita de Diego).
- **Regla de agentes:** cualquier cambio comercial debe conservar estados `active|pending|coming-soon` y no inventar PFIDs; validar contra RCC antes de publicar IDs nuevos.

**Handoff actualizado:** `memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md` · `memory/claude/dispatch-status-2026-04-19.md` · GitHub [#263](https://github.com/Gano-digital/Pilot/issues/263)

### Resolucion de conflictos
- Si dos agentes tocan el mismo archivo: el mas reciente en main gana
- Si hay conflicto de directivas: `CLAUDE.md` > `copilot-instructions.md` > `AGENTS.md`
- Si hay duda: PARAR y preguntar a Diego

## Seguridad
- Archivos sensibles: `.env`, `wp-config.php`, `*.key`, `*.pem`, `credentials.*`
- CI obligatorio: `secret-scan.yml` (TruffleHog) + `php-lint-gano.yml`
- Rate limiting: 20 req/IP/60s en REST endpoints
- Checkout Reseller: probar en entorno permitido por GoDaddy (staging/RCC); no asumir credenciales de terceros

## Narrativa de Marca
- Tono: "Manifiesto Tecnico" (autoritario, visionario, sofisticado)
- Nomenclatura: Nucleo Prime, Fortaleza Delta, Bastion SOTA
- NUNCA usar: "barato", "hosting compartido", "low cost"
- Foco: inversion en soberania digital

## Cursor Cloud specific instructions

### What can be developed/tested locally

This repo is a **partial WordPress installation** (customizations only — no WP core files). A full WordPress runtime (PHP + MySQL + Apache) is **not** set up in the Cloud VM. What **can** be run:

| Area | Command | Notes |
|------|---------|-------|
| **PHP syntax lint** | `find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;` and same for `wp-content/plugins/gano-*` | Mirrors CI workflow `php-lint-gano.yml`. All custom PHP must pass. |
| **GSD tests** | `cd .gsd && npm test` | Node.js ≥20. 1483/1504 pass; 21 failures are pre-existing in the submodule. |
| **GSD SDK tests** | `cd .gsd/sdk && npx vitest run` | 677/690 pass; 8 failures are pre-existing (MODULE_NOT_FOUND in gsd-tools integration). |
| **Python PDF reports** | `python3 scripts/generate_project_status_pdf.py` (and other `scripts/generate_*.py`) | Requires `fpdf2` + `fonts-dejavu`. Output in `reports/`. |
| **Ops Hub dashboard** | `python3 scripts/generate_gano_ops_progress.py && cd tools/gano-ops-hub/public && python3 -m http.server 8765` | Static dashboard at http://127.0.0.1:8765/. |

### System dependencies (installed via apt, not in update script)

- `php8.3-cli php8.3-common php8.3-xml php8.3-mbstring php8.3-curl` — for `php -l` linting
- `fonts-dejavu` — required by `fpdf2` PDF generation scripts (they look for `DejaVuSans-Oblique.ttf`)

### Gotchas

- The `reports/` directory is gitignored; PDF output is local-only.
- `wp-config.php` is never in the repo; only `wp-config-sample.php` exists.
- GSD submodule (`.gsd/`) has its own lockfiles; run `npm install` in both `.gsd/` and `.gsd/sdk/` separately.
- The Ops Hub needs `progress.json` regenerated before serving: `python3 scripts/generate_gano_ops_progress.py`.
- No pre-commit hooks or lint-staged config exist in this repo; CI enforcement happens via GitHub Actions.
