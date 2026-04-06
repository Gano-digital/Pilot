# Skill Master — Blender 3D Assets → Gano Digital (Complete Pipeline)

**Última actualización:** 2026-04-03
**Estado:** 🟢 READY (awaiting Blender daemon + assets)
**Especialización:** End-to-end workflow desde generación 3D hasta deployment en WordPress

---

## 🎯 Objetivo

Crear un pipeline completamente automatizado que permita:

1. **Generación:** Crear assets 3D (Hunyuan3D, Sketchfab, Polyhaven)
2. **Edición:** Texturizar, iluminar, renderizar en Blender
3. **Optimización:** Comprimir para web, versionado Git
4. **Deployment:** Integrar en WordPress vía Elementor
5. **Validación:** Lighthouse, performance, accesibilidad

**Resultado:** Desde "necesito un icono de seguridad 3D" → PNG/WebP optimizado en producción en <2 horas.

---

## 🏗️ Arquitectura (4 Etapas)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    USER REQUEST (Discord/Email)                     │
│                  "Necesito icono: Candado Digital"                  │
└────────────────────────┬────────────────────────────────────────────┘
                         │
                    ┌────▼─────┐
                    │  STAGE 1  │
                    │  SEARCH   │
                    └────┬─────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                │
   ┌────▼────┐    ┌─────▼──────┐  ┌─────▼────┐
   │Sketchfab│    │  Hunyuan3D │  │Polyhaven │
   │(model)  │    │ (generate) │  │(textures)│
   └────┬────┘    └─────┬──────┘  └─────┬────┘
        │                │                │
        └────────────────┼────────────────┘
                    ┌────▼──────┐
                    │  STAGE 2   │
                    │  BLENDER   │
                    │  EDITING   │
                    └────┬───────┘
         ┌──────────────┬┴┬──────────────┐
         │              │ │              │
    ┌────▼───┐  ┌───────▼─┴─────┐  ┌────▼────┐
    │ Import │  │ Texturize &   │  │ Material│
    │ & Mesh │  │   Lighting    │  │  Setup  │
    │Cleanup │  │               │  │         │
    └────┬───┘  └───────┬───────┘  └────┬────┘
         │              │               │
         └──────────────┼───────────────┘
                   ┌────▼──────┐
                   │  RENDER   │
                   │  (PNG/EXR)│
                   └────┬──────┘
                        │
                   ┌────▼──────────┐
                   │  STAGE 3       │
                   │  OPTIMIZE &    │
                   │  COMPRESS      │
                   └────┬───────────┘
          ┌─────────────┬┴┬──────────────┐
          │             │ │              │
      ┌───▼────┐  ┌────▼─┴───┐  ┌───────▼────┐
      │  WebP  │  │GLTF-Compr │  │  LOD Gen   │
      │Convert │  │           │  │ (high/med) │
      └───┬────┘  └────┬──────┘  └───────┬────┘
          │            │                 │
          └────────────┼─────────────────┘
                   ┌───▼──────┐
                   │  STAGE 4  │
                   │  GIT +    │
                   │ ELEMENTOR │
                   └───┬──────┘
        ┌──────────────┼──────────────┐
        │              │              │
   ┌────▼────┐   ┌─────▼───┐   ┌─────▼────┐
   │ Git Add │   │ Update  │   │ WP Sync  │
   │ Commit  │   │ Manifest│   │ & Render │
   │& Push   │   │         │   │  Test    │
   └────┬────┘   └─────┬───┘   └─────┬────┘
        │              │             │
        └──────────────┼─────────────┘
                   ┌───▼──────────┐
                   │  LIVE ON:    │
                   │gano.digital  │
                   └──────────────┘
```

---

## 📋 Flujo Detallado (Paso a Paso)

### STAGE 1: ASSET SEARCH & ACQUISITION

**Input:** Descripción en español (ej: "Icono candado digital zero-trust security")

**Paso 1A: Search (Sketchfab)**
```bash
task_id="search-sketchfab-security-lock"
command="search_sketchfab_models(
  query='candado digital secure zero trust lock icon 3d',
  categories='electronics,icons,security',
  count=5,
  downloadable=true
)"
# Output: [asset_1.glb, asset_2.glb, asset_3.glb]
```

**Paso 1B: Search Alternative (Hunyuan3D)**
```bash
task_id="generate-hunyuan3d-security-lock"
command="generate_hunyuan3d_model(
  text_prompt='Icono 3D moderno: candado digital con líneas de código superpuestas. Colores: azul digital #0066FF, plata, blanco. Estilo: minimalista, bordes definidos, fondo transparente. Resolución: 4K. Uso: landing page hosting seguro.',
  user_prompt='Para Gano Digital - tema Zero-Trust Security, vitrina WordPress'
)"
# Output: model_hunyuan_001.obj + textures
```

**Paso 1C: Validate & Select**
- Validar licencia (CC0 o comercial OK)
- Verificar peso (<500KB)
- Confirmar resolución (2K+ quality)
- Guardar a: `/tmp/blender-imports/security-lock-candidate-[source].glb`

**→ Mejor candidato → Paso 2**

---

### STAGE 2: BLENDER EDITING & RENDERING

**Input:** .glb/.obj descargado
**Output:** PNG/EXR renderizado + WebP optimizado

**Paso 2A: Import & Clean**
```python
import bpy

# Clear scene
bpy.ops.object.select_all(action='SELECT')
bpy.ops.object.delete()

# Import
asset_path = "/tmp/blender-imports/security-lock-candidate.glb"
bpy.ops.import_scene.gltf(filepath=asset_path)

# Clean mesh
bpy.ops.object.mode_set(mode='EDIT')
bpy.ops.mesh.select_all(action='SELECT')
bpy.ops.mesh.remove_doubles()  # Remove duplicate vertices
bpy.ops.mesh.normals_make_consistent()
bpy.ops.object.mode_set(mode='OBJECT')

print("✅ Mesh cleaned")
```

**Paso 2B: Material & Lighting**
```python
# Set viewport shading
for area in bpy.context.screen.areas:
    if area.type == 'VIEW_3D':
        area.spaces[0].shading.type = 'MATERIAL'

# Apply material from Polyhaven (if sourced from there)
# OR create procedural material
mat = bpy.data.materials.new(name="gano-material")
mat.use_nodes = True
bsdf = mat.node_tree.nodes["Principled BSDF"]
bsdf.inputs['Base Color'].default_value = (0, 0.4, 1.0, 1.0)  # Azul gano
bsdf.inputs['Metallic'].default_value = 0.6
bsdf.inputs['Roughness'].default_value = 0.3

# Lighting: 3-point setup
bpy.ops.object.light_add(type='SUN', location=(5, 5, 5))
light_sun = bpy.context.active_object
light_sun.data.energy = 2.0

bpy.ops.object.light_add(type='POINT', location=(-5, -5, 3))
light_fill = bpy.context.active_object
light_fill.data.energy = 1.0

bpy.ops.object.light_add(type='POINT', location=(0, -8, 0))
light_back = bpy.context.active_object
light_back.data.energy = 0.5

print("✅ Materials & lighting applied")
```

**Paso 2C: Render Setup**
```python
# Render engine & settings
scene = bpy.context.scene
scene.render.engine = 'CYCLES'
scene.render.samples = 256
scene.render.use_denoising = True
scene.render.film_transparent = True  # Transparent background

# Output format
scene.render.filepath = "/tmp/renders/security-lock-hero.png"
scene.render.image_settings.file_format = 'PNG'
scene.render.image_settings.color_mode = 'RGBA'
scene.render.resolution_x = 2048
scene.render.resolution_y = 2048

# Render
bpy.ops.render.render(write_still=True)
print("✅ Rendered: security-lock-hero.png")
```

**→ PNG transparente a `/tmp/renders/`**

---

### STAGE 3: OPTIMIZE & COMPRESS

**Input:** PNG 2K (5-10MB)
**Output:** WebP 800x800 (<50KB) + GLTF compressed

**Paso 3A: Image Optimization**
```bash
# PNG → WebP (lossy, quality=80)
ffmpeg -i /tmp/renders/security-lock-hero.png \
  -quality 80 \
  /tmp/optimized/security-lock-hero.webp

# Resize para thumbnails
ffmpeg -i /tmp/optimized/security-lock-hero.webp \
  -s 800x800 \
  /tmp/optimized/security-lock-800x800.webp

# Size check
du -h /tmp/optimized/security-lock-*.webp
# Expected: ~40KB total
```

**Paso 3B: GLTF Compression (if using 3D in website)**
```bash
# Instalar: npm install -g gltf-pipeline

gltf-pipeline \
  -i /tmp/blender-imports/security-lock-candidate.glb \
  -o wp-content/themes/gano-child/assets/3d/icons/security-lock-v1.glb \
  --draco

# Comprobar peso
ls -lh wp-content/themes/gano-child/assets/3d/icons/security-lock-v1.glb
# Expected: <200KB
```

**→ Assets a `wp-content/themes/gano-child/assets/`**

---

### STAGE 4: VERSION & DEPLOY

**Paso 4A: Update Manifest**
```json
{
  "assets": [
    {
      "id": "security-lock-v1.0",
      "name": "Candado Digital - Zero Trust",
      "description": "Icono 3D: candado minimalista con líneas de código",
      "file_image": "wp-content/themes/gano-child/assets/images/icons/security-lock-hero.webp",
      "file_3d": "wp-content/themes/gano-child/assets/3d/icons/security-lock-v1.glb",
      "source": "hunyuan3d | sketchfab:5xyz789 | polyhaven",
      "created": "2026-04-03",
      "created_by": "claude",
      "render_settings": {
        "resolution": "2048x2048",
        "engine": "cycles",
        "samples": 256
      },
      "optimizations": ["webp-lossy-80", "glb-draco"],
      "usage": ["hero-section", "feature-cards", "comparison-table"],
      "lighthouse_impact": "pending",
      "last_validated": "2026-04-03"
    }
  ]
}
```

**Paso 4B: Git Commit**
```bash
cd /path/to/gano.digital

git add \
  wp-content/themes/gano-child/assets/images/icons/security-lock-hero.webp \
  wp-content/themes/gano-child/assets/3d/icons/security-lock-v1.glb \
  3d-assets-manifest.json

git commit -m "feat(3d): security-lock icon v1.0 - zero-trust hero asset

- Generated via Hunyuan3D
- Rendered CYCLES 256 samples, transparent BG
- Optimized: WebP 80q (~40KB), GLBTF Draco (<200KB)
- Usage: hero section, feature grid, comparison
- Lighthouse validation pending"

git push origin main
```

**→ Assets en producción vía GitHub + cPanel deploy**

---

### STAGE 5: ELEMENTOR INTEGRATION & QA

**Paso 5A: Add to Elementor Custom Widget**
```html
<!-- En Elementor → Custom HTML -->
<div class="gano-3d-asset security-lock">
  <img
    src="/wp-content/themes/gano-child/assets/images/icons/security-lock-hero.webp"
    alt="Candado Digital - Seguridad Zero Trust"
    loading="lazy"
    width="400"
    height="400"
  />
</div>

<style>
  .gano-3d-asset {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
  }

  .gano-3d-asset img {
    max-width: 100%;
    height: auto;
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
</style>
```

**Paso 5B: Lighthouse Validation**
```bash
lighthouse https://gano.digital \
  --preset=desktop \
  --output-path=./reports/lighthouse-gano-3d.html

# Validar:
# - Performance >90
# - LCP <2.5s
# - CLS <0.1
```

**→ Asset live + validado**

---

## 🎯 Ejecución Automática (Queue)

**File:** `.github/agent-queue/tasks-blender-pipeline.json`

```json
{
  "batch_id": "blender-wave-001",
  "description": "Generar 5 assets 3D iniciales para Gano Digital",
  "tasks": [
    {
      "asset_id": "security-lock-hero",
      "description": "Candado digital - Zero Trust Security",
      "stages": ["search", "generate", "blend", "render", "optimize", "deploy"],
      "timeline_hours": 2,
      "assignee": "claude-blender"
    },
    {
      "asset_id": "speed-lightning-hero",
      "description": "Rayo velocidad - Performance",
      "stages": ["search", "generate", "blend", "render", "optimize", "deploy"],
      "timeline_hours": 2
    },
    {
      "asset_id": "datacenter-server",
      "description": "Datacenter NVMe moderno",
      "stages": ["search", "generate", "blend", "render", "optimize", "deploy"],
      "timeline_hours": 3
    },
    {
      "asset_id": "dds-protection",
      "description": "Escudo DDoS animado",
      "stages": ["search", "generate", "blend", "render", "animate", "optimize", "deploy"],
      "timeline_hours": 3
    },
    {
      "asset_id": "hero-globe-colombia",
      "description": "Globo terráqueo Colombia destacada",
      "stages": ["search", "generate", "blend", "render", "optimize", "deploy"],
      "timeline_hours": 2.5
    }
  ],
  "dependencies": ["SSH validated", "Blender daemon running"]
}
```

---

## 🚀 Self-Improvement Loop

Cada asset generado mejora mis patrones:

1. **Prompts más precisos:** Documentar qué prompts en Hunyuan3D generan mejor resultado
2. **Lighting presets:** Crear biblioteca de setups luz profesionales
3. **Optimization benchmarks:** Tracking de peso vs calidad
4. **Elementor patterns:** Reusable snippets para integración
5. **Lighthouse data:** Correlacionar formato/peso con performance

**Storage:** `.gano-skills/gano-blender-to-website-pipeline/LEARNED_PATTERNS.md`

---

## 📊 Datasets Generados

**Post-completitud (mes 1 Blender):**
- ✅ 12+ assets 3D profesionales
- ✅ 50+ Blender Python snippets
- ✅ 20+ Elementor integration patterns
- ✅ 10+ Lighthouse optimization benchmarks
- ✅ 5+ animation templates

**Utilidad:** Reducir tiempo generación asset futuro de 2h → 30min

---

## 🔗 Related Skills

- `.gano-skills/gano-blender-3d-assets-generator/` — Asset creation
- `.gano-skills/gano-cpanel-ssh-management/` — Deploy infrastructure
- `.gano-skills/gano-content-audit/` — Copy + imagery audit
- `.github/workflows/04-deploy.yml` — Auto-deploy to server

---

**Generated:** 2026-04-03 by Claude
**Status:** Ready for Blender daemon + first asset batch
