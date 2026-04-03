# Auditoría `:root` y `--gano-gold` — gano-child `style.css`

**Fecha:** 2026-04-03 (sesión nocturna, Cursor).

## Hallazgos

1. **Primer bloque `:root` (~línea 28):** tokens de marca globales, incluye `--gano-gold` y variantes.
2. **Bloque intermedio (~líneas 100+):** reasignación de `--gano-gold` en contexto de utilidades (documentado en comentarios como acento SOTA).
3. **Segundo bloque `:root` (~línea 910):** comentario indica variables shop; tokens de oro alineados al `:root` global.

## Conclusión

No se aplicó consolidación forzada en esta sesión: el archivo es grande y el riesgo visual es alto. Los duplicados de `--gano-gold` parecen **intencionales** (shop vs global). Próximo paso opcional: issue único “refactor tokens” con prueba visual en staging.

## Referencia

- `memory/content/visual-tokens-wave3.md` si se amplía política de tokens.
