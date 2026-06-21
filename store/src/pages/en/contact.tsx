import { useState } from 'react';
import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Send, CheckCircle } from 'lucide-react';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';

/**
 * Contacto en inglés. Traduce labels, validaciones y placeholders. El endpoint
 * /api/contact y los NOMBRES de campo (name, email, projectType, budget,
 * message) y sus VALUES de opción quedan idénticos al español — el backend no
 * cambia. Solo se traduce lo que ve el usuario.
 */

const socialLinks = [
  { label: 'LinkedIn', href: 'https://www.linkedin.com/' },
  { label: 'Instagram', href: 'https://www.instagram.com/' },
];

export default function ContactPageEn() {
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
    const maxLengths: Record<string, number> = { name: 100, email: 254, message: 2000 };
    if (maxLengths[name] && value.length > maxLengths[name]) return;
    setForm((prev) => ({ ...prev, [name]: value }));
    if (errors[name as keyof typeof form]) {
      setErrors((prev) => ({ ...prev, [name]: undefined }));
    }
  };

  const validate = (): boolean => {
    const newErrors: Partial<Record<keyof typeof form, string>> = {};
    const trimmedName = form.name.trim();
    const trimmedMessage = form.message.trim();

    if (!trimmedName || trimmedName.length < 2) {
      newErrors.name = 'Enter your name (at least 2 characters).';
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(form.email.trim())) {
      newErrors.email = 'Enter a valid email address.';
    }
    if (!form.projectType) {
      newErrors.projectType = 'Select a project type.';
    }
    if (!trimmedMessage || trimmedMessage.length < 10) {
      newErrors.message = 'Tell us about your project (at least 10 characters).';
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
        setSubmitError(data.error ?? 'We couldn\u2019t send your message. Please try again.');
        return;
      }
      setSubmitted(true);
    } catch {
      setSubmitError('Network error. Check your connection and try again.');
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
        <title>Contact — Gano Digital</title>
        <meta name="description" content="Let's talk about your project. Hosting, domains, business email, web security and custom infrastructure in Colombia." />
        <link rel="canonical" href="https://gano.digital/en/contact" />
        <meta property="og:title" content="Contact — Gano Digital" />
        <meta property="og:description" content="Let's talk about your digital infrastructure project. I reply in under 24 hours." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://gano.digital/en/contact" />
        <meta property="og:image" content="https://gano.digital/api/og?title=Let%27s+talk&description=Your+digital+infrastructure+project+starts+here.&tag=Contact" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="Contact — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Contact — Gano Digital" />
        <meta name="twitter:description" content="Let's talk about your digital infrastructure project. I reply in under 24 hours." />
        <meta name="twitter:image" content="https://gano.digital/api/og?title=Let%27s+talk&description=Your+digital+infrastructure+project+starts+here.&tag=Contact" />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'ContactPage',
          '@id': 'https://gano.digital/en/contact#webpage',
          name: 'Contact — Gano Digital',
          url: 'https://gano.digital/en/contact',
          inLanguage: 'en',
          isPartOf: { '@id': 'https://gano.digital/#website' },
          about: { '@id': 'https://gano.digital/#organization' },
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/contact" locale="en" />

      <section className="min-h-screen pt-20" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <div className="grid grid-cols-1 md:grid-cols-2 min-h-screen">

          {/* Left panel */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="flex flex-col justify-center px-8 md:px-16 py-20 md:py-28"
            style={{ backgroundColor: 'hsl(var(--background))' }}
          >
            <p className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-8">
              Let's talk
            </p>
            <h1
              className="font-heading font-bold text-foreground mb-8"
              style={{ fontSize: 'clamp(36px, 5vw, 64px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
            >
              Let's build your<br />infrastructure.
            </h1>

            <div className="flex items-center gap-2 mb-8">
              <span
                className="w-2 h-2 rounded-full animate-pulse"
                style={{ backgroundColor: CHARTREUSE }}
              />
              <span className="text-sm font-body text-foreground/70">Available for new projects</span>
            </div>

            <div className="flex flex-col gap-5 mb-12">
              <div>
                <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-1">Email</p>
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
                  href="https://wa.me/573135646123?text=Hi+Gano+Digital,+I+have+a+question"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="font-body text-foreground/80 hover:text-primary transition-colors duration-200 text-base"
                  style={{ color: CHARTREUSE }}
                >
                  +57 313 564 6123
                </a>
              </div>
              <div>
                <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-1">Response time</p>
                <p className="font-body text-foreground/70 text-sm">I reply in under 24 hours.</p>
              </div>
            </div>

            <div>
              <p className="text-xs tracking-widest uppercase font-body text-muted-foreground mb-4">Follow</p>
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

          {/* Right panel */}
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
                  Message sent.
                </h2>
                <p className="font-body text-base" style={{ color: 'hsl(var(--muted-foreground))', lineHeight: 1.7 }}>
                  Thanks for reaching out. I'll get back to you within the next 24 hours.
                </p>
              </motion.div>
            ) : (
              <form onSubmit={handleSubmit} className="flex flex-col gap-5">
                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Name
                  </label>
                  <input
                    type="text"
                    name="name"
                    value={form.name}
                    onChange={handleChange}
                    required
                    maxLength={100}
                    autoComplete="name"
                    placeholder="Your name"
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
                    Project type
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
                    <option value="" disabled>Select an option</option>
                    <option value="hosting">WordPress hosting</option>
                    <option value="dominio">Domain and business email</option>
                    <option value="seguridad">Security, SSL and backups</option>
                    <option value="vps">VPS or custom server</option>
                    <option value="desarrollo">Custom web development</option>
                    <option value="diagnostico">Infrastructure diagnosis</option>
                    <option value="migracion">Migration from another provider</option>
                    <option value="otro">Another question</option>
                  </select>
                  {errors.projectType && <p id="type-error" className="mt-1 text-xs" style={{ color: '#ef4444' }}>{errors.projectType}</p>}
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Estimated budget
                  </label>
                  <select
                    name="budget"
                    value={form.budget}
                    onChange={handleChange}
                    className={`w-full px-4 py-3 rounded-xl ${focusStyle} appearance-none`}
                    style={inputStyle}
                  >
                    <option value="" disabled>Select a range</option>
                    <option value="menos50k">Under $50,000 / mo</option>
                    <option value="50k150k">$50,000 – $150,000 / mo</option>
                    <option value="150k500k">$150,000 – $500,000 / mo</option>
                    <option value="proyecto">Custom project (one-time)</option>
                    <option value="nose">Not sure yet</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs tracking-widest uppercase font-body mb-2" style={{ color: 'hsl(var(--muted-foreground))' }}>
                    Message
                  </label>
                  <textarea
                    name="message"
                    value={form.message}
                    onChange={handleChange}
                    required
                    maxLength={2000}
                    rows={5}
                    placeholder="Tell us about your project..."
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
                    <span className="animate-pulse">Sending...</span>
                  ) : (
                    <>
                      Send message <Send size={14} />
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
