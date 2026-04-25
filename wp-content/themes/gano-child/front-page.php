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
				<li><a href="#nosotros"><?php esc_html_e( 'Nosotros', 'gano-child' ); ?></a></li>
				<li><a href="#contacto"><?php esc_html_e( 'Contacto', 'gano-child' ); ?></a></li>
			</ul>
			<div class="nav-cta">
				<a href="<?php echo esc_url( $url_login ); ?>" class="btn btn-ghost"><?php esc_html_e( 'Ingresar', 'gano-child' ); ?></a>
				<a href="#precios" class="btn btn-primary"><?php esc_html_e( 'Crear cuenta', 'gano-child' ); ?></a>
			</div>
			<button class="mobile-toggle" aria-label="<?php esc_attr_e( 'Abrir menú', 'gano-child' ); ?>" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</nav>

	<!-- Mobile Menu -->
	<div class="mobile-menu-backdrop"></div>

	<nav class="mobile-menu" aria-hidden="true">
		<div class="mobile-menu-header">
			<h2 style="margin: 0; font-size: 1.25rem; color: white;"><?php esc_html_e( 'Menú', 'gano-child' ); ?></h2>
			<button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'Cerrar menú', 'gano-child' ); ?>">&times;</button>
		</div>
		<div class="mobile-menu-items">
			<a href="#inicio"><?php esc_html_e( 'Inicio', 'gano-child' ); ?></a>
			<a href="#pilares"><?php esc_html_e( 'Infraestructura', 'gano-child' ); ?></a>
			<a href="#precios"><?php esc_html_e( 'Planes', 'gano-child' ); ?></a>
			<a href="#servicios"><?php esc_html_e( 'Servicios', 'gano-child' ); ?></a>
			<a href="#nosotros"><?php esc_html_e( 'Nosotros', 'gano-child' ); ?></a>
			<a href="#contacto"><?php esc_html_e( 'Contacto', 'gano-child' ); ?></a>
		</div>
		<div class="mobile-menu-footer">
			<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn"><?php esc_html_e( 'Crear cuenta', 'gano-child' ); ?></a>
		</div>
	</nav>

	<!-- SECCIÓN 1: INICIO (Hero) -->
	<section class="hero" id="inicio" data-animation="fade">
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
					<?php esc_html_e( 'Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.', 'gano-child' ); ?>
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

		<!-- Hero Holograma Canvas (F3.1) -->
		<div class="hero-logo">
			<canvas id="hero-holograma-canvas" width="400" height="400"></canvas>
		</div>
	</section>

	<!-- SECCIÓN 5: SHOWCASE (SOTA Articles) -->
	<section class="sota-banner" id="showcase">
		<div class="container sota-inner">
			<div class="sota-text reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label">SOTA</span>
				<h3><?php esc_html_e( 'State of the Art', 'gano-child' ); ?></h3>
				<p>
					<?php esc_html_e( 'En Gano Digital, SOTA es nuestro estándar: arquitectura y operación al nivel que el mercado considera "lo mejor disponible hoy", sin humo ni promesas genéricas. Lo aplicamos a hosting, seguridad y acompañamiento comercial para que tu equipo sepa exactamente qué compra y por qué.', 'gano-child' ); ?>
				</p>
			</div>
			<div class="sota-stats">
				<div class="sota-stat reveal" data-animation="scale" data-duration="600" data-delay="100">
					<div class="sota-stat-value">99.9%</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Disponibilidad Garantizada', 'gano-child' ); ?></div>
				</div>
				<div class="sota-stat reveal" data-animation="scale" data-duration="600" data-delay="200">
					<div class="sota-stat-value">&lt;8h</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Primera Respuesta', 'gano-child' ); ?></div>
				</div>
				<div class="sota-stat reveal" data-animation="scale" data-duration="600" data-delay="300">
					<div class="sota-stat-value">24/7</div>
					<div class="sota-stat-label"><?php esc_html_e( 'Monitoreo Proactivo', 'gano-child' ); ?></div>
				</div>
			</div>
		</div>
	</section>

	<!-- SECCIÓN 2: INFRAESTRUCTURA (Pilares) -->
	<section class="section" id="pilares" data-animation="reveal-up">
		<div class="container">
			<div class="section-header reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Infraestructura', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Cuatro pilares de la', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'infraestructura SOTA', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Lo que diferencia a Gano Digital en el mercado colombiano.', 'gano-child' ); ?></p>
			</div>

			<div class="pillars-grid">
				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="100" data-stagger="150">
					<div class="pillar-icon">
						<i class="fas fa-gauge-high"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'NVMe que se nota en Core Web Vitals, no solo en el folleto.', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="250" data-stagger="150">
					<div class="pillar-icon">
						<i class="fas fa-shield-halved"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'WordPress endurecida para el tráfico real de un negocio.', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="400" data-stagger="150">
					<div class="pillar-icon">
						<i class="fas fa-fingerprint"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Confianza cero por defecto: identidad, sesiones y permisos bajo control.', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'La seguridad no es un cartel: es política aplicada en capas. Menos suposiciones, más trazabilidad cuando importa.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="550" data-stagger="150">
					<div class="pillar-icon">
						<i class="fas fa-globe"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Contenido más cerca del usuario, sin magia barata.', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia. Menos saltos innecesarios, más respuesta perceptible.', 'gano-child' ); ?>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- SECCIÓN 3: PLANES (Ecosistemas) -->
	<section class="section" id="precios" style="background: var(--bg-surface);" data-animation="reveal-up">
		<div class="container">
			<div class="section-header reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Catálogo comercial', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Planes con precios en', 'gano-child' ); ?> <span class="gradient-text">COP</span></h2>
				<p class="section-desc"><?php esc_html_e( 'Compara planes con referencia USD* y elige el que necesita tu operación.', 'gano-child' ); ?></p>
			</div>

			<div class="pricing-tabs reveal" data-animation="fade" data-duration="600" data-delay="100">
				<button class="pricing-tab active" data-target="pricing-wordpress"><?php esc_html_e( 'WordPress', 'gano-child' ); ?></button>
				<button class="pricing-tab" data-target="pricing-compartido"><?php esc_html_e( 'Compartido', 'gano-child' ); ?></button>
				<button class="pricing-tab" data-target="pricing-vps">VPS</button>
			</div>

			<div class="pricing-grid" id="pricing-wordpress">
				<div class="pricing-card reveal" data-animation="reveal-left" data-duration="600" data-delay="200">
					<div class="pricing-name"><?php esc_html_e( 'Esencial', 'gano-child' ); ?></div>
					<div class="pricing-desc"><?php esc_html_e( 'Para emprendedores y proyectos nuevos', 'gano-child' ); ?></div>
					<div class="pricing-price">
						<span class="currency">$</span>
						<span class="amount">29.900</span>
						<span class="period">/mes</span>
						<div class="usd">~$7.50 USD</div>
					</div>
					<ul class="pricing-features">
						<li><i class="fas fa-check"></i> <?php esc_html_e( '1 sitio web', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( '10 GB NVMe', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( '100 GB ancho de banda', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'SSL gratuito', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'cPanel incluido', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Soporte 24/7', 'gano-child' ); ?></li>
						<li class="muted"><i class="fas fa-xmark"></i> <?php esc_html_e( 'CDN Edge', 'gano-child' ); ?></li>
						<li class="muted"><i class="fas fa-xmark"></i> <?php esc_html_e( 'Backups diarios', 'gano-child' ); ?></li>
					</ul>
					<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn btn-ghost" style="width:100%"><?php esc_html_e( 'Elegir plan', 'gano-child' ); ?></a>
				</div>

				<div class="pricing-card featured reveal" data-animation="scale" data-duration="600" data-delay="300">
					<div class="pricing-badge"><?php esc_html_e( 'Más popular', 'gano-child' ); ?></div>
					<div class="pricing-name"><?php esc_html_e( 'Profesional', 'gano-child' ); ?></div>
					<div class="pricing-desc"><?php esc_html_e( 'Para negocios en crecimiento', 'gano-child' ); ?></div>
					<div class="pricing-price">
						<span class="currency">$</span>
						<span class="amount">79.900</span>
						<span class="period">/mes</span>
						<div class="usd">~$19.90 USD</div>
					</div>
					<ul class="pricing-features">
						<li><i class="fas fa-check"></i> <?php esc_html_e( '5 sitios web', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( '50 GB NVMe', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Ancho de banda ilimitado', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'SSL gratuito', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'cPanel + Softaculous', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'CDN Edge Colombia', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Backups diarios', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Migración gratuita', 'gano-child' ); ?></li>
					</ul>
					<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn btn-primary" style="width:100%"><?php esc_html_e( 'Elegir plan', 'gano-child' ); ?></a>
				</div>

				<div class="pricing-card reveal" data-animation="reveal-right" data-duration="600" data-delay="400">
					<div class="pricing-name"><?php esc_html_e( 'Empresarial', 'gano-child' ); ?></div>
					<div class="pricing-desc"><?php esc_html_e( 'Para operaciones de alto tráfico', 'gano-child' ); ?></div>
					<div class="pricing-price">
						<span class="currency">$</span>
						<span class="amount">149.900</span>
						<span class="period">/mes</span>
						<div class="usd">~$37.50 USD</div>
					</div>
					<ul class="pricing-features">
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Sitios ilimitados', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( '100 GB NVMe', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Ancho de banda ilimitado', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'SSL Wildcard', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'cPanel + WHM', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'CDN Global + Edge', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'Backups en tiempo real', 'gano-child' ); ?></li>
						<li><i class="fas fa-check"></i> <?php esc_html_e( 'IP dedicada', 'gano-child' ); ?></li>
					</ul>
					<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn btn-ghost" style="width:100%"><?php esc_html_e( 'Elegir plan', 'gano-child' ); ?></a>
				</div>
			</div>

			<!-- COMPARTIDO — próximamente -->
			<div class="pricing-grid" id="pricing-compartido" style="display:none;">
				<div class="pricing-card reveal" data-animation="scale" data-duration="600" data-delay="200" style="grid-column: 1 / -1; max-width: 560px; margin: 0 auto; text-align: center;">
					<div class="pillar-icon" style="margin: 0 auto 1.5rem;">
						<i class="fas fa-layer-group"></i>
					</div>
					<div class="pricing-name"><?php esc_html_e( 'Hosting Compartido', 'gano-child' ); ?></div>
					<p class="pillar-desc" style="margin: 1rem 0 1.5rem;">
						<?php esc_html_e( 'Planes de hosting compartido con NVMe, cPanel y soporte en español. Catálogo en configuración — disponible próximamente.', 'gano-child' ); ?>
					</p>
					<a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="btn btn-ghost" style="width:100%">
						<?php esc_html_e( 'Notificarme cuando esté disponible', 'gano-child' ); ?>
					</a>
				</div>
			</div>

			<!-- VPS — próximamente -->
			<div class="pricing-grid" id="pricing-vps" style="display:none;">
				<div class="pricing-card reveal" data-animation="scale" data-duration="600" data-delay="200" style="grid-column: 1 / -1; max-width: 560px; margin: 0 auto; text-align: center;">
					<div class="pillar-icon" style="margin: 0 auto 1.5rem;">
						<i class="fas fa-cube"></i>
					</div>
					<div class="pricing-name">VPS Cloud</div>
					<p class="pillar-desc" style="margin: 1rem 0 1.5rem;">
						<?php esc_html_e( 'Servidores virtuales privados con recursos dedicados, panel de control y escalamiento bajo demanda. Catálogo en configuración — disponible próximamente.', 'gano-child' ); ?>
					</p>
					<a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="btn btn-ghost" style="width:100%">
						<?php esc_html_e( 'Notificarme cuando esté disponible', 'gano-child' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- SECCIÓN 4: SERVICIOS -->
	<section class="section" id="servicios" data-animation="reveal-up">
		<div class="container">
			<div class="section-header reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Servicios', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Blindaje y', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'Optimización', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Capas de seguridad avanzada e inteligencia aplicadas a tu presencia digital.', 'gano-child' ); ?></p>
			</div>

			<div class="services-list">
				<div class="service-row reveal" data-animation="reveal-up" data-duration="600" data-delay="100">
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

				<div class="service-row reveal" data-animation="reveal-up" data-duration="600" data-delay="200">
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

				<div class="service-row reveal" data-animation="reveal-up" data-duration="600" data-delay="300">
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

				<div class="service-row reveal" data-animation="reveal-up" data-duration="600" data-delay="400">
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

				<div class="service-row reveal" data-animation="reveal-up" data-duration="600" data-delay="500">
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

	<!-- SECCIÓN 6: NOSOTROS (Team/About) -->
	<section class="section" id="nosotros" data-animation="reveal-up">
		<div class="container">
			<div class="section-header reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Sobre nosotros', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Infraestructura SOTA,', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'operación seria', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Gano Digital es un socio tecnológico que trabaja en silencio, comprometido con la continuidad de tu infraestructura y la soberanía digital de Colombia.', 'gano-child' ); ?></p>
			</div>

			<div class="nosotros-content reveal" data-animation="reveal-up" data-duration="600" data-delay="100">
				<div class="nosotros-mission">
					<h3><?php esc_html_e( 'Nuestra misión', 'gano-child' ); ?></h3>
					<p><?php esc_html_e( 'Proporcionar infraestructura de clase mundial con estándares SOTA, operación transparente y soporte en español. Sin ruido, sin promesas genéricas. Solo resultados.', 'gano-child' ); ?></p>
				</div>
				<div class="nosotros-values">
					<h3><?php esc_html_e( 'Nuestros valores', 'gano-child' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'Transparencia radical en operación y precios', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'Soberanía digital: tus datos, tu control', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'Operación Colombia: equipo local, soporte en español', 'gano-child' ); ?></li>
						<li><?php esc_html_e( 'Continuidad sin negociación', 'gano-child' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- RESPALDO TÉCNICO — Hechos verificables, sin testimonios fabricados -->
	<section class="section">
		<div class="container">
			<div class="section-header reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Por qué Gano Digital', 'gano-child' ); ?></span>
				<h2 class="section-title"><?php esc_html_e( 'Infraestructura con', 'gano-child' ); ?> <span class="gradient-text"><?php esc_html_e( 'respaldo real', 'gano-child' ); ?></span></h2>
				<p class="section-desc"><?php esc_html_e( 'Sin promesas genéricas. Estos son los hechos detrás de nuestra operación.', 'gano-child' ); ?></p>
			</div>

			<div class="pillars-grid">
				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="100">
					<div class="pillar-icon">
						<i class="fas fa-server"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Infraestructura de datacenter enterprise', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Operamos sobre plataforma GoDaddy con SLA de 99.9 % de disponibilidad, redundancia de red y centros de datos certificados. La confiabilidad no es un cartel: está en el contrato.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="250">
					<div class="pillar-icon">
						<i class="fas fa-coins"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Precios en COP, sin tipo de cambio sorpresa', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Facturación en pesos colombianos. Saber exactamente cuánto pagas cada mes, sin convertir USD ni depender de la tasa del día, es parte del servicio.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="400">
					<div class="pillar-icon">
						<i class="fas fa-headset"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Soporte técnico en español, con contexto local', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Equipo que conoce el mercado colombiano, responde en tu idioma y entiende el contexto de tu negocio. Primera respuesta en menos de 8 horas, sin bots de primer nivel.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="pillar-card reveal" data-animation="reveal-up" data-duration="600" data-delay="550">
					<div class="pillar-icon">
						<i class="fas fa-lock"></i>
					</div>
					<h3 class="pillar-title"><?php esc_html_e( 'Seguridad aplicada, no decorativa', 'gano-child' ); ?></h3>
					<p class="pillar-desc">
						<?php esc_html_e( 'Hardening continuo, CSP enforced, rate limiting y WAF activos desde el primer día. La seguridad es configuración real, no un complemento de marketing.', 'gano-child' ); ?>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- SECCIÓN 7: CONTACTO (Final CTA) -->
	<section class="section" id="contacto" data-animation="reveal-up">
		<div class="container">
			<div class="contacto-box reveal" data-animation="reveal-up" data-duration="600" data-delay="0">
				<span class="section-label"><?php esc_html_e( 'Próximo paso', 'gano-child' ); ?></span>
				<h2 class="section-title" style="margin-bottom: 12px;"><?php esc_html_e( 'Crea tu cuenta hoy', 'gano-child' ); ?></h2>
				<p class="section-desc" style="max-width: 560px; margin: 0 auto;">
					<?php esc_html_e( 'Acceso inmediato a tu panel de control. Configura tu primer sitio, dominio y servicios en minutos. Soporte 24/7 en español desde el primer día.', 'gano-child' ); ?>
				</p>
				<div class="contacto-actions">
					<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn btn-primary btn-lg">
						<i class="fas fa-rocket"></i>
						<?php esc_html_e( 'Crear cuenta ahora', 'gano-child' ); ?>
					</a>
					<p class="contacto-note"><?php esc_html_e( 'O ', 'gano-child' ); ?><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'habla con nuestro equipo', 'gano-child' ); ?></a></p>
				</div>
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
						<?php esc_html_e( 'Un socio tecnológico que trabaja en silencio. Gano Digital no compite en hosting barato. Compite en continuidad: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio.', 'gano-child' ); ?>
					</p>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'Productos', 'gano-child' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Hosting WordPress', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Hosting Compartido', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'VPS Cloud', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Servidores Dedicados', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Creador de Sitios', 'gano-child' ); ?></a></li>
					</ul>
				</div>

				<div class="footer-col">
					<h4><?php esc_html_e( 'Empresa', 'gano-child' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/nosotros/' ) ); ?>"><?php esc_html_e( 'Sobre nosotros', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'Estado de red', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'gano-child' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"><?php esc_html_e( 'Preguntas frecuentes', 'gano-child' ); ?></a></li>
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
					<a href="#" aria-label="X / Twitter"><i class="fab fa-x-twitter"></i></a>
					<a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
					<a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
					<a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
				</div>
			</div>
		</div>
	</footer>

</div><!-- /.gano-landing-sota -->

<?php /* Pricing tabs switching */ ?>
<script>
(function () {
	'use strict';
	document.addEventListener( 'DOMContentLoaded', function () {
		var tabs  = document.querySelectorAll( '.pricing-tab' );
		var grids = document.querySelectorAll( '.pricing-grid' );
		if ( ! tabs.length ) { return; }
		tabs.forEach( function ( tab ) {
			tab.addEventListener( 'click', function () {
				var target = this.dataset.target;
				tabs.forEach( function ( t ) { t.classList.remove( 'active' ); } );
				grids.forEach( function ( g ) { g.style.display = 'none'; } );
				this.classList.add( 'active' );
				var grid = document.getElementById( target );
				if ( grid ) { grid.style.display = ''; }
			} );
		} );
	} );
}());
</script>
<?php get_footer(); ?>
