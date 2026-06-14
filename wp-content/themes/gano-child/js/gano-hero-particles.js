/**
 * ParticleSystem - Canvas-based particle animation with proximity connections
 *
 * Features:
 * - Responsive particle count by viewport breakpoint
 * - Physics: gravity, damping, wall bounce
 * - Proximity connections between nearby particles
 * - Mouse repulsion (desktop only)
 * - Touch detection to disable mouse effects
 */

class ParticleSystem {
  constructor() {
    // Color palette from gano-child theme
    this.colors = ['#1B4FD8', '#00C26B', '#FF6B35', '#D4AF37'];

    // Physics constants
    this.gravity = 0.05;
    this.damping = 0.98;
    this.bounceDamping = 0.8;
    this.maxVelocity = 5;
    this.connectionDistance = 150;
    this.connectionColor = 'rgba(27, 79, 216, 0.15)';
    this.connectionWidth = 0.5;

    // Mouse repulsion
    this.repulsionRadius = 100;
    this.repulsionForce = 0.3;
    this.mouseX = -1000;
    this.mouseY = -1000;
    this.isTouchDevice = false;

    // Particle pool
    this.particles = [];
    this.particleCount = 100;

    // Animation state
    this.animationFrameId = null;
    this.resizeTimeout = null;

    // Setup
    this.canvas = document.getElementById('particles');
    if (!this.canvas) {
      console.error('ParticleSystem: canvas#particles not found');
      return;
    }

    this.ctx = this.canvas.getContext('2d');
    if (!this.ctx) {
      console.error('Canvas 2D context unavailable');
      return;
    }
    this.setupCanvas();
    this.createParticles(this.getParticleCount());
    this.attachMouseListener();
    this.startAnimation();
  }

  /**
   * Setup canvas with devicePixelRatio support and resize handling
   */
  setupCanvas() {
    const dpr = window.devicePixelRatio || 1;

    const resizeCanvas = () => {
      const rect = this.canvas.getBoundingClientRect();
      const width = window.innerWidth;
      const height = window.innerHeight;

      // Set internal resolution for crisp rendering
      this.canvas.width = width * dpr;
      this.canvas.height = height * dpr;

      // Scale context to account for DPR
      this.ctx.scale(dpr, dpr);

      // CSS size
      this.canvas.style.width = width + 'px';
      this.canvas.style.height = height + 'px';
    };

    resizeCanvas();

    // Resize listener
    window.addEventListener('resize', () => {
      clearTimeout(this.resizeTimeout);
      this.resizeTimeout = setTimeout(() => {
        resizeCanvas();

        // Update particle count based on new viewport
        const newCount = this.getParticleCount();
        if (newCount !== this.particleCount) {
          this.particleCount = newCount;
          this.createParticles(newCount);
        }
      }, 100);
    });
  }

  /**
   * Get particle count based on viewport width breakpoint
   */
  getParticleCount() {
    const width = window.innerWidth;

    if (width >= 1024) {
      return 100; // Desktop
    } else if (width >= 768) {
      return 60; // Tablet
    } else {
      return 40; // Mobile
    }
  }

  /**
   * Create particles with random positions and velocities
   */
  createParticles(count) {
    this.particles = [];

    for (let i = 0; i < count; i++) {
      this.particles.push({
        x: Math.random() * this.canvas.width,
        y: Math.random() * this.canvas.height,
        vx: (Math.random() - 0.5) * 1,
        vy: (Math.random() - 0.5) * 1,
        radius: Math.random() * 1.5 + 1.5,
        color: this.colors[Math.floor(Math.random() * this.colors.length)],
        repelForce: 0
      });
    }
  }

  /**
   * Update particle positions and apply physics
   */
  updateParticles() {
    const width = this.canvas.width;
    const height = this.canvas.height;

    for (let particle of this.particles) {
      // Apply gravity
      particle.vy += this.gravity;

      // Apply damping (friction)
      particle.vx *= this.damping;
      particle.vy *= this.damping;

      // Clamp velocity to prevent accumulation
      particle.vx = Math.max(-this.maxVelocity, Math.min(this.maxVelocity, particle.vx));
      particle.vy = Math.max(-this.maxVelocity, Math.min(this.maxVelocity, particle.vy));

      // Update position
      particle.x += particle.vx;
      particle.y += particle.vy;

      // Bounce on walls
      if (particle.x - particle.radius < 0) {
        particle.x = particle.radius;
        particle.vx = -particle.vx * this.bounceDamping;
      } else if (particle.x + particle.radius > width) {
        particle.x = width - particle.radius;
        particle.vx = -particle.vx * this.bounceDamping;
      }

      if (particle.y - particle.radius < 0) {
        particle.y = particle.radius;
        particle.vy = -particle.vy * this.bounceDamping;
      } else if (particle.y + particle.radius > height) {
        particle.y = height - particle.radius;
        particle.vy = -particle.vy * this.bounceDamping;
      }

      // Reset repel force for next frame
      particle.repelForce = 0;
    }
  }

  /**
   * Draw lines between nearby particles (proximity connections)
   */
  drawConnections() {
    this.ctx.strokeStyle = this.connectionColor;
    this.ctx.lineWidth = this.connectionWidth;

    for (let i = 0; i < this.particles.length; i++) {
      for (let j = i + 1; j < this.particles.length; j++) {
        const p1 = this.particles[i];
        const p2 = this.particles[j];

        const dx = p2.x - p1.x;
        const dy = p2.y - p1.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance < this.connectionDistance) {
          this.ctx.beginPath();
          this.ctx.moveTo(p1.x, p1.y);
          this.ctx.lineTo(p2.x, p2.y);
          this.ctx.stroke();
        }
      }
    }
  }

  /**
   * Draw particles with glow effect
   */
  drawParticles() {
    for (let particle of this.particles) {
      // Draw glow (shadow effect)
      this.ctx.shadowColor = particle.color;
      this.ctx.shadowBlur = 8;

      // Draw particle circle
      this.ctx.fillStyle = particle.color;
      this.ctx.beginPath();
      this.ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
      this.ctx.fill();
    }

    // Clear shadow for next frame
    this.ctx.shadowBlur = 0;
  }

  /**
   * Apply mouse repulsion to nearby particles
   */
  applyMouseRepulsion() {
    // Only apply if not a touch device
    if (this.isTouchDevice) {
      return;
    }

    for (let particle of this.particles) {
      const dx = particle.x - this.mouseX;
      const dy = particle.y - this.mouseY;
      const distance = Math.sqrt(dx * dx + dy * dy);

      if (distance < this.repulsionRadius && distance > 0) {
        // Calculate repulsion force based on distance
        const force = (this.repulsionRadius - distance) * this.repulsionForce;

        // Normalize direction and apply force
        const angle = Math.atan2(dy, dx);
        particle.vx += Math.cos(angle) * force;
        particle.vy += Math.sin(angle) * force;
      }
    }
  }

  /**
   * Main animation loop
   */
  animate() {
    // Clear canvas
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

    // Update physics
    this.updateParticles();
    this.applyMouseRepulsion();

    // Draw
    this.drawConnections();
    this.drawParticles();

    // Continue animation loop
    this.animationFrameId = requestAnimationFrame(() => this.animate());
  }

  /**
   * Start the animation loop
   */
  startAnimation() {
    this.animate();
  }

  /**
   * Attach mouse listener for position tracking and touch detection
   */
  attachMouseListener() {
    document.addEventListener('mousemove', (e) => {
      this.mouseX = e.clientX;
      this.mouseY = e.clientY;
    });

    // Touch detection
    document.addEventListener('touchstart', () => {
      this.isTouchDevice = true;
    });

    document.addEventListener('mouseleave', () => {
      this.mouseX = -1000;
      this.mouseY = -1000;
    });
  }

  /**
   * Cleanup animation frame and timers on destroy
   */
  destroy() {
    if (this.animationFrameId) {
      cancelAnimationFrame(this.animationFrameId);
    }
    if (this.resizeTimeout) {
      clearTimeout(this.resizeTimeout);
    }
  }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  new ParticleSystem();
});
