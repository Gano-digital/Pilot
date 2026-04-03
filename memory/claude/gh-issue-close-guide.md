# Guía: cerrar issues en GitHub tras merge en `main`

**Quién cierra:** humano con permisos en `Gano-digital/Pilot`. Los agentes preparan PRs; el cierre del issue es decisión humana salvo automatización explícita.

## 1. Antes de cerrar

1. Buscar el issue en GitHub y localizar el marcador `<!-- agent-task-id:... -->` si existe.
2. En la rama `main`, confirmar que el cambio está fusionado (`git pull`, o revisar el PR vinculado).
3. Si el issue mezcla “solo Elementor” y “repo”, cerrar solo cuando **ambas** partes estén resueltas o dejar comentario de lo que queda en wp-admin.

## 2. Cómo buscar candidatos

- Filtros útiles: `is:open label:copilot`, `is:open label:homepage-fixplan`, `is:open label:coordination`.
- Revisar issues del rango **#17–#33** (oleada 1) y **#54–#68** (oleada 3) si siguen abiertos tras la consolidación 2026-04-03.

## 3. Plantillas de comentario (copiar / adaptar)

### Homepage / fixplan

```
Cerrando: el trabajo de este issue ya está integrado en `main` (fecha: YYYY-MM-DD).
Archivos / PR de referencia: #NNN
Queda acción manual en Elementor: [sí/no — detallar si aplica].
```

### Oleada 3 / marca / docs

```
Cerrando: entregables en `memory/content/` o `memory/research/` fusionados en `main` vía PR #NNN.
Si falta aplicación en Elementor, abrir issue `[sync]` con plantilla de coordinación.
```

### Tema / PHP

```
Cerrando: cambios en `gano-child` (o MU-plugins) en `main`. CI verde en el PR #NNN.
```

### Coordinación / sync

```
Cerrando: el drift descrito quedó alineado según comentario en PR #NNN; servidor verificado el YYYY-MM-DD.
```

## 4. Si **no** debes cerrar

- El issue pide algo que solo ocurre en producción y no hay evidencia en repo: deja comentario y etiqueta `coordination`.
- Duplicado: cierra con “Duplicate of #NN”.

## Referencias

- `.github/MERGE-PLAYBOOK.md`
- `memory/sessions/2026-04-03-consolidacion-prs-copilot.md`
