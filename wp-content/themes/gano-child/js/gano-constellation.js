/**
 * Gano Digital — Constellation 3D Hero
 * Esfera de partículas 3D con soporte completo para prefers-reduced-motion.
 *
 * Comportamiento NORMAL:
 *   - autoRotate activo, speeds de órbita y twinkle completos, scanlines ON,
 *     render cada frame.
 *
 * Comportamiento con prefers-reduced-motion: reduce:
 *   - autoRotate desactivado por defecto.
 *   - orbitSpeed reducido al 10 % (movimiento casi imperceptible).
 *   - twinkleSpeed = 0 (sin parpadeo).
 *   - scanlines desactivadas.
 *   - render throttleado a 1 frame de cada 2 (renderEvery = 2).
 *   - Interacción (drag + panel de info) siempre activa.
 *
 * HTML esperado:
 *   <div class="gano-constellation-wrap" aria-hidden="true"></div>
 *   <div id="gano-constellation-panel" role="status" hidden></div>
 *
 * Convenciones: Vanilla JS, sin dependencias externas, colores de --gano-* tokens.
 */

( function () {
    'use strict';

    // ─── 1. Detección de preferencia de movimiento reducido ───────────────
    const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

    // ─── 2. Configuración — ajustada automáticamente según preferencia ─────
    const CFG = {
        nodeCount:    120,
        radius:       160,          // radio de la esfera (px virtuales)
        focalLength:  400,          // perspectiva
        // autoRotate
        autoRotate:   ! prefersReduced,
        rotateSpeed:  0.003,        // rad/frame eje Y
        // órbitas individuales de cada nodo (pequeña oscilación)
        orbitSpeed:   prefersReduced ? 0.00015 : 0.0015,
        // twinkle (parpadeo de alpha)
        twinkleSpeed: prefersReduced ? 0       : 0.04,
        // scanlines (overlay de líneas horizontales)
        scanlines:    ! prefersReduced,
        // throttle de render: 1 = cada frame, 2 = cada 2 frames, etc.
        renderEvery:  prefersReduced ? 2 : 1,
        // umbral de distancia (normalizado por radio) para dibujar conexiones
        connectionDist: 0.45,
    };

    // Etiquetas descriptivas para el panel (ciclan sobre los nodos)
    const NODE_LABELS = [
        'NVMe · SSD Enterprise',
        'Zero-Trust · Seguridad',
        'IA Predictiva · Ops',
        'CDN Edge · Latencia',
        'Backups · Continuidad',
        'PHP 8.3 · WordPress',
        'SSL/TLS · Cifrado',
        'DDoS · Protección',
        'CO · Soberanía',
        'COP · Facturación',
    ];

    // ─── 3. Setup del canvas ───────────────────────────────────────────────
    const container = document.querySelector( '.gano-constellation-wrap' );
    if ( ! container ) return;

    const canvas  = document.createElement( 'canvas' );
    canvas.className = 'gano-constellation-canvas';
    canvas.setAttribute( 'aria-hidden', 'true' );
    container.appendChild( canvas );

    const ctx = canvas.getContext( '2d' );
    let W, H, cx, cy;

    function resize() {
        W  = canvas.width  = container.clientWidth  || 400;
        H  = canvas.height = container.clientHeight || 400;
        cx = W / 2;
        cy = H / 2;
    }
    window.addEventListener( 'resize', resize, { passive: true } );
    resize();

    // ─── 4. Creación de nodos (distribución de Fibonacci sobre esfera) ─────
    /**
     * @typedef {{ ox: number, oy: number, oz: number,
     *             orbit: number, orbitR: number,
     *             twinklePhase: number,
     *             label: string, detail: string,
     *             _x: number, _y: number, _z: number }} Node
     */

    /**
     * Genera `count` nodos distribuidos uniformemente sobre una esfera de radio `r`.
     * @param {number} count
     * @param {number} r
     * @returns {Node[]}
     */
    function createNodes( count, r ) {
        const nodes = [];
        const goldenAngle = Math.PI * ( 3 - Math.sqrt( 5 ) );

        for ( let i = 0; i < count; i++ ) {
            const y     = 1 - ( i / ( count - 1 ) ) * 2;
            const rr    = Math.sqrt( 1 - y * y );
            const theta = goldenAngle * i;

            nodes.push( {
                // posición base en la esfera
                ox:          r * rr * Math.cos( theta ),
                oy:          r * y,
                oz:          r * rr * Math.sin( theta ),
                // estado de la mini-órbita individual
                orbit:       Math.random() * Math.PI * 2,
                orbitR:      Math.random() * 0.3 + 0.05, // amplitud normalizada
                // estado del twinkle
                twinklePhase: Math.random() * Math.PI * 2,
                // metadata para el panel
                label:       'Nodo ' + String( i ).padStart( 3, '0' ),
                detail:      NODE_LABELS[ i % NODE_LABELS.length ],
                // coordenadas proyectadas (calculadas cada frame)
                _x: 0, _y: 0, _z: 0,
            } );
        }
        return nodes;
    }

    let nodes = createNodes( CFG.nodeCount, CFG.radius );

    // ─── 5. Rotación global (autoRotate + arrastre con mouse / touch) ──────
    let rotY       = 0;   // eje Y (horizontal)
    let rotX       = 0;   // eje X (vertical)
    let isDragging = false;
    let dragStartX = 0, dragStartY = 0;
    let dragRotY   = 0, dragRotX   = 0;

    // Mouse
    canvas.addEventListener( 'mousedown', function ( e ) {
        isDragging = true;
        dragStartX = e.clientX;
        dragStartY = e.clientY;
        dragRotY   = rotY;
        dragRotX   = rotX;
    } );
    window.addEventListener( 'mousemove', function ( e ) {
        if ( ! isDragging ) return;
        rotY = dragRotY + ( e.clientX - dragStartX ) * 0.005;
        rotX = dragRotX + ( e.clientY - dragStartY ) * 0.005;
    } );
    window.addEventListener( 'mouseup', function () {
        isDragging = false;
    } );

    // Touch
    canvas.addEventListener( 'touchstart', function ( e ) {
        isDragging = true;
        dragStartX = e.touches[ 0 ].clientX;
        dragStartY = e.touches[ 0 ].clientY;
        dragRotY   = rotY;
        dragRotX   = rotX;
    }, { passive: true } );
    canvas.addEventListener( 'touchmove', function ( e ) {
        if ( ! isDragging ) return;
        rotY = dragRotY + ( e.touches[ 0 ].clientX - dragStartX ) * 0.005;
        rotX = dragRotX + ( e.touches[ 0 ].clientY - dragStartY ) * 0.005;
    }, { passive: true } );
    canvas.addEventListener( 'touchend', function () {
        isDragging = false;
    } );

    // ─── 6. Panel de información (siempre activo) ──────────────────────────
    const panel = document.getElementById( 'gano-constellation-panel' );

    /**
     * Devuelve el nodo más cercano al punto (mx, my) en coords de canvas,
     * o null si ninguno está lo suficientemente cerca.
     * @param {number} mx
     * @param {number} my
     * @returns {Node|null}
     */
    function pickNode( mx, my ) {
        for ( let i = 0; i < nodes.length; i++ ) {
            const n = nodes[ i ];
            const proj = project( n._x, n._y, n._z );
            if ( Math.hypot( proj.sx - mx, proj.sy - my ) < 12 ) {
                return n;
            }
        }
        return null;
    }

    canvas.addEventListener( 'click', function ( e ) {
        if ( ! panel ) return;
        const rect = canvas.getBoundingClientRect();
        const mx   = e.clientX - rect.left;
        const my   = e.clientY - rect.top;
        const hit  = pickNode( mx, my );
        if ( hit ) {
            panel.textContent = hit.label + ' — ' + hit.detail;
            panel.hidden      = false;
            // Posicionar el panel cerca del nodo clicado
            panel.style.left = mx + 'px';
            panel.style.top  = ( my - 48 ) + 'px';
        } else {
            panel.hidden = true;
        }
    } );

    // ─── 7. Matemáticas de proyección y rotación 3D ────────────────────────

    /**
     * Proyección perspectiva: 3D → coordenadas de canvas.
     * @param {number} x
     * @param {number} y
     * @param {number} z
     * @returns {{ sx: number, sy: number, scale: number }}
     */
    function project( x, y, z ) {
        const s = CFG.focalLength / ( CFG.focalLength + z + CFG.radius );
        return { sx: cx + x * s, sy: cy + y * s, scale: s };
    }

    /**
     * Aplica rotación sobre ejes Y y X al punto (ox, oy, oz).
     * @param {number} ox
     * @param {number} oy
     * @param {number} oz
     * @returns {{ x: number, y: number, z: number }}
     */
    function applyRotation( ox, oy, oz ) {
        // Rotación Y
        const x  =  ox * Math.cos( rotY ) + oz * Math.sin( rotY );
        const z0 = -ox * Math.sin( rotY ) + oz * Math.cos( rotY );
        // Rotación X
        const y  =  oy * Math.cos( rotX ) - z0 * Math.sin( rotX );
        const z  =  oy * Math.sin( rotX ) + z0 * Math.cos( rotX );
        return { x, y, z };
    }

    // ─── 8. Loop de renderizado ────────────────────────────────────────────
    let frameCount = 0;
    let rafId;

    function draw() {
        rafId = requestAnimationFrame( draw );
        frameCount++;

        // Throttle: saltar frames si prefers-reduced-motion
        if ( frameCount % CFG.renderEvery !== 0 ) return;

        // Autorotación (desactivada si prefers-reduced-motion o el usuario arrastra)
        if ( CFG.autoRotate && ! isDragging ) {
            rotY += CFG.rotateSpeed;
        }

        ctx.clearRect( 0, 0, W, H );

        // ── Actualizar posiciones de nodos y ordenar por Z (painter's algorithm)
        for ( let i = 0; i < nodes.length; i++ ) {
            const n = nodes[ i ];

            // Mini-órbita individual
            n.orbit += CFG.orbitSpeed;

            const ox = n.ox + n.orbitR * CFG.radius * Math.cos( n.orbit );
            const oy = n.oy + n.orbitR * CFG.radius * Math.sin( n.orbit ) * 0.2;

            const r = applyRotation( ox, oy, n.oz );
            n._x = r.x;
            n._y = r.y;
            n._z = r.z;
        }

        // Ordenar por Z ascendente para dibujar los más lejanos primero
        const sorted = nodes.slice().sort( function ( a, b ) {
            return a._z - b._z;
        } );

        // ── Conexiones entre nodos cercanos
        const connThresh = CFG.radius * CFG.connectionDist;
        ctx.lineWidth = 0.4;
        for ( let i = 0; i < sorted.length; i++ ) {
            for ( let j = i + 1; j < sorted.length; j++ ) {
                const a    = sorted[ i ];
                const b    = sorted[ j ];
                const dist = Math.sqrt(
                    Math.pow( a._x - b._x, 2 ) +
                    Math.pow( a._y - b._y, 2 ) +
                    Math.pow( a._z - b._z, 2 )
                );
                if ( dist < connThresh ) {
                    const alpha = ( 1 - dist / connThresh ) * 0.3;
                    ctx.strokeStyle = 'rgba(27,79,216,' + alpha + ')'; // --gano-blue
                    ctx.beginPath();
                    const pa = project( a._x, a._y, a._z );
                    const pb = project( b._x, b._y, b._z );
                    ctx.moveTo( pa.sx, pa.sy );
                    ctx.lineTo( pb.sx, pb.sy );
                    ctx.stroke();
                }
            }
        }

        // ── Nodos
        for ( let i = 0; i < sorted.length; i++ ) {
            const n    = sorted[ i ];
            const proj = project( n._x, n._y, n._z );

            // Alpha basado en profundidad (los más cercanos se ven más brillantes)
            let alpha = ( n._z + CFG.radius ) / ( 2 * CFG.radius );
            alpha = Math.max( 0.2, Math.min( 1, alpha ) );

            // Twinkle — solo si speed > 0
            if ( CFG.twinkleSpeed > 0 ) {
                n.twinklePhase += CFG.twinkleSpeed;
                alpha *= 0.7 + 0.3 * Math.sin( n.twinklePhase );
            }

            const r = Math.max( 1.5, 4 * proj.scale );

            // Punto principal (--gano-green)
            ctx.beginPath();
            ctx.arc( proj.sx, proj.sy, r, 0, Math.PI * 2 );
            ctx.fillStyle = 'rgba(0,194,107,' + alpha + ')';
            ctx.fill();

            // Halo tenue (--gano-blue)
            ctx.beginPath();
            ctx.arc( proj.sx, proj.sy, r * 2.5, 0, Math.PI * 2 );
            ctx.fillStyle = 'rgba(27,79,216,' + ( alpha * 0.15 ) + ')';
            ctx.fill();
        }

        // ── Scanlines — desactivadas si prefers-reduced-motion
        if ( CFG.scanlines ) {
            ctx.lineWidth    = 1;
            ctx.strokeStyle  = 'rgba(0,0,0,0.06)';
            for ( let y = 0; y < H; y += 4 ) {
                ctx.beginPath();
                ctx.moveTo( 0, y );
                ctx.lineTo( W, y );
                ctx.stroke();
            }
        }
    }

    // ─── 9. Arranque ──────────────────────────────────────────────────────
    function init() {
        resize();
        draw();
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }

    // ─── 10. Limpieza en navegación SPA / Turbo ────────────────────────────
    document.addEventListener( 'turbo:before-cache', function () {
        cancelAnimationFrame( rafId );
    } );

} )();
