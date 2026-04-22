<?php
/**
 * Template Name: Dashboard SOTA — Demo Componentes
 * Description: Demostración de componentes reutilizables: Icon, Button, Card, Pricing
 * FASE 1: MVP Dashboard — Validación de design system en acción
 *
 * @package gano-child
 * @since 1.2.0
 */

get_header();
?>

<main id="gano-main-content" class="gano-dashboard-demo">

  <!-- HERO SECTION -->
  <section class="gano-dark-section" style="padding: 4rem 2rem; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
      <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; color: white;">
        Dashboard SOTA — Componentes en acción
      </h1>
      <p style="font-size: 1.125rem; color: rgba(255,255,255,0.9); margin-bottom: 2rem;">
        Demostración interactiva del design system WordPress SOTA con Lucide icons, buttons, cards y pricing.
      </p>
    </div>
  </section>

  <!-- SECCIÓN 1: ICONOS LUCIDE -->
  <section style="padding: 3rem 2rem; background: #f8fafc;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem;">
        1. Iconos Lucide (12+ disponibles)
      </h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 2rem;">
        <?php
        $icons = ['check', 'server', 'cloud', 'shield', 'zap', 'settings', 'alert-circle', 'chevron-right', 'menu', 'search', 'loader'];
        foreach ($icons as $icon) :
          ?>
          <div style="text-align: center; padding: 1rem; background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="margin-bottom: 0.5rem;">
              <?php echo gano_icon(['name' => $icon, 'size' => 32, 'color' => '#1B4FD8']); ?>
            </div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0;"><?php echo esc_html($icon); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- SECCIÓN 2: BOTONES EN VARIANTES -->
  <section style="padding: 3rem 2rem; background: white;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem;">
        2. Botones — 5 Variantes + Sizes + Estados
      </h2>

      <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Variantes (tamaño md)</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
          <?php
          echo gano_button(['text' => 'Primary', 'href' => '#', 'variant' => 'primary']);
          echo gano_button(['text' => 'Secondary', 'href' => '#', 'variant' => 'secondary']);
          echo gano_button(['text' => 'Outline', 'href' => '#', 'variant' => 'outline']);
          echo gano_button(['text' => 'Ghost', 'href' => '#', 'variant' => 'ghost']);
          echo gano_button(['text' => 'Destructive', 'href' => '#', 'variant' => 'destructive']);
          ?>
        </div>
      </div>

      <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Sizes (variante Primary)</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
          <?php
          echo gano_button(['text' => 'Small', 'href' => '#', 'size' => 'sm', 'variant' => 'primary']);
          echo gano_button(['text' => 'Medium', 'href' => '#', 'size' => 'md', 'variant' => 'primary']);
          echo gano_button(['text' => 'Large', 'href' => '#', 'size' => 'lg', 'variant' => 'primary']);
          ?>
        </div>
      </div>

      <div>
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Con Iconos</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
          <?php
          echo gano_button(['text' => 'Con icono left', 'href' => '#', 'icon' => 'check', 'icon_pos' => 'left', 'variant' => 'primary']);
          echo gano_button(['text' => 'Con icono right', 'href' => '#', 'icon' => 'chevron-right', 'icon_pos' => 'right', 'variant' => 'secondary']);
          echo gano_button(['text' => 'Loading...', 'href' => '#', 'loading' => true, 'variant' => 'primary']);
          ?>
        </div>
      </div>
    </div>
  </section>

  <!-- SECCIÓN 3: CARDS GENÉRICAS -->
  <section style="padding: 3rem 2rem; background: #f8fafc;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem;">
        3. Cards — Variantes (default, elevated, outline, dark)
      </h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <?php
        $card_variants = [
          ['variant' => 'default', 'title' => 'Card Default', 'icon' => 'server'],
          ['variant' => 'elevated', 'title' => 'Card Elevated', 'icon' => 'cloud'],
          ['variant' => 'outline', 'title' => 'Card Outline', 'icon' => 'shield'],
          ['variant' => 'dark', 'title' => 'Card Dark', 'icon' => 'zap'],
        ];

        foreach ($card_variants as $card) :
          echo gano_card([
            'title'   => $card['title'],
            'icon'    => $card['icon'],
            'variant' => $card['variant'],
            'content' => sprintf(
              'Tipo de card <strong>%s</strong>. Con icono <strong>%s</strong> y contenido de demostración. ' .
              'Parfecto para organizar información en grid.',
              $card['variant'],
              $card['icon']
            ),
            'footer'  => 'Pie de card personalizado',
          ]);
        endforeach;
        ?>
      </div>
    </div>
  </section>

  <!-- SECCIÓN 4: PRICING CARDS (ECOSISTEMAS) -->
  <section style="padding: 3rem 2rem; background: white;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1rem;">
        4. Pricing Cards — Ecosistemas Gano Digital
      </h2>
      <p style="color: #64748b; margin-bottom: 2rem; font-size: 1.125rem;">
        Grid responsive de planes con featured state, características y CTAs.
      </p>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <?php
        $planes = [
          [
            'name'       => 'Núcleo Prime',
            'price'      => '$196.000',
            'period'     => '/mes',
            'description' => 'El punto de partida correcto para sitios en crecimiento.',
            'features'   => ['20 GB NVMe', 'WordPress preinstalado', 'Soporte en español', 'SSL incluido'],
            'cta_text'   => 'Elegir Núcleo Prime',
            'cta_url'    => '/ecosistemas/#nucleo-prime',
            'featured'   => false,
            'tag'        => '',
          ],
          [
            'name'       => 'Fortaleza Delta',
            'price'      => '$450.000',
            'period'     => '/mes',
            'description' => 'Para marcas que ya generan ingresos con hardening activo.',
            'features'   => ['40 GB NVMe', 'Hardening incluido', 'Chat priorizado', 'Monitoreo 24/7'],
            'cta_text'   => 'Activar Fortaleza Delta',
            'cta_url'    => '/ecosistemas/#fortaleza-delta',
            'featured'   => true,
            'tag'        => 'Más popular',
          ],
          [
            'name'       => 'Bastión SOTA',
            'price'      => '$890.000',
            'period'     => '/mes',
            'description' => 'Rendimiento crítico con seguridad de nivel empresarial.',
            'features'   => ['75 GB NVMe', 'SLA 99.9%', 'Agente IA', 'Soporte <2h'],
            'cta_text'   => 'Solicitar Bastión SOTA',
            'cta_url'    => '/ecosistemas/#bastion-sota',
            'featured'   => false,
            'tag'        => 'Recomendado',
          ],
          [
            'name'       => 'Ultimate WP',
            'price'      => '$1.200.000',
            'period'     => '/mes',
            'description' => 'Máxima capacidad para agencias y alto tráfico.',
            'features'   => ['100 GB NVMe', 'Multi-site', 'Canal dedicado', 'DR incluido'],
            'cta_text'   => 'Cotizar Ultimate WP',
            'cta_url'    => '/ecosistemas/#ultimate-wp',
            'featured'   => false,
            'tag'        => '',
          ],
        ];

        foreach ($planes as $plan) :
          echo gano_pricing_card([
            'name'        => $plan['name'],
            'price'       => $plan['price'],
            'period'      => $plan['period'],
            'description' => $plan['description'],
            'features'    => $plan['features'],
            'cta_text'    => $plan['cta_text'],
            'cta_url'     => $plan['cta_url'],
            'featured'    => $plan['featured'],
            'tag'         => $plan['tag'],
          ]);
        endforeach;
        ?>
      </div>
    </div>
  </section>

  <!-- SECCIÓN 5: GRID & LAYOUT PATTERNS -->
  <section style="padding: 3rem 2rem; background: #f8fafc;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem;">
        5. Grid & Layout — CSS Grid nativo (auto-fit + minmax)
      </h2>
      <p style="color: #64748b; margin-bottom: 2rem;">
        Los grids de esta página usan <code>display: grid; grid-template-columns: repeat(auto-fit, minmax(...))</code>
        para responsividad sin media queries. Redimensiona la ventana para ver el comportamiento.
      </p>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <?php
        for ($i = 1; $i <= 6; $i++) :
          ?>
          <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="font-size: 3rem; font-weight: 700; color: #1B4FD8; margin-bottom: 0.5rem;">
              <?php echo $i; ?>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; margin: 0;">
              Item de grid responsive #{<?php echo $i; ?>}
            </p>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- SECCIÓN 6: TOKENS & DESIGN SYSTEM -->
  <section style="padding: 3rem 2rem; background: white;">
    <div style="max-width: 1200px; margin: 0 auto;">
      <h2 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 1.5rem;">
        6. Design Tokens en acción
      </h2>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div>
          <h3 style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0.5rem;">Colores Primarios</h3>
          <div style="display: flex; gap: 0.5rem;">
            <div style="width: 60px; height: 60px; background: #1B4FD8; border-radius: 4px;" title="Gano Blue"></div>
            <div style="width: 60px; height: 60px; background: #00C26B; border-radius: 4px;" title="Gano Green"></div>
            <div style="width: 60px; height: 60px; background: #FF6B35; border-radius: 4px;" title="Gano Orange"></div>
          </div>
        </div>

        <div>
          <h3 style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0.5rem;">Spacing Scale (8px)</h3>
          <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="width: 8px; height: 32px; background: #1B4FD8; border-radius: 2px;" title="8px"></div>
            <div style="width: 16px; height: 48px; background: #1B4FD8; border-radius: 2px;" title="16px"></div>
            <div style="width: 24px; height: 64px; background: #1B4FD8; border-radius: 2px;" title="24px"></div>
            <div style="width: 32px; height: 80px; background: #1B4FD8; border-radius: 2px;" title="32px"></div>
          </div>
        </div>

        <div>
          <h3 style="font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 0.5rem;">Border Radius</h3>
          <div style="display: flex; gap: 0.5rem;">
            <div style="width: 50px; height: 50px; background: #1B4FD8; border-radius: 4px;" title="4px"></div>
            <div style="width: 50px; height: 50px; background: #1B4FD8; border-radius: 8px;" title="8px"></div>
            <div style="width: 50px; height: 50px; background: #1B4FD8; border-radius: 12px;" title="12px"></div>
          </div>
        </div>
      </div>

      <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #1B4FD8;">
        <h3 style="font-size: 1rem; font-weight: 600; margin-top: 0;">Variables CSS disponibles</h3>
        <code style="display: block; white-space: pre-wrap; font-size: 0.875rem; color: #333;">
--gano-blue: #1B4FD8
--gano-green: #00C26B
--gano-orange: #FF6B35
--gano-dark: #05080b
--gano-bg-dark: #0F1923
--gano-radius-md: 8px
--gano-shadow: 0 2px 8px rgba(0,0,0,0.1)
        </code>
      </div>
    </div>
  </section>

  <!-- FOOTER DEMO -->
  <section style="padding: 3rem 2rem; background: #05080b; text-align: center; color: rgba(255,255,255,0.8);">
    <div style="max-width: 800px; margin: 0 auto;">
      <h2 style="color: white; margin-bottom: 1rem;">
        ¿Listo para integrar estos componentes?
      </h2>
      <p style="margin-bottom: 1.5rem; font-size: 1.125rem;">
        Los componentes están listos para usar en cualquier template WordPress.
      </p>
      <?php
      echo gano_button([
        'text' => 'Ver documentación en componentes/',
        'href' => 'https://github.com/Gano-digital/Pilot/tree/claude/serene-mcnulty/wp-content/themes/gano-child/components',
        'variant' => 'primary',
      ]);
      ?>
    </div>
  </section>

</main>

<?php get_footer(); ?>
