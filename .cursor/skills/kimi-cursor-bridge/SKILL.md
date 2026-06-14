# Kimi ↔ Cursor Bridge Skill

Permite usar **Kimi (Moonshot AI)** como agente complementario dentro del flujo de trabajo de Cursor. Este bridge documenta 3 modos de integración: modelo alternativo via OpenRouter, delegación por prompts, y sincronización de contexto.

---

## Modo 1: Kimi como Modelo Alternativo en Cursor (Recomendado)

Cursor soporta modelos externos via **OpenRouter**. Kimi K2.5 está disponible como `moonshotai/kimi-k2.5`.

### Setup (1 vez)

1. Crear cuenta en [openrouter.ai](https://openrouter.ai) y obtener API key.
2. Cursor → Settings (Ctrl+,) → Models → "Add model" → escribir `moonshotai/kimi-k2.5`.
3. O ir a "OpenAI API" → cambiar Base URL a `https://openrouter.ai/api/v1` → pegar API key de OpenRouter.
4. En la barra de chat de Cursor, seleccionar el modelo `moonshotai/kimi-k2.5` del dropdown.

### Cuándo usar Kimi vs Claude/GPT en Cursor

| Tarea | Modelo recomendado | Por qué |
|-------|-------------------|---------|
| Refactor masivo PHP/WP | **Kimi** | Contexto 256K tokens, maneja `functions.php` (2852 líneas) de una pasada |
| Efectos Canvas/WebGL | **Kimi** | Mejor generación de shaders y algoritmos proc. adaptados de assets locales |
| Debug de lógica comercial | **Kimi** | Razonamiento matemático fuerte, verificación de precios/bundles |
| Copy/marketing en español | **Kimi** | Optimizado para español latinoamericano, tono natural |
| A11y/SEO micro-fixes | Claude/Cursor | Más conservador, menos alucinaciones en specs técnicas |
| Git workflow complejo | Claude/Cursor | Mejor integración con GitHub Actions y rulesets |

---

## Modo 2: Delegación por Prompts (Copiar-pegar)

Cuando Cursor esté atascado o necesite contexto que Kimi ya tiene, usar estos prompts estandarizados para transferir trabajo entre agentes.

### Prompt: "Cursor → Kimi" (delegar a Kimi)

```markdown
## Contexto transferido desde Cursor
- **Proyecto:** Gano Digital — WordPress 6.x + tema child gano-child
- **Archivo objetivo:** `wp-content/themes/gano-child/PATH`
- **Tarea:** DESCRIPCIÓN
- **Bloqueante:** QUÉ ESTÁ ATAScANDO A CURSOR
- **Stack:** PHP 8.3, vanilla CSS/JS, GSAP 3, GoDaddy Reseller
- **Paleta:** SOTA dark — lavanda #c0c1ff, cian #4cd7f6, índigo #8083ff
- **Restricciones:** No CDNs para efectos, reduced-motion obligatorio, DPR capped a 2

## Entregable esperado
- Código completo listo para copiar a `functions.php` / template / CSS / JS
- `php -l` debe pasar sin errores
- Explicación de cambios en ≤5 líneas
```

### Prompt: "Kimi → Cursor" (reintegrar a Cursor)

```markdown
## Entregable desde Kimi
- **Archivo(s):** LISTA
- **Cambios:** RESUMEN_BREVE
- **Dependencias:** QUÉ SE ENCOLA/IMPORTA
- **Estado:** php -l OK / pendiente validación

## Acción requerida en Cursor
1. Revisar coherencia con design system (tokens `--gano-*`)
2. Verificar que no rompe Elementor/Hello Elementor parent
3. Commit con prefijo `feat()` o `fix()`
4. Deploy por SCP/webhook 04
```

---

## Modo 3: Sincronización de Contexto (Memory Bridge)

Para que Kimi y Cursor compartan el mismo estado mental del proyecto, usar estos archivos como fuente de verdad:

| Fuente | Propósito | Quién lo actualiza |
|--------|-----------|-------------------|
| `.cursor/memory/activeContext.md` | Estado actual, foco, bloqueantes | Cursor (humano o agente) |
| `.cursor/memory/progress.md` | Hitos completados y pendientes | Cursor (humano o agente) |
| `TASKS.md` | Checklist operativo global | Ambos — append-only |
| `.kimi/plans/*.md` | Planes maestros detallados | Kimi (plan mode) |
| `memory/sessions/*.md` | Handoffs y trazabilidad | Ambos |

### Regla de oro del bridge
> **Cursor nunca sobreescribe un `.kimi/plan` sin aprobación humana. Kimi nunca hace push a Git sin aprobación explícita.**

---

## Prompts Optimizados para Kimi (guardar como snippets)

### Snippet A: "Implementa efecto Canvas/WebGL"
```
Implementa un efecto [NOMBRE] en Canvas 2D o WebGL2 para el tema gano-child:
- Paleta SOTA: lavanda #c0c1ff, cian #4cd7f6, índigo #8083ff, fondo #0b1326
- Sin dependencias externas (no Three.js, no CDN)
- Lazy-init via IntersectionObserver
- Cancelar requestAnimationFrame al salir de viewport
- DPR capped a 2
- Fallback para móvil si pesa >10ms/frame
- Reduced-motion: pausar animaciones, dejar 1 frame estático
- Entregar como archivo JS independiente con namespace window.Gano[Nombre]
```

### Snippet B: "Verifica flujo comercial"
```
Verifica que los CTAs de [PÁGINA] apuntan correctamente al checkout:
- Función `gano_rstore_cart_url($pfid, $duration)` genera:
  https://cart.secureserver.net/?plid=599667&items=[{"id":"PFID","quantity":1,"duration":12}]
- Si PFID es 'PENDING_RCC' o PLID vacío → botón debe mostrar "Próximamente" con link a /contacto/
- 3 estados visuales: ACTIVO (glow + "Comprar"), PENDIENTE (opacity 0.6 + "Próximamente"), VENTAS (link /contacto/)
- Validar que no hay `#` en href de botones reales
```

### Snippet C: "Refactor con tokens unificados"
```
Refactoriza [ARCHIVO] para usar los tokens de `css/gano-tokens-unified.css`:
- Reemplazar colores hardcodeados por var(--gano-sota-*)
- Usar --gano-sota-motion-* para duraciones y easings
- Aplicar .gano-glow-text / .gano-glow-box donde haya sombras de neon
- Mantener compatibilidad con tokens legacy (--gano-blue, --gano-gold)
- No romper estilos existentes de Elementor
```

---

## MCP Servers relacionados

El `mcp.json` actual tiene:
- `github` — para PRs, issues, repo context
- `filesystem` — para navegar el proyecto
- `obsidian` — para second brain / memoria extendida

**No hay MCP server nativo para Kimi** (Moonshot AI no publica uno oficial aún). El bridge se hace via prompts y shared memory (archivos markdown).

---

## Workflow recomendado: Cursor + Kimi en paralelo

```
1. Cursor: explora código, refactoriza estructura, gestiona Git
   ↓
2. Si necesita >100K tokens de contexto o efectos proc. complejos:
   → Copiar prompt estandarizado + contexto relevante
   → Pegar en Kimi CLI / Kimi Code (sesión nueva)
   ↓
3. Kimi: genera código completo, valida php -l, explica cambios
   ↓
4. Copiar resultado de Kimi → Cursor
   → Cursor: revisa integración, coherencia de estilos, commit
   ↓
5. Deploy via SCP/webhook 04 (Cursor o manual)
```

---

## Permisos y límites

| Recurso | Cursor | Kimi | Quién decide |
|---------|--------|------|-------------|
| Editar código en repo | ✅ | ✅ | Agente activo (no simultáneo) |
| Commit + push GitHub | ⚠️ (con reglas) | ❌ (espera aprobación) | Humano |
| Deploy a producción | ⚠️ (SCP manual) | ❌ | Humano |
| Editar `.cursor/memory/` | ✅ | ⚠️ (solo append) | Cursor |
| Editar `.kimi/plans/` | ❌ | ✅ | Kimi (plan mode) |
| Crear nuevos archivos | ✅ | ✅ | Agente activo |
| Ejecutar `php -l` / tests | ✅ | ✅ | Ambos |
| Acceso a servidor SSH | ⚠️ | ⚠️ (desde entorno local) | Humano |

---

## Referencias

- Plan maestro actual: `.kimi/plans/domino-nova-falcon.md`
- Tokens SOTA: `wp-content/themes/gano-child/css/gano-tokens-unified.css`
- Texturas: `wp-content/themes/gano-child/css/gano-textures.css`
- Contexto técnico: `.cursor/memory/techContext.md`
