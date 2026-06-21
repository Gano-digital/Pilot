import { motion } from 'motion/react';
import { Check, Minus, ArrowUpRight } from 'lucide-react';
import { SERVICES, formatPrice, billingLabel } from '@/data/catalog';
import GlossaryTerm from './GlossaryTerm';

/**
 * Tabla comparativa de los 4 planes de Hosting WordPress.
 * Cada fila es un atributo; los valores se extraen de los datos reales.
 * Reveal escalonado por columna al entrar en viewport.
 */

const PLAN_IDS = ['wp-starter', 'pro-managed', 'business-nvme', 'ultimate'] as const;

interface Row {
  label: React.ReactNode;
  values: (string | boolean)[]; // por plan, en orden de PLAN_IDS
}

// Valores curados a partir de los features reales de cada plan.
const ROWS: Row[] = [
  { label: 'Sitios WordPress', values: ['1', '3', '10', 'Ilimitados'] },
  { label: <>Almacenamiento <GlossaryTerm termKey="nvme">NVMe</GlossaryTerm></>, values: ['30 GB', '75 GB', '150 GB Gen4', 'Ilimitado'] },
  { label: 'Visitas / mes', values: ['25.000', '150.000', '500.000', 'Sin tope'] },
  { label: <><GlossaryTerm termKey="staging">Staging</GlossaryTerm> incluido</>, values: [true, true, true, true] },
  { label: <><GlossaryTerm termKey="cdn">CDN</GlossaryTerm> global</>, values: [false, true, true, true] },
  { label: <><GlossaryTerm termKey="redis">Redis</GlossaryTerm> dedicado</>, values: [false, true, '1 GB', true] },
  { label: <><GlossaryTerm termKey="waf">WAF</GlossaryTerm> Capa 7</>, values: [false, false, true, true] },
  { label: 'Frecuencia de backups', values: ['Diario', 'Cada 12h', 'Cada 6h', 'Cada 4h'] },
  { label: 'Consultoría mensual', values: [false, false, false, '1h/mes'] },
];

function Cell({ value }: { value: string | boolean }) {
  if (value === true) {
    return <Check size={18} className="text-primary mx-auto" strokeWidth={2.5} aria-label="Incluido" />;
  }
  if (value === false) {
    return <Minus size={16} className="text-muted-foreground/40 mx-auto" aria-label="No incluido" />;
  }
  return <span className="font-body text-sm text-card-foreground/90">{value}</span>;
}

export default function HostingCompare() {
  const plans = PLAN_IDS.map((id) => SERVICES.find((s) => s.id === id)!).filter(Boolean);

  return (
    <div className="rounded-sm border border-border bg-card overflow-x-auto">
      <table className="w-full min-w-[680px] border-collapse">
        <caption className="sr-only">Comparación de planes de Hosting WordPress</caption>
        <thead>
          <tr>
            <th scope="col" className="text-left p-5 align-bottom">
              <span className="font-body text-xs uppercase tracking-[0.15em] text-muted-foreground">
                Compara planes
              </span>
            </th>
            {plans.map((plan, i) => (
              <motion.th
                scope="col"
                key={plan.id}
                initial={{ opacity: 0, y: 16 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: i * 0.08, ease: 'easeOut' as const }}
                className={`p-5 text-center align-bottom border-b border-border ${plan.badge ? 'bg-muted' : ''}`}
              >
                {plan.badge && (
                  <span className="inline-block mb-2 font-heading text-[10px] font-bold uppercase tracking-[0.15em] text-primary-foreground bg-primary px-2 py-0.5 rounded-sm">
                    {plan.badge}
                  </span>
                )}
                <span className="block font-heading text-lg font-bold text-card-foreground leading-tight">
                  {plan.name}
                </span>
                <span className="block mt-1 font-heading text-xl font-bold text-primary">
                  {formatPrice(plan.priceFrom, plan.currency)}
                  <span className="font-body text-xs text-muted-foreground">{billingLabel(plan.billingPeriod)}</span>
                </span>
              </motion.th>
            ))}
          </tr>
        </thead>
        <tbody>
          {ROWS.map((row, ri) => (
            <motion.tr
              key={ri}
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.35, delay: ri * 0.05, ease: 'easeOut' as const }}
              className="border-b border-border last:border-0"
            >
              <th scope="row" className="text-left p-5 font-body text-sm font-medium text-card-foreground/80">
                {row.label}
              </th>
              {row.values.map((v, ci) => (
                <td key={ci} className={`p-5 text-center ${plans[ci]?.badge ? 'bg-muted/40' : ''}`}>
                  <Cell value={v} />
                </td>
              ))}
            </motion.tr>
          ))}
          {/* Fila de CTA */}
          <tr>
            <td className="p-5" />
            {plans.map((plan) => (
              <td key={plan.id} className={`p-5 text-center ${plan.badge ? 'bg-muted/40' : ''}`}>
                <a
                  href={plan.buyUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-1.5 h-10 px-4 rounded-sm font-heading text-xs font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.04] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
                  aria-label={`Contratar ${plan.name}`}
                >
                  Elegir <ArrowUpRight size={14} aria-hidden="true" />
                </a>
              </td>
            ))}
          </tr>
        </tbody>
      </table>
    </div>
  );
}
