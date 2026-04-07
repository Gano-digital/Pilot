---
name: gano-dev-audit-reporting
description: Generar y mantener auditorías (PDF/HTML) para desarrolladores, con assets y métricas del repo.
---

# Skill — Auditoría para desarrolladores (PDF/HTML) — Gano Digital

**Última actualización:** 2026-04-07  
**Estado:** operativo  
**Entregables:** `memory/audits/*.pdf` + `memory/audits/*.html` (versionados)  

---

## Cuándo usar esta skill

- Diego pide “**exporta un PDF**”, “auditoría para mis desarrolladores”, “reporte de capacidades + roadmap”.
- Se agregan capacidades nuevas en Constellation / skills / workflows y hay que **reflejarlo** en un informe compartible.
- Se requiere un documento **versionado** y **regenerable** (no un copy-paste manual).

---

## Fuentes de verdad (orden)

1. `TASKS.md` — roadmap vivo (lo pendiente + lo hecho).
2. `CLAUDE.md` — tabla de skills + archivos importantes.
3. `tools/gano-ops-hub/public/data/progress.json` — métricas parseadas de `TASKS.md`.
4. `memory/constellation/CONSTELACION-COSMICA.html` — capacidades Constellation (interacción + SFX + UI).

---

## Generar el PDF (comando)

Desde la **raíz del repo**:

```bash
pip install fpdf2
python scripts/generate_dev_audit_pdf.py
```

Salida:
- `memory/audits/gano-digital-auditoria-desarrolladores-YYYY-MM-DD.pdf`

Notas:
- En Windows usa Arial; en Linux intenta DejaVu; si no hay TTF disponible cae a Helvetica (evitar caracteres raros si se edita el script en ese modo).
- Si el script falla por fonts, instalar una fuente TTF Unicode o ajustar `setup_doc_fonts()` en `scripts/generate_dev_audit_pdf.py`.

---

## Vista HTML (para imprimir / compartir en navegador)

- `memory/audits/auditoria-desarrolladores-2026-04.html`
- Puede imprimirse desde el navegador (Ctrl+P → Guardar como PDF) como copia alternativa.

---

## Qué actualizar cuando cambie el proyecto

### Si cambia el roadmap
- Actualizar `TASKS.md` (y regenerar `tools/gano-ops-hub/public/data/progress.json` si aplica).
- Regenerar el PDF con el script.

### Si cambian skills
- Confirmar que `CLAUDE.md` incluye la nueva fila en “Skills disponibles”.
- Regenerar el PDF (cuenta skills por carpeta).

### Si cambia Constellation (assets o interacción)
- Verificar que el PDF incluya:
  - Retratos: `memory/constellation/assets/portraits/*.png`
  - Audio HUD: `memory/constellation/sounds/agentcraft/*.wav`
  - Resumen de capacidades (interacción, SFX pools, guardrails)
- Regenerar el PDF.

---

## Guardrails (muy importante)

- **No** incluir binarios extraídos del juego (MPQ/GRP) en el repo.
- Está permitido versionar auditorías en `memory/audits/` porque `.gitignore` tiene excepción `!memory/audits/*.pdf`.
- `vendor/` y `.obsidian/` deben mantenerse fuera de git (ignorado por `.gitignore`).

---

## Checklist de entrega a desarrolladores

- [ ] PDF nuevo en `memory/audits/` con fecha actual.
- [ ] `memory/audits/README.md` sigue vigente.
- [ ] `CLAUDE.md` y `TASKS.md` coherentes (sin contradicciones obvias).
- [ ] Si se añadieron assets: confirmación de rutas y conteos (WAV/PNG).

