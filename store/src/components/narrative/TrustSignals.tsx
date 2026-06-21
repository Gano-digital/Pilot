import { motion } from 'motion/react';
import { Activity, RotateCcw, MoveRight, Headset, FileLock2, BadgeCheck } from 'lucide-react';

const GUARANTEES = [
  {
    icon: Activity,
    title: 'SLA 99.95% por escrito',
    desc: 'No es marketing: es un compromiso medible. Monitoreamos disponibilidad 24/7 y respondemos por ella.',
  },
  {
    icon: RotateCcw,
    title: 'Backups automáticos',
    desc: 'Copias cada pocas horas y restauración en un clic. Si algo sale mal, tu sitio vuelve como si nada.',
  },
  {
    icon: MoveRight,
    title: 'Migración sin downtime',
    desc: 'Movemos tu sitio actual sin que pierdas un solo cliente. Cero ventana de apagado, cero pánico.',
  },
  {
    icon: Headset,
    title: 'Soporte humano en español',
    desc: 'Hablas con ingenieros en tu zona horaria que conocen tu proyecto. Sin guiones, sin esperas eternas.',
  },
  {
    icon: FileLock2,
    title: 'Pago cifrado de extremo a extremo',
    desc: 'La transacción ocurre en una pasarela auditada que nunca toca nuestros servidores. Tus datos son tuyos.',
  },
  {
    icon: BadgeCheck,
    title: 'Reseller autorizado',
    desc: 'Operamos sobre infraestructura de una de las mayores plataformas de hosting del mundo. Respaldo real, no promesas.',
  },
];

export default function TrustSignals() {
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
            Por qué confiar en nosotros
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground"
            style={{ fontSize: 'clamp(30px, 4.2vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
          >
            Promesas que firmamos, no solo decimos.
          </motion.h2>
        </div>

        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.08 } } }}
          className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px rounded-sm overflow-hidden"
          style={{ backgroundColor: '#2A2A2A' }}
        >
          {GUARANTEES.map((g) => {
            const Icon = g.icon;
            return (
              <motion.div
                key={g.title}
                variants={{
                  hidden: { opacity: 0, y: 20 },
                  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
                }}
                className="group p-7 transition-colors duration-300"
                style={{ backgroundColor: 'hsl(var(--background))' }}
              >
                <motion.span
                  whileHover={{ rotate: -8, scale: 1.08 }}
                  transition={{ type: 'spring', stiffness: 400, damping: 12 }}
                  className="flex items-center justify-center h-12 w-12 rounded-sm bg-muted text-primary mb-5"
                >
                  <Icon size={24} strokeWidth={1.75} aria-hidden="true" />
                </motion.span>
                <h3 className="font-heading text-lg font-bold text-foreground mb-2 leading-tight">{g.title}</h3>
                <p className="font-body text-sm text-muted-foreground leading-relaxed">{g.desc}</p>
              </motion.div>
            );
          })}
        </motion.div>
      </div>
    </section>
  );
}
