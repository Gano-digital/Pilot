# Skill: Cloud agent — Run & test (Gano Digital Pilot)

## Metadata

- **Name**: `cloud-runbook`
- **Description**: Arranque mínimo para agentes Cloud: qué existe en el repo, cómo validar cambios sin WordPress completo, y cómo alinear pruebas con CI. Usar al entrar al workspace o antes de tocar varias áreas (PHP, scripts, Ops Hub, GSD).
- **Scope**: Raíz del repo `Pilot` (WordPress + tooling auxiliar).
- **No cubre**: Credenciales reales, `wp-config.php`, SSH al servidor (solo referencia a workflows que los usan).

## Activation signals

Cargar cuando el prompt mencione: *Cloud agent*, *CI local*, *cómo probar*, *php -l*, *staging*, *Reseller sandbox*, *validar cola*, *Ops Hub*, *submodule .gsd*.

---

## 1. Panorama rápido

| Área | Qué es | ¿“Arranca app” aquí? |
|------|--------|----------------------|
| `wp-content/` | Tema `gano-child`, MU-plugins `gano-*.php`, plugins `gano-*` + vendor WP | Requiere **WordPress + DB** en un host local (p. ej. Laragon) — el repo no trae `docker-compose`. |
| `.github/workflows/` | CI: PHP lint, TruffleHog, validación JSON cola agentes | Replicable en shell en el runner/agente. |
| `scripts/*.py` | Utilidades (colas, PDFs, ops progress) | `python3` estándar, sin `requirements.txt` en raíz. |
| `.gsd/` | Submodule *get-shit-done* + SDK | Tests Node **solo** si tocas código bajo `.gsd/`. |
| `tools/gano-ops-hub/` | Dashboard estático + generador | Servidor HTTP local para probar `fetch()`. |

**Login WordPress (humano o entorno con WP):** `wp-admin` con usuario del entorno. Los agentes en **solo-repo** no tienen sesión: validan sintaxis, grep y scripts; E2E Reseller requiere WP activo + permisos `manage_options` para *Herramientas → Smoke Test Reseller*.

---

## 2. Setup común (primera vez en el clone)

```bash
git submodule update --init --recursive   # trae .gsd
```

- **WordPress local:** seguir `README.md` § *Setup Local (Laragon)*: copiar `wp-config-sample.php` → `wp-config.php` **fuera de git**, definir DB. Los agentes **no** commitean `wp-config` ni leen `.env`.
- **PHP:** CI usa **8.2** para lint (`.github/workflows/php-lint-gano.yml`). Producción documentada 8.3 en `AGENTS.md`; usar 8.2+ para `-l`.
- **Python:** `python3` para scripts en `scripts/`.
- **Node:** solo necesario bajo `.gsd/` o `.gsd/sdk/` (engines ≥ 20).

---

## 3. Por área: ejecutar y probar

### 3.1 WordPress — tema child, MU-plugins, plugins `gano-*`

**Objetivo:** cambios en PHP propio sin levantar WP completo.

**Paridad con CI (recomendado en cada cambio):**

```bash
set -euo pipefail
find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
while IFS= read -r -d '' dir; do
  find "$dir" -name '*.php' -exec php -l {} \;
done < <(find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 2>/dev/null)
```

**Con WP levantado:** activar plugins de fase según `README.md` / `CLAUDE.md` (orden y reglas de no borrar `gano-phase*`).

### 3.2 Comercio / GoDaddy Reseller (`gano-reseller-enhancements`, CTAs, PFID)

**“Feature flags” / entornos:**

| Mecanismo | Efecto |
|-----------|--------|
| `GANO_RESELLER_SANDBOX` = true en `wp-config` (local) | Fuerza TLD OTE `test-godaddy.com` vía `Gano_Reseller_Sandbox` |
| Opción WP `gano_reseller_sandbox` | Igual; se puede togglear desde **wp-admin → Herramientas → Smoke Test Reseller** (formulario del plugin) |
| Constantes `GANO_PFID_*` en `gano-child/functions.php` | Mientras valgan `PENDING_RCC`, el flujo comercial está **pendiente** de RCC — no asumir checkout real |

**Flujo de prueba manual (requiere WP + plugin Reseller Store + credenciales del entorno):**

1. Activar sandbox (opción o constante).
2. *Herramientas → Smoke Test Reseller*: revisar checks (`pl_id`, plugin activo, enlaces al carrito).
3. Desde plantilla con CTA (p. ej. `shop-premium.php`): clic → carrito en dominio sandbox.

**Skill detallada Fase 4:** `.agents/skills/phase4-commerce/SKILL.md`.

### 3.3 CI / secretos (TruffleHog)

Paridad aproximada con workflow `02 · CI · Escaneo secretos`:

```bash
shopt -s nullglob
for target in wp-content/mu-plugins wp-content/themes/gano-child wp-content/plugins/gano-*; do
  echo "=== $target ==="
  docker run --rm -v "$PWD:/pwd" -w /pwd \
    trufflesecurity/trufflehog:latest filesystem "/pwd/$target" --only-verified
done
```

Requiere Docker. Si no hay Docker, al menos `php -l` + revisión humana de diffs sin secretos.

### 3.4 Cola de agentes / JSON (`.github/agent-queue/`)

```bash
python3 scripts/validate_agent_queue.py
```

Disparo en CI: workflow *07 · Agentes · Validar cola JSON*.

### 3.5 Claude dispatch (`memory/claude/dispatch-queue.json`)

Valida estructura mínima (`version`, `tasks`, claves por tarea):

```bash
python3 scripts/validate_claude_dispatch.py
```

Más contexto: `memory/claude/README.md` y tareas VS Code en `.vscode/tasks.json`.

### 3.6 Ops Hub (`tools/gano-ops-hub/`)

```bash
python3 scripts/generate_gano_ops_progress.py
cd tools/gano-ops-hub/public && python3 -m http.server 8765
```

Abrir `http://127.0.0.1:8765/` (no uses `file://` — el JSON se carga con `fetch()`).

### 3.7 Submodule `.gsd` (get-shit-done / SDK)

Solo si modificas código ahí:

```bash
cd .gsd && npm ci && npm test
# SDK tipado:
cd .gsd/sdk && npm ci && npm test
```

### 3.8 Scripts PDF / reportes (`scripts/generate_*.py`)

Requieren dependencias opcionales (p. ej. `fpdf2` mencionado en `CLAUDE.md` para algunos PDFs). Probar con:

```bash
python3 scripts/<script>.py --help 2>/dev/null || python3 scripts/<script>.py
```

Si falla por módulo faltante, instalar solo lo que indique el doc del script o `CLAUDE.md` — **no** añadir dependencias globales al repo sin decisión del equipo.

### 3.9 HTML estático (`memory/constellation/`)

Servidor HTTP local en el directorio del archivo (mismas razones que Ops Hub). Ver `tools/gano-ops-hub/README.md` § *Constelación 3D*.

---

## 4. Workflows GitHub (cuando el agente no tiene servidor)

| Workflow | Utilidad |
|----------|----------|
| `01 · CI · Sintaxis PHP (Gano)` | Ya replicado arriba con `find` + `php -l` |
| `02 · CI · Escaneo secretos` | TruffleHog en paths propios |
| `05 · Ops · Verificar parches en servidor` | Comparación repo ↔ servidor — necesita secrets `SSH`, `SERVER_HOST`, etc. |
| `Test Runner` | Placeholder self-hosted (`echo "Runner OK"`) — no sustituye pruebas locales |

---

## 5. Cómo actualizar esta skill

1. **Tras descubrir un atajo verificado** (nuevo script, nuevo workflow, nueva constante de entorno): añadir una fila en la tabla del §3 correspondiente o un subapartado breve.
2. **Si cambia CI** (versiones PHP, paths escaneados): alinear los bloques de comando con el YAML en `.github/workflows/`.
3. **Si el flujo Reseller cambia** (nueva opción WP, nueva página admin): documentar solo el “qué ejecutar / dónde clic”; detalle comercial sigue en `phase4-commerce` o `memory/commerce/`.
4. **Fecha:** actualizar la línea *Last updated* al final del archivo.

Mantener esta skill **mínima**: detalle largo vive en `README.md`, `CLAUDE.md`, `TASKS.md`, `memory/ops/`, no duplicar párrafos enteros.

---

**Skill status:** ACTIVE  
**Last updated:** 2026-04-11
