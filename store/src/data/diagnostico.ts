/**
 * Diagnóstico Operativo 360° — Gano Digital
 *
 * Herramienta de cualificación de leads. Cada pregunta indaga por un área de la
 * operación de una empresa y cada respuesta se mapea a un servicio CONCRETO del
 * catálogo (catalog.ts → Service.id) o, cuando la necesidad es compleja / no
 * estándar, a `advisory` para condicionar al lead a una asesoría profesional.
 *
 * El motor de recomendación (scoring) vive en este archivo y es puro (sin estado),
 * de modo que tanto el frontend (preview instantáneo) como el backend (informe
 * por email) producen exactamente el mismo resultado.
 */

export type Severity = 'oportunidad' | 'atencion' | 'critico';

/** Recomendación resuelta que se muestra al lead y se envía por email. */
export interface Recommendation {
  /** id de catálogo (Service.id) o 'advisory' para asesoría profesional. */
  serviceId: string;
  /** Título legible (no depende del catálogo para el email de texto plano). */
  title: string;
  /** Por qué se recomienda — frase orientada al dolor detectado. */
  reason: string;
  /** Ruta interna o ancla donde el lead puede profundizar. */
  href: string;
  severity: Severity;
}

export interface DiagnosticoOption {
  /** valor estable que viaja al backend. */
  value: string;
  label: string;
  /** Texto corto de apoyo bajo la opción. */
  hint?: string;
  /**
   * Servicios que dispara esta opción. Vacío = no genera recomendación
   * (p. ej. "ya lo tengo cubierto").
   */
  triggers: Recommendation[];
}

export interface DiagnosticoStep {
  id: string;
  /** Área de la operación. */
  area: string;
  /** Pregunta orientada al cliente. */
  question: string;
  /** Subtítulo / contexto. */
  helper: string;
  /** Icono lucide (resuelto en el componente). */
  icon: string;
  /** ¿Permite seleccionar varias opciones? */
  multiSelect: boolean;
  options: DiagnosticoOption[];
}

// Atajos de recomendación reutilizables ───────────────────────────────────
const ADVISORY: Recommendation = {
  serviceId: 'advisory',
  title: 'Asesoría profesional personalizada',
  reason:
    'Tu caso tiene aristas que ninguna plantilla resuelve bien. Vale la pena una conversación con criterio antes de invertir.',
  href: '/contact',
  severity: 'atencion',
};

const DIAGNOSTICO_SOBERANIA: Recommendation = {
  serviceId: 'diagnostico',
  title: 'Diagnóstico de Soberanía Digital',
  reason:
    'Una auditoría técnica completa para mapear todo tu stack antes de tomar decisiones a ciegas. 100% acreditable a un plan anual.',
  href: '/catalogo',
  severity: 'atencion',
};

export const DIAGNOSTICO_STEPS: DiagnosticoStep[] = [
  // 1 ─ PRESENCIA WEB / RECURSOS EXISTENTES ────────────────────────────────
  {
    id: 'presencia',
    area: 'Presencia web',
    question: '¿Cómo está hoy la presencia digital de tu negocio?',
    helper: 'El punto de partida define todo lo demás.',
    icon: 'globe',
    multiSelect: false,
    options: [
      {
        value: 'nada',
        label: 'No tengo sitio web todavía',
        hint: 'Estoy empezando de cero',
        triggers: [
          {
            serviceId: 'website-builder-plus',
            title: 'Website Builder Plus',
            reason: 'Estar en Google rápido sin equipo técnico: tu sitio en línea en 30 minutos, con dominio incluido.',
            href: '/services',
            severity: 'oportunidad',
          },
          {
            serviceId: 'dom-com',
            title: 'Dominio propio (.com / .co)',
            reason: 'Asegura tu nombre en internet antes de que alguien más lo haga. Es un activo que se revaloriza.',
            href: '/catalogo',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'redes-solo',
        label: 'Solo redes sociales (Instagram, Facebook…)',
        hint: 'Vendo por WhatsApp y redes',
        triggers: [
          {
            serviceId: 'builder-marketing',
            title: 'Builder Plus + Marketing',
            reason: 'Dejar de depender solo de un algoritmo ajeno: un sitio propio con email marketing y redes programadas.',
            href: '/services',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'wordpress',
        label: 'Tengo un sitio WordPress',
        hint: 'Propio o con otro proveedor',
        triggers: [
          {
            serviceId: 'pro-managed',
            title: 'WordPress Pro Managed',
            reason: 'Hosting administrado que no se cae, con velocidad y seguridad gestionadas por nosotros.',
            href: '/services',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'custom',
        label: 'Tengo una app o sistema a medida',
        hint: 'Desarrollo propio, ERP, plataforma…',
        triggers: [
          {
            serviceId: 'hosting-plus-dev',
            title: 'Web Hosting Plus Dev',
            reason: 'Entorno con SSH, PHP, Python y Node.js para proyectos que se salen de WordPress.',
            href: '/catalogo',
            severity: 'oportunidad',
          },
          ADVISORY,
        ],
      },
    ],
  },

  // 2 ─ CORREO Y COMUNICACIONES EMPRESARIALES ──────────────────────────────
  {
    id: 'correo',
    area: 'Comunicaciones',
    question: '¿Cómo se comunica tu empresa con clientes y equipo?',
    helper: 'El correo es la cara escrita de tu marca.',
    icon: 'mail',
    multiSelect: false,
    options: [
      {
        value: 'gmail-personal',
        label: 'Con Gmail / Hotmail personal',
        hint: 'tunegocio@gmail.com',
        triggers: [
          {
            serviceId: 'microsoft-365-basic',
            title: 'Microsoft 365 Business Basic',
            reason: 'La diferencia entre parecer freelance y empresa seria: hola@tuempresa.com con Teams y 1 TB de OneDrive.',
            href: '/catalogo',
            severity: 'atencion',
          },
          {
            serviceId: 'email-starter',
            title: 'Email profesional con tu dominio',
            reason: 'Un buzón con tu dominio propio, configurado con DKIM y SPF para que tus correos lleguen y no caigan en spam.',
            href: '/catalogo',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'dominio-basico',
        label: 'Correo con mi dominio, pero básico',
        hint: 'Funciona, pero sin herramientas',
        triggers: [
          {
            serviceId: 'email-pro',
            title: 'Email Pro',
            reason: 'Anti-phishing, DMARC y archivo legal de 7 años — clave si manejas información sensible o sector regulado.',
            href: '/catalogo',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'colaboracion',
        label: 'Necesito colaborar (documentos, videollamadas)',
        hint: 'Equipo trabajando junto',
        triggers: [
          {
            serviceId: 'microsoft-365-basic',
            title: 'Microsoft 365 Business Basic',
            reason: 'Teams, SharePoint y OneDrive con tu dominio: todo el equipo trabajando sobre los mismos archivos, sin caos.',
            href: '/catalogo',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'ya-tengo',
        label: 'Ya tengo correo profesional sólido',
        hint: 'M365 / Workspace funcionando',
        triggers: [],
      },
    ],
  },

  // 3 ─ MARKETING Y PUBLICIDAD ─────────────────────────────────────────────
  {
    id: 'marketing',
    area: 'Marketing y ads',
    question: '¿Cómo consigues clientes nuevos hoy?',
    helper: 'Atraer clientes de forma predecible es el motor del crecimiento.',
    icon: 'megaphone',
    multiSelect: true,
    options: [
      {
        value: 'voz-a-voz',
        label: 'Voz a voz / referidos',
        hint: 'Sin canal digital predecible',
        triggers: [
          {
            serviceId: 'builder-marketing',
            title: 'Builder Plus + Marketing',
            reason: 'Convierte el voz a voz en un sistema: email marketing, formularios y redes programadas para captar leads.',
            href: '/services',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'redes-organico',
        label: 'Publico en redes, pero sin estrategia',
        hint: 'Contenido cuando puedo',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Estrategia de contenido y redes',
            reason: 'Publicar sin estrategia quema tiempo. Te ayudo a definir un plan de contenido y, si quieres, a automatizarlo con IA.',
            href: '/soluciones-ia',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'ads',
        label: 'Quiero invertir en pauta (Meta / Google Ads)',
        hint: 'Anuncios pagados',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Acompañamiento en publicidad digital',
            reason: 'La pauta mal configurada quema presupuesto. Conversemos cómo estructurar campañas que sí conviertan.',
            href: '/contact',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'automatizar',
        label: 'Quiero que la captación funcione sola',
        hint: 'Agentes que califican y atienden leads',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'IA aplicada · captación automatizada',
            reason: 'Un agente que califica leads, responde y nutre prospectos 24/7 a través de tus redes y embudos.',
            href: '/soluciones-ia',
            severity: 'oportunidad',
          },
        ],
      },
    ],
  },

  // 4 ─ REDES SOCIALES Y COMUNIDAD ─────────────────────────────────────────
  {
    id: 'comunidad',
    area: 'Comunidad y atención',
    question: '¿Cómo manejas la atención a tus clientes en redes y chat?',
    helper: 'La velocidad de respuesta decide muchas ventas.',
    icon: 'messages-square',
    multiSelect: false,
    options: [
      {
        value: 'manual-saturado',
        label: 'Respondo yo, y a veces no doy abasto',
        hint: 'Mensajes sin contestar',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Empleado agéntico de atención',
            reason: 'Un agente que responde en WhatsApp, web y redes con el tono de tu marca — para que ningún cliente quede sin respuesta.',
            href: '/soluciones-ia',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'equipo-manual',
        label: 'Tengo gente atendiendo, pero sin guion',
        hint: 'Cada quien responde a su manera',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Scripts de interacción + automatización',
            reason: 'Estandarizo cómo tu equipo habla con los clientes y automatizo lo repetitivo para que se enfoquen en cerrar.',
            href: '/soluciones-ia',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'sin-comunidad',
        label: 'Casi no tengo presencia en redes',
        hint: 'Quiero construir comunidad',
        triggers: [
          {
            serviceId: 'builder-marketing',
            title: 'Builder Plus + Marketing',
            reason: 'Publicación programada en IG, FB y LinkedIn desde un solo lugar para empezar a construir comunidad.',
            href: '/services',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'cubierto',
        label: 'Mi atención funciona bien',
        triggers: [],
      },
    ],
  },

  // 5 ─ EQUIPO Y CAPACIDADES (LICENCIAS / PRODUCTIVIDAD) ────────────────────
  {
    id: 'equipo',
    area: 'Equipo y productividad',
    question: '¿Con qué herramientas trabaja tu equipo día a día?',
    helper: 'Las herramientas correctas multiplican la capacidad de cada persona.',
    icon: 'users',
    multiSelect: false,
    options: [
      {
        value: 'gratis-mezcladas',
        label: 'Apps gratuitas y mezcladas',
        hint: 'WhatsApp, Drive personal, Excel suelto…',
        triggers: [
          {
            serviceId: 'microsoft-365-pro',
            title: 'Microsoft 365 Comercio Profesional',
            reason: 'Una suite ofimática corporativa completa: Office instalado en 5 dispositivos, correo y videollamadas bajo tu marca y bien gobernados.',
            href: '/catalogo',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'sin-ia',
        label: 'No usamos IA todavía',
        hint: 'Tareas repetitivas a mano',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Licencias de IA + capacitación',
            reason: 'Una licencia de IA bien aprovechada es el empleado más rentable. Te acompaño a elegirla y a sacarle provecho real.',
            href: '/aprende',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'quiere-automatizar',
        label: 'Hay procesos repetitivos que quiero automatizar',
        hint: 'Cotizaciones, reportes, seguimientos…',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Automatización de procesos con IA',
            reason: 'Lo repetitivo deja de robar horas: cotizaciones, agendamiento y reportes corriendo en segundo plano.',
            href: '/soluciones-ia',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'equipado',
        label: 'Estamos bien equipados',
        triggers: [],
      },
    ],
  },

  // 6 ─ SEGURIDAD Y PROTECCIÓN DE MARCA ────────────────────────────────────
  {
    id: 'seguridad',
    area: 'Seguridad',
    question: '¿Qué tan protegida está tu operación digital?',
    helper: 'Pensar preventivamente cuesta mucho menos que reaccionar tarde.',
    icon: 'shield',
    multiSelect: true,
    options: [
      {
        value: 'sin-ssl',
        label: 'No estoy seguro de tener HTTPS / SSL',
        hint: 'Chrome marca el sitio como "no seguro"',
        triggers: [
          {
            serviceId: 'ssl-dv',
            title: 'Certificado SSL',
            reason: 'El candado que Google exige. Sin él, pierdes confianza y posicionamiento. Imprescindible si recibes cualquier dato.',
            href: '/seguridad',
            severity: 'critico',
          },
        ],
      },
      {
        value: 'sin-backup',
        label: 'No tengo backups automáticos',
        hint: 'Si algo falla, ¿qué pasa?',
        triggers: [
          {
            serviceId: 'codeguard-backup',
            title: 'Backup Automático Diario',
            reason: 'El 41% de los sitios hackeados no tenían backup. Restauración en un clic para dormir tranquilo.',
            href: '/seguridad',
            severity: 'critico',
          },
        ],
      },
      {
        value: 'recibe-ataques',
        label: 'Mi sitio recibe ataques o spam constante',
        hint: 'Bots, intentos de login, scraping…',
        triggers: [
          {
            serviceId: 'security-premium',
            title: 'Seguridad Web Premium',
            reason: 'Firewall anti-hackers, mitigación de bots, protección DDoS y limpieza priorizada. Bloquea ataques antes de que toquen tu sitio.',
            href: '/seguridad',
            severity: 'critico',
          },
        ],
      },
      {
        value: 'datos-sensibles',
        label: 'Manejo información sensible de clientes',
        hint: 'Datos personales, pagos, historiales…',
        triggers: [
          {
            serviceId: 'email-pro',
            title: 'Email Pro (anti-phishing + archivo legal)',
            reason: 'Manejar datos sensibles exige correo blindado: anti-phishing, DMARC y retención legal de 7 años.',
            href: '/seguridad',
            severity: 'atencion',
          },
          { ...DIAGNOSTICO_SOBERANIA, severity: 'atencion' },
        ],
      },
      {
        value: 'protegido',
        label: 'Tengo SSL, backups y firewall al día',
        triggers: [],
      },
    ],
  },

  // 7 ─ IMAGEN DE PRODUCTO / CATÁLOGO ──────────────────────────────────────
  {
    id: 'imagen',
    area: 'Imagen y catálogo',
    question: '¿Cómo se ve tu oferta de cara al cliente?',
    helper: 'Lo que entra por los ojos también vende.',
    icon: 'paintbrush',
    multiSelect: false,
    options: [
      {
        value: 'sin-catalogo',
        label: 'No tengo un catálogo visual ordenado',
        hint: 'Fotos sueltas en el celular',
        triggers: [
          {
            serviceId: 'website-builder-plus',
            title: 'Catálogo web profesional',
            reason: 'Un catálogo visual ordenado, con tu marca y fácil de actualizar — la diferencia entre improvisar y vender.',
            href: '/services',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'mejorar-marca',
        label: 'Mi imagen de marca necesita un salto',
        hint: 'Logo, identidad, sitio a la altura',
        triggers: [
          {
            serviceId: 'disenio-custom',
            title: 'Ecosistema SOTA (diseño a medida)',
            reason: 'Tu marca tratada como un producto: investigación, identidad y desarrollo de extremo a extremo.',
            href: '/disenos',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'ecommerce',
        label: 'Quiero vender en línea (e-commerce)',
        hint: 'Tienda con pagos',
        triggers: [
          {
            serviceId: 'business-nvme',
            title: 'WordPress Business NVMe',
            reason: 'Velocidad NVMe para una tienda que carga rápido y no pierde ventas por lentitud.',
            href: '/services',
            severity: 'oportunidad',
          },
          ADVISORY,
        ],
      },
      {
        value: 'imagen-ok',
        label: 'Mi imagen ya está cuidada',
        triggers: [],
      },
    ],
  },

  // 8 ─ MOMENTO / URGENCIA (cualificación comercial) ───────────────────────
  {
    id: 'momento',
    area: 'Tu momento',
    question: '¿En qué momento está tu empresa para invertir en esto?',
    helper: 'Sé franco — esto define cómo te acompaño.',
    icon: 'compass',
    multiSelect: false,
    options: [
      {
        value: 'explorando',
        label: 'Explorando, aprendiendo todavía',
        hint: 'Quiero entender antes de decidir',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Recursos para aprender',
            reason: 'Empieza por entender. En la sección de aprendizaje encuentras la hoja de ruta y el encuadre de inversión.',
            href: '/aprende',
            severity: 'oportunidad',
          },
        ],
      },
      {
        value: 'listo-pronto',
        label: 'Listo para mover algo este mes',
        hint: 'Tengo un problema concreto que resolver',
        triggers: [
          {
            serviceId: 'advisory',
            title: 'Conversación estratégica',
            reason: 'Tienes claridad y urgencia: agendemos una conversación para priorizar y ejecutar.',
            href: '/contact',
            severity: 'atencion',
          },
        ],
      },
      {
        value: 'invertir-grande',
        label: 'Quiero invertir en grande y a largo plazo',
        hint: 'Busco un socio, no un proveedor',
        triggers: [
          {
            serviceId: 'disenio-custom',
            title: 'Ecosistema SOTA · acompañamiento integral',
            reason: 'Este es el perfil con el que mejor trabajo: socios serios. Construyamos algo a la altura de tu ambición.',
            href: '/disenos',
            severity: 'atencion',
          },
          { ...DIAGNOSTICO_SOBERANIA, severity: 'oportunidad' },
        ],
      },
    ],
  },
];

/** Respuestas crudas que viajan del wizard al motor. value(s) por step.id. */
export type DiagnosticoAnswers = Record<string, string[]>;

export interface DiagnosticoResult {
  recommendations: Recommendation[];
  /** Total de áreas con al menos una necesidad detectada. */
  areasWithNeeds: number;
  /** ¿Algún hallazgo crítico (seguridad)? */
  hasCritical: boolean;
  /** ¿El caso amerita asesoría profesional? */
  needsAdvisory: boolean;
}

const SEVERITY_RANK: Record<Severity, number> = { critico: 0, atencion: 1, oportunidad: 2 };

/**
 * Motor de recomendación PURO. Recibe respuestas y devuelve recomendaciones
 * deduplicadas y ordenadas por severidad. Usado por frontend y backend por igual.
 */
export function computeDiagnostico(answers: DiagnosticoAnswers): DiagnosticoResult {
  const collected: Recommendation[] = [];
  const areasTouched = new Set<string>();

  for (const step of DIAGNOSTICO_STEPS) {
    const selected = answers[step.id] ?? [];
    for (const opt of step.options) {
      if (!selected.includes(opt.value)) continue;
      if (opt.triggers.length > 0) areasTouched.add(step.id);
      for (const rec of opt.triggers) collected.push(rec);
    }
  }

  // Dedupe por serviceId+title, conservando la severidad más alta.
  const byKey = new Map<string, Recommendation>();
  for (const rec of collected) {
    const key = `${rec.serviceId}::${rec.title}`;
    const existing = byKey.get(key);
    if (!existing || SEVERITY_RANK[rec.severity] < SEVERITY_RANK[existing.severity]) {
      byKey.set(key, rec);
    }
  }

  const recommendations = [...byKey.values()].sort(
    (a, b) => SEVERITY_RANK[a.severity] - SEVERITY_RANK[b.severity],
  );

  return {
    recommendations,
    areasWithNeeds: areasTouched.size,
    hasCritical: recommendations.some((r) => r.severity === 'critico'),
    needsAdvisory: recommendations.some((r) => r.serviceId === 'advisory'),
  };
}

export const SEVERITY_LABELS: Record<Severity, string> = {
  critico: 'Atención urgente',
  atencion: 'Conviene resolver',
  oportunidad: 'Oportunidad de mejora',
};
