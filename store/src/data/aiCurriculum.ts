/**
 * Temario de IA aplicada — el contenido pedagógico de la sala de lectura.
 *
 * No es un curso de teoría. Es la guía honesta de cómo se trabaja HOY con
 * inteligencia artificial en un negocio digital real: qué herramientas usar,
 * con qué procesos, bajo qué prácticas y guidelines, y por qué la documentación
 * es lo que separa un experimento de un sistema.
 *
 * Cada módulo explica QUÉ es, POR QUÉ importa, una checklist accionable y
 * conecta con un recurso o servicio. Mismo lenguaje editorial que el roadmap.
 */

export type AIModule = {
  id: string;
  number: string;
  /** Etiqueta corta para el índice. */
  kicker: string;
  title: string;
  /** Una frase que captura la esencia del módulo. */
  essence: string;
  /** Párrafos de lectura — el "por qué" y el "cómo". */
  body: string[];
  /** Pasos concretos y accionables. */
  checklist: string[];
  /** Recurso o acción que profundiza el módulo. */
  resource: { label: string; href: string; external?: boolean };
  /** Cita corta que cierra el módulo. */
  pull?: string;
};

export const AI_MODULES: AIModule[] = [
  {
    id: 'tooling',
    number: '01',
    kicker: 'Tooling',
    title: 'Tooling: el taller de quien trabaja con IA',
    essence: 'La IA no reemplaza tu criterio. Amplifica al que ya sabe lo que hace.',
    body: [
      'Antes de hablar de prompts y modelos, define tu taller. Trabajar con IA en serio no es abrir un chat y pedir cosas: es ensamblar un conjunto de herramientas que se complementan — un modelo de razonamiento para pensar, uno rápido para tareas de volumen, un editor con IA para escribir código, y un espacio donde todo eso convive con tus datos.',
      'Hoy el estándar profesional combina tres familias: asistentes conversacionales de frontera (Claude, GPT, Gemini) para razonamiento y redacción; copilotos integrados en el editor para desarrollo; y agentes capaces de ejecutar tareas — buscar, llamar APIs, leer documentos. La clave no es usar el más nuevo, sino el correcto para cada trabajo: un modelo grande para decisiones complejas, uno ligero para clasificar mil correos.',
      'El error más común es tratar a la IA como un oráculo infalible. La herramienta brilla cuando la guía alguien que sabe distinguir una buena respuesta de una plausible pero equivocada. Por eso el tooling empieza en ti: tu juicio es la pieza más importante del taller.',
    ],
    checklist: [
      'Elige un asistente de frontera como base (Claude para razonamiento y escritura larga)',
      'Suma un copiloto en tu editor si desarrollas o automatizas',
      'Separa modelos por trabajo: razonamiento profundo vs. tareas de volumen',
      'Nunca uses datos sensibles sin entender la política de privacidad del proveedor',
    ],
    resource: {
      label: 'Probar Claude con mi enlace',
      href: 'https://claude.ai/referral/PZmqsZRSUg?s=cowork&v=apps',
      external: true,
    },
    pull: 'La mejor herramienta de IA es la que multiplica tu criterio, no la que pretende sustituirlo.',
  },
  {
    id: 'procesos',
    number: '02',
    kicker: 'Procesos',
    title: 'Procesos: del prompt suelto al flujo repetible',
    essence: 'Un prompt genial que no puedes repetir no es una capacidad. Es suerte.',
    body: [
      'La diferencia entre jugar con IA y trabajar con IA es el proceso. Un resultado brillante que ocurrió una vez, sin que sepas cómo replicarlo, no le sirve a tu negocio. Lo que escala es el flujo: una secuencia que entra cualquier persona del equipo y produce el mismo nivel de calidad.',
      'Un buen proceso descompone el trabajo en pasos: contexto → instrucción → ejecución → revisión. Le das al modelo el contexto correcto (quién eres, qué buscas, ejemplos), una instrucción precisa, lo dejas ejecutar y — crítico — revisas con criterio humano antes de publicar. Ese último paso no es opcional; es lo que mantiene tu marca a salvo.',
      'Cuando un flujo funciona, lo conviertes en plantilla. Plantillas de prompt versionadas, con variables, que cualquiera puede usar. Así la IA deja de ser un truco de una persona y se vuelve una capacidad de la organización — auditable, mejorable, transferible.',
    ],
    checklist: [
      'Descompón cada tarea en contexto → instrucción → ejecución → revisión',
      'Convierte los prompts que funcionan en plantillas con variables',
      'Define siempre un paso de revisión humana antes de publicar',
      'Versiona tus prompts como versionas tu código: con historial',
    ],
    resource: { label: 'Ver catálogo de diseño con prompts listos', href: '/disenos' },
    pull: 'La IA escala cuando dejas de improvisar prompts y empiezas a diseñar procesos.',
  },
  {
    id: 'practicas',
    number: '03',
    kicker: 'Prácticas',
    title: 'Prácticas: cómo obtener respuestas que sirven',
    essence: 'El modelo es tan bueno como el contexto que le das. Basura entra, basura sale.',
    body: [
      'Las buenas prácticas con IA son sorprendentemente concretas. La primera: da contexto antes de pedir. Un modelo no adivina tu marca, tu cliente ni tu tono — díselo. Cuanto más rico el contexto (rol, audiencia, ejemplos de lo que te gusta), más afilada la respuesta.',
      'La segunda: pide formato explícito. Si quieres una tabla, una lista, un tono formal o 200 palabras exactas, dilo. Los modelos cumplen instrucciones precisas mucho mejor que deseos vagos. Y cuando la primera respuesta no da en el blanco, no empieces de cero: itera. "Más corto", "menos técnico", "con un ejemplo" refina sin perder lo bueno.',
      'La tercera, la que separa a los profesionales: verifica los hechos. Los modelos pueden afirmar con total seguridad algo falso — se llama alucinación. Para datos, cifras, citas legales o médicas, la IA te da el borrador; tú confirmas la verdad. Nunca publiques un dato que la IA inventó como si fuera tuyo.',
    ],
    checklist: [
      'Da rol, audiencia y ejemplos antes de pedir el resultado',
      'Especifica formato, extensión y tono de forma explícita',
      'Itera sobre la respuesta en vez de reescribir el prompt entero',
      'Verifica todo dato, cifra o cita: la IA redacta, tú confirmas',
    ],
    resource: { label: 'Hablar con un ingeniero sobre tu flujo', href: '/contact' },
    pull: 'Un prompt vago da una respuesta vaga. La precisión que pides es la calidad que recibes.',
  },
  {
    id: 'guidelines',
    number: '04',
    kicker: 'Guidelines',
    title: 'Guidelines: la línea entre potencia y riesgo',
    essence: 'La IA amplifica lo que le das: tu mejor juicio o tu peor descuido.',
    body: [
      'Adoptar IA sin reglas claras es como darle las llaves del auto a todo el equipo sin explicar las señales de tránsito. Las guidelines no frenan la innovación — la hacen segura y sostenible. Y se resumen en tres frentes: datos, transparencia y responsabilidad.',
      'Datos: nunca metas información sensible — datos de clientes, contraseñas, secretos comerciales — en herramientas sin entender qué hacen con ellos. Asume que lo que escribes puede entrenar al modelo, salvo que el proveedor garantice lo contrario por contrato. La regla simple: si no lo dirías en voz alta en un café, no lo pegues en un chat.',
      'Transparencia y responsabilidad: define cuándo divulgas que un contenido fue asistido por IA, quién revisa antes de publicar y quién responde si algo sale mal. La IA no es responsable de nada — tú sí. Una política de una página, escrita en lenguaje claro, evita el 90% de los problemas antes de que ocurran.',
    ],
    checklist: [
      'Prohíbe datos sensibles en herramientas sin garantía de privacidad',
      'Define qué contenido se divulga como asistido por IA',
      'Asigna siempre un responsable humano de la revisión final',
      'Escribe una política de uso de IA de una página, en lenguaje claro',
    ],
    resource: { label: 'Asegurar mi infraestructura y datos', href: '/catalogo' },
    pull: 'No necesitas un comité ni un abogado. Necesitas una página de reglas claras que todos respeten.',
  },
  {
    id: 'documentacion',
    number: '05',
    kicker: 'Documentación',
    title: 'Documentación: lo que vuelve repetible al genio',
    essence: 'Lo que no está documentado vive en una sola cabeza — y esa cabeza se va.',
    body: [
      'La documentación es la etapa que todos saltan y todos lamentan. Es lo que convierte un hallazgo afortunado en un activo permanente del negocio. Sin ella, el conocimiento de IA vive en la cabeza de una persona; con ella, vive en la organización.',
      'Documentar IA es sencillo y se sostiene en tres piezas: una biblioteca de prompts que funcionan (qué hacen, cuándo usarlos, qué variables aceptan); un registro de decisiones (qué modelo elegimos, por qué, qué descartamos); y guías de uso para que alguien nuevo produzca calidad desde el primer día sin reinventar lo aprendido.',
      'La buena noticia: la propia IA es excelente documentando. Pídele que resuma un flujo, que escriba la guía de un proceso, que genere ejemplos. Lo que antes tomaba horas hoy toma minutos. La documentación dejó de ser la excusa — ahora es la ventaja de quien la hace.',
    ],
    checklist: [
      'Crea una biblioteca de prompts probados con su propósito y variables',
      'Registra qué modelos y decisiones tomaste, y por qué',
      'Escribe una guía de inicio para que alguien nuevo produzca desde el día uno',
      'Usa la propia IA para redactar y mantener la documentación al día',
    ],
    resource: { label: 'Ver cómo documentamos cada proyecto', href: '/proceso' },
    pull: 'Documentar no es burocracia. Es convertir un golpe de suerte en una capacidad que se queda.',
  },
];

/** Principios que enmarcan el temario de IA — el manifiesto. */
export const AI_PRINCIPLES = [
  {
    title: 'La IA amplifica, no sustituye',
    desc: 'Multiplica el criterio de quien ya sabe. Tu juicio sigue siendo la pieza que más importa.',
  },
  {
    title: 'El contexto es el producto',
    desc: 'La calidad de la respuesta nace de la calidad de lo que le das. Invierte en el contexto, no en el truco.',
  },
  {
    title: 'Repetible vence a brillante',
    desc: 'Un resultado genial que no puedes replicar no escala. Diseña procesos, no golpes de suerte.',
  },
];

/** Mitos frecuentes sobre IA que cuestan tiempo y credibilidad. */
export const AI_MISTAKES = [
  {
    mistake: 'Tratar a la IA como un oráculo infalible',
    cost: 'Publicas un dato inventado como verdadero y pierdes en un instante la credibilidad que tardaste años en construir.',
  },
  {
    mistake: 'Pegar datos sensibles en cualquier herramienta',
    cost: 'Información de clientes o secretos del negocio terminan donde no debían, sin forma de recuperarlos.',
  },
  {
    mistake: 'No documentar lo que funciona',
    cost: 'El conocimiento se va con la persona que lo descubrió y el equipo vuelve a empezar de cero.',
  },
  {
    mistake: 'Perseguir cada modelo nuevo',
    cost: 'Gastas energía cambiando de herramienta en vez de dominar un flujo que ya te daba resultados.',
  },
];

/** Enlace de referido del usuario para Claude. */
export const CLAUDE_REFERRAL_URL = 'https://claude.ai/referral/PZmqsZRSUg?s=cowork&v=apps';
