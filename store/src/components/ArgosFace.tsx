import { useEffect, useRef, useState } from 'react';

/**
 * ArgosFace — el rostro robótico animado de Argos.
 *
 * Una silueta tipo robot compacto (inspiración Wall-E) con dos grandes ojos
 * blancos que:
 *  - Siguen el puntero del mouse (o se mueven solos si no hay puntero).
 *  - Parpadean ocasionalmente con un movimiento natural.
 *  - Tienen un leve "idle wander" para sentirse vivo.
 *
 * Respeta `prefers-reduced-motion`: si el usuario lo pide, los ojos quedan
 * centrados y sin animación.
 *
 * Construido con SVG + estado React (sin dependencias). Usa colores semánticos
 * vía `currentColor` para el cuerpo, de modo que hereda el color del contenedor.
 */

interface ArgosFaceProps {
  /** Tamaño en px del lienzo cuadrado. */
  size?: number;
  /** Si true, los ojos miran al puntero global; si false, solo idle wander. */
  trackPointer?: boolean;
  /** Color de los ojos (blanco por defecto). */
  eyeColor?: string;
  className?: string;
}

const prefersReducedMotion = () =>
  typeof window !== 'undefined' &&
  window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

export function ArgosFace({
  size = 40,
  trackPointer = true,
  eyeColor = '#FFFFFF',
  className,
}: ArgosFaceProps) {
  const wrapRef = useRef<HTMLDivElement>(null);
  // Desplazamiento de pupilas, normalizado a [-1, 1].
  const [gaze, setGaze] = useState({ x: 0, y: 0 });
  const [blink, setBlink] = useState(false);
  const reduced = useRef(false);

  useEffect(() => {
    reduced.current = prefersReducedMotion();
  }, []);

  // Seguimiento del puntero: calcula el ángulo desde el centro del rostro hacia
  // el mouse y desplaza las pupilas en esa dirección (con tope).
  useEffect(() => {
    if (!trackPointer || reduced.current) return;
    let raf = 0;
    const onMove = (e: MouseEvent) => {
      cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        const el = wrapRef.current;
        if (!el) return;
        const r = el.getBoundingClientRect();
        const cx = r.left + r.width / 2;
        const cy = r.top + r.height / 2;
        const dx = e.clientX - cx;
        const dy = e.clientY - cy;
        const dist = Math.hypot(dx, dy) || 1;
        // Normaliza y aplica un tope suave para que las pupilas no se salgan.
        const max = 1;
        setGaze({
          x: Math.max(-max, Math.min(max, dx / (dist + 60))),
          y: Math.max(-max, Math.min(max, dy / (dist + 60))),
        });
      });
    };
    window.addEventListener('mousemove', onMove);
    return () => {
      window.removeEventListener('mousemove', onMove);
      cancelAnimationFrame(raf);
    };
  }, [trackPointer]);

  // Idle wander: si no hay seguimiento de puntero, mueve los ojos suavemente.
  useEffect(() => {
    if (trackPointer || reduced.current) return;
    let raf = 0;
    let t = 0;
    const loop = () => {
      t += 0.02;
      setGaze({ x: Math.sin(t) * 0.6, y: Math.cos(t * 0.7) * 0.4 });
      raf = requestAnimationFrame(loop);
    };
    raf = requestAnimationFrame(loop);
    return () => cancelAnimationFrame(raf);
  }, [trackPointer]);

  // Parpadeo natural: intervalos aleatorios entre 2.5s y 6s.
  useEffect(() => {
    if (reduced.current) return;
    let timeout: ReturnType<typeof setTimeout>;
    const scheduleBlink = () => {
      const delay = 2500 + Math.random() * 3500;
      timeout = setTimeout(() => {
        setBlink(true);
        setTimeout(() => setBlink(false), 140);
        scheduleBlink();
      }, delay);
    };
    scheduleBlink();
    return () => clearTimeout(timeout);
  }, []);

  // Geometría: dos ojos (cabezas tipo binocular de Wall-E). En un viewBox 100x100.
  const pupilDX = gaze.x * 5; // recorrido horizontal de la pupila
  const pupilDY = gaze.y * 4; // recorrido vertical
  const eyeRY = blink ? 1.5 : 17; // ojo "cerrado" al parpadear

  return (
    <div ref={wrapRef} className={className} style={{ width: size, height: size }}>
      <svg
        viewBox="0 0 100 100"
        width={size}
        height={size}
        role="img"
        aria-label="Argos"
        style={{ display: 'block', overflow: 'visible' }}
      >
        {/* Cuerpo / casco del robot — usa currentColor para heredar el tema */}
        <g fill="currentColor">
          {/* Visera que une los dos ojos (estilo binocular) */}
          <rect x="14" y="30" width="72" height="40" rx="20" />
          {/* Cuello / base */}
          <rect x="40" y="66" width="20" height="10" rx="4" opacity="0.85" />
          {/* Pequeñas antenas para personalidad */}
          <rect x="30" y="20" width="3" height="12" rx="1.5" opacity="0.7" />
          <circle cx="31.5" cy="19" r="3" opacity="0.7" />
          <rect x="67" y="20" width="3" height="12" rx="1.5" opacity="0.7" />
          <circle cx="68.5" cy="19" r="3" opacity="0.7" />
        </g>

        {/* Ojos: esclerótica blanca grande */}
        <g
          style={{
            transition: blink ? 'none' : 'all 0.12s ease-out',
          }}
        >
          {/* Ojo izquierdo */}
          <ellipse cx="36" cy="49" rx="13" ry={eyeRY} fill={eyeColor} />
          {/* Ojo derecho */}
          <ellipse cx="64" cy="49" rx="13" ry={eyeRY} fill={eyeColor} />

          {/* Pupilas (solo visibles cuando el ojo está abierto) */}
          {!blink && (
            <g style={{ transition: 'transform 0.08s ease-out' }}>
              <circle
                cx={36 + pupilDX}
                cy={49 + pupilDY}
                r="6.5"
                fill="#0A0A0A"
              />
              <circle
                cx={64 + pupilDX}
                cy={49 + pupilDY}
                r="6.5"
                fill="#0A0A0A"
              />
              {/* Brillo en las pupilas — le da el toque "vivo" */}
              <circle cx={36 + pupilDX - 2} cy={49 + pupilDY - 2} r="1.8" fill="#FFFFFF" />
              <circle cx={64 + pupilDX - 2} cy={49 + pupilDY - 2} r="1.8" fill="#FFFFFF" />
            </g>
          )}
        </g>
      </svg>
    </div>
  );
}
