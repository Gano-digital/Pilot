document.addEventListener('DOMContentLoaded', function() {
    const quizData = [
        {
            q: "¿Cuál es la frontera jurisdiccional de tus activos digitales?",
            o: ["Infraestructura local (Soberanía)", "Nube pública (Dependencia externa)", "Hosting compartido (Riesgo distribuido)", "Sin definición clara"]
        },
        {
            q: "¿Bajo qué protocolo de confianza opera tu arquitectura actual?",
            o: ["Confianza Cero (Zero-Trust)", "Perímetro tradicional (Vulnerable)", "Sin protocolos activos"]
        },
        {
            q: "¿En qué escala se mide la resiliencia de tu operación?",
            o: ["Milisegundos (NVMe Gen4 + HA)", "Segundos (SSD Tradicional)", "Minutos/Horas (Infraestructura Legacy)"]
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
                <h3>Índice de Resiliencia Soberana</h3>
                <div class="score-circle">${finalScore}%</div>
                <p>${getRecommendation(finalScore)}</p>
                <button class="gano-buy-button" onclick="location.reload()">Reiniciar Auditoría</button>
            </div>
        `;
        // Log Quiz Completion
        fetch('/wp-json/gano-agent/v1/log', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ message: `EVENT: Quiz Completed | Score: ${finalScore}%`, level: 'INFO' }) });
    }

    function getRecommendation(s) {
        if (s < 50) return "Tu infraestructura opera bajo una dependencia crítica externa. Recomendamos el despliegue del 'Núcleo Prime' para cimentar tu soberanía.";
        if (s < 80) return "Posees una base sólida, pero careces de blindaje perimetral SOTA. Tu ecosistema ideal es la 'Fortaleza Delta'.";
        return "¡Eres un soberano digital! Tu arquitectura está lista para el nivel 'Bastión SOTA' de alta disponibilidad.";
    }

    renderQuestion();
});
