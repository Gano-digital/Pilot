/**
 * Núcleo de internacionalización — módulo PURO (sin React, sin `window`).
 *
 * Estrategia de arquitectura (decidida con el usuario):
 *  - El ESPAÑOL es el idioma por defecto y vive SIN prefijo (`/catalogo`,
 *    `/services`…). Esas URLs YA están indexadas por Google; no se tocan ni se
 *    redirigen, de modo que el SEO en español queda intacto.
 *  - El INGLÉS vive bajo el prefijo `/en` (`/en`, `/en/catalogo`…), con URLs
 *    propias e indexables, enlazadas a su par en español vía `hreflang`.
 *
 * Por qué un módulo puro: tanto el render de servidor (entry-server.tsx) como
 * el cliente derivan el locale del `pathname`. Si dependiéramos de `window` o
 * de estado global, el SSR y la hidratación divergirían (mismatch de React).
 * Derivar todo de la ruta garantiza que servidor y cliente coincidan siempre.
 */

export type Locale = 'es' | 'en';

export const DEFAULT_LOCALE: Locale = 'es';
export const LOCALES: readonly Locale[] = ['es', 'en'] as const;

/** Nombre de la cookie que recuerda la preferencia EXPLÍCITA del usuario. */
export const LOCALE_COOKIE = 'gano-lang';
/** Vigencia de la cookie de idioma: 1 año en segundos. */
export const LOCALE_COOKIE_MAX_AGE = 60 * 60 * 24 * 365;

/**
 * Rutas comerciales que existen en AMBOS idiomas en esta primera entrega.
 * La clave es la ruta canónica en español (sin prefijo); el valor es su par
 * en inglés (con prefijo `/en`). Mantener este mapa como única fuente de verdad
 * evita que la navegación y el `hreflang` se desincronicen.
 *
 * NOTA: las rutas en inglés CONSERVAN el slug en español (`/en/catalogo`,
 * `/en/soluciones-ia`) a propósito. Cambiar también el slug multiplicaría el
 * riesgo de enlaces rotos sin beneficio SEO real (el contenido de la página,
 * no el slug, es lo que Google indexa para el idioma). El prefijo `/en` ya
 * señala el idioma de forma inequívoca.
 */
const TRANSLATED_PATHS: Record<string, string> = {
  '/': '/en',
  '/catalogo': '/en/catalogo',
  '/services': '/en/services',
  '/soluciones-ia': '/en/soluciones-ia',
  '/seguridad': '/en/seguridad',
  '/contact': '/en/contact',
};

/** Mapa inverso (inglés → español) construido una sola vez. */
const EN_TO_ES: Record<string, string> = Object.fromEntries(
  Object.entries(TRANSLATED_PATHS).map(([es, en]) => [en, es]),
);

/** Normaliza un pathname: sin query/hash, sin barra final (salvo la raíz). */
function normalizePath(pathname: string): string {
  const clean = pathname.split('?')[0].split('#')[0];
  if (clean.length > 1 && clean.endsWith('/')) return clean.slice(0, -1);
  return clean || '/';
}

/**
 * Deriva el locale activo a partir del pathname. Cualquier ruta que empiece por
 * `/en` (exactamente `/en` o `/en/...`) es inglés; el resto es español.
 */
export function localeFromPath(pathname: string): Locale {
  const p = normalizePath(pathname);
  if (p === '/en' || p.startsWith('/en/')) return 'en';
  return 'es';
}

/**
 * Devuelve la versión de `pathname` en el locale solicitado, SOLO si la página
 * tiene par traducido. Si no lo tiene (p. ej. `/filosofia`, que aún no está en
 * inglés), devuelve `null` para que el llamador decida (típicamente: ocultar el
 * conmutador o enviar a la home del otro idioma).
 */
export function alternatePath(pathname: string, target: Locale): string | null {
  const p = normalizePath(pathname);
  const currentLocale = localeFromPath(p);
  if (currentLocale === target) return p;

  if (target === 'en') {
    // español → inglés
    return TRANSLATED_PATHS[p] ?? null;
  }
  // inglés → español
  return EN_TO_ES[p] ?? null;
}

/**
 * Prefija una ruta canónica española con el locale activo. Úsalo para construir
 * enlaces de navegación que respeten el idioma en el que está el usuario:
 *   localizedPath('/catalogo', 'en') → '/en/catalogo'
 *   localizedPath('/catalogo', 'es') → '/catalogo'
 * Si la ruta no tiene par traducido y el target es inglés, cae a la raíz `/en`
 * para no generar un 404.
 */
export function localizedPath(canonicalEsPath: string, locale: Locale): string {
  const p = normalizePath(canonicalEsPath);
  if (locale === 'es') return p;
  return TRANSLATED_PATHS[p] ?? '/en';
}

/** ¿La ruta actual tiene una versión en el otro idioma? */
export function hasAlternate(pathname: string): boolean {
  const p = normalizePath(pathname);
  return p in TRANSLATED_PATHS || p in EN_TO_ES;
}

/** Etiqueta `hreflang` BCP-47 para cada locale. */
export const HREFLANG: Record<Locale, string> = {
  es: 'es',
  en: 'en',
};

/** Valor `og:locale` para cada locale. */
export const OG_LOCALE: Record<Locale, string> = {
  es: 'es_ES',
  en: 'en_US',
};
