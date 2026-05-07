<?php
/**
 * Ecosystems Section — Featured Hosting Plans Teaser
 * Part of homepage v2 modular structure
 * Links to full catalog at /ecosistemas/
 *
 * @package Gano_Digital
 */

// Featured plans to display on homepage
$featured_plans = array(
	array(
		'name'        => 'WordPress Básico',
		'tag'         => '',
		'price'       => '$196.000',
		'period'      => '/mes',
		'description' => 'Perfecto para starters, blogs y pequeños negocios. WordPress preinstalado, SSL gratuito, y actualizaciones automáticas.',
		'features'    => array( '20 GB NVMe', 'SSL incluido', 'Backups diarios', 'Soporte 24/7' ),
		'cta'         => 'Conocer plan',
		'color'       => '#1B4FD8',
		'colorBg'     => '#E8EEFB',
	),
	array(
		'name'        => 'WordPress Deluxe',
		'tag'         => 'Más popular',
		'price'       => '$450.000',
		'period'      => '/mes',
		'description' => 'Ideal para ecommerces y agencias. Hardening de seguridad, backups bajo demanda, y soporte priorizado.',
		'features'    => array( '40 GB NVMe', 'Hardening activo', 'Backups on-demand', 'Soporte < 4h' ),
		'cta'         => 'Activar ahora',
		'color'       => '#00C26B',
		'colorBg'     => '#E0FAF0',
	),
	array(
		'name'        => 'WordPress Ultimate',
		'tag'         => 'Recomendado',
		'price'       => '$890.000',
		'period'      => '/mes',
		'description' => 'Hosting empresarial con SLA 99.9%, Agente IA, y soporte dedicado para operaciones críticas.',
		'features'    => array( '75 GB NVMe', 'SLA 99.9%', 'Agente IA', 'Soporte < 2h' ),
		'cta'         => 'Solicitar Ultimate',
		'color'       => '#D4AF37',
		'colorBg'     => '#FFF8E1',
	),
);

?>

<section class="ecosystems-teaser">
	<div class="ecosystems-container">
		<div class="ecosystems-header">
			<h2><?php esc_html_e( 'Nuestros Ecosistemas', 'gano-child' ); ?></h2>
			<p><?php esc_html_e( 'Elige el hosting perfecto para tu proyecto. Desde starters hasta operaciones empresariales.', 'gano-child' ); ?></p>
		</div>

		<div class="ecosystems-grid">
			<?php foreach ( $featured_plans as $plan ) : ?>
				<div class="ecosystems-card" style="border-left: 4px solid <?php echo esc_attr( $plan['color'] ); ?>;">
					<?php if ( ! empty( $plan['tag'] ) ) : ?>
						<span class="ecosystems-card__tag"><?php echo esc_html( $plan['tag'] ); ?></span>
					<?php endif; ?>

					<h3 class="ecosystems-card__title"><?php echo esc_html( $plan['name'] ); ?></h3>

					<div class="ecosystems-card__price">
						<span class="ecosystems-card__amount"><?php echo esc_html( $plan['price'] ); ?></span>
						<span class="ecosystems-card__period"><?php echo esc_html( $plan['period'] ); ?></span>
					</div>

					<p class="ecosystems-card__description"><?php echo esc_html( $plan['description'] ); ?></p>

					<ul class="ecosystems-card__features">
						<?php foreach ( $plan['features'] as $feature ) : ?>
							<li><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>

					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'ecosistemas' ) ) ); ?>" class="ecosystems-card__cta" style="background-color: <?php echo esc_attr( $plan['color'] ); ?>;">
						<?php echo esc_html( $plan['cta'] ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="ecosystems-footer">
			<p><?php esc_html_e( '¿Necesitas ayuda para elegir? ', 'gano-child' ); ?><a href="#cta-final" class="ecosystems-link"><?php esc_html_e( 'Solicita una demostración personalizada', 'gano-child' ); ?></a></p>
		</div>
	</div>
</section>

<style>
.ecosystems-teaser {
	padding: 80px 0;
	background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
}

.ecosystems-container {
	max-width: 1280px;
	margin: 0 auto;
	padding: 0 16px;
}

@media (min-width: 640px) {
	.ecosystems-container {
		padding: 0 24px;
	}
}

@media (min-width: 1024px) {
	.ecosystems-container {
		padding: 0 32px;
	}
}

.ecosystems-header {
	text-align: center;
	margin-bottom: 60px;
}

.ecosystems-header h2 {
	font-family: 'Plus Jakarta Sans', sans-serif;
	font-size: clamp(1.75rem, 4vw, 2.5rem);
	font-weight: 700;
	margin-bottom: 16px;
	color: #111827;
}

.ecosystems-header p {
	font-size: 1.0625rem;
	color: #4B5563;
	max-width: 600px;
	margin: 0 auto;
	line-height: 1.6;
}

.ecosystems-grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: 32px;
	margin-bottom: 48px;
}

@media (min-width: 768px) {
	.ecosystems-grid {
		grid-template-columns: repeat(3, 1fr);
	}
}

.ecosystems-card {
	position: relative;
	background: white;
	border-radius: 16px;
	padding: 32px;
	box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
	transition: transform 0.3s ease, box-shadow 0.3s ease;
	display: flex;
	flex-direction: column;
}

.ecosystems-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.02);
}

.ecosystems-card__tag {
	display: inline-block;
	font-size: 0.75rem;
	font-weight: 700;
	text-transform: uppercase;
	padding: 6px 12px;
	background: #E8EEFB;
	color: #1B4FD8;
	border-radius: 24px;
	margin-bottom: 16px;
	width: fit-content;
}

.ecosystems-card__title {
	font-family: 'Plus Jakarta Sans', sans-serif;
	font-size: 1.25rem;
	font-weight: 700;
	margin-bottom: 12px;
	color: #111827;
}

.ecosystems-card__price {
	display: flex;
	align-items: baseline;
	gap: 4px;
	margin-bottom: 12px;
}

.ecosystems-card__amount {
	font-family: 'Plus Jakarta Sans', sans-serif;
	font-size: 2rem;
	font-weight: 800;
	color: #111827;
}

.ecosystems-card__period {
	font-size: 0.9375rem;
	color: #6B7280;
}

.ecosystems-card__description {
	font-size: 0.9375rem;
	color: #4B5563;
	line-height: 1.6;
	margin-bottom: 20px;
	flex-grow: 1;
}

.ecosystems-card__features {
	list-style: none;
	padding: 0;
	margin-bottom: 24px;
	display: flex;
	flex-direction: column;
	gap: 8px;
}

.ecosystems-card__features li {
	font-size: 0.9375rem;
	color: #4B5563;
	padding-left: 24px;
	position: relative;
}

.ecosystems-card__features li:before {
	content: '✓';
	position: absolute;
	left: 0;
	color: #10B981;
	font-weight: 700;
}

.ecosystems-card__cta {
	display: inline-block;
	padding: 12px 24px;
	border-radius: 12px;
	font-weight: 600;
	font-size: 0.9375rem;
	color: white;
	text-decoration: none;
	text-align: center;
	transition: opacity 0.3s ease;
	border: none;
	cursor: pointer;
	font-family: inherit;
}

.ecosystems-card__cta:hover {
	opacity: 0.9;
}

.ecosystems-footer {
	text-align: center;
	padding-top: 40px;
	border-top: 1px solid #E5E7EB;
}

.ecosystems-footer p {
	font-size: 0.9375rem;
	color: #4B5563;
}

.ecosystems-link {
	color: #1B4FD8;
	text-decoration: none;
	font-weight: 600;
	transition: color 0.3s ease;
}

.ecosystems-link:hover {
	color: #1240B0;
	text-decoration: underline;
}
</style>
