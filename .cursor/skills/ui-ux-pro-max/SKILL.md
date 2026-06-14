# Skill: UI/UX Pro Max (Bridge)

## Metadata
- **Name**: ui-ux-pro-max
- **Description**: Puente local para usar el motor de diseno UI/UX Pro Max en flujos Cursor + GitHub del repo Gano Digital.
- **Scope**: Workspace (`Gano-digital/Pilot`)
- **Source**: `nextlevelbuilder/ui-ux-pro-max-skill`
- **Local source path**: `external/ui-ux-pro-max-skill/src/ui-ux-pro-max`

## Activation Signals
Activa este skill cuando el usuario pida:
- "landing", "homepage", "redesign", "mejora UX", "accesibilidad", "design system"
- "paleta", "tipografia", "estilo visual", "componentes UI", "dashboard"
- "auditoria UX/UI", "anti-patrones", "mejora conversion"

## What This Skill Adds
1. Recomendaciones por industria/producto para UI de alta calidad.
2. Generacion de design system (patron, estilo, colores, tipografia, anti-patrones).
3. Checklist de accesibilidad y consistencia visual para entrega.
4. Soporte de guidance por stack, incluyendo web/React/Next/Laravel y mas.

## Required First Step
Antes de usar comandos, confirma que existe:
- `external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py`

Si no existe, clonar:
```bash
gh repo clone nextlevelbuilder/ui-ux-pro-max-skill external/ui-ux-pro-max-skill
```

## Command Workflow

### 1) Generate design system (recommended first)
```bash
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "hosting wordpress colombia premium" --design-system -p "Gano Digital"
```

### 2) Deep dive by domain
```bash
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "conversion trust authority" --domain landing
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "accessible dark mode contrast" --domain ux
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "enterprise premium serif sans pairing" --domain typography
```

### 3) Validate implementation direction by stack
```bash
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "cta hierarchy spacing cards" --stack html-tailwind
python external/ui-ux-pro-max-skill/src/ui-ux-pro-max/scripts/search.py "component consistency and readability" --stack nextjs
```

## Gano-Specific Guardrails
- Mantener compatibilidad con `gano-child` + Elementor (sin introducir frameworks no solicitados).
- Priorizar tokens/variables CSS existentes en vez de estilos hardcodeados.
- No romper narrativa de marca (manifiesto tecnico, tono premium, soberania digital).
- Toda recomendacion debe pasar control de accesibilidad (foco, contraste, aria, reduced-motion).

## Deliverable Format Expected
Cada uso de este skill debe terminar con:
1. **System recommendation** (patron + estilo + paleta + tipografia).
2. **3-7 decisiones concretas** aplicables al archivo/cambio actual.
3. **Anti-patrones a evitar** para el contexto.
4. **Checklist rapido QA UX** antes de merge.

## Maintenance
Para actualizar el motor:
```bash
cd external/ui-ux-pro-max-skill
git pull
```
