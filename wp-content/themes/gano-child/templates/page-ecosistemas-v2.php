<?php
/**
 * Template Name: Ecosistemas V2 — Planes Gano
 *
 * Página de precios reconstruida. CSS inline autocontenido.
 * Diseño inspirado en GoDaddy Pricing — tarjetas limpias, comparación, bundles.
 *
 * @package gano-child
 */

get_header();

// Definición de planes
$planes = [
  [
    'id'        => 'wordpress-basic',
    'nombre'    => 'WordPress Básico',
    'precio'    => '$196.000',
    'precio_anual' => '$1.960.000',
    'ahorro'    => 'Ahorra $392.000',
    'periodo'   => '/mes',
    'tag'       => '',
    'featured'  => false,
    'color'     => '#1B4FD8',
    'colorBg'   => '#E8EEFB',
    'icon'      => 'M4 6h16M4 12h16M4 18h7',
    'desc'      => 'Ideal para proyectos personales, blogs y sitios corporativos pequeños que recién empiezan.',
    'features'  => [
      '20 GB almacenamiento NVMe',
      'WordPress preinstalado',
      'SSL Let\'s Encrypt incluido',
      'Backups diarios · 30 días',
      'CDN global',
      'Soporte ticket < 8h',
    ],
    'cta'       => 'Elegir plan',
    'link'      => '/producto/wordpress-basic/',
    'pfid'      => 'GANO_PFID_HOSTING_ECONOMIA',
  ],
  [
    'id'        => 'wordpress-deluxe',
    'nombre'    => 'WordPress Deluxe',
    'precio'    => '$450.000',
    'precio_anual' => '$4.500.000',
    'ahorro'    => 'Ahorra $900.000',
    'periodo'   => '/mes',
    'tag'       => 'Más popular',
    'featured'  => false,
    'color'     => '#00C26B',
    'colorBg'   => '#E0FAF0',
    'icon'      => 'M13 10V3L4 14h7v7l9-11h-7z',
    'desc'      => 'Perfecto para negocios en crecimiento, tiendas WooCommerce y sitios con tráfico moderado.',
    'features'  => [
      '40 GB NVMe · doble recursos',
      'Hardening WordPress activo',
      'Backups diarios + on-demand',
      'Soporte priorizado < 4h',
      'Monitoreo uptime',
      'WooCommerce optimizado',
    ],
    'cta'       => 'Activar ahora',
    'link'      => '/producto/wordpress-deluxe/',
    'pfid'      => 'GANO_PFID_HOSTING_DELUXE',
  ],
  [
    'id'        => 'wordpress-ultimate',
    'nombre'    => 'WordPress Ultimate',
    'precio'    => '$890.000',
    'precio_anual' => '$8.900.000',
    'ahorro'    => 'Ahorra $1.780.000',
    'periodo'   => '/mes',
    'tag'       => 'Recomendado',
    'featured'  => true,
    'color'     => '#D4AF37',
    'colorBg'   => '#FFF8E1',
    'icon'      => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    'desc'      => 'Rendimiento empresarial Gen4 con seguridad de nivel militar. Para comercio que no puede fallar.',
    'features'  => [
      '75 GB NVMe · recursos dedicados',
      'SLA ≥ 99.9% disponibilidad',
      'Monitoreo proactivo 24/7',
      'Hardening avanzado + auditoría',
      'Agente IA administración',
      'Soporte dedicado < 2h',
    ],
    'cta'       => 'Solicitar Ultimate',
    'link'      => '/producto/wordpress-ultimate/',
    'pfid'      => 'GANO_PFID_HOSTING_PREMIUM',
  ],
  [
    'id'        => 'cpanel-ultimate',
    'nombre'    => 'cPanel Ultimate',
    'precio'    => '$1.200.000',
    'precio_anual' => '$12.000.000',
    'ahorro'    => 'Ahorra $2.400.000',
    'periodo'   => '/mes',
    'tag'       => 'Para agencias',
    'featured'  => false,
    'color'     => '#7C3AED',
    'colorBg'   => '#F3E8FF',
    'icon'      => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    'desc'      => 'Máxima capacidad para agencias, portafolios masivos y plataformas de alto tráfico.',
    'features'  => [
      '100 GB NVMe · máximos recursos',
      'Multi-site ilimitado',
      'Soporte dedicado < 1h 24/7',
      'Backups continuos + DR',
      'Escalado automático',
      'Onboarding técnico incluido',
    ],
    'cta'       => 'Cotizar ahora',
    'link'      => '/producto/cpanel-ultimate/',
    'pfid'      => 'GANO_PFID_HOSTING_ULTIMATE',
  ],
];

// Bundles
$bundles = [
  [
    'nombre' => 'Starter Pack',
    'desc'   => 'Dominio .com + WordPress Básico + SSL',
    'precio' => '$245.000',
    'ahorro' => 'Ahorra 15%',
    'icon'   => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
    'cta'    => 'Armar bundle',
    'link'   => '/contacto/',
  ],
  [
    'nombre' => 'Business Pro',
    'desc'   => 'Dominio + WordPress Deluxe + Email + SSL',
    'precio' => '$520.000',
    'ahorro' => 'Ahorra 20%',
    'icon'   => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'cta'    => 'Armar bundle',
    'link'   => '/contacto/',
  ],
  [
    'nombre' => 'Enterprise Suite',
    'desc'   => 'Dominio + WordPress Ultimate + Email + Seguridad',
    'precio' => '$1.150.000',
    'ahorro' => 'Ahorra 25%',
    'icon'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'cta'    => 'Armar bundle',
    'link'   => '/contacto/',
  ],
];

// Tabla de comparación
$compare_rows = [
  ['Almacenamiento NVMe',         '20 GB',       '40 GB',        '75 GB',         '100 GB'],
  ['Recursos de cómputo',         'Base',        'Doble',        'Dedicados',     'Máximos'],
  ['Hardening WordPress',         'Básico',      'Activo',       'Avanzado',      'Avanzado'],
  ['Soporte español',             'Ticket < 8h', 'Priorit < 4h', 'Dedicado < 2h', 'Priorit < 1h'],
  ['Agente IA',                   '—',           '—',            '✓',             '✓'],
  ['SLA disponibilidad',          'Estándar',    'Estándar',     '≥ 99.9%',       '≥ 99.9%'],
  ['Backups',                     'Diarios · 30','Diarios · 30', 'Diarios · 60',  'Continuos · 90'],
  ['Monitoreo',                   '—',           'Uptime',       'Proactivo',     'Escalado auto'],
  ['CDN incluido',                '✓',           '✓',            '✓',             '✓'],
  ['Staging',                     '—',           '—',            '✓',             '✓'],
  ['Multi-site',                  '—',           '—',            '—',             '✓'],
];

// FAQ
$faqs = [
  ['¿Puedo cambiar de plan después?', 'Sí. Puedes migrar a un plan superior en cualquier momento. El cambio se procesa en menos de 24 horas hábiles y mantienes todos tus datos intactos.'],
  ['¿Qué significa "facturación en COP"?', 'Tu tarjeta o transferencia se debita en pesos colombianos. No hay conversiones de divisa ni sorpresas de tipo de cambio al final del mes.'],
  ['¿El dominio está incluido?', 'Los planes de hosting no incluyen dominio por defecto. Revisa nuestros Bundles que combinan dominio + hosting + SSL con descuento.'],
  ['¿Qué es el Agente IA?', 'Es un asistente inteligente integrado en WordPress Ultimate y cPanel Ultimate que monitorea tu sitio, detecta anomalías y sugiere optimizaciones automáticamente.'],
  ['¿Tienen garantía de reembolso?', 'Sí. Todos los planes incluyen 30 días de garantía. Si no estás satisfecho, te devolvemos el 100% sin preguntas.'],
];

?>

<style>
<?php include get_stylesheet_directory() . '/css/ecosistemas-v2.css'; ?>
</style>

<main class="gano-eco-v2">

  <!-- ════════════════════════════════════════════════════════════════
       HERO
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-hero">
    <div class="gano-eco-hero__bg">
      <div class="gano-eco-hero__orb gano-eco-hero__orb--1"></div>
      <div class="gano-eco-hero__orb gano-eco-hero__orb--2"></div>
      <div class="gano-eco-hero__orb gano-eco-hero__orb--3"></div>
    </div>
    <div class="gano-eco__container">
      <span class="gano-eco-hero__label">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Planes de Hosting Gano Digital
      </span>
      <h1>Infraestructura que <span>crece contigo</span></h1>
      <p class="gano-eco-hero__lead">
        Cuatro planes construidos sobre el programa Reseller de GoDaddy, operados en español
        y facturados en pesos colombianos. Sin contratos de permanencia. Sin sorpresas.
      </p>

      <div class="gano-eco-toggle" id="billing-toggle">
        <button type="button" class="gano-eco-toggle__btn active" data-period="monthly">Mensual</button>
        <button type="button" class="gano-eco-toggle__btn" data-period="annual">Anual <span class="gano-eco-toggle__save">-20%</span></button>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       PLAN CARDS
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-plans">
    <div class="gano-eco__container">
      <div class="gano-eco-plans__grid">

        <?php foreach ($planes as $plan) :
          $pfid = defined($plan['pfid']) ? constant($plan['pfid']) : 'PENDING_RCC';
          $cart_url = ($pfid !== 'PENDING_RCC' && function_exists('gano_rstore_cart_url'))
            ? gano_rstore_cart_url($pfid) : '#';
          $is_pending = ($pfid === 'PENDING_RCC');
        ?>
        <article class="gano-eco-card <?php echo $plan['featured'] ? 'gano-eco-card--featured' : ''; ?> eco-reveal"
                 id="<?php echo esc_attr($plan['id']); ?>">
          <?php if ($plan['tag']) : ?>
            <span class="gano-eco-card__badge"><?php echo esc_html($plan['tag']); ?></span>
          <?php endif; ?>

          <div class="gano-eco-card__illust gano-eco-card__illust--<?php echo $plan['id'] === 'wordpress-basic' ? 'basic' : ($plan['id'] === 'wordpress-deluxe' ? 'deluxe' : ($plan['id'] === 'wordpress-ultimate' ? 'premium' : 'ultimate')); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="<?php echo esc_attr($plan['color']); ?>" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr($plan['icon']); ?>"/>
            </svg>
          </div>

          <h3><?php echo esc_html($plan['nombre']); ?></h3>
          <p class="gano-eco-card__desc"><?php echo esc_html($plan['desc']); ?></p>

          <div class="gano-eco-card__price">
            <div class="gano-eco-card__price-value" data-monthly="<?php echo esc_attr($plan['precio']); ?>" data-annual="<?php echo esc_attr($plan['precio_anual']); ?>">
              <?php echo esc_html($plan['precio']); ?>
            </div>
            <div class="gano-eco-card__price-period"><?php echo esc_html($plan['periodo']); ?> · IVA incluido</div>
            <div class="gano-eco-card__price-note" data-ahorro="<?php echo esc_attr($plan['ahorro']); ?>" style="opacity:0;height:0;overflow:hidden;transition:all .3s ease;"><?php echo esc_html($plan['ahorro']); ?> en anual</div>
          </div>

          <ul class="gano-eco-card__features">
            <?php foreach ($plan['features'] as $f) : ?>
            <li>
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              <?php echo esc_html($f); ?>
            </li>
            <?php endforeach; ?>
          </ul>

          <div class="gano-eco-card__cta">
            <?php if ($is_pending) : ?>
              <a href="/contacto/" class="gano-eco-btn gano-eco-btn--primary"><?php echo esc_html($plan['cta']); ?></a>
              <small style="color:#9CA3AF;font-size:0.75rem;text-align:center;">Carrito en configuración · Contacta para activar</small>
            <?php else : ?>
              <a href="<?php echo esc_url($cart_url); ?>" class="gano-eco-btn gano-eco-btn--primary" target="_blank" rel="noopener">
                <?php echo esc_html($plan['cta']); ?>
              </a>
            <?php endif; ?>
            <a href="<?php echo esc_url($plan['link']); ?>" class="gano-eco-btn gano-eco-btn--ghost">Ver detalles del plan →</a>
          </div>
        </article>
        <?php endforeach; ?>

      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       BUNDLES
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-bundles">
    <div class="gano-eco__container">
      <div class="gano-eco-bundles__header eco-reveal">
        <h2>Bundles recomendados</h2>
        <p>Combina dominio, hosting y seguridad con descuentos exclusivos. Un solo pago, todo incluido.</p>
      </div>
      <div class="gano-eco-bundles__grid">
        <?php foreach ($bundles as $b) : ?>
        <div class="gano-eco-bundle eco-reveal">
          <div class="gano-eco-bundle__icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="#1B4FD8" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr($b['icon']); ?>"/></svg>
          </div>
          <h4><?php echo esc_html($b['nombre']); ?></h4>
          <p><?php echo esc_html($b['desc']); ?></p>
          <div class="gano-eco-bundle__price"><?php echo esc_html($b['precio']); ?> <small>/mes</small></div>
          <span class="gano-eco-bundle__save"><?php echo esc_html($b['ahorro']); ?></span>
          <a href="<?php echo esc_url($b['link']); ?>" class="gano-eco-btn gano-eco-btn--secondary" style="width:100%;"><?php echo esc_html($b['cta']); ?></a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       COMPARISON TABLE
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-compare">
    <div class="gano-eco__container">
      <div class="gano-eco-compare__header eco-reveal">
        <h2>Comparación de planes</h2>
        <p>Revisa lado a lado lo que incluye cada plan.</p>
      </div>
      <div class="gano-eco-table-wrap eco-reveal">
        <table class="gano-eco-table">
          <thead>
            <tr>
              <th>Característica</th>
              <th>WordPress Básico</th>
              <th>WordPress Deluxe</th>
              <th>WordPress Ultimate</th>
              <th>cPanel Ultimate</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($compare_rows as $row) : ?>
            <tr>
              <th><?php echo esc_html($row[0]); ?></th>
              <?php for ($i = 1; $i <= 4; $i++) : ?>
              <td>
                <?php if ($row[$i] === '✓') : ?>
                  <span class="check"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                <?php elseif ($row[$i] === '—') : ?>
                  <span class="dash">—</span>
                <?php else : ?>
                  <span class="highlight"><?php echo esc_html($row[$i]); ?></span>
                <?php endif; ?>
              </td>
              <?php endfor; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       TRUST SIGNALS
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-trust">
    <div class="gano-eco__container">
      <h2 class="eco-reveal">Por qué confían en Gano Digital</h2>
      <div class="gano-eco-trust__grid">
        <div class="gano-eco-trust__item eco-reveal">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          <h4>Infraestructura GoDaddy</h4>
          <p>Programa reseller autorizado con estándares globales de datacenter.</p>
        </div>
        <div class="gano-eco-trust__item eco-reveal">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <h4>99.9% Disponibilidad</h4>
          <p>SLA comprometido con monitoreo proactivo incluido.</p>
        </div>
        <div class="gano-eco-trust__item eco-reveal">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <h4>Facturación en COP</h4>
          <p>Pesos colombianos. Sin sorpresas de tipo de cambio.</p>
        </div>
        <div class="gano-eco-trust__item eco-reveal">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 9v4m0 0v4m0-4h4m-4 0H8"/></svg>
          <h4>Soporte en español</h4>
          <p>Primera respuesta en horas, no en días. En tu idioma.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       FAQ
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-faq">
    <div class="gano-eco__container">
      <div class="gano-eco-faq__header eco-reveal">
        <h2>Preguntas frecuentes</h2>
      </div>
      <div class="gano-eco-faq__list">
        <?php foreach ($faqs as $i => $faq) : ?>
        <div class="gano-eco-faq__item <?php echo $i === 0 ? 'open' : ''; ?> eco-reveal">
          <button type="button" class="gano-eco-faq__q" onclick="this.parentElement.classList.toggle('open')">
            <?php echo esc_html($faq[0]); ?>
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="gano-eco-faq__a"><p><?php echo esc_html($faq[1]); ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ════════════════════════════════════════════════════════════════
       FINAL CTA
       ════════════════════════════════════════════════════════════════ -->
  <section class="gano-eco-final">
    <div class="gano-eco__container">
      <h2 class="eco-reveal">¿No sabes cuál elegir?</h2>
      <p class="eco-reveal">Habla con nuestro equipo. Te ayudamos a identificar el plan ideal para tu proyecto sin compromiso.</p>
      <div class="gano-eco-final__btns eco-reveal">
        <a href="/contacto/" class="gano-eco-btn gano-eco-btn--primary">Hablar con un asesor</a>
        <a href="/dominios/" class="gano-eco-btn gano-eco-btn--secondary">Buscar un dominio</a>
      </div>
    </div>
  </section>

</main>

<script>
(function() {
  'use strict';

  // ── Scroll reveal (intersection observer) ─────────────────────
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

  // ── Billing toggle (mensual / anual) ──────────────────────────
  const toggleWrap = document.getElementById('billing-toggle');
  if (toggleWrap) {
    const btns = toggleWrap.querySelectorAll('[data-period]');
    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        const period = btn.dataset.period;
        btns.forEach(b => b.classList.toggle('active', b === btn));

        document.querySelectorAll('.gano-eco-card__price-value').forEach(el => {
          el.textContent = period === 'annual' ? el.dataset.annual : el.dataset.monthly;
        });
        document.querySelectorAll('.gano-eco-card__price-note').forEach(el => {
          if (period === 'annual') {
            el.style.opacity = '1'; el.style.height = 'auto'; el.style.marginTop = '6px';
          } else {
            el.style.opacity = '0'; el.style.height = '0'; el.style.marginTop = '0';
          }
        });
      });
    });
  }
})();
</script>

<?php get_footer(); ?>
