import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import {
  ArrowRight,
  Check,
  AlertTriangle,
  BookOpen,
  Compass,
  MessageCircle,
  Cpu,
  Sparkles,
  ExternalLink,
  Cloud,
  Mail,
  Globe,
  BrainCircuit,
  TrendingUp,
} from 'lucide-react';
import { STAGES, PRINCIPLES, COSTLY_MISTAKES } from '@/data/roadmap';
import {
  AI_MODULES,
  AI_PRINCIPLES,
  AI_MISTAKES,
  CLAUDE_REFERRAL_URL,
} from '@/data/aiCurriculum';
import TechStack from '@/components/TechStack';

const CLAUDE_QR = '/assets/qr-codes/qr-go_claude-vOGMsR.png';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+empezar+mi+proyecto';

// Inversión tangible: dónde poner el capital de forma inteligente.
const TANGIBLE_INVESTMENTS = [
  {
    icon: Cloud,
    asset: 'Nube e infraestructura',
    thesis: 'No alquilas un servidor: compras tiempo de actividad, velocidad y tranquilidad. La base sobre la que todo lo demás rinde.',
  },
  {
    icon: Mail,
    asset: 'Microsoft 365 · Google Workspace',
    thesis: 'Correo profesional, documentos colaborativos y suite ofimática. La diferencia entre parecer un proyecto y operar como una empresa.',
  },
  {
    icon: BrainCircuit,
    asset: 'Licencias de IA',
    thesis: 'Una licencia de IA bien aprovechada multiplica la producción de cada persona de tu equipo. Es el empleado más rentable que vas a contratar.',
  },
  {
    icon: Globe,
    asset: 'Dominios y marca digital',
    thesis: 'Tu nombre en internet es un activo que se revaloriza. Asegurarlo temprano es barato; recuperarlo tarde puede ser imposible.',
  },
];

export default function AprendePage() {
  const site = 'https://gano.digital';
  const title = 'Sala de Lectura — Soberanía digital + IA aplicada | Gano Digital';
  const description =
    'Guía para emprendedores: el roadmap de un negocio digital sólido y un temario profundo de IA aplicada — tooling, procesos, prácticas, guidelines y documentación. Qué necesitas, por qué importa y cómo lograrlo.';
  const ogImage =
    'https://gano.digital/api/og?title=Sala+de+Lectura&description=Soberan%C3%ADa+digital+e+IA+aplicada,+de+la+idea+a+la+pr%C3%A1ctica.&tag=Gano+Digital';

  const [activeStage, setActiveStage] = useState(STAGES[0].id);
  const stageRefs = useRef<Record<string, HTMLElement | null>>({});

  const [activeModule, setActiveModule] = useState(AI_MODULES[0].id);
  const moduleRefs = useRef<Record<string, HTMLElement | null>>({});

  // Resalta en el índice la etapa visible en pantalla.
  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) setActiveStage(entry.target.id);
        });
      },
      { rootMargin: '-30% 0px -55% 0px', threshold: 0 },
    );
    Object.values(stageRefs.current).forEach((el) => el && observer.observe(el));
    return () => observer.disconnect();
  }, []);

  // Resalta en el índice el módulo de IA visible en pantalla.
  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) setActiveModule(entry.target.id);
        });
      },
      { rootMargin: '-30% 0px -55% 0px', threshold: 0 },
    );
    Object.values(moduleRefs.current).forEach((el) => el && observer.observe(el));
    return () => observer.disconnect();
  }, []);

  const scrollTo = (id: string) => {
    stageRefs.current[id]?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  const scrollToModule = (id: string) => {
    moduleRefs.current[id]?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  const jsonLd = {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'Article',
        '@id': `${site}/aprende#article`,
        headline: 'De la idea a la soberanía digital: el roadmap del emprendedor',
        description,
        url: `${site}/aprende`,
        isPartOf: { '@id': `${site}/#website` },
        publisher: { '@id': `${site}/#organization` },
        inLanguage: 'es',
      },
      {
        '@type': 'Course',
        '@id': `${site}/aprende#curso-ia`,
        name: 'IA aplicada: tooling, procesos, prácticas, guidelines y documentación',
        description:
          'Temario práctico para trabajar con inteligencia artificial en un negocio digital real, en cinco módulos.',
        url: `${site}/aprende`,
        inLanguage: 'es',
        provider: { '@id': `${site}/#organization` },
        hasCourseInstance: AI_MODULES.map((m) => ({
          '@type': 'CourseInstance',
          name: m.title,
          courseMode: 'online',
          description: m.essence,
        })),
      },
    ],
  };

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/aprende`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="article" />
        <meta property="og:url" content={`${site}/aprende`} />
        <meta property="og:image" content={ogImage} />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify(jsonLd)}</script>
      </Helmet>

      {/* ── HERO EDITORIAL ── */}
      <section className="relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div
          className="absolute inset-0 pointer-events-none"
          style={{
            backgroundImage: `linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px)`,
            backgroundSize: '60px 60px',
          }}
        />
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 left-1/3 w-[55vw] h-[55vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.09) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.07, 1], opacity: [0.6, 1, 0.6] }}
          transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
        />

        <div className="relative max-w-4xl mx-auto px-6 md:px-10 pt-40 pb-24 md:pt-48 md:pb-32">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 mb-8 border border-border rounded-full px-3 py-1.5"
          >
            <BookOpen size={13} style={{ color: CHARTREUSE }} aria-hidden="true" />
            <span className="font-mono text-xs uppercase tracking-[0.15em] text-foreground/80">
              Sala de lectura
            </span>
          </motion.div>

          <h1
            className="font-heading font-bold text-foreground mb-8"
            style={{ fontSize: 'clamp(40px, 6.5vw, 84px)', letterSpacing: '-0.03em', lineHeight: 1.03 }}
          >
            <motion.span
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.7, ease: 'easeOut' }}
              className="inline-block"
            >
              De la idea a la <span style={{ color: CHARTREUSE }}>soberanía digital.</span>
            </motion.span>
          </h1>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="font-body text-lg md:text-2xl text-muted-foreground max-w-2xl"
            style={{ lineHeight: 1.6 }}
          >
            Todo gran negocio digital se construye en etapas. Esta es la guía honesta de
            qué necesitas en cada una — y por qué — para que no improvises tu futuro.
            Léela con calma. Está escrita para inspirarte a empezar.
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.35 }}
            className="flex items-center gap-3 mt-12 text-muted-foreground"
          >
            <Compass size={16} aria-hidden="true" />
            <span className="font-body text-sm">5 etapas · 8 minutos de lectura · Sin tecnicismos innecesarios</span>
          </motion.div>
        </div>
      </section>

      {/* ── MANIFIESTO DE PRINCIPIOS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(26px, 3.6vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
          >
            Tres verdades antes de empezar.
          </motion.h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {PRINCIPLES.map((p, i) => (
              <motion.div
                key={p.title}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                className="border-t-2 pt-6"
                style={{ borderColor: CHARTREUSE }}
              >
                <p className="font-mono text-sm mb-3" style={{ color: CHARTREUSE }}>0{i + 1}</p>
                <h3 className="font-heading text-xl font-bold text-foreground mb-3">{p.title}</h3>
                <p className="font-body text-muted-foreground leading-relaxed">{p.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── ROADMAP: ÍNDICE STICKY + ETAPAS ── */}
      <section className="py-16 md:py-24" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            {/* Índice de lectura sticky */}
            <aside className="lg:col-span-3">
              <div className="lg:sticky lg:top-28">
                <p className="font-mono text-xs uppercase tracking-[0.15em] text-muted-foreground mb-5">
                  El roadmap
                </p>
                <nav className="flex flex-col gap-1">
                  {STAGES.map((stage) => {
                    const isActive = activeStage === stage.id;
                    return (
                      <button
                        key={stage.id}
                        onClick={() => scrollTo(stage.id)}
                        className="group flex items-center gap-3 py-2.5 text-left transition-colors duration-200"
                      >
                        <span
                          className="font-mono text-xs tabular-nums transition-colors duration-200"
                          style={{ color: isActive ? CHARTREUSE : 'hsl(var(--muted-foreground))' }}
                        >
                          {stage.number}
                        </span>
                        <span
                          className="font-body text-sm transition-colors duration-200"
                          style={{ color: isActive ? 'hsl(var(--foreground))' : 'hsl(var(--muted-foreground))' }}
                        >
                          {stage.kicker}
                        </span>
                      </button>
                    );
                  })}
                </nav>
              </div>
            </aside>

            {/* Etapas */}
            <div className="lg:col-span-9 flex flex-col gap-24 md:gap-32">
              {STAGES.map((stage) => (
                <article
                  key={stage.id}
                  id={stage.id}
                  ref={(el) => { stageRefs.current[stage.id] = el; }}
                  className="scroll-mt-28"
                >
                  <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-100px' }}
                    transition={{ duration: 0.6, ease: 'easeOut' }}
                  >
                    {/* Encabezado de etapa */}
                    <div className="flex items-baseline gap-5 mb-6">
                      <span
                        className="font-heading font-bold leading-none tabular-nums shrink-0"
                        style={{ fontSize: 'clamp(48px, 7vw, 96px)', color: 'rgba(217,126,58,0.18)', letterSpacing: '-0.04em' }}
                      >
                        {stage.number}
                      </span>
                      <div>
                        <p className="font-mono text-xs uppercase tracking-[0.18em] mb-2" style={{ color: CHARTREUSE }}>
                          {stage.kicker}
                        </p>
                        <h2
                          className="font-heading font-bold text-foreground"
                          style={{ fontSize: 'clamp(26px, 3.4vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.08 }}
                        >
                          {stage.title}
                        </h2>
                      </div>
                    </div>

                    {/* Esencia */}
                    <p
                      className="font-heading text-foreground/90 mb-8 pl-5 border-l-2"
                      style={{ borderColor: CHARTREUSE, fontSize: 'clamp(18px, 2vw, 24px)', lineHeight: 1.4, fontStyle: 'italic' }}
                    >
                      {stage.essence}
                    </p>

                    {/* Cuerpo */}
                    <div className="flex flex-col gap-5 mb-10 max-w-2xl">
                      {stage.body.map((para, i) => (
                        <p key={i} className="font-body text-base md:text-lg text-muted-foreground" style={{ lineHeight: 1.75 }}>
                          {para}
                        </p>
                      ))}
                    </div>

                    {/* Checklist + enabler */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                      <div className="rounded-md border border-border p-6" style={{ backgroundColor: 'hsl(var(--card))' }}>
                        <p className="font-mono text-xs uppercase tracking-[0.12em] text-muted-foreground mb-4">
                          Tu checklist
                        </p>
                        <ul className="flex flex-col gap-3">
                          {stage.checklist.map((item) => (
                            <li key={item} className="flex items-start gap-3">
                              <span
                                className="flex items-center justify-center h-5 w-5 rounded-xl shrink-0 mt-0.5"
                                style={{ backgroundColor: 'rgba(217,126,58,0.14)' }}
                              >
                                <Check size={12} style={{ color: CHARTREUSE }} aria-hidden="true" />
                              </span>
                              <span className="font-body text-sm text-foreground/90 leading-snug">{item}</span>
                            </li>
                          ))}
                        </ul>
                      </div>

                      <div className="flex flex-col justify-between h-full gap-6">
                        {stage.pull && (
                          <p
                            className="font-heading font-bold text-foreground"
                            style={{ fontSize: 'clamp(18px, 2.2vw, 26px)', letterSpacing: '-0.02em', lineHeight: 1.3 }}
                          >
                            “{stage.pull}”
                          </p>
                        )}
                        <Link
                          to={stage.enabler.href}
                          className="group inline-flex items-center gap-2 self-start h-11 px-6 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.1em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
                        >
                          {stage.enabler.label}
                          <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
                        </Link>
                      </div>
                    </div>
                  </motion.div>
                </article>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── ERRORES QUE CUESTAN CARO ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="flex items-center gap-2 mb-4"
          >
            <AlertTriangle size={16} style={{ color: CHARTREUSE }} aria-hidden="true" />
            <span className="font-mono text-xs uppercase tracking-[0.18em] text-muted-foreground">
              Lo que nadie te advierte
            </span>
          </motion.div>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Cuatro atajos que terminan saliendo carísimos.
          </motion.h2>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {COSTLY_MISTAKES.map((m, i) => (
              <motion.div
                key={m.mistake}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.08 }}
                className="rounded-md border border-border p-6 md:p-7"
                style={{ backgroundColor: 'hsl(var(--card))' }}
              >
                <p className="font-heading text-lg font-bold text-foreground mb-2">{m.mistake}</p>
                <p className="font-body text-sm text-muted-foreground leading-relaxed">{m.cost}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── INTERLUDIO: ENTRA LA IA ── */}
      <section className="py-20 md:py-28 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 right-1/4 w-[50vw] h-[50vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.08) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.08, 1], opacity: [0.5, 0.9, 0.5] }}
          transition={{ duration: 9, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-4xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 mb-7 border border-border rounded-full px-3 py-1.5"
          >
            <Cpu size={13} style={{ color: CHARTREUSE }} aria-hidden="true" />
            <span className="font-mono text-xs uppercase tracking-[0.15em] text-foreground/80">
              Parte II · IA aplicada
            </span>
          </motion.div>
          <motion.h2
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="font-heading font-bold text-foreground mb-7"
            style={{ fontSize: 'clamp(34px, 5.5vw, 68px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
          >
            La infraestructura te da el terreno.
            <br />
            <span style={{ color: CHARTREUSE }}>La IA te da la velocidad.</span>
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 18 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl"
            style={{ lineHeight: 1.65 }}
          >
            Construir un negocio sólido ya no es solo cuestión de servidores y dominios. Es saber
            trabajar con inteligencia artificial de forma profesional — no jugando, sino con tooling,
            procesos, prácticas y documentación que escalan. Este es el temario honesto de cómo se hace.
          </motion.p>
        </div>
      </section>

      {/* ── MANIFIESTO DE IA ── */}
      <section className="py-16 md:py-24" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(26px, 3.6vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
          >
            Tres verdades sobre la IA.
          </motion.h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {AI_PRINCIPLES.map((p, i) => (
              <motion.div
                key={p.title}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                className="border-t-2 pt-6"
                style={{ borderColor: CHARTREUSE }}
              >
                <p className="font-mono text-sm mb-3" style={{ color: CHARTREUSE }}>0{i + 1}</p>
                <h3 className="font-heading text-xl font-bold text-foreground mb-3">{p.title}</h3>
                <p className="font-body text-muted-foreground leading-relaxed">{p.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── STACK DE HERRAMIENTAS (IA + FRAMEWORKS + INFRA) ── */}
      <TechStack
        kicker="El stack del temario"
        title="Las herramientas detrás de cada módulo."
        subtitle="Todo lo que enseñamos arriba lo aplicamos con estas herramientas: IA de frontera para pensar y escribir, frameworks probados para construir, e infraestructura sólida para sostenerlo."
        background="hsl(var(--muted))"
      />

      {/* ── TEMARIO DE IA: ÍNDICE STICKY + MÓDULOS ── */}
      <section className="py-16 md:py-24" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            {/* Índice del temario */}
            <aside className="lg:col-span-3">
              <div className="lg:sticky lg:top-28">
                <p className="font-mono text-xs uppercase tracking-[0.15em] text-muted-foreground mb-5">
                  El temario
                </p>
                <nav className="flex flex-col gap-1">
                  {AI_MODULES.map((mod) => {
                    const isActive = activeModule === mod.id;
                    return (
                      <button
                        key={mod.id}
                        onClick={() => scrollToModule(mod.id)}
                        className="group flex items-center gap-3 py-2.5 text-left transition-colors duration-200"
                      >
                        <span
                          className="font-mono text-xs tabular-nums transition-colors duration-200"
                          style={{ color: isActive ? CHARTREUSE : 'hsl(var(--muted-foreground))' }}
                        >
                          {mod.number}
                        </span>
                        <span
                          className="font-body text-sm transition-colors duration-200"
                          style={{ color: isActive ? 'hsl(var(--foreground))' : 'hsl(var(--muted-foreground))' }}
                        >
                          {mod.kicker}
                        </span>
                      </button>
                    );
                  })}
                </nav>
              </div>
            </aside>

            {/* Módulos */}
            <div className="lg:col-span-9 flex flex-col gap-24 md:gap-32">
              {AI_MODULES.map((mod) => (
                <article
                  key={mod.id}
                  id={mod.id}
                  ref={(el) => { moduleRefs.current[mod.id] = el; }}
                  className="scroll-mt-28"
                >
                  <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-100px' }}
                    transition={{ duration: 0.6, ease: 'easeOut' }}
                  >
                    {/* Encabezado de módulo */}
                    <div className="flex items-baseline gap-5 mb-6">
                      <span
                        className="font-heading font-bold leading-none tabular-nums shrink-0"
                        style={{ fontSize: 'clamp(48px, 7vw, 96px)', color: 'rgba(217,126,58,0.18)', letterSpacing: '-0.04em' }}
                      >
                        {mod.number}
                      </span>
                      <div>
                        <p className="font-mono text-xs uppercase tracking-[0.18em] mb-2" style={{ color: CHARTREUSE }}>
                          {mod.kicker}
                        </p>
                        <h2
                          className="font-heading font-bold text-foreground"
                          style={{ fontSize: 'clamp(26px, 3.4vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.08 }}
                        >
                          {mod.title}
                        </h2>
                      </div>
                    </div>

                    {/* Esencia */}
                    <p
                      className="font-heading text-foreground/90 mb-8 pl-5 border-l-2"
                      style={{ borderColor: CHARTREUSE, fontSize: 'clamp(18px, 2vw, 24px)', lineHeight: 1.4, fontStyle: 'italic' }}
                    >
                      {mod.essence}
                    </p>

                    {/* Cuerpo */}
                    <div className="flex flex-col gap-5 mb-10 max-w-2xl">
                      {mod.body.map((para, i) => (
                        <p key={i} className="font-body text-base md:text-lg text-muted-foreground" style={{ lineHeight: 1.75 }}>
                          {para}
                        </p>
                      ))}
                    </div>

                    {/* Checklist + recurso */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                      <div className="rounded-md border border-border p-6" style={{ backgroundColor: 'hsl(var(--card))' }}>
                        <p className="font-mono text-xs uppercase tracking-[0.12em] text-muted-foreground mb-4">
                          En la práctica
                        </p>
                        <ul className="flex flex-col gap-3">
                          {mod.checklist.map((item) => (
                            <li key={item} className="flex items-start gap-3">
                              <span
                                className="flex items-center justify-center h-5 w-5 rounded-xl shrink-0 mt-0.5"
                                style={{ backgroundColor: 'rgba(217,126,58,0.14)' }}
                              >
                                <Check size={12} style={{ color: CHARTREUSE }} aria-hidden="true" />
                              </span>
                              <span className="font-body text-sm text-foreground/90 leading-snug">{item}</span>
                            </li>
                          ))}
                        </ul>
                      </div>

                      <div className="flex flex-col justify-between h-full gap-6">
                        {mod.pull && (
                          <p
                            className="font-heading font-bold text-foreground"
                            style={{ fontSize: 'clamp(18px, 2.2vw, 26px)', letterSpacing: '-0.02em', lineHeight: 1.3 }}
                          >
                            “{mod.pull}”
                          </p>
                        )}
                        {mod.resource.external ? (
                          <a
                            href={mod.resource.href}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="group inline-flex items-center gap-2 self-start h-11 px-6 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.1em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
                          >
                            {mod.resource.label}
                            <ExternalLink size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
                          </a>
                        ) : (
                          <Link
                            to={mod.resource.href}
                            className="group inline-flex items-center gap-2 self-start h-11 px-6 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.1em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
                          >
                            {mod.resource.label}
                            <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
                          </Link>
                        )}
                      </div>
                    </div>
                  </motion.div>
                </article>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ── MITOS DE IA QUE CUESTAN CARO ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="flex items-center gap-2 mb-4"
          >
            <AlertTriangle size={16} style={{ color: CHARTREUSE }} aria-hidden="true" />
            <span className="font-mono text-xs uppercase tracking-[0.18em] text-muted-foreground">
              Lo que separa al amateur del profesional
            </span>
          </motion.div>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Cuatro errores que arruinan un buen proyecto de IA.
          </motion.h2>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {AI_MISTAKES.map((m, i) => (
              <motion.div
                key={m.mistake}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.08 }}
                className="rounded-md border border-border p-6 md:p-7"
                style={{ backgroundColor: 'hsl(var(--card))' }}
              >
                <p className="font-heading text-lg font-bold text-foreground mb-2">{m.mistake}</p>
                <p className="font-body text-sm text-muted-foreground leading-relaxed">{m.cost}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── INVERSIÓN TANGIBLE ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="flex items-center gap-2 mb-4"
          >
            <TrendingUp size={16} style={{ color: CHARTREUSE }} aria-hidden="true" />
            <span className="font-mono text-xs uppercase tracking-[0.18em] text-muted-foreground">
              Dónde poner el capital
            </span>
          </motion.div>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-5 max-w-2xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            La tecnología no es un gasto. Es el activo que mejor se revaloriza.
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground mb-14 max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            Las empresas que crecen no recortan en herramientas: invierten en las correctas
            y aprenden a exprimirlas. Aquí es donde el capital rinde de verdad — y donde te
            acompaño a elegir, instalar y aprovechar cada pieza.
          </motion.p>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            {TANGIBLE_INVESTMENTS.map((inv) => {
              const Icon = inv.icon;
              return (
                <motion.div
                  key={inv.asset}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  className="flex gap-5 p-7 rounded-md"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <span className="flex items-center justify-center h-12 w-12 rounded-xl shrink-0" style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}>
                    <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="font-heading font-bold text-foreground text-xl mb-2" style={{ letterSpacing: '-0.01em' }}>{inv.asset}</h3>
                    <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{inv.thesis}</p>
                  </div>
                </motion.div>
              );
            })}
          </motion.div>

          <motion.div
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="mt-10"
          >
            <Link
              to="/soluciones-ia"
              className="group inline-flex items-center gap-2 text-sm font-body font-medium transition-colors duration-200 hover:opacity-80"
              style={{ color: CHARTREUSE }}
            >
              Ver cómo aplico la IA en tu negocio <ArrowRight size={14} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── CTA DE REFERIDO A CLAUDE ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-5xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 28 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="rounded-lg overflow-hidden border"
            style={{ borderColor: 'hsl(var(--border))', backgroundColor: 'hsl(var(--card))' }}
          >
            <div className="grid grid-cols-1 md:grid-cols-5">
              {/* Texto */}
              <div className="md:col-span-3 p-8 md:p-11 flex flex-col justify-center">
                <div className="inline-flex items-center gap-2 mb-5 self-start border border-border rounded-full px-3 py-1.5">
                  <Sparkles size={13} style={{ color: CHARTREUSE }} aria-hidden="true" />
                  <span className="font-mono text-xs uppercase tracking-[0.15em] text-foreground/80">
                    La herramienta que recomendamos
                  </span>
                </div>
                <h2
                  className="font-heading font-bold text-foreground mb-4"
                  style={{ fontSize: 'clamp(28px, 4vw, 46px)', letterSpacing: '-0.03em', lineHeight: 1.08 }}
                >
                  Empieza con <span style={{ color: CHARTREUSE }}>Claude</span>.
                </h2>
                <p className="font-body text-base md:text-lg text-muted-foreground mb-7" style={{ lineHeight: 1.65 }}>
                  Todo el temario de arriba lo aplicamos a diario con Claude — el asistente de IA que
                  usamos para razonar, escribir y construir. Regístrate con nuestro enlace y empieza con
                  el mismo aliado con el que trabajamos. Es más que IA: un aliado para pensar más, crear
                  más y lograr más.
                </p>
                <div className="flex flex-wrap items-center gap-4">
                  <a
                    href={CLAUDE_REFERRAL_URL}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="group inline-flex items-center gap-2 h-12 px-8 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
                  >
                    Registrarme en Claude
                    <ExternalLink size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
                  </a>
                  <span className="font-body text-xs text-muted-foreground">
                    Beneficios para ti y para nosotros al unirte.
                  </span>
                </div>
              </div>

              {/* QR */}
              <div
                className="md:col-span-2 p-8 md:p-11 flex flex-col items-center justify-center gap-4 border-t md:border-t-0 md:border-l"
                style={{ borderColor: 'hsl(var(--border))', backgroundColor: 'rgba(217,126,58,0.04)' }}
              >
                <p className="font-mono text-xs uppercase tracking-[0.18em]" style={{ color: CHARTREUSE }}>
                  — Escanéame —
                </p>
                <div className="rounded-md p-3" style={{ backgroundColor: '#F5F5F5' }}>
                  <img
                    src={CLAUDE_QR}
                    alt="Código QR para registrarse en Claude con el enlace de referido de Gano Digital"
                    width={176}
                    height={176}
                    loading="lazy"
                    className="w-44 h-44 object-contain"
                  />
                </div>
                <p className="font-body text-xs text-muted-foreground text-center max-w-[12rem]">
                  Apunta tu cámara al código para abrir el registro.
                </p>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── CTA DE CIERRE ── */}
      <section className="py-24 md:py-36 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[60vw] h-[60vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.07) 0%, transparent 60%)' }}
          animate={{ scale: [1, 1.06, 1], opacity: [0.5, 0.9, 0.5] }}
          transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-3xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(34px, 5.5vw, 68px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
          >
            Ya tienes el mapa.
            <br />
            <span style={{ color: CHARTREUSE }}>Ahora construyamos juntos.</span>
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="font-body text-lg text-muted-foreground mb-10 max-w-xl mx-auto"
          >
            No tienes que recorrer las cinco etapas solo. Empieza por donde estés —
            nosotros ponemos la infraestructura, la ingeniería y el acompañamiento.
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
              className="group inline-flex items-center gap-2 h-12 px-8 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Empezar mi proyecto <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 h-12 px-8 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <MessageCircle size={16} /> Hablar con un ingeniero
            </a>
          </motion.div>
        </div>
      </section>
    </>
  );
}
