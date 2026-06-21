import { motion } from 'motion/react';
import type { ReactNode } from 'react';

const CHARTREUSE = '#D97E3A';

/**
 * Branding del stack — IA, frameworks e infraestructura.
 *
 * Cada marca se dibuja como un ícono monoline propio en la paleta terracota
 * del sitio (no logos oficiales multicolor). Esto mantiene la coherencia visual
 * de la marca Gano y evita usar artwork registrado de terceros.
 *
 * Los SVG usan `currentColor` para heredar el color del contenedor, de modo que
 * el hover y los estados se controlan con clases de Tailwind.
 */

type Tool = { name: string; icon: ReactNode };
type Group = { label: string; caption: string; tools: Tool[] };

const s = { fill: 'none', stroke: 'currentColor', strokeWidth: 1.6, strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const };

// ── Íconos IA ──────────────────────────────────────────────
const ClaudeIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    {/* destello de 8 puntas, alusivo a Claude */}
    <g {...s}>
      <path d="M12 3v6M12 15v6M3 12h6M15 12h6" />
      <path d="M6.3 6.3l3 3M14.7 14.7l3 3M17.7 6.3l-3 3M9.3 14.7l-3 3" />
    </g>
  </svg>
);
const OpenAIIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    {/* nudo hexagonal entrelazado */}
    <path {...s} d="M12 4.5l5 2.9v5.8l-5 2.9-5-2.9V7.4l5-2.9z" />
    <path {...s} d="M12 4.5v5.8M12 10.3l5-2.9M12 10.3l-5-2.9M12 10.3v5.8M12 16.1l5-2.9M12 16.1l-5-2.9" />
  </svg>
);
const GeminiIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    {/* destello de 4 puntas cóncavo, alusivo a Gemini */}
    <path {...s} d="M12 3c0 4.97 4.03 9 9 9-4.97 0-9 4.03-9 9 0-4.97-4.03-9-9-9 4.97 0 9-4.03 9-9z" />
  </svg>
);
const CopilotIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    {/* gafas/binario estilizado de copilot */}
    <rect {...s} x="3" y="9" width="7" height="7" rx="3.5" />
    <rect {...s} x="14" y="9" width="7" height="7" rx="3.5" />
    <path {...s} d="M10 12.5h4M5.5 7.5C6.5 5.5 8 5 12 5s5.5.5 6.5 2.5" />
  </svg>
);

// ── Íconos Frameworks ──────────────────────────────────────
const ReactIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <circle {...s} cx="12" cy="12" r="1.6" />
    <ellipse {...s} cx="12" cy="12" rx="10" ry="4" />
    <ellipse {...s} cx="12" cy="12" rx="10" ry="4" transform="rotate(60 12 12)" />
    <ellipse {...s} cx="12" cy="12" rx="10" ry="4" transform="rotate(120 12 12)" />
  </svg>
);
const NextIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <circle {...s} cx="12" cy="12" r="9" />
    <path {...s} d="M8.5 8v8M8.5 8l7.5 9.2M15.5 8.2V14" />
  </svg>
);
const NodeIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <path {...s} d="M12 3l7.5 4.3v8.6L12 21l-7.5-4.1V7.3L12 3z" />
    <path {...s} d="M9.5 14.3c0 1 .8 1.6 2.3 1.6 1.6 0 2.4-.7 2.4-1.8 0-1-.6-1.5-2.1-1.9l-.7-.2c-1.4-.3-2-.8-2-1.7 0-1 .9-1.6 2.2-1.6 1.3 0 2.1.6 2.2 1.6" />
  </svg>
);
const TypeScriptIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <rect {...s} x="3" y="3" width="18" height="18" rx="3" />
    <path {...s} d="M7 10.5h4M9 10.5V16M13 15.4c.3.5 1 .8 1.8.8 1 0 1.7-.5 1.7-1.3 0-.7-.4-1.1-1.4-1.4l-.6-.2c-1-.3-1.5-.7-1.5-1.5 0-.8.7-1.3 1.6-1.3.7 0 1.3.3 1.6.8" />
  </svg>
);
const TailwindIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <path {...s} d="M5 12c1-3 2.7-4.5 5-4.5 3.5 0 3.9 2.5 5.7 3 1.2.3 2.2-.1 3.3-1.2-1 3-2.7 4.5-5 4.5-3.5 0-3.9-2.5-5.7-3-1.2-.3-2.2.1-3.3 1.2z" />
    <path {...s} d="M3 18c1-3 2.7-4.5 5-4.5 3.5 0 3.9 2.5 5.7 3 1.2.3 2.2-.1 3.3-1.2" transform="translate(2 0)" />
  </svg>
);

// ── Íconos Infraestructura ─────────────────────────────────
const CloudflareIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <path {...s} d="M6 16h11a3 3 0 000-6 4.5 4.5 0 00-8.5-1.5A3.5 3.5 0 006 16z" />
    <path {...s} d="M17 16l3-1" />
  </svg>
);
const NginxIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <path {...s} d="M12 3l7.5 4.3v8.6L12 21l-7.5-4.1V7.3L12 3z" />
    <path {...s} d="M9 16V9l6 7V9" />
  </svg>
);
const LinuxIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    {/* pingüino simplificado */}
    <path {...s} d="M12 4c-2 0-3 1.5-3 4 0 1.5-1.5 3-2.5 5-1 2 .5 4 2 4.5M12 4c2 0 3 1.5 3 4 0 1.5 1.5 3 2.5 5 1 2-.5 4-2 4.5" />
    <path {...s} d="M8.5 17.5c1 1 2 1.5 3.5 1.5s2.5-.5 3.5-1.5" />
    <circle cx="10.5" cy="8.5" r="0.6" fill="currentColor" stroke="none" />
    <circle cx="13.5" cy="8.5" r="0.6" fill="currentColor" stroke="none" />
  </svg>
);
const DockerIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <g {...s}>
      <rect x="4" y="11" width="3" height="3" />
      <rect x="8" y="11" width="3" height="3" />
      <rect x="12" y="11" width="3" height="3" />
      <rect x="8" y="7" width="3" height="3" />
      <rect x="12" y="7" width="3" height="3" />
    </g>
    <path {...s} d="M3 14c0 3 2.5 5 6 5 5 0 8.5-2.5 9.5-6 1.5.3 2.5-.3 3-1-1 .2-1.7 0-2-.5" />
  </svg>
);
const ViteIcon = (
  <svg viewBox="0 0 24 24" className="h-7 w-7" aria-hidden="true">
    <path {...s} d="M3.5 5l8.5 15 8.5-15-8.5 2-8.5-2z" />
    <path {...s} d="M12 7.5l-2.2 1.2 2.2 4 2-4.5L12 7.5z" />
  </svg>
);

const GROUPS: Group[] = [
  {
    label: 'Inteligencia Artificial',
    caption: 'Razonamiento, redacción y agentes',
    tools: [
      { name: 'Claude', icon: ClaudeIcon },
      { name: 'OpenAI', icon: OpenAIIcon },
      { name: 'Gemini', icon: GeminiIcon },
      { name: 'Copilot', icon: CopilotIcon },
    ],
  },
  {
    label: 'Frameworks de desarrollo',
    caption: 'La base de cada producto que construimos',
    tools: [
      { name: 'React', icon: ReactIcon },
      { name: 'Next.js', icon: NextIcon },
      { name: 'Node.js', icon: NodeIcon },
      { name: 'TypeScript', icon: TypeScriptIcon },
      { name: 'Tailwind', icon: TailwindIcon },
    ],
  },
  {
    label: 'Infraestructura y DevOps',
    caption: 'Lo que mantiene todo en pie y veloz',
    tools: [
      { name: 'Cloudflare', icon: CloudflareIcon },
      { name: 'Nginx', icon: NginxIcon },
      { name: 'Linux', icon: LinuxIcon },
      { name: 'Docker', icon: DockerIcon },
      { name: 'Vite', icon: ViteIcon },
    ],
  },
];

type TechStackProps = {
  /** Texto del kicker superior. */
  kicker?: string;
  /** Título de la sección. */
  title?: string;
  /** Subtítulo descriptivo. */
  subtitle?: string;
  /** Tono de fondo. Por defecto usa la superficie sutil del tema (--muted). */
  background?: string;
};

export default function TechStack({
  kicker = 'Nuestro stack',
  title = 'Las herramientas con las que trabajamos.',
  subtitle = 'IA de frontera, frameworks probados e infraestructura sólida — la misma caja de herramientas que usamos para construir y mantener proyectos reales.',
  background = 'hsl(var(--muted))',
}: TechStackProps) {
  return (
    <section className="py-20 md:py-28" style={{ backgroundColor: background, borderTop: '1px solid hsl(var(--border))' }}>
      <div className="max-w-7xl mx-auto px-6 md:px-10">
        <motion.p
          initial={{ opacity: 0, y: 10 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="font-mono text-xs uppercase tracking-[0.18em] mb-4"
          style={{ color: CHARTREUSE }}
        >
          {kicker}
        </motion.p>
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.05 }}
          className="font-heading font-bold text-foreground mb-4 max-w-2xl"
          style={{ fontSize: 'clamp(26px, 3.6vw, 44px)', letterSpacing: '-0.03em', lineHeight: 1.1 }}
        >
          {title}
        </motion.h2>
        <motion.p
          initial={{ opacity: 0, y: 16 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.1 }}
          className="font-body text-base md:text-lg text-muted-foreground mb-14 max-w-2xl"
          style={{ lineHeight: 1.65 }}
        >
          {subtitle}
        </motion.p>

        <div className="flex flex-col gap-12">
          {GROUPS.map((group, gi) => (
            <motion.div
              key={group.label}
              initial={{ opacity: 0, y: 24 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true, margin: '-60px' }}
              transition={{ duration: 0.5, delay: gi * 0.1 }}
            >
              <div className="flex items-baseline gap-3 mb-5">
                <span className="font-mono text-xs tabular-nums" style={{ color: CHARTREUSE }}>
                  0{gi + 1}
                </span>
                <h3 className="font-heading text-lg font-bold text-foreground">{group.label}</h3>
                <span className="font-body text-sm text-muted-foreground hidden sm:inline">
                  · {group.caption}
                </span>
              </div>
              <ul className="flex flex-wrap gap-3">
                {group.tools.map((tool) => (
                  <li key={tool.name}>
                    <div
                      className="group flex items-center gap-3 rounded-md border px-4 py-3 transition-colors duration-200 hover:border-primary"
                      style={{ borderColor: 'hsl(var(--border))', backgroundColor: 'rgba(217,126,58,0.03)' }}
                    >
                      <span className="text-muted-foreground transition-colors duration-200 group-hover:text-primary">
                        {tool.icon}
                      </span>
                      <span className="font-heading text-sm font-bold text-foreground/90 whitespace-nowrap">
                        {tool.name}
                      </span>
                    </div>
                  </li>
                ))}
              </ul>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
