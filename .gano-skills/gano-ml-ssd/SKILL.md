---
name: gano-ml-ssd
description: Integrar y usar apple/ml-ssd (Simple Self-Distillation) como recurso de I+D para evaluación de codegen y experimentos internos (sin afectar el runtime WordPress).
---

# Skill — ML-SSD (Apple) como recurso integral de I+D

Upstream: [apple/ml-ssd](https://github.com/apple/ml-ssd.git)

**Qué es:** implementación de *Simple Self-Distillation (SSD)* para mejorar code generation (paper arXiv 2604.01193).

**Cómo lo usamos en Gano Digital:** como **recurso integral de procesos**, no como dependencia de producción:
- evaluar configuraciones de muestreo/decoding para agentes
- reproducir benchmarks internos (cuando tengamos corpora/tareas) y registrar hallazgos en `memory/research/`
- mantener una base reproducible para experimentos (sin “magia” ni cambios no auditados)

---

## Integración en el repo

Se incluye como **git submodule** en:

- `vendor/ml-ssd`

Esto permite actualizar/volver atrás sin copiar el repo completo dentro del historial.

---

## Guardrails (obligatorio)

- **No** es runtime del sitio WordPress: nada de esto se despliega a producción.
- **No** correr training pesado por defecto. Priorizar *evaluation* y experimentos pequeños.
- **No** meter datasets privados/secretos dentro del repo.
- La licencia del proyecto es **Apple (custom)**: respetar su `LICENSE` y evitar reutilizar branding.

---

## Uso rápido (local)

El upstream recomienda `uv`. En Windows, lo más estable suele ser **WSL2** para toolchains Python.

Ejemplo (conceptual, seguir README upstream para comandos exactos):

```bash
cd vendor/ml-ssd
uv sync --group evaluation
source .venv/bin/activate
python evaluation/eval.py --help
```

---

## Cómo se “incorpora” a nuestras tareas

Cuando una tarea requiera **mejorar calidad de outputs de agentes** (ej. prompts/plantillas, sampling params, consistencia de patches), usar este recurso para:
- definir un pequeño set de prompts/tareas representativas
- correr evaluación controlada
- registrar resultados y parámetros ganadores en `memory/research/` (con fecha)

