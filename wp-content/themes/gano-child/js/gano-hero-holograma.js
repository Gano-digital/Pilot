class HologramaController {
  constructor(canvasId, options = {}) {
    this.canvas = document.getElementById(canvasId);
    if (!this.canvas) {
      console.warn('HologramaController: Canvas not found');
      return;
    }

    this.ctx = this.canvas.getContext('2d');
    if (!this.ctx) {
      console.warn('HologramaController: Canvas 2D context unavailable, showing SVG fallback');
      this.showFallbackSVG();
      return;
    }

    this.config = {
      fontSize: 80,
      fontFamily: 'Arial, sans-serif',
      textColor: '#1B4FD8',
      glowColor: '#00C26B',
      glowBlur: 20,
      flickerSpeed: 0.01,
      driftAmount: 0.5,
      ...options
    };

    this.opacity = 1;
    this.drift = { x: 0, y: 0 };
    this.driftVelocity = { x: 0, y: 0 };
    this.frameCount = 0;
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
    const time = this.frameCount * this.config.flickerSpeed;
    this.opacity = 0.7 + Math.sin(time) * 0.3;
  }

  updateDrift() {
    this.driftVelocity.x += (Math.random() - 0.5) * this.config.driftAmount;
    this.driftVelocity.y += (Math.random() - 0.5) * this.config.driftAmount;

    this.drift.x += this.driftVelocity.x;
    this.drift.y += this.driftVelocity.y;

    this.drift.x = Math.max(-2, Math.min(2, this.drift.x));
    this.drift.y = Math.max(-2, Math.min(2, this.drift.y));

    this.driftVelocity.x *= 0.9;
    this.driftVelocity.y *= 0.9;
  }

  startAnimation() {
    const animate = () => {
      const width = this.canvas.width / (window.devicePixelRatio || 1);
      const height = this.canvas.height / (window.devicePixelRatio || 1);

      this.ctx.clearRect(0, 0, width, height);

      this.updateFlicker();
      this.updateDrift();
      this.frameCount++;

      this.drawLogo();

      requestAnimationFrame(animate);
    };
    animate();
  }

  showFallbackSVG() {
    this.canvas.style.display = 'none';

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('class', 'hero-holograma-svg');
    svg.setAttribute('viewBox', '0 0 300 200');

    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', '150');
    text.setAttribute('y', '100');
    text.setAttribute('font-size', '80');
    text.setAttribute('font-weight', 'bold');
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('dominant-baseline', 'central');
    text.setAttribute('fill', '#1B4FD8');
    text.setAttribute('class', 'hero-holograma-svg-text');
    text.textContent = 'GANO';

    svg.appendChild(text);
    this.canvas.parentNode.insertBefore(svg, this.canvas.nextSibling);

    console.log('HologramaController: Fallback SVG rendered');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const controller = new HologramaController('hero-holograma-canvas');
  if (!controller.ctx) {
    console.log('Canvas unavailable, SVG fallback active');
  }
});
