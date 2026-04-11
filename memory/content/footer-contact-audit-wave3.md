# Auditoría footer / contacto — Wave 3 (Abril 2026)

Skill aplicado: `.gano-skills/gano-content-audit`  
Relacionado con: `memory/research/gano-wave3-brand-ux-master-brief.md` §1, §6

> **Estado:** Insumo para revisión de Diego. Los datos marcados con `[PENDIENTE DIEGO]` no pueden ser inventados — deben venir del fundador antes de publicar.

---

## 1. Resumen ejecutivo

| Hallazgo | Ubicación | Criticidad | Acción |
|----------|-----------|------------|--------|
| Dirección USA falsa: "969 Pine Street, Grand Rapids, MI" | Footer Elementor (BD) | 🔴 Crítico | Reemplazar con dirección colombiana real |
| Teléfono USA falso: "+1-(314) 892-2600" | Footer Elementor (BD) | 🔴 Crítico | Reemplazar con número colombiano real |
| Email falso: "Hythostmain@mail.com" | Footer Elementor (BD) | 🔴 Crítico | Reemplazar con hola@gano.digital |
| Teléfono vacío en schema SEO | WP option `gano_seo_phone` (BD) | 🔴 Crítico | Definir en wp-admin → Gano SEO |
| NIT vacío en schema SEO | WP option `gano_seo_nit` (BD) | 🟠 Alto | Definir en wp-admin → Gano SEO |
| WhatsApp vacío en schema SEO | WP option `gano_seo_whatsapp` (BD) | 🟠 Alto | Definir en wp-admin → Gano SEO |
| Dirección "Calle 184 #18-22" en gano-phase3 | Plugin installer (activar una vez) | 🟡 Medio | Confirmar o ajustar antes de activar |
| Dirección "Calle 184 #18-22" en política de privacidad | Plugin installer (activar una vez) | 🟡 Medio | Confirmar o ajustar antes de activar |
| Dirección "Calle 184 #18-22" en WooCommerce store | WP option `woocommerce_store_address` (BD) | 🟡 Medio | Confirmar en wp-admin → WooCommerce → Ajustes |
| Teléfono ausente en página de contacto | gano-phase3 installer (activar una vez) | 🟡 Medio | Añadir antes de activar o editar en Elementor post-activación |
| Horario de atención parcial (sin soporte 24/7 explícito) | Footer / página Contacto | 🟡 Medio | Verificar y unificar mensaje de horarios |

---

## 2. Datos falsos detectados — ubicaciones exactas

### 2.1 Footer de Elementor (Base de Datos)

El footer del sitio está construido con **Elementor** y almacenado en la base de datos WordPress. No existe código PHP en el repo que genere este contenido. Debe editarse **directamente en el servidor** vía editor de Elementor.

El MU plugin `gano-security.php` (líneas 400–414) ya detecta estos placeholders en la opción `elementor_data` y muestra una alerta en wp-admin cuando están presentes:

```
'969 Pine Street'       → Dirección de ejemplo detectada en el sitio.
'Hythostmain@mail.com'  → Email de ejemplo detectado.
'+1-(314) 892-2600'     → Teléfono de ejemplo detectado.
```

**Datos incorrectos actualmente en el footer:**

| Campo | Valor falso (USA) | Valor propuesto |
|-------|------------------|-----------------|
| Dirección | 969 Pine Street, Grand Rapids, MI | `[PENDIENTE DIEGO]` — dirección fiscal real en Colombia |
| Teléfono | +1-(314) 892-2600 | `[PENDIENTE DIEGO]` — número colombiano +57 XXX XXX XXXX |
| Email principal | Hythostmain@mail.com | **hola@gano.digital** ✅ |

### 2.2 Widget de texto / íconos del footer (Elementor)

Si el footer tiene secciones adicionales con iconos de redes sociales o links de menú, revisar también:

- **Menú footer**: verificar que los ítems del menú apunten a URLs correctas (no `/home`, `/about` en inglés).
- **Copyright**: debe leer "© [año] Gano Digital SAS. Todos los derechos reservados." con año dinámico si es posible.
- **Links legales**: incluir enlace a `/legal` (Términos y Condiciones) y `/privacidad` una vez esas páginas estén activas.

### 2.3 gano-seo.php — Opciones de WordPress (Base de Datos)

Archivo: `wp-content/mu-plugins/gano-seo.php`, función `gano_seo_config()`.

Estas opciones se configuran en **wp-admin → Ajustes → Gano SEO** o mediante WP-CLI:

```bash
wp option update gano_seo_phone     '+57 XXX XXX XXXX'
wp option update gano_seo_whatsapp  '57XXXXXXXXXX'       # sin + ni espacios
wp option update gano_seo_nit       '9XX.XXX.XXX-X'
wp option update gano_seo_street    'Dirección real, Bogotá'
wp option update gano_seo_postal    'XXXXXX'             # código postal Bogotá
```

| Opción WP | Estado actual | Valor propuesto |
|-----------|--------------|-----------------|
| `gano_seo_phone` | Vacío `''` | `[PENDIENTE DIEGO]` +57 XXX XXX XXXX |
| `gano_seo_whatsapp` | Vacío `''` | `[PENDIENTE DIEGO]` número sin + ni espacios |
| `gano_seo_nit` | Vacío `''` | `[PENDIENTE DIEGO]` NIT de Gano Digital SAS |
| `gano_seo_street` | `'Bogotá, Colombia'` (genérico) | Dirección fiscal real si se tiene |
| `gano_seo_postal` | Vacío `''` | Código postal del domicilio fiscal |
| `gano_seo_legal_name` | `'Gano Digital SAS'` | Confirmar razón social exacta en Cámara de Comercio |
| `gano_seo_email` | `'hola@gano.digital'` ✅ | Sin cambio |
| `gano_seo_city` | `'Bogotá'` ✅ | Sin cambio |
| `gano_seo_region` | `'Cundinamarca'` ✅ | Sin cambio |
| `gano_seo_lat` | `'4.6097'` ✅ | Ajustar si la dirección real difiere del centro de Bogotá |
| `gano_seo_lng` | `'-74.0817'` ✅ | Ajustar si la dirección real difiere del centro de Bogotá |

### 2.4 Plugin gano-phase3-content — Página de Contacto (installer, no activo en BD)

Archivo: `wp-content/plugins/gano-phase3-content/gano-phase3-content.php`, función `gano_p3_contact_content()` (línea 615).

El installer creará la página `/contacto` con este contenido cuando se active. **Revisar y corregir antes de activar**:

**Datos actuales en el installer:**
```
📧 hola@gano.digital                         ← OK ✅
🗺️ Calle 184 #18-22, Bogotá, Colombia        ← [PENDIENTE DIEGO] confirmar dirección real
🕒 Lunes a Viernes: 8:00 AM – 6:00 PM        ← Confirmar horario comercial real
🕒 Sábados: 9:00 AM – 1:00 PM               ← Confirmar
📩 soporte@gano.digital                       ← OK ✅ (soporte 24/7)
```

**Dato faltante:** teléfono / WhatsApp directo — añadir cuando Diego confirme el número.

### 2.5 Plugin gano-phase3-content — Política de Privacidad (installer)

Archivo: `wp-content/plugins/gano-phase3-content/gano-phase3-content.php`, línea 791.

Texto actual: _"Gano Digital SAS, con domicilio en **Calle 184 #18-22, Bogotá, Colombia**…"_

- Confirmar que "Calle 184 #18-22" es la dirección fiscal real inscrita en Cámara de Comercio.
- Si no es correcta, editar el archivo del plugin antes de activarlo **o** editar la página en WordPress después de la activación.

### 2.6 WooCommerce Store Address (Base de Datos)

Configurado por `gano-phase2-business.php` al activarse:
```php
'woocommerce_store_address' => 'Calle 184 #18-22'
'woocommerce_store_city'    => 'Bogotá'
'woocommerce_default_country' => 'CO:CUN'
'woocommerce_store_postcode'  => '111156'
```

- Dirección: confirmar con Diego si es correcta o si debe cambiarse.
- Código postal 111156: verificar que corresponde a la dirección real (zona Usaquén, Bogotá Norte).
- Acceso: **wp-admin → WooCommerce → Ajustes → General**.

---

## 3. Copy listo para pegar — Footer Elementor

Usar los textos a continuación para reemplazar los datos falsos en el editor de Elementor (sección Footer):

### 3.1 Bloque de contacto / información (columna izquierda o central del footer)

```
Gano Digital
[PENDIENTE DIEGO: Dirección real, Bogotá, Colombia]
hola@gano.digital
[PENDIENTE DIEGO: +57 XXX XXX XXXX]

Soporte técnico 24/7: soporte@gano.digital
Facturación / DIAN: facturacion@gano.digital
Legal: legal@gano.digital
```

### 3.2 Bloque copyright (barra inferior del footer)

```
© 2024–[AÑO ACTUAL] Gano Digital SAS — Todos los derechos reservados.
Ecosistemas de alto rendimiento para proyectos digitales serios.
```

> **Tono**: sobrio, sin redundancias. Si el tema o Elementor lo permite, usar `[current_year]` shortcode (p. ej. via plugin) o código PHP `echo date('Y')` para el año actual. Si no es posible, actualizar manualmente cada enero.

### 3.3 Bloque horario de atención

```
Soporte comercial: Lunes a Sábado, 8:00 AM – 8:00 PM (hora Colombia)
Soporte técnico: 24/7 (canal ticket / email)
```

> **Coherencia**: verificar que este horario coincide con el que se publicará en la página `/contacto`.

### 3.4 Links legales sugeridos (footer bottom bar)

```
Términos y Condiciones  |  Política de Privacidad  |  SLA  |  Política de Uso Aceptable
```

---

## 4. Instrucciones paso a paso para Diego

### A. Reemplazar datos falsos en el Footer de Elementor

1. Ir a **wp-admin → Elementor → Mis plantillas** (o abrir el Theme Builder si el footer es una plantilla de tema).
2. Buscar la plantilla **Footer** o acceder desde el frontend: clic en "Editar con Elementor" (si está disponible).
3. Localizar los widgets que contienen:
   - Widget de icono + texto con dirección, teléfono o email
   - Widget de texto HTML o Heading con copyright
4. Reemplazar cada dato falso con los valores de la sección 3 de este documento.
5. Guardar y limpiar caché (LiteSpeed / SiteGround / Cloudflare / plugin de caché).

### B. Configurar opciones SEO (datos de schema)

En **wp-admin → Ajustes → Gano SEO** completar:
- Teléfono de contacto (formato +57...)
- NIT / Razón social
- Número WhatsApp (solo dígitos, sin +)
- Dirección completa si difiere del valor genérico "Bogotá, Colombia"

O via WP-CLI desde SSH:
```bash
wp option update gano_seo_phone    '+57 XXX XXX XXXX'
wp option update gano_seo_whatsapp '57XXXXXXXXXX'
wp option update gano_seo_nit      '9XX.XXX.XXX-X'
```

### C. Revisar WooCommerce Store Address

1. **wp-admin → WooCommerce → Ajustes → General**
2. Confirmar: dirección tienda, ciudad, código postal.
3. Si la dirección no es correcta, actualizar antes de activar `gano-phase2-business`.

### D. Antes de activar gano-phase3-content

Si el plugin aún no ha sido activado, editar primero:
1. `wp-content/plugins/gano-phase3-content/gano-phase3-content.php`
2. Función `gano_p3_contact_content()` (línea 615): buscar "Calle 184 #18-22" y reemplazar con dirección real.
3. Función de política de privacidad en el mismo archivo (línea ~791): misma dirección fiscal.
4. Agregar widget de teléfono/WhatsApp en la página de contacto.

---

## 5. Verificación post-cambio

| Check | Cómo verificar |
|-------|---------------|
| Footer sin datos USA | Abrir gano.digital en incógnito y revisar footer |
| Alerta wp-admin desaparecida | Ir a wp-admin; la alerta de `gano-security.php` no debe aparecer si los placeholders ya no están en `elementor_data` |
| Schema SEO correcto | Herramienta Rich Results Test de Google sobre `https://gano.digital` |
| Schema teléfono y NIT | Ver fuente HTML de la home → buscar `@type": "LocalBusiness"` |
| Contacto funcional | Enviar formulario de prueba en `/contacto` |
| WooCommerce dirección | wp-admin → WooCommerce → Ajustes → General |

---

## 6. Datos que Diego debe confirmar antes de publicar

> Ninguno de estos puede ser inventado. Añadir a este archivo cuando se confirmen.

```yaml
razon_social:   "Gano Digital SAS"   # confirmar razón social exacta
nit:            "[PENDIENTE]"         # NIT con dígito de verificación
direccion:      "[PENDIENTE]"         # Dirección fiscal registrada en Colombia
codigo_postal:  "[PENDIENTE]"         # Verificar que 111156 corresponde si aplica
telefono:       "[PENDIENTE]"         # +57 XXX XXX XXXX
whatsapp:       "[PENDIENTE]"         # Número WhatsApp Business (sin + ni espacios)
email_ventas:   "hola@gano.digital"  # Confirmado
email_soporte:  "soporte@gano.digital" # Confirmado
email_abuso:    "abuse@gano.digital" # Por confirmar si está activo
email_legal:    "legal@gano.digital" # Por confirmar si está activo
horario_comercial: "Lun–Sáb 8–20h"  # Confirmar
horario_soporte:   "24/7"            # Confirmar capacidad real
```

---

_Generado por: Copilot agent (oleada 3, issue [agent] Auditoría contenido: footer/contacto)_  
_Fuente de criterio: `.gano-skills/gano-content-audit/SKILL.md`_
