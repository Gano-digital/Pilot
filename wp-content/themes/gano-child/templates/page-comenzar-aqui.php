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

$url_shop        = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'shop-premium', 'tienda', 'catalogo' ) : home_url( '/shop-premium/' );
$url_ecosistemas = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'ecosistemas' ) : home_url( '/ecosistemas/' );
$url_dominios    = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'dominios' ) : home_url( '/dominios/' );
$url_contacto    = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'contacto' ) : home_url( '/contacto/' );
$url_diag        = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'diagnostico-digital', 'diagnostico' ) : home_url( '/diagnostico-digital/' );
$url_privacidad  = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'privacidad', 'politica-de-privacidad' ) : home_url( '/privacidad/' );
$url_terminos    = function_exists( 'gano_resolve_page_url' ) ? gano_resolve_page_url( 'terminos', 'terminos-y-condiciones' ) : home_url( '/terminos/' );

/**
 * URL del carrito con un plan de entrada (creación de cuenta en checkout GoDaddy).
 * Si el PFID aún no está configurado, se usa el catálogo completo.
 */
$gano_cart_register_url = $url_ecosistemas;
if ( function_exists( 'gano_rstore_cart_url' ) && defined( 'GANO_PFID_HOSTING_ECONOMIA' ) ) {
	$gano_entry_pfid = constant( 'GANO_PFID_HOSTING_ECONOMIA' );
	if ( is_string( $gano_entry_pfid ) && '' !== $gano_entry_pfid && 'PENDING_RCC' !== $gano_entry_pfid ) {
		$gano_try_cart = gano_rstore_cart_url( $gano_entry_pfid, 12 );
		if ( is_string( $gano_try_cart ) && '' !== $gano_try_cart && '#' !== $gano_try_cart ) {
			$gano_cart_register_url = $gano_try_cart;
		}
	}
}

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
				'text'  => __( 'En gano.digital verás referencia en COP y USD. El cobro lo confirma el carrito GoDaddy Reseller; revisa moneda y total antes de pagar.', 'gano-child' ),
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

<main id="gano-main-content" class="gano-start-journey gano-trust-page gano-km-shell gano-on-dark" data-gano-start-journey>

	<section class="gano-start-journey__hero" id="registro-checkout" aria-label="<?php esc_attr_e( 'Guía de compra y registro', 'gano-child' ); ?>">
		<div class="gano-km-container">
			<div class="gano-km-hero">
				<div class="gano-start-journey__hero-main">
					<p class="gano-km-live-badge"><?php esc_html_e( 'Guía de compra', 'gano-child' ); ?></p>
					<h1 class="gano-km-title">
						<?php esc_html_e( 'Compra con claridad', 'gano-child' ); ?>
						<span class="gano-km-title-accent"><?php esc_html_e( 'sin sorpresas', 'gano-child' ); ?></span>
					</h1>
					<p class="gano-km-lead">
						<?php esc_html_e( 'Tu cuenta de cliente del producto se crea en el checkout de GoDaddy Reseller (marca blanca), no en un formulario oculto de esta web. Elige plan aquí, abre el carrito y allí registras correo, facturación y pago con total transparencia.', 'gano-child' ); ?>
					</p>
					<ol class="gano-start-journey__register-track" aria-label="<?php esc_attr_e( 'Pasos para registrarte y comprar', 'gano-child' ); ?>">
						<li>
							<span class="gano-start-journey__register-track-step"><?php esc_html_e( 'Paso 1', 'gano-child' ); ?></span>
							<span class="gano-start-journey__register-track-text"><?php esc_html_e( 'Abre el carrito con un plan (o elige otro en el catálogo).', 'gano-child' ); ?></span>
						</li>
						<li>
							<span class="gano-start-journey__register-track-step"><?php esc_html_e( 'Paso 2', 'gano-child' ); ?></span>
							<span class="gano-start-journey__register-track-text"><?php esc_html_e( 'En cart.secureserver.net crea tu cuenta o inicia sesión.', 'gano-child' ); ?></span>
						</li>
						<li>
							<span class="gano-start-journey__register-track-step"><?php esc_html_e( 'Paso 3', 'gano-child' ); ?></span>
							<span class="gano-start-journey__register-track-text"><?php esc_html_e( 'Confirma moneda e importe; al pagar, activamos soporte Gano.', 'gano-child' ); ?></span>
						</li>
					</ol>
					<div class="gano-km-cta-row gano-start-journey__hero-ctas">
						<a class="gano-km-btn-primary" href="<?php echo esc_url( $gano_cart_register_url ); ?>" rel="noopener noreferrer"><?php esc_html_e( 'Crear cuenta en el checkout', 'gano-child' ); ?></a>
						<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Ver catálogo completo', 'gano-child' ); ?></a>
						<a class="gano-km-btn-secondary gano-start-journey__cta-quiet" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Hablar con el equipo', 'gano-child' ); ?></a>
					</div>
					<p class="gano-start-journey__microcopy">
						<?php esc_html_e( 'Tip: revisa el checklist (al lado en escritorio, debajo en móvil) antes de pagar. El botón principal te lleva al carrito seguro donde se completa el registro.', 'gano-child' ); ?>
					</p>
				</div>

				<aside class="gano-start-journey__glass" aria-label="<?php esc_attr_e( 'Checklist antes de pagar', 'gano-child' ); ?>">
					<h2 class="gano-start-journey__glass-title"><?php esc_html_e( 'Antes de pagar', 'gano-child' ); ?></h2>
					<ul class="gano-start-journey__checklist">
						<li><?php esc_html_e( 'Verifica moneda (COP / USD) y el plan en el resumen del carrito.', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'El pago ocurre en GoDaddy Reseller (carrito marca blanca).', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'Gano Digital no almacena datos de tarjeta.', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'Si algo no cuadra, para y escríbenos.', 'gano-child' ); ?></li>
					</ul>
					<div class="gano-start-journey__glass-cta">
						<a class="gano-km-btn-primary" href="<?php echo esc_url( $gano_cart_register_url ); ?>" rel="noopener noreferrer"><?php esc_html_e( 'Ir al checkout', 'gano-child' ); ?></a>
						<a class="gano-km-btn-secondary" href="#comenzar-compra-ahora"><?php esc_html_e( 'Ver más abajo', 'gano-child' ); ?></a>
						<a class="gano-km-btn-tertiary" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Resolver dudas', 'gano-child' ); ?></a>
					</div>
				</aside>
			</div>
		</div>
	</section>

	<section class="gano-trust-section gano-start-journey__trust" aria-labelledby="gano-start-trust-heading">
		<div class="gano-km-container">
			<h2 id="gano-start-trust-heading" class="screen-reader-text"><?php esc_html_e( 'Por qué comprar con este flujo', 'gano-child' ); ?></h2>
			<ul class="gano-start-journey__trust-list">
				<li><strong><?php esc_html_e( 'COP + USD*', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Referencia en sitio; cobro según carrito', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Checkout GoDaddy', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Pago en operador autorizado', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Sin tarjeta en Gano', 'gano-child' ); ?></strong><span><?php esc_html_e( 'No almacenamos datos de pago', 'gano-child' ); ?></span></li>
				<li><strong><?php esc_html_e( 'Soporte real', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Español, contexto WordPress', 'gano-child' ); ?></span></li>
			</ul>
		</div>
	</section>

	<section class="gano-trust-section gano-trust-section--alt" aria-labelledby="gano-start-steps-heading">
		<div class="gano-km-container">
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
						<a href="<?php echo esc_url( $gano_cart_register_url ); ?>" rel="noopener noreferrer"><?php esc_html_e( 'Abrir carrito (registro) →', 'gano-child' ); ?></a>
						&nbsp;·&nbsp;
						<a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Catálogo Ecosistemas →', 'gano-child' ); ?></a>
						&nbsp;·&nbsp;
						<a href="<?php echo esc_url( $url_dominios ); ?>"><?php esc_html_e( 'Dominios →', 'gano-child' ); ?></a>
					</p>
				</li>
				<li>
					<h3><?php esc_html_e( '2. Checkout seguro (fuera de gano.digital)', 'gano-child' ); ?></h3>
					<p>
						<?php esc_html_e( 'Al continuar, el navegador abre el carrito GoDaddy Reseller. Ahí es donde se crea tu cuenta de cliente (correo y contraseña del operador), datos de facturación y método de pago. Ese entorno tokeniza y cobra; Gano Digital no clona ese registro en WordPress.', 'gano-child' ); ?>
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
		<div class="gano-km-container">
			<h2 id="gano-start-paths-heading"><?php esc_html_e( 'Elige tu ritmo', 'gano-child' ); ?></h2>
			<div class="gano-start-journey__paths">
				<article class="gano-start-journey__path gano-start-journey__path--primary">
					<h3><?php esc_html_e( 'Ya sé qué necesito', 'gano-child' ); ?></h3>
					<p><?php esc_html_e( 'Usa el checkout con plan de entrada o abre el catálogo completo y pulsa “Contratar” en el plan que necesites. Si ves “configuración pendiente”, no fuerces el flujo: escríbenos.', 'gano-child' ); ?></p>
					<p class="gano-start-journey__path-row gano-start-journey__path-row--stack">
						<a class="gano-km-btn-primary" href="<?php echo esc_url( $gano_cart_register_url ); ?>" rel="noopener noreferrer"><?php esc_html_e( 'Checkout y registro', 'gano-child' ); ?></a>
						<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Catálogo Ecosistemas', 'gano-child' ); ?></a>
					</p>
				</article>
				<article class="gano-start-journey__path">
					<h3><?php esc_html_e( 'Quiero validar antes de pagar', 'gano-child' ); ?></h3>
					<p><?php esc_html_e( 'Usa el diagnóstico digital o escríbenos. Te ayudamos a mapear hosting, dominio y seguridad a tu etapa — sin presión para cerrar en la primera llamada.', 'gano-child' ); ?></p>
					<p class="gano-start-journey__path-row">
						<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_diag ); ?>"><?php esc_html_e( 'Diagnóstico', 'gano-child' ); ?></a>
						<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Contacto', 'gano-child' ); ?></a>
					</p>
				</article>
			</div>
		</div>
	</section>

	<?php
	gano_cta_registro(array(
		'heading'     => __( '¿Listo para crear tu cuenta en el checkout?', 'gano-child' ),
		'description' => __( 'Un clic te lleva al carrito seguro de GoDaddy Reseller: allí eliges método de pago y completas el registro de cliente. Luego te acompañamos en activación y soporte.', 'gano-child' ),
		'button_text' => __( 'Ir al checkout y registrarme', 'gano-child' ),
		'button_url'  => $gano_cart_register_url,
	));
	?>

	<section class="gano-trust-section gano-trust-section--alt gano-start-journey__faq" aria-labelledby="gano-start-faq-heading">
		<div class="gano-km-container gano-el-prose-narrow">
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
				<p><?php esc_html_e( 'En gano.digital mostramos referencia en COP y USD. La moneda definitiva y el total los confirma el carrito GoDaddy antes de pagar.', 'gano-child' ); ?></p>
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
		<div class="gano-km-container">
			<h2 id="gano-start-final-heading"><?php esc_html_e( 'Listo para el siguiente paso', 'gano-child' ); ?></h2>
			<p class="gano-start-journey__final-copy">
				<?php esc_html_e( 'El mejor momento para mejorar tu infraestructura es cuando ya entiendes qué estás comprando. Si es tu caso, el catálogo te espera.', 'gano-child' ); ?>
			</p>
			<p class="gano-start-journey__hero-cta">
				<a class="gano-km-btn-primary" href="<?php echo esc_url( $gano_cart_register_url ); ?>" rel="noopener noreferrer"><?php esc_html_e( 'Checkout: crear cuenta y pagar', 'gano-child' ); ?></a>
				<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Explorar todos los planes', 'gano-child' ); ?></a>
				<a class="gano-km-btn-secondary" href="<?php echo esc_url( $url_contacto ); ?>"><?php esc_html_e( 'Prefiero asesoría humana', 'gano-child' ); ?></a>
			</p>
		</div>
	</section>

</main>

<?php
get_footer();
