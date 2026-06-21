import { useState, useCallback } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, AnimatePresence, useReducedMotion } from 'motion/react';
import { Link } from 'react-router-dom';
import { X, ArrowUpRight, Check, Clock, Sparkles } from 'lucide-react';
import {
  DESIGN_PACKAGES,
  designWhatsappUrl,
  formatCOP,
  type DesignPackage,
} from '@/data/designCatalog';

const CHARTREUSE = '#D97E3A';

/* ── Mini-mockup que previsualiza el ESTILO de cada paquete usando su paleta ── */
function StylePreview({ pkg }: { pkg: DesignPackage }) {
  const { palette } = pkg;
  const reduce = useReducedMotion();

  // Cada vibe tiene una previsualización visual distinta.
  switch (pkg.vibe) {
    case 'neo-brutalist':
      return (
        <div className="w-full h-full p-5 flex flex-col gap-2" style={{ backgroundColor: palette.bg }}>
          <div
            className="px-3 py-2 font-black text-2xl leading-none"
            style={{
              backgroundColor: palette.surface,
              color: palette.text,
              border: `3px solid ${palette.text}`,
              boxShadow: `5px 5px 0 ${palette.accent}`,
            }}
          >
            BOLD.
          </div>
          <div className="flex gap-2 mt-1">
            <span className="px-2 py-1 text-[10px] font-bold" style={{ backgroundColor: palette.accent, color: '#fff', border: `2px solid ${palette.text}` }}>NUEVO</span>
            <span className="px-2 py-1 text-[10px] font-bold" style={{ backgroundColor: palette.accent2, color: '#fff', border: `2px solid ${palette.text}` }}>2026</span>
          </div>
        </div>
      );
    case 'kinetic-3d':
    case 'retro-future': {
      const glow = pkg.vibe === 'retro-future' ? palette.accent : palette.accent;
      return (
        <div className="relative w-full h-full overflow-hidden flex items-center justify-center" style={{ backgroundColor: palette.bg }}>
          <motion.div
            className="absolute rounded-full"
            style={{ width: 120, height: 120, background: `radial-gradient(circle, ${glow}, transparent 70%)`, filter: 'blur(8px)' }}
            animate={reduce ? undefined : { scale: [1, 1.25, 1], opacity: [0.6, 0.9, 0.6] }}
            transition={{ duration: 4, repeat: Infinity, ease: 'easeInOut' }}
          />
          <motion.div
            className="absolute w-16 h-16 rounded-xl"
            style={{ border: `2px solid ${palette.accent2}`, backgroundColor: 'transparent' }}
            animate={reduce ? undefined : { rotate: [0, 90, 180, 270, 360] }}
            transition={{ duration: 12, repeat: Infinity, ease: 'linear' }}
          />
          <span className="relative font-bold text-lg tracking-tight" style={{ color: palette.text }}>
            {pkg.vibe === 'retro-future' ? 'RETRO//FUTURE' : '3D · WebGL'}
          </span>
        </div>
      );
    }
    case 'editorial':
      return (
        <div className="w-full h-full p-5 flex flex-col justify-center" style={{ backgroundColor: palette.bg }}>
          <span className="text-[10px] tracking-[0.3em] uppercase mb-2" style={{ color: palette.accent }}>01 — Editorial</span>
          <span className="text-3xl leading-none mb-2" style={{ color: palette.text, fontFamily: 'Georgia, serif', fontStyle: 'italic' }}>
            La palabra<br />como marca.
          </span>
          <div className="w-10 h-px mt-1" style={{ backgroundColor: palette.accent2 }} />
        </div>
      );
    case 'minimal-luxe':
      return (
        <div className="w-full h-full flex flex-col items-center justify-center gap-3" style={{ backgroundColor: palette.bg }}>
          <span className="text-[10px] tracking-[0.35em] uppercase" style={{ color: palette.accent }}>MAISON</span>
          <span className="text-2xl" style={{ color: palette.text, fontFamily: 'Georgia, serif' }}>Lujo silencioso</span>
          <div className="w-8 h-px" style={{ backgroundColor: palette.accent }} />
        </div>
      );
    case 'warm-organic':
      return (
        <div className="relative w-full h-full overflow-hidden flex items-center justify-center" style={{ backgroundColor: palette.bg }}>
          <div className="absolute w-28 h-28 rounded-full" style={{ backgroundColor: palette.accent2, opacity: 0.25, top: -20, left: -10 }} />
          <div className="absolute w-20 h-20 rounded-full" style={{ backgroundColor: palette.accent, opacity: 0.3, bottom: -10, right: 0 }} />
          <div className="relative text-center">
            <span className="block text-2xl mb-1" style={{ color: palette.text, fontFamily: 'Georgia, serif' }}>Te acompañamos</span>
            <span className="inline-block px-3 py-1 rounded-full text-[11px] font-semibold" style={{ backgroundColor: palette.accent, color: '#fff' }}>Reservar</span>
          </div>
        </div>
      );
    case 'corporate-trust':
      return (
        <div className="w-full h-full p-5 flex flex-col gap-3" style={{ backgroundColor: palette.bg }}>
          <div className="flex items-center justify-between">
            <span className="font-bold text-sm" style={{ color: palette.text }}>Soluciones</span>
            <span className="px-2 py-0.5 rounded text-[10px] font-semibold" style={{ backgroundColor: palette.accent, color: '#fff' }}>B2B</span>
          </div>
          <div className="grid grid-cols-3 gap-1.5">
            {[palette.accent, palette.accent2, palette.accent].map((c, i) => (
              <div key={i} className="rounded p-2" style={{ backgroundColor: palette.surface }}>
                <div className="w-5 h-5 rounded mb-1" style={{ backgroundColor: c, opacity: 0.85 }} />
                <div className="w-full h-1 rounded" style={{ backgroundColor: '#D8E0EA' }} />
              </div>
            ))}
          </div>
          <div className="flex items-end gap-1 mt-auto h-8">
            {[40, 65, 50, 80, 70].map((h, i) => (
              <div key={i} className="flex-1 rounded-t" style={{ height: `${h}%`, backgroundColor: palette.accent2, opacity: 0.7 }} />
            ))}
          </div>
        </div>
      );
    case 'commerce-bold':
      return (
        <div className="w-full h-full p-4 grid grid-cols-2 gap-2" style={{ backgroundColor: palette.bg }}>
          {[0, 1].map((i) => (
            <div key={i} className="rounded-lg overflow-hidden flex flex-col" style={{ border: '1px solid #eee' }}>
              <div className="flex-1" style={{ backgroundColor: i === 0 ? palette.accent : palette.surface, minHeight: 44 }} />
              <div className="p-1.5 bg-white">
                <div className="w-3/4 h-1.5 rounded mb-1" style={{ backgroundColor: '#222' }} />
                <span className="text-[10px] font-bold" style={{ color: palette.accent }}>$ 89.000</span>
              </div>
            </div>
          ))}
        </div>
      );
    default:
      return <div className="w-full h-full" style={{ backgroundColor: palette.bg }} />;
  }
}

/* ── Tira de muestras de color de la paleta ── */
function PaletteSwatches({ pkg }: { pkg: DesignPackage }) {
  const swatches = [pkg.palette.bg, pkg.palette.surface, pkg.palette.text, pkg.palette.accent, pkg.palette.accent2];
  return (
    <div className="flex gap-1.5">
      {swatches.map((c, i) => (
        <span key={i} className="w-5 h-5 rounded-full" style={{ backgroundColor: c, border: '1px solid rgba(255,255,255,0.15)' }} title={c} />
      ))}
    </div>
  );
}

const cardVariants = {
  hidden: { opacity: 0, y: 28 },
  visible: (i: number) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.45, delay: i * 0.06, ease: 'easeOut' as const },
  }),
};

export default function DisenosPage() {
  const [selected, setSelected] = useState<DesignPackage | null>(null);
  const reduce = useReducedMotion();

  const close = useCallback(() => setSelected(null), []);

  const itemListJsonLd = {
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    '@id': 'https://gano.digital/disenos#catalog',
    name: 'Catálogo de Diseño Web — Gano Digital',
    description: 'Paquetes de diseño y desarrollo web con estilos propios para cada tipo de marca.',
    itemListElement: DESIGN_PACKAGES.map((p, i) => ({
      '@type': 'ListItem',
      position: i + 1,
      item: {
        '@type': 'Service',
        name: p.name,
        description: p.description,
        provider: { '@id': 'https://gano.digital/#organization' },
        areaServed: { '@type': 'Country', name: 'Colombia' },
        offers: { '@type': 'Offer', priceCurrency: 'COP', price: p.priceFrom },
      },
    })),
  };

  return (
    <>
      <Helmet>
        <title>Diseño Web — Estudios SOTA | Gano Digital</title>
        <meta name="description" content="Catálogo de diseño y desarrollo web: 8 estilos propios — editorial, 3D interactivo, lujo minimalista, brutalist, orgánico, corporativo, retro-futurista y e-commerce. Estrategia lista para producir, facturación en COP." />
        <link rel="canonical" href="https://gano.digital/disenos" />
        <meta property="og:title" content="Diseño Web — Estudios SOTA | Gano Digital" />
        <meta property="og:description" content="8 estilos de diseño web propios, cada uno con su identidad visual y estrategia lista para producir." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/disenos" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Dise%C3%B1o+Web+SOTA&description=8+estilos+propios%2C+estrategia+lista+para+producir.&tag=Dise%C3%B1o" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Diseño Web SOTA — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Diseño Web — Estudios SOTA | Gano Digital" />
        <meta name="twitter:description" content="8 estilos de diseño web propios, cada uno con su identidad visual y estrategia lista para producir." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Dise%C3%B1o+Web+SOTA&description=8+estilos+propios%2C+estrategia+lista+para+producir.&tag=Dise%C3%B1o" />
        <script type="application/ld+json">{JSON.stringify(itemListJsonLd)}</script>
      </Helmet>

      {/* ── HEADER ── */}
      <section className="pt-36 pb-14 md:pt-44 md:pb-16" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <p className="font-body text-xs tracking-[0.25em] uppercase mb-4" style={{ color: CHARTREUSE }}>
              Diseño & Desarrollo Web
            </p>
            <h1
              className="font-heading font-bold text-foreground mb-5"
              style={{ fontSize: 'clamp(48px, 7vw, 88px)', letterSpacing: '-0.03em' }}
            >
              Estudios SOTA
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            No vendemos plantillas. Cada estilo es un estudio de diseño con identidad propia — tipografía, paleta,
            movimiento y arquitectura pensados para un tipo de marca. Elige el que habla por la tuya; nosotros lo producimos.
          </motion.p>
        </div>
      </section>

      {/* ── GRID ── */}
      <section className="pb-24 md:pb-32" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {DESIGN_PACKAGES.map((pkg, i) => (
              <motion.button
                key={pkg.id}
                custom={i}
                variants={reduce ? undefined : cardVariants}
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, margin: '-40px' }}
                onClick={() => setSelected(pkg)}
                className="group text-left rounded-lg overflow-hidden flex flex-col"
                style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                whileHover={reduce ? undefined : { y: -4 }}
                transition={{ duration: 0.2 }}
              >
                {/* Previsualización del estilo */}
                <div className="relative h-44 overflow-hidden border-b" style={{ borderColor: 'hsl(var(--border))' }}>
                  <StylePreview pkg={pkg} />
                  {pkg.badge && (
                    <span
                      className="absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-heading font-bold tracking-widest uppercase"
                      style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                    >
                      {pkg.badge}
                    </span>
                  )}
                </div>

                {/* Cuerpo */}
                <div className="p-6 flex flex-col flex-1">
                  <h2 className="font-heading font-bold text-foreground text-xl mb-1.5" style={{ letterSpacing: '-0.02em' }}>
                    {pkg.name}
                  </h2>
                  <p className="font-body text-sm text-muted-foreground mb-4" style={{ lineHeight: 1.6 }}>
                    {pkg.tagline}
                  </p>

                  <div className="mb-4">
                    <PaletteSwatches pkg={pkg} />
                  </div>

                  <p className="font-body text-xs text-muted-foreground/80 mb-5">
                    {pkg.typography.note} · {pkg.timeline}
                  </p>

                  <div className="mt-auto flex items-end justify-between">
                    <div>
                      <span className="block text-[10px] uppercase tracking-widest text-muted-foreground mb-0.5">Desde</span>
                      <span className="font-heading font-bold text-lg" style={{ color: CHARTREUSE }}>
                        {formatCOP(pkg.priceFrom)}
                      </span>
                    </div>
                    <span
                      className="w-9 h-9 rounded-full flex items-center justify-center shrink-0 transition-transform group-hover:rotate-45"
                      style={{ backgroundColor: CHARTREUSE }}
                    >
                      <ArrowUpRight size={16} style={{ color: '#0A0A0A' }} />
                    </span>
                  </div>
                </div>
              </motion.button>
            ))}
          </div>

          {/* Nota de cierre */}
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="text-sm text-muted-foreground font-body mt-12 text-center max-w-2xl mx-auto"
          >
            ¿No encaja ninguno al 100%? Combinamos estilos o creamos uno a medida.{' '}
            <Link to="/contact" className="underline hover:text-foreground transition-colors" style={{ color: CHARTREUSE }}>
              Cuéntanos tu proyecto →
            </Link>
          </motion.p>
        </div>
      </section>

      {/* ── MODAL DE DETALLE ── */}
      <AnimatePresence>
        {selected && (
          <motion.div
            key="backdrop"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            className="fixed inset-0 z-[200] flex items-center justify-center p-4 md:p-10 overflow-y-auto"
            style={{ backgroundColor: 'rgba(0,0,0,0.92)' }}
            onClick={close}
          >
            <motion.div
              key="modal"
              initial={{ opacity: 0, scale: 0.94, y: 24 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.96, y: 16 }}
              transition={{ duration: 0.3, ease: 'easeOut' }}
              className="relative max-w-3xl w-full rounded-lg overflow-hidden my-auto"
              style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
              onClick={(e) => e.stopPropagation()}
            >
              {/* Previsualización grande */}
              <div className="relative h-52 md:h-60 border-b" style={{ borderColor: 'hsl(var(--border))' }}>
                <StylePreview pkg={selected} />
                <button
                  onClick={close}
                  aria-label="Cerrar"
                  className="absolute top-3 right-3 w-9 h-9 rounded-full flex items-center justify-center transition-transform hover:rotate-90"
                  style={{ backgroundColor: 'rgba(10,10,10,0.7)', color: '#fff' }}
                >
                  <X size={18} />
                </button>
              </div>

              <div className="p-7 md:p-9">
                <div className="flex items-start justify-between gap-4 mb-1">
                  <h2 className="font-heading font-bold text-foreground text-2xl md:text-3xl" style={{ letterSpacing: '-0.02em' }}>
                    {selected.name}
                  </h2>
                  <span className="font-heading font-bold text-lg shrink-0" style={{ color: CHARTREUSE }}>
                    {formatCOP(selected.priceFrom)}
                  </span>
                </div>
                <p className="font-body italic text-muted-foreground mb-5">"{selected.tagline}"</p>
                <p className="font-body text-base text-foreground/80 mb-6" style={{ lineHeight: 1.7 }}>
                  {selected.description}
                </p>

                {/* Meta grid */}
                <div className="grid grid-cols-2 gap-x-6 gap-y-5 mb-7">
                  <div>
                    <p className="text-[10px] uppercase tracking-widest text-muted-foreground mb-2">Paleta</p>
                    <PaletteSwatches pkg={selected} />
                  </div>
                  <div>
                    <p className="text-[10px] uppercase tracking-widest text-muted-foreground mb-2">Tipografía</p>
                    <p className="font-body text-sm text-foreground/80">{selected.typography.heading}</p>
                    <p className="font-body text-xs text-muted-foreground">+ {selected.typography.body}</p>
                  </div>
                  <div className="col-span-2">
                    <p className="text-[10px] uppercase tracking-widest text-muted-foreground mb-2 flex items-center gap-1.5">
                      <Sparkles size={11} style={{ color: CHARTREUSE }} /> Movimiento
                    </p>
                    <p className="font-body text-sm text-foreground/80">{selected.motion}</p>
                  </div>
                </div>

                {/* Ideal para */}
                <div className="mb-6">
                  <p className="text-[10px] uppercase tracking-widest text-muted-foreground mb-3">Ideal para</p>
                  <div className="flex flex-wrap gap-2">
                    {selected.idealFor.map((t) => (
                      <span key={t} className="px-3 py-1 rounded-full text-xs font-body" style={{ backgroundColor: 'hsl(var(--muted))', color: 'hsl(var(--muted-foreground))', border: '1px solid hsl(var(--border))' }}>
                        {t}
                      </span>
                    ))}
                  </div>
                </div>

                {/* Incluye */}
                <div className="mb-7">
                  <p className="text-[10px] uppercase tracking-widest text-muted-foreground mb-3">Incluye</p>
                  <ul className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    {selected.deliverables.map((d) => (
                      <li key={d} className="flex items-start gap-2.5">
                        <Check size={14} className="mt-0.5 shrink-0" style={{ color: CHARTREUSE }} />
                        <span className="font-body text-sm text-foreground/75">{d}</span>
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="flex items-center gap-2 text-sm text-muted-foreground font-body mb-7">
                  <Clock size={14} style={{ color: CHARTREUSE }} />
                  Producción estimada: {selected.timeline}
                </div>

                {/* CTA */}
                <a
                  href={designWhatsappUrl(selected)}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-heading font-bold text-sm tracking-widest uppercase transition-opacity hover:opacity-90"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  Cotizar "{selected.name}"
                  <ArrowUpRight size={15} />
                </a>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
