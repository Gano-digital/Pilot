import { motion } from 'motion/react';
import { TrendingDown, Clock, PhoneOff, ArrowRight } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

const PAINS = [
  {
    icon: TrendingDown,
    pain: 'Tu sitio se cae justo cuando llega el tráfico.',
    cost: 'El lunes de la campaña, el Black Friday, el día que saliste en prensa. Cada minuto caído es dinero que no vuelve.',
  },
  {
    icon: Clock,
    pain: 'Carga lento y Google te entierra.',
    cost: 'Un sitio que tarda 3 segundos pierde más de la mitad de sus visitas móviles. Pagas publicidad para que reboten.',
  },
  {
    icon: PhoneOff,
    pain: 'Abres un ticket y nadie responde en días.',
    cost: 'Soporte de copiar y pegar, en otro idioma, en otra zona horaria. Tu negocio detenido mientras esperas.',
  },
];

export default function CostOfFragility() {
  return (
    <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_1.2fr] gap-12 lg:gap-16 items-start">
          {/* Lado izquierdo: el dolor */}
          <div className="lg:sticky lg:top-28">
            <motion.p
              initial={{ opacity: 0, y: 10 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5 }}
              className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
            >
              Hablemos claro
            </motion.p>
            <motion.h2
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.1 }}
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(30px, 4.2vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
            >
              El hosting barato sale carísimo.
            </motion.h2>
            <motion.p
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.15 }}
              className="font-body text-muted-foreground mb-8"
              style={{ lineHeight: 1.7 }}
            >
              No se paga en la factura mensual. Se paga en ventas perdidas, clientes que no
              vuelven y noches sin dormir. Si algo de esto te suena, no estás solo —
              y tiene solución.
            </motion.p>
            <motion.a
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.25 }}
              href="/catalogo"
              className="group inline-flex items-center gap-2 font-body text-sm transition-colors duration-200 hover:opacity-80"
              style={{ color: CHARTREUSE }}
            >
              Quiero infraestructura que aguante
              <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
            </motion.a>
          </div>

          {/* Lado derecho: las consecuencias */}
          <motion.ul
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.12 } } }}
            className="flex flex-col gap-4"
          >
            {PAINS.map((p) => {
              const Icon = p.icon;
              return (
                <motion.li
                  key={p.pain}
                  variants={{
                    hidden: { opacity: 0, y: 24 },
                    visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
                  }}
                  className="flex gap-5 rounded-sm border border-border bg-card p-6 transition-colors duration-300 hover:border-primary"
                >
                  <span className="flex items-center justify-center h-11 w-11 rounded-sm bg-muted text-primary shrink-0">
                    <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="font-heading text-lg font-bold text-card-foreground mb-1.5 leading-tight">
                      {p.pain}
                    </h3>
                    <p className="font-body text-sm text-muted-foreground leading-relaxed">{p.cost}</p>
                  </div>
                </motion.li>
              );
            })}
          </motion.ul>
        </div>
      </div>
    </section>
  );
}
