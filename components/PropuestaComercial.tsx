import React, { useState } from 'react';
import { CloseIcon, BriefcaseIcon, GlobeIcon, KeyIcon, SparklesIcon, CloudUploadIcon, TerminalIcon, PuzzleIcon } from './icons';

interface PropuestaComercialProps {
  onClose: () => void;
}

// ─── Data types ───────────────────────────────────────────────────────────────

interface Bundle {
  id: string;
  emoji: string;
  name: string;
  subtitle: string;
  priceLabel: string;
  savingsLabel: string;
  color: string;
  items: string[];
}

interface HostingPlan {
  name: string;
  monthlyPrice: string;
  annualPrice: string;
  storage: string;
  bandwidth: string;
  emails: string;
  ssl: string;
  backups: string;
  cdn: boolean;
  cicd: boolean;
  ai: boolean;
  support: string;
  highlight?: boolean;
}

interface ServiceGroup {
  id: number;
  emoji: string;
  title: string;
  description: string;
  icon: React.ReactNode;
  items: string[];
}

// ─── Static data ──────────────────────────────────────────────────────────────

const HOSTING_PLANS: HostingPlan[] = [
  {
    name: 'INICIO',
    monthlyPrice: '$28,900',
    annualPrice: '$278,400',
    storage: '10 GB SSD',
    bandwidth: '100 GB/mes',
    emails: '5 cuentas',
    ssl: 'Incluido',
    backups: 'Semanal',
    cdn: false,
    cicd: false,
    ai: false,
    support: 'Tickets (48 h)',
  },
  {
    name: 'NEGOCIO',
    monthlyPrice: '$58,900',
    annualPrice: '$566,400',
    storage: '50 GB SSD NVMe',
    bandwidth: 'Ilimitado',
    emails: '25 cuentas',
    ssl: 'Incluido',
    backups: 'Diario (30 días)',
    cdn: true,
    cicd: true,
    ai: false,
    support: 'Chat + Tickets (24 h)',
    highlight: true,
  },
  {
    name: 'PROFESIONAL',
    monthlyPrice: '$118,900',
    annualPrice: '$1,142,400',
    storage: '150 GB SSD NVMe',
    bandwidth: 'Ilimitado',
    emails: 'Ilimitadas',
    ssl: 'SSL EV',
    backups: 'Diario + Off-site (60 días)',
    cdn: true,
    cicd: true,
    ai: true,
    support: 'Chat + Teléfono (4 h)',
  },
  {
    name: 'ENTERPRISE',
    monthlyPrice: '$298,000',
    annualPrice: '$2,860,800',
    storage: '500 GB SSD NVMe',
    bandwidth: 'Ilimitado + burst',
    emails: 'Ilimitadas',
    ssl: 'EV + OV Multi-dominio',
    backups: 'Continuo + multi-región',
    cdn: true,
    cicd: true,
    ai: true,
    support: 'Gerente dedicado (1 h)',
  },
];

const BUNDLES: Bundle[] = [
  {
    id: 'emprendedor',
    emoji: '🌱',
    name: 'EMPRENDEDOR',
    subtitle: 'Freelancers y negocios unipersonales',
    priceLabel: 'COP $390,000/año',
    savingsLabel: 'Ahorro $78,000 (16 %)',
    color: 'emerald',
    items: [
      '1 dominio .com o .co',
      'Hosting Plan INICIO (1 año)',
      'SSL gratuito',
      '3 correos corporativos',
      'WordPress preinstalado',
      'Plantilla profesional',
      'Tutorial de primeros pasos',
    ],
  },
  {
    id: 'pyme',
    emoji: '💼',
    name: 'PYME DIGITAL',
    subtitle: 'PYMEs y profesionales independientes',
    priceLabel: 'COP $890,000/año',
    savingsLabel: 'Ahorro $266,400 (23 %)',
    color: 'sky',
    items: [
      '1 dominio .com + 1 dominio adicional gratis',
      'Hosting Plan NEGOCIO (1 año)',
      'CDN global + SSL',
      '10 correos corporativos',
      'Sitio web de 5 páginas',
      'Google Analytics configurado',
      'Backups diarios',
    ],
  },
  {
    id: 'negocio',
    emoji: '🚀',
    name: 'NEGOCIO DIGITAL',
    subtitle: 'Empresas con e-commerce o plataformas',
    priceLabel: 'COP $2,490,000/año',
    savingsLabel: 'Ahorro $932,400 (27 %)',
    color: 'violet',
    items: [
      '2 dominios a elección',
      'Hosting Plan PROFESIONAL (1 año)',
      '25 correos corporativos',
      'Sitio web corporativo (10 páginas)',
      'Tienda virtual básica (50 productos)',
      'CI/CD completo + PSE/Wompi/Nequi',
      '1 script IA + Monitoreo 24/7',
    ],
  },
  {
    id: 'enterprise',
    emoji: '🏢',
    name: 'ENTERPRISE TOTAL',
    subtitle: 'Grandes empresas y plataformas SaaS',
    priceLabel: 'COP $6,800,000/año',
    savingsLabel: 'Ahorro $2,860,800 (30 %)',
    color: 'amber',
    items: [
      'Hasta 5 dominios a elección',
      'Hosting Plan ENTERPRISE (1 año)',
      'Correo corporativo ilimitado',
      'Plataforma a medida (a definir)',
      'CI/CD Blue-green + SLA 99.9 %',
      '3 scripts IA personalizados',
      'Gerente de cuenta dedicado',
    ],
  },
  {
    id: 'reseller',
    emoji: '🔁',
    name: 'RESELLER PRO',
    subtitle: 'Agencias y desarrolladores que revenden',
    priceLabel: 'COP $1,200,000/año',
    savingsLabel: 'Ahorro $480,000 (28 %)',
    color: 'rose',
    items: [
      'Panel White-Label con logo propio',
      'Hasta 10 dominios gestionables',
      '10 cuentas hosting Plan INICIO',
      'API gestión DNS',
      'Facturación automática a clientes',
      'Margen de reventa 20–35 %',
      '2 sesiones de capacitación técnica',
    ],
  },
  {
    id: 'ia',
    emoji: '🤖',
    name: 'IA STARTER',
    subtitle: 'Primer paso en automatización inteligente',
    priceLabel: 'COP $450,000 único',
    savingsLabel: 'Todo incluido — sin suscripción',
    color: 'cyan',
    items: [
      '1 script Python con Gemini IA',
      'Documentación completa',
      '2 horas de consultoría',
      'Acceso 3 meses al generador web',
      '1 ajuste/corrección incluido',
    ],
  },
];

const SERVICES: ServiceGroup[] = [
  {
    id: 1,
    emoji: '🌐',
    title: 'Dominios',
    description: 'Registro y gestión con panel DNS programático vía GoDaddy Reseller API.',
    icon: <GlobeIcon className="h-5 w-5" />,
    items: ['.com $42k · .co $68k · .digital $95k · .app $72k · .io $185k · .ai $320k'],
  },
  {
    id: 2,
    emoji: '🏠',
    title: 'Hospedaje Web',
    description: 'WordPress administrado + PHP 8.3 + CDN global + SSL automático + backups.',
    icon: <CloudUploadIcon className="h-5 w-5" />,
    items: ['Inicio $28,900/mes · Negocio $58,900/mes · Pro $118,900/mes · Enterprise $298,000/mes'],
  },
  {
    id: 3,
    emoji: '🛒',
    title: 'Desarrollo Web y E-commerce',
    description: 'Diseño y desarrollo de sitios web con integración PSE, Nequi, Wompi y MercadoPago.',
    icon: <TerminalIcon className="h-5 w-5" />,
    items: [
      'Landing page: $650k–$1.2M',
      'Sitio corporativo (5 p.): $1.8M–$3.5M',
      'Tienda virtual básica: $2.5M–$5M',
      'Web app personalizada: $15M+',
    ],
  },
  {
    id: 4,
    emoji: '🤖',
    title: 'Automatización con IA',
    description: 'Scripts, chatbots y agentes inteligentes con Google Gemini API.',
    icon: <SparklesIcon className="h-5 w-5" />,
    items: [
      'Script básico IA: $280,000',
      'Chatbot WhatsApp + IA: $3,500,000',
      'Automatización RPA: $2,200,000',
      'Licencia mensual generador: $180,000/mes',
    ],
  },
  {
    id: 5,
    emoji: '🔄',
    title: 'DevOps y CI/CD',
    description: 'Pipelines CI/CD, despliegues automáticos, blue-green y monitoreo continuo.',
    icon: <CloudUploadIcon className="h-5 w-5" />,
    items: [
      'Setup CI/CD básico: $450,000',
      'Multi-entorno completo: $980,000',
      'Blue-green deployments: $750,000',
      'Monitoreo 24/7: $320,000/mes',
    ],
  },
  {
    id: 6,
    emoji: '📧',
    title: 'Correo Corporativo',
    description: 'Correo profesional con MX, SPF, DKIM, DMARC — Google Workspace o Microsoft 365.',
    icon: <KeyIcon className="h-5 w-5" />,
    items: [
      'Email Starter (1–5 cuentas): $15,000/mes',
      'Email Business (6–25): $42,000/mes',
      'Google Workspace: $25,000/usuario/mes',
      'Microsoft 365: $32,000/usuario/mes',
    ],
  },
  {
    id: 7,
    emoji: '🔒',
    title: 'Seguridad Digital',
    description: 'Auditorías OWASP, hardening de servidor, monitoreo DNS y escaneo CodeQL.',
    icon: <KeyIcon className="h-5 w-5" />,
    items: ['Auditoría de seguridad: $1,200,000', 'Monitoreo DNS continuo: $320,000/mes'],
  },
  {
    id: 8,
    emoji: '🌍',
    title: 'Portal White-Label (Reseller)',
    description: 'Plataforma multi-tenant para agencias que revenden servicios digitales.',
    icon: <PuzzleIcon className="h-5 w-5" />,
    items: ['Bundle Reseller Pro: $1,200,000/año', 'Margen de reventa: 20–35 %'],
  },
];

const bundleColorMap: Record<string, { bg: string; ring: string; badge: string; accent: string }> = {
  emerald: { bg: 'bg-emerald-500/10', ring: 'ring-emerald-500/30', badge: 'bg-emerald-500/20 text-emerald-300', accent: 'text-emerald-400' },
  sky:     { bg: 'bg-sky-500/10',     ring: 'ring-sky-500/30',     badge: 'bg-sky-500/20 text-sky-300',     accent: 'text-sky-400'     },
  violet:  { bg: 'bg-violet-500/10',  ring: 'ring-violet-500/30',  badge: 'bg-violet-500/20 text-violet-300', accent: 'text-violet-400' },
  amber:   { bg: 'bg-amber-500/10',   ring: 'ring-amber-500/30',   badge: 'bg-amber-500/20 text-amber-300',  accent: 'text-amber-400'  },
  rose:    { bg: 'bg-rose-500/10',    ring: 'ring-rose-500/30',    badge: 'bg-rose-500/20 text-rose-300',    accent: 'text-rose-400'   },
  cyan:    { bg: 'bg-cyan-500/10',    ring: 'ring-cyan-500/30',    badge: 'bg-cyan-500/20 text-cyan-300',    accent: 'text-cyan-400'   },
};

type Tab = 'resumen' | 'servicios' | 'hosting' | 'bundles' | 'mercado';

// ─── Component ────────────────────────────────────────────────────────────────

const PropuestaComercial: React.FC<PropuestaComercialProps> = ({ onClose }) => {
  const [activeTab, setActiveTab] = useState<Tab>('resumen');
  const [expandedService, setExpandedService] = useState<number | null>(null);
  const [expandedBundle, setExpandedBundle] = useState<string | null>(null);

  const tabs: { id: Tab; label: string }[] = [
    { id: 'resumen',   label: '📋 Resumen' },
    { id: 'mercado',   label: '📊 Mercado' },
    { id: 'servicios', label: '🛠️ Servicios' },
    { id: 'hosting',   label: '🏠 Hosting' },
    { id: 'bundles',   label: '📦 Bundles' },
  ];

  const Check = () => <span className="text-emerald-400 flex-shrink-0">✓</span>;
  const Cross = () => <span className="text-slate-600 flex-shrink-0">–</span>;

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-modal-fade-in"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-labelledby="propuesta-title"
    >
      <div
        className="bg-slate-800 border border-slate-700/50 rounded-xl shadow-2xl w-full max-w-3xl max-h-[92vh] flex flex-col animate-modal-slide-in"
        onClick={e => e.stopPropagation()}
      >
        {/* Header */}
        <header className="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
          <h2 id="propuesta-title" className="text-lg font-bold text-white flex items-center gap-3">
            <BriefcaseIcon className="h-6 w-6 text-sky-400" />
            Propuesta Comercial — Gano Digital 🇨🇴
          </h2>
          <button
            onClick={onClose}
            className="p-1 rounded-full text-slate-400 hover:bg-slate-700 hover:text-white transition-colors"
            aria-label="Cerrar propuesta comercial"
          >
            <CloseIcon className="h-6 w-6" />
          </button>
        </header>

        {/* Tabs */}
        <nav className="flex gap-1 px-4 pt-3 pb-0 flex-shrink-0 overflow-x-auto">
          {tabs.map(tab => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex-shrink-0 px-3 py-1.5 rounded-t-lg text-xs font-semibold border-b-2 transition-colors ${
                activeTab === tab.id
                  ? 'bg-slate-700 text-sky-300 border-sky-400'
                  : 'text-slate-400 border-transparent hover:text-slate-200 hover:bg-slate-700/50'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </nav>

        {/* Content */}
        <main className="flex-1 overflow-y-auto p-4 space-y-4">

          {/* ── RESUMEN ── */}
          {activeTab === 'resumen' && (
            <div className="space-y-4">
              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-base mb-2">Resumen Ejecutivo</h3>
                <p className="text-slate-300 text-sm leading-relaxed">
                  Gano Digital ofrece un ecosistema completo de servicios digitales para el mercado colombiano:
                  desde registro de dominios y hospedaje administrado hasta desarrollo con inteligencia artificial,
                  DevOps automatizado y seguridad digital. Todos los precios en <strong className="text-white">COP fijo</strong>,
                  sin sorpresas por TRM.
                </p>
              </div>

              <div className="grid grid-cols-2 gap-3">
                {[
                  { label: 'Crecimiento del mercado TI en Colombia', value: '18–22 %/año' },
                  { label: 'PYMEs colombianas sin presencia web', value: '58 %' },
                  { label: 'E-commerce Colombia 2025', value: 'USD 9.3 mil mill.' },
                  { label: 'Startups activas en Colombia', value: '~15,000' },
                ].map(stat => (
                  <div key={stat.label} className="bg-slate-900/50 rounded-lg p-3 border border-slate-700/50">
                    <div className="text-sky-300 font-bold text-lg">{stat.value}</div>
                    <div className="text-slate-400 text-xs mt-0.5">{stat.label}</div>
                  </div>
                ))}
              </div>

              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-sm mb-3">Garantías Comerciales</h3>
                <ul className="space-y-2">
                  {[
                    '🛡️ Garantía de satisfacción 30 días — reembolso total sin preguntas',
                    '📊 SLA 99.9 % uptime (planes Pro y Enterprise)',
                    '⚡ Dominios activos en 15 min · Hosting en 1 hora',
                    '🇨🇴 Precios fijos en COP — sin variabilidad por TRM',
                    '🔒 Corrección de vulnerabilidades críticas en 48 horas',
                    '📄 Factura electrónica DIAN incluida',
                  ].map(item => (
                    <li key={item} className="text-slate-300 text-sm">{item}</li>
                  ))}
                </ul>
              </div>

              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-sm mb-3">Formas de Pago</h3>
                <div className="flex flex-wrap gap-2">
                  {['PSE', 'Tarjeta Crédito/Débito', 'Nequi', 'Daviplata', 'Cuotas sin interés'].map(p => (
                    <span key={p} className="bg-sky-500/10 text-sky-300 text-xs px-2 py-1 rounded-full ring-1 ring-sky-500/30">{p}</span>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* ── MERCADO ── */}
          {activeTab === 'mercado' && (
            <div className="space-y-4">
              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-sm mb-3">Benchmarking — Competidores en Colombia</h3>
                <div className="overflow-x-auto">
                  <table className="w-full text-xs text-left">
                    <thead>
                      <tr className="text-slate-400 border-b border-slate-700">
                        <th className="pb-2 pr-3 font-semibold">Proveedor</th>
                        <th className="pb-2 pr-3 font-semibold">Hosting/mes</th>
                        <th className="pb-2 pr-3 font-semibold">Dominio .com/año</th>
                        <th className="pb-2 font-semibold">Diferencial</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-700/50">
                      {[
                        { name: 'Hostinger', hosting: '$12,900', domain: '$35,000', diff: 'Precio bajo, soporte limitado' },
                        { name: 'GoDaddy CO', hosting: '$22,900', domain: '$45,000', diff: 'Marca global, DIY' },
                        { name: 'Media Commerce', hosting: '$45,000', domain: '$60,000', diff: 'Soporte local 24/7' },
                        { name: 'Datanauta', hosting: '$35,000', domain: '$55,000', diff: 'Especialistas Colombia' },
                        { name: 'ConverIT', hosting: '$29,900', domain: '$48,000', diff: 'Segmento empresarial' },
                        { name: 'IQ Internet', hosting: '$39,900', domain: '$65,000', diff: 'Enterprise, SLA' },
                        { name: '★ Gano Digital', hosting: '$28,900', domain: '$42,000', diff: 'IA + CI/CD + COP fijo' },
                      ].map((row, i) => (
                        <tr key={i} className={row.name.startsWith('★') ? 'text-sky-300 font-semibold' : 'text-slate-300'}>
                          <td className="py-1.5 pr-3">{row.name}</td>
                          <td className="py-1.5 pr-3">{row.hosting}</td>
                          <td className="py-1.5 pr-3">{row.domain}</td>
                          <td className="py-1.5 text-slate-400 text-xs">{row.diff}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>

              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-sm mb-3">Brechas del Mercado — Oportunidades</h3>
                <ul className="space-y-2">
                  {[
                    { gap: 'IA integrada en servicio base', opp: 'Ningún proveedor colombiano lo ofrece' },
                    { gap: 'CI/CD para PYMEs', opp: 'Solo disponible para enterprise en competidores' },
                    { gap: 'Precios fijos en COP', opp: 'Mayoría cobra en USD con variabilidad TRM' },
                    { gap: 'Bundles todo-en-uno', opp: 'Servicios separados generan fricción y sobrecosto' },
                    { gap: 'Reseller API programática', opp: 'Interfaces manuales en competidores' },
                  ].map((item, i) => (
                    <li key={i} className="flex gap-3 text-sm">
                      <span className="text-emerald-400 flex-shrink-0 mt-0.5">▸</span>
                      <div>
                        <span className="text-slate-200 font-medium">{item.gap}: </span>
                        <span className="text-slate-400">{item.opp}</span>
                      </div>
                    </li>
                  ))}
                </ul>
              </div>

              <div className="bg-slate-900/50 rounded-lg p-4 border border-slate-700/50">
                <h3 className="text-sky-300 font-bold text-sm mb-3">Segmentos Objetivo</h3>
                <div className="grid grid-cols-2 gap-3">
                  {[
                    { seg: 'A — MYPEs', desc: '2.3 M empresas · menos de 20 empleados · Web básica', color: 'emerald' },
                    { seg: 'B — Medianas Empresas', desc: '22,000 empresas · E-commerce · CRM', color: 'sky' },
                    { seg: 'C — Startups y Agencias', desc: '15,000 startups · APIs · CI/CD', color: 'violet' },
                    { seg: 'D — Revendedores', desc: '8,000 potenciales · White-label · márgenes', color: 'amber' },
                  ].map(s => (
                    <div key={s.seg} className="bg-slate-800 rounded-lg p-3 border border-slate-700/50">
                      <div className="text-slate-100 font-semibold text-xs mb-1">{s.seg}</div>
                      <div className="text-slate-400 text-xs">{s.desc}</div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* ── SERVICIOS ── */}
          {activeTab === 'servicios' && (
            <div className="space-y-2">
              <p className="text-slate-400 text-sm">
                Haz clic en cada servicio para ver detalle de precios y alcance.
              </p>
              {SERVICES.map(svc => (
                <div key={svc.id} className="border border-slate-700/50 rounded-lg overflow-hidden">
                  <button
                    onClick={() => setExpandedService(prev => (prev === svc.id ? null : svc.id))}
                    className="w-full flex items-center gap-3 p-3 text-left hover:bg-slate-700/40 transition-colors"
                    aria-expanded={expandedService === svc.id}
                  >
                    <span className="text-xl flex-shrink-0">{svc.emoji}</span>
                    <div className="flex-grow min-w-0">
                      <div className="font-semibold text-slate-100 text-sm">{svc.title}</div>
                      <div className="text-slate-400 text-xs truncate">{svc.description}</div>
                    </div>
                    <span className={`flex-shrink-0 text-slate-400 transition-transform duration-200 ${expandedService === svc.id ? 'rotate-180' : ''}`}>
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </span>
                  </button>
                  {expandedService === svc.id && (
                    <div className="px-4 pb-3 pt-2 bg-slate-900/30 border-t border-slate-700/50">
                      <ul className="space-y-1.5">
                        {svc.items.map((item, i) => (
                          <li key={i} className="flex items-start gap-2 text-slate-300 text-sm">
                            <span className="text-sky-500 mt-0.5 flex-shrink-0">›</span>
                            {item}
                          </li>
                        ))}
                      </ul>
                    </div>
                  )}
                </div>
              ))}
            </div>
          )}

          {/* ── HOSTING ── */}
          {activeTab === 'hosting' && (
            <div className="space-y-3">
              <p className="text-slate-400 text-sm">
                Todos los planes incluyen WordPress administrado, SSL automático y protección DDoS básica. Precios en COP con IVA.
              </p>
              {HOSTING_PLANS.map(plan => (
                <div
                  key={plan.name}
                  className={`rounded-lg p-4 border ${plan.highlight ? 'border-sky-500/50 bg-sky-500/5' : 'border-slate-700/50 bg-slate-900/50'}`}
                >
                  <div className="flex items-center justify-between mb-3">
                    <div>
                      <span className="font-bold text-slate-100 text-sm">{plan.name}</span>
                      {plan.highlight && (
                        <span className="ml-2 text-xs bg-sky-500/20 text-sky-300 px-2 py-0.5 rounded-full ring-1 ring-sky-500/30">
                          Más popular
                        </span>
                      )}
                    </div>
                    <div className="text-right">
                      <div className="text-sky-300 font-bold">COP {plan.monthlyPrice}/mes</div>
                      <div className="text-slate-400 text-xs">COP {plan.annualPrice}/año</div>
                    </div>
                  </div>
                  <div className="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">
                    {[
                      { label: 'Almacenamiento', value: plan.storage },
                      { label: 'Ancho de banda', value: plan.bandwidth },
                      { label: 'Correos', value: plan.emails },
                      { label: 'SSL', value: plan.ssl },
                      { label: 'Backups', value: plan.backups },
                      { label: 'Soporte', value: plan.support },
                    ].map(row => (
                      <div key={row.label} className="flex items-start gap-1">
                        <span className="text-slate-500 flex-shrink-0">{row.label}:</span>
                        <span className="text-slate-300">{row.value}</span>
                      </div>
                    ))}
                    <div className="flex items-center gap-1">{plan.cdn ? <Check /> : <Cross />}<span className="text-slate-300">CDN global</span></div>
                    <div className="flex items-center gap-1">{plan.cicd ? <Check /> : <Cross />}<span className="text-slate-300">CI/CD</span></div>
                    <div className="flex items-center gap-1">{plan.ai ? <Check /> : <Cross />}<span className="text-slate-300">IA Script Generator</span></div>
                  </div>
                </div>
              ))}
              <div className="bg-slate-900/50 rounded-lg p-3 border border-slate-700/50">
                <p className="text-slate-400 text-xs text-center">
                  Descuento del <span className="text-emerald-400 font-semibold">20 %</span> al pagar el plan anual · Factura electrónica DIAN incluida · Activación en menos de 1 hora
                </p>
              </div>
            </div>
          )}

          {/* ── BUNDLES ── */}
          {activeTab === 'bundles' && (
            <div className="space-y-2">
              <p className="text-slate-400 text-sm">
                Paquetes todo-en-uno con descuentos de hasta el 30 % sobre los precios individuales.
              </p>
              {BUNDLES.map(bundle => {
                const colors = bundleColorMap[bundle.color];
                return (
                  <div key={bundle.id} className={`border rounded-lg overflow-hidden ring-1 ${colors.ring} ${colors.bg}`}>
                    <button
                      onClick={() => setExpandedBundle(prev => (prev === bundle.id ? null : bundle.id))}
                      className="w-full flex items-center gap-3 p-3 text-left hover:bg-white/5 transition-colors"
                      aria-expanded={expandedBundle === bundle.id}
                    >
                      <span className="text-2xl flex-shrink-0">{bundle.emoji}</span>
                      <div className="flex-grow min-w-0">
                        <div className="font-bold text-slate-100 text-sm">{bundle.name}</div>
                        <div className="text-slate-400 text-xs">{bundle.subtitle}</div>
                      </div>
                      <div className="text-right flex-shrink-0">
                        <div className={`font-bold text-sm ${colors.accent}`}>{bundle.priceLabel}</div>
                        <div className={`text-xs px-1.5 py-0.5 rounded-full ${colors.badge}`}>{bundle.savingsLabel}</div>
                      </div>
                      <span className={`flex-shrink-0 text-slate-400 transition-transform duration-200 ${expandedBundle === bundle.id ? 'rotate-180' : ''}`}>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor" className="h-4 w-4" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                      </span>
                    </button>
                    {expandedBundle === bundle.id && (
                      <div className="px-4 pb-3 pt-2 border-t border-slate-700/50">
                        <ul className="space-y-1.5">
                          {bundle.items.map((item, i) => (
                            <li key={i} className="flex items-start gap-2 text-slate-300 text-sm">
                              <span className={`mt-0.5 flex-shrink-0 ${colors.accent}`}>✓</span>
                              {item}
                            </li>
                          ))}
                        </ul>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          )}

        </main>

        {/* Footer */}
        <footer className="p-4 border-t border-slate-700 bg-slate-800/50 flex-shrink-0">
          <p className="text-center text-xs text-slate-400">
            Documento completo en{' '}
            <code className="bg-slate-700 px-1 py-0.5 rounded text-sky-300">docs/propuesta-comercial.md</code>
            {' · '}
            <span className="text-slate-300">contacto@gano.digital</span>
            {' · Precios en COP con IVA · Válido 90 días'}
          </p>
        </footer>
      </div>
    </div>
  );
};

export default PropuestaComercial;
