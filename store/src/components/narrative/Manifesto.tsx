import { motion } from 'motion/react';

const CHARTREUSE = '#D97E3A';

export default function Manifesto() {
  return (
    <section className="py-24 md:py-36 relative overflow-hidden" style={{ backgroundColor: 'hsl(var(--background))', borderTop: '1px solid hsl(var(--border))' }}>
      {/* Glow ambiental */}
      <motion.div
        aria-hidden="true"
        className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70vw] h-[70vw] rounded-full pointer-events-none"
        style={{ background: 'radial-gradient(circle, rgba(217,126,58,0.07) 0%, transparent 60%)' }}
        animate={{ scale: [1, 1.06, 1], opacity: [0.5, 0.9, 0.5] }}
        transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
      />
      <div className="relative max-w-4xl mx-auto px-6 md:px-10 text-center">
        <motion.p
          initial={{ opacity: 0, y: 10 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="text-xs tracking-[0.25em] uppercase font-body text-muted-foreground mb-8"
        >
          Por qué decimos «soberana»
        </motion.p>
        <motion.blockquote
          initial={{ opacity: 0, y: 24 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6, ease: 'easeOut' }}
          className="font-heading font-bold text-foreground"
          style={{ fontSize: 'clamp(26px, 3.8vw, 48px)', letterSpacing: '-0.02em', lineHeight: 1.18 }}
        >
          Tu negocio no debería depender de un servidor que no entiendes,
          en un país que no conoces, atendido por nadie en particular.{' '}
          <span style={{ color: CHARTREUSE }}>
            Soberanía es saber que tu infraestructura es tuya — y que hay un humano
            que responde por ella.
          </span>
        </motion.blockquote>
        <motion.p
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.3 }}
          className="font-body text-muted-foreground mt-10"
        >
          — El equipo de Gano Digital
        </motion.p>
      </div>
    </section>
  );
}
