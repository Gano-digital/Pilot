<?php
/**
 * Template Name: Dominios — Registra tu Presencia
 * Description: Página de búsqueda y registro de dominios TLD con integración GoDaddy Reseller
 * SOTA aesthetic — buscador + grid de TLDs populares
 */

get_header();

// Dominio pre-cargado desde ?domain=X (vía formulario o botón TLD)
$initial_domain = isset( $_GET['domain'] ) ? sanitize_text_field( wp_unslash( $_GET['domain'] ) ) : '';

// PLID del reseller para construir URLs de carrito
$plid = 0;
if ( function_exists( 'rstore_get_option' ) ) {
    $plid = (int) rstore_get_option( 'pl_id' );
}
if ( $plid <= 0 ) {
    $plid = (int) get_option( 'rstore_pl_id', 599667 );
}
if ( $plid <= 0 ) {
    $plid = 599667;
}

// URL del proxy: /wp-json/gano/v1/domains/search
$proxy_url = rest_url( 'gano/v1/domains/search' );
?>

<main class="dominios-page">
    <!-- HERO -->
    <section class="dominios-hero">
        <div class="hero-content">
            <h1><?php esc_html_e( 'Tu Identidad Digital Comienza Aquí', 'gano-child' ); ?></h1>
            <p><?php esc_html_e( 'Busca, registra y gestiona dominios con la máxima libertad. Precios en COP, soporte en español y control total.', 'gano-child' ); ?></p>
        </div>
    </section>

    <!-- BUSCADOR DE DOMINIOS -->
    <section id="dominios-search" class="dominios-search-section">
        <h2 class="dominios-search-title"><?php esc_html_e( 'Busca tu Dominio Ideal', 'gano-child' ); ?></h2>

        <div class="search-container">
            <!-- Formulario propio — llama al proxy PHP en lugar de GoDaddy directamente
                 (GoDaddy no tiene CORS en www.secureserver.net para dominios reseller) -->
            <form id="gano-domain-form" class="gano-domain-form" autocomplete="off">
                <div class="gano-domain-input-wrap">
                    <input
                        type="text"
                        id="gano-domain-input"
                        class="gano-domain-input"
                        placeholder="<?php esc_attr_e( 'Encuentra tu dominio perfecto', 'gano-child' ); ?>"
                        value="<?php echo esc_attr( $initial_domain ); ?>"
                        maxlength="253"
                        autocomplete="off"
                        spellcheck="false"
                        aria-label="<?php esc_attr_e( 'Nombre de dominio a buscar', 'gano-child' ); ?>"
                    />
                    <button type="submit" class="gano-domain-btn" id="gano-domain-submit">
                        <span class="gano-domain-btn__text"><?php esc_html_e( 'Buscar', 'gano-child' ); ?></span>
                        <span class="gano-domain-btn__spinner" aria-hidden="true" hidden></span>
                    </button>
                </div>
            </form>

            <!-- Área de resultados — renderizada por JS -->
            <div id="gano-domain-results" class="gano-domain-results" role="region" aria-live="polite" aria-label="<?php esc_attr_e( 'Resultados de búsqueda de dominio', 'gano-child' ); ?>"></div>
        </div>

        <script>
        (function () {
            'use strict';

            var PROXY_URL  = <?php echo wp_json_encode( esc_url_raw( $proxy_url ) ); ?>;
            var PLID       = <?php echo (int) $plid; ?>;
            var CART_BASE  = 'https://cart.secureserver.net/go/domainstep';

            var form       = document.getElementById('gano-domain-form');
            var input      = document.getElementById('gano-domain-input');
            var submitBtn  = document.getElementById('gano-domain-submit');
            var btnText    = submitBtn ? submitBtn.querySelector('.gano-domain-btn__text') : null;
            var btnSpinner = submitBtn ? submitBtn.querySelector('.gano-domain-btn__spinner') : null;
            var results    = document.getElementById('gano-domain-results');

            if ( ! form || ! input || ! results ) { return; }

            // Auto-buscar si hay dominio pre-cargado desde ?domain=X
            var initialDomain = input.value.trim();
            if ( initialDomain.length > 0 ) {
                searchDomain( initialDomain );
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var q = input.value.trim();
                if ( q.length < 1 ) { return; }
                searchDomain( q );
            });

            function setLoading( loading ) {
                if ( loading ) {
                    if ( btnText )    { btnText.hidden    = true; }
                    if ( btnSpinner ) { btnSpinner.hidden = false; }
                    if ( submitBtn )  { submitBtn.disabled = true; }
                    results.innerHTML = '<p class="gano-domain-searching"><?php echo esc_js( __( 'Consultando disponibilidad…', 'gano-child' ) ); ?></p>';
                } else {
                    if ( btnText )    { btnText.hidden    = false; }
                    if ( btnSpinner ) { btnSpinner.hidden = true; }
                    if ( submitBtn )  { submitBtn.disabled = false; }
                }
            }

            function cartUrl( fqdn ) {
                return CART_BASE + '?plid=' + PLID + '&domain=' + encodeURIComponent( fqdn );
            }

            function renderResults( data ) {
                if ( ! data || ( ! data.exactMatchDomain && ! data.suggestedDomains ) ) {
                    results.innerHTML = '<p class="gano-domain-error"><?php echo esc_js( __( 'No se encontraron resultados. Intenta con otro nombre.', 'gano-child' ) ); ?></p>';
                    return;
                }

                var html = '<ul class="gano-domain-list">';

                // Resultado exacto primero
                // GoDaddy API usa el campo 'domain' (no 'fqdn')
                function getDomainName(d) { return d.domain || d.fqdn || d.domainName || ''; }

                if ( data.exactMatchDomain ) {
                    var d     = data.exactMatchDomain;
                    var dName = getDomainName(d);
                    var avail = d.available === true || d.available === 'true';
                    html += '<li class="gano-domain-item gano-domain-item--exact ' + ( avail ? 'is-available' : 'is-taken' ) + '">';
                    html += '<span class="gano-domain-item__name">' + escHtml( dName ) + '</span>';
                    if ( avail ) {
                        html += '<span class="gano-domain-item__badge gano-domain-item__badge--ok"><?php echo esc_js( __( 'Disponible', 'gano-child' ) ); ?></span>';
                        html += '<a class="gano-domain-item__cta" href="' + escHtml( cartUrl( dName ) ) + '" target="_blank" rel="noopener"><?php echo esc_js( __( 'Registrar →', 'gano-child' ) ); ?></a>';
                    } else {
                        html += '<span class="gano-domain-item__badge gano-domain-item__badge--no"><?php echo esc_js( __( 'No disponible', 'gano-child' ) ); ?></span>';
                    }
                    html += '</li>';
                }

                // Sugerencias
                if ( data.suggestedDomains && data.suggestedDomains.length ) {
                    data.suggestedDomains.forEach( function (d) {
                        var dName = getDomainName(d);
                        var avail = d.available === true || d.available === 'true';
                        if ( ! avail ) { return; }
                        html += '<li class="gano-domain-item is-available">';
                        html += '<span class="gano-domain-item__name">' + escHtml( dName ) + '</span>';
                        html += '<span class="gano-domain-item__badge gano-domain-item__badge--ok"><?php echo esc_js( __( 'Disponible', 'gano-child' ) ); ?></span>';
                        html += '<a class="gano-domain-item__cta" href="' + escHtml( cartUrl( dName ) ) + '" target="_blank" rel="noopener"><?php echo esc_js( __( 'Registrar →', 'gano-child' ) ); ?></a>';
                        html += '</li>';
                    });
                }

                html += '</ul>';
                results.innerHTML = html;
            }

            function searchDomain( q ) {
                setLoading( true );
                fetch( PROXY_URL + '?q=' + encodeURIComponent(q) + '&pageSize=5' )
                    .then( function(r) { return r.json(); } )
                    .then( function(data) {
                        setLoading( false );
                        if ( data && data.error ) {
                            results.innerHTML = '<p class="gano-domain-error">' + escHtml( data.error ) + '</p>';
                        } else {
                            renderResults( data );
                        }
                    })
                    .catch( function() {
                        setLoading( false );
                        results.innerHTML = '<p class="gano-domain-error"><?php echo esc_js( __( 'No se pudo verificar la disponibilidad. Intenta de nuevo.', 'gano-child' ) ); ?></p>';
                    });
            }

            function escHtml( str ) {
                return String(str)
                    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
                    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            }
        })();
        </script>
    </section>

    <!-- TLDS POPULARES -->
    <section class="tlds-section">
        <h2 class="tlds-title">Extensiones Populares</h2>
        <div class="tlds-grid">
            <?php
            $tlds = [
                [
                    'extension' => '.CO',
                    'tld'       => 'co',
                    'nombre'    => 'Colombia',
                    'desc'      => 'Presencia nacional de máxima autoridad',
                ],
                [
                    'extension' => '.COM',
                    'tld'       => 'com',
                    'nombre'    => 'Global',
                    'desc'      => 'El estándar internacional de credibilidad',
                ],
                [
                    'extension' => '.NET',
                    'tld'       => 'net',
                    'nombre'    => 'Infraestructura',
                    'desc'      => 'La red técnica de confianza',
                ],
                [
                    'extension' => '.TECH',
                    'tld'       => 'tech',
                    'nombre'    => 'Tecnología',
                    'desc'      => 'Para startups e innovadores',
                ],
                [
                    'extension' => '.BIZ',
                    'tld'       => 'biz',
                    'nombre'    => 'Negocio',
                    'desc'      => 'Enfoque operativo y profesional',
                ],
                [
                    'extension' => '.AI',
                    'tld'       => 'ai',
                    'nombre'    => 'Inteligencia Artificial',
                    'desc'      => 'La frontera de la innovación',
                ],
            ];

            foreach ($tlds as $tld) {
                ?>
                <div class="tld-card" data-tld="<?php echo esc_attr($tld['tld']); ?>">
                    <h3><?php echo esc_html($tld['extension']); ?></h3>
                    <p class="tld-price"><?php esc_html_e( 'Consultar precio vigente en el buscador', 'gano-child' ); ?></p>
                    <p class="tld-description"><?php echo esc_html($tld['desc']); ?></p>
                    <a href="#dominios-search"
                       class="tld-button"
                       data-tld="<?php echo esc_attr($tld['tld']); ?>"
                       aria-label="Registrar dominio <?php echo esc_attr($tld['extension']); ?>">
                        Registrar
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- TLD pre-fill: clic en card → rellena buscador propio + dispara búsqueda + scroll -->
    <script>
    (function () {
        document.querySelectorAll('.tld-button[data-tld]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var tld   = btn.getAttribute('data-tld');
                var input = document.getElementById('gano-domain-input');
                var form  = document.getElementById('gano-domain-form');
                if ( ! input || ! tld ) { return; }

                input.value = 'midominio.' + tld;

                // Scroll suave al buscador
                var section = document.getElementById('dominios-search');
                if ( section ) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                // Pequeño delay para que el scroll complete, luego disparar búsqueda
                setTimeout(function () {
                    if ( form ) {
                        form.dispatchEvent( new Event('submit', { bubbles: true, cancelable: true }) );
                    }
                    input.focus();
                }, 400 );
            });
        });
    })();
    </script>

    <!-- CTA FINAL -->
    <section class="dominios-cta-final">
        <div style="max-width: 700px; margin: 0 auto;">
            <h2>¿Necesitas Ayuda para Elegir?</h2>
            <p>Nuestro equipo te asesorará en la mejor estrategia de dominios para tu marca.</p>
            <a href="/contacto/" class="btn-primary">Contactar Equipo</a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
