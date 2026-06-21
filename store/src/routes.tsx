import { RouteObject } from 'react-router-dom';
import { lazy } from 'react';
import HomePage from './pages/index';
import ProdNotFoundPage from './pages/_404';

const NotFoundPage = import.meta.env.DEV
  ? lazy(() => import('../dev-tools/src/PageNotFound'))
  : ProdNotFoundPage;

const PortfolioPage = lazy(() => import('./pages/portfolio'));
const AboutPage = lazy(() => import('./pages/about'));
const ServicesPage = lazy(() => import('./pages/services'));
const DisenosPage = lazy(() => import('./pages/disenos'));
const CatalogoPage = lazy(() => import('./pages/catalogo'));
const ProcesoPage = lazy(() => import('./pages/proceso'));
const ContactPage = lazy(() => import('./pages/contact'));
const AprendePage = lazy(() => import('./pages/aprende'));
const SolucionesIaPage = lazy(() => import('./pages/soluciones-ia'));
const SeguridadPage = lazy(() => import('./pages/seguridad'));
const FilosofiaPage = lazy(() => import('./pages/filosofia'));
const TerminosPage = lazy(() => import('./pages/terminos'));
const ReembolsosPage = lazy(() => import('./pages/reembolsos'));
const PrivacidadPage = lazy(() => import('./pages/privacidad'));
const GamePage = lazy(() => import('./pages/game'));

// ── Páginas en inglés (prefijo /en) — el español sin prefijo queda intacto ──
const HomePageEn = lazy(() => import('./pages/en/index'));
const CatalogoPageEn = lazy(() => import('./pages/en/catalogo'));
const ServicesPageEn = lazy(() => import('./pages/en/services'));
const SolucionesIaPageEn = lazy(() => import('./pages/en/soluciones-ia'));
const SeguridadPageEn = lazy(() => import('./pages/en/seguridad'));
const ContactPageEn = lazy(() => import('./pages/en/contact'));

export const routes: RouteObject[] = [
  { path: '/', element: <HomePage /> },
  { path: '/portfolio', element: <PortfolioPage /> },
  { path: '/about', element: <AboutPage /> },
  { path: '/services', element: <ServicesPage /> },
  { path: '/disenos', element: <DisenosPage /> },
  { path: '/catalogo', element: <CatalogoPage /> },
  { path: '/proceso', element: <ProcesoPage /> },
  { path: '/aprende', element: <AprendePage /> },
  { path: '/soluciones-ia', element: <SolucionesIaPage /> },
  { path: '/filosofia', element: <FilosofiaPage /> },
  { path: '/seguridad', element: <SeguridadPage /> },
  { path: '/terminos', element: <TerminosPage /> },
  { path: '/reembolsos', element: <ReembolsosPage /> },
  { path: '/privacidad', element: <PrivacidadPage /> },
  { path: '/contact', element: <ContactPage /> },
  { path: '/game', element: <GamePage /> },

  // ── Inglés (/en) ── español sin prefijo intacto; estas son URLs propias e indexables
  { path: '/en', element: <HomePageEn /> },
  { path: '/en/catalogo', element: <CatalogoPageEn /> },
  { path: '/en/services', element: <ServicesPageEn /> },
  { path: '/en/soluciones-ia', element: <SolucionesIaPageEn /> },
  { path: '/en/seguridad', element: <SeguridadPageEn /> },
  { path: '/en/contact', element: <ContactPageEn /> },

  { path: '*', element: <NotFoundPage /> },
];

export type Path = '/' | '/portfolio' | '/about' | '/services' | '/disenos' | '/catalogo' | '/proceso' | '/aprende' | '/soluciones-ia' | '/filosofia' | '/seguridad' | '/terminos' | '/reembolsos' | '/privacidad' | '/contact' | '/game' | '/en' | '/en/catalogo' | '/en/services' | '/en/soluciones-ia' | '/en/seguridad' | '/en/contact';
export type Params = Record<string, string | undefined>;
