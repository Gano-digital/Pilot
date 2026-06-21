import { ExternalLink } from 'lucide-react';
import type { Reference } from '@/data/filosofia';

const CHARTREUSE = '#D97E3A';

type ReferencesProps = {
  references: Reference[];
  /** Título de la sección. */
  heading?: string;
  /** Texto introductorio opcional. */
  intro?: string;
};

/**
 * Sección de referencias estilo editorial — notas al pie con autor,
 * publicación, año y enlace a una fuente reputable. Reutilizable en
 * cualquier pieza del portal informativo de Gano Digital.
 */
export default function References({
  references,
  heading = 'Para seguir leyendo',
  intro = 'No te pedimos que nos creas. Estas son las fuentes detrás de lo que afirmamos — léelas, contrástalas y saca tus propias conclusiones.',
}: ReferencesProps) {
  return (
    <section
      className="py-20 md:py-28"
      style={{ backgroundColor: '#0A0A0A', borderTop: '1px solid #2A2A2A' }}
      aria-labelledby="referencias-heading"
    >
      <div className="max-w-4xl mx-auto px-6 md:px-10">
        <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
          Fuentes y evidencia
        </p>
        <h2
          id="referencias-heading"
          className="font-heading font-bold text-foreground mb-5"
          style={{ fontSize: 'clamp(26px, 3.5vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.08 }}
        >
          {heading}
        </h2>
        <p className="font-body text-base md:text-lg text-muted-foreground mb-12 max-w-2xl" style={{ lineHeight: 1.7 }}>
          {intro}
        </p>

        <ol className="flex flex-col gap-px" style={{ counterReset: 'ref' }}>
          {references.map((ref) => (
            <li
              key={ref.id}
              className="group relative py-6 border-t"
              style={{ borderColor: '#2A2A2A' }}
            >
              <a
                href={ref.href}
                target="_blank"
                rel="noopener noreferrer"
                className="block transition-colors duration-200"
              >
                <div className="flex flex-col md:flex-row md:items-baseline gap-1 md:gap-3 mb-1.5">
                  <span
                    className="font-heading text-sm font-bold shrink-0"
                    style={{ color: CHARTREUSE }}
                  >
                    {ref.author}
                  </span>
                  <span className="font-body text-xs text-muted-foreground">
                    {ref.publication} · {ref.year}
                  </span>
                </div>
                <p className="font-heading text-foreground text-lg md:text-xl mb-2 inline-flex items-start gap-2 group-hover:text-primary transition-colors duration-200" style={{ letterSpacing: '-0.01em', lineHeight: 1.25 }}>
                  <span className="italic">{ref.work}</span>
                  <ExternalLink size={15} className="mt-1.5 shrink-0 opacity-60 group-hover:opacity-100 transition-opacity" aria-hidden="true" />
                </p>
                <p className="font-body text-sm text-muted-foreground max-w-2xl" style={{ lineHeight: 1.6 }}>
                  {ref.note}
                </p>
              </a>
            </li>
          ))}
        </ol>

        <p className="font-body text-xs text-muted-foreground/70 mt-10 italic" style={{ lineHeight: 1.6 }}>
          Las cifras y citas se revisan periódicamente contra su fuente original. Si encuentras una
          imprecisión, escríbenos: la honestidad intelectual es parte del servicio.
        </p>
      </div>
    </section>
  );
}
