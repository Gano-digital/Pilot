import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import {
  ArrowRight, Bot, Workflow, MessagesSquare, TrendingUp,
  Headphones, Megaphone, FileSearch, Clock, Sparkles, MessageCircle,
  UserCheck, HeartHandshake, ShieldCheck,
} from 'lucide-react';
import TechStack from '@/components/TechStack';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=Quiero+explorar+soluciones+de+IA+y+empleados+ag%C3%A9nticos+para+mi+empresa';

const site = 'https://gano.digital';
const title = 'Soluciones de IA y Empleados Agénticos — Gano Digital';
const description =
  'Instalamos inteligencia artificial que trabaja por ti: agentes que atienden, automatizan y venden. Soluciones a medida en código, GoHighLevel y APIs — para empresas con necesidades reales.';
const ogImage = `${site}/api/og?title=IA+que+trabaja+por+ti&description=Agentes+que+atienden%2C+automatizan+y+venden+para+tu+empresa.&tag=Soluciones+IA`;

// Qué es un empleado agéntico — desmitificar
const capabilities = [
  {
    icon: Headphones,
    title: 'Atención que no duerme',
    desc: 'Un agente que responde a tus clientes en WhatsApp, web y redes a cualquier hora — con el tono de tu marca y acceso a tu información real, no respuestas genéricas.',
  },
  {
    icon: Workflow,
    title: 'Procesos que se ejecutan solos',
    desc: 'Cotizaciones, seguimientos, agendamiento, facturación, reportes. Lo repetitivo deja de robarte horas y pasa a correr en segundo plano, sin errores humanos.',
  },
  {
    icon: Megaphone,
    title: 'Marketing que opera 24/7',
    desc: 'Califica leads, nutre prospectos y mueve campañas a través de tus redes y embudos. Integración real con GoHighLevel y las APIs de las plataformas que ya usas.',
  },
  {
    icon: FileSearch,
    title: 'Conocimiento al instante',
    desc: 'Tus manuales, catálogos y datos convertidos en un asistente que tu equipo consulta y obtiene respuestas exactas — sin buscar en carpetas ni esperar a nadie.',
  },
];

// Cómo cambia el negocio — el argumento de inversión (enfoque aumentativo)
const outcomes = [
  {
    icon: TrendingUp,
    metric: 'Tu equipo, multiplicado',
    desc: 'Automatizar lo repetitivo le devuelve a tu gente las horas que hoy se van en tareas que agotan. La IA no reemplaza a tu equipo: lo libera para el trabajo que de verdad necesita criterio humano.',
  },
  {
    icon: Clock,
    metric: 'Más capacidad, mismo equipo',
    desc: 'Atiende a muchos más clientes sin sobrecargar a nadie. Tu operación crece mientras tu gente trabaja mejor, no más.',
  },
  {
    icon: MessagesSquare,
    metric: 'Cero clientes sin respuesta',
    desc: 'Cada mensaje recibe atención inmediata, y los casos que importan llegan a una persona real. La velocidad de la máquina, la calidez del humano.',
  },
];

// Para quién — casos por mercado
const markets = [
  { sector: 'Comercio y e-commerce', use: 'Agente de ventas que asesora, recomienda y cierra; recuperación de carritos; soporte post-venta automatizado.' },
  { sector: 'Servicios profesionales', use: 'Agendamiento inteligente, intake de clientes, seguimiento de casos y respuestas a preguntas frecuentes con criterio.' },
  { sector: 'Salud y bienestar', use: 'Recordatorios, confirmación de citas, triage inicial y atención de consultas básicas que descongestionan recepción.' },
  { sector: 'Educación y formación', use: 'Tutores virtuales, soporte a estudiantes, calificación asistida y onboarding automatizado de nuevos inscritos.' },
];

export default function SolucionesIaPage() {
  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/soluciones-ia`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/soluciones-ia`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Soluciones de IA — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Service',
          '@id': `${site}/soluciones-ia#service`,
          name: 'Soluciones de IA y Empleados Agénticos',
          serviceType: 'Inteligencia artificial aplicada a empresas',
          url: `${site}/soluciones-ia`,
          areaServed: 'CO',
          provider: { '@id': `${site}/#organization` },
          description,
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/soluciones-ia" locale="es" />

      {/* ── HERO ── */}
      <section className="relative overflow-hidden pt-36 pb-24 md:pt-44 md:pb-32" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 -right-1/4 w-[60vw] h-[60vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.12) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.08, 1], opacity: [0.6, 1, 0.6] }}
          transition={{ duration: 7, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <motion.span
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body text-foreground/90 border border-border rounded-full px-3 py-1.5 mb-8"
          >
            <Sparkles size={12} style={{ color: CHARTREUSE }} />
            Inteligencia artificial aplicada
          </motion.span>
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <h1
              className="font-heading font-bold text-foreground max-w-4xl mb-8"
              style={{ fontSize: 'clamp(40px, 7vw, 88px)', letterSpacing: '-0.03em', lineHeight: 1.02 }}
            >
              IA que trabaja con tu equipo.{' '}
              <span style={{ color: CHARTREUSE }}>No en su lugar.</span>
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl mb-10"
            style={{ lineHeight: 1.7 }}
          >
            La IA dejó de ser promesa. Hoy instalo agentes inteligentes que atienden a tus
            clientes, automatizan lo repetitivo y mueven tu marketing — para que tu gente recupere
            su tiempo y se concentre en lo que solo un humano hace bien. No te vendo una licencia
            ni un reemplazo: te entrego un equipo aumentado, siempre con personas al mando.
          </motion.p>
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.35, ease: 'easeOut' }}
            className="flex flex-wrap items-center gap-4"
          >
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Explorar mi caso <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </a>
            <Link
              to="/aprende"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              Aprender primero
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── QUÉ HACE UN EMPLEADO AGÉNTICO ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Qué es, sin humo
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Un empleado agéntico es software que entiende, decide y actúa.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              No es un chatbot de respuestas enlatadas. Es un sistema que conoce tu negocio,
              accede a tus datos reales y ejecuta tareas completas — de principio a fin.
            </p>
          </div>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            {capabilities.map((c) => {
              const Icon = c.icon;
              return (
                <motion.div
                  key={c.title}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  whileHover={{ y: -6 }}
                  className="flex gap-5 p-7 rounded-xl transition-colors duration-300"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <span className="flex items-center justify-center h-12 w-12 rounded-xl shrink-0" style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}>
                    <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="font-heading font-bold text-foreground text-xl mb-2" style={{ letterSpacing: '-0.01em' }}>{c.title}</h3>
                    <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{c.desc}</p>
                  </div>
                </motion.div>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* ── EL ARGUMENTO DE INVERSIÓN ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Por qué es inversión, no gasto
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              El retorno no es teórico. Se mide en horas devueltas a tu gente.
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {outcomes.map((o, i) => {
              const Icon = o.icon;
              return (
                <motion.div
                  key={o.metric}
                  initial={{ opacity: 0, y: 24 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: i * 0.1, ease: 'easeOut' as const }}
                  className="p-8 rounded-xl"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <Icon size={28} strokeWidth={1.75} style={{ color: CHARTREUSE }} className="mb-5" aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg mb-3" style={{ letterSpacing: '-0.01em' }}>{o.metric}</h3>
                  <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{o.desc}</p>
                </motion.div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── CASOS POR MERCADO ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Para tu mercado
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Cada negocio tiene su cuello de botella. Lo encuentro y lo automatizo.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              Estos son puntos de partida, no plantillas cerradas. Tu solución se diseña
              después de entender cómo funciona tu operación por dentro.
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {markets.map((m, i) => (
              <motion.div
                key={m.sector}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.08, ease: 'easeOut' as const }}
                className="p-7 rounded-xl"
                style={{ backgroundColor: 'hsl(var(--card))', borderLeft: `3px solid ${CHARTREUSE}` }}
              >
                <div className="flex items-center gap-3 mb-3">
                  <Bot size={18} style={{ color: CHARTREUSE }} aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg" style={{ letterSpacing: '-0.01em' }}>{m.sector}</h3>
                </div>
                <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{m.use}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── IA CON HUMANOS AL MANDO ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-5xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Cómo entendemos la IA
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              La máquina rinde mejor cuando un humano la guía.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              No creemos en automatizar personas para “ahorrar costos”. Creemos en aumentar su
              capacidad. Es una postura razonada y respaldada por economistas y marcos éticos
              internacionales — y guía cada solución que entregamos.
            </p>
          </div>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12"
          >
            {[
              { icon: UserCheck, title: 'Supervisión humana', desc: 'Cada flujo crítico lleva un punto de revisión humana. La IA propone y ejecuta; la persona decide y responde.' },
              { icon: HeartHandshake, title: 'Cercanía intacta', desc: 'Automatizamos lo repetitivo, no la relación. Cuando un cliente necesita un humano, encuentra a un humano.' },
              { icon: ShieldCheck, title: 'Aumentar, no recortar', desc: 'Liberamos a tu equipo del tedio para que haga el trabajo que sí necesita criterio. Más capacidad, no menos gente.' },
            ].map((item) => {
              const Icon = item.icon;
              return (
                <motion.div
                  key={item.title}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  className="p-7 rounded-xl"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <Icon size={26} strokeWidth={1.75} style={{ color: CHARTREUSE }} className="mb-4" aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg mb-2" style={{ letterSpacing: '-0.01em' }}>{item.title}</h3>
                  <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{item.desc}</p>
                </motion.div>
              );
            })}
          </motion.div>

          <Link
            to="/filosofia"
            className="group inline-flex items-center gap-2 font-heading text-sm font-bold uppercase tracking-[0.12em] text-foreground transition-colors duration-200 hover:text-primary"
          >
            Leer nuestra filosofía completa, con sus fuentes
            <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" style={{ color: CHARTREUSE }} />
          </Link>
        </div>
      </section>

      {/* ── STACK TECNOLÓGICO ── */}
      <TechStack
        kicker="Con qué lo construyo"
        title="Soluciones reales, no demos de feria."
        subtitle="Código a medida sobre Node.js, integración con GoHighLevel y las APIs de las redes que tu negocio ya usa, más modelos de IA de frontera. La misma caja de herramientas con la que construyo y opero sistemas en producción."
      />

      {/* ── CTA FINAL ── */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' as const }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(28px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            ¿Qué tarea de tu empresa debería estar corriendo sola?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground mb-10 max-w-2xl mx-auto"
            style={{ lineHeight: 1.7 }}
          >
            Conversemos sin compromiso. Te escucho, entiendo tu operación y te digo con
            franqueza dónde la IA suma de verdad — y dónde todavía no vale la pena.
          </motion.p>
          <a
            href={WHATSAPP}
            target="_blank"
            rel="noopener noreferrer"
            className="group inline-flex items-center gap-2 h-13 px-8 py-4 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
          >
            <MessageCircle size={16} /> Conversemos tu caso
            <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
          </a>
        </div>
      </section>
    </>
  );
}
