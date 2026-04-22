// Gano Digital — Catalog data (SOTA 2026 Edition)
// Precios numéricos en USD (GoDaddy Reseller base)

window.GANO_PRODUCTS = [
  // ======= HOSTING WEB CPANEL =======
  {
    id: 'cpanel-deluxe',
    category: 'hostingwebcpanel',
    objectives: ['blog', 'portafolio'],
    tier: 'Hosting Web cPanel',
    name: 'Hosting Deluxe',
    icon: 'fa-server',
    tagline: '1 sitio web, almacenamiento generoso y panel de control estándar.',
    pitch: 'Ideal si prefieres gestionar tu hosting tradicionalmente con cPanel y no requieres recursos de servidor dedicados.',
    monthly: 9.99,
    yearly: 119.88,
    badge: null,
    pfid: '459',
    features: [
      { label: 'Sitios web', value: '1 incluido' },
      { label: 'Almacenamiento', value: 'Ilimitado*' },
      { label: 'Bases de datos', value: '1 MySQL' },
      { label: 'SSL', value: 'Gratuito' },
    ],
    specs: {
      panel: 'cPanel estándar',
      soporte: '24/7',
    },
    bestFor: ['Sitios personales', 'Pymes iniciales']
  },
  {
    id: 'cpanel-ultimate',
    category: 'hostingwebcpanel',
    objectives: ['agencia', 'corporativo'],
    tier: 'Hosting Web cPanel',
    name: 'Hosting Ultimate',
    icon: 'fa-server',
    tagline: 'Sitios y bases ilimitadas, correo incluido y SSL gratuito.',
    pitch: 'El tope de gama en cPanel compartido. Escala mejor que el Deluxe estándar y permite alojar múltiples proyectos.',
    monthly: 12.99,
    yearly: 155.88,
    badge: 'Popular',
    pfid: '459',
    features: [
      { label: 'Sitios web', value: 'Ilimitados' },
      { label: 'Almacenamiento', value: 'Ilimitado*' },
      { label: 'Bases de datos', value: 'Ilimitadas' },
      { label: 'SSL', value: 'Gratuito' },
    ],
    specs: {
      panel: 'cPanel estándar',
      soporte: '24/7',
    },
    bestFor: ['Agencias', 'Múltiples sitios pyme']
  },

  // ======= WEB HOSTING PLUS =======
  {
    id: 'whp-inicio',
    category: 'webhostingplus',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Web Hosting Plus',
    name: 'WHP Inicio',
    icon: 'fa-rocket',
    tagline: 'El poder de un VPS con la facilidad de cPanel.',
    pitch: 'Recursos dedicados aislados sin la complejidad técnica de administrar un servidor raíz.',
    monthly: 21.99,
    yearly: 263.88,
    badge: null,
    pfid: '459',
    features: [
      { label: 'Almacenamiento', value: '100 GB NVMe' },
      { label: 'RAM', value: '4 GB' },
      { label: 'vCPU', value: '2 Cores' },
      { label: 'Sitios web', value: 'Hasta 50' },
    ],
    specs: {
      io: 'NVMe Gen4',
      panel: 'cPanel con recursos dedicados',
    },
    bestFor: ['E-commerce inicial', 'Corporativo de alto tráfico']
  },
  {
    id: 'whp-mejora',
    category: 'webhostingplus',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Web Hosting Plus',
    name: 'WHP Mejora',
    icon: 'fa-rocket',
    tagline: 'Más potencia y capacidad para e-commerce en crecimiento.',
    pitch: 'El doble de recursos de CPU y RAM para soportar picos de tráfico en tiendas online o foros.',
    monthly: 38.99,
    yearly: 467.88,
    badge: 'Recomendado',
    pfid: '459',
    features: [
      { label: 'Almacenamiento', value: '200 GB NVMe' },
      { label: 'RAM', value: '8 GB' },
      { label: 'vCPU', value: '4 Cores' },
      { label: 'Sitios web', value: 'Hasta 100' },
    ],
    specs: {
      io: 'NVMe Gen4',
      panel: 'cPanel con recursos dedicados',
    },
    bestFor: ['E-commerce activo', 'Portales de noticias']
  },
  {
    id: 'whp-crecimiento',
    category: 'webhostingplus',
    objectives: ['corporativo', 'ecommerce', 'agencia'],
    tier: 'Web Hosting Plus',
    name: 'WHP Crecimiento',
    icon: 'fa-rocket',
    tagline: 'Capacidad seria para agencias y operaciones robustas.',
    pitch: '16 GB de RAM y 8 cores de CPU para sitios web de misión crítica que no pueden permitirse latencia.',
    monthly: 54.99,
    yearly: 659.88,
    badge: null,
    pfid: '459',
    features: [
      { label: 'Almacenamiento', value: '300 GB NVMe' },
      { label: 'RAM', value: '16 GB' },
      { label: 'vCPU', value: '8 Cores' },
      { label: 'Sitios web', value: 'Hasta 150' },
    ],
    specs: {
      io: 'NVMe Gen4',
      panel: 'cPanel con recursos dedicados',
    },
    bestFor: ['Agencias', 'Sistemas pesados en PHP']
  },
  {
    id: 'whp-expansion',
    category: 'webhostingplus',
    objectives: ['agencia', 'ecommerce'],
    tier: 'Web Hosting Plus',
    name: 'WHP Expansión',
    icon: 'fa-rocket',
    tagline: 'El máximo rendimiento antes de pasar a un Dedicado puro.',
    pitch: 'Recursos masivos (32 GB RAM / 16 Cores) orquestados mediante un cPanel fácil de usar.',
    monthly: 76.99,
    yearly: 923.88,
    badge: null,
    pfid: '459',
    features: [
      { label: 'Almacenamiento', value: '400 GB NVMe' },
      { label: 'RAM', value: '32 GB' },
      { label: 'vCPU', value: '16 Cores' },
      { label: 'Sitios web', value: 'Hasta 200' },
    ],
    specs: {
      io: 'NVMe Gen4',
      panel: 'cPanel con recursos dedicados',
    },
    bestFor: ['Multi-tiendas', 'Operaciones a gran escala']
  },

  // ======= WORDPRESS ADMINISTRADO =======
  {
    id: 'wp-basico',
    category: 'wordpressadministrado',
    objectives: ['blog', 'portafolio'],
    tier: 'WordPress Administrado',
    name: 'WP Básico',
    icon: 'fa-wordpress',
    tagline: '1 sitio WordPress con actualizaciones automáticas.',
    pitch: 'Ideal cuando no tienes equipo técnico interno. Nosotros actualizamos el core, plugins y protegemos el entorno.',
    monthly: 5.99,
    yearly: 71.88,
    badge: null,
    pfid: '457',
    features: [
      { label: 'Sitios', value: '1 WordPress' },
      { label: 'Almacenamiento', value: '20 GB NVMe' },
      { label: 'Backups', value: 'Diarios automáticos' },
      { label: 'Seguridad', value: 'Análisis de malware' },
    ],
    specs: {
      cache: 'CDN + Caché básica',
      setup: 'Preinstalado',
    },
    bestFor: ['Blog personal', 'Portafolios']
  },
  {
    id: 'wp-pro',
    category: 'wordpressadministrado',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'WordPress Administrado',
    name: 'WP Pro',
    icon: 'fa-wordpress',
    tagline: 'Entornos de staging, mayor velocidad y seguridad avanzada.',
    pitch: 'Con staging incluido, puedes probar cambios sin romper tu sitio en vivo. Mayor almacenamiento y Object Cache.',
    monthly: 9.99,
    yearly: 119.88,
    badge: 'Popular',
    pfid: '457',
    features: [
      { label: 'Sitios', value: 'Hasta 3 WordPress' },
      { label: 'Staging', value: 'Entorno de pruebas 1-clic' },
      { label: 'Caché', value: 'Object Cache Redis' },
      { label: 'Almacenamiento', value: 'Ilimitado*' },
    ],
    specs: {
      cache: 'Redis Object Cache',
      setup: 'Herramientas Pro',
    },
    bestFor: ['Pymes', 'Desarrolladores freelance']
  },
  {
    id: 'wp-developer',
    category: 'wordpressadministrado',
    objectives: ['agencia'],
    tier: 'WordPress Administrado',
    name: 'WP Developer',
    icon: 'fa-wordpress',
    tagline: 'Panel multi-sitio diseñado para agencias y freelancers.',
    pitch: 'Gestiona docenas de clientes desde un solo dashboard con herramientas automatizadas de migración y clonación.',
    monthly: 14.99,
    yearly: 179.88,
    badge: null,
    pfid: '457',
    features: [
      { label: 'Sitios', value: 'Ilimitados' },
      { label: 'Panel', value: 'Gestión centralizada' },
      { label: 'Migración', value: 'Herramienta 1-clic' },
      { label: 'Backups', value: 'Diarios y a demanda' },
    ],
    specs: {
      cache: 'Redis + Global CDN',
      setup: 'Panel Reseller WP',
    },
    bestFor: ['Agencias creativas', 'Estudios de diseño']
  },

  // ======= SERVIDORES VPS (ESTÁNDAR) =======
  {
    id: 'vps-1',
    category: 'servidoresvps',
    objectives: ['desarrollo', 'blog'],
    tier: 'VPS Estándar',
    name: 'VPS 1 vCPU / 2 GB',
    icon: 'fa-microchip',
    tagline: 'Acceso root completo para tus primeros despliegues.',
    pitch: 'Un sandbox perfecto para desarrolladores. KVM puro, IP dedicada y control total sobre el sistema operativo.',
    monthly: 4.99,
    yearly: 59.88,
    badge: null,
    pfid: 'domain_search', // VPS uses external URL
    features: [
      { label: 'vCPU', value: '1 Core' },
      { label: 'RAM', value: '2 GB' },
      { label: 'Almacenamiento', value: '40 GB NVMe SSD' },
      { label: 'Tráfico', value: '1 TB' },
    ],
    specs: {
      virtualization: 'KVM',
      access: 'Root SSH',
    },
    bestFor: ['Testing', 'Proyectos pequeños']
  },
  {
    id: 'vps-2',
    category: 'servidoresvps',
    objectives: ['corporativo'],
    tier: 'VPS Estándar',
    name: 'VPS 2 vCPU / 4 GB',
    icon: 'fa-microchip',
    tagline: 'Balance perfecto para aplicaciones de tráfico medio.',
    pitch: 'Más memoria y CPU para levantar bases de datos pequeñas o contenedores Docker.',
    monthly: 25.99,
    yearly: 311.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '2 Cores' },
      { label: 'RAM', value: '4 GB' },
      { label: 'Almacenamiento', value: '100 GB NVMe SSD' },
      { label: 'Tráfico', value: '2 TB' },
    ],
    specs: {
      virtualization: 'KVM',
      access: 'Root SSH',
    },
    bestFor: ['Apps internas', 'Docker básico']
  },
  {
    id: 'vps-4',
    category: 'servidoresvps',
    objectives: ['ecommerce', 'corporativo'],
    tier: 'VPS Estándar',
    name: 'VPS 4 vCPU / 8 GB',
    icon: 'fa-microchip',
    tagline: 'Capacidad en serio para tiendas online y APIs.',
    pitch: 'El estándar de la industria para operaciones comerciales que necesitan infraestructura aislada y segura.',
    monthly: 50.99,
    yearly: 611.88,
    badge: 'Recomendado',
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '4 Cores' },
      { label: 'RAM', value: '8 GB' },
      { label: 'Almacenamiento', value: '200 GB NVMe SSD' },
      { label: 'Tráfico', value: '3 TB' },
    ],
    specs: {
      virtualization: 'KVM',
      access: 'Root SSH',
    },
    bestFor: ['Tiendas e-commerce', 'Bases de datos medianas']
  },
  {
    id: 'vps-8',
    category: 'servidoresvps',
    objectives: ['agencia', 'ecommerce'],
    tier: 'VPS Estándar',
    name: 'VPS 8 vCPU / 16 GB',
    icon: 'fa-microchip',
    tagline: 'Para alto tráfico y orquestación de múltiples servicios.',
    pitch: 'Suficiente músculo para gestionar clústeres, alto tráfico web o servidores de bases de datos pesadas.',
    monthly: 87.99,
    yearly: 1055.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '8 Cores' },
      { label: 'RAM', value: '16 GB' },
      { label: 'Almacenamiento', value: '400 GB NVMe SSD' },
      { label: 'Tráfico', value: '4 TB' },
    ],
    specs: {
      virtualization: 'KVM',
      access: 'Root SSH',
    },
    bestFor: ['Alto tráfico', 'Backends complejos']
  },
  {
    id: 'vps-16',
    category: 'servidoresvps',
    objectives: ['enterprise'],
    tier: 'VPS Estándar',
    name: 'VPS 16 vCPU / 32 GB',
    icon: 'fa-microchip',
    tagline: 'Cargas críticas empresariales y procesamiento masivo.',
    pitch: 'El tope de la línea estándar. Cuando el rendimiento es innegociable.',
    monthly: 146.99,
    yearly: 1763.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '16 Cores' },
      { label: 'RAM', value: '32 GB' },
      { label: 'Almacenamiento', value: '800 GB NVMe SSD' },
      { label: 'Tráfico', value: '5 TB' },
    ],
    specs: {
      virtualization: 'KVM',
      access: 'Root SSH',
    },
    bestFor: ['Aplicaciones Enterprise', 'Big Data local']
  },

  // ======= VPS HIGH PERFORMANCE =======
  {
    id: 'vps-hp-2',
    category: 'vpshighperformance',
    objectives: ['ecommerce'],
    tier: 'VPS High Performance',
    name: 'HP 2 vCPU / 8 GB',
    icon: 'fa-gauge-high',
    tagline: 'Procesadores AMD EPYC de alta frecuencia para mínima latencia.',
    pitch: 'Diseñado para aplicaciones que requieren un altísimo throughput de CPU y almacenamiento NVMe extremo.',
    monthly: 38.99,
    yearly: 467.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '2 Cores (EPYC)' },
      { label: 'RAM', value: '8 GB' },
      { label: 'Almacenamiento', value: '100 GB NVMe Gen4' },
      { label: 'Red', value: 'Red de alta velocidad' },
    ],
    specs: {
      cpu: 'AMD EPYC™',
      io: 'NVMe Gen4',
    },
    bestFor: ['APIs de baja latencia', 'Juegos']
  },
  {
    id: 'vps-hp-4',
    category: 'vpshighperformance',
    objectives: ['ecommerce', 'corporativo'],
    tier: 'VPS High Performance',
    name: 'HP 4 vCPU / 16 GB',
    icon: 'fa-gauge-high',
    tagline: 'Potencia equilibrada para aplicaciones corporativas exigentes.',
    pitch: 'Si tu SaaS necesita más RAM y CPU ultrarrápida, esta es la configuración ideal.',
    monthly: 63.99,
    yearly: 767.88,
    badge: 'Popular',
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '4 Cores (EPYC)' },
      { label: 'RAM', value: '16 GB' },
      { label: 'Almacenamiento', value: '200 GB NVMe Gen4' },
      { label: 'Red', value: 'Red de alta velocidad' },
    ],
    specs: {
      cpu: 'AMD EPYC™',
      io: 'NVMe Gen4',
    },
    bestFor: ['Plataformas SaaS', 'BBDD intensivas']
  },
  {
    id: 'vps-hp-8',
    category: 'vpshighperformance',
    objectives: ['enterprise'],
    tier: 'VPS High Performance',
    name: 'HP 8 vCPU / 32 GB',
    icon: 'fa-gauge-high',
    tagline: 'Rendimiento enterprise para plataformas de misión crítica.',
    pitch: 'El núcleo ideal para infraestructuras escalables, Kubernetes y cargas financieras.',
    monthly: 123.99,
    yearly: 1487.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '8 Cores (EPYC)' },
      { label: 'RAM', value: '32 GB' },
      { label: 'Almacenamiento', value: '400 GB NVMe Gen4' },
      { label: 'Red', value: 'Red de alta velocidad' },
    ],
    specs: {
      cpu: 'AMD EPYC™',
      io: 'NVMe Gen4',
    },
    bestFor: ['Misión crítica', 'Procesamiento de pagos']
  },
  {
    id: 'vps-hp-16',
    category: 'vpshighperformance',
    objectives: ['enterprise', 'agencia'],
    tier: 'VPS High Performance',
    name: 'HP 16 vCPU / 64 GB',
    icon: 'fa-gauge-high',
    tagline: 'Para IA, Machine Learning y procesamiento masivo de datos.',
    pitch: 'Recursos gigantescos a nivel de hardware, sin las restricciones del hardware convencional.',
    monthly: 187.99,
    yearly: 2255.88,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '16 Cores (EPYC)' },
      { label: 'RAM', value: '64 GB' },
      { label: 'Almacenamiento', value: '800 GB NVMe Gen4' },
      { label: 'Red', value: 'Red de alta velocidad' },
    ],
    specs: {
      cpu: 'AMD EPYC™',
      io: 'NVMe Gen4',
    },
    bestFor: ['AI/ML', 'Big Data Analytics']
  },
  {
    id: 'vps-hp-32',
    category: 'vpshighperformance',
    objectives: ['enterprise'],
    tier: 'VPS High Performance',
    name: 'HP 32 vCPU / 128 GB',
    icon: 'fa-gauge-high',
    tagline: 'Nivel datacenter para cargas corporativas máximas.',
    pitch: 'Reemplaza racks enteros de servidores on-premise con este hiper-nodo virtual en la nube.',
    monthly: 255.99,
    yearly: 3071.88,
    badge: 'Premium',
    pfid: 'domain_search',
    features: [
      { label: 'vCPU', value: '32 Cores (EPYC)' },
      { label: 'RAM', value: '128 GB' },
      { label: 'Almacenamiento', value: '1.6 TB NVMe Gen4' },
      { label: 'Red', value: 'Red de alta velocidad' },
    ],
    specs: {
      cpu: 'AMD EPYC™',
      io: 'NVMe Gen4',
    },
    bestFor: ['Clústeres Enterprise', 'Infraestructura Nacional']
  },

  // ======= CERTIFICADOS SSL =======
  {
    id: 'ssl-dv-1',
    category: 'certificadosssl',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Certificados SSL',
    name: 'SSL DV · 1 sitio',
    icon: 'fa-lock',
    tagline: 'Candado HTTPS para dominio único, ideal para pymes.',
    pitch: 'Cifrado SHA-2 de 2048 bits respaldado por la autoridad certificadora de GoDaddy.',
    monthly: null,
    yearly: 33.99,
    badge: null,
    pfid: '75',
    features: [
      { label: 'Dominio', value: '1 dominio protegido' },
      { label: 'Validación', value: 'Dominio (DV)' },
      { label: 'Cifrado', value: 'SHA-2 / 2048-bit' },
    ],
    specs: {
      emision: 'Inmediata',
      sello: 'Sello de sitio seguro',
    },
    bestFor: ['Blogs', 'Sitios estáticos']
  },
  {
    id: 'ssl-dv-5',
    category: 'certificadosssl',
    objectives: ['agencia', 'corporativo'],
    tier: 'Certificados SSL',
    name: 'SSL DV · 5 sitios',
    icon: 'fa-lock',
    tagline: '5 dominios en un solo certificado UCC/SAN.',
    pitch: 'Gestiona la seguridad de múltiples sitios desde un único punto de control.',
    monthly: null,
    yearly: 61.99,
    badge: 'Popular',
    pfid: '75',
    features: [
      { label: 'Dominios', value: 'Hasta 5 dominios' },
      { label: 'Validación', value: 'Dominio (DV)' },
      { label: 'Cifrado', value: 'SHA-2 / 2048-bit' },
    ],
    specs: {
      emision: 'Inmediata',
      sello: 'Sello de sitio seguro',
    },
    bestFor: ['Agencias', 'Grupos corporativos']
  },
  {
    id: 'ssl-dv-wildcard',
    category: 'certificadosssl',
    objectives: ['ecommerce', 'corporativo'],
    tier: 'Certificados SSL',
    name: 'SSL DV Comodín',
    icon: 'fa-lock',
    tagline: 'Protege subdominios ilimitados del dominio principal.',
    pitch: 'Ideal para arquitecturas complejas (ej. api.tudominio, app.tudominio) bajo un mismo certificado.',
    monthly: null,
    yearly: 214.99,
    badge: null,
    pfid: '75',
    features: [
      { label: 'Subdominios', value: 'Ilimitados' },
      { label: 'Validación', value: 'Dominio (DV)' },
      { label: 'Cifrado', value: 'SHA-2 / 2048-bit' },
    ],
    specs: {
      emision: 'Inmediata',
      sello: 'Sello de sitio seguro',
    },
    bestFor: ['Sistemas SaaS', 'Redes de portales']
  },
  {
    id: 'ssl-ev-1',
    category: 'certificadosssl',
    objectives: ['ecommerce', 'enterprise'],
    tier: 'Certificados SSL',
    name: 'SSL EV · 1 sitio',
    icon: 'fa-lock',
    tagline: 'Validación Extendida (EV) para máxima confianza.',
    pitch: 'Muestra el nombre de tu organización en el certificado, pasando por un riguroso chequeo legal.',
    monthly: null,
    yearly: 110.99,
    badge: null,
    pfid: '75',
    features: [
      { label: 'Dominio', value: '1 dominio' },
      { label: 'Validación', value: 'Extendida (EV)' },
      { label: 'Auditoría', value: 'Verificación legal' },
    ],
    specs: {
      emision: '1-5 días',
      garantia: 'Mayor cobertura',
    },
    bestFor: ['Bancos', 'E-commerce grandes']
  },
  {
    id: 'ssl-ev-5',
    category: 'certificadosssl',
    objectives: ['ecommerce', 'enterprise'],
    tier: 'Certificados SSL',
    name: 'SSL EV · 5 sitios',
    icon: 'fa-lock',
    tagline: 'Validación Extendida para 5 dominios corporativos.',
    pitch: 'Protege la red de portales de tu empresa con la mayor validación de identidad disponible.',
    monthly: null,
    yearly: 262.99,
    badge: null,
    pfid: '75',
    features: [
      { label: 'Dominios', value: 'Hasta 5 dominios' },
      { label: 'Validación', value: 'Extendida (EV)' },
      { label: 'Auditoría', value: 'Verificación legal' },
    ],
    specs: {
      emision: '1-5 días',
      garantia: 'Mayor cobertura',
    },
    bestFor: ['Fintechs', 'Holdings']
  },
  {
    id: 'ssl-administrado',
    category: 'certificadosssl',
    objectives: ['corporativo'],
    tier: 'Certificados SSL',
    name: 'SSL Administrado',
    icon: 'fa-lock',
    tagline: 'GoDaddy instala, renueva y monitorea el certificado.',
    pitch: 'No vuelvas a ver un error de "Certificado caducado". Nosotros nos ocupamos de todo el ciclo de vida del SSL.',
    monthly: null,
    yearly: 144.99,
    badge: 'Sin complicaciones',
    pfid: '75',
    features: [
      { label: 'Gestión', value: '100% Administrada' },
      { label: 'Renovación', value: 'Automática' },
      { label: 'Instalación', value: 'Hecha por expertos' },
    ],
    specs: {
      emision: 'Asistida',
      sello: 'Sello de sitio seguro',
    },
    bestFor: ['Pymes', 'Emprendedores']
  },

  // ======= CORREO MICROSOFT 365 =======
  {
    id: 'm365-esencial',
    category: 'correomicrosoft365',
    objectives: ['blog', 'portafolio'],
    tier: 'Correo Microsoft 365',
    name: 'Correo Esencial',
    icon: 'fa-envelope',
    tagline: 'Correo profesional con tu propio dominio.',
    pitch: 'Buzón de nivel corporativo para iniciar tu negocio con una imagen profesional (ej. info@tuempresa.com).',
    monthly: 1.99,
    yearly: 23.88,
    badge: null,
    pfid: '466',
    features: [
      { label: 'Buzón', value: '10 GB' },
      { label: 'Dominio', value: 'Asociado' },
      { label: 'Seguridad', value: 'Anti-spam básico' },
    ],
    specs: {
      ecosistema: 'Microsoft Webmail',
    },
    bestFor: ['Independientes', 'Freelancers']
  },
  {
    id: 'm365-basic',
    category: 'correomicrosoft365',
    objectives: ['corporativo'],
    tier: 'Correo Microsoft 365',
    name: 'M365 Business Basic',
    icon: 'fa-envelope',
    tagline: 'Email corporativo + Teams + 1 TB de OneDrive.',
    pitch: 'Colaboración en la nube para equipos modernos. Incluye versiones web de Word, Excel y PowerPoint.',
    monthly: 5.99,
    yearly: 71.88,
    badge: 'Popular',
    pfid: '466',
    features: [
      { label: 'Buzón', value: '50 GB' },
      { label: 'Almacenamiento', value: '1 TB OneDrive' },
      { label: 'Videollamadas', value: 'Microsoft Teams' },
    ],
    specs: {
      apps: 'Office Web',
      ecosistema: 'Microsoft 365',
    },
    bestFor: ['Pymes', 'Equipos remotos']
  },
  {
    id: 'm365-standard',
    category: 'correomicrosoft365',
    objectives: ['corporativo', 'agencia'],
    tier: 'Correo Microsoft 365',
    name: 'M365 Business Standard',
    icon: 'fa-envelope',
    tagline: 'Suite Office completa para descargar + Teams.',
    pitch: 'Para usuarios que necesitan instalar Word, Excel y PowerPoint en sus computadores de escritorio o Mac.',
    monthly: 12.99,
    yearly: 155.88,
    badge: null,
    pfid: '466',
    features: [
      { label: 'Buzón', value: '50 GB' },
      { label: 'Apps Desktop', value: 'Word, Excel, PPT' },
      { label: 'Almacenamiento', value: '1 TB OneDrive' },
    ],
    specs: {
      apps: 'Escritorio + Web',
      ecosistema: 'Microsoft 365',
    },
    bestFor: ['Oficinas', 'Analistas de datos']
  },
  {
    id: 'm365-premium',
    category: 'correomicrosoft365',
    objectives: ['enterprise', 'corporativo'],
    tier: 'Correo Microsoft 365',
    name: 'M365 Business Premium',
    icon: 'fa-envelope',
    tagline: 'Máxima seguridad con Intune y Defender.',
    pitch: 'Gestión de dispositivos móviles y protección contra amenazas cibernéticas avanzadas para tu equipo.',
    monthly: 22.99,
    yearly: 275.88,
    badge: 'Premium',
    pfid: '466',
    features: [
      { label: 'Seguridad', value: 'Microsoft Defender' },
      { label: 'Dispositivos', value: 'Intune MDM' },
      { label: 'Apps', value: 'Escritorio Premium' },
    ],
    specs: {
      apps: 'Escritorio + Web + Seguridad',
      ecosistema: 'Microsoft 365',
    },
    bestFor: ['Sectores regulados', 'Operaciones IT críticas']
  },

  // ======= SEGURIDAD WEB =======
  {
    id: 'seg-esencial',
    category: 'seguridadweb',
    objectives: ['blog', 'portafolio'],
    tier: 'Seguridad Web (Sucuri)',
    name: 'Seguridad Esencial',
    icon: 'fa-shield-halved',
    tagline: 'Escaneo diario y alertas automáticas de malware.',
    pitch: 'Monitorización pasiva continua. Te avisamos inmediatamente si detectamos código malicioso en tu sitio.',
    monthly: 3.99,
    yearly: 47.88,
    badge: null,
    pfid: '557',
    features: [
      { label: 'Escaneo', value: 'Diario' },
      { label: 'Alertas', value: 'Inmediatas' },
      { label: 'Reputación', value: 'Monitoreo de listas negras' },
    ],
    specs: {
      tipo: 'Monitorización',
      engine: 'Sucuri',
    },
    bestFor: ['Blogs personales', 'Landing pages']
  },
  {
    id: 'seg-deluxe',
    category: 'seguridadweb',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Seguridad Web (Sucuri)',
    name: 'Seguridad Deluxe',
    icon: 'fa-shield-halved',
    tagline: 'Limpieza de malware garantizada y WAF básico.',
    pitch: 'Si tu sitio fue hackeado, nosotros lo limpiamos. Incluye un Firewall de Aplicaciones Web (WAF) para prevenir ataques futuros.',
    monthly: 7.99,
    yearly: 95.88,
    badge: 'Popular',
    pfid: '557',
    features: [
      { label: 'Limpieza', value: 'Garantizada' },
      { label: 'Firewall (WAF)', value: 'Básico' },
      { label: 'Escaneo', value: 'Diario continuo' },
    ],
    specs: {
      tipo: 'Reparación + WAF',
      engine: 'Sucuri',
    },
    bestFor: ['Sitios hackeados', 'Pymes']
  },
  {
    id: 'seg-ultimate',
    category: 'seguridadweb',
    objectives: ['ecommerce', 'enterprise'],
    tier: 'Seguridad Web (Sucuri)',
    name: 'Seguridad Ultimate',
    icon: 'fa-shield-halved',
    tagline: 'Protección proactiva WAF Capa 7, Anti-DDoS y CDN Global.',
    pitch: 'El estándar de blindaje militar. Bloquea inyecciones SQL, ataques DDoS masivos y acelera tu sitio mundialmente.',
    monthly: 23.99,
    yearly: 287.88,
    badge: 'Máximo blindaje',
    pfid: '557',
    features: [
      { label: 'Firewall', value: 'WAF Avanzado' },
      { label: 'Rendimiento', value: 'CDN Global' },
      { label: 'Mitigación', value: 'Anti-DDoS' },
      { label: 'Respuesta', value: 'Prioritaria' },
    ],
    specs: {
      tipo: 'WAF Enterprise + CDN',
      engine: 'Sucuri',
    },
    bestFor: ['E-commerce grandes', 'Instituciones']
  },

  // ======= CREADOR DE SITIOS WEB =======
  {
    id: 'builder-basico',
    category: 'creadordesitiosweb',
    objectives: ['blog', 'portafolio'],
    tier: 'Websites + Marketing',
    name: 'Constructor Básico',
    icon: 'fa-pen-ruler',
    tagline: 'Lanza tu sitio en minutos con plantillas profesionales.',
    pitch: 'Sin código. Usa nuestro editor drag-and-drop con alojamiento integrado y conectividad a redes sociales.',
    monthly: 6.99,
    yearly: 83.88,
    badge: null,
    pfid: 'PENDING_RCC',
    features: [
      { label: 'Editor', value: 'Drag & Drop' },
      { label: 'Plantillas', value: 'Móvil-first' },
      { label: 'Hosting', value: 'Incluido' },
    ],
    specs: {
      curva: 'Fácil (No Code)',
    },
    bestFor: ['Emprendedores sin equipo técnico']
  },
  {
    id: 'builder-comercio',
    category: 'creadordesitiosweb',
    objectives: ['ecommerce'],
    tier: 'Websites + Marketing',
    name: 'Constructor Comercio',
    icon: 'fa-pen-ruler',
    tagline: 'Tienda online completa con pagos e inventario integrados.',
    pitch: 'Vende productos físicos o digitales fácilmente. Gestión de inventario, envíos y carritos abandonados.',
    monthly: 14.99,
    yearly: 179.88,
    badge: 'Popular',
    pfid: 'PENDING_RCC',
    features: [
      { label: 'Tienda', value: 'Completa' },
      { label: 'Pagos', value: 'Integrados' },
      { label: 'Inventario', value: 'Gestión visual' },
    ],
    specs: {
      curva: 'Fácil (No Code)',
    },
    bestFor: ['Pequeños comerciantes', 'Artesanos']
  },

  // ======= MARKETING DIGITAL =======
  {
    id: 'mkt-seo',
    category: 'marketingdigital',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Herramientas de Marketing',
    name: 'SEO Básico',
    icon: 'fa-chart-line',
    tagline: 'Asistente inteligente para posicionarte en Google.',
    pitch: 'Obtén sugerencias automáticas de palabras clave y meta tags para que tus clientes te encuentren más rápido.',
    monthly: 6.99,
    yearly: 83.88,
    badge: null,
    pfid: 'PENDING_RCC',
    features: [
      { label: 'Guía', value: 'Paso a paso' },
      { label: 'Palabras clave', value: 'Sugerencias IA' },
      { label: 'Seguimiento', value: 'Ranking en buscadores' },
    ],
    specs: {
      tipo: 'DIY SEO',
    },
    bestFor: ['Sitios nuevos', 'Mejora de visibilidad']
  },
  {
    id: 'mkt-email-1',
    category: 'marketingdigital',
    objectives: ['corporativo', 'ecommerce'],
    tier: 'Herramientas de Marketing',
    name: 'Email Marketing Starter',
    icon: 'fa-envelope-open-text',
    tagline: 'Envía boletines profesionales a tus clientes.',
    pitch: 'Plantillas de correo responsivas para campañas promocionales. Hasta 500 suscriptores.',
    monthly: 9.99,
    yearly: 119.88,
    badge: null,
    pfid: 'PENDING_RCC',
    features: [
      { label: 'Suscriptores', value: 'Hasta 500' },
      { label: 'Diseño', value: 'Plantillas drag & drop' },
      { label: 'Métricas', value: 'Apertura y clics' },
    ],
    specs: {
      tipo: 'Newsletter',
    },
    bestFor: ['Pymes locales', 'Bloggers']
  },
  {
    id: 'mkt-email-2',
    category: 'marketingdigital',
    objectives: ['ecommerce', 'agencia'],
    tier: 'Herramientas de Marketing',
    name: 'Email Marketing Esencial',
    icon: 'fa-envelope-open-text',
    tagline: 'Automatizaciones, segmentación y A/B testing.',
    pitch: 'Elévate de simples boletines a flujos automatizados de correo para nutrir leads y aumentar conversiones.',
    monthly: 19.99,
    yearly: 239.88,
    badge: 'Popular',
    pfid: 'PENDING_RCC',
    features: [
      { label: 'Suscriptores', value: 'Mayor límite' },
      { label: 'Automatización', value: 'Drip campaigns' },
      { label: 'Análisis', value: 'A/B Testing' },
    ],
    specs: {
      tipo: 'Marketing Automation',
    },
    bestFor: ['E-commerce', 'Campañas B2B']
  },

  // ======= DOMINIOS =======
  {
    id: 'dom-com',
    category: 'dominios',
    objectives: ['blog', 'portafolio', 'corporativo', 'ecommerce', 'agencia'],
    tier: 'Registro de Dominios',
    name: '.com',
    icon: 'fa-globe',
    tagline: 'El dominio más reconocido del mundo para tu marca.',
    pitch: 'Registra la matrícula digital más valiosa de internet. Incluye privacidad WHOIS gratuita.',
    monthly: null,
    yearly: 9.99,
    badge: 'Más buscado',
    pfid: 'domain_search',
    features: [
      { label: 'Reconocimiento', value: 'Global' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['Proyectos con proyección internacional']
  },
  {
    id: 'dom-co',
    category: 'dominios',
    objectives: ['blog', 'portafolio', 'corporativo', 'ecommerce', 'agencia'],
    tier: 'Registro de Dominios',
    name: '.co',
    icon: 'fa-globe',
    tagline: 'La extensión colombiana ideal para posicionamiento local.',
    pitch: 'Demuestra orgullo local o usa un hack de dominio ("compañía"). Extremadamente popular y corto.',
    monthly: null,
    yearly: 19.99,
    badge: 'Colombia',
    pfid: 'domain_search',
    features: [
      { label: 'Identidad', value: 'Local y Global' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['Empresas colombianas', 'Startups']
  },
  {
    id: 'dom-net',
    category: 'dominios',
    objectives: ['corporativo', 'agencia'],
    tier: 'Registro de Dominios',
    name: '.net',
    icon: 'fa-globe',
    tagline: 'Alternativa sólida para proyectos de tecnología y red.',
    pitch: 'Una de las extensiones originales de internet, altamente respetada y con excelente disponibilidad de nombres.',
    monthly: null,
    yearly: 12.99,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'Reconocimiento', value: 'Alto' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['Redes IT', 'Infraestructura']
  },
  {
    id: 'dom-io',
    category: 'dominios',
    objectives: ['agencia', 'ecommerce'],
    tier: 'Registro de Dominios',
    name: '.io',
    icon: 'fa-globe',
    tagline: 'Preferido para startups tecnológicas y productos SaaS.',
    pitch: 'El identificador estándar para la industria tech. Moderno, corto y con gran disponibilidad.',
    monthly: null,
    yearly: 39.99,
    badge: 'Tech',
    pfid: 'domain_search',
    features: [
      { label: 'Nicho', value: 'Startups / Tech' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['SaaS', 'Empresas tecnológicas']
  },
  {
    id: 'dom-store',
    category: 'dominios',
    objectives: ['ecommerce'],
    tier: 'Registro de Dominios',
    name: '.store',
    icon: 'fa-globe',
    tagline: 'Extensión ideal para tiendas online y marcas de comercio.',
    pitch: 'Dile al mundo exactamente qué haces desde el primer segundo. Perfecto para retail.',
    monthly: null,
    yearly: 2.99,
    badge: 'Oferta',
    pfid: 'domain_search',
    features: [
      { label: 'Propósito', value: 'Comercio electrónico' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['E-commerce', 'Retailers']
  },
  {
    id: 'dom-org',
    category: 'dominios',
    objectives: ['corporativo'],
    tier: 'Registro de Dominios',
    name: '.org',
    icon: 'fa-globe',
    tagline: 'Ideal para organizaciones, fundaciones y proyectos sociales.',
    pitch: 'La extensión de mayor credibilidad para proyectos no gubernamentales y de código abierto.',
    monthly: null,
    yearly: 9.99,
    badge: null,
    pfid: 'domain_search',
    features: [
      { label: 'Credibilidad', value: 'Máxima' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller',
    },
    bestFor: ['ONGs', 'Open Source']
  }
];

// ======= GLOSARIO (inline hover) =======
window.GANO_GLOSSARY = {
  'NVMe Gen4': {
    title: 'NVMe Gen4',
    body: 'Non-Volatile Memory Express sobre PCIe 4.0. Hasta 7.500 MB/s de lectura — 15× más rápido que un SSD SATA tradicional.',
    metric: '7.500 MB/s'
  },
  'WAF Capa 7': {
    title: 'WAF Capa 7',
    body: 'Web Application Firewall que opera en la capa de aplicación del modelo OSI. Bloquea inyecciones SQL y ataques en tiempo real.',
    metric: 'Capa 7 OSI'
  },
  'KVM': {
    title: 'Virtualización KVM',
    body: 'Aislamiento a nivel de kernel. Tus recursos están 100% garantizados y no son compartidos con otros inquilinos (No-Overselling).',
    metric: 'Recursos dedicados'
  },
  'cPanel': {
    title: 'cPanel',
    body: 'El panel de control estándar de la industria web para gestionar correos, bases de datos y archivos fácilmente sin usar la terminal de comandos.',
    metric: 'Estándar global'
  },
  'AMD EPYC™': {
    title: 'Procesadores AMD EPYC™',
    body: 'La arquitectura de procesadores para servidores más potente y eficiente de la actualidad. Ideal para procesos pesados de bases de datos.',
    metric: 'Alto throughput'
  },
};

// ======= FILTROS DE OBJETIVO =======
window.GANO_OBJECTIVES = [
  { id: 'all', label: 'Todos', icon: 'fa-asterisk' },
  { id: 'blog', label: 'Blog', icon: 'fa-feather-pointed' },
  { id: 'portafolio', label: 'Portafolio', icon: 'fa-image' },
  { id: 'corporativo', label: 'Corporativo', icon: 'fa-building' },
  { id: 'ecommerce', label: 'E-commerce', icon: 'fa-store' },
  { id: 'agencia', label: 'Agencia', icon: 'fa-sitemap' },
  { id: 'enterprise', label: 'Enterprise', icon: 'fa-city' },
];

// ======= CATEGORÍAS (Mapeado a functions.php) =======
window.GANO_CATEGORIES = [
  { id: 'all', label: 'Todos', icon: 'fa-asterisk' },
  { id: 'hostingwebcpanel', label: 'Hosting Web cPanel', icon: 'fa-server' },
  { id: 'webhostingplus', label: 'Web Hosting Plus', icon: 'fa-rocket' },
  { id: 'wordpressadministrado', label: 'WordPress Administrado', icon: 'fa-wordpress' },
  { id: 'servidoresvps', label: 'Servidores VPS', icon: 'fa-microchip' },
  { id: 'vpshighperformance', label: 'VPS High Performance', icon: 'fa-gauge-high' },
  { id: 'certificadosssl', label: 'Certificados SSL', icon: 'fa-lock' },
  { id: 'correomicrosoft365', label: 'Correo Microsoft 365', icon: 'fa-envelope' },
  { id: 'seguridadweb', label: 'Seguridad Web', icon: 'fa-shield-halved' },
  { id: 'creadordesitiosweb', label: 'Creador de Sitios Web', icon: 'fa-pen-ruler' },
  { id: 'marketingdigital', label: 'Marketing Digital', icon: 'fa-chart-line' },
  { id: 'dominios', label: 'Dominios', icon: 'fa-globe' },
];

// Format helpers
// Asume que el precio de entrada es en USD (ej. 9.99).
// Formatea con prefijo USD para evitar confusión.
window.formatCOP = function(n) {
  if (n == null) return '—';
  // Formato USD estricto: USD $9.99
  return 'USD $' + n.toFixed(2);
};

// Formato auxiliar si necesitas precios completos (anuales)
window.formatCOPFull = function(n) {
  if (n == null) return '—';
  return 'USD $' + n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
