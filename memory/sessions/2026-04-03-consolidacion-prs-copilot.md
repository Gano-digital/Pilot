# Consolidación PRs Copilot — 2026-04-03

**Autorizado por Diego.** Objetivo: vaciar cola de PRs abiertos con squash merge y criterio MERGE-PLAYBOOK.

## Cerrados sin merge (duplicado u obsoleto)

| PR | Motivo |
|----|--------|
| #49 | README/deploy ya alineados en `main`; conflicto con base |
| #84 | WIP vacío; trabajo en #83 (a11y) |
| #85 | WIP; especificación OG en #82 |

## Fusionados (squash) — oleada técnica + SEO/MU + Reseller + wave3 docs

Orden aproximado: #34 → #40 → #41 → #45 → #35–#39 → #42–#44 → #46 → #48 → #50 → #53 → #70–#82 → #83 → #52 (tras merge manual) → #76 (tras merge manual).

- **#52:** conflicto en `shop-premium.php` entre `rstore_id` (rama agente) y `GANO_PFID_*` + `gano_rstore_cart_url()` (`main` post-#46). Resolución: mantener modelo **pfid** de `main`, precios **1.200.000** / **1.800.000** COP.
- **#76:** conflicto en `style.css` entre utilidades tipográficas wave3 y bloque **a11y** (#83). Resolución: **ambos** bloques en el mismo archivo (tipografía primero, a11y después).

## Estado final

- **PRs abiertos:** 0 (lista GitHub al cierre de sesión).
- **`main`:** incluye smoke test reseller, visual-tokens-wave3, y catálogo shop alineado.

## Pendiente humano (no bloquea repo)

- RCC: reemplazar `PENDING_RCC` en `functions.php` por pfids reales.
- Servidor: deploy + verificar parches (`TASKS.md`).
- Revisar en Elementor: skip link `#gano-main-content` en canvas si aplica.
