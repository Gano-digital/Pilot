---
name: gano-wiki-curator
description: >
  Curación del Gano-Wiki local (OneDrive): sincronizar snapshots desde Pilot, mantener CHANGELOG/INDEX/manifest,
  regenerar paquetes PDF de lectura para humanos, y aplicar frontmatter embedding-ready a copias verbatim.
  Úsalo cuando el usuario diga "wiki", "Gano-Wiki", "curador", "ingest manifest", "PDF del wiki",
  "sincronizar wiki", "frontmatter wiki", o "exports pdf-readers".
---

# Gano Wiki Curator — Skill de curaduría local

El **Gano-Wiki** es la biblioteca de conocimiento **fuera de git** en
`C:\Users\diego\OneDrive\Documentos\Gano-Wiki\`. El código y la operación diaria siguen en `Pilot/`.

---

## Rutas canónicas

| Qué | Ruta |
|-----|------|
| Wiki | `%OneDrive%\Documentos\Gano-Wiki\` (o `GANO_WIKI_ROOT`) |
| Repo | `Pilot` (este repositorio) |
| PDFs de lectura | `Gano-Wiki\exports\pdf-readers\` |
| Bóveda sensible | `Gano-Wiki\_secrets-index\` (nunca subir a GitHub) |
| Esquema frontmatter | `Gano-Wiki\_schemas\article.yaml` |

---

## Flujo recomendado (orden)

1. **Leer** `Gano-Wiki/STATUS-AND-ROADMAP.md` + `CHANGELOG.md` (últimas entradas).
2. **Sincronizar** (opcional) memoria del repo hacia snapshots del wiki:
   - `powershell -File scripts/sync_wiki.ps1 -WikiRoot "C:\Users\diego\OneDrive\Documentos\Gano-Wiki" -WhatIf`
   - Quitar `-WhatIf` solo tras revisar la lista.
3. **Regenerar PDFs** de estudio/navegación para Diego:
   - `python scripts/generate_gano_wiki_reader_pdfs.py`
   - Salida: `exports/pdf-readers/Gano-Wiki-0*.pdf` + `README.md` del paquete.
4. **Frontmatter** en `.md` copiados verbatim: rellenar según `_schemas/article.yaml`; preferir script batch (Fase 5 evolución) antes que a mano archivo por archivo.

---

## Reglas duras

- **No** commitear el wiki ni `_secrets-index/` al repo público.
- **No** mover a papelera originales de claves/SFTP tras copiar a bóveda (política del wiki).
- **No** incrustar en PDFs el contenido de archivos en `_secrets-index/` (solo README + INVENTORY).
- Tras cada operación: **una entrada** en `Gano-Wiki/CHANGELOG.md` (origen → destino → tipo).

---

## Herramientas en Pilot

| Script | Rol |
|--------|-----|
| `scripts/generate_gano_wiki_reader_pdfs.py` | Paquete PDF de lectura (7 volúmenes + README en el wiki). |
| `scripts/sync_wiki.ps1` | Copia controlada `Pilot/.cursor/memory/*.md` → `Gano-Wiki/ai-agents/memory-snapshots/`. |

---

## Consumo optimizado para IA (recordatorio)

1. Los PDF son para **humanos**; las IAs deben preferir **Markdown con YAML** en el wiki.
2. Jerarquía de autoridad: `Pilot/CLAUDE.md` > `Pilot/.github/copilot-instructions.md` > `Pilot/AGENTS.md` > `Gano-Wiki/AGENTS.md`.
3. Super-skill narrativa: `Gano-Wiki/skills/super-skill/gano-digital-master.md`.

---

## Checklist de cierre de sesión de curaduría

- [ ] `CHANGELOG.md` actualizado
- [ ] `INDEX.md` o `STATUS-AND-ROADMAP.md` si cambió el alcance
- [ ] PDFs regenerados si hubo cambios sustantivos en meta-docs
- [ ] `ingest-manifest.md` (filas tocadas) alineadas con ✅/🟡
