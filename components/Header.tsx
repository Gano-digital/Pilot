import React from 'react';
import { PythonIcon, CloudUploadIcon, PuzzleIcon, BriefcaseIcon } from './icons';

interface HeaderProps {
  onDeployClick: () => void;
  onIntegrationsClick: () => void;
  onPropuestaClick: () => void;
}

const Header: React.FC<HeaderProps> = ({ onDeployClick, onIntegrationsClick, onPropuestaClick }) => {
  return (
    <header className="bg-slate-800/50 backdrop-blur-sm border-b border-slate-700/50 sticky top-0 z-10">
      <div className="container mx-auto px-4 md:px-6 lg:px-8 py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-4">
            <PythonIcon className="h-10 w-10 text-sky-400" />
            <div>
              <h1 className="text-xl md:text-2xl font-bold text-white">Python SiteCloner Generator</h1>
              <p className="text-sm text-slate-400">AI-Powered Python Script Generation</p>
            </div>
          </div>
          <div className="flex items-center gap-2">
            <button
              onClick={onPropuestaClick}
              aria-label="Propuesta comercial Gano Digital"
              className="bg-sky-700 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors"
            >
              <BriefcaseIcon className="h-5 w-5" />
              <span className="hidden sm:inline">Propuesta Comercial</span>
            </button>
            <button
              onClick={onIntegrationsClick}
              aria-label="Integration opportunities"
              className="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors"
            >
              <PuzzleIcon className="h-5 w-5" />
              <span className="hidden sm:inline">Integrations</span>
            </button>
            <button
              onClick={onDeployClick}
              aria-label="Deploy app"
              className="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors"
            >
              <CloudUploadIcon className="h-5 w-5" />
              <span className="hidden sm:inline">Deploy App</span>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;