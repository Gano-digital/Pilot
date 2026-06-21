import { motion } from 'motion/react';
import { Ear, Compass, GitBranch, HandHeart } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

const principles = [
  {
    icon: Ear,
    title: 'Primero escucho. Después propongo.',
    desc: 'No empiezo por venderte un plan. Empiezo por entender tu modelo de negocio, dónde pierdes dinero y qué te quita el sueño. La herramienta correcta solo aparece cuando el problema está claro.',
  },
  {
    icon: HandHeart,
    title: 'Te acompaño, no te entrego una factura.',
    desc: 'Configuro, instalo y te enseño a usar lo que contratas — en persona, paso a paso. Soy tu mentor técnico y tu soporte directo, no un ticket en una cola que nadie atiende.',
  },
  {
    icon: GitBranch,
    title: 'Pienso de forma no-lineal.',
    desc: 'Los caminos obvios rara vez son los rentables. Trabajo de manera contraintuitiva y recursiva: busco la salida que tu competencia no ve, porque está mirando el problema de frente.',
  },
  {
    icon: Compass,
    title: 'Mi norte es tu crecimiento.',
    desc: 'Conozco el mercado corporativo y el perfil pyme desde adentro de la industria. No mido el éxito en servicios vendidos, sino en capacidad real que sumo a tu empresa.',
  },
];

export default function WorkingMethod() {
  return (
    <section
      className="py-24 md:py-36 relative overflow-hidden"
      style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}
    >
      <div className="relative max-w-7xl mx-auto px-6 md:px-10">
        {/* Encabezado */}
        <div className="max-w-3xl mb-16 md:mb-20">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.25em] uppercase font-body mb-5"
            style={{ color: CHARTREUSE }}
          >
            Mi forma de trabajar
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.55, delay: 0.1, ease: 'easeOut' as const }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(30px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            La diferencia no está en el servidor.
            <br />
            Está en quién está del otro lado.
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.55, delay: 0.2, ease: 'easeOut' as const }}
            className="font-body text-lg text-muted-foreground"
            style={{ lineHeight: 1.7 }}
          >
            Puedes contratar infraestructura en cualquier parte. Lo que no se compra en
            un carrito es alguien que entienda tu negocio, te traduzca la tecnología y se
            quede contigo hasta que funcione.
          </motion.p>
        </div>

        {/* Principios */}
        <motion.div
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
          className="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-20"
        >
          {principles.map((p) => {
            const Icon = p.icon;
            return (
              <motion.div
                key={p.title}
                variants={{
                  hidden: { opacity: 0, y: 24 },
                  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
                }}
                className="flex gap-5 p-7 rounded-sm"
                style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
              >
                <span
                  className="flex items-center justify-center h-12 w-12 rounded-sm shrink-0"
                  style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}
                >
                  <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                </span>
                <div>
                  <h3 className="font-heading font-bold text-foreground text-xl mb-2" style={{ letterSpacing: '-0.01em' }}>
                    {p.title}
                  </h3>
                  <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>
                    {p.desc}
                  </p>
                </div>
              </motion.div>
            );
          })}
        </motion.div>

        {/* Filtro honesto: para quién es / para quién no */}
        <motion.div
          initial={{ opacity: 0, y: 28 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, margin: '-60px' }}
          transition={{ duration: 0.6, ease: 'easeOut' as const }}
          className="grid grid-cols-1 lg:grid-cols-2 gap-6"
        >
          <div
            className="p-8 md:p-10 rounded-sm"
            style={{ backgroundColor: 'hsl(var(--card))', borderLeft: `3px solid ${CHARTREUSE}` }}
          >
            <h3 className="font-heading font-bold text-foreground text-lg mb-5" style={{ letterSpacing: '-0.01em' }}>
              Trabajo mejor con quienes…
            </h3>
            <ul className="flex flex-col gap-3.5">
              {[
                'Leen, preguntan y quieren entender el porqué de cada decisión.',
                'Ven la tecnología como inversión, no como gasto a minimizar.',
                'Buscan un socio de largo plazo, no el precio más bajo del mes.',
                'Tienen necesidades complejas que merecen una solución a medida.',
              ].map((t) => (
                <li key={t} className="flex items-start gap-3">
                  <span className="mt-2 w-1.5 h-1.5 rounded-full shrink-0" style={{ backgroundColor: CHARTREUSE }} />
                  <span className="font-body text-sm text-foreground/80" style={{ lineHeight: 1.6 }}>{t}</span>
                </li>
              ))}
            </ul>
          </div>
          <div
            className="p-8 md:p-10 rounded-sm"
            style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
          >
            <h3 className="font-heading font-bold text-muted-foreground text-lg mb-5" style={{ letterSpacing: '-0.01em' }}>
              Probablemente no soy para ti si…
            </h3>
            <ul className="flex flex-col gap-3.5">
              {[
                'Solo buscas el hosting más barato y nada más.',
                'No tienes tiempo para una conversación sobre tu negocio.',
                'Esperas resultados sin involucrarte en el proceso.',
                'Prefieres un proveedor anónimo a un acompañamiento real.',
              ].map((t) => (
                <li key={t} className="flex items-start gap-3">
                  <span className="mt-2 w-1.5 h-1.5 rounded-full shrink-0 bg-muted-foreground/50" />
                  <span className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.6 }}>{t}</span>
                </li>
              ))}
            </ul>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
