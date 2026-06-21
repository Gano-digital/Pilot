/**
 * Glosario de términos técnicos del catálogo Gano Digital.
 * Cada término se explica en lenguaje claro, con una analogía cuando ayuda.
 * Usado por <GlossaryTerm> para mostrar tooltips pedagógicos.
 *
 * Las claves se buscan de forma case-insensitive. Mantén las definiciones
 * cortas (1-2 frases) — son tooltips, no artículos.
 */

export interface GlossaryEntry {
  term: string;
  short: string;
  long: string;
}

export const GLOSSARY: Record<string, GlossaryEntry> = {
  nvme: {
    term: 'NVMe',
    short: 'Almacenamiento ultrarrápido',
    long: 'Discos de estado sólido de última generación, hasta 6 veces más rápidos que un SSD común. Tu sitio carga archivos y consulta la base de datos casi al instante.',
  },
  waf: {
    term: 'WAF',
    short: 'Firewall de aplicación web',
    long: 'Un escudo que inspecciona cada visita antes de que llegue a tu sitio y bloquea ataques (inyecciones, bots, scraping) sin que tú tengas que hacer nada.',
  },
  ttfb: {
    term: 'TTFB',
    short: 'Tiempo hasta el primer byte',
    long: 'Cuánto tarda el servidor en empezar a responder. Mientras más bajo, más rápido se siente tu sitio y mejor te posiciona Google.',
  },
  lcp: {
    term: 'LCP',
    short: 'Largest Contentful Paint',
    long: 'El tiempo que tarda en aparecer el elemento principal de la página (la imagen o título grande). Es una de las métricas de velocidad que Google usa para rankear.',
  },
  cls: {
    term: 'CLS',
    short: 'Cumulative Layout Shift',
    long: 'Mide cuánto "salta" el contenido mientras carga. Un CLS bajo significa que los botones no se mueven justo cuando vas a hacer clic.',
  },
  whois: {
    term: 'WHOIS',
    short: 'Directorio público de dominios',
    long: 'La base de datos pública que muestra quién registró un dominio. La privacidad WHOIS oculta tus datos personales (nombre, correo, teléfono) de spammers.',
  },
  cdn: {
    term: 'CDN',
    short: 'Red de entrega de contenido',
    long: 'Copias de tu sitio repartidas en servidores por todo el mundo. Quien te visita carga desde el más cercano, así la web vuela en cualquier país.',
  },
  redis: {
    term: 'Redis',
    short: 'Caché en memoria',
    long: 'Una memoria ultrarrápida que guarda las consultas más usadas para no recalcularlas cada vez. Clave para que WooCommerce y WordPress respondan rápido bajo tráfico.',
  },
  ssl: {
    term: 'SSL',
    short: 'Certificado de seguridad',
    long: 'Activa el candado y el "https" en tu sitio, cifrando todo lo que viaja entre el visitante y tu web. Google penaliza los sitios que no lo tienen.',
  },
  'ssl wildcard': {
    term: 'SSL Wildcard',
    short: 'Certificado para todos tus subdominios',
    long: 'Un solo certificado que protege tu dominio y todos sus subdominios (tienda.tusitio.com, blog.tusitio.com…) sin tener que comprar uno para cada uno.',
  },
  ev: {
    term: 'EV',
    short: 'Validación Extendida',
    long: 'El nivel más alto de verificación de identidad para un certificado. Confirma que tu empresa es real y legalmente registrada — máxima confianza en el checkout.',
  },
  dv: {
    term: 'DV',
    short: 'Validación de Dominio',
    long: 'El certificado SSL más rápido de obtener: solo verifica que controlas el dominio. Suficiente para activar el candado y cifrar formularios.',
  },
  dmarc: {
    term: 'DMARC',
    short: 'Política anti-suplantación de correo',
    long: 'Le dice a los servidores qué hacer con correos que dicen venir de tu dominio pero no son legítimos. Evita que suplanten tu marca para hacer phishing.',
  },
  dkim: {
    term: 'DKIM',
    short: 'Firma criptográfica de correo',
    long: 'Una firma digital invisible en cada correo que envías. Prueba que el mensaje salió realmente de tu dominio y no fue alterado en el camino.',
  },
  spf: {
    term: 'SPF',
    short: 'Lista de remitentes autorizados',
    long: 'Define qué servidores tienen permiso para enviar correo en nombre de tu dominio. Reduce que tus mensajes caigan en spam.',
  },
  ddos: {
    term: 'DDoS',
    short: 'Ataque de denegación de servicio',
    long: 'Un ataque que satura tu sitio con tráfico falso masivo para tumbarlo. La protección anti-DDoS absorbe ese tráfico antes de que llegue a tu servidor.',
  },
  'anycast': {
    term: 'DNS Anycast',
    short: 'DNS distribuido globalmente',
    long: 'Tus registros DNS viven en múltiples servidores a la vez. La consulta se resuelve desde el más cercano, acelerando la primera conexión a tu sitio.',
  },
  staging: {
    term: 'Staging',
    short: 'Copia de pruebas de tu sitio',
    long: 'Un clon idéntico de tu web donde puedes probar cambios, plugins y actualizaciones sin riesgo. Si algo rompe, tu sitio en vivo nunca se entera.',
  },
  woocommerce: {
    term: 'WooCommerce',
    short: 'Tienda online sobre WordPress',
    long: 'El plugin de e-commerce más usado del mundo. Convierte tu WordPress en una tienda completa con carrito, pagos e inventario.',
  },
  'capa 7': {
    term: 'Capa 7',
    short: 'Nivel de aplicación',
    long: 'La capa más alta de la red, donde vive el contenido web real. Un firewall de Capa 7 entiende qué pide cada visita, no solo de dónde viene.',
  },
  owasp: {
    term: 'OWASP Top 10',
    short: 'Las 10 amenazas web más críticas',
    long: 'Una lista de referencia mundial con los riesgos de seguridad más comunes en aplicaciones web. Protegerse contra ellos es el estándar mínimo serio.',
  },
  ssh: {
    term: 'SSH',
    short: 'Acceso remoto seguro al servidor',
    long: 'Una conexión cifrada para controlar tu servidor desde la terminal. Te da poder total: instalar, configurar y automatizar sin límites.',
  },
  cpanel: {
    term: 'cPanel',
    short: 'Panel de control de hosting',
    long: 'La interfaz visual estándar para administrar tu hosting: correos, bases de datos, archivos y dominios, todo desde un mismo lugar.',
  },
  sla: {
    term: 'SLA',
    short: 'Acuerdo de nivel de servicio',
    long: 'El compromiso contractual de disponibilidad. Un SLA de 99.95% significa que el servicio garantiza estar arriba casi todo el tiempo, con respaldo si no se cumple.',
  },
  tld: {
    term: 'TLD',
    short: 'Extensión de dominio',
    long: 'La parte final de un dominio: .com, .co, .digital. Cada uno comunica algo distinto sobre tu marca y su origen.',
  },
};

/** Busca una entrada del glosario por término (case-insensitive). */
export function lookupTerm(key: string): GlossaryEntry | undefined {
  return GLOSSARY[key.toLowerCase().trim()];
}
