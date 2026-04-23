<?php
/**
 * HUD flotante (sitewide) — CRT scanlines + glitch postpunk.
 *
 * Render: wp_footer
 * Assets: gano-hud-status.css + gano-hud-status.js
 */

/**
 * Imprime el markup del HUD en el footer.
 */
function gano_render_status_hud(): void {
	?>
	<div class="gano-hud" data-gano-hud data-state="ok">
		<div class="gano-hud__bar" data-gano-hud-bar role="status" aria-live="off" tabindex="0" aria-label="Estado de operación">
			<span class="gano-hud__sr" data-gano-hud-sr aria-live="polite">Estado estable</span>
			<div class="gano-hud__inner">
				<div class="gano-hud__line" aria-hidden="true">
					<span class="gano-hud__token gano-hud__token--fps">FPS&nbsp;<span class="gano-hud__val" data-gano-hud-fps data-t="--">--</span></span>
					<span class="gano-hud__sep gano-hud__sep--a">|</span>
					<span class="gano-hud__token gano-hud__token--dcl">DCL&nbsp;<span class="gano-hud__val" data-gano-hud-dcl data-t="---">---</span></span>
					<span class="gano-hud__sep gano-hud__sep--b">|</span>
					<span class="gano-hud__token gano-hud__token--ping">PING&nbsp;<span class="gano-hud__val" data-gano-hud-ping data-t="---">---</span></span>
					<span class="gano-hud__sep">|</span>
					<span class="gano-hud__token gano-hud__token--time"><span class="gano-hud__val" data-gano-hud-time data-t="--:--:--">--:--:--</span></span>
				</div>
			</div>
		</div>
	</div>
	<?php
}

