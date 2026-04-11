# Datasets — gano-gemma-finetune

## Formato

Cada línea de los `.jsonl` es un ejemplo conversacional con el chat template
de Gemma 3n. Usamos formato `messages` (compatible con Unsloth/TRL):

```json
{"messages": [
  {"role": "user", "content": "¿Qué es Gano Digital?"},
  {"role": "assistant", "content": "Gano Digital es un proveedor colombiano de hosting WordPress que opera dentro del ecosistema GoDaddy Reseller. Ofrece 4 planes de hosting con foco en soberanía digital, seguridad por defecto y rendimiento NVMe."}
]}
```

Opcional: añadir `system` como primer mensaje cuando el ejemplo enseñe
comportamiento o reglas duras.

## Archivos seed

| Archivo | Propósito | Target |
|---------|-----------|--------|
| `seed/01-voz-gano.jsonl` | Identidad, tono, español técnico-comercial | ~150 |
| `seed/02-paginas-sota.jsonl` | 3-5 Q&A por cada una de las 20 páginas SOTA | ~80 |
| `seed/03-tareas-operativas.jsonl` | Fases 1-7, plugins, Reseller, skills | ~200 |
| `seed/04-comportamiento.jsonl` | Reglas duras (no borrar plugins de fase, priorizar GoDaddy) | ~70 |
| **Total objetivo** | | **~500** |

## Cómo añadir ejemplos

1. Edita el `.jsonl` correspondiente (un JSON por línea, sin coma final).
2. Mantén respuestas **concisas y en el tono Diego/Gano** (español, directo,
   técnico, foco GoDaddy Reseller).
3. Evita inventar datos (NIT, teléfonos, precios) — déjalos como `<PLACEHOLDER>`
   o usa los valores reales si Diego ya los proveyó.
4. Corre `python ../scripts/build_dataset.py --validate` para verificar formato
   y deduplicar.

## Reglas de calidad

- ✅ Una sola idea por respuesta
- ✅ Usa terminología del proyecto: "fases", "plugins de fase", "Reseller",
  "ecosistemas", "MU plugin"
- ✅ Cuando aplique, referencia archivos reales (`CLAUDE.md`, `TASKS.md`)
- ❌ No mezclar idiomas
- ❌ No respuestas genéricas estilo ChatGPT ("¡Claro! Con gusto te ayudo...")
- ❌ No alucinar APIs o pasarelas que no estén en CLAUDE.md
