import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { ArrowRight, Quote, Users } from 'lucide-react';
import References from '@/components/References';
import { TENETS, PILLARS, REFERENCES, PHILOSOPHY_INTRO } from '@/data/filosofia';

const CHARTREUSE = '#D97E3A';

const site = 'https://gano.digital';
const title = 'Nuestra Filosofía — IA que potencia a las personas | Gano Digital';
const description =
  'Nuestra postura sobre la inteligencia artificial: aumentar a las personas, no reemplazarlas. Un marco razonado y referenciado sobre IA ética, supervisión humana y colaboración — con la evidencia para que decidas por tu cuenta.';
const ogImage = `${site}/api/og?title=Nuestra+Filosof%C3%ADa&description=IA+que+potencia+a+las+personas%2C+no+que+las+reemplaza.&tag=Filosof%C3%ADa`;

export default function FilosofiaPage() {
  const jsonLd = {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'AboutPage',
        '@id': `${site}/filosofia#webpage`,
        name: title,
        url: `${site}/filosofia`,
        description,
        inLanguage: 'es',
        isPartOf: { '@id': `${site}/#website` },
        about: { '@id': `${site}/#organization` },
      },
      {
        '@type': 'Article',
        '@id': `${site}/filosofia#article`,
        headline: 'IA que potencia a las personas: nuestra filosofía',
        description,
        url: `${site}/filosofia`,
        inLanguage: 'es',
        publisher: { '@id': `${site}/#organization` },
        citation: REFERENCES.map((r) => ({
          '@type': 'CreativeWork',
          name: r.work,
          author: r.author,
          datePublished: r.year,
          url: r.href,
        })),
      },
    ],
  };

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/filosofia`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="article" />
        <meta property="og:url" content={`${site}/filosofia`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Nuestra Filosofía — Gano Digital" />
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
          aria-hidden="true"
        />
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 left-1/3 w-[55vw] h-[55vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.10) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.07, 1], opacity: [0.6, 1, 0.6] }}
          transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-4xl mx-auto px-6 md:px-10 pt-40 pb-20 md:pt-48 md:pb-28">
          <motion.span
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body text-foreground/90 border border-border rounded-full px-3 py-1.5 mb-8"
          >
            <Users size={12} style={{ color: CHARTREUSE }} />
            Nuestra filosofía
          </motion.span>
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <h1
              className="font-heading font-bold text-foreground mb-8"
              style={{ fontSize: 'clamp(40px, 7vw, 84px)', letterSpacing: '-0.03em', lineHeight: 1.03 }}
            >
              No automatizamos personas.{' '}
              <span style={{ color: CHARTREUSE }}>Las potenciamos.</span>
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            {PHILOSOPHY_INTRO}
          </motion.p>
        </div>
      </section>

      {/* ── TENETOS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-5xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-16">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Lo que sostenemos
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Seis principios que guían cada sistema que construimos.
            </h2>
          </div>

          <div className="flex flex-col">
            {TENETS.map((t, i) => (
              <motion.article
                key={t.number}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: (i % 2) * 0.08, ease: 'easeOut' as const }}
                className="grid grid-cols-1 md:grid-cols-[auto_1fr] gap-5 md:gap-8 py-9 border-t"
                style={{ borderColor: 'hsl(var(--border))' }}
              >
                <span
                  className="font-heading font-bold text-5xl md:text-6xl leading-none select-none"
                  style={{ color: 'rgba(217,126,58,0.35)' }}
                  aria-hidden="true"
                >
                  {t.number}
                </span>
                <div>
                  <h3 className="font-heading font-bold text-foreground text-2xl md:text-[28px] mb-3" style={{ letterSpacing: '-0.02em', lineHeight: 1.15 }}>
                    {t.title}
                  </h3>
                  <p className="font-body text-base md:text-lg text-muted-foreground max-w-2xl" style={{ lineHeight: 1.7 }}>
                    {t.body}
                  </p>
                </div>
              </motion.article>
            ))}
          </div>
        </div>
      </section>

      {/* ── MARCO TEÓRICO (PILARES) ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10">
          <div className="mb-16">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              El marco teórico
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Por qué pensamos así — y quién lo respalda.
            </h2>
            <p className="font-body text-lg text-muted-foreground max-w-2xl" style={{ lineHeight: 1.7 }}>
              Nuestra postura no nace de una corazonada. Se apoya en economistas, en marcos éticos
              internacionales y en la propia investigación de quienes construyen estos sistemas.
            </p>
          </div>

          <div className="flex flex-col gap-20">
            {PILLARS.map((p) => (
              <motion.article
                key={p.id}
                initial={{ opacity: 0, y: 24 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.6, ease: 'easeOut' as const }}
              >
                <p className="text-xs tracking-[0.2em] uppercase font-body mb-3" style={{ color: CHARTREUSE }}>
                  {p.kicker}
                </p>
                <h3 className="font-heading font-bold text-foreground text-2xl md:text-4xl mb-6" style={{ letterSpacing: '-0.02em', lineHeight: 1.1 }}>
                  {p.title}
                </h3>
                <div className="flex flex-col gap-5">
                  {p.body.map((para, idx) => (
                    <p key={idx} className="font-body text-base md:text-lg text-foreground/85" style={{ lineHeight: 1.8 }}>
                      {para}
                    </p>
                  ))}
                </div>

                {p.stat && (
                  <div
                    className="mt-8 p-7 rounded-xl flex flex-col sm:flex-row sm:items-center gap-5"
                    style={{ backgroundColor: 'hsl(var(--card))', borderLeft: `3px solid ${CHARTREUSE}` }}
                  >
                    <span
                      className="font-heading font-bold leading-none shrink-0"
                      style={{ color: CHARTREUSE, fontSize: 'clamp(40px, 6vw, 64px)', letterSpacing: '-0.03em' }}
                    >
                      {p.stat.value}
                    </span>
                    <p className="font-body text-sm md:text-base text-muted-foreground" style={{ lineHeight: 1.6 }}>
                      {p.stat.label}
                    </p>
                  </div>
                )}
              </motion.article>
            ))}
          </div>
        </div>
      </section>

      {/* ── CITA ANCLA ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-3xl mx-auto px-6 md:px-10 text-center">
          <Quote size={36} style={{ color: CHARTREUSE }} className="mx-auto mb-6 opacity-80" aria-hidden="true" />
          <blockquote className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(24px, 3.5vw, 40px)', letterSpacing: '-0.02em', lineHeight: 1.25 }}>
            “La pregunta no es cuántos empleos elimina la IA, sino qué capacidades humanas amplifica.”
          </blockquote>
          <p className="font-body text-sm text-muted-foreground mt-6">
            Nuestra lectura de la mejor evidencia disponible.
          </p>
        </div>
      </section>

      {/* ── REFERENCIAS ── */}
      <References references={REFERENCES} />

      {/* ── CTA ── */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10 text-center">
          <h2 className="font-heading font-bold text-foreground mb-6" style={{ fontSize: 'clamp(28px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
            ¿Quieres IA que sume a tu equipo, no que lo reemplace?
          </h2>
          <p className="font-body text-lg text-muted-foreground mb-10 max-w-2xl mx-auto" style={{ lineHeight: 1.7 }}>
            Así es exactamente como diseñamos cada solución. Conozcamos tu operación y encontremos
            dónde la IA potencia a tu gente — con honestidad sobre lo que suma y lo que no.
          </p>
          <div className="flex flex-wrap items-center justify-center gap-4">
            <Link
              to="/soluciones-ia"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Ver cómo lo aplicamos <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
            <Link
              to="/aprende"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              Ir a la sala de lectura
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
