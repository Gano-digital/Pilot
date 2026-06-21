import type { Locale } from '@/lib/i18n';

/**
 * Contenido de navegación bilingüe. Las `href` son SIEMPRE rutas canónicas en
 * español (sin prefijo); el Header las prefija con `localizedPath` según el
 * locale activo. Así el mapa de rutas vive en un único sitio (i18n.ts) y aquí
 * solo se traducen las etiquetas visibles.
 */
export type NavChild = { label: string; href: string; desc: string };
export type NavEntry = { label: string; href?: string; children?: NavChild[] };

type NavStrings = {
  entries: NavEntry[];
  contact: string;
  login: string;
  register: string;
  brandHome: string;
  openMenu: string;
  closeMenu: string;
  toLight: string;
  toDark: string;
  langSwitchTo: string; // texto del conmutador hacia el OTRO idioma
};

const ES: NavStrings = {
  entries: [
    { label: 'Planes', href: '/catalogo' },
    {
      label: 'Soluciones',
      children: [
        { label: 'IA aplicada', href: '/soluciones-ia', desc: 'Agentes y automatización con criterio' },
        { label: 'Seguridad', href: '/seguridad', desc: 'SSL, WAF, backups y anti-phishing' },
        { label: 'Servicios', href: '/services', desc: 'Hosting, correo y productividad' },
      ],
    },
    {
      label: 'Estudio',
      children: [
        { label: 'Diseño', href: '/disenos', desc: 'Ecosistemas a medida, SOTA' },
        { label: 'Portafolio', href: '/portfolio', desc: 'Trabajo seleccionado' },
        { label: 'Cómo contratas', href: '/proceso', desc: 'El proceso, paso a paso' },
      ],
    },
    {
      label: 'Recursos',
      children: [
        { label: 'Aprende', href: '/aprende', desc: 'Sala de lectura e IA aplicada' },
        { label: 'Filosofía', href: '/filosofia', desc: 'Cómo pensamos la tecnología' },
      ],
    },
    { label: 'Nosotros', href: '/about' },
  ],
  contact: 'Contacto',
  login: 'Iniciar sesión',
  register: 'Crear cuenta',
  brandHome: 'Gano Digital — inicio',
  openMenu: 'Abrir menú',
  closeMenu: 'Cerrar menú',
  toLight: 'Activar modo claro',
  toDark: 'Activar modo oscuro',
  langSwitchTo: 'EN',
};

/**
 * Versión en inglés. Las entradas sin par traducido todavía (Estudio, Recursos,
 * Nosotros) apuntan a sus rutas en español: `localizedPath` las dejará tal cual
 * porque no están en el mapa de traducción, de modo que el usuario en inglés
 * aterriza en la versión española de esas páginas (mejor que un enlace muerto),
 * y el conmutador de idioma seguirá funcionando por página.
 */
const EN: NavStrings = {
  entries: [
    { label: 'Plans', href: '/catalogo' },
    {
      label: 'Solutions',
      children: [
        { label: 'Applied AI', href: '/soluciones-ia', desc: 'Agents and automation, done with judgment' },
        { label: 'Security', href: '/seguridad', desc: 'SSL, WAF, backups and anti-phishing' },
        { label: 'Services', href: '/services', desc: 'Hosting, email and productivity' },
      ],
    },
    {
      label: 'Studio',
      children: [
        { label: 'Design', href: '/disenos', desc: 'Bespoke ecosystems, state of the art' },
        { label: 'Portfolio', href: '/portfolio', desc: 'Selected work' },
        { label: 'How it works', href: '/proceso', desc: 'The process, step by step' },
      ],
    },
    {
      label: 'Resources',
      children: [
        { label: 'Learn', href: '/aprende', desc: 'Reading room and applied AI' },
        { label: 'Philosophy', href: '/filosofia', desc: 'How we think about technology' },
      ],
    },
    { label: 'About', href: '/about' },
  ],
  contact: 'Contact',
  login: 'Log in',
  register: 'Sign up',
  brandHome: 'Gano Digital — home',
  openMenu: 'Open menu',
  closeMenu: 'Close menu',
  toLight: 'Switch to light mode',
  toDark: 'Switch to dark mode',
  langSwitchTo: 'ES',
};

export function navStrings(locale: Locale): NavStrings {
  return locale === 'en' ? EN : ES;
}
