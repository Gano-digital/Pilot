<?php
/**
 * Template Name: Política de Privacidad
 * @package gano-child
 */
get_header();
$fecha_actualizacion = '2026-04-06';
?>

<main id="gano-main-content" class="gano-trust-page gano-legal">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Legal</p>
      <h1>Política de Privacidad</h1>
      <p class="gano-trust-hero__sub">
        Última actualización: <?php echo esc_html($fecha_actualizacion); ?>.
        Ley 1581 de 2012 (Colombia) · GDPR aplicable.
      </p>
    </div>
  </section>

  <section class="gano-trust-section">
    <div class="gano-container gano-el-prose-narrow gano-legal__body">

      <section aria-labelledby="pp-responsable">
        <h2 id="pp-responsable">1. Responsable del tratamiento</h2>
        <ul>
          <li><strong>Organización:</strong> Gano Digital <!-- [NIT_PENDIENTE]: razón social completa --></li>
          <li><strong>NIT:</strong> [NIT_PENDIENTE]</li>
          <li><strong>Domicilio:</strong> Colombia <!-- [NIT_PENDIENTE]: dirección fiscal --></li>
          <li><strong>Correo de datos personales:</strong> <a href="mailto:legal@gano.digital">legal@gano.digital</a></li>
          <li><strong>Teléfono / WhatsApp:</strong> <a href="https://wa.me/573135646123">+57 313 564 6123</a></li>
        </ul>
      </section>

      <section aria-labelledby="pp-datos">
        <h2 id="pp-datos">2. Datos que se recopilan</h2>
        <ul>
          <li><strong>Datos de contacto:</strong> nombre, correo electrónico, empresa, teléfono (cuando el usuario los provee).</li>
          <li><strong>Datos de navegación:</strong> dirección IP, tipo de navegador, páginas visitadas, duración de sesión (logs del servidor).</li>
          <li><strong>Datos de pago:</strong> procesados directamente por el operador de checkout (GoDaddy Reseller). Gano Digital <strong>no almacena datos de tarjeta</strong>.</li>
          <li><strong>Cookies técnicas:</strong> sesión y seguridad. Ver sección 6.</li>
        </ul>
      </section>

      <section aria-labelledby="pp-finalidad">
        <h2 id="pp-finalidad">3. Finalidad del tratamiento</h2>
        <ul>
          <li>Prestación del servicio contratado (hosting, soporte, facturación).</li>
          <li>Respuesta a solicitudes de contacto y soporte técnico.</li>
          <li>Mejora del sitio web y análisis de uso (datos agregados y anonimizados).</li>
          <li>Cumplimiento de obligaciones legales (DIAN, SIC Colombia).</li>
        </ul>
      </section>

      <section aria-labelledby="pp-base-legal">
        <h2 id="pp-base-legal">4. Base legal del tratamiento</h2>
        <ul>
          <li><strong>Ejecución del contrato</strong> (Art. 6(1)(b) GDPR · Ley 1581/2012): para prestar los servicios contratados.</li>
          <li><strong>Interés legítimo</strong>: análisis de uso del sitio (estadísticas anonimizadas).</li>
          <li><strong>Cumplimiento de obligación legal</strong>: conservación de registros fiscales y contables.</li>
        </ul>
      </section>

      <section aria-labelledby="pp-transferencias">
        <h2 id="pp-transferencias">5. Transferencias internacionales</h2>
        <p>
          Los datos de alojamiento se procesan en infraestructura de GoDaddy, Inc. (Estados Unidos / Unión Europea).
          Gano Digital informa al usuario de esta circunstancia al momento de contratar.
          GoDaddy opera bajo las salvaguardas de transferencia internacional aplicables según normativa vigente.
        </p>
      </section>

      <section aria-labelledby="pp-cookies">
        <h2 id="pp-cookies">6. Cookies</h2>
        <ul>
          <li><strong>Cookies técnicas</strong> (sesión, seguridad): necesarias para el funcionamiento del sitio. No requieren consentimiento.</li>
          <li><strong>Cookies analíticas</strong>: <!-- [NIT_PENDIENTE]: ¿se usa GA, Matomo u otra herramienta con tracking? Si es así, añadir banner de cookies con opt-in. Si no se usa analítica con tracking, indicarlo aquí. --></li>
        </ul>
      </section>

      <section aria-labelledby="pp-derechos">
        <h2 id="pp-derechos">7. Derechos del titular</h2>
        <p>El titular de los datos puede ejercer los siguientes derechos escribiendo a <a href="mailto:legal@gano.digital">legal@gano.digital</a>:</p>
        <ul>
          <li><strong>Acceso:</strong> conocer qué datos se tratan sobre usted.</li>
          <li><strong>Rectificación:</strong> corregir datos inexactos o incompletos.</li>
          <li><strong>Supresión:</strong> solicitar la eliminación de sus datos cuando proceda.</li>
          <li><strong>Portabilidad:</strong> recibir sus datos en formato estructurado.</li>
          <li><strong>Oposición:</strong> oponerse al tratamiento en determinadas circunstancias.</li>
        </ul>
        <p>Respondemos dentro de <!-- [NIT_PENDIENTE]: X días hábiles según normativa SIC --> los plazos establecidos por la Ley 1581 de 2012.</p>
      </section>

      <section aria-labelledby="pp-retencion">
        <h2 id="pp-retencion">8. Retención de datos</h2>
        <p>
          Los datos se conservan durante la vigencia del contrato de servicio y,
          posteriormente, por <!-- [NIT_PENDIENTE]: X años --> el tiempo exigido por la normativa
          fiscal y contable colombiana aplicable.
        </p>
      </section>

      <hr style="margin:2rem 0; border-color:#e2e8f0;">
      <p class="gano-legal__footer-links">
        <a href="/legal/terminos-y-condiciones">Términos y Condiciones</a>
        &nbsp;·&nbsp;
        <a href="/contacto">Ejercer derechos: legal@gano.digital</a>
      </p>

    </div>
  </section>

</main>
<?php get_footer(); ?>
