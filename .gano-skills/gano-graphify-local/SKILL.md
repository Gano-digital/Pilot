---
name: gano-graphify-local
description: Usar Graphify (vendor) como herramienta local para generar un knowledge-graph del repo (sin hooks), para acelerar auditoría y navegación del tool gráfico.
---

# Skill — Graphify local (modo seguro, sin hooks)

**Objetivo:** poder ejecutar Graphify sobre partes del repo (ej. `memory/constellation/`, `.github/`, `tools/`) para producir `graphify-out/` (graph.json + GRAPH_REPORT.md + graph.html) y usarlo como “mapa” de arquitectura durante tareas complejas.

**Dónde vive Graphify:** `vendor/graphify/` (MIT). Está **ignorado por git** en este repo; se usa como dependencia local opcional.

---

## Guardrails anti‑honeypot (obligatorio)

- **No** ejecutar `graphify hook install` (no instalar git hooks).
- **No** correr Graphify sobre `.env`, `wp-config.php` ni carpetas sensibles.
- Ejecutar solo en carpetas específicas (path allowlist):  
  `memory/constellation/`, `memory/research/`, `.github/`, `tools/gano-ops-hub/`, `.gano-skills/`, `scripts/`.
- **No** abrir listeners de red. Si se usa MCP, debe ser **stdio** (como está diseñado).

---

## Setup (una vez por máquina)

### Windows (PowerShell)

1) Crear venv local:

```powershell
python -m venv .venv-graphify
.\.venv-graphify\Scripts\python -m pip install -U pip
```

2) Instalar Graphify desde el vendor (editable) + dependencias:

```powershell
.\.venv-graphify\Scripts\python -m pip install -e .\vendor\graphify
```

> Nota: esto instala dependencias de parsing (tree-sitter, networkx). No toca el sitio WordPress: es tooling local.

---

## Ejecutar (uso típico)

### Constellation (tool gráfico)

```powershell
.\.venv-graphify\Scripts\python -m graphify .\memory\constellation --no-viz
```

### Repo (solo docs/ops)

```powershell
.\.venv-graphify\Scripts\python -m graphify .\.github
.\.venv-graphify\Scripts\python -m graphify .\tools\gano-ops-hub
```

Salida esperada: carpeta `graphify-out/` en el root del repo.

---

## Cómo se usa en tareas (patrón)

1) Ejecutar Graphify sobre el scope del trabajo.\n
2) Leer `graphify-out/GRAPH_REPORT.md` para “god nodes” y comunidades.\n
3) Solo después hacer Grep/Glob tradicionales.\n
4) Si cambió código, re‑ejecutar Graphify con `--update` para refrescar.

---

## Limpieza

- `graphify-out/` puede borrarse localmente si no se necesita.
- No commitear salidas a menos que Diego lo pida explícitamente.

