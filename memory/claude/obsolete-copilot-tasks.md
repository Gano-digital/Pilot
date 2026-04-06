# Drift: cola Copilot vs código actual (auditoría local)

**Regla:** no borrar entradas de `.github/agent-queue/*.json` sin OK de Diego; esto solo documenta **probable obsolescencia** para cerrar issues en GitHub.

| agent-task-id        | Archivo cola | Estado en `main` (evidencia)                                                                                                                                                      | Recomendación                                                                                                     |
| -------------------- | ------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `theme-audit-handle` | `tasks.json` | `functions.php` ya usa handle `royal-elementor-kit-parent` y dependencia del child correcta (líneas ~24–25).                                                                      | Cerrar issue abierto con ese id si el PR ya fusionó el cambio; no re-sembrar.                                     |
| `theme-css-root`     | `tasks.json` | `style.css` define `--gano-gold` en `:root` global (líneas ~43 y ~100) y conserva un `:root` de shop para `--gano-dark-bg`, `--gano-glass`, `--gano-glass-border` (líneas ~910+). | Mantener issue como **documentación/seguimiento** (no obsoleto total). No mover tokens sin prueba visual de shop. |
| `theme-lcp`          | `tasks.json` | `functions.php` ya inyecta script LCP para front page con selectores de containers clásicos + cleanup por `DOMContentLoaded`/timeout (~260–320).                                  | No cerrar por código adicional; cerrar solo tras documentar validación en front y confirmar estructura Elementor. |

## Cómo usar esta tabla

1. Buscar en GitHub issues abiertos con `agent-task-id:theme-audit-handle` en el cuerpo.
2. Si `main` ya contiene el handle correcto, comentar enlace a `functions.php` y cerrar con plantilla de [`gh-issue-close-guide.md`](gh-issue-close-guide.md).
