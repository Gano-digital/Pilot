import { useLocation } from 'react-router-dom';
import { useMemo } from 'react';
import {
  localeFromPath,
  localizedPath,
  alternatePath,
  type Locale,
} from '@/lib/i18n';

/**
 * Deriva el locale activo de la ruta actual. NO usa estado ni contexto: el
 * locale es una función pura del pathname, así que servidor y cliente siempre
 * coinciden (sin mismatch de hidratación). `useLocation` funciona igual bajo
 * StaticRouterProvider (SSR) y BrowserRouter (cliente).
 *
 * Devuelve además helpers ya ligados al pathname/locale actuales para que los
 * componentes no tengan que importar el módulo i18n y reconstruir argumentos.
 */
export function useLocale() {
  const { pathname } = useLocation();

  return useMemo(() => {
    const locale = localeFromPath(pathname);
    return {
      locale,
      isEnglish: locale === 'en',
      /** Prefija una ruta canónica española según el locale activo. */
      to: (canonicalEsPath: string) => localizedPath(canonicalEsPath, locale),
      /** La URL equivalente de ESTA página en el otro idioma (o null). */
      alternate: (target: Locale) => alternatePath(pathname, target),
    };
  }, [pathname]);
}

export type { Locale };
