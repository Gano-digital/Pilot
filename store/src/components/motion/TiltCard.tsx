/**
 * <TiltCard> — superficie con inclinación 3D y elevación reactiva al puntero,
 * más un halo de luz cálido que sigue al cursor. Da la sensación de que la
 * tarjeta "flota y reacciona" al usuario. Solo transform/opacity.
 *
 * El brillo se construye con un MotionTemplate sobre un radial-gradient: no
 * crea capas extra de layout y se apaga al salir el puntero.
 */
import { useMemo, type ReactNode, type ElementType } from 'react';
import { motion, useMotionTemplate, useTransform } from 'motion/react';
import { usePointerTilt } from '@/lib/motion-physics';
import { cn } from '@/lib/utils';

type TiltCardProps = {
  children: ReactNode;
  className?: string;
  /** Inclinación máxima en grados (8 = sutil-medio, 14 = inmersivo). */
  maxTilt?: number;
  /** Cuánto se eleva al pasar el cursor (px de translateZ aparente). */
  lift?: number;
  /** Intensidad del halo de luz (0 = sin halo). */
  glow?: number;
  as?: ElementType;
  /** Estilos inline extra (p. ej. background semántico). */
  style?: React.CSSProperties;
};

export function TiltCard({
  children,
  className,
  maxTilt = 10,
  lift = 10,
  glow = 0.14,
  as = 'div',
  style,
}: TiltCardProps) {
  const { handlers, rotateX, rotateY, pointerX, pointerY, reduced } =
    usePointerTilt(maxTilt);

  const MotionTag = useMemo(() => motion(as as ElementType), [as]);

  // Halo radial cálido (terracota) que sigue al puntero.
  const glowX = useTransform(pointerX, (v) => `${v * 100}%`);
  const glowY = useTransform(pointerY, (v) => `${v * 100}%`);
  const background = useMotionTemplate`radial-gradient(420px circle at ${glowX} ${glowY}, hsl(26 68% 54% / ${glow}), transparent 60%)`;

  if (reduced) {
    return (
      <div className={cn('relative', className)} style={style}>
        {children}
      </div>
    );
  }

  return (
    <MotionTag
      {...handlers}
      className={cn('relative [transform-style:preserve-3d]', className)}
      style={{
        ...style,
        rotateX,
        rotateY,
        transformPerspective: 900,
      }}
      whileHover={{ scale: 1 + lift / 600, transition: { type: 'spring', stiffness: 200, damping: 20 } }}
    >
      {/* Halo de luz reactivo — bajo el contenido, no intercepta el puntero. */}
      {glow > 0 && (
        <motion.span
          aria-hidden
          className="pointer-events-none absolute inset-0 rounded-[inherit] opacity-0 transition-opacity duration-300"
          style={{ background, opacity: 1 }}
        />
      )}
      <div className="relative [transform:translateZ(40px)]">{children}</div>
    </MotionTag>
  );
}

export default TiltCard;
