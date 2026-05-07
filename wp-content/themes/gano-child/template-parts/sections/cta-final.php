<?php
/**
 * Final CTA Section — Lead Capture
 * Secondary messaging and lead form
 *
 * @package Gano_Digital
 */
?>

<div class="cta-final-section">
	<div class="cta-content">
		<h2><?php esc_html_e( 'Solicitar Demostración Gratuita', 'gano-child' ); ?></h2>
		<p><?php esc_html_e( 'Habla con nuestros expertos sobre cómo implementar soberanía digital en tu empresa.', 'gano-child' ); ?></p>

		<form id="cta-form" class="lead-form" method="POST">
			<?php wp_nonce_field( 'gano_lead_form', 'gano_nonce' ); ?>

			<div class="form-group">
				<label for="full_name"><?php esc_html_e( 'Nombre Completo', 'gano-child' ); ?> *</label>
				<input type="text" id="full_name" name="full_name" required aria-label="Nombre Completo">
			</div>

			<div class="form-group">
				<label for="email"><?php esc_html_e( 'Email', 'gano-child' ); ?> *</label>
				<input type="email" id="email" name="email" required aria-label="Email">
			</div>

			<div class="form-group">
				<label for="company"><?php esc_html_e( 'Empresa', 'gano-child' ); ?> *</label>
				<input type="text" id="company" name="company" required aria-label="Empresa">
			</div>

			<div class="form-group">
				<label for="role"><?php esc_html_e( 'Tu Rol', 'gano-child' ); ?> *</label>
				<select id="role" name="role" required aria-label="Tu Rol">
					<option value="">-- <?php esc_html_e( 'Seleccionar', 'gano-child' ); ?> --</option>
					<option value="cto">CTO</option>
					<option value="ciso">CISO</option>
					<option value="devops">DevOps</option>
					<option value="pm"><?php esc_html_e( 'Product Manager', 'gano-child' ); ?></option>
					<option value="founder"><?php esc_html_e( 'Fundador', 'gano-child' ); ?></option>
					<option value="other"><?php esc_html_e( 'Otro', 'gano-child' ); ?></option>
				</select>
			</div>

			<div class="form-group">
				<label for="message"><?php esc_html_e( '¿Qué necesitas? (Opcional)', 'gano-child' ); ?></label>
				<textarea id="message" name="message" rows="4" aria-label="¿Qué necesitas?"></textarea>
			</div>

			<div class="form-group">
				<label>
					<input type="checkbox" name="consent" required>
					<?php esc_html_e( 'Acepto recibir comunicaciones de Gano Digital', 'gano-child' ); ?> *
				</label>
			</div>

			<button type="submit" class="btn btn-primary btn-lg">
				<?php esc_html_e( 'Solicitar Demostración', 'gano-child' ); ?>
			</button>

			<p class="form-note">
				<?php esc_html_e( 'Responderemos dentro de 24 horas hábiles.', 'gano-child' ); ?>
			</p>
		</form>

		<div id="form-message" class="form-message" style="display: none;"></div>
	</div>
</div>

