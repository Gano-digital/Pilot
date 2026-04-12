<?php
/**
 * Template Name: Diagnostico Digital SOTA
 * Template Post Type: page
 *
 * @package gano-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="gano-main-content" class="gano-sota-surface gano-diagnostico-page">
	<section class="gano-sota-hero">
		<div class="gano-sota-hero__bg" aria-hidden="true"></div>
		<div class="gano-sota-hero__content">
			<span class="gano-sota-label">Diagnóstico Digital</span>
			<h1 class="gano-sota-hero__title">Evalúa la madurez de tu <span class="gano-sota-hero__title--accent">infraestructura WordPress</span></h1>
			<p class="gano-sota-hero__sub">
				Responde 6 preguntas para identificar riesgos operativos, nivel de soberanía digital y
				la arquitectura recomendada para tu etapa.
			</p>
		</div>
	</section>

	<section class="gano-sota-status-strip" aria-label="Indicadores del diagnóstico">
		<div class="gano-sota-status-strip__grid">
			<div class="gano-sota-status-strip__item">
				<span class="gano-sota-status-strip__label">Preguntas</span>
				<span class="gano-sota-status-strip__value">06</span>
			</div>
			<div class="gano-sota-status-strip__item">
				<span class="gano-sota-status-strip__label">Duración</span>
				<span class="gano-sota-status-strip__value">03 min</span>
			</div>
			<div class="gano-sota-status-strip__item">
				<span class="gano-sota-status-strip__label">Salida</span>
				<span class="gano-sota-status-strip__value">Score + Recomendación</span>
			</div>
		</div>
	</section>

	<section class="gano-sota-section">
		<div class="gano-sota-container">
			<div class="gano-sota-quiz-shell">
				<div class="gano-sota-quiz-progress" aria-hidden="true">
					<div class="gano-sota-quiz-progress__fill" id="gano-quiz-progress-fill"></div>
				</div>
				<p class="gano-diagnostico__step gano-sota-muted" id="gano-quiz-step">Pregunta 1 de 6</p>

				<h2 class="gano-diagnostico__question" id="gano-quiz-question"></h2>
				<div class="gano-diagnostico__answers" id="gano-quiz-answers"></div>

				<div class="gano-diagnostico__result" id="gano-quiz-result" hidden>
					<h3 id="gano-quiz-result-title"></h3>
					<p id="gano-quiz-result-copy"></p>
					<div class="gano-sota-hero__cta-row">
						<a class="gano-btn" href="<?php echo esc_url( home_url( '/ecosistemas' ) ); ?>">Ver arquitecturas recomendadas</a>
						<a class="gano-btn-outline" href="<?php echo esc_url( home_url( '/contacto' ) ); ?>">Hablar con un especialista</a>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<script id="gano-diagnostico-data" type="application/json">
<?php
echo wp_json_encode(
	array(
		array(
			'q'       => '¿Con qué frecuencia revisas seguridad y actualizaciones de WordPress?',
			'answers' => array(
				array( 'text' => 'Solo cuando algo falla.', 'score' => 1 ),
				array( 'text' => 'Una vez al mes.', 'score' => 2 ),
				array( 'text' => 'Semanalmente con checklist.', 'score' => 3 ),
			),
		),
		array(
			'q'       => '¿Cómo gestionas backups y recuperación?',
			'answers' => array(
				array( 'text' => 'No tengo un proceso definido.', 'score' => 1 ),
				array( 'text' => 'Backups automáticos, pero sin pruebas.', 'score' => 2 ),
				array( 'text' => 'Backups + pruebas de restauración.', 'score' => 3 ),
			),
		),
		array(
			'q'       => '¿Tu infraestructura soporta picos de tráfico sin degradación visible?',
			'answers' => array(
				array( 'text' => 'No, se cae o se vuelve lenta.', 'score' => 1 ),
				array( 'text' => 'A veces responde bien, a veces no.', 'score' => 2 ),
				array( 'text' => 'Sí, tenemos margen de capacidad.', 'score' => 3 ),
			),
		),
		array(
			'q'       => '¿Qué tan claro tienes el flujo comercial (checkout y conversiones)?',
			'answers' => array(
				array( 'text' => 'No está claro y perdemos leads.', 'score' => 1 ),
				array( 'text' => 'Funciona, pero sin mucha trazabilidad.', 'score' => 2 ),
				array( 'text' => 'Está optimizado y medido.', 'score' => 3 ),
			),
		),
		array(
			'q'       => '¿Qué nivel de soporte necesitas para operar?',
			'answers' => array(
				array( 'text' => 'Reactivo: cuando hay incidentes.', 'score' => 1 ),
				array( 'text' => 'Mixto: preventivo + reactivo.', 'score' => 2 ),
				array( 'text' => 'Proactivo con alertas e intervención.', 'score' => 3 ),
			),
		),
		array(
			'q'       => '¿Tu equipo tiene soberanía total sobre datos, accesos y operación?',
			'answers' => array(
				array( 'text' => 'No, dependemos de terceros sin control.', 'score' => 1 ),
				array( 'text' => 'Parcial, con varios puntos ciegos.', 'score' => 2 ),
				array( 'text' => 'Sí, tenemos control y documentación.', 'score' => 3 ),
			),
		),
	),
	JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
?>
</script>

<script>
(function () {
	const payloadNode = document.getElementById("gano-diagnostico-data");
	if (!payloadNode) {
		return;
	}

	const questions = JSON.parse(payloadNode.textContent || "[]");
	const questionEl = document.getElementById("gano-quiz-question");
	const answersEl = document.getElementById("gano-quiz-answers");
	const stepEl = document.getElementById("gano-quiz-step");
	const progressEl = document.getElementById("gano-quiz-progress-fill");
	const resultBox = document.getElementById("gano-quiz-result");
	const resultTitle = document.getElementById("gano-quiz-result-title");
	const resultCopy = document.getElementById("gano-quiz-result-copy");

	let current = 0;
	let score = 0;

	function renderQuestion() {
		const total = questions.length;
		const item = questions[current];
		const pct = total ? ((current / total) * 100) : 0;

		stepEl.textContent = "Pregunta " + (current + 1) + " de " + total;
		questionEl.textContent = item.q;
		progressEl.style.width = pct + "%";
		answersEl.innerHTML = "";

		item.answers.forEach(function (answer) {
			const button = document.createElement("button");
			button.type = "button";
			button.className = "gano-diagnostico__answer-btn";
			button.textContent = answer.text;
			button.addEventListener("click", function () {
				score += Number(answer.score || 0);
				current += 1;
				if (current >= total) {
					showResult();
					return;
				}
				renderQuestion();
			});
			answersEl.appendChild(button);
		});
	}

	function showResult() {
		const maxScore = questions.length * 3;
		const ratio = maxScore ? (score / maxScore) : 0;
		progressEl.style.width = "100%";
		stepEl.textContent = "Diagnóstico completado";
		questionEl.textContent = "Resultado";
		answersEl.innerHTML = "";
		resultBox.hidden = false;

		if (ratio < 0.45) {
			resultTitle.textContent = "Prioridad: estabilización inmediata";
			resultCopy.textContent = "Tu operación necesita una base más robusta. Recomendación inicial: Núcleo Prime o Fortaleza Delta con plan de hardening y soporte guiado.";
			return;
		}
		if (ratio < 0.75) {
			resultTitle.textContent = "Prioridad: optimización de operación";
			resultCopy.textContent = "Tienes fundamentos, pero aún hay riesgo operativo. Recomendación: Fortaleza Delta o Bastión SOTA con monitoreo proactivo.";
			return;
		}
		resultTitle.textContent = "Prioridad: escalado estratégico";
		resultCopy.textContent = "Tu madurez técnica es alta. Recomendación: Bastión SOTA o Ultimate WP para crecimiento sostenido y resiliencia comercial.";
	}

	if (questions.length > 0) {
		renderQuestion();
	}
})();
</script>

<?php get_footer(); ?>
