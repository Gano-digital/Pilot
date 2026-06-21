import { useState, useMemo } from 'react';
import { motion } from 'motion/react';
import { SERVICES, CATEGORY_ORDER } from '@/data/catalog';
import ServiceCard from './ServiceCard';

const containerVariants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.06 } },
} as const;

type GridLocale = 'es' | 'en';

/** Traducción de etiquetas de filtro. La CLAVE real de categoría (en español,
 *  de CATEGORY_ORDER) se conserva para el filtrado; solo cambia el rótulo. */
const CATEGORY_LABEL_EN: Record<string, string> = {
  Todos: 'All',
  'Hosting WordPress': 'WordPress Hosting',
  'Constructor Web': 'Website Builder',
  Dominios: 'Domains',
  Email: 'Email',
  Marketing: 'Marketing',
  Seguridad: 'Security',
  'Para Desarrolladores': 'For Developers',
  'VPS & Cómputo': 'VPS & Compute',
  'Servicios Gano': 'Gano Services',
};

export default function CatalogGrid({ locale = 'es' }: { locale?: GridLocale }) {
  const allLabel = locale === 'en' ? 'All' : 'Todos';
  const [active, setActive] = useState<string>(allLabel);

  const categories = useMemo(() => [allLabel, ...CATEGORY_ORDER], [allLabel]);

  const filtered = useMemo(() => {
    const list = active === allLabel
      ? SERVICES
      : SERVICES.filter((s) => s.category === active);
    // Mantener el orden por categoría canónica, luego por aparición
    return [...list].sort(
      (a, b) => CATEGORY_ORDER.indexOf(a.category) - CATEGORY_ORDER.indexOf(b.category),
    );
  }, [active, allLabel]);

  const labelFor = (cat: string) =>
    locale === 'en' ? (CATEGORY_LABEL_EN[cat] ?? cat) : cat;

  return (
    <div>
      {/* Filtros */}
      <div className="flex flex-wrap gap-2 mb-10" role="tablist" aria-label={locale === 'en' ? 'Filter services by category' : 'Filtrar servicios por categoría'}>
        {categories.map((cat) => {
          const isActive = active === cat;
          return (
            <button
              key={cat}
              role="tab"
              aria-selected={isActive}
              onClick={() => setActive(cat)}
              className={`font-body text-sm px-4 py-2 rounded-sm border transition-colors duration-200 ${
                isActive
                  ? 'bg-primary text-primary-foreground border-primary'
                  : 'bg-transparent text-muted-foreground border-border hover:text-foreground hover:border-foreground'
              }`}
            >
              {labelFor(cat)}
            </button>
          );
        })}
      </div>

      {/* Grid */}
      <motion.div
        key={active}
        variants={containerVariants}
        initial="hidden"
        animate="visible"
        className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        {filtered.map((service) => (
          <ServiceCard key={service.id} service={service} locale={locale} />
        ))}
      </motion.div>
    </div>
  );
}
