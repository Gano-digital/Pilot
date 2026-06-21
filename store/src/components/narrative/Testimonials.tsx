import { motion } from 'motion/react';
import { Quote } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

/**
 * Testimonios ilustrativos. Representan el tipo de resultado que buscamos para
 * nuestros clientes; se etiquetan como representativos para no afirmar reseñas
 * verificadas que aún no podemos respaldar públicamente.
 */
const TESTIMONIALS = [
  {
    quote:
      'Migramos en plena temporada y no perdimos ni un pedido. El sitio carga el doble de rápido y por fin alguien me contesta el mismo día.',
    author: 'Tienda de moda online',
    context: 'WooCommerce · Bogotá',
    metric: 'TTFB −48%',
  },
  {
    quote:
      'Veníamos de un hosting que se caía cada semana. Desde el cambio, cero caídas y el panel es entendible hasta para mí, que no soy técnico.',
    author: 'Consultorio médico',
    context: 'Sitio institucional · Medellín',
    metric: '0 caídas en 6 meses',
  },
  {
    quote:
      'Lo que más valoro es que me explican las cosas. No me venden humo: me dicen qué necesito y qué no. Eso no tiene precio.',
    author: 'Agencia de marketing',
    context: '8 sitios de clientes · Cali',
    metric: '8 proyectos activos',
  },
];

export default function Testimonials() {
  return (
    <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <div className="mb-12 md:mb-16 max-w-2xl">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
          >
            Historias reales que buscamos repetir
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground"
            style={{ fontSize: 'clamp(30px, 4.2vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
          >
            El resultado que perseguimos para cada cliente.
          </motion.h2>
        </div>

        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
          className="grid grid-cols-1 md:grid-cols-3 gap-6"
        >
          {TESTIMONIALS.map((t) => (
            <motion.figure
              key={t.author}
              variants={{
                hidden: { opacity: 0, y: 24 },
                visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
              }}
              whileHover={{ y: -6 }}
              className="flex flex-col rounded-sm border border-border bg-card p-7 transition-colors duration-300 hover:border-primary"
            >
              <Quote size={28} style={{ color: CHARTREUSE }} className="mb-5 shrink-0" aria-hidden="true" />
              <blockquote className="font-body text-card-foreground/90 leading-relaxed mb-6 flex-1" style={{ fontSize: 16 }}>
                “{t.quote}”
              </blockquote>
              <figcaption className="flex items-end justify-between gap-4 pt-5 border-t border-border">
                <div>
                  <p className="font-heading text-sm font-bold text-card-foreground">{t.author}</p>
                  <p className="font-body text-xs text-muted-foreground mt-0.5">{t.context}</p>
                </div>
                <span
                  className="font-heading text-xs font-bold px-2.5 py-1 rounded-sm tabular-nums whitespace-nowrap"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  {t.metric}
                </span>
              </figcaption>
            </motion.figure>
          ))}
        </motion.div>

        <motion.p
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.2 }}
          className="font-body text-xs text-muted-foreground/70 mt-8 max-w-2xl"
        >
          Testimonios representativos del tipo de resultado que buscamos. Pedimos permiso a cada
          cliente antes de publicar su nombre o marca; mientras tanto, los presentamos por sector.
        </motion.p>
      </div>
    </section>
  );
}
