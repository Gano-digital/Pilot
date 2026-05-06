<?php
/**
 * Plugin Name: Gano Homepage Data
 * Description: Central source for trust signals, metrics, testimonials, and certifications
 * Author: Diego Gano
 * Version: 1.0
 *
 * @package Gano_Digital
 */

/**
 * Get trust metrics for homepage
 *
 * @return array Trust metrics
 */
function gano_get_trust_metrics() {
	return array(
		'customer_count'      => '100+',
		'countries'           => 8,
		'uptime_guarantee'    => '99.9%',
		'avg_rating'          => 4.8,
		'total_reviews'       => 12,
		'years_in_operation'  => 2,
		'breaches_reported'   => 0,
	);
}

/**
 * Get sample testimonials for homepage
 *
 * @return array Testimonials
 */
function gano_get_testimonials() {
	return array(
		array(
			'name'    => 'Carlos Mendoza',
			'company' => 'FinTech Startup Colombia',
			'role'    => 'CTO',
			'quote'   => 'Soberanía digital completa. Nuestros datos en Colombia, cumplimiento regulatorio garantizado. El diferencial post-cuántico nos da tranquilidad para los próximos 10 años.',
			'rating'  => 5,
		),
		array(
			'name'    => 'Sofia Rodriguez',
			'company' => 'E-commerce LATAM',
			'role'    => 'Directora Operaciones',
			'quote'   => 'Migramos de GoDaddy a Gano. 3x más rápido, seguridad empresarial, soporte que entiende regulación local y cumplimiento normativo.',
			'rating'  => 5,
		),
		array(
			'name'    => 'Juan Pérez',
			'company' => 'Startup Tech Bogotá',
			'role'    => 'Fundador',
			'quote'   => 'Infraestructura dedicada sin ruido de shared hosting. El agente IA detectó un problema antes de que afectara el sitio en vivo.',
			'rating'  => 4.8,
		),
	);
}

/**
 * Get certifications and compliance badges
 *
 * @return array Certifications
 */
function gano_get_certifications() {
	return array(
		array(
			'name'  => 'GDPR Compliant',
			'icon'  => 'gdpr-icon.svg',
			'color' => '#0066cc',
		),
		array(
			'name'  => 'ISO 27001',
			'icon'  => 'iso27001-icon.svg',
			'color' => '#002858',
		),
		array(
			'name'  => 'Post-Quantum Ready',
			'icon'  => 'post-quantum-icon.svg',
			'color' => '#166c96',
		),
		array(
			'name'  => 'SOC 2 Type II',
			'icon'  => 'soc2-icon.svg',
			'color' => '#4a4a4a',
		),
	);
}

/**
 * Get competitive comparison data
 *
 * @return array Comparison data
 */
function gano_get_comparison_data() {
	return array(
		'headers' => array( 'Feature', 'Gano Digital', 'GoDaddy', 'Bluehost', 'Kinsta' ),
		'rows'    => array(
			array(
				'feature'  => 'Precio (USD/mes)',
				'gano'     => '$5,000',
				'godaddy'  => '$2.50–26.99',
				'bluehost' => '$2.95–$13.95',
				'kinsta'   => '$35–$900',
			),
			array(
				'feature'  => 'Data Sovereignty',
				'gano'     => '✓ Colombia',
				'godaddy'  => '✗ Global',
				'bluehost' => '✗ Global',
				'kinsta'   => '✓ 20 data centers',
			),
			array(
				'feature'  => 'Post-Quantum',
				'gano'     => '✓ Incluido',
				'godaddy'  => '✗',
				'bluehost' => '✗',
				'kinsta'   => '✗',
			),
			array(
				'feature'  => 'GDPR + DPA',
				'gano'     => '✓ DPA included',
				'godaddy'  => '✓ Available',
				'bluehost' => '⚠ Limited',
				'kinsta'   => '✓ Included',
			),
			array(
				'feature'  => 'Uptime SLA',
				'gano'     => '99.9% with penalty',
				'godaddy'  => '99.9%',
				'bluehost' => '99.9%',
				'kinsta'   => '99.9%',
			),
			array(
				'feature'  => 'Backups',
				'gano'     => '✓ Real-time',
				'godaddy'  => '✓ Daily',
				'bluehost' => '✓ Daily',
				'kinsta'   => '✓ Real-time',
			),
			array(
				'feature'  => 'Support (24/7)',
				'gano'     => '✓ Español',
				'godaddy'  => '✓ English',
				'bluehost' => '✓ English',
				'kinsta'   => '✓ English',
			),
			array(
				'feature'  => 'AI-Powered',
				'gano'     => '✓ Chat + Ops',
				'godaddy'  => '✓ Airo',
				'bluehost' => '✗',
				'kinsta'   => '✗',
			),
		),
	);
}

/**
 * Get FAQ items by category
 *
 * @return array FAQ items organized by category
 */
function gano_get_faq_items() {
	return array(
		'soberania'    => array(
			array(
				'id'       => 'faq-soberania-1',
				'question' => '¿Dónde están alojados mis datos?',
				'answer'   => 'En Colombia, bajo ley colombiana. Nunca salen de LATAM sin tu consentimiento explícito. Infraestructura dedicada con control total.',
			),
			array(
				'id'       => 'faq-soberania-2',
				'question' => '¿Puedo acceder a mis datos en cualquier momento?',
				'answer'   => 'Sí. Acceso total a data dumps, backups, logs. Sin restricciones. Data portability garantizada.',
			),
			array(
				'id'       => 'faq-soberania-3',
				'question' => '¿Qué pasa si hay orden judicial del extranjero?',
				'answer'   => 'Protegido por ley colombiana. No respondemos a órdenes US sin proceso legal en Colombia. Cumplimiento garantizado con regulación local.',
			),
		),
		'seguridad'    => array(
			array(
				'id'       => 'faq-seguridad-1',
				'question' => '¿Qué es cifrado post-cuántico?',
				'answer'   => 'Protección contra computadoras cuánticas que podrían quebrantar encriptación moderna. Listo hoy para amenazas de 2030+. Store-now-decrypt-later proof.',
			),
			array(
				'id'       => 'faq-seguridad-2',
				'question' => '¿Hacen auditorías externas?',
				'answer'   => 'Sí. Trimestrales. Certificaciones SOC 2 Type II, ISO 27001. Reportes disponibles bajo NDA.',
			),
			array(
				'id'       => 'faq-seguridad-3',
				'question' => '¿Qué pasa si me hackean?',
				'answer'   => 'Recovery <1 hora con backups en tiempo real. Agente IA monitorea 24/7 y detiene amenazas antes de que impacten.',
			),
		),
		'cumplimiento' => array(
			array(
				'id'       => 'faq-cumplimiento-1',
				'question' => '¿Cumplimos GDPR?',
				'answer'   => 'Completamente. DPA firmado. Auditorías regulares. Full-disk encryption. Data Processing Agreement disponible.',
			),
			array(
				'id'       => 'faq-cumplimiento-2',
				'question' => '¿Qué regulación colombiana aplica?',
				'answer'   => 'DIAN (fiscal), Superfinanciera (fintech), Bancoldex (comercio exterior), normas ICONTEC. Soporte especializado en regulación local.',
			),
		),
		'planes'       => array(
			array(
				'id'       => 'faq-planes-1',
				'question' => '¿Cuál plan necesito?',
				'answer'   => 'Básico: startups (control de datos). Avanzado: e-commerce/SaaS (rendimiento + seguridad). Soberanía: fintech/regulado (dedicado + post-cuántico).',
			),
			array(
				'id'       => 'faq-planes-2',
				'question' => '¿Puedo cambiar de plan?',
				'answer'   => 'Sí. Escalamiento sin downtime. Costo prorrateado. Infraestructura redimensionada en minutos, no días.',
			),
		),
	);
}
