import type { Request, Response } from 'express';

/**
 * Dynamic Open Graph image generator.
 * Returns an SVG-based OG image (1200×630) styled to match the site's
 * dark editorial aesthetic — chartreuse accent, Syne-style bold heading.
 *
 * Usage: /api/og?title=Page+Title&description=Short+description&tag=Hosting
 */
export default function handler(req: Request, res: Response) {
  const title = sanitize(firstParam(req.query.title) ?? 'Gano Digital', 60);
  const description = sanitize(
    firstParam(req.query.description) ?? 'Hosting, dominios y seguridad con ingeniería curada.',
    120,
  );
  // `tag` is also used in SVG geometry math, so we keep its plain-text length
  // (pre-escape) separately to size the pill accurately — an escaped "&" is
  // 5 chars (&amp;) but renders as 1 glyph.
  const tagPlain = truncate(firstParam(req.query.tag) ?? '', 30);
  const tag = escapeXml(tagPlain.toUpperCase());
  const tagGlyphs = tagPlain.length;

  // Word-wrap title into up to 2 lines (~28 chars each at this font size)
  const titleLines = wrapText(title, 28);

  const svg = `<?xml version="1.0" encoding="UTF-8"?>
<svg width="1200" height="630" viewBox="0 0 1200 630" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <!-- Subtle grid pattern -->
    <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
      <path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
    </pattern>
    <!-- Chartreuse glow -->
    <radialGradient id="glow" cx="15%" cy="85%" r="50%">
      <stop offset="0%" stop-color="#C8F04D" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#0A0A0A" stop-opacity="0"/>
    </radialGradient>
  </defs>

  <!-- Background -->
  <rect width="1200" height="630" fill="#0A0A0A"/>
  <rect width="1200" height="630" fill="url(#grid)"/>
  <rect width="1200" height="630" fill="url(#glow)"/>

  <!-- Left chartreuse accent bar -->
  <rect x="0" y="0" width="6" height="630" fill="#C8F04D"/>

  <!-- Top-left studio label -->
  <text x="60" y="72" font-family="system-ui, -apple-system, sans-serif" font-size="13"
        font-weight="500" fill="#666" letter-spacing="4" text-anchor="start">
    GANO DIGITAL
  </text>

  <!-- Tag pill (optional) -->
  ${tag ? `
  <rect x="60" y="100" width="${tagGlyphs * 9 + 28}" height="28" rx="14" fill="#1E1E1E" stroke="#2A2A2A" stroke-width="1"/>
  <text x="${60 + tagGlyphs * 4.5 + 14}" y="119" font-family="system-ui, -apple-system, sans-serif"
        font-size="12" font-weight="500" fill="#C8F04D" letter-spacing="2" text-anchor="middle">
    ${tag}
  </text>` : ''}

  <!-- Main title -->
  ${titleLines.map((line, i) => `
  <text x="60" y="${(tag ? 200 : 180) + i * 96}"
        font-family="system-ui, -apple-system, sans-serif"
        font-size="80" font-weight="800" fill="#F5F5F5"
        letter-spacing="-2" text-anchor="start">${line}</text>`).join('')}

  <!-- Description -->
  <text x="60" y="${(tag ? 200 : 180) + titleLines.length * 96 + 36}"
        font-family="system-ui, -apple-system, sans-serif"
        font-size="22" font-weight="400" fill="#888" letter-spacing="0" text-anchor="start">
    ${description}
  </text>

  <!-- Bottom-right chartreuse dot + "Available for projects" -->
  <circle cx="1100" cy="580" r="6" fill="#C8F04D"/>
  <text x="1116" y="585" font-family="system-ui, -apple-system, sans-serif"
        font-size="14" font-weight="500" fill="#666" letter-spacing="1" text-anchor="start">
    Hosting · Dominios · Seguridad
  </text>

  <!-- Bottom border line -->
  <rect x="60" y="610" width="1080" height="1" fill="#1E1E1E"/>
</svg>`;

  res
    .status(200)
    .set('Content-Type', 'image/svg+xml')
    .set('Cache-Control', 'public, max-age=86400, stale-while-revalidate=3600')
    .send(svg);
}

/**
 * Coerce a query param to a single safe string. Array-valued params
 * (?title=a&title=b → ["a","b"]) and non-string values are rejected to
 * undefined so the caller falls back to its default — never String([...]).
 */
function firstParam(v: unknown): string | undefined {
  return typeof v === 'string' ? v : undefined;
}

/** Truncate to a max glyph count. Operates on RAW (pre-escape) text. */
function truncate(input: string, maxLen: number): string {
  return input.slice(0, maxLen);
}

/** Escape the five XML-significant characters. */
function escapeXml(input: string): string {
  return input.replace(
    /[<>&"']/g,
    (c) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&apos;' })[c]!,
  );
}

/**
 * Truncate FIRST, then escape — never the other way around. Escaping before
 * slicing can cut a multi-char entity (e.g. "&amp;") mid-sequence, producing
 * malformed markup ("...&am") that breaks the SVG or, in lenient parsers,
 * could be reassembled into live markup. Truncating the raw input first
 * guarantees every escaped entity in the output is whole and inert.
 */
function sanitize(input: string, maxLen: number): string {
  return escapeXml(truncate(input, maxLen));
}

/** Naive word-wrap: split into lines of ~maxChars characters */
function wrapText(text: string, maxChars: number): string[] {
  const words = text.split(' ');
  const lines: string[] = [];
  let current = '';
  for (const word of words) {
    if ((current + ' ' + word).trim().length > maxChars && current) {
      lines.push(current.trim());
      current = word;
      if (lines.length >= 2) break; // max 2 lines
    } else {
      current = (current + ' ' + word).trim();
    }
  }
  if (current && lines.length < 2) lines.push(current.trim());
  return lines;
}
