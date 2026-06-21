import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { X, Languages } from 'lucide-react';
import { useLocale } from '@/hooks/useLocale';
import {
  LOCALE_COOKIE,
  LOCALE_COOKIE_MAX_AGE,
  type Locale,
} from '@/lib/i18n';

/**
 * Autodetección de idioma en la PRIMERA visita — implementada como sugerencia
 * no intrusiva, NO como redirección forzada.
 *
 * Por qué un banner y no un redirect automático:
 *  - Un redirect server-side por `Accept-Language` fragmenta el caché del CDN
 *    (cada idioma necesita su variante) y puede confundir a los crawlers, que
 *    declaran inglés en sus cabeceras y acabarían indexando /en en vez del
 *    español canónico.
 *  - Un redirect client-side duro produce un salto visible tras la hidratación.
 *  - La práctica recomendada (Google, Airbnb…) es SUGERIR una vez y recordar la
 *    decisión. El usuario mantiene el control; los buscadores ven la página que
 *    pidieron.
 *
 * SSR-safe: todo ocurre dentro de un useEffect (solo cliente). El primer render
 * del servidor y la hidratación coinciden porque el banner arranca oculto.
 */

function readCookie(name: string): string | null {
  if (typeof document === 'undefined') return null;
  const match = document.cookie.match(new RegExp(`(?:^|; )${name}=([^;]*)`));
  return match ? decodeURIComponent(match[1]) : null;
}

function writeLocaleCookie(locale: Locale) {
  if (typeof document === 'undefined') return;
  document.cookie = `${LOCALE_COOKIE}=${locale}; path=/; max-age=${LOCALE_COOKIE_MAX_AGE}; SameSite=Lax`;
}

export function LanguageAutoSuggest() {
  const navigate = useNavigate();
  const { locale, alternate } = useLocale();
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    // Si ya hay preferencia explícita guardada, no sugerimos nunca más.
    if (readCookie(LOCALE_COOKIE)) return;

    // El navegador prefiere inglés y el usuario está viendo el sitio en español.
    const prefersEnglish = (navigator.languages ?? [navigator.language])
      .some((l) => l?.toLowerCase().startsWith('en'));

    if (locale === 'es' && prefersEnglish) {
      // Solo sugerimos si esta página TIENE versión en inglés.
      if (alternate('en')) setVisible(true);
    }
  }, [locale, alternate]);

  if (!visible) return null;

  const dismiss = (remember: Locale | null) => {
    if (remember) writeLocaleCookie(remember);
    setVisible(false);
  };

  const goEnglish = () => {
    const dest = alternate('en') ?? '/en';
    writeLocaleCookie('en');
    setVisible(false);
    navigate(dest);
  };

  return (
    <div
      role="dialog"
      aria-label="Language suggestion"
      className="fixed bottom-4 left-1/2 -translate-x-1/2 z-[90] w-[calc(100%-2rem)] max-w-md"
    >
      <div
        className="flex items-center gap-3 rounded-xl px-4 py-3 shadow-lg"
        style={{
          backgroundColor: 'hsl(var(--popover))',
          border: '1px solid hsl(var(--border))',
        }}
      >
        <Languages size={18} className="shrink-0 text-primary" aria-hidden="true" />
        <p className="flex-1 text-sm font-body text-foreground/90 leading-snug">
          This site is available in English.
        </p>
        <button
          type="button"
          onClick={goEnglish}
          className="shrink-0 inline-flex items-center h-8 px-3 rounded-md text-xs font-heading font-bold uppercase tracking-wider transition-opacity duration-200 hover:opacity-90"
          style={{ background: 'hsl(var(--primary))', color: 'hsl(var(--primary-foreground))' }}
        >
          View in English
        </button>
        <button
          type="button"
          onClick={() => dismiss('es')}
          aria-label="Keep Spanish / Mantener español"
          className="shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-md text-foreground/60 hover:text-foreground hover:bg-muted transition-colors duration-200"
        >
          <X size={16} />
        </button>
      </div>
    </div>
  );
}
