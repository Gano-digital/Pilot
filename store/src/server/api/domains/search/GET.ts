import type { Request, Response } from 'express';

const PLID = 599667;

/**
 * GET /api/domains/search?q=QUERY&pageSize=5
 *
 * Proxies the GoDaddy reseller domain-search API through the backend so the
 * browser never makes the cross-origin call directly (GoDaddy blocks CORS).
 * The reseller storefront (gano.digital/dominios/) is presented as Referer/
 * Origin so the upstream API attributes the search to this reseller (plid).
 */
export default async function handler(req: Request, res: Response) {
  try {
    // Reject array-valued params (?q=a&q=b makes req.query.q a string[]).
    const rawQ = req.query.q;
    if (typeof rawQ !== 'undefined' && typeof rawQ !== 'string') {
      return res.status(400).json({ error: 'Parámetro "q" inválido.' });
    }
    let q = (rawQ as string | undefined)?.toLowerCase().trim() ?? '';

    if (!q) {
      return res.status(400).json({ error: 'Missing query parameter "q".' });
    }

    // Cap raw length before any processing to bound work and the upstream URL.
    if (q.length > 253) {
      return res.status(400).json({ error: 'La consulta es demasiado larga.' });
    }

    // If the user typed a full domain ("basoccer.club"), extract just the SLD.
    if (q.includes('.')) {
      const parts = q.split('.');
      if (parts[0].length >= 2) q = parts[0];
    }

    // Allow-list the SLD to the DNS label charset (RFC 1035: a-z, 0-9, hyphen),
    // and forbid leading/trailing hyphens. This is the key SSRF / injection
    // guard: only a clean label can ever reach the upstream URL, regardless of
    // what the client sends. Anything else is rejected before the fetch.
    if (!/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/.test(q)) {
      return res.status(400).json({
        error: 'El dominio solo puede contener letras, números y guiones.',
      });
    }

    // Clamp pageSize to a sane range.
    const rawPageSize = Number.parseInt((req.query.pageSize as string) ?? '5', 10);
    const pageSize = Number.isFinite(rawPageSize)
      ? Math.min(Math.max(rawPageSize, 1), 20)
      : 5;

    const url = `https://www.secureserver.net/api/v1/domains/${PLID}/?q=${encodeURIComponent(
      q,
    )}&pageSize=${pageSize}`;

    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 8000);

    let upstream: globalThis.Response;
    try {
      upstream = await fetch(url, {
        headers: {
          Referer: 'https://gano.digital/dominios/',
          Origin: 'https://gano.digital',
          Accept: 'application/json',
        },
        signal: controller.signal,
      });
    } finally {
      clearTimeout(timeout);
    }

    if (!upstream.ok) {
      console.error('domains.search.upstream-error', {
        status: upstream.status,
        q,
      });
      return res
        .status(502)
        .json({ error: 'El servicio de dominios no está disponible en este momento.' });
    }

    const data = await upstream.json();
    // Cache successful lookups briefly to soften repeated identical searches.
    res.set('Cache-Control', 'public, max-age=60');
    return res.json(data);
  } catch (error) {
    const aborted = error instanceof Error && error.name === 'AbortError';
    console.error('domains.search.failed', {
      aborted,
      error: error instanceof Error ? error.message : String(error),
    });
    return res
      .status(aborted ? 504 : 500)
      .json({ error: 'No se pudo completar la búsqueda de dominios.' });
  }
}
