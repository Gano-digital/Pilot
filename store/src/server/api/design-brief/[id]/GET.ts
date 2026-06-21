import type { Request, Response } from 'express';
import { DESIGN_PACKAGES, getDesignPackage } from '../../../../data/designCatalog.js';

/**
 * GET /api/design-brief/:id
 *
 * Devuelve el BRIEF ESTRATÉGICO de producción de un paquete de diseño:
 * el prompt maestro de generación + toda la ficha (paleta, tipografía,
 * secciones, entregables). Es el "panel de producción" de Gano Digital:
 * cuando entra un cliente interesado en un estilo, este endpoint entrega
 * la estrategia lista para producir.
 *
 * Sin :id válido devuelve 404 con la lista de ids disponibles.
 */
export default function handler(req: Request, res: Response) {
  const id = String(req.params.id ?? '').trim();
  const pkg = getDesignPackage(id);

  if (!pkg) {
    return res.status(404).json({
      error: 'Paquete de diseño no encontrado.',
      availableIds: DESIGN_PACKAGES.map((p) => p.id),
    });
  }

  res.set('Cache-Control', 'public, max-age=300, must-revalidate').json({
    id: pkg.id,
    name: pkg.name,
    vibe: pkg.vibe,
    tagline: pkg.tagline,
    description: pkg.description,
    idealFor: pkg.idealFor,
    palette: pkg.palette,
    typography: pkg.typography,
    motion: pkg.motion,
    sections: pkg.sections,
    deliverables: pkg.deliverables,
    priceFrom: pkg.priceFrom,
    currency: 'COP',
    timeline: pkg.timeline,
    badge: pkg.badge ?? null,
    /** Prompt maestro de producción — estrategia lista para generar el sitio. */
    generationPrompt: pkg.generationPrompt,
  });
}
