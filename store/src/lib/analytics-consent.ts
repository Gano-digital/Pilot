const CONSENT_KEY = 'c2_analytics_consent';

interface ConsentData {
  analytics: boolean;
  timestamp: number;
}

function parseConsent(raw: string | null): ConsentData | null {
  if (!raw) return null;
  try {
    return JSON.parse(raw) as ConsentData;
  } catch {
    return null;
  }
}

/**
 * Returns true if the visitor has accepted analytics cookies.
 * Reads the stored consent set by CookieBanner.
 */
export function getAnalyticsConsent(): boolean {
  if (typeof localStorage === 'undefined') return false;
  const consent = parseConsent(localStorage.getItem(CONSENT_KEY));
  return consent?.analytics === true;
}

/**
 * Registers a callback that fires whenever the visitor accepts or declines
 * the cookie banner. Returns a cleanup function to remove the listener.
 *
 * @example
 * const cleanup = onConsentChange((consented) => {
 *   if (consented) initMyTracker();
 * });
 * // Later: cleanup();
 */
export function onConsentChange(callback: (consented: boolean) => void): () => void {
  function handler(event: Event): void {
    const e = event as CustomEvent<{ consented: boolean }>;
    callback(e.detail.consented);
  }
  window.addEventListener('cookie-consent-changed', handler);
  return () => window.removeEventListener('cookie-consent-changed', handler);
}
