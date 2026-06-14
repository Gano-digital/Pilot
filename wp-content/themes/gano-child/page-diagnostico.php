<?php
/**
 * Template Name: Diagnóstico Digital
 *
 * Quiz interactivo de diagnóstico digital para prospectos.
 * Captura leads vía REST API y muestra recomendaciones de productos Gano.
 * Redirige al catálogo existente (/ecosistemas/ o /shop-premium/).
 *
 * @package gano-child
 */

get_header();

$url_contacto = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'contacto' )
	: home_url( '/contacto/' );

$url_ecosistemas = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'ecosistemas' )
	: home_url( '/ecosistemas/' );
?>

<main class="gano-diagnostico">

	<!-- Header del diagnóstico -->
	<header class="gano-diagnostico__header">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gano-diagnostico__logo">
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
		</a>
		<span class="gano-diagnostico__badge">Diagnóstico Digital Gratuito · 3 minutos</span>
		<a href="<?php echo esc_url( $url_contacto ); ?>" class="gano-diagnostico__cta-top">
			Hablar con asesor
		</a>
	</header>

	<!-- QUIZ -->
	<div class="gano-diagnostico__quiz" id="gd-quiz">
		<div class="gano-diagnostico__progress-bar">
			<div class="gano-diagnostico__progress-fill" id="gd-progress" style="width:0%"></div>
		</div>

		<!-- STEP 0: Tamaño -->
		<div class="gano-diagnostico__step active" data-step="0">
			<div class="gano-diagnostico__step-label">Inicio · Cuéntenos sobre su empresa</div>
			<h1 class="gano-diagnostico__question-title">¿Cuántas personas trabajan en su empresa?</h1>
			<p class="gano-diagnostico__question-sub">Esto nos ayuda a calibrar las preguntas y recomendaciones según el nivel de complejidad operacional de su negocio.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'size','solo')" data-val="solo">
					<span class="gano-diagnostico__option-icon">🧍</span>
					<span class="gano-diagnostico__option-label">Solo yo</span>
					<span class="gano-diagnostico__option-desc">Emprendedor, freelancer o negocio unipersonal</span>
					<span class="gano-diagnostico__option-check">✓</span>
				</div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'size','micro')" data-val="micro">
					<span class="gano-diagnostico__option-icon">👥</span>
					<span class="gano-diagnostico__option-label">2 a 10 personas</span>
					<span class="gano-diagnostico__option-desc">Microempresa o startup en etapa temprana</span>
					<span class="gano-diagnostico__option-check">✓</span>
				</div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'size','small')" data-val="small">
					<span class="gano-diagnostico__option-icon">🏢</span>
					<span class="gano-diagnostico__option-label">11 a 50 personas</span>
					<span class="gano-diagnostico__option-desc">Pequeña empresa o pyme en crecimiento</span>
					<span class="gano-diagnostico__option-check">✓</span>
				</div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'size','medium')" data-val="medium">
					<span class="gano-diagnostico__option-icon">🏛️</span>
					<span class="gano-diagnostico__option-label">51 a 200 personas</span>
					<span class="gano-diagnostico__option-desc">Empresa mediana con múltiples áreas</span>
					<span class="gano-diagnostico__option-check">✓</span>
				</div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'size','large')" data-val="large">
					<span class="gano-diagnostico__option-icon">🌆</span>
					<span class="gano-diagnostico__option-label">Más de 200 personas</span>
					<span class="gano-diagnostico__option-desc">Corporación o grupo empresarial</span>
					<span class="gano-diagnostico__option-check">✓</span>
				</div>
			</div>
			<div class="gano-diagnostico__nav">
				<span></span>
				<button class="gano-diagnostico__btn-next" id="gd-next-0" onclick="ganoNextStep()" disabled>Continuar →</button>
			</div>
		</div>

		<!-- STEP 1: Sector -->
		<div class="gano-diagnostico__step" data-step="1">
			<div class="gano-diagnostico__step-label">Paso 1 de 8 · Su actividad</div>
			<h2 class="gano-diagnostico__question-title">¿En qué sector opera principalmente?</h2>
			<p class="gano-diagnostico__question-sub">Cada industria tiene necesidades digitales diferentes. Queremos entender su contexto antes de recomendar.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','retail')" data-val="retail"><span class="gano-diagnostico__option-icon">🛒</span><span class="gano-diagnostico__option-label">Comercio y retail</span><span class="gano-diagnostico__option-desc">Venta de productos físicos o digitales</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','services')" data-val="services"><span class="gano-diagnostico__option-icon">🤝</span><span class="gano-diagnostico__option-label">Servicios profesionales</span><span class="gano-diagnostico__option-desc">Consultoría, legal, contable, salud</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','tech')" data-val="tech"><span class="gano-diagnostico__option-icon">💻</span><span class="gano-diagnostico__option-label">Tecnología y SaaS</span><span class="gano-diagnostico__option-desc">Software, apps, plataformas digitales</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','edu')" data-val="edu"><span class="gano-diagnostico__option-icon">📚</span><span class="gano-diagnostico__option-label">Educación y formación</span><span class="gano-diagnostico__option-desc">Cursos, capacitaciones, instituciones</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','food')" data-val="food"><span class="gano-diagnostico__option-icon">🍽️</span><span class="gano-diagnostico__option-label">Gastronomía y hotelería</span><span class="gano-diagnostico__option-desc">Restaurantes, cafés, hoteles, turismo</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'sector','other')" data-val="other"><span class="gano-diagnostico__option-icon">⚙️</span><span class="gano-diagnostico__option-label">Otro sector</span><span class="gano-diagnostico__option-desc">Manufactura, agroindustria, construcción</span><span class="gano-diagnostico__option-check">✓</span></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" id="gd-next-1" onclick="ganoNextStep()" disabled>Continuar →</button>
			</div>
		</div>

		<!-- STEP 2: Presencia web -->
		<div class="gano-diagnostico__step" data-step="2">
			<div class="gano-diagnostico__step-label">Paso 2 de 8 · Su sitio web</div>
			<h2 class="gano-diagnostico__question-title">¿Cómo está su sitio web actualmente?</h2>
			<p class="gano-diagnostico__question-sub">Su sitio web es la base de toda su presencia digital. Entender su estado actual nos ayuda a identificar la brecha.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'web','none')" data-val="none"><span class="gano-diagnostico__option-icon">🚫</span><span class="gano-diagnostico__option-label">No tengo sitio web</span><span class="gano-diagnostico__option-desc">Estoy buscando cómo crear uno</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'web','builder')" data-val="builder"><span class="gano-diagnostico__option-icon">🧱</span><span class="gano-diagnostico__option-label">Tengo uno básico</span><span class="gano-diagnostico__option-desc">Wix, WordPress.com u otra plataforma gratuita</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'web','wordpress')" data-val="wordpress"><span class="gano-diagnostico__option-icon">📄</span><span class="gano-diagnostico__option-label">WordPress propio</span><span class="gano-diagnostico__option-desc">Hosting con cPanel, necesita actualización</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'web','ecommerce')" data-val="ecommerce"><span class="gano-diagnostico__option-icon">🛍️</span><span class="gano-diagnostico__option-label">Tienda en línea</span><span class="gano-diagnostico__option-desc">WooCommerce, Shopify u otra plataforma e-commerce</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'web','custom')" data-val="custom"><span class="gano-diagnostico__option-icon">⚡</span><span class="gano-diagnostico__option-label">Aplicación web</span><span class="gano-diagnostico__option-desc">Sistema o plataforma desarrollada a medida</span><span class="gano-diagnostico__option-check">✓</span></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" id="gd-next-2" onclick="ganoNextStep()" disabled>Continuar →</button>
			</div>
		</div>

		<!-- STEP 3: Email -->
		<div class="gano-diagnostico__step" data-step="3">
			<div class="gano-diagnostico__step-label">Paso 3 de 8 · Comunicación</div>
			<h2 class="gano-diagnostico__question-title">¿Cómo se comunica por email con sus clientes?</h2>
			<p class="gano-diagnostico__question-sub">El tipo de correo que usa dice mucho sobre el nivel de profesionalismo que proyecta ante sus clientes y aliados.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'email','gmail')" data-val="gmail"><span class="gano-diagnostico__option-icon">📧</span><span class="gano-diagnostico__option-label">Gmail o Hotmail</span><span class="gano-diagnostico__option-desc">Correo personal para todo lo laboral</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'email','domain')" data-val="domain"><span class="gano-diagnostico__option-icon">✉️</span><span class="gano-diagnostico__option-label">Correo con mi dominio</span><span class="gano-diagnostico__option-desc">usuario@miempresa.com — hosting básico</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'email','m365')" data-val="m365"><span class="gano-diagnostico__option-icon">🏢</span><span class="gano-diagnostico__option-label">Microsoft 365 / Google Workspace</span><span class="gano-diagnostico__option-desc">Suite profesional con herramientas de colaboración</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'email','none')" data-val="none"><span class="gano-diagnostico__option-icon">❌</span><span class="gano-diagnostico__option-label">No uso email empresarial</span><span class="gano-diagnostico__option-desc">Solo mensajería (WhatsApp, etc.)</span><span class="gano-diagnostico__option-check">✓</span></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" id="gd-next-3" onclick="ganoNextStep()" disabled>Continuar →</button>
			</div>
		</div>

		<!-- STEP 4: Seguridad -->
		<div class="gano-diagnostico__step" data-step="4">
			<div class="gano-diagnostico__step-label">Paso 4 de 8 · Seguridad</div>
			<h2 class="gano-diagnostico__question-title">¿Qué tan sensibles son los datos que maneja?</h2>
			<p class="gano-diagnostico__question-sub">Esto determina el nivel de protección que necesita su infraestructura y los riesgos a los que está expuesto.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'data','public')" data-val="public"><span class="gano-diagnostico__option-icon">🌐</span><span class="gano-diagnostico__option-label">Solo información pública</span><span class="gano-diagnostico__option-desc">Catálogos, precios, información de contacto</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'data','clients')" data-val="clients"><span class="gano-diagnostico__option-icon">👤</span><span class="gano-diagnostico__option-label">Datos de clientes</span><span class="gano-diagnostico__option-desc">Nombres, emails, historial de compras, teléfonos</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'data','payments')" data-val="payments"><span class="gano-diagnostico__option-icon">💳</span><span class="gano-diagnostico__option-label">Pagos y finanzas</span><span class="gano-diagnostico__option-desc">Transacciones, facturación, datos bancarios</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'data','sensitive')" data-val="sensitive"><span class="gano-diagnostico__option-icon">🏥</span><span class="gano-diagnostico__option-label">Datos sensibles o regulados</span><span class="gano-diagnostico__option-desc">Salud, datos biométricos, secretos industriales</span><span class="gano-diagnostico__option-check">✓</span></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" id="gd-next-4" onclick="ganoNextStep()" disabled>Continuar →</button>
			</div>
		</div>

		<!-- STEP 5: Marketing channels -->
		<div class="gano-diagnostico__step" data-step="5">
			<div class="gano-diagnostico__step-label">Paso 5 de 8 · Presencia digital</div>
			<h2 class="gano-diagnostico__question-title">¿Qué canales digitales ya tiene activos?</h2>
			<p class="gano-diagnostico__question-sub">Seleccione todos los que apliquen. Esto nos permite identificar los recursos que ya tiene y los que necesita construir.</p>
			<div class="gano-diagnostico__multi-note">ℹ️ Puede seleccionar varios</div>
			<div class="gano-diagnostico__checkbox-grid">
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','instagram')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Instagram</strong>Perfil activo con publicaciones</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','facebook')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Facebook / Meta</strong>Página de empresa activa</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','linkedin')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>LinkedIn</strong>Perfil corporativo</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','tiktok')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>TikTok</strong>Canal con contenido</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','whatsapp')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>WhatsApp Business</strong>Número empresarial configurado</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'channels','youtube')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>YouTube</strong>Canal con videos</div></div>
				<div class="gano-diagnostico__cb-item selected" onclick="ganoToggleCB(this,'channels','none')"><span class="gano-diagnostico__cb-box" style="background:var(--gd-tertiary);border-color:var(--gd-tertiary);color:#001f27">✓</span><div class="gano-diagnostico__cb-text"><strong>Ninguno aún</strong>Estoy comenzando</div></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" onclick="ganoNextStep()">Continuar →</button>
			</div>
		</div>

		<!-- STEP 6: Assets -->
		<div class="gano-diagnostico__step" data-step="6">
			<div class="gano-diagnostico__step-label">Paso 6 de 8 · Sus recursos actuales</div>
			<h2 class="gano-diagnostico__question-title">¿Qué activos digitales ya tiene creados?</h2>
			<p class="gano-diagnostico__question-sub">Antes de recomendar, queremos saber qué ya tiene. Así evitamos que invierta en algo que ya posee.</p>
			<div class="gano-diagnostico__multi-note">ℹ️ Puede seleccionar varios</div>
			<div class="gano-diagnostico__checkbox-grid">
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','logo')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Logo diseñado</strong>Versiones PNG/SVG disponibles</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','brandbook')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Manual de marca</strong>Colores, tipografías definidas</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','domain')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Dominio registrado</strong>Mi .com, .co u otro</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','photos')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Fotos profesionales</strong>Productos, equipo, instalaciones</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','flyers')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Material gráfico</strong>Flyers, banners, presentaciones</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','video')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Video corporativo</strong>Presentación o demo</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','content')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Contenido escrito</strong>Blog, artículos, textos de web</div></div>
				<div class="gano-diagnostico__cb-item" onclick="ganoToggleCB(this,'assets','ads')"><span class="gano-diagnostico__cb-box">✓</span><div class="gano-diagnostico__cb-text"><strong>Pauta digital activa</strong>Google Ads, Meta Ads</div></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" onclick="ganoNextStep()">Continuar →</button>
			</div>
		</div>

		<!-- STEP 7: Goal -->
		<div class="gano-diagnostico__step" data-step="7">
			<div class="gano-diagnostico__step-label">Paso 7 de 8 · Sus metas</div>
			<h2 class="gano-diagnostico__question-title">¿Cuál es su principal meta digital para los próximos 12 meses?</h2>
			<p class="gano-diagnostico__question-sub">Esto define si priorizamos estabilidad, crecimiento, eficiencia operativa o transformación completa.</p>
			<div class="gano-diagnostico__options">
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'goal','launch')" data-val="launch"><span class="gano-diagnostico__option-icon">🚀</span><span class="gano-diagnostico__option-label">Lanzar mi presencia digital</span><span class="gano-diagnostico__option-desc">Crear sitio web, correo y marca desde cero</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'goal','migrate')" data-val="migrate"><span class="gano-diagnostico__option-icon">🔄</span><span class="gano-diagnostico__option-label">Migrar y mejorar lo actual</span><span class="gano-diagnostico__option-desc">Cambiar de proveedor o actualizar infraestructura</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'goal','scale')" data-val="scale"><span class="gano-diagnostico__option-icon">📈</span><span class="gano-diagnostico__option-label">Escalar y crecer</span><span class="gano-diagnostico__option-desc">Más tráfico, más ventas, más capacidad</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'goal','secure')" data-val="secure"><span class="gano-diagnostico__option-icon">🛡️</span><span class="gano-diagnostico__option-label">Fortalecer la seguridad</span><span class="gano-diagnostico__option-desc">Proteger datos, cumplir regulaciones</span><span class="gano-diagnostico__option-check">✓</span></div>
				<div class="gano-diagnostico__option" onclick="ganoSelectOption(this,'goal','automate')" data-val="automate"><span class="gano-diagnostico__option-icon">🤖</span><span class="gano-diagnostico__option-label">Automatizar procesos</span><span class="gano-diagnostico__option-desc">Reducir carga operativa con tecnología</span><span class="gano-diagnostico__option-check">✓</span></div>
			</div>
			<div class="gano-diagnostico__nav">
				<button class="gano-diagnostico__btn-back" onclick="ganoPrevStep()">← Atrás</button>
				<button class="gano-diagnostico__btn-next" id="gd-next-7" onclick="ganoNextStep()" disabled>Ver mi diagnóstico →</button>
			</div>
		</div>

	</div><!-- /quiz -->

	<!-- RESULTS -->
	<div class="gano-diagnostico__results" id="gd-results">

		<div id="gd-result-profile" class="gano-diagnostico__profile-badge"></div>

		<div style="margin-bottom:1rem">
			<span style="font-size:.7rem;text-transform:uppercase;letter-spacing:.15em;color:var(--gd-tertiary);font-weight:700">Su mapa de necesidades digitales</span>
		</div>
		<div class="gano-diagnostico__segments" id="gd-result-segments"></div>

		<div class="gano-diagnostico__lego-section">
			<div class="gano-diagnostico__lego-title">📦 Inventario: Lo que ya tiene</div>
			<div class="gano-diagnostico__lego-grid" id="gd-lego-have"></div>
		</div>

		<div class="gano-diagnostico__lego-section">
			<div class="gano-diagnostico__lego-title">➕ Lo que necesita para competir</div>
			<div class="gano-diagnostico__lego-grid" id="gd-lego-need"></div>
		</div>

		<div style="margin-bottom:1rem">
			<span style="font-size:.7rem;text-transform:uppercase;letter-spacing:.15em;color:var(--gd-primary);font-weight:700">Servicios recomendados para su empresa</span>
		</div>
		<div class="gano-diagnostico__reco-grid" id="gd-result-recos"></div>

		<div class="gano-diagnostico__cta-box">
			<h2>Su diagnóstico está listo.<br>
				<span style="background:linear-gradient(135deg,var(--gd-primary),var(--gd-tertiary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">¿Hablamos?</span>
			</h2>
			<p style="color:var(--gd-subt);max-width:500px;margin:0 auto;line-height:1.7">
				Un asesor de Gano Digital revisará este diagnóstico con usted y armará un plan personalizado. Sin compromiso, sin letra pequeña.
			</p>
			<div class="gano-diagnostico__cta-btns">
				<a href="<?php echo esc_url( $url_contacto ); ?>" class="gano-diagnostico__cta-primary">Hablar con un asesor →</a>
				<a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="gano-diagnostico__cta-secondary">Ver catálogo completo</a>
			</div>
			<button class="gano-diagnostico__restart-btn" onclick="ganoRestart()">🔄 Reiniciar diagnóstico</button>
		</div>

	</div><!-- /results -->

	<!-- LEAD CAPTURE OVERLAY -->
	<div class="gano-diagnostico__lead-overlay" id="gd-lead-overlay">
		<div class="gano-diagnostico__lead-box" id="gd-lead-box">
			<h3>Antes de ver sus resultados...</h3>
			<p>Déjenos sus datos para enviarle el diagnóstico completo y una propuesta personalizada. Sin spam, sin compromiso.</p>
			<input type="text" class="gano-diagnostico__lead-input" id="gd-lead-name" placeholder="Nombre completo *" aria-label="Nombre completo">
			<input type="email" class="gano-diagnostico__lead-input" id="gd-lead-email" placeholder="Correo electrónico *" aria-label="Correo electrónico">
			<input type="tel" class="gano-diagnostico__lead-input" id="gd-lead-phone" placeholder="Teléfono / WhatsApp" aria-label="Teléfono">
			<input type="text" class="gano-diagnostico__lead-input" id="gd-lead-company" placeholder="Empresa (opcional)" aria-label="Empresa">
			<button class="gano-diagnostico__lead-submit" id="gd-lead-submit" onclick="ganoSubmitLead()">Enviar y ver mi diagnóstico →</button>
			<button class="gano-diagnostico__lead-skip" onclick="ganoSkipLead()">Prefiero omitir este paso</button>
		</div>
		<div class="gano-diagnostico__lead-success" id="gd-lead-success">
			<h3>✓ Información enviada</h3>
			<p>Preparando su diagnóstico personalizado...</p>
		</div>
	</div>

</main>

<?php
get_footer();
