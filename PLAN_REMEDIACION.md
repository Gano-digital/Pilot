# PLAN DE REMEDIACIÓN - GANO.DIGITAL

**Fecha:** 2026-04-18
**Prioridad:** ALTA
**Tiempo estimado:** 2-4 horas

---

## PROBLEMA 1: ENCODING UTF-8 (CRÍTICO)

### Descripción
Caracteres españoles mal codificados en las notas de SSL de los planes de hosting.

### Síntomas
```
"período"      aparece como "perÃ­odo"
"añadido"      aparece como "aÃ±adido"
"función"      aparece como "funciÃ³n"
"garantizará"  aparece como "garantizarÃ¡"
"perderás"     aparece como "perderÃ¡s"
```

### Causa Probable
La base de datos no está configurada con collation UTF-8MB4, o los headers HTTP no especifican el charset correcto.

### Solución Paso a Paso

#### Step 1: Verificar y Corregir wp-config.php
```php
// Ubicación: wp-config.php (líneas ~30-35)

// ACTUAL (probablemente incorrecto):
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');

// CAMBIAR A:
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');
```

**Pasos:**
1. Conectar via SFTP/SSH al servidor
2. Editar `/wp-config.php`
3. Reemplazar las líneas de charset y collate
4. Guardar el archivo

#### Step 2: Asegurar Headers HTTP
```php
// Ubicación: wp-config.php o functions.php (header.php)

// AGREGAR en wp-config.php ANTES de "wp-settings.php":
if (!function_exists('set_charset_headers')) {
  add_action('send_headers', function() {
    header('Content-Type: text/html; charset=utf-8');
  }, 1);
}

// O en functions.php:
add_action('wp_head', function() {
  echo '<meta charset="UTF-8">';
}, 1);
```

#### Step 3: Convertir Base de Datos (IMPORTANTE!)
```sql
-- Si la DB aún está en utf8, ejecutar estos comandos vía phpMyAdmin

-- Convertir tabla completa
ALTER TABLE wp_posts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE wp_postmeta CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE wp_comments CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE wp_termmeta CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE wp_term_relationships CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- O ejecutar para TODA la base de datos:
ALTER DATABASE gano_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Luego actualizar todas las tablas:
REPAIR TABLE wp_posts;
REPAIR TABLE wp_postmeta;
```

#### Step 4: Limpiar Cache y Verificar
1. Limpiar todo el cache (WP Super Cache / W3 Total Cache)
2. Reconstruir búsquedas (si usa plugin de búsqueda)
3. Visitar el sitio en navegador incógnito
4. Verificar que los caracteres aparezcan correctamente

#### Step 5: Verificación Visual
- Abrir https://gano.digital/ en navegador
- Buscar "período" en el texto
- Verificar que aparece "período" y NO "perÃ­odo"
- Inspeccionar elemento (Dev Tools) → asegurar meta charset UTF-8

### Checklist de Remediación

- [ ] wp-config.php actualizado con utf8mb4
- [ ] Database convertida a utf8mb4_unicode_ci
- [ ] Headers HTTP con Content-Type: charset=utf-8
- [ ] Cache limpiado (todos los plugins)
- [ ] Página verificada en navegador (caracteres correctos)
- [ ] Test en múltiples navegadores (Chrome, Firefox, Safari)
- [ ] Inspectiona página source → meta charset correcto

**Tiempo estimado:** 30-45 minutos

---

## PROBLEMA 2: LOCALIZACIÓN INCOMPLETA (MEDIO)

### Descripción
Botones y textos en inglés mezclados con español en widgets de carrito (rstore).

### Ubicaciones Encontradas
```
data-text_placeholder="Find your perfect domain name"  → Traducir
data-text_search="Search"                              → Traducir
data-text_available="Congrats, ..."                    → Traducir
data-text_cart="Continue to cart"                      → Traducir
"Add to cart" en botones                               → Cambiar a "Añadir al carrito"
```

### Solución

#### Opción A: Modificar Atributos data-* en Elementor

1. Abrir WordPress Dashboard
2. Ir a: Elementor → Editar página principal
3. Buscar elemento "Domain Search" widget
4. Click en el widget → Editar configuración
5. Reemplazar textos en inglés:

| Campo | Actual | Nuevo |
|-------|--------|-------|
| Placeholder | `Find your perfect domain name` | `Encuentra tu dominio perfecto` |
| Search | `Search` | `Buscar` |
| Available | `Congrats, {domain_name} is available!` | `¡Felicidades! {domain_name} está disponible` |
| Not Available | `Sorry, {domain_name} is taken.` | `Lo sentimos, {domain_name} ya está en uso` |
| Continue to cart | `Continue to cart` | `Continuar al carrito` |
| Select | `Select` | `Seleccionar` |

6. Guardar cambios y publicar

#### Opción B: Código Personalizado (Si no está en Elementor)

Si el widget está hard-coded, crear función en `functions.php`:

```php
// functions.php
add_filter('gano_domain_search_text', function($text) {
  $translations = array(
    'Find your perfect domain name' => 'Encuentra tu dominio perfecto',
    'Search' => 'Buscar',
    'Congrats' => 'Felicidades',
    'is available' => 'está disponible',
    'Sorry' => 'Lo sentimos',
    'is taken' => 'ya está en uso',
    'Continue to cart' => 'Continuar al carrito',
    'Add to cart' => 'Añadir al carrito',
  );

  return strtr($text, $translations);
});
```

#### Opción C: Plugin de Traducción (Más mantenible)

Usar WPML o Polylang:
1. Instalar plugin WPML (o Polylang)
2. Registrar strings en traducción
3. Traducir todos los textos a español
4. Activar español como idioma principal

### Checklist de Remediación

- [ ] Identificar TODOS los textos en inglés en la página
- [ ] Localizar donde están (Elementor, código, widget config)
- [ ] Traducir al español (o crear filter)
- [ ] Verificar en fronted que aparece español
- [ ] Test en múltiples dispositivos (mobile, tablet, desktop)
- [ ] Verificar que funcionalidad no se rompe

**Tiempo estimado:** 15-30 minutos

**Traducciones recomendadas:**
- "Add to cart" → "Añadir al carrito" o "Elegir Plan"
- "Search" → "Buscar"
- "Continue" → "Continuar"
- "Select" → "Seleccionar"

---

## PROBLEMA 3: CLASE HTML "placeholder" (BAJO)

### Descripción
La clase `rstore_domain_placeholder` sugiere que el widget es un placeholder, pero está funcional.

### Solución

#### Opción 1: Renombrar CSS Class

1. Buscar: `rstore_domain_placeholder` en todo el código
2. Reemplazar por: `rstore_domain_widget` o `rstore-domain-input`

```bash
# Via terminal (SSH)
grep -r "rstore_domain_placeholder" /home/user/public_html/
find /home/user/public_html/ -type f -name "*.php" -o -name "*.css" -o -name "*.js" | xargs grep -l "rstore_domain_placeholder"

# Reemplazar (cuidado con orden de ejecución):
sed -i 's/rstore_domain_placeholder/rstore_domain_widget/g' archivo.php
```

3. Si está en Elementor custom CSS:
   - Dashboard → Customize → Additional CSS
   - Buscar `rstore_domain_placeholder`
   - Reemplazar por `rstore_domain_widget`

#### Opción 2: Dejar Como Está

Como es una clase interna y de bajo impacto, puede ignorarse en auditorías futuras.

### Checklist

- [ ] Cambiar nombre de clase (si no es crítico, skip)
- [ ] Verificar en CSS que referencia actualizada
- [ ] Test visual para asegurar que nada se rompe

**Tiempo estimado:** 5-10 minutos

---

## PROBLEMA 4: META TAGS INCONSISTENCIA (BAJO)

### Descripción
Meta description y OG description son ligeramente diferentes.

### Actual
```html
<meta name="description" content="Tu presencia digital, tu victoria">
<meta property="og:description" content="Servicios digitales para hacer crecer tu negocio en Colombia.">
```

### Recomendado
Mantener ambas pero coherentes:

```html
<!-- Meta description (para buscadores) -->
<meta name="description" content="Servicios digitales especializados en WordPress para hacer crecer tu negocio en Colombia. Hosting, seguridad, soporte 24/7.">

<!-- OG description (para redes sociales) -->
<meta property="og:description" content="Servicios digitales especializados en WordPress para hacer crecer tu negocio en Colombia. Hosting, seguridad, soporte 24/7.">
```

### Ubicación
- Editar en: Yoast SEO (si instalado)
- O editar en: `/wp-content/themes/gano-theme/header.php`
- O editar en: Elementor → Meta tags

### Checklist

- [ ] Editar meta description
- [ ] Editar OG description (hacerlas iguales o muy similar)
- [ ] Test en redes (compartir link en Facebook, Twitter)
- [ ] Verificar preview correcto

**Tiempo estimado:** 5 minutos

---

## CRONOGRAMA DE REMEDIACIÓN

### INMEDIATO (Hoy - 2-3 horas)
- [ ] Problema 1: Encoding UTF-8
  - [ ] Actualizar wp-config.php
  - [ ] Convertir base de datos
  - [ ] Limpiar cache
  - [ ] Verificar visualmente
  - **Tiempo:** 45-60 minutos

### ESTA SEMANA (Before Monday)
- [ ] Problema 2: Localización
  - [ ] Traducir textos rstore
  - [ ] Test multi-idioma
  - **Tiempo:** 15-30 minutos

- [ ] Problema 4: Meta Tags
  - [ ] Actualizar descripciones
  - [ ] Test social sharing
  - **Tiempo:** 5-10 minutos

### PRÓXIMO SPRINT
- [ ] Problema 3: Renombrar clase placeholder
  - [ ] Refactor CSS
  - [ ] Test visual
  - **Tiempo:** 10-15 minutos

---

## CHECKLIST DE IMPLEMENTACIÓN

### Antes de Empezar
- [ ] Backup completo del sitio (DB + archivos)
- [ ] Notificar a equipo de cambios
- [ ] Preparar ambiente de staging (si disponible)

### Implementación
- [ ] Problema 1: Encoding
- [ ] Problema 2: Localización
- [ ] Problema 4: Meta Tags
- [ ] Problema 3: Clase CSS (opcional, bajo impacto)

### Verificación
- [ ] Caracteres españoles correctos
- [ ] UI completa en español
- [ ] Meta tags consistentes
- [ ] Formulario lead funciona
- [ ] API responde correctamente
- [ ] Página carga en <2 segundos
- [ ] Mobile responsive

### Post-Implementation
- [ ] Limpiar todos los caches
- [ ] Pruebas en navegadores diferentes
- [ ] Actualizar documentación
- [ ] Notificar al cliente
- [ ] Registrar cambios en git

---

## ROLLBACK PLAN (si algo sale mal)

1. **Para Encoding:**
   ```sql
   ALTER TABLE wp_posts CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;
   -- Y revert wp-config.php cambios
   ```

2. **Para Localización:**
   - Revertir cambios en Elementor
   - Clear cache

3. **General:**
   - Restaurar backup si falla algo crítico
   - Contactar hosting si hay error de DB

---

## CONTACTOS DE SOPORTE

**Si necesitas ayuda:**
- Hosting Support: [contact info]
- WordPress Dev: [contact info]
- Elementor Help: https://elementor.com/help/

---

**Plan generado:** 2026-04-18
**Responsable:** [Asignar a alguien]
**Status:** PENDIENTE IMPLEMENTACIÓN
