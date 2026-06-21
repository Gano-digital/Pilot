import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { Check } from 'lucide-react';
import { formatPrice, cartUrl, PFID } from '@/data/catalog';
import TechStack from '@/components/TechStack';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';

/**
 * Servicios en inglés. Traducción profesional (no literal). Mantiene intactos:
 *  - formatPrice / cartUrl / PFID (precios y lógica de carrito del reseller)
 *  - estructura visual y tokens de tema idénticos al español
 * Las URLs internas apuntan a sus equivalentes /en.
 */

const services = [
  {
    num: '01',
    title: 'Managed WordPress Hosting',
    tagline: "Your site, without ever touching a server.",
    desc: 'WordPress pre-installed, automatic SSL and managed updates. From the Starter to get going to the Ultimate with a dedicated engineer — always on Gen4 NVMe and a global CDN.',
    deliverables: ['WordPress pre-installed + staging', 'Gen4 NVMe storage', 'Free auto-renewing SSL', 'Global CDN · 200+ POPs', 'Automatic backups · 24/7 support'],
    dark: true,
  },
  {
    num: '02',
    title: 'Domains & Business Email',
    tagline: 'Your name and your inbox, worthy of your brand.',
    desc: 'I register your domain (.co, .com, .digital and more) with WHOIS privacy and Anycast DNS, and set up professional email on your own domain — from a single mailbox to Microsoft 365 with Teams.',
    deliverables: ['.co · .com · .digital · .io domains', 'WHOIS privacy + Anycast DNS', 'you@yourbusiness.com email', 'DKIM · SPF · DMARC configured', 'Microsoft 365 Business available'],
    dark: false,
  },
  {
    num: '03',
    title: 'Web Security',
    tagline: 'Sleeping soundly shouldn\u2019t be a luxury.',
    desc: 'SSL/EV certificates, a layer-7 application firewall with bot mitigation, daily backups with one-click restore, and red-team-validated infrastructure hardening.',
    deliverables: ['DV SSL and EV Wildcard', 'Layer 7 WAF · OWASP Top 10', 'DDoS mitigation up to 10 Gbps', 'Daily backup · 1-click restore', 'Red-team-validated hardening'],
    dark: true,
  },
  {
    num: '04',
    title: 'Gano Services · Custom Infrastructure',
    tagline: 'When the catalog falls short, I build it.',
    desc: 'Managed VPS with a dedicated engineer, a 72-hour technical diagnosis of your stack, and custom WordPress development from wireframe to production. Curated engineering, not cold self-service.',
    deliverables: ['Managed VPS Pro · root SSH', 'Engineer assigned to your project', 'Technical diagnosis in 72h', 'Custom WordPress development', '3 months of post-launch support'],
    dark: false,
  },
];

const pricingTiers = [
  {
    name: 'WordPress Starter',
    price: formatPrice(15000, 'COP'),
    period: '/mo',
    desc: 'To launch your first website without the technical headaches.',
    features: ['1 WordPress site', '30 GB NVMe SSD', 'Up to 25,000 visits/mo', 'Free auto-renewing SSL', 'Staging included · 24/7 support'],
    featured: false,
    pfid: PFID.wordpressManaged,
  },
  {
    name: 'Pro Managed',
    price: formatPrice(39000, 'COP'),
    period: '/mo',
    desc: "For sites with real traffic that can't afford downtime.",
    features: ['3 WP sites · 75 GB NVMe', 'Up to 150,000 visits/mo', 'Global CDN · dedicated Redis', 'Backups every 12h · 30 days', '5 email accounts'],
    featured: true,
    pfid: PFID.wordpressManaged,
  },
  {
    name: 'Business NVMe',
    price: formatPrice(89000, 'COP'),
    period: '/mo',
    desc: 'Critical speed for high-authority WooCommerce.',
    features: ['10 WP sites · 150 GB Gen4 NVMe', 'Up to 500,000 visits/mo', 'Active Layer 7 WAF', 'Premium Global CDN', '99.95% SLA'],
    featured: false,
    pfid: PFID.wordpressManaged,
  },
];

export default function ServicesPageEn() {
  return (
    <>
      <Helmet>
        <title>Services — Gano Digital</title>
        <meta name="description" content="Managed WordPress hosting, domains, business email, web security and custom infrastructure. Curated engineering, billed in COP, from Colombia." />
        <link rel="canonical" href="https://gano.digital/en/services" />
        <meta property="og:title" content="Services — Gano Digital" />
        <meta property="og:description" content="Managed hosting, domains, business email, security and custom infrastructure. Curated engineering, billed in COP." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/en/services" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Services&description=Managed+hosting%2C+domains%2C+email%2C+security+and+custom+infrastructure.&tag=Services" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Services — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Services — Gano Digital" />
        <meta name="twitter:description" content="Managed hosting, domains, business email, security and custom infrastructure." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Services&description=Managed+hosting%2C+domains%2C+email%2C+security+and+custom+infrastructure.&tag=Services" />
        <script type="application/ld+json">
          {JSON.stringify({
            '@context': 'https://schema.org',
            '@graph': [
              {
                '@type': 'CollectionPage',
                '@id': 'https://gano.digital/en/services#webpage',
                url: 'https://gano.digital/en/services',
                name: 'Services — Gano Digital',
                inLanguage: 'en',
                description:
                  'Managed WordPress hosting, domains, business email, web security and custom infrastructure.',
                isPartOf: { '@id': 'https://gano.digital/#website' },
                about: { '@id': 'https://gano.digital/#organization' },
              },
              {
                '@type': 'Service',
                name: 'Managed WordPress Hosting',
                serviceType: 'Managed WordPress Hosting',
                provider: { '@id': 'https://gano.digital/#organization' },
                areaServed: { '@type': 'Country', name: 'Colombia' },
                offers: {
                  '@type': 'AggregateOffer',
                  priceCurrency: 'COP',
                  lowPrice: 15000,
                  highPrice: 148000,
                  offerCount: 4,
                },
              },
              {
                '@type': 'Service',
                name: 'Web Security',
                serviceType: 'Web Security (SSL, WAF, Backups)',
                provider: { '@id': 'https://gano.digital/#organization' },
                areaServed: { '@type': 'Country', name: 'Colombia' },
              },
              {
                '@type': 'Service',
                name: 'Domains & Business Email',
                serviceType: 'Domain Registration & Business Email',
                provider: { '@id': 'https://gano.digital/#organization' },
                areaServed: { '@type': 'Country', name: 'Colombia' },
              },
            ],
          })}
        </script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/services" locale="en" />

      {/* Page Header */}
      <section className="pt-36 pb-16 md:pt-44 md:pb-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <h1
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(52px, 8vw, 96px)', letterSpacing: '-0.03em' }}
            >
              Services
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-xl"
            style={{ lineHeight: 1.7 }}
          >
            Digital infrastructure curated by experts. Hosting, domains, security and development — billed in pesos.
          </motion.p>
        </div>
      </section>

      {/* Service Bands */}
      {services.map((svc) => (
        <section
          key={svc.num}
          className="py-20 md:py-28"
          style={{ backgroundColor: svc.dark ? 'hsl(var(--background))' : 'hsl(var(--secondary))' }}
        >
          <div className="max-w-7xl mx-auto px-6 md:px-10">
            <div className="w-12 h-px mb-10" style={{ backgroundColor: CHARTREUSE }} />

            <div className="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 items-start">
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.6, ease: 'easeOut' }}
              >
                <p
                  className="font-body text-xs tracking-widest uppercase mb-3"
                  style={{ color: 'hsl(var(--muted-foreground))' }}
                >
                  {svc.num}
                </p>
                <h2
                  className="font-heading font-bold mb-4"
                  style={{
                    fontSize: 'clamp(36px, 5vw, 64px)',
                    letterSpacing: '-0.03em',
                    color: svc.dark ? 'hsl(var(--foreground))' : 'hsl(var(--secondary-foreground))',
                  }}
                >
                  {svc.title}
                </h2>
                <p
                  className="font-body italic text-lg mb-5"
                  style={{ color: 'hsl(var(--muted-foreground))' }}
                >
                  "{svc.tagline}"
                </p>
                <p
                  className="font-body text-base"
                  style={{ color: 'hsl(var(--muted-foreground))', lineHeight: 1.7 }}
                >
                  {svc.desc}
                </p>
              </motion.div>

              <motion.div
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.15 }}
              >
                <p
                  className="text-xs tracking-widest uppercase font-body mb-5"
                  style={{ color: 'hsl(var(--muted-foreground))' }}
                >
                  Includes
                </p>
                <ul className="flex flex-col gap-3">
                  {svc.deliverables.map((item) => (
                    <li key={item} className="flex items-center gap-3">
                      <span
                        className="w-1.5 h-1.5 rounded-full shrink-0"
                        style={{ backgroundColor: CHARTREUSE }}
                      />
                      <span
                        className="font-body text-base"
                        style={{ color: svc.dark ? 'hsl(var(--foreground))' : 'hsl(var(--secondary-foreground))' }}
                      >
                        {item}
                      </span>
                    </li>
                  ))}
                </ul>
              </motion.div>
            </div>
          </div>
        </section>
      ))}

      {/* Pricing Tiers */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
          >
            Investment
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14"
            style={{ fontSize: 'clamp(32px, 4vw, 52px)', letterSpacing: '-0.03em' }}
          >
            Hosting plans
          </motion.h2>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
            {pricingTiers.map((tier, i) => (
              <motion.div
                key={tier.name}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.1 }}
                className="p-8 rounded-xl flex flex-col"
                style={{
                  backgroundColor: 'hsl(var(--card))',
                  border: tier.featured ? `2px solid ${CHARTREUSE}` : '1px solid hsl(var(--border))',
                }}
              >
                {tier.featured && (
                  <span
                    className="text-xs font-body font-medium tracking-widest uppercase mb-5 self-start px-3 py-1 rounded-full"
                    style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                  >
                    Most Popular
                  </span>
                )}
                <h3 className="font-heading font-bold text-foreground text-2xl mb-1" style={{ letterSpacing: '-0.02em' }}>
                  {tier.name}
                </h3>
                <p
                  className="font-heading font-bold mb-2"
                  style={{ fontSize: 'clamp(28px, 3vw, 40px)', color: CHARTREUSE, letterSpacing: '-0.02em' }}
                >
                  {tier.price}
                  <span className="font-body text-base text-muted-foreground ml-1">{tier.period}</span>
                </p>
                <p className="font-body text-sm text-muted-foreground mb-8" style={{ lineHeight: 1.6 }}>
                  {tier.desc}
                </p>
                <ul className="flex flex-col gap-3 mb-10 flex-1">
                  {tier.features.map((feat) => (
                    <li key={feat} className="flex items-start gap-3">
                      <Check size={14} className="mt-0.5 shrink-0" style={{ color: CHARTREUSE }} />
                      <span className="font-body text-sm text-foreground/70">{feat}</span>
                    </li>
                  ))}
                </ul>
                <a
                  href={cartUrl(tier.pfid)}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="group w-full text-center py-3 text-sm font-heading font-bold tracking-wide uppercase border transition-all duration-200 relative overflow-hidden block"
                  style={{
                    borderColor: tier.featured ? CHARTREUSE : 'hsl(var(--border))',
                    color: tier.featured ? '#0A0A0A' : 'hsl(var(--muted-foreground))',
                    backgroundColor: tier.featured ? CHARTREUSE : 'transparent',
                  }}
                >
                  Get this plan
                </a>
              </motion.div>
            ))}
          </div>

          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.4 }}
            className="text-sm text-muted-foreground font-body mt-8 text-center"
          >
            Every activation starts with a free consultation to confirm the exact plan you need.{' '}
            <Link to="/en/catalogo" className="underline hover:text-foreground transition-colors" style={{ color: CHARTREUSE }}>
              View full catalog →
            </Link>
          </motion.p>
        </div>
      </section>

      <TechStack
        kicker="What I build with"
        title="Frontier technology, engineering judgment."
        subtitle="I don't improvise with every service. I work with a proven stack of AI, development frameworks and infrastructure — the same tools I use to ship and maintain projects in production."
        background="hsl(var(--background))"
      />
    </>
  );
}
