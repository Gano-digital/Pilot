import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';

const CONSENT_KEY = 'c2_analytics_consent';

describe('analytics-consent', () => {
  beforeEach(() => {
    localStorage.clear();
    vi.resetModules();
  });

  afterEach(() => {
    vi.unstubAllEnvs();
  });

  async function loadModule(): Promise<typeof import('../../lib/analytics-consent')> {
    return import('../../lib/analytics-consent');
  }

  describe('getAnalyticsConsent', () => {
    it('returns false when no consent is stored', async () => {
      const { getAnalyticsConsent } = await loadModule();
      expect(getAnalyticsConsent()).toBe(false);
    });

    it('returns true when analytics consent is stored', async () => {
      localStorage.setItem(CONSENT_KEY, JSON.stringify({ analytics: true, timestamp: Date.now() }));
      const { getAnalyticsConsent } = await loadModule();
      expect(getAnalyticsConsent()).toBe(true);
    });

    it('returns false when analytics is declined', async () => {
      localStorage.setItem(CONSENT_KEY, JSON.stringify({ analytics: false, timestamp: Date.now() }));
      const { getAnalyticsConsent } = await loadModule();
      expect(getAnalyticsConsent()).toBe(false);
    });

    it('returns false when stored value is invalid JSON', async () => {
      localStorage.setItem(CONSENT_KEY, 'not-json');
      const { getAnalyticsConsent } = await loadModule();
      expect(getAnalyticsConsent()).toBe(false);
    });
  });

  describe('onConsentChange', () => {
    it('fires callback when cookie-consent-changed event is dispatched', async () => {
      const { onConsentChange } = await loadModule();
      const callback = vi.fn();

      onConsentChange(callback);
      window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: true } }));

      expect(callback).toHaveBeenCalledWith(true);
    });

    it('fires callback with false on decline', async () => {
      const { onConsentChange } = await loadModule();
      const callback = vi.fn();

      onConsentChange(callback);
      window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: false } }));

      expect(callback).toHaveBeenCalledWith(false);
    });

    it('cleanup function removes listener', async () => {
      const { onConsentChange } = await loadModule();
      const callback = vi.fn();

      const cleanup = onConsentChange(callback);
      cleanup();
      window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: true } }));

      expect(callback).not.toHaveBeenCalled();
    });
  });

  describe('consent-gated widget integration', () => {
    it('widget loads when consent is already granted', async () => {
      localStorage.setItem(CONSENT_KEY, JSON.stringify({ analytics: true, timestamp: Date.now() }));
      const { getAnalyticsConsent } = await loadModule();

      const loadWidget = vi.fn();
      if (getAnalyticsConsent()) loadWidget();

      expect(loadWidget).toHaveBeenCalled();
    });

    it('widget loads after consent is granted via event', async () => {
      const { getAnalyticsConsent, onConsentChange } = await loadModule();

      const loadWidget = vi.fn();
      if (getAnalyticsConsent()) loadWidget();
      onConsentChange((consented) => { if (consented) loadWidget(); });

      expect(loadWidget).not.toHaveBeenCalled();

      window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: true } }));

      expect(loadWidget).toHaveBeenCalledTimes(1);
    });

    it('widget does not load when consent is declined', async () => {
      const { getAnalyticsConsent, onConsentChange } = await loadModule();

      const loadWidget = vi.fn();
      if (getAnalyticsConsent()) loadWidget();
      onConsentChange((consented) => { if (consented) loadWidget(); });

      window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: false } }));

      expect(loadWidget).not.toHaveBeenCalled();
    });
  });
});
