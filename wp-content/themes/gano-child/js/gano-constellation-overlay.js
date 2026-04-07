/**
 * Gano Digital — Constellation SC Overlay Handler
 *
 * Gestiona la activación/desactivación de los overlays SC
 * (TL / TR / modal) para la página SOTA SHOP.
 *
 * Activación: clic en #sc-btn-menu o tecla "M" (mayúscula/minúscula).
 * En móviles (≤ 480 px) se muestra el modal centrado en lugar de los
 * paneles TL/TR, para no tapar controles ni la leyenda del catálogo.
 */

( function () {
    'use strict';

    /** Respeta prefers-reduced-motion: skip transitions */
    const prefersReducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const MOBILE_BP = 480;

    let isActive = false;

    /**
     * Activa/desactiva los overlays según el viewport.
     * En desktop: paneles TL y TR.
     * En mobile (≤ MOBILE_BP): modal centrado.
     *
     * @param {boolean} forceState — si se pasa, fuerza ese estado;
     *                               de lo contrario alterna.
     */
    function toggleOverlays( forceState ) {
        isActive = ( forceState !== undefined ) ? forceState : ! isActive;

        const isMobile = window.innerWidth <= MOBILE_BP;

        const btn    = document.getElementById( 'sc-btn-menu' );
        const ovTL   = document.getElementById( 'sc-overlay-tl' );
        const ovTR   = document.getElementById( 'sc-overlay-tr' );
        const modal  = document.getElementById( 'sc-modal' );

        /* Disable CSS transitions when user prefers reduced motion */
        const noMotionStyle = prefersReducedMotion ? 'transition:none!important;' : '';
        [ ovTL, ovTR, modal ].forEach( function ( el ) {
            if ( el ) { el.style.cssText = noMotionStyle; }
        } );

        /* Actualizar aria-expanded en el botón */
        if ( btn ) {
            btn.setAttribute( 'aria-expanded', String( isActive ) );
        }

        if ( isMobile ) {
            /* ── Mobile: mostrar/ocultar modal centrado ── */
            if ( modal ) {
                modal.classList.toggle( 'is-active', isActive );
                modal.setAttribute( 'aria-hidden', String( ! isActive ) );

                if ( isActive ) {
                    /* Mover foco al primer elemento focusable del modal */
                    const focusTarget = modal.querySelector(
                        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                    );
                    if ( focusTarget ) {
                        focusTarget.focus();
                    }
                }
            }

            /* Asegurar que los paneles TL/TR queden ocultos en mobile */
            if ( ovTL ) { ovTL.classList.remove( 'is-active' ); ovTL.setAttribute( 'aria-hidden', 'true' ); }
            if ( ovTR ) { ovTR.classList.remove( 'is-active' ); ovTR.setAttribute( 'aria-hidden', 'true' ); }
        } else {
            /* ── Desktop/Tablet: mostrar/ocultar paneles TL y TR ── */
            if ( ovTL ) {
                ovTL.classList.toggle( 'is-active', isActive );
                ovTL.setAttribute( 'aria-hidden', String( ! isActive ) );
            }
            if ( ovTR ) {
                ovTR.classList.toggle( 'is-active', isActive );
                ovTR.setAttribute( 'aria-hidden', String( ! isActive ) );
            }

            /* Asegurar que el modal quede oculto en desktop */
            if ( modal ) {
                modal.classList.remove( 'is-active' );
                modal.setAttribute( 'aria-hidden', 'true' );
            }
        }
    }

    /**
     * Cierra todos los overlays y el modal.
     */
    function closeAll() {
        toggleOverlays( false );
    }

    /**
     * Inicializa listeners una vez que el DOM está listo.
     */
    function init() {
        const btn    = document.getElementById( 'sc-btn-menu' );
        const modal  = document.getElementById( 'sc-modal' );
        const btnClose = document.getElementById( 'sc-modal-close' );

        /* Botón btnMenu */
        if ( btn ) {
            btn.addEventListener( 'click', function () {
                toggleOverlays();
            } );
        }

        /* Botón cerrar modal */
        if ( btnClose ) {
            btnClose.addEventListener( 'click', function () {
                closeAll();
                if ( btn ) { btn.focus(); }
            } );
        }

        /* Clic fuera del modal para cerrarlo */
        if ( modal ) {
            modal.addEventListener( 'click', function ( e ) {
                if ( e.target === modal ) {
                    closeAll();
                    if ( btn ) { btn.focus(); }
                }
            } );
        }

        /* Teclado: M = toggle, Escape = cerrar */
        document.addEventListener( 'keydown', function ( e ) {
            /* Ignorar cuando el foco está en un input/textarea */
            const tag = document.activeElement
                ? document.activeElement.tagName.toLowerCase()
                : '';
            if ( tag === 'input' || tag === 'textarea' || tag === 'select' ) {
                return;
            }

            if ( e.key === 'm' || e.key === 'M' ) {
                e.preventDefault();
                toggleOverlays();
            } else if ( e.key === 'Escape' && isActive ) {
                e.preventDefault();
                closeAll();
                if ( btn ) { btn.focus(); }
            }
        } );

        /* Recalcular en resize por si cambia entre mobile/desktop */
        let resizeTimer;
        window.addEventListener( 'resize', function () {
            clearTimeout( resizeTimer );
            resizeTimer = setTimeout( function () {
                if ( isActive ) {
                    /* Re-aplicar estado según nuevo viewport */
                    toggleOverlays( true );
                }
            }, 150 );
        } );
    }

    /* Esperar DOMContentLoaded */
    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }

} )();
