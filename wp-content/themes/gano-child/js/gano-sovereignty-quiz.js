document.addEventListener('DOMContentLoaded', function() {
    const quizData = [
        {
            q: "¿Dónde se alojan actualmente tus datos principales?",
            o: ["Servidor Local/Físico", "Hosting Compartido (GoDaddy/Bluehost)", "Cloud Pública (AWS/Google)", "Nuestra infraestructura propia"]
        },
        {
            q: "¿Tienes control total sobre tus llaves de cifrado?",
            o: ["Sí, 100%", "No lo sé / El proveedor las maneja", "No usamos cifrado aún"]
        },
        {
            q: "¿Qué tan rápido es el acceso a tu plataforma?",
            o: ["Instantáneo (NVMe)", "Aceptable (SSD)", "Lento / Intermitente"]
        }
    ];

    let currentStep = 0;
    let score = 0;

    const container = document.getElementById('gano-quiz-engine');
    if (!container) return;

    function renderQuestion() {
        if (currentStep >= quizData.length) {
            showResults();
            return;
        }

        const data = quizData[currentStep];
        let html = `<div class="quiz-question">${data.q}</div><div class="quiz-options">`;
        data.o.forEach((opt, index) => {
            html += `<div class="quiz-option" data-idx="${index}">${opt}</div>`;
        });
        html += `</div>`;
        container.innerHTML = html;

        document.querySelectorAll('.quiz-option').forEach(el => {
            el.addEventListener('click', () => {
                score += (2 - parseInt(el.dataset.idx)) * 10; // Simple scoring
                currentStep++;
                renderQuestion();
            });
        });
    }

    function showResults() {
        const finalScore = Math.max(0, Math.min(100, (score + 40)));
        container.innerHTML = `
            <div id="quiz-results" style="display:block;">
                <h3>Tu Score de Soberanía Digital</h3>
                <div class="score-circle">${finalScore}%</div>
                <p>${getRecommendation(finalScore)}</p>
                <button class="gano-buy-button" onclick="location.reload()">Reiniciar Test</button>
            </div>
        `;
        // Log Quiz Completion
        fetch('/wp-json/gano-agent/v1/log', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message: `EVENT: Quiz Completed | Score: ${finalScore}%`, level: 'INFO' }) });
    }

    function getRecommendation(s) {
        if (s < 50) return "Tu infraestructura está en riesgo de dependencia externa. Recomendamos el Tier 'Startup Blueprint' para iniciar tu migración SOTA.";
        if (s < 80) return "Tienes buenos cimientos, pero falta blindaje. El 'Business Ecosystem' es tu mejor opción.";
        return "¡Eres un soberano digital! Mantén tu estatus con nuestro soporte 'Enterprise Elite'.";
    }

    renderQuestion();
});
