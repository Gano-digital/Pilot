# ML-SSD (apple/ml-ssd) — integrado como submodule

Upstream: [apple/ml-ssd](https://github.com/apple/ml-ssd.git)

Este repo se incluye como **submodule** en `vendor/ml-ssd` para soportar experimentos reproducibles de I+D (evaluación de codegen), sin afectar el runtime del sitio WordPress.

## Ubicación

- `vendor/ml-ssd` (submodule)

## Actualizar submodule

```bash
git submodule update --init --recursive
git submodule foreach git status
```

## Guardrails

- No commitear datasets privados.
- No usar esto como dependencia de producción.
- Respetar la licencia Apple del upstream (ver `vendor/ml-ssd/LICENSE`).

## Skill

Ver `.gano-skills/gano-ml-ssd/SKILL.md`.

