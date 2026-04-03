# Arco narrativo homepage — Oleada 3 (Abril 2026)

Documento de arquitectura de información para la homepage de Gano Digital.  
Define el **orden canónico de secciones**, su justificación narrativa (marco AIDA adaptado) y el mapeo a bloques existentes en Elementor.

Copy completo de cada sección → [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md)  
Clases CSS Elementor → [`elementor-home-classes.md`](elementor-home-classes.md)

---

## Marco narrativo: AIDA + Prueba social

| Paso | Etapa | Pregunta que responde |
|------|-------|-----------------------|
| 1 | **Atención** | ¿Por qué debo mirar esto? |
| 2 | **Interés** | ¿Qué hace diferente a Gano Digital? |
| 3 | **Prueba social** | ¿Otros confían? ¿Hay evidencia real? |
| 4 | **Deseo** | ¿Qué gano concretamente si elijo este servicio? |
| 5 | **Acción** | ¿Cómo empiezo ahora? |

---

## Orden canónico de secciones

### Sección 1 — Hero (Atención)

**Etapa AIDA:** Atención  
**Objetivo:** Capturar en menos de 3 segundos; comunicar propuesta de valor y categoría.  
**Justificación:** El visitante llega con escepticismo. El H1 debe resolver "¿esto es para mí?" antes de que haga scroll. Título técnico + subheadline que nombra diferenciadores clave (NVMe, soporte es-CO, COP).

**Contenido:** Ver [Hero en `homepage-copy-2026-04.md`](homepage-copy-2026-04.md#hero).  
**Elementor:** contenedor con clase `gano-el-stack`; columna copy con `gano-el-layer-top`; columna imagen/shape con `gano-el-layer-base`.  
**UX:** un solo CTA primario visible en primer pantallazo; CTA secundario por debajo del fold en móvil.

---

### Sección 2 — Cuatro pilares (Interés)

**Etapa AIDA:** Interés  
**Objetivo:** Traducir promesa técnica a beneficios tangibles antes del primer CTExit.  
**Justificación:** Tras el hero el visitante pregunta "¿cómo exactamente?". Cuatro cards condensan los pilares que diferencian a Gano (velocidad, seguridad hardened, Zero-Trust, Edge) sin exigir scroll profundo.

**Contenido:** Ver [Cuatro pilares en `homepage-copy-2026-04.md`](homepage-copy-2026-04.md#cuatro-pilares-reemplaza-lorem-en-cada-card).  
**Elementor:** cada tarjeta con clase `gano-el-pillar`.  
**UX:** rejilla 2×2 en escritorio, apiladas en móvil; íconos SVG `aria-hidden` si son decorativos.

---

### Sección 3 — Un socio tecnológico (Interés / Prueba social)

**Etapa AIDA:** Interés → Prueba social  
**Objetivo:** Establecer credibilidad sin mentir; posicionar a Gano como aliado técnico, no como commodity.  
**Justificación:** Antes de pedir una decisión de compra, el visitante necesita confiar en la empresa. Este bloque presentacional (misión, bullets diferenciadores) cumple el rol de "manifiesto" y sirve como puente antes de métricas y ecosistemas.

**Contenido:** Ver [Bloque "Un socio tecnológico" en `homepage-copy-2026-04.md`](homepage-copy-2026-04.md#bloque-un-socio-tecnológico).  
**Elementor:** contenedor con clase `gano-el-prose-narrow` en el texto.  
**Nota:** Honestidad Reseller — no afirmar datacenter propio; resaltar acompañamiento, idioma y operación en COP.

---

### Sección 4 — Métricas / Franja de confianza (Prueba social)

**Etapa AIDA:** Prueba social  
**Objetivo:** Anclar promesas con cifras o indicadores verificables.  
**Justificación:** Las métricas concretas (disponibilidad objetivo, almacenamiento NVMe, soporte 24/7) convierten el discurso en evidencia. **Solo incluir cifras que se puedan respaldar con SLA real o rango prudente.**

**Contenido:** Ver [Métricas / franjas en `homepage-copy-2026-04.md`](homepage-copy-2026-04.md#métricas--franjas-sin-inflar-cifras).  
**Elementor:** fila con clase `gano-el-metrics`; cada celda métrica con `gano-el-metric`.  
**Evitar:** números inflados, logos de clientes sin contrato, cifras de "millones de usuarios".

---

### Sección 5 — Ecosistemas / Planes (Deseo)

**Etapa AIDA:** Deseo  
**Objetivo:** Concretar la oferta; el visitante elige su arquitectura.  
**Justificación:** Con credibilidad establecida, el visitante está listo para ver precios y planes. Presentar la familia de ecosistemas (Núcleo Prime → Fortaleza Delta → Bastión SOTA) en orden ascendente de complejidad/soberanía genera up-sell natural.  

**Contenido:** Copy de planes según catálogo WooCommerce / Reseller Store. No duplicar en este MD.  
**Elementor:** plantilla de tarjeta consistente con las del bloque pilares; CTA por tarjeta ("Elegir este plan").  
**Ruta:** enlace a `/ecosistemas` o shop — máximo 3 clics de home a checkout.  
**Nota:** Precios en COP; formulación "desde $X/mes" con enlace a página de detalle.

---

### Sección 6 — CTA final (Acción)

**Etapa AIDA:** Acción  
**Objetivo:** Cierre de página con una sola pregunta retórica y un botón de conversión principal.  
**Justificación:** Un CTA de cierre recoge a los visitantes que hicieron scroll completo pero no actuaron antes. Ofrece una última oportunidad de conversión sin fricciones adicionales.

**Contenido:** Ver [CTA final en `homepage-copy-2026-04.md`](homepage-copy-2026-04.md#cta-final).  
**Elementor:** botón principal ("Elegir mi arquitectura") con fondo `--gano-orange`; sin distractores adicionales.  
**Accesibilidad:** `focus-visible` en el botón; contraste mínimo WCAG AA sobre fondo oscuro.

---

## Resumen visual del flujo

```
┌───────────────────────────────────────────────────────┐
│ 1. HERO           → Atención          (primer scroll) │
│ 2. PILARES (×4)   → Interés           (cards)         │
│ 3. SOCIO TECN.    → Interés/Confianza (manifiesto)    │
│ 4. MÉTRICAS       → Prueba social     (cifras reales) │
│ 5. ECOSISTEMAS    → Deseo             (planes/precios)│
│ 6. CTA FINAL      → Acción            (cierre)        │
└───────────────────────────────────────────────────────┘
```

---

## Reglas de coherencia

| Regla | Detalle |
|-------|---------|
| **Una CTA dominante por viewport** | No competir 3 botones del mismo peso visual en pantalla. |
| **Tono uniforme** | Tú en marketing/hero; usted en legal y checkout. No mezclar en la misma sección. |
| **Cifras prudentes** | Solo SLAs reales o formulaciones "hasta / objetivo". Ver [Métricas en copy](homepage-copy-2026-04.md#métricas--franjas-sin-inflar-cifras). |
| **Honestidad Reseller** | No simular datacenter propio; mencionar programa de reseller de clase mundial cuando aplique. |
| **Profundidad máxima** | 3 clics de home a checkout o contacto comercial. |
| **Móvil primero** | Menú, CTA sticky opcional; sin popups que tapen el CTA en primer pantallazo. |

---

## Checklist de validación (antes de publicar)

- [ ] H1 visible sin scroll en 375 px (móvil) y 1280 px (escritorio).
- [ ] Copy de Lorem / placeholder reemplazado en todos los bloques (ver `elementor-home-classes.md`).
- [ ] Métricas revisadas con datos reales o eliminadas del bloque.
- [ ] Ecosistemas/planes con precios actualizados en COP.
- [ ] CTA primario tiene foco visible (`focus-visible`) y contraste WCAG AA.
- [ ] `prefers-reduced-motion` respetado en animaciones GSAP / scroll reveals.
- [ ] OG image configurada en Rank Math para la homepage.
- [ ] Páginas en borrador no compiten SEO con la home (ver nota en `homepage-copy-2026-04.md`).

---

_Generado en oleada 3 — Abril 2026. Mantener sincronizado con `homepage-copy-2026-04.md` y `elementor-home-classes.md`._
