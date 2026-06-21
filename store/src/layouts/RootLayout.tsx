import { Helmet } from '@dr.pogodin/react-helmet';
import { type ReactElement } from 'react';
import { ScrollRestoration } from 'react-router-dom';

import Footer from '@/layouts/parts/Footer';
import Header from '@/layouts/parts/Header';
import Website from '@/layouts/Website';
import { ThemeProvider } from '@/components/ThemeProvider';
import { LanguageAutoSuggest } from '@/components/LanguageAutoSuggest';
import { AsesorWidget } from '@/components/AsesorWidget';

/**
 * Root layout component that wraps all pages with consistent header and footer.
 *
 * To customize the header or footer, directly edit the Header.tsx and Footer.tsx
 * files in the layouts/parts directory.
 *
 * Site-wide <title> and <meta> live in the <Helmet> below. Individual pages can
 * override them by rendering their own <Helmet> — last-mounted wins.
 */
interface RootLayoutProps {
  children: ReactElement;
}

export default function RootLayout({ children }: RootLayoutProps) {
  return (
    <ThemeProvider>
      <Website>
        <Helmet>
          <title>Gano Digital — Hosting, Dominios y Seguridad con Ingeniería Curada</title>
          <meta name="description" content="Gano Digital es tu infraestructura digital soberana: hosting WordPress de alto rendimiento, dominios, correo profesional y seguridad. Planes claros, soporte real en Colombia." />
          <html lang="es" />
        </Helmet>
        <ScrollRestoration />
        <Header />
        {children}
        <Footer />
        <LanguageAutoSuggest />
        <AsesorWidget />
      </Website>
    </ThemeProvider>
  );
}
