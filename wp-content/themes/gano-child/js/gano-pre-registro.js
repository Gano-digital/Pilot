/**
 * Gano Pre-registro Handler
 * Captura leads de interés antes de redirigir al catálogo o checkout.
 */

document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.gano-pre-registro-form');

    forms.forEach(form => {
        const feedback = form.nextElementSibling && form.nextElementSibling.classList.contains('gano-pre-registro-feedback') 
            ? form.nextElementSibling 
            : null;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button');
            const originalBtnText = submitBtn.innerHTML;
            const formData = new FormData(form);
            const redirectTo = form.querySelector('input[name="redirect_to"]').value || '/';

            // Visual feedback
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin" style="display:inline-block; animation: spin 1s linear infinite;">⌛</span> Procesando...';
            
            if (feedback) {
                feedback.style.display = 'block';
                feedback.style.opacity = '1';
                feedback.innerHTML = 'Preparando tu acceso SOTA...';
                feedback.style.color = 'var(--gano-color-accent, #4CD7F6)';
            }

            // Validación de gano_vars (evitar ReferenceError)
            if (typeof gano_vars === 'undefined') {
                console.warn('gano_vars no definido. Redirigiendo directamente.');
                setTimeout(() => {
                    window.location.href = redirectTo;
                }, 1000);
                return;
            }

            // Preparamos los datos para AJAX de WordPress
            formData.append('action', 'gano_pre_registro');
            formData.append('nonce', gano_vars.nonce || '');

            fetch(gano_vars.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (feedback) {
                        feedback.innerHTML = '¡Acceso concedido! Redirigiendo...';
                        feedback.style.color = 'var(--gano-color-accent, #4CD7F6)';
                    }
                    
                    // Guardamos en localStorage para persistencia comercial opcional
                    localStorage.setItem('gano_lead_captured', 'true');
                    localStorage.setItem('gano_lead_name', formData.get('gano_name'));

                    setTimeout(() => {
                        window.location.href = redirectTo;
                    }, 1200);
                } else {
                    throw new Error(data.data || 'Error en el registro');
                }
            })
            .catch(error => {
                console.error('Pre-registro error:', error);
                if (feedback) {
                    feedback.innerHTML = 'Conectando con el catálogo...';
                    feedback.style.color = 'var(--gano-color-text-muted-on-dark, #94a3b8)';
                }
                
                setTimeout(() => {
                    window.location.href = redirectTo;
                }, 1500);
            });
        });
    });
});

