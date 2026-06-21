import { Helmet } from '@dr.pogodin/react-helmet';
import {
  alternatePath,
  OG_LOCALE,
  type Locale,
} from '@/lib/i18n';

const SITE = 'https://gano.digital';

type Props = {
  /**
   * Ruta CANÓNICA en español de esta página (sin prefijo), p. ej. '/catalogo'.
   * El componente deriva de aquí la URL en cada idioma y emite los `hreflang`
   * recíprocos. Pásale '/' para la home.
   */
  canonicalEsPath: string;
  /**
   * Locale en el que se está renderizando ESTA instancia. DEBE venir de
   * `useLocale()` (derivado del pathname) para ser determinista en SSR y
   * cliente — nunca de `window`, que no existe en el servidor y provocaría
   * `og:locale` incorrecto + mismatch de hidratación.
   */
  locale: Locale;
};

/**
 * Emite el bloque de SEO multilingüe para una página que existe en ambos
 * idiomas: `hreflang` recíproco (es, en, x-default) y `og:locale` /
 * `og:locale:alternate`. react-helmet fusiona múltiples Helmets, así que basta
 * con renderizar <LocaleSeo canonicalEsPath="/catalogo" locale={locale} />
 * junto al Helmet existente de la página.
 *
 * Reglas de hreflang (críticas para que Google las acepte):
 *  - Deben ser RECÍPROCAS: si /catalogo apunta a /en/catalogo como 'en',
 *    /en/catalogo debe apuntar de vuelta a /catalogo como 'es'. Como ambas
 *    páginas usan este mismo componente con el MISMO canonicalEsPath, la
 *    reciprocidad queda garantizada por construcción.
 *  - URLs absolutas siempre.
 *  - x-default apunta al español (idioma por defecto del negocio).
 */
export function LocaleSeo({ canonicalEsPath, locale }: Props) {
  const esPath = canonicalEsPath === '/' ? '/' : canonicalEsPath.replace(/\/$/, '');
  const enPath = alternatePath(esPath, 'en') ?? '/en';

  const esUrl = `${SITE}${esPath}`;
  const enUrl = `${SITE}${enPath}`;

  return (
    <Helmet>
      <html lang={locale} />
      <link rel="alternate" hrefLang="es" href={esUrl} />
      <link rel="alternate" hrefLang="en" href={enUrl} />
      <link rel="alternate" hrefLang="x-default" href={esUrl} />
      <meta property="og:locale" content={OG_LOCALE[locale]} />
      <meta
        property="og:locale:alternate"
        content={OG_LOCALE[locale === 'es' ? 'en' : 'es']}
      />
    </Helmet>
  );
}

