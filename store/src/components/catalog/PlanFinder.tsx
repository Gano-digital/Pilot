import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Compass, ArrowRight, RotateCcw, Sparkles } from 'lucide-react';
import { SERVICES, formatPrice, billingLabel, buyKind } from '@/data/catalog';

/**
 * Asistente de elección: 2 preguntas cortas que mapean el caso de uso del
 * visitante a una recomendación concreta del catálogo. Sin datos inventados —
 * cada resultado apunta a un servicio real por id.
 */

type Profile = 'arrancando' | 'negocio' | 'tienda' | 'agencia' | 'empresa';

interface Option {
  label: string;
  hint: string;
  value: Profile;
}

const OPTIONS: Option[] = [
  { label: 'Estoy arrancando', hint: 'Mi primer sitio o blog personal', value: 'arrancando' },
  { label: 'Tengo un negocio', hint: 'Web institucional con tráfico real', value: 'negocio' },
  { label: 'Vendo en línea', hint: 'Tienda WooCommerce / e-commerce', value: 'tienda' },
  { label: 'Soy agencia o dev', hint: 'Manejo varios proyectos o clientes', value: 'agencia' },
  { label: 'Operación empresarial', hint: 'No admito caídas ni improvisación', value: 'empresa' },
];

// Mapeo perfil → id de servicio recomendado + por qué.
const RECOMMENDATION: Record<Profile, { id: string; why: string }> = {
  arrancando: {
    id: 'wp-starter',
    why: 'Empiezas sin tocar un servidor: WordPress ya instalado, SSL automático y todo listo en minutos.',
  },
  negocio: {
    id: 'pro-managed',
    why: 'Pensado para cuando recibes visitas reales: staging para no romper nada, CDN global y backups frecuentes.',
  },
  tienda: {
    id: 'business-nvme',
    why: 'Cada milisegundo en el checkout es conversión. NVMe Gen4 y WAF activo para WooCommerce serio.',
  },
  agencia: {
    id: 'wp-deluxe',
    why: 'Tres instalaciones separadas con staging: maneja desarrollo, pruebas y clientes sin que se mezcle nada.',
  },
  empresa: {
    id: 'ultimate',
    why: 'Sin topes, ingeniero asignado y consultoría mensual. Para cuando tu operación digital no admite improvisar.',
  },
};

export default function PlanFinder() {
  const [profile, setProfile] = useState<Profile | null>(null);

  const recommended = profile
    ? SERVICES.find((s) => s.id === RECOMMENDATION[profile].id)
    : null;

  return (
    <div className="rounded-sm border border-border bg-card p-6 md:p-10 overflow-hidden">
      <div className="flex items-center gap-3 mb-2">
        <span className="flex items-center justify-center h-10 w-10 rounded-sm bg-primary text-primary-foreground shrink-0">
          <Compass size={20} strokeWidth={2} aria-hidden="true" />
        </span>
        <h3 className="font-heading text-2xl font-bold text-card-foreground">
          ¿No sabes cuál elegir?
        </h3>
      </div>
      <p className="font-body text-sm text-muted-foreground mb-7">
        Cuéntanos en qué punto estás y te recomendamos el plan exacto. Toma 5 segundos.
      </p>

      {/* Opciones */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-2" role="radiogroup" aria-label="¿En qué punto estás?">
        {OPTIONS.map((opt, i) => {
          const active = profile === opt.value;
          return (
            <motion.button
              key={opt.value}
              type="button"
              role="radio"
              aria-checked={active}
              onClick={() => setProfile(opt.value)}
              initial={{ opacity: 0, y: 12 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.3, delay: i * 0.05, ease: 'easeOut' as const }}
              whileHover={{ scale: 1.02 }}
              whileTap={{ scale: 0.98 }}
              className={`text-left p-4 rounded-sm border transition-colors duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring ${
                active
                  ? 'border-primary bg-muted'
                  : 'border-border bg-background hover:border-foreground'
              }`}
            >
              <span className={`block font-heading text-base font-bold mb-1 ${active ? 'text-primary' : 'text-card-foreground'}`}>
                {opt.label}
              </span>
              <span className="block font-body text-xs text-muted-foreground leading-snug">
                {opt.hint}
              </span>
            </motion.button>
          );
        })}
      </div>

      {/* Resultado */}
      <AnimatePresence mode="wait">
        {recommended && profile && (
          <motion.div
            key={profile}
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -8 }}
            transition={{ duration: 0.4, ease: 'easeOut' as const }}
            className="mt-7 p-6 rounded-sm border border-primary bg-muted"
          >
            <div className="flex items-center gap-2 mb-3">
              <Sparkles size={16} className="text-primary" aria-hidden="true" />
              <span className="font-body text-xs uppercase tracking-[0.15em] text-primary">
                Tu recomendación
              </span>
            </div>

            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div className="min-w-0">
                <h4 className="font-heading text-2xl font-bold text-card-foreground mb-2">
                  {recommended.name}
                </h4>
                <p className="font-body text-sm text-card-foreground/80 leading-relaxed max-w-xl">
                  {RECOMMENDATION[profile].why}
                </p>
                {recommended.bestFor && recommended.bestFor.length > 0 && (
                  <ul className="flex flex-wrap gap-2 mt-4">
                    {recommended.bestFor.map((item) => (
                      <li
                        key={item}
                        className="font-body text-xs text-card-foreground/80 bg-background rounded-sm px-2.5 py-1 border border-border"
                      >
                        {item}
                      </li>
                    ))}
                  </ul>
                )}
              </div>

              <div className="flex flex-col items-start md:items-end gap-3 shrink-0">
                <div className="flex items-baseline gap-1.5">
                  <span className="font-heading text-3xl font-bold text-primary">
                    {formatPrice(recommended.priceFrom, recommended.currency)}
                  </span>
                  <span className="font-body text-sm text-muted-foreground">
                    {billingLabel(recommended.billingPeriod)}
                  </span>
                </div>
                <a
                  href={recommended.buyUrl}
                  target={buyKind(recommended.buyUrl) === 'domains' ? undefined : '_blank'}
                  rel={buyKind(recommended.buyUrl) === 'domains' ? undefined : 'noopener noreferrer'}
                  className="inline-flex items-center gap-2 h-11 px-6 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
                >
                  Ver plan <ArrowRight size={15} aria-hidden="true" />
                </a>
              </div>
            </div>

            <button
              type="button"
              onClick={() => setProfile(null)}
              className="inline-flex items-center gap-1.5 mt-5 font-body text-xs text-muted-foreground hover:text-foreground transition-colors duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-sm"
            >
              <RotateCcw size={13} aria-hidden="true" /> Empezar de nuevo
            </button>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
