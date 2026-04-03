# Auditoría de Contenido — Footer y Contacto
**Fecha:** Abril 2026  
**Generado por:** Copilot Agent (issue #footer-contacto)  
**Skill:** `.gano-skills/gano-content-audit`

---

## Resumen ejecutivo

El sitio gano.digital heredó contenido de una plantilla genérica de hosting con datos de contacto ficticios de EE. UU. Este reporte lista todos los ítems identificados, su ubicación exacta (Elementor/base de datos — no en archivos del repo), y los valores de reemplazo propuestos.

> **⚠️ Nota para Diego:** Los datos marcados como "Elementor/BD" solo pueden editarse desde wp-admin → Elementor. El agente no tiene acceso directo a la base de datos del servidor de producción. Los datos marcados como "Código (solucionado)" ya fueron corregidos en el repo.

---

## CRÍTICOS — Rompen credibilidad inmediatamente

| # | Placeholder ficticio | Ubicación | Reemplazo propuesto | Estado |
|---|---------------------|-----------|---------------------|--------|
| 1 | `969 Pine Street, Grand Rapids, MI` | Footer (widget Elementor) y página /contacto | `[Dirección real de Diego en Bogotá]` | 🔴 Pendiente — editar en Elementor |
| 2 | `+1-(314)-892-2600` | Footer (widget Elementor) y página /contacto | `[Número colombiano real — +57 XXX XXX XXXX]` | 🔴 Pendiente — editar en Elementor |
| 3 | `Hythostmain@mail.com` | Footer (widget Elementor) y página /contacto | `hola@gano.digital` | 🔴 Pendiente — editar en Elementor |
| 4 | `Advanced Web Hosting Technology` | Hero de la home | Copy real (ver sección abajo) | 🔴 Pendiente — editar en Elementor |
| 5 | Testimonios ficticios ("Sony CEO", etc.) | Sección de testimonios en home | Eliminar sección o reemplazar con clientes reales | 🔴 Pendiente — editar en Elementor |
| 6 | Estadísticas inventadas ("Customers Worldwide") | Sección de stats en home | Eliminar sección o usar métricas reales verificadas | 🔴 Pendiente — editar en Elementor |

---

## ALTOS — Dañan la confianza del usuario

| # | Problema | Ubicación | Acción | Estado |
|---|----------|-----------|--------|--------|
| 7 | Sin página `/nosotros` real | — | Crear desde cero (ver estructura abajo) | 🔴 Pendiente — crear en wp-admin |
| 8 | Sin página `/legal` (Términos de servicio) | — | Crear conforme a Ley 1581/2012 Colombia | 🔴 Pendiente — crear en wp-admin |
| 9 | Sin SLA documentado | — | Crear `/sla` con uptime 99.9% y créditos | 🔴 Pendiente — crear en wp-admin |
| 10 | Sin página de infraestructura | — | Crear `/infraestructura` con specs reales | 🔴 Pendiente — crear en wp-admin |
| 11 | Menú principal sin asignar a ubicación `primary` | Apariencia → Menús | Asignar menú a `primary` en wp-admin | 🔴 Pendiente — configurar en wp-admin |

---

## Correcciones ya aplicadas en el código (repo)

| Archivo | Mejora |
|---------|--------|
| `wp-content/mu-plugins/gano-security.php` | **Bug corregido:** el detector de placeholders buscaba en `elementor_data` (opción global que no existe) → ahora busca en `_elementor_data` (post meta por página, donde Elementor guarda los datos reales). Ampliados los patrones detectados: `969 Pine Street`, `Grand Rapids`, `Hythostmain@mail.com`, `+1-(314) 892-2600`, `+1-(314)-892-2600`, `Advanced Web Hosting Technology`. Aviso mejorado: muestra el nombre de cada página afectada con enlace directo al editor de Elementor. Resultado cacheado 1 hora (transient) para no ralentizar el admin. Cache invalidada al guardar posts/meta. |
| `wp-content/mu-plugins/gano-seo.php` | Página de ajustes (`Ajustes → Gano SEO`) ya existente y funcional para configurar: NIT, teléfono, dirección, WhatsApp, email, logo, hero image, año de fundación. Estos datos se usan en Schema JSON-LD (LocalBusiness). |
| `wp-content/plugins/gano-phase2-business/` | Plugin Phase 2 ya configura datos colombianos reales (Bogotá, COP, hola@gano.digital) al activarse. |

---

## Datos de contacto a confirmar (Diego debe completar)

```
Razón social:   Gano Digital S.A.S.  (confirmar forma legal exacta)
NIT:            [Confirmar con DIAN]
Dirección:      [Dirección física real en Bogotá — calle, número, barrio]
Ciudad:         Bogotá, Colombia
Teléfono:       +57 [XXX] [XXX] [XXXX]  (fijo o celular)
WhatsApp:       57[XXXXXXXXXX]           (sin +, sin espacios — para URL wa.me/)
Email ventas:   hola@gano.digital        ✓ ya configurado
Email soporte:  soporte@gano.digital
Email abuse:    abuse@gano.digital
Email legal:    legal@gano.digital
Horario:        Lunes a Sábado 8am – 8pm COT | Soporte técnico 24/7
```

---

## Copy propuesto para Hero principal

Texto para reemplazar "Advanced Web Hosting Technology" en el bloque Hero de Elementor:

```
HEADLINE (H1):
Infraestructura blindada para proyectos digitales serios.

SUBHEADLINE:
Hosting WordPress de alto rendimiento con seguridad de nivel empresarial,
soporte en español 24/7 y facturación en pesos colombianos.

CTA PRIMARIO:   Ver planes y precios
CTA SECUNDARIO: Hablar con un experto

TRUST SIGNALS (debajo del CTA):
✓ Servidores NVMe Gen4   ✓ Uptime 99.9%   ✓ Facturación en COP   ✓ Soporte 24/7 en español
```

---

## Pasos manuales para Diego (orden recomendado)

### 1. Configurar datos reales en `Ajustes → Gano SEO`
Ir a **wp-admin → Ajustes → Gano SEO** y completar todos los campos:
- NIT, teléfono, WhatsApp, dirección física, código postal
- Esto actualiza el Schema JSON-LD automáticamente (sin tocar Elementor)

### 2. Editar el Footer en Elementor
1. Abrir **wp-admin → Apariencia → Elementor → Template del Footer**
   (o buscar la página/template "Footer" en Elementor Theme Builder)
2. Buscar el widget que contiene la dirección, teléfono y email
3. Reemplazar:
   - `969 Pine Street, Grand Rapids, MI` → dirección real de Colombia
   - `+1-(314)-892-2600` → número colombiano real
   - `Hythostmain@mail.com` → `hola@gano.digital`
4. Guardar y publicar

### 3. Editar la página de Contacto
1. Abrir **wp-admin → Páginas → Contacto → Editar con Elementor**
2. Reemplazar los mismos datos de dirección, teléfono y email
3. Verificar que el formulario de contacto envía a `hola@gano.digital`

### 4. Actualizar el Hero de la Home
1. Abrir **wp-admin → Páginas → Inicio (Home) → Editar con Elementor**
2. Localizar el widget con texto "Advanced Web Hosting Technology"
3. Reemplazar con el copy propuesto en la sección anterior

### 5. Eliminar / reemplazar testimonios y estadísticas falsas
1. En el editor de la Home, eliminar la sección de testimonios ficticios
2. Eliminar las estadísticas inventadas ("Customers Worldwide", números sin fuente)
3. Si se quieren mantener testimonios: usar clientes reales verificables

### 6. Verificar el detector automático
- Después de guardar los cambios en Elementor, ir a cualquier página del admin
- El aviso de "Datos placeholder detectados" debe desaparecer si todo fue reemplazado
- Si persiste: revisar las páginas listadas en el aviso con los enlaces directos a Elementor

---

## Validación final

| Paso | Cómo validar |
|------|-------------|
| Footer con datos colombianos | Revisar pie de página en el sitio público |
| Schema JSON-LD correcto | [Google Rich Results Test](https://search.google.com/test/rich-results) → probar URL del home |
| Sin aviso en wp-admin | Entrar al admin → verificar que no aparece el banner amarillo de Gano Security |
| Email de contacto | Enviar formulario de contacto y verificar recepción en hola@gano.digital |

---

*Generado automáticamente por GitHub Copilot Agent — issue `[agent] Auditoría contenido: footer/contacto`*
