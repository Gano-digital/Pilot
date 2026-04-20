/**
 * Gano — Cursor estilo WC3 (guantelete humano) + estados dinámicos.
 * Atlas: assets/cursor/wc3-human-atlas.png (256×128, sprites Blizzard / referencia comunitaria).
 */

document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.createElement('div');
    wrapper.className = 'gano-cursor-wrapper';
    wrapper.innerHTML = '<div id="gano-cursor-gauntlet" class="gano-cursor-gauntlet" aria-hidden="true"></div>';
    document.body.appendChild(wrapper);

    const gauntlet = document.getElementById('gano-cursor-gauntlet');
    if (!gauntlet) {
        return;
    }

    let mouseX = 0;
    let mouseY = 0;
    let drawX = 0;
    let drawY = 0;
    const lerpFollow = 1;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) {
        document.body.classList.add('gano-cursor--nomotion');
    }

    const hoverSelector =
        'a[href], button, [role="button"], [role="link"], label, select, .gano-quiz-option, input[type="submit"], .rstore-add-to-cart, summary, [data-gano-cursor-hover]';

    const textCursorSelector =
        'input[type="text"], input[type="email"], input[type="search"], input[type="url"], input[type="tel"], input[type="password"], input[type="number"], textarea, [contenteditable="true"]';

    const invalidSelector =
        '[aria-disabled="true"], [disabled], .disabled, [data-gano-cursor-invalid]';

    function targetMatches(el, selector) {
        return el && typeof el.closest === 'function' && el.closest(selector);
    }

    document.addEventListener('mousemove', function (e) {
        mouseX = e.clientX;
        mouseY = e.clientY;

        const t = e.target instanceof Element ? e.target : null;
        document.body.classList.toggle('gano-cursor--text', !!targetMatches(t, textCursorSelector));
        document.body.classList.toggle('gano-cursor--invalid', !!targetMatches(t, invalidSelector));
        document.body.classList.toggle(
            'gano-cursor--hover',
            !!targetMatches(t, hoverSelector) && !targetMatches(t, invalidSelector)
        );
    });

    function animate() {
        drawX += (mouseX - drawX) * lerpFollow;
        drawY += (mouseY - drawY) * lerpFollow;
        gauntlet.style.left = drawX + 'px';
        gauntlet.style.top = drawY + 'px';
        requestAnimationFrame(animate);
    }
    animate();

    document.addEventListener('mousedown', function () {
        document.body.classList.add('gano-cursor--click');
    });

    document.addEventListener('mouseup', function () {
        document.body.classList.remove('gano-cursor--click');
    });
});
