import type { Request, Response } from 'express';
import { DESIGN_PACKAGES } from '../../../data/designCatalog.js';

/**
 * GET /api/design-packages
 *
 * Devuelve el catálogo de paquetes de diseño en forma resumida (sin el
 * prompt de producción, que vive en /api/design-brief/:id). Útil para
 * alimentar la página /disenos o integraciones externas.
 */
export default function handler(_req: Request, res: Response) {
  const packages = DESIGN_PACKAGES.map((p) => ({
    id: p.id,
    name: p.name,
    vibe: p.vibe,
    tagline: p.tagline,
    description: p.description,
    idealFor: p.idealFor,
    palette: p.palette,
    typography: p.typography,
    sections: p.sections,
    deliverables: p.deliverables,
    priceFrom: p.priceFrom,
    currency: 'COP',
    timeline: p.timeline,
    badge: p.badge ?? null,
  }));

  res
    .set('Cache-Control', 'public, max-age=300, must-revalidate')
    .json({ count: packages.length, packages });
}
