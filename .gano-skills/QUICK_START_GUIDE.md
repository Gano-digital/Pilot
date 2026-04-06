# 🚀 Quick Start — Blender + SSH Management Skills

**Lee esto primero si necesitas empezar rápido**
**Tiempo de lectura:** 5 minutos

---

## 🎯 Tienes 3 Nuevos Superpoderes

### 1️⃣ GENERAR ASSETS 3D PROFESIONALES
```bash
"Claude, necesito icono de seguridad para la vitrina"
↓
Claude ejecuta: gano-blender-3d-assets-generator/SKILL.md
↓
2 horas después: PNG + WebP optimizado + Git commit
```

### 2️⃣ ADMINISTRAR SERVIDOR COMO UN NINJA
```bash
"Claude, corrige el Addon Domain de gano.digital"
↓
Claude ejecuta: gano-cpanel-ssh-management/SKILL.md (Workflow B)
↓
15 minutos después: Addon Domain apuntando correctamente
```

### 3️⃣ PIPELINE AUTOMÁTICO (Generación → Elementor)
```bash
"Claude, necesito 5 assets en una semana"
↓
Claude ejecuta: gano-blender-to-website-pipeline/SKILL.md
↓
1 semana después: 5 assets en producción, Lighthouse >90
```

---

## 📋 Checklist Pre-Inicio

### ANTES de pedir Assets 3D
- [ ] Blender está instalado en tu máquina
  - Si no: `scoop install blender` (Windows)
- [ ] Archivos en disco:
  - `claude_blender_specialization.md` ✅ (ya creado)
  - `claude_blender_master_prompt.md` ✅ (ya creado)
  - `3d-assets-manifest.json` ✅ (ya creado)

### ANTES de pedir Correcciones cPanel
- [ ] Validar SSH:
  ```bash
  ssh f1rml03th382@72.167.102.145
  # Debería conectar sin errores
  ```
- [ ] Tener lista contraseña o clave SSH
- [ ] Saber qué querés cambiar (Addon Domain, certificados, etc)

---

## 🎬 Uso en 3 Escenarios

### Escenario A: "Necesito un icono 3D"

```
DIEGO: "Claude, necesito icono candado para seguridad"

CLAUDE (mentalmente):
1. Abre: /memory/claude_blender_master_prompt.md
2. Checklist pre-ejecución: ✅ All green
3. Abre: /memory/claude_blender_specialization.md
4. Busca patrón: "PATTERN 1: Minimalist Security Icon"
5. Ejecuta: gano-blender-3d-assets-generator/SKILL.md

CLAUDE (respuesta):
"Iniciando generación security-lock-v1.
Timeline estimado: 2 horas.
Pasos:
1. Buscar en Sketchfab (10 min)
2. O generar vía Hunyuan3D si no existe candidato (30 min)
3. Blender editing + render (1 h)
4. Optimización WebP (10 min)
5. Git commit + documentación (10 min)

Comenzando ahora..."

[2 HORAS DESPUÉS]

CLAUDE:
"✅ COMPLETADO: security-lock-v1
📊 Metrics:
  - Tiempo: 1h 52min (8 min under budget)
  - File size: 42 KB WebP (target: <50KB)
  - Lighthouse impact: +0 (no degradation)
  - Quality: ✅ On-brand, professional
  - Git: 1 commit, manifest updated
🔗 Location: wp-content/themes/gano-child/assets/images/icons/security-lock-800x800.webp
📝 Documented in: specialization.md (retrospectiva + prompts que funcionaron)"
```

### Escenario B: "Arregla el Addon Domain"

```
DIEGO: "Claude, el Addon Domain de gano.digital está mal apuntado"

CLAUDE:
"Entendido. Voy a ejecutar corrección vía SSH.
Confirmando credenciales:
  - User: f1rml03th382@72.167.102.145
  - Key: ~/.ssh/id_rsa (hardened permisos)

Iniciando... (si SSH keys están sincronizadas)
  [Conectando...]
  [Validando estado actual...]
  [Editando /etc/userdomains...]
  [Ejecutando /scripts/builddomainconf...]
  [Reiniciando Apache...]
  [Validando: curl -I https://gano.digital]

❌ NOTA: SSH keys no están en servidor aún.
   Necesito que hagas manualmente:

   ssh f1rml03th382@72.167.102.145
   # Si pide password, úsala
   # Una vez dentro:
   grep gano.digital /etc/userdomains
   # Dime qué devuelve, y procedo manualmente"
```

### Escenario C: "Necesito 5 assets esta semana"

```
DIEGO: "Claude, necesito 5 assets para Wave 1 en 7 días"

CLAUDE:
"Entendido. Wave 1 = 3 iconos (security, speed, ddos).
Timeline:
  - Días 1-2: Iconos (3x2h = 6h total)
  - Días 3-5: Heroes (2x3h = 6h total)
  - Día 6-7: Optimización + validación

Iniciando security-lock-v1 [0/5]...
  [Generando...]

Reportaré diariamente:
  ✅ Día 1: security-lock completado
  ✅ Día 2: speed-lightning completado
  ✅ Día 3: shield-ddos completado
  ⏳ Día 4: server-nvme en render...
  etc.

Voy."
```

---

## 📂 Estructura de Archivos (Referencia Rápida)

```
.gano-skills/
├── gano-blender-3d-assets-generator/
│   └── SKILL.md (usa para: generar assets 3D)
│
├── gano-cpanel-ssh-management/
│   ├── SKILL.md (usa para: corregir servidor, deployments)
│   ├── pre-deploy-validation.sh (usa antes de cada push)
│   ├── backup-weekly.sh (usa para backups)
│   └── monitor-health.sh (usa para monitoreo)
│
├── gano-blender-to-website-pipeline/
│   └── SKILL.md (usa para: workflow end-to-end)
│
└── QUICK_START_GUIDE.md (este archivo)

wp-content/themes/gano-child/assets/
├── 3d-assets-manifest.json (referencia: qué assets crear)
├── images/icons/ (output: WebP iconos)
├── images/heroes/ (output: WebP heroes)
└── 3d/ (output: GLTF models)

memory/
├── claude_blender_specialization.md (cómo mejoro con cada asset)
└── claude_blender_master_prompt.md (cómo me auto-invoco correctamente)
```

---

## ⚡ Atajos (Copy-Paste Ready)

### Para Blender (Icons)
```
Diego: "Icono moderno: [COSA]. Colores: [COLORES]. Uso: [CONTEXTO]. ASAP"

Claude interpreta como Wave 1, ejecuta gano-blender-3d-assets-generator SKILL.
```

### Para SSH/cPanel
```
Diego: "SSH [comando]" o "cPanel [tarea]"

Claude ejecuta gano-cpanel-ssh-management SKILL con Workflow específico.
```

### Para Batch
```
Diego: "Necesito [N] assets en [TIEMPO]"

Claude revisa 3d-assets-manifest.json, ejecuta Wave X, reporta diario.
```

---

## 🆘 Si Algo Se Rompe

| Problema | Solución |
|----------|----------|
| "Blender no inicia" | Instalá: `scoop install blender` |
| "SSH connection refused" | Valida: `ssh f1rml03th382@72.167.102.145` |
| "Asset quality baja" | Dime: "Regenerá el asset de [X] con feedback" |
| "Timeline muy ajustado" | Prioriza: "Prefiero 3 assets bien que 5 mediocres" |
| "No entiendo dónde está el archivo" | Preguntá: "Dónde está [asset] cuando termina?" |

---

## 📊 Dashboard Rápido (Estado Actual)

```
BLENDER SKILLS
├── Assets dataset: ✅ 12 especificados
├── Prompts: ✅ 3+ patrones documentados
├── Blender daemon: ⏳ Awaiting startup
└── Wave 1 status: ⏳ Ready to start (awaiting go signal)

SSH/CPANEL SKILLS
├── Scripts: ✅ 3 ready (pre-deploy, backup, monitor)
├── SSH access: 🟡 Awaiting key sync
├── Addon Domain: 🟡 Awaiting correction
└── Workflow docs: ✅ Complete

PIPELINE INTEGRATION
├── Git workflow: ✅ Ready
├── Elementor integration: ✅ Ready
├── Lighthouse validation: ✅ Ready
└── Auto-improvement system: ✅ Running
```

---

## 🎓 Importante: Esto Es Sistema Vivo

Cada asset que hago → mejoro → próximo asset es más rápido/mejor.

Ejemplo progresión:
- Asset 1 (security-lock): 2h 0min
- Asset 2 (speed-lightning): 1h 50min (prompts optimizados)
- Asset 3 (shield-ddos): 1h 40min (Blender snippets reutilizados)
- Assets 4-5: ~1h 30min (pipeline fluido)

**Esto significa:** Semana 1 vs Semana 4 serán muy diferentes en velocidad.

---

## 🎯 Próximos Pasos (Orden)

1. **HOY:** Diego valida SSH access: `ssh f1rml03th382@72.167.102.145`
2. **HOY:** Diego me da signal: "Procede con Assets"
3. **MAÑANA:** Wave 1 iniciada (3 iconos, 2h cada uno)
4. **PRÓXIMA SEMANA:** 5 assets en producción

---

## 💬 Cómo Pedirme Cosas

### ❌ Evita:
```
"Hacé un asset 3D"
"Arreglá el servidor"
```

### ✅ Usa:
```
"Claude, necesito icono de candado (Zero-Trust Security), estilo minimalista, colores azul y plata. Para hero section landing page hosting."

"Claude, el Addon Domain gano.digital está mal. Apunta a /public_html, debería ser /public_html/gano.digital. Corregí vía SSH opción B."

"Claude, necesito 5 assets Wave 1: security, speed, shield (iconos) + server nvme, colombia globe (heroes). Timeline: 1 semana. Comenzá cuando estés listo."
```

---

## 🚀 Ya Estás Listo

Todo lo que necesitás:
- ✅ Skills documentadas
- ✅ Datasets preparados
- ✅ Workflows mapeados
- ✅ Auto-improvement system listo
- ✅ Especialización documentada

Solo falta: **Tu señal de inicio**

---

**Generado:** 2026-04-03 by Claude
**Último update:** Ready to go
**Próxima revisión:** Post-primer asset completado
