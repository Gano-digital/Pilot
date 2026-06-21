import { useState } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Send, CheckCircle } from 'lucide-react';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';

const socialLinks = [
  { label: 'LinkedIn', href: 'https://www.linkedin.com/' },
  { label: 'Instagram', href: 'https://www.instagram.com/' },
];

export default function ContactPage() {
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [errors, setErrors] = useState<Partial<Record<keyof typeof form, string>>>({});
  const [form, setForm] = useState({
    name: '',
    email: '',
    projectType: '',
    budget: '',
    message: '',
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    // Enforce max lengths at input time
    const maxLengths: Record<string, number> = { name: 100, email: 254, message: 2000 };
    if (maxLengths[name] && value.length > maxLengths[name]) return;
    setForm((prev) => ({ ...prev, [name]: value }));
    // Clear error on change
    if (errors[name as keyof typeof form]) {
      setErrors((prev) => ({ ...prev, [name]: undefined }));
    }
  };

  const validate = (): boolean => {
    const newErrors: Partial<Record<keyof typeof form, string>> = {};
    const trimmedName = form.name.trim();
    const trimmedMessage = form.message.trim();

    if (!trimmedName || trimmedName.length < 2) {
      newErrors.name = 'Escribe tu nombre (al menos 2 caracteres).';
    }
    // RFC 5321 basic email check
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(form.email.trim())) {
      newErrors.email = 'Escribe un correo electrónico válido.';
    }
    if (!form.projectType) {
      newErrors.projectType = 'Selecciona un tipo de proyecto.';
    }
    if (!trimmedMessage || trimmedMessage.length < 10) {
      newErrors.message = 'Cuéntanos sobre tu proyecto (al menos 10 caracteres).';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!validate()) return;
    setLoading(true);
    setSubmitError(null);
    try {
      const res = await fetch('/api/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form),
      });
      const data = await res.json() as { ok?: boolean; error?: string };
      if (!res.ok || !data.ok) {
        setSubmitError(data.error ?? 'No se pudo enviar el mensaje. Inténtalo de nuevo.');
        return;
      }
      setSubmitted(true);
    } catch {
      setSubmitError('Error de red. Verifica tu conexión e inténtalo de nuevo.');
    } finally {
      setLoading(false);
    }
  };

  const inputStyle = {
    backgroundColor: 'hsl(var(--card))',
    border: '1px solid hsl(var(--border))',
    color: 'hsl(var(--card-foreground))',
    fontFamily: 'inherit',
    fontSize: '16px',
    lineHeight: '1.5',
    outline: 'none',
    transition: 'border-color 0.2s',
  };

  const focusStyle = `focus:outline-none focus:ring-2 focus:ring-[#D97E3A] focus:border-transparent`;

  return (
    <>
      <Helmet>
        <title>Contacto — Gano Digital</title>
        <meta name="description" content="Hablemos de tu proyecto. Hosting, dominios, correo profesional, seguridad web e infraestructura a medida en Colombia." />
        <link rel="canonical" href="https://gano.digital/contact" />
        <meta property="og:title" content="Contacto — Gano Digital" />
        <meta property="og:description" content="Hablemos de tu proyecto de infraestructura digital. Respondemos en menos de 24 horas." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/contact" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Hablemos&description=Tu+proyecto+de+infraestructura+digital+empieza+aqu%C3%AD.&tag=Contacto" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Contacto — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Contacto — Gano Digital" />
        <meta name="twitter:description" content="Hablemos de tu proyecto de infraestructura digital. Respondemos en menos de 24 horas." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Hablemos&description=Tu+proyecto+de+infraestructura+digital+empieza+aqu%C3%AD.&tag=Contacto" />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ContactPage',
          '@id': 'https://gano.digital/contact#webpage',
          name: 'Contacto — Gano Digital',
          url: 'https://gano.digital/contact',
          isPartOf: { '@id': 'https://gano.digital/#website' },
          about: { '@id': 'https://gano.digital/#organization' },
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/contact" locale="es" />

      <section className="min-h-screen pt-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="grid grid-cols-1 md:grid-cols-2 min-h-screen">

          {/* Left panel — dark */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="flex flex-col justify-center px-8 md:px-16 py-20 md:py-28"
            style={{ backgroundColor: 'hsl(var(--background))' }}
          >
            <p className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-8">
              Hablemos
            </p>
            <h1
              className="font-heading font-bold text-foreground mb-8"
              style={{ fontSize: 'clamp(36px, 5vw, 64px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
            >
              Construyamos tu<br />infraestructura.
            </h1>

            <div className="flex items-center gap-2 mb-8">
              <span
                className="w-2 h-2 rounded-full animate-pulse"
                style={{ backgroundColor: CHARTREUSE }}
              />
              <span className="text-sm font-body text-foreground/70">Disponibles para nuevos proyectos</span>
            </div>

            <div className="flex flex-col gap-5 mb-12">
              <div>
                <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-1">Correo</p>
                <a
                  href="mailto:pymes@gano.digital"
                  className="font-body text-foreground/80 hover:text-primary transition-colors duration-200 text-base"
                  style={{ color: CHARTREUSE }}
                >
                  pymes@gano.digital
                </a>
              </div>
              <div>
                <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-1">WhatsApp</p>
                <a
                  href="https://wa.me/573135646123?text=Hola+Gano+Digital,+tengo+una+consulta"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="font-body text-foreground/80 hover:text-primary transition-colors duration-200 text-base"
                  style={{ color: CHARTREUSE }}
                >
                  +57 313 564 6123
                </a>
              </div>
              <div>
                <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-1">Tiempo de respuesta</p>
                <p className="font-body text-foreground/70 text-sm">Respondemos en menos de 24 horas.</p>
              </div>
            </div>

            <div>
              <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-4">Síguenos</p>
              <div className="flex gap-5">
                {socialLinks.map((link) => (
                  <a
                    key={link.label}
                    href={link.href}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-sm font-body text-foreground/50 hover:text-primary transition-colors duration-200"
                    style={{ '--hover-color': CHARTREUSE } as React.CSSProperties}
                  >
                    {link.label}
                  </a>
                ))}
              </div>
            </div>
          </motion.div>

          {/* Right panel — off-white */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.6, delay: 0.1, ease: 'easeOut' }}
            className="flex flex-col justify-center px-8 md:px-16 py-20 md:py-28"
            style={{ backgroundColor: 'hsl(var(--secondary))' }}
          >
            {submitted ? (
              <motion.div
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.4 }}
                className="text-center"
              >
                <CheckCircle size={48} className="mx-auto mb-6" style={{ color: CHARTREUSE }} />
                <h2 className="font-heading font-bold text-2xl mb-3" style={{ color: 'hsl(var(--secondary-foreground))', letterSpacing: '-0.02em' }}>
                  Mensaje enviado.
                </h2>
                <p className="font-body text-base" style={{ color: 'hsl(var(--muted-foreground))', lineHeight: 1.7 }}>
                  Gracias por escribirnos. Te responderemos dentro de las próximas 24 horas.
                </p>
              </motion.div>
            ) : (
              <form onSubmit={handleSubmit} className="flex flex-col gap-5">
                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Nombre
                  </label>
                  <input
                    type="text"
                    name="name"
                    value={form.name}
                    onChange={handleChange}
                    required
                    maxLength={100}
                    autoComplete="name"
                    placeholder="Tu nombre"
                    className={`w-full px-4 py-3 rounded-xl ${focusStyle}`}
                    style={{ ...inputStyle, borderColor: errors.name ? '#ef4444' : 'hsl(var(--border))' }}
                    aria-describedby={errors.name ? 'name-error' : undefined}
                  />
                  {errors.name && <p id="name-error" className="mt-1 text-xs" style={{ color: '#ef4444' }}>{errors.name}</p>}
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Email
                  </label>
                  <input
                    type="email"
                    name="email"
                    value={form.email}
                    onChange={handleChange}
                    required
                    maxLength={254}
                    autoComplete="email"
                    placeholder="your@email.com"
                    className={`w-full px-4 py-3 rounded-xl ${focusStyle}`}
                    style={{ ...inputStyle, borderColor: errors.email ? '#ef4444' : 'hsl(var(--border))' }}
                    aria-describedby={errors.email ? 'email-error' : undefined}
                  />
                  {errors.email && <p id="email-error" className="mt-1 text-xs" style={{ color: '#ef4444' }}>{errors.email}</p>}
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Tipo de proyecto
                  </label>
                  <select
                    name="projectType"
                    value={form.projectType}
                    onChange={handleChange}
                    required
                    className={`w-full px-4 py-3 rounded-xl ${focusStyle} appearance-none`}
                    style={{ ...inputStyle, borderColor: errors.projectType ? '#ef4444' : 'hsl(var(--border))' }}
                    aria-describedby={errors.projectType ? 'type-error' : undefined}
                  >
                    <option value="" disabled>Selecciona una opción</option>
                    <option value="hosting">Hosting WordPress</option>
                    <option value="dominio">Dominio y correo profesional</option>
                    <option value="seguridad">Seguridad, SSL y backups</option>
                    <option value="vps">VPS o servidor a medida</option>
                    <option value="desarrollo">Desarrollo web a medida</option>
                    <option value="diagnostico">Diagnóstico de infraestructura</option>
                    <option value="migracion">Migración desde otro proveedor</option>
                    <option value="otro">Otra consulta</option>
                  </select>
                  {errors.projectType && <p id="type-error" className="mt-1 text-xs" style={{ color: '#ef4444' }}>{errors.projectType}</p>}
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Presupuesto estimado
                  </label>
                  <select
                    name="budget"
                    value={form.budget}
                    onChange={handleChange}
                    className={`w-full px-4 py-3 rounded-xl ${focusStyle} appearance-none`}
                    style={inputStyle}
                  >
                    <option value="" disabled>Selecciona un rango</option>
                    <option value="menos50k">Menos de $50.000 / mes</option>
                    <option value="50k150k">$50.000 – $150.000 / mes</option>
                    <option value="150k500k">$150.000 – $500.000 / mes</option>
                    <option value="proyecto">Proyecto a medida (pago único)</option>
                    <option value="nose">Aún no lo sé</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Mensaje
                  </label>
                  <textarea
                    name="message"
                    value={form.message}
                    onChange={handleChange}
                    required
                    maxLength={2000}
                    rows={5}
                    placeholder="Cuéntanos sobre tu proyecto..."
                    className={`w-full px-4 py-3 rounded-xl resize-none ${focusStyle}`}
                    style={{ ...inputStyle, borderColor: errors.message ? '#ef4444' : 'hsl(var(--border))' }}
                    aria-describedby={errors.message ? 'message-error' : undefined}
                  />
                  <div className="flex justify-between mt-1">
                    {errors.message
                      ? <p id="message-error" className="text-xs" style={{ color: '#ef4444' }}>{errors.message}</p>
                      : <span />
                    }
                    <span className="text-xs text-muted-foreground font-body">{form.message.length}/2000</span>
                  </div>
                </div>

                <button
                  type="submit"
                  disabled={loading}
                  className="w-full py-4 flex items-center justify-center gap-3 font-heading font-bold text-sm tracking-widest uppercase transition-opacity duration-200 disabled:opacity-60 mt-2"
                  style={{ backgroundColor: CHARTREUSE, color: '#0A0A0A' }}
                >
                  {loading ? (
                    <span className="animate-pulse">Enviando...</span>
                  ) : (
                    <>
                      Enviar mensaje <Send size={14} />
                    </>
                  )}
                </button>

                {submitError && (
                  <p className="text-sm text-center font-body mt-2" style={{ color: '#ef4444' }}>
                    {submitError}
                  </p>
                )}
              </form>
            )}
          </motion.div>
        </div>
      </section>
    </>
  );
}
