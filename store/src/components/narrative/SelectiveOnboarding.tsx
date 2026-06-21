import { motion } from 'motion/react';
import { Link } from 'react-router-dom';
import { ArrowRight, Lock, Users, CalendarClock } from 'lucide-react';

const CHARTREUSE = '#D97E3A';

/**
 * Escasez honesta: posicionamos la capacidad limitada de onboarding como
 * consecuencia de la calidad (atención curada), no como truco de marketing.
 * Genera FOMO real y, al mismo tiempo, refuerza el posicionamiento de estudio.
 */

const TOTAL_SLOTS = 12;
const TAKEN = 9;
const remaining = TOTAL_SLOTS - TAKEN;

export default function SelectiveOnboarding() {
  return (
    <section className="py-20 md:py-28 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
      <motion.div
        aria-hidden="true"
        className="absolute top-0 right-0 w-[45vw] h-[45vw] rounded-full pointer-events-none"
        style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.08) 0%, transparent 60%)' }}
        animate={{ opacity: [0.5, 0.9, 0.5] }}
        transition={{ duration: 6, repeat: Infinity, ease: 'easeInOut' }}
      />

      <div className="relative max-w-7xl mx-auto px-6 md:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
          {/* Texto */}
          <div className="lg:col-span-7">
            <motion.div
              initial={{ opacity: 0, y: 10 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5 }}
              className="inline-flex items-center gap-2 mb-6 border border-border rounded-full px-3 py-1.5"
            >
              <Lock size={13} style={{ color: CHARTREUSE }} aria-hidden="true" />
              <span className="font-mono text-xs uppercase tracking-[0.15em] text-foreground/80">
                Acceso por cupos
              </span>
            </motion.div>

            <motion.h2
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.1 }}
              className="font-heading font-bold text-foreground mb-6"
              style={{ fontSize: 'clamp(30px, 4.5vw, 60px)', letterSpacing: '-0.03em', lineHeight: 1.04 }}
            >
              No tomamos todos los proyectos.
              <br />
              <span style={{ color: CHARTREUSE }}>Por eso los que tomamos vuelan.</span>
            </motion.h2>

            <motion.p
              initial={{ opacity: 0, y: 16 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.2 }}
              className="font-body text-base md:text-lg text-muted-foreground leading-relaxed max-w-xl mb-8"
            >
              Cada migración la planeamos a mano y la acompañamos un ingeniero asignado.
              Para no diluir esa atención, abrimos un número limitado de cupos de
              onboarding por trimestre. <span className="text-foreground">Cuando se llenan, se llenan.</span>
            </motion.p>

            <motion.div
              initial={{ opacity: 0, y: 10 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: 0.3 }}
              className="flex flex-wrap items-center gap-4"
            >
              <Link
                to="/catalogo"
                className="group inline-flex items-center gap-2 h-12 px-7 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03]"
              >
                Reservar mi cupo <ArrowRight size={16} className="transition-transform duration-200 group-hover:translate-x-1" />
              </Link>
              <span className="font-body text-sm text-muted-foreground">
                Sin compromiso · Diagnóstico inicial gratuito
              </span>
            </motion.div>
          </div>

          {/* Tarjeta de cupos */}
          <motion.div
            initial={{ opacity: 0, scale: 0.96 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, ease: 'easeOut' }}
            className="lg:col-span-5"
          >
            <div className="rounded-md border border-border p-7 md:p-8" style={{ backgroundColor: 'hsl(var(--card))' }}>
              <div className="flex items-center justify-between mb-6">
                <div className="flex items-center gap-2">
                  <CalendarClock size={16} style={{ color: CHARTREUSE }} aria-hidden="true" />
                  <span className="font-mono text-xs uppercase tracking-[0.12em] text-muted-foreground">
                    Cupos · Q3 2026
                  </span>
                </div>
                <span className="font-mono text-xs px-2 py-1 rounded-sm" style={{ backgroundColor: 'rgba(217,126,58,0.12)', color: CHARTREUSE }}>
                  Abierto
                </span>
              </div>

              <p className="font-heading font-bold text-foreground leading-none mb-2 tabular-nums" style={{ fontSize: 'clamp(48px, 7vw, 80px)' }}>
                {String(remaining).padStart(2, '0')}
                <span className="text-muted-foreground" style={{ fontSize: '0.4em' }}> / {TOTAL_SLOTS}</span>
              </p>
              <p className="font-body text-sm text-muted-foreground mb-6">
                cupos de onboarding disponibles este trimestre
              </p>

              {/* Barra de ocupación */}
              <div className="h-2.5 rounded-full overflow-hidden mb-3" style={{ backgroundColor: 'hsl(var(--muted))' }}>
                <motion.div
                  className="h-full rounded-full"
                  style={{ backgroundColor: CHARTREUSE }}
                  initial={{ width: 0 }}
                  whileInView={{ width: `${(TAKEN / TOTAL_SLOTS) * 100}%` }}
                  viewport={{ once: true }}
                  transition={{ duration: 1.4, ease: 'easeOut', delay: 0.3 }}
                />
              </div>
              <div className="flex items-center gap-2 text-muted-foreground">
                <Users size={13} aria-hidden="true" />
                <span className="font-body text-xs">
                  {TAKEN} marcas ya aseguraron el suyo
                </span>
              </div>
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
