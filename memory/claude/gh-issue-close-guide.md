# Cerrar issues en GitHub tras trabajo en `main`

Guía para **humanos** con permisos en `Gano-digital/Pilot`. Los agentes en el repo preparan texto; el cierre y la verificación de estado en GitHub los hace quien tenga acceso.

## Antes de cerrar

1. Buscar el issue abierto (por número o por etiqueta).
2. Confirmar en `main` que el cambio existe (diff, PR fusionado, o ruta citada en el issue).
3. Si el issue mezcla “solo servidor” y “repo”, cerrar solo el parte **repo** si aplica; dejar checklist en comentario para el resto.

## Plantillas de comentario (copiar/pegar)

### Genérico — resuelto en código

```text
Cerrado: el trabajo solicitado ya está integrado en `main` (fecha: YYYY-MM-DD).
Archivos / PRs relevantes: …
Si aún falta algo en producción, depende de deploy / wp-admin (ver issue o TASKS.md).
```

### Etiqueta `homepage-fixplan` / Elementor

```text
Cerrado en repo: … (si aplica).
Pendiente en producción: ajustes en Elementor/wp-admin — ver `TASKS.md` § Contenido homepage.
```

### Oleada 3 / marca / copy en `memory/content/`

```text
Entregables en `main` bajo `memory/content/` o `memory/research/`. Cierre por consolidación; revisar aplicación en sitio si el issue lo pedía explícitamente.
```

### `theme` / `gano-child`

```text
Cerrado: cambios del tema ya están en `main` (fecha: YYYY-MM-DD).
Verificación recomendada antes de cerrar: revisar diff del archivo tocado en `wp-content/themes/gano-child/` y PR relacionado.
Si falta reflejarse en producción, depende de deploy y limpieza de caché.
```

### `coordination` / sync

```text
Estado sincronizado en git. Deploy/validación en servidor: seguir `DEV-COORDINATION.md` o checklist del issue.
```

## Búsqueda útil en GitHub

- Issues abiertos con label `copilot` o `agent-dispatch` (según política del repo).
- Issues por categoría para cierre por lote: `homepage-fixplan`, `wave3`, `theme`, `coordination`.
- Filtro por texto: `agent-task-id:tu-id` en el cuerpo (si el issue viene de la cola **08**).
- Validar cierre contra `main`: abrir issue, ubicar archivos/PR citados y confirmar que el diff ya está fusionado antes de comentar y cerrar.
