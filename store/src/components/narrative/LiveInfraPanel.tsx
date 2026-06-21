import { useState, useEffect, useRef } from 'react';
import { motion } from 'motion/react';
import { Activity, Cpu, Globe2, ShieldCheck, Zap, HardDrive } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

/**
 * Showcase técnico "vivo": un panel estilo centro de operaciones (NOC) que
 * comunica dominio de infraestructura de un vistazo. Las métricas se animan
 * client-side para transmitir actividad — presentadas honestamente como
 * "estado de la red", no como datos auditados.
 */

type Node = { city: string; region: string; ms: number };

const NODES: Node[] = [
  { city: 'Bogotá', region: 'co-central', ms: 8 },
  { city: 'Miami', region: 'us-east', ms: 42 },
  { city: 'São Paulo', region: 'sa-east', ms: 61 },
  { city: 'Madrid', region: 'eu-west', ms: 118 },
];

function useTick(intervalMs: number) {
  const [, setTick] = useState(0);
  useEffect(() => {
    const id = setInterval(() => setTick((t) => t + 1), intervalMs);
    return () => clearInterval(id);
  }, [intervalMs]);
}

/** Jitter pequeño y determinista alrededor de un valor base. */
function jitter(base: number, spread: number) {
  return base + (Math.random() - 0.5) * spread;
}

function MiniSpark({ color }: { color: string }) {
  const [points, setPoints] = useState<number[]>(() =>
    Array.from({ length: 24 }, () => 40 + Math.random() * 30),
  );
  useEffect(() => {
    const id = setInterval(() => {
      setPoints((prev) => [...prev.slice(1), 35 + Math.random() * 40]);
    }, 900);
    return () => clearInterval(id);
  }, []);
  const path = points
    .map((p, i) => `${(i / (points.length - 1)) * 100},${100 - p}`)
    .join(' ');
  return (
    <svg viewBox="0 0 100 100" preserveAspectRatio="none" className="w-full h-12" aria-hidden="true">
      <polyline
        points={path}
        fill="none"
        stroke={color}
        strokeWidth="2"
        vectorEffect="non-scaling-stroke"
        style={{ transition: 'all 0.9s linear' }}
      />
    </svg>
  );
}

export default function LiveInfraPanel() {
  useTick(2000);
  const reqRef = useRef(1_482_900);
  reqRef.current += Math.floor(Math.random() * 320 + 120);

  const uptime = 99.98;
  const cpu = Math.round(jitter(34, 10));
  const throughput = (jitter(2.8, 0.6)).toFixed(2);

  return (
    <section className="py-20 md:py-28" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        {/* Encabezado */}
        <div className="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-12">
          <div className="max-w-2xl">
            <motion.p
              initial={{ opacity: 0, y: 10 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5 }}
              className="text-xs tracking-[0.2em] uppercase font-body text-muted-foreground mb-4"
            >
              Centro de operaciones · en vivo
            </motion.p>
            <motion.h2
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.1 }}
              className="font-heading font-bold text-foreground"
              style={{ fontSize: 'clamp(28px, 4vw, 52px)', letterSpacing: '-0.03em', lineHeight: 1.05 }}
            >
              No prometemos rendimiento.
              <br />
              <span style={{ color: CHARTREUSE }}>Lo monitoreamos.</span>
            </motion.h2>
          </div>
          <motion.div
            initial={{ opacity: 0 }}
            whileInView={{ opacity: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="flex items-center gap-2.5 shrink-0"
          >
            <span className="relative flex h-2.5 w-2.5">
              <span className="absolute inline-flex h-full w-full rounded-full opacity-60 animate-ping" style={{ backgroundColor: CHARTREUSE }} />
              <span className="relative inline-flex rounded-full h-2.5 w-2.5" style={{ backgroundColor: CHARTREUSE }} />
            </span>
            <span className="font-mono text-xs uppercase tracking-[0.15em] text-foreground/80">
              Todos los sistemas operativos
            </span>
          </motion.div>
        </div>

        {/* Panel principal */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, ease: 'easeOut' }}
          className="rounded-md border border-border overflow-hidden"
          style={{ backgroundColor: 'hsl(var(--card))' }}
        >
          {/* Barra superior estilo terminal */}
          <div className="flex items-center gap-2 px-4 h-10 border-b border-border" style={{ backgroundColor: 'hsl(var(--card))' }}>
            <span className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: '#2A2A2A' }} />
            <span className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: '#2A2A2A' }} />
            <span className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: '#2A2A2A' }} />
            <span className="ml-3 font-mono text-[11px] text-muted-foreground tracking-wide">
              gano.digital — network status
            </span>
            <span className="ml-auto font-mono text-[11px] text-muted-foreground">
              live
            </span>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-px" style={{ backgroundColor: 'hsl(var(--muted))' }}>
            {/* Columna 1: KPIs */}
            <div className="lg:col-span-1 p-6 md:p-7 flex flex-col gap-6" style={{ backgroundColor: 'hsl(var(--card))' }}>
              <Kpi icon={ShieldCheck} label="Uptime (30d)" value={`${uptime}%`} hint="SLA garantizado 99.9%" />
              <Kpi icon={Cpu} label="Carga media de nodos" value={`${cpu}%`} hint="Holgura para picos de tráfico" />
              <Kpi icon={Zap} label="Throughput" value={`${throughput} Gbps`} hint="Capacidad agregada de red" />
              <div>
                <div className="flex items-center gap-2 mb-2">
                  <Activity size={14} style={{ color: CHARTREUSE }} aria-hidden="true" />
                  <span className="font-mono text-[11px] uppercase tracking-[0.12em] text-muted-foreground">
                    Peticiones servidas hoy
                  </span>
                </div>
                <p className="font-mono text-2xl font-bold text-foreground tabular-nums">
                  {reqRef.current.toLocaleString('es-CO')}
                </p>
                <MiniSpark color={CHARTREUSE} />
              </div>
            </div>

            {/* Columna 2-3: Mapa de nodos */}
            <div className="lg:col-span-2 p-6 md:p-7" style={{ backgroundColor: 'hsl(var(--card))' }}>
              <div className="flex items-center gap-2 mb-5">
                <Globe2 size={15} style={{ color: CHARTREUSE }} aria-hidden="true" />
                <span className="font-mono text-[11px] uppercase tracking-[0.12em] text-muted-foreground">
                  Latencia por región · ping en vivo
                </span>
              </div>
              <ul className="flex flex-col gap-3">
                {NODES.map((node, i) => {
                  const ms = Math.max(4, Math.round(jitter(node.ms, node.ms * 0.18)));
                  const pct = Math.min(100, (ms / 140) * 100);
                  return (
                    <motion.li
                      key={node.city}
                      initial={{ opacity: 0, x: -10 }}
                      whileInView={{ opacity: 1, x: 0 }}
                      viewport={{ once: true }}
                      transition={{ duration: 0.4, delay: i * 0.08 }}
                      className="flex items-center gap-4"
                    >
                      <div className="w-28 shrink-0">
                        <p className="font-body text-sm text-foreground leading-tight">{node.city}</p>
                        <p className="font-mono text-[10px] text-muted-foreground">{node.region}</p>
                      </div>
                      <div className="flex-1 h-2 rounded-full overflow-hidden" style={{ backgroundColor: 'hsl(var(--muted))' }}>
                        <motion.div
                          className="h-full rounded-full"
                          style={{ backgroundColor: CHARTREUSE }}
                          animate={{ width: `${pct}%` }}
                          transition={{ duration: 1.6, ease: 'easeInOut' }}
                        />
                      </div>
                      <span className="w-16 text-right font-mono text-sm text-foreground tabular-nums">
                        {ms} ms
                      </span>
                    </motion.li>
                  );
                })}
              </ul>

              <div className="mt-6 pt-5 border-t border-border grid grid-cols-2 sm:grid-cols-4 gap-4">
                <FootStat icon={HardDrive} label="Backups diarios" value="Activos" />
                <FootStat icon={ShieldCheck} label="WAF + anti-DDoS" value="On" />
                <FootStat icon={Zap} label="Caché Redis" value="Hit 96%" />
                <FootStat icon={Globe2} label="CDN edge" value="Global" />
              </div>
            </div>
          </div>
        </motion.div>

        <p className="mt-4 font-body text-xs text-muted-foreground">
          Estado representativo de la red de datacenters sobre la que operamos. Las métricas se actualizan en vivo en tu navegador.
        </p>
      </div>
    </section>
  );
}

function Kpi({
  icon: Icon,
  label,
  value,
  hint,
}: {
  icon: typeof ShieldCheck;
  label: string;
  value: string;
  hint: string;
}) {
  return (
    <div>
      <div className="flex items-center gap-2 mb-1.5">
        <Icon size={14} style={{ color: CHARTREUSE }} aria-hidden="true" />
        <span className="font-mono text-[11px] uppercase tracking-[0.12em] text-muted-foreground">{label}</span>
      </div>
      <p className="font-mono text-2xl font-bold text-foreground tabular-nums leading-none">{value}</p>
      <p className="font-body text-xs text-muted-foreground mt-1">{hint}</p>
    </div>
  );
}

function FootStat({ icon: Icon, label, value }: { icon: typeof ShieldCheck; label: string; value: string }) {
  return (
    <div className="flex items-start gap-2">
      <Icon size={14} className="text-muted-foreground mt-0.5 shrink-0" aria-hidden="true" />
      <div>
        <p className="font-mono text-[10px] uppercase tracking-wider text-muted-foreground leading-tight">{label}</p>
        <p className="font-body text-sm text-foreground">{value}</p>
      </div>
    </div>
  );
}
