import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, useTransform } from 'motion/react';
import { ArrowRight, ChevronDown, Server, Shield, Globe, Gauge, MessageCircle, Sparkles, Bot } from 'lucide-react';
import ServiceCard from '@/components/catalog/ServiceCard';
import { usePointerField } from '@/lib/motion-physics';
import { SERVICES } from '@/data/catalog';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=Hi+Gano+Digital,+I%27d+like+some+guidance';

const featuredCatalog = SERVICES.filter((s) =>
  ['business-nvme', 'ultimate', 'vps-alpha'].includes(s.id),
);

// Same punch as "No vendo servidores. Catapulto empresas." — not a literal
// translation. The Spanish plays servers vs. growth; English keeps that beat.
const heroWords = ['I', 'don\u2019t', 'sell', 'servers.', 'I', 'scale', 'businesses.'];

const totalServices = SERVICES.length;

const stats = [
  { value: '1:1', label: 'Direct, hands-on support — no call centers' },
  { value: '360°', label: 'Enterprise, SMB, backend and frontend' },
  { value: `${totalServices}`, label: 'Curated services, not a generic catalog' },
  { value: '99.95%', label: 'Uptime SLA' },
];

const pillars = [
  {
    icon: Gauge,
    title: 'Speed that converts',
    desc: 'NVMe drives, a global CDN and Redis caching. Every millisecond saved is ranking on Google and conversion at your checkout.',
  },
  {
    icon: Shield,
    title: 'Security without the drama',
    desc: 'Application firewall, SSL, anti-DDoS and automatic backups. You sleep soundly even when the internet doesn\u2019t.',
  },
  {
    icon: Globe,
    title: 'Domains and email',
    desc: 'Your name on the internet and professional email on your own domain. The difference between looking freelance and looking like a serious company.',
  },
  {
    icon: Server,
    title: 'All the way to your own server',
    desc: 'When WordPress falls short: a VPS with full root and an engineer who knows your project by name.',
  },
];

const featuredWork = [
  { title: 'Zero-downtime WooCommerce migration', category: 'Migration', image: '/airo-assets/images/portfolio/migracion-woocommerce', href: '/portfolio' },
  { title: 'Security hardening (red team)', category: 'Security', image: '/airo-assets/images/portfolio/blindaje-seguridad', href: '/portfolio' },
  { title: 'NVMe speed optimization', category: 'Infrastructure', image: '/airo-assets/images/portfolio/optimizacion-velocidad', href: '/portfolio' },
];

export default function HomePageEn() {
  const site = 'https://gano.digital';
  const title = 'Gano Digital — Hosting, Domains and Security with Curated Engineering';
  const description =
    'Sovereign digital infrastructure: high-performance WordPress hosting, domains, professional email and security. Clear plans and real support from Colombia.';
  const ogImage =
    'https://gano.digital/api/og?title=Your+infrastructure,+sovereign.&description=Hosting,+domains+and+security+with+curated+engineering.&tag=Gano+Digital';

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
        <link rel="canonical" href={`${site}/en`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/en`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Gano Digital — Sovereign digital infrastructure" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@graph': [
            { '@type': 'WebSite', '@id': `${site}/#website`, name: 'Gano Digital', url: `${site}/` },
            { '@type': 'Organization', '@id': `${site}/#organization`, name: 'Gano Digital', url: `${site}/`, areaServed: 'CO' },
            { '@type': 'WebPage', '@id': `${site}/en#webpage`, url: `${site}/en`, inLanguage: 'en', isPartOf: { '@id': `${site}/#website` }, about: { '@id': `${site}/#organization` }, datePublished: '2026-06-19', dateModified: '2026-06-19' },
          ],
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/" locale="en" />

      {/* ── HERO ── */}
      <section
        className="relative min-h-screen flex flex-col justify-center overflow-hidden"
        style={{ backgroundColor: 'hsl(var(--background))' }}
      >
        <motion.div
          className="absolute inset-0 pointer-events-none"
          style={{
            x: gridX,
            y: gridY,
            backgroundImage: `linear-gradient(hsl(var(--foreground) / 0.04) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--foreground) / 0.04) 1px, transparent 1px)`,
            backgroundSize: '60px 60px',
          }}
        />
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
              Your digital growth partner
            </span>
            <span className="hidden sm:inline text-xs tracking-[0.18em] uppercase font-body text-muted-foreground">
              Industry-grade judgment · Colombia &amp; LATAM
            </span>
          </motion.div>

          <h1 className="font-heading font-bold leading-none tracking-tight mb-8" style={{ fontSize: 'clamp(40px, 7.5vw, 96px)', letterSpacing: '-0.03em' }}>
            {heroWords.map((word, i) => (
              <motion.span
                key={i}
                initial={{ opacity: 0, y: 40 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: i * 0.08, ease: 'easeOut' }}
                className={`inline-block mr-[0.25em] ${i >= 4 ? 'text-primary' : 'text-foreground'}`}
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
            I listen to your business model before proposing anything. Then I personally
            walk you through installing, configuring and actually <span className="text-foreground">using</span> the
            tools that multiply your capacity — from infrastructure that doesn’t go down
            to artificial intelligence that empowers your team. My trade isn’t selling you
            services: it’s putting real industry judgment on your side.
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.55, ease: 'easeOut' }}
            className="flex flex-wrap items-center gap-4 mt-10"
          >
            <Link
              to="/en/catalogo"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              View plans <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <MessageCircle size={16} /> Let’s talk about your project
            </a>
          </motion.div>

          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.6, delay: 0.8 }}
            className="flex items-end justify-between mt-20 md:mt-28"
          >
            <div className="flex flex-col items-center gap-2">
              <span className="text-xs tracking-widest text-muted-foreground uppercase font-body">Explore</span>
              <motion.div animate={{ y: [0, 6, 0] }} transition={{ duration: 1.4, repeat: Infinity, ease: 'easeInOut' }}>
                <ChevronDown size={18} style={{ color: CHARTREUSE }} />
              </motion.div>
            </div>
            <div className="flex items-center gap-2">
              <span className="w-2 h-2 rounded-full animate-pulse" style={{ backgroundColor: CHARTREUSE }} />
              <span className="text-sm font-body text-foreground/70">Active support from Colombia</span>
            </div>
          </motion.div>
        </div>
      </section>

      {/* ── THE PROBLEM ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--card))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10 text-center">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] text-muted-foreground uppercase font-body mb-4"
          >
            The hidden cost
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(30px, 5vw, 60px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Cheap hosting is the most expensive thing you’ll ever buy.
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground max-w-2xl mx-auto"
            style={{ lineHeight: 1.7 }}
          >
            A site that goes down on Black Friday. An inbox flagged as spam. A breach
            nobody noticed for weeks. The bill for fragility never shows up on the invoice —
            it shows up in the customers you quietly lose. I build so that bill never arrives.
          </motion.p>
        </div>
      </section>

      {/* ── PILLARS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.p
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-xs tracking-[0.2em] text-muted-foreground uppercase font-body mb-4"
          >
            Why it matters
          </motion.p>
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="font-heading font-bold text-foreground mb-14 max-w-2xl"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Four fronts that decide whether your site grows or goes down.
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

      {/* ── FEATURED PLANS ── */}
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
                Most chosen
              </motion.p>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.1 }}
                className="font-heading font-bold text-foreground"
                style={{ fontSize: 'clamp(32px, 4.5vw, 60px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                Plans people actually buy
              </motion.h2>
            </div>
            <motion.div
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              <Link
                to="/en/catalogo"
                className="text-sm font-body flex items-center gap-2 transition-colors duration-200 hover:opacity-80 whitespace-nowrap"
                style={{ color: CHARTREUSE }}
              >
                See the full catalog <ArrowRight size={14} />
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

      {/* ── BRIDGE: APPLIED AI ── */}
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
                <Sparkles size={12} /> New at Gano Digital
              </motion.span>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.1, ease: 'easeOut' as const }}
                className="font-heading font-bold text-foreground mb-5"
                style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                What if your team could do twice as much, freed from the work that drains it?
              </motion.h2>
              <motion.p
                initial={{ opacity: 0, y: 16 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.55, delay: 0.2, ease: 'easeOut' as const }}
                className="font-body text-lg text-muted-foreground mb-8"
                style={{ lineHeight: 1.7 }}
              >
                I deploy artificial intelligence that handles the repetitive, automates
                processes and moves your marketing — to give hours back to your people, not
                to replace them. Always with humans in command. Infrastructure is the
                foundation; AI applied with judgment is what you build on top of it.
              </motion.p>
              <motion.div
                initial={{ opacity: 0 }}
                whileInView={{ opacity: 1 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.3 }}
              >
                <Link
                  to="/en/soluciones-ia"
                  className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
                >
                  Discover applied AI <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
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

      {/* ── PORTFOLIO ── */}
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
                We design too
              </motion.p>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.1 }}
                className="font-heading font-bold text-foreground max-w-xl"
                style={{ fontSize: 'clamp(28px, 3.5vw, 48px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                Your brand is infrastructure too
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
                View portfolio <ArrowRight size={14} />
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
                      <span className="text-sm font-body tracking-widest uppercase" style={{ color: CHARTREUSE }}>View project</span>
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
            Ready to migrate without the fear?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15, ease: 'easeOut' }}
            className="text-lg text-muted-foreground font-body mb-10 max-w-xl mx-auto"
          >
            I’ll help you pick the right plan and move your site without losing a single customer.
          </motion.p>
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.25 }}
            className="flex flex-wrap items-center justify-center gap-4"
          >
            <Link
              to="/en/catalogo"
              className="group inline-flex items-center gap-3 px-8 py-4 border text-sm font-heading font-bold tracking-wide uppercase transition-all duration-250 relative overflow-hidden"
              style={{ borderColor: CHARTREUSE, color: CHARTREUSE }}
            >
              <span className="relative z-10 group-hover:text-[#0A0A0A] transition-colors duration-250">Explore plans</span>
              <ArrowRight size={16} className="relative z-10 group-hover:text-[#0A0A0A] transition-colors duration-250" />
              <span className="absolute inset-0 translate-x-[-101%] group-hover:translate-x-0 transition-transform duration-250 ease-out" style={{ backgroundColor: CHARTREUSE }} />
            </Link>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-8 py-4 text-sm font-heading font-bold tracking-wide uppercase text-foreground/70 hover:text-foreground transition-colors duration-200"
            >
              <MessageCircle size={16} /> Direct WhatsApp
            </a>
          </motion.div>
        </div>
      </section>
    </>
  );
}
