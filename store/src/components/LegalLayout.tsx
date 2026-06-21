import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { ArrowLeft, type LucideIcon } from 'lucide-react';
import type { ReactNode } from 'react';

const CHARTREUSE = '#D97E3A';
const SITE = 'https://gano.digital';

interface LegalLayoutProps {
  /** Path segment without leading slash, e.g. "terminos". */
  slug: string;
  /** Browser/SEO title (full, including brand). */
  title: string;
  /** Meta description (<=160 chars). */
  description: string;
  /** OG/Twitter image query tag. */
  ogTag: string;
  /** Eyebrow label above the H1. */
  eyebrow: string;
  /** H1 heading. */
  heading: string;
  /**
   * Tag used to render the visible heading. Defaults to "h1". Pass "p" when the
   * page already renders its own semantic <h1> (e.g. an sr-only one) so the page
   * has exactly one H1 — the visible heading then stays styled but is not a second H1.
   */
  headingAs?: 'h1' | 'p';
  /** Short intro paragraph under the heading. */
  intro: string;
  /** ISO date string shown as "última actualización". */
  lastUpdated: string;
  icon: LucideIcon;
  children: ReactNode;
}

export default function LegalLayout({
  slug,
  title,
  description,
  ogTag,
  eyebrow,
  heading,
  headingAs = 'h1',
  intro,
  lastUpdated,
  icon: Icon,
  children,
}: LegalLayoutProps) {
  const canonicalUrl = `${SITE}/${slug}`;
  const HeadingTag = headingAs;
  const ogImage = `${SITE}/api/og?title=${encodeURIComponent(heading)}&description=${encodeURIComponent(
    'Documentación legal de Gano Digital.',
  )}&tag=${encodeURIComponent(ogTag)}`;

  const formattedDate = new Intl.DateTimeFormat('es-CO', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(new Date(lastUpdated));

  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'WebPage',
    '@id': `${canonicalUrl}#webpage`,
    url: canonicalUrl,
    name: title,
    description,
    isPartOf: { '@id': `${SITE}/#website` },
    about: { '@id': `${SITE}/#organization` },
    dateModified: lastUpdated,
    inLanguage: 'es-CO',
  };

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={canonicalUrl} />
        <meta property="og:title" content={heading} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={canonicalUrl} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content={heading} />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={heading} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify(jsonLd)}</script>
      </Helmet>

      {/* HERO */}
      <section className="pt-36 pb-12 md:pt-44 md:pb-16" style={{ backgroundColor: '#0A0A0A' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, ease: 'easeOut' as const }}
          >
            <Link
              to="/"
              className="inline-flex items-center gap-2 text-xs font-body text-muted-foreground hover:text-foreground transition-colors mb-8"
            >
              <ArrowLeft size={14} aria-hidden="true" /> Volver al inicio
            </Link>
            <div className="flex items-center gap-3 mb-5">
              <span
                className="inline-flex items-center justify-center h-11 w-11 rounded-sm shrink-0"
                style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}
              >
                <Icon size={20} aria-hidden="true" />
              </span>
              <p className="text-xs tracking-[0.25em] uppercase font-body text-muted-foreground">
                {eyebrow}
              </p>
            </div>
            <HeadingTag
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(36px, 5.5vw, 64px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
            >
              {heading}
            </HeadingTag>
            <p className="text-base md:text-lg font-body text-muted-foreground leading-relaxed max-w-2xl">
              {intro}
            </p>
            <p className="mt-6 text-xs font-body text-muted-foreground/70">
              Última actualización: {formattedDate}
            </p>
          </motion.div>
        </div>
      </section>

      {/* CONTENT */}
      <section className="pb-24" style={{ backgroundColor: '#0A0A0A' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10">
          <div className="legal-content flex flex-col gap-10">{children}</div>

          {/* Cierre legal */}
          <div className="mt-16 pt-8 border-t" style={{ borderColor: '#2A2A2A' }}>
            <p className="text-sm font-body text-muted-foreground leading-relaxed">
              ¿Dudas sobre estas condiciones? Escríbenos por{' '}
              <a
                href="https://wa.me/573135646123?text=Tengo+una+consulta+sobre+los+t%C3%A9rminos+y+pol%C3%ADticas"
                target="_blank"
                rel="noopener noreferrer"
                className="underline hover:text-foreground transition-colors"
                style={{ color: CHARTREUSE }}
              >
                WhatsApp
              </a>{' '}
              o al correo{' '}
              <a
                href="mailto:pymes@gano.digital"
                className="underline hover:text-foreground transition-colors"
                style={{ color: CHARTREUSE }}
              >
                pymes@gano.digital
              </a>
              .
            </p>
          </div>
        </div>
      </section>
    </>
  );
}

/** Section block: a numbered heading + body content. */
export function LegalSection({
  num,
  title,
  children,
}: {
  num: string;
  title: string;
  children: ReactNode;
}) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: '-60px' }}
      transition={{ duration: 0.45, ease: 'easeOut' as const }}
      className="flex flex-col gap-4"
    >
      <div className="flex items-baseline gap-3">
        <span
          className="font-heading text-sm font-bold tabular-nums shrink-0"
          style={{ color: CHARTREUSE }}
        >
          {num}
        </span>
        <h2
          className="font-heading font-bold text-foreground"
          style={{ fontSize: 'clamp(20px, 2.6vw, 28px)', letterSpacing: '-0.02em' }}
        >
          {title}
        </h2>
      </div>
      <div className="flex flex-col gap-4 text-sm md:text-base font-body text-foreground/75 leading-relaxed pl-0 md:pl-8">
        {children}
      </div>
    </motion.div>
  );
}

/** Bullet list for legal clauses. */
export function LegalList({ items }: { items: ReactNode[] }) {
  return (
    <ul className="flex flex-col gap-2.5">
      {items.map((item, i) => (
        <li key={i} className="flex gap-3">
          <span
            className="mt-2 h-1.5 w-1.5 rounded-full shrink-0"
            style={{ backgroundColor: CHARTREUSE }}
            aria-hidden="true"
          />
          <span>{item}</span>
        </li>
      ))}
    </ul>
  );
}

/** Highlighted callout box for important notices. */
export function LegalCallout({ children }: { children: ReactNode }) {
  return (
    <div
      className="rounded-sm border p-5"
      style={{ borderColor: 'rgba(217,126,58,0.35)', backgroundColor: 'rgba(217,126,58,0.06)' }}
    >
      <div className="text-sm font-body text-foreground/85 leading-relaxed flex flex-col gap-2">
        {children}
      </div>
    </div>
  );
}
