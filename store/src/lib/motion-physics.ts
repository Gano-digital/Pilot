/**
 * motion-physics — Capa de movimiento orgánico reutilizable para Gano Digital.
 *
 * Filosofía UX: cada elemento "tiene físicas propias". Los elementos entran
 * con resorte (spring), flotan sutilmente, reaccionan al puntero con inclinación
 * 3D y se desplazan con parallax al hacer scroll. Toda la energía visual se
 * construye SOLO con `transform` y `opacity` para mantener 60fps y no disparar
 * reflows de layout.
 *
 * Reglas de rendimiento y accesibilidad:
 *  - `prefers-reduced-motion: reduce` → se neutraliza todo el movimiento.
 *  - SSR-safe: nada lee `window`/`document` durante el render; los hooks de
 *    efecto se ejecutan solo en cliente.
 *  - Springs compartidos para coherencia física en todo el sitio.
 */
import { useEffect, useRef, useState, type RefObject } from 'react';
import {
  useMotionValue,
  useSpring,
  useTransform,
  useScroll,
  type MotionValue,
  type Variants,
  type Transition,
} from 'motion/react';

/* ──────────────────────────────────────────────────────────────────────────
 * Configuración de resortes compartida — "física" del sitio.
 * Un único vocabulario de springs garantiza que todo se sienta del mismo mundo.
 * ────────────────────────────────────────────────────────────────────────── */
export const SPRING = {
  /** Reacción inmediata pero suave — para tilt al puntero. */
  snappy: { stiffness: 220, damping: 22, mass: 0.6 } as const,
  /** Asentamiento natural con leve rebote — para entradas. */
  settle: { stiffness: 140, damping: 18, mass: 0.9 } as const,
  /** Muy suave, perezoso — para parallax y flotación. */
  drift: { stiffness: 60, damping: 20, mass: 1.1 } as const,
} as const;

/* ──────────────────────────────────────────────────────────────────────────
 * prefers-reduced-motion — fuente de verdad reactiva, SSR-safe.
 * ────────────────────────────────────────────────────────────────────────── */
export function useReducedMotion(): boolean {
  const [reduced, setReduced] = useState(false);

  useEffect(() => {
    if (typeof window === 'undefined' || !window.matchMedia) return;
    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    setReduced(mq.matches);
    const onChange = (e: MediaQueryListEvent) => setReduced(e.matches);
    mq.addEventListener('change', onChange);
    return () => mq.removeEventListener('change', onChange);
  }, []);

  return reduced;
}

/* ──────────────────────────────────────────────────────────────────────────
 * Variantes de entrada con resorte — para usar con whileInView.
 * Devuelve variantes neutralizadas si el usuario pide menos movimiento.
 * ────────────────────────────────────────────────────────────────────────── */
type Direction = 'up' | 'down' | 'left' | 'right' | 'none';

export function useSpringEntrance(
  direction: Direction = 'up',
  distance = 28,
): { variants: Variants; reduced: boolean } {
  const reduced = useReducedMotion();

  if (reduced) {
    return {
      reduced,
      variants: {
        hidden: { opacity: 0 },
        visible: { opacity: 1, transition: { duration: 0.3 } },
      },
    };
  }

  const offset: Record<Direction, { x?: number; y?: number }> = {
    up: { y: distance },
    down: { y: -distance },
    left: { x: distance },
    right: { x: -distance },
    none: {},
  };

  const transition: Transition = { type: 'spring', ...SPRING.settle };

  return {
    reduced,
    variants: {
      hidden: { opacity: 0, ...offset[direction], scale: 0.985 },
      visible: { opacity: 1, x: 0, y: 0, scale: 1, transition },
    },
  };
}

/** Contenedor que escalona la entrada de sus hijos. */
export function useStaggerContainer(stagger = 0.08): Variants {
  return {
    hidden: {},
    visible: { transition: { staggerChildren: stagger, delayChildren: 0.04 } },
  };
}

/* ──────────────────────────────────────────────────────────────────────────
 * Parallax ligado al scroll — devuelve un MotionValue de desplazamiento Y.
 * `strength` negativo sube el elemento al hacer scroll (efecto profundidad).
 * ────────────────────────────────────────────────────────────────────────── */
export function useParallax(
  ref: RefObject<HTMLElement | null>,
  strength = 60,
): MotionValue<number> {
  const reduced = useReducedMotion();
  const { scrollYProgress } = useScroll({
    target: ref,
    offset: ['start end', 'end start'],
  });
  const raw = useTransform(scrollYProgress, [0, 1], [strength, -strength]);
  const smooth = useSpring(raw, SPRING.drift);
  const zero = useMotionValue(0);
  return reduced ? zero : smooth;
}

/* ──────────────────────────────────────────────────────────────────────────
 * Tilt 3D + elevación reactiva al puntero.
 * Devuelve handlers y MotionValues listos para aplicar a un elemento con
 * `transform-style: preserve-3d`. El cálculo es puro transform (rotateX/Y,
 * translateZ) — sin tocar layout.
 * ────────────────────────────────────────────────────────────────────────── */
export function usePointerTilt(maxTiltDeg = 8) {
  const reduced = useReducedMotion();

  const rx = useMotionValue(0);
  const ry = useMotionValue(0);
  const rotateX = useSpring(rx, SPRING.snappy);
  const rotateY = useSpring(ry, SPRING.snappy);

  // Posición normalizada del puntero (-0.5..0.5) para halos/reflejos opcionales.
  const px = useMotionValue(0.5);
  const py = useMotionValue(0.5);
  const pointerX = useSpring(px, SPRING.snappy);
  const pointerY = useSpring(py, SPRING.snappy);

  function onPointerMove(e: React.PointerEvent<HTMLElement>) {
    if (reduced) return;
    const el = e.currentTarget;
    const rect = el.getBoundingClientRect();
    const nx = (e.clientX - rect.left) / rect.width; // 0..1
    const ny = (e.clientY - rect.top) / rect.height; // 0..1
    px.set(nx);
    py.set(ny);
    ry.set((nx - 0.5) * 2 * maxTiltDeg); // izquierda/derecha → rotateY
    rx.set(-(ny - 0.5) * 2 * maxTiltDeg); // arriba/abajo → rotateX
  }

  function onPointerLeave() {
    rx.set(0);
    ry.set(0);
    px.set(0.5);
    py.set(0.5);
  }

  return {
    reduced,
    handlers: { onPointerMove, onPointerLeave },
    rotateX,
    rotateY,
    pointerX,
    pointerY,
  };
}

/* ──────────────────────────────────────────────────────────────────────────
 * Reacción global al puntero — para fondos/objetos del hero que "siguen" el
 * mouse. Devuelve coordenadas suavizadas en rango -1..1 relativas a la ventana.
 * ────────────────────────────────────────────────────────────────────────── */
export function usePointerField(intensity = 1) {
  const reduced = useReducedMotion();
  const x = useMotionValue(0);
  const y = useMotionValue(0);
  const sx = useSpring(x, SPRING.drift);
  const sy = useSpring(y, SPRING.drift);

  useEffect(() => {
    if (reduced || typeof window === 'undefined') return;
    let raf = 0;
    const onMove = (e: PointerEvent) => {
      cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        const nx = (e.clientX / window.innerWidth - 0.5) * 2 * intensity;
        const ny = (e.clientY / window.innerHeight - 0.5) * 2 * intensity;
        x.set(nx);
        y.set(ny);
      });
    };
    window.addEventListener('pointermove', onMove, { passive: true });
    return () => {
      window.removeEventListener('pointermove', onMove);
      cancelAnimationFrame(raf);
    };
  }, [reduced, intensity, x, y]);

  return { x: sx, y: sy, reduced };
}

/* ──────────────────────────────────────────────────────────────────────────
 * Flotación continua determinista — para objetos "ingrávidos".
 * Útil cuando se quiere un float que no dependa de @keyframes globales.
 * ────────────────────────────────────────────────────────────────────────── */
export function useFloat(amplitude = 8, durationSec = 6, delaySec = 0) {
  const reduced = useReducedMotion();
  if (reduced) {
    return { animate: {}, transition: {} as Transition };
  }
  return {
    animate: { y: [0, -amplitude, 0] },
    transition: {
      duration: durationSec,
      delay: delaySec,
      repeat: Infinity,
      ease: 'easeInOut' as const,
    } satisfies Transition,
  };
}

/* Marca el elemento solo durante la interacción para no fijar capas de GPU
 * permanentemente (evita coste de memoria de compositing en exceso). */
export function useWillChange() {
  const ref = useRef<HTMLElement | null>(null);
  return ref;
}
