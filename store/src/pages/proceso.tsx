import { useState } from 'react';
import { Link } from 'react-router-dom';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion, AnimatePresence } from 'motion/react';
import { ArrowRight, ChevronDown, Lock, ShieldCheck, ExternalLink, MessageCircle } from 'lucide-react';
import HowItWorks from '@/components/narrative/HowItWorks';

const CHARTREUSE = '#D97E3A';
const SITE = 'https://gano.digital';
const WHATSAPP = 'https://wa.me/573135646123?text=Hola+Gano+Digital,+tengo+una+duda+sobre+el+proceso+de+contrataci%C3%B3n';

const FAQ = [
  {
    q: '¿Por qué el pago se hace en otra página?',
    a: 'Porque el cobro lo procesa nuestro proveedor de infraestructura autorizado en su propia pasarela cifrada (secureserver.net). Es una plataforma global, auditada y certificada para manejar pagos. Nosotros no la modificamos ni almacenamos tus datos de tarjeta: esa separación es precisamente lo que protege tu dinero.',
  },
  {
    q: '¿Es seguro poner ahí mi tarjeta?',
    a: 'Sí. La pasarela usa cifrado de extremo a extremo (TLS) y cumple estándares internacionales de seguridad de pagos. Verás el candado de conexión segura en tu navegador y el dominio secureserver.net, que pertenece a una de las mayores empresas de hosting del mundo.',
  },
  {
    q: '¿Gano Digital ve los datos de mi tarjeta?',
    a: 'No. Tus datos de pago viajan directamente del navegador a la pasarela. Nunca pasan por nuestros servidores ni quedan guardados en este sitio. Solo recibimos la confirmación de que tu servicio fue contratado para empezar a configurarlo.',
  },
  {
    q: '¿Qué pasa después de pagar?',
    a: 'Recibes el comprobante de la pasarela y, en paralelo, un ingeniero de Gano Digital deja tu servicio operativo: DNS, SSL, correo y —si vienes de otro proveedor— la migración de tu sitio sin tiempo fuera de línea. Te avisamos en cada paso.',
  },
  {
    q: '¿Puedo hablar con alguien antes de pagar?',
    a: 'Siempre. Escríbenos por WhatsApp o por el formulario de contacto y un ingeniero te ayuda a elegir el plan correcto, sin compromiso. Preferimos que contrates con certeza, no a ciegas.',
  },
  {
    q: '¿Y si me equivoco de plan?',
    a: 'No pasa nada: los planes se pueden ajustar o cambiar de categoría. Si tienes dudas antes de contratar, te recomendamos el Diagnóstico de Soberanía o una asesoría rápida por WhatsApp para acertar a la primera.',
  },
];

export default function ProcesoPage() {
  const canonicalUrl = `${SITE}/proceso`;
  const ogImage = `${SITE}/api/og?title=C%C3%B3mo+contratas&description=Transparencia+total%3A+los+4+pasos+y+las+garant%C3%ADas+de+pago.&tag=Proceso`;
  const [open, setOpen] = useState<number | null>(0);

  const faqJsonLd = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    '@id': `${canonicalUrl}#faq`,
    isPartOf: { '@id': `${SITE}/#website` },
    about: { '@id': `${SITE}/#organization` },
    mainEntity: FAQ.map((f) => ({
      '@type': 'Question',
      name: f.q,
      acceptedAnswer: { '@type': 'Answer', text: f.a },
    })),
  };

  return (
    <>
      <Helmet>
        <title>Cómo contratas — Proceso y seguridad de pago — Gano Digital</title>
        <meta name="description" content="Contratar en Gano Digital es de cuatro pasos transparentes. Te explicamos por qué el pago ocurre en una pasarela externa cifrada y cómo protegemos tu dinero." />
        <link rel="canonical" href={canonicalUrl} />
        <meta property="og:title" content="Cómo contratas — Proceso y seguridad de pago" />
        <meta property="og:description" content="Los 4 pasos para contratar y por qué el checkout externo es una garantía, no una fricción." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={canonicalUrl} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Cómo contratas en Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Cómo contratas — Proceso y seguridad de pago" />
        <meta name="twitter:description" content="Los 4 pasos para contratar y por qué el checkout externo es una garantía." />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify(faqJsonLd)}</script>
      </Helmet>

      {/* HERO */}
      <section className="pt-36 pb-16 md:pt-44 md:pb-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
          >
            <div className="w-12 h-px mb-8 bg-primary" />
            <p className="text-xs tracking-[0.25em] uppercase font-body text-muted-foreground mb-6">
              Transparencia total
            </p>
            <h1
              className="font-heading font-bold text-foreground mb-6"
              style={{ fontSize: 'clamp(44px, 7.5vw, 88px)', letterSpacing: '-0.03em', lineHeight: 1.02 }}
            >
              Cómo contratas,<br />sin sorpresas.
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body max-w-2xl"
            style={{ lineHeight: 1.7 }}
          >
            Eliges aquí. Pagas en una pasarela blindada que no manipulamos. Nosotros configuramos
            todo. Te contamos cada paso para que sepas exactamente qué pasa con tu dinero y tu sitio.
          </motion.p>
        </div>
      </section>

      {/* PASOS */}
      <section className="pb-20 md:pb-28" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <HowItWorks compact showLink={false} />
        </div>
      </section>

      {/* EXPLICACIÓN DEL CHECKOUT EXTERNO */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
              <motion.h2
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5 }}
                className="font-heading font-bold text-foreground mb-5"
                style={{ fontSize: 'clamp(28px, 4vw, 48px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
              >
                El checkout externo es una garantía, no una fricción.
              </motion.h2>
              <motion.p
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.1 }}
                className="font-body text-muted-foreground mb-5"
                style={{ lineHeight: 1.7 }}
              >
                Cuando llega el momento de pagar, te llevamos al checkout de{' '}
                <span className="text-foreground font-semibold">secureserver.net</span>, la pasarela
                de nuestro proveedor de infraestructura autorizado. Es una página que no podemos
                editar — y eso es bueno: significa que tu pago pasa por un entorno auditado,
                certificado y respaldado por una de las plataformas de hosting más grandes del mundo.
              </motion.p>
              <motion.p
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: 0.15 }}
                className="font-body text-muted-foreground"
                style={{ lineHeight: 1.7 }}
              >
                Verás cambiar el dominio en tu navegador. Es normal y esperado. Tus datos de tarjeta
                nunca tocan los servidores de Gano Digital.
              </motion.p>
            </div>

            <motion.div
              initial={{ opacity: 0, scale: 0.97 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, ease: 'easeOut' }}
              className="rounded-xl border border-border bg-card p-6"
            >
              {/* Mock de barra de navegador segura */}
              <div className="flex items-center gap-2 rounded-xl bg-muted px-3 py-2.5 mb-5">
                <Lock size={15} style={{ color: CHARTREUSE }} className="shrink-0" aria-hidden="true" />
                <span className="font-body text-sm text-card-foreground/80 truncate">
                  https://<span className="text-foreground font-semibold">secureserver.net</span>/checkout
                </span>
                <ExternalLink size={14} className="text-muted-foreground ml-auto shrink-0" aria-hidden="true" />
              </div>
              <ul className="flex flex-col gap-3">
                {[
                  'Conexión cifrada TLS de extremo a extremo',
                  'Cumple estándares internacionales de pago',
                  'Tus datos no pasan por este sitio',
                  'Respaldo de una plataforma global de hosting',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2.5 font-body text-sm text-card-foreground/80">
                    <ShieldCheck size={16} className="text-primary shrink-0 mt-0.5" strokeWidth={2.25} aria-hidden="true" />
                    {item}
                  </li>
                ))}
              </ul>
            </motion.div>
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-3xl mx-auto px-6 md:px-10">
          <motion.h2
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="font-heading font-bold text-foreground mb-12"
            style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Preguntas sobre el pago
          </motion.h2>

          <div className="flex flex-col">
            {FAQ.map((item, i) => {
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
                        <p className="font-body text-muted-foreground leading-relaxed pb-6 max-w-2xl">
                          {item.a}
                        </p>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="font-heading font-bold text-foreground mb-5"
            style={{ fontSize: 'clamp(32px, 5vw, 64px)', letterSpacing: '-0.03em' }}
          >
            Ya sabes cómo funciona. Da el paso.
          </motion.h2>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.15 }}
            className="text-lg text-muted-foreground font-body mb-10 max-w-xl mx-auto"
          >
            Elige tu plan con total claridad — o escríbenos y lo decidimos juntos.
          </motion.p>
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.25 }}
            className="flex flex-wrap items-center justify-center gap-4"
          >
            <Link
              to="/catalogo"
              className="group inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
            >
              Ver planes <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </Link>
            <a
              href={WHATSAPP}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              <MessageCircle size={16} /> Hablar con un ingeniero
            </a>
          </motion.div>
        </div>
      </section>
    </>
  );
}
