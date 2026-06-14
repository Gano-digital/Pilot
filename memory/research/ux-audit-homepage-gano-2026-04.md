# Auditoría UX + plan de formalización — Homepage `gano.digital` (abr 2026)

**Objeto**: `wp-content/themes/gano-child/front-page.php` (homepage sin Elementor).  
**Meta**: convertir el home en una propuesta coherente (mensaje → prueba → valor → objeciones → acción), manteniendo el aesthetic SOTA y respetando rendimiento y accesibilidad (motion).

---

## 1) Diagnóstico rápido (estado actual)

- **Jerarquía de conversión difusa**: hay **dos CTAs primarios** en hero (Ecosistemas + Vista SOTA) + links adicionales (“primera compra”), y además CTAs repetidos más abajo. Esto aumenta carga cognitiva.
- **Orden de secciones**: se mezclan navegación/atlas, registro, dominio, catálogo, valor, métricas, lead magnet y cierre. Falta una “storyline” lineal.
- **Copy inconsistente**: frases duplicadas, capitalización irregular, y descripciones que repiten la misma pregunta en minúsculas.
- **Prueba/credenciales**: hay señales (métricas, CTA icons, socio tecnológico), pero no están “pegadas” al primer momento (primeros 5 segundos) con una lectura inmediata.
- **Accesibilidad y motion**: la estética incluye animación/glitch/scanlines (HUD). Debe existir una ruta clara para `prefers-reduced-motion` y evitar flashes excesivos.

---

## 2) Heurísticas y referencias (externas)

- **Homepages deben ser simples y explicar propósito rápidamente**: NN/g enfatiza comunicar “quién eres y qué haces”, mostrar ejemplos y guiar acciones sin saturar la portada.  
  Fuente: `https://www.nngroup.com/articles/homepage-design-principles`

- **Motion y accesibilidad**: evitar flashes > 3 por segundo, dar mecanismos de pausar/ocultar si el movimiento es no esencial y respetar `prefers-reduced-motion`.  
  Fuente: `https://web.dev/learn/accessibility/motion/`

> Nota: se prioriza NN/g y `web.dev` como fuentes durables; guías “2026 landing page” privadas se toman como apoyo, no como autoridad principal.

---

## 3) Hallazgos priorizados (P0/P1/P2)

### P0 — Conversión / claridad (impacto alto)
- **Un CTA principal dominante** en el hero (ej. “Abrir catálogo en Ecosistemas”).  
  - CTA secundario puede existir, pero visualmente subordinado y con texto que explique su rol (“Ver vista SOTA (demo)”).
- **Prueba inmediata**: una barra de 3 señales debajo del hero (NVMe / COP / 24/7) para disminuir incertidumbre sin scroll.
- **Eliminar repetición de CTAs**: mantener 1 CTA de “registro/acompañamiento” en el tramo medio (cuando ya hay valor + contexto), no pegado al hero.

### P1 — Estructura y contenido (impacto medio)
- **Reordenar narrativa**:
  1) Hero (promesa + CTA)  
  2) Señales de confianza (proof bar / trust bar)  
  3) “Catálogo comercial” (qué puede hacer aquí)  
  4) “4 pilares” (por qué Gano)  
  5) “Compromisos operativos” (qué garantizamos)  
  6) Dominio (tarea específica)  
  7) Lead magnet (captura)  
  8) Cierre (CTA final)  
  9) Socio + métricas (respaldo)
- **Copy editorial**: uniformar tono (SOTA, Colombia, continuidad), evitar preguntas repetidas y asegurar microcopy útil.

### P2 — A11y / performance / tech debt (impacto variable)
- **Reducir motion**: asegurar que animaciones “decorativas” puedan apagarse (CSS `prefers-reduced-motion`) y que no haya flicker peligroso.
- **INP/LCP**: mantener el hero liviano (imagen optimizada, CSS sin layout thrash, JS diferido).
- **Idioma**: si el sitio está en `en-US`, corregirlo en WP settings; si no es posible, usar un override técnico (pero preferible hacerlo en configuración).

---

## 4) Plan de ejecución (lo que voy a implementar en el child theme)

### Cambios inmediatos (ya aplicados / aplicando)
- **Arreglar copy default** del CTA de registro (capitalización y repetición).
- **Agregar “hero proof bar”** debajo del CTA del hero (3 señales: NVMe / COP / 24/7).
- **Eliminar CTA duplicado** de registro demasiado cerca del hero.

### Cambios siguientes (en este mismo ciclo)
- **Ajustar texto de CTAs** del hero para un propósito principal (catálogo) y secundario subordinado (vista SOTA).
- **Reordenar secciones** para una storyline más lineal (sin perder módulos existentes).
- **Checklist final**: verificar headings, anchors (`#ecosistemas`), y que `prefers-reduced-motion` mantenga una experiencia estable (sin loops agresivos).

---

## 5) Test plan (manual)

- **Mobile** (<= 390px): CTA primario visible sin scroll; proof bar se adapta; no hay saltos de layout.
- **A11y**: navegación por teclado, foco visible, labels del formulario, `aria-live` en status.
- **Motion**: con `prefers-reduced-motion: reduce` las animaciones decorativas bajan o se apagan.
- **Rutas**: enlaces a `Ecosistemas`, `shop-premium`, `comenzar-aqui` correctos.

