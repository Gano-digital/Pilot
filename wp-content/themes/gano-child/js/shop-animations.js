document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('billing-toggle');
    const prices = document.querySelectorAll('.gano-price-value');
    
    if (toggle) {
        toggle.addEventListener('change', function() {
            // Track Toggle usage
            fetch('/wp-json/gano-agent/v1/log', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: 'EVENT: Billing Toggle Switched to ' + (this.checked ? 'Annual' : 'Monthly'), level: 'INFO' })
            });

            prices.forEach(price => {
                const setup = price.getAttribute('data-setup');
                price.style.opacity = '0';
                setTimeout(() => {
                    price.innerText = new Intl.NumberFormat('es-CO').format(setup);
                    price.style.opacity = '1';
                }, 200);
            });
        });
    }

    // Telemetry for Buy Buttons
    const buyButtons = document.querySelectorAll('.gano-buy-button');
    buyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const plan = this.closest('.gano-product-card')?.querySelector('h3')?.innerText || 'Unknown';
            fetch('/wp-json/gano-agent/v1/log', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: 'EVENT: Buy Button Clicked | Plan: ' + plan, level: 'INFO' })
            });
        });
    });

    const specs = document.querySelectorAll('.gano-product-specs li');
    specs.forEach(spec => {
        spec.addEventListener('mouseenter', function() {
            this.style.color = '#D4AF37';
            this.style.transform = 'translateX(10px)';
            this.style.transition = '0.3s';
        });
        spec.addEventListener('mouseleave', function() {
            this.style.color = '#fff';
            this.style.transform = 'translateX(0)';
        });
    });
});
