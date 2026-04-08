# Auditorías para el equipo

| Archivo | Descripción |
|---------|-------------|
| `gano-digital-auditoria-desarrolladores-*.pdf` | Informe consolidado (regenerar con `python scripts/generate_dev_audit_pdf.py` desde la raíz del repo). |
| `auditoria-desarrolladores-2026-04.html` | Vista HTML con mismas secciones + retratos; imprimir a PDF desde el navegador si se desea. |

La excepción en `.gitignore` (`!memory/audits/*.pdf`) permite versionar estos PDF; el resto de `*.pdf` del repo sigue ignorado.
