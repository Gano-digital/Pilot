/**
 * Genera el contexto del catálogo que se inyecta en el system prompt del
 * asesor de IA. Lee directamente de SERVICES (único origen de verdad), así que
 * cuando el catálogo cambie, el conocimiento del agente cambia con él — sin
 * duplicar datos ni arriesgar que invente precios desactualizados.
 */

import {
  SERVICES,
  formatPrice,
  billingLabel,
  buyKind,
  type Service,
} from '@/data/catalog';

/** Traduce el tipo de destino de compra a una instrucción de cierre para el agente. */
function buyChannel(service: Service): string {
  switch (buyKind(service.buyUrl)) {
    case 'whatsapp':
      return 'compra/asesoría por WhatsApp (servicio de alto valor, atención 1-a-1)';
    case 'domains':
      return 'usar el buscador de dominios del sitio';
    case 'escaparate':
      return 'compra directa en el catálogo completo del sitio';
    case 'checkout':
    default:
      return 'compra directa en línea';
  }
}

/**
 * Construye un resumen compacto pero completo del catálogo, agrupado por
 * categoría, con precio, descripción corta, caso de uso y canal de compra.
 */
export function buildCatalogContext(): string {
  const byCategory = new Map<string, Service[]>();
  for (const s of SERVICES) {
    const list = byCategory.get(s.category) ?? [];
    list.push(s);
    byCategory.set(s.category, list);
  }

  const sections: string[] = [];
  for (const [category, services] of byCategory) {
    const lines = services.map((s) => {
      const price = `${formatPrice(s.priceFrom, s.currency)} ${billingLabel(s.billingPeriod)}`.trim();
      const useCase = s.useCase ? ` · Cuándo conviene: ${s.useCase}` : '';
      return `  - **${s.name}** (${price}) — ${s.shortDescription} Canal: ${buyChannel(s)}.${useCase}`;
    });
    sections.push(`### ${category}\n${lines.join('\n')}`);
  }

  return `## CATÁLOGO REAL DE GANO DIGITAL\nEstos son los únicos servicios, precios y características que puedes mencionar. Usa los nombres y precios exactos.\n\n${sections.join('\n\n')}`;
}
