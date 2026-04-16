---
name: gano-core-web-vitals-monitoring
description: >
  Skill de monitoreo continuo de Core Web Vitals y VSI (Visual Stability Index).
  Implementar dashboard, auditorías automáticas, y alertas para mantener CWV en VERDE
  post-deploy. Usa cuando necesites: auditar LCP/INP/CLS/VSI, configurar monitoreo
  24/7, revisar Lighthouse reports, o analizar impacto de cambios en performance.
---

# Gano Core Web Vitals Monitoring — Dashboard y Alerting SOTA 2026

Skill para implementar monitoreo integral de Core Web Vitals (CWV) + VSI (Visual Stability Index),
incluyendo auditorías automáticas, dashboards y alertas.

---

## Contexto SOTA 2026

### Las 4 Métricas Críticas para SEO

| Métrica | Rango | Green | Yellow | Red |
|---------|-------|-------|--------|-----|
| **LCP** (Largest Contentful Paint) | 0-4s | <2.5s | 2.5-4s | >4s |
| **INP** (Interaction to Next Paint) | 0-500ms | <200ms | 200-500ms | >500ms |
| **CLS** (Cumulative Layout Shift) | 0-1 | <0.1 | 0.1-0.25 | >0.25 |
| **VSI** (Visual Stability Index) | 0-1 | <0.15 | 0.15-0.3 | >0.3 |

### Ranking Impact (2026)
- **Scenario 1:** Contenido similar → **CWV decide ranking** (dominador)
- **Scenario 2:** Contenido diferente → **E-E-A-T primero**, CWV segunda
- **AMP nota:** Ya no otorga ventaja (standard HTML optimizado es igual)

### VSI — Nueva Métrica 2026
- **Qué mide:** Estabilidad layout durante **TODA la sesión** (no solo load)
- **Por qué:** Legacy CLS era demasiado leniente; medía solo durante interaction
- **Ubicación:** PageSpeed Insights → "Lab Data" → "Visual Stability Index"
- **Impacto SEO:** Nuevo factor de ranking a partir de Junio 2026

---

## Setup 1: Google PageSpeed Insights (Gratis, Manual)

### Auditoría Manual Semanal
```bash
# Paso 1: Ir a PageSpeed Insights
URL: https://pagespeed.web.dev/

# Paso 2: Auditar URLs principales
✓ https://gano.digital (home)
✓ https://gano.digital/shop-premium/
✓ https://gano.digital/sota-hub/
✓ https://gano.digital/seo-landing/
✓ https://gano.digital/diagnostico-digital/

# Paso 3: Anotar valores (crear spreadsheet)
Fecha | URL | LCP | INP | CLS | VSI | Overall Score
------|-----|-----|-----|-----|-----|---------------
2026-04-12 | home | 1.8s | 95ms | 0.05 | 0.03 | 92
2026-04-12 | shop-premium | 2.1s | 120ms | 0.08 | 0.04 | 89
...
```

### Interpretación de Lighthouse Report
```
Secciones clave:
1. "Metrics" → LCP, INP, CLS, VSI (lab data)
2. "Opportunities" → Qué optimizar primero (% impact)
3. "Diagnostics" → Detalles técnicos
4. "Passed Audits" → Lo que funciona bien

Acción si LCP > 2.5s:
└─ "Eliminate render-blocking resources"
   └─ "Reduce unused CSS/JavaScript"
   └─ "Optimize images"
   └─ "Reduce font loading time"
```

### Spreadsheet de Tracking (Google Sheets)
```
Crear sheet compartida: "Gano Digital — CWV Tracking 2026"

Columns:
- Date (YYYY-MM-DD)
- URL (página auditada)
- LCP (s)
- INP (ms)
- CLS (0.0-1.0)
- VSI (0.0-1.0)
- Overall Score (0-100)
- Notes (qué cambió?)

Cada viernes: re-auditar todas las URLs
Actualizar sheet con nuevos valores
Buscar trends de degradación
```

---

## Setup 2: DebugBear (Monitoreo Automático — Recomendado)

**Herramienta SOTA:** DebugBear proporciona auditorías automáticas 24/7 + alertas.

### Setup Inicial (5 minutos)
1. Ir a https://www.debugbear.com
2. Sign up (plan gratuito disponible)
3. Agregar sitio: https://gano.digital
4. Conectar con PageSpeed Insights API (opcional)
5. Configurar alertas por email

### Dashboard DebugBear
```
Vista principal:
┌─ LCP trend (último 30 días)
├─ INP trend
├─ CLS trend
├─ VSI trend
└─ Overall score trend

Alarmas configuradas:
• LCP > 2.5s → Email alert
• INP > 200ms → Email alert
• CLS > 0.1 → Email alert
• VSI > 0.15 → Email alert
• Score drop >5 puntos → Email alert
```

### Interpretación de Trends
```
✓ Línea estable (verde) = sitio performante
⚠️ Línea subiendo (amarilla) = degradación detectada
🔴 Línea roja = crítico, acción inmediata

Cuando degradación ocurre:
1. DebugBear muestra qué cambió (lighthouse diff)
2. Comparar con commits recientes en git
3. Revertir o optimizar cambio problemático
4. Re-auditar para confirmar mejora
```

---

## Setup 3: GitHub Actions + Automated Audits (Avanzado)

### Workflow Automático Post-Deploy
```yaml
# .github/workflows/15-cron-cwv-audit.yml

name: CWV Hourly Audit

on:
  schedule:
    - cron: '0 * * * *'  # Cada hora

jobs:
  audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Audit CWV via PageSpeed Insights API
        run: |
          # Usar API clave (desde secrets)
          curl -X GET \
            "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://gano.digital&key=${{ secrets.GOOGLE_PSI_KEY }}" \
            | jq '.lighthouseResult.audits | {lcp, inp, cls, vsi}'
      
      - name: Compare with baseline
        run: |
          # Si alguna métrica > threshold → comentar en issue
          # (opcional: crear issue "Performance degradation detected")
      
      - name: Slack notification (si cambio significativo)
        run: |
          # Notificar a #dev-alerts en Slack
          curl -X POST ${{ secrets.SLACK_WEBHOOK }} \
            -d '{"text": "⚠️ CWV degradation detected: LCP >2.5s"}'
```

### Setup Secrets GitHub
```
Necesario en GitHub → Settings → Secrets:
- GOOGLE_PSI_KEY: [API key de Google Cloud]
- SLACK_WEBHOOK: [URL webhook de Slack (si aplica)]
```

---

## Procedimiento: Auditoría Manual Completa (FASE 2)

**Duración:** 30 minutos  
**Frecuencia:** Semana 1 (baseline), luego semanal

### Paso 1: Abrir PageSpeed Insights
```
URL: https://pagespeed.web.dev/
Ingresar: https://gano.digital
Esperar análisis completo (~60s)
```

### Paso 2: Anotar Métricas Lab
```
✓ LCP: [valor en segundos]
✓ INP: [valor en ms]
✓ CLS: [valor en 0.0-1.0]
✓ VSI: [valor en 0.0-1.0, NEW 2026]
✓ Overall Score: [0-100]
```

### Paso 3: Revisar "Opportunities"
```
Lista priorizada por impacto:
1. Elemento con mayor impacto (% de mejora)
2. Segundo elemento
3. Tercero

Acción: Revisar si aplica a gano.digital
- "Eliminate unused CSS" → WP Rocket Remove Unused CSS
- "Optimize images" → Compresor online + WebP format
- "Reduce font loading" → Font strategy (máx 2-3 familias)
```

### Paso 4: Revisar "Diagnostics"
```
Sección técnica detallada:
• Tamaño payload total
• Tiempo de rendering
• Main thread work
• Layout thrashing (causa CLS)

Nota: Si VSI alto (>0.15) → revisar "Layout shifts"
```

### Paso 5: Actualizar Spreadsheet
```
Google Sheets "Gano Digital — CWV Tracking 2026"
Agregar fila con fecha + valores de hoy
```

---

## Optimizaciones Dirigidas (si CWV está ROJO)

### LCP Lento (>2.5s)
```
Causa común: Imagen hero grande sin optimizar

Solución:
1. Descargar imagen original
2. Comprimir: ImageOptim (Mac) o TinyPNG (online)
3. Convertir a WebP: cwebp tool
4. Subir a Elementor con lazy loading enabled
5. Re-auditar

Herramienta: https://tinypng.com/ (WebP online)
```

### INP Alto (>200ms)
```
Causa común: JavaScript bloqueante + sin defer

Solución:
1. Identificar script problemático en Lighthouse
2. En wp-config.php o theme:
   wp_enqueue_script(..., ['in_footer' => true, 'defer' => true])
3. Remover plugins innecesarios (cada plugin = JS overhead)
4. Re-auditar

Herramienta: Lighthouse "Main Thread Work" section
```

### CLS Alto (>0.1) / VSI Alto (>0.15)
```
Causa común: Elementor animaciones sin fixed dimensions

Solución:
1. En Elementor, para cada widget:
   - Asignar Height/Width fijos (o min-height)
   - Evitar margin collapses
2. En gano-child/style.css:
   img { width: 100%; height: auto; }  /* Fijar aspect ratio */
   .elementor-image img { aspect-ratio: 16/9; }
3. Revisar fuentes + loading strategy
4. Re-auditar

Nota: VSI es más estricto que CLS — revisar "Layout shifts" cada 5s
```

---

## Checklist de Implementación

- [ ] PageSpeed Insights auditoría inicial completada
- [ ] CWV baseline anotada en spreadsheet
- [ ] VSI (nuevo 2026) monitoreada
- [ ] DebugBear setup completado (opcional)
- [ ] GitHub Actions workflow 15 configurado (avanzado)
- [ ] Slack alerts configuradas (si aplica)
- [ ] Equipo entrenado en interpretación de reports
- [ ] Responsable asignado para re-auditar semanalmente
- [ ] Optimizaciones aplicadas (si alguna métrica ROJA)
- [ ] Post-optimización: todas métricas VERDE (90+)

---

## Referencias SOTA 2026

- [Google Core Web Vitals 2026](https://developers.google.com/search/docs/appearance/core-web-vitals)
- [PageSpeed Insights](https://pagespeed.web.dev/)
- [DebugBear Monitoring](https://www.debugbear.com/)
- [Core Web Vitals 2026 Guide ALM Corp](https://almcorp.com/blog/core-web-vitals-2026-technical-seo-guide/)
- [SOTA Investigation 2026-04](../../memory/research/sota-investigation-2026-04.md) — § 4 Core Web Vitals + SEO
