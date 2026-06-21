import { useState, useCallback } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Search, Loader2, ArrowRight, Check, X, AlertCircle } from 'lucide-react';
import { formatPrice } from '@/data/catalog';

const PLID = 599667;

interface DomainResult {
  domain: string;
  available: boolean;
  listPrice?: number | string;
  currency?: string;
}

interface ApiResponse {
  exactMatchDomain?: DomainResult;
  suggestedDomains?: DomainResult[];
}

/** Submit a hidden form POST to GoDaddy's reseller cart (no CORS on form POST). */
function addToCart(domainName: string) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `https://www.secureserver.net/api/v1/cart/${PLID}/?redirect=true`;

  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'items';
  input.value = JSON.stringify([{ id: 'domain', domain: domainName }]);
  form.appendChild(input);

  document.body.appendChild(form);
  form.submit();
}

/** Normalize a raw API price into a formatted display string. */
function displayPrice(d: DomainResult): string | null {
  if (d.listPrice === undefined || d.listPrice === null || d.listPrice === '') return null;
  const num = typeof d.listPrice === 'number' ? d.listPrice : Number(String(d.listPrice).replace(/[^0-9.]/g, ''));
  if (Number.isFinite(num) && num > 0) {
    return formatPrice(num, d.currency || 'COP');
  }
  // Fall back to the raw string if it already looks formatted.
  return typeof d.listPrice === 'string' ? d.listPrice : null;
}

function DomainRow({ d, featured }: { d: DomainResult; featured?: boolean }) {
  const price = displayPrice(d);
  return (
    <div
      className={`flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-sm border transition-colors duration-200 ${
        featured ? 'border-primary bg-muted' : 'border-border bg-card'
      }`}
    >
      <div className="flex items-center gap-3 min-w-0">
        <span className="font-heading text-lg font-bold text-card-foreground truncate">
          {d.domain}
        </span>
        {d.available ? (
          <span className="inline-flex items-center gap-1 font-body text-xs font-bold uppercase tracking-wider text-primary-foreground bg-primary px-2 py-1 rounded-sm shrink-0">
            <Check size={12} strokeWidth={3} aria-hidden="true" /> Disponible
          </span>
        ) : (
          <span className="inline-flex items-center gap-1 font-body text-xs font-bold uppercase tracking-wider text-destructive-foreground bg-destructive px-2 py-1 rounded-sm shrink-0">
            <X size={12} strokeWidth={3} aria-hidden="true" /> No disponible
          </span>
        )}
      </div>

      <div className="flex items-center gap-4 shrink-0">
        {price && (
          <span className="font-heading text-base font-bold text-primary whitespace-nowrap">
            {price}<span className="font-body text-xs text-muted-foreground">/año</span>
          </span>
        )}
        {d.available && (
          <button
            type="button"
            onClick={() => addToCart(d.domain)}
            className="inline-flex items-center gap-2 h-10 px-5 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.1em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.03] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
            aria-label={`Registrar ${d.domain}`}
          >
            Registrar <ArrowRight size={15} strokeWidth={2.25} aria-hidden="true" />
          </button>
        )}
      </div>
    </div>
  );
}

export default function DomainSearch() {
  const [query, setQuery] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [results, setResults] = useState<ApiResponse | null>(null);
  const [searched, setSearched] = useState(false);

  const runSearch = useCallback(async () => {
    const q = query.trim();
    if (!q) return;
    setLoading(true);
    setError(null);
    try {
      const res = await fetch(`/api/domains/search?q=${encodeURIComponent(q)}&pageSize=6`);
      if (!res.ok) {
        const body = await res.json().catch(() => null);
        throw new Error(body?.error || 'No se pudo completar la búsqueda.');
      }
      const data: ApiResponse = await res.json();
      setResults(data);
      setSearched(true);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Ocurrió un error inesperado.');
      setResults(null);
      setSearched(true);
    } finally {
      setLoading(false);
    }
  }, [query]);

  const onSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    void runSearch();
  };

  const suggestions = results?.suggestedDomains ?? [];
  const exact = results?.exactMatchDomain;
  const hasResults = !!exact || suggestions.length > 0;

  return (
    <div className="rounded-sm border border-border bg-card p-6 md:p-8">
      {/* Header */}
      <div className="mb-6">
        <h3 className="font-heading text-2xl font-bold text-card-foreground mb-2">
          Encuentra tu dominio
        </h3>
        <p className="font-body text-sm text-muted-foreground">
          Escribe el nombre que quieres y verifica su disponibilidad al instante.
        </p>
      </div>

      {/* Search form */}
      <form onSubmit={onSubmit} className="flex flex-col sm:flex-row gap-3 mb-2">
        <div className="relative flex-1">
          <Search
            size={18}
            className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground pointer-events-none"
            aria-hidden="true"
          />
          <input
            type="text"
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="miempresa.com"
            aria-label="Nombre de dominio a buscar"
            className="w-full h-12 pl-11 pr-4 rounded-sm bg-background border border-border text-foreground font-body placeholder:text-muted-foreground focus:border-primary focus:outline-none transition-colors duration-200"
          />
        </div>
        <button
          type="submit"
          disabled={loading || !query.trim()}
          className="inline-flex items-center justify-center gap-2 h-12 px-7 rounded-sm font-heading text-sm font-bold uppercase tracking-[0.12em] bg-primary text-primary-foreground transition-transform duration-200 hover:scale-[1.02] disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
        >
          {loading ? (
            <>
              <Loader2 size={16} className="animate-spin" aria-hidden="true" /> Buscando
            </>
          ) : (
            <>
              <Search size={16} aria-hidden="true" /> Buscar
            </>
          )}
        </button>
      </form>

      {/* Error */}
      <AnimatePresence>
        {error && (
          <motion.div
            initial={{ opacity: 0, y: -6 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
            className="flex items-center gap-2 mt-4 p-3 rounded-sm border border-destructive/50 bg-destructive/10 text-sm font-body text-destructive-foreground"
            role="alert"
          >
            <AlertCircle size={16} className="shrink-0" aria-hidden="true" />
            <span>{error}</span>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Results */}
      <AnimatePresence mode="wait">
        {searched && !error && (
          <motion.div
            key="results"
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.35, ease: 'easeOut' as const }}
            className="mt-6"
          >
            {!hasResults ? (
              <p className="font-body text-sm text-muted-foreground py-6 text-center">
                No encontramos resultados para esa búsqueda. Prueba con otro nombre.
              </p>
            ) : (
              <div className="flex flex-col gap-3">
                {exact && (
                  <>
                    <p className="font-body text-xs uppercase tracking-[0.15em] text-muted-foreground">
                      Coincidencia exacta
                    </p>
                    <DomainRow d={exact} featured />
                  </>
                )}
                {suggestions.length > 0 && (
                  <>
                    <p className="font-body text-xs uppercase tracking-[0.15em] text-muted-foreground mt-2">
                      Sugerencias
                    </p>
                    {suggestions.map((d) => (
                      <DomainRow key={d.domain} d={d} />
                    ))}
                  </>
                )}
              </div>
            )}
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
