# Second brain portable (`Gano-SecondBrain-COMPLETO`) — sincronización

**Ruta local:** `C:\Users\diego\Downloads\Gano-SecondBrain-COMPLETO\wiki`

## Qué se vuelca manualmente (abr 2026)

| Origen (este repo / workspace) | Destino en portable |
|--------------------------------|----------------------|
| `memory/sessions/*.md` | `wiki/sessions/` |
| `wiki/dev-sessions/*.md` | `wiki/dev-sessions/` |
| `memory/claude/*.md` + `memory/claude/dispatch-queue.json` | `wiki/pilot-claude-memory/` |
| `.cursor/memory/*.md` | `wiki/pilot-cursor-memory/` con sufijo `-2026-MM-DD.md` si se desea versionar |
| `memory/research/*.md` | `wiki/pilot-memory-research/` (volcado íntegro; puede solaparse con `wiki/research-sota/`) |

Índices en el portable: `wiki/sessions/README.md`, `wiki/dev-sessions/README.md`, `wiki/pilot-cursor-memory/README.md`, `wiki/INDEX.md`, `CHANGELOG.md`, `README-PRIMERO.md`.

## Paquete completo desde Pilot

`powershell -File scripts\export_second_brain_to_downloads.ps1 -FolderName 'Gano-SecondBrain-COMPLETO'` (desde clon **Pilot** con script vigente) — luego **reaplicar** las carpetas anteriores si el export regenera solo desde OneDrive y aún no incluye `sessions/` en el script.

## Canon documental

- Investigación visual: `memory/research/visual-systems-canvas-svg-second-brain-2026-04.md` (repo) ↔ `wiki/ux/research/visual-systems-canvas-svg-second-brain-2026-04.md` (portable).
