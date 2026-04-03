# Gano Digital — Brief maestro oleada 3 (marca, UX, comercial, activos)

Documento de insumos para alinear **contenido, diseño, arquitectura de información y narrativa comercial** con lo que el stack permite hoy (WordPress + Elementor + Reseller GoDaddy). Las tareas en `.github/agent-queue/tasks-wave3.json` desglosan esto en PRs/issues para Copilot y revisores humanos.

**Última actualización:** Abril 2026.

---

## 1. Decisiones de marca (recomendadas)

| Dimensión | Propuesta | Evitar |
|-----------|-----------|--------|
| **Posición** | Hosting y ecosistemas WordPress **de alto rendimiento** para Colombia/LATAM, con narrativa técnica creíble (SOTA = excelencia verificable, no buzzword vacío). | Claims imposibles de auditar (“100% uptime eterno”, “más rápido que X” sin métrica). |
| **Personalidad** | Precisa, en calidez latina, **ingeniero que explica bien**; confianza sin arrogancia. | Jerga vacía en inglés mezclada sin necesidad; tono alarmista (ya corregido en chat). |
| **Honestidad Reseller** | “Ecosistemas aprovisionados vía **programa de reseller de clase mundial**; facturación y soporte de producto según términos del proveedor de infraestructura.” | Simular que Gano es datacenter propio si no lo es. |
| **Nombre de planes** | Mantener **Núcleo Prime**, **Fortaleza Delta**, **Bastión SOTA** como familia coherente (escala de complejidad / soberanía percibida). | Renombrar sin actualizar CTAs, schema JSON-LD y Reseller IDs. |

---

## 2. Voz y tono (español)

- **Tú / usted:** usar **usted** en legal y checkout; **tú** permitido en marketing hero si el diseño es cercano — **no mezclar en la misma sección**.
- **Oraciones:** preferir verbo activo (“Incluye backups”, no “Es incluido”).
- **Cifras:** solo SLA y métricas **reales** o rangos prudente (“hasta”, “objetivo”) — alineado con `TASKS.md` y skill `gano-content-audit`.
- **CTA primarios:** verbos de valor: *Ver ecosistemas*, *Elegir plan*, *Hablar con ventas*; secundarios: *Comparar*, *Ver especificaciones*.

---

## 3. Sistema visual (extensión sobre `gano-child`)

- **Base actual:** tokens `--gano-gold`, utilidades `gano-el-*`, Kinetic Monolith + GSAP donde ya exista.
- **Ampliar sin romper:** una escala tipográfica documentada (display / H1–H3 / body / small) en `:root` o en doc + comentarios en `style.css`.
- **Contraste:** objetivo **WCAG AA** mínimo en texto sobre fondos oscuros del mockup; revisar oro sobre negro.
- **Motion:** micro-interacciones **< 300 ms** en UI; scroll storytelling **con `prefers-reduced-motion: reduce`** respetado (CSS + GSAP donde aplique).
- **3D:** no obligatorio para lanzamiento. Si se usa: **Spline/WebGL embebido** o **video loop** como fallback; priorizar **Lottie JSON** para iconografía animada ligera. Cada asset 3D debe tener **peso y LCP** documentados antes de producción.

---

## 4. Arquitectura de información (IA) — esqueleto sugerido

| Nivel | Rutas / plantillas | Rol |
|-------|-------------------|-----|
| **Home** | `/` | Narrativa completa + CTAs a shop/ecosistemas. |
| **Conversión** | `/ecosistemas` o shop premium | Catálogo SOTA + carrito Reseller. |
| **Confianza** | `/nosotros`, SLA/legal | Equipo/manifiesto solo si hay contenido real. |
| **Soporte** | Contacto, FAQ | Reducir fricción pre-venta; enlaces a políticas. |
| **Contenido largo** | Pilares SEO (20 páginas borrador) | Hub temático, enlaces internos a planes relevantes. |

**Profundidad:** máximo **3 clics** de home a “comprar” o “contacto comercial”.

---

## 5. UX — checklist de dominio (mastery práctico)

1. **Jerarquía visual:** un foco por viewport en hero; no competir 3 CTAs del mismo peso.
2. **Consistencia:** mismos patrones de tarjeta en pilares y en shop.
3. **Feedback:** hover/focus visibles; formularios con estados error/éxito.
4. **Performance percibida:** skeleton o placeholder de altura fija donde LCP sea imagen.
5. **Móvil:** menú, CTA sticky opcional, sin popups que tapen el CTA principal en primer pantallazo.
6. **Confianza:** logos de pago/métodos **solo si aplican** al flujo Reseller real.

---

## 6. Narrativa comercial (mercado Colombia / LATAM)

- **Dolor:** hosting lento, soporte genérico, renovaciones opacas, seguridad reactiva.
- **Alivio:** ecosistemas claros, lenguaje técnico traducido a negocio, camino de compra explícito.
- **Diferenciación honesta:** experiencia de marca, curaduría de producto, acompañamiento **en español** y foco WordPress — sin inventar data centers propios.
- **Objeciones:** precio → valor por ecosistema; confianza → transparencia Reseller + políticas; técnico → specs y FAQ schema.

---

## 7. Activos — especificación mínima

| Tipo | Uso | Notas |
|------|-----|--------|
| **Raster** | Hero, pilares, OG images | WebP + fallback; `width`/`height` para CLS; nombres `gano-{sección}-{variante}.webp`. |
| **SVG** | Iconos UI | optimizados; `aria-hidden` si decorativos. |
| **Video** | Loop corto hero opcional | muted, playsinline, poster estático; no autoplay con sonido. |
| **3D** | Opcional fase 2+ | glTF/USDZ solo si hay presupuesto y métricas; no bloquear MVP. |

---

## 8. Elementor — arquitectura de página

- **Contenedores:** un contenedor = una intención (no anidar 5 secciones en una).
- **Clases utilitarias:** seguir `memory/content/elementor-home-classes.md`; nuevas clases documentar ahí antes de CSS.
- **Globales:** colores/tipografías en kit; evitar overrides locales que rompan consistencia.
- **Plantillas:** Header/Footer/404 en Theme Builder; home como plantilla dedicada si simplifica mantenimiento.

---

## 9. Extensiones Cursor (opcionales)

- **Markdown All in One** — navegación en `memory/` y briefs.
- **YAML** — workflows y `labeler`.
- **PHP Intelephense** — tema hijo y MU-plugins.
- **ESLint** — si amplías GSAP/JS en `gano-child`.

No son obligatorias para cerrar la web.

---

## 10. Cómo usar este brief con la oleada 3

1. Ejecutar **Seed Copilot task queue** con `queue_file: tasks-wave3.json` y `scope: all` (o filtrar por `docs` / `theme` / `content_seo` / `commerce` / `coordination`).
2. Pegar prompt masivo desde `.github/prompts/copilot-bulk-assign.md`.
3. Los PRs deben **referenciar** este archivo o fragmentar contenido en `memory/content/*` para uso en Elementor.

---

_Fin del brief maestro oleada 3._
