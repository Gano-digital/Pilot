import { useEffect, useState } from 'react';

import { Button } from '@/components/ui/button';

const COOKIE_CONSENT_KEY = 'c2_analytics_consent';
const COOKIE_CONSENT_EXPIRES_DAYS = 365;

interface CookieConsent {
  analytics: boolean;
  timestamp: number;
}

declare global {
  interface Window {
    _signalsDataLayer?: unknown[];
    revokeAnalyticsConsent?: () => void;
    __SCC_INIT__?: boolean;
  }
}

// Inline C2 tracking - loads script and tracks clicks/pageviews
function initC2Tracking(): void {
  if (typeof window === 'undefined' || window.__SCC_INIT__) return;
  window.__SCC_INIT__ = true;
  window._signalsDataLayer = window._signalsDataLayer || [];

  const track = (eid: string, type: string, label: string, props?: Record<string, unknown>) => {
    window._signalsDataLayer!.push({
      schema: 'add_event', version: 'v1',
      data: { eid, type, event_label: label, custom_properties: { ...props, timestamp: new Date().toISOString(), source: 'airo-app-builder' } }
    });
  };

  const getSection = (el: HTMLElement): string => {
    if (el.closest('header')) return 'header';
    if (el.closest('footer')) return 'footer';
    if (el.closest('nav')) return 'nav';
    if (el.closest('main')) return 'main';
    return 'page';
  };

  const getDevice = (): string => {
    const w = window.innerWidth;
    return w < 768 ? 'mobile' : w < 1024 ? 'tablet' : 'desktop';
  };

  // Initial events
  track('airo.website.session', 'session', 'start', { page_path: location.pathname, referrer: document.referrer });
  track('airo.website.pageview', 'pageview', document.title, { page_path: location.pathname, referrer: document.referrer });

  // Click tracking
  // Capture phase (true) ensures we track clicks even if event.stopPropagation() is called
  document.addEventListener('click', (e) => {
    const el = (e.target as HTMLElement)?.closest('a, button, [role="button"]') as HTMLElement;
    if (!el) return;
    const text = el.textContent?.trim()?.substring(0, 100) || '';
    const href = (el as HTMLAnchorElement).href || '';
    const type = el.tagName.toLowerCase() === 'a' ? 'link' : 'button';

    let isExternal: boolean | undefined;
    if (href) {
      try {
        isExternal = new URL(href, location.origin).origin !== location.origin;
      } catch {
        // Malformed URL, treat as internal
      }
    }

    track('airo.website.click', 'click', text || type, {
      element_type: type,
      element_text: text,
      element_id: el.id || undefined,
      section: getSection(el),
      page_path: location.pathname,
      page_title: document.title,
      href: href || undefined,
      is_external: href ? isExternal : undefined,
      device: getDevice(),
      viewport_width: window.innerWidth
    });
  }, true);

  // Route tracking
  let lastUrl = location.href;
  const trackPage = () => {
    if (location.href !== lastUrl) {
      track('airo.website.pageview', 'pageview', document.title, { page_path: location.pathname, referrer: lastUrl });
      lastUrl = location.href;
    }
  };
  window.addEventListener('popstate', trackPage);
  const push = history.pushState, replace = history.replaceState;
  history.pushState = (...args) => { push.apply(history, args); setTimeout(trackPage, 0); };
  history.replaceState = (...args) => { replace.apply(history, args); setTimeout(trackPage, 0); };

  // Load SCC script
  const h = location.hostname;
  const url = h === 'localhost' || h.includes('dev-airoapp')
    ? 'https://img1.dev-wsimg.com/signals/js/clients/scc-c2/scc-c2.js'
    : h.includes('test-airoapp')
      ? 'https://img1.test-wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js'
      : 'https://img1.wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js';
  const script = document.createElement('script');
  script.src = url;
  script.async = true;
  document.head.appendChild(script);
}

/**
 * Cookie banner component for C2 analytics consent
 *
 * Displays a consent banner for C2 analytics tracking. Manages user consent
 * preferences in localStorage and controls whether analytics scripts are loaded.
 */
export default function CookieBanner() {
  const [showBanner, setShowBanner] = useState(false);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(function checkConsent() {
    if (typeof window === 'undefined') return;

    const consentData = localStorage.getItem(COOKIE_CONSENT_KEY);

    if (!consentData) {
      setShowBanner(true);
      setIsLoaded(true);
      return;
    }

    try {
      const consent: CookieConsent = JSON.parse(consentData);
      const daysSinceConsent = (Date.now() - consent.timestamp) / (1000 * 60 * 60 * 24);

      if (daysSinceConsent > COOKIE_CONSENT_EXPIRES_DAYS) {
        localStorage.removeItem(COOKIE_CONSENT_KEY);
        setShowBanner(true);
      } else if (consent.analytics) {
        initC2Tracking();
      }
    } catch {
      localStorage.removeItem(COOKIE_CONSENT_KEY);
      setShowBanner(true);
    }

    setIsLoaded(true);
  }, []);

  function saveConsent(analytics: boolean) {
    localStorage.setItem(COOKIE_CONSENT_KEY, JSON.stringify({ analytics, timestamp: Date.now() }));
    window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: analytics } }));
    if (analytics) initC2Tracking();
    setShowBanner(false);
  }

  function revokeConsent() {
    if (typeof window === 'undefined') return;
    localStorage.removeItem(COOKIE_CONSENT_KEY);
    window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: { consented: false } }));
    setShowBanner(true);
  }

  useEffect(function exposeRevokeFunction() {
    if (typeof window === 'undefined') return;
    window.revokeAnalyticsConsent = revokeConsent;
    return () => { delete window.revokeAnalyticsConsent; };
  }, []);

  if (!isLoaded || !showBanner) return null;

  return (
    <div
      className="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg"
      role="alertdialog"
      aria-live="polite"
      aria-label="Cookie consent banner"
      aria-describedby="cookie-banner-description"
      data-airo-non-editable
    >
      <div className="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div className="flex-1">
            <h3 className="text-sm font-semibold text-gray-900 mb-1">Cookie Consent</h3>
            <p id="cookie-banner-description" className="text-sm text-gray-600">
              We serve cookies. We use tools, such as cookies, to enable essential services and functionality on our site and to collect data on how visitors interact with our site, products and services. By clicking Accept, you agree to our use of these tools for advertising, analytics and support.
            </p>
          </div>
          <div className="flex items-center gap-3 flex-shrink-0">
            <Button size="sm" variant="secondary" onClick={() => saveConsent(false)} className="whitespace-nowrap">Decline</Button>
            <Button size="sm" onClick={() => saveConsent(true)} className="whitespace-nowrap" autoFocus>Accept</Button>
          </div>
        </div>
      </div>
    </div>
  );
}
