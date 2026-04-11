# 🚀 ACTIVAR SINCRONIZACIÓN OBSIDIAN

**Estado**: Listo para activar
**Opción Recomendada**: Automatización Windows (1 click)

---

## ⚡ Opción 1: Ejecución Manual Rápida

Abre PowerShell/CMD y ejecuta:

```cmd
cd <ruta-del-clon>
scripts\sync-gano-obsidian.bat
```

✅ Se sincronizará inmediatamente
✅ Verifica: `memory/constellation/STATUS-LIVE.md` se actualiza

---

## 🔄 Opción 2: Automatización Windows (RECOMENDADO)

Esto hace que se sincronice automáticamente cada hora:

### Paso 1: Abre Task Scheduler
```
Windows + R → taskschd.msc → Enter
```

### Paso 2: Crear Nueva Tarea
- Click derecho: "Create Basic Task"
- **Nombre**: `Gano-Digital-Obsidian-Sync`
- **Descripción**: `Sincroniza constelación Gano Digital con Obsidian Local REST API`

### Paso 3: Configurar Disparador (Trigger)
- **Trigger Type**: `On a Schedule`
- **Frequency**: `Daily`
- **Start Date**: Hoy
- **Start Time**: `09:00 AM`
- ✅ Check: `Repeat task every 1 hour`
- ✅ Check: `Enabled`

### Paso 4: Configurar Acción (Action)
- **Action**: `Start a program`
- **Program/Script**:
  ```
  cmd.exe
  ```
- **Add arguments**:
  ```
  /c "<ruta-del-clon>\scripts\sync-gano-obsidian.bat"
  ```
- **Start in**:
  ```
  <ruta-del-clon>
  ```

### Paso 5: Configurar Opciones
- ✅ `Run with highest privileges`
- ✅ `Run whether user is logged in or not`
- `If the task fails, retry after 5 minutes` (optional)
- `Stop the task if it runs longer than 10 minutes`

### Paso 6: Guardar
- Click `Finish`
- La tarea se ejecutará cada hora automáticamente

---

## 📋 Script Automático (Opcional)

Para crear la tarea automáticamente via PowerShell:

```powershell
# Ejecuta como Admin:
$action = New-ScheduledTaskAction -Execute 'cmd.exe' `
  -Argument '/c "<ruta-del-clon>\scripts\sync-gano-obsidian.bat"' `
  -WorkingDirectory '<ruta-del-clon>'

$trigger = New-ScheduledTaskTrigger -Daily -At 09:00 -RepetitionInterval (New-TimeSpan -Hours 1)

Register-ScheduledTask -Action $action -Trigger $trigger `
  -TaskName 'Gano-Digital-Obsidian-Sync' `
  -Description 'Sincroniza constelación Gano Digital con Obsidian'
```

---

## ✅ Verificar que Funciona

1. **Ejecución manual**:
   ```cmd
   scripts\sync-gano-obsidian.bat
   ```
   - Deberías ver: `[OK] Connected!` y `[OK] Status file updated!`

2. **Verificar archivo actualizado**:
   - Abre Obsidian
   - Navega a: `memory/constellation/STATUS-LIVE.md`
   - Verifica que el timestamp es reciente

3. **Verificar tarea programada**:
   - Task Scheduler → `Gano-Digital-Obsidian-Sync`
   - Click derecho → `Run`
   - STATUS-LIVE.md se actualiza nuevamente

---

## 🎯 Qué Sucede en Cada Sincronización

✅ Se actualiza `STATUS-LIVE.md` con:
- Timestamp de última sincronización
- Estado de sistemas
- Métricas Phase 4
- Próximas acciones

✅ Se verifica conexión a Obsidian Local REST API
✅ Se confirma que tu vault es accesible

---

## 🛑 Pausar/Detener Sincronización

### Pausar temporalmente:
- Task Scheduler → `Gano-Digital-Obsidian-Sync` → Click derecho → `Disable`

### Volver a habilitar:
- Task Scheduler → `Gano-Digital-Obsidian-Sync` → Click derecho → `Enable`

### Eliminar completamente:
- Task Scheduler → `Gano-Digital-Obsidian-Sync` → Click derecho → `Delete`

---

## 💡 Próximos Pasos

### Después de activar la sincronización:

1. **Hoy (Apr 6)**
   - [ ] Ejecuta script manual: `scripts\sync-gano-obsidian.bat`
   - [ ] Verifica STATUS-LIVE.md se actualiza
   - [ ] (Opcional) Configura tarea automática en Task Scheduler

2. **Semana 1 (Apr 7-13)**
   - [ ] Sincronización se ejecuta cada hora automáticamente
   - [ ] Consulta STATUS-LIVE.md para ver progreso
   - [ ] Los cambios en constelación se reflejan en Obsidian

3. **Semana 2+ (Apr 14+)**
   - [ ] Recibe actualizaciones automáticas de métricas
   - [ ] Usa constelación para presentar a equipo
   - [ ] Modifica scripts si necesitas personalizaciones

---

## ❓ FAQ

**¿Se necesita estar logueado en Windows?**
- No, configuramos `Run whether user is logged in or not`

**¿Qué pasa si Obsidian está cerrado?**
- El script reporta error "Could not connect"
- Se reintentar automáticamente en 1 hora

**¿Puedo cambiar la frecuencia de sincronización?**
- Sí, en Task Scheduler:
  - `Repeat task every: 30 minutes` (más frecuente)
  - `Repeat task every: 1 day` (menos frecuente)

**¿Dónde veo los logs?**
- Los comandos se guardan en Event Viewer:
  - Windows + R → `eventvwr` → `Windows Logs` → `System`

---

## 🚀 ¡LISTO!

Tu sincronización Obsidian está configurada y lista.

**Próximo paso**: Ejecuta manualmente una vez para verificar:
```cmd
scripts\sync-gano-obsidian.bat
```

Luego, Task Scheduler se encargará del resto automáticamente.

¿Preguntas? Consulta OBSIDIAN-MCP-SETUP.md

