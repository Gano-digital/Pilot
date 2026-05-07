#!/bin/bash
# Gano Digital — Homepage Staging Validation Script
# Ejecutar en servidor: /home/f1rml03th382/public_html/gano.digital
# Propósito: Validar todos los archivos de Task 7-10 estén presentes y tengan permisos correctos
# Fecha: 2026-05-06

set -e

WEBROOT="/home/f1rml03th382/public_html/gano.digital"
REPORT_FILE="${WEBROOT}/wp-content/themes/gano-child/docs/STAGING-VALIDATION-2026-05-06.log"

echo "==============================================="
echo "Gano Digital — Staging Validation Report"
echo "Fecha: $(date '+%Y-%m-%d %H:%M:%S')"
echo "================================================"
echo "" | tee -a "$REPORT_FILE"

# Función: verificar archivo existe y es legible
check_file() {
    local filepath="$1"
    local description="$2"

    if [ -f "${WEBROOT}${filepath}" ]; then
        local perms=$(stat -c '%A' "${WEBROOT}${filepath}")
        local size=$(stat -c '%s' "${WEBROOT}${filepath}")
        echo "✓ ${description}" | tee -a "$REPORT_FILE"
        echo "  Ruta: ${filepath}" | tee -a "$REPORT_FILE"
        echo "  Permisos: ${perms} | Tamaño: ${size} bytes" | tee -a "$REPORT_FILE"
    else
        echo "✗ FALTA: ${description}" | tee -a "$REPORT_FILE"
        echo "  Ruta: ${filepath}" | tee -a "$REPORT_FILE"
        return 1
    fi
}

# ===== VALIDAR ARCHIVOS IMPLEMENTADOS =====
echo "1. Verificando archivos de templates..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

check_file "/wp-content/themes/gano-child/template-parts/sections/hero.php" "Hero section template"
check_file "/wp-content/themes/gano-child/template-parts/sections/faq.php" "FAQ section template"
check_file "/wp-content/themes/gano-child/template-parts/sections/cta-final.php" "CTA form section template"

echo ""
echo "2. Verificando archivos CSS..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

check_file "/wp-content/themes/gano-child/css/components/hero.css" "Hero CSS"
check_file "/wp-content/themes/gano-child/css/components/faq.css" "FAQ CSS"
check_file "/wp-content/themes/gano-child/css/components/cta-final.css" "CTA CSS"

echo ""
echo "3. Verificando archivos JavaScript..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

check_file "/wp-content/themes/gano-child/js/components/faq-accordion.js" "FAQ Accordion JS"
check_file "/wp-content/themes/gano-child/js/components/form-handler.js" "Form Handler JS"

echo ""
echo "4. Verificando documentación de contenido..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

check_file "/docs/superpowers/content/blog-posts-2026-05.md" "Blog Posts Strategy"

# ===== VALIDAR SINTAXIS PHP =====
echo ""
echo "5. Validando sintaxis PHP de templates..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

for phpfile in hero.php faq.php cta-final.php; do
    filepath="/wp-content/themes/gano-child/template-parts/sections/${phpfile}"
    if php -l "${WEBROOT}${filepath}" > /dev/null 2>&1; then
        echo "✓ Sintaxis válida: ${phpfile}" | tee -a "$REPORT_FILE"
    else
        echo "✗ ERRORSINTAXIS: ${phpfile}" | tee -a "$REPORT_FILE"
        php -l "${WEBROOT}${filepath}" 2>&1 | tee -a "$REPORT_FILE"
    fi
done

# ===== VALIDAR CONTENIDO CRÍTICO =====
echo ""
echo "6. Verificando contenido de plantillas..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

# Verificar que hero.php contiene el H1 correcto
if grep -q "Soberanía Digital" "${WEBROOT}/wp-content/themes/gano-child/template-parts/sections/hero.php"; then
    echo "✓ Hero contiene headline correcto" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA headline en hero.php" | tee -a "$REPORT_FILE"
fi

# Verificar que faq.php contiene el hook gano_get_faq_items
if grep -q "gano_get_faq_items" "${WEBROOT}/wp-content/themes/gano-child/template-parts/sections/faq.php"; then
    echo "✓ FAQ template contiene hook de datos" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA hook de datos en FAQ" | tee -a "$REPORT_FILE"
fi

# Verificar que form-handler.js tiene validación de email
if grep -q "emailRegex" "${WEBROOT}/wp-content/themes/gano-child/js/components/form-handler.js"; then
    echo "✓ Form handler contiene validación de email" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA validación en form handler" | tee -a "$REPORT_FILE"
fi

# ===== VALIDAR ACCESIBILIDAD =====
echo ""
echo "7. Verificando atributos de accesibilidad..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

# Verificar ARIA attributes en FAQ
if grep -q 'aria-expanded' "${WEBROOT}/wp-content/themes/gano-child/template-parts/sections/faq.php"; then
    echo "✓ FAQ tiene aria-expanded" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA aria-expanded en FAQ" | tee -a "$REPORT_FILE"
fi

if grep -q 'aria-controls' "${WEBROOT}/wp-content/themes/gano-child/template-parts/sections/faq.php"; then
    echo "✓ FAQ tiene aria-controls" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA aria-controls en FAQ" | tee -a "$REPORT_FILE"
fi

# Verificar que form tiene nonce protection
if grep -q 'gano_nonce\|wp_nonce_field' "${WEBROOT}/wp-content/themes/gano-child/template-parts/sections/cta-final.php"; then
    echo "✓ Form contiene nonce CSRF" | tee -a "$REPORT_FILE"
else
    echo "✗ FALTA nonce en form" | tee -a "$REPORT_FILE"
fi

# ===== VALIDAR ENQUEUE DE ASSETS =====
echo ""
echo "8. Verificando que CSS/JS se encolaran correctamente..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

# Verificar que functions.php enqueue los assets
if grep -q 'faq-accordion\|form-handler\|hero.css\|faq.css\|cta-final.css' "${WEBROOT}/wp-content/themes/gano-child/functions.php"; then
    echo "✓ Assets están encolados en functions.php" | tee -a "$REPORT_FILE"
else
    echo "⚠ Verificar manualmente que CSS/JS se encolaran" | tee -a "$REPORT_FILE"
fi

# ===== VALIDAR PERMISOS DE ARCHIVOS =====
echo ""
echo "9. Verificando permisos de archivo..." | tee -a "$REPORT_FILE"
echo "" | tee -a "$REPORT_FILE"

# Todos los archivos deben ser legibles por el servidor web (typically 644 o 755)
for filepath in /wp-content/themes/gano-child/template-parts/sections/*.php \
                /wp-content/themes/gano-child/css/components/*.css \
                /wp-content/themes/gano-child/js/components/*.js; do
    if [ -f "${WEBROOT}${filepath}" ]; then
        perms=$(stat -c '%a' "${WEBROOT}${filepath}")
        if [ "$perms" = "644" ] || [ "$perms" = "755" ] || [ "$perms" = "644" ]; then
            echo "✓ Permisos correctos: ${filepath} (${perms})" | tee -a "$REPORT_FILE"
        else
            echo "⚠ Permisos anómalos: ${filepath} (${perms})" | tee -a "$REPORT_FILE"
        fi
    fi
done

# ===== RESUMEN =====
echo ""
echo "================================================"
echo "Validación completada"
echo "Reporte guardado en: ${REPORT_FILE}"
echo "================================================"
echo ""

# Retornar estado
if grep -q "✗" "$REPORT_FILE"; then
    echo "⚠ Se encontraron problemas. Revisar arriba."
    exit 1
else
    echo "✓ Todas las validaciones pasaron"
    exit 0
fi
