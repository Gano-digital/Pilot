# SOTA multimedia production kit (abril 2026)

## 1) Inventario validado de referencias

### Ruta validada

- `C:\Users\diego\Downloads\stitch\stitch\index.html`
- `C:\Users\diego\Downloads\stitch\stitch\prototypes\obsidian-prism\code.html`
- `C:\Users\diego\Downloads\stitch\stitch\prototypes\obsidian-prism\DESIGN.md`
- `C:\Users\diego\Downloads\_ARCHIVO_WEB\Gano_Digital_Showcase.html`

### Prototipos detectados en `stitch/prototypes`

- `obsidian-prism`
- `cybernetic-hero`
- `nvme-fractal-tree`
- `nvme-geodesic-sphere-v2`
- `nvme-modular-cube`
- `nvme-kinetic-eruption`
- `nvme-geodesic-sphere-v1`
- `nvme-geodesic-geometry`

## 2) Uso recomendado por bloque de pagina

| Bloque | Recurso multimedia sugerido | Objetivo |
|---|---|---|
| Hero | Visual insignia tipo prism/constellation | Comunicar sofisticacion tecnica sin saturar copy |
| Franja de confianza | Iconografia + micro-animaciones sutiles | Reforzar credibilidad operacional |
| Propuesta de valor | Cards con icono tecnico y micro-glow | Claridad de beneficios por pilar |
| Ecosistemas | Mini visuales por plan (rendimiento, seguridad, soporte) | Mejor escaneo comercial |
| CTA final | Glow central + sello de garantia | Cierre de conversion elegante |

## 3) Shotlist multimedia (produccion)

### Hero (prioridad P0)

1. `hero-prism-static-1920.webp`
   - relacion 16:9, peso objetivo <= 250 KB
2. `hero-prism-mobile-900.webp`
   - relacion 4:5, peso objetivo <= 180 KB
3. `hero-prism-ambient-loop.webm` (opcional)
   - 6-8 s, loop limpio, sin audio, <= 1.2 MB

### Ecosistemas (P1)

4. `plan-startup-node.webp`
5. `plan-prime-fortress.webp`
6. `plan-bastion-sota.webp`
   - todas con composicion consistente y gradiente de marca

### Social / SEO (P1)

7. `og-home-soberania-1200x630.webp`
8. `og-ecosistemas-1200x630.webp`
9. `og-sota-hub-1200x630.webp`

### Soporte comercial (P2)

10. `chat-agent-avatar-dark.webp`
11. `migration-trust-badge.svg`

## 4) Especificaciones tecnicas

- Formato preferente: WebP para raster, SVG para iconografia simple.
- Incluir `width` y `height` explicitos para evitar CLS.
- `loading="lazy"` en todo lo no-LCP.
- `fetchpriority="high"` solo para visual principal del hero.
- Versionado de archivo por fecha o hash para cache control.

## 5) Guia de estilo multimedia

- Fondo base nocturno (`#0b1326` o cercano), acentos en lila/cian/dorado.
- Evitar composiciones "stock genéricas"; priorizar geometria/infraestructura abstracta.
- Mantener lenguaje visual "precision enterprise": menos ruido, mas estructura.
- En piezas con texto incrustado: maximo 8-10 palabras.

## 6) Riesgos y mitigaciones

| Riesgo | Mitigacion |
|---|---|
| Peso alto en hero | Entregar variante estatica + loop opcional condicional |
| Inconsistencia cromatica entre assets | Usar LUT/preset de color unico para toda la serie |
| Saturacion de motion en mobile | Fallback estatico + `prefers-reduced-motion` |
| Divergencia entre diseño y codigo | Validar cada pieza contra clases canonicas (`gano-km-*`) |

## 7) Checklist de entrega multimedia

- [ ] Hero desktop/mobile exportados y comprimidos
- [ ] OGs listos para Rank Math / social preview
- [ ] Assets nombrados con convencion consistente
- [ ] Alt text redactado en es-CO por pieza clave
- [ ] Prueba de carga en mobile (3G Fast emulado)
