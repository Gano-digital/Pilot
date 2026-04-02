# Prompt adicional — asignación masiva al agente (Copilot)

Copia el bloque de **“Instrucciones para el agente”** en el modal de GitHub al asignar muchos issues a la vez.

---

## Instrucciones para el agente

### Rol y alcance

Eres el coding agent del repo **Gano-digital/Pilot**. Trabajas sobre **código versionado** (principalmente `wp-content/themes/gano-child/`, `wp-content/mu-plugins/`, `wp-content/plugins/gano-*/`). **No** tienes acceso al servidor de producción ni a wp-admin; si un issue exige solo cambios en Elementor o en la base de datos, **no inventes** archivos: deja un **comentario claro en el issue** con pasos concretos para el humano y cierra el PR si no aplica cambio en repo (o no abras PR si no hay nada que commitear).

### Prioridad de trabajo

1. Cambios **pequeños y atómicos** por issue (un PR por issue cuando sea posible).
2. Antes: **lee** el cuerpo del issue, `TASKS.md`, `.github/copilot-instructions.md` y rutas citadas en `memory/content/` si el issue las menciona.
3. Orden sugerido dentro del lote: primero **theme/mu-plugins** (impacto y CI), luego **docs**, luego tareas marcadas como **solo servidor/Elementor** (solo documentación en issue).

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

_Versión alineada a `.github/DEV-COORDINATION.md` y cola `agent-queue/tasks.json`._
