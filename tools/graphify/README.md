# Graphify (vendor) — integración segura para Gano Digital

Este repo incluye un clone de referencia en `vendor/graphify` (MIT) para poder generar un knowledge graph local y acelerar auditorías/UX refactors, **sin** instalar hooks ni modificar el sitio WordPress.

## Uso (Windows / PowerShell)

```powershell
python -m venv .venv-graphify
.\.venv-graphify\Scripts\python -m pip install -U pip
.\.venv-graphify\Scripts\python -m pip install -e .\vendor\graphify

# ejemplo: Constellation
.\.venv-graphify\Scripts\python -m graphify .\memory\constellation --no-viz
```

## Guardrails

- No ejecutar `graphify hook install`.
- No correr sobre archivos sensibles (`.env`, `wp-config.php`, etc.).
- Mantener esto como tooling local; no es dependencia runtime del sitio.

## Skill relacionada

Ver `.gano-skills/gano-graphify-local/SKILL.md`.

