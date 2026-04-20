<?php
/**
 * Template Name: Cómo comprar — Registro y checkout
 * Description: Página de conversión: explica el flujo hacia el carrito GoDaddy Reseller y reduce fricción pre-compra.
 *
 * @package gano-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$url_shop      = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'shop-premium', 'tienda', 'catalogo' ) : home_url( '/shop-premium/' );
$url_dominios  = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'dominios' ) : home_url( '/dominios/' );
$url_contacto  = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'contacto' ) : home_url( '/contacto/' );
$url_diag      = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'diagnostico-digital', 'diagnostico' ) : home_url( '/diagnostico-digital/' );
$url_privacidad = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'privacidad', 'politica-de-privacidad' ) : home_url( '/privacidad/' );
$url_terminos  = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'terminos', 'terminos-y-condiciones' ) : home_url( '/terminos/' );

$faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'mainEntity' => array(
		array(
			'@type'          => 'Question',
			'name'           => __( '¿Dónde se completa el pago cuando compro un plan?', 'gano-child' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => __( 'El checkout ocurre en el carrito de GoDaddy Reseller (dominio cart.secureserver.net o equivalente), con la configuración de marca blanca de tu reseller. Gano Digital no procesa ni almacena datos de tarjeta.', 'gano-child' ),
			),
		),
		array(
			'@type'          => 'Question',
			'name'           => __( '¿Gano Digital guarda mi número de tarjeta?', 'gano-child' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => __( 'No. Los datos de pago los ingresa directamente en el operador de checkout (GoDaddy). Puedes revisar cómo tratamos otros datos en la política de privacidad del sitio.', 'gano-child' ),
			),
		),
		array(
			'@type'          => 'Question',
			'name'           => __( '¿Los precios están en pesos colombianos (COP)?', 'gano-child' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => __( 'El catálogo está pensado para operación en COP. En el carrito, verifica que la moneda mostrada sea la esperada antes de confirmar.', 'gano-child' ),
			),
		),
		array(
			'@type'          => 'Question',
			'name'           => __( '¿Qué pasa si el botón dice “configuración pendiente”?', 'gano-child' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => __( 'Significa que falta completar el Product Family ID (PFID) o el Private Label ID en la configuración del reseller. Escríbenos por contacto y lo resolvemos sin exponerte a cargos incorrectos.', 'gano-child' ),
			),
		),
		array(
			'@type'          => 'Question',
			'name'           => __( '¿Puedo hablar con alguien antes de pagar?', 'gano-child' ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => __( 'Sí. Usa el contacto o el diagnóstico digital si quieres validar encaje del plan con tu escenario real.', 'gano-child' ),
			),
		),
	),
);

add_action(
	'wp_head',
	static function () use ( $faq_schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	},
	99
);

get_header();
?>

<main id="gano-main-content" class="gano-start-journey gano-trust-page gano-km-shell gano-on-dark">

	<section class="gano-dark-section gano-trust-hero gano-start-journey__hero">
		<div class="gano-container gano-start-journey__hero-inner">
			<p class="gano-label-pill gano-km-live-badge"><?php esc_html_e( 'Flujo comercial', 'gano-child' ); ?></p>
			<h1>
				<?php esc_html_e( 'De la duda al plan:', 'gano-child' ); ?>
				<span class="gano-start-journey__h1-gradient"><?php esc_html_e( 'cómo comprar con claridad', 'gano-child' ); ?></span>
			</h1>
			<p class="gano-trust-hero__sub gano-start-journey__lede">
				<?php esc_html_e( 'Aquí no hay letra pequeña escondida: te contamos qué ocurre cuando eliges un producto, en qué página se cobra y qué hace Gano Digital frente a eso. Objetivo simple — que avances o pidas ayuda con confianza.', 'gano-child' ); ?>
			</p>
			<p class="gano-start-journey__hero-cta">
				<a class="gano-btn gano-btn-primary" href="<?php echo esc_url( $url_shop ); ?>"><?php esc_html_e( 'Ir al catálogo SOTA', 'gano-child' ); ?></a>
				<a class="gano-btn-outline" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Hablar antes de pagar', 'gano-child' ); ?></a>
			</p>
		</div>
	</section>

	<section class="gano-trust-section gano-start-journey__trust" aria-labelledby="gano-start-trust-heading">
		<div class="gano-container">
			<h2 id="gano-start-trust-heading" class="screen-reader-text"><?php esc_html_e( 'Por qué comprar con este flujo', 'gano-child' ); ?></h2>
			<ul class="gano-start-journey__trust-list">
				<li><strong><?php esc_html_e( 'COP', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Operación comercial local', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Checkout GoDaddy', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Pago en operador autorizado', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Sin tarjeta en Gano', 'gano-child' ); ?></strong><span><?php esc_html_e( 'No almacenamos datos de pago', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Soporte real', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Español, contexto WordPress', 'gano-child' ); ?></span></li>
			</ul>
		</div>
	</section>

	<section class="gano-trust-section gano-trust-section--alt" aria-labelledby="gano-start-steps-heading">
		<div class="gano-container">
			<h2 id="gano-start-steps-heading"><?php esc_html_e( 'Tres pasos, sin sorpresas', 'gano-child' ); ?></h2>
			<p class="gano-el-prose-narrow gano-start-journey__intro">
				<?php esc_html_e( 'El catálogo vive en gano.digital; el cobro y la creación de cuenta del producto ocurren en la infraestructura de GoDaddy bajo el programa reseller — igual que miles de partners en el mundo, con marca y precios acordes a tu mercado.', 'gano-child' ); ?>
			</p>
			<ol class="gano-start-journey__steps">
				<li>
					<h3><?php esc_html_e( '1. Eliges producto y duración', 'gano-child' ); ?></h3>
					<p>
						<?php esc_html_e( 'En el catálogo SOTA comparas planes (hosting, seguridad, add-ons). Los precios mostrados están pensados para orientar; el detalle final lo confirma el carrito.', 'gano-child' ); ?>
					</p>
					<p class="gano-start-journey__step-link">
						<a href="<?php echo esc_url( $url_shop ); ?>"><?php esc_html_e( 'Abrir catálogo →', 'gano-child' ); ?></a>
						&nbsp;·&nbsp;
						<a href="<?php echo esc_url( $url_dominios ); ?>"><?php esc_html_e( 'Dominios →', 'gano-child' ); ?></a>
					</p>
				</li>
				<li>
					<h3><?php esc_html_e( '2. Checkout seguro (fuera de gano.digital)', 'gano-child' ); ?></h3>
					<p>
						<?php esc_html_e( 'Al pulsar adquirir, el navegador abre el carrito GoDaddy Reseller. Ahí creas o usas cuenta, facturación y método de pago. Ese entorno es quien tokeniza y procesa el cobro — no una pasarela improvisada en nuestro WordPress.', 'gano-child' ); ?>
					</p>
				</li>
				<li>
					<h3><?php esc_html_e( '3. Activación y soporte Gano', 'gano-child' ); ?></h3>
					<p>
						<?php esc_html_e( 'Cuando el producto queda activo en el operador, seguimos con hardening, migraciones o dudas operativas según tu plan. Si algo no cuadra en el camino, prefieres escribirnos antes que frustrarte en silencio.', 'gano-child' ); ?>
					</p>
				</li>
			</ol>
		</div>
	</section>

	<section class="gano-trust-section" aria-labelledby="gano-start-paths-heading">
		<div class="gano-container">
			<h2 id="gano-start-paths-heading"><?php esc_html_e( 'Elige tu ritmo', 'gano-child' ); ?></h2>
			<div class="gano-start-journey__paths">
				<article class="gano-start-journey__path gano-start-journey__path--primary">
					<h3><?php esc_html_e( 'Ya sé qué necesito', 'gano-child' ); ?></h3>
					<p><?php esc_html_e( 'Ve al catálogo, filtra por categoría y pulsa adquirir en el plan correcto. Si el botón está pendiente de configuración, no fuerces el flujo: contáctanos.', 'gano-child' ); ?></p>
					<a class="gano-btn gano-btn-primary" href="<?php echo esc_url( $url_shop ); ?>"><?php esc_html_e( 'Ir al catálogo', 'gano-child' ); ?></a>
				</article>
				<article class="gano-start-journey__path">
					<h3><?php esc_html_e( 'Quiero validar antes de pagar', 'gano-child' ); ?></h3>
					<p><?php esc_html_e( 'Usa el diagnóstico digital o escríbenos. Te ayudamos a mapear hosting, dominio y seguridad a tu etapa — sin presión para cerrar en la primera llamada.', 'gano-child' ); ?></p>
					<p class="gano-start-journey__path-row">
						<a class="gano-btn-outline" href="<?php echo esc_url( $url_diag ); ?>"><?php esc_html_e( 'Diagnóstico', 'gano-child' ); ?></a>
						<a class="gano-btn-outline" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Contacto', 'gano-child' ); ?></a>
					</p>
				</article>
			</div>
		</div>
	</section>

	<section class="gano-trust-section gano-trust-section--alt gano-start-journey__faq" aria-labelledby="gano-start-faq-heading">
		<div class="gano-container gano-el-prose-narrow">
			<h2 id="gano-start-faq-heading"><?php esc_html_e( 'Preguntas frecuentes', 'gano-child' ); ?></h2>

			<details class="gano-start-journey__details">
				<summary><?php esc_html_e( '¿Dónde se completa el pago cuando compro un plan?', 'gano-child' ); ?></summary>
				<p><?php esc_html_e( 'En el carrito de GoDaddy Reseller (p. ej. cart.secureserver.net), con la configuración de tu programa reseller. Gano Digital no procesa ni almacena datos de tarjeta.', 'gano-child' ); ?></p>
			</details>

			<details class="gano-start-journey__details">
				<summary><?php esc_html_e( '¿Gano Digital guarda mi número de tarjeta?', 'gano-child' ); ?></summary>
				<p>
					<?php esc_html_e( 'No. Los datos de pago los ingresa directamente en el operador de checkout. Puedes revisar el tratamiento de otros datos aquí:', 'gano-child' ); ?>
					<a href="<?php echo esc_url( $url_privacidad ); ?>"><?php esc_html_e( 'Política de privacidad', 'gano-child' ); ?></a>.
				</p>
			</details>

			<details class="gano-start-journey__details">
				<summary><?php esc_html_e( '¿Los precios están en pesos colombianos (COP)?', 'gano-child' ); ?></summary>
				<p><?php esc_html_e( 'El sitio está orientado a operación en COP. En el carrito, confirma la moneda antes de pagar.', 'gano-child' ); ?></p>
			</details>

			<details class="gano-start-journey__details">
				<summary><?php esc_html_e( '¿Qué pasa si el botón dice “configuración pendiente”?', 'gano-child' ); ?></summary>
				<p><?php esc_html_e( 'Falta enlazar el Product Family ID (PFID) o revisar el Private Label ID. Escríbenos para no exponerte a un cargo incorrecto o un carrito vacío.', 'gano-child' ); ?></p>
			</details>

			<details class="gano-start-journey__details">
				<summary><?php esc_html_e( '¿Qué términos aplican al producto?', 'gano-child' ); ?></summary>
				<p>
					<?php esc_html_e( 'Aplican las condiciones del fabricante/hosting (GoDaddy) para el producto subyacente, además de los términos comerciales que publicamos nosotros sobre el servicio Gano.', 'gano-child' ); ?>
					<a href="<?php echo esc_url( $url_terminos ); ?>"><?php esc_html_e( 'Ver términos del sitio', 'gano-child' ); ?></a>.
				</p>
			</details>
		</div>
	</section>

	<section class="gano-dark-section gano-start-journey__final-cta" id="comenzar-compra-ahora" aria-labelledby="gano-start-final-heading">
		<div class="gano-container">
			<h2 id="gano-start-final-heading"><?php esc_html_e( 'Listo para el siguiente paso', 'gano-child' ); ?></h2>
			<p class="gano-start-journey__final-copy">
				<?php esc_html_e( 'El mejor momento para mejorar tu infraestructura es cuando ya entiendes qué estás comprando. Si es tu caso, el catálogo te espera.', 'gano-child' ); ?>
			</p>
			<p class="gano-start-journey__hero-cta">
				<a class="gano-btn gano-btn-primary" href="<?php echo esc_url( $url_shop ); ?>"><?php esc_html_e( 'Comprar en el catálogo', 'gano-child' ); ?></a>
				<a class="gano-btn-outline" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Prefiero asesoría humana', 'gano-child' ); ?></a>
			</p>
		</div>
	</section>

</main>

<?php
get_footer();
