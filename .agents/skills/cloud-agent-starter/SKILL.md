# Skill: Cloud Agent Starter (Run + Test)

## Metadata
- **Name**: cloud-agent-starter
- **Description**: Starter runbook for Cloud agents to boot fast, execute common workflows, and test safely across this WordPress + Reseller repo.
- **Scope**: Entire workspace (`/workspace`)
- **Use when**: A Cloud agent starts work and needs practical setup, login checks, execution commands, feature-flag/mock strategy, and area-specific testing.

## 1) Bootstrap en 5 minutos

1. Confirmar rama y estado:
   - `git status -sb`
2. Confirmar acceso GitHub CLI:
   - `gh auth status`
3. Inicializar submódulos si falta `.gsd`:
   - `git submodule update --init --recursive`
4. Elegir modo de trabajo:
   - **Repo-only**: lint/tests/documentación sin tocar servidor.
   - **Staging/Prod**: validación real vía workflows 04/05/12 y pruebas manuales en sitio.
5. Regla de seguridad:
   - Nunca leer/editar secretos (`.env*`, `wp-config.php`, keys privadas).

## 2) Login y accesos que necesitas validar primero

### GitHub (siempre)
- `gh auth status` debe reportar sesión activa.
- `gh run list --limit 5` para contexto de CI reciente.

### WordPress/servidor (cuando el cambio requiere deploy)
- La operación remota depende de secrets en Actions:
  - `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`.
- Si faltan, trabaja en modo **repo-only**, deja bloqueo explícito y no inventes validación de servidor.

### GoDaddy Reseller / RCC (cuando hay cambios comerciales)
- Acceso RCC es humano (cuenta real). Como agente:
  - Usa placeholders `PENDING_RCC` para evitar checkout falso.
  - Pide PFIDs reales solo cuando el issue exija habilitar compra real.

## 3) Áreas del código + cómo correr y probar

### A. Tema y frontend (`wp-content/themes/gano-child/`)

**Ejecución práctica**
- No hay app local completa obligatoria en este repo; el flujo normal es editar + validar + desplegar a staging/prod.
- Si solo necesitas vista rápida de artefactos estáticos (Ops Hub):
  `python -m http.server 8765` dentro de `tools/gano-ops-hub/`.

**Testing mínimo recomendado**
1. Lint PHP en archivos tocados:
   - `php -l <archivo.php>`
2. Verificar que CI cubra rutas custom:
   - Workflow `01 · CI · Sintaxis PHP (Gano)`
   - Workflow `02 · CI · Escaneo secretos (TruffleHog)`
3. Si hubo cambio visual/comercial: probar en staging antes de producción.

### B. MU plugins globales (`wp-content/mu-plugins/`)

**Ejecución práctica**
- Cambios aquí impactan todo el sitio (seguridad/SEO).
- Deploy estándar por workflow `04 · Deploy · Producción (rsync)` (ideal primero a staging si aplica).

**Testing mínimo recomendado**
1. `php -l` en cada MU plugin tocado.
2. Revisar CI 01 + 02 en el commit.
3. Si toca hardening o parches de servidor:
   - Ejecutar `05 · Ops · Verificar parches en servidor` con `upload_missing=false` (dry-run).
   - Solo usar `upload_missing=true` si la comparación confirma desalineación.

### C. Comercio Reseller (`shop-premium.php`, `page-ecosistemas.php`, `functions.php`, `gano-reseller-enhancements`)

**Feature flags / mock strategy**
- **Flag principal de mock:** constantes `GANO_PFID_* = 'PENDING_RCC'`.
  - Resultado esperado: CTAs quedan en estado pendiente, sin checkout real.
- **Modo real:** PFIDs válidos + PLID configurado en Reseller Store.

**Testing workflow (mock seguro)**
1. Verificar que el producto use `GANO_PFID_*` y `gano_rstore_cart_url()`.
2. Confirmar que `PENDING_RCC` no rompe render ni produce links de compra reales.
3. `php -l` en archivos PHP tocados.

**Testing workflow (staging con PFIDs reales)**
1. Abrir `/shop-premium` o `/ecosistemas` en staging.
2. Clic en CTA de compra.
3. Confirmar redirección a carrito marca blanca GoDaddy (`cart.secureserver.net`).
4. Validar producto/precio/moneda COP.
5. Confirmar visibilidad en RCC (si el alcance incluye pedido completo).

### D. Workflows y operación GitHub (`.github/workflows/`)

**Ejecución práctica**
- Workflows clave:
  - `01` PHP lint
  - `02` secret scan
  - `04` deploy
  - `05` verify patches (`upload_missing` dry-run/acción)
  - `12` remove wp-file-manager (`force_remove=false` dry-run recomendado primero)

**Testing mínimo recomendado**
1. Validar sintaxis YAML y paths en diff.
2. Tras push, revisar estado de runs con:
   - `gh run list --limit 10`
3. Si cambiaste lógica de `05` o `12`, documenta prueba con input usado (`false`/`true`) y resultado esperado.

### E. Tooling Node (`.gsd/`, `.gsd/sdk/`)

**Ejecución práctica**
- Requiere Node >= 20.
- Comandos típicos:
  - `.gsd`: `npm test`
  - `.gsd/sdk`: `npm test`, `npm run test:unit`, `npm run test:integration`

**Testing mínimo recomendado**
1. Ejecutar solo suite del área tocada (`.gsd` o `.gsd/sdk`).
2. Si hubo cambios de dependencias/tooling, correr también auditoría del paquete afectado.

## 4) Toggles y dry-runs que debes usar por defecto

- `PENDING_RCC`: mantiene checkout desactivado hasta PFIDs reales.
- Workflow `05` con `upload_missing=false`: compara sin subir archivos.
- Workflow `12` con `force_remove=false`: verifica sin borrar plugin.
- Meta `_gano_coming_soon=1`: mantener páginas en borrador/coming soon cuando aplique rollout gradual.

## 5) Qué hacer si no puedes “arrancar todo”

Si no hay acceso a wp-admin/RCC/secrets:
1. Completa validación **repo-only** (lint/tests/CI paths).
2. Deja checklist de bloqueos concretos (qué acceso falta + qué prueba queda pendiente).
3. No inventes evidencia de staging/producción.

## 6) Cómo actualizar esta skill (muy corto)

Cuando descubras un truco nuevo de run/test:
1. Añade una línea en la sección del área correcta con:
   - **Comando exacto**
   - **Cuándo usarlo**
   - **Señal de éxito/fallo**
2. Si reemplaza un paso viejo, elimina el anterior en el mismo commit.
3. Mantén esta skill mínima: pasos accionables, sin teoría larga.

---
**Status**: ACTIVE
**Last Updated**: 2026-04-11
