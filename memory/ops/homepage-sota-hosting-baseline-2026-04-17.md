# Baseline técnico hosting — Homepage SOTA

Fecha: 2026-04-17  
Fuente: verificación directa por SSH (`ssh gano-godaddy`)

## Evidencia de entorno

- `PHP 8.3.30 (cli)` con OPcache e ionCube.
- `Linux 4.18.0-553.53.1.lve.el8.x86_64`.
- `WP-CLI 2.8.1` operativo en servidor.
- Motor SQL: `MariaDB 10.6.24`.

## Decisión de budgets para homepage

Presupuesto definido para balance visual-performance (objetivo acordado `<= 3.0s`):

- **LCP objetivo p75:** `<= 3.0s` (móvil y desktop).
- **Payload inicial homepage:** `<= 1.2 MB` transferido.
- **JS no crítico en first view:** `<= 180 KB` gzip.
- **Animación hero:** sutil, degradable por `prefers-reduced-motion`.
- **Canvas/3D pesado:** desactivado en hero principal mientras no aporte conversión directa.

## Implicaciones para implementación

1. Evitar inline JS/CSS para facilitar control de caché y mantenimiento.
2. Reutilizar tokens semánticos del tema y capas de superficie ya existentes.
3. Priorizar animaciones de bajo costo (gradientes/micro-movimiento) sobre escenas GPU complejas.
4. Mantener catálogo inteligente y comparador, con simplificación visual en móvil.

## Riesgos controlados

- Riesgo de sobreanimación en hosting gestionado: mitigado con motion sutil + fallback.
- Riesgo de drift visual entre secciones: mitigado con escala unificada de spacing y superficies.
- Riesgo de sobrecarga de scripts: mitigado separando `gano-homepage.js` del template.
