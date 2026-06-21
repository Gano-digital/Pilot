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
const WHATSAPP = 'https://wa.me/573135646123?text=Quiero+blindar+la+seguridad+de+mi+empresa';

const site = 'https://gano.digital';
const title = 'Seguridad y Protección de Marca — Gano Digital';
const description =
  'Pensar preventivamente cuesta menos que reaccionar tarde. SSL, correo anti-phishing, firewall WAF, backups y mitigación de amenazas con IA para empresas que manejan información sensible.';
const ogImage = `${site}/api/og?title=Seguridad+que+previene%2C+no+que+lamenta&description=SSL%2C+WAF%2C+backups+y+mitigaci%C3%B3n+de+amenazas+con+IA.&tag=Seguridad`;

// Amenazas reales — el panorama
const threats: { icon: LucideIcon; name: string; desc: string }[] = [
  {
    icon: Bug,
    name: 'Malware e inyecciones',
    desc: 'SQL injection, XSS y scripts maliciosos que secuestran tu sitio, roban datos o lo convierten en plataforma de spam sin que lo notes.',
  },
  {
    icon: KeyRound,
    name: 'Robo de credenciales',
    desc: 'Ataques de fuerza bruta y phishing que comprometen tus accesos. Una sola contraseña filtrada puede abrir toda tu operación.',
  },
  {
    icon: EyeOff,
    name: 'Suplantación de marca',
    desc: 'Correos falsos enviados a tu nombre, dominios clonados y estafas a tus clientes que erosionan la confianza que tanto costó construir.',
  },
  {
    icon: Database,
    name: 'Pérdida de datos',
    desc: 'Un hackeo, un error humano o una falla de hardware. Sin backups, un año de trabajo puede desaparecer en segundos.',
  },
];

// Prácticas preventivas — la cultura
const practices = [
  'Cifrado HTTPS en todo el sitio, siempre — no solo en el checkout.',
  'Backups automáticos diarios, almacenados separados del servidor.',
  'Autenticación de correo (DKIM, SPF, DMARC) para que nadie suplante tu marca.',
  'Firewall que filtra tráfico malicioso antes de que llegue a tu sitio.',
  'Actualizaciones gestionadas: el software viejo es la puerta favorita del atacante.',
  'Monitoreo continuo: detectar temprano es la mitad de la defensa.',
];

// Servicios concretos del catálogo
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

export default function SeguridadPage() {
  const securityServices = securityIds
    .map((id) => SERVICES.find((s) => s.id === id))
    .filter((s): s is Service => Boolean(s));

  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/seguridad`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/seguridad`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Seguridad y protección de marca — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Service',
          '@id': `${site}/seguridad#service`,
          name: 'Seguridad web y protección de marca',
          serviceType: 'Ciberseguridad para empresas',
          url: `${site}/seguridad`,
          areaServed: 'CO',
          provider: { '@id': `${site}/#organization` },
          description,
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/seguridad" locale="es" />

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
            Seguridad y protección de marca
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
              La seguridad no se compra después del ataque.{' '}
              <span style={{ color: CHARTREUSE }}>Se previene antes.</span>
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl mb-10"
            style={{ lineHeight: 1.7 }}
          >
            Manejar información de clientes es una responsabilidad, no solo una ventaja. Te
            ayudo a pensar preventivamente: blindar tu sitio, tu correo y tus datos antes de
            que una amenaza se convierta en una crisis — con prácticas sólidas y mitigación
            basada en inteligencia artificial.
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
              Blindar mi empresa <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </a>
            <Link
              to="/#diagnostico"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <Stethoscope size={16} /> Evaluar mi riesgo
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── AMENAZAS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              El panorama real
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Las amenazas no avisan. Y casi nunca apuntan al más grande.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              Los ataques automatizados no distinguen tamaño: buscan al desprotegido. La
              pyme suele ser el blanco más fácil precisamente porque cree que «a mí no me va a pasar».
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

      {/* ── MITIGACIÓN CON IA (diferenciador) ── */}
      <section className="py-20 md:py-28 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
            <div className="lg:col-span-7">
              <p className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body mb-5" style={{ color: CHARTREUSE }}>
                <BrainCircuit size={14} /> Defensa inteligente
              </p>
              <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
                Las amenazas evolucionan. Tu defensa también debería.
              </h2>
              <p className="font-body text-lg text-muted-foreground mb-6" style={{ lineHeight: 1.7 }}>
                La nueva generación de seguridad no se basa solo en reglas fijas, sino en
                modelos que aprenden patrones de ataque en tiempo real. Bot mitigation con
                machine learning, detección de anomalías y filtrado adaptativo que bloquea
                amenazas que un firewall tradicional dejaría pasar.
              </p>
              <ul className="flex flex-col gap-3">
                {[
                  'Bot mitigation con ML que distingue tráfico real de ataques.',
                  'Detección de anomalías antes de que escalen a incidente.',
                  'Reglas que evolucionan con las amenazas reales del entorno.',
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

      {/* ── PRÁCTICAS PREVENTIVAS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-12">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              La base no negociable
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Seis prácticas que toda empresa seria debería tener.
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

      {/* ── SERVICIOS CONCRETOS ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Tu kit de blindaje
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Servicios concretos para cada capa de defensa.
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
                      <span className="text-muted-foreground">Desde </span>
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
                      Contratar <ArrowRight size={14} className="transition-transform duration-200 group-hover:translate-x-1" />
                    </a>
                  </div>
                </motion.div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── CTA FINAL ── */}
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
            ¿No sabes por dónde empezar a protegerte?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground mb-10 max-w-2xl mx-auto"
            style={{ lineHeight: 1.7 }}
          >
            Haz el diagnóstico operativo: en dos minutos identificamos tus puntos ciegos de
            seguridad y te decimos, con franqueza, qué resolver primero.
          </motion.p>
          <Link
            to="/#diagnostico"
            className="group inline-flex items-center gap-2 h-13 px-8 py-4 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
          >
            <Stethoscope size={16} /> Evaluar mi seguridad
            <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
          </Link>
        </div>
      </section>
    </>
  );
}
