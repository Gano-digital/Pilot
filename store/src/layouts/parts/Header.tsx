import { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, Sun, Moon, LogIn, ArrowUpRight, ChevronDown } from 'lucide-react';
import { useTheme } from '@/components/ThemeProvider';
import { BrandLogo } from '@/components/BrandLogo';
import { LOGIN_URL, REGISTER_URL } from '@/data/catalog';
import { useLocale } from '@/hooks/useLocale';
import { navStrings, type NavEntry } from '@/lib/nav-content';
import { localizedPath } from '@/lib/i18n';
import { LanguageSwitcher } from '@/components/LanguageSwitcher';

/**
 * Arquitectura de navegación — agrupada en 5 entradas para evitar saturación.
 * `children` define un menú desplegable; sin `children` es enlace directo.
 * Las etiquetas y las rutas se resuelven por locale (ver lib/nav-content.ts y
 * lib/i18n.ts): las `href` son canónicas en español y se prefijan con `/en`
 * cuando el usuario está navegando en inglés.
 */

/** Detecta el estado activo de un grupo comparando rutas YA localizadas. */
function groupIsActive(entry: NavEntry, pathname: string, toLocalized: (p: string) => string): boolean {
  if (entry.href) return pathname === toLocalized(entry.href);
  return entry.children?.some((c) => toLocalized(c.href) === pathname) ?? false;
}

export default function Header() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [openMenu, setOpenMenu] = useState<string | null>(null);
  const [mobileGroup, setMobileGroup] = useState<string | null>(null);
  const closeTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const location = useLocation();
  const { theme, toggleTheme } = useTheme();
  const { locale, to } = useLocale();
  const t = navStrings(locale);
  const navEntries = t.entries;
  const homeHref = localizedPath('/', locale);

  // Cierre con retardo para que el cursor pueda viajar del trigger al panel.
  const scheduleClose = () => {
    if (closeTimer.current) clearTimeout(closeTimer.current);
    closeTimer.current = setTimeout(() => setOpenMenu(null), 120);
  };
  const cancelClose = () => {
    if (closeTimer.current) clearTimeout(closeTimer.current);
  };

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 40);
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    setMobileOpen(false);
    setOpenMenu(null);
    setMobileGroup(null);
  }, [location.pathname]);

  // Cerrar desplegables con Escape.
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === 'Escape') setOpenMenu(null);
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, []);

  useEffect(() => {
    document.body.style.overflow = mobileOpen ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [mobileOpen]);

  return (
    <>
      <header
        className="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        style={{
          backgroundColor: scrolled ? 'hsl(var(--background))' : 'transparent',
          borderBottom: scrolled
            ? '1px solid hsl(var(--border))'
            : '1px solid transparent',
        }}
      >
        <div className="max-w-7xl mx-auto px-6 md:px-10 flex items-center justify-between h-20 md:h-24">
          {/* Brand logo — adaptable al tema (clara/oscura con fundido) */}
          <Link
            to={homeHref}
            className="flex items-center group shrink-0"
            aria-label={t.brandHome}
          >
            <BrandLogo className="h-9 md:h-11 transition-transform duration-200 group-hover:scale-[1.03]" />
          </Link>

          {/* Desktop nav */}
          <nav className="hidden lg:flex items-center gap-1 xl:gap-2">
            {navEntries.map((entry) => {
              const isActive = groupIsActive(entry, location.pathname, to);

              // Enlace directo (sin desplegable)
              if (!entry.children) {
                return (
                  <Link
                    key={entry.label}
                    to={to(entry.href!)}
                    className="relative px-3 py-2 text-sm font-body text-foreground/70 hover:text-foreground transition-colors duration-200 group whitespace-nowrap"
                  >
                    {entry.label}
                    <span
                      className="absolute bottom-1 left-3 right-3 h-px bg-primary transition-all duration-250 ease-out"
                      style={{ transform: isActive ? 'scaleX(1)' : 'scaleX(0)', transformOrigin: 'left' }}
                    />
                    <span className="absolute bottom-1 left-3 right-3 h-px bg-primary origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-250 ease-out" />
                  </Link>
                );
              }

              // Entrada con menú desplegable
              const isOpen = openMenu === entry.label;
              return (
                <div
                  key={entry.label}
                  className="relative"
                  onMouseEnter={() => { cancelClose(); setOpenMenu(entry.label); }}
                  onMouseLeave={scheduleClose}
                >
                  <button
                    type="button"
                    aria-haspopup="true"
                    aria-expanded={isOpen}
                    onClick={() => setOpenMenu(isOpen ? null : entry.label)}
                    className="inline-flex items-center gap-1 px-3 py-2 text-sm font-body text-foreground/70 hover:text-foreground transition-colors duration-200 whitespace-nowrap"
                    style={{ color: isActive ? 'hsl(var(--foreground))' : undefined }}
                  >
                    {entry.label}
                    <ChevronDown
                      size={14}
                      aria-hidden="true"
                      className="transition-transform duration-200"
                      style={{ transform: isOpen ? 'rotate(180deg)' : 'rotate(0deg)' }}
                    />
                  </button>

                  {/* Panel desplegable */}
                  <div
                    className="absolute left-0 top-full pt-2 transition-all duration-200"
                    style={{
                      opacity: isOpen ? 1 : 0,
                      visibility: isOpen ? 'visible' : 'hidden',
                      transform: isOpen ? 'translateY(0)' : 'translateY(-6px)',
                      pointerEvents: isOpen ? 'auto' : 'none',
                    }}
                    onMouseEnter={cancelClose}
                    onMouseLeave={scheduleClose}
                  >
                    <div
                      className="min-w-[260px] rounded-md overflow-hidden p-1.5 shadow-lg"
                      style={{ backgroundColor: 'hsl(var(--popover))', border: '1px solid hsl(var(--border))' }}
                    >
                      {entry.children.map((child) => {
                        const childHref = to(child.href);
                        const childActive = location.pathname === childHref;
                        return (
                          <Link
                            key={child.href}
                            to={childHref}
                            className="block rounded px-3 py-2.5 transition-colors duration-150 hover:bg-muted"
                            style={{ backgroundColor: childActive ? 'hsl(var(--muted))' : undefined }}
                          >
                            <span className="block text-sm font-body font-medium text-foreground">{child.label}</span>
                            <span className="block text-xs text-muted-foreground mt-0.5">{child.desc}</span>
                          </Link>
                        );
                      })}
                    </div>
                  </div>
                </div>
              );
            })}

            {/* Separador sutil entre navegación y accesos de cuenta */}
            <span className="h-5 w-px bg-border mx-1" aria-hidden="true" />

            {/* Iniciar sesión — acceso sutil a la plataforma (pestaña nueva) */}
            <a
              href={LOGIN_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1.5 text-sm font-body text-foreground/70 hover:text-foreground transition-colors duration-200 whitespace-nowrap"
            >
              <LogIn size={15} aria-hidden="true" />
              {t.login}
            </a>

            {/* Registro — CTA primario destacado (pestaña nueva) */}
            <a
              href={REGISTER_URL}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1.5 text-xs font-heading font-bold uppercase tracking-widest transition-all duration-200 hover:opacity-90"
              style={{ color: 'hsl(var(--primary-foreground))', background: 'hsl(var(--primary))', padding: '7px 15px', borderRadius: 2, letterSpacing: '0.18em' }}
            >
              {t.register}
              <ArrowUpRight size={14} aria-hidden="true" />
            </a>

            {/* Conmutador de idioma (desktop) */}
            <LanguageSwitcher variant="compact" className="ml-1" />

            {/* Botón de tema (desktop) */}
            <button
              type="button"
              onClick={toggleTheme}
              aria-label={theme === 'dark' ? t.toLight : t.toDark}
              title={theme === 'dark' ? t.toLight : t.toDark}
              className="ml-1 inline-flex items-center justify-center h-9 w-9 rounded-full border border-border text-foreground/70 hover:text-foreground hover:border-primary transition-colors duration-200"
            >
              {theme === 'dark' ? <Sun size={16} /> : <Moon size={16} />}
            </button>
          </nav>

          {/* Controles móviles: tema + hamburguesa */}
          <div className="flex items-center gap-1 lg:hidden">
            <button
              type="button"
              onClick={toggleTheme}
              aria-label={theme === 'dark' ? t.toLight : t.toDark}
              className="inline-flex items-center justify-center h-9 w-9 rounded-full text-foreground/70 hover:text-foreground transition-colors duration-200"
            >
              {theme === 'dark' ? <Sun size={18} /> : <Moon size={18} />}
            </button>
            <button
              className="text-foreground p-1"
              onClick={() => setMobileOpen(true)}
              aria-label={t.openMenu}
            >
              <Menu size={22} />
            </button>
          </div>
        </div>
      </header>

      {/* Mobile full-screen overlay */}
      <div
        className="fixed inset-0 z-[100] flex flex-col transition-all duration-300"
        aria-hidden={!mobileOpen}
        inert={!mobileOpen}
        style={{
          backgroundColor: 'hsl(var(--background))',
          opacity: mobileOpen ? 1 : 0,
          pointerEvents: mobileOpen ? 'all' : 'none',
        }}
      >
        <div className="flex items-center justify-between px-6 h-20">
          <Link to={homeHref} className="flex items-center shrink-0" aria-label={t.brandHome}>
            <BrandLogo className="h-9" priority={false} />
          </Link>
          <button
            className="text-foreground p-1"
            onClick={() => setMobileOpen(false)}
            aria-label={t.closeMenu}
          >
            <X size={22} />
          </button>
        </div>
        <nav className="flex flex-col flex-1 px-8 py-6 gap-1 overflow-y-auto">
          {navEntries.map((entry, i) => {
            if (!entry.children) {
              return (
                <Link
                  key={entry.label}
                  to={to(entry.href!)}
                  className="font-heading text-3xl font-bold text-foreground/80 hover:text-primary transition-colors duration-200 py-3"
                  style={{ transitionDelay: mobileOpen ? `${i * 50}ms` : '0ms' }}
                >
                  {entry.label}
                </Link>
              );
            }
            const expanded = mobileGroup === entry.label;
            return (
              <div key={entry.label} className="border-b border-border/60 last:border-0">
                <button
                  type="button"
                  aria-expanded={expanded}
                  onClick={() => setMobileGroup(expanded ? null : entry.label)}
                  className="w-full flex items-center justify-between py-3 font-heading text-3xl font-bold text-foreground/80 hover:text-primary transition-colors duration-200"
                >
                  {entry.label}
                  <ChevronDown
                    size={24}
                    aria-hidden="true"
                    className="transition-transform duration-200 text-muted-foreground"
                    style={{ transform: expanded ? 'rotate(180deg)' : 'rotate(0deg)' }}
                  />
                </button>
                <div
                  className="overflow-hidden transition-all duration-300 ease-out"
                  style={{ maxHeight: expanded ? `${entry.children.length * 64}px` : '0px', opacity: expanded ? 1 : 0 }}
                >
                  <div className="flex flex-col gap-1 pb-3 pl-1">
                    {entry.children.map((child) => (
                      <Link
                        key={child.href}
                        to={to(child.href)}
                        className="py-2 font-body text-lg text-foreground/70 hover:text-primary transition-colors duration-200"
                      >
                        {child.label}
                        <span className="block text-xs text-muted-foreground">{child.desc}</span>
                      </Link>
                    ))}
                  </div>
                </div>
              </div>
            );
          })}
          {/* Contacto — acceso directo, no es navegación de descubrimiento */}
          <Link
            to={to('/contact')}
            className="font-heading text-3xl font-bold text-foreground/80 hover:text-primary transition-colors duration-200 py-3"
          >
            {t.contact}
          </Link>
        </nav>

        {/* Accesos de cuenta (móvil) — plataforma canónica en pestaña nueva */}
        <div className="px-8 pb-8 flex flex-col gap-3">
          <a
            href={REGISTER_URL}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center justify-center gap-2 font-heading font-bold uppercase tracking-widest text-sm py-4 rounded-sm transition-opacity duration-200 hover:opacity-90"
            style={{ color: 'hsl(var(--primary-foreground))', background: 'hsl(var(--primary))', letterSpacing: '0.18em' }}
          >
            {t.register}
            <ArrowUpRight size={18} aria-hidden="true" />
          </a>
          <a
            href={LOGIN_URL}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center justify-center gap-2 font-body text-base py-3 rounded-sm border border-border text-foreground/80 hover:text-foreground hover:border-primary transition-colors duration-200"
          >
            <LogIn size={17} aria-hidden="true" />
            {t.login}
          </a>
          {/* Conmutador de idioma (móvil) */}
          <LanguageSwitcher variant="full" />
          <p className="text-xs text-muted-foreground font-body text-center mt-2">© 2026 Gano Digital</p>
        </div>
      </div>
    </>
  );
}
