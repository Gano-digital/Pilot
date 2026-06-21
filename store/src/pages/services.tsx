import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { Check } from 'lucide-react';
import { formatPrice, cartUrl, PFID } from '@/data/catalog';
import TechStack from '@/components/TechStack';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';

const services = [
  {
    num: '01',
    title: 'Hosting WordPress Administrado',
    tagline: 'Tu sitio, sin que tengas que tocar un servidor.',
    desc: 'WordPress preinstalado, SSL automático y actualizaciones gestionadas. Desde el Starter para arrancar hasta el Ultimate con ingeniero asignado — siempre sobre NVMe Gen4 y CDN global.',
    deliverables: ['WordPress preinstalado + staging', 'Almacenamiento NVMe Gen4', 'SSL gratuito auto-renovado', 'CDN global · 200+ POPs', 'Backups automáticos · soporte 24/7'],
    dark: true,
  },
  {
    num: '02',
    title: 'Dominios y Email Profesional',
    tagline: 'Tu nombre y tu correo, a la altura de tu marca.',
    desc: 'Registramos tu dominio (.co, .com, .digital y más) con privacidad WHOIS y DNS Anycast, y montamos correo profesional con tu dominio — desde un buzón hasta Microsoft 365 con Teams.',
    deliverables: ['Dominios .co · .com · .digital · .io', 'Privacidad WHOIS + DNS Anycast', 'Correo tu@tunegocio.com', 'DKIM · SPF · DMARC configurados', 'Microsoft 365 Business disponible'],
    dark: false,
  },
  {
    num: '03',
    title: 'Seguridad Web',
    tagline: 'Dormir tranquilo no debería ser un lujo.',
    desc: 'Certificados SSL/EV, firewall de aplicación capa 7 con mitigación de bots, backups diarios con restauración en un clic y endurecimiento de infraestructura validado por red team.',
    deliverables: ['SSL DV y Wildcard EV', 'WAF Capa 7 · OWASP Top 10', 'Mitigación DDoS hasta 10 Gbps', 'Backup diario · restauración 1 clic', 'Endurecimiento validado (red team)'],
    dark: true,
  },
  {
    num: '04',
    title: 'Servicios Gano · Infraestructura a Medida',
    tagline: 'Cuando el catálogo se queda corto, lo construimos.',
    desc: 'VPS gestionado con ingeniero asignado, diagnóstico técnico de tu stack en 72 horas y desarrollo WordPress a medida de wireframe a producción. Ingeniería curada, no autoservicio frío.',
    deliverables: ['VPS Pro gestionado · root SSH', 'Ingeniero asignado a tu proyecto', 'Diagnóstico técnico en 72h', 'Desarrollo WordPress a medida', '3 meses de acompañamiento post-lanzamiento'],
    dark: false,
  },
];

const pricingTiers = [
  {
    name: 'WordPress Starter',
    price: formatPrice(15000, 'COP'),
    period: '/mes',
    desc: 'Para arrancar tu primera web sin complicaciones técnicas.',
    features: ['1 sitio WordPress', '30 GB SSD NVMe', 'Hasta 25.000 visitas/mes', 'SSL gratuito auto-renovado', 'Staging incluido · soporte 24/7'],
    featured: false,
    pfid: PFID.wordpressManaged,
  },
  {
    name: 'Pro Managed',
    price: formatPrice(39000, 'COP'),
    period: '/mes',
    desc: 'Para sitios con tráfico real que no pueden permitirse caídas.',
    features: ['3 sitios WP · 75 GB NVMe', 'Hasta 150.000 visitas/mes', 'CDN Global · Redis dedicado', 'Backups cada 12h · 30 días', '5 cuentas de email'],
    featured: true,
    pfid: PFID.wordpressManaged,
  },
  {
    name: 'Business NVMe',
    price: formatPrice(89000, 'COP'),
    period: '/mes',
    desc: 'Velocidad crítica para WooCommerce de alta autoridad.',
    features: ['10 sitios WP · 150 GB NVMe Gen4', 'Hasta 500.000 visitas/mes', 'WAF Capa 7 activo', 'CDN Global Premium', 'SLA 99.95%'],
    featured: false,
    pfid: PFID.wordpressManaged,
  },
];

export default function ServicesPage() {
  return (
    <>
      <Helmet>
        <title>Servicios — Gano Digital</title>
        <meta name="description" content="Hosting WordPress administrado, dominios, correo profesional, seguridad web e infraestructura a medida. Ingeniería curada y facturación en COP, desde Colombia." />
        <link rel="canonical" href="https://gano.digital/services" />
        <meta property="og:title" content="Servicios — Gano Digital" />
        <meta property="og:description" content="Hosting administrado, dominios, email profesional, seguridad e infraestructura a medida. Ingeniería curada, facturación en COP." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/services" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Servicios&description=Hosting+administrado%2C+dominios%2C+email%2C+seguridad+e+infraestructura+a+medida.&tag=Servicios" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Servicios — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Servicios — Gano Digital" />
        <meta name="twitter:description" content="Hosting administrado, dominios, email profesional, seguridad e infraestructura a medida." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Servicios&description=Hosting+administrado%2C+dominios%2C+email%2C+seguridad+e+infraestructura+a+medida.&tag=Servicios" />
        <script type="application/ld+json">
          {JSON.stringify({
            '@context': 'https://schema.org',
            '@graph': [
              {
                '@type': 'CollectionPage',
                '@id': 'https://gano.digital/services#webpage',
                url: 'https://gano.digital/services',
                name: 'Servicios — Gano Digital',
                description:
                  'Hosting WordPress administrado, dominios, correo profesional, seguridad web e infraestructura a medida.',
                isPartOf: { '@id': 'https://gano.digital/#website' },
                about: { '@id': 'https://gano.digital/#organization' },
              },
              {
                '@type': 'Service',
                name: 'Hosting WordPress Administrado',
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
                name: 'Seguridad Web',
                serviceType: 'Web Security (SSL, WAF, Backups)',
                provider: { '@id': 'https://gano.digital/#organization' },
                areaServed: { '@type': 'Country', name: 'Colombia' },
              },
              {
                '@type': 'Service',
                name: 'Dominios y Email Profesional',
                serviceType: 'Domain Registration & Business Email',
                provider: { '@id': 'https://gano.digital/#organization' },
                areaServed: { '@type': 'Country', name: 'Colombia' },
              },
            ],
          })}
        </script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/services" locale="es" />

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
              Servicios
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-xl"
            style={{ lineHeight: 1.7 }}
          >
            Infraestructura digital curada por expertos. Hosting, dominios, seguridad y desarrollo, con facturación en pesos.
          </motion.p>
        </div>
      </section>

      {/* Service Bands */}
      {services.map((svc, i) => (
        <section
          key={svc.num}
          className="py-20 md:py-28"
          style={{ backgroundColor: svc.dark ? 'hsl(var(--background))' : 'hsl(var(--secondary))' }}
        >
          <div className="max-w-7xl mx-auto px-6 md:px-10">
            {/* Accent line */}
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
                  Incluye
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
            Inversión
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14"
            style={{ fontSize: 'clamp(32px, 4vw, 52px)', letterSpacing: '-0.03em' }}
          >
            Planes de hosting
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
                    Más Popular
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
                  Contratar plan
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
            Toda activación empieza con una asesoría gratuita para confirmar el plan exacto que necesitas.{' '}
            <Link to="/catalogo" className="underline hover:text-foreground transition-colors" style={{ color: CHARTREUSE }}>
              Ver catálogo completo →
            </Link>
            {' · '}
            <Link to="/disenos" className="underline hover:text-foreground transition-colors" style={{ color: CHARTREUSE }}>
              Catálogo de diseño web →
            </Link>
          </motion.p>
        </div>
      </section>

      <TechStack
        kicker="Con qué construimos"
        title="Tecnología de frontera, criterio de ingeniería."
        subtitle="No improvisamos con cada servicio. Trabajamos con un stack probado de IA, frameworks de desarrollo e infraestructura — las mismas herramientas con las que montamos y mantenemos proyectos en producción."
        background="hsl(var(--background))"
      />
    </>
  );
}
