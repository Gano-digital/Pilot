import { useEffect, useRef, useState } from 'react';
import { useInView } from 'motion/react';

interface UseCountUpOptions {
  /** Valor final al que cuenta. */
  target: number;
  /** Duración de la animación en ms. */
  duration?: number;
  /** Solo animar una vez al entrar en viewport. */
  once?: boolean;
}

/**
 * Cuenta de 0 al valor objetivo cuando el elemento entra en viewport.
 * Devuelve [ref, value]. Aplica ease-out cúbico para un final suave.
 * Respeta prefers-reduced-motion (salta directo al valor final).
 */
export function useCountUp({ target, duration = 1200, once = true }: UseCountUpOptions) {
  const ref = useRef<HTMLElement>(null);
  const inView = useInView(ref, { once, margin: '-10% 0px' });
  const [value, setValue] = useState(0);
  const rafRef = useRef<number | null>(null);

  useEffect(() => {
    if (!inView) return;

    const prefersReduced =
      typeof window !== 'undefined' &&
      window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReduced) {
      setValue(target);
      return;
    }

    const start = performance.now();
    const tick = (now: number) => {
      const elapsed = now - start;
      const t = Math.min(elapsed / duration, 1);
      // ease-out cubic
      const eased = 1 - Math.pow(1 - t, 3);
      setValue(target * eased);
      if (t < 1) {
        rafRef.current = requestAnimationFrame(tick);
      } else {
        setValue(target);
      }
    };

    rafRef.current = requestAnimationFrame(tick);
    return () => {
      if (rafRef.current !== null) cancelAnimationFrame(rafRef.current);
    };
  }, [inView, target, duration]);

  return [ref, value] as const;
}
