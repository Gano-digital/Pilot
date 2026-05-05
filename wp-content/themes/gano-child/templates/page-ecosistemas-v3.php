<?php
/**
 * Template Name: Ecosistemas V3 — Catálogo Completo
 *
 * Página de precios con catálogo completo del Reseller Store.
 * Incluye 4 planes destacados, navegación por categorías, bundles, FAQ y CTA.
 *
 * @package gano-child
 */

get_header();

// ------------------------------------------------------------------
// Helpers
// ------------------------------------------------------------------
if ( ! function_exists( 'gano_v3_get_product_price' ) ) {
	/**
	 * Intenta obtener el precio de un producto reseller desde meta.
	 *
	 * @param int $product_id Post ID.
	 * @return string
	 */
	function gano_v3_get_product_price( $product_id ) {
		$keys = array( 'rstore_price', 'price', '_price', 'rstore_list_price', 'list_price' );
		foreach ( $keys as $k ) {
			$v = get_post_meta( $product_id, $k, true );
			if ( ! empty( $v ) ) {
				return $v;
			}
		}
		return '';
	}
}

if ( ! function_exists( 'gano_v3_format_price' ) ) {
	/**
	 * Formatea un valor de precio para pantalla.
	 *
	 * @param string|int|float $price Precio crudo.
	 * @return string
	 */
	function gano_v3_format_price( $price ) {
		if ( empty( $price ) ) {
			return '';
		}
		$price = trim( (string) $price );
		if ( is_numeric( $price ) ) {
			return '$' . number_format( (float) $price, 0, ',', '.' );
		}
		if ( false === strpos( $price, '$' ) && false === strpos( $price, 'COP' ) ) {
			return '$' . $price;
		}
		return $price;
	}
}

// ------------------------------------------------------------------
// Datos de planes destacados (config estática + sobreescritura dinámica)
// ------------------------------------------------------------------
$featured_slugs = array( 'wordpress-basico', 'wordpress-deluxe', 'wordpress-ultimate', 'cpanel-ultimate' );

$featured_config = array(
	'wordpress-basico'   => array(
		'tag'          => '',
		'featured'     => false,
		'color'        => '#1B4FD8',
		'colorBg'      => '#E8EEFB',
		'icon'         => 'M4 6h16M4 12h16M4 18h7',
		'desc'         => 'Ideal para proyectos personales, blogs y sitios corporativos pequeños que recién empiezan.',
		'features'     => array( '20 GB almacenamiento NVMe', 'WordPress preinstalado', 'SSL Let\'s Encrypt incluido', 'Backups diarios · 30 días', 'CDN global', 'Soporte ticket < 8h' ),
		'cta'          => 'Elegir plan',
		'precio'       => '$196.000',
		'precio_anual' => '$1.960.000',
		'ahorro'       => 'Ahorra $392.000',
	),
	'wordpress-deluxe'   => array(
		'tag'          => 'Más popular',
		'featured'     => false,
		'color'        => '#00C26B',
		'colorBg'      => '#E0FAF0',
		'icon'         => 'M13 10V3L4 14h7v7l9-11h-7z',
		'desc'         => 'Perfecto para negocios en crecimiento, tiendas WooCommerce y sitios con tráfico moderado.',
		'features'     => array( '40 GB NVMe · doble recursos', 'Hardening WordPress activo', 'Backups diarios + on-demand', 'Soporte priorizado < 4h', 'Monitoreo uptime', 'WooCommerce optimizado' ),
		'cta'          => 'Activar ahora',
		'precio'       => '$450.000',
		'precio_anual' => '$4.500.000',
		'ahorro'       => 'Ahorra $900.000',
	),
	'wordpress-ultimate' => array(
		'tag'          => 'Recomendado',
		'featured'     => true,
		'color'        => '#D4AF37',
		'colorBg'      => '#FFF8E1',
		'icon'         => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
		'desc'         => 'Rendimiento empresarial Gen4 con seguridad de nivel militar. Para comercio que no puede fallar.',
		'features'     => array( '75 GB NVMe · recursos dedicados', 'SLA ≥ 99.9% disponibilidad', 'Monitoreo proactivo 24/7', 'Hardening avanzado + auditoría', 'Agente IA administración', 'Soporte dedicado < 2h' ),
		'cta'          => 'Solicitar Ultimate',
		'precio'       => '$890.000',
		'precio_anual' => '$8.900.000',
		'ahorro'       => 'Ahorra $1.780.000',
	),
	'cpanel-ultimate'    => array(
		'tag'          => 'Para agencias',
		'featured'     => false,
		'color'        => '#7C3AED',
		'colorBg'      => '#F3E8FF',
		'icon'         => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
		'desc'         => 'Máxima capacidad para agencias, portafolios masivos y plataformas de alto tráfico.',
		'features'     => array( '100 GB NVMe · máximos recursos', 'Multi-site ilimitado', 'Soporte dedicado < 1h 24/7', 'Backups continuos + DR', 'Escalado automático', 'Onboarding técnico incluido' ),
		'cta'          => 'Cotizar ahora',
		'precio'       => '$1.200.000',
		'precio_anual' => '$12.000.000',
		'ahorro'       => 'Ahorra $2.400.000',
	),
);

$featured_products = array();
foreach ( $featured_slugs as $slug ) {
	$posts = get_posts(
		array(
			'post_type'      => 'reseller_product',
			'name'           => $slug,
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		)
	);

	$plan = isset( $featured_config[ $slug ] ) ? $featured_config[ $slug ] : array();
	if ( ! empty( $posts ) ) {
		$p             = $posts[0];
		$plan['id']    = esc_attr( $slug );
		$plan['nombre'] = get_the_title( $p );
		$plan['link']  = get_permalink( $p );
		$plan['post_id'] = $p->ID;

		$live_price = gano_v3_get_product_price( $p->ID );
		if ( ! empty( $live_price ) ) {
			$plan['precio'] = gano_v3_format_price( $live_price );
		}
	} else {
		$plan['id']     = esc_attr( $slug );
		$plan['nombre'] = str_replace( '-', ' ', $slug );
		$plan['link']   = '#';
		$plan['post_id'] = 0;
	}
	$featured_products[] = $plan;
}

// ------------------------------------------------------------------
// Categorías del reseller
// ------------------------------------------------------------------
$categories = get_terms(
	array(
		'taxonomy'   => 'reseller_product_category',
		'hide_empty' => true,
		'orderby'    => 'name',
	)
);

// ------------------------------------------------------------------
// Bundles
// ------------------------------------------------------------------
$bundles = array(
	array(
		'nombre' => 'Dominio + Hosting + SSL',
		'desc'   => 'Todo lo que necesitas para lanzar tu sitio web con seguridad y profesionalismo.',
		'precio' => '$245.000',
		'ahorro' => 'Ahorra 15%',
		'icon'   => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
		'cta'    => 'Armar bundle',
		'link'   => '/contacto/',
	),
	array(
		'nombre' => 'Email + Hosting',
		'desc'   => 'Comunicación profesional con tu marca y alojamiento rápido en un solo lugar.',
		'precio' => '$520.000',
		'ahorro' => 'Ahorra 20%',
		'icon'   => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
		'cta'    => 'Armar bundle',
		'link'   => '/contacto/',
	),
	array(
		'nombre' => 'Seguridad + Backup',
		'desc'   => 'Protección completa para tu sitio con monitoreo y respaldos automatizados.',
		'precio' => '$350.000',
		'ahorro' => 'Ahorra 20%',
		'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
		'cta'    => 'Armar bundle',
		'link'   => '/contacto/',
	),
);

// ------------------------------------------------------------------
// FAQ
// ------------------------------------------------------------------
$faqs = array(
	array( '¿Puedo cambiar de plan después?', 'Sí. Puedes migrar a un plan superior en cualquier momento. El cambio se procesa en menos de 24 horas hábiles y mantienes todos tus datos intactos.' ),
	array( '¿Qué significa "facturación en COP"?', 'Tu tarjeta o transferencia se debita en pesos colombianos. No hay conversiones de divisa ni sorpresas de tipo de cambio al final del mes.' ),
	array( '¿El dominio está incluido?', 'Los planes de hosting no incluyen dominio por defecto. Revisa nuestros Bundles que combinan dominio + hosting + SSL con descuento.' ),
	array( '¿Qué es el Agente IA?', 'Es un asistente inteligente integrado en WordPress Ultimate y cPanel Ultimate que monitorea tu sitio, detecta anomalías y sugiere optimizaciones automáticamente.' ),
	array( '¿Tienen garantía de reembolso?', 'Sí. Todos los planes incluyen 30 días de garantía. Si no estás satisfecho, te devolvemos el 100% sin preguntas.' ),
);
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap');

/* ─── Variables ─── */
:root {
  --eco-bg: #FFFFFF;
  --eco-bg-alt: #F8FAFC;
  --eco-bg-dark: #0F172A;
  --eco-text: #111827;
  --eco-text-secondary: #4B5563;
  --eco-text-muted: #6B7280;
  --eco-text-on-dark: #F1F5F9;

  --eco-primary: #1B4FD8;
  --eco-primary-dark: #1240B0;
  --eco-primary-light: #E8EEFB;

  --eco-accent: #10B981;
  --eco-accent-light: #D1FAE5;

  --eco-cta: #FF6B35;
  --eco-cta-dark: #E55520;
  --eco-cta-light: #FFF0EB;

  --eco-gold: #D4AF37;
  --eco-gold-soft: #FFF8E1;

  --eco-border: #E5E7EB;
  --eco-border-light: #F3F4F6;
  --eco-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
  --eco-shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.02);
  --eco-shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.12);

  --eco-radius: 16px;
  --eco-radius-lg: 24px;
  --eco-radius-sm: 12px;
}

/* ─── Reset local ─── */
.gano-eco-v3,
.gano-eco-v3 *,
.gano-eco-v3 *::before,
.gano-eco-v3 *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

.gano-eco-v3 ul,
.gano-eco-v3 ol {
  list-style: none;
}

/* ─── Base ─── */
.gano-eco-v3 {
  font-family: 'Inter', sans-serif;
  font-weight: 400;
  color: var(--eco-text);
  background: var(--eco-bg);
  line-height: 1.6;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.gano-eco-v3 h1,
.gano-eco-v3 h2,
.gano-eco-v3 h3,
.gano-eco-v3 h4,
.gano-eco-v3 h5,
.gano-eco-v3 h6 {
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ─── Keyframes ─── */
@keyframes ecoFadeUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes ecoFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes ecoScale {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

@keyframes ecoFloat {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

@keyframes ecoPulse {
  0%, 100% {
    box-shadow: 0 4px 16px rgba(255,107,53,0.25);
  }
  50% {
    box-shadow: 0 4px 24px rgba(255,107,53,0.4);
  }
}

/* ─── Scroll reveal ─── */
.gano-eco-v3 .eco-reveal {
  opacity: 0;
  transform: translateY(40px);
}

.gano-eco-v3 .eco-reveal.visible {
  opacity: 1;
  transform: translateY(0);
  transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1), transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

/* ─── Container ─── */
.gano-eco-v3 .eco-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 16px;
}

@media (min-width: 640px) {
  .gano-eco-v3 .eco-container {
    padding: 0 24px;
  }
}

@media (min-width: 1024px) {
  .gano-eco-v3 .eco-container {
    padding: 0 32px;
  }
}

/* ─── Hero ─── */
.gano-eco-v3 .eco-hero {
  position: relative;
  padding: 100px 0 60px;
  background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
  overflow: hidden;
}

.gano-eco-v3 .eco-hero__orbs {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
}

.gano-eco-v3 .eco-hero__orb {
  position: absolute;
  width: 400px;
  height: 400px;
  border-radius: 50%;
  filter: blur(100px);
  opacity: 0.15;
}

.gano-eco-v3 .eco-hero__orb--primary {
  background: var(--eco-primary);
  top: -100px;
  left: -100px;
}

.gano-eco-v3 .eco-hero__orb--accent {
  background: var(--eco-accent);
  bottom: -80px;
  right: -80px;
}

.gano-eco-v3 .eco-hero__orb--gold {
  background: var(--eco-gold);
  top: 40%;
  left: 50%;
  transform: translateX(-50%);
}

.gano-eco-v3 .eco-hero__content {
  position: relative;
  z-index: 1;
  text-align: center;
}

.gano-eco-v3 .eco-hero__label {
  display: inline-block;
  background: var(--eco-primary-light);
  color: var(--eco-primary);
  font-weight: 600;
  font-size: 0.875rem;
  border-radius: 999px;
  padding: 8px 18px;
  margin-bottom: 24px;
}

.gano-eco-v3 .eco-hero__title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  font-weight: 800;
  letter-spacing: -0.03em;
  color: var(--eco-text);
  margin-bottom: 20px;
}

.gano-eco-v3 .eco-hero__title span {
  background: linear-gradient(135deg, #1B4FD8, #10B981);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.gano-eco-v3 .eco-hero__lead {
  font-size: 1.125rem;
  color: var(--eco-text-muted);
  max-width: 600px;
  margin: 0 auto 32px;
  line-height: 1.7;
}

.gano-eco-v3 .eco-hero__toggle {
  display: inline-flex;
  align-items: center;
  background: #F3F4F6;
  border-radius: 999px;
  padding: 4px;
  gap: 4px;
}

.gano-eco-v3 .eco-hero__toggle-btn {
  border: none;
  background: transparent;
  color: var(--eco-text-muted);
  font-family: 'Inter', sans-serif;
  font-size: 0.9375rem;
  font-weight: 600;
  padding: 8px 20px;
  border-radius: 999px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.gano-eco-v3 .eco-hero__toggle-btn.active {
  background: white;
  color: var(--eco-text);
  box-shadow: var(--eco-shadow);
}

.gano-eco-v3 .eco-hero__badge {
  background: var(--eco-accent-light);
  color: var(--eco-accent);
  font-weight: 700;
  font-size: 0.75rem;
  padding: 4px 10px;
  border-radius: 999px;
  margin-left: 4px;
}

/* ─── Sticky Category Tabs ─── */
.gano-eco-v3 .eco-tabs {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(255,255,255,0.9);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--eco-border);
  padding: 12px 0;
}

.gano-eco-v3 .eco-tabs__scroll {
  display: flex;
  gap: 8px;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
}

.gano-eco-v3 .eco-tabs__scroll::-webkit-scrollbar {
  display: none;
}

.gano-eco-v3 .eco-tabs__btn {
  flex-shrink: 0;
  border: none;
  background: transparent;
  font-family: 'Inter', sans-serif;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--eco-text-muted);
  padding: 8px 16px;
  border-radius: 999px;
  cursor: pointer;
  transition: all 0.25s ease;
  white-space: nowrap;
}

.gano-eco-v3 .eco-tabs__btn:hover:not(.active) {
  background: var(--eco-border-light);
}

.gano-eco-v3 .eco-tabs__btn.active {
  background: var(--eco-primary);
  color: white;
}

/* ─── Section Headers ─── */
.gano-eco-v3 .eco-section-header {
  margin-bottom: 48px;
  text-align: center;
}

.gano-eco-v3 .eco-section-header__title {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 12px;
}

.gano-eco-v3 .eco-section-header__subtitle {
  font-size: 1.0625rem;
  color: var(--eco-text-muted);
  max-width: 560px;
  margin: 0 auto;
  line-height: 1.65;
}

/* ─── Plan Cards ─── */
.gano-eco-v3 .eco-plans-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}

@media (min-width: 640px) {
  .gano-eco-v3 .eco-plans-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gano-eco-v3 .eco-plans-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.gano-eco-v3 .eco-plan-card {
  position: relative;
  background: white;
  border: 1px solid var(--eco-border);
  border-radius: var(--eco-radius-lg);
  padding: 32px 28px;
  display: flex;
  flex-direction: column;
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.gano-eco-v3 .eco-plan-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--eco-shadow-xl);
  border-color: transparent;
}

.gano-eco-v3 .eco-plan-card--featured {
  border: 2px solid var(--eco-gold);
  box-shadow: 0 0 0 1px #D4AF37, 0 12px 32px -8px rgba(212,175,55,0.2);
}

.gano-eco-v3 .eco-plan-card--featured:hover {
  box-shadow: 0 0 0 1px #D4AF37, var(--eco-shadow-xl);
}

.gano-eco-v3 .eco-plan-card__badge {
  position: absolute;
  top: -12px;
  right: 24px;
  background: linear-gradient(135deg, #D4AF37, #B8941D);
  color: white;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 6px 14px;
  border-radius: 0 0 10px 10px;
}

.gano-eco-v3 .eco-plan-card__illustration {
  width: 80px;
  height: 80px;
  border-radius: 20px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.gano-eco-v3 .eco-plan-card__name {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.375rem;
  font-weight: 700;
  color: var(--eco-text);
  margin-bottom: 8px;
}

.gano-eco-v3 .eco-plan-card__desc {
  font-size: 0.9375rem;
  color: var(--eco-text-muted);
  line-height: 1.6;
  margin-bottom: 20px;
}

.gano-eco-v3 .eco-plan-card__price-wrap {
  margin-bottom: 4px;
}

.gano-eco-v3 .eco-plan-card__price {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 3rem;
  font-weight: 800;
  color: var(--eco-text);
  letter-spacing: -0.02em;
}

.gano-eco-v3 .eco-plan-card__price span {
  font-size: 1rem;
  font-weight: 500;
  color: var(--eco-text-muted);
}

.gano-eco-v3 .eco-plan-card__period {
  font-size: 0.875rem;
  color: var(--eco-text-muted);
}

.gano-eco-v3 .eco-plan-card__save {
  display: inline-block;
  font-size: 0.8125rem;
  font-weight: 700;
  color: var(--eco-accent);
  background: var(--eco-accent-light);
  padding: 4px 12px;
  border-radius: 999px;
  margin-top: 8px;
}

.gano-eco-v3 .eco-plan-card__features {
  margin-top: 24px;
  flex-grow: 1;
}

.gano-eco-v3 .eco-plan-card__feature {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 0;
  font-size: 0.9375rem;
  color: var(--eco-text-secondary);
  border-bottom: 1px solid var(--eco-border-light);
}

.gano-eco-v3 .eco-plan-card__feature:last-child {
  border-bottom: none;
}

.gano-eco-v3 .eco-plan-card__check {
  width: 20px;
  height: 20px;
  color: var(--eco-accent);
  flex-shrink: 0;
  margin-top: 2px;
}

.gano-eco-v3 .eco-plan-card__cta {
  width: 100%;
  padding: 16px;
  border-radius: var(--eco-radius-sm);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1rem;
  font-weight: 700;
  text-align: center;
  border: none;
  cursor: pointer;
  margin-top: 24px;
  transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
}

.gano-eco-v3 .eco-plan-card__cta--primary {
  background: linear-gradient(135deg, #FF6B35, #E55520);
  color: white;
  box-shadow: 0 4px 16px rgba(255,107,53,0.25);
}

.gano-eco-v3 .eco-plan-card__cta--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(255,107,53,0.35);
}

.gano-eco-v3 .eco-plan-card__cta--featured {
  background: var(--eco-primary);
  color: white;
}

.gano-eco-v3 .eco-plan-card__cta--featured:hover {
  background: var(--eco-primary-dark);
  transform: translateY(-2px);
}

.gano-eco-v3 .eco-plan-card__cta--secondary {
  background: transparent;
  color: var(--eco-primary);
  font-weight: 600;
  padding: 12px;
}

.gano-eco-v3 .eco-plan-card__cta--secondary:hover {
  background: var(--eco-primary-light);
}

/* ─── Product Cards ─── */
.gano-eco-v3 .eco-products-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
}

@media (min-width: 640px) {
  .gano-eco-v3 .eco-products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gano-eco-v3 .eco-products-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.gano-eco-v3 .eco-product-card {
  background: white;
  border: 1px solid var(--eco-border);
  border-radius: var(--eco-radius);
  padding: 24px;
  display: flex;
  flex-direction: column;
  transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.gano-eco-v3 .eco-product-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--eco-shadow-lg);
}

.gano-eco-v3 .eco-product-card__image {
  width: 48px;
  height: 48px;
  margin-bottom: 16px;
  object-fit: contain;
}

.gano-eco-v3 .eco-product-card__name {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.0625rem;
  font-weight: 600;
  color: var(--eco-text);
  margin-bottom: 6px;
}

.gano-eco-v3 .eco-product-card__desc {
  font-size: 0.875rem;
  color: var(--eco-text-muted);
  line-height: 1.55;
  margin-bottom: 16px;
  max-height: 4.5em;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}

.gano-eco-v3 .eco-product-card__price {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--eco-text);
  margin-bottom: 16px;
}

.gano-eco-v3 .eco-product-card__cta {
  display: block;
  background: var(--eco-primary-light);
  color: var(--eco-primary);
  font-weight: 600;
  padding: 10px 16px;
  border-radius: 10px;
  font-size: 0.875rem;
  text-align: center;
  text-decoration: none;
  margin-top: auto;
  transition: all 0.25s ease;
}

.gano-eco-v3 .eco-product-card__cta:hover {
  background: var(--eco-primary);
  color: white;
}

/* ─── Bundles Section ─── */
.gano-eco-v3 .eco-bundles {
  background: var(--eco-bg-alt);
  padding: 80px 0;
}

.gano-eco-v3 .eco-bundles-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}

@media (min-width: 640px) {
  .gano-eco-v3 .eco-bundles-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gano-eco-v3 .eco-bundles-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.gano-eco-v3 .eco-bundle-card {
  background: white;
  border: 2px solid transparent;
  border-radius: var(--eco-radius-lg);
  padding: 32px;
  text-align: center;
  transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.gano-eco-v3 .eco-bundle-card:hover {
  border-color: var(--eco-primary);
  box-shadow: var(--eco-shadow-lg);
}

.gano-eco-v3 .eco-bundle-card__icon {
  width: 56px;
  height: 56px;
  margin: 0 auto 16px;
  background: var(--eco-primary-light);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--eco-primary);
}

.gano-eco-v3 .eco-bundle-card__name {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--eco-text);
  margin-bottom: 8px;
}

.gano-eco-v3 .eco-bundle-card__desc {
  font-size: 0.9375rem;
  color: var(--eco-text-muted);
  margin-bottom: 16px;
  line-height: 1.6;
}

.gano-eco-v3 .eco-bundle-card__price {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 8px;
}

.gano-eco-v3 .eco-bundle-card__save {
  display: inline-block;
  font-size: 0.8125rem;
  font-weight: 700;
  color: var(--eco-accent);
  background: var(--eco-accent-light);
  padding: 4px 12px;
  border-radius: 999px;
}

/* ─── Comparison Table ─── */
.gano-eco-v3 .eco-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.gano-eco-v3 .eco-table {
  width: 100%;
  background: white;
  border-radius: var(--eco-radius-lg);
  border: 1px solid var(--eco-border);
  overflow: hidden;
  border-collapse: collapse;
  min-width: 720px;
}

.gano-eco-v3 .eco-table th {
  background: var(--eco-bg-alt);
  font-weight: 700;
  font-size: 0.9375rem;
  padding: 16px 20px;
  text-align: left;
  color: var(--eco-text);
  border-bottom: 1px solid var(--eco-border);
}

.gano-eco-v3 .eco-table td {
  padding: 14px 20px;
  font-size: 0.9375rem;
  color: var(--eco-text-secondary);
  border-bottom: 1px solid var(--eco-border-light);
}

.gano-eco-v3 .eco-table tr:last-child td {
  border-bottom: none;
}

.gano-eco-v3 .eco-table tr:hover td {
  background: #FAFBFC;
}

.gano-eco-v3 .eco-table__check {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: var(--eco-accent-light);
  color: var(--eco-accent);
}

.gano-eco-v3 .eco-table__dash {
  color: var(--eco-text-muted);
}

.gano-eco-v3 .eco-table__highlight {
  font-weight: 600;
  color: var(--eco-text);
}

/* ─── Trust Section ─── */
.gano-eco-v3 .eco-trust {
  background: var(--eco-bg-dark);
  padding: 80px 0;
  color: white;
  text-align: center;
}

.gano-eco-v3 .eco-trust__title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 700;
  color: white;
  margin-bottom: 48px;
}

.gano-eco-v3 .eco-trust__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
}

@media (min-width: 640px) {
  .gano-eco-v3 .eco-trust__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gano-eco-v3 .eco-trust__grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.gano-eco-v3 .eco-trust__item {
  padding: 16px;
}

.gano-eco-v3 .eco-trust__icon {
  width: 44px;
  height: 44px;
  color: var(--eco-gold);
  margin: 0 auto 16px;
}

.gano-eco-v3 .eco-trust__item-title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.0625rem;
  font-weight: 700;
  color: white;
  margin-bottom: 8px;
}

.gano-eco-v3 .eco-trust__item-desc {
  font-size: 0.9375rem;
  color: #94A3B8;
  line-height: 1.6;
}

/* ─── FAQ ─── */
.gano-eco-v3 .eco-faq {
  background: var(--eco-bg-alt);
  padding: 80px 0;
}

.gano-eco-v3 .eco-faq__item {
  background: white;
  border: 1px solid var(--eco-border);
  border-radius: var(--eco-radius-sm);
  overflow: hidden;
  margin-bottom: 12px;
}

.gano-eco-v3 .eco-faq__question {
  padding: 20px 24px;
  font-size: 1rem;
  font-weight: 600;
  color: var(--eco-text);
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  transition: background 0.25s ease;
  user-select: none;
}

.gano-eco-v3 .eco-faq__question:hover {
  background: var(--eco-bg-alt);
}

.gano-eco-v3 .eco-faq__arrow {
  width: 20px;
  height: 20px;
  color: var(--eco-text-muted);
  transition: transform 0.3s ease;
  flex-shrink: 0;
  margin-left: 12px;
}

.gano-eco-v3 .eco-faq__item.open .eco-faq__arrow {
  transform: rotate(180deg);
}

.gano-eco-v3 .eco-faq__answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.35s ease, padding 0.35s ease;
  padding: 0 24px;
}

.gano-eco-v3 .eco-faq__item.open .eco-faq__answer {
  max-height: 300px;
  padding-bottom: 20px;
}

.gano-eco-v3 .eco-faq__answer-text {
  color: var(--eco-text-muted);
  font-size: 0.9375rem;
  line-height: 1.65;
}

/* ─── Final CTA ─── */
.gano-eco-v3 .eco-final-cta {
  padding: 80px 0;
  text-align: center;
}

.gano-eco-v3 .eco-final-cta__title {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(1.5rem, 3vw, 2.25rem);
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 24px;
}

.gano-eco-v3 .eco-final-cta__buttons {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.gano-eco-v3 .eco-final-cta__btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 16px 32px;
  border-radius: var(--eco-radius-sm);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1rem;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  border: none;
  transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
}

.gano-eco-v3 .eco-final-cta__btn--primary {
  background: linear-gradient(135deg, #FF6B35, #E55520);
  color: white;
  box-shadow: 0 4px 16px rgba(255,107,53,0.25);
}

.gano-eco-v3 .eco-final-cta__btn--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(255,107,53,0.35);
}

.gano-eco-v3 .eco-final-cta__btn--secondary {
  background: white;
  color: var(--eco-primary);
  border: 1px solid var(--eco-border);
}

.gano-eco-v3 .eco-final-cta__btn--secondary:hover {
  background: var(--eco-primary-light);
  border-color: var(--eco-primary);
}

/* ─── Utilities ─── */
.gano-eco-v3 .eco-section {
  padding: 80px 0;
}

.gano-eco-v3 .eco-section--bg-alt {
  background: var(--eco-bg-alt);
}

/* ─── Responsive ─── */
@media (max-width: 639px) {
  .gano-eco-v3 .eco-hero {
    padding: 60px 0 40px;
  }

  .gano-eco-v3 .eco-plan-card {
    padding: 28px 20px;
  }

  .gano-eco-v3 .eco-plan-card__price {
    font-size: 2.5rem;
  }

  .gano-eco-v3 .eco-bundles {
    padding: 60px 0;
  }

  .gano-eco-v3 .eco-trust {
    padding: 60px 0;
  }

  .gano-eco-v3 .eco-faq {
    padding: 60px 0;
  }

  .gano-eco-v3 .eco-final-cta {
    padding: 60px 0;
  }

  .gano-eco-v3 .eco-section {
    padding: 60px 0;
  }
}


/* ================================================================
   ECOSISTEMAS V3 — Estilos adicionales para clases no cubiertas
   ================================================================ */

html { scroll-behavior: smooth; }

/* ---- .eco-plans ---- */
.eco-plans {
  padding: 60px 0;
  background: var(--eco-bg);
}

/* ---- .eco-btn system ---- */
.eco-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 14px 28px;
  border-radius: var(--eco-radius-sm);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1rem;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  border: none;
  transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
}

.eco-btn--primary {
  background: linear-gradient(135deg, #FF6B35, #E55520);
  color: white;
  box-shadow: 0 4px 16px rgba(255,107,53,0.25);
}

.eco-btn--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(255,107,53,0.35);
}

.eco-btn--ghost {
  background: transparent;
  color: var(--eco-primary);
  font-weight: 600;
  padding: 12px;
}

.eco-btn--ghost:hover {
  background: var(--eco-primary-light);
}

.eco-btn--secondary {
  background: white;
  color: var(--eco-primary);
  border: 1px solid var(--eco-border);
}

.eco-btn--secondary:hover {
  background: var(--eco-primary-light);
  border-color: var(--eco-primary);
}

/* Ghost button inside plan card */
.eco-plan-card .eco-btn--ghost {
  width: 100%;
  margin-top: 8px;
}

/* ---- Tabs button extras ---- */
.eco-tabs__btn {
  text-decoration: none;
}

/* ---- Category section ---- */
.eco-category {
  padding: 60px 0;
  background: #FFFFFF;
  border-top: 1px solid #F3F4F6;
}

.eco-category:nth-child(even) {
  background: #F8FAFC;
}

.eco-category__header {
  margin-bottom: 32px;
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}

.eco-category__header h2 {
  font-size: clamp(1.3rem, 2.5vw, 1.75rem);
  font-weight: 700;
}

.eco-category__count {
  font-size: 0.875rem;
  color: #9CA3AF;
  font-weight: 500;
}

/* ---- Products grid (BEM variant) ---- */
.eco-products__grid {
  display: grid;
  gap: 20px;
  grid-template-columns: 1fr;
}

@media (min-width: 640px) {
  .eco-products__grid { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
  .eco-products__grid { grid-template-columns: repeat(3, 1fr); }
}

/* ---- Bundles ---- */
.eco-bundles__header {
  margin-bottom: 48px;
  text-align: center;
}

.eco-bundles__header h2 {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 12px;
}

.eco-bundles__header p {
  font-size: 1.0625rem;
  color: var(--eco-text-muted);
  max-width: 560px;
  margin: 0 auto;
  line-height: 1.65;
}

.eco-bundles__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}

@media (min-width: 640px) {
  .eco-bundles__grid { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
  .eco-bundles__grid { grid-template-columns: repeat(3, 1fr); }
}

.eco-bundle {
  background: white;
  border: 2px solid transparent;
  border-radius: var(--eco-radius-lg);
  padding: 32px;
  text-align: center;
  transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.eco-bundle:hover {
  border-color: var(--eco-primary);
  box-shadow: var(--eco-shadow-lg);
}

.eco-bundle__icon {
  width: 56px;
  height: 56px;
  margin: 0 auto 16px;
  background: var(--eco-primary-light);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--eco-primary);
}

.eco-bundle h4 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--eco-text);
  margin-bottom: 8px;
}

.eco-bundle p {
  font-size: 0.9375rem;
  color: var(--eco-text-muted);
  margin-bottom: 16px;
  line-height: 1.6;
}

.eco-bundle__price {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 8px;
}

.eco-bundle__save {
  display: inline-block;
  font-size: 0.8125rem;
  font-weight: 700;
  color: var(--eco-accent);
  background: var(--eco-accent-light);
  padding: 4px 12px;
  border-radius: 999px;
}

/* ---- FAQ ---- */
.eco-faq__header {
  margin-bottom: 48px;
  text-align: center;
}

.eco-faq__header h2 {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 12px;
}

.eco-faq__list {
  max-width: 800px;
  margin: 0 auto;
}

.eco-faq__question {
  background: transparent;
  border: none;
  width: 100%;
  text-align: left;
  font-family: inherit;
}

/* ---- Final CTA ---- */
.eco-final {
  padding: 80px 0;
  text-align: center;
}

.eco-final h2 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(1.5rem, 3vw, 2.25rem);
  font-weight: 800;
  color: var(--eco-text);
  margin-bottom: 24px;
}

.eco-final p {
  font-size: 1.0625rem;
  color: var(--eco-text-muted);
  max-width: 560px;
  margin: 0 auto 32px;
  line-height: 1.65;
}

.eco-final__actions {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

/* ---- Plan card illustration modifiers ---- */
.eco-plan-card__illustration--basic { background: #E8EEFB; }
.eco-plan-card__illustration--deluxe { background: #E0FAF0; }
.eco-plan-card__illustration--premium { background: #FFF8E1; }
.eco-plan-card__illustration--ultimate { background: #F3E8FF; }

/* ---- Product card shortcode overrides ---- */
.eco-product-card .rstore-product,
.eco-product-card .reseller-product {
  margin: 0;
}

.eco-product-card .rstore-product-title,
.eco-product-card .reseller-product-title,
.eco-product-card h3 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  margin-bottom: 8px;
  color: #0F1923;
}

.eco-product-card .rstore-product-price,
.eco-product-card .reseller-product-price {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0F1923;
  margin: 12px 0;
  display: block;
}

.eco-product-card .rstore-product-button,
.eco-product-card .reseller-product-button,
.eco-product-card .button,
.eco-product-card a.button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  background: linear-gradient(135deg, #1B4FD8, #1240B0);
  color: #fff;
  font-weight: 700;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
}

.eco-product-card .rstore-product-button:hover,
.eco-product-card .reseller-product-button:hover,
.eco-product-card .button:hover,
.eco-product-card a.button:hover {
  box-shadow: 0 4px 12px rgba(27,79,216,0.25);
  transform: translateY(-1px);
}

/* ---- Responsive ---- */
@media (max-width: 639px) {
  .eco-plans { padding: 40px 0; }
  .eco-category { padding: 40px 0; }
  .eco-category__header h2 { font-size: 1.25rem; }
  .eco-final { padding: 60px 0; }
}
</style>

<main class="gano-eco-v3">

  <!-- ════════════════════════════════════════════════════════════════
       HERO
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-hero">
    <div class="eco-hero__orbs">
      <div class="eco-hero__orb eco-hero__orb--primary"></div>
      <div class="eco-hero__orb eco-hero__orb--gold"></div>
      <div class="eco-hero__orb eco-hero__orb--accent"></div>
    </div>
    <div class="eco-hero__content eco-container">
      <span class="eco-hero__label">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <?php esc_html_e( 'Catálogo Gano Digital', 'gano-child' ); ?>
      </span>
      <h1 class="eco-hero__title"><?php esc_html_e( 'Infraestructura que', 'gano-child' ); ?> <span><?php esc_html_e( 'crece contigo', 'gano-child' ); ?></span></h1>
      <p class="eco-hero__lead">
        <?php esc_html_e( 'Descubre nuestro catálogo completo de productos GoDaddy reseller: hosting, dominios, seguridad, email y más. Operamos en español y facturamos en pesos colombianos.', 'gano-child' ); ?>
      </p>

      <div class="eco-hero__toggle" id="billing-toggle">
        <button type="button" class="eco-hero__toggle-btn active" data-period="monthly"><?php esc_html_e( 'Mensual', 'gano-child' ); ?></button>
        <button type="button" class="eco-hero__toggle-btn" data-period="annual"><?php esc_html_e( 'Anual', 'gano-child' ); ?> <span class="eco-hero__badge">-20%</span></button>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       STICKY TABS
       ════════════════════════════════════════════════════════════════ -->
  <div class="eco-tabs">
    <div class="eco-container">
      <nav class="eco-tabs__scroll" id="cat-tabs" aria-label="<?php esc_attr_e( 'Categorías de productos', 'gano-child' ); ?>">
        <a href="#cat-hosting" class="eco-tabs__btn active"><?php esc_html_e( 'Hosting', 'gano-child' ); ?></a>
        <?php if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) : ?>
          <?php foreach ( $categories as $cat ) : ?>
            <?php if ( 'hosting' === $cat->slug ) { continue; } ?>
            <a href="#cat-<?php echo esc_attr( $cat->slug ); ?>" class="eco-tabs__btn"><?php echo esc_html( $cat->name ); ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </nav>
    </div>
  </div>

  <!-- ════════════════════════════════════════════════════════════════
       PLANES DESTACADOS — HOSTING
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-plans" id="cat-hosting">
    <div class="eco-container">
      <div class="eco-plans-grid">

        <?php foreach ( $featured_products as $plan ) : ?>
          <?php
          $card_id    = isset( $plan['id'] ) ? $plan['id'] : 'plan-' . wp_rand( 1000, 9999 );
          $card_class = ! empty( $plan['featured'] ) ? 'eco-plan-card eco-plan-card--featured eco-reveal' : 'eco-plan-card eco-reveal';
          $illust_mod = ( 'wordpress-basico' === $card_id ) ? 'basic' : ( ( 'wordpress-deluxe' === $card_id ) ? 'deluxe' : ( ( 'wordpress-ultimate' === $card_id ) ? 'premium' : 'ultimate' ) );
          ?>
          <article class="<?php echo esc_attr( $card_class ); ?>" id="<?php echo esc_attr( $card_id ); ?>">
            <?php if ( ! empty( $plan['tag'] ) ) : ?>
              <span class="eco-plan-card__badge"><?php echo esc_html( $plan['tag'] ); ?></span>
            <?php endif; ?>

            <div class="eco-plan-card__illustration eco-plan-card__illustration--<?php echo esc_attr( $illust_mod ); ?>">
              <svg fill="none" viewBox="0 0 24 24" stroke="<?php echo esc_attr( $plan['color'] ); ?>" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr( $plan['icon'] ); ?>"/>
              </svg>
            </div>

            <h3 class="eco-plan-card__name"><?php echo esc_html( $plan['nombre'] ); ?></h3>
            <p class="eco-plan-card__desc"><?php echo esc_html( $plan['desc'] ); ?></p>

            <div class="eco-plan-card__price-wrap">
              <div class="eco-plan-card__price" data-monthly="<?php echo esc_attr( $plan['precio'] ); ?>" data-annual="<?php echo esc_attr( $plan['precio_anual'] ); ?>">
                <?php echo esc_html( $plan['precio'] ); ?>
              </div>
              <div class="eco-plan-card__period">/mes · IVA incluido</div>
              <div class="eco-plan-card__save" data-ahorro="<?php echo esc_attr( $plan['ahorro'] ); ?>" style="opacity:0;height:0;overflow:hidden;transition:all .3s ease;"><?php echo esc_html( $plan['ahorro'] ); ?> en anual</div>
            </div>

            <ul class="eco-plan-card__features">
              <?php foreach ( $plan['features'] as $f ) : ?>
                <li class="eco-plan-card__feature">
                  <svg class="eco-plan-card__check" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  <?php echo esc_html( $f ); ?>
                </li>
              <?php endforeach; ?>
            </ul>

            <a href="<?php echo esc_url( $plan['link'] ); ?>" class="eco-plan-card__cta eco-btn eco-btn--primary">
              <?php echo esc_html( $plan['cta'] ); ?>
            </a>
            <a href="<?php echo esc_url( $plan['link'] ); ?>" class="eco-btn eco-btn--ghost">
              <?php esc_html_e( 'Ver detalles del plan →', 'gano-child' ); ?>
            </a>
          </article>
        <?php endforeach; ?>

      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       MÁS CATEGORÍAS
       ════════════════════════════════════════════════════════════════ -->
  <?php if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) : ?>
    <?php foreach ( $categories as $cat ) : ?>
      <?php if ( 'hosting' === $cat->slug ) { continue; } ?>

      <?php
      $cat_query = new WP_Query(
        array(
          'post_type'      => 'reseller_product',
          'posts_per_page' => -1,
          'post_status'    => 'publish',
          'tax_query'      => array(
            array(
              'taxonomy' => 'reseller_product_category',
              'field'    => 'slug',
              'terms'    => $cat->slug,
            ),
          ),
          'orderby' => 'title',
          'order'   => 'ASC',
        )
      );

      if ( ! $cat_query->have_posts() ) {
        wp_reset_postdata();
        continue;
      }
      ?>

      <section class="eco-category" id="cat-<?php echo esc_attr( $cat->slug ); ?>">
        <div class="eco-container">
          <div class="eco-category__header eco-reveal">
            <h2><?php echo esc_html( $cat->name ); ?></h2>
            <span class="eco-category__count">
              <?php
              printf(
                /* translators: %d: number of products */
                esc_html( _n( '%d producto', '%d productos', $cat_query->found_posts, 'gano-child' ) ),
                (int) $cat_query->found_posts
              );
              ?>
            </span>
          </div>
          <div class="eco-products__grid">
            <?php while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
              <div class="eco-product-card eco-reveal">
                <?php echo do_shortcode( '[rstore_product post_id=' . get_the_ID() . ']' ); ?>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>

      <?php wp_reset_postdata(); ?>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- ════════════════════════════════════════════════════════════════
       BUNDLES
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-bundles">
    <div class="eco-container">
      <div class="eco-bundles__header eco-reveal">
        <h2><?php esc_html_e( 'Bundles recomendados', 'gano-child' ); ?></h2>
        <p><?php esc_html_e( 'Combina dominio, hosting y seguridad con descuentos exclusivos. Un solo pago, todo incluido.', 'gano-child' ); ?></p>
      </div>
      <div class="eco-bundles__grid">
        <?php foreach ( $bundles as $b ) : ?>
          <div class="eco-bundle eco-reveal">
            <div class="eco-bundle__icon">
              <svg fill="none" viewBox="0 0 24 24" stroke="#1B4FD8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr( $b['icon'] ); ?>"/></svg>
            </div>
            <h4><?php echo esc_html( $b['nombre'] ); ?></h4>
            <p><?php echo esc_html( $b['desc'] ); ?></p>
            <div class="eco-bundle__price"><?php echo esc_html( $b['precio'] ); ?> <small>/mes</small></div>
            <span class="eco-bundle__save"><?php echo esc_html( $b['ahorro'] ); ?></span>
            <a href="<?php echo esc_url( $b['link'] ); ?>" class="eco-btn eco-btn--secondary" style="width:100%;"><?php echo esc_html( $b['cta'] ); ?></a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       TRUST SIGNALS
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-trust">
    <div class="eco-container">
      <h2 class="eco-trust__title eco-reveal"><?php esc_html_e( 'Por qué confían en Gano Digital', 'gano-child' ); ?></h2>
      <div class="eco-trust__grid">
        <div class="eco-trust__item eco-reveal">
          <svg class="eco-trust__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          <h4 class="eco-trust__item-title"><?php esc_html_e( 'Infraestructura GoDaddy', 'gano-child' ); ?></h4>
          <p class="eco-trust__item-desc"><?php esc_html_e( 'Programa reseller autorizado con estándares globales de datacenter.', 'gano-child' ); ?></p>
        </div>
        <div class="eco-trust__item eco-reveal">
          <svg class="eco-trust__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <h4 class="eco-trust__item-title"><?php esc_html_e( '99.9% Disponibilidad', 'gano-child' ); ?></h4>
          <p class="eco-trust__item-desc"><?php esc_html_e( 'SLA comprometido con monitoreo proactivo incluido.', 'gano-child' ); ?></p>
        </div>
        <div class="eco-trust__item eco-reveal">
          <svg class="eco-trust__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <h4 class="eco-trust__item-title"><?php esc_html_e( 'Facturación en COP', 'gano-child' ); ?></h4>
          <p class="eco-trust__item-desc"><?php esc_html_e( 'Pesos colombianos. Sin sorpresas de tipo de cambio.', 'gano-child' ); ?></p>
        </div>
        <div class="eco-trust__item eco-reveal">
          <svg class="eco-trust__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 9v4m0 0v4m0-4h4m-4 0H8"/></svg>
          <h4 class="eco-trust__item-title"><?php esc_html_e( 'Soporte en español', 'gano-child' ); ?></h4>
          <p class="eco-trust__item-desc"><?php esc_html_e( 'Primera respuesta en horas, no en días. En tu idioma.', 'gano-child' ); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       FAQ
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-faq">
    <div class="eco-container">
      <div class="eco-faq__header eco-reveal">
        <h2><?php esc_html_e( 'Preguntas frecuentes', 'gano-child' ); ?></h2>
      </div>
      <div class="eco-faq__list">
        <?php foreach ( $faqs as $i => $faq ) : ?>
          <div class="eco-faq__item <?php echo ( 0 === $i ) ? 'open' : ''; ?> eco-reveal">
            <button type="button" class="eco-faq__question" onclick="this.parentElement.classList.toggle('open')">
              <?php echo esc_html( $faq[0] ); ?>
              <svg class="eco-faq__arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="eco-faq__answer"><p class="eco-faq__answer-text"><?php echo esc_html( $faq[1] ); ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       FINAL CTA
       ════════════════════════════════════════════════════════════════ -->
  <section class="eco-final">
    <div class="eco-container">
      <h2 class="eco-reveal"><?php esc_html_e( '¿No sabes cuál elegir?', 'gano-child' ); ?></h2>
      <p class="eco-reveal"><?php esc_html_e( 'Habla con nuestro equipo. Te ayudamos a identificar el plan ideal para tu proyecto sin compromiso.', 'gano-child' ); ?></p>
      <div class="eco-final__actions eco-reveal">
        <a href="/contacto/" class="eco-btn eco-btn--primary"><?php esc_html_e( 'Hablar con un asesor', 'gano-child' ); ?></a>
        <a href="/dominios/" class="eco-btn eco-btn--secondary"><?php esc_html_e( 'Buscar un dominio', 'gano-child' ); ?></a>
      </div>
    </div>
  </section>

</main>

<script>
(function() {
  'use strict';

  // ── Scroll reveal (Intersection Observer) ───────────────────────
  const revealEls = document.querySelectorAll('.eco-reveal');
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(el => io.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add('visible'));
  }

  // ── Billing toggle (mensual / anual) ────────────────────────────
  const toggleWrap = document.getElementById('billing-toggle');
  if (toggleWrap) {
    const btns = toggleWrap.querySelectorAll('[data-period]');
    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        const period = btn.dataset.period;
        btns.forEach(b => b.classList.toggle('active', b === btn));

        document.querySelectorAll('.eco-plan-card__price').forEach(el => {
          el.textContent = period === 'annual' ? el.dataset.annual : el.dataset.monthly;
        });
        document.querySelectorAll('.eco-plan-card__save').forEach(el => {
          if (period === 'annual') {
            el.style.opacity = '1';
            el.style.height = 'auto';
            el.style.marginTop = '6px';
          } else {
            el.style.opacity = '0';
            el.style.height = '0';
            el.style.marginTop = '0';
          }
        });
      });
    });
  }

  // ── Category tabs active state on scroll ────────────────────────
  const tabs = document.querySelectorAll('.eco-tabs__btn');
  const sections = document.querySelectorAll('.eco-category, #cat-hosting');
  if (tabs.length && sections.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute('id');
          tabs.forEach(tab => {
            const href = tab.getAttribute('href');
            tab.classList.toggle('active', href === '#' + id);
          });
        }
      });
    }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });
    sections.forEach(sec => observer.observe(sec));
  }
})();
</script>

<?php get_footer(); ?>
