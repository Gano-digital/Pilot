import { useState } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, AnimatePresence } from 'motion/react';
import {
  ShieldCheck, Headset, Clock, BadgeDollarSign, ChevronDown,
  LayoutGrid, ExternalLink, LifeBuoy, type LucideIcon,
} from 'lucide-react';
import CatalogGrid from '@/components/catalog/CatalogGrid';
import DomainSearch from '@/components/catalog/DomainSearch';
import { SERVICES, ESCAPARATE_URL, HELP_URL } from '@/data/catalog';
import { LocaleSeo } from '@/components/LocaleSeo';

const SITE = 'https://gano.digital';

/**
 * Catálogo en inglés. Reutiliza la maquinaria interactiva intacta:
 *  - <CatalogGrid locale="en">  → mismas 35 fichas, lógica buyKind y precios sin tocar
 *  - <DomainSearch>             → mismo buscador conectado al carrito del reseller
 * Solo se traduce el copy de PÁGINA (encabezados, trust strip, FAQ). Los datos
 * del catálogo (nombres de producto, precios, PFIDs) son universales y quedan
 * idénticos al español. Se omiten PlanFinder/HowItWorks/CapabilityStatement
 * (copy narrativo en español) y se sustituyen por una FAQ traducida.
 */

const TRUST_POINTS_EN: { icon: LucideIcon; title: string; body: string }[] = [
  { icon: ShieldCheck, title: 'Curated engineering', body: 'I only put on the catalog what I would deploy on my own projects.' },
  { icon: Clock, title: 'Active in minutes', body: 'The moment you confirm, the clock works in your favor.' },
  { icon: BadgeDollarSign, title: 'Clear pricing', body: 'No surprises at renewal. What you see is what you pay.' },
  { icon: Headset, title: 'Real, human support', body: 'You talk to an engineer who knows your project — not a call center.' },
];

const FAQS_EN: { q: string; a: string }[] = [
  { q: 'Do you migrate my current site for me?', a: 'Yes. I move your site with zero downtime and verify everything works before pointing your domain. You don\u2019t lose a single customer in the process.' },
  { q: 'What payment methods can I use?', a: 'You pay securely through GoDaddy\u2019s checkout — credit/debit cards and the available local methods. Renewals are transparent, with no hidden surprises.' },
  { q: 'Can I upgrade my plan later?', a: 'Absolutely. You can scale from shared hosting all the way to a dedicated VPS as your project grows. I help you choose the right moment so you neither overpay nor fall short.' },
  { q: 'Where is support based and what language?', a: 'Support is based in Colombia and runs in Spanish and English. You speak directly with an engineer who knows your setup.' },
];

function SectionHead({ kicker, title, desc }: { kicker: string; title: string; desc?: string }) {
  return (
    <div className="mb-10">
      <motion.p
        initial={{ opacity: 0, y: 10 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.5 }}
        className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-3"
      >
        {kicker}
      </motion.p>
      <motion.h2
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.5, delay: 0.1 }}
        className="font-heading font-bold text-foreground"
        style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
      >
        {title}
      </motion.h2>
      {desc && (
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.15 }}
          className="font-body text-muted-foreground mt-4 max-w-2xl"
          style={{ lineHeight: 1.7 }}
        >
          {desc}
        </motion.p>
      )}
    </div>
  );
}

export default function CatalogoPageEn() {
  const canonicalUrl = `${SITE}/en/catalogo`;
  const ogImage = `${SITE}/api/og?title=Service+Catalog&description=Hosting%2C+domains%2C+email+and+security+managed+by+Gano+Digital.&tag=Catalog`;

  const jsonLd = {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'CollectionPage',
        '@id': `${canonicalUrl}#webpage`,
        name: 'Service Catalog — Gano Digital',
        url: canonicalUrl,
        inLanguage: 'en',
        isPartOf: { '@id': `${SITE}/#website` },
        about: { '@id': `${SITE}/#organization` },
        hasPart: SERVICES.map((s) => ({
          '@type': 'Product',
          name: s.name,
          description: s.shortDescription,
          category: s.category,
          offers: {
            '@type': 'Offer',
            price: s.priceFrom,
            priceCurrency: s.currency,
            url: s.buyUrl.startsWith('http') ? s.buyUrl : canonicalUrl,
            availability: 'https://schema.org/InStock',
          },
        })),
      },
      {
        '@type': 'FAQPage',
        '@id': `${canonicalUrl}#faq`,
        inLanguage: 'en',
        isPartOf: { '@id': `${SITE}/#website` },
        mainEntity: FAQS_EN.map((f) => ({
          '@type': 'Question',
          name: f.q,
          acceptedAnswer: { '@type': 'Answer', text: f.a },
        })),
      },
    ],
  };

  return (
    <>
      <Helmet>
        <title>Service Catalog — Gano Digital</title>
        <meta name="description" content="WordPress hosting, domains, business email, SSL and managed security. World-class infrastructure with real support. Compare plans and get started in seconds." />
        <link rel="canonical" href={canonicalUrl} />
        <meta property="og:title" content="Service Catalog — Gano Digital" />
        <meta property="og:description" content="Hosting, domains, email and security managed with curated engineering." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={canonicalUrl} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Service Catalog — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Service Catalog — Gano Digital" />
        <meta name="twitter:description" content="Hosting, domains, email and security managed with curated engineering." />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify(jsonLd)}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/catalogo" locale="en" />

      {/* Header */}
      <section className="pt-36 pb-16 md:pt-44 md:pb-20 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <div className="w-12 h-px mb-8 bg-primary" />
            <h1
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(48px, 8vw, 92px)', letterSpacing: '-0.03em', lineHeight: 1.02 }}
            >
              Service<br />Catalog
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            WordPress hosting, domains, business email and security — on world-class
            infrastructure, with curated engineering. I explain every term so you choose
            with judgment, not in the dark.
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.22 }}
            className="mt-7 flex flex-wrap items-center gap-x-3 gap-y-1.5"
          >
            <span className="font-body text-sm text-muted-foreground">
              Already know what you need?
            </span>
            <a
              href={ESCAPARATE_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center gap-1.5 font-body text-sm font-medium text-primary hover:opacity-80 transition-opacity duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-xl"
            >
              <LayoutGrid size={15} strokeWidth={2} aria-hidden="true" />
              Go to the classic checkout catalog
              <ExternalLink size={13} className="transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true" />
            </a>
          </motion.div>

          {/* Trust strip */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12"
          >
            {TRUST_POINTS_EN.map((tp) => {
              const Icon = tp.icon;
              return (
                <div key={tp.title} className="flex items-start gap-3 border-l border-border pl-4">
                  <Icon size={20} className="text-primary shrink-0 mt-0.5" strokeWidth={1.75} aria-hidden="true" />
                  <div>
                    <p className="font-heading text-sm font-bold text-foreground">{tp.title}</p>
                    <p className="font-body text-xs text-muted-foreground mt-0.5">{tp.body}</p>
                  </div>
                </div>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* Domain search */}
      <section id="dominios" className="pb-16 md:pb-20 bg-background scroll-mt-24 pt-8">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="Your name on the internet"
            title="Is your domain available?"
            desc="The first step of any serious digital presence. Check availability and register it instantly."
          />
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, ease: 'easeOut' }}
          >
            <DomainSearch />
          </motion.div>
        </div>
      </section>

      {/* Full catalog grid */}
      <section className="pb-24 md:pb-32 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="The full catalog"
            title="Explore every service"
            desc="Filter by category. Each card explains what it solves — tap “What does it solve?” for the detail."
          />
          <CatalogGrid locale="en" />
        </div>
      </section>

      {/* Bridge to full GoDaddy storefront */}
      <section className="pb-24 md:pb-32 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.55, ease: 'easeOut' as const }}
            className="rounded-md border border-border bg-card p-8 md:p-10 flex flex-col md:flex-row md:items-center gap-6 md:gap-10"
          >
            <div className="flex-1">
              <p className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-3">
                Looking for something more specific?
              </p>
              <h2
                className="font-heading font-bold text-card-foreground mb-3"
                style={{ fontSize: 'clamp(24px, 3vw, 38px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
              >
                Explore the full catalog
              </h2>
              <p className="font-body text-muted-foreground leading-relaxed max-w-2xl">
                Here I curate the services I recommend most. If you need something specific —
                advanced certificates, particular plans or extra tools — you can see them all
                in our full catalog, with the same backing and Gano Digital pricing.
              </p>
            </div>
            <a
              href={ESCAPARATE_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center justify-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03] shrink-0"
            >
              View full catalog
              <ExternalLink size={16} strokeWidth={2.25} className="transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true" />
            </a>
          </motion.div>

          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="mt-5 text-center font-body text-sm text-muted-foreground"
          >
            Already a customer and need help with a service?{' '}
            <a
              href={HELP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1 text-primary hover:opacity-80 transition-opacity duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-xl"
            >
              <LifeBuoy size={14} aria-hidden="true" />
              Help center
            </a>
          </motion.p>
        </div>
      </section>

      {/* FAQ */}
      <section className="pb-24 md:pb-32 bg-background">
        <div className="max-w-3xl mx-auto px-6 md:px-10">
          <SectionHead kicker="Before you buy" title="Frequently asked questions" />
          <CatalogFaqEn />
        </div>
      </section>
    </>
  );
}

function CatalogFaqEn() {
  const [open, setOpen] = useState<number | null>(0);
  return (
    <div className="flex flex-col">
      {FAQS_EN.map((item, i) => {
        const isOpen = open === i;
        return (
          <div key={item.q} className="border-b border-border">
            <button
              type="button"
              onClick={() => setOpen(isOpen ? null : i)}
              aria-expanded={isOpen}
              className="flex items-center justify-between gap-4 w-full py-5 text-left group focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-xl"
            >
              <span className="font-heading text-lg font-bold text-foreground group-hover:text-primary transition-colors duration-200">
                {item.q}
              </span>
              <motion.span animate={{ rotate: isOpen ? 180 : 0 }} transition={{ duration: 0.2 }} className="shrink-0">
                <ChevronDown size={20} className="text-muted-foreground" aria-hidden="true" />
              </motion.span>
            </button>
            <AnimatePresence initial={false}>
              {isOpen && (
                <motion.div
                  initial={{ height: 0, opacity: 0 }}
                  animate={{ height: 'auto', opacity: 1 }}
                  exit={{ height: 0, opacity: 0 }}
                  transition={{ duration: 0.3, ease: 'easeOut' as const }}
                  className="overflow-hidden"
                >
                  <p className="font-body text-muted-foreground leading-relaxed pb-6 max-w-2xl">{item.a}</p>
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        );
      })}
    </div>
  );
}
