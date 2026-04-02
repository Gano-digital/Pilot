# Prompt adicional — asignación masiva al agente (Copilot)

## Qué pegar según el lote (no mezclar)

| Lote | Issues | Qué usar |
|------|--------|----------|
| **Oleada 1** (homepage / tema técnico) | **#17–#33** | Bloque **“Oleada 1”** más abajo. El brief `gano-wave3-brand-ux-master-brief.md` es **opcional**, no la fuente principal. |
| **Oleada 3** (marca / UX / insumos) | **#54–#68** | Bloque **“Oleada 3”** más abajo + reglas comunes. |

Si pegaste el prompt de **oleada 3** en issues **#17–#33**, el agente puede desviarse (prioriza brief en vez de hero/menú/Lorem). **Corrige** dejando solo asignación + prompt oleada 1 en esos números, o confía en las reglas unificadas de la siguiente sección (ya matizadas).

---

## Instrucciones para el agente (unificadas — Copilot las interpreta por número de issue)

### Rol y alcance

Eres el coding agent del repo **Gano-digital/Pilot**. Trabajas sobre **código versionado** (principalmente `wp-content/themes/gano-child/`, `wp-content/mu-plugins/`, `wp-content/plugins/gano-*/`). **No** tienes acceso al servidor de producción ni a wp-admin; si un issue exige solo cambios en Elementor o en la base de datos, **no inventes** archivos: deja un **comentario claro en el issue** con pasos concretos para el humano y cierra el PR si no aplica cambio en repo (o no abras PR si no hay nada que commitear).

### Prioridad de trabajo

1. Cambios **pequeños y atómicos** por issue (un PR por issue cuando sea posible).
2. Antes: **lee** el cuerpo del issue, `TASKS.md`, `.github/copilot-instructions.md` y rutas citadas en `memory/content/` si el issue las menciona.
3. **Si el issue es #17–#33 (oleada 1):** prioriza **siempre** las tareas y archivos que cita el propio issue (p. ej. `homepage-copy-2026-04.md`, `elementor-home-classes.md`, `shop-premium.php`). El brief wave3 es **contexto secundario**; no generes entregables `*-wave3.md` salvo que el issue lo pida explícitamente.
4. **Si el issue es #54–#68 (oleada 3):** lee primero `memory/research/gano-wave3-brand-ux-master-brief.md`; entrega **markdown estructurado** en las rutas que el issue indique (`memory/content/*` o `memory/research/*`), español Colombia/LATAM, **honestidad Reseller** (sin simular datacenter propio).
5. Orden sugerido dentro del lote: primero **theme/mu-plugins** (impacto y CI), luego **docs**, luego tareas marcadas como **solo servidor/Elementor** (solo documentación en issue).

### Reglas duras (no negociables)

- **Nunca** commitear credenciales, tokens, contraseñas, IPs de servidor con usuario cPanel, ni claves Wompi en claro.
- **No eliminar** plugins `gano-phase*` sin confirmación explícita en el issue (son instaladores).
- Respetar prefijo `gano_` en funciones PHP nuevas del child theme; sanitizar/escapar según WordPress.
- Tocar **Wompi** o pagos solo con cambios mínimos y sin romper el flujo existente.

### CI y calidad

- Tras cambios PHP en rutas Gano: el repo ejecuta **PHP lint** y **TruffleHog** (rutas acotadas). Si el PR falla:
  - **PHP lint:** corrige sintaxis en los archivos tocados; vuelve a empujar.
  - **TruffleHog:** si algo parece falso positivo en código legítimo, **no** silencies con secretos reales; reduce ruido solo con exclusiones ya acordadas en el repo o comenta en el PR para revisión humana.
- Prefiere `php -l` mentalmente sobre archivos editados antes de subir.

### Self-troubleshooting (sin esperar al humano)

| Situación | Qué hacer |
|-----------|-----------|
| **Conflicto de merge** con `main` | Actualiza la rama del PR desde `main`, resuelve solo en archivos que tú tocaste; si el conflicto es ajeno, comenta en el PR y pide intervención. |
| **El issue mezcla “servidor” y “repo”** | Parte en dos: PR solo para la parte repo; comentario en el issue con checklist wp-admin para el resto. |
| **No encuentras el archivo citado** | Busca en el repo (`grep`/search); si no existe en esta copia, documenta en el issue y **no** bloquees el resto del lote. |
| **Cambio visual dudoso** | Limita el diff; usa clases existentes (`gano-el-*` en `style.css`) antes de CSS nuevo grande. |
| **Dependabot o workflow** | No reescribir toda la CI; cambios mínimos y motivados en el mensaje del commit. |
| **Dos issues piden lo mismo** | Un solo PR referenciando ambos issues; comenta en el duplicado el enlace al PR. |

### Entrega esperada

- **PR:** título claro, descripción con “Qué / Por qué / Cómo validar”, enlace al issue.
- Si **no hay PR posible:** comentario en el issue: motivo, pasos manuales, riesgos.
- Marca el issue según política del repo cuando el trabajo del agente en repo esté **hecho** o **bloqueado** con razón explícita.

### Idioma

- Mensajes de PR y comentarios en **español** (Colombia), técnico y directo.

---

## Bloque para copiar — solo oleada 1 (#17–#33)

```
Eres el coding agent del repo Gano-digital/Pilot. Este issue es de la oleada 1 (homepage/tema).

Prioridad: el CUERPO del issue y los archivos que cita (homepage-copy-2026-04.md, elementor-home-classes.md, etc.). No uses el brief wave3 como fuente principal.

Reglas: sin secretos en commits; no borrar gano-phase* sin confirmación; prefijo gano_ en PHP nuevo; si solo aplica Elementor/wp-admin, deja checklist en el issue sin inventar código.

CI: si tocas PHP Gano, debe pasar php-lint y TruffleHog. PR en español con enlace al issue.
```

## Bloque para copiar — solo oleada 3 (#54–#68)

```
Eres el coding agent del repo Gano-digital/Pilot (oleada 3).

Lee primero memory/research/gano-wave3-brand-ux-master-brief.md y el cuerpo del issue. Entrega en las rutas exactas que pide el issue (memory/content/* o memory/research/*). Español CO/LATAM; honestidad Reseller; sin datacenter propio ficticio; placeholders [NIT] donde falten datos.

Sin secretos; gano_ en PHP; CSP/MU gano-security si tocas CSS/JS. Un issue → un PR cuando sea posible. Mensajes en español.
```

---

_Versión alineada a `.github/DEV-COORDINATION.md` y colas `tasks.json` / `tasks-wave3.json`._
