/**
 * FAQ Accordion Component
 * Handles expand/collapse of FAQ items with accessibility support
 *
 * @package Gano_Digital
 */

(function() {
	'use strict';

	class FAQAccordion {
		constructor(containerSelector) {
			this.container = document.querySelector(containerSelector);
			if (!this.container) {
				return;
			}
			this.init();
		}

		init() {
			const buttons = this.container.querySelectorAll('.faq-question');
			buttons.forEach(button => {
				button.addEventListener('click', (e) => this.toggle(e));
				button.addEventListener('keydown', (e) => this.handleKeydown(e));
			});
		}

		toggle(event) {
			const button = event.currentTarget;
			const targetId = button.getAttribute('data-target');
			const answerDiv = document.querySelector(targetId);

			if (!answerDiv) {
				return;
			}

			// Close all other items
			this.container.querySelectorAll('.faq-answer').forEach(div => {
				if (div !== answerDiv) {
					this.closeItem(div);
				}
			});

			// Toggle current item
			const isOpen = !answerDiv.hidden;
			if (isOpen) {
				this.closeItem(answerDiv);
			} else {
				this.openItem(answerDiv);
			}

			// Track GA4 event
			if (window.gtag) {
				gtag('event', 'faq_interaction', {
					faq_id: button.getAttribute('data-faq-id'),
					action: isOpen ? 'close' : 'open'
				});
			}
		}

		openItem(answerDiv) {
			answerDiv.hidden = false;
			const button = this.container.querySelector(`[data-target="#${answerDiv.id}"]`);
			if (button) {
				button.setAttribute('aria-expanded', 'true');
				button.classList.add('open');
			}
		}

		closeItem(answerDiv) {
			answerDiv.hidden = true;
			const button = this.container.querySelector(`[data-target="#${answerDiv.id}"]`);
			if (button) {
				button.setAttribute('aria-expanded', 'false');
				button.classList.remove('open');
			}
		}

		handleKeydown(event) {
			const button = event.currentTarget;

			// Enter or Space to toggle
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				button.click();
			}

			// Arrow keys for navigation
			const buttons = Array.from(this.container.querySelectorAll('.faq-question'));
			const currentIndex = buttons.indexOf(button);

			if (event.key === 'ArrowDown' && currentIndex < buttons.length - 1) {
				event.preventDefault();
				buttons[currentIndex + 1].focus();
			} else if (event.key === 'ArrowUp' && currentIndex > 0) {
				event.preventDefault();
				buttons[currentIndex - 1].focus();
			}
		}
	}

	// Make FAQAccordion globally available
	window.FAQAccordion = FAQAccordion;

})();
