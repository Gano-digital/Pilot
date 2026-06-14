/**
 * Gano Digital — Showcase de Capacidades Futuristas
 * Motor de efectos procedurales adaptados a paleta SOTA
 * Sin dependencias externas. Lazy-init + reduced-motion aware.
 */
(function () {
  'use strict';

  const NS = 'gano-showcase';
  const PALETTE = {
    bg: '#0b1326',
    surface: '#080c18',
    primary: '#c0c1ff',
    tertiary: '#4cd7f6',
    accent: '#8083ff',
    text: '#e6e9f2',
    muted: '#8b93a8'
  };

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── Utilidades ──────────────────────────────────────────────────── */
  function resizeCanvas(cv) {
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const rect = cv.getBoundingClientRect();
    const w = Math.max(32, Math.floor(rect.width * dpr));
    const h = Math.max(32, Math.floor(rect.height * dpr));
    if (cv.width !== w || cv.height !== h) {
      cv.width = w;
      cv.height = h;
    }
    return { w, h, dpr, cssW: rect.width, cssH: rect.height };
  }

  function sim(t, n, seed) {
    const out = new Float32Array(n);
    for (let i = 0; i < n; i++) {
      const x = i / n;
      const bass = Math.sin(t * 0.002 + x * 6) * 0.35 + 0.35;
      const tre = Math.sin(t * 0.0037 + seed + x * 22) * 0.25 + 0.25;
      const pulse = 0.5 + 0.5 * Math.sin(t * 0.0015 + i * 0.08);
      out[i] = Math.max(0, Math.min(1, (bass * (1 - x) + tre * x) * pulse * (0.55 + 0.45 * Math.sin(t * 0.0008 + i))));
    }
    return out;
  }

  function mulberry32(a) {
    return function () {
      let t = a += 0x6D2B79F5;
      t = Math.imul(t ^ t >>> 15, t | 1);
      t ^= t + Math.imul(t ^ t >>> 7, t | 61);
      return ((t ^ t >>> 14) >>> 0) / 4294967296;
    };
  }

  function hexToRgb(hex) {
    const n = parseInt(hex.slice(1), 16);
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
  }

  const primaryRGB = hexToRgb(PALETTE.primary);
  const tertiaryRGB = hexToRgb(PALETTE.tertiary);
  const accentRGB = hexToRgb(PALETTE.accent);

  /* ── Efectos ─────────────────────────────────────────────────────── */
  const effects = {};

  /* 1. WebGL2 Plasma (hero background) */
  effects.plasma = function (canvas) {
    const gl = canvas.getContext('webgl2');
    if (!gl) return;

    const vs = `#version 300 es
    in vec4 a_position;
    void main() { gl_Position = a_position; }`;

    const fs = `#version 300 es
    precision highp float;
    out vec4 o;
    uniform float u_t;
    uniform vec2 u_res;
    void main() {
      vec2 uv = gl_FragCoord.xy / u_res;
      float a = sin(uv.x * 10.0 + u_t) + sin(uv.y * 8.0 - u_t * 0.7);
      float b = sin((uv.x + uv.y) * 12.0 + u_t * 1.3);
      vec3 c = vec3(0.04, 0.05, 0.12) + vec3(0.35 * a + 0.15, 0.25 * b + 0.1, 0.45 * sin(a + b) + 0.2);
      c = mix(c, vec3(0.47, 0.47, 1.0), 0.15); // tinte lavanda sutil
      o = vec4(c, 1.0);
    }`;

    function compile(type, src) {
      const sh = gl.createShader(type);
      gl.shaderSource(sh, src);
      gl.compileShader(sh);
      return sh;
    }

    const prog = gl.createProgram();
    gl.attachShader(prog, compile(gl.VERTEX_SHADER, vs));
    gl.attachShader(prog, compile(gl.FRAGMENT_SHADER, fs));
    gl.linkProgram(prog);
    gl.useProgram(prog);

    const buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, -1, 1, 1, -1, 1, 1]), gl.STATIC_DRAW);
    const loc = gl.getAttribLocation(prog, 'a_position');
    gl.enableVertexAttribArray(loc);
    gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);

    const uT = gl.getUniformLocation(prog, 'u_t');
    const uRes = gl.getUniformLocation(prog, 'u_res');
    let t0 = performance.now();
    let raf;

    function frame(t) {
      resizeCanvas(canvas);
      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.uniform1f(uT, (t - t0) * 0.001);
      gl.uniform2f(uRes, canvas.width, canvas.height);
      gl.drawArrays(gl.TRIANGLES, 0, 6);
      raf = requestAnimationFrame(frame);
    }
    if (!reducedMotion) raf = requestAnimationFrame(frame);

    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 2. Partículas conectadas (infraestructura) */
  effects.particles = function (canvas) {
    const ctx = canvas.getContext('2d');
    const N = 55;
    let pts = [];
    let t0 = performance.now();
    let raf;

    function reset() {
      const { w, h } = resizeCanvas(canvas);
      pts = [];
      for (let i = 0; i < N; i++) {
        pts.push({
          x: Math.random() * w, y: Math.random() * h,
          vx: (Math.random() - 0.5) * 1.0, vy: (Math.random() - 0.5) * 1.0
        });
      }
    }
    reset();
    window.addEventListener('resize', reset);

    function frame(t) {
      const { w, h } = resizeCanvas(canvas);
      ctx.fillStyle = '#080c18';
      ctx.fillRect(0, 0, w, h);
      const tt = (t - t0) * 0.001;

      for (const p of pts) {
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;
      }

      for (let i = 0; i < pts.length; i++) {
        for (let j = i + 1; j < pts.length; j++) {
          const a = pts[i], b = pts[j];
          const d = Math.hypot(a.x - b.x, a.y - b.y);
          if (d < 100) {
            ctx.strokeStyle = 'rgba(128,131,255,' + (1 - d / 100) * 0.4 + ')';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(a.x, a.y);
            ctx.lineTo(b.x, b.y);
            ctx.stroke();
          }
        }
      }

      for (const p of pts) {
        ctx.fillStyle = 'hsl(' + ((p.x + p.y + tt * 40) % 360) + ',70%,65%)';
        ctx.beginPath();
        ctx.moveTo(p.x, p.y - 5);
        ctx.lineTo(p.x + 4, p.y + 3);
        ctx.lineTo(p.x - 4, p.y + 3);
        ctx.closePath();
        ctx.fill();
      }
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); window.removeEventListener('resize', reset); };
  };

  /* 3. Espectro radial (rendimiento) */
  effects.radial = function (canvas) {
    const ctx = canvas.getContext('2d');
    let t0 = performance.now();
    let raf;

    function frame(t) {
      const { w, h } = resizeCanvas(canvas);
      ctx.fillStyle = '#040508';
      ctx.fillRect(0, 0, w, h);
      const s = sim(t, 64, 2);
      const cx = w / 2, cy = h / 2;
      const R = Math.min(w, h) * 0.28;

      for (let i = 0; i < s.length; i++) {
        const a = (i / s.length) * Math.PI * 2;
        const len = R + s[i] * Math.min(w, h) * 0.38;
        ctx.strokeStyle = 'hsla(' + (200 + i * 4) + ',85%,65%,' + (0.25 + s[i] * 0.65) + ')';
        ctx.lineWidth = 3 + 4 * s[i];
        ctx.beginPath();
        ctx.moveTo(cx + Math.cos(a) * R * 0.15, cy + Math.sin(a) * R * 0.15);
        ctx.lineTo(cx + Math.cos(a) * len, cy + Math.sin(a) * len);
        ctx.stroke();
      }
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 4. Osciloscopio bloom (monitoreo) */
  effects.scope = function (canvas) {
    const ctx = canvas.getContext('2d');
    let t0 = performance.now();
    let raf;

    function frame(t) {
      const { w, h } = resizeCanvas(canvas);
      ctx.fillStyle = 'rgba(2,4,10,.35)';
      ctx.fillRect(0, 0, w, h);
      ctx.lineJoin = 'round';
      ctx.strokeStyle = '#4cd7f6';
      ctx.lineWidth = 3;
      ctx.shadowColor = '#4cd7f6';
      ctx.shadowBlur = 18;
      ctx.beginPath();
      for (let i = 0; i < w; i++) {
        const y = h * 0.5 + Math.sin(i * 0.04 + t * 0.003) * h * 0.22 + Math.sin(i * 0.015 + t * 0.0017) * h * 0.08;
        if (i === 0) ctx.moveTo(i, y); else ctx.lineTo(i, y);
      }
      ctx.stroke();
      ctx.shadowBlur = 0;
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 5. Icosaedro wireframe (seguridad) */
  effects.ico = function (canvas) {
    const ctx = canvas.getContext('2d');
    const phi = (1 + Math.sqrt(5)) / 2;
    const raw = [[0, 1, phi], [0, -1, phi], [0, 1, -phi], [0, -1, -phi],
      [1, phi, 0], [-1, phi, 0], [1, -phi, 0], [-1, -phi, 0],
      [phi, 0, 1], [phi, 0, -1], [-phi, 0, 1], [-phi, 0, -1]];
    const verts = raw.map(v => { const m = Math.hypot(v[0], v[1], v[2]); return [v[0] / m, v[1] / m, v[2] / m]; });
    const edges = [];
    for (let i = 0; i < verts.length; i++) {
      for (let j = i + 1; j < verts.length; j++) {
        const d = Math.hypot(verts[i][0] - verts[j][0], verts[i][1] - verts[j][1], verts[i][2] - verts[j][2]);
        if (d > 0.98 && d < 1.12) edges.push([i, j]);
      }
    }
    let ang = 0;
    let raf;

    function proj(X, Y, Z, cx, cy) {
      const co = Math.cos(ang), si = Math.sin(ang);
      let x1 = X * co - Z * si, z1 = X * si + Z * co;
      const c2 = Math.cos(ang * 0.55), s2 = Math.sin(ang * 0.55);
      let y1 = Y * c2 - z1 * s2; z1 = Y * s2 + z1 * c2;
      const f = 220 / (220 + z1);
      return [cx + x1 * 95 * f, cy + y1 * 95 * f];
    }

    function frame(t) {
      const { w, h } = resizeCanvas(canvas);
      ctx.fillStyle = '#03040a';
      ctx.fillRect(0, 0, w, h);
      ang = t * 0.00055;
      const cx = w / 2, cy = h / 2;
      edges.forEach(([a, b]) => {
        const p1 = proj(verts[a][0], verts[a][1], verts[a][2], cx, cy);
        const p2 = proj(verts[b][0], verts[b][1], verts[b][2], cx, cy);
        ctx.strokeStyle = 'rgba(192,193,255,.5)';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(p1[0], p1[1]);
        ctx.lineTo(p2[0], p2[1]);
        ctx.stroke();
      });
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 8. Julia + anillo caleidoscópico (CTA background) */
  effects.julia = function (canvas) {
    const ctx = canvas.getContext('2d');
    let raf;

    function frame(t) {
      const { w, h } = resizeCanvas(canvas);
      const cx = w / 2, cy = h / 2;
      const R = Math.min(w, h) * 0.35;
      ctx.fillStyle = '#020308';
      ctx.fillRect(0, 0, w, h);

      const img = ctx.createImageData(w, h);
      const d = img.data;
      const cr = -0.8, ci = 0.156;

      for (let py = 0; py < h; py++) {
        for (let px = 0; px < w; px++) {
          const dx = px - cx, dy = py - cy;
          const p = (py * w + px) * 4;
          if (dx * dx + dy * dy > R * R) {
            d[p] = 10; d[p + 1] = 10; d[p + 2] = 20; d[p + 3] = 255;
            continue;
          }
          let zx = (px - cx) / R * 1.4, zy = (py - cy) / R * 1.4;
          let i = 0;
          for (; i < 48 && zx * zx + zy * zy < 4; i++) {
            const x2 = zx * zx - zy * zy + cr; zy = 2 * zx * zy + ci; zx = x2;
          }
          const v = i * 5;
          d[p] = v;
          d[p + 1] = 200 - v;
          d[p + 2] = 180;
          d[p + 3] = 255;
        }
      }
      ctx.putImageData(img, 0, 0);

      const n = 8;
      for (let s = 0; s < n; s++) {
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(s * Math.PI * 2 / n + t * 0.0005);
        ctx.strokeStyle = 'rgba(250,204,21,0.35)';
        ctx.lineWidth = 2;
        for (let r = 40; r < Math.min(w, h) * 0.48; r += 12)
          ctx.strokeRect(r * 0.1, r * 0.2, r, r * 0.15);
        ctx.restore();
      }
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 9. Metabolas triples (transición) */
  effects.meta = function (canvas) {
    const ctx = canvas.getContext('2d');
    const balls = Array.from({ length: 3 }, (_, i) => ({
      x: Math.random(), y: Math.random(),
      vx: (Math.random() - 0.5) * 0.002,
      vy: (Math.random() - 0.5) * 0.002,
      r: 0.08 + Math.random() * 0.12
    }));
    let raf;

    function frame() {
      const { w, h } = resizeCanvas(canvas);
      const img = ctx.createImageData(w, h);
      const d = img.data;
      balls.forEach(b => {
        b.x += b.vx; b.y += b.vy;
        if (b.x < 0 || b.x > 1) b.vx *= -1;
        if (b.y < 0 || b.y > 1) b.vy *= -1;
      });

      for (let py = 0; py < h; py++) {
        for (let px = 0; px < w; px++) {
          let sum = 0;
          const fx = px / w, fy = py / h;
          balls.forEach(b => {
            const dx = fx - b.x, dy = fy - b.y;
            sum += b.r * b.r / (dx * dx + dy * dy + 1e-5);
          });
          const p = (py * w + px) * 4;
          const th = 4.2;
          const u = sum > th ? 1 : (sum - th * 0.7) / (th * 0.3 + 1e-6);
          d[p] = 40 + u * 180;
          d[p + 1] = 20 + u * 60;
          d[p + 2] = 80 + u * 120;
          d[p + 3] = 255;
        }
      }
      ctx.putImageData(img, 0, 0);
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* 12. Vúmetros SVG (estado del sistema) */
  effects.vu = function (container) {
    const NS = 'http://www.w3.org/2000/svg';
    const svg = document.createElementNS(NS, 'svg');
    svg.setAttribute('viewBox', '0 0 800 200');
    svg.setAttribute('width', '100%');
    svg.setAttribute('height', '200');
    const needles = [];

    for (let i = 0; i < 8; i++) {
      const g = document.createElementNS(NS, 'g');
      g.setAttribute('transform', 'translate(' + (80 + i * 90) + ',150)');
      const arc = document.createElementNS(NS, 'path');
      arc.setAttribute('d', 'M -40 0 A 40 40 0 0 1 40 0');
      arc.setAttribute('fill', 'none');
      arc.setAttribute('stroke', '#334155');
      arc.setAttribute('stroke-width', '4');
      const needle = document.createElementNS(NS, 'line');
      needle.setAttribute('x1', '0');
      needle.setAttribute('y1', '0');
      needle.setAttribute('x2', '0');
      needle.setAttribute('y2', '-52');
      needle.setAttribute('stroke', '#f472b6');
      needle.setAttribute('stroke-width', '3');
      needle.setAttribute('stroke-linecap', 'round');
      needles.push(needle);
      g.appendChild(arc);
      g.appendChild(needle);
      svg.appendChild(g);
    }
    container.appendChild(svg);

    let raf;
    function frame(t) {
      const s = sim(t, 8, 9);
      needles.forEach((ln, idx) => {
        const ang = -60 + s[idx] * 120;
        ln.setAttribute('transform', 'rotate(' + ang + ')');
      });
      raf = requestAnimationFrame(frame);
    }

    if (!reducedMotion) raf = requestAnimationFrame(frame);
    return function cleanup() { cancelAnimationFrame(raf); };
  };

  /* ── Motor de inicialización ─────────────────────────────────────── */
  const observers = [];

  function initEffect(el) {
    const effect = el.dataset.gsEffect;
    if (!effect || !effects[effect]) return;

    // Evitar doble init
    if (el.dataset.gsInit === '1') return;
    el.dataset.gsInit = '1';

    const cleanup = effects[effect](el);
    if (cleanup) {
      el._gsCleanup = cleanup;
    }
  }

  function lazyInit() {
    const nodes = document.querySelectorAll('[data-gs-effect]');
    if (!('IntersectionObserver' in window)) {
      nodes.forEach(initEffect);
      return;
    }

    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          initEffect(entry.target);
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '120px' });

    nodes.forEach(function (node) {
      io.observe(node);
    });
    observers.push(io);
  }

  /* ── Bootstrap ───────────────────────────────────────────────────── */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', lazyInit);
  } else {
    lazyInit();
  }

  // Expose para debugging
  window.GanoShowcase = { effects, PALETTE, reducedMotion };
})();
