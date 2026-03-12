import React from 'react';
import { CloseIcon, BriefcaseIcon, GlobeAltIcon, ChartBarIcon, SparklesIcon, RocketLaunchIcon, KeyIcon } from './icons';

interface DeploymentGuideProps {
  onClose: () => void;
}

const DeploymentGuide: React.FC<DeploymentGuideProps> = ({ onClose }) => {
  const badgeClass = "inline-block bg-violet-500/10 text-violet-400 text-xs font-semibold px-2 py-0.5 rounded-full ring-1 ring-violet-500/30";

  const Service: React.FC<{ icon: React.ReactNode; title: string; tags: string[]; children: React.ReactNode }> = ({ icon, title, tags, children }) => (
    <li className="flex items-start gap-4">
      <div className="flex-shrink-0 bg-violet-500/10 text-violet-400 rounded-full h-10 w-10 flex items-center justify-center ring-2 ring-violet-500/30">
        {icon}
      </div>
      <div className="flex-grow">
        <strong className="font-semibold text-slate-100 flex items-center gap-2">{title}</strong>
        <div className="flex flex-wrap gap-1 mt-1 mb-2">
          {tags.map(tag => <span key={tag} className={badgeClass}>{tag}</span>)}
        </div>
        <div className="text-slate-400 text-sm">{children}</div>
      </div>
    </li>
  );

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm animate-modal-fade-in"
      onClick={onClose}
      role="dialog"
      aria-modal="true"
      aria-labelledby="services-guide-title"
    >
      <div
        className="bg-slate-800 border border-slate-700/50 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col animate-modal-slide-in"
        onClick={e => e.stopPropagation()}
      >
        <header className="flex items-center justify-between p-4 border-b border-slate-700 flex-shrink-0">
          <h2 id="services-guide-title" className="text-lg font-bold text-white flex items-center gap-3">
            <BriefcaseIcon className="h-6 w-6 text-violet-400" />
            Portafolio de Servicios — Gano Digital
          </h2>
          <button
            onClick={onClose}
            className="p-1 rounded-full text-slate-400 hover:bg-slate-700 hover:text-white transition-colors"
            aria-label="Cerrar portafolio de servicios"
          >
            <CloseIcon className="h-6 w-6" />
          </button>
        </header>

        <main className="p-6 overflow-y-auto">
          <p className="text-sm text-slate-300 mb-6">
            Somos una agencia boutique de transformación digital enfocada en resultados. Impulsamos el crecimiento de empresas latinoamericanas a través de estrategias digitales personalizadas y de alto impacto.
          </p>
          <ol className="space-y-6">
            <Service
              icon={<GlobeAltIcon className="h-5 w-5" />}
              title="Diseño y Desarrollo Web"
              tags={['WordPress', 'WooCommerce', 'Landing Pages']}
            >
              Sitios web corporativos de alto rendimiento, tiendas en línea y landing pages optimizadas para convertir visitantes en clientes.
            </Service>

            <Service
              icon={<ChartBarIcon className="h-5 w-5" />}
              title="SEO y Posicionamiento Orgánico"
              tags={['Auditoría SEO', 'On-page', 'Contenidos']}
            >
              Estrategias SEO integrales para aumentar tu visibilidad en Google, atraer tráfico cualificado y generar leads de forma continua.
            </Service>

            <Service
              icon={<SparklesIcon className="h-5 w-5" />}
              title="Marketing en Redes Sociales"
              tags={['Instagram', 'Facebook', 'LinkedIn']}
            >
              Gestión profesional de perfiles, creación de contenido visual impactante y estrategias de engagement para construir comunidades leales.
            </Service>

            <Service
              icon={<RocketLaunchIcon className="h-5 w-5" />}
              title="Publicidad Digital (PPC)"
              tags={['Google Ads', 'Meta Ads', 'Remarketing']}
            >
              Campañas de publicidad paga optimizadas para maximizar tu ROI, alcanzar a tu audiencia ideal y escalar resultados de manera sostenible.
            </Service>

            <Service
              icon={<KeyIcon className="h-5 w-5" />}
              title="Automatización y CRM"
              tags={['Email Marketing', 'HubSpot', 'ActiveCampaign']}
            >
              Implementación de flujos automatizados, email marketing estratégico y dashboards de analítica para tomar decisiones basadas en datos.
            </Service>
          </ol>
        </main>

        <footer className="p-4 border-t border-slate-700 bg-slate-800/50 flex-shrink-0">
          <p className="text-center text-sm text-slate-300">
            ¿Listo para crecer?{' '}
            <a
              href="https://gano.digital"
              target="_blank"
              rel="noopener noreferrer"
              className="text-violet-400 hover:text-violet-300 font-semibold underline transition-colors"
            >
              gano.digital
            </a>
            {' '}|{' '}
            <a
              href="mailto:hola@gano.digital"
              className="text-violet-400 hover:text-violet-300 font-semibold underline transition-colors"
            >
              hola@gano.digital
            </a>
          </p>
        </footer>
      </div>
    </div>
  );
};

export default DeploymentGuide;
