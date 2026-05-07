/**
 * Ecosystems Billing Toggle
 * Handles monthly/annual price switching for hosting plans
 *
 * @package Gano_Digital
 */

(function() {
	'use strict';

	// Pricing multipliers for annual vs monthly
	const PRICING_DATA = {
		'wordpress-basico': {
			monthly: 196000,
			annual: 2156000,
			currency: 'COP'
		},
		'wordpress-deluxe': {
			monthly: 450000,
			annual: 4950000,
			currency: 'COP'
		},
		'wordpress-ultimate': {
			monthly: 890000,
			annual: 9790000,
			currency: 'COP'
		}
	};

	// Format currency in Colombian Pesos
	function formatPrice(price) {
		return new Intl.NumberFormat('es-CO', {
			style: 'currency',
			currency: 'COP',
			minimumFractionDigits: 0,
			maximumFractionDigits: 0
		}).format(price);
	}

	// Initialize toggle functionality
	function initBillingToggle() {
		const toggleButton = document.querySelector('[data-billing-toggle]');
		const billingPeriod = document.querySelector('[data-billing-period]');

		if (!toggleButton || !billingPeriod) {
			return; // Toggle elements not found on this page
		}

		// Get initial state
		const currentPeriod = billingPeriod.getAttribute('data-billing-period') || 'monthly';
		updateToggleUI(toggleButton, currentPeriod);

		// Handle toggle click
		toggleButton.addEventListener('click', function() {
			const period = billingPeriod.getAttribute('data-billing-period');
			const newPeriod = period === 'monthly' ? 'annual' : 'monthly';

			// Update stored period
			billingPeriod.setAttribute('data-billing-period', newPeriod);
			localStorage.setItem('gano-billing-period', newPeriod);

			// Update UI
			updateToggleUI(toggleButton, newPeriod);
			updateAllPrices(newPeriod);

			// Dispatch event for other components
			window.dispatchEvent(new CustomEvent('gano-billing-period-changed', {
				detail: { period: newPeriod }
			}));
		});

		// Apply saved preference on page load
		const savedPeriod = localStorage.getItem('gano-billing-period') || 'monthly';
		if (savedPeriod !== currentPeriod) {
			billingPeriod.setAttribute('data-billing-period', savedPeriod);
			updateToggleUI(toggleButton, savedPeriod);
			updateAllPrices(savedPeriod);
		}
	}

	// Update toggle button UI
	function updateToggleUI(toggle, period) {
		toggle.setAttribute('aria-pressed', period === 'annual');
		toggle.classList.toggle('eco-billing-toggle--annual', period === 'annual');

		const label = toggle.querySelector('.eco-billing-toggle__label') || toggle;
		const labelText = period === 'annual' ? 'Pago Anual' : 'Pago Mensual';
		if (toggle.querySelector('.eco-billing-toggle__label')) {
			toggle.querySelector('.eco-billing-toggle__label').textContent = labelText;
		}

		// Update all price displays
		const priceElements = document.querySelectorAll('[data-plan-price]');
		priceElements.forEach(el => {
			const planId = el.getAttribute('data-plan-price');
			if (PRICING_DATA[planId]) {
				const price = PRICING_DATA[planId][period];
				el.textContent = formatPrice(price);
				el.setAttribute('data-period', period);
			}
		});
	}

	// Update all prices on the page
	function updateAllPrices(period) {
		const priceElements = document.querySelectorAll('[data-plan-price]');

		priceElements.forEach(el => {
			const planId = el.getAttribute('data-plan-price');

			if (PRICING_DATA[planId]) {
				const price = PRICING_DATA[planId][period];
				el.textContent = formatPrice(price);
				el.setAttribute('data-period', period);

				// Calculate savings for annual plans
				if (period === 'annual') {
					const monthlyTotal = PRICING_DATA[planId].monthly * 12;
					const savings = monthlyTotal - price;
					const savingsPercent = Math.round((savings / monthlyTotal) * 100);

					const savingsElement = el.closest('[data-plan-card]')?.querySelector('[data-plan-savings]');
					if (savingsElement) {
						savingsElement.textContent = `Ahorra ${savingsPercent}% pagando anual`;
						savingsElement.style.display = 'block';
					}
				} else {
					const savingsElement = el.closest('[data-plan-card]')?.querySelector('[data-plan-savings]');
					if (savingsElement) {
						savingsElement.style.display = 'none';
					}
				}
			}
		});

		// Update period labels
		const periodLabels = document.querySelectorAll('[data-billing-label]');
		periodLabels.forEach(label => {
			label.textContent = period === 'annual' ? 'año' : 'mes';
		});
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initBillingToggle);
	} else {
		initBillingToggle();
	}

	// Expose API globally for other scripts
	window.GanoBillingToggle = {
		setPeriod: function(period) {
			const billingPeriod = document.querySelector('[data-billing-period]');
			const toggleButton = document.querySelector('[data-billing-toggle]');
			if (billingPeriod && toggleButton) {
				billingPeriod.setAttribute('data-billing-period', period);
				localStorage.setItem('gano-billing-period', period);
				updateToggleUI(toggleButton, period);
				updateAllPrices(period);
			}
		},
		getPeriod: function() {
			const billingPeriod = document.querySelector('[data-billing-period]');
			return billingPeriod?.getAttribute('data-billing-period') || 'monthly';
		}
	};
})();
