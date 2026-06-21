/**
 * Roadmap del emprendedor digital — el contenido pedagógico de la sala de lectura.
 * De lo esencial a lo complejo: cada etapa explica QUÉ se necesita, POR QUÉ importa,
 * una checklist accionable, y conecta con el servicio de Gano que lo resuelve.
 */

export type Stage = {
  id: string;
  number: string;
  kicker: string;
  title: string;
  /** Una frase que captura la esencia de la etapa. */
  essence: string;
  /** Párrafos de lectura — el "por qué" y el "cómo". */
  body: string[];
  /** Pasos concretos y accionables. */
  checklist: string[];
  /** El servicio de Gano que habilita esta etapa. */
  enabler: { label: string; href: string };
  /** Cita corta inspiradora que cierra la etapa. */
  pull?: string;
};

export const STAGES: Stage[] = [
  {
    id: 'fundamentos',
    number: '01',
    kicker: 'El punto de partida',
    title: 'Fundamentos: convierte una idea en un activo',
    essence: 'Antes de construir, decide qué estás construyendo y a nombre de quién.',
    body: [
      'Todo negocio digital empieza con una decisión que casi nadie toma en serio: el nombre. Tu dominio no es un detalle técnico — es la dirección permanente de tu negocio en internet, el activo que apreciará con los años y la primera señal de que vas en serio.',
      'En esta etapa no necesitas un producto perfecto. Necesitas claridad: a quién sirves, qué problema resuelves y bajo qué nombre. Un dominio propio (.com, .co) y un correo profesional con ese dominio te separan de inmediato del 90% que sigue usando una dirección de Gmail para cerrar negocios.',
      'Pensar en grande desde el día uno no cuesta más: cuesta lo mismo registrar un dominio que respaldará una marca de millones que uno que abandonarás en tres meses. La diferencia está en la intención con la que eliges.',
    ],
    checklist: [
      'Define en una frase a quién sirves y qué problema resuelves',
      'Registra tu dominio propio antes de que alguien más lo tome',
      'Activa un correo profesional con tu dominio (no @gmail)',
      'Asegura tu nombre en las redes que vas a usar',
    ],
    enabler: { label: 'Asegurar mi dominio y correo', href: '/catalogo' },
    pull: 'Tu dominio es el único activo digital que solo tú puedes poseer. Empieza por ahí.',
  },
  {
    id: 'presencia',
    number: '02',
    kicker: 'Existir de verdad',
    title: 'Presencia: un hogar que trabaja por ti 24/7',
    essence: 'Tu sitio web es el único vendedor que nunca duerme y nunca pide aumento.',
    body: [
      'Una vez tienes nombre, necesitas un lugar. No un perfil prestado en una red social que mañana cambia sus reglas, sino un sitio propio: tu hogar digital, sobre infraestructura que controlas.',
      'Aquí entra la palabra que mucha gente subestima: hosting. Es el terreno donde se levanta tu negocio. Un hosting lento o inestable es como abrir una tienda con la puerta trabada: la gente llega, espera, se frustra y se va. Cada segundo de carga de más cuesta visitantes, ranking en Google y ventas.',
      'No necesitas el plan más caro — necesitas el correcto para tu etapa. Un sitio en WordPress sobre discos NVMe, con SSL, caché y backups automáticos, es suficiente para lanzar con dignidad y crecer sin migrar a las primeras de cambio.',
    ],
    checklist: [
      'Elige una plataforma que puedas administrar (WordPress es el estándar)',
      'Contrata hosting con SSL, backups y soporte real',
      'Asegúrate de que cargue en menos de 2.5 segundos en móvil',
      'Conecta tu dominio y correo a la nueva casa',
    ],
    enabler: { label: 'Ver planes de hosting', href: '/catalogo' },
    pull: 'No alquiles tu presencia en plataformas que no controlas. Construye sobre terreno propio.',
  },
  {
    id: 'crecimiento',
    number: '03',
    kicker: 'Que te encuentren',
    title: 'Crecimiento: de tener un sitio a tener tráfico',
    essence: 'El mejor sitio del mundo es invisible si nadie sabe que existe.',
    body: [
      'Tener un sitio no es el final del camino — es el comienzo. Ahora el reto es que la gente correcta llegue a él. Y eso se construye con rendimiento, contenido y confianza.',
      'El rendimiento es SEO: Google premia a los sitios rápidos y seguros con mejores posiciones. Por eso la infraestructura no es separable del marketing — un sitio veloz literalmente se posiciona mejor y convierte más.',
      'A esto se suman las señales de confianza: un correo profesional que no cae en spam, certificados de seguridad visibles, tiempos de carga consistentes. El crecimiento no es magia; es la acumulación de decisiones técnicas correctas que el cliente percibe como "esta empresa es seria".',
    ],
    checklist: [
      'Optimiza la velocidad: CDN, caché y compresión de imágenes',
      'Publica contenido útil que responda lo que tu cliente busca',
      'Configura tu correo para máxima entregabilidad (SPF, DKIM)',
      'Mide: instala analítica y revisa qué páginas convierten',
    ],
    enabler: { label: 'Acelerar mi sitio', href: '/catalogo' },
    pull: 'La velocidad no es un lujo técnico. Es tu mejor estrategia de marketing.',
  },
  {
    id: 'escala',
    number: '04',
    kicker: 'Cuando funciona',
    title: 'Escala: soportar el éxito sin romperse',
    essence: 'El peor momento para caerse es justo cuando todos te están mirando.',
    body: [
      'Llega el día en que tu campaña funciona, tu producto se vuelve viral o una temporada alta dispara el tráfico. Ese es el examen real: tu infraestructura, ¿aguanta o colapsa?',
      'Escalar significa anticipar el crecimiento antes de necesitarlo. Pasar de un hosting compartido a un servidor con recursos dedicados, sumar caché avanzada, balanceo de carga y monitoreo. No es sobre-ingeniería: es seguro de vida para tu negocio en su mejor momento.',
      'Aquí es donde un aliado técnico marca la diferencia entre una noche de pánico y una migración planeada con calma. Crecer es un buen problema — pero solo si tienes a quien llamar cuando el problema aparece a las 2 a.m.',
    ],
    checklist: [
      'Monitorea consumo de recursos para anticipar saturación',
      'Migra a recursos dedicados (VPS) antes de tocar el techo',
      'Implementa caché avanzada y CDN global',
      'Define un plan de respaldo y recuperación ante desastres',
    ],
    enabler: { label: 'Explorar servidores VPS', href: '/catalogo' },
    pull: 'No escales cuando el servidor ya está en llamas. Escala cuando todavía respira.',
  },
  {
    id: 'soberania',
    number: '05',
    kicker: 'El destino',
    title: 'Soberanía: tu infraestructura es tuya',
    essence: 'La meta no es depender menos de la tecnología. Es dominarla.',
    body: [
      'La etapa final no es un producto que compras — es una posición que alcanzas. Soberanía digital significa que entiendes, controlas y eres dueño de la infraestructura sobre la que corre tu negocio. Que ningún proveedor, algoritmo o cambio de términos puede ponerte de rodillas.',
      'En la práctica, es tener tu dominio, tus datos, tus respaldos y tus servidores bajo tu control, con un equipo que conoce tu proyecto por nombre y responde por él. Es dejar de ser inquilino y volverte propietario.',
      'Ese es el negocio que un inversor mira con respeto y el que un cliente recomienda sin dudar. No se llega solo — se llega acompañado. Y ese acompañamiento, de extremo a extremo, es exactamente lo que hacemos.',
    ],
    checklist: [
      'Centraliza el control de dominio, hosting y correo bajo tu cuenta',
      'Ten respaldos propios, automáticos y verificados',
      'Trabaja con un equipo que responda por tu infraestructura',
      'Documenta tu arquitectura para no depender de una sola persona',
    ],
    enabler: { label: 'Hablar con un ingeniero', href: '/contact' },
    pull: 'Soberanía es saber que tu infraestructura es tuya — y que hay un humano que responde por ella.',
  },
];

/** Principios que enmarcan toda la lectura — el manifiesto educativo. */
export const PRINCIPLES = [
  {
    title: 'Empieza pequeño, piensa grande',
    desc: 'No necesitas todo el día uno. Necesitas las bases correctas que no tendrás que rehacer mañana.',
  },
  {
    title: 'La infraestructura es estrategia',
    desc: 'Cada decisión técnica — velocidad, seguridad, escala — es una decisión de negocio disfrazada.',
  },
  {
    title: 'Poseer vence a alquilar',
    desc: 'Construye sobre activos que controlas. Las plataformas prestadas cambian las reglas sin avisar.',
  },
];

/** Errores frecuentes que cuestan tiempo y dinero — sección de advertencia. */
export const COSTLY_MISTAKES = [
  {
    mistake: 'Elegir el hosting más barato',
    cost: 'Sitio lento, caídas en temporada alta y migraciones de emergencia que cuestan 10x lo ahorrado.',
  },
  {
    mistake: 'Usar correo @gmail para el negocio',
    cost: 'Pierdes credibilidad en cada email y entregas tu marca a una plataforma que no controlas.',
  },
  {
    mistake: 'No tener respaldos propios',
    cost: 'El día que algo falla, descubres que no había copia. Años de trabajo en cero.',
  },
  {
    mistake: 'Construir solo en redes sociales',
    cost: 'Un cambio de algoritmo o una suspensión y tu negocio desaparece de un día para otro.',
  },
];
