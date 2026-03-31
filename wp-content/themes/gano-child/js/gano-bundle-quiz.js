/**
 * Gano Ecosystem Discovery Tool - Logic
 */

document.addEventListener('DOMContentLoaded', function() {
    const quiz = {
        currentStep: 0,
        answers: {},
        steps: document.querySelectorAll('.gano-quiz-step'),
        
        init: function() {
            const options = document.querySelectorAll('.gano-quiz-option');
            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const step = opt.closest('.gano-quiz-step');
                    const value = opt.dataset.value;
                    const name = step.dataset.name;
                    
                    this.answers[name] = value;
                    this.nextStep();
                });
            });
        },
        
        nextStep: function() {
            this.steps[this.currentStep].classList.remove('active');
            this.currentStep++;
            
            if (this.currentStep < this.steps.length) {
                this.steps[this.currentStep].classList.add('active');
            } else {
                this.showResult();
            }
        },
        
        showResult: function() {
            const resultArea = document.getElementById('gano-quiz-result');
            let recommendation = '';
            let bundle = '';
            let price = '';
            let url = '';
            let desc = '';

            // Expert Mode Check
            if (this.answers['expertise'] === 'expert') {
                recommendation = 'Ecosistema Personalizado (Modo Experto)';
                desc = 'Como usuario experto, te sugerimos configurar tu propio stack desde nuestro catálogo técnico para máximo control.';
                bundle = 'Catálogo SOTA Standard';
                price = 'Desde $ 0'; // Flexible
                url = '#standard-catalog'; // Anchor or page
            } else {
                // Tier Logic based on Business Scale
                if (this.answers['scale'] === 'enterprise') {
                    recommendation = 'Ecosistema Enterprise (3 Años)';
                    bundle = 'Soberanía Empresarial';
                    price = '$ 48.000.000';
                    url = '?gano_add_bundle=GANO-ENTERPRISE-3YR';
                    desc = 'Máxima potencia con VPS Dedicado, M365 Business Pro y Blindaje Capa 7 para operaciones críticas.';
                } else if (this.answers['scale'] === 'growing') {
                    recommendation = 'Ecosistema Professional (3 Años)';
                    bundle = 'Gano Professional Pro';
                    price = '$ 9.600.000';
                    url = '?gano_add_bundle=GANO-PRO-3YR';
                    desc = 'Perfecto para PyMEs en expansión. Incluye Managed WordPress, M365 Essentials y Dominio por 3 años.';
                } else {
                    recommendation = 'Ecosistema Starter (3 Años)';
                    bundle = 'Gano Starter Blueprint';
                    price = '$ 1.910.000';
                    url = '?gano_add_bundle=GANO-STARTER-3YR';
                    desc = 'Todo lo necesario para iniciar con pie derecho: Web Builder, Email Pro y Dominio. Pagado por 3 años.';
                }
            }

            document.getElementById('result-title').innerText = recommendation;
            document.getElementById('result-bundle').innerText = bundle;
            document.getElementById('result-desc').innerText = desc;
            document.getElementById('result-price-val').innerText = price;
            document.getElementById('result-buy-link').href = url;
            
            document.getElementById('gano-quiz-ui').style.display = 'none';
            resultArea.style.display = 'block';
        }
    };

    if (document.querySelector('.gano-quiz-container')) {
        quiz.init();
    }
});
