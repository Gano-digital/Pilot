/**
 * Agent-editable registry of publicly-crawlable routes. Consumed by the
 * /sitemap.xml handler in src/server/entry.ts.
 *
 * Guidelines for maintaining this file:
 * - Static public paths are synced automatically from src/routes.tsx.
 * - Do not include dynamic-param routes like "/products/:id" directly.
 *   Instead, enumerate real values (e.g. "/products/desk-pro") or skip.
 * - `path` MUST start with "/".
 * - Priorities are between 0.0 and 1.0. Home = 1.0, main sections = 0.8,
 *   deep pages = 0.5.
 * - Dev-only or auth-required routes MUST NOT be listed.
 */

export interface SeoRoute {
  path: string;
  changefreq?:
    | "always"
    | "hourly"
    | "daily"
    | "weekly"
    | "monthly"
    | "yearly"
    | "never";
  priority?: number;
  lastmod?: string;
}

export const seoRoutes: SeoRoute[] = [
  { path: "/", changefreq: "weekly", priority: 1.0 },
  { path: "/portfolio", changefreq: "monthly", priority: 0.8 },
  { path: "/about", changefreq: "monthly", priority: 0.8 },
  { path: "/services", changefreq: "monthly", priority: 0.8 },
  { path: "/disenos", changefreq: "monthly", priority: 0.8 },
  { path: "/catalogo", changefreq: "monthly", priority: 0.8 },
  { path: "/proceso", changefreq: "monthly", priority: 0.8 },
  { path: "/aprende", changefreq: "monthly", priority: 0.8 },
  { path: "/soluciones-ia", changefreq: "monthly", priority: 0.8 },
  { path: "/filosofia", changefreq: "monthly", priority: 0.8 },
  { path: "/seguridad", changefreq: "monthly", priority: 0.8 },
  { path: "/terminos", changefreq: "monthly", priority: 0.8 },
  { path: "/reembolsos", changefreq: "monthly", priority: 0.8 },
  { path: "/privacidad", changefreq: "monthly", priority: 0.8 },
  { path: "/contact", changefreq: "monthly", priority: 0.8 },
  { path: "/game", changefreq: "monthly", priority: 0.8 },
  { path: "/en", changefreq: "weekly", priority: 0.9 },
  { path: "/en/catalogo", changefreq: "monthly", priority: 0.5 },
  { path: "/en/services", changefreq: "monthly", priority: 0.5 },
  { path: "/en/soluciones-ia", changefreq: "monthly", priority: 0.5 },
  { path: "/en/seguridad", changefreq: "monthly", priority: 0.5 },
  { path: "/en/contact", changefreq: "monthly", priority: 0.5 },
];
