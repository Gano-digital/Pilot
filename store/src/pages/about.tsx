import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

const principles = [
  'No vendo servicios. Catapulto el crecimiento de tu empresa.',
  'Primero escucho tu negocio. La herramienta correcta viene después.',
  'El acompañamiento humano es el producto. La tecnología es el medio.',
  'Busco dinero inteligente: socios que leen, preguntan e invierten en grande.',
];

const skills = ['Acompañamiento y mentoría técnica', 'IA aplicada y empleados agénticos', 'Automatización de procesos', 'Hosting WordPress administrado', 'Migraciones sin downtime', 'Seguridad web y arquitectura'];
const tools = ['Node.js a medida', 'GoHighLevel', 'APIs de redes sociales', 'Modelos de IA de frontera', 'Cloudflare CDN', 'Microsoft 365 · Google Workspace'];

const processSteps = [
  { num: '01', title: 'Diagnóstico', desc: 'Analizamos a fondo tu operación digital actual: rendimiento, seguridad, puntos de fragilidad y objetivos de negocio.' },
  { num: '02', title: 'Arquitectura', desc: 'Diseñamos la infraestructura ideal para tu caso. Hosting, dominios, correo y seguridad dimensionados a tu tráfico real.' },
  { num: '03', title: 'Despliegue', desc: 'Migramos o montamos todo sin downtime. Configuración, SSL, backups y monitoreo activos desde el primer día.' },
  { num: '04', title: 'Operación', desc: 'Soporte continuo, monitoreo proactivo y acompañamiento de un ingeniero. Tu infraestructura, siempre en pie.' },
];

export default function AboutPage() {
  return (
    <>
      <Helmet>
        <title>Nosotros — Gano Digital</title>
        <meta name="description" content="Socio de crecimiento digital en Colombia: acompañamiento técnico, IA aplicada, automatización e infraestructura. Criterio de adentro de la industria, del lado de tu empresa." />
        <link rel="canonical" href="https://gano.digital/about" />
        <meta property="og:title" content="Nosotros — Gano Digital" />
        <meta property="og:description" content="Estudio de infraestructura digital. Ingeniería curada para hosting, dominios y seguridad web en Colombia." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/about" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Nosotros&description=Estudio+de+infraestructura+digital+soberana+en+Colombia.&tag=Nosotros" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Nosotros — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Nosotros — Gano Digital" />
        <meta name="twitter:description" content="Estudio de infraestructura digital. Ingeniería curada para hosting, dominios y seguridad web en Colombia." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Nosotros&description=Estudio+de+infraestructura+digital+soberana+en+Colombia.&tag=Nosotros" />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'AboutPage',
          '@id': 'https://gano.digital/about#webpage',
          name: 'Nosotros — Gano Digital',
          url: 'https://gano.digital/about',
          isPartOf: { '@id': 'https://gano.digital/#website' },
          about: { '@id': 'https://gano.digital/#organization' },
        })}</script>
      </Helmet>

      {/* Hero */}
      <section className="pt-36 pb-20 md:pt-44 md:pb-28" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 items-start">
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, ease: 'easeOut' }}
            >
              <h1
                className="font-heading font-bold text-foreground"
                style={{ fontSize: 'clamp(52px, 8vw, 96px)', letterSpacing: '-0.03em', lineHeight: 1 }}
              >
                Nosotros
              </h1>
            </motion.div>
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.15, ease: 'easeOut' }}
              className="md:pt-4"
            >
              <p
                className="font-body text-foreground/80 text-lg md:text-xl"
                style={{ lineHeight: 1.7 }}
              >
                Pasé años dentro de la industria del hosting y los servicios digitales a gran
                escala. Vi cómo se construyen estos productos, dónde fallan y por qué a tantos
                clientes los dejan esperando. Gano Digital nació de esa experiencia: tomar ese
                criterio de adentro y ponerlo del lado de quien de verdad lo necesita.
              </p>
              <p
                className="font-body text-muted-foreground text-base mt-5"
                style={{ lineHeight: 1.7 }}
              >
                Mi oficio no es vender servicios. Es escuchar tu modelo de negocio, entender
                tus necesidades reales y acompañarte —en persona— a instalar, configurar y usar
                las herramientas que catapultan tu crecimiento. Desde infraestructura que no se
                cae hasta inteligencia artificial que trabaja por ti. Soy tu mentor técnico y tu
                soporte directo, no un proveedor anónimo.
              </p>
              <p
                className="font-body text-muted-foreground text-base mt-5"
                style={{ lineHeight: 1.7 }}
              >
                Pienso de forma no-lineal: contraintuitiva y recursiva. Conozco el mercado
                corporativo y el perfil pyme, el backend y el frontend, y tengo una obsesión sana
                por investigar y construir cosas tangibles, con esencia y destreza. Busco socios
                serios que quieran invertir en grande — no la transacción más barata del mes.
              </p>
              <div className="mt-8">
                <Link
                  to="/contact"
                  className="inline-flex items-center gap-2 text-sm font-body font-medium transition-colors duration-200 hover:opacity-80"
                  style={{ color: CHARTREUSE }}
                >
                  Conversemos tu proyecto <ArrowRight size={14} />
                </Link>
              </div>
            </motion.div>
          </div>

          {/* Workspace image */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.3 }}
            className="mt-16 md:mt-20 rounded-xl overflow-hidden"
            style={{ aspectRatio: '21/9', backgroundColor: 'hsl(var(--card))' }}
          >
            <img
              src="/airo-assets/images/pages/about/designer-workspace"
              alt="Centro de operaciones de Gano Digital"
              className="w-full h-full object-cover"
              loading="eager"
            />
          </motion.div>
        </div>
      </section>

      {/* Philosophy */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--secondary))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body mb-14"
            style={{ color: 'hsl(var(--muted-foreground))' }}
          >
            Filosofía
          </motion.p>
          <div className="flex flex-col gap-8">
            {principles.map((principle, i) => (
              <motion.div
                key={i}
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1, ease: 'easeOut' }}
                className="pl-6 border-l-[1px]"
                style={{ borderColor: CHARTREUSE }}
              >
                <p
                  className="font-heading font-bold"
                  style={{ fontSize: 'clamp(24px, 3.5vw, 42px)', color: 'hsl(var(--secondary-foreground))', letterSpacing: '-0.02em' }}
                >
                  {principle}
                </p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Skills & Tools */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-14"
          >
            Qué hacemos
          </motion.p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20">
            {/* Skills */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.1 }}
            >
              <h3 className="font-heading font-bold text-foreground text-xl mb-6" style={{ letterSpacing: '-0.02em' }}>
                Especialidades
              </h3>
              <div className="flex flex-wrap gap-2">
                {skills.map((skill) => (
                  <span
                    key={skill}
                    className="px-4 py-2 text-sm font-body border rounded-full text-foreground/70"
                    style={{ borderColor: 'hsl(var(--border))' }}
                  >
                    {skill}
                  </span>
                ))}
              </div>
            </motion.div>
            {/* Tools */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <h3 className="font-heading font-bold text-foreground text-xl mb-6" style={{ letterSpacing: '-0.02em' }}>
                Tecnología
              </h3>
              <div className="flex flex-wrap gap-2">
                {tools.map((tool) => (
                  <span
                    key={tool}
                    className="px-4 py-2 text-sm font-body border rounded-full"
                    style={{ borderColor: CHARTREUSE, color: CHARTREUSE }}
                  >
                    {tool}
                  </span>
                ))}
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Process */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--secondary))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body mb-14"
            style={{ color: 'hsl(var(--muted-foreground))' }}
          >
            Proceso
          </motion.p>

          {/* Desktop: horizontal timeline */}
          <div className="hidden md:grid grid-cols-4 gap-6 relative">
            {/* Connector line */}
            <div
              className="absolute top-5 left-[12.5%] right-[12.5%] h-px"
              style={{ backgroundColor: 'hsl(var(--border))' }}
            />
            {processSteps.map((step, i) => (
              <motion.div
                key={step.num}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.12 }}
                className="relative"
              >
                <div
                  className="w-10 h-10 rounded-full flex items-center justify-center mb-5 font-heading font-bold text-sm relative z-10"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  {step.num}
                </div>
                <h4 className="font-heading font-bold text-xl mb-2" style={{ color: 'hsl(var(--secondary-foreground))', letterSpacing: '-0.02em' }}>
                  {step.title}
                </h4>
                <p className="font-body text-sm" style={{ color: 'hsl(var(--muted-foreground))', lineHeight: 1.7 }}>
                  {step.desc}
                </p>
              </motion.div>
            ))}
          </div>

          {/* Mobile: vertical */}
          <div className="md:hidden flex flex-col gap-10">
            {processSteps.map((step, i) => (
              <motion.div
                key={step.num}
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                className="flex gap-5"
              >
                <div
                  className="w-10 h-10 rounded-full flex items-center justify-center font-heading font-bold text-sm shrink-0"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  {step.num}
                </div>
                <div>
                  <h4 className="font-heading font-bold text-xl mb-1" style={{ color: 'hsl(var(--secondary-foreground))', letterSpacing: '-0.02em' }}>
                    {step.title}
                  </h4>
                  <p className="font-body text-sm" style={{ color: 'hsl(var(--muted-foreground))', lineHeight: 1.7 }}>
                    {step.desc}
                  </p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}
