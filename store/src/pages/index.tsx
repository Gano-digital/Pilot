import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, useTransform } from 'motion/react';
import { ArrowRight, ChevronDown, Server, Shield, Globe, Gauge, MessageCircle, Sparkles, Bot, Stethoscope, Check } from 'lucide-react';
import ServiceCard from '@/components/catalog/ServiceCard';
import CostOfFragility from '@/components/narrative/CostOfFragility';
import Manifesto from '@/components/narrative/Manifesto';
import TrustSignals from '@/components/narrative/TrustSignals';
import HowItWorks from '@/components/narrative/HowItWorks';
import Testimonials from '@/components/narrative/Testimonials';
import LiveInfraPanel from '@/components/narrative/LiveInfraPanel';
import SelectiveOnboarding from '@/components/narrative/SelectiveOnboarding';
import WorkingMethod from '@/components/narrative/WorkingMethod';
import DiagnosticoTool from '@/components/DiagnosticoTool';
import { Reveal } from '@/components/motion/Reveal';
import { usePointerField } from '@/lib/motion-physics';
import { SERVICES } from '@/data/catalog';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+asesor%C3%ADa';

const featuredCatalog = SERVICES.filter((s) =>
  ['business-nvme', 'ultimate', 'vps-alpha'].includes(s.id),
);

const heroWords = ['No', 'vendo', 'servidores.', 'Catapulto', 'empresas.'];

// Stats derivadas de datos reales del catálogo — sin cifras inventadas.
const totalServices = SERVICES.length;

const stats = [
  { value: '1:1', label: 'Acompañamiento directo, sin call centers' },
  { value: '360°', label: 'Corporativo, pyme, backend y frontend' },
  { value: `${totalServices}`, label: 'Servicios curados, no catálogo genérico' },
  { value: '99.95%', label: 'SLA de disponibilidad' },
];

// Pilares pedagógicos — qué resuelve cada frente, en lenguaje claro.
const pillars = [
  {
    icon: Gauge,
    title: 'Velocidad que convierte',
    desc: 'Discos NVMe, CDN global y caché Redis. Cada milisegundo ahorrado es ranking en Google y conversión en tu checkout.',
  },
  {
    icon: Shield,
    title: 'Seguridad sin drama',
    desc: 'Firewall de aplicación, SSL, anti-DDoS y backups automáticos. Duermes tranquilo aunque internet no descanse.',
  },
  {
    icon: Globe,
    title: 'Dominios y correo',
    desc: 'Tu nombre en internet y correo profesional con tu dominio. La diferencia entre parecer freelance y empresa seria.',
  },
  {
    icon: Server,
    title: 'Hasta tu propio servidor',
    desc: 'Cuando WordPress se queda corto: VPS con root completo y un ingeniero que conoce tu proyecto por nombre.',
  },
];

const featuredWork = [
  { title: 'Migración WooCommerce sin downtime', category: 'Migración', image: '/airo-assets/images/portfolio/migracion-woocommerce', href: '/portfolio' },
  { title: 'Blindaje de seguridad (red team)', category: 'Seguridad', image: '/airo-assets/images/portfolio/blindaje-seguridad', href: '/portfolio' },
  { title: 'Optimización de velocidad NVMe', category: 'Infraestructura', image: '/airo-assets/images/portfolio/optimizacion-velocidad', href: '/portfolio' },
];

export default function HomePage() {
  const site = 'https://gano.digital';
  const title = 'Gano Digital — Hosting, Dominios y Seguridad con Ingeniería Curada';
  const description =
    'Infraestructura digital soberana: hosting WordPress de alto rendimiento, dominios, correo profesional y seguridad. Planes claros y soporte real en Colombia.';
  const ogImage =
    'https://gano.digital/api/og?title=Tu+infraestructura,+soberana.&description=Hosting,+dominios+y+seguridad+con+ingenier%C3%ADa+curada.&tag=Gano+Digital';

  // Campo de puntero — el glow y la textura del hero reaccionan al mouse.
  const pointer = usePointerField(1);
  const glowX = useTransform(pointer.x, (v) => v * 60);
  const glowY = useTransform(pointer.y, (v) => v * 60);
  const gridX = useTransform(pointer.x, (v) => v * -18);
  const gridY = useTransform(pointer.y, (v) => v * -18);

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Gano Digital — Infraestructura digital soberana" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@graph': [
            { '@type': 'WebSite', '@id': `${site}/#website`, name: 'Gano Digital', url: `${site}/` },
            { '@type': 'Organization', '@id': `${site}/#organization`, name: 'Gano Digital', url: `${site}/`, areaServed: 'CO' },
            { '@type': 'WebPage', '@id': `${site}/#webpage`, url: `${site}/`, isPartOf: { '@id': `${site}/#website` }, about: { '@id': `${site}/#organization` }, datePublished: '2026-06-19', dateModified: '2026-06-19' },
          ],
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/" locale="es" />

      {/* ── HERO ── */}
      <section
        className="relative min-h-screen flex flex-col justify-center overflow-hidden"
        style={{ backgroundColor: 'hsl(var(--background))' }}
      >
        {/* Grid texture — deriva suavemente con el puntero */}
        <motion.div
          className="absolute inset-0 pointer-events-none"
          style={{
            x: gridX,
            y: gridY,
            backgroundImage: `linear-gradient(hsl(var(--foreground) / 0.04) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--foreground) / 0.04) 1px, transparent 1px)`,
            backgroundSize: '60px 60px',
          }}
        />
        {/* Glow ambiental — late solo y además persigue al puntero */}
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 -right-1/4 w-[60vw] h-[60vw] rounded-full pointer-events-none"
          style={{ x: glowX, y: glowY, background: 'radial-gradient(circle, rgba(217,126,58,0.10) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.08, 1], opacity: [0.6, 1, 0.6] }}
          transition={{ duration: 7, repeat: Infinity, ease: 'easeInOut' }}
        />

        <div className="relative max-w-7xl mx-auto px-6 md:px-10 pt-32 pb-24 w-full">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, ease: 'easeOut' }}
            className="flex items-center gap-3 mb-12"
          >
            <span className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body text-foreground/90 border border-border rounded-full px-3 py-1.5">
              <span className="w-1.5 h-1.5 rounded-full" style={{ backgroundColor: CHARTREUSE }} />
              Socio de crecimiento digital
            </span>
            <span className="hidden sm:inline text-xs tracking-[0.18em] uppercase font-body text-muted-foreground">
              Criterio de industria · Colombia &amp; LATAM
            </span>
          </motion.div>

          <h1 className="font-heading font-bold leading-none tracking-tight mb-8" style={{ fontSize: 'clamp(40px, 7.5vw, 96px)', letterSpacing: '-0.03em' }}>
            {heroWords.map((word, i) => (
              <motion.span
                key={i}
                initial={{ opacity: 0, y: 40 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: i * 0.08, ease: 'easeOut' }}
                className={`inline-block mr-[0.25em] ${i >= 3 ? 'text-primary' : 'text-foreground'}`}
              >
                {word}
              </motion.span>
            ))}
          </h1>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.4, ease: 'easeOut' }}
            className="text-lg md:text-xl text-muted-foreground font-body max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            Escucho tu modelo de negocio antes de proponer nada. Luego te acompaño
            personalmente a instalar, configurar y <span className="text-foreground">usar</span> las
            herramientas que multiplican tu capacidad — desde infraestructura que no se cae
            hasta inteligencia artificial que potencia a tu equipo. Mi oficio no es venderte servicios:
            es ponerte criterio de adentro de la industria de tu lado.
          </motion.p>

          {/* CTAs */}
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.55, ease: 'easeOut' }}
            className="flex flex-wrap items-center gap-4 mt-10"
          >
            <Link
              to="/catalogo"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Ver planes <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
            <a
              href="#diagnostico"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <Stethoscope size={16} /> Diagnóstico gratis
            </a>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <MessageCircle size={16} /> Conversemos tu proyecto
            </a>
          </motion.div>

          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.6, delay: 0.8 }}
            className="flex items-end justify-between mt-20 md:mt-28"
          >
            <div className="flex flex-col items-center gap-2">
              <span className="text-xs tracking-widest text-muted-foreground uppercase font-body">Explora</span>
              <motion.div animate={{ y: [0, 6, 0] }} transition={{ duration: 1.4, repeat: Infinity, ease: 'easeInOut' }}>
                <ChevronDown size={18} style={{ color: CHARTREUSE }} />
              </motion.div>
            </div>
            <div className="flex items-center gap-2">
              <span className="w-2 h-2 rounded-full animate-pulse" style={{ backgroundColor: CHARTREUSE }} />
              <span className="text-sm font-body text-foreground/70">Soporte activo en Colombia</span>
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── ACTO 1: EL PROBLEMA ── */}
      <CostOfFragility />

      {/* ── PILARES PEDAGÓGICOS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] text-muted-foreground uppercase font-body mb-4"
          >
            Por qué importa
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Cuatro frentes que deciden si tu sitio crece o se cae.
          </motion.h2>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
          >
            {pillars.map((p) => {
              const Icon = p.icon;
              return (
                <motion.div
                  key={p.title}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  whileHover={{ y: -6 }}
                  className="group p-6 rounded-xl border border-border bg-card transition-colors duration-300 hover:border-primary"
                >
                  <motion.span
                    whileHover={{ rotate: -8, scale: 1.08 }}
                    transition={{ type: 'spring', stiffness: 400, damping: 12 }}
                    className="flex items-center justify-center h-12 w-12 rounded-xl bg-muted text-primary mb-5"
                  >
                    <Icon size={24} strokeWidth={1.75} aria-hidden="true" />
                  </motion.span>
                  <h3 className="font-heading text-xl font-bold text-card-foreground mb-2">{p.title}</h3>
                  <p className="font-body text-sm text-muted-foreground leading-relaxed">{p.desc}</p>
                </motion.div>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* ── STATS ── */}
      <section className="py-16 md:py-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-6">
            {stats.map((stat, i) => (
              <motion.div
                key={stat.label}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1, ease: 'easeOut' }}
                className="text-center md:text-left"
              >
                <p className="font-heading font-bold leading-none mb-2 tabular-nums" style={{ fontSize: 'clamp(40px, 4.5vw, 64px)', color: CHARTREUSE, letterSpacing: '-0.03em' }}>
                  {stat.value}
                </p>
                <p className="text-sm font-body text-muted-foreground">{stat.label}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── SHOWCASE TÉCNICO VIVO (prueba de oficio) ── */}
      <Reveal as="div" parallax={28} direction="none">
        <LiveInfraPanel />
      </Reveal>

      {/* ── MANIFIESTO DE MARCA ── */}
      <Manifesto />

      {/* ── MI FORMA DE TRABAJAR (componente humano) ── */}
      <Reveal as="div" parallax={22} direction="none">
        <WorkingMethod />
      </Reveal>

      {/* ── DIAGNÓSTICO OPERATIVO 360° ── */}
      <section id="diagnostico" className="py-24 md:py-32 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 right-0 w-[50vw] h-[50vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.08) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.08, 1], opacity: [0.5, 0.9, 0.5] }}
          transition={{ duration: 9, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
            {/* Encabezado pegajoso */}
            <div className="lg:col-span-5 lg:sticky lg:top-28">
              <motion.span
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5 }}
                className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body border border-border rounded-full px-3 py-1.5 mb-6"
                style={{ color: CHARTREUSE }}
              >
                <Stethoscope size={12} /> Gratis · 2 minutos
              </motion.span>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.1, ease: 'easeOut' as const }}
                className="font-heading font-bold text-foreground mb-5"
                style={{ fontSize: 'clamp(30px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
              >
                ¿Sabes realmente qué necesita tu empresa?
              </motion.h2>
              <motion.p
                initial={{ opacity: 0, y: 16 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.2, ease: 'easeOut' as const }}
                className="font-body text-lg text-muted-foreground mb-6"
                style={{ lineHeight: 1.7 }}
              >
                Responde unas preguntas sobre tu operación —presencia web, correo, marketing,
                redes, equipo, seguridad y marca— y te muestro, sin humo, dónde estás dejando
                dinero o riesgo sobre la mesa. Cada hallazgo se traduce en una acción concreta.
              </motion.p>
              <motion.ul
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true }}
                variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.08 } } }}
                className="flex flex-col gap-3"
              >
                {[
                  'Mapeo 360° de tu operación digital',
                  'Recomendaciones priorizadas por urgencia',
                  'Informe a tu correo + conversación sin compromiso',
                ].map((t) => (
                  <motion.li
                    key={t}
                    variants={{ hidden: { opacity: 0, x: -12 }, visible: { opacity: 1, x: 0, transition: { duration: 0.4 } } }}
                    className="flex items-start gap-3"
                  >
                    <Check size={18} strokeWidth={2.25} className="mt-0.5 shrink-0" style={{ color: CHARTREUSE }} />
                    <span className="font-body text-sm text-foreground/80">{t}</span>
                  </motion.li>
                ))}
              </motion.ul>
            </div>

            {/* La herramienta */}
            <motion.div
              initial={{ opacity: 0, y: 28 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true, margin: '-80px' }}
              transition={{ duration: 0.6, ease: 'easeOut' as const }}
              className="lg:col-span-7"
            >
              <DiagnosticoTool />
            </motion.div>
          </div>
        </div>
      </section>

      {/* ── PLANES DESTACADOS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
            <div>
              <motion.p
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5 }}
                className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
              >
                Los más elegidos
              </motion.p>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.1 }}
                className="font-heading font-bold text-foreground"
                style={{ fontSize: 'clamp(32px, 4.5vw, 60px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                Planes que la gente contrata
              </motion.h2>
            </div>
            <motion.div
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <Link
                to="/catalogo"
                className="text-sm font-body flex items-center gap-2 transition-colors duration-200 hover:opacity-80 whitespace-nowrap"
                style={{ color: CHARTREUSE }}
              >
                Ver catálogo completo <ArrowRight size={14} />
              </Link>
            </motion.div>
          </div>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.08 } } }}
            className="grid grid-cols-1 md:grid-cols-3 gap-6"
          >
            {featuredCatalog.map((service) => (
              <ServiceCard key={service.id} service={service} />
            ))}
          </motion.div>
        </div>
      </section>

      {/* ── ACTO 3: LA PRUEBA (CONFIANZA) ── */}
      <TrustSignals />

      {/* ── PUENTE: IA APLICADA (oferta de mayor valor) ── */}
      <section className="py-20 md:py-28 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--card))', borderTop: '1px solid hsl(var(--border))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -bottom-1/3 -left-1/4 w-[55vw] h-[55vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.10) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.07, 1], opacity: [0.5, 0.9, 0.5] }}
          transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div className="lg:col-span-7">
              <motion.span
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5 }}
                className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body border border-border rounded-full px-3 py-1.5 mb-6"
                style={{ color: CHARTREUSE }}
              >
                <Sparkles size={12} /> Lo nuevo en Gano Digital
              </motion.span>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.1, ease: 'easeOut' as const }}
                className="font-heading font-bold text-foreground mb-5"
                style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                ¿Y si tu equipo pudiera hacer el doble, liberado del trabajo que lo agota?
              </motion.h2>
              <motion.p
                initial={{ opacity: 0, y: 16 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.2, ease: 'easeOut' as const }}
                className="font-body text-lg text-muted-foreground mb-8"
                style={{ lineHeight: 1.7 }}
              >
                Instalo inteligencia artificial que atiende lo repetitivo, automatiza
                procesos y mueve tu marketing — para devolverle horas a tu gente, no para
                reemplazarla. Siempre con personas al mando. La infraestructura es el cimiento;
                la IA bien aplicada es lo que construyes encima.
              </motion.p>
              <motion.div
                initial={{ opacity: 0 }}
                whileInView={{ opacity: 1 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.3 }}
              >
                <Link
                  to="/soluciones-ia"
                  className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
                >
                  Descubrir IA aplicada <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
                </Link>
              </motion.div>
            </div>
            <motion.div
              initial={{ opacity: 0, scale: 0.94 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, ease: 'easeOut' as const }}
              className="lg:col-span-5 flex justify-center"
            >
              <div
                className="flex items-center justify-center h-44 w-44 md:h-56 md:w-56 rounded-full"
                style={{ backgroundColor: 'rgba(217,126,58,0.10)', border: `1px solid ${CHARTREUSE}` }}
              >
                <Bot size={88} strokeWidth={1.2} style={{ color: CHARTREUSE }} aria-hidden="true" />
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* ── ACTO 4: EL CAMINO CLARO (CHECKOUT EXTERNO) ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <HowItWorks />
        </div>
      </section>

      {/* ── TESTIMONIOS ── */}
      <Testimonials />

      {/* ── PORTAFOLIO (prueba de oficio, secundario) ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="flex items-end justify-between mb-12">
            <div>
              <motion.p
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5 }}
                className="text-xs tracking-[0.2em] text-muted-foreground uppercase font-body mb-3"
              >
                También diseñamos
              </motion.p>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.1 }}
                className="font-heading font-bold text-foreground max-w-xl"
                style={{ fontSize: 'clamp(28px, 3.5vw, 48px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                Tu marca también es infraestructura
              </motion.h2>
            </div>
            <motion.div
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <Link
                to="/portfolio"
                className="text-sm font-body flex items-center gap-2 transition-colors duration-200 hover:opacity-80 whitespace-nowrap"
                style={{ color: CHARTREUSE }}
              >
                Ver portafolio <ArrowRight size={14} />
              </Link>
            </motion.div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
            {featuredWork.map((project, i) => (
              <motion.div
                key={project.title}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.6, delay: i * 0.12, ease: 'easeOut' }}
              >
                <Link to={project.href} className="group block">
                  <div className="relative overflow-hidden rounded-xl mb-4" style={{ aspectRatio: '4/3', backgroundColor: 'hsl(var(--muted))' }}>
                    <img
                      src={project.image}
                      alt={project.title}
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.02]"
                      loading="lazy"
                    />
                    <div className="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                      <span className="text-sm font-body tracking-widest uppercase" style={{ color: CHARTREUSE }}>Ver proyecto</span>
                    </div>
                    <div className="absolute inset-0 border-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-xl pointer-events-none" style={{ borderColor: CHARTREUSE }} />
                  </div>
                  <div className="flex items-center justify-between">
                    <h3 className="font-heading font-bold text-foreground text-lg">{project.title}</h3>
                    <span className="text-xs font-body px-2.5 py-1 rounded-full font-medium" style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}>
                      {project.category}
                    </span>
                  </div>
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── FOMO: ONBOARDING SELECTIVO ── */}
      <SelectiveOnboarding />

      {/* ── CTA ── */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="font-heading font-bold text-foreground mb-5"
            style={{ fontSize: 'clamp(36px, 5.5vw, 72px)', letterSpacing: '-0.03em' }}
          >
            ¿Listo para migrar sin miedo?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15, ease: 'easeOut' }}
            className="text-lg text-muted-foreground font-body mb-10 max-w-xl mx-auto"
          >
            Te ayudamos a elegir el plan correcto y movemos tu sitio sin que pierdas un solo cliente.
          </motion.p>
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.25 }}
            className="flex flex-wrap items-center justify-center gap-4"
          >
            <Link
              to="/catalogo"
              className="group inline-flex items-center gap-3 px-8 py-4 border text-sm font-heading font-bold tracking-wide uppercase transition-all duration-250 relative overflow-hidden"
              style={{ borderColor: CHARTREUSE, color: CHARTREUSE }}
            >
              <span className="relative z-10 group-hover:text-[#0A0A0A] transition-colors duration-250">Explorar planes</span>
              <ArrowRight size={16} className="relative z-10 group-hover:text-[#0A0A0A] transition-colors duration-250" />
              <span className="absolute inset-0 translate-x-[-101%] group-hover:translate-x-0 transition-transform duration-250 ease-out" style={{ backgroundColor: CHARTREUSE }} />
            </Link>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-8 py-4 text-sm font-heading font-bold tracking-wide uppercase text-foreground/70 hover:text-foreground transition-colors duration-200"
            >
              <MessageCircle size={16} /> WhatsApp directo
            </a>
          </motion.div>
        </div>
      </section>
    </>
  );
}
