/**
 * brand-logo — fuente única de verdad para las dos variantes del logo.
 *
 * El sistema de logos genera, para un mismo mark, una versión transparente
 * (trazos oscuros/terracota, pensada para fondo CLARO) y una versión "reversed"
 * (trazos claros/terracota, pensada para fondo OSCURO). Aquí exponemos AMBAS
 * para que <BrandLogo> elija según el tema activo y haga un fundido entre ellas.
 *
 * - `light`  → se usa cuando el tema es claro (lienzo crema): slot estándar.
 * - `dark`   → se usa cuando el tema es oscuro (lienzo #0A0A0A).
 *
 * La variante clara usa el slot del sistema (`/airo-assets/images/logo/horizontal`)
 * para que el usuario pueda intercambiar el logo sin tocar código. La variante
 * oscura apunta a la versión reversed resuelta del MISMO mark.
 */

/** Variante clara — slot del sistema (intercambiable por el usuario). */
export const LOGO_LIGHT_SRC = '/airo-assets/images/logo/horizontal';

/**
 * Variante oscura — versión "reversed" (trazos claros) del mismo mark,
 * derivada del sistema de logos. Resuelta del slot logo/horizontal.
 */
export const LOGO_DARK_SRC =
  'https://isteam.wsimg.com/genai-assistant/logoagent/customer/3adde9e1-061c-446d-845b-0580e89ce529/session/fdc182d6-4fc5-4b57-893b-3790853a045f/horizontal-reversed-30ee3fe632f57efa537c5e83814a700c/logo-logo-30ee3f.png';

export const LOGO_ALT = 'Gano Digital';
