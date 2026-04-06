<?php
/**
 * Template Name: Acuerdo de Nivel de Servicio (SLA)
 * @package gano-child
 */
get_header();
$fecha_actualizacion = '2026-04-06';
?>

<main id="gano-main-content" class="gano-trust-page gano-legal">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Legal</p>
      <h1>Acuerdo de Nivel de Servicio</h1>
      <p class="gano-trust-hero__sub">
        Última actualización: <?php echo esc_html($fecha_actualizacion); ?>.
        Compromisos de disponibilidad, soporte e incidentes.
      </p>
    </div>
  </section>

  <section class="gano-trust-section">
    <div class="gano-container gano-el-prose-narrow gano-legal__body">

      <div class="gano-trust-disclaimer">
        <p>
          <strong>Aviso importante.</strong> Los compromisos de disponibilidad de Gano Digital están
          condicionados a los niveles de servicio de GoDaddy como proveedor de infraestructura.
          Gano Digital actúa como operador autorizado y no puede garantizar disponibilidad
          superior a la que GoDaddy garantiza en su propio SLA.
          <a href="https://www.godaddy.com/es/legal" target="_blank" rel="noopener">Ver términos GoDaddy →</a>
        </p>
      </div>

      <section aria-labelledby="sla-disponibilidad">
        <h2 id="sla-disponibilidad">1. Objetivo de disponibilidad</h2>
        <p>
          <!-- [NIT_PENDIENTE]: Confirmar % real con GoDaddy para el plan contratado antes de publicar.
               No usar "99,9%" sin respaldo escrito. -->
          Gano Digital persigue mantener el servicio disponible de acuerdo con el plan contratado.
          El porcentaje de disponibilidad comprometido se especifica en el contrato individual de servicio.
        </p>
        <p>
          El cómputo de disponibilidad excluye: mantenimientos programados comunicados con antelación,
          incidentes causados por la infraestructura de GoDaddy fuera del control de Gano Digital,
          y modificaciones del cliente que generen la incidencia.
        </p>
      </section>

      <section aria-labelledby="sla-mantenimiento">
        <h2 id="sla-mantenimiento">2. Ventana de mantenimiento</h2>
        <p>
          <!-- [NIT_PENDIENTE]: Definir ventana real. Ej: domingos 02:00–04:00 hora Colombia. -->
          Los mantenimientos planificados se comunicarán con al menos 48 horas de antelación
          por correo electrónico al titular de la cuenta.
        </p>
      </section>

      <section aria-labelledby="sla-incidentes">
        <h2 id="sla-incidentes">3. Clasificación y tiempos de respuesta a incidentes</h2>
        <table class="gano-sla-table">
          <thead>
            <tr>
              <th scope="col">Severidad</th>
              <th scope="col">Descripción</th>
              <th scope="col">Tiempo de respuesta</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Crítico</strong></td>
              <td>Sitio completamente caído; sin acceso a wp-admin</td>
              <td><!-- [NIT_PENDIENTE] --> Por confirmar</td>
            </tr>
            <tr>
              <td><strong>Alto</strong></td>
              <td>Degradación severa del rendimiento o funcionalidades clave afectadas</td>
              <td><!-- [NIT_PENDIENTE] --> Por confirmar</td>
            </tr>
            <tr>
              <td><strong>Medio</strong></td>
              <td>Funcionalidad parcial; workaround disponible</td>
              <td><!-- [NIT_PENDIENTE] --> Por confirmar</td>
            </tr>
            <tr>
              <td><strong>Bajo</strong></td>
              <td>Consultas, mejoras, solicitudes no urgentes</td>
              <td><!-- [NIT_PENDIENTE] --> Por confirmar</td>
            </tr>
          </tbody>
        </table>
        <p style="font-size:.875rem; color:#64748b; margin-top:.75rem;">
          * Los tiempos de respuesta serán confirmados y publicados antes de la activación comercial del plan.
        </p>
      </section>

      <section aria-labelledby="sla-reporte">
        <h2 id="sla-reporte">4. Canal de reporte de incidentes</h2>
        <ul>
          <li>Correo: <a href="mailto:soporte@gano.digital">soporte@gano.digital</a></li>
          <li>WhatsApp: <a href="https://wa.me/573135646123">+57 313 564 6123</a></li>
        </ul>
        <p>Indicar siempre: dominio afectado, descripción del problema y hora del primer síntoma.</p>
      </section>

      <section aria-labelledby="sla-exclusiones">
        <h2 id="sla-exclusiones">5. Exclusiones</h2>
        <ul>
          <li>Ataques DDoS que superen la capacidad de mitigación del proveedor de infraestructura.</li>
          <li>Fallos de infraestructura de GoDaddy fuera del control de Gano Digital.</li>
          <li>Modificaciones realizadas por el cliente en su instalación WordPress que generen la incidencia.</li>
          <li>Mantenimientos programados comunicados con antelación.</li>
          <li>Eventos de fuerza mayor.</li>
        </ul>
      </section>

      <section aria-labelledby="sla-compensacion">
        <h2 id="sla-compensacion">6. Compensación por incumplimiento</h2>
        <p>
          <!-- [NIT_PENDIENTE]: Definir política de compensación antes de publicar.
               Ej: crédito de servicio proporcional al tiempo de caída; no aplica a incidentes GoDaddy. -->
          La política de compensación por incumplimiento del SLA se especificará en el contrato
          individual de servicio antes de la activación comercial del plan.
        </p>
      </section>

      <hr style="margin:2rem 0; border-color:#e2e8f0;">
      <p class="gano-legal__footer-links">
        <a href="/legal/terminos-y-condiciones">Términos y Condiciones</a>
        &nbsp;·&nbsp;
        <a href="/contacto">Reportar incidente</a>
      </p>

    </div>
  </section>

</main>

<style>
.gano-sla-table { width: 100%; border-collapse: collapse; font-size: .875rem; margin-top: 1rem; }
.gano-sla-table th, .gano-sla-table td { padding: .75rem 1rem; border-bottom: 1px solid #e2e8f0; text-align: left; }
.gano-sla-table thead th { background: #f8fafc; font-weight: 600; }
.gano-legal__body h2 { font-size: var(--gano-fs-xl, 1.25rem); margin: 2rem 0 .75rem; }
.gano-legal__body p, .gano-legal__body li { line-height: 1.75; }
.gano-legal__body ul { padding-left: 1.25rem; }
.gano-legal__footer-links { font-size: .875rem; color: #64748b; }
.gano-legal__footer-links a { color: var(--gano-blue, #2952CC); }
</style>

<?php get_footer(); ?>
