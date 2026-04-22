// Gano Digital — Catalog data
// Prices in COP. Yearly pricing is what GoDaddy Reseller bills.
// Monthly "saves" figure is computed from yearly-vs-monthly delta.

window.GANO_PRODUCTS = [
  // ======= HOSTING WORDPRESS =======
  {
    id: 'start-wp',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'hosting',
    objectives: ['blog', 'portafolio'],
    tier: 'Entrada · WordPress',
    name: 'Start WP',
    icon: 'fa-rocket',
    tagline: 'Para landing pages, blogs y portafolios. WordPress administrado sobre NVMe.',
    pitch: 'El primer peldaño de tu soberanía digital. Ideal para proyectos que acaban de salir del cajón y necesitan velocidad real sin complicaciones.',
    monthly: 19800,
    yearly: 196000,
    badge: null,
    features: [
      { label: 'Almacenamiento', value: '30 GB NVMe' },
      { label: 'Sitios WP', value: '1' },
      { label: 'Tráfico mensual', value: 'Hasta 25.000 visitas' },
      { label: 'Nodo', value: 'Bogotá' },
      { label: 'Backups', value: 'Diario · 14 días' },
      { label: 'SSL', value: 'Let\'s Encrypt gratis' },
      { label: 'Email profesional', value: '1 cuenta incluida' },
      { label: 'Memoria PHP', value: '256 MB' },
    ],
    specs: {
      cpu: '2 vCPU compartido',
      ram: '2 GB',
      io: 'NVMe Gen4 · 3.500 MB/s',
      tls: 'TLS 1.3',
      waf: 'Básico (OWASP core)',
      sla: '99.9%',
    },
    bestFor: ['Blog personal o corporativo', 'Portafolio creativo', 'Landing de un producto'],
  },
  {
    id: 'pro-managed',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'hosting',
    objectives: ['corporativo', 'blog', 'portafolio'],
    tier: 'Avanzado · WordPress',
    name: 'Pro Managed',
    icon: 'fa-microchip',
    tagline: 'Sitios multipágina con tráfico serio. Staging, CDN y cacheo de objetos.',
    pitch: 'Cuando tu sitio empieza a recibir visitas reales y no puedes permitirte caídas los lunes por la mañana. Incluye ambiente de pruebas para que nada rompa en producción.',
    monthly: 39000,
    yearly: 390000,
    badge: null,
    features: [
      { label: 'Almacenamiento', value: '75 GB NVMe' },
      { label: 'Sitios WP', value: '3' },
      { label: 'Tráfico mensual', value: 'Hasta 150.000 visitas' },
      { label: 'Staging', value: 'Incluido' },
      { label: 'CDN', value: 'Global · 200+ POPs' },
      { label: 'Object Cache', value: 'Redis dedicado' },
      { label: 'Backups', value: 'Cada 12 h · 30 d' },
      { label: 'Emails', value: '5 cuentas' },
    ],
    specs: {
      cpu: '4 vCPU compartido',
      ram: '4 GB',
      io: 'NVMe Gen4 · 5.200 MB/s',
      tls: 'TLS 1.3 · HSTS',
      waf: 'Capa 7 · reglas personalizadas',
      sla: '99.95%',
    },
    bestFor: ['Sitio corporativo multipágina', 'Blog con tráfico creciente', 'Agencia con 2-3 clientes'],
  },
  {
    id: 'business-nvme',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'hosting',
    objectives: ['ecommerce', 'corporativo'],
    tier: 'E-commerce · NVMe Gen4',
    name: 'Business NVMe',
    icon: 'fa-bolt',
    tagline: 'Velocidad crítica para WooCommerce de alta autoridad. SLA 99.95%.',
    pitch: 'Construido para WooCommerce serio. Cada milisegundo ahorrado en checkout es un 1% más de conversión. Aquí ahorramos cientos.',
    monthly: 89000,
    yearly: 890000,
    featured: true,
    badge: 'Popular',
    features: [
      { label: 'Almacenamiento', value: '150 GB NVMe Gen4' },
      { label: 'Sitios WP', value: '10' },
      { label: 'Tráfico mensual', value: 'Hasta 500.000 visitas' },
      { label: 'WAF Capa 7', value: 'Activo · reglas WooCommerce' },
      { label: 'CDN', value: 'Global Premium' },
      { label: 'Object Cache', value: 'Redis dedicado 1 GB' },
      { label: 'Backups', value: 'Cada 6 h · 60 d' },
      { label: 'Emails', value: '20 cuentas' },
    ],
    specs: {
      cpu: '8 vCPU prioritario',
      ram: '8 GB',
      io: 'NVMe Gen4 · 7.500 MB/s',
      tls: 'TLS 1.3 · Post-Q opt-in',
      waf: 'Capa 7 avanzado · bot mitigation',
      sla: '99.95%',
    },
    bestFor: ['Tienda WooCommerce con ≥200 productos', 'Marca con campañas pagas activas', 'Sitio con picos de tráfico'],
  },
  {
    id: 'ultimate',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'hosting',
    objectives: ['agencia', 'corporativo', 'ecommerce'],
    tier: 'Élite · WordPress',
    name: 'Ultimate',
    icon: 'fa-crown',
    tagline: 'Todo incluido: correo, SSL wildcard, monitoreo proactivo y consultoría mensual.',
    pitch: 'Para cuando tu operación digital ya no admite improvisación. Ingeniero SOTA asignado, revisión mensual, y todo el ecosistema servido en bandeja.',
    monthly: 148000,
    yearly: 1480000,
    badge: 'Élite',
    features: [
      { label: 'Almacenamiento', value: 'Ilimitado NVMe' },
      { label: 'Sitios WP', value: 'Ilimitados' },
      { label: 'Tráfico mensual', value: 'Sin tope · fair use' },
      { label: 'SSL Wildcard', value: 'Incluido' },
      { label: 'Consultoría', value: '1 h mensual con ingeniero SOTA' },
      { label: 'Monitoreo', value: '24/7 proactivo' },
      { label: 'Backups', value: 'Cada 4 h · 90 d' },
      { label: 'Emails', value: 'Ilimitados · anti-phishing' },
    ],
    specs: {
      cpu: '16 vCPU dedicado',
      ram: '16 GB',
      io: 'NVMe Gen4 · 7.500 MB/s dedicado',
      tls: 'TLS 1.3 · Post-Q por defecto',
      waf: 'Capa 7 · reglas custom por cliente',
      sla: '99.98%',
    },
    bestFor: ['Agencia con 10+ clientes', 'Marca corporativa con operación crítica', 'Ecosistema multi-tienda'],
  },

  // ======= VPS / DEDICATED =======
  {
    id: 'vps-alpha',
    pfid: 'PENDING_RCC', // TODO: VPS — probablemente cotización directa
    category: 'vps',
    objectives: ['agencia', 'corporativo', 'ecommerce'],
    tier: 'Máxima soberanía',
    name: 'VPS Pro Alpha',
    icon: 'fa-server',
    tagline: 'Nodo dedicado, orquestación manual por ingeniero SOTA asignado.',
    pitch: 'Tu propia fortaleza. Acceso root completo, stack a medida, y un ingeniero SOTA que conoce tu proyecto por nombre.',
    monthly: 250000,
    yearly: 2500000,
    badge: 'SOTA',
    features: [
      { label: 'vCPU', value: '8 cores dedicados' },
      { label: 'RAM', value: '32 GB DDR5' },
      { label: 'Almacenamiento', value: '500 GB NVMe Gen4' },
      { label: 'Ancho de banda', value: '10 TB/mes' },
      { label: 'Acceso root', value: 'Completo · SSH' },
      { label: 'Panel', value: 'cPanel o Plesk (opcional)' },
      { label: 'Ingeniero asignado', value: 'SOTA dedicado' },
      { label: 'SLA', value: '99.99%' },
    ],
    specs: {
      cpu: '8 vCPU AMD EPYC dedicados',
      ram: '32 GB DDR5 ECC',
      io: 'NVMe Gen4 · 7.500 MB/s RAID10',
      tls: 'TLS 1.3 · Post-Q configurable',
      waf: 'A medida · kernel-level opt',
      sla: '99.99%',
    },
    bestFor: ['Operación enterprise', 'SaaS en Colombia', 'Múltiples tiendas WooCommerce'],
  },

  // ======= DOMINIOS =======
  {
    id: 'dom-co',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'dominio',
    objectives: ['blog', 'portafolio', 'corporativo', 'ecommerce', 'agencia'],
    tier: 'Dominios · Reseller',
    name: 'Dominio .co',
    icon: 'fa-globe',
    tagline: 'Registro y renovación de dominios colombianos con privacidad WHOIS incluida.',
    pitch: 'El TLD que grita "hecho en Colombia". Viene con privacidad WHOIS, DNS premium y SSL básico de serie.',
    monthly: null,
    yearly: 89000,
    badge: null,
    features: [
      { label: 'TLD disponibles', value: '.co / .com.co / .digital' },
      { label: 'Privacidad WHOIS', value: 'Incluida gratis' },
      { label: 'DNS premium', value: 'Anycast global' },
      { label: 'SSL básico', value: 'Gratis de por vida' },
      { label: 'Forwarding', value: 'URL + email' },
      { label: 'Transferencia', value: 'Sin costo' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller · acreditado ICANN',
      dns: 'Anycast · 6 POPs globales',
      lock: 'Registry lock disponible',
    },
    bestFor: ['Todo proyecto que quiere identidad colombiana', 'Marca local', 'Complementar hosting'],
  },
  {
    id: 'dom-com',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'dominio',
    objectives: ['blog', 'portafolio', 'corporativo', 'ecommerce', 'agencia'],
    tier: 'Dominios · Reseller',
    name: 'Dominio .com',
    icon: 'fa-earth-americas',
    tagline: 'El clásico internacional. Para marcas que miran más allá del nodo Bogotá.',
    pitch: 'El .com sigue siendo la matrícula más reconocida del planeta. Registra o transfiere sin fricción.',
    monthly: null,
    yearly: 65000,
    badge: null,
    features: [
      { label: 'TLD', value: '.com · .net · .org' },
      { label: 'Privacidad WHOIS', value: 'Incluida' },
      { label: 'DNS premium', value: 'Anycast global' },
      { label: 'SSL básico', value: 'Gratis' },
      { label: 'Renovación', value: 'Hasta 10 años' },
    ],
    specs: {
      registrar: 'GoDaddy Reseller · ICANN',
      dns: 'Anycast global',
    },
    bestFor: ['Marca internacional', 'Startup con proyección global', 'Backup de tu .co'],
  },

  // ======= SEGURIDAD =======
  {
    id: 'ssl-pro',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'seguridad',
    objectives: ['ecommerce', 'corporativo'],
    tier: 'Blindaje · SSL',
    name: 'SSL Wildcard Pro',
    icon: 'fa-shield-halved',
    tagline: 'Certificado EV + Wildcard. Firma criptográfica visible en navegador.',
    pitch: 'No cualquier candadito: Validación Extendida más cobertura Wildcard para todos tus subdominios. La señal de confianza que tu checkout necesita.',
    monthly: null,
    yearly: 340000,
    badge: null,
    features: [
      { label: 'Validación', value: 'Extendida (EV)' },
      { label: 'Cobertura', value: 'Wildcard · *.tudominio.com' },
      { label: 'Cifrado', value: 'SHA-256 · 2048-bit' },
      { label: 'Garantía', value: 'USD $1.750.000' },
      { label: 'Soporte', value: '24/7 por chat' },
      { label: 'Reemisión', value: 'Ilimitada' },
    ],
    specs: {
      ca: 'Sectigo / DigiCert (según emisor)',
      algorithm: 'SHA-256 RSA 2048',
      tls: 'TLS 1.2 / 1.3',
    },
    bestFor: ['E-commerce con pasarela propia', 'Banco / fintech', 'Portal corporativo con login'],
  },
  {
    id: 'waf-pro',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'seguridad',
    objectives: ['ecommerce', 'corporativo', 'agencia'],
    tier: 'Blindaje · WAF',
    name: 'WAF Capa 7 Premium',
    icon: 'fa-lock',
    tagline: 'Firewall de aplicación con reglas curadas por ingenieros SOTA.',
    pitch: 'Bloqueo inteligente de bots, SQL injection, XSS y scraping agresivo. Reglas que evolucionan con las amenazas reales de Colombia.',
    monthly: 45000,
    yearly: 450000,
    badge: null,
    features: [
      { label: 'Nivel OSI', value: 'Capa 7 (aplicación)' },
      { label: 'Reglas', value: 'OWASP Top 10 + custom Gano' },
      { label: 'Bot mitigation', value: 'ML · huella de comportamiento' },
      { label: 'Rate limit', value: 'Por endpoint · configurable' },
      { label: 'DDoS', value: 'Hasta 10 Gbps mitigado' },
      { label: 'Dashboard', value: 'Live · exportable' },
    ],
    specs: {
      engine: 'ModSecurity + ML propietario',
      updates: 'Reglas actualizadas cada 24h',
      latency: '< 3 ms añadido',
    },
    bestFor: ['Tienda con historial de intentos de fraude', 'Sitio con formularios públicos', 'Portal con login'],
  },

  // ======= EMAIL =======
  {
    id: 'email-pro',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'email',
    objectives: ['corporativo', 'ecommerce', 'agencia'],
    tier: 'Correo corporativo',
    name: 'Email Pro',
    icon: 'fa-envelope-open-text',
    tagline: 'Buzones profesionales con tu dominio. Anti-phishing y archivo legal.',
    pitch: '25 GB por buzón, anti-spam agresivo, y retención legal de 7 años para sectores regulados.',
    monthly: 12000,
    yearly: 120000,
    badge: null,
    features: [
      { label: 'Espacio', value: '25 GB por buzón' },
      { label: 'Cuentas incluidas', value: '5 buzones' },
      { label: 'Dominio propio', value: 'Configurado sin costo' },
      { label: 'Protocolo', value: 'IMAP · POP3 · SMTP · Exchange' },
      { label: 'Anti-phishing', value: 'DMARC · DKIM · SPF' },
      { label: 'Móvil', value: 'iOS · Android sincronizado' },
      { label: 'Archivo legal', value: '7 años (opcional)' },
    ],
    specs: {
      platform: 'Microsoft 365 vía Reseller',
      storage: '25 GB/buzón expandible',
      compliance: 'GDPR · Ley 1581 Colombia',
    },
    bestFor: ['Equipo de 5-20 personas', 'Marca que firma contratos digitales', 'E-commerce con atención al cliente'],
  },

  // ======= WEBSITE BUILDER =======
  {
    id: 'builder-marketing',
    pfid: 'PENDING_RCC', // TODO: reemplazar con PFID real del RCC
    category: 'builder',
    objectives: ['blog', 'portafolio'],
    tier: 'Websites + Marketing',
    name: 'Builder Plus',
    icon: 'fa-paintbrush',
    tagline: 'Constructor drag-and-drop con marketing integrado. Sin tocar código.',
    pitch: 'Para cuando necesitas salir al aire esta semana. Constructor visual con SEO automático, email marketing y formularios — todo en una suscripción.',
    monthly: 39000,
    yearly: 390000,
    badge: null,
    features: [
      { label: 'Plantillas', value: '100+ diseños SOTA' },
      { label: 'Constructor', value: 'Drag & drop · móvil-first' },
      { label: 'SEO', value: 'Automático · sitemap dinámico' },
      { label: 'Email marketing', value: '1.000 envíos/mes' },
      { label: 'Formularios', value: 'Ilimitados' },
      { label: 'Social posting', value: 'IG · FB · LinkedIn programados' },
      { label: 'Hosting incluido', value: 'Sí · con dominio gratis 1er año' },
    ],
    specs: {
      engine: 'GoDaddy Websites + Marketing',
      mobile: 'Responsive nativo',
      analytics: 'Google Analytics 4 integrado',
    },
    bestFor: ['Emprendedor sin equipo técnico', 'Profesional independiente', 'Tienda pequeña con < 50 productos'],
  },

  // ======= SERVICIOS GANO =======
  {
    id: 'diagnostico',
    pfid: null, // Servicio propio Gano — va a WhatsApp/formulario
    category: 'servicio',
    objectives: ['agencia', 'corporativo', 'ecommerce'],
    tier: 'Consultoría · Gano Digital',
    name: 'Diagnóstico de Soberanía',
    icon: 'fa-stethoscope',
    tagline: 'Auditoría técnica de tu stack actual. Informe ejecutivo en 72 horas.',
    pitch: 'Antes de migrar a ciegas: un ingeniero SOTA audita tu infraestructura, mide benchmarks reales y entrega un plan de blindaje. Se acredita al contratar cualquier ecosistema.',
    monthly: null,
    yearly: 650000,
    badge: 'Único pago',
    features: [
      { label: 'Duración', value: '72 h · entrega garantizada' },
      { label: 'Alcance', value: 'Hosting · DNS · Seguridad · SEO técnico' },
      { label: 'Benchmarks', value: 'TTFB · LCP · CLS · auditoría WAF' },
      { label: 'Informe', value: 'PDF ejecutivo · 20-30 páginas' },
      { label: 'Reunión', value: '1 h con ingeniero SOTA' },
      { label: 'Acreditable', value: '100% contra ecosistema anual' },
    ],
    specs: {
      deliverable: 'PDF ejecutivo + grabación de reunión',
      tools: 'WebPageTest · Sucuri · propietarios Gano',
    },
    bestFor: ['Sitio existente con problemas de velocidad', 'Migración planificada', 'Auditoría pre-inversión'],
  },
  {
    id: 'disenio-custom',
    pfid: null, // Servicio propio Gano — va a WhatsApp/formulario
    category: 'servicio',
    objectives: ['agencia', 'corporativo', 'ecommerce'],
    tier: 'Servicio · Diseño a medida',
    name: 'Ecosistema SOTA',
    icon: 'fa-diagram-project',
    tagline: 'Diseño y desarrollo WordPress a medida. Monolito curado por Gano.',
    pitch: 'Desde wireframes hasta despliegue. Tu marca tratada como un producto SOTA: investigación, arquitectura, desarrollo, y 3 meses de acompañamiento post-lanzamiento.',
    monthly: null,
    yearly: 8500000,
    badge: 'A medida',
    features: [
      { label: 'Entregables', value: 'Wireframes · UI · desarrollo · deploy' },
      { label: 'Plazo', value: '6-10 semanas' },
      { label: 'Páginas', value: 'Hasta 12 en el scope inicial' },
      { label: 'Integraciones', value: 'CRM · pasarela · analytics' },
      { label: 'Hosting', value: 'Business NVMe incluido 1er año' },
      { label: 'Acompañamiento', value: '3 meses post-launch' },
    ],
    specs: {
      team: 'Diego + 2 ingenieros SOTA',
      stack: 'WordPress + Elementor + custom code',
      delivery: 'Git + staging + reunión semanal',
    },
    bestFor: ['Marca que necesita diferenciación visual', 'Lanzamiento nuevo', 'Rediseño estratégico'],
  },
];

// ======= GLOSARIO (inline hover) =======
window.GANO_GLOSSARY = {
  'NVMe Gen4': {
    title: 'NVMe Gen4',
    body: 'Non-Volatile Memory Express sobre PCIe 4.0. Hasta 7.500 MB/s de lectura — 15× más rápido que un SSD SATA tradicional. Se traduce en cargas WordPress 3-5× más veloces cuando hay muchas queries a base de datos.',
    metric: '7.500 MB/s'
  },
  'WAF Capa 7': {
    title: 'WAF Capa 7',
    body: 'Web Application Firewall que opera en la capa de aplicación del modelo OSI — inspecciona HTTP/HTTPS completo, no solo paquetes de red. Bloquea SQL injection, XSS, CSRF, y bots maliciosos antes de que toquen tu WordPress.',
    metric: 'OWASP Top 10'
  },
  'TLS 1.3': {
    title: 'TLS 1.3',
    body: 'La versión actual del protocolo que cifra HTTPS. Handshake más rápido (1-RTT), cifras obsoletas eliminadas, y resistencia post-cuántica opcional. La única configuración aceptable en 2026.',
    metric: '1-RTT handshake'
  },
  'Post-Cuántico': {
    title: 'Cifrado Post-Cuántico',
    body: 'Algoritmos criptográficos diseñados para resistir ataques de computadoras cuánticas. Gano ofrece Kyber-768 opt-in en el nodo Alpha Bogotá — seguridad que no caduca en 5 años.',
    metric: 'Kyber-768'
  },
  'CDN': {
    title: 'CDN Global',
    body: 'Content Delivery Network — red de servidores distribuidos que sirven tu contenido desde el POP más cercano al visitante. Reduce latencia un 60-80% en audiencias internacionales.',
    metric: '200+ POPs'
  },
  'Object Cache': {
    title: 'Object Cache (Redis)',
    body: 'Caché en memoria RAM para queries repetitivas de WordPress. Evita que cada visita pegue a MySQL. Resultado: sitios que aguantan 10× más tráfico sin degradarse.',
    metric: 'RAM-speed access'
  },
  'Nodo Bogotá': {
    title: 'Nodo Alpha Bogotá',
    body: 'Centro de datos localizado físicamente en Colombia. Latencia <20ms para usuarios locales. Cumple con Ley 1581 de protección de datos personales.',
    metric: '<20ms latencia local'
  },
  'WHOIS': {
    title: 'Privacidad WHOIS',
    body: 'WHOIS es la base de datos pública que lista al dueño de un dominio. Sin privacidad, tu teléfono y dirección quedan expuestos. Gano activa privacidad WHOIS gratis, siempre.',
    metric: 'Datos personales ocultos'
  },
  'SLA 99.95%': {
    title: 'SLA 99.95%',
    body: 'Service Level Agreement. 99.95% de uptime permite máximo 21.6 min de caída al mes. Si incumplimos, se acredita proporcional al tiempo caído.',
    metric: '21.6 min/mes máx'
  },
  'TTFB': {
    title: 'TTFB (Time to First Byte)',
    body: 'Tiempo entre que el navegador pide la página y recibe el primer byte. Google Core Web Vitals recomienda <200ms. Gano promedia 45-80ms en nodo Bogotá.',
    metric: 'Target: <200ms'
  },
  'DDoS': {
    title: 'Mitigación DDoS',
    body: 'Distributed Denial of Service: ataques que saturan tu servidor con tráfico falso. Gano mitiga hasta 10 Gbps en capa de red + filtrado inteligente en capa 7.',
    metric: 'Hasta 10 Gbps'
  },
};

// ======= ONBOARDING TIMELINE =======
window.GANO_TIMELINE = [
  { t: '0 min', title: 'Firma y pago', body: 'Cobro procesado por GoDaddy Reseller en COP. Factura electrónica emitida al instante.', icon: 'fa-file-signature' },
  { t: '2 min', title: 'Aprovisionamiento', body: 'Tu nodo se activa automáticamente en Alpha Bogotá. Credenciales por correo cifrado.', icon: 'fa-bolt' },
  { t: '5 min', title: 'Vinculación de dominio', body: 'DNS configurado por el Agente IA. Propagación acelerada vía Anycast.', icon: 'fa-globe' },
  { t: '10 min', title: 'WordPress listo', body: 'Instalación, SSL, WAF y backups activos. Ya puedes publicar.', icon: 'fa-wordpress', iconBrand: true },
  { t: '24 h', title: 'Llamada de bienvenida', body: 'Diego o un ingeniero SOTA te llama: repasamos objetivos, respondemos dudas.', icon: 'fa-headset' },
  { t: '7 días', title: 'Primer checkpoint', body: 'Revisión de métricas iniciales. Ajustes de cacheo y WAF según tu tráfico real.', icon: 'fa-chart-line' },
  { t: 'Siempre', title: 'Agente IA 24/7', body: 'Gano Agent disponible para cualquier consulta técnica o de facturación.', icon: 'fa-robot' },
];

// ======= FILTROS DE OBJETIVO =======
window.GANO_OBJECTIVES = [
  { id: 'all', label: 'Todos', icon: 'fa-asterisk' },
  { id: 'blog', label: 'Blog', icon: 'fa-feather-pointed' },
  { id: 'portafolio', label: 'Portafolio', icon: 'fa-image' },
  { id: 'corporativo', label: 'Corporativo', icon: 'fa-building' },
  { id: 'ecommerce', label: 'E-commerce', icon: 'fa-store' },
  { id: 'agencia', label: 'Agencia', icon: 'fa-sitemap' },
];

// ======= CATEGORÍAS =======
window.GANO_CATEGORIES = [
  { id: 'all', label: 'Todos', icon: 'fa-asterisk' },
  { id: 'hosting', label: 'Hosting WP', icon: 'fa-microchip' },
  { id: 'vps', label: 'VPS · Servidores', icon: 'fa-server' },
  { id: 'dominio', label: 'Dominios', icon: 'fa-globe' },
  { id: 'seguridad', label: 'Seguridad', icon: 'fa-shield-halved' },
  { id: 'email', label: 'Email', icon: 'fa-envelope' },
  { id: 'builder', label: 'Builder', icon: 'fa-paintbrush' },
  { id: 'servicio', label: 'Servicios Gano', icon: 'fa-handshake' },
];

// Format helpers
window.formatCOP = function(n) {
  if (n == null) return '—';
  if (n >= 1000000) return '$' + (n / 1000000).toFixed(n % 1000000 === 0 ? 0 : 1).replace('.', ',') + 'M';
  if (n >= 1000) return '$' + Math.round(n / 1000) + 'K';
  return '$' + n;
};
window.formatCOPFull = function(n) {
  if (n == null) return '—';
  return '$' + n.toLocaleString('es-CO');
};
