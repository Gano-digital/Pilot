/**
 * Gano Premium Custom Cursor - Physics & Interaction
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Logic to create markup
    const wrapper = document.createElement('div');
    wrapper.className = 'gano-cursor-wrapper';
    wrapper.innerHTML = `
        <div id="gano-cursor-dot"></div>
        <div id="gano-cursor-ring"></div>
    `;
    document.body.appendChild(wrapper);

    const dot = document.getElementById('gano-cursor-dot');
    const ring = document.getElementById('gano-cursor-ring');

    let mouseX = 0;
    let mouseY = 0;
    let dotX = 0;
    let dotY = 0;
    let ringX = 0;
    let ringY = 0;

    // Smooth Lerp factor (lower = smoother/slower)
    const lerpDot = 0.25;
    const lerpRing = 0.12;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Animation Loop
    function animate() {
        // Dot movement
        dotX += (mouseX - dotX) * lerpDot;
        dotY += (mouseY - dotY) * lerpDot;
        dot.style.left = dotX + 'px';
        dot.style.top = dotY + 'px';

        // Ring movement (more delay)
        ringX += (mouseX - ringX) * lerpRing;
        ringY += (mouseY - ringY) * lerpRing;
        ring.style.left = ringX + 'px';
        ring.style.top = ringY + 'px';

        requestAnimationFrame(animate);
    }
    animate();

    // Interaction Listeners
    const hoverTargets = 'a, button, .gano-quiz-option, input[type="submit"], i, .rstore-add-to-cart';
    
    document.addEventListener('mouseover', (e) => {
        if (e.target.closest(hoverTargets)) {
            document.body.classList.add('is-hovering');
        }
    });

    document.addEventListener('mouseout', (e) => {
        if (e.target.closest(hoverTargets)) {
            document.body.classList.remove('is-hovering');
        }
    });

    document.addEventListener('mousedown', () => {
        document.body.classList.add('is-clicking');
    });

    document.addEventListener('mouseup', () => {
        document.body.classList.remove('is-clicking');
    });
});
