import React from 'react';
import { cn } from '@/lib/utils';

/**
 * Website layout configuration options
 *
 * Defines the structural layout options for the Website component.
 * Note: Header and Footer are now typically managed by RootLayout in App.tsx.
 */
export interface WebsiteConfig {
  layout?: {
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'full';
    padding?: 'none' | 'sm' | 'md' | 'lg';
    background?: 'default' | 'muted' | 'gradient';
    minHeight?: boolean;
  };
}

interface WebsiteProps {
  children: React.ReactNode;
  config?: WebsiteConfig;
  className?: string;
}

/**
 * Website layout component
 *
 * Provides the structural container for website pages with configurable layout options.
 * This is a lower-level component - for most applications, use RootLayout instead,
 * which wraps this component and provides centralized header/footer management.
 *
 * @param children - Page content to render (can include Header, Footer, and main content)
 * @param config - Layout configuration options
 * @param className - Additional CSS classes
 *
 * @example
 * ```tsx
 * // Typical usage with RootLayout (recommended)
 * <RootLayout config={{ header: {...}, footer: {...} }}>
 *   <YourPage />
 * </RootLayout>
 *
 * // Direct usage (advanced)
 * <Website config={{ layout: { background: 'gradient' } }}>
 *   <Header config={headerConfig} />
 *   <main>Your content</main>
 *   <Footer config={footerConfig} />
 * </Website>
 * ```
 */
export default function Website({
  children,
  config = {},
  className
}: WebsiteProps) {
  const {
    layout = {
      maxWidth: 'full',
      padding: 'md',
      background: 'default',
      minHeight: true
    }
  } = config;

  const getBackgroundClass = () => {
    switch (layout.background) {
      case 'muted': return 'bg-muted';
      case 'gradient': return 'bg-gradient-to-b from-background to-muted/20';
      default: return 'bg-background';
    }
  };

  return (
    <div className={cn(
      layout.minHeight !== false && "min-h-screen",
      getBackgroundClass(),
      "flex flex-col",
      className
    )}>
      {children}
    </div>
  );
}
