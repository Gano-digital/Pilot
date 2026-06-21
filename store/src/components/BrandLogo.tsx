/**
 * <BrandLogo> — logo adaptable al tema.
 *
 * Renderiza las dos variantes (clara/oscura) superpuestas y cruza su opacidad
 * según el tema activo, con un fundido suave. Así el mark siempre tiene
 * contraste correcto sobre el lienzo crema (claro) o #0A0A0A (oscuro) y la
 * transición de tema no produce un destello.
 *
 * SSR-safe: el tema inicial es 'light' (coincide con el HTML servido), así que
 * la variante clara se pinta de inmediato sin parpadeo.
 */
import { motion } from 'motion/react';
import { useTheme } from '@/components/ThemeProvider';
import { LOGO_LIGHT_SRC, LOGO_DARK_SRC, LOGO_ALT } from '@/lib/brand-logo';
import { cn } from '@/lib/utils';

type BrandLogoProps = {
  /** Clases de tamaño (p. ej. "h-9 md:h-11"). Siempre con w-auto. */
  className?: string;
  /** alt accesible; por defecto el nombre de marca. */
  alt?: string;
  /** Prioriza la carga (logo del header, above-the-fold). */
  priority?: boolean;
};

export function BrandLogo({ className, alt = LOGO_ALT, priority = true }: BrandLogoProps) {
  const { theme } = useTheme();
  const isDark = theme === 'dark';

  const imgClass = cn(
    'block h-full w-auto object-contain shrink-0 self-center',
  );

  return (
    <span className={cn('relative inline-flex items-center', className)}>
      {/* Variante clara (trazo oscuro) — visible en tema claro */}
      <motion.img
        src={LOGO_LIGHT_SRC}
        alt={alt}
        className={imgClass}
        loading={priority ? 'eager' : 'lazy'}
        fetchPriority={priority ? 'high' : 'auto'}
        decoding="async"
        animate={{ opacity: isDark ? 0 : 1 }}
        transition={{ duration: 0.4, ease: 'easeInOut' }}
      />
      {/* Variante oscura (trazo claro) — superpuesta, visible en tema oscuro */}
      <motion.img
        src={LOGO_DARK_SRC}
        alt=""
        aria-hidden
        className={cn(imgClass, 'absolute inset-0')}
        loading={priority ? 'eager' : 'lazy'}
        decoding="async"
        animate={{ opacity: isDark ? 1 : 0 }}
        transition={{ duration: 0.4, ease: 'easeInOut' }}
      />
    </span>
  );
}

export default BrandLogo;
