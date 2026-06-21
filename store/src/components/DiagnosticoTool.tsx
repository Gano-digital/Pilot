import { useState, useMemo, useCallback } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Link } from 'react-router-dom';
import {
  Globe, Mail, Megaphone, MessagesSquare, Users, Shield, Paintbrush, Compass,
  ArrowRight, ArrowLeft, Check, Loader2, AlertTriangle, Sparkles, RotateCcw,
  type LucideIcon,
} from 'lucide-react';
import {
  DIAGNOSTICO_STEPS,
  computeDiagnostico,
  SEVERITY_LABELS,
  type DiagnosticoAnswers,
  type DiagnosticoResult,
  type Severity,
} from '@/data/diagnostico';

const CHARTREUSE = '#D97E3A';

const ICONS: Record<string, LucideIcon> = {
  globe: Globe,
  mail: Mail,
  megaphone: Megaphone,
  'messages-square': MessagesSquare,
  users: Users,
  shield: Shield,
  paintbrush: Paintbrush,
  compass: Compass,
};

const SEVERITY_STYLE: Record<Severity, { bg: string; text: string; dot: string }> = {
  critico: { bg: 'rgba(229,72,77,0.12)', text: '#E5484D', dot: '#E5484D' },
  atencion: { bg: 'rgba(217,126,58,0.12)', text: CHARTREUSE, dot: CHARTREUSE },
  oportunidad: { bg: 'rgba(155,168,155,0.10)', text: '#9BA89B', dot: '#9BA89B' },
};

type Phase = 'questions' | 'capture' | 'result';

export default function DiagnosticoTool() {
  const [phase, setPhase] = useState<Phase>('questions');
  const [stepIndex, setStepIndex] = useState(0);
  const [answers, setAnswers] = useState<DiagnosticoAnswers>({});
  const [lead, setLead] = useState({ name: '', email: '', company: '', phone: '' });
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [result, setResult] = useState<DiagnosticoResult | null>(null);

  const totalSteps = DIAGNOSTICO_STEPS.length;
  const step = DIAGNOSTICO_STEPS[stepIndex];
  const StepIcon = ICONS[step?.icon] ?? Compass;

  // Preview en vivo (frontend) — el servidor lo recalcula al enviar.
  const livePreview = useMemo(() => computeDiagnostico(answers), [answers]);

  const selected = answers[step?.id] ?? [];
  const canAdvance = selected.length > 0;

  const toggleOption = useCallback(
    (value: string) => {
      setAnswers((prev) => {
        const current = prev[step.id] ?? [];
        if (step.multiSelect) {
          const next = current.includes(value)
            ? current.filter((v) => v !== value)
            : [...current, value];
          return { ...prev, [step.id]: next };
        }
        return { ...prev, [step.id]: [value] };
      });
    },
    [step],
  );

  const goNext = useCallback(() => {
    if (stepIndex < totalSteps - 1) {
      setStepIndex((i) => i + 1);
    } else {
      setPhase('capture');
    }
  }, [stepIndex, totalSteps]);

  const goBack = useCallback(() => {
    if (phase === 'capture') {
      setPhase('questions');
      return;
    }
    if (stepIndex > 0) setStepIndex((i) => i - 1);
  }, [phase, stepIndex]);

  const reset = useCallback(() => {
    setPhase('questions');
    setStepIndex(0);
    setAnswers({});
    setLead({ name: '', email: '', company: '', phone: '' });
    setResult(null);
    setError(null);
  }, []);

  const submit = useCallback(async () => {
    setError(null);
    if (lead.name.trim().length < 2) {
      setError('Cuéntanos tu nombre.');
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(lead.email.trim())) {
      setError('Necesitamos un correo válido para enviarte el informe.');
      return;
    }
    setSubmitting(true);
    try {
      const res = await fetch('/api/diagnostico', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...lead, answers }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data?.error ?? 'No se pudo enviar.');
      setResult(data.result ?? livePreview);
      setPhase('result');
    } catch (e) {
      setError(e instanceof Error ? e.message : 'No se pudo enviar. Inténtalo de nuevo.');
    } finally {
      setSubmitting(false);
    }
  }, [lead, answers, livePreview]);

  const progress =
    phase === 'result'
      ? 100
      : phase === 'capture'
        ? 95
        : Math.round(((stepIndex) / totalSteps) * 90);

  return (
    <div
      className="relative rounded-lg overflow-hidden"
      style={{ backgroundColor: 'hsl(var(--card))', border: '1px solid hsl(var(--border))' }}
    >
      {/* Barra de progreso */}
      <div className="h-1 w-full" style={{ backgroundColor: 'hsl(var(--muted))' }}>
        <motion.div
          className="h-full"
          style={{ backgroundColor: CHARTREUSE }}
          initial={false}
          animate={{ width: `${progress}%` }}
          transition={{ duration: 0.4, ease: 'easeOut' }}
        />
      </div>

      <div className="p-6 md:p-10 min-h-[440px] flex flex-col">
        <AnimatePresence mode="wait">
          {/* ── FASE PREGUNTAS ── */}
          {phase === 'questions' && step && (
            <motion.div
              key={`q-${step.id}`}
              initial={{ opacity: 0, x: 24 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -24 }}
              transition={{ duration: 0.3, ease: 'easeOut' }}
              className="flex flex-col flex-1"
            >
              <div className="flex items-center justify-between mb-6">
                <span className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body" style={{ color: CHARTREUSE }}>
                  <StepIcon size={14} aria-hidden="true" /> {step.area}
                </span>
                <span className="font-mono text-xs text-muted-foreground">
                  {stepIndex + 1} / {totalSteps}
                </span>
              </div>

              <h3 className="font-heading font-bold text-foreground mb-2" style={{ fontSize: 'clamp(22px, 3vw, 32px)', letterSpacing: '-0.02em', lineHeight: 1.15 }}>
                {step.question}
              </h3>
              <p className="font-body text-sm text-muted-foreground mb-6">
                {step.helper}
                {step.multiSelect && <span className="ml-1 text-foreground/50">· Puedes elegir varias</span>}
              </p>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                {step.options.map((opt) => {
                  const isSel = selected.includes(opt.value);
                  return (
                    <button
                      key={opt.value}
                      type="button"
                      onClick={() => toggleOption(opt.value)}
                      className="text-left p-4 rounded-md transition-all duration-200 group"
                      style={{
                        backgroundColor: isSel ? 'rgba(217,126,58,0.10)' : 'hsl(var(--muted))',
                        border: `1px solid ${isSel ? CHARTREUSE : 'hsl(var(--border))'}`,
                      }}
                    >
                      <div className="flex items-start gap-3">
                        <span
                          className="mt-0.5 flex items-center justify-center h-5 w-5 rounded-full shrink-0 transition-colors duration-200"
                          style={{
                            backgroundColor: isSel ? CHARTREUSE : 'transparent',
                            border: `1px solid ${isSel ? CHARTREUSE : 'hsl(var(--border))'}`,
                          }}
                        >
                          {isSel && <Check size={12} strokeWidth={3} className="text-primary-foreground" />}
                        </span>
                        <span>
                          <span className="block font-body text-sm font-medium text-foreground">{opt.label}</span>
                          {opt.hint && <span className="block font-body text-xs text-muted-foreground mt-0.5">{opt.hint}</span>}
                        </span>
                      </div>
                    </button>
                  );
                })}
              </div>

              <div className="mt-auto flex items-center justify-between pt-2">
                <button
                  type="button"
                  onClick={goBack}
                  disabled={stepIndex === 0}
                  className="inline-flex items-center gap-2 text-sm font-body text-muted-foreground transition-colors duration-200 hover:text-foreground disabled:opacity-30 disabled:cursor-not-allowed"
                >
                  <ArrowLeft size={15} /> Atrás
                </button>
                <button
                  type="button"
                  onClick={goNext}
                  disabled={!canAdvance}
                  className="group inline-flex items-center gap-2 h-11 px-6 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-all duration-200 hover:scale-[1.03] disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100"
                >
                  {stepIndex === totalSteps - 1 ? 'Ver mi diagnóstico' : 'Siguiente'}
                  <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
                </button>
              </div>
            </motion.div>
          )}

          {/* ── FASE CAPTURA ── */}
          {phase === 'capture' && (
            <motion.div
              key="capture"
              initial={{ opacity: 0, x: 24 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -24 }}
              transition={{ duration: 0.3, ease: 'easeOut' }}
              className="flex flex-col flex-1"
            >
              <span className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body mb-6" style={{ color: CHARTREUSE }}>
                <Sparkles size={14} /> Último paso
              </span>
              <h3 className="font-heading font-bold text-foreground mb-2" style={{ fontSize: 'clamp(22px, 3vw, 32px)', letterSpacing: '-0.02em', lineHeight: 1.15 }}>
                Tu diagnóstico está listo.
              </h3>
              <p className="font-body text-sm text-muted-foreground mb-7 max-w-md">
                Detectamos <strong className="text-foreground">{livePreview.areasWithNeeds} áreas</strong> con oportunidades.
                Déjanos dónde enviarte el informe completo — y lo revisamos juntos sin compromiso.
              </p>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <Field label="Nombre *" value={lead.name} onChange={(v) => setLead((l) => ({ ...l, name: v }))} placeholder="Tu nombre" />
                <Field label="Empresa" value={lead.company} onChange={(v) => setLead((l) => ({ ...l, company: v }))} placeholder="Nombre de tu negocio" />
                <Field label="Correo *" type="email" value={lead.email} onChange={(v) => setLead((l) => ({ ...l, email: v }))} placeholder="tu@correo.com" />
                <Field label="WhatsApp" value={lead.phone} onChange={(v) => setLead((l) => ({ ...l, phone: v }))} placeholder="+57 ..." />
              </div>

              {error && (
                <p className="flex items-center gap-2 text-sm font-body mb-4" style={{ color: '#E5484D' }}>
                  <AlertTriangle size={15} /> {error}
                </p>
              )}

              <div className="mt-auto flex items-center justify-between pt-2">
                <button
                  type="button"
                  onClick={goBack}
                  className="inline-flex items-center gap-2 text-sm font-body text-muted-foreground transition-colors duration-200 hover:text-foreground"
                >
                  <ArrowLeft size={15} /> Atrás
                </button>
                <button
                  type="button"
                  onClick={submit}
                  disabled={submitting}
                  className="group inline-flex items-center gap-2 h-11 px-6 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-all duration-200 hover:scale-[1.03] disabled:opacity-60 disabled:cursor-wait"
                >
                  {submitting ? <><Loader2 size={15} className="animate-spin" /> Enviando…</> : <>Ver resultados <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" /></>}
                </button>
              </div>
            </motion.div>
          )}

          {/* ── FASE RESULTADO ── */}
          {phase === 'result' && result && (
            <motion.div
              key="result"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4, ease: 'easeOut' }}
              className="flex flex-col flex-1"
            >
              <span className="inline-flex items-center gap-2 text-xs tracking-[0.18em] uppercase font-body mb-5" style={{ color: CHARTREUSE }}>
                <Check size={14} /> Diagnóstico completo · informe enviado a tu correo
              </span>
              <h3 className="font-heading font-bold text-foreground mb-2" style={{ fontSize: 'clamp(22px, 3vw, 34px)', letterSpacing: '-0.02em', lineHeight: 1.12 }}>
                Esto es lo que tu operación necesita.
              </h3>
              <p className="font-body text-sm text-muted-foreground mb-6">
                {result.recommendations.length} recomendaciones priorizadas
                {result.hasCritical && <span style={{ color: '#E5484D' }}> · incluye hallazgos de seguridad urgentes</span>}.
              </p>

              <div className="flex flex-col gap-3 mb-6">
                {result.recommendations.map((rec, i) => {
                  const s = SEVERITY_STYLE[rec.severity];
                  return (
                    <motion.div
                      key={`${rec.serviceId}-${rec.title}-${i}`}
                      initial={{ opacity: 0, y: 12 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ duration: 0.35, delay: i * 0.06 }}
                      className="flex items-start gap-4 p-4 rounded-md"
                      style={{ backgroundColor: 'hsl(var(--muted))', border: '1px solid hsl(var(--border))' }}
                    >
                      <span className="mt-1.5 w-2 h-2 rounded-full shrink-0" style={{ backgroundColor: s.dot }} />
                      <div className="flex-1 min-w-0">
                        <span className="inline-block text-[10px] font-bold uppercase tracking-[0.08em] px-2 py-0.5 rounded mb-1.5" style={{ backgroundColor: s.bg, color: s.text }}>
                          {SEVERITY_LABELS[rec.severity]}
                        </span>
                        <p className="font-heading font-bold text-foreground text-base mb-1" style={{ letterSpacing: '-0.01em' }}>{rec.title}</p>
                        <p className="font-body text-sm text-muted-foreground" style={{ lineHeight: 1.55 }}>{rec.reason}</p>
                      </div>
                      <Link
                        to={rec.href}
                        className="self-center shrink-0 inline-flex items-center gap-1 text-xs font-body font-medium transition-colors duration-200 hover:opacity-80"
                        style={{ color: CHARTREUSE }}
                      >
                        Ver <ArrowRight size={13} />
                      </Link>
                    </motion.div>
                  );
                })}
              </div>

              {result.needsAdvisory && (
                <div className="p-5 rounded-md mb-6" style={{ backgroundColor: 'rgba(217,126,58,0.07)', border: `1px solid ${CHARTREUSE}` }}>
                  <p className="font-body text-sm text-foreground/90" style={{ lineHeight: 1.6 }}>
                    <strong>Tu caso tiene aristas que ninguna plantilla resuelve bien.</strong> Lo
                    más rentable es una conversación con criterio antes de invertir — para eso estoy.
                  </p>
                </div>
              )}

              <div className="mt-auto flex flex-wrap items-center gap-4 pt-2">
                <a
                  href={`https://wa.me/573135646123?text=${encodeURIComponent(`Hola, acabo de hacer el Diagnóstico 360°${lead.company ? ` para ${lead.company}` : ''} y quiero revisarlo contigo.`)}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="group inline-flex items-center gap-2 h-11 px-6 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
                >
                  Revisarlo contigo <ArrowRight size={15} className="transition-transform duration-200 group-hover:translate-x-1" />
                </a>
                <button
                  type="button"
                  onClick={reset}
                  className="inline-flex items-center gap-2 text-sm font-body text-muted-foreground transition-colors duration-200 hover:text-foreground"
                >
                  <RotateCcw size={14} /> Hacerlo de nuevo
                </button>
              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </div>
  );
}

function Field({
  label, value, onChange, placeholder, type = 'text',
}: {
  label: string;
  value: string;
  onChange: (v: string) => void;
  placeholder?: string;
  type?: string;
}) {
  return (
    <label className="block">
      <span className="block font-body text-xs uppercase tracking-[0.12em] text-muted-foreground mb-2">{label}</span>
      <input
        type={type}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        className="w-full h-11 px-4 rounded-md font-body text-sm text-foreground placeholder:text-muted-foreground/50 outline-none transition-colors duration-200 focus:border-primary"
        style={{ backgroundColor: 'hsl(var(--muted))', border: '1px solid hsl(var(--border))' }}
      />
    </label>
  );
}
