# AGENTS.md — Cross-Tool Agent Instructions

> Este archivo es leido por Cursor, Claude Code, GitHub Copilot, Codex, y cualquier herramienta AI que siga el estandar agents.md.

## Proyecto
**Gano Digital** — Plataforma de hosting WordPress para Colombia.
URL: https://gano.digital | Repo: Gano-digital/Pilot

## Stack
- WordPress 6.x + Elementor Pro + WooCommerce
- Pagos / checkout cliente: **GoDaddy Reseller Store** (COP); sin priorizar integraciones de pasarela fuera de ese ecosistema
- Seguridad: Wordfence + gano-security.php (MU-plugin)
- SEO: Rank Math + gano-seo.php (MU-plugin)
- Tema: gano-child (Hello Elementor child)
- Animaciones: GSAP 3 + ScrollTrigger
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
- NO eliminar plugins de fase (gano-phase*)
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

### Flujo de trabajo
```
Issue en GitHub → Copilot crea PR (draft) → CI verifica → Cursor/Diego revisa → Merge
```

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
