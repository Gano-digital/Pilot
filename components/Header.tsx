import React from 'react';
import { PythonIcon, CloudUploadIcon, PuzzleIcon } from './icons';

interface HeaderProps {
  onDeployClick: () => void;
  onIntegrationsClick: () => void;
}

const Header: React.FC<HeaderProps> = ({ onDeployClick, onIntegrationsClick }) => {
  return (
    <header className="bg-indigo-950/90 backdrop-blur-sm border-b border-violet-800/40 sticky top-0 z-10 shadow-lg shadow-violet-950/30">
      <div className="container mx-auto px-4 md:px-6 lg:px-8 py-3">
        <div className="flex items-center justify-between gap-4 min-w-0">
          <div className="flex items-center gap-3 min-w-0 flex-shrink-1">
            <PythonIcon className="h-9 w-9 text-violet-400 flex-shrink-0" />
            <div className="min-w-0">
              <h1 className="text-lg md:text-xl font-bold text-white leading-tight truncate">Python SiteCloner Generator</h1>
              <p className="text-xs text-violet-300/70 leading-tight">AI-Powered Python Script Generation</p>
            </div>
          </div>
          <div className="flex items-center gap-2 flex-shrink-0">
            <button
              onClick={onIntegrationsClick}
              aria-label="Integration opportunities"
              className="bg-violet-800/50 hover:bg-violet-700/60 text-violet-200 border border-violet-700/40 hover:border-violet-500/60 px-3 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all duration-200"
            >
              <PuzzleIcon className="h-4 w-4 flex-shrink-0" />
              <span className="hidden sm:inline">Integrations</span>
            </button>
            <button
              onClick={onDeployClick}
              aria-label="Deploy app"
              className="bg-violet-600 hover:bg-violet-500 text-white border border-violet-500/50 hover:border-violet-400/70 px-3 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-all duration-200 shadow-md shadow-violet-900/40"
            >
              <CloudUploadIcon className="h-4 w-4 flex-shrink-0" />
              <span className="hidden sm:inline">Deploy App</span>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;