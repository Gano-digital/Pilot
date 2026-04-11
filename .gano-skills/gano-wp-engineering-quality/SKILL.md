# Skill: Calidad de ingeniería y tests en WordPress (Gano)

## Metadata

- **Name**: `gano-wp-engineering-quality`
- **Description**: Subir la “nota” de ingeniería del repo (tests automatizados, regresión) **sin** reescribir el stack WordPress/Elementor. Usar cuando el proyecto esté listo para ir más allá de `php -l` + TruffleHog + CodeQL.
- **Scope**: `wp-content/mu-plugins/`, `wp-content/themes/gano-child/`, plugins `gano-*` (código propio).
- **Cuándo NO usar**: antes de tener deploy estable y prioridades de producto (Reseller, contenido) razonablemente cubiertas; no sustituye CI existente.

## Activation signals

- “tests automáticos”, “PHPUnit”, “WP-CLI tests”, “E2E”, “subir cobertura”, “calidad de código más allá del lint”
- “auditoría de ingeniería pura”, “regresión en MU-plugins”

## Marco de referencia (dimensiones)

| Dimensión | Qué mide | Nota orientativa hoy en Pilot |
|-----------|----------|--------------------------------|
| Gobernanza / CI / docs | Alto | Ya fuerte |
| Tests automatizados (PHPUnit / E2E) | Bajo–medio | Hueco típico en WP |
| Código custom (patrones, seguridad) | Medio–alto | Reglas `gano_`, prepare, CSP, etc. |

Objetivo de la etapa: **acercar la fila “tests”** a la del resto, sin pretender un monolito tipo framework MVC.

## Estrategia (orden sugerido)

1. **Congelar alcance**: qué no se va a testear (plantillas Elementor, todo el core WP).
2. **MU-plugins primero**: funciones puras o con mocks mínimos (`gano-seo` helpers, utilidades sin UI).
3. **Seguridad / límites**: `gano-security` — pruebas de presencia de headers o filtros solo si el entorno de test lo permite; si no, checks **WP-CLI** en staging (scripts, no PHPUnit).
4. **Integración ligera**: comandos `wp eval` / scripts que validen opciones críticas tras deploy (smoke).
5. **E2E opcional** (Playwright/Cypress): solo flujos críticos (formulario, CTA a URL esperada); coste alto, usar con moderación.

## Entregables

- `phpunit.xml` o bootstrap bajo `tests/` (solo si se adopta PHPUnit).
- Workflow opcional en `.github/workflows/` que ejecute tests en paths acotados (no en todo `wp-content/`).
- Documentar en `TASKS.md` el ítem correspondiente como hecho o en progreso.

## Restricciones Gano

- Prefijo `gano_` en PHP nuevo; `$wpdb->prepare()`; no secretos en repo.
- No tocar `wp-config.php` desde tests commiteados con rutas reales.
- Alinear versión PHP de CI con hosting (ver `php-lint-gano.yml` y `AGENTS.md`).

## Referencias en repo

- CI actual: `.github/workflows/php-lint-gano.yml`, `secret-scan.yml`
- Prioridad en roadmap: `TASKS.md` sección *Etapa posterior — Tests y calidad de ingeniería*
