import React from 'react';
import { SparklesIcon, ChartBarIcon, CopyIcon } from './icons';

const UsageGuide: React.FC = () => {
  const codeBlockClass = "bg-slate-900/70 text-violet-300 font-mono text-xs px-2 py-1 rounded";

  return (
    <div className="bg-slate-800/80 backdrop-blur-sm border border-slate-700/50 rounded-xl shadow-lg p-5 animate-fade-in">
      <h3 className="text-lg font-bold text-violet-400 mb-4 flex items-center">
        <SparklesIcon className="h-6 w-6 mr-2" />
        ¿Cómo usar tu propuesta?
      </h3>
      <ol className="space-y-4 text-slate-300 text-sm">

        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-violet-500/10 text-violet-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-violet-500/30">1</div>
          <div>
            <strong className="font-semibold text-slate-100">Copia la propuesta</strong>
            <p>Haz clic en el botón <strong className="text-slate-100">Copiar</strong> para copiar la propuesta generada al portapapeles. Está en formato Markdown, listo para usar.</p>
          </div>
        </li>

        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-violet-500/10 text-violet-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-violet-500/30">2</div>
          <div>
            <strong className="font-semibold text-slate-100">Personaliza el contenido</strong>
            <p>Adapta la propuesta con el nombre del cliente, datos específicos de su industria y los servicios que mejor se ajusten a sus necesidades. Usa un editor como Notion, Google Docs o Word.</p>
          </div>
        </li>

        <li className="flex items-start gap-4">
          <div className="flex-shrink-0 bg-violet-500/10 text-violet-400 rounded-full h-8 w-8 flex items-center justify-center font-bold ring-2 ring-violet-500/30">3</div>
          <div>
            <strong className="font-semibold text-slate-100">Comparte y convierte</strong>
            <p>Envía la propuesta al cliente por correo o preséntatela en una reunión. Para más información sobre nuestros servicios, visita <a href="https://gano.digital" target="_blank" rel="noopener noreferrer" className={codeBlockClass}>gano.digital</a>.</p>
          </div>
        </li>

      </ol>
      <style>{`
        @keyframes fade-in {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
          animation: fade-in 0.5s ease-out forwards;
        }
      `}</style>
    </div>
  );
};

export default UsageGuide;
