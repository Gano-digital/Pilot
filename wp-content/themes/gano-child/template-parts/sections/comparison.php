<?php
/**
 * Competitive Comparison Section
 * Gano vs GoDaddy, Bluehost, Kinsta
 *
 * @package Gano_Digital
 */

if ( ! function_exists( 'gano_get_comparison_data' ) ) {
	return;
}

$comparison_data = gano_get_comparison_data();
?>

<div class="container comparison">
	<h2><?php esc_html_e( 'Por qué elegir Gano Digital', 'gano-child' ); ?></h2>
	<p class="subtitle"><?php esc_html_e( 'Comparación clara: soberanía digital vs el resto del mercado', 'gano-child' ); ?></p>

	<div class="table-responsive">
		<table class="comparison-table">
			<thead>
				<tr>
					<?php foreach ( $comparison_data['headers'] as $header ) : ?>
						<th><?php echo esc_html( $header ); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $comparison_data['rows'] as $row ) : ?>
					<tr>
						<td class="feature-name"><strong><?php echo esc_html( $row['feature'] ); ?></strong></td>
						<td class="gano">
							<strong><?php echo wp_kses_post( $row['gano'] ); ?></strong>
						</td>
						<td><?php echo wp_kses_post( $row['godaddy'] ); ?></td>
						<td><?php echo wp_kses_post( $row['bluehost'] ); ?></td>
						<td><?php echo wp_kses_post( $row['kinsta'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<p class="comparison-note">
		<strong><?php esc_html_e( 'Nota:', 'gano-child' ); ?></strong>
		<?php esc_html_e( 'Gano es posicionamiento ultra-premium. Nuestro modelo está diseñado para empresas que necesitan soberanía de datos, cumplimiento normativo LATAM y protección post-cuántica. No competimos en precio; competimos en diferenciación.', 'gano-child' ); ?>
	</p>

</div>

