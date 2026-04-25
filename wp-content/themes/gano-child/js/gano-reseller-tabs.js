/**
 * Gano Reseller Tabs
 *
 * Tab switching logic para iframes Reseller en page-ecosistemas.php
 * Maneja el estado activo de botones y panes con transiciones suaves.
 *
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Esperar a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        initResellerTabs();
    });

    /**
     * Inicializa la funcionalidad de tabs para Reseller Store
     */
    function initResellerTabs() {
        const tabButtons = document.querySelectorAll('.gano-reseller-tab-btn');
        const tabPanes = document.querySelectorAll('.gano-reseller-tab-pane');

        if (tabButtons.length === 0 || tabPanes.length === 0) {
            return; // No tabs found on this page
        }

        // Configurar listeners en cada botón
        tabButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const tab = this.getAttribute('data-tab');
                switchTab(tab, tabButtons, tabPanes);
            });

            // Accesibilidad: permitir navegación con teclado (Enter/Space)
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });

        // Mostrar solo el primer pane por defecto
        if (tabPanes.length > 0) {
            tabPanes.forEach(function(pane, idx) {
                if (idx === 0) {
                    pane.classList.add('active');
                } else {
                    pane.classList.remove('active');
                }
            });
        }
    }

    /**
     * Cambia el tab activo
     *
     * @param {string} tabName - Valor de data-tab del tab a activar
     * @param {NodeList} buttons - Lista de botones de tab
     * @param {NodeList} panes - Lista de panes de contenido
     */
    function switchTab(tabName, buttons, panes) {
        // Remover clase active de todos los botones y panes
        buttons.forEach(function(btn) {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });

        panes.forEach(function(pane) {
            pane.classList.remove('active');
        });

        // Agregar clase active al botón y pane seleccionados
        const activeButton = document.querySelector('.gano-reseller-tab-btn[data-tab="' + tabName + '"]');
        const activePane = document.querySelector('.gano-reseller-tab-pane[data-tab="' + tabName + '"]');

        if (activeButton) {
            activeButton.classList.add('active');
            activeButton.setAttribute('aria-selected', 'true');
            activeButton.focus(); // Accesibilidad: dar foco al botón activado
        }

        if (activePane) {
            activePane.classList.add('active');
        }
    }
})();
