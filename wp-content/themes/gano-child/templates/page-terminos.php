<?php
/**
 * Template Name: Términos y Condiciones
 * @package gano-child
 */
get_header();
$fecha_actualizacion = '2026-04-06'; // Actualizar antes de publicar
?>

<main id="gano-main-content" class="gano-trust-page gano-legal">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Legal</p>
      <h1>Términos y Condiciones</h1>
      <p class="gano-trust-hero__sub">
        Última actualización: <?php echo esc_html($fecha_actualizacion); ?>.
        Versión en español Colombia. Ley aplicable: República de Colombia.
      </p>
    </div>
  </section>

  <section class="gano-trust-section">
    <div class="gano-container gano-el-prose-narrow gano-legal__body">

      <section aria-labelledby="tc-identificacion">
        <h2 id="tc-identificacion">1. Identificación del prestador</h2>
        <ul>
          <li><strong>Razón social:</strong> <!-- [NIT_PENDIENTE]: Razón social exacta en Cámara de Comercio --> Gano Digital</li>
          <li><strong>NIT:</strong> <!-- [NIT_PENDIENTE] --> [NIT_PENDIENTE]-[dígito de verificación]</li>
          <li><strong>Domicilio:</strong> <!-- [NIT_PENDIENTE]: Dirección fiscal Colombia --> Colombia</li>
          <li><strong>Correo legal:</strong> <a href="mailto:legal@gano.digital">legal@gano.digital</a></li>
          <li><strong>Teléfono / WhatsApp:</strong> <a href="https://wa.me/573135646123">+57 313 564 6123</a></li>
        </ul>
      </section>

      <section aria-labelledby="tc-objeto">
        <h2 id="tc-objeto">2. Objeto del servicio</h2>
        <p>
          Gano Digital provee ecosistemas de hosting WordPress administrado —bajo las marcas
          Núcleo Prime, Fortaleza Delta, Bastión SOTA y Ultimate WP— sobre infraestructura
          aprovisionada a través del programa de reseller de GoDaddy, Inc.
        </p>
        <div class="gano-trust-disclaimer">
          <p>
            <strong>Transparencia de infraestructura.</strong> Los servicios de Gano Digital son provistos
            sobre infraestructura operada por GoDaddy, Inc. a través de su programa de reseller.
            El usuario reconoce que las condiciones técnicas de la infraestructura subyacente están
            sujetas a los términos de servicio de GoDaddy, disponibles en
            <a href="https://www.godaddy.com/es/legal" target="_blank" rel="noopener">godaddy.com/es/legal</a>.
          </p>
        </div>
      </section>

      <section aria-labelledby="tc-precios">
        <h2 id="tc-precios">3. Precios y facturación</h2>
        <ul>
          <li>Los precios se expresan en pesos colombianos (COP) e incluyen IVA, salvo indicación expresa.</li>
          <li>Pueden variar por fluctuación del tipo de cambio USD/COP aplicado al margen del reseller.</li>
          <li>Facturación electrónica según normativa DIAN <!-- [NIT_PENDIENTE]: confirmar obligatoriedad según volumen -->.</li>
          <li>Los precios publicados son indicativos; el valor definitivo se confirma en el carrito de pago.</li>
        </ul>
      </section>

      <section aria-labelledby="tc-disponibilidad">
        <h2 id="tc-disponibilidad">4. Disponibilidad del servicio</h2>
        <p>
          Gano Digital persigue un objetivo de disponibilidad acorde al plan contratado.
          <!-- [NIT_PENDIENTE]: No publicar porcentaje concreto sin SLA escrito de GoDaddy para el plan.
               Sustituir esta nota con el % real antes de publicar. -->
          Los compromisos de disponibilidad están condicionados a los niveles de servicio de GoDaddy
          como proveedor de infraestructura. Ver
          <a href="/legal/acuerdo-de-nivel-de-servicio">Acuerdo de Nivel de Servicio</a>.
        </p>
      </section>

      <section aria-labelledby="tc-soporte">
        <h2 id="tc-soporte">5. Soporte técnico</h2>
        <ul>
          <li><strong>Canal principal:</strong> <a href="mailto:soporte@gano.digital">soporte@gano.digital</a></li>
          <li><strong>WhatsApp:</strong> <a href="https://wa.me/573135646123">+57 313 564 6123</a></li>
          <li><!-- [NIT_PENDIENTE]: Definir horario y tiempo de respuesta comprometido por plan --></li>
        </ul>
      </section>

      <section aria-labelledby="tc-cancelacion">
        <h2 id="tc-cancelacion">6. Cancelación y portabilidad</h2>
        <ul>
          <li>El usuario puede cancelar su servicio en cualquier momento contactando a <a href="mailto:soporte@gano.digital">soporte@gano.digital</a>.</li>
          <li>Gano Digital facilita la exportación de datos y contenido WordPress antes de la cancelación efectiva.</li>
          <li>Política de reembolsos: <!-- [NIT_PENDIENTE]: definir política antes de publicar -->.</li>
          <li>Los dominios registrados no son reembolsables tras la activación.</li>
        </ul>
      </section>

      <section aria-labelledby="tc-responsabilidad">
        <h2 id="tc-responsabilidad">7. Limitación de responsabilidad</h2>
        <p>
          Gano Digital no responde por caídas o degradaciones causadas por la infraestructura de GoDaddy
          más allá de lo que GoDaddy cubre en su propio SLA. La responsabilidad máxima de Gano Digital
          frente al usuario se limita al valor del servicio mensual contratado.
        </p>
      </section>

      <section aria-labelledby="tc-ley">
        <h2 id="tc-ley">8. Ley aplicable y jurisdicción</h2>
        <p>
          Estos Términos se rigen por las leyes de la República de Colombia.
          Para cualquier controversia, las partes se someten a la jurisdicción de los jueces
          y tribunales de Bogotá D.C., Colombia.
        </p>
      </section>

      <hr style="margin:2rem 0; border-color:#e2e8f0;">
      <p class="gano-legal__footer-links">
        <a href="/legal/politica-de-privacidad">Política de Privacidad</a>
        &nbsp;·&nbsp;
        <a href="/legal/acuerdo-de-nivel-de-servicio">Acuerdo de Nivel de Servicio</a>
        &nbsp;·&nbsp;
        <a href="/contacto">Preguntas: contáctanos</a>
      </p>

    </div>
  </section>

</main>
<?php get_footer(); ?>
