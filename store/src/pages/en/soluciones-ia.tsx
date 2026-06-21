import { Helmet } from '@dr.pogodin/react-helmet';
import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import {
  ArrowRight, Bot, Workflow, MessagesSquare, TrendingUp,
  Headphones, Megaphone, FileSearch, Clock, Sparkles, MessageCircle,
  UserCheck, HeartHandshake, ShieldCheck,
} from 'lucide-react';
import TechStack from '@/components/TechStack';
import { LocaleSeo } from '@/components/LocaleSeo';

const CHARTREUSE = '#D97E3A';
const WHATSAPP = 'https://wa.me/573135646123?text=I+want+to+explore+AI+solutions+and+agentic+employees+for+my+company';

const site = 'https://gano.digital';
const title = 'AI Solutions & Agentic Employees — Gano Digital';
const description =
  'I install artificial intelligence that works for you: agents that serve, automate and sell. Custom solutions in code, GoHighLevel and APIs — for companies with real needs.';
const ogImage = `${site}/api/og?title=AI+that+works+for+you&description=Agents+that+serve%2C+automate+and+sell+for+your+company.&tag=AI+Solutions`;

const capabilities = [
  {
    icon: Headphones,
    title: 'Support that never sleeps',
    desc: 'An agent that answers your customers on WhatsApp, web and social at any hour — in your brand\u2019s voice and with access to your real information, not generic replies.',
  },
  {
    icon: Workflow,
    title: 'Processes that run themselves',
    desc: 'Quotes, follow-ups, scheduling, invoicing, reporting. The repetitive stops stealing your hours and runs in the background, with no human error.',
  },
  {
    icon: Megaphone,
    title: 'Marketing that operates 24/7',
    desc: 'Qualifies leads, nurtures prospects and moves campaigns across your channels and funnels. Real integration with GoHighLevel and the APIs of the platforms you already use.',
  },
  {
    icon: FileSearch,
    title: 'Knowledge on demand',
    desc: 'Your manuals, catalogs and data turned into an assistant your team queries for exact answers — without digging through folders or waiting on anyone.',
  },
];

const outcomes = [
  {
    icon: TrendingUp,
    metric: 'Your team, multiplied',
    desc: 'Automating the repetitive gives your people back the hours that drain away on exhausting tasks. AI doesn\u2019t replace your team: it frees them for the work that truly needs human judgment.',
  },
  {
    icon: Clock,
    metric: 'More capacity, same team',
    desc: 'Serve far more customers without overloading anyone. Your operation grows while your people work better, not harder.',
  },
  {
    icon: MessagesSquare,
    metric: 'Zero customers left waiting',
    desc: 'Every message gets immediate attention, and the cases that matter reach a real person. The speed of the machine, the warmth of a human.',
  },
];

const markets = [
  { sector: 'Retail & e-commerce', use: 'A sales agent that advises, recommends and closes; cart recovery; automated post-sale support.' },
  { sector: 'Professional services', use: 'Smart scheduling, client intake, case follow-up and answers to frequent questions with judgment.' },
  { sector: 'Health & wellness', use: 'Reminders, appointment confirmation, initial triage and handling of basic queries that unclog the front desk.' },
  { sector: 'Education & training', use: 'Virtual tutors, student support, assisted grading and automated onboarding of new enrollees.' },
];

export default function SolucionesIaPageEn() {
  return (
    <>
      <Helmet>
        <title>{title}</title>
        <meta name="description" content={description} />
        <link rel="canonical" href={`${site}/en/soluciones-ia`} />
        <meta property="og:title" content={title} />
        <meta property="og:description" content={description} />
        <meta property="og:type" content="website" />
        <meta property="og:url" content={`${site}/en/soluciones-ia`} />
        <meta property="og:image" content={ogImage} />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:alt" content="AI Solutions — Gano Digital" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={title} />
        <meta name="twitter:description" content={description} />
        <meta name="twitter:image" content={ogImage} />
        <script type="application/ld+json">{JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Service',
          '@id': `${site}/en/soluciones-ia#service`,
          name: 'AI Solutions & Agentic Employees',
          serviceType: 'Applied artificial intelligence for businesses',
          url: `${site}/en/soluciones-ia`,
          inLanguage: 'en',
          areaServed: 'CO',
          provider: { '@id': `${site}/#organization` },
          description,
        })}</script>
      </Helmet>
      <LocaleSeo canonicalEsPath="/soluciones-ia" locale="en" />

      {/* ── HERO ── */}
      <section className="relative overflow-hidden pt-36 pb-24 md:pt-44 md:pb-32" style={{ backgroundColor: 'hsl(var(--background))' }}>
        <motion.div
          aria-hidden="true"
          className="absolute -top-1/4 -right-1/4 w-[60vw] h-[60vw] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.12) 0%, transparent 65%)' }}
          animate={{ scale: [1, 1.08, 1], opacity: [0.6, 1, 0.6] }}
          transition={{ duration: 7, repeat: Infinity, ease: 'easeInOut' }}
        />
        <div className="relative max-w-7xl mx-auto px-6 md:px-10">
          <motion.span
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5 }}
            className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body text-foreground/90 border border-border rounded-full px-3 py-1.5 mb-8"
          >
            <Sparkles size={12} style={{ color: CHARTREUSE }} />
            Applied artificial intelligence
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
              AI that works with your team.{' '}
              <span style={{ color: CHARTREUSE }}>Not instead of it.</span>
            </h1>
          </motion.div>
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2, ease: 'easeOut' }}
            className="font-body text-lg md:text-xl text-muted-foreground max-w-2xl mb-10"
            style={{ lineHeight: 1.7 }}
          >
            AI stopped being a promise. Today I install intelligent agents that serve your
            customers, automate the repetitive and move your marketing — so your people get
            their time back and focus on what only a human does well. I'm not selling you a
            license or a replacement: I deliver an augmented team, always with people in command.
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
              Explore my case <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
            </a>
            <Link
              to="/en/catalogo"
              className="inline-flex items-center gap-2 h-12 px-7 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] border border-border text-foreground transition-colors duration-200 hover:border-primary hover:text-primary"
            >
              See the catalog
            </Link>
          </motion.div>
        </div>
      </section>

      {/* ── WHAT AN AGENTIC EMPLOYEE DOES ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              What it is, no hype
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              An agentic employee is software that understands, decides and acts.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              It's not a chatbot of canned answers. It's a system that knows your business,
              accesses your real data and runs complete tasks — start to finish.
            </p>
          </div>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            {capabilities.map((c) => {
              const Icon = c.icon;
              return (
                <motion.div
                  key={c.title}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  whileHover={{ y: -6 }}
                  className="flex gap-5 p-7 rounded-xl transition-colors duration-300"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <span className="flex items-center justify-center h-12 w-12 rounded-xl shrink-0" style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}>
                    <Icon size={22} strokeWidth={1.75} aria-hidden="true" />
                  </span>
                  <div>
                    <h3 className="font-heading font-bold text-foreground text-xl mb-2" style={{ letterSpacing: '-0.01em' }}>{c.title}</h3>
                    <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{c.desc}</p>
                  </div>
                </motion.div>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* ── THE INVESTMENT CASE ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              Why it's an investment, not an expense
            </p>
            <h2 className="font-heading font-bold text-foreground" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              The return isn't theoretical. It's measured in hours given back to your people.
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {outcomes.map((o, i) => {
              const Icon = o.icon;
              return (
                <motion.div
                  key={o.metric}
                  initial={{ opacity: 0, y: 24 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: i * 0.1, ease: 'easeOut' as const }}
                  className="p-8 rounded-xl"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <Icon size={28} strokeWidth={1.75} style={{ color: CHARTREUSE }} className="mb-5" aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg mb-3" style={{ letterSpacing: '-0.01em' }}>{o.metric}</h3>
                  <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{o.desc}</p>
                </motion.div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── CASES BY MARKET ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-7xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              For your market
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              Every business has its bottleneck. I find it and automate it.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              These are starting points, not closed templates. Your solution is designed
              after I understand how your operation works from the inside.
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            {markets.map((m, i) => (
              <motion.div
                key={m.sector}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: i * 0.08, ease: 'easeOut' as const }}
                className="p-7 rounded-xl"
                style={{ backgroundColor: 'hsl(var(--card))', borderLeft: `3px solid ${CHARTREUSE}` }}
              >
                <div className="flex items-center gap-3 mb-3">
                  <Bot size={18} style={{ color: CHARTREUSE }} aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg" style={{ letterSpacing: '-0.01em' }}>{m.sector}</h3>
                </div>
                <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{m.use}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ── AI WITH HUMANS IN COMMAND ── */}
      <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-5xl mx-auto px-6 md:px-10">
          <div className="max-w-3xl mb-14">
            <p className="text-xs tracking-[0.2em] uppercase font-body mb-4" style={{ color: CHARTREUSE }}>
              How we understand AI
            </p>
            <h2 className="font-heading font-bold text-foreground mb-5" style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}>
              The machine performs best when a human guides it.
            </h2>
            <p className="font-body text-lg text-muted-foreground" style={{ lineHeight: 1.7 }}>
              I don't believe in automating people to “cut costs.” I believe in increasing their
              capacity. It's a reasoned stance backed by economists and international ethical
              frameworks — and it guides every solution I deliver.
            </p>
          </div>

          <motion.div
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true }}
            variants={{ hidden: {}, visible: { transition: { staggerChildren: 0.1 } } }}
            className="grid grid-cols-1 md:grid-cols-3 gap-6"
          >
            {[
              { icon: UserCheck, title: 'Human oversight', desc: 'Every critical flow has a human review point. AI proposes and executes; the person decides and answers.' },
              { icon: HeartHandshake, title: 'Closeness intact', desc: 'We automate the repetitive, not the relationship. When a customer needs a human, they find one.' },
              { icon: ShieldCheck, title: 'Augment, not cut', desc: 'We free your team from the tedium so they do the work that needs judgment. More capacity, not fewer people.' },
            ].map((item) => {
              const Icon = item.icon;
              return (
                <motion.div
                  key={item.title}
                  variants={{ hidden: { opacity: 0, y: 24 }, visible: { opacity: 1, y: 0, transition: { duration: 0.5, ease: 'easeOut' as const } } }}
                  className="p-7 rounded-xl"
                  style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
                >
                  <Icon size={26} strokeWidth={1.75} style={{ color: CHARTREUSE }} className="mb-4" aria-hidden="true" />
                  <h3 className="font-heading font-bold text-foreground text-lg mb-2" style={{ letterSpacing: '-0.01em' }}>{item.title}</h3>
                  <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.65 }}>{item.desc}</p>
                </motion.div>
              );
            })}
          </motion.div>
        </div>
      </section>

      {/* ── TECH STACK ── */}
      <TechStack
        kicker="What I build it with"
        title="Real solutions, not trade-show demos."
        subtitle="Custom code on Node.js, integration with GoHighLevel and the APIs of the networks your business already uses, plus frontier AI models. The same toolbox I use to build and operate systems in production."
      />

      {/* ── FINAL CTA ── */}
      <section className="py-24 md:py-32" style={{ backgroundColor: 'hsl(var(--muted))', borderTop: '1px solid hsl(var(--border))' }}>
        <div className="max-w-4xl mx-auto px-6 md:px-10 text-center">
          <motion.h2
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' as const }}
            className="font-heading font-bold text-foreground mb-6"
            style={{ fontSize: 'clamp(28px, 4.5vw, 56px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
          >
            Which task in your company should already be running on its own?
          </motion.h2>
          <motion.p
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="font-body text-lg text-muted-foreground mb-10 max-w-2xl mx-auto"
            style={{ lineHeight: 1.7 }}
          >
            Let's talk, no strings attached. I listen, understand your operation and tell you
            frankly where AI truly adds value — and where it's not worth it yet.
          </motion.p>
          <a
            href={WHATSAPP}
            target="_blank"
            rel="noopener noreferrer"
            className="group inline-flex items-center gap-2 h-13 px-8 py-4 rounded-xl font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
          >
            <MessageCircle size={16} /> Let's talk through your case
            <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
          </a>
        </div>
      </section>
    </>
  );
}
