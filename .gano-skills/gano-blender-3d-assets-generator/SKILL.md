# Skill — Blender 3D Asset Generator para Gano Digital

**Última actualización:** 2026-04-03
**Estado:** 🟢 OPERATIVO (awaiting Blender daemon)
**Especialización:** Generación integral de assets 3D para UI, marketing, y documentación técnica

---

## 🎯 Propósito

Crear un flujo de trabajo integrado Blender ↔ Gano Digital que permita:
- ✅ Generación rápida de iconos/ilustraciones 3D para vitrina WP
- ✅ Mockups de productos/servicios en tiempo real
- ✅ Assets técnicos para "Soberanía Digital" (seguridad, infraestructura)
- ✅ Animaciones cortas para landing pages (Elementor integration)
- ✅ Control de versiones de assets en Git (versioning)

---

## 📦 Dataset Definido

| Asset Type | Cantidad | Uso | Estado |
|-----------|----------|-----|--------|
| **Iconos 3D** (hosting, seguridad, velocidad) | 12 | Hero section + feature list | 🟡 En catalogación |
| **Server Illustrations** | 3 | Infraestructura visual (NVMe, DDoS, failover) | 🟡 En catalogación |
| **Product Mockups** | 5 | Planes de hosting (Basic, Pro, Enterprise) | 🟡 Esperando Blender |
| **Animaciones micro** | 3 | Skeleton loading, transition effects | 🟡 Esperando Blender |
| **Infografías técnicas** | 2 | Comparativa hosting tradicional vs. gano.digital | 🟡 Esperando Blender |

**Total objetivo:** 25 assets 3D profesiónales, versionados en Git bajo `/wp-content/themes/gano-child/assets/3d/`

---

## 🛠️ Flujo de Trabajo — 5 Pasos

### Paso 1: Descarga + Búsqueda de Base Assets
- **Tool:** `search_sketchfab_models` + `download_polyhaven_asset`
- **Input:** Descripción de asset (ej: "secure server icon")
- **Output:** .glb/.fbx descargado a `/tmp/blender-imports/`

### Paso 2: Generación 3D (Hunyuan3D o Hyper3D)
- **Tool:** `generate_hunyuan3d_model` o `generate_hyper3d_model_via_text`
- **Input:** Prompt especializado en español (tono marca Gano)
- **Output:** Archivo .obj/.gltf en `/tmp/blender-gen/`

### Paso 3: Edición + Texturización (Blender Python)
- **Tool:** `execute_blender_code` (Python API)
- **Actions:**
  - Import asset descargado o generado
  - Apply textures (Polyhaven PBR)
  - Lighting setup (3-point profesional)
  - Render a PNG/WebP (2K, background transparent)

### Paso 4: Optimización + Versioning
- **Tool:** Local file system + Git
- **Actions:**
  - Comprimir .glb para web (gltf-pipeline)
  - Generar LOD versions (high/med/low)
  - Commit a Git con metadata: `feat(3d): [nombre-asset] v1.0 | [descripción técnica]`

### Paso 5: Integración Elementor
- **Tool:** Manual en wp-admin (Elementor Custom HTML)
- **Implementation:**
  - `<model-viewer>` tag con Three.js loader
  - Auto-rotate, light interaction
  - Mobile-optimized

---

## 🔧 Comandos Específicos

### Buscar asset base (Sketchfab)
```bash
# Ejecutar desde Claude
search_sketchfab_models(
  query="secure datacenter server modern",
  categories="architectural,electronics",
  count=5
)
```

### Generar 3D vía AI (Hunyuan3D)
```bash
generate_hunyuan3d_model(
  text_prompt="Icono 3D minimalista: candado digital con líneas de código, colores azul y plata, estilo moderno, fondo transparente",
  user_prompt="Para landing page Gano Digital - tema: Zero-Trust Security"
)
```

### Ejecutar Blender Python (render)
```python
# Dentro execute_blender_code
import bpy
import os

# Load model
model_path = "/tmp/blender-imports/server.glb"
bpy.ops.import_scene.gltf(filepath=model_path)

# Set up lighting
bpy.ops.object.light_add(type='SUN', location=(5, 5, 5))
bpy.ops.object.light_add(type='POINT', location=(-5, -5, 3))

# Render
bpy.data.scenes["Scene"].render.engine = 'CYCLES'
bpy.data.scenes["Scene"].render.filepath = "/tmp/renders/server-icon.png"
bpy.ops.render.render(write_still=True)

print(f"✅ Renderizado: server-icon.png")
```

---

## 📁 Estructura de Carpetas (Git)

```
wp-content/themes/gano-child/assets/3d/
├── icons/
│   ├── security-lock.glb
│   ├── speed-lightning.glb
│   └── ...
├── illustrations/
│   ├── datacenter-nvme.glb
│   └── ...
├── mockups/
│   └── hosting-plans.glb
└── animations/
    └── skeleton-loader.glb
```

**Metadata file:** `3d-assets-manifest.json`
```json
{
  "assets": [
    {
      "id": "security-lock-v1",
      "name": "Candado Digital - Zero Trust",
      "file": "icons/security-lock.glb",
      "source": "hunyuan3d | sketchfab:XXXXX | manual",
      "created": "2026-04-03",
      "optimizations": ["glb-compressed", "lod-high", "lod-med"],
      "usage": "hero-section + feature-grid"
    }
  ]
}
```

---

## 🚀 Comandos de Dispatch (Para Claude)

Estos comandos van en `.github/agent-queue/tasks-blender-3d.json`:

```json
{
  "task_id": "blender-asset-security-icons-01",
  "description": "Generar 3 iconos 3D: Candado (Zero-Trust), Escudo (DDoS), Rayo (Velocidad)",
  "assignee": "claude",
  "commands": [
    "search_sketchfab_models query=secure icon modern count=5",
    "generate_hunyuan3d_model text_prompt='Icono 3D...'",
    "execute_blender_code (load + render)",
    "git_commit 'feat(3d): security-icon-set v1.0'"
  ]
}
```

---

## 📊 Especialización Auto-Progresiva

Cada vez que ejecute esta skill:
1. ✅ Leo la documentación actualizada
2. ✅ Ajusto prompts basado en resultado anterior
3. ✅ Agrego nuevos patrones a `BLENDER_PATTERNS.md`
4. ✅ Actualizo dataset en `3d-assets-manifest.json`

**Patrones aprendidos se guardan en:** `.gano-skills/gano-blender-3d-assets-generator/BLENDER_PATTERNS.md`

---

## 🎨 Prompt Especializado para Claude (Self-Improvement)

Uso este prompt cuando genero assets:

```
# Contexto
Soy especialista en generación 3D para Gano Digital.
Stack: Blender + Hunyuan3D + Sketchfab + Polyhaven.
Marca: Hosting WordPress premium Colombia.
Tono: Profesional, minimalista, moderno, seguridad-first.

# Si genero con Hunyuan3D:
- Especifico: "para landing WordPress" (contexto)
- Colores: Azul digital (#0066FF), plata, blanco
- Estilo: 3D moderno, bordes definidos, minimalista
- Resolución: 4K, fondo transparente PNG
- Licencia: Royalty-free para uso comercial

# Si descargo de Sketchfab:
- Busco: CC0 o CC-BY (uso libre)
- Calidad: 2K+, PBR materials
- Vértices: <100k (optimización web)
- Validación: Descargo preview, verifico que encaje marca

# Evaluación post-generación:
- ¿Encaja visual + funcionalmente?
- ¿Peso de archivo <5MB?
- ¿Optimización web (WebP/GLTF-compressed)?
- ¿Versionado en Git?

# Si NO cumple, itero:
- Regenero con prompt ajustado
- O busco alternativa en Sketchfab/Polyhaven
```

---

## 📋 Checklist de Completitud

- [ ] 12 iconos 3D creados + versionados
- [ ] 3 ilustraciones servidor + animadas
- [ ] 5 mockups de planes renderizados
- [ ] 3 micro-animaciones (Elementor ready)
- [ ] 2 infografías técnicas
- [ ] `3d-assets-manifest.json` actualizado
- [ ] Integración Elementor testeada (hero + features)
- [ ] Performance validado (Lighthouse >90)
- [ ] Git history limpio (commits descriptivos)

---

## 🔗 Referencias

- Blender Python API: https://docs.blender.org/api/current/
- Hunyuan3D: https://hunyuan3d.com
- Sketchfab Assets: https://sketchfab.com (filtro CC0)
- Polyhaven: https://polyhaven.com
- Model Viewer (Web): https://modelviewer.dev
- GLTF Optimization: https://github.com/khronosfoundation/gltf-pipeline

---

**Generated:** 2026-04-03 by Claude
**Next Review:** Post-primer asset creado
