/**
 * Nuestra Filosofía — el marco intelectual de Gano Digital sobre la IA.
 *
 * No es marketing. Es una postura razonada y referenciada sobre cómo debe
 * entrar la inteligencia artificial a una empresa: aumentando a las personas,
 * supervisada por humanos, orientada a la colaboración y no al reemplazo.
 *
 * Cada afirmación de peso está anclada a una fuente real y reputable que el
 * lector puede buscar y leer por su cuenta. La evidencia habla; las
 * conclusiones son del lector.
 *
 * NOTA EDITORIAL: las cifras marcadas con `verify: true` deben confirmarse
 * contra la fuente original antes de cada actualización pública.
 */

export type Tenet = {
  number: string;
  title: string;
  body: string;
  /** id de la referencia que respalda el teneto, si aplica. */
  refIds?: string[];
};

export type Reference = {
  id: string;
  /** Autor o institución. */
  author: string;
  /** Título de la obra o artículo. */
  work: string;
  /** Publicación o editorial. */
  publication: string;
  year: string;
  /** Enlace a una fuente reputable donde el lector puede continuar. */
  href: string;
  /** Una línea de por qué importa esta fuente para nuestro argumento. */
  note: string;
};

export type Pillar = {
  id: string;
  kicker: string;
  title: string;
  /** Párrafos de desarrollo del argumento. */
  body: string[];
  /** ids de referencias citadas en este pilar. */
  refIds: string[];
  /** Dato o estadística destacable. */
  stat?: { value: string; label: string; refId: string; verify?: boolean };
};

/* ───────────────────────── TENETOS ───────────────────────── */

export const TENETS: Tenet[] = [
  {
    number: '01',
    title: 'La IA es un exoesqueleto, no un sustituto',
    body: 'La tecnología que más valor genera es la que complementa el trabajo humano, no la que solo lo imita para reemplazarlo. Diseñamos sistemas que amplifican el criterio de quien ya sabe — no que pretenden borrar a quien decide.',
    refIds: ['autor-2015', 'brynjolfsson-2022'],
  },
  {
    number: '02',
    title: 'Toda automatización seria lleva un humano al mando',
    body: 'La supervisión humana no es desconfianza hacia la máquina: es responsabilidad. Un modelo puede afirmar con total seguridad algo falso. Por eso el juicio humano es la autoridad última en cada flujo que construimos — y así lo exigen ya los marcos éticos y regulatorios serios del mundo.',
    refIds: ['unesco-2021', 'eu-ai-act-2024'],
  },
  {
    number: '03',
    title: 'La dirección de la tecnología es una elección, no un destino',
    body: 'No existe una ley natural que obligue a usar la IA para recortar personas "y ahorrar costos". La forma en que se adopta una tecnología es una decisión — social, política y empresarial. Nosotros elegimos orientar la IA hacia el beneficio compartido: más capacidad para el equipo, no menos equipo.',
    refIds: ['acemoglu-johnson-2023'],
  },
  {
    number: '04',
    title: 'Lo repetitivo a la máquina; lo humano a las personas',
    body: 'Automatizamos lo que agota y no requiere criterio, para devolverle a tu gente el tiempo que merece el trabajo que sí lo necesita: el juicio, el cuidado, la relación, la estrategia. La IA no le quita el empleo a tu equipo; le quita el tedio.',
    refIds: ['brynjolfsson-li-raymond-2023'],
  },
  {
    number: '05',
    title: 'La cercanía es infraestructura crítica',
    body: 'Los clientes valoran la eficiencia de un sistema automatizado y, al mismo tiempo, exigen hablar con una persona real cuando algo importa. Esa cercanía no es nostalgia: es ventaja competitiva. Detrás de cada agente que instalamos hay un humano disponible para los momentos que lo merecen.',
    refIds: ['chomsky-2023'],
  },
  {
    number: '06',
    title: 'Informar es respetar',
    body: 'No te pedimos que nos creas. Te damos la evidencia — estudios, datos, autores que puedes leer por tu cuenta — para que tomes decisiones informadas. Un cliente que entiende el terreno decide mejor, y un cliente que decide mejor es un mejor socio.',
  },
];

/* ───────────────────────── PILARES (MARCO TEÓRICO) ───────────────────────── */

export const PILLARS: Pillar[] = [
  {
    id: 'aumentacion',
    kicker: 'La evidencia económica',
    title: 'Aumentar rinde más que reemplazar',
    body: [
      'Durante dos siglos, el miedo a que las máquinas dejen a todos sin trabajo ha reaparecido en cada salto tecnológico — y, hasta ahora, ha resultado incompleto. El economista del MIT David Autor mostró que la automatización no solo destruye tareas: también crea otras nuevas y, sobre todo, vuelve más valioso el trabajo humano que complementa. La pregunta correcta no es "¿cuántos empleos elimina la IA?", sino "¿qué capacidades humanas amplifica?".',
      'Erik Brynjolfsson, de Stanford, le puso nombre al error opuesto: la "trampa de Turing". Cuando diseñamos IA para imitar y sustituir a las personas en lugar de aumentarlas, no solo desperdiciamos el mayor potencial de la tecnología: concentramos poder y riqueza en quien controla las máquinas. El camino que de verdad genera valor amplio es el de la IA aumentativa.',
      'Esto no es optimismo ingenuo. Es la lectura de la mejor evidencia disponible: la tecnología que se queda y prospera es la que hace a las personas más capaces, no la que las vuelve prescindibles.',
    ],
    refIds: ['autor-2015', 'brynjolfsson-2022'],
    stat: {
      value: '≈14%',
      label: 'de aumento de productividad en agentes de soporte asistidos por IA — con el mayor efecto entre los trabajadores menos experimentados, cerrando brechas en lugar de eliminar puestos.',
      refId: 'brynjolfsson-li-raymond-2023',
      verify: true,
    },
  },
  {
    id: 'supervision',
    kicker: 'La ética aplicada',
    title: 'El humano, siempre al mando',
    body: [
      'La supervisión humana dejó de ser una opinión bienintencionada para convertirse en norma. La Recomendación de la UNESCO sobre la Ética de la Inteligencia Artificial, adoptada en 2021 por 193 países, consagra la supervisión humana, la proporcionalidad y el principio de no causar daño como pilares de cualquier sistema de IA responsable.',
      'La Unión Europea fue más allá: su Reglamento de IA (AI Act, 2024) convierte el "control humano efectivo" en un requisito legal para los sistemas de alto riesgo. La dirección es clara en todo el mundo serio: la IA decide más rápido, pero el humano responde.',
      'Empresas como Anthropic —creadora de Claude— han construido su misión entera alrededor de esta idea: una IA que sea útil, honesta e inofensiva, con la seguridad y el bienestar humano como brújula. No es casualidad que la frontera técnica y la frontera ética converjan en el mismo punto: la máquina rinde mejor cuando un humano la guía.',
    ],
    refIds: ['unesco-2021', 'eu-ai-act-2024', 'anthropic-cai'],
  },
  {
    id: 'eleccion',
    kicker: 'La dirección del cambio',
    title: 'La tecnología es una elección, no un destino',
    body: [
      'En "Poder y Progreso", los economistas Daron Acemoglu y Simon Johnson —Acemoglu, premio Nobel de Economía en 2024— demuelen el mito de que el progreso tecnológico beneficia automáticamente a todos. A lo largo de mil años de historia, sostienen, quién gana y quién pierde con una nueva tecnología depende de decisiones humanas: de cómo se diseña, para qué se usa y a quién sirve.',
      'La lección es liberadora, no fatalista. Si la automatización "para ahorrar costos" concentra los beneficios en pocos, no es porque la IA lo imponga: es porque alguien lo eligió así. Y si una empresa decide, en cambio, usar la IA para que su gente haga más y mejor trabajo, eso también es una elección — la nuestra.',
      'Noam Chomsky añade el matiz necesario sobre la naturaleza de estos sistemas: confundir la fluidez estadística de un modelo de lenguaje con comprensión genuina es un error de categoría. La IA es una herramienta extraordinariamente poderosa; no es un sujeto que sepa, entienda o decida por sí mismo. Quien sabe, entiende y decide sigue siendo el humano.',
    ],
    refIds: ['acemoglu-johnson-2023', 'chomsky-2023'],
  },
  {
    id: 'cercania',
    kicker: 'El argumento de negocio',
    title: 'La cercanía humana es ventaja competitiva',
    body: [
      'Hay una paradoja bien documentada en la experiencia de cliente: la gente quiere la velocidad de la automatización y, a la vez, valora profundamente poder hablar con una persona real en los momentos que cuentan. Las dos cosas no compiten — se complementan.',
      'La estrategia híbrida gana en los dos frentes. La IA atiende lo inmediato, lo repetitivo y lo de volumen, a cualquier hora; el humano entra donde su criterio y su calidez marcan la diferencia: la queja delicada, la decisión importante, la relación que se cultiva. Eliminar al humano "para ahorrar" no es eficiencia: es regalar lo único que un competidor no puede copiar.',
      'Por eso no vendemos reemplazo. Vendemos un equipo aumentado, respaldado siempre por personas reales.',
    ],
    refIds: ['cx-paradox'],
  },
];

/* ───────────────────────── REFERENCIAS ───────────────────────── */

export const REFERENCES: Reference[] = [
  {
    id: 'autor-2015',
    author: 'David H. Autor',
    work: 'Why Are There Still So Many Jobs? The History and Future of Workplace Automation',
    publication: 'Journal of Economic Perspectives, 29(3)',
    year: '2015',
    href: 'https://www.aeaweb.org/articles?id=10.1257/jep.29.3.3',
    note: 'La automatización complementa el trabajo humano tanto como lo sustituye; la tecnología aumentativa crea valor duradero.',
  },
  {
    id: 'brynjolfsson-2022',
    author: 'Erik Brynjolfsson',
    work: 'The Turing Trap: The Promise & Peril of Human-Like Artificial Intelligence',
    publication: 'Daedalus, 151(2) — American Academy of Arts & Sciences',
    year: '2022',
    href: 'https://www.amacad.org/publication/turing-trap-promise-peril-human-artificial-intelligence',
    note: 'Diseñar IA para imitar y reemplazar humanos concentra poder; la IA aumentativa libera su verdadero potencial.',
  },
  {
    id: 'brynjolfsson-li-raymond-2023',
    author: 'Erik Brynjolfsson, Danielle Li y Lindsey Raymond',
    work: 'Generative AI at Work',
    publication: 'National Bureau of Economic Research (NBER), Working Paper 31161',
    year: '2023',
    href: 'https://www.nber.org/papers/w31161',
    note: 'Estudio de campo: la IA elevó la productividad y benefició más a los trabajadores con menos experiencia.',
  },
  {
    id: 'acemoglu-johnson-2023',
    author: 'Daron Acemoglu y Simon Johnson',
    work: 'Power and Progress: Our Thousand-Year Struggle Over Technology and Prosperity',
    publication: 'PublicAffairs',
    year: '2023',
    href: 'https://shapingwork.mit.edu/power-and-progress/',
    note: 'Quién gana con una nueva tecnología depende de decisiones humanas, no de un destino inevitable.',
  },
  {
    id: 'chomsky-2023',
    author: 'Noam Chomsky, Ian Roberts y Jeffrey Watumull',
    work: 'The False Promise of ChatGPT',
    publication: 'The New York Times — Opinión',
    year: '2023',
    href: 'https://www.nytimes.com/2023/03/08/opinion/noam-chomsky-chatgpt-ai.html',
    note: 'Distingue la imitación estadística del lenguaje de la comprensión y el juicio genuinamente humanos.',
  },
  {
    id: 'unesco-2021',
    author: 'UNESCO',
    work: 'Recomendación sobre la Ética de la Inteligencia Artificial',
    publication: 'Organización de las Naciones Unidas para la Educación, la Ciencia y la Cultura',
    year: '2021',
    href: 'https://www.unesco.org/es/artificial-intelligence/recommendation-ethics',
    note: 'Marco global, adoptado por 193 países: supervisión humana, proporcionalidad y no causar daño.',
  },
  {
    id: 'eu-ai-act-2024',
    author: 'Parlamento Europeo y Consejo de la UE',
    work: 'Reglamento de Inteligencia Artificial (AI Act)',
    publication: 'Unión Europea',
    year: '2024',
    href: 'https://artificialintelligenceact.eu/',
    note: 'Convierte el control humano efectivo en requisito legal para los sistemas de IA de alto riesgo.',
  },
  {
    id: 'anthropic-cai',
    author: 'Anthropic',
    work: 'Constitutional AI: Harmlessness from AI Feedback',
    publication: 'Anthropic — Investigación',
    year: '2022',
    href: 'https://www.anthropic.com/research/constitutional-ai-harmlessness-from-ai-feedback',
    note: 'Una IA útil, honesta e inofensiva: la seguridad y el bienestar humano como principio de diseño.',
  },
  {
    id: 'cx-paradox',
    author: 'PwC',
    work: 'Experience is everything: Here\u2019s how to get it right',
    publication: 'PwC — Consumer Intelligence Series',
    year: '2018',
    href: 'https://www.pwc.com/us/en/services/consulting/library/consumer-intelligence-series/future-of-customer-experience.html',
    note: 'Los clientes adoptan la tecnología pero siguen valorando la interacción humana en los momentos clave.',
  },
];

export const PHILOSOPHY_INTRO =
  'Creemos que la inteligencia artificial bien aplicada no reemplaza personas: las potencia. Esta es la postura que guía cada sistema que construimos — razonada, referenciada y abierta a tu propio escrutinio.';
