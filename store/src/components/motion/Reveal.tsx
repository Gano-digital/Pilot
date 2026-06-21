/**
 * <Reveal> — entrada con resorte al entrar en viewport, con parallax opcional.
 * Encapsula useSpringEntrance + useParallax para no repetir lógica por página.
 * Solo anima transform/opacity. Respeta prefers-reduced-motion vía los hooks.
 */
import { useRef, useMemo, type ReactNode, type ElementType } from 'react';
import { motion } from 'motion/react';
import { useSpringEntrance, useParallax } from '@/lib/motion-physics';

type RevealProps = {
  children: ReactNode;
  /** Dirección desde la que entra el contenido. */
  direction?: 'up' | 'down' | 'left' | 'right' | 'none';
  /** Distancia inicial del desplazamiento (px). */
  distance?: number;
  /** Retardo de entrada (s). */
  delay?: number;
  /** Intensidad de parallax ligado al scroll (0 = desactivado). */
  parallax?: number;
  /** Etiqueta semántica a renderizar (section, article, div…). */
  as?: ElementType;
  className?: string;
  /** Re-disparar la animación cada vez que entra (por defecto una sola vez). */
  repeat?: boolean;
};

export function Reveal({
  children,
  direction = 'up',
  distance = 28,
  delay = 0,
  parallax = 0,
  as = 'div',
  className,
  repeat = false,
}: RevealProps) {
  const ref = useRef<HTMLElement | null>(null);
  const { variants } = useSpringEntrance(direction, distance);
  const y = useParallax(ref, parallax);
  const MotionTag = useMemo(() => motion(as as ElementType), [as]);

  return (
    <MotionTag
      ref={ref}
      className={className}
      variants={variants}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: !repeat, margin: '-12% 0px -12% 0px' }}
      transition={{ delay }}
      style={parallax ? { y } : undefined}
    >
      {children}
    </MotionTag>
  );
}

export default Reveal;
