import { useState, useId } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { lookupTerm } from '@/data/glossary';

interface GlossaryTermProps {
  /** Clave a buscar en el glosario (case-insensitive). */
  termKey: string;
  /** Texto a mostrar (por defecto, el nombre canónico del término). */
  children?: React.ReactNode;
}

/**
 * Resalta un término técnico con una línea punteada y muestra su definición
 * pedagógica en un tooltip al pasar el cursor o enfocar con teclado.
 * Si el término no existe en el glosario, renderiza el texto plano.
 */
export default function GlossaryTerm({ termKey, children }: GlossaryTermProps) {
  const entry = lookupTerm(termKey);
  const [open, setOpen] = useState(false);
  const tooltipId = useId();

  if (!entry) return <>{children ?? termKey}</>;

  const label = children ?? entry.term;

  return (
    <span className="relative inline-block">
      <button
        type="button"
        aria-describedby={open ? tooltipId : undefined}
        onMouseEnter={() => setOpen(true)}
        onMouseLeave={() => setOpen(false)}
        onFocus={() => setOpen(true)}
        onBlur={() => setOpen(false)}
        className="cursor-help font-semibold text-primary underline decoration-dotted decoration-from-font underline-offset-4 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-sm"
      >
        {label}
      </button>

      <AnimatePresence>
        {open && (
          <motion.span
            id={tooltipId}
            role="tooltip"
            initial={{ opacity: 0, y: 6, scale: 0.97 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 4, scale: 0.98 }}
            transition={{ duration: 0.18, ease: 'easeOut' as const }}
            className="absolute left-1/2 bottom-full z-50 mb-2 w-64 -translate-x-1/2 rounded-sm border border-border bg-popover p-4 text-left shadow-xl shadow-black/40"
          >
            <span className="block font-heading text-sm font-bold text-popover-foreground mb-1">
              {entry.term}
              <span className="ml-2 font-body text-[11px] font-normal uppercase tracking-wider text-primary">
                {entry.short}
              </span>
            </span>
            <span className="block font-body text-xs leading-relaxed text-muted-foreground">
              {entry.long}
            </span>
            {/* Flecha */}
            <span className="absolute left-1/2 top-full -translate-x-1/2 h-0 w-0 border-x-[6px] border-x-transparent border-t-[6px] border-t-border" />
          </motion.span>
        )}
      </AnimatePresence>
    </span>
  );
}
