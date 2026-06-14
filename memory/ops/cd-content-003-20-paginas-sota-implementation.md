# Tarea cd-content-003 — Wave contenidos: 20 páginas SOTA (arquitectura, seguridad, IA, etc.)

**ID:** `cd-content-003`  
**Prioridad:** P2  
**Requiere humano:** SÍ (Diego revisa + ajusta en Elementor post-import)  
**Generado:** 2026-04-19  
**Estado:** Listo para ejecución (no bloqueante)

---

## Objetivo

Implementar **20 páginas SOTA** (state-of-the-art) de estrategia de contenido vía plugin **`gano-content-importer`**. Cada página:

1. ✅ Tiene estructura canónica: Hero + Hook Box + Quote + 2–3 subsecciones + CTA final
2. ✅ Direcciona keyword específica (infraestructura, seguridad, IA, etc.)
3. ✅ Sigue guía de accesibilidad WCAG AA
4. ✅ Optimizada para Core Web Vitals
5. ✅ Contiene featured image + microcopy SEO

**Impacto:**
- Posicionamiento SEO + organic traffic para 20 nichos complementarios
- Enlazamiento interno: todas las páginas se citan entre sí (networkeffect)
- Content hubs: cada página puede agruparse en colecciones temáticas (5 categorías)
- Tiempo para Diego: **~30–45 min** (importar + revisión visual)

---

## 📋 Las 20 páginas SOTA — Mapeo completo

| # | Título | Slug | Categoría | Keyword primario | Secciones principales |
|---|--------|------|-----------|------------------|----------------------|
| 1 | Arquitectura NVMe: La Muerte del SSD Tradicional | `/sota-nvme-arquitectura` | **Infraestructura** | nvme hosting wordpress | Hero + Hook Box: Por qué NVMe + Subsección: Comparativa SSD vs NVMe + Técnico |
| 2 | Zero-Trust Security: El Fin de las Contraseñas | `/sota-zero-trust-security` | **Seguridad** | zero trust wordpress hosting | Hero + Hook Box: Principios Zero-Trust + Subsección: Implementación práctica |
| 3 | Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas | `/sota-ai-predictive-management` | **Inteligencia Artificial** | ai wordpress hosting colombia | Hero + Hook Box: ML en infraestructura + Subsección: Casos uso |
| 4 | Soberanía Digital en LATAM: Tus Datos, Tu Control | `/sota-soberania-digital-latam` | **Estrategia** | soberanía digital hosting colombia | Hero + Hook Box: Regulación LATAM + Subsección: Cumplimiento DIAN |
| 5 | Headless WordPress: La Velocidad Absoluta | `/sota-headless-wordpress` | **Infraestructura** | headless wordpress lcp | Hero + Hook Box: Arquitectura desacoplada + Subsección: Ventajas |
| 6 | Mitigación DDoS Inteligente: Firewall de Nueva Generación | `/sota-ddos-mitigation-ai` | **Seguridad** | ddos protection wordpress | Hero + Hook Box: Ataque DDoS + Subsección: Protección capa 7 |
| 7 | La Muerte del Hosting Compartido: El Riesgo Invisible | `/sota-shared-hosting-death` | **Infraestructura** | hosting compartido riesgos | Hero + Hook Box: Vulnerabilidades shared + Subsección: Alternativas |
| 8 | Edge Computing: Contenido a Cero Distancia del Cliente | `/sota-edge-computing` | **Infraestructura** | edge hosting cdm latam | Hero + Hook Box: Edge vs CDN + Subsección: Arquitectura |
| 9 | Green Hosting: Infraestructura Sostenible para tu Negocio | `/sota-green-hosting` | **Estrategia** | green hosting eco colombia | Hero + Hook Box: Sostenibilidad + Subsección: Certificaciones |
| 10 | Cifrado Post-Cuántico: La Bóveda del Futuro | `/sota-post-quantum-encryption` | **Seguridad** | post quantum cryptography | Hero + Hook Box: Amenaza cuántica + Subsección: Transición a PQC |
| 11 | CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo | `/sota-cicd-automated-deployment` | **Infraestructura** | ci cd wordpress deploy | Hero + Hook Box: Problemas manual deploy + Subsección: Pipelinesetup |
| 12 | Backups Continuos en Tiempo Real: Tu Máquina del Tiempo | `/sota-realtime-backups` | **Infraestructura** | backup wordpress hosting | Hero + Hook Box: RPO/RTO + Subsección: Inmutable backups |
| 13 | Skeleton Screens: La Psicología de la Velocidad Percibida | `/sota-skeleton-screens-ux` | **Rendimiento** | skeleton screens ux | Hero + Hook Box: Psychology percibida + Subsección: Implementación |
| 14 | Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral | `/sota-elastic-scaling` | **Infraestructura** | elastic scaling cloud | Hero + Hook Box: Traffic spike + Subsección: Auto-scaling |
| 15 | Self-Healing: El Ecosistema que se Cura Solo | `/sota-self-healing-infra` | **Infraestructura** | self healing infrastructure | Hero + Hook Box: Recuperación automática + Subsección: Observabilidad |
| 16 | Micro-Animaciones e Interacciones Hápticas: Diseño que se Siente | `/sota-microinteractions-haptics` | **Rendimiento** | micro animations web | Hero + Hook Box: Psicología interacción + Subsección: Casos uso |
| 17 | HTTP/3 y QUIC: El Protocolo que Rompe la Congestión | `/sota-http3-quic` | **Infraestructura** | http3 quic hosting | Hero + Hook Box: Limitaciones HTTP/2 + Subsección: Beneficios QUIC |
| 18 | Alta Disponibilidad (HA): La Infraestructura Indestructible | `/sota-high-availability-ha` | **Infraestructura** | high availability hosting | Hero + Hook Box: Definición HA + Subsección: 99.99% uptime |
| 19 | Analytics Server-Side: Privacidad, Velocidad y Datos Reales | `/sota-server-side-analytics` | `/sota-server-side-analytics` | **Estrategia** | server side analytics | Hero + Hook Box: Privacy-first + Subsección: Implementación |
| 20 | El Agente IA de Administración: Tu Infraestructura Habla Español | `/sota-ai-admin-agent-spanish` | **Inteligencia Artificial** | ai admin agent wordpress | Hero + Hook Box: Automatización IA + Subsección: Conversación natural |

---

## 🎨 Estructura canónica — Todas las 20 páginas

Cada página sigue este patrón (6 bloques):

### Bloque 1 — HERO
- H1: Título página
- Subheadline: propuesta valor en 1 línea (~80 caracteres)
- Imagen o shape hero (1200x600px optimizado)
- CTA primario: "Leer artículo" (scroll) O enlace externo según tema
- Token CSS: `--gano-fs-5xl` (H1), `--gano-fs-base` (body)

**Clase CSS:** `gano-el-hero-sota`

---

### Bloque 2 — HOOK BOX (cuadro diferencial)
- Fondo: `--gano-home-section--surface` (gradiente oscuro)
- Contenido: 2-3 párrafos de **por qué esto importa** + 1 bullet list
- Énfasis visual: border-left `--gano-gold` (3px)
- Accesibilidad: contraste texto WCAG AA 7:1 mínimo

**Clase CSS:** `gano-el-hook-box-sota`

---

### Bloque 3 — CITACIÓN / QUOTE
- Bloque con quote temático (relacionado a tema página)
- Attribution: autor + fuente
- Fondo: color secundario claro
- Tipo: 1-2 párrafos máximo

**Clase CSS:** `gano-el-quote-sota`

---

### Bloque 4 — CONTENIDO PRINCIPAL (2-3 subsecciones)

Estructura flexible por tema. Ejemplo:

**Subsección 4a: "¿Cuál es el problema?"**
- H2: Título
- Párrafo: 100-150 palabras
- Imagen (opcional): 800x400px
- Viñetas: máx 4 bullets

**Subsección 4b: "Cómo Gano Digital resuelve"**
- H2: Título
- Párrafo técnico: 100-150 palabras
- Tabla o lista de specs (si aplica)

**Clase CSS:** `gano-el-sota-section-[a/b/c]`

---

### Bloque 5 — ENLAZAMIENTO INTERNO (opcional)
- Sección "Temas relacionados"
- Links a 3-4 otras páginas SOTA (mismo catálogo)
- Formato: lista de enlances estilizados

**Clase CSS:** `gano-el-related-links-sota`

---

### Bloque 6 — CTA FINAL
- H2 retórica: ¿Listo para infraestructura SOTA?
- Botón primario: "Hablar con el equipo" → formulario contacto OR "Ver planes" → `/ecosistemas`
- Microcopy: "Respuesta en <2 horas" (si soporte disponible)

**Clase CSS:** `gano-el-cta-sota-final`

---

## 🖼️ Assets multimedia (por página)

Cada página necesita:

| Asset | Tipo | Tamaño | Optimización | Fuente |
|-------|------|--------|-------------|--------|
| Featured image (hero) | JPG/WebP | 1200x600px | <150 KB | Stock (Unsplash/Pexels) O generado |
| Diagram/illustration | SVG O PNG | 800x400px | <50 KB | Figma export OR AI generated |
| Optional: Inline chart | SVG | 600x300px | <30 KB | Chart.js export |

**Nota:** Todos los assets deben tener `alt` descriptivo en español.

---

## 📝 Copy por página — Placeholders y estructura

**[PENDIENTE DIEGO]:** Las siguientes secciones requieren copy refinado post-import.

Cada página debe reemplazar placeholders con:
1. H1 título (ya definido en tabla arriba)
2. Subheadline (propuesta valor, 1 línea)
3. Hook box: 2–3 párrafos + 1 lista bullets
4. Quote: 1 párrafo + attribution
5. Contenido subsecciones: párrafos técnicos + bullets
6. CTA final: botón + microcopy

**Checklist copy por página:**
- [ ] H1 contiene keyword primario
- [ ] Subheadline <= 80 caracteres
- [ ] Hook box sin Lorem Ipsum
- [ ] Quote verificable (si es de persona real)
- [ ] Subsecciones coherentes (sin off-topic)
- [ ] Links internos apuntan a URLs válidas
- [ ] CTA botón tiene destino funcional

---

## 🔄 Flujo de implementación (para Diego)

**Tiempo estimado:** 30–45 minutos  
**Requisitos:**
- Acceso wp-admin (gano.digital)
- Plugin `gano-content-importer` activo en lista de plugins
- Este documento a mano

---

### Paso 1: Validar plugin `gano-content-importer`

**1.1 — Accede wp-admin → Plugins:**

```
https://gano.digital/wp-admin/plugins.php
```

**1.2 — Busca "gano-content-importer":**

```
Ctrl+F → "gano-content-importer"
```

**Resultados esperados:**
- ✅ Aparece en lista **Inactive** (plugin desactivado) → Procede a 1.3
- ✅ Aparece en lista **Active** → Ya está listo, salta a Paso 2
- ❌ No aparece → Plugin no instalado (contactar soporte, ver Troubleshooting)

**1.3 — Si está Inactive, actívalo:**

1. Click en "Activate" junto al plugin
2. Espera ~3–5 segundos
3. Verifica que desaparece de "Inactive" y aparece en "Active"

---

### Paso 2: Ejecutar el importador

**2.1 — Accede a página especial del plugin:**

El plugin puede tener una página de admin. Busca una de estas rutas:

```
Opción A: wp-admin → Tools → Content Importer
Opción B: wp-admin → gano-content-importer
Opción C: URL directa: /wp-admin/admin.php?page=gano-content-importer
```

**2.2 — Botón "Import SOTA Pages":**

Debería haber un botón **"Importar páginas SOTA"** o **"Start Import"**.

1. Haz clic en el botón
2. Verifica confirmación: "Importando 20 páginas..."
3. Espera 30–60 segundos (depende servidor)
4. Confirmación final: "✅ 20 páginas importadas exitosamente"

**Nota:** El plugin crea páginas como **borrador (draft)**, no publicadas.

---

### Paso 3: Validación post-import

**3.1 — Navega a Páginas en wp-admin:**

```
wp-admin → Pages
```

**3.2 — Búsqueda "sota":**

```
Ctrl+F → "sota"
```

**Resultado esperado:**
- Debería aparecer 20 filas con páginas denominadas "sota-*"
- Status: **Draft** (borrador) para todas

**Tabla de validación:**

| Página | Slug | Status | Featured Image |
|--------|------|--------|----------------|
| 1. Arquitectura NVMe | sota-nvme-arquitectura | Draft | [ ] Verificar |
| 2. Zero-Trust Security | sota-zero-trust-security | Draft | [ ] Verificar |
| ... (15 más) | ... | Draft | [ ] Verificar |
| 20. AI Admin Agent | sota-ai-admin-agent-spanish | Draft | [ ] Verificar |

---

### Paso 4: Revisión visual en Elementor (por página)

**Tiempo:** ~3 min por página (60 min total)

**4.1 — Abre página en editor Elementor:**

1. En lista de páginas, haz clic en título de página (ej: "Arquitectura NVMe")
2. Se abre página en editor Elementor
3. Verifica estructura:
   - [ ] H1 visible (hero)
   - [ ] Hook box presente (fondo oscuro, border izquierda)
   - [ ] Imagen hero cargada
   - [ ] Copy legible (sin placeholders Lorem Ipsum)
   - [ ] Clases CSS aplicadas (si no están, agregar manualmente)

**4.2 — Completar [PENDIENTE DIEGO] placeholders:**

Si ves `[PENDIENTE_DIEGO]` o similar:
1. Reemplaza con copy real (ver sección "Copy por página" arriba)
2. Verifica ortografía en español
3. Valida links internos funcionan

**4.3 — Validación rápida de Lighthouse (opcional):**

1. Abre página en navegador (borrador previewable)
2. Chrome DevTools → Lighthouse → Performance
3. Objetivo: **≥85 en mobile** (meta Core Web Vitals)
4. Si < 85: documenta issues para optimización post-wave

---

### Paso 5: Publicación

**5.1 — Una vez validadas todas las 20 páginas:**

Para cada página:
1. Click en "Publish" (o "Cambios publicados")
2. Verifica que status cambia a **Published**
3. Toma nota de URL pública (ej: `https://gano.digital/sota-nvme-arquitectura`)

**5.2 — Validación de sitemap:**

WordPress actualiza automáticamente `sitemap_index.xml`. Verifica:

```
https://gano.digital/sitemap_index.xml
```

Debería incluir 20 nuevas páginas bajo `post-sitemap.xml`.

---

### Paso 6: Enlazamiento interno (automatizado vía plugin)

**6.1 — Verificar que el plugin generó links relacionados:**

Si el plugin tiene lógica de "related links":
1. Abre una página SOTA en frontend (publicada)
2. Busca sección "Temas relacionados" o "Leer también"
3. Verifica que hay 3-4 links a otras páginas SOTA

**6.2 — Si no aparece:**

Documentar en issue: plugin no generó links (trabajo manual si es crítico).

---

## 🎨 Tokens CSS — Verificación

Todos estos tokens deben existir en `wp-content/themes/gano-child/style.css`:

| Token | Valor | Usado en |
|-------|-------|----------|
| `--gano-fs-5xl` | 48 px | H1 hero SOTA |
| `--gano-fs-4xl` | 36 px | H2 subsecciones |
| `--gano-fs-base` | 16 px | Body, párrafos |
| `--gano-lh-tight` | 1.1 | H1 |
| `--gano-lh-snug` | 1.25 | H2 |
| `--gano-lh-relaxed` | 1.65 | Párrafos |
| `--gano-gold` | #D4AF37 | Border hook box |
| `--gano-home-section--surface` | (gradiente oscuro) | Hook box BG |
| `--gano-color-text-on-dark` | #E2E8F0 | Texto en oscuro |

**Validación:** Abre `style.css`, busca `:root`, confirma que todos los tokens arriba existen.

---

## 📊 Categorías temáticas — Agrupación post-import

Para SEO + UX, las 20 páginas pueden agruparse en **5 colecciones temáticas**:

| Categoría | Páginas | Slug colección | Propósito |
|-----------|---------|----------------|-----------|
| **Infraestructura** | 1, 5, 7, 8, 11, 12, 14, 15, 17, 18 | `/categoria/infraestructura-sota` | SEO hub, cross-links |
| **Seguridad** | 2, 6, 10 | `/categoria/seguridad-sota` | SEO hub |
| **Estrategia** | 4, 9, 19 | `/categoria/estrategia-sota` | SEO hub |
| **Inteligencia Artificial** | 3, 20 | `/categoria/ia-sota` | SEO hub |
| **Rendimiento UX** | 13, 16 | `/categoria/rendimiento-ux` | SEO hub |

**Implementación:** (Opcional, Fase posterior)
- Crear categorías vía wp-admin → Categorías
- Asignar cada página a su categoría
- Crear página de colección (ej: `/infraestructura`) que lista 10 páginas + descripción general

---

## ✅ Definition of Done

La tarea se cierra cuando:

- [ ] Plugin `gano-content-importer` activado
- [ ] 20 páginas importadas (confirmadas en wp-admin Pages)
- [ ] Todas las páginas tienen status **Draft** post-import
- [ ] Cada página revisada en Elementor (4 min/página min)
- [ ] [PENDIENTE DIEGO] placeholders reemplazados con copy real
- [ ] Clases CSS verificadas (gano-el-hero-sota, gano-el-hook-box-sota, etc.)
- [ ] Featured images cargadas + alt texto en español
- [ ] Lighthouse score ≥85 en mobile (muestra de 3 páginas)
- [ ] 20 páginas publicadas (status = Published)
- [ ] Sitemap actualizado (contiene 20 nuevas URLs)
- [ ] Enlaces internos funcionales (navegación entre páginas)
- [ ] Git commit: "cd-content-003: Importar 20 páginas SOTA"

---

## 🚨 Troubleshooting

### ❌ Escenario A: "Plugin no encontrado en Plugins"

**Causa:** Plugin no instalado

**Solución:**
1. Contacta soporte técnico (Claude ou Diego)
2. Verifica que `/wp-content/plugins/gano-content-importer/` existe en filesystem
3. Si no existe: clonar desde repo O descargar archivo

```bash
# Vía SSH:
cd /home/f1rml03th382/public_html/gano.digital/wp-content/plugins/
git clone https://repo-privado/gano-content-importer.git
```

---

### ❌ Escenario B: "Importación falla o se queda en "Importando...""

**Causa probable:** Timeout de servidor o error PHP

**Solución:**
1. Espera 2 min adicionales (servidor lento)
2. Recarga página (F5)
3. Si persiste error:
   - Revisa `/wp-content/debug.log` para detalles
   - Abre issue en GitHub: "cd-content-003: Import timeout"
   - Alternativa: importar en chunks (5 páginas por vez)

---

### ❌ Escenario C: "Páginas importadas pero sin contenido (vacías)"

**Causa:** Metadata o contenido no migrado

**Solución:**
1. Abre una página en editor Elementor
2. Si está completamente vacía (sin bloques):
   - El plugin puede no haber copiado estructura
   - Solución manual: copiar bloque de otra página SOTA como template
3. Si tiene bloques pero textos son "[PENDIENTE]":
   - Normal, procede con Paso 4 (completar placeholders)

---

### ❌ Escenario D: "Lighthouse score <85 en varias páginas"

**Causa probable:** Imágenes heavy, sin optimización

**Solución:**
1. Optimizar imágenes:
   - Usar plugin "Smush" o "Imagify"
   - Comprimir a <100 KB por imagen
2. Lazy loading:
   - Elementor tiene setting "Lazy load" por defecto
   - Verifica en Imagen → Advanced → Lazy Loading = ON
3. Core Web Vitals:
   - LCP: optimizar imagen hero (es el culpable típico)
   - INP: revisar JavaScript de interactividad
   - CLS: fijar dimensiones de imágenes

---

## 📋 Validación completa checklist

Antes de marcar Done, ejecuta:

```bash
# 1. Contar páginas importadas
wp post list --post_type=page --s="sota" --format=count
# Debería devolver: 20

# 2. Validar que todas son Draft
wp post list --post_type=page --s="sota" --format=table
# Todas deberían tener status "publish" o "draft" (documenta estado)

# 3. Validar sitemap
curl -s https://gano.digital/sitemap_index.xml | grep -c "sota"
# Debería devolver: >= 20

# 4. Lighthouse (muestra 1 página)
# Abre en navegador + DevTools + Lighthouse
# Captura screenshot de performance score
```

---

## 🔄 Post-implementación

**Próximos pasos (no bloqueantes):**

1. **Categorización temática:** Agregar categorías y páginas de colección (Fase posterior)
2. **Backlinks internos manuales:** Si el plugin no auto-generó "related links", agregar manualmente en Hook Box o final de cada página
3. **SEO refinement:** Rank Math schema JSON-LD (Fase 3, ya hecho) incluye estas nuevas páginas automáticamente
4. **Analytics:** Monitorear traffic a estas páginas vía Google Analytics (Fase posterior)
5. **Actualización de sitemap visual:** Si aplica (robots.txt, estructurado)

---

**Generado por:** Claude Dispatch (cd-content-003 implementation guide)  
**Última actualización:** 2026-04-19 22:15 UTC  
**Próximas tareas:** cd-content-002 (post-CD repo-002 Diego), cd-repo-003 (guía operativa)
