/**
 * Catálogo de servicios Gano Digital (reseller GoDaddy, plid=599667).
 * Datos reales — briefing junio 2026.
 *
 * NOTA SOBRE CHECKOUT (decisión de negocio):
 *  Los PFIDs son Product Family IDs de GoDaddy: un PFID cubre toda la familia
 *  de productos y el cliente elige el tier específico dentro del carrito.
 *  Confirmados vía SSH (catalog-sota-v2.js, 2026-06-14) y migrados a carrito:
 *    457  WordPress Managed   → wp-starter, pro-managed, business-nvme, ultimate, wp-deluxe
 *    459  Web Hosting Plus     → hosting-plus-dev
 *    75   SSL (todos)          → ssl-dv, ssl-pro
 *    466  Email / Microsoft 365 → email-starter, email-pro, email-godaddy-basic, microsoft-365-basic, microsoft-365-pro
 *    557  Seguridad Web / WAF  → security-advanced, security-premium
 *
 *  ESTRATEGIA DE CHECKOUT (decisión "Mixto", junio 2026):
 *   Servicios con PFID propio confirmado → carrito directo de GoDaddy (plid 599667).
 *   Servicios pendientes de PFID → enlazan al ESCAPARATE_URL (catálogo completo del
 *   reseller) para compra directa, EXCEPTO los VPS (alto margen) que van a WhatsApp
 *   para asesoría 1-a-1. Cuando se confirme un PFID en RCC, se asciende ese servicio
 *   del escaparate a carrito directo cambiando su buyUrl a cartUrl(PFID.X).
 *
 *  Pendientes de PFID (hoy → escaparate, salvo VPS → WhatsApp):
 *    websiteBuilder   → escaparate (creador de páginas web)
 *    codeguardBackup  → escaparate (backup)
 *    hostingBasico    → (definido pero sin tarjeta activa)
 *    emailMarketing   → escaparate (email marketing)
 *    vpsHighPerf      → WhatsApp (alto margen, asesoría 1-a-1)
 *
 *  Para migrar: RCC → Catálogo → buscar producto → copiar PFID de la URL
 *  → actualizar PFID.websiteBuilder / PFID.codeguardBackup / PFID.hostingBasico en este archivo.
 *
 *  Servicios Gano a medida (vps-alpha, diagnostico, disenio-custom) → WhatsApp
 *  intencional: no son SKU directo de GoDaddy.
 *
 *  Dominios → buyUrl '/catalogo'; el DomainSearch hace su propio form POST a
 *  GoDaddy (no requiere PFID).
 */

/** plid público del reseller Gano Digital — seguro en cliente. */
export const RESELLER_PLID = 599667;

/**
 * Escaparate completo de GoDaddy (catálogo storefront del reseller).
 * URL canónica confirmada por el dueño. Es el "catálogo completo" de respaldo:
 * cubre productos que aún no se han curado en gano.digital. Se usa como destino
 * de compra para servicios cuyo PFID propio aún no se ha verificado en RCC
 * (decisión de negocio "Mixto": los de alto margen —VPS— se quedan en WhatsApp
 * para asesoría 1-a-1; el resto enlaza al escaparate para compra directa).
 */
export const ESCAPARATE_URL = `https://www.secureserver.net/?pl_id=${RESELLER_PLID}`;

/**
 * URL del escaparate, opcionalmente con un término de búsqueda pre-cargado
 * para acercar al cliente al producto correcto dentro del storefront.
 */
export function escaparateUrl(search?: string): string {
  if (!search) return ESCAPARATE_URL;
  return `${ESCAPARATE_URL}&search=${encodeURIComponent(search)}`;
}

/* ────────────────────────────────────────────────────────────────────
 * PIPELINE CANÓNICO — informar → familiarizarse → registro → compra
 *
 * Canibalizamos las funciones canónicas del reseller (storefront GoDaddy)
 * sin perder nuestra narrativa. Tres rutas SSO de la plataforma:
 *   - REGISTER_URL : crear cuenta (registro del cliente)
 *   - LOGIN_URL    : iniciar sesión (clientes existentes)
 *   - HELP_URL     : centro de ayuda canónico
 *
 * Y un mapa de páginas de producto canónicas (/products/*) que sirven a la
 * vez para (a) navegar/familiarizarse con un producto sin presión de compra
 * y (b) comprar productos cuyo PFID propio aún no tenemos. Es el fallback
 * lógico de checkout para servicios sin botón de carrito directo.
 * ──────────────────────────────────────────────────────────────────── */

/** Registro de cuenta en la plataforma canónica (SSO secureserver). */
export const REGISTER_URL =
  `https://sso.secureserver.net/account/create?app=account&path=%2Fproducts%2Fprofessional-email&plid=${RESELLER_PLID}&prog_id=${RESELLER_PLID}&realm=idp`;

/** Inicio de sesión en la plataforma canónica (SSO secureserver). */
export const LOGIN_URL =
  `https://sso.secureserver.net/?app=account&path=%2Fproducts%2Fbusiness&plid=${RESELLER_PLID}&prog_id=${RESELLER_PLID}&realm=idp&referrer=sso`;

/** Centro de ayuda canónico del reseller. */
export const HELP_URL =
  `https://www.secureserver.net/help?pl_id=${RESELLER_PLID}&prog_id=${RESELLER_PLID}`;

/** Base del storefront canónico de gano.digital (páginas de producto). */
const PRODUCTS_BASE = 'https://www.gano.digital/products';

/**
 * Slugs canónicos de página de producto (/products/{slug}). Cada servicio del
 * catálogo apunta a uno de estos vía `canonicalSlug`. Permiten "Explorar a
 * fondo" (navegar la ficha canónica) y son el destino de compra de fallback.
 */
export type CanonicalSlug =
  | 'website-builder'
  | 'business'
  | 'vps'
  | 'email-marketing'
  | 'microsoft-365'
  | 'seo'
  | 'domain-transfer'
  | 'website-security'
  | 'ssl'
  | 'ssl-managed'
  | 'website-backup';

/** Construye la URL canónica de una página de producto del storefront. */
export function productPageUrl(slug: CanonicalSlug): string {
  return `${PRODUCTS_BASE}/${slug}`;
}

/** PFIDs de familia confirmados (GoDaddy Product Family IDs). */
export const PFID = {
  wordpressManaged: 457,   // ✅ confirmado — WordPress Managed
  webHostingPlus: 459,     // ✅ confirmado — Web Hosting Plus
  ssl: 75,                 // ✅ confirmado — SSL (todos los tiers)
  email: 466,              // ✅ confirmado — Email / Microsoft 365
  webSecurityWaf: 557,     // ✅ confirmado — Seguridad Web / WAF
  // ── PENDIENTES: verificar en RCC → Catálogo → buscar producto → ver PFID en URL ──
  websiteBuilder: 0,       // ⚠️ PENDIENTE — Website Builder (Websites + Marketing)
  codeguardBackup: 0,      // ⚠️ PENDIENTE — CodeGuard Backup
  hostingBasico: 0,        // ⚠️ PENDIENTE — Web Hosting básico (Economy/Deluxe)
  emailMarketing: 0,       // ⚠️ PENDIENTE — Email Marketing (Principiante/Establecido/Pro)
  vpsHighPerf: 0,          // ⚠️ PENDIENTE (PRIORIDAD ALTA) — VPS High Performance (todos los tiers)
} as const;

/**
 * Construye la URL de carrito de GoDaddy para una familia de productos.
 * Si el PFID es 0 (pendiente de verificación), devuelve WhatsApp como fallback.
 */
export function cartUrl(pfid: number): string {
  if (pfid === 0) return 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+asesor%C3%ADa';
  return `https://cart.secureserver.net/go/checkout?plid=${RESELLER_PLID}&pfid=${pfid}`;
}

export type IconHint =
  | 'cpu' | 'zap' | 'crown' | 'sprout' | 'server' | 'globe'
  | 'shield' | 'shield-check' | 'lock' | 'database' | 'mail'
  | 'at-sign' | 'paintbrush' | 'layers' | 'code' | 'stethoscope'
  | 'layout-dashboard';

export interface Service {
  id: string;
  category: string;
  name: string;
  shortDescription: string;
  longDescription: string;
  priceFrom: number;
  currency: string;
  billingPeriod: string;
  features: string[];
  buyUrl: string;
  iconHint: IconHint;
  badge?: string;
  /** Especificaciones técnicas opcionales (clave legible → valor). */
  specs?: Record<string, string>;
  /** Casos de uso ideales — alimentan PlanFinder y la tarjeta. */
  bestFor?: string[];
  /**
   * Casuística de aplicación — el "cuándo y por qué" de nuestro lado
   * informativo. Complementa (NO repite) lo que el cliente verá en la
   * página de compra: cuándo se justifica este servicio, qué necesidad lo
   * amerita y cómo se maneja en la práctica. Más profundo en los high-end.
   */
  useCase?: string;
  /**
   * Slug de la página de producto canónica (/products/{slug}). Habilita el
   * CTA secundario "Explorar a fondo" y es el destino de compra de fallback
   * para servicios sin PFID propio.
   */
  canonicalSlug?: CanonicalSlug;
}

/** Orden canónico de categorías para los filtros del catálogo. */
export const CATEGORY_ORDER: string[] = [
  'Hosting WordPress',
  'Constructor Web',
  'Dominios',
  'Email',
  'Marketing',
  'Seguridad',
  'Para Desarrolladores',
  'VPS & Cómputo',
  'Servicios Gano',
];

/** Formatea un precio en la moneda dada (COP → "$39.000"). */
export function formatPrice(value: number, currency: string): string {
  if (currency === 'COP') {
    return '$' + value.toLocaleString('es-CO');
  }
  return new Intl.NumberFormat('es-CO', { style: 'currency', currency }).format(value);
}

/** Etiqueta legible del periodo de cobro. */
export function billingLabel(period: string): string {
  switch (period) {
    case 'mensual': return '/mes';
    case 'anual': return '/año';
    case 'único pago': return 'único pago';
    case 'proyecto': return 'por proyecto';
    default: return `/${period}`;
  }
}

/** Tipo de destino al que apunta el botón de compra. */
export function buyKind(url: string): 'whatsapp' | 'domains' | 'escaparate' | 'checkout' {
  if (url.includes('wa.me')) return 'whatsapp';
  if (url === '/catalogo' || url === '/catalogo#dominios' || url.includes('gano.digital/dominios')) return 'domains';
  if (url.includes('secureserver.net/?pl_id=') || url.includes('secureserver.net/?')) return 'escaparate';
  return 'checkout';
}

export const SERVICES: Service[] = [
  // ── HOSTING WORDPRESS ──────────────────────────────────────────────
  {
    id: 'wp-starter',
    category: 'Hosting WordPress',
    name: 'WordPress Starter',
    shortDescription: 'Tu primera web WordPress lista en 5 minutos, sin tocar un servidor.',
    longDescription: 'Ideal si estás arrancando: WordPress ya viene instalado, el SSL se activa solo y las actualizaciones son automáticas. Sin configuraciones raras, sin código, sin sorpresas.',
    priceFrom: 15000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['1 sitio WordPress', '30 GB SSD NVMe', 'Hasta 25.000 visitas/mes', 'SSL gratuito auto-renovado', 'Actualizaciones WP automáticas', 'Staging incluido', 'Soporte 24/7'],
    specs: { php: 'PHP 8.x', cdn: 'CDN global incluido', backups: 'Diarios automáticos' },
    bestFor: ['Blog personal o de contenido', 'Portafolio de diseñador', 'Landing page de negocio local'],
    useCase: 'Lo eliges cuando tu prioridad es publicar, no administrar. Si vienes de un constructor cerrado o de un hosting compartido lento y quieres WordPress real —con su universo de plugins y temas— pero sin pelear con cPanel, este es el punto de partida. La regla práctica: mientras seas un solo sitio por debajo de ~25.000 visitas al mes, aquí estás cómodo; el día que necesites un entorno de pruebas serio o varios sitios, subes a Pro sin migrar a mano.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.wordpressManaged), // 457 — WordPress Managed (Starter)
    iconHint: 'sprout',
  },
  {
    id: 'pro-managed',
    category: 'Hosting WordPress',
    name: 'Pro Managed',
    shortDescription: 'Sitios multipágina con tráfico serio. Staging, CDN y cacheo de objetos.',
    longDescription: 'Cuando tu sitio empieza a recibir visitas reales y no puedes permitirte caídas los lunes. Ambiente de pruebas para que nada rompa en producción.',
    priceFrom: 39000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['75 GB NVMe', '3 sitios WP', 'Hasta 150.000 visitas/mes', 'Staging incluido', 'CDN Global 200+ POPs', 'Redis dedicado', 'Backups cada 12h · 30 días', '5 cuentas email'],
    specs: { cpu: '4 vCPU compartido', ram: '4 GB', io: 'NVMe Gen4 · 5.200 MB/s', sla: '99.95%' },
    bestFor: ['Sitio corporativo multipágina', 'Blog con tráfico creciente', 'Agencia con 2-3 clientes'],
    useCase: 'El plan al que se llega cuando el sitio ya factura o genera leads y una caída un lunes a las 9 a.m. cuesta dinero real. La diferencia frente a Starter no es solo potencia: es el flujo de trabajo. El entorno de staging te deja probar una actualización de plugin o un rediseño en una copia idéntica antes de tocar producción —el error más caro de WordPress es actualizar a ciegas en vivo—. Si manejas dos o tres sitios o un corporativo de varias páginas con tráfico creciente, este es el equilibrio.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.wordpressManaged), // 457 — WordPress Managed (Pro)
    iconHint: 'cpu',
  },
  {
    id: 'business-nvme',
    category: 'Hosting WordPress',
    name: 'Business NVMe',
    shortDescription: 'Velocidad crítica para WooCommerce de alta autoridad. SLA 99.95%.',
    longDescription: 'Construido para WooCommerce serio. Cada milisegundo ahorrado en checkout es un 1% más de conversión. Aquí ahorramos cientos.',
    priceFrom: 89000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['150 GB NVMe Gen4', '10 sitios WP', 'Hasta 500.000 visitas/mes', 'WAF Capa 7 activo', 'CDN Global Premium', 'Redis 1 GB dedicado', 'Backups cada 6h · 60 días', '20 cuentas email'],
    specs: { cpu: '8 vCPU prioritario', ram: '8 GB', io: 'NVMe Gen4 · 7.500 MB/s', sla: '99.95%' },
    bestFor: ['Tienda WooCommerce con 200+ productos', 'Marca con campañas pagas activas', 'Sitio con picos de tráfico'],
    useCase: 'Pensado para WooCommerce que vende de verdad. En una tienda, la velocidad no es estética: cada 100 ms que recortas del checkout se traduce en conversión medible, y aquí el NVMe Gen4 con Redis dedicado mueve el carrito y las consultas de catálogo sin que el sitio se arrodille en un Black Friday. El WAF de Capa 7 importa especialmente cuando procesas pagos: filtra inyecciones y bots antes de que toquen tu base de datos. La señal de que lo necesitas es simple — si pautas anuncios hacia la tienda, no puedes permitirte que la página caiga justo cuando llega el tráfico que pagaste.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.wordpressManaged), // 457 — WordPress Managed (Business NVMe)
    iconHint: 'zap',
    badge: 'Popular',
  },
  {
    id: 'ultimate',
    category: 'Hosting WordPress',
    name: 'Ultimate',
    shortDescription: 'Todo incluido: SSL wildcard, monitoreo proactivo y consultoría mensual.',
    longDescription: 'Para cuando tu operación digital ya no admite improvisación. Ingeniero SOTA asignado, revisión mensual y todo el ecosistema en bandeja.',
    priceFrom: 148000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['NVMe ilimitado', 'Sitios WP ilimitados', 'Tráfico sin tope · fair use', 'SSL Wildcard incluido', 'Consultoría 1h/mes · ingeniero SOTA', 'Monitoreo 24/7', 'Backups cada 4h · 90 días', 'Emails ilimitados anti-phishing'],
    specs: { cpu: '16 vCPU dedicado', ram: '16 GB', io: 'NVMe Gen4 · 7.500 MB/s dedicado', sla: '99.98%' },
    bestFor: ['Agencia con 10+ clientes', 'Marca corporativa con operación crítica', 'Ecosistema multi-tienda'],
    useCase: 'El tier que deja de ser "hosting" y pasa a ser un acuerdo de operación. Lo que compras aquí no es solo cómputo dedicado: es la hora mensual con un ingeniero SOTA que conoce tu arquitectura por nombre y revisa proactivamente lo que tú no tienes tiempo de mirar —cuellos de botella, plugins que pesan, picos anómalos—. Tiene sentido cuando administras un portafolio de clientes o una marca donde la web es la operación, no un folleto. Si todavía te preguntas si lo necesitas, probablemente aún no: este plan es para quien ya sabe exactamente por qué cada hora de downtime es inaceptable.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.wordpressManaged), // 457 — WordPress Managed (Ultimate)
    iconHint: 'crown',
    badge: 'Élite',
  },

  // ── CONSTRUCTOR WEB ────────────────────────────────────────────────
  {
    id: 'website-builder-plus',
    category: 'Constructor Web',
    name: 'Website Builder Plus',
    shortDescription: 'Tu sitio web en 30 minutos. Sin código, sin hosting técnico.',
    longDescription: 'Para negocios locales que necesitan presencia web pero no tienen equipo técnico. Arrastra, suelta, publica. Google Maps, WhatsApp Business y SEO básico desde el primer día.',
    priceFrom: 25000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['100+ diseños profesionales', 'Editor drag & drop', 'Dominio .com incluido 1er año', 'HTTPS automático', 'SEO básico incluido', 'Botón WhatsApp Business'],
    specs: { editor: 'Drag & Drop', hosting: 'Incluido', domain: '1 año gratis' },
    bestFor: ['Restaurantes y negocios locales', 'Profesionales independientes', 'Negocios que quieren estar en Google rápido'],
    useCase: 'La elección honesta cuando no tienes —ni quieres tener— equipo técnico y la web es un medio, no un fin. WordPress es más poderoso, pero también te pide mantenerlo; aquí el hosting, el SSL y las actualizaciones desaparecen del problema. Lo recomendamos para el negocio local que necesita estar en Google con su mapa, su horario y un botón de WhatsApp esta semana, no el mes que viene. Si más adelante el proyecto crece hacia algo que el editor no alcanza, migras a un WordPress gestionado con tu dominio intacto.',
    canonicalSlug: 'website-builder',
    buyUrl: escaparateUrl('creador de paginas web'), // → escaparate (Website Builder Personal) hasta confirmar PFID propio
    iconHint: 'paintbrush',
  },
  {
    id: 'builder-marketing',
    category: 'Constructor Web',
    name: 'Builder Comercial Plus',
    shortDescription: 'Constructor con SEO, redes sociales y citas en línea. Atrae más clientes.',
    longDescription: 'Para cuando necesitas atraer clientes en serio: diseño responsive, blog, SSL, botón de compra PayPal, SEO, integración con redes sociales y agendamiento de citas en línea.',
    priceFrom: 37809,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Diseño responsive móvil-first', 'Hosting + carga rápida incluidos', 'Crea un blog', 'Seguridad SSL', 'Botón de Compra/Donar con PayPal', 'Optimización para buscadores (SEO)', 'Integración con redes sociales', 'Citas en línea'],
    specs: { engine: 'GoDaddy Websites + Marketing', mobile: 'Responsive nativo', seo: 'SEO + redes integradas' },
    bestFor: ['Negocio que quiere atraer más clientes', 'Profesional con agendamiento de citas', 'Marca activa en redes sociales'],
    useCase: 'El paso natural cuando la presencia web ya no basta y necesitas que el sitio trabaje para conseguir clientes. La pieza que más mueve la aguja aquí es el agendamiento en línea: si eres consultor, estética, taller o cualquier negocio de cita previa, dejar que el cliente reserve solo —sin ida y vuelta por WhatsApp— recupera horas cada semana. Súmale el SEO integrado y la conexión con redes, y tienes un canal de captación, no solo una tarjeta de presentación digital.',
    canonicalSlug: 'website-builder',
    buyUrl: escaparateUrl('creador de paginas web marketing'), // → escaparate (Comercial Plus) hasta confirmar PFID propio
    iconHint: 'paintbrush',
    badge: 'Con marketing',
  },
  {
    id: 'builder-online-store',
    category: 'Constructor Web',
    name: 'Builder Tienda en Línea',
    shortDescription: 'Vende productos físicos y digitales con carrito, pagos y envíos.',
    longDescription: 'El constructor más completo: todo lo de Comercial Plus más carrito de compras incorporado, venta de productos físicos y digitales, tarjetas de crédito/débito y PayPal, opciones de envío flexibles, descuentos y administración de inventario.',
    priceFrom: 70569,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Diseño responsive + carga rápida', 'Blog · SSL · SEO incluidos', 'Carrito de compras incorporado', 'Vende productos físicos y digitales', 'Acepta tarjetas de crédito/débito y PayPal', 'Opciones de envío flexible', 'Descuentos y promociones', 'Administra inventario'],
    specs: { engine: 'GoDaddy Websites + Marketing', ecommerce: 'Carrito + pagos + inventario', shipping: 'Envío flexible' },
    bestFor: ['Tienda que vende en línea sin equipo técnico', 'Negocio con productos físicos y digitales', 'Emprendedor que quiere e-commerce ya'],
    useCase: 'Para vender en línea cuanto antes sin contratar un desarrollador. La frontera práctica con un WooCommerce sobre Business NVMe es esta: aquí ganas tiempo y simplicidad —carrito, pagos, inventario y envíos listos de fábrica— a cambio de menos control fino sobre el catálogo y las integraciones. Es la decisión correcta para un emprendedor con decenas de productos que prioriza lanzar y vender sobre personalizar. Cuando el inventario o las reglas de negocio se vuelvan complejas, esa será la señal para migrar a una tienda WordPress a medida.',
    canonicalSlug: 'website-builder',
    buyUrl: escaparateUrl('tienda en linea'), // → escaparate (Tienda en línea) hasta confirmar PFID propio
    iconHint: 'paintbrush',
    badge: 'E-commerce',
  },

  // ── DOMINIOS ───────────────────────────────────────────────────────
  {
    id: 'dom-co',
    category: 'Dominios',
    name: 'Dominio .co',
    shortDescription: 'Presencia colombiana de máxima autoridad. WHOIS incluido.',
    longDescription: "El TLD que grita 'hecho en Colombia'. Viene con privacidad WHOIS, DNS premium y SSL básico de serie.",
    priceFrom: 89000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['.co / .com.co / .digital', 'Privacidad WHOIS gratis', 'DNS Anycast global', 'SSL básico incluido', 'Forwarding URL + email'],
    useCase: 'La elección de identidad cuando tu mercado es Colombia. Un .co le dice a tu cliente —y a Google— que operas aquí, lo que ayuda al posicionamiento local y a la confianza. La privacidad WHOIS que incluimos de serie no es cosmética: oculta tu nombre, dirección y teléfono del registro público, frenando el spam y el robo de identidad que persigue a quien registra sin protección. Reserva el .co aunque ya tengas un .com; muchas marcas protegen ambos para que nadie capture su nombre en el TLD nacional.',
    canonicalSlug: 'domain-transfer',
    buyUrl: '/catalogo',
    iconHint: 'globe',
  },
  {
    id: 'dom-com',
    category: 'Dominios',
    name: 'Dominio .com',
    shortDescription: 'El estándar internacional. WHOIS + DNS Anycast incluidos.',
    longDescription: 'Registra tu .com con privacidad WHOIS incluida. DNS anycast y reenvío de URL sin costo extra. El primer paso para cualquier presencia digital seria.',
    priceFrom: 55000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['.com reconocido mundialmente', 'Privacidad WHOIS incluida', 'DNS Anycast rápido', 'Renovación automática'],
    useCase: 'El cimiento de cualquier presencia digital seria, y la primera decisión que deberías tomar antes que el hosting. El .com sigue siendo el dominio que la gente teclea por instinto y el que más credibilidad transmite a nivel internacional. Un consejo que damos siempre: registra el dominio a tu nombre y bajo tu cuenta —no la de un tercero— porque el dominio es el activo del que cuelga todo lo demás (correo, web, marca). Si planeas usar correo profesional, este es el paso cero del que dependen los registros MX, SPF y DKIM.',
    canonicalSlug: 'domain-transfer',
    buyUrl: '/catalogo',
    iconHint: 'globe',
  },
  {
    id: 'dom-net',
    category: 'Dominios',
    name: 'Dominio .net',
    shortDescription: 'Alternativa sólida para proyectos de tecnología e infraestructura.',
    longDescription: 'El .net sigue siendo una de las extensiones más reconocidas, ideal para proyectos de tecnología, redes e infraestructura cuando el .com no está disponible.',
    priceFrom: 52000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['.net — tecnología y redes', 'Privacidad WHOIS incluida', 'DNS Anycast'],
    useCase: 'La alternativa con pedigrí cuando el .com de tu nombre ya está tomado. El .net carga una asociación histórica con redes, infraestructura y proyectos técnicos, así que encaja natural en empresas de tecnología, ISPs o herramientas para desarrolladores. La estrategia habitual: si tu .com no está disponible y el .net de tu marca sí, tomarlo evita que un competidor capture una variante casi idéntica de tu nombre.',
    canonicalSlug: 'domain-transfer',
    buyUrl: '/catalogo',
    iconHint: 'globe',
  },
  {
    id: 'dom-io',
    category: 'Dominios',
    name: 'Dominio .io',
    shortDescription: 'El dominio preferido de startups tecnológicas y SaaS.',
    longDescription: 'El .io se ha convertido en el sello distintivo de startups tech y productos SaaS. Alto reconocimiento en el mundo del software.',
    priceFrom: 160000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['.io — tech & SaaS', 'Privacidad WHOIS incluida', 'Alto reconocimiento tech'],
    useCase: 'El dominio de facto del mundo del software. Si lanzas un SaaS, una API, una herramienta para desarrolladores o una startup tech, el .io te posiciona de inmediato en ese ecosistema —es el sello que la comunidad reconoce—. Cuesta más que un .com porque la demanda en el sector es alta, pero ese mismo precio actúa de filtro: comunica que el proyecto es serio. Tiene sentido cuando tu público objetivo es técnico y el nombre corto y memorable importa más que la familiaridad masiva.',
    canonicalSlug: 'domain-transfer',
    buyUrl: '/catalogo',
    iconHint: 'globe',
  },
  {
    id: 'dom-store',
    category: 'Dominios',
    name: 'Dominio .store',
    shortDescription: 'Dile al mundo qué haces desde la URL. Perfecto para e-commerce.',
    longDescription: 'El .store comunica tu propósito desde la propia dirección. Perfecto para tiendas online y con mucha mejor disponibilidad de nombres que el .com.',
    priceFrom: 12000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['.store — comercio electrónico', 'Alta disponibilidad de nombres', 'Privacidad WHOIS incluida'],
    useCase: 'El dominio que dice qué haces antes de que el visitante entre. Para un e-commerce, un .store comunica intención comercial desde la propia URL y —su gran ventaja práctica— tiene muchísima más disponibilidad de nombres que el saturado .com, así que puedes conseguir tu marca exacta sin guiones ni añadidos raros. Es una jugada inteligente para tiendas nuevas: o como dominio principal, o como redirección de campaña (tumarca.store → tu tienda) para promociones específicas.',
    canonicalSlug: 'domain-transfer',
    buyUrl: '/catalogo',
    iconHint: 'globe',
  },

  // ── EMAIL ──────────────────────────────────────────────────────────
  {
    id: 'email-starter',
    category: 'Email',
    name: 'Email Starter',
    shortDescription: 'tu@tunegocio.com desde el primer día. DKIM, SPF y Outlook/Gmail.',
    longDescription: 'Un buzón profesional con tu dominio. Sin pagar por 5 cuentas que no usas.',
    priceFrom: 6000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['1 cuenta profesional', '10 GB por buzón', 'DKIM · SPF configurados', 'Webmail incluido', 'Outlook · Gmail · Thunderbird', 'iOS · Android'],
    useCase: 'El salto de tunegocio@gmail.com a hola@tunegocio.com — el detalle que decide si un cliente te toma en serio. Esta opción es para el profesional o microempresa de una sola persona que solo necesita un buzón limpio bajo su marca. Lo que de verdad importa aquí, y muchos pasan por alto, es que DKIM y SPF vengan bien configurados: son los registros que evitan que tu correo caiga en spam y que alguien suplante tu dominio. Cuando contrates a tu segunda persona, subes de tier sin cambiar de dirección.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Starter)
    iconHint: 'at-sign',
  },
  {
    id: 'email-pro',
    category: 'Email',
    name: 'Email Pro',
    shortDescription: 'Buzones con anti-phishing, DMARC y archivo legal 7 años.',
    longDescription: '25 GB por buzón, anti-spam agresivo y retención legal para sectores regulados.',
    priceFrom: 12000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['25 GB por buzón', '5 cuentas incluidas', 'DMARC · DKIM · SPF', 'IMAP · POP3 · SMTP · Exchange', 'Archivo legal 7 años', 'iOS · Android sincronizado'],
    useCase: 'Pensado para el equipo pequeño que opera en un sector con obligaciones de cumplimiento. La pieza diferencial es el archivo legal de 7 años: si estás en finanzas, salud, legal o cualquier área donde te puedan exigir reconstruir una conversación de correo años después, esto deja de ser un lujo y pasa a ser un requisito. Súmale DMARC —el tercer registro anti-suplantación que la mayoría no configura— y tienes una bandeja que protege tanto tu reputación de envío como tu trazabilidad ante una auditoría.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Pro)
    iconHint: 'mail',
  },
  {
    id: 'email-godaddy-basic',
    category: 'Email',
    name: 'Email Profesional',
    shortDescription: 'Correo con tu dominio. Hasta 10 buzones, sin configuración técnica.',
    longDescription: 'El primer paso para parecer profesional: un correo con tu nombre de dominio. Configurable en 5 minutos.',
    priceFrom: 12000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['10 GB por buzón', 'Hasta 10 buzones', 'Anti-spam activo', 'Webmail + IMAP/SMTP', 'Configuración en minutos'],
    useCase: 'El punto medio para un equipo de hasta diez personas que quiere correo de marca sin entrar al ecosistema completo de Microsoft. Es la opción correcta cuando cada persona necesita su propia dirección (ventas@, soporte@, gerencia@) pero no usáis Word ni Excel a diario o ya los tenéis por otro lado. La ventaja operativa: se configura en minutos y no obliga a migrar herramientas. Si más adelante el equipo empieza a necesitar Office y Teams, M365 es el siguiente escalón natural manteniendo los mismos buzones.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Profesional)
    iconHint: 'at-sign',
  },
  {
    id: 'microsoft-365-email-essentials',
    category: 'Email',
    name: 'Microsoft 365 Correo Esencial',
    shortDescription: 'Correo Exchange profesional con tu dominio. La puerta de entrada al ecosistema Microsoft.',
    longDescription: 'Solo el correo, pero hecho bien: buzón Exchange de 50 GB con tu dominio, calendario y contactos sincronizados en todos tus dispositivos. Sin apps de Office — ideal si ya las tienes o solo necesitas un correo empresarial confiable bajo tu marca.',
    priceFrom: 14195,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Correo Exchange con tu dominio', '50 GB para correo, contactos y calendarios', 'Calendarios online compartidos', 'Sincronización entre todos los dispositivos', 'Anti-spam y anti-malware de Microsoft', 'Hasta 400 alias de correo', 'Webmail + Outlook + móvil'],
    useCase: 'La puerta de entrada al estándar empresarial Microsoft, sin pagar por aplicaciones que quizá ya tienes. Aquí compras Exchange real: el mismo motor de correo que usan las grandes corporaciones, con 50 GB, calendarios compartidos de verdad y la infraestructura anti-spam/anti-malware de Microsoft detrás. La distinción que conviene entender antes de comprar: este tier es solo correo —sin Word, Excel ni Teams—. Es la elección inteligente para la empresa que ya tiene licencias de Office por otra vía o trabaja en la nube, pero quiere su correo bajo Exchange con su dominio. Los 400 alias permiten crear direcciones de departamento sin pagar buzones extra.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Correo Esencial)
    iconHint: 'at-sign',
    badge: 'Empresarial',
  },
  {
    id: 'microsoft-365-basic',
    category: 'Email',
    name: 'Microsoft 365 Comercial Esencial',
    shortDescription: 'Office web + Teams + 1 TB OneDrive con tu dominio propio.',
    longDescription: 'hola@tuempresa.com en lugar de tuempresa@gmail.com. Versiones web de Excel, Word y PowerPoint, reuniones ilimitadas en HD y colaboración real bajo tu marca.',
    priceFrom: 28389,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Versiones web de Office (Excel, Word, PowerPoint)', '1 TB de almacenamiento en línea', 'Reuniones ilimitadas y videoconferencias en HD', 'Correo personalizado con tu dominio', '50 GB para correo, contactos y calendarios', 'Sincronización entre todos los dispositivos', 'Calendarios online compartidos', 'Hasta 400 alias de correo'],
    useCase: 'El primer tier que convierte el correo en una suite de trabajo completa, pensado para el equipo que vive en la nube. La frontera clave con el plan Estándar es esta: aquí Office es solo en versión web (Excel, Word y PowerPoint desde el navegador), no instalado en el escritorio. Para equipos que trabajan en portátiles compartidos, colaboran en documentos en tiempo real y hacen videollamadas constantes, eso basta y sobra —y abarata la licencia—. El 1 TB de OneDrive por persona y las reuniones HD ilimitadas en Teams son el verdadero motor de productividad. Sube a Estándar solo si necesitas las apps instaladas para trabajar sin conexión o con archivos muy pesados.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Comercial Esencial)
    iconHint: 'mail',
    badge: 'Empresarial',
  },
  {
    id: 'microsoft-365-pro',
    category: 'Email',
    name: 'Microsoft 365 Comercio Profesional',
    shortDescription: 'Office instalado en 5 dispositivos + correo y colaboración de clase mundial.',
    longDescription: 'La suite completa para empresas que viven en Office. Aplicaciones comerciales instaladas hasta en cinco dispositivos, además de las versiones web, correo con tu dominio y todo el ecosistema Microsoft bajo tu marca.',
    priceFrom: 41349,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Aplicaciones de Office instaladas en 5 dispositivos', 'Versiones web de Office (Excel, Word, PowerPoint)', '1 TB de almacenamiento en línea', 'Reuniones ilimitadas y videoconferencias en HD', 'Aplicaciones comerciales para potenciar tu empresa', 'Correo personalizado con tu dominio', '50 GB para correo, contactos y calendarios', 'Sincronización entre dispositivos · hasta 400 alias'],
    useCase: 'El tier tope para la empresa cuya operación gira por completo alrededor de Office y necesita herramientas de gestión empresarial. Frente al Estándar, lo que añade son las aplicaciones comerciales —gestión de citas, reservas y herramientas de negocio del ecosistema Microsoft— pensadas para profesionalizar la operación, no solo crear documentos. Tiene sentido cuando cada empleado trabaja con archivos pesados sin conexión, manejas datos sensibles que prefieres tener en apps instaladas y no en el navegador, y quieres exprimir todo el stack de productividad. Si no vas a usar las apps comerciales, el Estándar te da el Office de escritorio por menos.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Comercio en línea Profesional)
    iconHint: 'mail',
    badge: 'Office completo',
  },
  {
    id: 'microsoft-365-standard',
    category: 'Email',
    name: 'Microsoft 365 Comercial Estándar',
    shortDescription: 'Office de escritorio + correo + reuniones. La suite completa para PYMEs.',
    longDescription: 'El equilibrio que la mayoría de empresas busca: aplicaciones de Office instaladas en hasta cinco dispositivos, correo con tu dominio, 1 TB de OneDrive y reuniones HD ilimitadas. Todo lo necesario para operar una PYME sin pagar el tier más alto.',
    priceFrom: 34869,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Aplicaciones de Office instaladas en 5 dispositivos', 'Versiones web de Office (Excel, Word, PowerPoint)', '1 TB de almacenamiento en línea', 'Reuniones ilimitadas y videoconferencias en HD', 'Correo personalizado con tu dominio', '50 GB para correo, contactos y calendarios', 'Sincronización entre dispositivos · hasta 400 alias'],
    useCase: 'El plan que elige la mayoría de las PYMEs, y por buenas razones: te da el Office de escritorio completo —instalado en hasta cinco dispositivos por persona, funcionando sin conexión— sin pagar las apps comerciales del tier Profesional que muchos negocios nunca tocan. Es el punto dulce para una empresa donde la gente trabaja con Excel y Word de verdad, edita archivos pesados localmente y necesita Teams para reuniones. La regla simple: si tu equipo abre Office a diario en un portátil propio, este es tu plan; si solo necesitáis correo y documentos ocasionales en el navegador, el Comercial Esencial te ahorra dinero.',
    canonicalSlug: 'microsoft-365',
    buyUrl: cartUrl(PFID.email), // 466 — Email / Microsoft 365 (Comercial Estándar)
    iconHint: 'mail',
    badge: 'Más elegido',
  },

  // ── MARKETING ──────────────────────────────────────────────────────
  {
    id: 'email-marketing-beginner',
    category: 'Marketing',
    name: 'Email Marketing Principiante',
    shortDescription: 'Tu primera lista de correos, gratis. Hasta 500 contactos y 5.000 envíos/mes.',
    longDescription: 'El arranque sin fricción para construir tu primera base de contactos: formularios de registro, plantillas listas y envíos suficientes para validar que el email funciona en tu negocio — antes de invertir un peso.',
    priceFrom: 0,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Hasta 500 contactos', 'Hasta 5.000 correos/mes', 'Formularios de registro', 'Plantillas prediseñadas', 'Estadísticas básicas de apertura', 'Lista de clientes potenciales'],
    specs: { contacts: 'Hasta 500', sends: '5.000 correos/mes' },
    bestFor: ['Negocio que valida email por primera vez', 'Emprendedor construyendo su primera lista', 'Profesional independiente'],
    useCase: 'El laboratorio sin riesgo para descubrir si el email funciona en tu negocio antes de invertir. Con 500 contactos y 5.000 envíos al mes tienes margen de sobra para una primera campaña, un boletín mensual o una secuencia de bienvenida. El consejo estratégico: usa este tier para construir el hábito —captar correos con un formulario, enviar con constancia, medir aperturas— porque el email solo rinde cuando tu lista crece y es tuya, no alquilada. El día que las cifras justifiquen automatización, subes de plan sin perder un solo contacto.',
    canonicalSlug: 'email-marketing',
    buyUrl: escaparateUrl('email marketing'), // → escaparate (Email Marketing Principiante) hasta confirmar PFID propio
    iconHint: 'at-sign',
  },
  {
    id: 'email-marketing-pro',
    category: 'Marketing',
    name: 'Email Marketing Pro',
    shortDescription: 'Hasta 5.000 contactos y 50.000 correos/mes con automatización completa.',
    longDescription: 'Para comerciantes inteligentes con listas en crecimiento. Campañas automatizadas, correos de eventos, bienvenidas, conversión de tus publicaciones de blog en correos y estadísticas que puedes compartir con tu equipo.',
    priceFrom: 74809,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Hasta 5.000 contactos', 'Hasta 50.000 correos/mes', 'Formularios de registro ilimitados', 'Almacenamiento ilimitado de imágenes', 'Correo de bienvenida automático', 'Convierte publicaciones de blog en correos', 'Campañas automatizadas + correos de eventos', 'Lista de clientes potenciales · registro de IP', 'Comparte estadísticas con tu equipo'],
    specs: { contacts: 'Hasta 5.000', sends: '50.000 correos/mes', automation: 'Campañas + eventos automatizados' },
    bestFor: ['Tienda con base de clientes en crecimiento', 'Negocio que vive del email marketing', 'Marca con campañas recurrentes'],
    useCase: 'Para el negocio donde el email ya es un canal de ingresos, no un experimento. La diferencia que justifica el salto desde Establecido está en la automatización seria: secuencias de bienvenida, correos disparados por eventos (un abandono de carrito, una compra, un cumpleaños) y la conversión automática de tus posts de blog en campañas. A 5.000 contactos y 50.000 envíos, también ganas el trabajo en equipo —compartir estadísticas— que importa cuando hay más de una persona detrás del marketing. La señal de que lo necesitas: cuando dejar de enviar un correo se nota directamente en la facturación de la semana.',
    canonicalSlug: 'email-marketing',
    buyUrl: escaparateUrl('email marketing'), // → escaparate (Email Marketing Pro) hasta confirmar PFID propio
    iconHint: 'mail',
    badge: 'Pro',
  },
  {
    id: 'email-marketing-established',
    category: 'Marketing',
    name: 'Email Marketing Establecido',
    shortDescription: 'Hasta 2.500 contactos y 25.000 correos/mes. Para quien ya tiene clientes.',
    longDescription: 'El plan para negocios que ya tienen una base de clientes y quieren cultivarla: formularios ilimitados, manejo de suscripciones, bienvenidas automáticas y conversión de blog a correo.',
    priceFrom: 37879,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Hasta 2.500 contactos', 'Hasta 25.000 correos/mes', 'Formularios de registro ilimitados', 'Almacenamiento ilimitado de imágenes', 'Manejo de cancelación de suscripciones', 'Correo automático de bienvenida', 'Convierte publicaciones de blog en correos', 'Lista de clientes potenciales'],
    specs: { contacts: 'Hasta 2.500', sends: '25.000 correos/mes' },
    bestFor: ['Negocio con base de clientes activa', 'Profesional que nutre prospectos', 'Marca con boletín recurrente'],
    useCase: 'El tier intermedio para quien ya superó la fase de validar y tiene una base real que cultivar. A 2.500 contactos y 25.000 envíos cubres un boletín recurrente y campañas mensuales con holgura, y desbloqueas lo que de verdad cambia los resultados: bienvenidas automáticas, manejo limpio de bajas (clave para tu reputación de envío y para cumplir la ley) y formularios ilimitados para seguir captando. Es el plan correcto cuando ya sabes que el email convierte para ti, pero aún no necesitas la automatización por eventos del tier Pro.',
    canonicalSlug: 'email-marketing',
    buyUrl: escaparateUrl('email marketing'), // → escaparate (Email Marketing Establecido) hasta confirmar PFID propio
    iconHint: 'at-sign',
  },

  // ── SEGURIDAD ──────────────────────────────────────────────────────
  {
    id: 'ssl-dv',
    category: 'Seguridad',
    name: 'SSL Estándar DV',
    shortDescription: 'El candado que Google exige. Activa HTTPS en 1 clic.',
    longDescription: 'Google penaliza los sitios sin HTTPS y Chrome los marca como "No seguro". Imprescindible si recibes cualquier dato personal.',
    priceFrom: 95000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['1 dominio principal', 'Validación DV rápida', 'SHA-256 · RSA 2048-bit', 'TLS 1.2 y 1.3', 'Chrome · Firefox · Safari', 'Instalación 1 clic', 'Garantía USD $10.000'],
    useCase: 'El mínimo no negociable de internet hoy. Sin HTTPS, Chrome marca tu sitio como "No seguro" en la barra de direcciones y Google lo penaliza en el ranking —es decir, pierdes visitantes antes de que entren—. La validación DV (Domain Validation) confirma que controlas el dominio y se emite en minutos, lo que la hace perfecta para blogs, sitios informativos y cualquier página que reciba datos de un formulario de contacto. Si vas a procesar pagos o pedir datos sensibles, mira el escalón EV; para todo lo demás, este candado cumple y es el punto de partida correcto.',
    canonicalSlug: 'ssl',
    buyUrl: cartUrl(PFID.ssl), // 75 — SSL (Estándar DV)
    iconHint: 'lock',
  },
  {
    id: 'ssl-pro',
    category: 'Seguridad',
    name: 'SSL Wildcard Pro',
    shortDescription: 'Certificado EV + Wildcard. Cubre todos tus subdominios.',
    longDescription: 'Validación Extendida más cobertura Wildcard. La señal de confianza que tu checkout necesita.',
    priceFrom: 340000,
    currency: 'COP',
    billingPeriod: 'anual',
    features: ['Validación Extendida (EV)', 'Wildcard *.tudominio.com', 'SHA-256 2048-bit', 'Garantía USD $1.750.000', 'Reemisión ilimitada', 'Soporte 24/7'],
    useCase: 'El certificado para cuando la confianza vale dinero real. Combina dos cosas que rara vez van juntas: validación Extendida (EV), que exige verificar la existencia legal de tu empresa —el máximo nivel de garantía que un cliente puede recibir antes de teclear su tarjeta— y cobertura Wildcard, que asegura tu dominio y todos sus subdominios con un solo certificado (tienda., app., pagos., blog.…). Es la elección lógica para e-commerce serio, fintech o cualquier operación con múltiples subdominios donde gestionar certificados sueltos sería una pesadilla. La garantía de USD $1.750.000 respalda cada transacción cifrada; conviene si una brecha en el cifrado te expondría a reclamaciones de esa magnitud.',
    canonicalSlug: 'ssl',
    buyUrl: cartUrl(PFID.ssl), // 75 — SSL (Wildcard Pro / EV)
    iconHint: 'shield-check',
  },
  {
    id: 'codeguard-backup',
    category: 'Seguridad',
    name: 'Backup Automático Diario',
    shortDescription: 'Backups diarios + restauración en un clic. Para dormir tranquilo.',
    longDescription: 'El 41% de los sitios WordPress hackeados no tenían backup. ¿Cuánto vale un año de trabajo de tu sitio?',
    priceFrom: 18000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Backup diario automático', '30 días de historial', 'Restauración en 1 clic', 'Detección de cambios en tiempo real', 'Almacenamiento cloud separado', 'Alertas por email'],
    useCase: 'El seguro de vida de tu sitio, y el que casi nadie tiene hasta que es tarde. Un backup importa por dos motivos que la gente confunde: no es solo recuperarte de un hackeo —es deshacer un error propio (una actualización que rompió todo, un plugin que corrompió la base de datos, un borrado accidental)—. La clave técnica es que el respaldo viva en un almacenamiento cloud separado de tu servidor: si guardas la copia en la misma máquina que se infecta o falla, no tienes copia. Los 30 días de historial te dejan volver a un punto anterior a que el problema apareciera, no solo al último estado. Si tu sitio te costó tiempo o dinero, esto no es opcional.',
    canonicalSlug: 'website-backup',
    buyUrl: escaparateUrl('codeguard respaldo backup'), // → escaparate (CodeGuard Backup) hasta confirmar PFID propio
    iconHint: 'database',
  },
  {
    id: 'security-standard',
    category: 'Seguridad',
    name: 'Seguridad Web Estándar',
    shortDescription: 'El primer escudo: monitoreo de malware, SSL y limpieza cuando lo necesites.',
    longDescription: 'El punto de entrada a la seguridad gestionada. Monitoreo continuo de malware, certificado SSL incluido y una limpieza del sitio si algo se infiltra. Para sitios que aún no tienen tráfico masivo pero ya no quieren correr riesgos.',
    priceFrom: 26789,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Protege 1 sitio web', 'Análisis de malware continuo', 'Certificado SSL incluido', 'Una limpieza del sitio al año', 'Monitoreo de listas negras', 'Alertas de seguridad por email', '5 GB de respaldo seguro'],
    specs: { firewall: 'Monitoreo de malware gestionado', ssl: 'SSL incluido', cleanup: 'Una limpieza anual' },
    bestFor: ['Sitio nuevo que recibe datos básicos', 'Blog o portafolio profesional', 'Negocio local que arranca online'],
    useCase: 'El primer paso de la seguridad gestionada, para cuando ya tienes algo que perder pero aún no tráfico masivo. La diferencia frente a "instalar un plugin de seguridad y rezar" es que aquí alguien vigila por ti: monitoreo continuo de malware, alerta si tu dominio entra en una lista negra (lo que mataría tu correo y tu SEO) y —lo más valioso— una limpieza profesional del sitio incluida si algo se infiltra. Para un blog, portafolio o negocio local que arranca, es la red de seguridad que evita que un incidente menor se convierta en reconstruir todo desde cero.',
    canonicalSlug: 'website-security',
    buyUrl: cartUrl(PFID.webSecurityWaf), // 557 — Seguridad Web / WAF (Estándar)
    iconHint: 'shield',
  },
  {
    id: 'security-advanced',
    category: 'Seguridad',
    name: 'Seguridad Web Avanzada',
    shortDescription: 'Firewall + limpiezas ilimitadas + DDoS y CDN. Protección activa todo el año.',
    longDescription: 'El plan que toda empresa con tráfico real necesita: firewall contra hackers, SSL incluido, análisis de malware, limpiezas ilimitadas del sitio y aceleración con CDN para que la seguridad no cueste velocidad.',
    priceFrom: 53589,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Protege 1 sitio web', 'Firewall anti-hackers', 'Certificado SSL incluido en el firewall', 'Análisis de malware continuo', 'Limpiezas ilimitadas del sitio', 'Protección DDoS + aceleración CDN', '25 GB de respaldo seguro'],
    specs: { firewall: 'WAF gestionado · OWASP Top 10', cdn: 'Red de entrega de contenido global', cleanup: 'Limpieza y reparación ilimitada' },
    bestFor: ['Sitio con tráfico real y campañas activas', 'Negocio que recibe datos de clientes', 'Marca que no puede permitirse caídas'],
    useCase: 'El plan donde la seguridad pasa de vigilar a defender activamente. El salto clave sobre el Estándar es el firewall WAF de Capa 7: en lugar de solo avisarte de que hubo malware, filtra los ataques —inyecciones SQL, XSS, el OWASP Top 10— antes de que toquen tu sitio. Las limpiezas ilimitadas significan que si algo se cuela, lo resuelven cuantas veces haga falta sin costo extra, y la protección DDoS con CDN evita que una avalancha de tráfico malicioso (o un pico legítimo) te tumbe. Es el mínimo realista para cualquier sitio que reciba datos de clientes o invierta en campañas: no puedes pagar anuncios hacia una web que un ataque puede tumbar.',
    canonicalSlug: 'website-security',
    buyUrl: cartUrl(PFID.webSecurityWaf), // 557 — Seguridad Web / WAF (Avanzado)
    iconHint: 'shield',
  },
  {
    id: 'security-premium',
    category: 'Seguridad',
    name: 'Seguridad Web Premium',
    shortDescription: 'Máxima protección: limpieza priorizada, 200 GB de respaldo y CDN premium.',
    longDescription: 'Nuestro blindaje más completo. Todo lo del plan Avanzado, más limpieza y reparación priorizada cuando algo ocurre y 200 GB de respaldo seguro. Para operaciones digitales donde la seguridad no es negociable.',
    priceFrom: 80419,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['Protege 1 sitio web', 'Firewall anti-hackers', 'Certificado SSL incluido en el firewall', 'Análisis de malware continuo', 'Limpiezas ilimitadas del sitio', 'Protección DDoS + aceleración CDN', 'Limpieza y reparación priorizada', '200 GB de respaldo seguro'],
    specs: { firewall: 'WAF gestionado · bot mitigation ML', cdn: 'CDN premium global', cleanup: 'Reparación priorizada', backup: '200 GB cloud seguro' },
    bestFor: ['E-commerce de alto volumen', 'Operación crítica con SLA estricto', 'Marca con datos sensibles y compliance'],
    useCase: 'El blindaje para operaciones donde cada minuto de caída o cada brecha tiene un costo cuantificable. Sobre el Avanzado, lo que añade es velocidad de respuesta y capacidad: la limpieza y reparación priorizada significa que tu incidente va al frente de la cola —cuando un e-commerce de alto volumen está comprometido, la diferencia entre resolver en una hora o en seis es la diferencia entre un susto y una pérdida grave—. La mitigación de bots por machine learning frena el fraude automatizado y el scraping que afecta a las tiendas grandes, y los 200 GB de respaldo cubren catálogos e historiales extensos. Este tier es para quien ya opera bajo un SLA estricto o maneja datos sensibles con obligaciones de compliance: no lo compras por miedo, lo compras porque el riesgo ya está cuantificado en tu hoja de cálculo.',
    canonicalSlug: 'website-security',
    buyUrl: cartUrl(PFID.webSecurityWaf), // 557 — Seguridad Web / WAF (Premium)
    iconHint: 'shield-check',
    badge: 'Máxima protección',
  },

  // ── PARA DESARROLLADORES ───────────────────────────────────────────
  {
    id: 'wp-deluxe',
    category: 'Para Desarrolladores',
    name: 'WordPress Deluxe',
    shortDescription: '3 sitios independientes con staging. Para freelancers que entregan proyectos.',
    longDescription: 'Maneja dev, staging y producción en un solo plan — o tres clientes sin que se mezcle nada.',
    priceFrom: 25000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['3 instalaciones WP separadas', '75 GB NVMe', 'Hasta 100.000 visitas/mes', 'Staging · clon en 1 clic', 'CDN global', 'Backups diarios 1 clic'],
    specs: { php: 'PHP 8.x administrado', db: 'MySQL 8.0 · base por sitio', cache: 'OPcache + caché de página' },
    bestFor: ['Freelancer con 2-3 clientes', 'Agencia pequeña en crecimiento', 'Dev que usa staging'],
    useCase: 'El plan pensado para quien entrega proyectos, no solo administra el suyo. Tres instalaciones WordPress completamente aisladas te permiten dos flujos de trabajo profesionales: o montas dev → staging → producción de un mismo proyecto (probar sin riesgo, promover lo aprobado), o alojas tres clientes distintos sin que un problema en uno contamine a los otros. La regla práctica para un freelancer: cada cliente en su propia instalación significa que puedes entregar accesos, hacer mantenimiento o migrar uno sin tocar los demás. El clon en un clic es lo que convierte "voy a probar este rediseño" en una operación de minutos, no de una tarde.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.wordpressManaged), // 457 — WordPress Managed (Deluxe)
    iconHint: 'layers',
  },
  {
    id: 'hosting-plus-dev',
    category: 'Para Desarrolladores',
    name: 'Web Hosting Plus Dev',
    shortDescription: 'cPanel completo con SSH, PHP 8.x, MySQL, Python y cronjobs.',
    longDescription: 'Cuando WordPress se queda corto. SSH abierto, PHP + Python + Node.js básico, cronjobs y subdominios ilimitados.',
    priceFrom: 19000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['50 GB SSD', 'SSH acceso completo', 'MySQL ilimitadas', 'PHP 8.x · Python 3 · Node.js', 'Cronjobs ilimitados · editor visual', 'Subdominios ilimitados', 'Git desde terminal'],
    specs: { php: 'PHP 8.1/8.2 por dominio', mysql: 'MySQL 8.0 · phpMyAdmin', node: 'Node.js 20 LTS', panel: 'cPanel Linux completo' },
    bestFor: ['Dev PHP/Python con proyectos custom', 'App de gestión para negocio colombiano'],
    useCase: 'El entorno para cuando tu proyecto ya no cabe en WordPress y necesitas un servidor de verdad, pero sin asumir la administración completa de un VPS. La diferencia clave es el acceso: SSH abierto, cronjobs ilimitados y runtimes más allá de PHP —Python 3 y Node.js— sobre cPanel, lo que te deja desplegar una app de gestión a medida, un panel interno o un backend ligero. Es el punto medio honesto: más libertad que un hosting gestionado, menos responsabilidad operativa que un VPS root. Tiene sentido para el desarrollador que quiere desplegar con Git y correr tareas programadas, pero no quiere parchear el sistema operativo ni configurar el firewall a mano.',
    canonicalSlug: 'business',
    buyUrl: cartUrl(PFID.webHostingPlus), // 459 — Web Hosting Plus
    iconHint: 'code',
  },

  // ── VPS & CÓMPUTO (alto margen — prioridad de negocio) ─────────────
  // Línea escalonada de VPS High Performance gestionados. El PFID de familia
  // (PFID.vpsHighPerf) está PENDIENTE de verificación en checkout: mientras
  // sea 0, los tres botones caen a WhatsApp para no enviar al carrito
  // equivocado. Confirmar product_category_id en /v1/cart antes de migrar.
  {
    id: 'vps-pro-alpha',
    category: 'VPS & Cómputo',
    name: 'VPS Pro Alpha',
    shortDescription: 'Tu primer servidor dedicado gestionado. Root completo + ingeniero SOTA.',
    longDescription: 'El salto del hosting compartido a infraestructura propia. Acceso root total, stack a medida y un ingeniero SOTA que conoce tu proyecto por nombre — sin que tengas que aprender a administrar un servidor desde cero. No es un VPS commodity: es soberanía con red de seguridad.',
    priceFrom: 426669,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['8 vCPU dedicados', '32 GB RAM', '400 GB SSD NVMe', 'Tráfico ilimitado · monitoreo de rendimiento', 'Acceso root (SSH) completo', 'Stack a medida gestionado', 'Ingeniero SOTA asignado', 'SLA 99.9%'],
    specs: { cpu: '8 vCPU High Performance dedicados', ram: '32 GB', io: 'SSD NVMe 400 GB', uptime: '99.9% garantizado' },
    bestFor: ['Primer salto a servidor propio', 'SaaS en etapa temprana', '2-3 tiendas WooCommerce'],
    useCase: 'El umbral entre alquilar un pedazo de servidor compartido y tener infraestructura propia. Lo eliges cuando el hosting gestionado empieza a quedarte corto —vecinos ruidosos que frenan tu sitio, falta de control sobre el stack, o necesitas instalar algo que un plan compartido no permite—. Con root completo mandas tú: versiones exactas de software, servicios a medida, la configuración que tu app necesita. Pero la diferencia con un VPS commodity de mercado es el ingeniero SOTA asignado: no te entregamos una caja vacía y te deseamos suerte. Si nunca administraste un servidor, este es el salto seguro, porque la "soberanía" viene con red de seguridad. La señal de que es tu momento: cuando ya sabes qué te falta del hosting compartido, pero administrarlo solo te da vértigo.',
    canonicalSlug: 'vps',
    buyUrl: 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+asesor%C3%ADa+sobre+el+VPS+Alpha', // alto margen → WhatsApp (asesoría 1-a-1)
    iconHint: 'server',
  },
  {
    id: 'vps-pro-sigma',
    category: 'VPS & Cómputo',
    name: 'VPS Pro Sigma',
    shortDescription: 'Potencia para operación en producción seria. Doble de cómputo y NVMe.',
    longDescription: 'Cuando tu operación ya genera ingresos y cada segundo de caída cuesta dinero. El doble de núcleos y memoria que Alpha, almacenamiento NVMe de alto IOPS y un ingeniero SOTA que monitorea proactivamente. Construido para cargas que no pueden esperar.',
    priceFrom: 768000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['16 vCPU dedicados', '64 GB RAM', '800 GB SSD NVMe', 'Tráfico ilimitado · monitoreo proactivo 24/7', 'Acceso root (SSH) completo', 'Stack gestionado + tuning de rendimiento', 'Ingeniero SOTA asignado', 'Backups gestionados · SLA 99.95%'],
    specs: { cpu: '16 vCPU High Performance dedicados', ram: '64 GB', io: 'SSD NVMe 800 GB · alto IOPS', uptime: '99.95% garantizado' },
    bestFor: ['SaaS en producción con usuarios activos', 'E-commerce de alto tráfico', 'API o backend de misión crítica'],
    useCase: 'El tier para cuando el downtime ya no es una molestia sino una pérdida medible. Frente a Alpha, no solo duplicas cómputo y memoria: cambias la postura operativa. El NVMe de alto IOPS importa específicamente cuando tu cuello de botella es la base de datos —un SaaS con muchos usuarios concurrentes o un e-commerce con catálogo grande viven o mueren por la velocidad de lectura/escritura, no por la CPU—. El monitoreo proactivo 24/7 significa que vigilamos las señales antes de que se conviertan en caída: un proceso que consume de más, un disco que se llena, un pico anómalo. Lo eliges cuando tu operación ya factura con usuarios activos y necesitas un SLA del 99.95% que se traduzca en confianza para tus propios clientes.',
    canonicalSlug: 'vps',
    buyUrl: 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+asesor%C3%ADa+sobre+el+VPS+Sigma', // alto margen → WhatsApp (asesoría 1-a-1)
    iconHint: 'cpu',
    badge: 'Popular',
  },
  {
    id: 'vps-pro-omega',
    category: 'VPS & Cómputo',
    name: 'VPS Pro Omega',
    shortDescription: 'Infraestructura enterprise soberana. Máximo cómputo + consultoría mensual.',
    longDescription: 'El tope de la línea: máxima potencia, almacenamiento NVMe dedicado y un ingeniero SOTA con consultoría mensual incluida. Para operaciones donde la infraestructura es el negocio — multi-tienda, plataformas de datos, cargas que escalan sin previo aviso.',
    priceFrom: 1340000,
    currency: 'COP',
    billingPeriod: 'mensual',
    features: ['32 vCPU dedicados', '128 GB RAM', '1.6 TB SSD NVMe dedicado', 'Tráfico ilimitado · monitoreo 24/7 con alertas', 'Acceso root (SSH) completo', 'Stack gestionado + alta disponibilidad', 'Ingeniero SOTA · consultoría 1h/mes', 'Backups cada 6h · SLA 99.98%'],
    specs: { cpu: '32 vCPU High Performance dedicados', ram: '128 GB', io: 'SSD NVMe 1.6 TB dedicado', uptime: '99.98% garantizado' },
    bestFor: ['Operación enterprise multi-tienda', 'Plataforma de datos o analytics', 'Infraestructura crítica que escala'],
    useCase: 'El tope de la línea, para operaciones donde la infraestructura es el producto, no un costo de soporte. Lo que de verdad distingue a Omega no son los 32 vCPU ni los 128 GB —aunque permiten correr varias tiendas pesadas, una plataforma de analytics o cargas que escalan sin avisar—. Es la alta disponibilidad, los backups cada 6 horas y, sobre todo, la consultoría mensual con un ingeniero SOTA: una hora al mes para revisar arquitectura, planear crecimiento y anticipar cuellos de botella antes de que existan. A este nivel ya no compras un servidor, contratas un socio de infraestructura. Tiene sentido cuando una hora de caída se cuenta en cifras que justifican el SLA del 99.98%, y cuando prefieres prevenir con un experto que apagar incendios tú solo a las 3 a.m.',
    canonicalSlug: 'vps',
    buyUrl: 'https://wa.me/573135646123?text=Hola+Gano+Digital,+quiero+asesor%C3%ADa+sobre+el+VPS+Omega', // alto margen → WhatsApp (asesoría 1-a-1)
    iconHint: 'crown',
    badge: 'Élite',
  },

  // ── SERVICIOS GANO ─────────────────────────────────────────────────
  {
    id: 'diagnostico',
    category: 'Servicios Gano',
    name: 'Diagnóstico de Soberanía',
    shortDescription: 'Auditoría técnica de tu stack. Informe ejecutivo en 72 horas.',
    longDescription: 'Antes de migrar a ciegas: un ingeniero SOTA audita tu infraestructura, mide benchmarks reales y entrega un plan de blindaje. Se acredita al contratar cualquier ecosistema anual.',
    priceFrom: 650000,
    currency: 'COP',
    billingPeriod: 'único pago',
    features: ['Entrega en 72h garantizadas', 'Hosting · DNS · Seguridad · SEO técnico', 'Benchmarks TTFB/LCP/CLS', 'PDF ejecutivo 20-30 páginas', '1h con ingeniero SOTA', '100% acreditable a plan anual'],
    useCase: 'El paso que evita migrar a ciegas. Antes de mover tu operación a una infraestructura nueva, este diagnóstico audita lo que ya tienes —hosting, DNS, seguridad y SEO técnico— y te entrega un mapa real con números, no opiniones: TTFB, LCP y CLS medidos, vulnerabilidades detectadas, cuellos de botella nombrados. Es el servicio para quien sospecha que su stack actual lo está frenando pero no sabe dónde ni cuánto. Como el costo se acredita al 100% si luego contratas un plan anual, en la práctica es una auditoría sin riesgo: o te confirma que estás bien, o te da el plan exacto para arreglarlo.',
    buyUrl: 'https://wa.me/573135646123?text=Quiero+un+Diagnóstico+de+Soberanía+Digital',
    iconHint: 'stethoscope',
    badge: 'Único pago',
  },
  {
    id: 'disenio-custom',
    category: 'Servicios Gano',
    name: 'Ecosistema SOTA',
    shortDescription: 'Diseño y desarrollo WordPress a medida. De wireframe a producción.',
    longDescription: 'Desde wireframes hasta despliegue. Tu marca como un producto SOTA: investigación, arquitectura, desarrollo y 3 meses de acompañamiento post-lanzamiento.',
    priceFrom: 8500000,
    currency: 'COP',
    billingPeriod: 'proyecto',
    features: ['Wireframes · UI · desarrollo · deploy', '6-10 semanas', 'Hasta 12 páginas en scope inicial', 'CRM · pasarela · analytics', 'Business NVMe 1er año incluido', '3 meses post-launch'],
    useCase: 'Para cuando no quieres un plan que administrar, sino un producto terminado. Este es el servicio llave en mano: nosotros hacemos la investigación, la arquitectura, el diseño, el desarrollo y el despliegue, y te entregamos un sitio listo para operar sobre infraestructura Business NVMe incluida el primer año. Es la opción correcta para la empresa o profesional que valora su tiempo más que el ahorro de hacerlo uno mismo, y que quiere que su marca se vea y funcione como un producto serio desde el día uno. Los 3 meses de acompañamiento post-lanzamiento son la diferencia entre "te entrego y desaparezco" y un socio que se asegura de que el lanzamiento aterrice bien.',
    buyUrl: 'https://wa.me/573135646123?text=Quiero+cotizar+un+Ecosistema+SOTA',
    iconHint: 'layout-dashboard',
    badge: 'A medida',
  },
];

/** Preguntas frecuentes — sección FAQ en /catalogo. */
export const FAQS: { q: string; a: string }[] = [
  {
    q: '¿Puedo cambiar de plan después?',
    a: 'Sí. Migración a un plan superior en cualquier momento, en menos de 24h y sin perder datos.',
  },
  {
    q: '¿Qué significa "facturación en COP"?',
    a: 'Tu tarjeta o transferencia se debita en pesos colombianos. Sin conversiones ni sorpresas de tipo de cambio.',
  },
  {
    q: '¿El dominio está incluido en el hosting?',
    a: 'Los planes de hosting no incluyen dominio. Puedes añadir un .com o .co desde la sección Dominios de este catálogo.',
  },
  {
    q: '¿Tienen garantía de reembolso?',
    a: 'Sí. 30 días de garantía en todos los planes. Si no estás satisfecho, devolvemos el 100% sin preguntas.',
  },
  {
    q: '¿Necesito saber programar?',
    a: 'No para WordPress Starter, Business NVMe y Ultimate — vienen preinstalados. Los planes Dev (cPanel, SSH) sí requieren conocimientos básicos.',
  },
  {
    q: '¿Cómo funciona la contratación y el pago?',
    a: 'Hoy, al pulsar el botón te conectamos por WhatsApp con un ingeniero que confirma el plan exacto y te guía al checkout seguro en COP. Así nos aseguramos de que contratas el servicio correcto.',
  },
];

/** Señales de confianza — strip en /catalogo. iconHint mapea a lucide en la página. */
export const TRUST_POINTS: { icon: string; title: string; body: string }[] = [
  { icon: 'shield-check', title: 'Infraestructura GoDaddy', body: 'Reseller autorizado con estándares globales de datacenter y red en 200+ POPs.' },
  { icon: 'clock', title: '99.9% Disponibilidad', body: 'SLA comprometido con monitoreo proactivo 24/7. Si no cumplimos, se acredita.' },
  { icon: 'peso-sign', title: 'Facturación en COP', body: 'Pesos colombianos. Sin conversiones de divisa ni sorpresas de tipo de cambio.' },
  { icon: 'headset', title: 'Soporte en español', body: 'Equipo técnico colombiano. Primera respuesta en horas, no en días.' },
];

/** Línea de tiempo de activación — sección "¿Cómo funciona?" en /catalogo. */
export const ACTIVATION_TIMELINE: { t: string; title: string; body: string; icon: string }[] = [
  { t: '0 min', title: 'Confirmación y pago', body: 'Cobro en COP a través del checkout autorizado. Factura electrónica al instante.', icon: 'file-signature' },
  { t: '2 min', title: 'Aprovisionamiento', body: 'Tu nodo se activa automáticamente. Credenciales por correo cifrado.', icon: 'bolt' },
  { t: '5 min', title: 'Vinculación DNS', body: 'DNS configurado. Propagación acelerada vía Anycast.', icon: 'globe' },
  { t: '10 min', title: 'WordPress listo', body: 'Instalación, SSL, WAF y backups activos. Ya puedes publicar.', icon: 'wordpress' },
  { t: '24 h', title: 'Llamada de bienvenida', body: 'Un ingeniero SOTA revisa tus objetivos y responde dudas.', icon: 'headset' },
  { t: 'Siempre', title: 'Soporte continuo', body: 'Equipo disponible para cualquier consulta técnica o de facturación.', icon: 'robot' },
];
