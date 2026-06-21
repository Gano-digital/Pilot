import { useState, useCallback } from 'react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, AnimatePresence, useReducedMotion } from 'motion/react';
import { X, ArrowUpRight, Gamepad2, Play, ArrowDown, FlaskConical, Sparkles } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

type Category = 'All' | 'Interactivo' | 'Infraestructura' | 'Seguridad' | 'Migración' | 'Desarrollo';

const projects = [
  { id: 0, title: 'Demo FPS en el navegador',  category: 'Interactivo' as const,    image: '/airo-assets/images/portfolio/demo-fps',          year: '2026', desc: 'Demo técnica: un shooter en primera persona totalmente jugable, construido en el navegador con Three.js (WebGL), generación procedural de niveles, 3 tipos de enemigos con IA por estados, 3 armas y un motor de audio con Web Audio. Cero recursos externos. Una muestra en vivo de nuestra capacidad de ingeniería front-end.', span: 'col-span-2', link: '/game', badge: 'JUGABLE' },
  { id: 1, title: 'Migración WooCommerce sin downtime', category: 'Migración' as const,   image: '/airo-assets/images/portfolio/migracion-woocommerce', year: '2025', desc: 'Migración de una tienda WooCommerce con catálogo extenso desde un hosting compartido lento hacia infraestructura NVMe Gen4, sin una sola hora de caída. Sincronización en frío, cambio de DNS coordinado y validación de checkout antes de propagar.', span: 'col-span-2', link: null, badge: null },
  { id: 2, title: 'Blindaje de seguridad (red team)', category: 'Seguridad' as const,    image: '/airo-assets/images/portfolio/blindaje-seguridad',   year: '2025', desc: 'Endurecimiento integral de un sitio tras un incidente: WAF Capa 7 con reglas OWASP, mitigación de bots, backups cada 6 horas y un ciclo completo de red team interno hasta cerrar todas las vulnerabilidades.', span: 'col-span-1', link: null, badge: null },
  { id: 3, title: 'Optimización de velocidad NVMe', category: 'Infraestructura' as const, image: '/airo-assets/images/portfolio/optimizacion-velocidad', year: '2024', desc: 'Sitio corporativo con TTFB de más de 2s reducido a menos de 200ms: migración a NVMe Gen4, Redis dedicado, CDN global de 200+ POPs y caché de objetos afinado para el patrón de tráfico real.', span: 'col-span-1', link: null, badge: null },
  { id: 4, title: 'Email corporativo Microsoft 365', category: 'Infraestructura' as const, image: '/airo-assets/images/portfolio/email-corporativo',     year: '2024', desc: 'Migración de correo desde cuentas gratuitas hacia Microsoft 365 Business con dominio propio: configuración de DKIM, SPF y DMARC, buzones con Teams y OneDrive, sin perder un solo correo en el proceso.', span: 'col-span-1', link: null, badge: null },
  { id: 5, title: 'Constructor web para negocio local', category: 'Desarrollo' as const,   image: '/airo-assets/images/portfolio/negocio-local',       year: '2024', desc: 'Presencia web completa para un negocio local: sitio responsive con Google Maps, botón de WhatsApp Business y SEO básico, en línea y posicionado en Google en menos de una semana.', span: 'col-span-1', link: null, badge: null },
  { id: 6, title: 'Ecosistema WordPress a medida', category: 'Desarrollo' as const,       image: '/airo-assets/images/portfolio/ecosistema-wordpress', year: '2023', desc: 'Desarrollo WordPress a medida de wireframe a producción: arquitectura, diseño, integración de pasarela y analytics, sobre hosting Business NVMe incluido el primer año y tres meses de acompañamiento post-lanzamiento.', span: 'col-span-2', link: null, badge: null },
  { id: 7, title: 'Diagnóstico de soberanía digital', category: 'Infraestructura' as const, image: '/airo-assets/images/portfolio/diagnostico-soberania', year: '2023', desc: 'Auditoría técnica completa del stack de un cliente — hosting, DNS, seguridad y SEO técnico — con benchmarks de TTFB/LCP/CLS y un informe ejecutivo entregado en 72 horas con el plan de blindaje.', span: 'col-span-1', link: null, badge: null },
];

const categories: Category[] = ['All', 'Interactivo', 'Infraestructura', 'Seguridad', 'Migración', 'Desarrollo'];

// Card animation variants
const cardVariants = {
  hidden: { opacity: 0, y: 28, scale: 0.97 },
  visible: (i: number) => ({
    opacity: 1,
    y: 0,
    scale: 1,
    transition: { duration: 0.45, delay: i * 0.07, ease: 'easeOut' as const },
  }),
  exit: {
    opacity: 0,
    scale: 0.94,
    y: -12,
    transition: { duration: 0.25, ease: 'easeIn' as const },
  },
};

// Modal variants
const backdropVariants = {
  hidden: { opacity: 0 },
  visible: { opacity: 1, transition: { duration: 0.2 } },
  exit: { opacity: 0, transition: { duration: 0.2 } },
};

const modalVariants = {
  hidden: { opacity: 0, scale: 0.93, y: 32 },
  visible: {
    opacity: 1,
    scale: 1,
    y: 0,
    transition: { duration: 0.35, ease: 'easeOut' as const },
  },
  exit: {
    opacity: 0,
    scale: 0.95,
    y: 20,
    transition: { duration: 0.25, ease: 'easeIn' as const },
  },
};

// Stagger container
const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.07 } },
  exit: { transition: { staggerChildren: 0.04, staggerDirection: -1 as const } },
};

export default function PortfolioPage() {
  const [activeFilter, setActiveFilter] = useState<Category>('All');
  const [selectedProject, setSelectedProject] = useState<typeof projects[0] | null>(null);
  const shouldReduceMotion = useReducedMotion();

  const filtered = activeFilter === 'All'
    ? projects
    : projects.filter((p) => p.category === activeFilter);

  const handleFilterChange = useCallback((cat: Category) => {
    setActiveFilter(cat);
  }, []);

  return (
    <>
      <Helmet>
        <title>Proyectos — Gano Digital</title>
        <meta name="description" content="Casos reales de infraestructura desplegada: migraciones sin downtime, blindaje de seguridad, optimización de velocidad y desarrollo a medida. Ingeniería curada por Gano Digital." />
        <link rel="canonical" href="https://gano.digital/portfolio" />
        <meta property="og:title" content="Proyectos — Gano Digital" />
        <meta property="og:description" content="Casos reales de infraestructura: migraciones, seguridad, velocidad y desarrollo a medida." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/portfolio" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Proyectos&description=Migraciones%2C+seguridad%2C+velocidad+y+desarrollo+a+medida.&tag=Proyectos" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Proyectos — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Proyectos — Gano Digital" />
        <meta name="twitter:description" content="Casos reales de infraestructura: migraciones, seguridad, velocidad y desarrollo a medida." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Proyectos&description=Migraciones%2C+seguridad%2C+velocidad+y+desarrollo+a+medida.&tag=Proyectos" />
        <script type="application/ld+json">
          {JSON.stringify({
            '@context': 'https://schema.org',
            '@type': 'CollectionPage',
            '@id': 'https://gano.digital/portfolio#webpage',
            url: 'https://gano.digital/portfolio',
            name: 'Proyectos — Gano Digital',
            description:
              'Casos reales de infraestructura desplegada: migraciones, seguridad, velocidad y desarrollo a medida.',
            isPartOf: { '@id': 'https://gano.digital/#website' },
            about: { '@id': 'https://gano.digital/#organization' },
            hasPart: projects.map((p) => ({
              '@type': 'CreativeWork',
              name: p.title,
              description: p.desc,
              dateCreated: p.year,
            })),
          })}
        </script>
      </Helmet>

      {/* ── PAGE HEADER ── */}
      <section className="pt-36 pb-16 md:pt-44 md:pb-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' as const }}
          >
            <h1
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(52px, 8vw, 96px)', letterSpacing: '-0.03em' }}
            >
              Proyectos
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15, ease: 'easeOut' as const }}
            className="text-lg text-muted-foreground font-body max-w-xl"
            style={{ lineHeight: 1.7 }}
          >
            Casos reales de infraestructura desplegada: migraciones, seguridad, velocidad y desarrollo a medida.
          </motion.p>
        </div>
      </section>

      {/* ── TEASER BANNER + CTA ── */}
      <section className="pb-16 md:pb-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' as const }}
            className="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8 items-stretch"
          >
            {/* Video / teaser reel */}
            <div className="lg:col-span-3">
              <div
                className="relative overflow-hidden rounded-md group h-full"
                style={{ aspectRatio: '16/9', backgroundColor: 'hsl(var(--card))' }}
              >
                <video
                  className="absolute inset-0 w-full h-full object-cover"
                  poster="/airo-assets/images/pages/portfolio/teaser-poster"
                  autoPlay
                  muted
                  loop
                  playsInline
                  preload="none"
                  aria-label="Reel de presentación de Gano Digital"
                >
                  <source src="/airo-assets/videos/pages/portfolio/teaser-reel" type="video/mp4" />
                </video>

                {/* Gradient legibility overlay */}
                <div
                  className="absolute inset-0 pointer-events-none"
                  style={{
                    background:
                      'linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.35) 100%)',
                  }}
                />

                {/* Decorative play marker */}
                <div className="absolute inset-0 flex items-center justify-center pointer-events-none">
                  <motion.div
                    className="flex items-center justify-center rounded-full"
                    style={{
                      width: 72,
                      height: 72,
                      backgroundColor: CHARTREUSE,
                      boxShadow: '0 8px 40px rgba(217,126,58,0.45)',
                    }}
                    animate={shouldReduceMotion ? undefined : { scale: [1, 1.06, 1] }}
                    transition={{ duration: 2.4, repeat: Infinity, ease: 'easeInOut' as const }}
                  >
                    <Play size={28} style={{ color: '#0A0A0A', marginLeft: 3 }} fill="#0A0A0A" />
                  </motion.div>
                </div>

                {/* Reel label */}
                <div className="absolute bottom-4 left-4 flex items-center gap-2">
                  <span
                    className="px-2.5 py-1 rounded-full text-xs font-heading font-bold tracking-widest uppercase"
                    style={{ backgroundColor: 'rgba(10,10,10,0.7)', color: CHARTREUSE }}
                  >
                    Reel
                  </span>
                </div>
              </div>
            </div>

            {/* CTA panel */}
            <div className="lg:col-span-2 flex flex-col justify-center">
              <div
                className="rounded-md p-8 md:p-10 h-full flex flex-col justify-center"
                style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
              >
                <span
                  className="text-xs font-body tracking-widest uppercase mb-4"
                  style={{ color: CHARTREUSE }}
                >
                  Ingeniería en movimiento
                </span>
                <h2
                  className="font-heading font-bold text-foreground mb-4"
                  style={{ fontSize: 'clamp(28px, 3.2vw, 42px)', letterSpacing: '-0.02em', lineHeight: 1.1 }}
                >
                  No prometemos.<br />Lo desplegamos.
                </h2>
                <p
                  className="text-base text-muted-foreground font-body mb-8"
                  style={{ lineHeight: 1.7 }}
                >
                  Cada proyecto aquí es infraestructura real en producción. Mira el reel y baja a explorar los casos.
                </p>

                <a
                  href="#proyectos"
                  onClick={(e) => {
                    e.preventDefault();
                    document.getElementById('proyectos')?.scrollIntoView({
                      behavior: shouldReduceMotion ? 'auto' : 'smooth',
                      block: 'start',
                    });
                  }}
                  className="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-full font-heading font-bold text-base transition-transform duration-200 hover:scale-[1.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  Explorar proyectos
                  <ArrowDown size={18} />
                </a>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── FILTER BAR ── */}
      <section id="proyectos" className="pb-12 scroll-mt-24" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4, delay: 0.25 }}
            className="flex flex-wrap gap-2"
          >
            {categories.map((cat) => {
              const isActive = activeFilter === cat;
              return (
                <button
                  key={cat}
                  onClick={() => handleFilterChange(cat)}
                  className="relative px-4 py-2 text-sm font-body font-medium rounded-full border transition-colors duration-200 overflow-hidden"
                  style={{
                    color: isActive ? '#0A0A0A' : 'hsl(var(--muted-foreground))',
                    borderColor: isActive ? CHARTREUSE : 'hsl(var(--border))',
                    backgroundColor: 'transparent',
                  }}
                >
                  {/* Animated fill */}
                  <motion.span
                    className="absolute inset-0 rounded-full"
                    initial={false}
                    animate={{ opacity: isActive ? 1 : 0 }}
                    transition={{ duration: 0.2 }}
                    style={{ backgroundColor: CHARTREUSE }}
                  />
                  <span className="relative z-10">{cat}</span>
                </button>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* ── PROJECT GRID ── */}
      <section className="pb-24 md:pb-32" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            key={activeFilter}
            variants={containerVariants}
            initial="hidden"
            animate="visible"
            exit="exit"
            className="grid grid-cols-1 md:grid-cols-3 gap-5"
          >
            <AnimatePresence mode="popLayout">
              {filtered.map((project, i) => (
                <motion.div
                  key={project.id}
                  custom={i}
                  variants={shouldReduceMotion ? undefined : cardVariants}
                  initial="hidden"
                  animate="visible"
                  exit="exit"
                  layout
                  className={`${project.span === 'col-span-2' ? 'md:col-span-2' : 'md:col-span-1'}`}
                >
                  <motion.button
                    className="group w-full text-left"
                    onClick={() => setSelectedProject(project)}
                    whileHover={{ y: shouldReduceMotion ? 0 : -4 }}
                    transition={{ duration: 0.2, ease: 'easeOut' as const }}
                  >
                    {/* Image container */}
                    <div
                      className="relative overflow-hidden rounded-xl"
                      style={{
                        aspectRatio: project.span === 'col-span-2' ? '16/7' : '4/3',
                        backgroundColor: 'hsl(var(--card))',
                      }}
                    >
                      <motion.img
                        src={project.image}
                        alt={project.title}
                        className="w-full h-full object-cover"
                        loading="lazy"
                        whileHover={{ scale: shouldReduceMotion ? 1 : 1.04 }}
                        transition={{ duration: 0.5, ease: 'easeOut' as const }}
                      />

                      {/* Badge (e.g. PLAYABLE) */}
                      {project.badge && (
                        <div
                          className="absolute top-3 left-3 flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-heading font-bold tracking-widest uppercase"
                          style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                        >
                          <Gamepad2 size={11} />
                          {project.badge}
                        </div>
                      )}

                      {/* Overlay — slides up from bottom */}
                      <motion.div
                        className="absolute inset-0 flex flex-col justify-end p-6"
                        initial={{ opacity: 0 }}
                        whileHover={{ opacity: 1 }}
                        transition={{ duration: 0.2 }}
                        style={{ background: 'linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.4) 60%, transparent 100%)' }}
                      >
                        <motion.div
                          initial={{ y: 12, opacity: 0 }}
                          whileHover={{ y: 0, opacity: 1 }}
                          transition={{ duration: 0.25, ease: 'easeOut' as const }}
                          className="flex items-end justify-between"
                        >
                          <div>
                            <span
                              className="text-xs font-body tracking-widest uppercase block mb-1"
                              style={{ color: CHARTREUSE }}
                            >
                              {project.category}
                            </span>
                            <span className="font-heading font-bold text-lg text-white">
                              {project.title}
                            </span>
                          </div>
                          <div
                            className="w-9 h-9 rounded-full flex items-center justify-center shrink-0 ml-4"
                            style={{ backgroundColor: CHARTREUSE }}
                          >
                            <ArrowUpRight size={16} style={{ color: '#0A0A0A' }} />
                          </div>
                        </motion.div>
                      </motion.div>

                      {/* Chartreuse border on hover */}
                      <motion.div
                        className="absolute inset-0 rounded-xl pointer-events-none border-2"
                        initial={{ opacity: 0 }}
                        whileHover={{ opacity: 1 }}
                        transition={{ duration: 0.15 }}
                        style={{ borderColor: CHARTREUSE }}
                      />
                    </div>

                    {/* Card footer */}
                    <div className="mt-3 flex items-center justify-between px-0.5">
                      <span className="font-heading font-bold text-foreground text-base">
                        {project.title}
                      </span>
                      <span className="text-xs text-muted-foreground font-body">{project.year}</span>
                    </div>
                  </motion.button>
                </motion.div>
              ))}
            </AnimatePresence>
          </motion.div>
        </div>
      </section>

      {/* ── CREATIVE LAB ── */}
      <section className="pb-24 md:pb-32 border-t" style={{ backgroundColor: 'hsl(var(--background))', borderColor: 'hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10 pt-20 md:pt-28">
          {/* Section header */}
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-80px' }}
            transition={{ duration: 0.55, ease: 'easeOut' as const }}
            className="max-w-2xl mb-12"
          >
            <div className="flex items-center gap-2.5 mb-5">
              <FlaskConical size={18} style={{ color: CHARTREUSE }} />
              <span
                className="text-xs font-body tracking-widest uppercase"
                style={{ color: CHARTREUSE }}
              >
                Laboratorio creativo
              </span>
            </div>
            <h2
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(30px, 4vw, 52px)', letterSpacing: '-0.02em', lineHeight: 1.05 }}
            >
              Donde exploramos lo que aún no es servicio.
            </h2>
            <p className="text-lg text-muted-foreground font-body" style={{ lineHeight: 1.7 }}>
              Probamos herramientas nuevas antes de ofrecerlas. Esto es IA generativa
              en estado de experimentación — no un entregable cerrado, pero sí una señal
              de hacia dónde podemos llevar tu proyecto.
            </p>
          </motion.div>

          {/* Experiment piece */}
          <motion.div
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.6, delay: 0.1, ease: 'easeOut' as const }}
            className="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8 items-stretch"
          >
            {/* Video */}
            <div className="lg:col-span-3">
              <div
                className="relative overflow-hidden rounded-md h-full"
                style={{ aspectRatio: '16/9', backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
              >
                <video
                  className="absolute inset-0 w-full h-full object-cover"
                  poster="/airo-assets/images/pages/portfolio/teaser-poster"
                  controls
                  loop
                  playsInline
                  preload="metadata"
                  aria-label="Experimento de video generado con IA (con audio)"
                >
                  <source src="/airo-assets/videos/pages/portfolio/lab-experiment" type="video/mp4" />
                </video>

                {/* Experimental badge */}
                <div
                  className="absolute top-4 left-4 flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-heading font-bold tracking-widest uppercase"
                  style={{ backgroundColor: 'rgba(10,10,10,0.82)', color: CHARTREUSE, border: `1px solid ${CHARTREUSE}` }}
                >
                  <Sparkles size={12} />
                  Experimental
                </div>
              </div>
            </div>

            {/* Description + disclaimer */}
            <div className="lg:col-span-2 flex flex-col justify-center">
              <div
                className="rounded-md p-8 md:p-10 h-full flex flex-col justify-center"
                style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
              >
                <h3
                  className="font-heading font-bold text-foreground mb-4"
                  style={{ fontSize: 'clamp(22px, 2.4vw, 30px)', letterSpacing: '-0.01em', lineHeight: 1.15 }}
                >
                  Video generado con IA
                </h3>
                <p className="text-base text-muted-foreground font-body mb-6" style={{ lineHeight: 1.7 }}>
                  Una pieza creada experimentando con modelos de video generativo.
                  Nos sirve para entender el medio y medir hasta dónde llega la herramienta.
                </p>

                {/* Disclaimer note */}
                <div
                  className="rounded-xl p-4 mb-7"
                  style={{ backgroundColor: 'rgba(217,126,58,0.08)', borderLeft: `3px solid ${CHARTREUSE}` }}
                >
                  <p className="text-sm font-body" style={{ color: 'hsl(var(--foreground))', lineHeight: 1.6 }}>
                    <strong className="font-heading">Producto experimental.</strong>{' '}
                    Pieza generada con IA. Con posibilidad real de convertirse en un
                    entregable de producción comercial.
                  </p>
                </div>

                <a
                  href="https://wa.me/573135646123?text=Vi%20el%20experimento%20de%20video%20con%20IA%20en%20su%20portafolio%20y%20me%20interesa%20explorar%20posibilidades"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full font-heading font-bold text-sm transition-transform duration-200 hover:scale-[1.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 self-start"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  Explorar esta posibilidad
                  <ArrowUpRight size={16} />
                </a>
              </div>
            </div>
          </motion.div>
        </div>
      </section>
      <AnimatePresence>
        {selectedProject && (
          <motion.div
            key="backdrop"
            variants={backdropVariants}
            initial="hidden"
            animate="visible"
            exit="exit"
            className="fixed inset-0 z-[200] flex items-center justify-center p-4 md:p-10"
            style={{ backgroundColor: 'rgba(0,0,0,0.92)' }}
            onClick={() => setSelectedProject(null)}
          >
            <motion.div
              key="modal"
              variants={modalVariants}
              initial="hidden"
              animate="visible"
              exit="exit"
              className="relative max-w-3xl w-full rounded-xl overflow-hidden"
              style={{ backgroundColor: 'hsl(var(--card))' }}
              onClick={(e) => e.stopPropagation()}
            >
              {/* Image with subtle zoom-in on open */}
              <motion.div
                className="overflow-hidden"
                style={{ maxHeight: '55vh' }}
                initial={{ scale: 1.04 }}
                animate={{ scale: 1 }}
                transition={{ duration: 0.5, ease: 'easeOut' as const }}
              >
                <img
                  src={selectedProject.image}
                  alt={selectedProject.title}
                  className="w-full object-cover"
                  style={{ maxHeight: '55vh' }}
                />
              </motion.div>

              {/* Content — staggered reveal */}
              <motion.div
                className="p-8"
                initial="hidden"
                animate="visible"
                variants={{
                  hidden: {},
                  visible: { transition: { staggerChildren: 0.08, delayChildren: 0.15 } },
                }}
              >
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <motion.span
                      variants={{ hidden: { opacity: 0, y: 8 }, visible: { opacity: 1, y: 0 } }}
                      className="text-xs font-body tracking-widest uppercase mb-2 block"
                      style={{ color: CHARTREUSE }}
                    >
                      {selectedProject.category} · {selectedProject.year}
                    </motion.span>
                    <motion.h2
                      variants={{ hidden: { opacity: 0, y: 10 }, visible: { opacity: 1, y: 0 } }}
                      className="font-heading font-bold text-2xl text-foreground"
                      style={{ letterSpacing: '-0.02em' }}
                    >
                      {selectedProject.title}
                    </motion.h2>
                  </div>
                  <motion.button
                    onClick={() => setSelectedProject(null)}
                    className="text-muted-foreground hover:text-foreground transition-colors p-1 mt-1"
                    aria-label="Cerrar"
                    whileHover={{ rotate: 90, scale: 1.1 }}
                    transition={{ duration: 0.2 }}
                  >
                    <X size={20} />
                  </motion.button>
                </div>
                <motion.p
                  variants={{ hidden: { opacity: 0, y: 10 }, visible: { opacity: 1, y: 0 } }}
                  className="font-body text-muted-foreground"
                  style={{ lineHeight: 1.7 }}
                >
                  {selectedProject.desc}
                </motion.p>

                {/* Live link CTA */}
                {selectedProject.link && (
                  <motion.div
                    variants={{ hidden: { opacity: 0, y: 10 }, visible: { opacity: 1, y: 0 } }}
                    className="mt-6"
                  >
                    <Link
                      to={selectedProject.link}
                      className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-heading font-bold text-sm tracking-widest uppercase transition-opacity hover:opacity-80"
                      style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                      onClick={() => setSelectedProject(null)}
                    >
                      <Gamepad2 size={15} />
                      Jugar ahora
                    </Link>
                  </motion.div>
                )}
              </motion.div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
