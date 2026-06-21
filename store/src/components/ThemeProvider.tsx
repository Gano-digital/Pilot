import { createContext, useContext, useEffect, useState, useCallback, type ReactNode } from 'react';

export type Theme = 'light' | 'dark';

const STORAGE_KEY = 'gano-theme';

interface ThemeContextValue {
  theme: Theme;
  toggleTheme: () => void;
  setTheme: (theme: Theme) => void;
}

const ThemeContext = createContext<ThemeContextValue | undefined>(undefined);

/**
 * Lee el tema inicial. El modo CLARO es el defecto del sitio: solo devolvemos
 * 'dark' si el usuario lo eligió explícitamente y lo guardamos en localStorage.
 * No autoseguimos prefers-color-scheme para que la experiencia por defecto sea
 * siempre la versión clara (que suaviza la transición hacia el escaparate).
 *
 * En SSR (sin window) devolvemos 'light' — coincide con el HTML servido, que
 * no trae la clase .dark salvo que el script anti-parpadeo del head la añada.
 */
function getInitialTheme(): Theme {
  if (typeof window === 'undefined') return 'light';
  try {
    return localStorage.getItem(STORAGE_KEY) === 'dark' ? 'dark' : 'light';
  } catch {
    return 'light';
  }
}

export function ThemeProvider({ children }: { children: ReactNode }) {
  const [theme, setThemeState] = useState<Theme>(getInitialTheme);

  // Sincroniza la clase .dark en <html> y persiste la elección.
  useEffect(() => {
    const root = document.documentElement;
    if (theme === 'dark') {
      root.classList.add('dark');
    } else {
      root.classList.remove('dark');
    }
    try {
      localStorage.setItem(STORAGE_KEY, theme);
    } catch {
      /* almacenamiento no disponible — el tema sigue funcionando en memoria */
    }
  }, [theme]);

  const setTheme = useCallback((next: Theme) => setThemeState(next), []);
  const toggleTheme = useCallback(
    () => setThemeState((prev) => (prev === 'dark' ? 'light' : 'dark')),
    [],
  );

  return (
    <ThemeContext.Provider value={{ theme, toggleTheme, setTheme }}>
      {children}
    </ThemeContext.Provider>
  );
}

export function useTheme(): ThemeContextValue {
  const ctx = useContext(ThemeContext);
  if (!ctx) throw new Error('useTheme debe usarse dentro de <ThemeProvider>');
  return ctx;
}
