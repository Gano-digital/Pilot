import { motion } from 'motion/react';
import { Boxes, GitBranch, ShieldCheck, Gauge } from 'lucide-react';
import { SERVICES, CATEGORY_ORDER } from '@/data/catalog';

/**
 * Statement de capacidad del catálogo.
 * Posiciona a Gano Digital como estudio de infraestructura capaz de tomar
 * proyectos ambiciosos de extremo a extremo — no un revendedor de hosting.
 * Las métricas se derivan de datos reales del catálogo (sin cifras inventadas).
 */

const totalServices = SERVICES.length;
const totalCategories = CATEGORY_ORDER.length;
const wordpressPlans = SERVICES.filter((s) => s.category === 'Hosting WordPress').length;

const metrics = [
  { value: String(totalServices), label: 'servicios curados', sub: 'del dominio al servidor dedicado' },
  { value: String(totalCategories), label: 'frentes de infraestructura', sub: 'hosting, correo, seguridad, VPS y más' },
  { value: String(wordpressPlans), label: 'planes WordPress gestionados', sub: 'desde el primer sitio hasta alto tráfico' },
  { value: '99.9%', label: 'SLA de disponibilidad', sub: 'respaldado por red de datacenters global' },
];

const pillars = [
  {
    icon: Boxes,
    title: 'Arquitectura completa, no piezas sueltas',
    body: 'Dimensionamos hosting, dominios, correo, SSL y respaldos como un solo sistema coherente — no como productos desconectados.',
  },
  {
    icon: GitBranch,
    title: 'Migración sin downtime',
    body: 'Movemos proyectos en producción con planeación previa, ventana controlada y rollback listo. Tu sitio no se cae mientras crece.',
  },
  {
    icon: Gauge,
    title: 'Rendimiento que Google premia',
    body: 'NVMe, caché de objetos y CDN configurados para Core Web Vitals — porque la velocidad es ranking, no lujo.',
  },
  {
    icon: ShieldCheck,
    title: 'Soberanía y blindaje',
    body: 'Aislamiento, WAF, SSL y backups diarios. Tu infraestructura es tuya, y la defendemos como si fuera nuestra.',
  },
];

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.08 } },
} as const;

const itemVariants = {
  hidden: { opacity: 0, y: 24 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
} as const;

export default function CapabilityStatement() {
  return (
    <section className="py-16 md:py-24 bg-background border-y border-border">
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        {/* Statement editorial */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 mb-16">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, ease: 'easeOut' as const }}
            className="lg:col-span-7"
          >
            <p className="font-body text-xs uppercase tracking-[0.2em] text-primary mb-5">
              Un estudio, no un revendedor
            </p>
            <h2 className="font-heading text-3xl md:text-5xl font-bold text-foreground leading-[1.05] tracking-tight">
              Tomamos proyectos ambiciosos
              <br className="hidden md:block" />
              <span className="text-primary"> y entregamos resultados.</span>
            </h2>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1, ease: 'easeOut' as const }}
            className="lg:col-span-5 flex items-end"
          >
            <p className="font-body text-base md:text-lg text-muted-foreground leading-relaxed">
              Detrás de cada plan hay ingeniería curada: dimensionamos, migramos
              y operamos infraestructura para que tu negocio escale sin sustos.
              Empezamos con un dominio o levantamos un ecosistema completo —
              <span className="text-foreground"> la ambición la pones tú.</span>
            </p>
          </motion.div>
        </div>

        {/* Métricas reales */}
        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          variants={containerVariants}
          className="grid grid-cols-2 lg:grid-cols-4 gap-px bg-border rounded-sm overflow-hidden mb-16"
        >
          {metrics.map((m) => (
            <motion.div key={m.label} variants={itemVariants} className="bg-background p-6 md:p-8">
              <p className="font-heading text-4xl md:text-5xl font-bold text-foreground tabular-nums leading-none">
                {m.value}
              </p>
              <p className="font-heading text-sm font-bold text-primary uppercase tracking-wider mt-3">
                {m.label}
              </p>
              <p className="font-body text-xs text-muted-foreground mt-1.5 leading-relaxed">{m.sub}</p>
            </motion.div>
          ))}
        </motion.div>

        {/* Pilares de capacidad de entrega */}
        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          variants={containerVariants}
          className="grid grid-cols-1 md:grid-cols-2 gap-6"
        >
          {pillars.map((p) => (
            <motion.div
              key={p.title}
              variants={itemVariants}
              className="flex items-start gap-5 rounded-sm border border-border bg-card p-6 md:p-7 transition-colors duration-300 hover:border-primary"
            >
              <span className="flex items-center justify-center h-12 w-12 rounded-sm bg-muted text-primary shrink-0">
                <p.icon size={24} strokeWidth={1.75} aria-hidden="true" />
              </span>
              <div>
                <h3 className="font-heading text-lg font-bold text-card-foreground leading-snug mb-1.5">
                  {p.title}
                </h3>
                <p className="font-body text-sm text-muted-foreground leading-relaxed">{p.body}</p>
              </div>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
