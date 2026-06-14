/**
 * Lead Form Handler
 * Handles CTA form submission with validation and feedback
 *
 * @package Gano_Digital
 */

(function() {
	'use strict';

	const form = document.getElementById('cta-form');
	if (!form) return;

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const formData = new FormData(form);
		const data = Object.fromEntries(formData);

		try {
			// Validate required fields
			if (!data.full_name || !data.email || !data.company || !data.role) {
				showMessage('Por favor completa todos los campos requeridos.', 'error');
				return;
			}

			// Validate email format
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(data.email)) {
				showMessage('Por favor ingresa un email válido.', 'error');
				return;
			}

			// Track GA4 event
			if (window.gtag) {
				gtag('event', 'lead_form_submission', {
					company: data.company,
					role: data.role
				});
			}

			// Submit form via AJAX
			const ganoVars = window.ganoFormVars || {};
			const ajaxUrl = ganoVars.ajax_url || '/wp-admin/admin-ajax.php';
			const response = await fetch(ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'gano_process_lead_form',
					nonce: data.gano_nonce,
					full_name: data.full_name,
					email: data.email,
					company: data.company,
					role: data.role,
					message: data.message || '',
					consent: data.consent ? 'on' : 'off'
				})
			});

			const result = await response.json();

			if (result.success) {
				showMessage('¡Gracias! Nos pondremos en contacto pronto.', 'success');
				form.style.display = 'none';

				// Track conversion
				if (window.gtag) {
					gtag('event', 'conversion', {
						'conversion_id': 'lead_capture'
					});
				}
			} else {
				const errorMsg = (result.data && typeof result.data === 'string') ? result.data : 'No se pudo enviar el formulario.';
				showMessage('Error: ' + errorMsg, 'error');
			}
		} catch (error) {
			console.error('Form submission error:', error);
			showMessage('Error al enviar el formulario. Intenta de nuevo.', 'error');
		}
	});

	function showMessage(text, type) {
		const messageDiv = document.getElementById('form-message');
		messageDiv.textContent = text;
		messageDiv.className = 'form-message ' + type;
		messageDiv.style.display = 'block';

		// Auto-hide success messages after 5 seconds
		if (type === 'success') {
			setTimeout(() => {
				messageDiv.style.display = 'none';
			}, 5000);
		}
	}
})();
