<?php
/**
 * Template Name: Gano Digital — Homepage
 * Description: Front page principal con landing SOTA v2. Dark aesthetic — no Elementor.
 *
 * @package Gano_Digital
 */

get_header();

$url_ecosistemas = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'ecosistemas' )
	: home_url( '/ecosistemas/' );

$url_dominios = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'dominios' )
	: home_url( '/dominios/' );

// /catalogo/ es la URL canónica; el resolver conserva compatibilidad con slugs históricos.
$url_catalogo = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'shop-premium', 'catalogo', 'ecosistemas' )
	: home_url( '/catalogo/' );

$precio_desde_default      = '$39.000';
$precio_desde_raw          = get_option( 'gano_precio_desde', $precio_desde_default );
$precio_desde_source       = is_scalar( $precio_desde_raw ) ? trim( (string) $precio_desde_raw ) : $precio_desde_default;
$precio_desde_before_comma = strtok( $precio_desde_source, ',' );

if ( false === $precio_desde_before_comma ) {
	$precio_desde_before_comma = $precio_desde_source;
}

$precio_desde_num = preg_replace( '/\D+/', '', $precio_desde_before_comma );

if ( '' === $precio_desde_num ) {
	$precio_desde_num = preg_replace( '/\D+/', '', $precio_desde_default );
}

$precio_desde = '$' . number_format( (int) $precio_desde_num, 0, ',', '.' );

$url_login = wp_login_url( home_url() );
?>

<div class="gano-landing-sota">

	<!-- NAVIGATION -->
	<nav class="nav" id="navbar">
		<div class="container nav-inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo">
				<div class="nav-logo-icon">
					<i class="fas fa-shield-halved"></i>
				</div>
				<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
			</a>
			<ul class="nav-links">
				<li><a href="#inicio"><?php esc_html_e( 'Inicio', 'gano-child' ); ?></a></li>
				<li><a href="#pilares"><?php esc_html_e( 'Infraestructura', 'gano-child' ); ?></a></li>
				<li><a href="#precios"><?php esc_html_e( 'Planes', 'gano-child' ); ?></a></li>
				<li><a href="#servicios"><?php esc_html_e( 'Servicios', 'gano-child' ); ?></a></li>
				<li><a href="#dominios"><?php esc_html_e( 'Dominios', 'gano-child' ); ?></a></li>
			</ul>
			<div class="nav-cta">
				<a href="<?php echo esc_url( $url_login ); ?>" class="btn btn-ghost"><?php esc_html_e( 'Ingresar', 'gano-child' ); ?></a>
				<a href="#precios" class="btn btn-primary"><?php esc_html_e( 'Crear cuenta', 'gano-child' ); ?></a>
			</div>
			<button class="mobile-toggle" aria-label="<?php esc_attr_e( 'Abrir menú', 'gano-child' ); ?>" aria-controls="mobile-menu-panel" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</nav>

	<div class="mobile-menu-backdrop"></div>

	<nav id="mobile-menu-panel" class="mobile-menu" aria-hidden="true">
		<div class="mobile-menu-header">
			<h2 class="mobile-menu-title"><?php esc_html_e( 'Menú', 'gano-child' ); ?></h2>
			<button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'Cerrar menú', 'gano-child' ); ?>">&times;</button>
		</div>
		<div class="mobile-menu-items">
			<a href="#inicio"><?php esc_html_e( 'Inicio', 'gano-child' ); ?></a>
			<a href="#pilares"><?php esc_html_e( 'Infraestructura', 'gano-child' ); ?></a>
			<a href="#precios"><?php esc_html_e( 'Planes', 'gano-child' ); ?></a>
			<a href="#servicios"><?php esc_html_e( 'Servicios', 'gano-child' ); ?></a>
			<a href="#dominios"><?php esc_html_e( 'Dominios', 'gano-child' ); ?></a>
			<a href="<?php echo esc_url( $url_login ); ?>"><?php esc_html_e( 'Ingresar', 'gano-child' ); ?></a>
		</div>
		<div class="mobile-menu-footer">
			<a href="#precios" class="btn"><?php esc_html_e( 'Crear cuenta', 'gano-child' ); ?></a>
		</div>
	</nav>

	<!-- HERO -->
	<section class="hero" id="inicio">
		<div class="hero-bg">
			<div class="hero-grid"></div>
			<div class="hero-glow"></div>
			<div class="hero-particles" id="particles"></div>
		</div>

		<div class="container">
			<div class="hero-content">
				<div class="hero-badge animate-in">
					<span class="badge badge-accent">
						<i class="fas fa-bolt"></i>
						<?php esc_html_e( 'SOTA — State of the Art', 'gano-child' ); ?>
					</span>
				</div>

				<h1 class="hero-title animate-in delay-1">
					<span class="line"><?php esc_html_e( 'Infraestructura NVMe', 'gano-child' ); ?></span>
					<span class="line gradient-text"><?php esc_html_e( 'blindada', 'gano-child' ); ?></span>
					<span class="line"><?php esc_html_e( 'y un agente IA que', 'gano-child' ); ?></span>
					<span class="line"><?php esc_html_e( 'trabaja antes de que', 'gano-child' ); ?></span>
					<span class="line"><?php esc_html_e( 'algo falle.', 'gano-child' ); ?></span>
				</h1>

				<p class="hero-subtitle animate-in delay-2">
					<?php esc_html_e( 'Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.', 'gano-child' ); ?>
				</p>

				<div class="hero-ctas animate-in delay-3">
					<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn btn-primary btn-lg">
						<i class="fas fa-rocket"></i>
						<?php esc_html_e( 'Ver planes', 'gano-child' ); ?>
					</a>
					<a href="#pilares" class="btn btn-ghost btn-lg">
						<?php esc_html_e( 'Conocer infraestructura', 'gano-child' ); ?>
					</a>
				</div>

				<div class="hero-trust animate-in delay-4">
					<div class="trust-item">
						<i class="fas fa-microchip"></i>
						<span>NVMe</span>
					</div>
					<div class="trust-item">
						<i class="fas fa-coins"></i>
						<span>COP</span>
					</div>
					<div class="trust-item">
						<i class="fas fa-headset"></i>
						<span>24/7</span>
					</div>
				</div>
			</div>
		</div>

		<div class="hero-visual">
			<div class="server-rack">
				<div class="rack-unit">
					<div class="bar"><div class="bar-fill" style="width:78%"></div></div>
					<span class="mono" style="font-size:0.7rem;color:var(--text-muted)">WP-01</span>
				</div>
				<div class="rack-unit">
					<div class="bar"><div class="bar-fill" style="width:45%"></div></div>
					<span class="mono" style="font-size:0.7rem;color:var(--text-muted)">WP-02</span>
				</div>
				<div class="rack-unit">
					<div class="bar"><div class="bar-fill" style="width:92%"></div></div>
					<span class="mono" style="font-size:0.7rem;color:var(--text-muted)">WP-03</span>
				</div>
				<div class="rack-unit">
					<div class="bar"><div class="bar-fill" style="width:34%"></div></div>
					<span class="mono" style="font-size:0.7rem;color:var(--text-muted)">EDGE-BOG</span>
				</div>
				<div class="rack-unit">
					<div class="bar"><div class="bar-fill" style="width:61%"></div></div>
					<span class="mono" style="font-size:0.7rem;color:var(--text-muted)">EDGE-MDE</span>
				</div>
			</div>
		</div>
	</section>

	<!-- SOTA BANNER -->
	<section class="sota-banner">
		<div class="container sota-inner">
			<div class="sota-text reveal">
				<span class="section-label">SOTA</span>
				<h3><?php esc_html_e( 'State of the Art', 'gano-child' ); ?></h3>
				<p>
					<?php esc_html_e( 'En Gano Digital, SOTA es nuestro estándar: arquitectura y operación al nivel que el mercado considera "lo mejor disponible hoy", sin humo ni promesas genéricas. Lo aplicamos a hosting, seguridad y acompañamiento comercial para que tu equipo sepa exactamente qué compra y por qué.', 'gano-child' ); ?>
				</p>
			</div>
			<div class="sota-stats">
				<div class="sota-stat reveal">
					<div class="sota-stat-value">99.9%</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Disponibilidad', 'gano-child' ); ?></div>
				</div>
				<div class="sota-stat reveal">
					<div class="sota-stat-value">&lt;2h</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Respuesta', 'gano-child' ); ?></div>
				</div>
				<div class="sota-stat reveal">
					<div class="sota-stat-value">24/7</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Monitoreo', 'gano-child' ); ?></div>
				</div>
			</div>
		</div>
	</section>

	<!-- PILARS -->
	<section class="section" id="pilares">
		<div class="container">
			<div class="section-header reveal">
				<span class="section-label"><?php esc_html_e( 'Infraestructura', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Cuatro pilares de la', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'infraestructura SOTA', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Lo que diferencia a Gano Digital en el mercado colombiano.', 'gano-child' ); ?></p>
			</div>

			<div class="pillars-grid">
				<div class="pillar-card reveal">
					<div class="pillar-icon">
						<i class="fas fa-gauge-high"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Velocidad real (NVMe)', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal">
					<div class="pillar-icon">
						<i class="fas fa-shield-halved"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'WordPress blindada', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal">
					<div class="pillar-icon">
						<i class="fas fa-fingerprint"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Zero-Trust operativo', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Confianza cero por defecto: identidad, sesiones y permisos bajo control. La seguridad no es un cartel: es política aplicada en capas.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal">
					<div class="pillar-icon">
						<i class="fas fa-globe"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Edge y latencia', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Contenido más cerca del usuario sin magia barata. Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia.', 'gano-child' ); ?>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- PRICING -->
	<section class="section" id="precios" style="background: var(--bg-surface);">
		<div class="container">
			<div class="section-header reveal">
				<span class="section-label"><?php esc_html_e( 'Catálogo comercial', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Planes listos para comparar en', 'gano-child' ); ?> <span class="gradient-text">COP</span></h2>
				<p class="section-desc"><?php esc_html_e( 'La homepage presenta la propuesta de valor. Los precios vigentes y la disponibilidad viven en el catálogo.', 'gano-child' ); ?></p>
			</div>

			<div class="pricing-grid" id="pricing-wordpress">
				<div class="pricing-card featured reveal" style="grid-column: 1 / -1; max-width: 640px; margin: 0 auto; text-align: center;">
					<div class="pricing-badge"><?php esc_html_e( 'Catálogo en vivo', 'gano-child' ); ?></div>
					<div class="pricing-name"><?php esc_html_e( 'Planes desde', 'gano-child' ); ?></div>
					<div class="pricing-desc"><?php esc_html_e( 'Explora planes activos, compara capacidades y confirma el precio vigente antes de comprar.', 'gano-child' ); ?></div>
					<div class="pricing-price">
						<span class="amount"><?php echo esc_html( $precio_desde ); ?></span>
						<span class="period"><?php esc_html_e( '/mes', 'gano-child' ); ?></span>
						<div class="usd"><?php esc_html_e( 'Facturación en COP · Sin contratos anuales obligatorios', 'gano-child' ); ?></div>
					</div>
					<ul class="pricing-features">
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Planes de hosting, VPS, dominios y servicios en una sola vista', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'CTAs conectados al flujo comercial activo de Gano Digital', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Precio y disponibilidad sujetos al catálogo vigente', 'gano-child' ); ?></li>
					</ul>
					<a href="<?php echo esc_url( $url_catalogo ); ?>" class="btn btn-primary btn-lg" style="width:100%"><?php esc_html_e( 'Ver catálogo completo →', 'gano-child' ); ?></a>
				</div>
			</div>
		</div>
	</section>

	<!-- SERVICES -->
	<section class="section" id="servicios">
		<div class="container">
			<div class="section-header reveal">
				<span class="section-label"><?php esc_html_e( 'Servicios', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Blindaje y', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'Optimización', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Capas de seguridad avanzada e inteligencia aplicadas a tu presencia digital.', 'gano-child' ); ?></p>
			</div>

			<div class="services-list">
				<div class="service-row reveal">
					<div class="service-icon">
						<i class="fas fa-shield-virus"></i>
					</div>
					<div class="service-info">
						<h4><?php esc_html_e( 'Inmunidad Digital (SSL + WAF)', 'gano-child' ); ?></h4>
						<p><?php esc_html_e( 'Encriptación de grado militar y firewalls de aplicación web que aprenden y neutralizan amenazas antes de que toquen tu servidor.', 'gano-child' ); ?></p>
					</div>
					<div class="service-arrow">
						<i class="fas fa-arrow-right"></i>
					</div>
				</div>

				<div class="service-row reveal">
					<div class="service-icon">
						<i class="fas fa-magnifying-glass-chart"></i>
					</div>
					<div class="service-info">
						<h4><?php esc_html_e( 'SEO de Autoridad Monolítica', 'gano-child' ); ?></h4>
						<p><?php esc_html_e( 'Estrategias de posicionamiento diseñadas para dominar las búsquedas más competitivas en el mercado colombiano.', 'gano-child' ); ?></p>
					</div>
					<div class="service-arrow">
						<i class="fas fa-arrow-right"></i>
					</div>
				</div>

				<div class="service-row reveal">
					<div class="service-icon">
						<i class="fas fa-bolt"></i>
					</div>
					<div class="service-info">
						<h4><?php esc_html_e( 'Aceleración Anycast', 'gano-child' ); ?></h4>
						<p><?php esc_html_e( 'Distribución de contenido en el borde (Edge Computing). Tu sitio se sirve desde el nodo más cercano a tu cliente.', 'gano-child' ); ?></p>
					</div>
					<div class="service-arrow">
						<i class="fas fa-arrow-right"></i>
					</div>
				</div>

				<div class="service-row reveal">
					<div class="service-icon">
						<i class="fas fa-database"></i>
					</div>
					<div class="service-info">
						<h4><?php esc_html_e( 'Resiliencia y Backup SOTA', 'gano-child' ); ?></h4>
						<p><?php esc_html_e( 'Snapshotting continuo y backups granulares. En caso de desastre, tu infraestructura se reconstruye en segundos.', 'gano-child' ); ?></p>
					</div>
					<div class="service-arrow">
						<i class="fas fa-arrow-right"></i>
					</div>
				</div>

				<div class="service-row reveal">
					<div class="service-icon">
						<i class="fas fa-envelope-circle-check"></i>
					</div>
					<div class="service-info">
						<h4><?php esc_html_e( 'Elitismo en Conectividad', 'gano-child' ); ?></h4>
						<p><?php esc_html_e( 'Correo profesional y herramientas de colaboración (GWS / M365) configuradas bajo estándares de seguridad Gano.', 'gano-child' ); ?></p>
					</div>
					<div class="service-arrow">
						<i class="fas fa-arrow-right"></i>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- DOMAIN SEARCH -->
	<section class="section domain-section" id="dominios">
		<div class="container">
			<div class="domain-box reveal">
				<span class="section-label"><?php esc_html_e( 'Dominios', 'gano-child' ); ?></span>
				<h2 class="section-title" style="margin-bottom: 8px;"><?php esc_html_e( 'Encuentra tu dominio ideal', 'gano-child' ); ?></h2>
				<p class="section-desc" style="margin-bottom: 0;"><?php esc_html_e( 'Búsqueda instantánea de TLDs disponibles para lanzar tu operación.', 'gano-child' ); ?></p>

				<form class="domain-search" action="<?php echo esc_url( $url_dominios ); ?>" method="get">
					<input type="text" name="domain" class="domain-input" placeholder="tunegocio" aria-label="<?php esc_attr_e( 'Nombre de dominio', 'gano-child' ); ?>">
					<button type="submit" class="btn btn-primary btn-lg">
						<i class="fas fa-search"></i>
						<?php esc_html_e( 'Buscar', 'gano-child' ); ?>
					</button>
				</form>

				<div class="domain-tlds">
					<div class="tld-tag">.com</div>
					<div class="tld-tag">.co</div>
					<div class="tld-tag">.com.co</div>
					<div class="tld-tag">.net</div>
					<div class="tld-tag">.org</div>
				</div>
			</div>
		</div>
	</section>

	<!-- TESTIMONIALS -->
	<section class="section">
		<div class="container">
			<div class="section-header reveal">
				<span class="section-label"><?php esc_html_e( 'Confianza', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Lo que dicen nuestros', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'clientes', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Empresas colombianas que ya operan con infraestructura SOTA.', 'gano-child' ); ?></p>
			</div>

			<div class="testimonials-grid">
				<div class="testimonial-card reveal">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
					</div>
					<p class="testimonial-text">
						"<?php esc_html_e( 'Migrar a Gano Digital redujo nuestro tiempo de carga en un 60%. El soporte técnico local entiende nuestro negocio y responde en minutos, no en horas.', 'gano-child' ); ?>"
					</p>
					<div class="testimonial-author">
						<div class="testimonial-avatar">CM</div>
						<div>
							<div class="testimonial-name">Carlos Méndez</div>
							<div class="testimonial-role"><?php esc_html_e( 'CTO — TiendaVirtual.co', 'gano-child' ); ?></div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
					</div>
					<p class="testimonial-text">
						"<?php esc_html_e( 'La facturación en COP y el soporte en español fueron decisivos. Pero lo que realmente nos sorprendió fue la velocidad: NVMe se siente.', 'gano-child' ); ?>"
					</p>
					<div class="testimonial-author">
						<div class="testimonial-avatar">AR</div>
						<div>
							<div class="testimonial-name">Ana Rodríguez</div>
							<div class="testimonial-role"><?php esc_html_e( 'Directora — Agencia Bloom', 'gano-child' ); ?></div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal">
					<div class="stars">
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star"></i>
						<i class="fas fa-star-half-stroke"></i>
					</div>
					<p class="testimonial-text">
						"<?php esc_html_e( 'Después de un intento de ataque, el WAF de Gano bloqueó todo antes de que impactara. Zero-Trust no es marketing: es política real aplicada a nuestra infraestructura.', 'gano-child' ); ?>"
					</p>
					<div class="testimonial-author">
						<div class="testimonial-avatar">JP</div>
						<div>
							<div class="testimonial-name">Juan Pablo L.</div>
							<div class="testimonial-role"><?php esc_html_e( 'Fundador — Fintech Andina', 'gano-child' ); ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- NEWSLETTER -->
	<section class="section">
		<div class="container">
			<div class="newsletter-box reveal">
				<span class="section-label"><?php esc_html_e( 'Recursos', 'gano-child' ); ?></span>
				<h2 class="section-title" style="margin-bottom: 12px;"><?php esc_html_e( 'Primero: recibe la guía SOTA', 'gano-child' ); ?></h2>
				<p class="section-desc" style="max-width: 560px; margin: 0 auto;">
					<?php esc_html_e( 'Buenas prácticas de infraestructura, seguridad y conversión para equipos que necesitan resultados sostenibles. Sin spam. Cancela cuando quieras.', 'gano-child' ); ?>
				</p>
				<form class="newsletter-form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="gano-landing-newsletter">
					<?php wp_nonce_field( 'gano_pre_registro_nonce', 'nonce' ); ?>
					<input type="hidden" name="action" value="gano_pre_registro">
					<input type="email" name="gano_email" class="newsletter-input" placeholder="tu@empresa.com" required aria-label="<?php esc_attr_e( 'Correo electrónico', 'gano-child' ); ?>">
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-paper-plane"></i>
						<?php esc_html_e( 'Suscribirse', 'gano-child' ); ?>
					</button>
				</form>
			</div>
		</div>
	</section>

	<!-- FOOTER -->
	<footer class="footer">
		<div class="container">
			<div class="footer-grid">
				<div class="footer-brand">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
						<div class="nav-logo-icon" style="width:32px;height:32px;font-size:0.875rem;">
							<i class="fas fa-shield-halved"></i>
						</div>
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
					<p>
						<?php esc_html_e( 'Un socio tecnológico que trabaja en silencio. Infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'Productos', 'gano-child' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/catalogo/?cat=hosting' ) ); ?>"><?php esc_html_e( 'Hosting WordPress', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/catalogo/?cat=hosting' ) ); ?>"><?php esc_html_e( 'Hosting Compartido', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/catalogo/?cat=vps' ) ); ?>"><?php esc_html_e( 'VPS Cloud', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/catalogo/?cat=vps' ) ); ?>"><?php esc_html_e( 'Servidores Dedicados', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/catalogo/?cat=builder' ) ); ?>"><?php esc_html_e( 'Creador de Sitios', 'gano-child' ); ?></a></li>
					</ul>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'Empresa', 'gano-child' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>"><?php esc_html_e( 'Sobre nosotros', 'gano-child' ); ?></a></li>
						<li><a href="https://status.godaddy.com/" target="_blank" rel="noopener"><?php esc_html_e( 'Estado de red', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/comenzar-aqui/' ) ); ?>"><?php esc_html_e( 'Preguntas frecuentes', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'Contacto', 'gano-child' ); ?></a></li>
					</ul>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'Legal', 'gano-child' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/terminos/' ) ); ?>"><?php esc_html_e( 'Términos y condiciones', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/privacidad/' ) ); ?>"><?php esc_html_e( 'Política de privacidad', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/terminos/' ) ); ?>"><?php esc_html_e( 'Política de uso', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/sla/' ) ); ?>"><?php esc_html_e( 'SLA', 'gano-child' ); ?></a></li>
					</ul>
				</div>
			</div>

			<div class="footer-bottom">
				<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Soberanía digital · Operación Colombia.', 'gano-child' ); ?></p>
				<div class="footer-social">
					<a href="https://x.com/ganodigital2026" target="_blank" rel="noopener nofollow" aria-label="X / Twitter — @ganodigital2026"><i class="fab fa-x-twitter"></i></a>
					<a href="https://linkedin.com/company/gano-digital" target="_blank" rel="noopener nofollow" aria-label="LinkedIn — Gano Digital"><i class="fab fa-linkedin-in"></i></a>
					<a href="https://instagram.com/gano.digital2026" target="_blank" rel="noopener nofollow" aria-label="Instagram — @gano.digital2026"><i class="fab fa-instagram"></i></a>
					<a href="https://github.com/Gano-digital" target="_blank" rel="noopener nofollow" aria-label="GitHub — Gano-digital"><i class="fab fa-github"></i></a>
				</div>
			</div>
		</div>
	</footer>

</div><!-- /.gano-landing-sota -->

<?php get_footer(); ?>
