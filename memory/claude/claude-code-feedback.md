---
name: feedback-general
description: Reglas de trabajo en este proyecto — cuándo y cómo intervenir en el código
metadata: 
  node_type: memory
  type: feedback
  originSessionId: c58974dc-9c79-4530-bd2d-a948dce24523
---

Investigar y consultar siempre antes de escribir código.

**Why:** El servidor tiene un pipeline activo (GitHub Actions + self-hosted runner). Ediciones directas a archivos sin entender el flujo pueden romper el deploy o crear conflictos con el repositorio.

**How to apply:** Antes de editar cualquier archivo PHP/CSS/JS:
1. Leer el archivo completo
2. Identificar qué otros archivos dependen de él
3. Presentar el diagnóstico y propuesta a Diego
4. Esperar confirmación antes de editar

No eliminar plugins de fase (gano-phase1-installer, gano-phase2-business, gano-phase3-content, gano-phase6-catalog, gano-phase7-activator, gano-content-importer) sin confirmación explícita de Diego. Estos despliegan el sitio.
