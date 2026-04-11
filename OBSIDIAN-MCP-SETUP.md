# 🌌 Gano Digital — Obsidian MCP Integration Setup

**Estado**: ✅ COMPLETADO
**Versión**: 1.0
**Fecha**: 2026-04-06
**API Key**: Configurada ✅

---

## 🎯 Qué se logró

Hemos creado una **integración en tiempo real entre Claude y tu Obsidian vault** usando:

1. ✅ **Obsidian Local REST API** (Plugin instalado y configurado)
2. ✅ **8 documentos de constelación** (listos en `memory/constellation/`)
3. ✅ **3 Scripts de sincronización** (para automatizar actualizaciones)
4. ✅ **Dashboard HTML interactivo** (para visualización visual)

---

## 📁 Archivos Listos para Usar

### Constelación (8 documentos)
```
memory/constellation/
├── 00-PROYECTO-CONSTELACION-INDICE.md ← Comienza aquí
├── 01-MAPA-ESTRATEGICO.md
├── 02-FASES-ROADMAP.md
├── 03-ECOSISTEMA-AGENTES.md
├── 04-ARQUITECTURA-SISTEMAS.md
├── 05-PROCESOS-OPERATIVOS.md
├── 06-METRICAS-PROGRESO.md
├── 07-EQUIPO-COORDINACION.md
├── 08-DEPENDENCIAS-RIESGOS.md
├── MAPA-VISUAL-INTERACTIVO.md ← Diagramas Mermaid
└── STATUS-LIVE.md ← Actualizado automáticamente
```

### Visualizadores
```
memory/constellation/
└── VISUALIZADOR-INTERACTIVO.html ← Abre en navegador
```

### Scripts de Sincronización
```
scripts/
├── obsidian_sync_api.py ← Cliente Python (requiere requests)
├── obsidian_live_monitor.py ← Monitor en tiempo real
├── sync_obsidian.sh ← Script Bash
└── sync-obsidian-simple.ps1 ← PowerShell simple
```

---

## 🚀 Cómo Usar en la Práctica

### Opción 1: Visualización en Obsidian (RECOMENDADO)
1. Abre tu vault de Obsidian
2. Navega a `memory/constellation/00-PROYECTO-CONSTELACION-INDICE.md`
3. Verás todos los documentos con links interactivos
4. Los diagramas Mermaid se renderizan automáticamente
5. Usa **Graph View** (Ctrl+G) para ver relaciones visuales

### Opción 2: Visualización en Navegador
1. Abre `memory/constellation/VISUALIZADOR-INTERACTIVO.html` con tu navegador
2. Puedes:
   - Arrastrar nodos para reorganizar
   - Hacer click para destacar
   - Usar botones "Fase 4", "Agentes", "Riesgos" para enfocar

### Opción 3: Automatización en Tiempo Real

#### A. Ejecutar desde PowerShell (Windows)
```powershell
# En PowerShell con privilegios:
Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope CurrentUser

# Ejecutar el sincronizador:
powershell -ExecutionPolicy Bypass -File scripts/sync-obsidian-simple.ps1

# Para automatizar cada X minutos:
# Crear tarea programada de Windows (Task Scheduler)
```

#### B. Ejecutar desde bash/zsh (Mac/Linux)
```bash
bash scripts/sync_obsidian.sh
```

#### C. Ejecutar desde Python (Multiplataforma)
```bash
pip install requests
python3 scripts/obsidian_sync_api.py
```

---

## 🔌 Configuración del API Key

Los scripts leen el token desde la variable de entorno **`OBSIDIAN_API_KEY`**.
**No guardes el token en el repositorio.** Configúralo localmente:

**Windows (PowerShell)**:
```powershell
$env:OBSIDIAN_API_KEY = "<tu-token-local>"
```

**Linux / macOS**:
```bash
export OBSIDIAN_API_KEY="<tu-token-local>"
```

Puedes encontrar tu token en Obsidian → Settings → Community Plugins → Local REST API → API Key.

> ⚠️ El token no debe commitearse ni compartirse; vive únicamente en tu entorno local.

---

## 📊 Qué se Sincroniza Automáticamente

Cuando ejecutes cualquiera de los scripts, se actualiza:

1. **STATUS-LIVE.md** — Timestamp de última sincronización
2. **Índice de constelación** — Métricas frescas
3. **Dashboard de métricas** — Progreso Phase 4
4. **Reportes semanales** — Resúmenes automáticos
5. **Standups** — Logs de actividad diaria

---

## 🎯 Próximos Pasos para Ti

### Hoy (Apr 6)
- [ ] Abre `memory/constellation/00-PROYECTO-CONSTELACION-INDICE.md` en Obsidian
- [ ] Revisa los 8 documentos navegando los links
- [ ] Abre `VISUALIZADOR-INTERACTIVO.html` en navegador (opcional)

### Esta Semana
- [ ] Ejecuta el script de sincronización (`sync-obsidian-simple.ps1`)
- [ ] Verifica que `STATUS-LIVE.md` se actualiza
- [ ] (Opcional) Configura automatización en Task Scheduler

### Próxima Semana
- [ ] Recibe actualizaciones automáticas de métricas
- [ ] Consulta STATUS-LIVE.md para ver progreso en tiempo real
- [ ] Usa constelación para presentar a tu equipo

---

## 🛠️ Troubleshooting

**Si los scripts no funcionan:**

1. **"Could not connect to Obsidian"**
   - ✅ Abre Obsidian
   - ✅ Verifica que Local REST API está enabled
   - ✅ Comprueba puerto 27124 en Obsidian settings

2. **"ModuleNotFoundError: requests"**
   ```bash
   pip install requests
   ```

3. **Certificado SSL error**
   - El certificado es auto-firmado (local) — es seguro
   - Los scripts ya lo ignoran (`-SkipCertificateCheck`)

4. **Encoding error en PowerShell**
   - Usa `sync-obsidian-simple.ps1` (versión UTF-8)
   - O usa `sync_obsidian.sh` si tienes bash disponible

---

## 📈 Automatización Recomendada

### Windows (Task Scheduler)
```
Trigger: Daily @ 9:00 AM (o tu preferencia)
Action: powershell -ExecutionPolicy Bypass -File "C:\path\to\sync-obsidian-simple.ps1"
Repeat: Every 1 hour
```

### Mac/Linux (Cron)
```bash
# Editar crontab:
crontab -e

# Agregar línea:
0 9 * * * /bin/bash /path/to/scripts/sync_obsidian.sh
```

---

## ✅ Checklist de Verificación

- [ ] Obsidian abierto y Local REST API activo
- [ ] API Key validada (✅ ya está configurada)
- [ ] 8 documentos de constelación visibles en vault
- [ ] STATUS-LIVE.md presente (se crea automáticamente)
- [ ] VISUALIZADOR-INTERACTIVO.html funciona en navegador
- [ ] At least uno de los scripts ejecuta sin errores
- [ ] Obsidian puede actualizar archivos en tiempo real

---

## 💡 Tips Avanzados

### Crear vistas personalizadas en Obsidian
```
En Obsidian:
1. Settings → Templates
2. Crear templates para:
   - Daily standups
   - Weekly summaries
   - PFID progress logs
3. Usar en memory/constellation/STANDUPS/
```

### Monitoreo en vivo
```
Mantener abierto:
- memory/constellation/STATUS-LIVE.md (auto-refresh F5)
- memory/constellation/06-METRICAS-PROGRESO.md
- Graph View (Ctrl+G) para ver relaciones
```

### Integración con equipo
```
Compartir con equipo:
1. Obsidian Publish (si deseas web pública)
2. O simplemente: "Revisen memory/constellation en el repo"
3. Los documentos están en Markdown → legibles en GitHub también
```

---

## 🔐 Seguridad

- ✅ API Key es local (no viaja por internet)
- ✅ HTTPS encriptado en puerto 27124
- ✅ Token se carga desde variable de entorno `OBSIDIAN_API_KEY` (no versionado en el repo)
- ✅ Acceso solo vía LocalHost

---

## 📞 Soporte

Si necesitas:
- **Actualizar STATUS-LIVE.md**: Ejecuta scripts/sync-obsidian-simple.ps1
- **Agregar nuevas métricas**: Edita memory/constellation/06-METRICAS-PROGRESO.md
- **Cambiar formato**: Modifica los scripts (están en Python/Bash/PowerShell)
- **Sincronización manual**: Simplemente edita Obsidian — los cambios se guardan localmente

---

**¿Listo para empezar?**

→ Abre Obsidian y dirígete a: `memory/constellation/00-PROYECTO-CONSTELACION-INDICE.md`

🚀 Tu constelación interactiva está lista.

