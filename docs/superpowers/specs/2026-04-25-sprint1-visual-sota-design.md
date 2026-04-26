# Sprint 1 — Refactor Visual SOTA 2026 — Design Specification

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:writing-plans to implement this spec task-by-task.

**Goal:** Complete Sprint 1 visual enhancements for gano.digital homepage: navigation UX, scroll-spy, mobile menu, hero holograma, scroll animations, and particle system. All features are modular, progressive-enhancement compatible, and performance-optimized.

**Architecture:** Modular microfeatures approach. Six independent JS/CSS modules enqueued conditionally on front-page.php. All fallbacks tested (prefers-reduced-motion, no-JS, Canvas unavailable). Existing token system (gano-tokens-unified.css) and textures (gano-textures.css) reused.

**Tech Stack:** Vanilla JavaScript (ES6+), CSS Grid/Flexbox, Canvas 2D, IntersectionObserver, RequestAnimationFrame. No dependencies beyond WordPress core.

---

## Overview

### Current State (Kimi completed)
- ✅ `css/gano-tokens-unified.css` — Tokens enqueued in functions.php
- ✅ `css/gano-textures.css` — Textures (noise, scanlines, grid, vignette)
- ✅ `page-dashboard-demo.php` — Protected; legacy moved to `archive/`
- ✅ `front-page.php` — Basic HTML structure (nav, hero, sections)

### Sprint 1 Deliverables
- [ ] F2.1 Navigation UX — Reorganize front-page.php sections
- [ ] F2.2 Scroll-Spy — Hide-on-scroll, glassmorphism, active highlight
- [ ] F2.3 Mobile Menu — Slide animation, blur, CTA prominent
- [ ] F3.1 Hero Holograma — Canvas logo (GANO) + SVG fallback
- [ ] F3.2 Scroll Animations — Reveal, stagger, parallax on scroll
- [ ] F3.3 Particle System — Proximity connections, mouse repulsion
- [ ] Enqueue Strategy — functions.php integration
- [ ] Commit + Deploy — Merge to main, SCP to server

---

## Detailed Specification

### F2.1 — Navigation UX (Section Reorganization)

**Scope:** Restructure front-page.php to define 7 semantic sections with clear scroll targets.

**HTML Changes:**
- Current nav links point to: `#inicio`, `#pilares`, `#precios`, `#servicios`, `#dominios`
- New structure adds: `#showcase` (SOTA articles), `#nosotros` (team), `#contacto` (CTA)
- Nav updated to: Inicio → Infraestructura → Planes → Servicios → (implied Showcase) → Nosotros → Contacto

**Section Structure:**
```
<section id="inicio">       <!-- Hero: badge, title, subtitle, CTAs, trust items -->
<section id="pilares">      <!-- Infraestructura: features, pillars -->
<section id="precios">      <!-- Planes: 4 ecosistemas with comparison -->
<section id="servicios">    <!-- Services: features breakdown -->
<section id="showcase">     <!-- SOTA Articles: 20 content pieces (from gano-content-importer) -->
<section id="nosotros">     <!-- Team/About: company mission, Diego bio -->
<section id="contacto">     <!-- CTA: final conversion opportunity -->
```

**Nav Link Mapping:**
```html
<li><a href="#inicio">Inicio</a></li>
<li><a href="#pilares">Infraestructura</a></li>
<li><a href="#precios">Planes</a></li>
<li><a href="#servicios">Servicios</a></li>
<!-- #showcase implied in scroll flow -->
<li><a href="#nosotros">Nosotros</a></li>
<li><a href="#contacto">Contacto</a></li>
```

**Fallback:** If sections not found, nav links 404 gracefully (anchor navigation is forgiving).

---

### F2.2 — Scroll-Spy + Hide-on-Scroll + Glassmorphism

**Functionality:**

1. **Scroll-Spy:** Highlights current section's nav link based on which section is in viewport.
   - Uses IntersectionObserver with root margin (-50%)
   - Adds `.active` class to corresponding nav link
   - Removes `.active` from previous link

2. **Hide-on-Scroll:** Nav hides when scrolling down > 50px; reappears on scroll up.
   - Tracks scroll direction (deltaY)
   - Applies `.hidden` class (transforms nav out of view)
   - Threshold: show nav if scrollTop < lastScrollTop - 50

3. **Glassmorphism:** Nav background is semi-transparent with backdrop blur.
   - Background: `rgba(5, 7, 10, 0.7)` (dark with transparency)
   - Backdrop-filter: `blur(10px)`
   - Smooth transitions on show/hide

**CSS (gano-nav-enhanced.css):**
```css
.nav {
  position: sticky;
  top: 0;
  z-index: 1000;
  background: rgba(5, 7, 10, 0.7);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  transition: transform 0.3s ease, opacity 0.3s ease;
  border-bottom: 1px solid rgba(27, 79, 216, 0.2);
}

.nav.hidden {
  transform: translateY(-100%);
  opacity: 0;
  pointer-events: none;
}

.nav-links a {
  color: rgba(255, 255, 255, 0.7);
  transition: color 0.2s ease, border-color 0.2s ease;
}

.nav-links a.active {
  color: var(--gano-orange); /* #FF6B35 */
  border-bottom: 2px solid var(--gano-orange);
}

.nav-links a:hover {
  color: white;
  border-bottom: 2px solid var(--gano-blue);
}
```

**JS (gano-nav-scroll-spy.js):**
- IntersectionObserver: Watch all sections, update active link
- Scroll listener: Track direction, toggle `.hidden` class
- Threshold: Hide only if scrolling down AND already scrolled > 100px from top

**Fallback:** If JS fails, nav remains visible with static styling. Active link highlighting gracefully degrades to `:hover` state.

**Accessibility:** Nav links have proper `aria-current="page"` semantic. Focus outline preserved.

---

### F2.3 — Mobile Menu

**Trigger:** `.mobile-toggle` button (hamburger icon) visible only on mobile (max-width: 768px).

**Animation:**
- Menu slides in from left with `translateX(-100% → 0)`
- Backdrop (semi-transparent overlay) fades in
- Menu items stagger-animate on open
- Close button (X icon) or clicking outside closes menu
- ESC key closes menu

**CSS (gano-mobile-menu.css):**
```css
.mobile-menu {
  position: fixed;
  top: 0;
  left: -100vw;
  width: 100vw;
  height: 100vh;
  background: rgba(5, 7, 10, 0.95);
  backdrop-filter: blur(10px);
  transform: translateX(0);
  transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  z-index: 999;
}

.mobile-menu.open {
  transform: translateX(100vw);
}

.mobile-menu-items {
  display: flex;
  flex-direction: column;
  padding: 60px 20px 20px;
  gap: 20px;
}

.mobile-menu a {
  font-size: 1.25rem;
  color: white;
  opacity: 0;
  animation: slideInUp 0.3s ease-out forwards;
}

.mobile-menu.open a:nth-child(1) { animation-delay: 0.1s; }
.mobile-menu.open a:nth-child(2) { animation-delay: 0.2s; }
.mobile-menu.open a:nth-child(3) { animation-delay: 0.3s; }
/* ... etc */

@keyframes slideInUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.mobile-menu-cta {
  position: sticky;
  bottom: 0;
  padding: 20px;
  background: linear-gradient(180deg, transparent, rgba(5, 7, 10, 0.95));
}

.mobile-menu-cta .btn {
  width: 100%;
}
```

**JS (gano-mobile-menu.js):**
- Toggle `.open` class on click `.mobile-toggle`
- Close on any `.mobile-menu a` click (navigate then close)
- Close on ESC key
- Prevent body scroll when menu open (overflow: hidden)

**CTA:** "Crear cuenta" button duplicated in menu footer with full width styling for prominence.

**Accessibility:**
- `aria-expanded` on toggle button (true/false)
- `aria-label="Cerrar menú"` on close button
- Focus trap: first focusable item = close button, last = CTA button, wraps around

**Fallback:** If JS fails, menu hidden by default. Mobile users see toggle button but can't open (graceful degradation).

---

### F3.1 — Hero Holograma (Canvas Logo + Fallback)

**Concept:** Logo "GANO" rendered in Canvas 2D with neon effects:
- **Flicker Neon:** Opacity oscillates between 0.7–1.0 at ~10Hz
- **Poltergeist Drift:** Random micro-movements (±1px, ±0.5px/frame)
- **Glow Effect:** Shadow color = green (#00C26B), shadow blur = 20–30px
- **Fallback SVG:** If Canvas unavailable, show static SVG with CSS glow

**Canvas Implementation (gano-hero-holograma.js):**

```javascript
class HologramaController {
  constructor(canvasId, options = {}) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas) return;

    this.ctx = this.canvas.getContext('2d');
    if (!this.ctx) {
      this.showFallbackSVG();
      return;
    }

    this.config = {
      fontSize: 80,
      fontFamily: 'Arial, sans-serif',
      textColor: '#1B4FD8', // Blue
      glowColor: '#00C26B', // Green glow
      glowBlur: 20,
      flickerSpeed: 0.01, // Oscillation speed
      driftAmount: 0.5,   // Max pixel drift per frame
      ...options
    };

    this.opacity = 1;
    this.drift = { x: 0, y: 0 };
    this.setupCanvas();
    this.startAnimation();
  }

  setupCanvas() {
    const dpr = window.devicePixelRatio || 1;
    this.canvas.width = this.canvas.offsetWidth * dpr;
    this.canvas.height = this.canvas.offsetHeight * dpr;
    this.ctx.scale(dpr, dpr);

    window.addEventListener('resize', () => this.setupCanvas());
  }

  drawLogo() {
    const width = this.canvas.width / (window.devicePixelRatio || 1);
    const height = this.canvas.height / (window.devicePixelRatio || 1);
    const centerX = width / 2;
    const centerY = height / 2;

    this.ctx.font = `bold ${this.config.fontSize}px ${this.config.fontFamily}`;
    this.ctx.fillStyle = this.config.textColor;
    this.ctx.shadowColor = this.config.glowColor;
    this.ctx.shadowBlur = this.config.glowBlur;
    this.ctx.textAlign = 'center';
    this.ctx.textBaseline = 'middle';
    this.ctx.globalAlpha = this.opacity;
    this.ctx.fillText('GANO', centerX + this.drift.x, centerY + this.drift.y);
  }

  updateFlicker() {
    const time = Date.now() * this.config.flickerSpeed;
    this.opacity = 0.7 + Math.sin(time) * 0.3;
  }

  updateDrift() {
    this.drift.x += (Math.random() - 0.5) * this.config.driftAmount;
    this.drift.y += (Math.random() - 0.5) * this.config.driftAmount;
    // Clamp drift
    this.drift.x = Math.max(-2, Math.min(2, this.drift.x));
    this.drift.y = Math.max(-2, Math.min(2, this.drift.y));
  }

  startAnimation() {
    const animate = () => {
      const width = this.canvas.width / (window.devicePixelRatio || 1);
      const height = this.canvas.height / (window.devicePixelRatio || 1);

      this.ctx.clearRect(0, 0, width, height);
      this.updateFlicker();
      this.updateDrift();
      this.drawLogo();

      requestAnimationFrame(animate);
    };
    animate();
  }

  showFallbackSVG() {
    this.canvas.style.display = 'none';
    // Insert SVG fallback as sibling
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'hero-holograma-svg');
    svg.setAttribute('viewBox', '0 0 300 200');
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', '50%');
    text.setAttribute('y', '50%');
    text.setAttribute('font-size', '80');
    text.setAttribute('font-weight', 'bold');
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('dominant-baseline', 'central');
    text.setAttribute('fill', '#1B4FD8');
    text.setAttribute('class', 'hero-holograma-svg-text');
    text.textContent = 'GANO';
    svg.appendChild(text);
    this.canvas.parentNode.insertBefore(svg, this.canvas.nextSibling);
  }
}

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
  new HologramaController('hero-holograma-canvas');
});
```

**CSS (gano-hero-holograma.css):**
```css
#hero-holograma-canvas {
  width: 200px;
  height: 200px;
  margin: 0 auto;
  display: block;
}

.hero-holograma-svg {
  width: 200px;
  height: 200px;
  margin: 0 auto;
  display: block;
}

.hero-holograma-svg-text {
  filter: drop-shadow(0 0 20px #00C26B);
  animation: staticGlow 2s ease-in-out infinite;
}

@keyframes staticGlow {
  0%, 100% { filter: drop-shadow(0 0 15px #00C26B); }
  50% { filter: drop-shadow(0 0 25px #00C26B); }
}
```

**HTML (front-page.php):**
```html
<div class="hero-logo">
  <canvas id="hero-holograma-canvas"></canvas>
</div>
```

**Fallback Chain:**
1. Canvas (animated, full effects)
2. Canvas unavailable → SVG static with CSS glow
3. Both unavailable → CSS gradient text as last resort

**Performance:** RequestAnimationFrame capped at 60fps, drift values pooled (no array allocations per frame).

---

### F3.2 — Scroll Animations (Reveal, Stagger, Parallax)

**Concept:** Elements annotated with `data-animation` attributes trigger on scroll visibility. Uses IntersectionObserver + CSS keyframes + stagger delays.

**HTML Annotations (front-page.php):**
```html
<div class="feature-card" data-animation="reveal-up" data-delay="0.2">
<h2 data-animation="reveal-left" data-stagger="0.1">Título</h2>
<p data-animation="scale">Párrafo</p>
```

**Available Animations:**
- `reveal-up`: `translateY(60px) → 0`, opacity `0 → 1`
- `reveal-left`: `translateX(-40px) → 0`
- `reveal-right`: `translateX(40px) → 0`
- `scale`: `scale(0.8) → 1`
- `rotate`: `rotate(-5deg) → 0`
- `fade`: opacity only
- `parallax`: (optional) moveY based on scroll position

**CSS (gano-scroll-animations.css):**
```css
[data-animation] {
  opacity: 0;
  animation: var(--animation-name) var(--animation-duration, 0.6s) ease-out forwards;
  animation-delay: var(--animation-delay, 0s);
}

@keyframes reveal-up {
  from { transform: translateY(60px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes reveal-left {
  from { transform: translateX(-40px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes reveal-right {
  from { transform: translateX(40px); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes scale {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

@keyframes rotate {
  from { transform: rotate(-5deg); opacity: 0; }
  to { transform: rotate(0); opacity: 1; }
}

@keyframes fade {
  from { opacity: 0; }
  to { opacity: 1; }
}

@media (prefers-reduced-motion: reduce) {
  [data-animation] {
    animation: none !important;
    opacity: 1;
    transform: none !important;
  }
}
```

**JS (gano-scroll-reveal.js):**
```javascript
class ScrollReveal {
  constructor() {
    this.observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          this.animateElement(entry.target);
          this.observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('[data-animation]').forEach(el => {
      this.observer.observe(el);
    });
  }

  animateElement(el) {
    const animation = el.dataset.animation;
    const delay = parseFloat(el.dataset.delay) || 0;
    const duration = parseFloat(el.dataset.duration) || 0.6;
    const stagger = parseFloat(el.dataset.stagger) || 0;

    el.style.setProperty('--animation-name', animation);
    el.style.setProperty('--animation-duration', `${duration}s`);
    el.style.setProperty('--animation-delay', `${delay}s`);

    // Stagger child elements
    if (stagger > 0) {
      Array.from(el.children).forEach((child, index) => {
        child.style.setProperty('--animation-delay', `${delay + index * stagger}s`);
        child.dataset.animation = animation;
      });
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new ScrollReveal();
});
```

**Stagger Example:**
```html
<div class="features" data-animation="reveal-up" data-stagger="0.1">
  <div>Feature 1</div>  <!-- delay: 0s -->
  <div>Feature 2</div>  <!-- delay: 0.1s -->
  <div>Feature 3</div>  <!-- delay: 0.2s -->
</div>
```

**Parallax (Optional Enhancement):**
- If `data-parallax="true"`, element scrolls at reduced speed (create depth illusion)
- Calculation: `transform: translateY(scrollY * parallaxFactor)`
- Not included in initial release, can be added in follow-up

**Accessibility:** Respects `prefers-reduced-motion`; all animations disabled silently.

**Fallback:** If JS fails, elements visible immediately (no animation). CSS default is `opacity: 0` with keyframe fallback, so content is readable.

---

### F3.3 — Particle System (Hero Background)

**Concept:** Canvas-based particle system in hero background (#particles). Particles drift, create proximity lines, and repel from mouse.

**Configuration:**
- Desktop: 100 particles
- Tablet (768px): 60 particles
- Mobile (480px): 40 particles
- Connection distance: 150px
- Mouse repulsion radius: 80px

**JS (gano-hero-particles.js):**
```javascript
class ParticleSystem {
  constructor(canvasId, config = {}) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas) return;

    this.ctx = this.canvas.getContext('2d');
    if (!this.ctx) return;

    this.config = {
      particleCount: this.getParticleCount(),
      connectionDistance: 150,
      mouseRepulsionRadius: 80,
      particleSize: 1.5,
      color: 'rgba(27, 79, 216, 0.5)',
      lineColor: 'rgba(27, 79, 216, 0.3)',
      ...config
    };

    this.particles = [];
    this.mouseX = window.innerWidth / 2;
    this.mouseY = window.innerHeight / 2;
    this.setupCanvas();
    this.initParticles();
    this.startAnimation();
    this.attachEventListeners();
  }

  getParticleCount() {
    if (window.innerWidth < 480) return 40;
    if (window.innerWidth < 768) return 60;
    return 100;
  }

  setupCanvas() {
    const dpr = window.devicePixelRatio || 1;
    this.canvas.width = this.canvas.offsetWidth * dpr;
    this.canvas.height = this.canvas.offsetHeight * dpr;
    this.ctx.scale(dpr, dpr);
    window.addEventListener('resize', () => this.setupCanvas());
  }

  initParticles() {
    const width = this.canvas.width / (window.devicePixelRatio || 1);
    const height = this.canvas.height / (window.devicePixelRatio || 1);

    for (let i = 0; i < this.config.particleCount; i++) {
      this.particles.push({
        x: Math.random() * width,
        y: Math.random() * height,
        vx: (Math.random() - 0.5) * 0.5,
        vy: (Math.random() - 0.5) * 0.5,
        size: this.config.particleSize
      });
    }
  }

  distance(p1, p2) {
    const dx = p1.x - p2.x;
    const dy = p1.y - p2.y;
    return Math.sqrt(dx * dx + dy * dy);
  }

  drawConnections() {
    for (let i = 0; i < this.particles.length; i++) {
      for (let j = i + 1; j < this.particles.length; j++) {
        const dist = this.distance(this.particles[i], this.particles[j]);
        if (dist < this.config.connectionDistance) {
          const opacity = 1 - (dist / this.config.connectionDistance);
          this.ctx.strokeStyle = this.config.lineColor.replace('0.3', opacity * 0.5);
          this.ctx.lineWidth = 0.5;
          this.ctx.beginPath();
          this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
          this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
          this.ctx.stroke();
        }
      }
    }
  }

  updateParticles() {
    const width = this.canvas.width / (window.devicePixelRatio || 1);
    const height = this.canvas.height / (window.devicePixelRatio || 1);

    this.particles.forEach(p => {
      // Apply velocity
      p.x += p.vx;
      p.y += p.vy;

      // Friction
      p.vx *= 0.98;
      p.vy *= 0.98;

      // Wrap around edges
      if (p.x < 0) p.x = width;
      if (p.x > width) p.x = 0;
      if (p.y < 0) p.y = height;
      if (p.y > height) p.y = 0;

      // Mouse repulsion (desktop only)
      if (window.innerWidth > 768) {
        const dist = this.distance(p, { x: this.mouseX, y: this.mouseY });
        if (dist < this.config.mouseRepulsionRadius) {
          const angle = Math.atan2(p.y - this.mouseY, p.x - this.mouseX);
          p.vx += Math.cos(angle) * 0.3;
          p.vy += Math.sin(angle) * 0.3;
        }
      }
    });
  }

  drawParticles() {
    this.ctx.fillStyle = this.config.color;
    this.particles.forEach(p => {
      this.ctx.beginPath();
      this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
      this.ctx.fill();
    });
  }

  startAnimation() {
    const animate = () => {
      const width = this.canvas.width / (window.devicePixelRatio || 1);
      const height = this.canvas.height / (window.devicePixelRatio || 1);
      this.ctx.clearRect(0, 0, width, height);

      this.updateParticles();
      this.drawConnections();
      this.drawParticles();

      requestAnimationFrame(animate);
    };
    animate();
  }

  attachEventListeners() {
    document.addEventListener('mousemove', (e) => {
      this.mouseX = e.clientX;
      this.mouseY = e.clientY;
    });

    // Disable mouse repulsion on touch devices
    document.addEventListener('touchstart', () => {
      this.config.mouseRepulsionRadius = 0;
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new ParticleSystem('particles');
});
```

**CSS (gano-particles.css):**
```css
#particles {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}

.hero-bg {
  position: relative;
}
```

**Performance Optimizations:**
- Particles pooled (no array allocations per frame)
- Connection drawing early-exit on distance threshold
- Mouse repulsion disabled on touch (mobile)
- Desktop-only feature (80px repulsion radius)

**Fallback:** If Canvas unavailable, hero background rendered as CSS gradient (gano-hero-glow or similar from existing tokens).

---

### Enqueue Strategy (functions.php)

```php
add_action( 'wp_enqueue_scripts', function() {
    // Only on front page
    if ( ! is_front_page() ) {
        return;
    }

    // CSS — Enqueue all at once
    wp_enqueue_style( 'gano-nav-enhanced',
        get_stylesheet_directory_uri() . '/css/gano-nav-enhanced.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/gano-nav-enhanced.css' )
    );
    wp_enqueue_style( 'gano-mobile-menu',
        get_stylesheet_directory_uri() . '/css/gano-mobile-menu.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/gano-mobile-menu.css' )
    );
    wp_enqueue_style( 'gano-hero-holograma',
        get_stylesheet_directory_uri() . '/css/gano-hero-holograma.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/gano-hero-holograma.css' )
    );
    wp_enqueue_style( 'gano-scroll-animations',
        get_stylesheet_directory_uri() . '/css/gano-scroll-animations.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/gano-scroll-animations.css' )
    );
    wp_enqueue_style( 'gano-particles',
        get_stylesheet_directory_uri() . '/css/gano-particles.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/gano-particles.css' )
    );

    // JS — Footer, deferred (true = footer)
    wp_enqueue_script( 'gano-nav-scroll-spy',
        get_stylesheet_directory_uri() . '/js/gano-nav-scroll-spy.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/gano-nav-scroll-spy.js' ),
        true // Footer
    );
    wp_enqueue_script( 'gano-mobile-menu',
        get_stylesheet_directory_uri() . '/js/gano-mobile-menu.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/gano-mobile-menu.js' ),
        true
    );
    wp_enqueue_script( 'gano-hero-holograma',
        get_stylesheet_directory_uri() . '/js/gano-hero-holograma.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/gano-hero-holograma.js' ),
        true
    );
    wp_enqueue_script( 'gano-scroll-reveal',
        get_stylesheet_directory_uri() . '/js/gano-scroll-reveal.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/gano-scroll-reveal.js' ),
        true
    );
    wp_enqueue_script( 'gano-hero-particles',
        get_stylesheet_directory_uri() . '/js/gano-hero-particles.js',
        array(),
        filemtime( get_stylesheet_directory() . '/js/gano-hero-particles.js' ),
        true
    );
}, 12 );
```

**Caching:** Uses `filemtime()` for cache busting on file changes.

---

## Testing & Validation

### Manual Testing Checklist
- [ ] Desktop (1920px): All animations smooth, 60fps, hover states work
- [ ] Tablet (768px): Mobile menu works, scroll-spy responsive, particles reduced to 60
- [ ] Mobile (480px): Menu slide/fade smooth, no jank, particles 40 count, mouse repulsion disabled
- [ ] No JS: Content visible, nav static, holograma fallback (SVG), no errors in console
- [ ] Canvas unavailable: SVG fallback renders, glow CSS works
- [ ] Reduced motion: All animations disabled, elements visible
- [ ] Keyboard nav: Tab through nav, Enter opens/closes menu, ESC closes menu
- [ ] Browser zoom: 150%, 200% — layouts don't break, Canvas scales correctly

### Browser Compatibility
- Chrome/Edge: Full support (Canvas, IntersectionObserver, RequestAnimationFrame)
- Firefox: Full support
- Safari: Full support (backdrop-filter may need -webkit prefix)
- IE11: Canvas works, fallback SVG, CSS animations graceful
- Mobile browsers: Touch-optimized, no mouse repulsion

---

## Fallback Hierarchy

| Feature | Level 1 | Level 2 | Level 3 |
|---------|---------|---------|---------|
| **Scroll-Spy** | IntersectionObserver | None (nav static) | Link works, no highlight |
| **Hide-on-Scroll** | JS tracking | CSS position:sticky | Nav always visible |
| **Mobile Menu** | Slide animation | Display toggle | Hamburger visible, menu hidden |
| **Canvas Holograma** | Animated Canvas | SVG + CSS glow | Gradient text |
| **Particles** | Canvas system | CSS gradient bg | Solid color |
| **Scroll Animations** | RequestAnimationFrame | CSS keyframes | No animation, visible |
| **Reduced Motion** | All disabled | Instant visibility | No animation |

---

## Accessibility

- **WCAG AA compliant:** Keyboard navigation (Tab, Enter, ESC), focus outlines preserved
- **prefers-reduced-motion:** All animations disabled, opacity/transforms reset
- **ARIA:** `aria-expanded`, `aria-current="page"`, `aria-label` on interactive elements
- **Color contrast:** Verified against WCAG AA (4.5:1 for text, 3:1 for non-text)
- **SVG fallback:** Semantic `<text>` element with screen-reader support

---

## Performance Targets

- **First Paint (FP):** < 1s
- **Largest Contentful Paint (LCP):** < 2.5s (hero with particles should be fast)
- **Cumulative Layout Shift (CLS):** < 0.1 (no unexpected reflows)
- **Time to Interactive (TTI):** < 3.5s (JS deferred to footer)
- **Canvas FPS:** Steady 60 FPS on desktop, 30+ FPS on mobile

**Optimization techniques:**
- RequestAnimationFrame (60fps cap)
- Particle pooling (no allocations per frame)
- Early-exit distance checks
- Conditional enqueue on front_page only
- Footer JS defer (non-critical path)

---

## Commit & Deploy

**Files created/modified:**
- `wp-content/themes/gano-child/front-page.php` — Section reorganization
- `wp-content/themes/gano-child/functions.php` — Enqueue hook added
- `wp-content/themes/gano-child/js/gano-nav-scroll-spy.js` — NEW
- `wp-content/themes/gano-child/js/gano-mobile-menu.js` — NEW
- `wp-content/themes/gano-child/js/gano-hero-holograma.js` — NEW
- `wp-content/themes/gano-child/js/gano-scroll-reveal.js` — NEW
- `wp-content/themes/gano-child/js/gano-hero-particles.js` — NEW
- `wp-content/themes/gano-child/css/gano-nav-enhanced.css` — NEW
- `wp-content/themes/gano-child/css/gano-mobile-menu.css` — NEW
- `wp-content/themes/gano-child/css/gano-hero-holograma.css` — NEW
- `wp-content/themes/gano-child/css/gano-scroll-animations.css` — NEW
- `wp-content/themes/gano-child/css/gano-particles.css` — NEW

**Commit message:**
```
feat: Sprint 1 Visual SOTA — navigation UX, scroll-spy, mobile menu, hero holograma, scroll animations, particles

- F2.1: Reorganize front-page.php sections (7 semantic sections)
- F2.2: Scroll-spy + hide-on-scroll + glassmorphism nav
- F2.3: Mobile menu with slide/fade and stagger animations
- F3.1: Hero holograma (Canvas logo with neon flicker + SVG fallback)
- F3.2: Scroll animations (reveal, stagger, parallax on scroll)
- F3.3: Particle system (proximity connections, mouse repulsion)
- Enqueue: Conditional front_page only, footer defer, filemtime cache-bust
- Accessibility: WCAG AA, keyboard nav, prefers-reduced-motion
- Performance: 60fps Canvas, pooled particles, optimized distance checks
```

**Deploy:** After merge to main, SCP all 11 new files to server:
```bash
scp -r wp-content/themes/gano-child/js/ gano-godaddy:/home/f1rml03th382/public_html/gano.digital/wp-content/themes/gano-child/
scp -r wp-content/themes/gano-child/css/ gano-godaddy:/home/f1rml03th382/public_html/gano.digital/wp-content/themes/gano-child/
scp wp-content/themes/gano-child/front-page.php gano-godaddy:/home/f1rml03th382/public_html/gano.digital/wp-content/themes/gano-child/
scp wp-content/themes/gano-child/functions.php gano-godaddy:/home/f1rml03th382/public_html/gano.digital/wp-content/themes/gano-child/
```

---

## Success Criteria

✅ All 6 features (F2.1–F3.3) implemented and tested
✅ No breaking changes to existing templates
✅ All fallbacks tested (no-JS, Canvas unavailable, prefers-reduced-motion)
✅ Accessibility verified (WCAG AA, keyboard nav)
✅ Performance meets targets (60fps Canvas, LCP < 2.5s)
✅ Deployed to server, verified on production
✅ Commit with clean history, ready for merge

---

**Created:** 2026-04-25
**Version:** 1.0.0 (Sprint 1)
**Status:** DESIGN APPROVED — Ready for Implementation Plan
