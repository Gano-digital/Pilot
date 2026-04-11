# Skill: Cloud Agent Starter — Run & Test Gano Digital

## Metadata
- **Name**: cloud-agent-starter
- **Description**: Guía mínima para que un Cloud agent arranque rápido en este repo: login, setup, ejecución, flags operativos y testing por área.
- **Scope**: Workspace completo (`/workspace`)
- **Use when**: Primera sesión en el repo, handoff entre agentes, o cuando necesites confirmar “cómo correr y probar” sin perder tiempo.

---

## 0) Preflight (5 minutos)

Ejecuta esto antes de tocar código:

1. Confirmar herramientas y sesión:
   - `git status -sb`
   - `gh auth status`
   - `php -v`
   - `python3 --version`
   - `node -v && npm -v`
2. Confirmar regla de secretos:
   - **Nunca** leer ni commitear `.env*`, `wp-config.php`, `*.pem`, `*.key`, `credentials.*`.
3. Confirmar flujo comercial activo:
   - Checkout cliente = **GoDaddy Reseller Store + RCC** (no inventar pasarelas fuera del ecosistema actual).

---

## 1) Área WordPress runtime (tema, mu-plugins, plugins `gano-*`)

### Archivos clave
- `wp-content/themes/gano-child/`
- `wp-content/mu-plugins/`
- `wp-content/plugins/gano-*/`

### Login y arranque
1. Si vas a correr WordPress local:
   - Crea `wp-config.php` desde `wp-config-sample.php` **solo en local** (nunca en git).
   - Configura credenciales de DB en tu entorno local privado.
   - Inicia tu stack WP local (Laragon/LocalWP o equivalente).
2. Si no hay stack local disponible:
   - Trabaja con validaciones estáticas + workflows de GitHub + staging documentado.
3. Si necesitas shell remoto al hosting:
   - Usa `ssh_cli.py` con variables de entorno `GANO_SSH_*` (nunca hardcodees credenciales en archivos).

### Workflow de testing (rápido y útil)
1. **Lint PHP del código propio** (mismo alcance que CI):
   - `find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;`
   - `find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -exec find {} -name '*.php' -exec php -l {} \; \;`
2. **(Opcional) Escaneo de secretos en rutas Gano**:
   - Usa el workflow `02 · CI · Escaneo secretos (TruffleHog)` en GitHub Actions.
3. **Validación mínima funcional**:
   - Si tienes app levantada: abrir homepage + `/wp-admin` sin errores fatales.

### Criterio de pase
- Sin errores de `php -l` en rutas Gano.
- Sin secretos en diff.
- Sin fatal error visible al cargar frontend/wp-admin.

---

## 2) Área Commerce Reseller (Phase 4)

### Archivos clave
- `wp-content/plugins/gano-reseller-enhancements/`
- `wp-content/themes/gano-child/templates/shop-premium.php`
- `memory/commerce/rcc-pfid-checklist.md`

### Login y setup
1. Login en WordPress admin (`/wp-admin`) con usuario de entorno (staging o local).
2. Verifica que plugin `gano-reseller-enhancements` esté activo.
3. Abre `Herramientas > Smoke Test Reseller`.

### Feature flags / mocks operativos
1. **Sandbox Reseller (test-godaddy.com)**:
   - Flag persistente: opción WP `gano_reseller_sandbox`.
   - Toggle por UI en “Smoke Test Reseller”.
   - Toggle por CLI (si WP-CLI disponible):  
     `wp option update gano_reseller_sandbox 1` (activar)  
     `wp option update gano_reseller_sandbox 0` (desactivar)
2. **Flag de contenido coming soon**:
   - Meta key: `_gano_coming_soon` (evita publicar ciertas páginas en phase activators).

### Workflow de testing (concreto)
1. Activa sandbox.
2. En `Herramientas > Smoke Test Reseller`, valida:
   - `pl_id` configurado.
   - URL de checkout generada.
   - URLs de bundle (`gano_add_bundle`) redireccionan.
3. Haz smoke manual CTA → carrito reseller.
4. Registra resultado en `memory/commerce/rcc-pfid-checklist.md` o issue relacionado.
5. Desactiva sandbox al terminar si el entorno no es de pruebas.

### Criterio de pase
- Smoke test admin sin checks críticos en rojo.
- CTA abre carrito reseller correcto.
- Sin dejar sandbox encendido por accidente en producción.

---

## 3) Área GitHub workflows + cola de agentes

### Archivos clave
- `.github/workflows/*.yml`
- `.github/agent-queue/tasks*.json`
- `scripts/validate_agent_queue.py`

### Login y setup
1. Verifica sesión GitHub CLI: `gh auth status`.
2. Revisa workflows disponibles en repo (UI Actions o `gh workflow list`).

### Workflow de testing por área
1. Si cambias colas de agentes:
   - `python3 scripts/validate_agent_queue.py`
2. Si cambias docs operativas de workflow:
   - Verifica nombres y alcance contra `.github/workflows/README.md`.
3. Si cambias deploy/ops:
   - Confirma que no se filtren secretos.
   - Recuerda secretos requeridos en GitHub para 04/05/12: `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`.

### Criterio de pase
- Validador de cola JSON en verde.
- YAML consistente con nombres/documentación.
- Ningún secreto añadido al repo.

---

## 4) Área tooling `.gsd` (Node)

### Archivos clave
- `.gsd/package.json`
- `.gsd/sdk/package.json`

### Setup rápido
1. Instalar deps donde toque:
   - `cd .gsd && npm install`
   - `cd .gsd/sdk && npm install`

### Workflow de testing
1. En `.gsd`:
   - `npm test`
2. En `.gsd/sdk`:
   - `npm test`
   - Opcional por señal: `npm run test:unit`, `npm run test:integration`

### Criterio de pase
- Tests del submódulo/SDK pasan en el área tocada.
- No ejecutar suites globales innecesarias del repo completo.

---

## 5) Secuencia recomendada “primer día” (orden sugerido)

1. Preflight de herramientas + auth.
2. Corre validaciones estáticas del área que vas a tocar.
3. Si es commerce: habilita sandbox y corre smoke test reseller.
4. Si es CI/agent queue: valida JSON antes de PR.
5. Documenta hallazgos operativos nuevos (ver sección de mantenimiento).

---

## 6) Cómo mantener esta skill viva (runbook learning loop)

Cuando descubras un truco nuevo de ejecución/testing:

1. **Actualiza esta skill** con:
   - Comando exacto.
   - Cuándo usarlo.
   - Señal de éxito/fallo.
2. **Guarda evidencia corta** en `memory/sessions/` (qué problema resolvió).
3. Si afecta prioridades de proyecto, añade nota en `TASKS.md`.
4. Mantén cambios mínimos y verificables (evitar texto genérico).

Plantilla breve para agregar conocimiento:
- **Nuevo tip**: `<comando o paso>`
- **Se usa cuando**: `<síntoma>`
- **Éxito esperado**: `<salida/resultado>`
- **Fuente**: `<sesión/issue/PR>`

---

## Guardrails rápidos

- No usar este skill para justificar cambios fuera de scope.
- No asumir acceso a producción: valida en staging o entorno permitido.
- No hardcodear PFIDs/credenciales.
- Si algo depende de secreto faltante, reporta bloqueo con comando y error exacto.
