import { useState } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, AnimatePresence } from 'motion/react';
import {
  ShieldCheck, Headset, Clock, BadgeDollarSign, ChevronDown,
  FileSignature, Zap, Globe, LayoutGrid, Bot, ExternalLink, LifeBuoy, type LucideIcon,
} from 'lucide-react';
import CatalogGrid from '@/components/catalog/CatalogGrid';
import DomainSearch from '@/components/catalog/DomainSearch';
import PlanFinder from '@/components/catalog/PlanFinder';
import HostingCompare from '@/components/catalog/HostingCompare';
import CapabilityStatement from '@/components/catalog/CapabilityStatement';
import HowItWorks from '@/components/narrative/HowItWorks';
import { SERVICES, TRUST_POINTS, ACTIVATION_TIMELINE, FAQS, ESCAPARATE_URL, HELP_URL } from '@/data/catalog';
import { LocaleSeo } from '@/components/LocaleSeo';

const SITE = 'https://gano.digital';

/** Mapa de iconHint (string en datos) → componente lucide. */
const TRUST_ICONS: Record<string, LucideIcon> = {
  'shield-check': ShieldCheck,
  clock: Clock,
  'peso-sign': BadgeDollarSign,
  headset: Headset,
};

const TIMELINE_ICONS: Record<string, LucideIcon> = {
  'file-signature': FileSignature,
  bolt: Zap,
  globe: Globe,
  wordpress: LayoutGrid,
  headset: Headset,
  robot: Bot,
};

/** Encabezado de sección reutilizable con kicker + título. */
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

export default function CatalogoPage() {
  const canonicalUrl = `${SITE}/catalogo`;
  const ogImage = `${SITE}/api/og?title=Cat%C3%A1logo+de+Servicios&description=Hosting%2C+dominios%2C+email+y+seguridad+gestionados+por+Gano+Digital.&tag=Cat%C3%A1logo`;

  const jsonLd = {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'CollectionPage',
        '@id': `${canonicalUrl}#webpage`,
        name: 'Catálogo de Servicios — Gano Digital',
        url: canonicalUrl,
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
        isPartOf: { '@id': `${SITE}/#website` },
        mainEntity: FAQS.map((f) => ({
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
        <title>Catálogo de Servicios — Gano Digital</title>
        <meta name="description" content="Hosting WordPress, dominios, email corporativo, SSL y seguridad gestionados. Infraestructura de clase mundial con soporte en español. Compara planes y contrata en segundos." />
        <link rel="canonical" href={canonicalUrl} />
        <meta property="og:title" content="Catálogo de Servicios — Gano Digital" />
        <meta property="og:description" content="Hosting, dominios, email y seguridad gestionados con ingeniería curada." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={canonicalUrl} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Catálogo de Servicios — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Catálogo de Servicios — Gano Digital" />
        <meta name="twitter:description" content="Hosting, dominios, email y seguridad gestionados con ingeniería curada." />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify(jsonLd)}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/catalogo" locale="es" />

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
              Catálogo de<br />Servicios
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            Hosting WordPress, dominios, correo corporativo y seguridad — sobre infraestructura
            de clase mundial, con ingeniería curada. Te explicamos cada término para que elijas
            con criterio, no a ciegas.
          </motion.p>

          {/* Acceso directo al catálogo classic — para quien ya sabe qué busca.
              Pipeline UX: aquí informamos y damos criterio; quien ya decidió
              salta directo a la versión de compra simplificada del reseller. */}
          <motion.div
            initial={{ opacity: 0, y: 16 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.22 }}
            className="mt-7 flex flex-wrap items-center gap-x-3 gap-y-1.5"
          >
            <span className="font-body text-sm text-muted-foreground">
              ¿Ya sabes lo que necesitas?
            </span>
            <a
              href={ESCAPARATE_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center gap-1.5 font-body text-sm font-medium text-primary hover:opacity-80 transition-opacity duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-xl"
            >
              <LayoutGrid size={15} strokeWidth={2} aria-hidden="true" />
              Ir al catálogo classic de compra
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
            {TRUST_POINTS.map((tp) => {
              const Icon = TRUST_ICONS[tp.icon] ?? ShieldCheck;
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

      {/* Statement de capacidad — posicionamiento de estudio */}
      <CapabilityStatement />

      {/* Asistente de elección */}
      <section className="pb-16 md:pb-20 bg-background pt-16 md:pt-24">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <PlanFinder />
        </div>
      </section>

      {/* Buscador de dominios */}
      <section id="dominios" className="pb-16 md:pb-20 bg-background scroll-mt-24">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="Tu nombre en internet"
            title="¿Tu dominio está libre?"
            desc="El primer paso de cualquier presencia digital seria. Verifica disponibilidad y regístralo al instante."
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

      {/* Tabla comparativa de hosting */}
      <section className="pb-16 md:pb-20 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="Hosting WordPress"
            title="Compara y decide"
            desc="Cuatro planes, una sola tabla. Pasa el cursor sobre los términos técnicos para entender qué significan."
          />
          <HostingCompare />
        </div>
      </section>

      {/* Catálogo completo */}
      <section className="pb-24 md:pb-32 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="Todo el catálogo"
            title="Explora cada servicio"
            desc="Filtra por categoría. Cada tarjeta explica qué resuelve — toca «¿Qué resuelve?» para el detalle."
          />
          <CatalogGrid />
        </div>
      </section>

      {/* Puente al escaparate completo de GoDaddy */}
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
                ¿Buscas algo más específico?
              </p>
              <h2
                className="font-heading font-bold text-card-foreground mb-3"
                style={{ fontSize: 'clamp(24px, 3vw, 38px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
              >
                Explora el catálogo completo
              </h2>
              <p className="font-body text-muted-foreground leading-relaxed max-w-2xl">
                Aquí curamos los servicios que más recomendamos. Si necesitas un producto
                puntual —certificados avanzados, planes específicos o herramientas adicionales—
                puedes verlos todos en nuestro catálogo completo, con el mismo respaldo y precios de Gano Digital.
              </p>
            </div>
            <a
              href={ESCAPARATE_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="group inline-flex items-center justify-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03] shrink-0"
            >
              Ver catálogo completo
              <ExternalLink size={16} strokeWidth={2.25} className="transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true" />
            </a>
          </motion.div>

          {/* Cierre del pipeline: explorar → comprar → soporte canónico */}
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="mt-5 text-center font-body text-sm text-muted-foreground"
          >
            ¿Ya eres cliente y necesitas ayuda con un servicio?{' '}
            <a
              href={HELP_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1 text-primary hover:opacity-80 transition-opacity duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-xl"
            >
              <LifeBuoy size={14} aria-hidden="true" />
              Centro de ayuda
            </a>
          </motion.p>
        </div>
      </section>

      {/* Cómo funciona la contratación */}
      <section className="pb-20 md:pb-28 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10 pt-8 border-t border-border">
          <HowItWorks />
        </div>
      </section>

      {/* Línea de tiempo de activación */}
      <section className="pb-20 md:pb-28 bg-background">
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <SectionHead
            kicker="De la contratación al aire"
            title="Tu sitio, activo en minutos"
            desc="Apenas confirmas, el reloj corre a tu favor. Esto es lo que pasa después de contratar."
          />
          <motion.ol
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
          >
            {ACTIVATION_TIMELINE.map((step) => {
              const Icon = TIMELINE_ICONS[step.icon] ?? Zap;
              return (
                <motion.li
                  key={step.title}
                  variants={{
                    hidden: { opacity: 0, y: 24 },
                    visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
                  }}
                  className="flex flex-col rounded-xl border border-border bg-card p-6 transition-colors duration-300 hover:border-primary"
                >
                  <div className="flex items-center justify-between mb-4">
                    <span className="flex items-center justify-center h-11 w-11 rounded-xl bg-muted text-primary">
                      <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                    </span>
                    <span className="font-heading text-sm font-bold uppercase tracking-[0.12em] text-primary-foreground bg-primary px-2.5 py-1 rounded-xl tabular-nums">
                      {step.t}
                    </span>
                  </div>
                  <h3 className="font-heading text-lg font-bold text-card-foreground mb-1.5 leading-tight">{step.title}</h3>
                  <p className="font-body text-sm text-muted-foreground leading-relaxed">{step.body}</p>
                </motion.li>
              );
            })}
          </motion.ol>
        </div>
      </section>

      {/* Preguntas frecuentes */}
      <section className="pb-24 md:pb-32 bg-background">
        <div className="max-w-3xl mx-auto px-6 md:px-10">
          <SectionHead kicker="Antes de contratar" title="Preguntas frecuentes" />
          <CatalogFaq />
        </div>
      </section>
    </>
  );
}

function CatalogFaq() {
  const [open, setOpen] = useState<number | null>(0);
  return (
    <div className="flex flex-col">
      {FAQS.map((item, i) => {
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
