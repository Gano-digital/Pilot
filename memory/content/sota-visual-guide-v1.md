# Guia visual SOTA v1

## Objetivo

Mantener una sola capa visual para Theme + Elementor, evitando deriva de estilos por pagina.

## Tokens base

- Color primario operativo: `--gano-blue`
- Color de acento premium: `--gano-gold`
- Color de estado positivo: `--gano-green`
- Fondo oscuro principal: `--gano-dark`
- Tipografia heading: `--gano-font-heading`
- Tipografia body: `--gano-font-body`

## Componentes canónicos

1. Hero: `gano-sota-hero` + `gano-sota-hero__title` + `gano-sota-hero__cta-row`
2. Surface glass: `gano-sota-glass-card`
3. Status strip: `gano-sota-status-strip` + `gano-sota-status-strip__item`
4. Value cards: `gano-sota-cards-grid` + `gano-sota-card`
5. Quiz shell: `gano-sota-quiz-shell`

## Reglas de uso

- No usar estilos inline para layout estructural.
- No introducir Tailwind CDN ni utilidades ad-hoc en templates productivos.
- Cualquier nueva variacion visual se implementa como clase reusable.
- Mantener copy en espanol es-CO y claims verificables.

## Guardrails performance

- Evitar blur extremo y animaciones continuas en mobile.
- Respetar `prefers-reduced-motion`.
- Priorizar imagenes locales optimizadas (webp/jpg) y `loading="lazy"` fuera de LCP.

## Mapeo rapido por pagina

- Home (Elementor): hero + pilares + status strip + CTA final.
- Ecosistemas / Shop: cards premium + estado comercial + CTAs Reseller.
- Contacto: formulario y bloque lateral en estilo canónico.
- Diagnóstico: quiz shell + barra de progreso + resultado accionable.
- SOTA Hub / landings: hero oscuro + grid de artículos + CTA comercial.
