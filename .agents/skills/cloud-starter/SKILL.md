# Skill: Cloud Starter Runbook (Gano Digital)

## Metadata
- **Name**: `cloud-starter`
- **Description**: Guía mínima para que agentes Cloud puedan arrancar rápido, ejecutar checks útiles y probar cambios por área del repo sin depender de contexto previo.
- **Scope**: `Gano-digital/Pilot`
- **Use when**: inicias sesión nueva de agente, tomas un issue desconocido, o necesitas validar cambios en tema/plugins/workflows.

## 0) Bootstrap inmediato (90 segundos)

Ejecuta esto al inicio de cada sesión:

1. Estado del repo:
   - `git status --short --branch`
2. Validar acceso GitHub CLI:
   - `gh auth status`
3. Verificar toolchain base:
   - `php -v`
   - `python3 --version`
   - `node -v`
4. Verificar submódulo GSD:
   - `git submodule status`

Si `gh auth status` falla, registra bloqueo y no intentes operaciones dependientes de GitHub API.

---

## 1) Login / accesos operativos

### 1.1 GitHub (obligatorio para trabajo de PR/issues)
- Comando de verificación: `gh auth status`
- Uso permitido típico para diagnóstico: `gh pr view`, `gh run list`, `gh run view --log`

### 1.2 Servidor WordPress por SSH (solo cuando el task lo requiera)
Variables esperadas (nunca en commit):
- `GANO_SSH_HOST`
- `GANO_SSH_USER`
- `GANO_SSH_PASS` **o** `GANO_SSH_KEY_PATH`
- `GANO_SSH_PORT` (opcional)

Smoke test SSH:
- `python3 ssh_cli.py "wp --version || echo wp-cli-no-disponible"`

Si faltan variables, marca el task como bloqueado por acceso y continúa con validaciones locales/CI.

### 1.3 wp-admin / Elementor
- En Cloud normalmente no hay sesión interactiva persistente en wp-admin.
- Si el cambio exige UI en Elementor, deja explícito que requiere validación manual en staging/producción por humano o sesión GUI dedicada.

---

## 2) Cómo “arrancar la app” en Cloud (según área)

### 2.1 App pública WordPress (ya desplegada externamente)
- Smoke rápido:
  - `curl -I https://gano.digital`

### 2.2 Ops Hub (UI local en repo)
1. Generar datos:
   - `python3 scripts/generate_gano_ops_progress.py`
2. Levantar servidor estático:
   - `cd tools/gano-ops-hub/public && python3 -m http.server 8765`
3. Verificar:
   - `curl -I http://127.0.0.1:8765/`

### 2.3 WordPress local completo
- No asumir stack local en Cloud por defecto.
- Si no hay entorno WP listo (DB/config), priorizar:
  - lint PHP,
  - pruebas de scripts,
  - smoke HTTP contra entorno ya activo.

---

## 3) Testing por área del código (workflows concretos)

## A. Tema child (`wp-content/themes/gano-child/`)
Úsalo cuando toques templates, `functions.php`, JS/CSS del tema.

Checks mínimos:
- `php -l wp-content/themes/gano-child/functions.php`
- Si tocas templates: `php -l wp-content/themes/gano-child/templates/<archivo>.php`

Smoke funcional recomendado:
- `curl -I https://gano.digital`
- Si tocaste checkout/CTA: revisar `templates/shop-premium.php` + helper `gano_rstore_cart_url` en `functions.php`.

## B. MU plugins (`wp-content/mu-plugins/`)
Úsalo para seguridad/SEO o cambios globales.

Checks mínimos:
- `php -l wp-content/mu-plugins/gano-security.php`
- `php -l wp-content/mu-plugins/gano-seo.php`

Smoke headers de seguridad:
- `curl -sSI https://gano.digital | rg -i "content-security-policy|x-frame-options|x-content-type-options|referrer-policy"`

## C. Plugins Gano (`wp-content/plugins/gano-*/`)
Útil para Reseller, fases, contenido.

Checks mínimos (ejemplo Reseller):
- `php -l wp-content/plugins/gano-reseller-enhancements/gano-reseller-enhancements.php`

Chequeo de integración comercial:
- validar que no se inventen PFIDs y que se respete `PENDING_RCC` hasta tener datos reales de RCC.

## D. Cola de agentes / automatización GitHub (`.github/agent-queue`, workflows, scripts)
Cuando toques issues seed/orquestación/agentes.

Checks mínimos:
- `python3 scripts/validate_agent_queue.py`

Si tocas documentación de workflow:
- revisar `.github/workflows/README.md` y mantener nomenclatura `NN · Área · Descripción`.

## E. Ops scripts / reporting (`scripts/`, `tools/gano-ops-hub/`)
Cuando cambies métricas o reportes.

Checks mínimos:
- `python3 scripts/generate_gano_ops_progress.py --stdout`

Smoke UI local (si tocaste Hub):
- `cd tools/gano-ops-hub/public && python3 -m http.server 8765`
- `curl -I http://127.0.0.1:8765/`

## F. Tooling Node (`.gsd/`, `.gsd/sdk/`)
Solo si tocas SDK/tooling.

Checks mínimos:
- `npm --prefix .gsd/sdk run test:unit`
- `npm --prefix .gsd/sdk run test:integration` (solo si cambias integración)

---

## 4) Feature flags / toggles y cómo mockear sin riesgo

## 4.1 Chat IA (tema child)
- Toggle real: constante `GANO_API_TOKEN`.
- Modo determinista (recomendado en Cloud): token ausente o placeholder (`TU_TOKEN_AQUÍ`) para forzar fallback estático.
- Regla: nunca commitear tokens reales.

## 4.2 Reseller checkout
- Toggle operativo: constantes `GANO_PFID_*`.
- Estado seguro por defecto: `PENDING_RCC` (no compra real).
- Mock seguro: validar que con `PENDING_RCC` el helper de carrito no dispare checkout real.

## 4.3 Workflows de GitHub con inputs (flags de ejecución)
- `deploy.yml` → `environment` (`production`/`staging`)
- `verify-patches.yml` → `upload_missing` (`true`/`false`)
- `seed-copilot-queue.yml` → `queue_file`, `scope`

Trátalos como flags de ejecución; documenta en PR qué valor usaste al probar.

---

## 5) Checklist mínimo de salida (Cloud)

Antes de cerrar una tarea técnica:
- [ ] Lint/check del área modificada en verde
- [ ] Smoke de ejecución (HTTP o script) en verde
- [ ] Sin secretos en diff (`git diff --cached`)
- [ ] Evidencia de prueba en comentario/PR (comando + resultado)

---

## 6) Cómo actualizar esta skill cuando aparezcan nuevos “trucos” de testing

Mantén esta skill viva con cambios pequeños:

1. Añade el nuevo tip en la sección del área correcta (A–F).
2. Incluye siempre:
   - comando exacto,
   - cuándo usarlo,
   - señal de éxito esperada.
3. Si el tip viene de incidente/runbook:
   - enlaza el documento en `memory/ops/` o `memory/sessions/`.
4. Actualiza al final:
   - `Last updated: YYYY-MM-DD`
   - `Source: PR #... / issue #... / runbook ...`

---

**Last updated:** 2026-04-11  
**Source:** creación inicial de skill starter para Cloud agents
