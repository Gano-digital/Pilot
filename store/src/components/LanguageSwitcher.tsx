import { useNavigate } from 'react-router-dom';
import { Languages } from 'lucide-react';
import { useLocale } from '@/hooks/useLocale';
import {
  LOCALE_COOKIE,
  LOCALE_COOKIE_MAX_AGE,
  type Locale,
} from '@/lib/i18n';

/**
 * Fija la cookie de preferencia de idioma. Es la elección EXPLÍCITA del usuario,
 * así que la autodetección (LanguageAutoSuggest) la respetará y no volverá a
 * sugerir cambiar de idioma. SameSite=Lax basta: la cookie solo influye en
 * navegación de primer nivel dentro del propio sitio.
 */
function setLocaleCookie(locale: Locale) {
  if (typeof document === 'undefined') return;
  document.cookie = `${LOCALE_COOKIE}=${locale}; path=/; max-age=${LOCALE_COOKIE_MAX_AGE}; SameSite=Lax`;
}

type Props = {
  /** Estilo compacto (desktop, junto al toggle de tema) vs. expandido (móvil). */
  variant?: 'compact' | 'full';
  className?: string;
};

/**
 * Conmutador ES/EN. Cruza a la URL EQUIVALENTE de la página actual en el otro
 * idioma (no a la home), preservando el contexto del usuario. Si la página no
 * tiene par traducido, cae a la home del otro idioma para no romper.
 */
export function LanguageSwitcher({ variant = 'compact', className = '' }: Props) {
  const navigate = useNavigate();
  const { locale, alternate } = useLocale();
  const target: Locale = locale === 'en' ? 'es' : 'en';

  const handleSwitch = () => {
    setLocaleCookie(target);
    const dest = alternate(target) ?? (target === 'en' ? '/en' : '/');
    navigate(dest);
  };

  const label =
    target === 'en' ? 'Ver en inglés / View in English' : 'Ver en español / View in Spanish';

  if (variant === 'full') {
    return (
      <button
        type="button"
        onClick={handleSwitch}
        aria-label={label}
        className={`inline-flex items-center justify-center gap-2 font-body text-base py-3 rounded-sm border border-border text-foreground/80 hover:text-foreground hover:border-primary transition-colors duration-200 ${className}`}
      >
        <Languages size={17} aria-hidden="true" />
        {target === 'en' ? 'English' : 'Español'}
      </button>
    );
  }

  return (
    <button
      type="button"
      onClick={handleSwitch}
      aria-label={label}
      title={label}
      className={`inline-flex items-center gap-1.5 h-9 px-2.5 rounded-full border border-border text-xs font-heading font-bold tracking-widest text-foreground/70 hover:text-foreground hover:border-primary transition-colors duration-200 ${className}`}
    >
      <Languages size={14} aria-hidden="true" />
      {target.toUpperCase()}
    </button>
  );
}
