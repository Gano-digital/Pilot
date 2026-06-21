import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { MousePointerClick, Lock, ShieldCheck, Wrench, ArrowRight } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

interface Step {
  n: string;
  icon: typeof Lock;
  title: string;
  desc: string;
  /** Marca el paso donde el visitante sale a la pasarela externa. */
  external?: boolean;
}

const STEPS: Step[] = [
  {
    n: '01',
    icon: MousePointerClick,
    title: 'Eliges tu plan aquí',
    desc: 'Comparas, entiendes cada término y armas tu solución en Gano Digital. Sin presión y sin letra pequeña: lo que ves es lo que contratas.',
  },
  {
    n: '02',
    icon: Lock,
    title: 'Te llevamos al checkout seguro',
    desc: 'El pago se procesa en el checkout cifrado de nuestro proveedor de infraestructura autorizado (secureserver.net). Es una página blindada que no manipulamos — y eso es justo lo que la hace confiable.',
    external: true,
  },
  {
    n: '03',
    icon: ShieldCheck,
    title: 'Pagas en entorno blindado',
    desc: 'Tus datos de pago viajan cifrados de extremo a extremo y nunca tocan nuestros servidores. La transacción la respalda una de las plataformas de hosting más grandes del mundo.',
  },
  {
    n: '04',
    icon: Wrench,
    title: 'Nosotros configuramos todo',
    desc: 'Apenas confirmas, un ingeniero deja tu servicio listo: DNS, SSL, correo y migración sin downtime. Tú recibes las llaves; del trabajo sucio nos encargamos nosotros.',
  },
];

interface HowItWorksProps {
  /** Si true, oculta el encabezado de sección (para usarlo embebido). */
  compact?: boolean;
  /** Si true, muestra el CTA final hacia /proceso. */
  showLink?: boolean;
}

export default function HowItWorks({ compact = false, showLink = true }: HowItWorksProps) {
  return (
    <div>
      {!compact && (
        <div className="mb-12 md:mb-16">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
          >
            Transparencia total
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-4 max-w-3xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Contratar es de cuatro pasos. Y te los contamos todos.
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="font-body text-muted-foreground max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            El pago final ocurre en una pasarela externa que no podemos —ni queremos— modificar.
            Esa separación es una garantía: tu dinero pasa por un entorno auditado y blindado,
            no por una página improvisada.
          </motion.p>
        </div>
      )}

      <motion.ol
        initial="hidden"
        whileInView="visible"
        viewport={{ once: true }}
        variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.12 } } }}
        className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5"
      >
        {STEPS.map((step) => {
          const Icon = step.icon;
          return (
            <motion.li
              key={step.n}
              variants={{
                hidden: { opacity: 0, y: 24 },
                visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
              }}
              className="relative flex flex-col rounded-sm border border-border bg-card p-6 transition-colors duration-300 hover:border-primary"
            >
              {step.external && (
                <span className="absolute top-5 right-5 font-heading text-[10px] font-bold uppercase tracking-[0.12em] text-primary-foreground bg-primary px-2 py-1 rounded-sm">
                  Pago externo
                </span>
              )}
              <span
                className="font-heading font-bold tabular-nums mb-5"
                style={{ fontSize: 28, color: CHARTREUSE, letterSpacing: '-0.02em' }}
              >
                {step.n}
              </span>
              <span className="flex items-center justify-center h-11 w-11 rounded-sm bg-muted text-primary mb-4">
                <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
              </span>
              <h3 className="font-heading text-lg font-bold text-card-foreground mb-2 leading-tight">
                {step.title}
              </h3>
              <p className="font-body text-sm text-muted-foreground leading-relaxed">
                {step.desc}
              </p>
            </motion.li>
          );
        })}
      </motion.ol>

      {showLink && (
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.2 }}
          className="mt-8"
        >
          <Link
            to="/proceso"
            className="group inline-flex items-center gap-2 font-body text-sm transition-colors duration-200 hover:opacity-80"
            style={{ color: CHARTREUSE }}
          >
            Ver el proceso completo y las garantías de pago
            <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
          </Link>
        </motion.div>
      )}
    </div>
  );
}
