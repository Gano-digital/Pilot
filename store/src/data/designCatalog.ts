/**
 * Catálogo de Diseño Web Gano Digital — "Estudios SOTA".
 *
 * Cada paquete es un PRODUCTO VENDIBLE de diseño y desarrollo web con:
 *   - una identidad visual propia (paleta, tipografía, mood, movimiento),
 *   - una vertical de mercado ideal,
 *   - un alcance y precio en COP,
 *   - y — crítico para producción — un `generationPrompt`: el prompt maestro
 *     que el equipo usa para arrancar la producción del sitio del cliente
 *     con la estrategia ya resuelta.
 *
 * Fundamentado en investigación SOTA (junio 2026): tendencias premiadas en
 * Awwwards (3D/WebGL, GSAP, editorial, brutalist, storytelling, minimal) y el
 * mapeo industria→estilo→herramientas que el mercado realmente compra (Wix).
 *
 * El prompt de generación es deliberadamente exhaustivo: define stack, paleta
 * exacta, tipografía, secciones, tono de copy, movimiento y entregables, para
 * que el output sea consistente y "listo para producir" en cuanto entre un lead.
 */

export type DesignVibe =
  | 'editorial'
  | 'kinetic-3d'
  | 'minimal-luxe'
  | 'neo-brutalist'
  | 'warm-organic'
  | 'corporate-trust'
  | 'retro-future'
  | 'commerce-bold';

export interface PalettePreview {
  /** Fondo principal de la previsualización. */
  bg: string;
  /** Color de superficie/tarjeta sobre el fondo. */
  surface: string;
  /** Texto principal sobre el fondo. */
  text: string;
  /** Acento de marca del paquete. */
  accent: string;
  /** Acento secundario / detalle. */
  accent2: string;
}

export interface DesignPackage {
  id: string;
  /** Nombre comercial del paquete. */
  name: string;
  /** Estilo/vibe canónico — gobierna la previsualización. */
  vibe: DesignVibe;
  /** Frase gancho de una línea. */
  tagline: string;
  /** Descripción de venta (qué es y para quién). */
  description: string;
  /** Verticales de mercado donde brilla. */
  idealFor: string[];
  /** Paleta usada para previsualizar el estilo en la tarjeta. */
  palette: PalettePreview;
  /** Tipografías de referencia (heading / body). Solo descriptivas en la UI. */
  typography: { heading: string; body: string; note: string };
  /** Mood de movimiento — guía animación de la previsualización y la producción. */
  motion: string;
  /** Secciones que incluye el sitio entregado. */
  sections: string[];
  /** Entregables del paquete. */
  deliverables: string[];
  /** Precio desde, en COP. */
  priceFrom: number;
  /** Tiempo estimado de producción. */
  timeline: string;
  /** Badge opcional para la tarjeta. */
  badge?: string;
  /**
   * PROMPT MAESTRO DE PRODUCCIÓN (uso interno, expuesto vía /api/design-brief).
   * Listo para alimentar a un generador de sitios o briefar al equipo.
   */
  generationPrompt: string;
}

const WHATSAPP = '573135646123';

/** Construye el enlace de WhatsApp con el paquete preseleccionado. */
export function designWhatsappUrl(pkg: DesignPackage): string {
  const text = `Hola Gano Digital, me interesa el paquete de diseño "${pkg.name}". Quiero cotizarlo.`;
  return `https://wa.me/${WHATSAPP}?text=${encodeURIComponent(text)}`;
}

/** Formato de precio en COP, sin decimales. */
export function formatCOP(value: number): string {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    maximumFractionDigits: 0,
  }).format(value);
}

export const DESIGN_PACKAGES: DesignPackage[] = [
  {
    id: 'editorial-atelier',
    name: 'Atelier Editorial',
    vibe: 'editorial',
    tagline: 'Tipografía protagonista, ritmo de revista, autoridad inmediata.',
    description:
      'Diseño tipo revista de autor: tipografía serif de gran escala, retículas asimétricas y mucho aire. Comunica criterio y nivel sin gritar. Ideal para marcas que venden experiencia y palabra.',
    idealFor: ['Consultores y coaches', 'Estudios creativos', 'Autores y publicaciones', 'Despachos profesionales'],
    palette: { bg: '#0E0E0C', surface: '#1A1A16', text: '#F4F1EA', accent: '#D97E3A', accent2: '#C9C2B0' },
    typography: { heading: 'Fraunces / Playfair Display', body: 'Inter / Source Sans 3', note: 'Serif display + sans neutra' },
    motion: 'Reveals suaves por línea, transiciones de página tipo cortina, parallax discreto en imágenes.',
    sections: ['Hero tipográfico', 'Manifiesto', 'Servicios en formato índice', 'Casos en formato artículo', 'Sobre / autoridad', 'Contacto editorial'],
    deliverables: ['Sitio de 5–7 páginas', 'Sistema tipográfico', 'Plantilla de artículo/caso', 'SEO on-page', 'Hosting NVMe 1er año'],
    priceFrom: 3200000,
    timeline: '3–4 semanas',
    badge: 'Más vendido',
    generationPrompt: [
      'Genera un sitio web estilo REVISTA EDITORIAL DE AUTOR.',
      'STACK: React + Tailwind, SSR, animación con Motion. Mobile-first.',
      'PALETA (exacta): fondo #0E0E0C, superficie #1A1A16, texto #F4F1EA, acento terracota #D97E3A, neutro cálido #C9C2B0.',
      'TIPOGRAFÍA: heading serif display (Fraunces o Playfair Display) en escala muy grande (clamp 56–120px), body sans neutra (Inter o Source Sans 3) 16–18px, line-height 1.7.',
      'LAYOUT: retículas asimétricas, columnas tipo periódico, mucho whitespace, numeración de secciones (01, 02…), reglas finas (1px) como separadores.',
      'SECCIONES: (1) Hero puramente tipográfico con una sola frase potente; (2) Manifiesto a dos columnas; (3) Servicios como índice de revista; (4) Casos presentados como artículos con dropcap; (5) Sobre/autoridad con retrato editorial; (6) Contacto con tono de carta.',
      'MOVIMIENTO: reveals por línea al hacer scroll (stagger), transición de página tipo cortina, parallax sutil en imágenes (no más de 12px).',
      'COPY: tono culto, seguro, en español; frases cortas con autoridad. Sin jerga de marketing.',
      'ACCESIBILIDAD: contraste AA, foco visible, jerarquía H1→H2→H3 estricta.',
      'ENTREGABLE: 5–7 páginas, sistema tipográfico documentado, plantilla reutilizable de artículo/caso, SEO on-page (title, meta, JSON-LD Article).',
    ].join('\n'),
  },
  {
    id: 'kinetic-lab',
    name: 'Kinetic Lab 3D',
    vibe: 'kinetic-3d',
    tagline: 'WebGL, profundidad y movimiento. El sitio que la gente recuerda.',
    description:
      'Experiencia interactiva con 3D en tiempo real (Three.js/WebGL), scroll cinético y microinteracciones. Para marcas que quieren proyectar innovación y dejar huella. Calidad de portafolio premiado.',
    idealFor: ['Startups tech y SaaS', 'Productos de innovación', 'Agencias creativas', 'Lanzamientos de producto'],
    palette: { bg: '#070A12', surface: '#0F1626', text: '#EAF0FF', accent: '#5BA8FF', accent2: '#9D7BFF' },
    typography: { heading: 'Space Grotesk / Aeonik', body: 'Inter', note: 'Grotesk geométrica + sans técnica' },
    motion: 'Escena 3D reactiva al scroll y al cursor, transiciones GSAP, partículas y shaders sutiles.',
    sections: ['Hero 3D interactivo', 'Demo de producto', 'Características con scroll cinético', 'Métricas animadas', 'Integraciones', 'CTA inmersivo'],
    deliverables: ['Sitio de 4–6 páginas', 'Escena WebGL optimizada', 'Sistema de animación GSAP/Motion', 'Optimización LCP/CLS', 'Hosting NVMe + CDN'],
    priceFrom: 7800000,
    timeline: '5–7 semanas',
    badge: 'SOTA',
    generationPrompt: [
      'Genera un sitio web INTERACTIVO 3D / KINÉTICO de calidad premiada (Awwwards).',
      'STACK: React + Tailwind + Three.js (WebGL) + Motion/GSAP. SSR para el contenido, escena 3D hidratada en cliente.',
      'PALETA (exacta): fondo #070A12, superficie #0F1626, texto #EAF0FF, acento azul #5BA8FF, acento violeta #9D7BFF. Glow sutil, nunca saturado.',
      'TIPOGRAFÍA: heading grotesk geométrica (Space Grotesk o Aeonik), body Inter; tracking ajustado en titulares.',
      'HERO: escena WebGL reactiva al scroll y al movimiento del cursor (objeto abstracto, partículas o malla deformable con shader). Degradación elegante a imagen estática si no hay WebGL.',
      'SECCIONES: (1) Hero 3D; (2) Demo de producto con mockup flotante; (3) Características reveladas por scroll cinético (pin + parallax); (4) Métricas con count-up; (5) Integraciones en grilla; (6) CTA inmersivo a pantalla completa.',
      'MOVIMIENTO: GSAP ScrollTrigger para pinning, transiciones de página, microinteracciones en hover. 60fps objetivo; usar transform/opacity.',
      'RENDIMIENTO: lazy-load de la escena, presupuesto de polígonos bajo, LCP < 2.5s, CLS < 0.1, fetchpriority en el hero.',
      'COPY: tono visionario pero claro, en español; enfatizar innovación y resultados.',
      'ENTREGABLE: 4–6 páginas, escena WebGL optimizada y documentada, sistema de animación reutilizable, informe de rendimiento.',
    ].join('\n'),
  },
  {
    id: 'minimal-luxe',
    name: 'Minimal Luxe',
    vibe: 'minimal-luxe',
    tagline: 'Lujo silencioso. Espacio, detalle y una sola idea por pantalla.',
    description:
      'Minimalismo premium: negro y crema, espacio generoso, microtipografía precisa. El menos-es-más que asocia tu marca con calidad y exclusividad.',
    idealFor: ['Marcas de lujo', 'Arquitectura e interiorismo', 'Joyería y moda', 'Hospitality premium'],
    palette: { bg: '#0A0A0A', surface: '#141414', text: '#F5F5F5', accent: '#C8A96A', accent2: '#8A8A8A' },
    typography: { heading: 'Canela / Cormorant', body: 'Neue Haas / Inter', note: 'Serif elegante + grotesk neutra' },
    motion: 'Fades lentos, easing suave, imágenes que respiran con micro-zoom. Nada estridente.',
    sections: ['Hero a pantalla completa', 'Filosofía', 'Colección / portafolio', 'Detalle de producto', 'Experiencia', 'Contacto privado'],
    deliverables: ['Sitio de 5–6 páginas', 'Galería de alta resolución', 'Sistema de espaciado y tipografía', 'SEO técnico', 'Hosting NVMe'],
    priceFrom: 5400000,
    timeline: '4–5 semanas',
    generationPrompt: [
      'Genera un sitio web de LUJO MINIMALISTA ("quiet luxury").',
      'STACK: React + Tailwind, SSR, Motion para transiciones suaves.',
      'PALETA (exacta): fondo #0A0A0A, superficie #141414, texto #F5F5F5, acento oro suave #C8A96A, gris #8A8A8A. Sin gradientes ruidosos.',
      'TIPOGRAFÍA: heading serif elegante (Canela o Cormorant) en escala grande y ligera; body grotesk neutra (Neue Haas o Inter); tracking amplio en labels (uppercase, 0.2em).',
      'LAYOUT: una idea por pantalla, whitespace dominante, imágenes a sangre de alta resolución, retícula simple y precisa.',
      'SECCIONES: (1) Hero a pantalla completa con una imagen y una frase; (2) Filosofía breve; (3) Colección/portafolio en galería sobria; (4) Detalle con foco en materiales/calidad; (5) Experiencia; (6) Contacto privado (tono concierge).',
      'MOVIMIENTO: fades lentos (400–600ms), easing suave, micro-zoom en imágenes al entrar en viewport. Nada brusco.',
      'COPY: minimal, sofisticado, en español; verbos sensoriales, frases cortas.',
      'ENTREGABLE: 5–6 páginas, galería optimizada, sistema de espaciado/tipografía documentado, SEO técnico.',
    ].join('\n'),
  },
  {
    id: 'neo-brutalist',
    name: 'Neo-Brutalist',
    vibe: 'neo-brutalist',
    tagline: 'Contraste duro, bordes marcados, actitud. Imposible de ignorar.',
    description:
      'Brutalismo digital moderno: bloques sólidos, bordes gruesos, color de alto contraste y tipografía enorme. Para marcas jóvenes y audaces que quieren romper el molde.',
    idealFor: ['Marcas jóvenes / streetwear', 'Eventos y música', 'Productos digitales disruptivos', 'Comunidades creativas'],
    palette: { bg: '#FDFD96', surface: '#FFFFFF', text: '#0A0A0A', accent: '#FF4D2E', accent2: '#1B4DFF' },
    typography: { heading: 'Archivo Black / Anton', body: 'Space Mono / IBM Plex Mono', note: 'Grotesk pesada + mono' },
    motion: 'Snaps duros, hover con desplazamiento de sombra, cursor personalizado, sin easing suave.',
    sections: ['Hero de impacto', 'Propuesta directa', 'Servicios en bloques', 'Galería desordenada', 'FAQ sin filtro', 'CTA gritado'],
    deliverables: ['Sitio de 4–5 páginas', 'Sistema de bloques reutilizable', 'Cursor e interacciones custom', 'SEO on-page', 'Hosting NVMe'],
    priceFrom: 3800000,
    timeline: '3–4 semanas',
    generationPrompt: [
      'Genera un sitio web NEO-BRUTALISTA moderno, audaz y de alto contraste.',
      'STACK: React + Tailwind, SSR, Motion para snaps e interacciones.',
      'PALETA (exacta): fondo amarillo #FDFD96, superficie #FFFFFF, texto #0A0A0A, acento rojo #FF4D2E, acento azul #1B4DFF. Color plano, sin degradados.',
      'TIPOGRAFÍA: heading grotesk ultra-pesada (Archivo Black o Anton) ENORME; body monoespaciada (Space Mono o IBM Plex Mono).',
      'LAYOUT: bloques sólidos con bordes gruesos (3–4px negros), sombras duras desplazadas (box-shadow sin blur), retícula visible, asimetría intencional.',
      'SECCIONES: (1) Hero de impacto con titular gigantesco; (2) Propuesta directa sin rodeos; (3) Servicios en bloques apilados; (4) Galería deliberadamente desordenada; (5) FAQ con tono franco; (6) CTA "gritado" con botón enorme.',
      'MOVIMIENTO: transiciones duras (sin easing suave), hover que desplaza la sombra, cursor personalizado, microcopys juguetones.',
      'COPY: directo, con actitud, en español; segunda persona, frases imperativas.',
      'ACCESIBILIDAD: a pesar del estilo, mantener contraste AA y foco visible.',
      'ENTREGABLE: 4–5 páginas, sistema de bloques reutilizable, interacciones y cursor custom, SEO on-page.',
    ].join('\n'),
  },
  {
    id: 'warm-organic',
    name: 'Cálido Orgánico',
    vibe: 'warm-organic',
    tagline: 'Curvas suaves, tonos tierra, cercanía. Una marca que abraza.',
    description:
      'Diseño humano y acogedor: formas orgánicas, paleta terrosa, ilustración suave y fotografía natural. Genera confianza y calidez. Perfecto para bienestar, salud y servicios cercanos.',
    idealFor: ['Bienestar y terapia', 'Salud y nutrición', 'Productos naturales', 'Servicios locales cercanos'],
    palette: { bg: '#FBF6EF', surface: '#FFFFFF', text: '#2E2A24', accent: '#D97E3A', accent2: '#7C9070' },
    typography: { heading: 'Recoleta / Fraunces Soft', body: 'Mulish / Nunito Sans', note: 'Serif suave + sans redondeada' },
    motion: 'Entradas en blob, scroll calmado, hover con elevación tierna. Ritmo respirado.',
    sections: ['Hero acogedor', 'Promesa', 'Servicios con iconografía suave', 'Testimonios humanos', 'Equipo', 'Reserva / contacto'],
    deliverables: ['Sitio de 5–6 páginas', 'Sistema de formas orgánicas', 'Iconografía suave', 'Integración de reservas (opcional)', 'Hosting NVMe'],
    priceFrom: 3400000,
    timeline: '3–4 semanas',
    generationPrompt: [
      'Genera un sitio web CÁLIDO Y ORGÁNICO, humano y acogedor.',
      'STACK: React + Tailwind, SSR, Motion para entradas suaves.',
      'PALETA (exacta): fondo crema #FBF6EF, superficie #FFFFFF, texto #2E2A24, acento terracota #D97E3A, verde salvia #7C9070.',
      'TIPOGRAFÍA: heading serif suave (Recoleta o Fraunces Soft), body sans redondeada (Mulish o Nunito Sans); tono amable.',
      'LAYOUT: formas orgánicas (blobs, esquinas muy redondeadas 24–40px), composiciones generosas, fotografía natural y luminosa, ilustración ligera.',
      'SECCIONES: (1) Hero acogedor con foto cálida y promesa; (2) Promesa/valores; (3) Servicios con iconografía suave; (4) Testimonios humanos con foto; (5) Equipo cercano; (6) Reserva o contacto con tono amable.',
      'MOVIMIENTO: entradas con escala desde blob, scroll calmado, hover con elevación tierna y sombra difusa. Ritmo respirado, sin prisa.',
      'COPY: empático, cercano, en español; primera persona plural ("acompañamos", "cuidamos").',
      'ENTREGABLE: 5–6 páginas, sistema de formas orgánicas, set de iconos suaves, gancho para integración de reservas.',
    ].join('\n'),
  },
  {
    id: 'corporate-trust',
    name: 'Corporate Trust',
    vibe: 'corporate-trust',
    tagline: 'Estructura, claridad y solidez. La confianza que cierra negocios.',
    description:
      'Diseño corporativo limpio y estructurado: azul confianza, retículas ordenadas, datos y casos. Transmite seriedad y solidez para B2B, finanzas y servicios profesionales.',
    idealFor: ['B2B y corporativo', 'Finanzas y legal', 'Consultoría y servicios profesionales', 'Industria y manufactura'],
    palette: { bg: '#FFFFFF', surface: '#F4F7FB', text: '#0C1B2A', accent: '#1F5FBF', accent2: '#16A37A' },
    typography: { heading: 'Inter / Geist', body: 'Inter / Geist', note: 'Sans neutra y legible, un solo sistema' },
    motion: 'Reveals discretos, conteo de cifras, transiciones limpias. Sobriedad ante todo.',
    sections: ['Hero con propuesta de valor', 'Confianza / logos', 'Servicios estructurados', 'Casos con métricas', 'Equipo / credenciales', 'Contacto comercial'],
    deliverables: ['Sitio de 6–8 páginas', 'Sistema de componentes corporativo', 'Plantillas de caso y servicio', 'SEO técnico + datos estructurados', 'Hosting NVMe + SLA'],
    priceFrom: 4600000,
    timeline: '4–6 semanas',
    generationPrompt: [
      'Genera un sitio web CORPORATIVO de alta confianza para B2B / servicios profesionales.',
      'STACK: React + Tailwind, SSR, Motion para reveals discretos.',
      'PALETA (exacta): fondo #FFFFFF, superficie #F4F7FB, texto #0C1B2A, acento azul #1F5FBF, verde éxito #16A37A.',
      'TIPOGRAFÍA: un solo sistema sans neutra y legible (Inter o Geist) en pesos 400/600/700; jerarquía clara.',
      'LAYOUT: retículas ordenadas de 12 columnas, tarjetas limpias con borde sutil, mucho dato y prueba, sin adornos.',
      'SECCIONES: (1) Hero con propuesta de valor medible; (2) Franja de confianza (logos/credenciales); (3) Servicios estructurados en grilla; (4) Casos con métricas y resultados; (5) Equipo/credenciales; (6) Contacto comercial con formulario.',
      'MOVIMIENTO: reveals suaves al scroll, count-up en cifras, transiciones limpias. Nada llamativo; prioriza credibilidad.',
      'COPY: profesional, orientado a resultados y ROI, en español; respaldar afirmaciones con datos.',
      'ENTREGABLE: 6–8 páginas, sistema de componentes corporativo, plantillas de caso y servicio, datos estructurados (Organization, Service), SEO técnico.',
    ].join('\n'),
  },
  {
    id: 'retro-future',
    name: 'Retro-Future',
    vibe: 'retro-future',
    tagline: 'Nostalgia digital con tecnología de hoy. Estética con personalidad.',
    description:
      'Mezcla de estética retro (grano, CRT, neón, Y2K) con ejecución técnica moderna. Para marcas con personalidad fuerte que quieren destacar en cultura, gaming y entretenimiento.',
    idealFor: ['Gaming y esports', 'Música y cultura', 'Web3 y cripto', 'Productos con personalidad fuerte'],
    palette: { bg: '#0B0014', surface: '#170A2B', text: '#F2E9FF', accent: '#FF2E97', accent2: '#27E1C1' },
    typography: { heading: 'Monument Extended / Clash Display', body: 'Space Grotesk', note: 'Display extendida + grotesk' },
    motion: 'Glitch sutil, grano animado, glow neón, transiciones tipo scanline. Energía alta.',
    sections: ['Hero neón', 'Lore / historia', 'Features arcade', 'Galería con grano', 'Comunidad', 'CTA luminoso'],
    deliverables: ['Sitio de 4–6 páginas', 'Sistema de efectos (grano, glow, glitch)', 'Animaciones de carácter', 'Optimización de rendimiento', 'Hosting NVMe + CDN'],
    priceFrom: 5200000,
    timeline: '4–6 semanas',
    generationPrompt: [
      'Genera un sitio web RETRO-FUTURISTA (nostalgia digital + ejecución moderna).',
      'STACK: React + Tailwind, SSR, Motion/GSAP; efectos con CSS y canvas/shaders ligeros.',
      'PALETA (exacta): fondo #0B0014, superficie #170A2B, texto #F2E9FF, neón rosa #FF2E97, cian #27E1C1. Glow controlado.',
      'TIPOGRAFÍA: heading display extendida (Monument Extended o Clash Display), body Space Grotesk.',
      'LAYOUT: estética CRT/Y2K — grano sutil, líneas de scanline, marcos neón, badges arcade; mantener legibilidad.',
      'SECCIONES: (1) Hero neón con título luminoso; (2) Lore/historia de la marca; (3) Features estilo arcade en tarjetas; (4) Galería con grano y hover glitch; (5) Comunidad/redes; (6) CTA luminoso.',
      'MOVIMIENTO: glitch sutil en hover, grano animado, glow pulsante, transiciones tipo scanline. Energía alta pero sin marear.',
      'RENDIMIENTO: los efectos no deben costar el LCP; degradar en dispositivos lentos y respetar prefers-reduced-motion.',
      'COPY: con personalidad, cultural, en español; guiños retro sin perder claridad.',
      'ENTREGABLE: 4–6 páginas, sistema de efectos (grano/glow/glitch), animaciones de carácter, informe de rendimiento.',
    ].join('\n'),
  },
  {
    id: 'commerce-bold',
    name: 'Commerce Bold',
    vibe: 'commerce-bold',
    tagline: 'Tienda que vende. Producto al frente, conversión en cada scroll.',
    description:
      'E-commerce de alto rendimiento: producto protagonista, fichas claras, prueba social y checkout sin fricción. Diseño orientado a conversión sobre WooCommerce o headless.',
    idealFor: ['Tiendas online', 'Marcas DTC', 'Catálogos de producto', 'Lanzamientos y drops'],
    palette: { bg: '#FFFFFF', surface: '#0A0A0A', text: '#0A0A0A', accent: '#D97E3A', accent2: '#111111' },
    typography: { heading: 'Clash Grotesk / Satoshi', body: 'Satoshi / Inter', note: 'Grotesk moderna, alto impacto' },
    motion: 'Quick-view en hover, transiciones de galería, sticky add-to-cart, microfeedback de compra.',
    sections: ['Hero de producto', 'Grid de catálogo', 'Ficha de producto', 'Prueba social', 'Bundle / oferta', 'Checkout sin fricción'],
    deliverables: ['Tienda de 5–8 secciones', 'Plantilla de producto y categoría', 'Integración WooCommerce/headless', 'Optimización de conversión (CRO)', 'Hosting Business NVMe + CDN'],
    priceFrom: 6900000,
    timeline: '5–7 semanas',
    badge: 'E-commerce',
    generationPrompt: [
      'Genera una TIENDA ONLINE de alto rendimiento orientada a conversión.',
      'STACK: React + Tailwind, SSR; integración WooCommerce (REST) o headless commerce. Motion para microinteracciones.',
      'PALETA (exacta): fondo #FFFFFF, superficie oscura #0A0A0A, texto #0A0A0A, acento terracota #D97E3A, negro #111111. Producto protagonista, UI que no compite.',
      'TIPOGRAFÍA: heading grotesk moderna de alto impacto (Clash Grotesk o Satoshi), body Satoshi/Inter.',
      'LAYOUT: hero de producto a gran escala, grid de catálogo limpio, ficha con galería + variantes + prueba social, CTA de compra siempre visible.',
      'SECCIONES: (1) Hero de producto/colección; (2) Grid de catálogo filtrable; (3) Ficha de producto con quick-view; (4) Prueba social (reseñas, UGC); (5) Bundle/oferta con urgencia honesta; (6) Checkout sin fricción.',
      'MOVIMIENTO: quick-view en hover, transiciones de galería, sticky add-to-cart en móvil, microfeedback al añadir al carrito.',
      'CONVERSIÓN (CRO): jerarquía clara de CTA, envío/garantías visibles, reducir pasos de checkout, badges de confianza.',
      'RENDIMIENTO: imágenes optimizadas (WebP, lazy), LCP < 2.5s; SEO de producto con JSON-LD Product + Offer.',
      'ENTREGABLE: tienda con 5–8 secciones, plantillas de producto y categoría, integración de pagos, guía de CRO.',
    ].join('\n'),
  },
];

/** Helper: busca un paquete por id. */
export function getDesignPackage(id: string): DesignPackage | undefined {
  return DESIGN_PACKAGES.find((p) => p.id === id);
}
