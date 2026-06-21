import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import {
  Cpu, Zap, Crown, Sprout, Server, Globe, Shield, ShieldCheck,
  Lock, Database, Mail, AtSign, Paintbrush, Layers, Code,
  Stethoscope, LayoutDashboard, Check, ArrowUpRight, MessageCircle,
  ChevronDown, Search, ExternalLink, Compass,
  type LucideIcon,
} from 'lucide-react';
import type { Service, IconHint } from '@/data/catalog';
import { buyKind, productPageUrl } from '@/data/catalog';
import AnimatedPrice from './AnimatedPrice';
import LinkedText from './LinkedText';

type CardLocale = 'es' | 'en';

/**
 * Etiquetas de UI de la tarjeta por idioma. SOLO se traducen los textos de
 * interfaz (CTAs, toggles, encabezados de detalle). Los DATOS del servicio
 * (nombre, descripciones, features, specs) provienen del catálogo y se muestran
 * tal cual — los nombres de producto y precios son universales; el copy
 * descriptivo de catálogo permanece en su idioma de origen hasta que se
 * localicen los datos. Esto mantiene la lógica buyKind y los precios intactos.
 */
const CARD_LABELS: Record<CardLocale, {
  whatsapp: string; domains: string; escaparate: string; checkout: string;
  showLess: string; whatItSolves: string; whenItMakesSense: string;
  idealFor: string; exploreSpecs: string;
}> = {
  es: {
    whatsapp: 'Hablar por WhatsApp',
    domains: 'Buscar dominio',
    escaparate: 'Ver en catálogo completo',
    checkout: 'Contratar ahora',
    showLess: 'Ocultar detalle',
    whatItSolves: '¿Qué resuelve?',
    whenItMakesSense: 'Cuándo tiene sentido',
    idealFor: 'Ideal para',
    exploreSpecs: 'Explorar especificaciones a fondo',
  },
  en: {
    whatsapp: 'Chat on WhatsApp',
    domains: 'Find a domain',
    escaparate: 'View in full catalog',
    checkout: 'Get started now',
    showLess: 'Hide details',
    whatItSolves: 'What does it solve?',
    whenItMakesSense: 'When it makes sense',
    idealFor: 'Ideal for',
    exploreSpecs: 'Explore full specifications',
  },
};

const ICON_MAP: Record<IconHint, LucideIcon> = {
  cpu: Cpu,
  zap: Zap,
  crown: Crown,
  sprout: Sprout,
  server: Server,
  globe: Globe,
  shield: Shield,
  'shield-check': ShieldCheck,
  lock: Lock,
  database: Database,
  mail: Mail,
  'at-sign': AtSign,
  paintbrush: Paintbrush,
  layers: Layers,
  code: Code,
  stethoscope: Stethoscope,
  'layout-dashboard': LayoutDashboard,
};

const cardVariants = {
  hidden: { opacity: 0, y: 24 },
  visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } },
} as const;

export default function ServiceCard({ service, locale = 'es' }: { service: Service; locale?: CardLocale }) {
  const Icon = ICON_MAP[service.iconHint] ?? Globe;
  const kind = buyKind(service.buyUrl);
  const [expanded, setExpanded] = useState(false);
  const L = CARD_LABELS[locale];

  const ctaLabel =
    kind === 'whatsapp' ? L.whatsapp
    : kind === 'domains' ? L.domains
    : kind === 'escaparate' ? L.escaparate
    : L.checkout;

  const CtaIcon =
    kind === 'whatsapp' ? MessageCircle
    : kind === 'domains' ? Search
    : kind === 'escaparate' ? ExternalLink
    : ArrowUpRight;

  const ctaClasses =
    'mt-auto inline-flex items-center justify-center gap-2 w-full h-12 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.02] active:scale-[0.99] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring';

  const handleDomainClick = (e: React.MouseEvent<HTMLAnchorElement>) => {
    if (kind !== 'domains') return;
    e.preventDefault();
    const el = document.getElementById('dominios');
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      const input = el.querySelector('input');
      if (input) setTimeout(() => (input as HTMLInputElement).focus(), 400);
    } else {
      window.location.href = locale === 'en' ? '/en/catalogo#dominios' : '/catalogo#dominios';
    }
  };

  const isInternalDomain = kind === 'domains';

  return (
    <motion.article
      variants={cardVariants}
      whileHover={{ y: -8, transition: { type: 'spring', stiffness: 280, damping: 20 } }}
      transition={{ type: 'spring', stiffness: 300, damping: 24 }}
      className="group relative flex flex-col h-full rounded-2xl border border-border bg-card p-6 shadow-sm transition-[colors,box-shadow] duration-300 hover:border-primary hover:shadow-xl"
    >
      {/* Glow sutil en hover */}
      <div className="pointer-events-none absolute inset-0 rounded-2xl opacity-0 transition-opacity duration-300 group-hover:opacity-100" style={{ boxShadow: '0 0 0 1px hsl(var(--primary) / 0.4), 0 24px 70px -24px hsl(var(--primary) / 0.3)' }} aria-hidden="true" />

      {/* Badge */}
      {service.badge && (
        <span className="absolute top-5 right-5 z-10 font-heading text-[10px] font-bold uppercase tracking-[0.15em] text-primary-foreground bg-primary px-2 py-1 rounded-lg">
          {service.badge}
        </span>
      )}

      {/* Icon + category */}
      <div className="flex items-center gap-3 mb-5">
        <motion.span
          whileHover={{ rotate: -8, scale: 1.08 }}
          transition={{ type: 'spring', stiffness: 400, damping: 12 }}
          className="flex items-center justify-center h-11 w-11 rounded-xl bg-muted text-primary shrink-0"
        >
          <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
        </motion.span>
        <span className="font-body text-xs uppercase tracking-[0.15em] text-muted-foreground">
          {service.category}
        </span>
      </div>

      {/* Title + short desc */}
      <h3 className="font-heading text-2xl font-bold text-card-foreground leading-tight mb-2">
        {service.name}
      </h3>
      <p className="font-body text-sm text-muted-foreground leading-relaxed mb-5">
        {service.shortDescription}
      </p>

      {/* Price (animado) */}
      <AnimatedPrice
        value={service.priceFrom}
        currency={service.currency}
        billingPeriod={service.billingPeriod}
        className="mb-5"
      />

      {/* Features */}
      <ul className="flex flex-col gap-2 mb-4 flex-1">
        {service.features.map((feature, i) => (
          <motion.li
            key={feature}
            initial={{ opacity: 0, x: -6 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.3, delay: i * 0.04, ease: 'easeOut' as const }}
            className="flex items-start gap-2.5 font-body text-sm text-card-foreground/80"
          >
            <Check size={16} className="text-primary shrink-0 mt-0.5" strokeWidth={2.5} aria-hidden="true" />
            <span><LinkedText text={feature} /></span>
          </motion.li>
        ))}
      </ul>

      {/* Toggle de detalle pedagógico */}
      <button
        type="button"
        onClick={() => setExpanded((v) => !v)}
        aria-expanded={expanded}
        className="flex items-center gap-1.5 mb-4 font-body text-xs font-semibold uppercase tracking-wider text-muted-foreground transition-colors duration-200 hover:text-primary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-sm self-start"
      >
        {expanded ? L.showLess : L.whatItSolves}
        <motion.span animate={{ rotate: expanded ? 180 : 0 }} transition={{ duration: 0.2 }}>
          <ChevronDown size={14} aria-hidden="true" />
        </motion.span>
      </button>

      <AnimatePresence initial={false}>
        {expanded && (
          <motion.div
            key="detail"
            initial={{ height: 0, opacity: 0 }}
            animate={{ height: 'auto', opacity: 1 }}
            exit={{ height: 0, opacity: 0 }}
            transition={{ duration: 0.3, ease: 'easeOut' as const }}
            className="overflow-hidden"
          >
            <p className="font-body text-sm leading-relaxed text-card-foreground/75 mb-5 pl-3 border-l-2 border-primary">
              <LinkedText text={service.longDescription} />
            </p>

            {/* Casuística de aplicación — el "cuándo y por qué" (nuestra capa informativa) */}
            {service.useCase && (
              <div className="mb-5 rounded-xl bg-muted p-4">
                <p className="font-body text-xs uppercase tracking-[0.15em] text-primary mb-2 flex items-center gap-1.5">
                  <Compass size={13} aria-hidden="true" />
                  {L.whenItMakesSense}
                </p>
                <p className="font-body text-sm leading-relaxed text-card-foreground/80">
                  <LinkedText text={service.useCase} />
                </p>
              </div>
            )}

            {service.bestFor && service.bestFor.length > 0 && (
              <div className="mb-5">
                <p className="font-body text-xs uppercase tracking-[0.15em] text-muted-foreground mb-2">{L.idealFor}</p>
                <ul className="flex flex-wrap gap-2">
                  {service.bestFor.map((item) => (
                    <li
                      key={item}
                      className="font-body text-xs text-card-foreground/80 bg-muted rounded-lg px-2.5 py-1"
                    >
                      {item}
                    </li>
                  ))}
                </ul>
              </div>
            )}

            {service.specs && Object.keys(service.specs).length > 0 && (
              <dl className="mb-5 grid grid-cols-1 gap-1.5">
                {Object.entries(service.specs).map(([key, val]) => (
                  <div key={key} className="flex items-baseline justify-between gap-3 border-b border-border pb-1.5 last:border-0">
                    <dt className="font-body text-xs uppercase tracking-wider text-muted-foreground shrink-0">{key}</dt>
                    <dd className="font-body text-xs text-card-foreground/90 text-right">
                      <LinkedText text={val} />
                    </dd>
                  </div>
                ))}
              </dl>
            )}
          </motion.div>
        )}
      </AnimatePresence>

      {/* CTA */}
      <a
        href={service.buyUrl}
        onClick={isInternalDomain ? handleDomainClick : undefined}
        target={isInternalDomain ? undefined : '_blank'}
        rel={isInternalDomain ? undefined : 'noopener noreferrer'}
        className={ctaClasses}
        aria-label={`${ctaLabel}: ${service.name}`}
      >
        {ctaLabel}
        <CtaIcon size={16} strokeWidth={2.25} aria-hidden="true" />
      </a>

      {/* CTA secundario — explorar la ficha canónica del producto (sin presión de compra) */}
      {service.canonicalSlug && (
        <a
          href={productPageUrl(service.canonicalSlug)}
          target="_blank"
          rel="noopener noreferrer"
          className="mt-2.5 inline-flex items-center justify-center gap-1.5 w-full font-body text-xs text-muted-foreground hover:text-primary transition-colors duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring rounded-sm py-1"
          aria-label={`${L.exploreSpecs}: ${service.name}`}
        >
          {L.exploreSpecs}
          <ArrowUpRight size={13} aria-hidden="true" />
        </a>
      )}
    </motion.article>
  );
}
