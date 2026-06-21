import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import {
  ShieldAlert, ShieldCheck, Lock, Database, Mail, BrainCircuit,
  Bug, KeyRound, EyeOff, Fingerprint, ArrowRight, Stethoscope,
  type LucideIcon,
} from 'lucide-react';
import { SERVICES, type Service } from '@/data/catalog';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=I+want+to+harden+my+company%27s+security';

const site = 'https://gano.digital';
const title = 'Security & Brand Protection — Gano Digital';
const description =
  'Thinking preventively costs less than reacting too late. SSL, anti-phishing email, WAF firewall, backups and AI-based threat mitigation for companies that handle sensitive information.';
const ogImage = `${site}/api/og?title=Security+that+prevents%2C+not+regrets&description=SSL%2C+WAF%2C+backups+and+AI+threat+mitigation.&tag=Security`;

const threats: { icon: LucideIcon; name: string; desc: string }[] = [
  {
    icon: Bug,
    name: 'Malware & injections',
    desc: 'SQL injection, XSS and malicious scripts that hijack your site, steal data or turn it into a spam platform without you noticing.',
  },
  {
    icon: KeyRound,
    name: 'Credential theft',
    desc: 'Brute-force and phishing attacks that compromise your access. A single leaked password can open your entire operation.',
  },
  {
    icon: EyeOff,
    name: 'Brand impersonation',
    desc: 'Fake emails sent in your name, cloned domains and scams against your customers that erode the trust you worked so hard to build.',
  },
  {
    icon: Database,
    name: 'Data loss',
    desc: 'A hack, human error or hardware failure. Without backups, a year of work can vanish in seconds.',
  },
];

const practices = [
  'HTTPS encryption across the whole site, always — not just at checkout.',
  'Automatic daily backups, stored separately from the server.',
  'Email authentication (DKIM, SPF, DMARC) so no one can impersonate your brand.',
  'A firewall that filters malicious traffic before it reaches your site.',
  'Managed updates: outdated software is the attacker\u2019s favorite door.',
  'Continuous monitoring: detecting early is half the defense.',
];

const securityIds = ['security-premium', 'security-advanced', 'codeguard-backup', 'email-pro'];

const SERVICE_ICONS: Record<string, LucideIcon> = {
  'ssl-dv': Lock,
  'security-premium': ShieldCheck,
  'security-advanced': ShieldCheck,
  'codeguard-backup': Database,
  'email-pro': Mail,
};

function formatCOP(value: number): string {
  return new Intl.NumberFormat('es-CO').format(value);
}

export default function SeguridadPageEn() {
  const securityServices = securityIds
    .map((id) => SERVICES.find((s) => s.id === id))
    .filter((s): s is Service => Boolean(s));

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/en/seguridad`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/en/seguridad`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Security & brand protection — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Service',
          '@id': `${site}/en/seguridad#service`,
          name: 'Web security and brand protection',
          serviceType: 'Cybersecurity for businesses',
          url: `${site}/en/seguridad`,
          inLanguage: 'en',
          areaServed: 'CO',
          provider: { '@id': `${site}/#organization` },
          description,
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/seguridad" locale="en" />

      {/* ── HERO ── */}
      <section className="relative overflow-hidden pt-36 pb-24 md:pt-44 md:pb-32" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 -left-1/4 w-[60vw] h-[60vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.10) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.07, 1], opacity: [0.5, 0.9, 0.5] }}
          transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <motion.span
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body text-foreground/90 border border-border rounded-full px-3 py-1.5 mb-8"
          >
            <ShieldAlert size={12} style={{ color: CHARTREUSE }} />
            Security & brand protection
          </motion.span>
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <h1
              className="font-heading font-bold text-foreground max-w-4xl mb-8"
              style={{ fontSize: 'clamp(40px, 7vw, 88px)', letterSpacing: '-0.03em', lineHeight: 1.02 }}
            >
              Security isn't bought after the attack.{' '}
              <span style={{ color: CHARTREUSE }}>It's prevented before.</span>
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl mb-10"
            style={{ lineHeight: 1.7 }}
          >
            Handling customer data is a responsibility, not just an advantage. I help you think
            preventively: harden your site, your email and your data before a threat becomes a
            crisis — with solid practices and AI-based mitigation.
          </motion.p>
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.35, ease: 'easeOut' }}
            className="flex flex-wrap items-center gap-4"
          >
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Harden my company <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </a>
            <Link
              to="/en#diagnostico"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <Stethoscope size={16} /> Assess my risk
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── THREATS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              The real landscape
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Threats don't announce themselves. And they rarely target the biggest.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              Automated attacks don't care about size: they look for the unprotected. The small
              business is often the easiest target precisely because it thinks “it won't happen to me.”
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {threats.map((t, i) => {
              const Icon = t.icon;
              return (
                <motion.div
                  key={t.name}
                  initial={{ opacity: 0, y: 24 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: i * 0.08, ease: 'easeOut' as const }}
                  className="flex gap-5 p-7 rounded-md"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <span className="flex items-center justify-center h-12 w-12 rounded-xl shrink-0" style={{ backgroundColor: 'rgba(229,72,77,0.10)', color: '#E5484D' }}>
                    <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="font-heading font-bold text-foreground text-xl mb-2" style={{ letterSpacing: '-0.01em' }}>{t.name}</h3>
                    <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{t.desc}</p>
                  </div>
                </motion.div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── AI MITIGATION (differentiator) ── */}
      <section className="py-20 md:py-28 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div className="lg:col-span-7">
              <p className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body mb-5" style={{ color: CHARTREUSE }}>
                <BrainCircuit size={14} /> Intelligent defense
              </p>
              <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
                Threats evolve. Your defense should too.
              </h2>
              <p className="font-body text-lg text-muted-foreground mb-6" style={{ lineHeight: 1.7 }}>
                The new generation of security relies not only on fixed rules, but on models that
                learn attack patterns in real time. Bot mitigation with machine learning, anomaly
                detection and adaptive filtering that blocks threats a traditional firewall would let through.
              </p>
              <ul className="flex flex-col gap-3">
                {[
                  'ML bot mitigation that tells real traffic from attacks.',
                  'Anomaly detection before it escalates into an incident.',
                  'Rules that evolve with the real threats in your environment.',
                ].map((t) => (
                  <li key={t} className="flex items-start gap-3">
                    <Fingerprint size={18} className="mt-0.5 shrink-0" style={{ color: CHARTREUSE }} aria-hidden="true" />
                    <span className="font-body text-sm text-foreground/80" style={{ lineHeight: 1.6 }}>{t}</span>
                  </li>
                ))}
              </ul>
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
                <ShieldCheck size={88} strokeWidth={1.2} style={{ color: CHARTREUSE }} aria-hidden="true" />
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* ── PREVENTIVE PRACTICES ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-12">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              The non-negotiable baseline
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Six practices every serious business should have.
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-5">
            {practices.map((p, i) => (
              <motion.div
                key={p}
                initial={{ opacity: 0, x: -12 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: i * 0.06 }}
                className="flex items-start gap-4 py-4"
                style={{ borderBottom: '1px solid hsl(var(--border))' }}
              >
                <span className="font-mono text-sm shrink-0 mt-0.5" style={{ color: CHARTREUSE }}>0{i + 1}</span>
                <span className="font-body text-base text-foreground/85" style={{ lineHeight: 1.5 }}>{p}</span>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── CONCRETE SERVICES ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Your hardening kit
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Concrete services for every layer of defense.
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {securityServices.map((s, i) => {
              const Icon = SERVICE_ICONS[s.id] ?? ShieldCheck;
              return (
                <motion.div
                  key={s.id}
                  initial={{ opacity: 0, y: 24 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: i * 0.08, ease: 'easeOut' as const }}
                  className="flex flex-col p-7 rounded-md"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <div className="flex items-start gap-4 mb-4">
                    <span className="flex items-center justify-center h-12 w-12 rounded-xl shrink-0" style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}>
                      <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                    </span>
                    <div>
                      <h3 className="font-heading font-bold text-foreground text-xl" style={{ letterSpacing: '-0.01em' }}>{s.name}</h3>
                      <p className="font-body text-sm text-muted-foreground mt-1">{s.shortDescription}</p>
                    </div>
                  </div>
                  <p className="font-body text-sm text-foreground/70 mb-5" style={{ lineHeight: 1.6 }}>{s.longDescription}</p>
                  <div className="mt-auto flex items-center justify-between pt-4" style={{ borderTop: '1px solid hsl(var(--border))' }}>
                    <span className="font-body text-sm text-foreground">
                      <span className="text-muted-foreground">From </span>
                      <strong style={{ color: CHARTREUSE }}>${formatCOP(s.priceFrom)}</strong>
                      <span className="text-muted-foreground"> {s.currency}/{s.billingPeriod}</span>
                    </span>
                    <a
                      href={s.buyUrl}
                      target={s.buyUrl.startsWith('http') ? '_blank' : undefined}
                      rel={s.buyUrl.startsWith('http') ? 'noopener noreferrer' : undefined}
                      className="group inline-flex items-center gap-1.5 text-sm font-heading font-bold uppercase tracking-[0.08em] transition-colors duration-200 hover:opacity-80"
                      style={{ color: CHARTREUSE }}
                    >
                      Get started <ArrowRight size={14} className="transition-transform duration-200 group-hover:translate-x-1" />
                    </a>
                  </div>
                </motion.div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── FINAL CTA ── */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' as const }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(28px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Not sure where to start protecting yourself?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground mb-10 max-w-2xl mx-auto"
            style={{ lineHeight: 1.7 }}
          >
            Take the operational diagnosis: in two minutes we identify your security blind spots
            and tell you, frankly, what to fix first.
          </motion.p>
          <Link
            to="/en#diagnostico"
            className="group inline-flex items-center gap-2 h-13 px-8 py-4 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
          >
            <Stethoscope size={16} /> Assess my security
            <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
          </Link>
        </div>
      </section>
    </>
  );
}
